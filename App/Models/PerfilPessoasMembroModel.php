<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class PerfilPessoasMembroModel extends Model
{

	protected $table = 'sis_perfispessoasmembro';
	protected $primaryKey = 'codPessoaMembro';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codPessoa', 'codPerfil', 'dataInicio', 'dataEncerramento', 'dataCriacao', 'dataAtualizacao'];
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
		$query = $this->db->query('select ppm.*, p.nomeExibicao from sis_perfispessoasmembro as ppm 
		join sis_pessoas p on p.codPessoa = ppm.codPessoa
		where p.codOrganizacao = ' . $codOrganizacao);
		return $query->getResult();
	}

	public function pegaPorCodigo($codPessoaMembro)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codPessoaMembro = "' . $codPessoaMembro . '"');
		return $query->getRow();
	}
	public function pegaPorCodigoPerfil($codPerfil)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select ppm.*, p.nomeExibicao from sis_perfispessoasmembro as ppm 
		join sis_pessoas p on p.codPessoa = ppm.codPessoa
		where p.codOrganizacao = ' . $codOrganizacao . ' and p.ativo=1 and ppm.codPerfil=' . $codPerfil . ' order by p.codCargo asc, ppm.dataEncerramento desc,ppm.dataInicio asc');
		return $query->getResult();
	}

	public function pegaMeusPerfisValidos($codPessoa = null)
	{
		if ($codPessoa !== null) {
			$codOrganizacao = session()->codOrganizacao;
			$query = $this->db->query('select ppm.codPerfil, p.descricao,ppm.dataEncerramento 
			from sis_perfispessoasmembro as ppm 
		join sis_perfis p on p.codPerfil = ppm.codPerfil
		where p.codOrganizacao = ' . $codOrganizacao . ' and ppm.codPessoa=' . $codPessoa . ' and (ppm.dataEncerramento is null  or ppm.dataEncerramento >= CURDATE()) order by ppm.dataEncerramento desc,ppm.dataInicio asc');
			return $query->getResult();
		} else {
			return array();
		}
	}

	public function pegaMeusPerfisValidosPacientes()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select p.codPerfil, p.descricao, "2050-01-01" as dataEncerramento 
			from sis_perfis p where p.codPerfil in ( 9)');
		return $query->getResult();
	}

	public function pegaMeusPerfis($codPessoa)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select ppm.codPerfil, p.descricao,ppm.dataEncerramento 
		from sis_perfispessoasmembro as ppm 
		join sis_perfis p on p.codPerfil = ppm.codPerfil
		where p.codOrganizacao = ' . $codOrganizacao . ' and ppm.codPessoa=' . $codPessoa . ' order by ppm.dataEncerramento desc,ppm.dataInicio asc');
		return $query->getResult();
	}

	public function pegaMeusPerfisPacientes()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select p.codPerfil, p.descricao, "2050-01-01" as dataEncerramento 
		from sis_perfis p where p.codPerfil in( 9)');
		return $query->getResult();
	}

	public function pegaMinhasPermissoesModulos($codPessoa, $codPerfil)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select ppm.codPerfil,pm.listar,pm.adicionar,pm.editar,pm.deletar,m.nome,m.link 
		FROM sis_perfismodulos pm 
		join sis_perfispessoasmembro ppm on ppm.codPerfil=pm.codPerfil 
		join sis_modulos m on m.codModulo=pm.codModulo 
		where ppm.codPerfil=' . $codPerfil . ' and ppm.codPessoa=' . $codPessoa);
		return $query->getResult();
	}

	public function pegaMinhasPermissoesModulosPacientes()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select pm.codPerfil,pm.listar,pm.adicionar,pm.editar,pm.deletar,m.nome,m.link 
		FROM sis_perfismodulos pm 
		join sis_modulos m on m.codModulo=pm.codModulo 
		where pm.codPerfil in(9)');
		return $query->getResult();
	}
}
