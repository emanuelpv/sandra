<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\AtributosTipoLDAPModel;

class AtributosTipoLDAP extends BaseController
{

	protected $AtributosTipoLDAPModel;
	protected $pessoasModel;
	protected $organizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->AtributosTipoLDAPModel = new AtributosTipoLDAPModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);


		$permissao = verificaPermissao('AtributosTipoLDAP', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo AtributosTipoLDAP', session()->codPessoa);
			exit();
		}
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'atributosTipoLDAP',
			'title'     		=> 'Atributos LDAP'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('atributosTipoLDAP', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->AtributosTipoLDAPModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Editar" onclick="editatributosTipoLDAP(' . $value->codAtributoTipoLDAP . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="removeatributosTipoLDAP(' . $value->codAtributoTipoLDAP . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codAtributoTipoLDAP,
				$value->nomeTipoLDAP,
				$value->nomeAtributoLDAP,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codAtributoTipoLDAP');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->AtributosTipoLDAPModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function pegaAtributosLDAP($id)
	{
		$response = array();


		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->AtributosTipoLDAPModel->pegaPorTipoLDAP($id);

			echo json_encode($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function add()
	{

		$response = array();

		$fields['codAtributoTipoLDAP'] = $this->request->getPost('codAtributoTipoLDAP');
		$fields['codTipoLDAP'] = $this->request->getPost('codTipoLDAP');
		$fields['nomeAtributoLDAP'] = $this->request->getPost('nomeAtributoLDAP');
		$fields['dataCriacao'] = date('Y-m-d H:i');



		$this->validation->setRules([
			'codTipoLDAP' => ['label' => 'Tipo LDAP', 'rules' => 'required|numeric|max_length[11]'],
			'nomeAtributoLDAP' => ['label' => 'Atributo', 'rules' => 'required|max_length[60]'],
			'dataCriacao' => ['label' => 'Data Criacao', 'rules' => 'permit_empty'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtributosTipoLDAPModel->insert($fields)) {

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

		$fields['codAtributoTipoLDAP'] = $this->request->getPost('codAtributoTipoLDAP');
		$fields['codTipoLDAP'] = $this->request->getPost('codTipoLDAP');
		$fields['nomeAtributoLDAP'] = $this->request->getPost('nomeAtributoLDAP');

		$this->validation->setRules([
			'codAtributoTipoLDAP' => ['label' => 'codAtributoTipoLDAP', 'rules' => 'required|numeric|max_length[11]'],
			'codTipoLDAP' => ['label' => 'Tipo LDAP', 'rules' => 'required|numeric|max_length[11]'],
			'nomeAtributoLDAP' => ['label' => 'Atributo', 'rules' => 'required|max_length[60]'],
			'dataCriacao' => ['label' => 'Data Criacao', 'rules' => 'permit_empty'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtributosTipoLDAPModel->update($fields['codAtributoTipoLDAP'], $fields)) {

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

		$id = $this->request->getPost('codAtributoTipoLDAP');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->AtributosTipoLDAPModel->where('codAtributoTipoLDAP', $id)->delete()) {

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
