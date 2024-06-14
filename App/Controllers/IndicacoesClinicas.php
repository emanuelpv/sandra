<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\IndicacoesClinicasModel;

class IndicacoesClinicas extends BaseController
{

	protected $IndicacoesClinicasModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->IndicacoesClinicasModel = new IndicacoesClinicasModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{



		$data = [
			'controller'    	=> 'indicacoesClinicas',
			'title'     		=> 'Indicações Clínicas'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('indicacoesClinicas', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->IndicacoesClinicasModel->indicacoesValidasPaciente(session()->codPaciente);

		$x = 0;
		foreach ($result as $key => $value) {
			$x++;
			$ops = '<div class="btn-group">';
			$ops .= '</div>';

			if (date('Y-m-d') > $value->dataEncerramento) {
				$dataValidade = '<span class="right badge badge-danger">' . date('d/m/Y', strtotime($value->dataEncerramento)) . '</span>';
			} else {

				$dataValidade = '<span class="right badge badge-success">' . date('d/m/Y', strtotime($value->dataEncerramento)) . '</span>';
			}
			if ($value->acompanhamentoPermanente == 1) {
				$dataValidade = '<span style="font-size:16px" class="right badge badge-success">Indeterminada</span>';
			}

			$data['data'][$key] = array(
				$x,
				$value->protocolo,
				$value->descricaoEspecialidade,
				date('d/m/Y', strtotime($value->dataInicio)),
				$dataValidade,
				$value->nomeExibicao,
				$ops,
			);
		}
		return $this->response->setJSON($data);
	}

	public function indicacoesPaciente()
	{
		$response = array();


		$codPaciente = $this->request->getPost('codPaciente');

		$data['data'] = array();

		$result = $this->IndicacoesClinicasModel->indicacoesPaciente($codPaciente);

		$x = 0;
		foreach ($result as $key => $value) {
			$x++;
			$ops = '<div class="btn-group">';
			if ($value->acompanhamentoPermanente == 0) {
				$ops .= '	<button type="button" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="Indicação Permanente"  onclick="acompanhamentoPermanente(' . $value->codIndicacaoClinica . ')"><i class="fas fa-user-md"> Acompanhamento contínuo</i></button>';
			}
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeindicacoesClinicas(' . $value->codIndicacaoClinica . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			if (date('Y-m-d') > $value->dataEncerramento) {
				$dataValidade = '<span style="font-size:16px" class="right badge badge-danger">' . date('d/m/Y', strtotime($value->dataEncerramento)) . '</span>';
			} else {

				$dataValidade = '<span style="font-size:16px" class="right badge badge-success">' . date('d/m/Y', strtotime($value->dataEncerramento)) . '</span>';
			}

			if ($value->acompanhamentoPermanente == 1) {
				$dataValidade = '<span style="font-size:16px" class="right badge badge-success">Indeterminada</span>';
			}

			$data['data'][$key] = array(
				$x,
				$value->protocolo,
				$value->descricaoEspecialidade,
				date('d/m/Y', strtotime($value->dataInicio)),
				$dataValidade,
				$value->justificativa,
				$value->nomeExibicao,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}
	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codIndicacaoClinica');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->IndicacoesClinicasModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{



		$response = array();

		$codPaciente = $this->request->getPost('codPaciente');
		$codEspecialidade = $this->request->getPost('codEspecialidade');

		//VERIFICA EXISTENCIA DE INDICAÇÃO AINDA VÁLIDA
		$verificaExistencia = $this->IndicacoesClinicasModel->verificaExistencia($codPaciente, $codEspecialidade);

		if ($verificaExistencia !== NULL) {

			$response['success'] = false;
			$response['messages'] = 'Essa especialidade já possui uma indicação para este paciente. <br> Indicação válida até ' . date('d/m/Y', strtotime($verificaExistencia->dataEncerramento));
			return $this->response->setJSON($response);
		}

		if ($this->request->getPost('acompanhamentoPermanente') == 'on') {

			$fields['acompanhamentoPermanente'] = 1;
		} else {

			$fields['acompanhamentoPermanente'] = 0;
		}

		$fields['codEspecialidade'] = $codEspecialidade;
		$fields['codIndicacaoClinica'] = $this->request->getPost('codIndicacaoClinica');
		$fields['codPaciente'] = $codPaciente;
		$fields['protocolo'] = date('Y') . str_pad($codPaciente, 6, '0', STR_PAD_LEFT)  . geraNumero(2);
		$fields['justificativa'] = $this->request->getPost('justificativa');
		$fields['dataInicio'] = date('Y-m-d H:i');
		$fields['dataEncerramento'] = date('Y-m-d', strtotime(' +120 days'));
		$fields['codAutor'] = session()->codPessoa;


		$this->validation->setRules([
			'codEspecialidade' => ['label' => 'CodEspecialidade', 'rules' => 'required|max_length[11]'],
			'protocolo' => ['label' => 'Protocolo', 'rules' => 'required|max_length[20]'],
			'justificativa' => ['label' => 'Justificativa', 'rules' => 'permit_empty|bloquearReservado'],
			'dataInicio' => ['label' => 'DataInicio', 'rules' => 'required'],
			'dataEncerramento' => ['label' => 'DataEncerramento', 'rules' => 'required'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
			'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->IndicacoesClinicasModel->insert($fields)) {

				$response['success'] = true;
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

		$fields['codIndicacaoClinica'] = $this->request->getPost('codIndicacaoClinica');
		$fields['codEspecialidade'] = $this->request->getPost('codEspecialidade');
		$fields['protocolo'] = $this->request->getPost('protocolo');
		$fields['justificativa'] = $this->request->getPost('justificativa');
		$fields['dataInicio'] = $this->request->getPost('dataInicio');
		$fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');
		$fields['codAutor'] = $this->request->getPost('codAutor');
		$fields['codPaciente'] = $this->request->getPost('codPaciente');


		$this->validation->setRules([
			'codIndicacaoClinica' => ['label' => 'codIndicacaoClinica', 'rules' => 'required|numeric|max_length[11]'],
			'codEspecialidade' => ['label' => 'CodEspecialidade', 'rules' => 'required|max_length[11]'],
			'protocolo' => ['label' => 'Protocolo', 'rules' => 'required|max_length[20]'],
			'justificativa' => ['label' => 'Justificativa', 'rules' => 'permit_empty|bloquearReservado'],
			'dataInicio' => ['label' => 'DataInicio', 'rules' => 'required'],
			'dataEncerramento' => ['label' => 'DataEncerramento', 'rules' => 'required'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
			'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->IndicacoesClinicasModel->update($fields['codIndicacaoClinica'], $fields)) {

				$response['success'] = true;
				$response['messages'] = 'Atualizado com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
			}
		}

		return $this->response->setJSON($response);
	}
	public function acompanhamentoPermanente()
	{

		$response = array();


		$fields['codIndicacaoClinica'] = $this->request->getPost('codIndicacaoClinica');
		$fields['acompanhamentoPermanente'] = 1;
		$fields['codAutor'] = session()->codPessoa;


		$this->validation->setRules([
			'codIndicacaoClinica' => ['label' => 'codIndicacaoClinica', 'rules' => 'required|max_length[11]'],
			'acompanhamentoPermanente' => ['label' => 'acompanhamentoPermanente', 'rules' => 'required|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->IndicacoesClinicasModel->update($fields['codIndicacaoClinica'], $fields)) {

				$response['success'] = true;
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

		$id = $this->request->getPost('codIndicacaoClinica');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->IndicacoesClinicasModel->where('codIndicacaoClinica', $id)->delete()) {

				$response['success'] = true;
				$response['messages'] = 'Deletado com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na deleção!';
			}
		}

		return $this->response->setJSON($response);
	}
}
