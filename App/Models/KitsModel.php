<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class KitsModel extends Model
{

	protected $table = 'sis_kits';
	protected $primaryKey = 'codKit';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['dataCriacao', 'dataAtualizacao', 'codAutor', 'codStatus', 'descricaoKit', 'disponivel', 'descricaoAlternativa', 'valorun', 'codTipo'];
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
		$query = $this->db->query('select * from sis_kits k 
		left join sis_kitstipo kt on kt.codTipo = k.codTipo');
		return $query->getResult();
	}

	public function listaDropDownTipos()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select codTipo as id, DescricaoTipo as text from sis_kitstipo');
		return $query->getResult();
	}

	public function listaDropDownItensFarmacia()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select codItem as id, descricaoItem as text from sis_itensfarmacia');
		return $query->getResult();
	}
	public function pegaPorCodigo($codKit)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codKit = "' . $codKit . '"');
		return $query->getRow();
	}

	public function pegaPorNomeKit($descricaoKit)
	{
		$query = $this->db->query('select * from sis_kits where descricaoKit = "' . $descricaoKit . '"');
		return $query->getRow();
	}
	public function listaDropDownKits()
	{
		$query = $this->db->query('select codKit as id, descricaoKit as text from sis_kits where descricaoKit is not null');
		return $query->getResult();
	}
}
