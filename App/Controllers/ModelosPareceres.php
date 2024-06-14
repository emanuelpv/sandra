<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\ModelosPareceresModel;

class ModelosPareceres extends BaseController
{

	protected $ModelosPareceresModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->ModelosPareceresModel = new ModelosPareceresModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('ModelosPareceres', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "ModelosPareceres"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'ModelosPareceres',
			'title'     		=> 'Modelos Pareceres'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('ModelosPareceres', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->ModelosPareceresModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Usar"  onclick="usarModeloParecer(' . $value->codModelo . ')"><i class="fa fa-edit"></i> Usar</button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeModeloParecer(' . $value->codModelo . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$value->codModelo,
				$value->descricaoTipoModelo,
				$value->titulo,
				$value->conteudo,
				$value->dataCriacao,
				$value->codAutor,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}


	public function meusModelos()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->ModelosPareceresModel->pegaMeusModelos();
		$x = 0;
		foreach ($result as $key => $value) {
			$x++;
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Usar"  onclick="usarModeloParecer(' . $value->codModelo . ')"><i class="fa fa-check"></i> Usar</button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeModelosPareceres(' . $value->codModelo . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			if (strlen($value->conteudo) >= 100) {

				$conteudo = mb_substr($value->conteudo, 0, 90);
			} else {
				$conteudo = $value->conteudo;
			}

			$data['data'][$key] = array(
				$x,
				$value->titulo,
				$conteudo,
								date('d/m/Y H:i',strtotime($value->dataCriacao)),

				$value->nomeExibicao,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}


	public function outrosModelos()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->ModelosPareceresModel->pegaOutrosModelos();
		$x = 0;
		foreach ($result as $key => $value) {
			$x++;
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Usar"  onclick="usarModeloParecer(' . $value->codModelo . ')"><i class="fa fa-check"></i> Usar</button>';
			$ops .= '</div>';

			if (strlen($value->conteudo) >= 100) {

				$conteudo = mb_substr($value->conteudo, 0, 90);
			} else {
				$conteudo = $value->conteudo;
			}

			$data['data'][$key] = array(

				$x,
				$value->titulo,
				$conteudo,
								date('d/m/Y H:i',strtotime($value->dataCriacao)),

				$value->nomeExibicao,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codModelo');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->ModelosPareceresModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function salvarModelo()
	{

		$response = array();

		$fields['titulo'] = $this->request->getPost('titulo');;
		$fields['conteudo'] = $this->request->getPost('conteudo');
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['codAutor'] = session()->codPessoa;


		$this->validation->setRules([
			'titulo' => ['label' => 'Titulo', 'rules' => 'required|max_length[100]'],
			'conteudo' => ['label' => 'Conteudo', 'rules' => 'required'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->ModelosPareceresModel->insert($fields)) {

				$response['success'] = true;
				$response['messages'] = 'Modelo salvo com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na inserção!';
			}
		}

		return $this->response->setJSON($response);
	}

	public function add()
	{

		$response = array();

		$fields['codModelo'] = $this->request->getPost('codModelo');
		$fields['codTipoModelo'] = $this->request->getPost('codTipoModelo');
		$fields['titulo'] = $this->request->getPost('titulo');
		$fields['conteudo'] = $this->request->getPost('conteudo');
		$fields['dataCriacao'] = $this->request->getPost('dataCriacao');
		$fields['codAutor'] = $this->request->getPost('codAutor');


		$this->validation->setRules([
			'codTipoModelo' => ['label' => 'CodTipoModelo', 'rules' => 'required|numeric|max_length[11]'],
			'titulo' => ['label' => 'Titulo', 'rules' => 'required|max_length[100]'],
			'conteudo' => ['label' => 'Conteudo', 'rules' => 'required'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->ModelosPareceresModel->insert($fields)) {

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

		$fields['codModelo'] = $this->request->getPost('codModelo');
		$fields['codTipoModelo'] = $this->request->getPost('codTipoModelo');
		$fields['titulo'] = $this->request->getPost('titulo');
		$fields['conteudo'] = $this->request->getPost('conteudo');
		$fields['dataCriacao'] = $this->request->getPost('dataCriacao');
		$fields['codAutor'] = $this->request->getPost('codAutor');


		$this->validation->setRules([
			'codModelo' => ['label' => 'codModelo', 'rules' => 'required|numeric|max_length[11]'],
			'codTipoModelo' => ['label' => 'CodTipoModelo', 'rules' => 'required|numeric|max_length[11]'],
			'titulo' => ['label' => 'Titulo', 'rules' => 'required|max_length[100]'],
			'conteudo' => ['label' => 'Conteudo', 'rules' => 'required'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->ModelosPareceresModel->update($fields['codModelo'], $fields)) {

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

		$id = $this->request->getPost('codModelo');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->ModelosPareceresModel->where('codModelo', $id)->delete()) {

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
