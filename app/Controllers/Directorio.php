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
        helper(['form', 'url']);
    }

   public function index()
{
    $page = $this->request->getVar('page') ?? 1;
    $perPage = 7;

    // Hacemos LEFT JOIN a sí mismo para obtener los datos del líder (si lo hay)
    $builder = $this->directorioModel
        ->select('directorio.*, 
                  lider.nombre AS lider_nombre, 
                  lider.primer_apellido AS lider_apellido, 
                  lider.segundo_apellido AS lider_segundo')
        ->join('directorio AS lider', 'lider.id = directorio.id_lider', 'left');

    $contactos = $builder->paginate($perPage, 'group1');
    $pager = $this->directorioModel->pager;

    $data = [
        'contactos' => $contactos,
        'pager' => $pager,
        'perPage' => $perPage,
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
        'nombre' => 'required|min_length[3]|max_length[100]',
        'primer_apellido' => 'permit_empty|max_length[100]',
        'segundo_apellido' => 'permit_empty|max_length[100]',
        'curp' => 'permit_empty|exact_length[18]',
        'fecha_nacimiento' => 'permit_empty|valid_date[Y-m-d]',
        'residencia' => 'permit_empty|max_length[255]',
        'email' => 'permit_empty|valid_email|max_length[150]',
        'telefono' => 'permit_empty|max_length[50]',
        'direccion' => 'permit_empty|max_length[255]',
        'estado' => 'permit_empty|max_length[100]',
        'municipio' => 'permit_empty|max_length[100]',
        'localidad' => 'permit_empty|max_length[100]',
        'colonia' => 'permit_empty|max_length[100]',
        'calle' => 'permit_empty|max_length[100]',
        'numero_exterior' => 'permit_empty|max_length[20]',
        'numero_interior' => 'permit_empty|max_length[20]',
        'codigo_postal' => 'permit_empty|max_length[10]',
        'empresa' => 'permit_empty|max_length[100]',
        'cargo' => 'permit_empty|max_length[100]',
        'tipo_cliente' => 'permit_empty|max_length[50]',
        'nivel_estudios' => 'permit_empty|max_length[100]',
        'ocupacion' => 'permit_empty|max_length[100]',
        'tipo_discapacidad' => 'permit_empty|max_length[100]',
        'grupo_etnico' => 'permit_empty|max_length[100]',
        'acepta_avisos' => 'permit_empty|in_list[0,1]',
        'acepta_terminos' => 'permit_empty|in_list[0,1]',
        'id_lider' => 'permit_empty|is_natural',
        'tipo_red' => 'required|in_list[CDN,BNF,RED,EMP]'
    ];

    $data = $this->request->getPost(array_keys($rules));
    $data['acepta_avisos'] = $this->request->getPost('acepta_avisos') ? 1 : 0;
    $data['acepta_terminos'] = $this->request->getPost('acepta_terminos') ? 1 : 0;

    if (!$validation->setRules($rules)->run($data)) {
        return redirect()->back()->withInput()->with('errors', $validation->getErrors());
    }

    // Generar código único
    $ultimo = $this->directorioModel->orderBy('id', 'DESC')->first();
    $siguienteId = $ultimo ? $ultimo['id'] + 1 : 1;
    $data['codigo_ciudadano'] = 'CDZ-' . str_pad($siguienteId, 4, '0', STR_PAD_LEFT);

    $this->directorioModel->save($data);

    return redirect()->to('/directorio')->with('mensaje', 'Ciudadano registrado con éxito.');
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
    $contacto = $this->directorioModel->find($id);

    if (!$contacto) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException("No se encontró el contacto con ID $id");
    }

    $validation = \Config\Services::validation();

    $rules = [
        'nombre' => 'required|min_length[3]|max_length[100]',
        'primer_apellido' => 'permit_empty|max_length[100]',
        'segundo_apellido' => 'permit_empty|max_length[100]',
        'curp' => 'permit_empty|exact_length[18]',
        'fecha_nacimiento' => 'permit_empty|valid_date[Y-m-d]',
        'residencia' => 'permit_empty|max_length[255]',
        'email' => 'permit_empty|valid_email|max_length[150]',
        'telefono' => 'permit_empty|max_length[50]',
        'direccion' => 'permit_empty|max_length[255]',
        'estado' => 'permit_empty|max_length[100]',
        'municipio' => 'permit_empty|max_length[100]',
        'localidad' => 'permit_empty|max_length[100]',
        'colonia' => 'permit_empty|max_length[100]',
        'calle' => 'permit_empty|max_length[100]',
        'numero_exterior' => 'permit_empty|max_length[20]',
        'numero_interior' => 'permit_empty|max_length[20]',
        'codigo_postal' => 'permit_empty|max_length[10]',
        'empresa' => 'permit_empty|max_length[100]',
        'cargo' => 'permit_empty|max_length[100]',
        'tipo_cliente' => 'permit_empty|max_length[50]',
        'nivel_estudios' => 'permit_empty|max_length[100]',
        'ocupacion' => 'permit_empty|max_length[100]',
        'tipo_discapacidad' => 'permit_empty|max_length[100]',
        'grupo_etnico' => 'permit_empty|max_length[100]',
        'acepta_avisos' => 'permit_empty|in_list[0,1]',
        'acepta_terminos' => 'permit_empty|in_list[0,1]',
        'activo' => 'permit_empty|in_list[0,1]',
        'id_lider' => 'permit_empty|is_natural',
        'tipo_red' => 'required|in_list[CDN,BNF,RED,EMP]'
    ];

    $post = $this->request->getPost();
    $post['acepta_avisos'] = $this->request->getPost('acepta_avisos') ? 1 : 0;
    $post['acepta_terminos'] = $this->request->getPost('acepta_terminos') ? 1 : 0;
    $post['activo'] = $this->request->getPost('activo') ?? 1;

    if (!$validation->setRules($rules)->run($post)) {
        return redirect()->back()->withInput()->with('errors', $validation->getErrors());
    }

    $post['id'] = $id;
    $this->directorioModel->save($post);

    return redirect()->to('/directorio')->with('mensaje', 'Contacto actualizado con éxito.');
}


    public function eliminar($id = null)
    {
        $contacto = $this->directorioModel->find($id);

        if (!$contacto) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('No se encontró el contacto con ID ' . $id);
        }

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


}
