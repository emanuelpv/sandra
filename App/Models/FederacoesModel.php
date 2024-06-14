<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;
use CodeIgniter\Model;

class FederacoesModel extends Model {
    
	protected $table = 'sis_federacoes';
	protected $primaryKey = 'codFederacao';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['descricaoFederacao', 'servidor', 'banco', 'login', 'senha'];
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
        $query = $this->db->query('select * from sis_federacoes');
        return $query->getResult();
    }

	public function pegaPorCodigo($codFederacao)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codFederacao = "'.$codFederacao.'"');
        return $query->getRow();
    }

	public function pegaChave($codOrganizacao)
    {
        $query = $this->db->query('select chaveSalgada from sis_organizacoes where codOrganizacao = "'.$codOrganizacao.'"');
        return $query->getRow();
    }


}