<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\PrescricoesMaterialModel;

class PrescricoesMaterial extends BaseController
{

	protected $PrescricoesMaterialModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->PrescricoesMaterialModel = new PrescricoesMaterialModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('PrescricoesMaterial', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "PrescricoesMaterial"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'prescricoesMaterial',
			'title'     		=> 'Materials da Prescrição'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('prescricoesMaterial', $data);
	}


	public function listaDropDownMaterials()
	{

		$result = $this->PrescricoesMaterialModel->listaDropDownMaterials();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function getAllPorPrescricao()
	{
		$response = array();

		$data['data'] = array();
		$codAtendimentoPrescricao = $this->request->getPost('codAtendimentoPrescricao');
		$result = $this->PrescricoesMaterialModel->pegaPorCodigoAtendimentoPrescricao($codAtendimentoPrescricao);
		$x = count($result);
		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			if ($value->codStatus <= 1) {
				$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editprescricoesMaterial(' . $value->codPrescricaoMaterial . ')"><i class="fa fa-edit"></i></button>';
				$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeprescricoesMaterial(' . $value->codPrescricaoMaterial . ')"><i class="fa fa-trash"></i></button>';
			}
			$ops .= '</div>';

			$descricaoStatusMaterial = '<span class="right badge badge-' . $value->corStatusMaterial . '">' . $value->descricaoStatusMaterial . '</span>';


			$data['data'][$key] = array(
				$x,
				$value->descricaoItem . '<br><div class="right badge badge-danger">' . $value->observacao . '</div>',
				$value->qtde,
				$value->nomeExibicao,
				date('d/m/Y H:i', strtotime($value->dataAtualizacao)),
				$descricaoStatusMaterial,
				$ops,
			);
			$x--;
		}

		return $this->response->setJSON($data);
	}


	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->PrescricoesMaterialModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editprescricoesMaterial(' . $value->codPrescricaoMaterial . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeprescricoesMaterial(' . $value->codPrescricaoMaterial . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$value->codPrescricaoMaterial,
				$value->codAtendimentoPrescricao,
				$value->codMaterial,
				$value->qtde,
				$value->codStatus,
				$value->observacao,
				$value->codAutor,
				$value->dataCriacao,
				$value->dataAtualizacao,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codPrescricaoMaterial');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->PrescricoesMaterialModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codPrescricaoMaterial'] = $this->request->getPost('codPrescricaoMaterial');
		$fields['codAtendimentoPrescricao'] = $this->request->getPost('codAtendimentoPrescricao');
		$fields['codMaterial'] = $this->request->getPost('codMaterial');
		$fields['qtde'] = $this->request->getPost('qtde');
		$fields['codStatus'] = 1;
		$fields['observacao'] = $this->request->getPost('observacao');
		$fields['codAutor'] = session()->codPessoa;
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');


		$this->validation->setRules([
			'codAtendimentoPrescricao' => ['label' => 'CodAtendimentoPrescricao', 'rules' => 'required|numeric|max_length[11]'],
			'codMaterial' => ['label' => 'CodMaterial', 'rules' => 'required|max_length[11]'],
			'qtde' => ['label' => 'Qtde', 'rules' => 'required'],
			'codStatus' => ['label' => 'CodStatus', 'rules' => 'required|numeric|max_length[11]'],
			'observacao' => ['label' => 'Observacao', 'rules' => 'permit_empty'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->PrescricoesMaterialModel->insert($fields)) {

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

		$fields['codPrescricaoMaterial'] = $this->request->getPost('codPrescricaoMaterial');
		$fields['codAtendimentoPrescricao'] = $this->request->getPost('codAtendimentoPrescricao');
		$fields['codMaterial'] = $this->request->getPost('codMaterial');
		$fields['qtde'] = $this->request->getPost('qtde');
		$fields['observacao'] = $this->request->getPost('observacao');
		$fields['codAutor'] = session()->codPessoa;
		$fields['dataAtualizacao'] = date('Y-m-d H:i');


		$this->validation->setRules([
			'codPrescricaoMaterial' => ['label' => 'codPrescricaoMaterial', 'rules' => 'required|numeric|max_length[11]'],
			'codAtendimentoPrescricao' => ['label' => 'CodAtendimentoPrescricao', 'rules' => 'required|numeric|max_length[11]'],
			'codMaterial' => ['label' => 'CodMaterial', 'rules' => 'required|max_length[11]'],
			'qtde' => ['label' => 'Qtde', 'rules' => 'required'],
			'observacao' => ['label' => 'Observacao', 'rules' => 'permit_empty'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($fields['codPrescricaoMaterial'] !== NULL and $fields['codPrescricaoMaterial'] !== "") {

				if ($this->PrescricoesMaterialModel->update($fields['codPrescricaoMaterial'], $fields)) {

					$response['success'] = true;
					$response['messages'] = 'Atualizado com sucesso';
				} else {

					$response['success'] = false;
					$response['messages'] = 'Erro na atualização!';
				}
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

		$id = $this->request->getPost('codPrescricaoMaterial');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->PrescricoesMaterialModel->where('codPrescricaoMaterial', $id)->delete()) {

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
