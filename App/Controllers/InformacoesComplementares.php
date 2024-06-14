<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\InformacoesComplementaresModel;

class InformacoesComplementares extends BaseController
{

	protected $InformacoesComplementaresModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->InformacoesComplementaresModel = new InformacoesComplementaresModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation = \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao = $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);



	}

	public function index()
	{

		$permissao = verificaPermissao('InformacoesComplementares', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "InformacoesComplementares"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller' => 'informacoesComplementares',
			'title' => 'Informações Complementares'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('informacoesComplementares', $data);

	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->InformacoesComplementaresModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editinformacoesComplementares(' . $value->codInforComplementar . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeinformacoesComplementares(' . $value->codInforComplementar . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$value->codInforComplementar,
				$value->codRequisicao,
				$value->codCategoria,
				$value->conteudo,
				$value->dataCriacao,
				$value->dataAtualizacao,
				$value->codAutor,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}


	

	public function listaDropDownCategoriaInformacoesComplementares()
	{

		$result = $this->InformacoesComplementaresModel->listaDropDownCategoriaInformacoesComplementares();

		if ($result !== NULL) {

			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function informacoesComplementaresRequisicao()
	{
		$response = array();


		$codRequisicao = $this->request->getPost('codRequisicao');

		$data['data'] = array();

		$result = $this->InformacoesComplementaresModel->informacoesComplementaresRequisicao($codRequisicao);
		$x = 0;
		foreach ($result as $key => $value) {
			$x++;
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editinformacoesComplementares(' . $value->codInforComplementar . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeinformacoesComplementares(' . $value->codInforComplementar . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';


			$data['data'][$key] = array(
				$x,
				$value->descricaoCategoria,
				$value->conteudo,
				date('d/m/Y H:i', strtotime($value->dataCriacao)),
				$value->nomeExibicao,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}




	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codInforComplementar');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->InformacoesComplementaresModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);

		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();

		}

	}

	public function add()
	{

		$response = array();

		$fields['codInforComplementar'] = $this->request->getPost('codInforComplementar');
		$fields['codRequisicao'] = $this->request->getPost('codRequisicao');
		$fields['codCategoria'] = $this->request->getPost('codCategoria');
		$fields['conteudo'] = $this->request->getPost('conteudo');
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['codAutor'] = session()->codPessoa;


		$this->validation->setRules([
			'codRequisicao' => ['label' => 'CodRequisicao', 'rules' => 'required|numeric|max_length[11]'],
			'codCategoria' => ['label' => 'CodCategoria', 'rules' => 'required|numeric|max_length[11]'],
			'conteudo' => ['label' => 'Conteudo', 'rules' => 'required'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'permit_empty|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();

		} else {

			if ($this->InformacoesComplementaresModel->insert($fields)) {

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

		$fields['codInforComplementar'] = $this->request->getPost('codInforComplementar');
		$fields['codRequisicao'] = $this->request->getPost('codRequisicao');
		$fields['codCategoria'] = $this->request->getPost('codCategoria');
		$fields['conteudo'] = $this->request->getPost('conteudo');
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['codAutor'] = session()->codPessoa;


		$this->validation->setRules([
			'codInforComplementar' => ['label' => 'codInforComplementar', 'rules' => 'required|numeric|max_length[11]'],
			'codRequisicao' => ['label' => 'CodRequisicao', 'rules' => 'required|numeric|max_length[11]'],
			'codCategoria' => ['label' => 'CodCategoria', 'rules' => 'required|numeric|max_length[11]'],
			'conteudo' => ['label' => 'Conteudo', 'rules' => 'required'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'permit_empty|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();

		} else {

			if ($this->InformacoesComplementaresModel->update($fields['codInforComplementar'], $fields)) {

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

		$id = $this->request->getPost('codInforComplementar');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();

		} else {

			if ($this->InformacoesComplementaresModel->where('codInforComplementar', $id)->delete()) {

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