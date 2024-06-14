<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\EscalasModel;

class Escalas extends BaseController
{

	protected $EscalasModel;
	protected $PessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->EscalasModel = new EscalasModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->PessoasModel = new PessoasModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('Escalas', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "Escalas"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'escalas',
			'title'     		=> 'Escalas de Serviço'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('escalas', $data);
	}



	public function membrosEscala()
	{
		$response = array();

		$data['data'] = array();


		$codCargo = $this->request->getPost('codCargo');

		$codEscala = $this->request->getPost('codEscala');

		$concorrentes = $this->EscalasModel->concorrrentesEscala($codCargo);

		$html = "";
		$html .= '<div class="row">';
		foreach ($concorrentes as $concorrente) {
			$html .= '<div class="col-md-3">';


			if ($concorrente->codEscala !== NULL and $concorrente->codEscala == $codEscala) {
				$html .= '<button id="membro' . $concorrente->codPessoa . '" onclick="selecionarMembro(' . $concorrente->codPessoa . ',' . $codEscala . ')" style="width:100%;margin-top:10px" class="btn btn-success">' . $concorrente->nomeExibicao . '</button>';
			} else {
				$html .= '<button id="membro' . $concorrente->codPessoa . '" onclick="selecionarMembro(' . $concorrente->codPessoa . ',' . $codEscala . ')" style="width:100%;margin-top:10px" class="btn btn-primary">' . $concorrente->nomeExibicao . '</button>';
			}

			$html .= '</div>';
		}

		$html .= '</div>';

		$response['html'] = $html;
		return $this->response->setJSON($response);
	}
	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->EscalasModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editescalas(' . $value->codEscala . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeescalas(' . $value->codEscala . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$value->codEscala,
				$value->descricao,
				$value->abreviacaoDepartamento,
				$value->criadoPor,
				date('d/m/Y', strtotime($value->dataCriacao)),
				$value->modificadoPor,
				date('d/m/Y H:i', strtotime($value->dataAtualizacao)),

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}


	public function membrosAtivos()
	{
		$response = array();

		$data['data'] = array();

		$codEscala = $this->request->getPost('codEscala');
		$result = $this->EscalasModel->membrosAtivos($codEscala);
		$x = 0;
		foreach ($result as $key => $value) {

			$folgaPreta = 0;
			if ($value->dataUltimoEscalacaoPreta == NULL) {
				$dataUltimoEscalacaoPreta = '';
			} else {

				$dataUltimoEscalacaoPreta = date('Y-m-d', strtotime($value->dataUltimoEscalacaoPreta));

				$startTimeStamp = strtotime($dataUltimoEscalacaoPreta);
				$endTimeStamp = strtotime(date('Y-m-d'));

				if ($endTimeStamp >= $startTimeStamp) {

					$timeDiff = abs($endTimeStamp - $startTimeStamp);

					$folgaPreta = $timeDiff / 86400;  // 86400 seconds in one day

					// and you might want to convert to integer
					$folgaPreta = intval($folgaPreta);
				} else {
					$folgaPreta = 0;
				}
			}


			$folgaVermelha = 0;
			if ($value->dataUltimoEscalacaoVermelha == NULL) {
				$dataUltimoEscalacaoVermelha = '';
			} else {

				$dataUltimoEscalacaoVermelha = date('Y-m-d', strtotime($value->dataUltimoEscalacaoVermelha));

				$startTimeStamp = strtotime($dataUltimoEscalacaoVermelha);
				$endTimeStamp = strtotime(date('Y-m-d'));

				if ($endTimeStamp >= $startTimeStamp) {

					$timeDiff = abs($endTimeStamp - $startTimeStamp);

					$folgaVermelha = $timeDiff / 86400;  // 86400 seconds in one day

					// and you might want to convert to integer
					$folgaVermelha = intval($folgaVermelha);
				} else {
					$folgaVermelha = 0;
				}
			}



			//AFASTAMENTOS 


			$afastamentos = $this->EscalasModel->afastamentosMembro($value->codMembroEscala);


			$listaAfastamentos = '';

			if (!empty($afastamentos)) {

				foreach ($afastamentos as $afastamento) {

					if ($afastamento->afastamentoIndeterminado == 1) {

						$listaAfastamentos .= '<div> ' . $afastamento->statusServico . ' por tempo Indeterminado  ' . '</div>';

						if ($afastamento->observacoes !== NULL) {
							$listaAfastamentos .= '<div style="color:red"> ' .  $afastamento->observacoes . '</div>';
						}
					} else {

						if ($afastamento->dataInicioAfastamento == $afastamento->dataEncerramentoAfastamento) {
							$listaAfastamentos .= '<div>' . $afastamento->statusServico . ' em ' . date('d/m', strtotime($afastamento->dataInicioAfastamento)) . '</div>';
						} else {
							$listaAfastamentos .= '<div>' . $afastamento->statusServico . ' de ' . date('d/m', strtotime($afastamento->dataInicioAfastamento)) . ' a ' . date('d/m', strtotime($afastamento->dataEncerramentoAfastamento)) . '</div>';
						}
						if ($afastamento->observacoes !== NULL) {
							$listaAfastamentos .= '<div style="color:red"> ' .  $afastamento->observacoes . '</div>';
						}
					}
				}
			} else {
				$listaAfastamentos = '';
			}

			$ops = '<div class="btn-group">
						<button type="button" class="btn btn-info btn-sm">Ação</button>
						<button type="button" class="btn btn-info  btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
						<span class="sr-only">Toggle Dropdown</span>
						</button>
						<div class="dropdown-menu" role="menu">
							<a href="#" class="dropdown-item" onclick="definirUltimoServicoPreta(' . $value->codMembroEscala . ')">Última Preta</a>
							<a href="#" class="dropdown-item" onclick="definirUltimoServicoVermelha(' . $value->codMembroEscala . ')">Última Vermelha</a>
							<a href="#" class="dropdown-item" onclick="afastarmembro(' . $value->codMembroEscala . ')">Afastar Membro</a>
							<a href="#" class="dropdown-item" onclick="removerDefinitivo(' . $value->codMembroEscala . ')">Remover definitivo</a>
						</div>
					</div>';


			$x++;
			$data['data'][$key] = array(


				$value->membro . '<div>Última Preta: ' .
					date('d/m', strtotime($dataUltimoEscalacaoPreta)) . '</div>'
					. '<div>Última Vermelha: ' .
					date('d/m', strtotime($dataUltimoEscalacaoVermelha)) . '</div>',
				$listaAfastamentos,
				'<center>' . $folgaPreta . '</center>',
				'<center>' . $folgaVermelha . '</center>',
				$ops
			);
		}

		return $this->response->setJSON($data);
	}


	public function membrosAfastados()
	{
		$response = array();

		$data['data'] = array();

		$codEscala = $this->request->getPost('codEscala');
		$result = $this->EscalasModel->membrosAfastados($codEscala);
		$x = 0;
		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">
						<button type="button" class="btn btn-info btn-sm">Ação</button>
						<button type="button" class="btn btn-info btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
						<span class="sr-only">Toggle Dropdown</span>
						</button>
						<div class="dropdown-menu" role="menu">
							<a href="#" class="dropdown-item" onclick="editarAfastamento(' . $value->codAfastamento . ')">Editar Afastamento</a>
							<a href="#" class="dropdown-item" onclick="removerAfastamento(' . $value->codAfastamento . ')">Remover Afastamento</a>
						</div>
					</div>';




			if ($value->afastamentoIndeterminado == 1) {
				$inicio = date('d/m/Y', strtotime($value->dataInicioAfastamento));
				$termino = 'Indeterminado';
			} else {

				$inicio = date('d/m/Y', strtotime($value->dataInicioAfastamento));
				$termino = date('d/m/Y', strtotime($value->dataEncerramentoAfastamento));
			}

			$x++;
			$data['data'][$key] = array(
				$value->membro . '<div style="font-size:12px;color:red">' . $value->observacoes . '</div>',
				$inicio,
				$termino,
				$value->statusServico,
				$ops
			);
		}

		return $this->response->setJSON($data);
	}

	public function dadosTroca()
	{
		$response = array();

		$codTrocaEscala = $this->request->getPost('codTrocaEscala');

		if ($this->validation->check($codTrocaEscala, 'required|numeric')) {

			$data = $this->EscalasModel->dadosTroca($codTrocaEscala);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function listaTrocas()
	{
		$response = array();

		$data['data'] = array();

		$codEscala = $this->request->getPost('codEscala');
		$result = $this->EscalasModel->listaTrocas($codEscala);
		$x = 0;
		foreach ($result as $key => $value) {


			$ops = '<div class="btn-group">
						<button type="button" class="btn btn-info btn-sm">Ação</button>
						<button type="button" class="btn btn-info btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
						<span class="sr-only">Toggle Dropdown</span>
						</button>
						<div class="dropdown-menu" role="menu">
						<a href="#" class="dropdown-item" onclick="editarTroca(' . $value->codTrocaEscala . ')">Editar Troca</a>
						<a href="#" class="dropdown-item" onclick="removerTroca(' . $value->codTrocaEscala . ')">Remover Troca</a>
						</div>
					</div>';



			if ($value->codTipoEscala == 1) {
				$tipoEscala = 'Preta';
			} else {
				$tipoEscala = 'Vermelha';
			}

			$x++;
			$data['data'][$key] = array(
				$x,
				date('d/m/Y', strtotime($value->dataPrevisao)),
				$value->nomeExibicaoSai,
				$value->nomeExibicaoEntra,
				$tipoEscala,
				$value->observacoes,
				$ops
			);
		}

		return $this->response->setJSON($data);
	}


	public function datasVermelhas()
	{
		$response = array();

		$data['data'] = array();

		$codEscala = $this->request->getPost('codEscala');
		$result = $this->EscalasModel->listaDatasVermelhas($codEscala);
		$x = 0;
		foreach ($result as $key => $value) {


			$ops = '<div class="btn-group">
						<button type="button" class="btn btn-info btn-sm">Ação</button>
						<button type="button" class="btn btn-info btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
						<span class="sr-only">Toggle Dropdown</span>
						</button>
						<div class="dropdown-menu" role="menu">';




			if ($value->ativo == 1) {
				$ops .= '<a href="#" class="dropdown-item" onclick="desativarDataVermelha(' . $value->codDataVermelha  . ')">Desativar</a>';
			} else {
				$ops .= '<a href="#" class="dropdown-item" onclick="ativarDataVermelha(' . $value->codDataVermelha  . ')">Ativar</a>';
			}

			$ops .= '<a href="#" class="dropdown-item" onclick="removerDataVermelha(' . $value->codDataVermelha  . ')">Remover Definitivo</a>';


			$ops .= '
						</div>
					</div>';


			if ($value->recorrente == 1) {
				$recorrente = 'Sim';
			} else {
				$recorrente = 'Não';
			}
			if ($value->ativo == 1) {
				$ativo = 'Sim';
			} else {
				$ativo = 'Não';
			}

			$x++;
			$data['data'][$key] = array(
				date('d/m', strtotime($value->dataVermelha)),
				$value->descricao,
				$recorrente,
				$ativo,
				$ops
			);
		}

		return $this->response->setJSON($data);
	}


	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codEscala');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->EscalasModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function getAfastamento()
	{
		$response = array();

		$codAfastamento = $this->request->getPost('codAfastamento');

		if ($this->validation->check($codAfastamento, 'required|numeric')) {

			$data = $this->EscalasModel->getAfastamento($codAfastamento);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function getOneMembroEscala()
	{
		$response = array();

		$codMembroEscala = $this->request->getPost('codMembroEscala');

		if ($this->validation->check($codMembroEscala, 'required|numeric')) {

			$data = $this->EscalasModel->pegaMembroEscala($codMembroEscala);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}
	public function dadosPrevisaoEscala()
	{
		$response = array();

		$codPrevisaoEscala = $this->request->getPost('codPrevisaoEscala');

		if ($this->validation->check($codPrevisaoEscala, 'required|numeric')) {

			$data = $this->EscalasModel->dadosPrevisaoEscala($codPrevisaoEscala);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codEscala'] = $this->request->getPost('codEscala');
		$fields['descricao'] = $this->request->getPost('descricao');
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['dataLimiteLiberacao'] = date('Y-m-d');
		$fields['codAutor'] = session()->codPessoa;
		$fields['setorGestor'] = $this->request->getPost('setorGestor');
		$fields['modificadoPor'] = session()->codPessoa;


		$this->validation->setRules([
			'descricao' => ['label' => 'Descrição', 'rules' => 'required|max_length[200]'],
			'dataCriacao' => ['label' => 'Data Criação', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'Data Atualização', 'rules' => 'required'],
			'codAutor' => ['label' => 'Criado Por', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'setorGestor' => ['label' => 'Setor Gestor', 'rules' => 'required|numeric|max_length[11]'],
			'modificadoPor' => ['label' => 'Modificado Por', 'rules' => 'permit_empty|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->EscalasModel->insert($fields)) {

				$response['success'] = true;
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

		$fields['codEscala'] = $this->request->getPost('codEscala');
		$fields['descricao'] = $this->request->getPost('descricao');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['codAutor'] = session()->codPessoa;
		$fields['setorGestor'] = $this->request->getPost('setorGestor');
		$fields['modificadoPor'] = session()->codPessoa;



		$this->validation->setRules([
			'codEscala' => ['label' => 'Código', 'rules' => 'required|numeric|max_length[11]'],
			'descricao' => ['label' => 'Descrição', 'rules' => 'required|max_length[200]'],
			'dataAtualizacao' => ['label' => 'Data Atualização', 'rules' => 'required'],
			'codAutor' => ['label' => 'Criado Por', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'setorGestor' => ['label' => 'Setor Gestor', 'rules' => 'required|numeric|max_length[11]'],
			'modificadoPor' => ['label' => 'Modificado Por', 'rules' => 'permit_empty|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->EscalasModel->update($fields['codEscala'], $fields)) {

				$response['success'] = true;
				$response['messages'] = 'Atualizado com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
			}
		}

		return $this->response->setJSON($response);
	}

	public function selecionarMembro()
	{

		$response = array();

		$fields['codEscala'] = $this->request->getPost('codEscala');
		$fields['codPessoa'] = $this->request->getPost('codPessoa');




		$this->validation->setRules([
			'codEscala' => ['label' => 'Código', 'rules' => 'required|numeric|max_length[11]'],
			'codPessoa' => ['label' => 'codPessoa', 'rules' => 'required|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {



			$verificaMembroCadastrado = $this->EscalasModel->verificaMembroCadastrado($fields['codEscala'], $fields['codPessoa']);

			if (!empty($verificaMembroCadastrado)) {

				$response['success'] = false;
				$response['messages'] = 'Pessoa Já cadatrada!';
				return $this->response->setJSON($response);
			}

			if ($this->EscalasModel->addmembro($fields['codEscala'], $fields['codPessoa'])) {

				$response['success'] = true;
				$response['messages'] = 'Atualizado com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
			}
		}

		return $this->response->setJSON($response);
	}

	public function editarTrocaAgora()
	{

		$response = array();

		$fields['codTrocaEscala'] = $this->request->getPost('codTrocaEscala');
		$fields['tipoTroca'] = $this->request->getPost('tipoTroca');
		$fields['trocadoPor'] = $this->request->getPost('codPessoaTroca');
		$fields['observacoes'] = $this->request->getPost('observacoes');



		$this->validation->setRules([
			'codTrocaEscala' => ['label' => 'codTrocaEscala', 'rules' => 'required|numeric|max_length[11]'],
			'tipoTroca' => ['label' => 'tipoTroca', 'rules' => 'required|numeric|max_length[11]'],
			'trocadoPor' => ['label' => 'trocadoPor', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->EscalasModel->updateTrocaEscala($fields['codTrocaEscala'], $fields['tipoTroca'], $fields['trocadoPor'], $fields['observacoes'])) {

				$response['success'] = true;
				$response['messages'] = 'Atualizado com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
			}
		}

		return $this->response->setJSON($response);
	}

	public function afastarMembroEdit()
	{

		$response = array();

		$fields['codAfastamento'] = $this->request->getPost('codAfastamento');
		$fields['codStatus'] = $this->request->getPost('codStatusAfastamento');

		if ($this->request->getPost('dataInicioAfastamento') !== NULL and $this->request->getPost('dataInicioAfastamento') !== "") {
			$fields['dataInicioAfastamento'] = $this->request->getPost('dataInicioAfastamento');
		} else {
			$fields['dataInicioAfastamento'] = date('Y-m-d');
		}

		if ($this->request->getPost('dataEncerramentoAfastamento') !== NULL and $this->request->getPost('dataEncerramentoAfastamento') !== "") {
			$fields['dataEncerramentoAfastamento'] = $this->request->getPost('dataEncerramentoAfastamento');
		} else {
			$fields['dataEncerramentoAfastamento'] = NULL;
		}


		if ($this->request->getPost('afastamentoIndeterminado') == 'true') {
			$fields['afastamentoIndeterminado'] = 1;
			$fields['dataInicioAfastamento'] = date('Y-m-d');
			$fields['dataEncerramentoAfastamento'] = NULL;
		} else {
			$fields['afastamentoIndeterminado'] = 0;
		}



		$fields['observacoes'] = $this->request->getPost('observacoes');

		if ($fields['afastamentoIndeterminado']) {
		}


		if ($fields['afastamentoIndeterminado'] == 0 and ($fields['dataInicioAfastamento'] == NULL or  $fields['dataEncerramentoAfastamento'] == NULL)) {

			$response['success'] = false;
			$response['messages'] = 'Voce deve definir o tempo de afastamento';
			return $this->response->setJSON($response);
		}

		$this->validation->setRules([
			'codAfastamento' => ['label' => 'codAfastamento', 'rules' => 'required|numeric|max_length[11]'],
			'codStatus' => ['label' => 'Motivo', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->EscalasModel->afastarMembroEdit(
				$fields['codAfastamento'],
				$fields['codStatus'],
				$fields['afastamentoIndeterminado'],
				$fields['dataInicioAfastamento'],
				$fields['dataEncerramentoAfastamento'],
				$fields['observacoes']
			)) {

				$response['success'] = true;
				$response['messages'] = 'Atualizado com sucesso';
				return $this->response->setJSON($response);
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
				return $this->response->setJSON($response);
			}
		}

		return $this->response->setJSON($response);
	}

	public function afastarMembroAdd()
	{

		$response = array();

		$fields['codMembroEscala'] = $this->request->getPost('codMembroEscala');
		$fields['codStatus'] = $this->request->getPost('codStatusAfastamento');

		if ($this->request->getPost('dataInicioAfastamento') !== NULL and $this->request->getPost('dataInicioAfastamento') !== "") {
			$fields['dataInicioAfastamento'] = $this->request->getPost('dataInicioAfastamento');
		} else {
			$fields['dataInicioAfastamento'] = date('Y-m-d');;
		}

		if ($this->request->getPost('dataEncerramentoAfastamento') !== NULL and $this->request->getPost('dataEncerramentoAfastamento') !== "") {
			$fields['dataEncerramentoAfastamento'] = $this->request->getPost('dataEncerramentoAfastamento');
		} else {
			$fields['dataEncerramentoAfastamento'] = NULL;
		}


		if ($this->request->getPost('afastamentoIndeterminado') == 'true') {
			$fields['afastamentoIndeterminado'] = 1;
			$fields['dataInicioAfastamento'] = date('Y-m-d');
			$fields['dataEncerramentoAfastamento'] = NULL;
		} else {
			$fields['afastamentoIndeterminado'] = 0;
		}



		$fields['observacoes'] = $this->request->getPost('observacoes');


		if ($fields['afastamentoIndeterminado'] == 0 and ($fields['dataInicioAfastamento'] == NULL or  $fields['dataEncerramentoAfastamento'] == NULL)) {

			$response['success'] = false;
			$response['messages'] = 'Voce deve definir o tempo de afastamento';
			return $this->response->setJSON($response);
		}

		$this->validation->setRules([
			'codMembroEscala' => ['label' => 'codMembroEscala', 'rules' => 'required|numeric|max_length[11]'],
			'codStatus' => ['label' => 'Motivo', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->EscalasModel->afastarMembroAdd(
				$fields['codMembroEscala'],
				$fields['codStatus'],
				$fields['afastamentoIndeterminado'],
				$fields['dataInicioAfastamento'],
				$fields['dataEncerramentoAfastamento'],
				$fields['observacoes']
			)) {

				$response['success'] = true;
				$response['messages'] = 'Atualizado com sucesso';
				return $this->response->setJSON($response);
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
				return $this->response->setJSON($response);
			}
		}

		return $this->response->setJSON($response);
	}


	public function afastarMembroAgora()
	{

		$response = array();

		$fields['codMembroEscala'] = $this->request->getPost('codMembroEscala');
		$fields['codStatus'] = $this->request->getPost('codStatusAfastamento');

		if ($this->request->getPost('dataInicioAfastamento') !== NULL and $this->request->getPost('dataInicioAfastamento') !== "") {
			$fields['dataInicioAfastamento'] = $this->request->getPost('dataInicioAfastamento');
		} else {
			$fields['dataInicioAfastamento'] = date('Y-m-d');
		}

		if ($this->request->getPost('dataEncerramentoAfastamento') !== NULL and $this->request->getPost('dataEncerramentoAfastamento') !== "") {
			$fields['dataEncerramentoAfastamento'] = $this->request->getPost('dataEncerramentoAfastamento');
		} else {
			$fields['dataEncerramentoAfastamento'] = NULL;
		}


		if ($this->request->getPost('afastamentoIndeterminado') == 'true') {
			$fields['afastamentoIndeterminado'] = 1;
			$fields['dataEncerramentoAfastamento'] = NULL;
			$fields['dataInicioAfastamento'] = date('Y-m-d');
		} else {
			$fields['afastamentoIndeterminado'] = 0;
		}



		$fields['observacoes'] = $this->request->getPost('observacoes');

		if ($fields['afastamentoIndeterminado']) {
		}


		if ($fields['afastamentoIndeterminado'] == 0 and ($fields['dataInicioAfastamento'] == NULL or  $fields['dataEncerramentoAfastamento'] == NULL)) {

			$response['success'] = false;
			$response['messages'] = 'Voce deve definir o tempo de afastamento';
			return $this->response->setJSON($response);
		}

		$this->validation->setRules([
			'codMembroEscala' => ['label' => 'codMembroEscala', 'rules' => 'required|numeric|max_length[11]'],
			'codStatus' => ['label' => 'Motivo', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->EscalasModel->afastarMembroAgora(
				$fields['codMembroEscala'],
				$fields['codStatus'],
				$fields['afastamentoIndeterminado'],
				$fields['dataInicioAfastamento'],
				$fields['dataEncerramentoAfastamento'],
				$fields['observacoes']
			)) {

				$response['success'] = true;
				$response['messages'] = 'Atualizado com sucesso';
				return $this->response->setJSON($response);
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
				return $this->response->setJSON($response);
			}
		}

		return $this->response->setJSON($response);
	}

	public function afastarMembroEditAgora()
	{

		$response = array();

		$fields['codMembroEscala'] = $this->request->getPost('codMembroEscala');
		$fields['codStatus'] = $this->request->getPost('codStatusAfastamento');

		if ($this->request->getPost('dataInicioAfastamento') !== NULL and $this->request->getPost('dataInicioAfastamento') !== "") {
			$fields['dataInicioAfastamento'] = $this->request->getPost('dataInicioAfastamento');
		} else {
			$fields['dataInicioAfastamento'] = date('Y-m-d');
		}

		if ($this->request->getPost('dataEncerramentoAfastamento') !== NULL and $this->request->getPost('dataEncerramentoAfastamento') !== "") {
			$fields['dataEncerramentoAfastamento'] = $this->request->getPost('dataEncerramentoAfastamento');
		} else {
			$fields['dataEncerramentoAfastamento'] = NULL;
		}


		if ($this->request->getPost('afastamentoIndeterminado') == 'true') {
			$fields['afastamentoIndeterminado'] = 1;
			$fields['dataEncerramentoAfastamento'] = NULL;
			$fields['dataInicioAfastamento'] = date('Y-m-d');
		} else {
			$fields['afastamentoIndeterminado'] = 0;
		}



		$fields['observacoes'] = $this->request->getPost('observacoes');

		if ($fields['afastamentoIndeterminado']) {
		}


		if ($fields['afastamentoIndeterminado'] == 0 and ($fields['dataInicioAfastamento'] == NULL or  $fields['dataEncerramentoAfastamento'] == NULL)) {

			$response['success'] = false;
			$response['messages'] = 'Voce deve definir o tempo de afastamento';
			return $this->response->setJSON($response);
		}

		$this->validation->setRules([
			'codMembroEscala' => ['label' => 'codMembroEscala', 'rules' => 'required|numeric|max_length[11]'],
			'codStatus' => ['label' => 'Motivo', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->EscalasModel->afastarMembroAgora(
				$fields['codMembroEscala'],
				$fields['codStatus'],
				$fields['afastamentoIndeterminado'],
				$fields['dataInicioAfastamento'],
				$fields['dataEncerramentoAfastamento'],
				$fields['observacoes']
			)) {

				$response['success'] = true;
				$response['messages'] = 'Atualizado com sucesso';
				return $this->response->setJSON($response);
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
				return $this->response->setJSON($response);
			}
		}

		return $this->response->setJSON($response);
	}

	public function dataUltimoEscalacaoPreta()
	{

		$fields['codMembroEscala'] = $this->request->getPost('codMembroEscala');
		$fields['dataUltimoEscalacaoPreta'] = $this->request->getPost('dataUltimoEscalacaoPreta');

		$this->validation->setRules([
			'codMembroEscala' => ['label' => 'codMembroEscala', 'rules' => 'required|numeric|max_length[11]'],
			'dataUltimoEscalacaoPreta' => ['label' => 'Data última escalação', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->EscalasModel->atualizadataUltimoEscalacaoPreta(
				$fields['codMembroEscala'],
				$fields['dataUltimoEscalacaoPreta']
			)) {

				$response['success'] = true;
				$response['messages'] = 'Atualizado com sucesso';
				return $this->response->setJSON($response);
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
				return $this->response->setJSON($response);
			}
		}

		return $this->response->setJSON($response);
	}


	public function dataUltimoEscalacaoVermelha()
	{

		$fields['codMembroEscala'] = $this->request->getPost('codMembroEscala');
		$fields['dataUltimoEscalacaoVermelha'] = $this->request->getPost('dataUltimoEscalacaoVermelha');

		$this->validation->setRules([
			'codMembroEscala' => ['label' => 'codMembroEscala', 'rules' => 'required|numeric|max_length[11]'],
			'dataUltimoEscalacaoVermelha' => ['label' => 'Data última escalação', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->EscalasModel->atualizadataUltimoEscalacaoVermelha(
				$fields['codMembroEscala'],
				$fields['dataUltimoEscalacaoVermelha']
			)) {

				$response['success'] = true;
				$response['messages'] = 'Atualizado com sucesso';
				return $this->response->setJSON($response);
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
				return $this->response->setJSON($response);
			}
		}

		return $this->response->setJSON($response);
	}


	public function listaDropDownMembrosEscalas($codEscala = NULL)
	{

		if ($codEscala == NULL) {

			$codEscala = $this->request->getPost('codEscala');
		}

		$result = $this->EscalasModel->listaDropDownMembrosEscalas($codEscala);

		if ($result !== NULL) {

			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function trocarEscalaAgora()
	{

		$fields['codPrevisaoEscala'] = $this->request->getPost('codPrevisaoEscala');
		$fields['codEscala'] = $this->request->getPost('codEscala');
		$fields['codPessoaOriginal'] = $this->request->getPost('codPessoaOriginal');
		$fields['dataPrevisao'] = $this->request->getPost('dataPrevisao');
		$fields['codTipoEscala'] = $this->request->getPost('codTipoEscala');
		$fields['codPessoaTroca'] = $this->request->getPost('codPessoaTroca');
		$fields['tipoTroca'] = $this->request->getPost('tipoTroca');
		$fields['observacoes'] = $this->request->getPost('observacoes');
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['codAutor'] = session()->codPessoa;

		$this->validation->setRules([
			'codPrevisaoEscala' => ['label' => 'codPrevisaoEscala', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			$statusAdd = $this->EscalasModel->addTrocarEscala(
				$fields['codEscala'],
				$fields['codPrevisaoEscala'],
				$fields['codPessoaOriginal'],
				$fields['dataPrevisao'],
				$fields['codTipoEscala'],
				$fields['codPessoaTroca'],
				$fields['tipoTroca'],
				$fields['observacoes'],
				$fields['dataCriacao'],
				$fields['codAutor'],
			);


			/*

			if ($statusAdd === 3) {

				$nomeExibicao =  $this->PessoasModel->pegaPessoaPorCodPessoa($fields['codPessoaOriginal'])->nomeExibicao;
				$response['success'] = false;
				$response['messages'] = 'Já existe uma troca para "' . $nomeExibicao . '".';
				return $this->response->setJSON($response);
			}

			if ($statusAdd === 4) {

				$nomeExibicao =  $this->PessoasModel->pegaPessoaPorCodPessoa($fields['codPessoaTroca'])->nomeExibicao;
				$response['success'] = false;
				$response['messages'] = '"' . $nomeExibicao . '" já está trncando com outro usuário';
				return $this->response->setJSON($response);
			}
			*/
			if ($statusAdd == true) {

				$response['success'] = true;
				$response['messages'] = 'Troca configurada com sucesso';
				return $this->response->setJSON($response);
			}
		}

		return $this->response->setJSON($response);
	}

	public function prontoServico()
	{

		$fields['codMembroEscala'] = $this->request->getPost('codMembroEscala');

		$this->validation->setRules([
			'codMembroEscala' => ['label' => 'codMembroEscala', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->EscalasModel->prontoServico(
				$fields['codMembroEscala'],
			)) {

				$response['success'] = true;
				$response['messages'] = 'Atualizado com sucesso';
				return $this->response->setJSON($response);
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
				return $this->response->setJSON($response);
			}
		}

		return $this->response->setJSON($response);
	}

	public function removerDefinitivo()
	{

		$fields['codMembroEscala'] = $this->request->getPost('codMembroEscala');

		$this->validation->setRules([
			'codMembroEscala' => ['label' => 'codMembroEscala', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->EscalasModel->removerDefinitivo(
				$fields['codMembroEscala']
			)) {

				$response['success'] = true;
				$response['messages'] = 'Removido com sucesso';
				return $this->response->setJSON($response);
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
				return $this->response->setJSON($response);
			}
		}

		return $this->response->setJSON($response);
	}


	public function removerAfastamento()
	{

		$fields['codAfastamento'] = $this->request->getPost('codAfastamento');

		$this->validation->setRules([
			'codAfastamento' => ['label' => 'codAfastamento', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->EscalasModel->removerAfastamento(
				$fields['codAfastamento']
			)) {

				$response['success'] = true;
				$response['messages'] = 'Removido com sucesso';
				return $this->response->setJSON($response);
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
				return $this->response->setJSON($response);
			}
		}

		return $this->response->setJSON($response);
	}


	public function desativarDataVermelha()
	{

		$fields['codDataVermelha'] = $this->request->getPost('codDataVermelha');

		$this->validation->setRules([
			'codDataVermelha' => ['label' => 'codDataVermelha', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->EscalasModel->desativarDataVermelha(
				$fields['codDataVermelha']
			)) {

				$response['success'] = true;
				$response['messages'] = 'Desativado com sucesso';
				return $this->response->setJSON($response);
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
				return $this->response->setJSON($response);
			}
		}

		return $this->response->setJSON($response);
	}

	public function ativarDataVermelha()
	{

		$fields['codDataVermelha'] = $this->request->getPost('codDataVermelha');

		$this->validation->setRules([
			'codDataVermelha' => ['label' => 'codDataVermelha', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->EscalasModel->ativarDataVermelha(
				$fields['codDataVermelha']
			)) {

				$response['success'] = true;
				$response['messages'] = 'Desativado com sucesso';
				return $this->response->setJSON($response);
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
				return $this->response->setJSON($response);
			}
		}

		return $this->response->setJSON($response);
	}

	public function removerDataVermelha()
	{

		$fields['codDataVermelha'] = $this->request->getPost('codDataVermelha');

		$this->validation->setRules([
			'codDataVermelha' => ['label' => 'codDataVermelha', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->EscalasModel->removerDataVermelha(
				$fields['codDataVermelha']
			)) {

				$response['success'] = true;
				$response['messages'] = 'Desativado com sucesso';
				return $this->response->setJSON($response);
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
				return $this->response->setJSON($response);
			}
		}

		return $this->response->setJSON($response);
	}

	
	public function adicionarDataVermelha()
	{

		$response = array();

		$fields['recorrente'] = $this->request->getPost('recorrente');
		$fields['descricao'] = $this->request->getPost('descricao');
		$fields['dataVermelha'] = $this->request->getPost('dataVermelha');


		$this->validation->setRules([
			'recorrente' => ['label' => 'recorrente', 'rules' => 'required|numeric|max_length[11]'],
			'descricao' => ['label' => 'Descriçao', 'rules' => 'required'],
			'dataVermelha' => ['label' => 'Data Atualização', 'rules' => 'required'],
		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->EscalasModel->insertDataVermelha($fields['dataVermelha'], $fields['descricao'], $fields['recorrente'])) {

				$response['success'] = true;
				$response['messages'] = 'Data Vermelha inserida com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na inserção!';
			}
		}

		return $this->response->setJSON($response);
	}
	
	public function removerTroca()
	{

		$fields['codTrocaEscala'] = $this->request->getPost('codTrocaEscala');

		$this->validation->setRules([
			'codTrocaEscala' => ['label' => 'codTrocaEscala', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->EscalasModel->removerTroca(
				$fields['codTrocaEscala']
			)) {

				$response['success'] = true;
				$response['messages'] = 'Troca Removida com sucesso';
				return $this->response->setJSON($response);
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
				return $this->response->setJSON($response);
			}
		}

		return $this->response->setJSON($response);
	}
	public function mostrarPrevisaoEscala()
	{

		$response = array();
		$fields['codEscala'] = $this->request->getPost('codEscala');
		$dataLimiteLiberacao = $this->request->getPost('dataLimiteLiberacao');

		$dadosEscala = $this->EscalasModel->dadosEscala($fields['codEscala']);
		$dataLimiteLiberacao  = $dadosEscala->dataLimiteLiberacao;


		$html = '';

		$previsaoEscala = $this->EscalasModel->mostrarPrevisaoEscala($fields['codEscala'], $dataLimiteLiberacao);

		$html .= '<div class="row">';

		$btnTroca = array();
		foreach ($previsaoEscala as $previsao) {

			if ($previsao->codTipoEscala == 1) {
				$colorBtn = 'dark';
			} else {
				$colorBtn = 'danger';
			}


			if ($previsao->nomeExibicao == session()->nomeExibicao) {
				$colorDestaque = 'yellow';
			} else {
				$colorDestaque = '';
			}

			/*
			if (!in_array($previsao->codPessoa . $previsao->codTipoEscala, $btnTroca)) {
				array_push($btnTroca, $previsao->codPessoa . $previsao->codTipoEscala);
				$showBtnTroca = 'onclick="trocar(' . $previsao->codPrevisaoEscala . ')"';
			} else {

				$showBtnTroca = 'onclick="trocarNaoAutorizada()"';
			}
			*/
			$showBtnTroca = 'onclick="trocar(' . $previsao->codPrevisaoEscala . ')"';



			//TROCAS QUE ACONPANHAM A DATA

			$verificaTrocaAcompanhamData = $this->EscalasModel->verificaTrocaAcompanhamData($previsao->dataPrevisao, $previsao->codEscala);

			if (!empty($verificaTrocaAcompanhamData)) {
				//TEM TROCA


				$nomeExibicao = '
				<div style="font-size:12px"><s>' . $previsao->nomeExibicao . '</s></div>
				<div style="color:yellow"><img style="width:15px;" src="' . base_url() . '/imagens/atencao.gif"><b>' . $verificaTrocaAcompanhamData->nomeExibicaoEntra . '</b></div>
				';
				$showBtnTroca = 'onclick="editarTroca(' . $verificaTrocaAcompanhamData->codTrocaEscala . ')"';


				//VERIFICA SE MUDANÇA NA ESCALA O USUÁRIO QUE SUBSTITUI DEVE SEGUIR A PESSOA ORIGINAL DA TROCA

				if ($verificaTrocaAcompanhamData->tipoTroca == 2) {
					if ($verificaTrocaAcompanhamData->codPessoa	!== $previsao->codPessoa) {

						$nomeExibicao = '
						<div><b>' . $previsao->nomeExibicao . '</b></div>
						<div style="font-size:12px; color:yellow"><img style="width:20px;" src="' . base_url() . '/imagens/atencao.gif">' . $verificaTrocaAcompanhamData->nomeExibicaoEntra . ' deve seguir ' . $verificaTrocaAcompanhamData->nomeExibicaoSai . '</div>
						';
					}
				}
			} else {
				$nomeExibicao = $previsao->nomeExibicao;
			}


			$vermelha = $this->EscalasModel->verificaSeVermelha(strtotime($previsao->dataPrevisao));
			$ultimoServico = $this->EscalasModel->ultimoServico($fields['codEscala'], $previsao->codPessoa);

			if ($vermelha !== true) {
				$dataUltimoServico = date('d/m', strtotime($ultimoServico->dataUltimoEscalacaoPreta));
			} else {

				$dataUltimoServico = date('d/m', strtotime($ultimoServico->dataUltimoEscalacaoVermelha));
			}


			$html .= '<div class="col-md-2">';
			$html .= '
			<button  ' . $showBtnTroca . ' style="height: 120px;color: ' . $colorDestaque . ' !important; border-width: 3px;border-color: ' . $colorDestaque . ';margin-top:15px;font-size:12px" type="button" data-toggle="tooltip" data-placement="top" title="Último Serviço em: ' . $dataUltimoServico . '" class="btn btn-block bg-gradient-' . $colorBtn . ' btn-lg">
				<div class="text-center"><h5>' . diaSemanaAbreviado($previsao->dataPrevisao) . ' (' . date('d/m', strtotime($previsao->dataPrevisao)) . ')</h5></div>
				<div class="text-center"><h6>' . $nomeExibicao . '</h6></div>
			</button>
			
			';
			$html .= '</div>';
		}
		$html .= '</div>';
		$response['html'] = $html;
		return $this->response->setJSON($response);
	}
	public function atualizarPrevisaoEscala()
	{


		$fields['codEscala'] = $this->request->getPost('codEscala');

		$dadosEscala = $this->EscalasModel->dadosEscala($fields['codEscala']);

		if ($this->request->getPost('dataLimiteLiberacao') !== NULL) {
			$fields['dataLimiteLiberacao']  = $this->request->getPost('dataLimiteLiberacao');
		} else {
			$fields['dataLimiteLiberacao']  = $dadosEscala->dataLimiteLiberacao;
		}

		if ($this->request->getPost('folgaLiberacao') !== NULL) {
			$fields['folgaLiberacao']  = $this->request->getPost('folgaLiberacao');
		} else {
			$fields['folgaLiberacao']  = $dadosEscala->folgaLiberacao;
		}


		//fatorFolga

		if ($fields['folgaLiberacao'] == 24) {
			$fatorFolga = 2;
		}
		if ($fields['folgaLiberacao'] == 48) {
			$fatorFolga = 3;
		}
		if ($fields['folgaLiberacao'] == 72) {
			$fatorFolga = 4;
		}


		$this->validation->setRules([
			'codEscala' => ['label' => 'codEscala', 'rules' => 'required|numeric|max_length[11]'],
			'dataLimiteLiberacao' => ['label' => 'dataLimiteLiberacao', 'rules' => 'required'],
			'folgaLiberacao' => ['label' => 'folgaLiberacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->EscalasModel->atualizarPrevisaoEscala(
				$fields['codEscala'],
				$fields['dataLimiteLiberacao'],
				$fields['folgaLiberacao']
			)) {



				$dataLimiteLiberacao = strtotime($fields['dataLimiteLiberacao']);

				$dataReferencia = strtotime(date('Y-m-d')) + 86400;


				$this->EscalasModel->limpaPrevisaoEscala($fields['codEscala']);

				//$this->EscalasModel->limpaPrevisaoEscala($fields['codEscala'], 1);
				$prontoRetornoEscala = array();





				for ($x = $dataReferencia; $x <= $dataLimiteLiberacao; $x = $x + 86400) {




					//LISTA DE IMPEDIDOS NA DATA DE REFERÊNCIA
					$listaImpedidos = $this->EscalasModel->listaImpedidos($fields['codEscala'],  date('Y-m-d', $x));

					$impedidos = array();

					//DISPENSADOS
					foreach ($listaImpedidos as $row) {
						if ($row->codStatus == '2' and strtotime($row->dataEncerramentoAfastamento) >= $x) {

							array_push($impedidos, $row->codPessoa);
						}
						if ($row->codStatus !== '2') {

							array_push($impedidos, $row->codPessoa);
						}
					}


					/*
					//INCLUIR ANIVERSARIANTES NA LISTA DE IMPEDIDOS
					$aniversariantes = $this->PessoasModel->aniversariantesNaDataReferencia(date('m-d', $x));


					foreach ($aniversariantes as $aniversariante) {
						array_push($impedidos, $aniversariante->codPessoa);
					}


					*/

					$vermelha = $this->EscalasModel->verificaSeVermelha($x);

					if ($vermelha !== true) {

						//PRETA

						//MEMBROS PRETA

						$folgasPretas = $this->EscalasModel->folgasPretas($fields['codEscala'], date('Y-m-d', $x));


						foreach ($folgasPretas as $folga) {

							if ($folga->codPessoa !== NULL and !in_array($folga->codPessoa, $impedidos)) {


								//NÃO DEIXA O ANTERIOR SER A MESMA PESSOA

								$ultimaEscala = $this->EscalasModel->ultimaEscala($folga->codPessoa, $fields['codEscala']);

								if (date('Y-m-d', strtotime($ultimaEscala->dataPrevisao)) <= date('Y-m-d', strtotime(date('Y-m-d', $x) . ' -' . $fatorFolga . ' day'))) {

									$this->EscalasModel->addPrevisaoEscala(date('Y-m-d', $x), 1, $fields['codEscala'], $folga->codPessoa);
									break;
								}
							}
						}
					} else {
						//VERMELHA
						$folgasVermelhas = $this->EscalasModel->folgasVermelhas($fields['codEscala'], date('Y-m-d', $x));

						foreach ($folgasVermelhas as $folga) {

							if ($folga->codPessoa !== NULL and !in_array($folga->codPessoa, $impedidos)) {

								$ultimaEscala = $this->EscalasModel->ultimaEscala($folga->codPessoa, $fields['codEscala']);

								if (date('Y-m-d', strtotime($ultimaEscala->dataPrevisao)) <= date('Y-m-d', strtotime(date('Y-m-d', $x) . ' -' . $fatorFolga . ' day'))) {

									$this->EscalasModel->addPrevisaoEscala(date('Y-m-d', $x), 2, $fields['codEscala'], $folga->codPessoa);
									break;
								}
							}
						}
					}


					//reinicia o loop
					$dataReferencia = $dataReferencia + 86400;
				}

				$response['success'] = true;
				$response['messages'] = 'Escala atualizada com sucesso';
				return $this->response->setJSON($response);
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
				return $this->response->setJSON($response);
			}
		}

		return $this->response->setJSON($response);
	}
	public function listaDropDownMotivosAfastamentos()
	{
		$result = $this->EscalasModel->listaDropDownMotivosAfastamentos();

		if ($result !== NULL) {

			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function remove()
	{
		$response = array();

		$id = $this->request->getPost('codEscala');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->EscalasModel->where('codEscala', $id)->delete()) {

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
