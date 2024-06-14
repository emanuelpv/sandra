<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;
use App\Models\PrescricoesMaterialModel;
use App\Models\ModelosMateriaisItensModel;

use App\Models\ModelosMateriaisModel;

class ModelosMateriais extends BaseController
{

	protected $ModelosMateriaisModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->ModelosMateriaisModel = new ModelosMateriaisModel();
		$this->ModelosMateriaisItensModel = new ModelosMateriaisItensModel();
		$this->PrescricoesMaterialModel = new PrescricoesMaterialModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('ModelosMateriais', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "ModelosMateriais"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'ModelosMateriais',
			'title'     		=> 'Modelos Materials'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('ModelosMateriais', $data);
	}


	public function meusModelos()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->ModelosMateriaisModel->pegaMeusModelos();
		$x = 0;
		foreach ($result as $key => $value) {
			$x++;
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-success"  data-toggle="tooltip" data-placement="top" title="Importar"  onclick="usarModeloMaterial(' . $value->codModelo . ')"><i class="fa fa-check"></i> Importar</button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Importar"  onclick="editarModeloMaterial(' . $value->codModelo . ',\'' . mb_strtoupper($value->titulo, "utf-8") . '\')"><i class="fa fa-edit"></i> Editar</button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeModelosMateriais(' . $value->codModelo . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$x,
				$value->titulo,
				date('d/m/Y H:i', strtotime($value->dataCriacao)),
				$value->nomeExibicao,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}


	public function outrosModelos()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->ModelosMateriaisModel->pegaOutrosModelos();
		$x = 0;
		foreach ($result as $key => $value) {
			$x++;
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-success"  data-toggle="tooltip" data-placement="top" title="Importar"  onclick="usarModeloMaterial(' . $value->codModelo . ')"><i class="fa fa-check"></i> Importar</button>';
			$ops .= '</div>';



			$data['data'][$key] = array(

				$x,
				$value->titulo,
				date('d/m/Y H:i', strtotime($value->dataCriacao)),
				$value->nomeExibicao,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}


	public function materiaisModelo()
	{
		$response = array();

		$data['data'] = array();
		$codModelo = $this->request->getPost('codModelo');
		$result = $this->ModelosMateriaisModel->pegaPorCodigoModeloPrescricao($codModelo);

		$x = count($result);
		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			if ($value->stat <= 1) {
				$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editModeloprescricaoMaterials(' . $value->codItemModelo . ')"><i class="fa fa-edit"></i></button>';
				$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeModeloprescricaoMaterials(' . $value->codItemModelo . ')"><i class="fa fa-trash"></i></button>';
			}
			$ops .= '</div>';

			$descricaoStatusMaterial = '<span class="right badge badge-' . $value->corStatusMaterial . '">' . $value->descricaoStatusMaterial . '</span>';

			$data['data'][$key] = array(
				$x,
				$value->descricaoItem,
				$value->qtde,
				$value->observacao,
				$descricaoStatusMaterial,
				$value->nomeExibicao,
				$ops,
			);
			$x--;
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$codItemModelo = $this->request->getPost('codItemModelo');

		if ($this->validation->check($codItemModelo, 'required|numeric')) {

			$data = $this->ModelosMateriaisModel->pegaPorCodigoItem($codItemModelo);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}
	public function usarModelo()
	{

		$response = array();


		$codAtendimentoPrescricao = $this->request->getPost('codAtendimentoPrescricao');
		$codModelo = $this->request->getPost('codModelo');

		$data = $this->ModelosMateriaisItensModel->pegaPorCodModelo($codModelo);

		if (!empty($data)) {


			foreach ($data as $prescricao) {


				$fields['codMaterial'] = $prescricao->codMaterial;
				$fields['codAtendimentoPrescricao'] = $codAtendimentoPrescricao;
				$fields['qtde'] = $prescricao->qtde;
				$fields['codStatus'] = $prescricao->codStatus;
				$fields['observacao'] = $prescricao->observacao;
				$fields['codAutor'] = session()->codPessoa;
				$fields['dataCriacao'] = date('Y-m-d H:i');
				$fields['dataAtualizacao'] = date('Y-m-d H:i');



				//VERIFICA SE MEDICAMENTO JÁ ESTÁ PRESCRITO

				$existe = $this->ModelosMateriaisModel->verificaExistenciaMaterial($codAtendimentoPrescricao, $prescricao->codMaterial);

				if ($existe == NULL) {


					if ($this->PrescricoesMaterialModel->insert($fields)) {
						$response['success'] = true;
						$response['messages'] = 'Importação de modelo realizada com sucesso';
					} else {
						$response['success'] = false;
						$response['messages'] = 'Falha na Importação de medicamentos';
					}
				} else {
					$response['success'] = true;
					$response['messages'] = 'Importação de modelo realizada com sucesso';
				}
			}
		} else {
			$response['success'] = false;
			$response['messages'] = 'Nenhum medicamento para importar';
		}




		return $this->response->setJSON($response);
	}


	public function add()
	{

		$response = array();



		$codAtendimentoPrescricao = $this->request->getPost('codAtendimentoPrescricao');

		$data = $this->ModelosMateriaisModel->pegaPorCodigoAtendimentoPrescricao($codAtendimentoPrescricao);


		if (!empty($data)) {


			$fieldsModelo['titulo'] = $this->request->getPost('titulo');
			$fieldsModelo['codAutor'] = session()->codPessoa;
			$fieldsModelo['dataCriacao'] = date('Y-m-d H:i');
			$fieldsModelo['dataAtualizacao'] = date('Y-m-d H:i');

			if ($codModelo = $this->ModelosMateriaisModel->insert($fieldsModelo)) {


				foreach ($data as $prescricao) {


					$fields['codModelo'] = $codModelo;
					$fields['codMaterial'] = $prescricao->codMaterial;
					$fields['qtde'] = $prescricao->qtde;
					$fields['observacao'] = $prescricao->observacao;
					$fields['codStatus'] = 1;
					$fields['codAutor'] = session()->codPessoa;
					$fields['dataCriacao'] = date('Y-m-d H:i');
					$fields['dataAtualizacao'] = date('Y-m-d H:i');

					if ($this->ModelosMateriaisItensModel->insert($fields)) {
					}
				}
			}
		}
		$response['success'] = true;
		$response['messages'] = 'Modelo criado com sucesso';

		return $this->response->setJSON($response);
	}

	public function addItem()
	{

		$response = array();

		$fields['codModelo'] = $this->request->getPost('codModelo');
		$fields['codMaterial'] = $this->request->getPost('codMaterial');
		$fields['qtde'] = $this->request->getPost('qtde');
		$fields['observacao'] = $this->request->getPost('observacao');
		$fields['codStatus'] = 1;
		$fields['codAutor'] = session()->codPessoa;
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');


		$this->validation->setRules([
			'codModelo' => ['label' => 'codModelo', 'rules' => 'required|numeric|max_length[11]'],
			'codMaterial' => ['label' => 'CodMaterial', 'rules' => 'required|numeric|max_length[11]'],
			'qtde' => ['label' => 'Qtde', 'rules' => 'required'],
			'observacao' => ['label' => 'observacao', 'rules' => 'permit_empty'],
			'dataCriacao' => ['label' => 'dataCriacao', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($fields['codModelo'] !== NULL and $fields['codModelo'] !== "" and $fields['codModelo'] !== " ") {

				if ($this->ModelosMateriaisItensModel->insert($fields)) {

					$response['success'] = true;
					$response['messages'] = 'Item incluído no modelo com sucesso';
				} else {

					$response['success'] = false;
					$response['messages'] = 'Erro na atualização!';
				}
			}
		}

		return $this->response->setJSON($response);
	}


	public function editItem()
	{

		$response = array();

		$fields['codItemModelo'] = $this->request->getPost('codItemModelo');
		$fields['codModelo'] = $this->request->getPost('codModelo');
		$fields['codMaterial'] = $this->request->getPost('codMaterial');
		$fields['qtde'] = $this->request->getPost('qtde');
		$fields['obs'] = $this->request->getPost('obs');
		$fields['codStatus'] = 1;
		$fields['observacao'] = $this->request->getPost('observacao');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');


		$this->validation->setRules([
			'codItemModelo' => ['label' => 'codItemModelo', 'rules' => 'required|numeric|max_length[11]'],
			'codModelo' => ['label' => 'codModelo', 'rules' => 'required|numeric|max_length[11]'],
			'codMaterial' => ['label' => 'CodMaterial', 'rules' => 'required|numeric|max_length[11]'],
			'qtde' => ['label' => 'Qtde', 'rules' => 'required'],
			'observacao' => ['label' => 'observacao', 'rules' => 'permit_empty'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($fields['codItemModelo'] !== NULL and $fields['codItemModelo'] !== "" and $fields['codItemModelo'] !== " ") {

				if ($this->ModelosMateriaisItensModel->update($fields['codItemModelo'], $fields)) {

					$response['success'] = true;
					$response['messages'] = 'Atualizado com sucesso';
				} else {

					$response['success'] = false;
					$response['messages'] = 'Erro na atualização!';
				}
			}
		}

		return $this->response->setJSON($response);
	}


	public function remove()
	{
		$response = array();

		$id = $this->request->getPost('codModelo');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->ModelosMateriaisModel->where('codModelo', $id)->delete()) {

				//REMOVE OS MEDICAMENTOS TAMBEM
				$this->ModelosMateriaisItensModel->removeMateriais($id);

				$response['success'] = true;
				$response['messages'] = 'Deletado com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na deleção!';
			}
		}

		return $this->response->setJSON($response);
	}

	public function removeItem()
	{

		$response = array();

		$codItemModelo = $this->request->getPost('codItemModelo');

		if (!$this->validation->check($codItemModelo, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->ModelosMateriaisModel->removeItem($codItemModelo)) {

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
