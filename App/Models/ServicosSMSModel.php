<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class ServicosSMSModel extends Model
{

	protected $table = 'sis_servicossms';
	protected $primaryKey = 'codServicoSMS';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['creditos', 'conta', 'codOrganizacao', 'codProvedor', 'token', 'statusSMS'];
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
		$query = $this->db->query('select * from sis_servicossms where codOrganizacao='.$codOrganizacao);
		return $query->getResult();
	}


	public function pegaAtivoComCreditos()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from sis_servicossms where statusSMS=1 and creditos>0 and codOrganizacao='.$codOrganizacao);
		return $query->getResult();
	}


	public function pegaPorCodigo($codServicoSMS)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from ' . $this->table . ' where codOrganizacao='.$codOrganizacao .' and codServicoSMS = "' . $codServicoSMS . '"');
		return $query->getRow();
	}
}
