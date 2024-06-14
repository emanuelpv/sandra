<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\FaturamentoTaxasServicosModel;
use App\Models\DepartamentosModel;
use App\Models\TaxasServicosModel;

class FaturamentoTaxasServicos extends BaseController
{

	protected $FaturamentoTaxasServicosModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->FaturamentoTaxasServicosModel = new FaturamentoTaxasServicosModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->DepartamentosModel = new DepartamentosModel();
		$this->TaxasServicosModel = new TaxasServicosModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('FaturamentoTaxasServicos', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "FaturamentoTaxasServicos"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'faturamentoTaxasServicos',
			'title'     		=> 'Faturamento de Taxas e Servicos'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('faturamentoTaxasServicos', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->FaturamentoTaxasServicosModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editfaturamentoTaxasServicos(' . $value->codFaturamentoTaxasServico . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removefaturamentoTaxasServicos(' . $value->codFaturamentoTaxasServico . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$value->codFaturamentoTaxasServico,
				$value->codAtendimento,
				$value->codTaxaServico,
				$value->quantidade,
				$value->valor,
				$value->codLocalAtendimento,
				$value->dataCriacao,
				$value->dataAtualizacao,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function taxasServicosFaturados()
	{
		$response = array();

		$data['data'] = array();


		$codFatura = $this->request->getPost('codFatura');

		$result = $this->FaturamentoTaxasServicosModel->taxasServicosFaturados($codFatura);
		$x = 0;
		foreach ($result as $key => $value) {
			$x++;
			$ops = '<div class="btn-group">';
			if ($value->codStatusFatura == 0) {
				$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removefaturamentoTaxasServicos(' . $value->codFaturamentoTaxasServico . ')"><i class="fa fa-trash"></i></button>';
			}
			$ops .= '</div>';



			$descricaoStatus = '<span class="right badge badge-' . $value->corStatusTaxaServico . '">' . $value->descricaoStatusTaxaServico . '</span>';

			$statusFaturado = "";
			$statusFaturadoeAuditado = "";
			$statusLancadoSire = "";

			if ($value->codStatus == 8) {
				$statusFaturado = "selected";
			}
			if ($value->codStatus == 9) {
				$statusFaturadoeAuditado = "selected";
			}
			if ($value->codStatus == 10) {
				$statusLancadoSire = "selected";
			}


			$data['data'][$key] = array(
				$x,
				$value->descricaoDepartamento . ' (' . $value->descricaoLocalAtendimento . ')',
				$value->referencia,
				$value->descricaoTaxaServico . '<div style="font-size:10px;color:red">' . $value->observacoes . '</div>',
				'<input style="width:50px;display:none" class="editarTaxas" id="editarTaxasQtde' . $value->codFaturamentoTaxasServico . '" name="editarQtde' . $value->codFaturamentoTaxasServico . '" value="' . $value->quantidade . '"><span class="verTaxas">' . $value->quantidade . '</span>',
				round($value->valor,2),
				'<span class="ver" >R$ ' . round($value->subTotal,2) . '</span>',
				'De ' . date('d/m/Y', strtotime($value->dataInicio)) . ' a ' . date('d/m/Y', strtotime($value->dataEncerramento)),
				$value->nomeAuditor,
				$descricaoStatus,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codFaturamentoTaxasServico');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->FaturamentoTaxasServicosModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codFaturamentoTaxasServico'] = $this->request->getPost('codFaturamentoTaxasServico');
		$fields['codAtendimento'] = $this->request->getPost('codAtendimento');
		$fields['codTaxaServico'] = $this->request->getPost('codTaxaServico');
		$fields['quantidade'] = $this->request->getPost('quantidade');
		$fields['valor'] = $this->request->getPost('valor');
		$fields['codLocalAtendimento'] = $this->request->getPost('codLocalAtendimento');
		$fields['dataCriacao'] = $this->request->getPost('dataCriacao');
		$fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');


		$this->validation->setRules([
			'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'codTaxaServico' => ['label' => 'codTaxaServico', 'rules' => 'required|numeric|max_length[11]'],
			'quantidade' => ['label' => 'Quantidade', 'rules' => 'required|numeric|max_length[11]'],
			'valor' => ['label' => 'Valor', 'rules' => 'required'],
			'codLocalAtendimento' => ['label' => 'CodLocalAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->FaturamentoTaxasServicosModel->insert($fields)) {

				$response['success'] = true;
				$response['messages'] = 'Informação inserida com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na inserção!';
			}
		}

		return $this->response->setJSON($response);
	}
	public function addIndividual()
	{

		$response = array();


		$item  = $this->TaxasServicosModel->pegaPorCodigo($this->request->getPost('codTaxaServico'));



		$fields['codFatura'] = $this->request->getPost('codFatura');
		$fields['codAtendimento'] = $this->request->getPost('codAtendimento');
		$fields['codTaxaServico'] = $this->request->getPost('codTaxaServico');
		$fields['quantidade'] = $this->request->getPost('quantidade');
		$fields['dataInicio'] = $this->request->getPost('dataInicio');
		$fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');
		$fields['valor'] = $item->valor;
		$fields['codStatus'] = 8;
		$fields['codLocalAtendimento'] = $this->request->getPost('codLocalAtendimento');
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');


		$this->validation->setRules([
			'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'codTaxaServico' => ['label' => 'codTaxaServico', 'rules' => 'required|numeric|max_length[11]'],
			'quantidade' => ['label' => 'Quantidade', 'rules' => 'required|numeric|max_length[11]'],
			'valor' => ['label' => 'Valor', 'rules' => 'required'],
			'codLocalAtendimento' => ['label' => 'CodLocalAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->FaturamentoTaxasServicosModel->insert($fields)) {

				$response['success'] = true;
				$response['messages'] = 'Informação inserida com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = json_encode($fields);
			}
		}

		return $this->response->setJSON($response);
	}
	public function edit()
	{

		$response = array();

		$fields['codFaturamentoTaxasServico'] = $this->request->getPost('codFaturamentoTaxasServico');
		$fields['codAtendimento'] = $this->request->getPost('codAtendimento');
		$fields['codTaxaServico'] = $this->request->getPost('codTaxaServico');
		$fields['quantidade'] = $this->request->getPost('quantidade');
		$fields['valor'] = $this->request->getPost('valor');
		$fields['codLocalAtendimento'] = $this->request->getPost('codLocalAtendimento');
		$fields['dataCriacao'] = $this->request->getPost('dataCriacao');
		$fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');


		$this->validation->setRules([
			'codFaturamentoTaxasServico' => ['label' => 'codFaturamentoTaxasServico', 'rules' => 'required|numeric|max_length[11]'],
			'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'codTaxaServico' => ['label' => 'codTaxaServico', 'rules' => 'required|numeric|max_length[11]'],
			'quantidade' => ['label' => 'Quantidade', 'rules' => 'required|numeric|max_length[11]'],
			'valor' => ['label' => 'Valor', 'rules' => 'required'],
			'codLocalAtendimento' => ['label' => 'CodLocalAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->FaturamentoTaxasServicosModel->update($fields['codFaturamentoTaxasServico'], $fields)) {

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

		$id = $this->request->getPost('codFaturamentoTaxasServico');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->FaturamentoTaxasServicosModel->where('codFaturamentoTaxasServico', $id)->delete()) {

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
