<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\PrescricoesOutrasModel;

class PrescricoesOutras extends BaseController
{

	protected $PrescricoesOutrasModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->PrescricoesOutrasModel = new PrescricoesOutrasModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('PrescricoesOutras', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "PrescricoesOutras"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'prescricoesOutras',
			'title'     		=> 'Outras da Prescrição'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('prescricoesOutras', $data);
	}


	public function listaDropDownOutras()
	{

		$result = $this->PrescricoesOutrasModel->listaDropDownOutras();

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
		$result = $this->PrescricoesOutrasModel->pegaPorCodigoAtendimentoPrescricao($codAtendimentoPrescricao);
		$x = count($result);
		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			if ($value->codStatus <= 1) {
				$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editprescricoesOutras(' . $value->codPrescricaoOutra . ')"><i class="fa fa-edit"></i></button>';
				$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeprescricoesOutras(' . $value->codPrescricaoOutra . ')"><i class="fa fa-trash"></i></button>';
			}
			$ops .= '</div>';

			$descricaoStatusOutras = '<span class="right badge badge-'.$value->corStatusOutras.'">' . $value->descricaoStatusOutras . '</span>';


			$data['data'][$key] = array(
				$x,
				$value->nomeTipoOutraPrescricao,
				$value->descricao,
				$value->apraza,
				$value->nomeExibicao,
				date('d/m/Y H:i', strtotime($value->dataAtualizacao)),
				$descricaoStatusOutras,
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

		$result = $this->PrescricoesOutrasModel->pegaTudo();
		$x = count($result);
		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			if ($value->codStatus <= 1) {
				$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editprescricoesOutras(' . $value->codPrescricaoOutra . ')"><i class="fa fa-edit"></i></button>';
				$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeprescricoesOutras(' . $value->codPrescricaoOutra . ')"><i class="fa fa-trash"></i></button>';
			}
			$ops .= '</div>';

			$data['data'][$key] = array(
				$x,
				$value->codAtendimentoPrescricao,
				$value->codOutras,
				$value->descricao,
				$value->codStatus,
				$value->apraza,
				$value->codAutor,
				$value->dataCriacao,
				$value->dataAtualizacao,

				$ops,
			);
			$x--;
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{

		$response = array();

		$id = $this->request->getPost('codPrescricaoOutra');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->PrescricoesOutrasModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{
		$response = array();

		$fields['codPrescricaoOutra'] = $this->request->getPost('codPrescricaoOutra');
		$fields['codAtendimentoPrescricao'] = $this->request->getPost('codAtendimentoPrescricao');
		$fields['codTipoOutraPrescricao'] = $this->request->getPost('codTipoOutraPrescricao');
		$fields['descricao'] = $this->request->getPost('descricao');
		$fields['codStatus'] = 1;
		$fields['apraza'] = $this->request->getPost('apraza');
		$fields['codAutor'] = session()->codPessoa;
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');


		$this->validation->setRules([
			'codAtendimentoPrescricao' => ['label' => 'CodAtendimentoPrescricao', 'rules' => 'required|numeric|max_length[11]'],
			'codTipoOutraPrescricao' => ['label' => 'codTipoOutraPrescricao', 'rules' => 'required|max_length[11]'],
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

			if ($this->PrescricoesOutrasModel->insert($fields)) {

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

		$fields['codPrescricaoOutra'] = $this->request->getPost('codPrescricaoOutra');
		$fields['codAtendimentoPrescricao'] = $this->request->getPost('codAtendimentoPrescricao');
		$fields['codTipoOutraPrescricao'] = $this->request->getPost('codTipoOutraPrescricao');
		$fields['descricao'] = $this->request->getPost('descricao');
		$fields['codStatus'] = 1;
		$fields['apraza'] = $this->request->getPost('apraza');
		$fields['codAutor'] = session()->codPessoa;
		$fields['dataAtualizacao'] = date('Y-m-d H:i');


		$this->validation->setRules([
			'codPrescricaoOutra' => ['label' => 'codPrescricaoOutra', 'rules' => 'required|numeric|max_length[11]'],
			'codAtendimentoPrescricao' => ['label' => 'CodAtendimentoPrescricao', 'rules' => 'required|numeric|max_length[11]'],
			'codTipoOutraPrescricao' => ['label' => 'codTipoOutraPrescricao', 'rules' => 'required|max_length[11]'],
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

			if ($fields['codPrescricaoOutra'] !== NULL and $fields['codPrescricaoOutra'] !== "") {
				if ($this->PrescricoesOutrasModel->update($fields['codPrescricaoOutra'], $fields)) {

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

		$id = $this->request->getPost('codPrescricaoOutra');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->PrescricoesOutrasModel->where('codPrescricaoOutra', $id)->delete()) {

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
