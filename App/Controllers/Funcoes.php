<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrganizacoesModel;
use App\Models\LogsModel;

use App\Models\FuncoesModel;

class Funcoes extends BaseController
{

	protected $funcoesModel;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->funcoesModel = new FuncoesModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		
		$permissao = verificaPermissao('Funcoes', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo Funcoes', session()->codPessoa);
			exit();
		}
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'funcoes',
			'title'     		=> 'Funções'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('funcoes', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->funcoesModel->select('codFuncao, descricaoFuncao,siglaFuncao')->findAll();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-xs btn-primary" onclick="edit(' . $value->codFuncao . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="remove(' . $value->codFuncao . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codFuncao,
				$value->descricaoFuncao,
				$value->siglaFuncao,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codFuncao');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->funcoesModel->pegaFuncao($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();



		$fields['codOrganizacao'] = session()->codOrganizacao;
		$fields['codFuncao'] = $this->request->getPost('codFuncao');
		$fields['descricaoFuncao'] = $this->request->getPost('descricaoFuncao');
		$fields['siglaFuncao'] = $this->request->getPost('siglaFuncao');
		$fields['dataCriacao'] = date('Y-m-d H:i');


		$this->validation->setRules([
			'descricaoFuncao' => ['label' => 'Descrição função', 'rules' => 'required|max_length[100]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->funcoesModel->insert($fields)) {

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

		$fields['codFuncao'] = $this->request->getPost('codFuncao');
		$fields['descricaoFuncao'] = $this->request->getPost('descricaoFuncao');
		$fields['siglaFuncao'] = $this->request->getPost('siglaFuncao');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');


		$this->validation->setRules([
			'codFuncao' => ['label' => 'codFuncao', 'rules' => 'required|numeric'],
			'descricaoFuncao' => ['label' => 'Descrição função', 'rules' => 'required|max_length[100]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->funcoesModel->update($fields['codFuncao'], $fields)) {

				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
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

		$id = $this->request->getPost('codFuncao');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->funcoesModel->where('codFuncao', $id)->delete()) {

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
