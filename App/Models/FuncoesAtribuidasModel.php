<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class FuncoesAtribuidasModel extends Model
{

	protected $table = 'sis_funcoesatribuidas';
	protected $primaryKey = 'codPessoaFuncao';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codPessoaFuncao','codOrganizacao', 'codPessoa', 'codFuncao', 'dataInicio', 'dataEncerramento', 'dataCriacao', 'dataAtualizacao'];
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
		$query = $this->db->query('select fa.*, p.nomeExibicao,f.descricaoFuncao from sis_funcoesatribuidas fa 
		left join sis_pessoas p on fa.codPessoa = p.codPessoa
		left join sis_funcoes f on fa.codFuncao=f.codFuncao where fa.codOrganizacao = ' . $codOrganizacao);
		return $query->getResult();
	}

	public function pegaTudoPorPessoa($codPessoa)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select fa.*, p.nomeExibicao,f.descricaoFuncao from sis_funcoesatribuidas fa 
		left join sis_pessoas p on fa.codPessoa = p.codPessoa
		left join sis_funcoes f on fa.codFuncao=f.codFuncao where fa.codPessoa = '.$codPessoa.' and fa.codOrganizacao = ' . $codOrganizacao);
		return $query->getResult();
	}

	public function pegaPorCodigo($codPessoaFuncao)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codPessoaFuncao = "' . $codPessoaFuncao . '"');
		return $query->getRow();
	}
}
