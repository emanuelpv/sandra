<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class ProjetosFaseModel extends Model {
    
	protected $table = 'sis_projetosfases';
	protected $primaryKey = 'codProjetoFase';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codProjeto', 'descricaoFase', 'dataInicial', 'dataEncerramento'];
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
        $query = $this->db->query('select * from sis_projetosfases');
        return $query->getResult();
    }

	public function pegaPorCodigo($codProjetoFase)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codProjetoFase = "'.$codProjetoFase.'"');
        return $query->getRow();
    }

	public function fasesProjeto($codProjeto)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codProjeto = "'.$codProjeto.'"');
        return $query->getResult();
    }


}