<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class PacientesAlergiaModel extends Model
{

	protected $table = 'sis_pacientesalergias';
	protected $primaryKey = 'codPacienteAlergia';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codPaciente', 'descricaoAlergenico', 'codTipoAlergenico', 'dataCriacao', 'codAutor'];
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
		$query = $this->db->query('select * from sis_pacientesalergias');
		return $query->getResult();
	}

	public function pegaPorCodigo($codPacienteAlergia)
	{
		$query = $this->db->query('select * from sis_pacientesalergias where codPacienteAlergia = "' . $codPacienteAlergia . '"');
		return $query->getRow();
	}
	public function listaDropDown()
	{
		$query = $this->db->query('select codTipoAlergenico as id, descricaoTipoAlergenico as text from sis_tiposalergenicos');
		return $query->getResult();
	}
}
