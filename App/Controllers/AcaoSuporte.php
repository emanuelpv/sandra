<?php
// Desenvolvido por sucesso Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\AcaoSuporteModel;

class AcaoSuporte extends BaseController
{

	protected $acaoSuporteModel;
	protected $pessoasModel;
	protected $organizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->acaoSuporteModel = new AcaoSuporteModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);

		$permissao = verificaPermissao('AcaoSuporte', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo AcaoSuporte', session()->codPessoa);
			exit();
		}
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'acaoSuporte',
			'title'     		=> 'Ação Suporte'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('acaoSuporte', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->acaoSuporteModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Editar" onclick="editacaoSuporte(' . $value->codAcaoSuporte . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="removeacaoSuporte(' . $value->codAcaoSuporte . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codAcaoSuporte,
				$value->codSolicitacao,
				$value->codPessoa,
				$value->descricaoAcao,
				$value->dataInício,
				$value->codStatusSolicitacao,
				$value->percentualConclusao,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codAcaoSuporte');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->acaoSuporteModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codAcaoSuporte'] = $this->request->getPost('codAcaoSuporte');
		$fields['codSolicitacao'] = $this->request->getPost('codSolicitacao');
		$fields['codPessoa'] = $this->request->getPost('codPessoa');
		$fields['descricaoAcao'] = $this->request->getPost('descricaoAcao');
		$fields['dataInício'] = $this->request->getPost('dataInício');
		$fields['codStatusSolicitacao'] = $this->request->getPost('codStatusSolicitacao');
		$fields['percentualConclusao'] = $this->request->getPost('percentualConclusao');


		$this->validation->setRules([
			'codSolicitacao' => ['label' => 'CodSolicitacao', 'rules' => 'required|numeric|max_length[11]'],
			'codPessoa' => ['label' => 'CodPessoa', 'rules' => 'required|numeric|max_length[11]'],
			'descricaoAcao' => ['label' => 'DescricaoAcao', 'rules' => 'required'],
			'dataInício' => ['label' => 'DataInício', 'rules' => 'required'],
			'codStatusSolicitacao' => ['label' => 'CodStatusSolicitacao', 'rules' => 'required|numeric|max_length[11]'],
			'percentualConclusao' => ['label' => 'PercentualConclusao', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->acaoSuporteModel->insert($fields)) {

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

		$fields['codAcaoSuporte'] = $this->request->getPost('codAcaoSuporte');
		$fields['codSolicitacao'] = $this->request->getPost('codSolicitacao');
		$fields['codPessoa'] = $this->request->getPost('codPessoa');
		$fields['descricaoAcao'] = $this->request->getPost('descricaoAcao');
		$fields['dataInício'] = $this->request->getPost('dataInício');
		$fields['codStatusSolicitacao'] = $this->request->getPost('codStatusSolicitacao');
		$fields['percentualConclusao'] = $this->request->getPost('percentualConclusao');


		$this->validation->setRules([
			'codSolicitacao' => ['label' => 'CodSolicitacao', 'rules' => 'required|numeric|max_length[11]'],
			'codPessoa' => ['label' => 'CodPessoa', 'rules' => 'required|numeric|max_length[11]'],
			'descricaoAcao' => ['label' => 'DescricaoAcao', 'rules' => 'required'],
			'dataInício' => ['label' => 'DataInício', 'rules' => 'required'],
			'codStatusSolicitacao' => ['label' => 'CodStatusSolicitacao', 'rules' => 'required|numeric|max_length[11]'],
			'percentualConclusao' => ['label' => 'PercentualConclusao', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->acaoSuporteModel->update($fields['codAcaoSuporte'], $fields)) {

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

		$id = $this->request->getPost('codAcaoSuporte');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->acaoSuporteModel->where('codAcaoSuporte', $id)->delete()) {

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
