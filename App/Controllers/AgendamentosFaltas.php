<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\AgendamentosFaltasModel;

class AgendamentosFaltas extends BaseController
{

	protected $AgendamentosFaltasModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->AgendamentosFaltasModel = new AgendamentosFaltasModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('AgendamentosFaltas', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "AgendamentosFaltas"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'agendamentosFaltas',
			'title'     		=> 'Absenteísmo'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('agendamentosFaltas', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->AgendamentosFaltasModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';



			if ($value->impedidoAgendar == 1) {
				$status = "SIM";
				$ops .= '	<button type="button" class="btn btn-sm btn-danger"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="removerImpedimentoAgendar(' . $value->codAgendamentoFalta . ')"><i class="fa fa-edit"></i>Remover Impedimento</button>';
			} else {
				$status = "NÂO";
				$ops .= '	<button type="button" class="btn btn-sm btn-warning"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="adicionarImpedimentoAgendar(' . $value->codAgendamentoFalta . ')"><i class="fa fa-edit"></i>Adicionar Impedimento</button>';
			}
			$ops .= '</div>';

			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codAgendamentoFalta,
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

	public function faltasPaciente()
	{
		$response = array();

		$data['data'] = array();

		$codPaciente = $this->request->getPost('codPaciente');

		$result = $this->AgendamentosFaltasModel->faltasPaciente($codPaciente);

		foreach ($result as $key => $value) {

			$ops = '';



			if ($value->impedidoAgendar == 1) {
				$status = "SIM";
			} else {
				$status = "NÂO";
			}

			$data['csrf_token'] = csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codAgendamentoFalta,
				$value->descricaoEspecialidade,
				$value->nomeEspecialista,
				date('d/m/Y', strtotime($value->dataCriacao)),
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

		$id = $this->request->getPost('codAgendamentoFalta');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->AgendamentosFaltasModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codAgendamentoFalta'] = $this->request->getPost('codAgendamentoFalta');
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

			if ($this->AgendamentosFaltasModel->insert($fields)) {

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

		$fields['codAgendamentoFalta'] = $this->request->getPost('codAgendamentoFalta');
		$fields['codPaciente'] = $this->request->getPost('codPaciente');
		$fields['codEspecialidade'] = $this->request->getPost('codEspecialidade');
		$fields['codEspecialista'] = $this->request->getPost('codEspecialista');
		$fields['dataCriacao'] = $this->request->getPost('dataCriacao');
		$fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');
		$fields['impedidoAgendar'] = $this->request->getPost('impedidoAgendar');


		$this->validation->setRules([
			'codAgendamentoFalta' => ['label' => 'codAgendamentoFalta', 'rules' => 'required|numeric|max_length[11]'],
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

			if ($this->AgendamentosFaltasModel->update($fields['codAgendamentoFalta'], $fields)) {

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
	public function adicionarImpedimentoAgendar()
	{

		$response = array();

		$fields['codAgendamentoFalta'] = $this->request->getPost('codAgendamentoFalta');
		$fields['codAutor'] = session()->codPessoa;
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['impedidoAgendar'] = 1;


		$this->validation->setRules([
			'codAgendamentoFalta' => ['label' => 'codAgendamentoFalta', 'rules' => 'required|numeric|max_length[11]'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
			'impedidoAgendar' => ['label' => 'Impedido de Agendar', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AgendamentosFaltasModel->update($fields['codAgendamentoFalta'], $fields)) {

				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['messages'] = 'Impedimento adicionado com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
			}
		}

		return $this->response->setJSON($response);
	}
	public function removerImpedimentoAgendar()
	{

		$response = array();

		$fields['codAgendamentoFalta'] = $this->request->getPost('codAgendamentoFalta');
		$fields['codAutor'] = session()->codPessoa;
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['impedidoAgendar'] = 0;


		$this->validation->setRules([
			'codAgendamentoFalta' => ['label' => 'codAgendamentoFalta', 'rules' => 'required|numeric|max_length[11]'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
			'impedidoAgendar' => ['label' => 'Impedido de Agendar', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AgendamentosFaltasModel->update($fields['codAgendamentoFalta'], $fields)) {

				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['messages'] = 'Impedimento removido com sucesso';
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

		$id = $this->request->getPost('codAgendamentoFalta');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->AgendamentosFaltasModel->where('codAgendamentoFalta', $id)->delete()) {

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
