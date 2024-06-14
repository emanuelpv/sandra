<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ModulosModel;
use App\Models\LogsModel;
use App\Models\ModulosNotificacaoModel;

class Modulos extends BaseController
{

	protected $ModulosModel;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->ModulosModel = new ModulosModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->modulosNotificacaoModel = new ModulosNotificacaoModel();
		$this->validation =  \Config\Services::validation();

		$permissao = verificaPermissao('Modulos', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo Modulos', session()->codPessoa);
			exit();
		}
	}

	public function index()
	{



		$data = [
			'controller'    	=> 'Modulos',
			'title'     		=> 'Modulos'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('modulos', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->ModulosModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-xs btn-primary" onclick="edit(' . $value->codModulo . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="remove(' . $value->codModulo . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codModulo,
				$value->nome,
				$value->link,
				$value->descricaoPai,
				$value->ordem,
				$value->destaque,
				$value->icone,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$codModulo = $this->request->getPost('codModulo');

		if ($this->validation->check($codModulo, 'required|numeric')) {

			$data = $this->ModulosModel->where('codModulo', $codModulo)->first();

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function notificacoesModulo()
	{
		$response = array();

		$data['data'] = array();
		$codModulo = $this->request->getPost('codModulo');
		$result = $this->modulosNotificacaoModel->pegaPorCodigoModulo($codModulo);

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Editar" onclick="editmodulosNotificacao(' . $value->codModuloNotificacao . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="removemodulosNotificacao(' . $value->codModuloNotificacao . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$destinatarios = "";
			$destinos = explode(",", $value->destinoNotificacao);
			foreach ($destinos as $row) {
				if ($row == 0) {
					$destinatarios .= 'Autor da ação';
				}
				$destinatarios .= getNomeExibicaoPessoa($this, $row) . ", ";
			}



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codModuloNotificacao,
				$value->descricaoTipoNotificacao,
				$value->nomeModeloNotificacao,
				$value->nomeProtocoloNotificacao,
				$destinatarios,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}


	public function add()
	{

		$response = array();

		$fields['codModulo'] = $this->request->getPost('codModulo');
		$fields['nome'] = $this->request->getPost('nome');
		$fields['link'] = $this->request->getPost('link');
		$fields['pai'] = $this->request->getPost('pai');
		$fields['ordem'] = $this->request->getPost('ordem');
		$fields['destaque'] = $this->request->getPost('destaque');
		$fields['icone'] = $this->request->getPost('icone');
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['DataAtualizacao'] = date('Y-m-d H:i');

		$this->validation->setRules([
			'nome' => ['label' => 'Nome', 'rules' => 'required|max_length[50]'],
			'link' => ['label' => 'Link', 'rules' => 'required|max_length[70]'],
			'pai' => ['label' => 'Pai', 'rules' => 'permit_empty|max_length[11]'],
			'ordem' => ['label' => 'Ordem', 'rules' => 'required|numeric|max_length[11]'],
			'destaque' => ['label' => 'Destaque', 'rules' => 'required|numeric|max_length[11]'],
			'icone' => ['label' => 'Icone', 'rules' => 'required|max_length[40]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->ModulosModel->insert($fields)) {
				$this->ModulosModel = new ModulosModel();
				$Modulos = $this->ModulosModel->pegaTudo();
				session()->set('modulos', $Modulos);

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

		$fields['codModulo'] = $this->request->getPost('codModulo');
		$fields['nome'] = $this->request->getPost('nome');
		$fields['link'] = $this->request->getPost('link');
		$fields['pai'] = $this->request->getPost('pai');
		$fields['ordem'] = $this->request->getPost('ordem');
		$fields['destaque'] = $this->request->getPost('destaque');
		$fields['icone'] = $this->request->getPost('icone');
		$fields['DataAtualizacao'] = date('Y-m-d H:i');


		$this->validation->setRules([
			'codModulo' => ['label' => 'codModulo', 'rules' => 'required|numeric|max_length[11]'],
			'nome' => ['label' => 'Nome', 'rules' => 'required|max_length[50]'],
			'link' => ['label' => 'Link', 'rules' => 'required|max_length[70]'],
			'pai' => ['label' => 'Pai', 'rules' => 'permit_empty|max_length[11]'],
			'ordem' => ['label' => 'Ordem', 'rules' => 'required|numeric|max_length[11]'],
			'destaque' => ['label' => 'Destaque', 'rules' => 'required|numeric|max_length[11]'],
			'icone' => ['label' => 'Icone', 'rules' => 'required|max_length[40]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->ModulosModel->update($fields['codModulo'], $fields)) {
				$this->ModulosModel = new ModulosModel();
				$Modulos = $this->ModulosModel->pegaTudo();
				session()->set('modulos', $Modulos);

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

		$codModulo = $this->request->getPost('codModulo');

		if (!$this->validation->check($codModulo, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->ModulosModel->where('codModulo', $codModulo)->delete()) {

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
