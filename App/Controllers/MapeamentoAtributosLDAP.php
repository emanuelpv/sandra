<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\MapeamentoAtributosLDAPModel;

class MapeamentoAtributosLDAP extends BaseController
{

	protected $mapeamentoAtributosLDAPModel;
	protected $pessoasModel;
	protected $organizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->MapeamentoAtributosLDAPModel = new MapeamentoAtributosLDAPModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);


		$permissao = verificaPermissao('MapeamentoAtributosLDAP', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo MapeamentoAtributosLDAP', session()->codPessoa);
			exit();
		}
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'mapeamentoAtributosLDAP',
			'title'     		=> 'Mapeamento de Atributos LDAP'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('mapeamentoAtributosLDAP', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->MapeamentoAtributosLDAPModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Editar" onclick="editmapeamentoAtributosLDAP(' . $value->codMapAttrLDAP . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="removemapeamentoAtributosLDAP(' . $value->codMapAttrLDAP . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codMapAttrLDAP,
				$value->codServidorLDAP,
				$value->nomeAtributoSistema,
				$value->nomeAtributoLDAP,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function pegaServidorLDAP($codServidorLDAP)
	{
		$response = array();

		$data['data'] = array();

		$result = $this->MapeamentoAtributosLDAPModel->pegaTudoPorServidor($codServidorLDAP);

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Editar" onclick="editmapeamentoAtributosLDAP(' . $value->codMapAttrLDAP . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="removemapeamentoAtributosLDAP(' . $value->codMapAttrLDAP . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codMapAttrLDAP,
				$value->descricaoServidorLDAP,
				$value->nomeAtributoSistema,
				$value->nomeAtributoLDAP,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}



	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codMapAttrLDAP');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->MapeamentoAtributosLDAPModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codMapAttrLDAP'] = $this->request->getPost('codMapAttrLDAP');
		$fields['codServidorLDAP'] = $this->request->getPost('codServidorLDAP');
		$fields['nomeAtributoSistema'] = $this->request->getPost('nomeAtributoSistema');
		$fields['nomeAtributoLDAP'] = $this->request->getPost('nomeAtributoLDAP');
		$fields['codOrganizacao'] = session()->codOrganizacao;


		$this->validation->setRules([
			'codServidorLDAP' => ['label' => 'Servidor LDAP', 'rules' => 'required|numeric|max_length[11]'],
			'nomeAtributoSistema' => ['label' => 'Atributo Sistema', 'rules' => 'required|max_length[60]'],
			'nomeAtributoLDAP' => ['label' => 'Atributo LDAP', 'rules' => 'required|max_length[60]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->MapeamentoAtributosLDAPModel->insert($fields)) {

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

		$fields['codMapAttrLDAP'] = $this->request->getPost('codMapAttrLDAP');
		$fields['codServidorLDAP'] = $this->request->getPost('codServidorLDAP');
		$fields['nomeAtributoSistema'] = $this->request->getPost('nomeAtributoSistema');
		$fields['nomeAtributoLDAP'] = $this->request->getPost('nomeAtributoLDAP');


		$this->validation->setRules([
			'codMapAttrLDAP' => ['label' => 'codMapAttrLDAP', 'rules' => 'required|numeric|max_length[11]'],
			'codServidorLDAP' => ['label' => 'Servidor LDAP', 'rules' => 'required|numeric|max_length[11]'],
			'nomeAtributoSistema' => ['label' => 'Atributo Sistema', 'rules' => 'required|max_length[60]'],
			'nomeAtributoLDAP' => ['label' => 'Atributo LDAP', 'rules' => 'required|max_length[60]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->MapeamentoAtributosLDAPModel->update($fields['codMapAttrLDAP'], $fields)) {

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

		$id = $this->request->getPost('codMapAttrLDAP');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->MapeamentoAtributosLDAPModel->where('codMapAttrLDAP', $id)->delete()) {

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
