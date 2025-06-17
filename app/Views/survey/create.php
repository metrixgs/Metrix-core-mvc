<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Encuesta | Módulo Encuestas Metrix</title>
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
        .logo {
            max-height: 60px;
            width: auto;
        }
        .question-item {
            transition: all 0.3s ease;
        }
        .question-item:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.07);
        }
        .custom-file-input {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }
        .custom-file-input input[type=file] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
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
                        <a href=" <?= base_url('/surveys') ?>" class="flex items-center">
                            <span class="text-3xl font-bold text-green-500">METRIX</span>
                        </a>
                    </div>
                    <div class="lg:hidden">
                        <button id="menuToggle" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                    </div>
                    <div class="hidden lg:flex lg:items-center lg:space-x-8">
                        <a href="<?= base_url('/surveys') ?>" class="text-gray-900 hover:text-green-500 px-3 py-2 text-sm font-medium">Home</a>
                        <a href="<?= base_url('/survey/create') ?>" class="text-green-500 border-b-2 border-green-500 px-3 py-2 text-sm font-medium">Crear Encuesta</a>
                        <a href="<?= base_url('/surveys') ?>" class="text-gray-900 hover:text-green-500 px-3 py-2 text-sm font-medium">Listar Encuestas</a>
                        <a href="#" class="text-gray-900 hover:text-green-500 px-3 py-2 text-sm font-medium">Control de Incidencias</a>
                    </div>
                </div>
                <div id="mobileMenu" class="hidden lg:hidden py-2">
                    <a href="<?= base_url('surveys') ?>" class="block text-gray-900 hover:text-green-500 px-4 py-2 text-sm font-medium">Home</a>

                    <a href="<?= base_url('survey/create') ?>" class="block text-green-500 font-medium px-4 py-2 text-sm">Crear Encuesta</a>
                    <a href="/encuestas/public/surveys" class="block text-gray-900 hover:text-green-500 px-4 py-2 text-sm font-medium">Listar Encuestas</a>
                    <a href="#" class="block text-gray-900 hover:text-green-500 px-4 py-2 text-sm font-medium">Control de Incidencias</a>
                </div>
            </div>
        </nav>

        <div class="gradient-bg text-white">
            <div class="container mx-auto px-4 py-12 text-center">
                <h1 class="text-4xl font-bold mb-2">Crear Nueva Encuesta</h1>
                <p class="text-xl">Diseña tu encuesta personalizada con múltiples tipos de preguntas</p>
            </div>
        </div>
    </header>

    <!-- CONTENT -->
    <section class="container mx-auto px-4 py-10">
        <div class="bg-white rounded-lg shadow-lg p-8 card-hover max-w-5xl mx-auto">
            <form action="<?= base_url('survey/store') ?>" method="post" enctype="multipart/form-data" class="space-y-6">
                <?= csrf_field() ?>
                
                <div class="border-b pb-6 mb-6">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800 flex items-center">
                        <i class="fas fa-edit mr-3 text-green-500"></i>Información de la Encuesta
                    </h2>
                    
                    <!-- Título -->
                    <div class="mb-6">
                        <label class="block mb-2 font-medium text-gray-700">Título de la Encuesta:</label>
                        <input type="text" name="title" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                    </div>

                    <!-- Descripción -->
                    <div class="mb-6">
                        <label class="block mb-2 font-medium text-gray-700">Descripción:</label>
                        <textarea name="description" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition" rows="4"></textarea>
                    </div>

                    <!-- Imagen de la encuesta -->
                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Imagen de la Encuesta:</label>
                        <div class="relative">
                            <div class="flex items-center">
                                <div class="relative overflow-hidden inline-block">
                                    <button type="button" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition btn-hover flex items-center">
                                        <i class="fas fa-cloud-upload-alt mr-2"></i> Seleccionar Imagen
                                    </button>
                                    <input type="file" name="image" id="imageInput" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                </div>
                                <span id="fileNameDisplay" class="ml-3 text-gray-600 text-sm"></span>
                            </div>
                            <div class="mt-3">
                                <img id="imagePreview" class="hidden mt-2 max-h-64 rounded-lg border border-gray-200" />
                            </div>
                        </div>
                    </div>
                </div>

                <h2 class="text-2xl font-bold mb-6 text-gray-800 flex items-center">
                    <i class="fas fa-list-alt mr-3 text-green-500"></i>Preguntas
                </h2>
                
                <!-- Contenedor de preguntas -->
                <div id="questionsContainer" class="space-y-6"></div>

                <button type="button" id="addQuestionBtn" class="mt-6 bg-green-500 hover:bg-green-600 text-white px-5 py-3 rounded-lg transition btn-hover flex items-center">
                    <i class="fas fa-plus-circle mr-2"></i> Agregar Pregunta
                </button>

                <!-- Botón enviar -->
                <div class="pt-8 border-t border-gray-200 mt-10">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg transition btn-hover flex items-center">
                        <i class="fas fa-save mr-2"></i> Guardar Encuesta
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
        // Script para el menú móvil
        document.getElementById("menuToggle").addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.classList.toggle('hidden');
        });

        // Scripts para la gestión de preguntas
        let questionCount = 0;
        
        function toggleOptions(select) {
            const container = select.closest('.question-item');
            const options = container.querySelector('.options-container');
            if (select.value === 'multiple') {
                options.style.display = 'block';
            } else {
                options.style.display = 'none';
            }
        }

        function addOption(button, questionIndex) {
            const optionsList = button.closest('.options-container').querySelector('.options-list');
            const optionContainer = document.createElement('div');
            optionContainer.className = 'relative mb-2';
            
            const input = document.createElement('input');
            input.type = 'text';
            input.name = `questions[${questionIndex}][options][]`;
            input.placeholder = 'Opción';
            input.required = true;
            input.className = 'block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition pr-10';
            
            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'absolute right-2 top-1/2 transform -translate-y-1/2 text-red-500 hover:text-red-700';
            removeButton.innerHTML = '<i class="fas fa-times"></i>';
            removeButton.onclick = function() { optionContainer.remove(); };
            
            optionContainer.appendChild(input);
            optionContainer.appendChild(removeButton);
            optionsList.appendChild(optionContainer);
        }

        function removeQuestion(button) {
            const questionItem = button.closest('.question-item');
            questionItem.classList.add('opacity-0');
            setTimeout(() => {
                questionItem.remove();
            }, 300);
        }

        document.getElementById('addQuestionBtn').addEventListener('click', function () {
            const index = questionCount++;
            const container = document.createElement('div');
            container.className = 'question-item border border-gray-200 p-6 rounded-lg bg-gray-50 relative transition-all duration-300';

            container.innerHTML = `
                <button type="button" class="absolute top-4 right-4 text-red-500 hover:text-red-700 w-8 h-8 flex items-center justify-center rounded-full bg-white shadow-sm" onclick="removeQuestion(this)">
                    <i class="fas fa-trash-alt"></i>
                </button>

                <div class="mb-4">
                    <label class="block mb-2 font-medium text-gray-700">Texto de la pregunta:</label>
                    <input type="text" name="questions[${index}][text]" required placeholder="Escriba su pregunta aquí"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                </div>

                <div class="mb-4">
                    <label class="block mb-2 font-medium text-gray-700">Tipo de pregunta:</label>
                    <select name="questions[${index}][type]" class="question-type w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                            onchange="toggleOptions(this)">
                        <option value="text">Respuesta abierta</option>
                        <option value="multiple">Selección múltiple</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 font-medium text-gray-700">Imagen de la pregunta (opcional):</label>
                    <div class="relative">
                        <div class="flex items-center">
                            <div class="relative overflow-hidden inline-block">
                                <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition flex items-center">
                                    <i class="fas fa-image mr-2"></i> Seleccionar Imagen
                                </button>
                                <input type="file" name="questions[${index}][image]" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer question-image-input">
                            </div>
                            <span class="file-name-display ml-3 text-gray-600 text-sm"></span>
                        </div>
                        <div class="mt-3">
                            <img class="question-image-preview hidden mt-2 max-h-64 rounded-lg border border-gray-200" />
                        </div>
                    </div>
                </div>

                <div class="options-container" style="display: none;">
                    <label class="block mb-2 font-medium text-gray-700">Opciones:</label>
                    <div class="options-list mb-3"></div>
                    <button type="button" onclick="addOption(this, ${index})"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition flex items-center">
                        <i class="fas fa-plus mr-2"></i> Agregar opción
                    </button>
                </div>
            `;

            document.getElementById('questionsContainer').appendChild(container);
        });

        // Vista previa de imagen principal
        document.getElementById('imageInput').addEventListener('change', function (event) {
            const preview = document.getElementById('imagePreview');
            const fileNameDisplay = document.getElementById('fileNameDisplay');
            const file = event.target.files[0];

            if (file) {
                fileNameDisplay.textContent = file.name;
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                fileNameDisplay.textContent = '';
                preview.classList.add('hidden');
                preview.src = '';
            }
        });

        // Vista previa de imágenes de preguntas
        document.addEventListener('change', function (event) {
            if (event.target && event.target.classList.contains('question-image-input')) {
                const container = event.target.closest('.relative');
                const preview = container.querySelector('.question-image-preview');
                const fileNameDisplay = container.querySelector('.file-name-display');
                const file = event.target.files[0];

                if (file) {
                    fileNameDisplay.textContent = file.name;
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    fileNameDisplay.textContent = '';
                    preview.classList.add('hidden');
                    preview.src = '';
                }
            }
        });

        // Agregar la primera pregunta automáticamente
        document.getElementById('addQuestionBtn').click();
    </script>
</body>
</html>