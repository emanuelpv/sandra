<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;
use CodeIgniter\Model;

class DepositosModel extends Model {
    
	protected $table = 'sis_depositos';
	protected $primaryKey = 'codDeposito';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['dataCriacao','dataAtualizacao','codOrganizacao','codAutor','codStatus','descricaoDeposito', 'codDepartamento'];
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
        $query = $this->db->query('select * from sis_depositos d 
		left join sis_departamentos dd on dd.codDepartamento=d.codDepartamento');
        return $query->getResult();
    }

	public function pegaPorCodigo($codDeposito)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codDeposito = "'.$codDeposito.'"');
        return $query->getRow();
    }

	public function listaDropDown()
    {
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select codDeposito as id, descricaoDeposito as text from sis_depositos where codOrganizacao ='.$codOrganizacao );
        return $query->getResult();
    }


}