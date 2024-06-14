<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\PacientesModel;
use App\Models\OrganizacoesModel;

use App\Models\AgendamentosReservasModel;

class MinhasReservas extends BaseController
{

    protected $AgendamentosReservasModel;
    protected $pessoasModel;
    protected $OrganizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

    public function __construct()
    {

        helper('seguranca_helper');
        verificaSeguranca($this, session(), base_url());
        $this->AgendamentosReservasModel = new AgendamentosReservasModel();
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


        $data = [
            'controller'        => 'agendamentosReservas',
            'title'             => 'Reservas'
        ];
        echo view('tema/cabecalho');
        echo view('tema/menu_vertical');
        echo view('tema/menu_horizontal');
        return view('minhasReservas', $data);
    }

    public function getAll()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->AgendamentosReservasModel->minhasReservas();

        foreach ($result as $key => $value) {

            $ops = '
            <div class="btn-group">
                    <button type="button" class="btn btn-info">Ação</button>
                    <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                      <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu" role="menu">
                        <a href="#" class="dropdown-item" onclick="removeagendamentosReservas(' . $value->codAgendamentoReserva . ')">Desitir da Reserva</a>
                    </div>
            </div>';

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
                $value->descricaoEspecialidade,
                $status,
                $especialista,
                date('d/m/Y H:i', strtotime($value->dataCriacao)),
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

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
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
            $response['success'] = false;
            $response['messages'] = 'Você deve informar ao menos 1 dia da semana';

            return $this->response->setJSON($response);
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
            'codAgendamentoReserva' => ['label' => 'codAgendamentoReserva', 'rules' => 'required|numeric|max_length[11]'],
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
                $response['messages'] = 'Você saiu do cadastro reserva!';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na deleção!';
            }
        }

        return $this->response->setJSON($response);
    }
}
