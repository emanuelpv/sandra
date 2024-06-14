<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class TermosModel extends Model
{

	protected $table = 'sis_termos';
	protected $primaryKey = 'codTermo';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codOrganizacao', 'dataCriacao', 'dataAtualizacao', 'codPessoa', 'assunto', 'termo', 'codStatus'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;


	public function pegaTudo()
	{
		if (session()->codOrganizacao == NULL) {
			$codOrganizacao = session()->codOrganizacao;
		} else {
			$configuracao = config('App');
			session()->set('codOrganizacao', $configuracao->codOrganizacao);
			$codOrganizacao = $configuracao->codOrganizacao;
		}

		$query = $this->db->query('select * from sis_termos t left join sis_pessoas p on p.codPessoa=t.codPessoa');
		return $query->getResult();
	}

	public function pegaPorCodigo($codTermo)
	{
		$query = $this->db->query('select * from sis_termos t left join sis_pessoas p on p.codPessoa=t.codPessoa where t.codTermo = "' . $codTermo . '"');
		return $query->getRow();
	}
}
