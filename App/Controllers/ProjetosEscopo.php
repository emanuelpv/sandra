<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\ProjetosEscopoModel;

class ProjetosEscopo extends BaseController
{

	protected $projetosEscopoModel;
	protected $pessoasModel;
	protected $organizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->projetosEscopoModel = new ProjetosEscopoModel();
		$this->organizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->organizacao =  $this->organizacoesModel->pegaOrganizacao($this->codOrganizacao);

		$permissao = verificaPermissao('ProjetosEscopo', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo ProjetosEscopo', session()->codPessoa);
			exit();
		}
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'projetosEscopo',
			'title'     		=> 'Escopo do Projeto'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('projetosEscopo', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->projetosEscopoModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editprojetosEscopo(' . $value->codProjetoEscopo . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeprojetosEscopo(' . $value->codProjetoEscopo . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codProjetoEscopo,
				$value->codProjeto,
				$value->descricaoEscopo,
				$value->codTipoEscopo,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codProjetoEscopo');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->projetosEscopoModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codProjetoEscopo'] = $this->request->getPost('codProjetoEscopo');
		$fields['codProjeto'] = $this->request->getPost('codProjeto');
		$fields['descricaoEscopo'] = $this->request->getPost('descricaoEscopo');
		$fields['codTipoEscopo'] = $this->request->getPost('codTipoEscopo');


		$this->validation->setRules([
			'codProjeto' => ['label' => 'CodProjeto', 'rules' => 'required|numeric|max_length[11]'],
			'descricaoEscopo' => ['label' => 'Descrição do Escopo', 'rules' => 'required|max_length[150]'],
			'codTipoEscopo' => ['label' => 'Tipo', 'rules' => 'required|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->projetosEscopoModel->insert($fields)) {

				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['codTipoEscopo'] = $fields['codTipoEscopo'];
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

		$fields['codProjetoEscopo'] = $this->request->getPost('codProjetoEscopo');
		$fields['codProjeto'] = $this->request->getPost('codProjeto');
		$fields['descricaoEscopo'] = $this->request->getPost('descricaoEscopo');
		$fields['codTipoEscopo'] = $this->request->getPost('codTipoEscopo');


		$this->validation->setRules([
			'codProjeto' => ['label' => 'CodProjeto', 'rules' => 'required|numeric|max_length[11]'],
			'descricaoEscopo' => ['label' => 'Descrição do Escopo', 'rules' => 'required|max_length[150]|bloquearReservado'],
			'codTipoEscopo' => ['label' => 'Tipo', 'rules' => 'required|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->projetosEscopoModel->update($fields['codProjetoEscopo'], $fields)) {

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

		$id = $this->request->getPost('codProjetoEscopo');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->projetosEscopoModel->where('codProjetoEscopo', $id)->delete()) {

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
