@extends('layouts.app')

@section('title', 'Kelola User')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-theme-primary">Data User</h2>
        <a href="{{ route('admin.users.create') }}" class="glass-button bg-gradient-to-r from-indigo-500 to-violet-600 border-0 !text-white shadow-lg shadow-indigo-500/30">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
            </svg>
            Tambah User
        </a>
    </div>

    <div class="glass-table w-full overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="uppercase">
                    <th scope="col">No</th>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Bidang</th>
                    <th scope="col">Role</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="border-t border-black/5 dark:border-white/5">
                        <td class="px-4 py-3 text-theme-secondary">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 font-semibold text-theme-primary">{{ $user->username }}</td>
                        <td class="px-4 py-3 text-theme-secondary">{{ $user->email }}</td>
                        <td class="px-4 py-3 text-theme-secondary">{{ $user->bidang->nama_bidang ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @php
                                $roleColors = ['admin' => 'bg-indigo-500/15 text-indigo-600 dark:text-indigo-300 border-indigo-500/20', 'user' => 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-300 border-emerald-500/20', 'pimpinan' => 'bg-amber-500/15 text-amber-600 dark:text-amber-300 border-amber-500/20'];
                                $roleLabels = ['admin' => 'Admin', 'user' => 'User', 'pimpinan' => 'Pimpinan'];
                            @endphp
                            <span class="px-2.5 py-1 rounded-lg text-xs font-medium border {{ $roleColors[$user->role] ?? 'bg-slate-500/15 text-slate-600 dark:text-slate-300 border-slate-500/20' }}">
                                {{ $roleLabels[$user->role] ?? $user->role }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="glass-button text-xs py-1.5 px-2.5 !text-amber-600 dark:!text-amber-400 border-amber-500/20 hover:bg-amber-500/10">
                                    Edit
                                </a>
                                @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline"
                                          onsubmit="return confirm('Hapus user {{ $user->username }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="glass-button text-xs py-1.5 px-2.5 !text-red-600 dark:!text-red-400 border-red-500/20 hover:bg-red-500/10">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-theme-muted">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            Belum ada data user.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="mt-4">{{ $users->links() }}</div>
    @endif
</div>
@endsection
