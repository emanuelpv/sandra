<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\CategoriasSuporteModel;

class CategoriasSuporte extends BaseController
{

	protected $categoriasSuporteModel;
	protected $pessoasModel;
	protected $organizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->CategoriasSuporteModel = new CategoriasSuporteModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);


		$permissao = verificaPermissao('CategoriasSuporte', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo CategoriasSuporte', session()->codPessoa);
			exit();
		}
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'categoriasSuporte',
			'title'     		=> 'Categorias de Suporte'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('categoriasSuporte', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->CategoriasSuporteModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Editar" onclick="editcategoriasSuporte(' . $value->codCategoriaSuporte . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="removecategoriasSuporte(' . $value->codCategoriaSuporte . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codCategoriaSuporte,
				$value->descricaoCategoriaSuporte,
				$value->siglaEquipeSuporte,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codCategoriaSuporte');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->CategoriasSuporteModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function listaDropDown()
	{

		$categorias = $this->CategoriasSuporteModel->listaDropDown();

		if ($categorias !== NULL) {


			return $this->response->setJSON($categorias);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function add()
	{

		$response = array();
		$codOrganizacao = session()->codOrganizacao;

		$fields['codCategoriaSuporte'] = $this->request->getPost('codCategoriaSuporte');
		$fields['descricaoCategoriaSuporte'] = $this->request->getPost('descricaoCategoriaSuporte');
		$fields['codEquipeResponsavel'] = $this->request->getPost('codEquipeResponsavel');
		$fields['codOrganizacao'] = $codOrganizacao;


		$this->validation->setRules([
			'descricaoCategoriaSuporte' => ['label' => 'Descrição da Categoria', 'rules' => 'required|max_length[50]'],
			'codEquipeResponsavel' => ['label' => 'Equipe Responsável', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->CategoriasSuporteModel->insert($fields)) {

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

		$fields['codCategoriaSuporte'] = $this->request->getPost('codCategoriaSuporte');
		$fields['descricaoCategoriaSuporte'] = $this->request->getPost('descricaoCategoriaSuporte');
		$fields['codEquipeResponsavel'] = $this->request->getPost('codEquipeResponsavel');
		$fields['sla'] = $this->request->getPost('sla');
		$fields['medidaSLA'] = $this->request->getPost('medidaSLA');
		$fields['slo'] = $this->request->getPost('slo');
		$fields['sli'] = $this->request->getPost('sli');
		$fields['medidaSLO'] = $this->request->getPost('medidaSLO');

		$this->validation->setRules([
			'codCategoriaSuporte' => ['label' => 'codCategoriaSuporte', 'rules' => 'required|numeric|max_length[11]'],
			'descricaoCategoriaSuporte' => ['label' => 'Descrição da Categoria', 'rules' => 'required|max_length[50]'],
			'codEquipeResponsavel' => ['label' => 'Equipe Responsável', 'rules' => 'required'],
			'sla' => ['label' => 'SLA', 'rules' => 'required'],
			'medidaSLA' => ['label' => 'medida SLA', 'rules' => 'required'],
			'slo' => ['label' => 'SLO', 'rules' => 'required'],
			'sli' => ['label' => 'SLI', 'rules' => 'required'],
			'medidaSLO' => ['label' => 'medida SLO', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->CategoriasSuporteModel->update($fields['codCategoriaSuporte'], $fields)) {

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

		$id = $this->request->getPost('codCategoriaSuporte');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->CategoriasSuporteModel->where('codCategoriaSuporte', $id)->delete()) {

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
