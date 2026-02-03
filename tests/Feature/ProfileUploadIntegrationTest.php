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

class ProfileUploadIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * This test will attempt to upload files to the configured S3 disk using
     * the same Livewire component used in production. It will clean up files
     * afterwards.
     *
     * Requirements: the .env AWS_* variables must point to a reachable S3/MinIO
     * endpoint and credentials with put/get/delete permissions on the bucket.
     */
    public function test_livewire_uploads_ktp_and_selfie_to_real_s3()
    {
        // Only run this integration test when explicitly enabled via env var.
        if (! (env('RUN_S3_INTEGRATION_TESTS') || getenv('RUN_S3_INTEGRATION_TESTS'))) {
            $this->markTestSkipped('S3 integration tests are disabled. Set RUN_S3_INTEGRATION_TESTS=true to enable.');
        }

        // Use the real s3 disk from configuration
        config(['filesystems.default' => 's3']);

        // Ensure the s3 disk is fully-configured for the SDK at runtime (region/endpoint/credentials)
        config(['filesystems.disks.s3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION') ?: 'us-east-1',
            'bucket' => env('AWS_BUCKET') ?: getenv('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => (bool) filter_var(env('AWS_USE_PATH_STYLE_ENDPOINT', false), FILTER_VALIDATE_BOOLEAN),
            'throw' => false,
        ]]);

        $user = User::factory()->create(['password' => bcrypt('password'), 'no_hp' => '08123456789', 'alamat' => 'Test Address']);
        $location = Location::factory()->create();

        $this->actingAs($user);

        // Use small fake images
        $ktp = UploadedFile::fake()->image('ktp-integration.jpg', 100, 100)->size(50);
        $selfie = UploadedFile::fake()->image('selfie-integration.jpg', 100, 100)->size(50);

        try {
            // Quick connectivity / permission check: try a simple put/get/delete
            try {
                Storage::disk('s3')->put('integration_check.txt', 'ok');
                $this->assertTrue(Storage::disk('s3')->exists('integration_check.txt'), 'Simple check file not found on S3');
                Storage::disk('s3')->delete('integration_check.txt');
            } catch (\Exception $diag) {
                $this->fail("S3 connectivity/permission check failed: " . $diag->getMessage());
            }

            Livewire::test(FormProfile::class)
                ->set('name', $user->name)
                ->set('email', $user->email)
                ->set('no_hp', '08123456789')
                ->set('alamat', 'Test Address')
                ->set('id_location', $location->id)
                ->set('jenis_kelamin', 1)
                ->set('ktp', $ktp)
                ->set('selfie', $selfie)
                ->call('updateProfile')
                ->assertRedirect(route('profile'));

            $user->refresh();

            $this->assertNotNull($user->ktp, 'User ktp path is null after upload');
            $this->assertNotNull($user->selfie, 'User selfie path is null after upload');

            // Verify files exist on the s3 disk
            $this->assertTrue(Storage::disk('s3')->exists($user->ktp), "KTP file '{$user->ktp}' was not found on S3 disk");
            $this->assertTrue(Storage::disk('s3')->exists($user->selfie), "Selfie file '{$user->selfie}' was not found on S3 disk");

            // Clean up files from S3
            Storage::disk('s3')->delete([$user->ktp, $user->selfie]);

        } catch (\Exception $e) {
            // If anything goes wrong (network, credentials, etc.), fail with helpful message
            $this->fail('Integration S3 upload failed: ' . get_class($e) . ' - ' . $e->getMessage());
        }
    }
}
