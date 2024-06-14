<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\NotificacoesFilaModel;

class NotificacoesFila extends BaseController
{

	protected $NotificacoesFilaModel;
	protected $pessoasModel;
	protected $organizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->NotificacoesFilaModel = new NotificacoesFilaModel();
		$this->organizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->organizacao =  $this->organizacoesModel->pegaOrganizacao($this->codOrganizacao);

		$permissao = verificaPermissao('NotificacoesFila', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo NotificacoesFila', session()->codPessoa);
			exit();
		}
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'notificacoesFila',
			'title'     		=> 'Notificações na FIla'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('notificacoesFila', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->NotificacoesFilaModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editnotificacoesFila(' . $value->codNotificacao . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removenotificacoesFila(' . $value->codNotificacao . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codNotificacao,
				$value->conteudo,
				$value->remetente,
				$value->destinatario,
				$value->codOrganizacao,
				$value->codProtocoloNotificacao,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codNotificacao');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->NotificacoesFilaModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codNotificacao'] = $this->request->getPost('codNotificacao');
		$fields['conteudo'] = $this->request->getPost('conteudo');
		$fields['remetente'] = $this->request->getPost('remetente');
		$fields['destinatario'] = $this->request->getPost('destinatario');
		$fields['codOrganizacao'] = $this->request->getPost('codOrganizacao');
		$fields['codProtocoloNotificacao'] = $this->request->getPost('codProtocoloNotificacao');
		$fields['autor'] = session()->codPessoa;
		$fields['dataAtualizacao'] = date('Y-m-d H:i');


		$this->validation->setRules([
			'conteudo' => ['label' => 'conteudo', 'rules' => 'required'],
			'remetente' => ['label' => 'Remetente', 'rules' => 'required|max_length[100]'],
			'destinatario' => ['label' => 'Destinatario', 'rules' => 'required|max_length[100]'],
			'codOrganizacao' => ['label' => 'CodOrganizacao', 'rules' => 'required|numeric|max_length[11]'],
			'codProtocoloNotificacao' => ['label' => 'CodProtocoloNotificacao', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->NotificacoesFilaModel->insert($fields)) {

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

		$fields['codNotificacao'] = $this->request->getPost('codNotificacao');
		$fields['conteudo'] = $this->request->getPost('conteudo');
		$fields['remetente'] = $this->request->getPost('remetente');
		$fields['destinatario'] = $this->request->getPost('destinatario');
		$fields['codOrganizacao'] = $this->request->getPost('codOrganizacao');
		$fields['codProtocoloNotificacao'] = $this->request->getPost('codProtocoloNotificacao');


		$this->validation->setRules([
			'codNotificacao' => ['label' => 'codNotificacao', 'rules' => 'required|numeric'],
			'conteudo' => ['label' => 'conteudo', 'rules' => 'required'],
			'remetente' => ['label' => 'Remetente', 'rules' => 'required|max_length[100]'],
			'destinatario' => ['label' => 'Destinatario', 'rules' => 'required|max_length[100]'],
			'codOrganizacao' => ['label' => 'CodOrganizacao', 'rules' => 'required|numeric|max_length[11]'],
			'codProtocoloNotificacao' => ['label' => 'CodProtocoloNotificacao', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->NotificacoesFilaModel->update($fields['codNotificacao'], $fields)) {

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

		$id = $this->request->getPost('codNotificacao');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->NotificacoesFilaModel->where('codNotificacao', $id)->delete()) {

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
