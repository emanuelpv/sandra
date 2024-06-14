<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class ProtocolosRedeModel extends Model {
    
	protected $table = 'sis_protocolosrede';
	protected $primaryKey = 'codProtocoloRede';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['nomeProtocoloRede', 'conector', 'portaPadrao'];
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
        $query = $this->db->query('select * from sis_protocolosrede');
        return $query->getResult();
    }

	public function pegaPorCodigo($codProtocoloRede)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codProtocoloRede = "'.$codProtocoloRede.'"');
        return $query->getRow();
    }



}