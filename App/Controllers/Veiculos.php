<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;
use App\Models\DocumentosVeiculoModel;

use App\Models\VeiculosModel;

use CodeIgniter\Files\File;

class Veiculos extends BaseController
{

	protected $VeiculosModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{


		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->VeiculosModel = new VeiculosModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->PessoasModel = new PessoasModel();
		$this->DocumentosVeiculoModel = new DocumentosVeiculoModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		if (gethostname() !== 'TELL' and gethostname() !== 'sandra-producao-01' and gethostname() !== 'sandra-dev-01') {
			echo mensagemAcessoSomenteIntranet(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso somente pela intranet', session()->codPessoa);
			exit();
		}

		$data = [
			'controller'    	=> 'veiculos',
			'title'     		=> 'Veículos'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('veiculos', $data);
	}

	public function pegaMarcas()
	{
		$response = array();

		$listaMarcas = array();
		$marcas = $this->VeiculosModel->pegaMarcas();

		foreach ($marcas as $marca) {
			array_push($listaMarcas, $marca->descricao);
		}
		$response['marcas'] = json_encode($listaMarcas);

		return $this->response->setJSON($response);
	}

	public function pegaModelos()
	{
		$response = array();

		$listaModelos = array();
		$modelos = $this->VeiculosModel->pegaModelos();

		foreach ($modelos as $modelo) {
			array_push($listaModelos, $modelo->descricao);
		}
		$response['modelos'] = json_encode($listaModelos);

		return $this->response->setJSON($response);
	}
	public function pegaCores()
	{
		$response = array();

		$listaCores = array();
		$cores = $this->VeiculosModel->pegaCores();

		foreach ($cores as $cor) {
			array_push($listaCores, $cor->descricao);
		}
		$response['cores'] = json_encode($listaCores);

		return $this->response->setJSON($response);
	}
	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$codPessoa = session()->codPessoa;
		$codPaciente = session()->codPaciente;

		$permissao = verificaPermissao('Veiculos', 'deletar');
		if ($permissao == 0) {
			$result = $this->VeiculosModel->pegaVeiculosUsuario($codPessoa, $codPaciente);
		} else {
			$result = $this->VeiculosModel->pegaVeiculosUsuario(-1, NULL);
		}
		$x = 0;
		foreach ($result as $key => $value) {

			if ($value->nomeExibicaoPessoa !== NULL) {
				$nomeExibicao = $value->nomeExibicaoPessoa;
			}
			if ($value->nomeExibicaoPaciente !== NULL) {
				$nomeExibicao = $value->nomeExibicaoPaciente;
			}

			if ($value->codStatus == 0) {
				$status = '<span class="right badge badge-danger">Aguardando aprovação</span>';
			}

			if ($value->codStatus == 1) {
				$status = '<span class="right badge badge-success">Aprovado</span>';
			}

			if ($value->codStatus == 2) {
				$status = '<span class="right badge badge-danger">Rejeitado</span>';
			}
			if ($value->codStatus == 3) {
				$status = '<span class="right badge badge-danger">Vencida</span>';
			}

			/*

			DESATIVAÇÃO DO VENCIMENTO AUTOMÁTICO

			if (date('Y-m-d') >= $value->dataValidade and $value->dataValidade !== NULL) {

				$fields['codStatus'] = 3;
				$fields['dataAtualizacao'] = date('Y-m-d H:i');


				if ($this->VeiculosModel->update($value->codVeiculo, $fields)) {
				}
			}
			*/
			$x++;
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editveiculos(' . $value->codVeiculo . ')"><i class="fa fa-edit"></i></button>';

			$permissao = verificaPermissao('Veiculos', 'deletar');
			if ($permissao == 0) {
			} else {
				if ($value->codStatus !== '1') {
					$ops .= '	<button type="button" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="Aprovar"  onclick="aprovarveiculo(' . $value->codVeiculo . ')"><i class="fa fa-check"></i></button>';
				} else {
					$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Reprovar"  onclick="rejeitarveiculo(' . $value->codVeiculo . ')"><i class="fa fa-check"></i></button>';
				}
			}

			$ops .= '	<button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="Imprimir"  onclick="imprimirCartaoEstacionamento(' . $value->codVeiculo . ')"><i class="fa fa-print"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeveiculos(' . $value->codVeiculo . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			if ($value->dataAutorizacao) {
				$dataAutorizacao = date('d//m/Y', strtotime($value->dataAutorizacao));
			} else {
				$dataAutorizacao = NULL;
			}

			if ($value->dataValidade) {

				$dataValidade = date('d/m/Y', strtotime($value->dataValidade));
			} else {
				$dataValidade = NULL;
			}


			$data['data'][$key] = array(
				$x,
				$value->placa,
				$nomeExibicao,
				$value->marca,
				$value->modelo,
				$value->cor,
				$status,
				$dataAutorizacao,
				$dataValidade,
				$value->observacao,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}
	public function imprimirCartaoEstacionamento()
	{
		$response = array();

		$codVeiculo = $this->request->getPost('codVeiculo');

		if ($this->validation->check($codVeiculo, 'required|numeric')) {

			$data = $this->VeiculosModel->pegaPorCodigo($codVeiculo);

			if ($data->codStatus == 0) {

				$response['success'] = false;
				$response['messages'] = 'Só é possível imprimir o carão de estacionamento após a aprovação do seu requerimento. Aguarde a análise da segurança Orgânica!';

				return $this->response->setJSON($response);
			}

			if (date('Y-m-d') >= $data->dataValidade) {

				$response['success'] = false;
				$response['messages'] = 'Autorização vencida. Contate a segurança orgânica!';

				return $this->response->setJSON($response);
			}



			if ($data->codStatus == 1) {

				if ($data->nomeExibicaoPessoa !== NULL) {
					$nomeusuario = $data->nomeExibicaoPessoa . " (" . $data->descricaoDepartamento . ")";
				}
				if ($data->nomeExibicaoPaciente !== NULL) {
					$nomeusuario = $data->nomeExibicaoPaciente;
				}


				$html = '
			
				<center><div style="width:8cm;margin-top:30px;border-color:#000 !important" class="row border border-5 d-flex justify-content-center  align-items-center" >
	
					<div class="row">
						<div class="col-md-12">
							<img style="width:50px" src="' . base_url() . '/imagens/organizacoes/' . session()->logo . '">
						</div>
						<div style="font-weight: bold;margin-top:10px;font-size:16px" class="col-md-12">
							' . session()->descricaoOrganizacao . '
						</div>

					</div>
	
				</div>
				<div style="width:8cm;border-color:#000 !important" class="row border d-flex justify-content-center  align-items-center" >
					<div style="font-weight: bold;font-size:20px;margin-top:20px;" class="col-md-12" >ESTACIONAMENTO</div>
					<div style="font-weight: bold;font-size:30px;" class="col-md-12" >(' . date('Y', strtotime($data->dataValidade)) . ')</div>
					<div style="margin-top:20px;font-size:40px" class="col-md-12" >' . $data->placa . '</div>
					<div style="margin-top:20px;" id="qrcodeCartao"></div>
					<div style="margin-top:30px;font-size:14px" class="col-md-12" >Mantenha este cartão em local visível</div>
					<div style="font-size:14px" class="col-md-12" >Este cartão não dispensa a identificação pessoal</div>
					<div style="margin-top:20px;font-size:25px" class="col-md-12" >Validade: ' . nomeMesPorExtenso(date('m', strtotime($data->dataValidade))) . ' - ' . date('Y', strtotime($data->dataValidade)) . '</div>

				</div>
				</center>
				<div style="border-bottom: 1px dotted; margin-top:20px" class="row"></div>
				<div style="font-weight: bold;font-size:20px;margin-top:20px;" class="col-md-12" >
				Avisos
				</div>
				<div style="font-size:18px;margin-top:10px;" class="col-md-12" >
				1) Este cartão foi gerado para identificação do seu veículo; <br> 
				2) A validade do cartão está impressa no mesmo;<br>
				3) Recorte, plastifique e coloque em local visível do seu veículo; e<br>
				4) A identificação deste será feita por dispositivo eletrônico com leitos de QRCode não conectado a rede Wifi da organização.
				</div>
					

				
				
				';

				$response['success'] = true;
				$response['messages'] = 'HTML';
				$response['html'] = $html;
				$response['dadosQR'] = $nomeusuario;


				return $this->response->setJSON($response);
			}

			if ($data->codStatus == 2) {

				$response['success'] = false;
				$response['messages'] = 'Autorização rejeitada. Contate a segurança orgânica';

				return $this->response->setJSON($response);
			}

			if ($data->codStatus == 3) {

				$response['success'] = false;
				$response['messages'] = 'Autorização vencida';

				return $this->response->setJSON($response);
			}





			return $this->response->setJSON($response);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}
	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codVeiculo');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->VeiculosModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();


		/*
		$arquivo = $this->request->getFile('file');
		$nomeArquivo = session()->cpf .	geraNumero(3) . '.' . $arquivo->getClientExtension();
		$arquivo->move(WRITEPATH . '../arquivos/veiculos/',  $nomeArquivo, true);
		$response['success'] = $nomeArquivo;
		return $this->response->setJSON($response);

		//	exit();
*/
		if (session()->codPessoa !== NULL) {
			$fields['codPessoa'] = session()->codPessoa;
			$fields['codAutor'] = session()->codPessoa;
		}

		if (session()->codPaciente !== NULL) {
			$fields['codPaciente'] = session()->codPaciente;
			$fields['codAutor'] = session()->codPaciente;
		}

		$fields['codVeiculo'] = $this->request->getPost('codVeiculo');
		$fields['placa'] = mb_strtoupper($this->request->getPost('placa'), "utf-8");
		$fields['cpf'] = removeCaracteresIndesejados($this->request->getPost('cpf'));
		$fields['condutor1'] =  mb_strtoupper($this->request->getPost('condutor1'), "utf-8");
		$fields['condutor2'] =  mb_strtoupper($this->request->getPost('condutor2'), "utf-8");
		$fields['codVisitante'] = NULL;
		$fields['marca'] =  mb_strtoupper($this->request->getPost('marca'), "utf-8");
		$fields['modelo'] =  mb_strtoupper($this->request->getPost('modelo'), "utf-8");
		$fields['cor'] = $this->request->getPost('cor');
		$fields['codStatus'] = 0;
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['dataAutorizacao'] = NULL;
		$fields['dataValidade'] = NULL;
		$fields['observacao'] = $this->request->getPost('observacao');


		$this->validation->setRules([
			'placa' => ['label' => 'Placa', 'rules' => 'required|max_length[7]|bloquearReservado'],
			'cpf' => ['label' => 'Cpf', 'rules' => 'required|max_length[15]'],
			'codPessoa' => ['label' => 'CodPessoa', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'codVisitante' => ['label' => 'CodVisitante', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'marca' => ['label' => 'Marca', 'rules' => 'required|max_length[30]|bloquearReservado'],
			'modelo' => ['label' => 'Modelo', 'rules' => 'required|max_length[30]|bloquearReservado'],
			'cor' => ['label' => 'Cor', 'rules' => 'required|max_length[20]|bloquearReservado'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'dataAutorizacao' => ['label' => 'DataAutorizacao', 'rules' => 'permit_empty'],
			'dataValidade' => ['label' => 'DataValidade', 'rules' => 'permit_empty'],
			'observacao' => ['label' => 'Observacao', 'rules' => 'permit_empty|bloquearReservado'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($codVeiculo = $this->VeiculosModel->insert($fields)) {



				$response['codVeiculo'] = $codVeiculo;
				$response['success'] = true;
				$response['messages'] = 'Adicionado com sucesso';


				$codOrganizacao = session()->codOrganizacao;
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na inserção!';
			}
		}

		return $this->response->setJSON($response);
	}



	public function aprovarveiculo()
	{


		$response = array();
		$fields['codVeiculo'] = $this->request->getPost('codVeiculo');
		$fields['codStatus'] = 1;
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['dataAutorizacao'] = date('Y-m-d H:i');
		$fields['dataValidade'] = date('Y-12-31 23:59'); //date('Y-m-d', strtotime(date('Y-m-d H:i') . ' + 365 days'));


		if (!$this->validation->check($fields['codVeiculo'], 'required|numeric')) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {


			if ($this->VeiculosModel->update($fields['codVeiculo'], $fields)) {


				//NOTIFICAÇÃO DO USUÁRIO

				if (session()->codPessoa !== NULL) {
					$funcionario = 1;
					$fields['codAutor'] = session()->codPessoa;
					$fields['marcadoPor'] = session()->codPessoa;
					$pessoa = $this->PessoasModel->pegaPessoaDepartamento(session()->codPessoa);
					$nomeFuncionarioResponsavel = $pessoa->nomeExibicao;
					$nomeDepartamentoResponsavel = $pessoa->descricaoDepartamento;
				} else {
					$funcionario = 0;
					if (session()->codPaciente !== NULL) {
						$fields['marcadoPor'] = session()->codPaciente;
						$fields['codAutor'] = session()->codPaciente;
					}
				}



				//ENVIAR NOTIFICAÇÃO

				$data = $this->VeiculosModel->pegaPorCodigo($this->request->getPost('codVeiculo'));

				if ($data->codPessoa !== NULL) {
					$emailPessoal = $data->emailPessoalPessoa;
					$nomeExibicao = $data->nomeExibicaoPessoa;
				}
				if ($data->codPaciente !== NULL) {
					$emailPessoal = $data->emailPessoalPaciente;
					$nomeExibicao = $data->nomeExibicaoPaciente;
				}

				if ($emailPessoal !== NULL and $emailPessoal !== "" and $emailPessoal !== " ") {
					$email = $emailPessoal;
					$email = removeCaracteresIndesejadosEmail($email);
				} else {
					$email = NULL;
				}

				if ($email !== NULL and $nomeExibicao !== NULL) {
					$conteudo = "
									<div> Caro senhor(a), " . $nomeExibicao . ",</div>";
					$conteudo .= "<div>Sua entrada no estacionamento foi concedida até " . nomeMesPorExtenso(date('m', strtotime($data->dataValidade))) . ' - ' . date('Y', strtotime($data->dataValidade)) . " </div>";
					$conteudo .= "<div>Imprima o seu QRCode! </div>";

					$conteudo .= "<div style='font-size: 12px;margin-top:16px'>Atenciosamente,</div>";
					if ($funcionario == 1) {
						$conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeFuncionarioResponsavel . "</div>";
						$conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeDepartamentoResponsavel  . "</div>";
					}
					$conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . session()->descricaoOrganizacao . "</div>";

					$resultadoEmail = @email($email, 'AUTORIZAÇÃO DE ESTACIONAMENTO', $conteudo);
					if ($resultadoEmail == false) {

						//ADICIONAR NOTIFICAÇÃO ANA FILA EM CASO DE FALHA
						@addNotificacoesFila($conteudo, $email, $email, 1);
					}
				}


				$response['success'] = true;
				$response['messages'] = 'Aprovado com sucesso';
				sleep(1);
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
			}
		}






		return $this->response->setJSON($response);
	}

	public function rejeitarveiculo()
	{

		$response = array();
		$fields['codVeiculo'] = $this->request->getPost('codVeiculo');
		$fields['codStatus'] = 2;
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['dataAutorizacao'] = NULL;
		$fields['dataValidade'] = NULL;


		if ($this->VeiculosModel->update($fields['codVeiculo'], $fields)) {

			$response['success'] = true;
			$response['messages'] = 'Atualizado com sucesso';
		} else {

			$response['success'] = false;
			$response['messages'] = 'Erro na atualização!';
		}


		return $this->response->setJSON($response);
	}


	public function edit()
	{

		$response = array();

		$fields['codVeiculo'] = $this->request->getPost('codVeiculo');
		$fields['placa'] = $this->request->getPost('placa');
		$fields['cpf'] = removeCaracteresIndesejados($this->request->getPost('cpf'));
		$fields['condutor1'] =  mb_strtoupper($this->request->getPost('condutor1'), "utf-8");
		$fields['condutor2'] =  mb_strtoupper($this->request->getPost('condutor2'), "utf-8");
		$fields['marca'] = $this->request->getPost('marca');
		$fields['modelo'] = $this->request->getPost('modelo');
		$fields['cor'] = $this->request->getPost('cor');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['observacao'] = $this->request->getPost('observacao');



		$this->validation->setRules([
			'placa' => ['label' => 'Placa', 'rules' => 'required|max_length[7]|bloquearReservado'],
			'cpf' => ['label' => 'Cpf', 'rules' => 'required|max_length[15]'],
			'codPessoa' => ['label' => 'CodPessoa', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'codVisitante' => ['label' => 'CodVisitante', 'rules' => 'permit_empty|numeric|max_length[11]'],
			'marca' => ['label' => 'Marca', 'rules' => 'required|max_length[30]|bloquearReservado'],
			'modelo' => ['label' => 'Modelo', 'rules' => 'required|max_length[30]|bloquearReservado'],
			'cor' => ['label' => 'Cor', 'rules' => 'required|max_length[20]|bloquearReservado'],
			'dataAutorizacao' => ['label' => 'DataAutorizacao', 'rules' => 'permit_empty'],
			'dataValidade' => ['label' => 'DataValidade', 'rules' => 'permit_empty'],
			'observacao' => ['label' => 'Observacao', 'rules' => 'permit_empty|bloquearReservado'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->VeiculosModel->update($fields['codVeiculo'], $fields)) {

				$response['success'] = true;
				$response['messages'] = 'Atualizado com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na atualização!';
			}
		}

		return $this->response->setJSON($response);
	}

	function enviarArquivo()
	{


		$response = array();


		$codVeiculo = $this->request->getPost('codVeiculo');
		$arquivo = $this->request->getFile('file');
		$nomeArquivo = session()->cpf . geraNumero(3) . '.' . $arquivo->getClientExtension();


		//VERIFICA TAMANHO
		/*
		$getClientMimeType = $arquivo->getClientMimeType();
		$response['success'] = false;
		$response['messages'] = $getClientMimeType;
		return $this->response->setJSON($response);
*/
		if ($arquivo->getSize() > 10000000) {
			$response['success'] = false;
			$response['messages'] = "Desculpe, o arquivo é muito grande. Máximo de 10Mb.";
			return $this->response->setJSON($response);
		}


		//VERIFICA TIPO
		$getClientMimeType = $arquivo->getClientMimeType();
		if (
			$getClientMimeType !== 'application/pdf'
			and $getClientMimeType !== 'image/png'
			and $getClientMimeType !== 'image/jpg'
			and $getClientMimeType !== 'image/jpeg'
			and $getClientMimeType !== 'image/gif'
		) {
			$response['success'] = false;
			$response['messages'] = "Desculpe, somente arquivos com as extensões à seguir são permitidos: PDF, JPG, JPEG, PNG & GIF";
			return $this->response->setJSON($response);
		}




		$fields['codVeiculo'] = $codVeiculo;
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['codStatus'] = 0;
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['documento'] = $nomeArquivo;

		if (session()->codPessoa !== NULL) {
			$fields['codPessoa'] = session()->codPessoa;
			$fields['codAutor'] = session()->codPessoa;
		}

		if (session()->codPaciente !== NULL) {
			$fields['codPaciente'] = session()->codPaciente;
			$fields['codAutor'] = session()->codPaciente;
		}


		if ($this->DocumentosVeiculoModel->insert($fields)) {
			$arquivo->move(WRITEPATH . '../arquivos/veiculos/',  $nomeArquivo, true);
		}

		$response['success'] = true;
		$response['messages'] = 'Arquivo Incluído com sucesso';

		return $this->response->setJSON($response);
	}

	public function verDocumento()
	{
		$response = array();

		$codDocumento = $this->request->getPost('codDocumento');

		if ($this->validation->check($codDocumento, 'required|numeric')) {

			$data = $this->DocumentosVeiculoModel->pegaPorCodigo($codDocumento);


			$response['success'] = true;
			$response['documento'] = '

			<object data="' . base_url() . '/arquivos/veiculos/' . $data->documento . '" type="application/pdf" style="width:90vw;height:80vh;">
				<p>Alternative text - include a link <a href="' . base_url() . '/arquivos/veiculos/' . $data->documento . '">to the PDF!</a></p>
	   		 </object>


			';
			return $this->response->setJSON($response);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}



	public function documentosVeiculo()
	{
		$response = array();

		$data['data'] = array();


		$codVeiculo = $this->request->getPost('codVeiculo');
		$result = $this->DocumentosVeiculoModel->pegaPorCodVeiculo($codVeiculo);

		$x = 0;
		foreach ($result as $key => $value) {
			$x++;
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Ver"  onclick="verDocumentoVeiculo(' . $value->codDocumento . ')">Ver</button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removedocumentosVeiculo(' . $value->codDocumento . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$x,
				$value->dataCriacao,
				$value->documento,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}


	public function remove()
	{
		$response = array();

		$id = $this->request->getPost('codVeiculo');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->VeiculosModel->where('codVeiculo', $id)->delete()) {

				$response['success'] = true;
				$response['messages'] = 'Deletado com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na deleção!';
			}
		}

		return $this->response->setJSON($response);
	}

	public function removeDocumentosVeiculo()
	{
		$response = array();

		$id = $this->request->getPost('codDocumento');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->DocumentosVeiculoModel->where('codDocumento', $id)->delete()) {

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
