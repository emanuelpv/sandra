<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class TiposProjetosModel extends Model {
    
	protected $table = 'sis_tiposprojetos';
	protected $primaryKey = 'codTipoProjeto';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['descricaoTipoProjeto', 'ordem', 'codLocalAtendimento', 'prazo', 'ativarNotificacao', 'nrdiasNotificacao', 'link', 'icone'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;    
	

	public function pega_tiposprojetos_por_codTipoProjeto($codTipoProjeto)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codTipoProjeto = "'.$codTipoProjeto.'"');
        return $query->getRow();
    }

	public function pega_tiposprojetos()
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select * from sis_tiposprojetos');
        return $query->getResult();
    }


	public function listaDropDown()
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select codTipoProjeto as id, descricaoTipoProjeto as text from sis_tiposprojetos');
        return $query->getResult();
    }

}