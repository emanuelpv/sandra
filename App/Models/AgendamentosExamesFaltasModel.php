<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class AgendamentosExamesFaltasModel extends Model
{

	protected $table = 'age_agendamentosexamesfalta';
	protected $primaryKey = 'codExameFalta';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['dataInicioImpedimento','dataEncerramentoImpedimento','codAutor', 'codPaciente', 'codEspecialidade', 'codEspecialista', 'dataCriacao', 'dataAtualizacao', 'impedidoAgendar'];
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
		$query = $this->db->query('select af.*, pee.nomeExibicao as nomeEspecialista,e.descricaoEspecialidade,pa.nomeExibicao as nomePaciente, pe.nomeExibicao as nomeAutor
		from age_agendamentosexamesfalta af
		left join sis_pacientes pa on pa.codPaciente=af.codPaciente
		left join sis_pessoas pe on pe.codPessoa = af.codAutor
		left join sis_pessoas pee on pee.codPessoa = af.codEspecialista
		left join sis_especialidades e on e.codEspecialidade =af.codEspecialidade 
		');
		return $query->getResult();
	}

	public function pegaPorCodigo($codExameFalta)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codExameFalta = "' . $codExameFalta . '"');
		return $query->getRow();
	}
}
