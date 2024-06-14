<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class StatusProjetosModel extends Model {
    
	protected $table = 'sis_statusprojetos';
	protected $primaryKey = 'codStatusProjeto';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codStatusProjeto','descricaoStatusProjeto', 'codCorStatusProjeto'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;    
	

	public function pega_statusprojetos_por_codStatusProjeto($codStatusProjeto)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codStatusProjeto = "'.$codStatusProjeto.'"');
        return $query->getRow();
    }

	public function pega_statusprojetos()
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select * from sis_statusprojetos');
        return $query->getResult();
    }

	public function listaDropDown()
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select codStatusProjeto as id, descricaoStatusProjeto as text from sis_statusprojetos');
        return $query->getResult();
    }

}