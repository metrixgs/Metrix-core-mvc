<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuariosModel;
use App\Models\TicketsModel;
use App\Models\AreasModel;
use App\Models\ModulosModel;
use App\Models\PermisosModel;
use App\Models\NotificacionesModel;
use App\Models\RolesModel;
use App\Models\CuentasModel;
use App\Models\TagModel; // Añadir el modelo de tags

class Usuarios extends BaseController {

    protected $usuarios;
    protected $tickets;
    protected $areas;
    protected $permisos;
    protected $modulos;
    protected $notificaciones;
    protected $roles;
    protected $cuentas;
    protected $tagsModel; // Declarar la propiedad para el modelo de tags

    public function __construct() {
        // Instanciar los modelos
        $this->usuarios = new UsuariosModel();
        $this->tickets = new TicketsModel();
        $this->notificaciones = new NotificacionesModel();
        $this->areas = new AreasModel();
        $this->modulos = new ModulosModel();
        $this->permisos = new PermisosModel();
        $this->roles = new RolesModel();
        $this->cuentas = new CuentasModel();
        $this->tagsModel = new TagModel(); // Instanciar el modelo de tags

        # Cargar los Helpers
        helper(['Alerts', 'Email', 'Rol', 'Menu', 'bitacora']);
    }

    private function getUserId() {
        $userId = session('session_data.id');
        // Asegura que el ID sea un número positivo. Si no, usa 1 (asumiendo que el ID 1 es un usuario de sistema/admin válido).
        return (is_numeric($userId) && $userId > 0) ? (int)$userId : 1;
    }

    public function index() {
        # Creamo el titulo de la pagina...
        $data['titulo_pagina'] = 'Metrix | Lista de Usuarios';
        
        # Obtenemos todos los tickets del cliente...
        $tickets = $this->tickets->obtenerTickets();
        $data['tickets'] = $tickets;

        # Obtenemos todas las notificaciones por usuario...
        $notificaciones = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));
        $data['notificaciones'] = $notificaciones;

        # Obtenemos todos los usuarios...
        $cuenta_id = session('session_data.cuenta_id');
        $usuarios = $this->usuarios->obtenerUsuarios($cuenta_id);
        $data['usuarios'] = $usuarios;

        # Obtenemos todas las areas...
        $areas = $this->areas->obtenerAreas();
        $data['areas'] = $areas;

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('usuarios/lista-usuarios', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }
    
    public function nuevo() {
        $data['titulo_pagina'] = 'Metrix | Nuevo usuario';
        $data['tickets'] = $this->tickets->obtenerTickets();
        $data['notificaciones'] = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));
        $data['areas'] = $this->areas->obtenerAreas();
        $data['roles'] = $this->roles->obtenerRoles();

        // ✅ Obtener nombre del rol y cuenta actual desde la sesión
        $rolId = session('session_data.rol_id');
        $cuentaId = session('session_data.cuenta_id');

        $data['nombre_rol'] = '';
        $data['nombre_cuenta'] = '';

        if ($rolId) {
            $rol = $this->roles->obtenerRol($rolId);
            $data['nombre_rol'] = $rol['nombre'] ?? 'Rol ID: ' . $rolId;
        }

        if ($cuentaId) {
            $this->cuentas = new \App\Models\CuentasModel();
            $cuenta = $this->cuentas->obtenerCuenta($cuentaId);
            $data['nombre_cuenta'] = $cuenta['nombre'] ?? 'Cuenta ID: ' . $cuentaId;
        }

        return view('incl/head-application', $data)
            . view('incl/header-application', $data)
            . view('incl/menu-admin', $data)
            . view('usuarios/nuevo-usuario', $data)
            . view('incl/footer-application', $data)
            . view('incl/scripts-application', $data);
    }

    public function nuevoOperador($dependencia_id) {
        $data['titulo_pagina'] = 'Metrix | Nuevo Usuario Operador';
        $data['tickets'] = $this->tickets->obtenerTickets();
        $data['notificaciones'] = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));
        $data['areas'] = $this->areas->obtenerAreas();
        $data['roles'] = $this->roles->obtenerRoles();
        $data['dependencia_id'] = $dependencia_id; // Pasar el ID de la dependencia a la vista

        // Obtener nombre del rol y cuenta actual desde la sesión
        $rolId = session('session_data.rol_id');
        $cuentaId = session('session_data.cuenta_id');

        $data['nombre_rol'] = '';
        $data['nombre_cuenta'] = '';

        if ($rolId) {
            $rol = $this->roles->obtenerRol($rolId);
            $data['nombre_rol'] = $rol['nombre'] ?? 'Rol ID: ' . $rolId;
        }

        if ($cuentaId) {
            $this->cuentas = new \App\Models\CuentasModel();
            $cuenta = $this->cuentas->obtenerCuenta($cuentaId);
            $data['nombre_cuenta'] = $cuenta['nombre'] ?? 'Cuenta ID: ' . $cuentaId;
        }

        return view('incl/head-application', $data)
            . view('incl/header-application', $data)
            . view('incl/menu-admin', $data)
            . view('usuarios/nuevo-usuario-operador', $data) // Nueva vista
            . view('incl/footer-application', $data)
            . view('incl/scripts-application', $data);
    }

    public function detalle($usuario_id) {
        $data['titulo_pagina'] = 'Metrix | Informacion del Usuario';
        
        $data['tickets'] = $this->tickets->obtenerTickets();
        $data['notificaciones'] = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));
        
        $areas = $this->areas->obtenerAreas();
        $data['areas'] = $areas;

        // 🚀 Agrega esta línea para que la vista tenga acceso a $roles
        $data['roles'] = $this->roles->obtenerRoles();

        $usuario = $this->usuarios->obtenerUsuario($usuario_id);
        $data['usuario'] = $usuario;

        if (empty($usuario)) {
            return redirect()->to("usuarios/");
        }

        $permisos = $this->permisos->obtenerPermisosPorUsuario($usuario_id);
        $data['permisos'] = $permisos;

        $modulos = $this->modulos->obtenerModulos();
        $data['modulos'] = $modulos;

        // Cargar tags para el modal de asignación
        $data['catalogo_tags'] = $this->tagsModel->allOrdered();
        $data['tag_stats'] = $this->tagsModel->getUsersCountByTag();
        $data['usuario_tags'] = $this->usuarios->getTagsForUser($usuario_id);

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('usuarios/detalle-usuario', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
    }

    /**
     * Asigna tags a un usuario.
     *
     * @param int $usuario_id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function assignTags($usuario_id)
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->back();
        }

        $tagSlugs = $this->request->getPost('tags_seleccionados'); // Viene como CSV
        $tagSlugsArray = array_filter(array_map('trim', explode(',', $tagSlugs)));

        // Obtener los IDs de los tags a partir de los slugs
        $tagIds = [];
        if (!empty($tagSlugsArray)) {
            $tags = $this->tagsModel->whereIn('slug', $tagSlugsArray)->findAll();
            $tagIds = array_column($tags, 'id');
        }

        if ($this->usuarios->assignTagsToUser($usuario_id, $tagIds)) {
            log_activity(
                $this->getUserId(),
                'Usuarios',
                'Asignación de Tags',
                [
                    'usuario_id' => $usuario_id,
                    'tags_asignados' => $tagSlugsArray
                ]
            );
            $this->session->setFlashdata([
                'titulo' => "¡Éxito!",
                'mensaje' => "Tags asignados correctamente al usuario.",
                'tipo' => "success"
            ]);
        } else {
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se pudieron asignar los tags al usuario.",
                'tipo' => "danger"
            ]);
        }

        return redirect()->to("usuarios/detalle/{$usuario_id}");
    }

    /**
     * Devuelve el catálogo de tags con el conteo de usuarios asociados.
     * Utilizado por AJAX en el modal de selección de tags.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function tagsCatalog()
    {
        try {
            $tags = $this->tagsModel->allOrdered();
            $tagStats = $this->tagsModel->getUsersCountByTag();

            // Añadir el conteo de usuarios a cada tag
            foreach ($tags as &$tag) {
                $tag['user_count'] = $tagStats[$tag['slug']] ?? 0;
            }
            unset($tag); // Romper la referencia del último elemento

            return $this->response->setJSON([
                'ok'   => true,
                'data' => $tags
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'ok'        => false,
                'message'   => 'Error al obtener las etiquetas',
                'exception' => $e->getMessage()
            ]);
        }
    }

    public function actualizar() {
        # Obtenemos los datos del formulario...
        $usuario_id = $this->request->getPost('id');
        $rol_id = $this->request->getPost('rol_id');
        $nombre = $this->request->getPost('nombre');
        $correo = $this->request->getPost('correo');
        $cargo = $this->request->getPost('cargo');
        $telefono = $this->request->getPost('telefono');
        $contrasena = $this->request->getPost('contrasena');

        # Definimos inicialmente la variable area_id, que será NULL si no se recibe
        $area_id = $this->request->getPost('area_id') ?? NULL;

        # Obtenemos informacion del usuario...
        $usuario = $this->usuarios->obtenerUsuario($usuario_id);

        # Validamos si el usuario existe...
        if (empty($usuario)) {
            # No existe el usuario...
            return redirect()->to("usuarios/");
        }

        # Definimos las reglas de validación para los campos...
        $validationRules = [
            'id' => 'required|numeric',
            'rol_id' => 'required|numeric',
            'cargo' => 'required|min_length[1]|max_length[100]',
            'nombre' => 'required|min_length[3]|max_length[100]',
            'correo' => "required|valid_email|is_unique[tbl_usuarios.correo,id,{$usuario_id}]",
            'telefono' => "required|is_unique[tbl_usuarios.telefono,id,{$usuario_id}]"
        ];

        # Validamos los datos del formulario...
        if (!$this->validate($validationRules)) {
            # Guardamos los errores de la validación
            session()->setFlashdata('validation', $this->validator->getErrors());

            # Si la validación falla, almacenamos los errores y los datos en la sesión...
            return redirect()->to("usuarios/detalle/{$usuario_id}")->withInput();
        }

        # Creamos la variable de actualizacion...
        $infoUsuario = [
            'rol_id' => $rol_id,
            'area_id' => $area_id,
            'cargo' => $cargo,
            'nombre' => strtoupper($nombre),
            'correo' => $correo,
            'telefono' => $telefono,
            'contrasena' => $contrasena
        ];

        # Actualizamos la informacion de la cuenta...
        if ($this->usuarios->actualizarUsuario($usuario_id, $infoUsuario)) {
            # Registro en bitácora
            log_activity(
                $this->getUserId(),
                'Usuarios',
                'Actualización',
                [
                    'usuario_id' => $usuario_id,
                    'nombre' => $nombre,
                    'correo' => $correo,
                    'rol_id' => $rol_id
                ]
            );
            
            # Se actualizo la cuenta
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "Se ha actualizado la informacion del usuario de forma correcta.",
                'tipo' => "success"
            ]);

            return redirect()->to("usuarios/detalle/{$usuario_id}");
        } else {
            # No se actualizo la cuenta...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se pudo actualizar la informacion del usuario, intentalo nuevamente.",
                'tipo' => "danger"
            ]);

            return redirect()->to("usuarios/detalle/{$usuario_id}");
        }
    }
    
    public function crear() {
        # Obtenemos los datos del formulario...
        $rol_id = $this->request->getPost('rol_id');
        $cargo = $this->request->getPost('cargo');
        $nombre = $this->request->getPost('nombre');
        $correo = $this->request->getPost('correo');
        $telefono = $this->request->getPost('telefono');
        $contrasena = $this->request->getPost('contrasena');
        $area_id = $this->request->getPost('area_id') ?? NULL;

        // Si el rol es 5 (Operador) y no se proporciona area_id, se asigna a NULL
        if ($rol_id == 5 && empty($area_id)) {
            $area_id = NULL;
        }

        # Obtenemos datos de sesión para multitenencia y trazabilidad
        $creador_id = $this->getUserId();
        $cuenta_id = session('session_data.cuenta_id') ?? NULL;

        # Definimos las reglas de validación para los campos...
        $validationRules = [
            'rol_id' => 'required|numeric',
            'cargo' => 'required',
            'nombre' => 'required|min_length[3]|max_length[100]',
            'correo' => 'required|valid_email|is_unique[tbl_usuarios.correo]',
            'telefono' => 'required|numeric|is_unique[tbl_usuarios.telefono]',
            'contrasena' => 'required|min_length[6]',
            'confirmar_contrasena' => 'required_with[contrasena]|matches[contrasena]', // Añadir validación de confirmación
        ];

        # Validamos los datos del formulario...
        if (!$this->validate($validationRules)) {
            session()->setFlashdata('validation', $this->validator->getErrors());
            // Redirigir a la vista de creación de operador si viene de ahí
            if ($this->request->getPost('from_dependencia_crear_operador')) {
                return redirect()->to("usuarios/nuevo-operador/{$area_id}")->withInput();
            }
            return redirect()->to("usuarios/nuevo")->withInput();
        }

        # Armamos el arreglo con la información del nuevo usuario
        $infoUsuario = [
            'rol_id' => $rol_id,
            'area_id' => $area_id,
            'cargo' => $cargo,
            'nombre' => strtoupper($nombre),
            'correo' => $correo,
            'telefono' => $telefono,
            'contrasena' => $contrasena, // Contraseña en texto plano
            'creado_por_id' => $creador_id,
            'cuenta_id' => $cuenta_id
        ];

        # Creamos el nuevo usuario...
        if ($this->usuarios->crearUsuario($infoUsuario)) {
            $nuevoUsuarioId = $this->usuarios->insertID();
            
            $this->session->setFlashdata([
                'titulo' => "¡Éxito!",
                'mensaje' => "Se ha creado el usuario de forma correcta.",
                'tipo' => "success"
            ]);

            # Registro en bitácora (después de la creación exitosa)
            log_activity(
                $this->getUserId(),
                'Usuarios',
                'Creación',
                [
                    'usuario_id' => $nuevoUsuarioId,
                    'nombre' => $nombre,
                    'correo' => $correo,
                    'rol_id' => $rol_id
                ]
            );
            // Redirigir a la vista de detalle de dependencia si viene de ahí
            if ($this->request->getPost('from_dependencia_crear_operador')) {
                return redirect()->to("dependencias/detalle/{$area_id}");
            }
            return redirect()->to("usuarios");
        } else {
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se pudo crear el usuario, intenta nuevamente.",
                'tipo' => "danger"
            ]);
            // Redirigir a la vista de creación de operador si viene de ahí
            if ($this->request->getPost('from_dependencia_crear_operador')) {
                return redirect()->to("usuarios/nuevo-operador/{$area_id}")->withInput();
            }
            return redirect()->to("usuarios/nuevo");
        }
    }

    public function eliminar() {
        # Obtenemos la informacion del formulario...
        $usuario_id = $this->request->getPost('usuario_id');

        # Obtenemos informacion del usuario...
        $usuario = $this->usuarios->obtenerUsuario($usuario_id);

        # Validamos si el usuario existe...
        if (empty($usuario)) {
            # No existe el usuario...
            return redirect()->to("usuarios/");
        }

        # Validamos si el usuario que quiero eliminar es el de la sesion...
        if (session('session_data.id') === $usuario['id']) {
            # No se puede eliminar la misma cuenta desde esta opcion...
            $this->session->setFlashdata([
                'titulo' => "¡Atencion!",
                'mensaje' => "No puedes eliminar tu cuenta desde la seccion usuarios, "
                . "para hacerlo ingresa al perfil en la opcion eliminar. Recuerda que "
                . "uan vez que se elimine la cuenta no podras recuperarla.",
                'tipo' => "warning"
            ]);

            return redirect()->to("usuarios/");
        }

        # Eliminamos el usuario...
        if ($this->usuarios->eliminarUsuario($usuario_id)) {
            # Registro en bitácora (solo si hay un usuario logueado válido)
            $loggedInUserId = $this->getUserId();
            if ($loggedInUserId !== null) {
                log_activity(
                    $loggedInUserId,
                    'Usuarios',
                    'Eliminación',
                    [
                        'usuario_id' => $usuario_id,
                        'nombre' => $usuario['nombre'],
                        'correo' => $usuario['correo']
                    ]
                );
            }
            
            # Se elimino el usuario...
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "Se ha eliminado de forma correcta la cuenta del usuario.",
                'tipo' => "success"
            ]);

            return redirect()->to("usuarios/");
        } else {
            # No se pudo eliminar el usuario...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se pudo eliminar la cuenta del usuario, intentalo nuevamente.",
                'tipo' => "danger"
            ]);

            return redirect()->to("usuarios/detalle/{$usuario_id}");
        }
    }

    public function crearPermiso() {
        # Obtenemos la informacion del formulario...
        $usuario_id = $this->request->getPost('usuario_id');
        $modulo_id = $this->request->getPost('modulo_id');
        $lectura = $this->request->getPost('lectura') ? 1 : 0;
        $escritura = $this->request->getPost('escritura') ? 1 : 0;
        $actualizacion = $this->request->getPost('actualizacion') ? 1 : 0;
        $eliminacion = $this->request->getPost('eliminacion') ? 1 : 0;

        # Validamos si existe un permiso para este usuario en el modulo...
        $permiso = $this->permisos->obtenerPermisosPorUsuarioModulo($usuario_id, $modulo_id);

        # Obtenemos informacion del modulo...
        $modulo = $this->modulos->obtenerModulo($modulo_id);

        # Obtenemos informacion del usuario...
        $usuario = $this->usuarios->obtenerUsuario($usuario_id);

        if (!empty($permiso)) {
            # Existe ya un permiso para el modulo...
            $this->session->setFlashdata([
                'titulo' => "¡Atencion!",
                'mensaje' => "Ya se han asignado permisos para el usuario "
                . "<strong>{$usuario['nombre']}</strong> en el modulo de gestion"
                . " <strong>{$modulo['nombre']}</strong>.",
                'tipo' => "warning"
            ]);

            return redirect()->to("usuarios/detalle/{$usuario_id}");
        }

        # Creamos la variable de los permisos...
        $infoPermisos = [
            'modulo_id' => $modulo['id'],
            'usuario_id' => $usuario_id,
            'lectura' => $lectura,
            'escritura' => $escritura,
            'actualizacion' => $actualizacion,
            'eliminacion' => $eliminacion,
            'fecha_creacion' => date('Y-m-d H:i:s'),
        ];

        # Creamos los nuevos permisos...
        if ($this->permisos->crearPermiso($infoPermisos)) {
            $permisoId = $this->permisos->insertID();
            
            # Registro en bitácora (solo si hay un usuario logueado válido)
            $loggedInUserId = $this->getUserId();
            if ($loggedInUserId !== null) {
                log_activity(
                    $loggedInUserId,
                    'Permisos',
                    'Creación',
                    [
                        'permiso_id' => $permisoId,
                        'usuario_id' => $usuario_id,
                        'modulo_id' => $modulo_id,
                        'modulo_nombre' => $modulo['nombre']
                    ]
                );
            }
            
            # Se creo el permiso
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "Se han registrado los nuevos permisos para el "
                . "usuario en el modulo de gestion <strong>{$modulo['nombre']}</strong> de forma exitosa.",
                'tipo' => "success"
            ]);

            return redirect()->to("usuarios/detalle/{$usuario_id}");
        } else {
            # No se crearon los permisos...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se han podido registrar los nuevos permisos para el "
                . "usuario en el modulo de gestion <strong>{$modulo['nombre']}</strong>, intentalo nuevamnete.",
                'tipo' => "danger"
            ]);

            return redirect()->to("usuarios/detalle/{$usuario_id}");
        }
    }

    public function eliminarPermiso($permiso_id) {
        # Obtenemos informacion del permiso...
        $permiso = $this->permisos->obtenerPermiso($permiso_id);

        # Validamos si existe el permiso...
        if (empty($permiso)) {
            # No existe el permiso...
            return redirect()->to("usuarios/");
        }

        # Obtenemos informacion del usuario...
        $usuario = $this->usuarios->obtenerUsuario($permiso['usuario_id']);

        # Obtenemos informacion del modulo...
        $modulo = $this->modulos->obtenerModulo($permiso['modulo_id']);

        # Eliminamos el permiso...
        if ($this->permisos->eliminarPermiso($permiso_id)) {
            # Registro en bitácora (solo si hay un usuario logueado válido)
            $loggedInUserId = $this->getUserId();
            if ($loggedInUserId !== null) {
                log_activity(
                    $loggedInUserId,
                    'Permisos',
                    'Eliminación',
                    [
                        'permiso_id' => $permiso_id,
                        'usuario_id' => $permiso['usuario_id'],
                        'modulo_id' => $permiso['modulo_id'],
                        'modulo_nombre' => $modulo['nombre']
                    ]
                );
            }
            
            # Se elimino el permiso...
            $this->session->setFlashdata([
                'titulo' => "¡Exito!",
                'mensaje' => "Se han eliminado los permisos del modulo <strong>{$modulo['nombre']}</strong>"
                . " asociados al usuario <strong>{$usuario['nombre']}</strong> de forma exitosa.",
                'tipo' => "success"
            ]);

            return redirect()->to("usuarios/detalle/{$usuario['id']}");
        } else {
            # No se eliminaron los permisos...
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se han podido eliminar los permisos al modulo "
                . "<strong>{$modulo['nombre']}</strong>, intentalo nuevamente.",
                'tipo' => "danger"
            ]);

            return redirect()->to("usuarios/detalle/{$usuario['id']}");
        }
    }
}