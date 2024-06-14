<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class OrcamentosModel extends Model
{

	protected $table = 'ges_orcamentos';
	protected $primaryKey = 'codOrcamento';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codPessoa','documento','dataOrcamento', 'codFornecedor', 'valorUnitario', 'codTipoOrcamento', 'codRequisicaoItem', 'dataCriacao', 'dataAtualizacao'];
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
		$query = $this->db->query('select * from ges_orcamentos');
		return $query->getResult();
	}

	public function orcamentosItem($codRequisicaoItem)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from ges_orcamentos o
		left join ges_fornecedores f on f.codFornecedor = o.codFornecedor
		left join ges_tipoorcamento t on t.codTipoOrcamento = o.codTipoOrcamento where o.codRequisicaoItem="' . $codRequisicaoItem.'"');
		return $query->getResult();
	}
	public function pegaPorCodigo($codOrcamento)
	{
		$query = $this->db->query('select o.*, f.razaoSocial from ges_orcamentos o join ges_fornecedores f on f.codFornecedor = o.codFornecedor where o.codOrcamento = "' . $codOrcamento . '"');
		//$query = $this->db->query('select * from ' . $this->table. ' where codOrcamento = "'.$codOrcamento.'"');
		return $query->getRow();
	}

	public function pegaDadosItem($codOrcamento)
	{
		$query = $this->db->query('select ir.metodoCalculo,ir.codRequisicaoItem,ir.valorUnit from ges_orcamentos o join ges_itensrequisicao ir on ir.codRequisicaoItem = o.codRequisicaoItem where o.codOrcamento = "' . $codOrcamento . '"');
		//$query = $this->db->query('select * from ' . $this->table. ' where codOrcamento = "'.$codOrcamento.'"');
		return $query->getRow();
	}


	public function calcularTotalGeralPorCodRequisicao($codRequisicao)
	{
		if ($codRequisicao !== NULL and $codRequisicao !== "" and $codRequisicao !== " ") {

			$query = $this->db->query('select sum(valorTotal) as valorTotal from ges_itensrequisicao where codRequisicao = "' . $codRequisicao . '"');
			$valorTotal = $query->getRow()->valorTotal;

			if ($valorTotal > 0) {
				$this->db->query('update ges_requisicao set valorTotal = ' . $valorTotal . ' where codRequisicao=' . $codRequisicao);
			}
			return true;
		}
	}



	public function calcularTotalGeralPorCodItem($codRequisicaoItem)
	{
		if ($codRequisicaoItem !== NULL and $codRequisicaoItem !== "" and $codRequisicaoItem !== " ") {

			$query = $this->db->query('select sum(valorTotal) as valorTotal from ges_itensrequisicao where codRequisicao in (select codRequisicao from ges_itensrequisicao where codRequisicaoItem="'.$codRequisicaoItem.'")');
			$valorTotal = $query->getRow()->valorTotal;

			if ($valorTotal > 0) {
				$this->db->query('update ges_requisicao set valorTotal = ' . $valorTotal . ' where codRequisicao in (select codRequisicao from ges_itensrequisicao where codRequisicaoItem="'.$codRequisicaoItem.'")');
			}
			return true;
		}
	}


	public function calcularValor($codRequisicaoItem, $metodoCalculo, $valorUnitario)
	{

		if ($codRequisicaoItem !== NULL and $codRequisicaoItem !== "" and $codRequisicaoItem !== " ") {

			//LEGENDA
			/*
			-1 = DELETAR
			0 = Valor Fixo
			1 = Média de preços
			2 = Menor preço
			3 = Maior preço
			*/





			//VALOR FIXO
			if ($metodoCalculo == 0) {
				$query = $this->db->query('select valorUnit as valor from ges_itensrequisicao where codRequisicaoItem=' . $codRequisicaoItem);
				$valor = $query->getRow()->valor;
				$query = $this->db->query('update ges_itensrequisicao set valorTotal = (valorUnit*qtde_sol) where codRequisicaoItem=' . $codRequisicaoItem);
				return $valor;
			}

			//VALOR MEDIA




			if ($metodoCalculo == 1) {
				$query = $this->db->query('select AVG(valorUnitario) as valor from ges_orcamentos where codRequisicaoItem=' . $codRequisicaoItem);

				$valor = $query->getRow()->valor;
				if ($valor == NULL or $valor == "") {
					$query = $this->db->query('update ges_itensrequisicao set valorTotal = (valorUnit*qtde_sol) where codRequisicaoItem=' . $codRequisicaoItem);
					return $valorUnitario;
				} else {
					$query = $this->db->query('update ges_itensrequisicao set valorUnit = ' . $valor . ',valorTotal = (' . $valor . '*qtde_sol) where codRequisicaoItem=' . $codRequisicaoItem);
					return $valor;
				}

			}






			//MENOR VALOR
			if ($metodoCalculo == 2) {
				$query = $this->db->query('select MIN(valorUnitario) as valor from ges_orcamentos where codRequisicaoItem=' . $codRequisicaoItem);

				$valor = $query->getRow()->valor;
				if ($valor == NULL or $valor == "") {
					$query = $this->db->query('update ges_itensrequisicao set valorTotal = (valorUnit*qtde_sol) where codRequisicaoItem=' . $codRequisicaoItem);
					return $valorUnitario;
				} else {
					$query = $this->db->query('update ges_itensrequisicao set valorUnit = ' . $valor . ',valorTotal = (' . $valor . '*qtde_sol) where codRequisicaoItem=' . $codRequisicaoItem);
					return $valor;
				}

			}



			//MAIOR VALOR
			if ($metodoCalculo == 3) {
				$query = $this->db->query('select MAX(valorUnitario) as valor from ges_orcamentos where codRequisicaoItem=' . $codRequisicaoItem);

				$valor = $query->getRow()->valor;
				if ($valor == NULL or $valor == "") {
					$query = $this->db->query('update ges_itensrequisicao set valorTotal = (valorUnit*qtde_sol) where codRequisicaoItem=' . $codRequisicaoItem);
					return $valorUnitario;
				} else {
					$query = $this->db->query('update ges_itensrequisicao set valorUnit = ' . $valor . ',valorTotal = (' . $valor . '*qtde_sol) where codRequisicaoItem=' . $codRequisicaoItem);
					return $valor;
				}

			}




		}

		return true;
	}
}