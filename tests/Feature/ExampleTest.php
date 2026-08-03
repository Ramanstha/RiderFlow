<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_view_the_public_home_page(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertViewIs('ui.content.home');
    }

    public function test_guests_can_view_the_public_about_page(): void
    {
        $response = $this->get('/about');

        $response->assertOk();
        $response->assertViewIs('ui.content.about');
    }

    public function test_guests_can_view_the_public_contact_page(): void
    {
        $response = $this->get('/contact');

        $response->assertOk();
        $response->assertViewIs('ui.content.contact');
    }

    public function test_guests_can_submit_the_contact_form(): void
    {
        $response = $this->post('/contact', [
            'name' => 'Sita Rai',
            'email' => 'sita@example.com',
            'subject' => 'Rider enquiry',
            'phone' => '9801074948',
            'message' => 'We need 20 riders in Kathmandu.',
        ]);

        $response->assertRedirect(route('contact'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'sita@example.com',
            'subject' => 'Rider enquiry',
        ]);
    }

    public function test_the_contact_form_rejects_invalid_input(): void
    {
        $response = $this->post('/contact', [
            'name' => '',
            'email' => 'not-an-email',
            'message' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'message']);
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_the_dashboard_redirects_guests_to_login(): void
    {
        // The admin dashboard lives at /admin — route-named "dashboard".
        $response = $this->get('/admin');

        $response->assertRedirect(route('login'));
    }
}
