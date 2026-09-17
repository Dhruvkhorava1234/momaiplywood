<x-app-layout>
    <div x-data="{ addModal: false, stockModal: false, editModal: false, selectedProduct: null, editingProduct: null }" class="space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-gray-200/80 shadow-2xs">
            <div>
                <h1 class="text-xl font-bold text-gray-800 tracking-tight">
                    {{ __('Inventory & Stock Management') }}
                </h1>
                <p class="text-xs text-gray-500">
                    {{ __('Real-time DataTable with live search, column sorting, pagination, and stock replenishment.') }}
                </p>
            </div>
            <div class="flex items-center gap-2.5">
                <button type="button"
                        @click="addModal = true"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#324b3e] hover:bg-[#23382f] text-white text-xs font-bold shadow-md transition active:scale-[0.98]">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>{{ __('Add Product') }}</span>
                </button>
            </div>
        </div>

        <!-- Stock Status Quick Filter Pills -->
        <div class="flex flex-wrap items-center gap-2">
            <span class="text-xs font-semibold text-gray-500">Filter Status:</span>
            <a href="{{ route('products.index') }}"
               class="px-3 py-1 rounded-full text-xs font-semibold transition {{ !request('stock_status') ? 'bg-[#324b3e] text-white shadow-2xs' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                All Items
            </a>
            <a href="{{ route('products.index', ['stock_status' => 'low']) }}"
               class="px-3 py-1 rounded-full text-xs font-semibold transition {{ request('stock_status') === 'low' ? 'bg-amber-600 text-white shadow-2xs' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                Low Stock
            </a>
            <a href="{{ route('products.index', ['stock_status' => 'out']) }}"
               class="px-3 py-1 rounded-full text-xs font-semibold transition {{ request('stock_status') === 'out' ? 'bg-rose-600 text-white shadow-2xs' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                Out of Stock
            </a>
        </div>

        <!-- Products DataTable Card -->
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-2xs overflow-hidden">
            <div class="p-2 sm:p-4 overflow-x-auto">
                <table id="products-table" class="display responsive nowrap w-full text-left text-xs">
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
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $prod)
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
                                <td class="text-center font-mono font-bold">
                                    <span class="{{ $prod->stock_quantity <= 0 ? 'text-rose-600 font-black' : ($prod->stock_quantity <= $prod->min_alert_stock ? 'text-amber-600' : 'text-emerald-700') }}">
                                        {{ $prod->stock_quantity }} {{ $prod->unit }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if ($prod->stock_quantity <= 0)
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                            Out of Stock
                                        </span>
                                    @elseif ($prod->stock_quantity <= $prod->min_alert_stock)
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-900 border border-amber-200">
                                            Low Stock
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            In Stock
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <button type="button"
                                                @click="editingProduct = {{ $prod->toJson() }}; editModal = true"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-[11px] transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            <span>Update</span>
                                        </button>

                                        <button type="button"
                                                @click="selectedProduct = {{ $prod->toJson() }}; stockModal = true"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-[#324b3e]/10 hover:bg-[#324b3e]/20 text-[#324b3e] font-bold text-[11px] transition">
                                            <span>Stock</span>
                                        </button>

                                        <form action="{{ route('products.destroy', $prod) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-[11px] transition"
                                                    title="Delete Product">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                <span>Delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Product Modal -->
        <div x-show="addModal"
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-black/50 backdrop-blur-xs overflow-y-auto">
            <div class="bg-white rounded-3xl p-5 sm:p-6 max-w-lg w-full shadow-2xl space-y-4 border border-gray-200 my-auto"
                 @click.away="addModal = false">
                <div class="flex items-center justify-between border-b pb-3">
                    <h3 class="font-bold text-gray-900 text-sm">
                        Add New Inventory Product
                    </h3>
                    <button type="button" @click="addModal = false" class="text-gray-400 hover:text-gray-600 text-lg font-bold">&times;</button>
                </div>

                <form action="{{ route('products.store') }}" method="POST" class="space-y-3.5">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Product Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" required placeholder="e.g. Solar Inverter 10KVA"
                                   class="w-full px-3.5 py-2 rounded-xl text-xs bg-gray-50 border border-gray-300 focus:bg-white focus:border-[#324b3e] outline-none" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Company / Brand Name</label>
                            <input type="text" name="company_name" placeholder="e.g. Luminous, Tata, Greenlam"
                                   class="w-full px-3.5 py-2 rounded-xl text-xs bg-gray-50 border border-gray-300 focus:bg-white focus:border-[#324b3e] outline-none" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">SKU / Code</label>
                            <input type="text" name="sku" placeholder="Auto-generated if blank"
                                   class="w-full px-3.5 py-2 rounded-xl text-xs bg-gray-50 border border-gray-300 focus:bg-white focus:border-[#324b3e] outline-none" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Category <span class="text-rose-500">*</span></label>
                            <input type="text" name="category" required placeholder="e.g. Inverters"
                                   class="w-full px-3.5 py-2 rounded-xl text-xs bg-gray-50 border border-gray-300 focus:bg-white focus:border-[#324b3e] outline-none" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Cost Price (₹)</label>
                            <input type="number" name="cost_price" step="any" min="0" placeholder="0.00"
                                   class="w-full px-3.5 py-2 rounded-xl text-xs bg-gray-50 border border-gray-300 focus:bg-white focus:border-[#324b3e] outline-none" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Selling Price (₹) <span class="text-rose-500">*</span></label>
                            <input type="number" name="selling_price" step="any" min="0" required placeholder="100000.00"
                                   class="w-full px-3.5 py-2 rounded-xl text-xs bg-gray-50 border border-gray-300 focus:bg-white focus:border-[#324b3e] outline-none" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Initial Stock <span class="text-rose-500">*</span></label>
                            <input type="number" name="stock_quantity" min="0" required value="10"
                                   class="w-full px-3.5 py-2 rounded-xl text-xs bg-gray-50 border border-gray-300 focus:bg-white focus:border-[#324b3e] outline-none" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Alert Min Stock</label>
                            <input type="number" name="min_alert_stock" min="0" value="5"
                                   class="w-full px-3.5 py-2 rounded-xl text-xs bg-gray-50 border border-gray-300 focus:bg-white focus:border-[#324b3e] outline-none" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Unit</label>
                            <input type="text" name="unit" value="pcs"
                                   class="w-full px-3.5 py-2 rounded-xl text-xs bg-gray-50 border border-gray-300 focus:bg-white focus:border-[#324b3e] outline-none" />
                        </div>
                    </div>

                    <div class="pt-2 flex items-center justify-end gap-2">
                        <button type="button" @click="addModal = false"
                                class="px-4 py-2 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-5 py-2 rounded-full bg-[#324b3e] hover:bg-[#23382f] text-white text-xs font-bold shadow-md">
                            Save Product
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Stock Adjustment Modal -->
        <div x-show="stockModal"
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-black/50 backdrop-blur-xs overflow-y-auto">
            <div class="bg-white rounded-3xl p-5 sm:p-6 max-w-md w-full shadow-2xl space-y-4 border border-gray-200 my-auto"
                 @click.away="stockModal = false">
                <div class="flex items-center justify-between border-b pb-3">
                    <h3 class="font-bold text-gray-900 text-sm">
                        Adjust Stock Quantity
                    </h3>
                    <button type="button" @click="stockModal = false" class="text-gray-400 hover:text-gray-600 text-lg font-bold">&times;</button>
                </div>

                <template x-if="selectedProduct">
                    <form :action="'/products/' + selectedProduct.id + '/adjust-stock'" method="POST" class="space-y-3.5">
                        @csrf

                        <div class="bg-gray-50 p-3 rounded-xl text-xs space-y-1">
                            <div class="font-bold text-gray-900" x-text="selectedProduct.name"></div>
                            <div class="flex justify-between text-gray-500">
                                <span>Current Stock:</span>
                                <span class="font-mono font-bold text-emerald-700" x-text="selectedProduct.stock_quantity + ' ' + selectedProduct.unit"></span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Adjustment Action</label>
                            <select name="adjustment_type" class="w-full px-3.5 py-2 rounded-xl text-xs bg-gray-50 border border-gray-300 outline-none">
                                <option value="add">+ Add Received Stock</option>
                                <option value="subtract">- Remove / Damaged Stock</option>
                                <option value="set">Set Exact Count</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Quantity</label>
                            <input type="number" name="quantity" min="1" required value="5"
                                   class="w-full px-3.5 py-2 rounded-xl text-xs bg-white border border-gray-300 focus:border-[#324b3e] outline-none font-bold" />
                        </div>

                        <div class="pt-2 flex items-center justify-end gap-2">
                            <button type="button" @click="stockModal = false"
                                    class="px-4 py-2 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-5 py-2 rounded-full bg-[#324b3e] hover:bg-[#23382f] text-white text-xs font-bold shadow-md">
                                Update Stock
                            </button>
                        </div>
                    </form>
                </template>
            </div>
        </div>

        <!-- Edit/Update Product Modal -->
        <div x-show="editModal"
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-black/50 backdrop-blur-xs overflow-y-auto">
            <div class="bg-white rounded-3xl p-5 sm:p-6 max-w-lg w-full shadow-2xl space-y-4 border border-gray-200 my-auto"
                 @click.away="editModal = false">
                <div class="flex items-center justify-between border-b pb-3">
                    <h3 class="font-bold text-gray-900 text-sm">
                        Update Product Details
                    </h3>
                    <button type="button" @click="editModal = false" class="text-gray-400 hover:text-gray-600 text-lg font-bold">&times;</button>
                </div>

                <template x-if="editingProduct">
                    <form :action="'/products/' + editingProduct.id" method="POST" class="space-y-3.5">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Product Name <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" required x-model="editingProduct.name"
                                       class="w-full px-3.5 py-2 rounded-xl text-xs bg-gray-50 border border-gray-300 focus:bg-white focus:border-[#324b3e] outline-none" />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Company / Brand Name</label>
                                <input type="text" name="company_name" x-model="editingProduct.company_name"
                                       class="w-full px-3.5 py-2 rounded-xl text-xs bg-gray-50 border border-gray-300 focus:bg-white focus:border-[#324b3e] outline-none" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">SKU / Code</label>
                                <input type="text" name="sku" x-model="editingProduct.sku"
                                       class="w-full px-3.5 py-2 rounded-xl text-xs bg-gray-50 border border-gray-300 focus:bg-white focus:border-[#324b3e] outline-none" />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Category <span class="text-rose-500">*</span></label>
                                <input type="text" name="category" required x-model="editingProduct.category"
                                       class="w-full px-3.5 py-2 rounded-xl text-xs bg-gray-50 border border-gray-300 focus:bg-white focus:border-[#324b3e] outline-none" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Cost Price (₹)</label>
                                <input type="number" name="cost_price" step="any" min="0" x-model="editingProduct.cost_price"
                                       class="w-full px-3.5 py-2 rounded-xl text-xs bg-gray-50 border border-gray-300 focus:bg-white focus:border-[#324b3e] outline-none" />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Selling Price (₹) <span class="text-rose-500">*</span></label>
                                <input type="number" name="selling_price" step="any" min="0" required x-model="editingProduct.selling_price"
                                       class="w-full px-3.5 py-2 rounded-xl text-xs bg-gray-50 border border-gray-300 focus:bg-white focus:border-[#324b3e] outline-none" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Stock Quantity <span class="text-rose-500">*</span></label>
                                <input type="number" name="stock_quantity" min="0" required x-model="editingProduct.stock_quantity"
                                       class="w-full px-3.5 py-2 rounded-xl text-xs bg-gray-50 border border-gray-300 focus:bg-white focus:border-[#324b3e] outline-none" />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Alert Min Stock</label>
                                <input type="number" name="min_alert_stock" min="0" x-model="editingProduct.min_alert_stock"
                                       class="w-full px-3.5 py-2 rounded-xl text-xs bg-gray-50 border border-gray-300 focus:bg-white focus:border-[#324b3e] outline-none" />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Unit</label>
                                <input type="text" name="unit" x-model="editingProduct.unit"
                                       class="w-full px-3.5 py-2 rounded-xl text-xs bg-gray-50 border border-gray-300 focus:bg-white focus:border-[#324b3e] outline-none" />
                            </div>
                        </div>

                        <div class="pt-2 flex items-center justify-end gap-2">
                            <button type="button" @click="editModal = false"
                                    class="px-4 py-2 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-5 py-2 rounded-full bg-[#324b3e] hover:bg-[#23382f] text-white text-xs font-bold shadow-md">
                                Save Changes
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
            $('#products-table').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50, 100],
                order: [[1, 'asc']], // Sort by product name by default
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search any product, SKU, price...",
                    lengthMenu: "Show _MENU_ items per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ products",
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
