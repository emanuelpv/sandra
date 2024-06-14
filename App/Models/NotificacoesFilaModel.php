<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class NotificacoesFilaModel extends Model {
    
	protected $table = 'sis_notificacoesfila';
	protected $primaryKey = 'codNotificacao';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['autor','dataAtualizacao','conteudo', 'remetente', 'destinatario', 'codOrganizacao', 'codProtocoloNotificacao'];
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
        $query = $this->db->query('select * from sis_notificacoesfila');
        return $query->getResult();
    }

	public function pegaPorCodigo($codNotificacao)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codNotificacao = "'.$codNotificacao.'"');
        return $query->getRow();
    }



}