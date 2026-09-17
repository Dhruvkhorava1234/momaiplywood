<x-app-layout>
    <div x-data="{ paymentModal: false, selectedBill: null, paymentAmount: '' }" class="space-y-6">
        
        <!-- Header & Metrics -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-gray-200/80 shadow-2xs">
            <div>
                <h1 class="text-xl font-bold text-gray-800 tracking-tight">
                    {{ __('Bills & Transaction History') }}
                </h1>
                <p class="text-xs text-gray-500">
                    {{ __('Full DataTable ledger of customer bills, payments, and outstanding balances.') }}
                </p>
            </div>
            <div>
                <a href="{{ route('bills.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#324b3e] hover:bg-[#23382f] text-white text-xs font-bold shadow-md transition active:scale-[0.98]">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>{{ __('Make New Bill') }}</span>
                </a>
            </div>
        </div>

        <!-- 3 Financial Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-2xs">
                <span class="text-[11px] font-medium text-gray-500 block">Total Billed Volume</span>
                <span class="text-xl font-bold text-gray-900 font-mono">₹{{ number_format($totalBilled, 2) }}</span>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-2xs">
                <span class="text-[11px] font-medium text-emerald-600 block">Total Collected Cash</span>
                <span class="text-xl font-bold text-emerald-800 font-mono">₹{{ number_format($totalCollected, 2) }}</span>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-rose-200 shadow-2xs bg-rose-50/40">
                <span class="text-[11px] font-bold text-rose-600 block">Total Customer Due (Khata)</span>
                <span class="text-xl font-bold text-rose-700 font-mono">₹{{ number_format($totalDue, 2) }}</span>
            </div>
        </div>

        <!-- Status Filter Tabs -->
        <div class="flex flex-wrap items-center gap-2">
            <span class="text-xs font-semibold text-gray-500">Filter Status:</span>
            <a href="{{ route('bills.index') }}"
               class="px-3 py-1 rounded-full text-xs font-semibold transition {{ !request('status') ? 'bg-[#324b3e] text-white shadow-2xs' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                All Bills
            </a>
            <a href="{{ route('bills.index', ['status' => 'partial']) }}"
               class="px-3 py-1 rounded-full text-xs font-semibold transition {{ request('status') === 'partial' ? 'bg-amber-600 text-white shadow-2xs' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                Partial Due
            </a>
            <a href="{{ route('bills.index', ['status' => 'paid']) }}"
               class="px-3 py-1 rounded-full text-xs font-semibold transition {{ request('status') === 'paid' ? 'bg-emerald-600 text-white shadow-2xs' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                Fully Paid
            </a>
            <a href="{{ route('bills.index', ['status' => 'unpaid']) }}"
               class="px-3 py-1 rounded-full text-xs font-semibold transition {{ request('status') === 'unpaid' ? 'bg-rose-600 text-white shadow-2xs' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                Unpaid
            </a>
        </div>

        <!-- Bills DataTable Card -->
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-2xs overflow-hidden">
            <div class="p-2 sm:p-4 overflow-x-auto">
                <table id="bills-table" class="display responsive nowrap w-full text-left text-xs">
                    <thead>
                        <tr>
                            <th>Bill No</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th class="text-right">Grand Total</th>
                            <th class="text-right">Paid</th>
                            <th class="text-right">Balance Due</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bills as $bill)
                            <tr>
                                <td class="font-mono font-bold text-gray-800">
                                    <a href="{{ route('bills.slip', $bill) }}" class="hover:text-[#324b3e] underline">
                                        {{ $bill->bill_number }}
                                    </a>
                                </td>
                                <td>
                                    <div class="font-semibold text-gray-900">{{ $bill->customer_name }}</div>
                                    @if ($bill->customer_phone)
                                        <div class="text-[10px] text-gray-400 font-mono">{{ $bill->customer_phone }}</div>
                                    @endif
                                </td>
                                <td class="text-gray-500 font-mono text-[11px]" data-order="{{ $bill->created_at->timestamp }}">
                                    {{ $bill->created_at->format('d M Y, h:i A') }}
                                </td>
                                <td class="text-right font-mono font-semibold text-gray-900" data-order="{{ $bill->grand_total }}">
                                    ₹{{ number_format($bill->grand_total, 2) }}
                                </td>
                                <td class="text-right font-mono text-emerald-700 font-semibold" data-order="{{ $bill->paid_amount }}">
                                    ₹{{ number_format($bill->paid_amount, 2) }}
                                </td>
                                <td class="text-right font-mono font-bold {{ $bill->due_amount > 0 ? 'text-rose-600' : 'text-gray-400' }}" data-order="{{ $bill->due_amount }}">
                                    ₹{{ number_format($bill->due_amount, 2) }}
                                </td>
                                <td class="text-center">
                                    @if ($bill->payment_status === 'paid')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            Paid
                                        </span>
                                    @elseif ($bill->payment_status === 'partial')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-900 border border-amber-200">
                                            Partial Due
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                            Unpaid
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        @if ($bill->payment_status === 'paid')
                                            <a href="{{ route('bills.slip', $bill) }}"
                                               class="px-2.5 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 font-bold text-[11px] transition"
                                               title="View & Print Official MOMAI Bill">
                                                📄 Full Bill
                                            </a>
                                        @else
                                            <a href="{{ route('bills.slip', $bill) }}"
                                               class="px-2.5 py-1 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-300 font-semibold text-[11px] transition"
                                               title="View Small Due Slip">
                                                🧾 Due Slip
                                            </a>

                                            <button type="button"
                                                    @click="selectedBill = {{ $bill->toJson() }}; paymentAmount = '{{ $bill->due_amount }}'; paymentModal = true"
                                                    class="px-2.5 py-1 rounded-lg bg-[#324b3e] hover:bg-[#23382f] text-white font-bold text-[11px] shadow-2xs transition"
                                                    title="Collect Due Payment">
                                                + Pay Due
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Due Payment Collection Modal -->
        <div x-show="paymentModal"
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-black/50 backdrop-blur-xs overflow-y-auto">
            <div class="bg-white rounded-3xl p-5 sm:p-6 max-w-md w-full shadow-2xl space-y-4 border border-gray-200 my-auto"
                 @click.away="paymentModal = false">
                <div class="flex items-center justify-between border-b pb-3">
                    <h3 class="font-bold text-gray-900 text-sm">
                        Collect Due Payment
                    </h3>
                    <button type="button" @click="paymentModal = false" class="text-gray-400 hover:text-gray-600 text-lg font-bold">&times;</button>
                </div>

                <template x-if="selectedBill">
                    <form :action="'/bills/' + selectedBill.id + '/payment'" method="POST" class="space-y-3.5">
                        @csrf

                        <div class="bg-gray-50 p-3 rounded-xl text-xs space-y-1">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Bill Number:</span>
                                <span class="font-mono font-bold text-gray-800" x-text="selectedBill.bill_number"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Customer:</span>
                                <span class="font-semibold text-gray-800" x-text="selectedBill.customer_name"></span>
                            </div>
                            <div class="flex justify-between text-rose-600 font-bold">
                                <span>Outstanding Due:</span>
                                <span class="font-mono" x-text="'₹' + Number(selectedBill.due_amount).toLocaleString('en-IN')"></span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">
                                Amount Paying Now (₹)
                            </label>
                            <input type="number"
                                   name="amount"
                                   x-model="paymentAmount"
                                   :max="selectedBill.due_amount"
                                   min="1"
                                   step="any"
                                   required
                                   class="w-full px-3.5 py-2 rounded-xl text-sm font-bold font-mono text-emerald-800 bg-white border border-gray-300 focus:border-[#324b3e] outline-none" />
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                Payment Method
                            </label>
                            <select name="payment_method"
                                    class="w-full px-3 py-2 rounded-xl text-xs bg-gray-50 border border-gray-300 outline-none">
                                <option value="cash">Cash</option>
                                <option value="upi">UPI / Online</option>
                                <option value="card">Card</option>
                                <option value="bank_transfer">Bank Transfer</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                Note
                            </label>
                            <input type="text"
                                   name="note"
                                   placeholder="e.g. Paid remaining balance by cash"
                                   class="w-full px-3 py-2 rounded-xl text-xs bg-gray-50 border border-gray-300 outline-none" />
                        </div>

                        <div class="pt-2 flex items-center justify-end gap-2">
                            <button type="button"
                                    @click="paymentModal = false"
                                    class="px-4 py-2 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-5 py-2 rounded-full bg-[#324b3e] hover:bg-[#23382f] text-white text-xs font-bold shadow-md">
                                Save Payment
                            </button>
                        </div>
                    </form>
                </template>
            </div>
        </div>

    </div>

    <!-- DataTables Initialization Script -->
    <script>
        $(document).ready(function() {
            $('#bills-table').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50, 100],
                order: [[2, 'desc']], // Sort by date descending
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search bill #, customer, amount...",
                    lengthMenu: "Show _MENU_ bills per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ bills",
                    paginate: {
                        first: "«",
                        last: "»",
                        next: "Next →",
                        previous: "← Prev"
                    }
                }
            });
        });
    </script>
</x-app-layout>
