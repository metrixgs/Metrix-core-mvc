<?php

namespace App\Validation;

use App\Models\AreasModel;

class CustomRules
{
    /**
     * Valida si un ID de área existe en la tabla tbl_areas.
     *
     * @param int $area_id El ID del área a validar.
     * @return bool True si el área existe, false en caso contrario.
     */
    public function area_exists(?int $area_id): bool
    {
        if ($area_id === null || $area_id === 0) { // Considerar 0 como vacío si es el caso
            return true;
        }
        $model = new AreasModel();
        return $model->find($area_id) !== null;
    }
}