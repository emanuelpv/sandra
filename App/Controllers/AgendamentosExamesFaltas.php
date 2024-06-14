<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\AgendamentosExamesFaltasModel;

class AgendamentosExamesFaltas extends BaseController
{

	protected $AgendamentosExamesFaltasModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->AgendamentosExamesFaltasModel = new AgendamentosExamesFaltasModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('AgendamentosExamesFaltas', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "AgendamentosExamesFaltas"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'agendamentosExamesFaltas',
			'title'     		=> 'Absenteísmo'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('agendamentosExamesFaltas', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->AgendamentosExamesFaltasModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="removerImpedimentoAgendar(' . $value->codExameFalta . ')"><i class="fa fa-edit"></i>Remover Impedimento agenda</button>';
			$ops .= '</div>';

			if ($value->impedidoAgendar == 1) {
				$status = "SIM";
			}


			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codExameFalta,
				$value->nomePaciente,
				$value->descricaoEspecialidade,
				$value->nomeEspecialista,
				$value->dataCriacao,
				date('d/m/Y', strtotime($value->dataInicioImpedimento)),
				date('d/m/Y', strtotime($value->dataEncerramentoImpedimento)),
				$status,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codExameFalta');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->AgendamentosExamesFaltasModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codExameFalta'] = $this->request->getPost('codExameFalta');
		$fields['codPaciente'] = $this->request->getPost('codPaciente');
		$fields['codEspecialidade'] = $this->request->getPost('codEspecialidade');
		$fields['codEspecialista'] = $this->request->getPost('codEspecialista');
		$fields['dataCriacao'] = $this->request->getPost('dataCriacao');
		$fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');
		$fields['impedidoAgendar'] = $this->request->getPost('impedidoAgendar');


		$this->validation->setRules([
			'codPaciente' => ['label' => 'Paciente', 'rules' => 'required|numeric|max_length[11]'],
			'codEspecialidade' => ['label' => 'Especialidade', 'rules' => 'required|numeric|max_length[11]'],
			'codEspecialista' => ['label' => 'Especialista', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'Data da Falta', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
			'impedidoAgendar' => ['label' => 'Impedido de Agendar', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AgendamentosExamesFaltasModel->insert($fields)) {

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

		$fields['codExameFalta'] = $this->request->getPost('codExameFalta');
		$fields['codPaciente'] = $this->request->getPost('codPaciente');
		$fields['codEspecialidade'] = $this->request->getPost('codEspecialidade');
		$fields['codEspecialista'] = $this->request->getPost('codEspecialista');
		$fields['dataCriacao'] = $this->request->getPost('dataCriacao');
		$fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');
		$fields['impedidoAgendar'] = $this->request->getPost('impedidoAgendar');


		$this->validation->setRules([
			'codExameFalta' => ['label' => 'codExameFalta', 'rules' => 'required|numeric|max_length[11]'],
			'codPaciente' => ['label' => 'Paciente', 'rules' => 'required|numeric|max_length[11]'],
			'codEspecialidade' => ['label' => 'Especialidade', 'rules' => 'required|numeric|max_length[11]'],
			'codEspecialista' => ['label' => 'Especialista', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'Data da Falta', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
			'impedidoAgendar' => ['label' => 'Impedido de Agendar', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AgendamentosExamesFaltasModel->update($fields['codExameFalta'], $fields)) {

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

		$id = $this->request->getPost('codExameFalta');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->AgendamentosExamesFaltasModel->where('codExameFalta', $id)->delete()) {

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
