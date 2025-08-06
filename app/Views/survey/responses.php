<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Respuestas de la Encuesta: <?= htmlspecialchars($survey['title']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        img.thumb {
            max-width: 120px;
            max-height: 120px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body class="bg-gray-100 p-6 font-sans">

<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-lg">
    <h1 class="text-3xl font-bold mb-4 text-gray-800">
        Respuestas de la Encuesta: <?= htmlspecialchars($survey['title']) ?>
    </h1>
    <p class="text-gray-600 mb-6"><?= htmlspecialchars($survey['description']) ?></p>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead class="bg-gradient-to-r from-[#7DB62F] to-[#5c981a] text-white">
                <tr>
                    <th class="px-6 py-3 text-left">Nombre</th>
                    <th class="px-6 py-3 text-left">Correo Electrónico</th>
                    <th class="px-6 py-3 text-left">Fecha de Respuesta</th>
                    <?php 
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
                        $answersDecoded = json_decode($response['answers'], true);
                        $userAnswers = [];

                        // Soportar diferentes formatos
                        if (isset($answersDecoded['respuestas']) && is_array($answersDecoded['respuestas'])) {
                            $userAnswers = $answersDecoded['respuestas'];
                        } elseif (is_array($answersDecoded)) {
                            $userAnswers = $answersDecoded;
                        }

                        foreach ($questions as $index => $question):
                            $answerValue = '<span class="text-gray-400 italic">No respondida</span>';

                            // Intentar encontrar la mejor coincidencia por texto
                            $found = false;
                            foreach ($userAnswers as $ua) {
                                if (!isset($ua['pregunta'])) continue;
                                // Match flexible insensible a mayúsculas y espacios
                                if (strcasecmp(trim($ua['pregunta']), trim($question['text'])) === 0) {
                                    $respuesta = $ua['respuesta'] ?? '';
                                    $answerValue = renderAnswer($respuesta);
                                    $found = true;
                                    break;
                                }
                            }

                            // Si no encontró por texto, probar por índice (viejo formato)
                            if (!$found && isset($userAnswers[$index]['respuesta'])) {
                                $respuesta = $userAnswers[$index]['respuesta'];
                                $answerValue = renderAnswer($respuesta);
                            }
                        ?>
                            <td class="px-6 py-4"><?= $answerValue ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-between items-center">
        <a href="<?= base_url('/surveys') ?>" class="text-[#7DB62F] hover:underline text-lg">
            Regresar a todas las encuestas
        </a>
        <a href="javascript:window.print();" class="bg-[#7DB62F] hover:bg-[#5c981a] text-white px-6 py-3 rounded-lg shadow-md text-lg transition">
            Imprimir Respuestas
        </a>
    </div>
</div>

</body>
</html>

<?php
function renderAnswer($respuesta) {
    if (is_array($respuesta)) {
        if (isset($respuesta['nombre'])) {
            return htmlspecialchars($respuesta['nombre']);
        } elseif (isset($respuesta['texto'])) {
            return htmlspecialchars($respuesta['texto']);
        } elseif (isset($respuesta['base64'])) {
            return '<img class="thumb" src="' . htmlspecialchars($respuesta['base64']) . '" alt="Foto">';
        } elseif (isset($respuesta['url']) && str_starts_with($respuesta['url'], 'http')) {
            return '<a class="text-blue-600 underline" href="' . htmlspecialchars($respuesta['url']) . '" target="_blank">Ver imagen</a>';
        } else {
            return htmlspecialchars(json_encode($respuesta));
        }
    } elseif (is_string($respuesta)) {
        return htmlspecialchars($respuesta);
    }

    return '<span class="text-gray-400 italic">No respondida</span>';
}
?>
