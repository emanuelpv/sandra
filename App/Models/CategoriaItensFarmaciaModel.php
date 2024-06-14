<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;
use CodeIgniter\Model;

class CategoriaItensFarmaciaModel extends Model {
    
	protected $table = 'sis_itensfarmaciacategoria';
	protected $primaryKey = 'codCategoria';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['descricaoCategoria'];
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
        $query = $this->db->query('select * from sis_itensfarmaciacategoria');
        return $query->getResult();
    }

	public function pegaPorCodigo($codCategoria)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codCategoria = "'.$codCategoria.'"');
        return $query->getRow();
    }



}