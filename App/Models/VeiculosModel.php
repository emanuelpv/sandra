<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class VeiculosModel extends Model
{

	protected $table = 'seg_veiculos';
	protected $primaryKey = 'codVeiculo';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['condutor1', 'condutor2', 'arquivo', 'placa', 'cpf', 'codPessoa', 'codPaciente', 'codVisitante', 'marca', 'modelo', 'cor', 'codStatus', 'dataCriacao', 'dataAtualizacao', 'dataAutorizacao', 'dataValidade', 'codAutor', 'observacao'];
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
		$query = $this->db->query('select * from seg_veiculos');
		return $query->getResult();
	}

	public function pegaPorCodigo($codVeiculo)
	{
		$query = $this->db->query('select v.*,d.descricaoDepartamento,p.nomeExibicao as nomeExibicaoPessoa, pp.nomeExibicao as nomeExibicaoPaciente, p.emailPessoal as emailPessoalPessoa, pp.emailPessoal as emailPessoalPaciente 
		from seg_veiculos v
		left join sis_pessoas p on p.codPessoa=v.codPessoa
		left join sis_pacientes pp on pp.codPaciente=v.codPaciente
		left join sis_departamentos d on p.codDepartamento=d.codDepartamento
		where codVeiculo = "' . $codVeiculo . '"');
		return $query->getRow();
	}
	public function pegaVeiculosUsuario($codPessoa = NULL, $codPaciente = NULL)
	{
		$codOrganizacao = session()->codOrganizacao;

		if ($codPessoa == -1) {
			$query = $this->db->query('select v.*,p.nomeExibicao as nomeExibicaoPessoa, pp.nomeExibicao as nomeExibicaoPaciente 
			from seg_veiculos v
			left join sis_pessoas p on p.codPessoa=v.codPessoa
			left join sis_pacientes pp on pp.codPaciente=v.codPaciente
			order by v.codStatus desc');
			return $query->getResult();
		} else {
			$query = $this->db->query('select v.*,p.nomeExibicao as nomeExibicaoPessoa, pp.nomeExibicao as nomeExibicaoPaciente 
			from seg_veiculos v
			left join sis_pessoas p on p.codPessoa=v.codPessoa
			left join sis_pacientes pp on pp.codPaciente=v.codPaciente
			where v.codPessoa = "' . $codPessoa . '" or v.codPaciente="' . $codPaciente . '" 
			order by v.codStatus desc');
			return $query->getResult();
		}
	}

	public function pegaMarcas()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select distinct descricao 
		from (select descricao from seg_marcasveiculos
		union all 
		select marca as descricao from seg_veiculos)x		
		');
		return $query->getResult();
	}


	public function pegaModelos()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select distinct descricao 
		from (select descricao from seg_modelosveiculos
		union all 
		select modelo as descricao from seg_veiculos)x		
		');
		return $query->getResult();
	}

	public function pegaCores()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select cor as descricao from seg_veiculos');
		return $query->getResult();
	}
}
