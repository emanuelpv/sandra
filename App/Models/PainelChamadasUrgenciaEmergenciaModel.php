<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class PainelChamadasUrgenciaEmergenciaModel extends Model
{

	protected $table = 'amb_chamadasfilaurgenciaemergencia';
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

	public function pacientesChamados()
	{
		$query = $this->db->query('select pa.*,cf.*,pe.nomeExibicao, TIMESTAMPDIFF(MINUTE, cf.dataChamada,NOW()) as ultimaChamada
		from amb_chamadasfilaurgenciaemergencia cf
		left join sis_pacientes pa on pa.codPaciente=cf.codPaciente 
		left join sis_pessoas pe on pe.codPessoa = cf.codChamador
		where cf.codEspecialidade in (29) and cf.qtdChamadas>0
		order by cf.qtdChamadas desc,cf.codChamada asc limit 1');
		return $query->getRow();
	}

	public function pacientesUrgenciaEmergenciaUltimasChamadas($especialidades = null)
	{

		$configuracao = config('App');
		session()->set('codOrganizacao', $configuracao->codOrganizacao);
		$codOrganizacao = $configuracao->codOrganizacao;

		$query = $this->db->query('select max(cfum.dataChamada) as dataChamada,pe.nomeExibicao as especialista,pa.nomeExibicao as nomePaciente,TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade,cfum.localAtendimento,count(*) as Nrchamadas FROM amb_chamadasfilaurgenciaemergencia cfum
		join sis_pessoas pe on cfum.codChamador = pe.codPessoa
		join sis_pacientes pa on cfum.codPaciente = pa.codPaciente
		group by pe.nomeExibicao,pa.nomeExibicao,cfum.localAtendimento,pa.dataNascimento 
        having  DATE_SUB(NOW(), INTERVAL 10 MINUTE) < dataChamada
		order by cfum.dataChamada desc limit 10');


		return $query->getResult();
	}

	public function pacientesUrgenciaEmergencia($especialidades = null)
	{


		$configuracao = config('App');
		session()->set('codOrganizacao', $configuracao->codOrganizacao);
		$codOrganizacao = $configuracao->codOrganizacao;

		$query = $this->db->query('select a.*,e.*,p.nomeExibicao as nomeEspecialista,pa.nomeExibicao as nomePaciente,pa.fotoPerfil,p.codPessoa as codEspecialista,p.fotoPerfil as fotoPerfilEspecialista,pa.cpf, pa.celular,pa.emailPessoal,pa.celular,TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade
		from amb_atendimentos a 
		left join sis_especialidades e on e.codEspecialidade=a.codEspecialidade
		left join sis_pessoas p on a.codEspecialista=p.codPessoa		
		left join sis_pacientes pa on a.codPaciente=pa.codPaciente
		left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento		
        where a.codOrganizacao=' . $codOrganizacao . ' and a.dataEncerramento is null and (d.codDepartamento=13 or la.codLocalAtendimento=0) and a.dataCriacao >= ADDDATE(NOW(), INTERVAL -8 HOUR) and a.codTipoAtendimento in(1) and a.codStatus in(0,12,13) and a.legado=0
		order by a.dataCriacao asc');


		return $query->getResult();
	}
}
