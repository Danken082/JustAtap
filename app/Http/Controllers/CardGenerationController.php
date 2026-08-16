<?php

namespace App\Http\Controllers;

use App\Models\Cards as Card;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CardGenerationController extends Controller
{
    public function index(): View
    {
        $cards = Card::latest()->get();
        $users = User::latest()->get();
        $selectedUser = $users->firstWhere('id', request('user_id')) ?? $users->first();
        $profile = $selectedUser ? $this->profileForUser($selectedUser) : null;

        return view('admin.CardGeneration', compact('cards', 'users', 'selectedUser', 'profile'));
    }

    public function create(): View
    {
        return view('admin.CardGeneration');
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $cardNumber = $this->generateCardNumber();

        Card::create([
            'card_number' => $cardNumber,
            'name' => $validated['name'],
        ]);

        $user = User::findOrFail($validated['user_id']);
        $user->update(['card_id' => $cardNumber]);

        return redirect()->route('admin.cards.index', ['user_id' => $user->id])
            ->with('success', 'Card generated and assigned successfully.');
    }

    public function update(Request $request, Card $card)
    {
        $assignedUser = User::where('card_id', $card->card_number)->first();

        $validated = $request->validate([
            'card_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cards', 'card_number')->ignore($card->id),
                function (string $attribute, mixed $value, \Closure $fail) use ($assignedUser, $card) {
                    if (User::where('card_id', $value)->where('id', '!=', $assignedUser?->id ?? 0)->exists()) {
                        $fail('This card number is already assigned to another user.');
                    }

                    if (Card::where('card_number', $value)->whereKeyNot($card->id)->exists()) {
                        $fail('This card number already exists in the card table.');
                    }
                },
            ],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $card->update([
            'card_number' => $validated['card_number'],
            'name' => $validated['name'],
        ]);

        if ($assignedUser) {
            $assignedUser->update(['card_id' => $validated['card_number']]);
        }

        return redirect()->route('admin.cards.index')
            ->with('success', 'Card updated successfully.');
    }

    public function updateUserProfile(Request $request, User $user)
    {
        $validated = $request->validate([
            'display_name' => ['required', 'string', 'max:255'],
            'display_name_font_size' => ['nullable', 'string', 'max:10'],
            'layout_style' => ['nullable', 'in:classic_card,wave_split,soft_fade,hihello_card,hihello_card'],
            'title' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:500'],
            'background_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'text_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'accent_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'card_style' => ['required', 'in:glass,clean,bold'],
            'background_pattern' => ['required', 'in:gradient,dots,solid'],
        ]);

        $profile = $this->profileForUser($user);
        $profile->update($validated);

        return redirect()->route('admin.cards.index', ['user_id' => $user->id])
            ->with('success', 'Profile updated successfully.');
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
