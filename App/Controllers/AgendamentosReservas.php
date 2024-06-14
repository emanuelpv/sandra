<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\PacientesModel;
use App\Models\ComentariosReservasModel;
use App\Models\OrganizacoesModel;
use App\Models\AgendamentosModel;

use App\Models\AgendamentosReservasModel;

class AgendamentosReservas extends BaseController
{

    protected $AgendamentosReservasModel;
    protected $pessoasModel;
    protected $OrganizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $ComentariosReservasModel;
    protected $validation;

    public function __construct()
    {

        helper('seguranca_helper');
        verificaSeguranca($this, session(), base_url());
        $this->AgendamentosReservasModel = new AgendamentosReservasModel();
        $this->PacientesModel = new PacientesModel();
        $this->PessoasModel = new PessoasModel();
        $this->OrganizacoesModel = new OrganizacoesModel();
        $this->ComentariosReservasModel = new ComentariosReservasModel();
        $this->AgendamentosModel = new AgendamentosModel();
        $this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
        $this->validation =  \Config\Services::validation();
        $this->codOrganizacao = session()->codOrganizacao;
        $this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
    }

    public function index()
    {

        $permissao = verificaPermissao('agendamentosReservas', 'listar');
        if ($permissao == 0) {
            echo mensagemAcessoNegado(session()->organizacoes);
            $this->LogsModel->inserirLog('Acesso indevido ao Módulo "AgendamentosReservas"', session()->codPessoa);
            exit();
        }


        $data = [
            'controller'        => 'agendamentosReservas',
            'title'             => 'Reservas'
        ];
        echo view('tema/cabecalho');
        echo view('tema/menu_vertical');
        echo view('tema/menu_horizontal');
        return view('agendamentosReservas', $data);
    }

    public function getAllPendentes()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->AgendamentosReservasModel->pegaTudoPendentes();

        foreach ($result as $key => $value) {

            $ops = '<div class="btn-group">';
            $ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editagendamentosReservas(' . $value->codAgendamentoReserva . ')"><i class="fa fa-edit"></i></button>';
            $ops .= '</div>';

            $diaSemana = "";
            $hora = "";

            if ($value->preferenciaHora == 0) {
                $hora .= "Qualquer Hora";
            }
            if ($value->preferenciaHora == 1) {
                $hora .= "Manhã";
            }
            if ($value->preferenciaHora == 2) {
                $hora .= "Tarde";
            }

            if ($value->segunda == 1) {
                $diaSemana .= "Seg | ";
            }
            if ($value->terca == 1) {
                $diaSemana .= "Ter | ";
            }
            if ($value->quarta == 1) {
                $diaSemana .= "Qua | ";
            }
            if ($value->quinta == 1) {
                $diaSemana .= "Qui | ";
            }
            if ($value->sexta == 1) {
                $diaSemana .= "Sex | ";
            }
            if ($value->sabado == 1) {
                $diaSemana .= "Sab | ";
            }
            if ($value->domingo == 1) {
                $diaSemana .= "Dom | ";
            }
            $corStatus = "danger";
            if ($value->codStatus == 1) {
                $corStatus = "warning";
            }

            if ($value->codStatus == 2) {
                $corStatus = "success";
            }

            $status = '<span style="margin-left:10px" class="right badge badge-' . $corStatus . '">' . $value->descricaoStatus . '</span>';


            $diaSemana = rtrim($diaSemana, "| ");






            if ($value->nomeEspecialsta !== NULL and  $value->nomeEspecialsta !== "") {
                $especialista = $value->nomeEspecialsta;
            } else {
                $especialista = "Qualquer especialista";
            }
            $data['data'][$key] = array(
                $value->protocolo,
                $value->nomeExibicao . " (" . $value->idade . ")",
                $value->descricaoEspecialidade,
                $especialista,
                $status,
                date('d-m-Y', strtotime($value->dataSolicitacao)),
                $diaSemana,
                $hora,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }
    public function getAllResolvidos()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->AgendamentosReservasModel->pegaTudoResolvidos();

        foreach ($result as $key => $value) {

            $ops = '<div class="btn-group">';
            $ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editagendamentosReservas(' . $value->codAgendamentoReserva . ')"><i class="fa fa-edit"></i></button>';
            $ops .= '</div>';

            $diaSemana = "";
            $hora = "";

            if ($value->preferenciaHora == 0) {
                $hora .= "Qualquer Hora";
            }
            if ($value->preferenciaHora == 1) {
                $hora .= "Manhã";
            }
            if ($value->preferenciaHora == 2) {
                $hora .= "Tarde";
            }

            if ($value->segunda == 1) {
                $diaSemana .= "Seg | ";
            }
            if ($value->terca == 1) {
                $diaSemana .= "Ter | ";
            }
            if ($value->quarta == 1) {
                $diaSemana .= "Qua | ";
            }
            if ($value->quinta == 1) {
                $diaSemana .= "Qui | ";
            }
            if ($value->sexta == 1) {
                $diaSemana .= "Sex | ";
            }
            if ($value->sabado == 1) {
                $diaSemana .= "Sab | ";
            }
            if ($value->domingo == 1) {
                $diaSemana .= "Dom | ";
            }
            $corStatus = "danger";
            if ($value->codStatus == 1) {
                $corStatus = "warning";
            }

            if ($value->codStatus == 2) {
                $corStatus = "success";
            }

            $status = '<span style="margin-left:10px" class="right badge badge-' . $corStatus . '">' . $value->descricaoStatus . '</span>';


            $diaSemana = rtrim($diaSemana, "| ");






            if ($value->nomeEspecialsta !== NULL and  $value->nomeEspecialsta !== "") {
                $especialista = $value->nomeEspecialsta;
            } else {
                $especialista = "Qualquer especialista";
            }
            $data['data'][$key] = array(
                $value->protocolo,
                $value->nomeExibicao . " (" . $value->idade . ")",
                $value->descricaoEspecialidade,
                $especialista,
                $status,
                date('d-m-Y', strtotime($value->dataSolicitacao)),
                $diaSemana,
                $hora,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }


    public function getOne()
    {
        $response = array();

        $id = $this->request->getPost('codAgendamentoReserva');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->AgendamentosReservasModel->pegaPorCodigo($id);



            //outrosContatos

            $todosContatos = $data->celular . " | ";

            $outrosContatosPaciente = $this->AgendamentosModel->outrosContatosPorPaciente($data->codPaciente);

            foreach ($outrosContatosPaciente as $outrocontato) {
                $todosContatos .= $outrocontato->numeroContato . " | ";
            }

            $todosContatos = rtrim($todosContatos, "| ");


            $response['contatos'] = $todosContatos;
            $response['codAgendamentoReserva'] = $data->codAgendamentoReserva;
            $response['codOrganizacao'] = $data->codOrganizacao;
            $response['codPaciente'] = $data->codPaciente;
            $response['codEspecialidade'] = $data->codEspecialidade;
            $response['codEspecialista'] = $data->codEspecialista;
            $response['codStatus'] = $data->codStatus;
            $response['dataCriacao'] = $data->dataCriacao;
            $response['dataAtualizacao'] = $data->dataAtualizacao;
            $response['preferenciaDia'] = $data->preferenciaDia;
            $response['preferenciaHora'] = $data->preferenciaHora;
            $response['codAutor'] = $data->codAutor;
            $response['protocolo'] = $data->protocolo;
            $response['nomeExibicao'] = $data->nomeExibicao;
            $response['descricaoEspecialidade'] = $data->descricaoEspecialidade;
            $response['dataSolicitacao'] = $data->dataSolicitacao;
            $response['idade'] = $data->idade;
            $response['descricaoStatus'] = $data->descricaoStatus;
            $response['segunda'] = $data->segunda;
            $response['terca'] = $data->terca;
            $response['quarta'] = $data->quarta;
            $response['quinta'] = $data->quinta;
            $response['sexta'] = $data->sexta;








            $comentarios = $this->AgendamentosReservasModel->comentarios($data->codAgendamentoReserva);

            $html = "";
            foreach ($comentarios as $comentario) {


                $dia = date('d', strtotime($comentario->dataCriacao)) . '/' . nomeMesAbreviado(date('m', strtotime($comentario->dataCriacao))) . '/' . date('Y', strtotime($comentario->dataCriacao));

                $hora = date('H:i', strtotime($comentario->dataCriacao));

                $html .=
                    '
            <div>
                <i class="fas fa-comments bg-yellow"></i>
                <div class="timeline-item">
                    <span class="time">' . $dia . ' ' . $hora . '</span>
                    <h3 class="timeline-header"><a href="#">' . $comentario->nomeColaborador . '</a></h3>
                    <div class="timeline-body">
                    ' . $comentario->comentario . '
                    </div>
                </div>
            </div>';
            }

            $response['html'] = $html;





            return $this->response->setJSON($response);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function demandaReprimidaReserva()
    {
        $response = array();


        $demandaReprimidaReserva = $this->AgendamentosReservasModel->demandaReprimidaReserva();


        $response['html'] = "";
        if (!empty($demandaReprimidaReserva)) {
            $response['html'] .= "<div style='margin-bottom:10px' class='row'>";
            foreach ($demandaReprimidaReserva as $demanda) {
                $response['html'] .= ' 
            <div class="col-md-2">
                <span class="shadow-sm">
                <span style="font-size:12px" class="info-box-text"><b>'.$demanda->descricaoEspecialidade.' - '.$demanda->total.'</b></span>
                </span>
            </div>';
            }
        }
        $response['html'] .= "</div>";

        return $this->response->setJSON($response);
    }

    public function add()
    {

        $response = array();


        if ($this->request->getPost('codPaciente') == NULL or $this->request->getPost('codPaciente') == "") {
            $response['success'] = false;
            $response['messages'] = 'Você deve informar o paciente';

            return $this->response->setJSON($response);
        }


        //VERIFICA EXISTENCIA

        $existe = $this->AgendamentosReservasModel->verificaExistencia($this->request->getPost('codPaciente'), $this->request->getPost('codEspecialidade'));

        if ($existe !== NULL) {
            $response['success'] = false;
            $response['messages'] = 'Você já está na lista de espera desta especialidade';

            return $this->response->setJSON($response);
        }

        //VERIFICA SE EXISTEM MAIS DE 3 RESERVAS

        $maisDeX = $this->AgendamentosReservasModel->verificaExistenciaMaisDeX($this->request->getPost('codPaciente'));

        if ($maisDeX >= 3) {
            $response['success'] = false;
            $response['messages'] = 'Não é possível realizar mais de 3 reservas';

            return $this->response->setJSON($response);
        }

        $fields['codOrganizacao'] = session()->codOrganizacao;
        $fields['codPaciente'] = $this->request->getPost('codPaciente');
        $fields['codEspecialidade'] = $this->request->getPost('codEspecialidade');
        $fields['codEspecialista'] = $this->request->getPost('codEspecialista');
        $fields['codStatus'] = 0;
        $fields['dataCriacao'] = date('Y-m-d H:i');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['preferenciaHora'] = $this->request->getPost('preferenciaHora');
        $fields['protocolo'] = date('Y') . str_pad($this->request->getPost('codPaciente'), 6, '0', STR_PAD_LEFT) . geraNumero(3);




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

        $preferenciaDiasSemama = "";
        $verificaDiaSemana = 0;
        if ($this->request->getPost('segunda') == 'on') {
            $fields['segunda'] = 1;
            $verificaDiaSemana = 1;
            $preferenciaDiasSemama .= "Seg |";
        } else {
            $fields['segunda'] = 0;
        }
        if ($this->request->getPost('terca') == 'on') {
            $fields['terca'] = 1;
            $verificaDiaSemana = 1;
            $preferenciaDiasSemama .= "Ter |";
        } else {
            $fields['terca'] = 0;
        }
        if ($this->request->getPost('quarta') == 'on') {
            $fields['quarta'] = 1;
            $verificaDiaSemana = 1;
            $preferenciaDiasSemama .= "Qua |";
        } else {
            $fields['quarta'] = 0;
        }
        if ($this->request->getPost('quinta') == 'on') {
            $fields['quinta'] = 1;
            $verificaDiaSemana = 1;
            $preferenciaDiasSemama .= "Qui |";
        } else {
            $fields['quinta'] = 0;
        }
        if ($this->request->getPost('sexta') == 'on') {
            $fields['sexta'] = 1;
            $verificaDiaSemana = 1;
            $preferenciaDiasSemama .= "Sex |";
        } else {
            $fields['sexta'] = 0;
        }
        if ($this->request->getPost('sabado') == 'on') {
            $fields['sabado'] = 1;
            $verificaDiaSemana = 1;
            $preferenciaDiasSemama .= "Sab |";
        } else {
            $fields['sabado'] = 0;
        }

        $preferenciaHora = " (Manhã ou Tarde)";
        if ($fields['preferenciaHora'] == 1) {
            $preferenciaHora = " (Manhã)";
        }
        if ($fields['preferenciaHora'] == 2) {
            $preferenciaHora = " (Tarde)";
        }


        $preferenciaDiasSemama = rtrim($preferenciaDiasSemama, "| ");


        if ($verificaDiaSemana == 0) {

            $fields['segunda'] = 1;
            $fields['terca'] = 1;
            $fields['quarta'] = 1;
            $fields['quinta'] = 1;
            $fields['sexta'] = 1;
            $fields['sabado'] = 1;
        }



        $this->validation->setRules([
            'codOrganizacao' => ['label' => 'CodOrganizacao', 'rules' => 'required|numeric|max_length[11]'],
            'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'required|numeric|max_length[11]'],
            'codEspecialidade' => ['label' => 'CodEspecialidade', 'rules' => 'required|numeric|max_length[11]'],
            'codEspecialista' => ['label' => 'CodEspecialista', 'rules' => 'required|numeric|max_length[11]'],
            'codStatus' => ['label' => 'CodStatus', 'rules' => 'required|numeric|max_length[11]'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
            'segunda' => ['label' => 'Segunda', 'rules' => 'permit_empty|max_length[11]'],
            'terca' => ['label' => 'Terça', 'rules' => 'permit_empty|max_length[11]'],
            'quarta' => ['label' => 'Quarta', 'rules' => 'permit_empty|max_length[11]'],
            'quinta' => ['label' => 'Quinta', 'rules' => 'permit_empty|max_length[11]'],
            'sexta' => ['label' => 'Sexta', 'rules' => 'permit_empty|max_length[11]'],
            'sabado' => ['label' => 'Sábado', 'rules' => 'permit_empty|max_length[11]'],
            'preferenciaHora' => ['label' => 'PreferenciaHora', 'rules' => 'required|max_length[11]'],
            'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
            'protocolo' => ['label' => 'Protocolo', 'rules' => 'permit_empty|max_length[20]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->AgendamentosReservasModel->insert($fields)) {


                $dadosPaciente = $this->PacientesModel->pegaPacientePorCodPaciente($fields['codPaciente']);

                $nomeEspecialidade = lookupNomeEspecialidade($fields['codEspecialidade']);
                $nomeEspecialista = lookupEspecialista($fields['codEspecialista']);
                if ($nomeEspecialista == null or $nomeEspecialista == "" or $nomeEspecialista == 0) {
                    $nomeEspecialista = "Qualquer um";
                }



                //ENVIAR NOTIFICAÇÃO

                if ($dadosPaciente->emailPessoal !== NULL and $dadosPaciente->emailPessoal !== "" and $dadosPaciente->emailPessoal !== " ") {
                    $email = $dadosPaciente->emailPessoal;
                    $email = removeCaracteresIndesejadosEmail($email);
                } else {
                    sleep(3); // mesmo que o usuário não tenha email, espera um pouco e mostra a mensagem de "estamos processando seu pedido..."
                    $email = NULL;
                }

                if ($email !== NULL and $dadosPaciente->nomeExibicao !== NULL) {
                    $conteudo = "
                                 <div> Caro senhor(a), " . $dadosPaciente->nomeExibicao . ",</div>";
                    $conteudo .= "<div>Registramos seu pedido de ingresso na fila de espera às " . date("d/m/Y  H:i") . ", através do Protocolo Nr " . $fields['protocolo'] . ".</div>";
                    $conteudo .= "<div>Acompanhe atentamente, através da plataforma, o andamento do seu pedido</div>";

                    $conteudo .= "<span style='margin-top:15px;'>DADOS DA RESERVA:";
                    $conteudo .= "<div style='font-size:12px; font-weight: bold;margin-top:10px'>ESPECIALIDADE: <span>" . $nomeEspecialidade . "</span></div>";
                    $conteudo .= "<div style='font-size:20px; font-weight: bold;margin-top:0px'>PROFISSIONAL: <span>" . $nomeEspecialista . "</span></div>";
                    $conteudo .= "<div style='font-size:20px; font-weight: bold;margin-top:0px'>PREFERÊNCIAS: <span>" . $preferenciaDiasSemama . " - " . $preferenciaHora . "</span></div>";
                    $conteudo .= "<div style='font-size:20px; font-weight: bold;margin-top:0px'>PROTOCOLO: <span>" . $fields['protocolo'] . "</span></div>";
                    $conteudo .= "<div style='font-size: 12px;margin-top:16px'>Atenciosamente,</div>";
                    if ($funcionario == 1) {
                        $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeFuncionarioResponsavel . "</div>";
                        $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeDepartamentoResponsavel  . "</div>";
                    }
                    $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . session()->descricaoOrganizacao . "</div>";

                    $resultadoEmail = @email($email, 'LISTA DE ESPERA #' . $fields['protocolo'], $conteudo);
                    if ($resultadoEmail == false) {

                        //ADICIONAR NOTIFICAÇÃO ANA FILA EM CASO DE FALHA
                        @addNotificacoesFila($conteudo, $email, $email, 1);
                    }
                }


                //ENVIAR SMS
                $celular = removeCaracteresIndesejados($dadosPaciente->celular);
                $conteudoSMS = "
                             Caro senhor(a), " . $dadosPaciente->nomeExibicao . ",";
                $conteudoSMS .= "Você entrou na lista de espera para consulta para " . $nomeEspecialidade . ", " . $nomeEspecialista . ", em " . date("d/m/Y H:i", strtotime(date('Y-m-d H:i'))) . ". Nr protocolo:" . $fields['protocolo'] . ".";

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
                $response['messages'] = 'Informação inserida com sucesso';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na inserção!';
            }
        }

        return $this->response->setJSON($response);
    }



    public function comentarios()
    {

        $response = array();

        $codAgendamentoReserva = $this->request->getPost('codAgendamentoReserva');



        return $this->response->setJSON($response);
    }




    public function addComentario()
    {

        $response = array();


        $fields['codAgendamentoReserva'] = $this->request->getPost('codAgendamentoReserva');
        $fields['comentario'] = $this->request->getPost('comentario');
        $fields['dataCriacao'] = date('Y-m-d H:i');

        if (session()->codPessoa !== NULL) {
            $fields['codPessoa'] = session()->codPessoa;
        } else {
            $fields['codPessoa'] = 0;
        }

        if ($this->request->getPost('codPaciente') !== NULL) {
            $fields['codPaciente'] = $this->request->getPost('codPaciente');
        } else {

            if (session()->codPaciente !== NULL) {
                $fields['codPaciente'] = session()->codPaciente;
            } else {
                $fields['codPaciente'] = 0;
            }
        }


        $this->validation->setRules([
            'codAgendamentoReserva' => ['label' => 'CodAgendamentoReserva', 'rules' => 'required|numeric|max_length[11]'],
            'comentario' => ['label' => 'Comentario', 'rules' => 'required'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
            'codPessoa' => ['label' => 'CodPessoa', 'rules' => 'required|numeric|max_length[11]'],
            'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'required|numeric|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->ComentariosReservasModel->insert($fields)) {



                $comentarios = $this->AgendamentosReservasModel->comentarios($fields['codAgendamentoReserva']);

                $html = "";

                foreach ($comentarios as $comentario) {


                    $dia = date('d', strtotime($comentario->dataCriacao)) . '/' . nomeMesAbreviado(date('m', strtotime($comentario->dataCriacao))) . '/' . date('Y', strtotime($comentario->dataCriacao));

                    $hora = date('H:i', strtotime($comentario->dataCriacao));

                    $html .=
                        '
                    <div>
                        <i class="fas fa-comments bg-yellow"></i>
                        <div class="timeline-item">
                            <span class="time">' . $dia . ' ' . $hora . '</span>
                            <h3 class="timeline-header"><a href="#">' . $comentario->nomeColaborador . '</a></h3>
                            <div class="timeline-body">
                            ' . $comentario->comentario . '
                            </div>
                        </div>
                    </div>';
                }

                $response['html'] = $html;




                $response['success'] = true;
                $response['messages'] = 'Informação inserida com sucesso';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na inserção!';
            }
        }

        return $this->response->setJSON($response);
    }


    public function encerrarCaso()
    {

        $response = array();


        $fields['codAgendamentoReserva'] = $this->request->getPost('codAgendamentoReserva');
        $fields['comentario'] = $this->request->getPost('comentario');
        $fields['dataCriacao'] = date('Y-m-d H:i');

        if (session()->codPessoa !== NULL) {
            $fields['codPessoa'] = session()->codPessoa;
        } else {
            $fields['codPessoa'] = 0;
        }

        if ($this->request->getPost('codPaciente') !== NULL) {
            $fields['codPaciente'] = $this->request->getPost('codPaciente');
        } else {

            if (session()->codPaciente !== NULL) {
                $fields['codPaciente'] = session()->codPaciente;
            } else {
                $fields['codPaciente'] = 0;
            }
        }


        $this->validation->setRules([
            'codAgendamentoReserva' => ['label' => 'CodAgendamentoReserva', 'rules' => 'required|numeric|max_length[11]'],
            'comentario' => ['label' => 'Comentario', 'rules' => 'required'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
            'codPessoa' => ['label' => 'CodPessoa', 'rules' => 'required|numeric|max_length[11]'],
            'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'required|numeric|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {


            $fields['codAgendamentoReserva'] = $this->request->getPost('codAgendamentoReserva');
            $fields['codStatus'] = 2; // 2 concluido
            $fields['dataAtualizacao'] = date('Y-m-d H:i');
            $fields['codAutor'] = session()->codPessoa;
            if ($this->AgendamentosReservasModel->update($fields['codAgendamentoReserva'], $fields)) {


                if ($this->ComentariosReservasModel->insert($fields)) {



                    $comentarios = $this->AgendamentosReservasModel->comentarios($fields['codAgendamentoReserva']);

                    $html = "";

                    foreach ($comentarios as $comentario) {


                        $dia = date('d', strtotime($comentario->dataCriacao)) . '/' . nomeMesAbreviado(date('m', strtotime($comentario->dataCriacao))) . '/' . date('Y', strtotime($comentario->dataCriacao));

                        $hora = date('H:i', strtotime($comentario->dataCriacao));

                        $html .=
                            '
                        <div>
                            <i class="fas fa-comments bg-yellow"></i>
                            <div class="timeline-item">
                                <span class="time">' . $dia . ' ' . $hora . '</span>
                                <h3 class="timeline-header"><a href="#">' . $comentario->nomeColaborador . '</a></h3>
                                <div class="timeline-body">
                                ' . $comentario->comentario . '
                                </div>
                            </div>
                        </div>';
                    }

                    $response['html'] = $html;




                    $response['success'] = true;
                    $response['messages'] = 'Informação inserida com sucesso';
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

        $fields['codAgendamentoReserva'] = $this->request->getPost('codAgendamentoReserva');
        $fields['codOrganizacao'] = $this->request->getPost('codOrganizacao');
        $fields['codPaciente'] = $this->request->getPost('codPaciente');
        $fields['codEspecialidade'] = $this->request->getPost('codEspecialidade');
        $fields['codEspecialista'] = $this->request->getPost('codEspecialista');
        $fields['codStatus'] = $this->request->getPost('codStatus');
        $fields['dataCriacao'] = $this->request->getPost('dataCriacao');
        $fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');
        $fields['preferenciaDia'] = $this->request->getPost('preferenciaDia');
        $fields['preferenciaHora'] = $this->request->getPost('preferenciaHora');
        $fields['codAutor'] = $this->request->getPost('codAutor');
        $fields['protocolo'] = $this->request->getPost('protocolo');


        $this->validation->setRules([
            'codAgendamentoReserva' => ['label' => 'CodOrganizacao', 'rules' => 'required|numeric|max_length[11]'],
            'codOrganizacao' => ['label' => 'CodOrganizacao', 'rules' => 'required|numeric|max_length[11]'],
            'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'required|numeric|max_length[11]'],
            'codEspecialidade' => ['label' => 'CodEspecialidade', 'rules' => 'required|numeric|max_length[11]'],
            'codEspecialista' => ['label' => 'CodEspecialista', 'rules' => 'required|numeric|max_length[11]'],
            'codStatus' => ['label' => 'CodStatus', 'rules' => 'required|numeric|max_length[11]'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
            'preferenciaDia' => ['label' => 'PreferenciaDia', 'rules' => 'required|max_length[11]'],
            'preferenciaHora' => ['label' => 'PreferenciaHora', 'rules' => 'required|max_length[11]'],
            'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
            'protocolo' => ['label' => 'Protocolo', 'rules' => 'permit_empty|max_length[20]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->AgendamentosReservasModel->update($fields['codAgendamentoReserva'], $fields)) {

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
    public function atualizaStatusReserva()
    {

        $response = array();

        $fields['codAgendamentoReserva'] = $this->request->getPost('codAgendamentoReserva');
        $fields['codStatus'] = 2; // 2 concluido
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['codAutor'] = session()->codPessoa;


        $this->validation->setRules([
            'codAgendamentoReserva' => ['label' => 'codAgendamentoReserva', 'rules' => 'required|numeric|max_length[11]'],
            'codStatus' => ['label' => 'CodStatus', 'rules' => 'required|numeric|max_length[11]'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
            'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->AgendamentosReservasModel->update($fields['codAgendamentoReserva'], $fields)) {

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

        $id = $this->request->getPost('codAgendamentoReserva');

        if (!$this->validation->check($id, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($this->AgendamentosReservasModel->where('codAgendamentoReserva', $id)->delete()) {

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
