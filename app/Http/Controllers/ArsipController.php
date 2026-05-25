<?php

namespace App\Http\Controllers;

use App\Exports\ArsipExport;
use App\Models\Arsip;
use App\Models\Bidang;
use App\Models\JenisArsip;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ArsipController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Arsip::with(['jenisArsip', 'bidang', 'uploader']);

        if ($user->isUser()) {
            $query->where('bidang_id', $user->bidang_id);
        }

        if ($request->filled('bidang_id') && ($user->isAdmin() || $user->isPimpinan())) {
            $query->where('bidang_id', $request->bidang_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('nomor_arsip', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jenis_arsip_id')) {
            $query->where('jenis_arsip_id', $request->jenis_arsip_id);
        }

        if ($request->filled('tgl_dari')) {
            $query->whereDate('tgl_dilegalkan', '>=', $request->tgl_dari);
        }

        if ($request->filled('tgl_sampai')) {
            $query->whereDate('tgl_dilegalkan', '<=', $request->tgl_sampai);
        }

        $arsips = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $jenisArsipList = JenisArsip::orderBy('nama_jenis')->get();
        $bidangList = Bidang::orderBy('nama_bidang')->get();

        return view('arsip.index', compact('arsips', 'jenisArsipList', 'bidangList'));
    }

    public function create()
    {
        $user = request()->user();
        if ($user->isPimpinan()) {
            // abort(403, 'Pimpinan tidak dapat mengunggah arsip.');
            return redirect()->back()->with('error', 'Pimpinan tidak dapat mengunggah arsip.');
        }

        $jenisArsipList = JenisArsip::orderBy('nama_jenis')->get();
        $bidangList = Bidang::orderBy('nama_bidang')->get();
        return view('arsip.create', compact(['jenisArsipList', 'bidangList']));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $user = $request->user();
        if ($user->isPimpinan()) {
            // abort(403, 'Pimpinan tidak dapat mengunggah arsip.');
            return redirect()->back()->with('error', 'Pimpinan tidak dapat mengunggah arsip.');

        }

        $validate = Validator::make($request->all(), [
            'nomor_arsip' => 'required|string|max:255',
            'tgl_dilegalkan' => 'required|date',
            'judul' => 'required|string|max:500',
            'jenis_arsip_id' => 'required|exists:jenis_arsip,id',
            'bidang_id' => 'required|exists:bidang,id',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);
        // $validated = $request->validate([
        //     'nomor_arsip' => 'required|string|max:255',
        //     'tgl_dilegalkan' => 'required|date',
        //     'judul' => 'required|string|max:500',
        //     'jenis_arsip_id' => 'required|exists:jenis_arsip,id',
        //     'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        // ]);

        if ($validate->fails()) {
            return redirect()->back()->with('error', $validate->errors()->first())->withInput();

        }
        $validated = $validate->validated();

        $file = $request->file('file');
        $filename = time() . '_' . $file->hashName();
        $path = $file->storeAs('arsip', $filename, 'public');

        Arsip::create([
            'nomor_arsip' => $validated['nomor_arsip'],
            'tgl_dilegalkan' => $validated['tgl_dilegalkan'],
            'judul' => $validated['judul'],
            'jenis_arsip_id' => $validated['jenis_arsip_id'],
            'bidang_id' => $validated['bidang_id'],
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'file_type' => $file->getClientOriginalExtension(),
            'uploaded_by' => $user->id,
        ]);

        return redirect()->route('arsip.index')->with('success', 'Arsip berhasil diunggah.');
    }

    public function show(Arsip $arsip)
    {
        $user = request()->user();
        if ($user->isUser() && $arsip->bidang_id !== $user->bidang_id) {
            return redirect()->back()->with('error', 'Anda tidak dapat mengakses arsip bidang lain.');

        }

        if (!Storage::disk('public')->exists($arsip->file_path)) {
            // abort(404, 'File tidak ditemukan.');
            return redirect()->back()->with('error', 'File tidak ditemukan.');

        }

        return Storage::disk('public')->response($arsip->file_path);
    }

    public function edit(Arsip $arsip)
    {
        $user = request()->user();
        if ($user->isPimpinan()) {
            // abort(403, 'Pimpinan tidak dapat mengedit arsip.');
            return redirect()->back()->with('error', 'Pimpinan tidak dapat mengedit arsip.');
        }
        if ($user->isUser() && $arsip->bidang_id !== $user->bidang_id) {
            // abort(403, 'Anda tidak dapat mengedit arsip bidang lain.');
            return redirect()->back()->with('error', 'Anda tidak dapat mengedit arsip bidang lain.');
        }

        $jenisArsipList = JenisArsip::orderBy('nama_jenis')->get();

        return view('arsip.edit', compact('arsip', 'jenisArsipList'));
    }

    public function update(Request $request, Arsip $arsip)
    {
        $user = $request->user();
        if ($user->isPimpinan()) {
            // abort(403, 'Pimpinan tidak dapat mengedit arsip.');
            return redirect()->back()->with('error', 'Pimpinan tidak dapat mengedit arsip.');
        }
        if ($user->isUser() && $arsip->bidang_id !== $user->bidang_id) {
            // abort(403, 'Anda tidak dapat mengedit arsip bidang lain.');
            return redirect()->back()->with('error', 'Anda tidak dapat mengedit arsip bidang lain.');
        }

        $rules = [
            'nomor_arsip' => 'required|string|max:255',
            'tgl_dilegalkan' => 'required|date',
            'judul' => 'required|string|max:500',
            'jenis_arsip_id' => 'required|exists:jenis_arsip,id',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];

        if ($user->isAdmin()) {
            $rules['bidang_id'] = 'required|exists:bidang,id';
        }

        $validated = $request->validate($rules);

        $data = [
            'nomor_arsip' => $validated['nomor_arsip'],
            'tgl_dilegalkan' => $validated['tgl_dilegalkan'],
            'judul' => $validated['judul'],
            'jenis_arsip_id' => $validated['jenis_arsip_id'],
        ];

        if ($user->isAdmin()) {
            $data['bidang_id'] = $validated['bidang_id'];
        }

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($arsip->file_path);
            $file = $request->file('file');
            $filename = time() . '_' . $file->hashName();
            $data['file_path'] = $file->storeAs('arsip', $filename, 'public');
            $data['file_size'] = $file->getSize();
            $data['file_type'] = $file->getClientOriginalExtension();
        }

        $arsip->update($data);

        return redirect()->route('arsip.index')->with('success', 'Arsip berhasil diperbarui.');
    }

    public function destroy(Arsip $arsip)
    {
        $user = request()->user();
        if ($user->isPimpinan()) {
            // abort(403, 'Pimpinan tidak dapat menghapus arsip.');
            return redirect()->back()->with('error', 'Pimpinan tidak dapat menghapus arsip.');
        }
        if ($user->isUser() && $arsip->bidang_id !== $user->bidang_id) {
            // abort(403, 'Anda tidak dapat menghapus arsip bidang lain.');
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus arsip bidang lain.');
        }

        Storage::disk('public')->delete($arsip->file_path);
        $arsip->delete();

        return redirect()->route('arsip.index')->with('success', 'Arsip berhasil dihapus.');
    }

    public function exportExcel(Request $request)
    {
        $user = $request->user();
        $query = Arsip::with(['jenisArsip', 'bidang']);

        if ($user->isUser()) {
            $query->where('bidang_id', $user->bidang_id);
        }

        if ($request->filled('bidang_id') && ($user->isAdmin() || $user->isPimpinan())) {
            $query->where('bidang_id', $request->bidang_id);
        }

        $arsips = $query->orderBy('created_at', 'desc')->get();

        $data = $arsips->map(function ($arsip, $index) {
            return [
                'No' => $index + 1,
                'Nomor Arsip' => $arsip->nomor_arsip,
                'Judul Arsip' => $arsip->judul,
                'Tanggal Dilegalkan' => $arsip->tgl_dilegalkan->format('d/m/Y'),
                'Jenis Arsip' => $arsip->jenisArsip->nama_jenis ?? '-',
                'Bidang' => $arsip->bidang->nama_bidang ?? '-',
            ];
        })->toArray();

        $headings = ['No', 'Nomor Arsip', 'Judul Arsip', 'Tanggal Dilegalkan', 'Jenis Arsip', 'Bidang'];

        return Excel::download(
            new ArsipExport($data, $headings),
            'laporan-arsip-' . date('Y-m-d') . '.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $user = $request->user();
        $query = Arsip::with(['jenisArsip', 'bidang']);

        if ($user->isUser()) {
            $query->where('bidang_id', $user->bidang_id);
        }

        if ($request->filled('bidang_id') && ($user->isAdmin() || $user->isPimpinan())) {
            $query->where('bidang_id', $request->bidang_id);
        }

        $arsips = $query->orderBy('created_at', 'desc')->get();

        $data = $arsips->map(function ($arsip, $index) {
            return [
                'no' => $index + 1,
                'nomor_arsip' => $arsip->nomor_arsip,
                'judul' => $arsip->judul,
                'tgl_dilegalkan' => $arsip->tgl_dilegalkan->format('d/m/Y'),
                'jenis_arsip' => $arsip->jenisArsip->nama_jenis ?? '-',
                'bidang' => $arsip->bidang->nama_bidang ?? '-',
            ];
        });

        $pdf = Pdf::loadView('arsip.export-pdf', compact('data'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('laporan-arsip-' . date('Y-m-d') . '.pdf');
    }
}
