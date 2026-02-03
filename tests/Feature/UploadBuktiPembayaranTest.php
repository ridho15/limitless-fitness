<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\OrderPaket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadBuktiPembayaranTest extends TestCase
{
    use RefreshDatabase;

    public function test_upload_new_bukti_pembayaran_stores_file_and_updates_db()
    {
        Storage::fake('s3');
        config(['filesystems.default' => 's3']);

        $user = User::factory()->create();
        $order = OrderPaket::factory()->create([
            'id_user' => $user->id,
            'bukti_pembayaran' => null,
            'status' => 2
        ]);

        $this->actingAs($user);

        $file = UploadedFile::fake()->image('bukti1.jpg');

        $response = $this->post(route('upload-bukti-pembayaran', ['id' => $order->id]), [
            'bukti_pembayaran' => $file
        ]);

        $response->assertRedirect();
        $order->refresh();

        $this->assertNotNull($order->bukti_pembayaran);
        Storage::disk('s3')->assertExists($order->bukti_pembayaran);
    }

    public function test_reupload_replaces_old_file_and_deletes_old()
    {
        Storage::fake('s3');
        config(['filesystems.default' => 's3']);

        $user = User::factory()->create();
        $order = OrderPaket::factory()->create([
            'id_user' => $user->id,
            'status' => 2
        ]);

        // Put an existing file into per-order folder
        $initialPath = 'bukti_pembayaran/' . $order->id . '/old.jpg';
        Storage::disk('s3')->put($initialPath, 'old');
        $order->update(['bukti_pembayaran' => $initialPath]);

        $this->actingAs($user);

        // Sanity check: old file present before upload
        $filesBefore = Storage::disk('s3')->allFiles('bukti_pembayaran');
        $this->assertContains($initialPath, $filesBefore, 'Old file should exist before upload');

        $newFile = UploadedFile::fake()->image('bukti2.jpg');

        $response = $this->post(route('upload-bukti-pembayaran', ['id' => $order->id]), [
            'bukti_pembayaran' => $newFile
        ]);

        $response->assertRedirect();
        $order->refresh();

        $this->assertNotNull($order->bukti_pembayaran);
        $this->assertNotEquals('bukti_pembayaran/old.jpg', $order->bukti_pembayaran);
        Storage::disk('s3')->assertExists($order->bukti_pembayaran);

        $filesAfter = Storage::disk('s3')->allFiles('bukti_pembayaran');
        // Debug info on failure: list files
        $this->assertNotContains('bukti_pembayaran/old.jpg', $filesAfter, 'Old file still present after upload: ' . implode(',', $filesAfter));
    }

    public function test_billing_page_shows_change_button_when_bukti_exists()
    {
        Storage::fake('s3');
        config(['filesystems.default' => 's3']);

        $user = User::factory()->create();
        $order = OrderPaket::factory()->create([
            'id_user' => $user->id,
            'status' => 2,
            'is_active' => 0
        ]);

        $initialPath = 'bukti_pembayaran/' . $order->id . '/file.jpg';
        Storage::disk('s3')->put($initialPath, 'ok');
        $order->update(['bukti_pembayaran' => $initialPath]);

        $this->actingAs($user);

        $response = $this->get(route('profile.billing'));
        $response->assertStatus(200);
        $response->assertSee('Ubah Bukti Pembayaran');
    }
}
