<x-app-layout>
    <div x-data="billingSystem()" class="space-y-6">

        <!-- Header -->
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 sm:p-5 rounded-2xl border border-gray-200/80 shadow-2xs">
            <div>
                <h1 class="text-xl font-bold text-gray-800 tracking-tight">
                    {{ __('Make Bill / Point of Sale (POS)') }}
                </h1>
                <p class="text-xs text-gray-500">
                    {{ __('Issue invoices, record partial payments, and generate printable transaction slips.') }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('bills.index') }}"
                    class="px-3.5 py-1.5 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold transition">
                    {{ __('View Bills History') }}
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('bills.store') }}" @submit="return validateForm()">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- Left Side (8 Cols): Customer Info & Product Selection Table -->
                <div class="lg:col-span-8 space-y-6">

                    <!-- Customer Details Card -->
                    <div class="bg-white rounded-2xl p-5 border border-gray-200/80 shadow-2xs space-y-4">
                        <div
                            class="text-xs font-bold text-gray-700 uppercase tracking-wider flex items-center justify-between">
                            <span>1. Customer Details</span>
                            <span class="text-[11px] font-normal text-gray-400">Bill No: <span
                                    class="font-mono text-gray-700 font-bold">{{ $nextBillNumber }}</span></span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                    Customer Name <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="customer_name" x-model="customer.name" required
                                    placeholder="e.g. dk"
                                    class="w-full px-3.5 py-2 rounded-xl text-xs bg-gray-50 border border-gray-300 focus:bg-white focus:border-[#324b3e] focus:ring-2 focus:ring-[#324b3e]/20 outline-none transition" />
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                    Phone / MO.
                                </label>
                                <input type="text" name="customer_phone" x-model="customer.phone"
                                    placeholder="e.g. 9876543210"
                                    class="w-full px-3.5 py-2 rounded-xl text-xs bg-gray-50 border border-gray-300 focus:bg-white focus:border-[#324b3e] focus:ring-2 focus:ring-[#324b3e]/20 outline-none transition" />
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                    Address (City / Area)
                                </label>
                                <input type="text" name="customer_address" x-model="customer.address"
                                    placeholder="e.g. Porbandar"
                                    class="w-full px-3.5 py-2 rounded-xl text-xs bg-gray-50 border border-gray-300 focus:bg-white focus:border-[#324b3e] focus:ring-2 focus:ring-[#324b3e]/20 outline-none transition" />
                            </div>
                        </div>
                    </div>

                    <!-- Items & Cart Card -->
                    <div class="bg-white rounded-2xl p-5 border border-gray-200/80 shadow-2xs space-y-4">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-gray-700 uppercase tracking-wider block">
                                    2. Search & Add Products
                                </span>
                                <button type="button"
                                    @click="showCustomItemModal = true; $nextTick(() => $refs.customNameInput.focus())"
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-[#324b3e]/10 hover:bg-[#324b3e] text-[#324b3e] hover:text-white text-xs font-semibold transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    <span>Direct Add</span>
                                </button>
                            </div>

                            <!-- Big Prominent Autosearch Bar -->
                            <div class="relative w-full" @click.away="searchOpen = false">
                                <div class="relative">
                                    <input type="text" x-model="searchQuery" @input="onSearchInput()"
                                        @keydown.enter.prevent="onSearchEnter()"
                                        @focus="if (searchResults.length > 0) searchOpen = true"
                                        placeholder="Type product name to search or press Enter to add directly..."
                                        autocomplete="off"
                                        class="w-full pl-11 pr-10 py-3.5 rounded-2xl text-sm font-medium bg-gray-50/80 border-2 border-gray-200 focus:bg-white focus:border-[#324b3e] focus:ring-4 focus:ring-[#324b3e]/10 outline-none transition shadow-2xs" />
                                    <svg class="w-5 h-5 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    <div x-show="searchLoading" class="absolute right-4 top-1/2 -translate-y-1/2">
                                        <svg class="animate-spin h-4 w-4 text-[#324b3e]"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Autosearch Results List -->
                                <div x-show="searchOpen" x-cloak
                                    class="absolute left-0 right-0 top-full mt-2 bg-white rounded-2xl shadow-2xl border border-gray-200/90 py-2 z-50 max-h-80 overflow-y-auto divide-y divide-gray-100">
                                    <template x-for="prod in searchResults" :key="prod.id">
                                        <div @click="selectProduct(prod)"
                                            class="px-4 py-3 hover:bg-[#324b3e]/5 cursor-pointer transition flex items-center justify-between group">
                                            <div>
                                                <span
                                                    class="font-semibold text-sm text-gray-800 group-hover:text-[#324b3e] transition"
                                                    x-text="prod.name"></span>
                                                <span class="text-xs text-gray-400 ml-2 font-mono"
                                                    x-text="'₹' + Number(prod.selling_price).toLocaleString('en-IN')"></span>
                                            </div>
                                            <svg class="w-4 h-4 text-gray-300 group-hover:text-[#324b3e] transition"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                        </div>
                                    </template>

                                    <!-- Quick Option to add product if not found in list -->
                                    <div x-show="searchQuery.trim().length >= 2" @click="openDirectModalWithQuery()"
                                        class="px-4 py-3 bg-emerald-50/50 hover:bg-emerald-100/60 cursor-pointer transition flex items-center justify-between border-t border-emerald-100">
                                        <div class="text-xs text-emerald-900 flex items-center gap-2">
                                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>Product nathi? Direct add karo: "<strong><span
                                                        x-text="searchQuery"></span></strong>"</span>
                                        </div>
                                        <span
                                            class="text-[11px] font-bold text-emerald-700 bg-white px-2 py-0.5 rounded-md border border-emerald-300 shadow-2xs">+
                                            Direct Enter</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Direct Quick Add Bar Inline -->
                        <div
                            class="p-3.5 bg-gray-50/80 rounded-xl border border-gray-200 flex flex-wrap items-end gap-2.5">
                            <div class="flex-1 min-w-[180px]">
                                <label class="block text-[11px] font-semibold text-gray-600 mb-1">
                                    Direct Item Name (નામ)
                                </label>
                                <input type="text" x-model="quickItem.name"
                                    @keydown.enter.prevent="$refs.quickPriceInput.focus()"
                                    placeholder="e.g. Fevicol 500g, Hardware screw, etc."
                                    class="w-full px-3 py-1.5 rounded-lg text-xs bg-white border border-gray-300 focus:border-[#324b3e] focus:ring-1 focus:ring-[#324b3e] outline-none" />
                            </div>

                            <div class="w-24">
                                <label class="block text-[11px] font-semibold text-gray-600 mb-1">
                                    Rate / Price (₹)
                                </label>
                                <input type="number" x-ref="quickPriceInput" x-model.number="quickItem.price"
                                    @keydown.enter.prevent="$refs.quickQtyInput.focus()" min="0" step="any"
                                    placeholder="0.00"
                                    class="w-full px-3 py-1.5 rounded-lg text-xs font-mono text-right bg-white border border-gray-300 focus:border-[#324b3e] focus:ring-1 focus:ring-[#324b3e] outline-none" />
                            </div>

                            <div class="w-20">
                                <label class="block text-[11px] font-semibold text-gray-600 mb-1">
                                    Qty (જથ્થો)
                                </label>
                                <input type="number" x-ref="quickQtyInput" x-model.number="quickItem.quantity"
                                    @keydown.enter.prevent="addQuickItem()" min="1" value="1"
                                    class="w-full px-3 py-1.5 rounded-lg text-xs font-mono text-center bg-white border border-gray-300 focus:border-[#324b3e] focus:ring-1 focus:ring-[#324b3e] outline-none" />
                            </div>

                            <div class="w-24 text-right pb-1 font-mono text-xs font-bold text-gray-800">
                                <div class="text-[10px] text-gray-400 font-sans font-normal">Line Total</div>
                                <span
                                    x-text="'₹' + Number((quickItem.price || 0) * (quickItem.quantity || 1)).toLocaleString('en-IN')"></span>
                            </div>

                            <button type="button" @click="addQuickItem()"
                                class="px-4 py-1.5 rounded-lg bg-[#324b3e] hover:bg-[#23382f] text-white text-xs font-bold shadow-xs transition flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                <span>Add</span>
                            </button>
                        </div>

                        <!-- Items Table -->
                        <div class="overflow-x-auto border border-gray-200 rounded-xl">
                            <table class="w-full text-left text-xs">
                                <thead
                                    class="bg-gray-50 border-b border-gray-200 text-gray-600 font-semibold uppercase text-[10px] tracking-wider">
                                    <tr>
                                        <th class="py-2.5 px-3">Item Description</th>
                                        <th class="py-2.5 px-3 w-32 text-right">Price / Rate (₹)</th>
                                        <th class="py-2.5 px-3 w-28 text-center">Qty</th>
                                        <th class="py-2.5 px-3 w-32 text-right">Total (₹)</th>
                                        <th class="py-2.5 px-3 w-10 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <template x-for="(item, index) in items" :key="index">
                                        <tr class="hover:bg-gray-50/70 transition">
                                            <td class="py-2.5 px-3">
                                                <input type="hidden" :name="'items[' + index + '][product_id]'"
                                                    :value="item.product_id || ''">
                                                <input type="hidden" :name="'items[' + index + '][product_name]'"
                                                    :value="item.name">
                                                <input type="hidden" :name="'items[' + index + '][unit_price]'"
                                                    :value="item.unit_price">
                                                <input type="hidden" :name="'items[' + index + '][quantity]'"
                                                    :value="item.quantity">

                                                <div class="flex items-center gap-2">
                                                    <span class="font-medium text-gray-800" x-text="item.name"></span>
                                                    <span x-show="!item.product_id"
                                                        class="px-1.5 py-0.5 rounded text-[9px] bg-amber-50 border border-amber-200 text-amber-800 font-semibold">Direct
                                                        Item</span>
                                                </div>
                                                <div x-show="item.product_id" class="text-[10px] text-gray-400"
                                                    x-text="'Stock available: ' + item.stock"></div>
                                            </td>
                                            <td class="py-2.5 px-3 text-right font-mono">
                                                <div class="flex items-center justify-end gap-1">
                                                    <span class="text-gray-400 text-[11px]">₹</span>
                                                    <input type="number" x-model.number="item.unit_price"
                                                        @input="updateCalculation()" min="0" step="any"
                                                        class="w-24 text-right px-2 py-0.5 rounded border border-gray-200 focus:border-[#324b3e] font-mono text-xs outline-none" />
                                                </div>
                                            </td>
                                            <td class="py-2.5 px-3 text-center">
                                                <div
                                                    class="inline-flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                                    <button type="button" @click="decrementQty(index)"
                                                        class="px-2 py-0.5 bg-gray-100 hover:bg-gray-200 font-bold text-gray-600">-</button>
                                                    <input type="number" x-model.number="item.quantity"
                                                        @input="if(item.quantity < 1) item.quantity = 1; updateCalculation()"
                                                        min="1"
                                                        class="w-12 text-center text-xs font-semibold text-gray-800 border-none outline-none py-0.5" />
                                                    <button type="button" @click="incrementQty(index)"
                                                        class="px-2 py-0.5 bg-gray-100 hover:bg-gray-200 font-bold text-gray-600">+</button>
                                                </div>
                                            </td>
                                            <td class="py-2.5 px-3 text-right font-semibold font-mono text-gray-900"
                                                x-text="'₹' + (Number(item.quantity || 0) * Number(item.unit_price || 0)).toLocaleString('en-IN')">
                                            </td>
                                            <td class="py-2.5 px-3 text-center">
                                                <button type="button" @click="removeItem(index)"
                                                    class="text-rose-500 hover:text-rose-700 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="items.length === 0">
                                        <td colspan="5" class="text-center py-8 text-gray-400 text-xs">
                                            No items added yet. Search above, use the direct add bar, or click "Load
                                            Sample Bill".
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                Remarks / Payment Notes
                            </label>
                            <input type="text" name="notes" x-model="notes"
                                placeholder="e.g. Balance to be cleared by month end"
                                class="w-full px-3 py-2 rounded-xl text-xs bg-gray-50 border border-gray-300 focus:bg-white focus:border-[#324b3e] outline-none" />
                        </div>
                    </div>
                </div>

                <!-- Right Side (4 Cols): Payment Summary & Due Tracker -->
                <div class="lg:col-span-4 space-y-6">

                    <div class="bg-white rounded-2xl p-5 border border-gray-200/80 shadow-2xs space-y-4">
                        <div
                            class="text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-gray-100 pb-2">
                            3. Payment & Due Slip
                        </div>

                        <!-- Subtotal -->
                        <div class="flex items-center justify-between text-xs text-gray-600">
                            <span>Subtotal:</span>
                            <span class="font-mono font-semibold"
                                x-text="'₹' + subtotal().toLocaleString('en-IN')">₹0</span>
                        </div>

                        <!-- Discount -->
                        <div class="flex items-center justify-between text-xs text-gray-600">
                            <span>Discount (₹):</span>
                            <input type="number" name="discount" x-model.number="discount" min="0"
                                class="w-24 text-right px-2 py-1 rounded-lg text-xs border border-gray-300 focus:border-[#324b3e] outline-none" />
                        </div>

                        <!-- Grand Total Banner -->
                        <div class="p-4 rounded-xl bg-[#23382f] text-white flex items-center justify-between shadow-md">
                            <div>
                                <span
                                    class="text-[10px] text-emerald-300 uppercase tracking-wider block font-semibold">Grand
                                    Total</span>
                                <span class="text-xs text-gray-300">Net Payable Amount</span>
                            </div>
                            <div class="text-xl font-bold font-mono tracking-tight"
                                x-text="'₹' + grandTotal().toLocaleString('en-IN')">
                                ₹0
                            </div>
                        </div>

                        <!-- Amount Paid Input -->
                        <div class="space-y-1.5 pt-1">
                            <label class="block text-xs font-semibold text-gray-700">
                                Amount Received / Paid Now (₹) <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" name="paid_amount" x-model.number="paidAmount" required min="0"
                                step="any" placeholder="0.00"
                                class="w-full px-4 py-2.5 rounded-xl text-base font-bold font-mono text-emerald-800 bg-emerald-50/70 border border-emerald-300 focus:bg-white focus:border-[#324b3e] focus:ring-2 focus:ring-[#324b3e]/20 outline-none transition" />
                        </div>

                        <!-- Real-time Balance Due Card -->
                        <div class="p-3.5 rounded-xl border transition-all"
                            :class="dueAmount() > 0 ? 'bg-rose-50 border-rose-200 text-rose-900' : 'bg-emerald-50 border-emerald-200 text-emerald-900'">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-xs font-bold block"
                                        x-text="dueAmount() > 0 ? 'Remaining Balance Due (Khata):' : 'Fully Paid'"></span>
                                    <span class="text-[10px] opacity-75"
                                        x-text="dueAmount() > 0 ? 'Will be printed on slip & added to history' : 'Zero balance remaining'"></span>
                                </div>
                                <div class="text-lg font-bold font-mono"
                                    x-text="'₹' + dueAmount().toLocaleString('en-IN')">
                                    ₹0
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                Payment Method
                            </label>
                            <select name="payment_method" x-model="paymentMethod"
                                class="w-full px-3 py-2 rounded-xl text-xs bg-gray-50 border border-gray-300 focus:border-[#324b3e] outline-none">
                                <option value="cash">Cash</option>
                                <option value="upi">UPI / GPay / PhonePe</option>
                                <option value="card">Card / POS Terminal</option>
                                <option value="bank_transfer">Bank Transfer / NEFT</option>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit" :disabled="items.length === 0"
                                class="w-full py-3 px-5 rounded-full bg-[#324b3e] hover:bg-[#23382f] disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold text-sm shadow-md transition-all flex items-center justify-center gap-2 active:scale-[0.99]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                <span>{{ __('Generate Bill & Print Slip') }}</span>
                            </button>
                        </div>

                    </div>
                </div>

            </div>
        </form>
    </div>

    <!-- Modal for Adding Direct Product -->
    <div x-show="showCustomItemModal" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
        @keydown.escape.window="showCustomItemModal = false">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md border border-gray-200 shadow-2xl space-y-4"
            @click.away="showCustomItemModal = false">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <div class="flex items-center gap-2">
                    <div
                        class="w-8 h-8 rounded-lg bg-emerald-50 text-[#324b3e] flex items-center justify-center font-bold">
                        +
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-gray-800">Direct Product / Custom Item</h3>
                        <p class="text-[11px] text-gray-400">Add any item directly without saving in stock first</p>
                    </div>
                </div>
                <button type="button" @click="showCustomItemModal = false"
                    class="text-gray-400 hover:text-gray-600 text-sm">✕</button>
            </div>

            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                        Product / Item Name <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" x-ref="customNameInput" x-model="customItem.name"
                        @keydown.enter.prevent="$refs.customPriceInput.focus()"
                        placeholder="e.g. Fevicol 1kg or Plywood cutting"
                        class="w-full px-3.5 py-2 rounded-xl text-xs bg-gray-50 border border-gray-300 focus:bg-white focus:border-[#324b3e] outline-none" />
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                            Price / Rate (₹) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" x-ref="customPriceInput" x-model.number="customItem.price"
                            @keydown.enter.prevent="$refs.customQtyInput.focus()" min="0" step="any" placeholder="0.00"
                            class="w-full px-3.5 py-2 rounded-xl text-xs font-mono bg-gray-50 border border-gray-300 focus:bg-white focus:border-[#324b3e] outline-none" />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                            Quantity <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" x-ref="customQtyInput" x-model.number="customItem.quantity"
                            @keydown.enter.prevent="addCustomItemFromModal()" min="1" value="1"
                            class="w-full px-3.5 py-2 rounded-xl text-xs font-mono bg-gray-50 border border-gray-300 focus:bg-white focus:border-[#324b3e] outline-none" />
                    </div>
                </div>

                <div class="p-3 bg-gray-50 rounded-xl flex items-center justify-between font-mono text-xs">
                    <span class="text-gray-500">Item Total:</span>
                    <span class="font-bold text-gray-900 text-sm"
                        x-text="'₹' + Number((customItem.price || 0) * (customItem.quantity || 1)).toLocaleString('en-IN')"></span>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" @click="showCustomItemModal = false"
                    class="px-4 py-2 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-100 transition">
                    Cancel
                </button>
                <button type="button" @click="addCustomItemFromModal()"
                    class="px-5 py-2 rounded-xl text-xs font-bold bg-[#324b3e] hover:bg-[#23382f] text-white shadow-md transition flex items-center gap-1.5">
                    <span>Add to Bill</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Alpine.js POS Billing Logic -->
    <script>
        function billingSystem() {
            return {
                customer: {
                    name: '',
                    phone: '',
                    address: ''
                },
                searchQuery: '',
                searchResults: [],
                searchLoading: false,
                searchOpen: false,
                searchTimer: null,
                showCustomItemModal: false,
                quickItem: {
                    name: '',
                    price: '',
                    quantity: 1
                },
                customItem: {
                    name: '',
                    price: '',
                    quantity: 1
                },
                items: [],
                discount: 0,
                paidAmount: 0,
                paymentMethod: 'cash',
                notes: '',

                init() {
                    //
                },

                onSearchInput() {
                    clearTimeout(this.searchTimer);
                    const clean = this.searchQuery.trim();

                    if (clean.length < 2) {
                        this.searchResults = [];
                        this.searchOpen = false;
                        this.searchLoading = false;
                        return;
                    }

                    this.searchLoading = true;
                    this.searchOpen = true;

                    this.searchTimer = setTimeout(() => {
                        fetch('{{ route('products.search') }}?q=' + encodeURIComponent(clean), {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                            .then(res => res.json())
                            .then(data => {
                                this.searchResults = data;
                                this.searchLoading = false;
                                this.searchOpen = true;
                            })
                            .catch(err => {
                                console.error(err);
                                this.searchLoading = false;
                            });
                    }, 200);
                },

                onSearchEnter() {
                    const query = this.searchQuery.trim();
                    if (!query) return;

                    if (this.searchResults.length > 0) {
                        this.selectProduct(this.searchResults[0]);
                    } else {
                        this.openDirectModalWithQuery();
                    }
                },

                openDirectModalWithQuery() {
                    this.customItem.name = this.searchQuery.trim();
                    this.customItem.price = '';
                    this.customItem.quantity = 1;
                    this.searchOpen = false;
                    this.showCustomItemModal = true;
                    this.$nextTick(() => {
                        if (this.$refs.customPriceInput) {
                            this.$refs.customPriceInput.focus();
                        }
                    });
                },

                selectProduct(prod) {
                    const existing = this.items.find(i => i.product_id === prod.id);
                    if (existing) {
                        existing.quantity++;
                    } else {
                        this.items.push({
                            product_id: prod.id,
                            name: prod.name,
                            unit_price: parseFloat(prod.selling_price),
                            quantity: 1,
                            stock: prod.stock_quantity
                        });
                    }

                    this.searchQuery = '';
                    this.searchResults = [];
                    this.searchOpen = false;
                    this.autoSyncPaid();
                },

                addQuickItem() {
                    const name = (this.quickItem.name || '').trim();
                    const price = parseFloat(this.quickItem.price);
                    const qty = parseInt(this.quickItem.quantity) || 1;

                    if (!name) {
                        alert('Please enter item name.');
                        return;
                    }

                    if (isNaN(price) || price < 0) {
                        alert('Please enter a valid price/amount.');
                        return;
                    }

                    this.items.push({
                        product_id: null,
                        name: name,
                        unit_price: price,
                        quantity: qty,
                        stock: 'Direct item'
                    });

                    this.quickItem.name = '';
                    this.quickItem.price = '';
                    this.quickItem.quantity = 1;
                    this.autoSyncPaid();
                },

                addCustomItemFromModal() {
                    const name = (this.customItem.name || '').trim();
                    const price = parseFloat(this.customItem.price);
                    const qty = parseInt(this.customItem.quantity) || 1;

                    if (!name) {
                        alert('Please enter item name.');
                        return;
                    }

                    if (isNaN(price) || price < 0) {
                        alert('Please enter a valid rate.');
                        return;
                    }

                    this.items.push({
                        product_id: null,
                        name: name,
                        unit_price: price,
                        quantity: qty,
                        stock: 'Direct item'
                    });

                    this.showCustomItemModal = false;
                    this.searchQuery = '';
                    this.customItem.name = '';
                    this.customItem.price = '';
                    this.customItem.quantity = 1;
                    this.autoSyncPaid();
                },

                incrementQty(index) {
                    this.items[index].quantity++;
                    this.autoSyncPaid();
                },

                decrementQty(index) {
                    if (this.items[index].quantity > 1) {
                        this.items[index].quantity--;
                    } else {
                        this.removeItem(index);
                    }
                    this.autoSyncPaid();
                },

                removeItem(index) {
                    this.items.splice(index, 1);
                    this.autoSyncPaid();
                },

                updateCalculation() {
                    this.autoSyncPaid();
                },

                subtotal() {
                    return this.items.reduce((sum, item) => sum + ((Number(item.quantity) || 0) * (Number(item.unit_price) || 0)), 0);
                },

                grandTotal() {
                    return Math.max(0, this.subtotal() - (Number(this.discount) || 0));
                },

                dueAmount() {
                    return Math.max(0, this.grandTotal() - (Number(this.paidAmount) || 0));
                },

                autoSyncPaid() {
                    // If user hasn't typed an amount yet or it matched previous total, keep it responsive
                    // (we don't override if user specifically set partial payment)
                },


                validateForm() {
                    if (this.items.length === 0) {
                        alert('Please add at least one item to make a bill.');
                        return false;
                    }
                    if (!this.customer.name.trim()) {
                        alert('Please enter customer name.');
                        return false;
                    }
                    return true;
                }
            };
        }
    </script>
</x-app-layout>