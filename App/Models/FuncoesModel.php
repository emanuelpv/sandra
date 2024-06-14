<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class FuncoesModel extends Model
{

	protected $table = 'sis_funcoes';
	protected $primaryKey = 'codFuncao';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codOrganizacao', 'dataCriacao', 'descricaoFuncao', 'dataAtualizacao', 'siglaFuncao'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;


	public function pegaFuncoes()
	{
		$query = $this->db->query('select * from ' . $this->table . ' order by  ordenacaoFuncao asc');
		return $query->getResult();
	}



	public function pegaFuncao($codFuncao)
	{
		$csrf_hash = csrf_hash();
		$query = $this->db->query('select *,"'.$csrf_hash.'" as csrf_hash from ' . $this->table . ' where codFuncao=' . $codFuncao . '  order by  ordenacaoFuncao asc');
		return $query->getRow();
	}
}
