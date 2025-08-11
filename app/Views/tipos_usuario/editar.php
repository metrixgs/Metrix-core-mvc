<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Editar Tipo de Usuario<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link href="<?= base_url('assets/css/select2.min.css') ?>" rel="stylesheet">
<style>
.editar-tipo-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px 0;
}

.editar-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    overflow: hidden;
}

.editar-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 25px;
    position: relative;
    overflow: hidden;
}

.editar-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
    opacity: 0.1;
}

.editar-header h1 {
    margin: 0;
    font-size: 2rem;
    font-weight: 700;
    position: relative;
    z-index: 1;
}

.editar-header p {
    margin: 10px 0 0 0;
    opacity: 0.9;
    font-size: 1rem;
    position: relative;
    z-index: 1;
}

.info-actual {
    background: linear-gradient(135deg, #e3f2fd, #bbdefb);
    border: 1px solid #90caf9;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 25px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.info-item {
    background: white;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.info-label {
    font-size: 0.8rem;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 5px;
}

.info-value {
    font-size: 1rem;
    font-weight: 600;
    color: #333;
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

.btn-danger {
    background: linear-gradient(135deg, #dc3545, #e83e8c);
    color: white;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #c82333, #d91a72);
    transform: translateY(-2px);
    color: white;
    text-decoration: none;
}

.alert-warning {
    background: linear-gradient(135deg, #fff3cd, #ffeaa7);
    border: 1px solid #ffeaa7;
    color: #856404;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.estadisticas-uso {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #e9ecef;
}

.stat-item:last-child {
    border-bottom: none;
}

.stat-label {
    font-weight: 500;
    color: #333;
}

.stat-value {
    font-weight: 600;
    color: #667eea;
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
    
    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="editar-tipo-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="editar-card">
                    <div class="editar-header">
                        <h1><i class="fas fa-user-edit"></i> Editar Tipo de Usuario</h1>
                        <p>Modificar configuración del tipo: <?= htmlspecialchars($tipo['nombre']) ?></p>
                    </div>
                    
                    <div class="p-4">
                        <!-- Información Actual -->
                        <div class="info-actual">
                            <h4><i class="fas fa-info-circle"></i> Información Actual</h4>
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Código</div>
                                    <div class="info-value"><?= htmlspecialchars($tipo['codigo']) ?></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Categoría</div>
                                    <div class="info-value"><?= htmlspecialchars($tipo['categoria_nombre'] ?? 'Sin categoría') ?></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Nivel de Acceso</div>
                                    <div class="info-value">Nivel <?= $tipo['nivel_acceso'] ?></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Estado</div>
                                    <div class="info-value"><?= $tipo['activo'] ? 'Activo' : 'Inactivo' ?></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Fecha Creación</div>
                                    <div class="info-value"><?= date('d/m/Y', strtotime($tipo['created_at'])) ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Estadísticas de Uso -->
                        <?php if (isset($estadisticas_uso)): ?>
                        <div class="estadisticas-uso">
                            <h5><i class="fas fa-chart-bar"></i> Estadísticas de Uso</h5>
                            <div class="stat-item">
                                <span class="stat-label">Usuarios Asignados:</span>
                                <span class="stat-value"><?= $estadisticas_uso['usuarios_totales'] ?? 0 ?></span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Usuarios Activos:</span>
                                <span class="stat-value"><?= $estadisticas_uso['usuarios_activos'] ?? 0 ?></span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Permisos Configurados:</span>
                                <span class="stat-value"><?= $estadisticas_uso['permisos_configurados'] ?? 0 ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (($estadisticas_uso['usuarios_totales'] ?? 0) > 0): ?>
                        <div class="alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Atención:</strong> Este tipo de usuario tiene <?= $estadisticas_uso['usuarios_totales'] ?> usuarios asignados. 
                            Los cambios afectarán a todos los usuarios de este tipo.
                        </div>
                        <?php endif; ?>
                        
                        <form id="formEditarTipo" action="<?= base_url('tipos-usuario/editar/' . $tipo['id']) ?>" method="POST">
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
                                                   value="<?= htmlspecialchars($tipo['nombre']) ?>" required>
                                            <div class="form-text">Nombre descriptivo del tipo de usuario</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="codigo">Código *</label>
                                            <input type="text" class="form-control" id="codigo" name="codigo" 
                                                   value="<?= htmlspecialchars($tipo['codigo']) ?>" required>
                                            <div class="form-text">Código único para identificación interna</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label" for="descripcion">Descripción</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?= htmlspecialchars($tipo['descripcion'] ?? '') ?></textarea>
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
                                        <div class="categoria-option <?= $categoria['id'] == $tipo['categoria_id'] ? 'selected' : '' ?>" 
                                             onclick="seleccionarCategoria(<?= $categoria['id'] ?>)">
                                            <input type="radio" name="categoria_id" value="<?= $categoria['id'] ?>" 
                                                   id="categoria_<?= $categoria['id'] ?>" 
                                                   <?= $categoria['id'] == $tipo['categoria_id'] ? 'checked' : '' ?> required>
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
                                    <div class="nivel-option <?= $tipo['nivel_acceso'] == 1 ? 'selected' : '' ?>" onclick="seleccionarNivel(1)">
                                        <div class="nivel-number">1</div>
                                        <div class="nivel-label">Máximo</div>
                                    </div>
                                    <div class="nivel-option <?= $tipo['nivel_acceso'] == 2 ? 'selected' : '' ?>" onclick="seleccionarNivel(2)">
                                        <div class="nivel-number">2</div>
                                        <div class="nivel-label">Alto</div>
                                    </div>
                                    <div class="nivel-option <?= $tipo['nivel_acceso'] == 3 ? 'selected' : '' ?>" onclick="seleccionarNivel(3)">
                                        <div class="nivel-number">3</div>
                                        <div class="nivel-label">Medio</div>
                                    </div>
                                    <div class="nivel-option <?= $tipo['nivel_acceso'] == 4 ? 'selected' : '' ?>" onclick="seleccionarNivel(4)">
                                        <div class="nivel-number">4</div>
                                        <div class="nivel-label">Bajo</div>
                                    </div>
                                    <div class="nivel-option <?= $tipo['nivel_acceso'] == 5 ? 'selected' : '' ?>" onclick="seleccionarNivel(5)">
                                        <div class="nivel-number">5</div>
                                        <div class="nivel-label">Mínimo</div>
                                    </div>
                                </div>
                                
                                <input type="hidden" name="nivel_acceso" id="nivel_acceso" value="<?= $tipo['nivel_acceso'] ?>">
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
                                                <input type="checkbox" name="puede_crear_usuarios" value="1" 
                                                       <?= $tipo['puede_crear_usuarios'] ? 'checked' : '' ?>>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                        <p class="form-text">Permite crear nuevos usuarios en el sistema</p>
                                    </div>
                                    
                                    <div class="permiso-card">
                                        <div class="permiso-header">
                                            <h5 class="permiso-title">Modificar Usuarios</h5>
                                            <label class="permiso-switch">
                                                <input type="checkbox" name="puede_modificar_usuarios" value="1" 
                                                       <?= $tipo['puede_modificar_usuarios'] ? 'checked' : '' ?>>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                        <p class="form-text">Permite editar información de usuarios existentes</p>
                                    </div>
                                    
                                    <div class="permiso-card">
                                        <div class="permiso-header">
                                            <h5 class="permiso-title">Eliminar Usuarios</h5>
                                            <label class="permiso-switch">
                                                <input type="checkbox" name="puede_eliminar_usuarios" value="1" 
                                                       <?= $tipo['puede_eliminar_usuarios'] ? 'checked' : '' ?>>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                        <p class="form-text">Permite eliminar usuarios del sistema</p>
                                    </div>
                                    
                                    <div class="permiso-card">
                                        <div class="permiso-header">
                                            <h5 class="permiso-title">Estado Activo</h5>
                                            <label class="permiso-switch">
                                                <input type="checkbox" name="activo" value="1" 
                                                       <?= $tipo['activo'] ? 'checked' : '' ?>>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                        <p class="form-text">El tipo de usuario estará activo</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Botones de Acción -->
                            <div class="btn-grupo">
                                <button type="submit" class="btn-custom btn-primary">
                                    <i class="fas fa-save"></i>
                                    Guardar Cambios
                                </button>
                                <a href="<?= base_url('tipos-usuario/permisos/' . $tipo['id']) ?>" class="btn-custom btn-secondary">
                                    <i class="fas fa-key"></i>
                                    Gestionar Permisos
                                </a>
                                <a href="<?= base_url('tipos-usuario') ?>" class="btn-custom btn-secondary">
                                    <i class="fas fa-arrow-left"></i>
                                    Volver
                                </a>
                                <?php if (($estadisticas_uso['usuarios_totales'] ?? 0) == 0): ?>
                                <button type="button" class="btn-custom btn-danger" onclick="eliminarTipo()">
                                    <i class="fas fa-trash"></i>
                                    Eliminar Tipo
                                </button>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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

// Eliminar tipo de usuario
function eliminarTipo() {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción eliminará permanentemente el tipo de usuario',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`<?= base_url('tipos-usuario/eliminar/' . $tipo['id']) ?>`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '¡Eliminado!',
                        text: data.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '<?= base_url('tipos-usuario') ?>';
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message,
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Error al procesar la solicitud',
                    icon: 'error'
                });
            });
        }
    });
}

// Validación del formulario
document.getElementById('formEditarTipo').addEventListener('submit', function(e) {
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
        title: 'Guardando cambios...',
        text: 'Por favor espere',
        icon: 'info',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
});
</script>
<?= $this->endSection() ?>