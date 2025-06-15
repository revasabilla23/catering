@extends('layout.app')

@section('title', 'Monitoring Konsumsi')
@section('header', 'Monitoring Konsumsi Harian')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-5">

    <form method="GET" action="{{ route('hrga.konsumsi') }}" class="flex flex-col sm:flex-row items-center gap-4 mb-10">
        <label for="tanggal" class="w-full sm:w-auto font-semibold text-gray-900 dark:text-white">Pilih Tanggal:</label>
        <input
            type="date"
            name="tanggal"
            id="tanggal"
            value="{{ request('tanggal', \Carbon\Carbon::today()->toDateString()) }}"
            class="w-full sm:w-48 border border-gray-300 rounded-md px-4 py-2 text-gray-900 placeholder-gray-400
                    focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition
                    dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400"
        >
        <button
            type="submit"
            class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-md shadow-md
                    transition duration-300"
        >
            Tampilkan
        </button>
    </form>

    <h2 class="text-3xl font-semibold text-gray-900 dark:text-white mb-6 border-b border-gray-300 dark:border-gray-700 pb-3">
        Monitoring Konsumsi Tanggal:
        <span class="text-blue-600 dark:text-blue-400">{{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</span>
    </h2>

    <div class="bg-white p-2 rounded-xl shadow-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        {{-- Kontainer dengan tinggi minimum yang responsif --}}
        <div class="relative h-96 md:h-[500px] lg:h-[600px]">
            <canvas id="monitoringChart"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const data = @json($dataMonitoring);

    const labels = data.map(item => item.shift);
    const pesananData = data.map(item => item.pesanan);
    const konsumsiData = data.map(item => item.konsumsi);
    const sisaData = data.map(item => item.sisa);

    function getCssVariable(variable) {
        return getComputedStyle(document.documentElement).getPropertyValue(variable).trim();
    }

    const chartColors = {
        pesanan: '#3b82f6', // blue-500 for light mode
        konsumsi: '#10b981', // green-500 for light mode
        sisa: '#f59e0b', // yellow-500 for light mode
        gridLineColorLight: '#E5E7EB',
        gridLineColorDark: 'var(--dark-grey)',
        tickColorLight: '#4B5563',
        tickColorDark: 'var(--dark-grey)',
        labelColorLight: '#374151',
        labelColorDark: 'var(--dark)',
        tooltipBgLight: '#FFFFFF',
        tooltipBgDark: 'var(--dark)',
        tooltipTitleLight: '#374151',
        tooltipTitleDark: 'var(--dark)',
        tooltipBodyLight: '#4B5563',
        tooltipBodyDark: 'var(--dark-grey)',
        tooltipBorderLight: '#E5E7EB',
        tooltipBorderDark: 'var(--dark-grey)',
    };

    let monitoringChart;

    function createOrUpdateChart() {
        if (monitoringChart) {
            monitoringChart.destroy();
        }

        const isDarkMode = document.documentElement.classList.contains('dark');

        const gridLineColor = isDarkMode ? getCssVariable('--dark-grey') : chartColors.gridLineColorLight;
        const tickColor = isDarkMode ? getCssVariable('--dark-grey') : chartColors.tickColorLight;
        const labelColor = isDarkMode ? getCssVariable('--dark') : chartColors.labelColorLight;
        const tooltipBg = isDarkMode ? getCssVariable('--dark') : chartColors.tooltipBgLight;
        const tooltipTitleColor = isDarkMode ? getCssVariable('--dark') : chartColors.tooltipTitleLight;
        const tooltipBodyColor = isDarkMode ? getCssVariable('--dark-grey') : chartColors.tooltipBodyLight;
        const tooltipBorderColor = isDarkMode ? getCssVariable('--dark-grey') : chartColors.tooltipBorderLight;

        monitoringChart = new Chart(document.getElementById('monitoringChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Pesanan',
                        data: pesananData,
                        backgroundColor: chartColors.pesanan,
                        borderRadius: 6,
                        barPercentage: 0.6,
                    },
                    {
                        label: 'Konsumsi',
                        data: konsumsiData,
                        backgroundColor: chartColors.konsumsi,
                        borderRadius: 6,
                        barPercentage: 0.6,
                    },
                    {
                        label: 'Sisa',
                        data: sisaData,
                        backgroundColor: chartColors.sisa,
                        borderRadius: 6,
                        barPercentage: 0.6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Penting agar chart memanjang sesuai tinggi kontainer
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        labels: {
                            font: { size: 14, weight: '600' },
                            padding: 24,
                            color: labelColor,
                        }
                    },
                    tooltip: {
                        enabled: true,
                        mode: 'nearest',
                        intersect: false,
                        bodyFont: { size: 14 },
                        padding: 10,
                        backgroundColor: tooltipBg,
                        titleColor: tooltipTitleColor,
                        bodyColor: tooltipBodyColor,
                        borderColor: tooltipBorderColor,
                        borderWidth: 1,
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: gridLineColor,
                        },
                        ticks: {
                            color: tickColor,
                            font: { size: 12 }
                        },
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: gridLineColor,
                        },
                        ticks: {
                            color: tickColor,
                            font: { size: 12 },
                            callback: function(value) {
                                return value;
                            }
                        }
                    }
                }
            }
        });
    }

    // Panggil saat DOM dimuat
    document.addEventListener('DOMContentLoaded', createOrUpdateChart);

    // Deteksi perubahan tema (dark/light mode) dan perbarui chart
    const htmlElement = document.documentElement;
    const observer = new MutationObserver(mutations => {
        mutations.forEach(mutation => {
            if (mutation.attributeName === 'class') {
                createOrUpdateChart();
            }
        });
    });

    observer.observe(htmlElement, { attributes: true });

    // Opsional: Perbarui chart saat ukuran jendela berubah (jika ada kasus edge yang terlewat oleh responsive:true)
    // window.addEventListener('resize', () => {
    //     if (monitoringChart) {
    //         monitoringChart.resize();
    //     }
    // });
</script>
@endsection