<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\EspecialidadesMembroModel;

class EspecialidadesMembro extends BaseController
{

	protected $EspecialidadesMembroModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->EspecialidadesMembroModel = new EspecialidadesMembroModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);

		$permissao = verificaPermissao('EspecialidadesMembro', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "EspecialidadesMembro"', session()->codPessoa);
			exit();
		}
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'especialidadesMembro',
			'title'     		=> 'Especialidades membros'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('especialidadesMembro', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->EspecialidadesMembroModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editespecialidadesMembro(' . $value->codEspecialidadeMembro . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeespecialidadesMembro(' . $value->codEspecialidadeMembro . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codEspecialidadeMembro,
				$value->codEspecialidade,
				$value->codPessoa,
				$value->codEstadoFederacao,
				$value->numeroInscricao,
				$value->numeroSire,
				$value->observacoes,
				$value->dataCriacao,
				$value->dataAtualizacao,
				$value->autor,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codEspecialidadeMembro');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->EspecialidadesMembroModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codEspecialidadeMembro'] = $this->request->getPost('codEspecialidadeMembro');
		$fields['codEspecialidade'] = $this->request->getPost('codEspecialidade');
		$fields['codPessoa'] = $this->request->getPost('codPessoa');
		$fields['codEstadoFederacao'] = $this->request->getPost('codEstadoFederacao');
		$fields['numeroInscricao'] = $this->request->getPost('numeroInscricao');
		$fields['numeroSire'] = $this->request->getPost('numeroSire');
		$fields['observacoes'] = $this->request->getPost('observacoes');
		$fields['dataCriacao'] = $this->request->getPost('dataCriacao');
		$fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');
		$fields['autor'] = $this->request->getPost('autor');


		$this->validation->setRules([
			'codEspecialidade' => ['label' => 'CodEspecialidade', 'rules' => 'required|numeric|max_length[11]'],
			'codPessoa' => ['label' => 'CodPessoa', 'rules' => 'required|numeric|max_length[11]'],
			'codEstadoFederacao' => ['label' => 'CodEstadoFederacao', 'rules' => 'required|numeric|max_length[11]'],
			'numeroInscricao' => ['label' => 'NumeroInscricao', 'rules' => 'required|max_length[20]'],
			'numeroSire' => ['label' => 'NumeroSire', 'rules' => 'required|max_length[20]'],
			'observacoes' => ['label' => 'Observacoes', 'rules' => 'permit_empty|max_length[20]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
			'autor' => ['label' => 'Autor', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->EspecialidadesMembroModel->insert($fields)) {

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

		$fields['codEspecialidadeMembro'] = $this->request->getPost('codEspecialidadeMembro');
		$fields['codEspecialidade'] = $this->request->getPost('codEspecialidade');
		$fields['codPessoa'] = $this->request->getPost('codPessoa');
		$fields['codEstadoFederacao'] = $this->request->getPost('codEstadoFederacao');
		$fields['numeroInscricao'] = $this->request->getPost('numeroInscricao');
		$fields['numeroSire'] = $this->request->getPost('numeroSire');
		$fields['observacoes'] = $this->request->getPost('observacoes');
		$fields['dataCriacao'] = $this->request->getPost('dataCriacao');
		$fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');
		$fields['autor'] = $this->request->getPost('autor');


		$this->validation->setRules([
			'codEspecialidadeMembro' => ['label' => 'codEspecialidadeMembro', 'rules' => 'required|numeric|max_length[11]'],
			'codEspecialidade' => ['label' => 'CodEspecialidade', 'rules' => 'required|numeric|max_length[11]'],
			'codPessoa' => ['label' => 'CodPessoa', 'rules' => 'required|numeric|max_length[11]'],
			'codEstadoFederacao' => ['label' => 'CodEstadoFederacao', 'rules' => 'required|numeric|max_length[11]'],
			'numeroInscricao' => ['label' => 'NumeroInscricao', 'rules' => 'required|max_length[20]'],
			'numeroSire' => ['label' => 'NumeroSire', 'rules' => 'required|max_length[20]'],
			'observacoes' => ['label' => 'Observacoes', 'rules' => 'permit_empty|max_length[20]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
			'autor' => ['label' => 'Autor', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->EspecialidadesMembroModel->update($fields['codEspecialidadeMembro'], $fields)) {

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

		$id = $this->request->getPost('codEspecialidadeMembro');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->EspecialidadesMembroModel->where('codEspecialidadeMembro', $id)->delete()) {

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
