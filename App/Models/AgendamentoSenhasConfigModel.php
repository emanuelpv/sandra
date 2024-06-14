<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class AgendamentoSenhasConfigModel extends Model
{

	protected $table = 'amb_agendamentosenhasconfig';
	protected $primaryKey = 'codConfig';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codOrganizacao', 'ordemAtendimento', 'autor', 'codDepartamento', 'dataCriacao', 'dataInicio', 'horaInicio', 'dataEncerramento', 'horaEncerramento', 'tempoAtendimento', 'qtdeAtendentes', 'codStatusAgendamento', 'codTipoAgendamento', 'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo', 'dataAtualizacao'];
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
		$query = $this->db->query('select ac.*,e.abreviacaoDepartamento,pp.nomeExibicao as autor,ass.nomeStatus,t.nomeTipo
		from amb_agendamentosenhasconfig ac
		join sis_departamentos e on ac.codDepartamento=e.codDepartamento
		join sis_pessoas pp on pp.codPessoa = ac.autor
		join amb_agendamentosstatusconfig ass on ass.codStatus=ac.codStatusAgendamento
		join amb_agendamentostipo t on t.codTipo=ac.codTipoAgendamento
		order by ac.dataCriacao desc');
		return $query->getResult();
	}


	public function vagasCriadas($codConfig)
	{


		$query = $this->db->query('select count(*) as total
		FROM amb_atendimentosenha a
		join amb_agendamentosenhasconfig ac on ac.codConfig=a.codConfig
		where a.codConfig = ' . $codConfig);
		return $query->getRow();
	}

	public function vagasAbertas($codConfig)
	{


		$query = $this->db->query('select count(*) as total
		FROM amb_atendimentosenha a
		join amb_agendamentosenhasconfig ac on ac.codConfig=a.codConfig
		where a.codPaciente =0 and a.codConfig = ' . $codConfig);
		return $query->getRow();
	}

	public function removeSlotsNaoAgendados($codConfig)
	{
		$query = $this->db->query('delete from amb_atendimentosenha where codConfig=' . $codConfig . ' and codPaciente=0');
		//$query = $this->db->query('update amb_agendamentos set codConfig=-1 where codConfig=' . $codConfig . ' and codPaciente<>0');
		return true;
	}


	public function pegaPorCodigo($codConfig)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codConfig = "' . $codConfig . '"');
		return $query->getRow();
	}

	public function listaDropDownTipoSenha()
	{
		$query = $this->db->query('select codTipoSenha as id, descricaoTipoSenha as text from sis_tiposenha');
		return $query->getResult();
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
