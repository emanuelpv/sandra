<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class painelChamadasModel extends Model
{

	protected $table = 'amb_chamadasfila';
	protected $primaryKey = 'codChamada';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codEspecialidade', 'codClasseRisco', 'codOrganizacao', 'localAtendimento', 'dataChamada', 'codChamador', 'qtdChamadas', 'codPaciente'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;

	public function listaDropDownEspecialidades()
	{
		$query = $this->db->query('select codEspecialidade as id,descricaoEspecialidade as text from sis_especialidades order by descricaoEspecialidade');
		return $query->getResult();
	}

	public function pegaTudo()
	{
		$query = $this->db->query('select * from amb_chamadasfila');
		return $query->getRow();
	}

	public function pacientesChamados($pacientesChamados = null)
	{
		if ($pacientesChamados !== null and $pacientesChamados !== "") {
			$query = $this->db->query('select e.descricaoEspecialidade,pa.*,cf.*,pe.nomeExibicao, TIMESTAMPDIFF(MINUTE, cf.dataChamada,NOW()) as ultimaChamada 
		from amb_chamadasfila cf
		left join sis_pacientes pa on pa.codPaciente=cf.codPaciente 
		left join sis_especialidades e on e.codEspecialidade=cf.codEspecialidade 
		left join sis_pessoas pe on pe.codPessoa = cf.codChamador
		where cf.codEspecialidade in (' . $pacientesChamados . ')
		order by cf.qtdChamadas desc,cf.codChamada asc  limit 1');
		} else {
			/*
			$query = $this->db->query('select e.descricaoEspecialidade,pa.*,cf.*,pe.nomeExibicao 
			from amb_chamadasfila cf
			left join sis_pacientes pa on pa.codPaciente=cf.codPaciente 
			left join sis_especialidades e on e.codEspecialidade=cf.codEspecialidade 
			left join sis_pessoas pe on pe.codPessoa = cf.codChamador
			order by cf.qtdChamadas desc,cf.codChamada asc  limit 1');
			*/
		}

		return $query->getRow();
	}


	public function marcados($especialidades = null)
	{

		$especialidades = removeCaracteresIndesejados($especialidades);

		if ($especialidades !== null and $especialidades !== "") {
			$query = $this->db->query('select a.*,e.*,p.nomeExibicao as nomeEspecialista,pa.nomeExibicao as nomePaciente,pa.fotoPerfil,p.codPessoa as codEspecialista,p.fotoPerfil as fotoPerfilEspecialista,pa.cpf, pa.celular,pa.emailPessoal,pa.celular,TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade
		from amb_agendamentos a 
		left join amb_agendamentosconfig ac on a.codConfig=ac.codConfig 
		left join sis_especialidades e on e.codEspecialidade=a.codEspecialidade
		left join sis_pessoas p on a.codEspecialista=p.codPessoa		
		left join sis_pacientes pa on a.codPaciente=pa.codPaciente	
		where encerramentoAtendimento is null and a.codPaciente <> 0 and a.codEspecialidade in (' . $especialidades . ') and DATE_FORMAT(a.dataInicio, "%Y-%m-%d")=CURRENT_DATE() order by a.dataInicio asc,a.codEspecialista asc');
		} else {

			/*
			$query = $this->db->query('select a.*,e.*,p.nomeExibicao as nomeEspecialista,pa.nomeExibicao as nomePaciente,pa.fotoPerfil,p.codPessoa as codEspecialista,p.fotoPerfil as fotoPerfilEspecialista,pa.cpf, pa.celular,pa.emailPessoal,pa.celular,TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade
		from amb_agendamentos a 
		left join amb_agendamentosconfig ac on a.codConfig=ac.codConfig 
		left join sis_especialidades e on e.codEspecialidade=a.codEspecialidade
		left join sis_pessoas p on a.codEspecialista=p.codPessoa		
		left join sis_pacientes pa on a.codPaciente=pa.codPaciente		
		where encerramentoAtendimento is null and a.codPaciente <> 0 and DATE_FORMAT(a.dataInicio, "%Y-%m-%d")=CURRENT_DATE() order by a.dataInicio asc,a.codEspecialista asc');
		
		*/
			return array();
		}

		return $query->getResult();
	}

	public function pacientesUltimasChamadas($especialidades = null)
	{

		$configuracao = config('App');
		session()->set('codOrganizacao', $configuracao->codOrganizacao);
		$codOrganizacao = $configuracao->codOrganizacao;

		$query = $this->db->query('select pe.nomeExibicao as especialista,pa.nomeExibicao as nomePaciente,TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade,cf.localAtendimento,count(*) as Nrchamadas 
		FROM amb_chamadasfila cf
		join sis_pessoas pe on cf.codChamador = pe.codPessoa
		join sis_pacientes pa on cf.codPaciente = pa.codPaciente
		where cf.codEspecialidade in (' . $especialidades . ')
		group by pe.nomeExibicao,pa.nomeExibicao,cf.localAtendimento,pa.dataNascimento 
		order by cf.dataChamada desc limit 10');
		return $query->getResult();
	}
}
