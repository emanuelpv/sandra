<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\PrescricoesCuidadosModel;

class PrescricoesCuidados extends BaseController
{

	protected $PrescricoesCuidadosModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->PrescricoesCuidadosModel = new PrescricoesCuidadosModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('PrescricoesCuidados', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "PrescricoesCuidados"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'prescricoesCuidados',
			'title'     		=> 'Cuidados da Prescrição'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('prescricoesCuidados', $data);
	}


	public function listaDropDownCuidados()
	{

		$result = $this->PrescricoesCuidadosModel->listaDropDownCuidados();

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
		$result = $this->PrescricoesCuidadosModel->pegaPorCodigoAtendimentoPrescricao($codAtendimentoPrescricao);
		$x = count($result);
		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			if ($value->codStatus <= 1) {
				$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editprescricoesCuidados(' . $value->codPrescricaoCuidado . ')"><i class="fa fa-edit"></i></button>';
				$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeprescricoesCuidados(' . $value->codPrescricaoCuidado . ')"><i class="fa fa-trash"></i></button>';
			}
			$ops .= '</div>';

			$descricaoStatusCuidado = '<span class="right badge badge-'.$value->corStatusCuidado.'">' . $value->descricaoStatusCuidado . '</span>';


			$data['data'][$key] = array(
				$x,
				$value->nomeTipoCuidadoPrescricao,
				$value->descricao,
				$value->apraza,
				$value->nomeExibicao,
				date('d/m/Y H:i', strtotime($value->dataAtualizacao)),
				$descricaoStatusCuidado,
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

		$result = $this->PrescricoesCuidadosModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editprescricoesCuidados(' . $value->codPrescricaoCuidado . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeprescricoesCuidados(' . $value->codPrescricaoCuidado . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$value->codPrescricaoCuidado,
				$value->codAtendimentoPrescricao,
				$value->codTipoCuidadoPrescricaos,
				$value->descricao,
				$value->codStatus,
				$value->apraza,
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

		$id = $this->request->getPost('codPrescricaoCuidado');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->PrescricoesCuidadosModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{
		$response = array();

		$fields['codPrescricaoCuidado'] = $this->request->getPost('codPrescricaoCuidado');
		$fields['codAtendimentoPrescricao'] = $this->request->getPost('codAtendimentoPrescricao');
		$fields['codTipoCuidadoPrescricao'] = $this->request->getPost('codTipoCuidadoPrescricao');
		$fields['descricao'] = $this->request->getPost('descricao');
		$fields['codStatus'] = 1;
		$fields['apraza'] = $this->request->getPost('apraza');
		$fields['codAutor'] = session()->codPessoa;
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');


		$this->validation->setRules([
			'codAtendimentoPrescricao' => ['label' => 'CodAtendimentoPrescricao', 'rules' => 'required|numeric|max_length[11]'],
			'codTipoCuidadoPrescricao' => ['label' => 'codTipoCuidadoPrescricao', 'rules' => 'required|max_length[11]'],
			'descricao' => ['label' => 'Descrição', 'rules' => 'required'],
			'codStatus' => ['label' => 'CodStatus', 'rules' => 'required|numeric|max_length[11]'],
			'apraza' => ['label' => 'Apraza', 'rules' => 'permit_empty'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->PrescricoesCuidadosModel->insert($fields)) {

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

		$fields['codPrescricaoCuidado'] = $this->request->getPost('codPrescricaoCuidado');
		$fields['codAtendimentoPrescricao'] = $this->request->getPost('codAtendimentoPrescricao');
		$fields['codTipoCuidadoPrescricao'] = $this->request->getPost('codTipoCuidadoPrescricao');
		$fields['descricao'] = $this->request->getPost('descricao');
		$fields['codStatus'] = 1;
		$fields['apraza'] = $this->request->getPost('apraza');
		$fields['codAutor'] = session()->codPessoa;
		$fields['dataAtualizacao'] = date('Y-m-d H:i');


		$this->validation->setRules([
			'codPrescricaoCuidado' => ['label' => 'codPrescricaoCuidado', 'rules' => 'required|numeric|max_length[11]'],
			'codAtendimentoPrescricao' => ['label' => 'CodAtendimentoPrescricao', 'rules' => 'required|numeric|max_length[11]'],
			'codTipoCuidadoPrescricao' => ['label' => 'codTipoCuidadoPrescricao', 'rules' => 'required|max_length[11]'],
			'descricao' => ['label' => 'Descrição', 'rules' => 'required'],
			'codStatus' => ['label' => 'CodStatus', 'rules' => 'required|numeric|max_length[11]'],
			'apraza' => ['label' => 'Apraza', 'rules' => 'permit_empty'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($fields['codPrescricaoCuidado'] !== NULL and $fields['codPrescricaoCuidado'] !== "") {
				if ($this->PrescricoesCuidadosModel->update($fields['codPrescricaoCuidado'], $fields)) {

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

		$id = $this->request->getPost('codPrescricaoCuidado');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->PrescricoesCuidadosModel->where('codPrescricaoCuidado', $id)->delete()) {

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
