<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\PacientesModel;
use App\Models\FaturamentoModel;
use App\Models\AtendimentosModel;
use App\Models\FaturamentoTaxasServicosModel;
use App\Models\FaturamentoProcedimentosModel;
use App\Models\FaturamentoMedicamentosModel;
use App\Models\FaturamentoMateriaisModel;
use App\Models\AtendimentosPrescricoesModel;
use App\Models\FaturamentoKitsModel;

class Faturamento extends BaseController
{

	protected $FaturamentoModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $LogsModel;

	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->FaturamentoModel = new FaturamentoModel();
		$this->PacientesModel = new PacientesModel();
		$this->FaturamentoTaxasServicosModel = new FaturamentoTaxasServicosModel();
		$this->FaturamentoProcedimentosModel = new FaturamentoProcedimentosModel();
		$this->FaturamentoMedicamentosModel = new FaturamentoMedicamentosModel();
		$this->FaturamentoKitsModel = new FaturamentoKitsModel();
		$this->FaturamentoMateriaisModel = new FaturamentoMateriaisModel();
		$this->AtendimentosPrescricoesModel = new AtendimentosPrescricoesModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->AtendimentosModel = new AtendimentosModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('Faturamento', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "Faturamento"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'faturamento',
			'title'     		=> 'Faturamento'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('faturamento', $data);
	}


	public function contasAbertas()
	{
		$response = array();

		$data['data'] = array();


		if ($this->request->getPost('codDepartamento') !== NULL and $this->request->getPost('codDepartamento') !== "" and $this->request->getPost('codDepartamento') !== 0) {
			$result =  $this->FaturamentoModel->contasAbertas($this->request->getPost('codDepartamento'));
		} else {

			$result =  $this->FaturamentoModel->contasAbertas();
		}




		$x = 0;
		foreach ($result as $key => $value) {

			//VERIRICA SE TEM PRESCRIÇÃO
			$verificaSePrescricao =  $this->FaturamentoModel->verificaSePrescricao($value->codAtendimento);
			$x++;

			if ($verificaSePrescricao !== NULL) {
				$prescricao = 'Sim';
			} else {
				$prescricao = 'Não';
			}


			$ops = '
            <div class="btn-group">
                <button type="button" class="btn btn-primary">Ação</button>
                <button type="button" class="btn btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu" role="menu">
                <a href="#" class="dropdown-item" onclick="verFaturas(' . $value->codAtendimento . ')">Ver Faturas</a>
                <a href="#" class="dropdown-item" onclick="encerrarConta(' . $value->codAtendimento . ')">Encerrar Conta</a>
                <a href="#" class="dropdown-item" onclick="reabrirConta(' . $value->codAtendimento . ')">Reabrir Conta</a>
                </div>
            </div>';



			$verificaUltimaFatura =  $this->FaturamentoModel->verificaUltimaFatura($value->codAtendimento);

			if ($verificaUltimaFatura !== NULL) {
				if (date('Y-m-d') > date('Y-m-d', strtotime($verificaUltimaFatura->dataCriacao . ' +7 day'))) {

					$tempoUltimaFatura = intervaloTempoFatura(date('Y-m-d'), $verificaUltimaFatura->dataCriacao);

					$ultimaFatura = '<div><span class="right badge badge-danger">' . $tempoUltimaFatura . '</span></div>';
				} else {
					$ultimaFatura = '<div>' . date('d/m/Y', strtotime($verificaUltimaFatura->dataCriacao)) . '</div>
					';
				}
			} else {
				$ultimaFatura = '<span class="right badge badge-danger">Não Possui</span>';
			}
			/*
				$tempo =  intervaloTempoFatura($value->dataCriacao, date('Y-m-d H:i'));
				$tempoAtendimento = '<span class="right badge badge-info">' . $tempo . '</span>';
			*/
			if ($value->codLocalAtendimento == 0) {
				$descricaoLocalAtendimento =  "Acolhimento";
			} else {
				$descricaoLocalAtendimento = '
                <div>' . $value->descricaoDepartamento . '</div>
                <div class="right badge badge-success">' . mb_strtoupper($value->descricaoLocalAtendimento, 'utf-8') . '</div>
                ';

				//$descricaoLocalAtendimento =  $value->descricaoDepartamento . " (" . $value->descricaoLocalAtendimento . ")";
			}

			// if ($value->dataInicioPrescricao == date("Y-m-d", time() + 86400) and $value->codStatusPrescricao == 2) {


			$statusAtendimento = '
            <div>
             <span class="right badge badge-warning">' . $value->descricaoStatusAtendimento . '</span>
            </div>
            
            ';

			if (strlen($value->hda) >= 100) {

				$conteudoHDA = mb_substr($value->hda, 0, 90);
			} else {
				$conteudoHDA = $value->hda;
			}


			$data['data'][$key] = array(
				$value->codAtendimento,
				$value->nomeCompleto,
				date('d/m/Y H:i', strtotime($value->dataCriacao)),
				$descricaoLocalAtendimento,
				$statusAtendimento,
				//$tempoAtendimento,
				$prescricao,
				$ultimaFatura,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}



	public function buscaAvancada()
	{
		$response = array();

		$data['data'] = array();



		if ($this->request->getPost('paciente') !== NULL and $this->request->getPost('paciente') !== "" and $this->request->getPost('paciente') !== 0) {
			$result =  $this->FaturamentoModel->buscaAvancada($this->request->getPost('paciente'));
		} else {
		}


		$x = 0;
		foreach ($result as $key => $value) {

			//VERIRICA SE TEM PRESCRIÇÃO
			$verificaSePrescricao =  $this->FaturamentoModel->verificaSePrescricao($value->codAtendimento);
			$x++;

			if ($verificaSePrescricao !== NULL) {
				$prescricao = 'Sim';
			} else {
				$prescricao = 'Não';
			}


			$ops = '
            <div class="btn-group">
                <button type="button" class="btn btn-primary">Ação</button>
                <button type="button" class="btn btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu" role="menu">
                <a href="#" class="dropdown-item" onclick="verFaturas(' . $value->codAtendimento . ')">Ver Faturas</a>
                <a href="#" class="dropdown-item" onclick="encerrarConta(' . $value->codAtendimento . ')">Encerrar Conta</a>
                <a href="#" class="dropdown-item" onclick="reabrirConta(' . $value->codAtendimento . ')">Reabrir Conta</a>
                </div>
            </div>';



			$verificaUltimaFatura =  $this->FaturamentoModel->verificaUltimaFatura($value->codAtendimento);

			if ($verificaUltimaFatura !== NULL) {
				if (date('Y-m-d') > date('Y-m-d', strtotime($verificaUltimaFatura->dataCriacao . ' +7 day'))) {

					$tempoUltimaFatura = intervaloTempoFatura(date('Y-m-d'), $verificaUltimaFatura->dataCriacao);

					$ultimaFatura = '<div><span class="right badge badge-danger">' . $tempoUltimaFatura . '</span></div>';
				} else {
					$ultimaFatura = '<div>' . date('d/m/Y', strtotime($verificaUltimaFatura->dataCriacao)) . '</div>
					';
				}
			} else {
				$ultimaFatura = '<span class="right badge badge-danger">Não Possui</span>';
			}
			/*
				$tempo =  intervaloTempoFatura($value->dataCriacao, date('Y-m-d H:i'));
				$tempoAtendimento = '<span class="right badge badge-info">' . $tempo . '</span>';
			*/
			if ($value->codLocalAtendimento == 0) {
				$descricaoLocalAtendimento =  "Acolhimento";
			} else {
				$descricaoLocalAtendimento = '
                <div>' . $value->descricaoDepartamento . '</div>
                <div class="right badge badge-success">' . mb_strtoupper($value->descricaoLocalAtendimento, 'utf-8') . '</div>
                ';

				//$descricaoLocalAtendimento =  $value->descricaoDepartamento . " (" . $value->descricaoLocalAtendimento . ")";
			}

			// if ($value->dataInicioPrescricao == date("Y-m-d", time() + 86400) and $value->codStatusPrescricao == 2) {


			$statusAtendimento = '
            <div>
             <span class="right badge badge-warning">' . $value->descricaoStatusAtendimento . '</span>
            </div>
            
            ';

			if (strlen($value->hda) >= 100) {

				$conteudoHDA = mb_substr($value->hda, 0, 90);
			} else {
				$conteudoHDA = $value->hda;
			}


			$data['data'][$key] = array(
				$value->codAtendimento,
				$value->nomeCompleto,
				date('d/m/Y H:i', strtotime($value->dataCriacao)),
				$descricaoLocalAtendimento,
				$statusAtendimento,
				//$tempoAtendimento,
				$prescricao,
				$ultimaFatura,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}



	public function contasFechadas()
	{
		$response = array();

		$data['data'] = array();

		if ($this->request->getPost('codDepartamento') !== NULL and $this->request->getPost('codDepartamento') !== "" and $this->request->getPost('codDepartamento') !== 0) {
			$result =  $this->FaturamentoModel->contasFechadas($this->request->getPost('codDepartamento'));
		} else {

			$result =  $this->FaturamentoModel->contasFechadas();
		}


		$x = 0;
		foreach ($result as $key => $value) {
			$x++;
			$ops = '
            <div class="btn-group">
                <button type="button" class="btn btn-primary">Ação</button>
                <button type="button" class="btn btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu" role="menu">
                <a href="#" class="dropdown-item" onclick="verFaturas(' . $value->codAtendimento . ')">Ver Faturas</a>
                <a href="#" class="dropdown-item" onclick="encerrarConta(' . $value->codAtendimento . ')">Encerrar Conta</a>
                <a href="#" class="dropdown-item" onclick="reabrirConta(' . $value->codAtendimento . ')">Reabrir Conta</a>
                </div>
            </div>
            ';


			$tempo =  intervaloTempoAtendimento($value->dataCriacao, date('Y-m-d H:i'));
			$tempoAtendimento = '<span class="right badge badge-info">' . $tempo . '</span>';

			if ($value->codLocalAtendimento == 0) {
				$descricaoLocalAtendimento =  "Acolhimento";
			} else {
				$descricaoLocalAtendimento = '
                <div>' . $value->descricaoDepartamento . '</div>
                <div class="right badge badge-success">' . mb_strtoupper($value->descricaoLocalAtendimento, 'utf-8') . '</div>
                ';

				//$descricaoLocalAtendimento =  $value->descricaoDepartamento . " (" . $value->descricaoLocalAtendimento . ")";
			}

			// if ($value->dataInicioPrescricao == date("Y-m-d", time() + 86400) and $value->codStatusPrescricao == 2) {


			$statusAtendimento = '
            <div>
             <span class="right badge badge-warning">' . $value->descricaoStatusAtendimento . '</span>
            </div>
            
            ';

			if (strlen($value->hda) >= 100) {

				$conteudoHDA = mb_substr($value->hda, 0, 90);
			} else {
				$conteudoHDA = $value->hda;
			}


			$data['data'][$key] = array(
				$value->codAtendimento,
				$value->nomeCompleto,
				$descricaoLocalAtendimento,
				$statusAtendimento,
				$tempoAtendimento,
				date('d/m/Y H:i', strtotime($value->dataCriacao)),
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function buscaTaxasServicos($codAtendimento = NULL)
	{

		//DEATIVARDO POIS TRAS MUITO LIXO
		//sleep(1);


		/*
		if ($codAtendimento !== NULL) {
			$codAtendimento = $codAtendimento;
		} else {
			if ($this->request->getPost('codAtendimento') !== NULL) {
				$codAtendimento = $this->request->getPost('codAtendimento');
			} else {
				$codAtendimento = NULL;

				$response['success'] = true;
				$response['quantidade'] = 0;
				$response['messages'] = 'Existem 0 Taxas e Serviços';
				return $this->response->setJSON($response);
			}
		}

		$dataInicio = $this->request->getPost('dataInicio');
		$dataEncerramento = $this->request->getPost('dataEncerramento');
		$codFatura = $this->request->getPost('codFatura');
		$ccusto = $this->request->getPost('ccusto');


		$ultimoLancamento = $this->FaturamentoTaxasServicosModel->ultimoLancamento($codAtendimento);

		if ($ultimoLancamento->dataEncerramento < $dataEncerramento or $dataEncerramento == NULL) {
			$prescricoes = $this->AtendimentosPrescricoesModel->pegaDiarias($codAtendimento, $dataInicio, $dataEncerramento, $ccusto);
		}
		if (!empty($prescricoes)) {



			$qtdDiarias = 0;
			$totalGeralDiarias = 0;
			$diasJaComputados = array();
			$locaisAtendimento = array();



			//LOCAIS


			foreach ($prescricoes as $prescricao) {


				//VERIFICA EXISTENCIA

				$verificaExistencia = $this->FaturamentoTaxasServicosModel->verificaExistencia($codAtendimento, $prescricao->dataInicio, $prescricao->dataEncerramento, $prescricao->codLocalAtendimento);


				if ($verificaExistencia == NULL) {

					//DIARIA PACIENTE
					$fields['codAtendimento'] = $codAtendimento;
					$fields['codFatura'] = $codFatura;
					$fields['dataInicio'] = $prescricao->dataInicio;
					$fields['dataEncerramento'] = $prescricao->dataEncerramento;
					$fields['codTaxaServico'] = $prescricao->codTaxaServicoPaciente;
					$fields['quantidade'] = $prescricao->qtdDiarias;
					$fields['valor'] = $prescricao->valorPaciente;
					$fields['codLocalAtendimento'] = $prescricao->codLocalAtendimento;
					$fields['codAutor'] = session()->codPessoa;
					$fields['codStatus'] = 8; // 8 = FATURADO
					$fields['dataCriacao'] = date('Y-m-d H:i');
					$fields['dataAtualizacao'] = date('Y-m-d H:i');




					if ($this->FaturamentoTaxasServicosModel->insert($fields)) {
					}


					//DIARIA ACOMPANHANTE
					$fields['codAtendimento'] = $codAtendimento;
					$fields['codFatura'] = $codFatura;
					$fields['dataInicio'] = $prescricao->dataInicio;
					$fields['dataEncerramento'] = $prescricao->dataEncerramento;
					$fields['codTaxaServico'] = $prescricao->codTaxaServicoAcompanhante;
					$fields['quantidade'] = $prescricao->qtdDiarias;
					$fields['valor'] = $prescricao->valorAcompanhante;
					$fields['codLocalAtendimento'] = $prescricao->codLocalAtendimento;
					$fields['codAutor'] = session()->codPessoa;
					$fields['codStatus'] = 8; // 8 = FATURADO
					$fields['dataCriacao'] = date('Y-m-d H:i');
					$fields['dataAtualizacao'] = date('Y-m-d H:i');




					if ($this->FaturamentoTaxasServicosModel->insert($fields)) {
					}

					$totalGeralDiarias = $totalGeralDiarias + $prescricao->qtdDiarias;
				}
			}

			$response['success'] = true;
			$response['quantidade'] = $totalGeralDiarias;
			$response['messages'] = 'Existem ' . $totalGeralDiarias . ' Taxas e Serviços';
		} else {
			$response['success'] = true;
			$response['quantidade'] = 0;
			$response['messages'] = 'Existem 0 Taxas e Serviços';
		}

		*/

		$response['success'] = true;
		$response['quantidade'] = 0;
		$response['messages'] = 'Lançamentos devem ser manuais';
		return $this->response->setJSON($response);
	}



	public function buscaMateriaisMedicos($codAtendimento = NULL)
	{

		if ($codAtendimento !== NULL) {
			$codAtendimento = $codAtendimento;
		} else {
			if ($this->request->getPost('codAtendimento') !== NULL) {
				$codAtendimento = $this->request->getPost('codAtendimento');
			} else {
				$codAtendimento = NULL;

				$response['success'] = true;
				$response['quantidade'] = 0;
				$response['messages'] = 'Existem 0 itens de Materiais';
				return $this->response->setJSON($response);
			}
		}


		$codFatura = $this->request->getPost('codFatura');
		$ccusto = $this->request->getPost('ccusto');
		$dataInicio = $this->request->getPost('dataInicio');
		$dataEncerramento = $this->request->getPost('dataEncerramento');

		$materiais = $this->FaturamentoMateriaisModel->materiais($codAtendimento, $ccusto, $dataInicio, $dataEncerramento);


		$qtdeMateriais = 0;


		foreach ($materiais as $material) {
			$fields = array();

			//VERIFICA SE JA FATURADO
			$verificaExistenciaMaterialFaturado = $this->FaturamentoMateriaisModel->verificaExistenciaMaterialFaturado($codAtendimento, $material->codPrescricaoMaterial);



			$fields['quantidade'] = $material->qtde;

			//SE LIBERADO
			if ($verificaExistenciaMaterialFaturado->codStatus == 3) {
				$fields['quantidade'] = $material->totalLiberado;
			}
			//SE ENTREGUE
			if ($verificaExistenciaMaterialFaturado->codStatus == 4) {
				$fields['quantidade'] = $material->totalEntregue;
			}
			//SE EXECUTADO
			if ($verificaExistenciaMaterialFaturado->codStatus == 5) {
				$fields['quantidade'] = $material->totalExecutado;
			}



			if ($verificaExistenciaMaterialFaturado == NULL) {

				$fields['codAtendimento'] = $codAtendimento;
				$fields['codFatura'] = $codFatura;
				$fields['codPrescricaoMaterial'] = $material->codPrescricaoMaterial;
				$fields['codMaterial'] = $material->codMaterial;
				$fields['autorPrescricao'] = $material->codAutorPrescricao;
				$fields['dataPrescricao'] = $material->dataCriacaoPrescricaoMaterial;
				$fields['valor'] = $material->valor;
				$fields['codAutor'] = session()->codPessoa;
				$fields['codStatus'] = $material->codStatusMaterial;
				$fields['codLocalAtendimento'] = $material->codLocalAtendimento;
				$fields['dataCriacao'] = date('Y-m-d H:i');
				$fields['dataAtualizacao'] =  date('Y-m-d H:i');

				if ($this->FaturamentoMateriaisModel->insert($fields)) {
					$qtdeMateriais = $qtdeMateriais + $material->qtde;
				}
			} else {
				if ($fields['codStatus'] !== -1 and $verificaExistenciaMaterialFaturado->codStatus < 8 and $verificaExistenciaMaterialFaturado->codStatus >= 0) {
					$fields['codStatus'] = $material->codStatusMaterial;
					$fields['valor'] = $material->valor;

					if ($verificaExistenciaMaterialFaturado->codFaturamentoMaterial != NULL and $verificaExistenciaMaterialFaturado->codFaturamentoMaterial != "" and $verificaExistenciaMaterialFaturado->codFaturamentoMaterial != " ") {
						$this->FaturamentoMateriaisModel->update($verificaExistenciaMaterialFaturado->codFaturamentoMaterial, $fields);
					}
				}
			}
		}

		$response['success'] = true;
		$response['quantidade'] = $qtdeMateriais;
		$response['messages'] = 'Existem ' .  $qtdeMateriais . ' itens de Materiais Médicos';


		return $this->response->setJSON($response);
	}




	public function buscaProcedimentos($codAtendimento = NULL)
	{

		sleep(1);


		if ($codAtendimento !== NULL) {
			$codAtendimento = $codAtendimento;
		} else {
			if ($this->request->getPost('codAtendimento') !== NULL) {
				$codAtendimento = $this->request->getPost('codAtendimento');
			} else {
				$codAtendimento = NULL;

				$response['success'] = true;
				$response['quantidade'] = 0;
				$response['messages'] = 'Existem 0 itens de Procedimentos';
				return $this->response->setJSON($response);
			}
		}


		$codFatura = $this->request->getPost('codFatura');
		$ccusto = $this->request->getPost('ccusto');
		$dataInicio = $this->request->getPost('dataInicio');
		$dataEncerramento = $this->request->getPost('dataEncerramento');



		$procedimentos = $this->FaturamentoProcedimentosModel->procedimentos($codAtendimento, $ccusto, $dataInicio, $dataEncerramento);


		$qtdeProcedimentos = 0;


		foreach ($procedimentos as $procedimento) {
			$fields = array();

			//VERIFICA SE JA FATURADO
			$verificaExistenciaProcedimentoFaturado = $this->FaturamentoProcedimentosModel->verificaExistenciaProcedimentoFaturado($codAtendimento, $procedimento->codPrescricaoProcedimento);


			$fields['quantidade'] = $procedimento->qtde;

			if ($verificaExistenciaProcedimentoFaturado == NULL) {

				$fields['codAtendimento'] = $codAtendimento;
				$fields['codFatura'] = $codFatura;
				$fields['codPrescricaoProcedimento'] = $procedimento->codPrescricaoProcedimento;
				$fields['codProcedimento'] = $procedimento->codProcedimento;
				$fields['autorPrescricao'] = $procedimento->codAutorPrescricao;
				$fields['dataPrescricao'] = $procedimento->dataCriacaoPrescricaoProcedimento;
				$fields['valor'] = $procedimento->valor;
				$fields['codAutor'] = session()->codPessoa;
				$fields['codStatus'] = $procedimento->codStatusProcedimento;
				$fields['codLocalAtendimento'] = $procedimento->codLocalAtendimento;
				$fields['dataCriacao'] = date('Y-m-d H:i');
				$fields['dataAtualizacao'] =  date('Y-m-d H:i');


				if ($this->FaturamentoProcedimentosModel->insert($fields)) {
					$qtdeProcedimentos = $qtdeProcedimentos + $procedimento->qtde;
				}
			} else {
				if ($fields['codStatus'] !== -1 and $verificaExistenciaProcedimentoFaturado->codStatus < 8 and $verificaExistenciaProcedimentoFaturado->codStatus >= 0) {
					$fields['codStatus'] = $procedimento->codStatusProcedimento;
					$fields['valor'] = $procedimento->valor;
					if ($verificaExistenciaProcedimentoFaturado->codFaturamentoProcedimento !== NULL and $verificaExistenciaProcedimentoFaturado->codFaturamentoProcedimento !== "" and $verificaExistenciaProcedimentoFaturado->codFaturamentoProcedimento !== " ") {
						$this->FaturamentoProcedimentosModel->update($verificaExistenciaProcedimentoFaturado->codFaturamentoProcedimento, $fields);
					}
				}
			}
		}

		$response['success'] = true;
		$response['quantidade'] = $qtdeProcedimentos;
		$response['messages'] = 'Existem ' .  $qtdeProcedimentos . ' itens de Procedimentos';


		return $this->response->setJSON($response);
	}




	public function buscaMedicamentos($codAtendimento = NULL)
	{


		sleep(1);


		if ($codAtendimento !== NULL) {
			$codAtendimento = $codAtendimento;
		} else {
			if ($this->request->getPost('codAtendimento') !== NULL) {
				$codAtendimento = $this->request->getPost('codAtendimento');
			} else {
				$codAtendimento = NULL;

				$response['success'] = true;
				$response['quantidade'] = 0;
				$response['messages'] = 'Existem 0 itens de Medicamentos';
				return $this->response->setJSON($response);
			}
		}
		$codFatura = $this->request->getPost('codFatura');
		$ccusto = $this->request->getPost('ccusto');
		$dataInicio = $this->request->getPost('dataInicio');
		$dataEncerramento = $this->request->getPost('dataEncerramento');



		$medicamentos = $this->FaturamentoMedicamentosModel->medicamentos($codAtendimento, $ccusto, $dataInicio, $dataEncerramento);

		$qtdeMedicamentos = 0;


		foreach ($medicamentos as $medicamento) {
			$fields = array();

			//VERIFICA SE JA FATURADO
			$verificaExistenciaMedicamentoFaturado = $this->FaturamentoMedicamentosModel->verificaExistenciaMedicamentoFaturado($codAtendimento, $medicamento->codPrescricaoMedicamento);



			$fields['quantidade'] = $medicamento->qtde;

			//SE LIBERADO
			if ($verificaExistenciaMedicamentoFaturado->codStatus == 3) {
				$fields['quantidade'] = $medicamento->totalLiberado;
			}
			//SE ENTREGUE
			if ($verificaExistenciaMedicamentoFaturado->codStatus == 4) {
				$fields['quantidade'] = $medicamento->totalEntregue;
			}
			//SE EXECUTADO
			if ($verificaExistenciaMedicamentoFaturado->codStatus == 5) {
				$fields['quantidade'] = $medicamento->totalExecutado;
			}

			if ($verificaExistenciaMedicamentoFaturado == NULL) {

				$fields['codAtendimento'] = $codAtendimento;
				$fields['codFatura'] = $codFatura;
				$fields['codPrescricaoMedicamento'] = $medicamento->codPrescricaoMedicamento;
				$fields['codMedicamento'] = $medicamento->codMedicamento;
				$fields['autorPrescricao'] = $medicamento->codAutorPrescricao;
				$fields['dataPrescricao'] = $medicamento->dataCriacaoPrescricaoMedicamento;
				$fields['valor'] = $medicamento->valor;
				$fields['codAutor'] = session()->codPessoa;
				$fields['codStatus'] = $medicamento->codStatusMedicamento;
				$fields['codLocalAtendimento'] = $medicamento->codLocalAtendimento;
				$fields['dataCriacao'] = date('Y-m-d H:i');
				$fields['dataAtualizacao'] =  date('Y-m-d H:i');


				if ($this->FaturamentoMedicamentosModel->insert($fields)) {
					$qtdeMedicamentos = $qtdeMedicamentos + $medicamento->qtde;
				}
			} else {
				if ($fields['codStatus'] !== -1 and $verificaExistenciaMedicamentoFaturado->codStatus < 8 and $verificaExistenciaMedicamentoFaturado->codStatus >= 0) {
					$fields['codStatus'] = $medicamento->codStatusMedicamento;
					$fields['valor'] = $medicamento->valor;


					if ($verificaExistenciaMedicamentoFaturado->codFaturamentoMedicamento !== NULL and $verificaExistenciaMedicamentoFaturado->codFaturamentoMedicamento !== "" and $verificaExistenciaMedicamentoFaturado->codFaturamentoMedicamento !== " ") {
						$this->FaturamentoMedicamentosModel->update($verificaExistenciaMedicamentoFaturado->codFaturamentoMedicamento, $fields);
					}
				}
			}
		}

		$response['success'] = true;
		$response['quantidade'] = $qtdeMedicamentos;
		$response['messages'] = 'Existem ' .  $qtdeMedicamentos . ' itens de Medicamentos';


		return $this->response->setJSON($response);
	}



	public function buscaKits($codAtendimento = NULL)
	{

		sleep(1);


		if ($codAtendimento !== NULL) {
			$codAtendimento = $codAtendimento;
		} else {
			if ($this->request->getPost('codAtendimento') !== NULL) {
				$codAtendimento = $this->request->getPost('codAtendimento');
			} else {
				$codAtendimento = NULL;

				$response['success'] = true;
				$response['quantidade'] = 0;
				$response['messages'] = 'Existem 0 itens de Kits';
				return $this->response->setJSON($response);
			}
		}

		$codFatura = $this->request->getPost('codFatura');
		$ccusto = $this->request->getPost('ccusto');
		$dataInicio = $this->request->getPost('dataInicio');
		$dataEncerramento = $this->request->getPost('dataEncerramento');



		$kits = $this->FaturamentoKitsModel->kits($codAtendimento, $ccusto, $dataInicio, $dataEncerramento);

		$qtdeKits = 0;


		foreach ($kits as $kit) {
			$fields = array();

			//VERIFICA SE JA FATURADO
			$verificaExistenciaKitFaturado = $this->FaturamentoKitsModel->verificaExistenciaKitFaturado($codAtendimento, $kit->codPrescricaoKit);




			if ($verificaExistenciaKitFaturado == NULL) {

				$fields['codAtendimento'] = $codAtendimento;
				$fields['codFatura'] = $codFatura;
				$fields['codPrescricaoKit'] = $kit->codPrescricaoKit;
				$fields['codKit'] = $kit->codKit;
				$fields['autorPrescricao'] = $kit->codAutorPrescricao;
				$fields['dataPrescricao'] = $kit->dataCriacaoPrescricaoKit;
				$fields['quantidade'] = $kit->qtde;
				$fields['valor'] = $kit->valorun;
				$fields['codAutor'] = session()->codPessoa;
				$fields['codStatus'] = $kit->codStatusKit;
				$fields['codLocalAtendimento'] = $kit->codLocalAtendimento;
				$fields['dataCriacao'] = date('Y-m-d H:i');
				$fields['dataAtualizacao'] =  date('Y-m-d H:i');


				if ($this->FaturamentoKitsModel->insert($fields)) {
					$qtdeKits = $qtdeKits + $kit->qtde;
				}
			} else {
				if ($fields['codStatus'] !== -1 and  $verificaExistenciaKitFaturado->codStatus < 8 and $verificaExistenciaKitFaturado->codStatus >= 0) {
					$fields['codStatus'] = $kit->codStatusKit;
					$fields['quantidade'] = $kit->qtde;
					$fields['valor'] = $kit->valorun;

					if ($verificaExistenciaKitFaturado->codFaturamentoKit !== NULL and $verificaExistenciaKitFaturado->codFaturamentoKit !== "" and $verificaExistenciaKitFaturado->codFaturamentoKit !== " ") {
						$this->FaturamentoKitsModel->update($verificaExistenciaKitFaturado->codFaturamentoKit, $fields);
					}
				}
			}
		}

		$response['success'] = true;
		$response['quantidade'] = $qtdeKits;
		$response['messages'] = 'Existem ' .  $qtdeKits . ' itens de Kits';


		return $this->response->setJSON($response);
	}


	public function savarAuditoriaMedicamentos($codAtendimento = NULL)
	{

		$codStatus = "";
		foreach ($this->request->getPost() as $chave => $atributo) {
			$fields = array();

			if (strpos($chave,  'editarQtde') !== false) {
				$codFaturamentoMedicamento = str_replace('editarQtde', '', $chave);


				$quantidade =  $this->request->getPost('editarQtde' . $codFaturamentoMedicamento);
				$fields['quantidade'] = str_replace(",", ".", $quantidade);
				$fields['observacoes'] = $this->request->getPost('editarObservacoes' . $codFaturamentoMedicamento);
				//$fields['valor'] = $this->request->getPost('editarValor' . $codFaturamentoMedicamento);
				//$fields['codStatus'] = 8; //Faturado
				$fields['codAuditor'] = session()->codPessoa;


				if ($codFaturamentoMedicamento !== NULL and $codFaturamentoMedicamento !== "" and $codFaturamentoMedicamento !== " ") {
					if ($this->FaturamentoMedicamentosModel->update($codFaturamentoMedicamento, $fields)) {
					}
				}
			}
		}




		$response['success'] = true;
		$response['quantidade'] = 0;
		$response['messages'] = 'Auditoria realizada para os itens selecionados';
		return $this->response->setJSON($response);
	}


	public function savarPeriodoObservacoesForm()
	{


		$codFatura  = $this->request->getPost('codFatura');
		$fields['dataInicio'] = $this->request->getPost('dataInicio');
		$fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');
		$fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');
		$fields['observacoes'] = $this->request->getPost('observacoes');
		$fields['codAutor'] = session()->codPessoa;

		if ($codFatura !== NULL and $codFatura !== "" and $codFatura !== " ") {
			if ($this->FaturamentoModel->update($codFatura, $fields)) {
			}
		}


		$response['success'] = true;
		$response['messages'] = 'Informações Gravadas com sucesso';
		return $this->response->setJSON($response);
	}

	public function savarAuditoriaProcedimentos($codAtendimento = NULL)
	{

		$codStatus = "";
		foreach ($this->request->getPost() as $chave => $atributo) {
			$fields = array();

			if (strpos($chave,  'editarQtde') !== false) {
				$codFaturamentoProcedimento = str_replace('editarQtde', '', $chave);



				$fields['quantidade'] = $this->request->getPost('editarQtde' . $codFaturamentoProcedimento);
				$fields['observacoes'] = $this->request->getPost('editarObservacoes' . $codFaturamentoProcedimento);
				$fields['dataAtualizacao'] = date('Y-m-d H:i');
				$fields['codAuditor'] = session()->codPessoa;


				if ($codFaturamentoProcedimento !== NULL and $codFaturamentoProcedimento !== "" and $codFaturamentoProcedimento !== " ") {
					if ($this->FaturamentoProcedimentosModel->update($codFaturamentoProcedimento, $fields)) {
					}
				}
			}
		}




		$response['success'] = true;
		$response['quantidade'] = 0;
		$response['messages'] = 'Auditoria realizada para os itens selecionados';
		return $this->response->setJSON($response);
	}

	public function savarAuditoriaMateriais($codAtendimento = NULL)
	{

		$codStatus = "";
		foreach ($this->request->getPost() as $chave => $atributo) {
			$fields = array();

			if (strpos($chave,  'editarQtde') !== false) {
				$codFaturamentoMaterial = str_replace('editarQtde', '', $chave);



				$fields['quantidade'] = $this->request->getPost('editarQtde' . $codFaturamentoMaterial);
				$fields['observacoes'] = $this->request->getPost('editarObservacoes' . $codFaturamentoMaterial);
				$fields['dataAtualizacao'] = date('Y-m-d H:i');
				$fields['codAuditor'] = session()->codPessoa;


				if ($codFaturamentoMaterial !== NULL and $codFaturamentoMaterial !== "" and $codFaturamentoMaterial !== " ") {
					if ($this->FaturamentoMateriaisModel->update($codFaturamentoMaterial, $fields)) {
					}
				}
			}
		}




		$response['success'] = true;
		$response['quantidade'] = 0;
		$response['messages'] = 'Auditoria realizada para os itens selecionados';
		return $this->response->setJSON($response);
	}

	public function savarAuditoriaTaxasServicos($codAtendimento = NULL)
	{

		$codStatus = "";
		foreach ($this->request->getPost() as $chave => $atributo) {
			$fields = array();

			if (strpos($chave,  'editarQtde') !== false) {
				$codFaturamentoTaxasServico = str_replace('editarQtde', '', $chave);



				$fields['quantidade'] = $this->request->getPost('editarQtde' . $codFaturamentoTaxasServico);
				$codStatus = $this->request->getPost('editarStatus' . $codFaturamentoTaxasServico);
				if ($codStatus !== NULL and $codStatus !== "" and $codStatus !== " ") {
					$fields['codStatus'] = $codStatus;
					$fields['codAuditor'] = session()->codPessoa;
				}

				if ($codFaturamentoTaxasServico !== NULL and $codFaturamentoTaxasServico !== "" and $codFaturamentoTaxasServico !== " ") {
					if ($this->FaturamentoTaxasServicosModel->update($codFaturamentoTaxasServico, $fields)) {
					}
				}
			}
		}




		$response['success'] = true;
		$response['quantidade'] = 0;
		$response['messages'] = 'Auditoria realizada para os itens selecionados';
		return $this->response->setJSON($response);
	}


	public function savarAuditoriaKits($codAtendimento = NULL)
	{

		$codStatus = "";
		foreach ($this->request->getPost() as $chave => $atributo) {
			$fields = array();

			if (strpos($chave,  'editarQtde') !== false) {
				$codFaturamentoKit = str_replace('editarQtde', '', $chave);



				$fields['quantidade'] = $this->request->getPost('editarQtde' . $codFaturamentoKit);
				$codStatus = $this->request->getPost('editarStatus' . $codFaturamentoKit);
				if ($codStatus !== NULL and $codStatus !== "" and $codStatus !== " ") {
					$fields['codStatus'] = $codStatus;
					$fields['codAuditor'] = session()->codPessoa;
				}

				if ($codFaturamentoKit !== NULL and $codFaturamentoKit !== "" and $codFaturamentoKit !== " ") {
					if ($this->FaturamentoKitsModel->update($codFaturamentoKit, $fields)) {
					}
				}
			}
		}




		$response['success'] = true;
		$response['quantidade'] = 0;
		$response['messages'] = 'Auditoria realizada para os itens selecionados';
		return $this->response->setJSON($response);
	}

	public function gerarFaturaAgora()
	{

		sleep(3);


		$response['success'] = true;
		$response['messages'] = 'Fatura gerada com sucesso';


		return $this->response->setJSON($response);
	}


	public function faturasAtendimento()
	{
		$response = array();

		$data['data'] = array();

		$codAtendimento = $this->request->getPost('codAtendimento');

		$result = $this->FaturamentoModel->faturasAtendimento($codAtendimento);

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editfaturamento(' . $value->codFatura . ')"><i class="fa fa-edit"></i></button>';
			if ($value->codStatusFatura == 0 or $value->codStatusFatura == -1) {
				$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removefaturamento(' . $value->codFatura . ')"><i class="fa fa-trash"></i>Remover</button>';
			}

			if ($value->codStatusFatura == 1) {
				$ops .= '	<button type="button" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="top" title="Reabrir Fatura"  onclick="reabrirFaturamento(' . $value->codFatura . ')">Reabrir Fatura</button>';
			}
			$ops .= '</div>';

			$descricaoStatus = '<span class="right badge badge-' . $value->corStatusFatura . '">' . $value->descricaoStatusFatura . '</span>';

			$periodo = 'De ' . date('d/m/Y', strtotime($value->dataInicio)) . " à " . date('d/m/Y', strtotime($value->dataEncerramento));
			$data['data'][$key] = array(
				$value->codFatura,
				$value->codAtendimento,
				$value->nomePaciente,
				$value->autor,
				$periodo,
				$descricaoStatus,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->FaturamentoModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editfaturamento(' . $value->codFatura . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removefaturamento(' . $value->codFatura . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$value->codFatura,
				$value->codAtendimento,
				$value->codPaciente,
				$value->codCcusto,
				$value->dataCriacao,
				$value->dataAtualizacao,
				$value->codAutor,
				$value->dataInicio,
				$value->dataEncerramento,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codFatura');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->FaturamentoModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function dadosFatura()
	{
		$response = array();

		$id = $this->request->getPost('codFatura');

		if ($this->validation->check($id, 'required|numeric')) {

			$fatura = $this->FaturamentoModel->dadosFatura($id);

			$statusFatura = '<span class="right badge badge-' . $fatura->corStatusFatura . '">' . $fatura->descricaoStatusFatura . '</span>';

			if ($fatura->dataInicio !== NULL) {
				$dataInicio = date('d/m/Y', strtotime($fatura->dataInicio));
			} else {
			}
			if ($fatura->dataEncerramento !== NULL) {
				$dataFim = date('d/m/Y', strtotime($fatura->dataEncerramento));
			}

			if ($dataInicio !== NULL and $dataFim !== NULL) {
				$periodo = ' De ' . $dataInicio . ' à ' . $dataFim;
			} else {
				$periodo = 'À definir';
			}

			$periodo = $this->FaturamentoModel->periodo($id);
			$periodoFull = 'De ' . date('d', strtotime($periodo->dataMinima)) . ' de ' . nomeMesPorExtenso(date('m', strtotime($periodo->dataMinima))) . ' até ' . date('d', strtotime($periodo->dataMaxima)) . ' de ' . nomeMesPorExtenso(date('m', strtotime($periodo->dataMaxima))) . ' de ' . date('Y', strtotime($periodo->dataMaxima)) . '.';




			if ($periodo->dataMinima = NULL or $periodo->dataMaxima == NULL) {
				$periodoFull = '';
			}

			$txtReabertura = "";
			if ($fatura->dataReabertura !== NULL) {
				$txtReabertura = '
				
				
				<div class="col-md-8">
					<div class="form-group">
						<label> Dados Reabertura: </label>
						<span style="color:red">Reaberto em ' . date("d/m/Y H:i", strtotime($fatura->dataReabertura)) . ' | por ' . $fatura->autorReabertura . ' | <b>Motivo</b>: "' . $fatura->motivoReabertura . '".</span>
					</div>
				</div>
					
					
					';
			}


			$anoFatura = date('Y', strtotime($fatura->dataCriacao));


			$html = "";

			$html .= '
				
				<div style=""margin-bottom:0p class="row">				
					<div class="col-md-2">
						<div class="form-group">
							<label> Fatura: </label>
							' . $id . '/' . $anoFatura . '
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label> Atendimento: </label>
							' . $fatura->codAtendimento . '
						</div>
					</div>
					<div class="col-md-5">
						<div class="form-group">
							<label> Paciente: </label>
							' . $fatura->nomePaciente . '
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label> Status da Fatura: </label>
							' . $statusFatura  . '
						</div>
					</div>
					' . $txtReabertura . '					
									
				</div>
				
				<div style="margin-top:0pxs" class="row">							
					<div style="font-weight:bold" class="col-md-3">
						<div class="form-group">
							<label> LOCAL: </label>
							' . $fatura->descricaoDepartamento . ' (' . $fatura->descricaoLocalAtendimento . ')
						</div>
					</div>
					<div style="font-weight:bold" class="col-md-3">
						<div class="form-group">
							<label> DATA NASCIMENTO: </label>
							' . date('d/m/Y', strtotime($fatura->dataNascimento)) .' ('.$fatura->idade. ')
						</div>
					</div>
					<div style="font-weight:bold" class="col-md-2">
						<div class="form-group">
							<label> Nº PLANO: </label>
							' . $fatura->codPlano. '
						</div>
					</div>
					<div style="font-weight:bold" class="col-md-2">
						<div class="form-group">
							<label> ADMISSAO: </label>
							' . date('d/m/Y', strtotime($fatura->dataAdmissao)) . '
						</div>
					</div>
				</div>
				
				';


			$response['success'] = true;
			$response['codFatura'] = $id;
			$response['ccusto'] = $fatura->ccusto;
			$response['codAtendimento'] = $fatura->codAtendimento;
			$response['dataInicio'] = $fatura->dataInicio;
			$response['dataEncerramento'] = $fatura->dataEncerramento;
			$response['observacoes'] = $fatura->observacoes;
			$response['codStatusFatura'] = $fatura->codStatusFatura;
			$response['html'] = $html;
			return $this->response->setJSON($response);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function fecharFatura()
	{
		$response = array();

		$codFatura = $this->request->getPost('codFatura');


		$fields['codFatura'] = $codFatura;
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['codAutor'] = session()->codPessoa;
		$fields['codStatusFatura'] = 1;


		$this->validation->setRules([
			'codFatura' => ['label' => 'codFatura', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($codFatura = $this->FaturamentoModel->update($fields['codFatura'], $fields)) {

				//ATUALIZA STATUS ITENS AUDITADOS
				$this->FaturamentoModel->atualizaItens($fields['codFatura']);

				$response['success'] = true;
				$response['messages'] = 'Fatura Encerrada com suceso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na inserção!';
			}
		}

		return $this->response->setJSON($response);
	}


	public function reabrirFaturamento()
	{
		$response = array();

		$codFatura = $this->request->getPost('codFatura');
		$motivo = $this->request->getPost('motivo');


		$fields['codFatura'] = $codFatura;
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['codStatusFatura'] = -1;
		$fields['motivoReabertura'] = $motivo;
		$fields['reabertoPor'] = session()->codPessoa;
		$fields['dataReabertura'] = date('Y-m-d H:i');


		$this->validation->setRules([
			'codFatura' => ['label' => 'codFatura', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($codFatura = $this->FaturamentoModel->update($fields['codFatura'], $fields)) {

				//ATUALIZA STATUS ITENS AUDITADOS
				$this->FaturamentoModel->atualizaItens($fields['codFatura']);

				$response['success'] = true;
				$response['messages'] = 'Fatura Encerrada com suceso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na inserção!';
			}
		}

		return $this->response->setJSON($response);
	}
	public function encerrarConta()
	{
		$response = array();

		$codAtendimento = $this->request->getPost('codAtendimento');


		$fields['codAtendimento'] = $codAtendimento;
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['codAutor'] = session()->codPessoa;
		$fields['codStatusConta'] = 2;


		$this->validation->setRules([
			'codAtendimento' => ['label' => 'codAtendimento', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($codFatura = $this->AtendimentosModel->update($fields['codAtendimento'], $fields)) {

				$response['success'] = true;
				$response['messages'] = 'Conta Encerrada com suceso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na inserção!';
			}
		}

		return $this->response->setJSON($response);
	}



	public function reabrirConta()
	{
		$response = array();

		$codAtendimento = $this->request->getPost('codAtendimento');


		$fields['codAtendimento'] = $codAtendimento;
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['codAutor'] = session()->codPessoa;
		$fields['codStatusConta'] = 1;


		$this->validation->setRules([
			'codAtendimento' => ['label' => 'codAtendimento', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($codFatura = $this->AtendimentosModel->update($fields['codAtendimento'], $fields)) {

				$response['success'] = true;
				$response['messages'] = 'Conta Reaberta com suceso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na inserção!';
			}
		}

		return $this->response->setJSON($response);
	}

	public function dadosImpressaoFatura()
	{
		$response = array();

		$codFatura = $this->request->getPost('codFatura');

		if ($this->validation->check($codFatura, 'required|numeric')) {



			$fatura = $this->FaturamentoModel->dadosFatura($codFatura);
			/*

			if ($fatura->codStatusFatura == 0) {

				$response['success'] = false;
				$response['messages'] = 'Só é permitido imprimir faturas completamente auditadas e fechadas. Conclua a auditoria e feche esta fatura!';
				return $this->response->setJSON($response);
			}

			*/
			$totaisFatura = $this->FaturamentoModel->totaisFatura($codFatura);



			$totalGeral = round($totaisFatura['totalMedicamentos'] + $totaisFatura['totalMateriais'] + $totaisFatura['totalKits'] + $totaisFatura['totalProcedimentos'] + $totaisFatura['totalTaxasServicos'], 2);

			//$periodo = $this->FaturamentoModel->periodo($codFatura);
			//$periodoFull = 'De ' . date('d', strtotime($periodo->dataMinima)) . ' de ' . nomeMesPorExtenso(date('m', strtotime($periodo->dataMinima))) . ' até ' . date('d', strtotime($periodo->dataMaxima)) . ' de ' . nomeMesPorExtenso(date('m', strtotime($periodo->dataMaxima))) . ' de ' . date('Y', strtotime($periodo->dataMaxima)) . '.';
			$periodoFull = 'De ' . date('d', strtotime($fatura->dataInicio)) . ' de ' . nomeMesPorExtenso(date('m', strtotime($fatura->dataInicio))) . ' até ' . date('d', strtotime($fatura->dataEncerramento)) . ' de ' . nomeMesPorExtenso(date('m', strtotime($fatura->dataEncerramento))) . ' de ' . date('Y', strtotime($fatura->dataEncerramento)) . '.';


			if ($fatura->dataInicio !== NULL) {
				$dataInicio = date('d/m/Y', strtotime($fatura->dataInicio));
			} else {
			}
			if ($fatura->dataEncerramento !== NULL) {
				$dataFim = date('d/m/Y', strtotime($fatura->dataEncerramento));
			}

			if ($dataInicio !== NULL and $dataFim !== NULL) {
				$periodo = ' De ' . $dataInicio . ' à ' . $dataFim;
			} else {
				$periodo = 'À definir';
			}










			//DETALHAMENTO MEDICAMENTOS

			$datalhesMedicamentos = $this->FaturamentoModel->datalhesMedicamentos($codFatura);

			$tabelaMedidamentos = "
			<table width='100%'>
			<th>Nr</th>
			<th>CCusto</th>
			<th>Data</th>
			<th>Medicamento</th>
			<th>Qtde</th>
			<th>Valor Un</th>
			<th>SubTotal</th>
			";

			$x = 0;
			foreach ($datalhesMedicamentos as $medicamento) {
				$x++;
				$tabelaMedidamentos .= '
				<tr>
					<td>' . $x . '</td>
					<td>' . $medicamento->descricaoDepartamento . '</td>
					<td>' . date('d/m/Y', strtotime($medicamento->dataPrescricao)) . '</td>
					<td>' . $medicamento->descricaoItem . '</td>
					<td>' . $medicamento->quantidade . '</td>
					<td>' . $medicamento->valor . '</td>
					<td>R$ ' . round($medicamento->quantidade * $medicamento->valor, 2) . '</td>
				</tr>
				';
			}

			$tabelaMedidamentos .= "</table>";



			//DETALHAMENTO MATERIAIS

			$datalhesMedicamentos = $this->FaturamentoModel->datalhesMateriais($codFatura);

			$tabelaMateriais = "
			<table width='100%'>
			<th>Nr</th>
			<th>CCusto</th>
			<th>Data</th>
			<th>Material</th>
			<th>Qtde</th>
			<th>Valor Un</th>
			<th>SubTotal</th>
			";

			$x = 0;
			foreach ($datalhesMedicamentos as $material) {
				$x++;
				$tabelaMateriais .= '
				<tr>
					<td>' . $x . '</td>
					<td>' . $material->descricaoDepartamento . '</td>
					<td>' . date('d/m/Y', strtotime($material->dataPrescricao)) . '</td>
					<td>' . $material->descricaoItem . '</td>
					<td>' . $material->quantidade . '</td>
					<td>' . $material->valor . '</td>
					<td>R$ ' . round($material->quantidade * $material->valor, 2) . '</td>
				</tr>
				';
			}

			$tabelaMateriais .= "</table>";



			//DETALHAMENTO KITS

			$datalhesKits = $this->FaturamentoModel->datalhesKits($codFatura);

			$tabelaKits = "
			<table width='100%'>
			<th>Nr</th>
			<th>CCusto</th>
			<th>Data</th>
			<th>Kit</th>
			<th>Qtde</th>
			<th>Valor Un</th>
			<th>SubTotal</th>
			";

			$x = 0;
			foreach ($datalhesKits as $kit) {
				$x++;
				$tabelaKits .= '
				<tr>
					<td>' . $x . '</td>
					<td>' . $kit->descricaoDepartamento . '</td>
					<td>' . date('d/m/Y', strtotime($kit->dataPrescricao)) . '</td>
					<td>' . $kit->descricaoKit . '</td>
					<td>' . $kit->quantidade . '</td>
					<td>' . $kit->valor . '</td>
					<td>R$ ' . round($kit->quantidade * $kit->valor, 2) . '</td>
				</tr>
				';
			}

			$tabelaKits .= "</table>";




			//DETALHAMENTO TAXAS E SERVIÇOS

			$datalhesTaxasServicos = $this->FaturamentoModel->datalhesTaxasServicos($codFatura);

			$tabelaTaxasServicos = "
			<table width='100%'>
			<th>Nr</th>
			<th>CCusto</th>
			<th>Período</th>
			<th>Código</th>
			<th>DESCRIÇÃO</th>
			<th>Qtde</th>
			<th>Valor Un</th>
			<th>SubTotal</th>
			";

			$x = 0;
			foreach ($datalhesTaxasServicos as $taxaServico) {
				$x++;
				$tabelaTaxasServicos .= '
				<tr>
					<td>' . $x . '</td>
					<td>' . $taxaServico->descricaoDepartamento . '</td>
					<td> De ' . date('d/m/Y', strtotime($taxaServico->dataInicio)) . ' à ' . date('d/m/Y', strtotime($taxaServico->dataEncerramento)) . '</td>
					<td>' . $taxaServico->referencia . '</td>
					<td>' . $taxaServico->descricao . '</td>
					<td>' . $taxaServico->quantidade . '</td>
					<td>' . $taxaServico->valor . '</td>
					<td>R$ ' . round($taxaServico->quantidade * $taxaServico->valor, 2) . '</td>
				</tr>
				';
			}

			$tabelaTaxasServicos .= "</table>";





			//DETALHAMENTO PROCEDIMENTOS

			$datalhesProcedimentos = $this->FaturamentoModel->datalhesProcedimentos($codFatura);

			$tabelaProcedimentos = "
			<table width='100%'>
			<th>Nr</th>
			<th>CCusto</th>
			<th>Data</th>
			<th>Código</th>
			<th>Procedimento</th>
			<th>Qtde</th>
			<th>Valor Un</th>
			<th>SubTotal</th>
			";

			$x = 0;
			foreach ($datalhesProcedimentos as $procedimento) {
				$x++;
				$tabelaProcedimentos .= '
				<tr>
					<td>' . $x . '</td>
					<td>' . $procedimento->descricaoDepartamento . '</td>
					<td>' . date('d/m/Y', strtotime($procedimento->dataPrescricao)) . '</td>
					<td>' . $procedimento->referencia . '</td>
					<td>' . $procedimento->descricao . '</td>
					<td>' . $procedimento->quantidade . '</td>
					<td>' . $procedimento->valor . '</td>
					<td>R$ ' . round($procedimento->quantidade * $procedimento->valor, 2) . '</td>
				</tr>
				';
			}

			$tabelaProcedimentos .= "</table>";





			$html = "";


			if ($fatura->codStatusFatura == 0) {

				$html .= '
				
				<div  style="color:red;font-size:30px;font-weight: bold;margin-bottom:10px" class="text-center" class="row">
				RASCUNHO
				</div>
				<div  style="color:red;font-size:14px;font-weight: bold;margin-bottom:10px" class="text-center" class="row">
				Só é permitido imprimir faturas completamente auditadas e fechadas. Conclua a auditoria e feche esta fatura!
				</div>
				
				';
			}

			$nrFatura = $fatura->codFatura . '/' . date('Y');

			$html .= '
				
				<div  style="font-size:20px;font-weight: bold;margin-bottom:10px" class="text-center" class="row">
				DETALHAMENTO DA FATURA nº' . $nrFatura . '
				</div>
				<div style="font-size: 12px" class="row">				
					<div class="col-md-6 border border-dark">
						<div class="row">
							<div style="font-weight: bold; background:#dee2e6" class="col-md-12  border border-dark text-center">
								DADOS DO PACIENTE
							</div>						
						</div>
						<div class="row">
							<div class="col-md-12  border border-dark">
								Paciente:' . $fatura->nomePaciente . ' (' . $fatura->siglaTipoBeneficiario . ')
							</div>						
						</div>
						<div class="row">
							<div class="col-md-6 border border-dark">
							Cod Beneficiário:' . $fatura->codPlano . '
							</div>
							<div class="col-md-6 border border-dark">
							Prontuário:' . $fatura->codProntuario . '
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-6 border border-dark">
							Posto/Grad:' . $fatura->cargoPaciente . '
							</div>
							<div class="col-md-6 border border-dark">
							CPF:' . $fatura->cpf . '
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 border border-dark">
								Sexo:' . $fatura->sexo . '
							</div>
							<div class="col-md-6 border border-dark">
								Dt Nasc:' . date('d/m/Y', strtotime($fatura->dataNascimento)) . ' - ' . $fatura->idade . ' Anos
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 border border-dark">
								OM:' . $fatura->siglaOm . '
							</div>
							<div class="col-md-6 border border-dark">
							Validade:' . date('d/m/Y', strtotime($fatura->validade)) . '
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 border border-dark">
								Info Complementares: ' . $fatura->observacoes . '
							</div>
						</div>
					</div>


					<div class="col-md-6 border border-dark">

					<div class="row">
							<div style="font-weight: bold; background:#dee2e6" class="col-md-12  border border-dark text-center">
								RESUMO DA FATURA
							</div>						
						</div>
						<div class="row">
							<div class="col-md-6  border border-dark">
								ITEM
							</div>	
							<div class="col-md-6  border border-dark">
								VALOR FINAL
							</div>						
						</div>
						<div class="row">
							<div class="col-md-6 border border-dark">
								MEDICAMENTOS
							</div>
							<div class="col-md-6 border border-dark">
								R$ ' . round($totaisFatura['totalMedicamentos'], 2) . '
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 border border-dark">
								MATERIAIS MÉDICOS
							</div>
							<div class="col-md-6 border border-dark">
							R$ ' . round($totaisFatura['totalMateriais'], 2) . '
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 border border-dark">
								PROCEDIMENTOS
							</div>
							<div class="col-md-6 border border-dark">
							R$ ' . round($totaisFatura['totalProcedimentos'], 2) . '
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 border border-dark">
								TAXAS DE USO E SERVIÇOS
							</div>
							<div class="col-md-6 border border-dark">
							R$ ' . $totaisFatura['totalTaxasServicos'] . '
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 border border-dark">
								KITS
							</div>
							<div class="col-md-6 border border-dark">
							R$ ' . round($totaisFatura['totalKits'], 2) . '
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 border border-dark">
								TOTAL DA FATURA
							</div>
							<div class="col-md-6 border border-dark">
								R$ ' . round($totalGeral, 2) . '
							</div>
						</div>
					</div>
									
				</div>

				<div style="font-size: 12px;margin-top:10px" class="row">		

					<div style="font-weight: bold; background:#dee2e6" class="col-md-12  border border-dark text-center">
						DADOS DA INTERNAÇÃO
					</div>
					
					<div class="col-md-12  border border-dark">

						<div class="row">
							<div class="col-md-4  border border-dark">
								Nº ATENDIMENTO:' . $fatura->codAtendimento . '/' . date('Y', strtotime($fatura->dataInicioAtendimento)) . '
							</div>
							<div class="col-md-4  border border-dark">
								CLÍNIICA: ' . $fatura->descricaoDepartamento . '
							</div>
							<div class="col-md-4  border border-dark">
								LEITO: ' . $fatura->descricaoLocalAtendimento . '
							</div>
						</div>

						<div class="row">
							<div class="col-md-12  border border-dark">
							PERÍODO DAS DESPESAS: ' . $periodoFull . '
							</div>
							
						</div>

						<div class="row">
							<div class="col-md-12  border border-dark">
							OBSERVAÇÕES DA FATURA:
							</div>
							
						</div>


					</div>




				</div>


				<!-- TABELA DE PROCEDIMENTOS --!>

				<div style="font-size: 12px;margin-top:10px" class="row">		

					<div style="font-weight: bold; background:#dee2e6" class="col-md-12  border border-dark text-center">
						PROCEDIMENTOS
					</div>
				
					<div class="col-md-12  border border-dark">

						' . $tabelaProcedimentos . '

					</div>
					<div style="font-weight:bold" class="col-md-12  border border-dark">

						TOTAL PROCEDIMENTOS: 	R$ ' . round($totaisFatura['totalProcedimentos'], 2) . '

					</div>
				</div>

				<!-- TABELA DE TAXAS E SERVIÇOS --!>

				<div style="font-size: 12px;margin-top:10px" class="row">		

					<div style="font-weight: bold; background:#dee2e6" class="col-md-12  border border-dark text-center">
						TAXAS E SERVIÇOS
					</div>
				
					<div class="col-md-12  border border-dark">

						' . $tabelaTaxasServicos . '

					</div>
					<div style="font-weight:bold" class="col-md-12  border border-dark">

						TOTAL TAXAS E SERVIÇOS: 	R$ ' . round($totaisFatura['totalTaxasServicos'], 2) . '

					</div>
				</div>

				<!-- TABELA DE MEDICAMENTOS --!>

				<div style="font-size: 12px;margin-top:10px" class="row">		

					<div style="font-weight: bold; background:#dee2e6" class="col-md-12  border border-dark text-center">
						MEDICAMENTOS
					</div>
				
					<div class="col-md-12  border border-dark">

						' . $tabelaMedidamentos . '

					</div>
					<div style="font-weight:bold" class="col-md-12  border border-dark">

						TOTAL MEDICAMENTOS: 	R$ ' . round($totaisFatura['totalMedicamentos'], 2) . '

					</div>
				</div>


				

				<!-- TABELA DE MATERIAIS --!>

				<div style="font-size: 12px;margin-top:10px" class="row">		

					<div style="font-weight: bold; background:#dee2e6" class="col-md-12  border border-dark text-center">
						MATERIAIS MÉDICOS
					</div>
				
					<div class="col-md-12  border border-dark">

						' . $tabelaMateriais . '

					</div>
					<div style="font-weight:bold" class="col-md-12  border border-dark">

						TOTAL MATERIAIS: 	R$ ' . round($totaisFatura['totalMateriais'], 2) . '

					</div>
				</div>



								

				<!-- TABELA DE KITS --!>

				<div style="font-size: 12px;margin-top:10px" class="row">		

					<div style="font-weight: bold; background:#dee2e6" class="col-md-12  border border-dark text-center">
						KITS CONSUMIDOS
					</div>
				
					<div class="col-md-12  border border-dark">

						' . $tabelaKits . '

					</div>
					<div style="font-weight:bold" class="col-md-12  border border-dark">

						TOTAL KITS: 	R$ ' . round($totaisFatura['totalKits'], 2) . '

					</div>
				</div>




				<!-- ASSINATURAS --!>

				<div style="font-size: 12px;margin-top:10px" class="row">		

				
					<div class="col-md-12 ">

						Documento Impresso por ' . session()->nomeExibicao . ' (CPF:' . session()->cpf . ')' . ', em ' . session()->cidade . '-' . session()->uf . ', ' . date('d') . ' de ' . nomeMesPorExtenso(date('m')) . ' de ' . date('Y') . '.

					</div>


				</div>

				<div style="font-size: 12px;margin-top:10px" class="row">						
					<div class="col-md-3  border border-dark">
						Conferido Por:
					</div>
					<div class="col-md-3  border border-dark">
						Conferido Por:
					</div>
					<div class="col-md-3  border border-dark">
						Conferido Por:
					</div>
					<div class="col-md-3  border border-dark">
						Auditado Por:
					</div>
				</div>




				
				';


			$response['success'] = true;
			$response['codFatura'] = $codFatura;
			$response['codAtendimento'] = $fatura->codAtendimento;
			$response['html'] = $html;
			return $this->response->setJSON($response);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function dadosImpressaoConsolidado()
	{
		$response = array();

		$codFatura = $this->request->getPost('codFatura');

		if ($this->validation->check($codFatura, 'required|numeric')) {



			$fatura = $this->FaturamentoModel->dadosFatura($codFatura);
			/*

			if ($fatura->codStatusFatura == 0) {

				$response['success'] = false;
				$response['messages'] = 'Só é permitido imprimir faturas completamente auditadas e fechadas. Conclua a auditoria e feche esta fatura!';
				return $this->response->setJSON($response);
			}

			*/
			$totaisFatura = $this->FaturamentoModel->totaisFatura($codFatura);



			$totalGeral = round($totaisFatura['totalMedicamentos'] + $totaisFatura['totalMateriais'] + $totaisFatura['totalKits'] + $totaisFatura['totalProcedimentos'] + $totaisFatura['totalTaxasServicos'], 2);

			//$periodo = $this->FaturamentoModel->periodo($codFatura);
			//$periodoFull = 'De ' . date('d', strtotime($periodo->dataMinima)) . ' de ' . nomeMesPorExtenso(date('m', strtotime($periodo->dataMinima))) . ' até ' . date('d', strtotime($periodo->dataMaxima)) . ' de ' . nomeMesPorExtenso(date('m', strtotime($periodo->dataMaxima))) . ' de ' . date('Y', strtotime($periodo->dataMaxima)) . '.';
			$periodoFull = 'De ' . date('d', strtotime($fatura->dataInicio)) . ' de ' . nomeMesPorExtenso(date('m', strtotime($fatura->dataInicio))) . ' até ' . date('d', strtotime($fatura->dataEncerramento)) . ' de ' . nomeMesPorExtenso(date('m', strtotime($fatura->dataEncerramento))) . ' de ' . date('Y', strtotime($fatura->dataEncerramento)) . '.';


			if ($fatura->dataInicio !== NULL) {
				$dataInicio = date('d/m/Y', strtotime($fatura->dataInicio));
			} else {
			}
			if ($fatura->dataEncerramento !== NULL) {
				$dataFim = date('d/m/Y', strtotime($fatura->dataEncerramento));
			}

			if ($dataInicio !== NULL and $dataFim !== NULL) {
				$periodo = ' De ' . $dataInicio . ' à ' . $dataFim;
			} else {
				$periodo = 'À definir';
			}










			//DETALHAMENTO MEDICAMENTOS

			$datalhesMedicamentos = $this->FaturamentoModel->datalhesMedicamentosConsolidados($codFatura);

			$tabelaMedidamentos = "
			<table border=1>
			<th>Nr</th>
			<th>Medicamento</th>
			<th>Qtde</th>
			";

			$x = 0;
			foreach ($datalhesMedicamentos as $medicamento) {
				$x++;
				$tabelaMedidamentos .= '
				<tr>
					<td>' . $x . '</td>
					<td>' . $medicamento->descricaoItem . '</td>
					<td>' . $medicamento->quantidade . '</td>
				</tr>
				';
			}

			$tabelaMedidamentos .= "</table>";



			//DETALHAMENTO MATERIAIS

			$datalhesMedicamentos = $this->FaturamentoModel->datalhesMateriaisConsolidados($codFatura);

			$tabelaMateriais = "
			<table border=1>
			<th>Nr</th>
			<th>Material</th>
			<th>Qtde</th>
			";

			$x = 0;
			foreach ($datalhesMedicamentos as $material) {
				$x++;
				$tabelaMateriais .= '
				<tr>
					<td>' . $x . '</td>
					<td>' . $material->descricaoItem . '</td>
					<td>' . $material->quantidade . '</td>
				</tr>
				';
			}

			$tabelaMateriais .= "</table>";



			$html = "";


			$nrFatura = $fatura->codFatura . '/' . date('Y');

			$html .= '
				
				<div  style="font-size:20px;font-weight: bold;margin-bottom:10px" class="text-center" class="row">
				CONSOLIDADO DA FATURA nº' . $nrFatura . '
				</div>

				<div style="font-size: 12px;margin-top:10px" class="row">		

					<div style="font-weight: bold; background:#dee2e6" class="col-md-12  border border-dark text-center">
						DADOS DA INTERNAÇÃO
					</div>
					
					<div class="col-md-12  border border-dark">

					
					<div class="row">
						<div class="col-md-4  border border-dark">
							Paciente:' . $fatura->nomePaciente . ' (' . $fatura->siglaTipoBeneficiario . ')
						</div>
						<div class="col-md-4  border border-dark">
							Cod Beneficiário:' . $fatura->codPlano . '
						</div>
						<div class="col-md-4  border border-dark">
							Posto/Grad:' . $fatura->cargoPaciente . '					
						</div>
					</div>


						<div class="row">
							<div class="col-md-4  border border-dark">
								Nº ATENDIMENTO:' . $fatura->codAtendimento . '/' . date('Y', strtotime($fatura->dataInicioAtendimento)) . '
							</div>
							<div class="col-md-4  border border-dark">
								CLÍNIICA: ' . $fatura->descricaoDepartamento . '
							</div>
							<div class="col-md-4  border border-dark">
								LEITO: ' . $fatura->descricaoLocalAtendimento . '
							</div>
						</div>

						<div class="row">
							<div class="col-md-12  border border-dark">
							PERÍODO DAS DESPESAS: ' . $periodoFull . '
							</div>
							
						</div>

						<div class="row">
							<div class="col-md-12  border border-dark">
							OBSERVAÇÕES DA FATURA:
							</div>
							
						</div>


					</div>




				</div>




				<!-- TABELA DE MEDICAMENTOS --!>

				<div style="font-size: 12px;margin-top:10px" class="row">		

					<div style="font-weight: bold; background:#dee2e6" class="col-md-12  border border-dark text-center">
						MEDICAMENTOS
					</div>
				
					<div class="col-md-12  border border-dark">

						' . $tabelaMedidamentos . '

					</div>
				</div>


				

				<!-- TABELA DE MATERIAIS --!>

				<div style="font-size: 12px;margin-top:10px" class="row">		

					<div style="font-weight: bold; background:#dee2e6" class="col-md-12  border border-dark text-center">
						MATERIAIS MÉDICOS
					</div>
				
					<div class="col-md-12  border border-dark">

						' . $tabelaMateriais . '

					</div>
				</div>



				
				';


			$response['success'] = true;
			$response['codFatura'] = $codFatura;
			$response['codAtendimento'] = $fatura->codAtendimento;
			$response['html'] = $html;
			return $this->response->setJSON($response);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function gravaCentroCusto()
	{

		$response = array();
		$codFatura = $this->request->getPost('codFatura');
		$ccusto = $this->request->getPost('ccusto');

		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['ccusto'] = json_encode($ccusto);
		if ($this->validation->check($codFatura, 'required|numeric')) {
			if ($codFatura = $this->FaturamentoModel->update($codFatura, $fields)) {
			}
		}


		$response['success'] = true;
		$response['messages'] = 'Centros de Custos definidos';

		return $this->response->setJSON($response);
	}

	public function vefificaExistenciaDespesas()
	{


		$response = array();
		$codAtendimento = $this->request->getPost('codAtendimento');



		//VERIFICA SE CONTA ABERTA
		$statusConta = $this->FaturamentoModel->verificaStatusConta($codAtendimento);

		if ($statusConta !== NULL) {
			if ($statusConta->codStatusConta == 2) {
				$response['contaAberta'] = false;
				$response['messages'] = 'Conta está encerrada. Não é possível criar nova fatura. Se for o caso, reabra a conta para gerar nova fatura';
				return $this->response->setJSON($response);
			}
		}




		//VERIFICA SE EXITEM DESPESAS PARA FATURAR

		$totalMateriais = $this->FaturamentoModel->verificaExistenciaMateriaisNaoFaturadas($codAtendimento)->totalNaoLancados;
		$totalProcedimentos = $this->FaturamentoModel->verificaExistenciaProcedimentosNaoFaturadas($codAtendimento)->totalNaoLancados;
		$totalMedicamentos = $this->FaturamentoModel->verificaExistenciaMedicamentosNaoFaturadas($codAtendimento)->totalNaoLancados;
		$totalKits = $this->FaturamentoModel->verificaExistenciaKitsNaoFaturadas($codAtendimento)->totalNaoLancados;






		if (($totalMateriais + $totalProcedimentos + $totalMedicamentos + $totalKits) > 0) {

			$response['success'] = true;
			return $this->response->setJSON($response);
		} else {

			$response['success'] = false;
			return $this->response->setJSON($response);
		}








		/*




				//REMOVER KITS

				if ($this->FaturamentoKitsModel->verificaExistenciaDespesas(NaoFaturadas($atendimento->codPaciente)) {
				}
				



*/
	}



	public function add()
	{


		$response = array();
		$codAtendimento = $this->request->getPost('codAtendimento');



		//VERIFICA SE EXISTE FATURAS EM ABERTO


		$verificaSeFaturaAberta = $this->FaturamentoModel->verificaSeFaturaAberta($codAtendimento);

		if ($verificaSeFaturaAberta->codStatusFatura !== NULL) {

			$response['success'] = false;
			$response['messages'] = 'Não é possível gerar nova fatura enquanto existir uma em aberto. Conclua a auditoria na fatura existente!';
			return $this->response->setJSON($response);
		}


		$atendimento = $this->AtendimentosModel->pegaPorCodigo($codAtendimento);



		$fields['codAtendimento'] = $codAtendimento;
		$fields['codPaciente'] = $atendimento->codPaciente;
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['codAutor'] = session()->codPessoa;
		$fields['dataInicio'] = date('Y-m-d H:i');;
		$fields['dataEncerramento'] = NULL;


		$this->validation->setRules([
			'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
			'dataInicio' => ['label' => 'DataInicio', 'rules' => 'permit_empty|valid_date'],
			'dataEncerramento' => ['label' => 'DataEncerramento', 'rules' => 'permit_empty|valid_date'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($codFatura = $this->FaturamentoModel->insert($fields)) {

				$response['success'] = true;
				$response['codFatura'] = $codFatura;
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

		$fields['codFatura'] = $this->request->getPost('codFatura');
		$fields['codAtendimento'] = $this->request->getPost('codAtendimento');
		$fields['codPaciente'] = $this->request->getPost('codPaciente');
		$fields['dataCriacao'] = $this->request->getPost('dataCriacao');
		$fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');
		$fields['codAutor'] = $this->request->getPost('codAutor');
		$fields['dataInicio'] = $this->request->getPost('dataInicio');
		$fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');


		$this->validation->setRules([
			'codFatura' => ['label' => 'codFatura', 'rules' => 'required|numeric|max_length[11]'],
			'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
			'dataInicio' => ['label' => 'DataInicio', 'rules' => 'permit_empty|valid_date'],
			'dataEncerramento' => ['label' => 'DataEncerramento', 'rules' => 'permit_empty|valid_date'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->FaturamentoModel->update($fields['codFatura'], $fields)) {

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

		$codFatura = $this->request->getPost('codFatura');

		if (!$this->validation->check($codFatura, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->FaturamentoModel->where('codFatura', $codFatura)->delete()) {

				$this->LogsModel->inserirLog('Removeu a fatura nº ' . $codFatura . '', session()->codPessoa);

				$response['success'] = true;
				$response['messages'] = 'Deletado com sucesso';



				//REMOVER TAXAS E SERVIÇOS
				if ($this->FaturamentoTaxasServicosModel->removeFatura($codFatura)) {
				}




				//REMOVER MATERIAIS MÉDICOS

				if ($this->FaturamentoMateriaisModel->removeFatura($codFatura)) {
				}


				//REMOVER PROCEDIMENTOS

				if ($this->FaturamentoProcedimentosModel->removeFatura($codFatura)) {
				}



				//REMOVER MEDICAMENTOS

				if ($this->FaturamentoMedicamentosModel->removeFatura($codFatura)) {
				}



				//REMOVER KITS

				if ($this->FaturamentoKitsModel->removeFatura($codFatura)) {
				}
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na deleção!';
			}
		}

		return $this->response->setJSON($response);
	}



	public function pacienteSelecionado()
	{

		$response = array();
		$codPaciente = $this->request->getPost('codPaciente');

		$result = $this->PacientesModel->pegaPacientePorCodPaciente($codPaciente);


		$response['success'] = true;
		$response['codPaciente'] = $codPaciente;
		$response['nomeCompletoPrec'] = $result->nomeCompleto . ' (Nº PLANO:' . $result->codPlano . ')';
		return $this->response->setJSON($response);
	}



	public function addAtendimentoManual()
	{

		$response = array();



		$fields['codOrganizacao'] = session()->codOrganizacao;
		$fields['codPaciente'] = $this->request->getPost('codPaciente');
		$fields['codLocalAtendimento'] = $this->request->getPost('codLocalAtendimento');
		$fields['codEspecialista'] = session()->codPessoa;
		$fields['codEspecialidade'] = 106;
		$fields['codStatus'] = 2;
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['dataInicio'] = $this->request->getPost('dataInicio');
		$fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');
		$fields['codTipoAtendimento'] = 7;
		$fields['codAutor'] = session()->codPessoa;



		$this->validation->setRules([
			'codOrganizacao' => ['label' => 'CodOrganizacao', 'rules' => 'required|numeric|max_length[11]'],
			'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'required|numeric|max_length[11]'],
			'codLocalAtendimento' => ['label' => 'codLocalAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'codEspecialista' => ['label' => 'CodEspecialista', 'rules' => 'required|numeric|max_length[11]'],
			'codEspecialidade' => ['label' => 'CodEspecialidade', 'rules' => 'required|numeric|max_length[11]'],
			'codStatus' => ['label' => 'Status', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
			'dataInicio' => ['label' => 'DataInicio', 'rules' => 'permit_empty'],
			'dataEncerramento' => ['label' => 'DataEncerramento', 'rules' => 'permit_empty'],
			'codTipoAtendimento' => ['label' => 'CodTipoAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($codAtendimento = $this->AtendimentosModel->insert($fields)) {

				$response['success'] = true;
				$response['codAtendimento'] = $codAtendimento;
				$response['csrf_hash'] = csrf_hash();
				$response['messages'] = 'Atendimento Iniciado';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na inserção!';
			}
		}

		sleep(1);



		if (session()->nomeLocalAtendimento == NULL or session()->codLocalAtendimento == NULL) {
			session()->codLocalAtendimento = $this->request->getPost('codLocalAtendimento');
			session()->nomeLocalAtendimento = lookupNomeLocalAtendimento($this->request->getPost('codLocalAtendimento'));
		}

		if (session()->codEspecialidadeAtendimento == NULL or session()->nomeEspecialidadesAtendimento == NULL) {
			session()->codEspecialidadeAtendimento = $this->request->getPost('codEspecialidade');
			session()->nomeEspecialidadesAtendimento = lookupNomeEspecialidade($this->request->getPost('codEspecialidade'));
		}


		return $this->response->setJSON($response);
	}

	public function pegaPaciente()
	{
		$response = array();

		$data['data'] = array();

		$paciente = $this->request->getPost('paciente');

		$result = $this->PacientesModel->pegaPaciente($paciente);

		foreach ($result as $key => $value) {

			$ops = '<div class="text-center"><div style="text-align:center" class="btn-group">';
			$ops .= '	<button style="margin-left:1px" type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Editar paciente" onclick="selecionarPacienteAgora(' . $value->codPaciente . ')">SELECIONAR</button>';


			$ops .= '</div></div>';

			if ($value->ativo == 0) {
				$ativo = ' <span><i style="font-size:20px" class="fas fa-user-slash text-danger"></i></span>';
			}
			if ($value->ativo == 1) {
				$ativo = ' <span><i style="font-size:20px" class="fas fa-user text-success"></i></span>';
			}

			$status = '<span class="right badge badge-' . $value->corStatusCadastroPaciente . '">' . $value->nomeStatusCadastroPaciente . '</span>';
			$data['data'][$key] = array(
				$value->codPaciente . $ativo,
				$value->nomeExibicao,
				$value->cpf,
				$value->codPlano,
				$value->codProntuario,
				$status,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}
}
