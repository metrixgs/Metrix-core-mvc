<?php

namespace App\Models;

use CodeIgniter\Model;

class SurveyModel extends Model
{
    protected $table = 'surveys';
    protected $primaryKey = 'id';
     protected $allowedFields = ['title', 'description', 'questions', 'image', 'campana_id'];

    protected $useTimestamps = true;

    public function contarEncuestasPorCampana($campana_id)
{
    return $this->where('campana_id', $campana_id)->countAllResults();
}

}
