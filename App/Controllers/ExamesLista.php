<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrganizacoesModel;
use App\Models\PessoasModel;
use App\Models\LogsModel;
use App\Models\ExamesListaMembroModel;

use App\Models\ExamesListaModel;

class ExamesLista extends BaseController
{

	protected $examesListaModel;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->ExamesListaModel = new ExamesListaModel();
		$this->ExamesListaMembroModel = new ExamesListaMembroModel();
		$this->PessoasModel = new PessoasModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
	}

	public function index()
	{

		$permissao = verificaPermissao('ExamesLista', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo ExamesLista', session()->codPessoa);
			exit();
		}

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());



		$data = [
			'controller'    	=> 'examesLista',
			'title'     		=> 'ExamesLista'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('examesLista', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->ExamesListaModel->pegaExamesLista();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-xs btn-primary" onclick="edit(' . $value->codExameLista . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="remove(' . $value->codExameLista . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			if ($this->ExamesListaModel->pegaConselho($value->codConselho) == NULL) {
				$codConselho = null;
			} else {
				$codConselho = $this->ExamesListaModel->pegaConselho($value->codConselho)->nomeConselho;
			}


			if ($value->codTipo == 1) {
				$tipo = "Médica";
			} else {
				$tipo = "Administrativo";
			}


			if ($value->cadastroReserva == 1) {
				$cadastroReserva = "Sim";
			} else {
				$cadastroReserva = "Não";
			}


			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codExameLista,
				$value->descricaoExameLista,
				$tipo,
				$codConselho,
				$value->numeroConselho,
				$cadastroReserva,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}



	public function pegaMembro()
	{
		$response = array();

		$id = $this->request->getPost('codExameListaMembro');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->ExamesListaMembroModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function pegaMembros($codExameLista = null)
	{
		$response = array();

		$data['data'] = array();

		if ($codExameLista == NULL) {
			$codExameLista = $this->request->getPost('codExameLista');
		}
		$result = $this->ExamesListaModel->pegaMembros($codExameLista);

		$x = 0;
		foreach ($result as $key => $value) {

			$x++;

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-xs btn-primary" onclick="editexamesListaMembro(' . $value->codExameListaMembro . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="removeexamesListaMembro(' . $value->codExameListaMembro . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';


			$atende = '';


			if ($value->ativo == 0) {
				$ativo = ' <span><i style="font-size:20px" class="fas fa-user-slash text-danger"></i></span>';
			}
			if ($value->ativo == 1) {
				$ativo = ' <span><i style="font-size:20px" class="fas fa-user text-success"></i></span>';

				if ($value->atende == 1) {
					$atende = ' <span><i style="font-size:20px" class="fas fa-check text-success"></i></span>';
				}
			}

			if ($value->codTipo == 1) {
				$tipo = "Médica";
			} else {
				$tipo = "Administrativo";
			}

			if ($value->descricaoMotivoInativacao !== NULL) {
				$descricaoMotivoInativacao = " (" . $value->descricaoMotivoInativacao . ")";
			}


			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$x . $ativo,
				$value->nomeExibicao . $descricaoMotivoInativacao,
				$value->numeroInscricao,
				$value->siglaEstadoFederacao,
				$value->numeroSire,
				mb_substr($value->observacoes, 0, 30),
				$atende,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}



	public function listaDropDownPessoas()
	{

		$result = $this->PessoasModel->listaDropDownPessoas();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function listaDropDownEstadosFederacao()
	{

		$result = $this->ExamesListaModel->listaDropDownEstadosFederacao();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function importarExamesLista()
	{
		ini_set('max_execution_time', '0'); //300 seconds = 5 minutes
		set_time_limit(0);


		//IMPORTAR ESPECIALIDADES
		$examesListaApolo = $this->ExamesListaModel->examesListaApolo();


		foreach ($examesListaApolo as $especilidadeApolo) {
			$fields = array();


			$fields['codOrganizacao'] = session()->codOrganizacao;
			$fields['descricaoExameLista'] = $especilidadeApolo->nome_esp;
			$fields['codConselho'] = conselhoLookup($especilidadeApolo->tipo);
			$fields['numeroConselho'] = $especilidadeApolo->cod_cons;
			$fields['codTipo'] = 1;


			$especilidade = $this->ExamesListaModel->pegaexameListaPorNome($especilidadeApolo->nome_esp);

			if ($especilidade !== NULL and $especilidade->codExameLista !== NULL) {
				$this->ExamesListaModel->update($especilidade->codExameLista, $fields);
			} else {
				$this->ExamesListaModel->insert($fields);
			}
		}




		//IMPORTAR MEMBROS
		$membrosExamesListaApolo = $this->ExamesListaModel->membrosExamesListaApolo();


		foreach ($membrosExamesListaApolo as $membroEspecilidadeApolo) {

			$membros = array();


			$var = $membroEspecilidadeApolo->insc_data;
			$date = str_replace('/', '-', $var);
			$dataInscricao = date('Y-m-d', strtotime($date));

			if ($membroEspecilidadeApolo->atende == 'SIM') {
				$atende = 1;
			} else {
				$atende = 0;
			}


			$membros['codOrganizacao'] = session()->codOrganizacao;

			$membros['codExameLista'] = lookupCodExameLista($membroEspecilidadeApolo->nome_esp);


			$membros['codPessoa'] = lookupCodPessoaPorCODPLANOIntegracaoApolo($membroEspecilidadeApolo->prec_cp);
			$membros['codEstadoFederacao'] = lookupcodEstadoFederacao($membroEspecilidadeApolo->Sigla);
			$membros['numeroInscricao'] = $membroEspecilidadeApolo->inscricao;
			$membros['dataInscricao'] = $dataInscricao;
			$membros['numeroSire'] = $membroEspecilidadeApolo->cod_sire;
			$membros['observacoes'] = $membroEspecilidadeApolo->nome_esp;
			$membros['dataCriacao'] = date('Y-m-d H:i');
			$membros['dataAtualizacao'] = date('Y-m-d H:i');
			$membros['autor'] = $membroEspecilidadeApolo->resp;
			$membros['atende'] = $atende;


			if ($membros['codPessoa'] !== NULL and $membros['codExameLista'] !== NULL) {

				$membrosEspecilidade = $this->ExamesListaModel->pegaMembroPorExameListaECodPessoa($membros['codExameLista'], $membros['codPessoa']);

				if ($membrosEspecilidade !== NULL) {
					@$this->ExamesListaMembroModel->update($membrosEspecilidade->codExameListaMembro, $membros);
				} else {

					@$this->ExamesListaMembroModel->insert($membros);
				}
			}
		}




		$response['success'] = true;
		$response['csrf_hash'] = csrf_hash();
		$response['messages'] = 'Importação de examesLista e membros realizada com sucesso';
		return $this->response->setJSON($response);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codExameLista');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->ExamesListaModel->where('codExameLista', $id)->first();

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();



		$fields['codOrganizacao'] = session()->codOrganizacao;
		$fields['codExameLista'] = $this->request->getPost('codExameLista');
		$fields['descricaoExameLista'] = $this->request->getPost('descricaoExameLista');
		$fields['codTipo'] = $this->request->getPost('codTipo');
		$fields['codTipoAgenda'] = $this->request->getPost('codTipoAgenda');
		$fields['codConselho'] = $this->request->getPost('codConselho');
		$fields['numeroConselho'] = $this->request->getPost('numeroConselho');
		$fields['cadastroReserva'] = $this->request->getPost('cadastroReserva');


		$this->validation->setRules([
			'descricaoExameLista' => ['label' => 'Descrição exameLista', 'rules' => 'required|max_length[60]'],
			'numeroConselho' => ['label' => 'Número Conselho', 'rules' => 'required|max_length[12]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->ExamesListaModel->insert($fields)) {

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

	public function editMembro()
	{

		$response = array();

		$fields = array();



		$fields['codExameListaMembro'] = $this->request->getPost('codExameListaMembro');
		$fields['codOrganizacao'] = session()->codOrganizacao;
		$fields['codExameLista'] = $this->request->getPost('codExameLista');
		$fields['codPessoa'] = $this->request->getPost('codPessoa');
		$fields['codEstadoFederacao'] = $this->request->getPost('codEstadoFederacao');
		$fields['numeroInscricao'] = $this->request->getPost('numeroInscricao');
		$fields['numeroSire'] = $this->request->getPost('numeroSire');
		$fields['observacoes'] = $this->request->getPost('observacoes');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['autor'] = session()->codPessoa;

		if ($this->request->getPost('atende') == 'on') {
			$fields['atende'] = '1';
		} else {
			$fields['atende'] = '0';
		}


		$this->validation->setRules([
			'codExameListaMembro' => ['label' => 'codExameListaMembro', 'rules' => 'required|numeric'],
			'atende' => ['label' => 'Atende', 'rules' => 'permit_empty'],
			'codExameLista' => ['label' => 'Código ExameLista', 'rules' => 'required'],
			'codPessoa' => ['label' => 'Código Especialista', 'rules' => 'required|numeric'],
			'codEstadoFederacao' => ['label' => 'Código Estado', 'rules' => 'required|numeric'],
			'numeroInscricao' => ['label' => 'Número Inscrição Conselho', 'rules' => 'required|max_length[20]'],
			'numeroSire' => ['label' => 'Número do Sire', 'rules' => 'permit_empty|max_length[20]'],
			'observacoes' => ['label' => 'Observações', 'rules' => 'permit_empty|max_length[500]'],

		]);

		if ($this->validation->run($fields) == FALSE) {



			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->ExamesListaMembroModel->update($fields['codExameListaMembro'], $fields)) {

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

	public function AddMembro()
	{

		$response = array();

		$fields = array();


		$fields['codOrganizacao'] = session()->codOrganizacao;
		$fields['codExameLista'] = $this->request->getPost('codExameLista');
		$fields['codPessoa'] = $this->request->getPost('codPessoa');
		$fields['codEstadoFederacao'] = $this->request->getPost('codEstadoFederacao');
		$fields['numeroInscricao'] = $this->request->getPost('numeroInscricao');
		$fields['numeroSire'] = $this->request->getPost('numeroSire');
		$fields['observacoes'] = $this->request->getPost('observacoes');
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['autor'] = session()->codPessoa;

		if ($this->request->getPost('atende') == 'on') {
			$fields['atende'] = '1';
		} else {
			$fields['atende'] = '0';
		}


		$existe = $this->ExamesListaMembroModel->pegaExameListaPorMembro($fields['codExameLista'], $fields['codPessoa']);

		if ($existe !== NULL) {
			$response['success'] = false;
			$response['messages'] = 'Especialista já é membro';

			return $this->response->setJSON($response);
		}

		$this->validation->setRules([
			'atende' => ['label' => 'Atende', 'rules' => 'permit_empty'],
			'codExameLista' => ['label' => 'Código ExameLista', 'rules' => 'required'],
			'codPessoa' => ['label' => 'Código Especialista', 'rules' => 'required|numeric'],
			'codEstadoFederacao' => ['label' => 'Código Estado', 'rules' => 'required|numeric'],
			'numeroInscricao' => ['label' => 'Número Inscrição Conselho', 'rules' => 'required|max_length[20]'],
			'numeroSire' => ['label' => 'Número do Sire', 'rules' => 'permit_empty|max_length[20]'],
			'observacoes' => ['label' => 'Observações', 'rules' => 'permit_empty|max_length[500]'],

		]);

		if ($this->validation->run($fields) == FALSE) {



			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->ExamesListaMembroModel->insert($fields)) {

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

	public function listaDropDownExamesLista()
	{

		$result = $this->ExamesListaModel->listaDropDownExamesLista();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}
	public function listaDropDownExamesListaDisponivelMarcacao()
	{

		$result = $this->ExamesListaModel->listaDropDownExamesListaDisponivelMarcacao();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function statusMinhasExamesLista()
	{
		$response = array();

		if (empty(session()->minhasExamesLista)) {
			$statusMinhasExamesLista = 0;
		} else {

			$statusMinhasExamesLista = 1;

			$selectExameListaAtendimento = '<div>Você atende por mais de uma exameLista. Antes de iniciar o atendimento defina por qual você irá atender.</div><select required style="width:300px" id="codExameListaAtendimento" name="codExameListaAtendimento" class="custom-select"><option value=""></option>';

			foreach (session()->minhasExamesLista as $minhaExameLista) {
				$selectExameListaAtendimento .= '<option value="' . $minhaExameLista->codExameLista . '">' . $minhaExameLista->descricaoExameLista . '</option>';
			}
			$selectExameListaAtendimento .= '</select>';
		}




		if (session()->codLocalAtendimento !== null) {
			$codLocalAtendimento = session()->codLocalAtendimento;
		} else {
			$codLocalAtendimento =  0;
		}
		if (session()->nomeLocalAtendimento !== null) {
			$nomeLocalAtendimento = session()->nomeLocalAtendimento;
		}


		//VERIFICA SE JÁ DEFINIDA A ESPECIALIDADE DA SESSÃO

		$response['codExameListaAtendimento'] = session()->codExameListaAtendimento;

		$response['success'] = true;
		$response['csrf_hash'] = csrf_hash();
		$response['statusMinhasExamesLista'] = $statusMinhasExamesLista;
		$response['selectExameListaAtendimento'] = $selectExameListaAtendimento;
		$response['codLocalAtendimento'] = $codLocalAtendimento;
		$response['nomeLocalAtendimento'] = $nomeLocalAtendimento;
		$response['nomeExameListaAtendimento'] = lookupNomeExameLista(session()->codExameListaAtendimento);
		return $this->response->setJSON($response);
	}



	public function listaDropDownEspecialistasDisponivelMarcacaoManual()
	{

		$response = array();
		$codExameLista = $this->request->getPost('codExameLista');
		$especialistas = $this->ExamesListaModel->listaDropDownEspecialistasDisponivelMarcacao($codExameLista);

		$html = '';

		$html .= '
			<select id="codEspecialistaReserva" name="codEspecialista" class="custom-select" required>
			<option value="0">Qualquer especialista</option>';

		foreach ($especialistas as $especialista) {
			$html .= '<option value="' . $especialista->id . '">' . $especialista->text . '</option>';
		}

		$html  .= '</select>';


		$response['success'] = true;
		$response['csrf_hash'] = csrf_hash();
		$response['html'] = $html;
		return $this->response->setJSON($response);
	}


	public function listaDropDownEspecialistasDisponivelMarcacao()
	{

		$codExameLista = $this->request->getPost('codExameLista');
		$result = $this->ExamesListaModel->listaDropDownEspecialistasDisponivelMarcacao($codExameLista);

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function listaDropDownConselhos()
	{

		$result = $this->ExamesListaModel->listaDropDownConselhos();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function edit()
	{

		$response = array();

		$fields['codExameLista'] = $this->request->getPost('codExameLista');
		$fields['descricaoExameLista'] = $this->request->getPost('descricaoExameLista');
		$fields['codTipo'] = $this->request->getPost('codTipo');
		$fields['codTipoAgenda'] = $this->request->getPost('codTipoAgenda');
		$fields['codConselho'] = $this->request->getPost('codConselho');
		$fields['numeroConselho'] = $this->request->getPost('numeroConselho');
		$fields['cadastroReserva'] = $this->request->getPost('cadastroReserva');


		$this->validation->setRules([
			'codExameLista' => ['label' => 'codExameLista', 'rules' => 'required|numeric|max_length[60]'],
			'descricaoExameLista' => ['label' => 'Descrição exameLista', 'rules' => 'required|max_length[60]'],
			'numeroConselho' => ['label' => 'Número Conselho', 'rules' => 'required|max_length[12]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->ExamesListaModel->update($fields['codExameLista'], $fields)) {

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


	public function removeMembro()
	{
		$response = array();

		$id = $this->request->getPost('codExameListaMembro');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->ExamesListaMembroModel->where('codExameListaMembro', $id)->delete()) {

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


	public function remove()
	{
		$response = array();

		$id = $this->request->getPost('codExameLista');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->ExamesListaModel->where('codExameLista', $id)->delete()) {

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
