<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\GuiasPacienteModel;

class GuiasPaciente extends BaseController
{

	protected $GuiasPacienteModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		$this->GuiasPacienteModel = new GuiasPacienteModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);

		$configuracao = config('App');
		session()->set('codOrganizacao', $configuracao->codOrganizacao);
		$codOrganizacao = $configuracao->codOrganizacao;
		$dadosOrganizacao = $this->OrganizacoesModel->pegaDadosBasicosOrganizacao($codOrganizacao);

		session()->set('descricaoOrganizacao', $dadosOrganizacao->descricao);
		session()->set('siglaOrganizacao', $dadosOrganizacao->siglaOrganizacao);
		session()->set('logo', $dadosOrganizacao->logo);
	}

	public function index()
	{

		verificaSeguranca($this, session(), base_url());
		$permissao = verificaPermissao('GuiasPaciente', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "GuiasPaciente"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'guiasPaciente',
			'title'     		=> 'Guias Paciente'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('guiasPaciente', $data);
	}

	public function devolucao()
	{

		return view('guiasPacienteDevolucao');
	}

	public function getAll()
	{
		
		verificaSeguranca($this, session(), base_url());
		$response = array();

		$data['data'] = array();

		$result = $this->GuiasPacienteModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-dark"  data-toggle="tooltip" data-placement="top" title="Cancelar"  onclick="cancelarGuiasPaciente(' . $value->codGuia . ')"><i class="fa fa-ban"></i> Cancelar Guia</button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editguiasPaciente(' . $value->codGuia . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeguiasPaciente(' . $value->codGuia . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			if ($value->informacoesComplementares !== NULL) {
				$informacoesComplementares = '
				<span style="font-size:12px;color:red; font-weight: bold;">Informações Complementares</span><br><span style="font-size:12px;color:red">' . $value->informacoesComplementares . '</span>';
			} else {
				$informacoesComplementares = "";
			}

			$data['data'][$key] = array(
				$value->codGuia,
				$value->numeroGuia,
				$value->codPlano,
				'<div class="row">			
					<div class="col-md-12">
						' . $value->nomeBeneficiario . '
					</div>		
					<div class="col-md-12">
						<span class="right badge badge-' . $value->corStatus . '">' . $value->descricaoStatus . '</span>
					</div>			
					<div class="col-md-12">
						' . date('d/m/Y H:i', strtotime($value->dataCriacao)) . '
					</div>	
					<div class="col-md-12">
						' . $informacoesComplementares . '
					</div>
				
				</div>',

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function canceladas()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->GuiasPacienteModel->canceladas();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editguiasPaciente(' . $value->codGuia . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeguiasPaciente(' . $value->codGuia . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';


			if ($value->informacoesComplementares !== NULL) {
				$informacoesComplementares = '
				<span style="font-size:12px;color:red; font-weight: bold;">Informações Complementares</span><br><span style="font-size:12px;color:red">' . $value->informacoesComplementares . '</span>';
			} else {
				$informacoesComplementares = "";
			}

			$data['data'][$key] = array(
				$value->codGuia,
				$value->numeroGuia,
				$value->codPlano,
				'<div class="row">			
					<div class="col-md-12">
						' . $value->nomeBeneficiario . '
					</div>		
					<div class="col-md-12">
						<span class="right badge badge-' . $value->corStatus . '">' . $value->descricaoStatus . '</span>
					</div>			
					<div class="col-md-12">
						' . date('d/m/Y H:i', strtotime($value->dataCriacao)) . '
					</div>	
					<div class="col-md-12">
						' . $informacoesComplementares . '
					</div>
				
				</div>',

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}


	public function listaStatusGuia()
	{

		$result = $this->GuiasPacienteModel->listaStatusGuia();

		if ($result !== NULL) {

			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codGuia');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->GuiasPacienteModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codGuia'] = $this->request->getPost('codGuia');
		$fields['codPaciente'] = $this->request->getPost('codPaciente');
		$fields['valorTotal'] = $this->request->getPost('valorTotal');
		$fields['nomeBeneficiario'] = $this->request->getPost('nomeBeneficiario');
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['codPlano'] = $this->request->getPost('codPlano');
		$fields['codOrganizacao'] = session()->codOrganizacao;
		$fields['numeroGuia'] = $this->request->getPost('numeroGuia');
		$fields['informacoesComplementares'] = $this->request->getPost('informacoesComplementares');


		$this->validation->setRules([
			'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'valorTotal' => ['label' => 'ValorTotal', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'nomeBeneficiario' => ['label' => 'NomeBeneficiario', 'rules' => 'permit_empty|max_length[100]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'permit_empty'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'permit_empty'],
			'codPlano' => ['label' => 'CodPlano', 'rules' => 'permit_empty|max_length[14]'],
			'numeroGuia' => ['label' => 'NumeroGuia', 'rules' => 'permit_empty|max_length[14]'],
			'informacoesComplementares' => ['label' => 'informacoesComplementares', 'rules' => 'permit_empty|max_length[14]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->GuiasPacienteModel->insert($fields)) {

				$response['success'] = true;
				$response['messages'] = 'Informação inserida com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na inserção!';
			}
		}

		return $this->response->setJSON($response);
	}
	public function solicitacaoDevolucao()
	{

		$response = array();

		$fields['numeroGuia'] = $this->request->getPost('numeroGuia');
		$fields['nomeBeneficiario'] = mb_strtoupper($this->request->getPost('nomeBeneficiario'), 'utf-8');
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['codPlano'] = $this->request->getPost('codPlano');
		$fields['codOrganizacao'] = session()->codOrganizacao;


		$this->validation->setRules([
			'numeroGuia' => ['label' => 'Nº da Guia', 'rules' => 'required|bloquearReservado|max_length[20]'],
			'nomeBeneficiario' => ['label' => 'Nome do Beneficiário', 'rules' => 'required|bloquearReservado|max_length[100]'],
			'codPlano' => ['label' => 'Nº Nº Plano', 'rules' => 'required|bloquearReservado|max_length[20]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->GuiasPacienteModel->insert($fields)) {

				$response['success'] = true;
				$response['messages'] = 'Obrigado! Iremos processar o cancelamento desta guia para que o recurso retorne ao sistema e possa ser utilizado por outro beneficiário.';
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

		$fields['codGuia'] = $this->request->getPost('codGuia');
		$fields['codPaciente'] = $this->request->getPost('codPaciente');
		$fields['valorTotal'] = $this->request->getPost('valorTotal');
		$fields['nomeBeneficiario'] = $this->request->getPost('nomeBeneficiario');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['codPlano'] = $this->request->getPost('codPlano');
		$fields['numeroGuia'] = $this->request->getPost('numeroGuia');
		$fields['informacoesComplementares'] = $this->request->getPost('informacoesComplementares');
		$fields['codStatus'] = $this->request->getPost('codStatus');


		$this->validation->setRules([
			'codGuia' => ['label' => 'codGuia', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'valorTotal' => ['label' => 'ValorTotal', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'nomeBeneficiario' => ['label' => 'NomeBeneficiario', 'rules' => 'permit_empty|max_length[100]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'permit_empty'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'permit_empty'],
			'codPlano' => ['label' => 'CodPlano', 'rules' => 'permit_empty|max_length[14]'],
			'numeroGuia' => ['label' => 'NumeroGuia', 'rules' => 'permit_empty|max_length[14]'],
			'codStatus' => ['label' => 'codStatus', 'rules' => 'permit_empty|max_length[14]'],
			'informacoesComplementares' => ['label' => 'informacoesComplementares', 'rules' => 'permit_empty|max_length[14]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->GuiasPacienteModel->update($fields['codGuia'], $fields)) {

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

		$id = $this->request->getPost('codGuia');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->GuiasPacienteModel->where('codGuia', $id)->delete()) {

				$response['success'] = true;
				$response['messages'] = 'Deletado com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na deleção!';
			}
		}

		return $this->response->setJSON($response);
	}

	public function cancelar()
	{

		$response = array();

		$fields['codGuia'] = $this->request->getPost('codGuia');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['codStatus'] = 10;


		$this->validation->setRules([
			'codGuia' => ['label' => 'codGuia', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'codStatus' => ['label' => 'codStatus', 'rules' => 'permit_empty|max_length[14]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->GuiasPacienteModel->update($fields['codGuia'], $fields)) {

				$response['success'] = true;
				$response['messages'] = 'Guia cancelada com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
			}
		}

		return $this->response->setJSON($response);
	}
}
