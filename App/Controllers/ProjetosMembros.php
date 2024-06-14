<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\ProjetosMembrosModel;
use App\Models\ProjetosModel;

class ProjetosMembros extends BaseController
{

	protected $projetosMembrosModel;
	protected $pessoasModel;
	protected $organizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->ProjetosMembrosModel = new ProjetosMembrosModel();
		$this->ProjetosModel = new ProjetosModel();
		$this->organizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->organizacao =  $this->organizacoesModel->pegaOrganizacao($this->codOrganizacao);

		$permissao = verificaPermissao('ProjetosMembros', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo ProjetosMembros', session()->codPessoa);
			exit();
		}
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'projetosMembros',
			'title'     		=> 'Membros do Projeto'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('projetosMembros', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->ProjetosMembrosModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editprojetosMembros(' . $value->codProjetoMembro . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeprojetosMembros(' . $value->codProjetoMembro . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codProjetoMembro,
				$value->codMembro,
				$value->codTipoMembro,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}



	public function membrosProjeto()
	{
		$response = array();

		$data['data'] = array();

		$codProjeto = $this->request->getPost('codProjeto');

		$result = $this->ProjetosMembrosModel->pegaTudoPorCodProjeto($codProjeto);

		$x = 0;
		foreach ($result as $key => $value) {
			$x++;
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeprojetosMembros(' . $value->codProjetoMembro . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$x,
				$value->nomeExibicao,
				$value->descricaoTipoMembro,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}



	public function listaDropDownTipoMembros()
	{

		$result = $this->ProjetosMembrosModel->pegaTipoMembros();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}



	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codProjetoMembro');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->ProjetosMembrosModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codProjetoMembro'] = $this->request->getPost('codProjetoMembro');
		$fields['codMembro'] = $this->request->getPost('codMembro');
		$fields['codTipoMembro'] = $this->request->getPost('codTipoMembro');



		$this->validation->setRules([
			'codMembro' => ['label' => 'CodMembro', 'rules' => 'required|max_length[11]'],
			'codTipoMembro' => ['label' => 'CodTipoMembro', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->ProjetosMembrosModel->insert($fields)) {

				//seta Gestor
				if ($fields['codTipoMembro'] == 2) {
					$fieldsProjeto['codProjeto'] = $this->request->getPost('codProjetoMembro');
					$fieldsProjeto['codGestor'] = $this->request->getPost('codMembro');;
					if ($this->ProjetosModel->update($fieldsProjeto['codProjeto'], $fieldsProjeto)) {
					}
				}
				//seta Supervisor
				if ($fields['codTipoMembro'] == 3) {
					$fieldsProjeto['codProjeto'] = $this->request->getPost('codProjetoMembro');
					$fieldsProjeto['codSupervisor'] = $this->request->getPost('codMembro');;
					if ($this->ProjetosModel->update($fieldsProjeto['codProjeto'], $fieldsProjeto)) {
					}
				}



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

		$fields['codProjetoMembro'] = $this->request->getPost('codProjetoMembro');
		$fields['codMembro'] = $this->request->getPost('codMembro');
		$fields['codTipoMembro'] = $this->request->getPost('codTipoMembro');


		$this->validation->setRules([
			'codMembro' => ['label' => 'CodMembro', 'rules' => 'required|max_length[11]'],
			'codTipoMembro' => ['label' => 'CodTipoMembro', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->ProjetosMembrosModel->update($fields['codProjetoMembro'], $fields)) {

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

		$id = $this->request->getPost('codProjetoMembro');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->ProjetosMembrosModel->where('codProjetoMembro', $id)->delete()) {

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
