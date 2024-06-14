<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\FuncoesAtribuidasModel;

class FuncoesAtribuidas extends BaseController
{

	protected $funcoesAtribuidasModel;
	protected $pessoasModel;
	protected $organizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{



		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->funcoesAtribuidasModel = new FuncoesAtribuidasModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
		$permissao = verificaPermissao('FuncoesAtribuidas', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo FuncoesAtribuidas', session()->codPessoa);
			exit();
		}
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'funcoesAtribuidas',
			'title'     		=> 'Funções Atribuidas'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('funcoesAtribuidas', $data);
	}


	public function pegaFuncoesPessoa($codPessoa)
	{
		$response = array();

		$data['data'] = array();

		$result = $this->funcoesAtribuidasModel->pegaTudoPorPessoa($codPessoa);

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Editar" onclick="editfuncoesAtribuidas(' . $value->codPessoaFuncao . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="removefuncoesAtribuidas(' . $value->codPessoaFuncao . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codPessoaFuncao,
				$value->nomeExibicao,
				$value->descricaoFuncao,
				$value->dataInicio,
				$value->dataEncerramento,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->funcoesAtribuidasModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Editar" onclick="editfuncoesAtribuidas(' . $value->codPessoaFuncao . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="removefuncoesAtribuidas(' . $value->codPessoaFuncao . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codPessoaFuncao,
				$value->nomeExibicao,
				$value->descricaoFuncao,
				$value->dataInicio,
				$value->dataEncerramento,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codPessoaFuncao');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->funcoesAtribuidasModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codPessoaFuncao'] = $this->request->getPost('codPessoaFuncao');
		$fields['codPessoa'] = $this->request->getPost('codPessoa');
		$fields['codFuncao'] = $this->request->getPost('codFuncao');
		$fields['dataInicio'] = $this->request->getPost('dataInicio');
		$fields['codOrganizacao'] = $this->codOrganizacao;
		if ($this->request->getPost('dataEncerramento') == NULL or $this->request->getPost('dataEncerramento') == '') {
			$fields['dataEncerramento'] = NULL;
		} else {
			$fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');
		}
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');

		$this->validation->setRules([
			'codPessoa' => ['label' => 'Pessoa', 'rules' => 'required|max_length[11]'],
			'codFuncao' => ['label' => 'Função', 'rules' => 'required|max_length[11]'],
			'dataInicio' => ['label' => 'Data Início', 'rules' => 'required|valid_date|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->funcoesAtribuidasModel->insert($fields)) {

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

		$fields['codOrganizacao'] = session()->codOrganizacao;
		$fields['codPessoaFuncao'] = $this->request->getPost('codPessoaFuncao');
		$fields['codPessoa'] = $this->request->getPost('codPessoa');
		$fields['codFuncao'] = $this->request->getPost('codFuncao');
		$fields['dataInicio'] = $this->request->getPost('dataInicio');
		if ($this->request->getPost('dataEncerramento') == NULL or $this->request->getPost('dataEncerramento') == '') {
			$fields['dataEncerramento'] = NULL;
		} else {
			$fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');
		}
		$fields['dataAtualizacao'] = date('Y-m-d H:i');


		$this->validation->setRules([
			'codPessoaFuncao' => ['label' => 'codPessoaFuncao', 'rules' => 'required|numeric'],
			'codPessoa' => ['label' => 'Pessoa', 'rules' => 'required|max_length[11]'],
			'codFuncao' => ['label' => 'Função', 'rules' => 'required|max_length[11]'],
			'dataInicio' => ['label' => 'Data Início', 'rules' => 'required|valid_date|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->funcoesAtribuidasModel->update($fields['codPessoaFuncao'], $fields)) {

				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['messages'] = 'Registro atualizado com sucesso';
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

		$id = $this->request->getPost('codPessoaFuncao');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->funcoesAtribuidasModel->where('codPessoaFuncao', $id)->delete()) {

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
