<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\PacientesMedicamentosContinuoModel;

class PacientesMedicamentosContinuo extends BaseController
{

	protected $PacientesMedicamentosContinuoModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->PacientesMedicamentosContinuoModel = new PacientesMedicamentosContinuoModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('PacientesMedicamentosContinuo', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "PacientesMedicamentosContinuo"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'pacientesMedicamentosContinuo',
			'title'     		=> 'Medicamentos Contínuo'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('pacientesMedicamentosContinuo', $data);
	}


	public function getAllcodPaciente()
	{
		$response = array();

		$data['data'] = array();
		$codPaciente = $this->request->getPost('codPaciente');
		$result = $this->PacientesMedicamentosContinuoModel->pegaTudoPaciente($codPaciente);
		$x = 0;
		foreach ($result as $key => $value) {
			$x++;
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removepacientesMedicamentosContinuo(' . $value->codPacienteMedicamento . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$x,
				$value->descricaoMedicamento,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->PacientesMedicamentosContinuoModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editpacientesMedicamentosContinuo(' . $value->codPacienteMedicamento . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removepacientesMedicamentosContinuo(' . $value->codPacienteMedicamento . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codPacienteMedicamento,
				$value->codPaciente,
				$value->descricaoMedicamento,
				$value->observacao,
				$value->dataCriacao,
				$value->codAutor,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codPacienteMedicamento');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->PacientesMedicamentosContinuoModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codPacienteMedicamento'] = $this->request->getPost('codPacienteMedicamento');
		$fields['codPaciente'] = $this->request->getPost('codPaciente');
		$fields['descricaoMedicamento'] = $this->request->getPost('descricaoMedicamento');
		$fields['observacao'] = $this->request->getPost('observacao');
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['codAutor'] = session()->codPessoa;


		$this->validation->setRules([
			'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'required|numeric|max_length[11]'],
			'descricaoMedicamento' => ['label' => 'DescricaoMedicamento', 'rules' => 'required'],
			'observacao' => ['label' => 'Observacao', 'rules' => 'permit_empty'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->PacientesMedicamentosContinuoModel->insert($fields)) {

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

		$fields['codPacienteMedicamento'] = $this->request->getPost('codPacienteMedicamento');
		$fields['codPaciente'] = $this->request->getPost('codPaciente');
		$fields['descricaoMedicamento'] = $this->request->getPost('descricaoMedicamento');
		$fields['observacao'] = $this->request->getPost('observacao');
		$fields['dataCriacao'] = $this->request->getPost('dataCriacao');
		$fields['codAutor'] = $this->request->getPost('codAutor');


		$this->validation->setRules([
			'codPacienteMedicamento' => ['label' => 'codPacienteMedicamento', 'rules' => 'required|numeric|max_length[11]'],
			'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'required|numeric|max_length[11]'],
			'descricaoMedicamento' => ['label' => 'DescricaoMedicamento', 'rules' => 'required|max_length[60]'],
			'observacao' => ['label' => 'Observacao', 'rules' => 'required'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->PacientesMedicamentosContinuoModel->update($fields['codPacienteMedicamento'], $fields)) {

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

		$id = $this->request->getPost('codPacienteMedicamento');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->PacientesMedicamentosContinuoModel->where('codPacienteMedicamento', $id)->delete()) {

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
