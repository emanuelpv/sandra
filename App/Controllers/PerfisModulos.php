<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\PerfisModulosModel;

class PerfisModulos extends BaseController
{

	protected $perfisModulosModel;
	protected $pessoasModel;
	protected $organizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->perfisModulosModel = new PerfisModulosModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);



		$permissao = verificaPermissao('PerfisModulos', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo PerfisModulos', session()->codPessoa);
			exit();
		}
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'perfisModulos',
			'title'     		=> 'Perfis Módulos'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('perfisModulos', $data);
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
	public function getAll()
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

			$data = $this->perfisModulosModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codPerfil'] = $this->request->getPost('codPerfil');
		$fields['codModulo'] = $this->request->getPost('codModulo');
		$fields['listar'] = $this->request->getPost('listar');
		$fields['adicionar'] = $this->request->getPost('adicionar');
		$fields['editar'] = $this->request->getPost('editar');
		$fields['deletar'] = $this->request->getPost('deletar');


		$this->validation->setRules([
			'codModulo' => ['label' => 'CodModulo', 'rules' => 'required|numeric|max_length[11]'],
			'listar' => ['label' => 'Listar', 'rules' => 'required|numeric|max_length[11]'],
			'adicionar' => ['label' => 'Adicionar', 'rules' => 'required|numeric|max_length[11]'],
			'editar' => ['label' => 'Editar', 'rules' => 'required|numeric|max_length[11]'],
			'deletar' => ['label' => 'Deletar', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->perfisModulosModel->insert($fields)) {

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

	public function edit()
	{

		$response = array();

		$fields['codPerfil'] = $this->request->getPost('codPerfil');
		$fields['codModulo'] = $this->request->getPost('codModulo');
		$fields['listar'] = $this->request->getPost('listar');
		$fields['adicionar'] = $this->request->getPost('adicionar');
		$fields['editar'] = $this->request->getPost('editar');
		$fields['deletar'] = $this->request->getPost('deletar');


		$this->validation->setRules([
			'codPerfil' => ['label' => 'codPerfil', 'rules' => 'required|numeric|max_length[11]'],
			'codModulo' => ['label' => 'CodModulo', 'rules' => 'required|numeric|max_length[11]'],
			'listar' => ['label' => 'Listar', 'rules' => 'required|numeric|max_length[11]'],
			'adicionar' => ['label' => 'Adicionar', 'rules' => 'required|numeric|max_length[11]'],
			'editar' => ['label' => 'Editar', 'rules' => 'required|numeric|max_length[11]'],
			'deletar' => ['label' => 'Deletar', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->perfisModulosModel->update($fields['codPerfil'], $fields)) {

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

			if ($this->perfisModulosModel->where('codPerfil', $id)->delete()) {

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
