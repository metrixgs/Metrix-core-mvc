<?php

namespace App\Controllers;

use App\Models\DirectorioModel;
use App\Controllers\BaseController;

class Directorio extends BaseController
{
    protected $directorioModel;

    public function __construct()
    {
        $this->directorioModel = new DirectorioModel();
        helper(['form', 'url', 'bitacora']);
    }

    public function index()
    {
        $page = $this->request->getVar('page') ?? 1;
        $perPage = $this->request->getVar('perPage') ?? 25; // Obtener perPage de la URL o usar 25 por defecto

        // Hacemos LEFT JOIN a sí mismo para obtener los datos del líder (si lo hay)
        $page = $this->request->getVar('page') ?? 1;
        $perPage = $this->request->getVar('perPage') ?? 25; // Obtener perPage de la URL o usar 25 por defecto

        // Construir el builder para la consulta principal
        $builder = $this->directorioModel
            ->select('directorio.*,
                      lider.nombre AS lider_nombre,
                      lider.primer_apellido AS lider_apellido,
                      lider.segundo_apellido AS lider_segundo')
            ->join('directorio AS lider', 'lider.id = directorio.id_lider', 'left');

        // Obtener el total de registros antes de aplicar la paginación
        $totalContactos = $builder->countAllResults(false);

        $contactos = [];
        $pager = null;

        // Si perPage es -1 (opción "Todos"), no paginar
        if ($perPage == -1) {
            $contactos = $builder->findAll();
            $perPage = $totalContactos; // Para que la vista sepa que se están mostrando todos
        } else {
            $contactos = $builder->paginate($perPage, 'group1');
            $pager = $this->directorioModel->pager;
        }
        
        $data = [
            'contactos' => $contactos,
            'pager' => $pager,
            'perPage' => $perPage,
            'totalContactos' => $totalContactos, // Pasar el total de contactos a la vista
            'titulo_pagina' => 'Directorio | Lista de Contactos'
        ];

        return view('incl/head-application', $data)
             . view('incl/header-application', $data)
             . view('incl/menu-admin', $data)
             . view('directorio/index', $data)
             . view('incl/footer-application', $data)
             . view('incl/scripts-application', $data);
    }

    public function crear()
    {
        $data['titulo_pagina'] = 'Directorio | Nuevo Contacto';

        // Obtener lista de líderes
        $data['lideres'] = $this->directorioModel
            ->select('id, nombre, primer_apellido, segundo_apellido')
            ->where('es_lider', 1)
            ->findAll();

        return view('incl/head-application', $data)
             . view('incl/header-application', $data)
             . view('incl/menu-admin', $data)
             . view('directorio/crear', $data)
             . view('incl/footer-application', $data)
             . view('incl/scripts-application', $data);
    }
    
    public function guardar()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'nombre'             => 'required|min_length[3]|max_length[100]',
            'primer_apellido'    => 'permit_empty|max_length[100]',
            'segundo_apellido'   => 'permit_empty|max_length[100]',
            'curp'               => 'permit_empty|exact_length[18]',
            'fecha_nacimiento'   => 'permit_empty|valid_date[Y-m-d]',
            'residencia'         => 'permit_empty|max_length[255]',
            'email'              => 'permit_empty|valid_email|max_length[150]',
            'telefono'           => 'permit_empty|max_length[50]',
            'direccion'          => 'permit_empty|max_length[255]',
            'estado'             => 'permit_empty|max_length[100]',
            'municipio'          => 'permit_empty|max_length[100]',
            'localidad'          => 'permit_empty|max_length[100]',
            'colonia'            => 'permit_empty|max_length[100]',
            'calle'              => 'permit_empty|max_length[100]',
            'numero_exterior'    => 'permit_empty|max_length[20]',
            'numero_interior'    => 'permit_empty|max_length[20]',
            'codigo_postal'      => 'permit_empty|max_length[10]',
            'empresa'            => 'permit_empty|max_length[100]',
            'cargo'              => 'permit_empty|max_length[100]',
            'tipo_cliente'       => 'permit_empty|max_length[50]',
            'nivel_estudios'     => 'permit_empty|max_length[100]',
            'ocupacion'          => 'permit_empty|max_length[100]',
            'tipo_discapacidad'  => 'permit_empty|max_length[100]',
            'grupo_etnico'       => 'permit_empty|max_length[100]',
            'acepta_avisos'      => 'permit_empty|in_list[0,1]',
            'acepta_terminos'    => 'permit_empty|in_list[0,1]',
            'id_lider'           => 'permit_empty|is_natural',
            'tipo_red'           => 'required|in_list[CDN,BNF,RED,EMP]',
            'latitud'            => 'permit_empty|decimal',
            'longitud'           => 'permit_empty|decimal',
        ];

        // Obtener datos del formulario y normalizar
        $data = $this->request->getPost(array_keys($rules));
        $data['acepta_avisos']  = $this->request->getPost('acepta_avisos') ? 1 : 0;
        $data['acepta_terminos']= $this->request->getPost('acepta_terminos') ? 1 : 0;

        // Validación
        if (! $validation->setRules($rules)->run($data)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Limpiar espacios
        $data['estado']    = trim($data['estado']);
        $data['municipio'] = trim($data['municipio']);

        // Generar código único
        $ultimo      = $this->directorioModel->orderBy('id', 'DESC')->first();
        $siguienteId = $ultimo ? $ultimo['id'] + 1 : 1;
        $data['codigo_ciudadano'] = 'CDZ-' . str_pad($siguienteId, 4, '0', STR_PAD_LEFT);

        // Guardar en DB (estado y municipio se guardan por separado)
        if ($this->directorioModel->save($data)) {
            $nuevoId = $this->directorioModel->getInsertID();
            
            // Registrar en bitácora
            $usuarioId = (int) (session('session_data')['usuario_id'] ?? 1);
            log_activity(
                $usuarioId,
                'Directorio',
                'Creación',
                [
                    'contacto_id' => $nuevoId,
                    'nombre' => $data['nombre'],
                    'tipo_red' => $data['tipo_red']
                ]
            );
            
            return redirect()->to('/directorio')->with('mensaje', 'Ciudadano registrado con éxito.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Error al guardar el contacto.');
        }
    }

    public function editar($id = null)
    {
        $contacto = $this->directorioModel->find($id);

        if (!$contacto) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('No se encontró el contacto con ID ' . $id);
        }

        // Obtener líderes para el select
        $lideres = $this->directorioModel
            ->select('id, nombre, primer_apellido, segundo_apellido')
            ->where('es_lider', 1)
            ->findAll();

        $data = [
            'contacto' => $contacto,
            'lideres' => $lideres,
            'titulo_pagina' => 'Directorio | Editar Contacto'
        ];

        return view('incl/head-application', $data)
             . view('incl/header-application', $data)
             . view('incl/menu-admin', $data)
             . view('directorio/editar', $data)
             . view('incl/footer-application', $data)
             . view('incl/scripts-application', $data);
    }
     
    public function actualizar($id = null)
    {
        // Verificar existencia del contacto
        $contacto = $this->directorioModel->find($id);
        if (!$contacto) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("No se encontró el contacto con ID $id");
        }

        $validation = \Config\Services::validation();

        $rules = [
            'nombre'             => 'required|min_length[3]|max_length[100]',
            'primer_apellido'    => 'permit_empty|max_length[100]',
            'segundo_apellido'   => 'permit_empty|max_length[100]',
            'curp'               => 'permit_empty|exact_length[18]',
            'old_curp'           => 'permit_empty',
            'fecha_nacimiento'   => 'permit_empty|valid_date[Y-m-d]',
            'residencia'         => 'permit_empty|max_length[255]',
            'email'              => 'permit_empty|valid_email|max_length[150]',
            'telefono'           => 'permit_empty|max_length[50]',
            'direccion'          => 'permit_empty|max_length[255]',
            'estado'             => 'permit_empty|max_length[100]',
            'municipio'          => 'permit_empty|max_length[100]',
            'localidad'          => 'permit_empty|max_length[100]',
            'colonia'            => 'permit_empty|max_length[100]',
            'calle'              => 'permit_empty|max_length[100]',
            'numero_exterior'    => 'permit_empty|max_length[20]',
            'numero_interior'    => 'permit_empty|max_length[20]',
            'codigo_postal'      => 'permit_empty|max_length[10]',
            'empresa'            => 'permit_empty|max_length[100]',
            'cargo'              => 'permit_empty|max_length[100]',
            'tipo_cliente'       => 'permit_empty|max_length[50]',
            'nivel_estudios'     => 'permit_empty|max_length[100]',
            'ocupacion'          => 'permit_empty|max_length[100]',
            'tipo_discapacidad'  => 'permit_empty|max_length[100]',
            'grupo_etnico'       => 'permit_empty|max_length[100]',
            'acepta_avisos'      => 'permit_empty|in_list[0,1]',
            'acepta_terminos'    => 'permit_empty|in_list[0,1]',
            'activo'             => 'permit_empty|in_list[0,1]',
            'id_lider'           => 'permit_empty|is_natural',
            'tipo_red'           => 'required|in_list[CDN,BNF,RED,EMP]',
            'latitud'            => 'permit_empty|decimal',
            'longitud'           => 'permit_empty|decimal',
        ];

        // Obtener datos del formulario
        $post = $this->request->getPost();
        $post['acepta_avisos']   = $this->request->getPost('acepta_avisos') ? 1 : 0;
        $post['acepta_terminos'] = $this->request->getPost('acepta_terminos') ? 1 : 0;
        $post['activo']          = $this->request->getPost('activo') ?? 1;

        // Validación
        if (!$validation->setRules($rules)->run($post)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Manejo de CURP: asegurarse de que no sea cadena vacía
        if (isset($post['curp'])) {
            $post['curp'] = trim($post['curp']);

            if ($post['curp'] === '') {
                // Si viene vacío, conservar el valor anterior o dejarlo en null
                $post['curp'] = !empty($post['old_curp']) ? $post['old_curp'] : null;
            }
        }
        unset($post['old_curp']);

        // Limpiar espacios en municipio y estado
        $post['municipio'] = isset($post['municipio']) ? trim($post['municipio']) : null;
        $post['estado']    = isset($post['estado']) ? trim($post['estado']) : null;

        // Asignar ID para la actualización
        $post['id'] = $id;

        // Guardar en la base de datos
        if ($this->directorioModel->save($post)) {
            $usuarioId = (int) (session('session_data')['usuario_id'] ?? 0);
            log_activity(
                $usuarioId,
                'Directorio',
                'Actualización',
                [
                    'contacto_id' => $id,
                    'nombre' => $post['nombre'],
                    'tipo_red' => $post['tipo_red']
                ]
            );
            
            return redirect()->to('/directorio')->with('mensaje', 'Contacto actualizado con éxito.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Error al actualizar el contacto.');
        }
    }

    public function eliminar($id = null)
    {
        $contacto = $this->directorioModel->find($id);

        if (!$contacto) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('No se encontró el contacto con ID ' . $id);
        }
        $usuarioId = (int) (session('session_data')['usuario_id'] ?? 0);
        log_activity(
            $usuarioId,
            'Directorio',
            'Eliminación',
            [
                'contacto_id' => $id,
                'nombre' => $contacto['nombre'],
                'tipo_red' => $contacto['tipo_red']
            ]
        );
        $this->directorioModel->delete($id);
        return redirect()->to('/directorio')->with('mensaje', 'Contacto eliminado con éxito.');
    }

    public function ver($id = null)
    {
        $contacto = $this->directorioModel->find($id);

        if (!$contacto) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('No se encontró el contacto con ID ' . $id);
        }

        $data = [
            'contacto' => $contacto,
            'titulo_pagina' => 'Directorio | Detalles del Ciudadano'
        ];

        return view('incl/head-application', $data)
             . view('incl/header-application', $data)
             . view('incl/menu-admin', $data)
             . view('directorio/ver', $data)
             . view('incl/footer-application', $data)
             . view('incl/scripts-application', $data);
    }

    public function mapa($id = null)
    {
        $contacto = $this->directorioModel->find($id);

        if (!$contacto || !$contacto['latitud'] || !$contacto['longitud']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('No se encontró ubicación para el contacto.');
        }

        $data = [
            'titulo_pagina' => 'Ubicación del Ciudadano',
            'contacto' => $contacto
        ];

        return view('incl/head-application', $data)
             . view('incl/header-application', $data)
             . view('incl/menu-admin', $data)
             . view('directorio/mapa', $data)
             . view('incl/footer-application', $data)
             . view('incl/scripts-application', $data);
   }

   public function importarCsv()
   {
       $validation = \Config\Services::validation();
       $file = $this->request->getFile('csvFile');

       // Reglas de validación para el archivo CSV
       $rules = [
           'csvFile' => [
               'uploaded[csvFile]',
               'mime_in[csvFile,text/csv]',
               'max_size[csvFile,5120]', // 5MB
           ],
       ];

       if (!$this->validate($rules)) {
           return redirect()->back()->with('error', 'Error al subir el archivo: ' . $validation->getError('csvFile'));
       }

       if (!$file->isValid() || $file->hasMoved()) {
           return redirect()->back()->with('error', 'Error al subir el archivo CSV.');
       }

       $filePath = $file->getTempName();
       $handle = fopen($filePath, 'r');

       if ($handle === FALSE) {
           return redirect()->back()->with('error', 'No se pudo abrir el archivo CSV.');
       }

       $header = fgetcsv($handle, 0, ','); // Leer la primera fila (encabezados)
       $rows = [];
       $insertedCount = 0;
       $errors = [];

       // Mapeo de columnas del CSV a campos de la base de datos
       $columnMapping = [
           'ID_DEL' => 'id_del',
           'NOM_DEL' => 'nom_del',
           'CVE_LOC' => 'cve_loc',
           'NOM_LOC' => 'nom_loc',
           'ID_COL' => 'id_col',
           'NOM_COL' => 'nom_col',
           'CVE_AGEB' => 'cve_ageb',
           'CVE_MZA' => 'cve_mza',
           'CU_MZA' => 'cu_mza',
           'CVE_CAT' => 'cve_cat',
           'CALLE' => 'calle',
           'NUM_EXT' => 'numero_exterior',
           'NO_INT' => 'numero_interior',
           'LETRA' => 'letra',
           'COLONIA' => 'colonia',
           'TIPO' => 'tipo',
           'ZONA' => 'zona',
           'SECTOR' => 'sector',
           'DISTRITO_F' => 'distrito_f',
           'DISTRITO_L' => 'distrito_l',
           'SECCION' => 'seccion',
           'CIRCUITO' => 'circuito',
           'DISTRITO_J' => 'distrito_j',
           'LAT' => 'latitud',
           'LON' => 'longitud',
           // Asegúrate de que todos los campos relevantes del CSV estén aquí
           // y que coincidan con los 'allowedFields' en DirectorioModel.php
       ];

       while (($row = fgetcsv($handle, 0, ',')) !== FALSE) {
           if (count($header) != count($row)) {
               $errors[] = "Fila con número de columnas incorrecto: " . implode(',', $row);
               continue;
           }
           $rowData = array_combine($header, $row);
           $dataToInsert = [];

           foreach ($columnMapping as $csvColumn => $dbField) {
               if (isset($rowData[$csvColumn])) {
                   $dataToInsert[$dbField] = $rowData[$csvColumn];
               }
           }

           // Campos adicionales que no vienen en el CSV pero son requeridos o tienen valores por defecto
           $dataToInsert['nombre'] = $rowData['NOM_DEL'] ?? 'N/A'; // O algún valor por defecto
           $dataToInsert['primer_apellido'] = $rowData['NOM_LOC'] ?? 'N/A'; // O algún valor por defecto
           $dataToInsert['tipo_red'] = 'CDN'; // Valor por defecto, ajustar si el CSV lo provee
           $dataToInsert['codigo_ciudadano'] = 'CDZ-' . uniqid(); // Generar un código único

           // Validar y guardar
           if ($this->directorioModel->insert($dataToInsert)) {
               $insertedCount++;
           } else {
               $errors[] = "Error al insertar la fila: " . implode(',', $row) . " - Errores: " . json_encode($this->directorioModel->errors());
           }
       }

       fclose($handle);

       if (!empty($errors)) {
           session()->setFlashdata('error', 'Se importaron ' . $insertedCount . ' registros con éxito, pero ocurrieron errores en algunas filas: ' . implode('; ', $errors));
       } else {
           session()->setFlashdata('mensaje', 'Se importaron ' . $insertedCount . ' registros desde el CSV con éxito.');
       }

       return redirect()->to('/directorio');
   }
}