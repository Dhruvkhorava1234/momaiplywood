<x-guest-layout>
    <!-- Desktop Split Card Design -->
    <div class="hidden md:flex w-full max-w-4xl bg-white rounded-[36px] shadow-card-premium overflow-hidden min-h-[620px] border border-white/60">
        
        <!-- Left Side: Register Form -->
        <div class="w-1/2 p-10 lg:p-12 flex flex-col justify-center bg-white z-10">
            <div class="mb-6 text-center">
                <h1 class="text-2xl lg:text-3xl font-semibold text-gray-800 tracking-tight">
                    {{ __('Create Account') }}
                </h1>
                <p class="mt-1 text-xs text-gray-400">
                    {{ __('Join us today and get started') }}
                </p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-3.5">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs text-gray-400 font-normal mb-1 ml-3">
                        {{ __('Name') }}
                    </label>
                    <input id="name"
                           type="text"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           autofocus
                           autocomplete="name"
                           placeholder="John Doe"
                           class="w-full px-5 py-2.5 rounded-full text-sm text-gray-700 bg-white border border-gray-300 focus:border-botanical-700 focus:ring-2 focus:ring-botanical-700/20 placeholder-gray-300 transition outline-none shadow-sm" />
                    <x-input-error :messages="$errors->get('name')" class="mt-1 ml-3" />
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs text-gray-400 font-normal mb-1 ml-3">
                        {{ __('Email') }}
                    </label>
                    <input id="email"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autocomplete="username"
                           placeholder="name@example.com"
                           class="w-full px-5 py-2.5 rounded-full text-sm text-gray-700 bg-white border border-gray-300 focus:border-botanical-700 focus:ring-2 focus:ring-botanical-700/20 placeholder-gray-300 transition outline-none shadow-sm" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1 ml-3" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs text-gray-400 font-normal mb-1 ml-3">
                        {{ __('Password') }}
                    </label>
                    <input id="password"
                           type="password"
                           name="password"
                           required
                           autocomplete="new-password"
                           placeholder="••••••••"
                           class="w-full px-5 py-2.5 rounded-full text-sm text-gray-700 bg-white border border-gray-300 focus:border-botanical-700 focus:ring-2 focus:ring-botanical-700/20 placeholder-gray-300 transition outline-none shadow-sm" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1 ml-3" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs text-gray-400 font-normal mb-1 ml-3">
                        {{ __('Confirm Password') }}
                    </label>
                    <input id="password_confirmation"
                           type="password"
                           name="password_confirmation"
                           required
                           autocomplete="new-password"
                           placeholder="••••••••"
                           class="w-full px-5 py-2.5 rounded-full text-sm text-gray-700 bg-white border border-gray-300 focus:border-botanical-700 focus:ring-2 focus:ring-botanical-700/20 placeholder-gray-300 transition outline-none shadow-sm" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 ml-3" />
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit"
                            class="w-full py-3 px-6 rounded-full bg-[#344b3f] hover:bg-[#293d33] active:bg-[#203129] active:scale-[0.99] text-white font-medium text-sm tracking-wide shadow-md hover:shadow-lg transition-all duration-150">
                        {{ __('Register') }}
                    </button>
                </div>

                <div class="text-center pt-2">
                    <a href="{{ route('login') }}"
                       class="text-xs text-gray-400 hover:text-botanical-700 transition duration-150">
                        {{ __('Already registered? Log in') }}
                    </a>
                </div>
            </form>
        </div>

        <!-- Right Side: Foliage Artwork -->
        <div class="w-1/2 relative bg-[#13251c] overflow-hidden select-none">
            <img src="{{ asset('images/papercut-leaves.jpg') }}"
                 alt="Botanical papercut foliage"
                 class="w-full h-full object-cover object-left" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-white/10 pointer-events-none"></div>
        </div>
    </div>

    <!-- Mobile View: Frosted Card -->
    <div class="block md:hidden w-full max-w-sm">
        <div class="glass-card-mobile rounded-[32px] p-7 sm:p-9">
            <div class="mb-5 text-center">
                <h1 class="text-2xl font-semibold text-gray-800 tracking-tight">
                    {{ __('Create Account') }}
                </h1>
                <p class="mt-1 text-xs text-gray-500">
                    {{ __('Join us today and get started') }}
                </p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-3.5">
                @csrf

                <div>
                    <label for="mobile_name" class="block text-xs text-gray-500 font-normal mb-1 ml-3">
                        {{ __('Name') }}
                    </label>
                    <input id="mobile_name"
                           type="text"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           autofocus
                           autocomplete="name"
                           placeholder="John Doe"
                           class="w-full px-5 py-2.5 rounded-full text-sm text-gray-800 bg-white/80 backdrop-blur-sm border border-gray-300/80 focus:border-botanical-700 focus:bg-white focus:ring-2 focus:ring-botanical-700/25 placeholder-gray-400 transition outline-none shadow-xs" />
                    <x-input-error :messages="$errors->get('name')" class="mt-1 ml-3" />
                </div>

                <div>
                    <label for="mobile_reg_email" class="block text-xs text-gray-500 font-normal mb-1 ml-3">
                        {{ __('Email') }}
                    </label>
                    <input id="mobile_reg_email"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autocomplete="username"
                           placeholder="name@example.com"
                           class="w-full px-5 py-2.5 rounded-full text-sm text-gray-800 bg-white/80 backdrop-blur-sm border border-gray-300/80 focus:border-botanical-700 focus:bg-white focus:ring-2 focus:ring-botanical-700/25 placeholder-gray-400 transition outline-none shadow-xs" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1 ml-3" />
                </div>

                <div>
                    <label for="mobile_reg_password" class="block text-xs text-gray-500 font-normal mb-1 ml-3">
                        {{ __('Password') }}
                    </label>
                    <input id="mobile_reg_password"
                           type="password"
                           name="password"
                           required
                           autocomplete="new-password"
                           placeholder="••••••••"
                           class="w-full px-5 py-2.5 rounded-full text-sm text-gray-800 bg-white/80 backdrop-blur-sm border border-gray-300/80 focus:border-botanical-700 focus:bg-white focus:ring-2 focus:ring-botanical-700/25 placeholder-gray-400 transition outline-none shadow-xs" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1 ml-3" />
                </div>

                <div>
                    <label for="mobile_reg_password_confirmation" class="block text-xs text-gray-500 font-normal mb-1 ml-3">
                        {{ __('Confirm Password') }}
                    </label>
                    <input id="mobile_reg_password_confirmation"
                           type="password"
                           name="password_confirmation"
                           required
                           autocomplete="new-password"
                           placeholder="••••••••"
                           class="w-full px-5 py-2.5 rounded-full text-sm text-gray-800 bg-white/80 backdrop-blur-sm border border-gray-300/80 focus:border-botanical-700 focus:bg-white focus:ring-2 focus:ring-botanical-700/25 placeholder-gray-400 transition outline-none shadow-xs" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 ml-3" />
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full py-3 px-6 rounded-full bg-[#344b3f] hover:bg-[#293d33] active:bg-[#203129] active:scale-[0.99] text-white font-medium text-sm tracking-wide shadow-md hover:shadow-lg transition-all duration-150">
                        {{ __('Register') }}
                    </button>
                </div>

                <div class="text-center pt-2">
                    <a href="{{ route('login') }}"
                       class="text-xs text-gray-500 hover:text-botanical-800 transition duration-150">
                        {{ __('Already registered? Log in') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
