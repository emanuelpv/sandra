<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\KitsModel;
use App\Models\KitsItensModel;

class Kits extends BaseController
{

	protected $KitsModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->KitsModel = new KitsModel();
		$this->KitsItensModel = new KitsItensModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('Kits', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "Kits"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'kits',
			'title'     		=> 'Kits'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('kits', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->KitsModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editkits(' . $value->codKit . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="desativarKit(' . $value->codKit . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			if ($value->codStatus == 1) {
				$status = 'Ativo';
			} else {

				$status = 'Desativo';
			}
			$data['data'][$key] = array(
				$value->codKit,
				$value->descricaoKit,
				$value->descricaoAlternativa,
				$value->valorun,
				$value->DescricaoTipo,
				$value->disponivel,
				$status,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codKit');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->KitsModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function listaDropDownTipos()
	{

		$result = $this->KitsModel->listaDropDownTipos();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}
	public function listaDropDownKits()
	{

		$result = $this->KitsModel->listaDropDownKits();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}
	public function listaDropDownItensFarmacia()
	{

		$result = $this->KitsModel->listaDropDownItensFarmacia();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codKit'] = $this->request->getPost('codKit');
		$fields['descricaoKit'] = $this->request->getPost('descricaoKit');
		$fields['descricaoAlternativa'] = $this->request->getPost('descricaoAlternativa');
		$fields['codTipo'] = $this->request->getPost('codTipo');
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['codAutor'] = session()->codPessoa;


		$this->validation->setRules([
			'descricaoKit' => ['label' => 'DescricaoKit', 'rules' => 'required|max_length[100]'],
			'descricaoAlternativa' => ['label' => 'DescricaoAlternativa', 'rules' => 'permit_empty'],
			'codTipo' => ['label' => 'Tipo', 'rules' => 'required|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->KitsModel->insert($fields)) {

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

		$fields['codKit'] = $this->request->getPost('codKit');
		$fields['descricaoKit'] = $this->request->getPost('descricaoKit');
		$fields['descricaoAlternativa'] = $this->request->getPost('descricaoAlternativa');
		$fields['codTipo'] = $this->request->getPost('codTipo');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['codAutor'] = session()->codPessoa;


		$this->validation->setRules([
			'codKit' => ['label' => 'codKit', 'rules' => 'required|numeric|max_length[11]'],
			'descricaoKit' => ['label' => 'DescricaoKit', 'rules' => 'required|max_length[100]'],
			'disponivel' => ['label' => 'Disponivel', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'descricaoAlternativa' => ['label' => 'DescricaoAlternativa', 'rules' => 'permit_empty'],
			'valorun' => ['label' => 'Valorun', 'rules' => 'permit_empty'],
			'codTipo' => ['label' => 'Tipo', 'rules' => 'required|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {


			//Atualiza valor do KIT
			$result = $this->KitsItensModel->itensKit($fields['codKit']);
			$totalKit = 0;
			foreach ($result as $itemKit) {
				$totalKit = $totalKit + $itemKit->valor;
			}

			$atualizaKit['valorun'] = $totalKit;

			if ($fields['codKit'] !== NULL and $fields['codKit'] !== "" and $fields['codKit'] !== " ") {
				if ($this->KitsModel->update($fields['codKit'], $atualizaKit)) {
				}
			}


			if ($this->KitsModel->update($fields['codKit'], $fields)) {

				$response['success'] = true;
				$response['messages'] = 'Atualizado com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
			}
		}

		return $this->response->setJSON($response);
	}

	public function desativarKit()
	{

		//NÃO REMOVE, APENAS DESATIVA
		$response = array();

		$codKit = $this->request->getPost('codKit');

		if (!$this->validation->check($codKit, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			$fields['codStatus'] = 0;

			if ($this->KitsModel->update($codKit, $fields)) {
				$response['success'] = true;
				$response['messages'] = 'Desativado com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na desativação!';
			}
		}

		return $this->response->setJSON($response);
	}
}
