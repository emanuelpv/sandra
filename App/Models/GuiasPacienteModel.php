<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class GuiasPacienteModel extends Model
{

	protected $table = 'fus_guias';
	protected $primaryKey = 'codGuia';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codOrganizacao','informacoesComplementares', 'codStatus', 'codPaciente', 'valorTotal', 'nomeBeneficiario', 'dataCriacao', 'dataAtualizacao', 'codPlano', 'numeroGuia'];
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
		$query = $this->db->query('select g.*,gs.descricao as descricaoStatus,gs.corStatus
		from fus_guias g
		join fus_guiasstatus gs on gs.codStatus=g.codStatus
		where g.codStatus<>10
		order by g.dataCriacao desc');
		return $query->getResult();
	}
	public function canceladas()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select g.*,gs.descricao as descricaoStatus,gs.corStatus
		from fus_guias g
		join fus_guiasstatus gs on gs.codStatus=g.codStatus
		where g.codStatus=10
		order by g.dataAtualizacao desc');
		return $query->getResult();
	}

	public function pegaPorCodigo($codGuia)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codGuia = "' . $codGuia . '"');
		return $query->getRow();
	}



	public function listaStatusGuia()
	{
		$query = $this->db->query('select codStatus as id, descricao as text from fus_guiasstatus');
		return $query->getResult();
	}
}
