<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\DepositosModel;

class Depositos extends BaseController
{

	protected $DepositosModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->DepositosModel = new DepositosModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('Depositos', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "Depositos"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'depositos',
			'title'     		=> 'Depositos'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('depositos', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->DepositosModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editdepositos(' . $value->codDeposito . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removedepositos(' . $value->codDeposito . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			if ($value->codStatus == 1) {
				$status = "Ativo";
			} else {
				$status = "Desativado";
			}


			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codDeposito,
				$value->descricaoDeposito,
				$value->descricaoDepartamento,
				$status,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codDeposito');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->DepositosModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codDeposito'] = $this->request->getPost('codDeposito');
		$fields['descricaoDeposito'] = mb_strtoupper($this->request->getPost('descricaoDeposito'), "utf-8");
		$fields['codDepartamento'] = $this->request->getPost('codDepartamento');
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['codAutor'] = session()->codPessoa;
		$fields['codOrganizacao'] = session()->codOrganizacao;
		$fields['codStatus'] = 1;


		$this->validation->setRules([
			'descricaoDeposito' => ['label' => 'Descrição', 'rules' => 'required|max_length[100]'],
			'codDepartamento' => ['label' => 'Departamento Gestor', 'rules' => 'required|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->DepositosModel->insert($fields)) {

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


	public function listaDropDown()
	{

		$result = $this->DepositosModel->listaDropDown();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function edit()
	{

		$response = array();

		$fields['codDeposito'] = $this->request->getPost('codDeposito');
		$fields['descricaoDeposito'] = mb_strtoupper($this->request->getPost('descricaoDeposito'), "utf-8");
		$fields['codDepartamento'] = $this->request->getPost('codDepartamento');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['codAutor'] = session()->codPessoa;
		$fields['codOrganizacao'] = session()->codOrganizacao;

		if ($this->request->getPost('codStatus') == 'on') {
			$fields['codStatus'] = 1;
		} else {
			$fields['codStatus'] = 0;
		}




		$this->validation->setRules([
			'codDeposito' => ['label' => 'codDeposito', 'rules' => 'required|numeric|max_length[11]'],
			'descricaoDeposito' => ['label' => 'Descrição', 'rules' => 'required|max_length[100]'],
			'codDepartamento' => ['label' => 'Departamento Gestor', 'rules' => 'required|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->DepositosModel->update($fields['codDeposito'], $fields)) {

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

		$id = $this->request->getPost('codDeposito');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->DepositosModel->where('codDeposito', $id)->delete()) {

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
