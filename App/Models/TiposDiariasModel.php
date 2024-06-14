<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;
use CodeIgniter\Model;

class TiposDiariasModel extends Model {
    
	protected $table = 'sis_tiposdiarias';
	protected $primaryKey = 'codTipoDiaria';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codDgp','descricao', 'valor'];
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
        $query = $this->db->query('select * from sis_tiposdiarias');
        return $query->getResult();
    }

	public function pegaPorCodigo($codTipoDiaria)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codTipoDiaria = "'.$codTipoDiaria.'"');
        return $query->getRow();
    }



}