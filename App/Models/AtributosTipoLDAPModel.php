<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class AtributosTipoLDAPModel extends Model {
    
	protected $table = 'sis_atributostipoldap';
	protected $primaryKey = 'codAtributoTipoLDAP';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codTipoLDAP', 'nomeAtributoLDAP', 'dataCriacao'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;    
	

	public function pegaTudo()
    {
		
        $query = $this->db->query('select a.codAtributoTipoLDAP, a.codTipoLDAP, a.nomeAtributoLDAP, tl.* 
		from sis_atributostipoldap a left join sis_tipoldap tl on tl.codTipoLDAP=a.codTipoLDAP 
		order by a.codTipoLDAP asc,a.nomeAtributoLDAP asc');
        return $query->getResult();
    }



	public function pegaPorCodigo($codAtributoTipoLDAP)
    {
        $query = $this->db->query('select * from ' . $this->table. '  a left join sis_tipoldap tl on tl.codTipoLDAP=a.codTipoLDAP where codAtributoTipoLDAP = "'.$codAtributoTipoLDAP.'" order by a.codTipoLDAP asc');
        return $query->getRow();
    }

	public function pegaPorTipoLDAP($codTipoLDAP)
    {
        $query = $this->db->query('select * from ' . $this->table. '  a left join sis_tipoldap tl on tl.codTipoLDAP=a.codTipoLDAP where a.codTipoLDAP = "'.$codTipoLDAP.'" order by a.codTipoLDAP asc');
        return $query->getResult();
    }

	public function pegaPorTipoLDAPSelect($codTipoLDAP)
    {
        $query = $this->db->query('select nomeAtributoLDAP as id, concat(a.nomeAtributoLDAP," - ",tl.nomeTipoLDAP) as text from ' . $this->table. '  a 
		left join sis_tipoldap tl on tl.codTipoLDAP=a.codTipoLDAP where concat(a.nomeAtributoLDAP," - ",tl.nomeTipoLDAP) is not null and a.codTipoLDAP = "'.$codTipoLDAP.'" order by a.codTipoLDAP asc');
        return $query->getResult();
    }




}