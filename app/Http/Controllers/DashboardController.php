<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $totalProducts = Product::count();
        $totalOrders = Bill::count();
        $totalStock = (int) Product::sum('stock_quantity');
        $outOfStock = Product::where('stock_quantity', '<=', 0)->count();
        $lowStock = Product::where('stock_quantity', '>', 0)
            ->whereColumn('stock_quantity', '<=', 'min_alert_stock')
            ->count();

        $totalCustomers = Customer::count();
        $totalDue = (float) Bill::sum('due_amount');
        $totalRevenue = (float) Bill::sum('paid_amount');

        // Sold vs Stock units
        $soldUnits = (int) BillItem::sum('quantity');
        $totalUnits = $soldUnits + $totalStock;
        $soldPercentage = $totalUnits > 0 ? round(($soldUnits / $totalUnits) * 100) : 32;

        // Top products by sales
        $topProducts = BillItem::selectRaw('product_name, SUM(quantity) as total_qty, SUM(total_price) as total_sales')
            ->groupBy('product_name')
            ->orderByDesc('total_sales')
            ->limit(5)
            ->get();

        // Recent bills
        $recentBills = Bill::latest()->limit(5)->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalStock',
            'outOfStock',
            'lowStock',
            'totalCustomers',
            'totalDue',
            'totalRevenue',
            'soldUnits',
            'totalUnits',
            'soldPercentage',
            'topProducts',
            'recentBills'
        ));
    }
}
