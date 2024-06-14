<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\StatusSuporteModel;

class StatusSuporte extends BaseController
{

	protected $statusSuporteModel;
	protected $pessoasModel;
	protected $organizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->StatusSuporteModel = new StatusSuporteModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);

		$permissao = verificaPermissao('StatusSuporte', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo StatusSuporte', session()->codPessoa);
			exit();
		}
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'statusSuporte',
			'title'     		=> 'Status de Suporte'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('statusSuporte', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->StatusSuporteModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Editar" onclick="editstatusSuporte(' . $value->codStatusSuporte . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="removestatusSuporte(' . $value->codStatusSuporte . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codStatusSuporte,
				$value->descricaoStatusSuporte,
				$value->codCorStatusSuporte . ' <i style="width:200px;color:' . $value->codCorStatusSuporte . '" class="fas fa-square"></i>',

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codStatusSuporte');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->StatusSuporteModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function listaDropDown()
	{

		$result = $this->StatusSuporteModel->listaDropDown();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function listaPercentualConclusao()
	{

		$result = $this->StatusSuporteModel->listaPercentualConclusao();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}



	public function add()
	{

		$response = array();

		$fields['codStatusSuporte'] = $this->request->getPost('codStatusSuporte');
		$fields['descricaoStatusSuporte'] = $this->request->getPost('descricaoStatusSuporte');
		$fields['codCorStatusSuporte'] = 'xxx';


		$this->validation->setRules([
			'descricaoStatusSuporte' => ['label' => 'Descrição do Status', 'rules' => 'required|max_length[50]'],
			'codCorStatusSuporte' => ['label' => 'codCorStatusSuporte', 'rules' => 'max_length[20]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->StatusSuporteModel->insert($fields)) {

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

		$fields['codStatusSuporte'] = $this->request->getPost('codStatusSuporte');
		$fields['descricaoStatusSuporte'] = $this->request->getPost('descricaoStatusSuporte');
		$fields['codCorStatusSuporte'] = $this->request->getPost('codCorStatusSuporte');


		$this->validation->setRules([
			'codStatusSuporte' => ['label' => 'codStatusSuporte', 'rules' => 'required|numeric|max_length[11]'],
			'descricaoStatusSuporte' => ['label' => 'Descrição do Status', 'rules' => 'required|max_length[50]'],
			'codCorStatusSuporte' => ['label' => 'codCorStatusSuporte', 'rules' => 'max_length[20]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->StatusSuporteModel->update($fields['codStatusSuporte'], $fields)) {

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

		$id = $this->request->getPost('codStatusSuporte');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->StatusSuporteModel->where('codStatusSuporte', $id)->delete()) {

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
