<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class ProjetosEscopoModel extends Model {
    
	protected $table = 'sis_projetosescopo';
	protected $primaryKey = 'codProjetoEscopo';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codProjeto', 'descricaoEscopo', 'codTipoEscopo'];
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
        $query = $this->db->query('select * from sis_projetosescopo');
        return $query->getResult();
    }

	public function pegaPorCodigo($codProjetoEscopo)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codProjetoEscopo = "'.$codProjetoEscopo.'"');
        return $query->getRow();
    }

	
	
	public function listaEscopo($codProjeto)
    {
		
	
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select * from sis_projetosescopo pe
		where pe.codProjeto = '.$codProjeto.' and pe.codTipoEscopo=1');
        return $query->getResult();
    }


		
	public function listaNaoEscopo($codProjeto)
    {
		
	
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select * from sis_projetosescopo pe
		where pe.codProjeto = '.$codProjeto.' and pe.codTipoEscopo=0');
        return $query->getResult();
    }



}