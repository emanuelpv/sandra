<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;
use CodeIgniter\Model;

class HistoricoAcoesModel extends Model {
    
	protected $table = 'ges_historicoacoes';
	protected $primaryKey = 'codHistoricoAcao';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codTipoAcao', 'descricaoAcao', 'codAutor', 'dataCriacao', 'recurso', 'codRequisicao'];
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
        $query = $this->db->query('select * from ges_historicoacoes');
        return $query->getResult();
    }

	public function pegaPorCodigo($codHistoricoAcao)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codHistoricoAcao = "'.$codHistoricoAcao.'"');
        return $query->getRow();
    }



}