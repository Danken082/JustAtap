<?php

namespace Tests\Feature;

use App\Mail\GuestCheckoutSummaryMail;
use App\Mail\GuestOrderReceiptMail;
use App\Models\Cards;
use App\Models\ProfileLink;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminCardAndProfileManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_profile_route_alias_works_for_card_id(): void
    {
        $user = User::factory()->create([
            'name' => 'Jane Public',
            'card_id' => 'ID-2026-000777',
        ]);

        $response = $this->get('/profile/'.$user->card_id);

        $response->assertOk();
        $response->assertSee('Jane Public');
    }

    public function test_guest_checkout_sends_receipt_to_customer_and_order_summary_to_admin(): void
    {
        config(['app.admin_emails' => ['admin@example.com']]);

        $product = Product::create([
            'name' => 'Smart Tap Card',
            'slug' => 'smart-tap-card',
            'description' => 'NFC business card',
            'price' => 49.99,
            'category' => 'Cards',
            'main_image' => null,
            'is_active' => true,
        ]);

        $product->colors()->create(['name' => 'Black', 'sort_order' => 1]);
        $product->sizes()->create(['name' => 'Standard', 'sort_order' => 1]);

        $this->withSession([
            'guest_cart' => [
                $product->id.'::Black::Standard' => 2,
            ],
        ]);

        Mail::fake();

        $response = $this->post(route('cart.checkout'), [
            'customer_name' => 'Jane Customer',
            'customer_email' => 'jane@example.com',
        ]);

        $response->assertRedirect(route('cart.index'));

        Mail::assertSent(GuestOrderReceiptMail::class, function ($mail) {
            return $mail->hasTo('jane@example.com')
                && $mail->customerName === 'Jane Customer'
                && $mail->total === 99.98;
        });

        Mail::assertSent(GuestCheckoutSummaryMail::class, function ($mail) {
            return $mail->hasTo('admin@example.com')
                && $mail->orderedBy === 'Jane Customer'
                && $mail->orderedByEmail === 'jane@example.com';
        });
    }

    public function test_corporate_buyer_can_manage_only_profiles_for_cards_they_ordered(): void
    {
        $buyer = User::factory()->create(['is_corporate' => true, 'company_name' => 'Buyer Company', 'card_id' => null]);
        $otherBuyer = User::factory()->create(['is_corporate' => true, 'company_name' => 'Other Company', 'card_id' => null]);
        $employee = User::factory()->create(['card_id' => 'ID-2026-000100']);
        $otherEmployee = User::factory()->create(['card_id' => 'ID-2026-000101']);

        Cards::create([
            'card_number' => $employee->card_id,
            'name' => 'Buyer Card',
            'purchaser_id' => $buyer->id,
        ]);
        Cards::create([
            'card_number' => $otherEmployee->card_id,
            'name' => 'Other Buyer Card',
            'purchaser_id' => $otherBuyer->id,
        ]);

        $this->actingAs($buyer)
            ->get(route('corporate.cards.index'))
            ->assertOk()
            ->assertSee($employee->card_id)
            ->assertDontSee($otherEmployee->card_id);

        $this->actingAs($buyer)
            ->post(route('corporate.cards.profile.update', $employee->card_id), $this->profilePayload())
            ->assertRedirect(route('corporate.cards.index', ['card_id' => $employee->card_id]));

        $this->assertSame('Updated by corporate buyer', $employee->fresh()->profile->display_name);

        $this->actingAs($buyer)
            ->post(route('corporate.cards.profile.update', $otherEmployee->card_id), $this->profilePayload())
            ->assertForbidden();
    }

    public function test_employee_account_cannot_access_corporate_card_management(): void
    {
        $employee = User::factory()->create(['is_corporate' => false]);

        $this->actingAs($employee)
            ->get(route('corporate.cards.index'))
            ->assertForbidden();
    }

    /** @return array<string, string> */
    private function profilePayload(): array
    {
        return [
            'display_name' => 'Updated by corporate buyer',
            'title' => 'Product Lead',
            'bio' => 'Managed by the card purchaser.',
            'layout_style' => 'classic_card',
            'card_style' => 'glass',
            'background_pattern' => 'gradient',
            'background_color' => '#123456',
            'text_color' => '#abcdef',
            'accent_color' => '#654321',
            'display_name_font_size' => '24',
        ];
    }

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

    public function test_admin_can_duplicate_a_user_with_a_new_card_and_copied_profile(): void
    {
        config(['app.admin_emails' => ['admin@example.com']]);

        $admin = User::factory()->create(['email' => 'admin@example.com']);
        $source = User::factory()->create([
            'name' => 'Source User',
            'email' => 'source@example.com',
            'card_id' => 'ID-2026-000200',
        ]);
        $sourceProfile = $source->profile()->create([
            'display_name' => 'Source Profile',
            'profile_builder_active' => true,
        ]);
        $sourceProfile->links()->create([
            'type' => 'website',
            'label' => 'Website',
            'value' => 'https://example.com',
            'sort_order' => 1,
        ]);
        Cards::create(['card_number' => $source->card_id, 'name' => 'Source Card']);

        $response = $this->actingAs($admin)->post(route('admin.users.duplicate', $source));

        $response->assertRedirect(route('admin.dashboard'));
        $duplicate = User::where('email', 'source.copy@example.com')->firstOrFail();
        $this->assertNotSame($source->card_id, $duplicate->card_id);
        $this->assertDatabaseHas('cards', ['card_number' => $duplicate->card_id]);
        $this->assertSame('Source Profile', $duplicate->profile->display_name);
        $this->assertCount(1, $duplicate->profile->links);
    }

    public function test_admin_profile_editor_renders_for_the_selected_user(): void
    {
        config(['app.admin_emails' => ['admin@example.com']]);

        $admin = User::factory()->create(['email' => 'admin@example.com']);
        $user = User::factory()->create(['name' => 'Editable User', 'card_id' => 'ID-2026-000202']);
        $user->profile()->create(['display_name' => 'Editable Profile']);

        $this->actingAs($admin)
            ->get(route('admin.users.profile.edit', $user))
            ->assertOk()
            ->assertSee('Editable User')
            ->assertSee(route('admin.users.profile.update', $user), false)
            ->assertSee('Save Profile');
    }

    public function test_admin_can_search_users_by_name_email_or_card_id(): void
    {
        config(['app.admin_emails' => ['admin@example.com']]);

        $admin = User::factory()->create(['email' => 'admin@example.com']);
        User::factory()->create([
            'name' => 'Searchable Member',
            'email' => 'searchable@example.com',
            'card_id' => 'ID-2026-000204',
        ]);
        User::factory()->create([
            'name' => 'Hidden Member',
            'email' => 'hidden@example.com',
            'card_id' => 'ID-2026-000205',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard', ['user_search' => 'searchable@example.com']))
            ->assertOk()
            ->assertSee('Searchable Member')
            ->assertDontSee('Hidden Member')
            ->assertSee('searchable@example.com');

        $this->actingAs($admin)
            ->get(route('admin.dashboard', ['user_search' => '000205']))
            ->assertOk()
            ->assertSee('Hidden Member')
            ->assertDontSee('Searchable Member');
    }

    public function test_user_can_update_personal_name_and_email_from_profile_editor(): void
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
        ]);

        $this->actingAs($user)
            ->post(route('profile.personal-info.update'), [
                'name' => 'New Name',
                'email' => 'new@example.com',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'email' => 'new@example.com',
        ]);
    }

    public function test_public_profile_views_are_counted_and_shown_in_the_editor(): void
    {
        $user = User::factory()->create(['card_id' => 'ID-2026-000203']);
        $user->profile()->create([
            'display_name' => 'Viewed Profile',
            'profile_builder_active' => true,
        ]);

        $this->get(route('profile.public', $user->card_id))->assertOk();
        $this->get(route('profile.public', $user->card_id))->assertOk();

        $this->assertSame(2, $user->fresh()->profile->profile_view_count);
        $this->actingAs($user)
            ->get(route('profile.edit'))
            ->assertOk()
            ->assertSee('2')
            ->assertSee('live profile views');
    }

    public function test_admin_can_deactivate_and_reactivate_a_public_profile(): void
    {
        config(['app.admin_emails' => ['admin@example.com']]);

        $admin = User::factory()->create(['email' => 'admin@example.com']);
        $user = User::factory()->create(['card_id' => 'ID-2026-000201']);
        $user->profile()->create([
            'display_name' => 'Active Profile',
            'profile_builder_active' => true,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.users.profile-builder.toggle', $user))
            ->assertRedirect(route('admin.dashboard'));
        $this->assertFalse($user->fresh()->profile->profile_builder_active);
        $this->get(route('profile.public', $user->card_id))->assertNotFound();

        $this->actingAs($admin)
            ->post(route('admin.users.profile-builder.toggle', $user))
            ->assertRedirect(route('admin.dashboard'));
        $this->assertTrue($user->fresh()->profile->profile_builder_active);
        $this->get(route('profile.public', $user->card_id))->assertOk();
    }

    public function test_admin_can_delete_a_user_and_their_profile(): void
    {
        config(['app.admin_emails' => ['admin@example.com']]);

        $admin = User::factory()->create(['email' => 'admin@example.com']);
        $user = User::factory()->create();
        $profile = $user->profile()->create(['display_name' => 'To Delete']);
        ProfileLink::create([
            'user_profile_id' => $profile->id,
            'type' => 'website',
            'label' => 'Website',
            'value' => 'https://example.com',
            'sort_order' => 1,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $user))
            ->assertRedirect(route('admin.dashboard'));

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('user_profiles', ['id' => $profile->id]);
    }
}
