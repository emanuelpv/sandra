<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class PontoEletronicoModel extends Model
{

	protected $table = 'sis_pontoeletronico';
	protected $primaryKey = 'codPontoEletronico ';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codOrganizacao', 'codPessoa', 'dataCriacao', 'imagem'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;


	public function pegaTudo()
	{
		$query = $this->db->query('select * from sis_pontoeletronico');
		return $query->getRow();
	}

}
