<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class AtalhosModel extends Model {
    
	protected $table = 'sis_atalhos';
	protected $primaryKey = 'codAtalho';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codModulo', 'codPerfil','codOrganizacao'];
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
        $query = $this->db->query('select * 
		from sis_atalhos a 
		join sis_modulos m on m.codModulo=a.codModulo');
        return $query->getResult();
    }

	public function pegaTudoPorPerfil($codPerfil = 0)
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select * 
		from sis_atalhos a 
		join sis_modulos m on m.codModulo=a.codModulo
		where a.codPerfil = '.$codPerfil);

		if($query == NULL){
			return false;

		}else{
			return $query->getResult();

		}
			
    }

	public function pegaPorCodigo($codAtalho)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codAtalho = "'.$codAtalho.'"');
        return $query->getRow();
    }



}