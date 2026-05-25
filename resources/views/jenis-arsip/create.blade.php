@extends('layouts.app')

@section('title', 'Tambah Jenis Arsip')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="glass-card">
        <h2 class="text-xl font-bold text-theme-primary mb-6">Tambah Jenis Arsip Baru</h2>

        <form method="POST" action="{{ route('admin.jenis-arsip.store') }}" class="space-y-4">
            @csrf

            <x-glass-input label="Nama Jenis Arsip" name="nama_jenis" value="{{ old('nama_jenis') }}" required
                placeholder="Contoh: Surat Keputusan (SK)" />

            <x-glass-input label="Kode Jenis (opsional)" name="kode_jenis" value="{{ old('kode_jenis') }}"
                placeholder="Contoh: SK" />

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="glass-button bg-gradient-to-r from-indigo-500 to-violet-600 border-0 !text-white font-semibold shadow-lg shadow-indigo-500/30">
                    Simpan
                </button>
                <a href="{{ route('admin.jenis-arsip.index') }}" class="glass-button text-theme-muted hover:text-theme-primary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
