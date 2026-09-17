<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'MOMAI PLYWOOD') }} - Inventory Management</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Bootstrap 5 CSS & JS for 100% Mobile Responsiveness -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- jQuery & DataTables -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            [x-cloak] { display: none !important; }

            /* Fix Bootstrap button and link defaults overriding botanical styles */
            a { text-decoration: none; }

            /* Mobile Sidebar Transitions */
            .sidebar-backdrop {
                background-color: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(2px);
            }

            @media (max-width: 1023px) {
                .mobile-sidebar {
                    position: fixed !important;
                    top: 0;
                    bottom: 0;
                    left: 0;
                    z-index: 1050;
                    transform: translateX(-100%);
                    transition: transform 0.25s ease-in-out;
                    width: 288px !important;
                }
                .mobile-sidebar.open {
                    transform: translateX(0);
                }
                .main-viewport {
                    height: 100vh;
                    overflow-y: auto;
                    -webkit-overflow-scrolling: touch;
                }
            }

            /* Custom DataTables Styling for Botanical Theme */
            .dataTables_wrapper {
                padding: 1rem;
                font-size: 0.8125rem;
                color: #374151;
            }
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                margin-bottom: 1rem;
            }
            .dataTables_wrapper .dataTables_length select {
                padding: 0.35rem 2rem 0.35rem 0.75rem;
                border-radius: 0.75rem;
                border: 1px solid #d1d5db;
                font-size: 0.75rem;
                background-color: #f9fafb;
                outline: none;
            }
            .dataTables_wrapper .dataTables_filter input {
                padding: 0.4rem 0.85rem;
                border-radius: 9999px;
                border: 1px solid #d1d5db;
                font-size: 0.75rem;
                margin-left: 0.5rem;
                background-color: #ffffff;
                outline: none;
                transition: all 0.15s;
            }
            .dataTables_wrapper .dataTables_filter input:focus {
                border-color: #324b3e;
                box-shadow: 0 0 0 3px rgba(50, 75, 62, 0.15);
            }
            table.dataTable {
                border-collapse: separate !important;
                border-spacing: 0;
                width: 100% !important;
                margin-top: 0.5rem !important;
                margin-bottom: 0.75rem !important;
                border: none !important;
            }
            table.dataTable thead th {
                background-color: #f8fafc !important;
                color: #4b5563 !important;
                font-size: 0.6875rem !important;
                font-weight: 700 !important;
                text-transform: uppercase !important;
                letter-spacing: 0.05em !important;
                border-bottom: 1px solid #e2e8f0 !important;
                border-top: none !important;
                padding: 0.875rem 1rem !important;
            }
            table.dataTable tbody td {
                padding: 0.875rem 1rem !important;
                border-bottom: 1px solid #f1f5f9 !important;
                vertical-align: middle !important;
            }
            table.dataTable tbody tr:hover td {
                background-color: #f8fafc !important;
            }
            table.dataTable.no-footer {
                border-bottom: 1px solid #e2e8f0 !important;
            }
            .dataTables_wrapper .dataTables_info {
                padding-top: 1rem;
                font-size: 0.75rem;
                color: #6b7280;
            }
            .dataTables_wrapper .dataTables_paginate {
                padding-top: 0.75rem;
            }
            .dataTables_wrapper .dataTables_paginate .paginate_button {
                padding: 0.35rem 0.75rem !important;
                border-radius: 0.5rem !important;
                margin: 0 2px !important;
                border: 1px solid transparent !important;
                font-size: 0.75rem !important;
                font-weight: 600 !important;
                color: #4b5563 !important;
            }
            .dataTables_wrapper .dataTables_paginate .paginate_button.current,
            .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
                background: #324b3e !important;
                color: #ffffff !important;
                border-color: #324b3e !important;
            }
            .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
                background: #e2e8f0 !important;
                color: #1f2937 !important;
            }

            @media print {
                html, body {
                    height: auto !important;
                    overflow: visible !important;
                    background: #ffffff !important;
                }
                body * {
                    visibility: hidden;
                }
                #printable-slip, #printable-slip *,
                #printable-due-slip, #printable-due-slip * {
                    visibility: visible;
                }
                #printable-slip {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100% !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    box-shadow: none !important;
                    border: none !important;
                }
                #printable-due-slip {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100% !important;
                    max-width: 420px !important;
                    margin: 0 auto !important;
                    padding: 0 !important;
                    box-shadow: none !important;
                }
                @page {
                    size: auto;
                    margin: 8mm;
                }
            }
        </style>
    </head>
    <body x-data="{ mobileOpen: false }" class="font-sans antialiased text-gray-800 h-screen w-screen overflow-hidden bg-[#edf4f7] flex">
        
        <!-- Mobile Sidebar Backdrop Overlay -->
        <div x-show="mobileOpen"
             x-cloak
             @click="mobileOpen = false"
             class="sidebar-backdrop fixed inset-0 z-40 lg:hidden"></div>

        <!-- Left Full-Height Dark Sidebar (Palette: #23382f, #293b32) -->
        <aside :class="mobileOpen ? 'open' : ''"
               class="mobile-sidebar w-72 bg-[#23382f] text-gray-200 flex flex-col justify-between shrink-0 p-5 border-r border-[#1a2d25] h-full overflow-y-auto select-none z-50 lg:static lg:translate-x-0">
            <div>
                <!-- Brand Title (Cross Removed as requested) -->
                <div class="flex items-center justify-between pb-5 border-b border-[#2e473d]">
                    <div class="flex items-center gap-2.5">
                        <img src="{{ asset('images/logo.svg') }}" alt="MOMAI PLYWOOD Logo" class="w-9 h-9 rounded-xl shadow-sm object-cover" />
                        <div>
                            <span class="font-bold text-sm text-white tracking-wide block">MOMAI PLYWOOD</span>
                            <span class="text-[10px] text-emerald-300 font-medium">Inventory POS</span>
                        </div>
                    </div>
                    <span class="text-[10px] font-mono px-2 py-0.5 rounded-full bg-[#1b2b24] text-emerald-400 font-bold border border-[#2e473d]">
                        {{ date('Y') }}
                    </span>
                </div>

                <!-- Admin Profile: Premium Minimal Card -->
                <div class="relative overflow-hidden p-3.5 my-3 rounded-2xl bg-gradient-to-b from-[#2a4539] to-[#1c3027] border border-[#3b5a4b]/60 shadow-md">
                    <div class="flex items-center gap-3">
                        <div class="relative shrink-0">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-[#3b5e4d] via-[#4d7863] to-[#72a38a] flex items-center justify-center text-white font-bold text-sm shadow-inner ring-2 ring-emerald-400/20">
                                {{ strtoupper(substr(Auth::user()->name ?? 'N', 0, 1)) }}
                            </div>
                            <!-- Glowing Online Status Dot -->
                            <span class="absolute -bottom-0.5 -right-0.5 flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500 border-2 border-[#23382f]"></span>
                            </span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-1.5">
                                <h2 class="font-bold text-xs text-white tracking-wide truncate">
                                    {{ Auth::user()->name ?? 'Nirmal Kumar P' }}
                                </h2>
                            </div>
                            <p class="text-[11px] text-emerald-200/80 font-normal truncate mt-0.5">
                                {{ Auth::user()->email ?? 'admin@momai.com' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="mt-3 space-y-1.5 font-medium text-xs">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('dashboard') ? 'bg-[#edf4f7] text-[#23382f] font-bold shadow-md' : 'text-gray-300 hover:bg-[#2e473d] hover:text-white' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('dashboard') ? 'text-[#23382f]' : 'text-emerald-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        <span>{{ __('Dashboard') }}</span>
                    </a>

                    <!-- Make Bill (POS) -->
                    <a href="{{ route('bills.create') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('bills.create') ? 'bg-[#edf4f7] text-[#23382f] font-bold shadow-md' : 'text-gray-300 hover:bg-[#2e473d] hover:text-white' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('bills.create') ? 'text-[#23382f]' : 'text-emerald-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <span>{{ __('Make Bill (POS)') }}</span>
                    </a>

                    <!-- Orders & Bill History -->
                    <a href="{{ route('bills.index') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('bills.index') || request()->routeIs('bills.slip') ? 'bg-[#edf4f7] text-[#23382f] font-bold shadow-md' : 'text-gray-300 hover:bg-[#2e473d] hover:text-white' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('bills.index') ? 'text-[#23382f]' : 'text-emerald-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span>{{ __('Bills & History') }}</span>
                    </a>

                    <!-- Inventory & Products -->
                    <a href="{{ route('products.index') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('products.index') ? 'bg-[#edf4f7] text-[#23382f] font-bold shadow-md' : 'text-gray-300 hover:bg-[#2e473d] hover:text-white' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('products.index') ? 'text-[#23382f]' : 'text-emerald-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span>{{ __('Inventory & Stock') }}</span>
                    </a>

                    <!-- Reporting -->
                    <a href="{{ route('reports.index') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('reports.index') ? 'bg-[#edf4f7] text-[#23382f] font-bold shadow-md' : 'text-gray-300 hover:bg-[#2e473d] hover:text-white' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('reports.index') ? 'text-[#23382f]' : 'text-emerald-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>{{ __('Reporting') }}</span>
                    </a>

                    <!-- Settings -->
                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('profile.edit') ? 'bg-[#edf4f7] text-[#23382f] font-bold shadow-md' : 'text-gray-300 hover:bg-[#2e473d] hover:text-white' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('profile.edit') ? 'text-[#23382f]' : 'text-emerald-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>{{ __('Settings') }}</span>
                    </a>
                </nav>
            </div>

            <!-- Bottom Logout Button -->
            <div class="pt-4 border-t border-[#2e473d]">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center gap-3 px-3.5 py-2 text-xs text-gray-300 hover:text-white hover:bg-rose-900/30 rounded-xl transition duration-150 font-medium">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>{{ __('Logout') }}</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Right Side Full-Width Content Area -->
        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden bg-[#edf4f7] main-viewport">
            
            <!-- Top Navbar Bar (Full Screen & Responsive) -->
            <header class="px-4 sm:px-6 lg:px-8 py-3 bg-white border-b border-gray-200/80 shrink-0 flex items-center justify-between gap-3 z-10 shadow-2xs">
                <!-- Left Title & Mobile Hamburger Button -->
                <div class="flex items-center gap-3 min-w-0">
                    <!-- Mobile Hamburger Button -->
                    <button type="button"
                            @click="mobileOpen = true"
                            class="lg:hidden p-2 rounded-xl text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none transition shrink-0"
                            aria-label="Open Navigation">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div class="truncate">
                        <h2 class="text-base sm:text-lg lg:text-xl font-bold text-gray-800 tracking-tight truncate">
                            {{ __('Welcome') }} {{ explode(' ', Auth::user()->name ?? 'Nirmal')[0] }} !
                        </h2>
                        <p class="text-[11px] text-gray-500 font-medium hidden sm:block lg:hidden truncate">Inventory & POS</p>
                    </div>
                    <span class="hidden md:inline-block text-xs text-gray-400 font-medium">|</span>
                    <span class="hidden md:inline-block text-xs font-semibold text-gray-600 uppercase tracking-wider">Inventory & POS Dashboard</span>
                </div>

                <!-- Right Search & Quick Actions -->
                <div class="flex items-center gap-3">
                    <!-- Global AJAX Autosearch -->
                    <div x-data="globalAutoSearch()" class="relative hidden sm:block w-72" @click.away="isOpen = false">
                        <form action="{{ route('products.index') }}" method="GET" class="relative">
                            <input type="text"
                                   name="search"
                                   x-model="query"
                                   @input="onInput()"
                                   @focus="if (results.length > 0) isOpen = true"
                                   placeholder="Search products, SKU, company..."
                                   autocomplete="off"
                                   class="w-full pl-4 pr-9 py-1.5 rounded-full text-xs bg-gray-50 border border-gray-300 focus:bg-white focus:border-[#324b3e] focus:ring-2 focus:ring-[#324b3e]/20 outline-none transition" />
                            <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </form>

                        <!-- Autosearch Dropdown Results -->
                        <div x-show="isOpen"
                             x-cloak
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="absolute left-0 right-0 top-full mt-2 bg-white rounded-2xl shadow-xl border border-gray-200/90 py-2 z-50 max-h-80 overflow-y-auto">
                            
                            <!-- Loading indicator -->
                            <div x-show="loading" class="px-4 py-3 text-center text-xs text-gray-400 flex items-center justify-center gap-2">
                                <svg class="animate-spin h-3.5 w-3.5 text-[#324b3e]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Searching...</span>
                            </div>

                            <!-- Results list -->
                            <template x-for="item in results" :key="item.id">
                                <a :href="'{{ route('products.index') }}?search=' + encodeURIComponent(item.name)"
                                   class="block px-4 py-2.5 hover:bg-gray-50 border-b border-gray-100 last:border-0 transition">
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="font-bold text-xs text-gray-800" x-text="item.name"></div>
                                        <span class="font-mono text-xs font-bold text-[#324b3e]" x-text="'₹' + Number(item.selling_price).toLocaleString('en-IN')"></span>
                                    </div>
                                    <div class="flex items-center gap-2 mt-0.5 text-[11px] text-gray-400 font-mono">
                                        <span class="font-semibold text-gray-600" x-text="item.sku"></span>
                                        <template x-if="item.company_name">
                                            <span>• <span class="text-indigo-600 font-sans" x-text="item.company_name"></span></span>
                                        </template>
                                        <span>• <span class="text-gray-500 font-sans" x-text="item.category"></span></span>
                                        <span>• <span :class="item.stock_quantity <= 0 ? 'text-rose-500 font-bold' : 'text-emerald-600 font-bold'" x-text="item.stock_quantity + ' ' + item.unit"></span></span>
                                    </div>
                                </a>
                            </template>

                            <!-- No results found -->
                            <div x-show="!loading && results.length === 0 && query.trim().length >= 2"
                                 class="px-4 py-3 text-center text-xs text-gray-400">
                                No matching products found for "<span class="font-bold text-gray-600" x-text="query"></span>"
                            </div>
                        </div>
                    </div>

                    <!-- Status Pill -->
                    <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-200 rounded-full px-3 py-1 text-xs text-emerald-800 shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="font-semibold text-[11px]">System Online</span>
                    </div>
                </div>
            </header>

            <!-- Alerts -->
            @if (session('success'))
                <div class="mx-6 lg:mx-8 mt-4 p-3 rounded-xl bg-emerald-100/90 border border-emerald-300 text-emerald-900 text-xs flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2 font-medium">
                        <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Main Content Container (Full Width & Height Scrolling, 100% Mobile Responsive) -->
            <main class="flex-1 overflow-y-auto p-3 sm:p-5 lg:p-8 space-y-4 sm:space-y-6">
                {{ $slot }}
            </main>
        </div>

        <!-- Global Autosearch Logic (Alpine + AJAX Fetch) -->
        <script>
            function globalAutoSearch() {
                return {
                    query: '',
                    results: [],
                    loading: false,
                    isOpen: false,
                    debounceTimer: null,

                    onInput() {
                        clearTimeout(this.debounceTimer);
                        const cleanQuery = this.query.trim();

                        if (cleanQuery.length < 2) {
                            this.results = [];
                            this.isOpen = false;
                            this.loading = false;
                            return;
                        }

                        this.loading = true;
                        this.isOpen = true;

                        // Debounce AJAX request by 200ms
                        this.debounceTimer = setTimeout(() => {
                            fetch('{{ route('products.search') }}?q=' + encodeURIComponent(cleanQuery), {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                this.results = data;
                                this.loading = false;
                                this.isOpen = true;
                            })
                            .catch(error => {
                                console.error('AutoSearch error:', error);
                                this.loading = false;
                            });
                        }, 200);
                    }
                }
            }
        </script>
    </body>
</html>
