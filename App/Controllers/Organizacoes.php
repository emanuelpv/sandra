<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LogsModel;
use CodeIgniter\Files\File;
use App\Models\OrganizacoesModel;
use App\Models\AtributosSistemaModel;
use App\Models\AtributosSistemaOrganizacaoModel;

class Organizacoes extends BaseController
{

	protected $OrganizacoesModel;
	protected $validation;
	protected $AtrubutosSistema;

	public function __construct()
	{

		helper('seguranca_helper', 'form', 'url');
		//print_r(current_url());exit();
		verificaSeguranca($this, session(), base_url());
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->validation =  \Config\Services::validation();

		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->AtributosSistemaModel = new AtributosSistemaModel();
		$this->AtributosSistemaOrganizacaoModel = new AtributosSistemaOrganizacaoModel();

	}

	public function index()
	{

		$permissao = verificaPermissao('Organizacoes', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo Organizacoes', session()->codPessoa);
			exit();
		}
		
		$data = [
			'controller'    	=> 'organizacoes',
			'title'     		=> 'Organizações'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('organizacoes', $data);
		
	}

	public function getAll()
	{

		$response = array();

		$data['data'] = array();
		$result = $this->OrganizacoesModel->pegaOrganizacoes();
		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-xs btn-primary" onclick="edit(' . $value->codOrganizacao . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="remove(' . $value->codOrganizacao . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codOrganizacao,
				$value->descricao,
				$value->siglaOrganizacao,
				$value->endereço,
				$value->cep,
				$value->telefone,
				$value->cnpj,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codOrganizacao');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->OrganizacoesModel->where('codOrganizacao', $id)->first();

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function salvarSPED()
	{

		$response = array();

		$fields['codOrganizacao']=$this->request->getPost('codOrganizacao');
		$fields['servidorSpedDB'] = $this->request->getPost('servidorSpedDB');
		$fields['SpedDB'] = $this->request->getPost('SpedDB');
		$fields['administradorSpedDB'] = $this->request->getPost('administradorSpedDB');
		$fields['senhaadministradorSpedDB'] = $this->request->getPost('senhaadministradorSpedDB');
		$fields['checkboxSPED'] = $this->request->getPost('checkboxSPED');


		if ($this->request->getPost('checkboxSPED') == 'on') {
			$fields['servidorSPEDStatus'] = '1';
		} else {
			$fields['servidorSPEDStatus'] = '0';
		}

		$fields['dataAtualizacao'] = date('Y-m-d H:i');

		$this->validation->setRules([
			'codOrganizacao' => ['label' => 'codOrganizacao', 'rules' => 'required|numeric|max_length[11]'],
			'servidorSpedDB' => ['label' => 'servidorSpedDB', 'rules' => 'required|max_length[100]'],
			'servidorSpedDB' => ['label' => 'servidorSpedDB', 'rules' => 'required|max_length[100]'],
			'SpedDB' => ['label' => 'SpedDB', 'rules' => 'permit_empty|max_length[100]'],
			'administradorSpedDB' => ['label' => 'administradorSpedDB', 'rules' => 'permit_empty|max_length[50]'],
			'senhaadministradorSpedDB' => ['label' => 'senhaadministradorSpedDB', 'rules' => 'permit_empty|max_length[50]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->OrganizacoesModel->update($fields['codOrganizacao'], $fields)) {

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


	public function add()
	{

		$response = array();

		$fields['codOrganizacao'] = $this->request->getPost('codOrganizacao');
		$fields['descricao'] = $this->request->getPost('descricao');
		$fields['siglaOrganizacao'] = $this->request->getPost('siglaOrganizacao');
		$fields['endereço'] = $this->request->getPost('endereço');
		$fields['telefone'] = $this->request->getPost('telefone');
		$fields['cnpj'] = $this->request->getPost('cnpj');
		$fields['cep'] = $this->request->getPost('cep');
		$fields['cidade'] = mb_strtoupper($this->request->getPost('cidade'), 'utf-8');
		$fields['codEstadoFederacao'] = $this->request->getPost('codEstadoFederacao');
		$fields['chaveSalgada'] = random_str(12, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%&*');;
		$fields['tempoInatividade'] = '40';
		$fields['loginAdmin'] = 'admin';
		$fields['senhaAdmin'] = hash("sha256", 'admin');

		if ($this->request->getPost('matriz') == 'on') {
			$fields['matriz'] = '1';
			$this->OrganizacoesModel->redefineMatriz();
		} else {
			$fields['matriz'] = '0';
		}


		$this->validation->setRules([
			'descricao' => ['label' => 'Descricao', 'rules' => 'required|max_length[100]'],
			'endereço' => ['label' => 'Endereço', 'rules' => 'permit_empty|max_length[100]'],
			'telefone' => ['label' => 'Telefone', 'rules' => 'permit_empty|max_length[16]'],
			'cnpj' => ['label' => 'Cnpj', 'rules' => 'permit_empty|max_length[15]'],
		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($newCodOrganizacao = $this->OrganizacoesModel->insert($fields)) {

				//adiciona atributos padrão dos formulários da Organização

				$AtrubutosSistema = $this->AtributosSistemaModel->pegaAtributosPadraoOrganizacao();

				foreach ($AtrubutosSistema as $row) {
					$atributosPadrao['codAtributosSistema'] = $row->codAtributosSistema;
					$atributosPadrao['nomeAtributoSistema'] = $row->nomeAtributoSistema;
					$atributosPadrao['codOrganizacao'] = $newCodOrganizacao;
					$atributosPadrao['descricaoAtributoSistema'] = $row->descricaoAtributoSistema;
					$atributosPadrao['visivelFormulario'] = $row->visivelFormulario;
					$atributosPadrao['obrigatorio'] = $row->obrigatorio;
					$atributosPadrao['visivelLDAP'] = $row->visivelLDAP;
					$atributosPadrao['cadastroRapido'] = $row->cadastroRapido;
					$atributosPadrao['ordenacao'] = $row->ordenacao;
					$atributosPadrao['tipo'] = $row->tipo;
					$atributosPadrao['tamanho'] = $row->tamanho;
					$atributosPadrao['icone'] = $row->icone;

					$db      = \Config\Database::connect();
					$builderOrganizacao = $db->table('sis_atributossistemaorganizacao');
					$builderOrganizacao->insert($atributosPadrao);
				}


				$response['success'] = true;
				$response['codOrganizacao'] = $newCodOrganizacao;
				$response['csrf_hash'] = csrf_hash();
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


		$fields['codOrganizacao'] = $this->request->getPost('codOrganizacao');
		$fields['descricao'] = $this->request->getPost('descricao');
		$fields['siglaOrganizacao'] = $this->request->getPost('siglaOrganizacao');
		$fields['endereço'] = $this->request->getPost('endereço');
		$fields['telefone'] = $this->request->getPost('telefone');
		$fields['cnpj'] = $this->request->getPost('cnpj');
		$fields['site'] = $this->request->getPost('site');
		$fields['cep'] = $this->request->getPost('cep');
		$fields['cidade'] = mb_strtoupper($this->request->getPost('cidade'), 'utf-8');
		$fields['codEstadoFederacao'] = $this->request->getPost('codEstadoFederacao');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		if ($this->request->getPost('matriz') == 'on') {
			$fields['matriz'] = '1';
			$this->OrganizacoesModel->redefineMatriz();
		} else {
			$fields['matriz'] = '0';
		}

		$this->validation->setRules([
			'codOrganizacao' => ['label' => 'codOrganizacao', 'rules' => 'required|numeric|max_length[11]'],
			'descricao' => ['label' => 'Descricao', 'rules' => 'required|max_length[100]'],
			'endereço' => ['label' => 'Endereço', 'rules' => 'permit_empty|max_length[100]'],
			'telefone' => ['label' => 'Telefone', 'rules' => 'permit_empty|max_length[16]'],
			'cnpj' => ['label' => 'Cnpj', 'rules' => 'permit_empty|max_length[15]'],
			'site' => ['label' => 'site', 'rules' => 'permit_empty|max_length[200]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->OrganizacoesModel->update($fields['codOrganizacao'], $fields)) {

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

	public function SalvaPoliticaSenhas()
	{

		$codOrganizacao =  $this->request->getPost('codOrganizacao');


		$dadosOrganizacao['minimoCaracteres'] = $this->request->getPost('minimoCaracteres');
		$dadosOrganizacao['diferenteUltimasSenhas'] = $this->request->getPost('diferenteUltimasSenhas');

		$dadosOrganizacao['politicaSenha'] = 0;
		$dadosOrganizacao['senhaNaoSimples'] = 0;
		$dadosOrganizacao['numeros'] = 0;
		$dadosOrganizacao['letras'] = 0;
		$dadosOrganizacao['maiusculo'] = 0;
		$dadosOrganizacao['caracteresEspeciais'] = 0;

		if ($this->request->getPost('politicaSenha') == 'on') {
			$dadosOrganizacao['politicaSenha'] = 1;
		}


		if ($this->request->getPost('senhaNaoSimples') == 'on') {
			$dadosOrganizacao['senhaNaoSimples'] = 1;
		}


		if ($this->request->getPost('numeros') == 'on') {
			$dadosOrganizacao['numeros'] = 1;
		} else {
		}


		if ($this->request->getPost('letras') == 'on') {
			$dadosOrganizacao['letras'] = 1;
		}


		if ($this->request->getPost('caracteresEspeciais') == 'on') {
			$dadosOrganizacao['caracteresEspeciais'] = 1;
		}

		if ($this->request->getPost('maiusculo') == 'on') {
			$dadosOrganizacao['maiusculo'] = 1;
		}





		session()->set('politicaSenha', $dadosOrganizacao['politicaSenha']);
		session()->set('senhaNaoSimples', $dadosOrganizacao['senhaNaoSimples']);
		session()->set('numeros', $dadosOrganizacao['numeros']);
		session()->set('letras', $dadosOrganizacao['letras']);
		session()->set('caracteresEspeciais', $dadosOrganizacao['caracteresEspeciais']);
		session()->set('minimoCaracteres', $dadosOrganizacao['minimoCaracteres']);
		session()->set('maiusculo', $dadosOrganizacao['maiusculo']);
		session()->set('diferenteUltimasSenhas', $dadosOrganizacao['diferenteUltimasSenhas']);


		$db      = \Config\Database::connect();
		$builderOrganizacao = $db->table('sis_organizacoes');
		$builderOrganizacao->where('codOrganizacao', $codOrganizacao);


		if ($builderOrganizacao->update($dadosOrganizacao)) {

			$response['success'] = true;
			$response['csrf_hash'] = csrf_hash();
			$response['messages'] = 'Política de senhas salva com sucesso';
		} else {
			$response['success'] = false;
			$response['messages'] = 'Erro ao salvar política';
		}
		return $this->response->setJSON($response);
	}


	public function salvaSenhaNovosUsuarios()
	{

		$codOrganizacao =  $this->request->getPost('codOrganizacao');

		$dadosOrganizacao['ativarSenhaPadrao'] = 0;
		$dadosOrganizacao['confirmacaoCadastroPorEmail'] = 0;
		$dadosOrganizacao['senhaAleatória'] = 0;

		if ($this->request->getPost('senhaNovousuario') == 1) {
			$dadosOrganizacao['ativarSenhaPadrao'] = 1;
		}


		if ($this->request->getPost('senhaPadrao') !== NULL) {
			$dadosOrganizacao['senhaPadrao'] = $this->request->getPost('senhaPadrao');
		}


		if ($this->request->getPost('senhaNovousuario') == 2) {
			$dadosOrganizacao['confirmacaoCadastroPorEmail'] = 1;
		}


		if ($this->request->getPost('senhaNovousuario') == 3) {
			$dadosOrganizacao['senhaAleatória'] = 1;
		}



		$db      = \Config\Database::connect();
		$builderOrganizacao = $db->table('sis_organizacoes');
		$builderOrganizacao->where('codOrganizacao', $codOrganizacao);


		if ($builderOrganizacao->update($dadosOrganizacao)) {

			$response['success'] = true;
			$response['csrf_hash'] = csrf_hash();
			$response['messages'] = 'Definição de senha inicial salva com sucesso';
		} else {
			$response['success'] = false;
			$response['messages'] = 'Erro ao salvar política de senha inicial';
		}
		return $this->response->setJSON($response);
	}

	public function salvarDiversos()
	{

		$codOrganizacao = $this->request->getPost('codOrganizacao');

		$dadosOrganizacao['codTimezone'] = $this->request->getPost('codTimezone');
		$dadosOrganizacao['chaveSalgada'] = $this->request->getPost('chaveSalgada');
		$dadosOrganizacao['tempoInatividade'] = $this->request->getPost('tempoInatividade');
		$dadosOrganizacao['forcarExpiracao'] = $this->request->getPost('forcarExpiracao');
		$dadosOrganizacao['formularioRegistro'] = $this->request->getPost('formularioRegistro');
		$dadosOrganizacao['codPerfilPadrao'] = $this->request->getPost('codPerfil');
		$dadosOrganizacao['nomeExibicaoSistema'] = $this->request->getPost('nomeExibicaoSistema');
		session()->set('nomeExibicaoSistema', $this->request->getPost('nomeExibicaoSistema'));

		$organizacao = $this->OrganizacoesModel->pegaOrganizacao($codOrganizacao);


		if ($this->request->getPost('codTimezone') !== NULL) {
			$timezone = $this->OrganizacoesModel->pegaTimezone($dadosOrganizacao['codTimezone']);
			session()->codTimezone = $this->request->getPost('codTimezone');
			session()->timezone = $timezone->nome;
		}

		if ($this->request->getPost('permiteAutocadastro') == 'on') {
			$dadosOrganizacao['permiteAutocadastro'] = 1;
		} else {
			$dadosOrganizacao['permiteAutocadastro'] = 0;
		}



		$db      = \Config\Database::connect();
		$builderOrganizacao = $db->table('sis_organizacoes');
		$builderOrganizacao->where('codOrganizacao', $codOrganizacao);


		//RECONFIGURA VARIÁVEISDE TEMPO DE SEÇÃO
		session()->tempoInatividade =  $this->request->getPost('tempoInatividade');
		session()->forcarExpiracao =  $this->request->getPost('forcarExpiracao');

		if ($builderOrganizacao->update($dadosOrganizacao)) {

			$response['success'] = true;
			$response['csrf_hash'] = csrf_hash();
			$response['messages'] = 'Política de senhas salva com sucesso';
		} else {
			$response['success'] = false;
			$response['messages'] = 'Erro ao salvar política';
		}
		return $this->response->setJSON($response);
	}

	public function salvarCabacalho()
	{

		$response = array();
		$fields['codOrganizacao'] = $this->request->getPost('codOrganizacao');
		$fields['cabecalho'] = $this->request->getPost('cabecalho');


		$fields['dataAtualizacao'] = date('Y-m-d H:i');

		$this->validation->setRules([
			'cabecalho' => ['label' => 'Cabeçalho', 'rules' => 'permit_empty'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->OrganizacoesModel->update($fields['codOrganizacao'], $fields)) {

				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['messages'] = 'Cabeçalho ofícios salvo com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
			}
		}

		return $this->response->setJSON($response);
	}

	public function salvarCabacalhoPrescricao()
	{

		$response = array();
		$fields['codOrganizacao'] = $this->request->getPost('codOrganizacao');
		$fields['cabecalhoPrescricao'] = $this->request->getPost('cabecalho');


		$fields['dataAtualizacao'] = date('Y-m-d H:i');

		$this->validation->setRules([
			'cabecalhoPrescricao' => ['label' => 'Cabeçalho Prescrição', 'rules' => 'permit_empty'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->OrganizacoesModel->update($fields['codOrganizacao'], $fields)) {

				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['messages'] = 'Cabeçalho Prescrição salvo com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
			}
		}

		return $this->response->setJSON($response);
	}


	public function salvarRedesSociais()
	{

		$response = array();
		$fields['codOrganizacao'] = $this->request->getPost('codOrganizacao');
		$fields['linkedin_url'] = $this->request->getPost('linkedin_url');
		$fields['facebook_url'] = $this->request->getPost('facebook_url');
		$fields['instagram_url'] = $this->request->getPost('instagram_url');
		$fields['twitter_url'] = $this->request->getPost('twitter_url');
		$fields['youtube_url'] = $this->request->getPost('youtube_url');


		$fields['dataAtualizacao'] = date('Y-m-d H:i');

		$this->validation->setRules([
			'linkedin_url' => ['label' => 'linkedin', 'rules' => 'permit_empty'],
			'facebook_url' => ['label' => 'facebook', 'rules' => 'permit_empty'],
			'instagram_url' => ['label' => 'instagram', 'rules' => 'permit_empty'],
			'twitter_url' => ['label' => 'twitter', 'rules' => 'permit_empty'],
			'youtube_url' => ['label' => 'youtube', 'rules' => 'permit_empty'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->OrganizacoesModel->update($fields['codOrganizacao'], $fields)) {

				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['messages'] = 'Redes Sociais salvas com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
			}
		}

		return $this->response->setJSON($response);
	}
	public function salvarRodape()
	{

		$response = array();
		$fields['codOrganizacao'] = $this->request->getPost('codOrganizacao');
		$fields['rodape'] = $this->request->getPost('rodape');


		$fields['dataAtualizacao'] = date('Y-m-d H:i');

		$this->validation->setRules([
			'rodape' => ['label' => 'Rodape', 'rules' => 'permit_empty'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->OrganizacoesModel->update($fields['codOrganizacao'], $fields)) {

				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['messages'] = 'Rodapé ofícios salvo com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
			}
		}

		return $this->response->setJSON($response);
	}
	public function salvarRodapePrescricao()
	{

		$response = array();
		$fields['codOrganizacao'] = $this->request->getPost('codOrganizacao');
		$fields['rodapePrescricao'] = $this->request->getPost('rodape');


		$fields['dataAtualizacao'] = date('Y-m-d H:i');

		$this->validation->setRules([
			'rodapePrescricao' => ['label' => 'Rodape', 'rules' => 'permit_empty'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->OrganizacoesModel->update($fields['codOrganizacao'], $fields)) {

				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['messages'] = 'Rodapé Prescrição salvo com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
			}
		}

		return $this->response->setJSON($response);
	}

	public function salvarSeguranca()
	{


		$codOrganizacao = $this->request->getPost('codOrganizacao');

		$organizacao = $this->OrganizacoesModel->pegaOrganizacao($codOrganizacao);

		if ($this->request->getPost('confirmacao') == NULL) {
			$response['success'] = false;
			$response['messages'] = 'É necessário confirmar a senha';
			return $this->response->setJSON($response);
		}

		if ($this->request->getPost('confirmacao') !== $this->request->getPost('senhaAdmin')) {

			$response['success'] = false;
			$response['messages'] = 'Senha de confirmação não confere';
			return $this->response->setJSON($response);
		}


		$dadosOrganizacao['loginAdmin'] = $this->request->getPost('loginAdmin');
		if ($this->request->getPost('senhaAdmin') !== $organizacao->senhaAdmin) {

			$dadosOrganizacao['senhaAdmin'] = hash("sha256", $this->request->getPost('senhaAdmin'));
		}


		$db      = \Config\Database::connect();
		$builderOrganizacao = $db->table('sis_organizacoes');
		$builderOrganizacao->where('codOrganizacao', $codOrganizacao);


		if ($builderOrganizacao->update($dadosOrganizacao)) {

			$response['success'] = true;
			$response['csrf_hash'] = csrf_hash();
			$response['messages'] = 'Senha Salva com sucesso';
		} else {
			$response['success'] = false;
			$response['messages'] = 'Erro ao salvar senha';
		}
		return $this->response->setJSON($response);
	}

	public function remove()
	{
		$response = array();

		$response['success'] = false;
		$response['messages'] = 'Funcionalidade desativada. Contate o administrador do sistema';

		return $this->response->setJSON($response);


		$id = $this->request->getPost('codOrganizacao');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->OrganizacoesModel->where('codOrganizacao', $id)->delete()) {

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







	public function envia_fundo()
	{


		$response = array();

		$validationRule = [
			'file' => [
				'label' => 'Imagem',
				'rules' => [
					'uploaded[file]',
					'is_image[file]',
					'mime_in[file,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
					'max_size[file,5242880]',
					'max_dims[file,4000,3000]',
				],
				'errors' => [
					'is_image' => 'Não é uma imagem',
					'max_size' => 'Arquivo muito grane',
					'max_dims' => 'Resolução da imagem muito alta',
					'mime_in' => 'Extensão inválida',
					'uploaded' => 'Extensão inválida',
				],
			],
		];
		if (!$this->validate($validationRule)) {

			$response['success'] = false;
			$response['messages'] = $this->validator->getError();
			return $this->response->setJSON($response);
		}



		if ($this->request->getFile('file') !== NULL) {

			$img = $this->request->getFile('file');
			$nomeArquivo = session()->codOrganizacao . '.' . $img->getClientExtension();
			$img->move(WRITEPATH . '../imagens/fundo/',  $nomeArquivo, true);

			$fields = array(
				'fundo' => $nomeArquivo,
				'dataAtualizacao' => date('Y-m-d H:i'),
			);
			if ($this->OrganizacoesModel->update(session()->codOrganizacao, $fields)) {
			}

			$response['success'] = true;
			$response['fundo'] = $nomeArquivo;
			$response['messages'] =  'Imagem de fundo enviada com sucesso';
			return $this->response->setJSON($response);
		}
	}


	public function envia_logo()
	{


		$response = array();

		$validationRule = [
			'file' => [
				'label' => 'Imagem',
				'rules' => [
					'uploaded[file]',
					'is_image[file]',
					'mime_in[file,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
					'max_size[file,5242880]',
					'max_dims[file,1600,1200s]',
				],
				'errors' => [
					'is_image' => 'Não é uma imagem',
					'max_size' => 'Arquivo muito grane',
					'max_dims' => 'Resolução da imagem muito alta',
					'mime_in' => 'Extensão inválida',
					'uploaded' => 'Extensão inválida',
				],
			],
		];
		if (!$this->validate($validationRule)) {

			$response['success'] = false;
			$response['messages'] = $this->validator->getError();
			return $this->response->setJSON($response);
		}



		if ($this->request->getFile('file') !== NULL) {

			$img = $this->request->getFile('file');
			$nomeArquivo = session()->codOrganizacao . '.' . $img->getClientExtension();
			$img->move(WRITEPATH . '../imagens/organizacoes/',  $nomeArquivo, true);

			$fields = array(
				'logo' => $nomeArquivo,
				'dataAtualizacao' => date('Y-m-d H:i'),
			);
			if ($this->OrganizacoesModel->update(session()->codOrganizacao, $fields)) {
			}

			$response['success'] = true;
			$response['logo'] = $nomeArquivo;
			$response['messages'] =  'Logo enviada com sucesso';
			return $this->response->setJSON($response);
		}
	}
	public function envia_slideShow()
	{


		$response = array();

		$validationRule = [
			'file' => [
				'label' => 'Imagem',
				'rules' => [
					'uploaded[file]',
					'is_image[file]',
					'mime_in[file,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
					'max_size[file,50000]',
					'max_dims[file,1600,1200]',
				],
				'errors' => [
					'is_image' => 'Não é uma imagem',
					'max_size' => 'Arquivo muito grane',
					'max_dims' => 'Resolução da imagem muito alta',
					'mime_in' => 'Extensão inválida',
					'uploaded' => 'Extensão inválida',
				],
			],
		];
		if (!$this->validate($validationRule)) {

			$response['success'] = false;
			$response['messages'] = $this->validator->getError();
			return $this->response->setJSON($response);
		}



		if ($this->request->getFile('file') !== NULL) {

			$img = $this->request->getFile('file');
			$nomeArquivo = $img->getName();
			$img->move(WRITEPATH . '../imagens/slideshow/',  $nomeArquivo, true);

			$fields = array(
				'foto' => $nomeArquivo,
				'dataAtualizacao' => date('Y-m-d H:i'),
			);
			if ($this->OrganizacoesModel->update(session()->codOrganizacao, $fields)) {
			}

			$response['success'] = true;
			$response['foto'] = $nomeArquivo;
			$response['messages'] =  'Foto enviada com sucesso';
			return $this->response->setJSON($response);
		}
	}


	public function envia_foto()
	{


		$response = array();

		$validationRule = [
			'file' => [
				'label' => 'Imagem',
				'rules' => [
					'uploaded[file]',
					'is_image[file]',
					'mime_in[file,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
					'max_size[file,50000]',
					'max_dims[file,1600,1200]',
				],
				'errors' => [
					'is_image' => 'Não é uma imagem',
					'max_size' => 'Arquivo muito grane',
					'max_dims' => 'Resolução da imagem muito alta',
					'mime_in' => 'Extensão inválida',
					'uploaded' => 'Extensão inválida',
				],
			],
		];
		if (!$this->validate($validationRule)) {

			$response['success'] = false;
			$response['messages'] = $this->validator->getError();
			return $this->response->setJSON($response);
		}



		if ($this->request->getFile('file') !== NULL) {

			$img = $this->request->getFile('file');
			$nomeArquivo = 'fotoOrganizacao_' . session()->codOrganizacao . '.' . $img->getClientExtension();
			$img->move(WRITEPATH . '../imagens/organizacoes/',  $nomeArquivo, true);

			$fields = array(
				'foto' => $nomeArquivo,
				'dataAtualizacao' => date('Y-m-d H:i'),
			);
			if ($this->OrganizacoesModel->update(session()->codOrganizacao, $fields)) {
			}

			$response['success'] = true;
			$response['foto'] = $nomeArquivo;
			$response['messages'] =  'Foto enviada com sucesso';
			return $this->response->setJSON($response);
		}
	}
}
