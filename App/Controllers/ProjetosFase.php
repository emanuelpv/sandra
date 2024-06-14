<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\ProjetosFaseModel;

class ProjetosFase extends BaseController
{

	protected $projetosFaseModel;
	protected $pessoasModel;
	protected $organizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->ProjetosFaseModel = new ProjetosFaseModel();
		$this->organizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->organizacao =  $this->organizacoesModel->pegaOrganizacao($this->codOrganizacao);


		$permissao = verificaPermissao('ProjetosFase', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo ProjetosFase', session()->codPessoa);
			exit();
		}
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'projetosFase',
			'title'     		=> 'Fases Projeto'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('projetosFase', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->ProjetosFaseModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editprojetosFase(' . $value->codProjetoFase . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeprojetosFase(' . $value->codProjetoFase . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codProjetoFase,
				$value->codProjeto,
				$value->descricaoFase,
				$value->dataInicial,
				$value->dataEncerramento,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}


	public function fasesProjeto()
	{
		$response = array();

		$data['data'] = array();

		$codProjeto = $this->request->getPost('codProjeto');

		$result = $this->ProjetosFaseModel->fasesProjeto($codProjeto);
		$x = 0;
		foreach ($result as $key => $value) {
			$x++;
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editprojetosFase(' . $value->codProjetoFase . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeprojetosFase(' . $value->codProjetoFase . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$x,
				$value->descricaoFase,
				date('d/m/Y', strtotime($value->dataInicial)),
				date('d/m/Y', strtotime($value->dataEncerramento)),
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}


	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codProjetoFase');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->ProjetosFaseModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codProjeto'] = $this->request->getPost('codProjeto');
		$fields['descricaoFase'] = $this->request->getPost('descricaoFase');
		$fields['dataInicial'] = $this->request->getPost('dataInicial');
		$fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');


		$this->validation->setRules([
			'codProjeto' => ['label' => 'CodProjeto', 'rules' => 'required|numeric|max_length[11]'],
			'descricaoFase' => ['label' => 'DescricaoFase', 'rules' => 'required|max_length[100]'],
			'dataInicial' => ['label' => 'DataInicial', 'rules' => 'permit_empty'],
			'dataEncerramento' => ['label' => 'DataEncerramento', 'rules' => 'permit_empty'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->ProjetosFaseModel->insert($fields)) {

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

		$fields['codProjetoFase'] = $this->request->getPost('codProjetoFase');
		$fields['descricaoFase'] = $this->request->getPost('descricaoFase');
		$fields['dataInicial'] = $this->request->getPost('dataInicial');
		$fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');


		$this->validation->setRules([
			'codProjetoFase' => ['label' => 'codProjetoFase', 'rules' => 'required|numeric|max_length[11]'],
			'descricaoFase' => ['label' => 'DescricaoFase', 'rules' => 'required|max_length[100]'],
			'dataInicial' => ['label' => 'DataInicial', 'rules' => 'permit_empty'],
			'dataEncerramento' => ['label' => 'DataEncerramento', 'rules' => 'permit_empty'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->ProjetosFaseModel->update($fields['codProjetoFase'], $fields)) {

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

		$id = $this->request->getPost('codProjetoFase');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->ProjetosFaseModel->where('codProjetoFase', $id)->delete()) {

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
