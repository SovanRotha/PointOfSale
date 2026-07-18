<?php

namespace Tests\Feature;

use App\Models\Item\Category;
use App\Models\Item\MenuItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PosWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('secret1234'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'admin@example.com',
            'password' => 'secret1234',
        ]);

        $response->assertOk()
            ->assertJsonPath('message', 'Login successful');
    }

    public function test_order_can_be_created_with_items(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'Drinks',
            'description' => 'Beverages',
            'is_active' => true,
        ]);
        $menuItem = MenuItem::create([
            'name' => 'Coffee',
            'description' => 'Hot coffee',
            'cost_price' => 20,
            'selling_price' => 50,
            'category_id' => $category->id,
            'sku' => 'COF-001',
            'barcode' => '123456',
            'is_active' => true,
            'track_stock' => false,
            'stock_quantity' => 10,
            'low_stock_threshold' => 5,
        ]);

        $response = $this->actingAs($user)->postJson('/api/orders', [
            'type' => 'takeaway',
            'items' => [
                [
                    'menu_item_id' => $menuItem->id,
                    'quantity' => 2,
                    'modifiers' => [],
                ],
            ],
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'type' => 'takeaway',
            'status' => 'pending',
        ]);
    }

    public function test_payment_can_be_recorded_for_an_order(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'Food',
            'description' => 'Main dishes',
            'is_active' => true,
        ]);
        $menuItem = MenuItem::create([
            'name' => 'Pizza',
            'description' => 'Cheese pizza',
            'cost_price' => 30,
            'selling_price' => 100,
            'category_id' => $category->id,
            'sku' => 'PIZ-001',
            'barcode' => '654321',
            'is_active' => true,
            'track_stock' => false,
            'stock_quantity' => 10,
            'low_stock_threshold' => 5,
        ]);

        $orderResponse = $this->actingAs($user)->postJson('/api/orders', [
            'type' => 'dine_in',
            'items' => [
                [
                    'menu_item_id' => $menuItem->id,
                    'quantity' => 1,
                    'modifiers' => [],
                ],
            ],
        ]);

        $orderId = $orderResponse->json('data.id');

        $paymentResponse = $this->actingAs($user)->postJson('/api/payments', [
            'order_id' => $orderId,
            'payment_method' => 'cash',
            'amount' => 100,
        ]);

        $paymentResponse->assertCreated();
        $this->assertDatabaseHas('payments', [
            'order_id' => $orderId,
            'status' => 'completed',
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $orderId,
            'status' => 'paid',
        ]);
    }

    public function test_stock_log_can_be_created_and_update_stock_quantity(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'Snacks',
            'description' => 'Quick bites',
            'is_active' => true,
        ]);
        $menuItem = MenuItem::create([
            'name' => 'Burger',
            'description' => 'Beef burger',
            'cost_price' => 25,
            'selling_price' => 80,
            'category_id' => $category->id,
            'sku' => 'BUR-001',
            'barcode' => '111111',
            'is_active' => true,
            'track_stock' => true,
            'stock_quantity' => 10,
            'low_stock_threshold' => 5,
        ]);

        $response = $this->actingAs($user)->postJson('/api/stock_logs', [
            'menu_item_id' => $menuItem->id,
            'quantity_change' => 5,
            'change_type' => 'addition',
            'reason' => 'Restock',
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('stock_logs', [
            'menu_item_id' => $menuItem->id,
            'change_type' => 'addition',
        ]);
        $this->assertSame(15, $menuItem->fresh()->stock_quantity);
    }
}
