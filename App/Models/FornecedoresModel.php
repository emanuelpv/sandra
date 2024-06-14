<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class FornecedoresModel extends Model
{

	protected $table = 'ges_fornecedores';
	protected $primaryKey = 'codFornecedor';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['inscricao', 'codTipo', 'codNatureza', 'nomeFantasia', 'razaoSocial', 'endereco', 'cidade', 'codEstadoFederacao', 'cep', 'contatos', 'email', 'website', 'ocspsa', 'simples', 'mnt', 'observacoes', 'dataCriacao', 'dataAtualizacao', 'codAutor'];
	protected $useTimestamps = false;
	protected $createdField = 'created_at';
	protected $updatedField = 'updated_at';
	protected $deletedField = 'deleted_at';
	protected $validationRules = [];
	protected $validationMessages = [];
	protected $skipValidation = true;


	public function pegaTudo()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from ges_fornecedores');
		return $query->getResult();
	}

	public function pegaPorCodigo($codFornecedor)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codFornecedor = "' . $codFornecedor . '"');
		return $query->getRow();
	}

	public function listaDropDownTipoFornecedor()
	{
		$query = $this->db->query('select codTipo as id, descricao as text from sis_tipofornecedor where descricao is not null ');
		return $query->getResult();
	}

	public function listaDropDownFornecedores()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select codFornecedor as id, concat(inscricao," - ", nomeFantasia, " - " ,razaoSocial) as text from ges_fornecedores  where concat(inscricao," - ", nomeFantasia, " - " ,razaoSocial) is not null');
		return $query->getResult();
	}



}