<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\PreferenciasModel;

class Preferencias extends BaseController
{

	protected $PreferenciasModel;
	protected $pessoasModel;
	protected $organizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->PreferenciasModel = new PreferenciasModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);


		$permissao = verificaPermissao('Preferencias', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo Preferencias', session()->codPessoa);
			exit();
		}
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'preferencias',
			'title'     		=> 'Preferências'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('preferencias', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->PreferenciasModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editpreferencias(' . $value->codPreferencia . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removepreferencias(' . $value->codPreferencia . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codPreferencia,
				$value->codPessoa,
				$value->categoriasSolicitacoes,
				$value->statusSolicitacoes,
				$value->periodoSolicitacoes,
				$value->autorPreferencia,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codPreferencia');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->PreferenciasModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function salvaPreferenciaSolicitacoes()
	{

		$response = array();
		$codPessoa = session()->codPessoa;

		if (session()->nomeCompleto == 'Administrador') {

			$preferencia = $this->PreferenciasModel->pegaPorCodigoPessoa(0);
		} else {

			$preferencia = $this->PreferenciasModel->pegaPorCodigoPessoa($codPessoa);
		}
		$preferencia = $this->PreferenciasModel->pegaPorCodigoPessoa($codPessoa);

		if ($preferencia !== NULL) {
			$codPreferencia = $preferencia->codPreferencia;


			$fieldsUpdate['codPreferencia'] = $codPreferencia;
			$fieldsUpdate['codPessoa'] = $this->request->getPost('codPessoa');
			$fieldsUpdate['categoriasSolicitacoes'] = json_encode($this->request->getPost('arrayCategoria'));
			$fieldsUpdate['statusSolicitacoes'] = json_encode($this->request->getPost('arrayStatus'));
			$fieldsUpdate['codSolicitante'] = json_encode($this->request->getPost('arrayCodSolicitante'));
			$fieldsUpdate['codResponsavel'] = json_encode($this->request->getPost('codResponsavel'));
			$fieldsUpdate['codDepartamento'] = json_encode($this->request->getPost('arrayCodDepartamento'));
			$fieldsUpdate['periodoSolicitacoes'] = $this->request->getPost('periodoSolicitacoes');
			$fieldsUpdate['autorPreferencia'] = $codPessoa;
			$fieldsUpdate['codOrganizacao'] = session()->codOrganizacao;
			$fieldsUpdate['dataAtualizacao'] = date('Y-m-d H:i');;


			$this->validation->setRules([
				'codPreferencia' => ['label' => 'codPreferencia', 'rules' => 'required|numeric|max_length[11]'],
				'codPessoa' => ['label' => 'CodPessoa', 'rules' => 'required|numeric|max_length[11]'],
				'categoriasSolicitacoes' => ['label' => 'CategoriasSolicitacoes', 'rules' => 'permit_empty'],
				'statusSolicitacoes' => ['label' => 'StatusSolicitacoes', 'rules' => 'permit_empty'],
				'autorPreferencia' => ['label' => 'AutorPreferencia', 'rules' => 'permit_empty'],

			]);

			if ($this->validation->run($fieldsUpdate) == FALSE) {

				$response['success'] = false;
				$response['messages'] = $this->validation->listErrors();
			} else {

				if ($this->PreferenciasModel->update($fieldsUpdate['codPreferencia'], $fieldsUpdate)) {

					$response['success'] = true;
					$response['csrf_hash'] = csrf_hash();
					$response['messages'] = 'Atualizado com sucesso';
				} else {

					$response['success'] = false;
					$response['messages'] = 'Erro na atualização!';
				}
			}
		} else {


			$fields['codPessoa'] = $codPessoa;
			$fields['categoriasSolicitacoes'] = json_encode($this->request->getPost('arrayCategoria'));
			$fields['statusSolicitacoes'] = json_encode($this->request->getPost('arrayStatus'));
			$fields['periodoSolicitacoes'] = json_encode($this->request->getPost('periodoSolicitacoes'));
			$fields['codSolicitante'] = json_encode($this->request->getPost('arrayCodSolicitante'));
			$fields['codDepartamento'] = json_encode($this->request->getPost('arrayCodDepartamento'));
			$fields['autorPreferencia'] = $codPessoa;


			$fields['codOrganizacao'] = session()->codOrganizacao;
			$fields['dataAtualizacao'] = date('Y-m-d H:i');




			$this->validation->setRules([
				'codPessoa' => ['label' => 'CodPessoa', 'rules' => 'required|numeric|max_length[11]'],
				'categoriasSolicitacoes' => ['label' => 'CategoriasSolicitacoes', 'rules' => 'permit_empty'],
				'statusSolicitacoes' => ['label' => 'StatusSolicitacoes', 'rules' => 'permit_empty'],
				'periodoSolicitacoes' => ['label' => 'PeriodoSolicitacoes', 'rules' => 'permit_empty'],
				'autorPreferencia' => ['label' => 'AutorPreferencia', 'rules' => 'permit_empty'],

			]);

			if ($this->validation->run($fields) == FALSE) {

				$response['success'] = false;
				$response['messages'] = $this->validation->listErrors();
			} else {

				if ($this->PreferenciasModel->insert($fields)) {

					$response['success'] = true;
					$response['csrf_hash'] = csrf_hash();
					$response['messages'] = 'Informação inserida com sucesso';
				} else {

					$response['success'] = false;
					$response['messages'] = 'Erro na inserção!';
				}
			}
		}

		return $this->response->setJSON($response);
	}


	public function add()
	{

		$response = array();

		$fields['codPreferencia'] = $this->request->getPost('codPreferencia');
		$fields['codPessoa'] = $this->request->getPost('codPessoa');
		$fields['categoriasSolicitacoes'] = $this->request->getPost('categoriasSolicitacoes');
		$fields['statusSolicitacoes'] = $this->request->getPost('statusSolicitacoes');
		$fields['periodoSolicitacoes'] = $this->request->getPost('periodoSolicitacoes');
		$fields['autorPreferencia'] = $this->request->getPost('autorPreferencia');


		$this->validation->setRules([
			'codPessoa' => ['label' => 'CodPessoa', 'rules' => 'required|numeric|max_length[11]'],
			'categoriasSolicitacoes' => ['label' => 'CategoriasSolicitacoes', 'rules' => 'required|max_length[30]'],
			'statusSolicitacoes' => ['label' => 'StatusSolicitacoes', 'rules' => 'required|max_length[30]'],
			'periodoSolicitacoes' => ['label' => 'PeriodoSolicitacoes', 'rules' => 'required|numeric|max_length[11]'],
			'autorPreferencia' => ['label' => 'AutorPreferencia', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->PreferenciasModel->insert($fields)) {

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

		$fields['codPreferencia'] = $this->request->getPost('codPreferencia');
		$fields['codPessoa'] = $this->request->getPost('codPessoa');
		$fields['categoriasSolicitacoes'] = $this->request->getPost('categoriasSolicitacoes');
		$fields['statusSolicitacoes'] = $this->request->getPost('statusSolicitacoes');
		$fields['periodoSolicitacoes'] = $this->request->getPost('periodoSolicitacoes');
		$fields['autorPreferencia'] = $this->request->getPost('autorPreferencia');


		$this->validation->setRules([
			'codPreferencia' => ['label' => 'codPreferencia', 'rules' => 'required|numeric|max_length[11]'],
			'codPessoa' => ['label' => 'CodPessoa', 'rules' => 'required|numeric|max_length[11]'],
			'categoriasSolicitacoes' => ['label' => 'CategoriasSolicitacoes', 'rules' => 'required|max_length[30]'],
			'statusSolicitacoes' => ['label' => 'StatusSolicitacoes', 'rules' => 'required|max_length[30]'],
			'periodoSolicitacoes' => ['label' => 'PeriodoSolicitacoes', 'rules' => 'required|numeric|max_length[11]'],
			'autorPreferencia' => ['label' => 'AutorPreferencia', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->PreferenciasModel->update($fields['codPreferencia'], $fields)) {

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

		$id = $this->request->getPost('codPreferencia');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->PreferenciasModel->where('codPreferencia', $id)->delete()) {

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
