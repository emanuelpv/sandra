<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class ControleAntimicrobianoModel extends Model
{

	protected $table = 'amb_controleantimicrobiano';
	protected $primaryKey = 'codControleAntimicrobiano';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['dataPedidoCultura','dataSuspensao','suspensoPor','motivoSuspensaoGuia','detalheResultadoCultura','codStatus','per', 'qtde', 'via', 'und', 'freq', 'dias', 'codItem', 'codAtendimento', 'codPaciente', 'codAutor', 'dataCriacao', 'dataAtualizacao', 'dataInicio', 'dataEncerramento', 'primeiraEscolha', 'indicacaoAntibiotico', 'tipoInfeccao', 'respiratoria', 'urinaria', 'peleTecido', 'cirurgia', 'correnteSanguinea', 'outroSitioInfecao', 'resultadoCultura', 'faltaMedicamentoFarmacia', 'alergiaAntimicrobiano', 'insuficienciaRenal', 'insuficienciaHepatica', 'outroEsquemaAlternativo', 'justificativaEsquema', 'resultadoCultura', 'solicitouCultura'];
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
		$query = $this->db->query('select * from amb_controleantimicrobiano');
		return $query->getResult();
	}
	public function verificaGuiaAtiva($codItem, $codPaciente)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from amb_controleantimicrobiano
		where codItem="' . $codItem . '" and codPaciente="' . $codPaciente . '"
		and dataEncerramento >=DATE_FORMAT(NOW(), "%Y-%m-%d") and codStatus>=1 limit 1');
		return $query->getRow();
	}

	public function pegaDiagnosticos($codAtendimento)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select c.cid
		from amb_atendimentos a
		left join amb_atendimentosdiagnostico ad on ad.codAtendimento=a.codAtendimento
		left join amb_cid10 c on c.codCid=ad.codCid
		where a.codAtendimento="' . $codAtendimento . '" order by ad.codTipoDiagnostico asc limit 1');
		return $query->getRow();
	}
	public function pegaTudoPorPaciente($codPaciente)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select pp.nomeExibicao as autorSuspensao,p.nomeExibicao,cam.*, cam.codAtendimento,iff.descricaoItem
		from amb_controleantimicrobiano  cam
		left join sis_pessoas p on p.codPessoa=cam.codAutor
		left join sis_itensfarmacia iff on iff.codItem=cam.codItem 	
		left join sis_pessoas pp on pp.codPessoa=cam.suspensoPor 	
		where cam.codPaciente="' . $codPaciente . '" order by codControleAntimicrobiano desc');
		return $query->getResult();
	}

	public function pegaPorCodigo($codControleAntimicrobiano)
	{
		$query = $this->db->query('select em.numeroInscricao,uf.siglaEstadoFederacao as uf,c.nomeConselho,cca.siglaCargo,pe.nomeCompleto as nomeCompletoEspecialista,ca.codAtendimento,p.nomeExibicao,p.codProntuario,a.dataCriacao as dataInternacao, TIMESTAMPDIFF(YEAR, p.dataNascimento,CURDATE()) as idade,p.sexo, d.abreviacaoDepartamento,la.descricaoLocalAtendimento,ca.*,iff.descricaoItem,pp.descricaoPeriodo,u.descricaoUnidade, v.descricaoVia from 
		amb_controleantimicrobiano ca
		left join sis_pacientes p on p.codPaciente=ca.codPaciente
		left join amb_atendimentos a on a.codAtendimento=ca.codAtendimento
		left join sis_itensfarmacia iff on iff.codItem=ca.codItem
		left join sis_unidades u on u.codUnidade=ca.und
		left join sis_vias v on v.codVia=ca.via
		left join sis_pessoas pe on pe.codPessoa=ca.codAutor 
        left join sis_cargos cca on cca.codCargo = pe.codCargo
        left join sis_especialidadesmembros em on em.codPessoa = pe.codPessoa
        left join sis_especialidades e on e.codEspecialidade = em.codEspecialidade
        left join sis_conselhos c on c.codConselho = e.codConselho
        left join sis_estadosfederacao uf on uf.codEstadoFederacao = em.codEstadoFederacao
		left join sis_periodoprescricao pp on pp.codPeriodo=ca.per
		left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento        
		where ca.codControleAntimicrobiano ="' . $codControleAntimicrobiano . '"');
		return $query->getRow();
	}

    public function esquemasAntimicrobianos()
	{
		$query = $this->db->query('select em.numeroInscricao,uf.siglaEstadoFederacao as uf,c.nomeConselho,cca.siglaCargo,pe.nomeCompleto as nomeCompletoEspecialista,ca.codAtendimento,p.nomeExibicao,p.codProntuario,a.dataCriacao as dataInternacao, TIMESTAMPDIFF(YEAR, p.dataNascimento,CURDATE()) as idade,p.sexo, d.abreviacaoDepartamento,la.descricaoLocalAtendimento,ca.*,iff.descricaoItem,pp.descricaoPeriodo,u.descricaoUnidade, v.descricaoVia from 
		amb_controleantimicrobiano ca
		left join sis_pacientes p on p.codPaciente=ca.codPaciente
		left join amb_atendimentos a on a.codAtendimento=ca.codAtendimento
		left join sis_itensfarmacia iff on iff.codItem=ca.codItem
		left join sis_unidades u on u.codUnidade=ca.und
		left join sis_vias v on v.codVia=ca.via
		left join sis_pessoas pe on pe.codPessoa=ca.codAutor 
        left join sis_cargos cca on cca.codCargo = pe.codCargo
        left join sis_especialidadesmembros em on em.codPessoa = pe.codPessoa
        left join sis_especialidades e on e.codEspecialidade = em.codEspecialidade
        left join sis_conselhos c on c.codConselho = e.codConselho
        left join sis_estadosfederacao uf on uf.codEstadoFederacao = em.codEstadoFederacao
		left join sis_periodoprescricao pp on pp.codPeriodo=ca.per
		left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento        
		where ca.dataEncerramento >= CURDATE() and ca.codStatus=1');
		return $query->getResult();
	}
}
