<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\PacientesModel;
use App\Models\OrganizacoesModel;
use App\Models\PainelSenhasModel;

use App\Models\AtendimentoSenhasModel;
use App\Models\AtendimentoslocaisModel;

class AtendimentoSenhas extends BaseController
{

	protected $AtendimentoSenhasModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->AtendimentoSenhasModel = new AtendimentoSenhasModel();
		$this->AtendimentoslocaisModel = new AtendimentoslocaisModel();
		$this->PainelSenhasModel = new PainelSenhasModel();
		$this->PessoasModel = new PessoasModel();
		$this->PacientesModel = new PacientesModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPaciente);
		$this->validation = \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao = $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{


		$data = [
			'controller' => 'atendimentoSenhas',
			'title' => 'Senhas Atendimento'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('atendimentoSenhas', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->AtendimentoSenhasModel->pegaAtendimentos();

		foreach ($result as $key => $value) {

			$ops = '
			<div class="btn-group">
				<button type="button" class="btn btn-info">Ação</button>
				<button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
				<span class="sr-only">Toggle Dropdown</span>
				</button>
				<div class="dropdown-menu" role="menu">
					<a href="#" class="dropdown-item" onclick="iniciarAtendimento(' . $value->codSenhaAtendimento . ')">Iniciar Atendimento</a>
					<a href="#" class="dropdown-item" onclick="encerrarAtendimentoAgora(' . $value->codSenhaAtendimento . ')">Encerrar Atendimento</a>
					<a href="#" class="dropdown-item" onclick="chamarAtendimentoIniciado(' . $value->codSenhaAtendimento . ')">Editar</a>
					<a href="#" class="dropdown-item" onclick="chamarPainelAgora(' . $value->codSenhaAtendimento . ')">Chamar no painel</a>
					<a href="#" class="dropdown-item" onclick="faltouAtendimento(' . $value->codSenhaAtendimento . ')">Faltou</a>
				</div>
			</div>';


			if ($value->codPrioridade == 1) {
				$prioridade = "Prioridade";
			} else {
				$prioridade = "Normal";
			}
			if ($value->codStatus == 0) {
				if ($value->qtdChamadas > 0) {
					$status = "Chamado";
				} else {
					$status = "Aguardando";
				}
			}
			if ($value->codStatus == 1) {
				$status = "Em atendimento";
			}
			if ($value->codStatus == 3) {
				$status = "Atendido";
			}
			if ($value->codStatus == 4) {
				$status = "Abandonados";
			}

			if ($value->qtdChamadas > 0) {
				$qtdChamadas = $value->qtdChamadas;
			} else {
				$qtdChamadas = NULL;
			}
			if ($value->nomePaciente !== NULL) {
				$nomePaciente = $value->nomePaciente . ' (' . $value->idade . ')';
			} else {
				$nomePaciente = "Não Identificado";
			}

			if ($value->dataAgendamento !== NULL) {
				$dataAgendamento = date('d/m/Y H:i', strtotime($value->dataAgendamento));
			} else {
				$dataAgendamento = NULL;
			}

			if ($value->dataInicio !== NULL) {
				$inicio = date('d/m/Y H:i', strtotime($value->dataInicio));
			} else {
				$inicio = NULL;
			}


			$precCelular =

				'

								<div class="row">
									<div class="col-md-12">
										<div>
											' . $value->codPlano . '
										</div>

										<div style="margin-top:0px;font-size:10px">
											' . $value->celular . '
										</div>
									</div>
								</div>';


			$data['csrf_token'] = csrf_token();
			$data['csrf_hash'] = csrf_hash();
			$data['data'][$key] = array(
				$value->senha,
				$nomePaciente,
				$value->cpf,
				$precCelular,
				$prioridade,
				$inicio,
				$status,
				$qtdChamadas,
				$value->nomeExibicao,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}


	public function filtrarAgendados()
	{


		$response = array();
		if ($this->request->getPost('dataInicio') !== NULL) {
			$dataInicio = $this->request->getPost('dataInicio');
		} else {
			$dataInicio = NULL;
		}
		session()->set('dataInicioAgendados', $dataInicio);


		$response['success'] = true;
		$response['csrf_hash'] = csrf_hash();
		return $this->response->setJSON($response);
	}


	public function setaTipoFila()
	{
		$response = array();
		$codTipoFila = $this->request->getPost('codTipoFila');

		session()->set('codTipoFila', $codTipoFila);


		$response['success'] = true;
		$response['csrf_hash'] = csrf_hash();
		$response['message'] = $codTipoFila;
		return $this->response->setJSON($response);
	}


	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codSenhaAtendimento');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->AtendimentoSenhasModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function removerAgendamento()
	{


		$codSenhaAtendimento = $this->request->getPost('codSenhaAtendimento');

		$response = array();
		//ATUALIZA STATUS DO ATENDIMENTO
		$statusAtendimento['codStatus'] = 0;
		$statusAtendimento['codPaciente'] = 0;
		$statusAtendimento['cpf'] = NULL;
		$statusAtendimento['nomePaciente'] = NULL;
		$statusAtendimento['idade'] = NULL;
		$statusAtendimento['codAtendente'] = NULL;
		$statusAtendimento['codAutor'] = NULL;
		$statusAtendimento['protocolo'] = NULL;
		$statusAtendimento['dataProtocolo'] = NULL;
		$statusAtendimento['dataAgendamento'] = NULL;
		$statusAtendimento['codAtendente'] = session()->codPessoa;

		if (!$this->validation->check($codSenhaAtendimento, 'required|numeric')) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtendimentoSenhasModel->update($codSenhaAtendimento, $statusAtendimento)) {
			}
		}





		$response['success'] = true;
		$response['csrf_hash'] = csrf_hash();
		$response['messages'] = 'Agendamento cancelado com sucesso';
		return $this->response->setJSON($response);
	}


	public function iniciarAtendimento()
	{


		$codSenhaAtendimento = $this->request->getPost('codSenhaAtendimento');
		$atendimentoSenha = $this->AtendimentoSenhasModel->pegaPorCodigo($codSenhaAtendimento);

		if ($atendimentoSenha->codStatus > 0) {
			$response['success'] = 'info';


			$response['csrf_hash'] = csrf_hash();
			return $this->response->setJSON($response);
		}

		$response = array();
		//ATUALIZA STATUS DO ATENDIMENTO
		$statusAtendimento['codStatus'] = 1;
		$statusAtendimento['dataInicioAtendimento'] = date('Y-m-d H:i');
		$statusAtendimento['codAtendente'] = session()->codPessoa;
		$statusAtendimento['codLocalAtendimento'] = session()->codLocalAtendimento;


		if ($this->validation->check($codSenhaAtendimento, 'required|numeric')) {

			if ($this->AtendimentoSenhasModel->update($codSenhaAtendimento, $statusAtendimento)) {
			}


			$html = $this->geraModal($atendimentoSenha->codPaciente, $atendimentoSenha->idade, $atendimentoSenha->cpf, $atendimentoSenha->fotoPerfil, $atendimentoSenha->nomePaciente, $atendimentoSenha->senha, $atendimentoSenha->codSenhaAtendimento);
			//ATUALIZA STATUS DO ATENDIMENTO

			$response['success'] = true;
			$response['csrf_hash'] = csrf_hash();
			$response['html'] = $html;
			$response['codSenhaAtendimento'] = $codSenhaAtendimento;
		} else {

			$response['success'] = false;
			$response['messages'] = 'Falha ao chamar paciente, contate o administrador do sistema';
		}

		return $this->response->setJSON($response);
	}

	public function chamarProximo()
	{

		if (session()->codTipoFila == NULL or session()->codTipoFila == '' or session()->codTipoFila == ' ') {
			$codTipoFila = 1;
		} else {
			$codTipoFila = session()->codTipoFila;
		}

		$proximoPrioridade = $this->AtendimentoSenhasModel->proximoPrioridade($codTipoFila);
		$proximoNormal = $this->AtendimentoSenhasModel->proximoNormal($codTipoFila);


		if (session()->chamarPrioridade !== NULL and $proximoPrioridade !== NUll) {

			if (session()->chamarPrioridade >= 0 and session()->chamarPrioridade <= 3) {
				session()->chamarPrioridade--;
			} else {
				session()->chamarPrioridade = 3;
			}
		}

		//DEFINE QUEM CHAMAR PRIORIDADE OU NORMAL

		if (session()->chamarPrioridade >= 0 and $proximoPrioridade !== NUll) {
			//CHAMA PRIORIDADE

			if ($proximoPrioridade !== NULL) {
				$html = $this->geraModal($proximoPrioridade->codPaciente, $proximoPrioridade->idade, $proximoPrioridade->cpf, $proximoPrioridade->fotoPerfil, $proximoPrioridade->nomePaciente, $proximoPrioridade->senha, $proximoPrioridade->codSenhaAtendimento);
				//ATUALIZA STATUS DO ATENDIMENTO

				$codSenhaAtendimento = $proximoPrioridade->codSenhaAtendimento;
				$statusAtendimento['codStatus'] = 1;
				$statusAtendimento['dataInicioAtendimento'] = date('Y-m-d H:i');
				$statusAtendimento['codAtendente'] = session()->codPessoa;
				$statusAtendimento['codLocalAtendimento'] = session()->codLocalAtendimento;

				if (!$this->validation->check($proximoPrioridade->codSenhaAtendimento, 'required|numeric')) {

					$response['success'] = false;
					$response['messages'] = $this->validation->listErrors();
				} else {

					$this->AtendimentoSenhasModel->update($proximoPrioridade->codSenhaAtendimento, $statusAtendimento);
				}
			} else {
				$html = '
				<div class="callout callout-info">
				<h5>Fila de Prioridade está vazia</h5>

				<p>Tente puxar da fila Normal</p>
			  </div>';
			}
		} else {
			//CHAMA NORMAL
			if ($proximoNormal !== NULL) {
				$senha = $proximoNormal->senha;
				$html = $this->geraModal($proximoNormal->codPaciente, $proximoNormal->idade, $proximoNormal->cpf, $proximoNormal->fotoPerfil, $proximoNormal->nomePaciente, $proximoNormal->senha, $proximoNormal->codSenhaAtendimento);
				$codSenhaAtendimento = $proximoNormal->codSenhaAtendimento;
				session()->chamarPrioridade = 3;

				//ATUALIZA STATUS DO ATENDIMENTO
				$statusAtendimento['codStatus'] = 1;
				$statusAtendimento['dataInicioAtendimento'] = date('Y-m-d H:i');
				$statusAtendimento['codAtendente'] = session()->codPessoa;
				$statusAtendimento['codLocalAtendimento'] = session()->codLocalAtendimento;

				if (!$this->validation->check($proximoNormal->codSenhaAtendimento, 'required|numeric')) {

					$response['success'] = false;
					$response['messages'] = $this->validation->listErrors();
				} else {

					if ($this->AtendimentoSenhasModel->update($proximoNormal->codSenhaAtendimento, $statusAtendimento)) {
					}
				}
			} else {
				$html = '
				<div class="callout callout-info">
				<h5>Fila Normal está vazia</h5>

				<p>Tente puxar da fila de prioridades</p>
			  </div>';
			}
		}

		$response['success'] = true;
		$response['csrf_hash'] = csrf_hash();
		$response['html'] = $html;
		$response['codSenhaAtendimento'] = $codSenhaAtendimento;
		return $this->response->setJSON($response);
	}

	public function chamarPainelAgora()
	{
		$codSenhaAtendimento = $this->request->getPost('codSenhaAtendimento');

		$atendimento = $this->AtendimentoSenhasModel->pegaPorCodigo($codSenhaAtendimento);


		$localAtendimento = $this->AtendimentoslocaisModel->pegaPorCodigo(session()->codLocalAtendimento);


		$response = array();
		$data = array();

		if (session()->nomeLocalAtendimento !== NULL) {
			$data['localAtendimento'] = " (" . session()->nomeLocalAtendimento . ")";
		} else {
			$data['localAtendimento'] = "";
		}
		$data['qtdChamadas'] = 1;
		$data['nomeCompleto'] = $atendimento->nomePaciente;
		$data['senha'] = $atendimento->senha;
		$data['codDepartamento'] = $atendimento->codDepartamento;
		$data['localAtendimento'] = $localAtendimento->descricaoLocalAtendimento;
		$data['fotoPerfil'] = $atendimento->fotoPerfil;
		$data['dataChamada'] = date('Y-m-d H:i');

		if ($this->PainelSenhasModel->insert($data)) {

			//ATUALIZA REGISTRO
			$dataChamada['qtdChamadas'] = $atendimento->qtdChamadas + 1;

			if ($atendimento->dataHoraPrimeiraChamada == NULL) {
				$dataChamada['dataHoraPrimeiraChamada'] = date('Y-m-d H:i');
			}

			if (!$this->validation->check($codSenhaAtendimento, 'required|numeric')) {

				$response['success'] = false;
				$response['messages'] = $this->validation->listErrors();
			} else {
				$this->AtendimentoSenhasModel->update($codSenhaAtendimento, $dataChamada);
			}




			$response['success'] = true;
			$response['csrf_hash'] = csrf_hash();
			$response['messages'] = 'chamada realizada com sucessoo';
		} else {
			$response['success'] = false;
			$response['messages'] = 'Erro ao chamar pessoa no painel, contate o administrador do sistema';
		}

		return $this->response->setJSON($response);
	}

	public function chamarAtendimentoIniciado()
	{
		$codSenhaAtendimento = $this->request->getPost('codSenhaAtendimento');

		$atendimento = $this->AtendimentoSenhasModel->pegaPorCodigo($codSenhaAtendimento);

		if ($atendimento->codStatus == 0) {
			$response['success'] = false;
			$response['messages'] = 'Só é possível editar o atendimento com o status de "EM ANDAMENTO". Realize a chamada automática do sistema';
			return $this->response->setJSON($response);
		}
		$html = $this->geraModal($atendimento->codPaciente, $atendimento->idade, $atendimento->cpf, $atendimento->fotoPerfil, $atendimento->nomePaciente, $atendimento->senha, $atendimento->codSenhaAtendimento, $atendimento->dataAgendamento);

		$response['success'] = true;
		$response['csrf_hash'] = csrf_hash();
		$response['html'] = $html;
		return $this->response->setJSON($response);
	}

	public function geraModal($codPaciente = null, $idade = null, $cpf = null, $fotoPerfil = null, $nomePaciente = null, $senha = null, $codSenhaAtendimento = null, $dataAgendamento = null)
	{
		$html = "";

		if ($idade !== NULL) {
			$idadeInfo = '<h1 class="widget-user-desc">IDADE: ' . $idade . '</h1>';
		}
		if ($dataAgendamento !== NULL) {
			$dataAgendamento = '<h1 class="widget-user-desc">AGENDAMENTO: ' . date("H:i", strtotime($dataAgendamento)) . '</h1>';
		} else {
			$dataAgendamento = "";
		}

		if ($fotoPerfil !== NULL) {
			$fotoPerfilInfo = '

			<style>
			.borda {
				background: -webkit-linear-gradient(left top, #fffe01 0%, #28a745 100%);
				border-radius: 1000px;
				padding: 6px;
				width: 300px;
				height: 300px;

			}
			</style>


			<img style="width:200px" class="borda" onerror="this.onerror=null; this.remove();" alt="" src="' . base_url() . '/arquivos/imagens/pacientes/' . $fotoPerfil . '">';
		} else {
			$fotoPerfilInfo = "";
		}

		$html .= '
		<div class="row">
		<input type="hidden" id="codSenhaAtendimento" name="codSenhaAtendimento" value="' . $codSenhaAtendimento . '" >
		<input type="hidden" id="codPaciente" name="codPaciente" value="' . $codPaciente . '" >
		<input type="hidden" id="idade"  name="idade" value="' . $idade . '" >
		<input type="hidden" id="cpf" name="cpf" value="' . $cpf . '" >
		<input type="hidden" id="fotoPerfil" name="fotoPerfil" value="' . $fotoPerfil . '" >
		<input type="hidden" id="nomePaciente" name="nomePaciente" value="' . $nomePaciente . '" >
			<div class="col-md-12">
								<div class="card card-widget widget-user shadow">
									<!-- Add the bg color to the header using any of the bg-* classes -->
									<div style="background:#56ff0052;height:200px" class="widget-user-header">
									<h1 style="font-size:30px !important;margin-bottom:10px;font-weight: bold;" class="widget-user-username">' . $senha . '</h1>
									<h3 style="font-size:30px !important;margin-bottom:10px;font-weight: bold;" class="widget-user-username">' . $nomePaciente . '</h3>
									' . $fotoPerfilInfo . '
									</div>
									<div class="card-footer">
										<div class="row">
											<div class="col-sm-6 border-right">
												<div style="font-size:14px;font-weight: bold; color:green" class="description-block">
													<h5 class="description-header">
														' . $dataAgendamento . '
												</div>
												<div style="font-size:14px;font-weight: bold; color:green" class="description-block">
													<h5 class="description-header">
														' . $idadeInfo . '
												</div>
											</div>
											<div class="col-md-6 text-center">
											<div class="form-group">
												<a onclick="chamarPainelAgora(' . $codSenhaAtendimento . ')" class="btn btn-block btn-outline-secondary btn-lg ">
													<div>
													<i style="color:#007bff" class="fas fa-bullhorn zoom fa-4x" ></i>
													</div>
												<div>Chamar no painel</div>
												</a>
											</div>
										</div>
										</div>
									</div>
								</div>
								</div>
							</div>

		';

		return $html;
	}


	public function verificaSala()
	{
		$response = array();

		if (session()->nomeLocalAtendimento !== NULL) {
			$response['success'] = true;
			$response['csrf_hash'] = csrf_hash();
			$response['botao'] = '<button class="btn btn-danger btn-lg " onclick="showDefinirSala()" id="Troca sala">
			<div class="spinner-grow text-light spinner-grow-sm" role="status">
			<span class="sr-only">Loading...</span>
			</div><span class="col-md-12">' . session()->nomeLocalAtendimento . '</span>
			(Trocar Local)</button>';
			$response['nomeLocalAtendimento'] = session()->nomeLocalAtendimento;
			$response['nomeDepartamentoAtendimento'] = session()->nomeDepartamentoAtendimento = lookupCodNomeDepartamentosJson(session()->codDepartamentoAtendimento)[0]->descricaoDepartamento;
		} else {

			$response['success'] = true;
			$response['csrf_hash'] = csrf_hash();
			$response['botao'] = '<button class="btn btn-warning btn-lg" onclick="showDefinirSala()" id="Troca sala"><i class="fas fa-edit"></i>Definir Local de Atendimento</button>';
			$response['nomeLocalAtendimento'] = '';
		}
		return $this->response->setJSON($response);
	}

	public function procuraPessoa()
	{

		$response = array();

		$cpf = removeCaracteresIndesejados($this->request->getPost('cpf'));
		$paciente = $this->PacientesModel->pegaPacientePorcpf($cpf);

		if ($paciente->nomeExibicao == NULL) {
			$nomePaciente = 'Dados Não encontrados';
		} else {
			$nomePaciente = $paciente->nomeExibicao;
		}

		$fotoPerfil = base_url() . "/arquivos/imagens/pacientes/" . $paciente->fotoPerfil;

		if ($paciente->idade >= 60) {
			$checked = "checked";
		} else {
			$checked = "";
		}
		$html = "";

		$html .= '
		<div class="row">
		<input type="hidden" id="codPaciente" name="codPaciente" value="' . $paciente->codPaciente . '" >
		<input type="hidden" id="idade"  name="idade" value="' . $paciente->idade . '" >
		<input type="hidden" id="cpf" name="cpf" value="' . $cpf . '" >
		<input type="hidden" id="fotoPerfil" name="fotoPerfil" value="' . $paciente->fotoPerfil . '" >
		<input type="hidden" id="nomePaciente" name="nomePaciente" value="' . $paciente->nomeExibicao . '" >
			<div class="col-md-12">
								<div class="card card-widget widget-user shadow">
									<!-- Add the bg color to the header using any of the bg-* classes -->
									<div style="background:#56ff0052;height:200px" class="widget-user-header">
										<h3 style="font-size:30px !important;margin-bottom:10px;font-weight: bold;" class="widget-user-username">' . $nomePaciente . '</h3>

										<style>
										.borda {
											background: -webkit-linear-gradient(left top, #fffe01 0%, #28a745 100%);
											border-radius: 1000px;
											padding: 6px;
											width: 300px;
											height: 300px;

										}
										</style>
										<img style="width:200px" class="borda" alt="" src="' . $fotoPerfil . '">

									</div>
									<div class="card-footer">
										<div class="row">
											<div class="col-sm-6 border-right">
												<div style="font-size:14px;font-weight: bold; color:green" class="description-block">
													<h5 class="description-header">
														<h1 class="widget-user-desc">IDADE:' . $paciente->idade . '</h1>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="description-block">
													<h5 class="description-header">
														<div style="font-size:14px;font-weight: bold ; color:green">
															<div style="font-size:14px;">
															<div class="form-group">
													<label for="checkboxPrioridade"><h1>Sou Prioridade: </h1></label>
													<div class="icheck-primary d-inline">
														<style>
															input[type=checkbox] {
																transform: scale(1.8);
															}
														</style>
														<input style="margin-left:5px;" name="prioridade" ' . $checked . ' type="checkbox" id="checkboxPrioridade">


													</div>
												</div>
															</div>
														</div>
													</h5>
												</div>
											</div>
										</div>
									</div>
								</div>
								</div>
							</div>

		';

		$response['success'] = true;
		$response['csrf_hash'] = csrf_hash();
		$response['html'] = $html;

		return $this->response->setJSON($response);
	}

	public function encerrarAtendimento()
	{

		sleep(1);
		$response = array();

		if (!$this->validation->check($this->request->getPost('codSenhaAtendimento'), 'required|numeric')) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			$fields['dataEncerramentoAtendimento'] = date('Y-m-d H:i');
			$fields['codStatus'] = 3;

			if ($this->AtendimentoSenhasModel->update($this->request->getPost('codSenhaAtendimento'), $fields)) {
			}
		}


		$response['success'] = true;
		$response['csrf_hash'] = csrf_hash();
		$response['messages'] = 'Atendimento Encerrdo';
		return $this->response->setJSON($response);
	}
	public function encerrarAtendimentoComFalta()
	{

		sleep(1);
		$response = array();


		if (!$this->validation->check($this->request->getPost('codSenhaAtendimento'), 'required|numeric')) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			$fields['dataEncerramentoAtendimento'] = date('Y-m-d H:i');
			$fields['codStatus'] = 3;
			$fields['faltou'] = 1;

			if ($this->AtendimentoSenhasModel->update($this->request->getPost('codSenhaAtendimento'), $fields)) {
			}
		}



		$response['success'] = true;
		$response['csrf_hash'] = csrf_hash();
		$response['messages'] = 'Atendimento Encerrdo';
		return $this->response->setJSON($response);
	}
	public function gerarProtocolo()
	{

		$response = array();
		if ($this->request->getPost('codDepartmento') !== NULL) {
			$fields['codDepartamento'] = $this->request->getPost('codDepartmento');
			session()->codDepartamentoAtendimento = $this->request->getPost('codDepartmento');
		} else {
			$fields['codDepartamento'] = session()->codDepartamentoAtendimento;
		}

		if ($this->request->getPost('prioridade') == 'on') {
			$fields['codPrioridade'] = 1;
		} else {
			$fields['codPrioridade'] = 0;
		}

		if ($fields['codPrioridade'] == 1) {
			$ultimoNumeroPrioridade = $this->AtendimentoSenhasModel->ultimoNumeroPrioridade(1, $fields['codDepartamento'])->total;
			$ultimoNumeroPrioridade++;
			$ultimoNumeroPrioridade = str_pad($ultimoNumeroPrioridade, 3, '0', STR_PAD_LEFT);
			$senha = 'P' . $ultimoNumeroPrioridade;
			$fields['senha'] = $senha;
		} else {
			$ultimoNumeroNormal = $this->AtendimentoSenhasModel->ultimoNumeroNormal(1, $fields['codDepartamento'])->total;
			$ultimoNumeroNormal++;
			$ultimoNumeroNormal = str_pad($ultimoNumeroNormal, 3, '0', STR_PAD_LEFT);
			$senha = 'N' . $ultimoNumeroNormal;
			$fields['senha'] = $senha;
		}


		if ($this->request->getPost('codPaciente') == NULL or $this->request->getPost('codPaciente') == '') {
			$codPaciente = NULL;
		} else {
			$codPaciente = $this->request->getPost('codPaciente');
		}


		if ($this->request->getPost('nomePaciente') == NULL or $this->request->getPost('nomePaciente') == '') {
			$nomePaciente = NULL;
		} else {
			$nomePaciente = $this->request->getPost('nomePaciente');
		}

		if ($this->request->getPost('idade') == NULL or $this->request->getPost('idade') == '') {
			$idade = NULL;
		} else {
			$idade = $this->request->getPost('idade');
		}


		if ($this->request->getPost('fotoPerfil') == NULL or $this->request->getPost('fotoPerfil') == '') {
			$fotoPerfil = NULL;
		} else {
			$fotoPerfil = $this->request->getPost('fotoPerfil');
		}


		$fields['protocolo'] = date('Y') . date('m') . date('d') . geraNumero(6);
		$fields['dataProtocolo'] = date('Y-m-d H:i');
		$fields['dataAgendamento'] = date('Y-m-d H:i');
		$fields['codPaciente'] = $codPaciente;
		$fields['nomePaciente'] = $nomePaciente;
		$fields['codLocalAtendimento'] = NULL;
		$fields['idade'] = $idade;
		$fields['cpf'] = $this->request->getPost('cpf');
		$fields['fotoPerfil'] = $fotoPerfil;
		$fields['codOrganizacao'] = session()->codOrganizacao;
		$fields['dataInicio'] = date('Y-m-d H:i');
		$fields['dataEncerramento'] = NULL;
		$fields['codStatus'] = 0;
		$fields['codTipoFila'] = 1;

		$this->validation->setRules([
			'protocolo' => ['label' => 'Protocolo', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($codSenhaAtendimento = $this->AtendimentoSenhasModel->insert($fields)) {

				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['codSenhaAtendimento'] = $codSenhaAtendimento;
				$response['senha'] = $fields['senha'];
				$response['protocolo'] = $fields['protocolo'];
				$response['data'] = date('d/m/d H:i', strtotime($fields['dataProtocolo']));
				$response['messages'] = 'Informação inserida com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na inserção!';
			}
		}

		return $this->response->setJSON($response);
	}

	public function gerarProtocoloResultado()
	{

		$response = array();


		if ($this->request->getPost('prioridade') == 'on') {
			$fields['codPrioridade'] = 1;
		} else {
			$fields['codPrioridade'] = 0;
		}

		if ($fields['codPrioridade'] == 1) {
			$ultimoNumeroPrioridade = $this->AtendimentoSenhasModel->ultimoNumeroPrioridade(2)->total;
			$ultimoNumeroPrioridade++;
			$ultimoNumeroPrioridade = str_pad($ultimoNumeroPrioridade, 3, '0', STR_PAD_LEFT);
			$senha = 'RP' . $ultimoNumeroPrioridade;
			$fields['senha'] = $senha;
		} else {
			$ultimoNumeroNormal = $this->AtendimentoSenhasModel->ultimoNumeroNormal(2)->total;
			$ultimoNumeroNormal++;
			$ultimoNumeroNormal = str_pad($ultimoNumeroNormal, 3, '0', STR_PAD_LEFT);
			$senha = 'RN' . $ultimoNumeroNormal;
			$fields['senha'] = $senha;
		}


		if ($this->request->getPost('codPaciente') == NULL or $this->request->getPost('codPaciente') == '') {
			$codPaciente = NULL;
		} else {
			$codPaciente = $this->request->getPost('codPaciente');
		}


		if ($this->request->getPost('nomePaciente') == NULL or $this->request->getPost('nomePaciente') == '') {
			$nomePaciente = NULL;
		} else {
			$nomePaciente = $this->request->getPost('nomePaciente');
		}

		if ($this->request->getPost('idade') == NULL or $this->request->getPost('idade') == '') {
			$idade = NULL;
		} else {
			$idade = $this->request->getPost('idade');
		}


		if ($this->request->getPost('fotoPerfil') == NULL or $this->request->getPost('fotoPerfil') == '') {
			$fotoPerfil = NULL;
		} else {
			$fotoPerfil = $this->request->getPost('fotoPerfil');
		}


		$fields['protocolo'] = date('Y') . date('m') . date('d') . geraNumero(6);
		$fields['dataProtocolo'] = date('Y-m-d H:i');
		$fields['codPaciente'] = $codPaciente;
		$fields['nomePaciente'] = $nomePaciente;
		$fields['codDepartamento'] = session()->codDepartamentoAtendimento;
		$fields['codLocalAtendimento'] = NULL;
		$fields['idade'] = $idade;
		$fields['cpf'] = $this->request->getPost('cpf');
		$fields['fotoPerfil'] = $fotoPerfil;
		$fields['dataInicio'] = NULL;
		$fields['codOrganizacao'] = session()->codOrganizacao;
		$fields['dataEncerramento'] = NULL;
		$fields['codStatus'] = 0;
		$fields['codTipoFila'] = 2;

		$this->validation->setRules([
			'protocolo' => ['label' => 'Protocolo', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($codSenhaAtendimento = $this->AtendimentoSenhasModel->insert($fields)) {

				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['codSenhaAtendimento'] = $codSenhaAtendimento;
				$response['senha'] = $fields['senha'];
				$response['protocolo'] = $fields['protocolo'];
				$response['data'] = date('d/m/d H:i', strtotime($fields['dataProtocolo']));
				$response['messages'] = 'Informação inserida com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na inserção!';
			}
		}

		return $this->response->setJSON($response);
	}
	public function add()
	{

		$response = array();

		$fields['codSenhaAtendimento'] = $this->request->getPost('codSenhaAtendimento');
		$fields['protocolo'] = $this->request->getPost('protocolo');
		$fields['codPaciente'] = $this->request->getPost('codPaciente');
		$fields['cpf'] = $this->request->getPost('cpf');
		$fields['senha'] = $this->request->getPost('senha');
		$fields['codPrioridade'] = $this->request->getPost('codPrioridade');
		$fields['dataInicio'] = $this->request->getPost('dataInicio');
		$fields['codStatus'] = $this->request->getPost('codStatus');
		$fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');


		$this->validation->setRules([
			'protocolo' => ['label' => 'Protocolo', 'rules' => 'required|max_length[16]'],
			'codPaciente' => ['label' => 'Pessoa', 'rules' => 'required|numeric|max_length[11]'],
			'cpf' => ['label' => 'CPF', 'rules' => 'required|numeric|max_length[11]'],
			'senha' => ['label' => 'Senha', 'rules' => 'required|max_length[4]'],
			'codPrioridade' => ['label' => 'CodPrioridade', 'rules' => 'required|numeric|max_length[11]'],
			'dataInicio' => ['label' => 'Data Início', 'rules' => 'required|numeric|max_length[11]'],
			'codStatus' => ['label' => 'CodStatus', 'rules' => 'required|numeric|max_length[11]'],
			'dataEncerramento' => ['label' => 'Data Encerramento', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtendimentoSenhasModel->insert($fields)) {

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


	public function gravarSala()
	{
		$codDepartamentoAtendimento = $this->request->getPost('codDepartamento');
		$codLocalAtendimento = $this->request->getPost('codLocalAtendimento');
		$localAtendimento = $this->AtendimentoslocaisModel->pegaPorCodigo($codLocalAtendimento);


		$response['success'] = true;
		$response['csrf_hash'] = csrf_hash();
		$response['nomeLocalAtendimento'] = session()->nomeLocalAtendimento = $localAtendimento->descricaoLocalAtendimento;
		$response['codLocalAtendimento'] = session()->codLocalAtendimento = $localAtendimento->codLocalAtendimento;
		$response['codDepartamentoAtendimento'] = session()->codDepartamentoAtendimento = $codDepartamentoAtendimento;
		return $this->response->setJSON($response);
	}


	public function edit()
	{

		$response = array();

		$fields['codSenhaAtendimento'] = $this->request->getPost('codSenhaAtendimento');
		$fields['protocolo'] = $this->request->getPost('protocolo');
		$fields['codPaciente'] = $this->request->getPost('codPaciente');
		$fields['cpf'] = $this->request->getPost('cpf');
		$fields['senha'] = $this->request->getPost('senha');
		$fields['codPrioridade'] = $this->request->getPost('codPrioridade');
		$fields['dataInicio'] = $this->request->getPost('dataInicio');
		$fields['codStatus'] = $this->request->getPost('codStatus');
		$fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');


		$this->validation->setRules([
			'codSenhaAtendimento' => ['label' => 'codSenhaAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'protocolo' => ['label' => 'Protocolo', 'rules' => 'required|max_length[16]'],
			'codPaciente' => ['label' => 'Pessoa', 'rules' => 'required|numeric|max_length[11]'],
			'cpf' => ['label' => 'CPF', 'rules' => 'required|numeric|max_length[11]'],
			'senha' => ['label' => 'Senha', 'rules' => 'required|max_length[4]'],
			'codPrioridade' => ['label' => 'CodPrioridade', 'rules' => 'required|numeric|max_length[11]'],
			'dataInicio' => ['label' => 'Data Início', 'rules' => 'required|numeric|max_length[11]'],
			'codStatus' => ['label' => 'CodStatus', 'rules' => 'required|numeric|max_length[11]'],
			'dataEncerramento' => ['label' => 'Data Encerramento', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtendimentoSenhasModel->update($fields['codSenhaAtendimento'], $fields)) {

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

		$id = $this->request->getPost('codSenhaAtendimento');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->AtendimentoSenhasModel->where('codSenhaAtendimento', $id)->delete()) {

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
