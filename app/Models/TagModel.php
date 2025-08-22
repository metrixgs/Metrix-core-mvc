<?php
namespace App\Models;

use CodeIgniter\Model;

class TagModel extends Model
{
    protected $DBGroup       = 'default';    // usa tu grupo real si no es 'default'
    protected $table         = 'tbl_tags';   // este es el nombre exacto en tu dump
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['tag','slug','created_at'];
    protected $useTimestamps = false;

    public function allOrdered(): array
    {
        return $this->select('id, tag, slug')
                    ->orderBy('tag','ASC')
                    ->findAll();
   }

   /**
    * Obtiene el conteo de usuarios asociados a cada tag.
    *
    * @return array Un array asociativo donde la clave es el slug del tag y el valor es el conteo de usuarios.
    */
   public function getUsersCountByTag(): array
   {
       $result = $this->db->table('tbl_tags t')
                          ->select('t.slug, COUNT(ut.usuario_id) as user_count')
                          ->join('tbl_usuario_tags ut', 'ut.tag_id = t.id', 'left')
                          ->groupBy('t.slug')
                          ->get()
                          ->getResultArray();

       $counts = [];
       foreach ($result as $row) {
           $counts[$row['slug']] = (int)$row['user_count'];
       }
       return $counts;
   }

   /**
    * Obtiene solo los tags que tienen al menos un usuario asociado, junto con el conteo de usuarios.
    *
    * @return array
    */
   public function getTagsWithUserCounts(): array
   {
       $result = $this->db->table('tbl_tags t')
                          ->select('t.id, t.tag, t.slug, COUNT(ut.usuario_id) as user_count')
                          ->join('tbl_usuario_tags ut', 'ut.tag_id = t.id', 'inner') // INNER JOIN para solo tags con usuarios
                          ->groupBy('t.id, t.tag, t.slug')
                          ->orderBy('t.tag', 'ASC')
                          ->get()
                          ->getResultArray();

       // Formatear el resultado para incluir el conteo directamente en cada tag
       foreach ($result as &$row) {
           $row['user_count'] = (int)$row['user_count'];
       }
       unset($row); // Romper la referencia del último elemento

       return $result;
   }
}
