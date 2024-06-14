<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class ClassificacaoPrioridadeModel extends Model {
    
	protected $table = 'sis_classificacaoprioridade';
	protected $primaryKey = 'codPrioridade';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['descricaoPrioridade'];
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
        $query = $this->db->query('select * from sis_classificacaoprioridade');
        return $query->getResult();
    }

	public function pegaPorCodigo($codPrioridade)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codPrioridade = "'.$codPrioridade.'"');
        return $query->getRow();
    }

	public function listaDropDown()
    {
        $query = $this->db->query('select codPrioridade as id, descricaoPrioridade as text from ' . $this->table);
        return $query->getResult();
    }


}