@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    {{-- Statistic Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="glass-card group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-theme-muted">Total Arsip</p>
                    <p class="text-2xl font-bold text-theme-primary" data-counter="{{ $totalArsip }}">{{ $totalArsip }}</p>
                </div>
            </div>
        </div>

        <div class="glass-card group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-theme-muted">Jenis Arsip</p>
                    <p class="text-2xl font-bold text-theme-primary">{{ count($arsipPerJenis) }}</p>
                </div>
            </div>
        </div>

        <div class="glass-card group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg shadow-amber-500/30 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-theme-muted">Bidang</p>
                    <p class="text-2xl font-bold text-theme-primary">{{ count($allBidang) }}</p>
                </div>
            </div>
        </div>

        <div class="glass-card group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-rose-500 to-pink-600 flex items-center justify-center shadow-lg shadow-rose-500/30 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-theme-muted">Pengguna</p>
                    <p class="text-2xl font-bold text-theme-primary">{{ \App\Models\User::count() }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-chart-container title="Arsip per Jenis">
            <div id="chartJenis"></div>
        </x-chart-container>

        <x-chart-container title="Arsip per Bidang">
            <div id="chartBidang"></div>
        </x-chart-container>
    </div>

    <x-chart-container title="Arsip per Bulan">
        <div id="chartBulan"></div>
    </x-chart-container>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof ApexCharts === 'undefined') return;

    const textColor = getComputedStyle(document.documentElement).getPropertyValue('--text-secondary').trim();
    const gridColor = getComputedStyle(document.documentElement).getPropertyValue('--border-table').trim();

    const commonOptions = {
        chart: {
            foreColor: textColor,
            background: 'transparent',
            toolbar: { show: false },
            fontFamily: 'Instrument Sans, sans-serif',
        },
        grid: { borderColor: gridColor },
        tooltip: {
            theme: document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light',
        },
    };

    new ApexCharts(document.querySelector('#chartJenis'), {
        ...commonOptions,
        chart: { ...commonOptions.chart, type: 'donut', height: 320 },
        series: @json(collect($arsipPerJenis)->pluck('total')),
        labels: @json(collect($arsipPerJenis)->pluck('nama')),
        colors: ['#6366f1', '#8b5cf6', '#06b6d4', '#10b981', '#f59e0b', '#ef4444', '#ec4899', '#f43f5e'],
        legend: { position: 'bottom', fontSize: '12px' },
        dataLabels: { enabled: true, style: { colors: ['#fff'] } },
        plotOptions: {
            pie: {
                donut: {
                    size: '65%',
                    labels: {
                        show: true,
                        total: { show: true, label: 'Total', color: getComputedStyle(document.documentElement).getPropertyValue('--text-primary').trim(), fontSize: '14px', fontWeight: 600 }
                    }
                }
            }
        },
    }).render();

    new ApexCharts(document.querySelector('#chartBidang'), {
        ...commonOptions,
        chart: { ...commonOptions.chart, type: 'bar', height: 320 },
        series: [{ name: 'Jumlah Arsip', data: @json(collect($arsipPerBidang)->pluck('total')) }],
        xaxis: { categories: @json(collect($arsipPerBidang)->pluck('nama')) },
        colors: ['#6366f1'],
        plotOptions: { bar: { borderRadius: 6, columnWidth: '55%' } },
    }).render();

    new ApexCharts(document.querySelector('#chartBulan'), {
        ...commonOptions,
        chart: { ...commonOptions.chart, type: 'area', height: 320 },
        series: [{ name: 'Arsip', data: @json(collect($arsipPerBulan)->pluck('total')) }],
        xaxis: { categories: @json(collect($arsipPerBulan)->pluck('bulan')) },
        colors: ['#8b5cf6'],
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.5, opacityTo: 0.05 } },
        stroke: { curve: 'smooth', width: 3 },
    }).render();
});
</script>
@endpush
