@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard Overview')

@section('sidebar')
    @include('components.sidebar-admin')
@endsection

@section('content')
    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        
        <!-- Card: Total Armada -->
        <div class="bg-surface rounded-xl p-6 shadow-halus border border-gray-100 flex items-center justify-between group hover:shadow-md transition-all">
            <div>
                <p class="text-sm font-medium text-secondary mb-1">Total Armada</p>
                <h3 class="text-2xl font-bold text-gray-800">24 <span class="text-xs font-normal text-success bg-success/10 px-2 py-0.5 rounded-full ml-1">Unit</span></h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xl group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all duration-300">
                <i class="fa-solid fa-bus"></i>
            </div>
        </div>

        <!-- Card: Total Supir -->
        <div class="bg-surface rounded-xl p-6 shadow-halus border border-gray-100 flex items-center justify-between group hover:shadow-md transition-all">
            <div>
                <p class="text-sm font-medium text-secondary mb-1">Total Supir</p>
                <h3 class="text-2xl font-bold text-gray-800">32 <span class="text-xs font-normal text-success bg-success/10 px-2 py-0.5 rounded-full ml-1">Orang</span></h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xl group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                <i class="fa-solid fa-id-card"></i>
            </div>
        </div>

        <!-- Card: Tiket Hari Ini -->
        <div class="bg-surface rounded-xl p-6 shadow-halus border border-gray-100 flex items-center justify-between group hover:shadow-md transition-all">
            <div>
                <p class="text-sm font-medium text-secondary mb-1">Tiket Terjual (Hari Ini)</p>
                <h3 class="text-2xl font-bold text-gray-800">145 <span class="text-xs font-normal text-success bg-success/10 px-2 py-0.5 rounded-full ml-1">+12%</span></h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-orange-50 text-warning flex items-center justify-center text-xl group-hover:scale-110 group-hover:bg-warning group-hover:text-white transition-all duration-300">
                <i class="fa-solid fa-ticket"></i>
            </div>
        </div>

        <!-- Card: Pendapatan -->
        <div class="bg-surface rounded-xl p-6 shadow-halus border border-gray-100 flex items-center justify-between group hover:shadow-md transition-all">
            <div>
                <p class="text-sm font-medium text-secondary mb-1">Pendapatan (Bulan Ini)</p>
                <h3 class="text-xl font-bold text-gray-800">Rp 45.2M</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-emerald-50 text-success flex items-center justify-center text-xl group-hover:scale-110 group-hover:bg-success group-hover:text-white transition-all duration-300">
                <i class="fa-solid fa-wallet"></i>
            </div>
        </div>

    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Bar Chart: Statistik Penjualan -->
        <div class="lg:col-span-2 bg-surface rounded-xl shadow-halus p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Statistik Penjualan Tiket</h3>
                <select class="text-sm border-gray-200 rounded-lg text-secondary focus:ring-primary focus:border-primary">
                    <option>7 Hari Terakhir</option>
                    <option>Bulan Ini</option>
                    <option>Tahun Ini</option>
                </select>
            </div>
            <div class="relative h-72 w-full">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Doughnut Chart: Status Armada -->
        <div class="bg-surface rounded-xl shadow-halus p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Operasional Armada</h3>
            <div class="relative h-64 w-full flex items-center justify-center">
                <canvas id="armadaChart"></canvas>
            </div>
            <div class="mt-4 grid grid-cols-2 gap-4 text-center">
                <div>
                    <p class="text-xs text-secondary">Beroperasi</p>
                    <p class="text-lg font-bold text-primary">18</p>
                </div>
                <div>
                    <p class="text-xs text-secondary">Maintenance</p>
                    <p class="text-lg font-bold text-warning">6</p>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
<script>
    // Notifikasi Selamat Datang (SweetAlert Toast)
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });
    
    Toast.fire({
        icon: 'success',
        title: 'Selamat datang di Dashboard Admin!'
    });

    // Inisialisasi Chart.js
    document.addEventListener('DOMContentLoaded', function () {
        
        // 1. Line Chart Penjualan
        const ctxSales = document.getElementById('salesChart').getContext('2d');
        
        // Membuat efek gradient untuk Line Chart
        let gradientSales = ctxSales.createLinearGradient(0, 0, 0, 400);
        gradientSales.addColorStop(0, 'rgba(30, 58, 138, 0.5)'); // primary color with opacity
        gradientSales.addColorStop(1, 'rgba(30, 58, 138, 0.0)');

        new Chart(ctxSales, {
            type: 'line',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                datasets: [{
                    label: 'Tiket Terjual',
                    data: [120, 150, 140, 180, 220, 300, 280],
                    borderColor: '#1e3a8a', // primary color
                    backgroundColor: gradientSales,
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#1e3a8a',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    fill: true,
                    tension: 0.4 // Membuat kurva melengkung halus
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [4, 4], color: '#f1f5f9' },
                        border: { display: false }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false }
                    }
                }
            }
        });

        // 2. Doughnut Chart Armada
        const ctxArmada = document.getElementById('armadaChart').getContext('2d');
        new Chart(ctxArmada, {
            type: 'doughnut',
            data: {
                labels: ['Beroperasi', 'Maintenance'],
                datasets: [{
                    data: [18, 6],
                    backgroundColor: ['#1e3a8a', '#f59e0b'], // Primary & Warning
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%', // Ketebalan donut
                plugins: {
                    legend: { display: false }
                }
            }
        });
    });
</script>
@endpush