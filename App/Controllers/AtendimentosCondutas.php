<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\AtendimentosCondutasModel;

class AtendimentosCondutas extends BaseController
{

	protected $AtendimentosCondutasModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->AtendimentosCondutasModel = new AtendimentosCondutasModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('AtendimentosCondutas', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "AtendimentosCondutas"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'atendimentosCondutas',
			'title'     		=> 'Atendimentos Condutas'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('atendimentosCondutas', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->AtendimentosCondutasModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editatendimentosCondutas(' . $value->codAtendimentoConduta . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeatendimentosCondutas(' . $value->codAtendimentoConduta . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$value->codAtendimentoConduta,
				$value->codAtendimento,
				$value->dataInicio,
				$value->dataEncerramento,
				$value->codStatus,
				$value->conteudoConduta,
				$value->impresso,
				$value->codAutor,
				$value->dataCriacao,
				$value->dataAtualizacao,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}


	public function getAllCondutas()
	{
		$response = array();

		$data['data'] = array();


		$codAtendimento = $this->request->getPost('codAtendimento');
		$result = $this->AtendimentosCondutasModel->pegaPorCodAtendimento($codAtendimento);
		$x = count($result);
		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editAtendimentosConduta(' . $value->codAtendimentoConduta . ')"><i class="fa fa-edit"></i></button>';
			if ($value->codStatus == 1) {
				$ops .= '	<button type="button" class="btn btn-sm btn-primary"  data-toggle="tooltip" data-placement="top" title="Assinar"  onclick="assinarConduta(' . $value->codAtendimentoConduta . ')"><i class="fa fa-signature"></i></button>';
				$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeatendimentosConduta(' . $value->codAtendimentoConduta . ')"><i class="fa fa-trash"></i></button>';
			} else {
				$ops .= '	<button type="button" class="btn btn-sm btn-primary"  data-toggle="tooltip" data-placement="top" title="Já Assinado"  onclick="jaAssinado(' . $value->codAtendimentoConduta . ')"><i class="fa fa-signature"></i></button>';
			}

			$ops .= '</div>';


			if (strlen($value->conteudoConduta) >= 100) {

				$conteudoConduta = mb_substr($value->conteudoConduta, 0, 90);
			} else {
				$conteudoConduta = $value->conteudoConduta;
			}

			$descricaoStatus = '<span class="right badge badge-' . $value->corStatusConduta . '">' . $value->descricaoStatus . '</span>';

			$assinadoPor = NULL;
			if($value->codAutor !== $value->assinadoPor and $value->assinadoPor!==NULL){
				$assinadoPor = 'Assinado Por: '.$value->nomeExibicaoAssinador;

			}

			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$x,
				strip_tags($conteudoConduta),
				'<div>'.$value->nomeExibicao.'</div><div style="font-size:10px" class="right badge badge-danger">'.$assinadoPor.'</div>',
				date('d/m/Y H:i', strtotime($value->dataCriacao)),
				$descricaoStatus,
				$ops,
			);
			$x--;
		}

		return $this->response->setJSON($data);
	}


	public function editarConduta()
	{
		$response = array();
		$conduta = $this->request->getPost('conduta');
		$codAtendimentoConduta = $this->request->getPost('codAtendimentoConduta');


		//$verificaExistencia = $this->AtendimentosPrescricoesModel->pegaPorCodigo($codAtendimento);

		if ($codAtendimentoConduta !== NULL and $codAtendimentoConduta !== "" and $codAtendimentoConduta !== " ") {
			//UPDATE

			$fields['conteudoConduta'] = $conduta;
			$fields['codAutor'] = session()->codPessoa;
			$fields['dataAtualizacao'] = date('Y-m-d H:i');

			if ($this->AtendimentosCondutasModel->update($codAtendimentoConduta, $fields)) {

				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['codAtendimentoConduta'] = $codAtendimentoConduta;
				$response['messages'] = 'Salvo com sucesso';
			} else {
				$response['success'] = true;
				$response['messages'] = 'Falha na atualização';
			}
		} else {
			$response['success'] = true;
			$response['messages'] = 'Falha na atualização';
		}

		return $this->response->setJSON($response);
	}
	public function salvaConduta()
	{
		$response = array();
		$conduta = $this->request->getPost('conduta');
		$codAtendimento = $this->request->getPost('codAtendimento');
		$codAtendimentoConduta = $this->request->getPost('codAtendimentoConduta');


		//$verificaExistencia = $this->AtendimentosPrescricoesModel->pegaPorCodigo($codAtendimento);

		if ($codAtendimentoConduta == NULL) {
			//INSERT
			$fields['codAtendimento'] = $codAtendimento;
			$fields['conteudoConduta'] = $conduta;
			$fields['codStatus'] = 1;
			$fields['codAutor'] = session()->codPessoa;
			$fields['dataInicio'] = date('Y-m-d H:i');
			//$fields['dataEncerramento'] = date('Y-m-d H:i');
			$fields['dataCriacao'] = date('Y-m-d H:i');
			$fields['dataAtualizacao'] = date('Y-m-d H:i');

			if ($codAtendimentoConduta = $this->AtendimentosCondutasModel->insert($fields)) {

				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['codAtendimentoConduta'] = $codAtendimentoConduta;

				$response['messages'] = 'Conduta Registrada com sucesso';
			}
		} else {
			//UPDATE

			$fields['codAtendimento'] = $codAtendimento;
			$fields['conteudoConduta'] = $conduta;
			$fields['codStatus'] = 1;
			$fields['codAutor'] = session()->codPessoa;
			$fields['dataInicio'] = date('Y-m-d H:i');
			$fields['dataEncerramento'] = date('Y-m-d H:i');
			$fields['dataAtualizacao'] = date('Y-m-d H:i');


			//NÃO DEIXA ATUALIZAR SE CÓDIGO FOR NULO OU VAZIO
			if ($this->validation->check($codAtendimentoConduta, 'required|numeric')) {
				if ($this->AtendimentosCondutasModel->update($codAtendimentoConduta, $fields)) {

					$response['success'] = true;
					$response['csrf_hash'] = csrf_hash();
					$response['codAtendimentoConduta'] = $codAtendimentoConduta;
					$response['messages'] = 'Atualizado com sucesso';
				}
			} else {
				$response['success'] = false;
				$response['messages'] = 'Erro na operação!';
				return $this->response->setJSON($response);
			}
		}



		$response['success'] = true;
		$response['csrf_hash'] = csrf_hash();


		return $this->response->setJSON($response);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codAtendimentoConduta');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->AtendimentosCondutasModel->pegaPorCodigo($id);

			$response['conteudoConduta'] = $data->conteudoConduta;
			$response['dataCriacao'] = session()->cidade . '-' . session()->uf . ', ' . date('d', strtotime($data->dataCriacao)) . ' de ' . nomeMesPorExtenso(date('m', strtotime($data->dataCriacao))) . ' de ' . date('Y', strtotime($data->dataCriacao)) . '.';
			$response['codStatus'] = $data->codStatus;
			$response['cpfPaciente'] = 'CPF: ' . $data->cpf;
			$response['nomePaciente'] = 'PACIENTE: ' . $data->nomePaciente;
			$response['nomeEspecialista'] = $data->nomeEspecialista . ' - ' . $data->siglaCargo;
			if ($data->nomeConselho !== NULL and $data->numeroInscricao !== NULL and $data->siglaEstadoFederacao !== NULL) {
				$response['numeroConselho'] = $data->nomeConselho . ' ' . $data->numeroInscricao . '/' . $data->siglaEstadoFederacao;
			} else {
				$response['numeroConselho'] = null;
			}

			if (date('Y-m-d') <= date('Y-m-d', strtotime($data->dataCriacao))) {
				$response['editavel'] = 1;
			} else {
				$response['editavel'] = 0;
			}

			return $this->response->setJSON($response);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codAtendimentoConduta'] = $this->request->getPost('codAtendimentoConduta');
		$fields['codAtendimento'] = $this->request->getPost('codAtendimento');
		$fields['dataInicio'] = $this->request->getPost('dataInicio');
		$fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');
		$fields['codStatus'] = $this->request->getPost('codStatus');
		$fields['conteudoConduta'] = $this->request->getPost('conteudoConduta');
		$fields['impresso'] = $this->request->getPost('impresso');
		$fields['codAutor'] = $this->request->getPost('codAutor');
		$fields['dataCriacao'] = $this->request->getPost('dataCriacao');
		$fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');


		$this->validation->setRules([
			'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'dataInicio' => ['label' => 'DataInicio', 'rules' => 'permit_empty|valid_date'],
			'dataEncerramento' => ['label' => 'DataEncerramento', 'rules' => 'permit_empty|valid_date'],
			'codStatus' => ['label' => 'CodStatus', 'rules' => 'required|max_length[11]'],
			'conteudoConduta' => ['label' => 'ConteudoConduta', 'rules' => 'permit_empty'],
			'impresso' => ['label' => 'Impresso', 'rules' => 'required|numeric|max_length[11]'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtendimentosCondutasModel->insert($fields)) {

				$response['success'] = true;
				$response['messages'] = 'Informação inserida com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na inserção!';
			}
		}

		return $this->response->setJSON($response);
	}


	public function assinatura()
	{
		$response = array();
		$codAtendimentoConduta = $this->request->getPost('codAtendimentoConduta');


		$fieldsConduta['codStatus'] = 2;
		$fieldsConduta['assinadoPor'] = session()->codPessoa;
		$fieldsConduta['dataAtualizacao'] = date('Y-m-d H:i');

		if ($this->validation->check($codAtendimentoConduta, 'required|numeric')) {
			if ($this->AtendimentosCondutasModel->update($codAtendimentoConduta, $fieldsConduta)) {
			}
		} else {
			$response['success'] = false;
			$response['messages'] = 'Erro na operação!';
			return $this->response->setJSON($response);
		}

		$response['success'] = true;
		$response['csrf_hash'] = csrf_hash();
		$response['messages'] = 'Conduta assinada com sucesso!';
		return $this->response->setJSON($response);
	}

	public function edit()
	{

		$response = array();

		$fields['codAtendimentoConduta'] = $this->request->getPost('codAtendimentoConduta');
		$fields['codAtendimento'] = $this->request->getPost('codAtendimento');
		$fields['dataInicio'] = $this->request->getPost('dataInicio');
		$fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');
		$fields['codStatus'] = $this->request->getPost('codStatus');
		$fields['conteudoConduta'] = $this->request->getPost('conteudoConduta');
		$fields['impresso'] = $this->request->getPost('impresso');
		$fields['codAutor'] = $this->request->getPost('codAutor');
		$fields['dataCriacao'] = $this->request->getPost('dataCriacao');
		$fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');


		$this->validation->setRules([
			'codAtendimentoConduta' => ['label' => 'codAtendimentoConduta', 'rules' => 'required|numeric'],
			'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'dataInicio' => ['label' => 'DataInicio', 'rules' => 'permit_empty|valid_date'],
			'dataEncerramento' => ['label' => 'DataEncerramento', 'rules' => 'permit_empty|valid_date'],
			'codStatus' => ['label' => 'CodStatus', 'rules' => 'required|max_length[11]'],
			'conteudoConduta' => ['label' => 'ConteudoConduta', 'rules' => 'permit_empty'],
			'impresso' => ['label' => 'Impresso', 'rules' => 'required|numeric|max_length[11]'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtendimentosCondutasModel->update($fields['codAtendimentoConduta'], $fields)) {

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

		$id = $this->request->getPost('codAtendimentoConduta');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->AtendimentosCondutasModel->where('codAtendimentoConduta', $id)->delete()) {

				$response['success'] = true;
				$response['messages'] = 'Conduta removida com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na deleção!';
			}
		}

		return $this->response->setJSON($response);
	}
}
