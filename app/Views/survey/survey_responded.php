 <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estadísticas por Encuesta</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: sans-serif;
            background: #f9f9f9;
            padding: 20px;
        }
        .chart-container {
            margin: 40px 0;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        h2, h4 {
            text-align: center;
            color: #333;
        }
        .resumen {
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div>
    <h2><?= esc($titulo_pagina ?? 'Estadísticas') ?></h2>
    <div>
        <strong>Diagnóstico de Vista:</strong><br>
        <?= isset($estadistica) ? count($estadistica) . ' pregunta(s) múltiples encontrada(s).' : '⚠ $estadistica no está definida' ?>
    </div>
</div>

<?php if (!empty($estadistica)): ?>
    <?php foreach ($estadistica as $index => $dato): ?>
        <?php 
            if (is_array($dato['conteo']) && !empty($dato['conteo'])) {
                arsort($dato['conteo']);
                $opcionPopular = array_key_first($dato['conteo']);
                $conteoPopular = $dato['conteo'][$opcionPopular];
            } else {
                $opcionPopular = 'No hay datos';
                $conteoPopular = 0;
                $dato['conteo'] = []; // importante para Chart.js
            }
        ?>
        <div class="chart-container">
            <h4><?= esc($dato['pregunta']) ?></h4>
            <canvas id="chart<?= $index ?>" width="400" height="400"></canvas>
            <p class="resumen">✅ Opción más popular: <strong><?= esc($opcionPopular) ?></strong> (<?= $conteoPopular ?> votos)</p>
        </div>

        <script>
            const ctx<?= $index ?> = document.getElementById('chart<?= $index ?>').getContext('2d');
            new Chart(ctx<?= $index ?>, {
                type: 'bar',
                data: {
                    labels: <?= json_encode(array_keys($dato['conteo'])) ?>,
                    datasets: [{
                        label: 'Cantidad de respuestas',
                        data: <?= json_encode(array_values($dato['conteo'])) ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        </script>
    <?php endforeach; ?>
<?php else: ?>
    <p>No hay estadísticas disponibles.</p>
<?php endif; ?>

</body>
</html>
