<?php

namespace App\Http\Controllers;

use App\Mail\CorporateCardOrderRequestMail;
use App\Models\Cards as Card;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class CorporateCardController extends Controller
{
    public function index(Request $request): View
    {
    $this->ensureCorporateAccess($request);

        $cards = Card::query()
            ->where('purchaser_id', $request->user()->id)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $selectedCard = null;
        $employee = null;
        $profile = null;

        if ($request->filled('card_id')) {
            $selectedCard = $cards->firstWhere('card_number', $request->string('card_id')->toString());

            if ($selectedCard) {
                $employee = User::where('card_id', $selectedCard->card_number)->first();
                $profile = $employee ? $this->profileForUser($employee) : null;
            }
        }

        return view('corporate.cards.index', compact('cards', 'selectedCard', 'employee', 'profile'));
    }

    public function order(Request $request): RedirectResponse
    {
        $this->ensureCorporateAccess($request);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:500'],
            'name' => ['nullable', 'string', 'max:255'],
        ]);

        $admin = $request->user();
        $companyName = $admin->company_name ?: $admin->name;

        foreach (config('app.admin_emails', []) as $adminEmail) {
            Mail::to($adminEmail)->send(new CorporateCardOrderRequestMail(
                $companyName,
                $admin->email,
                (string) ($validated['name'] ?? ''),
                (int) $validated['quantity'],
            ));
        }

        return redirect()->route('corporate.cards.index')
            ->with('success', 'Your order request for '.$validated['quantity'].' card(s) has been sent to the admin team. Card IDs will be generated and shared after review.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $this->ensureCorporateAccess($request);

        $validated = $request->validate([
            'card_ids' => ['required', 'array'],
            'card_ids.*' => ['required', 'integer', 'exists:cards,id'],
        ]);

        $allowedIds = Card::where('purchaser_id', $request->user()->id)
            ->whereIn('id', $validated['card_ids'])
            ->pluck('id')
            ->all();

        foreach ($validated['card_ids'] as $position => $cardId) {
            if (! in_array((int) $cardId, $allowedIds, true)) {
                continue;
            }

            Card::where('id', $cardId)
                ->where('purchaser_id', $request->user()->id)
                ->update(['sort_order' => $position + 1]);
        }

        return redirect()->route('corporate.cards.index')
            ->with('success', 'Card order updated.');
    }

    public function deactivateEmployee(Request $request, User $user): RedirectResponse
    {
        $this->ensureCorporateAccess($request);

        abort_unless($this->ownedByCompany($request->user(), $user), 403, 'This employee account does not belong to your company.');

        $user->update(['is_active' => false]);

        return redirect()->route('corporate.cards.index')
            ->with('success', 'Employee account deactivated.');
    }

    public function deleteEmployee(Request $request, User $user): RedirectResponse
    {
        $this->ensureCorporateAccess($request);

        abort_unless($this->ownedByCompany($request->user(), $user), 403, 'This employee account does not belong to your company.');

        $user->delete();

        return redirect()->route('corporate.cards.index')
            ->with('success', 'Employee account deleted.');
    }

    public function updateProfile(Request $request, string $cardId): RedirectResponse
    {
        $this->ensureCorporateAccess($request);

        $card = Card::where('card_number', $cardId)
            ->first();

        abort_unless($card !== null && $card->purchaser_id === $request->user()->id, 403, 'This card does not belong to your company.');

        $employee = User::where('card_id', $card->card_number)->firstOrFail();

        $validated = $request->validate([
            'display_name' => ['required', 'string', 'max:255'],
            'display_name_font_size' => ['nullable', 'string', 'max:10'],
            'layout_style' => ['nullable', 'in:classic_card,wave_split,soft_fade,hihello_card'],
            'title' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:500'],
            'background_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'text_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'card_style' => ['required', 'in:glass,clean,bold'],
            'background_pattern' => ['required', 'in:gradient,dots,solid'],
        ]);

        $this->profileForUser($employee)->update($validated);

        return redirect()->route('corporate.cards.index', ['card_id' => $card->card_number])
            ->with('success', 'Employee profile updated.');
    }

    private function generateCardNumber(): string
    {
        do {
            $candidate = 'CARD-' . strtoupper(\Illuminate\Support\Str::random(12));
        } while (Card::where('card_number', $candidate)->exists() || User::where('card_id', $candidate)->exists());

        return $candidate;
    }

    private function ensureCorporateAccess(Request $request): void
    {
        abort_unless($request->user()->isCorporate(), 403);
    }

    private function ownedByCompany(User $companyUser, User $employee): bool
    {
        if (! $companyUser->isCorporate()) {
            return false;
        }

        if ($employee->is_corporate) {
            return false;
        }

        return Card::where('purchaser_id', $companyUser->id)
            ->where('card_number', $employee->card_id)
            ->exists();
    }

    private function profileForUser(User $user): UserProfile
    {
        return $user->profile()->firstOrCreate([], [
            'display_name' => $user->name,
            'display_name_font_size' => '24',
            'layout_style' => 'classic_card',
            'title' => 'Digital Profile',
            'bio' => 'Edit your profile from the dashboard.',
            'background_color' => '#111827',
            'text_color' => '#f9fafb',
            'accent_color' => '#60a5fa',
            'card_style' => 'glass',
            'background_pattern' => 'gradient',
            'badge_images' => [],
            'profile_builder_active' => true,
        ]);
    }
}