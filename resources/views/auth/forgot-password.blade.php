<x-guest-layout>
    <div class="w-full max-w-md bg-white rounded-[32px] shadow-card-premium p-8 sm:p-10 border border-white/80">
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-semibold text-gray-800 tracking-tight">
                {{ __('Reset Password') }}
            </h1>
            <p class="mt-2 text-xs text-gray-500 font-normal leading-relaxed">
                {{ __('Enter your email address and we will send you a link to reset your password.') }}
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs text-gray-400 font-normal mb-1.5 ml-3">
                    {{ __('Email') }}
                </label>
                <input id="email"
                       type="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autofocus
                       placeholder="name@example.com"
                       class="w-full px-5 py-3 rounded-full text-sm text-gray-700 bg-white border border-gray-300 focus:border-botanical-700 focus:ring-2 focus:ring-botanical-700/20 placeholder-gray-300 transition outline-none shadow-sm" />
                <x-input-error :messages="$errors->get('email')" class="mt-1.5 ml-3" />
            </div>

            <div class="pt-2">
                <button type="submit"
                        class="w-full py-3 px-6 rounded-full bg-[#344b3f] hover:bg-[#293d33] active:bg-[#203129] active:scale-[0.99] text-white font-medium text-sm tracking-wide shadow-md hover:shadow-lg transition-all duration-150">
                    {{ __('Email Password Reset Link') }}
                </button>
            </div>

            <div class="text-center pt-3">
                <a href="{{ route('login') }}"
                   class="text-xs text-gray-400 hover:text-botanical-700 transition duration-150">
                    {{ __('← Back to log in') }}
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>
