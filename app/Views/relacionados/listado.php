<h3><?= esc($titulo) ?> de la campaña #<?= esc($campana_id) ?></h3>

<?php if (!empty($items)): ?>
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <?php foreach (array_keys($items[0]) as $col): ?>
                    <th><?= esc($col) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $row): ?>
                <tr>
                    <?php foreach ($row as $value): ?>
                        <td><?= esc($value) ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No hay registros.</p>
<?php endif; ?>
