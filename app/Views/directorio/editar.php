 <!-- Header con gradiente usando solo Bootstrap -->
<div class="bg-warning bg-gradient text-dark py-4 mb-4 rounded-4 shadow">
    <div class="text-center">
        <h1 class="mb-2 fw-bold display-6">
            <i class="bi bi-pencil-square me-2"></i>
            Editar Contacto
        </h1>
        <p class="mb-0 opacity-75 lead">Actualiza la información del contacto</p>
    </div>
</div>

<div class="container" style="max-width: 900px;">
    <!-- Card principal con sombra moderna -->
    <div class="card shadow-lg border-0 rounded-4 mb-4">
        <div class="card-body p-4 p-md-5">
            
            <!-- Mostrar errores de validación con diseño moderno -->
            <?php if(session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-sm" role="alert">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                        <strong>¡Atención! Corrige los siguientes errores:</strong>
                    </div>
                    <ul class="mb-0 list-unstyled">
                        <?php foreach(session()->getFlashdata('errors') as $error): ?>
                            <li class="ms-4"><i class="bi bi-dot text-danger me-1"></i><?= esc($error) ?></li>
                        <?php endforeach ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

 <form action="<?= base_url('directorio/actualizar/'.$contacto['id']) ?>" method="post" enctype="multipart/form-data" novalidate class="needs-validation">
                
                <!-- Sección: Información Personal -->
                <div class="mb-5">
                    <h4 class="text-primary fw-bold border-bottom border-primary border-2 pb-2 mb-4">
                        <i class="bi bi-person-fill me-2"></i>
                        Información Personal
                    </h4>
                    
                    <div class="row g-3">
                        <!-- Nombre (Requerido) -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input 
                                    type="text" 
                                    class="form-control border-2 shadow-sm" 
                                    id="nombre" 
                                    name="nombre" 
                                    value="<?= set_value('nombre', esc($contacto['nombre'])); ?>" 
                                    placeholder="Ingresa el nombre"
                                    required
                                    minlength="3"
                                    maxlength="100"
                                >
                                <label for="nombre">
                                    <i class="bi bi-person me-1"></i>
                                    Nombre <span class="text-danger">*</span>
                                </label>
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>
                                    Por favor ingresa un nombre válido (mínimo 3 caracteres).
                                </div>
                            </div>
                        </div>

                        <!-- Primer Apellido -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input 
                                    type="text" 
                                    class="form-control border-2 shadow-sm" 
                                    id="primer_apellido" 
                                    name="primer_apellido" 
                                    value="<?= set_value('primer_apellido', esc($contacto['primer_apellido'] ?? '')); ?>"
                                    placeholder="Ingresa el primer apellido"
                                    maxlength="100"
                                >
                                <label for="primer_apellido">
                                    <i class="bi bi-person-badge me-1"></i>
                                    Primer Apellido
                                </label>
                            </div>
                        </div>

                        <!-- Segundo Apellido -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input 
                                    type="text" 
                                    class="form-control border-2 shadow-sm" 
                                    id="segundo_apellido" 
                                    name="segundo_apellido" 
                                    value="<?= set_value('segundo_apellido', esc($contacto['segundo_apellido'] ?? '')); ?>"
                                    placeholder="Ingresa el segundo apellido"
                                    maxlength="100"
                                >
                                <label for="segundo_apellido">
                                    <i class="bi bi-person-badge me-1"></i>
                                    Segundo Apellido
                                </label>
                            </div>
                        </div>

                        <!-- CURP -->
                        <div class="col-md-6">
    <div class="form-floating">
        <input 
            type="text" 
            class="form-control border-2 shadow-sm text-uppercase" 
            id="curp" 
            name="curp" 
            value="<?= set_value('curp', esc($contacto['curp'] ?? '')); ?>"
            placeholder="CURP de 18 caracteres"
            maxlength="18"
            pattern="[A-Z]{4}[0-9]{6}[HM][A-Z]{5}[0-9]{2}"
        >
        <label for="curp">
            <i class="bi bi-card-text me-1"></i>
            CURP
        </label>
        <div class="form-text">Formato: ABCD123456HDFXYZ01</div>
    </div>
</div>

<!-- Hidden para conservar la CURP antigua -->
<input 
    type="hidden" 
    name="old_curp" 
    value="<?= esc(set_value('old_curp', $contacto['curp'] ?? '')); ?>"
>

                        <!-- Fecha de Nacimiento -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input 
                                    type="date" 
                                    class="form-control border-2 shadow-sm" 
                                    id="fecha_nacimiento" 
                                    name="fecha_nacimiento" 
                                    value="<?= set_value('fecha_nacimiento', esc($contacto['fecha_nacimiento'] ?? '')); ?>"
                                >
                                <label for="fecha_nacimiento">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    Fecha de Nacimiento
                                </label>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input 
                                    type="email" 
                                    class="form-control border-2 shadow-sm" 
                                    id="email" 
                                    name="email" 
                                    value="<?= set_value('email', esc($contacto['email'] ?? '')); ?>"
                                    placeholder="correo@ejemplo.com"
                                    maxlength="150"
                                >
                                <label for="email">
                                    <i class="bi bi-envelope me-1"></i>
                                    Email
                                </label>
                            </div>
                        </div>

                        <!-- Teléfono -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input 
                                    type="text" 
                                    class="form-control border-2 shadow-sm" 
                                    id="telefono" 
                                    name="telefono" 
                                    value="<?= set_value('telefono', esc($contacto['telefono'] ?? '')); ?>"
                                    placeholder="+52 555 123 4567"
                                    maxlength="50"
                                >
                                <label for="telefono">
                                    <i class="bi bi-telephone me-1"></i>
                                    Teléfono
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Foto de pefil  -->

 <div class="mb-4">
    <label for="foto_perfil" class="form-label fw-semibold">Foto de Perfil</label>
    <input 
        class="form-control border-2 shadow-sm" 
        type="file" 
        id="foto_perfil" 
        name="foto_perfil" 
        accept="image/*"
        onchange="previewImagen(event)"
    >

    <!-- Imagen previa nueva -->
    <div id="preview-container" class="mt-2">
        <?php if (!empty($contacto['foto_perfil'])): ?>
            <img 
                id="preview-img" 
                src="<?= base_url('uploads/perfiles/' . $contacto['foto_perfil']) ?>" 
                class="img-thumbnail" 
                style="max-width: 150px;"
            >
        <?php else: ?>
            <img 
                id="preview-img" 
                src="#" 
                class="img-thumbnail d-none" 
                style="max-width: 150px;"
            >
        <?php endif; ?>
    </div>
</div>




                <!-- Sección: Dirección -->
                <div class="mb-5">
                    <h4 class="text-info fw-bold border-bottom border-info border-2 pb-2 mb-4">
                        <i class="bi bi-geo-alt-fill me-2"></i>
                        Información de Dirección
                    </h4>
                    
                    <div class="row g-3">
                        <!-- Residencia -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input 
                                    type="text" 
                                    class="form-control border-2 shadow-sm" 
                                    id="residencia" 
                                    name="residencia" 
                                    value="<?= set_value('residencia', esc($contacto['residencia'] ?? '')); ?>"
                                    placeholder="Ciudad de residencia"
                                    maxlength="255"
                                >
                                <label for="residencia">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    Residencia
                                </label>
                            </div>
                        </div>

                        <!-- Estado -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input 
                                    type="text" 
                                    class="form-control border-2 shadow-sm" 
                                    id="estado" 
                                    name="estado" 
                                    value="<?= set_value('estado', esc($contacto['estado'] ?? '')); ?>"
                                    placeholder="Estado"
                                    maxlength="100"
                                >
                                <label for="estado">
                                    <i class="bi bi-map me-1"></i>
                                    Estado
                                </label>
                            </div>
                        </div>

                        <!-- Municipio -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input 
                                    type="text" 
                                    class="form-control border-2 shadow-sm" 
                                    id="municipio" 
                                    name="municipio" 
                                    value="<?= set_value('municipio', esc($contacto['municipio'] ?? '')); ?>"
                                    placeholder="Municipio"
                                    maxlength="100"
                                >
                                <label for="municipio">
                                    <i class="bi bi-building me-1"></i>
                                    Municipio
                                </label>
                            </div>
                        </div>

                        <!-- Localidad -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input 
                                    type="text" 
                                    class="form-control border-2 shadow-sm" 
                                    id="localidad" 
                                    name="localidad" 
                                    value="<?= set_value('localidad', esc($contacto['localidad'] ?? '')); ?>"
                                    placeholder="Localidad"
                                    maxlength="100"
                                >
                                <label for="localidad">
                                    <i class="bi bi-signpost me-1"></i>
                                    Localidad
                                </label>
                            </div>
                        </div>

                        <!-- Colonia -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input 
                                    type="text" 
                                    class="form-control border-2 shadow-sm" 
                                    id="colonia" 
                                    name="colonia" 
                                    value="<?= set_value('colonia', esc($contacto['colonia'] ?? '')); ?>"
                                    placeholder="Colonia"
                                    maxlength="100"
                                >
                                <label for="colonia">
                                    <i class="bi bi-house-door me-1"></i>
                                    Colonia
                                </label>
                            </div>
                        </div>

                        <!-- Calle -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input 
                                    type="text" 
                                    class="form-control border-2 shadow-sm" 
                                    id="calle" 
                                    name="calle" 
                                    value="<?= set_value('calle', esc($contacto['calle'] ?? '')); ?>"
                                    placeholder="Calle"
                                    maxlength="100"
                                >
                                <label for="calle">
                                    <i class="bi bi-sign-turn-right me-1"></i>
                                    Calle
                                </label>
                            </div>
                        </div>

                        <!-- Número Exterior -->
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input 
                                    type="text" 
                                    class="form-control border-2 shadow-sm" 
                                    id="numero_exterior" 
                                    name="numero_exterior" 
                                    value="<?= set_value('numero_exterior', esc($contacto['numero_exterior'] ?? '')); ?>"
                                    placeholder="No. Ext."
                                    maxlength="20"
                                >
                                <label for="numero_exterior">
                                    <i class="bi bi-hash me-1"></i>
                                    No. Exterior
                                </label>
                            </div>
                        </div>

                        <!-- Número Interior -->
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input 
                                    type="text" 
                                    class="form-control border-2 shadow-sm" 
                                    id="numero_interior" 
                                    name="numero_interior" 
                                    value="<?= set_value('numero_interior', esc($contacto['numero_interior'] ?? '')); ?>"
                                    placeholder="No. Int."
                                    maxlength="20"
                                >
                                <label for="numero_interior">
                                    <i class="bi bi-hash me-1"></i>
                                    No. Interior
                                </label>
                            </div>
                        </div>

                        <!-- Código Postal -->
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input 
                                    type="text" 
                                    class="form-control border-2 shadow-sm" 
                                    id="codigo_postal" 
                                    name="codigo_postal" 
                                    value="<?= set_value('codigo_postal', esc($contacto['codigo_postal'] ?? '')); ?>"
                                    placeholder="C.P."
                                    maxlength="10"
                                    pattern="[0-9]{5}"
                                >
                                <label for="codigo_postal">
                                    <i class="bi bi-mailbox me-1"></i>
                                    Código Postal
                                </label>
                            </div>
                        </div>

                        <!-- Dirección Completa -->
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea 
                                    class="form-control border-2 shadow-sm" 
                                    id="direccion" 
                                    name="direccion" 
                                    placeholder="Dirección completa"
                                    style="height: 80px;"
                                    maxlength="255"
                                ><?= set_value('direccion', esc($contacto['direccion'] ?? '')); ?></textarea>
                                <label for="direccion">
                                    <i class="bi bi-house me-1"></i>
                                    Dirección Completa
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección: Información Profesional -->
                <div class="mb-5">
                    <h4 class="text-success fw-bold border-bottom border-success border-2 pb-2 mb-4">
                        <i class="bi bi-briefcase-fill me-2"></i>
                        Información Profesional
                    </h4>
                    
                    <div class="row g-3">
                        <!-- Empresa -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input 
                                    type="text" 
                                    class="form-control border-2 shadow-sm" 
                                    id="empresa" 
                                    name="empresa" 
                                    value="<?= set_value('empresa', esc($contacto['empresa'] ?? '')); ?>"
                                    placeholder="Nombre de la empresa"
                                    maxlength="100"
                                >
                                <label for="empresa">
                                    <i class="bi bi-building me-1"></i>
                                    Empresa
                                </label>
                            </div>
                        </div>

                        <!-- Cargo -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input 
                                    type="text" 
                                    class="form-control border-2 shadow-sm" 
                                    id="cargo" 
                                    name="cargo" 
                                    value="<?= set_value('cargo', esc($contacto['cargo'] ?? '')); ?>"
                                    placeholder="Cargo o posición"
                                    maxlength="100"
                                >
                                <label for="cargo">
                                    <i class="bi bi-person-workspace me-1"></i>
                                    Cargo
                                </label>
                            </div>
                        </div>

                        <!-- Ocupación -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input 
                                    type="text" 
                                    class="form-control border-2 shadow-sm" 
                                    id="ocupacion" 
                                    name="ocupacion" 
                                    value="<?= set_value('ocupacion', esc($contacto['ocupacion'] ?? '')); ?>"
                                    placeholder="Ocupación"
                                    maxlength="100"
                                >
                                <label for="ocupacion">
                                    <i class="bi bi-tools me-1"></i>
                                    Ocupación
                                </label>
                            </div>
                        </div>

                        <!-- Nivel de Estudios -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select border-2 shadow-sm" id="nivel_estudios" name="nivel_estudios">
                                    <option value="">Seleccione una opción...</option>
                                    <option value="Sin estudios" <?= (set_value('nivel_estudios', $contacto['nivel_estudios'] ?? '') === 'Sin estudios') ? 'selected' : '' ?>>Sin estudios</option>
                                    <option value="Primaria" <?= (set_value('nivel_estudios', $contacto['nivel_estudios'] ?? '') === 'Primaria') ? 'selected' : '' ?>>Primaria</option>
                                    <option value="Secundaria" <?= (set_value('nivel_estudios', $contacto['nivel_estudios'] ?? '') === 'Secundaria') ? 'selected' : '' ?>>Secundaria</option>
                                    <option value="Preparatoria" <?= (set_value('nivel_estudios', $contacto['nivel_estudios'] ?? '') === 'Preparatoria') ? 'selected' : '' ?>>Preparatoria</option>
                                    <option value="Técnico" <?= (set_value('nivel_estudios', $contacto['nivel_estudios'] ?? '') === 'Técnico') ? 'selected' : '' ?>>Técnico</option>
                                    <option value="Licenciatura" <?= (set_value('nivel_estudios', $contacto['nivel_estudios'] ?? '') === 'Licenciatura') ? 'selected' : '' ?>>Licenciatura</option>
                                    <option value="Maestría" <?= (set_value('nivel_estudios', $contacto['nivel_estudios'] ?? '') === 'Maestría') ? 'selected' : '' ?>>Maestría</option>
                                    <option value="Doctorado" <?= (set_value('nivel_estudios', $contacto['nivel_estudios'] ?? '') === 'Doctorado') ? 'selected' : '' ?>>Doctorado</option>
                                </select>
                                <label for="nivel_estudios">
                                    <i class="bi bi-mortarboard me-1"></i>
                                    Nivel de Estudios
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección: Información Adicional -->
                <div class="mb-5">
                    <h4 class="text-secondary fw-bold border-bottom border-secondary border-2 pb-2 mb-4">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Información Adicional
                    </h4>
                    
                    <div class="row g-3">
                        <!-- Tipo de Cliente -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select border-2 shadow-sm" id="tipo_cliente" name="tipo_cliente">
                                    <option value="">Seleccione una opción...</option>
                                    <option value="Prospecto" <?= (set_value('tipo_cliente', $contacto['tipo_cliente'] ?? '') === 'Prospecto') ? 'selected' : '' ?>>🔍 Prospecto</option>
                                    <option value="Cliente" <?= (set_value('tipo_cliente', $contacto['tipo_cliente'] ?? '') === 'Cliente') ? 'selected' : '' ?>>👤 Cliente</option>
                                    <option value="Premium" <?= (set_value('tipo_cliente', $contacto['tipo_cliente'] ?? '') === 'Premium') ? 'selected' : '' ?>>⭐ Premium</option>
                                    <option value="Comprador" <?= (set_value('tipo_cliente', $contacto['tipo_cliente'] ?? '') === 'Comprador') ? 'selected' : '' ?>>🛒 Comprador</option>
                                    <option value="VIP" <?= (set_value('tipo_cliente', $contacto['tipo_cliente'] ?? '') === 'VIP') ? 'selected' : '' ?>>💎 VIP</option>
                                </select>
                                <label for="tipo_cliente">
                                    <i class="bi bi-tags me-1"></i>
                                    Tipo de Cliente
                                </label>
                            </div>
                        </div>
                        
<!-- Ejemplo con select -->
<label for="activo">Estado:</label>
<select name="activo" id="activo" class="form-control">
    <option value="1" <?= $contacto['activo'] == 1 ? 'selected' : '' ?>>Activo</option>
    <option value="0" <?= $contacto['activo'] == 0 ? 'selected' : '' ?>>Inactivo</option>
</select>

                        <!-- Tipo de Discapacidad -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input 
                                    type="text" 
                                    class="form-control border-2 shadow-sm" 
                                    id="tipo_discapacidad" 
                                    name="tipo_discapacidad" 
                                    value="<?= set_value('tipo_discapacidad', esc($contacto['tipo_discapacidad'] ?? '')); ?>"
                                    placeholder="Tipo de discapacidad"
                                    maxlength="100"
                                >
                                <label for="tipo_discapacidad">
                                    <i class="bi bi-heart me-1"></i>
                                    Tipo de Discapacidad
                                </label>
                            </div>
                        </div>

                        <!-- Grupo Étnico -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input 
                                    type="text" 
                                    class="form-control border-2 shadow-sm" 
                                    id="grupo_etnico" 
                                    name="grupo_etnico" 
                                    value="<?= set_value('grupo_etnico', esc($contacto['grupo_etnico'] ?? '')); ?>"
                                    placeholder="Grupo étnico"
                                    maxlength="100"
                                >
                                <label for="grupo_etnico">
                                    <i class="bi bi-people me-1"></i>
                                    Grupo Étnico
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Sección:editar lider-->


                <div class="col-md-6">
    <div class="form-floating">
        <select class="form-select border-2 shadow-sm" name="id_lider" id="id_lider">
            <option value="">Sin líder asignado</option>
            <?php foreach ($lideres as $lider): ?>
                <option value="<?= $lider['id'] ?>"
                    <?= set_value('id_lider', $contacto['id_lider'] ?? '') == $lider['id'] ? 'selected' : '' ?>>
                    <?= esc($lider['nombre'] . ' ' . $lider['primer_apellido'] . ' ' . $lider['segundo_apellido']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label for="id_lider">
            <i class="bi bi-person-bounding-box me-1"></i>
            Selecciona un líder
        </label>
    </div>
</div>
 <!-- #red select
  -->

  <select name="tipo_red" class="form-select" required>
    <option value="">Selecciona una  red ...</option>
    <option value="CDN" <?= set_value('tipo_red', $contacto['tipo_red'] ?? '') == 'CDN' ? 'selected' : '' ?>>CDN - Ciudadano</option>
    <option value="BNF" <?= set_value('tipo_red', $contacto['tipo_red'] ?? '') == 'BNF' ? 'selected' : '' ?>>BNF - Beneficiario</option>
    <option value="RED" <?= set_value('tipo_red', $contacto['tipo_red'] ?? '') == 'RED' ? 'selected' : '' ?>>RED - Red de Apoyo</option>
    <option value="EMP" <?= set_value('tipo_red', $contacto['tipo_red'] ?? '') == 'EMP' ? 'selected' : '' ?>>EMP - Empresa</option>
</select>


                <!-- Sección: Configuración y Términos -->
                <div class="mb-4">
                    <h4 class="text-warning fw-bold border-bottom border-warning border-2 pb-2 mb-4">
                        <i class="bi bi-gear-fill me-2"></i>
                        Configuración y Términos
                    </h4>
                    
                    <div class="row g-3">
                        <!-- Acepta Avisos -->
                        <div class="col-md-6">
                            <div class="card bg-light border-2 shadow-sm h-100">
                                <div class="card-body d-flex align-items-center justify-content-center p-3">
                                    <div class="form-check form-switch fs-5">
                                        <input 
                                            class="form-check-input" 
                                            type="checkbox" 
                                            role="switch" 
                                            id="acepta_avisos" 
                                            name="acepta_avisos" 
                                            <?= (set_value('acepta_avisos', $contacto['acepta_avisos'] ?? 0) == 1) ? 'checked' : '' ?>
                                        >
                                        <label class="form-check-label fw-semibold" for="acepta_avisos">
                                            <i class="bi bi-bell me-1"></i>
                                            Acepta Avisos
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Acepta Términos -->
                        <div class="col-md-6">
                            <div class="card bg-light border-2 shadow-sm h-100">
                                <div class="card-body d-flex align-items-center justify-content-center p-3">
                                    <div class="form-check form-switch fs-5">
                                        <input 
                                            class="form-check-input" 
                                            type="checkbox" 
                                            role="switch" 
                                            id="acepta_terminos" 
                                            name="acepta_terminos" 
                                            <?= (set_value('acepta_terminos', $contacto['acepta_terminos'] ?? 0) == 1) ? 'checked' : '' ?>
                                        >
                                        <label class="form-check-label fw-semibold" for="acepta_terminos">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Acepta Términos
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información del contacto -->
                <div class="alert alert-info border-0 rounded-3 shadow-sm mb-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                        <div>
                            <strong>ID del Contacto:</strong> #<?= $contacto['id'] ?>
                            <br>
                            <small class="text-muted">
                                <i class="bi bi-calendar3 me-1"></i>
                                Última actualización en proceso...
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                    <a href="<?= base_url('directorio') ?>" class="btn btn-outline-secondary btn-lg rounded-3">
                        <i class="bi bi-arrow-left me-2"></i>
                        Volver al Listado
                    </a>
                    <div class="d-grid gap-2 d-md-flex">
                        <button type="reset" class="btn btn-outline-warning btn-lg me-md-2 rounded-3">
                            <i class="bi bi-arrow-clockwise me-2"></i>
                            Restablecer
                        </button>
                        <button type="submit" class="btn btn-warning btn-lg rounded-3 shadow-sm text-dark fw-bold">
                            <i class="bi bi-check-lg me-2"></i>
                            Actualizar Contacto
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<!-- JavaScript mejorado -->
<script>
(() => {
    'use strict';
    
    // Seleccionar todos los formularios que necesitan validación
    const forms = document.querySelectorAll('form[novalidate]');
    
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                
                // Scroll al primer campo con error
                const firstInvalid = form.querySelector(':invalid');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center' 
                    });
                    firstInvalid.focus();
                }
            } else {
                // Mostrar confirmación antes de actualizar
                const confirmUpdate = confirm('¿Estás seguro de que deseas actualizar este contacto?');
                if (!confirmUpdate) {
                    event.preventDefault();
                    event.stopPropagation();
                }
            }
            form.classList.add('was-validated');
        }, false);
        
        // Agregar efectos de hover y focus a los inputs
        const inputs = form.querySelectorAll('.form-control, .form-select');
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.classList.add('shadow');
            });
            
            input.addEventListener('blur', () => {
                input.parentElement.classList.remove('shadow');
            });
            
            // Detectar cambios en los campos para mostrar que han sido modificados
            input.addEventListener('input', () => {
                if (input.value !== input.defaultValue) {
                    input.classList.add('border-warning');
                } else {
                    input.classList.remove('border-warning');
                }
            });
        });
    });
    
    // Animación para los switches
    const switches = document.querySelectorAll('.form-check-input[type="checkbox"]');
    switches.forEach(switchElement => {
        switchElement.addEventListener('change', function() {
            const label = this.nextElementSibling;
            if (this.checked) {
                label.classList.add('text-success');
                label.classList.remove('text-muted');
                // Pequeña animación de éxito
                label.style.transform = 'scale(1.05)';
                setTimeout(() => {
                    label.style.transform = 'scale(1)';
                }, 150);
            } else {
                label.classList.add('text-muted');
                label.classList.remove('text-success');
            }
        });
    });
    
    // Formateo automático del CURP
    const curpInput = document.getElementById('curp');
    if (curpInput) {
        curpInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
            // Validación visual básica
            if (this.value.length === 18) {
                this.classList.add('border-success');
                this.classList.remove('border-danger');
            } else if (this.value.length > 0) {
                this.classList.add('border-warning');
                this.classList.remove('border-success', 'border-danger');
            } else {
                this.classList.remove('border-success', 'border-warning', 'border-danger');
            }
        });
    }
    
    // Formateo del código postal
    const cpInput = document.getElementById('codigo_postal');
    if (cpInput) {
        cpInput.addEventListener('input', function() {
            // Solo números
            this.value = this.value.replace(/\D/g, '');
            if (this.value.length === 5) {
                this.classList.add('border-success');
                this.classList.remove('border-warning');
            } else if (this.value.length > 0) {
                this.classList.add('border-warning');
                this.classList.remove('border-success');
            } else {
                this.classList.remove('border-success', 'border-warning');
            }
        });
    }
    
    // Botón reset mejorado
    const resetButton = document.querySelector('button[type="reset"]');
    if (resetButton) {
        resetButton.addEventListener('click', function(e) {
            e.preventDefault();
            const confirmReset = confirm('¿Estás seguro de que deseas restablecer todos los campos?');
            if (confirmReset) {
                const form = this.closest('form');
                form.reset();
                form.classList.remove('was-validated');
                
                // Remover clases de cambios
                form.querySelectorAll('.border-warning, .border-success').forEach(input => {
                    input.classList.remove('border-warning', 'border-success');
                });
                
                // Resetear switches
                switches.forEach(switchEl => {
                    const label = switchEl.nextElementSibling;
                    if (switchEl.checked) {
                        label.classList.add('text-success');
                        label.classList.remove('text-muted');
                    } else {
                        label.classList.add('text-muted');
                        label.classList.remove('text-success');
                    }
                });
                
                // Mostrar notificación
                const alert = document.createElement('div');
                alert.className = 'alert alert-info alert-dismissible fade show position-fixed';
                alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                alert.innerHTML = `
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Campos restablecidos</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(alert);
                
                // Auto-remover después de 3 segundos
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 3000);
            }
        });
    }
    
    // Validación en tiempo real para email
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            if (this.value && !this.checkValidity()) {
                this.classList.add('border-danger');
                this.classList.remove('border-success', 'border-warning');
            } else if (this.value) {
                this.classList.add('border-success');
                this.classList.remove('border-danger', 'border-warning');
            }
        });
    }
    
    // Contador de caracteres para campos con límite
    const textInputs = document.querySelectorAll('input[maxlength], textarea[maxlength]');
    textInputs.forEach(input => {
        const maxLength = input.getAttribute('maxlength');
        if (maxLength) {
            input.addEventListener('input', function() {
                const remaining = maxLength - this.value.length;
                const percentage = (this.value.length / maxLength) * 100;
                
                if (percentage > 90) {
                    this.classList.add('border-warning');
                    this.classList.remove('border-success');
                } else if (percentage > 70) {
                    this.classList.add('border-success');
                    this.classList.remove('border-warning');
                } else {
                    this.classList.remove('border-warning', 'border-success');
                }
            });
        }
    });
})();
</script>
  <!-- #previewn de imagen de perfiles 
   
  -->
 <script>
function previewImagen(event) {
    const file = event.target.files[0];
    const img = document.getElementById('preview-img');

    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            img.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    } else {
        img.src = '#';
        img.classList.add('d-none');
    }
}
</script>
