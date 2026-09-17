<x-app-layout>
    <div class="max-w-md mx-auto space-y-6">
        
        <!-- Action Toolbar (Hidden during print) -->
        <div class="flex flex-wrap items-center justify-between gap-3 bg-white p-4 rounded-2xl border border-gray-200/80 shadow-2xs print:hidden">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                <span class="text-xs font-bold text-gray-700">Due Payment Slip</span>
            </div>
            
            <div class="flex items-center gap-2">
                <button type="button"
                        onclick="window.print()"
                        class="px-3.5 py-2 rounded-full bg-[#324b3e] hover:bg-[#23382f] text-white text-xs font-bold shadow-md transition flex items-center gap-1.5 active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    <span>{{ __('Print Slip') }}</span>
                </button>

                <a href="{{ route('bills.index') }}"
                   class="px-3 py-2 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold transition">
                    {{ __('Bills') }}
                </a>

                <a href="{{ route('bills.show', $bill) }}"
                   class="px-3 py-2 rounded-full bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-semibold border border-emerald-200 transition"
                   title="View Full Bill">
                    {{ __('Full Bill') }}
                </a>
            </div>
        </div>

        <!-- Info Note explaining workflow -->
        <div class="p-3.5 bg-amber-50 rounded-2xl border border-amber-200 text-amber-900 text-xs flex items-center justify-between print:hidden">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span><strong>Partial Due Active:</strong> Small slip issued for payment received. The official Red MOMAI PLYWOOD Full Bill unlocks automatically once fully paid.</span>
            </div>
        </div>

        <!-- Small Compact Slip Document (Matches #printable-due-slip) -->
        <div id="printable-due-slip" class="bg-white rounded-2xl p-6 border-2 border-dashed border-gray-300 shadow-md text-gray-900 font-mono text-xs space-y-4 max-w-[380px] mx-auto">
            
            <!-- Store Header -->
            <div class="text-center pb-3 border-b-2 border-dashed border-gray-300">
                <div class="font-extrabold text-base tracking-widest text-[#23382f]">
                    MOMAI PLYWOOD
                </div>
                <div class="text-[10px] text-gray-500 font-sans uppercase">
                    Payment Receipt & Due Khata Slip
                </div>
                <div class="text-[11px] font-bold text-gray-600 mt-0.5">
                    MO. 91063 40961
                </div>
            </div>

            <!-- Receipt Info -->
            <div class="space-y-1 text-[11px] pb-3 border-b border-dashed border-gray-200">
                <div class="flex justify-between">
                    <span class="text-gray-500">Bill Ref:</span>
                    <span class="font-bold text-gray-900">{{ $bill->bill_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Date & Time:</span>
                    <span class="font-bold text-gray-800">
                        {{ ($latestPayment ? $latestPayment->created_at : $bill->created_at)->format('d/m/Y, h:i A') }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Customer:</span>
                    <span class="font-bold text-gray-900">{{ $bill->customer_name }}</span>
                </div>
                @if ($bill->customer_phone)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Phone:</span>
                        <span class="text-gray-800">{{ $bill->customer_phone }}</span>
                    </div>
                @endif
                @if ($bill->customer_address)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Address:</span>
                        <span class="text-gray-800">{{ $bill->customer_address }}</span>
                    </div>
                @endif
            </div>

            <!-- Items Purchased Summary -->
            <div class="space-y-1.5 pb-3 border-b border-dashed border-gray-200 text-[11px]">
                <div class="flex justify-between font-bold text-gray-500 uppercase text-[9px] tracking-wider pb-1">
                    <span>Particulars</span>
                    <span>Qty x Rate</span>
                    <span class="text-right">Amt</span>
                </div>
                @foreach ($bill->items as $item)
                    <div class="flex justify-between items-baseline gap-1">
                        <span class="truncate flex-1 font-semibold text-gray-800">{{ $item->product_name }}</span>
                        <span class="text-gray-500 text-[10px]">{{ $item->quantity }} x {{ number_format($item->unit_price, 0) }}</span>
                        <span class="font-bold text-right font-mono">₹{{ number_format($item->total_price, 0) }}</span>
                    </div>
                @endforeach
            </div>

            <!-- Financial Calculation & Due Summary -->
            <div class="space-y-1.5 text-[11px] pt-1">
                <div class="flex justify-between text-gray-600">
                    <span>Bill Grand Total:</span>
                    <span class="font-bold font-mono">₹{{ number_format($bill->grand_total, 2) }}</span>
                </div>

                @if ($latestPayment)
                    <div class="flex justify-between font-bold text-emerald-800 bg-emerald-50 px-2 py-1 rounded">
                        <span>Payment Received:</span>
                        <span class="font-mono">₹{{ number_format($latestPayment->amount_paid, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-[10px] text-gray-500 px-2">
                        <span>Mode: {{ strtoupper($latestPayment->payment_method) }}</span>
                        <span>{{ $latestPayment->note ?? '' }}</span>
                    </div>
                @endif

                <div class="flex justify-between text-gray-600">
                    <span>Total Paid So Far:</span>
                    <span class="font-semibold font-mono text-emerald-700">₹{{ number_format($bill->paid_amount, 2) }}</span>
                </div>

                <!-- OUTSTANDING REMAINING BALANCE DUE (Boxed High-Visibility) -->
                <div class="mt-2 p-2.5 border-2 border-rose-400 bg-rose-50/70 rounded-lg text-rose-950 flex justify-between items-center">
                    <div>
                        <div class="font-black text-xs uppercase tracking-wide text-rose-800">
                            REMAINING DUE:
                        </div>
                        <div class="text-[9px] text-rose-600 font-sans">
                            Balance Due (Khata)
                        </div>
                    </div>
                    <div class="text-base font-black font-mono text-rose-700">
                        ₹{{ number_format($bill->due_amount, 2) }}
                    </div>
                </div>
            </div>

            <!-- Footer Notes -->
            <div class="pt-3 border-t-2 border-dashed border-gray-300 text-center text-[10px] text-gray-500 space-y-1">
                <p class="font-bold text-gray-700 uppercase">"YOUR SATISFACTION IS OUR PRIORITY"</p>
                <p>Full official bill issued upon full payment clearance.</p>
                <p class="text-[9px] text-gray-400 font-sans">Thank you for your business!</p>
            </div>

        </div>

    </div>
</x-app-layout>