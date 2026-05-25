@extends('layouts.app')

@section('title', 'Edit Bidang')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="glass-card">
        <h2 class="text-xl font-bold text-theme-primary mb-6">Edit Bidang</h2>

        <form method="POST" action="{{ route('admin.bidang.update', $bidang) }}" class="space-y-4">
            @csrf @method('PUT')

            <x-glass-input label="Nama Bidang" name="nama_bidang" value="{{ old('nama_bidang', $bidang->nama_bidang) }}" required />

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="glass-button bg-gradient-to-r from-indigo-500 to-violet-600 border-0 !text-white font-semibold shadow-lg shadow-indigo-500/30">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.bidang.index') }}" class="glass-button text-theme-muted hover:text-theme-primary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
