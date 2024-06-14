<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\AtoCirurgicoMembrosModel;

class AtoCirurgicoMembros extends BaseController
{

	protected $AtoCirurgicoMembrosModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->AtoCirurgicoMembrosModel = new AtoCirurgicoMembrosModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}


	public function pegaMembros()
	{
		$response = array();

		$listaMembros = array();
		$membros = $this->AtoCirurgicoMembrosModel->pegaMembros();

		foreach ($membros as $membro) {
			array_push($listaMembros, $membro->nomeMembro);
		}
		$response['membros'] = json_encode($listaMembros);

		return $this->response->setJSON($response);
	}

	public function pegaConselhos()
	{
		$response = array();

		$listaConselhos = array();
		$conselhos = $this->AtoCirurgicoMembrosModel->pegaConselhos();

		foreach ($conselhos as $conselho) {
			array_push($listaConselhos, $conselho->nomeConselho);
		}
		$response['conselhos'] = json_encode($listaConselhos);

		return $this->response->setJSON($response);
	}

	public function index()
	{

		$permissao = verificaPermissao('AtoCirurgicoMembros', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "AtoCirurgicoMembros"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'atoCirurgicoMembros',
			'title'     		=> 'Membros Ato Cirúrgico'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('atoCirurgicoMembros', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->AtoCirurgicoMembrosModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editatoCirurgicoMembros(' . $value->codAtoCirurgicoMembro . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeatoCirurgicoMembros(' . $value->codAtoCirurgicoMembro . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$value->codAtoCirurgicoMembro,
				$value->codAtoCirurgico,
				$value->nomeMembro,
				$value->inscricaoMembro,
				$value->dataCriacao,
				$value->dataAtualizacao,
				$value->codAutor,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}
	public function getAllMembrosAtoCirurgico()
	{
		$response = array();

		$data['data'] = array();


		$codAtoCirurgico = $this->request->getPost('codAtoCirurgico');

		$result = $this->AtoCirurgicoMembrosModel->getAllMembrosAtoCirurgico($codAtoCirurgico);

		$x = 0;
		foreach ($result as $key => $value) {
			$x++;

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeatoCirurgicoMembros(' . $value->codAtoCirurgicoMembro . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$x,
				$value->nomeMembro,
				$value->conselhoMembro,
				$value->inscricaoMembro,
				$value->descricaoFuncao,
				$value->siglaEstadoFederacao,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}


	public function listaDropDownFuncoes()
	{

		$result = $this->AtoCirurgicoMembrosModel->listaDropDownFuncoes();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function pegaDadosMembro()
	{

		$result = array();
		$response = array();
		$nomeMembroAtoCirurgico =  $this->request->getPost('nomeMembroAtoCirurgico');;

		if ($this->validation->check($nomeMembroAtoCirurgico, 'required')) {
			$result = $this->AtoCirurgicoMembrosModel->pegaDadosMembro($nomeMembroAtoCirurgico);
		}
		
		$response['conselhoMembro'] = $result->conselhoMembro;
		$response['inscricaoMembro'] = $result->inscricaoMembro;
		$response['codEstadoFederacao'] = $result->codEstadoFederacao;

		return $this->response->setJSON($response);
	}


	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codAtoCirurgicoMembro');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->AtoCirurgicoMembrosModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codAtoCirurgico'] = $this->request->getPost('codAtoCirurgico');
		$fields['nomeMembro'] = mb_strtoupper($this->request->getPost('nomeMembro'),'utf8');
		$fields['inscricaoMembro'] = $this->request->getPost('inscricaoMembro');
		$fields['conselhoMembro'] = $this->request->getPost('conselhoMembro');
		$fields['codFuncaoMembro'] = $this->request->getPost('codFuncaoMembro');
		$fields['codEstadoFederacao'] = $this->request->getPost('codEstadoFederacao');
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['codAutor'] = session()->codPessoa;


		$this->validation->setRules([
			'codAtoCirurgico' => ['label' => 'CodAtoCirurgico', 'rules' => 'required|numeric|max_length[11]'],
			'nomeMembro' => ['label' => 'NomeMembro', 'rules' => 'required|max_length[100]'],
			'conselhoMembro' => ['label' => 'conselhoMembro', 'rules' => 'required|max_length[20]'],
			'inscricaoMembro' => ['label' => 'inscricaoMembro', 'rules' => 'required|max_length[20]'],
			'codFuncaoMembro' => ['label' => 'codFuncaoMembro', 'rules' => 'required|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtoCirurgicoMembrosModel->insert($fields)) {

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

		$fields['codAtoCirurgicoMembro'] = $this->request->getPost('codAtoCirurgicoMembro');
		$fields['codAtoCirurgico'] = $this->request->getPost('codAtoCirurgico');
		$fields['nomeMembro'] = $this->request->getPost('nomeMembro');
		$fields['inscricaoMembro'] = $this->request->getPost('inscricaoMembro');
		$fields['dataCriacao'] = $this->request->getPost('dataCriacao');
		$fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');
		$fields['codAutor'] = $this->request->getPost('codAutor');


		$this->validation->setRules([
			'codAtoCirurgicoMembro' => ['label' => 'CodAtoCirurgicoMembro', 'rules' => 'required|numeric|max_length[11]'],
			'codAtoCirurgico' => ['label' => 'CodAtoCirurgico', 'rules' => 'required|numeric|max_length[11]'],
			'nomeMembro' => ['label' => 'NomeMembro', 'rules' => 'required|max_length[100]'],
			'inscricaoMembro' => ['label' => 'inscricaoMembro', 'rules' => 'required|max_length[20]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtoCirurgicoMembrosModel->update($fields['codAtoCirurgicoMembro'], $fields)) {

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

		$id = $this->request->getPost('codAtoCirurgicoMembro');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->AtoCirurgicoMembrosModel->where('codAtoCirurgicoMembro', $id)->delete()) {

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
