<?php

namespace Tests\Feature;

use App\Mail\ProfileContactMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ProfileCustomizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_update_can_store_custom_display_name_font_size_logo_and_badges(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/profile/edit', [
            'display_name' => 'Jane Doe',
            'title' => 'Designer',
            'bio' => 'Hello world',
            'background_color' => '#111111',
            'text_color' => '#ffffff',
            'accent_color' => '#ff6600',
            'card_style' => 'glass',
            'background_pattern' => 'gradient',
            'display_name_font_size' => '24',
            'layout_style' => 'wave_split',
            'logo_url' => 'https://example.com/logo.png',
            'badge_images' => [
                'https://example.com/badge-1.png',
                'https://example.com/badge-2.png',
            ],
        ]);

        $response->assertSessionHas('status', 'Profile design saved.');

        $profile = $user->fresh()->profile;

        $this->assertSame('24', $profile->display_name_font_size);
        $this->assertSame('wave_split', $profile->layout_style);
        $this->assertSame('https://example.com/logo.png', $profile->logo_url);
        $this->assertSame([
            'https://example.com/badge-1.png',
            'https://example.com/badge-2.png',
        ], $profile->badge_images);
    }

    public function test_profile_edit_page_uses_saved_text_color_in_live_preview(): void
    {
        $user = User::factory()->create();
        $user->profile()->create([
            'display_name' => 'Jane Doe',
            'title' => 'Designer',
            'bio' => 'Hello world',
            'text_color' => '#123456',
            'background_color' => '#111111',
            'accent_color' => '#ff6600',
            'card_style' => 'glass',
            'background_pattern' => 'gradient',
            'display_name_font_size' => '24',
            'layout_style' => 'classic_card',
        ]);

        $response = $this->actingAs($user)->get('/profile/edit');

        $response->assertOk();
        $response->assertSee('color: #123456', false);
    }

    public function test_user_can_add_multiple_profile_links_in_one_submission(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/profile/links', [
            'links' => [
                [
                    'type' => 'instagram',
                    'label' => 'Instagram',
                    'value' => 'instagram.com/jane',
                ],
                [
                    'type' => 'website',
                    'label' => 'Portfolio',
                    'value' => 'example.com',
                ],
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Links added.');

        $this->assertCount(2, $user->fresh()->profile->links);
        $this->assertSame('https://instagram.com/jane', $user->fresh()->profile->links->first()->value);
        $this->assertSame('https://example.com', $user->fresh()->profile->links->last()->value);
    }

    public function test_public_profile_can_send_contact_vcard_to_entered_email(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'name' => 'Jane Public',
            'card_id' => 'ID-2026-000777',
        ]);

        $response = $this->post(route('profile.public.share', ['cardId' => $user->card_id]), [
            'name' => 'John Smith',
            'email' => 'john@example.com',
            'phone' => '+1 555 123 4567',
        ]);

        $response->assertNoContent();

        Mail::assertSent(ProfileContactMail::class, function ($mail) {
            return $mail->hasTo('john@example.com')
                && $mail->contactName === 'John Smith'
                && $mail->profileOwnerName === 'Jane Public';
        });
    }
}
