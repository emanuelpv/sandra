<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;
use App\Models\PrescricoesKitModel;
use App\Models\PrescricoesMaterialModel;
use App\Models\PrescricaoMedicamentosModel;

class PrescricoesKit extends BaseController
{

	protected $PrescricoesKitModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->PrescricoesKitModel = new PrescricoesKitModel();
		$this->PrescricoesMaterialModel = new PrescricoesMaterialModel();
		$this->PrescricaoMedicamentosModel = new PrescricaoMedicamentosModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('PrescricoesKit', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "PrescricoesKit"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'prescricoesKit',
			'title'     		=> 'Kits da Prescrição'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('prescricoesKit', $data);
	}


	public function listaDropDownKits()
	{

		$result = $this->PrescricoesKitModel->listaDropDownKits();

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
		$result = $this->PrescricoesKitModel->pegaPorCodigoAtendimentoPrescricao($codAtendimentoPrescricao);
		$x = count($result);
		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			if ($value->codStatus <= 1) {
				$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeprescricoesKit(' . $value->codPrescricaoKit . ')"><i class="fa fa-trash"></i></button>';
			}
			$ops .= '</div>';


			$descricaoStatusKit = '<span class="right badge badge-' . $value->corStatusKit . '">' . $value->descricaoStatusKit . '</span>';



			$data['data'][$key] = array(
				$x,
				$value->descricaoKit,
				$value->qtde,
				$value->observacao,
				$value->nomeExibicao,
				date('d/m/Y H:i', strtotime($value->dataAtualizacao)),
				$descricaoStatusKit,
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

		$result = $this->PrescricoesKitModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editprescricoesKit(' . $value->codPrescricaoKit . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeprescricoesKit(' . $value->codPrescricaoKit . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$value->codPrescricaoKit,
				$value->codAtendimentoPrescricao,
				$value->codKit,
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

		$id = $this->request->getPost('codPrescricaoKit');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->PrescricoesKitModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codPrescricaoKit'] = $this->request->getPost('codPrescricaoKit');
		$fields['codAtendimentoPrescricao'] = $this->request->getPost('codAtendimentoPrescricao');
		$fields['codKit'] = $this->request->getPost('codKit');
		$fields['qtde'] = $this->request->getPost('qtde');
		$fields['codStatus'] = 1;
		$fields['observacao'] = $this->request->getPost('observacao');
		$fields['codAutor'] = session()->codPessoa;
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');


		$this->validation->setRules([
			'codAtendimentoPrescricao' => ['label' => 'CodAtendimentoPrescricao', 'rules' => 'required|numeric|max_length[11]'],
			'codKit' => ['label' => 'CodKit', 'rules' => 'required|max_length[11]'],
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

			if ($codPrescricaoKit = $this->PrescricoesKitModel->insert($fields)) {



				//INSERE MEDICAMENTOS E MATERIAIS


				$itensKit = $this->PrescricoesKitModel->pegeItensKits($fields['codKit']);


				foreach ($itensKit as $item) {


					if ($item->codCategoria == 6) {
						//MATERIAL


						$fieldsMaterial = array();

						$fieldsMaterial['codAtendimentoPrescricao'] = 	$fields['codAtendimentoPrescricao'];
						$fieldsMaterial['codMaterial'] = $item->codItem;
						$fieldsMaterial['qtde'] = $item->qtde * $fields['qtde'];
						$fieldsMaterial['codStatus'] = 1;
						$fieldsMaterial['observacao'] = 'Origem de Kit';
						$fieldsMaterial['codAutor'] = session()->codPessoa;
						$fieldsMaterial['dataCriacao'] = date('Y-m-d H:i');
						$fieldsMaterial['dataAtualizacao'] = date('Y-m-d H:i');
						$fieldsMaterial['codPrescricaoKit'] = $codPrescricaoKit;

						if ($this->PrescricoesMaterialModel->insert($fieldsMaterial)) {
						}
					} else {
						//MEDICAMENTOS

						$fieldsMedicamento = array();

						$fieldsMedicamento['codAtendimentoPrescricao'] = 	$fields['codAtendimentoPrescricao'];
						$fieldsMedicamento['codMedicamento'] = $item->codItem;
						$fieldsMedicamento['qtde'] = $item->qtde * $fields['qtde'];
						$fieldsMedicamento['und'] = 1;
						$fieldsMedicamento['via'] = 0;
						$fieldsMedicamento['freq'] = 1;
						$fieldsMedicamento['per'] = 0;
						$fieldsMedicamento['dias'] = 0;
						$fieldsMedicamento['horaIni'] = NULL;
						$fieldsMedicamento['agora'] = 0;
						$fieldsMedicamento['risco'] = 0;
						$fieldsMedicamento['obs'] = 'Origem de Kit';
						$fieldsMedicamento['apraza'] = NULL;
						$fieldsMedicamento['total'] = $item->qtde * $fields['qtde'];
						$fieldsMedicamento['stat'] = 1;
						$fieldsMedicamento['codAutor'] = session()->codPessoa;
						$fieldsMedicamento['dataCriacao'] = date('Y-m-d H:i');
						$fieldsMedicamento['dataAtualizacao'] = date('Y-m-d H:i');
						$fieldsMedicamento['codPrescricaoKit'] = $codPrescricaoKit;

						if ($this->PrescricaoMedicamentosModel->insert($fieldsMedicamento)) {
						}
					}
				}


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

		$fields['codPrescricaoKit'] = $this->request->getPost('codPrescricaoKit');
		$fields['codAtendimentoPrescricao'] = $this->request->getPost('codAtendimentoPrescricao');
		$fields['codKit'] = $this->request->getPost('codKit');
		$fields['qtde'] = $this->request->getPost('qtde');
		$fields['codStatus'] = $this->request->getPost('codStatus');
		$fields['observacao'] = $this->request->getPost('observacao');
		$fields['codAutor'] = $this->request->getPost('codAutor');
		$fields['dataCriacao'] = $this->request->getPost('dataCriacao');
		$fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');


		$this->validation->setRules([
			'codPrescricaoKit' => ['label' => 'codPrescricaoKit', 'rules' => 'required|numeric|max_length[11]'],
			'codAtendimentoPrescricao' => ['label' => 'CodAtendimentoPrescricao', 'rules' => 'required|numeric|max_length[11]'],
			'codKit' => ['label' => 'CodKit', 'rules' => 'required|max_length[11]'],
			'qtde' => ['label' => 'Qtde', 'rules' => 'required'],
			'codStatus' => ['label' => 'CodStatus', 'rules' => 'required|numeric|max_length[11]'],
			'observacao' => ['label' => 'Observacao', 'rules' => 'required'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {


			if ($this->PrescricoesKitModel->update($fields['codPrescricaoKit'], $fields)) {

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

		$codPrescricaoKit = $this->request->getPost('codPrescricaoKit');

		if (!$this->validation->check($codPrescricaoKit, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->PrescricoesKitModel->where('codPrescricaoKit', $codPrescricaoKit)->delete()) {



				//REMOVER MATERIAIS FROM KIT

				$this->PrescricoesKitModel->removeMateriaisKit($codPrescricaoKit);



				//REMOVER MEDICAMENTOS FROM KIT				
				$this->PrescricoesKitModel->removeMedicamentosKit($codPrescricaoKit);




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
