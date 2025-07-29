@extends('layouts.coordinateur')

@section('title', 'Taux de présence par classe')

@section('content')
    <div class="max-w-6xl mx-auto mt-10 bg-white p-6 rounded-xl shadow-md">

        <h2 class="text-2xl font-bold mb-6">Taux de présence par classe</h2>
        <a href="{{ route('coordinateur.presences.statistiques') }}"
            class="text-gray-600 hover:text-gray-800 transition-colors">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        {{-- Formulaire de filtrage --}}
        <form action="{{ route('coordinateur.presences.parClasse') }}" method="GET" class="mb-6 flex items-end gap-4">
            <div>
                <label for="date_debut" class="block text-sm font-medium text-gray-700">Date de début</label>
                <input type="date" id="date_debut" name="date_debut" value="{{ request('date_debut') }}"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label for="date_fin" class="block text-sm font-medium text-gray-700">Date de fin</label>
                <input type="date" id="date_fin" name="date_fin" value="{{ request('date_fin') }}"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md mt-5">Filtrer</button>
            </div>
        </form>

        {{-- Graphique --}}
        <div style="height: {{ count($donnees) * 50 }}px; max-height: 800px;">
            <canvas id="presenceChart"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = {!! json_encode(collect($donnees)->pluck('classe')) !!};
        const data = {!! json_encode(collect($donnees)->pluck('taux')) !!};

        const backgroundColors = data.map(taux => {
            const t = parseFloat(taux ?? 0);
            if (t >= 70) return '#007500'; // Vert foncé
            if (t > 50) return '#66bb6a'; // Vert clair
            if (t > 30) return '#ffa500'; // Orange
            return '#e53935'; // Rouge
        });

        const ctx = document.getElementById('presenceChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Taux de présence (%)',
                    data: data,
                    backgroundColor: backgroundColors,
                    borderColor: '#333',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        max: 100,
                        title: {
                            display: true,
                            text: 'Taux (%)'
                        }
                    },
                    y: {
                        ticks: {
                            font: {
                                size: 14
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => `${ctx.parsed.x}%`
                        }
                    }
                }
            }
        });
    </script>
@endsection
