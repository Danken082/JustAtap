<?php

namespace App\Http\Controllers;

use App\Models\ProfileLink;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * @return array<string, array{label: string, icon: string, category: string, placeholder: string, value_type: string}>
     */
    private function linkTypes(): array
    {
        return [
            'x' => ['label' => 'X.com', 'icon' => 'bi-twitter-x', 'category' => 'Social', 'placeholder' => 'x.com/username', 'value_type' => 'url'],
            'facebook' => ['label' => 'Facebook', 'icon' => 'bi-facebook', 'category' => 'Social', 'placeholder' => 'facebook.com/username', 'value_type' => 'url'],
            'instagram' => ['label' => 'Instagram', 'icon' => 'bi-instagram', 'category' => 'Social', 'placeholder' => 'instagram.com/username', 'value_type' => 'url'],
            'snapchat' => ['label' => 'Snapchat', 'icon' => 'bi-snapchat', 'category' => 'Social', 'placeholder' => 'snapchat.com/add/username', 'value_type' => 'url'],
            'linkedin' => ['label' => 'LinkedIn', 'icon' => 'bi-linkedin', 'category' => 'Social', 'placeholder' => 'linkedin.com/in/username', 'value_type' => 'url'],
            'pinterest' => ['label' => 'Pinterest', 'icon' => 'bi-pinterest', 'category' => 'Social', 'placeholder' => 'pinterest.com/username', 'value_type' => 'url'],
            'threads' => ['label' => 'Threads', 'icon' => 'bi-threads', 'category' => 'Social', 'placeholder' => 'threads.net/@username', 'value_type' => 'url'],
            'tiktok' => ['label' => 'TikTok', 'icon' => 'bi-tiktok', 'category' => 'Social', 'placeholder' => 'tiktok.com/@username', 'value_type' => 'url'],
            'whatsapp' => ['label' => 'WhatsApp', 'icon' => 'bi-whatsapp', 'category' => 'Communication', 'placeholder' => '+639171234567 or wa.me/number', 'value_type' => 'phone_or_url'],
            'telegram' => ['label' => 'Telegram', 'icon' => 'bi-telegram', 'category' => 'Communication', 'placeholder' => 't.me/username', 'value_type' => 'url'],
            'discord' => ['label' => 'Discord', 'icon' => 'bi-discord', 'category' => 'Communication', 'placeholder' => 'discord.gg/invite-code', 'value_type' => 'url'],
            'email' => ['label' => 'Email', 'icon' => 'bi-envelope-fill', 'category' => 'Communication', 'placeholder' => 'you@example.com', 'value_type' => 'email'],
            'phone' => ['label' => 'Phone', 'icon' => 'bi-telephone-fill', 'category' => 'Communication', 'placeholder' => '+639171234567', 'value_type' => 'phone'],
            'zoom' => ['label' => 'Zoom', 'icon' => 'bi-camera-video-fill', 'category' => 'Conferencing', 'placeholder' => 'zoom.us/j/meeting-id', 'value_type' => 'url'],
            'teams' => ['label' => 'Teams', 'icon' => 'bi-microsoft-teams', 'category' => 'Conferencing', 'placeholder' => 'teams.microsoft.com/l/meetup-join/...', 'value_type' => 'url'],
            'google_meet' => ['label' => 'Meet', 'icon' => 'bi-camera-video', 'category' => 'Conferencing', 'placeholder' => 'meet.google.com/abc-defg-hij', 'value_type' => 'url'],
            'skype' => ['label' => 'Skype', 'icon' => 'bi-skype', 'category' => 'Conferencing', 'placeholder' => 'join.skype.com/...', 'value_type' => 'url'],
            'paypal' => ['label' => 'PayPal', 'icon' => 'bi-paypal', 'category' => 'Payment', 'placeholder' => 'paypal.me/username', 'value_type' => 'url'],
            'cashapp' => ['label' => 'Cash App', 'icon' => 'bi-cash-stack', 'category' => 'Payment', 'placeholder' => 'cash.app/$username', 'value_type' => 'url'],
            'youtube' => ['label' => 'YouTube', 'icon' => 'bi-youtube', 'category' => 'Video', 'placeholder' => 'youtube.com/@channel', 'value_type' => 'url'],
            'vimeo' => ['label' => 'Vimeo', 'icon' => 'bi-vimeo', 'category' => 'Video', 'placeholder' => 'vimeo.com/username', 'value_type' => 'url'],
            'twitch' => ['label' => 'Twitch', 'icon' => 'bi-twitch', 'category' => 'Video', 'placeholder' => 'twitch.tv/username', 'value_type' => 'url'],
            'spotify' => ['label' => 'Spotify', 'icon' => 'bi-spotify', 'category' => 'Music', 'placeholder' => 'open.spotify.com/user/username', 'value_type' => 'url'],
            'soundcloud' => ['label' => 'SoundCloud', 'icon' => 'bi-cloud', 'category' => 'Music', 'placeholder' => 'soundcloud.com/username', 'value_type' => 'url'],
            'behance' => ['label' => 'Behance', 'icon' => 'bi-behance', 'category' => 'Design', 'placeholder' => 'behance.net/username', 'value_type' => 'url'],
            'dribbble' => ['label' => 'Dribbble', 'icon' => 'bi-dribbble', 'category' => 'Design', 'placeholder' => 'dribbble.com/username', 'value_type' => 'url'],
            'github' => ['label' => 'GitHub', 'icon' => 'bi-github', 'category' => 'Other', 'placeholder' => 'github.com/username', 'value_type' => 'url'],
            'website' => ['label' => 'Website', 'icon' => 'bi-globe2', 'category' => 'Other', 'placeholder' => 'yourdomain.com', 'value_type' => 'url'],
            'address' => ['label' => 'Address', 'icon' => 'bi-geo-alt-fill', 'category' => 'Other', 'placeholder' => 'Business address or map URL', 'value_type' => 'address'],
        ];
    }

    /**
     * @param array<string, mixed> $validated
     */
    private function normalizeLinkValue(array $validated): string
    {
        $type = $validated['type'];
        $value = trim((string) $validated['value']);
        $metadata = $this->linkTypes()[$type] ?? null;
        $valueType = $metadata['value_type'] ?? 'url';

        if ($valueType === 'email' && ! str_starts_with(strtolower($value), 'mailto:')) {
            return 'mailto:'.$value;
        }

        if ($valueType === 'phone' && ! str_starts_with(strtolower($value), 'tel:')) {
            return 'tel:'.$value;
        }

        if ($valueType === 'phone_or_url') {
            if (preg_match('/^https?:\/\//i', $value)) {
                return $value;
            }

            $digits = preg_replace('/[^0-9+]/', '', $value) ?? '';

            if ($digits !== '') {
                return 'https://wa.me/'.ltrim($digits, '+');
            }
        }

        if ($valueType === 'address' && ! preg_match('/^https?:\/\//i', $value)) {
            return 'https://maps.google.com/?q='.urlencode($value);
        }

        if (! preg_match('/^(https?:\/\/|mailto:|tel:)/i', $value)) {
            return 'https://'.$value;
        }

        return $value;
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
            'avatar_offset_x' => 0,
            'avatar_offset_y' => 0,
        ]);
    }

    private function storagePathFromPublicUrl(?string $mediaUrl): ?string
    {
        if ($mediaUrl === null || $mediaUrl === '') {
            return null;
        }

        $path = parse_url($mediaUrl, PHP_URL_PATH);

        if (! is_string($path) || $path === '') {
            $path = $mediaUrl;
        }

        if (! str_starts_with($path, '/storage/')) {
            return null;
        }

        return ltrim(substr($path, strlen('/storage/')), '/');
    }

    private function deleteStoredMediaIfExists(?string $mediaUrl): void
    {
        $storagePath = $this->storagePathFromPublicUrl($mediaUrl);

        if ($storagePath !== null && Storage::disk('public')->exists($storagePath)) {
            Storage::disk('public')->delete($storagePath);
        }
    }

    private function normalizeBadgeImages(mixed $badgeImages): array
    {
        if (is_array($badgeImages)) {
            $items = $badgeImages;
        } else {
            $items = preg_split('/\r\n|\r|\n|,/', (string) $badgeImages) ?: [];
        }

        $items = array_values(array_filter(array_map(static fn ($item) => trim((string) $item), $items), static fn ($item) => $item !== ''));

        return array_slice($items, 0, 10);
    }

    private function saveBadgeImages(Request $request): array
    {
        $storedPaths = [];

        if ($request->hasFile('badge_images')) {
            foreach ($request->file('badge_images') as $badgeFile) {
                if ($badgeFile === null || ! $badgeFile->isValid()) {
                    continue;
                }

                $storedPath = $badgeFile->store('profile-badges', 'public');
                $storedPaths[] = Storage::url($storedPath);
            }
        }

        $existingBadgeImages = [];
        $rawBadgeImages = $request->input('badge_images');

        if (is_array($rawBadgeImages)) {
            $existingBadgeImages = $this->normalizeBadgeImages($rawBadgeImages);
        } else {
            $existingRaw = $request->input('existing_badge_images');

            if (is_string($existingRaw) && trim($existingRaw) !== '') {
                $decoded = json_decode($existingRaw, true);

                if (is_array($decoded)) {
                    $existingBadgeImages = $this->normalizeBadgeImages($decoded);
                } else {
                    $existingBadgeImages = $this->normalizeBadgeImages($existingRaw);
                }
            }
        }

        $merged = array_values(array_filter(array_merge($existingBadgeImages, $storedPaths), static fn ($item) => is_string($item) && trim($item) !== ''));

        return array_slice($merged, 0, 10);
    }

    public function edit(Request $request): View
    {
        $user = $request->user();
        $profile = $this->profileForUser($user)->load('links');

        return view('profile.edit', [
            'user' => $user,
            'profile' => $profile,
            'linkTypes' => $this->linkTypes(),
            'adminEditor' => false,
        ]);
    }

    public function updatePersonalInfo(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->update($validated);

        return back()->with('status', 'Personal information updated.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
        ]);

        $request->user()->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        return redirect()->route('home')->with('status', 'Password updated successfully.');
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'display_name' => ['required', 'string', 'max:255'],
            'display_name_font_size' => ['nullable', 'string', 'max:10'],
            'layout_style' => ['nullable', 'in:classic_card,wave_split,soft_fade,hihello_card'],
            'title' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:500'],
            'avatar_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif,mp4,mov,m4v,webm,avi', 'max:4096'],
            'avatar_url' => ['nullable', 'string', 'max:500'],
            'logo_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
            'logo_url' => ['nullable', 'string', 'max:500'],
            'badge_images' => ['nullable'],
            'remove_avatar' => ['nullable', 'boolean'],
            'remove_logo' => ['nullable', 'boolean'],
            'background_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'text_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'accent_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'card_style' => ['required', 'in:glass,clean,bold'],
            'background_pattern' => ['required', 'in:gradient,dots,solid'],
            'avatar_offset_x' => ['nullable', 'numeric', 'between:-200,200'],
            'avatar_offset_y' => ['nullable', 'numeric', 'between:-200,200'],
        ]);

        $profile = $this->profileForUser($request->user());
        $profileData = collect($validated)->except(['avatar_image', 'remove_avatar', 'logo_image', 'remove_logo'])->all();
        $profileData['avatar_url'] = $validated['avatar_url'] ?? $profile->avatar_url;
        $profileData['logo_url'] = $validated['logo_url'] ?? $profile->logo_url;
        $profileData['badge_images'] = $this->saveBadgeImages($request);
        $profileData['avatar_offset_x'] = (int) round((float) ($validated['avatar_offset_x'] ?? $profile->avatar_offset_x ?? 0));
        $profileData['avatar_offset_y'] = (int) round((float) ($validated['avatar_offset_y'] ?? $profile->avatar_offset_y ?? 0));

        if ($request->boolean('remove_avatar')) {
            $this->deleteStoredMediaIfExists($profile->avatar_url);
            $profileData['avatar_url'] = null;
            $profileData['avatar_offset_x'] = 0;
            $profileData['avatar_offset_y'] = 0;
        }

        if ($request->hasFile('avatar_image')) {
            $this->deleteStoredMediaIfExists($profile->avatar_url);
            $storedPath = $request->file('avatar_image')->store('profile-avatars', 'public');
            $profileData['avatar_url'] = Storage::url($storedPath);
            $profileData['avatar_offset_x'] = (int) ($validated['avatar_offset_x'] ?? 0);
            $profileData['avatar_offset_y'] = (int) ($validated['avatar_offset_y'] ?? 0);
        }

        if ($request->boolean('remove_logo')) {
            $this->deleteStoredMediaIfExists($profile->logo_url);
            $profileData['logo_url'] = null;
        }

        if ($request->hasFile('logo_image')) {
            $this->deleteStoredMediaIfExists($profile->logo_url);
            $storedPath = $request->file('logo_image')->store('profile-logos', 'public');
            $profileData['logo_url'] = Storage::url($storedPath);
        }

        $profile->update($profileData);

        return back()->with('status', 'Profile design saved.');
    }

    /**
     * @return array<int, array{type: string, label: string, value: string}>
     */
    private function normalizeLinkEntries(Request $request): array
    {
        $rawEntries = $request->input('links');

        if (! is_array($rawEntries) || $rawEntries === []) {
            $rawEntries = [$request->only(['type', 'label', 'value'])];
        }

        $allowedTypes = array_keys($this->linkTypes());
        $entries = [];

        foreach ($rawEntries as $entry) {
            if (! is_array($entry)) {
                continue;
            }

            $validator = Validator::make($entry, [
                'type' => ['required', Rule::in($allowedTypes)],
                'label' => ['required', 'string', 'max:100'],
                'value' => ['required', 'string', 'max:255'],
            ]);

            if ($validator->fails()) {
                throw new \Illuminate\Validation\ValidationException($validator);
            }

            $validated = $validator->validated();
            $entries[] = [
                'type' => $validated['type'],
                'label' => trim((string) $validated['label']),
                'value' => $this->normalizeLinkValue($validated),
            ];
        }

        return $entries;
    }

    private function saveLinkEntries(UserProfile $profile, array $entries): int
    {
        $sortOrderStart = ((int) $profile->links()->max('sort_order')) + 1;

        foreach ($entries as $index => $entry) {
            $profile->links()->create([
                'type' => $entry['type'],
                'label' => $entry['label'],
                'value' => $entry['value'],
                'sort_order' => $sortOrderStart + $index,
            ]);
        }

        return count($entries);
    }

    public function addLink(Request $request): RedirectResponse
    {
        $profile = $this->profileForUser($request->user());
        $entries = $this->normalizeLinkEntries($request);

        if ($entries === []) {
            $request->validate([
                'type' => ['required'],
                'label' => ['required'],
                'value' => ['required'],
            ]);
        }

        $created = $this->saveLinkEntries($profile, $entries);

        return back()->with('status', $created > 1 ? 'Links added.' : 'Link added.');
    }

    public function removeLink(Request $request, ProfileLink $link): RedirectResponse
    {
        $profile = $this->profileForUser($request->user());

        if ($link->user_profile_id !== $profile->id) {
            abort(403);
        }

        $link->delete();

        return back()->with('status', 'Link removed.');
    }

    public function addUserProfileLink(Request $request, User $user): RedirectResponse
    {
        $profile = $this->profileForUser($user);
        $entries = $this->normalizeLinkEntries($request);

        if ($entries === []) {
            $request->validate([
                'type' => ['required'],
                'label' => ['required'],
                'value' => ['required'],
            ]);
        }

        $created = $this->saveLinkEntries($profile, $entries);

        return back()->with('status', $created > 1 ? 'Profile links added.' : 'Profile link added.');
    }

    public function removeUserProfileLink(User $user, ProfileLink $link): RedirectResponse
    {
        abort_unless($link->user_profile_id === $user->profile?->id, 404);
        $link->delete();

        return back()->with('status', 'Profile link removed.');
    }

    public function showPublic(string $cardId): View
    {
        $user = User::where('card_id', $cardId)->firstOrFail();
        $profile = $this->profileForUser($user)->load('links');

        abort_if($profile->profile_builder_active === false, 404);
        $profile->increment('profile_view_count');
        $profile->refresh();

        return view('profile.show', [
            'user' => $user,
            'profile' => $profile,
            'linkTypes' => $this->linkTypes(),
        ]);
    }

    public function sharePublicProfile(Request $request, string $cardId): \Illuminate\Http\Response
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::where('card_id', $cardId)->firstOrFail();
        $profile = $this->profileForUser($user);

        \Illuminate\Support\Facades\Mail::to($validated['email'])
            ->send(new \App\Mail\ProfileContactMail(
                $profile,
                $user,
                $validated['name'] ?? $user->name,
                $validated['email'],
                $validated['phone'] ?? '',
            ));

        return response()->noContent();
    }

    public function updateUserProfile(Request $request, User $user): RedirectResponse
    {
        $profile = $this->profileForUser($user);

        $validated = $request->validate([
            'display_name' => ['required', 'string', 'max:255'],
            'display_name_font_size' => ['nullable', 'string', 'max:10'],
            'layout_style' => ['nullable', 'in:classic_card,wave_split,soft_fade,hihello_card'],
            'title' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:500'],
            'avatar_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
            'avatar_url' => ['nullable', 'string', 'max:500'],
            'logo_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
            'logo_url' => ['nullable', 'string', 'max:500'],
            'badge_images' => ['nullable'],
            'remove_avatar' => ['nullable', 'boolean'],
            'remove_logo' => ['nullable', 'boolean'],
            'background_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'text_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'accent_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'card_style' => ['required', 'in:glass,clean,bold'],
            'background_pattern' => ['required', 'in:gradient,dots,solid'],
            'avatar_offset_x' => ['nullable', 'numeric', 'between:-200,200'],
            'avatar_offset_y' => ['nullable', 'numeric', 'between:-200,200'],
        ]);

        $profileData = collect($validated)->except(['avatar_image', 'remove_avatar', 'logo_image', 'remove_logo', 'badge_images'])->all();
        $profileData['avatar_url'] = $validated['avatar_url'] ?? $profile->avatar_url;
        $profileData['logo_url'] = $validated['logo_url'] ?? $profile->logo_url;
        $profileData['badge_images'] = $this->saveBadgeImages($request);
        $profileData['avatar_offset_x'] = (int) ($validated['avatar_offset_x'] ?? $profile->avatar_offset_x ?? 0);
        $profileData['avatar_offset_y'] = (int) ($validated['avatar_offset_y'] ?? $profile->avatar_offset_y ?? 0);

        if ($request->boolean('remove_avatar')) {
            $this->deleteStoredMediaIfExists($profile->avatar_url);
            $profileData['avatar_url'] = null;
            $profileData['avatar_offset_x'] = 0;
            $profileData['avatar_offset_y'] = 0;
        }

        if ($request->hasFile('avatar_image')) {
            $this->deleteStoredMediaIfExists($profile->avatar_url);
            $storedPath = $request->file('avatar_image')->store('profile-avatars', 'public');
            $profileData['avatar_url'] = Storage::url($storedPath);
            $profileData['avatar_offset_x'] = (int) ($validated['avatar_offset_x'] ?? 0);
            $profileData['avatar_offset_y'] = (int) ($validated['avatar_offset_y'] ?? 0);
        }

        if ($request->boolean('remove_logo')) {
            $this->deleteStoredMediaIfExists($profile->logo_url);
            $profileData['logo_url'] = null;
        }

        if ($request->hasFile('logo_image')) {
            $this->deleteStoredMediaIfExists($profile->logo_url);
            $storedPath = $request->file('logo_image')->store('profile-logos', 'public');
            $profileData['logo_url'] = Storage::url($storedPath);
        }

        $profile->update($profileData);

        return back()->with('status', "Profile for {$user->name} updated.");
    }

    public function editUserProfile(Request $request, string $cardId): View
    {
        $user = User::where('card_id', $cardId)->firstOrFail();
        $profile = $this->profileForUser($user)->load('links');

        return view('admin.profile.edituserprofile', [
            'user' => $user,
            'profile' => $profile,
            'linkTypes' => $this->linkTypes(),
            'adminEditor' => true,
        ]);
    }

    public function editAdminUserProfile(User $user): View
    {
        return view('admin.profile.edituserprofile', [
            'user' => $user,
            'profile' => $this->profileForUser($user)->load('links'),
            'linkTypes' => $this->linkTypes(),
            'adminEditor' => true,
        ]);
    }
}
