<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LogsModel;
use App\Models\RelatoriosModel;

class Relatorios extends BaseController
{

	protected $RelatoriosModel;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());

		$this->RelatoriosModel = new RelatoriosModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();



		$permissao = verificaPermissao('Relatorios', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo Relatorios', session()->codPessoa);
			exit();
		}

	}

	public function index()
	{



		$data = [
			'controller'    	=> 'Relatorios',
			'title'     		=> 'Relatorios'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('relatorios', $data);
	}




	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->RelatoriosModel->select('id, nome, link, pai, ordem, destaque, icone')->findAll();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-xs btn-primary" onclick="edit(' . $value->id . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="remove(' . $value->id . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->id,
				$value->nome,
				$value->link,
				$value->pai,
				$value->ordem,
				$value->destaque,
				$value->icone,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('id');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->RelatoriosModel->where('id', $id)->first();

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['id'] = $this->request->getPost('id');
		$fields['nome'] = $this->request->getPost('nome');
		$fields['link'] = $this->request->getPost('link');
		$fields['pai'] = $this->request->getPost('pai');
		$fields['ordem'] = $this->request->getPost('ordem');
		$fields['destaque'] = $this->request->getPost('destaque');
		$fields['icone'] = $this->request->getPost('icone');


		$this->validation->setRules([
			'nome' => ['label' => 'Nome', 'rules' => 'required|max_length[50]'],
			'link' => ['label' => 'Link', 'rules' => 'required|max_length[70]'],
			'pai' => ['label' => 'Pai', 'rules' => 'permit_empty|max_length[11]'],
			'ordem' => ['label' => 'Ordem', 'rules' => 'required|numeric|max_length[11]'],
			'destaque' => ['label' => 'Destaque', 'rules' => 'required|numeric|max_length[11]'],
			'icone' => ['label' => 'Icone', 'rules' => 'required|max_length[20]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->RelatoriosModel->insert($fields)) {

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

		$fields['id'] = $this->request->getPost('id');
		$fields['nome'] = $this->request->getPost('nome');
		$fields['link'] = $this->request->getPost('link');
		$fields['pai'] = $this->request->getPost('pai');
		$fields['ordem'] = $this->request->getPost('ordem');
		$fields['destaque'] = $this->request->getPost('destaque');
		$fields['icone'] = $this->request->getPost('icone');


		$this->validation->setRules([
			'id' => ['label' => 'id', 'rules' => 'required|numeric|max_length[11]'],
			'nome' => ['label' => 'Nome', 'rules' => 'required|max_length[50]'],
			'link' => ['label' => 'Link', 'rules' => 'required|max_length[70]'],
			'pai' => ['label' => 'Pai', 'rules' => 'permit_empty|max_length[11]'],
			'ordem' => ['label' => 'Ordem', 'rules' => 'required|numeric|max_length[11]'],
			'destaque' => ['label' => 'Destaque', 'rules' => 'required|numeric|max_length[11]'],
			'icone' => ['label' => 'Icone', 'rules' => 'required|max_length[20]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->RelatoriosModel->update($fields['id'], $fields)) {

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

		$id = $this->request->getPost('id');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->RelatoriosModel->where('id', $id)->delete()) {

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
