@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="glass-card">
        <h2 class="text-xl font-bold text-theme-primary mb-6">Tambah User Baru</h2>

        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
            @csrf

            <x-glass-input label="Username" name="username" value="{{ old('username') }}" required placeholder="Masukkan username" />

            <x-glass-input label="Email" name="email" type="email" value="{{ old('email') }}" required placeholder="user@example.com" />

            <x-glass-input label="Password" name="password" type="password" required placeholder="Minimal 6 karakter" />

            <x-glass-input label="Konfirmasi Password" name="password_confirmation" type="password" required placeholder="Ulangi password" />

            <x-glass-input label="Bidang" name="bidang_id" type="select"
                :options="['' => 'Pilih Bidang'] + $bidangs->pluck('nama_bidang', 'id')->toArray()"
                value="{{ old('bidang_id') }}" />

            <x-glass-input label="Role" name="role" type="select"
                :options="['user' => 'User', 'pimpinan' => 'Pimpinan', 'admin' => 'Admin']"
                value="{{ old('role', 'user') }}" required />

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="glass-button bg-gradient-to-r from-indigo-500 to-violet-600 border-0 !text-white font-semibold shadow-lg shadow-indigo-500/30">
                    Simpan
                </button>
                <a href="{{ route('admin.users.index') }}" class="glass-button text-theme-muted hover:text-theme-primary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
