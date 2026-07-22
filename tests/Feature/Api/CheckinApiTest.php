<?php

namespace Tests\Feature\Api;

use App\Models\CheckInOut;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckinApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_duplicate_checkin_within_five_minutes_is_idempotent(): void
    {
        $payload = ['id_user' => 10, 'jadwal' => now()->toISOString(), 'status' => 'checkin', 'id_location' => 1, 'id_order_paket' => 5];
        $first = $this->postJson('/api/checkin', $payload)->assertCreated()->json('data.id');
        $second = $this->postJson('/api/checkin', $payload)->assertOk()->json('data.id');
        $this->assertSame($first, $second);
        $this->assertSame(1, CheckInOut::count());
    }
}
