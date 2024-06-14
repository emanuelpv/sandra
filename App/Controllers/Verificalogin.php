<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\OrganizacoesModel;
use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\PacientesModel;
use App\Models\CargosModel;
use App\Models\ConfiguracaoGlobalModel;
use CodeIgniter\HTTP\IncomingRequest;
use App\Models\ModulosModel;
use App\Models\PerfilPessoasMembroModel;
use App\Models\ServicoLDAPModel;
use App\Models\MapeamentoAtributosLDAPModel;
use App\Models\EspecialidadesMembroModel;
use App\Models\SolicitacoesSuporteModel;

class Verificalogin extends BaseController
{
	protected $usuariosModel;
	protected $validation;
	protected $organizacao;
	protected $CargosModel;
	protected $ServicoLDAPModel;
	protected $OrganizacoesModel;
	protected $ConfiguracaoGlobal;
	protected $PerfilPessoasMembroModel;
	protected $MapeamentoAtributosLDAPModel;
	protected $EspecialidadesMembroModel;
	protected $LogsModel;
	protected $PessoasModel;
	protected $PacientesModel;
	protected $SolicitacoesSuporteModel;

	public function __construct()
	{
		$this->PerfilPessoasMembroModel = new PerfilPessoasMembroModel();
		$this->MapeamentoAtributosLDAPModel = new MapeamentoAtributosLDAPModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->CargosModel = new CargosModel();
		$this->ConfiguracaoGlobal = new ConfiguracaoGlobalModel();
		$this->EspecialidadesMembroModel = new EspecialidadesMembroModel();
		$this->LogsModel = new LogsModel();
		$this->PessoasModel = new PessoasModel();
		$this->PacientesModel = new PacientesModel();
		$this->ServicoLDAPModel = new ServicoLDAPModel();
		$this->SolicitacoesSuporteModel = new SolicitacoesSuporteModel();

		//NÃO REMOVER POIS TEM QUE SER DEFINIDA ANTES DO SELECT
		$configuracao = config('App');
		session()->set('codOrganizacao', $configuracao->codOrganizacao);
		session()->set('ambienteTeste', $configuracao->ambienteTeste);

		$this->ModulosModel = new ModulosModel();
		$Modulos = $this->ModulosModel->pegaTudo();
		$Cargos = $this->CargosModel->pegaCargos();
		session()->set('cargos', $Cargos);
		session()->set('modulos', $Modulos);
		$this->validation =  \Config\Services::validation();
	}
	public function index()
	{
		//session()->setFlashdata('mensagem', 'Login ou senha incorreta, tente novamente!');


		$request = service('request');

		helper(['form', 'url']);


		if ($this->verificaCredenciais() == 1 and session()->localizador == NULL) {
			return redirect()->to(base_url() . '/principal/?autorizacao=' . session()->autorizacao);
		}


		if ($this->verificaCredenciais() == 1 and session()->localizador == 1) {

			if (session()->codAtendimentoPrescricao !== '') {
				return redirect()->to(base_url() . '/localizador/prescricao/' . session()->codAtendimentoPrescricao);
			} else {
				return redirect()->to(base_url() . '/localizador/');
			}
		}

		if ($this->verificaCredenciais() !== 1 and session()->localizador == 1) {

			if (session()->codAtendimentoPrescricao !== '') {
				session()->setFlashdata('mensagem_erro', 'Falha login! Verifique seu login ou senha');
				return redirect()->to(base_url() . '/localizador/prescricao/' . session()->codAtendimentoPrescricao);
			} else {
				session()->setFlashdata('mensagem_erro', 'Falha login! Verifique seu login ou senha');
				return redirect()->to(base_url() . '/localizador/');
			}
		}


		$data['organizacao'] = $this->OrganizacoesModel->pegaOrganizacoes();
		if (session()->getFlashdata('mensagemPerfilInexistente') == 1) {
			session()->setFlashdata('mensagem', 'Não há perfil associado ao seu usuário. Contate o administrador do sistema');
		}
		if (session()->getFlashdata('mensagemLoginInexistente') == 1) {
			//session()->setFlashdata('mensagem', 'Conta Inexistente');
			session()->setFlashdata('mensagem', 'Falha login! Verifique seu login ou senha');
			session()->setFlashdata('mensagem_erro', 'Falha login! Verifique seu login ou senha');
		}
		if (session()->getFlashdata('mensagemSenhaErrada') == 1) {
			//session()->setFlashdata('mensagem', 'Senha não confere');
			session()->setFlashdata('mensagem', 'Falha login! Verifique seu login ou senha');
			session()->setFlashdata('mensagem_erro', 'Falha login! Verifique seu login ou senha');
		}
		if (session()->getFlashdata('mensagemFalhaLoginLDAP') == 1) {
			session()->setFlashdata('mensagem', 'Falha login! Verifique seu login ou senha');
			session()->setFlashdata('mensagem_erro', 'Falha login! Verifique seu login ou senha');
		}
		if (session()->getFlashdata('mensagemFalhaLoginADMIN') == 1) {
			//session()->setFlashdata('mensagem', 'Falha login do ADMIN! Verifique seu login ou senha');
			session()->setFlashdata('mensagem', 'Falha login! Verifique seu login ou senha');
			session()->setFlashdata('mensagem_erro', 'Falha login! Verifique seu login ou senha');
		}
		if (session()->getFlashdata('mensagemFalhaFormatoLoginSenha') == 1) {
			//session()->setFlashdata('mensagem', 'Falha login do ADMIN! Verifique seu login ou senha');
			session()->setFlashdata('mensagem', 'Falha login! Verifique seu login ou senha');
			session()->setFlashdata('mensagem_erro', 'Falha login! Verifique seu login ou senha');
		}
		if (session()->getFlashdata('mensagemLoginBloqueado') == 1) {
			session()->setFlashdata('mensagem', 'Cadastro desatualizado. Procure o setor de marcações!');
		}
		if (!empty(session()->getFlashdata('mensagemPerfilExpirado'))) {
			session()->setFlashdata('mensagem', session()->getFlashdata('mensagemPerfilExpirado'));
		}




		echo view('tema_home/cabecalho');
		echo view('tema_home/menu_horizontal');
		// echo view('tema_home/menu_vertical');
		// 
		echo view('login', $data);
		echo view('tema_home/rodape');
	}



	function verificaCredenciais()
	{

		//INSTANCIA POST/GET FORMS
		$request = service('request');


		//PEGA DADOS


		$login = mb_strtolower(rtrim(ltrim($request->getPost('login'))));

		if (!strpos($login, '@')) {
			$login = removeCaracteresIndesejados($login);
		}


		$senha = rtrim(ltrim($request->getPost('senha')));
		$perfilLogin = rtrim(ltrim($request->getPost('perfilLogin')));




		//LOCALIZADOR

		session()->set('localizador', NULL);
		session()->set('codAtendimentoPrescricao', NULL);
		session()->set('checksum', NULL);

		session()->set('localizador', $request->getPost('localizador'));
		session()->set('codAtendimentoPrescricao', $request->getPost('codAtendimentoPrescricao'));
		session()->set('checksum', $request->getPost('checksum'));


		//VALIDA FORMATO LOGIN/SENHA

		$dadoslogin['login'] = mb_strtolower(rtrim(ltrim($request->getPost('login'))));
		$dadoslogin['senha'] = rtrim(ltrim($request->getPost('senha')));

		$this->validation->setRules([
			'login' => ['label' => 'login', 'rules' => 'required|bloquearReservado'],
			'senha' => ['label' => 'senha', 'rules' => 'required|bloquearReservado'],

		]);

		if ($this->validation->run($dadoslogin) == FALSE) {

			session()->setFlashdata('mensagemFalhaFormatoLoginSenha', 1);
			return 0;
		}

		//NÃO REMOVER POIS TEM QUE SER DEFINIDA ANTES DO SELECT
		$configuracao = config('App');
		session()->set('codOrganizacao', $configuracao->codOrganizacao);
		$codOrganizacao = $configuracao->codOrganizacao;

		$organizacao = $this->OrganizacoesModel->pegaOrganizacao($codOrganizacao);


		// P A C I E N T E

		if ($perfilLogin == 1) {
			//$login = removeCaracteresIndesejados($login);


			$pessoa = $this->PacientesModel->pegaPacientePorLogin($login);

			if ($pessoa == NULL) {
				session()->setFlashdata('mensagemLoginInexistente', 1);
				return 0;
			}


			//NÃO DEIXA BLOQUEADOS LOGAR
			if ($pessoa->codStatusCadastroPaciente == 3) {
				session()->setFlashdata('mensagemLoginBloqueado', 1);
				return 0;
			}


			//VERIFICA SE ANIVERSARIANTE
			if (date('m-d', strtotime($pessoa->dataNascimento)) == date('m-d')) {
				session()->set('aniversariante', 1);
			} else {
				session()->set('aniversariante', 0);
			}



			//VERIFICA PERFILS DE ACESSO DE PACIENTES
			if ($this->verificaPerfisPacientes() == 0) {

				session()->setFlashdata('mensagemPerfilInexistente', 1);
				return 0;
			}

			/*  #############    AUTENTICAÇÃO PADRÃO  ##################   */



			//VERIFICA SENHA
			$senha = hash("sha256",  $senha . $organizacao->chaveSalgada);

			if ($pessoa->senha == $senha) {
				$this->variaveisSessao($codOrganizacao, $login, $organizacao, $pessoa);
				//LOG DE OUDITORIA
				$this->LogsModel->inserirLogPaciente('Login Sucesso', $pessoa->codPaciente);



				return 1;
			} else {
				$this->LogsModel->inserirLogPaciente('Login falha', $pessoa->codPaciente);
				session()->setFlashdata('mensagemSenhaErrada', 1);

				return 0;
			}
		}


		if ($perfilLogin == 2) {




			//LOGIN COM ADMIN
			if ($login == 'admin') {

				if ($this->loginAdmin($login, $senha, $organizacao) == 0) {
					session()->setFlashdata('mensagemFalhaLoginADMIN', 1);
					return 0;
				} else {

					$this->variaveisSessao($codOrganizacao, $login, $organizacao, $pessoa);
					session()->set('nomeCompleto', 'Administrador');
					session()->set('nomeExibicao', 'Administrador');
					session()->set('codPessoa', 0);

					return 1;
				}
			}




			//PEGA DADOS DE DE PESSOA
			$pessoa = $this->PessoasModel->pegaPessoaPorLogin($login);

			if ($pessoa == NULL) {
				session()->setFlashdata('mensagemLoginInexistente', 1);
				return 0;
			}


			if ($pessoa == NULL and $login !== 'admin') {
				session()->setFlashdata('mensagemLoginInexistente', 1);
				return false;
			}


			//VERIFICA SE ANIVERSARIANTE
			if (date('m-d', strtotime($pessoa->dataNascimento)) == date('m-d')) {
				session()->set('aniversariante', 1);
			} else {
				session()->set('aniversariante', 0);
			}


			//ANIVERSARIANTES

			$aniversariantes = $this->PessoasModel->aniversariantesHoje();
			session()->set('aniversariantes', $aniversariantes);


			//VERIFICA PERFILS DE ACESSO DE PESSOAS
			if ($this->verificaPerfisPessoas($organizacao, $pessoa) == 0) {
				session()->setFlashdata('mensagemPerfilInexistente', 1);
				return 0;
			}



			/*  #############    AUTENTICAÇÃO PADRÃO  ##################   */



			//VERIFICA SENHA
			$senha = hash("sha256",  $senha . $organizacao->chaveSalgada);


			if ($pessoa->senha == $senha) {
				$this->variaveisSessao($codOrganizacao, $login, $organizacao, $pessoa);
				//LOG DE OUDITORIA
				$this->LogsModel->inserirLog('Login Sucesso', $pessoa->codPessoa);


				//VERIFICA SE MÉDICO/DENTISTA

				$especialidades = $this->EspecialidadesMembroModel->pegaEspecialidadesPorCodMembro($pessoa->codPessoa);
				session()->minhasEspecialidades = $especialidades;

				$filtro["codEspecialidade"] = null;
				$filtro["codEspecialista"] = $pessoa->codPessoa;
				$filtro["dataInicio"] = date('Y-m-d H:i');
				$filtro["dataEncerramento"] = date('Y-m-d H:i');

				session()->set('filtroEspecialidade', $filtro);

				return 1;
			} else {


				//VERIFICA CONTA SE INTEGRAÇÃO LDAP

				if (!empty($this->ServicoLDAPModel->pegaTudoAtivo())) {
					if ($this->loginLDAP($login, $senha, $organizacao) == 0) {
						session()->setFlashdata('mensagemFalhaLoginLDAP', 1);
						return 0;
					} else {
						$pessoa = $this->PessoasModel->pegaPessoaPorLogin($login); //NÃO REMOVER, POIS PEGA NOVAMENTE OS DADOS DA PESSOA
						$this->variaveisSessao($codOrganizacao, $login, $organizacao, $pessoa);

						if ($this->verificaPerfisPessoas($organizacao, $pessoa) == 0) {
							session()->setFlashdata('mensagemPerfilInexistente', 1);
							return 0;
						}

						//VERIFICA SE MÉDICO/DENTISTA
						$especialidades = $this->EspecialidadesMembroModel->pegaEspecialidadesPorCodMembro($pessoa->codPessoa);
						session()->minhasEspecialidades = $especialidades;


						$filtro["codEspecialidade"] = null;
						$filtro["codEspecialista"] = $pessoa->codPessoa;
						$filtro["dataInicio"] = date('Y-m-d');
						$filtro["dataEncerramento"] = date('Y-m-d');

						session()->set('filtroEspecialidade', $filtro);

						return 1;
					}
				} else {

					$this->LogsModel->inserirLog('Login falha', $pessoa->codPessoa);
					session()->setFlashdata('mensagemSenhaErrada', 1);

					return 0;
				}
			}
		}
	}


	public function emissaoCartao()
	{
		$response = array();


		$id = $this->request->getPost('codPaciente');
		$organizacao =  $this->OrganizacoesModel->pegaOrganizacao(session()->codOrganizacao);


		if ($this->validation->check($id, 'required|numeric')) {
			$data = array();
			$paciente = $this->PacientesModel->emissaoCartao($id);
			$data['nomeCompleto'] = $paciente->nomeCompleto;
			$data['codPaciente'] = $paciente->codPaciente;
			$data['valorChecksum'] = MD5($paciente->codPaciente . $organizacao->chaveSalgada);
			$data['fotoPerfil'] = $paciente->fotoPerfil;
			$data['cpf'] = $paciente->cpf;
			$data['nomeTipoBeneficiario'] = $paciente->nomeTipoBeneficiario;
			$data['descricaoCargo'] = $paciente->descricaoCargo;
			$data['codProntuario'] = $paciente->codProntuario;
			$data['codProntuario'] = $paciente->codProntuario;
			$data['validadeProntuario'] = $paciente->validadeProntuario;
			$data['responsavel'] = getNomeExibicaoPessoa($this, session()->codPessoa);
			$data['dataEmissao'] = date('d/m/Y H:i');

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	function verificaPerfisPacientes()
	{

		$meusPerfisValidos = $this->PerfilPessoasMembroModel->pegaMeusPerfisValidosPacientes();
		$todosMeusPerfis = $this->PerfilPessoasMembroModel->pegaMeusPerfisPacientes();

		//reexecutar consulta dos meus perfis
		$meusModulos = $this->PerfilPessoasMembroModel->pegaMinhasPermissoesModulosPacientes();

		session()->set('perfilSessao', 9);
		session()->set('meusPerfis', $meusPerfisValidos);
		session()->set('meusModulos', $meusModulos);
		return 1;
	}

	function verificaPerfisPessoas($organizacao, $pessoa)
	{

		//meus perfis
		$meusPerfisValidos = $this->PerfilPessoasMembroModel->pegaMeusPerfisValidos($pessoa->codPessoa);
		$todosMeusPerfis = $this->PerfilPessoasMembroModel->pegaMeusPerfis($pessoa->codPessoa);


		if (empty($todosMeusPerfis)) {
			//NUNCA HOUVE PERFIL ASSOSSIADO

			if ($organizacao->codPerfilPadrao > 0) {
				//atualiza pessoa
				$perfilPadrao['codPerfilPadrao'] = $organizacao->codPerfilPadrao;
				$db      = \Config\Database::connect();
				$builderPessoa = $db->table('sis_pessoas');
				$builderPessoa->where('codPessoa', $pessoa->codPessoa);
				$builderPessoa->update($perfilPadrao);

				//insere pessoa no perfil


				$dadosmembroPerfil['codPerfil'] = $organizacao->codPerfilPadrao;
				$dadosmembroPerfil['codPessoa'] = $pessoa->codPessoa;
				$dadosmembroPerfil['dataCriacao'] = date('Y-m-d H:i');
				$dadosmembroPerfil['dataAtualizacao'] = date('Y-m-d H:i');
				$dadosmembroPerfil['dataInicio'] = date('Y-m-d');
				$builderMembroPerfil = $db->table('sis_perfispessoasmembro');
				$builderMembroPerfil->insert($dadosmembroPerfil);

				//reexecutar consulta dos meus perfis
				$meusPerfisValidos = $this->PerfilPessoasMembroModel->pegaMeusPerfisValidos($pessoa->codPessoa);
				$todosMeusPerfis = $this->PerfilPessoasMembroModel->pegaMeusPerfis($pessoa->codPessoa);
				$meusModulos = $this->PerfilPessoasMembroModel->pegaMinhasPermissoesModulos($pessoa->codPessoa, $organizacao->codPerfilPadrao);
				session()->set('perfilSessao', $organizacao->codPerfilPadrao);
				session()->set('meusPerfis', $meusPerfisValidos);
				session()->set('meusModulos', $meusModulos);
				return 1;
			} else {

				session()->setFlashdata('mensagemPerfilInexistente', 1);
				return 0;
			}
		} else {

			// TEM OU TEVE PERFIL

			$validos = 0;
			$expirados = 0;
			$perfilPadrao = -1;
			foreach ($todosMeusPerfis as $meuperfil) {
				if ($meuperfil->dataEncerramento !== NULL and $meuperfil->dataEncerramento < date('Y-m-d')) {

					$expirados++;
				} else {
					$validos++;
					if ($pessoa->codPerfilPadrao == $meuperfil->codPerfil) {
						$perfilPadrao = $meuperfil->codPerfil;
					} else {
						$perfilNaoPadrao =  $meuperfil->codPerfil;
					}
				}
			}
			if ($validos == 0 and $expirados > 0) {
				session()->setFlashdata('mensagemPerfilExpirado', 'Sua conta estava associada ao perfil "' . $meuperfil->descricao . '" que expirou em ' . date('d/m/Y', strtotime($meuperfil->dataEncerramento)) . '.');
				return 0;
			}
			if ($validos > 0) {
				if ($perfilPadrao !== -1) {
					$codPerfil = $perfilPadrao;
				} else {
					$codPerfil = $perfilNaoPadrao;
				}
			}


			$meusModulos = $this->PerfilPessoasMembroModel->pegaMinhasPermissoesModulos($pessoa->codPessoa, $codPerfil);
			session()->set('perfilSessao', $codPerfil);
			session()->set('meusPerfis', $meusPerfisValidos);
			session()->set('meusModulos', $meusModulos);
			return 1;
			//print_r(session()->meusModulos);
			//exit();
		}
	}


	function verificaSeAdmin()
	{

		print_r(session()->meusPerfis);
		exit();
		foreach (session()->meusPerfis as $perfil) {
			if ($perfil->descricao == 'Administrador') {
				return 1;
			}
		}
		return 0;
	}
	function variaveisSessao($codOrganizacao, $login, $organizacao, $pessoa)
	{

		//SET TIMEZONE
		$timezone = $this->OrganizacoesModel->pegaTimezoneOrganizacao($codOrganizacao);

		if ($timezone == NULL) {
			$timezone = 'America/Sao_Paulo';
		} else {
			$timezone = $timezone->nome;
		}

		date_default_timezone_set($timezone);


		//  ****  VARIÁVEIS ****

		$perfis = $this->PerfilPessoasMembroModel->pegaMeusPerfisValidos($pessoa->codPessoa);

		foreach ($perfis as $perfil) {
			if ($perfil->descricao == 'Administrador') {
				session()->set('perfilAdmin', 1);
				break;
			} else {
				session()->set('perfilAdmin', 0);
			}
		}

		$equipesTecnicas = $this->SolicitacoesSuporteModel->equipesTecnicasPorPessoa($pessoa->codPessoa);

		session()->set('equipesTecnicas', $equipesTecnicas);



		if ($pessoa->codPaciente !== NULL) {
			session()->set('codPaciente', $pessoa->codPaciente);
			//SEGURANÇA
			session()->set('autorizacao', md5($pessoa->codPaciente) . md5(time()));
		} else {
			//SEGURANÇA
			session()->set('autorizacao', md5($pessoa->codPessoa) . md5(time()));
		}

		session()->set('estaLogado', 1);
		session()->set('ip', pegaIP());
		session()->set('logo', $organizacao->logo);
		session()->set('codOrganizacao', $organizacao->codOrganizacao);
		session()->set('descricaoOrganizacao', $organizacao->descricao);
		session()->set('cabecalhoOficios', $organizacao->cabecalho);
		session()->set('rodapeOficios', $organizacao->rodape);
		session()->set('cabecalhoPrescricao', $organizacao->cabecalhoPrescricao);
		session()->set('rodapePrescricao', $organizacao->rodapePrescricao);
		session()->set('siglaOrganizacao', $organizacao->siglaOrganizacao);
		session()->set('telefoneOrganizacao', $organizacao->telefone);
		session()->set('fundo', $organizacao->fundo);
		session()->set('codTimezone', $organizacao->codTimezone);
		session()->set('timezone', $timezone);
		session()->set('codPessoa', $pessoa->codPessoa);
		session()->set('codPerfil', $pessoa->codPerfilPadrao);
		session()->set('permissoes', null);
		session()->set('login', $login);
		session()->set('nomeCompleto', $pessoa->nomeCompleto);
		session()->set('nomeExibicao', $pessoa->nomeExibicao);
		session()->set('codPlano', $pessoa->codPlano);
		session()->set('emailPessoal', $pessoa->emailPessoal);
		session()->set('cpf', $pessoa->cpf);
		session()->set('fotoPerfil', $pessoa->fotoPerfil);
		session()->set('codDepartamento', $pessoa->codDepartamento);
		session()->set('tempoInatividade', $organizacao->tempoInatividade);
		session()->set('tempoInatividade_em', date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " +" . $organizacao->tempoInatividade . " minutes")));
		session()->set('forcarExpiracao', $organizacao->forcarExpiracao);
		session()->set('forcarExpiracao_em', date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " +" . $organizacao->forcarExpiracao . " minutes")));
		session()->set('dt_login', date("Y-m-d H:i:s"));
		session()->set('politicaSenha', $organizacao->politicaSenha);
		session()->set('senhaNaoSimples', $organizacao->senhaNaoSimples);
		session()->set('numeros', $organizacao->numeros);
		session()->set('letras', $organizacao->letras);
		session()->set('caracteresEspeciais', $organizacao->caracteresEspeciais);
		session()->set('minimoCaracteres', $organizacao->minimoCaracteres);
		session()->set('maiusculo', $organizacao->maiusculo);
		session()->set('nomeExibicaoSistema', $organizacao->nomeExibicaoSistema);
		session()->set('cidade', $organizacao->cidade);
		session()->set('endereço', $organizacao->endereço);
		session()->set('telefone', $organizacao->telefone);
		session()->set('cep', $organizacao->cep);
		session()->set('uf', $organizacao->siglaEstadoFederacao);
	}



	function loginLDAP($login, $senha, $organizacao)
	{
		$servidoresLDAP = $this->ServicoLDAPModel->pegaTudoAtivo();

		foreach ($servidoresLDAP as $servidorLDAP) {

			$loginLDAP = $this->ServicoLDAPModel->conectaldap($login, $senha, $servidorLDAP->codServidorLDAP);


			if ($loginLDAP['status'] == 1) {

				$dadosLdapPessoa = $this->ServicoLDAPModel->pegaPessoas($loginLDAP['tipoldap'], $orderby = 'sn', $login);

				//print_r($dadosLdapPessoa); exit();
				$pessoa = $this->PessoasModel->pegaPessoaPorLogin($login);
				if ($pessoa == NULL) {

					$atributosMapeados = $this->MapeamentoAtributosLDAPModel->pegaAtributosMapeados($loginLDAP['codServidorLDAP']);

					foreach ($atributosMapeados as $atributos) {
						if (is_array($dadosLdapPessoa[0][$atributos->nomeAtributoLDAP])) {
							$dados[$atributos->nomeAtributoSistema] =  $dadosLdapPessoa[0][$atributos->nomeAtributoLDAP][0];
						} else {
							$dados[$atributos->nomeAtributoSistema] =  $dadosLdapPessoa[0][$atributos->nomeAtributoLDAP];
						}
					}

					$dados['codOrganizacao'] =  session()->codOrganizacao;
					$dados['dataCriacao'] = date('Y-m-d H:i:s');
					$dados['dataAtualizacao'] = date('Y-m-d H:i:s');
					$dados['ativo'] = 1;
					$dados['aceiteTermos'] = 1;
					$dados['senha'] = hash("sha256",  $senha . $organizacao->chaveSalgada);


					$this->PessoasModel->insert($dados);
				}
				return 1;
			} else {
				//return 0;
			}
		}
		return 0;
	}
	function loginAdmin($login, $senha, $organizacao)
	{

		//é o admin

		session()->set('meusPerfis', array());
		session()->set('meusModulos', array());




		if (hash("sha256", $senha) == $organizacao->senhaAdmin) {


			//  ****  VARIÁVEIS ****

			session()->set('nomeCompleto', 'Administrador');
			session()->set('nomeExibicao', 'Administrador');

			//LOG DE OUDITORIA
			$this->LogsModel->inserirLog('Login Sucesso', 0);
			return 1;
		} else {

			//LOG DE OUDITORIA
			$this->LogsModel->inserirLog('Login falha', 0);
			session()->setFlashdata('mensagemSenhaErrada', 1);
			return 0;
		}
	}


	function verificaForcaExpiracao()
	{


		//FORÇA TERMINO DE SESSAO
		if ((int)session()->forcarExpiracao === 0) {
			echo json_encode(array('status' => 0));
		} else {
			if (date('Y-m-d H:i:s', strtotime(session()->forcarExpiracao_em)) >= date('Y-m-d H:i:s')) {
				echo json_encode(array('status' => 0));
			} else {
				echo json_encode(array('status' => 1));
			}
		}
	}


	public function verificaSTempoInatividade()
	{
		//EXPIRA SEÇÃO POR TEMPO DE INATIVIDADE
		if ((int)session()->tempoInatividade == 0) {
			echo json_encode(array('status' => 0));
		} else {
			if (session()->tempoInatividade_em >= date('Y-m-d H:i:s')) {
				echo json_encode(array('status' => 0));
			} else {
				echo json_encode(array('status' => 1));
			}
		}
	}

	public function verificaSeLogado()
	{
		$response = array();

		if (session()->estaLogado !== NULL) {
			//está logado
			$response['success'] = true;
		} else {
			//não está logado
			$response['success'] = false;
		}


		return $this->response->setJSON($response);
	}

	public function pegaCargos()
	{
		$response = array();


		//INSTANCIA POST/GET FORMS
		$request = service('request');


		//PEGA DADOS
		$codCargo = $request->getPost('codCargo');

		if (is_numeric($codCargo)) {

			$data = $this->CargosModel->pegaCargosPorCodigo($codCargo);

			return json_encode($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function pegaOrganizacao()
	{
		//INSTANCIA POST/GET FORMS
		$request = service('request');


		//PEGA DADOS
		$codOrganizacao = $request->getPost('codOrganizacao');

		$response = array();

		if (is_numeric($codOrganizacao)) {

			$data = $this->OrganizacoesModel->pegaOrganizacao($codOrganizacao);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}
	public function verificacaoConfirmacoes($cpf = null)
	{
		$response = array();

		//VALIDAR ENTRADAS
		$validacao['cpf'] = $this->request->getPost('cpf');
		$validacao['identidade'] = $this->request->getPost('identidadeValidacao');
		$validacao['email'] = $this->request->getPost('emailPessoalValidacao');
		$validacao['celular'] = $this->request->getPost('celularValidacao');
		$validacao['codPlano'] = $this->request->getPost('codPlanoValidacao');
		$validacao['nomeMae'] = $this->request->getPost('nomeMaeValidacao');
		$validacao['dataNascimento'] = $this->request->getPost('dataNascimentoValidacao');


		$this->validation->setRules([
			'cpf' => ['label' => 'CPF', 'rules' => 'required|bloquearReservado|numeric|max_length[14]'],
			'identidade' => ['label' => 'Identidade', 'rules' => 'permit_empty|bloquearReservado|numeric|max_length[3]'],
			'email' => ['label' => 'E-mail', 'rules' => 'permit_empty|bloquearReservado|valid_email|max_length[40]'],
			'celular' => ['label' => 'Celular', 'rules' => 'permit_empty|bloquearReservado|numeric|max_length[4]'],
			'codPlano' => ['label' => 'Código Beneficiário', 'rules' => 'required|bloquearReservado|numeric|max_length[3]'],
			'nomeMae' => ['label' => 'Nome da Mae', 'rules' => 'required|bloquearReservado|max_length[20]'],

		]);

		if ($this->validation->run($validacao) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {
			//LOGICA AQUI



			if ($cpf !== NULL) {
				$cpf = $cpf;
			} else {
				$cpf = $this->request->getPost('cpf');
			}


			$codOrganizacao = $this->request->getPost('codOrganizacao');
			session()->set('codOrganizacao', $codOrganizacao);

			$codPerfil = $this->request->getPost('codPerfil');
			$cpf = removeCaracteresIndesejados($cpf);


			$pessoa = $this->PacientesModel->pegaPacientePorcpf($cpf);

			if ($this->request->getPost('emailPessoalNovos') !== NULL) {
				$atualiza["emailPessoal"] = $this->request->getPost('emailPessoalNovos');
			}
			if ($this->request->getPost('celularNovos') !== NULL) {
				$atualiza["celular"] = $this->request->getPost('celularNovos');
			}

			if ($this->request->getPost('emailPessoalNovos') !== NULL or $this->request->getPost('celularNovos') !== NULL) {
				$this->PacientesModel->update($pessoa->codPaciente, $atualiza);
			}


			$tudoOK = 1;
			if ($pessoa !== NULL) {

				$dados = array();


				if ($pessoa->nomeCompleto !== NULL and strpos($pessoa->nomeCompleto, '***') === false) {
					//$dados['nomeCompleto'] = $pessoa->nomeCompleto;
				}
				if ($pessoa->identidade !== NULL) {
					//Primeiros 3 números do Identidade

					$dados['identidade'] = mb_substr($pessoa->identidade, 0, 3);
					if ($this->request->getPost('identidadeValidacao')) {
						if (mb_substr($this->request->getPost('identidadeValidacao'), 0, 3) == $dados['identidade']) {
						} else {
							$tudoOK = 0;
						}
					}
				}
				if ($pessoa->emailPessoal !== NULL and strpos($pessoa->emailPessoal, '***') === false) {

					//informe seu email

					$emailPessoal = explode("@", $pessoa->emailPessoal);
					$emailPessoal = mb_substr($emailPessoal[0], 0, round(strlen($emailPessoal[0]) / 2)) . '****@' . mb_substr($emailPessoal[1], 0, 3) . '*******';

					$dados['emailPessoal'] = $pessoa->emailPessoal;

					if ($this->request->getPost('emailPessoalValidacao')) {
						if (mb_strtolower($this->request->getPost('emailPessoalValidacao'), "utf-8") == mb_strtolower($dados['emailPessoal'], "utf-8")) {
						} else {
							$tudoOK = 0;
						}
					}
				}


				if ($pessoa->celular !== NULL and $pessoa->celular !== "" and strpos($pessoa->celular, '***') === false) {
					//Primeiros 4 números do CELULAR
					$dados['celular'] = mb_substr($pessoa->celular, -4);
					if ($this->request->getPost('celularValidacao')) {
						if (mb_strtolower($this->request->getPost('celularValidacao'), "utf-8") == mb_strtolower($dados['celular'], "utf-8")) {
						} else {
							$tudoOK = 0;
						}
					}
				}

				if ($pessoa->codPlano !== NULL and $pessoa->codPlano !== "" and strpos($pessoa->codPlano, '***') === false) {
					//ultimos 3 números do CadBEM /codplano
					$dados['codPlano'] =  mb_substr($pessoa->codPlano, 0, 3);
					if ($this->request->getPost('codPlanoValidacao')) {
						if (mb_strtolower($this->request->getPost('codPlanoValidacao'), "utf-8") == mb_strtolower($dados['codPlano'], "utf-8")) {
						} else {
							$tudoOK = 0;
						}
					}
				}
				if ($pessoa->nomeMae !== NULL and $pessoa->nomeMae !== "" and strpos($pessoa->nomeMae, '***') === false) {
					//PRIMEIRO NOME DA MAE
					$nomeMae = explode(" ", $pessoa->nomeMae);
					$dados['nomeMae'] = $nomeMae[0];
					if ($this->request->getPost('nomeMaeValidacao')) {
						if (trim(mb_strtolower(removeAcentos($this->request->getPost('nomeMaeValidacao')), "utf-8")) == trim(mb_strtolower(removeAcentos($dados['nomeMae']), "utf-8"))) {
						} else {
							$tudoOK = 0;
						}
					}
				}

				if ($pessoa->dataNascimento !== NULL and $pessoa->dataNascimento !== "" and strpos($pessoa->dataNascimento, '***') === false) {
					$dados['dataNascimento'] = date('Y', strtotime($pessoa->dataNascimento));
					if ($this->request->getPost('dataNascimentoValidacao')) {
						if (mb_strtolower($this->request->getPost('dataNascimentoValidacao'), "utf-8") == mb_strtolower($dados['dataNascimento'], "utf-8")) {
						} else {
							$tudoOK = 0;
						}
					}
				}




				//VERIFICA SE VALIDAÇÃO PASSOU

				if ($this->request->getPost('respostas') == 1) {

					if ($tudoOK == 1) {
						$response['success'] = true;
						$response['csrf_hash'] = csrf_hash();



						$senha = geraSenha($tamanho = 8, true, true, true);


						//TROCA SENHA
						$this->PacientesModel->trocaSenha($pessoa->codPaciente, $senha, $senha);


						//ENVIA NOTIFICAÇÃO POR EMAIL
						$textoEmail = '<div>Caro usu&aacute;rio(a), ' . $pessoa->nomeExibicao . ', </div>';
						$textoEmail .= '<div>Seu login &eacute; <b>' . $pessoa->cpf . '</b> e foi gerada a senha provis&oacute;ria <b>' . $senha . '</b></div>';
						$textoEmail .= '<div>Em momento oportuno, troque a sua senha.</div>';
						$textoEmail .= '<div>Atenciosamente, Equipe de TI do ' . $pessoa->siglaOrganizacao . '</div>';

						if ($pessoa->emailPessoal !== NULL) {
							$email = $pessoa->emailPessoal;
						} else {
							$email = $atualiza["emailPessoal"];
						}
						email($email, 'SENHA', $textoEmail);


						//ENVIA NOTIFICAÇÃO POR SMS
						if ($pessoa->celular !== NULL) {
							$celular = $pessoa->celular;
						} else {
							$celular = $atualiza["celular"];
						}
						$textoSMS = 'Caro usuário(a), ' . $pessoa->nomeExibicao . ', \n';
						$textoSMS .= 'Seu login é ' . $pessoa->cpf . ' e foi gerada a senha provisória ' . $senha . '\n';
						$textoSMS .= 'Em momento oportuno, troque a sua senha.\n';
						$textoSMS .= 'Atenciosamente, Equipe de TI do ' . $pessoa->siglaOrganizacao . '\n';

						sms($celular, $textoSMS);

						$response['success'] = true;
						$response['csrf_hash'] = csrf_hash();
						$response['emailPessoal'] = true;
						$response['messages'] = 'Confirmamos sua identidade. Uma senha provisória foi enviada para o seu email ' . $emailPessoal;

						//grava no LOG SUCESSO
						$this->LogsModel->inserirLogPaciente('Recuperação de senha do cpf ' . $pessoa->cpf, $pessoa->codPaciente);


						return $this->response->setJSON($response);
					} else {
						//grava no LOG FALHA
						$this->LogsModel->inserirLogPaciente('Falha na recuperação de senha do cpf ' . $pessoa->cpf, $pessoa->codPaciente);

						$response['success'] = false;
						$response['messages'] = '<div>Não foi possível confirmar seus dados.</div> <div style="font-size:14px;color:red">Tente novamente ou procure o Hospital.</div>';
						return $this->response->setJSON($response);
					}
				}
			}
		}
		return $this->response->setJSON($response);
	}



	public function getOne()
	{
		$response = array();
		$id = $this->request->getPost('codPaciente');

		$autorizacaoCodPesssoaMD5 = mb_substr($_SESSION['autorizacao'], 0, 32);

		if ($autorizacaoCodPesssoaMD5 !== md5($id)) {
			header("Location:" . base_url());
			exit();
		} else {
			//print mb_substr($_GET['autorizacao'], 0, 32)." = ". md5(session()->cpf);
		}





		$data = $this->PacientesModel->pegaPacientePorCodPaciente($id);

		return $this->response->setJSON($data);
	}

	public function listaDropDownParentesco()
	{

		$result = $this->PacientesModel->listaDropDownParentesco();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}
	public function listaDropDownTiposContatos()
	{

		$result = $this->PacientesModel->listaDropDownTiposContatos();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function incluirContatoSelf($codPaciente = null)
	{
		$response = array();
		$dados = array();



		$dados['codOrganizacao'] = session()->codOrganizacao;
		$dados['codPaciente'] = $this->request->getPost('codPaciente');
		$dados['codTipoContato'] = $this->request->getPost('codTipoContato');
		$dados['nomeContato'] = $this->request->getPost('nomeContato');
		$dados['numeroContato'] = $this->request->getPost('numeroContato');
		$dados['codParentesco'] = $this->request->getPost('codParentesco');
		$dados['observacoes'] = $this->request->getPost('observacoes');






		$this->validation->setRules([
			'codPaciente' => ['label' => 'Paciente', 'rules' => 'required|numeric|max_length[11]'],
			'codTipoContato' => ['label' => 'Tipo Contato', 'rules' => 'required|numeric|max_length[11]'],
			'codParentesco' => ['label' => 'Parentesco', 'rules' => 'required|numeric|max_length[11]'],
			'nomeContato' => ['label' => 'Nome Contato', 'rules' => 'required|bloquearReservado|max_length[20]'],
			'numeroContato' => ['label' => 'Número Contato', 'rules' => 'required|bloquearReservado|max_length[50]'],
			'observacoes' => ['label' => 'Observações', 'rules' => 'bloquearReservado|max_length[60]'],

		]);

		if ($this->validation->run($dados) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->PacientesModel->inserirOutroContato($dados)) {

				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['messages'] = 'Contato incluido com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na incluir!';
			}
		}

		return $this->response->setJSON($response);
	}





	public function atualizaPaciente()
	{

		$response = array();


		$fields['codPaciente'] = $this->request->getPost('codPaciente');
		$fields['codOrganizacao'] =  $this->request->getPost('codOrganizacao');
		$fields['emailPessoal'] = $this->request->getPost('emailPessoal');
		$fields['celular'] = $this->request->getPost('celular');
		$fields['endereco'] = $this->request->getPost('endereco');
		$fields['cep'] = $this->request->getPost('cep');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$request = \Config\Services::request();
		$ip = $request->getIPAddress();
		$fields['ipRequisitante'] = $ip;

		if (session()->codPessoa !== NULL) {
			$fields['autorAtualizacao'] = session()->codPessoa;
		} else {
			if (session()->codPaciente !== NULL) {
				$fields['autorAtualizacao'] = session()->codPaciente;
			} else {
				$fields['autorAtualizacao'] = 0;
			}
		}


		$this->validation->setRules([
			'codPaciente' => ['label' => 'codPaciente', 'rules' => 'required|numeric|max_length[11]'],
			'codOrganizacao' => ['label' => 'CodOrganizacao', 'rules' => 'required|numeric|max_length[11]'],
			'celular' => ['label' => 'celular', 'rules' => 'required|bloquearReservado|max_length[16]'],
			'emailPessoal' => ['label' => 'E-mail Pessoal', 'rules' => 'permit_empty|valid_email|bloquearReservado'],
			'endereco' => ['label' => 'endereco', 'rules' => 'permit_empty|bloquearReservado'],
			'cep' => ['label' => 'cep', 'rules' => 'permit_empty|bloquearReservado'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->PacientesModel->update($fields['codPaciente'], $fields)) {


				$paciente = $this->PacientesModel->pegaPacientePorCodPaciente($fields['codPaciente']);



				if ($paciente->emailPessoal !== NULL and $paciente->emailPessoal !== "" and $paciente->emailPessoal !== " ") {
					$email = $paciente->emailPessoal;
					$email = removeCaracteresIndesejadosEmail($email);
				} else {
					$email = NULL;
				}

				if ($email !== NULL and $paciente->nomeExibicao !== NULL) {
					$conteudo = "
								<div> Caro senhor(a), " . $paciente->nomeExibicao . ",</div>";
					$conteudo .= "<div>Seus dados foram alterados com sucesso em " . date("d/m/Y  H:i") . ".";
					$conteudo .= "<div style='margin-top:20px'>Atenciosamente,</div>";
					$conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . session()->descricaoOrganizacao . "</div>";

					$resultadoEmail = @email($email, 'ATUALIZAÇÃO DE CADASTRO', $conteudo);
					if ($resultadoEmail == false) {

						//ADICIONAR NOTIFICAÇÃO ANA FILA EM CASO DE FALHA
						@addNotificacoesFila($conteudo, $email, $email, 1);
					}


					//ENVIAR SMS
					$celular = removeCaracteresIndesejados($paciente->celular);
					$conteudoSMS = "
									Caro senhor(a), " . $paciente->nomeExibicao . ",";
					$conteudoSMS .= " Seus dados foram alterados com sucesso em " . date("d/m/Y  H:i") . ".";

					$conteudoSMS .= "Atenciosamente, ";
					$conteudoSMS .= session()->siglaOrganizacao;

					if ($celular !== NULL  and $celular !== ""  and $celular !== " ") {
						$resultadoSMS = @sms($celular, $conteudoSMS);
						if ($resultadoSMS == false) {

							//ADICIONAR NOTIFICAÇÃO ANA FILA EM CASO DE FALHA
							@addNotificacoesFila($conteudoSMS, 'Sistema', $celular, 2);
						}
					}
				}



				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['messages'] = 'Paciente atualizado com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na inserção!';
			}
		}


		return $this->response->setJSON($response);
	}

	public function getOutrosContatos()
	{


		$response = array();

		$data['data'] = array();

		$codPaciente = $this->request->getPost('codPaciente');



		$autorizacaoCodPesssoaMD5 = mb_substr($_SESSION['autorizacao'], 0, 32);

		if ($autorizacaoCodPesssoaMD5 !== md5($codPaciente)) {
			header("Location:" . base_url());
			exit();
		} else {
			//print mb_substr($_GET['autorizacao'], 0, 32)." = ". md5(session()->cpf);
		}


		$result = $this->PacientesModel->pegaOutrosContatosPorCodPaciente($codPaciente);

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Editar"  onclick="modificarContatoSelf(' . $value->codOutroContato . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeContatoSelf(' . $value->codOutroContato . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$key + 1,
				$this->PacientesModel->tipoContatoLookup($value->codTipoContato),
				$value->nomeContato,
				$this->PacientesModel->parentescoLookup($value->codParentesco),
				$value->numeroContato,
				$value->observacoes,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}
	public function removeContatoSelf()
	{

		$codOutroContato = $this->request->getPost('codOutroContato');

		$this->PacientesModel->removerOutroContato($codOutroContato);
		$response['success'] = true;
		$response['csrf_hash'] = csrf_hash();

		return $this->response->setJSON($response);
	}

	public function pegaUsuario()
	{
		$response = array();


		$cpf = removeCaracteresIndesejados($this->request->getPost('cpf'));
		$dados['cpf'] = $cpf;


		$this->validation->setRules([
			'cpf' => ['label' => 'CPF', 'rules' => 'required|bloquearReservado|numeric|max_length[16]'],

		]);

		if ($this->validation->run($dados) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {


			$codOrganizacao = $this->request->getPost('codOrganizacao');

			session()->set('codOrganizacao', $codOrganizacao);

			$codPerfil = $this->request->getPost('codPerfil');


			$pessoa = $this->PacientesModel->pegaPacientePorcpf($cpf);

			$tudoOK = 1;
			if ($pessoa !== NULL) {

				$dados = array();
				$novos = array();


				if ($pessoa->nomeCompleto !== NULL and strpos($pessoa->nomeCompleto, '***') === false) {
					//$dados['nomeCompleto'] = $pessoa->nomeCompleto;
				}
				if ($pessoa->identidade !== NULL) {
					//Primeiros 3 números da identidade

					$dados['identidade'] = mb_substr($pessoa->identidade, 0, 3);
				}
				if ($pessoa->emailPessoal !== NULL and strpos($pessoa->emailPessoal, '***') === false) {


					//informe seu email

					$emailPessoal = explode("@", $pessoa->emailPessoal);
					$emailPessoal = mb_substr($emailPessoal[0], 0, round(strlen($emailPessoal[0]) / 2)) . '****@' . mb_substr($emailPessoal[1], 0, 3) . '*******';

					$dados['emailPessoal'] = $pessoa->emailPessoal;

					if ($this->request->getPost('emailPessoalValidacao')) {
						if ($this->request->getPost('emailPessoalValidacao') == $dados['emailPessoal']) {
						} else {
							$tudoOK = 0;
						}
					}
				} else {
					$novos['emailPessoal'] = NULL;
				}
				if ($pessoa->celular !== NULL and $pessoa->celular !== "" and strpos($pessoa->celular, '***') === false) {
					//ultimos 4 números do Celular
					$dados['celular'] = mb_substr($pessoa->celular, -4);
				} else {
					$novos['celular'] = NULL;
				}
				if ($pessoa->codPlano !== NULL and $pessoa->codPlano !== "" and strpos($pessoa->codPlano, '***') === false) {
					//ultimos 3 números do CadBEM /codplano
					$dados['codPlano'] =  mb_substr($pessoa->codPlano, 0, 3);
				}
				if ($pessoa->nomeMae !== NULL and $pessoa->nomeMae !== "" and strpos($pessoa->nomeMae, '***') === false) {
					//PRIMEIRO NOME DA MAE

					$nomeMae = explode(" ", $pessoa->nomeMae);
					$dados['nomeMae'] = $nomeMae[0];
				}

				if ($pessoa->dataNascimento !== NULL and $pessoa->dataNascimento !== "" and strpos($pessoa->dataNascimento, '***') === false) {
					$dados['dataNascimento'] = date('Y', strtotime($pessoa->dataNascimento));
				}


				//VERIFICA SE VALIDAÇÃO PASSOU

				if ($this->request->getPost('respostas') == 1) {

					if ($tudoOK == 1) {
						$response['success'] = true;
						$response['csrf_hash'] = csrf_hash();
						$response['messages'] = 'Passou, enviamos um email';
						return $this->response->setJSON($response);
					} else {
						$response['success'] = false;
						$response['messages'] = '<div>Não foi possível confirmar seus dados.</div> <div style="font-size:14px;color:red">Tente novamente ou procure o Hospital.</div>';
						return $this->response->setJSON($response);
					}
				}


				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['dados'] = $dados;
				$response['cpf'] = $cpf;
				$response['codOrganizacao'] = $codOrganizacao;
				$response['formConfirmacao'] = $this->montaFormularioConfirmacao($dados, $novos);
			} else {
				$response['success'] = false;
				$response['messages'] = '<div>Cadastro não localizado.</div> <div style="font-size:14px;color:red">Tente novamente ou procure o Hospital.</div>';
			}
		}

		return $this->response->setJSON($response);
	}



	public function montaFormularioConfirmacao($dados, $novos)
	{
		$x = 0;

		$formulario = '<form id="confirmacoesForm" class="pl-3 pr-3">';

		foreach ($dados as $key => $valor) {
			$label = "";
			$tipo = "";
			$tamanho = "";
			$caracteres = "";

			if ($key == 'identidade') {
				$label = "Qual os <b>3 primeiros</b> digitos da sua <b>identidade</b>?";
				$tipo = "text";
				$caracteres = "3";
				$tamanho = "60";
			}

			if ($key == 'codPlano') {
				$label = "Qual são <b>3 primeiros</b> digitos do seu nº de Beneficiário (codplano)?";
				$tipo = "text";
				$caracteres = "3";
				$tamanho = "60";
			}


			if ($key == 'emailPessoal') {
				$label = "Qual é seu <b>e-mail</b> cadatrado previamente no sistema?";
				$tipo = "text";
				$caracteres = "40";
				$tamanho = "300";
			}

			if ($key == 'nomeMae') {
				$label = "Qual é o <b>primeiro nome</b> da sua mãe?";
				$tipo = "text";
				$caracteres = "40";
				$tamanho = "200";
			}
			if ($key == 'celular') {
				$label = "Quais são os <b>4 últimos</b> números do seu <b>celular</b>?";
				$tipo = "text";
				$caracteres = "4";
				$tamanho = "70";
			}


			if ($key == 'dataNascimento') {
				$label = "Qual é o <b>ano</b> do seu nascimento?";
				$tipo = "text";
				$caracteres = "4";
				$tamanho = "70";
			}


			$x++;

			$formulario .= '
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
					<label for="' . $key . '" name="' . $key . '">' . $label . '</label>
					<input style="width:' . $tamanho . 'px" type="' . $tipo . '" id="' . $key . '" name="' . $key . 'Validacao" class="form-control" maxlength="' . $caracteres . '" required>
																
					</div>	


				</div>	
			</div>			
			';
		}


		foreach ($novos as $key => $valor) {
			$label = "";
			$tipo = "";
			$tamanho = "";
			$caracteres = "";

			if ($key == 'emailPessoal') {
				$label = "Falta seu e-mail em nosso sistema, por favor, forneça-o para que a senha possa ser enviada.";
				$tipo = "text";
				$caracteres = "40";
				$tamanho = "300";
				$exemplo = "";
			}

			if ($key == 'celular') {
				$label = "Falta seu Nº de Celular em nosso sistema, por favor, forneça-o para que possamos contata-lo em caso de necessidade.";
				$tipo = "text";
				$caracteres = "60";
				$tamanho = "200";
				$exemplo = '<span style="font-size:9px;color:red"> informe o DDD: Exemplo: 81 99999-9999</span>';
			}



			$x++;
			$formulario .= '
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
					<label for="' . $key . '" name="' . $key . '">' . $label . '</label>
					<input style="width:' . $tamanho . 'px" type="' . $tipo . '" id="' . $key . 'Add" name="' . $key . 'Novos" class="form-control" maxlength="' . $caracteres . '" required>
					' . $exemplo . '										
					</div>	
				</div>	
				
			</div>			
			';
		}
		$formulario .= '</form>';
		return $formulario;
	}


	public function redefineOrganizacao()
	{
		//INSTANCIA POST/GET FORMS
		$request = service('request');


		//PEGA DADOS
		$codOrganizacao = $request->getPost('codOrganizacao');

		$response = array();

		if (is_numeric($codOrganizacao)) {

			session()->set('codOrganizacao', $codOrganizacao);

			return true;
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}
}
