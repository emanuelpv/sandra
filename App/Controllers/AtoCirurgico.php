<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\AtoCirurgicoModel;
use App\Models\AtendimentosModel;

class AtoCirurgico extends BaseController
{

	protected $AtoCirurgicoModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $Organizacao;
	protected $codOrganizacao;
	protected $validation;
	protected $AtendimentosModel;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->AtoCirurgicoModel = new AtoCirurgicoModel();
		$this->AtendimentosModel = new AtendimentosModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('AtoCirurgico', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "AtoCirurgico"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'atoCirurgico',
			'title'     		=> 'Ato Cirúrgico'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('atoCirurgico', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->AtoCirurgicoModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editatoCirurgico(' . $value->codAtoCirurgico . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeatoCirurgico(' . $value->codAtoCirurgico . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$value->codAtoCirurgico,
				$value->codAtendimento,
				$value->dataCriacao,
				$value->dataAtualizacao,
				$value->codAutor,
				$value->codStatus,
				$value->codTipoAtoCirurgico,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}


	public function listaDropDownTiposAnestesia()
	{

		$result = $this->AtoCirurgicoModel->listaDropDownTiposAnestesia();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}
	public function getAllDocumentosCirurgicos()
	{
		$response = array();

		$data['data'] = array();
		$codAtendimento = $this->request->getPost('codAtendimento');

		$result = $this->AtoCirurgicoModel->pegaPorCodAtendmento($codAtendimento);

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-primary"  data-toggle="tooltip" data-placement="top" title="Ver"  onclick="editatoCirurgico(' . $value->codAtoCirurgico . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-secondary"  data-toggle="tooltip" data-placement="top" title="Imprimir"  onclick="imprimiratoCirurgico(' . $value->codAtoCirurgico . ')"><i class="fa fa-print"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Assinar"  onclick="assinaratoCirurgico(' . $value->codAtoCirurgico . ')"><i class="fa fa-signature"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeatoCirurgico(' . $value->codAtoCirurgico . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$descricaoStatus = '<span class="right badge badge-' . $value->corStatus . '">' . $value->descricaoStatus . '</span>';

			$assinadoPor = NULL;
			if ($value->codAutor !== $value->assinadoPor and $value->assinadoPor !== NULL) {
				$assinadoPor = 'Assinado Por: ' . $value->nomeExibicaoAssinador;
			}

			if ($value->codTipoAtoCirurgico == 1) {
				$codTipoAtoCirurgico = 'Descrição Cirúrgica';
			} else {
				$codTipoAtoCirurgico = 'Descrição de Anestesista';
			}
			if ($value->resumoProcedimento == NULL) {
				$resumoProcedimento = '<span class="right badge badge-danger">Descrição incompleta</span>';
			} else {
				$resumoProcedimento = $value->resumoProcedimento;
			}


			$data['data'][$key] = array(
				$value->codAtoCirurgico,
				$resumoProcedimento,
				$codTipoAtoCirurgico,
				date('d/m/Y H:i', strtotime($value->dataCriacao)),
				'<div>' . $value->nomeExibicao . '</div><div style="font-size:10px" class="right badge badge-danger">' . $assinadoPor . '</div>',
				$descricaoStatus,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codAtoCirurgico');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->AtoCirurgicoModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function dadosPacienteAtoCirurgico()
	{

		$codAtoCirurgico = $this->request->getPost('codAtoCirurgico');
		$data = $this->AtoCirurgicoModel->dadosPacienteAtoCirurgico($codAtoCirurgico);

		$html = '';

		$response = array();
		$response['success'] = true;
		$html .= '
		<div class="row">
			<div class="col-md-4">
				<b>NOME DO PACIENTE:</b> ' . $data->nomeExibicao . '
			</div>
			<div class="col-md-2">
			<b>IDADE:</b> ' . $data->idade . '
			</div>
			<div class="col-md-3">
			<b>Nº CPF:</b> ' . $data->cpf . '
			</div>
			<div class="col-md-3">
			<b>Nº Prontuário:</b> ' . $data->codProntuario . '
			</div>
		</div>';


		$response['html'] = $html;

		return $this->response->setJSON($response);
	}


	public function imprimiratoCirurgico()
	{

		$codAtoCirurgico = $this->request->getPost('codAtoCirurgico');
		$data = $this->AtoCirurgicoModel->dadosPacienteAtoCirurgico($codAtoCirurgico);
		$membros = $this->AtoCirurgicoModel->dadosMembrosAtoCirurgico($codAtoCirurgico);
		$procedimentosCBHPM = $this->AtoCirurgicoModel->procedimentosCBHPM($codAtoCirurgico);
		$procedimentosMatMedOPME = $this->AtoCirurgicoModel->procedimentosMatMedOPME($codAtoCirurgico);


		$htmlMembros = '';

		$x = 0;
		foreach ($membros as $membro) {
			if ($x == 0) {
				$margem = '10px';
			} else {
				$margem = '10px';
			}
			$htmlMembros .= '
			<span style="margin-left:' . $margem . '">
				<b> ' . mb_strtoupper($membro->descricaoFuncao, 'utf8') . ':</b> ' . $membro->nomeMembro . ', ' . $membro->conselhoMembro . ' ' . $membro->inscricaoMembro . '/' . $membro->siglaEstadoFederacao . '
			</span>
			';
			$x++;
		}




		$html = '';

		$response = array();
		$response['success'] = true;
		$html .= '
		<div style="background:#cbd9e7;color:#000;font-weight: bold;" class="row border  d-flex justify-content-center">DADOS DO PACIENTE
		</div>
		<div class="row border">
			<span style="margin-left:10px">
				<b>NOME:</b> ' . $data->nomeExibicao . '
			</span>
			<span style="margin-left:10px">
			<b> IDADE:</b> ' . $data->idade . '
			</span>
			<span  style="margin-left:10px">
			<b>Nº CPF:</b> ' . $data->cpf . '
			</span>
			<span style="margin-left:10px" >
			<b>Nº Prontuário:</b> ' . $data->codProntuario . '
			</span>
			<span style="margin-left:10px">
				<b>Beneficiário:</b> ' . $data->codPlano . '
			</span>
		</div>
		';


		//MEMBROS

		$html .= '
		<div style="background:#cbd9e7;color:#000;font-weight: bold;" class="row border  d-flex justify-content-center">DADOS DA EQUIPE
		</div>
			<div class="row border">
				' . $htmlMembros . '
			</div>
		</div>
		';

		//PROCEDIMENTO DESCRITIVO

		//HORA INICIO E FIM DO(S) PROCEDIMENTO(S)
		if (!empty($procedimentosCBHPM)) {

			$dataInicio = null;
			$dataEncerramento = null;
			$duracao = null;

			foreach ($procedimentosCBHPM as $procedimentoCBHPM) {

				if ($dataInicio == null) {
					$dataInicio = $procedimentoCBHPM->dataInicio;
				}
				if (strtotime($procedimentoCBHPM->dataInicio) < strtotime($dataInicio)) {
					$dataInicio = $procedimentoCBHPM->dataInicio;
				};
				if ($dataEncerramento == null) {
					$dataEncerramento = $procedimentoCBHPM->dataEncerramento;
				}
				if (strtotime($procedimentoCBHPM->dataEncerramento) > strtotime($dataEncerramento)) {
					$dataEncerramento = $procedimentoCBHPM->dataEncerramento;
				};
			}
			$duracao = intervaloTempoAtendimento($dataInicio, $dataEncerramento);
			$dataInicio = date('d/m/Y H:i', strtotime($dataInicio));
			$dataEncerramento = date('d/m/Y H:i', strtotime($dataEncerramento));
			$tempo = '
			<div style="margin-top:5px" class="col-md-12">			
				<span><b>Início:</b> ' . $dataInicio . '</span>
				<span><b>Encerramento:</b> ' . $dataEncerramento . '</span>
				<span><b>Duração:</b> ' . $duracao . '</span>	
			</div>
			';
		}

		$html .= '
		<div style="background:#cbd9e7;color:#000;font-weight: bold;" class="row border  d-flex justify-content-center">DADOS DO PROCEDIMENTO
		</div>
		<div class="row border">

			<div style="margin-top:5px" class="col-md-12">			
				<b>Procedimento:</b> ' . $data->resumoProcedimento . '			
			</div>
			
			' . $tempo . '
		
			<div style="margin-top:5px" class="col-md-12">			
				<b>Tipo de Anestesia:</b> ' . $data->descricaoTipoAnestesia . '			
			</div>

			<div style="margin-top:5px" class="col-md-12">			
				<b>Informações Clínicas Pré-Operatório:</b> ' . $data->preOperatorio . '			
			</div>

			<div style="margin-top:5px" class="col-md-12">			
				<b>Intercorrências:</b> ' . $data->intercorrencias . '			
			</div>

			<div style="margin-top:5px" class="col-md-12">			
				<b>Informações Clínicas Pós-Operatório:</b> ' . $data->posOperatorio . '			
			</div>

			<div style="margin-top:5px" class="col-md-12">			
				<b>Descrição:</b> ' . $data->descricaoAto . '			
			</div>
			
		</div>';


		//PROCEDIMENTOS CBHPM

		$htmlProcedimentosCBHPM = '';

		$htmlProcedimentosCBHPM .= '
		<div style="background:#cbd9e7;color:#000;font-weight: bold;" class="row border  d-flex justify-content-center">PROCEDIMENTO CBHPM
		</div>
		<div class="row border">		
			<div class="col-md-2 border  d-flex justify-content-center">Código</div>
			<div class="col-md-6 border">Procedimento</div>
			<div class="col-md-1 border  d-flex justify-content-center">Qtde</div>
			<div class="col-md-1 border  d-flex justify-content-center">Custo</div>
			<div class="col-md-2 border  d-flex justify-content-center">Tabela</div>				
		';

		$totalProcedimentosCBHPM = count($procedimentosCBHPM);

		foreach ($procedimentosCBHPM as $key => $procedimentoCBHPM) {

			if ($key === array_key_first($procedimentosCBHPM)) {
				$dataInicio = $procedimentosCBHPM[$key]->dataInicio;
			}
			if ($key === array_key_last($procedimentosCBHPM)) {
				$dataEncerramento = $procedimentosCBHPM[$key]->dataEncerramento;
			}

			$htmlProcedimentosCBHPM .= '
			
			<div class="col-md-2 border  d-flex justify-content-center">' . $procedimentoCBHPM->referencia . '</div>
			<div class="col-md-6 border">' . $procedimentoCBHPM->descricaoProcedimento . '</div>
			<div class="col-md-1 border  d-flex justify-content-center">' . $procedimentoCBHPM->qtde . '</div>
			<div class="col-md-1 border  d-flex justify-content-center">' . $procedimentoCBHPM->custo . '</div>
			<div class="col-md-2 border  d-flex justify-content-center">' . $procedimentoCBHPM->descricaoTabelaConvenio . '</div>
			
			';
		}
		$htmlProcedimentosCBHPM .= '
		</div>';

		$html .= $htmlProcedimentosCBHPM;



		//MEDICAMENTOS, MATERIAIS, OPME

		$htmlMatMedOPME = '';

		$htmlMatMedOPME .= '
				<div style="background:#cbd9e7;color:#000;font-weight: bold;" class="row border d-flex justify-content-center">MATERIAIS, MEDICAMENTOS E OPME
				</div>
				<div class="row border">		
					<div class="col-md-7 border">Item</div>
					<div class="col-md-1 border  d-flex justify-content-center">Qtde</div>
					<div class="col-md-2 border  d-flex justify-content-center">Unidade</div>	
					<div class="col-md-2 border  d-flex justify-content-center">Categoria</div>			
				
					
				';
		foreach ($procedimentosMatMedOPME as $procedimentoMatMedOPME) {

			$htmlMatMedOPME .= '
					
					<div class="col-md-7 border">' . $procedimentoMatMedOPME->descricaoItem . '</div>
					<div class="col-md-1 border  d-flex justify-content-center">' . $procedimentoMatMedOPME->qtde . '</div>
					<div class="col-md-2 border  d-flex justify-content-center">' . $procedimentoMatMedOPME->descricaoUnidade . '</div>
					<div class="col-md-2 border  d-flex justify-content-center">' . $procedimentoMatMedOPME->descricaoCategoria . '</div>
					
					';
		}
		$htmlMatMedOPME .= '
				</div>';

		$html .= $htmlMatMedOPME;




		//ASSINATURAS

		$htmlAssinaturas = '<div style="margin-top:30px"> </div> ';

		foreach ($membros as $membro) {

			//SOMENTE O CIRURGIÃO
			if ($membro->codFuncao == 1) {


				$htmlAssinaturas .= '
			<div style="margin-top:20px" class=" d-flex justify-content-center">
				<div class="border-top"> 
					' . $membro->nomeMembro . ', ' . $membro->conselhoMembro . ' ' . $membro->inscricaoMembro . '/' . $membro->siglaEstadoFederacao . '
				</div> 
			</div> 
			<div style="margin-top:0px !important" class="d-flex justify-content-center">
				<b>' . mb_strtoupper($membro->descricaoFuncao, 'utf8') . '</b>
			</div>
			';
			}
		}
		$html .= $htmlAssinaturas;

		$response['codStatus'] = $data->codStatus;
		$response['html'] = $html;

		return $this->response->setJSON($response);
	}

	public function add()
	{

		$response = array();
		$codAtendimento = $this->request->getPost('codAtendimento');

		$atendimento = $this->AtendimentosModel->dadosAtendimentoCompleto($codAtendimento);


		$fields['codAtendimento'] = $this->request->getPost('codAtendimento');
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['codAutor'] = session()->codPessoa;
		$fields['codLocalAtendimento'] = $atendimento->codLocalAtendimento;
		$fields['codStatus'] = 1;
		$fields['codTipoAtoCirurgico'] = $this->request->getPost('codTipoAtoCirurgico');


		$this->validation->setRules([
			'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
			'codStatus' => ['label' => 'CodStatus', 'rules' => 'required|numeric|max_length[11]'],
			'codTipoAtoCirurgico' => ['label' => 'CodTipoAtoCirurgico', 'rules' => 'required|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($codAtoCirurgico = $this->AtoCirurgicoModel->insert($fields)) {

				$response['success'] = true;
				$response['codAtoCirurgico'] = $codAtoCirurgico;
				$response['codTipoAtoCirurgico'] = $fields['codTipoAtoCirurgico'];
				$response['messages'] = 'Informação inserida com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na inserção!';
			}
		}

		return $this->response->setJSON($response);
	}


	public function assinaratoCirurgico()
	{



		$response = array();
		$fields['codAtoCirurgico'] = $this->request->getPost('codAtoCirurgico');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['assinadoPor'] = session()->codPessoa;
		$fields['codStatus'] = 2;

		//VERIFICA MEMBROS
		$membros = $this->AtoCirurgicoModel->dadosMembrosAtoCirurgico($fields['codAtoCirurgico']);

		//SEM MEMBROS
		if (empty($membros)) {

			$response['success'] = false;
			$response['messages'] = 'Não é possível assinar sem membros';
			return $this->response->setJSON($response);
		}

		$temCirurgiao = 0;
		foreach ($membros as $membro) {
			if ($membro->codFuncao == 1) {
				$temCirurgiao = 1;
			}
		}

		if ($temCirurgiao == 0) {

			$response['success'] = false;
			$response['messages'] = 'Não é possível assinar sem um cirurgião na equipe';
			return $this->response->setJSON($response);
		}



		//VERIFICA PROCEDIMENTOS
		$procedimentosCBHPM = $this->AtoCirurgicoModel->procedimentosCBHPM($fields['codAtoCirurgico']);

		if (empty($procedimentosCBHPM)) {

			$response['success'] = false;
			$response['messages'] = 'Não é possível assinar sem procedimentos lançados';
			return $this->response->setJSON($response);
		}

		//VERIFICA MEDICAMENTOS, MATERIAIS E OPME
		//DESATIVADO A PEDIDO DE YITZHAK
		/*
		$procedimentosMatMedOPME = $this->AtoCirurgicoModel->procedimentosMatMedOPME($fields['codAtoCirurgico']);

		if (empty($procedimentosMatMedOPME)) {

			$response['success'] = false;
			$response['messages'] = 'Não é possível assinar sem informar os itens usados no ato Cirúrgico (materiais, Medicamentos e/ou OPME)';
			return $this->response->setJSON($response);
		}
		*/

		//VERIFICA SE RELATÓRIO COMPLETO

		$data = $this->AtoCirurgicoModel->dadosPacienteAtoCirurgico($fields['codAtoCirurgico']);


		if ($data->descricaoAto == NULL) {
			$response['success'] = false;
			$response['messages'] = 'É necessário preencher o relatório do ato cirúrcigo';
			return $this->response->setJSON($response);
		}




		$this->validation->setRules([
			'codAtoCirurgico' => ['label' => 'codAtoCirurgico', 'rules' => 'required|numeric|max_length[11]'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
			'assinadoPor' => ['label' => 'assinadoPor', 'rules' => 'required|numeric|max_length[11]'],
			'codStatus' => ['label' => 'CodStatus', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtoCirurgicoModel->update($fields['codAtoCirurgico'], $fields)) {

				$response['success'] = true;
				$response['messages'] = 'Ato assinado com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na assinatura';
			}
		}


		return $this->response->setJSON($response);
	}

	public function edit()
	{

		$response = array();

		$fields['codAtoCirurgico'] = $this->request->getPost('codAtoCirurgico');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['codAutor'] = session()->codPessoa;
		$fields['codTipoAnestesia'] = $this->request->getPost('codTipoAnestesia');
		$fields['preOperatorio'] = $this->request->getPost('preOperatorio');
		$fields['resumoProcedimento'] = $this->request->getPost('resumoProcedimento');
		$fields['posOperatorio'] = $this->request->getPost('posOperatorio');
		$fields['intercorrencias'] = $this->request->getPost('intercorrencias');
		$fields['descricao'] = $this->request->getPost('descricao');


		$this->validation->setRules([
			'codAtoCirurgico' => ['label' => 'codAtoCirurgico', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtoCirurgicoModel->update($fields['codAtoCirurgico'], $fields)) {

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

		$id = $this->request->getPost('codAtoCirurgico');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->AtoCirurgicoModel->where('codAtoCirurgico', $id)->delete()) {

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
