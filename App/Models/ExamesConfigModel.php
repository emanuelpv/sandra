<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class ExamesConfigModel extends Model
{

	protected $table = 'amb_examesconfig';
	protected $primaryKey = 'codConfig';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codLocal', 'codOrganizacao', 'ordemAtendimento', 'autor', 'codExameLista', 'codEspecialista', 'dataCriacao', 'dataInicio', 'horaInicio', 'dataEncerramento', 'horaEncerramento', 'tempoAtendimento', 'intervaloAtendimento', 'codStatusExame', 'codTipoExame', 'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo', 'dataAtualizacao'];
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
		$query = $this->db->query('select pp.nomeExibicao as nomeAutor,ac.*,e.descricaoExameLista,p.nomeExibicao ,ass.nomeStatus,t.nomeTipo
		from amb_examesconfig ac
		join age_agendamentosexameslista e on ac.codExameLista=e.codExameLista
		join sis_pessoas p on p.codPessoa = ac.codEspecialista
		join sis_pessoas pp on pp.codPessoa = ac.autor
		join amb_examesstatusconfig ass on ass.codStatus=ac.codStatusExame
		join amb_examestipo t on t.codTipo=ac.codTipoExame
		order by ac.dataCriacao desc');
		return $query->getResult();
	}
	public function pegaAgendasProgramadas()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select pp.nomeExibicao as nomeAutor,ac.*,e.descricaoExameLista,p.nomeExibicao ,ass.nomeStatus,t.nomeTipo
		from amb_examesconfig ac
		join age_agendamentosexameslista e on ac.codExameLista=e.codExameLista
		join sis_pessoas p on p.codPessoa = ac.codEspecialista
		join sis_pessoas pp on pp.codPessoa = ac.autor
		join amb_examesstatusconfig ass on ass.codStatus=ac.codStatusExame
		join amb_examestipo t on t.codTipo=ac.codTipoExame
		where ac.dataEncerramento >= NOW() and ac.codStatusExame<>1
		order by ac.dataCriacao desc');
		return $query->getResult();
	}
	public function pegaAgendasLiberadasHoje()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select pp.nomeExibicao as nomeAutor,ac.*,e.descricaoExameLista,p.nomeExibicao ,ass.nomeStatus,t.nomeTipo
		from amb_examesconfig ac
		join age_agendamentosexameslista e on ac.codExameLista=e.codExameLista
		join sis_pessoas p on p.codPessoa = ac.codEspecialista
		join sis_pessoas pp on pp.codPessoa = ac.autor
		join amb_examesstatusconfig ass on ass.codStatus=ac.codStatusExame
		join amb_examestipo t on t.codTipo=ac.codTipoExame
		where DATE_FORMAT(ac.dataAtualizacao, "%Y-%m-%d") = DATE_FORMAT(NOW(), "%Y-%m-%d") and ac.codStatusExame=1
		order by ac.dataCriacao desc');
		return $query->getResult();
	}
	public function buscaAvancada($codEspecialidade = NULL, $codEspecialista = NULL, $dataInicio = NULL, $dataEncerramento = NULL)
	{

		$filtro = '';

		if ($codEspecialidade !== NULL and $codEspecialidade !== "" and $codEspecialidade !== " ") {
			$filtro .= ' and ac.codExameLista="' . $codEspecialidade . '"';
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
		$query = $this->db->query('select pp.nomeExibicao as nomeAutor,ac.*,e.descricaoExameLista,p.nomeExibicao ,ass.nomeStatus,t.nomeTipo
		from amb_examesconfig ac
		join age_agendamentosexameslista e on ac.codExameLista=e.codExameLista
		join sis_pessoas p on p.codPessoa = ac.codEspecialista
		join sis_pessoas pp on pp.codPessoa = ac.autor
		join amb_examesstatusconfig ass on ass.codStatus=ac.codStatusExame
		join amb_examestipo t on t.codTipo=ac.codTipoExame
		where 1=1 ' . $filtro . '
		order by ac.dataCriacao desc');
		return $query->getResult();
	}
	public function pegaPorCodigo($codConfig)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codConfig = "' . $codConfig . '"');
		return $query->getRow();
	}


	public function removeExameConfig($codConfig)
	{
		if ($this->db->query('delete from amb_exames where codConfig = ' . $codConfig . ' and codPaciente=0')) {
			return true;
		}
	}



	public function updateExames($codConfig, $data)
	{
		if ($codConfig !== NULL and $codConfig !== "" and $codConfig !== " ") {

			$this->db->where('codConfig', $codConfig);
			$this->db->update('amb_exames', $data);
		}
	}


	public function vagasCriadas($codConfig)
	{


		$query = $this->db->query('select count(*) as total
		FROM amb_exames a
		join amb_examesconfig ac on ac.codConfig=a.codConfig
		where a.codConfig = ' . $codConfig);
		return $query->getRow();
	}

	public function vagasAbertas($codConfig)
	{


		$query = $this->db->query('select count(*) as total
		FROM amb_exames a
		join amb_examesconfig ac on ac.codConfig=a.codConfig
		where a.codPaciente =0 and a.codConfig = ' . $codConfig);
		return $query->getRow();
	}
}
