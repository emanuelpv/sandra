<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\AtendimentosPareceresModel;

class AtendimentosPareceres extends BaseController
{

	protected $AtendimentosPareceresModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->AtendimentosPareceresModel = new AtendimentosPareceresModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('AtendimentosPareceres', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "AtendimentosPareceres"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'atendimentosPareceres',
			'title'     		=> 'Pareceres do Atendimento'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('atendimentosPareceres', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->AtendimentosPareceresModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editatendimentosPareceres(' . $value->codAtendimentoParecer . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeatendimentosPareceres(' . $value->codAtendimentoParecer . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codAtendimentoParecer,
				$value->codAtendimento,
				$value->codStatus,
				$value->conteudoParecer,
				$value->impresso,
				$value->codAutor,
				$value->dataCriacao,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}
	public function getAllPareceres()
	{
		$response = array();

		$data['data'] = array();


		$codAtendimento = $this->request->getPost('codAtendimento');
		$result = $this->AtendimentosPareceresModel->pegaPorCodAtendimento($codAtendimento);
		$x = count($result);
		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editatendimentosPareceres(' . $value->codAtendimentoParecer . ')"><i class="fa fa-edit"></i></button>';
			if ($value->codStatus == 1) {
				$ops .= '	<button type="button" class="btn btn-sm btn-primary"  data-toggle="tooltip" data-placement="top" title="Assinar"  onclick="assinarParecer(' . $value->codAtendimentoParecer . ')"><i class="fa fa-signature"></i></button>';
				$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeatendimentosPareceres(' . $value->codAtendimentoParecer . ')"><i class="fa fa-trash"></i></button>';
			} else {
				$ops .= '	<button type="button" class="btn btn-sm btn-primary"  data-toggle="tooltip" data-placement="top" title="Já Assinado"  onclick="jaAssinado(' . $value->codAtendimentoParecer . ')"><i class="fa fa-signature"></i></button>';
			}
			$ops .= '</div>';



			if (strlen($value->conteudoParecer) >= 100) {

				$conteudoParecer = mb_substr($value->conteudoParecer, 0, 90);
			} else {
				$conteudoParecer = $value->conteudoParecer;
			}

			$descricaoStatus = '<span class="right badge badge-' . $value->corStatusParecer . '">' . $value->descricaoStatus . '</span>';

		
			$assinadoPor = NULL;
			if($value->codAutor !== $value->assinadoPor and $value->assinadoPor!==NULL){
				$assinadoPor = 'Assinado Por: '.$value->nomeExibicaoAssinador;

			}

			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$x,
				strip_tags($conteudoParecer),
				'<div>'.$value->nomeExibicao.'</div><div style="font-size:10px" class="right badge badge-danger">'.$assinadoPor.'</div>',
				date('d/m/Y', strtotime($value->dataCriacao)),
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

		$id = $this->request->getPost('codAtendimentoParecer');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->AtendimentosPareceresModel->pegaPorCodigo($id);


			$response['conteudoParecer'] = $data->conteudoParecer;
			$response['dataCriacao'] = session()->cidade . '-' . session()->uf . ', ' . date('d', strtotime($data->dataCriacao)) . ' de ' . nomeMesPorExtenso(date('m', strtotime($data->dataCriacao))) . ' de ' . date('Y', strtotime($data->dataCriacao)) . '.';
			$response['cpfPaciente'] = 'CPF: ' . $data->cpf;
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

	public function salvaParecer()
	{
		$response = array();
		$parecer = $this->request->getPost('parecer');
		$codAtendimento = $this->request->getPost('codAtendimento');
		$codEspecialidade = $this->request->getPost('codEspecialidade');
		$codAtendimentoParecer = $this->request->getPost('codAtendimentoParecer');



		if ($codAtendimentoParecer == NULL and $codEspecialidade !== NULL) {
			//INSERT
			$fields['codAtendimento'] = $codAtendimento;
			$fields['conteudoParecer'] = $parecer;
			$fields['codStatus'] = 1;
			$fields['codEspecialidade'] = $codEspecialidade;
			$fields['codAutor'] = session()->codPessoa;
			$fields['dataCriacao'] = date('Y-m-d H:i');
			$fields['dataAtualizacao'] = date('Y-m-d H:i');

			if ($codAtendimentoParecer = $this->AtendimentosPareceresModel->insert($fields)) {

				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['codAtendimentoParecer'] = $codAtendimentoParecer;
				$response['messages'] = 'Informação inserida com sucesso';
			}
		} else {
			//UPDATE

			$fields['codAtendimento'] = $codAtendimento;
			$fields['conteudoParecer'] = $parecer;
			$fields['codStatus'] = 1;
			//$fields['codEspecialidade'] = $codAtendimento;
			$fields['codAutor'] = session()->codPessoa;
			$fields['dataAtualizacao'] = date('Y-m-d H:i');

			//NÃO DEIXA ATUALIZAR SE CÓDIGO FOR NULO OU VAZIO
			if ($this->validation->check($codAtendimentoParecer, 'required|numeric')) {
				if ($this->AtendimentosPareceresModel->update($codAtendimentoParecer, $fields)) {

					$response['success'] = true;
					$response['csrf_hash'] = csrf_hash();
					$response['codAtendimentoParecer'] = $codAtendimentoParecer;
					$response['messages'] = 'Parecer atualizada com sucesso';
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
		$response['messages'] = 'Parecer registrado';




		return $this->response->setJSON($response);
	}

	public function add()
	{

		$response = array();

		$fields['codAtendimentoParecer'] = $this->request->getPost('codAtendimentoParecer');
		$fields['codAtendimento'] = $this->request->getPost('codAtendimento');
		$fields['codStatus'] = $this->request->getPost('codStatus');
		$fields['conteudoParecer'] = $this->request->getPost('conteudoParecer');
		$fields['impresso'] = $this->request->getPost('impresso');
		$fields['codAutor'] = $this->request->getPost('codAutor');
		$fields['dataCriacao'] = $this->request->getPost('dataCriacao');


		$this->validation->setRules([
			'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'codStatus' => ['label' => 'Status', 'rules' => 'required|numeric|max_length[11]'],
			'conteudoParecer' => ['label' => 'ConteudoParecer', 'rules' => 'permit_empty'],
			'impresso' => ['label' => 'Impresso', 'rules' => 'required|numeric|max_length[11]'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtendimentosPareceresModel->insert($fields)) {

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
		$codAtendimentoParecer = $this->request->getPost('codAtendimentoParecer');


		$fieldsParecer['codStatus'] = 2;
		$fieldsParecer['assinadoPor'] = session()->codPessoa;
		$fieldsParecer['dataAtualizacao'] = date('Y-m-d H:i');

		if ($this->validation->check($codAtendimentoParecer, 'required|numeric')) {
			if ($this->AtendimentosPareceresModel->update($codAtendimentoParecer, $fieldsParecer)) {
			}
		} else {
			$response['success'] = false;
			$response['messages'] = 'Erro na operação!';
			return $this->response->setJSON($response);
		}

		$response['success'] = true;
		$response['csrf_hash'] = csrf_hash();
		$response['messages'] = 'Parecer assinado com sucesso!';
		return $this->response->setJSON($response);
	}




	public function edit()
	{

		$response = array();

		$fields['codAtendimentoParecer'] = $this->request->getPost('codAtendimentoParecer');
		$fields['codAtendimento'] = $this->request->getPost('codAtendimento');
		$fields['codStatus'] = $this->request->getPost('codStatus');
		$fields['conteudoParecer'] = $this->request->getPost('conteudoParecer');
		$fields['impresso'] = $this->request->getPost('impresso');
		$fields['codAutor'] = $this->request->getPost('codAutor');
		$fields['dataCriacao'] = $this->request->getPost('dataCriacao');


		$this->validation->setRules([
			'codAtendimentoParecer' => ['label' => 'codAtendimentoParecer', 'rules' => 'required|numeric]'],
			'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'codStatus' => ['label' => 'Status', 'rules' => 'required|numeric|max_length[11]'],
			'conteudoParecer' => ['label' => 'ConteudoParecer', 'rules' => 'permit_empty'],
			'impresso' => ['label' => 'Impresso', 'rules' => 'required|numeric|max_length[11]'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtendimentosPareceresModel->update($fields['codAtendimentoParecer'], $fields)) {

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

		$id = $this->request->getPost('codAtendimentoParecer');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->AtendimentosPareceresModel->where('codAtendimentoParecer', $id)->delete()) {

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
