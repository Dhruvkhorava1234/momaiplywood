<x-guest-layout>
    <!-- Desktop & Tablet Split Card Design -->
    <div class="hidden md:flex w-full max-w-4xl bg-white rounded-[36px] shadow-card-premium overflow-hidden min-h-[580px] border border-white/60">
        
        <!-- Left Side: Login Form -->
        <div class="w-1/2 p-10 lg:p-14 flex flex-col justify-center bg-white z-10">
            <!-- Header -->
            <div class="mb-7 text-center">
                <h1 class="text-2xl lg:text-3xl font-semibold text-gray-800 tracking-tight">
                    {{ __('Log in') }}
                </h1>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Login / Email Input -->
                <div>
                    <label for="email" class="block text-xs text-gray-400 font-normal mb-1.5 ml-3">
                        {{ __('Login, email or phone number') }}
                    </label>
                    <div class="relative">
                        <input id="email"
                               type="text"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               autofocus
                               autocomplete="username"
                               placeholder="name@example.com"
                               class="w-full px-5 py-3 rounded-full text-sm text-gray-700 bg-white border border-gray-300 focus:border-botanical-700 focus:ring-2 focus:ring-botanical-700/20 placeholder-gray-300 transition outline-none shadow-sm" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1.5 ml-3" />
                </div>

                <!-- Password Input -->
                <div x-data="{ show: false }">
                    <label for="password" class="block text-xs text-gray-400 font-normal mb-1.5 ml-3">
                        {{ __('Password') }}
                    </label>
                    <div class="relative">
                        <input id="password"
                               :type="show ? 'text' : 'password'"
                               name="password"
                               required
                               autocomplete="current-password"
                               placeholder="••••••••"
                               class="w-full px-5 py-3 pr-12 rounded-full text-sm text-gray-700 bg-white border border-gray-300 focus:border-botanical-700 focus:ring-2 focus:ring-botanical-700/20 placeholder-gray-300 transition outline-none shadow-sm" />
                        
                        <!-- Toggle Password Visibility -->
                        <button type="button"
                                @click="show = !show"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition p-1 focus:outline-none"
                                aria-label="Toggle password visibility">
                            <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1.5 ml-3" />
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit"
                            class="w-full py-3 px-6 rounded-full bg-[#344b3f] hover:bg-[#293d33] active:bg-[#203129] active:scale-[0.99] text-white font-medium text-sm tracking-wide shadow-md hover:shadow-lg transition-all duration-150">
                        {{ __('Log in') }}
                    </button>
                </div>

                <!-- Divider -->
                <div class="relative flex items-center justify-center pt-2">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <!-- Forgot Password Link -->
                <div class="text-center pt-2">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-xs text-gray-400 hover:text-botanical-700 transition duration-150">
                            {{ __('forgot login or password?') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Right Side: Organic Papercut Wave & Jungle Foliage -->
        <div class="w-1/2 relative bg-[#13251c] overflow-hidden select-none">
            <img src="{{ asset('images/papercut-leaves.jpg') }}"
                 alt="Botanical papercut foliage"
                 class="w-full h-full object-cover object-left" />
            
            <!-- Subtle lighting gradient overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-white/10 pointer-events-none"></div>
        </div>
    </div>

    <!-- Mobile View: Floating Frosted Glassmorphism Card -->
    <div class="block md:hidden w-full max-w-sm">
        <div class="glass-card-mobile rounded-[32px] p-7 sm:p-9">
            
            <!-- Mobile Header -->
            <div class="mb-6 text-center">
                <h1 class="text-2xl font-semibold text-gray-800 tracking-tight">
                    {{ __('Log in') }}
                </h1>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Mobile Login / Email Input -->
                <div>
                    <label for="mobile_email" class="block text-xs text-gray-500 font-normal mb-1.5 ml-3">
                        {{ __('Login, email or phone number') }}
                    </label>
                    <div class="relative">
                        <input id="mobile_email"
                               type="text"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               autofocus
                               autocomplete="username"
                               placeholder="name@example.com"
                               class="w-full px-5 py-3 rounded-full text-sm text-gray-800 bg-white/80 backdrop-blur-sm border border-gray-300/80 focus:border-botanical-700 focus:bg-white focus:ring-2 focus:ring-botanical-700/25 placeholder-gray-400 transition outline-none shadow-xs" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1.5 ml-3" />
                </div>

                <!-- Mobile Password Input -->
                <div x-data="{ show: false }">
                    <label for="mobile_password" class="block text-xs text-gray-500 font-normal mb-1.5 ml-3">
                        {{ __('Password') }}
                    </label>
                    <div class="relative">
                        <input id="mobile_password"
                               :type="show ? 'text' : 'password'"
                               name="password"
                               required
                               autocomplete="current-password"
                               placeholder="••••••••"
                               class="w-full px-5 py-3 pr-12 rounded-full text-sm text-gray-800 bg-white/80 backdrop-blur-sm border border-gray-300/80 focus:border-botanical-700 focus:bg-white focus:ring-2 focus:ring-botanical-700/25 placeholder-gray-400 transition outline-none shadow-xs" />
                        
                        <!-- Toggle Password Visibility -->
                        <button type="button"
                                @click="show = !show"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition p-1 focus:outline-none"
                                aria-label="Toggle password visibility">
                            <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1.5 ml-3" />
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit"
                            class="w-full py-3 px-6 rounded-full bg-[#344b3f] hover:bg-[#293d33] active:bg-[#203129] active:scale-[0.99] text-white font-medium text-sm tracking-wide shadow-md hover:shadow-lg transition-all duration-150">
                        {{ __('Log in') }}
                    </button>
                </div>



                <!-- Forgot Password Link -->
                <div class="text-center pt-2">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-xs text-gray-500 hover:text-botanical-800 transition duration-150">
                            {{ __('forgot login or password?') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
