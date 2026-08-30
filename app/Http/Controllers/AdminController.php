<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProfileLink;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(Request $request): View
    {
        $userSearch = trim((string) $request->query('user_search', ''));

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

        return view('admin.dashboard', [
            'users' => $users,
            'profiles' => $profiles,
            'latestLinks' => $latestLinks,
            'products' => $products,
            'userSearch' => $userSearch,
        ]);
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
