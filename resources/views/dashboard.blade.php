<x-app-layout>
    <div class="space-y-6">
        
        <!-- Top Action Bar: Quick Links -->
        <div class="flex flex-wrap items-center justify-between gap-3 bg-white/70 p-4 rounded-2xl border border-gray-200/80 shadow-2xs">
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Quick Actions:</span>
                <span class="text-xs text-gray-500">POS & Inventory Operations</span>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('bills.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#324b3e] hover:bg-[#293b32] text-white text-xs font-semibold shadow-md transition active:scale-[0.98]">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>{{ __('Make New Bill (POS)') }}</span>
                </a>
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white hover:bg-gray-50 text-gray-700 text-xs font-semibold border border-gray-300 shadow-2xs transition">
                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span>{{ __('Manage Stock') }}</span>
                </a>
            </div>
        </div>

        <!-- Over View Cards Section (Matching Reference Image) -->
        <div>
            <div class="mb-3 text-xs font-bold text-gray-700 uppercase tracking-wider">
                Over View
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- Card 1: Total Products -->
                <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-2xs flex items-center gap-3.5 hover:shadow-xs transition">
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center shrink-0 text-emerald-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xl font-bold text-gray-800 tracking-tight">
                            {{ number_format($totalProducts) }}
                        </div>
                        <div class="text-xs font-medium text-gray-500">
                            Total Products
                        </div>
                    </div>
                </div>

                <!-- Card 2: Orders / Bills -->
                <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-2xs flex items-center gap-3.5 hover:shadow-xs transition">
                    <div class="w-11 h-11 rounded-xl bg-teal-50 border border-teal-100 flex items-center justify-center shrink-0 text-teal-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xl font-bold text-gray-800 tracking-tight">
                            {{ number_format($totalOrders) }}
                        </div>
                        <div class="text-xs font-medium text-gray-500">
                            Orders / Bills
                        </div>
                    </div>
                </div>

                <!-- Card 3: Total Stock -->
                <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-2xs flex items-center gap-3.5 hover:shadow-xs transition">
                    <div class="w-11 h-11 rounded-xl bg-cyan-50 border border-cyan-100 flex items-center justify-center shrink-0 text-cyan-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xl font-bold text-gray-800 tracking-tight">
                            {{ number_format($totalStock) }}
                        </div>
                        <div class="text-xs font-medium text-gray-500">
                            Total Stock (Units)
                        </div>
                    </div>
                </div>

                <!-- Card 4: Out of Stock (Peach/Coral Alert Card in Mockup) -->
                <div class="bg-[#fcf1ec] rounded-2xl p-4 border border-[#f3d4c5] shadow-2xs flex items-center justify-between relative hover:shadow-xs transition">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-[#fbe5dc] border border-[#f4c8b6] flex items-center justify-center shrink-0 text-[#c25e36]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xl font-bold text-[#a64823] tracking-tight">
                                {{ number_format($outOfStock) }}
                            </div>
                            <div class="text-xs font-semibold text-[#b85a36]">
                                Out of Stock
                            </div>
                        </div>
                    </div>
                    <span class="w-5 h-5 rounded-full bg-white/70 text-[#c25e36] text-[10px] font-bold flex items-center justify-center" title="{{ $lowStock }} items are at low stock level">
                        i
                    </span>
                </div>

            </div>
        </div>

        <!-- Middle Section: 3 Columns (No of users, Inventory Values, Top 10 by sales) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5" id="due-section">
            
            <!-- Column 1: No of Users / Khata Due Summary (4 cols) -->
            <div class="lg:col-span-3 bg-white rounded-2xl p-5 border border-gray-200/80 shadow-2xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between text-xs text-gray-600 font-semibold mb-4">
                        <span>No of users</span>
                        <span class="text-gray-400">⋮</span>
                    </div>

                    <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-700 mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>

                    <div class="text-2xl font-bold text-gray-900 tracking-tight">
                        {{ $totalCustomers }}
                    </div>
                    <div class="text-xs text-gray-500 font-medium">
                        Total Customers
                    </div>
                </div>

                <!-- Live Khata / Due Tracker Box -->
                <div class="mt-5 pt-4 border-t border-gray-100">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-gray-500">Customer Dues:</span>
                        <span class="font-bold text-rose-600">₹{{ number_format($totalDue, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs mt-1.5">
                        <span class="text-gray-500">Total Collected:</span>
                        <span class="font-semibold text-emerald-700">₹{{ number_format($totalRevenue, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Column 2: Inventory Values Donut Chart (4 cols) -->
            <div class="lg:col-span-5 bg-white rounded-2xl p-5 border border-gray-200/80 shadow-2xs flex flex-col justify-between">
                <div class="text-xs font-semibold text-gray-700 mb-2">
                    Inventory Values
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-around gap-4 my-auto py-2">
                    <!-- Donut SVG -->
                    <div class="relative w-32 h-32 shrink-0">
                        <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                            <!-- Background Circle -->
                            <path class="text-[#324b3e]" stroke-width="5" stroke="currentColor" fill="none"
                                  d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <!-- Foreground Segment (Sold %) -->
                            <path class="text-[#88afc2]" stroke-dasharray="{{ $soldPercentage }}, 100" stroke-width="5" stroke="currentColor" fill="none"
                                  d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                            <span class="text-xs font-bold text-gray-700">{{ $soldPercentage }}%</span>
                            <span class="text-[9px] text-gray-400">Sold</span>
                        </div>
                    </div>

                    <!-- Legend -->
                    <div class="space-y-3 text-xs">
                        <div class="flex items-center gap-2.5">
                            <span class="w-3.5 h-3.5 rounded-sm bg-[#88afc2] inline-block shrink-0"></span>
                            <div>
                                <span class="text-gray-500 block text-[11px]">Sold units</span>
                                <span class="font-bold text-gray-800">{{ number_format($soldUnits) }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <span class="w-3.5 h-3.5 rounded-sm bg-[#324b3e] inline-block shrink-0"></span>
                            <div>
                                <span class="text-gray-500 block text-[11px]">Total units</span>
                                <span class="font-bold text-gray-800">{{ number_format($totalUnits) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Column 3: Top Stores / Products by Sales (4 cols) -->
            <div class="lg:col-span-4 bg-white rounded-2xl p-5 border border-gray-200/80 shadow-2xs">
                <div class="text-xs font-semibold text-gray-700 mb-3">
                    Top Products by Sales
                </div>

                <div class="space-y-2.5">
                    @forelse ($topProducts as $item)
                        <div class="flex items-center justify-between text-xs gap-2">
                            <span class="truncate max-w-[130px] font-medium text-gray-700" title="{{ $item->product_name }}">
                                {{ $item->product_name }}
                            </span>
                            <div class="flex items-center gap-2 flex-1 justify-end">
                                <div class="w-24 bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                    <div class="bg-[#3d594b] h-full rounded-full" style="width: {{ min(100, max(15, ($item->total_sales / 100000) * 100)) }}%"></div>
                                </div>
                                <span class="text-[11px] font-semibold text-gray-800 shrink-0 w-12 text-right">
                                    ₹{{ number_format($item->total_sales / 1000, 0) }}k
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-gray-400 text-xs">
                            No billing history yet.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Bottom Section: Expense vs Profit Smooth Chart (Matching Reference Image) -->
        <div class="bg-white rounded-2xl p-5 border border-gray-200/80 shadow-2xs">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xs font-bold text-gray-700 tracking-wider">
                    Expense vs Profit
                </h3>
                <span class="text-[11px] font-medium text-gray-500 bg-gray-100 px-2.5 py-0.5 rounded-full">
                    Last 6 months
                </span>
            </div>

            <!-- Chart Area Container -->
            <div class="relative w-full h-44 pt-4">
                <!-- SVG Area Chart -->
                <svg class="w-full h-full overflow-visible" viewBox="0 0 600 120" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="profitGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" stop-color="#3d594b" stop-opacity="0.25"/>
                            <stop offset="100%" stop-color="#3d594b" stop-opacity="0.0"/>
                        </linearGradient>
                        <linearGradient id="expenseGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" stop-color="#e07a5f" stop-opacity="0.18"/>
                            <stop offset="100%" stop-color="#e07a5f" stop-opacity="0.0"/>
                        </linearGradient>
                    </defs>

                    <!-- Horizontal Grid Lines -->
                    <line x1="0" y1="20" x2="600" y2="20" stroke="#f1f5f9" stroke-width="1" stroke-dasharray="4"/>
                    <line x1="0" y1="55" x2="600" y2="55" stroke="#f1f5f9" stroke-width="1" stroke-dasharray="4"/>
                    <line x1="0" y1="90" x2="600" y2="90" stroke="#f1f5f9" stroke-width="1" stroke-dasharray="4"/>

                    <!-- Expense Wave Fill & Line -->
                    <path d="M 0,75 Q 80,95 160,82 T 320,60 T 450,45 T 600,68 L 600,120 L 0,120 Z" fill="url(#expenseGrad)"/>
                    <path d="M 0,75 Q 80,95 160,82 T 320,60 T 450,45 T 600,68" fill="none" stroke="#e07a5f" stroke-width="2"/>

                    <!-- Profit Wave Fill & Line -->
                    <path d="M 0,90 Q 90,65 180,78 T 350,48 T 490,22 T 600,32 L 600,120 L 0,120 Z" fill="url(#profitGrad)"/>
                    <path d="M 0,90 Q 90,65 180,78 T 350,48 T 490,22 T 600,32" fill="none" stroke="#324b3e" stroke-width="2.5"/>

                    <!-- Markers -->
                    <circle cx="320" cy="60" r="4" fill="#e07a5f" stroke="#ffffff" stroke-width="2"/>
                    <circle cx="490" cy="22" r="4" fill="#324b3e" stroke="#ffffff" stroke-width="2"/>
                </svg>

                <!-- Floating Labels like in mockup -->
                <div class="absolute left-[50%] top-6 -translate-x-1/2 bg-[#c25e36] text-white text-[9px] font-bold px-2 py-0.5 rounded shadow-xs">
                    Highest Expense
                </div>
                <div class="absolute left-[78%] top-0 -translate-x-1/2 bg-[#293b32] text-white text-[9px] font-bold px-2 py-0.5 rounded shadow-xs">
                    Highest Profit
                </div>
            </div>

            <!-- X-Axis Labels -->
            <div class="flex justify-between text-[11px] text-gray-400 font-medium px-2 pt-2 border-t border-gray-100 mt-2">
                <span>Dec</span>
                <span>Jan</span>
                <span>Feb</span>
                <span>Mar</span>
                <span>April</span>
                <span>May</span>
                <span>Jun</span>
            </div>
        </div>

    </div>
</x-app-layout>
