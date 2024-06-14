<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\ProtocolosRedeModel;

class ProtocolosRede extends BaseController
{

	protected $protocolosRedeModel;
	protected $pessoasModel;
	protected $organizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->ProtocolosRedeModel = new ProtocolosRedeModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);

		$permissao = verificaPermissao('ProtocolosRede', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo ProtocolosRede', session()->codPessoa);
			exit();
		}
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'protocolosRede',
			'title'     		=> 'Protocolos de Rede'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('protocolosRede', $data);
	}


	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->ProtocolosRedeModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Editar" onclick="editprotocolosRede(' . $value->codProtocoloRede . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="removeprotocolosRede(' . $value->codProtocoloRede . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codProtocoloRede,
				$value->nomeProtocoloRede,
				$value->conector,
				$value->portaPadrao,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}


	public function integracaoProtocolos()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->ProtocolosRedeModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Editar" onclick="editprotocolosRede(' . $value->codProtocoloRede . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="removeprotocolosRede(' . $value->codProtocoloRede . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codProtocoloRede,
				$value->nomeProtocoloRede,
				$value->portaPadrao,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codProtocoloRede');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->ProtocolosRedeModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codProtocoloRede'] = $this->request->getPost('codProtocoloRede');
		$fields['nomeProtocoloRede'] = $this->request->getPost('nomeProtocoloRede');
		$fields['conector'] = $this->request->getPost('conector');
		$fields['portaPadrao'] = $this->request->getPost('portaPadrao');


		$this->validation->setRules([
			'nomeProtocoloRede' => ['label' => 'Nome', 'rules' => 'required|max_length[40]'],
			'conector' => ['label' => 'Conector', 'rules' => 'required|max_length[40]'],
			'portaPadrao' => ['label' => 'Porta Padrão', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->ProtocolosRedeModel->insert($fields)) {

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

		$fields['codProtocoloRede'] = $this->request->getPost('codProtocoloRede');
		$fields['nomeProtocoloRede'] = $this->request->getPost('nomeProtocoloRede');
		$fields['conector'] = $this->request->getPost('conector');
		$fields['portaPadrao'] = $this->request->getPost('portaPadrao');


		$this->validation->setRules([
			'nomeProtocoloRede' => ['label' => 'Nome', 'rules' => 'required|max_length[40]'],
			'conector' => ['label' => 'Conector', 'rules' => 'required|max_length[40]'],
			'portaPadrao' => ['label' => 'Porta Padrão', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->ProtocolosRedeModel->update($fields['codProtocoloRede'], $fields)) {

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

		$id = $this->request->getPost('codProtocoloRede');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->ProtocolosRedeModel->where('codProtocoloRede', $id)->delete()) {

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
