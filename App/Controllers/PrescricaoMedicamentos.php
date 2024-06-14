<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;
use App\Models\ControleAntimicrobianoModel;

use App\Models\AtendimentosPrescricoesModel;
use App\Models\PrescricaoMedicamentosModel;
use App\Models\SuspensaoMedicamentosModel;
use App\Models\PrescricoesComplementaresModel;

class PrescricaoMedicamentos extends BaseController
{

    protected $PrescricaoMedicamentosModel;
    protected $pessoasModel;
    protected $OrganizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

    public function __construct()
    {

        helper('seguranca_helper');
        verificaSeguranca($this, session(), base_url());
        $this->PrescricaoMedicamentosModel = new PrescricaoMedicamentosModel();
        $this->PrescricoesComplementaresModel = new PrescricoesComplementaresModel();
        $this->AtendimentosPrescricoesModel = new AtendimentosPrescricoesModel();
        $this->ControleAntimicrobianoModel = new ControleAntimicrobianoModel();
        $this->SuspensaoMedicamentosModel = new SuspensaoMedicamentosModel();
        $this->OrganizacoesModel = new OrganizacoesModel();
        $this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
        $this->validation =  \Config\Services::validation();
        $this->codOrganizacao = session()->codOrganizacao;
        $this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
    }

    public function index()
    {

        $permissao = verificaPermissao('PrescricaoMedicamentos', 'listar');
        if ($permissao == 0) {
            echo mensagemAcessoNegado(session()->organizacoes);
            $this->LogsModel->inserirLog('Acesso indevido ao Módulo "PrescricaoMedicamentos"', session()->codPessoa);
            exit();
        }


        $data = [
            'controller'        => 'prescricaoMedicamentos',
            'title'             => 'Prescrição de Medicamentos'
        ];
        echo view('tema/cabecalho');
        echo view('tema/menu_vertical');
        echo view('tema/menu_horizontal');
        return view('prescricaoMedicamentos', $data);
    }

    public function getAll()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->PrescricaoMedicamentosModel->pegaTudo();

        foreach ($result as $key => $value) {

            $ops = '<div class="btn-group">';
            $ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editprescricaoMedicamentos(' . $value->codPrescricaoMedicamento . ')"><i class="fa fa-edit"></i></button>';
            $ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeprescricaoMedicamentos(' . $value->codPrescricaoMedicamento . ')"><i class="fa fa-trash"></i></button>';
            $ops .= '</div>';

            $data['data'][$key] = array(
                $value->codPrescricaoMedicamento,
                $value->codAtendimentoPrescricao,
                $value->codMedicamento,
                $value->qtde,
                $value->und,
                $value->via,
                $value->freq,
                $value->per,
                $value->dias,
                $value->horaIni,
                $value->agora,
                $value->risco,
                $value->obs,
                $value->apraza,
                $value->total,
                $value->stat,
                $value->codAutor,
                $value->dataCriacao,
                $value->dataAtualizacao,

                $ops,
            );
        }

        return $this->response->setJSON($data);
    }





    public function suspenderMedicamento()
    {
        $response = array();

        $fields['codSuspensaoMedicamento'] = $this->request->getPost('codAtendimentoPrescricao');
        $fields['codPrescricaoMedicamento'] = $this->request->getPost('codPrescricaoMedicamento');
        $fields['codMedicamento'] = $this->request->getPost('codMedicamento');
        $fields['codAtendimento'] = $this->request->getPost('codAtendimento');
        $fields['codAutor'] = session()->codPessoa;
        $fields['motivo'] = $this->request->getPost('motivo');
        $fields['qtdDevolucao'] = $this->request->getPost('devolucao');
        $fields['dataCriacao'] = date("Y-m-d H:i");
        $fields['codPaciente'] = $this->request->getPost('codPaciente');


        $this->validation->setRules([
            'codPrescricaoMedicamento' => ['label' => 'CodPrescricaoMedicamento', 'rules' => 'permit_empty|numeric|max_length[11]'],
            'codMedicamento' => ['label' => 'CodMedicamento', 'rules' => 'required|numeric|max_length[11]'],
            'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
            'motivo' => ['label' => 'Motivo', 'rules' => 'required|bloquearReservado'],
            'qtdDevolucao' => ['label' => 'QtdDevolucao', 'rules' => 'required|numeric|max_length[11]'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->SuspensaoMedicamentosModel->insert($fields)) {

                //VERIFICA SE EXISTE GUIA ANTIMICROBIANA PARA SUSPENDER
                $verificaGuasParaSuspender = $this->SuspensaoMedicamentosModel->verificaGuasParaSuspender($fields['codPrescricaoMedicamento'], $fields['codMedicamento']);

                if ($verificaGuasParaSuspender !== NULL) {
                    $suspenderAntimicrobiano['codControleAntimicrobiano'] = $verificaGuasParaSuspender->codControleAntimicrobiano;
                    $fields['dataAtualizacao'] = date("Y-m-d H:i");
                    $suspenderAntimicrobiano['motivoSuspensaoGuia'] = $fields['motivo'];
                    $suspenderAntimicrobiano['codStatus'] = 0;

                    if ($suspenderAntimicrobiano['codControleAntimicrobiano'] !== NULL and $suspenderAntimicrobiano['codControleAntimicrobiano'] !== "" and $suspenderAntimicrobiano['codControleAntimicrobiano'] !== " ") {
                        $this->ControleAntimicrobianoModel->update($suspenderAntimicrobiano['codControleAntimicrobiano'], $suspenderAntimicrobiano);
                    }
                }
            }

            $response['success'] = true;
            $response['messages'] = 'Suspensão realizada com sucesso!';
        }

        return $this->response->setJSON($response);
    }

    public function getAllPorPrescricao()
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



        //$x = count($result);        
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
            if ($value->stat <= 1) {
                $ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editprescricaoMedicamentos(' . $value->codPrescricaoMedicamento . ')"><i class="fa fa-edit"></i></button>';
                $ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeprescricaoMedicamentos(' . $value->codPrescricaoMedicamento . ')"><i class="fa fa-trash"></i></button>';
            }

            if ($value->codPrescricaoComplementar !== NULL and  date('Y-m-d H:i') >= date('Y-m-d H:i', strtotime("+15 minute", strtotime($value->dataCriacaoPrescricao)))) {
                $ops = '';
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
                }


                if ($value->totalEntregue > 0) {
                    $total .= '<div class="right badge badge-success"> Entregue:' . $value->totalEntregue . '</div><br>';
                }
            }



            if (($value->totalEntregue > 0 or $value->totalEntregue > 0) and $value->totalExecutado == 0 and $value->stat >  0) {
                $total .= '<a href="#" onclick="gravarMedicamentosExecutados(' . $value->codPrescricaoMedicamento . ',' . $value->total . ')"><div class="right badge badge-danger"> Executado:??</div><br></a>';
            }


            if ($value->totalExecutado > 0 and $value->stat >  0) {
                if ($value->totalExecutado == $value->freq) {
                    $total .= '<a href="#" onclick="gravarMedicamentosExecutados(' . $value->codPrescricaoMedicamento . ',' . $value->total . ')"><div class="right badge badge-success"> Executado:' . $value->totalExecutado . '</div><br></a>';
                } else {
                    $total .= '<a href="#" onclick="gravarMedicamentosExecutados(' . $value->codPrescricaoMedicamento . ',' . $value->total . ')"><div class="right badge badge-info"> Executado:' . $value->totalExecutado . '</div><br></a>';
                }
            }


            /*
               CHECAGEM DA ENFERMAGEM
                                 
            */

            $checagem = $this->checagemMedicamento($value->codPrescricaoMedicamento, $value->codMedicamento, $value->freq, $value->codStatusPrescricao, $value->dataCriacaoPrescricaoMedicamento, $value->horaIni);

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
                </div>
                <div style="margin-bottom:10px" >
                <button style="font-size:16px; margin-bottom:10px" type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Adicionar Guia Antimicroniano"  onclick="listaGuiaAntimicrobiano(' . $value->codAtendimento . ',' . $value->codPaciente . ',' . $value->codItem . ',' . $value->codPrescricaoMedicamento . ')"><i class="fa fa-edit"></i>Adicionar Guia Antimicroniano</button>
                </div>';
                }
                if ($resultAntimicrobiano->codControleAntimicrobiano !== NULL and $resultAntimicrobiano->codStatus == 0) {
                    $guiaAntimicrobiano = '
					<div> <img style="width:30px;" src="' . base_url() . '/imagens/atencao.gif">
						<span style="color:red;font-size:16px; margin-bottom:10px">Guia antimicrobiana Nº ' . $resultAntimicrobiano->codControleAntimicrobiano . ' foi suspensa por ' . $resultAntimicrobiano->suspensoPor . ' em ' . date("d/m/Y H:i", strtotime($resultAntimicrobiano->dataSuspensao)) . '
						</span>

					</div>
                    <button style="font-size:16px; margin-bottom:10px" type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Adicionar Guia Antimicroniano"  onclick="listaGuiaAntimicrobiano(' . $value->codAtendimento . ',' . $value->codPaciente . ',' . $value->codItem . ',' . $value->codPrescricaoMedicamento . ')"><i class="fa fa-edit"></i>Adicionar Guia Antimicroniano</button>
                    ';
                }

                if ($resultAntimicrobiano->codControleAntimicrobiano !== NULL and $resultAntimicrobiano->codStatus == 1) {



                    $dataPrescricao = strtotime($value->dataInicioPrescricao);
                    $dataEncerramento = strtotime($resultAntimicrobiano->dataEncerramento);
                    $dataInicio = strtotime($resultAntimicrobiano->dataInicio);

                    $diasAntimicrobiano = round(($dataPrescricao - $dataInicio) / 60 / 60 / 24);
                    $diasAntimicrobiano = $diasAntimicrobiano + 1 . '&deg; dia';


                    if (date("Y-m-d", strtotime($value->dataInicioPrescricao)) > date("Y-m-d", strtotime($resultAntimicrobiano->dataEncerramento)) and $resultAntimicrobiano->codStatus == 1) {
                        $guiaAntimicrobiano = '<div>
                        <img style="width:50px;" src="' . base_url() . '/imagens/atencao.gif">
                        <span style="color:red;font-size:16px;margin-bottom:10px">Guia venceu há ' . abs($diasAntimicrobiano) . ' dia(s)' . ' Deseja emitir nova guia antimicrobiana?</span></div>
                        <button style="font-size:16px; margin-bottom:10px" type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Adicionar Guia Antimicroniano"  onclick="listaGuiaAntimicrobiano(' . $value->codAtendimento . ',' . $value->codPaciente . ',' . $value->codItem . ',' . $value->codPrescricaoMedicamento . ')"><i class="fa fa-edit"></i>Adicionar Guia Antimicroniano</button>
                        ';
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
                                <button style="font-size:16px; margin-bottom:10px" type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Adicionar Guia Antimicroniano"  onclick="listaGuiaAntimicrobiano(' . $value->codAtendimento . ',' . $value->codPaciente . ',' . $value->codItem . ',' . $value->codPrescricaoMedicamento . ')"><i class="fa fa-edit"></i>Listar Guias</button>
                                <button style="font-size:16px; margin-bottom:10px" type="button" class="btn btn-sm btn-success"  data-toggle="tooltip" data-placement="top" title="Adicionar Guia Antimicroniano"  onclick="imprimirGuiaAntimicrobiana(' . $resultAntimicrobiano->codControleAntimicrobiano . ')"><i class="fa fa-edit"></i>Imprimir Guia</button>
                                </div>
                                </div>
                            ';
                    }
                }
            }
            $totalLiberado = 0;

            if ($value->totalLiberado !== NULL and $value->totalLiberado !== "" and $value->totalLiberado !== " ") {
                $totalLiberado = $value->totalLiberado;
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
                $autorMotivo = '<div style="color:red"> Suspenso em : ' . date("d/m/Y H:i", strtotime($value->dataSuspensao)) . ' por ' . $value->autorSuspensao . '.</div>';
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
                ' . $guiaAntimicrobiano . $autorMotivo . $checagem,
                $value->qtde,
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
                $ops . $btnSuspenderMedicamento,
            );
            //$x--;
        }

        return $this->response->setJSON($data);
    }

    public function getAllPorPrescricaoDietas()
    {
        $response = array();

        $data['data'] = array();
        $codAtendimentoPrescricao = $this->request->getPost('codAtendimentoPrescricao');
        $result = $this->PrescricaoMedicamentosModel->pegaPorCodigoAtendimentoPrescricaoDietas($codAtendimentoPrescricao);

        $x = count($result);
        foreach ($result as $key => $value) {

            $ops = '<div class="btn-group">';


            if ($value->dataInicioPrescricao >= date("Y-m-d")) {
                $ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editprescricaoMedicamentos(' . $value->codPrescricaoMedicamento . ')"><i class="fa fa-edit"></i></button>';
                $ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeprescricaoMedicamentos(' . $value->codPrescricaoMedicamento . ')"><i class="fa fa-trash"></i></button>';
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
                }


                if ($value->totalEntregue > 0) {
                    $total .= '<div class="right badge badge-success"> Entregue:' . $value->totalEntregue . '</div><br>';
                }
            }



            if (($value->totalEntregue > 0 or $value->totalEntregue > 0) and $value->totalExecutado == 0 and $value->stat >  0) {
                $total .= '<a href="#" onclick="gravarMedicamentosExecutados(' . $value->codPrescricaoMedicamento . ',' . $value->total . ')"><div class="right badge badge-danger"> Executado:??</div><br></a>';
            }


            if ($value->totalExecutado > 0 and $value->stat >  0) {

                if ($value->totalExecutado == $value->freq) {
                    $total .= '<a href="#" onclick="gravarMedicamentosExecutados(' . $value->codPrescricaoMedicamento . ',' . $value->total . ')"><div class="right badge badge-success"> Executado:' . $value->totalExecutado . '</div><br></a>';
                } else {
                    $total .= '<a href="#" onclick="gravarMedicamentosExecutados(' . $value->codPrescricaoMedicamento . ',' . $value->total . ')"><div class="right badge badge-info"> Executado:' . $value->totalExecutado . '</div><br></a>';
                }
            }


            /*
               CHECAGEM DA ENFERMAGEM
                                 
            */

            $checagem = $this->checagemMedicamento($value->codPrescricaoMedicamento, $value->codMedicamento, $value->freq, $value->codStatusPrescricao, $value->dataCriacaoPrescricaoMedicamento, $value->horaIni);


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



            $autorMotivo = NULL;
            if ($value->codSuspensaoMedicamento !== NULL) {
                $descricaoItem = '<s>' . $value->descricaoItem . '</s>';
                $autorMotivo = '<div style="color:red"> Suspenso em : ' . date("d/m/Y H:i", strtotime($value->dataSuspensao)) . ' por ' . $value->autorSuspensao . '.</div>';
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

            $aplicacao = 'Frequência: ' . $value->freq . 'x/' . $value->descricaoPeriodo . ' | Por ' . $dias . $inicio;


            $data['data'][$key] = array(
                $x,
                '<div style="font-weight: bold;">' . $descricaoItem . $prescricaoComplementar . '</div><div>' .  $aplicacao . '</div><br><div style="font-size:12px;color:red">' . $value->obs . '</div><div style="font-size:14px;">' . $autorComplemento . '</div>
                <br>' . $autorMotivo,
                $value->qtde,
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
            $x--;
        }

        return $this->response->setJSON($data);
    }
    public function checkinMedicamento($codPrescricaoMedicamento = null, $codMedicamento = null)
    {


        $fields['codPrescricaoMedicamento'] = $this->request->getPost('codPrescricaoMedicamento');
        $fields['codMedicamento'] = $this->request->getPost('codMedicamento');
        $fields['observacoes'] = $this->request->getPost('observacoes');
        $fields['dataHoraChecagem'] = date('Y-m-d H:i', strtotime($this->request->getPost('dataHoraChecagem')));





        $this->validation->setRules([
            'codPrescricaoMedicamento' => ['label' => 'codPrescricaoMedicamento', 'rules' => 'required|numeric|max_length[11]'],
            'codMedicamento' => ['label' => 'CodMedicamento', 'rules' => 'required|numeric|max_length[11]'],
            'dataHoraChecagem' => ['label' => 'dataHoraChecagem', 'rules' => 'required|valid_date'],
            'observacoes' => ['label' => 'observacoes', 'rules' => 'permit_empty|bloquearReservado'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {



            if ($resultado = $this->PrescricaoMedicamentosModel->checkinMedicamento($fields['codPrescricaoMedicamento'], $fields['codMedicamento'], $fields['observacoes'], $fields['dataHoraChecagem'])) {
                $response['success'] = true;
                $response['messages'] = 'Check-in realizado!';
            } else {
                $response['success'] = false;
                $response['messages'] = 'Falha na operação';
            }
        }







        return $this->response->setJSON($response);
    }


    public function checagemMedicamento($codPrescricaoMedicamento = null, $codMedicamento = null, $frequencia = null, $statusPrescricao = null, $dataCriacaoPrescricaoMedicamento = null, $horaInicio = null)
    {

        $minhasEspecialidades = session()->minhasEspecialidades;
        $especialidadesSociais = array('ENFERMAGEM');
        $especialidadeAuxiliar = 0;
        foreach ($minhasEspecialidades as $especialidade) {
            if (in_array($especialidade->descricaoEspecialidade, $especialidadesSociais)) {
                $especialidadeAuxiliar = 1;
            }
        }



        $checagem = $this->PrescricaoMedicamentosModel->checagemMedicamento($codPrescricaoMedicamento, $codMedicamento);


        $html = '';
        $botoes = '';

        if ($frequencia == 0 or $frequencia == NULL) {
            $frequencia = 1;
        }
        $intervalo = 24 / $frequencia;
        $intervalo = "+ " . (string)$intervalo . " hours";



        if ($statusPrescricao > 1) {

            $corCard = 'secondary';
            $corBotao = 'danger';
            $statusBotao = 'Check-in';
            $ultimaChecagem = NULL;
            if ($frequencia == count($checagem)) {
                $corCard = 'success';
            }


            for ($x = 0; $x < $frequencia; $x++) {

                if ($checagem[$x]->dataCriacao !== NULL) {
                    $corBotao = 'success';
                    $statusBotao = 'Checado';
                    $ultimaChecagem = date("Y-m-d H:i", strtotime($checagem[$x]->dataCriacao));
                    $botoes .= '<div style="margin-top:3px" class="bg-' . $corBotao . '">Checado - ' . date('d/m/Y  H:i', strtotime($checagem[$x]->dataCriacao)) . ' | ' . $checagem[$x]->nomeExibicao . ' | ' . $checagem[$x]->observacao . '</div>
                    ';
                } else {
                    $corBotao = 'danger';
                    $statusBotao = 'Check-in';
                    if (empty($checagem) and $ultimaChecagem == NULL) {
                        $ultimaChecagem = date("Y-m-d H:i");
                        $ultimaChecagem = date("Y-m-d H:i", strtotime($ultimaChecagem));
                    } else {
                        $ultimaChecagem = date("Y-m-d H:i", strtotime($ultimaChecagem . $intervalo));
                    }
                    $checagemAtual = date("d/m/Y H:i", strtotime($ultimaChecagem));
                    $ano = date("Y", strtotime($ultimaChecagem));
                    $mes = date("m", strtotime($ultimaChecagem));
                    $dia = date("d", strtotime($ultimaChecagem));
                    $hora = date("H", strtotime($ultimaChecagem));
                    $minuto = date("i", strtotime($ultimaChecagem));
                    $botoes .= '<div style="margin-top:5px"><button onclick="checkinMedicamento(' . $codPrescricaoMedicamento . ',' . $codMedicamento . ',' . $ano . ',' . $mes . ',' . $dia . ',' . $hora . ',' . $minuto . ')" class="btn btn-' . $corBotao . '">' . $statusBotao . ' ' .  $checagemAtual . ' </button></div>';
                }
            }




            $collapsedCard = 'collapsed-card';
            $collapsedCard = 'collapsed-card';
            $dataCardWidget = 'data-card-widget="collapse"';
            $diaplay = 'display: none;';
            if ($especialidadeAuxiliar == 1) {
                $collapsedCard = '';
                $dataCardWidget = '';
                $diaplay = '';
            }



            $html = '           
        <div class="col-md-12">
             <div class="card card-' . $corCard . ' ' . $collapsedCard . '">
                <div class="card-header">
                    <h3 class="card-title">Checagem Enfermagem</h3>   
                    <div class="card-tools">
                    <button type="button" class="btn btn-tool" ' . $dataCardWidget . '><i class="fas fa-plus"></i>
                    </button>
                </div>                
                </div>

                
            
                <div style="font-size:14px;' . $diaplay . '" class="card-body">
                    ';

            $html .= $botoes;



            $html .=                '
                </div>
            
            </div>
            
        </div>
      ';
        }
        return $html;
    }



    public function buscaDetalheItem()
    {
        $response = array();

        $codItem = $this->request->getPost('codItem');
        $edit = $this->request->getPost('edit');
        $codPaciente = $this->request->getPost('codPaciente');
        $id = $this->request->getPost('codPrescricaoMedicamento');

        //VERIFICA SE JÁ EXISTE ALGUM GUIA VÁLIDA


        $existeGuia = $this->ControleAntimicrobianoModel->verificaGuiaAtiva($codItem, $codPaciente);

        if ($existeGuia !== NULL and $edit !== '1') {

            $response["success"] = false;
            $response["messages"] = 'Já exite uma guia ativa! Não é possível criar uma nova. Caso necessário, suspenda a guia atual e crie uma nova.';
            return $this->response->setJSON($response);
        }



        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->PrescricaoMedicamentosModel->buscaDetalheItem($id);


            $dias = '';
            if ($data->dias > 1) {
                $dias = $data->dias . ' dias';
            }
            if ($data->dias == 1) {
                $dias = $data->dias . ' dia';
            }
            $aplicacao = '<div style="font-size:20px; font-weight:bold">' . $data->qtde . ' ' . $data->descricaoUnidade . '(s) de ' . $data->descricaoItem . '</div>';
            $aplicacao .= '<div style="font-size:20px;font-weight:bold">Frequência: ' . $data->freq . 'x/' . $data->descricaoPeriodo . ' | ' . $data->descricaoVia . '  | Por ' . $dias . '</div>';

            $previsaoEncerramento = date('Y-m-d', strtotime(date('Y-m-d')) + 86400 * $data->dias);

            $response["success"] = true;
            $response["dados"] = $aplicacao;
            $response["per"] = $data->per;
            $response["qtde"] = $data->qtde;
            $response["und"] = $data->und;
            $response["via"] = $data->via;
            $response["freq"] = $data->freq;
            $response["dias"] = $data->dias;
            $response["previsaoEncerramento"] =  $previsaoEncerramento;
            return $this->response->setJSON($response);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }
    public function getOne()
    {
        $response = array();

        $id = $this->request->getPost('codPrescricaoMedicamento');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->PrescricaoMedicamentosModel->pegaPorCodigo($id);

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function listaDropDownUnidades()
    {

        $result = $this->PrescricaoMedicamentosModel->listaDropDownUnidades();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function listaDropDownVias()
    {

        $result = $this->PrescricaoMedicamentosModel->listaDropDownVias();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function listaDropDownPeriodo()
    {

        $result = $this->PrescricaoMedicamentosModel->listaDropDownPeriodo();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function listaDropDownAgora()
    {

        $result = $this->PrescricaoMedicamentosModel->listaDropDownAgora();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function listaDropDownRiscoPrescricao()
    {

        $result = $this->PrescricaoMedicamentosModel->listaDropDownRiscoPrescricao();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function add()
    {


        $response = array();
        $codAtendimentoPrescricao = $this->request->getPost('codAtendimentoPrescricao');
        $codAtendimento = $this->request->getPost('codAtendimento');



        //VERIFICA PRESCRIÇÃO DO PACIENTE CORRENTE


        $codPrescricaoMedicamento = $this->request->getPost('codAtendimentoPrescricao');

        if ($this->validation->check($codAtendimentoPrescricao, 'required|numeric') and $this->validation->check($codAtendimento, 'required|numeric')) {


            $verificaExistencia = $this->AtendimentosPrescricoesModel->pegaPorcodAtendimentoPrescricaoEAtendimento($codAtendimentoPrescricao, $codAtendimento);

            if ($verificaExistencia == NULL) {
                $response['success'] = false;
                $response['messages'] = 'Falha na validação do atendimento atual.  Atualize a página (F5) ou entre novamente no sistema. Recomendamos a utilização do Google Chrome atualizado.';

                $this->LogsModel->inserirLog('Violação de inclusão de medicamento. Atendimento atual não coincide com o cache (codAtendimentoTmp)', session()->codPessoa);

                return $this->response->setJSON($response);
            }
        }



        $fields['codAtendimentoPrescricao'] = $this->request->getPost('codAtendimentoPrescricao');
        $fields['codPrescricaoMedicamento'] = $this->request->getPost('codPrescricaoMedicamento');
        $fields['codAtendimentoPrescricao'] = $this->request->getPost('codAtendimentoPrescricao');
        $fields['codMedicamento'] = $this->request->getPost('codMedicamento');
        $fields['qtde'] = brl2decimal($this->request->getPost('qtde'));
        $fields['und'] = $this->request->getPost('und');
        $fields['via'] = $this->request->getPost('via');
        $fields['freq'] = $this->request->getPost('freq');
        $fields['per'] = $this->request->getPost('per');
        $fields['dias'] = $this->request->getPost('dias');
        $fields['horaIni'] = $this->request->getPost('horaIni');
        $fields['agora'] = $this->request->getPost('agora');
        $fields['risco'] = $this->request->getPost('risco');
        $fields['obs'] = $this->request->getPost('obs');
        $fields['apraza'] = $this->request->getPost('apraza');
        $fields['total'] = $this->request->getPost('total');
        $fields['stat'] = 1;
        $fields['codAutor'] = session()->codPessoa;
        $fields['dataCriacao'] = date('Y-m-d H:i');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');


        $this->validation->setRules([
            'codAtendimentoPrescricao' => ['label' => 'CodAtendimentoPrescricao', 'rules' => 'required|numeric|max_length[11]'],
            'codMedicamento' => ['label' => 'CodMedicamento', 'rules' => 'required|numeric|max_length[11]'],
            'qtde' => ['label' => 'Qtde', 'rules' => 'required'],
            'und' => ['label' => 'Und', 'rules' => 'required|max_length[11]'],
            'via' => ['label' => 'Via', 'rules' => 'required|max_length[11]'],
            'freq' => ['label' => 'Freq', 'rules' => 'required|numeric|max_length[2]'],
            'per' => ['label' => 'Per', 'rules' => 'required|max_length[11]'],
            'dias' => ['label' => 'Dias', 'rules' => 'required|numeric|max_length[3]'],
            'horaIni' => ['label' => 'HoraIni', 'rules' => 'permit_empty|max_length[10]'],
            'agora' => ['label' => 'Agora', 'rules' => 'required|max_length[1]'],
            'risco' => ['label' => 'Risco', 'rules' => 'required|max_length[11]'],
            'obs' => ['label' => 'Obs', 'rules' => 'permit_empty'],
            'total' => ['label' => 'Total', 'rules' => 'required'],
            'stat' => ['label' => 'Stat', 'rules' => 'required|max_length[11]'],
            'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($codPrescricaoMedicamento = $this->PrescricaoMedicamentosModel->insert($fields)) {

                if ($verificaExistencia->codStatus > 1) {

                    $prescricaoComplementar['codPrescricaoMedicamento'] = $codPrescricaoMedicamento;
                    $this->PrescricoesComplementaresModel->insert($prescricaoComplementar);
                }
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

        $fields['codPrescricaoMedicamento'] = $this->request->getPost('codPrescricaoMedicamento');
        $fields['codAtendimentoPrescricao'] = $this->request->getPost('codAtendimentoPrescricao');
        $fields['codMedicamento'] = $this->request->getPost('codMedicamento');
        $fields['qtde'] = brl2decimal($this->request->getPost('qtde'));
        $fields['und'] = $this->request->getPost('und');
        $fields['via'] = $this->request->getPost('via');
        $fields['freq'] = $this->request->getPost('freq');
        $fields['per'] = $this->request->getPost('per');
        $fields['dias'] = $this->request->getPost('dias');
        $fields['horaIni'] = $this->request->getPost('horaIni');
        $fields['agora'] = $this->request->getPost('agora');
        $fields['risco'] = $this->request->getPost('risco');
        $fields['obs'] = $this->request->getPost('obs');
        $fields['total'] = $this->request->getPost('total');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');


        $this->validation->setRules([
            'codAtendimentoPrescricao' => ['label' => 'CodAtendimentoPrescricao', 'rules' => 'required|numeric|max_length[11]'],
            'codMedicamento' => ['label' => 'CodMedicamento', 'rules' => 'required|numeric|max_length[11]'],
            'qtde' => ['label' => 'Qtde', 'rules' => 'required'],
            'und' => ['label' => 'Und', 'rules' => 'required|max_length[11]'],
            'via' => ['label' => 'Via', 'rules' => 'required|max_length[11]'],
            'freq' => ['label' => 'Freq', 'rules' => 'required|numeric|max_length[2]'],
            'per' => ['label' => 'Per', 'rules' => 'required|max_length[11]'],
            'dias' => ['label' => 'Dias', 'rules' => 'required|numeric|max_length[3]'],
            'horaIni' => ['label' => 'HoraIni', 'rules' => 'permit_empty|max_length[10]'],
            'agora' => ['label' => 'Agora', 'rules' => 'required|max_length[1]'],
            'risco' => ['label' => 'Risco', 'rules' => 'required|max_length[11]'],
            'obs' => ['label' => 'Obs', 'rules' => 'permit_empty'],
            'total' => ['label' => 'Total', 'rules' => 'required'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->PrescricaoMedicamentosModel->update($fields['codPrescricaoMedicamento'], $fields)) {

                $response['success'] = true;
                $response['messages'] = 'Atualizado com sucesso';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na atualização!';
            }
        }

        return $this->response->setJSON($response);
    }


    public function gravarMedicamentosExecutados()
    {

        $response = array();


        $fields['codPrescricaoMedicamento'] = $this->request->getPost('codPrescricaoMedicamento');
        $fields['totalExecutado'] = $this->request->getPost('totalExecutado');
        $fields['total'] = $this->request->getPost('total');
        $fields['codAutorExecucao'] = session()->codPessoa;
        $fields['dataAtualizacao'] = date('Y-m-d H:i');


        if ($fields['totalExecutado'] > $fields['total']) {
            $response['success'] = false;
            $response['messages'] = 'Quantidade aplicada/executada não pode ser maior que o solicitada!';


            return $this->response->setJSON($response);
        }


        if ($fields['codPrescricaoMedicamento'] !== NULL and $fields['codPrescricaoMedicamento'] !== "" and $fields['codPrescricaoMedicamento'] !== " ") {

            if ($this->PrescricaoMedicamentosModel->update($fields['codPrescricaoMedicamento'], $fields)) {

                $response['success'] = true;
                $response['messages'] = 'Atualizado com sucesso';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na atualização!';
            }
        } else {
            $response['success'] = false;
            $response['messages'] = 'Erro na atualização!';
        }

        return $this->response->setJSON($response);
    }


    public function remove()
    {
        $response = array();

        $id = $this->request->getPost('codPrescricaoMedicamento');

        if (!$this->validation->check($id, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($this->PrescricaoMedicamentosModel->where('codPrescricaoMedicamento', $id)->delete()) {

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
