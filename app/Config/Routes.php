<?php

use CodeIgniter\Router\RouteCollection;




$routes->get('unauthorized', 'Auth::unauthorized');

$routes->get('login', 'Auth::index');
$routes->post('login/validar', 'Auth::validar');
$routes->post('auth/login', 'Auth::login');
$routes->get('logout', 'Auth::logout');

// Ruta del mapa integrado con vite 
$routes->get('mapa', 'MapaController::index');


// Rutas administrativas usando IDs de rol
$routes->get('/', 'Panel::index'); // admin
$routes->get('panel', 'Panel::index'); // admin
#Rutas de las Campañas
$routes->get('campanas/', 'Campanas::index');
$routes->get('campanas/detalle/(:num)', 'Campanas::detalle/$1');
$routes->get('campanas/rondas/(:num)', 'Campanas::rondas/$1');

 $routes->post('campanas/crear', 'Campanas::crear');
$routes->post('campanas/actualizar', 'Campanas::actualizar');
$routes->post('campanas/eliminar', 'Campanas::eliminar');

#Rutas de los tipos de Campañas
$routes->get('campanas/tipos', 'Campanas::tipos');
$routes->get('campanas/tipos/detalle/(:num)', 'Campanas::detalle_tipo/$1');
$routes->post('campanas/tipos/crear', 'Campanas::crear_tipo');
$routes->post('campanas/tipos/eliminar', 'Campanas::eliminar_tipo');
$routes->post('campanas/tipos/actualizar', 'Campanas::actualizar_tipo');
$routes->get('campanas/nueva', 'Campanas::nueva');
 $routes->get('campanas/tags', 'Campanas::tagsCatalog');


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
$routes->get('rondas/obtenerOperadoresPorBrigada/(:num)', 'Rondas::obtenerOperadoresPorBrigada/$1');


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
$routes->post('dependencias/asignarUsuarioADependencia', 'Dependencias::asignarUsuarioADependencia');
$routes->post('dependencias/desasignarUsuarioDeDependencia', 'Dependencias::desasignarUsuarioDeDependencia');
$routes->get('dependencias/asignar-usuario/(:num)', 'Dependencias::asignarUsuario/$1');

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
$routes->get('usuarios/nuevo-operador/(:num)', 'Usuarios::nuevoOperador/$1'); // Nueva ruta para crear operador
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
$routes->get('cuentas/create', 'CuentaController::create');
$routes->post('cuentas/store', 'CuentaController::store');


#rutas para encuestas 

$routes->get('/surveys', 'SurveyController::index');
$routes->get('/survey/create', 'SurveyController::create');
$routes->post('/survey/store', 'SurveyController::store');
$routes->get('/survey/(:num)', 'SurveyController::show/$1');
$routes->post('/survey/(:num)/storeResponse', 'SurveyController::storeResponse/$1');

$routes->get('/survey/(:num)/estadistica', 'SurveyController::estadisticaPorEncuesta/$1');
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

$routes->post('directorio/eliminar/(:num)', 'Directorio::eliminar/$1');


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


// targetas 

$routes->group('relacionados', function($routes) {
    $routes->get('rondas/(:num)', 'CampanasRelacionados::rondas/$1');
    $routes->get('brigadas/(:num)', 'CampanasRelacionados::brigadas/$1');
    $routes->get('visitas/(:num)', 'CampanasRelacionados::visitas/$1');
    $routes->get('incidencias/(:num)', 'CampanasRelacionados::incidencias/$1');
    $routes->get('encuestas/(:num)', 'CampanasRelacionados::encuestas/$1');
    $routes->get('entregas/(:num)', 'CampanasRelacionados::entregas/$1');
    $routes->get('peticiones/(:num)', 'CampanasRelacionados::peticiones/$1');
    $routes->get('relacionados/(:segment)/(:num)', 'RelacionadosController::listar/$1/$2');

});

// Rutas AJAX del Dashboard
$routes->post('dashboard/estadisticas', 'DashboardAjax::obtenerEstadisticas');
$routes->post('dashboard/exportar', 'DashboardAjax::exportarExcel');

// Ruta de prueba del dashboard
$routes->get('test-dashboard', 'TestDashboard::index');
