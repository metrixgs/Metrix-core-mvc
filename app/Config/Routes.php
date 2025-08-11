<?php

use CodeIgniter\Router\RouteCollection;




$routes->get('unauthorized', 'Auth::unauthorized');

$routes->get('login', 'Auth::index');
$routes->post('login/validar', 'Auth::validar');
$routes->post('auth/login', 'Auth::login');
$routes->get('logout', 'Auth::logout');
$routes->get('recuperar', 'Auth::recuperar');
$routes->post('recuperar/enviar', 'Auth::recovery');
$routes->get('registro', 'Auth::registro');

// Test routes for CuentaFilter
$routes->get('test', 'TestController::index', ['filter' => 'cuenta']); // Protected by cuenta filter
$routes->get('test/public', 'TestController::public'); // Public route without filter

// Ruta del mapa integrado con vite 
$routes->get('mapa', 'MapaController::index');



// Rutas para gestión de permisos del sidebar (solo administradores)
$routes->group('sidebar-permisos', ['filter' => 'sidebar_admin'], function($routes) {
    $routes->get('/', 'SidebarPermisos::index');
    $routes->post('actualizar', 'SidebarPermisos::actualizar');
    $routes->post('configurar-defecto', 'SidebarPermisos::configurarDefecto');
    $routes->get('estadisticas', 'SidebarPermisos::estadisticas');
    $routes->get('exportar', 'SidebarPermisos::exportar');
    $routes->get('historial', 'SidebarPermisos::historial');
});



// Rutas administrativas usando IDs de rol
$routes->get('/', 'Panel::index', ['filter' => 'cuenta']); // admin
$routes->get('panel', 'Panel::index', ['filter' => 'cuenta']); // admin
$routes->get('dashboard', 'Panel::index', ['filter' => 'cuenta']); // dashboard
// Grupo de rutas protegidas por el filtro de cuenta
$routes->group('', ['filter' => 'cuenta'], function($routes) {
    #Rutas de las Campañas
    $routes->get('campanas/', 'Campanas::index');
    $routes->get('campanas/detalle/(:num)', 'Campanas::detalle/$1');
    $routes->post('campanas/crear', 'Campanas::crear');
    $routes->post('campanas/actualizar', 'Campanas::actualizar');
    $routes->post('campanas/eliminar', 'Campanas::eliminar');

    #Rutas de los tipos de Campañas
    $routes->get('campanas/tipos', 'Campanas::tipos');
    $routes->get('campanas/tipos/detalle/(:num)', 'Campanas::detalle_tipo/$1');
    $routes->post('campanas/tipos/crear', 'Campanas::crear_tipo');
    $routes->post('campanas/tipos/eliminar', 'Campanas::eliminar_tipo');
    $routes->post('campanas/tipos/actualizar', 'Campanas::actualizar_tipo');

    #Rutas de la Rondas...
    $routes->get('rondas/', 'Rondas::index');
    $routes->get('rondas/detalle/(:num)', 'Rondas::detalle/$1');
    $routes->match(['get', 'post'], 'rondas/crear', 'Rondas::crear');
    $routes->match(['get', 'post'], 'rondas/editar/(:num)', 'Rondas::editar/$1');
    $routes->get('rondas/eliminar/(:num)', 'Rondas::eliminar/$1');
    $routes->get('rondas/cambiar_estado/(:num)/(:any)', 'Rondas::cambiar_estado/$1/$2');
    $routes->get('rondas/puntos', 'Rondas::puntos');
    $routes->get('rondas/zonas', 'Rondas::zonas');
    $routes->get('rondas/listar/(:num)', 'Rondas::listar/$1');


    #Rutas de Rondas por Segmentación...
    $routes->get('rondas/por_segmentacion', 'Rondas::por_segmentacion');

    #Rutas de Segmentaciones...
    $routes->get('segmentaciones', 'Rondas::segmentaciones');
    $routes->match(['get', 'post'], 'rondas/crear_segmentacion', 'Rondas::crear_segmentacion');
    $routes->match(['get', 'post'], 'rondas/editar_segmentacion/(:num)', 'Rondas::editar_segmentacion/$1');
    $routes->get('rondas/eliminar_segmentacion/(:num)', 'Rondas::eliminar_segmentacion/$1');

    #Rutas de los subtipos de Campañas
    $routes->get('campanas/subtipos', 'Campanas::subtipos', );
    $routes->post('campanas/subtipos/crear', 'Campanas::crear_subtipo');
    $routes->post('campanas/subtipos/actualizar', 'Campanas::actualizar_subtipo');
    $routes->post('campanas/subtipos/eliminar', 'Campanas::eliminar_subtipo');
    $routes->get('campanas/obtener/subtipos/(:num)', 'Campanas::obtener_subtipos/$1');

    #Rutas de las Dependencias
    $routes->get('dependencias', 'Dependencias::index');
    $routes->get('dependencias/detalle/(:num)', 'Dependencias::detalle/$1');
    $routes->post('dependencias/crear', 'Dependencias::crear');
    $routes->post('dependencias/actualizar/(:num)', 'Dependencias::actualizar/$1');
    $routes->post('dependencias/eliminar', 'Dependencias::eliminar');

    #Rutas de los Tickets
    $routes->get('tickets/', 'Tickets::index');
    $routes->get('tickets/tipos', 'Tickets::tipos');
    $routes->get('tickets/creados', 'Tickets::ticketsCreados');
    $routes->get('tickets/asignados', 'Tickets::ticketsAsignados');
    $routes->get('tickets/nuevo', 'Tickets::nuevoTicket', );
    $routes->get('tickets/detalle/(:num)', 'Tickets::detalle/$1');
    $routes->post('tickets/crear', 'Tickets::crear');
    $routes->post('tickets/crear-tipo', 'Tickets::crearTipo');
    $routes->get('tickets/enviar-recordatorio/(:num)', 'Tickets::enviarRecordatorio/$1');
    $routes->post('tickets/subir-archivo', 'Tickets::subirArchivo');
    $routes->post('tickets/crear-comentario', 'Tickets::crearComentario');
    $routes->post('tickets/cerrar-ticket', 'Tickets::cerrarTicket');
    $routes->get('tickets/eliminar/(:num)', 'Tickets::eliminar/$1');

    #Rutas de las Tareas
    $routes->get('tareas/', 'Tareas::index');
    $routes->get('tareas/detalle/(:num)', 'Tareas::detalle/$1');
    $routes->post('tareas/crear', 'Tareas::crear');
    $routes->post('tareas/solucionar', 'Tareas::solucionar');
    $routes->post('tareas/documento', 'Tareas::documento');

    #Rutas de las notificaciones...
    $routes->get('notificaciones', 'Notificaciones::index');
    $routes->get('notificaciones/detalle/(:num)', 'Notificaciones::index');
    $routes->get('notificaciones/nueva', 'Notificaciones::nueva');
    $routes->get('notificaciones/eliminar/(:num)', 'Notificaciones::eliminar/$1');
    $routes->post('notificaciones/crear', 'Notificaciones::crear');

    #Rutas del Perfil...
    $routes->get('perfil', 'Perfil::index');
    $routes->get('perfil/eliminar-cuenta', 'Perfil::eliminarCuenta');
    $routes->post('perfil/actualizar', 'Perfil::actualizar');

    #Rutas de soporte tecnico...
    $routes->get('soporte/conversaciones', 'Soporte::index', ['filter' => 'role:administrador']);
    $routes->get('soporte/conversaciones/detalle/(:num)', 'Soporte::detalle/$1');
    $routes->post('soporte/crear', 'Soporte::crear');
    $routes->get('soporte/mensajes', 'Soporte::mensajes');
    $routes->get('soporte/descargar', 'Soporte::descargar');

    #Rutas de los Clientes...
    $routes->get('clientes', 'Clientes::index');
    $routes->get('clientes/detalle/(:num)', 'Clientes::detalle/$1');
    $routes->post('clientes/actualizar', 'Clientes::actualizar');
    $routes->post('clientes/crear', 'Clientes::crear');
    $routes->get('clientes/eliminar/(:num)', 'Clientes::eliminar/$1');

    #Rutas de los Usuarios...
    $routes->get('usuarios', 'Usuarios::index');
    $routes->get('usuarios/detalle/(:num)', 'Usuarios::detalle/$1');
    $routes->get('usuarios/nuevo', 'Usuarios::nuevo');
    $routes->get('usuarios/eliminar-permiso/(:num)', 'Usuarios::eliminarPermiso/$1');
    $routes->post('usuarios/actualizar', 'Usuarios::actualizar');
    $routes->post('usuarios/crear', 'Usuarios::crear');
    $routes->post('usuarios/eliminar', 'Usuarios::eliminar');
    $routes->post('usuarios/crear-permiso', 'Usuarios::crearPermiso');

    #Rutas de los Reportes...
    $routes->get('reportes/requerimientos', 'Reportes::requerimientos');
    $routes->get('reportes/tareas', 'Reportes::tareas');
    $routes->post('reportes/resultado-requerimientos', 'Reportes::reporteRequerimientos');
    $routes->post('reportes/resultado-tareas', 'Reportes::reporteTareas');

    #Rutas de la configuracion...
    $routes->get('configuracion/', 'Configuracion::index');
    $routes->post('configuracion/actualizar', 'Configuracion::actualizar');
    $routes->get('configuracion/sla', 'Configuracion::sla');
    $routes->post('configuracion/sla/crear', 'Configuracion::crear_sla');
    $routes->get('configuracion/sla/eliminar/(:num)', 'Configuracion::eliminar_sla/$1', );



    #Rutas de las cuentas...admin por el master gestion de clientes
    $routes->get('cuentas/', 'CuentaController::index');
    $routes->get('cuentas/create', 'CuentaController::create');
    $routes->post('cuentas/store', 'CuentaController::store');
    $routes->get('cuentas/edit/(:num)', 'CuentaController::edit/$1');
    $routes->post('cuentas/update/(:num)', 'CuentaController::update/$1');
    $routes->delete('cuentas/delete/(:num)', 'CuentaController::delete/$1');
    $routes->post('cuentas/activate/(:num)', 'CuentaController::activate/$1');
    $routes->post('cuentas/deactivate/(:num)', 'CuentaController::deactivate/$1');


    #rutas para encuestas 
    $routes->get('/surveys', 'SurveyController::index');
    $routes->get('/survey/create', 'SurveyController::create');
    $routes->post('/survey/store', 'SurveyController::store');
    $routes->get('/survey/(:num)', 'SurveyController::show/$1');
    $routes->post('/survey/(:num)/storeResponse', 'SurveyController::storeResponse/$1');
    $routes->get('/survey/(:num)/responded', 'SurveyController::surveyResponded/$1');
    //respuestas
    $routes->get('/survey/(:num)/responses', 'SurveyController::showResponses/$1');
    //exel
    $routes->get('/survey/exportResponses/(:num)', 'SurveyController::exportResponses/$1');




    // rutas directorio(CRM)
    $routes->get('/directorio', 'Directorio::index');
    $routes->get('/directorio/crear', 'Directorio::crear');
    $routes->post('/directorio/guardar', 'Directorio::guardar');
    $routes->get('/directorio/editar/(:num)', 'Directorio::editar/$1');
    $routes->post('/directorio/actualizar/(:num)', 'Directorio::actualizar/$1');
    $routes->delete('/directorio/(:num)', 'Directorio::eliminar/$1');
    $routes->get('/directorio/ver/(:num)', 'Directorio::ver/$1');
    $routes->get('directorio/mapa/(:num)', 'Directorio::mapa/$1');


    //pagina para el Qr 
    $routes->get('tickets/listadosQr', 'TiketListaController::index');
    $routes->get('/tickets/ver/(:num)', 'TiketListaController::verTicket/$1'); // <-- esta es nueva

    // Exportaciones del directorio
    $routes->get('export/excel', 'ExportController::excel');
    $routes->get('export/pdf', 'ExportController::pdf');
    $routes->get('export/csv', 'ExportController::csv');
    $routes->post('export/enviar-correo', 'ExportController::enviarCorreo');

    // Rutas de la Bitácora de Usuarios
    $routes->get('bitacora/', 'BitacoraController::index');




    // Rutas de Activación de Usuarios
    $routes->get('activacion-usuarios', 'ActivacionUsuarios::index');
    $routes->get('activacion-usuarios/panel-master', 'ActivacionUsuarios::panelMaster');
    $routes->get('activacion-usuarios/panel-administrador', 'ActivacionUsuarios::panelAdministrador');
    $routes->get('activacion-usuarios/configuracion', 'ActivacionUsuarios::configuracion');
    $routes->post('activacion-usuarios/guardar-configuracion', 'ActivacionUsuarios::guardarConfiguracion');
    $routes->post('activacion-usuarios/activar-usuario', 'ActivacionUsuarios::activarUsuario');
    $routes->post('activacion-usuarios/desactivar-usuario', 'ActivacionUsuarios::desactivarUsuario');
    $routes->get('activacion-usuarios/obtener-usuario/(:num)', 'ActivacionUsuarios::obtenerUsuario/$1');
    $routes->get('activacion-usuarios/estadisticas', 'ActivacionUsuarios::obtenerEstadisticas');
    $routes->get('activacion-usuarios/historial', 'ActivacionUsuarios::obtenerHistorial');
    $routes->get('activacion-usuarios/exportar-reporte', 'ActivacionUsuarios::exportarReporte');

    




}); // End of cuenta filter group

