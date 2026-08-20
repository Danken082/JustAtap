<?php

namespace App\Http\Controllers;

use App\Models\Cards as Card;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CorporateCardController extends Controller
{
    public function index(Request $request): View
    {
    $this->ensureCorporateAccess($request);

        $cards = Card::query()
            ->where('purchaser_id', $request->user()->id)
            ->latest()
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

        for ($number = 0; $number < $validated['quantity']; $number++) {
            Card::create([
                'purchaser_id' => $request->user()->id,
                'card_number' => $this->generateCardNumber(),
                'name' => $validated['name'] ?: $request->user()->name . ' Employee Card',
            ]);
        }

        return redirect()->route('corporate.cards.index')
            ->with('success', $validated['quantity'] . ' corporate card(s) ordered.');
    }

    public function updateProfile(Request $request, string $cardId): RedirectResponse
    {
        $this->ensureCorporateAccess($request);

        $card = Card::where('card_number', $cardId)
            ->where('purchaser_id', $request->user()->id)
            ->firstOrFail();
        $employee = User::where('card_id', $card->card_number)->firstOrFail();

        $validated = $request->validate([
            'display_name' => ['required', 'string', 'max:255'],
            'display_name_font_size' => ['nullable', 'string', 'max:10'],
            'layout_style' => ['nullable', 'in:classic_card,wave_split,soft_fade,hihello_card'],
            'title' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:500'],
            'background_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'text_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'accent_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'card_style' => ['required', 'in:glass,clean,bold'],
            'background_pattern' => ['required', 'in:gradient,dots,solid'],
        ]);

        $this->profileForUser($employee)->update($validated);

        return redirect()->route('corporate.cards.index', ['card_id' => $card->card_number])
            ->with('success', 'Employee profile updated.');
    }

    private function generateCardNumber(): string
    {
        $year = date('Y');
        $next = 1;

        while (true) {
            $candidate = 'ID-' . $year . '-' . str_pad($next, 6, '0', STR_PAD_LEFT);

            if (! Card::where('card_number', $candidate)->exists() && ! User::where('card_id', $candidate)->exists()) {
                return $candidate;
            }

            $next++;
        }
    }

    private function ensureCorporateAccess(Request $request): void
    {
        abort_unless($request->user()->isCorporate(), 403);
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
        ]);
    }
}