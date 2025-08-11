<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Crear Tipo de Usuario<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link href="<?= base_url('assets/css/select2.min.css') ?>" rel="stylesheet">
<style>
.crear-tipo-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px 0;
}

.crear-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    overflow: hidden;
}

.crear-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 25px;
    position: relative;
    overflow: hidden;
}

.crear-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
    opacity: 0.1;
}

.crear-header h1 {
    margin: 0;
    font-size: 2rem;
    font-weight: 700;
    position: relative;
    z-index: 1;
}

.crear-header p {
    margin: 10px 0 0 0;
    opacity: 0.9;
    font-size: 1rem;
    position: relative;
    z-index: 1;
}

.form-section {
    background: white;
    border-radius: 10px;
    padding: 25px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    border-left: 4px solid #667eea;
}

.section-title {
    color: #333;
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-title i {
    color: #667eea;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    display: block;
}

.form-control {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 12px 15px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    width: 100%;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    outline: none;
}

.form-text {
    font-size: 0.85rem;
    color: #6c757d;
    margin-top: 5px;
}

.categoria-option {
    display: flex;
    align-items: center;
    padding: 10px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    margin-bottom: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
}

.categoria-option:hover {
    border-color: #667eea;
    background: #f8f9ff;
}

.categoria-option.selected {
    border-color: #667eea;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.categoria-option input[type="radio"] {
    margin-right: 15px;
    transform: scale(1.2);
}

.categoria-info h4 {
    margin: 0 0 5px 0;
    font-size: 1rem;
    font-weight: 600;
}

.categoria-info p {
    margin: 0;
    font-size: 0.85rem;
    opacity: 0.8;
}

.nivel-selector {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 10px;
    margin-top: 10px;
}

.nivel-option {
    text-align: center;
    padding: 15px 10px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
}

.nivel-option:hover {
    border-color: #667eea;
    background: #f8f9ff;
}

.nivel-option.selected {
    border-color: #667eea;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.nivel-number {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 5px;
}

.nivel-label {
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.permisos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.permiso-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    border: 1px solid #e9ecef;
}

.permiso-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
}

.permiso-title {
    font-weight: 600;
    color: #333;
    margin: 0;
}

.permiso-switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.permiso-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 24px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .slider {
    background: linear-gradient(135deg, #667eea, #764ba2);
}

input:checked + .slider:before {
    transform: translateX(26px);
}

.btn-grupo {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 30px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
}

.btn-custom {
    padding: 12px 30px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 0.95rem;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd8, #6a4190);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    color: white;
    text-decoration: none;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-2px);
    color: white;
    text-decoration: none;
}

.alert-info {
    background: linear-gradient(135deg, #d1ecf1, #bee5eb);
    border: 1px solid #b6d4da;
    color: #0c5460;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.codigo-preview {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    padding: 8px 12px;
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
    color: #495057;
    margin-top: 5px;
}

@media (max-width: 768px) {
    .nivel-selector {
        grid-template-columns: repeat(3, 1fr);
    }
    
    .permisos-grid {
        grid-template-columns: 1fr;
    }
    
    .btn-grupo {
        flex-direction: column;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="crear-tipo-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="crear-card">
                    <div class="crear-header">
                        <h1><i class="fas fa-user-plus"></i> Crear Nuevo Tipo de Usuario</h1>
                        <p>Configure un nuevo tipo de usuario según las especificaciones Metrix</p>
                    </div>
                    
                    <div class="p-4">
                        <div class="alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Información:</strong> Los tipos de usuario se crean según la documentación "Tipos de Usuarios Metrix (Julio 2025)". 
                            Seleccione la categoría apropiada y configure los permisos según las necesidades organizacionales.
                        </div>
                        
                        <form id="formCrearTipo" action="<?= base_url('tipos-usuario/crear') ?>" method="POST">
                            <?= csrf_field() ?>
                            
                            <!-- Información Básica -->
                            <div class="form-section">
                                <h3 class="section-title">
                                    <i class="fas fa-info-circle"></i>
                                    Información Básica
                                </h3>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="nombre">Nombre del Tipo *</label>
                                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                                   placeholder="Ej: Coordinador de Campo" required>
                                            <div class="form-text">Nombre descriptivo del tipo de usuario</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="codigo">Código *</label>
                                            <input type="text" class="form-control" id="codigo" name="codigo" 
                                                   placeholder="COORD_CAMPO" required>
                                            <div class="codigo-preview" id="codigoPreview">Se generará automáticamente</div>
                                            <div class="form-text">Código único para identificación interna</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label" for="descripcion">Descripción</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" 
                                              placeholder="Descripción detallada de las responsabilidades y alcance del tipo de usuario"></textarea>
                                    <div class="form-text">Descripción opcional de las funciones y responsabilidades</div>
                                </div>
                            </div>
                            
                            <!-- Categoría -->
                            <div class="form-section">
                                <h3 class="section-title">
                                    <i class="fas fa-layer-group"></i>
                                    Categoría de Usuario
                                </h3>
                                
                                <div class="row">
                                    <?php foreach ($categorias as $categoria): ?>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="categoria-option" onclick="seleccionarCategoria(<?= $categoria['id'] ?>)">
                                            <input type="radio" name="categoria_id" value="<?= $categoria['id'] ?>" 
                                                   id="categoria_<?= $categoria['id'] ?>" required>
                                            <div class="categoria-info">
                                                <h4><?= htmlspecialchars($categoria['nombre']) ?></h4>
                                                <p><?= htmlspecialchars($categoria['descripcion']) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <!-- Nivel de Acceso -->
                            <div class="form-section">
                                <h3 class="section-title">
                                    <i class="fas fa-layer-group"></i>
                                    Nivel de Acceso
                                </h3>
                                
                                <div class="form-text mb-3">
                                    Seleccione el nivel jerárquico (1 = Mayor autoridad, 5 = Menor autoridad)
                                </div>
                                
                                <div class="nivel-selector">
                                    <div class="nivel-option" onclick="seleccionarNivel(1)">
                                        <div class="nivel-number">1</div>
                                        <div class="nivel-label">Máximo</div>
                                    </div>
                                    <div class="nivel-option" onclick="seleccionarNivel(2)">
                                        <div class="nivel-number">2</div>
                                        <div class="nivel-label">Alto</div>
                                    </div>
                                    <div class="nivel-option" onclick="seleccionarNivel(3)">
                                        <div class="nivel-number">3</div>
                                        <div class="nivel-label">Medio</div>
                                    </div>
                                    <div class="nivel-option" onclick="seleccionarNivel(4)">
                                        <div class="nivel-number">4</div>
                                        <div class="nivel-label">Bajo</div>
                                    </div>
                                    <div class="nivel-option" onclick="seleccionarNivel(5)">
                                        <div class="nivel-number">5</div>
                                        <div class="nivel-label">Mínimo</div>
                                    </div>
                                </div>
                                
                                <input type="hidden" name="nivel_acceso" id="nivel_acceso" value="3">
                            </div>
                            
                            <!-- Permisos Básicos -->
                            <div class="form-section">
                                <h3 class="section-title">
                                    <i class="fas fa-key"></i>
                                    Permisos Básicos
                                </h3>
                                
                                <div class="form-text mb-3">
                                    Configure los permisos básicos que tendrá este tipo de usuario
                                </div>
                                
                                <div class="permisos-grid">
                                    <div class="permiso-card">
                                        <div class="permiso-header">
                                            <h5 class="permiso-title">Crear Usuarios</h5>
                                            <label class="permiso-switch">
                                                <input type="checkbox" name="puede_crear_usuarios" value="1">
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                        <p class="form-text">Permite crear nuevos usuarios en el sistema</p>
                                    </div>
                                    
                                    <div class="permiso-card">
                                        <div class="permiso-header">
                                            <h5 class="permiso-title">Modificar Usuarios</h5>
                                            <label class="permiso-switch">
                                                <input type="checkbox" name="puede_modificar_usuarios" value="1">
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                        <p class="form-text">Permite editar información de usuarios existentes</p>
                                    </div>
                                    
                                    <div class="permiso-card">
                                        <div class="permiso-header">
                                            <h5 class="permiso-title">Eliminar Usuarios</h5>
                                            <label class="permiso-switch">
                                                <input type="checkbox" name="puede_eliminar_usuarios" value="1">
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                        <p class="form-text">Permite eliminar usuarios del sistema</p>
                                    </div>
                                    
                                    <div class="permiso-card">
                                        <div class="permiso-header">
                                            <h5 class="permiso-title">Estado Activo</h5>
                                            <label class="permiso-switch">
                                                <input type="checkbox" name="activo" value="1" checked>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                        <p class="form-text">El tipo de usuario estará activo al crearse</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Botones de Acción -->
                            <div class="btn-grupo">
                                <button type="submit" class="btn-custom btn-primary">
                                    <i class="fas fa-save"></i>
                                    Crear Tipo de Usuario
                                </button>
                                <a href="<?= base_url('tipos-usuario') ?>" class="btn-custom btn-secondary">
                                    <i class="fas fa-times"></i>
                                    Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Generar código automáticamente
document.getElementById('nombre').addEventListener('input', function() {
    const nombre = this.value;
    const codigo = nombre
        .toUpperCase()
        .replace(/[ÁÀÄÂ]/g, 'A')
        .replace(/[ÉÈËÊ]/g, 'E')
        .replace(/[ÍÌÏÎ]/g, 'I')
        .replace(/[ÓÒÖÔ]/g, 'O')
        .replace(/[ÚÙÜÛ]/g, 'U')
        .replace(/[Ñ]/g, 'N')
        .replace(/[^A-Z0-9]/g, '_')
        .replace(/_+/g, '_')
        .replace(/^_|_$/g, '');
    
    document.getElementById('codigo').value = codigo;
    document.getElementById('codigoPreview').textContent = codigo || 'Se generará automáticamente';
});

// Seleccionar categoría
function seleccionarCategoria(categoriaId) {
    // Remover selección anterior
    document.querySelectorAll('.categoria-option').forEach(option => {
        option.classList.remove('selected');
    });
    
    // Seleccionar nueva categoría
    const option = document.querySelector(`input[value="${categoriaId}"]`).closest('.categoria-option');
    option.classList.add('selected');
    
    // Marcar radio button
    document.getElementById(`categoria_${categoriaId}`).checked = true;
}

// Seleccionar nivel
function seleccionarNivel(nivel) {
    // Remover selección anterior
    document.querySelectorAll('.nivel-option').forEach(option => {
        option.classList.remove('selected');
    });
    
    // Seleccionar nuevo nivel
    event.target.closest('.nivel-option').classList.add('selected');
    
    // Actualizar campo oculto
    document.getElementById('nivel_acceso').value = nivel;
}

// Validación del formulario
document.getElementById('formCrearTipo').addEventListener('submit', function(e) {
    const nombre = document.getElementById('nombre').value.trim();
    const codigo = document.getElementById('codigo').value.trim();
    const categoria = document.querySelector('input[name="categoria_id"]:checked');
    
    if (!nombre) {
        e.preventDefault();
        Swal.fire({
            title: 'Error',
            text: 'El nombre del tipo de usuario es obligatorio',
            icon: 'error'
        });
        return;
    }
    
    if (!codigo) {
        e.preventDefault();
        Swal.fire({
            title: 'Error',
            text: 'El código del tipo de usuario es obligatorio',
            icon: 'error'
        });
        return;
    }
    
    if (!categoria) {
        e.preventDefault();
        Swal.fire({
            title: 'Error',
            text: 'Debe seleccionar una categoría',
            icon: 'error'
        });
        return;
    }
    
    // Mostrar loading
    Swal.fire({
        title: 'Creando tipo de usuario...',
        text: 'Por favor espere',
        icon: 'info',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
});

// Inicializar nivel por defecto
document.addEventListener('DOMContentLoaded', function() {
    seleccionarNivel(3); // Nivel medio por defecto
});
</script>
<?= $this->endSection() ?>