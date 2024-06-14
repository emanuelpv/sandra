<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class EncaminhamentosModel extends Model
{


    public function agendamentosLivres()
	{

		$query = $this->db->query('select a.*,ac.codStatusAgendamento 
		from amb_atendimentosenha a
		left join amb_agendamentosenhasconfig ac on ac.codConfig=a.codConfig
		where ac.codStatusAgendamento=1 and a.codPaciente = 0  and a.codStatus = 0  and a.dataAtualizacao <= NOW()
		and a.dataInicio > date_add(NOW(),interval -60 minute)
		order by a.dataInicio asc');
		return $query->getResult();
	}


	public function registrarPesquisaVagaPaciente()
	{

		if (session()->codPaciente !== NULL) {
			$codPaciente = session()->codPaciente;

			$query = $this->db->query('insert into logs_pesquisavagaspaciente (codPesquisa, codPaciente, dataPesquisa) VALUES (NULL, "' . $codPaciente . '", current_timestamp())');
		}


		return true;
	}


}
