<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Respuestas de la Encuesta: <?= $survey['title'] ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --accent-color: linear-gradient(135deg, #7DB62F 0%, #5c981a 100%);
            --text-color: #333;
        }
    </style>
</head>
<body class="bg-gray-100 p-6 font-sans">

    <div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold mb-4 text-gray-800">Respuestas de la Encuesta: <?= $survey['title'] ?></h1>
        <p class="text-gray-600 mb-6"><?= $survey['description'] ?></p>

        <!-- Tabla de respuestas -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead class="bg-gradient-to-r from-[#7DB62F] to-[#5c981a] text-white">
                    <tr>
                        <th class="px-6 py-3 text-left">Nombre</th>
                        <th class="px-6 py-3 text-left">Correo Electrónico</th>
                        <th class="px-6 py-3 text-left">Fecha de Respuesta</th>
                        <?php 
                        // Decodificar las preguntas de la encuesta
                        $questions = json_decode($survey['questions'], true);
                        foreach ($questions as $question):
                        ?>
                            <th class="px-6 py-3 text-left"><?= htmlspecialchars($question['text']) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <?php foreach ($responses as $response): ?>
                        <tr class="border-t border-gray-200 hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4"><?= htmlspecialchars($response['name']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($response['email']) ?></td>
                            <td class="px-6 py-4"><?= date('d/m/Y H:i', strtotime($response['created_at'])) ?></td>

                            <?php
                            // Decodificar las respuestas
                            $answers = json_decode($response['answers'], true);
                            foreach ($questions as $index => $question):
                                $answer = isset($answers[$index]) ? htmlspecialchars($answers[$index]['response']) : 'No respondida';
                            ?>
                                <td class="px-6 py-4"><?= $answer ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-between items-center">
            <a href="<?= base_url('/surveys') ?>" class="text-[#7DB62F] hover:underline text-lg">Regresar a todas las encuestas</a>
            <a href="javascript:window.print();" class="bg-[#7DB62F] hover:bg-[#5c981a] text-white px-6 py-3 rounded-lg shadow-md text-lg transition">Imprimir Respuestas</a>
        </div>
    </div>

</body>
</html>
