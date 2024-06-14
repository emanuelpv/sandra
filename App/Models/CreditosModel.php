<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class CreditosModel extends Model {
    
	protected $table = 'sis_creditos';
	protected $primaryKey = 'codCredito';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['whatsapp', 'sms', 'smtp'];
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
        $query = $this->db->query('select * from sis_creditos');
        return $query->getResult();
    }

	public function pegaPorCodigo($codCredito)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codCredito = "'.$codCredito.'"');
        return $query->getRow();
    }



}