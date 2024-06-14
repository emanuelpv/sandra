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
use App\Models\PainelChamadasModel;
use App\Models\AgendamentosFaltasModel;
use App\Models\AtendimentoslocaisModel;
use App\Models\IndicacoesClinicasModel;

use App\Models\AgendamentosModel;
use App\Models\AgendamentosReservasModel;

class Agendamentos extends BaseController
{

    protected $AgendamentosModel;
    protected $pessoasModel;
    protected $OrganizacoesModel;
    protected $EspecialidadesModel;
    protected $AgendamentosReservasModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

    public function __construct()
    {

        helper('seguranca_helper');
        verificaSeguranca($this, session(), base_url());
        $this->AtendimentoslocaisModel = new AtendimentoslocaisModel();
        $this->EspecialidadesModel = new EspecialidadesModel();
        $this->AgendamentosFaltasModel = new AgendamentosFaltasModel();
        $this->IndicacoesClinicasModel = new IndicacoesClinicasModel();
        $this->AgendamentosModel = new AgendamentosModel();
        $this->PainelChamadasModel = new PainelChamadasModel();
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
        $permissao = verificaPermissao('Agendamentos', 'listar');
        if ($permissao == 0) {
            echo mensagemAcessoNegado(session()->organizacoes);
            $this->LogsModel->inserirLog('Acesso indevido ao Módulo "Agendamentos"', session()->codPessoa);
            exit();
        }

        $especialistas = $this->EspecialidadesModel->especialistas();


        $data = [
            'controller'        => 'agendamentos',
            'title'             => 'Agendamentos',
            'especialistas' => $especialistas,
        ];

        echo view('tema/cabecalho');
        echo view('tema/menu_vertical');
        echo view('tema/menu_horizontal');
        return view('agendamentos', $data);
    }



    public function tentativas()
    {

        echo view('tema/cabecalho');
        echo view('tema/menu_vertical');
        echo view('tema/menu_horizontal');
        return view('agendamentosTentativas');
    }


    public function tentativasPorPaciente()
    {
        $response = array();

        $data['data'] = array();

        $prec = $this->request->getPost('prec');

        $result = $this->AgendamentosModel->tentativasPorPaciente($prec);

        foreach ($result as $key => $value) {

            $ops = '';

            $data['data'][$key] = array(
                $value->nomeExibicao,
                $value->cpf,
                $value->descricaoEspecialidade,
                $value->dias,
            );
        }

        return $this->response->setJSON($data);
    }


    public function consulta()
    {


        $data = array();
        $data = $this->EspecialidadesModel->especialistas();

        $data = [
            'controller'        => 'agendamentos',
            'title'             => 'Agendamentos',
            'data' => $data,
        ];
        echo view('tema/cabecalho');
        echo view('tema/menu_vertical');
        echo view('tema/menu_horizontal');
        return view('agendamentosConsulta', $data);
    }


    public function agendarVisita()
    {


        $data = array();
        $data = $this->EspecialidadesModel->especialistas();

        $data = [
            'controller'        => 'agendamentos',
            'title'             => 'Agendamentos',
            'data' => $data,
        ];
        echo view('tema/cabecalho');
        echo view('tema/menu_vertical');
        echo view('tema/menu_horizontal');
        return view('agendamentosConsulta', $data);
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


    public function proximasConsultas()
    {
        $response = array();

        $data['data'] = array();

        $codPaciente = $this->request->getPost('codPaciente');
        $consultas = $this->AgendamentosModel->proximasConsultas($codPaciente);


        foreach ($consultas as $key => $consulta) {

            $ops = '
            <div class="btn-group">
                    <button type="button" class="btn btn-info">Ação</button>
                    <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                      <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu" role="menu">
                        <a href="#" class="dropdown-item" onclick="desmarcar(' . $consulta->codAgendamento . ')">Desmarcar</a>
                         <a href="#" class="dropdown-item" onclick="comprovante(' . $consulta->codAgendamento . ')">Comprovante de Agendamento</a>
                    </div>
            </div>
            ';

            $data['data'][$key] = array(
                $consulta->Tipo,
                $consulta->descricaoEspecialidade,
                $consulta->nomeExibicao,
                date('d/m/Y H:i', strtotime($consulta->dataInicio)),
                $consulta->nomeStatus,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }


    public function filtrarVagas()
    {
        $response = array();

        if ($this->request->getPost('codEspecialidade')  !== NULL and $this->request->getPost('codEspecialidade')  !== '') {
            $codEspecialidade = $this->request->getPost('codEspecialidade');
        } else {
            $codEspecialidade = NULL;
        }
        if ($this->request->getPost('codEspecialista')  !== NULL and $this->request->getPost('codEspecialista')  !== 0 and $this->request->getPost('codEspecialista')  !== '') {
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

        $this->validation->setRules([
            'codEspecialidade' => ['label' => 'Especialidade', 'rules' => 'permit_empty|bloquearReservado|numeric|max_length[11]'],
            'codEspecialista' => ['label' => 'Especialista', 'rules' => 'permit_empty|bloquearReservado|numeric|max_length[11]'],
            'dataInicio' => ['label' => 'dataInicio', 'rules' => 'permit_empty|bloquearReservado|valid_date'],
            'dataEncerramento' => ['label' => 'dataEncerramento', 'rules' => 'permit_empty|bloquearReservado|valid_date'],

        ]);

        if ($this->validation->run($filtro) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {


            session()->set('filtroEspecialidade', $filtro);
            $response['success'] = true;
            $response['csrf_hash'] = csrf_hash();
        }



        return $this->response->setJSON($response);
    }


    public function marcados()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->AgendamentosModel->marcados();

        foreach ($result as $key => $value) {



            $ops = '
            <div class="btn-group">
                    <button type="button" class="btn btn-info">Ação</button>
                    <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                      <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu" role="menu">
                        <a href="#" class="dropdown-item" onclick="irParaProntuario(' . $value->codAgendamento . ')">Iniciar Consulta</a>
                        <a href="#" class="dropdown-item" onclick="encerrarConsulta(' . $value->codAgendamento . ')">Encerrar Consulta</a>
                        <a href="#" class="dropdown-item" onclick="desmarcar(' . $value->codAgendamento . ')">Desmarcar</a>
                         <a href="#" class="dropdown-item" onclick="chegou(' . $value->codAgendamento . ')">Chegou</a>
                         <a href="#" class="dropdown-item" onclick="chamarPainel(' . $value->codAgendamento . ')">Chamar no painel</a>
                         <a href="#" class="dropdown-item" onclick="comprovante(' . $value->codAgendamento . ')">Comprovante de Agendamento</a>
                         <a href="#" class="dropdown-item" onclick="faltou(' . $value->codAgendamento . ')">Faltou</a>
                         <a href="#" class="dropdown-item" onclick="cancelarConsulta(' . $value->codAgendamento . ')">Cancelar Consulta</a>
                          </div>
            </div>

            ';

            if ($value->horaChegada !== NULL) {
                $horaChegada = date('H:i', strtotime($value->horaChegada));
                //$tempoAtendimento = intervaloTempoHoraMinutos($horaChegada, date('Y-m-d H:i'));

                if ($value->encerramentoAtendimento !== NULL and $value->inicioAtendimento !== NULL) {
                    $tempoAtendimento = intervaloTempoHoraMinutos($value->inicioAtendimento, $value->encerramentoAtendimento);
                } else {
                    $tempoAtendimento = intervaloTempoHoraMinutos($value->horaChegada, $value->encerramentoAtendimento);
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
            $especialidade = $value->descricaoEspecialidade . " (" . $value->nomeEspecialista . ")";
            $data['data'][$key] = array(
                $fotoPerfil . " " . $value->nomePaciente . " (" . $value->idade . ")" . '<a onclick="chamarPainel(' . $value->codAgendamento . ')" href="#"><i style="color:#007bff" class="fas fa-bullhorn"></i></a>',
                $especialidade,
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




    public function meusAgendamentosPassados()
    {
        $response = array();



        $data['data'] = array();
        $codPaciente = $this->request->getPost('codPaciente');


        $result = $this->AgendamentosModel->meusAgendamentosPassados($codPaciente);


        foreach ($result as $key => $value) {



            $ops = '
            <div class="btn-group">
                    <button type="button" class="btn btn-info">Ação</button>
                    <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                      <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu" role="menu">
                      <a href="#" class="dropdown-item" onclick="comprovanteA4(' . $value->codAgendamento . ')">Comprovante de Agendamento</a>';

            $dataInicio = date('Y-m-d', strtotime($value->dataInicio));
            if ($value->dataInicio >= date('Y-m-d H:i', strtotime(date('Y-m-d H:i'), "-1 days"))) {
                $ops .= ' <a href="#" class="dropdown-item" onclick="confirmou(' . $value->codAgendamento . ')">Confirmar (Irei)</a>';
                $ops .= '<a href="#" class="dropdown-item" onclick="desmarcar(' . $value->codAgendamento . ')">Desmarcar</a>';
            }

            if (date($dataInicio, strtotime("-1 days")) > date('Y-m-d')) {
                $ops .= ' <a href="#" class="dropdown-item" onclick="remarcar(' . $value->codAgendamento . ')">Remarcar</a>';
            }

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

            $dia = " Dia " . date('d/m', strtotime($value->dataInicio)) . " às " . date('H:i', strtotime($value->dataInicio));
            $especialidade = $value->nomeEspecialista . " (" . $value->descricaoEspecialidade . ")";
            $data['data'][$key] = array(
                $especialidade,
                $dia,
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

        $result = $this->AgendamentosModel->marcados();
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
                      <a class="dropdown-item" onclick="desmarcar(' . $value->codAgendamento . ')">Desmarcar</a>
                      <a class="dropdown-item" onclick="confirmou(' . $value->codAgendamento . ')">Confirmar</a>
                      <a class="dropdown-item" onclick="chegou(' . $value->codAgendamento . ')">Chegou</a>
                      <a class="dropdown-item" onclick="chamarPainel(' . $value->codAgendamento . ')">Chamar no painel</a>
                      <a class="dropdown-item" onclick="imprimirComprovanteAgendamento(' . $value->codAgendamento . ')">Comprovante de Agendamento</a>
                      <a href="#" class="dropdown-item" onclick="faltou(' . $value->codAgendamento . ')">Faltou</a>
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

            $outrosContatosPaciente = $this->AgendamentosModel->outrosContatosPorPaciente($value->codPaciente);

            foreach ($outrosContatosPaciente as $outrocontato) {
                $todosContatos .= $outrocontato->numeroContato . " | ";
            }

            $todosContatos = rtrim($todosContatos, "| ");


            if ($value->marcadoPor == $value->codPaciente) {
                $autorMarcacao = 'Paciente';
            } else {
                $autorMarcacao = $value->autorMarcacao;
            }

            $periodo = date('d/m', strtotime($value->dataInicio)) . " das " . date('H:i', strtotime($value->dataInicio)) . " às " . date('H:i', strtotime($value->dataEncerramento));
            $fotoPerfil = '<img  alt="" style="width:30px" src="' . base_url() . '/arquivos/imagens/pacientes/' . $value->fotoPerfil . '" class="img-circle elevation-2">';
            $especialidade = $value->descricaoEspecialidade . " (" . $value->nomeEspecialista . ")";
            $data['data'][$key] = array(
                $x,
                $fotoPerfil . " " . $value->nomePaciente,
                $especialidade,
                $periodo,
                $todosContatos,
                $value->codPlano,
                $value->codProntuario,
                $autorMarcacao,
                $value->nomeStatus,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }




    public function dashboard()
    {
        $response = array();
        $agendamentos = $this->AgendamentosModel->dashboard();

        $response['dados'] = json_encode($agendamentos);
        return $this->response->setJSON($response);
    }




    public function comprovante()
    {
        $response = array();



        $data['data'] = array();


        $codAgendamento = $this->request->getPost('codAgendamento');
        $agendamento = $this->AgendamentosModel->comprovante($codAgendamento);
        $organizacao =  $this->OrganizacoesModel->pegaOrganizacao(session()->codOrganizacao);
        if ($agendamento->marcadoPor == $agendamento->codPaciente) {
            $autorMarcacao = 'Paciente';
        } else {
            $autorMarcacao = $agendamento->autorMarcacao;
        }
        $data['nomePaciente'] = $agendamento->nomePaciente;
        $data['codPlano'] = $agendamento->codPlano;
        $data['nomeEspecialista'] = $agendamento->nomeEspecialista . " (" . $agendamento->descricaoEspecialidade . ")";
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
            $data['local'] = $agendamento->descricaoDepartamento;
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
        $codAgendamento = $this->request->getPost('codAgendamento');
        $dados = $this->AgendamentosModel->comprovante($codAgendamento);

        if (session()->nomeLocalAtendimento !== NULL) {
            $data['localAtendimento'] = " (" . session()->nomeLocalAtendimento . ")";
        } else {
            $data['localAtendimento'] = "";
        }
        $data['codOrganizacao'] = session()->codOrganizacao;
        $data['codChamador'] = session()->codPessoa;
        $data['qtdChamadas'] = 2;
        $data['codPaciente'] = $dados->codPaciente;
        $data['codEspecialidade'] = $dados->codEspecialidade;

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
        $codAgendamento = $this->request->getPost('codAgendamento');
        $codPacienteMarcacao = $this->request->getPost('codPacienteMarcacao');

        $agendamento = $this->AgendamentosModel->pegaPorCodigo($codAgendamento);





        // REGRAS DE BLOQUEIO



        if ((int)$agendamento->codPaciente > 0) {
            $response['success'] = false;
            $response['messages'] = 'Alguem já marcou nessa data e hora antes de você. Tente outra marcação!';
            return $this->response->setJSON($response);
        }


        //SLOT É DA INTERNET, SÓ PACIENTE PODE MARCAR

        if ($agendamento->codTipoAgendamento == 1 and session()->codPessoa !== NULL and date('Y-m-d', strtotime(date('Y-m-d') . ' +2 day')) <= date('Y-m-d', strtotime($agendamento->dataInicio))) {

            $response['success'] = false;
            $response['messages'] = 'Vaga reservadas para marcação apenas pela internet!';
            $response['html'] =  '<span style="color:red" >Esta vaga poderá ser utilizada pelo setor de marcações 48h antes do dia da consulta, caso não tenha sido marcada pela Internet!</span>';
            return $this->response->setJSON($response);
        }



        //SLOT É DE RETORNO, SÓ MÉDICOS PODEM MARCAR

        if ($agendamento->codTipoAgendamento == 2 and empty(session()->minhasEspecialidades)) {

            $response['success'] = false;
            $response['messages'] = 'Vaga reservadas para marcação de retorno do pacientes somente o médico pode remarcar';
            return $this->response->setJSON($response);
        }





        //JÁ MARCOU ESPECIALIDADE XXX NOS PRÓXIMOS 30 DIAS
        //RETORNO DE FORA DESSA REGRA


        $excluidos = array(29, 13);
        //29= CLINICA MÉDICA
        //13 ONCOLOGIA


        if (!in_array($agendamento->codEspecialidade, $excluidos)) {

            $verificaExiteMarcacao = $this->AgendamentosModel->exiteAgendamentos30Dias($codPacienteMarcacao, $agendamento->codEspecialidade);



            if (!empty($verificaExiteMarcacao) and session()->codPessoa == NULL) {

                $response['success'] = false;
                $response['messages'] = 'Para esta especialidade, só é possível marcar 1 consulta pela internet período de 30 dias. Verifique a possibilidade de marcação de retorno junto ao seu médico/clínica ou diretamento no setor de marcações';
                return $this->response->setJSON($response);
            }

            //VERIFICA SE MAIS DE 3 CONSULTAS NOS 30 DIAS
            /*
            $verificaExite3Consultas = $this->AgendamentosModel->exiteAgendamentos3Consultas30Dias($codPacienteMarcacao);


            if ($verificaExite3Consultas >= 3) {
                $response['success'] = false;
                $response['messages'] = 'Só é possível marcar 3 especialidades pelo período de 30 dias.';
                return $this->response->setJSON($response);
            }
            */

            $verificaPacienteFaltoso = $this->AgendamentosModel->verificaPacienteFaltoso($codPacienteMarcacao, $agendamento->codEspecialidade);



            if (!empty($verificaPacienteFaltoso)) {
                $response['success'] = false;
                $response['messages'] = 'Foi identificado que o Paciente faltou a uma consulta nesta especialidade. O Paciente está impedido de fazer novas marcações até ' . date('d/m/Y', strtotime($verificaPacienteFaltoso[0]->dataEncerramentoImpedimento)) . ".";
                return $this->response->setJSON($response);
            }
        } else {

            //SE CLINICA MÉDICA NÃO PODE MARCAR COM MENOS DE 8 DIAS
            $verificaExiteMarcacao = $this->AgendamentosModel->exiteAgendamentos8Dias($codPacienteMarcacao, $agendamento->codEspecialidade);

            if (!empty($verificaExiteMarcacao) and session()->codPessoa == NULL) {
                if (!empty($verificaExiteMarcacao)) {

                    $response['success'] = false;
                    $response['messages'] = 'Para especialidade, só é possível marcar 1 consulta pela internet no período de 8 dias. Verifique a possibilidade de marcação de retorno junto ao seu médico/clínica ou diretamento no setor de marcações';
                    return $this->response->setJSON($response);
                }
            }
        }


        $dadosPaciente = $this->PacientesModel->pegaPacientePorCodPaciente($codPacienteMarcacao);


        if ($agendamento->codFaixaEtaria > 0) {
            if ($dadosPaciente->idade < $agendamento->idadeMinima or $dadosPaciente->idade > $agendamento->idadeMaxima) {

                //APLICAR REGRA DE EXCESSÃO
                if ($agendamento->codFaixaEtaria == 200 and $dadosPaciente->idade >= 18) {
                } else {
                    $response['success'] = false;
                    $response['messages'] = 'Este especialista só atende pacientes ' .  $agendamento->descricaoFaixaEtaria;
                    return $this->response->setJSON($response);
                }
            }
        }




        $response = array();

        $protocolo = date('Y') . str_pad($codPacienteMarcacao, 6, '0', STR_PAD_LEFT)  . geraNumero(2);
        $fields['codAgendamento'] = $codAgendamento;
        $fields['codOrganizacao'] = session()->codOrganizacao;
        $fields['codPaciente'] = $codPacienteMarcacao;
        $fields['codStatus'] = 1;
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['dataMarcacao'] = date('Y-m-d H:i');

        $fields['protocolo'] = $protocolo;


        if (session()->codPessoa !== NULL) {
            $funcionario = 1;
            $fields['codAutor'] = session()->codPessoa;
            $fields['marcadoPor'] = session()->codPessoa;
            $pessoa = $this->PessoasModel->pegaPessoaDepartamento(session()->codPessoa);
            $nomeFuncionarioResponsavel = $pessoa->nomeExibicao;
            $nomeDepartamentoResponsavel = $pessoa->descricaoDepartamento;
        } else {
            $funcionario = 0;
            if (session()->codPaciente !== NULL) {
                $fields['marcadoPor'] = session()->codPaciente;
                $fields['codAutor'] = session()->codPaciente;
            }
        }

        $this->validation->setRules([
            'codAgendamento' => ['label' => 'codAgendamento', 'rules' => 'required|numeric|max_length[11]'],
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

            if ($this->AgendamentosModel->update($codAgendamento, $fields)) {



                //VERIFICA SE O PACIENTE JÁ ESTAVA NA RESERVA E MODIFICA STATUS PARA RESOLVIDO
                $this->AgendamentosReservasModel->atualizaStatusReserva($codPacienteMarcacao, $agendamento->codEspecialidade);




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
                }



                //ENVIAR SMS
                $celular = removeCaracteresIndesejados($dadosPaciente->celular);
                $conteudoSMS = "
                               Caro senhor(a), " . $dadosPaciente->nomeExibicao . ",";
                $conteudoSMS .= " Sua consulta para " . $agendamento->nomeExibicao . ", " . $agendamento->descricaoEspecialidade . ",  foi marcada para " . diaSemanaCompleto($agendamento->dataInicio) . ' dia '  . date("d/m/Y H:i", strtotime($agendamento->dataInicio)) . ". Nr protocolo:" . $fields['protocolo'] . ".";

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
                $response['codAgendamento'] = $codAgendamento;
                $response['messages'] = 'Marcação realziada com sucesso';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na Marcação!';
            }
        }

        return $this->response->setJSON($response);
    }




    public function reMarcarPaciente()
    {
        $response = array();
        $codAgendamento = $this->request->getPost('codAgendamento');
        $codPacienteMarcacao = $this->request->getPost('codPacienteMarcacao');
        $codAgendamentoAnteriorRemarcacao = $this->request->getPost('codAgendamentoAnteriorRemarcacao');




        //DESMARCAR


        $fieldsDesmarcacao = array();

        $fieldsDesmarcacao['codPaciente'] = 0;
        $fieldsDesmarcacao['codStatus'] = 0;
        $fieldsDesmarcacao['protocolo'] = NULL;
        $fieldsDesmarcacao['dataMarcacao'] = NULL;
        $fieldsDesmarcacao['dataAtualizacao'] = date('Y-m-d H:i');


        if ($this->validation->check($codAgendamentoAnteriorRemarcacao, 'required|numeric')) {
            $this->AgendamentosModel->update($codAgendamentoAnteriorRemarcacao, $fieldsDesmarcacao);
        }



        $agendamento = $this->AgendamentosModel->pegaPorCodigo($codAgendamento);

        // REGRAS DE BLOQUEIO



        if ((int)$agendamento->codPaciente > 0) {
            $response['success'] = false;
            $response['messages'] = 'Alguem já marcou nessa data e hora antes de você. Tente outra marcação!';
            return $this->response->setJSON($response);
        }


        //SLOT É DA INTERNET, SÓ PACIENTE PODE MARCAR

        if ($agendamento->codTipoAgendamento == 1 and session()->codPessoa !== NULL and date('Y-m-d', strtotime(date('Y-m-d') . ' +2 day')) <= date('Y-m-d', strtotime($agendamento->dataInicio))) {

            $response['success'] = false;
            $response['messages'] = 'Vaga reservadas para marcação apenas pela internet!';
            $response['html'] =  '<span style="color:red" >Esta vaga poderá ser utilizada pelo setor de marcações 48h antes do dia da consulta, caso não tenha sido marcada pela Internet!</span>';
            return $this->response->setJSON($response);
        }



        //SLOT É DE RETORNO, SÓ MÉDICOS PODEM MARCAR

        if ($agendamento->codTipoAgendamento == 2 and empty(session()->minhasEspecialidades)) {

            $response['success'] = false;
            $response['messages'] = 'Vaga reservadas para marcação de retorno do pacientes somente o médico pode remarcar';
            return $this->response->setJSON($response);
        }





        //JÁ MARCOU ESPECIALIDADE XXX NOS PRÓXIMOS 30 DIAS
        //RETORNO DE FORA DESSA REGRA


        $excluidos = array('29');

        if (!in_array($agendamento->codEspecialidade, $excluidos)) {


            $verificaExiteMarcacao = $this->AgendamentosModel->exiteAgendamentos30Dias($codPacienteMarcacao, $agendamento->codEspecialidade);

            /*
            if (!empty($verificaExiteMarcacao)) {

                $response['success'] = false;
                $response['messages'] = 'Só é possível marcar 1 consulta nesta especialidade pelo período de 30 dias. Verifique a possibilidade de marcação de retorno junto ao seu médico/clínica ou diretamento no setor de marcações';
                return $this->response->setJSON($response);
            }




            //VERIFICA SE MAIS DE 3 CONSULTAS NOS 30 DIAS

            $verificaExite3Consultas = $this->AgendamentosModel->exiteAgendamentos3Consultas30Dias($codPacienteMarcacao);


            if ($verificaExite3Consultas >= 3) {
                $response['success'] = false;
                $response['messages'] = 'Só é possível marcar 3 especialidades pelo período de 30 dias.';
                return $this->response->setJSON($response);
            }


*/

            $verificaPacienteFaltoso = $this->AgendamentosModel->verificaPacienteFaltoso($codPacienteMarcacao, $agendamento->codEspecialidade);



            if (!empty($verificaPacienteFaltoso)) {
                $response['success'] = false;
                $response['messages'] = 'Foi identificado que o Paciente faltou a uma consulta nesta especialidade. O Paciente está impedido de fazer novas marcações até ' . date('d/m/Y', strtotime($verificaPacienteFaltoso[0]->dataEncerramentoImpedimento)) . ".";
                return $this->response->setJSON($response);
            }
        }


        $dadosPaciente = $this->PacientesModel->pegaPacientePorCodPaciente($codPacienteMarcacao);


        $response = array();

        $protocolo = date('Y') . str_pad($codPacienteMarcacao, 6, '0', STR_PAD_LEFT)  . geraNumero(2);
        $fields['codAgendamento'] = $codAgendamento;
        $fields['codOrganizacao'] = session()->codOrganizacao;
        $fields['codPaciente'] = $codPacienteMarcacao;
        $fields['codStatus'] = 1;
        $fields['dataAtualizacao'] = date('Y-m-d H:i');

        $fields['protocolo'] = $protocolo;


        if (session()->codPessoa !== NULL) {
            $funcionario = 1;
            $fields['codAutor'] = session()->codPessoa;
            $fields['marcadoPor'] = session()->codPessoa;
            $pessoa = $this->PessoasModel->pegaPessoaDepartamento(session()->codPessoa);
            $nomeFuncionarioResponsavel = $pessoa->nomeExibicao;
            $nomeDepartamentoResponsavel = $pessoa->descricaoDepartamento;
        } else {
            $funcionario = 0;
            if (session()->codPaciente !== NULL) {
                $fields['marcadoPor'] = session()->codPaciente;
                $fields['codAutor'] = session()->codPaciente;
            }
        }

        $this->validation->setRules([
            'codAgendamento' => ['label' => 'codAgendamento', 'rules' => 'required|numeric|max_length[11]'],
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

            if ($this->AgendamentosModel->update($codAgendamento, $fields)) {



                //VERIFICA SE O PACIENTE JÁ ESTAVA NA RESERVA E MODIFICA STATUS PARA RESOLVIDO
                $this->AgendamentosReservasModel->atualizaStatusReserva($codPacienteMarcacao, $agendamento->codEspecialidade);




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
                }


                //ENVIAR SMS
                $celular = removeCaracteresIndesejados($dadosPaciente->celular);
                $conteudoSMS = "
                               Caro senhor(a), " . $dadosPaciente->nomeExibicao . ",";
                $conteudoSMS .= " Sua consulta para " . $agendamento->nomeExibicao . ", " . $agendamento->descricaoEspecialidade . ",  foi marcada para " . diaSemanaCompleto($agendamento->dataInicio) . " dia" . date("d/m/Y H:i", strtotime($agendamento->dataInicio)) . ". Nr protocolo:" . $fields['protocolo'] . ".";

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
                $response['codAgendamento'] = $codAgendamento;
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



    public function dashboardConsultas()
    {



        $data = [
            'controller'        => 'teste',
            'title'             => 'teste'
        ];
        echo view('tema/cabecalho');
        echo view('tema/menu_vertical');
        echo view('tema/menu_horizontal');
        return view('relatorios/CONSULTAS/dashboard', $data);
    }

    public function agendamentosPorEspecialidade()
    {
        $response = array();


        $codEspecialidade = session()->filtroEspecialidade["codEspecialidade"];
        $codEspecialista = session()->filtroEspecialidade["codEspecialista"];

        //DADOS DA ESPECIAIDADE
        $dadosEspecialidade = $this->EspecialidadesModel->pegaEspecialidadePorCodEspecialidade($codEspecialidade, $codEspecialista);


        //DADOS DA ESPECIALIDADE
        $ultimasAgendasAbertas = $this->AgendamentosModel->ultimasAgendasAbertasInternet($codEspecialidade);

        //DADOS DA ESPECIALIDADE
        //$qtdBeneficiariosTentandoMarcar = $this->AgendamentosModel->qtdBeneficiariosTentandoMarcar($codEspecialidade);

        $beneficiariosPesquisaram = 0; //$qtdBeneficiariosTentandoMarcar->totalBeneficiarios;

        if ($beneficiariosPesquisaram > 0) {
            $demanda = '<div>** ' . $beneficiariosPesquisaram . ' beneficiário(s) buscaram o serviço de agendamento para essa especialidade nos últimos 20 dias.</div>
            ';
        } else {
            $demanda = '';
        }


        //ESTATÍSTICAS DAS VAGAS
        $dadosUltimasAgendasAbertas = "";

        if (!empty($ultimasAgendasAbertas)) {
            $vagasInternet = 0;
            $vagasPresencial = 0;
            $vagasRetorno = 0;
            $ultimaLiberacao = "";
            foreach ($ultimasAgendasAbertas as $row) {
                if ($row->codTipo == 1) {
                    //INTERNET
                    $vagasInternet = $row->totalVagas;
                }
                if ($row->codTipo == 4) {
                    //PRESENCIAL
                    $vagasPresencial = $row->totalVagas;
                }
                if ($row->codTipo == 2) {
                    //RETORNO
                    $vagasRetorno = $row->totalVagas;
                }
                //$ultimaLiberacao = "*** Data da última abertura da agenda foi em ".date("d/m/Y", strtotime($row->ultimaLiberacao)).' às '.date("H:i", strtotime($row->ultimaLiberacao)).'.';
            }


            $dadosUltimasAgendasAbertas .=

                '
            <div style="font-size:20px" class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-bell"></i> <b>INFORMAÇÕES SOBRE AS VAGAS DE "' . mb_strtoupper($dadosEspecialidade->descricaoEspecialidade, 'utf-8') . '"</b></h5>
                
                <div>* Nos últimos 15 dias foram disponibilizadas ' . $vagasInternet . ' vagas para internet, ' . $vagasPresencial . ' vagas em formato presencial e ' . $vagasRetorno . ' vagas de retorno.</div>

                ' . $demanda . $ultimaLiberacao;

            $dadosUltimasAgendasAbertas .= '
            </div>
            
            ';
        }


        $faixaEtaria = '';
        if ($dadosEspecialidade->codFaixaEtaria > 0) {
            $faixaEtaria = $dadosEspecialidade->descricaoFaixaEtaria;
        }


        //VERIFICA SE EXIGE INDICAÇÃO

        if ($dadosEspecialidade->exigirIndicacao == 1) {

            //VERIFICA SE PACIENTE WRITELIST
            if (session()->codPaciente !== NULL) {

                $verificaExistencia = $this->IndicacoesClinicasModel->verificaExistencia(session()->codPaciente, $codEspecialidade);
                if ($verificaExistencia == NULL) {


                    if ($dadosEspecialidade->mensagemExigirIndicacao == NULL or $dadosEspecialidade->mensagemExigirIndicacao == "") {
                        $mensagemIndicacao  = 'Caro usuário(a), para consultar as vagas dessa especialidade você necessita de uma indicação médica cadastrada no sistema. Recomendamos que você procure o ABAS para avaliação médica ou a triagem da Clinica odontológica para as vagas odontológicas.';
                    } else {
                        $mensagemIndicacao = $dadosEspecialidade->mensagemExigirIndicacao;
                    }
                    $response['success'] = true;
                    $response['csrf_hash'] = csrf_hash();
                    $response['slotsLivres'] = '

                        <div style="font-size:20px;background:#f11717;color:#fff !important" class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-ban"></i> <b>Essa especialidade exige indicação clínica</b></h5>
                           ' . $mensagemIndicacao . '
                        </div>
                        
                        <div style="font-size:20px" class="alert alert-info alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-bell"></i> <b>IMPORTANTE</b></h5>
                        As indicações para especialistas são válidas por 120 dias, após esse período é necessário ser reavaliado.
                        </div>
                        ';

                    return $this->response->setJSON($response);
                }
            }
        }




        $agendamentos = $this->AgendamentosModel->agendamentosPorEspecialidade();


        //VERIFICA SE PERMITE CADASTRO RESERVA
        $verificaCadastroReserva = $this->AgendamentosModel->verificaCadastroReserva($codEspecialidade);

        if ($agendamentos == 'noEspecialidade') {

            $response['success'] = true;
            $response['csrf_hash'] = csrf_hash();
            $response['slotsLivres'] = '<div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban"></i> AVISO!</h5>
            Informe ao menos uma especialidade!
          </div>';
            $response['slotsLivres'] .=  $dadosUltimasAgendasAbertas;
            return $this->response->setJSON($response);
        }



        if (empty($agendamentos) or $agendamentos == NULL) {


            if (session()->codPaciente !== NULL) {

                //REGISTRAR QUE FEZ A PESQUISA
                $this->LogsModel->inserirPesquisaVagas(session()->codPaciente, $codEspecialidade);
            }


            $response['success'] = true;
            $response['csrf_hash'] = csrf_hash();
            if ($verificaCadastroReserva->cadastroReserva == 0) {
                $response['slotsLivres'] = '

                        <div style="font-size:20px;background:#f11717;color:#fff !important" class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-ban"></i> <b>AVISO!</b></h5>
                            Nenhuma vaga encontrada!
                        </div>';

                if ($dadosEspecialidade->mensagemFalhaMarcacao !== NULL and $dadosEspecialidade->mensagemFalhaMarcacao !== "" and $dadosEspecialidade->mensagemFalhaMarcacao !== " ") {
                    $response['slotsLivres'] .= '
                        <div style="font-size:20px" class="alert alert-info alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-bell"></i> <b>IMPORTANTE</b></h5>
                        ' . $dadosEspecialidade->mensagemFalhaMarcacao . '
                        </div>
                            ';
                }
                $response['slotsLivres'] .=  $dadosUltimasAgendasAbertas;
            } else {




                //VERIFICA SE PACIENTE TENTOU MAIS DE 15DIAS E NÃO CONSEGUIU

                if (session()->codPaciente !== NULL) {
                    $verificatentativasPaciente = $this->AgendamentosModel->verificatentativasPaciente(session()->codPaciente, $codEspecialidade);

                    if ($verificatentativasPaciente->dias >= 10) {
                        $response['slotsLivres'] = '
                        <div style="background:#3498db38 !important" class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-ban"></i> <b>AVISO!</b></h5>
                            Nenhuma vaga encontrada!
                            Deseja entrar na lista de reservas?
                            <button onclick="entrarListaReserva(\'' . $codEspecialidade . '\',\'' . '\')" type="button" class="btn btn-lg btn-success" data-dismiss="alert" aria-hidden="true"> ENTRAR NA LISTA DE RESERVA</button>

                         </div>';
                    } else {
                        $response['slotsLivres'] = '

                    <div style="font-size:20px;background:#f11717;color:#fff !important" class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-ban"></i> <b>AVISO!</b></h5>
                        Nenhuma vaga encontrada!
                    </div>';



                        if ($dadosEspecialidade->mensagemFalhaMarcacao !== NULL and $dadosEspecialidade->mensagemFalhaMarcacao !== "" and $dadosEspecialidade->mensagemFalhaMarcacao !== " ") {
                            $response['slotsLivres'] .= '
                        <div style="font-size:20px" class="alert alert-info alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-bell"></i> <b>IMPORTANTE</b></h5>
                        ' . $dadosEspecialidade->mensagemFalhaMarcacao . '
                        </div>
                            ';
                        }
                    }
                } else {
                    $response['slotsLivres'] = '

                    <div style="font-size:20px;background:#f11717;color:#fff !important" class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-ban"></i> <b>AVISO!</b></h5>
                        Nenhuma vaga encontrada!
                    </div>';


                    if ($dadosEspecialidade->mensagemFalhaMarcacao !== NULL and $dadosEspecialidade->mensagemFalhaMarcacao !== "" and $dadosEspecialidade->mensagemFalhaMarcacao !== " ") {
                        $response['slotsLivres'] .= '
                            <div style="font-size:20px" class="alert alert-info alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h5><i class="icon fas fa-bell"></i> <b>IMPORTANTE</b></h5>
                            ' . $dadosEspecialidade->mensagemFalhaMarcacao . '
                            </div>
                                ';
                    }
                }





                if ($dadosEspecialidade->mensagemSucessoMarcacao !== NULL and $dadosEspecialidade->mensagemSucessoMarcacao !== "" and $dadosEspecialidade->mensagemSucessoMarcacao !== " ") {
                    $response['slotsLivres'] .= '
                        <div style="font-size:20px" class="alert alert-info alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-bell"></i> <b>IMPORTANTE</b></h5>
                        ' . $dadosEspecialidade->mensagemSucessoMarcacao . '
                        </div>
                            ';
                }
                $response['slotsLivres'] .=  $dadosUltimasAgendasAbertas;
            }



            return $this->response->setJSON($response);
        }
        $especialistas = array();
        $arrayMaster = array();
        foreach ($agendamentos as $especialista) {
            if (!in_array($especialista->codEspecialista, $especialistas) and $especialista->codEspecialista !== NULL) {


                array_push($arrayMaster, array(
                    'codEspecialista' => $especialista->codEspecialista,
                    'codEspecialidade' => $especialista->codEspecialidade,
                ));

                array_push($especialistas, $especialista->codEspecialista);
            }
        }

        $slotsLivres = '';
        $slotsLivres .= '<div class="row">';
        foreach ($arrayMaster as $dados) {


            $dadosEspecialista = $this->AgendamentosModel->pegaPessoa($dados['codEspecialista'], $dados['codEspecialidade']);

            if ($dadosEspecialista->descricaoFaixaEtaria !== NULL) {
                $descricaoFaixaEtaria = ' - ' . $dadosEspecialista->descricaoFaixaEtaria;
            } else {
                $descricaoFaixaEtaria = '';
            }

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
                                ' . $dadosEspecialista->nomeExibicao . $descricaoFaixaEtaria . '

                                </span>
                            </div>

                        </a>



        ';

            $slotsLivres .= '<div style="margin-top:10px" class="row">';
            foreach ($agendamentos as  $key => $agendamento) {
                if ($dados['codEspecialista'] == $agendamento->codEspecialista) {



                    if ($agendamento->codPaciente == 0) {
                        $hora = date('H:i', strtotime($agendamento->dataInicio));
                        $diaMes = date('d/m', strtotime($agendamento->dataInicio));
                        $diaSenma = diaSemanaAbreviado($agendamento->dataInicio);

                        $botao = 'btn-outline-primary';

                        if ($agendamento->nomeTipo == 'PRESENCIAL') {
                            $botao = 'btn-outline-success';
                        }
                        if ($agendamento->nomeTipo == 'RETORNO') {
                            $botao = 'btn-info';
                        }
                        if ($agendamento->nomeTipo == 'CIRURGIA') {
                            $botao = 'btn-danger';
                        }
                        if ($agendamento->nomeTipo == 'INTERNET') {
                            $botao = 'btn-outline-info';
                        }

                        $slotsLivres .= '

                    <div class="col-md-3">
                        <div class="form-group">
                            <button type="button" onclick="escolhaPaciente(' . $agendamento->codAgendamento . ')" class="btn btn-block ' . $botao . ' btn-lg">
                            <div>' . $agendamento->nomeTipo . '/' . $agendamento->descricaoDepartamento . '</div>
                            <div style="font-weight:bold">' . "(" . $diaSenma . ") " . $diaMes . ' - ' . $hora . ' </div>
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


    public function remarcacaoPorEspecialidade()
    {
        $response = array();



        $codAgendamento = $this->request->getPost('codAgendamento');

        $meuAgendamentoAtual = $this->AgendamentosModel->pegaPorCodigo($codAgendamento);

        $codEspecialidade = $meuAgendamentoAtual->codEspecialidade;
        $codEspecialista = NULL;


        $agendamentos = $this->AgendamentosModel->remarcacaoPorEspecialidade($codEspecialidade);

        //VERIFICA SE PERMITE CADASTRO RESERVA
        $verificaCadastroReserva = $this->AgendamentosModel->verificaCadastroReserva($codEspecialidade);

        if ($agendamentos == 'noEspecialidade') {

            $response['success'] = true;
            $response['csrf_hash'] = csrf_hash();
            $response['slotsLivres'] = '<div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban"></i> AVISO!</h5>
            Informe ao menos uma especialidade!
          </div>';
            return $this->response->setJSON($response);
        }



        if (empty($agendamentos) or $agendamentos == NULL) {

            sleep(3);
            $response['success'] = true;
            $response['csrf_hash'] = csrf_hash();
            if ($verificaCadastroReserva->cadastroReserva == 0) {
                $response['slotsLivres'] = '<div style="background:#fd7e1473 !important" class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-ban"></i> <b>AVISO!</b></h5>
                    Não é possível remarcar, pois nenhuma vaga foi encontrada. !
                </div>';
            }



            return $this->response->setJSON($response);
        }
        $especialistas = array();
        foreach ($agendamentos as $especialista) {
            if (!in_array($especialista->codEspecialista, $especialistas) and $especialista->codEspecialista !== NULL) {
                array_push($especialistas, $especialista->codEspecialista);
            }
        }

        $slotsLivres = '';
        $slotsLivres .= '<div class="row">';
        foreach ($especialistas as $especialista) {


            $dadosEspecialista = $this->AgendamentosModel->pegaPessoa($especialista);


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
            foreach ($agendamentos as  $key => $agendamento) {
                if ($especialista == $agendamento->codEspecialista) {



                    if ($agendamento->codPaciente == 0) {
                        $hora = date('H:i', strtotime($agendamento->dataInicio));
                        $diaMes = date('d/m', strtotime($agendamento->dataInicio));
                        $diaSenma = diaSemanaAbreviado($agendamento->dataInicio);

                        $botao = 'btn-outline-primary';

                        if ($agendamento->nomeTipo == 'PRESENCIAL') {
                            $botao = 'btn-outline-success';
                        }
                        if ($agendamento->nomeTipo == 'RETORNO') {
                            $botao = 'btn-info';
                        }
                        if ($agendamento->nomeTipo == 'INTERNET') {
                            $botao = 'btn-outline-info';
                        }

                        $slotsLivres .= '

                    <div class="col-md-3">
                        <div class="form-group">
                            <button type="button" onclick="escolhaPacienteRemarcacao(' . $agendamento->codAgendamento . ')" class="btn btn-block ' . $botao . ' btn-lg">
                            <div>' . $agendamento->nomeTipo . '</div>
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




        $agendamentoAnterior = "

        <div style='background:#ff00008c' class='callout callout-danger'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>


                <div style='font-size:20px'>
                <div style='font-weight:bold'>DESMARCAR</div>
                <div>Paciente:  " .  $meuAgendamentoAtual->nomeCompleto .   "</div>
                <div>Especialista:  " . $meuAgendamentoAtual->nomeExibicao . " (" . $meuAgendamentoAtual->descricaoEspecialidade . ")</div>
                <div>Data/Hora: " . date('d/m/Y H:i', strtotime($meuAgendamentoAtual->dataInicio)) . "</div>
                <div>Local: " . $meuAgendamentoAtual->descricaoDepartamento . "</div>
                </div>


        </div>



        ";




        sleep(2);

        $response['agendamentoAnterior'] = $agendamentoAnterior;
        $response['success'] = true;
        $response['codPaciente'] = $meuAgendamentoAtual->codPaciente;
        $response['nomePaciente'] = $meuAgendamentoAtual->nomeCompleto;
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



    public function encaixePaciente()
    {

        $response = array();


        $dataInicio = $this->request->getPost('dataInicio') . ' ' . $this->request->getPost('horaInicio');



        $fields['codAgendamento'] = $this->request->getPost('codAgendamento');
        $fields['codConfig'] = 0;
        $fields['codOrganizacao'] = session()->codOrganizacao;
        $fields['codPaciente'] = $this->request->getPost('codPaciente');
        $fields['codLocal'] = 0;
        $fields['codEspecialista'] = $this->request->getPost('codEspecialista');;
        $fields['codEspecialidade'] = $this->request->getPost('codEspecialidade');
        $fields['codStatus'] = 6;
        $fields['dataCriacao'] = date('Y-m-d H:i');
        $fields['dataAtualizacao'] =  date('Y-m-d H:i');
        $fields['dataInicio'] =  $dataInicio;
        $fields['dataEncerramento'] =  $dataInicio;
        $fields['codTipoAgendamento'] = 6;
        $fields['codAutor'] = session()->codPessoa;
        $fields['protocolo'] = date('Y') . str_pad($this->request->getPost('codPaciente'), 6, '0', STR_PAD_LEFT)  . geraNumero(2);
        $fields['ordemAtendimento'] = 1000;


        $this->validation->setRules([
            'codConfig' => ['label' => 'CodConfig', 'rules' => 'required|numeric|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->AgendamentosModel->insert($fields)) {

                $response['success'] = true;
                $response['csrf_hash'] = csrf_hash();
                $response['messages'] = 'Encaixe realizado com sucesso';
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

        if (session()->codPaciente !== NULL) {

            $dadosPaciente = $this->PacientesModel->organizacaoPaciente(session()->codPaciente);
            $nomePaciente = $dadosPaciente->nomeCompleto;
        } else {
            $nomePaciente = $this->request->getPost('nomePaciente');
        }



        $fields['codAgendamento'] = $this->request->getPost('codAgendamento');
        $fields['dataAtualizacao'] = $dataAtualizacao;

        if ($fields['codAgendamento'] !== NULL and $fields['codAgendamento'] !== "" and $fields['codAgendamento'] !== " ") {
            $this->AgendamentosModel->update($fields['codAgendamento'], $fields);
        }

        $dadosAgendamento = $this->AgendamentosModel->pegaPorCodigo($fields['codAgendamento']);

        if ($dadosAgendamento !== NULL) {

            $response['success'] = true;
            $response['csrf_hash'] = csrf_hash();

            $html = "

            <div class='callout callout-info'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>


                    <div style='font-size:20px'>
                    <div style='font-weight:bold'><span class='right badge badge-success'>Nova Marcação. Confirme os dados!</span> </div>
                    <div>Paciente:  " .  $nomePaciente .   "</div>
                    <div>Especialista:  " . $dadosAgendamento->nomeExibicao . " (" . $dadosAgendamento->descricaoEspecialidade . ")</div>
                    <div>Data/Hora: " . date('d/m/Y H:i', strtotime($dadosAgendamento->dataInicio)) . "</div>
                    <div>Local: " . $dadosAgendamento->descricaoDepartamento . "</div>
                    </div>


            </div>



            ";
            $response['dadosConfirmacao'] = $html;
        } else {
            $response['success'] = false;
        }

        return $this->response->setJSON($response);
    }





    public function reservaUmMinutoRemarcacao()
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



        $fields['codAgendamento'] = $this->request->getPost('codAgendamento');
        $fields['dataAtualizacao'] = $dataAtualizacao;

        if ($fields['codAgendamento'] !== NULL and $fields['codAgendamento'] !== "" and $fields['codAgendamento'] !== " ") {
            $this->AgendamentosModel->update($fields['codAgendamento'], $fields);
        }

        $dadosAgendamento = $this->AgendamentosModel->pegaPorCodigo($fields['codAgendamento']);

        if ($dadosAgendamento !== NULL) {

            $response['success'] = true;
            $response['csrf_hash'] = csrf_hash();

            $html = "

            <div style='background:#28a74580' class='callout callout-info'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>


                    <div style='font-size:20px'>
                    <div style='font-weight:bold'><span class='right badge badge-success'>NOVA MARCAÇÃO</span> </div>
                    <div>Paciente:  " .  $nomePaciente .   "</div>
                    <div>Especialista:  " . $dadosAgendamento->nomeExibicao . " (" . $dadosAgendamento->descricaoEspecialidade . ")</div>
                    <div>Data/Hora: " . date('d/m/Y H:i', strtotime($dadosAgendamento->dataInicio)) . "</div>
                    <div>Local: " . $dadosAgendamento->descricaoDepartamento . "</div>
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

            if ($fields['codAgendamento'] !== NULL and $fields['codAgendamento'] !== "" and $fields['codAgendamento'] !== " ") {

                if ($this->AgendamentosModel->update($fields['codAgendamento'], $fields)) {

                    $response['success'] = true;
                    $response['csrf_hash'] = csrf_hash();
                    $response['messages'] = 'Atualizado com sucesso';
                } else {

                    $response['success'] = false;
                    $response['messages'] = 'Erro na atualização!';
                }
            } else {
                $response['success'] = false;
                $response['messages'] = 'Erro na atualização!';
            }
        }

        return $this->response->setJSON($response);
    }

    public function desmarcar()
    {
        $response = array();

        $codAgendamento = $this->request->getPost('codAgendamento');

        $dadosAgendamento = $this->AgendamentosModel->pegaPorCodigo($codAgendamento);

        if ($dadosAgendamento->codStatus == 4) {

            $response['success'] = false;
            $response['messages'] = 'Não é possível desmarcar';
            return $this->response->setJSON($response);
        }


        $especialidade = $dadosAgendamento->descricaoEspecialidade;
        $especialista = $dadosAgendamento->nomeExibicao;
        $protocolo = $dadosAgendamento->protocolo;
        $dataInicio = date('d/m/Y H:i', strtotime($dadosAgendamento->dataInicio));

        $dadosPaciente = $this->PacientesModel->pegaPacientePorCodPaciente($dadosAgendamento->codPaciente);

        $nomePaciente = $dadosPaciente->nomeExibicao;

        $fields = array();

        $fields['codPaciente'] = 0;
        $fields['codStatus'] = 0;
        $fields['protocolo'] = NULL;
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


            //NÃO DEIXA ATUALIZAR SE cÓDIGO FOR NULO OU VAZIO
            if ($codAgendamento !== NULL and $codAgendamento !== "" and $codAgendamento !== " ") {
            } else {
                $response['success'] = false;
                $response['messages'] = 'Erro na operação! Atualização sem codAgendamento';
                return $this->response->setJSON($response);
            }

            if ($this->AgendamentosModel->update($codAgendamento, $fields)) {


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



    public function faltou()
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

        $fields['codPaciente'] = $dadosAgendamento->codPaciente;
        $fields['codEspecialidade'] = $dadosAgendamento->codEspecialidade;
        $fields['codEspecialista'] = $dadosAgendamento->codEspecialista;
        $fields['dataCriacao'] = date('Y-m-d H:i');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['dataInicioImpedimento'] = date('Y-m-d');
        $fields['dataEncerramentoImpedimento'] = date('Y-m-d', strtotime(' +30 days'));
        $fields['impedidoAgendar'] = 0;
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


        if (!$this->validation->check($codAgendamento, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($this->AgendamentosFaltasModel->insert($fields)) {


                //ATUALIZA STATUS NA TABELA DE AGENDAMENTOS

                $statusArray = array();
                $statusArray['codStatus'] = 3; //Faltou
                $statusArray['dataAtualizacao'] = date('Y-m-d H:i');
                $this->AgendamentosModel->update($codAgendamento, $statusArray);

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
                    $conteudo .= "<div>Não registramos sua presença no ambulatório " . $especialidade  . " para sua consulta com " . $especialista . ", em " . date("d/m/Y  H:i", strtotime($dadosAgendamento->dataInicio)) . "</div>";
                    $conteudo .= "<div>Devido à grande procura por agendamentos, não será possível remarcar para essa especialidade nos próximos 30 dias.</div>";

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
                    $conteudoSMS .= "Não registramos sua presença na  consulta ambulatorial " . $especialidade . ". Devido à grande procura por agendamentos, não será possível remarcar para essa especialidade nos próximos 30 dias.";

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
                $response['messages'] = 'Paciente foi registrado com falta e está impedido de agendar para ' . $especialista . ' (' . $especialidade . ')';
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





        if (!$this->validation->check($codAgendamento, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($this->AgendamentosModel->update($codAgendamento, $fields)) {


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


    public function encerrarConsulta()
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





        if (!$this->validation->check($codAgendamento, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($this->AgendamentosModel->update($codAgendamento, $fields)) {


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
                    $conteudo .= "<div>Sua consulta foi encerrada com o(a) " . $especialista . " ( " . $especialidade . ").</div>";
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
                    $conteudoSMS .= "Sua consulta foi encerrada com o(a) " . $especialista . " ( " . $especialidade . ").";

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



    public function cancelarConsulta()
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





        if (!$this->validation->check($codAgendamento, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {



            if ($codAgendamento !== NULL and $codAgendamento !== "" and $codAgendamento !== " ") {

                if ($this->AgendamentosModel->update($codAgendamento, $fields)) {


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
                        $conteudo .= "<div>Sua consulta foi cancelada com o(a) " . $especialista . " ( " . $especialidade . ").</div>";
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
                        $conteudoSMS .= "Sua consulta foi encerrada com o(a) " . $especialista . " ( " . $especialidade . ").";

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
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na alteração de status!';
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
