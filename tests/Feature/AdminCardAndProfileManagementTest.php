<?php

namespace Tests\Feature;

use App\Mail\GuestCheckoutSummaryMail;
use App\Mail\GuestOrderReceiptMail;
use App\Models\Cards;
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
}
