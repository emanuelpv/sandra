<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrganizacoesModel;
use App\Models\PessoasModel;
use App\Models\LogsModel;
use App\Models\EspecialidadesMembroModel;

use App\Models\EspecialidadesModel;

class Especialidades extends BaseController
{

	protected $especialidadesModel;
	protected $EspecialidadesModel;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->EspecialidadesModel = new EspecialidadesModel();
		$this->EspecialidadesMembroModel = new EspecialidadesMembroModel();
		$this->PessoasModel = new PessoasModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
	}

	public function index()
	{

		$permissao = verificaPermissao('Especialidades', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo Especialidades', session()->codPessoa);
			exit();
		}

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());



		$data = [
			'controller'    	=> 'especialidades',
			'title'     		=> 'Especialidades'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('especialidades', $data);
	}



	public function mensagens()
	{


		$organizacao = $this->OrganizacoesModel->where('codOrganizacao', session()->codOrganizacao)->first();

		$data = [
			'controller'    	=> 'especialidades',
			'title'     		=> 'Especialidades',
			'mensagemPaciente' => $organizacao->mensagemPaciente,
		];

		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('especialidadesMensagens', $data);
	}



	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->EspecialidadesModel->select('codEspecialidade, descricaoEspecialidade,codTipo,codConselho,numeroConselho,cadastroReserva,exigirIndicacao')->findAll();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-xs btn-primary" onclick="edit(' . $value->codEspecialidade . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="remove(' . $value->codEspecialidade . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			if ($this->EspecialidadesModel->pegaConselho($value->codConselho) == NULL) {
				$codConselho = null;
			} else {
				$codConselho = $this->EspecialidadesModel->pegaConselho($value->codConselho)->nomeConselho;
			}


			if ($value->codTipo == 1) {
				$tipo = "Saúde";
			} else {
				$tipo = "Administrativo";
			}
			if ($value->cadastroReserva == 1) {
				$reserva = "Sim";
			} else {
				$reserva = "Não";
			}


			if ($value->exigirIndicacao == 1) {
				$encaminhamento = "Sim";
			} else {
				$encaminhamento = "Não";
			}

			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codEspecialidade,
				$value->descricaoEspecialidade,
				$tipo,
				$codConselho,
				$value->numeroConselho,
				$reserva,
				$encaminhamento,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}





	public function getAllMensagensConsultas()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->EspecialidadesModel->especialidadesDisponiveis();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-xs btn-primary" onclick="editarMensagem(' . $value->codEspecialidade . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '</div>';



			if (strlen($value->mensagemSucessoMarcacao) >= 300) {

				$mensagemSucessoMarcacao = mb_substr($value->mensagemSucessoMarcacao, 0, 90);
			} else {
				$mensagemSucessoMarcacao = $value->mensagemSucessoMarcacao;
			}


			if (strlen($value->mensagemFalhaMarcacao) >= 300) {

				$mensagemFalhaMarcacao = mb_substr($value->mensagemFalhaMarcacao, 0, 90);
			} else {
				$mensagemFalhaMarcacao = $value->mensagemFalhaMarcacao;
			}




			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codEspecialidade,
				$value->descricaoEspecialidade,
				$mensagemSucessoMarcacao,
				$mensagemFalhaMarcacao,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}


	public function getAllMensagensEncaminhamentos()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->EspecialidadesModel->especialidadesExigemEncaminhamento();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-xs btn-primary" onclick="editarMensagemEncaminhamento(' . $value->codEspecialidade . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '</div>';



			if (strlen($value->mensagemExigirIndicacao) >= 300) {

				$mensagemExigirIndicacao = mb_substr($value->mensagemExigirIndicacao, 0, 90);
			} else {
				$mensagemExigirIndicacao = $value->mensagemExigirIndicacao;
			}




			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codEspecialidade,
				$value->descricaoEspecialidade,
				$mensagemExigirIndicacao,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}





	public function pegaMembro()
	{
		$response = array();

		$id = $this->request->getPost('codEspecialidadeMembro');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->EspecialidadesMembroModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function pegaMembros($codEspecialidade = null)
	{
		$response = array();

		$data['data'] = array();

		if ($codEspecialidade == NULL) {
			$codEspecialidade = $this->request->getPost('codEspecialidade');
		}
		$result = $this->EspecialidadesModel->pegaMembros($codEspecialidade);

		$x = 0;
		foreach ($result as $key => $value) {

			$x++;

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-xs btn-primary" onclick="editespecialidadesMembro(' . $value->codEspecialidadeMembro . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="removeespecialidadesMembro(' . $value->codEspecialidadeMembro . ')"><i class="fa fa-trash"></i></button>';
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
				$tipo = "Saúde";
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

		$result = $this->EspecialidadesModel->listaDropDownEstadosFederacao();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function importarEspecialidades()
	{
		ini_set('max_execution_time', '0'); //300 seconds = 5 minutes
		set_time_limit(0);


		//IMPORTAR ESPECIALIDADES
		$especialidadesApolo = $this->EspecialidadesModel->especialidadesApolo();


		foreach ($especialidadesApolo as $especilidadeApolo) {
			$fields = array();


			$fields['codOrganizacao'] = session()->codOrganizacao;
			$fields['descricaoEspecialidade'] = $especilidadeApolo->nome_esp;
			$fields['codConselho'] = conselhoLookup($especilidadeApolo->tipo);
			$fields['numeroConselho'] = $especilidadeApolo->cod_cons;
			$fields['codTipo'] = 1;


			$especilidade = $this->EspecialidadesModel->pegaespecialidadePorNome($especilidadeApolo->nome_esp);

			if ($especilidade !== NULL) {
				$this->EspecialidadesModel->update($especilidade->codEspecialidade, $fields);
			} else {
				$this->EspecialidadesModel->insert($fields);
			}
		}




		//IMPORTAR MEMBROS
		$membrosEspecialidadesApolo = $this->EspecialidadesModel->membrosEspecialidadesApolo();


		foreach ($membrosEspecialidadesApolo as $membroEspecilidadeApolo) {

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

			$membros['codEspecialidade'] = lookupCodEspecialidade($membroEspecilidadeApolo->nome_esp);


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


			if ($membros['codPessoa'] !== NULL and $membros['codEspecialidade'] !== NULL) {

				$membrosEspecilidade = $this->EspecialidadesModel->pegaMembroPorEspecialidadeECodPessoa($membros['codEspecialidade'], $membros['codPessoa']);

				if ($membrosEspecilidade !== NULL) {
					@$this->EspecialidadesMembroModel->update($membrosEspecilidade->codEspecialidadeMembro, $membros);
				} else {

					@$this->EspecialidadesMembroModel->insert($membros);
				}
			}
		}




		$response['success'] = true;
		$response['csrf_hash'] = csrf_hash();
		$response['messages'] = 'Importação de especialidades e membros realizada com sucesso';
		return $this->response->setJSON($response);
	}


	public function salvarmensagemPaciente()
	{

		$response = array();
		$fields['codOrganizacao'] = session()->codOrganizacao;
		$fields['mensagemPaciente'] = $this->request->getPost('mensagem');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');

		$this->validation->setRules([

			'codOrganizacao' => ['label' => 'codOrganizacao', 'rules' => 'required'],
			'mensagemPaciente' => ['label' => 'Mensagem', 'rules' => 'permit_empty|max_length[300]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->OrganizacoesModel->update($fields['codOrganizacao'], $fields)) {

				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['messages'] = $fields['mensagemPaciente'];
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
			}
		}

		return $this->response->setJSON($response);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codEspecialidade');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->EspecialidadesModel->where('codEspecialidade', $id)->first();

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		if (session()->perfilAdmin !== 1) {

			$response['success'] = false;
			$response['csrf_hash'] = csrf_hash();
			$response['messages'] = 'Somente administradores podem adicionar novas especialidades. Procure o administrador do sistema!';

			return $this->response->setJSON($response);
		}



		$fields['codOrganizacao'] = session()->codOrganizacao;
		$fields['codEspecialidade'] = $this->request->getPost('codEspecialidade');
		$fields['descricaoEspecialidade'] = $this->request->getPost('descricaoEspecialidade');
		$fields['codTipo'] = $this->request->getPost('codTipo');
		$fields['codTipoAgenda'] = $this->request->getPost('codTipoAgenda');
		$fields['codConselho'] = $this->request->getPost('codConselho');
		$fields['numeroConselho'] = $this->request->getPost('numeroConselho');
		$fields['cadastroReserva'] = $this->request->getPost('cadastroReserva');
		$fields['exigirIndicacao'] = $this->request->getPost('exigirIndicacao');


		$this->validation->setRules([
			'descricaoEspecialidade' => ['label' => 'Descrição especialidade', 'rules' => 'required|max_length[60]'],
			'codTipoAgenda' => ['label' => 'codTipoAgenda', 'rules' => 'required|max_length[12]'],
			'cadastroReserva' => ['label' => 'cadastroReserva', 'rules' => 'required|max_length[12]'],
			'numeroConselho' => ['label' => 'Número Conselho', 'rules' => 'required|max_length[12]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->EspecialidadesModel->insert($fields)) {

				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['messages'] = 'Informação inserida com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = json_encode($fields);
			}
		}

		return $this->response->setJSON($response);
	}

	public function editMembro()
	{

		$response = array();

		$fields = array();


		if ($this->request->getPost('codFaixaEtaria') == NULL) {
			$codFaixaEtaria = 0;
		} else {
			$codFaixaEtaria = $this->request->getPost('codFaixaEtaria');
		}

		$fields['codEspecialidadeMembro'] = $this->request->getPost('codEspecialidadeMembro');
		$fields['codOrganizacao'] = session()->codOrganizacao;
		$fields['codEspecialidade'] = $this->request->getPost('codEspecialidade');
		$fields['codPessoa'] = $this->request->getPost('codPessoa');
		$fields['codEstadoFederacao'] = $this->request->getPost('codEstadoFederacao');
		$fields['numeroInscricao'] = $this->request->getPost('numeroInscricao');
		$fields['numeroSire'] = $this->request->getPost('numeroSire');
		$fields['observacoes'] = $this->request->getPost('observacoes');
		$fields['codFaixaEtaria'] = $codFaixaEtaria;
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['autor'] = session()->codPessoa;

		if ($this->request->getPost('atende') == 'on') {
			$fields['atende'] = '1';
		} else {
			$fields['atende'] = '0';
		}

		$this->validation->setRules([
			'codEspecialidadeMembro' => ['label' => 'codEspecialidadeMembro', 'rules' => 'required|numeric'],
			'atende' => ['label' => 'Atende', 'rules' => 'permit_empty'],
			'codEspecialidade' => ['label' => 'Código Especialidade', 'rules' => 'required'],
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

			if ($this->EspecialidadesMembroModel->update($fields['codEspecialidadeMembro'], $fields)) {

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

		if ($this->request->getPost('codFaixaEtaria') == NULL) {
			$codFaixaEtaria = 0;
		} else {
			$codFaixaEtaria = $this->request->getPost('codFaixaEtaria');
		}


		$fields['codOrganizacao'] = session()->codOrganizacao;
		$fields['codEspecialidade'] = $this->request->getPost('codEspecialidade');
		$fields['codPessoa'] = $this->request->getPost('codPessoa');
		$fields['codEstadoFederacao'] = $this->request->getPost('codEstadoFederacao');
		$fields['numeroInscricao'] = $this->request->getPost('numeroInscricao');
		$fields['numeroSire'] = $this->request->getPost('numeroSire');
		$fields['observacoes'] = $this->request->getPost('observacoes');
		$fields['codFaixaEtaria'] = $codFaixaEtaria;
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['autor'] = session()->codPessoa;

		if ($this->request->getPost('atende') == 'on') {
			$fields['atende'] = '1';
		} else {
			$fields['atende'] = '0';
		}


		$existe = $this->EspecialidadesMembroModel->pegaEspecialidadePorMembro($fields['codEspecialidade'], $fields['codPessoa']);

		if ($existe !== NULL) {
			$response['success'] = false;
			$response['messages'] = 'Especialista já é membro';

			return $this->response->setJSON($response);
		}

		$this->validation->setRules([
			'atende' => ['label' => 'Atende', 'rules' => 'permit_empty'],
			'codEspecialidade' => ['label' => 'Código Especialidade', 'rules' => 'required'],
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

			if ($this->EspecialidadesMembroModel->insert($fields)) {

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

	public function listaDropDownEspecialidades()
	{

		$result = $this->EspecialidadesModel->listaDropDownEspecialidades();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}
	public function listaDropDownEspecialidadesDisponivelMarcacao()
	{

		$result = $this->EspecialidadesModel->listaDropDownEspecialidadesDisponivelMarcacao();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}
	public function listaDropDownEspecialidadesDisponivelIndicacao()
	{

		$result = $this->EspecialidadesModel->listaDropDownEspecialidadesDisponivelIndicacao();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function statusMinhasEspecialidades()
	{
		$response = array();

		if (empty(session()->minhasEspecialidades)) {
			$statusMinhasEspecialidades = 0;
		} else {

			$statusMinhasEspecialidades = 1;

			if (count(session()->minhasEspecialidades) > 1) {
				$selectEspecialidadeAtendimento = '<div>Você atende por mais de uma especialidade. Antes de iniciar o atendimento defina por qual você irá atender.</div><select required style="width:300px" id="codEspecialidadeAtendimento" name="codEspecialidadeAtendimento" class="custom-select"><option value=""></option>';

				foreach (session()->minhasEspecialidades as $minhaEspecialidade) {
					$selectEspecialidadeAtendimento .= '<option value="' . $minhaEspecialidade->codEspecialidade . '">' . $minhaEspecialidade->descricaoEspecialidade . '</option>';
				}
				$selectEspecialidadeAtendimento .= '</select>';
			} else {
				$selectEspecialidadeAtendimento = '<div>Especialidade:</div>
				<select required style="width:300px;margin-bottom:5px;margin-top:5px" id="codEspecialidadeAtendimento" name="codEspecialidadeAtendimento" class="custom-select">
				<option value="' . session()->minhasEspecialidades[0]->codEspecialidade . '">' . session()->minhasEspecialidades[0]->descricaoEspecialidade . '</option>';

				$selectEspecialidadeAtendimento .= '</select>';
			}
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

		$response['codEspecialidadeAtendimento'] = session()->codEspecialidadeAtendimento;

		$response['success'] = true;
		$response['csrf_hash'] = csrf_hash();
		$response['statusMinhasEspecialidades'] = $statusMinhasEspecialidades;
		$response['selectEspecialidadeAtendimento'] = $selectEspecialidadeAtendimento;
		$response['codLocalAtendimento'] = $codLocalAtendimento;
		$response['nomeLocalAtendimento'] = $nomeLocalAtendimento;
		$response['nomeEspecialidadeAtendimento'] = lookupNomeEspecialidade(session()->codEspecialidadeAtendimento);
		return $this->response->setJSON($response);
	}


	public function minhasEspecialidadesnoParecer()
	{
		$response = array();

		if (empty(session()->minhasEspecialidades)) {
			$statusMinhasEspecialidades = 0;
		} else {

			$statusMinhasEspecialidades = 1;

			if (count(session()->minhasEspecialidades) > 1) {
				$selectEspecialidadeAtendimento = '<div>Você atende por mais de uma especialidade. Antes de iniciar o atendimento defina por qual você irá atender.</div><select required style="width:300px" 
				id="codEspecialidadeAtendimentoParecerAdd" name="codEspecialidade" class="custom-select"><option value=""></option>';

				foreach (session()->minhasEspecialidades as $minhaEspecialidade) {
					$selectEspecialidadeAtendimento .= '<option value="' . $minhaEspecialidade->codEspecialidade . '">' . $minhaEspecialidade->descricaoEspecialidade . '</option>';
				}
				$selectEspecialidadeAtendimento .= '</select>';
			} else {
				$selectEspecialidadeAtendimento = '<div>Especialidade:</div>
				<select required style="width:300px;margin-bottom:5px;margin-top:5px" id="codEspecialidadeAtendimentoParecerAdd" name="codEspecialidade" class="custom-select">
				<option value="' . session()->minhasEspecialidades[0]->codEspecialidade . '">' . session()->minhasEspecialidades[0]->descricaoEspecialidade . '</option>';

				$selectEspecialidadeAtendimento .= '</select>';
			}
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

		$response['codEspecialidadeAtendimento'] = session()->codEspecialidadeAtendimento;

		$response['success'] = true;
		$response['csrf_hash'] = csrf_hash();
		$response['statusMinhasEspecialidades'] = $statusMinhasEspecialidades;
		$response['selectEspecialidadeAtendimento'] = $selectEspecialidadeAtendimento;
		$response['codLocalAtendimento'] = $codLocalAtendimento;
		$response['nomeLocalAtendimento'] = $nomeLocalAtendimento;
		$response['nomeEspecialidadeAtendimento'] = lookupNomeEspecialidade(session()->codEspecialidadeAtendimento);
		return $this->response->setJSON($response);
	}



	public function listaDropDownEspecialistasDisponivelMarcacaoManual()
	{

		$response = array();
		$codEspecialidade = $this->request->getPost('codEspecialidade');
		$especialistas = $this->EspecialidadesModel->listaDropDownEspecialistasDisponivelMarcacao($codEspecialidade);

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

		$codEspecialidade = $this->request->getPost('codEspecialidade');
		$result = $this->EspecialidadesModel->listaDropDownEspecialistasDisponivelMarcacao($codEspecialidade);

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function listaDropDownConselhos()
	{

		$result = $this->EspecialidadesModel->listaDropDownConselhos();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function listaDropDownFaixasEtarias()
	{

		$result = $this->EspecialidadesModel->listaDropDownFaixasEtarias();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function edit()
	{

		$response = array();

		$fields['codEspecialidade'] = $this->request->getPost('codEspecialidade');
		$fields['descricaoEspecialidade'] = $this->request->getPost('descricaoEspecialidade');
		$fields['codTipo'] = $this->request->getPost('codTipo');
		$fields['codTipoAgenda'] = $this->request->getPost('codTipoAgenda');
		$fields['codConselho'] = $this->request->getPost('codConselho');
		$fields['numeroConselho'] = $this->request->getPost('numeroConselho');
		$fields['cadastroReserva'] = $this->request->getPost('cadastroReserva');
		$fields['exigirIndicacao'] = $this->request->getPost('exigirIndicacao');


		$this->validation->setRules([
			'codEspecialidade' => ['label' => 'codEspecialidade', 'rules' => 'required|numeric|max_length[11]'],
			'descricaoEspecialidade' => ['label' => 'Descrição especialidade', 'rules' => 'required|max_length[60]'],
			'codTipoAgenda' => ['label' => 'codTipoAgenda', 'rules' => 'required|max_length[12]'],
			'cadastroReserva' => ['label' => 'cadastroReserva', 'rules' => 'required|max_length[12]'],
			'numeroConselho' => ['label' => 'Número Conselho', 'rules' => 'required|max_length[12]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->EspecialidadesModel->update($fields['codEspecialidade'], $fields)) {

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



	public function editMensagens()
	{

		$response = array();

		$fields['codEspecialidade'] = $this->request->getPost('codEspecialidade');
		$fields['mensagemFalhaMarcacao'] = $this->request->getPost('mensagemFalhaMarcacao');
		$fields['mensagemSucessoMarcacao'] = $this->request->getPost('mensagemSucessoMarcacao');


		$this->validation->setRules([
			'codEspecialidade' => ['label' => 'codEspecialidade', 'rules' => 'required|numeric'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->EspecialidadesModel->update($fields['codEspecialidade'], $fields)) {

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


	public function editMensagensEncaminhamento()
	{

		$response = array();

		$fields['codEspecialidade'] = $this->request->getPost('codEspecialidade');
		$fields['mensagemExigirIndicacao'] = $this->request->getPost('mensagemExigirIndicacao');


		$this->validation->setRules([
			'codEspecialidade' => ['label' => 'codEspecialidade', 'rules' => 'required|numeric'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->EspecialidadesModel->update($fields['codEspecialidade'], $fields)) {

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

		$id = $this->request->getPost('codEspecialidadeMembro');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->EspecialidadesMembroModel->where('codEspecialidadeMembro', $id)->delete()) {

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

		$id = $this->request->getPost('codEspecialidade');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->EspecialidadesModel->where('codEspecialidade', $id)->delete()) {

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
