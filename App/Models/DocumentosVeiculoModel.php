<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;
use CodeIgniter\Model;

class DocumentosVeiculoModel extends Model {
    
	protected $table = 'seg_documentosveiculo';
	protected $primaryKey = 'codDocumento';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codVeiculo', 'dataCriacao', 'dataAtualizacao', 'documento', 'codAutor'];
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
        $query = $this->db->query('select * from seg_documentosveiculo');
        return $query->getResult();
    }

	public function pegaPorCodigo($codDocumento)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codDocumento = "'.$codDocumento.'"');
        return $query->getRow();
    }


	public function pegaPorCodVeiculo($codVeiculo)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codVeiculo = "'.$codVeiculo.'"');
        return $query->getResult();
    }


}