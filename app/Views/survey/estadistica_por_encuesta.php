<div class="container mx-auto px-4 pt-16 pb-6 mb-20">
    <h2 class="text-2xl font-bold text-center mb-2"><?= esc($survey['title']) ?></h2>

    <div class="text-center text-gray-600 text-sm mb-6">
        Total de encuestados: <strong><?= $totalRespuestas ?></strong>
    </div>

    <?php if (!empty($agrupadas)) : ?>
        <!-- Gráfico polar -->
        <div class="flex justify-center mb-12">
            <canvas id="polarChart" width="300" height="300"></canvas>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('polarChart').getContext('2d');

            const allLabels = [];
            const allValues = [];

            <?php foreach ($agrupadas as $pregunta => $respuestas) :
                foreach ($respuestas as $opcion => $cantidad) : ?>
                    allLabels.push("<?= esc($opcion) ?>");
                    allValues.push(<?= (int)$cantidad ?>);
            <?php endforeach; endforeach; ?>

            new Chart(ctx, {
                type: 'polarArea',
                data: {
                    labels: allLabels,
                    datasets: [{
                        label: 'Total de respuestas',
                        data: allValues,
                        backgroundColor: [
                            '#f87171', '#60a5fa', '#facc15', '#4ade80',
                            '#a78bfa', '#fb923c', '#34d399', '#f472b6'
                        ],
                        borderColor: '#ffffff',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'right' },
                        title: {
                            display: true,
                            text: 'Respuestas combinadas por opción'
                        }
                    }
                }
            });
        </script>

        <!-- Resumen por pregunta -->
        <div class="mt-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
            <?php foreach ($agrupadas as $pregunta => $respuestas) : ?>
                <div class="bg-white border border-gray-200 shadow-sm rounded-xl p-5">
                    <h3 class="text-base font-semibold text-gray-800 mb-3"><?= esc($pregunta) ?></h3>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($respuestas as $valor => $cantidad) : ?>
                            <span class="px-3 py-1 rounded-full text-sm font-medium text-white"
                                  style="background-color: <?= '#' . substr(md5($valor), 0, 6) ?>;">
                                <?= esc($valor) ?> (<?= $cantidad ?>)
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <p class="text-center text-red-500 mt-6">No hay datos disponibles para esta encuesta.</p>
    <?php endif; ?>
</div>
