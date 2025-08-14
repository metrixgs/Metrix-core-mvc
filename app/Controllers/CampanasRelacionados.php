<?php
namespace App\Controllers;

use App\Controllers\BaseController;

class CampanasRelacionados extends BaseController
{
    private function cargarVistaCompleta($vista, $data)
    {
        return view('incl/head-application', $data)
            . view('incl/header-application', $data)
            . view('incl/menu-admin', $data)
            . view($vista, $data)
            . view('incl/footer-application', $data)
            . view('incl/scripts-application', $data);
    }

    // 🔹 Rondas
    public function rondas($campana_id)
    {
        $data['registros'] = $this->db->table('tbl_rondas')
            ->where('campana_id', $campana_id)
            ->get()
            ->getResultArray();

        $data['titulo'] = "Rondas de la campaña {$campana_id}";
        $data['titulo_pagina'] = $data['titulo'];

        return $this->cargarVistaCompleta('relacionados/rondas', $data);
    }

    // 🔹 Brigadas
    public function brigadas($campana_id)
    {
        $data['registros'] = $this->db->table('tbl_brigadas')
            ->where('campana_id', $campana_id)
            ->get()
            ->getResultArray();

        $data['titulo'] = "Brigadas de la campaña {$campana_id}";
        $data['titulo_pagina'] = $data['titulo'];

        return $this->cargarVistaCompleta('relacionados/brigadas', $data);
    }

    // 🔹 Visitas
    public function visitas($campana_id)
    {
        $data['registros'] = $this->db->table('tbl_visitas')
            ->where('campana_id', $campana_id)
            ->get()
            ->getResultArray();

        $data['titulo'] = "Visitas de la campaña {$campana_id}";
        $data['titulo_pagina'] = $data['titulo'];

        return $this->cargarVistaCompleta('relacionados/visitas', $data);
    }

    // 🔹 Incidencias
    public function incidencias($campana_id)
    {
        $ticketsModel = new \App\Models\TicketsModel();
        $data['registros'] = $ticketsModel->obtenerTicketsPorCampana($campana_id);

        $data['titulo'] = "Incidencias de la campaña {$campana_id}";
        $data['titulo_pagina'] = $data['titulo'];

        return $this->cargarVistaCompleta('relacionados/incidencias', $data);
    }

    // 🔹 Encuestas
    public function encuestas($campana_id)
    {
        $data['registros'] = $this->db->table('survey_responses')
            ->where('id_campana', $campana_id)
            ->get()
            ->getResultArray();

        $data['titulo'] = "Encuestas de la campaña {$campana_id}";
        $data['titulo_pagina'] = $data['titulo'];

        return $this->cargarVistaCompleta('relacionados/encuestas', $data);
    }

    // 🔹 Entregas
    public function entregas($campana_id)
    {
        $data['registros'] = $this->db->table('tbl_entregas')
            ->where('campana_id', $campana_id)
            ->get()
            ->getResultArray();

        $data['titulo'] = "Entregas de la campaña {$campana_id}";
        $data['titulo_pagina'] = $data['titulo'];

        return $this->cargarVistaCompleta('relacionados/entregas', $data);
    }

    // 🔹 Peticiones
    public function peticiones($campana_id)
    {
        $data['registros'] = $this->db->table('tbl_peticiones')
            ->where('campana_id', $campana_id)
            ->get()
            ->getResultArray();

        $data['titulo'] = "Peticiones de la campaña {$campana_id}";
        $data['titulo_pagina'] = $data['titulo'];

        return $this->cargarVistaCompleta('relacionados/peticiones', $data);
    }
}