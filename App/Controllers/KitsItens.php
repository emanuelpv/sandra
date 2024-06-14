<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\KitsItensModel;
use App\Models\KitsModel;

class KitsItens extends BaseController
{

	protected $KitsItensModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->KitsItensModel = new KitsItensModel();
		$this->KitsModel = new KitsModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('KitsItens', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "KitsItens"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'kitsItens',
			'title'     		=> 'Itens de KIts'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('kitsItens', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->KitsItensModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editkitsItens(' . $value->codKitItem . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removekitsItens(' . $value->codKitItem . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$value->codKitItem,
				$value->codKit,
				$value->qtde,
				date('d/m/Y H:i', strtotime($value->dataCriacao)),
				date('d/m/Y H:i', strtotime($value->dataAtualizacao)),
				$value->codAutor,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}


	public function itensKit()
	{
		$response = array();

		$codKit = $this->request->getPost('codKit');

		$data['data'] = array();

		$result = $this->KitsItensModel->itensKit($codKit);

		$x = 0;
		foreach ($result as $key => $value) {
			$x++;
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editkitsItens(' . $value->codKitItem . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removekitsItens(' . $value->codKitItem . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$x,
				$value->descricaoItem,
				$value->qtde,
				$value->valor,
				date('d/m/Y H:i', strtotime($value->dataCriacao)),
				date('d/m/Y H:i', strtotime($value->dataAtualizacao)),
				$value->nomeExibicao,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}
	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codKitItem');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->KitsItensModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codItem'] = $this->request->getPost('codItem');
		$fields['codKit'] = $this->request->getPost('codKit');
		$fields['qtde'] = $this->request->getPost('qtde');
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['codAutor'] = session()->codPessoa;


		$this->validation->setRules([
			'codKit' => ['label' => 'CodKit', 'rules' => 'required|numeric|max_length[11]'],
			'qtde' => ['label' => 'Qtde', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->KitsItensModel->insert($fields)) {

				$response['success'] = true;
				$response['messages'] = 'Informação inserida com sucesso';

				//Atualiza valor do KIT
				$result = $this->KitsItensModel->itensKit($fields['codKit']);
				$totalKit = 0;
				foreach ($result as $itemKit) {
					$totalKit = $totalKit + $itemKit->valor;
				}

				$atualizaKit['valorun'] = $totalKit;

				if ($fields['codKit'] !== NULL and $fields['codKit'] !== "" and $fields['codKit'] !== " ") {
					if ($this->KitsModel->update($fields['codKit'], $atualizaKit)) {
					}
				}
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

		$fields['codKitItem'] = $this->request->getPost('codKitItem');

		$fields['qtde'] = $this->request->getPost('qtde');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['codAutor'] = session()->codPessoa;


		$this->validation->setRules([
			'codKitItem' => ['label' => 'codKitItem', 'rules' => 'required|numeric|max_length[11]'],
			'qtde' => ['label' => 'Qtde', 'rules' => 'required|numeric|max_length[11]'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->KitsItensModel->update($fields['codKitItem'], $fields)) {

				$response['success'] = true;
				$response['messages'] = 'Atualizado com sucesso';
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

		$id = $this->request->getPost('codKitItem');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			$data = $this->KitsItensModel->pegaPorCodigo($id);



			if ($this->KitsItensModel->where('codKitItem', $id)->delete()) {


				//Atualiza valor do KIT
				$result = $this->KitsItensModel->itensKit($data->codKit);
				$totalKit = 0;
				foreach ($result as $itemKit) {
					$totalKit = $totalKit + $itemKit->valor;
				}

				$atualizaKit['valorun'] = $totalKit;

				if ($data->codKit !== NULL and $data->codKit !== "" and $data->codKit !== " ") {
					if ($this->KitsModel->update($data->codKit, $atualizaKit)) {
					}
				}


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
