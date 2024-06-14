<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\AtributosSistemaOrganizacaoModel;

class AtributosSistemaOrganizacao extends BaseController
{

	protected $AtributosSistemaOrganizacaoModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->AtributosSistemaOrganizacaoModel = new AtributosSistemaOrganizacaoModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('AtributosSistemaOrganizacao', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo AtributosSistemaOrganizacao', session()->codPessoa);
			exit();
		}

		$data = [
			'controller'    	=> 'atributosSistemaOrganizacao',
			'title'     		=> 'Atributos Sistema Organização'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('atributosSistemaOrganizacao', $data);
	}



	public function verificaAtributos()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->AtributosSistemaOrganizacaoModel->pegaTudo();

		foreach ($result as $key => $value) {



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codAtributosSistemaOrganizacao,
				$value->nomeAtributoSistema,
				$value->descricaoAtributoSistema,
				$value->visivelFormulario,
				$value->cadastroRapido,
				$value->visivelLDAP,
				$value->obrigatorio,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$id = $this->request->getPost('codOrganizacao');
		$result = $this->AtributosSistemaOrganizacaoModel->pegaTudoPorOrganizacao($id);

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Editar" onclick="editatributosSistemaOrganizacao(' . $value->codAtributosSistemaOrganizacao . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '</div>';

			if ($value->visivelFormulario == 1) {
				$visivelFormulario = '<i class="far fa-check-circle"></i>';
			} else {
				$visivelFormulario = '<i class="far fa-times-circle"></i>';
			}
			if ($value->obrigatorio == 1) {
				$obrigatorio = '<i class="far fa-check-circle"></i>';
			} else {
				$obrigatorio = '<i class="far fa-times-circle"></i>';
			}
			if ($value->visivelLDAP == 1) {
				$visivelLDAP = '<i class="far fa-check-circle"></i>';
			} else {
				$visivelLDAP = '<i class="far fa-times-circle"></i>';
			}
			if ($value->cadastroRapido == 1) {
				$cadastroRapido = '<i class="far fa-check-circle"></i>';
			} else {
				$cadastroRapido = '<i class="far fa-times-circle"></i>';
			}



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codAtributosSistemaOrganizacao,
				$value->nomeAtributoSistema,
				$value->descricaoAtributoSistema,
				$visivelFormulario,
				$cadastroRapido,
				$visivelLDAP,
				$obrigatorio,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codAtributosSistemaOrganizacao');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->AtributosSistemaOrganizacaoModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codAtributosSistemaOrganizacao'] = $this->request->getPost('codAtributosSistemaOrganizacao');
		$fields['codAtributosSistema'] = $this->request->getPost('codAtributosSistema');
		$fields['codOrganizacao'] = $this->request->getPost('codOrganizacao');
		$fields['descricaoAtributoSistema'] = $this->request->getPost('descricaoAtributoSistema');
		$fields['visivelFormulario'] = $this->request->getPost('visivelFormulario');
		$fields['obrigatorio'] = $this->request->getPost('obrigatorio');


		$this->validation->setRules([
			'codAtributosSistema' => ['label' => 'Código do Atributo', 'rules' => 'required|numeric|max_length[11]'],
			'codOrganizacao' => ['label' => 'Código Organizacao', 'rules' => 'required|numeric|max_length[11]'],
			'descricaoAtributoSistema' => ['label' => 'Descrição', 'rules' => 'required|max_length[150]'],
			'visivelFormulario' => ['label' => 'Visível', 'rules' => 'required|numeric|max_length[11]'],
			'obrigatorio' => ['label' => 'Obrigatório', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtributosSistemaOrganizacaoModel->insert($fields)) {

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

		$fields['codAtributosSistemaOrganizacao'] = $this->request->getPost('codAtributosSistemaOrganizacao');
		$fields['codOrganizacao'] = $this->request->getPost('codOrganizacao');
		$fields['descricaoAtributoSistema'] = $this->request->getPost('descricaoAtributoSistema');

		if ($this->request->getPost('visivelFormulario') == 'on') {
			$fields['visivelFormulario'] = 1;
		} else {
			$fields['visivelFormulario'] = 0;
		}
		if ($this->request->getPost('cadastroRapido') == 'on') {
			$fields['cadastroRapido'] = 1;
		} else {
			$fields['cadastroRapido'] = 0;
		}
		if ($this->request->getPost('visivelLDAP') == 'on') {
			$fields['visivelLDAP'] = 1;
		} else {
			$fields['visivelLDAP'] = 0;
		}
		if ($this->request->getPost('obrigatorio') == 'on') {
			$fields['obrigatorio'] = 1;
		} else {
			$fields['obrigatorio'] = 0;
		}

		$this->validation->setRules([
			'codAtributosSistemaOrganizacao' => ['label' => 'codAtributosSistemaOrganizacao', 'rules' => 'required|numeric|max_length[11]'],
			'codOrganizacao' => ['label' => 'Código Organizacao', 'rules' => 'required|numeric|max_length[11]'],
			'descricaoAtributoSistema' => ['label' => 'Descrição', 'rules' => 'required|max_length[150]'],
			'visivelFormulario' => ['label' => 'Visível', 'rules' => 'numeric|max_length[11]'],
			'obrigatorio' => ['label' => 'Obrigatório', 'rules' => 'numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtributosSistemaOrganizacaoModel->update($fields['codAtributosSistemaOrganizacao'], $fields)) {

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

		$id = $this->request->getPost('codAtributosSistemaOrganizacao');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->AtributosSistemaOrganizacaoModel->where('codAtributosSistemaOrganizacao', $id)->delete()) {

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
