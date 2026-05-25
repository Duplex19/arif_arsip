<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('bidang')->orderBy('username')->paginate(20);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $bidangs = Bidang::orderBy('nama_bidang')->get();

        return view('users.create', compact('bidangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'bidang_id' => 'nullable|exists:bidang,id',
            'role' => 'required|in:admin,user,pimpinan',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        if ($validated['role'] === 'admin') {
            $validated['bidang_id'] = null;
        }

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $bidangs = Bidang::orderBy('nama_bidang')->get();

        return view('users.edit', compact('user', 'bidangs'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'bidang_id' => 'nullable|exists:bidang,id',
            'role' => 'required|in:admin,user,pimpinan',
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($validated['role'] === 'admin') {
            $validated['bidang_id'] = null;
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === request()->user()->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
