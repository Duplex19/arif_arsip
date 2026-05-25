@extends('layouts.app')

@section('title', 'Edit Arsip')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="glass-card">
        <h2 class="text-xl font-bold text-theme-primary mb-6">Edit Arsip</h2>

        <form method="POST" action="{{ route('arsip.update', $arsip) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf @method('PUT')

            <x-glass-input label="Nomor Arsip" name="nomor_arsip" :value="old('nomor_arsip', $arsip->nomor_arsip)" required
                placeholder="Contoh: 800/SK/IV/2025" />

            <x-glass-input label="Tanggal Dilegalkan" name="tgl_dilegalkan" type="date"
                :value="old('tgl_dilegalkan', $arsip->tgl_dilegalkan->format('Y-m-d'))" required />

            <x-glass-input label="Judul Arsip" name="judul" :value="old('judul', $arsip->judul)" required
                placeholder="Masukkan judul arsip" />

            <x-glass-input label="Jenis Arsip" name="jenis_arsip_id" type="select"
                :options="$jenisArsipList->pluck('nama_jenis', 'id')->toArray()"
                :value="old('jenis_arsip_id', $arsip->jenis_arsip_id)" required placeholder="Pilih jenis arsip" />

            @if(auth()->user()->isAdmin())
                <x-glass-input label="Bidang" name="bidang_id" type="select"
                    :options="\App\Models\Bidang::orderBy('nama_bidang')->get()->pluck('nama_bidang', 'id')->toArray()"
                    :value="old('bidang_id', $arsip->bidang_id)" required placeholder="Pilih bidang" />
            @endif

            <div class="mb-4">
                <label for="file" class="block text-sm font-medium text-theme-secondary mb-1.5">
                    File Dokumen <span class="text-theme-muted text-xs">(biarkan kosong jika tidak diganti)</span>
                </label>
                <input type="file" name="file" id="file" accept=".pdf,.jpg,.jpeg,.png"
                       class="glass-input file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-indigo-500 file:to-violet-600 file:text-white hover:file:opacity-90">
                <p class="mt-1.5 text-xs text-theme-muted">Maksimal 5MB. Format: PDF, JPG, PNG</p>
                @error('file')
                    <p class="mt-1.5 text-sm text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="glass-button bg-gradient-to-r from-indigo-500 to-violet-600 border-0 !text-white font-semibold shadow-lg shadow-indigo-500/30">
                    Simpan Perubahan
                </button>
                <a href="{{ route('arsip.index') }}" class="glass-button text-theme-muted hover:text-theme-primary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
