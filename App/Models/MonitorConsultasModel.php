<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class MonitorConsultasModel extends Model
{


	public function agendamentosSemana()
	{
		if (session()->codOrganizacao == NULL) {
			$codOrganizacao = $this->db->query('select codOrganizacao from sis_organizacoes where matriz=1')->getRow()->codOrganizacao;
		} else {
			$codOrganizacao = session()->codOrganizacao;
		}


		$select = 'select c.codTipoAgendamento, DAYOFWEEK(a.dataMarcacao) diaSemana, a.codAgendamento,DATE_FORMAT(a.dataMarcacao, "%Y-%m-%d") as dataMarcacao,DATE_FORMAT(a.dataMarcacao, "%w") as diaSemana, DATE_FORMAT(a.dataEncerramento, "%Y-%m-%d") as dataEncerramento,a.*
		from amb_agendamentos a 
		left join amb_agendamentosconfig c on c.codConfig = a.codConfig
		where a.codPaciente<> 0 and a.codOrganizacao = ' . $codOrganizacao . ' and YEARWEEK(a.dataMarcacao) = YEARWEEK(NOW())';
		$query = $this->db->query($select);
		return $query->getResult();
	}

	public function agendamentosSemanaAnterior()
	{
		if (session()->codOrganizacao == NULL) {
			$codOrganizacao = $this->db->query('select codOrganizacao from sis_organizacoes where matriz=1')->getRow()->codOrganizacao;
		} else {
			$codOrganizacao = session()->codOrganizacao;
		}


		$select = 'select c.codTipoAgendamento, DAYOFWEEK(a.dataMarcacao) diaSemana, a.codAgendamento,DATE_FORMAT(a.dataMarcacao, "%Y-%m-%d") as dataMarcacao,DATE_FORMAT(a.dataMarcacao, "%w") as diaSemana, DATE_FORMAT(a.dataEncerramento, "%Y-%m-%d") as dataEncerramento,a.*
		from amb_agendamentos a 
		left join amb_agendamentosconfig c on c.codConfig = a.codConfig
		where a.codPaciente<> 0 and a.codOrganizacao = ' . $codOrganizacao . ' and YEARWEEK(a.dataMarcacao) = YEARWEEK(NOW())-1';
		$query = $this->db->query($select);
		return $query->getResult();
	}


	public function vagasAbertasInternet()
	{
		if (session()->codOrganizacao == NULL) {
			$codOrganizacao = $this->db->query('select codOrganizacao from sis_organizacoes where matriz=1')->getRow()->codOrganizacao;
		} else {
			$codOrganizacao = session()->codOrganizacao;
		}


		$select = 'select count(*) as vagasAbertas, e.descricaoEspecialidade
		from amb_agendamentos a 
		left join amb_agendamentosconfig c on c.codConfig = a.codConfig
		left join sis_especialidades e on e.codEspecialidade = a.codEspecialidade
		where a.codPaciente=0 and c.codStatusAgendamento=1 and a.codOrganizacao = ' . $codOrganizacao . ' and c.codTipoAgendamento=1 and a.dataInicio > NOW()
        group by e.descricaoEspecialidade
        order by vagasAbertas desc';
		$query = $this->db->query($select);
		return $query->getResult();
	}
	
	public function vagasAbertasPresencial()
	{
		if (session()->codOrganizacao == NULL) {
			$codOrganizacao = $this->db->query('select codOrganizacao from sis_organizacoes where matriz=1')->getRow()->codOrganizacao;
		} else {
			$codOrganizacao = session()->codOrganizacao;
		}


		$select = 'select count(*) as vagasAbertas, e.descricaoEspecialidade
		from amb_agendamentos a 
		left join amb_agendamentosconfig c on c.codConfig = a.codConfig
		left join sis_especialidades e on e.codEspecialidade = a.codEspecialidade
		where a.codPaciente=0 and a.codStatus = 0 and c.codStatusAgendamento=1 and a.codOrganizacao = ' . $codOrganizacao . ' and c.codTipoAgendamento=4 and a.dataInicio > NOW()
        group by e.descricaoEspecialidade
        order by vagasAbertas desc';
		$query = $this->db->query($select);
		return $query->getResult();
	}

	public function maisMarcadas()
	{



		if (session()->codOrganizacao == NULL) {
			$codOrganizacao = $this->db->query('select codOrganizacao from sis_organizacoes where matriz=1')->getRow()->codOrganizacao;
		} else {
			$codOrganizacao = session()->codOrganizacao;
		}


		$select = 'select count(codAgendamento) as totalAgendamentos, e.descricaoEspecialidade as especialidade
		from amb_agendamentos a 
		left join sis_especialidades e on e.codEspecialidade=a.codEspecialidade
		where a.codEspecialidade<>29 and a.codPaciente<>0 and a.codOrganizacao = ' . $codOrganizacao . ' and a.dataCriacao >= DATE(NOW()) - INTERVAL 30 DAY
        group by descricaoEspecialidade
        order by count(codAgendamento) desc
        limit 6';
		$query = $this->db->query($select);
		return $query->getResult();
	}


	public function totalAgendamentos()
	{


		if (session()->codOrganizacao == NULL) {
			$codOrganizacao = $this->db->query('select codOrganizacao from sis_organizacoes where matriz=1')->getRow()->codOrganizacao;
		} else {
			$codOrganizacao = session()->codOrganizacao;
		}


		$totais = array();


		$totalInternet = 'select count(*) as total
		from amb_agendamentos a 
		left join amb_agendamentosconfig c on c.codConfig=a.codConfig
		where c.codTipoAgendamento=1 and a.codPaciente>0 and  a.codOrganizacao = ' . $codOrganizacao . ' and DATE_FORMAT(a.dataMarcacao, "%Y-%m-%d") = CURDATE()';
		$internet = $this->db->query($totalInternet);




		$totalRetorno = 'select count(*) as total
		from amb_agendamentos a 
		left join amb_agendamentosconfig c on c.codConfig=a.codConfig
		where c.codTipoAgendamento=2 and a.codPaciente>0 and  a.codOrganizacao = ' . $codOrganizacao . ' and DATE_FORMAT(a.dataMarcacao, "%Y-%m-%d") = CURDATE()';
		$retorno = $this->db->query($totalRetorno);


		$totalAbas = 'select count(*) as total
		from amb_agendamentos a 
		left join amb_agendamentosconfig c on c.codConfig=a.codConfig
		where c.codTipoAgendamento=3 and a.codPaciente>0 and  a.codOrganizacao = ' . $codOrganizacao . ' and DATE_FORMAT(a.dataMarcacao, "%Y-%m-%d") = CURDATE()';
		$abas = $this->db->query($totalAbas);

		$totalSame = 'select count(*) as total
		from amb_agendamentos a 
		left join amb_agendamentosconfig c on c.codConfig=a.codConfig
		where c.codTipoAgendamento=4 and a.codPaciente>0 and  a.codOrganizacao = ' . $codOrganizacao . ' and DATE_FORMAT(a.dataMarcacao, "%Y-%m-%d") = CURDATE()';
		$same = $this->db->query($totalSame);


		$totais['internet'] = $internet->getRow()->total;
		$totais['retorno'] = $retorno->getRow()->total;
		$totais['abas'] = $abas->getRow()->total;
		$totais['same'] = $same->getRow()->total;



		return $totais;
	}
}
