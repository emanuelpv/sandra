<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;
use App\Models\ModelosNotificacaoModel;

class ModelosNotificacao extends BaseController
{

	protected $ModelosNotificacaoModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $Organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->ModelosNotificacaoModel = new ModelosNotificacaoModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);


		$permissao = verificaPermissao('ModelosNotificacao', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo ModelosNotificacao', session()->codPessoa);
			exit();
		}
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'modelosNotificacao',
			'title'     		=> 'Modelos de Notificação'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('modelosNotificacao', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->ModelosNotificacaoModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Editar" onclick="editmodelosNotificacao(' . $value->codModeloNotificacao . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="removemodelosNotificacao(' . $value->codModeloNotificacao . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codModeloNotificacao,
				$value->nomeModeloNotificacao,
				$value->nomeProtocoloNotificacao,
				$value->assunto,
				$value->responderPara,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codModeloNotificacao');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->ModelosNotificacaoModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codOrganizacao'] = session()->codOrganizacao;
		$fields['codModeloNotificacao'] = $this->request->getPost('codModeloNotificacao');
		$fields['nomeModeloNotificacao'] = $this->request->getPost('nomeModeloNotificacao');
		$fields['assunto'] = $this->request->getPost('assunto');
		$fields['responderPara'] = $this->request->getPost('responderPara');
		$fields['conteudoModeloNotificacao'] = $this->request->getPost('conteudoModeloNotificacao');
		$fields['codProtocoloNotificacao'] = $this->request->getPost('codProtocoloNotificacao');


		$this->validation->setRules([
			'nomeModeloNotificacao' => ['label' => 'Modelo', 'rules' => 'required|max_length[60]'],
			'assunto' => ['label' => 'Assunto', 'rules' => 'required|max_length[100]'],
			'responderPara' => ['label' => 'Responder Para', 'rules' => 'max_length[60]'],
			'conteudoModeloNotificacao' => ['label' => 'Conteudo', 'rules' => 'required'],
			'codProtocoloNotificacao' => ['label' => 'Protocolo', 'rules' => 'required|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->ModelosNotificacaoModel->insert($fields)) {

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

		$fields['codModeloNotificacao'] = $this->request->getPost('codModeloNotificacao');
		$fields['nomeModeloNotificacao'] = $this->request->getPost('nomeModeloNotificacao');
		$fields['assunto'] = $this->request->getPost('assunto');
		$fields['responderPara'] = $this->request->getPost('responderPara');
		$fields['conteudoModeloNotificacao'] = $this->request->getPost('conteudoModeloNotificacao');
		$fields['codProtocoloNotificacao'] = $this->request->getPost('codProtocoloNotificacao');


		$this->validation->setRules([
			'codModeloNotificacao' => ['label' => 'codModeloNotificacao', 'rules' => 'required|numeric|max_length[11]'],
			'nomeModeloNotificacao' => ['label' => 'Modelo', 'rules' => 'required|max_length[60]'],
			'assunto' => ['label' => 'Assunto', 'rules' => 'required|max_length[100]'],
			'responderPara' => ['label' => 'Responder Para', 'rules' => 'max_length[60]'],
			'conteudoModeloNotificacao' => ['label' => 'Conteudo', 'rules' => 'required'],
			'codProtocoloNotificacao' => ['label' => 'Protocolo', 'rules' => 'required|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->ModelosNotificacaoModel->update($fields['codModeloNotificacao'], $fields)) {

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

		$id = $this->request->getPost('codModeloNotificacao');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->ModelosNotificacaoModel->where('codModeloNotificacao', $id)->delete()) {

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
