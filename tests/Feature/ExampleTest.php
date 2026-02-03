<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Paket;
use App\Models\OrderPaket;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test order package page loads successfully
     */
    public function test_order_package_page_loads_successfully(): void
    {
        // Create a test package
        $paket = Paket::factory()->create([
            'nama' => 'Test Package',
            'harga' => 100000,
            'is_show_public' => 1,
            'valid_month' => 3
        ]);

        $response = $this->get('/order-package/' . $paket->id);

        $response->assertStatus(200)
                ->assertSee('PESAN PAKET FITNESS')
                ->assertSee('Test Package')
                ->assertSee('Rp 100.000');
    }

    /**
     * Test order package with new user
     */
    public function test_order_package_with_new_user(): void
    {
        // Create a test package
        $paket = Paket::factory()->create([
            'nama' => 'Premium Package',
            'harga' => 200000,
            'diskon' => 0,
            'is_show_public' => 1,
            'valid_month' => 6
        ]);

        $orderData = [
            'nama' => 'John Doe',
            'email' => 'john@example.com',
            'no_hp' => '08123456789',
            'alamat' => 'Jl. Test No. 123',
            'catatan' => 'Test order'
        ];

        $response = $this->post('/order-package/' . $paket->id, $orderData);

        // Redirect to order payment page with public token
        $this->assertStringContainsString('/order-payment/', $response->headers->get('Location'));
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'no_hp' => '08123456789',
            'alamat' => 'Jl. Test No. 123'
        ]);
        $this->assertDatabaseHas('order_pakets', [
            'id_paket' => $paket->id,
            'harga' => 200000,
            'status' => 2 // belum lunas
        ]);
    }

    /**
     * Test order package with existing user
     */
    public function test_order_package_with_existing_user(): void
    {
        // Create existing user
        $existingUser = User::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'no_hp' => '08198765432',
            'alamat' => 'Old Address'
        ]);

        // Create a test package
        $paket = Paket::factory()->create([
            'nama' => 'Basic Package',
            'harga' => 150000,
            'diskon' => 0,
            'is_show_public' => 1,
            'valid_month' => 3
        ]);

        $orderData = [
            'nama' => 'Jane Smith Updated',
            'email' => 'jane@example.com', // same email
            'no_hp' => '08198765432',
            'alamat' => 'New Address',
            'catatan' => 'Updated order'
        ];

        $response = $this->post('/order-package/' . $paket->id, $orderData);

        // Redirect to order payment page with public token
        $this->assertStringContainsString('/order-payment/', $response->headers->get('Location'));

        // Check that user data was updated
        $this->assertDatabaseHas('users', [
            'email' => 'jane@example.com',
            'name' => 'Jane Smith Updated',
            'alamat' => 'New Address'
        ]);

        // Check that order was created
        $this->assertDatabaseHas('order_pakets', [
            'id_user' => $existingUser->id,
            'id_paket' => $paket->id,
            'harga' => 150000
        ]);
    }

    /**
     * Test order package validation errors
     */
    public function test_order_package_validation_errors(): void
    {
        $paket = Paket::factory()->create();

        // Test with empty data
        $response = $this->post('/order-package/' . $paket->id, []);

        $response->assertRedirect()
                ->assertSessionHasErrors(['nama', 'email', 'no_hp', 'alamat']);
    }

    /**
     * Test order package with invalid email
     */
    public function test_order_package_invalid_email(): void
    {
        $paket = Paket::factory()->create();

        $orderData = [
            'nama' => 'John Doe',
            'email' => 'invalid-email',
            'no_hp' => '08123456789',
            'alamat' => 'Test Address'
        ];

        $response = $this->post('/order-package/' . $paket->id, $orderData);

        $response->assertRedirect()
                ->assertSessionHasErrors('email');
    }

    /**
     * Test duplicate active package order
     */
    public function test_duplicate_active_package_order(): void
    {
        $user = User::factory()->create();
        $paket = Paket::factory()->create();

        // Create existing active order
        OrderPaket::factory()->create([
            'id_user' => $user->id,
            'id_paket' => $paket->id,
            'status' => 1, // aktif
            'is_active' => 1
        ]);

        $orderData = [
            'nama' => $user->name,
            'email' => $user->email,
            'no_hp' => $user->no_hp,
            'alamat' => $user->alamat
        ];

        $response = $this->post('/order-package/' . $paket->id, $orderData);

        $response->assertRedirect()
                ->assertSessionHas('error', 'Anda sudah memiliki paket ini yang masih aktif. Silakan hubungi admin untuk informasi lebih lanjut.');
    }
}
