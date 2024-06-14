<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;
use CodeIgniter\Model;

class DocumentoRequisicaoModel extends Model {
    
	protected $table = 'ges_documentosrequisicao';
	protected $primaryKey = 'codDocumentoRequisicao';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codDocumento', 'codRequisicao'];
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
        $query = $this->db->query('select * from ges_documentosrequisicao');
        return $query->getResult();
    }

	public function pegaPorCodigo($codDocumentoRequisicao)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codDocumentoRequisicao = "'.$codDocumentoRequisicao.'"');
        return $query->getRow();
    }



}