<?php

namespace Tests\Feature;

use App\Models\License;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    protected function createUser(array $overrides = []): User
    {
        $attrs = array_merge([
            'name' => 'Test User',
            'email' => 'user'.uniqid('', true).'@example.com',
            'password' => 'password',
        ], $overrides);

        $type = $attrs['type'] ?? 0;
        unset($attrs['type']);

        $user = new User($attrs);
        $user->type = $type;
        $user->save();

        return $user->fresh();
    }

    public function test_state_changing_license_reissue_rejects_get()
    {
        $user = $this->createUser();
        $license = $user->license()->create([
            'key' => 'License-TestKey123456',
            'ip' => '1.2.3.4',
            'status' => 'Active',
            'expireable' => false,
            'expires_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/licenses/'.$license->id.'/reissue');
        $response->assertStatus(405);
        $this->assertSame('1.2.3.4', $license->fresh()->ip);
    }

    public function test_license_reissue_via_post_clears_ip()
    {
        $user = $this->createUser();
        $license = $user->license()->create([
            'key' => 'License-TestKey654321',
            'ip' => '1.2.3.4',
            'status' => 'Active',
            'expireable' => false,
            'expires_at' => now(),
        ]);

        $response = $this->actingAs($user)->post('/licenses/'.$license->id.'/reissue');
        $response->assertRedirect();
        $this->assertSame('', $license->fresh()->ip);
    }

    public function test_verify_rejects_expired_license_even_if_status_active()
    {
        $user = $this->createUser();
        $license = $user->license()->create([
            'key' => 'License-ExpiredKey0001',
            'ip' => '127.0.0.1',
            'status' => 'Active',
            'expireable' => true,
            'expires_at' => now()->subDay(),
        ]);

        $response = $this->getJson('/api/license/'.$license->key);

        $response->assertStatus(403)
            ->assertJson(['valid' => false]);
        $this->assertSame('Expired', $license->fresh()->status);
    }

    public function test_api_user_create_cannot_set_admin_type()
    {
        $admin = $this->createUser(['type' => 1, 'email' => 'admin@example.com']);
        $token = 'test-admin-token';
        $admin->forceFill(['api_token' => hash('sha256', $token)])->save();

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/create', [
                'name' => 'Hacker',
                'email' => 'hacker@example.com',
                'password' => 'password123',
                'type' => 1,
            ]);

        $response->assertStatus(201);
        $created = User::where('email', 'hacker@example.com')->first();
        $this->assertNotNull($created);
        $this->assertSame(0, (int) $created->type);
    }

    public function test_api_cannot_delete_admin_users()
    {
        $admin = $this->createUser(['type' => 1, 'email' => 'admin-del@example.com']);
        $otherAdmin = $this->createUser(['type' => 1, 'email' => 'other-admin@example.com']);
        $token = 'test-admin-token-2';
        $admin->forceFill(['api_token' => hash('sha256', $token)])->save();

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson('/api/user/'.$otherAdmin->email.'/delete');

        $response->assertStatus(403);
        $this->assertDatabaseHas('users', ['email' => 'other-admin@example.com']);
    }

    public function test_api_missing_license_returns_http_404()
    {
        $response = $this->getJson('/api/license/does-not-exist');
        $response->assertStatus(404)
            ->assertJson(['status' => 404, 'error' => true]);
    }

    public function test_type_is_not_mass_assignable()
    {
        $user = new User([
            'name' => 'Mass Assign',
            'email' => 'mass@example.com',
            'password' => 'password',
            'type' => 1,
        ]);
        $user->type = 0;
        $user->save();

        // Even if type was passed to the constructor fill, fillable should ignore it;
        // we explicitly set type=0 above. Re-fill attempt:
        $user->fill(['type' => 1]);
        $user->save();

        $this->assertSame(0, (int) $user->fresh()->type);
    }
}
