<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;
use App\Models\DepartamentosModel;
use App\Models\ParteRequisitoriaModel;

class ParteRequisitoria extends BaseController
{

	protected $ParteRequisitoriaModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->ParteRequisitoriaModel = new ParteRequisitoriaModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->DepartamentosModel = new DepartamentosModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('ParteRequisitoria', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "ParteRequisitoria"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'parterequisitoria',
			'title'     		=> 'Parte Requisitória'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('parterequisitoria', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->ParteRequisitoriaModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editparterequisitoria(' . $value->codRequisicao . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeparterequisitoria(' . $value->codRequisicao . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			if ($value->matSau == 1) {
				$matSau = 'Sim';
			} else {
				$matSau = 'Não';
			}
			if ($value->carDisp == 1) {
				$carDisp = 'Sim';
			} else {
				$carDisp = 'Não';
			}

			$data['data'][$key] = array(
				$value->numeroRequisicao,
				$value->descricao,
				$value->abreviacaoDepartamento,
				$value->descricaoClasseRequisicao,
				$value->descricaoTipoRequisicao,
				$value->dataRequisicao,
				$value->valorTotal,
				$matSau,
				$carDisp,
				$value->descricaoStatusRequisicao,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codRequisicao');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->ParteRequisitoriaModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function listaDropDownTipoParteRequisitoria()
	{

		$result = $this->ParteRequisitoriaModel->listaDropDownTipoParteRequisitoria();

		if ($result !== NULL) {

			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}
	public function listaDropDownClasseParteRequisitoria()
	{

		$result = $this->ParteRequisitoriaModel->listaDropDownClasseParteRequisitoria();

		if ($result !== NULL) {

			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}



	public function add()
	{

		$response = array();

		//VERIFICA NUMERAÇÃO 

		$codDepartamento = $this->request->getPost('codDepartamento');

		if ($this->validation->check($codDepartamento, 'required|numeric')) {

			$dadosDepartamento = $this->DepartamentosModel->pegaDepartamento($codDepartamento);


			//VERIFICA O ÚLTIMO LANÇAMENTO DESTE ANO
			$ultimoLancamentoAnoCorrente = $this->ParteRequisitoriaModel->ultimoLancamentoAnoCorrente($codDepartamento);

			$numeroRequisicao = 1;
			$anoRequisicao = date('Y');
			if ($ultimoLancamentoAnoCorrente == NULL or $dadosDepartamento->SeqAno < date('Y')) {
				$atualizaDepartamento['SeqAno'] = date('Y');
				$atualizaDepartamento['seqRequisicao'] = 1;
				$this->DepartamentosModel->update($codDepartamento, $atualizaDepartamento);
			} else {
				if ($ultimoLancamentoAnoCorrente->ano == NULL or $ultimoLancamentoAnoCorrente->ano == '' or $ultimoLancamentoAnoCorrente->ano == ' ') {
					$anoRequisicao =  date('Y');
				} else {
					$anoRequisicao = $ultimoLancamentoAnoCorrente->ano;
				}

				$atualizaDepartamento['seqRequisicao'] = $ultimoLancamentoAnoCorrente->numeroRequisicao+1;
				$this->DepartamentosModel->update($codDepartamento, $atualizaDepartamento);

				$numeroRequisicao = $atualizaDepartamento['seqRequisicao'];
			}


			$fields['codRequisicao'] = $this->request->getPost('codRequisicao');
			$fields['numeroRequisicao'] = $numeroRequisicao;
			$fields['ano'] = $anoRequisicao;
			$fields['codRequisicao'] = $this->request->getPost('codRequisicao');
			$fields['dataRequisicao'] = date('Y-m-d H:i');
			$fields['dataAtualizacao'] = date('Y-m-d H:i');
			$fields['descricao'] = $this->request->getPost('descricao');
			$fields['codTipoRequisicao'] = $this->request->getPost('codTipoRequisicao');
			$fields['codDepartamento'] = $codDepartamento;
			$fields['codClasseRequisicao'] = $this->request->getPost('codClasseRequisicao');
			$fields['valorTotal'] = $this->request->getPost('valorTotal');
			$fields['matSau'] = $this->request->getPost('matSau');
			$fields['carDisp'] = $this->request->getPost('carDisp');


			$this->validation->setRules([
				'descricao' => ['label' => 'Descricao', 'rules' => 'required'],
				'codTipoRequisicao' => ['label' => 'CodTipoRequisicao', 'rules' => 'required|max_length[11]'],
				'codClasseRequisicao' => ['label' => 'codClasseRequisicao', 'rules' => 'required|max_length[11]'],
				'dataRequisicao' => ['label' => 'DataRequisicao', 'rules' => 'required|valid_date'],
				'valorTotal' => ['label' => 'ValorTotal', 'rules' => 'permit_empty'],
				'matSau' => ['label' => 'MatSau', 'rules' => 'required|max_length[3]'],
				'carDisp' => ['label' => 'CarDisp', 'rules' => 'required|max_length[3]'],

			]);

			if ($this->validation->run($fields) == FALSE) {

				$response['success'] = false;
				$response['messages'] = $this->validation->listErrors();
			} else {

				if ($this->ParteRequisitoriaModel->insert($fields)) {

					$response['success'] = true;
					$response['messages'] = 'Informação inserida com sucesso';
				} else {

					$response['success'] = false;
					$response['messages'] = 'Erro na inserção!';
				}
			}
		}
		return $this->response->setJSON($response);
	}

	public function edit()
	{

		$response = array();
		$fields['codRequisicao'] =$this->request->getPost('codRequisicao');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['descricao'] = $this->request->getPost('descricao');
		$fields['codTipoRequisicao'] = $this->request->getPost('codTipoRequisicao');
		$fields['codClasseRequisicao'] = $this->request->getPost('codClasseRequisicao');
		$fields['dataRequisicao'] = $this->request->getPost('dataRequisicao');
		$fields['valorTotal'] = $this->request->getPost('valorTotal');
		$fields['matSau'] = $this->request->getPost('matSau');
		$fields['carDisp'] = $this->request->getPost('carDisp');


		$this->validation->setRules([
			'descricao' => ['label' => 'Descricao', 'rules' => 'required'],
			'codTipoRequisicao' => ['label' => 'CodTipoRequisicao', 'rules' => 'required|max_length[11]'],
			'codClasseRequisicao' => ['label' => 'codClasseRequisicao', 'rules' => 'required|max_length[11]'],
			'dataRequisicao' => ['label' => 'DataRequisicao', 'rules' => 'required|valid_date'],
			'valorTotal' => ['label' => 'ValorTotal', 'rules' => 'permit_empty'],
			'matSau' => ['label' => 'MatSau', 'rules' => 'required|max_length[3]'],
			'carDisp' => ['label' => 'CarDisp', 'rules' => 'required|max_length[3]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->ParteRequisitoriaModel->update($fields['codRequisicao'], $fields)) {

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

		$id = $this->request->getPost('codRequisicao');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->ParteRequisitoriaModel->where('codRequisicao', $id)->delete()) {

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
