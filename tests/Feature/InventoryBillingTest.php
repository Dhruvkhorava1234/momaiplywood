<?php

namespace Tests\Feature;

use App\Models\Bill;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryBillingTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create([
            'name' => 'Nirmal Kumar P',
            'email' => 'admin@example.com',
        ]);
    }

    public function test_dashboard_renders_with_metrics_for_admin(): void
    {
        $response = $this->actingAs($this->admin)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Inventory Management');
        $response->assertSee('Total Products');
        $response->assertSee('Total Stock');
    }

    public function test_can_create_product_and_manage_stock(): void
    {
        $response = $this->actingAs($this->admin)->post(route('products.store'), [
            'name' => 'Commercial Inverter 10KVA',
            'sku' => 'INV-TEST-1',
            'category' => 'Inverters',
            'cost_price' => 75000,
            'selling_price' => 100000,
            'stock_quantity' => 10,
            'min_alert_stock' => 2,
            'unit' => 'pcs',
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'sku' => 'INV-TEST-1',
            'stock_quantity' => 10,
        ]);

        $product = Product::where('sku', 'INV-TEST-1')->first();

        // Adjust stock (+5)
        $adjustResponse = $this->actingAs($this->admin)->post(route('products.adjust-stock', $product), [
            'adjustment_type' => 'add',
            'quantity' => 5,
        ]);

        $adjustResponse->assertSessionHas('success');
        $this->assertEquals(15, $product->fresh()->stock_quantity);
    }

    public function test_make_bill_with_partial_payment_generates_slip_and_records_history(): void
    {
        // Setup: Item worth 1 Lakh (₹1,00,000), Stock: 10
        $product = Product::create([
            'name' => 'Commercial Inverter 10KVA',
            'sku' => 'INV-10K',
            'category' => 'Inverters',
            'cost_price' => 75000,
            'selling_price' => 100000,
            'stock_quantity' => 10,
            'min_alert_stock' => 2,
            'unit' => 'pcs',
        ]);

        // Customer takes 1 Lakh item, pays 50,000
        $response = $this->actingAs($this->admin)->post(route('bills.store'), [
            'customer_name' => 'Rajesh Sharma',
            'customer_phone' => '9876543210',
            'discount' => 0,
            'paid_amount' => 50000,
            'payment_method' => 'cash',
            'notes' => 'Customer took 1 Lakh item, gave 50,000 advance',
            'items' => [
                [
                    'product_id' => $product->id,
                    'unit_price' => 100000,
                    'quantity' => 1,
                ],
            ],
        ]);

        // Verify Bill is created with 50,000 due
        $bill = Bill::first();
        $this->assertNotNull($bill);
        $response->assertRedirect(route('bills.slip', $bill));

        $this->assertEquals(100000, (float) $bill->grand_total);
        $this->assertEquals(50000, (float) $bill->paid_amount);
        $this->assertEquals(50000, (float) $bill->due_amount);
        $this->assertEquals('partial', $bill->payment_status);

        // Verify Stock decremented by 1 (10 - 1 = 9)
        $this->assertEquals(9, $product->fresh()->stock_quantity);

        // Verify Customer Khata due is updated
        $customer = Customer::where('phone', '9876543210')->first();
        $this->assertNotNull($customer);
        $this->assertEquals(50000, (float) $customer->total_due);

        // Verify Slip page renders with accurate details
        $slipResponse = $this->actingAs($this->admin)->get(route('bills.slip', $bill));
        $slipResponse->assertStatus(200);
        $slipResponse->assertSee('100,000.00');
        $slipResponse->assertSee('50,000.00');
        $slipResponse->assertSee('Commercial Inverter 10KVA');
        $slipResponse->assertSee('Balance Due (Khata)');

        // Test subsequent payment against remaining due
        $payResponse = $this->actingAs($this->admin)->post(route('bills.payment', $bill), [
            'amount' => 50000,
            'payment_method' => 'upi',
            'note' => 'Remaining balance cleared via UPI',
        ]);

        $payResponse->assertSessionHas('success');
        $this->assertEquals(0, (float) $bill->fresh()->due_amount);
        $this->assertEquals(100000, (float) $bill->fresh()->paid_amount);
        $this->assertEquals('paid', $bill->fresh()->payment_status);
        $this->assertEquals(0, (float) $customer->fresh()->total_due);
    }

    public function test_make_bill_with_direct_unlisted_product(): void
    {
        $response = $this->actingAs($this->admin)->post(route('bills.store'), [
            'customer_name' => 'Ketan Patel',
            'customer_phone' => '9988776655',
            'discount' => 50,
            'paid_amount' => 450,
            'payment_method' => 'cash',
            'notes' => 'Custom unlisted hardware item',
            'items' => [
                [
                    'product_id' => null,
                    'product_name' => 'Instant Custom Hardware Fitting',
                    'unit_price' => 250,
                    'quantity' => 2,
                ],
            ],
        ]);

        $bill = Bill::where('customer_name', 'Ketan Patel')->first();
        $this->assertNotNull($bill);
        $response->assertRedirect(route('bills.slip', $bill));

        $this->assertEquals(500, (float) $bill->subtotal);
        $this->assertEquals(450, (float) $bill->grand_total);
        $this->assertEquals(450, (float) $bill->paid_amount);
        $this->assertEquals(0, (float) $bill->due_amount);
        $this->assertEquals('paid', $bill->payment_status);

        $item = $bill->items()->first();
        $this->assertNotNull($item);
        $this->assertNull($item->product_id);
        $this->assertEquals('Instant Custom Hardware Fitting', $item->product_name);
        $this->assertEquals(250, (float) $item->unit_price);
        $this->assertEquals(2, $item->quantity);
    }

    public function test_product_is_soft_deleted(): void
    {
        $product = Product::create([
            'name' => 'Solar Battery 150Ah',
            'sku' => 'BAT-150',
            'category' => 'Batteries',
            'cost_price' => 12000,
            'selling_price' => 16000,
            'stock_quantity' => 5,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('products.destroy', $product));

        $response->assertRedirect(route('products.index'));
        $this->assertSoftDeleted('products', ['id' => $product->id]);
        $this->assertNull(Product::find($product->id));
        $this->assertNotNull(Product::withTrashed()->find($product->id));
    }

    public function test_ajax_autosearch_returns_matching_products(): void
    {
        Product::create([
            'name' => 'Luminous Inverter Pro',
            'company_name' => 'Luminous',
            'sku' => 'LUM-PRO-1',
            'category' => 'Inverters',
            'cost_price' => 20000,
            'selling_price' => 25000,
            'stock_quantity' => 10,
        ]);

        // Less than 2 chars returns empty array
        $shortResponse = $this->actingAs($this->admin)->get(route('products.search', ['q' => 'L']));
        $shortResponse->assertStatus(200);
        $shortResponse->assertJsonCount(0);

        // 2 or more characters performs AJAX search
        $response = $this->actingAs($this->admin)->get(route('products.search', ['q' => 'Lum']));
        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Luminous Inverter Pro']);
    }

    public function test_reports_page_renders_pending_bills_out_of_stock_and_low_stock(): void
    {
        Product::create([
            'name' => 'Out Stock Item',
            'sku' => 'OUT-1',
            'category' => 'General',
            'selling_price' => 500,
            'stock_quantity' => 0,
            'min_alert_stock' => 5,
        ]);

        Product::create([
            'name' => 'Low Stock Item',
            'sku' => 'LOW-1',
            'category' => 'General',
            'selling_price' => 700,
            'stock_quantity' => 2,
            'min_alert_stock' => 5,
        ]);

        $response = $this->actingAs($this->admin)->get(route('reports.index'));

        $response->assertStatus(200);
        $response->assertSee('Business Intelligence & Reports');
        $response->assertSee('Pending Customer Bills');
        $response->assertSee('Out of Stock Products');
        $response->assertSee('Low Stock Products');
        $response->assertSee('Out Stock Item');
        $response->assertSee('Low Stock Item');
    }
}
