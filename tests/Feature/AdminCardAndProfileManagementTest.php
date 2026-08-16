<?php

namespace Tests\Feature;

use App\Models\Cards;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCardAndProfileManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_requires_a_generated_card_id_that_is_not_already_used(): void
    {
        Cards::create([
            'card_number' => 'ID-2026-000010',
            'name' => 'Available Card',
        ]);

        $response = $this->from('/register')->post(route('register.attempt'), [
            'name' => 'New Member',
            'card_id' => 'ID-2026-000011',
            'email' => 'newmember@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['card_id']);
        $this->assertStringContainsString("doesn't exist", $response->getSession()->get('errors')->first('card_id'));

        $response = $this->from('/register')->post(route('register.attempt'), [
            'name' => 'New Member',
            'card_id' => 'ID-2026-000010',
            'email' => 'newmember@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['card_id']);
        $this->assertEquals(0, User::where('card_id', 'ID-2026-000010')->count());

        $this->assertDatabaseHas('cards', ['card_number' => 'ID-2026-000010']);
    }

    public function test_admin_can_generate_a_card_for_a_user_and_update_their_profile(): void
    {
        config(['app.admin_emails' => ['admin@example.com']]);

        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'card_id' => 'ID-2026-000001',
        ]);

        $user = User::factory()->create([
            'name' => 'Member User',
            'email' => 'member@example.com',
            'card_id' => 'ID-2026-000002',
        ]);

        $this->actingAs($admin);

        $response = $this->post(route('admin.cards.generate'), [
            'name' => 'VIP Card',
            'user_id' => $user->id,
        ]);

        $response->assertRedirect(route('admin.cards.index', ['user_id' => $user->id]));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertNotEmpty($user->card_id);
        $this->assertTrue(Cards::where('name', 'VIP Card')->exists());

        $response = $this->post(route('admin.users.profile.update', $user), [
            'display_name' => 'Updated Display Name',
            'title' => 'Product Lead',
            'bio' => 'Updated live preview bio.',
            'layout_style' => 'wave_split',
            'card_style' => 'bold',
            'background_pattern' => 'dots',
            'background_color' => '#123456',
            'text_color' => '#abcdef',
            'accent_color' => '#654321',
            'display_name_font_size' => '32',
        ]);

        $response->assertRedirect();
        $user->refresh();
        $profile = $user->profile()->first();

        $this->assertNotNull($profile);
        $this->assertSame('Updated Display Name', $profile->display_name);
        $this->assertSame('wave_split', $profile->layout_style);
    }
}
