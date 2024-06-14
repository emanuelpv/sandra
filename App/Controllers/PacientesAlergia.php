<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\PacientesAlergiaModel;

class PacientesAlergia extends BaseController
{

	protected $PacientesAlergiaModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->PacientesAlergiaModel = new PacientesAlergiaModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('PacientesAlergia', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "PacientesAlergia"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'pacientesAlergia',
			'title'     		=> 'Alergias do Paciente'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('pacientesAlergia', $data);
	}


	public function listaDropDown()
	{

		$result = $this->PacientesAlergiaModel->listaDropDown();

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

		$result = $this->PacientesAlergiaModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editpacientesAlergia(' . $value->codPacienteAlergia . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removepacientesAlergia(' . $value->codPacienteAlergia . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codPacienteAlergia,
				$value->codPaciente,
				$value->descricaoAlergenico,
				$value->codTipoAlergenico,
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

		$id = $this->request->getPost('codPacienteAlergia');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->PacientesAlergiaModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codPacienteAlergia'] = $this->request->getPost('codPacienteAlergia');
		$fields['codPaciente'] = $this->request->getPost('codPaciente');
		$fields['descricaoAlergenico'] = $this->request->getPost('descricaoAlergenico');
		$fields['codTipoAlergenico'] = $this->request->getPost('codTipoAlergenico');
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['codAutor'] = session()->codPessoa;


		$this->validation->setRules([
			'codPaciente' => ['label' => 'Paciente', 'rules' => 'required|numeric|max_length[11]'],
			'descricaoAlergenico' => ['label' => 'Descrição do Alergênico', 'rules' => 'permit_empty|max_length[60]'],
			'codTipoAlergenico' => ['label' => 'Tipo', 'rules' => 'required|max_length[11]'],
			'dataCriacao' => ['label' => 'Data Inclusão', 'rules' => 'required'],
			'codAutor' => ['label' => 'Autor', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->PacientesAlergiaModel->insert($fields)) {

				$response['success'] = true;
				$response['messages'] = 'Alergênico inserido com sucesso';
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

		$fields['codPacienteAlergia'] = $this->request->getPost('codPacienteAlergia');
		$fields['codPaciente'] = $this->request->getPost('codPaciente');
		$fields['descricaoAlergenico'] = $this->request->getPost('descricaoAlergenico');
		$fields['codTipoAlergenico'] = $this->request->getPost('codTipoAlergenico');
		$fields['dataCriacao'] = $this->request->getPost('dataCriacao');
		$fields['codAutor'] = $this->request->getPost('codAutor');


		$this->validation->setRules([
			'codPacienteAlergia' => ['label' => 'codPacienteAlergia', 'rules' => 'required|numeric|max_length[11]'],
			'codPaciente' => ['label' => 'Paciente', 'rules' => 'required|numeric|max_length[11]'],
			'descricaoAlergenico' => ['label' => 'Descrição do Alergênico', 'rules' => 'required|max_length[60]'],
			'codTipoAlergenico' => ['label' => 'Tipo', 'rules' => 'required|max_length[11]'],
			'dataCriacao' => ['label' => 'Data Inclusão', 'rules' => 'required'],
			'codAutor' => ['label' => 'Autor', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->PacientesAlergiaModel->update($fields['codPacienteAlergia'], $fields)) {

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

		$id = $this->request->getPost('codPacienteAlergia');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->PacientesAlergiaModel->where('codPacienteAlergia', $id)->delete()) {

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
