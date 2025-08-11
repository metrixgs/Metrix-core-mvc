<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Activación de Usuarios</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Inicio</a></li>
                        <li class="breadcrumb-item active">Activación de Usuarios</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            
            <!-- Panel de Acceso Rápido -->
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-users-cog"></i> Panel de Control - Activación de Usuarios
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php if (isset($es_master) && $es_master): ?>
                                <!-- Opciones para Master -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="info-box bg-gradient-primary">
                                        <span class="info-box-icon"><i class="fas fa-crown"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Panel Master</span>
                                            <span class="info-box-number">Gestión Global</span>
                                            <div class="progress">
                                                <div class="progress-bar" style="width: 100%"></div>
                                            </div>
                                            <span class="progress-description">
                                                <a href="<?= base_url('activacion-usuarios/panel-master') ?>" class="btn btn-light btn-sm mt-2">
                                                    <i class="fas fa-arrow-right"></i> Acceder
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 col-lg-4">
                                    <div class="info-box bg-gradient-info">
                                        <span class="info-box-icon"><i class="fas fa-chart-bar"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Estadísticas Generales</span>
                                            <span class="info-box-number"><?= $estadisticas_generales['total'] ?? 0 ?> Usuarios</span>
                                            <div class="progress">
                                                <div class="progress-bar" style="width: <?= $estadisticas_generales['porcentaje_activos'] ?? 0 ?>%"></div>
                                            </div>
                                            <span class="progress-description">
                                                <?= $estadisticas_generales['activos'] ?? 0 ?> activos de <?= $estadisticas_generales['total'] ?? 0 ?> total
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 col-lg-4">
                                    <div class="info-box bg-gradient-success">
                                        <span class="info-box-icon"><i class="fas fa-building"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Cuentas Activas</span>
                                            <span class="info-box-number"><?= $cuentas_activas ?? 0 ?></span>
                                            <div class="progress">
                                                <div class="progress-bar" style="width: 100%"></div>
                                            </div>
                                            <span class="progress-description">
                                                Cuentas en el sistema
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (isset($es_administrador) && $es_administrador): ?>
                                <!-- Opciones para Administrador -->
                                <div class="col-md-6 col-lg-6">
                                    <div class="info-box bg-gradient-warning">
                                        <span class="info-box-icon"><i class="fas fa-user-shield"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Panel Administrador</span>
                                            <span class="info-box-number">Mi Cuenta</span>
                                            <div class="progress">
                                                <div class="progress-bar" style="width: 100%"></div>
                                            </div>
                                            <span class="progress-description">
                                                <a href="<?= base_url('activacion-usuarios/panel-administrador') ?>" class="btn btn-light btn-sm mt-2">
                                                    <i class="fas fa-arrow-right"></i> Acceder
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 col-lg-6">
                                    <div class="info-box bg-gradient-info">
                                        <span class="info-box-icon"><i class="fas fa-users"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Usuarios de mi Cuenta</span>
                                            <span class="info-box-number"><?= $estadisticas_cuenta['total'] ?? 0 ?></span>
                                            <div class="progress">
                                                <div class="progress-bar" style="width: <?= $estadisticas_cuenta['porcentaje_activos'] ?? 0 ?>%"></div>
                                            </div>
                                            <span class="progress-description">
                                                <?= $estadisticas_cuenta['activos'] ?? 0 ?> activos
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Historial Reciente (Solo Master) -->
            <?php if (isset($es_master) && $es_master && !empty($historial_reciente)): ?>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-history"></i> Actividad Reciente
                            </h3>
                            <div class="card-tools">
                                <a href="<?= base_url('activacion-usuarios/panel-master') ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> Ver Todo
                                </a>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Usuario Afectado</th>
                                        <th>Acción</th>
                                        <th>Ejecutado por</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($historial_reciente as $item): ?>
                                    <tr>
                                        <td><?= date('d/m/Y H:i', strtotime($item['fecha_accion'])) ?></td>
                                        <td><?= htmlspecialchars($item['usuario_afectado_nombre']) ?></td>
                                        <td>
                                            <span class="badge badge-<?= $item['accion'] == 'activar' ? 'success' : 'warning' ?>">
                                                <?= ucfirst($item['accion']) ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($item['ejecutor_nombre']) ?></td>
                                        <td>
                                            <span class="badge badge-<?= $item['resultado'] == 'exitoso' ? 'success' : 'danger' ?>">
                                                <?= ucfirst($item['resultado']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Usuarios Recientes (Solo Administrador) -->
            <?php if (isset($es_administrador) && $es_administrador): ?>
            <div class="row">
                <?php if (!empty($usuarios_activos)): ?>
                <div class="col-md-6">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-user-check"></i> Usuarios Activos Recientes
                            </h3>
                        </div>
                        <div class="card-body">
                            <?php foreach ($usuarios_activos as $usuario): ?>
                            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                                <div>
                                    <strong><?= htmlspecialchars($usuario['nombre']) ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars($usuario['email']) ?></small>
                                </div>
                                <span class="badge badge-success">Activo</span>
                            </div>
                            <?php endforeach; ?>
                            <div class="text-center mt-3">
                                <a href="<?= base_url('activacion-usuarios/panel-administrador') ?>" class="btn btn-success btn-sm">
                                    <i class="fas fa-eye"></i> Ver Todos
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($usuarios_inactivos)): ?>
                <div class="col-md-6">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-user-times"></i> Usuarios Inactivos
                            </h3>
                        </div>
                        <div class="card-body">
                            <?php foreach ($usuarios_inactivos as $usuario): ?>
                            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                                <div>
                                    <strong><?= htmlspecialchars($usuario['nombre']) ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars($usuario['email']) ?></small>
                                </div>
                                <span class="badge badge-warning">Inactivo</span>
                            </div>
                            <?php endforeach; ?>
                            <div class="text-center mt-3">
                                <a href="<?= base_url('activacion-usuarios/panel-administrador') ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-eye"></i> Ver Todos
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
        </div>
    </section>
</div>

<style>
.info-box {
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    border-radius: .25rem;
    background: #fff;
    display: flex;
    margin-bottom: 1rem;
    min-height: 80px;
    padding: .5rem;
    position: relative;
    width: 100%;
}

.info-box .info-box-icon {
    border-radius: .25rem;
    align-items: center;
    display: flex;
    font-size: 1.875rem;
    justify-content: center;
    text-align: center;
    width: 70px;
}

.info-box .info-box-content {
    display: flex;
    flex-direction: column;
    justify-content: center;
    line-height: 1.8;
    flex: 1;
    padding: 0 10px;
}

.bg-gradient-primary {
    background: linear-gradient(45deg, #007bff, #6610f2) !important;
    color: #fff;
}

.bg-gradient-info {
    background: linear-gradient(45deg, #17a2b8, #007bff) !important;
    color: #fff;
}

.bg-gradient-success {
    background: linear-gradient(45deg, #28a745, #20c997) !important;
    color: #fff;
}

.bg-gradient-warning {
    background: linear-gradient(45deg, #ffc107, #fd7e14) !important;
    color: #212529;
}
</style>