<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LogsModel;
use App\Models\OrganizacoesModel;

class Dashboards extends BaseController
{
	protected $usuariosModel;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->LogsModel = new LogsModel();

		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->validation =  \Config\Services::validation();

		$permissao = verificaPermissao('Dashboards', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo Dashboards', session()->codPessoa);
			exit();
		}
	}

	public function index()
	{


		$data['organizacao'] = $this->OrganizacoesModel->select('codOrganizacao,descricao')->findAll();
		helper('form');
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('dashboards', $data);
	}
}
