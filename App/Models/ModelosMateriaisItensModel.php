<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class ModelosMateriaisItensModel extends Model
{

	protected $table = 'amb_modelosmateriaisitens';
	protected $primaryKey = 'codItemModelo';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codModelo', 'codMaterial', 'qtde','codStatus', 'observacao', 'codAutor', 'dataCriacao', 'dataAtualizacao'];
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
		$query = $this->db->query('select * from amb_modelosmateriaisitens');
		return $query->getResult();
	}



	public function pegaPorCodigo($codModelo)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codModelo = "' . $codModelo . '"');
		return $query->getRow();
	}

	

	public function pegaPorCodModelo($codModelo)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codModelo = "' . $codModelo . '"');
		return $query->getResult();
	}	
	
	public function removeMateriais($codModelo)
	{
		$query = $this->db->query('delete from ' . $this->table . ' where codModelo = "' . $codModelo . '"');
		return true;
	}
}
