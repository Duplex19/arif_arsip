@extends('layouts.app')

@section('title', 'Data Arsip')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h2 class="text-xl font-bold text-theme-primary">Daftar Arsip</h2>
        <div class="flex flex-wrap gap-2">
            @if(auth()->user()->isAdmin() || auth()->user()->isUser())
                <a href="{{ route('arsip.create') }}" class="glass-button bg-gradient-to-r from-indigo-500 to-violet-600 border-0 !text-white hover:!text-white shadow-lg shadow-indigo-500/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Unggah Arsip
                </a>
            @endif
            <a href="{{ route('arsip.export.excel', request()->query()) }}" class="glass-button !text-emerald-600 dark:!text-emerald-400 border-emerald-500/30 hover:bg-emerald-500/10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export Excel
            </a>
            <a href="{{ route('arsip.export.pdf', request()->query()) }}" class="glass-button !text-rose-600 dark:!text-rose-400 border-rose-500/30 hover:bg-rose-500/10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                Export PDF
            </a>
        </div>
    </div>

    <form method="GET" action="{{ route('arsip.index') }}" class="glass-card">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-theme-muted mb-1">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Judul / Nomor arsip..." class="glass-input">
            </div>
            @if(auth()->user()->isAdmin() || auth()->user()->isPimpinan())
            <div>
                <label class="block text-sm font-medium text-theme-muted mb-1">Bidang</label>
                <select name="bidang_id" class="glass-input">
                    <option value="">Semua Bidang</option>
                    @foreach($bidangList as $bidang)
                        <option value="{{ $bidang->id }}" {{ request('bidang_id') == $bidang->id ? 'selected' : '' }}>
                            {{ $bidang->nama_bidang }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif
            <div>
                <label class="block text-sm font-medium text-theme-muted mb-1">Jenis Arsip</label>
                <select name="jenis_arsip_id" class="glass-input">
                    <option value="">Semua Jenis</option>
                    @foreach($jenisArsipList as $jenis)
                        <option value="{{ $jenis->id }}" {{ request('jenis_arsip_id') == $jenis->id ? 'selected' : '' }}>
                            {{ $jenis->nama_jenis }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="glass-button bg-gradient-to-r from-indigo-500 to-violet-600 border-0 !text-white flex-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Filter
                </button>
                <a href="{{ route('arsip.index') }}" class="glass-button text-theme-muted flex-1 text-center hover:text-theme-primary">
                    Reset
                </a>
            </div>
        </div>
    </form>

    <div class="glass-table w-full overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="uppercase">
                    <th scope="col" class="w-16">No</th>
                    <th scope="col">Nomor Arsip</th>
                    <th scope="col">Judul Arsip</th>
                    <th scope="col">Tanggal Dilegalkan</th>
                    <th scope="col">Jenis Arsip</th>
                    @if(auth()->user()->isAdmin() || auth()->user()->isPimpinan())
                        <th scope="col">Bidang</th>
                    @endif
                    <th scope="col">File</th>
                    <th scope="col" class="w-40">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($arsips as $arsip)
                    <tr class="border-t border-black/5 dark:border-white/5">
                        <td class="px-4 py-3 text-theme-secondary">{{ $loop->iteration + ($arsips->currentPage() - 1) * $arsips->perPage() }}</td>
                        <td class="px-4 py-3 font-semibold text-theme-primary">{{ $arsip->nomor_arsip }}</td>
                        <td class="px-4 py-3 max-w-xs truncate text-theme-secondary">{{ $arsip->judul }}</td>
                        <td class="px-4 py-3 text-theme-secondary">{{ $arsip->tgl_dilegalkan->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-indigo-500/15 text-indigo-600 dark:text-indigo-300 border border-indigo-500/20">
                                {{ $arsip->jenisArsip->nama_jenis ?? '-' }}
                            </span>
                        </td>
                        @if(auth()->user()->isAdmin() || auth()->user()->isPimpinan())
                            <td class="px-4 py-3 text-theme-secondary">{{ $arsip->bidang->nama_bidang ?? '-' }}</td>
                        @endif
                        <td class="px-4 py-3">
                            <a href="{{ route('arsip.show', $arsip) }}" target="_blank"
                               class="text-indigo-500 dark:text-indigo-400 hover:text-indigo-600 dark:hover:text-indigo-300 underline underline-offset-2 font-medium">
                                Lihat
                            </a>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                @if(!auth()->user()->isPimpinan())
                                    @if(auth()->user()->isAdmin() || $arsip->bidang_id === auth()->user()->bidang_id)
                                        <a href="{{ route('arsip.edit', $arsip) }}"
                                           class="glass-button text-xs py-1.5 px-2.5 !text-amber-600 dark:!text-amber-400 border-amber-500/20 hover:bg-amber-500/10">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('arsip.destroy', $arsip) }}" class="inline"
                                              onsubmit="return confirm('Hapus arsip ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="glass-button text-xs py-1.5 px-2.5 !text-red-600 dark:!text-red-400 border-red-500/20 hover:bg-red-500/10">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <span class="text-xs text-theme-muted">-</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->isAdmin() || auth()->user()->isPimpinan() ? 8 : 6 }}" class="px-4 py-10 text-center text-theme-muted">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Belum ada data arsip.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($arsips->hasPages())
        <div class="mt-4">
            {{ $arsips->links() }}
        </div>
    @endif
</div>
@endsection
