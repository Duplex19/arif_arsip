@extends('layouts.app')

@section('title', 'Profil')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="glass-card">
        <h2 class="text-xl font-bold text-theme-primary mb-6">Edit Profil</h2>

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf @method('PUT')

            <x-glass-input label="Username" name="username" value="{{ old('username', $user->username) }}" required />

            <x-glass-input label="Email" name="email" type="email" value="{{ old('email', $user->email) }}" required />

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="glass-button bg-gradient-to-r from-indigo-500 to-violet-600 border-0 !text-white font-semibold shadow-lg shadow-indigo-500/30">
                    Simpan Profil
                </button>
            </div>
        </form>
    </div>

    <div class="glass-card">
        <h2 class="text-xl font-bold text-theme-primary mb-6">Ubah Password</h2>

        <form method="POST" action="{{ route('profile.password') }}" class="space-y-4">
            @csrf @method('PUT')

            <x-glass-input label="Password Baru" name="password" type="password" required placeholder="Minimal 6 karakter" />

            <x-glass-input label="Konfirmasi Password" name="password_confirmation" type="password" required placeholder="Ulangi password baru" />

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="glass-button bg-gradient-to-r from-indigo-500 to-violet-600 border-0 !text-white font-semibold shadow-lg shadow-indigo-500/30">
                    Ubah Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
