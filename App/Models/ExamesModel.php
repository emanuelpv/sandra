<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class ExamesModel extends Model
{

	protected $table = 'amb_exames';
	protected $primaryKey = 'codExame';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['dataMarcacao','horaChegada','inicioAtendimento','encerramentoAtendimento', 'observacoes','confirmou', 'chegou', 'codConfig', 'codOrganizacao', 'codPaciente', 'codEspecialista', 'codExameLista', 'codStatus', 'dataCriacao', 'dataAtualizacao', 'dataInicio', 'dataEncerramento', 'codAutor', 'protocolo', 'ordemAtendimento'];
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
		$query = $this->db->query('select * from amb_exames');
		return $query->getResult();
	}

	public function verificaExistenciaExame($codExameLista,$codEspecialista,$dataInicio)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from amb_exames where codExameLista='.$codExameLista.' and codEspecialista='.$codEspecialista.' and dataInicio="'.$dataInicio.'"');
		return $query->getRow();
	}

public function outrosContatosPorPaciente($codPaciente)
	{
		$query = $this->db->query('select * from sis_outroscontatos where codPaciente = ' . $codPaciente);
		return $query->getResult();
	}

	public function pegaPessoa($codPessoa)
	{
		$query = $this->db->query('select * from sis_pessoas where codPessoa = ' . $codPessoa);
		return $query->getRow();
	}

	public function pegaPorCodigo($codExame)
	{
		$codOrganicacao = session()->codOrganizacao;
		$query = $this->db->query('select TIMESTAMPDIFF (YEAR, pa.dataNascimento,CURDATE()) as idade, pa.nomeCompleto,pa.nomeCompleto as nomeCompletoPaciente, pa.codProntuario,a.*,e.descricaoExameLista,p.nomeExibicao,d.descricaoDepartamento,pa.emailPessoal as emailPessoalPaciente,pa.celular as celularPaciente,pa.nomeExibicao,a.protocolo
		from amb_exames a
		left join amb_examesconfig ac on ac.codConfig = a.codConfig
		left join age_agendamentosexameslista e on e.codExameLista = a.codExameLista
		left join sis_departamentos d on d.codDepartamento = ac.codLocal
		left join sis_pessoas p on p.codPessoa = a.codEspecialista
		left join sis_pacientes pa on pa.codPaciente = a.codPaciente
		 where a.codOrganizacao =' . $codOrganicacao . ' and a.codExame = "' . $codExame . '"');
		return $query->getRow();
	}

	public function comprovante($codExame)
	{
		$codOrganicacao = session()->codOrganizacao;
		$query = $this->db->query('select a.*,pp.nomeExibicao as autorMarcacao,e.descricaoExameLista,d.descricaoDepartamento,a.codExameLista,
		p.nomeExibicao as nomeEspecialista,pa.nomeCompleto,pa.codPaciente,pa.emailPessoal as emailPessoalPaciente,
		pa.nomeCompleto as nomePaciente, a.protocolo,pa.codProntuario,pa.codPlano
		from amb_exames a
		left join amb_examesconfig ac on ac.codConfig=a.codConfig
		left join age_agendamentosexameslista e on e.codExameLista = a.codExameLista
		left join sis_pessoas p on p.codPessoa = a.codEspecialista
		left join sis_pessoas pp on a.marcadoPor=pp.codPessoa	
		left join sis_pacientes pa on pa.codPaciente = a.codPaciente
		left join sis_departamentos d on d.codDepartamento = ac.codLocal
		where a.codExame = ' . $codExame . ' and a.codOrganizacao=' . $codOrganicacao);
		return $query->getRow();
	}

	public function verificaCadastroReserva($codExameLista)
	{
		$query = $this->db->query('select *
		from age_agendamentosexameslista where codExameLista=' . $codExameLista);
		return $query->getRow();
	}

	public function examesPorexameLista()
	{

		$codExameLista = session()->filtroExameLista["codExameLista"];
		$dataInicio = session()->filtroExameLista["dataInicio"];
		$dataEncerramento = session()->filtroExameLista["dataEncerramento"];
		$codEspecialista = session()->filtroExameLista["codEspecialista"];

		if ($codExameLista == NULL) {
			return 'noexameLista';
		}

		if ($codExameLista !== NULL and $codExameLista !== "") {
			$filtro = ' a.codExameLista = ' . $codExameLista;
		}
		if ($codEspecialista !== NULL and $codEspecialista !== "null" and $codEspecialista !== "0") {
			$filtro .= ' and a.codEspecialista = ' . $codEspecialista;
		}
		if ($dataInicio !== NULL and $dataInicio !== "") {

			if ($dataInicio < date('Y-m-d')) {
				$filtro .= ' and DATE_FORMAT(a.dataInicio, "%Y-%m-%d") >="' . date('Y-m-d') . '"';
			} else {
				$filtro .= ' and DATE_FORMAT(a.dataInicio, "%Y-%m-%d") >="' . $dataInicio . '"';
			}
		} else {
			$filtro .= ' and DATE_FORMAT(a.dataInicio, "%Y-%m-%d") >="' . date('Y-m-d') . '"';
		}
		if ($dataEncerramento !== NULL and $dataEncerramento !== "") {
			$filtro .= ' and  DATE_FORMAT(a.dataEncerramento, "%Y-%m-%d")  <= "' . $dataEncerramento . '"';
		}



		$query = $this->db->query('select a.*,e.*,agt.*,d.descricaoDepartamento,pa.codPlano,pa.codProntuario,p.nomeExibicao as nomeEspecialista,pa.nomeExibicao as nomePaciente,pa.fotoPerfil,p.codPessoa as codEspecialista,p.fotoPerfil as fotoPerfilEspecialista,pa.cpf, pa.celular,pa.emailPessoal
		from amb_exames a 
		left join amb_examesconfig ac on ac.codConfig=a.codConfig
		left join amb_examestipo agt on agt.codTipo=ac.codTipoExame
		left join age_agendamentosexameslista e on e.codExameLista=a.codExameLista
		left join sis_pessoas p on a.codEspecialista=p.codPessoa		
		left join sis_pacientes pa on a.codPaciente=pa.codPaciente
		left join sis_departamentos d on d.codDepartamento = ac.codLocal		
		where (ac.codStatusExame=1 or ac.codStatusExame is null) and a.codPaciente = 0 and a.codStatus = 0 and a.dataAtualizacao <= NOW() and a.dataInicio > date_add(NOW(),interval -120 minute)  and ' . $filtro . ' order by a.dataInicio asc');
		return $query->getResult();
	}


	public function proximosExames($codPaciente)
	{
		$codOrganicacao = session()->codOrganizacao;
		$query = $this->db->query('select es.*,e.codExame,el.descricaoExameLista,c.siglaCargo,p.nomeExibicao, "Exame" as Tipo , e.dataInicio
		from amb_exames e
		left join age_agendamentosexameslista el on el.codExameLista = e.codExameLista
		left join amb_examesstatus es on es.codStatus=e.codStatus
		left join sis_pessoas p on p.codPessoa = e.codEspecialista
		left join sis_pacientes pa on pa.codPaciente = e.codPaciente
		left join sis_cargos c on c.codCargo=p.codCargo and c.codOrganizacao=p.codOrganizacao  
		where e.dataInicio > NOW() and e.codOrganizacao =' . $codOrganicacao . ' and e.codPaciente = "' . $codPaciente . '" order by e.dataInicio desc limit 3');
		return $query->getResult();
	}

	public function cancelamentosExamesPorEspecialidade()
	{
		$codOrganizacao = session()->codOrganizacao;
		$codExameLista = session()->filtroExameLista["codExameLista"];
		$dataInicio = session()->filtroExameLista["dataInicio"];
		$dataEncerramento = session()->filtroExameLista["dataEncerramento"];
		$codEspecialista = session()->filtroExameLista["codEspecialista"];

		if ($codExameLista == NULL) {
			return 'noexameLista';
		}

		if ($codExameLista !== NULL and $codExameLista !== "") {
			$filtro = ' a.codExameLista = ' . $codExameLista;
		}
		if ($codEspecialista !== NULL and $codEspecialista !== "null" and $codEspecialista !== "0") {
			$filtro .= ' and a.codEspecialista = ' . $codEspecialista;
		}
		if ($dataInicio !== NULL and $dataInicio !== "") {

			if ($dataInicio < date('Y-m-d')) {
				$filtro .= ' and DATE_FORMAT(a.dataInicio, "%Y-%m-%d") >="' . date('Y-m-d') . '"';
			} else {
				$filtro .= ' and DATE_FORMAT(a.dataInicio, "%Y-%m-%d") >="' . $dataInicio . '"';
			}
		} else {
			$filtro .= ' and DATE_FORMAT(a.dataInicio, "%Y-%m-%d") >="' . date('Y-m-d') . '"';
		}
		if ($dataEncerramento !== NULL and $dataEncerramento !== "") {
			$filtro .= ' and  DATE_FORMAT(a.dataEncerramento, "%Y-%m-%d")  <= "' . $dataEncerramento . '"';
		}



		$query = $this->db->query('select es.nomeStatus,a.*,e.*,agt.*,d.descricaoDepartamento,pa.codPlano,pa.codProntuario,p.nomeExibicao as nomeEspecialista,pa.nomeExibicao as nomePaciente,pa.fotoPerfil,p.codPessoa as codEspecialista,p.fotoPerfil as fotoPerfilEspecialista,pa.cpf, pa.celular,pa.emailPessoal
		from amb_exames a 
		left join amb_examesconfig ac on ac.codConfig=a.codConfig
		left join amb_examestipo agt on agt.codTipo=ac.codTipoExame
		left join amb_examesstatus es on es.codStatus=a.codStatus
		left join age_agendamentosexameslista e on e.codExameLista=a.codExameLista
		left join sis_pessoas p on a.codEspecialista=p.codPessoa		
		left join sis_pacientes pa on a.codPaciente=pa.codPaciente
		left join sis_departamentos d on d.codDepartamento = ac.codLocal
		where a.codOrganizacao ='.$codOrganizacao.'  and ' . $filtro . ' order by a.dataInicio asc');
		return $query->getResult();
	}


	

	public function meusPacientesHoje()
	{
		$query = $this->db->query('select count(*) as total
		from amb_exames where codEspecialista='.session()->codPessoa.' and DATE_FORMAT(dataInicio, "%Y-%m-%d") = DATE_FORMAT(NOW(), "%Y-%m-%d")');
		return $query->getRow()->total;

	}
	public function marcados()
	{

		$codExameLista = session()->filtroExameLista["codExameLista"];
		$dataInicio = session()->filtroExameLista["dataInicio"];
		$dataEncerramento = session()->filtroExameLista["dataEncerramento"];
		$codEspecialista = session()->filtroExameLista["codEspecialista"];




		if ($codExameLista !== NULL and $codExameLista !== "") {
			$filtro = ' and a.codExameLista = ' . $codExameLista . '';
		}
		if ($codEspecialista !== NULL and $codEspecialista !== "null") {
			$filtro .= ' and a.codEspecialista = ' . $codEspecialista;
		}
		if ($dataInicio !== NULL and $dataInicio !== "") {
			$filtro .= ' and DATE_FORMAT(a.dataInicio, "%Y-%m-%d") >="' . $dataInicio . '"';
		} else {
			$filtro .= ' and DATE_FORMAT(a.dataInicio, "%Y-%m-%d") >= DATE_FORMAT(NOW(), "%Y-%m-%d")';
		}
		if ($dataEncerramento !== NULL and $dataEncerramento !== "") {
			$filtro .= ' and DATE_FORMAT(a.dataEncerramento, "%Y-%m-%d")  <= "' . $dataEncerramento . '"';
		}

		if ($dataInicio !== NULL and $dataInicio !== "" and ($dataEncerramento == NULL or $dataEncerramento == "")) {
			$filtro .= ' and DATE_FORMAT(a.dataInicio, "%Y-%m-%d") ="' . $dataInicio . '"';
		}



		$query = $this->db->query('select a.*,s.nomeStatus,e.*,p.nomeExibicao as nomeEspecialista,pa.codPlano,pa.codProntuario,pa.nomeExibicao as nomePaciente,pa.fotoPerfil,p.codPessoa as codEspecialista,p.fotoPerfil as fotoPerfilEspecialista,pa.cpf, pa.celular,pa.emailPessoal,pa.celular,TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade
		from amb_exames a 
		left join amb_examesstatus s on s.codStatus=a.codStatus 
		left join amb_examesconfig ac on a.codConfig=ac.codConfig 
		left join age_agendamentosexameslista e on e.codExameLista=a.codExameLista
		left join sis_pessoas p on a.codEspecialista=p.codPessoa		
		left join sis_pacientes pa on a.codPaciente=pa.codPaciente		
		where a.codPaciente <> 0 ' . $filtro . ' order by a.dataInicio asc,a.codEspecialista asc');
		return $query->getResult();
	}



	public function removeSlotsNaoAgendados($codConfig)
	{
		$query = $this->db->query('delete from amb_exames where codConfig=' . $codConfig . ' and codPaciente=0');
		return true;
	}

	public function meusExames()
	{


		$codPaciente = session()->codPaciente;

		$query = $this->db->query('select a.*,e.*,p.nomeExibicao as nomeEspecialista,d.descricaoDepartamento,e.descricaoExameLista,pa.nomeExibicao as nomePaciente,pa.fotoPerfil,p.codPessoa as codEspecialista,p.fotoPerfil as fotoPerfilEspecialista,pa.cpf, pa.celular,pa.emailPessoal,pa.celular
		from amb_exames a 
		left join amb_examesconfig ac on ac.codConfig=a.codConfig
		left join age_agendamentosexameslista e on e.codExameLista=a.codExameLista
		left join sis_pessoas p on a.codEspecialista=p.codPessoa		
		left join sis_pacientes pa on a.codPaciente=pa.codPaciente	
		left join sis_departamentos d on d.codDepartamento = ac.codLocal	
		where a.codPaciente =' . $codPaciente . '  order by a.dataInicio desc,a.codEspecialista asc');
		return $query->getResult();
	}

	
}
