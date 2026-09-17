<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BillController extends Controller
{
    public function index(Request $request): View
    {
        $query = Bill::with(['items', 'customer'])->latest();

        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->where(function ($q) use ($s) {
                $q->where('bill_number', 'like', "%{$s}%")
                    ->orWhere('customer_name', 'like', "%{$s}%")
                    ->orWhere('customer_phone', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('payment_status', $request->input('status'));
        }

        $bills = $query->get();

        $totalBilled = (float) Bill::sum('grand_total');
        $totalCollected = (float) Bill::sum('paid_amount');
        $totalDue = (float) Bill::sum('due_amount');

        return view('bills.index', compact('bills', 'totalBilled', 'totalCollected', 'totalDue'));
    }

    public function create(): View
    {
        $products = Product::where('stock_quantity', '>', 0)
            ->orderBy('name')
            ->get();

        $allProducts = Product::orderBy('name')->get();
        $customers = Customer::orderBy('name')->get();
        $nextBillNumber = Bill::generateBillNumber();

        return view('bills.create', compact('products', 'allProducts', 'customers', 'nextBillNumber'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:50'],
            'customer_address' => ['nullable', 'string', 'max:255'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['nullable', 'exists:products,id'],
            'items.*.product_name' => ['nullable', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ]);

        $bill = DB::transaction(function () use ($validated) {
            // 1. Resolve customer
            $customerId = $validated['customer_id'] ?? null;
            if (! $customerId && ! empty($validated['customer_phone'])) {
                $customer = Customer::firstOrCreate(
                    ['phone' => $validated['customer_phone']],
                    [
                        'name' => $validated['customer_name'],
                        'address' => $validated['customer_address'] ?? null,
                    ]
                );
                $customerId = $customer->id;
            }

            // 2. Compute items and subtotal
            $subtotal = 0;
            $itemsData = [];

            foreach ($validated['items'] as $itemInput) {
                $qty = (int) $itemInput['quantity'];
                $price = (float) $itemInput['unit_price'];
                $lineTotal = $qty * $price;
                $subtotal += $lineTotal;

                $productId = ! empty($itemInput['product_id']) ? (int) $itemInput['product_id'] : null;
                $productName = trim($itemInput['product_name'] ?? '');

                if ($productId) {
                    $product = Product::find($productId);
                    if ($product) {
                        $productName = $product->name;
                        // Decrement stock for inventory products
                        $product->decrement('stock_quantity', $qty);
                    }
                }

                if (empty($productName)) {
                    $productName = 'Item / General Product';
                }

                $itemsData[] = [
                    'product_id' => $productId,
                    'product_name' => $productName,
                    'unit_price' => $price,
                    'quantity' => $qty,
                    'total_price' => $lineTotal,
                ];
            }

            $discount = (float) ($validated['discount'] ?? 0);
            $grandTotal = max(0, $subtotal - $discount);
            $paidAmount = min($grandTotal, (float) $validated['paid_amount']);
            $dueAmount = max(0, $grandTotal - $paidAmount);

            // Determine status
            if ($dueAmount <= 0) {
                $paymentStatus = 'paid';
            } elseif ($paidAmount > 0) {
                $paymentStatus = 'partial';
            } else {
                $paymentStatus = 'unpaid';
            }

            // 3. Create Bill
            $bill = Bill::create([
                'bill_number' => Bill::generateBillNumber(),
                'customer_id' => $customerId,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'] ?? null,
                'customer_address' => $validated['customer_address'] ?? null,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'grand_total' => $grandTotal,
                'paid_amount' => $paidAmount,
                'due_amount' => $dueAmount,
                'payment_status' => $paymentStatus,
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Save items
            foreach ($itemsData as $item) {
                $bill->items()->create($item);
            }

            // Save initial payment if paid > 0
            if ($paidAmount > 0) {
                $bill->payments()->create([
                    'amount_paid' => $paidAmount,
                    'payment_method' => $validated['payment_method'],
                    'note' => 'Initial payment at bill generation',
                ]);
            }

            // Update customer total due
            if ($customerId && $dueAmount > 0) {
                Customer::where('id', $customerId)->increment('total_due', $dueAmount);
            }

            return $bill;
        });

        return redirect()->route('bills.slip', $bill)
            ->with('success', "Bill {$bill->bill_number} generated successfully!");
    }

    public function slip(Bill $bill): View
    {
        $bill->load(['items', 'customer', 'payments']);

        // If bill is still unpaid or partially paid with due balance, display small due slip.
        // If bill is fully paid, display the official MOMAI PLYWOOD full bill.
        if ($bill->payment_status !== 'paid' && $bill->due_amount > 0) {
            $latestPayment = $bill->payments()->latest()->first();

            return view('bills.due_slip', compact('bill', 'latestPayment'));
        }

        return view('bills.slip', compact('bill'));
    }

    public function showBill(Bill $bill): View
    {
        $bill->load(['items', 'customer', 'payments']);

        return view('bills.slip', compact('bill'));
    }

    public function addPayment(Request $request, Bill $bill): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:'.$bill->due_amount],
            'payment_method' => ['required', 'string'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $amount = (float) $validated['amount'];

        $payment = DB::transaction(function () use ($bill, $amount, $validated) {
            $bill->increment('paid_amount', $amount);
            $bill->decrement('due_amount', $amount);

            $payment = $bill->payments()->create([
                'amount_paid' => $amount,
                'payment_method' => $validated['payment_method'],
                'note' => $validated['note'] ?? 'Due payment collected',
            ]);

            if ($bill->due_amount <= 0) {
                $bill->update(['payment_status' => 'paid']);
            } else {
                $bill->update(['payment_status' => 'partial']);
            }

            if ($bill->customer_id) {
                Customer::where('id', $bill->customer_id)->decrement('total_due', min($amount, $bill->customer->total_due ?? $amount));
            }

            return $payment;
        });

        // If after this payment the bill is fully paid, open full bill; otherwise open small due slip
        if ($bill->due_amount <= 0) {
            return redirect()->route('bills.slip', $bill)
                ->with('success', "Bill {$bill->bill_number} is now FULLY PAID! Full bill generated.");
        }

        return redirect()->route('payments.slip', $payment)
            ->with('success', 'Due payment of ₹'.number_format($amount, 2).' recorded. Small payment slip generated!');
    }

    public function paymentSlip(BillPayment $payment): View
    {
        $payment->load('bill.customer');
        $bill = $payment->bill;
        $latestPayment = $payment;

        return view('bills.due_slip', compact('bill', 'latestPayment'));
    }
}
