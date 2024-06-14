<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class AgendamentosConfigModel extends Model
{

	protected $table = 'amb_agendamentosconfig';
	protected $primaryKey = 'codConfig';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['dataLiberacao','totalVagas','codLocal', 'codOrganizacao', 'ordemAtendimento', 'autor', 'codEspecialidade', 'codEspecialista', 'dataCriacao', 'dataInicio', 'horaInicio', 'dataEncerramento', 'horaEncerramento', 'tempoAtendimento', 'intervaloAtendimento', 'codStatusAgendamento', 'codTipoAgendamento', 'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo', 'dataAtualizacao'];
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
		$query = $this->db->query('select ac.*,e.descricaoEspecialidade,p.nomeExibicao,pp.nomeExibicao as autor,ass.nomeStatus,t.nomeTipo
		from amb_agendamentosconfig ac
		left join sis_especialidades e on ac.codEspecialidade=e.codEspecialidade
		left join sis_pessoas p on p.codPessoa = ac.codEspecialista
		left join sis_pessoas pp on pp.codPessoa = ac.autor
		left join amb_agendamentosstatusconfig ass on ass.codStatus=ac.codStatusAgendamento
		left join amb_agendamentostipo t on t.codTipo=ac.codTipoAgendamento
		order by ac.dataCriacao desc');
		return $query->getResult();
	}

	public function pegaAgendasProgramadas()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select ac.*,e.descricaoEspecialidade,p.nomeExibicao,pp.nomeExibicao as autor,ass.nomeStatus,t.nomeTipo
		from amb_agendamentosconfig ac
		left join sis_especialidades e on ac.codEspecialidade=e.codEspecialidade
		left join sis_pessoas p on p.codPessoa = ac.codEspecialista
		left join sis_pessoas pp on pp.codPessoa = ac.autor
		left join amb_agendamentosstatusconfig ass on ass.codStatus=ac.codStatusAgendamento
		left join amb_agendamentostipo t on t.codTipo=ac.codTipoAgendamento
		where ac.dataEncerramento >= CURDATE() and ac.codStatusAgendamento<>1
		order by ac.dataCriacao desc');
		return $query->getResult();
	}




	public function buscaAvancada($codEspecialidade = NULL, $codEspecialista = NULL, $dataInicio = NULL, $dataEncerramento = NULL)
	{

		$filtro = '';

		if ($codEspecialidade !== NULL and $codEspecialidade !== "" and $codEspecialidade !== " ") {
			$filtro .= ' and ac.codEspecialidade="' . $codEspecialidade . '"';
		}

		if ($codEspecialista !== NULL and $codEspecialista !== "" and $codEspecialista !== " ") {
			$filtro .= ' and ac.codEspecialista="' . $codEspecialista . '"';
		}

		if ($dataInicio !== NULL and $dataInicio !== "" and $dataInicio !== " ") {
			$filtro .= ' and ac.dataInicio>="' . $dataInicio . '"';
		}

		if ($dataEncerramento !== NULL and $dataEncerramento !== "" and $dataEncerramento !== " ") {
			$filtro .= ' and ac.dataEncerramento<="' . $dataEncerramento . '"';
		}


		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select ac.*,e.descricaoEspecialidade,p.nomeExibicao,pp.nomeExibicao as autor,ass.nomeStatus,t.nomeTipo
		from amb_agendamentosconfig ac
		left join sis_especialidades e on ac.codEspecialidade=e.codEspecialidade
		left join sis_pessoas p on p.codPessoa = ac.codEspecialista
		left join sis_pessoas pp on pp.codPessoa = ac.autor
		left join amb_agendamentosstatusconfig ass on ass.codStatus=ac.codStatusAgendamento
		left join amb_agendamentostipo t on t.codTipo=ac.codTipoAgendamento
		where 1=1 ' . $filtro . '
		order by ac.dataCriacao desc');
		return $query->getResult();
	}
	public function pegaAgendasLiberadasHoje()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select ac.*,e.descricaoEspecialidade,p.nomeExibicao,pp.nomeExibicao as autor,ass.nomeStatus,t.nomeTipo
		from amb_agendamentosconfig ac
		left join sis_especialidades e on ac.codEspecialidade=e.codEspecialidade
		left join sis_pessoas p on p.codPessoa = ac.codEspecialista
		left join sis_pessoas pp on pp.codPessoa = ac.autor
		left join amb_agendamentosstatusconfig ass on ass.codStatus=ac.codStatusAgendamento
		left join amb_agendamentostipo t on t.codTipo=ac.codTipoAgendamento
		where DATE_FORMAT(ac.dataAtualizacao, "%Y-%m-%d") = CURDATE() and ac.codStatusAgendamento=1
		order by ac.dataCriacao desc');
		return $query->getResult();
	}
	public function pegaPorCodigo($codConfig)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codConfig = "' . $codConfig . '"');
		return $query->getRow();
	}


	public function removeAgendamentoConfig($codConfig)
	{
		if ($this->db->query('delete from amb_agendamentos where codConfig = ' . $codConfig . ' and codPaciente=0')) {
			return true;
		}
	}



	public function updateAgendamentos($codConfig, $data)
	{
		if ($codConfig !== NULL and $codConfig !== "" and $codConfig !== " ") {
			$this->db->where('codConfig', $codConfig);
			$this->db->update('amb_agendamentos', $data);
		}
	}
}
