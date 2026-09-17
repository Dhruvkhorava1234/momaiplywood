<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        // 1. Pending Bills (Bills where due_amount > 0 or payment_status in ['partial', 'unpaid'])
        $pendingBills = Bill::query()
            ->where('due_amount', '>', 0)
            ->orWhereIn('payment_status', ['partial', 'unpaid'])
            ->latest()
            ->get();

        $totalPendingAmount = (float) $pendingBills->sum('due_amount');
        $totalPendingCount = $pendingBills->count();

        // 2. Out of Stock Products (Stock <= 0)
        $outOfStockProducts = Product::query()
            ->where('stock_quantity', '<=', 0)
            ->latest()
            ->get();

        $outOfStockCount = $outOfStockProducts->count();

        // 3. Low Stock Products (Stock > 0 and stock <= min_alert_stock)
        $lowStockProducts = Product::query()
            ->where('stock_quantity', '>', 0)
            ->whereColumn('stock_quantity', '<=', 'min_alert_stock')
            ->latest()
            ->get();

        $lowStockCount = $lowStockProducts->count();

        return view('reports.index', compact(
            'pendingBills',
            'totalPendingAmount',
            'totalPendingCount',
            'outOfStockProducts',
            'outOfStockCount',
            'lowStockProducts',
            'lowStockCount'
        ));
    }
}
