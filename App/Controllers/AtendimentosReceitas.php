<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\AtendimentosReceitasModel;

class AtendimentosReceitas extends BaseController
{

	protected $AtendimentosReceitasModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->AtendimentosReceitasModel = new AtendimentosReceitasModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('AtendimentosReceitas', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "AtendimentosReceitas"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'atendimentosReceitas',
			'title'     		=> 'Receitas do Atendimento'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('atendimentosReceitas', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->AtendimentosReceitasModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editatendimentosReceitas(' . $value->codAtendimentoReceita . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeatendimentosReceitas(' . $value->codAtendimentoReceita . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codAtendimentoReceita,
				$value->codAtendimento,
				$value->codStatus,
				$value->conteudoReceita,
				$value->impresso,
				$value->codAutor,
				$value->dataCriacao,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}
	public function getAllReceitas()
	{
		$response = array();

		$data['data'] = array();


		$codAtendimento = $this->request->getPost('codAtendimento');
		$result = $this->AtendimentosReceitasModel->pegaPorCodAtendimento($codAtendimento);
		$x = count($result);
		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editatendimentosReceitas(' . $value->codAtendimentoReceita . ')"><i class="fa fa-edit"></i></button>';
			if ($value->codStatus == 1) {
				$ops .= '	<button type="button" class="btn btn-sm btn-primary"  data-toggle="tooltip" data-placement="top" title="Assinar"  onclick="assinarReceita(' . $value->codAtendimentoReceita . ')"><i class="fa fa-signature"></i></button>';
				$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeatendimentosReceitas(' . $value->codAtendimentoReceita . ')"><i class="fa fa-trash"></i></button>';
			} else {
				$ops .= '	<button type="button" class="btn btn-sm btn-primary"  data-toggle="tooltip" data-placement="top" title="Já Assinado"  onclick="jaAssinado(' . $value->codAtendimentoReceita . ')"><i class="fa fa-signature"></i></button>';
			}
			$ops .= '</div>';

			if ($value->codTipoReceita == 1) {
				$tipoReceita = 'Controle Especial';
			} else {
				$tipoReceita = 'Comum';
			}


			if (strlen($value->conteudoReceita) >= 100) {

				$conteudoReceita = mb_substr($value->conteudoReceita, 0, 90);
			} else {
				$conteudoReceita = $value->conteudoReceita;
			}

			$descricaoStatus = '<span class="right badge badge-' . $value->corStatusReceita . '">' . $value->descricaoStatus . '</span>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$x,
				strip_tags($conteudoReceita),
				$value->nomeExibicao,
				date('d/m/Y', strtotime($value->dataCriacao)),
				$tipoReceita,
				$descricaoStatus,
				$ops,
			);
			$x--;
		}

		return $this->response->setJSON($data);
	}
	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codAtendimentoReceita');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->AtendimentosReceitasModel->pegaPorCodigo($id);

			if ($data->codTipoReceita == 1) {
				$response['tituloReceita'] = 'RECEITUÁRIO DE CONTROLE ESPECIAL';
				$response['enderecoPaciente'] = 'ENDEREÇO: ' . $data->endereco;
			} else {
				$response['tituloReceita'] = 'RECEITUÁRIO';
				$response['enderecoPaciente'] = NULL;
			}

			$response['codTipoReceita'] = $data->codTipoReceita;
			$response['conteudoReceita'] = $data->conteudoReceita;
			$response['dataCriacao'] = session()->cidade . '-' . session()->uf . ', ' . date('d', strtotime($data->dataCriacao)) . ' de ' . nomeMesPorExtenso(date('m', strtotime($data->dataCriacao))) . ' de ' . date('Y', strtotime($data->dataCriacao)) . '.';
			$response['cpfPaciente'] = NULL; //'CPF: ' . $data->cpf;
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

	public function salvaReceita()
	{
		$response = array();
		$receita = $this->request->getPost('receita');
		$codTipoReceita = $this->request->getPost('codTipoReceita');
		$codAtendimento = $this->request->getPost('codAtendimento');
		//$codReceita = $this->request->getPost('codReceita');
		$codAtendimentoReceita = $this->request->getPost('codAtendimentoReceita');



		if ($codAtendimentoReceita == NULL and $codTipoReceita !== NULL) {
			//INSERT
			$fields['codAtendimento'] = $codAtendimento;
			$fields['codTipoReceita'] = $codTipoReceita;
			$fields['conteudoReceita'] = $receita;
			$fields['codStatus'] = 1;
			$fields['codAutor'] = session()->codPessoa;
			$fields['dataCriacao'] = date('Y-m-d H:i');
			$fields['dataAtualizacao'] = date('Y-m-d H:i');

			if ($codAtendimentoReceita = $this->AtendimentosReceitasModel->insert($fields)) {

				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['codAtendimentoReceita'] = $codAtendimentoReceita;
				$response['messages'] = 'Informação inserida com sucesso';
			}
		} else {
			//UPDATE

			$fields['codAtendimento'] = $codAtendimento;
			$fields['conteudoReceita'] = $receita;
			$fields['codStatus'] = 1;
			$fields['codTipoReceita'] = $codTipoReceita;
			$fields['codAutor'] = session()->codPessoa;
			$fields['dataAtualizacao'] = date('Y-m-d H:i');

			//NÃO DEIXA ATUALIZAR SE CÓDIGO FOR NULO OU VAZIO
			if ($this->validation->check($codAtendimentoReceita, 'required|numeric')) {
				if ($this->AtendimentosReceitasModel->update($codAtendimentoReceita, $fields)) {

					$response['success'] = true;
					$response['csrf_hash'] = csrf_hash();
					$response['codAtendimentoReceita'] = $codAtendimentoReceita;
					$response['messages'] = 'Receita atualizada com sucesso';
					return $this->response->setJSON($response);
				}
			} else {
				$response['success'] = false;
				$response['messages'] = 'Erro na operação!';
				return $this->response->setJSON($response);
			}
		}



		$response['success'] = true;
		$response['csrf_hash'] = csrf_hash();
		$response['messages'] = 'Receita registrado';




		return $this->response->setJSON($response);
	}


	public function salvaReceitaEditada()
	{
		$response = array();
		$receita = $this->request->getPost('receita');
		$codTipoReceita = $this->request->getPost('codTipoReceita');
		$codAtendimento = $this->request->getPost('codAtendimento');
		//$codReceita = $this->request->getPost('codReceita');
		$codAtendimentoReceita = $this->request->getPost('codAtendimentoReceita');


		if ($codAtendimentoReceita !== NULL) {
			//UPDATE

			$fields['codAtendimento'] = $codAtendimento;
			$fields['conteudoReceita'] = $receita;
			$fields['codTipoReceita'] = $codTipoReceita;
			$fields['codAutor'] = session()->codPessoa;
			$fields['dataAtualizacao'] = date('Y-m-d H:i');

			//NÃO DEIXA ATUALIZAR SE CÓDIGO FOR NULO OU VAZIO
			if ($this->validation->check($codAtendimentoReceita, 'required|numeric')) {
				if ($this->AtendimentosReceitasModel->update($codAtendimentoReceita, $fields)) {

					$response['success'] = true;
					$response['csrf_hash'] = csrf_hash();
					$response['codAtendimentoReceita'] = $codAtendimentoReceita;
					$response['messages'] = 'Receita atualizada com sucesso';
					return $this->response->setJSON($response);
				}
			} else {
				$response['success'] = false;
				$response['messages'] = 'Erro na operação!';
				return $this->response->setJSON($response);
			}
		}



		$response['success'] = true;
		$response['csrf_hash'] = csrf_hash();
		$response['messages'] = 'Receita registrado';




		return $this->response->setJSON($response);
	}
	public function add()
	{

		$response = array();

		$fields['codAtendimentoReceita'] = $this->request->getPost('codAtendimentoReceita');
		$fields['codAtendimento'] = $this->request->getPost('codAtendimento');
		$fields['codTipoReceita'] = $this->request->getPost('codTipoReceita');
		$fields['codStatus'] = $this->request->getPost('codStatus');
		$fields['conteudoReceita'] = $this->request->getPost('conteudoReceita');
		$fields['impresso'] = $this->request->getPost('impresso');
		$fields['codAutor'] = $this->request->getPost('codAutor');
		$fields['dataCriacao'] = $this->request->getPost('dataCriacao');


		$this->validation->setRules([
			'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'codStatus' => ['label' => 'Status', 'rules' => 'required|numeric|max_length[11]'],
			'conteudoReceita' => ['label' => 'ConteudoReceita', 'rules' => 'permit_empty'],
			'impresso' => ['label' => 'Impresso', 'rules' => 'required|numeric|max_length[11]'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtendimentosReceitasModel->insert($fields)) {

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


	public function assinatura()
	{
		$response = array();
		$codAtendimentoReceita = $this->request->getPost('codAtendimentoReceita');


		$fieldsReceita['codStatus'] = 2;
		$fieldsReceita['codAutor'] = session()->codPessoa;
		$fieldsReceita['dataAtualizacao'] = date('Y-m-d H:i');

		if ($this->validation->check($codAtendimentoReceita, 'required|numeric')) {
			if ($this->AtendimentosReceitasModel->update($codAtendimentoReceita, $fieldsReceita)) {
			}
		} else {
			$response['success'] = false;
			$response['messages'] = 'Erro na operação!';
			return $this->response->setJSON($response);
		}

		$response['success'] = true;
		$response['csrf_hash'] = csrf_hash();
		$response['messages'] = 'Receita assinado com sucesso!';
		return $this->response->setJSON($response);
	}




	public function edit()
	{

		$response = array();

		$fields['codAtendimentoReceita'] = $this->request->getPost('codAtendimentoReceita');
		$fields['codAtendimento'] = $this->request->getPost('codAtendimento');
		$fields['codStatus'] = $this->request->getPost('codStatus');
		$fields['conteudoReceita'] = $this->request->getPost('conteudoReceita');
		$fields['impresso'] = $this->request->getPost('impresso');
		$fields['codAutor'] = $this->request->getPost('codAutor');
		$fields['dataCriacao'] = $this->request->getPost('dataCriacao');


		$this->validation->setRules([
			'codAtendimentoReceita' => ['label' => 'codAtendimentoReceita', 'rules' => 'required|numeric]'],
			'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'codStatus' => ['label' => 'Status', 'rules' => 'required|numeric|max_length[11]'],
			'conteudoReceita' => ['label' => 'ConteudoReceita', 'rules' => 'permit_empty'],
			'impresso' => ['label' => 'Impresso', 'rules' => 'required|numeric|max_length[11]'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtendimentosReceitasModel->update($fields['codAtendimentoReceita'], $fields)) {

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

		$id = $this->request->getPost('codAtendimentoReceita');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->AtendimentosReceitasModel->where('codAtendimentoReceita', $id)->delete()) {

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
