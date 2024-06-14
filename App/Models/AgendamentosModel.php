<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class AgendamentosModel extends Model
{

	protected $table = 'amb_agendamentos';
	protected $primaryKey = 'codAgendamento';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['dataMarcacao', 'horaChegada', 'inicioAtendimento', 'encerramentoAtendimento', 'confirmou', 'chegou', 'codConfig', 'codOrganizacao', 'codPaciente', 'codEspecialista', 'codEspecialidade', 'codStatus', 'dataCriacao', 'dataAtualizacao', 'dataInicio', 'dataEncerramento', 'codAutor', 'protocolo', 'ordemAtendimento', 'marcadoPor'];
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
		$query = $this->db->query('select * from amb_agendamentos');
		return $query->getResult();
	}

	public function verificaExistenciaAgendamento($codEspecialidade, $codEspecialista, $dataInicio)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from amb_agendamentos where codEspecialidade=' . $codEspecialidade . ' and codEspecialista=' . $codEspecialista . ' and dataInicio="' . $dataInicio . '"');
		return $query->getRow();
	}
	public function verificaExistenciaAgendamentoSIGH($codEspecialidade, $codEspecialista, $dataInicio)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from amb_agendamentos where codEspecialidade="' . $codEspecialidade . '" and codEspecialista="' . $codEspecialista . '" and dataInicio="' . $dataInicio . '"');
		return $query->getRow();
	}
	public function outrosContatosPorPaciente($codPaciente)
	{
		$query = $this->db->query('select * from sis_outroscontatos where codPaciente = ' . $codPaciente);
		return $query->getResult();
	}

	public function pegaPessoa($codPessoa = NULL, $codEspecialidade = NULL)
	{
		if ($codEspecialidade == NULL) {
			$query = $this->db->query('select p.*,NULL as idadeMinima, NULL as idadeMaxima, NULL as descricaoFaixaEtaria from sis_pessoas p where p.codPessoa = ' . $codPessoa);
		} else {
			$query = $this->db->query('select * from sis_pessoas p
			left join sis_especialidadesmembros em on em.codPessoa = p.codPessoa and em.codEspecialidade="' . $codEspecialidade . '"
			left join sis_faixasetarias fe on fe.codFaixaEtaria = em.codFaixaEtaria
			where p.codPessoa = ' . $codPessoa);
		}
		return $query->getRow();
	}

	public function pegaPorCodigo($codAgendamento)
	{
		$codOrganicacao = session()->codOrganizacao;
		$query = $this->db->query('select em.codFaixaEtaria,fe.idadeMinima,fe.idadeMaxima, fe.descricaoFaixaEtaria,c.descricaoCargo,c.siglaCargo,tb.nomeTipoBeneficiario,tb.siglaTipoBeneficiario, TIMESTAMPDIFF (YEAR, pa.dataNascimento,CURDATE()) as idade, pa.nomeCompleto, pa.codProntuario,pa.codPlano,a.*,e.descricaoEspecialidade,p.nomeExibicao,d.descricaoDepartamento,ac.codTipoAgendamento
		from amb_agendamentos a
		left join amb_agendamentosconfig ac on ac.codConfig = a.codConfig
		left join sis_especialidades e on e.codEspecialidade = a.codEspecialidade
		left join sis_especialidadesmembros em on em.codPessoa = a.codEspecialista and em.codEspecialidade=e.codEspecialidade
		left join sis_departamentos d on d.codDepartamento = ac.codLocal
		left join sis_faixasetarias fe on fe.codFaixaEtaria = em.codFaixaEtaria
		left join sis_pessoas p on p.codPessoa = a.codEspecialista
		left join sis_pacientes pa on pa.codPaciente = a.codPaciente
		left join sis_tipobeneficiario tb on tb.codTipoBeneficiario=pa.codTipoBeneficiario
		left join sis_cargos c on c.codCargo=pa.codCargo and c.codOrganizacao=pa.codOrganizacao  
		 where a.codOrganizacao =' . $codOrganicacao . ' and a.codAgendamento = "' . $codAgendamento . '"');
		return $query->getRow();
	}


	public function pegaAgendamentoPorEspecialidadePacienteHoje($paciente, $codEspecialista)
	{
		$codOrganicacao = session()->codOrganizacao;
		$query = $this->db->query('select *
		from amb_agendamentos a
		where a.codOrganizacao =' . $codOrganicacao . ' and a.codPaciente = "' . $paciente . '" and a.codEspecialista = "' . $codEspecialista . '" and DATE_FORMAT(a.dataInicio, "%Y-%m-%d") = CURDATE()');
		return $query->getRow();
	}

	public function proximasConsultas($codPaciente)
	{
		$codOrganicacao = session()->codOrganizacao;
		$query = $this->db->query('select ass.*,a.codAgendamento, e.descricaoEspecialidade,c.siglaCargo,p.nomeExibicao, "Consulta" as Tipo , a.dataInicio
		from amb_agendamentos a
		left join sis_especialidades e on e.codEspecialidade = a.codEspecialidade
		left join amb_agendamentosstatus ass on ass.codStatus=a.codStatus
		left join sis_pessoas p on p.codPessoa = a.codEspecialista
		left join sis_pacientes pa on pa.codPaciente = a.codPaciente
		left join sis_cargos c on c.codCargo=p.codCargo and c.codOrganizacao=p.codOrganizacao  
		where a.dataInicio > NOW()  and a.codOrganizacao =' . $codOrganicacao . ' and a.codPaciente = "' . $codPaciente . '" and a.encerramentoAtendimento is null order by a.dataInicio desc limit 3');
		return $query->getResult();
	}
	
	public function tentativasPorPaciente($prec = NULL)
	{
		$codOrganicacao = session()->codOrganizacao;
		$query = $this->db->query('select count(dia) as dias,p.nomeExibicao,p.cpf,p.codplano,e.descricaoEspecialidade
		from (select distinct codPaciente,codEspecialidade, DATE_FORMAT(dataPesquisa, "%Y-%m-%d") dia 
		FROM logs_pesquisavagas
		where DATE_FORMAT(dataPesquisa, "%Y-%m-%d")  > ADDDATE(NOW(), INTERVAL -30 DAY)
		)x            
		join sis_pacientes p on p.codPaciente=x.codPaciente
		join sis_especialidades e on e.codEspecialidade=x.codEspecialidade
		where p.cpf ="' . $prec . '" or p.codPlano ="' . $prec . '"
		group by x.codPaciente,x.codEspecialidade
		ORDER BY dias desc');
		return $query->getResult();
	}


	public function comprovante($codAgendamento)
	{
		$codOrganicacao = session()->codOrganizacao;
		$query = $this->db->query('select a.*,pp.nomeExibicao as autorMarcacao,e.descricaoEspecialidade,d.descricaoDepartamento,a.codEspecialidade,
		p.nomeExibicao as nomeEspecialista,pa.nomeCompleto,pa.codPaciente,
		pa.nomeCompleto as nomePaciente, a.protocolo,pa.codProntuario,pa.codPlano
		from amb_agendamentos a
		left join amb_agendamentosconfig ac on ac.codConfig=a.codConfig
		left join sis_especialidades e on e.codEspecialidade = a.codEspecialidade
		left join sis_pessoas p on p.codPessoa = a.codEspecialista
		left join sis_pessoas pp on a.marcadoPor=pp.codPessoa	
		left join sis_pacientes pa on pa.codPaciente = a.codPaciente
		left join sis_departamentos d on d.codDepartamento = ac.codLocal
		where a.codAgendamento = ' . $codAgendamento . ' and a.codOrganizacao=' . $codOrganicacao);
		return $query->getRow();
	}



	public function dashboard()
	{
		$codOrganicacao = session()->codOrganizacao;
		$query = $this->db->query('select att.nomeTipo as tipoAgenda, p.nomeExibicao as especialista,e.descricaoEspecialidade as especialidade,ags.nomeStatus as statusAgenda, DATE_FORMAT(a.dataInicio, "%m") as Mes,DATE_FORMAT(a.dataInicio, "%Y") as Ano,DATE_FORMAT(a.dataInicio,"%b") as NomeMes, QUARTER(a.dataInicio) as Trimestre,WEEKOFYEAR(a.dataInicio) as Semana
		from amb_agendamentos a
        left join amb_agendamentosstatus ags on ags.codStatus=a.codStatus
		left join amb_agendamentosconfig ac on ac.codConfig=a.codConfig
        left join amb_agendamentostipo att on att.codTipo=ac.codTipoAgendamento
		left join sis_especialidades e on e.codEspecialidade = a.codEspecialidade
		left join sis_pessoas p on p.codPessoa = a.codEspecialista
		left join sis_pessoas pp on a.marcadoPor=pp.codPessoa	
		left join sis_pacientes pa on pa.codPaciente = a.codPaciente
		left join sis_departamentos d on d.codDepartamento = ac.codLocal      
		where ac.codTipoAgendamento <> 0 and a.codStatus not in(0,4) and a.codOrganizacao=' . $codOrganicacao . '   order by a.dataInicio asc, pp.codCargo');
		return $query->getResult();
	}


	public function verificaCadastroReserva($codEspecialidade)
	{
		$query = $this->db->query('select *
		from sis_especialidades where codEspecialidade=' . $codEspecialidade);
		return $query->getRow();
	}

	public function verificatentativasPaciente($codPaciente, $codEspecialidade)
	{
		$query = $this->db->query('
		select count(dia) as dias,codPaciente,codEspecialidade from (
			select distinct codPaciente,codEspecialidade, DATE_FORMAT(dataPesquisa, "%Y-%m-%d") dia FROM logs_pesquisavagas
			where DATE_FORMAT(dataPesquisa, "%Y-%m-%d")  > ADDDATE(NOW(), INTERVAL -30 DAY)
			)x WHERE codPaciente=' . $codPaciente . '  and codEspecialidade=' . $codEspecialidade . ' 
			group by codPaciente,codEspecialidade');
		return $query->getRow();
	}


	public function ultimasAgendasAbertasInternet($codEspecialidade = NULL)
	{

		$query = $this->db->query('select att.codTipo,att.nomeTipo, max(ac.dataLiberacao) as ultimaLiberacao,sum(totalVagas) as totalVagas 
		from amb_agendamentosconfig ac
		left join amb_agendamentostipo att on att.codTipo=ac.codTipoAgendamento
		where ac.codTipoAgendamento = 1 and ac.codEspecialidade="' . $codEspecialidade . '" and ac.codStatusAgendamento=1 and ac.dataLiberacao >= DATE_SUB(NOW(), INTERVAL 15 DAY)
		group by ac.codEspecialidade,att.nomeTipo');
		return $query->getResult();
	}

	public function qtdBeneficiariosTentandoMarcar($codEspecialidade = NULL)
	{

		$query = $this->db->query('select count(codPaciente) as totalBeneficiarios from 
		(select distinct codPaciente from logs_pesquisavagas 
		where codEspecialidade="' . $codEspecialidade . '" and dataPesquisa >= DATE_SUB(NOW(), INTERVAL 20 DAY))x');
		return $query->getRow();
	}


	public function agendamentosPorEspecialidade()
	{

		$codEspecialidade = session()->filtroEspecialidade["codEspecialidade"];
		$codEspecialista = session()->filtroEspecialidade["codEspecialista"];
		$dataInicio = session()->filtroEspecialidade["dataInicio"];
		$dataEncerramento = session()->filtroEspecialidade["dataEncerramento"];

		if ($codEspecialidade == NULL) {
			return 'noEspecialidade';
		}


		if ($codEspecialidade !== NULL and $codEspecialidade !== "") {
			$filtro = ' a.codEspecialidade = ' . $codEspecialidade . '';
		}
		if ($codEspecialista !== NULL and $codEspecialista !== "null" and $codEspecialista !== "0") {
			$filtro .= ' and a.codEspecialista = ' . $codEspecialista;
		}
		if ($dataInicio !== NULL and $dataInicio !== "") {

			if ($dataInicio < date('Y-m-d')) {
				//$filtro .= ' and DATE_FORMAT(a.dataInicio, "%Y-%m-%d") >="' . date('Y-m-d') . '"';	//Alterado em 28/07/2022			
				$filtro .= ' and a.dataInicio >= NOW()';
			} else {
				$filtro .= ' and a.dataInicio >="' . $dataInicio . '"';
			}
		} else {
			//$filtro .= ' and DATE_FORMAT(a.dataInicio, "%Y-%m-%d") >="' . date('Y-m-d') . '"';	//Alterado em 28/07/2022			
			$filtro .= ' and a.dataInicio  >= NOW()';
		}
		if ($dataEncerramento !== NULL and $dataEncerramento !== "") {
			$filtro .= ' and  DATE_FORMAT(a.dataEncerramento, "%Y-%m-%d")  <= "' . $dataEncerramento . '"';
		}

		//FILTRAR PACIENTE PARA VER SÓ VAGAS DA INTERNET
		if (session()->codPaciente !== NULL and session()->codPaciente > 0) {
			$filtro .= ' and ac.codTipoAgendamento=1'; //TIPO INTERNET
		} else {
			//SE É O ESPECIALISTA, SÓ VER RETORNOS DELE
			if (!empty(session()->minhasEspecialidades)) {
				$filtro .= ' and (ac.codTipoAgendamento in(8,7)) or (ac.codTipoAgendamento in(2) and a.codEspecialista="' . session()->codPessoa . '" and a.dataInicio  >= NOW())'; //TIPO RETORNO
			}
		}


		$query = $this->db->query('select ass.*,a.*,e.*,agt.*,d.descricaoDepartamento,pa.codPlano,pa.codProntuario,p.nomeExibicao as nomeEspecialista,pa.nomeExibicao as nomePaciente,pa.fotoPerfil,p.codPessoa as codEspecialista,p.fotoPerfil as fotoPerfilEspecialista,pa.cpf, pa.celular,pa.emailPessoal
		from amb_agendamentos a 
		left join amb_agendamentosconfig ac on ac.codConfig=a.codConfig
		left join amb_agendamentostipo agt on agt.codTipo=ac.codTipoAgendamento
		left join amb_agendamentosstatus ass on ass.codStatus=a.codStatus
		left join sis_especialidades e on e.codEspecialidade=a.codEspecialidade
		left join sis_pessoas p on a.codEspecialista=p.codPessoa		
		left join sis_pacientes pa on a.codPaciente=pa.codPaciente
		left join sis_departamentos d on d.codDepartamento = ac.codLocal		
		where (ac.codStatusAgendamento=1 or ac.codStatusAgendamento is null) and a.codPaciente = 0  and a.codStatus = 0  and a.dataAtualizacao <= NOW()  and a.dataInicio > date_add(NOW(),interval -60 minute)  and ' . $filtro . ' order by a.dataInicio asc');
		return $query->getResult();
	}



	public function remarcacaoPorEspecialidade($codEspecialidade)
	{

		$codEspecialidade = $codEspecialidade;
		$codEspecialista = null;
		$dataInicio = session()->filtroEspecialidade["dataInicio"];
		$dataEncerramento = session()->filtroEspecialidade["dataEncerramento"];

		if ($codEspecialidade == NULL) {
			return 'noEspecialidade';
		}


		if ($codEspecialidade !== NULL and $codEspecialidade !== "") {
			$filtro = ' a.codEspecialidade = ' . $codEspecialidade . '';
		}
		if ($codEspecialista !== NULL and $codEspecialista !== "null" and $codEspecialista !== "0") {
			$filtro .= ' and a.codEspecialista = ' . $codEspecialista;
		}
		if ($dataInicio !== NULL and $dataInicio !== "") {

			if ($dataInicio < date('Y-m-d')) {
				$filtro .= ' and DATE_FORMAT(a.dataInicio, "%Y-%m-%d") >="' . date('Y-m-d') . '"';
			} else {
				$filtro .= ' and DATE_FORMAT(a.dataInicio, "%Y-%m-%d") >="' . $dataInicio . '"';
			}
		} else {
			$filtro .= ' and DATE_FORMAT(a.dataInicio, "%Y-%m-%d") >="' . date('Y-m-d') . '"';
		}
		if ($dataEncerramento !== NULL and $dataEncerramento !== "") {
			$filtro .= ' and  DATE_FORMAT(a.dataEncerramento, "%Y-%m-%d")  <= "' . $dataEncerramento . '"';
		}

		//FILTRAR PACIENTE PARA VER SÓ VAGAS DA INTERNET
		if (session()->codPaciente !== NULL and session()->codPaciente > 0) {
			$filtro .= ' and ac.codTipoAgendamento=1'; //TIPO INTERNET
		} else {
			//SE É O ESPECIALISTA, SÓ VER RETORNOS
			if (!empty(session()->minhasEspecialidades)) {
				$filtro .= ' and ac.codTipoAgendamento=2'; //TIPO RETORNO
			} else {
				//$filtro .= ' and (ac.codTipoAgendamento in (3,4) or a.codConfig=0)'; //TIPO INTERNET
			}
		}


		$query = $this->db->query('select ass.*,a.*,e.*,agt.*,d.descricaoDepartamento,pa.codPlano,pa.codProntuario,p.nomeExibicao as nomeEspecialista,pa.nomeExibicao as nomePaciente,pa.fotoPerfil,p.codPessoa as codEspecialista,p.fotoPerfil as fotoPerfilEspecialista,pa.cpf, pa.celular,pa.emailPessoal
		from amb_agendamentos a 
		left join amb_agendamentosconfig ac on ac.codConfig=a.codConfig
		left join amb_agendamentostipo agt on agt.codTipo=ac.codTipoAgendamento
		left join amb_agendamentosstatus ass on ass.codStatus=a.codStatus
		left join sis_especialidades e on e.codEspecialidade=a.codEspecialidade
		left join sis_pessoas p on a.codEspecialista=p.codPessoa		
		left join sis_pacientes pa on a.codPaciente=pa.codPaciente
		left join sis_departamentos d on d.codDepartamento = ac.codLocal		
		where (ac.codStatusAgendamento=1 or ac.codStatusAgendamento is null) and a.codPaciente = 0  and a.codStatus = 0  and a.dataAtualizacao <= NOW()  and a.dataInicio > date_add(NOW(),interval -120 minute)  and ' . $filtro . ' order by a.dataInicio asc');
		return $query->getResult();
	}


	public function cancelamentosAgendamentosPorEspecialidade()
	{

		$codOrganizacao = session()->codOrganizacao;
		$codEspecialidade = session()->filtroEspecialidade["codEspecialidade"];
		$codEspecialista = session()->filtroEspecialidade["codEspecialista"];
		$dataInicio = session()->filtroEspecialidade["dataInicio"];
		$dataEncerramento = session()->filtroEspecialidade["dataEncerramento"];

		if ($codEspecialidade == NULL) {
			return 'noEspecialidade';
		}


		if ($codEspecialidade !== NULL and $codEspecialidade !== "") {
			$filtro = ' a.codEspecialidade = ' . $codEspecialidade . '';
		}
		if ($codEspecialista !== NULL and $codEspecialista !== "null" and $codEspecialista !== "0") {
			$filtro .= ' and a.codEspecialista = ' . $codEspecialista;
		}
		if ($dataInicio !== NULL and $dataInicio !== "") {

			if ($dataInicio < date('Y-m-d')) {
				$filtro .= ' and DATE_FORMAT(a.dataInicio, "%Y-%m-%d") >="' . date('Y-m-d') . '"';
			} else {
				$filtro .= ' and DATE_FORMAT(a.dataInicio, "%Y-%m-%d") >="' . $dataInicio . '"';
			}
		} else {
			$filtro .= ' and DATE_FORMAT(a.dataInicio, "%Y-%m-%d") >="' . date('Y-m-d') . '"';
		}
		if ($dataEncerramento !== NULL and $dataEncerramento !== "") {
			$filtro .= ' and  DATE_FORMAT(a.dataEncerramento, "%Y-%m-%d")  <= "' . $dataEncerramento . '"';
		}


		$query = $this->db->query('select ass.*,a.*,e.*,agt.*,d.descricaoDepartamento,pa.codPlano,pa.codProntuario,p.nomeExibicao as nomeEspecialista,pa.nomeExibicao as nomePaciente,pa.fotoPerfil,p.codPessoa as codEspecialista,p.fotoPerfil as fotoPerfilEspecialista,pa.cpf, pa.celular,pa.emailPessoal
		from amb_agendamentos a 
		left join amb_agendamentosconfig ac on ac.codConfig=a.codConfig
		left join amb_agendamentostipo agt on agt.codTipo=ac.codTipoAgendamento
		left join amb_agendamentosstatus ass on ass.codStatus=a.codStatus
		left join sis_especialidades e on e.codEspecialidade=a.codEspecialidade
		left join sis_pessoas p on a.codEspecialista=p.codPessoa		
		left join sis_pacientes pa on a.codPaciente=pa.codPaciente
		left join sis_departamentos d on d.codDepartamento = ac.codLocal		
		where a.codOrganizacao =' . $codOrganizacao . '  and ' . $filtro . ' order by a.dataInicio asc');
		return $query->getResult();
	}


	public function vagasCriadas($codConfig)
	{


		$query = $this->db->query('select count(*) as total
		FROM amb_agendamentos a
		join amb_agendamentosconfig ac on ac.codConfig=a.codConfig
		where a.codConfig = ' . $codConfig);
		return $query->getRow();
	}

	public function vagasAbertas($codConfig)
	{


		$query = $this->db->query('select count(*) as total
		FROM amb_agendamentos a
		join amb_agendamentosconfig ac on ac.codConfig=a.codConfig
		where a.codPaciente =0 and a.codConfig = ' . $codConfig);
		return $query->getRow();
	}

	public function exiteAgendamentos30Dias($codPaciente, $codEspecialidade)
	{


		$query = $this->db->query('select *
		from amb_agendamentos a 
		left join amb_agendamentosconfig ac on ac.codConfig=a.codConfig
		where a.codPaciente = "' . $codPaciente . '" and a.codEspecialidade="' . $codEspecialidade . '"  and (ac.codTipoAgendamento not in (2,3) or a.codConfig=0) and a.dataInicio >= NOW() and a.dataInicio <= ADDDATE(NOW(), INTERVAL 30 DAY) order by codAgendamento desc limit 1');
		return $query->getRow();
	}

	public function exiteAgendamentos8Dias($codPaciente, $codEspecialidade)
	{


		$query = $this->db->query('select *
		from amb_agendamentos a 
		left join amb_agendamentosconfig ac on ac.codConfig=a.codConfig
		where a.codPaciente = "' . $codPaciente . '" and a.codEspecialidade="' . $codEspecialidade . '"  and (ac.codTipoAgendamento not in (2,3) or a.codConfig=0) and a.dataInicio >= NOW() and a.dataInicio <= ADDDATE(NOW(), INTERVAL 8 DAY) order by codAgendamento desc limit 1');
		return $query->getRow();
	}

	public function exiteAgendamentos3Consultas30Dias($codPaciente)
	{


		$query = $this->db->query('select count(codAgendamento) as total
		from amb_agendamentos a 
		left join amb_agendamentosconfig ac on ac.codConfig=a.codConfig
		where a.codPaciente = ' . $codPaciente . ' and ac.codTipoAgendamento not in (2,3) and a.dataInicio >= ADDDATE(NOW(), INTERVAL -30 DAY)');
		return $query->getRow()->total;
	}


	public function verificaPacienteFaltoso($codPaciente, $codEspecialidade)
	{

		$query = $this->db->query('select *
		from amb_agendamentosfalta
		where impedidoAgendar = 1 and codPaciente = ' . $codPaciente . ' and codEspecialidade=' . $codEspecialidade . ' and dataEncerramentoImpedimento > NOW() limit 1');
		return $query->getResult();
	}

	public function meusPacientesHoje()
	{
		$query = $this->db->query('select count(*) as total
		from amb_agendamentos where codEspecialista=' . session()->codPessoa . ' and codPaciente <> 0 and DATE_FORMAT(dataInicio, "%Y-%m-%d") = DATE_FORMAT(NOW(), "%Y-%m-%d")');
		return $query->getRow()->total;
	}


	public function marcados()
	{

		$codEspecialidade = session()->filtroEspecialidade["codEspecialidade"];
		$dataInicio = session()->filtroEspecialidade["dataInicio"];
		$dataEncerramento = session()->filtroEspecialidade["dataEncerramento"];
		$codEspecialista = session()->filtroEspecialidade["codEspecialista"];




		if ($codEspecialidade !== NULL and $codEspecialidade !== "") {
			$filtro = ' and a.codEspecialidade = ' . $codEspecialidade . '';
		}
		if ($codEspecialista !== NULL and $codEspecialista !== "null") {
			$filtro .= ' and a.codEspecialista = ' . $codEspecialista;
		}
		if ($dataInicio !== NULL and $dataInicio !== "") {
			$filtro .= ' and DATE_FORMAT(a.dataInicio, "%Y-%m-%d") >="' . $dataInicio . '"';
		} else {
			$filtro .= ' and DATE_FORMAT(a.dataInicio, "%Y-%m-%d") >= DATE_FORMAT(NOW(), "%Y-%m-%d")';
		}
		if ($dataEncerramento !== NULL and $dataEncerramento !== "") {
			$filtro .= ' and DATE_FORMAT(a.dataEncerramento, "%Y-%m-%d")  <= "' . $dataEncerramento . '"';
		}

		if ($dataInicio !== NULL and $dataInicio !== "" and ($dataEncerramento == NULL or $dataEncerramento == "")) {
			$filtro .= ' and DATE_FORMAT(a.dataInicio, "%Y-%m-%d") ="' . $dataInicio . '"';
		}

		$query = $this->db->query('select pp.nomeExibicao as autorMarcacao, a.*,s.nomeStatus,e.*,p.nomeExibicao as nomeEspecialista,pa.codPlano,pa.codProntuario,pa.nomeExibicao as nomePaciente,pa.fotoPerfil,p.codPessoa as codEspecialista,p.fotoPerfil as fotoPerfilEspecialista,pa.cpf, pa.celular,pa.emailPessoal,pa.celular,TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade
		from amb_agendamentos a 
		left join amb_agendamentosstatus s on s.codStatus=a.codStatus 
		left join amb_agendamentosconfig ac on a.codConfig=ac.codConfig 
		left join sis_especialidades e on e.codEspecialidade=a.codEspecialidade
		left join sis_pessoas p on a.codEspecialista=p.codPessoa		
		left join sis_pessoas pp on a.marcadoPor=pp.codPessoa		
		left join sis_pacientes pa on a.codPaciente=pa.codPaciente		
		where a.codPaciente <> 0 ' . $filtro . ' order by a.dataInicio asc,a.codEspecialista asc');
		return $query->getResult();
	}



	public function removeSlotsNaoAgendados($codConfig)
	{
		$query = $this->db->query('delete from amb_agendamentos where codConfig=' . $codConfig . ' and codPaciente=0');
		//$query = $this->db->query('update amb_agendamentos set codConfig=-1 where codConfig=' . $codConfig . ' and codPaciente<>0');
		return true;
	}

	public function todosMeusAgendamentos()
	{
		$codPaciente = session()->codPaciente;


		$query = $this->db->query('select * from 
		(select "Consulta" as tipoAgenda,a.dataInicio,a.codAgendamento,a.codStatus,ass.nomeStatus,p.nomeExibicao as nomeEspecialista,d.descricaoDepartamento,e.descricaoEspecialidade,pa.nomeExibicao as nomePaciente,pa.fotoPerfil,p.codPessoa as codEspecialista,p.fotoPerfil as fotoPerfilEspecialista,pa.cpf, pa.celular,pa.emailPessoal
				from amb_agendamentos a 
				left join amb_agendamentosconfig ac on ac.codConfig=a.codConfig
				left join amb_agendamentosstatus ass on ass.codStatus=a.codStatus
				left join sis_especialidades e on e.codEspecialidade=a.codEspecialidade
				left join sis_pessoas p on a.codEspecialista=p.codPessoa		
				left join sis_pacientes pa on a.codPaciente=pa.codPaciente	
				left join sis_departamentos d on d.codDepartamento = ac.codLocal	
				where a.codPaciente =' . $codPaciente . '
		union all        
		select "Exame" as tipoAgenda,a.dataInicio,a.codExame as codAgendamento,a.codStatus,ass.nomeStatus,p.nomeExibicao as nomeEspecialista,d.descricaoDepartamento,e.descricaoExameLista as descricaoEspecialidade,pa.nomeExibicao as nomePaciente,pa.fotoPerfil,p.codPessoa as codEspecialista,p.fotoPerfil as fotoPerfilEspecialista,pa.cpf, pa.celular,pa.emailPessoal
				from age_agendamentosexames a 
				left join age_agendamentosexamesconfig ac on ac.codConfig=a.codConfig
				left join age_agendamentosexamesstatus ass on ass.codStatus=a.codStatus
				left join age_agendamentosexameslista e on e.codExameLista=a.codExameLista
				left join sis_pessoas p on a.codEspecialista=p.codPessoa		
				left join sis_pacientes pa on a.codPaciente=pa.codPaciente	
				left join sis_departamentos d on d.codDepartamento = ac.codLocal	
				where a.codPaciente =' . $codPaciente . '
		union all
		select "Serviço" as tipoAgenda,a.dataInicio,a.codSenhaAtendimento as codAgendamento,a.codStatus,ass.nomeStatus,NULL as nomeEspecialista,d.descricaoDepartamento, d.descricaoDepartamento as descricaoEspecialidade,pa.nomeExibicao as nomePaciente,pa.fotoPerfil,NULL as codEspecialista,NULL as fotoPerfilEspecialista,pa.cpf, pa.celular,pa.emailPessoal
		from amb_atendimentosenha a 
		left join amb_agendamentosenhasconfig ac on ac.codConfig=a.codConfig
		left join amb_agendamentosstatus ass on ass.codStatus=a.codStatus
		left join sis_pacientes pa on a.codPaciente=pa.codPaciente	
		left join sis_departamentos d on d.codDepartamento = a.codDepartamento	
		where a.codPaciente =' . $codPaciente . '
		)x 
				
		  order by x.dataInicio desc');
		return $query->getResult();
	}
	public function meusAgendamentos()
	{


		$codPaciente = session()->codPaciente;

		$query = $this->db->query('select a.*,ass.*,e.*,p.nomeExibicao as nomeEspecialista,d.descricaoDepartamento,e.descricaoEspecialidade,pa.nomeExibicao as nomePaciente,pa.fotoPerfil,p.codPessoa as codEspecialista,p.fotoPerfil as fotoPerfilEspecialista,pa.cpf, pa.celular,pa.emailPessoal,pa.celular
		from amb_agendamentos a 
		left join amb_agendamentosconfig ac on ac.codConfig=a.codConfig
		left join amb_agendamentosstatus ass on ass.codStatus=a.codStatus
		left join sis_especialidades e on e.codEspecialidade=a.codEspecialidade
		left join sis_pessoas p on a.codEspecialista=p.codPessoa		
		left join sis_pacientes pa on a.codPaciente=pa.codPaciente	
		left join sis_departamentos d on d.codDepartamento = ac.codLocal	
		where a.codPaciente =' . $codPaciente . '  order by a.dataInicio desc,a.codEspecialista asc');
		return $query->getResult();
	}


	public function meusAgendamentosPassados($codPaciente = NULL)
	{

		$query = $this->db->query('select a.*,ass.*,e.*,p.nomeExibicao as nomeEspecialista,d.descricaoDepartamento,e.descricaoEspecialidade,pa.nomeExibicao as nomePaciente,pa.fotoPerfil,p.codPessoa as codEspecialista,p.fotoPerfil as fotoPerfilEspecialista,pa.cpf, pa.celular,pa.emailPessoal,pa.celular
		from amb_agendamentos a 
		left join amb_agendamentosconfig ac on ac.codConfig=a.codConfig
		left join amb_agendamentosstatus ass on ass.codStatus=a.codStatus
		left join sis_especialidades e on e.codEspecialidade=a.codEspecialidade
		left join sis_pessoas p on a.codEspecialista=p.codPessoa		
		left join sis_pacientes pa on a.codPaciente=pa.codPaciente	
		left join sis_departamentos d on d.codDepartamento = ac.codLocal	
		where a.codPaciente =' . $codPaciente . ' and a.dataInicio < NOW() order by a.dataInicio desc,a.codEspecialista asc');
		return $query->getResult();
	}
}
