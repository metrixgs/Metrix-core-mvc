<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DemoMetrixModel;

class DashboardMetrix extends BaseController
{
    protected $demoMetrixModel;

    public function __construct()
    {
        $this->demoMetrixModel = new DemoMetrixModel();
    }

    public function index()
    {
        // Obtener programas sociales para el selector
        $data['programas'] = $this->demoMetrixModel->getProgramasSociales();
        
        // Métricas iniciales (sin filtro de programa)
        $data['total_beneficiarios'] = $this->demoMetrixModel->getTotalBeneficiarios();
        $data['beneficiarios_unicos'] = $this->demoMetrixModel->getBeneficiariosUnicos();
        $data['beneficiarios_recurrentes'] = $this->demoMetrixModel->getBeneficiariosRecurrentes();
        
        // Datos para gráficos
        $data['genero_data'] = $this->demoMetrixModel->getBeneficiariosPorGenero();
        $data['edad_data'] = $this->demoMetrixModel->getBeneficiariosPorEdad();
        $data['municipios_data'] = $this->demoMetrixModel->getTop5Municipios();
        $data['secciones_data'] = $this->demoMetrixModel->getTop15Secciones();
        
        // Datos para selectores de comparativas
        $data['distritos_federales'] = $this->demoMetrixModel->getDistritosFederales();
        $data['distritos_locales'] = $this->demoMetrixModel->getDistritosLocales();
        $data['liderazgos'] = $this->demoMetrixModel->getLiderazgos();
        $data['secciones'] = $this->demoMetrixModel->getSecciones();
        
        return view('dashboard/metrix', $data);
    }

    // AJAX: Actualizar métricas por programa
    public function updateMetrics()
    {
        $programa = $this->request->getPost('programa');
        
        $data = [
            'total_beneficiarios' => $this->demoMetrixModel->getTotalBeneficiarios($programa),
            'beneficiarios_unicos' => $this->demoMetrixModel->getBeneficiariosUnicos($programa),
            'beneficiarios_recurrentes' => $this->demoMetrixModel->getBeneficiariosRecurrentes($programa),
            'genero_data' => $this->demoMetrixModel->getBeneficiariosPorGenero($programa),
            'edad_data' => $this->demoMetrixModel->getBeneficiariosPorEdad($programa),
            'municipios_data' => $this->demoMetrixModel->getTop5Municipios($programa),
            'secciones_data' => $this->demoMetrixModel->getTop15Secciones($programa)
        ];
        
        return $this->response->setJSON($data);
    }

    // AJAX: Comparativa DFs
    public function comparativaDFs()
    {
        $dfs = $this->request->getPost('dfs');
        $programa = $this->request->getPost('programa');
        
        if (!is_array($dfs)) {
            $dfs = explode(',', $dfs);
        }
        
        $data = $this->demoMetrixModel->getComparativaDFs($dfs, $programa);
        
        return $this->response->setJSON($data);
    }

    // AJAX: Comparativa DLs
    public function comparativaDLs()
    {
        $dls = $this->request->getPost('dls');
        $programa = $this->request->getPost('programa');
        
        if (!is_array($dls)) {
            $dls = explode(',', $dls);
        }
        
        $data = $this->demoMetrixModel->getComparativaDLs($dls, $programa);
        
        return $this->response->setJSON($data);
    }

    // AJAX: Comparativa Liderazgos
    public function comparativaLiderazgos()
    {
        $liderazgos = $this->request->getPost('liderazgos');
        $programa = $this->request->getPost('programa');
        
        if (!is_array($liderazgos)) {
            $liderazgos = explode(',', $liderazgos);
        }
        
        $data = $this->demoMetrixModel->getComparativaLiderazgos($liderazgos, $programa);
        
        return $this->response->setJSON($data);
    }

    // AJAX: Comparativa Secciones Electorales
    public function comparativaSecciones()
    {
        $secciones = $this->request->getPost('secciones');
        $programa = $this->request->getPost('programa');
        
        if (!is_array($secciones)) {
            $secciones = explode(',', $secciones);
        }
        
        $data = $this->demoMetrixModel->getComparativaSecciones($secciones, $programa);
        
        return $this->response->setJSON($data);
    }

    // AJAX: Obtener opciones actualizadas para selectores
    public function getSelectorsData()
    {
        $programa = $this->request->getPost('programa');
        
        $data = [
            'distritos_federales' => $this->demoMetrixModel->getDistritosFederales($programa),
            'distritos_locales' => $this->demoMetrixModel->getDistritosLocales($programa),
            'liderazgos' => $this->demoMetrixModel->getLiderazgos($programa),
            'secciones' => $this->demoMetrixModel->getSecciones($programa)
        ];
        
        return $this->response->setJSON($data);
    }
}