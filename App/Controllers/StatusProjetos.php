<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\StatusProjetosModel;

class StatusProjetos extends BaseController
{

	protected $statusProjetosModel;
	protected $pessoasModel;
	protected $organizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->statusProjetosModel = new StatusProjetosModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);


		$permissao = verificaPermissao('StatusProjetos', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo StatusProjetos', session()->codPessoa);
			exit();
		}
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'statusProjetos',
			'title'     		=> 'Status Projetos'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('statusProjetos', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->statusProjetosModel->pega_statusprojetos();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Editar" onclick="edit(' . $value->codStatusProjeto . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="remove(' . $value->codStatusProjeto . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codStatusProjeto,
				$value->descricaoStatusProjeto,
				$value->codCorStatusProjeto . ' <i style="width:200px;color:' . $value->codCorStatusProjeto . '" class="fas fa-square"></i>',

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}


	public function listaDropDown()
	{


		$result = $this->statusProjetosModel->listaDropDown();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}



	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codStatusProjeto');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->statusProjetosModel->where('codStatusProjeto', $id)->first();

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codStatusProjeto'] = $this->request->getPost('codStatusProjeto');
		$fields['descricaoStatusProjeto'] = $this->request->getPost('descricaoStatusProjeto');
		$fields['codCorStatusProjeto'] = $this->request->getPost('codCorStatusProjeto');


		$this->validation->setRules([
			'descricaoStatusProjeto' => ['label' => 'Descrição', 'rules' => 'required|max_length[50]'],
			'codCorStatusProjeto' => ['label' => 'Cor', 'rules' => 'required|max_length[10]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->statusProjetosModel->insert($fields)) {

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

		$fields['codStatusProjeto'] = $this->request->getPost('codStatusProjeto');
		$fields['descricaoStatusProjeto'] = $this->request->getPost('descricaoStatusProjeto');
		$fields['codCorStatusProjeto'] = $this->request->getPost('codCorStatusProjeto');


		$this->validation->setRules([
			'codStatusProjeto' => ['label' => 'codStatusProjeto', 'rules' => 'required|numeric|max_length[11]'],
			'descricaoStatusProjeto' => ['label' => 'Descrição', 'rules' => 'required|max_length[50]'],
			'codCorStatusProjeto' => ['label' => 'Cor', 'rules' => 'required|max_length[10]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->statusProjetosModel->update($fields['codStatusProjeto'], $fields)) {

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

		$id = $this->request->getPost('codStatusProjeto');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->statusProjetosModel->where('codStatusProjeto', $id)->delete()) {

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
