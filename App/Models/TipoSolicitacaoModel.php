<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class TipoSolicitacaoModel extends Model {
    
	protected $table = 'sis_tiposolicitacao';
	protected $primaryKey = 'codTipoSolicitacao';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['descricaoTipoSolicitacao'];
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
        $query = $this->db->query('select * from sis_tiposolicitacao');
        return $query->getResult();
    }

	public function pegaPorCodigo($codTipoSolicitacao)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codTipoSolicitacao = "'.$codTipoSolicitacao.'"');
        return $query->getRow();
    }

	public function listaDropDown()
    {
        $query = $this->db->query('select codTipoSolicitacao as id, descricaoTipoSolicitacao as text from ' . $this->table.' where descricaoTipoSolicitacao is not null');
        return $query->getResult();
    }




}