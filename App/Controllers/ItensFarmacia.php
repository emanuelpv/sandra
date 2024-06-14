<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;
use App\Models\PrescricoesMaterialModel;
use App\Models\AtendimentosPrescricoesModel;
use App\Models\PrescricaoMedicamentosModel;
use App\Models\AtendimentosModel;
use App\Models\ItensFarmaciaModel;

class ItensFarmacia extends BaseController
{

	protected $ItensFarmaciaModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->ItensFarmaciaModel = new ItensFarmaciaModel();
		$this->PrescricoesMaterialModel = new PrescricoesMaterialModel();
		$this->PrescricaoMedicamentosModel = new PrescricaoMedicamentosModel();
		$this->AtendimentosPrescricoesModel = new AtendimentosPrescricoesModel();
		$this->AtendimentosModel = new AtendimentosModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('ItensFarmacia', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "ItensFarmacia"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'itensFarmacia',
			'title'     		=> 'Itens Hospitalares'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('itensFarmacia', $data);
	}


	public function dispensacao()
	{

		$permissao = verificaPermissao('itensFarmacia/dispensacao', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo de "Dispensação"', session()->codPessoa);
			exit();
		}


		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('itensFarmaciaDispensacao');
	}



	public function prescricaoMedicamentos()
	{
		$response = array();

		$data['data'] = array();
		$codAtendimentoPrescricao = $this->request->getPost('codAtendimentoPrescricao');
		$result = $this->PrescricaoMedicamentosModel->pegaPorCodigoAtendimentoPrescricao($codAtendimentoPrescricao);

		//ALERGIAS
		$alergias = $this->PrescricaoMedicamentosModel->pegaAlergiasPaciente($result[0]->codPaciente);



		$listaAlergias = array();
		foreach ($alergias as $alergia) {

			array_push($listaAlergias, $alergia->descricaoAlergenico);
		}

		$x = 0;
		foreach ($result as $key => $value) {
			$x++;

			$existeAlergia = NULL;



			if (count($listaAlergias) > 0) {
				foreach ($listaAlergias as $nomeAlergia) {

					if ($value->descricaoItem !== NULL and $value->descricaoItem !== "" and $value->descricaoItem !== ' ' and $nomeAlergia !== NULL and $nomeAlergia !== '' and $nomeAlergia !== ' ') {
						$pos = strpos(mb_strtoupper($value->descricaoItem, 'utf-8'), mb_strtoupper($nomeAlergia, 'utf-8'));

						// exemplo de uso:

						if ($pos === false) {
						} else {
							$existeAlergia = '<br><span class="right badge badge-danger" style="font-size:12px"> ALERGIA ?</span> <img style="width:50px;" src="' . base_url() . '/imagens/atencao.gif">';
							break;
						}
					}
				}
			}

			$ops = '<div class="btn-group">';
			if ($value->stat <= 3) {
				//$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editprescricaoMedicamentos(' . $value->codPrescricaoMedicamento . ')"><i class="fa fa-edit"></i></button>';
				//$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeprescricaoMedicamentos(' . $value->codPrescricaoMedicamento . ')"><i class="fa fa-trash"></i></button>';
			}
			$ops .= '</div>';

			$descricaoStatusPrescricao = '<div class="right badge badge-' . $value->corStatusPrescricao . '">' . $value->descricaoStatusPrescricao . '</div>';
			$statusRisco = '<div class="right badge badge-' . $value->corRiscoPrescricao . '">' . $value->descricaoRiscoPrescricao . '</div>';

			$total = '<div class="right badge badge-primary"> Solicitado:' . $value->total . '</div>';


			if ($value->stat ==  0) {
				$total .= '<div class="right badge badge-dark"> Liberado:0</div><br>';
			} else {
				if ($value->totalLiberado > 0) {
					$total .= '<div class="right badge badge-success"> Liberado:' . $value->totalLiberado . '</div><br>';
					$totalLiberado = $value->totalLiberado;
				} else {
					$totalLiberado = $value->qtde * $value->freq;
				}


				if ($value->totalEntregue > 0) {
					$total .= '<div class="right badge badge-success"> Entregue:' . $value->totalEntregue . '</div><br>';
				}
			}



			if (($value->totalEntregue > 0 or $value->totalEntregue > 0) and $value->totalExecutado == 0 and $value->stat >  0) {
				$total .= '<a href="#" onclick="gravarMedicamentosExecutados(' . $value->codPrescricaoMedicamento . ',' . $value->total . ')"><div class="right badge badge-danger"> Executado:??</div><br></a>';
			}


			if ($value->totalExecutado > 0 and $value->stat >  0) {
				$total .= '<a href="#" onclick="gravarMedicamentosExecutados(' . $value->codPrescricaoMedicamento . ',' . $value->total . ')"><div class="right badge badge-primary"> Executado:' . $value->totalExecutado . '</div><br></a>';
			}


			if ($value->horaIni !== NULL and $value->horaIni !== '') {
				$inicio = ' | Inícios às ' . $value->horaIni;
			} else {
				$inicio = '';
			}

			$dias = '';
			if ($value->dias > 1) {
				$dias = $value->dias . ' dias';
			}
			if ($value->dias == 1) {
				$dias = $value->dias . ' dia';
			}

			$aplicacao = 'Frequência: ' . $value->freq . 'x/' . $value->descricaoPeriodo . ' | Por ' . $dias . $inicio;



			$guiaAntimicrobiano = '';

			if ($value->antibiotico == 1) {

				//ir buscar as guias deste atendimento e comparar 
				//e não tiver guia, dar a opção de criar
				//dar a opção de cancelar, guia e medicamento e colocar motivo.
				//Criar aba de todas as guias de antimicrobianos do paciente
				//Dar essa visão à Farmácia

				$resultAntimicrobiano = $this->PrescricaoMedicamentosModel->verificaGuiaAntimicrobiano($value->codAtendimento, $value->codItem, $value->dataInicioPrescricao);


				if ($resultAntimicrobiano->codControleAntimicrobiano == NULL) {

					$guiaAntimicrobiano = '
                <div> <img style="width:50px;" src="' . base_url() . '/imagens/atencao.gif">
                    <span style="font-size:16px; margin-bottom:10px" class="right badge badge-danger">Falta guia antimicrobiano
                    </span>
                </div>';
				}

				if ($resultAntimicrobiano->codControleAntimicrobiano !== NULL and $resultAntimicrobiano->codStatus == 0) {
					$guiaAntimicrobiano = '
					<div> <img style="width:30px;" src="' . base_url() . '/imagens/atencao.gif">
						<span style="color:red;font-size:16px; margin-bottom:10px" >Guia antimicrobiana Nº ' . $resultAntimicrobiano->codControleAntimicrobiano . ' foi suspensa por ' . $resultAntimicrobiano->suspensoPor . ' em ' . date("d/m/Y H:i", strtotime($resultAntimicrobiano->dataSuspensao)) . '
						</span>
					</div>
					<div style="color:red;font-size:16px;">
					Motivo:' . $resultAntimicrobiano->motivoSuspensaoGuia . '
					</div>';
				} else {



					$dataPrescricao = strtotime($value->dataInicioPrescricao);
					$dataEncerramento = strtotime($resultAntimicrobiano->dataEncerramento);
					$dataInicio = strtotime($resultAntimicrobiano->dataInicio);

					$diasAntimicrobiano = round(($dataPrescricao - $dataInicio) / 60 / 60 / 24);
					$diasAntimicrobiano = $diasAntimicrobiano + 1 . '&deg; dia';


					if (date("Y-m-d", strtotime($value->dataInicioPrescricao)) > date("Y-m-d", strtotime($resultAntimicrobiano->dataEncerramento))) {
						$guiaAntimicrobiano = '<div>
                        <img style="width:50px;" src="' . base_url() . '/imagens/atencao.gif">
                        <span style="color:red;font-size:16px;margin-bottom:10px">Não possui Guia Antimicrobiana';
						//. abs($diasAntimicrobiano) . ' dia(s)' . ' Deseja emitir nova guia antimicrobiana?</span></div>';
					} else {
						$guiaAntimicrobiano .= '
                            <div style="height:100% !important" class="callout callout-danger">
                            		<div style="font-size:16px;font-weight:bold"><center>Guia Antimicrobiana</center></div>
									<div><span style="font-size:16px;" class="right badge badge-danger">' . $diasAntimicrobiano . '</span></div>
									<div>Nº da Guia:' . $resultAntimicrobiano->codControleAntimicrobiano . '</div>
									<div> Data Início:' . date("d/m/Y", strtotime($resultAntimicrobiano->dataInicio)) . '</div>
									<div> Data Encerramento:' . date("d/m/Y", strtotime($resultAntimicrobiano->dataEncerramento)) . '</div>
									<div>Emissor:' . $resultAntimicrobiano->nomeExibicao . '</div>
								
									<div style="margin-bottom:10px" >
									<button style="font-size:16px; margin-bottom:10px" type="button" class="btn btn-sm btn-success"  data-toggle="tooltip" data-placement="top" title="Guia Antimicroniano"  onclick="imprimirGuiaAntimicrobiana(' . $resultAntimicrobiano->codControleAntimicrobiano . ')"><i class="fa fa-edit"></i>Imprimir Guia</button>
									</div>
                            </div>
                            ';
					}
				}
			} else {
			}

			if ($value->dataInicioPrescricao >= date("Y-m-d")) {

				$btnSuspenderMedicamento = '
                <div style="margin-bottom:10px" >
                    <button style="font-size:16px; margin-top:15px" type="button" class="btn btn-sm btn-danger"  data-toggle="tooltip" data-placement="top" title="Suspender Medicamento"  onclick="suspenderMedicamento(' . $value->codAtendimento . ',' . $value->codPaciente . ',' . $value->codItem . ',' . $value->codPrescricaoMedicamento . ',' . $totalLiberado . ')">Suspender</button>
                </div>';
			} else {
				$btnSuspenderMedicamento = NULL;
			}

			$autorMotivo = NULL;
			if ($value->codSuspensaoMedicamento !== NULL) {
				$descricaoItem = '<s>' . $value->descricaoItem . '</s>';
				$autorMotivo = '<div style="color:red">
				<span><img style="width:30px;" src="' . base_url() . '/imagens/atencao.gif"></span>
				Suspenso em : ' . date("d/m/Y H:i", strtotime($value->dataSuspensao)) . ' por ' . $value->autorSuspensao . '.</div>';
				$autorMotivo .= '<div style="color:red"> Motivo:' . $value->motivo . '</div>';


				//LIMPA DADOS SOBRE GUIA ANTIMICROBIANA
				$guiaAntimicrobiano = NULL;
				$checagem = NULL;
				$btnSuspenderMedicamento = NULL;
				$obs = NULL;
			} else {
				$descricaoItem = $value->descricaoItem;
				$obs = $value->obs;
			}

            if ($value->codPrescricaoComplementar == NULL) {
                $prescricaoComplementar = '';
                $autorComplemento = '';
            } else {
                $prescricaoComplementar = '<span style="margin-left:10px;font-size:12px;" class="right badge badge-danger">Complementar</span>';
                $autorComplemento = '<div> Por ' . $value->autorComplemento . ' em ' . date("d/m/Y H:i", strtotime($value->dataCriacaoComplemento)) . '.</div>';
            }

			
			$data['data'][$key] = array(
				$x,
				'<div style="font-weight: bold;">' . $descricaoItem . $prescricaoComplementar . '</div><div>' . $aplicacao . '</div>' . $existeAlergia . '<br><div style="font-size:14px;color:red">' . $obs . '</div><div style="font-size:14px;">' . $autorComplemento . '</div>
                <br>
                ' . $guiaAntimicrobiano . $autorMotivo,
				$value->qtde,
				'<input style="width:50px; display: none" class="editarMedicamentos" id="editarQtde' . $value->codPrescricaoMedicamento . '" name="editarQtde' . $value->codPrescricaoMedicamento . '" value="' . $totalLiberado . '"><span class="verMedicamentos">' . $value->totalLiberado . '</span>',
				$value->descricaoUnidade,
				$value->descricaoVia,
				'<div>
                Agora: ' . $value->descricaoAplicarAgora . '
                </div>
                <div>
                Risco: ' . $statusRisco . '
                </div>',
				$total,
				$descricaoStatusPrescricao,
				$value->nomeExibicao,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}


	public function savarLiberacaoMedicamentos($codAtendimento = NULL)
	{

		$codStatus = "";
		foreach ($this->request->getPost() as $chave => $atributo) {
			$fields = array();

			if (strpos($chave,  'editarQtde') !== false) {
				$codPrescricaoMedicamento = str_replace('editarQtde', '', $chave);



				$fields['totalLiberado'] = $this->request->getPost('editarQtde' . $codPrescricaoMedicamento);

				if ($fields['totalLiberado'] > 0) {
					$fields['stat'] = 3; //LIBERADO
				}



				if ($fields['totalLiberado'] == 'f' or $fields['totalLiberado'] == 'F') {
					$fields['stat'] = 0; //FALTA
				}



				$fields['codAutorLiberacao'] = session()->codPessoa;
				$fields['dataAtualizacao'] = date('Y-m-d H:i');


				if ($codPrescricaoMedicamento !== NULL and $codPrescricaoMedicamento !== "" and $codPrescricaoMedicamento !== " ") {
					if ($this->PrescricaoMedicamentosModel->update($codPrescricaoMedicamento, $fields)) {
					}
				}
			}
		}




		$response['success'] = true;
		$response['messages'] = 'Liberação realizada com sucesso';
		return $this->response->setJSON($response);
	}



	public function prescricaoMateriais()
	{
		$response = array();

		$data['data'] = array();
		$codAtendimentoPrescricao = $this->request->getPost('codAtendimentoPrescricao');
		$result = $this->PrescricoesMaterialModel->pegaPorCodigoAtendimentoPrescricao($codAtendimentoPrescricao);
		$x = count($result);
		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			if ($value->codStatus <= 3) {
				$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editprescricoesMaterial(' . $value->codPrescricaoMaterial . ')"><i class="fa fa-edit"></i></button>';
				$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeprescricoesMaterial(' . $value->codPrescricaoMaterial . ')"><i class="fa fa-trash"></i></button>';
			}
			$ops .= '</div>';

			$descricaoStatusMaterial = '<span class="right badge badge-' . $value->corStatusMaterial . '">' . $value->descricaoStatusMaterial . '</span>';

			if ($value->totalLiberado !== NULL) {
				$totalLiberado = $value->totalLiberado;
			} else {
				$totalLiberado = $value->qtde;
			}


			$data['data'][$key] = array(
				$x,
				$value->descricaoItem . '<br><div class="right badge badge-danger">' . $value->observacao . '</div>',
				$value->qtde,
				'<input style="width:50px; display: none" class="editarMateriais" id="editarQtde' . $value->codPrescricaoMaterial . '" name="editarQtde' . $value->codPrescricaoMaterial . '" value="' . $totalLiberado . '"><span class="verMateriais">' . $value->totalLiberado . '</span>',

				$value->nomeExibicao,
				date('d/m/Y H:i', strtotime($value->dataAtualizacao)),
				$descricaoStatusMaterial,
				$ops,
			);
			$x--;
		}

		return $this->response->setJSON($data);
	}


	public function savarLiberacaoMateriais($codAtendimento = NULL)
	{

		$codStatus = "";
		foreach ($this->request->getPost() as $chave => $atributo) {
			$fields = array();

			if (strpos($chave,  'editarQtde') !== false) {
				$codPrescricaoMateriais = str_replace('editarQtde', '', $chave);



				$fields['totalLiberado'] = $this->request->getPost('editarQtde' . $codPrescricaoMateriais);

				if ($fields['totalLiberado'] > 0) {
					$fields['codStatus'] = 3; //LIBERADO
				}



				if ($fields['totalLiberado'] == 'f' or $fields['totalLiberado'] == 'F') {
					$fields['totalLiberado'] = 0;
					$fields['codStatus'] = 0; //FALTA
				}



				$fields['codAutorLiberacao'] = session()->codPessoa;
				$fields['dataAtualizacao'] = date('Y-m-d H:i');


				if ($codPrescricaoMateriais !== NULL and $codPrescricaoMateriais !== "" and $codPrescricaoMateriais !== " ") {
					if ($this->PrescricoesMaterialModel->update($codPrescricaoMateriais, $fields)) {
					}
				}
			}
		}




		$response['success'] = true;
		$response['messages'] = 'Liberação realizada com sucesso';
		return $this->response->setJSON($response);
	}

	public function pendentesDispensacao()
	{
		$response = array();

		$data['data'] = array();


		if ($this->request->getPost('codDepartamento') !== NULL and $this->request->getPost('codDepartamento') !== "" and $this->request->getPost('codDepartamento') !== 0) {
			$result = $this->ItensFarmaciaModel->pendentesDispensacao($this->request->getPost('codDepartamento'));
		} else {

			$result = $this->ItensFarmaciaModel->pendentesDispensacao();
		}



		foreach ($result as $key => $value) {

			$ops = '
            <div class="btn-group">
                <button type="button" class="btn btn-primary">Ação</button>
                <button type="button" class="btn btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu" role="menu">
                <a href="#" class="dropdown-item" onclick="processarDispensacao(' . $value->codAtendimentoPrescricao . ')">Processar dispensação</a>
                <a href="#" class="dropdown-item" onclick="assinarDispensacao(' . $value->codAtendimentoPrescricao . ')">Assinar dispensação</a>
                </div>
            </div>
            ';


			if ($value->dataInicio == $value->dataEncerramento) {
				$periodo = 'Dia ' . date('d/m/y', strtotime($value->dataEncerramento));
			} else {
				$periodo = 'De ' . date('d/m/y', strtotime($value->dataInicio)) . ' à ' . date('d/m/y', strtotime($value->dataEncerramento));
			}


			if ($value->codLocalAtendimento == 0) {
				$descricaoLocalAtendimento =  "Acolhimento";
			} else {
				$descricaoLocalAtendimento = '
                <div>' . $value->descricaoDepartamento . '</div>
                <div class="right badge badge-success">' . mb_strtoupper($value->descricaoLocalAtendimento, 'utf-8') . '</div>
                ';
			}



			if ($value->descricaoStatus == 'Assinada') {
				$status = 'À dispensar';
			} else {
				$status = $value->descricaoStatus;
			}

			$statusPrescricao = '
            <div>
             <span class="right badge badge-' . $value->corStatusPrescricao . '">' . $status . '</span>
            </div>
            
            ';

			$data['data'][$key] = array(
				$value->codAtendimentoPrescricao,
				$value->nomeCompleto,
				$descricaoLocalAtendimento,
				$periodo . '<div style="font-size:12px; color:red">' . $value->conteudoPrescricao . '</div>',
				$value->nomeEspecialista,
				$statusPrescricao,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function emProcessamentoDispensacao()
	{
		$response = array();

		$data['data'] = array();


		if ($this->request->getPost('codDepartamento') !== NULL and $this->request->getPost('codDepartamento') !== "" and $this->request->getPost('codDepartamento') !== 0) {
			$result = $this->ItensFarmaciaModel->emProcessamentoDispensacao($this->request->getPost('codDepartamento'));
		} else {

			$result = $this->ItensFarmaciaModel->emProcessamentoDispensacao();
		}



		foreach ($result as $key => $value) {

			$ops = '
            <div class="btn-group">
                <button type="button" class="btn btn-primary">Ação</button>
                <button type="button" class="btn btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu" role="menu">
                <a href="#" class="dropdown-item" onclick="processarDispensacao(' . $value->codAtendimentoPrescricao . ')">Processar dispensação</a>
                <a href="#" class="dropdown-item" onclick="assinarDispensacao(' . $value->codAtendimentoPrescricao . ')">Assinar dispensação</a>
                </div>
            </div>
            ';


			if ($value->dataInicio == $value->dataEncerramento) {
				$periodo = 'Dia ' . date('d/m/y', strtotime($value->dataEncerramento));
			} else {
				$periodo = 'De ' . date('d/m/y', strtotime($value->dataInicio)) . ' à ' . date('d/m/y', strtotime($value->dataEncerramento));
			}


			if ($value->codLocalAtendimento == 0) {
				$descricaoLocalAtendimento =  "Acolhimento";
			} else {
				$descricaoLocalAtendimento = '
                <div>' . $value->descricaoDepartamento . '</div>
                <div class="right badge badge-success">' . mb_strtoupper($value->descricaoLocalAtendimento, 'utf-8') . '</div>
                ';
			}



			if ($value->descricaoStatus == 'Assinada') {
				$status = 'À dispensar';
			} else {
				$status = $value->descricaoStatus;
			}

			$statusPrescricao = '
            <div>
             <span class="right badge badge-' . $value->corStatusPrescricao . '">' . $status . '</span>
            </div>
            
            ';

			$data['data'][$key] = array(
				$value->codAtendimentoPrescricao,
				$value->nomeCompleto,
				$descricaoLocalAtendimento,
				$periodo . '<div style="font-size:12px; color:red">' . $value->conteudoPrescricao . '</div>',
				$value->nomeEspecialista,
				$statusPrescricao,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}



	public function filtrarDispensacao()
	{
		$response = array();


		if ($this->request->getPost('codDepartamento')  !== NULL and $this->request->getPost('codDepartamento')  !== 0 and $this->request->getPost('codDepartamento')  !== '') {
			$codDepartamento = $this->request->getPost('codDepartamento');
		} else {
			$codDepartamento = NULL;
		}
		if ($this->request->getPost('codCategoria')  !== NULL and $this->request->getPost('codCategoria')  !== 0 and $this->request->getPost('codCategoria')  !== '') {
			$codCategoria = $this->request->getPost('codCategoria');
		} else {
			$codCategoria = NULL;
		}
		if ($this->request->getPost('dataInicio')  !== NULL) {
			$dataInicio = $this->request->getPost('dataInicio');
		} else {
			$dataInicio = NULL;
		}
		if ($this->request->getPost('dataEncerramento')  !== NULL) {
			$dataEncerramento = $this->request->getPost('dataEncerramento');
		} else {
			$dataEncerramento = NULL;
		}

		$filtro["codDepartamento"] = $codDepartamento;
		$filtro["codCategoria"] = $codCategoria;
		$filtro["dataInicio"] = $dataInicio;
		$filtro["dataEncerramento"] = $dataEncerramento;

		$this->validation->setRules([
			'codCategoria' => ['label' => 'codCategoria', 'rules' => 'permit_empty|bloquearReservado|numeric|max_length[11]'],
			'codDepartamento' => ['label' => 'codDepartamento', 'rules' => 'permit_empty|bloquearReservado|numeric|max_length[11]'],
			'dataInicio' => ['label' => 'dataInicio', 'rules' => 'permit_empty|bloquearReservado|valid_date'],
			'dataEncerramento' => ['label' => 'dataEncerramento', 'rules' => 'permit_empty|bloquearReservado|valid_date'],

		]);

		if ($this->validation->run($filtro) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {


			session()->set('filtroDispensacao', $filtro);
			$response['success'] = true;
			$response['csrf_hash'] = csrf_hash();
		}



		return $this->response->setJSON($response);
	}



	public function dispensados()
	{
		$response = array();

		$data['data'] = array();



		if ($this->request->getPost('codDepartamento') !== NULL and $this->request->getPost('codDepartamento') !== "" and $this->request->getPost('codDepartamento') !== 0) {
			$result = $this->ItensFarmaciaModel->dispensados($this->request->getPost('codDepartamento'));
		} else {

			$result = $this->ItensFarmaciaModel->dispensados();
		}



		foreach ($result as $key => $value) {

			$ops = '
            <div class="btn-group">
                <button type="button" class="btn btn-primary">Ação</button>
                <button type="button" class="btn btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu" role="menu">
                <a href="#" class="dropdown-item" onclick="processarDispensacao(' . $value->codAtendimentoPrescricao . ')">Processar dispensação</a>
                <a href="#" class="dropdown-item" onclick="assinarDispensacao(' . $value->codAtendimentoPrescricao . ')">Assinar dispensação</a>
                </div>
            </div>
            ';


			if ($value->dataInicio == $value->dataEncerramento) {
				$periodo = 'Dia ' . date('d/m/y', strtotime($value->dataEncerramento));
			} else {
				$periodo = 'De ' . date('d/m/y', strtotime($value->dataInicio)) . ' à ' . date('d/m/y', strtotime($value->dataEncerramento));
			}


			if ($value->codLocalAtendimento == 0) {
				$descricaoLocalAtendimento =  "Acolhimento";
			} else {
				$descricaoLocalAtendimento = '
                <div>' . $value->descricaoDepartamento . '</div>
                <div class="right badge badge-success">' . mb_strtoupper($value->descricaoLocalAtendimento, 'utf-8') . '</div>
                ';
			}



			$statusPrescricao = '
            <div>
             <span class="right badge badge-' . $value->corStatusPrescricao . '">' . $value->descricaoStatus . '</span>
            </div>
            
            ';

			$data['data'][$key] = array(
				$value->codAtendimentoPrescricao,
				$value->nomeCompleto,
				$descricaoLocalAtendimento,
				$periodo . '<div style="font-size:12px; color:red">' . $value->conteudoPrescricao . '</div>',
				$value->nomeEspecialista,
				$statusPrescricao,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}


	public function imprimirPrescricao($codAtendimentoPrescricao = null)
	{
		$response = array();

		if ($codAtendimentoPrescricao == NULL) {
			$codAtendimentoPrescricao = $this->request->getPost('codAtendimentoPrescricao');
		}

		if ($this->validation->check($codAtendimentoPrescricao, 'required|numeric')) {

			$atendimentos = $this->ItensFarmaciaModel->atendimentoPorCodAtendimentoPrescricao($codAtendimentoPrescricao);
			$prescricoes = $this->ItensFarmaciaModel->prescricoesPorCodAtendimentoPrescricao($codAtendimentoPrescricao);
			$materiais = $this->ItensFarmaciaModel->materiaisPorCodAtendimentoPrescricao($codAtendimentoPrescricao);


			$nrAtendimento = str_pad($atendimentos->codAtendimento, 8, "0", STR_PAD_LEFT) . '/' . (string)date('Y');
			$nrPrescricao = str_pad($atendimentos->codAtendimentoPrescricao, 8, "0", STR_PAD_LEFT) . '/' . (string)date('Y');


			$periodo = 'De ' . date('d/m/Y', strtotime($atendimentos->dataInicio)) . " à " . date('d/m/Y', strtotime($atendimentos->dataEncerramento));


			$html = '
			<style>
				.grid-striped .row:nth-of-type(odd) {
					background-color: rgba(0,0,0,.05);
				}
				
				.grid-stripedNada .row:nth-of-type(odd) {
					background-color: rgba(0,0,0,.0);
				}
			</style>

			<div style="font-size:14px;" class="row">
				<div class="col-md-12">
					<div style="font-size:30px;font-weight: bold;margin-bottom:10px" class="text-center">DISPENSAÇÃO</div>			
			';

			//ATENDIMENTO
			$html .= '
				<div class="row border">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-3">
								Atendimento Nº: ' . $nrAtendimento . ' 
							</div>
							<div class="col-md-3">
								Prescrição Nº: ' . $nrPrescricao . ' 
							</div>
							<div class="col-md-6 class="text-right">
								Período Prescrição: ' . $periodo . ' 
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								Paciente: ' . $atendimentos->paciente . ' (' . $atendimentos->idade . ')
							</div>
							<div class="col-md-3">
								Nº Plano: ' . $atendimentos->codPlano . ' 
							</div>
							<div class="col-md-6 class="text-right">
								Prontuário: ' . $atendimentos->codProntuario . ' 
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 class="text-right">
								Especialista: ' . $atendimentos->especialista . ' 
							</div>
							<div class="col-md-3">
								Situação: ' . $atendimentos->siglaTipoBeneficiario . ' 
							</div>							
							<div style="font-weight: bold;" class="col-md-3">
								Local: ' . $atendimentos->abreviacaoDepartamento . ' (' . $atendimentos->descricaoLocalAtendimento . ')' . ' 
							</div>
						</div>
					</div>
				</div>
				';



			//DIETA

			$html .= '<b>DIETA [+]</b>
				<div class="row border grid-striped">
					<div class="col-md-12">
						<div class="row">
								<div class="col-md-3">
								' . $atendimentos->dieta . ' 
								</div>
						</div>
					</div>
				</div>';





			//MEDICAMENTOS
			$x = 0;
			$html .= '<b>MEDICAMENTOS [+]</b>
				<div class="row border grid-striped">
					<div class="col-md-12">				
					
					<div class="row">
							<div style="font-weight: bold;" class="col-md-5">
								PRESCRIÇÃO   
							</div>
							<div style="font-weight: bold;" class="col-md-2">
								DOSE   
							</div>
							<div style="font-weight: bold;" class="col-md-1">
								VIA   
							</div>
							<div style="font-weight: bold;" class="col-md-1">
								INTERV?
							</div>
							<div style="font-weight: bold;" class="col-md-1">
								SOLICITADO
							</div>
							<div style="font-weight: bold;" class="col-md-1">
								LIBERADO
							</div>
					</div>
					
					';


			foreach ($prescricoes as $prescricao) {
				$x++;


				$obs =  $prescricao->observacaoMedicamento;
				$autorMotivo = NULL;
				if ($prescricao->codSuspensaoMedicamento !== NULL) {
					$descricaoItem = '<s>' . $prescricao->descricaoItem . '</s>';
					$obs = '<div style="color:red"> Suspenso em : ' . date("d/m/Y H:i", strtotime($prescricao->dataSuspensao)) . ' por ' . $prescricao->autorSuspensao . '.</div>';
					$obs .= '<div style="color:red"> Motivo:' . $prescricao->motivo . '</div>';


					//LIMPA DADOS SOBRE GUIA ANTIMICROBIANA
					$guiaAntimicrobiano = NULL;
					$checagem = NULL;
					$btnSuspenderMedicamento = NULL;
				} else {
					$descricaoItem = $prescricao->descricaoItem;
					$obs = $prescricao->obs;
				}

				if ($prescricao->codPrescricaoComplementar == NULL) {
					$prescricaoComplementar = '';
					$autorComplemento = '';
				} else {
					$prescricaoComplementar = '<span> (**)</span>';
					$autorComplemento = '<div> Por ' . $prescricao->autorComplemento . ' em ' . date("d/m/Y H:i", strtotime($prescricao->dataCriacaoComplemento)) . '.</div>';
				}

				$html .= '

						
						<div class="row">
							<div class="col-md-5">
								<div>' . $x . ' - ' . $prescricao->nee . ' - <b>' . $descricaoItem . $prescricaoComplementar . '</b></div>
								<div>' . $obs . '</div>
								<div>' . $autorComplemento . '</div>
								
							</div>
							
							<div class="col-md-2">
								<div>' . $prescricao->qtde . '-' . $prescricao->descricaoUnidade . '</div>
							</div>
							
							<div class="col-md-1">
								<div>' . $prescricao->descricaoVia . '</div>
							</div>
							<div class="col-md-1">
								<div>' . $prescricao->freq . 'x ' . $prescricao->descricaoPeriodo . '</div>
							</div>
							<div class="col-md-1">
								<div>' . $prescricao->total . '</div>
							</div>
							<div class="col-md-1">
								<div>' . $prescricao->totalLiberado . '</div>
							</div>
							
						</div>



							';
			}
			$html .= '	
				</div>				
			</div>';







			//MATERIAIS
			$x = 0;
			$html .= '<b>MATERIAIS [+]</b>
									<div class="row border grid-striped">
										<div class="col-md-12">
										
										
										<div class="row">
												<div style="font-weight: bold;" class="col-md-6">
													MATERIAL
												</div>
												<div style="font-weight: bold;" class="col-md-3">
													SOLICITADO
												</div>
												<div style="font-weight: bold;" class="col-md-3">
													LIBERADO
												</div>
												<div style="font-weight: bold;" class="col-md-3">
													OBSERVAÇÕES/RECOMENDAÇÕES
												</div>
										</div>
										
										';


			foreach ($materiais  as $material) {
				$x++;
				$html .= '
					
											
											<div class="row">
												<div class="col-md-6">
													<div>' . $x . ' - <b>' . $material->descricaoItem . '</b></div>
												</div>
												
												<div class="col-md-3">
													<div>' . $material->qtde . '</div>
												</div>
												<div class="col-md-3">
													<div>' . $material->totalLiberado . '</div>
												</div>
												<div class="col-md-3">
													<div>' . $material->observacaoMaterial . '</div>
												</div>
											</div>
												';
			}

			$html .= '	
									</div>				
								</div>
								
								<div> ** Medicamento adicionado após a assinatura da prescrição
								</div>';




			$dataCriacao = $data['dataCriacao'] = session()->cidade . '-' . session()->uf . ', ' . date('d', strtotime(date('Y-m-d'))) . ' de ' . nomeMesPorExtenso(date('m', strtotime(date('Y-m-d')))) . ' de ' . date('Y', strtotime(date('Y-m-d'))) . '.';

			$html .= '
					<div style="margin-top:30px;" class="row">
								<div class="col-md-12">
									<div class="text-right">' . $dataCriacao . '</div>
								</div>
					</div>';


			if ($atendimentos->dispensacaoAssinadaPor !== NULL) {
				$status = '<div style="font-size:12px;font-weight: bold;margin-top:0px;color:green" class="text-center">(Dispensação assinada Eletronicamente)</div>	
						';
			} else {
				$status = '<div style="font-size:12px;font-weight: bold;margin-top:0px;color:red" class="text-center">(Dispensação não assinada Eletronicamente)</div>	
						';
			}

			$assinatura =
				'
				<div class="row">
				<div class="col-md-12">
				<div style="font-size:16px;font-weight: bold;margin-top:30px" class="text-center">' . $atendimentos->nomeCompletoEspecialista . ' - <b>' . $atendimentos->siglaCargo . '</b></div>	
				<div style="font-size:16px;font-weight: bold;margin-top:0px" class="text-center">' . $atendimentos->nomeConselho . ' ' . $atendimentos->numeroInscricao . '/' . $atendimentos->uf . '</div>	
				' . $status . '		
				</div>
				</div>';



			$html .= $assinatura;







			$html .= '
            <div style="margin-top:50px;" class="row">
						<div class="col-md-12">
                        <div class="text-right">Emitido por ' . session()->nomeExibicao . ' (CPF:' . substr(session()->cpf, 0, -6) . '*****'  . ')</div>
						<div class="text-right">Em ' . date('d/m/Y H:i') . '</div>
                        <div class="text-right">Sistema SANDRA | ' . base_url() . '</div>
						</div>
			</div>';



			$response['success'] = true;
			$response['html'] = $html;
			return $this->response->setJSON($response);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function dispensados_old()
	{
		$response = array();

		$data['data'] = array();



		$result = $this->ItensFarmaciaModel->dispensados();



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
				$x,
				$value->nomeCompleto,
				$descricaoLocalAtendimento,
				$statusAtendimento,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}


	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		if ($this->request->getPost('codCategoria') !== NULL and $this->request->getPost('codCategoria') !== "" and $this->request->getPost('codCategoria') !== 0) {
			$result = $this->ItensFarmaciaModel->pegaPorCodCategoria($this->request->getPost('codCategoria'));
		} else {

			$result = $this->ItensFarmaciaModel->pegaTudo();
		}

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="edititensFarmacia(' . $value->codItem . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeitensFarmacia(' . $value->codItem . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codItem,
				$value->nee,
				$value->descricaoItem,
				$value->valor,
				$value->saldo,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}


	public function verificaSeAntimicrobiano()
	{
		$response = array();
		$data = array();

		$id = $this->request->getPost('codItem');

		if ($this->validation->check($id, 'required|numeric')) {

			$result = $this->ItensFarmaciaModel->pegaPorCodigo($id);

			$data['antibiotico'] = $result->antibiotico;

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function getOne()
	{
		$response = array();
		$data = array();

		$id = $this->request->getPost('codItem');

		if ($this->validation->check($id, 'required|numeric')) {

			$result = $this->ItensFarmaciaModel->pegaPorCodigo($id);

			if ($result->dataValidade < date('Y-m-d')) {
				$vencido = "SIM";
			} else {
				$vencido = "NÂO";
			}

			$data['codItem'] = $result->codItem;
			$data['codOrganizacao'] = session()->codOrganizacao;
			$data['nee'] = $result->nee;
			$data['descricaoItem'] = $result->descricaoItem;
			$data['valor'] = $result->valor;
			$data['saldo'] = $result->saldo;
			$data['observacao'] = $result->observacao;
			$data['sire'] = $result->sire;
			$data['codCategoria'] = $result->codCategoria;
			$data['descricaoCategoria'] = $result->descricaoCategoria;
			$data['ean'] = $result->ean;
			$data['nme'] = $result->nme;
			$data['pp'] = $result->pp;
			$data['ativo'] = $result->ativo;
			$data['imagemItem'] = $result->imagemItem;
			$data['dataCriacao'] = $result->dataCriacao;
			$data['dataAtualizacao'] = $result->dataAtualizacao;
			$data['vencido'] = $vencido;
			$data['codBarra'] = $result->codBarra;
			$data['codAutor'] = $result->codAutor;
			$data['antibiotico'] = $result->antibiotico;
			$data['checksum'] = MD5($result->codItem . $result->descricaoItem);


			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}



	public function add()
	{

		$response = array();

		$fields['codItem'] = $this->request->getPost('codItem');
		$fields['codOrganizacao'] = session()->codOrganizacao;
		$fields['nee'] = $this->request->getPost('nee');
		$fields['descricaoItem'] = mb_strtoupper($this->request->getPost('descricaoItem'), "utf-8");
		$fields['valor'] = $this->request->getPost('valor');
		$fields['saldo'] = 0;
		$fields['observacao'] = $this->request->getPost('observacao');
		$fields['sire'] = $this->request->getPost('sire');
		$fields['codCategoria'] = $this->request->getPost('codCategoria');
		$fields['ean'] = $this->request->getPost('ean');
		$fields['nme'] = $this->request->getPost('nme');
		$fields['pp'] = $this->request->getPost('pp');
		$fields['antibiotico'] = $this->request->getPost('antibiotico');
		$fields['ativo'] = 1;
		$fields['imagemItem'] = $this->request->getPost('imagemItem');
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['codAutor'] = session()->codPessoa;
		$fields['codBarra'] = strtotime(date('Y-m-d H:i')) + geraNumero(6);



		$this->validation->setRules([
			'nee' => ['label' => 'NEE', 'rules' => 'required|max_length[20]'],
			'descricaoItem' => ['label' => 'Descrição', 'rules' => 'required|max_length[250]'],
			'valor' => ['label' => 'Valor', 'rules' => 'required'],
			'saldo' => ['label' => 'Saldo', 'rules' => 'required'],
			'observacao' => ['label' => 'Observação', 'rules' => 'permit_empty'],
			'sire' => ['label' => 'Sire', 'rules' => 'permit_empty|max_length[10]'],
			'codCategoria' => ['label' => 'Categoria', 'rules' => 'permit_empty|max_length[11]'],
			'ean' => ['label' => 'EAN', 'rules' => 'permit_empty|max_length[15]'],
			'nme' => ['label' => 'NME', 'rules' => 'permit_empty'],
			'pp' => ['label' => 'PP', 'rules' => 'permit_empty'],
			'imagemItem' => ['label' => 'ImagemItem', 'rules' => 'permit_empty|max_length[32]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->ItensFarmaciaModel->insert($fields)) {

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




	public function edit()
	{

		$response = array();


		if ($this->request->getPost('ativo') == 'on') {
			$fields['ativo'] = 1;
		} else {
			$fields['ativo'] = 0;
		}
		$fields['codItem'] = $this->request->getPost('codItem');
		$fields['nee'] = $this->request->getPost('nee');
		$fields['descricaoItem'] = mb_strtoupper($this->request->getPost('descricaoItem'), "utf-8");
		$fields['valor'] = $this->request->getPost('valor');
		$fields['saldo'] = $this->request->getPost('saldo');
		$fields['observacao'] = $this->request->getPost('observacao');
		$fields['sire'] = $this->request->getPost('sire');
		$fields['codCategoria'] = $this->request->getPost('codCategoria');
		$fields['ean'] = $this->request->getPost('ean');
		$fields['nme'] = $this->request->getPost('nme');
		$fields['antibiotico'] = $this->request->getPost('antibiotico');
		$fields['pp'] = $this->request->getPost('pp');
		$fields['imagemItem'] = $this->request->getPost('imagemItem');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');
		$fields['codAutor'] = session()->codPessoa;


		$this->validation->setRules([
			'codItem' => ['label' => 'codItem', 'rules' => 'required|numeric|max_length[20]'],
			'nee' => ['label' => 'NEE', 'rules' => 'required|max_length[20]'],
			'descricaoItem' => ['label' => 'Descrição', 'rules' => 'required|max_length[250]'],
			'valor' => ['label' => 'Valor', 'rules' => 'required'],
			'saldo' => ['label' => 'Saldo', 'rules' => 'required'],
			'observacao' => ['label' => 'Observação', 'rules' => 'permit_empty'],
			'sire' => ['label' => 'Sire', 'rules' => 'permit_empty|max_length[10]'],
			'codCategoria' => ['label' => 'Categoria', 'rules' => 'permit_empty|max_length[11]'],
			'ean' => ['label' => 'EAN', 'rules' => 'permit_empty|max_length[15]'],
			'nme' => ['label' => 'NME', 'rules' => 'permit_empty'],
			'pp' => ['label' => 'PP', 'rules' => 'permit_empty'],
			'imagemItem' => ['label' => 'ImagemItem', 'rules' => 'permit_empty|max_length[32]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->ItensFarmaciaModel->update($fields['codItem'], $fields)) {

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

	public function listaDropDown()
	{

		$result = $this->ItensFarmaciaModel->listaDropDown();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function listaDropDownMedicamentos()
	{

		$result = $this->ItensFarmaciaModel->listaDropDownMedicamentos();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	
	public function listaDropDownTodosItens()
	{

		$result = $this->ItensFarmaciaModel->listaDropDownTodosItens();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function listaDropDownDietas()
	{

		$result = $this->ItensFarmaciaModel->listaDropDownDietas();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function listaDropDownMateriais()
	{

		$result = $this->ItensFarmaciaModel->listaDropDownMateriais();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function listaDropDownMedicamentosAnvisa()
	{

		$result = $this->ItensFarmaciaModel->listaDropDownMedicamentosAnvisa();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}




	public function remove()
	{
		$response = array();

		$id = $this->request->getPost('codItem');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->ItensFarmaciaModel->where('codItem', $id)->delete()) {

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
