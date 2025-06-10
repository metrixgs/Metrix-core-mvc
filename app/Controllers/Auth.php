<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuariosModel;
use App\Models\ConfiguracionModel;

class Auth extends BaseController {

    protected $usuarios;
    protected $configuracion;

    public function __construct() {
        // Instanciar los modelos
        $this->usuarios = new UsuariosModel();
        $this->configuracion = new ConfiguracionModel();

        # Cargar los Helpers
        helper('Alerts');
        helper('Email');
    }

    public function index() {
        $data['titulo_pagina'] = 'KeyMaster | Iniciar sesion';

        return view('autenticacion/auth-signin', $data);
    }

    public function registro() {
        $data['titulo_pagina'] = 'KeyMaster | Crear cuenta';

        return view('autenticacion/auth-signout', $data);
    }

    public function recuperar() {
        $data['titulo_pagina'] = 'KeyMaster | Crear cuenta';

        return view('autenticacion/auth-recovery', $data);
    }

    public function recovery() {
        # Obtenemos la informacion del formulario...
        $correo = $this->request->getPost('correo');

        # Definimos las reglas de validación para los campos...
        $validationRules = [
            'correo' => 'required|valid_email',
        ];

        # Validamos los datos del formulario...
        if (!$this->validate($validationRules)) {
            # El correo no tiene el formato correcto...
            $this->session->setFlashdata([
                'titulo' => '¡Error!',
                'mensaje' => 'revisa el correo e intentalo nuevamente.',
                'tipo' => 'danger'
            ]);
            return redirect()->to('autenticacion/recuperar')->withInput();
        }

        # Obtenemos informacion del usuario con el correo...
        $usuario = $this->usuarios->obtenerUsuarioPorCorreo($correo);

        # Validamos si existe el correo en la base de datos...
        if (empty($usuario)) {
            # No xiste el correo...
            $this->session->setFlashdata([
                'titulo' => '¡Error!',
                'mensaje' => 'El correo no esta registrado.',
                'tipo' => 'danger'
            ]);
            return redirect()->to('autenticacion/recuperar')->withInput();
        }

        # Creamos las variables del correo...
        $destinatarios = [$usuario['correo']];
        $asunto = 'Recuperación de Cuenta';
        $mensaje = '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Recuperación de Cuenta</title>
        </head>
        <body style="font-family: Arial, sans-serif; background-color: #f9f9f9; margin: 0; padding: 0;">
            <table style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border: 1px solid #dddddd; border-radius: 8px; padding: 20px;">
                <tr>
                    <td style="text-align: center; padding-bottom: 20px;">
                        <img src="' . base_url() . "public/files/images/logo-dark.png" . '" alt="Logo de la empresa" style="max-width: 150px;">
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left; font-size: 16px; color: #333333;">
                        <p style="margin: 0 0 10px;">Hola,</p>
                        <p style="margin: 0 0 10px;">Hemos recibido una solicitud para recuperar tu cuenta. A continuación, encontrarás tus datos de acceso:</p>
                        <p style="margin: 0 0 10px;"><strong>Correo:</strong> ' . htmlspecialchars($usuario['correo']) . '</p>
                        <p style="margin: 0 0 10px;"><strong>Contraseña:</strong> ' . htmlspecialchars($usuario['contrasena']) . '</p>
                        <p style="margin: 0 0 20px;">Te recomendamos cambiar esta contraseña tan pronto como inicies sesión.</p>
                        <p style="margin: 0 0 20px;">Si no has solicitado esta recuperación de cuenta, por favor, ignora este mensaje o ponte en contacto con nuestro equipo de soporte.</p>
                        <p style="margin: 0; text-align: center;">
                            <a href="' . base_url() . "autenticacion/inicio" . '" style="display: inline-block; background-color: #007bff; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 5px; font-size: 16px;">Iniciar Sesión</a>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; padding-top: 20px; font-size: 12px; color: #999999;">
                        <p style="margin: 0;">© ' . date('Y') . ' Tu Empresa. Todos los derechos reservados.</p>
                        <p style="margin: 0;">Este correo fue enviado automáticamente, por favor, no respondas.</p>
                    </td>
                </tr>
            </table>
        </body>
        </html>';

        $adjuntos = ['public/files/images/logo-dark.png'];
        $esHtml = true;

        # Creamos y enviamos el correo...
        if (enviarCorreo($destinatarios, $asunto, $mensaje, $adjuntos, $esHtml)) {
            # Correo enviado exitosamente...
            $this->session->setFlashdata([
                'titulo' => '¡Éxito!',
                'mensaje' => 'El correo de recuperación ha sido enviado exitosamente a tu dirección registrada. '
                . 'Por favor, revisa tu bandeja de entrada. Si no lo encuentras, no olvides verificar la bandeja de spam.',
                'tipo' => 'success'
            ]);
            return redirect()->to('autenticacion/recuperar')->withInput();
        } else {
            # No se pudo enviar el correo...
            $this->session->setFlashdata([
                'titulo' => '¡Error!',
                'mensaje' => 'No se ha podido enviar el correo intentalo nuevamente.',
                'tipo' => 'danger'
            ]);
            return redirect()->to('autenticacion/recuperar')->withInput();
        }
    }

   public function validar() {
    // Obtener datos del formulario
    $usuario = $this->request->getPost('usuario');
    $contrasena = $this->request->getPost('contrasena');

    // Validación básica de los campos
    $validationRules = [
        'usuario' => 'required|valid_email',
        'contrasena' => 'required|min_length[8]',
    ];

    if (!$this->validate($validationRules)) {
        $this->session->setFlashdata([
            'titulo' => '¡Error!',
            'mensaje' => 'El usuario o la contraseña es inválido.',
            'tipo' => 'danger'
        ]);
        return redirect()->to('autenticacion/inicio')->withInput();
    }

    // Buscar usuario por correo
    $cuenta = $this->usuarios->obtenerUsuarioPorCorreo($usuario);

    if (empty($cuenta)) {
        $this->session->setFlashdata([
            'titulo' => '¡Error!',
            'mensaje' => 'El usuario no está registrado.',
            'tipo' => 'danger'
        ]);
        return redirect()->to('login')->withInput();
    }

    // Comparar contraseña directamente (SIN hasheo)
    if ($cuenta['contrasena'] != $contrasena) {
        $this->session->setFlashdata([
            'titulo' => '¡Error!',
            'mensaje' => 'La contraseña es incorrecta.',
            'tipo' => 'danger'
        ]);
        return redirect()->to('login')->withInput();
    }

    // Verificar que exista rol_id y cuenta_id
     if (!isset($cuenta['rol_id']) || (!isset($cuenta['cuenta_id']) && $cuenta['rol_id'] != 1)) {

        $this->session->setFlashdata([
            'titulo' => '¡Error!',
            'mensaje' => 'Tu cuenta está incompleta. Contacta al administrador.',
            'tipo' => 'danger'
        ]);
        return redirect()->to('login');
    }

    // Guardar datos mínimos en la sesión
    session()->set('session_data', [
        'usuario_id' => $cuenta['id'],
        'nombre'     => $cuenta['nombre'],
        'correo'     => $cuenta['correo'],
        'rol_id'     => $cuenta['rol_id'],
        'cuenta_id'  => $cuenta['cuenta_id']
    ]);

    // Obtener configuración general del sistema
    $configuracion = $this->configuracion->obtenerConfiguracion(1);
    session()->set('configuracion', $configuracion);

    return redirect()->to('panel');
}
    public function crear() {
        # Obtenemos la informacion del formulario...
        $nombre = $this->request->getPost('nombre');
        $nit = $this->request->getPost('nit');
        $responsable = $this->request->getPost('responsable');
        $telefono = $this->request->getPost('telefono');
        $correo = $this->request->getPost('correo');
        $contrasena = $this->request->getPost('contrasena');

        # Definimos las reglas de validación para los campos...
        $validationRules = [
            'nombre' => 'required',
            'nit' => 'required|min_length[8]|max_length[15]|is_unique[tbl_empresas.identificacion]',
            'responsable' => 'required',
            'telefono' => 'required|numeric|min_length[7]|max_length[15]|is_unique[tbl_empresas.telefono]',
            'correo' => 'required|valid_email|is_unique[tbl_empresas.correo]',
            'contrasena' => 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/]',
        ];

        # Validamos los datos del formulario...
        if (!$this->validate($validationRules)) {
            # Guardamos los errores de la validación
            session()->setFlashdata('validation', $this->validator->getErrors());

            # Si la validación falla, almacenamos los errores y los datos en la sesión...
            return redirect()->to('autenticacion/registro')->withInput();
        }

        # Creamos la variable para crear la empresa...
        $infoEmpresa = [
            'nombre' => strtoupper($nombre),
            'identificacion' => $nit,
            'correo' => $correo,
            'telefono' => $telefono,
            'responsable' => strtoupper($responsable),
            'direccion' => '',
            'estado' => 1
        ];

        # Creamos la empresa...
        if ($this->empresas->crearEmpresa($infoEmpresa)) {
            # Se ccreo la empresa...
            $this->session->setFlashdata([
                'titulo' => '¡Exito!',
                'mensaje' => 'Se ha registrado la empresa de forma exitosa.',
                'tipo' => 'success'
            ]);

            # Obtenemos el ultimo id de ka empresa...
            $empresa_id = $this->empresas->obtenerIDUltimoRegistro();

            # Creamos la variable para crear la cuenta del usuario...
            $infoUsuario = [
                'rol_id' => 1,
                'empresa_id' => $empresa_id,
                'condominio_id' => NULL,
                'nombre' => strtoupper($responsable),
                'correo' => $correo,
                'contrasena' => $contrasena,
                'telefono' => $telefono,
                'estado' => 1
            ];

            # Creamos la cuenta del usuario...
            if ($this->usuarios->crearUsuario($infoUsuario)) {
                # Se creo el usuario administrador...
                $this->session->setFlashdata([
                    'titulo' => '¡Exitos!',
                    'mensaje' => 'Se ha creado el usuario de forma correcta.',
                    'tipo' => 'success'
                ]);
                return redirect()->to('autenticacion/inicio');
            } else {
                # No se pudo crear el usuario...
                $this->session->setFlashdata([
                    'titulo' => '¡Error!',
                    'mensaje' => 'No se pudo registrar el usuario, intentalo nuevamente.',
                    'tipo' => 'danger'
                ]);
                return redirect()->to('autenticacion/registro')->withInput();
            }
        } else {
            # No se creo la empresa...
            $this->session->setFlashdata([
                'titulo' => '¡Error!',
                'mensaje' => 'No se pudo crear la empresa, intentalo nuevamenete.',
                'tipo' => 'danger'
            ]);
            return redirect()->to('autenticacion/registro')->withInput();
        }
    }

    public function unauthorized() {
        echo "No estas autorizado";
    }

    public function logout() {
        session()->destroy();
        return redirect()->to('/login');
    }
}
