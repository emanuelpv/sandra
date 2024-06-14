<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\AtendimentosEvolucoesModel;

class AtendimentosEvolucoes extends BaseController
{

	protected $AtendimentosEvolucoesModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->AtendimentosEvolucoesModel = new AtendimentosEvolucoesModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('AtendimentosEvolucoes', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "AtendimentosEvolucoes"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'atendimentosEvolucoes',
			'title'     		=> 'Evoluções do Atendimento'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('atendimentosEvolucoes', $data);
	}


	public function getAllEvolucoes()
	{
		$response = array();

		$data['data'] = array();
		$codAtendimento = $this->request->getPost('codAtendimento');
		$result = $this->AtendimentosEvolucoesModel->pegaPorCodAtendimento($codAtendimento);
		$x = count($result);
		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editAtendimentosEvolucao(' . $value->codAtendimentoEvolucao . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-secondary"  data-toggle="tooltip" data-placement="top" title="Ver"  onclick="verEvolucao(' . $value->codAtendimentoEvolucao . ')"><i class="fa fa-print"></i></button>';

			if ($value->codStatus == 1) {
				$ops .= '	<button type="button" class="btn btn-sm btn-primary"  data-toggle="tooltip" data-placement="top" title="Assinar"  onclick="assinarEvolucao(' . $value->codAtendimentoEvolucao . ')"><i class="fa fa-signature"></i></button>';
				$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeatendimentosEvolucao(' . $value->codAtendimentoEvolucao . ')"><i class="fa fa-trash"></i></button>';
			} else {
				$ops .= '	<button type="button" class="btn btn-sm btn-primary"  data-toggle="tooltip" data-placement="top" title="Já Assinado"  onclick="jaAssinado(' . $value->codAtendimentoEvolucao . ')"><i class="fa fa-signature"></i></button>';
			}

			$ops .= '	<button type="button" class="btn btn-sm btn-success"  data-toggle="tooltip" data-placement="top" title="Clonar"  onclick="clonarAtendimentoEvolucao(' . $value->codAtendimentoEvolucao . ')"><i class="fa fa-clone"></i></button>';
			$ops .= '</div>';

			$descricaoStatus = '<span class="right badge badge-' . $value->corStatusEvolucao . '">' . $value->descricaoStatus . '</span>';


			$assinadoPor = NULL;
			if ($value->codAutor !== $value->assinadoPor and $value->assinadoPor !== NULL) {
				$assinadoPor = 'Assinado Por: ' . $value->nomeExibicaoAssinador;
			}

			$data['data'][$key] = array(
				$x,
				mb_substr(strip_tags($value->conteudoEvolucao), 0, 100) . '...',
				$value->descricaoTipoEvolucao,
				'<div>'.$value->nomeExibicao.'</div><div style="font-size:10px" class="right badge badge-danger">'.$assinadoPor.'</div>',
				date('d/m/Y H:i', strtotime($value->dataCriacao)),
				$descricaoStatus,
				$ops,
			);
			$x--;
		}

		return $this->response->setJSON($data);
	}


	public function listaDropDownTiposEvolucao()
	{

		$result = $this->AtendimentosEvolucoesModel->listaDropDownTiposEvolucao();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function editarEvolucao()
	{
		$response = array();
		$evolucao = $this->request->getPost('evolucao');
		$codTipoEvolucao = $this->request->getPost('codTipoEvolucao');
		$codAtendimentoEvolucao = $this->request->getPost('codAtendimentoEvolucao');


		//$verificaExistencia = $this->AtendimentosPrescricoesModel->pegaPorCodigo($codAtendimento);

		if ($this->validation->check($codAtendimentoEvolucao, 'required|numeric')) {
			//UPDATE

			$fields['codTipoEvolucao'] = $codTipoEvolucao;
			$fields['conteudoEvolucao'] = $evolucao;
			$fields['codAutor'] = session()->codPessoa;
			$fields['dataAtualizacao'] = date('Y-m-d H:i');

			if ($this->AtendimentosEvolucoesModel->update($codAtendimentoEvolucao, $fields)) {

				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['codAtendimentoEvolucao'] = $codAtendimentoEvolucao;
				$response['messages'] = 'Atualizado com sucesso';
			} else {
				$response['success'] = true;
				$response['messages'] = 'Falha na atualização';
			}
		} else {
			$response['success'] = false;
			$response['messages'] = 'Erro na operação!';
			return $this->response->setJSON($response);
		}

		return $this->response->setJSON($response);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->AtendimentosEvolucoesModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editatendimentosEvolucoes(' . $value->codAtendimentoEvolucao . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeatendimentosEvolucoes(' . $value->codAtendimentoEvolucao . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$value->codAtendimentoEvolucao,
				$value->codAtendimento,
				$value->dataInicio,
				$value->dataEncerramento,
				$value->codStatus,
				$value->conteudoEvolucao,
				$value->impresso,
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

		$id = $this->request->getPost('codAtendimentoEvolucao');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->AtendimentosEvolucoesModel->pegaPorCodigo($id);

			$response['codAtendimento'] = $data->codAtendimento;
			$response['conteudoEvolucao'] = $data->conteudoEvolucao;
			$response['codTipoEvolucao'] = $data->codTipoEvolucao;
			$response['datHora'] = date('d/m/Y H:i', strtotime($data->dataCriacao));
			$response['localAtendimento'] = $data->abreviacaoDepartamento . ' (' . $data->descricaoLocalAtendimento . ')';
			$response['descricaoTipoEvolucao'] = $data->descricaoTipoEvolucao;
			$response['dataCriacao'] = session()->cidade . '-' . session()->uf . ', ' . date('d', strtotime($data->dataCriacao)) . ' de ' . nomeMesPorExtenso(date('m', strtotime($data->dataCriacao))) . ' de ' . date('Y', strtotime($data->dataCriacao)) . '.';
			$response['cpfPaciente'] = 'Nº PLANO: ' . $data->codPlano . ' | ' . $data->siglaTipoBeneficiario . ' | CPF: ' . $data->cpf;
			$response['codStatus'] = $data->codStatus;
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



	public function addEvolucao()
	{
		$response = array();
		$codTipoEvolucao = $this->request->getPost('codTipoEvolucao');
		$evolucao = $this->request->getPost('evolucao');
		$codAtendimento = $this->request->getPost('codAtendimento');
		$codAtendimentoEvolucao = $this->request->getPost('codAtendimentoEvolucao');
		$codLocalAtendimento = $this->request->getPost('codLocalAtendimento');


		//$verificaExistencia = $this->AtendimentosPrescricoesModel->pegaPorCodigo($codAtendimento);

		if ($codAtendimentoEvolucao == NULL) {
			//INSERT
			$fields['codAtendimento'] = $codAtendimento;
			$fields['codTipoEvolucao'] = $codTipoEvolucao;
			$fields['codLocalAtendimento'] = $codLocalAtendimento;
			$fields['conteudoEvolucao'] = $evolucao;
			$fields['codStatus'] = 1;
			$fields['codAutor'] = session()->codPessoa;
			$fields['dataInicio'] = date('Y-m-d H:i');
			//$fields['dataEncerramento'] = date('Y-m-d H:i');
			$fields['dataCriacao'] = date('Y-m-d H:i');
			$fields['dataAtualizacao'] = date('Y-m-d H:i');

			if ($codAtendimentoEvolucao = $this->AtendimentosEvolucoesModel->insert($fields)) {

				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['codAtendimentoEvolucao'] = $codAtendimentoEvolucao;

				$response['messages'] = 'Evolucao Registrada com sucesso';
			}
		} else {
			//UPDATE

			$fields['codAtendimento'] = $codAtendimento;
			$fields['conteudoEvolucao'] = $evolucao;
			$fields['codStatus'] = 1;
			$fields['codAutor'] = session()->codPessoa;
			$fields['dataInicio'] = date('Y-m-d H:i');
			$fields['dataEncerramento'] = date('Y-m-d H:i');
			$fields['dataAtualizacao'] = date('Y-m-d H:i');

			//NÃO DEIXA ATUALIZAR SE CÓDIGO FOR NULO OU VAZIO
			if ($this->validation->check($codAtendimentoEvolucao, 'required|numeric')) {

				if ($this->AtendimentosEvolucoesModel->update($codAtendimentoEvolucao, $fields)) {

					$response['success'] = true;
					$response['csrf_hash'] = csrf_hash();
					$response['codAtendimentoEvolucao'] = $codAtendimentoEvolucao;
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

	public function add()
	{

		$response = array();

		$fields['codAtendimentoEvolucao'] = $this->request->getPost('codAtendimentoEvolucao');
		$fields['codAtendimento'] = $this->request->getPost('codAtendimento');
		$fields['dataInicio'] = $this->request->getPost('dataInicio');
		$fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');
		$fields['codStatus'] = $this->request->getPost('codStatus');
		$fields['conteudoEvolucao'] = $this->request->getPost('conteudoEvolucao');
		$fields['impresso'] = $this->request->getPost('impresso');
		$fields['codAutor'] = $this->request->getPost('codAutor');
		$fields['dataCriacao'] = $this->request->getPost('dataCriacao');
		$fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');


		$this->validation->setRules([
			'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'dataInicio' => ['label' => 'DataInicio', 'rules' => 'permit_empty|valid_date'],
			'dataEncerramento' => ['label' => 'DataEncerramento', 'rules' => 'permit_empty|valid_date'],
			'codStatus' => ['label' => 'CodStatus', 'rules' => 'required|numeric|max_length[11]'],
			'conteudoEvolucao' => ['label' => 'ConteudoEvolucao', 'rules' => 'permit_empty'],
			'impresso' => ['label' => 'Impresso', 'rules' => 'required|numeric|max_length[11]'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtendimentosEvolucoesModel->insert($fields)) {

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
		$codAtendimentoEvolucao = $this->request->getPost('codAtendimentoEvolucao');


		$evolucao = $this->AtendimentosEvolucoesModel->pegaEvolucao($codAtendimentoEvolucao);


		if ($evolucao->codAutor !== session()->codPessoa) {

			$response['success'] = false;
			$response['messages'] = 'Apenas o autor da evolução pode assinar!';
			return $this->response->setJSON($response);
		}

		$fieldsEvolucao['codStatus'] = 2;
		$fieldsEvolucao['assinadoPor'] = session()->codPessoa;
		$fieldsEvolucao['dataAtualizacao'] = date('Y-m-d H:i');

		if ($this->validation->check($codAtendimentoEvolucao, 'required|numeric')) {
			if ($this->AtendimentosEvolucoesModel->update($codAtendimentoEvolucao, $fieldsEvolucao)) {
			}
		} else {

			$response['success'] = false;
			$response['messages'] = 'Erro na operação!';
			return $this->response->setJSON($response);
		}

		$response['success'] = true;
		$response['csrf_hash'] = csrf_hash();
		$response['messages'] = 'Evolução assinada com sucesso!';
		return $this->response->setJSON($response);
	}


	public function edit()
	{

		$response = array();

		$fields['codAtendimentoEvolucao'] = $this->request->getPost('codAtendimentoEvolucao');
		$fields['codAtendimento'] = $this->request->getPost('codAtendimento');
		$fields['dataInicio'] = $this->request->getPost('dataInicio');
		$fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');
		$fields['codStatus'] = $this->request->getPost('codStatus');
		$fields['conteudoEvolucao'] = $this->request->getPost('conteudoEvolucao');
		$fields['impresso'] = $this->request->getPost('impresso');
		$fields['codAutor'] = $this->request->getPost('codAutor');
		$fields['dataCriacao'] = $this->request->getPost('dataCriacao');
		$fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');


		$this->validation->setRules([
			'codAtendimentoEvolucao' => ['label' => 'codAtendimentoEvolucao', 'rules' => 'required|numeric'],
			'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'dataInicio' => ['label' => 'DataInicio', 'rules' => 'permit_empty|valid_date'],
			'dataEncerramento' => ['label' => 'DataEncerramento', 'rules' => 'permit_empty|valid_date'],
			'codStatus' => ['label' => 'CodStatus', 'rules' => 'required|numeric|max_length[11]'],
			'conteudoEvolucao' => ['label' => 'ConteudoEvolucao', 'rules' => 'permit_empty'],
			'impresso' => ['label' => 'Impresso', 'rules' => 'required|numeric|max_length[11]'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtendimentosEvolucoesModel->update($fields['codAtendimentoEvolucao'], $fields)) {

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

		$id = $this->request->getPost('codAtendimentoEvolucao');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->AtendimentosEvolucoesModel->where('codAtendimentoEvolucao', $id)->delete()) {

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
