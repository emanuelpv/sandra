<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class AtendimentosModel extends Model
{

	protected $table = 'amb_atendimentos';
	protected $primaryKey = 'codAtendimento';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['migrado', 'codStatusConta', 'legado', 'codClasseRisco', 'codOrganizacao', 'codPaciente', 'codLocalAtendimento', 'codEspecialista', 'codEspecialidade', 'codStatus', 'dataCriacao', 'dataAtualizacao', 'dataInicio', 'dataEncerramento', 'codTipoAtendimento', 'codAutor'];
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
		$query = $this->db->query('select * from amb_atendimentos');
		return $query->getResult();
	}

	public function maximaData()
	{
		$query = $this->db->query('select max(dataCriacao) as data from amb_atendimentos where codStatus=0');
		return $query->getRow();
	}


	public function getStatusAtendimento($codStatus)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select descricaoStatusAtendimento from amb_atendimentosstatus where codStatusAtendimento=' . $codStatus);
		return $query->getRow();
	}


	public function listaDropDownStatusAtendimento()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select codStatusAtendimento id, descricaoStatusAtendimento as text from amb_atendimentosstatus');
		return $query->getResult();
	}
	public function listaDropDownStatusEncerramentoAtendimento()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select codStatusAtendimento id, descricaoStatusAtendimento as text from amb_atendimentosstatus where codStatusAtendimento in(2, 3, 8, 9, 11)');
		return $query->getResult();
	}
	public function listaDropDownCid10()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select codCid id, cid as text from amb_cid10');
		return $query->getResult();
	}
	public function pegaPorCodigo($codAtendimento)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codAtendimento = "' . $codAtendimento . '"');
		return $query->getRow();
	}

	public function previsaoAlta($codAtendimento)
	{
		$query = $this->db->query('select atp.*,a.dataEncerramento,a.codStatus,d.codTipoDepartamento
		from amb_atendimentos a
		left join amb_atendimentosprevalta atp on a.codAtendimento=atp.codAtendimento
        left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento
		where a.codAtendimento = "' . $codAtendimento . '" 
		order by atp.codPrevAlta desc limit 1');
		return $query->getRow();
	}


	public function verificaSeUnidadeInternacao($codAtendimento)
	{
		$query = $this->db->query('select d.codTipoDepartamento from amb_atendimentos a
        join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		join sis_departamentos d on d.codDepartamento=la.codDepartamento
		where d.codTipoDepartamento in (2,6) and a.codAtendimento = "' . $codAtendimento . '"');
		return $query->getRow();
	}


	public function pegaAlergias($codAtendimento)
	{
		$query = $this->db->query('select paa.descricaoAlergenico from amb_atendimentos a
		join sis_pacientes pa on pa.codPaciente=a.codPaciente 
		join sis_pacientesalergias paa on paa.codPaciente=pa.codPaciente 
		where a.codAtendimento = "' . $codAtendimento . '" 
		order by paa.descricaoAlergenico asc');
		return $query->getResult();
	}

	public function verificaExistencia($codPaciente = null, $codEspecialista = null, $codTipoAtendimento = null)
	{
		$query = $this->db->query('select *, c.siglaCargo,tb.nomeTipoBeneficiario, TIMESTAMPDIFF (YEAR, pa.dataNascimento,CURDATE()) as idade 
		from amb_atendimentos a		
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente		
		left join sis_tipobeneficiario tb on tb.codTipoBeneficiario=pa.codTipoBeneficiario
		left join sis_cargos c on c.codCargo=pa.codCargo and c.codOrganizacao=pa.codOrganizacao  
		where a.codTipoAtendimento =' . $codTipoAtendimento . ' and (a.codStatus = 0 or a.dataEncerramento is null) and a.codPaciente = ' . $codPaciente . ' and a.codEspecialista=' . $codEspecialista . ' limit 1');
		return $query->getRow();
	}
		public function verificaExistenciaAtendimentoEmAberto($codPaciente = null, $codEspecialista = null, $codTipoAtendimento = null)
	{
		$query = $this->db->query('select *, c.siglaCargo,tb.nomeTipoBeneficiario, TIMESTAMPDIFF (YEAR, pa.dataNascimento,CURDATE()) as idade 
		from amb_atendimentos a		
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente		
		left join sis_tipobeneficiario tb on tb.codTipoBeneficiario=pa.codTipoBeneficiario
		left join sis_cargos c on c.codCargo=pa.codCargo and c.codOrganizacao=pa.codOrganizacao  
		where a.codTipoAtendimento =' . $codTipoAtendimento . ' and a.codStatus <> 0 and a.dataEncerramento is null and a.migrado=0 and a.codPaciente = ' . $codPaciente . ' and a.codEspecialista=' . $codEspecialista . ' order by a.dataCriacao desc limit 1');
		return $query->getRow();
	}
	public function verificaExistenciaUrgenciaEmergencia($codPaciente)
	{
		$query = $this->db->query('select *,TIMESTAMPDIFF (YEAR, pa.dataNascimento,CURDATE()) as idade from amb_atendimentos a		
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente
		where a.codTipoAtendimento =1 and a.dataEncerramento is null and a.codPaciente = "' . $codPaciente . '" order by a.codAtendimento desc limit 1');
		return $query->getRow();
	}

	public function verificaExistenciaTratOncologico($codPaciente)
	{
		$query = $this->db->query('select *,TIMESTAMPDIFF (YEAR, pa.dataNascimento,CURDATE()) as idade from amb_atendimentos a		
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente
		where a.codTipoAtendimento =6 and a.dataEncerramento is null and a.codPaciente = "' . $codPaciente . '" order by a.codAtendimento desc limit 1');
		return $query->getRow();
	}

	public function verificaExistePrescricao($codAtendimento)
	{
		$query = $this->db->query('select ap.*,aps.*, p.nomeExibicao
		from amb_atendimentosprescricoes ap
		left join sis_pessoas p on p.codPessoa=ap.codAutor
		join amb_atendimentosprescricoesstatus aps on aps.codStatus= ap.codStatus
		where ap.codAtendimento = "' . $codAtendimento . '" and ap.dataInicio = CURDATE()');
		return $query->getRow();
	}



	public function verificaExistenciaMigracao($codPaciente = null, $codEspecialista = null, $codEspecialidade = null, $dataInicio = null)
	{

		$query = $this->db->query('select * from amb_atendimentos 
		where codPaciente = "' . $codPaciente . '" and codEspecialista = "' . $codEspecialista . '" and codEspecialidade="' . $codEspecialidade . '" and dataInicio = "' . $dataInicio . '"');
		return $query->getRow();
	}


	public function verificaExistenciaEvolucao($codPaciente = null, $codEspecialista = null, $dataCriacao = null)
	{

		$query = $this->db->query('select * 
		from amb_atendimentosevolucoes e
		join amb_atendimentos a on a.codAtendimento=e.codAtendimento
		where a.codPaciente = "' . $codPaciente . '" and a.codEspecialista = "' . $codEspecialista . '" and e.dataCriacao="' . $dataCriacao . '"');
		return $query->getRow();
	}

	public function verificaExistenciaConduta($codPaciente = null, $codEspecialista = null, $dataCriacao = null)
	{

		$query = $this->db->query('select * 
		from amb_atendimentoscondutas c
		join amb_atendimentos a on a.codAtendimento=c.codAtendimento
		where a.codPaciente = "' . $codPaciente . '" and a.codEspecialista = "' . $codEspecialista . '" and c.dataCriacao="' . $dataCriacao . '"');
		return $query->getRow();
	}



	public function verificaExistenciaDiagnostico($codAtendimento = null)
	{

		$query = $this->db->query('select * from amb_atendimentosdiagnostico 
		where codAtendimento = "' . $codAtendimento . '"');
		return $query->getRow();
	}


	public function pacientesInternados($codDepartamento = NULL)
	{
		$filtro = '';

		if ($codDepartamento == NULL) {
			$filtro .= '';
		} else {
			if ($codDepartamento == 0) {
				$filtro .= '';
			} else {
				$filtro .= ' and la.codDepartamento=' . $codDepartamento;
			}
		}


		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select a.codPaciente, cr.*, a.codAtendimento,pa.nomeCompleto, TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade,
		aa.hda,a.dataCriacao,d.descricaoDepartamento,la.codLocalAtendimento,la.descricaoLocalAtendimento,s.descricaoStatusAtendimento
		from  amb_atendimentos a 
		left join amb_atendimentosanamnese aa on aa.codAtendimento=a.codAtendimento
		left join amb_atendimentosstatus s on s.codStatusAtendimento=a.codStatus
		left join sis_pessoas pe on pe.codPessoa=a.codEspecialista
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente		
		left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento		
		left join amb_classificacaorisco cr on cr.codClasseRisco=a.codClasseRisco		
		where a.codOrganizacao=' . $codOrganizacao . $filtro . ' and a.dataEncerramento is null and (a.codTipoAtendimento = 4 or (a.codTipoAtendimento=1 and a.codStatus=7 )) and a.codStatus not in(2, 3, 8, 9, 11)
		order by a.codClasseRisco desc,a.dataCriacao asc');
		return $query->getResult();
	}


	public function situacaoLeitos()
	{
		$filtro = "";

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select als.descricaoSituacaoLocalAtendimento, a.codAtendimento,pa.nomeCompleto, TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade,
		a.dataCriacao,d.descricaoDepartamento,la.codLocalAtendimento,la.descricaoLocalAtendimento,s.descricaoStatusAtendimento
		from  amb_atendimentoslocais  la
		left join amb_atendimentos a on la.codLocalAtendimento=a.codLocalAtendimento 
		and a.codOrganizacao=' . $codOrganizacao . ' and a.dataEncerramento is null 
		and a.codTipoAtendimento = 4 and a.codStatus not in (2, 3, 8, 9, 11) and a.legado=0
		left join amb_atendimentoslocaissituacao als on als.codSituacaoLocalAtendimento=la.codSituacaoLocalAtendimento
		left join amb_atendimentosstatus s on s.codStatusAtendimento=a.codStatus
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente		
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento		
		where la.codTipoLocalAtendimento=2  and la.codStatusLocalAtendimento =1 and la.codSituacaoLocalAtendimento not in(3)
		order by la.descricaoLocalAtendimento asc');
		return $query->getResult();
	}


	public function situacaoLeitosPorDepartamento($codDepartamento)
	{
		$filtro = "";

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select la.codSituacaoLocalAtendimento,als.descricaoSituacaoLocalAtendimento, a.codAtendimento,pa.nomeCompleto, TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade,
		a.dataCriacao,d.descricaoDepartamento,la.codLocalAtendimento,la.descricaoLocalAtendimento,s.descricaoStatusAtendimento
		from  amb_atendimentoslocais  la
		left join amb_atendimentos a on la.codAtendimento=a.codAtendimento 
		left join amb_atendimentoslocaissituacao als on als.codSituacaoLocalAtendimento=la.codSituacaoLocalAtendimento
		left join amb_atendimentosstatus s on s.codStatusAtendimento=a.codStatus
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente		
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento
		where la.codTipoLocalAtendimento=2 and la.codStatusLocalAtendimento =1 and la.codSituacaoLocalAtendimento not in(3) and la.codDepartamento = ' . $codDepartamento . '	
		order by la.descricaoLocalAtendimento asc');
		return $query->getResult();
	}

	public function listaLeitosInternacao()
	{
		$filtro = "";

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select la.codAtendimento,la.codLocalAtendimento,a.codLocalAtendimento as codLocalAtendimentoPrincipal
		from  amb_atendimentoslocais  la
		left join amb_atendimentos a on la.codAtendimento=a.codAtendimento 
		where la.codTipoLocalAtendimento=2 and la.codAtendimento is not null and a.codAtendimento is not null	
		order by la.codLocalAtendimento asc');
		return $query->getResult();
	}




	public function situacaoTodosLeitos()
	{
		$filtro = "";

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select la.codSituacaoLocalAtendimento,als.descricaoSituacaoLocalAtendimento, a.codAtendimento,pa.nomeCompleto, TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade,
		a.dataCriacao,d.descricaoDepartamento,la.codLocalAtendimento,la.descricaoLocalAtendimento,s.descricaoStatusAtendimento
		from  amb_atendimentoslocais  la
		left join amb_atendimentos a on la.codAtendimento=a.codAtendimento 
		left join amb_atendimentoslocaissituacao als on als.codSituacaoLocalAtendimento=la.codSituacaoLocalAtendimento
		left join amb_atendimentosstatus s on s.codStatusAtendimento=a.codStatus
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente		
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento
		where la.codTipoLocalAtendimento=2 and la.codStatusLocalAtendimento =1 and la.codSituacaoLocalAtendimento not in(3)
		order by la.descricaoLocalAtendimento asc');
		return $query->getResult();
	}


	public function pacientesUrgenciaEmergenciaAcolhimento()
	{
		$filtro = "";

		$codOrganizacao = session()->codOrganizacao;

		/*
		//CLASSIFICAÇÃO POR IDADE ABANDONADA - PEDIDO MÉDICO
		$query = $this->db->query('select a.codClasseRisco, cr.descricaoClasseRisco, cr.corClasseRisco, a.codAtendimento,a.codPaciente,pa.nomeCompleto,TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade,
		CASE
			WHEN TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) < 7 THEN 0
			WHEN TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) > 60 THEN 1
			ELSE 2
		END prioridade,
		aa.queixaPrincipal,a.dataCriacao,d.descricaoDepartamento,la.codLocalAtendimento,la.descricaoLocalAtendimento,s.descricaoStatusAtendimento
		from  amb_atendimentos a 
		left join amb_atendimentosanamnese aa on aa.codAtendimento=a.codAtendimento
		left join amb_atendimentosstatus s on s.codStatusAtendimento=a.codStatus
		left join sis_pessoas pe on pe.codPessoa=a.codEspecialista
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente		
		left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento		
		left join amb_classificacaorisco cr on cr.codClasseRisco=a.codClasseRisco		
		where a.codOrganizacao=' . $codOrganizacao . ' and a.dataEncerramento is null and a.codTipoAtendimento = 1 and a.codStatus in(0,12,13) and a.legado=0 
		order by a.codClasseRisco desc,prioridade asc, idade desc, a.dataCriacao asc');
		
		
		*/



		$query = $this->db->query('select a.codClasseRisco, cr.descricaoClasseRisco, cr.corClasseRisco, a.codAtendimento,a.codPaciente,pa.nomeCompleto,TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade,
		aa.queixaPrincipal,a.dataCriacao,d.descricaoDepartamento,la.codLocalAtendimento,la.descricaoLocalAtendimento,s.descricaoStatusAtendimento
		from  amb_atendimentos a 
		left join amb_atendimentosanamnese aa on aa.codAtendimento=a.codAtendimento
		left join amb_atendimentosstatus s on s.codStatusAtendimento=a.codStatus
		left join sis_pessoas pe on pe.codPessoa=a.codEspecialista
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente		
		left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento		
		left join amb_classificacaorisco cr on cr.codClasseRisco=a.codClasseRisco		
		where a.codOrganizacao=' . $codOrganizacao . ' and a.dataEncerramento is null and (d.codDepartamento=13 or la.codLocalAtendimento=0) and a.dataCriacao >= ADDDATE(NOW(), INTERVAL -8 HOUR) and a.codTipoAtendimento in(1) and a.codStatus in(0,12,13) and a.legado=0 
		order by a.codClasseRisco desc, a.dataCriacao asc');






		return $query->getResult();
	}

	public function pacientesUrgenciaEmergenciaEmAtendimento()
	{
		$filtro = "";

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select a.codClasseRisco, cr.descricaoClasseRisco, cr.corClasseRisco, a.codAtendimento,pe.nomeExibicao as nomeEspeciaoista,a.codPaciente,pa.nomeCompleto, 
		TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade,
		CASE
			WHEN TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) < 7 THEN 0
			WHEN TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) > 60 THEN 1
			ELSE 2
		END prioridade,
		aa.queixaPrincipal,a.dataCriacao,d.descricaoDepartamento,la.codLocalAtendimento,la.descricaoLocalAtendimento,s.descricaoStatusAtendimento
		from  amb_atendimentos a 
		left join amb_atendimentosanamnese aa on aa.codAtendimento=a.codAtendimento
		left join amb_atendimentosstatus s on s.codStatusAtendimento=a.codStatus
		left join sis_pessoas pe on pe.codPessoa=a.codEspecialista
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente		
		left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento		
		left join amb_classificacaorisco cr on cr.codClasseRisco=a.codClasseRisco		
		where a.codOrganizacao=' . $codOrganizacao . ' and a.dataEncerramento is null and a.codTipoAtendimento in(1,4,5) and d.codDepartamento = 13 and a.codStatus not in(0,12, 2, 3, 8, 9, 11,12,13) and a.dataCriacao >= ADDDATE(NOW(), INTERVAL -4 DAY) and a.legado=0 
		order by a.dataCriacao asc, a.codClasseRisco desc,prioridade asc, idade desc'); // ALTERADO EM 15/09/22 PARA ORDENAR POR TEMPO - SOL TEN VALENÇA
		return $query->getResult();
	}



	public function pacientesUrgenciaEmergenciaMeusPacientes()
	{
		$filtro = "";

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select a.codClasseRisco, cr.descricaoClasseRisco, cr.corClasseRisco, a.codAtendimento,pe.nomeExibicao as nomeEspeciaoista,a.codPaciente,pa.nomeCompleto, 
		TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade,
		CASE
			WHEN TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) < 7 THEN 0
			WHEN TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) > 60 THEN 1
			ELSE 2
		END prioridade,
		aa.queixaPrincipal,a.dataCriacao,d.descricaoDepartamento,la.codLocalAtendimento,la.descricaoLocalAtendimento,s.descricaoStatusAtendimento
		from  amb_atendimentos a 
		left join amb_atendimentosanamnese aa on aa.codAtendimento=a.codAtendimento
		left join amb_atendimentosstatus s on s.codStatusAtendimento=a.codStatus
		left join sis_pessoas pe on pe.codPessoa=a.codEspecialista
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente		
		left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento		
		left join amb_classificacaorisco cr on cr.codClasseRisco=a.codClasseRisco		
		where a.codEspecialista = "' . session()->codPessoa . '" and a.codOrganizacao=' . $codOrganizacao . ' and a.dataEncerramento is null and a.codTipoAtendimento in(1,4,5) and d.codDepartamento = 13  and a.codStatus not in(0,12, 2, 3, 8, 9, 11,12,13) and a.dataCriacao >= ADDDATE(NOW(), INTERVAL -4 DAY) and a.legado=0 
		order by a.dataCriacao asc, a.codClasseRisco desc,prioridade asc, idade desc'); // ALTERADO EM 15/09/22 PARA ORDENAR POR TEMPO - SOL TEN VALENÇA
		return $query->getResult();
	}

	public function pacientesUrgenciaEmergenciaBuscaAvancada($paciente)
	{
		$filtro = ' and  pa.nomeCompleto="traz nada"';

		if ($paciente !== NULL and $paciente !== '' and $paciente !== ' ') {
			$filtro = ' and ( pa.nomeCompleto like "%'.$paciente.'%" or pa.cpf like "%'.$paciente.'%" or pa.codPlano like "%'.$paciente.'%" )';
		}

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select a.codClasseRisco, cr.descricaoClasseRisco, cr.corClasseRisco, a.codAtendimento,pe.nomeExibicao as nomeEspeciaoista,a.codPaciente,pa.nomeCompleto, 
		TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade,
		CASE
			WHEN TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) < 7 THEN 0
			WHEN TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) > 60 THEN 1
			ELSE 2
		END prioridade,
		aa.queixaPrincipal,aa.hda,a.dataCriacao,d.descricaoDepartamento,la.codLocalAtendimento,la.descricaoLocalAtendimento,s.descricaoStatusAtendimento
		from  amb_atendimentos a 
		left join amb_atendimentosanamnese aa on aa.codAtendimento=a.codAtendimento
		left join amb_atendimentosstatus s on s.codStatusAtendimento=a.codStatus
		left join sis_pessoas pe on pe.codPessoa=a.codEspecialista
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente		
		left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento		
		left join amb_classificacaorisco cr on cr.codClasseRisco=a.codClasseRisco		
		where a.codOrganizacao=' . $codOrganizacao . $filtro . '
		order by a.dataCriacao desc, a.codClasseRisco desc,prioridade asc, idade desc');
		return $query->getResult();
	}
	public function pacientesUrgenciaEmergenciaBaixados()
	{
		$filtro = "";

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select a.codClasseRisco, cr.descricaoClasseRisco, cr.corClasseRisco, a.codAtendimento,pe.nomeExibicao as nomeEspeciaoista,a.codPaciente,pa.nomeCompleto, 
		TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade,
		CASE
			WHEN TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) < 7 THEN 0
			WHEN TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) > 60 THEN 1
			ELSE 2
		END prioridade,
		aa.queixaPrincipal,a.dataCriacao,d.descricaoDepartamento,la.codLocalAtendimento,la.descricaoLocalAtendimento,s.descricaoStatusAtendimento
		from  amb_atendimentos a 
		left join amb_atendimentosanamnese aa on aa.codAtendimento=a.codAtendimento
		left join amb_atendimentosstatus s on s.codStatusAtendimento=a.codStatus
		left join sis_pessoas pe on pe.codPessoa=a.codEspecialista
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente		
		left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento		
		left join amb_classificacaorisco cr on cr.codClasseRisco=a.codClasseRisco		
		where a.codOrganizacao=' . $codOrganizacao . ' and a.dataEncerramento is null and a.codTipoAtendimento in(1,4,5) and d.codDepartamento = 13  and a.codStatus in(7)
		order by a.dataCriacao asc, a.codClasseRisco desc,prioridade asc, idade desc'); // ALTERADO EM 15/09/22 PARA ORDENAR POR TEMPO - SOL TEN VALENÇA
		return $query->getResult();
	}
	public function pacientesUrgenciaEmergenciaAguardandoLeito()
	{
		$filtro = "";

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select a.codClasseRisco, cr.descricaoClasseRisco, cr.corClasseRisco, a.codAtendimento,pe.nomeExibicao as nomeEspeciaoista,a.codPaciente,pa.nomeCompleto, 
		TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade,
		CASE
			WHEN TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) < 7 THEN 0
			WHEN TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) > 60 THEN 1
			ELSE 2
		END prioridade,
		aa.queixaPrincipal,a.dataCriacao,d.descricaoDepartamento,la.codLocalAtendimento,la.descricaoLocalAtendimento,s.descricaoStatusAtendimento
		from  amb_atendimentos a 
		left join amb_atendimentosanamnese aa on aa.codAtendimento=a.codAtendimento
		left join amb_atendimentosstatus s on s.codStatusAtendimento=a.codStatus
		left join sis_pessoas pe on pe.codPessoa=a.codEspecialista
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente		
		left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento		
		left join amb_classificacaorisco cr on cr.codClasseRisco=a.codClasseRisco		
		where a.codOrganizacao=' . $codOrganizacao . ' and a.dataEncerramento is null and a.codTipoAtendimento in(1,4,5) and d.codDepartamento = 13  and a.codStatus in(16)
		order by a.dataCriacao asc, a.codClasseRisco desc,prioridade asc, idade desc'); // ALTERADO EM 15/09/22 PARA ORDENAR POR TEMPO - SOL TEN VALENÇA
		return $query->getResult();
	}

	public function pacientesAtendidosUrgenciaEmergencia()
	{


		$filtro = '';


		$dataInicio = session()->filtroAtendimentoUrgenciaEmergecia["dataInicio"];
		$dataEncerramento = session()->filtroAtendimentoUrgenciaEmergecia["dataEncerramento"];

		if ($dataInicio !== NULL and $dataInicio !== "") {

			$filtro .= ' and DATE_FORMAT(a.dataCriacao, "%Y-%m-%d") >="' . $dataInicio . '"';
		} else {
			$filtro .= ' and  DATE_FORMAT(a.dataCriacao, "%Y-%m-%d")  >= CURDATE()';
		}
		if ($dataEncerramento !== NULL and $dataEncerramento !== "") {
			$filtro .= ' and  DATE_FORMAT(a.dataCriacao, "%Y-%m-%d")  <= "' . $dataEncerramento . '"';
		}


		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select a.codClasseRisco,tb.siglaTipoBeneficiario, ca.siglaCargo, pa.codPlano, a.codAtendimento,pe.nomeExibicao as nomeEspeciaoista,a.codPaciente,pa.nomeCompleto, 
		TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade,
		aa.queixaPrincipal,a.dataCriacao,d.descricaoDepartamento,la.codLocalAtendimento,la.descricaoLocalAtendimento,s.descricaoStatusAtendimento
		from  amb_atendimentos a 
		left join amb_atendimentosanamnese aa on aa.codAtendimento=a.codAtendimento
		left join amb_atendimentosstatus s on s.codStatusAtendimento=a.codStatus
		left join sis_pessoas pe on pe.codPessoa=a.codEspecialista
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente
		left join sis_cargos ca on ca.codCargo=pa.codCargo		
		left join sis_tipobeneficiario tb on tb.codTipoBeneficiario=pa.codTipoBeneficiario		
		left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento			
		where a.codTipoAtendimento = 1 and a.codOrganizacao=' . $codOrganizacao . $filtro . ' 
		order by a.dataCriacao asc');
		return $query->getResult();
	}






	public function medicamentosPrescritosUrgenciaEmergencia()
	{


		$filtro = '';


		$dataInicio = session()->filtroMedicamentosPrescritosUrgenciaEmergecia["dataInicio"];
		$dataEncerramento = session()->filtroMedicamentosPrescritosUrgenciaEmergecia["dataEncerramento"];

		if ($dataInicio !== NULL and $dataInicio !== "") {

			$filtro .= ' and DATE_FORMAT(apm.dataCriacao, "%Y-%m-%d") >="' . $dataInicio . '"';
		} else {
			$filtro .= ' and  DATE_FORMAT(apm.dataCriacao, "%Y-%m-%d")  >= CURDATE()';
		}
		if ($dataEncerramento !== NULL and $dataEncerramento !== "") {
			$filtro .= ' and  DATE_FORMAT(apm.dataCriacao, "%Y-%m-%d")  <= "' . $dataEncerramento . '"';
		}


		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select i.descricaoItem, count(apm.total) as total FROM amb_atendimentosprescricoesmedicamentos apm
		join amb_atendimentosprescricoes ap on ap.codAtendimentoPrescricao=apm.codAtendimentoPrescricao
		join amb_atendimentos a on a.codAtendimento=ap.codAtendimento
		join  sis_itensfarmacia i on i.codItem=apm.codMedicamento
		where a.codTipoAtendimento = 1  and a.codOrganizacao=' . $codOrganizacao . $filtro . ' 
		group by i.descricaoItem
		order by total desc limit 100');
		return $query->getResult();
	}


	public function todosMedicamentosPrescritosUrgenciaEmergencia()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('
		select a.codAtendimento, i.descricaoItem as Medicamento,ifc.descricaoCategoria TipoMedicamento, apm.total as Total, p.nomeExibicao as Paciente, DATE_FORMAT(apm.dataCriacao, "%m") as Mes,DATE_FORMAT(apm.dataCriacao, "%Y") as Ano,DATE_FORMAT(apm.dataCriacao,"%b") as NomeMes, QUARTER(apm.dataCriacao) as Trimestre,WEEKOFYEAR(apm.dataCriacao) as Semana  
		FROM amb_atendimentosprescricoesmedicamentos apm
		join amb_atendimentosprescricoes ap on ap.codAtendimentoPrescricao=apm.codAtendimentoPrescricao
		join amb_atendimentos a on a.codAtendimento=ap.codAtendimento
		join sis_pacientes p on p.codPaciente=a.codPaciente
		join  sis_itensfarmacia i on i.codItem=apm.codMedicamento
		join sis_itensfarmaciacategoria ifc on ifc.codCategoria=i.codCategoria
		where a.codTipoAtendimento = 1 and ap.codStatus<>1  and a.codOrganizacao=' . $codOrganizacao . ' 
		order by apm.total desc');
		return $query->getResult();
	}



	public function totalPacientesUrgenciaEmergencia()
	{
		$filtro = "";

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select count(*) as total
		from  amb_atendimentos a 
		where a.codOrganizacao=' . $codOrganizacao . ' and a.dataCriacao >= ADDDATE(NOW(), INTERVAL -2 DAY) and a.codTipoAtendimento = 1 and  a.codStatus not in(2, 3, 8, 9, 11) and a.legado=0');
		return $query->getRow();
	}

	public function totalPacientesInternados()
	{
		$filtro = "";

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select count(*) as total
		from  amb_atendimentos a 		
		left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento
		where d.codDepartamento not in(89) and d.codTipoDepartamento=2 and a.codOrganizacao=' . $codOrganizacao . ' and a.codTipoAtendimento = 4 and a.dataEncerramento is null');
		return $query->getRow();
	}

	public function unidadesInternacao()
	{
		$filtro = "";

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select descricaoDepartamento
		from  sis_departamentos d 
		where d.codDepartamento not in(89) and d.codTipoDepartamento in (2,6) and d.ativo=1 order by descricaoDepartamento asc');
		return $query->getResult();
	}

	public function internados($descricaoDepartamento)
	{
		$filtro = "";

		$query = $this->db->query('
		select la.codSituacaoLocalAtendimento,als.descricaoSituacaoLocalAtendimento, a.codAtendimento,pa.nomeCompleto, TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade,
		a.dataCriacao,d.descricaoDepartamento,la.codLocalAtendimento,la.descricaoLocalAtendimento,s.descricaoStatusAtendimento
		from  amb_atendimentoslocais  la
		left join amb_atendimentos a on la.codAtendimento=a.codAtendimento 
		left join amb_atendimentoslocaissituacao als on als.codSituacaoLocalAtendimento=la.codSituacaoLocalAtendimento
		left join amb_atendimentosstatus s on s.codStatusAtendimento=a.codStatus
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente		
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento
		where la.codTipoLocalAtendimento=2 and la.codStatusLocalAtendimento =1 and la.codSituacaoLocalAtendimento not in(3) and d.descricaoDepartamento = "' . $descricaoDepartamento . '"	
		order by la.descricaoLocalAtendimento asc
		');
		return $query->getResult();
	}
	public function monitorPrevAlta($codAtendimento)
	{
		$filtro = "";

		$query = $this->db->query('select atp.*,a.dataEncerramento,a.codStatus,d.codTipoDepartamento
		from amb_atendimentos a
		left join amb_atendimentosprevalta atp on a.codAtendimento=atp.codAtendimento
        left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento
		where a.codAtendimento = "' . $codAtendimento . '" 
		order by atp.codPrevAlta desc limit 1');
		return $query->getRow();
	}
	public function totalPacientesInternadosOCS()
	{
		$filtro = "";

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select count(*) as total
		from  amb_atendimentos a 		
		left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento
		where d.codDepartamento not in(89)  and d.codTipoDepartamento=6 and a.codOrganizacao=' . $codOrganizacao . ' and a.codTipoAtendimento = 4 and a.dataEncerramento is null');
		return $query->getRow();
	}

	public function pegaNomeDepartamento($codDepartamento)
	{
		$query = $this->db->query('select * from sis_departamentos where codDepartamento=' . $codDepartamento);
		return $query->getRow();
	}
	public function sensoInternadosFarmacia()
	{
		$filtro = '';
		$codDepartamento = session()->filtroDispensacao["codDepartamento"];

		if ($codDepartamento !== 0 and $codDepartamento !== NULL and $codDepartamento !== '' and $codDepartamento !== ' ') {
			$filtro .= ' and la.codDepartamento=' . $codDepartamento;
		}
		$query = $this->db->query('select a.codAtendimento, abreviacaoDepartamento,descricaoLocalAtendimento,pa.cpf,pa.nomeCompleto,descricaoCargo, siglaTipoBeneficiario,TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade
		from  amb_atendimentos a 
		left join amb_atendimentosstatus s on s.codStatusAtendimento=a.codStatus
		left join sis_pessoas pe on pe.codPessoa=a.codEspecialista
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente
		left join sis_cargos ca on ca.codCargo=pa.codCargo		
		left join sis_tipobeneficiario tb on tb.codTipoBeneficiario=pa.codTipoBeneficiario		
		left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento
        where a.dataEncerramento is null and a.codTipoAtendimento=4 ' . $filtro . '
        order by abreviacaoDepartamento desc, descricaoLocalAtendimento asc');
		return $query->getResult();
	}

	public function sensoInternadosServicoSocial()
	{
		$query = $this->db->query('select a.codPaciente,a.codAtendimento, abreviacaoDepartamento,descricaoLocalAtendimento,pa.cpf,pa.codPlano,pa.nomeCompleto,siglaCargo, siglaTipoBeneficiario,TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade
		from  amb_atendimentos a 
		left join amb_atendimentosstatus s on s.codStatusAtendimento=a.codStatus
		left join sis_pessoas pe on pe.codPessoa=a.codEspecialista
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente
		left join sis_cargos ca on ca.codCargo=pa.codCargo		
		left join sis_tipobeneficiario tb on tb.codTipoBeneficiario=pa.codTipoBeneficiario		
		left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento
        where a.dataEncerramento is null and a.codTipoAtendimento=4
        order by abreviacaoDepartamento desc, descricaoLocalAtendimento asc');
		return $query->getResult();
	}

	public function dadosOcupacaoLeitos()
	{
		$dadosLeitos = array();


		$codOrganizacao = session()->codOrganizacao;
		if ($codOrganizacao == NULL) {

			$configuracao = config('App');
			session()->set('codOrganizacao', $configuracao->codOrganizacao);
			$codOrganizacao = $configuracao->codOrganizacao;
		}
		$query = $this->db->query('select count(*) as totalLeitosOcupados from amb_atendimentoslocais la
		join sis_departamentos d on d.codDepartamento=la.codDepartamento
		WHERE d.ativo=1 and la.codOrganizacao =' . $codOrganizacao . ' and d.codTipoDepartamento not in (6) and d.abreviacaoDepartamento not like "%SAD%" and d.abreviacaoDepartamento not like "%PAMO%" and la.codStatusLocalAtendimento =1 and la.codSituacaoLocalAtendimento not in(3) and la.codTipoLocalAtendimento = 2 and la.codAtendimento is not null');
		$dadosLeitos['totalLeitosOcupados'] = $query->getRow()->totalLeitosOcupados;


		$query = $this->db->query('select count(*) as totalLeitos from amb_atendimentoslocais la
		join sis_departamentos d on d.codDepartamento=la.codDepartamento
		WHERE d.ativo=1 and la.codOrganizacao =' . $codOrganizacao . ' and d.codTipoDepartamento not in (6) and d.abreviacaoDepartamento not like "%SAD%" and d.abreviacaoDepartamento not like "%PAMO%" and la.codStatusLocalAtendimento = 1 and la.codTipoLocalAtendimento = 2  and la.codSituacaoLocalAtendimento not in(3)');
		$dadosLeitos['totalLeitos']  = $query->getRow()->totalLeitos;


		$query = $this->db->query('select count(*) as totalLeitosEmManutencao 
		from amb_atendimentoslocais la 
		join sis_departamentos d on d.codDepartamento=la.codDepartamento 
		WHERE d.ativo=1 and la.codOrganizacao =' . $codOrganizacao . ' and d.codTipoDepartamento not in (6) and d.abreviacaoDepartamento not like "%SAD%" and d.abreviacaoDepartamento not like "%PAMO%" and la.codStatusLocalAtendimento = 1 and la.codTipoLocalAtendimento = 2 and la.codSituacaoLocalAtendimento=2');
		$dadosLeitos['totalLeitosEmManutencao'] = $query->getRow()->totalLeitosEmManutencao;


		return $dadosLeitos;
	}

	public function dadosIniciaAtendimento($codAtendimento)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select pe.nomeExibicao as chamador, la.descricaoLocalAtendimento, d.descricaoDepartamento, la.codAtendimento as codAtendimentoLeito, cue.localAtendimento as localChamada,cue.codChamador, a.*, pa.codProntuario,c.descricaoCargo,c.siglaCargo,tb.siglaTipoBeneficiario,tb.nomeTipoBeneficiario,pa.codPlano,pa.nomeCompleto,
		TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade, TIMESTAMPDIFF(MINUTE, cue.dataChamada,NOW()) as ultimaChamada
		from  amb_atendimentos a 
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente
		left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento
		left join amb_chamadasfilaurgenciaemergencia cue on cue.codPaciente=a.codPaciente		
		left join sis_pessoas pe on pe.codPessoa=cue.codChamador
		left join sis_tipobeneficiario tb on tb.codTipoBeneficiario=pa.codTipoBeneficiario
		left join sis_cargos c on c.codCargo=pa.codCargo and c.codOrganizacao=pa.codOrganizacao 
		where a.codOrganizacao=' . $codOrganizacao . ' and  a.codAtendimento="' . $codAtendimento . '" order by cue.dataChamada desc  limit 1');
		return $query->getRow();
	}

	public function listaDropDownTiposAtendimentos()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select codTipoAtendimento as id, descricaoTipoAtendimento as text from amb_atendimentostipos');
		return $query->getResult();
	}

	public function dadosAtendimento($codAtendimento)
	{
		$filtro = "";

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select a.codTipoAtendimento, a.codLocalAtendimento,a.codEspecialista, a.codAtendimento, pa.codPaciente, aa.hda, d.codCid, s.descricaoStatusAtendimento,pa.codProntuario,pa.nomeCompleto, TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade
		from  amb_atendimentos a 
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente	
		left join amb_atendimentosanamnese aa on aa.codAtendimento=a.codAtendimento
		left join amb_atendimentosdiagnostico d on d.codAtendimento=a.codAtendimento
		left join amb_atendimentosstatus s on s.codStatusAtendimento=a.codStatus
		where a.codOrganizacao=' . $codOrganizacao . ' and a.codAtendimento=' . $codAtendimento . ' limit 1');
		return $query->getRow();
	}

	public function dadosLeito($codLocalAtendimento = NULL)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select *
		from amb_atendimentoslocais l
		where l.codLocalAtendimento="' . $codLocalAtendimento . '"');
		return $query->getRow();
	}


	public function setaLeitoAtendimento($codLocalAtendimento = NULL, $codAtendimento = NULL)
	{

		$query = $this->db->query('update amb_atendimentoslocais
		set codAtendimento="' . $codAtendimento . '" 
		where codLocalAtendimento="' . $codLocalAtendimento . '"');
		return true;
	}


	public function dadosEtiquetaAtendimento($codAtendimento)
	{
		$filtro = "";

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select a.codEspecialista, a.codAtendimento, pa.codPaciente,pa.codProntuario,pa.nomeCompleto, TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade,pa.sexo,pa.codPlano, pa.nomeMae
		from  amb_atendimentos a 
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente	
		where a.codOrganizacao=' . $codOrganizacao . ' and a.codAtendimento=' . $codAtendimento . ' limit 1');
		return $query->getRow();
	}



	public function listaAtendimentosPorPaciente($codPaciente)
	{
		$query = $this->db->query('select * 
		from amb_atendimentos a
		where a.codPaciente = "' . $codPaciente . '" order by a.dataCriacao desc');
		return $query->getResult();
	}

	public function listaAtendimentosCodAtendimento($codAtendimento)
	{
		$query = $this->db->query('select * 
		from amb_atendimentos a
		where a.codAtendimento = "' . $codAtendimento . '" order by a.codAtendimento desc');
		return $query->getResult();
	}



	public function dadosAtendimentoCompleto($codAtendimento)
	{
		$filtro = "";

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select pa.nomeMae as nomeMaePaciente,pa.validade as dataValidade,pa.dataNascimento as dataNascimentoPaciente,pa.codProntuario as codProntuarioPaciente,pa.codPlano as codPlanoPaciente,pa.cpf as cpfPaciente,pa.sexo as sexoPaciente,ccc.descricaoCargo as descricaoCargoPaciente,ccc.siglaCargo as siglaCargoPaciente,tb.nomeTipoBeneficiario,tb.siglaTipoBeneficiario,pa.nomeExibicao as nomeExibicaoPaciente,a.*,cc.nomeConselho, em.numeroInscricao,ef.siglaEstadoFederacao, e.descricaoEspecialidade, t.descricaoTipoAtendimento,pe.codCargo, c.siglaCargo,pe.nomeExibicao as nomeEspecialista,pe.nomeCompleto as nomeCompletoEspecialista, concat(dd.descricaoDepartamento," - ",la.descricaoLocalAtendimento) as localAtendimento,pa.codPaciente, aa.hda, d.codCid, s.descricaoStatusAtendimento,pa.codProntuario,pa.nomeCompleto, TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade
		from  amb_atendimentos a 
		left join amb_atendimentosanamnese aa on aa.codAtendimento=a.codAtendimento
		left join amb_atendimentosdiagnostico d on d.codAtendimento=a.codAtendimento
		left join amb_atendimentosstatus s on s.codStatusAtendimento=a.codStatus
		left join amb_atendimentostipos t on t.codTipoAtendimento=a.codTipoAtendimento
		left join sis_pessoas pe on pe.codPessoa=a.codEspecialista
		left join sis_especialidadesmembros em on pe.codPessoa= em.codPessoa	
		left join sis_estadosfederacao ef on ef.codEstadoFederacao= em.codEstadoFederacao	
		left join sis_especialidades e on e.codEspecialidade=em.codEspecialidade
		left join sis_conselhos cc on cc.codConselho=e.codConselho
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente
		left join sis_cargos c on c.codCargo=pe.codCargo and c.codOrganizacao=pe.codOrganizacao	
		left join sis_tipobeneficiario tb on tb.codTipoBeneficiario=pa.codTipoBeneficiario
		left join sis_cargos ccc on ccc.codCargo=pa.codCargo and ccc.codOrganizacao=pa.codOrganizacao  
		left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos dd on dd.codDepartamento=la.codDepartamento		
		where a.codOrganizacao=' . $codOrganizacao . ' and a.codAtendimento=' . $codAtendimento . ' limit 1');
		return $query->getRow();
	}

	public function dadosDiagnosticosAtendimento($codAtendimento)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select *
		from amb_atendimentosdiagnostico ad 	 
		left join amb_cid10 c on ad.codCid=c.codCid	
		where ad.codAtendimento=' . $codAtendimento . ' order by ad.codTipoDiagnostico asc, ad.codAtendimentoDiagnostico asc');
		return $query->getResult();
	}


	public function dadosCondutasAtendimento($codAtendimento)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select ac.*,cc.nomeConselho, em.numeroInscricao,ef.siglaEstadoFederacao, e.descricaoEspecialidade,pe.nomeExibicao as nomeEspecialista,pe.nomeCompleto as nomeCompletoEspecialista,pe.nomeCompleto,c.codCargo,c.siglaCargo
		from amb_atendimentos a 
		left join amb_atendimentoscondutas ac on a.codAtendimento=ac.codAtendimento
		left join sis_pessoas pe on pe.codPessoa=ac.codAutor
		left join sis_especialidadesmembros em on pe.codPessoa= em.codPessoa and pe.codEspecialidade=em.codEspecialidade	
		left join sis_estadosfederacao ef on ef.codEstadoFederacao= em.codEstadoFederacao	
		left join sis_especialidades e on e.codEspecialidade=em.codEspecialidade
		left join sis_conselhos cc on cc.codConselho=e.codConselho
		left join sis_cargos c on c.codCargo=pe.codCargo and c.codOrganizacao=pe.codOrganizacao  
		where ac.codAtendimento=' . $codAtendimento . ' order by ac.dataCriacao desc');
		return $query->getResult();
	}

	public function dadosEvolucoesAtendimento($codAtendimento)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select  ae.*,cc.nomeConselho, em.numeroInscricao,ef.siglaEstadoFederacao, e.descricaoEspecialidade,pe.nomeExibicao as nomeEspecialista,pe.nomeCompleto as nomeCompletoEspecialista,pe.nomeCompleto,c.siglaCargo
		from amb_atendimentos a 
		left join amb_atendimentosevolucoes ae on a.codAtendimento=ae.codAtendimento
		left join sis_pessoas pe on pe.codPessoa=ae.codAutor
		left join sis_especialidadesmembros em on pe.codPessoa= em.codPessoa  and pe.codEspecialidade=em.codEspecialidade	
		left join sis_estadosfederacao ef on ef.codEstadoFederacao= em.codEstadoFederacao	
		left join sis_especialidades e on e.codEspecialidade=em.codEspecialidade
		left join sis_conselhos cc on cc.codConselho=e.codConselho
		left join sis_cargos c on c.codCargo=pe.codCargo and c.codOrganizacao=pe.codOrganizacao  
		where ae.codAtendimento=' . $codAtendimento . ' order by ae.dataCriacao desc');
		return $query->getResult();
	}
	public function dadosPareceresAtendimento($codAtendimento)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select  ae.*,cc.nomeConselho, em.numeroInscricao,ef.siglaEstadoFederacao, e.descricaoEspecialidade,pe.nomeExibicao as nomeEspecialista,pe.nomeCompleto as nomeCompletoEspecialista,pe.nomeCompleto,c.siglaCargo
		from amb_atendimentos a 
		left join amb_atendimentospareceres ae on a.codAtendimento=ae.codAtendimento
		left join sis_pessoas pe on pe.codPessoa=ae.codAutor
		left join sis_especialidadesmembros em on pe.codPessoa= em.codPessoa  and pe.codEspecialidade=em.codEspecialidade	
		left join sis_estadosfederacao ef on ef.codEstadoFederacao= em.codEstadoFederacao	
		left join sis_especialidades e on e.codEspecialidade=em.codEspecialidade
		left join sis_conselhos cc on cc.codConselho=e.codConselho
		left join sis_cargos c on c.codCargo=pe.codCargo and c.codOrganizacao=pe.codOrganizacao  
		where ae.codAtendimento=' . $codAtendimento . ' order by ae.dataCriacao desc');
		return $query->getResult();
	}

	public function pegaAtendimentosPorCodPaciente($codPaciente, $codTipoAtendimento, $codStatusAtendimento)
	{
		$filtro = "";

		if ($codTipoAtendimento !== NULL and $codTipoAtendimento !== "") {
			$filtro = ' and a.codTipoAtendimento=' . $codTipoAtendimento;
		}

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select a.*, t.descricaoTipoAtendimento, s.descricaoStatusAtendimento,e.descricaoEspecialidade,pe.nomeExibicao as nomeEspecialista,pa.nomeExibicao as nomePaciente,d.descricaoDepartamento,la.descricaoLocalAtendimento
		from  amb_atendimentos a 
		left join sis_especialidades e on e.codEspecialidade=a.codEspecialidade
		left join sis_pessoas pe on pe.codPessoa=a.codEspecialista
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente
		left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento
		left join amb_atendimentosstatus s on s.codStatusAtendimento=a.codStatus
		left join amb_atendimentostipos t on t.codTipoAtendimento=a.codTipoAtendimento
		where a.codStatus = ' . $codStatusAtendimento . ' and a.codOrganizacao=' . $codOrganizacao . ' and a.codPaciente=' . $codPaciente . ' ' . $filtro);
		return $query->getResult();
	}



	public function atendimento($codPaciente)
	{
		$filtro = "";

		$codEspecialidade = session()->filtroEspecialidadeFiltroProntuario;
		$codTipoAtendimento = session()->filtroTipoAtendimentoFiltroProntuario;


		if ($codEspecialidade !== NULL and $codEspecialidade !== "") {
			$filtro .= " and a.codEspecialidade=" . $codEspecialidade;
		}

		if ($codTipoAtendimento !== NULL and $codTipoAtendimento !== "") {
			$filtro .= " and a.codTipoAtendimento=" . $codTipoAtendimento;
		}



		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select a.*,aa.hda,aa.queixaPrincipal, t.descricaoTipoAtendimento, s.descricaoStatusAtendimento,e.descricaoEspecialidade,pe.nomeExibicao as nomeEspecialista,pa.nomeExibicao as nomePaciente,d.descricaoDepartamento,la.descricaoLocalAtendimento
		from  amb_atendimentos a 
		left join amb_atendimentosanamnese aa on aa.codAtendimento=a.codAtendimento
		left join sis_especialidades e on e.codEspecialidade=a.codEspecialidade
		left join sis_pessoas pe on pe.codPessoa=a.codEspecialista
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente
		left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento
		left join amb_atendimentosstatus s on s.codStatusAtendimento=a.codStatus
		left join amb_atendimentostipos t on t.codTipoAtendimento=a.codTipoAtendimento
		where a.codTipoAtendimento not in(7) and  a.codOrganizacao=' . $codOrganizacao . ' and a.codPaciente=' . $codPaciente . ' ' . $filtro . ' order by a.dataInicio desc');
		return $query->getResult();
	}

	public function emAtendimento($codPaciente)
	{
		$filtro = "";

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select a.*,aa.hda,aa.queixaPrincipal, t.descricaoTipoAtendimento, s.descricaoStatusAtendimento,e.descricaoEspecialidade,pe.nomeExibicao as nomeEspecialista,pa.nomeExibicao as nomePaciente,d.descricaoDepartamento,la.descricaoLocalAtendimento
		from  amb_atendimentos a 
		left join amb_atendimentosanamnese aa on aa.codAtendimento=a.codAtendimento
		left join sis_especialidades e on e.codEspecialidade=a.codEspecialidade
		left join sis_pessoas pe on pe.codPessoa=a.codEspecialista
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente
		left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento
		left join amb_atendimentosstatus s on s.codStatusAtendimento=a.codStatus
		left join amb_atendimentostipos t on t.codTipoAtendimento=a.codTipoAtendimento
		where a.codStatus not in(2,3,8,9) and a.codOrganizacao=' . $codOrganizacao . ' and a.codPaciente=' . $codPaciente . ' ' . $filtro);
		return $query->getResult();
	}
}
