@extends('layouts.coordinateur')

@section('title', 'Volume cumulé de cours par classe')

@section('content')
{{-- Page d'affichages pour le volume cumule de cours par classe --}}
    <a href="{{ route('coordinateur.presences.statistiques') }}" class="text-gray-600 hover:text-gray-800 transition-colors">
        <i class="fas fa-arrow-left text-xl"></i>
    </a>
    <div class="max-w-7xl mx-auto mt-10 bg-white p-6 rounded-xl shadow-md">
        <h2 class="text-2xl font-bold mb-4">Volume cumulé des heures de cours par classe</h2>
        <p class="mb-6 text-gray-600">Toutes les séances (hors séances annulées) – Semestre 1 & 2</p>

        <div class="relative overflow-x-auto">
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-10">
                    <canvas id="cumulativeChart" height="{{ count($donnees) * 50 }}"></canvas>
                </div>
                <div class="col-span-2 pt-10">
                    <h4 class="text-red-600 font-bold text-center">Total</h4>
                    <ul class="text-center mt-2 space-y-3">
                        @foreach ($donnees as $d)
                            <li class="text-xl font-semibold text-gray-800">{{ $d->total }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('cumulativeChart').getContext('2d');

        const labels = {!! json_encode($donnees->pluck('classe')) !!};
        const s1 = {!! json_encode($donnees->pluck('s1')) !!};
        const s2 = {!! json_encode($donnees->pluck('s2')) !!};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                        label: 'SEMESTRE 1',
                        data: s1,
                        backgroundColor: '#002f6c'
                    },
                    {
                        label: 'SEMESTRE 2',
                        data: s2,
                        backgroundColor: '#74a9e2'
                    }
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: ctx => `${ctx.dataset.label} : ${ctx.parsed.x} heures`
                        }
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Nombre d’heures'
                        }
                    },
                    y: {
                        stacked: true,
                        ticks: {
                            font: {
                                size: 14
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
