<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
