<x-app-layout>
    <div x-data="{ activeTab: 'pending' }" class="space-y-6">
        
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-gray-200/80 shadow-2xs">
            <div>
                <h1 class="text-xl font-bold text-gray-800 tracking-tight">
                    {{ __('Business Intelligence & Reports') }}
                </h1>
                <p class="text-xs text-gray-500">
                    {{ __('Real-time tracking of pending customer bills, out of stock products, and low stock inventory alerts.') }}
                </p>
            </div>
            
            <div class="flex items-center gap-2">
                <button type="button"
                        onclick="window.print()"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold transition active:scale-[0.98]">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    <span>{{ __('Print Report') }}</span>
                </button>
            </div>
        </div>

        <!-- 3 Summary Alert Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <!-- 1. Pending Bills Card -->
            <div @click="activeTab = 'pending'"
                 :class="activeTab === 'pending' ? 'ring-2 ring-rose-500 border-rose-300 shadow-md' : 'hover:border-gray-300'"
                 class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-2xs transition cursor-pointer flex flex-col justify-between group">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-rose-700">Pending Customer Bills</span>
                    <div class="w-9 h-9 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center font-bold text-xs">
                        {{ $totalPendingCount }}
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-black font-mono text-gray-900">
                        ₹{{ number_format($totalPendingAmount, 2) }}
                    </div>
                    <div class="flex items-center justify-between text-xs mt-1 text-gray-500">
                        <span>{{ $totalPendingCount }} bills with unpaid dues</span>
                        <span class="text-rose-600 font-semibold group-hover:underline">View List →</span>
                    </div>
                </div>
            </div>

            <!-- 2. Out of Stock Card -->
            <div @click="activeTab = 'out'"
                 :class="activeTab === 'out' ? 'ring-2 ring-rose-600 border-rose-300 shadow-md' : 'hover:border-gray-300'"
                 class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-2xs transition cursor-pointer flex flex-col justify-between group">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-rose-800">Out of Stock Items</span>
                    <div class="w-9 h-9 rounded-xl bg-rose-100 border border-rose-300 text-rose-700 flex items-center justify-center font-bold text-xs">
                        {{ $outOfStockCount }}
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-black font-mono text-rose-600">
                        {{ $outOfStockCount }} <span class="text-sm font-sans font-medium text-gray-500">Products</span>
                    </div>
                    <div class="flex items-center justify-between text-xs mt-1 text-gray-500">
                        <span>Stock quantity reached 0</span>
                        <span class="text-rose-600 font-semibold group-hover:underline">View Items →</span>
                    </div>
                </div>
            </div>

            <!-- 3. Low Stock Alert Card -->
            <div @click="activeTab = 'low'"
                 :class="activeTab === 'low' ? 'ring-2 ring-amber-500 border-amber-300 shadow-md' : 'hover:border-gray-300'"
                 class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-2xs transition cursor-pointer flex flex-col justify-between group">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-700">Low Stock Alert</span>
                    <div class="w-9 h-9 rounded-xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center font-bold text-xs">
                        {{ $lowStockCount }}
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-black font-mono text-amber-600">
                        {{ $lowStockCount }} <span class="text-sm font-sans font-medium text-gray-500">Products</span>
                    </div>
                    <div class="flex items-center justify-between text-xs mt-1 text-gray-500">
                        <span>Below minimum replenishment alert</span>
                        <span class="text-amber-600 font-semibold group-hover:underline">View Items →</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex flex-wrap items-center gap-2 border-b border-gray-200 pb-3">
            <button type="button"
                    @click="activeTab = 'pending'"
                    :class="activeTab === 'pending' ? 'bg-[#324b3e] text-white shadow-xs' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200'"
                    class="px-4 py-2 rounded-full text-xs font-bold transition flex items-center gap-2">
                <span>Pending Bills</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] bg-rose-500 text-white font-mono">{{ $totalPendingCount }}</span>
            </button>

            <button type="button"
                    @click="activeTab = 'out'"
                    :class="activeTab === 'out' ? 'bg-[#324b3e] text-white shadow-xs' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200'"
                    class="px-4 py-2 rounded-full text-xs font-bold transition flex items-center gap-2">
                <span>Out of Stock</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] bg-rose-600 text-white font-mono">{{ $outOfStockCount }}</span>
            </button>

            <button type="button"
                    @click="activeTab = 'low'"
                    :class="activeTab === 'low' ? 'bg-[#324b3e] text-white shadow-xs' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200'"
                    class="px-4 py-2 rounded-full text-xs font-bold transition flex items-center gap-2">
                <span>Low Stock</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] bg-amber-500 text-white font-mono">{{ $lowStockCount }}</span>
            </button>
        </div>

        <!-- Tab 1: Pending Bills Table -->
        <div x-show="activeTab === 'pending'" x-cloak class="bg-white rounded-2xl border border-gray-200/80 shadow-2xs overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="font-bold text-gray-900 text-sm">All Pending & Partial Bills (Unpaid Khata)</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Bills with outstanding payment balances requiring recovery.</p>
                </div>
                <div class="sm:text-right">
                    <span class="text-xs text-gray-400 block">Total Outstanding Due</span>
                    <span class="text-lg font-black font-mono text-rose-600">₹{{ number_format($totalPendingAmount, 2) }}</span>
                </div>
            </div>

            <div class="p-2 sm:p-4 overflow-x-auto">
                <table id="pending-bills-table" class="display responsive nowrap w-full text-left text-xs">
                    <thead>
                        <tr>
                            <th>Bill No</th>
                            <th>Date</th>
                            <th>Customer Name</th>
                            <th>Phone</th>
                            <th class="text-right">Total Bill (₹)</th>
                            <th class="text-right">Paid (₹)</th>
                            <th class="text-right">Pending Due (₹)</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendingBills as $bill)
                            <tr>
                                <td class="font-mono font-bold text-gray-900">
                                    {{ $bill->bill_number }}
                                </td>
                                <td class="text-gray-500">
                                    {{ $bill->created_at->format('d M Y') }}
                                </td>
                                <td class="font-semibold text-gray-800">
                                    {{ $bill->customer_name }}
                                </td>
                                <td class="font-mono text-gray-500">
                                    {{ $bill->customer_phone ?: '—' }}
                                </td>
                                <td class="text-right font-mono text-gray-700">
                                    ₹{{ number_format($bill->grand_total, 2) }}
                                </td>
                                <td class="text-right font-mono font-medium text-emerald-600">
                                    ₹{{ number_format($bill->paid_amount, 2) }}
                                </td>
                                <td class="text-right font-mono font-bold text-rose-600">
                                    ₹{{ number_format($bill->due_amount, 2) }}
                                </td>
                                <td class="text-center">
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold {{ $bill->payment_status === 'partial' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ ucfirst($bill->payment_status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a href="{{ route('bills.slip', $bill) }}"
                                           class="px-2.5 py-1 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-[11px] transition">
                                            Slip
                                        </a>
                                        <a href="{{ route('bills.index') }}"
                                           class="px-2.5 py-1 rounded-lg bg-[#324b3e]/10 hover:bg-[#324b3e]/20 text-[#324b3e] font-bold text-[11px] transition">
                                            Collect
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab 2: Out of Stock Table -->
        <div x-show="activeTab === 'out'" x-cloak class="bg-white rounded-2xl border border-gray-200/80 shadow-2xs overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="font-bold text-gray-900 text-sm text-rose-700">Out of Stock Products (Critical)</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Inventory items that currently have zero stock and need immediate replenishment.</p>
                </div>
                <span class="inline-flex self-start sm:self-auto px-3 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold font-mono">
                    {{ $outOfStockCount }} Items Depleted
                </span>
            </div>

            <div class="p-2 sm:p-4 overflow-x-auto">
                <table id="out-of-stock-table" class="display responsive nowrap w-full text-left text-xs">
                    <thead>
                        <tr>
                            <th>SKU / Code</th>
                            <th>Product Name</th>
                            <th>Company Name</th>
                            <th>Category</th>
                            <th class="text-right">Cost (₹)</th>
                            <th class="text-right">Selling Price (₹)</th>
                            <th class="text-center">Stock Level</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($outOfStockProducts as $prod)
                            <tr>
                                <td class="font-mono text-gray-600 font-bold">
                                    {{ $prod->sku }}
                                </td>
                                <td class="font-semibold text-gray-900">
                                    {{ $prod->name }}
                                </td>
                                <td class="text-gray-600 font-medium">
                                    {{ $prod->company_name ?: '—' }}
                                </td>
                                <td>
                                    <span class="px-2 py-0.5 rounded-lg bg-gray-100 text-gray-600 text-[11px] font-medium">
                                        {{ $prod->category }}
                                    </span>
                                </td>
                                <td class="text-right font-mono text-gray-500">
                                    ₹{{ number_format($prod->cost_price, 2) }}
                                </td>
                                <td class="text-right font-mono font-bold text-gray-900">
                                    ₹{{ number_format($prod->selling_price, 2) }}
                                </td>
                                <td class="text-center font-mono font-black text-rose-600">
                                    0 {{ $prod->unit }}
                                </td>
                                <td class="text-center">
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                        Out of Stock
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('products.index') }}"
                                       class="px-3 py-1 rounded-lg bg-[#324b3e] text-white font-bold text-[11px] hover:bg-[#23382f] transition shadow-2xs">
                                        + Restock
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab 3: Low Stock Table -->
        <div x-show="activeTab === 'low'" x-cloak class="bg-white rounded-2xl border border-gray-200/80 shadow-2xs overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="font-bold text-gray-900 text-sm text-amber-700">Low Stock Products (Warning Alert)</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Products that have fallen below their configured minimum alert threshold.</p>
                </div>
                <span class="inline-flex self-start sm:self-auto px-3 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold font-mono">
                    {{ $lowStockCount }} Low Stock Items
                </span>
            </div>

            <div class="p-2 sm:p-4 overflow-x-auto">
                <table id="low-stock-table" class="display responsive nowrap w-full text-left text-xs">
                    <thead>
                        <tr>
                            <th>SKU / Code</th>
                            <th>Product Name</th>
                            <th>Company Name</th>
                            <th>Category</th>
                            <th class="text-right">Selling Price (₹)</th>
                            <th class="text-center">Current Stock</th>
                            <th class="text-center">Min Alert Level</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lowStockProducts as $prod)
                            <tr>
                                <td class="font-mono text-gray-600 font-bold">
                                    {{ $prod->sku }}
                                </td>
                                <td class="font-semibold text-gray-900">
                                    {{ $prod->name }}
                                </td>
                                <td class="text-gray-600 font-medium">
                                    {{ $prod->company_name ?: '—' }}
                                </td>
                                <td>
                                    <span class="px-2 py-0.5 rounded-lg bg-gray-100 text-gray-600 text-[11px] font-medium">
                                        {{ $prod->category }}
                                    </span>
                                </td>
                                <td class="text-right font-mono font-bold text-gray-900">
                                    ₹{{ number_format($prod->selling_price, 2) }}
                                </td>
                                <td class="text-center font-mono font-bold text-amber-600">
                                    {{ $prod->stock_quantity }} {{ $prod->unit }}
                                </td>
                                <td class="text-center font-mono text-gray-500">
                                    {{ $prod->min_alert_stock }} {{ $prod->unit }}
                                </td>
                                <td class="text-center">
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-900 border border-amber-200">
                                        Low Stock
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('products.index') }}"
                                       class="px-3 py-1 rounded-lg bg-amber-600 text-white font-bold text-[11px] hover:bg-amber-700 transition shadow-2xs">
                                        Restock
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- DataTables Initialization -->
    <script>
        $(document).ready(function() {
            function createDataTable(selector, emptyMsg) {
                if ($(selector).length) {
                    $(selector).DataTable({
                        responsive: true,
                        pageLength: 10,
                        lengthMenu: [5, 10, 25, 50],
                        language: {
                            search: "_INPUT_",
                            searchPlaceholder: "Search report data...",
                            lengthMenu: "Show _MENU_ records",
                            info: "Showing _START_ to _END_ of _TOTAL_ entries",
                            emptyTable: emptyMsg,
                            zeroRecords: "No matching records found"
                        }
                    });
                }
            }

            createDataTable('#pending-bills-table', 'No pending bills found! All customer dues are currently cleared.');
            createDataTable('#out-of-stock-table', 'Awesome! No products are currently out of stock.');
            createDataTable('#low-stock-table', 'No low stock warnings! All inventory levels are above alert limits.');
        });
    </script>
</x-app-layout>
