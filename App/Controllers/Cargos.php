<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrganizacoesModel;
use App\Models\LogsModel;

use App\Models\CargosModel;

class Cargos extends BaseController
{

	protected $CargosModel;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->CargosModel = new CargosModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
	}

	public function index()
	{

		$permissao = verificaPermissao('cargos', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo CARGOS', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'cargos',
			'title'     		=> 'Cargos'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('cargos', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->CargosModel->select('codCargo, descricaoCargo,siglaCargo')->findAll();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-xs btn-primary" onclick="edit(' . $value->codCargo . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="remove(' . $value->codCargo . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codCargo,
				$value->descricaoCargo,
				$value->siglaCargo,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codCargo');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->CargosModel->where('codCargo', $id)->first();

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();



		$fields['codOrganizacao'] = session()->codOrganizacao;
		$fields['codCargo'] = $this->request->getPost('codCargo');
		$fields['descricaoCargo'] = $this->request->getPost('descricaoCargo');
		$fields['siglaCargo'] = $this->request->getPost('siglaCargo');
		$fields['dataCriacao'] = date('Y-m-d H:i');


		$this->validation->setRules([
			'descricaoCargo' => ['label' => 'Descrição cargo', 'rules' => 'required|max_length[100]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->CargosModel->insert($fields)) {

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

		$fields['codCargo'] = $this->request->getPost('codCargo');
		$fields['descricaoCargo'] = $this->request->getPost('descricaoCargo');
		$fields['siglaCargo'] = $this->request->getPost('siglaCargo');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');


		$this->validation->setRules([
			'codCargo' => ['label' => 'codCargo', 'rules' => 'required|numeric|max_length[11]'],
			'descricaoCargo' => ['label' => 'Descrição cargo', 'rules' => 'required|max_length[100]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->CargosModel->update($fields['codCargo'], $fields)) {

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

		$id = $this->request->getPost('codCargo');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->CargosModel->where('codCargo', $id)->delete()) {

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
