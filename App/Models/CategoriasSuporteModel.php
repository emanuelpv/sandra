<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class CategoriasSuporteModel extends Model {
    
	protected $table = 'sis_categoriassuporte';
	protected $primaryKey = 'codCategoriaSuporte';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['sla','sli','medidaSLA', 'slo','medidaSLO','codOrganizacao','descricaoCategoriaSuporte','codEquipeResponsavel'];
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
        $query = $this->db->query('select * from sis_categoriassuporte cs 
		left join sis_equipesuporte es on es.codEquipeSuporte=cs.codEquipeResponsavel
		where cs.codOrganizacao = '.$codOrganizacao);
        return $query->getResult();
    }

	public function pegaPorCodigo($codCategoriaSuporte)
    {
        $query = $this->db->query('select * from sis_categoriassuporte where codCategoriaSuporte = "'.$codCategoriaSuporte.'"');
        return $query->getRow();
    }


	public function listaDropDown()
    {
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select codCategoriaSuporte as id, descricaoCategoriaSuporte as text from sis_categoriassuporte where descricaoCategoriaSuporte is not null
		and codOrganizacao = '.$codOrganizacao);
        return $query->getResult();
    }

}