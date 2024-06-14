<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class AtoCirurgicoModel extends Model
{

	protected $table = 'cir_atocirurgico';
	protected $primaryKey = 'codAtoCirurgico';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['intercorrencias','posOperatorio','resumoProcedimento','preOperatorio','codTipoAnestesia','codLocalAtendimento', 'descricao', 'impresso', 'assinadoPor', 'codAtendimento', 'dataCriacao', 'dataAtualizacao', 'codAutor', 'codStatus', 'codTipoAtoCirurgico'];
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
		$query = $this->db->query('select * from cir_atocirurgico');
		return $query->getResult();
	}

	public function pegaPorCodAtendmento($codAtendimento)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select dc.*, p.nomeExibicao,ae.*,pp.nomeExibicao as nomeExibicaoAssinador 
		from cir_atocirurgico dc
		left join sis_pessoas p on p.codPessoa=dc.codAutor
		left join sis_pessoas pp on pp.codPessoa=dc.assinadoPor 
		left join cir_atocirurgicostatus as ae on ae.codStatus=dc.codStatus 
		where dc.codAtendimento="' . $codAtendimento . '"');
		return $query->getResult();
	}
	public function listaDropDownTiposAnestesia()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select codTipoAnestesia as id, descricao as text 
		from cir_atotiposanestesia where descricao is not null');
		return $query->getResult();
	}

	public function pegaPorCodigo($codAtoCirurgico)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codAtoCirurgico = "' . $codAtoCirurgico . '"');
		return $query->getRow();
	}
		public function dadosMembrosAtoCirurgico($codAtoCirurgico)
	{
		$query = $this->db->query('select ef.siglaEstadoFederacao,m.nomeMembro,m.conselhoMembro,m.inscricaoMembro,f.descricaoFuncao,f.codFuncao
		from cir_atocirurgicomembros m
		join sis_estadosfederacao ef on ef.codEstadoFederacao=m.codEstadoFederacao
		join cir_atocirurgicofuncoes f on f.codFuncao=m.codFuncaoMembro
		where m.codAtoCirurgico ="' . $codAtoCirurgico . '" order by f.ordenacao asc');
		return $query->getResult();
	}
	
	public function procedimentosCBHPM($codAtoCirurgico)
	{
		$query = $this->db->query('select p.dataInicio,p.dataEncerramento, tc.descricao as descricaoTabelaConvenio,pp.descricao as descricaoProcedimento,pp.referencia,p.qtde, pp.valor*p.qtde as custo 
		from cir_atocirurgicoprocedimentos p
		join amb_procedimentos pp on pp.codProcedimento=p.codProcedimento
		join sis_tabelarefconvenio tc on tc.codTabelaRef=p.codTabelaRef
		where p.codAtoCirurgico ="' . $codAtoCirurgico . '" order by p.classificacaoProcedimento desc,p.codAtoCirurgicoProcedimento asc');
		return $query->getResult();
	}	
	
	public function procedimentosMatMedOPME($codAtoCirurgico)
	{
		$query = $this->db->query('select iff.descricaoItem,m.qtde,u.descricaoUnidade,c.descricaoCategoria
		from cir_atocirurgicomatmed m
		join sis_itensfarmacia iff on iff.codItem=m.codMatMed
		join sis_itensfarmaciacategoria c on c.codCategoria=iff.codCategoria
		join sis_unidades u on u.codUnidade=m.codUnidade
		where m.codAtoCirurgico ="' . $codAtoCirurgico . '" order by c.codCategoria asc');
		return $query->getResult();
	}
	public function dadosPacienteAtoCirurgico($codAtoCirurgico)
	{
		$query = $this->db->query('select ta.descricao descricaoTipoAnestesia,a.descricao as descricaoAto,a.posOperatorio,a.intercorrencias,a.resumoProcedimento,a.preOperatorio,a.codStatus, d.descricaoDepartamento,
		la.codLocalAtendimento,la.descricaoLocalAtendimento,
		pa.nomeExibicao,pa.cpf,pa.codProntuario,TIMESTAMPDIFF (YEAR, pa.dataNascimento,CURDATE()) as idade, 
		c.descricaoCargo,c.siglaCargo,tb.nomeTipoBeneficiario, DATE_FORMAT(pa.dataNascimento,"%d/%m/%Y") as dataNascimentoPerfil, 
		DATE_FORMAT(pa.validade,"%d/%m/%Y") as validadePerfil,DATE_FORMAT(pa.dataNascimento,"%d/%m/%Y") as dataNascimentoPerfil,
		pe.nomeExibicao autorUltimaAtualizacao, DATE_FORMAT(pa.dataAtualizacao,"%d/%m/%Y %H:%i") AS dataUltimaAtualizacao, 
		pa.ativo,  tb.siglaTipoBeneficiario, pa.codPlano
		from cir_atocirurgico a
		left join amb_atendimentos aa on aa.codAtendimento=a.codAtendimento
		left join sis_pacientes pa on pa.codPaciente=aa.codPaciente
		left join sis_pessoas pe on aa.codAutor=pe.codPessoa
		left join cir_atotiposanestesia ta on ta.codTipoAnestesia=a.codTipoAnestesia
		left join sis_tipobeneficiario tb on tb.codTipoBeneficiario=pa.codTipoBeneficiario
		left join sis_cargos c on c.codCargo=pa.codCargo and c.codOrganizacao=pa.codOrganizacao
		left join amb_atendimentoslocais la on la.codLocalAtendimento=aa.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento
		where a.codAtoCirurgico ="' . $codAtoCirurgico . '"');
		return $query->getRow();
	}
}
