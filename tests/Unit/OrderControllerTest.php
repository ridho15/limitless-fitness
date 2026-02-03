<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Paket;
use App\Models\OrderPaket;
use App\Http\Controllers\OrderController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $orderController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderController = new OrderController();
    }

    /**
     * Test orderPackage method returns correct view
     */
    public function test_order_package_method_returns_correct_view()
    {
        $paket = Paket::factory()->create([
            'nama' => 'Test Package',
            'harga' => 100000
        ]);

        $response = $this->get('/order-package/' . $paket->id);

        $response->assertStatus(200)
                ->assertViewIs('order-package')
                ->assertViewHas('paket', $paket);
    }

    /**
     * Test postOrderPackage creates new user and order
     */
    public function test_post_order_package_creates_new_user_and_order()
    {
        $paket = Paket::factory()->create([
            'nama' => 'Premium Package',
            'harga' => 200000,
            'diskon' => 0,
            'valid_month' => 6
        ]);

        $requestData = [
            'nama' => 'John Doe',
            'email' => 'john@example.com',
            'no_hp' => '08123456789',
            'alamat' => 'Jl. Test No. 123'
        ];

        // Use HTTP request to allow validation and redirects to be handled by framework
        $response = $this->post('/order-package/' . $paket->id, $requestData);

        // Check redirect
        if ($response->getStatusCode() !== 302) {
            $this->fail('Unexpected response status: ' . $response->getStatusCode() . ' content: ' . $response->getContent());
        }

        // Directly call controller to capture internal exception (debugging)
        try {
            $controller = new OrderController();
            $controller->postOrderPackage(Request::create('/order-package/' . $paket->id, 'POST', $requestData), $paket->id);
        } catch (\Exception $e) {
            $this->fail('Controller threw: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine() . '\n' . $e->getTraceAsString());
        }

        // Ensure there is no generic error in session
        $this->assertFalse($response->getSession()->has('error'), 'Controller returned an error in session: ' . json_encode($response->getSession()->get('error')));

        // Check database
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'no_hp' => '08123456789',
            'alamat' => 'Jl. Test No. 123'
        ]);

        $user = User::where('email', 'john@example.com')->first();
        $this->assertDatabaseHas('order_pakets', [
            'id_user' => $user->id,
            'id_paket' => $paket->id,
            'harga' => 200000,
            'status' => 2
        ]);
    }

    /**
     * Test postOrderPackage updates existing user
     */
    public function test_post_order_package_updates_existing_user()
    {
        $existingUser = User::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'no_hp' => '08198765432',
            'alamat' => 'Old Address'
        ]);

        $paket = Paket::factory()->create([
            'nama' => 'Basic Package',
            'harga' => 150000
        ]);

        $requestData = [
            'nama' => 'Jane Smith Updated',
            'email' => 'jane@example.com',
            'no_hp' => '08198765432',
            'alamat' => 'New Address'
        ];

        // Use HTTP request so validation and redirects are handled by the framework
        $response = $this->post('/order-package/' . $paket->id, $requestData);

        $response->assertStatus(302);

        // Check that user was updated
        $updatedUser = User::find($existingUser->id);
        $this->assertEquals('Jane Smith Updated', $updatedUser->name);
        $this->assertEquals('New Address', $updatedUser->alamat);

        // Check that order was created
        $this->assertDatabaseHas('order_pakets', [
            'id_user' => $existingUser->id,
            'id_paket' => $paket->id
        ]);
    }

    /**
     * Test postOrderPackage prevents duplicate active orders
     */
    public function test_post_order_package_prevents_duplicate_active_orders()
    {
        $user = User::factory()->create();
        $paket = Paket::factory()->create();

        // Create existing active order
        OrderPaket::factory()->create([
            'id_user' => $user->id,
            'id_paket' => $paket->id,
            'status' => 1,
            'is_active' => 1
        ]);

        $requestData = [
            'nama' => $user->name,
            'email' => $user->email,
            'no_hp' => $user->no_hp,
            'alamat' => $user->alamat
        ];

        // Use HTTP request so validation and redirects are handled by the framework
        $response = $this->post('/order-package/' . $paket->id, $requestData);

        $response->assertStatus(302);

        // Should not create new order
        $this->assertEquals(1, OrderPaket::where('id_user', $user->id)->where('id_paket', $paket->id)->count());

        // Should not create new order
        $this->assertEquals(1, OrderPaket::where('id_user', $user->id)->where('id_paket', $paket->id)->count());
    }

    /**
     * Test postOrderPackage validation
     */
    public function test_post_order_package_validation()
    {
        $paket = Paket::factory()->create();

        $requestData = [
            'nama' => '',
            'email' => 'invalid-email',
            'no_hp' => '',
            'alamat' => ''
        ];

        // Use HTTP request to trigger validation handling
        $response = $this->post('/order-package/' . $paket->id, $requestData);

        $response->assertRedirect()
                ->assertSessionHasErrors(['nama', 'email', 'no_hp', 'alamat']);
    }

    /**
     * Test postOrderPackage with non-existent package
     */
    public function test_post_order_package_with_non_existent_package()
    {
        $requestData = [
            'nama' => 'John Doe',
            'email' => 'john@example.com',
            'no_hp' => '08123456789',
            'alamat' => 'Test Address'
        ];

        // Use HTTP POST to non-existent package and expect a redirect with error
        $response = $this->post('/order-package/999', $requestData);

        $response->assertRedirect()
                ->assertSessionHas('error');
    }

    /**
     * Test publicPackages method
     */
    public function test_public_packages_method()
    {
        $paket = Paket::factory()->create(['is_show_public' => 1]);

        $controller = new OrderController();
        $response = $controller->publicPackages();

        $this->assertInstanceOf(\Illuminate\View\View::class, $response);
        $this->assertArrayHasKey('pakets', $response->getData());
    }
}