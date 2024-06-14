<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\PacientesModel;
use App\Models\OrganizacoesModel;
use App\Models\EspecialidadesModel;
use App\Models\NotificacoesFilaModel;

use App\Models\AgendamentosModel;

class meusAgendamentos extends BaseController
{

    protected $AgendamentosModel;
    protected $pessoasModel;
    protected $OrganizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

    public function __construct()
    {

        helper('seguranca_helper');
        verificaSeguranca($this, session(), base_url());
        $this->EspecialidadesModel = new EspecialidadesModel();
        $this->AgendamentosModel = new AgendamentosModel();
        $this->PacientesModel = new PacientesModel();
        $this->NotificacoesFilaModel = new NotificacoesFilaModel();
        $this->PessoasModel = new PessoasModel();
        $this->OrganizacoesModel = new OrganizacoesModel();
        $this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
        $this->validation =  \Config\Services::validation();
        $this->codOrganizacao = session()->codOrganizacao;
    }

    public function index()
    {

        $data = [
            'controller'        => 'agendamentos',
            'title'             => 'Agendamentos'
        ];
        echo view('tema/cabecalho');
        echo view('tema/menu_vertical');
        echo view('tema/menu_horizontal');
        return view('meusAgendamentos', $data);
    }

    public function getAll()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->AgendamentosModel->pegaTudo();

        foreach ($result as $key => $value) {

            $ops = '<div class="btn-group">';
            $ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editagendamentos(' . $value->codAgendamento . ')"><i class="fa fa-edit"></i></button>';
            $ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeagendamentos(' . $value->codAgendamento . ')"><i class="fa fa-trash"></i></button>';
            $ops .= '</div>';

            $data['data'][$key] = array(
                $value->codAgendamento,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }


    public function filtrar()
    {
        if ($this->request->getPost('codEspecialidade')  !== NULL) {
            $codEspecialidade = $this->request->getPost('codEspecialidade');
        } else {
            $codEspecialidade = NULL;
        }
        if ($this->request->getPost('codEspecialista')  !== NULL) {
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

        $filtro["codEspecialidade"] = $codEspecialidade;
        $filtro["codEspecialista"] = $codEspecialista;
        $filtro["dataInicio"] = $dataInicio;
        $filtro["dataEncerramento"] = $dataEncerramento;

        session()->set('filtroEspecialidade', $filtro);

        $response = array();

        $response['success'] = true;
        $response['csrf_hash'] = csrf_hash();
        return $this->response->setJSON($response);
    }


    public function marcados()
    {
        $response = array();



        $data['data'] = array();

        $result = $this->AgendamentosModel->todosMeusAgendamentos();


        foreach ($result as $key => $value) {
            $opsConsulta = '';
            $opsExame = '';
            $opsServico = '';


            $ops = '
            <div class="btn-group">
                    <button type="button" class="btn btn-secondary">Opções</button>
                    <button type="button" class="btn btn-secondary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                      <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu" role="menu">';


            //BOTÕES CONSULTAS
            if ($value->tipoAgenda == 'Consulta') {

                $opsConsulta .=   '<a href="#" class="dropdown-item" onclick="comprovanteA4Consulta(' . $value->codAgendamento . ')">Comprovante de Agendamento</a>';



                $dataInicio = date('Y-m-d', strtotime($value->dataInicio));
                if ($value->dataInicio >= date('Y-m-d H:i', strtotime(date('Y-m-d H:i'), "-1 days"))) {
                    $opsConsulta .= ' <a href="#" class="dropdown-item" onclick="confirmou(' . $value->codAgendamento . ')">Confirmar (Irei)</a>';
                    $opsConsulta .= '<a href="#" class="dropdown-item" onclick="desmarcarConsulta(' . $value->codAgendamento . ')">Desmarcar</a>';
                }

                if (date($dataInicio, strtotime("-1 days")) > date('Y-m-d')) {
                    $opsConsulta .= ' <a href="#" class="dropdown-item" onclick="remarcar(' . $value->codAgendamento . ')">Remarcar</a>';
                }
            }



            //BOTÕES EXAMES
            if ($value->tipoAgenda == 'Exame') {

                $opsExame  .=   '<a href="#" class="dropdown-item" onclick="comprovanteA4Exame(' . $value->codAgendamento . ')">Comprovante de Agendamento</a>';



                $dataInicio = date('Y-m-d', strtotime($value->dataInicio));
                if ($value->dataInicio >= date('Y-m-d H:i', strtotime(date('Y-m-d H:i'), "-1 days"))) {
                    //$opsExame .= ' <a href="#" class="dropdown-item" onclick="confirmou(' . $value->codAgendamento . ')">Confirmar (Irei)</a>';
                    $opsExame .= '<a href="#" class="dropdown-item" onclick="desmarcarExame(' . $value->codAgendamento . ')">Desmarcar</a>';
                }

                if (date($dataInicio, strtotime("-1 days")) > date('Y-m-d')) {
                    //$opsExame .= ' <a href="#" class="dropdown-item" onclick="remarcar(' . $value->codAgendamento . ')">Remarcar</a>';
                }
            }


            //BOTÕES EXAMES
            if ($value->tipoAgenda == 'Serviço') {

                $opsServico  .=   '<a href="#" class="dropdown-item" onclick="comprovanteA4Servico(' . $value->codAgendamento . ')">Comprovante de Agendamento</a>';



                $dataInicio = date('Y-m-d', strtotime($value->dataInicio));
                if ($value->dataInicio >= date('Y-m-d H:i', strtotime(date('Y-m-d H:i'), "-1 days"))) {
                    //$opsServico .= ' <a href="#" class="dropdown-item" onclick="confirmou(' . $value->codAgendamento . ')">Confirmar (Irei)</a>';
                    $opsServico .= '<a href="#" class="dropdown-item" onclick="desmarcarServico(' . $value->codAgendamento . ')">Desmarcar</a>';
                }

                if (date($dataInicio, strtotime("-1 days")) > date('Y-m-d')) {
                    //$opsServico .= ' <a href="#" class="dropdown-item" onclick="remarcar(' . $value->codAgendamento . ')">Remarcar</a>';
                }
            }

            $ops .= $opsConsulta;
            $ops .= $opsExame;
            $ops .= $opsServico;
            $ops .= '</div>
                  </div>

            ';

            $status = "";



            if ($value->codStatus == 1 and $dataInicio >= date('Y-m-d') and $value->chegou == 0) {
                $status = '<span class="right badge badge-info">' . $value->nomeStatus . '</span>';
            }

            if ($value->codStatus == 2) {
                $status = '<span class="right badge badge-success">' . $value->nomeStatus . '</span>';
            }

            if ($value->codStatus == 3) {
                $status = '<span class="right badge badge-danger">' . $value->nomeStatus . '</span>';
            }


            if ($status == "") {

                if ($value->dataInicio > date('Y-m-d H:i')) {
                    $status = '<span class="right badge badge-info">AGUARDANDO</span>';
                } else {
                    $status = '<span class="right badge badge-info">PASSOU</span>';
                }
            }


            if ($value->descricaoDepartamento !== NULL) {
                $localAtendimento = $value->descricaoDepartamento;
            } else {
                $localAtendimento = session()->siglaOrganizacao;
            }

            $dia = '<div> <span class="right badge badge-danger" > ' . $localAtendimento . ' </span>, ' . diaSemanaCompleto($value->dataInicio) . ', ' . date('d/m/Y', strtotime($value->dataInicio)) . " às " . date('H:i', strtotime($value->dataInicio)) . '</div>';
            $especialidade = '<div>' . $value->descricaoEspecialidade  . " - " . $value->nomeEspecialista . "</div><div><span class='right badge badge-info'>" . $value->tipoAgenda . "</span></div>";
            $data['data'][$key] = array(

                '<div style="font-size:24px" class="row">
                    <div class="col-md-3">
                    ' . $especialidade . '
                    </div>
                    
                    <div class="col-md-3">
                        <b>Local/Data/Hora</b>: ' . $dia . '
                    </div>
                    
                    <div class="col-md-3">
                    <b>Status</b>: <br>' . $status . '
                    </div>
                    
                    <div style="margin-top:15px" class="col-md-3">
                    ' . $ops . '
                    </div>
                </div>
                ',

            );
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

        $codEspecialidade = $this->request->getPost('codEspecialidade');
        $result = $this->EspecialidadesModel->listaDropDownEspecialistasDisponivelMarcacao($codEspecialidade);

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }
    public function marcarPaciente()
    {
        $response = array();
        $codAgendamento = $this->request->getPost('codAgendamento');
        $codPacienteMarcacao = $this->request->getPost('codPacienteMarcacao');

        $agendamento = $this->AgendamentosModel->pegaPorCodigo($codAgendamento);

        if ($agendamento->codPaciente !== '0') {
            $response['success'] = false;
            $response['messages'] = 'Alguem já marcou nessa data e hora antes de você. Tente outra marcação!';
            return $this->response->setJSON($response);
        }
        $dadosPaciente = $this->PacientesModel->pegaPacientePorCodPaciente($codPacienteMarcacao);


        $response = array();

        $protocolo = date('Y') . $codPacienteMarcacao . geraNumero(2);
        $fields['codAgendamento'] = $codAgendamento;
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
            'codAgendamento' => ['label' => 'codAgendamento', 'rules' => 'required|numeric|max_length[11]'],
            'codOrganizacao' => ['label' => 'CodOrganizacao', 'rules' => 'required|numeric|max_length[11]'],
            'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'required|numeric|max_length[11]'],
            'codStatus' => ['label' => 'CodStatus', 'rules' => 'required|numeric|max_length[11]'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
            'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
            'protocolo' => ['label' => 'Protocolo', 'rules' => 'required|numeric|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->AgendamentosModel->update($codAgendamento, $fields)) {

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
                                <div> Caro senhor(a), " . $dadosPaciente->nomeExibicao . ",</div>";
                    $conteudo .= "<div>sua consulta foi agendada para " . diaSemanaCompleto($agendamento->dataInicio) . " dia " .  date("d/m/Y H:i", strtotime($agendamento->dataInicio)) . ". Protocolo Nr " .  $fields['protocolo'] . ".</div>";

                    $conteudo .= "<span style='margin-top:15px;'>DADOS DA CONSULTA:";
                    $conteudo .= "<div style='font-size:12px; font-weight: bold;margin-top:10px'>ESPECIALIDADE: <span>" . $agendamento->descricaoEspecialidade . "</span></div>";
                    $conteudo .= "<div style='font-size:20px; font-weight: bold;margin-top:0px'>PROFISSIONAL: <span>" . $agendamento->nomeExibicao . "</span></div>";
                    $conteudo .= "<div style='font-size:20px; font-weight: bold;margin-top:0px'>DATA/HORA: <span>" . date("d/m/Y H:i", strtotime($agendamento->dataInicio)) . "</span></div>";
                    $conteudo .= "<div style='font-size:20px; font-weight: bold;margin-top:0px'>PROTOCOLO: <span>" . $fields['protocolo'] . "</span></div>";
                    $conteudo .= "<div style='font-size: 12px;margin-top:16px'>Atenciosamente,</div>";
                    if ($funcionario == 1) {
                        $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeFuncionarioResponsavel . "</div>";
                        $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeDepartamentoResponsavel  . "</div>";
                    }
                    $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . session()->descricaoOrganizacao . "</div>";

                    $resultadoEmail = @email($email, 'MARCAÇÃO DE CONSULTA #' . $fields['protocolo'], $conteudo);
                    if ($resultadoEmail == false) {

                        //ADICIONAR NOTIFICAÇÃO ANA FILA EM CASO DE FALHA
                        @addNotificacoesFila($conteudo, $email, $email, 1);
                    }


                    //ENVIAR SMS
                    $celular = removeCaracteresIndesejados($dadosPaciente->celular);
                    $conteudoSMS = "
                                    Caro senhor(a), " . $dadosPaciente->nomeExibicao . ",";
                    $conteudoSMS .= " Sua consulta para " . $agendamento->nomeExibicao . ", " . $agendamento->descricaoEspecialidade . ",  foi marcada para " . diaSemanaCompleto($agendamento->dataInicio) . " dia " . date("d/m/Y H:i", strtotime($agendamento->dataInicio)) . ". Nr protocolo:" . $fields['protocolo'] . ".";

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
                $response['messages'] = 'Marcação realziada com sucesso';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na Marcação!';
            }
        }

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

            $pacientes = $this->PacientesModel->pegaNomeOuCpf($nomeCpf);
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
                                <label for="pacienteMarcar' . $codPaciente . '">' . $nomeCompleto . ' (' . $cpf . ')
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

    public function emissaoCartao()
    {
        $response = array();


        $id = $this->request->getPost('codPaciente');
        $organizacao =  $this->OrganizacoesModel->pegaOrganizacao(session()->codOrganizacao);


        if ($this->validation->check($id, 'required|numeric')) {
            $data = array();
            $paciente = $this->PacientesModel->emissaoCartao($id);
            $data['nomeCompleto'] = $paciente->nomeCompleto;
            $data['codPaciente'] = $paciente->codPaciente;
            $data['valorChecksum'] = MD5($paciente->codPaciente . $organizacao->chaveSalgada);
            $data['fotoPerfil'] = $paciente->fotoPerfil;
            $data['cpf'] = $paciente->cpf;
            $data['nomeTipoBeneficiario'] = $paciente->nomeTipoBeneficiario;
            $data['descricaoCargo'] = $paciente->descricaoCargo;
            $data['codProntuario'] = $paciente->codProntuario;
            $data['codProntuario'] = $paciente->codProntuario;
            $data['validadeProntuario'] = $paciente->validadeProntuario;
            $data['responsavel'] = getNomeExibicaoPessoa($this, session()->codPessoa);
            $data['dataEmissao'] = date('d/m/Y H:i');

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function remarcacaoPorEspecialidade()
    {
        $response = array();


        $codAgendamento = $this->request->getPost('codAgendamento');


        $meuAgendamentoAtual = $this->AgendamentosModel->pegaPorCodigo($codAgendamento);

        $agendamentos = $this->AgendamentosModel->remarcacaoPorEspecialidade($meuAgendamentoAtual->codEspecialidade);

        $especialistas = array();
        foreach ($agendamentos as $especialista) {
            if (!in_array($especialista->codEspecialista, $especialistas)) {
                array_push($especialistas, $especialista->codEspecialista);
            }
        }

        $slotsLivres = '';
        $slotsLivres .= '<div class="row">';
        foreach ($especialistas as $especialista) {


            $dadosEspecialista = $this->AgendamentosModel->pegaPessoa($especialista);


            $slotsLivres .= '<div class="col-md-12">
                <div class="form-group">

                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                  <img alt="" style="width:80px" id="fotoPerfilBarraSuperior" src="' . base_url() . '/arquivos/imagens/pessoas/' . $dadosEspecialista->fotoPerfil . '" class="img-circle elevation-2">
                </div>
                <div class="info">
                  <a style="font-size:20px" href="#" class="d-block">' . $dadosEspecialista->nomeExibicao . '</a>

                </div>
              </div>


        ';

            $slotsLivres .= '<div class="row">';
            foreach ($agendamentos as  $key => $agendamento) {
                if ($especialista == $agendamento->codEspecialista) {

                    if ($agendamento->codPaciente == 0) {
                        $hora = date('H:i', strtotime($agendamento->dataInicio));
                        $diaMes = date('d/m/Y', strtotime($agendamento->dataInicio));

                        $slotsLivres .= '

                    <div class="col-md-2">
                        <div class="form-group">
                            <button type="button" onclick="escolhaPaciente(' . $agendamento->codAgendamento . ')" class="btn btn-block btn-outline-primary btn-lg">' . $diaMes . ' - ' . $hora . '</button>
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

        $response['success'] = true;
        $response['csrf_hash'] = csrf_hash();
        $response['slotsLivres'] = $slotsLivres;
        return $this->response->setJSON($response);
    }

    public function agendamentosPorEspecialidade()
    {
        $response = array();



        $agendamentos = $this->AgendamentosModel->agendamentosPorEspecialidade();
        $especialistas = array();
        foreach ($agendamentos as $especialista) {
            if (!in_array($especialista->codEspecialista, $especialistas)) {
                array_push($especialistas, $especialista->codEspecialista);
            }
        }

        $slotsLivres = '';
        $slotsLivres .= '<div class="row">';
        foreach ($especialistas as $especialista) {


            $dadosEspecialista = $this->AgendamentosModel->pegaPessoa($especialista);


            $slotsLivres .= '<div class="col-md-12">
                <div class="form-group">

                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                  <img alt="" style="width:80px" id="fotoPerfilBarraSuperior" src="' . base_url() . '/arquivos/imagens/pessoas/' . $dadosEspecialista->fotoPerfil . '" class="img-circle elevation-2">
                </div>
                <div class="info">
                  <a style="font-size:20px" href="#" class="d-block">' . $dadosEspecialista->nomeExibicao . '</a>

                </div>
              </div>


        ';

            $slotsLivres .= '<div class="row">';
            foreach ($agendamentos as  $key => $agendamento) {
                if ($especialista == $agendamento->codEspecialista) {

                    if ($agendamento->codPaciente == 0) {
                        $hora = date('H:i', strtotime($agendamento->dataInicio));
                        $diaMes = date('d/m/Y', strtotime($agendamento->dataInicio));

                        $slotsLivres .= '

                    <div class="col-md-2">
                        <div class="form-group">
                            <button type="button" onclick="escolhaPaciente(' . $agendamento->codAgendamento . ')" class="btn btn-block btn-outline-primary btn-lg">' . $diaMes . ' - ' . $hora . '</button>
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

        $response['success'] = true;
        $response['csrf_hash'] = csrf_hash();
        $response['slotsLivres'] = $slotsLivres;
        return $this->response->setJSON($response);
    }

    public function getOne()
    {
        $response = array();

        $id = $this->request->getPost('codAgendamento');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->AgendamentosModel->pegaPorCodigo($id);

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function add()
    {

        $response = array();

        $fields['codAgendamento'] = $this->request->getPost('codAgendamento');
        $fields['codConfig'] = $this->request->getPost('codConfig');
        $fields['codOrganizacao'] = $this->request->getPost('codOrganizacao');
        $fields['codPaciente'] = $this->request->getPost('codPaciente');
        $fields['codLocal'] = $this->request->getPost('codLocal');
        $fields['codEspecialista'] = $this->request->getPost('codEspecialista');
        $fields['codEspecialidade'] = $this->request->getPost('codEspecialidade');
        $fields['codStatus'] = $this->request->getPost('codStatus');
        $fields['dataCriacao'] = $this->request->getPost('dataCriacao');
        $fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');
        $fields['dataInicio'] = $this->request->getPost('dataInicio');
        $fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');
        $fields['codTipoAgendamento'] = $this->request->getPost('codTipoAgendamento');
        $fields['codAutor'] = $this->request->getPost('codAutor');
        $fields['protocolo'] = $this->request->getPost('protocolo');
        $fields['ordemAtendimento'] = $this->request->getPost('ordemAtendimento');


        $this->validation->setRules([
            'codConfig' => ['label' => 'CodConfig', 'rules' => 'required|numeric|max_length[11]'],
            'codOrganizacao' => ['label' => 'CodOrganizacao', 'rules' => 'required|numeric|max_length[11]'],
            'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'required|numeric|max_length[11]'],
            'codLocal' => ['label' => 'CodLocal', 'rules' => 'required|numeric|max_length[11]'],
            'codEspecialista' => ['label' => 'CodEspecialista', 'rules' => 'required|numeric|max_length[11]'],
            'codEspecialidade' => ['label' => 'CodEspecialidade', 'rules' => 'required|numeric|max_length[11]'],
            'codStatus' => ['label' => 'CodStatus', 'rules' => 'required|numeric|max_length[11]'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
            'dataInicio' => ['label' => 'DataInicio', 'rules' => 'permit_empty'],
            'dataEncerramento' => ['label' => 'DataEncerramento', 'rules' => 'permit_empty'],
            'codTipoAgendamento' => ['label' => 'codTipoAgendamento', 'rules' => 'required|numeric|max_length[11]'],
            'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
            'protocolo' => ['label' => 'Protocolo', 'rules' => 'required|numeric|max_length[11]'],
            'ordemAtendimento' => ['label' => 'OrdemAtendimento', 'rules' => 'required|numeric|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->AgendamentosModel->insert($fields)) {

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
        $startTime = date("Y-m-d H:i:s");
        $dataAtualizacao = date('Y-m-d H:i:s', strtotime('+1 minutes', strtotime($startTime)));


        $fields['codAgendamento'] = $this->request->getPost('codAgendamento');
        $fields['dataAtualizacao'] = $dataAtualizacao;

        if (!$this->validation->check($fields['codAgendamento'], 'required|numeric')) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {
            $this->AgendamentosModel->update($fields['codAgendamento'], $fields);
        }
    }

    public function edit()
    {

        $response = array();

        $fields['codAgendamento'] = $this->request->getPost('codAgendamento');
        $fields['codConfig'] = $this->request->getPost('codConfig');
        $fields['codOrganizacao'] = $this->request->getPost('codOrganizacao');
        $fields['codPaciente'] = $this->request->getPost('codPaciente');
        $fields['codLocal'] = $this->request->getPost('codLocal');
        $fields['codEspecialista'] = $this->request->getPost('codEspecialista');
        $fields['codEspecialidade'] = $this->request->getPost('codEspecialidade');
        $fields['codStatus'] = $this->request->getPost('codStatus');
        $fields['dataCriacao'] = $this->request->getPost('dataCriacao');
        $fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');
        $fields['dataInicio'] = $this->request->getPost('dataInicio');
        $fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');
        $fields['codTipoAgendamento'] = $this->request->getPost('codTipoAgendamento');
        $fields['codAutor'] = $this->request->getPost('codAutor');
        $fields['protocolo'] = $this->request->getPost('protocolo');
        $fields['ordemAtendimento'] = $this->request->getPost('ordemAtendimento');


        $this->validation->setRules([
            'codAgendamento' => ['label' => 'codAgendamento', 'rules' => 'required|numeric|max_length[11]'],
            'codConfig' => ['label' => 'CodConfig', 'rules' => 'required|numeric|max_length[11]'],
            'codOrganizacao' => ['label' => 'CodOrganizacao', 'rules' => 'required|numeric|max_length[11]'],
            'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'required|numeric|max_length[11]'],
            'codLocal' => ['label' => 'CodLocal', 'rules' => 'required|numeric|max_length[11]'],
            'codEspecialista' => ['label' => 'CodEspecialista', 'rules' => 'required|numeric|max_length[11]'],
            'codEspecialidade' => ['label' => 'CodEspecialidade', 'rules' => 'required|numeric|max_length[11]'],
            'codStatus' => ['label' => 'CodStatus', 'rules' => 'required|numeric|max_length[11]'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
            'dataInicio' => ['label' => 'DataInicio', 'rules' => 'permit_empty'],
            'dataEncerramento' => ['label' => 'DataEncerramento', 'rules' => 'permit_empty'],
            'codTipoAgendamento' => ['label' => 'codTipoAgendamento', 'rules' => 'required|numeric|max_length[11]'],
            'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
            'protocolo' => ['label' => 'Protocolo', 'rules' => 'required|numeric|max_length[11]'],
            'ordemAtendimento' => ['label' => 'OrdemAtendimento', 'rules' => 'required|numeric|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->AgendamentosModel->update($fields['codAgendamento'], $fields)) {

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

    public function desmarcarConsulta()
    {

        $response = array();

        $codAgendamento = $this->request->getPost('codAgendamento');

        $dadosAgendamento = $this->AgendamentosModel->pegaPorCodigo($codAgendamento);

        $especialidade = $dadosAgendamento->descricaoEspecialidade;
        $especialista = $dadosAgendamento->nomeExibicao;
        $protocolo = $dadosAgendamento->protocolo;
        $dataInicio = date('d/m/Y H:i', strtotime($dadosAgendamento->dataInicio));

        $dadosPaciente = $this->PacientesModel->pegaPacientePorCodPaciente($dadosAgendamento->codPaciente);

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





        if (!$this->validation->check($codAgendamento, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($this->AgendamentosModel->update($codAgendamento, $fields)) {


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
                    $conteudo .= "<div style='font-size:12px; font-weight: bold;margin-top:10px'>ESPECIALIDADE: <span>" . $especialidade . "</span></div>";
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
                    $conteudoSMS .= " Sua consulta para " . $especialista . ", " . $especialidade . ",  foi desmarcada";

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


    public function chegou()
    {
        $response = array();

        $codAgendamento = $this->request->getPost('codAgendamento');

        $dadosAgendamento = $this->AgendamentosModel->pegaPorCodigo($codAgendamento);

        $especialidade = $dadosAgendamento->descricaoEspecialidade;
        $especialista = $dadosAgendamento->nomeExibicao;
        $protocolo = $dadosAgendamento->protocolo;
        $dataInicio = date('d/m/Y H:i', strtotime($dadosAgendamento->dataInicio));

        $dadosPaciente = $this->PacientesModel->pegaPacientePorCodPaciente($dadosAgendamento->codPaciente);

        $nomePaciente = $dadosPaciente->nomeExibicao;

        $fields = array();

        $fields['chegou'] = 1;
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

            if ($this->AgendamentosModel->update($codAgendamento, $fields)) {


                //ENVIAR NOTIFICAÇÃO
                sleep(3);
                if ($dadosPaciente->emailPessoal !== NULL and $dadosPaciente->emailPessoal !== "" and $dadosPaciente->emailPessoal !== " ") {
                    $email = $dadosPaciente->emailPessoal;
                } else {
                    $email = NULL;
                }

                if ($email !== NULL and $dadosPaciente->nomeExibicao !== NULL) {
                    $conteudo = "
                                <div> Caro senhor(a), " . $nomePaciente . ",</div>";
                    $conteudo .= "<div>Obrigado por comparecer à consulta do(a) " . $especialista . " ( " . $especialidade . ").</div>";
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
                    $conteudoSMS .= "Obrigado por comparecer à consulta do(a) " . $especialista . " ( " . $especialidade . ").";

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

    public function confirmou()
    {
        $response = array();

        $codAgendamento = $this->request->getPost('codAgendamento');

        $dadosAgendamento = $this->AgendamentosModel->pegaPorCodigo($codAgendamento);

        $especialidade = $dadosAgendamento->descricaoEspecialidade;
        $especialista = $dadosAgendamento->nomeExibicao;
        $protocolo = $dadosAgendamento->protocolo;
        $dataInicio = date('d/m/Y H:i', strtotime($dadosAgendamento->dataInicio));

        $dadosPaciente = $this->PacientesModel->pegaPacientePorCodPaciente($dadosAgendamento->codPaciente);

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





        if (!$this->validation->check($codAgendamento, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($this->AgendamentosModel->update($codAgendamento, $fields)) {


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
                    $conteudo .= "<div>Obrigado por confirmar sua presença na consulta do(a) " . $especialista . " ( " . $especialidade . ").</div>";
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
                    $conteudoSMS .= "Obrigado por confirmar sua presença na consulta do(a) " . $especialista . " ( " . $especialidade . ").";

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
