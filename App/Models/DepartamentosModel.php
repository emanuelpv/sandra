<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class DepartamentosModel extends Model
{

	protected $table = 'sis_departamentos';
	protected $primaryKey = 'codDepartamento';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codChefe','SeqAno','seqRequisicao','codTaxaServicoAcompanhante', 'codTaxaServico', 'autorAlteracao', 'codTipoDepartamento', 'ultimaAlteracao', 'codDepartamento', 'codOrganizacao', 'descricaoDepartamento', 'abreviacaoDepartamento', 'paiDepartamento', 'telefone', 'email', 'ativo'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;

	public function listaDropDown()
	{
		$csrf_hash = csrf_hash();
		$query = $this->db->query('select "' . $csrf_hash . '" as csrf_hash, codDepartamento as id, concat(descricaoDepartamento," - ",abreviacaoDepartamento) as text 
		from ' . $this->table . ' where concat(descricaoDepartamento," - ",abreviacaoDepartamento) is not null order by descricaoDepartamento asc');
		return $query->getResult();
	}
	public function listaDropDownDepartamentosAtendimento()
	{
		$csrf_hash = csrf_hash();
		$query = $this->db->query('select "' . $csrf_hash . '" as csrf_hash, codDepartamento as id, concat(descricaoDepartamento," - ",abreviacaoDepartamento) as text 
		from ' . $this->table . ' where codTipoDepartamento = 3 and concat(descricaoDepartamento," - ",abreviacaoDepartamento) is not null order by descricaoDepartamento asc');
		return $query->getResult();
	}


	public function listaDropDownUnidadesInternacao()
	{
		$csrf_hash = csrf_hash();
		$query = $this->db->query('
		select codDepartamento as id, concat(descricaoDepartamento," - ",abreviacaoDepartamento) as text 
		from ' . $this->table . ' where ativo=1 and  (codTipoDepartamento in(2,6) or descricaoDepartamento like "%EMERGÊNCIA%" or abreviacaoDepartamento like "%PAMO%"  ) and concat(descricaoDepartamento," - ",abreviacaoDepartamento) is not null order by descricaoDepartamento asc');
		return $query->getResult();
	}


	public function listaDropDownUnidadesInternacaoCirurgia()
	{
		$csrf_hash = csrf_hash();
		$query = $this->db->query('
		select codDepartamento as id, concat(descricaoDepartamento," - ",abreviacaoDepartamento) as text 
		from ' . $this->table . ' where ativo=1 and (codTipoDepartamento in(2,4,5,6) or descricaoDepartamento like "%EMERGÊNCIA%" or abreviacaoDepartamento like "%PAMO%"  ) and concat(descricaoDepartamento," - ",abreviacaoDepartamento) is not null order by descricaoDepartamento asc');
		return $query->getResult();
	}
	public function listaDropDownUnidadesFaturamento()
	{
		$csrf_hash = csrf_hash();
		$query = $this->db->query('
		select codDepartamento as id, concat(descricaoDepartamento," - ",abreviacaoDepartamento) as text 
		from ' . $this->table . ' where ativo=1 and (codTipoDepartamento in(2,4,5,6) or codDepartamento in (13,72,36)) and concat(descricaoDepartamento," - ",abreviacaoDepartamento) is not null order by descricaoDepartamento asc');
		return $query->getResult();
	}

	public function listaDropDownUnidadesInternacaoHelper()
	{
		$csrf_hash = csrf_hash();
		$query = $this->db->query('
		select codDepartamento as id, concat(descricaoDepartamento," - ",abreviacaoDepartamento) as text 
		from ' . $this->table . ' where ativo=1 and (codTipoDepartamento in(2,4,6) or descricaoDepartamento like "%EMERGÊNCIA%" or abreviacaoDepartamento like "%PAMO%"  ) and concat(descricaoDepartamento," - ",abreviacaoDepartamento) is not null order by descricaoDepartamento asc');
		return $query->getResult();
	}


	
	public function listaDropDownUnidadesFaturamentoHelper()
	{
		$csrf_hash = csrf_hash();
		$query = $this->db->query('
		select codDepartamento as id, concat(descricaoDepartamento," - ",abreviacaoDepartamento) as text 
		from ' . $this->table . ' where ativo=1 and (codTipoDepartamento in(2,4,5,6) or codDepartamento in (13,72)) and concat(descricaoDepartamento," - ",abreviacaoDepartamento) is not null order by descricaoDepartamento asc');
		return $query->getResult();
	}


	public function listaDropDownTiposLocalAtendimentos()
	{
		$query = $this->db->query('select codTipoLocalAtendimento as id, descricaoTipoLocalAtendimento as text from amb_atendimentoslocaistipos');
		return $query->getResult();
	}
	public function listaDropDownStatusLocalAtendimentos()
	{
		$query = $this->db->query('select codStatusLocalAtendimento as id, descricaoStatusLocalAtendimento as text from amb_atendimentoslocaisstatus');
		return $query->getResult();
	}


	public function listaDropDownSituacaoLocalAtendimentos()
	{
		$query = $this->db->query('select codSituacaoLocalAtendimento as id, descricaoSituacaoLocalAtendimento as text from amb_atendimentoslocaissituacao');
		return $query->getResult();
	}

	public function listaTiposDiarias()
	{
		$query = $this->db->query('select codTaxaServico as id, descricao as text from amb_taxasservicos');
		return $query->getResult();
	}

	public function salaDepartamento($codDepartamento)
	{
		$query = $this->db->query('select la.codLocalAtendimento FROM sis_departamentos d
		left join amb_atendimentoslocais la on d.codDepartamento=la.codDepartamento
        where la.codStatusLocalAtendimento =1 and la.codSituacaoLocalAtendimento not in(3) and d.codDepartamento = "' . $codDepartamento . '" order by d.codDepartamento asc limit 1');
		return $query->getRow();
	}

	public function lookupCodNomeDepartamentosJson($departamentos)
	{
		$query = $this->db->query('select * from sis_departamentos where codDepartamento in (' . $departamentos . ')');

		return $query->getResult();
	}
	public function lookupMigracaoDepartamentos($nomeDepartamento)
	{
		$query = $this->db->query('select * from tabelalookupdepartamentos where descricaoDepartamento like "%' . $nomeDepartamento . '%"');

		return $query->getRow()->codDepartamento;
	}


	public function pegaDepartamentos()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from sis_departamentos d
		left join sis_tipodepartamento  td on td.codTipoDepartamento=d.codTipoDepartamento
		where d.codOrganizacao = ' . $codOrganizacao . ' order by  d.descricaoDepartamento asc');
		return $query->getResult();
	}


	public function getAllClassificacaoDiarias()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select d.*,td.*,tdd.descricao as acomodacaoPaciente,tdd.valor as valorPaciente,tddd.descricao as acomodacaoAcompanhante,tddd.valor as valorAcompanhante from sis_departamentos d
		left join sis_tipodepartamento  td on td.codTipoDepartamento=d.codTipoDepartamento
		left join amb_taxasservicos  tdd on tdd.codTaxaServico=d.codTaxaServico
		left join amb_taxasservicos  tddd on tddd.codTaxaServico=d.codTaxaServicoAcompanhante
		where td.codTipoDepartamento in(2,6) and d.codOrganizacao = ' . $codOrganizacao . ' order by  d.descricaoDepartamento asc');
		return $query->getResult();
	}

	public function pegaDepartamento($codDepartamento)
	{
		$csrf_hash = csrf_hash();
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select *,"' . $csrf_hash . '" as csrf_hash  from ' . $this->table . ' where codDepartamento = "' . $codDepartamento . '" and codOrganizacao = ' . $codOrganizacao . ' order by  descricaoDepartamento asc');
		return $query->getRow();
	}

	public function pegaPessoasNoDepartamento($codDepartamento)
	{
		$csrf_hash = csrf_hash();
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select p.*, d.descricaoDepartamento, d.abreviacaoDepartamento,"' . $csrf_hash . '" as csrf_hash
		from sis_pessoas p left join sis_departamentos d on p.codDepartamento = d.codDepartamento where p.codDepartamento = ' . $codDepartamento . ' and p.codOrganizacao = ' . $codOrganizacao . ' order by p.codCargo asc');
		return $query->getResult();
	}

	public function pegaDepartamentoPorNome($nomeDepartamento)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from ' . $this->table . ' where descricaoDepartamento = "' . $nomeDepartamento . '" and codOrganizacao = ' . $codOrganizacao);
		return $query->getRow();
	}
}
