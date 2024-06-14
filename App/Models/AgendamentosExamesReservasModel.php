<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class AgendamentosExamesReservasModel extends Model
{

	protected $table = 'age_agendamentosexamesreservas';
	protected $primaryKey = 'codExameReserva';
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
		$query = $this->db->query('select ar.*,ars.*,pa.*,e.*,pe.nomeExibicao as nomeEspecialsta,DATE_FORMAT(ar.dataCriacao, "%d/%m/%Y %H:%i") as dataSolicitacao,
		TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade
		from age_agendamentosexamesreservas ar 
		left join sis_pacientes pa on pa.codPaciente=ar.codPaciente
		left join sis_especialidades e on e.codEspecialidade=ar.codEspecialidade
		left join sis_pessoas pe on pe.codPessoa=ar.codEspecialista
		left join age_agendamentosexamesreservasstatus ars on ars.codStatusReserva=ar.codStatus 
		');
		return $query->getResult();
	}

	public function pegaPorCodigo($codExameReserva)
	{
		$query = $this->db->query('
		select ar.*,pa.*,ars.*,e.*,pe.nomeExibicao as nomeEspecialsta,DATE_FORMAT(ar.dataCriacao, "%d/%m/%Y %H:%i") as dataSolicitacao,
		TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade
		from age_agendamentosexamesreservas ar 
		left join sis_pacientes pa on pa.codPaciente=ar.codPaciente
		left join sis_especialidades e on e.codEspecialidade=ar.codEspecialidade
		left join sis_pessoas pe on pe.codPessoa=ar.codEspecialista 
		left join age_agendamentosexamesreservasstatus ars on ars.codStatusReserva=ar.codStatus 
		where ar.codExameReserva = "' . $codExameReserva . '"');
		return $query->getRow();
	}

	public function atualizaStatusReserva($codPacienteMarcacao,$codEspecialidade)
	{
		$query = $this->db->query('
		update age_agendamentosexamesreservas
		set codStatus = 2
		where codStatus <> 2 and codEspecialidade='.$codEspecialidade.' and codPaciente = ' . $codPacienteMarcacao);
		return true;
	}

	public function verificaExistenciaMaisDeX($codPaciente)
	{
		$query = $this->db->query('
		select count(*) as total from age_agendamentosexamesreservas		
		where codStatus <> 2 and codPaciente='.$codPaciente);
		return $query->getRow()->total;
	}
	
	public function verificaExistencia($codPaciente,$codEspecialidade)
	{
		$query = $this->db->query('
		select * from age_agendamentosexamesreservas
		where codStatus <> 2 and codPaciente='.$codPaciente.' and codEspecialidade='.$codEspecialidade.' limit 1');
		return $query->getRow();
	}
}
