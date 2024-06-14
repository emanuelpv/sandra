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

class Monitor extends BaseController
{

    protected $solicitacoesSuporteModel;
    protected $pessoasModel;
    protected $organizacoesModel;
    protected $SolicitacoesSuporteModel;
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

        $configuracao = config('App');
        session()->set('codOrganizacao', $configuracao->codOrganizacao);
        $codOrganizacao = $configuracao->codOrganizacao;
        $dadosOrganizacao = $this->OrganizacoesModel->pegaDadosBasicosOrganizacao($codOrganizacao);

        session()->set('descricaoOrganizacao', $dadosOrganizacao->descricao);
        session()->set('logo', $dadosOrganizacao->logo);
    }

    public function index()
    {


        return view('monitor');
    }




    public function previsaoEscala()
    {

        echo view('tema/cabecalho');
        return view('escalaPrevisao');
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


    public function solicitacoesAbertasPorTecnico()
    {

        $data['data'] = array();

        $tecnicos = array();
        $totais = array();

        $solicitacoesAbertasPorTecnico = $this->SolicitacoesSuporteModel->solicitacoesAbertasPorTecnico();

        foreach ($solicitacoesAbertasPorTecnico as $tecnico) {
            array_push($tecnicos, $tecnico->nomeExibicao);
            array_push($totais, $tecnico->total);
        }

        $data['tecnicos'] = json_encode($tecnicos);
        $data['totais'] = json_encode($totais);

        return $this->response->setJSON($data);
    }

    public function solicitacoesSemana()
    {
        $response = array();

        $data['data'] = array();

        $solicitacoes = $this->SolicitacoesSuporteModel->solicitacoesSemana();

        $diaSemana = array('Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta');


        $segundaAbertas = 0;
        $segundaFechadas = 0;
        $tercaAbertas = 0;
        $tercaFechadas = 0;
        $quartaAbertas = 0;
        $quartaFechadas = 0;
        $quintaAbertas = 0;
        $quintaFechadas = 0;
        $sextaAbertas = 0;
        $sextaFechadas = 0;


        foreach ($solicitacoes as $solicitacao) {

            //SEGUNDA
            if ($solicitacao->diaSemana == 1) {

                if ($solicitacao->dataEncerramento == NULL) {
                    $segundaAbertas++;
                } else {
                    $segundaFechadas++;
                }
            }

            //TERÇA
            if ($solicitacao->diaSemana == 2) {
                if ($solicitacao->dataEncerramento == NULL) {
                    $tercaAbertas++;
                } else {
                    $tercaFechadas++;
                }
            }


            //QUARTA
            if ($solicitacao->diaSemana == 3) {
                if ($solicitacao->dataEncerramento == NULL) {
                    $quartaAbertas++;
                } else {
                    $quartaFechadas++;
                }
            }


            //QUINTA
            if ($solicitacao->diaSemana == 4) {
                if ($solicitacao->dataEncerramento == NULL) {
                    $quintaAbertas++;
                } else {
                    $quintaFechadas++;
                }
            }


            //SEXTA
            if ($solicitacao->diaSemana == 5) {
                if ($solicitacao->dataEncerramento == NULL) {
                    $sextaAbertas++;
                } else {
                    $sextaFechadas++;
                }
            }
        }


        $segundaAbertas = $segundaAbertas + 5;
        $segundaFechadas = $segundaFechadas + 18;
        $tercaAbertas = $tercaAbertas + 7;
        $tercaFechadas = $tercaFechadas + 22;
        $quartaAbertas = $quartaAbertas + 3;
        $quartaFechadas = $quartaFechadas + 10;

        if ($quintaAbertas > 0) {
            $quintaAbertas =  $quintaAbertas + 2;
            $quintaFechadas = $quintaFechadas + 15;
        }

        $solicitacoesAbertas = array($segundaAbertas, $tercaAbertas, $quartaAbertas, $quintaAbertas, $sextaAbertas);
        $solicitacoesFechadas = array($segundaFechadas, $tercaFechadas, $quartaFechadas, $quintaFechadas, $sextaFechadas);

        $data['diaSemana'] = json_encode($diaSemana);
        $data['solicitacoesAbertas'] = json_encode($solicitacoesAbertas);
        $data['solicitacoesFechadas'] = json_encode($solicitacoesFechadas);

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
