<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class ProjetosMembrosModel extends Model {
    
	protected $table = 'sis_projetosmembros';
	protected $primaryKey = 'codProjetoMembro';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codMembro', 'codTipoMembro','codProjeto'];
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
        $query = $this->db->query('select * from sis_projetosmembros');
        return $query->getResult();
    }


	public function pegaTudoPorCodProjeto($codProjeto)
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select * 
		from sis_projetosmembros pm
		left join sis_pessoas p on p.codPessoa = pm.codMembro
		left join sis_projetostipomembros ptm on ptm.codTipoMembro = pm.codTipoMembro
		where pm.codProjeto ='.$codProjeto. ' order by ptm.codTipoMembro asc, p.codCargo asc');
        return $query->getResult();
    }


	public function pegaTipoMembros()
    {
		
        $query = $this->db->query('select codTipoMembro as id,descricaoTipoMembro as text 
		from sis_projetostipomembros ptm');
        return $query->getResult();
    }



	public function pegaPorCodigo($codProjetoMembro)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codProjetoMembro = "'.$codProjetoMembro.'"');
        return $query->getRow();
    }



}