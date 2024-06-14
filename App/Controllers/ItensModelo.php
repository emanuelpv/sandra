<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\ItensModeloModel;

class ItensModelo extends BaseController
{

	protected $ItensModeloModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->ItensModeloModel = new ItensModeloModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('ItensModelo', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "ItensModelo"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'itensModelo',
			'title'     		=> 'Itens Modelo'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('itensModelo', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->ItensModeloModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="edititensModelo(' . $value->codItemModelo . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeitensModelo(' . $value->codItemModelo . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$value->codItemModelo,
				$value->codCat,
				$value->descricao,
				$value->descricaoTipoMaterial,
				$value->dataCriacao,
				$value->nomeExibicao,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function modelos()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->ItensModeloModel->pegaTudo();

		foreach ($result as $key => $value) {
			$informacoesComplementares = '
			<div class="row">
			<div class="col-md-12">
' . $value->descricaoTipoMaterial . '
			</div>
			<div class="col-md-12">
' . date('d/m/Y',strtotime($value->dataCriacao)) . '
			</div>
			<div class="col-md-12">
' . $value->nomeExibicao . '
			</div>
			</div>
			
			
			
			';
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn  btn-success"  data-toggle="tooltip" data-placement="top" title="Usar Modelo"  onclick="usarModelo(' . $value->codItemModelo . ')">Usar Modelo</button>';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$value->codItemModelo,
				$value->codCat,
				$value->descricao,
				$informacoesComplementares,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}
	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codItemModelo');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->ItensModeloModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codItemModelo'] = $this->request->getPost('codItemModelo');
		$fields['codCat'] = $this->request->getPost('codCat');
		$fields['descricao'] = $this->request->getPost('descricao');
		$fields['tipoMaterial'] = $this->request->getPost('tipoMaterial');
		$fields['unidade'] = $this->request->getPost('unidade');
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['codAutor'] = session()->codPessoa;


		$this->validation->setRules([
			'codCat' => ['label' => 'CodCat', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'descricao' => ['label' => 'Descrição', 'rules' => 'permit_empty'],
			'tipoMaterial' => ['label' => 'TipoMaterial', 'rules' => 'permit_empty|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->ItensModeloModel->insert($fields)) {

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

		$fields['codItemModelo'] = $this->request->getPost('codItemModelo');
		$fields['codCat'] = $this->request->getPost('codCat');
		$fields['descricao'] = $this->request->getPost('descricao');
		$fields['tipoMaterial'] = $this->request->getPost('tipoMaterial');
		$fields['unidade'] = $this->request->getPost('unidade');
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['codAutor'] = session()->codPessoa;



		$this->validation->setRules([
			'codItemModelo' => ['label' => 'codItemModelo', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'codCat' => ['label' => 'CodCat', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'descricao' => ['label' => 'Descrição', 'rules' => 'permit_empty'],
			'tipoMaterial' => ['label' => 'TipoMaterial', 'rules' => 'permit_empty|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->ItensModeloModel->update($fields['codItemModelo'], $fields)) {

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

		$id = $this->request->getPost('codItemModelo');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->ItensModeloModel->where('codItemModelo', $id)->delete()) {

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
