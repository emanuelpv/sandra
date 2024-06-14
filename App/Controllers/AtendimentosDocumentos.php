<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\AtendimentosDocumentosModel;

class atendimentosDocumentos extends BaseController
{

	protected $AtendimentosDocumentosModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->AtendimentosDocumentosModel = new AtendimentosDocumentosModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('atendimentosDocumentos', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "atendimentosDocumentos"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'atendimentosDocumentos',
			'title'     		=> 'Documentos do Atendimento'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('atendimentosDocumentos', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->AtendimentosDocumentosModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editatendimentosDocumentos(' . $value->codAtendimentoDocumento . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeatendimentosDocumentos(' . $value->codAtendimentoDocumento . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codAtendimentoDocumento,
				$value->codAtendimento,
				$value->codStatus,
				$value->conteudoDocumento,
				$value->impresso,
				$value->codAutor,
				$value->dataCriacao,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}
	public function getAllDocumentos()
	{
		$response = array();

		$data['data'] = array();


		$codAtendimento = $this->request->getPost('codAtendimento');
		$result = $this->AtendimentosDocumentosModel->pegaPorCodAtendimento($codAtendimento);
		$x = count($result);
		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editatendimentosDocumentos(' . $value->codAtendimentoDocumento . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-success"  data-toggle="tooltip" data-placement="top" title="Clonar"  onclick="clonarAtendimentoDocumento(' . $value->codAtendimentoDocumento . ')"><i class="fa fa-clone"></i></button>';
			if ($value->codStatus == 1) {
				$ops .= '	<button type="button" class="btn btn-sm btn-primary"  data-toggle="tooltip" data-placement="top" title="Assinar"  onclick="assinarDocumento(' . $value->codAtendimentoDocumento . ')"><i class="fa fa-signature"></i></button>';
				$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeatendimentosDocumentos(' . $value->codAtendimentoDocumento . ')"><i class="fa fa-trash"></i></button>';
			} else {
				$ops .= '	<button type="button" class="btn btn-sm btn-primary"  data-toggle="tooltip" data-placement="top" title="Já Assinado"  onclick="jaAssinado(' . $value->codAtendimentoDocumento . ')"><i class="fa fa-signature"></i></button>';
			}

			$ops .= '</div>';

			if ($value->codTipoDocumento == 0) {
				$tipoDocumento = 'Receituário Comum';
			}
			if ($value->codTipoDocumento == 1) {
				$tipoDocumento = 'Receituário de Controle Especial';
			}

			if ($value->codTipoDocumento == 2) {
				$tipoDocumento = 'Declaração';
			}

			if ($value->codTipoDocumento == 1) {
				$tipoDocumento = 'Atestado';
			}



			if (strlen($value->conteudoDocumento) >= 100) {

				$conteudoDocumento = mb_substr($value->conteudoDocumento, 0, 90);
			} else {
				$conteudoDocumento = $value->conteudoDocumento;
			}

			$descricaoStatus = '<span class="right badge badge-' . $value->corStatusDocumento . '">' . $value->descricaoStatus . '</span>';

			$assinadoPor = NULL;
			if ($value->codAutor !== $value->assinadoPor and $value->assinadoPor !== NULL) {
				$assinadoPor = 'Assinado Por: ' . $value->nomeExibicaoAssinador;
			}

			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$x,
				strip_tags($conteudoDocumento),
				'<div>' . $value->nomeExibicao . '</div><div style="font-size:10px" class="right badge badge-danger">' . $assinadoPor . '</div>',
				date('d/m/Y', strtotime($value->dataCriacao)),
				$tipoDocumento,
				$descricaoStatus,
				$ops,
			);
			$x--;
		}

		return $this->response->setJSON($data);
	}

	public function comparecimento()
	{
		$response = array();

		$codAtendimento = $this->request->getPost('codAtendimento');
		$response['html'] = '';
		if ($this->validation->check($codAtendimento, 'required|numeric')) {
			$data = $this->AtendimentosDocumentosModel->dadosAtendimento($codAtendimento);


			if ($data->siglaCid == NULL or $data->siglaCid == "" or $data->siglaCid == " ") {
				$siglaCid = "______";
			} else {
				$siglaCid = $data->siglaCid;
			}


			$response['html'] = '<div>Declaro para os devidos fins que <b>' .
				$data->nomeCompleto . '</b>, CPF ' . $data->cpf . ', compareceu a este Hospital, dando entrada ' . diaSemanaCompleto($data->dataCriacao) . ', ' . date('d/m/Y', strtotime($data->dataCriacao)) . ' às ' . date('H:i', strtotime($data->dataCriacao)) . '. Permanecendo até ____________________________________.</div>';
			$response['html'] .= '
				<div style="margin-top:60px">CID Nº ' . $siglaCid . '</div>';

			return $this->response->setJSON($response);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function atestadoMedico()
	{
		$response = array();

		$codAtendimento = $this->request->getPost('codAtendimento');
		$response['html'] = '';
		if ($this->validation->check($codAtendimento, 'required|numeric')) {
			$data = $this->AtendimentosDocumentosModel->dadosAtendimento($codAtendimento);

			if ($data->siglaCid == NULL or $data->siglaCid == "" or $data->siglaCid == " ") {
				$siglaCid = "______";
			} else {
				$siglaCid = $data->siglaCid;
			}

			$response['html'] = '<div>Atesto para os devidos fins que <b>' .
				$data->nomeCompleto . '</b>, CPF ' . $data->cpf . ', necessita de ________ dia(s) de afastamento de suas atividades laborais, a partir desta data por motivo de doença</div>';
			$response['html'] .= '
				<div style="margin-top:60px">CID Nº ' . $siglaCid . '</div>';
			return $this->response->setJSON($response);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codAtendimentoDocumento');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->AtendimentosDocumentosModel->pegaPorCodigo($id);

			$response['enderecoPaciente'] = NULL;
			$enderecoPaciente = NULL;
			$nomePaciente = NULL;
			$cpfPaciente = NULL;


			if ($data->codTipoDocumento == 1) {
				$response['tituloDocumento'] = 'RECEITUÁRIO DE CONTROLE ESPECIAL';
				$nomePaciente = '<div>' . 'PACIENTE: ' . $data->nomePaciente . '</div>';
				$cpfPaciente = '<div>' . 'CPF: ' . $data->cpf . '</div>';

				if ($data->endereco == NULL or $data->endereco == "" or $data->endereco == " ") {
					$enderecoPaciente = '<div> ENDEREÇO: _____________________________________</div>';
				} else {
					$enderecoPaciente = '<div>' . 'ENDEREÇO: ' . $data->endereco . '</div>';
				}
				//$enderecoPaciente = NULL;
			}

			if ($data->codTipoDocumento == 0) {
				$response['tituloDocumento'] = 'RECEITUÁRIO';
				$nomePaciente = '<div>' . 'PACIENTE: ' . $data->nomePaciente . '</div>';
				$cpfPaciente = '<div>' . 'CPF: ' . $data->cpf . '</div>';
			}

			if ($data->codTipoDocumento == 2) {
				$response['tituloDocumento'] = 'DECLARAÇÃO DE COMPARECIMENTO';
			}


			if ($data->codTipoDocumento == 3) {
				$response['tituloDocumento'] = 'ATESTADO MÉDICO';
			}


			$response['cpfPaciente'] = $data->cpf;
			$response['nomePaciente'] = $data->nomePaciente;
			$response['codTipoDocumento'] = $data->codTipoDocumento;
			$response['conteudoDocumento'] = $data->conteudoDocumento;
			$response['dataCriacao'] = session()->cidade . '-' . session()->uf . ', ' . date('d', strtotime($data->dataCriacao)) . ' de ' . nomeMesPorExtenso(date('m', strtotime($data->dataCriacao))) . ' de ' . date('Y', strtotime($data->dataCriacao)) . '.';
			$response['codStatus'] = $data->codStatus;
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

			$html = '';

			if ($data->codTipoDocumento == 1) {
				$html = '
				<div style="height: 200mm;" class="row">

					<div class="col-md-6">
					
						<div class="row">
							<div class="col-md-8">
								' . session()->cabecalhoPrescricao . '
							</div>
							<div style="margin-right:2px;font-size:12px" class="col-md-3 border">
								<div>1º VIA - Farmácia</div>
								<div>2º VIA - Paciente</div>
							</div>
						</div>
					
						<div class="row">
							<div style="font-weight: bold; margin-top:10px" class="col-md-12 d-flex justify-content-center">
								' . $response['tituloDocumento'] . '
							</div>
						</div>

						<div style="font-size:14px;margin-top:10px" class="row">
							<div class="col-md-10px">
								' . $nomePaciente . '
								' . $cpfPaciente . '
								' . $enderecoPaciente . '
							</div>
						</div>
						<div style="font-size:14px;margin-top:10px" class="row">
							<div class="col-md-10px">
								<div>' . $response['conteudoDocumento'] . '</div>
							</div>
						</div>

						<div style="margin-top:40px" class="row">
							<div class="col-md-12">
								<div class="text-right">' . $response['dataCriacao'] . '</div>
							</div>
						</div>

						<div style="margin-top:40px" class="row">
							<div class="col-md-12">
								<div style="font-weight: bold;" class="text-center">' . $response['nomeEspecialista'] . '</div>
								<div style="font-weight: bold;" class="text-center">' . $response['numeroConselho'] . '</div>
							</div>
						</div>

						<div  style="margin-top:10px" class="row">
							<div class="col-md-6 border">
								<div style="font-weight: bold; margin-top:10px" class="col-md-12 d-flex justify-content-center">
									IDENTIFICAÇÃO COMPRADOR
								</div>
								<div style="margin-right:2px;font-size:10px; white-space:pre-wrap;" class="col-md-12">
									<span>Nome:_____________________________________
										Ident:________________________ 
										Org. Emissor:_______ Ender:_______________
										 Cidade:________________________ 
										 UF:_____ Fone:____________________</span>
								</div>
							</div>
							<div class="col-md-6 border">
								<div style="font-weight: bold; margin-top:10px" class="col-md-12 d-flex justify-content-center">
									IDENTIFICAÇÃO DO FORNECEDOR
								</div>
								<div style="margin-top:160px;font-size:10px;" class="col-md-12 d-flex justify-content-center">
									Assinatura Farmaceutico/Data
								</div>


							</div>


						</div>

						

						<div style="margin-top:20px" class="row">
							<div class="col-md-12">
								' . session()->rodapePrescricao . '
							</div>
						</div>


					</div>
					<div style="border-left-style:dashed; border-width:thin;" class="col-md-6 ">
						
						<div class="row">
							<div class="col-md-8">
								' . session()->cabecalhoPrescricao . '
							</div>
							<div style="margin-right:2px;font-size:12px" class="col-md-3 border">
								<div>1º VIA - Farmácia</div>
								<div>2º VIA - Paciente</div>
							</div>
						</div>
					
						<div class="row">
							<div style="font-weight: bold; margin-top:10px" class="col-md-12 d-flex justify-content-center">
								' . $response['tituloDocumento'] . '
							</div>
						</div>

						<div style="font-size:14px;margin-top:10px" class="row">
							<div style="margin-left:10px" class="col-md-10px">
								' . $nomePaciente . '
								' . $cpfPaciente . '
								' . $enderecoPaciente . '
							</div>
						</div>
						<div style="font-size:14px;margin-top:10px" class="row">
							<div class="col-md-10px">
								<div style="margin-left:10px">' . $response['conteudoDocumento'] . '</div>
							</div>
						</div>

						<div style="margin-top:40px" class="row">
							<div class="col-md-12">
								<div class="text-right">' . $response['dataCriacao'] . '</div>
							</div>
						</div>

						<div style="margin-top:40px" class="row">
							<div class="col-md-12">
								<div style="font-weight: bold;" class="text-center">' . $response['nomeEspecialista'] . '</div>
								<div style="font-weight: bold;" class="text-center">' . $response['numeroConselho'] . '</div>
							</div>
						</div>

						<div  style="margin-top:10px" class="row">
							<div class="col-md-6 border">
								<div style="font-weight: bold; margin-top:10px" class="col-md-12 d-flex justify-content-center">
									IDENTIFICAÇÃO COMPRADOR
								</div>
								<div style="margin-right:2px;font-size:10px; white-space:pre-wrap;" class="col-md-12">
									<span>Nome:_____________________________________
										Ident:________________________ 
										Org. Emissor:_______ Ender:_______________
										 Cidade:________________________ 
										 UF:_____ Fone:____________________</span>
								</div>
							</div>
							<div class="col-md-6 border">
								<div style="font-weight: bold; margin-top:10px" class="col-md-12 d-flex justify-content-center">
									IDENTIFICAÇÃO DO FORNECEDOR
								</div>
								<div style="margin-top:160px;font-size:10px;" class="col-md-12 d-flex justify-content-center">
									Assinatura Farmaceutico/Data
								</div>


							</div>


						</div>

						<div style="margin-top:20px" class="row">
							<div class="col-md-12">
								' . session()->rodapePrescricao . '
							</div>
						</div>

					</div>
				</div>
				
				';
			} else {
				$html = '
				<div style="height: 200mm;" class="row">

					<div class="col-md-12">
					
						<div class="row">
							<div class="col-md-8">
								' . session()->cabecalhoPrescricao . '
							</div>
						</div>
					
						<div class="row">
							<div style="font-weight: bold; margin-top:30px" class="col-md-12 d-flex justify-content-center">
								' . $response['tituloDocumento'] . '
							</div>
						</div>

						<div style="font-size:14px;margin-top:10px;margin-left:30px;margin-right:30px" class="row">
							<div class="col-md-10px">
							' . $nomePaciente . '
							' . $cpfPaciente . '
							</div>
						</div>
						<div style="font-size:14px;margin-top:30px;margin-left:30px;margin-right:30px" class="row">
							<div class="col-md-10px">
								<div>' . $response['conteudoDocumento'] . '</div>
							</div>
						</div>

						<div style="margin-top:70px;margin-left:30px;margin-right:30px" class="row">
							<div class="col-md-12">
								<div class="text-right">' . $response['dataCriacao'] . '</div>
							</div>
						</div>

						<div style="margin-top:70px" class="row">
							<div class="col-md-12">
								<div style="font-weight: bold;" class="text-center">' . $response['nomeEspecialista'] . '</div>
								<div style="font-weight: bold;" class="text-center">' . $response['numeroConselho'] . '</div>
							</div>
						</div>

						

						<div class="row">
							<div class="col-md-12">
								' . session()->rodapePrescricao . '
							</div>
						</div>

					</div>
				</div>
				
				';
			}
			$response['html'] = $html;


			return $this->response->setJSON($response);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function clonarDocumento()
	{


		$codAtendimentoDocumento = $this->request->getPost('codAtendimentoDocumento');

		if ($this->validation->check($codAtendimentoDocumento, 'required|numeric')) {

			$dadosDocumento = $this->AtendimentosDocumentosModel->pegaPorCodigo($codAtendimentoDocumento);



			$response = array();

			$fields['codAtendimento'] = $dadosDocumento->codAtendimento;
			$fields['codTipoDocumento'] = $dadosDocumento->codTipoDocumento;
			$fields['codStatus'] = 1;
			$fields['conteudoDocumento'] = $dadosDocumento->conteudoDocumento;
			$fields['impresso'] = $dadosDocumento->impresso;
			$fields['codAutor'] = session()->codPessoa;
			$fields['dataCriacao'] = date('Y-m-d H:i');
			if (empty($dadosDocumento)) {

				$response['success'] = false;
				$response['messages'] = 'Nada para clonar!';

				return $this->response->setJSON($response);
			}
		}
		$this->validation->setRules([
			'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'codStatus' => ['label' => 'Status', 'rules' => 'required|numeric|max_length[11]'],
			'conteudoDocumento' => ['label' => 'ConteudoDocumento', 'rules' => 'permit_empty'],
			'impresso' => ['label' => 'Impresso', 'rules' => 'required|numeric|max_length[11]'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],

		]);



		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtendimentosDocumentosModel->insert($fields)) {

				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['messages'] = 'Clonagem realizada com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na clonagem!';
			}
		}

		return $this->response->setJSON($response);
	}
	public function salvaDocumento()
	{
		$response = array();
		$documento = $this->request->getPost('documento');
		$codTipoDocumento = $this->request->getPost('codTipoDocumento');
		$codAtendimento = $this->request->getPost('codAtendimento');
		//$codDocumento = $this->request->getPost('codDocumento');
		$codAtendimentoDocumento = $this->request->getPost('codAtendimentoDocumento');



		if ($codAtendimentoDocumento == NULL and $codTipoDocumento !== NULL) {
			//INSERT
			$fields['codAtendimento'] = $codAtendimento;
			$fields['codTipoDocumento'] = $codTipoDocumento;
			$fields['conteudoDocumento'] = $documento;
			$fields['codStatus'] = 1;
			$fields['codAutor'] = session()->codPessoa;
			$fields['dataCriacao'] = date('Y-m-d H:i');
			$fields['dataAtualizacao'] = date('Y-m-d H:i');

			if ($codAtendimentoDocumento = $this->AtendimentosDocumentosModel->insert($fields)) {

				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['codAtendimentoDocumento'] = $codAtendimentoDocumento;
				$response['messages'] = 'Informação inserida com sucesso';
			}
		} else {
			//UPDATE

			$fields['codAtendimento'] = $codAtendimento;
			$fields['conteudoDocumento'] = $documento;
			$fields['codStatus'] = 1;
			$fields['codTipoDocumento'] = $codTipoDocumento;
			$fields['codAutor'] = session()->codPessoa;
			$fields['dataAtualizacao'] = date('Y-m-d H:i');

			//NÃO DEIXA ATUALIZAR SE CÓDIGO FOR NULO OU VAZIO
			if ($this->validation->check($codAtendimentoDocumento, 'required|numeric')) {
				if ($this->AtendimentosDocumentosModel->update($codAtendimentoDocumento, $fields)) {

					$response['success'] = true;
					$response['csrf_hash'] = csrf_hash();
					$response['codAtendimentoDocumento'] = $codAtendimentoDocumento;
					$response['messages'] = 'Documento atualizada com sucesso';
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
		$response['messages'] = 'Documento registrado';




		return $this->response->setJSON($response);
	}
	public function salvaDocumentoEditada()
	{
		$response = array();
		$documento = $this->request->getPost('documento');
		$codTipoDocumento = $this->request->getPost('codTipoDocumento');
		$codAtendimento = $this->request->getPost('codAtendimento');
		//$codDocumento = $this->request->getPost('codDocumento');
		$codAtendimentoDocumento = $this->request->getPost('codAtendimentoDocumento');


		if ($codAtendimentoDocumento !== NULL) {
			//UPDATE

			$fields['codAtendimento'] = $codAtendimento;
			$fields['conteudoDocumento'] = $documento;
			$fields['codTipoDocumento'] = $codTipoDocumento;
			$fields['codAutor'] = session()->codPessoa;
			$fields['dataAtualizacao'] = date('Y-m-d H:i');

			//NÃO DEIXA ATUALIZAR SE CÓDIGO FOR NULO OU VAZIO
			if ($this->validation->check($codAtendimentoDocumento, 'required|numeric')) {
				if ($this->AtendimentosDocumentosModel->update($codAtendimentoDocumento, $fields)) {

					$response['success'] = true;
					$response['csrf_hash'] = csrf_hash();
					$response['codAtendimentoDocumento'] = $codAtendimentoDocumento;
					$response['messages'] = 'Documento atualizada com sucesso';
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
		$response['messages'] = 'Documento registrado';




		return $this->response->setJSON($response);
	}
	public function add()
	{

		$response = array();

		$fields['codAtendimentoDocumento'] = $this->request->getPost('codAtendimentoDocumento');
		$fields['codAtendimento'] = $this->request->getPost('codAtendimento');
		$fields['codTipoDocumento'] = $this->request->getPost('codTipoDocumento');
		$fields['codStatus'] = $this->request->getPost('codStatus');
		$fields['conteudoDocumento'] = $this->request->getPost('conteudoDocumento');
		$fields['impresso'] = $this->request->getPost('impresso');
		$fields['codAutor'] = $this->request->getPost('codAutor');
		$fields['dataCriacao'] = $this->request->getPost('dataCriacao');


		$this->validation->setRules([
			'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'codStatus' => ['label' => 'Status', 'rules' => 'required|numeric|max_length[11]'],
			'conteudoDocumento' => ['label' => 'ConteudoDocumento', 'rules' => 'permit_empty'],
			'impresso' => ['label' => 'Impresso', 'rules' => 'required|numeric|max_length[11]'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtendimentosDocumentosModel->insert($fields)) {

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
		$codAtendimentoDocumento = $this->request->getPost('codAtendimentoDocumento');


		$fieldsDocumento['codStatus'] = 2;
		$fieldsDocumento['assinadoPor'] = session()->codPessoa;
		$fieldsDocumento['dataAtualizacao'] = date('Y-m-d H:i');

		if ($this->validation->check($codAtendimentoDocumento, 'required|numeric')) {
			if ($this->AtendimentosDocumentosModel->update($codAtendimentoDocumento, $fieldsDocumento)) {
			}
		} else {
			$response['success'] = false;
			$response['messages'] = 'Erro na operação!';
			return $this->response->setJSON($response);
		}

		$response['success'] = true;
		$response['csrf_hash'] = csrf_hash();
		$response['messages'] = 'Documento assinado com sucesso!';
		return $this->response->setJSON($response);
	}




	public function edit()
	{

		$response = array();

		$fields['codAtendimentoDocumento'] = $this->request->getPost('codAtendimentoDocumento');
		$fields['codAtendimento'] = $this->request->getPost('codAtendimento');
		$fields['codStatus'] = $this->request->getPost('codStatus');
		$fields['conteudoDocumento'] = $this->request->getPost('conteudoDocumento');
		$fields['impresso'] = $this->request->getPost('impresso');
		$fields['codAutor'] = $this->request->getPost('codAutor');
		$fields['dataCriacao'] = $this->request->getPost('dataCriacao');


		$this->validation->setRules([
			'codAtendimentoDocumento' => ['label' => 'codAtendimentoDocumento', 'rules' => 'required|numeric]'],
			'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'codStatus' => ['label' => 'Status', 'rules' => 'required|numeric|max_length[11]'],
			'conteudoDocumento' => ['label' => 'ConteudoDocumento', 'rules' => 'permit_empty'],
			'impresso' => ['label' => 'Impresso', 'rules' => 'required|numeric|max_length[11]'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtendimentosDocumentosModel->update($fields['codAtendimentoDocumento'], $fields)) {

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

		$id = $this->request->getPost('codAtendimentoDocumento');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->AtendimentosDocumentosModel->where('codAtendimentoDocumento', $id)->delete()) {

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
