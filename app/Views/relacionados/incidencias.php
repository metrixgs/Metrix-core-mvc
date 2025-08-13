<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= esc($titulo_pagina) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="p-4">

   <h3 class="mb-3"><?= esc($titulo_pagina) ?></h3>

    <?php if (!empty($registros)): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="table-light">
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
        <div class="alert alert-warning">No hay registros disponibles.</div>
    <?php endif; ?>

</body>
</html>
