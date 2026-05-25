@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12 relative">
    {{-- Decorative elements --}}
    <div class="absolute top-20 left-20 w-72 h-72 bg-indigo-500/20 rounded-full blur-[100px]"></div>
    <div class="absolute bottom-20 right-20 w-96 h-96 bg-violet-500/20 rounded-full blur-[120px]"></div>

    <div class="w-full max-w-md relative z-10">
        <div class="text-center mb-8">
            <div class="inline-flex w-24 h-24 rounded-2xl items-center justify-center mb-5 shadow-2xl shadow-indigo-500/20 ring-2 ring-white/10 overflow-hidden">
                <img src="{{ asset('images/logo-way-kanan.png') }}" alt="Lambang Way Kanan"
                     class="w-full h-full object-cover">
            </div>
            <h1 class="text-3xl font-bold text-theme-primary mb-1">E-Arsip DKP</h1>
            <p class="text-theme-muted">Dinas Ketahanan Pangan Kab. Way Kanan</p>
        </div>

        <form method="POST" action="{{ route('login.store') }}" class="glass-card space-y-5">
            @csrf

            <div>
                <label for="username" class="block text-sm font-medium text-theme-secondary mb-1.5">Username</label>
                <input type="text" name="username" id="username" value="{{ old('username') }}"
                       class="glass-input @error('username') !border-red-400/50 @enderror"
                       placeholder="Masukkan username" required autofocus>
                @error('username')
                    <p class="mt-1.5 text-sm text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-theme-secondary mb-1.5">Password</label>
                <input type="password" name="password" id="password"
                       class="glass-input @error('password') !border-red-400/50 @enderror"
                       placeholder="Masukkan password" required>
                @error('password')
                    <p class="mt-1.5 text-sm text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-600 text-white font-semibold shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
                Masuk
            </button>
        </form>

        <p class="text-center mt-6 text-xs text-theme-muted">
            &copy; {{ date('Y') }} Dinas Ketahanan Pangan Kab. Way Kanan
        </p>
    </div>
</div>
@endsection
