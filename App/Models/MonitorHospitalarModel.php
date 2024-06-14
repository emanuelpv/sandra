<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class MonitorHospitalarModel extends Model
{

	public function atendimentosEmergenciaSemanaAtual()
	{
		if (session()->codOrganizacao == NULL) {
			$codOrganizacao = $this->db->query('select codOrganizacao from sis_organizacoes where matriz=1')->getRow()->codOrganizacao;
		} else {
			$codOrganizacao = session()->codOrganizacao;
		}


		$select = '
		select a.codAtendimento ,DATE_FORMAT(a.dataCriacao, "%Y-%m-%d") as dataCriacao, DATE_FORMAT(a.dataCriacao, "%w") as diaSemana
		from amb_atendimentos a 
		where a.codOrganizacao = "' . $codOrganizacao . '" 
		and a.codTipoAtendimento = 1
		and YEARWEEK(a.dataCriacao, 1) = YEARWEEK(CURDATE(), 1)';
		$query = $this->db->query($select);
		return $query->getResult();
	}

	public function atendimentosEmergenciaSemanaPassada()
	{
		if (session()->codOrganizacao == NULL) {
			$codOrganizacao = $this->db->query('select codOrganizacao from sis_organizacoes where matriz=1')->getRow()->codOrganizacao;
		} else {
			$codOrganizacao = session()->codOrganizacao;
		}


		$select = '
		select a.codAtendimento ,DATE_FORMAT(a.dataCriacao, "%Y-%m-%d") as dataCriacao, DATE_FORMAT(a.dataCriacao, "%w") as diaSemana
		from amb_atendimentos a 
		where a.codOrganizacao = "' . $codOrganizacao . '" 
		and a.codTipoAtendimento = 1
		and a.dataCriacao >= curdate() - INTERVAL DAYOFWEEK(curdate())+6 DAY
		AND a.dataCriacao < curdate() - INTERVAL DAYOFWEEK(curdate())-1 DAY';
		$query = $this->db->query($select);
		return $query->getResult();
	}

	public function atendimentosPorMedicos()
	{
		if (session()->codOrganizacao == NULL) {
			$codOrganizacao = $this->db->query('select codOrganizacao from sis_organizacoes where matriz=1')->getRow()->codOrganizacao;
		} else {
			$codOrganizacao = session()->codOrganizacao;
		}


		$select = 'select count(a.codAtendimento) as total, 
		CASE WHEN p.nomeExibicao IS NULL
                  THEN  "ACOLHIMENTO"
                  ELSE  p.nomeExibicao
         END nomeExibicao, p.fotoPerfil
		from amb_atendimentos a 
		left join sis_pessoas p on p.codPessoa=a.codEspecialista
		where a.codOrganizacao = ' . $codOrganizacao . ' and a.codTipoAtendimento = 1 
		and date_format(a.dataCriacao, "%Y-%m-%d") >=  DATE_SUB(CURDATE(), INTERVAL 1 DAY)
		group by p.nomeExibicao,p.fotoPerfil
		order by p.codCargo';
		$query = $this->db->query($select);
		return $query->getResult();
	}
}
