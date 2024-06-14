<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class RequisicaoModel extends Model
{

	protected $table = 'ges_requisicao';
	protected $primaryKey = 'codRequisicao';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['numeroRequisicao', 'ano', 'descricao', 'codTipoRequisicao', 'codClasseRequisicao', 'dataAtualizacao', 'dataRequisicao', 'dataCriacao', 'valorTotal', 'codDepartamento', 'matSau', 'carDisp', 'codAutor', 'codAutorUltAlteracao'];
	protected $useTimestamps = false;
	protected $createdField = 'created_at';
	protected $updatedField = 'updated_at';
	protected $deletedField = 'deleted_at';
	protected $validationRules = [];
	protected $validationMessages = [];
	protected $skipValidation = true;


	public function pegaTudo()
	{


		$codTipoRequisicao = session()->filtroRequisicao['codTipoRequisicao'];
		$codClasseRequisicao = session()->filtroRequisicao['codClasseRequisicao'];
		$codStatusRequisicao = session()->filtroRequisicao['codStatusRequisicao'];
		$periodoRequisicoes = session()->filtroRequisicao['periodoRequisicoes'];
		$codDepartamento = session()->filtroRequisicao['codDepartamento'];
		$palarvaChave = session()->filtroRequisicao['palarvaChave'];
		$codResponsavel = session()->filtroRequisicao['codResponsavel'];
		$codFornecedor = session()->filtroRequisicao['codFornecedor'];



		if (session()->filtroRequisicao !== NULL and session()->filtroRequisicao !== "null" and session()->filtroRequisicao !== '') {


			$filtro = ' r.dataRequisicao > "2000-01-01"';
			//$filtro = " r.dataRequisicao > NOW() - INTERVAL 60 DAY";

			if ($periodoRequisicoes !== NULL and $periodoRequisicoes !== "null" and $periodoRequisicoes !== '') {

				if ($periodoRequisicoes > 1) {
					$filtro = ' r.dataRequisicao > NOW() - INTERVAL ' . $periodoRequisicoes . ' DAY';
				} else {
					$filtro = ' r.dataRequisicao > "2000-01-01"';
				}
			}



			if ($codDepartamento !== NULL and $codDepartamento !== 'null' and $codDepartamento !== ''  and $codDepartamento > 1) {
				$filtro .= ' and r.codDepartamento in(' . $codDepartamento . ')';
			}

			if ($codTipoRequisicao !== NULL and $codTipoRequisicao !== "null" and $codTipoRequisicao !== "") {

				$filtro .= ' and r.codTipoRequisicao in(' . $codTipoRequisicao . ')';
			}

			if ($codClasseRequisicao !== NULL and $codClasseRequisicao !== "null"  and $codClasseRequisicao !== "") {

				$filtro .= ' and r.codClasseRequisicao in(' . $codClasseRequisicao . ')';
			}

			if ($codResponsavel !== NULL and $codResponsavel !== "null" and $codResponsavel !== "") {

				$filtro .= ' and r.codAutor in(' . $codResponsavel . ')';
			}

			if ($codStatusRequisicao !== NULL and $codStatusRequisicao !== "null" and $codStatusRequisicao !== "") {

				$filtro .= ' and r.codStatusRequisicao in(' . $codStatusRequisicao . ')';
			}

			if ($codFornecedor !== NULL and $codFornecedor !== 'null' and $codFornecedor !== "") {

				$filtro .= ' and r.codRequisicao in(
					select rr.codRequisicao from ges_requisicao rr 
				left join ges_itensrequisicao ir on ir.codRequisicao=rr.codRequisicao
				left join ges_orcamentos o on o.codRequisicaoItem=ir.codRequisicaoItem
				where o.codFornecedor="' . $codFornecedor . '")';
			}


			if ($palarvaChave !== NULL and $palarvaChave !== "") {

				$itens = explode(";", $palarvaChave);

				$stringelect = "(";
				$requisicoesPChave = "";

				$x = 0;
				foreach ($itens as $item) {
					$x++;
					if ($x == 1) {
						$stringelect .= ' descricao like "%' . $item . '%"';
					} else {
						$stringelect .= ' or descricao like "%' . $item . '%"';
					}
				}
				$stringelect .= ")";

				$pChave = $this->db->query('select * from ges_itensrequisicao where codRequisicaoItem>0 and ' . $stringelect);
				$resultadoPChave = $pChave->getResult();
				$x = 0;
				foreach ($resultadoPChave as $resultado) {
					$x++;
					if ($x == 1) {
						$requisicoesPChave .= $resultado->codRequisicao;
					} else {
						$requisicoesPChave .=  "," . $resultado->codRequisicao;
					}
				}
				if (count($resultadoPChave) > 0) {
					$filtro .= ' and r.codRequisicao in(' . $requisicoesPChave . ')';
				}else{
					$filtro .= ' and r.codRequisicao in(-1)'; //não vei nada no array
				}
			}




			$query = $this->db->query('select *
		from ges_requisicao r
		left join sis_departamentos d on d.codDepartamento=r.codDepartamento
		left join ges_classerequisicao c on c.codClasseRequisicao=r.codClasseRequisicao
		left join ges_tiporequisicao t on t.codTipoRequisicao=r.codTipoRequisicao
		left join ges_statusrequisicao s on s.codStatusRequisicao=r.codStatusRequisicao
		left join sis_pessoas p on p.codPessoa=r.codAutor
		where ' . $filtro . '
		order by r.codRequisicao desc,  r.codDepartamento asc');
			return $query->getResult();
		} else {
			$query = $this->db->query('select *
		from ges_requisicao r
		left join sis_departamentos d on d.codDepartamento=r.codDepartamento
		left join ges_classerequisicao c on c.codClasseRequisicao=r.codClasseRequisicao
		left join ges_tiporequisicao t on t.codTipoRequisicao=r.codTipoRequisicao
		left join ges_statusrequisicao s on s.codStatusRequisicao=r.codStatusRequisicao
		left join sis_pessoas p on p.codPessoa=r.codAutor
		where r.codDepartamento=' . session()->codDepartamento . '
		order by r.codRequisicao desc,  r.codDepartamento asc');
			return $query->getResult();
		}
	}


	public function verificaPaassexEmElaboracao($codDepartamento)
	{
		$query = $this->db->query('select *
		from ges_requisicao r where r.codDepartamento="'.$codDepartamento.'" and r.codTipoRequisicao=70 and YEAR(r.dataRequisicao)=YEAR(NOW()) limit 1');
		return $query->getRow();
	}
	public function paassex()
	{

		$codDepartamento = session()->codDepartamento;
		$codPessoa = session()->codPessoa;

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select *
		from ges_requisicao r
		left join sis_departamentos d on d.codDepartamento=r.codDepartamento
		left join ges_classerequisicao c on c.codClasseRequisicao=r.codClasseRequisicao
		left join ges_tiporequisicao t on t.codTipoRequisicao=r.codTipoRequisicao
		left join ges_statusrequisicao s on s.codStatusRequisicao=r.codStatusRequisicao
		left join sis_pessoas p on p.codPessoa=r.codAutor
		where r.codTipoRequisicao=70 and (r.codDepartamento = "' . $codDepartamento . '" or r.codAutor="' . $codPessoa . '")
		order by r.codRequisicao desc,  r.codDepartamento asc');
		return $query->getResult();
	}
	public function itensPAASSEx()
	{

		$codDepartamento = session()->codDepartamento;
		$codPessoa = session()->codPessoa;

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select c.descricaoCatmat,r.codRequisicao,r.descricao as descricaoRequisicao,d.descricaoDepartamento,tr.AbrevTipoRequisicao as tipoRequisicao,descricaoClasseRequisicao as classeRequisicao,r.valorTotal, sr.descricaoStatusRequisicao as status,p.nomeExibicao, ir.descricao as item, ir.valorUnit,ir.qtde_sol, ir.valorTotal,
		tm.descricaoTipoMaterial, ir.prioridade,u.descricaoUnidade,ir.obs as justificativaItem, ir.codCat as CatMat
		FROM ges_requisicao r 
		left join ges_itensrequisicao ir on ir.codRequisicao=r.codRequisicao
		join sis_departamentos d on d.codDepartamento=r.codDepartamento
		join ges_tiporequisicao tr on tr.codTipoRequisicao=r.codTipoRequisicao
		join ges_classerequisicao cr on cr.codClasseRequisicao=r.codClasseRequisicao
		join ges_statusrequisicao sr on sr.codStatusRequisicao=r.codStatusRequisicao
		join sis_catmat c on c.catmat=ir.codCat
		join ges_tipomaterial tm on tm.codTipoMaterial=ir.tipoMaterial
		join sis_unidades u on u.codUnidade = ir.unidade
		join sis_pessoas p on p.codPessoa=r.codAutor
		where r.codTipoRequisicao=70  
		ORDER BY r.codRequisicao  DESC');
		return $query->getResult();
	}
	public function lookupUnidade($unidade)
	{

		$query = $this->db->query('select * from sis_unidades where descricaoUnidade="' . $unidade . '"');
		return $query->getRow();
	}


	public function verificaJaImportado($codRequisicao, $codCat)
	{

		$query = $this->db->query('select * from ges_itensrequisicao  where codRequisicao="' . $codRequisicao . '" and codCat="' . $codCat . '" ');
		return $query->getRow();
	}




	public function listaDropDownTiporequisicao()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select codTipoRequisicao as id,descricaoTipoRequisicao as text from ges_tiporequisicao where descricaoTipoRequisicao is not null order by descricaoTipoRequisicao');
		return $query->getResult();
	}
	public function listaDropDownStatusRequisicao()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select codStatusRequisicao as id,descricaoStatusRequisicao as text from ges_statusrequisicao where descricaoStatusRequisicao is not null order by descricaoStatusRequisicao');
		return $query->getResult();
	}
	public function listaDropDownTipoAcao()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select codTipoAcao as id,descricaoTipoAcao as text from ges_tipoacao where descricaoTipoAcao is not null');
		return $query->getResult();
	}
	public function listaDropDownClasserequisicao()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select codClasseRequisicao as id,descricaoClasseRequisicao as text from ges_classerequisicao where descricaoClasseRequisicao is not null
		');
		return $query->getResult();
	}


	public function pegaPorCodigo($codRequisicao)
	{

		$query = $this->db->query('select t.descricaoTipoRequisicao,r.codTipoRequisicao,d.codDepartamento,d.descricaoDepartamento, r.*,DATE_FORMAT(r.dataRequisicao, "%Y-%m-%d") as dataRequisicao, concat("Requisição nº ",r.numeroRequisicao,"/",r.ano) as tituloRequisicao 
		from ges_requisicao r 
		left join sis_departamentos d on d.codDepartamento = r.codDepartamento
		left join ges_tiporequisicao t on t.codTipoRequisicao=r.codTipoRequisicao
		where r.codRequisicao = "' . $codRequisicao . '"');
		return $query->getRow();
	}


	public function pegaFavorecido($codRequisicao)
	{
		$query = $this->db->query('select ir.codRequisicaoItem,MIN(o.valorUnitario)  as valorUnitario,o.codRequisicaoItem, f.inscricao, f.razaoSocial
		from ges_requisicao r 
		left join ges_itensrequisicao ir on ir.codRequisicao=r.codRequisicao
		left join ges_orcamentos o on ir.codRequisicaoItem=o.codRequisicaoItem
        left join ges_fornecedores f on f.codFornecedor = o.codFornecedor
		where r.codRequisicao =' . $codRequisicao . '
        group by o.codRequisicaoItem 
		having o.codRequisicaoItem is not null
		order by ir.nrRef asc, ir.codRequisicaoItem asc');
		return $query->getResult();
	}

	public function pegaRemetente($codPessoa)
	{
		$query = $this->db->query('select concat(p.nomeCompleto, "  - ", c.siglaCargo) as assinador from sis_pessoas p
		left join sis_cargos c on c.codCargo=p.codCargo where p.codPessoa=' . $codPessoa);
		return $query->getRow();
	}

	public function pegaAssinador($codDepartamento)
	{
		$query = $this->db->query('select concat(p.nomeCompleto, "  - ", c.siglaCargo) as assinador 
		from sis_pessoas p
		left join sis_departamentos d on d.codChefe = p.codPessoa
		left join sis_cargos c on c.codCargo=p.codCargo 
		where d.codDepartamento=' . $codDepartamento);
		return $query->getRow();
	}





	public function pegaHistoricoAcoes($codRequisicao)
	{
		$query = $this->db->query('select r.*, ta.descricaoTipoAcao, p.nomeExibicao
		from ges_historicoacoes r 
		left join ges_tipoacao ta on ta.codTipoAcao=r.codTipoAcao
		left join sis_pessoas p on p.codPessoa=r.codAutor
		where codRequisicao = "' . $codRequisicao . '"
		order by r.dataCriacao desc');
		return $query->getResult();
	}


	public function ultimoLancamentoAnoCorrente($codDepartamento)
	{
		$query = $this->db->query('select * from  ges_requisicao where codDepartamento = "' . $codDepartamento . '" and ano=YEAR(NOW()) order by codRequisicao desc limit 1');
		return $query->getRow();
	}



	public function pegaInformacoesComplemetares($codRequisicao)
	{
		$query = $this->db->query('select * from ges_inforcomplementares where codRequisicao = "' . $codRequisicao . '"');
		return $query->getResult();
	}
	public function pegaItensRequisicoes($codRequisicao)
	{
		$query = $this->db->query('select * from ges_itensrequisicao where codRequisicao = "' . $codRequisicao . '"');
		return $query->getResult();
	}

	public function pegaOrcamentos($codRequisicaoItem)
	{
		$query = $this->db->query('select * from ges_orcamentos where codRequisicaoItem = "' . $codRequisicaoItem . '"');
		return $query->getResult();
	}


	public function removeInformacoesComplementares($codRequisicao)
	{
		if ($codRequisicao !== NULL and $codRequisicao !== "" and $codRequisicao !== " ") {
			$this->db->query('delete from ges_inforcomplementares where codRequisicao = "' . $codRequisicao . '"');
		}
		return true;
	}

	public function removeItensRequisicao($codRequisicao)
	{
		if ($codRequisicao !== NULL and $codRequisicao !== "" and $codRequisicao !== " ") {

			$this->db->query('delete from ges_orcamentos where codRequisicaoItem in(select codRequisicaoItem from ges_itensrequisicao where codRequisicao = "' . $codRequisicao . '")');

			$this->db->query('delete from ges_itensrequisicao where codRequisicao = "' . $codRequisicao . '"');
		}
		return true;
	}
}
