<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\PacientesModel;
use App\Models\OrganizacoesModel;

use App\Models\QuestionariosModel;
use App\Models\RespostasQuestionarioModel;
use App\Models\DadosDemograficosModel;
use App\Models\PerguntasQuestionarioModel;

class Questionarios extends BaseController
{

	protected $QuestionariosModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->QuestionariosModel = new QuestionariosModel();
		$this->PessoasModel = new PessoasModel();
		$this->PacientesModel = new PacientesModel();
		$this->DadosDemograficosModel = new DadosDemograficosModel();
		$this->PerguntasQuestionarioModel = new PerguntasQuestionarioModel();
		$this->RespostasQuestionarioModel = new RespostasQuestionarioModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation = \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao = $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('Questionarios', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "Questionarios"', session()->codPessoa);
			exit();
		}




		$data = [
			'controller' => 'questionarios',
			'title' => 'Questionarios'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('questionarios', $data);
	}

	public function termoAceite()
	{
		$response = array();

		$html = "";
		$codQuestionario = $this->request->getPost('codQuestionario');
		$termoAceite = $this->QuestionariosModel->termoAceite($codQuestionario);

		if (session()->codPessoa == !NULL) {

			$dadosusuario = $this->PessoasModel->where('codPessoa', session()->codPessoa)->first();
		}

		if (session()->codPaciente == !NULL) {

			$dadosusuario = $this->PacientesModel->where('codPaciente', session()->codPaciente)->first();
		}



		//verifica se ja respondeu


		$termo = dadosTcle($termoAceite->termoAceite, $dadosusuario->nomeCompleto,  $dadosusuario->cpf);

		$html = '<div  style="margin-top:20px;margin-bottom:20px">';
		$html .= $termo;
		$html .= "</div>";
		$response["success"] = true;
		$response["codQuestionario"] = $codQuestionario;
		$response["html"] = $html;

		return $this->response->setJSON($response);
	}

	public function perguntasQuestionario()
	{
		$response = array();

		$html = "";
		$dadosDemograficosColaboradores = "";
		$dadosDemograficosPacientes = "";

		$codQuestionario = $this->request->getPost('codQuestionario');
		$modulo = $this->request->getPost('modulo');


		if ($this->request->getPost('modulo') == NULL) {
			$modulo = NULL;
		} else {
			$modulo = $this->request->getPost('modulo');
		}




		if ($modulo == 'Prontuário Eletrônico') {

			if (!empty(session()->filtroEspecialidade)) {
			} else {
				$modulo = 'Agendamento';
			}
		}



		$perguntas = $this->QuestionariosModel->perguntasQuestionario($codQuestionario);

		shuffle($perguntas);
		if (session()->codPaciente !== NULL) {
			$modulo = 'Agendamento';
			$dadosUsuario = $this->QuestionariosModel->dadosPaciente(session()->codPaciente);
			$dadosDemograficosPacientes = dadosDemograficosPacientes($this, $modulo);
			$listBoxDepartamento = "";
			$listaModulos = "";
		}

		$tipoUsuario = 'Paciente';
		if (session()->codPessoa !== NULL) {
			$tipoUsuario = 'Colaborador';
			$dadosUsuario = $this->QuestionariosModel->dadosPessoa(session()->codPessoa);
			$dadosDemograficosColaboradores = dadosDemograficosColaboradores($this, $modulo);
		}

		$html .= '
		<form id="respostaQuestionarioForm" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="' .  csrf_token() . 'respostaQuestionarioForm" name="' .  csrf_token() . '" value="' . csrf_hash() . '">
						<input type="hidden" id="codQuestionario" name="codQuestionario" value="' . $codQuestionario . '" class="form-control" placeholder="Código" maxlength="11" required>
						<input type="hidden" id="nomeExibicao" name="nomeExibicao" value="' . $dadosUsuario->nomeExibicao . '" class="form-control" placeholder="Código" maxlength="11" required >
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<input type="hidden" id="nomeCompleto" name="nomeCompleto" value="' . $dadosUsuario->nomeCompleto . '" class="form-control" placeholder="Código" maxlength="11" required>
							</div>
						</div>		
						<div class="col-md-2">
							<div class="form-group">
								<input type="hidden" id="idade" name="idade" value="' . $dadosUsuario->idade . '" class="form-control" placeholder="Código" maxlength="11" required>
							</div>
						</div>					
					</div>	
					' . $dadosDemograficosColaboradores . '	
					
					
					' . $dadosDemograficosPacientes . '
					
		';

		$x = 0;
		foreach ($perguntas as $pergunta) {
			$x++;
			$html .= '
			<div  style="margin-top:20px;margin-bottom:20px" class="col-md-12">';
			$html .= '
				<div class="card">
					<div  style="background:#6c757d3b;color:#000" class="card-header">
						<h3 style="font-size:25px" class="card-title">Q' . $x . ' - ' . $pergunta->descricaoPergunta . '</h3>
					</div>
					<div class="card-body">
						<div class="row">';


			$html .= '

			<style>
			.is-invalid{background:red}
			.invalid-feedback{background:red;color:#000}
			input[type=radio] {
				border: 0px;
				width: 60%;
				height: 2em;
			}
			</style>
			
								<div style="background:#ff00008f;color:#fff;font-weight:bold" class="col-md-2">
									<div style="margin-top:5px" class="row d-flex justify-content-center">
										<input  required type="radio" id="respostaPergunta' . $pergunta->codPergunta . '" name="respostaPergunta' . $pergunta->codPergunta . '" value=1>
									</div>
									<div class="row d-flex justify-content-center">
									<label>Discordo Totalmente</label>
									</div>
								</div>
								<div style="background:#f48120b3;color:#fff;font-weight:bold" class="col-md-2">
									<div style="margin-top:5px" class="row d-flex justify-content-center">
										<input type="radio" id="respostaPergunta" name="respostaPergunta' . $pergunta->codPergunta . '" value=2>
									</div>
									<div class="row d-flex justify-content-center">
									<label>Discordo</label>
									</div>
								</div>
								<div style="background:#f5fa4b;color:#99b89d;font-weight:bold" class="col-md-2">
									<div style="margin-top:5px" class="row d-flex justify-content-center">
										<input type="radio" id="respostaPergunta" name="respostaPergunta' . $pergunta->codPergunta . '" value=3>
									</div>
									<div class="row d-flex justify-content-center">
									<label>Neutro</label>
									</div>
								</div>
								
								<div style="background:#c6e736;color:#fff;font-weight:bold" class="col-md-2">
									<div style="margin-top:5px" class="row d-flex justify-content-center">
										<input type="radio" id="respostaPergunta" name="respostaPergunta' . $pergunta->codPergunta . '" value=4>
									</div>
									<div class="row d-flex justify-content-center">
									<label>Concordo</label>
									</div>
								</div>
								<div style="background:#0cc027;color:#fff;font-weight:bold" class="col-md-2">
									<div style="margin-top:5px" class="row d-flex justify-content-center">
										<input type="radio" id="respostaPergunta" name="respostaPergunta' . $pergunta->codPergunta . '" value=5>
									</div>
									<div class="row d-flex justify-content-center">
									<label>Concordo Totalmente</label>
									</div>
								</div>
								';



			$html .= '
					   </div>	
					</div>			
				</div>
			</div>
			';
		}

		$html .= '

			<div>
				<button type="submit" class="btn btn-primary" title="enviar"> <i class="fas fa-filter"></i>Enviar respostas</button>
				</div>
			</form>
			';


		$response["success"] = true;
		$response["codQuestionario"] = $codQuestionario;
		$response["html"] = $html;

		return $this->response->setJSON($response);
	}


	public function verificaExistencia()
	{
		$response = array();

		$html = "";


		if ($this->request->getPost('codQuestionario') == NULL) {
			$codQuestionario = NULL;
		} else {
			$codQuestionario = $this->request->getPost('codQuestionario');
		}

		if (session()->codPaciente !== NULL) {
			$modulo = 'Agendamento';
		} else {
			$modulo = $this->request->getPost('modulo');
		}


		if ($codQuestionario !== NULL and $codQuestionario !== "" and $codQuestionario !== " ") {
			$pesquisas = $this->QuestionariosModel->verificaExistenciaPesquisa($modulo, $codQuestionario);
		} else {
			$pesquisas = $this->QuestionariosModel->verificaExistenciaPesquisa($modulo, NULL);
		}


		//verifica se ja respondeu

		$html = '';
		$x = 0;
		foreach ($pesquisas as $pesquisa) {
			$x++;
			//$respostas = $this->QuestionariosModel->verificaExistenciaRespostas(5);

			if ($pesquisa->codDadosDemograficos == NULL) {
				$html .= '
				<style>
				.modal {
					overflow: auto !important;
				}
				</style>

				<div class="row">
				<div class="col-12">
					<div class="card card-secondary">
						<div style="font-size:25px" class="card-header">
						Pesquisa ' . $x . ' - ' . $pesquisa->titulo . '
						</div>
						<div class="card-body">
					

						<div class="card">
							<div style="background-color: #f8f9fa" class="card-header">
								<h5 class="card-title m-0">Objetivo</h5>
							</div>
							<div class="card-body">
								<h6 class="card-title">
								' . $pesquisa->objetivo . '
								</h6>

							</div>
						</div>

						<div class="card">
							<div style="background-color: #f8f9fa" class="card-header">
								<h5 class="card-title m-0">Instruções</h5>
							</div>
							<div class="card-body">
								<h6 class="card-title">
								' . $pesquisa->instrucoes . '
								</h6>

							</div>
						</div>

						</div>
					</div>						
				</div>				
			</div>

	<div class="row">
		<input type="hidden" id="codQuestionario" name="codQuestionario" value="' . $pesquisa->codQuestionario . '">
		<div style="margin-top:30px" class="col-md-2">
			<button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="iniciarpesquisa(' . $pesquisa->codQuestionario . ')" title="Adicionar">Sim, desejo participar
			</button>
		</div>	
	</div>
	

				';
				$response["success"] = true;
			} else {
				//$html .= "Existe e já foi respondido";
				$response["success"] = false;
			}
		}
		$html .= "";
		$response["html"] = $html;


		$botaoPesquisa = '
		
		<div style="margin-top:30px;margin-bottom:10px" class="col-md-2">
			<button style="width:400px" class="btn btn-block btn-outline-primary" onclick="chamaModalQualidade(' . $pesquisa->codQuestionario . ')">
				<div class="justify-content-center">
					<img style="width:150px" src="' . base_url() . "/imagens/pesquisa.png" . '">
					
				</div>
				<div class="justify-content-center"> 
					Há uma pesquisa disponível, clique aqui e PARTICIPE!
				</div>
			</button>
		</div>
		';


		$response["botao"] = $botaoPesquisa;


		return $this->response->setJSON($response);
	}


	public function pegaDadosDemograficos()
	{
		$response = array();

		$data['data'] = array();


		$codQuestionario = $this->request->getPost('codQuestionario');

		if ($this->validation->check($codQuestionario, 'required|numeric')) {
			$result = $this->QuestionariosModel->pegaDadosDemograficos($codQuestionario);
		}

		$x = 0;
		foreach ($result as $key => $value) {
			$x++;
			$data['data'][$key] = array(
				$x,
				$value->nomeExibicao,
				$value->tipoQuestionario,
				$value->setor,
				$value->tipoUsuario,
				$value->modulo,
				$value->P1,
				$value->P2,
				$value->P3,
				$value->P4,
				$value->P5,
				$value->P6,
				$value->P7,
				$value->P8,
				$value->P9,
				$value->P10,
				$value->pontos,
				$value->escala,
			);
		}



		return $this->response->setJSON($data);
	}


	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->QuestionariosModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editquestionarios(' . $value->codQuestionario . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removequestionarios(' . $value->codQuestionario . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="testarPesquisa(' . $value->codQuestionario . ')">Testar</button>';
			$ops .= '</div>';

			/*
				$value->codTipoQuestionario,
				$value->titulo,
				$value->aplicadoUsuarios,
				$value->aplicadoFuncionarios,
				$value->dataInicio,
				$value->dataEncerramento,
				$value->ativo,


			*/

			$aplicadoFuncionarios = "Não";
			if ($value->aplicadoFuncionarios == 1) {
				$aplicadoFuncionarios = "Sim";
			}

			$aplicadoUsuarios = "Não";
			if ($value->aplicadoUsuarios == 1) {
				$aplicadoUsuarios = "Sim";
			}


			$pesquisaAtiva = "Não";
			if ($value->ativo == 1) {
				$pesquisaAtiva = "Sim";
			}

			$informacoes = '
			<div class="row">
				<div class="col-md-12">
				<b>Objetivo</b>: <br>' . $value->objetivo . '
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
				<b>Data Inicio</b>: ' . date('d/m/Y', strtotime($value->dataInicio)) . '
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
				<b>Data Encerraento</b>: ' . date('d/m/Y', strtotime($value->dataEncerramento)) . '
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
				<b>Aplicar para colaboradores</b>: ' . $aplicadoFuncionarios . '
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
				<b>Aplicar para Pacientes</b>: ' . $aplicadoUsuarios . '
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
				<b>Pesquisa ativa</b>: ' . $pesquisaAtiva . '
				</div>
			</div>
						
			';

			$data['data'][$key] = array(
				$value->codQuestionario,
				$value->descricaoTipoQuestionario,
				$value->titulo,
				$informacoes,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}



	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codQuestionario');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->QuestionariosModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codQuestionario'] = $this->request->getPost('codQuestionario');
		$fields['codTipoQuestionario'] = $this->request->getPost('codTipoQuestionario');
		$fields['dataInicio'] = $this->request->getPost('dataInicio');
		$fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');
		$fields['ativo'] = 0;
		$fields['objetivo'] = $this->request->getPost('objetivo');
		$fields['titulo'] = $this->request->getPost('titulo');
		$fields['instrucoes'] = $this->request->getPost('instrucoes');
		$fields['termoAceite'] = $this->request->getPost('termoAceite');
		$fields['aplicadoUsuarios'] = $this->request->getPost('aplicadoUsuarios');
		$fields['aplicadoFuncionarios'] = $this->request->getPost('aplicadoFuncionarios');





		if ($codQuestionario = $this->QuestionariosModel->insert($fields)) {
			$perguntas = $this->QuestionariosModel->pegaPerguntas($this->request->getPost('codTipoQuestionario'));

			foreach ($perguntas as $item) {
				$fieldsPerguntas['codPergunta'] = $item->codPergunta;
				$fieldsPerguntas['codQuestionario'] = $codQuestionario;
				$this->PerguntasQuestionarioModel->insert($fieldsPerguntas);
			}


			$response['success'] = true;
			$response['messages'] = 'Informação inserida com sucesso';
		} else {

			$response['success'] = false;
			$response['messages'] = 'Erro na inserção!';
		}

		return $this->response->setJSON($response);
	}
	public function naoConcordou()
	{

		$response = array();

		$respostaDemografico['codPessoa'] = session()->codPessoa;
		$respostaDemografico['codPaciente'] = session()->codPaciente;
		$respostaDemografico['nomeExibicao'] = session()->nomeExibicao;
		$respostaDemografico['nomeCompleto'] = session()->nomeCompleto;
		$respostaDemografico['codQuestionario'] = $this->request->getPost('codQuestionario');
		$respostaDemografico['modulo'] = $this->request->getPost('modulo');
		$respostaDemografico['dataResposta'] = date('Y-m-d H:i');
		$respostaDemografico['concordou'] = 0;

		$codDadosDemograficos = $this->DadosDemograficosModel->insert($respostaDemografico);

		$response['success'] = true;
		$response["messages"] = 'Pesquisa respondida com sucesso';

		return $this->response->setJSON($response);
	}

	public function inserirResposta()
	{

		$response = array();


		if (session()->codPessoa !== NULL) {
			$tipoUsuario = 'Colaborador';
			$respostaDemografico['setor'] = $this->request->getPost('codDepartamento');
		}

		$respostaDemografico['modulo'] = $this->request->getPost('modulo');

		if (session()->codPaciente !== NULL) {
			$tipoUsuario = 'Paciente';
			$respostaDemografico['setor'] = 'Internet';
			$respostaDemografico['modulo'] = 'Agendamento';
		}


		$respostaDemografico['codQuestionario'] = $this->request->getPost('codQuestionario');
		$respostaDemografico['codPaciente'] = session()->codPaciente;
		$respostaDemografico['codPessoa'] = session()->codPessoa;
		$respostaDemografico['nomeCompleto'] = $this->request->getPost('nomeCompleto');
		$respostaDemografico['nomeExibicao'] = $this->request->getPost('nomeExibicao');
		$respostaDemografico['idade'] = $this->request->getPost('idade');
		$respostaDemografico['idade'] = $this->request->getPost('idade');
		$respostaDemografico['sexo'] = $this->request->getPost('sexo');
		$respostaDemografico['educacao'] = $this->request->getPost('educacao');
		$respostaDemografico['ocupacao'] = $this->request->getPost('ocupacao');
		$respostaDemografico['experienciaProfissional'] = $this->request->getPost('experienciaProfissional');
		$respostaDemografico['experienciaTecnologia'] = $this->request->getPost('experienciaTecnologia');
		$respostaDemografico['experienciaProduto'] = $this->request->getPost('experienciaProduto');
		$respostaDemografico['tipoUsuario'] = $tipoUsuario;
		$respostaDemografico['concordou'] = 1;


		$codDadosDemograficos = $this->DadosDemograficosModel->insert($respostaDemografico);

		if ($codDadosDemograficos !== NULL) {

			foreach ($this->request->getPost() as $chave => $atributo) {
				$resposta = array();

				if (strpos($chave,  'respostaPergunta') !== false) {

					$resposta['codDadosDemograficos'] = $codDadosDemograficos;
					$resposta['codPergunta'] = str_replace('respostaPergunta', '', $chave);
					$resposta['codQuestionario'] = $this->request->getPost('codQuestionario');
					$resposta['resposta'] = $this->request->getPost('respostaPergunta' . $resposta['codPergunta']);
					$resposta['dataResposta'] = date('Y-m-d H:i');
					$resposta['severidade'] = $this->request->getPost('severidade');
					$resposta['codPaciente'] = session()->codPaciente;
					$resposta['codPessoa'] = session()->codPessoa;

					$codResposta = $this->RespostasQuestionarioModel->insert($resposta);
				}
			}

			$response['success'] = true;
			$response["messages"] = 'Pesquisa registrada. Obrigado por participar!';
		} else {
			$response['success'] = true;
			$response["messages"] = 'Falha na inserção';
		}


		sleep(2);
		return $this->response->setJSON($response);
	}

	public function edit()
	{

		$response = array();

		$fields['codQuestionario'] = $this->request->getPost('codQuestionario');
		$fields['dataInicio'] = $this->request->getPost('dataInicio');
		$fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');
		$fields['ativo'] = $this->request->getPost('ativo');
		$fields['objetivo'] = $this->request->getPost('objetivo');
		$fields['titulo'] = $this->request->getPost('titulo');
		$fields['instrucoes'] = $this->request->getPost('instrucoes');
		$fields['termoAceite'] = $this->request->getPost('termoAceite');
		$fields['aplicadoUsuarios'] = $this->request->getPost('aplicadoUsuarios');
		$fields['aplicadoFuncionarios'] = $this->request->getPost('aplicadoFuncionarios');


		$this->validation->setRules([
			'codQuestionario' => ['label' => 'codQuestionario', 'rules' => 'required|numeric'],
			'dataInicio' => ['label' => 'Data Início', 'rules' => 'required|valid_date'],
			'dataEncerramento' => ['label' => 'Data Encerramento', 'rules' => 'permit_empty|valid_date'],
			'ativo' => ['label' => 'Ativo', 'rules' => 'required|max_length[11]'],
			'objetivo' => ['label' => 'Objetivo', 'rules' => 'required'],
			'instrucoes' => ['label' => 'Instrucoes', 'rules' => 'permit_empty'],
			'termoAceite' => ['label' => 'TermoAceite', 'rules' => 'required'],
			'aplicadoUsuarios' => ['label' => 'Aplicado à Usuários', 'rules' => 'required|max_length[11]'],
			'aplicadoFuncionarios' => ['label' => 'Aplicado à Funcionários', 'rules' => 'required|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->QuestionariosModel->update($fields['codQuestionario'], $fields)) {

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

		$id = $this->request->getPost('codQuestionario');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->QuestionariosModel->where('codQuestionario', $id)->delete()) {

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
