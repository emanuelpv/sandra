<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class ServicoSMTPModel extends Model {
    
	protected $table = 'sis_servicosmtp';
	protected $primaryKey = 'codServidorSMTP';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codOrganizacao','descricaoServidorSMTP', 'ipServidorSMTP', 'portaSMTP', 'loginSMTP', 'senhaSMTP', 'emailRetorno', 'protocoloSMTP', 'statusSMTP','dataCriacao','daraAtualizacao'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;    
	

	public function pegaServicoSMTPModelPorcodServidorSMTP($codServidorSMTP)
    {
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select * from ' . $this->table. ' where codOrganizacao = '.$codOrganizacao .' and codServidorSMTP = "'.$codServidorSMTP.'"');
        return $query->getRow();
    }

	public function pega_servicosmtp()
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select * from sis_servicosmtp where codOrganizacao = '.$codOrganizacao);
        return $query->getResult();
    }

	public function pegaAtivo()
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select * from sis_servicosmtp 
		where codOrganizacao = '.$codOrganizacao.'
		and statusSMTP = 1 limit 1');
        return $query->getRow();
    }

}