<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;
use CodeIgniter\Model;

class DocumentosModel extends Model {
    
	protected $table = 'ges_documentos';
	protected $primaryKey = 'codDocumento';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['assunto', 'conteudo', 'codDestinatario', 'codRemetente', 'dataCriacao', 'dataAtualizacao', 'codAutor', 'codTipoDocumento', 'codStatus'];
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
        $query = $this->db->query('select * from ges_documentos');
        return $query->getResult();

    }	public function porRequisicaoCompra($codRequisicao = NULL)
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select * 
		from ges_documentos d
		left join ges_documentosrequisicao dr on dr.codDocumento=d.codDocumento
		where dr.codRequisicao="'.$codRequisicao.'"');
        return $query->getResult();
    }

	public function pegaPorCodigo($codDocumento)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codDocumento = "'.$codDocumento.'"');
        return $query->getRow();
    }



}