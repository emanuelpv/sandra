<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\AtoCirurgicoMatMedModel;

class AtoCirurgicoMatMed extends BaseController
{

	protected $AtoCirurgicoMatMedModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->AtoCirurgicoMatMedModel = new AtoCirurgicoMatMedModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('AtoCirurgicoMatMed', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "AtoCirurgicoMatMed"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'atoCirurgicoMatMed',
			'title'     		=> 'Documento Cirurgico MatMed'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('atoCirurgicoMatMed', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->AtoCirurgicoMatMedModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editatoCirurgicoMatMed(' . $value->codAtoCirurgicoMatMed . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeatoCirurgicoMatMed(' . $value->codAtoCirurgicoMatMed . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$value->codAtoCirurgicoMatMed,
				$value->codAtoCirurgico,
				$value->codMatMed,
				$value->qtde,
				$value->observacao,
				$value->dataCriacao,
				$value->codAutor,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}
	public function getAllMatMedAtoCirurgico()
	{
		$response = array();

		$data['data'] = array();
		$codAtoCirurgico = $this->request->getPost('codAtoCirurgico');
		$tipoItem = $this->request->getPost('tipoItem');



		$result = $this->AtoCirurgicoMatMedModel->getAllMatMedAtoCirurgico($codAtoCirurgico,$tipoItem);
		$x = 0;
		foreach ($result as $key => $value) {
			$x++;
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editatoCirurgicoMatMed(' . $value->codAtoCirurgicoMatMed . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeatoCirurgicoMatMed(' . $value->codAtoCirurgicoMatMed . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';


			$data['data'][$key] = array(
				$x,
				$value->descricaoItem,
				$value->descricaoCategoria,
				$value->qtde,
				$value->descricaoUnidade,
				$value->observacao,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}
	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codAtoCirurgicoMatMed');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->AtoCirurgicoMatMedModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codAtoCirurgico'] = $this->request->getPost('codAtoCirurgico');
		$fields['codMatMed'] = $this->request->getPost('codMatMed');
		$fields['qtde'] = brl2decimal($this->request->getPost('qtde'));
		$fields['codUnidade'] = $this->request->getPost('codUnidade');
		$fields['observacao'] = $this->request->getPost('observacao');
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['codAutor'] = session()->codPessoa;


		$this->validation->setRules([
			'codAtoCirurgico' => ['label' => 'CodAtoCirurgico', 'rules' => 'required|numeric|max_length[11]'],
			'codMatMed' => ['label' => 'CodMatMed', 'rules' => 'required|numeric|max_length[11]'],
			'qtde' => ['label' => 'Qtde', 'rules' => 'required'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtoCirurgicoMatMedModel->insert($fields)) {

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

		$fields['codAtoCirurgicoMatMed'] = $this->request->getPost('codAtoCirurgicoMatMed');
		$fields['codMatMed'] = $this->request->getPost('codMatMed');
		$fields['qtde'] = brl2decimal($this->request->getPost('qtde'));
		$fields['codUnidade'] = $this->request->getPost('codUnidade');
		$fields['observacao'] = $this->request->getPost('observacao');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['codAutor'] = session()->codPessoa;


		$this->validation->setRules([
			'codAtoCirurgicoMatMed' => ['label' => 'CodAtoCirurgicoMatMed', 'rules' => 'required|numeric|max_length[11]'],
			'codMatMed' => ['label' => 'CodMatMed', 'rules' => 'required|numeric|max_length[11]'],
			'qtde' => ['label' => 'Qtde', 'rules' => 'required'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtoCirurgicoMatMedModel->update($fields['codAtoCirurgicoMatMed'], $fields)) {

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

		$id = $this->request->getPost('codAtoCirurgicoMatMed');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->AtoCirurgicoMatMedModel->where('codAtoCirurgicoMatMed', $id)->delete()) {

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
