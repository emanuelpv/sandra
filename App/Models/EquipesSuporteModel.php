<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class EquipesSuporteModel extends Model {
    
	protected $table = 'sis_equipesuporte';
	protected $primaryKey = 'codEquipeSuporte';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codOrganizacao','siglaEquipeSuporte', 'descricaoEquipeSuporte', 'codDepartamentoResponsavel'];
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
        $query = $this->db->query('select * from sis_equipesuporte es 
		left join sis_departamentos d on d.codDepartamento=es.codDepartamentoResponsavel
		where es.codOrganizacao='.$codOrganizacao);
        return $query->getResult();
    }

	public function pegaPorCodigo($codEquipeSuporte)
    {
		
        $query = $this->db->query('select * from ' . $this->table. ' where codEquipeSuporte = "'.$codEquipeSuporte.'"');
        return $query->getRow();
    }


	public function listaDropDown()
    {
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select codEquipeSuporte as id, concat(descricaoEquipeSuporte," - ",siglaEquipeSuporte) as text from sis_equipesuporte where codOrganizacao='.$codOrganizacao .' and concat(descricaoEquipeSuporte," - ",siglaEquipeSuporte) is not null');
        return $query->getResult();
    }


}