<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrganizacoesModel;
use App\Models\AtendimentoSenhasModel;
use App\Models\PessoasModel;
use App\Models\PacientesModel;
use App\Models\LogsModel;

use App\Models\EncaminhamentosModel;

class Encaminhamentos extends BaseController
{

    protected $EncaminhamentosModel;
    protected $validation;
    protected $AtendimentoSenhasModel;
    protected $PessoasModel;
    protected $PacientesModel;
    protected $OrganizacoesModel;
    protected $LogsModel;

    public function __construct()
    {

        helper('seguranca_helper');
        verificaSeguranca($this, session(), base_url());
        $this->PessoasModel = new PessoasModel();
        $this->PacientesModel = new PacientesModel();
        $this->OrganizacoesModel = new OrganizacoesModel();
        $this->AtendimentoSenhasModel = new AtendimentoSenhasModel;
        $this->EncaminhamentosModel = new EncaminhamentosModel();
        $this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
        $this->validation =  \Config\Services::validation();
    }

    public function index()
    {


        $permissao = verificaPermissao('Encaminhamentos', 'listar');
        if ($permissao == 0) {
            echo mensagemAcessoNegado(session()->organizacoes);
            $this->LogsModel->inserirLog('Acesso indevido ao Módulo Encaminhamentos', session()->codPessoa);
            exit();
        }

        helper('seguranca_helper');
        verificaSeguranca($this, session(), base_url());



        $data = [
            'controller'        => 'Encaminhamentos',
            'title'             => 'Encaminhamentos'
        ];
        echo view('tema/cabecalho');
        echo view('tema/menu_vertical');
        echo view('tema/menu_horizontal');
        return view('encaminhamentos', $data);
    }


    public function agendamentosLivres()
    {
        $response = array();




        //VERIFICA SE JA TEM AGENDAMENTO

        $verificaSeAgendado = $this->AtendimentoSenhasModel->verificaSeAgendado(session()->codPaciente);


        if ($verificaSeAgendado !== NULL and session()->codPaciente !== NULL and session()->codPaciente !== "") {

            $conteudo = "<div> Caro senhor(a), " . $verificaSeAgendado->nomeExibicao . ",</div>";
            $conteudo .= "<div>Já existe um agendamento marcado para " . date("d/m/Y H:i", strtotime($verificaSeAgendado->dataInicio)) . ". Protocolo Nr " . $verificaSeAgendado->protocolo . ".</div>";
            $conteudo .= "<span style='margin-top:15px;'><b>LOCAL DE ATENDIMENTO:" . $verificaSeAgendado->descricaoDepartamento . "</b>";

            $response['existeAgendamento'] = true;
            $response['codSenhaAtendimento'] = $verificaSeAgendado->codSenhaAtendimento;
            $response['messages'] = $conteudo;
            $response['csrf_hash'] = csrf_hash();
            return $this->response->setJSON($response);
        }

        //DADOS DAS VAGAS
        $encaminhamentos = $this->EncaminhamentosModel->agendamentosLivres();

        //REGISTRA TENTATIVA 
        $this->EncaminhamentosModel->registrarPesquisaVagaPaciente();




        if (empty($encaminhamentos) or $encaminhamentos == NULL) {

            sleep(2);

            $mensagemPaciente = $this->OrganizacoesModel->mensagemPaciente();

            if ($mensagemPaciente->mensagemPaciente !== NULL and $mensagemPaciente->mensagemPaciente !== "") {
                $mensagem = $mensagemPaciente->mensagemPaciente;
            } else {
                $mensagem = 'Nenhuma vaga encontrada!';
            }

            $response['slotsLivres'] = '';
            $response['slotsLivres'] = '

                    <div style="font-size:20px;background:#f11717;color:#fff !important" class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-ban"></i> <b>AVISO!</b></h5>
                        ' . $mensagem . '
                    </div>';

            $response['success'] = true;
            $response['csrf_hash'] = csrf_hash();
            $response['slotsLivres'] =  $response['slotsLivres'];
            return $this->response->setJSON($response);
        } else {



            $slotsLivres = '';
            $slotsLivres .= '<div class="row">';


            foreach ($encaminhamentos as $encaminhamento) {


                $hora = date('H:i', strtotime($encaminhamento->dataInicio));
                $diaMes = date('d/m', strtotime($encaminhamento->dataInicio));
                $diaSenma = diaSemanaAbreviado($encaminhamento->dataInicio);

                $slotsLivres .= '

                    <div class="col-md-3">
                        <div class="form-group">
                            <button type="button" onclick="escolhaPaciente(' . $encaminhamento->codSenhaAtendimento . ')" class="btn btn-outline-primary  btn-lg">
                            <div style="font-weight:bold">' . "(" . $diaSenma . ") " . $diaMes . ' - ' . $hora . ' </div>
                            </button>
                        </div>
                    </div>
                    ';
            }





            $slotsLivres .= '</div>';
        }



        $response['success'] = true;
        $response['csrf_hash'] = csrf_hash();
        $response['slotsLivres'] = $slotsLivres;
        return $this->response->setJSON($response);
    }


    public function reservaUmMinuto()
    {
        $response = array();

        $startTime = date("Y-m-d H:i:s");
        $dataAtualizacao = date('Y-m-d H:i:s', strtotime('+1 minutes', strtotime($startTime)));

        if (session()->codPaciente !== NULL) {

            $dadosPaciente = $this->PacientesModel->organizacaoPaciente(session()->codPaciente);
            $nomePaciente = $dadosPaciente->nomeCompleto;
        } else {
            $nomePaciente = $this->request->getPost('nomePaciente');
        }



        $fields['codSenhaAtendimento'] = $this->request->getPost('codSenhaAtendimento');
        $fields['dataAtualizacao'] = $dataAtualizacao;

        if ($fields['codSenhaAtendimento'] !== NULL and $fields['codSenhaAtendimento'] !== "" and $fields['codSenhaAtendimento'] !== " ") {
            $this->AtendimentoSenhasModel->update($fields['codSenhaAtendimento'], $fields);
        }

        $dadosAgendamento = $this->AtendimentoSenhasModel->pegaPorCodigo($fields['codSenhaAtendimento']);

        if ($dadosAgendamento !== NULL) {

            $response['success'] = true;
            $response['csrf_hash'] = csrf_hash();

            $html = "

            <div class='callout callout-info'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>


                    <div style='font-size:20px'>
                    <div style='font-weight:bold'><span class='right badge badge-success'>Nova Marcação. Confirme os dados!</span> </div>
                    <div>Paciente:  " .  $nomePaciente .   "</div>
                    <div>Data/Hora: " . date('d/m/Y H:i', strtotime($dadosAgendamento->dataInicio)) . "</div>
                    </div>


            </div>



            ";
            $response['dadosConfirmacao'] = $html;
        } else {
            $response['success'] = false;
        }

        return $this->response->setJSON($response);
    }



    public function marcarPaciente()
    {
        $response = array();
        $codSenhaAtendimento = $this->request->getPost('codSenhaAtendimento');
        $codPacienteMarcacao = $this->request->getPost('codPacienteMarcacao');

        $agendamento = $this->AtendimentoSenhasModel->pegaPorCodigo($codSenhaAtendimento);


        if ((int)$agendamento->codPaciente > 0) {
            $response['success'] = false;
            $response['messages'] = 'Alguem já marcou nessa data e hora antes de você. Tente novamente!';
            return $this->response->setJSON($response);
        }



        $dadosPaciente = $this->PacientesModel->pegaPacientePorCodPaciente($codPacienteMarcacao);


        //VERIFICA SE JA TEM AGENDAMENTO

        $verificaSeAgendado = $this->AtendimentoSenhasModel->verificaSeAgendado(session()->codPaciente);


        if ($verificaSeAgendado !== NULL and session()->codPaciente !== NULL and session()->codPaciente !== "") {

            $conteudo = "<div> Caro senhor(a), " . $verificaSeAgendado->nomeExibicao . ",</div>";
            $conteudo .= "<div>Já existe um agendamento marcado para " . date("d/m/Y H:i", strtotime($verificaSeAgendado->dataInicio)) . ". Protocolo Nr " . $verificaSeAgendado->protocolo . ".</div>";
            $conteudo .= "<span style='margin-top:15px;'><b>LOCAL DE ATENDIMENTO:" . $verificaSeAgendado->descricaoDepartamento . "</b>";

            $response['existeAgendamento'] = true;
            $response['codSenhaAtendimento'] = $verificaSeAgendado->codSenhaAtendimento;
            $response['messages'] = $conteudo;
            $response['csrf_hash'] = csrf_hash();
            return $this->response->setJSON($response);
        }


        $response = array();

        $protocolo = date('Y') . str_pad($codPacienteMarcacao, 6, '0', STR_PAD_LEFT)  . geraNumero(2);
        $fields['codSenhaAtendimento'] = $codSenhaAtendimento;
        $fields['codOrganizacao'] = session()->codOrganizacao;
        $fields['codPaciente'] = $codPacienteMarcacao;
        $fields['codStatus'] = 0;
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['dataAgendamento'] = date('Y-m-d H:i');
        $fields['nomePaciente'] = $dadosPaciente->nomeCompleto;
        $fields['idade'] = $dadosPaciente->idade;
        $fields['cpf'] = $dadosPaciente->cpf;
        $fields['protocolo'] = $protocolo;


        if (session()->codPessoa !== NULL) {
            $funcionario = 1;
            $fields['codAutor'] = session()->codPessoa;
            $fields['codAtendente'] = session()->codPessoa;
            $pessoa = $this->PessoasModel->pegaPessoaDepartamento(session()->codPessoa);
            $nomeFuncionarioResponsavel = $pessoa->nomeExibicao;
            $nomeDepartamentoResponsavel = $pessoa->descricaoDepartamento;
        } else {
            $funcionario = 0;
            if (session()->codPaciente !== NULL) {
                $fields['codAutor'] = session()->codPaciente;
                $fields['codAtendente'] = session()->codPaciente;
            }
        }

        $this->validation->setRules([
            'codSenhaAtendimento' => ['label' => 'codSenhaAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'codOrganizacao' => ['label' => 'CodOrganizacao', 'rules' => 'required|numeric|max_length[11]'],
            'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'required|numeric|max_length[11]'],
            'codStatus' => ['label' => 'CodStatus', 'rules' => 'required|numeric|max_length[11]'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
            'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->AtendimentoSenhasModel->update($codSenhaAtendimento, $fields)) {



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
                    $conteudo .= "<div>seu atendimento foi agendado para " .  date("d/m/Y H:i", strtotime($agendamento->dataInicio)) . ". Protocolo Nr " . $fields['protocolo'] . ".</div>";

                    $conteudo .= "<span style='margin-top:15px;'><b>LOCAL DE ATENDIMENTO:" . $agendamento->descricaoDepartamento . "</b>";
                    $conteudo .= "<div style='font-size: 12px;margin-top:16px'>Atenciosamente,</div>";
                    if ($funcionario == 1) {
                        $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeFuncionarioResponsavel . "</div>";
                        $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeDepartamentoResponsavel  . "</div>";
                    }
                    $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . session()->descricaoOrganizacao . "</div>";

                    $resultadoEmail = @email($email, 'AGENDAMENTO ' . $agendamento->descricaoDepartamento . ' #' . $fields['protocolo'], $conteudo);
                    if ($resultadoEmail == false) {

                        //ADICIONAR NOTIFICAÇÃO ANA FILA EM CASO DE FALHA
                        @addNotificacoesFila($conteudo, $email, $email, 1);
                    }
                }


                //ENVIAR SMS
                $celular = removeCaracteresIndesejados($dadosPaciente->celular);
                $conteudoSMS = "
                               Caro senhor(a), " . $dadosPaciente->nomeExibicao . ",";
                $conteudoSMS .= " Seu agendamento para " . $agendamento->descricaoDepartamento . ", foi marcado para " . date("d/m/Y H:i", strtotime($agendamento->dataInicio)) . ". Nr protocolo:" . $fields['protocolo'] . ".";

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
                $response['codSenhaAtendimento'] = $codSenhaAtendimento;
                $response['messages'] = $conteudo;
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na Marcação!';
            }
        }

        return $this->response->setJSON($response);
    }


    
    public function comprovante()
    {
        $response = array();



        $data['data'] = array();


        $codAgendamento = $this->request->getPost('codAgendamento');
        $agendamento = $this->AtendimentoSenhasModel->comprovante($codAgendamento);
        $organizacao =  $this->OrganizacoesModel->pegaOrganizacao(session()->codOrganizacao);
        if ($agendamento->marcadoPor == $agendamento->codPaciente) {
            $autorMarcacao = 'Paciente';
        } else {
            $autorMarcacao = $agendamento->autorMarcacao;
        }
        $data['nomePaciente'] = $agendamento->nomePaciente;
        $data['codPlano'] = $agendamento->codPlano;
        $data['descricaoLocalAtendimento'] = $agendamento->descricaoLocalAtendimento . " (" . $agendamento->descricaoEspecialidade . ")";
        $data['codProntuario'] = $agendamento->codProntuario;
        $data['descricaoDepartamento'] = $agendamento->descricaoDepartamento;
        $data['protocolo'] = $agendamento->protocolo;
        $data['autorMarcacao'] = $autorMarcacao;
        $data['dataInicio'] = date('d/m/Y H:i', strtotime($agendamento->dataInicio));
        $data['valorChecksum'] = MD5($agendamento->codAgendamento . $organizacao->chaveSalgada);
        $data['codAgendamento'] = $codAgendamento;



        if ($agendamento->descricaoDepartamento == NULL) {
            $data['local'] = session()->siglaOrganizacao;
        } else {
            $data['local'] = $agendamento->descricaoLocalAtendimento;
        }


        return $this->response->setJSON($data);
    }


    

    public function desmarcarServico()
    {

        $response = array();

        $codAgendamento = $this->request->getPost('codAgendamento');


        $dadosAgendamento = $this->AtendimentoSenhasModel->pegaPorCodigo($codAgendamento);

        $descricaoDepartamento = $dadosAgendamento->descricaoDepartamento;
        $descricaoLocalAtendimento = $dadosAgendamento->descricaoLocalAtendimento;

        $protocolo = $dadosAgendamento->protocolo;
        $dataInicio = date('d/m/Y H:i', strtotime($dadosAgendamento->dataInicio));

        $dadosPaciente = $this->PacientesModel->pegaPacientePorCodPaciente($dadosAgendamento->codPaciente);

        $nomePaciente = $dadosPaciente->nomeExibicao;

        $fields = array();

        $fields['codPaciente'] = 0;
        $fields['cpf'] = NULL;
        $fields['protocolo'] = NULL;
        $fields['nomePaciente'] = NULL;
        $fields['idade'] = NULL;
        $fields['dataAgendamento'] = NULL;
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





        if (!$this->validation->check($codAgendamento, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($this->AtendimentoSenhasModel->update($codAgendamento, $fields)) {


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
                    $conteudo .= "<div>sua consulta foi desmarcada às " . date("d/m/Y  H:i") . "</div>";

                    $conteudo .= "<span style='margin-top:15px;'>DADOS DA CONSULTA DESMARCADA:";
                    $conteudo .= "<div style='font-size:12px; font-weight: bold;margin-top:10px'>SERVIÇO: <span>" . $descricaoDepartamento   . "</span></div>";
                    $conteudo .= "<div style='font-size:20px; font-weight: bold;margin-top:0px'>LOCAL: <span>" . $descricaoLocalAtendimento. "</span></div>";
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
                    $conteudoSMS .= " Sua consulta para " . $descricaoDepartamento. ", " . $descricaoLocalAtendimento . ",  foi desmarcada";

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
                $response['messages'] = 'Consulta foi desmarcada com sucesso';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na deleção!';
            }
        }

        return $this->response->setJSON($response);
    }
}
