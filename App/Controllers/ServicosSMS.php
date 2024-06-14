<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\ServicosSMSModel;

class ServicosSMS extends BaseController
{

	protected $servicosSMSModel;
	protected $pessoasModel;
	protected $organizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->ServicosSMSModel = new ServicosSMSModel();
		$this->organizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->organizacao =  $this->organizacoesModel->pegaOrganizacao($this->codOrganizacao);

		$permissao = verificaPermissao('ServicosSMS', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo ServicosSMS', session()->codPessoa);
			exit();
		}
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'servicosSMS',
			'title'     		=> 'Serviços de SMS'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('servicosSMS', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->ServicosSMSModel->pegaTudo();
		$x = 0;

		// SERVIÇOS:
		// 1 - ZENVIA
		// 2 - MEX
		// 3 - TWILIO



		foreach ($result as $key => $value) {
			$x++;
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editservicosSMS(' . $value->codServicoSMS . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeservicosSMS(' . $value->codServicoSMS . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			if ($value->codProvedor == 1) {
				$provedor = 'ZENVIA';
			}
			if ($value->codProvedor == 2) {
				$provedor = 'MEX';
			}
			if ($value->codProvedor == 3) {
				$provedor = 'TWILIO';
			}


			if ($value->statusSMS == 1) {
				$statusSMS = 'Ativado';
			}
			if ($value->statusSMS == 0) {
				$statusSMS = 'Desativado';
			}




			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$x,
				$provedor,
				$statusSMS,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codServicoSMS');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->ServicosSMSModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codServicoSMS'] = $this->request->getPost('codServicoSMS');
		$fields['codOrganizacao'] = session()->codOrganizacao;
		$fields['codProvedor'] = $this->request->getPost('codProvedor');
		$fields['token'] = $this->request->getPost('token');
		$fields['conta'] = $this->request->getPost('conta');
		$fields['statusSMS'] = $this->request->getPost('statusSMS');


		$this->validation->setRules([
			'codOrganizacao' => ['label' => 'CodOrganizacao', 'rules' => 'required|numeric|max_length[11]'],
			'codProvedor' => ['label' => 'CodProvedor', 'rules' => 'required|max_length[11]'],
			'token' => ['label' => 'Token', 'rules' => 'permit_empty|max_length[100]'],
			'conta' => ['label' => 'Conta', 'rules' => 'permit_empty|max_length[100]'],
			'statusSMS' => ['label' => 'StatusSMS', 'rules' => 'required|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->ServicosSMSModel->insert($fields)) {

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

		$fields['codServicoSMS'] = $this->request->getPost('codServicoSMS');
		$fields['codOrganizacao'] = session()->codOrganizacao;
		$fields['codProvedor'] = $this->request->getPost('codProvedor');
		$fields['token'] = $this->request->getPost('token');
		$fields['conta'] = $this->request->getPost('conta');
		$fields['statusSMS'] = $this->request->getPost('statusSMS');


		$this->validation->setRules([
			'codServicoSMS' => ['label' => 'codServicoSMS', 'rules' => 'required|numeric|max_length[11]'],
			'codOrganizacao' => ['label' => 'CodOrganizacao', 'rules' => 'required|numeric|max_length[11]'],
			'codProvedor' => ['label' => 'CodProvedor', 'rules' => 'required|max_length[11]'],
			'token' => ['label' => 'Token', 'rules' => 'permit_empty|max_length[100]'],
			'conta' => ['label' => 'Conta', 'rules' => 'permit_empty|max_length[100]'],
			'statusSMS' => ['label' => 'StatusSMS', 'rules' => 'required|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->ServicosSMSModel->update($fields['codServicoSMS'], $fields)) {

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

		$id = $this->request->getPost('codServicoSMS');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->ServicosSMSModel->where('codServicoSMS', $id)->delete()) {

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
