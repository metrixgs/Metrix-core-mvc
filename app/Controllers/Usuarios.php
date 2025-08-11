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

class Usuarios extends BaseController {

    protected $usuarios;
    protected $tickets;
    protected $areas;
    protected $permisos;

    protected $modulos;
    protected $notificaciones;
    protected $roles; 
    protected $cuentas;

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

        # Cargar los Helpers
        helper(['Alerts', 'Email', 'Rol', 'Menu', 'bitacora']);
    }

    private function getUserId() {
        return session('session_data.usuario_id') ?? 0;
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

        # Validación automática de cuenta_id (solo para administradores)
        $rolActual = session('session_data.rol_id');
        if (in_array($rolActual, [1, 2, 3, 4])) {
            $this->usuarios->validarYCorregirCuentaId();
        }

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
        
        // ✅ Obtener nombre del rol y cuenta actual desde la sesión
        $rolId = session('session_data.rol_id');
        
        // ✅ Filtrar roles según el tipo de usuario que está creando
        if ($rolId == 2) {
            // ADMINISTRADOR: No puede crear roles globales (1, 2, 3, 4)
            $data['roles'] = $this->roles->where('id NOT IN (1, 2, 3, 4)')->findAll();
        } else {
            // Otros roles: Pueden ver todos los roles
            $data['roles'] = $this->roles->obtenerRoles();
        }
        $cuentaId = session('session_data.cuenta_id');

        $data['nombre_rol'] = '';
        $data['nombre_cuenta'] = '';
        
        // ✅ Verificar si es un rol global (MEGA_ADMIN=1, ADMINISTRADOR=2, MASTER=3, DESARROLLADOR=4)
        $rolesGlobales = [1, 2, 3, 4];
        $data['es_rol_global'] = in_array($rolId, $rolesGlobales);
        
        // ✅ Si es rol global con permisos de selección de cuenta (excluir ADMINISTRADOR)
        if ($data['es_rol_global'] && in_array($rolId, [1, 3, 4])) {
            $data['cuentas'] = $this->cuentas->obtenerCuentasActivas();
        }

        if ($rolId) {
            $rol = $this->roles->obtenerRol($rolId);
            $data['nombre_rol'] = $rol['nombre'] ?? 'Rol ID: ' . $rolId;
        }

        if ($cuentaId) {
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

    public function detalle($usuario_id) {
        $data['titulo_pagina'] = 'Metrix | Informacion del Usuario';
        
        $data['tickets'] = $this->tickets->obtenerTickets();
        $data['notificaciones'] = $this->notificaciones->obtenerNotificacionesPorUsuario(session('session_data.id'));
        
        $areas = $this->areas->obtenerAreas();
        $data['areas'] = $areas;

        // ✅ Obtener rol del usuario actual para filtrar roles disponibles
        $rolActual = session('session_data.rol_id');
        
        // ✅ Filtrar roles según el tipo de usuario que está editando
        if ($rolActual == 2) {
            // ADMINISTRADOR: No puede asignar roles globales (1, 2, 3, 4)
            $data['roles'] = $this->roles->where('id NOT IN (1, 2, 3, 4)')->findAll();
        } else {
            // Otros roles: Pueden ver todos los roles
            $data['roles'] = $this->roles->obtenerRoles();
        }

        $usuario = $this->usuarios->obtenerUsuario($usuario_id);
        $data['usuario'] = $usuario;

        if (empty($usuario)) {
            return redirect()->to("usuarios/");
        }

        $permisos = $this->permisos->obtenerPermisosUsuario($usuario_id);
        $data['permisos'] = $permisos;

        $modulos = $this->modulos->obtenerModulos();
        $data['modulos'] = $modulos;

        return view('incl/head-application', $data)
                . view('incl/header-application', $data)
                . view('incl/menu-admin', $data)
                . view('usuarios/detalle-usuario', $data)
                . view('incl/footer-application', $data)
                . view('incl/scripts-application', $data);
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

        # ✅ Validar permisos de rol según el usuario que está actualizando
        $rolActual = session('session_data.rol_id');
        if ($rolActual == 2 && in_array($rol_id, [1, 2, 3, 4])) {
            # ADMINISTRADOR no puede asignar roles globales
            session()->setFlashdata('error', 'No tiene permisos para asignar este rol.');
            return redirect()->to("usuarios/detalle/{$usuario_id}")->withInput();
        }

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
        $cuenta_seleccionada = $this->request->getPost('cuenta_id') ?? NULL;

        # Obtenemos datos de sesión para multitenencia y trazabilidad
        $creador_id = $this->getUserId();
        $rolCreadorId = session('session_data.rol_id');
        
        # ✅ Verificar si el creador es un rol global
        $rolesGlobales = [1, 2, 3, 4];
        $esRolGlobal = in_array($rolCreadorId, $rolesGlobales);
        
        # ✅ Asignar cuenta_id según el tipo de rol
        if ($esRolGlobal && in_array($rolCreadorId, [1, 3, 4]) && $cuenta_seleccionada) {
            # Si es rol con permisos de selección y seleccionó una cuenta específica
            $cuenta_id = $cuenta_seleccionada;
        } else {
            # Para ADMINISTRADOR y roles no globales, usar la cuenta de la sesión
            $cuenta_id = session('session_data.cuenta_id') ?? NULL;
        }

        # Definimos las reglas de validación para los campos...
        $validationRules = [
            'rol_id' => 'required|numeric',
            'cargo' => 'required',
            'nombre' => 'required|min_length[3]|max_length[100]',
            'correo' => 'required|valid_email|is_unique[tbl_usuarios.correo]',
            'telefono' => 'required|numeric|is_unique[tbl_usuarios.telefono]',
            'contrasena' => 'required|min_length[6]',
        ];
        
        # ✅ Agregar validación de cuenta_seleccionada para roles con permisos de selección
        if ($esRolGlobal && in_array($rolCreadorId, [1, 3, 4])) {
            $validationRules['cuenta_seleccionada'] = 'required|numeric';
        }

        # Validamos los datos del formulario...
        if (!$this->validate($validationRules)) {
            session()->setFlashdata('validation', $this->validator->getErrors());
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
            'contrasena' => $contrasena,
            'creado_por_id' => $creador_id,
            'cuenta_id' => $cuenta_id
        ];

        # Creamos el nuevo usuario...
        if ($this->usuarios->crearUsuario($infoUsuario)) {
            $nuevoUsuarioId = $this->usuarios->insertID();
            
            # Registro en bitácora
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
            
            $this->session->setFlashdata([
                'titulo' => "¡Éxito!",
                'mensaje' => "Se ha creado el usuario de forma correcta.",
                'tipo' => "success"
            ]);
            return redirect()->to("usuarios");
        } else {
            $this->session->setFlashdata([
                'titulo' => "¡Error!",
                'mensaje' => "No se pudo crear el usuario, intenta nuevamente.",
                'tipo' => "danger"
            ]);
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
            # Registro en bitácora
            log_activity(
                $this->getUserId(),
                'Usuarios',
                'Eliminación',
                [
                    'usuario_id' => $usuario_id,
                    'nombre' => $usuario['nombre'],
                    'correo' => $usuario['correo']
                ]
            );
            
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
            
            # Registro en bitácora
            log_activity(
                $this->getUserId(),
                'Permisos',
                'Creación',
                [
                    'permiso_id' => $permisoId,
                    'usuario_id' => $usuario_id,
                    'modulo_id' => $modulo_id,
                    'modulo_nombre' => $modulo['nombre']
                ]
            );
            
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
            # Registro en bitácora
            log_activity(
                $this->getUserId(),
                'Permisos',
                'Eliminación',
                [
                    'permiso_id' => $permiso_id,
                    'usuario_id' => $permiso['usuario_id'],
                    'modulo_id' => $permiso['modulo_id'],
                    'modulo_nombre' => $modulo['nombre']
                ]
            );
            
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