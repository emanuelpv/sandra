<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class CargosModel extends Model
{

	protected $table = 'sis_cargos';
	protected $primaryKey = 'codCargo';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codOrganizacao', 'dataCriacao', 'descricaoCargo', 'dataAtualizacao', 'siglaCargo'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;


	public function pegaCargos()
	{
		if (session()->codOrganizacao == NULL) {
			$configuracao = config('App');
			session()->set('codOrganizacao', $configuracao->codOrganizacao);
			$codOrganizacao = $configuracao->codOrganizacao;
		} else {
			$codOrganizacao = session()->codOrganizacao;
		}



		$query = $this->db->query('select * from ' . $this->table . ' where codOrganizacao = ' . $codOrganizacao . ' order by  ordenacaoCargo asc');
		return $query->getResult();
	}

	public function listaDropDownCargos()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select codCargo as id, siglaCargo as text from sis_cargos where codOrganizacao = ' . $codOrganizacao . ' order by codCargo asc');
		return $query->getResult();
	}


	public function pegaCargosPorCodigo($codCargo)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from ' . $this->table . ' where codOrganizacao = ' . $codOrganizacao . ' and codCargo=' . $codCargo . ' order by  ordenacaoCargo asc');
		return $query->getRow();
	}

	public function pegaCargosPorOrganizacao($codOrganizacao)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from ' . $this->table . ' where codOrganizacao=' . $codOrganizacao . ' order by  ordenacaoCargo asc');
		return $query->getRow();
	}

	public function pegaCargosPorOrganizacaoECargo($codOrganizacao, $codCargo)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codCargo=' . $codCargo . ' and codOrganizacao=' . $codOrganizacao . ' order by  ordenacaoCargo asc');
		return $query->getRow();
	}
}
