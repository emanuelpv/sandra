<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\EquipesSuporteModel;

class EquipesSuporte extends BaseController
{

	protected $equipesSuporteModel;
	protected $pessoasModel;
	protected $organizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->equipesSuporteModel = new EquipesSuporteModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
		$permissao = verificaPermissao('EquipesSuporte', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo EquipesSuporte', session()->codPessoa);
			exit();
		}
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'equipesSuporte',
			'title'     		=> 'Equipes de Suporte'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('equipesSuporte', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->equipesSuporteModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Editar" onclick="editequipesSuporte(' . $value->codEquipeSuporte . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="removeequipesSuporte(' . $value->codEquipeSuporte . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codEquipeSuporte,
				$value->siglaEquipeSuporte,
				$value->descricaoEquipeSuporte,
				$value->abreviacaoDepartamento,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codEquipeSuporte');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->equipesSuporteModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function listaDropDown()
	{

		$result = $this->equipesSuporteModel->listaDropDown();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}




	public function add()
	{

		$response = array();



		$fields['codEquipeSuporte'] = $this->request->getPost('codEquipeSuporte');
		$fields['siglaEquipeSuporte'] = $this->request->getPost('siglaEquipeSuporte');
		$fields['descricaoEquipeSuporte'] = $this->request->getPost('descricaoEquipeSuporte');
		$fields['codDepartamentoResponsavel'] = $this->request->getPost('codDepartamentoResponsavel');
		$fields['codOrganizacao'] = session()->codOrganizacao;

		$this->validation->setRules([
			'siglaEquipeSuporte' => ['label' => 'Sigla', 'rules' => 'required|max_length[50]'],
			'descricaoEquipeSuporte' => ['label' => 'Descrição Equipe', 'rules' => 'required|max_length[200]'],
			'codDepartamentoResponsavel' => ['label' => 'Departamento', 'rules' => 'required|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->equipesSuporteModel->insert($fields)) {

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

		$fields['codEquipeSuporte'] = $this->request->getPost('codEquipeSuporte');
		$fields['siglaEquipeSuporte'] = $this->request->getPost('siglaEquipeSuporte');
		$fields['descricaoEquipeSuporte'] = $this->request->getPost('descricaoEquipeSuporte');
		$fields['codDepartamentoResponsavel'] = $this->request->getPost('codDepartamentoResponsavel');


		$this->validation->setRules([
			'codEquipeSuporte' => ['label' => 'codEquipeSuporte', 'rules' => 'required|numeric|max_length[50]'],
			'siglaEquipeSuporte' => ['label' => 'Sigla', 'rules' => 'required|max_length[50]'],
			'descricaoEquipeSuporte' => ['label' => 'Descrição Equipe', 'rules' => 'required|max_length[200]'],
			'codDepartamentoResponsavel' => ['label' => 'Departamento', 'rules' => 'required|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->equipesSuporteModel->update($fields['codEquipeSuporte'], $fields)) {

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

		$id = $this->request->getPost('codEquipeSuporte');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->equipesSuporteModel->where('codEquipeSuporte', $id)->delete()) {

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
