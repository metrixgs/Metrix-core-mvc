<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Detalles - <?= htmlspecialchars($tipo['nombre']) ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.detalle-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px 0;
}

.detalle-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    overflow: hidden;
}

.detalle-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    position: relative;
    overflow: hidden;
}

.detalle-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
    opacity: 0.1;
}

.detalle-header h1 {
    margin: 0;
    font-size: 2.5rem;
    font-weight: 700;
    position: relative;
    z-index: 1;
}

.detalle-header .subtitle {
    margin: 10px 0 0 0;
    opacity: 0.9;
    font-size: 1.1rem;
    position: relative;
    z-index: 1;
}

.estado-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
    z-index: 2;
}

.estado-activo {
    background: rgba(40, 167, 69, 0.9);
    color: white;
}

.estado-inactivo {
    background: rgba(220, 53, 69, 0.9);
    color: white;
}

.info-section {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    border-left: 4px solid #667eea;
}

.section-title {
    color: #333;
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.section-title i {
    color: #667eea;
    font-size: 1.2rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.info-item {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.info-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.info-label {
    font-size: 0.85rem;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
    font-weight: 500;
}

.info-value {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    word-break: break-word;
}

.categoria-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
}

.categoria-metrix {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.categoria-cliente {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
}

.categoria-sistema {
    background: linear-gradient(135deg, #ffc107, #fd7e14);
    color: #212529;
}

.nivel-indicator {
    display: flex;
    align-items: center;
    gap: 10px;
}

.nivel-bar {
    width: 100px;
    height: 8px;
    background: #e9ecef;
    border-radius: 4px;
    overflow: hidden;
}

.nivel-fill {
    height: 100%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 4px;
    transition: width 0.3s ease;
}

.estadisticas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.stat-card {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    border: 1px solid #dee2e6;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 8px;
}

.stat-label {
    font-size: 0.9rem;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 500;
}

.permisos-resumen {
    background: linear-gradient(135deg, #e3f2fd, #bbdefb);
    border: 1px solid #90caf9;
    border-radius: 12px;
    padding: 20px;
}

.permisos-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.permiso-group {
    background: white;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.permiso-group h5 {
    margin: 0 0 10px 0;
    color: #333;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.permiso-items {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.permiso-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.permiso-activo {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.permiso-inactivo {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.usuarios-relacionados {
    background: linear-gradient(135deg, #fff3cd, #ffeaa7);
    border: 1px solid #ffeaa7;
    border-radius: 12px;
    padding: 20px;
}

.usuarios-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.usuario-card {
    background: white;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 12px;
}

.usuario-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 1.1rem;
}

.usuario-info h6 {
    margin: 0;
    font-weight: 600;
    color: #333;
}

.usuario-info p {
    margin: 0;
    font-size: 0.85rem;
    color: #666;
}

.acciones-container {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 25px;
    margin-top: 30px;
}

.btn-grupo {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-custom {
    padding: 12px 24px;
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

.btn-success {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
}

.btn-success:hover {
    background: linear-gradient(135deg, #218838, #1ba085);
    transform: translateY(-2px);
    color: white;
    text-decoration: none;
}

.btn-warning {
    background: linear-gradient(135deg, #ffc107, #fd7e14);
    color: #212529;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #e0a800, #e8590c);
    transform: translateY(-2px);
    color: #212529;
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

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
    background: white;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -22px;
    top: 20px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #667eea;
    border: 3px solid white;
    box-shadow: 0 0 0 2px #667eea;
}

.timeline-date {
    font-size: 0.8rem;
    color: #666;
    margin-bottom: 5px;
}

.timeline-content {
    color: #333;
    font-weight: 500;
}

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .estadisticas-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .permisos-list {
        grid-template-columns: 1fr;
    }
    
    .usuarios-grid {
        grid-template-columns: 1fr;
    }
    
    .btn-grupo {
        flex-direction: column;
    }
    
    .detalle-header h1 {
        font-size: 2rem;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="detalle-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="detalle-card">
                    <div class="detalle-header">
                        <div class="estado-badge <?= $tipo['activo'] ? 'estado-activo' : 'estado-inactivo' ?>">
                            <?= $tipo['activo'] ? 'Activo' : 'Inactivo' ?>
                        </div>
                        <h1><i class="fas fa-user-tag"></i> <?= htmlspecialchars($tipo['nombre']) ?></h1>
                        <p class="subtitle">Código: <?= htmlspecialchars($tipo['codigo']) ?></p>
                    </div>
                    
                    <div class="p-4">
                        <!-- Información Básica -->
                        <div class="info-section">
                            <h2 class="section-title">
                                <i class="fas fa-info-circle"></i>
                                Información Básica
                            </h2>
                            
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Nombre del Tipo</div>
                                    <div class="info-value"><?= htmlspecialchars($tipo['nombre']) ?></div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Código Único</div>
                                    <div class="info-value"><?= htmlspecialchars($tipo['codigo']) ?></div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Categoría</div>
                                    <div class="info-value">
                                        <span class="categoria-badge categoria-<?= strtolower(str_replace(' ', '-', $tipo['categoria_nombre'] ?? 'sistema')) ?>">
                                            <i class="fas fa-layer-group"></i>
                                            <?= htmlspecialchars($tipo['categoria_nombre'] ?? 'Sin categoría') ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Nivel de Acceso</div>
                                    <div class="info-value">
                                        <div class="nivel-indicator">
                                            <span>Nivel <?= $tipo['nivel_acceso'] ?></span>
                                            <div class="nivel-bar">
                                                <div class="nivel-fill" style="width: <?= (6 - $tipo['nivel_acceso']) * 20 ?>%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Fecha de Creación</div>
                                    <div class="info-value"><?= date('d/m/Y H:i', strtotime($tipo['created_at'])) ?></div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Última Modificación</div>
                                    <div class="info-value"><?= $tipo['updated_at'] ? date('d/m/Y H:i', strtotime($tipo['updated_at'])) : 'Sin modificaciones' ?></div>
                                </div>
                            </div>
                            
                            <?php if (!empty($tipo['descripcion'])): ?>
                            <div class="mt-3">
                                <div class="info-label">Descripción</div>
                                <div class="info-value"><?= nl2br(htmlspecialchars($tipo['descripcion'])) ?></div>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Estadísticas -->
                        <div class="info-section">
                            <h2 class="section-title">
                                <i class="fas fa-chart-bar"></i>
                                Estadísticas de Uso
                            </h2>
                            
                            <div class="estadisticas-grid">
                                <div class="stat-card">
                                    <div class="stat-number"><?= $estadisticas['usuarios_totales'] ?? 0 ?></div>
                                    <div class="stat-label">Usuarios Asignados</div>
                                </div>
                                
                                <div class="stat-card">
                                    <div class="stat-number"><?= $estadisticas['usuarios_activos'] ?? 0 ?></div>
                                    <div class="stat-label">Usuarios Activos</div>
                                </div>
                                
                                <div class="stat-card">
                                    <div class="stat-number"><?= $estadisticas['permisos_configurados'] ?? 0 ?></div>
                                    <div class="stat-label">Permisos Configurados</div>
                                </div>
                                
                                <div class="stat-card">
                                    <div class="stat-number"><?= $estadisticas['modulos_activos'] ?? 0 ?></div>
                                    <div class="stat-label">Módulos Activos</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Permisos Configurados -->
                        <?php if (!empty($permisos_configurados)): ?>
                        <div class="info-section">
                            <h2 class="section-title">
                                <i class="fas fa-key"></i>
                                Permisos Configurados
                            </h2>
                            
                            <div class="permisos-resumen">
                                <p><strong>Total de permisos activos:</strong> <?= count($permisos_configurados) ?></p>
                                
                                <div class="permisos-list">
                                    <?php 
                                    $permisos_por_modulo = [];
                                    foreach ($permisos_configurados as $permiso) {
                                        $modulo = $permiso['modulo_nombre'] ?? 'Sin módulo';
                                        if (!isset($permisos_por_modulo[$modulo])) {
                                            $permisos_por_modulo[$modulo] = [];
                                        }
                                        $permisos_por_modulo[$modulo][] = $permiso;
                                    }
                                    ?>
                                    
                                    <?php foreach ($permisos_por_modulo as $modulo => $permisos): ?>
                                    <div class="permiso-group">
                                        <h5>
                                            <i class="fas fa-cog"></i>
                                            <?= htmlspecialchars($modulo) ?>
                                        </h5>
                                        <div class="permiso-items">
                                            <?php foreach ($permisos as $permiso): ?>
                                            <span class="permiso-badge permiso-activo">
                                                <?= htmlspecialchars($permiso['permiso_nombre']) ?>
                                            </span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Usuarios Relacionados -->
                        <?php if (!empty($usuarios_relacionados)): ?>
                        <div class="info-section">
                            <h2 class="section-title">
                                <i class="fas fa-users"></i>
                                Usuarios con este Tipo
                            </h2>
                            
                            <div class="usuarios-relacionados">
                                <p><strong>Total de usuarios:</strong> <?= count($usuarios_relacionados) ?></p>
                                
                                <div class="usuarios-grid">
                                    <?php foreach (array_slice($usuarios_relacionados, 0, 8) as $usuario): ?>
                                    <div class="usuario-card">
                                        <div class="usuario-avatar">
                                            <?= strtoupper(substr($usuario['nombre'], 0, 1)) ?>
                                        </div>
                                        <div class="usuario-info">
                                            <h6><?= htmlspecialchars($usuario['nombre']) ?></h6>
                                            <p><?= htmlspecialchars($usuario['email'] ?? 'Sin email') ?></p>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                    
                                    <?php if (count($usuarios_relacionados) > 8): ?>
                                    <div class="usuario-card" style="justify-content: center; background: #f8f9fa; border: 2px dashed #dee2e6;">
                                        <div class="text-center">
                                            <i class="fas fa-plus-circle" style="font-size: 1.5rem; color: #667eea; margin-bottom: 5px;"></i>
                                            <p style="margin: 0; font-weight: 600; color: #667eea;">
                                                +<?= count($usuarios_relacionados) - 8 ?> más
                                            </p>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Historial de Cambios -->
                        <?php if (!empty($historial_cambios)): ?>
                        <div class="info-section">
                            <h2 class="section-title">
                                <i class="fas fa-history"></i>
                                Historial de Cambios
                            </h2>
                            
                            <div class="timeline">
                                <?php foreach (array_slice($historial_cambios, 0, 5) as $cambio): ?>
                                <div class="timeline-item">
                                    <div class="timeline-date">
                                        <?= date('d/m/Y H:i', strtotime($cambio['fecha'])) ?>
                                    </div>
                                    <div class="timeline-content">
                                        <strong><?= htmlspecialchars($cambio['accion']) ?></strong>
                                        <?php if (!empty($cambio['descripcion'])): ?>
                                        <br><small class="text-muted"><?= htmlspecialchars($cambio['descripcion']) ?></small>
                                        <?php endif; ?>
                                        <?php if (!empty($cambio['usuario'])): ?>
                                        <br><small class="text-muted">Por: <?= htmlspecialchars($cambio['usuario']) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Acciones -->
                        <div class="acciones-container">
                            <h3 class="text-center mb-4">Acciones Disponibles</h3>
                            <div class="btn-grupo">
                                <a href="<?= base_url('tipos-usuario/editar/' . $tipo['id']) ?>" class="btn-custom btn-primary">
                                    <i class="fas fa-edit"></i>
                                    Editar Tipo
                                </a>
                                
                                <a href="<?= base_url('tipos-usuario/permisos/' . $tipo['id']) ?>" class="btn-custom btn-success">
                                    <i class="fas fa-key"></i>
                                    Gestionar Permisos
                                </a>
                                
                                <button type="button" class="btn-custom btn-warning" onclick="duplicarTipo()">
                                    <i class="fas fa-copy"></i>
                                    Duplicar Tipo
                                </button>
                                
                                <button type="button" class="btn-custom btn-secondary" onclick="exportarConfiguracion()">
                                    <i class="fas fa-download"></i>
                                    Exportar Configuración
                                </button>
                                
                                <?php if (($estadisticas['usuarios_totales'] ?? 0) == 0): ?>
                                <button type="button" class="btn-custom btn-danger" onclick="eliminarTipo()">
                                    <i class="fas fa-trash"></i>
                                    Eliminar Tipo
                                </button>
                                <?php endif; ?>
                                
                                <a href="<?= base_url('tipos-usuario') ?>" class="btn-custom btn-secondary">
                                    <i class="fas fa-arrow-left"></i>
                                    Volver al Listado
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Duplicar tipo de usuario
function duplicarTipo() {
    Swal.fire({
        title: 'Duplicar Tipo de Usuario',
        html: `
            <div class="text-left">
                <div class="form-group mb-3">
                    <label class="form-label">Nuevo Nombre:</label>
                    <input type="text" id="nuevo-nombre" class="form-control" value="Copia de <?= addslashes($tipo['nombre']) ?>">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Nuevo Código:</label>
                    <input type="text" id="nuevo-codigo" class="form-control" value="<?= addslashes($tipo['codigo']) ?>_COPIA">
                </div>
                <div class="form-group">
                    <label class="form-check-label">
                        <input type="checkbox" id="copiar-permisos" class="form-check-input" checked>
                        Copiar permisos configurados
                    </label>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Duplicar',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            const nombre = document.getElementById('nuevo-nombre').value.trim();
            const codigo = document.getElementById('nuevo-codigo').value.trim();
            const copiarPermisos = document.getElementById('copiar-permisos').checked;
            
            if (!nombre || !codigo) {
                Swal.showValidationMessage('Nombre y código son obligatorios');
                return false;
            }
            
            return { nombre, codigo, copiarPermisos };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const data = result.value;
            
            fetch('<?= base_url('tipos-usuario/duplicar/' . $tipo['id']) ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '¡Duplicado!',
                        text: data.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '<?= base_url('tipos-usuario/detalle/') ?>' + data.nuevo_id;
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

// Exportar configuración
function exportarConfiguracion() {
    const configuracion = {
        tipo_usuario: {
            id: <?= $tipo['id'] ?>,
            nombre: '<?= addslashes($tipo['nombre']) ?>',
            codigo: '<?= addslashes($tipo['codigo']) ?>',
            categoria_id: <?= $tipo['categoria_id'] ?? 'null' ?>,
            nivel_acceso: <?= $tipo['nivel_acceso'] ?>,
            descripcion: '<?= addslashes($tipo['descripcion'] ?? '') ?>',
            activo: <?= $tipo['activo'] ? 'true' : 'false' ?>
        },
        permisos: <?= json_encode($permisos_configurados ?? []) ?>,
        estadisticas: <?= json_encode($estadisticas ?? []) ?>,
        fecha_exportacion: new Date().toISOString()
    };
    
    const blob = new Blob([JSON.stringify(configuracion, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `tipo_usuario_${configuracion.tipo_usuario.codigo}_${new Date().toISOString().split('T')[0]}.json`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
    
    Swal.fire({
        title: '¡Exportado!',
        text: 'La configuración se ha exportado correctamente',
        icon: 'success',
        timer: 2000,
        showConfirmButton: false
    });
}

// Eliminar tipo de usuario
function eliminarTipo() {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción eliminará permanentemente el tipo de usuario y no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('<?= base_url('tipos-usuario/eliminar/' . $tipo['id']) ?>', {
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
</script>
<?= $this->endSection() ?>