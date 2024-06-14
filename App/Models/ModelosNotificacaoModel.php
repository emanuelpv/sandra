<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class ModelosNotificacaoModel extends Model {
    
	protected $table = 'sis_modelosnotificacao';
	protected $primaryKey = 'codModeloNotificacao';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codOrganizacao','nomeModeloNotificacao', 'assunto', 'responderPara', 'conteudoModeloNotificacao', 'codProtocoloNotificacao'];
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
        $query = $this->db->query('select mn.*,pn.nomeProtocoloNotificacao from sis_modelosnotificacao mn left join sis_protocolosnotificacao pn on pn.codProtocolonotificacao=mn.codProtocolonotificacao');
        return $query->getResult();
    }

	
	public function pegaProtocolos()
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select * from sis_protocolosnotificacao');
        return $query->getResult();
    }


	public function pegaPorCodigo($codModeloNotificacao)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codModeloNotificacao = "'.$codModeloNotificacao.'"');
        return $query->getRow();
    }



}