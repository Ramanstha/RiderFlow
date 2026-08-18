<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\ActsWithRoles;
use Tests\TestCase;

class AdminContactMessageTest extends TestCase
{
    use ActsWithRoles, RefreshDatabase;

    protected function message(array $overrides = []): ContactMessage
    {
        return ContactMessage::create(array_merge([
            'name' => 'Sita Rai',
            'email' => 'sita@example.com',
            'subject' => 'Rider enquiry',
            'phone' => '9801074948',
            'message' => 'We need 20 riders in Kathmandu.',
        ], $overrides));
    }

    public function test_admins_can_view_the_contact_message_list(): void
    {
        $admin = $this->actingAsSuperAdmin();
        $this->message();

        $response = $this->actingAs($admin)->get(route('contact-message.index'));

        $response->assertOk();
        $response->assertViewIs('admin.contact-message.index');
        $response->assertSee('sita@example.com');
    }

    public function test_the_list_can_be_searched(): void
    {
        $admin = $this->actingAsSuperAdmin();
        $this->message();
        $this->message(['name' => 'Hari Thapa', 'email' => 'hari@example.com', 'subject' => 'Pricing']);

        $response = $this->actingAs($admin)->get(route('contact-message.index', ['search' => 'hari']));

        $response->assertOk();
        $response->assertSee('hari@example.com');
        $response->assertDontSee('sita@example.com');
    }

    public function test_admins_can_view_a_single_message(): void
    {
        $admin = $this->actingAsSuperAdmin();
        $message = $this->message();

        $response = $this->actingAs($admin)->get(route('contact-message.show', $message));

        $response->assertOk();
        $response->assertViewIs('admin.contact-message.show');
        $response->assertSee('We need 20 riders in Kathmandu.');
    }

    public function test_admins_can_delete_a_message(): void
    {
        $admin = $this->actingAsSuperAdmin();
        $message = $this->message();

        $this->actingAs($admin)
            ->delete(route('contact-message.destroy', $message))
            ->assertRedirect(route('contact-message.index'));

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_users_without_site_manage_cannot_view_messages(): void
    {
        $viewer = $this->actingAsViewer();
        $this->message();

        $this->actingAs($viewer)->get(route('contact-message.index'))->assertForbidden();
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('contact-message.index'))->assertRedirect(route('login'));
    }
}
