<?php

namespace Tests\Feature\Api;

use App\Models\Location;
use App\Models\OrderPaket;
use App\Models\Paket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckPaketTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_active_user_with_active_package_is_allowed(): void
    {
        Carbon::setTestNow('2026-07-22 10:00:00');
        $user = User::factory()->create(['status' => 1]);
        $package = $this->createOrder($user, ['expired_date' => now()->addMonth()]);

        $this->postJson('/api/check-paket', ['id_user' => $user->id])
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.total_active_packages', 1)
            ->assertJsonPath('data.packages.0.valid_month', $package->valid_month);
    }

    public function test_inactive_user_is_rejected_even_with_active_package(): void
    {
        $user = User::factory()->create(['status' => 0]);
        $this->createOrder($user);

        $this->postJson('/api/check-paket', ['id_user' => $user->id])
            ->assertNotFound()
            ->assertJsonPath('status', false)
            ->assertJsonPath('message', 'Akun user tidak aktif')
            ->assertJsonMissingPath('data.packages');
    }

    public function test_expired_package_is_rejected(): void
    {
        $user = User::factory()->create(['status' => 1]);
        $this->createOrder($user, ['expired_date' => now()->subSecond()]);

        $this->postJson('/api/check-paket', ['id_user' => $user->id])
            ->assertNotFound()
            ->assertJsonPath('status', false);
    }

    public function test_unpaid_or_inactive_order_is_rejected(): void
    {
        $user = User::factory()->create(['status' => 1]);
        $this->createOrder($user, ['status' => 2]);
        $this->createOrder($user, ['is_active' => 0]);

        $this->postJson('/api/check-paket', ['id_user' => $user->id])
            ->assertNotFound()
            ->assertJsonPath('status', false);
    }

    public function test_unknown_user_returns_validation_error(): void
    {
        $this->postJson('/api/check-paket', ['id_user' => 999999])
            ->assertUnprocessable()
            ->assertJsonPath('status', false)
            ->assertJsonPath('message', 'Validasi gagal');
    }

    public function test_all_active_packages_are_returned(): void
    {
        $user = User::factory()->create(['status' => 1]);
        $this->createOrder($user);
        $this->createOrder($user);

        $this->postJson('/api/check-paket', ['id_user' => $user->id])
            ->assertOk()
            ->assertJsonPath('data.total_active_packages', 2)
            ->assertJsonCount(2, 'data.packages');
    }

    public function test_package_expiring_at_the_current_time_is_still_active(): void
    {
        Carbon::setTestNow('2026-07-22 10:00:00');
        $user = User::factory()->create(['status' => 1]);
        $this->createOrder($user, ['expired_date' => now()]);

        $this->postJson('/api/check-paket', ['id_user' => $user->id])
            ->assertOk()
            ->assertJsonPath('status', true);
    }

    private function createOrder(User $user, array $overrides = []): Paket
    {
        $location = Location::factory()->create();
        $package = Paket::factory()->create();

        OrderPaket::create(array_merge([
            'id_user' => $user->id,
            'id_paket' => $package->id,
            'harga' => $package->harga,
            'status' => 1,
            'is_active' => 1,
            'expired_date' => now()->addMonth(),
            'id_location' => $location->id,
        ], $overrides));

        return $package;
    }
}
