<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_loads_for_authenticated_user()
    {
        // Create a user directly in the DB to avoid potential hashed-cast issues in PHPUnit
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

        // Ensure the user has a valid barcode
        $barcode = \App\Http\Controllers\HelperController::generateBarcode();
        \Illuminate\Support\Facades\DB::table('users')->where('id', $user->id)->update(['barcode' => $barcode]);
        $user->refresh();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
        $response->assertViewIs('base-2.profile.index');
    }

    public function test_profile_page_shows_ktp_and_selfie_preview_when_uploaded()
    {
        $userId = \Illuminate\Support\Facades\DB::table('users')->insertGetId([
            'name' => 'Test User',
            'email' => 'test+' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'no_hp' => '08123456789',
            'alamat' => 'Test Address',
            'ktp' => 'uploads/ktp/sample-ktp.jpg',
            'selfie' => 'uploads/selfie/sample-selfie.jpg',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user = User::find($userId);

        // Ensure the user has a valid barcode
        $barcode = \App\Http\Controllers\HelperController::generateBarcode();
        \Illuminate\Support\Facades\DB::table('users')->where('id', $user->id)->update(['barcode' => $barcode]);
        $user->refresh();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
        $response->assertSee('Foto KTP');
        $response->assertSee('Foto Selfie');
        $response->assertSee(storage_url('uploads/ktp/sample-ktp.jpg'));
        $response->assertSee(storage_url('uploads/selfie/sample-selfie.jpg'));
    }

    public function test_profile_page_shows_placeholder_when_no_ktp_or_selfie()
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

        // Ensure the user has a valid barcode
        $barcode = \App\Http\Controllers\HelperController::generateBarcode();
        \Illuminate\Support\Facades\DB::table('users')->where('id', $user->id)->update(['barcode' => $barcode]);
        $user->refresh();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
        $response->assertSee('Foto KTP');
        $response->assertSee('Foto Selfie');
        $response->assertSee('Belum di-upload');
    }

    public function test_profile_page_shows_ktp_and_selfie_for_gleyadmin_user()
    {
        // This test uses the actual database, not the test database
        $user = \App\Models\User::where('email', 'gleyadmin@gmail.com')->first();

        if (!$user) {
            $this->markTestSkipped('User gleyadmin@gmail.com not found in database');
        }

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
        $response->assertSee('Foto KTP');
        $response->assertSee('Foto Selfie');

        if ($user->ktp) {
            $response->assertSee(storage_url($user->ktp));
        }

        if ($user->selfie) {
            $response->assertSee(storage_url($user->selfie));
        }
    }
}
