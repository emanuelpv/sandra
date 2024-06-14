<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;
use App\Models\DepartamentosModel;
use App\Models\RequisicaoModel;
use App\Models\ItensRequisicaoModel;
use App\Models\HistoricoAcoesModel;
use App\Models\OrcamentosModel;
use App\Models\ItensModeloModel;
use App\Models\InformacoesComplementaresModel;

class Requisicao extends BaseController
{

	protected $RequisicaoModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->RequisicaoModel = new RequisicaoModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->DepartamentosModel = new DepartamentosModel();
		$this->ItensRequisicaoModel = new ItensRequisicaoModel();
		$this->PessoasModel = new PessoasModel();
		$this->HistoricoAcoesModel = new HistoricoAcoesModel();
		$this->ItensModeloModel = new ItensModeloModel();
		$this->OrcamentosModel = new OrcamentosModel();
		$this->InformacoesComplementaresModel = new InformacoesComplementaresModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation = \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao = $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{


		session()->remove('filtroRequisicao');

		$permissao = verificaPermissao('Requisicao', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "Requisicao"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller' => 'Requisicao',
			'title' => 'Requisição'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('requisicao', $data);
	}


	public function geraRequisicao($codRequisicao = NULL)
	{
		$response = array();
		$html = "";
		if ($codRequisicao == NULL) {
			$codRequisicao = 51;
		}
		$requisicao = $this->RequisicaoModel->pegaPorCodigo($codRequisicao);

		$dia = session()->cidade . '-' . session()->uf . ', ' . date('d', strtotime($requisicao->dataCriacao)) . ' de ' . nomeMesPorExtenso(date('m', strtotime($requisicao->dataCriacao))) . ' de ' . date('Y', strtotime($requisicao->dataCriacao)) . '.';


		$itens = $this->ItensRequisicaoModel->pegaItensRequisicao($codRequisicao);
		$informacoesComplementares = $this->InformacoesComplementaresModel->informacoesComplementaresRequisicao($codRequisicao);

		$y = 1;
		$complemento = '';
		foreach ($informacoesComplementares as $informacaoComplementar) {
			$y++;
			$complemento .= '
			
			<div style="margin-top:2px" class="row">
			' . $y . '. ' . mb_strtoupper($informacaoComplementar->descricaoCategoria, 'utf-8') . '
			</div>
			<div style="margin-top:2px" class="row">
				' . $informacaoComplementar->conteudo . '
			</div>
			
			';
		}


		$assinador = mb_strtoupper($this->RequisicaoModel->pegaAssinador(session()->codPessoa)->assinador, 'utf-8');

		$favorecidos = $this->RequisicaoModel->pegaFavorecido($codRequisicao);

		$listaFavorecidos = '';
		$z = 0;
		foreach ($favorecidos as $favorecido) {
			$z++;
			$listaFavorecidos .= '
			
			
			<div style="margin-top:2px" class="row">
			' . $z . '. ' . $favorecido->inscricao . ' | ' . $favorecido->razaoSocial . ' | valor R$ ' . $favorecido->valorUnitario . '
			</div>';
		}


		$dadosTabela = '';
		$x = 0;
		foreach ($itens as $item) {
			$x++;

			/*
			if($item->nrRef == NULL or $item->nrRef=="" or $item->nrRef ==0){

				if($item->obs !==NULL){
					$referencia=$item->nrRef;
				}else{
					$referencia='***';
				}
				
			}else{
				$referencia=$item->nrRef;
			}

*/
			$dadosTabela .=
				'
			<tr>
				<th style="text-align:center !important;" scope="row">' . $x . '</th>
				<td style="text-align:center !important;" class="align-middle">' . $item->nrRef . '</td>
				<td style="text-align:center !important;" class="align-middle">' . $item->descricao . '</td>
				<td style="text-align:center !important;" >' . $item->qtde_sol . '</td>
				<td style="text-align:center !important;" >' . $item->descricaoUnidade . '</td>
				<td style="text-align:center !important;" >R$ ' . $item->valorUnit . '</td>
				<td style="text-align:center !important;" >R$ ' . $item->valorTotal . '</td>
			</tr>';
		}

		$html .= '
		
		<div class="row">
			Requisição nº ' . $requisicao->numeroRequisicao . '/' . $requisicao->ano . ' - ' . $requisicao->descricaoDepartamento . ' 
		</div>
		<div style="margin-left:220px" class="row justify-content-end">
				' . $dia . '
		</div>


		<div style="margin-top:2px" class="row">
			URGENTÍSSIMO
		</div>

		<div style="margin-top:6px" class="row">
				Do (a) Chefe do (a) ' . mb_convert_case($requisicao->descricaoDepartamento, MB_CASE_TITLE, 'UTF-8') . ' 
		</div>
		<div style="margin-top:5px" class="row">
			Ao Fiscal Administrativo 
		</div>


		<div class="row">
					<p style="margin-top:15px">Nos termos no contido no artigo 13 da Portaria Ministerial nr 305/95 (IG 12-02), solicito-vos providências junto ao Ordenador de Despesas no sentido de aprovar a aquisição de material ou contratação de serviços conforme tabela a seguir:</p>
		</div>
		<div style="margin-top:5px" class="row">
			1. OBJETIVO
		</div>
		<div style="margin-top:5px" class="row justify-content-center">


			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
					<th style="text-align:center !important;" scope="col">Nr</th>
					<th style="text-align:center !important;" scope="col">Item</th>
					<th style="text-align:center !important;" scope="col">Especificação do Item</th>
					<th style="text-align:center !important;" scope="col">Qtd</th>
					<th style="text-align:center !important;" scope="col">Und</th>
					<th style="text-align:center !important;" scope="col">Valor Estimado</th>
					<th style="text-align:center !important; width:5cm !important" scope="col">SubTotal</th>
					</tr>
				</thead>

				<tbody>
				' . $dadosTabela . '
			  	</tbody>
				  <tfoot>
				  <tr>
					 <th colspan="6" class="text-right">Total geral da requisição</th>
					 <th style="text-align:center !important;" colspan="1">R$ ' . $requisicao->valorTotal . '</th>
				  </tr>
			  </tfoot>


			</table>

		</div>';

		$html .= $complemento;

		$html .= '
		<div style="margin-top:5px" class="row">
			' . ($y + 1) . '. FAVORECIDO(S)
		</div>
		' . $listaFavorecidos . '
		
		';

		$html .= '
		<div style="margin-top:30px" class="row  justify-content-center">
		______________________________________________
	</div>
		<div id="assinadorDocumento" style="margin-top:0px" class="row  justify-content-center">
			
		</div>';


		$response['success'] = true;
		$response['html'] = $html;

		return $this->response->setJSON($response);
	}

	public function imprimirRequisicao($codRequisicao = NULL)
	{
		$response = array();
		$html = "";

		$codRequisicao = $this->request->getPost('codRequisicao');

		$requisicao = $this->RequisicaoModel->pegaPorCodigo($codRequisicao);

		$tipoRequisicao = 'a aquisição';
		$justificativa = '
		<div class="row">
					<p style="margin-top:15px">Nos termos no contido no artigo 13 da Portaria Ministerial nr 305/95 (IG 12-02), solicito-vos providências junto ao Ordenador de Despesas no sentido de aprovar ' . $tipoRequisicao . ' de material ou contratação de serviços conforme tabela a seguir:</p>
		</div>
		';

		if ($requisicao->codTipoRequisicao == 97) {
			$tipoRequisicao = 'a <b>anulação de empenho</b>';

			$justificativa = '
			<div class="row">
						<p style="margin-top:15px">
						Solicito autorização para que a seção de Aquisições, Licitações e Contratos proceda a ANULAÇÃO DA(S) NOTA(S) DE EMPENHO conforme tabela a seguir:
						</p>
			</div>
			';
		}

		if ($requisicao->codTipoRequisicao == 98) {
			$tipoRequisicao = 'a <b>anulação de requisição</b>';
			$justificativa = '
			<div class="row">
						<p style="margin-top:15px">Nos termos no contido no artigo 13 da Portaria Ministerial nr 305/95 (IG 12-02), solicito-vos providências junto ao Ordenador de Despesas no sentido de aprovar ' . $tipoRequisicao . ' de material ou contratação de serviços conforme tabela a seguir:</p>
			</div>
			';
		}


		$dia = session()->cidade . '-' . session()->uf . ', ' . date('d', strtotime($requisicao->dataRequisicao)) . ' de ' . nomeMesPorExtenso(date('m', strtotime($requisicao->dataRequisicao))) . ' de ' . date('Y', strtotime($requisicao->dataRequisicao)) . '.';


		$itens = $this->ItensRequisicaoModel->pegaItensRequisicao($codRequisicao);
		$informacoesComplementares = $this->InformacoesComplementaresModel->informacoesComplementaresRequisicao($codRequisicao);

		$y = 1;
		$complemento = '';
		foreach ($informacoesComplementares as $informacaoComplementar) {
			$y++;
			$complemento .= '
			
			<div style="margin-top:2px" class="row">
			' . $y . '. ' . mb_strtoupper($informacaoComplementar->descricaoCategoria, 'utf-8') . '
			</div>
			<div style="margin-top:2px" class="row">
				' . $informacaoComplementar->conteudo . '
			</div>
			
			';
		}


		$assinador = mb_strtoupper($this->RequisicaoModel->pegaAssinador($requisicao->codDepartamento)->assinador, 'utf-8');

		$favorecidos = $this->RequisicaoModel->pegaFavorecido($codRequisicao);

		$listaFavorecidos = '';
		$z = 0;
		foreach ($favorecidos as $favorecido) {
			$z++;
			$listaFavorecidos .= '
			
			
			<div style="margin-top:2px" class="row">
			' . $z . '. ' . $favorecido->inscricao . ' | ' . $favorecido->razaoSocial . ' | valor R$ ' . $favorecido->valorUnitario . '
			</div>';
		}


		$dadosTabela = '';
		$x = 0;
		foreach ($itens as $item) {
			$x++;

			/*
			if($item->nrRef == NULL or $item->nrRef=="" or $item->nrRef ==0){

				if($item->obs !==NULL){
					$referencia=$item->nrRef;
				}else{
					$referencia='***';
				}
				
			}else{
				$referencia=$item->nrRef;
			}

*/
			$dadosTabela .=
				'
			<tr>
				<th style="text-align:center !important;" scope="row">' . $x . '</th>
				<td style="text-align:center !important;" class="align-middle">' . $item->nrRef . '</td>
				<td style="text-align:center !important;" class="align-middle">' . $item->descricao . '</td>
				<td style="text-align:center !important;" >' . $item->qtde_sol . '</td>
				<td style="text-align:center !important;" >' . $item->descricaoUnidade . '</td>
				<td style="text-align:center !important;" >R$ ' . $item->valorUnit . '</td>
				<td style="text-align:center !important;" >R$ ' . $item->valorTotal . '</td>
			</tr>';
		}

		$html .= '
		
		<div class="row">
			Requisição nº ' . $requisicao->numeroRequisicao . '/' . $requisicao->ano . ' - ' . $requisicao->descricaoDepartamento . ' 
		</div>
		<div style="margin-left:220px" class="row justify-content-end">
				' . $dia . '
		</div>


		<!-- <div style="margin-top:2px" class="row">
			URGENTÍSSIMO
		</div> --!>

		<div style="margin-top:6px" class="row">
				Do (a) Chefe do (a) ' . mb_convert_case($requisicao->descricaoDepartamento, MB_CASE_TITLE, 'UTF-8') . ' 
		</div>
		<div style="margin-top:5px" class="row">
			Ao Ordenador de Despesas 
		</div>		
		<div style="margin-top:5px" class="row">
			Assunto: ' . mb_strtoupper($requisicao->descricaoTipoRequisicao, 'utf-8') . ' 
		</div>


		' . $justificativa . '
		<div style="margin-top:5px" class="row">
			1. OBJETIVO
		</div>
		<div style="margin-top:5px" class="row justify-content-center">


			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
					<th style="text-align:center !important;" scope="col">Nr</th>
					<th style="text-align:center !important;" scope="col">Item</th>
					<th style="text-align:center !important;" scope="col">Especificação do Item</th>
					<th style="text-align:center !important;" scope="col">Qtd</th>
					<th style="text-align:center !important;" scope="col">Und</th>
					<th style="text-align:center !important;" scope="col">Valor Estimado</th>
					<th style="text-align:center !important; width:5cm !important" scope="col">SubTotal</th>
					</tr>
				</thead>

				<tbody>
				' . $dadosTabela . '
			  	</tbody>
				  <tfoot>
				  <tr>
					 <th colspan="6" class="text-right">Total geral da requisição</th>
					 <th style="text-align:center !important;" colspan="1">R$ ' . $requisicao->valorTotal . '</th>
				  </tr>
			  </tfoot>


			</table>

		</div>';

		$html .= $complemento;

		$html .= '
		<div style="margin-top:5px" class="row">
			' . ($y + 1) . '. FAVORECIDO(S)
		</div>
		' . $listaFavorecidos . '
		
		';

		$html .= '
		<div style="margin-top:30px" class="row  justify-content-center">
		______________________________________________
	</div>
		<div style="margin-top:0px" class="row  justify-content-center">
			' . $assinador . '
		</div>';


		$response['success'] = true;
		$response['html'] = $html;

		return $this->response->setJSON($response);
	}



	public function listaDropDownDepartamentos()
	{

		$result = $this->DepartamentosModel->listaDropDown();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function listaDropDownAutor()
	{

		$result = $this->PessoasModel->listaDropDownPessoas();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}
	function usarModelo()
	{

		$response = array();

		$codItemModelo = $this->request->getPost('codItemModelo');
		$codRequisicao = $this->request->getPost('codRequisicao');

		if ($this->validation->check($codItemModelo, 'required|numeric')) {

			$item = $this->ItensModeloModel->pegaPorCodigo($codItemModelo);

			$fieldsItensRequisicao['codRequisicao'] = $codRequisicao;
			$fieldsItensRequisicao['nrRef'] = NULL;
			$fieldsItensRequisicao['descricao'] = $item->descricao;
			$fieldsItensRequisicao['codCat'] =  $item->codCat;
			$fieldsItensRequisicao['unidade'] = $item->unidade;
			$fieldsItensRequisicao['qtde_sol'] = NULL;
			$fieldsItensRequisicao['valorUnit'] = NULL;
			$fieldsItensRequisicao['valorTotal'] = NULL;
			$fieldsItensRequisicao['metodoCalculo'] = 1; //1 = Média de preços
			$fieldsItensRequisicao['cod_siasg'] = NULL;
			$fieldsItensRequisicao['tipoMaterial'] = $item->tipoMaterial;
			$fieldsItensRequisicao['obs'] = NULL;
			$fieldsItensRequisicao['dataCriacao'] = date('Y-m-d H:i');
			$fieldsItensRequisicao['dataAtualizacao'] = date('Y-m-d H:i');
			$fieldsItensRequisicao['codAutor'] = session()->codPessoa;
			$fieldsItensRequisicao['codAutorUltAlteracao'] = session()->codPessoa;


			$verificaJaImportado =  $this->RequisicaoModel->verificaJaImportado($codRequisicao, $item->codCat);
			if ($verificaJaImportado == NULL) {
				$codRequisicaoItem = $this->ItensRequisicaoModel->insert($fieldsItensRequisicao);
			} else {
				$response['success'] = false;
				$response['messages'] = 'Item já incluído na requisição';

				return $this->response->setJSON($response);
			}
		}

		$response['success'] = true;
		$response['messages'] = 'Item incluído com sucesso';
		return $this->response->setJSON($response);
	}


	function importarItens()
	{


		$response = array();

		$arquivo  = $this->request->getFile('file');


		$nomeArquivo = session()->cpf . '.' . $arquivo->getClientExtension();



		//VERIFICA TAMANHO
		/*
		$getClientMimeType = $arquivo->getClientMimeType();
		$response['success'] = false;
		$response['messages'] = $getClientMimeType;
		return $this->response->setJSON($response);
		*/

		if ($arquivo->getSize() > 10000000) {
			$response['success'] = false;
			$response['messages'] = "Desculpe, o arquivo é muito grande. Máximo de 10Mb.";
			return $this->response->setJSON($response);
		}


		//VERIFICA TIPO
		$getClientMimeType = $arquivo->getClientMimeType();
		if (
			$getClientMimeType !== 'text/csv'
		) {
			$response['success'] = false;
			$response['messages'] = "Desculpe, somente é permitido importar arquivos com a extensão *.CSV.";
			return $this->response->setJSON($response);
		}


		$arquivo->move(WRITEPATH . '../arquivos/temp/',  $nomeArquivo, true);


		$dados = array();
		$file = fopen(WRITEPATH . '../arquivos/temp/' . $nomeArquivo, 'r');
		while (($line = fgetcsv($file)) !== FALSE) {
			//$line is an array of the csv elements
			array_push($dados, $line);
		}
		fclose($file);



		$importados = 0;
		$jaExistiam = 0;
		for ($x = 0; $x <= count($dados); $x++) {

			//sequencial,id,nome,tipo,unidade

			if ($dados[$x][0] >= 1) {
				$saida = $dados[$x][1] . '' . $dados[$x][2] . '' . $dados[$x][3] . '' . $dados[$x][4];


				$codRequisicao = $this->request->getPost('codRequisicao');

				if ($this->validation->check($codRequisicao, 'required|numeric')) {

					$unidade = 1;
					if ($this->RequisicaoModel->lookupUnidade($dados[$x][4])->codUnidade !== NULL) {
						$unidade = $this->RequisicaoModel->lookupUnidade($dados[$x][4])->codUnidade;
					}

					$fieldsItensRequisicao['codRequisicao'] = $codRequisicao;
					$fieldsItensRequisicao['nrRef'] = NULL;
					$fieldsItensRequisicao['descricao'] = $dados[$x][2];
					$fieldsItensRequisicao['codCat'] = $dados[$x][1];
					$fieldsItensRequisicao['unidade'] = $unidade;
					$fieldsItensRequisicao['qtde_sol'] = NULL;
					$fieldsItensRequisicao['valorUnit'] = NULL;
					$fieldsItensRequisicao['valorTotal'] = NULL;
					$fieldsItensRequisicao['metodoCalculo'] = 1; //1 = Média de preços
					$fieldsItensRequisicao['cod_siasg'] = NULL;
					$fieldsItensRequisicao['tipoMaterial'] = NULL;
					$fieldsItensRequisicao['obs'] = NULL;
					$fieldsItensRequisicao['dataCriacao'] = date('Y-m-d H:i');
					$fieldsItensRequisicao['dataAtualizacao'] = date('Y-m-d H:i');
					$fieldsItensRequisicao['codAutor'] = session()->codPessoa;
					$fieldsItensRequisicao['codAutorUltAlteracao'] = session()->codPessoa;


					$verificaJaImportado =  $this->RequisicaoModel->verificaJaImportado($codRequisicao, $dados[$x][1]);
					if ($verificaJaImportado == NULL) {
						$codRequisicaoItem = $this->ItensRequisicaoModel->insert($fieldsItensRequisicao);
						$importados++;
					} else {
						$jaExistiam++;
					}
				}
			}
		}

		if ($jaExistiam > 0) {
			$response['messages'] = 'Operação realizada com sucesso. Foram importados ' . $importados . ' itens, sendo ' . $jaExistiam . ' itens com mesmo catMat já existiam na requisição.';
		} else {
			$response['messages'] = 'Operação realizada com sucesso. Foram importados ' . $importados . '.';
		}



		$response['dados'] = json_encode($saida);
		$response['success'] = true;

		return $this->response->setJSON($response);
	}

	public function getAll()
	{


		$response = array();

		$data['data'] = array();



		$PAASSEX = $this->request->getPost('PAASSEX');
		if ($PAASSEX == NULL) {

			$result = $this->RequisicaoModel->pegaTudo();
		} else {
			$result = $this->RequisicaoModel->paassex();
		}

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editrequisicao(' . $value->codRequisicao . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="Clonar"  onclick="clonarequisicao(' . $value->codRequisicao . ')"><i class="fa fa-clone"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="Imprimir"  onclick="imprimirRequisicao(' . $value->codRequisicao . ')"><i class="fa fa-print"></i></button>';

			$permissao = verificaPermissao('Requisicao', 'deletar');
			if ($permissao == 1 or $value->codPessoa == session()->codPessoa) {
				$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removerequisicao(' . $value->codRequisicao . ')"><i class="fa fa-trash"></i></button>';
			}


			$ops .= '</div>';

			if ($value->matSau == 1) {
				$matSau = 'Sim';
			} else {
				$matSau = 'Não';
			}
			if ($value->carDisp == 1) {
				$carDisp = 'Sim';
			} else {
				$carDisp = 'Não';
			}

			$dados = '
			
					<div style="margin-left:250px"  class="row">
						
					</div>
					
					<div style="font-size:14px;color:#000" class="row">
						Data Requisição: ' . date('d/m/Y', strtotime($value->dataRequisicao)) . '
					</div>
					<div style="font-size:14px;color:#000" class="row">
						Classe: ' . $value->descricaoClasseRequisicao . '
					</div>
					<div style="font-size:14px;color:#000" class="row">
						Tipo: ' . $value->descricaoTipoRequisicao . '
					</div>
					<div style="font-size:14px;color:#000" class="row">
						Criado Por: ' . $value->nomeExibicao . '
					</div>					
					<div style="font-size:14px;color:#000" class="row">
						Total: R$ ' . $value->valorTotal . '
					</div>
					<div style="font-size:14px;color:#000" class="row">
					Status: <a onclick="editrequisicao(' . $value->codRequisicao . ')" class="right badge badge-success">' . $value->descricaoStatusRequisicao . '</a>
					</div>
			
			';

			$data['data'][$key] = array(
				$value->numeroRequisicao . "." . $value->ano,
				$value->descricao,
				$value->descricaoDepartamento,
				$dados,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function itensPAASSEx()
	{


		$response = array();

		$data['data'] = array();



		$PAASSEX = $this->request->getPost('PAASSEX');

		$result = $this->RequisicaoModel->itensPAASSEx();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$value->codRequisicao,
				$value->descricaoRequisicao,
				$value->descricaoDepartamento,
				$value->tipoRequisicao,
				$value->classeRequisicao,
				$value->valorTotal,
				$value->status,
				$value->nomeExibicao,
				$value->descricaoCatmat,
				$value->item,
				$value->valorUnit,
				$value->qtde_sol,
				$value->valorTotal,
				$value->descricaoTipoMaterial,
				$value->prioridade,
				$value->descricaoUnidade,
				$value->justificativaItem,
				$value->CatMat,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codRequisicao');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->RequisicaoModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function pegaHistoricoAcoes()
	{
		$response = array();

		$id = $this->request->getPost('codRequisicao');

		if ($this->validation->check($id, 'required|numeric')) {

			$historicoAcoes = $this->RequisicaoModel->pegaHistoricoAcoes($id);

			$html = "";

			$datas = array();
			foreach ($historicoAcoes as $data) {

				if (!in_array(date('Y-m-d', strtotime($data->dataCriacao)), $datas)) {
					array_push($datas, date('Y-m-d', strtotime($data->dataCriacao)));
				}
			}


			foreach ($datas as $acao) {

				$html .= '
				<div class="time-label">
					<span class="bg-red">' . date('d', strtotime($acao)) . '' . nomeMesAbreviado(date('m', strtotime($acao))) . '' . date('Y', strtotime($acao)) . '</span>
				</div>';

				foreach ($historicoAcoes as $itensData) {

					if (date('Y-m-d', strtotime($acao)) == date('Y-m-d', strtotime($itensData->dataCriacao))) {

						$html .= '
						
						
						<div>
							<i class="fas fa-info bg-blue"></i>
							<div class="timeline-item">
								<span style="color:#fff;font-size:14px" class="time"><i class="fas fa-clock"></i> ' . date('H:i', strtotime($itensData->dataCriacao)) . '</span>
								<h3 class="timeline-header bg-secondary"><a> ' . $itensData->descricaoTipoAcao . '</a> </h3>
		
									<div class="timeline-body">
										' . $itensData->descricaoAcao . '
									</div>
									<div  style="color:green;font-size:12px" class="timeline-footer">
										' . $itensData->nomeExibicao . '
									</div>
							</div>
						</div>
						
						';
					}
				}
			}

			$response['html'] .= $html;




			'
			<div class="time-label">
				<span class="bg-red">10 Feb. 2014</span>
			</div>
		
			<div>
				<i class="fas fa-envelope bg-blue"></i>
					<div class="timeline-item">
						<span class="time"><i class="fas fa-clock"></i> 12:05</span>
						<h3 class="timeline-header"><a href="#">Support Team</a> sent
							you an email</h3>

						<div class="timeline-body">
							Etsy doostang zoodles disqus groupon greplin oooj voxy
							zoodles,
							weebly ning heekya handango imeem plugg dopplr jibjab,
							movity
							jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo
							kaboodle
							quora plaxo ideeli hulu weebly balihoo...
						</div>
						<div class="timeline-footer">
							<a class="btn btn-primary btn-sm">Read more</a>
							<a class="btn btn-danger btn-sm">Delete</a>
						</div>
					</div>
			</div>
			
			
			';


			return $this->response->setJSON($response);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function listaDropDownTipoRequisicao()
	{

		$result = $this->RequisicaoModel->listaDropDownTipoRequisicao();

		if ($result !== NULL) {

			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function salvaFiltro()
	{
		sleep(1);
		$response = array();
		$removerCaracteres = array("[", "]", '"');

		if ($this->request->getPost('arrayStatus') !== NULL) {
			$filtro["codStatusRequisicao"] = str_replace($removerCaracteres, '', json_encode($this->request->getPost('arrayStatus')));
		} else {
			$filtro["codStatusRequisicao"] = NULL;
		}

		if ($this->request->getPost('arrayTipo') !== NULL) {
			$filtro["codTipoRequisicao"] = str_replace($removerCaracteres, '', json_encode($this->request->getPost('arrayTipo')));
		} else {
			$filtro["codTipoRequisicao"] = NULL;
		}


		if ($this->request->getPost('arrayCodClasse') !== NULL) {
			$filtro["codClasseRequisicao"] = str_replace($removerCaracteres, '', json_encode($this->request->getPost('arrayCodClasse')));
		} else {
			$filtro["codClasseRequisicao"] = NULL;
		}


		if ($this->request->getPost('codFornecedor') !== NULL) {
			$filtro["codFornecedor"] = str_replace($removerCaracteres, '', json_encode($this->request->getPost('codFornecedor')));
		} else {
			$filtro["codFornecedor"] = NULL;
		}

		if ($this->request->getPost('palarvaChave') !== NULL) {
			$filtro["palarvaChave"] = str_replace($removerCaracteres, '', json_encode($this->request->getPost('palarvaChave'), JSON_UNESCAPED_UNICODE));
		} else {
			$filtro["palarvaChave"] = NULL;
		}


		if ($this->request->getPost('codResponsavel') !== NULL) {
			$filtro["codResponsavel"] = str_replace($removerCaracteres, '', json_encode($this->request->getPost('codResponsavel')));
		} else {
			$filtro["codResponsavel"] = NULL;
		}



		if ($this->request->getPost('arrayCodDepartamento') !== NULL) {
			$filtro["codDepartamento"] = str_replace($removerCaracteres, '', json_encode($this->request->getPost('arrayCodDepartamento')));
		} else {
			$filtro["codDepartamento"] = NULL;
		}


		if ($this->request->getPost('periodoRequisicoes') !== NULL) {
			$filtro["periodoRequisicoes"] = str_replace($removerCaracteres, '', json_encode($this->request->getPost('periodoRequisicoes')));
		} else {
			$filtro["periodoRequisicoes"] = NULL;
		}




		session()->set('filtroRequisicao', $filtro);


		$response['success'] = true;

		$response['messages'] = 'Filtro aplicado com sucesso';

		return $this->response->setJSON($response);
	}


	public function limpaFiltro()
	{
		$response = array();


		session()->remove('filtroRequisicao');



		$response['success'] = true;

		$response['messages'] = 'Filtro aplicado com sucesso';

		return $this->response->setJSON($response);
	}

	public function listaDropDownStatusRequisicao()
	{

		$result = $this->RequisicaoModel->listaDropDownStatusRequisicao();

		if ($result !== NULL) {

			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function listaDropDownTipoAcao()
	{

		$result = $this->RequisicaoModel->listaDropDownTipoAcao();

		if ($result !== NULL) {

			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function listaDropDownClasseRequisicao()
	{

		$result = $this->RequisicaoModel->listaDropDownClasseRequisicao();

		if ($result !== NULL) {

			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}



	public function add()
	{

		$response = array();


		$codDepartamento = $this->request->getPost('codDepartamento');

		//VERIFICA SE SETOR JÁ TEM UM PEDIDO DE PAASSEX EM ELABORAÇÃO	


		if ($this->request->getPost('codTipoRequisicao') == 70) {


			if ($this->request->getPost('codClasseRequisicao') !== '1') {

				$response['success'] = false;
				$response['messages'] = 'PAASSEX É UTILIZADO APENAS PARA AQUISIÇÕES DE MATERIAL PERMANENTE';
				return $this->response->setJSON($response);
			}

			$verificaPaassexEmElaboracao = $this->RequisicaoModel->verificaPaassexEmElaboracao($codDepartamento);



			if ($verificaPaassexEmElaboracao !== NULL) {

				$response['success'] = false;
				$response['messages'] = 'Este setor já possui uma requisição em aberto. Adicone todos os itens dentro da mesma requisição.';
				return $this->response->setJSON($response);
			}
		}


		if ($this->validation->check($codDepartamento, 'required|numeric')) {

			$dadosDepartamento = $this->DepartamentosModel->pegaDepartamento($codDepartamento);


			//VERIFICA O ÚLTIMO LANÇAMENTO DESTE ANO
			$ultimoLancamentoAnoCorrente = $this->RequisicaoModel->ultimoLancamentoAnoCorrente($codDepartamento);

			$numeroRequisicao = 1;
			$anoRequisicao = date('Y');
			if ($ultimoLancamentoAnoCorrente == NULL or $dadosDepartamento->SeqAno < date('Y')) {
				$atualizaDepartamento['SeqAno'] = date('Y');
				$atualizaDepartamento['seqRequisicao'] = 1;
				$this->DepartamentosModel->update($codDepartamento, $atualizaDepartamento);
			} else {
				if ($ultimoLancamentoAnoCorrente->ano == NULL or $ultimoLancamentoAnoCorrente->ano == '' or $ultimoLancamentoAnoCorrente->ano == ' ') {
					$anoRequisicao = date('Y');
				} else {
					$anoRequisicao = $ultimoLancamentoAnoCorrente->ano;
				}

				$atualizaDepartamento['seqRequisicao'] = $ultimoLancamentoAnoCorrente->numeroRequisicao + 1;
				$this->DepartamentosModel->update($codDepartamento, $atualizaDepartamento);

				$numeroRequisicao = $atualizaDepartamento['seqRequisicao'];
			}


			$fields['codRequisicao'] = $this->request->getPost('codRequisicao');
			$fields['numeroRequisicao'] = $numeroRequisicao;
			$fields['ano'] = $anoRequisicao;
			$fields['codRequisicao'] = $this->request->getPost('codRequisicao');
			$fields['dataRequisicao'] = date('Y-m-d H:i');
			$fields['dataCriacao'] = date('Y-m-d H:i');
			$fields['dataAtualizacao'] = date('Y-m-d H:i');
			$fields['descricao'] = $this->request->getPost('descricao');
			$fields['codTipoRequisicao'] = $this->request->getPost('codTipoRequisicao');
			$fields['codDepartamento'] = $codDepartamento;
			$fields['codClasseRequisicao'] = $this->request->getPost('codClasseRequisicao');
			$fields['matSau'] = $this->request->getPost('matSau');
			//$fields['carDisp'] = $this->request->getPost('carDisp');
			$fields['codAutor'] = session()->codPessoa;
			$fields['codAutorUltAlteracao'] = session()->codPessoa;


			$this->validation->setRules([
				'descricao' => ['label' => 'Descricao', 'rules' => 'required'],
				'codTipoRequisicao' => ['label' => 'CodTipoRequisicao', 'rules' => 'required|max_length[11]'],
				'codClasseRequisicao' => ['label' => 'codClasseRequisicao', 'rules' => 'required|max_length[11]'],
				'dataRequisicao' => ['label' => 'DataRequisicao', 'rules' => 'required|valid_date'],
				'matSau' => ['label' => 'MatSau', 'rules' => 'required|max_length[3]'],

			]);

			if ($this->validation->run($fields) == FALSE) {

				$response['success'] = false;
				$response['messages'] = $this->validation->listErrors();
			} else {

				if ($codRequisicao = $this->RequisicaoModel->insert($fields)) {

					$response['codRequisicao'] = $codRequisicao;
					$response['success'] = true;
					$response['messages'] = 'Informação inserida com sucesso';
				} else {

					$response['success'] = false;
					$response['messages'] = 'Erro na inserção!';
				}
			}
		}
		return $this->response->setJSON($response);
	}

	public function edit()
	{


		if ($this->request->getPost('codTipoRequisicao') == 70) {


			if ($this->request->getPost('codClasseRequisicao') !== '1') {

				$response['success'] = false;
				$response['messages'] = 'PAASSEX É UTILIZADO APENAS PARA AQUISIÇÕES DE MATERIAL PERMANENTE';
				return $this->response->setJSON($response);
			}
		}

		$response = array();
		$fields['codRequisicao'] = $this->request->getPost('codRequisicao');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['descricao'] = $this->request->getPost('descricao');
		$fields['codTipoRequisicao'] = $this->request->getPost('codTipoRequisicao');
		$fields['codClasseRequisicao'] = $this->request->getPost('codClasseRequisicao');
		$fields['dataRequisicao'] = $this->request->getPost('dataRequisicao');
		$fields['matSau'] = $this->request->getPost('matSau');
		//$fields['carDisp'] = $this->request->getPost('carDisp');
		//$fields['valorTotal'] = $this->request->getPost('valorTotal');
		//$fields['codAutor'] = session()->codPessoa;
		$fields['codAutorUltAlteracao'] = session()->codPessoa;


		$this->validation->setRules([
			'codRequisicao' => ['label' => 'codRequisicao', 'rules' => 'required|numeric'],
			'descricao' => ['label' => 'Descricao', 'rules' => 'required'],
			'codTipoRequisicao' => ['label' => 'CodTipoRequisicao', 'rules' => 'required|max_length[11]'],
			'codClasseRequisicao' => ['label' => 'codClasseRequisicao', 'rules' => 'required|max_length[11]'],
			'dataRequisicao' => ['label' => 'DataRequisicao', 'rules' => 'required|valid_date'],
			'matSau' => ['label' => 'MatSau', 'rules' => 'required|max_length[3]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->RequisicaoModel->update($fields['codRequisicao'], $fields)) {

				$response['success'] = true;
				$response['messages'] = 'Atualizado com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
			}
		}

		return $this->response->setJSON($response);
	}



	public function clonarRequisicao()
	{

		$response = array();
		$codRequisicaoClonado = $this->request->getPost('codRequisicao');

		$requisicao = $this->RequisicaoModel->pegaPorCodigo($codRequisicaoClonado);
		$numeroRequisicao = $requisicao->numeroRequisicao;
		$dia = date('d', strtotime($requisicao->dataCriacao)) . ' de ' . nomeMesPorExtenso(date('m', strtotime($requisicao->dataCriacao))) . ' de ' . date('Y', strtotime($requisicao->dataCriacao)) . '.';

		//CLONAR REQUISIÇÃO



		$dadosDepartamento = $this->DepartamentosModel->pegaDepartamento($requisicao->codDepartamento);


		//VERIFICA O ÚLTIMO LANÇAMENTO DESTE ANO
		$ultimoLancamentoAnoCorrente = $this->RequisicaoModel->ultimoLancamentoAnoCorrente($requisicao->codDepartamento);

		$numeroRequisicao = 1;
		$anoRequisicao = date('Y');
		if ($ultimoLancamentoAnoCorrente == NULL or $dadosDepartamento->SeqAno < date('Y')) {
			$atualizaDepartamento['SeqAno'] = date('Y');
			$atualizaDepartamento['seqRequisicao'] = 1;

			if ($requisicao->codDepartamento !== NULL and $requisicao->codDepartamento !== "" and $requisicao->codDepartamento !== " ") {
				$this->DepartamentosModel->update($requisicao->codDepartamento, $atualizaDepartamento);
			}
		} else {
			if ($ultimoLancamentoAnoCorrente->ano == NULL or $ultimoLancamentoAnoCorrente->ano == '' or $ultimoLancamentoAnoCorrente->ano == ' ') {
				$anoRequisicao = date('Y');
			} else {
				$anoRequisicao = $ultimoLancamentoAnoCorrente->ano;
			}

			$atualizaDepartamento['seqRequisicao'] = $ultimoLancamentoAnoCorrente->numeroRequisicao + 1;
			
			if ($requisicao->codDepartamento !== NULL and $requisicao->codDepartamento !== "" and $requisicao->codDepartamento !== " ") {

				$this->DepartamentosModel->update($requisicao->codDepartamento, $atualizaDepartamento);
			}
			$numeroRequisicao = $atualizaDepartamento['seqRequisicao'];
		}

		$fieldsRequisicao['numeroRequisicao'] = $numeroRequisicao;
		$fieldsRequisicao['ano'] = $anoRequisicao;
		$fieldsRequisicao['dataRequisicao'] = date('Y-m-d H:i');
		$fieldsRequisicao['dataCriacao'] = date('Y-m-d H:i');
		$fieldsRequisicao['dataAtualizacao'] = date('Y-m-d H:i');
		$fieldsRequisicao['descricao'] = $requisicao->descricao;
		$fieldsRequisicao['valorTotal'] = $requisicao->valorTotal;
		$fieldsRequisicao['codTipoRequisicao'] = $requisicao->codTipoRequisicao;
		$fieldsRequisicao['codDepartamento'] = $requisicao->codDepartamento;
		$fieldsRequisicao['codClasseRequisicao'] = $requisicao->codClasseRequisicao;
		$fieldsRequisicao['matSau'] = $requisicao->matSau;
		$fieldsRequisicao['carDisp'] = $requisicao->carDisp;
		$fieldsRequisicao['codAutor'] = session()->codPessoa;
		$fieldsRequisicao['codAutorUltAlteracao'] = session()->codPessoa;
		$codRequisicao = $this->RequisicaoModel->insert($fieldsRequisicao);


		if ($codRequisicao !== NULL) {

			//INFORMAÇÕES COMPLEMENTARES


			$informacoesComplementares = $this->RequisicaoModel->pegaInformacoesComplemetares($codRequisicaoClonado);


			if ($informacoesComplementares !== NULL) {
				foreach ($informacoesComplementares as $informacaoComplementar) {

					$fieldsInformacaoComplementar['codRequisicao'] = $codRequisicao;
					$fieldsInformacaoComplementar['codCategoria'] = $informacaoComplementar->codCategoria;
					$fieldsInformacaoComplementar['conteudo'] = $informacaoComplementar->conteudo;
					$fieldsInformacaoComplementar['dataCriacao'] = date('Y-m-d H:i');
					$fieldsInformacaoComplementar['dataAtualizacao'] = date('Y-m-d H:i');
					$fieldsInformacaoComplementar['codAutor'] = session()->codPessoa;
					$this->InformacoesComplementaresModel->insert($fieldsInformacaoComplementar);
				}
			}


			//CLONAR ITENS REQUISIÇÃO

			$itensRequisicao = $this->RequisicaoModel->pegaItensRequisicoes($codRequisicaoClonado);

			if ($itensRequisicao !== NULL) {

				$fieldsItensRequisicao = array();
				foreach ($itensRequisicao as $itemRequisicao) {

					$fieldsItensRequisicao['codRequisicao'] = $codRequisicao;
					$fieldsItensRequisicao['nrRef'] = $itemRequisicao->nrRef;
					$fieldsItensRequisicao['descricao'] = $itemRequisicao->descricao;
					$fieldsItensRequisicao['unidade'] = $itemRequisicao->unidade;
					$fieldsItensRequisicao['qtde_sol'] = $itemRequisicao->qtde_sol;
					$fieldsItensRequisicao['valorUnit'] = brl2decimal($itemRequisicao->valorUnit);
					$fieldsItensRequisicao['valorTotal'] = brl2decimal($itemRequisicao->valorTotal);
					$fieldsItensRequisicao['metodoCalculo'] = $itemRequisicao->metodoCalculo; //1 = Média de preços
					$fieldsItensRequisicao['cod_siasg'] = $itemRequisicao->cod_siasg;
					$fieldsItensRequisicao['tipoMaterial'] = $itemRequisicao->tipoMaterial;
					$fieldsItensRequisicao['obs'] = $itemRequisicao->obs;
					$fieldsItensRequisicao['dataCriacao'] = date('Y-m-d H:i');
					$fieldsItensRequisicao['dataAtualizacao'] = date('Y-m-d H:i');
					$fieldsItensRequisicao['codAutor'] = session()->codPessoa;
					$fieldsItensRequisicao['codAutorUltAlteracao'] = session()->codPessoa;


					$codRequisicaoItem = $this->ItensRequisicaoModel->insert($fieldsItensRequisicao);


					if ($codRequisicaoItem !== NULL and $itemRequisicao->codRequisicaoItem !== 0) {

						$orcamentos = $this->RequisicaoModel->pegaOrcamentos($itemRequisicao->codRequisicaoItem);


						if ($orcamentos !== NULL) {
							foreach ($orcamentos as $orcamento) {

								$fieldsOrcamento['codFornecedor'] = $orcamento->codFornecedor;
								$fieldsOrcamento['valorUnitario'] = brl2decimal($orcamento->valorUnitario);
								$fieldsOrcamento['codTipoOrcamento'] = $orcamento->codTipoOrcamento;
								$fieldsOrcamento['codRequisicaoItem'] = $codRequisicaoItem;
								$fieldsOrcamento['dataOrcamento'] = $orcamento->dataOrcamento;
								$fieldsOrcamento['dataCriacao'] = date('Y-m-d H:i');
								$fieldsOrcamento['dataAtualizacao'] = date('Y-m-d H:i');
								$this->OrcamentosModel->insert($fieldsOrcamento);
							}
						}
					}
				}
			}
		}


		//REGISTRAR NO LOG



		$fieldsHistoricoAcao['codTipoAcao'] = 1;
		$fieldsHistoricoAcao['descricaoAcao'] = 'CLONAGEM DE REQUISIÇÃO: Origem requisição nº ' . $numeroRequisicao . "/" . $fieldsRequisicao['ano'] . ", de " . $dia;
		$fieldsHistoricoAcao['codAutor'] = session()->codPessoa;
		$fieldsHistoricoAcao['dataCriacao'] = date('Y-m-d H:i');
		$fieldsHistoricoAcao['recurso'] = $this->request->getPost('recurso');
		$fieldsHistoricoAcao['codRequisicao'] = $codRequisicao;

		$this->HistoricoAcoesModel->insert($fieldsHistoricoAcao);


		$response['success'] = true;
		$response['messages'] = 'Clone realizado com sucesso';
		return $this->response->setJSON($response);
	}


	public function remove()
	{
		$response = array();

		$id = $this->request->getPost('codRequisicao');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->RequisicaoModel->where('codRequisicao', $id)->delete()) {


				//REMOVER INFORMAÇÕES COMPLEMENTARES

				$this->RequisicaoModel->removeInformacoesComplementares($id);

				//REMOVER ITENS DA REQUISIÇÃO e ORÇAMENTOS

				$this->RequisicaoModel->removeItensRequisicao($id);



				$response['success'] = true;
				$response['messages'] = 'Deletado com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na deleção!';
			}
		}

		return $this->response->setJSON($response);
	}
}
