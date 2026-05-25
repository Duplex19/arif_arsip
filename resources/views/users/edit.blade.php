@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="glass-card">
        <h2 class="text-xl font-bold text-theme-primary mb-6">Edit User: {{ $user->username }}</h2>

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
            @csrf @method('PUT')

            <x-glass-input label="Username" name="username" value="{{ old('username', $user->username) }}" required />

            <x-glass-input label="Email" name="email" type="email" value="{{ old('email', $user->email) }}" required />

            <x-glass-input label="Password Baru" name="password" type="password"
                placeholder="Kosongkan jika tidak diganti" />

            <x-glass-input label="Konfirmasi Password" name="password_confirmation" type="password"
                placeholder="Ulangi password baru" />

            <x-glass-input label="Bidang" name="bidang_id" type="select"
                :options="['' => 'Pilih Bidang'] + $bidangs->pluck('nama_bidang', 'id')->toArray()"
                :value="old('bidang_id', $user->bidang_id)" />

            <x-glass-input label="Role" name="role" type="select"
                :options="['user' => 'User', 'pimpinan' => 'Pimpinan', 'admin' => 'Admin']"
                :value="old('role', $user->role)" required />

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="glass-button bg-gradient-to-r from-indigo-500 to-violet-600 border-0 !text-white font-semibold shadow-lg shadow-indigo-500/30">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.users.index') }}" class="glass-button text-theme-muted hover:text-theme-primary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
