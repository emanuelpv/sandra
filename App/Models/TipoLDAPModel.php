<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class TipoLDAPModel extends Model {
    
	protected $table = 'sis_tipoldap';
	protected $primaryKey = 'codTipoLDAP';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['nomeTipoLDAP'];
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
        $query = $this->db->query('select * from sis_tipoldap');
        return $query->getResult();
		
    }

	public function pegaPorCodigo($codTipoLDAP)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codTipoLDAP = "'.$codTipoLDAP.'"');
        return $query->getRow();
    }



}