<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\PacientesModel;
use App\Models\OrganizacoesModel;
use App\Models\ExamesListaModel;
use App\Models\PainelChamadasModel;
use App\Models\ExamesFaltasModel;
use App\Models\AtendimentoslocaisModel;

use App\Models\ExamesModel;
use App\Models\ExamesReservasModel;

class Exames extends BaseController
{

    protected $ExamesModel;
    protected $pessoasModel;
    protected $OrganizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

    public function __construct()
    {

        helper('seguranca_helper');
        verificaSeguranca($this, session(), base_url());
        $this->AtendimentoslocaisModel = new AtendimentoslocaisModel();
        $this->ExamesListaModel = new ExamesListaModel();
        $this->ExamesFaltasModel = new ExamesFaltasModel();
        $this->ExamesModel = new ExamesModel();
        $this->PainelChamadasModel = new PainelChamadasModel();
        $this->ExamesReservasModel = new ExamesReservasModel();
        $this->PacientesModel = new PacientesModel();
        $this->PessoasModel = new PessoasModel();
        $this->OrganizacoesModel = new OrganizacoesModel();
        $this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
        $this->validation =  \Config\Services::validation();
        $this->codOrganizacao = session()->codOrganizacao;
        $this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
    }

    public function index()
    {
        $permissao = verificaPermissao('Exames', 'listar');
        if ($permissao == 0) {
            echo mensagemAcessoNegado(session()->organizacoes);
            $this->LogsModel->inserirLog('Acesso indevido ao Módulo "Exames"', session()->codPessoa);
            exit();
        }

        $especialistas = $this->ExamesListaModel->especialistas();


        $data = [
            'controller'        => 'exames',
            'title'             => 'Exames',
            'especialistas' => $especialistas,
        ];

        echo view('tema/cabecalho');
        echo view('tema/menu_vertical');
        echo view('tema/menu_horizontal');
        return view('exames', $data);
    }

    public function consulta()
    {


        $data = array();
        $data = $this->ExamesListaModel->especialistas();

        $data = [
            'controller'        => 'exames',
            'title'             => 'Exames',
            'data' => $data,
        ];
        echo view('tema/cabecalho');
        echo view('tema/menu_vertical');
        echo view('tema/menu_horizontal');
        return view('examesConsulta', $data);
    }

    public function getAll()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->ExamesModel->pegaTudo();


        foreach ($result as $key => $value) {

            $ops = '<div class="btn-group">';
            $ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editexames(' . $value->codExame . ')"><i class="fa fa-edit"></i></button>';
            $ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeexames(' . $value->codExame . ')"><i class="fa fa-trash"></i></button>';
            $ops .= '</div>';

            $data['data'][$key] = array(
                $value->codExame,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }





    public function proximosExames()
    {
        $response = array();

        $data['data'] = array();

        $codPaciente = $this->request->getPost('codPaciente');
        $exames = $this->ExamesModel->proximosExames($codPaciente);


        foreach ($exames as $key => $exame) {

            $ops = '
            <div class="btn-group">
                    <button type="button" class="btn btn-info">Ação</button>
                    <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                      <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu" role="menu">
                        <a href="#" class="dropdown-item" onclick="desmarcarExame(' . $exame->codExame . ')">Desmarcar</a>
                         <a href="#" class="dropdown-item" onclick="comprovanteExame(' . $exame->codExame . ')">Comprovante de Agendamento</a>
                    </div>
            </div>
            ';

            $data['data'][$key] = array(
                $exame->Tipo,
                $exame->descricaoExameLista,
                $exame->nomeExibicao,
                date('d/m/Y H:i', strtotime($exame->dataInicio)),
                $exame->nomeStatus,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }



    public function filtrarVagas()
    {
        $response = array();

        if ($this->request->getPost('codExameLista')  !== NULL and $this->request->getPost('codExameLista')  !== '') {
            $codExameLista = $this->request->getPost('codExameLista');
        } else {
            $codExameLista = NULL;
        }
        if ($this->request->getPost('codEspecialista')  !== NULL and $this->request->getPost('codEspecialista')  !== '') {
            $codEspecialista = $this->request->getPost('codEspecialista');
        } else {
            $codEspecialista = NULL;
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

        $filtro["codExameLista"] = $codExameLista;
        $filtro["codEspecialista"] = $codEspecialista;
        $filtro["dataInicio"] = $dataInicio;
        $filtro["dataEncerramento"] = $dataEncerramento;

        $this->validation->setRules([
            'codExameLista' => ['label' => 'ExameLista', 'rules' => 'permit_empty|bloquearReservado|numeric|max_length[11]'],
            'codEspecialista' => ['label' => 'Especialista', 'rules' => 'permit_empty|bloquearReservado|numeric|max_length[11]'],
            'dataInicio' => ['label' => 'dataInicio', 'rules' => 'permit_empty|bloquearReservado|valid_date'],
            'dataEncerramento' => ['label' => 'dataEncerramento', 'rules' => 'permit_empty|bloquearReservado|valid_date'],

        ]);

        if ($this->validation->run($filtro) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {


            session()->set('filtroExameLista', $filtro);
            $response['success'] = true;
            $response['csrf_hash'] = csrf_hash();
        }



        return $this->response->setJSON($response);
    }



    public function iniciarExame()
    {
        $response = array();



        //VERIFICA SE JÁ FOI DEFINIDO O LOCAL DO ATENDIMENTO

        if (session()->nomeLocalAtendimento == NULL or session()->codLocalAtendimento == NULL  or session()->codLocalAtendimento == 0) {

            $response['success'] = false;
            $response['messages'] = 'Você deve definir primeiro o local de atendimento';
            return $this->response->setJSON($response);
            //session()->codLocalAtendimento = $this->request->getPost('codLocalAtendimento');
            //session()->nomeLocalAtendimento = lookupNomeLocalAtendimento($this->request->getPost('codLocalAtendimento'));
        }


        $codExame = $this->request->getPost('codExame');
        $fields['codStatus'] = 1;
        $fields['inicioAtendimento'] = date('Y-m-d H:i');
        if ($codExame !== NULL and $codExame !== "" and $codExame !== " ") {
            if ($this->ExamesModel->update($codExame, $fields)) {
                sleep(3);
                $response['success'] = true;
                $response['csrf_hash'] = csrf_hash();
                $response['messages'] = 'Atendimento Iniciado';
            }
        } else {
            $response['success'] = false;
            $response['messages'] = 'Falha no processamento';
        }

        return $this->response->setJSON($response);
    }




    public function chegou()
    {
        $response = array();

        $codExame = $this->request->getPost('codExame');

        $dadosExames = $this->ExamesModel->pegaPorCodigo($codExame);

        $especialidade = $dadosExames->descricaoExameLista;
        $especialista = $dadosExames->nomeExibicao;
        $protocolo = $dadosExames->protocolo;
        $dataInicio = date('d/m/Y H:i', strtotime($dadosExames->dataInicio));

        $dadosPaciente = $this->PacientesModel->pegaPacientePorCodPaciente($dadosExames->codPaciente);

        $nomePaciente = $dadosPaciente->nomeExibicao;

        $fields = array();

        $fields['chegou'] = 1;
        $fields['codStatus'] = 1;
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['horaChegada'] = date('Y-m-d H:i');


        if (session()->codPessoa !== NULL) {
            $funcionario = 1;
            $fields['codAutor'] = session()->codPessoa;
            $pessoa = $this->PessoasModel->pegaPessoaDepartamento(session()->codPessoa);
            $nomeFuncionarioResponsavel = $pessoa->nomeExibicao;
            $nomeDepartamentoResponsavel = $pessoa->descricaoDepartamento;
        } else {
            $funcionario = 0;
            if (session()->codPaciente !== NULL) {
                $fields['codAutor'] = session()->codPaciente;
            } else {
                $fields['codAutor'] = 0;
            }
        }


        if (!$this->validation->check($codExame, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($this->ExamesModel->update($codExame, $fields)) {


                //ENVIAR NOTIFICAÇÃO
                //sleep(3);
                if ($dadosPaciente->emailPessoal !== NULL and $dadosPaciente->emailPessoal !== "" and $dadosPaciente->emailPessoal !== " ") {
                    $email = $dadosPaciente->emailPessoal;
                    $email = removeCaracteresIndesejadosEmail($email);
                } else {
                    $email = NULL;
                }

                if ($email !== NULL and $dadosPaciente->nomeExibicao !== NULL) {
                    $conteudo = "
                                <div> Caro senhor(a), " . $nomePaciente . ",</div>";
                    $conteudo .= "<div>Obrigado por comparecer ao exame do(a) " . $especialista . " ( " . $especialidade . ").</div>";
                    $conteudo .= "<div>Sua presença foi registada em " . date("d/m/Y  H:i") . ".</div>";
                    $conteudo .= "<div style='font-size: 12px;margin-top:16px'>Atenciosamente,</div>";
                    if ($funcionario == 1) {
                        $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeFuncionarioResponsavel . "</div>";
                        $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeDepartamentoResponsavel  . "</div>";
                    }
                    $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . session()->descricaoOrganizacao . "</div>";

                    $resultadoEmail = @email($email, 'PRESENÇA CONFIRMADA #' . $protocolo, $conteudo);
                    if ($resultadoEmail == false) {

                        //ADICIONAR NOTIFICAÇÃO ANA FILA EM CASO DE FALHA
                        @addNotificacoesFila($conteudo, $email, $email, 1);
                    }


                    //ENVIAR SMS
                    $celular = removeCaracteresIndesejados($dadosPaciente->celular);
                    $conteudoSMS = "
                                   Caro senhor(a), " . $nomePaciente . ",";
                    $conteudoSMS .= "Obrigado por comparecer ao exame do(a) " . $especialista . " ( " . $especialidade . ").";

                    $conteudoSMS .= "Atenciosamente, ";
                    $conteudoSMS .= session()->siglaOrganizacao;

                    if ($celular !== NULL  and $celular !== ""  and $celular !== " ") {
                        $resultadoSMS = @sms($celular, $conteudoSMS);
                        if ($resultadoSMS == false) {

                            //ADICIONAR NOTIFICAÇÃO ANA FILA EM CASO DE FALHA
                            @addNotificacoesFila($conteudoSMS, 'Sistema', $celular, 2);
                        }
                    }
                }




                $response['success'] = true;
                $response['csrf_hash'] = csrf_hash();
                $response['messages'] = 'Presença registrada';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na deleção!';
            }
        }

        return $this->response->setJSON($response);
    }


    public function cancelarExame()
    {

        $codExame = $this->request->getPost('codExame');


        $dadosExame = $this->ExamesModel->pegaPorCodigo($codExame);


        $especialidade = $dadosExame->descricaoExameLista;
        $especialista = $dadosExame->nomeExibicao;
        $protocolo = $dadosExame->protocolo;

        $fields['codStatus'] = 4;
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['codAutor'] = session()->codPessoa;

        if (!$this->validation->check($codExame, 'required|numeric')) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();

            return $this->response->setJSON($response);
        } else {
            $this->ExamesModel->update($codExame, $fields);
        }



        $nomePaciente = $dadosExame->nomeExibicao;

        $fields = array();

        $fields['codStatus'] = 4;
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['encerramentoAtendimento'] = date('Y-m-d H:i');


        if (session()->codPessoa !== NULL) {
            $funcionario = 1;
            $fields['codAutor'] = session()->codPessoa;
            $pessoa = $this->PessoasModel->pegaPessoaDepartamento(session()->codPessoa);
            $nomeFuncionarioResponsavel = $pessoa->nomeExibicao;
            $nomeDepartamentoResponsavel = $pessoa->descricaoDepartamento;
        } else {
            $funcionario = 0;
            if (session()->codPaciente !== NULL) {
                $fields['codAutor'] = session()->codPaciente;
            } else {
                $fields['codAutor'] = 0;
            }
        }





        if (!$this->validation->check($codExame, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {


            if ($codExame == !NULL and $codExame !== "" and $codExame !== " ") {


                if ($this->ExamesModel->update($codExame, $fields)) {


                    //ENVIAR NOTIFICAÇÃO
                    // sleep(3);
                    if ($dadosExame->emailPessoalPaciente !== NULL and $dadosExame->emailPessoalPaciente !== "" and $dadosExame->emailPessoalPaciente !== " ") {
                        $email = $dadosExame->emailPessoalPaciente;
                        $email = removeCaracteresIndesejadosEmail($email);
                    } else {
                        $email = NULL;
                    }

                    if ($email !== NULL and $dadosExame->nomeExibicao !== NULL) {
                        $conteudo = "
                                    <div> Caro senhor(a), " . $nomePaciente . ",</div>";
                        $conteudo .= "<div>Seu exame foi cancelado com o(a) " . $especialista . " ( " . $especialidade . ").</div>";
                        $conteudo .= "<div>Em " . date("d/m/Y  H:i") . ".</div>";
                        $conteudo .= "<div style='font-size: 12px;margin-top:16px'>Atenciosamente,</div>";
                        if ($funcionario == 1) {
                            $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeFuncionarioResponsavel . "</div>";
                            $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeDepartamentoResponsavel  . "</div>";
                        }
                        $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . session()->descricaoOrganizacao . "</div>";

                        $resultadoEmail = @email($email, 'CONSULTA ENCERRADA #' . $protocolo, $conteudo);
                        if ($resultadoEmail == false) {

                            //ADICIONAR NOTIFICAÇÃO ANA FILA EM CASO DE FALHA
                            @addNotificacoesFila($conteudo, $email, $email, 1);
                        }


                        //ENVIAR SMS
                        $celular = removeCaracteresIndesejados($dadosExame->celularPaciente);
                        $conteudoSMS = "
                                Caro senhor(a), " . $nomePaciente . ",";
                        $conteudoSMS .= "Seu exame foi cancelado com o(a) " . $especialista . " ( " . $especialidade . ").";

                        $conteudoSMS .= "Atenciosamente, ";
                        $conteudoSMS .= session()->siglaOrganizacao;

                        if ($celular !== NULL  and $celular !== ""  and $celular !== " ") {
                            $resultadoSMS = @sms($celular, $conteudoSMS);
                            if ($resultadoSMS == false) {

                                //ADICIONAR NOTIFICAÇÃO ANA FILA EM CASO DE FALHA
                                @addNotificacoesFila($conteudoSMS, 'Sistema', $celular, 2);
                            }
                        }
                    }




                    $response['success'] = true;
                    $response['csrf_hash'] = csrf_hash();
                    $response['messages'] = 'Exame Cancelado';
                } else {

                    $response['success'] = false;
                    $response['messages'] = 'Erro na alteração de status!';
                }
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na alteração de status!';
            }
        }

        return $this->response->setJSON($response);
    }


    public function marcados()
    {
        $response = array();



        $data['data'] = array();

        $result = $this->ExamesModel->marcados();



        foreach ($result as $key => $value) {



            $ops = '
            <div class="btn-group">
                    <button type="button" class="btn btn-info">Ação</button>
                    <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                      <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu" role="menu">
                    <a href="#" class="dropdown-item" onclick="iniciarExame(' . $value->codExame . ')">Iniciar Exame</a>
                         <a href="#" class="dropdown-item" onclick="encerrarExame(' . $value->codExame . ')">Encerrar Exame</a>
                         <a href="#" class="dropdown-item" onclick="desmarcarExame(' . $value->codExame . ')">Desmarcar</a>
                         <a href="#" class="dropdown-item" onclick="chegouExame(' . $value->codExame . ')">Chegou</a>
                         <a href="#" class="dropdown-item" onclick="chamarPainelExame(' . $value->codExame . ')">Chamar no painel</a>
                         <a href="#" class="dropdown-item" onclick="comprovanteExame(' . $value->codExame . ')">Comprovante de Exame</a>
                         <a href="#" class="dropdown-item" onclick="faltouExame(' . $value->codExame . ')">Faltou</a>
                         <a href="#" class="dropdown-item" onclick="cancelarExame(' . $value->codExame . ')">Cancelar Exame</a>

                         </div>
            </div>

            ';

            if ($value->horaChegada !== NULL) {
                $horaChegada = date('H:i', strtotime($value->horaChegada));
                //$tempoAtendimento = intervaloTempoHoraMinutos($horaChegada, date('Y-m-d H:i'));

                if ($value->encerramentoAtendimento !== NULL) {
                    $tempoAtendimento = intervaloTempoHoraMinutos($horaChegada, $value->encerramentoAtendimento);
                } else {
                    $tempoAtendimento = intervaloTempoHoraMinutos($horaChegada, date('Y-m-d H:i'));
                }
            } else {
                $horaChegada = "";
                $tempoAtendimento = "";
            }




            if ($value->codStatus == 3) {
                $statusChegou = '<i class="fa fa-thumbs-down text-center" style="font-size:30px;color:red"></i>';
            } else {
                if ($value->chegou == 1) {
                    $statusChegou = '<i class="fa fa-thumbs-up text-center" style="font-size:30px;color:#098f09"></i>';
                } else {
                    $statusChegou = "-";
                }
            }





            $status = '';

            if ($value->codStatus == 1) {
                if ($value->chegou == 1) {
                    $status = '<span class="right badge badge-warning">Aguardando</span>';
                }
            }


            if ($value->codStatus == 2) {
                $status = '<span class="right badge badge-success">Atendido</span>';
            }

            if ($value->codStatus == 3) {
                $status = '<span class="right badge badge-danger">Faltou</span>';
            }

            if ($value->codStatus == 4) {
                $status = '<span class="right badge badge-danger">Cancelada</span>';
            }

            $periodo = date('d/m', strtotime($value->dataInicio)) . " das " . date('H:i', strtotime($value->dataInicio)) . " às " . date('H:i', strtotime($value->dataEncerramento));
            $fotoPerfil = '<img  alt="" style="width:30px" src="' . base_url() . '/arquivos/imagens/pacientes/' . $value->fotoPerfil . '" class="img-circle elevation-2">';
            $exameLista = $value->descricaoExameLista . " (" . $value->nomeEspecialista . ")";
            $data['data'][$key] = array(
                $fotoPerfil . " " . $value->nomePaciente . " (" . $value->idade . ")" . '<a onclick="chamarPainel(' . $value->codExame . ')" href="#"><i style="color:#007bff" class="fas fa-bullhorn"></i></a>',
                $exameLista,
                $value->observacoes,
                $periodo,
                $value->celular,
                $statusChegou,
                $horaChegada,
                $tempoAtendimento,
                $status,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }

    public function marcadosListaImprimir()
    {
        $response = array();



        $data['data'] = array();

        $result = $this->ExamesModel->marcados();
        $x = 0;
        foreach ($result as $key => $value) {
            $x++;


            $ops = '
            <div class="btn-group">
                    <button type="button" class="btn btn-info">Ação</button>
                    <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                      <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu" role="menu">
                      <a class="dropdown-item" onclick="desmarcarExame(' . $value->codExame . ')">Desmarcar</a>
                      <a class="dropdown-item" onclick="confirmouExame(' . $value->codExame . ')">Confirmar</a>
                      <a class="dropdown-item" onclick="chegouExame(' . $value->codExame . ')">Chegou</a>
                      <a class="dropdown-item" onclick="chamarPainelExame(' . $value->codExame . ')">Chamar no painel</a>
                      <a class="dropdown-item" onclick="imprimirComprovanteExame(' . $value->codExame . ')">Comprovante de Exame</a>
                      <a href="#" class="dropdown-item" onclick="faltouExame(' . $value->codExame . ')">Faltou</a>
                      </div>
                  </div>

            ';

            if ($value->chegou == 1) {
                $statusChegou = 'SIM';
            } else {
                $statusChegou = '<div class="icheck-primary d-inline">
                <input type="checkbox" id="checkboxPrimary1">
               </div>';
            }

            if ($value->confirmou == 1) {
                $statusConfirmou = 'SIM';
            } else {
                $statusConfirmou = '<div class="icheck-primary d-inline">
                <input type="checkbox" id="checkboxPrimary1">
               </div>';
            }

            //outrosContatos

            $todosContatos = $value->celular . " | ";

            $outrosContatosPaciente = $this->ExamesModel->outrosContatosPorPaciente($value->codPaciente);

            foreach ($outrosContatosPaciente as $outrocontato) {
                $todosContatos .= $outrocontato->numeroContato . " | ";
            }

            $todosContatos = rtrim($todosContatos, "| ");

            $periodo = date('d/m', strtotime($value->dataInicio)) . " das " . date('H:i', strtotime($value->dataInicio)) . " às " . date('H:i', strtotime($value->dataEncerramento));
            $fotoPerfil = '<img  alt="" style="width:30px" src="' . base_url() . '/arquivos/imagens/pacientes/' . $value->fotoPerfil . '" class="img-circle elevation-2">';
            $exameLista = $value->descricaoExameLista . " (" . $value->nomeEspecialista . ")";
            $data['data'][$key] = array(
                $x,
                $fotoPerfil . " " . $value->nomePaciente,
                $exameLista,
                $value->observacoes,
                $periodo,
                $todosContatos,
                $value->codPlano,
                $value->codProntuario,
                $value->nomeStatus,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }

    public function enviarPorEmailDados()
    {
        $response = array();



        $data['data'] = array();


        $codExame = $this->request->getPost('codExame');
        $exame = $this->ExamesModel->comprovante($codExame);
        $organizacao =  $this->OrganizacoesModel->pegaOrganizacao(session()->codOrganizacao);

        if ($exame->emailPessoalPaciente !== null and $exame->emailPessoalPaciente !== '' and $exame->emailPessoalPaciente !== ' ') {
            $data['emailPessoal'] = $exame->emailPessoalPaciente;
            $data['emailPessoalInfo'] = '<span>Seu email "' . $exame->emailPessoalPaciente . '" está correto?</span> <span class="right badge badge-danger"><a onclick="editPacienteForm(' . $exame->codPaciente . ')">CLIQUE AQUI</a></span> <span> para trocar</span>';
        } else {
            $data['emailPessoal'] = null;
            $data['emailPessoalInfo'] = '<span>Email não cadastrado</span> <span class="right badge badge-danger"><a onclick="editPacienteForm(' . $exame->codPaciente . ')">CLIQUE AQUI</a></span> <span>  para cadastrar agora</span>';
        }

        $data['autorMarcacao'] = $exame->autorMarcacao;
        $data['nomePaciente'] = $exame->nomePaciente;
        $data['nomeExame'] = $exame->descricaoExameLista . " | " . $exame->nomeEspecialista . "";
        $data['codProntuario'] = $exame->codProntuario;
        $data['descricaoDepartamento'] = $exame->descricaoDepartamento;
        $data['protocolo'] = $exame->protocolo;
        $data['dataInicio'] = date('d/m/Y H:i', strtotime($exame->dataInicio));
        $data['valorChecksum'] = MD5($exame->codExame . $organizacao->chaveSalgada);
        $data['codExame'] = $codExame;



        if ($exame->descricaoDepartamento == NULL) {
            $data['local'] = session()->siglaOrganizacao;
        } else {
            $data['local'] = $exame->descricaoDepartamento;
        }


        return $this->response->setJSON($data);
    }



    public function comprovante()
    {
        $response = array();



        $data['data'] = array();


        $codExame = $this->request->getPost('codExame');
        $exame = $this->ExamesModel->comprovante($codExame);
        $organizacao =  $this->OrganizacoesModel->pegaOrganizacao(session()->codOrganizacao);


        $data['autorMarcacao'] = $exame->autorMarcacao;
        $data['nomePaciente'] = $exame->nomePaciente;
        $data['codPlano'] = $exame->codPlano;
        $data['nomeExame'] = $exame->descricaoExameLista . " | " . $exame->nomeEspecialista . "";
        $data['codProntuario'] = $exame->codProntuario;
        $data['descricaoDepartamento'] = $exame->descricaoDepartamento;
        $data['protocolo'] = $exame->protocolo;
        $data['dataInicio'] = date('d/m/Y H:i', strtotime($exame->dataInicio));
        $data['valorChecksum'] = MD5($exame->codExame . $organizacao->chaveSalgada);
        $data['codExame'] = $codExame;



        if ($exame->descricaoDepartamento == NULL) {
            $data['local'] = session()->siglaOrganizacao;
        } else {
            $data['local'] = $exame->descricaoDepartamento;
        }


        return $this->response->setJSON($data);
    }


    public function listaDropDownPacientes()
    {

        $result = $this->PacientesModel->listaDropDownPacientes();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function listaDropDownEspecialistasDisponivelMarcacao()
    {

        $codExameLista = $this->request->getPost('codExameLista');
        $result = $this->ExamesListaModel->listaDropDownEspecialistasDisponivelMarcacao($codExameLista);

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function gravarSala()
    {
        $codLocalAtendimento = $this->request->getPost('codLocalAtendimento');
        $localAtendimento = $this->AtendimentoslocaisModel->pegaPorCodigo($codLocalAtendimento);

        $response['success'] = true;
        $response['csrf_hash'] = csrf_hash();
        $response['nomeLocalAtendimento'] =  session()->nomeLocalAtendimento  = $localAtendimento->descricaoLocalAtendimento;
        $response['codLocalAtendimento'] =  session()->codLocalAtendimento  = $localAtendimento->codLocalAtendimento;
        return $this->response->setJSON($response);
    }

    public function chamarPacienteAgora()
    {
        $response = array();
        $data = array();
        $codExame = $this->request->getPost('codExame');
        $dados = $this->ExamesModel->comprovante($codExame);

        if (session()->nomeLocalAtendimento !== NULL) {
            $data['localAtendimento'] = " (" . session()->nomeLocalAtendimento . ")";
        } else {
            $data['localAtendimento'] = "";
        }
        $data['codOrganizacao'] = session()->codOrganizacao;
        $data['codChamador'] = session()->codPessoa;
        $data['qtdChamadas'] = 2;
        $data['codPaciente'] = $dados->codPaciente;
        $data['codExameLista'] = $dados->codExameLista;

        if ($this->PainelChamadasModel->insert($data)) {
            $response['success'] = true;
            $response['csrf_hash'] = csrf_hash();
            $response['messages'] = 'Paciente ' . $dados->nomeCompleto . ' chamado com sucessoo';
        } else {
            $response['success'] = false;
            $response['messages'] = 'Erro ao chamar paciente, contate o administrador do sistema';
        }



        return $this->response->setJSON($response);
    }


    public function verificaSala()
    {
        $response = array();

        if (session()->nomeLocalAtendimento !== NULL) {
            $response['success'] = true;
            $response['csrf_hash'] = csrf_hash();
            $response['botao'] = '<button class="btn btn-warning " onclick="showDefinirSala()" id="Troca sala"><i class="fas fa-edit"></i>Trocar Local de Atendimento</button>';
            $response['nomeLocalAtendimento'] = '

            <div class="spinner-grow text-light spinner-grow-sm" role="status">
			<span class="sr-only">Loading...</span>
			</div><span class="col-md-12">' . session()->nomeLocalAtendimento . '</span>

           '; //session()->nomeLocalAtendimento;
        } else {

            $response['success'] = true;
            $response['csrf_hash'] = csrf_hash();
            $response['botao'] = '<button class="btn btn-warning " onclick="showDefinirSala()" id="Troca sala"><i class="fas fa-edit"></i>Definir Local de Atendimento (Não definida)</button>';
            $response['nomeLocalAtendimento'] = '';
        }
        return $this->response->setJSON($response);
    }
    public function selecionarPacienteReserva()
    {
        $codPacienteMarcacao = $this->request->getPost('codPacienteMarcacao');
    }

    public function marcarPaciente()
    {
        $response = array();
        $codExame = $this->request->getPost('codExame');
        $codPacienteMarcacao = $this->request->getPost('codPacienteMarcacao');
        $observacoes = $this->request->getPost('observacoes');

        $exame = $this->ExamesModel->pegaPorCodigo($codExame);

        if ($exame->codPaciente !== '0') {
            $response['success'] = false;
            $response['messages'] = 'Alguem já marcou nessa data e hora antes de você. Tente outra marcação!';
            return $this->response->setJSON($response);
        }

        $dadosPaciente = $this->PacientesModel->pegaPacientePorCodPaciente($codPacienteMarcacao);


        $response = array();

        if ($observacoes !== NULL and $observacoes !== "" and $observacoes !== " ") {
            $fields['observacoes'] = $observacoes;
        }

        $protocolo = date('Y') . str_pad($codPacienteMarcacao, 6, '0', STR_PAD_LEFT)  . geraNumero(2);
        $fields['codExame'] = $codExame;
        $fields['codOrganizacao'] = session()->codOrganizacao;
        $fields['codPaciente'] = $codPacienteMarcacao;
        $fields['codStatus'] = 1;
        $fields['dataAtualizacao'] = date('Y-m-d H:i');

        $fields['protocolo'] = $protocolo;


        if (session()->codPessoa !== NULL) {
            $funcionario = 1;
            $fields['codAutor'] = session()->codPessoa;
            $pessoa = $this->PessoasModel->pegaPessoaDepartamento(session()->codPessoa);
            $nomeFuncionarioResponsavel = $pessoa->nomeExibicao;
            $nomeDepartamentoResponsavel = $pessoa->descricaoDepartamento;
        } else {
            $funcionario = 0;
            if (session()->codPaciente !== NULL) {
                $fields['codAutor'] = session()->codPaciente;
            } else {
                $fields['codAutor'] = 0;
            }
        }

        $this->validation->setRules([

            'codExame' => ['label' => 'codExame', 'rules' => 'required|numeric|max_length[11]'],
            'codOrganizacao' => ['label' => 'CodOrganizacao', 'rules' => 'required|numeric|max_length[11]'],
            'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'required|numeric|max_length[11]'],
            'codStatus' => ['label' => 'CodStatus', 'rules' => 'required|numeric|max_length[11]'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
            'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
            'protocolo' => ['label' => 'Protocolo', 'rules' => 'required'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->ExamesModel->update($codExame, $fields)) {



                //VERIFICA SE O PACIENTE JÁ ESTAVA NA RESERVA E MODIFICA STATUS PARA RESOLVIDO

                if ($codPacienteMarcacao !== NULL and $codPacienteMarcacao !== "" and $codPacienteMarcacao !== " " and $exame->codExameLista !== NULL and $exame->codExameLista !== "" and $exame->codExameLista !== " ") {
                    $this->ExamesReservasModel->atualizaStatusReserva($codPacienteMarcacao, $exame->codExameLista);
                }




                //ENVIAR NOTIFICAÇÃO

                if ($dadosPaciente->emailPessoal !== NULL and $dadosPaciente->emailPessoal !== "" and $dadosPaciente->emailPessoal !== " ") {
                    $email = $dadosPaciente->emailPessoal;
                    $email = removeCaracteresIndesejadosEmail($email);
                } else {
                    $email = NULL;
                }

                if ($email !== NULL and $dadosPaciente->nomeExibicao !== NULL) {
                    $conteudo = "
                                <div> Caro senhor(a), " . $dadosPaciente->nomeExibicao . ",</div>";
                    $conteudo .= "<div>seu exame foi agendado com sucesso para " . diaSemanaCompleto($exame->dataInicio) . " dia " . date("d/m/Y H:i", strtotime($exame->dataInicio)) . ", através do Protocolo Nr " .  $fields['protocolo'] . ".</div>";

                    $conteudo .= "<span style='margin-top:15px;'>DADOS DO EXAME:";
                    $conteudo .= "<div style='font-size:12px; font-weight: bold;margin-top:10px'>EXAME: <span>" . $exame->descricaoExameLista . "</span></div>";
                    $conteudo .= "<div style='font-size:20px; font-weight: bold;margin-top:0px'>PROFISSIONAL: <span>" . $exame->nomeExibicao . "</span></div>";
                    $conteudo .= "<div style='font-size:20px; font-weight: bold;margin-top:0px'>DATA/HORA: <span>" . date("d/m/Y H:i", strtotime($exame->dataInicio)) . "</span></div>";
                    $conteudo .= "<div style='font-size:20px; font-weight: bold;margin-top:0px'>PROTOCOLO: <span>" . $fields['protocolo'] . "</span></div>";
                    $conteudo .= "<div style='font-size: 12px;margin-top:16px'>Atenciosamente,</div>";
                    if ($funcionario == 1) {
                        $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeFuncionarioResponsavel . "</div>";
                        $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeDepartamentoResponsavel  . "</div>";
                    }
                    $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . session()->descricaoOrganizacao . "</div>";

                    $resultadoEmail = @email($email, 'MARCAÇÃO DE EXAME #' . $fields['protocolo'], $conteudo);
                    if ($resultadoEmail == false) {

                        //ADICIONAR NOTIFICAÇÃO ANA FILA EM CASO DE FALHA
                        @addNotificacoesFila($conteudo, $email, $email, 1);
                    }
                }


                //ENVIAR SMS
                $celular = removeCaracteresIndesejados($dadosPaciente->celular);
                $conteudoSMS = "
                               Caro senhor(a), " . $dadosPaciente->nomeExibicao . ",";
                $conteudoSMS .= " Seu exame para " . $exame->descricaoExameLista . ", " . $exame->nomeExibicao . ",  foi marcado para " . diaSemanaCompleto($exame->dataInicio) . " dia " . date("d/m/Y H:i", strtotime($exame->dataInicio)) . ". Nr protocolo:" . $fields['protocolo'] . ".";

                $conteudoSMS .= "Atenciosamente, ";
                $conteudoSMS .= session()->siglaOrganizacao;

                if ($celular !== NULL  and $celular !== ""  and $celular !== " ") {
                    $resultadoSMS = @sms($celular, $conteudoSMS);
                    if ($resultadoSMS == false) {

                        //ADICIONAR NOTIFICAÇÃO ANA FILA EM CASO DE FALHA
                        @addNotificacoesFila($conteudoSMS, 'Sistema', $celular, 2);
                    }
                }


                $response['success'] = true;
                $response['csrf_hash'] = csrf_hash();
                $response['codExame'] = $codExame;
                $response['messages'] = 'Marcação realziada com sucesso';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na Marcação!';
            }
        }

        return $this->response->setJSON($response);
    }



    public function comprovantePorEmailAgora()
    {
        $response = array();
        $codExame = $this->request->getPost('codExame');
        $codPacienteMarcacao = $this->request->getPost('codPacienteMarcacao');

        $exame = $this->ExamesModel->pegaPorCodigo($codExame);

        $response = array();

        $fields['protocolo'] = $exame->protocolo;


        if (session()->codPessoa !== NULL) {
            $funcionario = 1;
            $fields['codAutor'] = session()->codPessoa;
            $pessoa = $this->PessoasModel->pegaPessoaDepartamento(session()->codPessoa);
            $nomeFuncionarioResponsavel = $pessoa->nomeExibicao;
            $nomeDepartamentoResponsavel = $pessoa->descricaoDepartamento;
        } else {
            $funcionario = 0;
            if (session()->codPaciente !== NULL) {
                $fields['codAutor'] = session()->codPaciente;
            } else {
                $fields['codAutor'] = 0;
            }
        }



        //ENVIAR NOTIFICAÇÃO

        if ($exame->emailPessoalPaciente !== NULL and $exame->emailPessoalPaciente !== "" and $exame->emailPessoalPaciente !== " ") {
            $email = $exame->emailPessoalPaciente;
            $email = removeCaracteresIndesejadosEmail($email);
        } else {
            $email = NULL;
        }

        if ($email !== NULL and $exame->nomeExibicao !== NULL) {
            $conteudo = "
                                <div> Caro senhor(a), " . $exame->nomeExibicao . ",</div>";
            $conteudo .= "<div>Seu exame foi agendado com sucesso para " . diaSemanaCompleto($exame->dataInicio) . " dia " . date("d/m/Y H:i", strtotime($exame->dataInicio)) . ", através do Protocolo Nr " .  $fields['protocolo'] . ".</div>";

            $conteudo .= "<span style='margin-top:15px;'>DADOS DA CONSULTA:";
            $conteudo .= "<div style='font-size:12px; font-weight: bold;margin-top:10px'>EXAME: <span>" . $exame->descricaoExameLista . "</span></div>";
            $conteudo .= "<div style='font-size:20px; font-weight: bold;margin-top:0px'>PROFISSIONAL: <span>" . $exame->nomeExibicao . "</span></div>";
            $conteudo .= "<div style='font-size:20px; font-weight: bold;margin-top:0px'>DATA/HORA: <span>" . date("d/m/Y H:i", strtotime($exame->dataInicio)) . "</span></div>";
            $conteudo .= "<div style='font-size:20px; font-weight: bold;margin-top:0px'>PROTOCOLO: <span>" . $fields['protocolo'] . "</span></div>";
            $conteudo .= "<div style='font-size: 12px;margin-top:16px'>Atenciosamente,</div>";
            if ($funcionario == 1) {
                $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeFuncionarioResponsavel . "</div>";
                $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeDepartamentoResponsavel  . "</div>";
            }
            $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . session()->descricaoOrganizacao . "</div>";

            $resultadoEmail = @email($email, 'MARCAÇÃO DE EXAME #' . $fields['protocolo'], $conteudo);
            if ($resultadoEmail == false) {

                //ADICIONAR NOTIFICAÇÃO ANA FILA EM CASO DE FALHA
                @addNotificacoesFila($conteudo, $email, $email, 1);
            }
        }


        //ENVIAR SMS
        $celular = removeCaracteresIndesejados($exame->celularPacinte);
        $conteudoSMS = "
                               Caro senhor(a), " . $exame->nomeExibicao . ",";
        $conteudoSMS .= " Seu exame para " . $exame->descricaoExameLista . ", " . $exame->nomeExibicao . ",  foi marcado para " . diaSemanaCompleto($exame->dataInicio) . " dia " . date("d/m/Y H:i", strtotime($exame->dataInicio)) . ". Nr protocolo:" . $fields['protocolo'] . ".";

        $conteudoSMS .= "Atenciosamente, ";
        $conteudoSMS .= session()->siglaOrganizacao;

        if ($celular !== NULL  and $celular !== ""  and $celular !== " ") {
            $resultadoSMS = @sms($celular, $conteudoSMS);
            if ($resultadoSMS == false) {

                //ADICIONAR NOTIFICAÇÃO ANA FILA EM CASO DE FALHA
                @addNotificacoesFila($conteudoSMS, 'Sistema', $celular, 2);
            }
        }


        $response['success'] = true;
        $response['csrf_hash'] = csrf_hash();
        $response['codExame'] = $codExame;
        $response['messages'] = 'Dados do exame enviados por E-mail';

        return $this->response->setJSON($response);
    }


    public function procurarPaciente()
    {
        $response = array();
        $nomeCpf = $this->request->getPost('nomeCpf');

        if (is_numeric($nomeCpf)) {
            $nomeCpf = removeCaracteresIndesejados($nomeCpf);
        }

        if ($nomeCpf !== NULL) {

            $pacientes = $this->PacientesModel->pegaNomeCpfOuPREC($nomeCpf);
            if ($nomeCpf !== NULL) {

                $html = '
                <div class="row">

                    <div class="form-group clearfix">

                ';
                foreach ($pacientes as $paciente) {
                    $nomeCompleto =  $paciente->nomeCompleto;
                    $cpf =  $paciente->cpf;
                    $codPaciente =  $paciente->codPaciente;

                    $html .= '


                        <div class="row">
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="pacienteMarcar' . $codPaciente . '" name="codPacienteMarcacao" value=' . $codPaciente . '>
                                <label id="label' . $codPaciente . '" for="pacienteMarcar' . $codPaciente . '">' . $nomeCompleto . ' (' . $cpf . ')
                                </label>
                            </div>
                        </div>


                    ';
                }
                $html .= '
                </div>
                </div>';


                $response['success'] = true;
                $response['csrf_hash'] = csrf_hash();
                $response['html'] = $html;
            } else {
                $response['success'] = false;
                $response['messages'] = 'nenhum usuário localizado!';
            }
        } else {

            $response['success'] = false;
            $response['messages'] = 'Você deve informar os dados do Paciente';
        }
        return $this->response->setJSON($response);
    }


    public function examesPorExameLista()
    {
        $response = array();


        $codExameLista = session()->filtroExameLista["codExameLista"];
        $codEspecialista = session()->filtroExameLista["codEspecialista"];


        $exames = $this->ExamesModel->examesPorExameLista();


        //VERIFICA SE PERMITE CADASTRO RESERVA
        $verificaCadastroReserva = $this->ExamesModel->verificaCadastroReserva($codExameLista);


        if ($exames == 'noExameLista') {

            $response['success'] = true;
            $response['csrf_hash'] = csrf_hash();
            $response['slotsLivres'] = '<div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban"></i> AVISO!</h5>
            Informe ao menos uma exameLista!
          </div>';
            return $this->response->setJSON($response);
        }



        if (empty($exames) or $exames == NULL) {

            sleep(3);
            $response['success'] = true;
            $response['csrf_hash'] = csrf_hash();
            if ($verificaCadastroReserva->cadastroReserva == 0) {
                $response['slotsLivres'] = '<div style="background:#fd7e1473 !important" class="alert alert-warning alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h5><i class="icon fas fa-ban"></i> <b>AVISO!</b></h5>
    Nenhuma vaga encontrada!
   </div>';
            } else {
                $response['slotsLivres'] = '<div style="background:#3498db38 !important" class="alert alert-warning alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h5><i class="icon fas fa-ban"></i> <b>AVISO!</b></h5>
    Nenhuma vaga encontrada!
    Deseja entrar na lista de reservas?
    <button onclick="entrarListaReservaExames(\'' . $codExameLista . '\',\'' . '\')" type="button" class="btn btn-lg btn-success" data-dismiss="alert" aria-hidden="true"> ENTRAR NA LISTA DE RESERVA</button>
  </div>';
            }



            return $this->response->setJSON($response);
        }
        $especialistas = array();
        foreach ($exames as $especialista) {
            if (!in_array($especialista->codEspecialista, $especialistas) and $especialista->codEspecialista !== NULL) {
                array_push($especialistas, $especialista->codEspecialista);
            }
        }

        $slotsLivres = '';
        $slotsLivres .= '<div class="row">';
        foreach ($especialistas as $especialista) {


            $dadosEspecialista = $this->ExamesModel->pegaPessoa($especialista);


            $slotsLivres .= '

        <style>
            .bordaFotoEspecialista {
                background: -webkit-linear-gradient(left top, #1a712e 0%, #2adb50 100%);
                border-radius: 100px;
                padding: 6px;

            }

        </style>






            <div class="col-md-12">
                <div class="form-group">

                        <a style="font-size:20px;" href="#" onclick="perfilMedico(' . $dadosEspecialista->codPessoa . ')">
                            <div class="bordaFotoEspecialista">
                                <img  alt="" style="width:80px" src="' . base_url() . '/arquivos/imagens/pessoas/' . $dadosEspecialista->fotoPerfil . '" class="img-circle elevation-2">
                                <span  style="font-size:20px; color:yellow;font-weight: bold;">
                                ' . $dadosEspecialista->nomeExibicao . '

                                </span>
                            </div>

                        </a>



        ';

            $slotsLivres .= '<div style="margin-top:10px" class="row">';
            foreach ($exames as  $key => $exame) {
                if ($especialista == $exame->codEspecialista) {



                    if ($exame->codPaciente == 0) {
                        $hora = date('H:i', strtotime($exame->dataInicio));
                        $diaMes = date('d/m', strtotime($exame->dataInicio));
                        $diaSenma = diaSemanaAbreviado($exame->dataInicio);

                        $botao = 'btn-outline-primary';

                        if ($exame->nomeTipo == 'CONSULTA') {
                            $botao = 'btn-outline-success';
                        }
                        if ($exame->nomeTipo == 'RETORNO') {
                            $botao = 'btn-info';
                        }

                        $slotsLivres .= '

                    <div class="col-md-3">
                        <div class="form-group">
                            <button type="button" onclick="escolhaPacienteExame(' . $exame->codExame . ')" class="btn btn-block ' . $botao . ' btn-lg">
                            <div>' . $exame->nomeTipo . '</div>
                            <div>' . "(" . $diaSenma . ") " . $diaMes . ' - ' . $hora . ' </div>
                            </button>
                        </div>
                    </div>
                    ';
                    } else {
                    }
                }
            }
            $slotsLivres .= '
            </div>
            </div>
            </div>';
        }
        $slotsLivres .= '</div>';


        sleep(4);

        $response['success'] = true;
        $response['csrf_hash'] = csrf_hash();
        $response['slotsLivres'] = $slotsLivres;
        return $this->response->setJSON($response);
    }




    public function nomePaciente()
    {

        $codPaciente = $this->request->getPost('codPaciente');
        $paciente = $this->PacientesModel->pegaPacientePorCodPaciente($codPaciente);

        $response['success'] = true;
        $response['csrf_hash'] = csrf_hash();
        $response['nomePaciente'] =  $paciente->nomeExibicao;
        return $this->response->setJSON($response);
    }


    public function getOne()
    {
        $response = array();

        $id = $this->request->getPost('codExame');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->ExamesModel->pegaPorCodigo($id);

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function add()
    {

        $response = array();

        $fields['codExame'] = $this->request->getPost('codExame');
        $fields['codConfig'] = $this->request->getPost('codConfig');
        $fields['codOrganizacao'] = $this->request->getPost('codOrganizacao');
        $fields['codPaciente'] = $this->request->getPost('codPaciente');
        $fields['codLocal'] = $this->request->getPost('codLocal');
        $fields['codEspecialista'] = $this->request->getPost('codEspecialista');
        $fields['codExameLista'] = $this->request->getPost('codExameLista');
        $fields['codStatus'] = $this->request->getPost('codStatus');
        $fields['dataCriacao'] = $this->request->getPost('dataCriacao');
        $fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');
        $fields['dataInicio'] = $this->request->getPost('dataInicio');
        $fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');
        $fields['codTipoExame'] = $this->request->getPost('codTipoExame');
        $fields['codAutor'] = $this->request->getPost('codAutor');
        $fields['protocolo'] = $this->request->getPost('protocolo');
        $fields['ordemAtendimento'] = $this->request->getPost('ordemAtendimento');


        $this->validation->setRules([
            'codConfig' => ['label' => 'CodConfig', 'rules' => 'required|numeric|max_length[11]'],
            'codOrganizacao' => ['label' => 'CodOrganizacao', 'rules' => 'required|numeric|max_length[11]'],
            'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'required|numeric|max_length[11]'],
            'codLocal' => ['label' => 'CodLocal', 'rules' => 'required|numeric|max_length[11]'],
            'codEspecialista' => ['label' => 'CodEspecialista', 'rules' => 'required|numeric|max_length[11]'],
            'codExameLista' => ['label' => 'CodExameLista', 'rules' => 'required|numeric|max_length[11]'],
            'codStatus' => ['label' => 'CodStatus', 'rules' => 'required|numeric|max_length[11]'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
            'dataInicio' => ['label' => 'DataInicio', 'rules' => 'permit_empty'],
            'dataEncerramento' => ['label' => 'DataEncerramento', 'rules' => 'permit_empty'],
            'codTipoExame' => ['label' => 'codTipoExame', 'rules' => 'required|numeric|max_length[11]'],
            'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
            'protocolo' => ['label' => 'Protocolo', 'rules' => 'required|numeric|max_length[11]'],
            'ordemAtendimento' => ['label' => 'OrdemAtendimento', 'rules' => 'required|numeric|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->ExamesModel->insert($fields)) {

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
    public function reservaUmMinuto()
    {
        $response = array();

        $startTime = date("Y-m-d H:i:s");
        $dataAtualizacao = date('Y-m-d H:i:s', strtotime('+1 minutes', strtotime($startTime)));

        $nomePaciente = $this->request->getPost('nomePaciente');

        $fields['codExame'] = $this->request->getPost('codExame');
        $fields['dataAtualizacao'] = $dataAtualizacao;

        if (!$this->validation->check($fields['codExame'], 'required|numeric')) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {
            $this->ExamesModel->update($fields['codExame'], $fields);
        }


        $dadosExame = $this->ExamesModel->pegaPorCodigo($fields['codExame']);

        if ($dadosExame !== NULL) {

            $response['success'] = true;
            $response['csrf_hash'] = csrf_hash();

            $html = "

            <div class='callout callout-info'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>


                    <div style='font-size:20px;'>
                        <div style='font-weight:bold'>Verifique atentamente se os dados do exame estão corretos.</div>
                        <div>Paciente:  " .  $nomePaciente .   "</div>
                        <div>Exame:  " . $dadosExame->descricaoExameLista . " | " . $dadosExame->nomeExibicao . "</div>
                        <div>Data/Hora: " . date('d/m/Y H:i', strtotime($dadosExame->dataInicio)) . "</div>
                        <div>Local: " . $dadosExame->descricaoDepartamento . "</div>
                        <div>Observações:</div>
                        <div style='margin-bottom: 10px;'><input type='text' autocomplete='off' id='observacoesMarcacaoExame' 'name='observacoes' class='form-control' placeholder='Informe aqui as observações (Opcional)'  maxlength='70'></div>
                    </div>
            </div>



            ";
            $response['dadosConfirmacao'] = $html;
        } else {
            $response['success'] = false;
        }

        return $this->response->setJSON($response);
    }

    public function edit()
    {

        $response = array();

        $fields['codExame'] = $this->request->getPost('codExame');
        $fields['codConfig'] = $this->request->getPost('codConfig');
        $fields['codOrganizacao'] = $this->request->getPost('codOrganizacao');
        $fields['codPaciente'] = $this->request->getPost('codPaciente');
        $fields['codLocal'] = $this->request->getPost('codLocal');
        $fields['codEspecialista'] = $this->request->getPost('codEspecialista');
        $fields['codExameLista'] = $this->request->getPost('codExameLista');
        $fields['codStatus'] = $this->request->getPost('codStatus');
        $fields['dataCriacao'] = $this->request->getPost('dataCriacao');
        $fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');
        $fields['dataInicio'] = $this->request->getPost('dataInicio');
        $fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');
        $fields['codTipoExame'] = $this->request->getPost('codTipoExame');
        $fields['codAutor'] = $this->request->getPost('codAutor');
        $fields['protocolo'] = $this->request->getPost('protocolo');
        $fields['ordemAtendimento'] = $this->request->getPost('ordemAtendimento');


        $this->validation->setRules([
            'codConfig' => ['label' => 'CodConfig', 'rules' => 'required|numeric|max_length[11]'],
            'codOrganizacao' => ['label' => 'CodOrganizacao', 'rules' => 'required|numeric|max_length[11]'],
            'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'required|numeric|max_length[11]'],
            'codLocal' => ['label' => 'CodLocal', 'rules' => 'required|numeric|max_length[11]'],
            'codEspecialista' => ['label' => 'CodEspecialista', 'rules' => 'required|numeric|max_length[11]'],
            'codExameLista' => ['label' => 'CodExameLista', 'rules' => 'required|numeric|max_length[11]'],
            'codStatus' => ['label' => 'CodStatus', 'rules' => 'required|numeric|max_length[11]'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
            'dataInicio' => ['label' => 'DataInicio', 'rules' => 'permit_empty'],
            'dataEncerramento' => ['label' => 'DataEncerramento', 'rules' => 'permit_empty'],
            'codTipoExame' => ['label' => 'codTipoExame', 'rules' => 'required|numeric|max_length[11]'],
            'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
            'protocolo' => ['label' => 'Protocolo', 'rules' => 'required|numeric|max_length[11]'],
            'ordemAtendimento' => ['label' => 'OrdemAtendimento', 'rules' => 'required|numeric|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->ExamesModel->update($fields['codExame'], $fields)) {

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

    public function desmarcarExame()
    {
        $response = array();

        $codExame = $this->request->getPost('codExame');

        $dadosExame = $this->ExamesModel->pegaPorCodigo($codExame);



        if ($dadosExame->codStatus == 4) {

            $response['success'] = false;
            $response['messages'] = 'Não é possível desmarcar';
            return $this->response->setJSON($response);
        }



        $exameLista = $dadosExame->descricaoExameLista;
        $especialista = $dadosExame->nomeExibicao;
        $protocolo = $dadosExame->protocolo;
        $dataInicio = date('d/m/Y H:i', strtotime($dadosExame->dataInicio));

        $dadosPaciente = $this->PacientesModel->pegaPacientePorCodPaciente($dadosExame->codPaciente);

        $nomePaciente = $dadosPaciente->nomeExibicao;

        $fields = array();

        $fields['codPaciente'] = 0;
        $fields['protocolo'] = NULL;
        $fields['codStatus'] = 0;
        $fields['dataAtualizacao'] = date('Y-m-d H:i');


        if (session()->codPessoa !== NULL) {
            $funcionario = 1;
            $fields['codAutor'] = session()->codPessoa;
            $pessoa = $this->PessoasModel->pegaPessoaDepartamento(session()->codPessoa);
            $nomeFuncionarioResponsavel = $pessoa->nomeExibicao;
            $nomeDepartamentoResponsavel = $pessoa->descricaoDepartamento;
        } else {
            $funcionario = 0;
            if (session()->codPaciente !== NULL) {
                $fields['codAutor'] = session()->codPaciente;
            } else {
                $fields['codAutor'] = 0;
            }
        }


        if (!$this->validation->check($codExame, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($this->ExamesModel->update($codExame, $fields)) {


                //ENVIAR NOTIFICAÇÃO
                // sleep(3);
                if ($dadosPaciente->emailPessoal !== NULL and $dadosPaciente->emailPessoal !== "" and $dadosPaciente->emailPessoal !== " ") {
                    $email = $dadosPaciente->emailPessoal;
                    $email = removeCaracteresIndesejadosEmail($email);
                } else {
                    $email = NULL;
                }

                if ($email !== NULL and $dadosPaciente->nomeExibicao !== NULL) {
                    $conteudo = "
                                 <div> Caro senhor(a), " . $nomePaciente . ",</div>";
                    $conteudo .= "<div>seu exame foi desmarcado às " . date("d/m/Y  H:i") . "</div>";

                    $conteudo .= "<span style='margin-top:15px;'>DADOS DO EXAME DESMARCADA:";
                    $conteudo .= "<div style='font-size:12px; font-weight: bold;margin-top:10px'>EXAME: <span>" . $exameLista . "</span></div>";
                    $conteudo .= "<div style='font-size:20px; font-weight: bold;margin-top:0px'>PROFISSIONAL: <span>" . $especialista . "</span></div>";
                    $conteudo .= "<div style='font-size:20px; font-weight: bold;margin-top:0px'>DATA/HORA: <span>" . $dataInicio . "</span></div>";
                    $conteudo .= "<div style='font-size: 12px;margin-top:16px'>Atenciosamente,</div>";
                    if ($funcionario == 1) {
                        $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeFuncionarioResponsavel . "</div>";
                        $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeDepartamentoResponsavel  . "</div>";
                    }
                    $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . session()->descricaoOrganizacao . "</div>";

                    $resultadoEmail = @email($email, 'CONSULTA DESMARCADA #' . $protocolo, $conteudo);
                    if ($resultadoEmail == false) {

                        //ADICIONAR NOTIFICAÇÃO ANA FILA EM CASO DE FALHA
                        @addNotificacoesFila($conteudo, $email, $email, 1);
                    }


                    //ENVIAR SMS
                    $celular = removeCaracteresIndesejados($dadosPaciente->celular);
                    $conteudoSMS = "
                                     Caro senhor(a), " . $nomePaciente . ",";
                    $conteudoSMS .= "Seu exame para " . $especialista . ", " . $exameLista . ",  foi desmarcado";

                    $conteudoSMS .= "Atenciosamente, ";
                    $conteudoSMS .= session()->siglaOrganizacao;

                    if ($celular !== NULL  and $celular !== ""  and $celular !== " ") {
                        $resultadoSMS = @sms($celular, $conteudoSMS);
                        if ($resultadoSMS == false) {

                            //ADICIONAR NOTIFICAÇÃO ANA FILA EM CASO DE FALHA
                            @addNotificacoesFila($conteudoSMS, 'Sistema', $celular, 2);
                        }
                    }
                }




                $response['success'] = true;
                $response['csrf_hash'] = csrf_hash();
                $response['messages'] = 'Exame foi desmarcado com sucesso';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na deleção!';
            }
        }

        return $this->response->setJSON($response);
    }



    public function faltou()
    {
        $response = array();

        $codExame = $this->request->getPost('codExame');

        $dadosExame = $this->ExamesModel->pegaPorCodigo($codExame);

        $exameLista = $dadosExame->descricaoExameLista;
        $especialista = $dadosExame->nomeExibicao;
        $protocolo = $dadosExame->protocolo;
        $dataInicio = date('d/m/Y H:i', strtotime($dadosExame->dataInicio));

        $dadosPaciente = $this->PacientesModel->pegaPacientePorCodPaciente($dadosExame->codPaciente);

        $nomePaciente = $dadosPaciente->nomeExibicao;

        $fields = array();

        $fields['codPaciente'] = $dadosExame->codPaciente;
        $fields['codExameLista'] = $dadosExame->codExameLista;
        $fields['codEspecialista'] = $dadosExame->codEspecialista;
        $fields['dataCriacao'] = date('Y-m-d H:i');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['dataInicioImpedimento'] = date('Y-m-d');
        $fields['dataEncerramentoImpedimento'] = date('Y-m-d', strtotime(' +30 days'));
        $fields['impedidoAgendar'] = 1;
        $fields['codAutor'] = session()->codPessoa;


        if (session()->codPessoa !== NULL) {
            $funcionario = 1;
            $pessoa = $this->PessoasModel->pegaPessoaDepartamento(session()->codPessoa);
            $nomeFuncionarioResponsavel = $pessoa->nomeExibicao;
            $nomeDepartamentoResponsavel = $pessoa->descricaoDepartamento;
        } else {
            $funcionario = 0;
            if (session()->codPaciente !== NULL) {
                $fields['codAutor'] = session()->codPaciente;
            } else {
                $fields['codAutor'] = 0;
            }
        }


        if (!$this->validation->check($codExame, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($this->ExamesFaltasModel->insert($fields)) {


                //ATUALIZA STATUS NA TABELA DE AGENDAMENTOS

                $statusArray = array();
                $statusArray['codStatus'] = 3; //Faltou
                $statusArray['dataAtualizacao'] = date('Y-m-d H:i');
                $this->ExamesModel->update($codExame, $statusArray);

                //ENVIAR NOTIFICAÇÃO
                // sleep(3);
                if ($dadosPaciente->emailPessoal !== NULL and $dadosPaciente->emailPessoal !== "" and $dadosPaciente->emailPessoal !== " ") {
                    $email = $dadosPaciente->emailPessoal;
                    $email = removeCaracteresIndesejadosEmail($email);
                } else {
                    $email = NULL;
                }

                if ($email !== NULL and $dadosPaciente->nomeExibicao !== NULL) {
                    $conteudo = "
                    <div> Caro senhor(a), " . $nomePaciente . ",</div>";
                    $conteudo .= "<div>Não registramos sua presença no ambulatório " . $exameLista  . " para seu exame com " . $especialista . ", em " . date("d/m/Y  H:i", strtotime($dadosExame->dataInicio)) . "</div>";
                    $conteudo .= "<div>Devido à grande procura por exames, não será possível remarcar para essa exameLista nos próximos 30 dias.</div>";

                    $conteudo .= "<div style='font-size: 12px;margin-top:16px'>Atenciosamente,</div>";
                    if ($funcionario == 1) {
                        $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeFuncionarioResponsavel . "</div>";
                        $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeDepartamentoResponsavel  . "</div>";
                    }
                    $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . session()->descricaoOrganizacao . "</div>";

                    $resultadoEmail = @email($email, 'FALTA EM CONSULTA #' . $protocolo, $conteudo);
                    if ($resultadoEmail == false) {

                        //ADICIONAR NOTIFICAÇÃO NA FILA EM CASO DE FALHA
                        @addNotificacoesFila($conteudo, $email, $email, 1);
                    }


                    //ENVIAR SMS
                    $celular = removeCaracteresIndesejados($dadosPaciente->celular);
                    $conteudoSMS = "
                                     Caro senhor(a), " . $nomePaciente . ",";
                    $conteudoSMS .= "Não registramos sua presença no exame " . $exameLista . ". Devido à grande procura por exames, não será possível remarcar para essa exameLista nos próximos 30 dias.";

                    $conteudoSMS .= "Atenciosamente, ";
                    $conteudoSMS .= session()->siglaOrganizacao;

                    if ($celular !== NULL  and $celular !== ""  and $celular !== " ") {
                        $resultadoSMS = @sms($celular, $conteudoSMS);
                        if ($resultadoSMS == false) {

                            //ADICIONAR NOTIFICAÇÃO ANA FILA EM CASO DE FALHA
                            @addNotificacoesFila($conteudoSMS, 'Sistema', $celular, 2);
                        }
                    }
                }




                $response['success'] = true;
                $response['csrf_hash'] = csrf_hash();
                $response['messages'] = 'Paciente foi registrado com falta e está impedido de agendar para ' . $especialista . ' (' . $exameLista . ')';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na deleção!';
            }
        }

        return $this->response->setJSON($response);
    }

    public function chegouExame()
    {
        $response = array();

        $codExame = $this->request->getPost('codExame');

        $dadosExame = $this->ExamesModel->pegaPorCodigo($codExame);

        $exameLista = $dadosExame->descricaoExameLista;
        $especialista = $dadosExame->nomeExibicao;
        $protocolo = $dadosExame->protocolo;
        $dataInicio = date('d/m/Y H:i', strtotime($dadosExame->dataInicio));

        $dadosPaciente = $this->PacientesModel->pegaPacientePorCodPaciente($dadosExame->codPaciente);

        $nomePaciente = $dadosPaciente->nomeExibicao;

        $fields = array();

        $fields['chegou'] = 1;
        $fields['codStatus'] = 1;
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['horaChegada'] = date('Y-m-d H:i');


        if (session()->codPessoa !== NULL) {
            $funcionario = 1;
            $fields['codAutor'] = session()->codPessoa;
            $pessoa = $this->PessoasModel->pegaPessoaDepartamento(session()->codPessoa);
            $nomeFuncionarioResponsavel = $pessoa->nomeExibicao;
            $nomeDepartamentoResponsavel = $pessoa->descricaoDepartamento;
        } else {
            $funcionario = 0;
            if (session()->codPaciente !== NULL) {
                $fields['codAutor'] = session()->codPaciente;
            } else {
                $fields['codAutor'] = 0;
            }
        }





        if (!$this->validation->check($codExame, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($this->ExamesModel->update($codExame, $fields)) {


                //ENVIAR NOTIFICAÇÃO
                //sleep(3);
                if ($dadosPaciente->emailPessoal !== NULL and $dadosPaciente->emailPessoal !== "" and $dadosPaciente->emailPessoal !== " ") {
                    $email = $dadosPaciente->emailPessoal;
                    $email = removeCaracteresIndesejadosEmail($email);
                } else {
                    $email = NULL;
                }

                if ($email !== NULL and $dadosPaciente->nomeExibicao !== NULL) {
                    $conteudo = "
                                <div> Caro senhor(a), " . $nomePaciente . ",</div>";
                    $conteudo .= "<div>Obrigado por comparecer o exame do(a) " . $especialista . " ( " . $exameLista . ").</div>";
                    $conteudo .= "<div>Sua presença foi registada em " . date("d/m/Y  H:i") . ".</div>";
                    $conteudo .= "<div style='font-size: 12px;margin-top:16px'>Atenciosamente,</div>";
                    if ($funcionario == 1) {
                        $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeFuncionarioResponsavel . "</div>";
                        $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeDepartamentoResponsavel  . "</div>";
                    }
                    $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . session()->descricaoOrganizacao . "</div>";

                    $resultadoEmail = @email($email, 'PRESENÇA CONFIRMADA #' . $protocolo, $conteudo);
                    if ($resultadoEmail == false) {

                        //ADICIONAR NOTIFICAÇÃO ANA FILA EM CASO DE FALHA
                        @addNotificacoesFila($conteudo, $email, $email, 1);
                    }


                    //ENVIAR SMS
                    $celular = removeCaracteresIndesejados($dadosPaciente->celular);
                    $conteudoSMS = "
                                   Caro senhor(a), " . $nomePaciente . ",";
                    $conteudoSMS .= "Obrigado por comparecer ao exame do(a) " . $especialista . " ( " . $exameLista . ").";

                    $conteudoSMS .= "Atenciosamente, ";
                    $conteudoSMS .= session()->siglaOrganizacao;

                    if ($celular !== NULL  and $celular !== ""  and $celular !== " ") {
                        $resultadoSMS = @sms($celular, $conteudoSMS);
                        if ($resultadoSMS == false) {

                            //ADICIONAR NOTIFICAÇÃO ANA FILA EM CASO DE FALHA
                            @addNotificacoesFila($conteudoSMS, 'Sistema', $celular, 2);
                        }
                    }
                }




                $response['success'] = true;
                $response['csrf_hash'] = csrf_hash();
                $response['messages'] = 'Presença registrada';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na deleção!';
            }
        }

        return $this->response->setJSON($response);
    }


    public function encerrarExame()
    {
        $response = array();

        $codExame = $this->request->getPost('codExame');

        $dadosExame = $this->ExamesModel->pegaPorCodigo($codExame);

        $exameLista = $dadosExame->descricaoExameLista;
        $especialista = $dadosExame->nomeExibicao;
        $protocolo = $dadosExame->protocolo;
        $dataInicio = date('d/m/Y H:i', strtotime($dadosExame->dataInicio));

        $dadosPaciente = $this->PacientesModel->pegaPacientePorCodPaciente($dadosExame->codPaciente);

        $nomePaciente = $dadosPaciente->nomeExibicao;

        $fields = array();

        $fields['codStatus'] = 2;
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['encerramentoAtendimento'] = date('Y-m-d H:i');


        if (session()->codPessoa !== NULL) {
            $funcionario = 1;
            $fields['codAutor'] = session()->codPessoa;
            $pessoa = $this->PessoasModel->pegaPessoaDepartamento(session()->codPessoa);
            $nomeFuncionarioResponsavel = $pessoa->nomeExibicao;
            $nomeDepartamentoResponsavel = $pessoa->descricaoDepartamento;
        } else {
            $funcionario = 0;
            if (session()->codPaciente !== NULL) {
                $fields['codAutor'] = session()->codPaciente;
            } else {
                $fields['codAutor'] = 0;
            }
        }



        if (!$this->validation->check($codExame, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($this->ExamesModel->update($codExame, $fields)) {


                //ENVIAR NOTIFICAÇÃO
                // sleep(3);
                if ($dadosPaciente->emailPessoal !== NULL and $dadosPaciente->emailPessoal !== "" and $dadosPaciente->emailPessoal !== " ") {
                    $email = $dadosPaciente->emailPessoal;
                    $email = removeCaracteresIndesejadosEmail($email);
                } else {
                    $email = NULL;
                }

                if ($email !== NULL and $dadosPaciente->nomeExibicao !== NULL) {
                    $conteudo = "
                                <div> Caro senhor(a), " . $nomePaciente . ",</div>";
                    $conteudo .= "<div>Seu exame foi encerrado com o(a) " . $especialista . " ( " . $exameLista . ").</div>";
                    $conteudo .= "<div>Em " . date("d/m/Y  H:i") . ".</div>";
                    $conteudo .= "<div style='font-size: 12px;margin-top:16px'>Atenciosamente,</div>";
                    if ($funcionario == 1) {
                        $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeFuncionarioResponsavel . "</div>";
                        $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeDepartamentoResponsavel  . "</div>";
                    }
                    $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . session()->descricaoOrganizacao . "</div>";

                    $resultadoEmail = @email($email, 'CONSULTA ENCERRADA #' . $protocolo, $conteudo);
                    if ($resultadoEmail == false) {

                        //ADICIONAR NOTIFICAÇÃO ANA FILA EM CASO DE FALHA
                        @addNotificacoesFila($conteudo, $email, $email, 1);
                    }


                    //ENVIAR SMS
                    $celular = removeCaracteresIndesejados($dadosPaciente->celular);
                    $conteudoSMS = "
                                   Caro senhor(a), " . $nomePaciente . ",";
                    $conteudoSMS .= "Seu Exame foi encerrado com o(a) " . $especialista . " ( " . $exameLista . ").";

                    $conteudoSMS .= "Atenciosamente, ";
                    $conteudoSMS .= session()->siglaOrganizacao;

                    if ($celular !== NULL  and $celular !== ""  and $celular !== " ") {
                        $resultadoSMS = @sms($celular, $conteudoSMS);
                        if ($resultadoSMS == false) {

                            //ADICIONAR NOTIFICAÇÃO ANA FILA EM CASO DE FALHA
                            @addNotificacoesFila($conteudoSMS, 'Sistema', $celular, 2);
                        }
                    }
                }




                $response['success'] = true;
                $response['csrf_hash'] = csrf_hash();
                $response['messages'] = 'Consulta Encerrada';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na alteração de status!';
            }
        }

        return $this->response->setJSON($response);
    }

    public function confirmouExame()
    {
        $response = array();

        $codExame = $this->request->getPost('codExame');

        $dadosExame = $this->ExamesModel->pegaPorCodigo($codExame);

        $exameLista = $dadosExame->descricaoExameLista;
        $especialista = $dadosExame->nomeExibicao;
        $protocolo = $dadosExame->protocolo;
        $dataInicio = date('d/m/Y H:i', strtotime($dadosExame->dataInicio));

        $dadosPaciente = $this->PacientesModel->pegaPacientePorCodPaciente($dadosExame->codPaciente);

        $nomePaciente = $dadosPaciente->nomeExibicao;

        $fields = array();

        $fields['confirmou'] = 1;
        $fields['dataAtualizacao'] = date('Y-m-d H:i');


        if (session()->codPessoa !== NULL) {
            $funcionario = 1;
            $fields['codAutor'] = session()->codPessoa;
            $pessoa = $this->PessoasModel->pegaPessoaDepartamento(session()->codPessoa);
            $nomeFuncionarioResponsavel = $pessoa->nomeExibicao;
            $nomeDepartamentoResponsavel = $pessoa->descricaoDepartamento;
        } else {
            $funcionario = 0;
            if (session()->codPaciente !== NULL) {
                $fields['codAutor'] = session()->codPaciente;
            } else {
                $fields['codAutor'] = 0;
            }
        }





        if (!$this->validation->check($codExame, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($this->ExamesModel->update($codExame, $fields)) {


                //ENVIAR NOTIFICAÇÃO
                sleep(3);
                if ($dadosPaciente->emailPessoal !== NULL and $dadosPaciente->emailPessoal !== "" and $dadosPaciente->emailPessoal !== " ") {
                    $email = $dadosPaciente->emailPessoal;
                    $email = removeCaracteresIndesejadosEmail($email);
                } else {
                    $email = NULL;
                }

                if ($email !== NULL and $dadosPaciente->nomeExibicao !== NULL) {
                    $conteudo = "
                                <div> Caro senhor(a), " . $nomePaciente . ",</div>";
                    $conteudo .= "<div>Obrigado por confirmar sua presença no exame do(a) " . $especialista . " ( " . $exameLista . ").</div>";
                    $conteudo .= "<div>Sua presença foi registada em " . date("d/m/Y  H:i") . ".</div>";
                    $conteudo .= "<div style='font-size: 12px;margin-top:16px'>Atenciosamente,</div>";
                    if ($funcionario == 1) {
                        $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeFuncionarioResponsavel . "</div>";
                        $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeDepartamentoResponsavel  . "</div>";
                    }
                    $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . session()->descricaoOrganizacao . "</div>";

                    $resultadoEmail = @email($email, 'PRESENÇA CONFIRMADA #' . $protocolo, $conteudo);
                    if ($resultadoEmail == false) {

                        //ADICIONAR NOTIFICAÇÃO ANA FILA EM CASO DE FALHA
                        @addNotificacoesFila($conteudo, $email, $email, 1);
                    }


                    //ENVIAR SMS
                    $celular = removeCaracteresIndesejados($dadosPaciente->celular);
                    $conteudoSMS = "
                                   Caro senhor(a), " . $nomePaciente . ",";
                    $conteudoSMS .= "Obrigado por confirmar sua presença no exame do(a) " . $especialista . " ( " . $exameLista . ").";

                    $conteudoSMS .= "Atenciosamente, ";
                    $conteudoSMS .= session()->siglaOrganizacao;

                    if ($celular !== NULL  and $celular !== ""  and $celular !== " ") {
                        $resultadoSMS = @sms($celular, $conteudoSMS);
                        if ($resultadoSMS == false) {

                            //ADICIONAR NOTIFICAÇÃO ANA FILA EM CASO DE FALHA
                            @addNotificacoesFila($conteudoSMS, 'Sistema', $celular, 2);
                        }
                    }
                }




                $response['success'] = true;
                $response['csrf_hash'] = csrf_hash();
                $response['messages'] = 'Presença confirmada';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na deleção!';
            }
        }

        return $this->response->setJSON($response);
    }
}
