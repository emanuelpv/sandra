<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\PrevisaoAltaModel;

class PrevisaoAlta extends BaseController
{

	protected $PrevisaoAltaModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->PrevisaoAltaModel = new PrevisaoAltaModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('PrevisaoAlta', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "PrevisaoAlta"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'previsaoAlta',
			'title'     		=> 'previsaoAlta'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('previsaoAlta', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$codAtendimento = $this->request->getPost('codAtendimento');


		if ($this->validation->check($codAtendimento, 'required|numeric')) {

			$result = $this->PrevisaoAltaModel->pegaPrevisaoAltaPorCodAtendimento($codAtendimento);

			$x = 0;
			foreach ($result as $key => $value) {
				$x++;
				$ops = '<div class="btn-group">';
				$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeprevisaoAlta(' . $value->codPrevAlta . ')"><i class="fa fa-trash"></i></button>';
				$ops .= '</div>';


				if ($value->dataPrevAlta == NULL) {
					$dataPrevAlta = 'Indeterminada';
				} else {
					$dataPrevAlta = date('d/m/Y', strtotime($value->dataPrevAlta));
				}
				$data['data'][$key] = array(
					$x,
					$dataPrevAlta,
					htmlentities($value->condicaoAlta),
					$value->nomeExibicao,
					$ops,
				);
			}

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codPrevAlta');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->PrevisaoAltaModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codPrevAlta'] = $this->request->getPost('codPrevAlta');
		$fields['codAtendimento'] = $this->request->getPost('codAtendimento');
		$fields['codAutor'] = session()->codPessoa;
		if ($this->request->getPost('previsaoIndeterminada') == 'on') {
			$fields['dataPrevAlta'] = null;
			$fields['indeterminado'] = 1;
			$fields['condicaoAlta'] = 'Indeterminada';
		} else {


			$fields['condicaoAlta'] = $this->request->getPost('condicaoAlta');
			$fields['dataPrevAlta'] = $this->request->getPost('dataPrevAlta');
		}

		$this->validation->setRules([
			'codAtendimento' => ['label' => 'codAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
			'condicaoAlta' => ['label' => 'CondicaoAlta', 'rules' => 'bloquearReservado'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->PrevisaoAltaModel->insert($fields)) {

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

		$fields['codPrevAlta'] = $this->request->getPost('codPrevAlta');
		$fields['codAtendimento'] = $this->request->getPost('codAtendimento');
		$fields['codAutor'] = $this->request->getPost('codAutor');
		$fields['condicaoAlta'] = $this->request->getPost('condicaoAlta');
		$fields['dataPrevAlta'] = $this->request->getPost('dataPrevAlta');


		$this->validation->setRules([
			'codPrevAlta' => ['label' => 'codPrevAlta', 'rules' => 'required|numeric|max_length[11]'],
			'codAtendimento' => ['label' => 'codAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
			'condicaoAlta' => ['label' => 'CondicaoAlta', 'rules' => 'required'],
			'dataPrevAlta' => ['label' => 'dataPrevAlta', 'rules' => 'bloquearReservado'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->PrevisaoAltaModel->update($fields['codPrevAlta'], $fields)) {

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

		$id = $this->request->getPost('codPrevAlta');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->PrevisaoAltaModel->where('codPrevAlta', $id)->delete()) {

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
