<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\DepartamentodependenciasModel;

class Departamentodependencias extends BaseController
{

	protected $DepartamentodependenciasModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->DepartamentodependenciasModel = new DepartamentodependenciasModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('Departamentodependencias', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "Departamentodependencias"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'departamentodependencias',
			'title'     		=> 'Dependências dos Departamentos'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('departamentodependencias', $data);
	}



	public function listaDropDown($codDepartamento = null)
	{

		$codDepartamento = $this->request->getPost('codDepartamento');
		$result = $this->DepartamentodependenciasModel->listaDropDown($codDepartamento);

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function dependencia()
	{
		$response = array();

		$data['data'] = array();

		$codDepartamento = $this->request->getPost('codDepartamento');


		$result = $this->DepartamentodependenciasModel->pegaPorDepartamento($codDepartamento);
		$x = 0;
		foreach ($result as $key => $value) {
			$x++;
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editdepartamentodependencias(' . $value->codDependencia . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removedepartamentodependencias(' . $value->codDependencia . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$x,
				$value->descricaoDependencia,
				$value->descricaoTipoDependecia,
				$value->descricaoStatusDependecia,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}
	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->DepartamentodependenciasModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editdepartamentodependencias(' . $value->codDependencia . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removedepartamentodependencias(' . $value->codDependencia . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$value->codDependencia,
				$value->descricaoDependencia,
				$value->codTipoDependecia,
				$value->codStatusDependecia,
				$value->codPessoa,
				$value->dataAtualizacao,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}





	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codDependencia');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->DepartamentodependenciasModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codDependencia'] = $this->request->getPost('codDependencia');
		$fields['codDepartamento'] = $this->request->getPost('codDepartamento');
		$fields['codOrganizacao'] = session()->codOrganizacao;
		$fields['descricaoDependencia'] = $this->request->getPost('descricaoDependencia');
		$fields['codTipoDependecia'] = $this->request->getPost('codTipoDependecia');
		$fields['codStatusDependecia'] = $this->request->getPost('codStatusDependecia');
		$fields['codPessoa'] = session()->codPessoa;
		$fields['dataAtualizacao'] = date('Y-m-d');


		$this->validation->setRules([
			'descricaoDependencia' => ['label' => 'DescricaoDependencia', 'rules' => 'required|max_length[100]'],
			'codTipoDependecia' => ['label' => 'CodTipoDependecia', 'rules' => 'required|max_length[11]'],
			'codStatusDependecia' => ['label' => 'CodStatusDependecia', 'rules' => 'required|max_length[11]'],
			'codPessoa' => ['label' => 'CodPessoa', 'rules' => 'required|numeric|max_length[11]'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->DepartamentodependenciasModel->insert($fields)) {

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

		$fields['codDependencia'] = $this->request->getPost('codDependencia');
		$fields['descricaoDependencia'] = $this->request->getPost('descricaoDependencia');
		$fields['codTipoDependecia'] = $this->request->getPost('codTipoDependecia');
		$fields['codStatusDependecia'] = $this->request->getPost('codStatusDependecia');
		$fields['codPessoa'] = session()->codPessoa;
		$fields['dataAtualizacao'] = date('Y-m-d H:i');


		$this->validation->setRules([
			'descricaoDependencia' => ['label' => 'DescricaoDependencia', 'rules' => 'required|max_length[100]'],
			'codTipoDependecia' => ['label' => 'CodTipoDependecia', 'rules' => 'required|max_length[11]'],
			'codStatusDependecia' => ['label' => 'CodStatusDependecia', 'rules' => 'required|max_length[11]'],
			'codPessoa' => ['label' => 'CodPessoa', 'rules' => 'required|numeric|max_length[11]'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->DepartamentodependenciasModel->update($fields['codDependencia'], $fields)) {

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

		$id = $this->request->getPost('codDependencia');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->DepartamentodependenciasModel->where('codDependencia', $id)->delete()) {

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
