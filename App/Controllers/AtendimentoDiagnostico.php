<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\AtendimentoDiagnosticoModel;

class AtendimentoDiagnostico extends BaseController
{

	protected $AtendimentoDiagnosticoModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->AtendimentoDiagnosticoModel = new AtendimentoDiagnosticoModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('AtendimentoDiagnostico', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "AtendimentoDiagnostico"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'atendimentoDiagnostico',
			'title'     		=> 'atendimentoDiagnostico'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('atendimentoDiagnostico', $data);
	}

	public function getAllPorAtendimento()
	{
		$response = array();

		$data['data'] = array();
		$codAtendimento = $this->request->getPost('codAtendimento');


		$result = $this->AtendimentoDiagnosticoModel->getAllPorAtendimento($codAtendimento);


		$x = 1;
		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';


			$arrayStatusEncerraAtendimento = array(2, 3, 8, 9, 11);

			if (!in_array($value->codStatus, $arrayStatusEncerraAtendimento)) {
				$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeatendimentoDiagnostico(' . $value->codAtendimentoDiagnostico . ')"><i class="fa fa-trash"></i></button>';
			}
			$ops .= '</div>';

			if ($value->codTipoDiagnostico == 1) {
				$tipo = 'Principal';
			} else {
				$tipo = 'Secundário';
			}
			$data['data'][$key] = array(
				$x,
				$value->cid,
				$tipo,
				$value->nomeExibicao,
				date('d/m/Y H:i', strtotime($value->dataCriacao)),

				$ops,
			);
			$x++;
		}

		return $this->response->setJSON($data);
	}


	public function getAllPorAtendimentoHistorico()
	{
		$response = array();

		$data['data'] = array();
		$codAtendimento = $this->request->getPost('codAtendimento');


		$result = $this->AtendimentoDiagnosticoModel->historicoDiagnostico($codAtendimento);


		$x = 1;
		foreach ($result as $key => $value) {


			if ($value->codTipoDiagnostico == 1) {
				$tipo = 'Principal';
			} else {
				$tipo = 'Secundário';
			}
			$data['data'][$key] = array(
				$value->codAtendimento,
				$value->descricaoTipoAtendimento,
				$value->cid,
				$tipo,
				$value->nomeExibicao,
				date('d/m/Y H:i', strtotime($value->dataCriacao)),

			);
			$x++;
		}

		return $this->response->setJSON($data);
	}
	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->AtendimentoDiagnosticoModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editatendimentoDiagnostico(' . $value->codAtendimentoDiagnostico . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeatendimentoDiagnostico(' . $value->codAtendimentoDiagnostico . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$value->codAtendimentoDiagnostico,
				$value->codAtendimento,
				$value->codAutor,
				$value->codTipoDiagnostico,
				$value->dataCriacao,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codAtendimentoDiagnostico');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->AtendimentoDiagnosticoModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codAtendimentoDiagnostico'] = $this->request->getPost('codAtendimentoDiagnostico');
		$fields['codAtendimento'] = $this->request->getPost('codAtendimento');
		$fields['codCid'] = $this->request->getPost('codCid');
		$fields['codAutor'] = session()->codPessoa;
		$fields['codTipoDiagnostico'] = $this->request->getPost('tipoDiagnostico');
		$fields['dataCriacao'] = date('Y-m-d H:i');


		$this->validation->setRules([
			'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
			'codTipoDiagnostico' => ['label' => 'CodTipoDiagnostico', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {



			if ($fields['codTipoDiagnostico'] == 1 and $fields['codAtendimento'] !== null and $fields['codAtendimento'] !== '' and $fields['codAtendimento'] !== ' ') {

				if ($this->AtendimentoDiagnosticoModel->updateDiagnosticoPrincipal($fields['codAtendimento'])) {
				}
			}


			if ($this->AtendimentoDiagnosticoModel->insert($fields)) {

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

		$fields['codAtendimentoDiagnostico'] = $this->request->getPost('codAtendimentoDiagnostico');
		$fields['codAtendimento'] = $this->request->getPost('codAtendimento');
		$fields['codAutor'] = $this->request->getPost('codAutor');
		$fields['codTipoDiagnostico'] = $this->request->getPost('codTipoDiagnostico');
		$fields['dataCriacao'] = $this->request->getPost('dataCriacao');


		$this->validation->setRules([
			'codAtendimentoDiagnostico' => ['label' => 'codAtendimentoDiagnostico', 'rules' => 'required|numeric|max_length[11]'],
			'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
			'codTipoDiagnostico' => ['label' => 'CodTipoDiagnostico', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			//NÃO DEIXA ATUALIZAR SE CÓDIGO FOR NULO OU VAZIO
			if ($fields['codAtendimentoDiagnostico'] !== NULL and $fields['codAtendimentoDiagnostico'] !== "" and $fields['codAtendimentoDiagnostico'] !== " ") {
				if ($this->AtendimentoDiagnosticoModel->update($fields['codAtendimentoDiagnostico'], $fields)) {

					$response['success'] = true;
					$response['messages'] = 'Atualizado com sucesso';
				} else {

					$response['success'] = false;
					$response['messages'] = 'Erro na atualização!';
				}
			} else {
				$response['success'] = false;
				$response['messages'] = 'Erro na operação!';
				return $this->response->setJSON($response);
			}
		}

		return $this->response->setJSON($response);
	}

	public function remove()
	{
		$response = array();

		$id = $this->request->getPost('codAtendimentoDiagnostico');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->AtendimentoDiagnosticoModel->where('codAtendimentoDiagnostico', $id)->delete()) {

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
