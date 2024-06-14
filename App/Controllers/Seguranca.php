<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LogsModel;

class Seguranca extends BaseController
{

	public function __construct()
	{
		$this->validation =  \Config\Services::validation();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);

	}

	public function verificaSeguranca()
	{
		$tipoPermissao =  $this->request->getPost('tipoPermissao');

		$modulo =  $this->request->getPost('modulo');

		$response['verificado'] = true;
		$response['permissao'] = 0;
		$response['mensagem'] = 'Você não tem acesso a este recurso';


		foreach (session()->meusModulos as $meuModulo) {

			if ($meuModulo->link == $modulo) {

				if ($tipoPermissao == 'listar') {
					$response['permissao'] = $meuModulo->listar;
					return $this->response->setJSON($response);
				}
				if ($tipoPermissao == 'adicionar') {
					$response['permissao'] = $meuModulo->adicionar;
					return $this->response->setJSON($response);
				}
				if ($tipoPermissao == 'editar') {
					$response['permissao'] = $meuModulo->editar;
					return $this->response->setJSON($response);
				}
				if ($tipoPermissao == 'deletar') {
					$response['permissao'] = $meuModulo->deletar;
					return $this->response->setJSON($response);
				}
			}
		}

		return $this->response->setJSON($response);
	}
}
