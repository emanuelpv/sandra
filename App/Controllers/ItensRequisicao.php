<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\ItensRequisicaoModel;
use App\Models\OrcamentosModel;

class ItensRequisicao extends BaseController
{

	protected $ItensRequisicaoModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;
	protected $LogsModel;
	protected $Organizacao;
	protected $OrcamentosModel;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->ItensRequisicaoModel = new ItensRequisicaoModel();
		$this->OrcamentosModel = new OrcamentosModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation = \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao = $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}




	public function itensRequisicao()
	{
		$response = array();

		$data['data'] = array();

		$codRequisicao = $this->request->getPost('codRequisicao');

		$result = $this->ItensRequisicaoModel->pegaItensRequisicao($codRequisicao);



		$x = 0;
		foreach ($result as $key => $value) {

			if ($value->codCat !== NULL) {
				$descricao = '<a target="_blank" href="http://compras.dados.gov.br/licitacoes/v1/licitacoes.html?item_material=' . $value->codCat . '">' . $value->descricao . '</a>';
			} else {
				$descricao = $value->descricao;
			}

			if ($value->qtde_sol == NULL) {
				$qtde_sol = '<span class="right badge badge-danger">Falta</span>';
			} else {
				$qtde_sol = $value->qtde_sol;
			}


			if ($value->valorUnit == NULL) {
				$valorUnit = '<span class="right badge badge-danger">Falta</span>';
			} else {
				$valorUnit = $value->valorUnit;
			}
			if ($value->valorTotal == NULL) {
				$valorTotal = '<span class="right badge badge-danger">Falta</span>';
			} else {
				$valorTotal = $value->valorTotal;
			}

			if ($value->descricaoTipoMaterial == NULL) {
				$descricaoTipoMaterial = '<span class="right badge badge-danger">Falta</span>';
			} else {
				$descricaoTipoMaterial = $value->descricaoTipoMaterial;
			}

			$verificaOrcamentoItem = $this->OrcamentosModel->orcamentosItem($value->codRequisicaoItem);

			$obs =  '';
			$codCat =  '';


			if ($value->codTipoRequisicao == 70 or $value->codTipoRequisicao == 10 or $value->codTipoRequisicao == 30 or $value->codTipoRequisicao == 31) {

				if ($verificaOrcamentoItem == NULL) {
					$obs .=  '<span class="right badge badge-danger"> Falta orçamento</span>';
				} else {
					$obs .= $value->obs;
				}


				if ($value->prioridade == NULL) {
					$obs .=  '<span class="right badge badge-danger"> Falta prioridade</span>';
				}

				if ($value->codCat == NULL) {
					$obs .=  '<span class="right badge badge-danger"> Falta CatMat ou CatServ</span>';
				}

				if ($value->obs == NULL) {
					$obs .=  '<span class="right badge badge-danger"> Falta justificativa</span>';
				}

				if ($value->tipoMaterial == NULL) {
					$obs .=  '<span class="right badge badge-danger"> Falta tipo Material</span>';
				}
			} else {
				$obs = $value->obs;
			}



			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="edititensRequisicao(' . $value->codRequisicaoItem . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeitensRequisicao(' . $value->codRequisicaoItem . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';
			$x++;
			$data['data'][$key] = array(
				$x,
				$value->nrRef,
				$descricao,
				$value->descricaoUnidade,
				$qtde_sol,
				$valorUnit,
				$valorTotal,
				$descricaoTipoMaterial,
				$obs,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne($id = null)
	{
		$response = array();

		if ($id == NULL) {

			$id = $this->request->getPost('codRequisicaoItem');
		}

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->ItensRequisicaoModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		if ($this->request->getPost('nrRef') == 0 or $this->request->getPost('nrRef') == NULL) {
			$nrRef = NULL;
		} else {
			$nrRef = $this->request->getPost('nrRef');
		}
		$fields['codRequisicaoItem'] = $this->request->getPost('codRequisicaoItem');
		$fields['codRequisicao'] = $this->request->getPost('codRequisicao');
		$fields['nrRef'] = $nrRef;
		$fields['codCat'] = $this->request->getPost('codCat');
		$fields['prioridade'] = $this->request->getPost('prioridade');
		$fields['descricao'] = $this->request->getPost('descricao');
		$fields['unidade'] = $this->request->getPost('unidade');
		$fields['qtde_sol'] = $this->request->getPost('qtdeSol');
		$fields['valorUnit'] = brl2decimal($this->request->getPost('valorUnit'));
		$fields['metodoCalculo'] = $this->request->getPost('metodoCalculo'); //1 = Média de preços
		$fields['cod_siasg'] = $this->request->getPost('codSiasg');
		$fields['tipoMaterial'] = $this->request->getPost('tipoMaterial');
		$fields['obs'] = $this->request->getPost('obs');
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['codAutorUltAlteracao'] = session()->codPessoa;


		$this->validation->setRules([
			'NrRef' => ['label' => 'NrRef', 'rules' => 'permit_empty'],
			'descricao' => ['label' => 'Descrição Item', 'rules' => 'required'],
			'unidade' => ['label' => 'Unidade', 'rules' => 'required|max_length[11]'],
			'qtde_sol' => ['label' => 'Qtde Sol', 'rules' => 'required'],
			'valorUnit' => ['label' => 'ValorUnit', 'rules' => 'required'],
			'cod_siasg' => ['label' => 'Cod Siasg', 'rules' => 'permit_empty|max_length[20]'],
			'obs' => ['label' => 'Observação', 'rules' => 'permit_empty'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($codRequisicaoItem = $this->ItensRequisicaoModel->insert($fields)) {



				//RECALCULAR VALOR UNITÁRIO E SUBTOTAL
				$valor = $this->OrcamentosModel->calcularValor($codRequisicaoItem, $this->request->getPost('metodoCalculo'), $fields['valorUnit']);
				$this->OrcamentosModel->calcularTotalGeralPorCodItem($codRequisicaoItem);

				$response['success'] = true;
				$response['codRequisicaoItem'] = $codRequisicaoItem;
				$response['messages'] = 'Informação inserida com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na inserção!';
			}
		}

		return $this->response->setJSON($response);
	}

	public function listaDropDownUnidades()
	{

		$result = $this->ItensRequisicaoModel->listaDropDownUnidades();

		if ($result !== NULL) {

			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}
	public function listaDropDownTipoMaterial()
	{

		$result = $this->ItensRequisicaoModel->listaDropDownTipoMaterial();

		if ($result !== NULL) {

			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function listaDropDownTipoOrcamento()
	{

		$result = $this->ItensRequisicaoModel->listaDropDownTipoOrcamento();

		if ($result !== NULL) {

			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}



	public function edit()
	{

		$response = array();

		if ($this->request->getPost('nrRef') == 0 or $this->request->getPost('nrRef') == NULL) {
			$nrRef = NULL;
		} else {
			$nrRef = $this->request->getPost('nrRef');
		}
		$fields['codRequisicaoItem'] = $this->request->getPost('codRequisicaoItem');
		$fields['nrRef'] = $nrRef;
		$fields['codCat'] = $this->request->getPost('codCat');
		$fields['prioridade'] = $this->request->getPost('prioridade');
		$fields['descricao'] = $this->request->getPost('descricao');
		$fields['unidade'] = $this->request->getPost('unidade');
		$fields['qtde_sol'] = $this->request->getPost('qtdeSol');
		$fields['valorUnit'] = brl2decimal($this->request->getPost('valorUnit'));
		$fields['metodoCalculo'] = brl2decimal($this->request->getPost('metodoCalculo'));
		$fields['cod_siasg'] = $this->request->getPost('codSiasg');
		$fields['tipoMaterial'] = $this->request->getPost('tipoMaterial');
		$fields['obs'] = $this->request->getPost('obs');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['codAutorUltAlteracao'] = session()->codPessoa;


		$this->validation->setRules([
			'codRequisicaoItem' => ['label' => 'codRequisicaoItem', 'rules' => 'required|numeric'],
			'nrRef' => ['label' => 'nrRef', 'rules' => 'permit_empty'],
			'descricao' => ['label' => 'Descrição Item', 'rules' => 'required'],
			'unidade' => ['label' => 'Unidade', 'rules' => 'required|max_length[11]'],
			'qtde_sol' => ['label' => 'Qtde Sol', 'rules' => 'required'],
			'valorUnit' => ['label' => 'ValorUnit', 'rules' => 'required'],
			'cod_siasg' => ['label' => 'Cod Siasg', 'rules' => 'permit_empty|max_length[20]'],
			'obs' => ['label' => 'Observação', 'rules' => 'permit_empty'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->ItensRequisicaoModel->update($fields['codRequisicaoItem'], $fields)) {

				//RECALCULAR VALOR UNITÁRIO E SUBTOTAL
				$valor = $this->OrcamentosModel->calcularValor($fields['codRequisicaoItem'], $fields['metodoCalculo'], $fields['valorUnit']);
				$this->OrcamentosModel->calcularTotalGeralPorCodItem($fields['codRequisicaoItem']);

				$response['success'] = true;
				$response['messages'] = 'Atualizado com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
			}
		}

		return $this->response->setJSON($response);
	}



	public function mudaMetodoCalculo()
	{

		$response = array();

		$fields['codRequisicaoItem'] = $this->request->getPost('codRequisicaoItem');
		$fields['metodoCalculo'] = brl2decimal($this->request->getPost('metodoCalculo'));
		$fields['valorUnit'] = brl2decimal($this->request->getPost('valorUnit'));
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['codAutorUltAlteracao'] = session()->codPessoa;


		$this->validation->setRules([
			'codRequisicaoItem' => ['label' => 'codRequisicaoItem', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->ItensRequisicaoModel->update($fields['codRequisicaoItem'], $fields)) {

				//RECALCULAR VALOR UNITÁRIO E SUBTOTAL
				$valor = $this->OrcamentosModel->calcularValor($fields['codRequisicaoItem'], $fields['metodoCalculo'], $fields['valorUnit']);
				$this->OrcamentosModel->calcularTotalGeralPorCodItem($fields['codRequisicaoItem']);

				$response['valor'] = brl2decimal($valor);
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

		$id = $this->request->getPost('codRequisicaoItem');

		$data = $this->ItensRequisicaoModel->pegaPorCodigo($id);

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			//RECALCULAR VALOR UNITÁRIO E SUBTOTAL

			if ($this->ItensRequisicaoModel->where('codRequisicaoItem', $id)->delete()) {
				$this->ItensRequisicaoModel->removeOrcamentos($id);

				$this->OrcamentosModel->calcularTotalGeralPorCodRequisicao($data->codRequisicao);

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
