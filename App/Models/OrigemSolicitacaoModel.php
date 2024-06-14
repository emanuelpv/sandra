<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class OrigemSolicitacaoModel extends Model {
    
	protected $table = 'sis_origemsolicitacao';
	protected $primaryKey = 'codOrigemSolicitacao';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['descricaoOrigemSolicitacao'];
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
        $query = $this->db->query('select * from sis_origemsolicitacao');
        return $query->getResult();
    }

	public function pegaPorCodigo($codOrigemSolicitacao)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codOrigemSolicitacao = "'.$codOrigemSolicitacao.'"');
        return $query->getRow();
    }


	public function listaDropDown()
    {
        $query = $this->db->query('
		select codOrigemSolicitacao as id, descricaoOrigemSolicitacao as text from ' . $this->table);
        return $query->getResult();
    }

}