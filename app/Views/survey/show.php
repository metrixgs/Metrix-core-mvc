<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Responder Encuesta | Módulo Encuestas Metrix</title>
    <meta name="description" content="Sistema de control de encuestas y incidencias">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">
    
    <!-- Tailwind CSS CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #8DC63F 0%, #6ca82a 100%);
        }
        .btn-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .question-card {
            transition: all 0.3s ease;
        }
        .question-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }
        .custom-radio input[type="radio"] {
            display: none;
        }
        .custom-radio input[type="radio"] + label {
            position: relative;
            padding-left: 30px;
            cursor: pointer;
            display: block;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        .custom-radio input[type="radio"] + label:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 22px;
            height: 22px;
            border: 2px solid #8DC63F;
            border-radius: 50%;
            background: white;
        }
        .custom-radio input[type="radio"]:checked + label:after {
            content: '';
            position: absolute;
            left: 5px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #8DC63F;
            transition: all 0.3s ease;
        }
        .custom-radio input[type="radio"] + label:hover {
            color: #6ca82a;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <!-- HEADER: MENU + HERO SECTION -->
    <header>
        <nav class="bg-white shadow-md">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center py-4">
                    <div class="flex items-center">
                        <a href="/" class="flex items-center">
                            <span class="text-3xl font-bold text-green-500">METRIX</span>
                        </a>
                    </div>
                    <div class="lg:hidden">
                        <button id="menuToggle" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                    </div>
                  
                </div>
                <div id="mobileMenu" class="hidden lg:hidden py-2">
                    <a href="/encuestas/public/" class="block text-gray-900 hover:text-green-500 px-4 py-2 text-sm font-medium">Home</a>
                    <a href="/encuestas/public/survey/create" class="block text-gray-900 hover:text-green-500 px-4 py-2 text-sm font-medium">Crear Encuesta</a>
                    <a href="/encuestas/public/surveys" class="block text-gray-900 hover:text-green-500 px-4 py-2 text-sm font-medium">Listar Encuestas</a>
                    <a href="#" class="block text-gray-900 hover:text-green-500 px-4 py-2 text-sm font-medium">Control de Incidencias</a>
                </div>
            </div>
        </nav>

        <div class="gradient-bg text-white">
            <div class="container mx-auto px-4 py-12 text-center">
                <h1 class="text-4xl font-bold mb-2">Responder Encuesta</h1>
                <p class="text-xl">Gracias por participar en nuestra encuesta</p>
            </div>
        </div>
    </header>

    <!-- CONTENT -->
    <section class="container mx-auto px-4 py-10">
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-8 card-hover">
            <?php if(session()->getFlashdata('message')): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3 text-xl"></i>
                    <span><?= session()->getFlashdata('message') ?></span>
                </div>
            <?php endif; ?>

            <div class="mb-10 pb-6 border-b border-gray-200">
                <div class="flex flex-col md:flex-row">
                    <?php if(!empty($survey['image'])): ?>
                        <div class="md:w-1/3 mb-6 md:mb-0 md:pr-6">
                            <div class="rounded-lg overflow-hidden shadow-md h-full">
                                <img src="<?= base_url($survey['image']) ?>" alt="Imagen de la encuesta" class="w-full h-full object-cover">
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="<?= !empty($survey['image']) ? 'md:w-2/3' : 'w-full' ?>">
                        <h1 class="text-3xl font-bold mb-4 text-gray-800"><?= $survey['title'] ?></h1>
                        <div class="text-gray-600 text-lg leading-relaxed">
                            <?= $survey['description'] ?>
                        </div>
                    </div>
                </div>
            </div>

            <form action="<?= base_url('survey/' . $survey['id'] . '/storeResponse') ?>" method="post">
                <?= csrf_field() ?>

                <!-- Datos personales del encuestado -->
                <div class="mb-10 p-6 bg-green-50 rounded-lg border border-green-100 card-hover">
                    <h3 class="text-xl font-semibold mb-6 flex items-center text-gray-800">
                        <i class="fas fa-user-circle text-green-500 mr-3"></i>Sus datos
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block mb-2 font-medium text-gray-700">Nombre:</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input type="text" name="name" required class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                            </div>
                        </div>

                        <div>
                            <label class="block mb-2 font-medium text-gray-700">Email:</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input type="email" name="email" required class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preguntas de la encuesta -->
                <div class="space-y-8">
                    <h3 class="text-xl font-semibold mb-6 flex items-center text-gray-800">
                        <i class="fas fa-list-alt text-green-500 mr-3"></i>Preguntas
                    </h3>

                    <?php 
                    // Decodificar las preguntas desde el JSON
                    $questions = json_decode($survey['questions'], true);
                    
                    // Reconstruir el array de preguntas correctamente
                    $processedQuestions = [];
                    $currentQuestion = null;
                    
                    // Procesar el array para agrupar correctamente
                    foreach ($questions as $item) {
                        if (isset($item['text'])) {
                            // Es una nueva pregunta
                            if ($currentQuestion !== null) {
                                $processedQuestions[] = $currentQuestion;
                            }
                            $currentQuestion = $item;
                        } else {
                            // Es un atributo o opción de la pregunta actual
                            if (isset($item['type'])) {
                                $currentQuestion['type'] = $item['type'];
                            }
                            if (isset($item['options'])) {
                                if (!isset($currentQuestion['options'])) {
                                    $currentQuestion['options'] = [];
                                }
                                $currentQuestion['options'][] = $item['options'][0]; // Tomar la primera opción
                            }
                            // Añadir soporte para imágenes de preguntas si existe
                            if (isset($item['image'])) {
                                $currentQuestion['image'] = $item['image'];
                            }
                        }
                    }
                    
                    // Añadir la última pregunta
                    if ($currentQuestion !== null) {
                        $processedQuestions[] = $currentQuestion;
                    }
                    
                    if (!empty($processedQuestions)):
                        foreach ($processedQuestions as $index => $question):
                            $questionText = isset($question['text']) ? $question['text'] : 'Pregunta sin texto';
                            $questionType = isset($question['type']) ? $question['type'] : 'text';
                    ?>
                        <div class="p-6 bg-white rounded-lg border border-gray-200 shadow-sm question-card">
                            <div class="flex flex-col md:flex-row">
                                <?php if (isset($question['image']) && !empty($question['image'])): ?>
                                    <div class="md:w-1/3 mb-4 md:mb-0 md:pr-6">
                                        <div class="rounded-lg overflow-hidden shadow-md">
                                            <img src="<?= base_url($question['image']) ?>" alt="Imagen de la pregunta" class="w-full">
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="<?= (isset($question['image']) && !empty($question['image'])) ? 'md:w-2/3' : 'w-full' ?>">
                                    <h3 class="text-xl font-semibold mb-4 text-gray-800 flex items-start">
                                        <span class="flex-shrink-0 bg-green-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3">
                                            <?= $index + 1 ?>
                                        </span>
                                        <?= $questionText ?>
                                    </h3>
                                    
                                    <?php if($questionType === 'text'): ?>
                                        <!-- Pregunta de respuesta abierta -->
                                        <div class="mt-4">
                                            <textarea 
                                                name="answers[<?= $index ?>][response]" 
                                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition" 
                                                rows="5"
                                                placeholder="Escribe tu respuesta aquí..."
                                                required
                                            ></textarea>
                                            <input type="hidden" name="answers[<?= $index ?>][question]" value="<?= htmlspecialchars($questionText) ?>">
                                            <input type="hidden" name="answers[<?= $index ?>][type]" value="text">
                                        </div>

                                    <?php elseif($questionType === 'multiple' && isset($question['options']) && !empty($question['options'])): ?>
                                        <!-- Pregunta de selección múltiple -->
                                        <div class="mt-4 space-y-1">
                                            <?php $letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H']; ?>
                                            <?php foreach($question['options'] as $optIndex => $option): ?>
                                                <div class="custom-radio">
                                                    <input 
                                                        type="radio" 
                                                        id="opt_<?= $index ?>_<?= $optIndex ?>" 
                                                        name="answers[<?= $index ?>][response]" 
                                                        value="<?= htmlspecialchars($option) ?>"
                                                        required
                                                    >
                                                    <label for="opt_<?= $index ?>_<?= $optIndex ?>" class="py-2">
                                                        <span class="text-green-800 font-medium mr-2"><?= $letters[$optIndex] ?>.</span> 
                                                        <?= $option ?>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                            <input type="hidden" name="answers[<?= $index ?>][question]" value="<?= htmlspecialchars($questionText) ?>">
                                            <input type="hidden" name="answers[<?= $index ?>][type]" value="multiple">
                                        </div>
                                    <?php else: ?>
                                        <!-- Opción por defecto si el tipo no es válido o las opciones no están definidas -->
                                        <div class="mt-4">
                                            <textarea 
                                                name="answers[<?= $index ?>][response]" 
                                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition" 
                                                rows="5"
                                                placeholder="Escribe tu respuesta aquí..."
                                                required
                                            ></textarea>
                                            <input type="hidden" name="answers[<?= $index ?>][question]" value="<?= htmlspecialchars($questionText) ?>">
                                            <input type="hidden" name="answers[<?= $index ?>][type]" value="text">
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <div class="p-6 bg-yellow-50 border border-yellow-200 rounded-lg flex items-center">
                            <i class="fas fa-exclamation-circle text-yellow-500 mr-3 text-xl"></i>
                            <p>No hay preguntas disponibles para esta encuesta.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mt-10 pt-6 border-t border-gray-200">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg shadow-md transition btn-hover flex items-center">
                        <i class="fas fa-paper-plane mr-2"></i> Enviar respuesta
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-gray-800 text-white py-10 mt-20">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-6 md:mb-0">
                    <div class="flex items-center">
                        <span class="text-2xl font-bold text-green-500 mr-2">METRIX</span>
                        <span class="text-gray-400">| Encuestas e Incidencias</span>
                    </div>
                </div>
                <div class="text-center md:text-right">
                    <p>&copy; <?= date('Y') ?> Sistema Encuestas Metrix. Todos los derechos reservados.</p>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-700 flex justify-center space-x-6">
                <a href="#" class="text-gray-400 hover:text-white">
                    <i class="fab fa-facebook-f text-xl"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-white">
                    <i class="fab fa-twitter text-xl"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-white">
                    <i class="fab fa-linkedin-in text-xl"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-white">
                    <i class="fab fa-instagram text-xl"></i>
                </a>
            </div>
        </div>
    </footer>

    <!-- SCRIPTS -->
    <script>
        document.getElementById("menuToggle").addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.classList.toggle('hidden');
        });
    </script>
</body>
</html>