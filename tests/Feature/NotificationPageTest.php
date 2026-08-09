<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Concerns\ActsWithRoles;
use Tests\TestCase;

class NotificationPageTest extends TestCase
{
    use ActsWithRoles, RefreshDatabase;

    public function test_guests_are_redirected_away_from_the_notification_page(): void
    {
        $this->get(route('notification.index'))->assertRedirect(route('login'));
    }

    public function test_the_notification_page_lists_the_signed_in_users_notifications(): void
    {
        $admin = $this->actingAsSuperAdmin();
        $other = User::factory()->create();

        $this->makeNotification($admin, 'Mine to read');
        $this->makeNotification($other, 'Someone elses');

        $this->actingAs($admin)
            ->get(route('notification.index'))
            ->assertOk()
            ->assertSee('Mine to read')
            ->assertDontSee('Someone elses');
    }

    public function test_a_notification_can_be_marked_as_read(): void
    {
        $admin = $this->actingAsSuperAdmin();
        $id = $this->makeNotification($admin, 'Unread item');

        $this->actingAs($admin)
            ->from(route('notification.index'))
            ->post(route('notification.read', $id))
            ->assertRedirect(route('notification.index'));

        $this->assertSame(0, $admin->unreadNotifications()->count());
    }

    public function test_marking_a_notification_read_follows_its_action_url(): void
    {
        $admin = $this->actingAsSuperAdmin();
        $id = $this->makeNotification($admin, 'Go somewhere', ['url' => route('dashboard')]);

        $this->actingAs($admin)
            ->post(route('notification.read', $id))
            ->assertRedirect(route('dashboard'));
    }

    public function test_a_user_cannot_touch_another_users_notification(): void
    {
        $admin = $this->actingAsSuperAdmin();
        $other = User::factory()->create();
        $id = $this->makeNotification($other, 'Not yours');

        $this->actingAs($admin)
            ->post(route('notification.read', $id))
            ->assertNotFound();

        $this->actingAs($admin)
            ->delete(route('notification.destroy', $id))
            ->assertNotFound();

        $this->assertSame(1, $other->unreadNotifications()->count());
    }

    public function test_all_notifications_can_be_marked_read_at_once(): void
    {
        $admin = $this->actingAsSuperAdmin();
        $this->makeNotification($admin, 'First');
        $this->makeNotification($admin, 'Second');

        $this->actingAs($admin)
            ->from(route('notification.index'))
            ->post(route('notification.readAll'))
            ->assertRedirect(route('notification.index'));

        $this->assertSame(0, $admin->unreadNotifications()->count());
    }

    public function test_a_notification_can_be_deleted(): void
    {
        $admin = $this->actingAsSuperAdmin();
        $id = $this->makeNotification($admin, 'Delete me');

        $this->actingAs($admin)
            ->from(route('notification.index'))
            ->delete(route('notification.destroy', $id))
            ->assertRedirect(route('notification.index'));

        $this->assertSame(0, $admin->notifications()->count());
    }

    /**
     * @param  array<string, mixed>  $extra
     */
    private function makeNotification(User $user, string $title, array $extra = []): string
    {
        $id = (string) Str::uuid();

        DB::table('notifications')->insert([
            'id' => $id,
            'type' => 'App\\Notifications\\GenericNotification',
            'notifiable_type' => $user->getMorphClass(),
            'notifiable_id' => $user->getKey(),
            'data' => json_encode(array_merge(['title' => $title], $extra)),
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $id;
    }
}
