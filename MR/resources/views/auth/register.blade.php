<x-guest-layout>
    <div class="space-y-8">
        <div class="text-center">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-cyan-600">Join Us</p>
            <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900">إنشاء حساب جديد</h1>
            <p class="mt-2 text-sm text-slate-500">سجّل الآن وابدأ بإدارة واجهات الـ API باحتراف</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" class="!text-slate-700 !font-semibold" :value="__('Name')" />
                <x-text-input id="name"
                              class="block mt-2 w-full !rounded-xl !border-slate-200 bg-white/80 px-4 py-3 text-sm shadow-sm focus:!border-indigo-500 focus:!ring-indigo-500"
                              type="text"
                              name="name"
                              :value="old('name')"
                              required
                              autofocus
                              autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-rose-600" />
            </div>

            <!-- Email Address -->
            <div>
                <x-input-label for="email" class="!text-slate-700 !font-semibold" :value="__('Email')" />
                <x-text-input id="email"
                              class="block mt-2 w-full !rounded-xl !border-slate-200 bg-white/80 px-4 py-3 text-sm shadow-sm focus:!border-indigo-500 focus:!ring-indigo-500"
                              type="email"
                              name="email"
                              :value="old('email')"
                              required
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
                              autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-rose-600" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" class="!text-slate-700 !font-semibold" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation"
                              class="block mt-2 w-full !rounded-xl !border-slate-200 bg-white/80 px-4 py-3 text-sm shadow-sm focus:!border-indigo-500 focus:!ring-indigo-500"
                              type="password"
                              name="password_confirmation"
                              required
                              autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-rose-600" />
            </div>

            <div class="space-y-3">
                <x-primary-button class="w-full justify-center !rounded-xl !bg-gradient-to-r from-cyan-600 via-blue-600 to-indigo-600 !px-6 !py-3 !text-sm !font-semibold !normal-case shadow-lg shadow-cyan-200/60 hover:!from-cyan-700 hover:!to-indigo-700 focus:!ring-cyan-400">
                    {{ __('Register') }}
                </x-primary-button>

                <a class="flex w-full items-center justify-center rounded-xl border border-slate-200 bg-white/70 px-6 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-cyan-200 hover:text-cyan-600" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>
