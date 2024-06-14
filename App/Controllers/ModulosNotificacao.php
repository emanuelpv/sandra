<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\ModulosNotificacaoModel;

class ModulosNotificacao extends BaseController
{

	protected $modulosNotificacaoModel;
	protected $pessoasModel;
	protected $organizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->modulosNotificacaoModel = new ModulosNotificacaoModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);


		$permissao = verificaPermissao('ModulosNotificacao', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo ModulosNotificacao', session()->codPessoa);
			exit();
		}
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'modulosNotificacao',
			'title'     		=> 'Notificações dos Módulos'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('modulosNotificacao', $data);
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


	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->modulosNotificacaoModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Editar" onclick="editmodulosNotificacao(' . $value->codModuloNotificacao . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="removemodulosNotificacao(' . $value->codModuloNotificacao . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';





			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codModuloNotificacao,
				$value->codTipoNotificacao,
				$value->codModeloNotificacao,
				$value->destinoNotificacao,
				$value->observacoes,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codModuloNotificacao');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->modulosNotificacaoModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codOrganizacao'] = session()->codOrganizacao;
		$fields['codModulo'] = $this->request->getPost('codModulo');
		$fields['codModuloNotificacao'] = $this->request->getPost('codModuloNotificacao');
		$fields['codTipoNotificacao'] = $this->request->getPost('codTipoNotificacao');
		$fields['codModeloNotificacao'] = $this->request->getPost('codModeloNotificacao');

		$fields['destinoNotificacao'] = implode(',', $this->request->getPost('destinoNotificacao'));

		$fields['observacoes'] = $this->request->getPost('observacoes');


		$this->validation->setRules([
			'codTipoNotificacao' => ['label' => 'Tipo', 'rules' => 'max_length[11]'],
			'codModeloNotificacao' => ['label' => 'Modelo', 'rules' => 'required|max_length[11]'],
			'destinoNotificacao' => ['label' => 'Destino', 'rules' => 'required'],
			'observacoes' => ['label' => 'Observacoes', 'rules' => 'permit_empty|max_length[60]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->modulosNotificacaoModel->insert($fields)) {

				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['messages'] = 'Informação inserida com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = $fields['codTipoNotificacao'];
			}
		}

		return $this->response->setJSON($response);
	}

	public function edit()
	{

		$response = array();

		$fields['codOrganizacao'] = session()->codOrganizacao;
		$fields['codModuloNotificacao'] = $this->request->getPost('codModuloNotificacao');
		$fields['codTipoNotificacao'] = $this->request->getPost('codTipoNotificacao');
		$fields['codModeloNotificacao'] = $this->request->getPost('codModeloNotificacao');

		$fields['destinoNotificacao'] = implode(',', $this->request->getPost('destinoNotificacao'));



		$this->validation->setRules([
			'codModuloNotificacao' => ['label' => 'codModuloNotificacao', 'rules' => 'required|numeric|max_length[11]'],
			'codTipoNotificacao' => ['label' => 'Tipo', 'rules' => 'required|max_length[11]'],
			'codModeloNotificacao' => ['label' => 'Modelo', 'rules' => 'required|max_length[11]'],
			'destinoNotificacao' => ['label' => 'Destino', 'rules' => 'required'],
			'observacoes' => ['label' => 'Observacoes', 'rules' => 'permit_empty|max_length[60]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->modulosNotificacaoModel->update($fields['codModuloNotificacao'], $fields)) {

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

		$id = $this->request->getPost('codModuloNotificacao');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->modulosNotificacaoModel->where('codModuloNotificacao', $id)->delete()) {

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



	function getNomeExibicaoPessoa($codPessoa)
	{
		$pessoas = $this->PessoasModel->pegaPessoaPorCodPessoa($codPessoa);
		return $pessoas->nomeExibicao;
	}
}
