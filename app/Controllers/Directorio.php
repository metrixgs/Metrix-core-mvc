<?php

namespace App\Controllers;

use App\Models\DirectorioModel;
use App\Models\TagsModel;
use App\Models\DirectorioTagsModel;
use App\Controllers\BaseController;

class Directorio extends BaseController
{
    protected $directorioModel;
    protected $tagsModel;
    protected $directorioTagsModel;

    public function __construct()
    {
        $this->directorioModel = new DirectorioModel();
        $this->tagsModel = new TagsModel();
        $this->directorioTagsModel = new DirectorioTagsModel();
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
                      lider.segundo_apellido AS lider_segundo,
                      GROUP_CONCAT(tags.nombre ORDER BY tags.nombre ASC SEPARATOR ", ") AS tags_asociados')
            ->join('directorio AS lider', 'lider.id = directorio.id_lider', 'left')
            ->join('directorio_tags', 'directorio_tags.directorio_id = directorio.id', 'left')
            ->join('tags', 'tags.id = directorio_tags.tag_id', 'left')
            ->groupBy('directorio.id'); // Agrupar por el ID del directorio para obtener todos los tags

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
            'nombre'             => 'required|min_length|max_length',
            'primer_apellido'    => 'permit_empty|max_length',
            'segundo_apellido'   => 'permit_empty|max_length',
            'curp'               => 'permit_empty|exact_length',
            'fecha_nacimiento'   => 'permit_empty|valid_date[Y-m-d]',
            'residencia'         => 'permit_empty|max_length',
            'email'              => 'permit_empty|valid_email|max_length',
            'telefono'           => 'permit_empty|max_length',
            'direccion'          => 'permit_empty|max_length',
            'estado'             => 'permit_empty|max_length',
            'municipio'          => 'permit_empty|max_length',
            'localidad'          => 'permit_empty|max_length',
            'colonia'            => 'permit_empty|max_length',
            'calle'              => 'permit_empty|max_length',
            'numero_exterior'    => 'permit_empty|max_length',
            'numero_interior'    => 'permit_empty|max_length',
            'codigo_postal'      => 'permit_empty|max_length',
            'empresa'            => 'permit_empty|max_length',
            'cargo'              => 'permit_empty|max_length',
            'tipo_cliente'       => 'permit_empty|max_length',
            'nivel_estudios'     => 'permit_empty|max_length',
            'ocupacion'          => 'permit_empty|max_length',
            'tipo_discapacidad'  => 'permit_empty|max_length',
            'grupo_etnico'       => 'permit_empty|max_length',
            'acepta_avisos'      => 'permit_empty|in_list',
            'acepta_terminos'    => 'permit_empty|in_list',
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
            'nombre'             => 'required|min_length|max_length',
            'primer_apellido'    => 'permit_empty|max_length',
            'segundo_apellido'   => 'permit_empty|max_length',
            'curp'               => 'permit_empty|exact_length',
            'old_curp'           => 'permit_empty',
            'fecha_nacimiento'   => 'permit_empty|valid_date[Y-m-d]',
            'residencia'         => 'permit_empty|max_length',
            'email'              => 'permit_empty|valid_email|max_length',
            'telefono'           => 'permit_empty|max_length',
            'direccion'          => 'permit_empty|max_length',
            'estado'             => 'permit_empty|max_length',
            'municipio'          => 'permit_empty|max_length',
            'localidad'          => 'permit_empty|max_length',
            'colonia'            => 'permit_empty|max_length',
            'calle'              => 'permit_empty|max_length',
            'numero_exterior'    => 'permit_empty|max_length',
            'numero_interior'    => 'permit_empty|max_length',
            'codigo_postal'      => 'permit_empty|max_length',
            'empresa'            => 'permit_empty|max_length',
            'cargo'              => 'permit_empty|max_length',
            'tipo_cliente'       => 'permit_empty|max_length',
            'nivel_estudios'     => 'permit_empty|max_length',
            'ocupacion'          => 'permit_empty|max_length',
            'tipo_discapacidad'  => 'permit_empty|max_length',
            'grupo_etnico'       => 'permit_empty|max_length',
            'acepta_avisos'      => 'permit_empty|in_list',
            'acepta_terminos'    => 'permit_empty|in_list',
            'activo'             => 'permit_empty|in_list',
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

    public function editarTags($id = null)
    {
        $citizen = $this->directorioModel->find($id);

        if (!$citizen) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('No se encontró el ciudadano con ID ' . $id);
        }

        // Obtener todos los tags disponibles
        $allTags = $this->tagsModel->findAll();

        // Obtener los tags actuales del ciudadano
        $currentTags = $this->directorioTagsModel
                            ->select('tags.id, tags.nombre')
                            ->join('tags', 'tags.id = directorio_tags.tag_id')
                            ->where('directorio_id', $id)
                            ->findAll();
        
        $currentTagIds = array_column($currentTags, 'id');

        $data = [
            'citizen' => $citizen,
            'allTags' => $allTags,
            'currentTagIds' => $currentTagIds,
            'titulo_pagina' => 'Directorio | Editar Tags'
        ];

        return view('incl/head-application', $data)
             . view('incl/header-application', $data)
             . view('incl/menu-admin', $data)
             . view('directorio/editar_tags', $data)
             . view('incl/footer-application', $data)
             . view('incl/scripts-application', $data);
    }

    public function guardarTags($id = null)
    {
        $citizen = $this->directorioModel->find($id);

        if (!$citizen) {
            return redirect()->back()->with('error', 'Ciudadano no encontrado.');
        }

        $selectedTagIds = $this->request->getPost('tags') ?? [];

        // Eliminar tags existentes para este ciudadano
        $this->directorioTagsModel->where('directorio_id', $id)->delete();

        // Asociar los nuevos tags
        foreach ($selectedTagIds as $tagId) {
            $this->directorioTagsModel->addTagToDirectorio($id, (int) $tagId);
        }

        return redirect()->to('/directorio')->with('mensaje', 'Tags actualizados con éxito para ' . $citizen['nombre']);
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
           'NOMBRE(S) DE PILA' => 'nombre',
           'APELLIDO PATERNO' => 'primer_apellido',
           'APELLIDO MATERNO' => 'segundo_apellido',
           'GENERO' => 'genero',
           'LIDERAZGO' => 'nombre_liderazgo', // Mapear a nuevo campo
           'COORDINADOR' => 'nombre_coordinador', // Mapear a nuevo campo
           'EDAD' => 'edad', // Mapear a nuevo campo
           'TELEFONO' => 'telefono',
           'CURP (18 DIGITOS)' => 'curp',
           'CLAVE DE ELECTOR (18 DIGITOS)' => 'clave_elector', // Mapear a nuevo campo
           'FECHA DE NACIMIENTO' => 'fecha_nacimiento', // Se procesará aparte
           'SECCION' => 'seccion',
           'VIGENCIA' => 'vigencia', // Mapear a nuevo campo
           'CALLE' => 'calle',
           'NO. EXTERIOR' => 'numero_exterior',
           'NO. INTERIOR' => 'numero_interior',
           'COLONIA' => 'colonia',
           'CODIGO POSTAL' => 'codigo_postal',
           'DELEGACION' => 'nom_del', // Asumiendo que DELEGACION del CSV es nom_del
           'DIRECCION' => 'direccion',
           'MUNICIPIO' => 'municipio',
           'ESTADO' => 'estado',
           'LATITUD' => 'latitud',
           'LONGITUD' => 'longitud',
           'TIPO DE SANGRE' => 'tipo_sangre', // Mapear a nuevo campo
           // Las siguientes columnas son duplicadas o para la sección de "servicios"
           // 'NOMBRE(S) DE PILA' => 'nombre', // Duplicado, se usa el primero
           // 'APELLIDO PATERNO' => 'primer_apellido', // Duplicado, se usa el primero
           // 'APELLIDO MATERNO' => 'segundo_apellido', // Duplicado, se usa el primero
           // 'TELEFONO' => 'telefono', // Duplicado, se usa el primero
           'SERVICIOS' => 'servicios', // Mapear a nuevo campo
           'TARIFA' => 'tarifa', // Mapear a nuevo campo
           'CATEGORIA' => 'categoria', // Mapear a nuevo campo
           'DIAS' => 'dias', // Mapear a nuevo campo
           'HORARIOS' => 'horarios', // Mapear a nuevo campo
           'DISCAPACIDAD' => 'tipo_discapacidad', // Mapear a campo existente
           'TIPO DE DISC' => 'tipo_discapacidad', // Duplicado, se usa el anterior
           'DESCUENTO' => 'descuento', // Mapear a nuevo campo
           'ESTATUS' => 'activo', // Mapear a campo existente (activo)
           'AÑO' => 'anio', // Mapear a nuevo campo
           'PAQUETE' => 'paquete', // Mapear a nuevo campo
           'TAGS' => 'tags_csv', // Campo especial para procesar tags
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

           // Procesar FECHA DE NACIMIENTO si existe
           if (isset($rowData['FECHA DE NACIMIENTO'])) {
               $dataToInsert['fecha_nacimiento'] = $this->excelSerialDateToDate($rowData['FECHA DE NACIMIENTO']);
           }

           // Campos adicionales que no vienen en el CSV pero son requeridos o tienen valores por defecto
           $dataToInsert['tipo_red'] = $dataToInsert['tipo_red'] ?? 'CDN'; // Valor por defecto, ajustar si el CSV lo provee
           $dataToInsert['codigo_ciudadano'] = 'CDZ-' . uniqid(); // Generar un código único

           // Asegurarse de que 'activo' sea 0 o 1
           if (isset($rowData['ESTATUS'])) { // Usar la columna 'ESTATUS' del CSV
               $dataToInsert['activo'] = (strtolower($rowData['ESTATUS']) === 'activo' || $rowData['ESTATUS'] === '1') ? 1 : 0;
           } else {
               $dataToInsert['activo'] = 1; // Por defecto activo
           }

           // Asegurarse de que 'descuento' sea 0 o 1
           if (isset($rowData['DESCUENTO'])) { // Usar la columna 'DESCUENTO' del CSV
               $dataToInsert['descuento'] = (strtolower($rowData['DESCUENTO']) === 'si' || $rowData['DESCUENTO'] === '1') ? 1 : 0;
           } else {
               $dataToInsert['descuento'] = 0; // Por defecto sin descuento
           }

           // Convertir tarifa a float si existe
           if (isset($dataToInsert['tarifa'])) {
               $dataToInsert['tarifa'] = (float) str_replace(',', '', $dataToInsert['tarifa']);
           }

           // Verificar si el ciudadano ya existe por CURP
           $existingCitizen = null;
           if (isset($dataToInsert['curp']) && !empty($dataToInsert['curp'])) {
               $existingCitizen = $this->directorioModel->where('curp', $dataToInsert['curp'])->first();
           }

           $citizenId = null;
           $operation = '';

           if ($existingCitizen) {
               // Actualizar registro existente
               $citizenId = $existingCitizen['id'];
               $dataToInsert['id'] = $citizenId; // Asegurar que el ID esté presente para la actualización
               // No generar un nuevo codigo_ciudadano si ya existe
               unset($dataToInsert['codigo_ciudadano']);

               if ($this->directorioModel->update($citizenId, $dataToInsert)) {
                   $insertedCount++;
                   $operation = 'actualizado';
               } else {
                   $errors[] = "Error al actualizar la fila (CURP: {$dataToInsert['curp']}): " . implode(',', $row) . " - Errores: " . json_encode($this->directorioModel->errors());
               }
           } else {
               // Insertar nuevo registro
               // Generar un nuevo codigo_ciudadano solo para nuevas inserciones
               $dataToInsert['codigo_ciudadano'] = 'CDZ-' . uniqid();
               if ($this->directorioModel->insert($dataToInsert)) {
                   $citizenId = $this->directorioModel->getInsertID();
                   $insertedCount++;
                   $operation = 'insertado';
               } else {
                   $errors[] = "Error al insertar la fila: " . implode(',', $row) . " - Errores: " . json_encode($this->directorioModel->errors());
               }
           }

           // Si la operación fue exitosa (inserción o actualización), procesar tags
           if ($citizenId) {
               // Lógica para generar y asociar tags
               $tagsToAssociate = [];

               // Tags basados en EDAD
               if (isset($dataToInsert['edad']) && is_numeric($dataToInsert['edad'])) {
                   $edad = (int) $dataToInsert['edad'];
                   if ($edad < 18) {
                       $tagsToAssociate[] = 'Joven';
                   } elseif ($edad >= 18 && $edad < 60) {
                       $tagsToAssociate[] = 'Adulto';
                   } else {
                       $tagsToAssociate[] = 'Adulto Mayor';
                   }
               }

               // Tags del CSV (columna 'TAGS')
               if (isset($rowData['TAGS']) && !empty($rowData['TAGS'])) {
                   $csvTags = explode(',', $rowData['TAGS']);
                   foreach ($csvTags as $csvTag) {
                       $tagsToAssociate[] = trim($csvTag);
                   }
               }

               // Procesar y asociar tags
               // Primero, eliminar tags existentes para este ciudadano si es una actualización
               if ($operation === 'actualizado') {
                   $this->directorioTagsModel->where('directorio_id', $citizenId)->delete();
               }

               foreach (array_unique($tagsToAssociate) as $tagName) {
                   if (!empty($tagName)) {
                       $tagId = $this->tagsModel->getOrCreateTag($tagName);
                       $this->directorioTagsModel->addTagToDirectorio($citizenId, $tagId);
                   }
               }
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

   // Función auxiliar para convertir número de serie de Excel a fecha Y-m-d
   private function excelSerialDateToDate($serial): ?string
   {
       if (!is_numeric($serial) || $serial <= 0) {
           return null;
       }
       // Excel base date is 1899-12-30 for Windows, 1904-01-01 for Mac.
       // Assuming Windows base date for now.
       // 25569 is the number of days between 1900-01-01 and 1970-01-01 (Unix epoch)
       // Excel day 1 is 1900-01-01. PHP's date() starts from Unix epoch.
       $unixTimestamp = ($serial - 25569) * 86400;
       return date('Y-m-d', $unixTimestamp);
   }
}