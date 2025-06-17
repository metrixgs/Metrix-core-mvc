<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Encuestas</title>
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: linear-gradient(135deg, #7DB62F 0%, #5c981a 100%);
            --primary-color: #7DB62F;
            --secondary-color: #5c981a;
            --success-color: #4cc9f0;
            --text-color: #333;
            --light-gray: #f8f9fa;
            --border-radius: 8px;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: var(--text-color);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            background-color: white;
            box-shadow: var(--shadow);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo i {
            font-size: 28px;
        }

        .search-bar {
            flex-grow: 1;
            max-width: 500px;
            margin: 0 20px;
            position: relative;
        }

        .search-bar input {
            width: 100%;
            padding: 10px 15px;
            padding-left: 40px;
            border-radius: var(--border-radius);
            border: 1px solid #ddd;
            font-size: 16px;
        }

        .search-bar i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .actions {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 10px 15px;
            border-radius: var(--border-radius);
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #8DC63F 0%, #6ca82a 100%);
            color: white;
        }
        
        .btn-success:hover {
            background: linear-gradient(135deg, #7DB62F 0%, #5c981a 100%);
        }

        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
        }

        .btn-outline:hover {
            background-color: var(--primary-color);
            color: white;
        }

        main {
            margin-top: 30px;
        }

        .page-title {
            margin-bottom: 20px;
            color: var(--text-color);
            font-size: 28px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .survey-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .survey-card {
            background-color: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .survey-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            padding: 15px;
            background-color: var(--accent-color);
            color: white;
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--primary-color);
        }

        .card-description {
            color: #666;
            margin-bottom: 15px;
            height: 60px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }

        .card-meta {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            color: #888;
            margin-bottom: 15px;
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            background-color: var(--light-gray);
            border-top: 1px solid #eee;
        }

        .no-surveys {
            background-color: white;
            padding: 30px;
            border-radius: var(--border-radius);
            text-align: center;
            box-shadow: var(--shadow);
        }

        .no-surveys i {
            font-size: 48px;
            color: #ccc;
            margin-bottom: 15px;
        }

        .no-surveys h3 {
            margin-bottom: 10px;
            color: #666;
        }

        /* Toast para notificaciones */
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #333;
            color: white;
            padding: 12px 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toast.show {
            opacity: 1;
        }

        .toast.success {
            background-color: #28a745;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 15px;
            }
            
            .search-bar {
                width: 100%;
                max-width: none;
                margin: 15px 0;
            }
            
            .survey-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <i class="fas fa-poll"></i>
                    <span>Metrix </span>
                </div>
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Buscar encuestas...">
                </div>
                <div class="actions">
                    <button class="btn btn-outline" id="sortButton">
                        <i class="fas fa-sort"></i> Ordenar
                    </button>
                    <button class="btn btn-primary" onclick="window.location.href=' <?= base_url('survey/create') ?>'">
                        <i class="fas fa-plus"></i> Nueva Encuesta
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="container">
        <h1 class="page-title">
            <i class="fas fa-list"></i>
            Mis Encuestas
        </h1>

        <div id="surveyContainer">
            <?php if (empty($surveys)): ?>
                <div class="no-surveys">
                    <i class="fas fa-clipboard-list"></i>
                    <h3>No hay encuestas disponibles</h3>
                    <p>Crea tu primera encuesta para comenzar</p>
                    <button class="btn btn-primary" style="margin-top: 15px" onclick="window.location.href='<?= base_url('survey/create') ?>'">
                        <i class="fas fa-plus"></i> Crear Encuesta
                    </button>
                </div>
            <?php else: ?>
                <div class="survey-grid">
                    <?php foreach ($surveys as $survey): ?>
                        <div class="survey-card">
                            <div class="card-header">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <div class="card-body">
                                <h3 class="card-title"><?= esc($survey['title']) ?></h3>
                                <p class="card-description"><?= esc($survey['description']) ?></p>
                                <div class="card-meta">
                                    <span><i class="far fa-calendar-alt"></i> Creada: <?= isset($survey['created_at']) ? date('d/m/Y', strtotime($survey['created_at'])) : 'N/A' ?></span>
                                    <span><i class="fas fa-chart-bar"></i> Respuestas: <?= isset($survey['responses']) ? $survey['responses'] : '0' ?></span>
                                </div>
                                <button class="btn btn-primary" style="width: 100%;" onclick="window.location.href='/survey/<?= $survey['id'] ?>'">
                                    <i class="fas fa-external-link-alt"></i> Abrir Encuesta
                                </button>
                            </div>
                            
<div class="card-footer">
    <button class="btn btn-outline" onclick="copyLink('/survey/<?= $survey['id'] ?>')">
        <i class="fas fa-share-alt"></i> Compartir
    </button>
    <button class="btn btn-success" onclick="window.location.href='<?= base_url('survey/' . $survey['id'] . '/responses') ?>'">
        <i class="fas fa-chart-bar"></i> Respuestas
    </button>
    <button class="btn btn-success" onclick="window.location.href='<?= base_url('survey/exportResponses/' . $survey['id']) ?>'">
        <i class="fas fa-file-excel"></i> Exportar Respuestas
    </button>
</div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <div id="toast" class="toast"></div>

    <script>
        // Función para copiar el enlace al portapapeles
        function copyLink(url) {
            const fullUrl = window.location.origin + url;
            
            // Usar la API Clipboard moderna si está disponible
            if (navigator.clipboard) {
                navigator.clipboard.writeText(fullUrl)
                    .then(() => showToast('¡Enlace copiado con éxito!', 'success'))
                    .catch(err => {
                        console.error('Error al copiar: ', err);
                        fallbackCopyMethod(fullUrl);
                    });
            } else {
                fallbackCopyMethod(fullUrl);
            }
        }
        
        // Método de respaldo para navegadores que no soportan la API Clipboard
        function fallbackCopyMethod(text) {
            const tempInput = document.createElement('input');
            document.body.appendChild(tempInput);
            tempInput.value = text;
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            showToast('¡Enlace copiado con éxito!', 'success');
        }
        
        // Mostrar una notificación toast
        function showToast(message, type = '') {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = 'toast show';
            
            if (type) {
                toast.classList.add(type);
            }
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }
        
        // Búsqueda en tiempo real
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.survey-card');
            
            cards.forEach(card => {
                const title = card.querySelector('.card-title').textContent.toLowerCase();
                const description = card.querySelector('.card-description').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || description.includes(searchTerm)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
        
        // Ordenar encuestas (ejemplo básico)
        let sortAscending = true;
        document.getElementById('sortButton').addEventListener('click', function() {
            const container = document.querySelector('.survey-grid');
            const cards = Array.from(container.querySelectorAll('.survey-card'));
            
            cards.sort((a, b) => {
                const titleA = a.querySelector('.card-title').textContent;
                const titleB = b.querySelector('.card-title').textContent;
                
                if (sortAscending) {
                    return titleA.localeCompare(titleB);
                } else {
                    return titleB.localeCompare(titleA);
                }
            });
            
            // Vaciar y volver a llenar el contenedor
            container.innerHTML = '';
            cards.forEach(card => container.appendChild(card));
            
            // Cambiar el icono y el orden para la próxima vez
            const icon = this.querySelector('i');
            if (sortAscending) {
                icon.className = 'fas fa-sort-down';
                showToast('Ordenado de A a Z');
            } else {
                icon.className = 'fas fa-sort-up';
                showToast('Ordenado de Z a A');
            }
            
            sortAscending = !sortAscending;
        });
    </script>
</body>
</html>