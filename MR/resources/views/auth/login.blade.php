<x-guest-layout>
    <div class="space-y-8">
        <div class="text-center">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-indigo-500">Welcome Back</p>
            <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900">تسجيل الدخول</h1>
            <p class="mt-2 text-sm text-slate-500">أدخل بياناتك للمتابعة إلى لوحة التحكم</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" class="!text-slate-700 !font-semibold" :value="__('Email')" />
                <x-text-input id="email"
                              class="block mt-2 w-full !rounded-xl !border-slate-200 bg-white/80 px-4 py-3 text-sm shadow-sm focus:!border-indigo-500 focus:!ring-indigo-500"
                              type="email"
                              name="email"
                              :value="old('email')"
                              required
                              autofocus
                              autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-rose-600" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" class="!text-slate-700 !font-semibold" :value="__('Password')" />
                <x-text-input id="password"
                              class="block mt-2 w-full !rounded-xl !border-slate-200 bg-white/80 px-4 py-3 text-sm shadow-sm focus:!border-indigo-500 focus:!ring-indigo-500"
                              type="password"
                              name="password"
                              required
                              autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-rose-600" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center gap-2 text-sm text-slate-600">
                    <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    {{ __('Remember me') }}
                </label>
                @if (Route::has('password.request'))
                    <a class="text-sm font-semibold text-indigo-600 transition hover:text-indigo-700" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <div class="space-y-3">
                <x-primary-button class="w-full justify-center !rounded-xl !bg-gradient-to-r from-indigo-600 via-blue-600 to-cyan-500 !px-6 !py-3 !text-sm !font-semibold !normal-case shadow-lg shadow-indigo-200/60 hover:!from-indigo-700 hover:!to-cyan-600 focus:!ring-indigo-400">
                    {{ __('Log in') }}
                </x-primary-button>

                @if (Route::has('register'))
                    <a class="flex w-full items-center justify-center rounded-xl border border-slate-200 bg-white/70 px-6 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:text-indigo-600" href="{{ route('register') }}">
                        {{ __('Register') }}
                    </a>
                @endif
            </div>
        </form>
    </div>
</x-guest-layout>
