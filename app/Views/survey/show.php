<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Responder Encuesta | Módulo Encuestas Metrix</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto px-4 py-12">
        <div class="bg-white p-8 rounded-lg shadow">
            <h1 class="text-3xl font-bold mb-4 text-green-700"><?= esc($survey['title']) ?></h1>
            <p class="mb-6 text-gray-600"><?= esc($survey['description']) ?></p>

            <form action="<?= base_url('survey/' . $survey['id'] . '/storeResponse') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="grid grid-cols-1 gap-8">
                <?php
                    $questions = json_decode($survey['questions'], true);
                    if ($questions):
                        foreach ($questions as $index => $question):
                            $questionText = $question['text'] ?? 'Pregunta sin texto';
                            $type = $question['type'] ?? 'text';
                ?>
                    <div class="p-6 bg-gray-50 rounded border">
                        <h2 class="text-lg font-semibold mb-4">
                            <?= ($index + 1) . '. ' . esc($questionText) ?>
                        </h2>

                        <?php if ($type === 'text'): ?>
                            <textarea name="answers[<?= $index ?>][response]" required rows="4"
                                class="w-full border px-4 py-2 rounded focus:ring-green-500 focus:outline-none"
                                placeholder="Tu respuesta..."></textarea>

                        <?php elseif ($type === 'multiple' && isset($question['options']) && is_array($question['options'])): ?>
                            <div class="space-y-2">
                                <?php foreach ($question['options'] as $optIndex => $option): ?>
                                    <div class="flex items-start">
                                        <input type="radio"
                                            id="opt_<?= $index ?>_<?= $optIndex ?>"
                                            name="answers[<?= $index ?>][response]"
                                            value="<?= htmlspecialchars(is_array($option) ? $option['nombre'] : $option) ?>"
                                            required class="mt-1 mr-2">
                                        <label for="opt_<?= $index ?>_<?= $optIndex ?>" class="text-gray-700">
                                            <span class="text-green-800 font-medium mr-2"><?= chr(65 + $optIndex) ?>.</span>
                                            <?= esc(is_array($option) ? $option['nombre'] : $option) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                        <?php elseif ($type === 'photo'): ?>
                            <div>
                                <input type="file" name="photo_<?= $index ?>" accept="image/*" capture="environment"
                                    class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4
                                           file:rounded-full file:border-0 file:text-sm file:font-semibold
                                           file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                                <p class="text-sm text-gray-500 mt-1">Toma una foto desde la cámara.</p>
                            </div>

                        <?php else: ?>
                            <p class="text-red-500">Tipo de pregunta no válido o sin opciones.</p>
                        <?php endif; ?>

                        <!-- Campos comunes -->
                        <input type="hidden" name="answers[<?= $index ?>][question]" value="<?= esc($questionText) ?>">
                        <input type="hidden" name="answers[<?= $index ?>][type]" value="<?= $type ?>">
                    </div>
                <?php endforeach; endif; ?>
                </div>

                <div class="mt-8">
                    <button type="submit"
                        class="bg-green-600 text-white px-6 py-3 rounded shadow hover:bg-green-700 transition">
                        <i class="fas fa-paper-plane mr-2"></i>Enviar Respuestas
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
