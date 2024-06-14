<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\PrescricaoProcedimentoModel;

class PrescricaoProcedimentos extends BaseController
{

	protected $PrescricaoProcedimentoModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->PrescricaoProcedimentoModel = new PrescricaoProcedimentoModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('PrescricaoProcedimentos', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "PrescricaoProcedimentos"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'PrescricaoProcedimentos',
			'title'     		=> 'Prescrição de Procedimentos'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('PrescricaoProcedimentos', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->PrescricaoProcedimentoModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editprescricaoProcedimento(' . $value->codPrescricaoProcedimento . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeprescricaoProcedimento(' . $value->codPrescricaoProcedimento . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$value->codPrescricaoProcedimento,
				$value->codAtendimentoPrescricao,
				$value->codProcedimento,
				$value->qtde,
				$value->codStatus,
				$value->observacao,
				$value->codAutor,
				$value->dataCriacao,
				$value->dataAtualizacao,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}
	public function getAllPorPrescricao()
	{
		$response = array();

		$data['data'] = array();
		$codAtendimentoPrescricao = $this->request->getPost('codAtendimentoPrescricao');
		$result = $this->PrescricaoProcedimentoModel->getAllPorPrescricao($codAtendimentoPrescricao);
		//$result = $this->PrescricaoProcedimentoModel->pegaTudo();
		foreach ($result as $key => $value) {


			$ops = '<div class="btn-group">';
			if ($value->codStatus <= 1) {
				$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editprescricaoProcedimento(' . $value->codPrescricaoProcedimento . ')"><i class="fa fa-edit"></i></button>';
				$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeprescricaoProcedimento(' . $value->codPrescricaoProcedimento . ')"><i class="fa fa-trash"></i></button>';
			}
			$ops .= '</div>';

			$descricaoStatusProcedimento = '<span class="right badge badge-' . $value->corStatusProcedimento . '">' . $value->descricaoStatusProcedimento . '</span>';




			$data['data'][$key] = array(
				$value->codPrescricaoProcedimento,
				$value->referencia,
				$value->descricaoProcedimento,
				$value->qtde,
				$value->observacao,
				$value->nomeExibicao,
				$value->dataCriacao,
				$descricaoStatusProcedimento,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codPrescricaoProcedimento');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->PrescricaoProcedimentoModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codPrescricaoProcedimento'] = $this->request->getPost('codPrescricaoProcedimento');
		$fields['codAtendimentoPrescricao'] = $this->request->getPost('codAtendimentoPrescricao');
		$fields['codProcedimento'] = $this->request->getPost('codProcedimento');
		$fields['qtde'] = brl2decimal($this->request->getPost('qtde'));
		$fields['codStatus'] = 1;
		$fields['observacao'] = $this->request->getPost('observacao');
		$fields['codAutor'] = session()->codPessoa;
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');


		$this->validation->setRules([
			'codAtendimentoPrescricao' => ['label' => 'CodAtendimentoPrescricao', 'rules' => 'required|numeric|max_length[11]'],
			'codProcedimento' => ['label' => 'CodProcedimento', 'rules' => 'required|numeric|max_length[11]'],
			'qtde' => ['label' => 'Qtde', 'rules' => 'required'],
			'codStatus' => ['label' => 'CodStatus', 'rules' => 'required|numeric|max_length[11]'],
			'observacao' => ['label' => 'Observacao', 'rules' => 'permit_empty'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->PrescricaoProcedimentoModel->insert($fields)) {

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

		$fields['codPrescricaoProcedimento'] = $this->request->getPost('codPrescricaoProcedimento');
		$fields['codAtendimentoPrescricao'] = $this->request->getPost('codAtendimentoPrescricao');
		$fields['codProcedimento'] = $this->request->getPost('codProcedimento');
		$fields['qtde'] = brl2decimal($this->request->getPost('qtde'));
		$fields['codStatus'] = 1;
		$fields['observacao'] = $this->request->getPost('observacao');
		$fields['codAutor'] = session()->codPessoa;
		$fields['dataCriacao'] = date('Y-m-d H:i');


		$this->validation->setRules([
			'codPrescricaoProcedimento' => ['label' => 'codPrescricaoProcedimento', 'rules' => 'required|numeric|max_length[11]'],
			'codAtendimentoPrescricao' => ['label' => 'CodAtendimentoPrescricao', 'rules' => 'required|numeric|max_length[11]'],
			'codProcedimento' => ['label' => 'CodProcedimento', 'rules' => 'required|numeric|max_length[11]'],
			'qtde' => ['label' => 'Qtde', 'rules' => 'required'],
			'codStatus' => ['label' => 'CodStatus', 'rules' => 'required|numeric|max_length[11]'],
			'observacao' => ['label' => 'Observacao', 'rules' => 'permit_empty'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->PrescricaoProcedimentoModel->update($fields['codPrescricaoProcedimento'], $fields)) {

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

		$result = $this->PrescricaoProcedimentoModel->listaDropDownProcedimentos();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function listaDropDownTabelaRef()
	{

		$dados = array();
		$result = $this->PrescricaoProcedimentoModel->listaDropDownTabelaRef();

		$codPaciente  = $this->request->getPost('codPaciente');
		$pegaConvenio = $this->PrescricaoProcedimentoModel->pegaConvenio($codPaciente);

		if ($pegaConvenio !== NULL) {
		}



		if ($result !== NULL) {

			$dados[''] = 1;
			$dados['lista'] = $result;

			return $this->response->setJSON($dados);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function remove()
	{
		$response = array();

		$id = $this->request->getPost('codPrescricaoProcedimento');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->PrescricaoProcedimentoModel->where('codPrescricaoProcedimento', $id)->delete()) {

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
