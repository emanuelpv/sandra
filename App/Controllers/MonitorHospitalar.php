<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;
use App\Models\CategoriasSuporteModel;
use App\Models\MonitorHospitalarModel;

use App\Models\StatusSuporteModel;
use App\Models\PreferenciasModel;

use App\Models\AcaoSuporteModel;
use App\Models\SolicitacoesSuporteModel;

class MonitorHospitalar extends BaseController
{

    protected $solicitacoesSuporteModel;
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
        $this->MonitorHospitalarModel = new MonitorHospitalarModel();
        $this->AcaoSuporte = new AcaoSuporteModel();
        $this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
        $this->validation =  \Config\Services::validation();
    }

    public function index()
    {

        return view('monitorHospitalar');
    }

    public function ocupacao()
    {

        $response = array();

        $data['data'] = array();

        $dadosOcupacaoLeitos = dadosOcupacaoLeitos();
        $data['totalLeitos'] = 0;
        $data['totalLeitosOcupados'] = 0;
        $data['totalLeitosLivres'] = 0;
        $data['totalLeitosEmManutencao'] = 0;

        if ($dadosOcupacaoLeitos['totalLeitos'] >= 0) {
            $data['totalLeitos'] = $dadosOcupacaoLeitos['totalLeitos'];
        }

        if ($dadosOcupacaoLeitos['totalLeitosOcupados'] >= 0) {
            $data['totalLeitosOcupados'] = $dadosOcupacaoLeitos['totalLeitosOcupados'];
            $data['totalLeitosLivres'] = $data['totalLeitos'] - $data['totalLeitosOcupados'];
        }
        $data['totalLeitosEmManutencao'] = $dadosOcupacaoLeitos['totalLeitosEmManutencao'];


        return $this->response->setJSON($data);
    }

    public function solicitacoesInfraEmAberto()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->SolicitacoesSuporteModel->solicitacoesInfraEmAberto();

        foreach ($result as $key => $value) {

            $ops = '<div">';

            $data['data'][$key] = array(
                $value->codSolicitacao,
                $value->descricaoSolicitacao,
                $value->descricaoCategoriaSuporte,
                $value->nomeExibicao,
                $value->descricaoDepartamento,
                $value->ResponsavelTecnico,
                date('d/m/Y H:i', strtotime($value->dataCriacao)),
                $value->descricaoStatusSuporte,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }


    public function solicitacoesSistemasEmAberto()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->SolicitacoesSuporteModel->solicitacoesSistemasEmAberto();

        foreach ($result as $key => $value) {

            $ops = '<div">';


            $data['data'][$key] = array(
                $value->codSolicitacao,
                $value->descricaoSolicitacao,
                $value->descricaoCategoriaSuporte,
                $value->nomeExibicao,
                $value->descricaoDepartamento,
                $value->ResponsavelTecnico,
                date('d/m/Y H:i', strtotime($value->dataCriacao)),
                $value->descricaoStatusSuporte,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }


    public function solicitacoesTelefoniaEmAberto()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->SolicitacoesSuporteModel->solicitacoesTelefoniaEmAberto();

        foreach ($result as $key => $value) {

            $ops = '<div">';


            $data['data'][$key] = array(
                $value->codSolicitacao,
                $value->descricaoSolicitacao,
                $value->descricaoCategoriaSuporte,
                $value->nomeExibicao,
                $value->descricaoDepartamento,
                $value->ResponsavelTecnico,
                date('d/m/Y H:i', strtotime($value->dataCriacao)),
                $value->descricaoStatusSuporte,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }
    public function resolubilidadeHoje()
    {
        $response = array();
        $result = $this->SolicitacoesSuporteModel->resolubilidadeHoje();

        $statusAberto = 0;
        $statusEncerrado = 0;

        if (!empty($result)) {

            foreach ($result as $row) {
                if ($row->codStatusSolicitacao == 5) {
                    $statusEncerrado++;
                } else {
                    $statusAberto++;
                }
            }

            $data['totalSolicitacoes'] = count($result);
            $data['statusEncerrado'] = $statusEncerrado;
        } else {
            $data['totalSolicitacoes'] = 0;
            $data['statusEncerrado'] = 0;
        }
        return $this->response->setJSON($data);
    }

    public function resolubilidadeOntem()
    {
        $response = array();
        $result = $this->SolicitacoesSuporteModel->resolubilidadeOntem();

        $statusAberto = 0;
        $statusEncerrado = 0;

        if (!empty($result)) {

            foreach ($result as $row) {
                if ($row->codStatusSolicitacao == 5 and date("Y-m-d", strtotime($row->dataEncerramento)) == date("Y-m-d", strtotime($row->dataCriacao))) {
                    $statusEncerrado++;
                } else {
                    $statusAberto++;
                }
            }

            $data['totalSolicitacoes'] = count($result);
            $data['statusEncerrado'] = $statusEncerrado;
        } else {
            $data['totalSolicitacoes'] = 0;
            $data['statusEncerrado'] = 0;
        }
        return $this->response->setJSON($data);
    }


    public function atendimentosPorMedicos()
    {

        $data['data'] = array();

        $medicos = array();
        $totais = array();

        $atendimentosPorMedicos = $this->MonitorHospitalarModel->atendimentosPorMedicos();

        foreach ($atendimentosPorMedicos as $medico) {
            array_push($medicos, $medico->nomeExibicao);
            array_push($totais, $medico->total);
        }

        $data['medicos'] = json_encode($medicos);
        $data['totais'] = json_encode($totais);

        return $this->response->setJSON($data);
    }

    public function atendimentosEmergenciaSemana()
    {
        $response = array();

        $data['data'] = array();

        $atendimentosEmergenciaSemanaAtual = $this->MonitorHospitalarModel->atendimentosEmergenciaSemanaAtual();
        $atendimentosEmergenciaSemanaPassada = $this->MonitorHospitalarModel->atendimentosEmergenciaSemanaPassada();

        $diaSemana = array('Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo');


        $segundaAtual = 0;
        $segundaPassada = 0;
        $tercaAtual = 0;
        $tercaPassada = 0;
        $quartaAtual = 0;
        $quartaPassada = 0;
        $quintaAtual = 0;
        $quintaPassada = 0;
        $sextaAtual = 0;
        $sextaPassada = 0;
        $sabadoPassado = 0;
        $sabadoAtual = 0;
        $domingoPassado = 0;
        $domingoAtual = 0;


        foreach ($atendimentosEmergenciaSemanaAtual as $atendimento) {

            //SEGUNDA
            if ($atendimento->diaSemana == 1) {

                $segundaAtual++;
            }

            //TERÇA
            if ($atendimento->diaSemana == 2) {
                $tercaAtual++;
            }


            //QUARTA
            if ($atendimento->diaSemana == 3) {
                $quartaAtual++;
            }


            //QUINTA
            if ($atendimento->diaSemana == 4) {
                $quintaAtual++;
            }


            //SEXTA
            if ($atendimento->diaSemana == 5) {
                $sextaAtual++;
            }


            //SABADO
            if ($atendimento->diaSemana == 6) {
                $sabadoAtual++;
            }


            //DOMINGO
            if ($atendimento->diaSemana == 0) {
                $domingoAtual++;
            }
        }

        foreach ($atendimentosEmergenciaSemanaPassada as $atendimento) {

            //SEGUNDA
            if ($atendimento->diaSemana == 1) {

                $segundaPassada++;
            }

            //TERÇA
            if ($atendimento->diaSemana == 2) {
                $tercaPassada++;
            }


            //QUARTA
            if ($atendimento->diaSemana == 3) {
                $quartaPassada++;
            }


            //QUINTA
            if ($atendimento->diaSemana == 4) {
                $quintaPassada++;
            }


            //SEXTA
            if ($atendimento->diaSemana == 5) {
                $sextaPassada++;
            }

            //SABADO
            if ($atendimento->diaSemana == 6) {
                $sabadoPassado++;
            }


            //DOMINGO
            if ($atendimento->diaSemana == 0) {
                $domingoPassado++;
            }
        }

        $semanaAtual = array($segundaAtual, $tercaAtual, $quartaAtual, $quintaAtual, $sextaAtual, $sabadoAtual,$domingoAtual);
        $SemanaPassada = array($segundaPassada, $tercaPassada, $quartaPassada, $quintaPassada, $sextaPassada,$sabadoPassado,$domingoPassado);

        $data['diaSemana'] = json_encode($diaSemana);
        $data['semanaAtual'] = json_encode($semanaAtual);
        $data['semanaPassada'] = json_encode($SemanaPassada);

        return $this->response->setJSON($data);
    }


    public function maioresSolicitantes()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->SolicitacoesSuporteModel->maioresSolicitantes();

        $departamentos = array();
        $totais = array();
        $totalGeral = 0;

        foreach ($result as $row1) {
            $totalGeral = $totalGeral + $row1->totalAtendimentos;
        }

        foreach ($result as $row) {
            if ($totalGeral > 0) {
                array_push($totais, round($row->totalAtendimentos / $totalGeral * 100));
            }
            array_push($departamentos, $row->departamento);
        }

        $data['departamentos'] = json_encode($departamentos);
        $data['totais'] = json_encode($totais);

        return $this->response->setJSON($data);
    }
    public function solicitacoesAtendidasPorTecnico()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->SolicitacoesSuporteModel->solicitacoesAtendidasPorTecnico();

        foreach ($result as $key => $value) {

            $ops = "";

            $meta = '';

            $statusMeta = '<i class="fa fa-thumbs-down text-center" style="font-size:30px;color:#ff0505"></i>';

            $percentual = round($value->totalAtendimentos / 15 * 100);


            $elogios = '<div class="row">';

            $pontosAvaliacoes = $value->pontosAvaliacoes;

            if ($value->caveira == 1) {
                $elogios .= '<img style="margin-left:5px;width:30px" src="' . base_url() . '/imagens/caveira3.png">';
                //SE CAVEIRA, REDUZ EM 50% OS ELOGIOS

                $pontosAvaliacoes = $pontosAvaliacoes / 2;
            }
            if ($value->caveira == 2) {
                $elogios .= '<img style="margin-left:5px;width:30px" src="' . base_url() . '/imagens/caveira3.png">';
                $elogios .= '<img style="margin-left:5px;width:30px" src="' . base_url() . '/imagens/caveira3.png">';
                //SE CAVEIRA, REDUZ EM 100% OS ELOGIOS

                $pontosAvaliacoes = 0;
            }


            if ($pontosAvaliacoes >= 5 and $pontosAvaliacoes < 10) {
                $elogios .= '<img style="margin-left:5px;width:30px" src="' . base_url() . '/imagens/estrelaDourada.png">';
            }


            if ($pontosAvaliacoes >= 10 and $pontosAvaliacoes < 15) {
                $elogios .= '<img style="margin-left:5px;width:30px" src="' . base_url() . '/imagens/estrelaDourada.png">';
                $elogios .= '<img style="margin-left:5px;width:30px" src="' . base_url() . '/imagens/estrelaDourada.png">';
            }


            if ($pontosAvaliacoes >= 15 and $pontosAvaliacoes < 20) {
                $elogios .= '<img style="margin-left:5px;width:30px" src="' . base_url() . '/imagens/estrelaDourada.png">';
                $elogios .= '<img style="margin-left:5px;width:30px" src="' . base_url() . '/imagens/estrelaDourada.png">';
                $elogios .= '<img style="margin-left:5px;width:30px" src="' . base_url() . '/imagens/estrelaDourada.png">';
            }

            if ($pontosAvaliacoes >= 20 and $pontosAvaliacoes < 30) {
                $elogios .= '<img style="margin-left:5px;width:30px" src="' . base_url() . '/imagens/estrelaDourada.png">';
                $elogios .= '<img style="margin-left:5px;width:30px" src="' . base_url() . '/imagens/estrelaDourada.png">';
                $elogios .= '<img style="margin-left:5px;width:30px" src="' . base_url() . '/imagens/estrelaDourada.png">';
                $elogios .= '<img style="margin-left:5px;width:30px" src="' . base_url() . '/imagens/estrelaDourada.png">';
            }

            if ($pontosAvaliacoes >= 35) {
                $elogios .= '<img style="margin-left:5px;width:30px" src="' . base_url() . '/imagens/estrelaDourada.png">';
                $elogios .= '<img style="margin-left:5px;width:30px" src="' . base_url() . '/imagens/estrelaDourada.png">';
                $elogios .= '<img style="margin-left:5px;width:30px" src="' . base_url() . '/imagens/estrelaDourada.png">';
                $elogios .= '<img style="margin-left:5px;width:30px" src="' . base_url() . '/imagens/estrelaDourada.png">';
                $elogios .= '<img style="margin-left:5px;width:30px" src="' . base_url() . '/imagens/estrelaDourada.png">';
            }


            if ($percentual >= 40) {
            }


            $elogios .= '</div>';

            if ($percentual < 50) {
                $statusMeta = '<i class="fa fa-thumbs-down text-center" style="font-size:30px;color:#ff0505"></i>';
                $meta = '	<div class="progress mb-3">
                    <div class="progress-bar bg-danger progress-bar-striped" role="progressbar" aria-valuenow="' . $percentual . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percentual . '%">
                        ' . $percentual . '% da Meta
                    </div>
                </div>';
            }
            if ($percentual >= 50 and $percentual < 60) {
                $statusMeta = '<i class="fa fa-thumbs-down text-center" style="font-size:30px;color:#ff7805"></i>';

                $meta = '	<div class="progress mb-3">
                    <div class="progress-bar bg-warning progress-bar-striped" role="progressbar" aria-valuenow="' . $percentual . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percentual . '%">
                        ' . $percentual . '% da Meta
                    </div>
                </div>';
            }
            if ($percentual >= 60 and $percentual < 90) {
                $statusMeta = '<i class="fa fa-thumbs-down text-center" style="font-size:30px;color:#ffd505"></i>';

                $meta = '	<div class="progress mb-3">
                    <div class="progress-bar bg-info progress-bar-striped" role="progressbar" aria-valuenow="' . $percentual . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percentual . '%">
                        ' . $percentual . '% da Meta
                    </div>
                </div>';
            }
            if ($percentual >= 90 and $percentual < 99) {
                $statusMeta = '<i class="fa fa-thumbs-down text-center" style="font-size:30px;color:#d9ff05"></i>';

                $meta = '	<div class="progress mb-3">
                    <div class="progress-bar bg-primary progress-bar-striped" role="progressbar" aria-valuenow="' . $percentual . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percentual . '%">
                        ' . $percentual . '% da Meta
                    </div>
                </div>';
            }
            if ($percentual >= 100) {
                $statusMeta = '<i class="fa fa-thumbs-up text-center" style="font-size:30px;color:#0cff08"></i>';
                $meta = '	<div class="progress mb-3">
                    <div class="progress-bar bg-success progress-bar-striped" role="progressbar" aria-valuenow="' . $percentual . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percentual . '%">
                        ' . $percentual . '% da Meta
                    </div>
                </div>';
            }


            if ($value->fotoPerfil !== NULL) {
                $fotoPerfil =  'arquivos/imagens/pessoas/' . $value->fotoPerfil;
            } else {
                $fotoPerfil = 'arquivos/imagens/pessoas/no_image.jpg';
            }

            $tecnicoReponsavel = '<span><img style="width:50px" class="img-circle elevation-2" src="' . $fotoPerfil . '"></span><span>&nbsp;&nbsp;&nbsp;' . $value->tecnicoReponsavel . '</span>';

            if ($value->tempoEmAtendimento !== NULL and $value->tempoEmAtendimento > 0) {
                $horasTrabalhadas = $value->tempoEmAtendimento . ' h';
            } else {
                $horasTrabalhadas = NULL;
            }


            $data['data'][$key] = array(
                $tecnicoReponsavel,
                $value->totalAtendimentos,
                $meta,
                '<span>' . $horasTrabalhadas . '</span>',
                $statusMeta,
                $elogios,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }
}
