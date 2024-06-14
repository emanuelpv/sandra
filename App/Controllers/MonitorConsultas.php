<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;
use App\Models\CategoriasSuporteModel;
use App\Models\StatusSuporteModel;
use App\Models\PreferenciasModel;
use App\Models\AcaoSuporteModel;
use App\Models\SolicitacoesSuporteModel;





use App\Models\MonitorConsultasModel;





class MonitorConsultas extends BaseController
{

    protected $SolicitacoesSuporteModel;
    protected $pessoasModel;
    protected $organizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

    public function __construct()
    {

        $this->PessoasModel = new PessoasModel();
        $this->SolicitacoesSuporteModel = new SolicitacoesSuporteModel();
        $this->OrganizacoesModel = new OrganizacoesModel();
        $this->StatusSuporteModel = new StatusSuporteModel();
        $this->PreferenciasModel = new PreferenciasModel();
        $this->AcaoSuporte = new AcaoSuporteModel();
        $this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
        $this->validation =  \Config\Services::validation();

        $this->MonitorConsultasModel = new MonitorConsultasModel();
    }

    public function index()
    {

        return view('monitorConsultas');
    }


    public function totalAgendamentos()
    {
        $response = array();
        $totais = $this->MonitorConsultasModel->totalAgendamentos();

        return $this->response->setJSON($totais);
    }

 

    public function agendamentosSemana()
    {
        $response = array();

        $data['data'] = array();

        $agendamentos = $this->MonitorConsultasModel->agendamentosSemana();

        $diaSemana = array('Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta');


        $segundaInternet = 0;
        $segundaSame = 0;
        $tercaInternet = 0;
        $tercaSame = 0;
        $quartaInternet = 0;
        $quartaSame = 0;
        $quintaInternet = 0;
        $quintaSame = 0;
        $sextaInternet = 0;
        $sextaSame = 0;


        foreach ($agendamentos as $agendamento) {

            //SEGUNDA
            if ($agendamento->diaSemana == 1) {

                if ($agendamento->codTipoAgendamento == 1) {
                    $segundaInternet++;
                }

                if ($agendamento->codTipoAgendamento == 4) {
                    $segundaSame++;
                }
            }

            //TERCA
            if ($agendamento->diaSemana == 2) {

                if ($agendamento->codTipoAgendamento == 1) {
                    $tercaInternet++;
                }

                if ($agendamento->codTipoAgendamento == 4) {
                    $tercaSame++;
                }
            }


            //QUARTA
            if ($agendamento->diaSemana == 3) {

                if ($agendamento->codTipoAgendamento == 1) {
                    $quartaInternet++;
                }

                if ($agendamento->codTipoAgendamento == 4) {
                    $quartaSame++;
                }
            }

            //  QUINTA
            if ($agendamento->diaSemana == 4) {

                if ($agendamento->codTipoAgendamento == 1) {
                    $quintaInternet++;
                }

                if ($agendamento->codTipoAgendamento == 4) {
                    $quintaSame++;
                }
            }

            //  SEXTA
            if ($agendamento->diaSemana == 5) {

                if ($agendamento->codTipoAgendamento == 1) {
                    $sextaInternet++;
                }

                if ($agendamento->codTipoAgendamento == 4) {
                    $sextaSame++;
                }
            }


            if ($segundaInternet == 0) {
                $segundaInternet = NULL;
            }
            if ($segundaSame == 0) {
                $segundaSame = NULL;
            }


            if ($tercaInternet == 0) {
                $tercaInternet = NULL;
            }
            if ($tercaSame == 0) {
                $tercaSame = NULL;
            }


            if ($quartaInternet == 0) {
                $quartaInternet = NULL;
            }
            if ($quartaSame == 0) {
                $quartaSame = NULL;
            }


            if ($quintaInternet == 0) {
                $quintaInternet = NULL;
            }
            if ($quintaSame == 0) {
                $quintaSame = NULL;
            }
            if ($sextaInternet == 0) {
                $sextaInternet = NULL;
            }
            if ($sextaSame == 0) {
                $sextaSame = NULL;
            }
        }
        $agendamentosInternet = array($segundaInternet, $tercaInternet, $quartaInternet, $quintaInternet, $sextaInternet);
        $agendamentosSame = array($segundaSame, $tercaSame, $quartaSame, $quintaSame, $sextaSame);

        $data['diaSemana'] = json_encode($diaSemana);
        $data['agendamentosInternet'] = json_encode($agendamentosInternet);
        $data['agendamentosSame'] = json_encode($agendamentosSame);

        return $this->response->setJSON($data);
    }


    
    public function agendamentosSemanaAnterior()
    {
        $response = array();

        $data['data'] = array();

        $agendamentos = $this->MonitorConsultasModel->agendamentosSemanaAnterior();

        $diaSemana = array('Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta');


        $segundaInternet = 0;
        $segundaSame = 0;
        $tercaInternet = 0;
        $tercaSame = 0;
        $quartaInternet = 0;
        $quartaSame = 0;
        $quintaInternet = 0;
        $quintaSame = 0;
        $sextaInternet = 0;
        $sextaSame = 0;


        foreach ($agendamentos as $agendamento) {

            //SEGUNDA
            if ($agendamento->diaSemana == 1) {

                if ($agendamento->codTipoAgendamento == 1) {
                    $segundaInternet++;
                }

                if ($agendamento->codTipoAgendamento == 4) {
                    $segundaSame++;
                }
            }

            //TERCA
            if ($agendamento->diaSemana == 2) {

                if ($agendamento->codTipoAgendamento == 1) {
                    $tercaInternet++;
                }

                if ($agendamento->codTipoAgendamento == 4) {
                    $tercaSame++;
                }
            }


            //QUARTA
            if ($agendamento->diaSemana == 3) {

                if ($agendamento->codTipoAgendamento == 1) {
                    $quartaInternet++;
                }

                if ($agendamento->codTipoAgendamento == 4) {
                    $quartaSame++;
                }
            }

            //  QUINTA
            if ($agendamento->diaSemana == 4) {

                if ($agendamento->codTipoAgendamento == 1) {
                    $quintaInternet++;
                }

                if ($agendamento->codTipoAgendamento == 4) {
                    $quintaSame++;
                }
            }

            //  SEXTA
            if ($agendamento->diaSemana == 5) {

                if ($agendamento->codTipoAgendamento == 1) {
                    $sextaInternet++;
                }

                if ($agendamento->codTipoAgendamento == 4) {
                    $sextaSame++;
                }
            }


            if ($segundaInternet == 0) {
                $segundaInternet = NULL;
            }
            if ($segundaSame == 0) {
                $segundaSame = NULL;
            }


            if ($tercaInternet == 0) {
                $tercaInternet = NULL;
            }
            if ($tercaSame == 0) {
                $tercaSame = NULL;
            }


            if ($quartaInternet == 0) {
                $quartaInternet = NULL;
            }
            if ($quartaSame == 0) {
                $quartaSame = NULL;
            }


            if ($quintaInternet == 0) {
                $quintaInternet = NULL;
            }
            if ($quintaSame == 0) {
                $quintaSame = NULL;
            }
            if ($sextaInternet == 0) {
                $sextaInternet = NULL;
            }
            if ($sextaSame == 0) {
                $sextaSame = NULL;
            }
        }
        $agendamentosInternet = array($segundaInternet, $tercaInternet, $quartaInternet, $quintaInternet, $sextaInternet);
        $agendamentosSame = array($segundaSame, $tercaSame, $quartaSame, $quintaSame, $sextaSame);

        $data['diaSemana'] = json_encode($diaSemana);
        $data['agendamentosInternet'] = json_encode($agendamentosInternet);
        $data['agendamentosSame'] = json_encode($agendamentosSame);

        return $this->response->setJSON($data);
    }


    public function maisMarcadas()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->MonitorConsultasModel->maisMarcadas();

        $especialidades = array();
        $totais = array();
        $totalGeral = 0;

        foreach ($result as $row1) {
            $totalGeral = $totalGeral + $row1->totalAgendamentos;
        }

        foreach ($result as $row) {
            if ($totalGeral > 0) {
                array_push($totais, round($row->totalAgendamentos / $totalGeral * 100));
            }
            array_push($especialidades, $row->especialidade);
        }

        $data['especialidades'] = json_encode($especialidades);
        $data['totais'] = json_encode($totais);

        return $this->response->setJSON($data);
    }




    public function vagasAbertas()
    {
        $response = array();

        $data['data'] = array();

        $vagasAbertasInternet = $this->MonitorConsultasModel->vagasAbertasInternet();
        $vagasAbertasPresencial = $this->MonitorConsultasModel->vagasAbertasPresencial();


        $vagasInternet = '
        
        <div class="row">
        ';
        foreach ($vagasAbertasInternet as $vagaAbertaInternet) {

            $vagasInternet .= '
            <div class="col-md-4">
                <button style="width:100%; margin-top:10px;" class="btn btn-success">
                  <div>' . $vagaAbertaInternet->descricaoEspecialidade . '</div>
                  <div>(' . $vagaAbertaInternet->vagasAbertas . ')</div>
                </button>
            </div>';
        }
        $vagasInternet .= '</div>';




        $vagasPresencial = '
        
        <div class="row">
        ';
        foreach ($vagasAbertasPresencial as $vagaAbertaPresencial) {

            $vagasPresencial .= '
            <div class="col-md-4">
                <button style="width:100%; margin-top:10px;" class="btn btn-primary">
                  <div>' . $vagaAbertaPresencial->descricaoEspecialidade . '</div>
                  <div>(' . $vagaAbertaPresencial->vagasAbertas . ')</div>
                </button>
            </div>';
        }
        $vagasPresencial .= '</div>';




        $response['vagasInternet'] = $vagasInternet;
        $response['vagasPresencial'] = $vagasPresencial;

        return $this->response->setJSON($response);
    }
}
