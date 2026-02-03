<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Http\Livewire\FormProfile;

class ProfileUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_livewire_uploads_ktp_and_selfie_to_s3()
    {
        // Fake the s3 disk and set it as default
        Storage::fake('s3');
        config(['filesystems.default' => 's3']);

        $user = User::factory()->create(['password' => bcrypt('password'), 'no_hp' => '08123456789', 'alamat' => 'Test Address']);
        $location = Location::factory()->create();

        $this->actingAs($user);

        Livewire::test(FormProfile::class)
            ->set('name', $user->name)
            ->set('email', $user->email)
            ->set('no_hp', '08123456789')
            ->set('alamat', 'Test Address')
            ->set('id_location', $location->id)
            ->set('jenis_kelamin', 1)
            ->set('ktp', UploadedFile::fake()->image('ktp.jpg'))
            ->set('selfie', UploadedFile::fake()->image('selfie.jpg'))
            ->call('updateProfile')
            ->assertRedirect(route('profile'));

        // Ensure files were stored in the s3 disk under the expected folders
        $ktpFiles = Storage::disk('s3')->allFiles('ktp');
        $selfieFiles = Storage::disk('s3')->allFiles('selfie');

        $this->assertCount(1, $ktpFiles);
        $this->assertCount(1, $selfieFiles);

        $user->refresh();
        $this->assertNotNull($user->ktp);
        $this->assertNotNull($user->selfie);

        Storage::disk('s3')->assertExists($user->ktp);
        Storage::disk('s3')->assertExists($user->selfie);
    }
}
