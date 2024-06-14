<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class AtendimentosPrescricoesModel extends Model
{

	protected $table = 'amb_atendimentosprescricoes';
	protected $primaryKey = 'codAtendimentoPrescricao';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codTipoPrescricao','migrado', 'dispensacaoAssinadaPor', 'prescricaoAssinadaPor', 'codAutorDispensacao', 'dieta', 'codAtendimento', 'codOrganizacao', 'codLocalAtendimento', 'codStatus', 'conteudoPrescricao', 'impresso', 'codAutor', 'dataCriacao', 'dataAtualizacao', 'dataInicio', 'dataEncerramento'];
	protected $useTimestamps = false;
	protected $createdField = 'created_at';
	protected $updatedField = 'updated_at';
	protected $deletedField = 'deleted_at';
	protected $validationRules = [];
	protected $validationMessages = [];
	protected $skipValidation = true;


	public function pegaTudo()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from amb_atendimentosprescricoes');
		return $query->getResult();
	}


	public function listaDropDownTipoPrescricao()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select codTipoPrescricao as id, descricaoTipo as text from amb_atendimentosprescricoestipo');
		return $query->getResult();
	}


	public function pegaDiarias($codAtendimento = NULL, $dataInicio = NULL, $dataEncerramento = NULL, $ccusto = NULL)
	{
		$filtro = '';


		if ($dataInicio !== NULL and $dataInicio !== "" and $dataInicio !== " ") {
			$filtro .= ' and ap.dataInicio >= "' . $dataInicio . '"';
		}

		if ($dataEncerramento !== NULL and $dataEncerramento !== "" and $dataEncerramento !== " ") {
			$filtro .= ' and ap.dataEncerramento <="' . $dataEncerramento . '"';
		}

		$filtro = '';
		$ccusto = str_replace('"', "", $ccusto);
		if ($ccusto !== "0" and $ccusto !== NULL and $ccusto !== "" and $ccusto !== " ") {

			$filtro .= ' and d.codDepartamento in(' . $ccusto . ')';
		}


		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select ap.codLocalAtendimento, max(ap.dataEncerramento) as dataEncerramento,min(ap.dataInicio) as dataInicio, ap.codLocalAtendimento, sum(ap.dataEncerramento - ap.dataInicio) as qtdDiarias,td.codTaxaServico as codTaxaServicoPaciente,td.referencia as referenciaPaciente,td.valor as valorPaciente,
		tdd.codTaxaServico as codTaxaServicoAcompanhante,tdd.referencia as referenciaAcompanhante,tdd.valor as valorAcompanhante
		from amb_atendimentosprescricoes ap
		join amb_atendimentoslocais al on al.codLocalAtendimento=ap.codLocalAtendimento
		join sis_departamentos d on d.codDepartamento=al.codDepartamento
		join amb_taxasservicos td on td.codTaxaServico=d.codTaxaServico
		join amb_taxasservicos tdd on tdd.codTaxaServico=d.codTaxaServicoAcompanhante
		where ap.codAtendimento = ' . $codAtendimento . $filtro . ' and ap.dataInicio >= "2022-09-03"
        group by codLocalAtendimento
		ORDER BY ap.dataInicio  ASC');
		return $query->getResult();
	}


	public function removeItensRelacionados($codAtendimentoPrescricao)
	{
		if ($codAtendimentoPrescricao !== NULL and $codAtendimentoPrescricao !== "" and $codAtendimentoPrescricao !== " ") {

			$this->db->query('delete from amb_atendimentosprescricoesmedicamentos WHERE codAtendimentoPrescricao = ' . $codAtendimentoPrescricao);
			$this->db->query('delete from amb_atendimentosprescricoescuidados WHERE codAtendimentoPrescricao = ' . $codAtendimentoPrescricao);
			$this->db->query('delete from amb_atendimentosprescricoeskits WHERE codAtendimentoPrescricao = ' . $codAtendimentoPrescricao);
			$this->db->query('delete from amb_atendimentosprescricoesmateriais WHERE codAtendimentoPrescricao = ' . $codAtendimentoPrescricao);
			$this->db->query('delete from amb_atendimentosprescricoesoutras WHERE codAtendimentoPrescricao = ' . $codAtendimentoPrescricao);
		}
		return true;
	}

	public function pegaPorCodigo($codAtendimentoPrescricao)
	{
		$query = $this->db->query('select aa.historiaAlergias,ap.*,a.codPaciente 
		from amb_atendimentosprescricoes ap 
		left join amb_atendimentos a on a.codAtendimento=ap.codAtendimento
		left join amb_atendimentosanamnese aa on aa.codAtendimento=a.codAtendimento
		where ap.codAtendimentoPrescricao = ' . $codAtendimentoPrescricao);
		return $query->getRow();
	}


	public function alergias($codPaciente)
	{
		$query = $this->db->query('select * from sis_pacientesalergias where codPaciente = "' . $codPaciente . '"');
		return $query->getResult();
	}


	public function pegaPorCodAtendimento($codAtendimento)
	{
		$query = $this->db->query('select ap.*,p.nomeExibicao ,aps.*,apt.*,pp.nomeExibicao as nomeExibicaoAssinador
		from amb_atendimentosprescricoes ap
		left join sis_pessoas p on p.codPessoa=ap.codAutor 
		left join sis_pessoas pp on pp.codPessoa=ap.prescricaoAssinadaPor 
		left join amb_atendimentosprescricoesstatus aps on aps.codStatus=ap.codStatus 
		left join amb_atendimentosprescricoestipo apt on apt.codTipoPrescricao=ap.codTipoPrescricao 
		where ap.codAtendimento = ' . $codAtendimento . ' order by ap.codAtendimentoPrescricao desc');
		return $query->getResult();
	}

	public function prescricoesPorCodAtendimentoPrescricao($codAtendimentoPrescricao)
	{
		$query = $this->db->query('select pc.codPrescricaoComplementar,asm.dataCriacao as dataSuspensao,asm.motivo, asm.codSuspensaoMedicamento,ppe.nomeExibicao as autorSuspensao,pex.nomeExibicao as autorComplemento,apm.dataCriacao as dataCriacaoComplemento,ap.*,pe.nomeExibicao as especialista, pe.nomeCompleto as nomeCompletoEspecialista, pa.nomeCompleto as paciente, pa.codProntuario,
		aps.descricaoStatus,iff.descricaoItem,apm.qtde,u.descricaoUnidade,v.descricaoVia,apm.freq,
		pp.descricaoPeriodo, apm.dias, apm.horaIni,saa.descricaoAplicarAgora,
		rp.descricaoRiscoPrescricao,apm.obs,apm.apraza,apm.total,apm.obs as observacaoMedicamento,
		iff.nee,ap.dieta
		from amb_atendimentosprescricoes ap
		left join amb_atendimentos a on a.codAtendimento = ap.codAtendimento
		left join sis_pacientes pa on pa.codPaciente = a.codPaciente
		left join sis_pessoas pe on pe.codPessoa=ap.codAutor
		left join amb_atendimentosprescricoesstatus aps on aps.codStatus=ap.codStatus 
		left join amb_atendimentosprescricoesmedicamentos apm on apm.codAtendimentoPrescricao=ap.codAtendimentoPrescricao
		left join amb_atendimentossuspensaomedicamentos asm on asm.codPrescricaoMedicamento=apm.codPrescricaoMedicamento
        left join sis_pessoas ppe on ppe.codPessoa=asm.codAutor
		left join sis_pessoas pex on pex.codPessoa=apm.codAutor
		left join amb_prescricaocomplementar pc on pc.codPrescricaoMedicamento=apm.codPrescricaoMedicamento
		left join sis_itensfarmacia iff on iff.codItem=apm.codMedicamento 
		left join sis_unidades u on u.codUnidade=apm.und 
		left join sis_vias v on v.codVia=apm.via 
		left join sis_periodoprescricao pp on pp.codPeriodo=apm.per 
		left join sis_statusaplicaragora saa on saa.codAplicarAgora=apm.agora 
		left join sis_riscoprescricao rp on rp.codRiscoPrescricao=apm.risco 
		where ap.codAtendimentoPrescricao = ' . $codAtendimentoPrescricao . ' order by apm.codPrescricaoMedicamento desc');
		return $query->getResult();
	}


	public function cuidadosPorCodAtendimentoPrescricao($codAtendimentoPrescricao)
	{
		$query = $this->db->query('select *
		from amb_atendimentosprescricoes ap
		left join amb_atendimentosprescricoescuidados apc on apc.codAtendimentoPrescricao=ap.codAtendimentoPrescricao
		left join sis_tiposcuidadosprescricoes tcp on apc.codTipoCuidadoPrescricao=tcp.codTipoCuidadoPrescricao
		where ap.codAtendimentoPrescricao = ' . $codAtendimentoPrescricao . ' order by ap.codAtendimentoPrescricao desc');
		return $query->getResult();
	}

	public function kitsPorCodAtendimentoPrescricao($codAtendimentoPrescricao)
	{
		$query = $this->db->query('select *
		from amb_atendimentosprescricoes ap
		left join amb_atendimentosprescricoeskits apk on apk.codAtendimentoPrescricao=ap.codAtendimentoPrescricao
		left join sis_kits k on apk.codKit=k.codKit
		where ap.codAtendimentoPrescricao = ' . $codAtendimentoPrescricao . ' order by ap.codAtendimentoPrescricao desc');
		return $query->getResult();
	}

	public function procedimentosPorCodAtendimentoPrescricao($codAtendimentoPrescricao)
	{
		$query = $this->db->query('select *
		from amb_atendimentosprescricoes ap
		left join amb_atendimentosprescricoesprocedimentos apk on apk.codAtendimentoPrescricao=ap.codAtendimentoPrescricao
		left join amb_procedimentos k on apk.codProcedimento=k.codProcedimento
		where ap.codAtendimentoPrescricao = ' . $codAtendimentoPrescricao . ' order by ap.codAtendimentoPrescricao desc');
		return $query->getResult();
	}




	public function materiaisPorCodAtendimentoPrescricao($codAtendimentoPrescricao)
	{
		$query = $this->db->query('select apm.*,iff.*,ap.*, apm.observacao as observacaoMaterial
		from amb_atendimentosprescricoes ap
		left join amb_atendimentosprescricoesmateriais apm on apm.codAtendimentoPrescricao=ap.codAtendimentoPrescricao
		left join sis_itensfarmacia iff on iff.codItem=apm.codMaterial
		where ap.codAtendimentoPrescricao = ' . $codAtendimentoPrescricao . ' order by ap.codAtendimentoPrescricao desc');
		return $query->getResult();
	}


	public function outrasPorCodAtendimentoPrescricao($codAtendimentoPrescricao)
	{
		$query = $this->db->query('select *
		from amb_atendimentosprescricoes ap
		left join amb_atendimentosprescricoesoutras apo on apo.codAtendimentoPrescricao=ap.codAtendimentoPrescricao
		left join sis_tiposoutrasprescricoes op on apo.codTipoOutraPrescricao=op.codTipoOutraPrescricao
		where ap.codAtendimentoPrescricao = ' . $codAtendimentoPrescricao . ' order by ap.codAtendimentoPrescricao desc');
		return $query->getResult();
	}


	public function atendimentoPorCodAtendimentoPrescricao($codAtendimentoPrescricao)
	{
		$query = $this->db->query('select aa.historiaAlergias,a.codPaciente,d.abreviacaoDepartamento,la.descricaoLocalAtendimento,tp.siglaTipoBeneficiario,c.nomeConselho,ca.siglaCargo,em.numeroInscricao,uf.siglaEstadoFederacao as uf,a.codAtendimento,  pe.nomeCompleto as nomeCompletoEspecialista, ap.*,pe.nomeExibicao as especialista, pa.nomeCompleto as paciente, 
		pa.codProntuario,pa.codPlano, aps.descricaoStatus,ap.dataInicio, ap.dataEncerramento, ap.codAtendimentoPrescricao,ap.prescricaoAssinadaPor,TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade
		from amb_atendimentosprescricoes ap
		left join amb_atendimentos a on a.codAtendimento = ap.codAtendimento
		left join amb_atendimentosanamnese aa on aa.codAtendimento = a.codAtendimento
		left join sis_pacientes pa on pa.codPaciente = a.codPaciente
		left join sis_tipobeneficiario tp on pa.codTipoBeneficiario = tp.codTipoBeneficiario
		left join sis_pessoas pe on pe.codPessoa=ap.prescricaoAssinadaPor 
        left join sis_cargos ca on ca.codCargo = pe.codCargo
        left join sis_especialidadesmembros em on em.codPessoa = pe.codPessoa
        left join sis_especialidades e on e.codEspecialidade = em.codEspecialidade
        left join sis_conselhos c on c.codConselho = e.codConselho
        left join sis_estadosfederacao uf on uf.codEstadoFederacao = em.codEstadoFederacao
		left join amb_atendimentosprescricoesstatus aps on aps.codStatus=ap.codStatus
		left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento        
        where ap.codAtendimentoPrescricao =' . $codAtendimentoPrescricao . ' order by ap.codAtendimentoPrescricao desc limit 1');
		return $query->getRow();
	}




	public function pegaPorcodAtendimentoPrescricaoEAtendimento($codAtendimentoPrescricao, $codAtendimento)
	{
		$query = $this->db->query('select * from amb_atendimentosprescricoes where codAtendimentoPrescricao = "' . $codAtendimentoPrescricao . '" and codAtendimento="' . $codAtendimento . '"');
		return $query->getRow();
	}





	public function pegaPorCodAtendimentoPrescricao($codAtendimentoPrescricao)
	{
		$query = $this->db->query('select ap.*,p.nomeExibicao, pa.nomeExibicao as nomePaciente, TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade, aps.descricaoStatus,d.abreviacaoDepartamento,la.descricaoLocalAtendimento
		from amb_atendimentosprescricoes ap
		left join amb_atendimentos a on a.codAtendimento=ap.codAtendimento 
		left join sis_pessoas p on p.codPessoa=ap.codAutor 
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente
		left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento		
		left join amb_atendimentosprescricoesstatus aps on aps.codStatus=ap.codStatus 
		where ap.codAtendimentoPrescricao = ' . $codAtendimentoPrescricao . ' order by ap.codAtendimentoPrescricao desc');
		return $query->getRow();
	}

	public function pegaQtdeMedicamentosPrescricao($codAtendimentoPrescricao)
	{
		$query = $this->db->query('select count(*) as total
		from amb_atendimentosprescricoesmedicamentos
		where codAtendimentoPrescricao = ' . $codAtendimentoPrescricao);
		return $query->getRow();
	}

	public function pegaQtdeMateriaisPrescricao($codAtendimentoPrescricao)
	{
		$query = $this->db->query('select count(*) as total
		from amb_atendimentosprescricoesmateriais
		where codAtendimentoPrescricao = ' . $codAtendimentoPrescricao);
		return $query->getRow();
	}


	public function clonarMedicamentos($codAtendimentoPrescricao)
	{
		$query = $this->db->query('select ap.*,p.nomeExibicao ,aps.descricaoStatus
		from amb_atendimentosprescricoes ap
		left join sis_pessoas p on p.codPessoa=ap.codAutor 
		left join amb_atendimentosprescricoesstatus aps on aps.codStatus=ap.codStatus 
		where ap.codAtendimentoPrescricao = ' . $codAtendimentoPrescricao . ' order by ap.dataCriacao desc');
		return $query->getResult();
	}
}