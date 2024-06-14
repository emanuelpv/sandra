<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class DepartamentodependenciasModel extends Model {
    
	protected $table = 'sis_departamentodependencias';
	protected $primaryKey = 'codDependencia';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codDepartamento','codOrganizacao','descricaoDependencia', 'codTipoDependecia', 'codStatusDependecia', 'codPessoa', 'dataAtualizacao'];
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
        $query = $this->db->query('select * from sis_departamentodependencias');
        return $query->getResult();
    }

	public function pegaPorCodigo($codDependencia)
    {
        $query = $this->db->query('select * from sis_departamentodependencias where codDependencia = "'.$codDependencia.'"');
        return $query->getRow();
    }

	public function listaDropDown($codDepartamento = null)
    {
        $query = $this->db->query('select codDependencia as id, descricaoDependencia as text from sis_departamentodependencias where codDepartamento = "'.$codDepartamento.'" order by descricaoDependencia');
        return $query->getResult();
    }

	public function pegaPorDepartamento($codDepartamento)
    {
        $query = $this->db->query('select * 
		from sis_departamentodependencias dd
		left join sis_tiposdependencia td on td.codTipoDependecia = dd.codTipoDependecia
		left join sis_statusdependencia sd on sd.codStatusDependecia = dd.codStatusDependecia
		 where dd.codDepartamento = '.$codDepartamento);
        return $query->getResult();
    }


}