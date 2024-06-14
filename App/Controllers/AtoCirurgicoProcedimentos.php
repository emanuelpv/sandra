<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\AtoCirurgicoProcedimentosModel;

class AtoCirurgicoProcedimentos extends BaseController
{

	protected $AtoCirurgicoProcedimentosModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->AtoCirurgicoProcedimentosModel = new AtoCirurgicoProcedimentosModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('AtoCirurgicoProcedimentos', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "AtoCirurgicoProcedimentos"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'atoCirurgicoProcedimentos',
			'title'     		=> 'Procedimentos Ato Cirúrgico'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('atoCirurgicoProcedimentos', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->AtoCirurgicoProcedimentosModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editatoCirurgicoProcedimentos(' . $value->codAtoCirurgicoProcedimento . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeatoCirurgicoProcedimentos(' . $value->codAtoCirurgicoProcedimento . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$value->codAtoCirurgicoProcedimento,
				$value->codAtoCirurgico,
				$value->codProcedimento,
				$value->qtde,
				$value->observacao,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getAllProcedimentosAtoCirurgico()
	{
		$response = array();

		$data['data'] = array();
		$codAtoCirurgico = $this->request->getPost('codAtoCirurgico');

		$result = $this->AtoCirurgicoProcedimentosModel->getAllProcedimentosAtoCirurgico($codAtoCirurgico);


		$x = 0;
		foreach ($result as $key => $value) {
			$x++;

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editatoCirurgicoProcedimentos(' . $value->codAtoCirurgicoProcedimento . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeatoCirurgicoProcedimentos(' . $value->codAtoCirurgicoProcedimento . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$x,
				$value->referencia,
				$value->descricao,
				$value->qtde,
				$value->observacao,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codAtoCirurgicoProcedimento');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->AtoCirurgicoProcedimentosModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codAtoCirurgico'] = $this->request->getPost('codAtoCirurgico');
		$fields['codProcedimento'] = $this->request->getPost('codProcedimento');
		$fields['qtde'] = brl2decimal($this->request->getPost('qtde'));
		$fields['observacao'] = $this->request->getPost('observacao');
		$fields['codTabelaRef'] = $this->request->getPost('codTabelaRef');
		$fields['dataInicio'] = $this->request->getPost('dataInicio');
		$fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');
		$fields['filme'] = brl2decimal($this->request->getPost('filme'));
		$fields['viaAcesso'] = $this->request->getPost('viaAcesso');
		$fields['codTecnica'] = $this->request->getPost('codTecnica');
		$fields['codAcomodacao'] = $this->request->getPost('codAcomodacao');


		if ($fields['classificacaoProcedimento'] == 1) {
			//SETA TODOS COMO SECUNDÁRIO
			$fieldsSecundario['classificacaoProcedimento'] = 0;
			$this->AtoCirurgicoProcedimentosModel->setaSecundario($fields['codAtoCirurgico']);
		} else {
			$fieldsSecundario['classificacaoProcedimento'] = 0;
		}




		$this->validation->setRules([
			'codAtoCirurgico' => ['label' => 'CodAtoCirurgico', 'rules' => 'required|numeric|max_length[11]'],
			'codProcedimento' => ['label' => 'CodProcedimento', 'rules' => 'required|numeric|max_length[11]'],
			'qtde' => ['label' => 'Qtde', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtoCirurgicoProcedimentosModel->insert($fields)) {

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

		$fields['codAtoCirurgicoProcedimento'] = $this->request->getPost('codAtoCirurgicoProcedimento');
		$fields['codAtoCirurgico'] = $this->request->getPost('codAtoCirurgico');
		$fields['codProcedimento'] = $this->request->getPost('codProcedimento');
		$fields['qtde'] = brl2decimal($this->request->getPost('qtde'));
		$fields['observacao'] = $this->request->getPost('observacao');
		$fields['codTabelaRef'] = $this->request->getPost('codTabelaRef');
		$fields['dataInicio'] = $this->request->getPost('dataInicio');
		$fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');
		$fields['filme'] = brl2decimal($this->request->getPost('filme'));
		$fields['viaAcesso'] = $this->request->getPost('viaAcesso');
		$fields['codTecnica'] = $this->request->getPost('codTecnica');
		$fields['codAcomodacao'] = $this->request->getPost('codAcomodacao');


		if ($fields['classificacaoProcedimento'] == 1) {
			//SETA TODOS COMO SECUNDÁRIO
			$fieldsSecundario['classificacaoProcedimento'] = 0;
			$this->AtoCirurgicoProcedimentosModel->setaSecundario($fields['codAtoCirurgico']);
		} else {
			$fieldsSecundario['classificacaoProcedimento'] = 0;
		}


		$this->validation->setRules([
			'codAtoCirurgicoProcedimento' => ['label' => 'CodAtoCirurgicoProcedimento', 'rules' => 'required|numeric|max_length[11]'],
			'codAtoCirurgico' => ['label' => 'CodAtoCirurgico', 'rules' => 'required|numeric|max_length[11]'],
			'codProcedimento' => ['label' => 'CodProcedimento', 'rules' => 'required|numeric|max_length[11]'],
			'qtde' => ['label' => 'Qtde', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtoCirurgicoProcedimentosModel->update($fields['codAtoCirurgicoProcedimento'], $fields)) {

				$response['success'] = true;
				$response['messages'] = 'Atualizado com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
			}
		}

		return $this->response->setJSON($response);
	}

	public function listaDropDownProcedimentos()
	{
		$codTabelaRef = $this->request->getPost('codTabelaRef');

		$result = $this->AtoCirurgicoProcedimentosModel->listaDropDownProcedimentos($codTabelaRef);

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}
	public function listaDropDownTecnica()
	{

		$result = $this->AtoCirurgicoProcedimentosModel->listaDropDownTecnica();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}
	public function listaDropDownAcomodacoes()
	{

		$result = $this->AtoCirurgicoProcedimentosModel->listaDropDownAcomodacoes();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}
	public function listaDropDownTabelaRef()
	{

		$dados = array();
		$result = $this->AtoCirurgicoProcedimentosModel->listaDropDownTabelaRef();

		$codPaciente  = $this->request->getPost('codPaciente');
		$pegaConvenio = $this->AtoCirurgicoProcedimentosModel->pegaConvenio($codPaciente);


		if (!empty($pegaConvenio)) {

			foreach ($pegaConvenio as $dadosConvenio) {
				if ($dadosConvenio->principal == 1) {
					$codTabelaRef = $dadosConvenio->codTabelaRef;
				} else {
					$codTabelaRef = 0;
				}
			}
		} else {
			$codTabelaRef = 0;
		}



		if ($result !== NULL) {

			$dados['codTabelaRef'] = $codTabelaRef;
			$dados['lista'] = $result;

			return $this->response->setJSON($dados);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function remove()
	{
		$response = array();

		$id = $this->request->getPost('codAtoCirurgicoProcedimento');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->AtoCirurgicoProcedimentosModel->where('codAtoCirurgicoProcedimento', $id)->delete()) {

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
