<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileVoucherTest extends TestCase
{
    use RefreshDatabase;

    public function test_voucher_page_loads_for_authenticated_user()
    {
        $userId = \Illuminate\Support\Facades\DB::table('users')->insertGetId([
            'name' => 'Test User',
            'email' => 'test+' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'no_hp' => '08123456789',
            'alamat' => 'Test Address',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user = User::find($userId);

        // Ensure the user has a valid barcode (some methods rely on it)
        $barcode = \App\Http\Controllers\HelperController::generateBarcode();
        \Illuminate\Support\Facades\DB::table('users')->where('id', $user->id)->update(['barcode' => $barcode]);
        $user->refresh();

        $response = $this->actingAs($user)->get('/profile/voucher');

        $response->assertStatus(200);
        $response->assertViewIs('base-2.profile.voucher');
    }
}
