<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\AtendimentoslocaisModel;

class Atendimentoslocais extends BaseController
{

	protected $AtendimentoslocaisModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->AtendimentoslocaisModel = new AtendimentoslocaisModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('Atendimentoslocais', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "Atendimentoslocais"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'atendimentoslocais',
			'title'     		=> 'Dependências dos Departamentos'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('atendimentoslocais', $data);
	}

	public function listaDropDownAtivos($codDepartamento = null)
	{

		$codDepartamento = $this->request->getPost('codDepartamento');
		$result = $this->AtendimentoslocaisModel->listaDropDownAtivos($codDepartamento);

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function listaDropDownSalasGuichesAtivos($codDepartamento = null)
	{

		$codDepartamento = $this->request->getPost('codDepartamento');
		$result = $this->AtendimentoslocaisModel->listaDropDownSalasGuichesAtivos($codDepartamento);

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}
	
	public function listaDropDownLeitosLocaisProcedimentosAtivos($codDepartamento = null)
	{

		$codDepartamento = $this->request->getPost('codDepartamento');
		$result = $this->AtendimentoslocaisModel->listaDropDownLeitosLocaisProcedimentosAtivos($codDepartamento);

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function listaDropDown($codDepartamento = null)
	{

		$codDepartamento = $this->request->getPost('codDepartamento');
		$result = $this->AtendimentoslocaisModel->listaDropDown($codDepartamento);

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function locaisAtendimento()
	{
		$response = array();

		$data['data'] = array();

		$codDepartamento = $this->request->getPost('codDepartamento');
		$csrf_sandra = $this->request->getPost('csrf_sandra');


		$result = $this->AtendimentoslocaisModel->pegaPorDepartamento($codDepartamento);
		$x = 0;
		foreach ($result as $key => $value) {
			$x++;
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editatendimentoslocais(' . $value->codLocalAtendimento . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeatendimentoslocais(' . $value->codLocalAtendimento . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_hash'] =  $csrf_sandra;
			$data['data'][$key] = array(
				$value->codLocalAtendimento,
				$value->descricaoLocalAtendimento,
				$value->descricaoTipoLocalAtendimento,
				$value->descricaoStatusLocalAtendimento,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}
	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->AtendimentoslocaisModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editatendimentoslocais(' . $value->codLocalAtendimento . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeatendimentoslocais(' . $value->codLocalAtendimento . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codLocalAtendimento,
				$value->descricaoLocalAtendimento,
				$value->codTipoLocalAtendimento,
				$value->codStatusLocalAtendimento,
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

		$id = $this->request->getPost('codLocalAtendimento');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->AtendimentoslocaisModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codLocalAtendimento'] = $this->request->getPost('codLocalAtendimento');
		$fields['codDepartamento'] = $this->request->getPost('codDepartamento');
		$fields['codOrganizacao'] = session()->codOrganizacao;
		$fields['descricaoLocalAtendimento'] = $this->request->getPost('descricaoLocalAtendimento');
		$fields['codTipoLocalAtendimento'] = $this->request->getPost('codTipoLocalAtendimento');
		$fields['codStatusLocalAtendimento'] = $this->request->getPost('codStatusLocalAtendimento');
		$fields['codSituacaoLocalAtendimento'] = $this->request->getPost('codSituacaoLocalAtendimento');
		$fields['observacoes'] = $this->request->getPost('observacoes');
		$fields['codPessoa'] = session()->codPessoa;
		$fields['dataAtualizacao'] = date('Y-m-d');


		$this->validation->setRules([
			'descricaoLocalAtendimento' => ['label' => 'DescricaoLocalAtendimento', 'rules' => 'required|max_length[100]'],
			'codTipoLocalAtendimento' => ['label' => 'CodTipoDependecia', 'rules' => 'required|max_length[11]'],
			'codStatusLocalAtendimento' => ['label' => 'CodStatusDependecia', 'rules' => 'required|max_length[11]'],
			'codPessoa' => ['label' => 'CodPessoa', 'rules' => 'required|numeric|max_length[11]'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtendimentoslocaisModel->insert($fields)) {

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

		$fields['codLocalAtendimento'] = $this->request->getPost('codLocalAtendimento');
		$fields['descricaoLocalAtendimento'] = $this->request->getPost('descricaoLocalAtendimento');
		$fields['codTipoLocalAtendimento'] = $this->request->getPost('codTipoLocalAtendimento');
		$fields['codStatusLocalAtendimento'] = $this->request->getPost('codStatusLocalAtendimento');
		$fields['codSituacaoLocalAtendimento'] = $this->request->getPost('codSituacaoLocalAtendimento');
		$fields['observacoes'] = $this->request->getPost('observacoes');
		$fields['codPessoa'] = session()->codPessoa;
		$fields['dataAtualizacao'] = date('Y-m-d H:i');


		$this->validation->setRules([
			'codLocalAtendimento' => ['label' => 'codLocalAtendimento', 'rules' => 'required|numeric'],
			'descricaoLocalAtendimento' => ['label' => 'DescricaoLocalAtendimento', 'rules' => 'required|max_length[100]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtendimentoslocaisModel->update($fields['codLocalAtendimento'], $fields)) {

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

		$id = $this->request->getPost('codLocalAtendimento');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->AtendimentoslocaisModel->where('codLocalAtendimento', $id)->delete()) {

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
