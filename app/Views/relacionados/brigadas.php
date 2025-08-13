<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= esc($titulo ?? 'Brigadas') ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        /* Ajuste para evitar que el contenido quede oculto detrás de header/menu fijos */
        body {
            padding-top: 100px;
            background-color: #f8f9fa;
        }

        /* Estilos personalizados con verde METRIX */
        .metrix-container {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 2rem auto;
        }

        .metrix-title {
            color: #5a6c57;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 3px solid #7a8d75;
        }

        /* Evitar scroll horizontal */
        .table-metrix {
            table-layout: fixed;
            width: 100%;
            word-wrap: break-word;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(122, 141, 117, 0.15);
        }

        .table-metrix th,
        .table-metrix td {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 0;
        }

        /* Permitir expansión en hover para ver contenido completo */
        .table-metrix td:hover {
            white-space: normal;
            word-break: break-all;
            position: relative;
            z-index: 10;
            background-color: #f4f7f3 !important;
            box-shadow: 0 4px 8px rgba(122, 141, 117, 0.3);
            max-height: none;
        }

        .table-metrix thead th {
            background: linear-gradient(135deg, #7a8d75 0%, #6b7c66 100%);
            color: white;
            border: none;
            padding: 1rem 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .table-metrix tbody tr {
            border-bottom: 1px solid #e8ede7;
            transition: all 0.2s ease;
        }

        .table-metrix tbody tr:hover {
            background-color: #f4f7f3;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(122, 141, 117, 0.1);
        }

        .table-metrix tbody td {
            padding: 0.9rem 0.75rem;
            vertical-align: middle;
            border: none;
            color: #4a5649;
        }

        /* Filas alternadas con verde muy suave */
        .table-metrix tbody tr:nth-child(even) {
            background-color: #f9faf9;
        }

        .table-metrix tbody tr:nth-child(odd) {
            background-color: white;
        }

        /* Alert personalizado */
        .alert-metrix {
            background: linear-gradient(135deg, #f4f7f3 0%, #e8ede7 100%);
            border: 1px solid #7a8d75;
            border-radius: 8px;
            color: #5a6c57;
            padding: 1.5rem;
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .metrix-container {
                margin: 0.5rem;
                padding: 1rem;
            }
            
            .table-metrix {
                font-size: 0.85rem;
            }
            
            .table-metrix thead th,
            .table-metrix tbody td {
                padding: 0.5rem 0.3rem;
                font-size: 0.8rem;
            }

            /* En móvil, mostrar texto en múltiples líneas */
            .table-metrix td {
                white-space: normal !important;
                height: auto;
                max-height: 60px;
                overflow: hidden;
            }

            .table-metrix td:hover {
                max-height: none;
            }
        }

        @media (max-width: 576px) {
            .table-metrix thead th,
            .table-metrix tbody td {
                padding: 0.4rem 0.2rem;
                font-size: 0.75rem;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="metrix-container">
        <h3 class="metrix-title"><?= esc($titulo ?? 'Brigadas') ?></h3>

        <?php if (!empty($registros)): ?>
            <div class="table-container">
                <table class="table table-metrix">
                    <thead>
                        <tr>
                            <?php foreach (array_keys($registros[0]) as $col): ?>
                                <th><?= esc($col) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $fila): ?>
                            <tr>
                                <?php foreach ($fila as $valor): ?>
                                    <td><?= esc($valor) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-metrix">
                <strong>🏥 Datos no encontrados.</strong><br>
                No se encontraron brigadas disponibles en este momento.
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>