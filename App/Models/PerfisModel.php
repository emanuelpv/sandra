<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class PerfisModel extends Model {
    
	protected $table = 'sis_perfis';
	protected $primaryKey = 'codPerfil';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['descricao', 'codOrganizacao','dataCriacao','dataAtualizacao'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;    
	

	public function pega_todasPerfis()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select f.codPerfil, f.descricao as descricao_perfil, o.descricao as descricaoOrganizacao 
		from sis_perfis f join sis_organizacoes o on o.codOrganizacao = f.codOrganizacao where o.codOrganizacao ='.$codOrganizacao);
		return $query->getResult();
	}

	public function getAllPerfisSame()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select f.codPerfil, f.descricao as descricao_perfil, o.descricao as descricaoOrganizacao 
		from sis_perfis f join sis_organizacoes o on o.codOrganizacao = f.codOrganizacao where o.codOrganizacao ='.$codOrganizacao.' and f.descricao like "%same%"');
		return $query->getResult();
	}
}

