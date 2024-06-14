<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class ConfiguracaoGlobalModel extends Model
{

	protected $table = 'configuracaoGlobal';
	protected $primaryKey = 'codConfiguracaoGlobal';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['tokenVideochamada', 'urlVideochamada', 'permiteAutocadastro'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;

	public function pegaConfiguracaoGlobal()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from ' . $this->table);
		return $query->getRow();
	}


}
