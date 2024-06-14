<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\AtendimentosPrescricoesModel;
use App\Models\PrescricaoProcedimentoModel;
use App\Models\PrescricoesCuidadosModel;
use App\Models\PrescricaoMedicamentosModel;
use App\Models\PrescricoesMaterialModel;
use App\Models\PrescricoesOutrasModel;
use App\Models\PrescricoesKitModel;

class AtendimentosPrescricoes extends BaseController
{

	protected $AtendimentosPrescricoesModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->PrescricoesKitModel = new PrescricoesKitModel();
		$this->AtendimentosPrescricoesModel = new AtendimentosPrescricoesModel();
		$this->PrescricaoMedicamentosModel = new PrescricaoMedicamentosModel();
		$this->PrescricoesCuidadosModel = new PrescricoesCuidadosModel();
		$this->PrescricaoProcedimentoModel = new PrescricaoProcedimentoModel();
		$this->PrescricoesMaterialModel = new PrescricoesMaterialModel();
		$this->PrescricoesOutrasModel = new PrescricoesOutrasModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation = \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao = $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('AtendimentosPrescricoes', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "AtendimentosPrescricoes"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller' => 'atendimentosPrescricoes',
			'title' => 'Prescrições do Atendimento'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('atendimentosPrescricoes', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->AtendimentosPrescricoesModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editatendimentosPrescricoes(' . $value->codAtendimentoPrescricao . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeatendimentosPrescricoes(' . $value->codAtendimentoPrescricao . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] = csrf_token();
			$data['csrf_hash'] = csrf_hash();
			$data['data'][$key] = array(
				$value->codAtendimentoPrescricao,
				$value->codAtendimento,
				$value->codStatus,
				$value->conteudoPrescricao,
				$value->impresso,
				$value->codAutor,
				$value->dataCriacao,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}
	public function getAllPrescricoes()
	{
		$response = array();

		$data['data'] = array();


		$codAtendimento = $this->request->getPost('codAtendimento');
		$result = $this->AtendimentosPrescricoesModel->pegaPorCodAtendimento($codAtendimento);

		$organizacao =  $this->OrganizacoesModel->pegaOrganizacao(session()->codOrganizacao);



		$x = count($result);
		foreach ($result as $key => $value) {



			$ops = '<div class="btn-group">';
			if ($value->codStatus == 1) {
				$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editatendimentosPrescricoes(' . $value->codAtendimentoPrescricao . ')"><i class="fa fa-edit"></i></button>';
				$ops .= '	<button type="button" class="btn btn-sm btn-primary"  data-toggle="tooltip" data-placement="top" title="Assinar"  onclick="assinarPrescricao(' . $value->codAtendimentoPrescricao . ')"><i class="fa fa-signature"></i></button>';
			} else {
				$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Ver"  onclick="editatendimentosPrescricoes(' . $value->codAtendimentoPrescricao . ')"><i class="fa fa-eye"></i></button>';
				$ops .= '	<button type="button" class="btn btn-sm btn-primary"  data-toggle="tooltip" data-placement="top" title="Já Assinado"  onclick="jaAssinado(' . $value->codAtendimentoPrescricao . ')"><i class="fa fa-signature"></i></button>';
			}
			$ops .= '	<button type="button" class="btn btn-sm btn-success"  data-toggle="tooltip" data-placement="top" title="Clonar"  onclick="clonarAtendimentoPrescricao(' . $value->codAtendimentoPrescricao . ')"><i class="fa fa-clone"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-secondary"  data-toggle="tooltip" data-placement="top" title="Imprimir"  onclick="imprimirAtendimentoPrescricao('.$value->codAtendimentoPrescricao.','.$value->codAtendimento.')"><i class="fa fa-print"></i></button>';

			if ($value->codStatus == 1) {
				$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeatendimentosPrescricoes(' . $value->codAtendimentoPrescricao . ')"><i class="fa fa-trash"></i></button>';
			} else {
				$ops .= '	<button type="button" class="btn btn-sm btn-danger"  data-toggle="tooltip" data-placement="top" title="Já Assinado"  onclick="jaAssinado(' . $value->codAtendimentoPrescricao . ')"><i class="fa fa-trash"></i></button>';
			}

			$ops .= '</div>';





			if (strlen($value->conteudoPrescricao) >= 100) {

				$conteudoPrescricao = mb_substr($value->conteudoPrescricao, 0, 90);
			} else {
				$conteudoPrescricao = $value->conteudoPrescricao;
			}


			$descricaoStatus = '<span class="right badge badge-' . $value->corStatusPrescricao . '">' . $value->descricaoStatus . '</span>';
			$descricaoTipo = '<span style="font-size:16px" class="right badge badge-' . $value->corTipoPrescricao . '">' . $value->descricaoTipo . '</span>';

			$assinadoPor = NULL;
			if ($value->codAutor !== $value->prescricaoAssinadaPor and $value->prescricaoAssinadaPor !== NULL) {
				$assinadoPor = 'Assinado Por: ' . $value->nomeExibicaoAssinador;
			}



			$data['csrf_token'] = csrf_token();
			$data['csrf_hash'] = csrf_hash();
			$data['data'][$key] = array(
				$x,
				'<div>' . $descricaoTipo . '</div>' . '<div style="font-size:12px;color:red">' . strip_tags($conteudoPrescricao) . '</div>',
				'<div>' . $value->nomeExibicao . '</div><div style="font-size:10px" class="right badge badge-danger">' . $assinadoPor . '</div>',
				date('d/m/Y H:i', strtotime($value->dataCriacao)),
				'De ' . date('d/m/Y', strtotime($value->dataInicio)) . ' até ' . date('d/m/Y', strtotime($value->dataEncerramento)),
				$descricaoStatus,
				$ops,
			);
			$x--;
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codAtendimentoPrescricao');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->AtendimentosPrescricoesModel->pegaPorCodigo($id);
			$alergias = $this->AtendimentosPrescricoesModel->alergias($data->codPaciente);

			if (!empty($alergias)) {
				$listaAlergias = '';
				foreach ($alergias as $alergia) {
					$listaAlergias .= $alergia->descricaoAlergenico . " | ";
				}
				$listaAlergias = rtrim($listaAlergias, " | ");

				$listaAlergias = '

				<div style="margin-bottom:10px;padding-right:15px;padding-left:15px;background:red;color:#fff" class="col-md-12">
					<div style="width:80px" class="col-md-4">
						<img style="width:80px;" src="' . base_url() . '/imagens/atencao.gif">
					</div>
					<div class="col-md-8 text-left">
						<div class="row">
							<b>ALERGIAS</b>:
						</div>
						<div class="row">
						<b>' . $listaAlergias . '</b>
						</div>
						<div class="row">
						' . $data->historiaAlergias . '
						</div>
					</div>
				</div>';

				$response['alergias'] = $listaAlergias;
			} else {
				$response['alergias'] = NULL;
			}


			if ($data->dataEncerramento > date("Y-m-d")) {
				$response['prescricaoDoDia'] = 1;
			} else {
				$response['prescricaoDoDia'] = 0;
			}

			$response['codAtendimentoPrescricao'] = $data->codAtendimentoPrescricao;
			$response['codAtendimento'] = $data->codAtendimento;
			$response['dataInicio'] = $data->dataInicio;
			$response['dataEncerramento'] = $data->dataEncerramento;
			$response['codStatus'] = $data->codStatus;
			$response['conteudoPrescricao'] = $data->conteudoPrescricao;
			$response['codTipoPrescricao'] = $data->codTipoPrescricao;
			$response['impresso'] = $data->impresso;
			$response['codAutor'] = $data->codAutor;
			$response['dataCriacao'] = $data->dataCriacao;
			$response['dataAtualizacao'] = $data->dataAtualizacao;
			$response['dieta'] = $data->dieta;


			if (date('Y-m-d') <= date('Y-m-d', strtotime($data->dataCriacao))) {
				$response['editavel'] = 1;
			} else {
				$response['editavel'] = 0;
			}


			return $this->response->setJSON($response);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function imprimirPrescricao($codAtendimentoPrescricao = null)
	{
		$response = array();

		if ($codAtendimentoPrescricao == NULL) {
			$codAtendimentoPrescricao = $this->request->getPost('codAtendimentoPrescricao');
		}

		if ($this->validation->check($codAtendimentoPrescricao, 'required|numeric')) {


			$atendimentos = $this->AtendimentosPrescricoesModel->atendimentoPorCodAtendimentoPrescricao($codAtendimentoPrescricao);
			$prescricoes = $this->AtendimentosPrescricoesModel->prescricoesPorCodAtendimentoPrescricao($codAtendimentoPrescricao);
			$cuidados = $this->AtendimentosPrescricoesModel->cuidadosPorCodAtendimentoPrescricao($codAtendimentoPrescricao);
			$kits = $this->AtendimentosPrescricoesModel->kitsPorCodAtendimentoPrescricao($codAtendimentoPrescricao);
			$procedimentos = $this->AtendimentosPrescricoesModel->procedimentosPorCodAtendimentoPrescricao($codAtendimentoPrescricao);
			$materiais = $this->AtendimentosPrescricoesModel->materiaisPorCodAtendimentoPrescricao($codAtendimentoPrescricao);
			$outras = $this->AtendimentosPrescricoesModel->outrasPorCodAtendimentoPrescricao($codAtendimentoPrescricao);


			$organizacao =  $this->OrganizacoesModel->pegaOrganizacao(session()->codOrganizacao);


			$alergias = $this->AtendimentosPrescricoesModel->alergias($atendimentos->codPaciente);

			if (!empty($alergias)) {
				$listaAlergias = '';
				foreach ($alergias as $alergia) {
					$listaAlergias .= $alergia->descricaoAlergenico . " | ";
				}
				$listaAlergias = rtrim($listaAlergias, " | ");
			} else {
				$listaAlergias = NULL;
			}



			$nrAtendimento = str_pad($atendimentos->codAtendimento, 8, "0", STR_PAD_LEFT);
			$nrPrescricao = str_pad($atendimentos->codAtendimentoPrescricao, 8, "0", STR_PAD_LEFT);


			$periodo = 'De ' . date('d/m/Y', strtotime($atendimentos->dataInicio)) . " à " . date('d/m/Y', strtotime($atendimentos->dataEncerramento));



			//VERIFICA SE JA ASSINADO, CASO NÃO SAIR!!!

			if ($atendimentos->codStatus < 2) {
				$response['success'] = false;
				$response['messages'] = 'Apenas é possível imprimir a prescrição verificada e assinada. Assine a prescrição!';
				return $this->response->setJSON($response);
			}




			$html = '';

			$html .= '	
			
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
					<div style="font-size:30px;font-weight: bold;margin-bottom:10px" class="text-center">PRESCRIÇÃO</div>			
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
						<div style="font-weight: bold;" class="row">
							<div class="col-md-3">
								Paciente: ' . $atendimentos->paciente . ' 
							</div>
							<div class="col-md-3">
								Nº Plano: ' . $atendimentos->codPlano . ' 
							</div>
							<div class="col-md-6 class="text-right">
								Prontuário: ' . $atendimentos->codProntuario . ' 
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								IDADE: ' . $atendimentos->idade . ' 
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

			$html .= '<b>ALERGIAS [+]</b>
			<div class="row border">
				<div class="col-md-12">
					<div class="row">
							<div class="col-md-12">
								<b>' . $listaAlergias . '</b> 
							</div>
							<div class="col-md-12">								
							' . $atendimentos->historiaAlergias . '
							</div>
					</div>
				</div>
			</div>';



			//DIETA

			$html .= '<b>DIETA [+]</b>
				<div class="row border">
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
							<div style="font-weight: bold;" class="col-md-1">
								DOSE   
							</div>
							<div style="font-weight: bold;" class="col-md-1">
								VIA   
							</div>
							<div style="font-weight: bold;" class="col-md-1">
								INTERV?
							</div>
							<div style="font-weight: bold;" class="col-md-1">
								AGORA?
							</div>
							<div style="font-weight: bold;" class="col-md-3">
								  <span style="margin-left:30px">APRAZAMENTO</span>
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
					$autorComplemento = '<div style="font-size:12px" > Por ' . $prescricao->autorComplemento . ' em ' . date("d/m/Y H:i", strtotime($prescricao->dataCriacaoComplemento)) . '.</div>';
				}


				$html .= '

						
						<div class="row border" >
							<div class="col-md-5">
								<div>' . $x . ' - ' . $prescricao->nee . ' - <b>' . $descricaoItem . $prescricaoComplementar . '</b></div>
								<div>' . $obs . '</div>
								<div>' . $autorComplemento . '</div>
							</div>
							
							<div class="col-md-1">
								<div>' . $prescricao->qtde . '-' . $prescricao->descricaoUnidade . '</div>
							</div>
							
							<div class="col-md-1">
								<div>' . $prescricao->descricaoVia . '</div>
							</div>
							<div class="col-md-1">
								<div>' . $prescricao->freq . 'x ' . $prescricao->descricaoPeriodo . '</div>
							</div>
							<div class="col-md-1">
								<div>' . $prescricao->descricaoAplicarAgora . '</div>
							</div>
							<div class="col-md-3">
								<div style="margin-left:15px">
										<span style="margin-left:10px" class="col-md-3">|       </span>
										<span  style="margin-left:50px" class="col-md-3">|       </span>
										<span  style="margin-left:50px" class="col-md-3">|       </span>
								</div>
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
													QUANTIDADE
												</div>
												<div style="font-weight: bold;" class="col-md-3">
													OBSERVAÇÕES/RECOMENDAÇÕES
												</div>
										</div>
										
										';


			foreach ($materiais as $material) {
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
													<div>' . $material->observacaoMaterial . '</div>
												</div>
											</div>
												';
			}

			$html .= '	
									</div>				
								</div>';




			//CUIDADOS
			$x = 0;
			$html .= '<b>CUIDADOS [+]</b>
				<div class="row border  grid-striped">
					<div class="col-md-12">
					
					
					<div class="row">
							<div style="font-weight: bold;" class="col-md-6">
								PRESCRIÇÃO
							</div>
							<div style="font-weight: bold;" class="col-md-3">
								OBSERVAÇÕES/RECOMENDAÇÕES
							</div>
							<div style="font-weight: bold;" class="col-md-3">
								APRAZAMENTO
							</div>
					</div>
					
					';


			foreach ($cuidados as $cuidado) {
				$x++;
				$html .= '

						
						<div class="row">
							<div class="col-md-6">
								<div>' . $x . ' - <b>' . $cuidado->nomeTipoCuidadoPrescricao . '</b></div>
							</div>
							
							<div class="col-md-3">
								<div>' . $cuidado->descricao . '</div>
							</div>
							
							<div class="col-md-3">
								<div>' . $cuidado->apraza . '</div>
							</div>
						</div>
							';
			}

			$html .= '	
				</div>				
			</div>';






			//KITS
			$x = 0;
			$html .= '<b>KITS [+]</b>
						<div class="row border grid-striped">
							<div class="col-md-12">
							
							
							<div class="row">
									<div style="font-weight: bold;" class="col-md-6">
										PRESCRIÇÃO
									</div>
									<div style="font-weight: bold;" class="col-md-3">
										QUANTIDADE
									</div>
									<div style="font-weight: bold;" class="col-md-3">
										OBSERVAÇÕES/RECOMENDAÇÕES
									</div>
							</div>
							
							';


			foreach ($kits as $kit) {
				$x++;
				$html .= '
		
								
								<div class="row">
									<div class="col-md-6">
										<div>' . $x . ' - <b>' . $kit->descricaoKit . '</b></div>
									</div>
									
									<div class="col-md-3">
										<div>' . $kit->qtde . '</div>
									</div>
									<div class="col-md-3">
										<div>' . $kit->observacao . '</div>
									</div>
								</div>
									';
			}

			$html .= '	
						</div>				
					</div>';








			//PROCEDIMENTOS
			$x = 0;
			$html .= '<b>PROCEDIMENTOS [+]</b>
						<div class="row border grid-striped">
							<div class="col-md-12">
							
							
								<div class="row">
										<div style="font-weight: bold;" class="col-md-6">
											PROCEDIMENTO
										</div>
										<div style="font-weight: bold;" class="col-md-1">
											QUANTIDADE
										</div>
										<div style="font-weight: bold;" class="col-md-1">
											VALOR
										</div>
										<div style="font-weight: bold;" class="col-md-1">
											SUBTOTAL
										</div>
										<div style="font-weight: bold;" class="col-md-3">
											OBSERVAÇÕES/RECOMENDAÇÕES
										</div>
								</div>
							
							';

			$subtotal = 0;
			foreach ($procedimentos as $procedimento) {
				$x++;
				$html .= '
		
								
								<div class="row">
									<div class="col-md-6">
										<div>' . $x . ' - <b>' . $procedimento->referencia . ' - ' . $procedimento->descricao . '</b></div>
									</div>									
									<div class="col-md-1">
										<div>' . $procedimento->qtde . '</div>
									</div>
									<div class="col-md-1">
										<div>' . $procedimento->valor . '</div>
									</div>
									<div class="col-md-1">
										<div>' . round($procedimento->qtde * $procedimento->valor, 2) . '</div>
									</div>
									<div class="col-md-3">
										<div>' . $procedimento->observacao . '</div>
									</div>
								</div>
									';
				$subtotal = $subtotal + ($procedimento->qtde * $procedimento->valor);
			}

			$html .= '	
						</div>
						<div class="col-md-12">
							<div class="row">
								<div style="font-weight: bold;" class="col-md-8">
									TOTAL EM PROCEDIMENTOS
								</div>
								<div style="font-weight: bold;" class="col-md-4">
									R$ ' . round($subtotal, 2) . '
								</div>
							</div>
						</div>			
					</div>';








			//OUTRAS PRESCRIÇÕES
			$x = 0;
			$html .= '<b>OUTRAS PRESCRIÇÕES [+]</b>
						<div class="row border grid-striped">
							<div class="col-md-12">
							
							
							<div class="row border-top">
									<div style="font-weight: bold;" class="col-md-3">
										TIPO
									</div>
									<div style="font-weight: bold;" class="col-md-6">
										PRESCRIÇÃO
									</div>
									<div style="font-weight: bold;" class="col-md-3">
										APRAZAMENTO
									</div>
							</div>
							
							';


			foreach ($outras as $outra) {
				$x++;
				$html .= '
		
								
								<div class="row border-top">
									
									<div class="col-md-3">
										<div>' . $x . ' - <b>' . $outra->nomeTipoOutraPrescricao . '</b></div>
									</div>
									<div class="col-md-6">
										<div>' . $outra->descricao . '</div>
									</div>
									<div class="col-md-3">
										<div>' . $outra->apraza . '</div>
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



			if ($atendimentos->prescricaoAssinadaPor !== NULL) {
				$status = '<div style="font-size:12px;font-weight: bold;margin-top:0px;color:green" class="text-center">(Assinada Eletronicamente)</div>	
						';
			} else {
				$status = '<div style="font-size:12px;font-weight: bold;margin-top:0px;color:red" class="text-center">(Não assinada Eletronicamente)</div>	
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
                       	 	<div class="text-right">Emitido por ' . session()->nomeExibicao . ' (CPF:' . substr(session()->cpf, 0, -6) . '*****' . ')</div>
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
	public function verificaTipo()
	{
		$response = array();
		$codAtendimentoPrescricao = $this->request->getPost('codAtendimentoPrescricao');

		//altera status das prescrições


		$dadosPrescricao = $this->AtendimentosPrescricoesModel->pegaPorCodAtendimentoPrescricao($codAtendimentoPrescricao);

		if ($dadosPrescricao->codTipoPrescricao == NULL or $dadosPrescricao->codTipoPrescricao == 0) {

			$response['faltaTipoPrescricao'] = 1;
			return $this->response->setJSON($response);
		} else {

			$response['faltaTipoPrescricao'] = 0;
			return $this->response->setJSON($response);
		}
	}
	public function assinatura()
	{
		$response = array();
		$codAtendimentoPrescricao = $this->request->getPost('codAtendimentoPrescricao');

		//altera status das prescrições


		$dadosPrescricao = $this->AtendimentosPrescricoesModel->pegaPorCodAtendimentoPrescricao($codAtendimentoPrescricao);

		$fieldsPrescricao['codStatus'] = 2;
		$fieldsConduta['assinadoPor'] = session()->codPessoa;
		$fieldsPrescricao['prescricaoAssinadaPor'] = session()->codPessoa;
		$fieldsPrescricao['dataAtualizacao'] = date('Y-m-d H:i');

		if ($this->validation->check($dadosPrescricao->codAtendimentoPrescricao, 'required|numeric')) {
			if ($this->AtendimentosPrescricoesModel->update($dadosPrescricao->codAtendimentoPrescricao, $fieldsPrescricao)) {
			}
		} else {
			$response['success'] = false;
			$response['messages'] = 'Violação na tentativa de assinatura com código vazio!';
			return $this->response->setJSON($response);
		}


		// MEDICAMENTOS

		$dadosMedicamentos = $this->PrescricaoMedicamentosModel->pegaPorcodAtendimentoPrescricao($codAtendimentoPrescricao);


		foreach ($dadosMedicamentos as $medicamento) {

			$fieldsMedicamentos['stat'] = 2;
			$fieldsMedicamentos['codAutor'] = session()->codPessoa;
			$fieldsMedicamentos['dataAtualizacao'] = date('Y-m-d H:i');


			//NÃO DEIXA ATUALIZAR SE CÓDIGO FOR NULO OU VAZIO
			if ($this->validation->check($medicamento->codPrescricaoMedicamento, 'required|numeric')) {
				if ($this->PrescricaoMedicamentosModel->update($medicamento->codPrescricaoMedicamento, $fieldsMedicamentos)) {
				}
			} else {
				$response['success'] = false;
				$response['messages'] = 'Violação na atualização de medicamentos com código vazio!!';
				return $this->response->setJSON($response);
			}
		}







		// KITS


		$dadosKits = $this->PrescricoesKitModel->pegaClonarKits($codAtendimentoPrescricao);


		foreach ($dadosKits as $kit) {
			$fieldsKits['codStatus'] = 2;
			$fieldsKits['codAutor'] = session()->codPessoa;
			$fieldsKits['dataAtualizacao'] = date('Y-m-d H:i');


			//NÃO DEIXA ATUALIZAR SE CÓDIGO FOR NULO OU VAZIO
			if ($this->validation->check($kit->codPrescricaoKit, 'required|numeric')) {
				if ($this->PrescricoesKitModel->update($kit->codPrescricaoKit, $fieldsKits)) {
				}
			} else {
				$response['success'] = false;
				$response['messages'] = 'Violação na tentativa de atualização de KIT com código vazio!';
				return $this->response->setJSON($response);
			}
		}





		// MATERIAIS

		$dadosMateriais = $this->PrescricoesMaterialModel->pegaClonarMateriais($codAtendimentoPrescricao);


		foreach ($dadosMateriais as $material) {
			$fieldsMaterial['codStatus'] = 2;
			$fieldsMaterial['codAutor'] = session()->codPessoa;
			$fieldsMaterial['dataAtualizacao'] = date('Y-m-d H:i');


			//NÃO DEIXA ATUALIZAR SE CÓDIGO FOR NULO OU VAZIO
			if ($this->validation->check($material->codPrescricaoMaterial, 'required|numeric')) {
				if ($this->PrescricoesMaterialModel->update($material->codPrescricaoMaterial, $fieldsMaterial)) {
				}
			} else {
				$response['success'] = false;
				$response['messages'] = 'Violação na tentativa de atualização de MATERIAL com código vazio!!';
				return $this->response->setJSON($response);
			}
		}







		// CUIDADOS


		$dadosCuidados = $this->PrescricoesCuidadosModel->pegaClonarMateriais($codAtendimentoPrescricao);


		foreach ($dadosCuidados as $cuidado) {
			$fieldsCuidados['codStatus'] = 2;
			$fieldsCuidados['codAutor'] = session()->codPessoa;
			$fieldsCuidados['dataAtualizacao'] = date('Y-m-d H:i');


			//NÃO DEIXA ATUALIZAR SE CÓDIGO FOR NULO OU VAZIO
			if ($this->validation->check($cuidado->codPrescricaoCuidado, 'required|numeric')) {
				if ($this->PrescricoesCuidadosModel->update($cuidado->codPrescricaoCuidado, $fieldsCuidados)) {
				}
			} else {
				$response['success'] = false;
				$response['messages'] = 'Violação na tentativa de atualização de CUIDADO com código vazio!!';
				return $this->response->setJSON($response);
			}
		}





		//OUTRAS PRESCRIÇÕES

		$dadosOutras = $this->PrescricoesOutrasModel->pegaClonarOutras($codAtendimentoPrescricao);


		foreach ($dadosOutras as $outra) {
			$fieldsOutras['codStatus'] = 2;
			$fieldsOutras['codAutor'] = session()->codPessoa;
			$fieldsOutras['dataAtualizacao'] = date('Y-m-d H:i');


			//NÃO DEIXA ATUALIZAR SE CÓDIGO FOR NULO OU VAZIO
			if ($this->validation->check($outra->codPrescricaoOutra, 'required|numeric')) {
				if ($this->PrescricoesOutrasModel->update($outra->codPrescricaoOutra, $fieldsOutras)) {
				}
			} else {
				$response['success'] = false;
				$response['messages'] = 'Violação na tentativa de atualização de OUTRA PRESCRIÇÃO com código vazio!!';
				return $this->response->setJSON($response);
			}
		}

		$response['success'] = true;
		$response['csrf_hash'] = csrf_hash();
		$response['messages'] = 'Prescrição assinada com sucesso!';
		return $this->response->setJSON($response);
	}

	public function clonarPrescricao()
	{
		$response = array();
		$codAtendimentoPrescricao = $this->request->getPost('codAtendimentoPrescricao');
		$codAtendimento = $this->request->getPost('codAtendimento');
		$medicamentos = $this->request->getPost('medicamentos');
		$kits = $this->request->getPost('kits');
		$materiais = $this->request->getPost('materiais');
		$cuidados = $this->request->getPost('cuidados');
		$dietas = $this->request->getPost('dietas');
		$procedimentos = $this->request->getPost('procedimentos');
		$outras = $this->request->getPost('outras');



		//CLONAR PRESCRIÇÃO

		$dadosPrescricao = $this->AtendimentosPrescricoesModel->pegaPorCodAtendimentoPrescricao($codAtendimentoPrescricao);


		$fieldsPrescricao['codAtendimento'] = $codAtendimento;
		$fieldsPrescricao['conteudoPrescricao'] = $dadosPrescricao->conteudoPrescricao;
		$fieldsPrescricao['codTipoPrescricao'] = $dadosPrescricao->codTipoPrescricao;
		if ($dietas == 'true') {
			$fieldsPrescricao['dieta'] = $dadosPrescricao->dieta;
		}

		$fieldsPrescricao['codLocalAtendimento'] = $dadosPrescricao->codLocalAtendimento;
		$fieldsPrescricao['codStatus'] = 1;
		$fieldsPrescricao['codAutor'] = session()->codPessoa;
		$fieldsPrescricao['dataInicio'] = date('Y-m-d');
		$fieldsPrescricao['dataEncerramento'] = date('Y-m-d', strtotime("+1 day", strtotime(date('Y-m-d'))));
		$fieldsPrescricao['dataCriacao'] = date('Y-m-d H:i');
		$fieldsPrescricao['dataAtualizacao'] = date('Y-m-d H:i');

		if ($novoCodAtendimentoPrescricao = $this->AtendimentosPrescricoesModel->insert($fieldsPrescricao)) {
		}




		//CLONAR MEDICAMENTOS

		if ($medicamentos == 'true') {

			$dadosMedicamentos = $this->PrescricaoMedicamentosModel->pegaPorcodAtendimentoPrescricaoClone($codAtendimentoPrescricao);


			foreach ($dadosMedicamentos as $medicamento) {

				$fieldsMedicamentos = array();

				$fieldsMedicamentos['codAtendimentoPrescricao'] = $novoCodAtendimentoPrescricao;
				$fieldsMedicamentos['codMedicamento'] = $medicamento->codMedicamento;
				$fieldsMedicamentos['qtde'] = $medicamento->qtde;
				$fieldsMedicamentos['und'] = $medicamento->und;
				$fieldsMedicamentos['via'] = $medicamento->via;
				$fieldsMedicamentos['freq'] = $medicamento->freq;
				$fieldsMedicamentos['per'] = $medicamento->per;
				$fieldsMedicamentos['dias'] = $medicamento->dias;
				$fieldsMedicamentos['horaIni'] = $medicamento->horaIni;
				$fieldsMedicamentos['agora'] = $medicamento->agora;
				$fieldsMedicamentos['risco'] = $medicamento->risco;
				$fieldsMedicamentos['obs'] = $medicamento->obs;
				$fieldsMedicamentos['apraza'] = $medicamento->apraza;
				$fieldsMedicamentos['total'] = $medicamento->total;
				$fieldsMedicamentos['stat'] = 1;
				$fieldsMedicamentos['codAutor'] = session()->codPessoa;
				$fieldsMedicamentos['dataCriacao'] = date('Y-m-d H:i');
				$fieldsMedicamentos['dataAtualizacao'] = date('Y-m-d H:i');


				if ($this->PrescricaoMedicamentosModel->insert($fieldsMedicamentos)) {
				}
			}
		}






		//CLONAR KITS

		if ($kits == 'true') {

			$dadosKits = $this->PrescricoesKitModel->pegaClonarKits($codAtendimentoPrescricao);


			foreach ($dadosKits as $kit) {

				$fieldsKits['codAtendimentoPrescricao'] = $novoCodAtendimentoPrescricao;
				$fieldsKits['codKit'] = $kit->codKit;
				$fieldsKits['qtde'] = $kit->qtde;
				$fieldsKits['codStatus'] = 1;
				$fieldsKits['observacao'] = $kit->observacao;
				$fieldsKits['codAutor'] = session()->codPessoa;
				$fieldsKits['dataCriacao'] = date('Y-m-d H:i');
				$fieldsKits['dataAtualizacao'] = date('Y-m-d H:i');
				if ($this->PrescricoesKitModel->insert($fieldsKits)) {
				}
			}
		}




		//CLONAR MATERIAIS

		if ($materiais == 'true') {

			$dadosMateriais = $this->PrescricoesMaterialModel->pegaClonarMateriais($codAtendimentoPrescricao);


			foreach ($dadosMateriais as $material) {

				$fieldsMaterial['codAtendimentoPrescricao'] = $novoCodAtendimentoPrescricao;
				$fieldsMaterial['codMaterial'] = $material->codMaterial;
				$fieldsMaterial['qtde'] = $material->qtde;
				$fieldsMaterial['codStatus'] = 1;
				$fieldsMaterial['observacao'] = $material->observacao;
				$fieldsMaterial['codAutor'] = session()->codPessoa;
				$fieldsMaterial['dataCriacao'] = date('Y-m-d H:i');
				$fieldsMaterial['dataAtualizacao'] = date('Y-m-d H:i');

				if ($this->PrescricoesMaterialModel->insert($fieldsMaterial)) {
				}
			}
		}






		//CLONAR CUIDADOS

		if ($cuidados == 'true') {

			$dadosCuidados = $this->PrescricoesCuidadosModel->pegaClonarMateriais($codAtendimentoPrescricao);


			foreach ($dadosCuidados as $cuidado) {

				$fieldsCuidados['codAtendimentoPrescricao'] = $novoCodAtendimentoPrescricao;
				$fieldsCuidados['codTipoCuidadoPrescricao'] = $cuidado->codTipoCuidadoPrescricao;
				$fieldsCuidados['descricao'] = $cuidado->descricao;
				$fieldsCuidados['codStatus'] = 1;
				$fieldsCuidados['apraza'] = $cuidado->apraza;
				$fieldsCuidados['codAutor'] = session()->codPessoa;
				$fieldsCuidados['dataCriacao'] = date('Y-m-d H:i');
				$fieldsCuidados['dataAtualizacao'] = date('Y-m-d H:i');

				if ($this->PrescricoesCuidadosModel->insert($fieldsCuidados)) {
				}
			}
		}

		//CLONAR PROCEDIMENTOS

		if ($procedimentos == 'true') {

			$dadosProcedimentos = $this->PrescricaoProcedimentoModel->pegaClonarProcedimentos($codAtendimentoPrescricao);


			foreach ($dadosProcedimentos as $procedimento) {

				$fieldsProcedimento['codAtendimentoPrescricao'] = $novoCodAtendimentoPrescricao;
				$fieldsProcedimento['codProcedimento'] = $procedimento->codProcedimento;
				$fieldsProcedimento['qtde'] = $procedimento->qtde;
				$fieldsProcedimento['codStatus'] = 1;
				$fieldsProcedimento['observacao'] = $procedimento->observacao;
				$fieldsProcedimento['codAutor'] = session()->codPessoa;
				$fieldsProcedimento['dataCriacao'] = date('Y-m-d H:i');
				$fieldsProcedimento['dataAtualizacao'] = date('Y-m-d H:i');

				if ($this->PrescricaoProcedimentoModel->insert($fieldsProcedimento)) {
				}
			}
		}



		//CLONAR OUTRAS PRESCRIÇÕES

		if ($outras == 'true') {

			$dadosOutras = $this->PrescricoesOutrasModel->pegaClonarOutras($codAtendimentoPrescricao);


			foreach ($dadosOutras as $outra) {

				$fieldsOutras['codAtendimentoPrescricao'] = $novoCodAtendimentoPrescricao;
				$fieldsOutras['codTipoOutraPrescricao'] = $outra->codTipoOutraPrescricao;
				$fieldsOutras['descricao'] = $outra->descricao;
				$fieldsOutras['codStatus'] = 1;
				$fieldsOutras['apraza'] = $outra->apraza;
				$fieldsOutras['codAutor'] = session()->codPessoa;
				$fieldsOutras['dataCriacao'] = date('Y-m-d H:i');
				$fieldsOutras['dataAtualizacao'] = date('Y-m-d H:i');

				if ($this->PrescricoesOutrasModel->insert($fieldsOutras)) {
				}
			}
		}










		$response['success'] = true;
		$response['csrf_hash'] = csrf_hash();
		$response['messages'] = 'Clone realizado com sucesso!';
		return $this->response->setJSON($response);
	}


	public function listaDropDownTipoPrescricao()
	{

		$result = $this->AtendimentosPrescricoesModel->listaDropDownTipoPrescricao();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}



	public function processarDispensacao()
	{

		$response = array();

		$fields['codAtendimentoPrescricao'] = $this->request->getPost('codAtendimentoPrescricao');

		$dadosPrescricao = $this->AtendimentosPrescricoesModel->pegaPorCodAtendimentoPrescricao($fields['codAtendimentoPrescricao']);



		$response['dadosPaciente'] = 'Paciente: ' . $dadosPrescricao->nomePaciente . ' , Idade: ' . $dadosPrescricao->idade . ' anos, ' . $dadosPrescricao->abreviacaoDepartamento . ' (' . $dadosPrescricao->descricaoLocalAtendimento . ')';
		$response['qtdeMedicamentos'] = 'Medicamentos (' . $this->AtendimentosPrescricoesModel->pegaQtdeMedicamentosPrescricao($fields['codAtendimentoPrescricao'])->total . ')';
		$response['qtdeMateriais'] = 'Materiais (' . $this->AtendimentosPrescricoesModel->pegaQtdeMateriaisPrescricao($fields['codAtendimentoPrescricao'])->total . ')';


		if ($dadosPrescricao->codStatus < 3) {

			$fieldsPrescricao['codStatus'] = 3;
			$fieldsPrescricao['codAutorDispensacao'] = session()->codPessoa;
			$fieldsPrescricao['dataAtualizacao'] = date('Y-m-d H:i');

			if ($this->validation->check($fields['codAtendimentoPrescricao'], 'required|numeric')) {
				if ($this->AtendimentosPrescricoesModel->update($fields['codAtendimentoPrescricao'], $fieldsPrescricao)) {
					$response['success'] = true;
					$response['messages'] = 'Processamento Iniciado';
					return $this->response->setJSON($response);
				}
			} else {
				$response['success'] = false;
				$response['messages'] = $this->validation->listErrors();
			}
		}

		return $this->response->setJSON($response);
	}


	public function assinarDispensacao()
	{

		$response = array();

		$fields['codAtendimentoPrescricao'] = $this->request->getPost('codAtendimentoPrescricao');

		if ($this->validation->check($fields['codAtendimentoPrescricao'], 'required|numeric')) {

			//$dadosPrescricao = $this->AtendimentosPrescricoesModel->pegaPorCodAtendimentoPrescricao($fields['codAtendimentoPrescricao']);


			$fieldsPrescricao['codStatus'] = 4;
			$fieldsPrescricao['codAutorDispensacao'] = session()->codPessoa;
			$fieldsPrescricao['dispensacaoAssinadaPor'] = session()->codPessoa;
			$fieldsPrescricao['dataAtualizacao'] = date('Y-m-d H:i');


			if ($this->AtendimentosPrescricoesModel->update($fields['codAtendimentoPrescricao'], $fieldsPrescricao)) {
				$response['success'] = true;
				$response['messages'] = 'Dispensação assinada';
				return $this->response->setJSON($response);
			}
		} else {
			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
			return $this->response->setJSON($response);
		}
	}



	public function salvaPrescricao()
	{
		$response = array();
		$prescricao = $this->request->getPost('prescricao');
		$codTipoPrescricao = $this->request->getPost('codTipoPrescricao');
		$dieta = $this->request->getPost('dieta');
		$dataInicio = $this->request->getPost('dataInicio');
		$dataEncerramento = $this->request->getPost('dataEncerramento');
		$codAtendimento = $this->request->getPost('codAtendimento');
		$codAtendimentoPrescricao = $this->request->getPost('codAtendimentoPrescricao');
		$codLocalAtendimento = $this->request->getPost('codLocalAtendimento');


		if ($dataInicio == null or $dataInicio == '' or $dataInicio == '0000-00-00' or $dataEncerramento == null or $dataEncerramento == '' or $dataEncerramento == '0000-00-00') {

			$response['success'] = false;
			$response['messages'] = 'Você deve informar a duração da prescrição';
			return $this->response->setJSON($response);
		}

		//$verificaExistencia = $this->AtendimentosPrescricoesModel->pegaPorCodigo($codAtendimento);

		if ($codAtendimentoPrescricao == NULL) {
			//INSERT
			$fields['codAtendimento'] = $codAtendimento;
			$fields['codLocalAtendimento'] = $codLocalAtendimento;
			$fields['codOrganizacao'] = session()->codOrganizacao;
			$fields['conteudoPrescricao'] = $prescricao;
			$fields['codTipoPrescricao'] = $codTipoPrescricao;
			$fields['dieta'] = $dieta;
			$fields['codStatus'] = 1;
			$fields['codAutor'] = session()->codPessoa;
			$fields['dataInicio'] = $dataInicio;
			$fields['dataEncerramento'] = $dataEncerramento;
			$fields['dataCriacao'] = date('Y-m-d H:i');
			$fields['dataAtualizacao'] = date('Y-m-d H:i');

			if ($codAtendimentoPrescricao = $this->AtendimentosPrescricoesModel->insert($fields)) {

				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['codAtendimentoPrescricao'] = $codAtendimentoPrescricao;
				$response['messages'] = 'Informação inserida com sucesso';
			}
		} else {
			//UPDATE

			$fields['codAtendimento'] = $codAtendimento;
			$fields['conteudoPrescricao'] = $prescricao;
			$fields['codTipoPrescricao'] = $codTipoPrescricao;
			$fields['dieta'] = $dieta;
			$fields['codStatus'] = 1;
			$fields['codAutor'] = session()->codPessoa;
			$fields['dataInicio'] = $dataInicio;
			$fields['dataEncerramento'] = $dataEncerramento;
			$fields['dataAtualizacao'] = date('Y-m-d H:i');


			//NÃO DEIXA ATUALIZAR SE CÓDIGO FOR NULO OU VAZIO
			if ($this->validation->check($codAtendimentoPrescricao, 'required|numeric')) {

				if ($this->AtendimentosPrescricoesModel->update($codAtendimentoPrescricao, $fields)) {

					$response['success'] = true;
					$response['csrf_hash'] = csrf_hash();
					$response['codAtendimentoPrescricao'] = $codAtendimentoPrescricao;
					$response['messages'] = 'Prescrição atualizada com sucesso';
					return $this->response->setJSON($response);
				}
			} else {
				$response['success'] = false;
				$response['messages'] = 'Erro na operação!';
				return $this->response->setJSON($response);
			}
		}



		$response['success'] = true;
		$response['csrf_hash'] = csrf_hash();
		$response['messages'] = 'Prescrição Iniciada. Cadastre os demais itens da prescrição';




		return $this->response->setJSON($response);
	}

	public function add()
	{

		$response = array();

		$fields['codAtendimentoPrescricao'] = $this->request->getPost('codAtendimentoPrescricao');
		$fields['codAtendimento'] = $this->request->getPost('codAtendimento');
		$fields['codStatus'] = $this->request->getPost('codStatus');
		$fields['conteudoPrescricao'] = $this->request->getPost('conteudoPrescricao');
		$fields['impresso'] = $this->request->getPost('impresso');
		$fields['codAutor'] = $this->request->getPost('codAutor');
		$fields['dataCriacao'] = $this->request->getPost('dataCriacao');


		$this->validation->setRules([
			'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'codStatus' => ['label' => 'Status', 'rules' => 'required|numeric|max_length[11]'],
			'conteudoPrescricao' => ['label' => 'ConteudoPrescricao', 'rules' => 'permit_empty'],
			'impresso' => ['label' => 'Impresso', 'rules' => 'required|numeric|max_length[11]'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtendimentosPrescricoesModel->insert($fields)) {

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

		$fields['codAtendimentoPrescricao'] = $this->request->getPost('codAtendimentoPrescricao');
		$fields['codAtendimento'] = $this->request->getPost('codAtendimento');
		$fields['codStatus'] = $this->request->getPost('codStatus');
		$fields['conteudoPrescricao'] = $this->request->getPost('conteudoPrescricao');
		$fields['impresso'] = $this->request->getPost('impresso');
		$fields['codAutor'] = $this->request->getPost('codAutor');
		$fields['dataCriacao'] = $this->request->getPost('dataCriacao');


		$this->validation->setRules([
			'codAtendimentoPrescricao' => ['label' => 'codAtendimentoPrescricao', 'rules' => 'required|numeric'],
			'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'codStatus' => ['label' => 'Status', 'rules' => 'required|numeric|max_length[11]'],
			'conteudoPrescricao' => ['label' => 'ConteudoPrescricao', 'rules' => 'permit_empty'],
			'impresso' => ['label' => 'Impresso', 'rules' => 'required|numeric|max_length[11]'],
			'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->AtendimentosPrescricoesModel->update($fields['codAtendimentoPrescricao'], $fields)) {

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

	public function remove()
	{
		$response = array();

		$id = $this->request->getPost('codAtendimentoPrescricao');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->AtendimentosPrescricoesModel->where('codAtendimentoPrescricao', $id)->delete()) {


				//remover itens Relacionados
				$this->AtendimentosPrescricoesModel->removeItensRelacionados($id);

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
