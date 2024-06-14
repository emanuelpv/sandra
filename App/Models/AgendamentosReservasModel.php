<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class AgendamentosReservasModel extends Model
{

	protected $table = 'amb_agendamentosreservas';
	protected $primaryKey = 'codAgendamentoReserva';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'codOrganizacao', 'codPaciente', 'codEspecialidade', 'codEspecialista', 'codStatus', 'dataCriacao', 'dataAtualizacao', 'preferenciaDia', 'preferenciaHora', 'codAutor', 'protocolo'];
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
		$query = $this->db->query('select ar.*,ars.*,pa.*,e.*,pe.nomeExibicao as nomeEspecialsta,ar.dataCriacao as dataSolicitacao,
		TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade
		from amb_agendamentosreservas ar 
		left join sis_pacientes pa on pa.codPaciente=ar.codPaciente
		left join sis_especialidades e on e.codEspecialidade=ar.codEspecialidade
		left join sis_pessoas pe on pe.codPessoa=ar.codEspecialista
		left join amb_agendamentosreservasstatus ars on ars.codStatusReserva=ar.codStatus order by ar.codStatus asc, ar.dataCriacao asc
		');
		return $query->getResult();
	}


	public function pegaTudoPendentes()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select ar.*,ars.*,pa.*,e.*,pe.nomeExibicao as nomeEspecialsta,ar.dataCriacao as dataSolicitacao,
		TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade
		from amb_agendamentosreservas ar 
		left join sis_pacientes pa on pa.codPaciente=ar.codPaciente
		left join sis_especialidades e on e.codEspecialidade=ar.codEspecialidade
		left join sis_pessoas pe on pe.codPessoa=ar.codEspecialista
		left join amb_agendamentosreservasstatus ars on ars.codStatusReserva=ar.codStatus 
		where ar.codStatus<2 order by ar.codStatus asc, ar.dataCriacao asc
		');
		return $query->getResult();
	}
	public function pegaTudoResolvidos()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select ar.*,ars.*,pa.*,e.*,pe.nomeExibicao as nomeEspecialsta,ar.dataCriacao as dataSolicitacao,
		TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade
		from amb_agendamentosreservas ar 
		left join sis_pacientes pa on pa.codPaciente=ar.codPaciente
		left join sis_especialidades e on e.codEspecialidade=ar.codEspecialidade
		left join sis_pessoas pe on pe.codPessoa=ar.codEspecialista
		left join amb_agendamentosreservasstatus ars on ars.codStatusReserva=ar.codStatus
		where ar.codStatus=2 order by ar.codStatus asc, ar.dataCriacao asc
		');
		return $query->getResult();
	}
	public function minhasReservas()
	{
		$codPaciente = session()->codPaciente;

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select ar.*,ars.*,pa.*,e.*,pe.nomeExibicao as nomeEspecialsta,ar.dataCriacao as dataSolicitacao,
		TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade
		from amb_agendamentosreservas ar 
		left join sis_pacientes pa on pa.codPaciente=ar.codPaciente
		left join sis_especialidades e on e.codEspecialidade=ar.codEspecialidade
		left join sis_pessoas pe on pe.codPessoa=ar.codEspecialista
		left join amb_agendamentosreservasstatus ars on ars.codStatusReserva=ar.codStatus 
		where ar.codPaciente = ' . $codPaciente);
		return $query->getResult();
	}


	public function pegaPorCodigo($codAgendamentoReserva)
	{
		$query = $this->db->query('
		select ar.*,pa.*,ars.*,e.*,pe.nomeExibicao as nomeEspecialsta,DATE_FORMAT(ar.dataCriacao, "%d/%m/%Y %H:%i") as dataSolicitacao,
		TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade
		from amb_agendamentosreservas ar 
		left join sis_pacientes pa on pa.codPaciente=ar.codPaciente
		left join sis_especialidades e on e.codEspecialidade=ar.codEspecialidade
		left join sis_pessoas pe on pe.codPessoa=ar.codEspecialista 
		left join amb_agendamentosreservasstatus ars on ars.codStatusReserva=ar.codStatus 
		where ar.codAgendamentoReserva = "' . $codAgendamentoReserva . '"');
		return $query->getRow();
	}

	public function atualizaStatusReserva($codPacienteMarcacao, $codEspecialidade)
	{

		$verificaExistencia = $this->db->query('
		select * from amb_agendamentosreservas
		where codStatus <> 2 and codEspecialidade=' . $codEspecialidade . ' and codPaciente = ' . $codPacienteMarcacao);
		$cadastroReserva = $verificaExistencia->getRow();

		if ($cadastroReserva !== NULL) {

			$comentario = $this->db->query('
		INSERT INTO amb_agendamentosreservascomentarios (codContato, codAgendamentoReserva, comentario, dataCriacao, codPessoa, codPaciente) VALUES (NULL, "' . $cadastroReserva->codAgendamentoReserva . '", "Conseguiu marcar pelo sistema", CURRENT_TIMESTAMP, "' . session()->codPessoa . '", "' . $codPacienteMarcacao . '");');
			$atualizacao = $this->db->query('
		update amb_agendamentosreservas set codStatus = 2
		where codAgendamentoReserva="' . $cadastroReserva->codAgendamentoReserva . '"');
			return true;
		} else {
			return true;
		}
	}

	public function verificaExistenciaMaisDeX($codPaciente)
	{
		$query = $this->db->query('
		select count(*) as total from amb_agendamentosreservas		
		where codStatus <> 2 and codPaciente=' . $codPaciente);
		return $query->getRow()->total;
	}

	public function comentarios($codAgendamentoReserva)
	{
		$query = $this->db->query('
		select arc.* , pa.nomeExibicao as nomePaciente,pe.nomeExibicao as nomeColaborador  
		from amb_agendamentosreservascomentarios arc
		left join sis_pessoas pe on pe.codPessoa=arc.codPessoa	
		left join sis_pacientes pa on pa.codPaciente=arc.codPaciente	
		where arc.codAgendamentoReserva="' . $codAgendamentoReserva . '" order by codContato desc');
		return $query->getResult();
	}


	public function demandaReprimidaReserva()
	{
		$query = $this->db->query('
		select e.descricaoEspecialidade, count(*) as total FROM amb_agendamentosreservas ar 
		join sis_especialidades e on ar.codEspecialidade=e.codEspecialidade
		where ar.codStatus<2
		group by e.descricaoEspecialidade
		order by total desc');
		return $query->getResult();
	}
	public function verificaExistencia($codPaciente, $codEspecialidade)
	{
		$query = $this->db->query('
		select * from amb_agendamentosreservas
		where codStatus <> 2 and codPaciente=' . $codPaciente . ' and codEspecialidade=' . $codEspecialidade . ' limit 1');
		return $query->getRow();
	}
}
