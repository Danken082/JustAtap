<?php

namespace App\Http\Controllers;

use App\Models\Cards;
use App\Models\Product;
use App\Models\ProfileLink;
use App\Models\User;
use App\Models\UserProfile;
use App\Support\CardIdGenerator;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use ZipArchive;

class AdminController extends Controller
{
    public function dashboard(Request $request): View
    {
        $userSearch = trim((string) $request->query('user_search', ''));
        $notifications = $request->user()->unreadNotifications()
            ->latest()
            ->limit(10)
            ->get();

        $users = User::with('profile')
            ->withCount('profile')
            ->when($userSearch !== '', function ($query) use ($userSearch): void {
                $query->where(function ($userQuery) use ($userSearch): void {
                    $userQuery->where('name', 'like', "%{$userSearch}%")
                        ->orWhere('email', 'like', "%{$userSearch}%")
                        ->orWhere('card_id', 'like', "%{$userSearch}%");
                });
            })
            ->latest()
            ->get();

        $profiles = UserProfile::with('user')
            ->withCount('links')
            ->latest()
            ->get();

        $latestLinks = ProfileLink::with('profile.user')
            ->latest()
            ->limit(50)
            ->get();

        $products = Product::with(['colors', 'sizes'])
            ->latest()
            ->get()
            ->map(fn (Product $product) => $product->toCatalogArray())
            ->all();

        // Fetch corporate admins with their card orders
        $corporateAdmins = User::where('is_corporate', true)
            ->with(['cards' => function ($query) {
                $query->orderBy('sort_order')->orderBy('id');
            }])
            ->withCount('cards')
            ->latest()
            ->get();

        return view('admin.dashboard', [
            'users' => $users,
            'profiles' => $profiles,
            'latestLinks' => $latestLinks,
            'products' => $products,
            'userSearch' => $userSearch,
            'corporateAdmins' => $corporateAdmins,
            'notifications' => $notifications,
        ]);
    }

    public function markNotificationRead(Request $request, string $notification): RedirectResponse
    {
        $request->user()->unreadNotifications()
            ->where('id', $notification)
            ->firstOrFail()
            ->markAsRead();

        return redirect()->route('admin.dashboard');
    }

    public function createCorporateAdmin(): View
    {
        return view('admin.corporate-admin-create');
    }

    public function storeCorporateAdmin(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'default_password' => ['required', 'string', 'min:8'],
            'card_count' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'card_numbers' => ['nullable', 'array'],
            'card_numbers.*' => ['required', 'string', 'max:255'],
        ]);

        $cardNumbers = collect($validated['card_numbers'] ?? [])
            ->filter(fn ($cardNumber) => is_string($cardNumber) && trim($cardNumber) !== '')
            ->values()
            ->all();

        $cardCount = isset($validated['card_count']) ? (int) $validated['card_count'] : 0;
        $manualCardNumbers = array_values(array_unique($cardNumbers));

        $corporateAdmin = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'company_name' => $validated['company_name'],
            'is_corporate' => true,
            'card_id' => null,
            'password' => Hash::make($validated['default_password']),
        ]);

        $companyCardId = CardIdGenerator::generateForCompany($validated['company_name']);
        $corporateAdmin->update(['card_id' => $companyCardId]);

        $cardsToCreate = [$companyCardId];

        if ($manualCardNumbers !== []) {
            $cardsToCreate = array_merge($cardsToCreate, $manualCardNumbers);
        }

        if ($manualCardNumbers === [] && $cardCount > 0) {
            $cardsToCreate = array_merge($cardsToCreate, CardIdGenerator::generateMultipleForCompany($validated['company_name'], $cardCount));
        }

        $cardsToCreate = array_values(array_unique($cardsToCreate));

        foreach ($cardsToCreate as $position => $cardId) {
            Cards::create([
                'card_number' => $cardId,
                'name' => $validated['company_name'],
                'purchaser_id' => $corporateAdmin->id,
                'sort_order' => $position + 1,
            ]);
        }

        $displayCardIds = array_values(array_unique($cardsToCreate));
        $successMessage = 'Corporate admin registered. Generated card IDs: '.implode(', ', $displayCardIds).'.';

        return redirect()->route('admin.dashboard')
            ->with('success', $successMessage);
    }

    public function addCardsToCorporateAdmin(Request $request, User $admin): RedirectResponse
    {
        abort_unless($admin->is_corporate, 403, 'User is not a corporate admin');

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:500'],
        ]);

        $quantity = (int) $validated['quantity'];
        $maxSortOrder = (int) Cards::where('purchaser_id', $admin->id)->max('sort_order') ?? 0;

        $generatedCardIds = CardIdGenerator::generateMultipleForCompany($admin->company_name ?? 'CORPORATE', $quantity);

        foreach ($generatedCardIds as $position => $cardId) {
            Cards::create([
                'card_number' => $cardId,
                'name' => $admin->company_name,
                'purchaser_id' => $admin->id,
                'sort_order' => $maxSortOrder + $position + 1,
            ]);
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Added '.$quantity.' new card ID'.($quantity !== 1 ? 's' : '').' to '.$admin->name.': '.implode(', ', $generatedCardIds).'.');
    }

    public function toggleCorporateAdmin(User $admin): RedirectResponse
    {
        abort_unless($admin->is_corporate, 403, 'User is not a corporate admin');

        $admin->update([
            'is_active' => !$admin->is_active,
        ]);

        $status = $admin->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.dashboard')
            ->with('success', 'Corporate admin '.$admin->name.' has been '.$status.'.');
    }

    public function destroyCorporateAdmin(User $admin, DatabaseManager $database): RedirectResponse
    {
        abort_unless($admin->is_corporate, 403, 'User is not a corporate admin');

        $adminName = $admin->name;

        $database->transaction(function () use ($admin): void {
            // Delete all cards associated with this corporate admin
            Cards::where('purchaser_id', $admin->id)->delete();
            // Delete the corporate admin user
            $admin->delete();
        });

        return redirect()->route('admin.dashboard')
            ->with('success', 'Corporate admin '.$adminName.' and all their card data have been deleted.');
    }

    public function duplicateUser(User $user, DatabaseManager $database): RedirectResponse
    {
        $duplicate = $database->transaction(function () use ($user): User {
            $cardId = $this->generateCardNumber();
            $duplicate = $user->replicate();
            $duplicate->name = $user->name.' (Copy)';
            $duplicate->email = $this->uniqueDuplicateEmail($user->email);
            $duplicate->card_id = $cardId;
            $duplicate->save();

            $sourceProfile = $user->profile()->with('links')->first();

            if ($sourceProfile) {
                $profile = $sourceProfile->replicate();
                $profile->user_id = $duplicate->id;
                $profile->save();

                foreach ($sourceProfile->links as $link) {
                    $duplicateLink = $link->replicate();
                    $duplicateLink->user_profile_id = $profile->id;
                    $duplicateLink->save();
                }
            }

            \App\Models\Cards::create([
                'card_number' => $cardId,
                'name' => $sourceProfile?->display_name ?: $duplicate->name,
            ]);

            return $duplicate;
        });

        return redirect()->route('admin.dashboard')
            ->with('success', "User duplicated as {$duplicate->email}.");
    }

    public function deleteUser(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id) {
            abort(403, 'You cannot delete your own admin account.');
        }

        $user->delete();

        return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully.');
    }

    public function toggleProfileBuilder(User $user): RedirectResponse
    {
        $profile = $user->profile()->firstOrCreate([], [
            'display_name' => $user->name,
            'profile_builder_active' => true,
        ]);
        $profile->update(['profile_builder_active' => ! $profile->profile_builder_active]);

        return redirect()->route('admin.dashboard')
            ->with('success', $profile->profile_builder_active ? 'Profile builder activated.' : 'Profile builder deactivated.');
    }

    public function downloadProfileQr(User $user)
    {
        $publicUrl = route('profile.public', ['cardId' => $user->card_id]);
        $qrUrl = $this->buildQrCodeUrl($publicUrl, $user->profile?->avatar_url, $user->profile?->logo_url);
        $filename = preg_replace('/[^A-Za-z0-9._-]+/', '_', $user->name ?: $user->email ?: 'profile').'_profile_qr.png';

        $response = Http::timeout(20)->get($qrUrl);

        if ($response->failed()) {
            abort(500, 'Unable to generate QR code for this profile.');
        }

        return response($response->body())
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }

    public function downloadSelectedProfileQrs(Request $request)
    {
        $userIds = $request->input('user_ids', []);
        $userIds = is_array($userIds) ? array_values(array_unique(array_filter($userIds, 'is_numeric'))) : [];

        if ($userIds === []) {
            return back()->with('error', 'Select at least one user to download QR codes.');
        }

        $users = User::whereIn('id', $userIds)->get();

        if ($users->isEmpty()) {
            return back()->with('error', 'No valid users were selected.');
        }

        if (! class_exists('ZipArchive')) {
            abort(500, 'ZIP support is not enabled in this PHP environment.');
        }

        $zipTempPath = tempnam(sys_get_temp_dir(), 'profile_qr_');
        $zip = new ZipArchive();

        if ($zip->open($zipTempPath, ZipArchive::OVERWRITE | ZipArchive::CREATE) !== true) {
            abort(500, 'Unable to create QR archive.');
        }

        foreach ($users as $user) {
            $publicUrl = route('profile.public', ['cardId' => $user->card_id]);
            $qrUrl = $this->buildQrCodeUrl($publicUrl, $user->profile?->avatar_url, $user->profile?->logo_url);
            $filename = preg_replace('/[^A-Za-z0-9._-]+/', '_', $user->name ?: $user->email ?: 'profile').'_profile_qr.png';

            $response = Http::timeout(20)->get($qrUrl);
            if ($response->successful()) {
                $zip->addFromString($filename, $response->body());
            }
        }

        $zip->close();

        return response()->download($zipTempPath, 'profile_qr_download.zip')->deleteFileAfterSend(true);
    }

    private function buildQrCodeUrl(string $publicUrl, ?string $avatarUrl = null, ?string $logoUrl = null): string
    {
        $baseUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=1000x1000&format=png&data='.urlencode($publicUrl);
        $centerImage = ! empty($avatarUrl) ? $avatarUrl : $logoUrl;

        if (! empty($centerImage)) {
            $baseUrl .= '&ecc=M&margin=10&logo='.urlencode($centerImage);
        }

        return $baseUrl;
    }

    private function generateCardNumber(): string
    {
        $next = 1;

        while (true) {
            $candidate = 'ID-'.date('Y').'-'.str_pad((string) $next, 6, '0', STR_PAD_LEFT);

            if (! User::where('card_id', $candidate)->exists() && ! \App\Models\Cards::where('card_number', $candidate)->exists()) {
                return $candidate;
            }

            $next++;
        }
    }

    private function uniqueDuplicateEmail(string $email): string
    {
        [$localPart, $domain] = array_pad(explode('@', $email, 2), 2, 'example.com');
        $candidate = $localPart.'.copy@'.$domain;
        $suffix = 2;

        while (User::where('email', $candidate)->exists()) {
            $candidate = $localPart.'.copy'.$suffix.'@'.$domain;
            $suffix++;
        }

        return $candidate;
    }
}
