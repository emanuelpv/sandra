<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class IndicacoesClinicasModel extends Model
{

	protected $table = 'amb_indicacoesclinicas';
	protected $primaryKey = 'codIndicacaoClinica';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['acompanhamentoPermanente','codEspecialidade', 'protocolo', 'justificativa', 'dataInicio', 'dataEncerramento', 'codAutor', 'codPaciente'];
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
		$query = $this->db->query('select * from amb_indicacoesclinicas 
		where codPaciente = "' . session()->codPaciente . '" and (dataEncerramento > NOW() or acompanhamentoPermanente=1)');

		return $query->getResult();
	}

	public function indicacoesPaciente($codPaciente)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select i.*,e.descricaoEspecialidade,p.nomeExibicao
		from amb_indicacoesclinicas i
		left join sis_especialidades e on e.codEspecialidade=i.codEspecialidade
		left join sis_pessoas p on p.codPessoa=i.codAutor
		where i.codPaciente="' . $codPaciente . '"  and (i.dataEncerramento > NOW() or i.acompanhamentoPermanente=1) order by i.dataEncerramento desc');
		return $query->getResult();
	}

	public function indicacoesValidasPaciente($codPaciente)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select i.*,e.descricaoEspecialidade,p.nomeExibicao
		from amb_indicacoesclinicas i
		left join sis_especialidades e on e.codEspecialidade=i.codEspecialidade
		left join sis_pessoas p on p.codPessoa=i.codAutor
		where i.codPaciente="' . $codPaciente . '"  and (i.dataEncerramento > NOW() or i.acompanhamentoPermanente=1)
		order by i.dataEncerramento desc');
		return $query->getResult();
	}



	public function pegaPorCodigo($codIndicacaoClinica)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codIndicacaoClinica = "' . $codIndicacaoClinica . '"');
		return $query->getRow();
	}

	public function verificaExistencia($codPaciente, $codEspecialidade)
	{
		$query = $this->db->query('select * from amb_indicacoesclinicas 
		where codPaciente = "' . $codPaciente . '" and codEspecialidade = "' . $codEspecialidade . '"   and (dataEncerramento > NOW() or acompanhamentoPermanente=1)');
		return $query->getRow();
	}
}
