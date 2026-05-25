<?php

namespace App\Http\Controllers;

use App\Models\JenisArsip;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JenisArsipController extends Controller
{
    public function index()
    {
        $jenisArsips = JenisArsip::withCount('arsips')->orderBy('nama_jenis')->get();

        return view('jenis-arsip.index', compact('jenisArsips'));
    }

    public function create()
    {
        return view('jenis-arsip.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_jenis' => 'required|string|max:255',
            'kode_jenis' => 'nullable|string|max:50|unique:jenis_arsip',
        ]);

        JenisArsip::create($validated);

        return redirect()->route('admin.jenis-arsip.index')->with('success', 'Jenis arsip berhasil ditambahkan.');
    }

    public function edit(JenisArsip $jenisArsip)
    {
        return view('jenis-arsip.edit', compact('jenisArsip'));
    }

    public function update(Request $request, JenisArsip $jenisArsip)
    {
        $validated = $request->validate([
            'nama_jenis' => 'required|string|max:255',
            'kode_jenis' => ['nullable', 'string', 'max:50', Rule::unique('jenis_arsip')->ignore($jenisArsip->id)],
        ]);

        $jenisArsip->update($validated);

        return redirect()->route('admin.jenis-arsip.index')->with('success', 'Jenis arsip berhasil diperbarui.');
    }

    public function destroy(JenisArsip $jenisArsip)
    {
        if ($jenisArsip->arsips()->count() > 0) {
            return back()->with('error', 'Jenis arsip tidak dapat dihapus karena masih memiliki arsip terkait.');
        }

        $jenisArsip->delete();

        return redirect()->route('admin.jenis-arsip.index')->with('success', 'Jenis arsip berhasil dihapus.');
    }
}
