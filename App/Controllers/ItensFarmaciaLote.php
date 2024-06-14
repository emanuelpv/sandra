<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;
use App\Models\ItensFarmaciaModel;

use App\Models\ItensFarmaciaLoteModel;

class ItensFarmaciaLote extends BaseController
{

	protected $ItensFarmaciaLoteModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->ItensFarmaciaLoteModel = new ItensFarmaciaLoteModel();
		$this->ItensFarmaciaModel = new ItensFarmaciaModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('ItensFarmaciaLote', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "ItensFarmaciaLote"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'itensFarmaciaLote',
			'title'     		=> 'itensFarmaciaLote'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('itensFarmaciaLote', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->ItensFarmaciaLoteModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="edititensFarmaciaLote(' . $value->codLote . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeitensFarmaciaLote(' . $value->codLote . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codLote,
				$value->nrLote,
				$value->codBarra,
				$value->quantidade,
				$value->dataValidade,
				$value->dataCriacao,
				$value->dataAtualizacao,
				$value->dataInventario,
				$value->observacao,
				$value->codAutor,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}
	public function getAllPorItem()
	{
		$response = array();

		$data['data'] = array();

		$codItem = $this->request->getPost('codItem');


		$result = $this->ItensFarmaciaLoteModel->pegaPorCodItem($codItem);

		foreach ($result as $key => $value) {
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="edititensFarmaciaLote(' . $value->codLote . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeitensFarmaciaLote(' . $value->codLote . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$statusVencido = "";

			if ($value->codBarra !== NULL and $value->codBarra !== "") {
				$statusCodBarra = '<i class="fas fa-barcode zoom fa-3x"></i>';
			} else {
				$statusCodBarra = "Não Possui";
			}
			if ($value->validadeIndeterminada == 1) {
				$validade = 'Indeterminada';
			} else {
				if ($value->dataValidade !== NULL and $value->dataValidade !== '0000-00-00') {
					$validade = date('d/m/Y', strtotime($value->dataValidade));
					if ($value->dataValidade !== NULL and $value->dataValidade < date('Y-m-d') and $value->quantidade > 0) {
						$statusVencido = '
						<span>
							<div class="spinner-grow bg-danger" style="width: 1rem; height: 1rem;" role="status">
								<span class="sr-only">Loading...
								</span>
							</div>
						</span>';
					} else {
						$statusVencido = "";
					}
				} else {
					$validade = "-";
				}
			}

			if ($value->dataInventario !== NULL and $value->dataInventario !== '0000-00-00') {
				$dataInventario = date('d/m/Y', strtotime($value->dataInventario));
			} else {
				$dataInventario = "-";
			}






			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codLote,
				$value->nrLote,
				$value->quantidade,
				$statusVencido . $validade,
				$dataInventario,
				$value->observacaoLote,
				$statusCodBarra,
				$value->nomeExibicao,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codLote');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->ItensFarmaciaLoteModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		if ($this->request->getPost('validadeIndeterminada') == 'on') {
			$fields['validadeIndeterminada'] = 1;
		} else {
			$fields['validadeIndeterminada'] = 0;
		}

		if ($fields['validadeIndeterminada'] == 1) {
			$fields['dataValidade'] = null;
		} else {
			if ($this->request->getPost('dataValidade') !== NULL) {
				$fields['dataValidade'] = $this->request->getPost('dataValidade');
			} else {
				$fields['dataValidade'] = null;
			}
		}



		$fields['codLote'] = $this->request->getPost('codLote');
		$fields['codItem'] = $this->request->getPost('codItem');
		$fields['codDeposito'] = $this->request->getPost('codDeposito');
		$fields['codLocalizacao'] = $this->request->getPost('codLocalizacao');
		$fields['codOrganizacao'] = session()->codOrganizacao;
		$fields['nrLote'] = $this->request->getPost('nrLote');
		$fields['codBarra'] = $this->request->getPost('codBarra');
		$fields['nf'] = $this->request->getPost('nf');
		$fields['valorAquisicao'] = $this->request->getPost('valorAquisicao');
		$fields['requisicao'] = $this->request->getPost('requisicao');
		$fields['empenho'] = $this->request->getPost('empenho');
		$fields['quantidade'] = $this->request->getPost('quantidade');
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['dataInventario'] =  date('Y-m-d H:i');
		$fields['observacao'] = $this->request->getPost('observacao');
		$fields['codAutor'] = session()->codPessoa;


		$this->validation->setRules([
			'nrLote' => ['label' => 'Nº do Lote', 'rules' => 'required|max_length[50]'],
			'codBarra' => ['label' => 'Código de Barras', 'rules' => 'permit_empty|max_length[64]'],
			'quantidade' => ['label' => 'Quantidade', 'rules' => 'required'],
			'dataValidade' => ['label' => 'Data Validade', 'rules' => 'permit_empty|valid_date'],
			'dataCriacao' => ['label' => 'Criação', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'Atualização', 'rules' => 'required'],
			'dataInventario' => ['label' => 'DataInventario', 'rules' => 'permit_empty'],
			'observacao' => ['label' => 'Observacao', 'rules' => 'permit_empty'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($codLote = $this->ItensFarmaciaLoteModel->insert($fields)) {
				$item = $this->ItensFarmaciaLoteModel->pegaPorCodigo($codLote);
				$itemUpdate['saldo'] =  $item->saldo + $this->request->getPost('quantidade');
				if ($item->codItem !== NULL and $item->codItem !== "") {
					$this->ItensFarmaciaModel->update($item->codItem, $itemUpdate);
				}


				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['messages'] = 'Informação inserida com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na inserção!';
			}
		}

		return $this->response->setJSON($response);
	}

	public function edit()
	{


		$response = array();

		$item = $this->ItensFarmaciaLoteModel->pegaPorCodigo($this->request->getPost('codLote'));

		if ($item->quantidade > $this->request->getPost('quantidade')) {

			$itemUpdate['saldo'] =  $item->saldo - ($item->quantidade - $this->request->getPost('quantidade'));
			if ($item->codItem !== NULL and $item->codItem !== "") {

				$this->ItensFarmaciaModel->update($item->codItem, $itemUpdate);
			}
		}


		if ($item->quantidade < $this->request->getPost('quantidade')) {
			$itemUpdate['saldo'] =  $item->saldo + ($this->request->getPost('quantidade') - $item->quantidade);
			if ($item->codItem !== NULL and $item->codItem !== "") {

				$this->ItensFarmaciaModel->update($item->codItem, $itemUpdate);
			}
		}



		if ($this->request->getPost('validadeIndeterminada') == 'on') {
			$fields['validadeIndeterminada'] = 1;
		} else {
			$fields['validadeIndeterminada'] = 0;
		}

		if ($fields['validadeIndeterminada'] == 1) {
			$fields['dataValidade'] = null;
		} else {
			if ($this->request->getPost('dataValidade') !== NULL) {
				$fields['dataValidade'] = $this->request->getPost('dataValidade');
			} else {
				$fields['dataValidade'] = null;
			}
		}

		$fields['codLote'] = $this->request->getPost('codLote');
		$fields['nrLote'] = $this->request->getPost('nrLote');
		$fields['codDeposito'] = $this->request->getPost('codDeposito');
		$fields['codLocalizacao'] = $this->request->getPost('codLocalizacao');
		$fields['codBarra'] = $this->request->getPost('codBarra');
		$fields['nf'] = $this->request->getPost('nf');
		$fields['valorAquisicao'] = $this->request->getPost('valorAquisicao');
		$fields['requisicao'] = $this->request->getPost('requisicao');
		$fields['empenho'] = $this->request->getPost('empenho');
		$fields['quantidade'] = $this->request->getPost('quantidade');
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['dataInventario'] =  $this->request->getPost('dataInventario');

		$fields['observacao'] = $this->request->getPost('observacao');
		$fields['codAutor'] = session()->codPessoa;

		$this->validation->setRules([
			'codLote' => ['label' => 'codLote', 'rules' => 'required|numeric|max_length[50]'],
			'nrLote' => ['label' => 'Nº do Lote', 'rules' => 'required|max_length[50]'],
			'codBarra' => ['label' => 'Código de Barras', 'rules' => 'permit_empty|max_length[64]'],
			'dataValidade' => ['label' => 'Data Validade', 'rules' => 'permit_empty|valid_date'],
			'dataCriacao' => ['label' => 'Criação', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'Atualização', 'rules' => 'required'],
			'observacao' => ['label' => 'Observacao', 'rules' => 'permit_empty'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->ItensFarmaciaLoteModel->update($fields['codLote'], $fields)) {
				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['messages'] = 'Atualizado com sucesso';
				$response['saldo'] = $itemUpdate['saldo'];
			} else {
				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
			}
		}

		return $this->response->setJSON($response);
	}

	public function remove()
	{
		$response = array();

		$id = $this->request->getPost('codLote');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->ItensFarmaciaLoteModel->where('codLote', $id)->delete()) {

				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['messages'] = 'Deletado com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na deleção!';
			}
		}

		return $this->response->setJSON($response);
	}
}
