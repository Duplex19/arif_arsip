<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BidangController extends Controller
{
    public function index()
    {
        $bidangs = Bidang::withCount('users', 'arsips')->orderBy('nama_bidang')->get();

        return view('bidang.index', compact('bidangs'));
    }

    public function create()
    {
        return view('bidang.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_bidang' => 'required|string|max:255|unique:bidang',
        ]);

        Bidang::create($validated);

        return redirect()->route('admin.bidang.index')->with('success', 'Bidang berhasil ditambahkan.');
    }

    public function edit(Bidang $bidang)
    {
        return view('bidang.edit', compact('bidang'));
    }

    public function update(Request $request, Bidang $bidang)
    {
        $validated = $request->validate([
            'nama_bidang' => ['required', 'string', 'max:255', Rule::unique('bidang')->ignore($bidang->id)],
        ]);

        $bidang->update($validated);

        return redirect()->route('admin.bidang.index')->with('success', 'Bidang berhasil diperbarui.');
    }

    public function destroy(Bidang $bidang)
    {
        if ($bidang->users()->count() > 0) {
            return back()->with('error', 'Bidang tidak dapat dihapus karena masih memiliki user terkait.');
        }

        $bidang->delete();

        return redirect()->route('admin.bidang.index')->with('success', 'Bidang berhasil dihapus.');
    }
}
