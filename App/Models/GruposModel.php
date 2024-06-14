<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class GruposModel extends Model {
    
	protected $table = 'sis_grupos';
	protected $primaryKey = 'codGrupo';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codDepartamento','codOrganizacao','autorAlteracao','ultimaAlteracao','descricaoGrupo', 'abreviacaoGrupo', 'telefone', 'email', 'ativo'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;    
	

	public function listaDropDown()
    {
        $query = $this->db->query('select codGrupo as id, descricaoGrupo as text from ' . $this->table.' where descricaoGrupo is not null');
        return $query->getResult();
    }
	
	public function verificaPessoasNoGrupo($codPessoa,$codGrupo)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select 	p.*, g.descricaoGrupo, g.abreviacaoGrupo 
		from sis_pessoas p 
		left join sis_grupospessoas gp on gp.codPessoa = p.codPessoa 
		left join sis_grupos g on g.codGrupo = gp.codGrupo 
		where p.ativo=1 and  gp.codPessoa ='.$codPessoa. ' and g.codGrupo = '.$codGrupo.' and g.codOrganizacao = '.$codOrganizacao .' order by p.codCargo asc');
		return $query->getRow();
	}

	public function pegaPessoasNoGrupo($codGrupo)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select 	p.*, g.descricaoGrupo, g.abreviacaoGrupo 
		from sis_pessoas p 
		left join sis_grupospessoas gp on gp.codPessoa = p.codPessoa 
		left join sis_grupos g on g.codGrupo = gp.codGrupo 
		where p.ativo=1 and g.codGrupo = '.$codGrupo.' and g.codOrganizacao = '.$codOrganizacao .' order by p.codCargo asc');
		return $query->getResult();
	}

	public function pegaTudo()
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select g.*, concat(descricaoDepartamento," - ",abreviacaoDepartamento) as descricaoDepartamento from sis_grupos g left join sis_departamentos d on d.codDepartamento=g.codDepartamento');
        return $query->getResult();
    }

	
	public function AddMembro($codPessoa, $codGrupo)
	{
		
		$codOrganizacao = session()->codOrganizacao;
		$autorInclusao = session()->codPessoa;
		if ($codPessoa !== NULL) {
			$query = $this->db->query('insert into sis_grupospessoas(codOrganizacao,codGrupo,codPessoa,dataInclusao,autorInclusao) values (' . $codOrganizacao .','.$codGrupo.','.$codPessoa.',Now(),'.$autorInclusao.')');
		}
		return 1;
	}
	
	public function removerMembro($codPessoa, $codGrupo)
	{
		
		$codOrganizacao = session()->codOrganizacao;
		$autorInclusao = session()->codPessoa;
		if ($codPessoa !== NULL) {
			$query = $this->db->query('delete from sis_grupospessoas where codPessoa = '.$codPessoa.' and codGrupo = '.$codGrupo);
		}
		return 1;
	}

	public function pegaPorCodigo($codGrupo)
    {
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select g.*, concat(descricaoDepartamento," - ",abreviacaoDepartamento) as descricaoDepartamento from sis_grupos g 
		left join sis_departamentos d on d.codDepartamento=g.codDepartamento where g.codGrupo='.$codGrupo.' and g.codOrganizacao='.$codOrganizacao);
        return $query->getRow();
    }

	public function pegaGrupoPorNome($nomeGrupo)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from ' . $this->table . ' where abreviacaoGrupo = "'.$nomeGrupo.'" and codOrganizacao = '.$codOrganizacao);
		return $query->getRow();
	}
	

}