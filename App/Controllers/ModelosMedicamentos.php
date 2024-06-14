<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;
use App\Models\PrescricaoMedicamentosModel;
use App\Models\ModelosMedicamentosItensModel;

use App\Models\ModelosMedicamentosModel;

class ModelosMedicamentos extends BaseController
{

	protected $ModelosMedicamentosModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->ModelosMedicamentosModel = new ModelosMedicamentosModel();
		$this->PrescricaoMedicamentosModel = new PrescricaoMedicamentosModel();
		$this->ModelosMedicamentosItensModel = new ModelosMedicamentosItensModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('ModelosMedicamentos', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "ModelosMedicamentos"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'ModelosMedicamentos',
			'title'     		=> 'Modelos Medicamentos'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('ModelosMedicamentos', $data);
	}


	public function meusModelos()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->ModelosMedicamentosModel->pegaMeusModelos();
		$x = 0;
		foreach ($result as $key => $value) {
			$x++;
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-success"  data-toggle="tooltip" data-placement="top" title="Importar"  onclick="usarModeloMedicamento(' . $value->codModelo . ')"><i class="fa fa-check"></i> Importar</button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Importar"  onclick="editarModeloMedicamento(' . $value->codModelo . ',\'' . mb_strtoupper($value->titulo,"utf-8") . '\')"><i class="fa fa-edit"></i> Editar</button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeModelosMedicamentos(' . $value->codModelo . ')"><i class="fa fa-trash"></i></button>';
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

		$result = $this->ModelosMedicamentosModel->pegaOutrosModelos();
		$x = 0;
		foreach ($result as $key => $value) {
			$x++;
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-success"  data-toggle="tooltip" data-placement="top" title="Importar"  onclick="usarModeloMedicamento(' . $value->codModelo . ')"><i class="fa fa-check"></i> Importar</button>';
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


	public function medicamentosModelo()
	{
		$response = array();

		$data['data'] = array();
		$codModelo = $this->request->getPost('codModelo');
		$result = $this->ModelosMedicamentosModel->pegaPorCodigoModeloPrescricao($codModelo);

		$x = count($result);
		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			if ($value->stat <= 1) {
				$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editModeloprescricaoMedicamentos(' . $value->codItemModelo . ')"><i class="fa fa-edit"></i></button>';
				$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeModeloprescricaoMedicamentos(' . $value->codItemModelo . ')"><i class="fa fa-trash"></i></button>';
			}
			$ops .= '</div>';

			$descricaoStatusPrescricao = '<span class="right badge badge-' . $value->corStatusPrescricao . '">' . $value->descricaoStatusPrescricao . '</span>';
			$statusRisco = '<span class="right badge badge-' . $value->corRiscoPrescricao . '">' . $value->descricaoRiscoPrescricao . '</span>';

			$data['data'][$key] = array(
				$x,
				$value->descricaoItem,
				$value->qtde,
				$value->descricaoUnidade,
				$value->descricaoVia,
				'<div>
                Frequência: ' . $value->freq . '
                </div>
                <div>
                Período: ' . $value->descricaoPeriodo . '
                </div>
                <div>
                Nº Dias: ' . $value->dias . '
                </div>
                Início: ' . $value->horaIni . '
                </div>',
				'<div>
                Agora: ' . $value->descricaoAplicarAgora . '
                </div>
                <div>
                Risco: ' . $statusRisco . '
                </div>',
				$value->obs,
				$value->total,
				$descricaoStatusPrescricao,
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

			$data = $this->ModelosMedicamentosModel->pegaPorCodigoItem($codItemModelo);

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

		$data = $this->ModelosMedicamentosItensModel->pegaPorCodModelo($codModelo);

		if (!empty($data)) {


			foreach ($data as $prescricao) {


				$fields['codAtendimentoPrescricao'] = $codAtendimentoPrescricao;
				$fields['codMedicamento'] = $prescricao->codMedicamento;
				$fields['qtde'] = $prescricao->qtde;
				$fields['und'] = $prescricao->und;
				$fields['via'] = $prescricao->via;
				$fields['freq'] = $prescricao->freq;
				$fields['per'] = $prescricao->per;
				$fields['dias'] = $prescricao->dias;
				$fields['horaIni'] = $prescricao->horaIni;
				$fields['agora'] = $prescricao->agora;
				$fields['risco'] = $prescricao->risco;
				$fields['obs'] = $prescricao->obs;
				$fields['apraza'] = $prescricao->apraza;
				$fields['total'] = $prescricao->total;
				$fields['stat'] = 1;
				$fields['codAutor'] = session()->codPessoa;
				$fields['dataCriacao'] = date('Y-m-d H:i');
				$fields['dataAtualizacao'] = date('Y-m-d H:i');

				//VERIFICA SE MEDICAMENTO JÁ ESTÁ PRESCRITO

				$existe = $this->PrescricaoMedicamentosModel->verificaExistenciaMedicamento($codAtendimentoPrescricao, $prescricao->codMedicamento);

				if ($existe == NULL) {


					if ($this->PrescricaoMedicamentosModel->insert($fields)) {
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

		$data = $this->PrescricaoMedicamentosModel->pegaPorCodigoAtendimentoPrescricao($codAtendimentoPrescricao);


		if (!empty($data)) {


			$fieldsModelo['titulo'] = $this->request->getPost('titulo');
			$fieldsModelo['codAutor'] = session()->codPessoa;
			$fieldsModelo['dataCriacao'] = date('Y-m-d H:i');
			$fieldsModelo['dataAtualizacao'] = date('Y-m-d H:i');

			if ($codModelo = $this->ModelosMedicamentosModel->insert($fieldsModelo)) {


				foreach ($data as $prescricao) {


					$fields['codModelo'] = $codModelo;
					$fields['codMedicamento'] = $prescricao->codMedicamento;
					$fields['qtde'] = $prescricao->qtde;
					$fields['und'] = $prescricao->und;
					$fields['via'] = $prescricao->via;
					$fields['freq'] = $prescricao->freq;
					$fields['per'] = $prescricao->per;
					$fields['dias'] = $prescricao->dias;
					$fields['horaIni'] = $prescricao->horaIni;
					$fields['agora'] = $prescricao->agora;
					$fields['risco'] = $prescricao->risco;
					$fields['obs'] = $prescricao->obs;
					$fields['apraza'] = $prescricao->apraza;
					$fields['total'] = $prescricao->total;
					$fields['stat'] = 1;
					$fields['codAutor'] = session()->codPessoa;
					$fields['dataCriacao'] = date('Y-m-d H:i');
					$fields['dataAtualizacao'] = date('Y-m-d H:i');

					if ($this->ModelosMedicamentosItensModel->insert($fields)) {
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
		$fields['codMedicamento'] = $this->request->getPost('codMedicamento');
		$fields['qtde'] = $this->request->getPost('qtde');
		$fields['und'] = $this->request->getPost('und');
		$fields['via'] = $this->request->getPost('via');
		$fields['freq'] = $this->request->getPost('freq');
		$fields['per'] = $this->request->getPost('per');
		$fields['dias'] = $this->request->getPost('dias');
		$fields['horaIni'] = $this->request->getPost('horaIni');
		$fields['agora'] = $this->request->getPost('agora');
		$fields['risco'] = $this->request->getPost('risco');
		$fields['obs'] = $this->request->getPost('obs');
		$fields['total'] = $this->request->getPost('total');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');


		$this->validation->setRules([
			'codModelo' => ['label' => 'codModelo', 'rules' => 'required|numeric|max_length[11]'],
			'codMedicamento' => ['label' => 'CodMedicamento', 'rules' => 'required|numeric|max_length[11]'],
			'qtde' => ['label' => 'Qtde', 'rules' => 'required'],
			'und' => ['label' => 'Und', 'rules' => 'required|max_length[11]'],
			'via' => ['label' => 'Via', 'rules' => 'required|max_length[11]'],
			'freq' => ['label' => 'Freq', 'rules' => 'required|numeric|max_length[2]'],
			'per' => ['label' => 'Per', 'rules' => 'required|max_length[11]'],
			'dias' => ['label' => 'Dias', 'rules' => 'required|numeric|max_length[3]'],
			'horaIni' => ['label' => 'HoraIni', 'rules' => 'permit_empty|max_length[10]'],
			'agora' => ['label' => 'Agora', 'rules' => 'required|max_length[1]'],
			'risco' => ['label' => 'Risco', 'rules' => 'required|max_length[11]'],
			'obs' => ['label' => 'Obs', 'rules' => 'permit_empty'],
			'total' => ['label' => 'Total', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($fields['codModelo'] !== NULL and $fields['codModelo'] !== "" and $fields['codModelo'] !== " ") {

				if ($this->ModelosMedicamentosItensModel->insert($fields)) {

					$response['success'] = true;
					$response['messages'] = 'Adicionado com sucesso';
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
		$fields['codMedicamento'] = $this->request->getPost('codMedicamento');
		$fields['qtde'] = $this->request->getPost('qtde');
		$fields['und'] = $this->request->getPost('und');
		$fields['via'] = $this->request->getPost('via');
		$fields['freq'] = $this->request->getPost('freq');
		$fields['per'] = $this->request->getPost('per');
		$fields['dias'] = $this->request->getPost('dias');
		$fields['horaIni'] = $this->request->getPost('horaIni');
		$fields['agora'] = $this->request->getPost('agora');
		$fields['risco'] = $this->request->getPost('risco');
		$fields['obs'] = $this->request->getPost('obs');
		$fields['total'] = $this->request->getPost('total');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');


		$this->validation->setRules([
			'codItemModelo' => ['label' => 'codItemModelo', 'rules' => 'required|numeric|max_length[11]'],
			'codModelo' => ['label' => 'codModelo', 'rules' => 'required|numeric|max_length[11]'],
			'codMedicamento' => ['label' => 'CodMedicamento', 'rules' => 'required|numeric|max_length[11]'],
			'qtde' => ['label' => 'Qtde', 'rules' => 'required'],
			'und' => ['label' => 'Und', 'rules' => 'required|max_length[11]'],
			'via' => ['label' => 'Via', 'rules' => 'required|max_length[11]'],
			'freq' => ['label' => 'Freq', 'rules' => 'required|numeric|max_length[2]'],
			'per' => ['label' => 'Per', 'rules' => 'required|max_length[11]'],
			'dias' => ['label' => 'Dias', 'rules' => 'required|numeric|max_length[3]'],
			'horaIni' => ['label' => 'HoraIni', 'rules' => 'permit_empty|max_length[10]'],
			'agora' => ['label' => 'Agora', 'rules' => 'required|max_length[1]'],
			'risco' => ['label' => 'Risco', 'rules' => 'required|max_length[11]'],
			'obs' => ['label' => 'Obs', 'rules' => 'permit_empty'],
			'total' => ['label' => 'Total', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($fields['codItemModelo'] !== NULL and $fields['codItemModelo'] !== "" and $fields['codItemModelo'] !== " ") {

				if ($this->ModelosMedicamentosItensModel->update($fields['codItemModelo'], $fields)) {

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

			if ($this->ModelosMedicamentosModel->where('codModelo', $id)->delete()) {

				//REMOVE OS MEDICAMENTOS TAMBEM
				$this->ModelosMedicamentosItensModel->removeMedicamentos($id);

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

			if ($this->ModelosMedicamentosModel->removeItem($codItemModelo)) {

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
