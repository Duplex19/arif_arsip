@extends('layouts.app')

@section('title', 'Kelola Jenis Arsip')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-theme-primary">Data Jenis Arsip</h2>
        <a href="{{ route('admin.jenis-arsip.create') }}" class="glass-button bg-gradient-to-r from-indigo-500 to-violet-600 border-0 !text-white shadow-lg shadow-indigo-500/30">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Jenis Arsip
        </a>
    </div>

    <div class="glass-table w-full overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="uppercase">
                    <th scope="col">No</th>
                    <th scope="col">Nama Jenis</th>
                    <th scope="col">Kode</th>
                    <th scope="col">Jumlah Arsip</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jenisArsips as $jenis)
                    <tr class="border-t border-black/5 dark:border-white/5">
                        <td class="px-4 py-3 text-theme-secondary">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 font-semibold text-theme-primary">{{ $jenis->nama_jenis }}</td>
                        <td class="px-4 py-3 text-theme-secondary font-mono text-xs">{{ $jenis->kode_jenis ?? '-' }}</td>
                        <td class="px-4 py-3 text-theme-secondary">{{ $jenis->arsips_count }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.jenis-arsip.edit', $jenis) }}" class="glass-button text-xs py-1.5 px-2.5 !text-amber-600 dark:!text-amber-400 border-amber-500/20 hover:bg-amber-500/10">
                                    Edit
                                </a>
                                @if($jenis->arsips_count === 0)
                                    <form method="POST" action="{{ route('admin.jenis-arsip.destroy', $jenis) }}" class="inline"
                                          onsubmit="return confirm('Hapus jenis arsip {{ $jenis->nama_jenis }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="glass-button text-xs py-1.5 px-2.5 !text-red-600 dark:!text-red-400 border-red-500/20 hover:bg-red-500/10">
                                            Hapus
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-theme-muted px-2">Memiliki arsip</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-theme-muted">
                            Belum ada data jenis arsip.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
