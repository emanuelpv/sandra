<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class AtendimentoSenhasModel extends Model
{

	protected $table = 'amb_atendimentosenha';
	protected $primaryKey = 'codSenhaAtendimento';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['qtdChamadas', 'dataHoraPrimeiraChamada', 'faltou', 'dataEncerramentoAtendimento', 'dataInicioAtendimento', 'dataAgendamento', 'dataAtualizacao', 'dataCriacao', 'codAutor', 'ordemAtendimento', 'codOrganizacao', 'codDepartamento', 'codConfig', 'protocolo', 'codTipoFila', 'codAtendente', 'dataProtocolo', 'codPaciente', 'idade', 'cpf', 'senha', 'codPrioridade', 'dataInicio', 'codStatus', 'dataEncerramento', 'nomePaciente', 'codDepartamento', 'codLocalAtendimento', 'fotoPerfil'];
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
		$query = $this->db->query('select * from amb_atendimentosenha');
		return $query->getResult();
	}


	public function pegaAtendimentos()
	{

		if (session()->codTipoFila == NULL or session()->codTipoFila == ''  or session()->codTipoFila == ' ') {
			$codTipoFila = 1;
		} else {
			$codTipoFila = session()->codTipoFila;
		}


		if (session()->codDepartamentoAtendimento == NULL) {
			$codDepartamento = 0;
		} else {
			$codDepartamento = session()->codDepartamentoAtendimento;
		}


		if (session()->dataInicioAgendados !== NULL and session()->dataInicioAgendados !== "" and session()->dataInicioAgendados !== " ") {

			$query = $this->db->query('select atds.*, p.nomeExibicao,pp.codPlano,pp.celular
		from amb_atendimentosenha atds
		left join sis_pessoas p on p.codPessoa=atds.codAtendente
		left join sis_pacientes pp on pp.codPaciente=atds.codPaciente
		where atds.codTipoFila = ' . $codTipoFila . '
		and dataAgendamento is not null and atds.codDepartamento = ' . $codDepartamento . '
		and atds.codStatus<>3 and DATE_FORMAT(atds.dataInicio, "%Y-%m-%d") = "' . date('Y-m-d', strtotime(session()->dataInicioAgendados)) . '"
		order by atds.dataInicio, idade desc, codStatus desc, atds.senha asc');
		} else {
			$codOrganizacao = session()->codOrganizacao;
			$query = $this->db->query('select atds.*, p.nomeExibicao,pp.codPlano
		from amb_atendimentosenha atds
		left join sis_pessoas p on p.codPessoa=atds.codAtendente
		left join sis_pacientes pp on pp.codPaciente=atds.codPaciente
		where atds.codTipoFila = ' . $codTipoFila . '
		and dataAgendamento is not null and atds.codDepartamento = ' . $codDepartamento . '
		and atds.codStatus<>3 and DATE_FORMAT(atds.dataInicio, "%Y-%m-%d") = DATE_FORMAT(NOW(), "%Y-%m-%d")
		order by atds.dataInicio, idade desc, codStatus desc, atds.senha asc');
		}
		return $query->getResult();
	}

	public function comprovante($codAgendamento)
	{
		$codOrganicacao = session()->codOrganizacao;
		$query = $this->db->query('select a.*,la.descricaoLocalAtendimento,pp.nomeExibicao as autorMarcacao,d.descricaoDepartamento as descricaoEspecialidade,d.descricaoDepartamento,
		pa.nomeCompleto,pa.codPaciente,
			pa.nomeCompleto as nomePaciente, a.protocolo,pa.codProntuario,pa.codPlano
			from amb_atendimentosenha a
			left join amb_agendamentosenhasconfig ac on ac.codConfig=a.codConfig
			left join sis_pessoas pp on a.codAutor=pp.codPessoa	
			left join sis_pacientes pa on pa.codPaciente = a.codPaciente
			left join sis_departamentos d on d.codDepartamento = a.codDepartamento
			left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
			where a.codSenhaAtendimento = ' . $codAgendamento . ' and a.codOrganizacao=' . $codOrganicacao);
		return $query->getRow();
	}


	public function ultimoNumeroPrioridade($codTipoFila, $codDepartamento)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select count(*) as total from amb_atendimentosenha where codDepartamento = ' . $codDepartamento . ' and codTipoFila=' . $codTipoFila . ' and codPrioridade=1 and DATE_FORMAT(dataProtocolo, "%Y-%m-%d") >= DATE_FORMAT(NOW(), "%Y-%m-%d")');
		return $query->getRow();
	}


	public function proximoPrioridade($codTipoFila)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from amb_atendimentosenha where codTipoFila=' . $codTipoFila . ' and codStatus = 0 and codPrioridade=1 and DATE_FORMAT(dataProtocolo, "%Y-%m-%d") >= DATE_FORMAT(NOW(), "%Y-%m-%d") order by senha asc, dataProtocolo asc limit 1');
		return $query->getRow();
	}

	public function proximoNormal($codTipoFila)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from amb_atendimentosenha where codTipoFila=' . $codTipoFila . ' and  codStatus = 0 and codPrioridade=0 and DATE_FORMAT(dataProtocolo, "%Y-%m-%d") >= DATE_FORMAT(NOW(), "%Y-%m-%d") order by senha asc, dataProtocolo asc limit 1');
		return $query->getRow();
	}

	public function ultimoNumeroNormal($codTipoFila, $codDepartamento)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select count(*) as total from amb_atendimentosenha where codDepartamento = ' . $codDepartamento . ' and codTipoFila=' . $codTipoFila . ' and codPrioridade=0 and DATE_FORMAT(dataProtocolo, "%Y-%m-%d") >= DATE_FORMAT(NOW(), "%Y-%m-%d")');
		return $query->getRow();
	}







	public function pegaPorCodigo($codSenhaAtendimento)
	{
		$query = $this->db->query('select d.descricaoDepartamento,ass.*,la.descricaoLocalAtendimento
		from amb_atendimentosenha ass
		left join amb_atendimentoslocais la on la.codLocalAtendimento=ass.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=ass.codDepartamento
		where ass.codSenhaAtendimento = "' . $codSenhaAtendimento . '"');
		return $query->getRow();
	}

	public function verificaSeAgendado($codPaciente = NULL)
	{
		$query = $this->db->query('select  ass.*, p.nomeCompleto,p.nomeExibicao,d.descricaoDepartamento 
		from amb_atendimentosenha ass
		left join amb_atendimentoslocais la on la.codLocalAtendimento=ass.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=ass.codDepartamento
		left join sis_pacientes p on p.codPaciente=ass.codPaciente
		where ass.dataInicio >= NOW() and ass.codPaciente = "' . $codPaciente . '"');
		return $query->getRow();
	}


}
