<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class AcaoSuporteModel extends Model {
    
	protected $table = 'sis_acaosuporte';
	protected $primaryKey = 'codAcaoSuporte';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codTipoAcao','codSolicitacao', 'codPessoa', 'descricaoAcao', 'dataInício', 'codStatusSolicitacao', 'percentualConclusao'];
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
        $query = $this->db->query('select * from sis_acaosuporte');
        return $query->getResult();
    }

	public function pegaPorCodigo($codAcaoSuporte)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codAcaoSuporte = "'.$codAcaoSuporte.'"');
        return $query->getRow();
    }



}