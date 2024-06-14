<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\PacientesModel;
use App\Models\OrganizacoesModel;
use App\Models\CategoriasSuporteModel;
use App\Models\AgendamentosModel;

use App\Models\StatusSuporteModel;
use App\Models\PreferenciasModel;

use App\Models\AcaoSuporteModel;
use App\Models\TotenModel;
use App\Models\SolicitacoesSuporteModel;
use App\Models\AtendimentoSenhasModel;

class Toten extends BaseController
{

    protected $solicitacoesSuporteModel;
    protected $pessoasModel;
    protected $organizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

    public function __construct()
    {

        $this->TotenModel = new TotenModel();
        $this->AtendimentoSenhasModel = new AtendimentoSenhasModel();
        $this->AgendamentosModel = new AgendamentosModel();
        $this->PessoasModel = new PessoasModel();
        $this->PacientesModel = new PacientesModel();
        $this->SolicitacoesSuporteModel = new SolicitacoesSuporteModel();
        $this->OrganizacoesModel = new OrganizacoesModel();
        $this->StatusSuporteModel = new StatusSuporteModel();
        $this->PreferenciasModel = new PreferenciasModel();
        $this->AcaoSuporte = new AcaoSuporteModel();
        $this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
        $this->validation =  \Config\Services::validation();

        $configuracao = config('App');
        session()->set('codOrganizacao', $configuracao->codOrganizacao);
        $codOrganizacao = $configuracao->codOrganizacao;
        $dadosOrganizacao = $this->OrganizacoesModel->pegaDadosBasicosOrganizacao($codOrganizacao);

        session()->set('descricaoOrganizacao', $dadosOrganizacao->descricao);
        session()->set('logo', $dadosOrganizacao->logo);
    }

    public function index()
    {

        return view('toten');
    }



    public function procuraPessoa()
    {

        $response = array();

        $cpf = removeCaracteresIndesejados($this->request->getPost('cpf'));
        $paciente = $this->PacientesModel->pegaPacientePorcpf($cpf);

        if ($paciente->nomeExibicao == NULL) {
            $nomePaciente = 'Dados Não encontrados';
        } else {
            $nomePaciente = $paciente->nomeExibicao;
        }

        $fotoPerfil = base_url() . "/arquivos/imagens/pacientes/" . $paciente->fotoPerfil;

        if ($paciente->idade >= 60) {
            $checked = "checked";
        } else {
            $checked = "";
        }
        $html = "";

        $html .= '
		<div class="row">
		<input type="hidden" id="codPaciente" name="codPaciente" value="' . $paciente->codPaciente . '" >
		<input type="hidden" id="idade"  name="idade" value="' . $paciente->idade . '" >
		<input type="hidden" id="cpf" name="cpf" value="' . $cpf . '" >
		<input type="hidden" id="fotoPerfil" name="fotoPerfil" value="' . $paciente->fotoPerfil . '" >
		<input type="hidden" id="nomePaciente" name="nomePaciente" value="' . $paciente->nomeExibicao . '" >
			<div class="col-md-12">
								<div class="card card-widget widget-user shadow">
									<!-- Add the bg color to the header using any of the bg-* classes -->
									<div style="background:#56ff0052;height:200px" class="widget-user-header">
										<h3 style="font-size:30px !important;margin-bottom:10px;font-weight: bold;" class="widget-user-username">' . $nomePaciente . '</h3>
                                        <style>
                                        .borda {
                                            background: -webkit-linear-gradient(left top, #fffe01 0%, #28a745 100%);
                                            border-radius: 1000px;
                                            padding: 6px;
                                            width: 100px;
                                            height: 100px;
                            
                                        }
                                        </style>
                                        <img style="width:100px" class="borda" alt="" src="' . $fotoPerfil . '">

									</div>
									<div class="card-footer">
										<div class="row">
											<div class="col-sm-6 border-right">
												<div style="font-size:14px;font-weight: bold; color:green" class="description-block">
													<h5 class="description-header">
														<h1 class="widget-user-desc">IDADE:' . $paciente->idade . '</h1>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="description-block">
													<h5 class="description-header">
														<div style="font-size:14px;font-weight: bold ; color:green">					
															<div style="font-size:14px;">
															<div class="form-group">
													<label for="checkboxPrioridade"><h1>Prioridade: </h1></label>
													<div class="icheck-primary d-inline">
														<style>
															input[type=checkbox] {
																transform: scale(1.8);
															}
														</style>
														<input style="margin-left:5px;" name="prioridade" ' . $checked . ' type="checkbox" id="checkboxPrioridade">


													</div>
												</div>
															</div>
														</div>
													</h5>
												</div>
											</div>
										</div>
									</div>
								</div>
								</div>
							</div>
		
		';

        $response['success'] = true;
        $response['csrf_hash'] = csrf_hash();
        $response['html'] = $html;

        sleep(4);
        return $this->response->setJSON($response);
    }

    public function PainelSenhasFilaPrioridades()
    {
        $response = array();



        $data['data'] = array();


        $Departamentos = $this->request->getPost('Departamentos');
        $result = $this->TotenModel->filaPrioridades($Departamentos);

        foreach ($result as $key => $value) {



            if ($value->dataProtocolo !== NULL) {
                $horaChegada = date('H:i', strtotime($value->dataProtocolo));
                $tempoAtendimento = intervaloTempoHoraMinutos($value->dataProtocolo, date('Y-m-d H:i'));
            } else {
                $horaChegada = "";
                $tempoAtendimento  = "";
            }

            $data['data'][$key] = array(
                $value->senha,
                $horaChegada,
                $tempoAtendimento,
            );
        }

        return $this->response->setJSON($data);
    }


    public function PainelSenhasFilaNormal()
    {
        $response = array();



        $data['data'] = array();


        $Departamentos = $this->request->getPost('Departamentos');
        $result = $this->TotenModel->filaNormal($Departamentos);

        foreach ($result as $key => $value) {



            if ($value->dataProtocolo !== NULL) {
                $horaChegada = date('H:i', strtotime($value->dataProtocolo));
                $tempoAtendimento = intervaloTempoHoraMinutos($value->dataProtocolo, date('Y-m-d H:i'));
            } else {
                $horaChegada = "";
                $tempoAtendimento  = "";
            }

            $data['data'][$key] = array(
                $value->senha,
                $horaChegada,
                $tempoAtendimento,
            );
        }

        return $this->response->setJSON($data);
    }


    public function painelSenhas()
    {
        $response = array();



        $data['data'] = array();


        $Departamentos = $this->request->getPost('Departamentos');
        $result = $this->TotenModel->marcados($Departamentos);

        foreach ($result as $key => $value) {



            if ($value->horaChegada !== NULL) {
                $horaChegada = date('H:i', strtotime($value->horaChegada));
                $tempoAtendimento = intervaloTempoHoraMinutos($value->horaChegada, date('Y-m-d H:i'));
            } else {
                $horaChegada = "";
                $tempoAtendimento  = "";
            }


            if ($value->chegou == 1) {
                $statusChegou = '<i class="fa fa-thumbs-up text-center" style="font-size:30px;color:#098f09"></i>';
            } else {
                $statusChegou = "-";
            }

            if ($value->confirmou == 1) {
                $statusConfirmou = '<i class="fa fa-thumbs-up text-center" style="font-size:30px;color:#098f09"></i>';
            } else {
                $statusConfirmou = "-";
            }

            if ($value->dataInicio !== $value->dataEncerramento) {
                $periodo = date('d/m', strtotime($value->dataInicio)) . " das " . date('H:i', strtotime($value->dataInicio)) . " às " . date('H:i', strtotime($value->dataEncerramento));
            } else {
                $periodo = date('d/m', strtotime($value->dataInicio)) . " às " . date('H:i', strtotime($value->dataInicio));
            }
            $fotoPerfil = '<img  alt="" style="width:30px" id="fotoPerfilBarraSuperior" src="' . base_url() . '/arquivos/imagens/pacientes/' . $value->fotoPerfil . '" class="img-circle elevation-2">';

            if ($value->nomeEspecialista !== NULL) {
                $especialidade = $value->descricaoEspecialidade . " (" . $value->nomeEspecialista . ")";
            } else {
                $especialidade = $value->descricaoEspecialidade;
            }


            $data['data'][$key] = array(
                $fotoPerfil . " " . $value->nomePaciente . " (" . $value->idade . ")",
                $especialidade,
                $periodo,
                $horaChegada,
                $tempoAtendimento,
            );
        }

        return $this->response->setJSON($data);
    }



    public function listaDropDownDepartamentos()
    {

        $result = $this->TotenModel->listaDropDownDepartamentos();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function verificaChamadas($Departamentos = null)
    {
        if ($Departamentos  == NULL) {
            $Departamentos =  $this->request->getPost('Departamentos');
        }
        $arrayDepartamentos = json_decode($Departamentos);
        $Departamentos = removeBraketsJson($Departamentos);

        $data['data'] = array();

        $chamados = $this->TotenModel->senhaChamada($Departamentos);


        if (!empty($chamados) and in_array($chamados->codDepartamento, $arrayDepartamentos)) {

            $tratamento = "";
            /*
            if ($chamados->sexo == 'M') {
                $tratamento = "SENHOR";
            }

            if ($chamados->sexo == 'F') {
                $tratamento = "SENHORA";
            }

            */
            $data['success'] = true;
            $data['sala'] = $chamados->localAtendimento;
            $data['pacienteModal'] = '             
             
             <img  alt="" style="width:300px" src="' . base_url() . '/arquivos/imagens/pacientes/' . $chamados->fotoPerfil . '" class="img-circle elevation-2">
			    <div style="font-weight: bold; font-size:80px">' . $chamados->nomeCompleto . '<div>
                <div style="font-weight: bold; font-size:80px"> SENHA ' . $chamados->senha . '<div>
                <div>' . $chamados->localAtendimento . '<div>';


            $data['textoLeitura'] = $tratamento . " " . $chamados->nomeCompleto . ' senha ' . $chamados->senha . ", " . $data['sala'];


            if ($chamados->qtdChamadas <= 1) {
                $this->TotenModel->where('codChamada', $chamados->codChamada)->delete();
            } else {
                $data['qtdChamadas'] = $chamados->qtdChamadas - 1;

                if ($chamados->codChamada !== NULL and $chamados->codChamada !== "" and $chamados->codChamada !== " ") {
                    $this->TotenModel->update($chamados->codChamada, $data);
                }
            }


            return $this->response->setJSON($data);
        } else {
            $data['success'] = false;
            return $this->response->setJSON($data);
        }
    }
}
