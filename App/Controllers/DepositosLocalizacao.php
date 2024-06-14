<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\DepositosLocalizacaoModel;

class DepositosLocalizacao extends BaseController
{

	protected $DepositosLocalizacaoModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->DepositosLocalizacaoModel = new DepositosLocalizacaoModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('DepositosLocalizacao', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "DepositosLocalizacao"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'DepositosLocalizacao',
			'title'     		=> 'Endereços do Deposito'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('DepositosLocalizacao', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->DepositosLocalizacaoModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editDepositosLocalizacao(' . $value->codLocalizacao . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeDepositosLocalizacao(' . $value->codLocalizacao . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codLocalizacao,
				$value->descricaoLocalizacao,
				$value->codStatus,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}


	public function listaDropDown()
	{

		$result = $this->DepositosLocalizacaoModel->listaDropDown();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function listaDropDownPorDeposito()
	{
		$codDeposito = $this->request->getPost('codDeposito');
		$result = $this->DepositosLocalizacaoModel->listaDropDownPorDeposito($codDeposito);

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}



	public function getAllDeposito()
	{
		$response = array();

		$data['data'] = array();
		$codDeposito = $this->request->getPost('codDeposito');

		$result = $this->DepositosLocalizacaoModel->pegaTudoDeposito($codDeposito);
		$x = 0;
		foreach ($result as $key => $value) {
			$x++;
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editDepositosLocalizacao(' . $value->codLocalizacao . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeDepositosLocalizacao(' . $value->codLocalizacao . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			if ($value->codStatus == 1) {
				$status = 'Ativo';
			} else {
				$status = 'Desativado';
			}



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$x,
				$value->descricaoLocalizacao,
				$status,
				date('d/m/Y H:i', strtotime($value->dataAtualizacao)),
				$value->nomeExibicao,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codLocalizacao');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->DepositosLocalizacaoModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codLocalizacao'] = $this->request->getPost('codLocalizacao');
		$fields['descricaoLocalizacao'] = mb_strtoupper($this->request->getPost('descricaoLocalizacao'), "utf-8");
		$fields['codDeposito'] = $this->request->getPost('codDeposito');
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['codAutor'] = session()->codPessoa;
		$fields['codOrganizacao'] = session()->codOrganizacao;
		$fields['codStatus'] = 1;


		$this->validation->setRules([
			'descricaoLocalizacao' => ['label' => 'Descrição', 'rules' => 'required|max_length[200]'],
			'codStatus' => ['label' => 'status', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->DepositosLocalizacaoModel->insert($fields)) {

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

		$fields['codLocalizacao'] = $this->request->getPost('codLocalizacao');
		$fields['descricaoLocalizacao'] = mb_strtoupper($this->request->getPost('descricaoLocalizacao'), "utf-8");
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['codAutor'] = session()->codPessoa;
		$fields['codOrganizacao'] = session()->codOrganizacao;
		if ($this->request->getPost('codStatus') == 'on') {
			$fields['codStatus'] = 1;
		} else {
			$fields['codStatus'] = 0;
		}



		$this->validation->setRules([
			'codLocalizacao' => ['label' => 'codLocalizacao', 'rules' => 'required|numeric|max_length[11]'],
			'descricaoLocalizacao' => ['label' => 'Descrição', 'rules' => 'required|max_length[200]'],
			'codStatus' => ['label' => 'status', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->DepositosLocalizacaoModel->update($fields['codLocalizacao'], $fields)) {

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

		$id = $this->request->getPost('codLocalizacao');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->DepositosLocalizacaoModel->where('codLocalizacao', $id)->delete()) {

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
