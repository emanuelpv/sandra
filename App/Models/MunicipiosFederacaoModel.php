<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class MunicipiosFederacaoModel extends Model {
    
	protected $table = 'sis_municipiosfederacao';
	protected $primaryKey = 'codMunicipioFederacao';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = [ 'codMunicipioFederacao', 'codUnidadeFederacao', 'uf', 'municipio', 'regiao', 'populacao', 'porte', 'capital'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;    


	public function pegaMunicipiosFederacao()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from ' . $this->table. ' order by  municipio asc');
		return $query->getResult();
	}

	public function pegaDepartamento($codDepartamento)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from ' . $this->table . ' where codDepartamento = '.$codDepartamento.' and codOrganizacao = '.$codOrganizacao.' order by  descricaoDepartamento asc');
		return $query->getResult();
	}
	
	
	
}