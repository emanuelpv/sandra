<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class ClassificacaoUrgenciaModel extends Model {
    
	protected $table = 'sis_classificacaourgencia';
	protected $primaryKey = 'codUrgencia';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['descricaoUrgencia'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;    
	

	public function pegaTudo()
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select * from sis_classificacaourgencia');
        return $query->getResult();
    }

	public function pegaPorCodigo($codUrgencia)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codUrgencia = "'.$codUrgencia.'"');
        return $query->getRow();
    }

	public function listaDropDown()
    {
        $query = $this->db->query('select codUrgencia as id, descricaoUrgencia as text from ' . $this->table);
        return $query->getResult();
    }


}