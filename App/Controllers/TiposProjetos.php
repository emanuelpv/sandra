<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\TiposProjetosModel;

class TiposProjetos extends BaseController
{

	protected $tiposProjetosModel;
	protected $pessoasModel;
	protected $organizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->tiposProjetosModel = new TiposprojetosModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);

		$permissao = verificaPermissao('TiposProjetos', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo TiposProjetos', session()->codPessoa);
			exit();
		}
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'tiposProjetos',
			'title'     		=> 'Tipos Projetos'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('tiposProjetos', $data);
	}



	public function listaDropDown()
	{


		$result = $this->tiposProjetosModel->listaDropDown();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->tiposProjetosModel->pega_tiposprojetos();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Editar" onclick="edit(' . $value->codTipoProjeto . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="remove(' . $value->codTipoProjeto . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codTipoProjeto,
				$value->descricaoTipoProjeto,
				$value->ordem,
				$value->codLocalAtendimento,
				$value->prazo,
				$value->ativarNotificacao,
				$value->nrdiasNotificacao,
				$value->link,
				$value->icone,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codTipoProjeto');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->tiposProjetosModel->pega_tiposprojetos_por_codTipoProjeto($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codTipoProjeto'] = $this->request->getPost('codTipoProjeto');
		$fields['descricaoTipoProjeto'] = $this->request->getPost('descricaoTipoProjeto');
		$fields['ordem'] = $this->request->getPost('ordem');
		$fields['codLocalAtendimento'] = $this->request->getPost('codLocalAtendimento');
		$fields['prazo'] = $this->request->getPost('prazo');
		$fields['ativarNotificacao'] = $this->request->getPost('ativarNotificacao');
		$fields['nrdiasNotificacao'] = $this->request->getPost('nrdiasNotificacao');
		$fields['link'] = $this->request->getPost('link');
		$fields['icone'] = $this->request->getPost('icone');


		$this->validation->setRules([
			'descricaoTipoProjeto' => ['label' => 'Descrição', 'rules' => 'required|max_length[100]'],
			'ordem' => ['label' => 'Ordem', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'codLocalAtendimento' => ['label' => 'Dependência', 'rules' => 'permit_empty|max_length[11]'],
			'prazo' => ['label' => 'Prazo', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'ativarNotificacao' => ['label' => 'Ativar Notificação', 'rules' => 'permit_empty|max_length[11]'],
			'nrdiasNotificacao' => ['label' => 'Nr dias Notificação', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'link' => ['label' => 'Link', 'rules' => 'permit_empty|max_length[150]'],
			'icone' => ['label' => 'Icone', 'rules' => 'permit_empty|max_length[40]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->tiposProjetosModel->insert($fields)) {

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

		$fields['codTipoProjeto'] = $this->request->getPost('codTipoProjeto');
		$fields['descricaoTipoProjeto'] = $this->request->getPost('descricaoTipoProjeto');
		$fields['ordem'] = $this->request->getPost('ordem');
		$fields['codLocalAtendimento'] = $this->request->getPost('codLocalAtendimento');
		$fields['prazo'] = $this->request->getPost('prazo');
		$fields['ativarNotificacao'] = $this->request->getPost('ativarNotificacao');
		$fields['nrdiasNotificacao'] = $this->request->getPost('nrdiasNotificacao');
		$fields['link'] = $this->request->getPost('link');
		$fields['icone'] = $this->request->getPost('icone');


		$this->validation->setRules([
			'codTipoProjeto' => ['label' => 'codTipoProjeto', 'rules' => 'required|numeric|max_length[11]'],
			'descricaoTipoProjeto' => ['label' => 'Descrição', 'rules' => 'required|max_length[100]'],
			'ordem' => ['label' => 'Ordem', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'codLocalAtendimento' => ['label' => 'Dependência', 'rules' => 'permit_empty|max_length[11]'],
			'prazo' => ['label' => 'Prazo', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'ativarNotificacao' => ['label' => 'Ativar Notificação', 'rules' => 'permit_empty|max_length[11]'],
			'nrdiasNotificacao' => ['label' => 'Nr dias Notificação', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'link' => ['label' => 'Link', 'rules' => 'permit_empty|max_length[150]'],
			'icone' => ['label' => 'Icone', 'rules' => 'permit_empty|max_length[40]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->tiposProjetosModel->update($fields['codTipoProjeto'], $fields)) {

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

		$id = $this->request->getPost('codTipoProjeto');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->tiposProjetosModel->where('codTipoProjeto', $id)->delete()) {

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
