<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Encuestas</title>
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

        /* Alert de información personalizado */
        .alert-metrix-info {
            background: linear-gradient(135deg, #e8f5e8 0%, #d4edda 100%);
            border: 1px solid #7a8d75;
            border-radius: 8px;
            color: #2d5016;
            padding: 1rem;
            margin-bottom: 1.5rem;
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

        /* Columnas específicas con anchos fijos */
        .table-metrix th:nth-child(1), /* ID */
        .table-metrix td:nth-child(1) {
            width: 8%;
        }

        .table-metrix th:nth-child(2), /* Título */
        .table-metrix td:nth-child(2) {
            width: 25%;
        }

        .table-metrix th:nth-child(3), /* Descripción */
        .table-metrix td:nth-child(3) {
            width: 30%;
        }

        .table-metrix th:nth-child(4), /* Imagen */
        .table-metrix td:nth-child(4) {
            width: 20%;
            text-align: center;
        }

        .table-metrix th:nth-child(5), /* Fecha */
        .table-metrix td:nth-child(5) {
            width: 17%;
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

        /* No expandir en hover la columna de imagen */
        .table-metrix td:nth-child(4):hover {
            white-space: nowrap;
            word-break: normal;
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

        /* Estilos para imágenes */
        .table-metrix img {
            border-radius: 6px;
            border: 2px solid #e8ede7;
            transition: all 0.2s ease;
        }

        .table-metrix img:hover {
            transform: scale(1.1);
            border-color: #7a8d75;
            box-shadow: 0 4px 8px rgba(122, 141, 117, 0.3);
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
            body {
                padding-top: 50px;
            }
            
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

            /* Ajustes específicos para móvil */
            .table-metrix th:nth-child(1),
            .table-metrix td:nth-child(1) {
                width: 10%;
            }

            .table-metrix th:nth-child(2),
            .table-metrix td:nth-child(2) {
                width: 25%;
            }

            .table-metrix th:nth-child(3),
            .table-metrix td:nth-child(3) {
                width: 35%;
            }

            .table-metrix th:nth-child(4),
            .table-metrix td:nth-child(4) {
                width: 15%;
            }

            .table-metrix th:nth-child(5),
            .table-metrix td:nth-child(5) {
                width: 15%;
            }

            .table-metrix img {
                width: 50px !important;
            }
        }

        @media (max-width: 576px) {
            .table-metrix thead th,
            .table-metrix tbody td {
                padding: 0.4rem 0.2rem;
                font-size: 0.75rem;
            }

            .table-metrix img {
                width: 40px !important;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="metrix-container">
        <h2 class="metrix-title">📊 Encuestas</h2>

        <?php if (isset($total_encuestas)): ?>
            <div class="alert-metrix-info">
                <strong>📈 Total de encuestas</strong> para la campaña <strong><?= esc($campana_id) ?></strong>: 
                <span style="font-size: 1.1em; color: #2d5016; font-weight: bold;"><?= esc($total_encuestas) ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($encuestas)): ?>
            <div class="table-container">
                <table class="table table-metrix">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Imagen</th>
                            <th>Fecha Creación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($encuestas as $encuesta): ?>
                            <tr>
                                <td><?= esc($encuesta['id']) ?></td>
                                <td><?= esc($encuesta['title']) ?></td>
                                <td><?= esc($encuesta['description']) ?></td>
                                <td>
                                    <?php if (!empty($encuesta['image'])): ?>
                                        <img src="<?= base_url('uploads/' . $encuesta['image']) ?>" alt="Imagen" width="80">
                                    <?php else: ?>
                                        <span class="text-muted">📷 Sin imagen</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($encuesta['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-metrix">
                <strong>📋 No hay encuestas contestadas.</strong><br>
                No se encontraron encuestas contestadas para esta campaña en este momento.
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>