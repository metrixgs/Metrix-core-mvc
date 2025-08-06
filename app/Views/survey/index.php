<!-- Font Awesome para iconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Normalize.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">

<style>
 :root {
    --primary-color: #7DB62F;
    --secondary-color: #5c981a;
    --accent-color: linear-gradient(135deg, #7DB62F 0%, #5c981a 100%);
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

/* ✅ Layout para footer al fondo */
html, body {
    height: 100%;
    overflow-x: hidden;
}

body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    background-color: #f5f7fa;
    color: var(--text-color);
    line-height: 1.6;
    padding-top: 120px;
}

main {
    flex: 1;
    margin-top: 120px;
    padding-bottom: 200px;
    min-height: 100vh; /* 👈 asegura altura mínima */
}

footer.footer {
    background-color: #f1f1f1;
    text-align: center;
    padding: 10px 0;
    color: #666;
    font-size: 14px;
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
    flex-wrap: wrap;
    gap: 15px;
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
    position: relative;
}

.search-bar input {
    width: 100%;
    padding: 10px 15px 10px 40px;
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
    flex-wrap: wrap;
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
    background-image: var(--accent-color);
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
    gap: 10px;
    flex-wrap: wrap;
    justify-content: space-between;
    padding: 15px;
    padding-bottom: 25px; /* 👈 extra padding */
    background-color: var(--light-gray);
    border-top: 1px solid #eee;
}

.card-footer button {
    flex: 1;
    min-width: 100px;
    white-space: nowrap;
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

/* Toast de notificación */
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
    .survey-grid {
        grid-template-columns: 1fr;
    }
    .search-bar {
        width: 100%;
        margin: 15px 0;
    }
}

</style>
</head>

<body>
<header>
    <div class="container">
        <div class="header-content">
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Buscar encuestas...">
            </div>
            <div class="actions">
                <button class="btn btn-outline" id="sortButton">
                    <i class="fas fa-sort"></i> Ordenar
                </button>
                <button class="btn btn-primary" onclick="window.location.href='<?= base_url('survey/create') ?>'">
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

<footer class="footer">
    <p>&copy; 2023 Sistema de Encuestas</p>
</footer>

<div id="toast" class="toast"></div>

<script>
    function copyLink(url) {
        const fullUrl = window.location.origin + url;
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

    function fallbackCopyMethod(text) {
        const tempInput = document.createElement('input');
        document.body.appendChild(tempInput);
        tempInput.value = text;
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        showToast('¡Enlace copiado con éxito!', 'success');
    }

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

    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const cards = document.querySelectorAll('.survey-card');
        cards.forEach(card => {
            const title = card.querySelector('.card-title').textContent.toLowerCase();
            const description = card.querySelector('.card-description').textContent.toLowerCase();
            card.style.display = title.includes(searchTerm) || description.includes(searchTerm) ? '' : 'none';
        });
    });

    let sortAscending = true;
    document.getElementById('sortButton').addEventListener('click', function () {
        const container = document.querySelector('.survey-grid');
        const cards = Array.from(container.querySelectorAll('.survey-card'));
        cards.sort((a, b) => {
            const titleA = a.querySelector('.card-title').textContent;
            const titleB = b.querySelector('.card-title').textContent;
            return sortAscending ? titleA.localeCompare(titleB) : titleB.localeCompare(titleA);
        });
        container.innerHTML = '';
        cards.forEach(card => container.appendChild(card));
        const icon = this.querySelector('i');
        icon.className = sortAscending ? 'fas fa-sort-down' : 'fas fa-sort-up';
        showToast(sortAscending ? 'Ordenado de A a Z' : 'Ordenado de Z a A');
        sortAscending = !sortAscending;
    });
</script>
