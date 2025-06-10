 <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Tickets</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #333;
            height: 100vh;
            overflow: hidden;
        }

        .container {
            width: 100%;
            height: 100vh;
            background: white;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .header {
            background-color: #2d5a27;
            color: white;
            padding: 15px 30px;
            border-bottom: 3px solid #1e3a1a;
            flex-shrink: 0;
        }

        h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }

        .table-container {
            flex: 1;
            overflow: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            height: 100%;
        }

        th {
            background-color: #4a7c59;
            color: white;
            padding: 12px 10px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #2d5a27;
            white-space: nowrap;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tr:hover {
            background-color: #e8f5e8;
        }

        .boton {
            background-color: #28a745;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: background-color 0.3s ease;
            display: inline-block;
        }

        .boton:hover {
            background-color: #218838;
        }

        .id-cell {
            font-weight: 600;
            color: #2d5a27;
        }

        .prioridad {
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-block;
            min-width: 60px;
            text-align: center;
        }

        .prioridad.alta {
            background-color: #dc3545;
            color: white;
        }

        .prioridad.media {
            background-color: #ffc107;
            color: #212529;
        }

        .prioridad.baja {
            background-color: #28a745;
            color: white;
        }

        .descripcion {
            max-width: 250px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .fecha {
            color: #6c757d;
            font-size: 13px;
        }

        .titulo {
            font-weight: 500;
            color: #2d5a27;
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
            }

            .header {
                padding: 20px;
            }

            h2 {
                font-size: 1.5rem;
            }

            table {
                font-size: 12px;
            }

            th, td {
                padding: 8px 6px;
            }

            .descripcion {
                max-width: 150px;
            }
        }

        .stats-bar {
            background-color: #f8f9fa;
            padding: 10px 30px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            flex-shrink: 0;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .stat-number {
            background-color: #28a745;
            color: white;
            padding: 4px 8px;
            border-radius: 3px;
            font-weight: 600;
            font-size: 12px;
        }

        .stat-label {
            color: #6c757d;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Lista de Tickets</h2>
        </div>
        
        <div class="stats-bar">
            <div class="stat-item">
                <span class="stat-number"><?= count($tickets) ?></span>
                <span class="stat-label">Total de Tickets</span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?= count(array_filter($tickets, fn($t) => strtolower($t['prioridad']) === 'alta')) ?></span>
                <span class="stat-label">Prioridad Alta</span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?= count(array_filter($tickets, fn($t) => strtolower($t['prioridad']) === 'media')) ?></span>
                <span class="stat-label">Prioridad Media</span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?= count(array_filter($tickets, fn($t) => strtolower($t['prioridad']) === 'baja')) ?></span>
                <span class="stat-label">Prioridad Baja</span>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente ID</th>
                        <th>Área ID</th>
                        <th>Usuario ID</th>
                        <th>Campaña ID</th>
                        <th>Identificador</th>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Prioridad</th>
                        <th>Fecha Creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td class="id-cell"><?= str_pad(esc($ticket['id']), 3, '0', STR_PAD_LEFT) ?></td>
                        <td><?= esc($ticket['cliente_id']) ?></td>
                        <td><?= esc($ticket['area_id']) ?></td>
                        <td><?= esc($ticket['usuario_id']) ?></td>
                        <td><?= esc($ticket['campana_id']) ?></td>
                        <td><?= esc($ticket['identificador']) ?></td>
                        <td class="titulo"><?= esc($ticket['titulo']) ?></td>
                        <td class="descripcion"><?= esc($ticket['descripcion']) ?></td>
                        <td>
                            <span class="prioridad <?= strtolower(esc($ticket['prioridad'])) ?>">
                                <?= esc($ticket['prioridad']) ?>
                            </span>
                        </td>
                        <td class="fecha"><?= date('d/m/Y', strtotime(esc($ticket['fecha_creacion']))) ?></td>
                        <td><a class="boton" href="<?= base_url('tickets/ver/' . $ticket['id']) ?>">Ver más</a></td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
