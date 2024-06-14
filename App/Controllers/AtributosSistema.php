<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\AtributosSistemaModel;

class AtributosSistema extends BaseController
{

	protected $AtributosSistemaModel;
	protected $pessoasModel;
	protected $organizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->AtributosSistemaModel = new AtributosSistemaModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);

		$permissao = verificaPermissao('AtributosSistema', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo AtributosSistema', session()->codPessoa);
			exit();
		}
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'atributosSistema',
			'title'     		=> 'Atributos do Sistema'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('atributosSistema', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->AtributosSistemaModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Editar" onclick="editatributosSistema(' . $value->codAtributosSistema . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="removeatributosSistema(' . $value->codAtributosSistema . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codAtributosSistema,
				$value->descricaoAtributoSistema,
				$value->nomeAtributoSistema,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codAtributosSistema');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->AtributosSistemaModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codAtributosSistema'] = $this->request->getPost('codAtributosSistema');
		$fields['descricaoAtributoSistema'] = $this->request->getPost('descricaoAtributoSistema');
		$fields['nomeAtributoSistema'] = $this->request->getPost('nomeAtributoSistema');


		$this->validation->setRules([
			'descricaoAtributoSistema' => ['label' => 'Descrição do Atributo', 'rules' => 'required|max_length[150]'],
			'nomeAtributoSistema' => ['label' => 'Nome Atributo', 'rules' => 'required|max_length[60]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtributosSistemaModel->insert($fields)) {

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

		$fields['codAtributosSistema'] = $this->request->getPost('codAtributosSistema');
		$fields['descricaoAtributoSistema'] = $this->request->getPost('descricaoAtributoSistema');
		$fields['nomeAtributoSistema'] = $this->request->getPost('nomeAtributoSistema');


		$this->validation->setRules([
			'codAtributosSistema' => ['label' => 'codAtributosSistema', 'rules' => 'required|numeric'],
			'descricaoAtributoSistema' => ['label' => 'Descrição do Atributo', 'rules' => 'required|max_length[150]'],
			'nomeAtributoSistema' => ['label' => 'Nome Atributo', 'rules' => 'required|max_length[60]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtributosSistemaModel->update($fields['codAtributosSistema'], $fields)) {

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

		$id = $this->request->getPost('codAtributosSistema');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->AtributosSistemaModel->where('codAtributosSistema', $id)->delete()) {

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
