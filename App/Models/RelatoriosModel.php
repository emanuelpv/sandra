<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class RelatoriosModel extends Model {
    
	protected $table = 'sis_relatorios';
	protected $primaryKey = 'id';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['nome', 'link', 'pai', 'ordem', 'destaque', 'icone'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;    
	

	public function pegaRelatoriosRaiz()
	{
		$query = $this->db->query('select * from ' . $this->table . ' where pai = 0 order by ordem desc');
		return $query->getResult();
	}

	public function pegaRelatoriosFilho()
	{
		$query = $this->db->query('select * from ' . $this->table . ' where pai != 0 order by  ordem desc, id asc');
		return $query->getResult();
	}

}


