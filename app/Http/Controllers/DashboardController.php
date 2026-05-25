<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\Bidang;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $bidangId = $user->bidang_id;

        $arsipQuery = Arsip::query();
        $bidangQuery = Bidang::query();

        if ($user->isUser()) {
            $arsipQuery->where('bidang_id', $bidangId);
        }

        $totalArsip = $arsipQuery->count();

        $arsipPerJenisQuery = Arsip::query();
        if ($user->isUser()) {
            $arsipPerJenisQuery->where('bidang_id', $bidangId);
        }
        $arsipPerJenis = $arsipPerJenisQuery
            ->selectRaw('jenis_arsip_id, count(*) as total')
            ->groupBy('jenis_arsip_id')
            ->with('jenisArsip')
            ->get()
            ->map(fn ($item) => [
                'nama' => $item->jenisArsip->nama_jenis ?? 'Unknown',
                'total' => $item->total,
            ]);

        $arsipPerBidangQuery = Arsip::query();
        if ($user->isUser()) {
            $arsipPerBidangQuery->where('bidang_id', $bidangId);
        }
        $arsipPerBidang = $arsipPerBidangQuery
            ->selectRaw('bidang_id, count(*) as total')
            ->groupBy('bidang_id')
            ->with('bidang')
            ->get()
            ->map(fn ($item) => [
                'nama' => $item->bidang->nama_bidang ?? 'Unknown',
                'total' => $item->total,
            ]);

        $arsipPerBulanQuery = Arsip::query();
        if ($user->isUser()) {
            $arsipPerBulanQuery->where('bidang_id', $bidangId);
        }
        $arsipPerBulan = $arsipPerBulanQuery
            ->get()
            ->groupBy(fn ($item) => $item->tgl_dilegalkan->format('Y-m'))
            ->map(fn ($items, $bulan) => [
                'bulan' => $bulan,
                'total' => $items->count(),
            ])
            ->sortBy('bulan')
            ->values();

        $allBidang = Bidang::orderBy('nama_bidang')->get();

        return view('dashboard.index', compact(
            'totalArsip',
            'arsipPerJenis',
            'arsipPerBidang',
            'arsipPerBulan',
            'allBidang',
            'user'
        ));
    }
}
