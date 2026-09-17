<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Action Toolbar (Hidden during print) -->
        <div
            class="flex flex-wrap items-center justify-between gap-3 bg-white p-4 rounded-2xl border border-gray-200/80 shadow-2xs print:hidden">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span class="text-xs font-bold text-gray-700">Bill Generated Successfully!</span>
            </div>

            <div class="flex items-center gap-2.5">
                <button type="button" onclick="window.print()"
                    class="px-4 py-2 rounded-full bg-[#324b3e] hover:bg-[#23382f] text-white text-xs font-bold shadow-md transition flex items-center gap-1.5 active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>{{ __('Print Slip') }}</span>
                </button>

                <a href="{{ route('bills.create') }}"
                    class="px-3.5 py-2 rounded-full bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-semibold border border-emerald-200 transition">
                    {{ __('+ New Bill') }}
                </a>

                <a href="{{ route('bills.index') }}"
                    class="px-3.5 py-2 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold transition">
                    {{ __('All Bills History') }}
                </a>
            </div>
        </div>

        <!-- Exact Physical MOMAI PLYWOOD Invoice Slip -->
        <div id="printable-slip"
            class="relative mx-auto bg-white border-2 border-[#b53127] rounded-sm p-4 sm:p-7 shadow-lg font-sans text-[#b53127] max-w-[800px] select-none">

            <!-- Top Row: Phone Number -->
            <div class="flex justify-end items-center relative z-10 text-xs font-bold tracking-wider mb-1">
                <span>MO. 91063 40961</span>
            </div>

            <!-- Header: MOMAI PLYWOOD -->
            <div class="relative z-10 pb-2">
                <div
                    class="text-2xl sm:text-3xl font-extrabold tracking-widest uppercase leading-none font-serif text-[#b53127]">
                    MOMAI
                </div>
                <div
                    class="text-2xl sm:text-3xl font-extrabold tracking-widest uppercase leading-none font-serif text-[#b53127]">
                    PLYWOOD
                </div>
            </div>

            <!-- Customer & Bill Info Grid Box -->
            <div class="border-t-2 border-b-2 border-[#b53127] my-2 text-xs">
                <div class="grid grid-cols-12">

                    <!-- Left Section: NAME, ADDRESS, MO (8 cols) -->
                    <div class="col-span-8 sm:col-span-9 pr-3 py-2 border-r-2 border-[#b53127] space-y-2.5">
                        <!-- NAME -->
                        <div class="flex items-end">
                            <span class="font-extrabold tracking-wider uppercase text-[11px] shrink-0 w-16">NAME
                                :</span>
                            <div
                                class="flex-1 border-b border-[#b53127] px-2 font-serif text-base font-bold text-gray-900 leading-tight">
                                {{ $bill->customer_name }}
                            </div>
                        </div>

                        <!-- ADDRESS -->
                        <div class="flex items-end">
                            <span class="font-extrabold tracking-wider uppercase text-[11px] shrink-0 w-16">ADDRESS
                                :</span>
                            <div
                                class="flex-1 border-b border-[#b53127] px-2 font-serif text-sm font-semibold text-gray-800 leading-tight">
                                {{ $bill->customer_address ?? ($bill->customer->address ?? '') }}
                            </div>
                        </div>

                        <!-- MO -->
                        <div class="flex items-end">
                            <span class="font-extrabold tracking-wider uppercase text-[11px] shrink-0 w-16">MO. :</span>
                            <div
                                class="flex-1 border-b border-[#b53127] px-2 font-serif text-sm font-semibold text-gray-800 leading-tight">
                                {{ $bill->customer_phone ?? ($bill->customer->phone ?? '') }}
                            </div>
                        </div>
                    </div>

                    <!-- Right Section: BILL NO, DATE (4 cols) -->
                    <div class="col-span-4 sm:col-span-3 pl-2 sm:pl-3 py-2 flex flex-col justify-between">
                        <!-- BILL NO -->
                        <div class="flex items-baseline gap-2 border-b border-[#b53127] pb-1">
                            <span class="font-extrabold tracking-wider uppercase text-[11px] shrink-0">BILL NO. :</span>
                            <span class="font-serif text-base font-black text-gray-900">
                                {{ preg_replace('/[^0-9]/', '', $bill->bill_number) ?: $bill->id }}
                            </span>
                        </div>

                        <!-- DATE -->
                        <div class="flex items-baseline gap-2 pt-1 border-b border-[#b53127]">
                            <span class="font-extrabold tracking-wider uppercase text-[11px] shrink-0">DATE :</span>
                            <span class="font-serif text-sm font-bold text-gray-900 px-1">
                                {{ $bill->created_at->format('d / m / y') }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Table of Items (Red borders matching physical bill) -->
            <div class="w-full mt-2">
                <table class="w-full border-collapse text-xs">
                    <thead>
                        <tr class="border-b-2 border-[#b53127] text-center font-extrabold uppercase text-[11px]">
                            <th class="w-12 py-1.5 border-r border-[#b53127]">SR.<br>NO.</th>
                            <th class="py-1.5 px-3 text-center border-r border-[#b53127]">PARTICULARS</th>
                            <th class="w-14 py-1.5 text-center border-r border-[#b53127]">QTY.</th>
                            <th class="w-20 py-1.5 text-center border-r border-[#b53127]">RATE</th>
                            <th class="w-24 py-1.5 text-center">AMOUNT</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-medium">
                        @php
                            $totalRows = max(10, count($bill->items));
                        @endphp

                        @for ($i = 0; $i < $totalRows; $i++)
                            @php
                                $item = $bill->items[$i] ?? null;
                            @endphp
                            <tr class="border-b border-[#b53127]/60 h-8">
                                <!-- SR NO -->
                                <td class="text-center font-bold font-serif text-gray-900 border-r border-[#b53127]">
                                    {{ $item ? ($i + 1) . '.' : '' }}
                                </td>

                                <!-- PARTICULARS -->
                                <td class="px-3 font-serif font-bold text-gray-900 border-r border-[#b53127]">
                                    @if ($item)
                                        <span>{{ $item->product_name }}</span>
                                    @endif
                                </td>

                                <!-- QTY -->
                                <td class="text-center font-serif font-bold text-gray-900 border-r border-[#b53127]">
                                    {{ $item ? $item->quantity : '' }}
                                </td>

                                <!-- RATE -->
                                <td class="text-right pr-2 font-serif font-bold text-gray-900 border-r border-[#b53127]">
                                    {{ $item ? number_format($item->unit_price, 0) : '' }}
                                </td>

                                <!-- AMOUNT -->
                                <td class="text-right pr-2 font-serif font-bold text-gray-900">
                                    {{ $item ? number_format($item->total_price, 0) : '' }}
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>

            <!-- Footer Section: IN WORD, ADVANCE RS, GRAND TOTAL -->
            <div class="border-t-2 border-[#b53127] mt-0 text-xs">
                <div class="grid grid-cols-12">

                    <!-- IN WORD & MOTTO (Left 8 cols) -->
                    <div
                        class="col-span-8 sm:col-span-8 pr-3 py-2 border-r-2 border-[#b53127] flex flex-col justify-between">
                        <!-- IN WORD -->
                        <div class="flex items-start">
                            <span class="font-extrabold tracking-wider uppercase text-[11px] shrink-0 pt-0.5">IN WORD
                                :</span>
                            <span
                                class="pl-2 font-serif text-sm sm:text-base font-bold text-gray-900 capitalize leading-tight">
                                {{ $bill->amount_in_words }}
                            </span>
                        </div>
                    </div>

                    <!-- ADVANCE RS & GRAND TOTAL (Right 4 cols) -->
                    <div class="col-span-4 sm:col-span-4 flex flex-col justify-between">

                        <!-- ADVANCE RS (Or Amount Paid / Advance) -->
                        <div class="border-b-2 border-[#b53127] px-2 py-1.5 flex items-center justify-between">
                            <span class="font-extrabold tracking-wider uppercase text-[11px]">ADVANCE RS.</span>
                            <span class="font-serif text-sm font-bold text-gray-900">
                                @if ($bill->paid_amount > 0 && $bill->due_amount > 0)
                                    {{ number_format($bill->paid_amount, 0) }}
                                @endif
                            </span>
                        </div>

                        <!-- GRAND TOTAL -->
                        <div class="px-2 py-2 flex items-center justify-between">
                            <span class="font-extrabold tracking-wider uppercase text-[11px]">GRAND TOTAL...</span>
                            <span class="font-serif text-base sm:text-lg font-black text-gray-900">
                                {{ number_format($bill->grand_total, 0) }}
                            </span>
                        </div>

                    </div>

                </div>
            </div>

            <!-- Khata / Due Balance Indicator if Partial (Print clean) -->
            @if ($bill->due_amount > 0)
                <div
                    class="mt-2 pt-1 border-t border-dashed border-[#b53127] flex justify-between items-center text-[11px] font-bold">
                    <span>REMAINING BALANCE DUE:</span>
                    <span
                        class="font-serif text-sm font-bold text-rose-700">₹{{ number_format($bill->due_amount, 2) }}</span>
                </div>
            @endif

            <!-- MOTTO -->
            <div class="pt-4 text-center font-bold tracking-wider text-[11px] uppercase text-[#b53127]">
                "YOUR SATISFACTION IS OUR PRIORITY"
            </div>
        </div>

    </div>
</x-app-layout>