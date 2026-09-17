<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\BillPayment;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Single User
        User::updateOrCreate(
            ['email' => 'tejas@gmail.com'],
            [
                'name' => 'Tejas',
                'password' => Hash::make('tejas123'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Sample Products
        $products = [
            [
                'name' => 'Commercial Inverter 10KVA',
                'company_name' => 'Luminous',
                'sku' => 'INV-10K',
                'category' => 'Inverters',
                'cost_price' => 78000,
                'selling_price' => 100000,
                'stock_quantity' => 14, // 15 originally, 1 sold in demo bill
                'min_alert_stock' => 3,
                'unit' => 'pcs',
            ],
            [
                'name' => 'Solar Panel 540W Mono PERC',
                'company_name' => 'Tata Power Solar',
                'sku' => 'SP-540W',
                'category' => 'Solar Panels',
                'cost_price' => 14000,
                'selling_price' => 18500,
                'stock_quantity' => 45,
                'min_alert_stock' => 10,
                'unit' => 'pcs',
            ],
            [
                'name' => 'Lithium Battery 48V 100Ah',
                'company_name' => 'Exide',
                'sku' => 'BAT-48100',
                'category' => 'Batteries',
                'cost_price' => 48000,
                'selling_price' => 65000,
                'stock_quantity' => 8,
                'min_alert_stock' => 5,
                'unit' => 'pcs',
            ],
            [
                'name' => 'Hybrid Solar Inverter 5KW',
                'company_name' => 'Microtek',
                'sku' => 'HSI-5KW',
                'category' => 'Inverters',
                'cost_price' => 34000,
                'selling_price' => 45000,
                'stock_quantity' => 20,
                'min_alert_stock' => 5,
                'unit' => 'pcs',
            ],
            [
                'name' => 'Smart Digital Energy Meter',
                'company_name' => 'Schneider Electric',
                'sku' => 'EM-3PH',
                'category' => 'Accessories',
                'cost_price' => 2800,
                'selling_price' => 4200,
                'stock_quantity' => 2, // Low stock
                'min_alert_stock' => 5,
                'unit' => 'pcs',
            ],
            [
                'name' => 'Heavy Duty Distribution Box',
                'company_name' => 'Havells',
                'sku' => 'DB-16W',
                'category' => 'Accessories',
                'cost_price' => 1600,
                'selling_price' => 2400,
                'stock_quantity' => 0, // Out of stock
                'min_alert_stock' => 5,
                'unit' => 'pcs',
            ],
            [
                'name' => 'MC4 Solar Connectors (Pair)',
                'company_name' => 'Staubli',
                'sku' => 'MC4-PR',
                'category' => 'Accessories',
                'cost_price' => 60,
                'selling_price' => 120,
                'stock_quantity' => 380,
                'min_alert_stock' => 50,
                'unit' => 'pair',
            ],
            [
                'name' => 'Copper DC Cable 4 sq mm (100m)',
                'company_name' => 'Polycab',
                'sku' => 'CAB-4SQ',
                'category' => 'Cables',
                'cost_price' => 2900,
                'selling_price' => 3800,
                'stock_quantity' => 28,
                'min_alert_stock' => 8,
                'unit' => 'roll',
            ],
        ];

        foreach ($products as $p) {
            Product::updateOrCreate(['sku' => $p['sku']], $p);
        }

        // 3. Customers
        $c1 = Customer::updateOrCreate(
            ['phone' => '9876543210'],
            [
                'name' => 'Rajesh Sharma',
                'email' => 'rajesh@example.com',
                'address' => 'Plot 42, Green Avenue, Industrial Area',
                'total_due' => 50000,
            ]
        );

        $c2 = Customer::updateOrCreate(
            ['phone' => '9822334455'],
            [
                'name' => 'Vikram Patel',
                'email' => 'vikram@example.com',
                'address' => '12, Sunrise Complex, Main Road',
                'total_due' => 0,
            ]
        );

        // 4. Sample Bills
        // Demo scenario: 1 lakh item taken, 50 thousand paid, 50 thousand due
        $invProduct = Product::where('sku', 'INV-10K')->first();
        if ($invProduct && ! Bill::where('bill_number', 'INV-DEMO-0001')->exists()) {
            $bill1 = Bill::create([
                'bill_number' => 'INV-DEMO-0001',
                'customer_id' => $c1->id,
                'customer_name' => $c1->name,
                'customer_phone' => $c1->phone,
                'subtotal' => 100000,
                'discount' => 0,
                'grand_total' => 100000,
                'paid_amount' => 50000,
                'due_amount' => 50000,
                'payment_status' => 'partial',
                'payment_method' => 'cash',
                'notes' => 'Customer took 1 Lakh Commercial Inverter, paid 50,000 cash, balance due 50,000.',
                'created_at' => now()->subDays(1),
            ]);

            BillItem::create([
                'bill_id' => $bill1->id,
                'product_id' => $invProduct->id,
                'product_name' => $invProduct->name,
                'unit_price' => 100000,
                'quantity' => 1,
                'total_price' => 100000,
            ]);

            BillPayment::create([
                'bill_id' => $bill1->id,
                'amount_paid' => 50000,
                'payment_method' => 'cash',
                'note' => 'Initial payment at bill creation',
                'created_at' => now()->subDays(1),
            ]);
        }

        // Fully paid bill
        $solarProduct = Product::where('sku', 'SP-540W')->first();
        if ($solarProduct && ! Bill::where('bill_number', 'INV-DEMO-0002')->exists()) {
            $bill2 = Bill::create([
                'bill_number' => 'INV-DEMO-0002',
                'customer_id' => $c2->id,
                'customer_name' => $c2->name,
                'customer_phone' => $c2->phone,
                'subtotal' => 37000,
                'discount' => 0,
                'grand_total' => 37000,
                'paid_amount' => 37000,
                'due_amount' => 0,
                'payment_status' => 'paid',
                'payment_method' => 'upi',
                'notes' => '2 panels paid in full via UPI',
                'created_at' => now()->subHours(5),
            ]);

            BillItem::create([
                'bill_id' => $bill2->id,
                'product_id' => $solarProduct->id,
                'product_name' => $solarProduct->name,
                'unit_price' => 18500,
                'quantity' => 2,
                'total_price' => 37000,
            ]);

            BillPayment::create([
                'bill_id' => $bill2->id,
                'amount_paid' => 37000,
                'payment_method' => 'upi',
                'note' => 'Full payment via UPI',
                'created_at' => now()->subHours(5),
            ]);
        }
    }
}
