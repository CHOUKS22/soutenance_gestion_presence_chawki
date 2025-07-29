@extends('layouts.coordinateur')

@section('title', 'Volume d’heures de cours dispensés par classe')

@section('content')
    <a href="{{ route('coordinateur.presences.statistiques') }}" class="text-gray-600 hover:text-gray-800 transition-colors">
        <i class="fas fa-arrow-left text-xl"></i>
    </a>
    <div class="max-w-7xl mx-auto mt-10 bg-white p-6 rounded-xl shadow-md">
        <h2 class="text-2xl font-bold mb-6">Volume d’heures de cours par type</h2>
        <p class="mb-4 text-gray-600">Semestres 1 et 2 (année académique en cours)</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Semestre 1 --}}
            <div>
                <h3 class="text-lg font-semibold mb-2 text-gray-800">NOMBRE D’HEURES DE COURS – Sem. 1</h3>
                <div style="height: {{ count($dataSemestre1) * 40 }}px;">
                    <canvas id="chartSem1"></canvas>
                </div>
            </div>

            {{-- Semestre 2 --}}
            <div>
                <h3 class="text-lg font-semibold mb-2 text-gray-800">NOMBRE D’HEURES DE COURS – Sem. 2</h3>
                <div style="height: {{ count($dataSemestre2) * 40 }}px;">
                    <canvas id="chartSem2"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const classes = {!! json_encode(collect($dataSemestre1)->pluck('classe')) !!};

        const dataSem1 = {
            labels: classes,
            datasets: [{
                    label: 'Présentiel',
                    data: {!! json_encode(collect($dataSemestre1)->pluck('Présentiel')) !!},
                    backgroundColor: '#002f6c'
                },
                {
                    label: 'E-learning',
                    data: {!! json_encode(collect($dataSemestre1)->pluck('E-learning')) !!},
                    backgroundColor: '#4CAF50'
                },
                {
                    label: 'Workshop',
                    data: {!! json_encode(collect($dataSemestre1)->pluck('Workshop')) !!},
                    backgroundColor: '#f59e0b'
                }
            ]
        };

        const dataSem2 = {
            labels: classes,
            datasets: [{
                    label: 'Présentiel',
                    data: {!! json_encode(collect($dataSemestre2)->pluck('Présentiel')) !!},
                    backgroundColor: '#74a9e2'
                },
                {
                    label: 'E-learning',
                    data: {!! json_encode(collect($dataSemestre2)->pluck('E-learning')) !!},
                    backgroundColor: '#a3e635'
                },
                {
                    label: 'Workshop',
                    data: {!! json_encode(collect($dataSemestre2)->pluck('Workshop')) !!},
                    backgroundColor: '#fbbf24'
                }
            ]
        };

        const options = {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Nombre d’heures'
                    }
                }
            }
        };

        new Chart(document.getElementById('chartSem1'), {
            type: 'bar',
            data: dataSem1,
            options: options
        });

        new Chart(document.getElementById('chartSem2'), {
            type: 'bar',
            data: dataSem2,
            options: options
        });
    </script>

@endsection
