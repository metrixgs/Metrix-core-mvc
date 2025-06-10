<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Encuesta Respondida con Éxito</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #8DC63F 0%, #6ca82a 100%);
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .btn-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="bg-gray-100 p-6">

    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md card-hover">
        <div class="text-center">
            <i class="fas fa-check-circle text-green-500 text-5xl mb-4"></i>
            <h1 class="text-3xl font-bold text-center mb-4 text-gray-800">¡Gracias por responder la encuesta!</h1>
            <p class="text-center text-gray-600">Tu respuesta ha sido registrada con éxito.</p>
            
            <div class="mt-6 text-center">
                <a href="/encuestas/public/" class="inline-block gradient-bg text-white px-8 py-3 rounded-lg shadow-md btn-hover">
                    <i class="fas fa-home mr-2"></i> Volver a la página principal
                </a>
            </div>
        </div>
    </div>

</body>
</html>