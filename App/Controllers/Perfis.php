<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LogsModel;
use App\Models\PerfisModel;
use App\Models\AtalhosModel;
use App\Models\PerfisModulosModel;
use App\Models\PerfilPessoasMembroModel;


class Perfis extends BaseController
{

	protected $PerfisModel;
	protected $validation;
	protected $perfilPessoasMembroModel;
	protected $perfisModulosModel;
	protected $LogsModel;
	protected $atalhosModel;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->PerfisModel = new PerfisModel();
		$this->validation =  \Config\Services::validation();
		$this->atalhosModel = new AtalhosModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->perfisModulosModel = new PerfisModulosModel();
		$this->perfilPessoasMembroModel = new PerfilPessoasMembroModel();



	}

	public function index()
	{

		$permissao = verificaPermissao('Perfis', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo Perfis', session()->codPessoa);
			exit();
		}

		$data = [
			'controller'    	=> 'perfis',
			'title'     		=> 'Perfis'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('perfis', $data);
	}


	
	public function perfisSame()
	{

		$permissao = verificaPermissao('perfis/perfisSame', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo Perfis do setor de marcações', session()->codPessoa);
			exit();
		}

		$data = [
			'controller'    	=> 'perfis',
			'title'     		=> 'Perfis'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('perfisSame', $data);
	}


	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->PerfisModel->pega_todasPerfis();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-xs btn-primary" onclick="edit(' . $value->codPerfil . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="remove(' . $value->codPerfil . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codPerfil,
				$value->descricao_perfil,
				$value->descricaoOrganizacao,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}


	public function getAllPerfisSame()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->PerfisModel->getAllPerfisSame();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-xs btn-primary" onclick="edit(' . $value->codPerfil . ')"><i class="fa fa-edit"></i> Permissões</button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codPerfil,
				$value->descricao_perfil,
				$value->descricaoOrganizacao,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}



	public function getAllPerfisModulos()
	{
		$response = array();

		$data['data'] = array();

		$codPerfil = $this->request->getPost('codPerfil');
		$result = $this->perfisModulosModel->pegaTudoPerfil($codPerfil);

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Editar" onclick="editperfisModulos(' . $value->codPerfil . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="removeperfisModulos(' . $value->codPerfil . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			//LISTAR

			if ($value->listar == 1) {
				$listarChecked = 'checked';
			} else {
				$listarChecked = '';
			}

			$listar = '
			<div class="icheck-primary d-inline">
				<style>
				td{text-align:center;
					input[type=checkbox] {
						transform: scale(1.8);
					}
				</style>
			<input class="listar" id="listar' . $value->codModulo . '" type="checkbox" ' . $listarChecked . ' name="listar' . $value->codModulo . '"></div>';


			if ($value->adicionar == 1) {
				$adicionarChecked = 'checked';
			} else {
				$adicionarChecked = '';
			}
			$adicionar = '
			<div class="icheck-primary d-inline">
				<style>
				td{text-align:center;
					input[type=checkbox] {
						transform: scale(1.8);
					}
				</style>
				<input class="adicionar" id="adicionar' . $value->codModulo . '" type="checkbox" ' . $adicionarChecked . ' name="adicionar' . $value->codModulo . '"></div>';

			if ($value->editar == 1) {
				$editarChecked = 'checked';
			} else {
				$editarChecked = '';
			}
			$editar = '
			<div class="icheck-primary d-inline">
				<style>
				td{text-align:center;
					input[type=checkbox] {
						transform: scale(1.8);
					}
				</style>
				<input class="editar" id="editar' . $value->codModulo . '" type="checkbox" ' . $editarChecked . ' name="editar' . $value->codModulo . '"></div>';

			if ($value->deletar == 1) {
				$deletarChecked = 'checked';
			} else {
				$deletarChecked = '';
			}
			$deletar = '
			<div class="icheck-primary d-inline">
				<style>
				td{text-align:center;
					input[type=checkbox] {
						transform: scale(1.8);
					}
				</style>
				<input class="deletar" id="deletar' . $value->codModulo . '" type="checkbox" ' . $deletarChecked . ' name="deletar' . $value->codModulo . '"></div>';

			if ($value->pai == null) {
				$pai = "#";
			} else {
				$pai = $value->pai;
			}


			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$pai . '/' . '<b>' . $value->nome . '</b>',
				$listar,
				$adicionar,
				$editar,
				$deletar,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}
	

	public function atalhos()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->PerfisModel->pega_todasPerfis();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-xs btn-primary" onclick="edit(' . $value->codPerfil . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="remove(' . $value->codPerfil . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codPerfil,
				$value->descricao_perfil,
				$value->descricaoOrganizacao,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getMembros()
	{
		$codPerfil = $this->request->getPost('codPerfil');
		$response = array();

		$data['data'] = array();

		$result = $this->perfilPessoasMembroModel->pegaPorCodigoPerfil($codPerfil);

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Editar" onclick="editperfilPessoasMembro(' . $value->codPessoaMembro . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="removeperfilPessoasMembro(' . $value->codPessoaMembro . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			if ($value->dataEncerramento == NULL) {
				$dataEncerramento = 'Indeterminado';
			} else {
				$dataEncerramento = $value->dataEncerramento;
			}


			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->nomeExibicao,
				$value->dataInicio,
				$dataEncerramento,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}


	public function atalhosPerfil()
	{
		$response = array();

		$data['data'] = array();

		$codPerfil = $this->request->getPost('codPerfil');
		$result = $this->perfisModulosModel->pegaModulosVisiveis($codPerfil);

		foreach ($result as $key => $value) {

			$ops = '';



			//LISTAR

			if ($value->codAtalho !== NULL) {
				$listarChecked = 'checked';
				$cmdo = 'delete';
				$codAtalho = $value->codAtalho;
			} else {
				$listarChecked = '';
				$cmdo = 'insert';
				$codAtalho = 0;
			}

			$atalho = '
			<div class="icheck-primary d-inline">
				<style>
				td{text-align:center;
					input[type=checkbox] {
						transform: scale(1.8);
					}
				</style>
			<input class="atalho" onclick="atualizaPerfilAtalho(\'' . $codPerfil . '\', \'' . $codAtalho . '\', \'' . $value->codModulo . '\', \'' . $cmdo . '\');" id="atalho' . $value->codModulo . '" type="checkbox" ' . $listarChecked . ' name="atalho' . $value->codModulo . '"></div>';





			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				'<b>' . $value->nome . '</b>',
				$atalho,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}



	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codPerfil');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->PerfisModel->where('codPerfil', $id)->first();

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();
		$fields['codOrganizacao'] = session()->codOrganizacao;
		$fields['codPerfil'] = $this->request->getPost('codPerfil');
		$fields['descricao'] = $this->request->getPost('descricao');
		$fields['codOrganizacao'] = $this->request->getPost('codOrganizacao');
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');


		$this->validation->setRules([
			'descricao' => ['label' => 'Descrição', 'rules' => 'required|max_length[60]'],
			'codOrganizacao' => ['label' => 'Organização', 'rules' => 'required|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->PerfisModel->insert($fields)) {

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

	public function atualizaPerfilAtalho()
	{

		$response = array();
		$fields['codOrganizacao'] = session()->codOrganizacao;
		$fields['codPerfil'] = $this->request->getPost('codPerfil');
		$fields['codModulo'] = $this->request->getPost('codModulo');
		$codAtalho = $this->request->getPost('codAtalho');
		$cmdo = $this->request->getPost('cmdo');





		$this->validation->setRules([
			'codPerfil' => ['label' => 'codPerfil', 'rules' => 'required'],
			'codModulo' => ['label' => 'codModulo', 'rules' => 'required'],

		]);

		if ($cmdo == 'insert') {
			if ($this->validation->run($fields) == FALSE) {

				$response['success'] = false;
				$response['messages'] = $this->validation->listErrors();
			} else {

				if ($this->atalhosModel->insert($fields)) {

					$response['success'] = true;
					$response['csrf_hash'] = csrf_hash();
					$response['messages'] = 'Atalho adicionado';
				} else {

					$response['success'] = false;
					$response['messages'] = 'Erro na inserção!';
				}
			}
		}

		if ($cmdo == 'delete') {
			if ($this->validation->run($fields) == FALSE) {

				$response['success'] = false;
				$response['messages'] = $this->validation->listErrors();
			} else {

				if ($this->atalhosModel->where('codAtalho', $codAtalho)->delete()) {

					$response['success'] = true;
					$response['csrf_hash'] = csrf_hash();
					$response['messages'] = 'Atalho removido';
				} else {

					$response['success'] = false;
					$response['messages'] = 'Erro na inserção!';
				}
			}
		}



		return $this->response->setJSON($response);
	}

	public function edit()
	{

		$response = array();

		$fields['codPerfil'] = $this->request->getPost('codPerfil');
		$fields['descricao'] = $this->request->getPost('descricao');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');


		$this->validation->setRules([
			'codPerfil' => ['label' => 'codPerfil', 'rules' => 'required|numeric|max_length[11]'],
			'descricao' => ['label' => 'Descrição', 'rules' => 'required|max_length[60]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->PerfisModel->update($fields['codPerfil'], $fields)) {

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

	public function getOnePerfilPessoaMembro()
	{
		$response = array();

		$id = $this->request->getPost('codPessoaMembro');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->perfilPessoasMembroModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}





	public function addPerfilMembroPessoa()
	{

		$response = array();

		$fields['codPessoaMembro'] = $this->request->getPost('codPessoaMembro');
		$fields['codPessoa'] = $this->request->getPost('codPessoa');
		$fields['codPerfil'] = $this->request->getPost('codPerfil');
		$fields['dataInicio'] = $this->request->getPost('dataInicio');
		if ($this->request->getPost('dataEncerramento') == NULL) {
			$fields['dataEncerramento'] = NULL;
		} else {
			$fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');
		}
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] =  date('Y-m-d H:i');

		$this->validation->setRules([
			'codPessoa' => ['label' => 'Membro', 'rules' => 'required|numeric|max_length[11]'],
			'codPerfil' => ['label' => 'CodPerfil', 'rules' => 'required|numeric|max_length[11]'],
			'dataInicio' => ['label' => 'DataInicio', 'rules' => 'required|valid_date'],
			'dataEncerramento' => ['label' => 'DataEncerramento', 'rules' => 'permit_empty|valid_date'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->perfilPessoasMembroModel->insert($fields)) {

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


	public function salvaPermissoes()
	{

		$codPerfil = $this->request->getPost('codPerfil');
		$codOrganizacao = $this->request->getPost('codOrganizacao');

		$response = array();

		$fields['codPerfil'] = $this->request->getPost('codPerfil');
		$fields['codOrganizacao'] = $this->request->getPost('codOrganizacao');


		//LIMPA
		$this->perfisModulosModel->deletePerfilModulo($codPerfil, $codOrganizacao);

		//DEFINE
		$valorListar = '';
		$valorAdicionar = '';
		$valorEditar = '';
		$valorDeletar = '';
		$listar_label = 'listar';
		$adicionar_label = 'adicionar';
		$editar_label = 'editar';
		$deletar_label = 'deletar';

		$comandos = '';

		foreach ($this->request->getPost() as $chave => $atributo) {

			if (strpos($chave,  $listar_label) !== false) {
				$valorListar = str_replace($listar_label, '', $chave);
				$codModulo = $valorListar;
				$listar = 1;

				//verifica se ja existe
				$result = $this->perfisModulosModel->verificaSeExiste($codPerfil, $codModulo);
				if ($result == NULL) {
					//CRIA
					$comandos = array(
						'codOrganizacao' => $codOrganizacao,
						'codPerfil' => $codPerfil,
						'codModulo' => $codModulo,
						'listar' => 1,
					);
					$this->perfisModulosModel->insert($comandos);
				} else {
					//ATUALZA
					$this->perfisModulosModel->atualiza_listar($codPerfil, $codModulo);
				}
			}
			if (strpos($chave,  $adicionar_label) !== false) {
				$valorAdicionar = str_replace($adicionar_label, '', $chave);
				$codModulo = $valorAdicionar;
				$adicionar = 1;
				//verifica se ja existe
				$result = $this->perfisModulosModel->verificaSeExiste($codPerfil, $codModulo);
				if ($result == NULL) {
					//CRIA
					$comandos = array(
						'codOrganizacao' => $codOrganizacao,
						'codPerfil' => $codPerfil,
						'codModulo' => $codModulo,
						'adicionar' => 1,
					);
					$this->perfisModulosModel->insert($comandos);
				} else {
					//ATUALZA
					$this->perfisModulosModel->atualiza_adicionar($codPerfil, $codModulo);
				}
			}
			if (strpos($chave,  $editar_label) !== false) {
				$valorEditar = str_replace($editar_label, '', $chave);
				$codModulo = $valorEditar;
				$editar = 1;
				//verifica se ja existe
				$result = $this->perfisModulosModel->verificaSeExiste($codPerfil, $codModulo);
				if ($result == NULL) {
					//CRIA
					$comandos = array(
						'codOrganizacao' => $codOrganizacao,
						'codPerfil' => $codPerfil,
						'codModulo' => $codModulo,
						'editar' => 1,
					);
					$this->perfisModulosModel->insert($comandos);
				} else {
					//ATUALZA
					$this->perfisModulosModel->atualiza_editar($codPerfil, $codModulo);
				}
			}
			if (strpos($chave,  $deletar_label) !== false) {
				$valorDeletar = str_replace($deletar_label, '', $chave);
				$codModulo = $valorDeletar;
				$deletar = 1;
				//verifica se ja existe
				$result = $this->perfisModulosModel->verificaSeExiste($codPerfil, $codModulo);
				if ($result == NULL) {
					//CRIA
					$comandos = array(
						'codOrganizacao' => $codOrganizacao,
						'codPerfil' => $codPerfil,
						'codModulo' => $codModulo,
						'deletar' => 1,
					);
					$this->perfisModulosModel->insert($comandos);
				} else {
					//ATUALZA
					$this->perfisModulosModel->atualiza_deletar($codPerfil, $codModulo);
				}
			}
		}

		$response['success'] = true;
		$response['csrf_hash'] = csrf_hash();
		$response['messages'] = 'Permissões atualizadas com sucesso!';
		return $this->response->setJSON($response);
	}


	public function editPerfilMembroPessoa()
	{

		$response = array();

		$fields['codPessoaMembro'] = $this->request->getPost('codPessoaMembro');
		$fields['codPessoa'] = $this->request->getPost('codPessoa');
		$fields['codPerfil'] = $this->request->getPost('codPerfil');
		$fields['dataInicio'] = $this->request->getPost('dataInicio');
		if ($this->request->getPost('dataEncerramento') == NULL) {
			$fields['dataEncerramento'] = NULL;
		} else {
			$fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');
		}

		$fields['dataAtualizacao'] =  date('Y-m-d H:i');


		$this->validation->setRules([
			'codPessoaMembro' => ['label' => 'codPessoaMembro', 'rules' => 'required|numeric|max_length[11]'],
			'codPessoa' => ['label' => 'Membro', 'rules' => 'required|numeric|max_length[11]'],
			'codPerfil' => ['label' => 'CodPerfil', 'rules' => 'required|numeric|max_length[11]'],
			'dataInicio' => ['label' => 'DataInicio', 'rules' => 'required|valid_date'],
			'dataEncerramento' => ['label' => 'DataEncerramento', 'rules' => 'permit_empty|valid_date'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->perfilPessoasMembroModel->update($fields['codPessoaMembro'], $fields)) {

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

		$id = $this->request->getPost('codPerfil');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->PerfisModel->where('codPerfil', $id)->delete()) {

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

	public function removePessoaMembro()
	{
		$response = array();

		$id = $this->request->getPost('codPessoaMembro');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->perfilPessoasMembroModel->where('codPessoaMembro', $id)->delete()) {

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
