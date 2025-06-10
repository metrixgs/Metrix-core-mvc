<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0"><?= $titulo_pagina; ?></h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url() . "panel/"; ?>">Inicio</a></li>
                            <li class="breadcrumb-item active">Conversacion</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm-auto">
                                <div>
                                    <h4 class="card-title mb-0 flex-grow-1">Conversacion</h4>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <!-- Botón para abrir el modal para crear empresa-->
                                    <div class="search-box ms-2">
                                        <a id="descargarConversacion" class="btn btn-primary" href="#">Descargar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="p-3 user-chat-topbar">
                            <div class="row align-items-center">
                                <div class="col-sm-4 col-8">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 d-block d-lg-none me-3">
                                            <a href="javascript: void(0);" class="user-chat-remove fs-18 p-1"><i class="ri-arrow-left-s-line align-bottom"></i></a>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 chat-user-img online user-own-img align-self-center me-3 ms-0">
                                                    <img src="<?= base_url(); ?>public/files/images/foto-usuario.jpg" class="rounded-circle avatar-xs" alt="">
                                                    <span class="user-status"></span>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <h5 class="text-truncate mb-0 fs-16"><a class="text-reset username" data-bs-toggle="offcanvas" href="#userProfileCanvasExample" aria-controls="userProfileCanvasExample">Soporte Tecnico</a></h5>
                                                    <p class="text-truncate text-muted fs-14 mb-0 userStatus"><small>GestionPlus</small></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="d-flex flex-column flex-lg-row" style="border: 1px solid #eaeaea; height: 600px; position: relative;">
                            <!-- Contenido del chat con fondo -->
                            <div style="flex: 1; display: flex; flex-direction: column; justify-content: space-between; position: relative; z-index: 1;">
                                <div id="chatMessages" style="flex: 1; overflow-y: auto; padding: 10px; background: rgba(255, 255, 255, 0.8);">
                                    <?php

                                    // Función para obtener la fecha formateada
                                    function formatMessageDate($messageDate) {
                                        $messageDateTime = new DateTime($messageDate);
                                        $now = new DateTime();

                                        // Comparar fechas
                                        $dateDifference = $now->diff($messageDateTime);

                                        // Si es el mismo día, solo mostramos la hora
                                        if ($dateDifference->days == 0) {
                                            return $messageDateTime->format('h:i a'); // Formato: 03:15 pm
                                        }

                                        // Si es diferente día, mostramos la fecha completa
                                        return $messageDateTime->format('d M Y h:i a'); // Formato: 22 Dec 2024 03:15 pm
                                    }
                                    ?>

                                    <?php foreach ($mensajes as $mensaje): ?>
                                        <?php if ($mensaje['remitente_id'] === session('session_data.id')): ?>
                                            <!-- Mensaje enviado por el usuario -->
                                            <div style="display: flex; align-items: flex-end; justify-content: flex-end; margin-bottom: 10px;">
                                                <div style="max-width: 70%; background: #007bff; color: #fff; border-radius: 10px 10px 0 10px; padding: 10px; text-align: right;">
                                                    <p style="margin: 0;"><?= htmlspecialchars($mensaje['mensaje']); ?></p>
                                                    <span style="font-size: 0.8rem; color: #cce5ff; display: block;">
                                                        <?= formatMessageDate($mensaje['fecha_creacion']); ?>
                                                    </span>
                                                </div>
                                                <img src="<?= base_url(); ?>public/files/images/foto-usuario.jpg" alt="Fondo" style="width: 40px; height: 40px; margin-left: 10px; border-radius: 50%; background-repeat: repeat;">
                                            </div>
                                        <?php else: ?>
                                            <!-- Mensaje recibido del administrador -->
                                            <div style="display: flex; align-items: flex-start; margin-bottom: 10px;">
                                                <img src="<?= base_url(); ?>public/files/images/logo.png" alt="Soporte Técnico" style="width: 40px; height: 40px; margin-right: 10px; border-radius: 50%; background-repeat: repeat;">
                                                <div style="max-width: 70%; background: #f1f1f1; border-radius: 10px 10px 10px 0; padding: 10px;">
                                                    <!-- Nombre del administrador -->
                                                    <span style="font-size: 0.9rem; font-weight: bold; color: #333; margin-bottom: 5px; display: block;">
                                                        <?= htmlspecialchars($mensaje['nombre_remitente']); ?>
                                                    </span>
                                                    <p style="margin: 0;"><?= htmlspecialchars($mensaje['mensaje']); ?></p>
                                                    <span style="font-size: 0.8rem; color: #777; display: block;">
                                                        <?= formatMessageDate($mensaje['fecha_creacion']); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>

                                <!-- Formulario para enviar mensajes -->
                                <form class="chat-form bg-white" action="<?= base_url() . 'soporte/crear'; ?>" method="post" style="display: flex; align-items: center; border-top: 1px solid #eaeaea; padding: 10px;">
                                    <input type="hidden" name="conversacion_id" required="" value="<?= $conversacion['id']; ?>">
                                    <textarea name="mensaje" id="chatInput" placeholder="Escribe un mensaje..." rows="1" style="flex: 1; border: 1px solid #eaeaea; border-radius: 5px; padding: 10px; resize: none;" required></textarea>
                                    <button type="submit" style="margin-left: 10px; background: #007bff; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
                                        <i class="ri-send-plane-2-fill"></i>
                                    </button>
                                </form>
                            </div>

                            <!-- Fondo con opacidad -->
                            <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-image: url('<?= base_url(); ?>public/files/images/fondo-chat.jpg'); background-repeat: repeat; background-position: center; opacity: 0.6; z-index: 0;"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

