<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\PacientesModel;
use App\Models\OrganizacoesModel;

use App\Models\AtendimentosModel;
use App\Models\AgendamentosModel;
use App\Models\PainelChamadasModel;
use App\Models\AtendimentoAnamneseModel;
use App\Models\PainelChamadasUrgenciaEmergenciaModel;
use App\Models\AtendimentosPrescricoesModel;
use App\Models\AtendimentoslocaisModel;

class Atendimentos extends BaseController
{

    protected $AtendimentosModel;
    protected $AtendimentoslocaisModel;
    protected $pessoasModel;
    protected $OrganizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $PacientesModel;
    protected $PainelChamadasModel;
    protected $AtendimentosPrescricoesModel;
    protected $AtendimentoAnamneseModel;
    protected $PainelChamadasUrgenciaEmergenciaModel;
    protected $LogsModel;
    protected $AgendamentosModel;
    protected $Organizacao;
    protected $validation;

    public function __construct()
    {

        helper('seguranca_helper');
        verificaSeguranca($this, session(), base_url());
        $this->AtendimentosModel = new AtendimentosModel();
        $this->PacientesModel = new PacientesModel();
        $this->PainelChamadasModel = new PainelChamadasModel();
        $this->AtendimentosPrescricoesModel = new AtendimentosPrescricoesModel();
        $this->AtendimentoslocaisModel = new AtendimentoslocaisModel();
        $this->AtendimentoAnamneseModel = new AtendimentoAnamneseModel();
        $this->PainelChamadasUrgenciaEmergenciaModel = new PainelChamadasUrgenciaEmergenciaModel();
        $this->AgendamentosModel = new AgendamentosModel();
        $this->OrganizacoesModel = new OrganizacoesModel();
        $this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
        $this->validation =  \Config\Services::validation();
        $this->codOrganizacao = session()->codOrganizacao;
        $this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
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



    public function iniciarAtendimentoAgendado()
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



        $codAgendamento = $this->request->getPost('codAgendamento');
        $agendamento = $this->AgendamentosModel->pegaPorCodigo($codAgendamento);


        //verifica se já existe um atendimento aberto

        $existe = $this->AtendimentosModel->verificaExistencia($agendamento->codPaciente, $agendamento->codEspecialista, 2);

        if ($existe->codAtendimento !== NULL) {
            $response['success'] = 'info';
            $response['codPaciente'] = $agendamento->codPaciente;
            $response['codAtendimento'] = $existe->codAtendimento;
            $response['nomeCompleto'] = $existe->nomeCompleto;
            $response['codProntuario'] = $existe->codProntuario;
            $response['idade'] = $existe->idade;
            $response['siglaTipoBeneficiario'] = $agendamento->siglaTipoBeneficiario;
            $response['codPlano'] = $agendamento->codPlano;
            $response['nomeCargoTmp'] = $existe->siglaCargo;
            $response['nomeTipoBeneficiarioTmp'] = $existe->nomeTipoBeneficiario;

            if ($existe->codStatus == 0) {
                $response['messages'] = 'Existe um "atendimento/caso" orindo do sistema anterior que precisa de classificação!';
            } else {
                $response['messages'] = 'Já existe uma "atendimento/caso" em aberto em seu nome. Você deve evoluir ou encerrar';
            }

            return $this->response->setJSON($response);
        }



        $fields['codOrganizacao'] = session()->codOrganizacao;
        $fields['codPaciente'] = $agendamento->codPaciente;
        $fields['codLocalAtendimento'] =  session()->codLocalAtendimento;
        $fields['codEspecialista'] = $agendamento->codEspecialista;
        $fields['codEspecialidade'] = $agendamento->codEspecialidade;
        $fields['codStatus'] = 1;
        $fields['dataCriacao'] = date('Y-m-d H:i');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['dataInicio'] = date('Y-m-d H:i');;
        $fields['dataEncerramento'] = null;
        $fields['codTipoAtendimento'] = 2; // Consulta Ambulatório
        $fields['codAutor'] = session()->codPessoa;


        $this->validation->setRules([
            'codOrganizacao' => ['label' => 'CodOrganizacao', 'rules' => 'required|numeric|max_length[11]'],
            'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'required|numeric|max_length[11]'],
            'codLocalAtendimento' => ['label' => 'codLocalAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'codEspecialista' => ['label' => 'CodEspecialista', 'rules' => 'required|numeric|max_length[11]'],
            'codEspecialidade' => ['label' => 'CodEspecialidade', 'rules' => 'required|numeric|max_length[11]'],
            'codStatus' => ['label' => 'Status', 'rules' => 'required|numeric|max_length[11]'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
            'dataInicio' => ['label' => 'DataInicio', 'rules' => 'permit_empty'],
            'dataEncerramento' => ['label' => 'DataEncerramento', 'rules' => 'permit_empty'],
            'codTipoAtendimento' => ['label' => 'CodTipoAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($codAtendimento = $this->AtendimentosModel->insert($fields)) {

                $response['success'] = true;
                $response['codAtendimento'] = $codAtendimento;
                $response['csrf_hash'] = csrf_hash();
                $response['codPaciente'] = $agendamento->codPaciente;
                $response['nomeCompleto'] = $agendamento->nomeCompleto;
                $response['codProntuario'] = $agendamento->codProntuario;
                $response['idade'] = $agendamento->idade;
                $response['siglaTipoBeneficiario'] = $agendamento->siglaTipoBeneficiario;
                $response['codPlano'] = $agendamento->codPlano;
                $response['nomeCargoTmp'] = $agendamento->siglaCargo;
                $response['nomeTipoBeneficiarioTmp'] = $agendamento->nomeTipoBeneficiario;
                $response['messages'] = 'Atendimento Iniciado';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na inserção!';
            }
        }
        sleep(3);

        $response['success'] = true;
        $response['csrf_hash'] = csrf_hash();
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



    public function gravaClassificacaoRisco()
    {
        $response = array();


        $codAtendimento = $this->request->getPost('codAtendimento');


        $atendimento = $this->AtendimentosModel->dadosIniciaAtendimento($codAtendimento);

        //VERIFICA SE ENFERMAGEM

        $minhasEspecialidades = session()->minhasEspecialidades;
        $especialidadesSociais = array('ENFERMAGEM');
        $especialidadeAuxiliar = 0;
        foreach ($minhasEspecialidades as $especialidade) {
            if (in_array($especialidade->descricaoEspecialidade, $especialidadesSociais)) {
                $especialidadeAuxiliar = 1;
            }
        }

        if ($especialidadeAuxiliar == 1) {
            $fields['codStatus'] = 13;
        }



        $fields['codClasseRisco'] = $this->request->getPost('codClasseRisco');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');

        if ($this->AtendimentosModel->update($codAtendimento, $fields)) {
        }


        $response['success'] = true;
        $response['messages'] = 'Classificação de Risco realizada!';

        return $this->response->setJSON($response);
    }



    public function irParaAtendimentoUrgenciaEmergencia()
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

        //VERIFICA SE ENFERMAGEM

        $minhasEspecialidades = session()->minhasEspecialidades;


        $especialidadesSociais = array('ENFERMAGEM');
        $especialidadeAuxiliar = 0;
        foreach ($minhasEspecialidades as $especialidade) {
            if (in_array($especialidade->descricaoEspecialidade, $especialidadesSociais)) {
                $especialidadeAuxiliar = 1;
            }
        }



        $codAtendimento = $this->request->getPost('codAtendimento');
        $atendimento = $this->AtendimentosModel->dadosIniciaAtendimento($codAtendimento);




        if ($atendimento->codAtendimentoLeito !== NULL and $atendimento->codAtendimentoLeito !== $codAtendimento) {

            $response['alertaLeito'] = true;

            $dataPrevAlta = '<img style="width:20px;" src="' . base_url() . '/imagens/atencao.gif">Amanhã, ' . date('d/m/Y', strtotime($data));;

            $response['mensagemAlertaLeito'] = '
            <div class="col-md-12">
                <div style="font-size:25px;color:yellow" class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h3> <img style="width:50px;" src="' . base_url() . '/imagens/atencao.gif"> Atenção!</h3>
                        Verificamos que este paciente pode estar no leito errado. Por favor, confirme se este paciente está realmente no leito <b>"' . $atendimento->descricaoLocalAtendimento . '"</b>!<br> Caso necessário, transfira este paciente para o leito correto.
                </div>
            </div>
            ';
        }



        //atendimento "VIRGEM E NA EMERGENCIA"
        if ($atendimento->codStatus < 1) {


            if ($especialidadeAuxiliar == 1) {
                $fields['codStatus'] = 12;
            } else {
                $fields['codStatus'] = 1;
            }



            $fields['codLocalAtendimento'] =  session()->codLocalAtendimento;
            $fields['dataAtualizacao'] = date('Y-m-d H:i');
            $fields['dataInicio'] = date('Y-m-d H:i');
            $fields['dataEncerramento'] = null;
            $fields['codEspecialista'] = session()->codPessoa;
            $fields['codAutor'] = session()->codPessoa;

            if (!$this->validation->check($codAtendimento, 'required|numeric')) {

                $response['success'] = false;
                $response['messages'] = $this->validation->listErrors();
            } else {
                $this->AtendimentosModel->update($codAtendimento, $fields);
            }


            $response['success'] = true;

            $response['codAtendimento'] = $codAtendimento;
            $response['codPaciente'] = $atendimento->codPaciente;
            $response['nomeCompleto'] = $atendimento->nomeCompleto;
            $response['codProntuario'] = $atendimento->codProntuario;
            $response['idade'] = $atendimento->idade;
            $response['siglaTipoBeneficiario'] = $atendimento->siglaTipoBeneficiario;
            $response['codPlano'] = $atendimento->codPlano;
            $response['siglaCargo'] = $atendimento->siglaCargo;
            $response['nomeLocalAtendimentoTmp'] = $atendimento->descricaoLocalAtendimento;
            $response['nomeUnidadeInternacaoTmp'] = $atendimento->descricaoDepartamento;
            $response['nomeTipoBeneficiario'] = $atendimento->nomeTipoBeneficiario;
            $response['messages'] = 'Atendimento Iniciado';
        } else {


            //ALTERA SE NÃO TIVER ENCERRADO O ATENDIMENTO

            $arrayStatusEncerraAtendimento = array(2, 3, 8, 9, 11);
            if (!in_array($atendimento->codStatus, $arrayStatusEncerraAtendimento)) {



                if ($especialidadeAuxiliar == 1) {
                    //SE ENFERMEIROS NÃO FAZ NADA
                } else {
                    //SE MÉDICO
                    $fields['codEspecialista'] = session()->codPessoa;
                    $fields['dataAtualizacao'] = date('Y-m-d H:i');
                    $fields['codAutor'] = session()->codPessoa;
                    if ($codAtendimento = $this->AtendimentosModel->update($codAtendimento, $fields)) {
                    }
                }
            }



            $response['success'] = true;
            $response['codAtendimento'] = $codAtendimento;
            $response['codPaciente'] = $atendimento->codPaciente;
            $response['nomeCompleto'] = $atendimento->nomeCompleto;
            $response['codProntuario'] = $atendimento->codProntuario;
            $response['idade'] = $atendimento->idade;
            $response['siglaTipoBeneficiario'] = $atendimento->siglaTipoBeneficiario;
            $response['codPlano'] = $atendimento->codPlano;
            $response['siglaCargo'] = $atendimento->siglaCargo;
            $response['nomeTipoBeneficiario'] = $atendimento->nomeTipoBeneficiario;
            $response['messages'] = 'Atendimento Reiniciado';
        }

        return $this->response->setJSON($response);
    }


    public function filtrarAtendidosUrgenciaEmergencia()
    {
        $response = array();

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

        $filtro["dataInicio"] = $dataInicio;
        $filtro["dataEncerramento"] = $dataEncerramento;

        $this->validation->setRules([
            'dataInicio' => ['label' => 'dataInicio', 'rules' => 'permit_empty|bloquearReservado|valid_date'],
            'dataEncerramento' => ['label' => 'dataEncerramento', 'rules' => 'permit_empty|bloquearReservado|valid_date'],

        ]);

        if ($this->validation->run($filtro) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {


            session()->set('filtroAtendimentoUrgenciaEmergecia', $filtro);
            $response['success'] = true;
            $response['csrf_hash'] = csrf_hash();
        }



        return $this->response->setJSON($response);
    }




    public function filtrarMedicamentosPrescritosUrgenciaEmergencia()
    {
        $response = array();

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

        $filtro["dataInicio"] = $dataInicio;
        $filtro["dataEncerramento"] = $dataEncerramento;

        $this->validation->setRules([
            'dataInicio' => ['label' => 'dataInicio', 'rules' => 'permit_empty|bloquearReservado|valid_date'],
            'dataEncerramento' => ['label' => 'dataEncerramento', 'rules' => 'permit_empty|bloquearReservado|valid_date'],

        ]);

        if ($this->validation->run($filtro) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {


            session()->set('filtroMedicamentosPrescritosUrgenciaEmergecia', $filtro);
            $response['success'] = true;
            $response['csrf_hash'] = csrf_hash();
        }



        return $this->response->setJSON($response);
    }


    public function irParaAtendimentoInternacao()
    {
        $response = array();


        $codAtendimento = $this->request->getPost('codAtendimento');
        $atendimento = $this->AtendimentosModel->dadosIniciaAtendimento($codAtendimento);

        if ($atendimento->codAtendimentoLeito !== NULL and $atendimento->codAtendimentoLeito !== $codAtendimento) {

            $response['alertaLeito'] = true;

            $dataPrevAlta = '<img style="width:20px;" src="' . base_url() . '/imagens/atencao.gif">Amanhã, ' . date('d/m/Y', strtotime($data));;

            $response['mensagemAlertaLeito'] = '
            <div class="col-md-12">
                <div style="font-size:25px;color:yellow" class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h3> <img style="width:50px;" src="' . base_url() . '/imagens/atencao.gif"> Atenção!</h3>
                        Verificamos que este paciente pode estar no leito errado. Por favor, confirme se este paciente está realmente no leito <b>"' . $atendimento->descricaoLocalAtendimento . '"</b>!<br> Caso necessário, transfira este paciente para o leito correto.
                </div>
            </div>
            ';
        }

        $response['success'] = true;
        $response['codAtendimento'] = $codAtendimento;
        $response['codPaciente'] = $atendimento->codPaciente;
        $response['nomeCompleto'] = $atendimento->nomeCompleto;
        $response['codProntuario'] = $atendimento->codProntuario;
        $response['idade'] = $atendimento->idade;
        $response['siglaCargo'] = $atendimento->siglaCargo;
        $response['siglaTipoBeneficiario'] = $atendimento->siglaTipoBeneficiario;
        $response['codPlano'] = $atendimento->codPlano;
        $response['nomeTipoBeneficiario'] = $atendimento->nomeTipoBeneficiario;
        $response['nomeUnidadeInternacaoTmp'] = $atendimento->descricaoDepartamento;
        $response['nomeLocalAtendimentoTmp'] = $atendimento->descricaoLocalAtendimento;
        $response['messages'] = 'Atendimento Iniciado';


        return $this->response->setJSON($response);
    }

    public function irParaProntuario()
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



        $codAgendamento = $this->request->getPost('codAgendamento');
        $agendamento = $this->AgendamentosModel->pegaPorCodigo($codAgendamento);


        if ($agendamento->codStatus < 2) {

            $fields['codStatus'] = 1;
            $fields['chegou'] = 1;
            $fields['inicioAtendimento'] = date('Y-m-d H:i');
            if ($codAgendamento !== NULL and $codAgendamento !== "") {
                if ($this->AgendamentosModel->update($codAgendamento, $fields)) {
                }
            }
        }

        if ($agendamento !== NULL) {

            sleep(3);
            $response['success'] = true;
            //$response['codAtendimento'] = $codAtendimento;
            $response['csrf_hash'] = csrf_hash();
            $response['codPaciente'] = $agendamento->codPaciente;
            $response['nomeCompleto'] = $agendamento->nomeCompleto;
            $response['codProntuario'] = $agendamento->codProntuario;
            $response['idade'] = $agendamento->idade;
            $response['siglaTipoBeneficiario'] = $agendamento->siglaTipoBeneficiario;
            $response['codPlano'] = $agendamento->codPlano;
            $response['siglaCargo'] = $agendamento->siglaCargo;
            $response['nomeTipoBeneficiario'] = $agendamento->nomeTipoBeneficiario;
            $response['nomeUnidadeInternacaoTmp'] = $agendamento->descricaoDepartamento;
            $response['nomeLocalAtendimentoTmp'] = NULL;
            $response['messages'] = 'Atendimento Iniciado';



            return $this->response->setJSON($response);
        }


        return $this->response->setJSON($response);
    }



    public function index()
    {

        $permissao = verificaPermissao('Atendimentos', 'listar');
        if ($permissao == 0) {
            echo mensagemAcessoNegado(session()->organizacoes);
            $this->LogsModel->inserirLog('Acesso indevido ao Módulo "Atendimentos"', session()->codPessoa);
            exit();
        }


        $data = [
            'controller'        => 'atendimentos',
            'title'             => 'Atendimentos'
        ];
        echo view('tema/cabecalho');
        echo view('tema/menu_vertical');
        echo view('tema/menu_horizontal');
        return view('atendimentos', $data);
    }




    public function listaDropDownStatusAtendimento()
    {

        $result = $this->AtendimentosModel->listaDropDownStatusAtendimento();
        if ($result !== NULL) {

            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function listaDropDownStatusEncerramentoAtendimento()
    {

        $result = $this->AtendimentosModel->listaDropDownStatusEncerramentoAtendimento();
        if ($result !== NULL) {

            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }



    public function listaDropDownCid10()
    {

        $result = $this->AtendimentosModel->listaDropDownCid10();
        if ($result !== NULL) {

            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function pacientesUrgenciaEmergenciaAcolhimento()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->AtendimentosModel->pacientesUrgenciaEmergenciaAcolhimento();



        //VERIFICA SE ESPECIALISTA
        if (empty(session()->minhasEspecialidades)) {
            $statusMinhasEspecialidades = 0;
        } else {

            $statusMinhasEspecialidades = 1;
        }


        $x = 0;
        foreach ($result as $key => $value) {
            $x++;
            $ops = '
            <div class="btn-group">
                <button type="button" class="btn btn-light">Ação</button>
                <button type="button" class="btn btn-light dropdown-toggle dropdown-icon" data-toggle="dropdown">
                <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu" role="menu">';

            if ($statusMinhasEspecialidades == 1) {
                $ops .= '
                <a href="#" class="dropdown-item" onclick="irParaAtendimentoUrgenciaEmergencia(' . $value->codAtendimento . ')">Ir para o Atendimento</a>
                    <a href="#" class="dropdown-item" onclick="prontuarioAPartirdoPamo(' . $value->codPaciente . ')">Consultar Prontuário</a>
                    <a href="#" class="dropdown-item" onclick="chamarPainelUrgenciaEmergencia(' . $value->codAtendimento . ')">Chamar no painel</a>
                    <a href="#" class="dropdown-item" onclick="imprimirEtiquetaEmergencia(' . $value->codAtendimento . ')">Imprimir Etiqueta</a>
                    <a href="#" class="dropdown-item" onclick="classificacaoRiscoUrgenciaEmergencia(' . $value->codAtendimento . ')">Classificação de Risco</a>
                    <a href="#" class="dropdown-item" onclick="transferirPaciente(' . $value->codAtendimento . ')">Transferir Paciente</a>
                    <a href="#" class="dropdown-item" onclick="aguardandoLeito(' . $value->codAtendimento . ')">Aguardando Leito</a>
               ';
            } else {
                $ops .= '
                <a href="#" class="dropdown-item" onclick="chamarPainelUrgenciaEmergencia(' . $value->codAtendimento . ')">Chamar no painel</a>
                <a href="#" class="dropdown-item" onclick="imprimirEtiquetaEmergencia(' . $value->codAtendimento . ')">Imprimir Etiqueta</a>
                <a href="#" class="dropdown-item" onclick="classificacaoRiscoUrgenciaEmergencia(' . $value->codAtendimento . ')">Classificação de Risco</a>
                ';
            }

            $ops .= ' </div>
</div>';

            $tempo =  intervaloTempoAtendimento($value->dataCriacao, date('Y-m-d H:i'));
            $tempoAtendimento = '<span class="right badge badge-info">' . $tempo . '</span>';

            if ($value->codLocalAtendimento == 0) {
                $descricaoLocalAtendimento =  "Acolhimento";
            } else {
                $descricaoLocalAtendimento =  $value->descricaoDepartamento . " (" . $value->descricaoLocalAtendimento . ")";
            }

            $statusAtendimento = '<span class="right badge badge-warning">' . $value->descricaoStatusAtendimento . '</span>';


            $data['data'][$key] = array(
                $x,
                $value->codClasseRisco,
                $value->nomeCompleto,
                $value->idade,
                $value->queixaPrincipal,
                $descricaoLocalAtendimento,
                date('d/m/Y H:i', strtotime($value->dataCriacao)),
                $statusAtendimento,
                $tempoAtendimento,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }


    public function pacientesInternados()
    {
        $response = array();

        $data['data'] = array();

        if (session()->unidadeInternacao == NULL) {
            $codDepartamento = null;
        } else {
            $codDepartamento =  session()->unidadeInternacao;
        }

        $result = $this->AtendimentosModel->pacientesInternados($codDepartamento);


        $x = 0;
        foreach ($result as $key => $value) {
            $dataPrevAlta = NULL;
            $faltam = NULL;
            $x++;
            $ops = '
            <div class="btn-group">
                <button type="button" class="btn btn-light">Ação</button>
                <button type="button" class="btn btn-light dropdown-toggle dropdown-icon" data-toggle="dropdown">
                <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu" role="menu">
                <a href="#" class="dropdown-item" onclick="irParaAtendimentoInternacao(' . $value->codAtendimento . ')">Ir para o Atendimento</a>
                <a href="#" class="dropdown-item" onclick="imprimirEtiquetaEmergencia(' . $value->codAtendimento . ')">Imprimir Etiqueta</a>
                <a href="#" class="dropdown-item" onclick="transferirPaciente(' . $value->codAtendimento . ')">Transferir Paciente</a>
                </div>
            </div>
            ';


            $tempo =  intervaloTempoAtendimento($value->dataCriacao, date('Y-m-d H:i'));

            $tempoAtendimento = '<div class="right badge badge-info">' . $tempo . '</div>';

            $dadosAlta = $this->AtendimentosModel->previsaoAlta($value->codAtendimento);

            if ($dadosAlta !== NULL) {
                $previsao =  previsaoAlta($dadosAlta->dataPrevAlta, $dadosAlta->dataEncerramento, $dadosAlta->indeterminado);
                $tempoAtendimento .= '<div class="right badge badge-warning">Prev. Alta: ' . $previsao['dataPrevAlta'] . ' ' . $previsao['faltam'] . '<div>';
            } else {
                $previsao = NULL;
                $previsao['dataPrevAlta'] = 'Falta informar';
                $tempoAtendimento .= '<div class="right badge badge-danger">Prev. Alta: ' . $previsao['dataPrevAlta'] . ' ' . $previsao['faltam'] . '<div>';
            }





            if ($value->codLocalAtendimento == 0) {
                $descricaoLocalAtendimento =  "Acolhimento";
            } else {
                $descricaoLocalAtendimento = '
                <div>' . $value->descricaoDepartamento . '</div>
                <div class="right badge badge-success">' . mb_strtoupper($value->descricaoLocalAtendimento, 'utf-8') . '</div>
                ';

                //$descricaoLocalAtendimento =  $value->descricaoDepartamento . " (" . $value->descricaoLocalAtendimento . ")";
            }

            // if ($value->dataInicioPrescricao == date("Y-m-d", time() + 86400) and $value->codStatusPrescricao == 2) {


            $statusAtendimento = '
            <div>
             <span class="right badge badge-warning">' . $value->descricaoStatusAtendimento . '</span>
            </div>

            ';

            if (strlen($value->hda) >= 100) {

                $conteudoHDA = mb_substr($value->hda, 0, 90);
            } else {
                $conteudoHDA = $value->hda;
            }


            $data['data'][$key] = array(
                $x,
                $value->codClasseRisco,
                $value->nomeCompleto,
                $value->idade,
                $conteudoHDA,
                $descricaoLocalAtendimento,
                date('d/m/Y H:i', strtotime($value->dataCriacao)),
                $statusAtendimento,
                $tempoAtendimento,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }

    public function aguardandoLeito()
    {

        $response = array();

        $fields['codOrganizacao'] = session()->codOrganizacao;
        $fields['codAtendimento'] = $this->request->getPost('codAtendimento');
        $fields['codStatus'] = 16;
        $fields['codStatus'] = 16;
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['codAutor'] = session()->codPessoa;



        $this->validation->setRules([
            'codAtendimento' => ['label' => 'codAtendimento', 'rules' => 'required|numeric|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($codAtendimento = $this->AtendimentosModel->update($fields['codAtendimento'], $fields)) {

                $response['success'] = true;
                $response['csrf_hash'] = csrf_hash();
                $response['messages'] = 'Paciente definido como aguardando leito';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na y!';
            }
        }



        return $this->response->setJSON($response);
    }
    public function pacientesUrgenciaEmergenciaEmAtendimento()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->AtendimentosModel->pacientesUrgenciaEmergenciaEmAtendimento();



        $x = 0;
        foreach ($result as $key => $value) {
            $x++;
            $ops = '
            <div class="btn-group">
                <button type="button" class="btn btn-light">Ação</button>
                <button type="button" class="btn btn-light dropdown-toggle dropdown-icon" data-toggle="dropdown">
                <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu" role="menu">
                <a href="#" class="dropdown-item" onclick="irParaAtendimentoUrgenciaEmergencia(' . $value->codAtendimento . ')">Ir para o Atendimento</a>
                <a href="#" class="dropdown-item" onclick="prontuarioAPartirdoPamo(' . $value->codPaciente . ')">Consultar Prontuário</a>
                    <a href="#" class="dropdown-item" onclick="chamarPainelUrgenciaEmergencia(' . $value->codAtendimento . ')">Chamar no painel</a>
                    <a href="#" class="dropdown-item" onclick="imprimirEtiquetaEmergencia(' . $value->codAtendimento . ')">Imprimir Etiqueta</a>
                    <a href="#" class="dropdown-item" onclick="classificacaoRiscoUrgenciaEmergencia(' . $value->codAtendimento . ')">Classificação de Risco</a>
                    <a href="#" class="dropdown-item" onclick="transferirPaciente(' . $value->codAtendimento . ')">Transferir Paciente</a>
                    <a href="#" class="dropdown-item" onclick="aguardandoLeito(' . $value->codAtendimento . ')">Aguardando Leito</a>
                </div>
            </div>
            ';


            $tempo =  intervaloTempoAtendimento($value->dataCriacao, date('Y-m-d H:i'));
            $tempoAtendimento = '<span class="right badge badge-info">' . $tempo . '</span>';


            if ($value->codLocalAtendimento == 0) {
                $descricaoLocalAtendimento =  "Acolhimento";
            } else {
                $descricaoLocalAtendimento = '
                <div>' . $value->descricaoDepartamento . '</div>
                <div class="right badge badge-success">' . mb_strtoupper($value->descricaoLocalAtendimento, 'utf-8') . '</div>
                ';

                //$descricaoLocalAtendimento =  $value->descricaoDepartamento . " (" . $value->descricaoLocalAtendimento . ")";
            }


            $statusAtendimento = '<span class="right badge badge-warning">' . $value->descricaoStatusAtendimento . '</span>';


            $data['data'][$key] = array(
                $x,
                $value->codClasseRisco,
                $value->nomeCompleto,
                $value->idade,
                $value->queixaPrincipal,
                $descricaoLocalAtendimento,
                $value->nomeEspeciaoista, //date('d/m/Y H:i', strtotime($value->dataCriacao)),
                $statusAtendimento,
                $tempoAtendimento,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }

    public function pacientesUrgenciaEmergenciaMeusPacientes()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->AtendimentosModel->pacientesUrgenciaEmergenciaMeusPacientes();



        $x = 0;
        foreach ($result as $key => $value) {
            $x++;
            $ops = '
            <div class="btn-group">
                <button type="button" class="btn btn-light">Ação</button>
                <button type="button" class="btn btn-light dropdown-toggle dropdown-icon" data-toggle="dropdown">
                <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu" role="menu">
                <a href="#" class="dropdown-item" onclick="irParaAtendimentoUrgenciaEmergencia(' . $value->codAtendimento . ')">Ir para o Atendimento</a>
                <a href="#" class="dropdown-item" onclick="prontuarioAPartirdoPamo(' . $value->codPaciente . ')">Consultar Prontuário</a>
                    <a href="#" class="dropdown-item" onclick="chamarPainelUrgenciaEmergencia(' . $value->codAtendimento . ')">Chamar no painel</a>
                    <a href="#" class="dropdown-item" onclick="imprimirEtiquetaEmergencia(' . $value->codAtendimento . ')">Imprimir Etiqueta</a>
                    <a href="#" class="dropdown-item" onclick="classificacaoRiscoUrgenciaEmergencia(' . $value->codAtendimento . ')">Classificação de Risco</a>
                    <a href="#" class="dropdown-item" onclick="transferirPaciente(' . $value->codAtendimento . ')">Transferir Paciente</a>
                    <a href="#" class="dropdown-item" onclick="aguardandoLeito(' . $value->codAtendimento . ')">Aguardando Leito</a>
                </div>
            </div>
            ';


            $tempo =  intervaloTempoAtendimento($value->dataCriacao, date('Y-m-d H:i'));
            $tempoAtendimento = '<span class="right badge badge-info">' . $tempo . '</span>';


            if ($value->codLocalAtendimento == 0) {
                $descricaoLocalAtendimento =  "Acolhimento";
            } else {
                $descricaoLocalAtendimento = '
                <div>' . $value->descricaoDepartamento . '</div>
                <div class="right badge badge-success">' . mb_strtoupper($value->descricaoLocalAtendimento, 'utf-8') . '</div>
                ';

                //$descricaoLocalAtendimento =  $value->descricaoDepartamento . " (" . $value->descricaoLocalAtendimento . ")";
            }


            $statusAtendimento = '<span class="right badge badge-warning">' . $value->descricaoStatusAtendimento . '</span>';


            $data['data'][$key] = array(
                $x,
                $value->codClasseRisco,
                $value->nomeCompleto,
                $value->idade,
                $value->queixaPrincipal,
                $descricaoLocalAtendimento,
                $value->nomeEspeciaoista, //date('d/m/Y H:i', strtotime($value->dataCriacao)),
                $statusAtendimento,
                $tempoAtendimento,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }

    public function pacientesUrgenciaEmergenciaAguardandoLeito()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->AtendimentosModel->pacientesUrgenciaEmergenciaAguardandoLeito();



        $x = 0;
        foreach ($result as $key => $value) {
            $x++;
            $ops = '
            <div class="btn-group">
                <button type="button" class="btn btn-light">Ação</button>
                <button type="button" class="btn btn-light dropdown-toggle dropdown-icon" data-toggle="dropdown">
                <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu" role="menu">
                <a href="#" class="dropdown-item" onclick="irParaAtendimentoUrgenciaEmergencia(' . $value->codAtendimento . ')">Ir para o Atendimento</a>
                <a href="#" class="dropdown-item" onclick="prontuarioAPartirdoPamo(' . $value->codPaciente . ')">Consultar Prontuário</a>
                    <a href="#" class="dropdown-item" onclick="chamarPainelUrgenciaEmergencia(' . $value->codAtendimento . ')">Chamar no painel</a>
                    <a href="#" class="dropdown-item" onclick="imprimirEtiquetaEmergencia(' . $value->codAtendimento . ')">Imprimir Etiqueta</a>
                    <a href="#" class="dropdown-item" onclick="classificacaoRiscoUrgenciaEmergencia(' . $value->codAtendimento . ')">Classificação de Risco</a>
                    <a href="#" class="dropdown-item" onclick="transferirPaciente(' . $value->codAtendimento . ')">Transferir Paciente</a>
                </div>
            </div>
            ';


            $tempo =  intervaloTempoAtendimento($value->dataCriacao, date('Y-m-d H:i'));
            $tempoAtendimento = '<span class="right badge badge-info">' . $tempo . '</span>';


            if ($value->codLocalAtendimento == 0) {
                $descricaoLocalAtendimento =  "Acolhimento";
            } else {
                $descricaoLocalAtendimento = '
                <div>' . $value->descricaoDepartamento . '</div>
                <div class="right badge badge-success">' . mb_strtoupper($value->descricaoLocalAtendimento, 'utf-8') . '</div>
                ';

                //$descricaoLocalAtendimento =  $value->descricaoDepartamento . " (" . $value->descricaoLocalAtendimento . ")";
            }


            $statusAtendimento = '<span class="right badge badge-warning">' . $value->descricaoStatusAtendimento . '</span>';


            $data['data'][$key] = array(
                $x,
                $value->codClasseRisco,
                $value->nomeCompleto,
                $value->idade,
                $value->queixaPrincipal,
                $descricaoLocalAtendimento,
                $value->nomeEspeciaoista, //date('d/m/Y H:i', strtotime($value->dataCriacao)),
                $statusAtendimento,
                $tempoAtendimento,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }


    public function pacientesUrgenciaEmergenciaBaixados()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->AtendimentosModel->pacientesUrgenciaEmergenciaBaixados();



        $x = 0;
        foreach ($result as $key => $value) {
            $x++;
            $ops = '
            <div class="btn-group">
                <button type="button" class="btn btn-light">Ação</button>
                <button type="button" class="btn btn-light dropdown-toggle dropdown-icon" data-toggle="dropdown">
                <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu" role="menu">
                <a href="#" class="dropdown-item" onclick="irParaAtendimentoUrgenciaEmergencia(' . $value->codAtendimento . ')">Ir para o Atendimento</a>
                <a href="#" class="dropdown-item" onclick="prontuarioAPartirdoPamo(' . $value->codPaciente . ')">Consultar Prontuário</a>
                    <a href="#" class="dropdown-item" onclick="chamarPainelUrgenciaEmergencia(' . $value->codAtendimento . ')">Chamar no painel</a>
                    <a href="#" class="dropdown-item" onclick="imprimirEtiquetaEmergencia(' . $value->codAtendimento . ')">Imprimir Etiqueta</a>
                    <a href="#" class="dropdown-item" onclick="classificacaoRiscoUrgenciaEmergencia(' . $value->codAtendimento . ')">Classificação de Risco</a>
                    <a href="#" class="dropdown-item" onclick="transferirPaciente(' . $value->codAtendimento . ')">Transferir Paciente</a>
                    <a href="#" class="dropdown-item" onclick="aguardandoLeito(' . $value->codAtendimento . ')">Aguardando Leito</a>
                </div>
            </div>
            ';


            $tempo =  intervaloTempoAtendimento($value->dataCriacao, date('Y-m-d H:i'));
            $tempoAtendimento = '<span class="right badge badge-info">' . $tempo . '</span>';


            if ($value->codLocalAtendimento == 0) {
                $descricaoLocalAtendimento =  "Acolhimento";
            } else {
                $descricaoLocalAtendimento = '
                <div>' . $value->descricaoDepartamento . '</div>
                <div class="right badge badge-success">' . mb_strtoupper($value->descricaoLocalAtendimento, 'utf-8') . '</div>
                ';

                //$descricaoLocalAtendimento =  $value->descricaoDepartamento . " (" . $value->descricaoLocalAtendimento . ")";
            }


            $statusAtendimento = '<span class="right badge badge-warning">' . $value->descricaoStatusAtendimento . '</span>';


            $data['data'][$key] = array(
                $x,
                $value->codClasseRisco,
                $value->nomeCompleto,
                $value->idade,
                $value->queixaPrincipal,
                $descricaoLocalAtendimento,
                $value->nomeEspeciaoista, //date('d/m/Y H:i', strtotime($value->dataCriacao)),
                $statusAtendimento,
                $tempoAtendimento,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }

    public function pacientesUrgenciaEmergenciaBuscaAvancada()
    {
        $response = array();

        $data['data'] = array();
        if ($this->request->getPost('paciente') !== NULL and $this->request->getPost('paciente') !== "" and $this->request->getPost('paciente') !== 0) {

            $result = $this->AtendimentosModel->pacientesUrgenciaEmergenciaBuscaAvancada($this->request->getPost('paciente'));
        }


        $x = 0;
        foreach ($result as $key => $value) {
            $x++;
            $ops = '
            <div class="btn-group">
                <button type="button" class="btn btn-light">Ação</button>
                <button type="button" class="btn btn-light dropdown-toggle dropdown-icon" data-toggle="dropdown">
                <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu" role="menu">
                <a href="#" class="dropdown-item" onclick="irParaAtendimentoUrgenciaEmergencia(' . $value->codAtendimento . ')">Ir para o Atendimento</a>
                <a href="#" class="dropdown-item" onclick="prontuarioAPartirdoPamo(' . $value->codPaciente . ')">Consultar Prontuário</a>
                    <a href="#" class="dropdown-item" onclick="chamarPainelUrgenciaEmergencia(' . $value->codAtendimento . ')">Chamar no painel</a>
                    <a href="#" class="dropdown-item" onclick="imprimirEtiquetaEmergencia(' . $value->codAtendimento . ')">Imprimir Etiqueta</a>
                    <a href="#" class="dropdown-item" onclick="classificacaoRiscoUrgenciaEmergencia(' . $value->codAtendimento . ')">Classificação de Risco</a>
                    <a href="#" class="dropdown-item" onclick="transferirPaciente(' . $value->codAtendimento . ')">Transferir Paciente</a>
                    <a href="#" class="dropdown-item" onclick="aguardandoLeito(' . $value->codAtendimento . ')">Aguardando Leito</a>
                </div>
            </div>
            ';


            $tempo =  intervaloTempoAtendimento($value->dataCriacao, date('Y-m-d H:i'));
            $tempoAtendimento = '<span class="right badge badge-info">' . $tempo . '</span>';


            if ($value->codLocalAtendimento == 0) {
                $descricaoLocalAtendimento =  "Acolhimento";
            } else {
                $descricaoLocalAtendimento = '
                <div>' . $value->descricaoDepartamento . '</div>
                <div class="right badge badge-success">' . mb_strtoupper($value->descricaoLocalAtendimento, 'utf-8') . '</div>
                ';

                //$descricaoLocalAtendimento =  $value->descricaoDepartamento . " (" . $value->descricaoLocalAtendimento . ")";
            }


            $statusAtendimento = '<span class="right badge badge-warning">' . $value->descricaoStatusAtendimento . '</span>';

            if (strlen($value->hda) >= 100) {

                $conteudoHDA = mb_substr($value->hda, 0, 200) . '...';
            } else {
                $conteudoHDA = $value->hda;
            }

            $data['data'][$key] = array(
                $x,
                $value->codClasseRisco,
                $value->nomeCompleto,
                $value->idade,
                $conteudoHDA,
                $descricaoLocalAtendimento,
                $value->nomeEspeciaoista, //date('d/m/Y H:i', strtotime($value->dataCriacao)),
                $statusAtendimento,
                $tempoAtendimento,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }

    public function pacientesAtendidosUrgenciaEmergencia()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->AtendimentosModel->pacientesAtendidosUrgenciaEmergencia();



        $x = 0;
        foreach ($result as $key => $value) {
            $x++;
            $ops = '';



            $tempo =  intervaloTempoAtendimento($value->dataCriacao, date('Y-m-d H:i'));
            $tempoAtendimento = '<span class="right badge badge-info">' . $tempo . '</span>';

            if ($value->codLocalAtendimento == 0) {
                $descricaoLocalAtendimento =  "Acolhimento";
            } else {
                $descricaoLocalAtendimento = '<div class="right">' . mb_strtoupper($value->descricaoLocalAtendimento, 'utf-8') . '</div>
                ';

                //$descricaoLocalAtendimento =  $value->descricaoDepartamento . " (" . $value->descricaoLocalAtendimento . ")";
            }


            $statusAtendimento = '<span class="right badge badge-warning">' . $value->descricaoStatusAtendimento . '</span>';


            $data['data'][$key] = array(
                $x,
                $value->codPlano,
                $value->siglaCargo,
                $value->nomeCompleto,
                $value->idade,
                $value->siglaTipoBeneficiario,
                $descricaoLocalAtendimento,
                $value->nomeEspeciaoista,
                $value->queixaPrincipal,
                // $value->nomeEspeciaoista, //date('d/m/Y H:i', strtotime($value->dataCriacao)),
                //$statusAtendimento,
                //$tempoAtendimento,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }


    public function medicamentosPrescritosUrgenciaEmergencia()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->AtendimentosModel->medicamentosPrescritosUrgenciaEmergencia();



        $x = 0;
        foreach ($result as $key => $value) {
            $x++;
            $ops = '';

            $data['data'][$key] = array(
                $x,
                $value->descricaoItem,
                $value->total,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }

    public function chamarPacienteAgora()
    {
        $response = array();
        $data = array();

        //VERIFICA SE JÁ FOI DEFINIDO O LOCAL DO ATENDIMENTO

        if (session()->nomeLocalAtendimento == NULL or session()->codLocalAtendimento == NULL  or session()->codLocalAtendimento == 0) {

            $response['success'] = false;
            $response['messages'] = 'Você deve definir primeiro o local de atendimento';
            return $this->response->setJSON($response);
            //session()->codLocalAtendimento = $this->request->getPost('codLocalAtendimento');
            //session()->nomeLocalAtendimento = lookupNomeLocalAtendimento($this->request->getPost('codLocalAtendimento'));
        }



        $codAtendimento = $this->request->getPost('codAtendimento');
        $dados = $this->AtendimentosModel->dadosIniciaAtendimento($codAtendimento);

        if (session()->nomeLocalAtendimento !== NULL) {
            $data['localAtendimento'] = " (" . session()->nomeLocalAtendimento . ")";
        } else {
            $data['localAtendimento'] = "";
        }
        $data['codOrganizacao'] = session()->codOrganizacao;
        $data['codChamador'] = session()->codPessoa;
        $data['qtdChamadas'] = 2;
        $data['codPaciente'] = $dados->codPaciente;
        $data['codClasseRisco'] = $dados->codClasseRisco;
        $data['codEspecialidade'] = $dados->codEspecialidade;

        if ($this->PainelChamadasModel->insert($data)) {
            $response['success'] = true;
            $response['csrf_hash'] = csrf_hash();
            $response['messages'] = 'Paciente ' . $dados->nomeCompleto . ' chamado com sucesso';
        } else {
            $response['success'] = false;
            $response['messages'] = 'Erro ao chamar paciente, contate o administrador do sistema';
        }



        return $this->response->setJSON($response);
    }


    public function chamarPacienteUrgenciaEmergenciaAgora()
    {
        $response = array();
        $data = array();

        //VERIFICA SE JÁ FOI DEFINIDO O LOCAL DO ATENDIMENTO

        if (session()->nomeLocalAtendimento == NULL or session()->codLocalAtendimento == NULL  or session()->codLocalAtendimento == 0) {

            $response['success'] = false;
            $response['messages'] = 'Você deve definir primeiro o local de atendimento';
            return $this->response->setJSON($response);
            //session()->codLocalAtendimento = $this->request->getPost('codLocalAtendimento');
            //session()->nomeLocalAtendimento = lookupNomeLocalAtendimento($this->request->getPost('codLocalAtendimento'));
        }




        $codAtendimento = $this->request->getPost('codAtendimento');
        $dados = $this->AtendimentosModel->dadosIniciaAtendimento($codAtendimento);




        if ($dados->ultimaChamada !== NULL and $dados->ultimaChamada < 5 and $dados->codChamador !== session()->codPessoa) {
            $response['jaChamado'] = true;
            $response['messages'] = 'Paciente já foi chamado por ' . $dados->chamador . ' em ' . $dados->localChamada . ' há ' . $dados->ultimaChamada . ' minuto(s)';
            return $this->response->setJSON($response);
        }





        if (session()->nomeLocalAtendimento !== NULL) {
            $data['localAtendimento'] = " (" . session()->nomeLocalAtendimento . ")";
        } else {
            $data['localAtendimento'] = "";
        }
        $data['codOrganizacao'] = session()->codOrganizacao;
        $data['codChamador'] = session()->codPessoa;
        $data['qtdChamadas'] = 2;
        $data['codPaciente'] = $dados->codPaciente;
        $data['dataChamada'] = date('Y-m-d H:i');
        $data['codClasseRisco'] = $dados->codClasseRisco;
        $data['codEspecialidade'] = $dados->codEspecialidade;

        if ($this->PainelChamadasUrgenciaEmergenciaModel->insert($data)) {
            $response['success'] = true;
            $response['csrf_hash'] = csrf_hash();
            $response['messages'] = 'Paciente ' . $dados->nomeCompleto . ' chamado com sucesso';
        } else {
            $response['success'] = false;
            $response['messages'] = 'Erro ao chamar paciente, contate o administrador do sistema';
        }



        return $this->response->setJSON($response);
    }


    public function todosAtendimentos_OLD()
    {
        $response = array();

        $codPaciente = $this->request->getPost('codPaciente');

        $data['data'] = array();

        $result = $this->AtendimentosModel->Atendimento($codPaciente);
        $x = 0;
        foreach ($result as $key => $value) {
            $x++;
            $btnEditar = '	<span style="margin-top:10px" ><button style="margin-left:1px" type="button" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="Editar paciente" onclick="editarAtendimento(' . $value->codAtendimento . ')"><i class="fa fa-edit"></i> Editar</button></span>';
            $btnResumo = '	<span style="margin-top:10px" ><button style="margin-left:1px" type="button" class="btn  btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Imprimir Resumo" onclick="imprimirBoletimAtendimento(' . $value->codAtendimento . ')"><i class="fa fa-print"></i> Resumo</button></span>';


            if ($value->dataEncerramento == NULL) {
                $dataEncerramento = '-';
            } else {
                $dataEncerramento = date('d/m/Y H:i', strtotime($value->dataEncerramento));
            }


            $statusAtendimento = '<span class="right badge badge-warning">' . $value->descricaoStatusAtendimento . '</span>';



            if ($value->dataEncerramento == NULL) {
                $dataFim = date('Y-m-d H:i');
            } else {
                $dataFim = $value->dataEncerramento;
            }



            if ($value->dataEncerramento == $value->dataInicio and $value->codStatus == 0) {
                //APENAS PARA OS CASOS MIGRADOS DO SISTEMA LEGADO E QUE AINDA NÃO ESTAO CLASSIFICADOS
                $dataFim = date('Y-m-d H:i');
            }



            $tempo =  intervaloTempoAtendimento($value->dataInicio, $dataFim);
            $tempoAtendimento = '';
            if ($value->codStatus !== '0') {
                $tempoAtendimento = '<span class="right badge badge-info">' . $tempo . '</span>';
            }

            if ($value->codLocalAtendimento == 0 and $value->codTipoAtendimento == 1) {
                $descricaoLocalAtendimento =  "PAMO (Acolhimento)";
            } else {
                $descricaoLocalAtendimento =  $value->descricaoDepartamento . " (" . $value->descricaoLocalAtendimento . ")";
            }

            if ($value->hda !== NULL and $value->hda !== "" and $value->hda !== " ") {

                $problema = mb_substr(strip_tags($value->hda), 0, 100) . '...';
            } else {
                $problema = mb_substr(strip_tags($value->queixaPrincipal), 0, 100) . '...';
            }


            $data['data'][$key] = array(
                $value->codAtendimento,
                $value->descricaoEspecialidade,
                $value->nomeEspecialista,
                $descricaoLocalAtendimento,
                $value->descricaoTipoAtendimento,
                $problema,
                date('d/m/Y H:i', strtotime($value->dataInicio)),
                $dataEncerramento,
                $statusAtendimento . $tempoAtendimento . $btnEditar . $btnResumo,
            );
        }

        sleep(1);
        return $this->response->setJSON($data);
    }



    public function todosAtendimentos()
    {
        $response = array();

        $codPaciente = $this->request->getPost('codPaciente');

        $data['data'] = array();

        $result = $this->AtendimentosModel->Atendimento($codPaciente);
        $x = 0;

        $html = '';
        $html .= '<div style="margin-top:20px" class="timeline">';
        foreach ($result as $key => $value) {
            $x++;
            $btnEditar = '	<span style="margin-top:10px" ><button style="margin-left:1px" type="button" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="Editar paciente" onclick="editarAtendimento(' . $value->codAtendimento . ')"><i class="fa fa-edit"></i> Editar</button></span>';
            $btnResumo = '	<span style="margin-top:10px" ><button style="margin-left:1px" type="button" class="btn  btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Imprimir Resumo" onclick="imprimirBoletimAtendimento(' . $value->codAtendimento . ')"><i class="fa fa-print"></i> Resumo</button></span>';
            $btnChamarPainel = '	<span style="margin-top:10px" ><button style="margin-left:1px" type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="Editar paciente" onclick="chamarPainelFromAtendimento(' . $value->codAtendimento . ')"><i class="fa fa-edit"></i> Chamar Painel</button></span>';


            if ($value->dataEncerramento == NULL) {
                $dataEncerramento = '-';
            } else {
                $dataEncerramento = date('d/m/Y H:i', strtotime($value->dataEncerramento));
            }


            $statusAtendimento = '<span class="right badge badge-warning">' . $value->descricaoStatusAtendimento . '</span>';



            if ($value->dataEncerramento == NULL) {
                $dataFim = date('Y-m-d H:i');
            } else {
                $dataFim = $value->dataEncerramento;
            }



            if ($value->dataEncerramento == $value->dataInicio and $value->codStatus == 0) {
                //APENAS PARA OS CASOS MIGRADOS DO SISTEMA LEGADO E QUE AINDA NÃO ESTAO CLASSIFICADOS
                $dataFim = date('Y-m-d H:i');
            }



            $tempo =  intervaloTempoAtendimento($value->dataInicio, $dataFim);
            $tempoAtendimento = '';
            if ($value->codStatus !== '0') {
                $tempoAtendimento = '<span class="right badge badge-info">' . $tempo . '</span>';
            }

            if ($value->codLocalAtendimento == 0 and $value->codTipoAtendimento == 1) {
                $descricaoLocalAtendimento =  "PAMO (Acolhimento)";
            } else {
                $descricaoLocalAtendimento =  $value->descricaoDepartamento . " (" . $value->descricaoLocalAtendimento . ")";
            }

            $problema = "";
            if ($value->hda !== NULL and $value->hda !== "" and $value->hda !== " ") {

                $problema .= '<div><b>HDA:</b> ' . mb_substr(strip_tags($value->hda), 0, 150) . '...</div>';
            }

            if ($value->queixaPrincipal !== NULL and $value->queixaPrincipal !== "" and $value->queixaPrincipal !== " ") {

                $problema .= '<div><b>QP:</b> ' . mb_substr(strip_tags($value->queixaPrincipal), 0, 150) . '...</div>';
            }
            if ($value->hmp !== NULL and $value->hmp !== "" and $value->hmp !== " ") {

                $problema .= '<div><b>HPP:</b> ' . mb_substr(strip_tags($value->hmp), 0, 150) . '...</div>';
            }


            //LISTAR CONDUTAS

            $condutas = $this->AtendimentosModel->dadosCondutasAtendimento($value->codAtendimento);
            $htmlCondutas = "";
            if (!empty($condutas)) {

                $htmlCondutas = "";
                $x = 0;
                foreach ($condutas as $conduta) {
                    $x++;

                    $replace = array('<p>', '</p>');
                    $conteudoConduta = str_replace($replace, '', $conduta->conteudoConduta);

                    $htmlCondutas .= '<div>
                                         ' . $x . ') ' . $conteudoConduta . ' - ' . date('d/m/Y H:i', strtotime($conduta->dataCriacao)) . ' - ' . $conduta->nomeEspecialista . '
                                    </div>';
                }
            } else {
                $htmlCondutas = '<div>Não possui</div>';
            }



            //LISTAR SOLICITAÇÃO DE EXAMES/PROCEDIMENTOS

            $listaProcedimentosSolicitados = '';
 /*
            $procedimentos = $this->AtendimentosModel->getAllSolicitacaoProcedimentos($value->codAtendimento);

            $x = 0;
            $listaProcedimentosSolicitados .=  '<div class="row">';
            foreach ($procedimentos as $procedimento) {
                $x++;

                $checked = '<img style="width:30px" src="./imagens/check.png">';


                $listaProcedimentosSolicitados .=  ' 
                <div class="col-md-12">
               ' . $checked . $procedimento->descricao . '
                </div>
                
                ';
            }
            $listaProcedimentosSolicitados .=  "</div>";



           
                $value->codAtendimento,
                $value->descricaoEspecialidade,
                $value->nomeEspecialista,
                $descricaoLocalAtendimento,
                $value->descricaoTipoAtendimento,
                $problema,
                date('d/m/Y H:i', strtotime($value->dataInicio)),
                $dataEncerramento,
                $statusAtendimento . $tempoAtendimento . $btnEditar . $btnResumo,
                */
            $html .= '
            
            <div class="time-label">
                <span class="bg-red">' . date('d/m/Y', strtotime($value->dataInicio)) . '</span>
            </div>
            <div>
                    <i class="fas fa-user bg-green"></i>
                    <div class="timeline-item">
                        <span class="time"><i class="fas fa-clock"></i>' . date('H:i', strtotime($value->dataInicio)) . '</span>
                        <h3 class="timeline-header"><b>' . $value->nomeEspecialista . ' - ' . $value->descricaoEspecialidade . '</b></h3>

                        <div class="timeline-body">
                            
                        

                        <div class="row">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header bg-secondary border-0">
                                        <div class="d-flex justify-content-between">
                                            <h3 class="card-title">Dados Atendimento</h3>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                    
                                        <div class="row">
                                             Atendimento: ' . $value->codAtendimento . '
                                        </div>
                                        <div class="row">
                                             Paciente: ' . $value->nomePaciente . ' (' . $value->idade . ')
                                        </div>
                                        <div class="row">
                                             Local Atendimento: ' . $descricaoLocalAtendimento . '
                                        </div>
                                        <div class="row">
                                             Especialidade: ' . $value->descricaoEspecialidade . '
                                        </div>
                                        <div class="row">
                                             Tipo Atendimento: ' . $value->descricaoTipoAtendimento . '
                                        </div>
                                        <div class="row">
                                             Encerramento: ' . $dataEncerramento . '
                                        </div>
                                        <div class="row">
                                             Status: ' . $statusAtendimento . '
                                        </div>
                                        
                                        <div class="row">
                                             Tempo de Atendimento: ' . $tempoAtendimento . '
                                        </div>
                                        <div class="row">
                                            ' . $btnEditar . '
                                            ' . $btnResumo . '
                                            ' . $btnChamarPainel . '
                                        </div>
                                    
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-8 col-md-8">                               
                                <div class="card card-primary card-tabs">
                                    <div class="card-header p-0 pt-1">
                                        <ul class="nav nav-tabs" id="abaResumo-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="abaResumo-anamnese-tab" data-toggle="pill" href="#abaResumo-anamnese" role="tab" aria-controls="abaResumo-anamnese" aria-selected="true">Anamnese</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="abaResumo-exameFisico-tab" data-toggle="pill" href="#abaResumo-exameFisico" role="tab" aria-controls="abaResumo-exameFisico" aria-selected="false">Exame Físico</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="abaResumo-hdConduta-tab" data-toggle="pill" href="#abaResumo-hdConduta" role="tab" aria-controls="abaResumo-hdConduta" aria-selected="false">HD/Conduta</a>
                                        </li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content" id="abaResumo-tabContent">
                                        <div class="tab-pane fade show active" id="abaResumo-anamnese" role="tabpanel" aria-labelledby="abaResumo-anamnese-tab">
                                        ' . $problema . '
                                        </div>
                                        <div class="tab-pane fade" id="abaResumo-exameFisico" role="tabpanel" aria-labelledby="abaResumo-exameFisico-tab">
                                        ' . $value->exameFisico . '
                                        </div>
                                        <div class="tab-pane fade" id="abaResumo-hdConduta" role="tabpanel" aria-labelledby="abaResumo-hdConduta-tab">
                                            <div style="margin-top:10px"><b>HD:</b> ' . $value->hipoteseDiagnostica . '</div>
                                            <div style="margin-top:10px"><b>CONDUTA(S):</b> ' . $htmlCondutas . '</div>
                                            <div style="margin-top:10px"><b>SOLCITAÇÃO DE EXAMES/PROCEDIMENTOS:</b></div>
                                            <div>'.$listaProcedimentosSolicitados.'</div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            
                            </div>
                        </div>

                        </div>
                        
                    </div>
                </div>

                ';
        }


        $html .= '
            <div>
                <i class="fas fa-clock bg-gray"></i>
            </div>
        </div>';
        $response['html'] = $html;
        sleep(1);
        return $this->response->setJSON($response);
    }





    public function sensoInternadosFarmacia()
    {


        $response = array();


        $html = '

		<style>
		table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #b8b8b863;
        }

		tr.border_bottom td {
			border-bottom: 1px solid black;
		  }
		</style>
		';

        $dataCriacao = $data['dataCriacao'] = session()->cidade . '-' . session()->uf . ', ' . date('d', strtotime(date('Y-m-d'))) . ' de ' . nomeMesPorExtenso(date('m', strtotime(date('Y-m-d')))) . ' de ' . date('Y', strtotime(date('Y-m-d'))) . ' às ' . date('H:i');
        $cabecalho = session()->cabecalhoPrescricao;
        $result = $this->AtendimentosModel->sensoInternadosFarmacia();

        $codDepartamento = session()->filtroDispensacao["codDepartamento"];
        $codCategoria = session()->filtroDispensacao["codCategoria"];

        if ($codDepartamento !== 0 and $codDepartamento !== NULL and $codDepartamento !== '' and $codDepartamento !== ' ') {
            $nomeDepartamento = $this->AtendimentosModel->pegaNomeDepartamento($codDepartamento)->abreviacaoDepartamento;
        } else {
            $nomeDepartamento = 'TODOS';
        }
        $nomeTipoDispensacao = '';
        if ($codCategoria !== 0 and $codCategoria !== NULL and $codCategoria !== '' and $codCategoria !== ' ') {

            if ($codCategoria == 1) {
                $nomeTipoDispensacao = ' (Medicamentos)';
            }

            if ($codCategoria == 2) {
                $nomeTipoDispensacao = ' (Materiais)';
            }
        }



        $html .= '

		<div style="margin-bottom:10px" class="row">
			<div class="col-md-12">
				' . $cabecalho . '
			</div>
		</div>

		<div style="font-size:30px;font-weight: bold;margin-left:10px;margin-bottom:10px" class="row text-center">CENSO DE PACIENTES INTERNADOS</div>
        <div style="font-size:50px;font-weight: bold;margin-right:20px;" class="text-right">' . $nomeDepartamento . '<span style="font-size:20px !important">' . $nomeTipoDispensacao . '</span></div>';

        $html .= '<div style="margin-left:15px;">' . $dataCriacao . '</div>';
        $html .= '<table style="width:100%;font-size:12px">';

        $html .= '
		<div style="margin-top:50px;" class="row">
					<div class="col-md-12">
					<div class="text-right">Emitido por ' . session()->nomeExibicao . ' (CPF:' . substr(session()->cpf, 0, -6) . '*****'  . ')</div>
					<div class="text-right">Em ' . date('d/m/Y H:i') . '</div>
					<div class="text-right">Sistema SANDRA | ' . base_url() . '</div>
					</div>
		</div>';

        $html .= '
		<tr class="border_bottom">
		<th>UNIDADE</th>
		<th>LOCAL</th>
		<th>PACIENTE</th>
		<th>IDADE</th>
		<th>PRESCRIÇÃO HOJE</th>
		<th>STATUS</th>
		<th>OBSERVAÇÕES</th>
		</tr>
		';

        foreach ($result as $value) {


            //verifica se tem presrição hoje

            $existePrescricao = $this->AtendimentosModel->verificaExistePrescricao($value->codAtendimento);
            //$verificaPrescricaoDispensada = $this->ItensFarmaciaModel->verificaPrescricaoDispensada($value->codAtendimento);
            $statusGeral = '';
            $observacao = '';

            if ($existePrescricao !== NULL) {
                $temPrescricao = 'Sim';

                if ($existePrescricao->codStatus >= 2) {
                    $statusGeral = $existePrescricao->descricaoStatus;
                } else {
                    $statusGeral = 'Não assinada';
                    $observacao = $existePrescricao->nomeExibicao . '222 criou a prescrição, porém não assinou';
                }
            } else {
                $temPrescricao = 'Não';
            }





            /*
			if ($verificaPrescricaoDispensada !== NULL) {
				$prescricaoDispensada = 'Sim';
			} else {
				$prescricaoDispensada = 'Não';
			}
			*/

            $html .= '<tr class="border_bottom">';
            $html .= '<td style="width:5px">' . $value->abreviacaoDepartamento . '</td>';
            $html .= '<td style="width:120px">' . $value->descricaoLocalAtendimento . '</td>';
            $html .= '<td style="width:300px">' . $value->nomeCompleto . '</td>';
            $html .= '<td style="width:10px">' . $value->idade . '</td>';
            $html .= '<td style="width:10px">' . $temPrescricao . '</td>';
            $html .= '<td style="width:100px">' . $statusGeral . '</td>';
            $html .= '<td style="width:500px">' . $observacao . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';


        $response['html'] = $html;
        return $this->response->setJSON($response);
        //print $response['html'];

    }

    public function sensoInternadosServicoSocial()
    {


        $response = array();


        $html = '

		<style>
		table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #b8b8b863;
        }

		tr.border_bottom td {
			border-bottom: 1px solid black;
		  }
		</style>
		';

        $dataCriacao = $data['dataCriacao'] = session()->cidade . '-' . session()->uf . ', ' . date('d', strtotime(date('Y-m-d'))) . ' de ' . nomeMesPorExtenso(date('m', strtotime(date('Y-m-d')))) . ' de ' . date('Y', strtotime(date('Y-m-d'))) . ' às ' . date('H:i');
        $cabecalho = session()->cabecalhoPrescricao;
        $result = $this->AtendimentosModel->sensoInternadosServicoSocial();
        $html .= '

		<div style="margin-bottom:10px" class="row">
			<div class="col-md-12">
				' . $cabecalho . '
			</div>
		</div>

		<div style="font-size:30px;font-weight: bold;margin-left:10px;margin-bottom:10px" class="row text-center">CENSO DE PACIENTES INTERNADOS</div>';

        $html .= '<div style="margin-left:15px;">' . $dataCriacao . '</div>';
        $html .= '<table style="width:100%;font-size:12px">';

        $html .= '
		<div style="margin-top:50px;" class="row">
					<div class="col-md-12">
					<div class="text-right">Emitido por ' . session()->nomeExibicao . ' (CPF:' . substr(session()->cpf, 0, -6) . '*****'  . ')</div>
					<div class="text-right">Em ' . date('d/m/Y H:i') . '</div>
					<div class="text-right">Sistema SANDRA | ' . base_url() . '</div>
					</div>
		</div>';

        $html .= '
		<tr class="border_bottom">
		<th>UNIDADE</th>
		<th>LOCAL</th>
		<th>PACIENTE</th>
		<th>POSTO/GRAD</th>
		<th>SIT</th>
		<th>IDADE</th>
		<th>Nº PLANO</th>
		<th>CONTATOS</th>
		</tr>
		';

        foreach ($result as $value) {

            //outrosContatos

            $todosContatos = $value->celular . " | ";

            $outrosContatosPaciente = $this->AgendamentosModel->outrosContatosPorPaciente($value->codPaciente);

            foreach ($outrosContatosPaciente as $outrocontato) {
                $todosContatos .= $outrocontato->numeroContato . " | ";
            }

            $todosContatos = rtrim($todosContatos, "| ");




            $html .= '<tr class="border_bottom">';
            $html .= '<td style="width:5px">' . $value->abreviacaoDepartamento . '</td>';
            $html .= '<td style="width:100px">' . $value->descricaoLocalAtendimento . '</td>';
            $html .= '<td style="width:200px">' . $value->nomeCompleto . '</td>';
            $html .= '<td style="width:10px">' . $value->siglaCargo . '</td>';
            $html .= '<td style="width:10px">' . $value->siglaTipoBeneficiario . '</td>';
            $html .= '<td style="width:5px">' . $value->idade . '</td>';
            $html .= '<td style="width:10px">' . $value->codPlano . '</td>';
            $html .= '<td style="width:300px">' .  $todosContatos . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';


        $response['html'] = $html;
        return $this->response->setJSON($response);
        //print $response['html'];

    }



    public function filtrarEspecialidadeFiltroProntuario()
    {

        if ($this->request->getPost('codEspecialidadeFiltroProntuario')  !== NULL and $this->request->getPost('codEspecialidadeFiltroProntuario')  < 1000) {
            $codEspecialidadeFiltroProntuario = $this->request->getPost('codEspecialidadeFiltroProntuario');
        } else {
            $codEspecialidadeFiltroProntuario = NULL;
        }

        session()->set('filtroEspecialidadeFiltroProntuario', $codEspecialidadeFiltroProntuario);

        $response = array();

        $response['success'] = true;
        $response['csrf_hash'] = csrf_hash();
        return $this->response->setJSON($response);
    }


    public function limpaFiltroProntuario()
    {

        session()->set('filtroEspecialidadeFiltroProntuario', null);

        session()->set('filtroTipoAtendimentoFiltroProntuario', null);

        $response = array();

        $response['success'] = true;
        return $this->response->setJSON($response);
    }



    public function filtrarTipoAtendimentoFiltroProntuario()
    {

        if ($this->request->getPost('codTipoAtendimentoFiltroProntuario')  !== NULL and $this->request->getPost('codTipoAtendimentoFiltroProntuario') < 1000) {
            $codTipoAtendimentoFiltroProntuario = $this->request->getPost('codTipoAtendimentoFiltroProntuario');
        } else {
            $codTipoAtendimentoFiltroProntuario = NULL;
        }

        session()->set('filtroTipoAtendimentoFiltroProntuario', $codTipoAtendimentoFiltroProntuario);

        $response = array();

        $response['success'] = true;
        $response['csrf_hash'] = csrf_hash();
        return $this->response->setJSON($response);
    }


    public function listaDropDownTiposAtendimentos()
    {

        $result = $this->AtendimentosModel->listaDropDownTiposAtendimentos();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function filtrarUnidadeInternacao()
    {
        $response = array();
        $unidadeInternacao = $this->request->getPost('unidadeInternacao');

        session()->set('unidadeInternacao', $unidadeInternacao);

        sleep(2);
        $response['success'] = true;
        $response['csrf_hash'] = csrf_hash();
        $response['messages'] = session()->unidadeInternacao;

        return $this->response->setJSON($response);
    }



    public function imprimirEtiquetaEmergencia()
    {
        $response = array();
        $codAtendimento = $this->request->getPost('codAtendimento');


        $dadosEtiqueta = $this->AtendimentosModel->dadosEtiquetaAtendimento($codAtendimento);

        $html = '';
        $html .= '
        <div style="margin-left:90px;margin-top:40px;font-size:12px;font-weight:bold;font-family:serif" class="row">
            <div class="row">
                <div class="row">
                Nº Atendimento: ' . $dadosEtiqueta->codAtendimento . ' | Prontuário: ' . $dadosEtiqueta->codProntuario . ' | Nome: ' . $dadosEtiqueta->nomeCompleto . ' |  Idade: ' . $dadosEtiqueta->idade . '
                </div>



                <div style="argin-top:10px;" class="row">
                Sexo: ' . $dadosEtiqueta->sexo . '

                | CodPlano: ' . $dadosEtiqueta->codPlano . ' | Nome Mãe: ' . $dadosEtiqueta->nomeMae . '

                </div>

            </div>


        ';




        $response['success'] = true;
        $response['html'] = $html;
        $response['messages'] = session()->unidadeInternacao;

        return $this->response->setJSON($response);
    }


    public function transferirLeitoAgora()
    {


        $codAtendimento = $this->request->getPost('codAtendimento');
        $codLocalAtendimento = $this->request->getPost('codLocalAtendimento');

        $dadosAtendimento = $this->AtendimentosModel->dadosAtendimento($codAtendimento);
        $tipoDepartamento = $this->AtendimentoslocaisModel->pegaPorCodLocalAtendimento($codLocalAtendimento);

        $fields['codAtendimento'] = $codAtendimento;
        $fields['codLocalAtendimento'] = $codLocalAtendimento;


        if ($tipoDepartamento->codTipoDepartamento == 6) {//codTipoDepartamento == 6 == Internações OCS
            $fields['codTipoAtendimento'] = 8; //Internação
            $fields['codStatus'] = 7; // 7 == Internado
        }



        if ($tipoDepartamento->codTipoDepartamento == 2) { //codTipoDepartamento == 2 == Internações or //codTipoDepartamento == 6 == Internações OCSsss
            $fields['codTipoAtendimento'] = 4; //Internação
            $fields['codStatus'] = 7; // 7 == Internado
        }

        if ($tipoDepartamento->codTipoDepartamento == 3) { //codTipoDepartamento == 3 == Atendimentos
            $fields['codTipoAtendimento'] = 1;
        }

        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['dataEncerramento'] = NULL; // Atendimento aberto
        if ($this->validation->check($fields['codAtendimento'], 'required|numeric')) {
            if ($this->AtendimentosModel->update($fields['codAtendimento'], $fields)) {


                if ($dadosAtendimento->codLocalAtendimento !== 0 and $dadosAtendimento->codLocalAtendimento !== NULL and $dadosAtendimento->codLocalAtendimento !== "" and $dadosAtendimento->codLocalAtendimento !== " ") {
                    //LIBERA LEITO CASO ESTIVESSE OCUPADO

                    $this->AtendimentoslocaisModel->liberaLeito($fields['codAtendimento']);
                }

                //DEFINI OCUPAÇÃO DO LEITO
                $this->AtendimentoslocaisModel->defineLocalAtendimento($codLocalAtendimento, $codAtendimento);




                $response['success'] = true;
                $response['messages'] = 'Paciente transferido com sucesso';
            } else {
                $response['success'] = false;
                $response['messages'] = 'Falha ao alterar leito';
            }
        } else {

            $response['success'] = false;
            $response['messages'] = 'Falha ao alterar leito';
        }

        sleep(3);
        return $this->response->setJSON($response);
    }

    public function situacaoLeitos()
    {
        $response = array();

        $data['data'] = array();

        if (session()->unidadeInternacao == NULL) {
            $codDepartamento = 0;
        } else {
            $codDepartamento =  session()->unidadeInternacao;
        }

        $codAtendimento = $this->request->getPost('codAtendimento');

        //ROTINA DE AJUSTE LEITOS

        $listaLeitosInternacao = $this->AtendimentosModel->listaLeitosInternacao();

        foreach ($listaLeitosInternacao as $leito) {

            if ($leito->codLocalAtendimento !== $leito->codLocalAtendimentoPrincipal and $leito->codLocalAtendimentoPrincipal !== NULL) {

                //LIMPA LEITO
                $this->AtendimentoslocaisModel->liberaLeito($leito->codAtendimento);
            }
        }



        $dataAtendimento = $this->AtendimentosModel->dadosAtendimento($codAtendimento);
        // $result = $this->AtendimentosModel->situacaoLeitos();
        $result = $this->AtendimentosModel->situacaoLeitosPorDepartamento($codDepartamento);

        foreach ($result as $key => $value) {

            $ops = '<div class="btn-group">';
            if ($value->codSituacaoLocalAtendimento !== '2' and $value->nomeCompleto == NULL) {
                $ops .= '	<button type="button" class="btn btn-sm btn-success"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="transferirLeitoAgora(' . $codAtendimento . ',' . $value->codLocalAtendimento  . ',\'' . $dataAtendimento->nomeCompleto . '\')"><i class="fa fa-edit"></i>Transferir Paciente</button>';
            }
            $ops .= '</div>';


            $tempo =  intervaloTempoAtendimento($value->dataCriacao, date('Y-m-d H:i'));


            if ($value->codAtendimento !== NULL) {
                $situacaoLocal = 'OCUPADO';
            } else {
                $situacaoLocal = 'LIVRE';
            }
            if ($value->codSituacaoLocalAtendimento == 2) {
                $situacaoLocal = 'EM MANUTENÇÃO';
            }



            if ($value->nomeCompleto !== NULL) {

                $dadosAlta = $this->AtendimentosModel->previsaoAlta($value->codAtendimento);

                if ($dadosAlta !== NULL) {
                    $previsao =  previsaoAlta($dadosAlta->dataPrevAlta, $dadosAlta->dataEncerramento, $dadosAlta->indeterminado);
                    $previsaoAlta = '<div class="right badge badge-warning">Prev. Alta: ' . $previsao['dataPrevAlta'] . ' ' . $previsao['faltam'] . '<div>';
                } else {
                    $previsao = NULL;
                    $previsao['dataPrevAlta'] = 'Falta informar';
                    $previsaoAlta = '<div class="right badge badge-danger">Prev. Alta: ' . $previsao['dataPrevAlta'] . ' ' . $previsao['faltam'] . '<div>';
                }
            } else {
                $previsaoAlta = NULL;
            }


            $data['data'][$key] = array(
                mb_strtoupper($value->descricaoLocalAtendimento, 'utf-8'),
                $value->nomeCompleto,
                $value->idade,
                $value->descricaoDepartamento,
                $situacaoLocal,
                '<div>' . $tempo . '</div>' . $previsaoAlta,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }
    public function situacaoTodosLeitos()
    {
        $response = array();

        $data['data'] = array();

        if (session()->unidadeInternacao == NULL) {
            $codDepartamento = 0;
        } else {
            $codDepartamento =  session()->unidadeInternacao;
        }


        $result = $this->AtendimentosModel->situacaoTodosLeitos();

        foreach ($result as $key => $value) {

            $ops = '<div class="btn-group">';
            if ($value->codSituacaoLocalAtendimento !== '2' and $value->nomeCompleto == NULL) {
                $ops .= '	<button type="button" class="btn btn-sm btn-success"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="transferirLeitoAgora(' . $value->codAtendimento . ',' . $value->codLocalAtendimento  . ',\'' . $value->nomeCompleto . '\')"><i class="fa fa-edit"></i>Transferir Paciente</button>';
            }
            $ops .= '</div>';


            $tempo =  intervaloTempoAtendimento($value->dataCriacao, date('Y-m-d H:i'));


            if ($value->codAtendimento !== NULL) {
                $situacaoLocal = 'OCUPADO';
            } else {
                $situacaoLocal = 'LIVRE';
            }
            if ($value->codSituacaoLocalAtendimento == 2) {
                $situacaoLocal = 'EM MANUTENÇÃO';
            }



            $data['data'][$key] = array(
                $value->descricaoDepartamento,
                mb_strtoupper($value->descricaoLocalAtendimento, 'utf-8'),
                $value->nomeCompleto,
                $value->idade,
                $situacaoLocal,
                $tempo,
            );
        }

        return $this->response->setJSON($data);
    }
    public function getAll()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->AtendimentosModel->pegaTudo();

        foreach ($result as $key => $value) {

            $ops = '<div class="btn-group">';
            $ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editatendimentos(' . $value->codAtendimento . ')"><i class="fa fa-edit"></i></button>';
            $ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeatendimentos(' . $value->codAtendimento . ')"><i class="fa fa-trash"></i></button>';
            $ops .= '</div>';

            $data['data'][$key] = array(
                $value->codAtendimento,
                $value->codOrganizacao,
                $value->codPaciente,
                $value->codLocalAtendimento,
                $value->codEspecialista,
                $value->codEspecialidade,
                $value->codStatus,
                $value->dataCriacao,
                $value->dataAtualizacao,
                $value->dataInicio,
                $value->dataEncerramento,
                $value->codTipoAtendimento,
                $value->codAutor,

                $ops,
            );
        }

        return $this->response->setJSON($data);
    }
    public function modificarStatusAgora()
    {

        $response = array();


        //STATUS DE ENCERRAMENTO
        $arrayStatusEncerraAtendimento = array(2, 3, 8, 9, 11);



        $fields['codAtendimento'] = $this->request->getPost('codAtendimento');
        $fields['codStatus'] = $this->request->getPost('codStatus');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');

        $dadosAtendimento = $this->AtendimentosModel->dadosAtendimento($fields['codAtendimento']);
        $descricaoStatusAtendimento = $this->AtendimentosModel->getStatusAtendimento($fields['codStatus'])->descricaoStatusAtendimento;



        //VERIFICA SE REABRIR
        if (!in_array($fields['codStatus'], $arrayStatusEncerraAtendimento)) {
            $fields['codStatus'] = $fields['codStatus'];
            $fields['dataEncerramento'] = NULL;


            if ($this->validation->check($fields['codAtendimento'], 'required|numeric')) {

                if ($this->AtendimentosModel->update($fields['codAtendimento'], $fields)) {
                    $response['messages'] = 'Atendimento reaberto!';
                    if ($fields['codStatus'] == -1) {
                        $response['messages'] = 'Atendimento reaberto!';
                        $fields['codStatus'] = 7;
                    }

                    if ($fields['codStatus'] == 0) {
                        $response['messages'] = 'Aguardando Classificação';
                    }


                    if ($fields['codStatus'] == 1) {
                        $response['messages'] = 'Em Atendimento';
                    }

                    if ($fields['codStatus'] == 4) {
                        $response['messages'] = 'Observação  - Aguardando Resultado Exame';
                    }
                    if ($fields['codStatus'] == 5) {
                        $response['messages'] = 'Observação  - Aguardando Melhora';
                    }
                    if ($fields['codStatus'] == 6) {
                        $response['messages'] = 'Observação  - Pós Cirurgia';
                    }
                    if ($fields['codStatus'] == 16) {
                        $response['messages'] = 'Observação  - Aguardando Leito';
                    }


                    $response['success'] = true;
                    $response['descricaoStatusAtendimento'] = 'Em Atendimento';

                    return $this->response->setJSON($response);
                }
            }
        }





        //VERIFICA SE NÃO EXISTE HDA OU DIAGNÓSTICO

        if (in_array($fields['codStatus'], $arrayStatusEncerraAtendimento)) {
            if ($dadosAtendimento->hda == NULL or $dadosAtendimento->hda == "" or $dadosAtendimento->hda == " " or $dadosAtendimento->codCid == NULL) {
                $response['success'] = false;
                $response['descricaoStatusAtendimento'] = $descricaoStatusAtendimento;
                $response['messages'] = 'Falta informar HDA e/ou Diagnóstico!!! Não é possível encerrar atendimento';

                return $this->response->setJSON($response);
            }
        }

        //NÃO DEIXA ATUALIZAR SE CÓDIGO FOR NULO OU VAZIO
        if ($this->validation->check($fields['codAtendimento'], 'required|numeric')) {

            //LIBERA LEITO E DEFINE DATA ENCERRAMENTO
            if (in_array($fields['codStatus'], $arrayStatusEncerraAtendimento)) {
                $fields['dataEncerramento'] = date('Y-m-d H:i');
            }
            if ($this->AtendimentosModel->update($fields['codAtendimento'], $fields)) {



                if ($dadosAtendimento->codTipoAtendimento !== 2 and  $dadosAtendimento->codTipoAtendimento !== 3 and $dadosAtendimento->codTipoAtendimento !== 7) {

                    //LIBERAR LEITO
                    $this->AtendimentoslocaisModel->liberaLeito($fields['codAtendimento']);
                }


                $response['success'] = true;
                $response['descricaoStatusAtendimento'] = $descricaoStatusAtendimento;
                $response['messages'] = 'Status alterado com sucesso';
            } else {
                $response['success'] = false;
                $response['messages'] = 'Falha ao alterar status';
            }
        } else {
            $response['success'] = false;
            $response['messages'] = 'Erro na operação!';
            return $this->response->setJSON($response);
        }



        return $this->response->setJSON($response);
    }

    public function reabrirAtendimento()
    {

        $response = array();

        $fields['codAtendimento'] = $this->request->getPost('codAtendimento');

        $fields['dataAtualizacao'] = date('Y-m-d H:i');

        if ($this->validation->check($fields['codAtendimento'], 'required|numeric')) {
            $dadosAtendimento = $this->AtendimentosModel->dadosAtendimento($fields['codAtendimento']);

            if ($dadosAtendimento->codTipoAtendimento == 4) {
                $dadosLeito =  $this->AtendimentosModel->dadosLeito($dadosAtendimento->codLocalAtendimento);

                if ($dadosLeito !== NULL) {

                    if ($dadosLeito->codAtendimento == NULL) {
                        //Muda status
                        $fields['codStatus'] = 7;
                        $fields['dataEncerramento'] = NULL;

                        if ($this->AtendimentosModel->update($fields['codAtendimento'], $fields)) {
                        }

                        //seta novamente ocupando o leito
                        if ($dadosAtendimento->codLocalAtendimento !== NULL and $dadosAtendimento->codLocalAtendimento !== '') {
                            $this->AtendimentosModel->setaLeitoAtendimento($dadosAtendimento->codLocalAtendimento, $fields['codAtendimento']);
                        }

                        $response['success'] = true;
                        $response['codPaciente'] = $dadosAtendimento->codPaciente;
                        $response['messages'] = 'leito recupado para este paciente';
                        return $this->response->setJSON($response);
                    } else {

                        if ((int)$dadosLeito->codAtendimento !== (int)$fields['codAtendimento']) {
                            //leito já esta ocupado, ve terá que colocar este paciente em outro leito

                            $response['success'] = 'ocupado';
                            $response['codPaciente'] = $dadosAtendimento->codPaciente;
                            $response['messages1'] = 'Leito ocupado por outro paciente';
                            $response['messages2'] = 'O leito ' . $dadosLeito->descricaoLocalAtendimento . ' já encontra-se ocupado. Deseja escolher outro?';
                            return $this->response->setJSON($response);
                        } else {
                        }
                    }
                }
            } else {

                $fields['codStatus'] = 1;
                if ($this->AtendimentosModel->update($fields['codAtendimento'], $fields)) {
                }

                $response['success'] = true;
                $response['codPaciente'] = $dadosAtendimento->codPaciente;
                $response['messages'] = 'Atendimento reaberto';
                return $this->response->setJSON($response);
            }
        } else {
            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        }
    }

    public function statusEncerramentoAtendimentAgora()
    {

        $response = array();

        $fields['codAtendimento'] = $this->request->getPost('codAtendimento');
        $fields['codStatus'] = $this->request->getPost('codStatus');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');


        $fields['dataEncerramento'] = date('Y-m-d H:i');

        $dadosAtendimento = $this->AtendimentosModel->dadosAtendimento($fields['codAtendimento']);

        //VERIFICA SE NÃO EXISTE HDA OU DIAGNÓSTICO

        if ($dadosAtendimento->hda == NULL or $dadosAtendimento->hda == "" or $dadosAtendimento->hda == " " or $dadosAtendimento->codCid == NULL) {
            $response['success'] = false;
            $response['messages'] = 'Falta informar HDA e/ou Diagnóstico!!! Não é possível encerrar atendimento';

            return $this->response->setJSON($response);
        }


        //NÃO DEIXA ATUALIZAR SE CÓDIGO FOR NULO OU VAZIO
        if ($this->validation->check($fields['codAtendimento'], 'required|numeric')) {
            if ($this->AtendimentosModel->update($fields['codAtendimento'], $fields)) {

                if ($dadosAtendimento->codTipoAtendimento !== 2 and  $dadosAtendimento->codTipoAtendimento !== 3 and $dadosAtendimento->codTipoAtendimento !== 7) {

                    //LIBERAR LEITO
                    $this->AtendimentoslocaisModel->liberaLeito($fields['codAtendimento']);
                }




                //VERIFICA SE EXISTE AGENDAMENTO ABERTO PARA ENCERRRAR

                if ($dadosAtendimento->codTipoAtendimento == 2) {
                    $fieldsAgendamento['codStatus'] = 2;
                    $fieldsAgendamento['dataAtualizacao'] = date('Y-m-d H:i');
                    $fieldsAgendamento['encerramentoAtendimento'] = date('Y-m-d H:i');

                    $dadosAgendamento = $this->AgendamentosModel->pegaAgendamentoPorEspecialidadePacienteHoje($dadosAtendimento->codPaciente, $dadosAtendimento->codEspecialista);

                    if ($dadosAgendamento !== NULL) {

                        if ($this->validation->check($dadosAgendamento->codAgendamento, 'required|numeric')) {
                            if ($this->AgendamentosModel->update($dadosAgendamento->codAgendamento, $fieldsAgendamento)) {
                            }
                        } else {
                            $response['success'] = false;
                            $response['messages'] = $this->validation->listErrors();
                        }
                    }
                }



                $response['success'] = true;
                $response['messages'] = 'Atendimento Encerrado';
            } else {
                $response['success'] = false;
                $response['messages'] = 'Falha ao alterar status';
            }
        } else {
            $response['success'] = false;
            $response['messages'] = 'Erro na operação!';
            return $this->response->setJSON($response);
        }



        return $this->response->setJSON($response);
    }


    public function mudaStatusAtendimento()
    {

        $response = array();

        $fields['codAtendimento'] = $this->request->getPost('codAtendimento');

        if ($this->validation->check($fields['codAtendimento'], 'required|numeric')) {


            $fields['codStatus'] = $this->request->getPost('codStatus');
            $fields['dataAtualizacao'] = date('Y-m-d H:i');

            $arrayFechamento = array(2, 3, 8, 9, 11);

            if (in_array($fields['codStatus'], $arrayFechamento)) {
                $fields['dataEncerramento'] = date('Y-m-d H:i');
            } else {
                $fields['dataEncerramento'] = null;
            }

            $fields['codAutor'] = session()->codPessoa;


            //NÃO DEIXA ATUALIZAR SE CÓDIGO FOR NULO OU VAZIO
            if ($this->validation->check($fields['codAtendimento'], 'required|numeric')) {

                if ($this->AtendimentosModel->update($fields['codAtendimento'], $fields)) {

                    $response['success'] = true;
                    $response['messages'] = 'Status alterado com sucesso';
                } else {
                    $response['success'] = false;
                    $response['messages'] = 'Falha ao alterar status';
                }
            } else {
                $response['success'] = false;
                $response['messages'] = 'Erro na operação!';
                return $this->response->setJSON($response);
            }


            return $this->response->setJSON($response);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function pegaTempoAtendimento()
    {
        $response = array();

        $id = $this->request->getPost('codAtendimento');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->AtendimentosModel->pegaPorCodigo($id);

            if ($data->dataEncerramento == NULL) {
                $dataFim = null;
            } else {
                $dataFim = $data->dataEncerramento;
            }

            if ($data->dataEncerramento == $data->dataInicio and $data->codStatus == 0) {
                //APENAS PARA OS CASOS MIGRADOS DO SISTEMA LEGADO E QUE AINDA NÃO ESTAO CLASSIFICADOS
                $dataFim = date('Y-m-d');
            }

            if ($tempo =  intervaloTempoAtendimento($data->dataInicio, $dataFim)) {
                $response['success'] = true;

                if ($dataFim == NULL) {
                    $response['dataEncerramento'] = null;
                    $response['ano'] = $tempo['unidadeAno'];
                    $response['mes'] = $tempo['unidadeMes'];
                    $response['dia'] = $tempo['unidadeDia'];
                    $response['hora'] = $tempo['unidadeHora'];
                    $response['minuto'] = $tempo['unidadeMinuto'];
                } else {
                    $response['dataEncerramento'] = $data->dataEncerramento;
                }

                $response['tempoAtendimento'] = $tempo;
            } else {
                $response['success'] = true;
                $response['tempoAtendimento'] = '';
            }

            return $this->response->setJSON($response);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function previsaoAlta()
    {
        $response = array();

        $codAtendimento = $this->request->getPost('codAtendimento');




        if ($this->validation->check($codAtendimento, 'required|numeric')) {

            $response['perguntar'] = 0;

            $data = $this->AtendimentosModel->previsaoAlta($codAtendimento);
            $verificaSeUnidadeInternacao = $this->AtendimentosModel->verificaSeUnidadeInternacao($codAtendimento);

            $codStatusEncerramento = array(2, 3, 8, 9, 11);



            if ($data->dataPrevAlta !== NULL and ($data->codTipoDepartamento == 2 or $data->codTipoDepartamento == 6)) {

                $previsao =  previsaoAlta($data->dataPrevAlta, $data->dataEncerramento, $data->indeterminado);


                $response['dataPrevAlta'] =  $previsao['dataPrevAlta'];
                $response['diasParaAlta'] =  $previsao['faltam'];
            } else {
                $response['dataPrevAlta'] = NULL;
                $response['diasParaAlta'] =  NULL;
                if (!in_array($data->codStatus, $codStatusEncerramento)  and ($data->codTipoDepartamento == 2 or $data->codTipoDepartamento == 6)) {
                    $response['perguntar'] = 1;
                }
            }

            $response['indeterminado'] = $data->indeterminado;

            $response['success'] = true;
            return $this->response->setJSON($response);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }



    public function pegaAlergias()
    {
        $response = array();

        $codAtendimento = $this->request->getPost('codAtendimento');




        if ($this->validation->check($codAtendimento, 'required|numeric')) {

            $data = $this->AtendimentosModel->pegaAlergias($codAtendimento);

            $listaAlergia = NULL;

            if ($data !== NULL) {

                foreach ($data as $alergia) {

                    $listaAlergia .= $alergia->descricaoAlergenico . ',';
                }

                $response['listaAlergia'] = $listaAlergia;
            } else {
                $response['listaAlergia'] = NULL;
            }


            $response['success'] = true;
            return $this->response->setJSON($response);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function pegaDiasAtendimento()
    {
        $response = array();

        $id = $this->request->getPost('codAtendimento');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->AtendimentosModel->pegaPorCodigo($id);

            if ($data->dataEncerramento == NULL) {
                $dataFim = date('Y-m-d H:i');
            } else {
                $dataFim = $data->dataEncerramento;
            }

            if ($dataFim == date('Y-m-d', strtotime($data->dataInicio))) {
                $dataFim = date('Y-m-d H:i');


                $tempo =  intervaloTempoAtendimento($data->dataInicio, $dataFim);
            } else {


                $tempo =  intervaloTempoAtendimento($data->dataInicio, $dataFim);
            }


            $response['tempoAtendimento'] = $tempo;


            $response['dataEncerramento'] = $data->dataEncerramento;
            $response['dataInicio'] = $data->dataInicio;
            $response['success'] = true;
            return $this->response->setJSON($response);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function dadosPaciente()
    {
        $response = array();

        $codPaciente = $this->request->getPost('codPaciente');

        if ($this->validation->check($codPaciente, 'required|numeric')) {

            $data = $this->PacientesModel->pegaPacientePorCodPaciente($codPaciente);
        }

        return $this->response->setJSON($data);
    }


    public function dadosAtendimentos()
    {
        $response = array();

        //VERIFICA  PERMISSÃO


        $acessoLiberado = 0;

        if (!empty(session()->minhasEspecialidades)) {
            $acessoLiberado = 1;
        }

        if (!empty(session()->minhasEspecialidades)) {
            $acessoLiberado = 1;
        }


        $perfisLiberados = array(2, 10, 16);
        if (!empty(session()->meusPerfis)) {


            foreach (session()->meusPerfis as $meuPerfil) {

                if (in_array($meuPerfil->codPerfil, $perfisLiberados)) {
                    $acessoLiberado = 1;
                }
            }
        }


        if ($acessoLiberado == 0) {
            $data['success'] = false;
            $data['messages'] = 'Apenas profissionais de saúde tem acesso a este recurso';

            return $this->response->setJSON($data);
        }




        $codPaciente = $this->request->getPost('codPaciente');

        $assinaturas = array();


        if ($this->validation->check($codPaciente, 'required|numeric')) {


            $listaAtendimentos = $this->AtendimentosModel->listaAtendimentosPorPaciente($codPaciente);


            $html = '';
            foreach ($listaAtendimentos as $atendimento) {

                $assinatura = array();

                $dataAtendimento = $this->AtendimentosModel->dadosAtendimentoCompleto($atendimento->codAtendimento);

                $assinatura['codCargo'] = $dataAtendimento->codCargo;
                $assinatura['siglaCargo'] = $dataAtendimento->siglaCargo;
                $assinatura['especialista'] = $dataAtendimento->nomeEspecialista;
                $assinatura['especialistaNomeCompleto'] = $dataAtendimento->nomeCompletoEspecialista;
                $assinatura['nomeConselho'] = $dataAtendimento->nomeConselho;
                $assinatura['siglaEstadoFederacao'] = $dataAtendimento->siglaEstadoFederacao;
                $assinatura['numeroInscricao'] = $dataAtendimento->numeroInscricao;;
                $assinatura['descricaoEspecialidade'] = $dataAtendimento->descricaoEspecialidade;


                array_push($assinaturas, $assinatura);

                $nrAtendimento = str_pad($dataAtendimento->codAtendimento, 8, '0', STR_PAD_LEFT);
                $ano = date('Y', strtotime($dataAtendimento->dataCriacao));


                $especialista =  $dataAtendimento->nomeEspecialista;
                $local =  $dataAtendimento->localAtendimento;
                $tipoAtendimento =  $dataAtendimento->descricaoTipoAtendimento;
                $hda =  $dataAtendimento->hda;

                $statusAtendimento =  $dataAtendimento->descricaoStatusAtendimento;



                if ($dataAtendimento->dataEncerramento == NULL) {
                    $periodoAtendimento = date('d/m/Y H:i', strtotime($dataAtendimento->dataInicio));
                } else {

                    if (date('d/m/Y', strtotime($dataAtendimento->dataInicio)) < date('d/m/Y', strtotime($dataAtendimento->dataEncerramento))) {
                        $periodoAtendimento = date('d/m/Y H:i', strtotime($dataAtendimento->dataInicio)) . ' a ' . $dataAtendimento = date('d/m/Y H:i', strtotime($dataAtendimento->dataEncerramento));
                    } else {
                        $periodoAtendimento = date('d/m/Y H:i', strtotime($dataAtendimento->dataInicio));
                    }
                }



                //DIAGNOSTICOS
                $diagnosticos = $this->AtendimentosModel->dadosDiagnosticosAtendimento($atendimento->codAtendimento);

                $listaDiagnosticos = "";
                foreach ($diagnosticos as $diagnostico) {
                    $listaDiagnosticos .= $diagnostico->cid . ",";
                }
                $listaDiagnosticos = rtrim($listaDiagnosticos, ",");




                //CONDUTAS
                $condutas = $this->AtendimentosModel->dadosCondutasAtendimento($atendimento->codAtendimento);

                if (!empty($condutas)) {





                    $htmlCondutas = "";
                    foreach ($condutas as $conduta) {

                        //assinatura
                        $assinatura['codCargo'] = $conduta->codCargo;
                        $assinatura['siglaCargo'] = $conduta->siglaCargo;
                        $assinatura['especialista'] = $conduta->nomeEspecialista;
                        $assinatura['especialistaNomeCompleto'] = $conduta->nomeCompletoEspecialista;
                        $assinatura['nomeConselho'] = $conduta->nomeConselho;
                        $assinatura['numeroInscricao'] = $conduta->numeroInscricao;
                        $assinatura['siglaEstadoFederacao'] = $conduta->siglaEstadoFederacao;
                        $assinatura['descricaoEspecialidade'] = $conduta->descricaoEspecialidade;

                        $htmlCondutas .= '<div class="text-muted well well-sm shadow-none">
                   ' . $conduta->conteudoConduta . ' - ' . date('d/m/Y H:i', strtotime($conduta->dataCriacao)) . ' - ' . $conduta->nomeEspecialista . '
                    </div>';
                    }
                } else {
                    $htmlCondutas = '<div class="text-muted well well-sm shadow-none">Não possui</div>';
                }


                //EVOLUÇÕES
                $evolucoes = $this->AtendimentosModel->dadosEvolucoesAtendimento($atendimento->codAtendimento);

                if (!empty($evolucoes)) {


                    $htmlEvolucoes = "";
                    foreach ($evolucoes as $evolucao) {

                        //assinatura
                        $assinatura['codCargo'] = $evolucao->codCargo;
                        $assinatura['siglaCargo'] = $evolucao->siglaCargo;
                        $assinatura['especialista'] = $evolucao->nomeEspecialista;
                        $assinatura['especialistaNomeCompleto'] = $evolucao->nomeCompletoEspecialista;
                        $assinatura['nomeConselho'] = $evolucao->nomeConselho;
                        $assinatura['numeroInscricao'] = $evolucao->numeroInscricao;
                        $assinatura['siglaEstadoFederacao'] = $evolucao->siglaEstadoFederacao;
                        $assinatura['descricaoEspecialidade'] = $evolucao->descricaoEspecialidade;


                        $htmlEvolucoes .= '<div class="text-muted well well-sm shadow-none">
                                   ' . $evolucao->conteudoEvolucao . ' - ' . date('d/m/Y H:i', strtotime($evolucao->dataCriacao)) . ' - ' . $evolucao->nomeEspecialista . '
                                    </div>';
                    }
                } else {
                    $htmlEvolucoes = '<div class="text-muted well well-sm shadow-none">Não possui</div>';
                }





                //PARECERES
                $pareceres = $this->AtendimentosModel->dadosPareceresAtendimento($atendimento->codAtendimento);

                if (!empty($pareceres)) {


                    $htmlPareceres = "";
                    foreach ($pareceres as $parecer) {

                        //assinatura
                        $assinatura['codCargo'] = $parecer->codCargo;
                        $assinatura['siglaCargo'] = $parecer->siglaCargo;
                        $assinatura['especialista'] = $parecer->nomeEspecialista;
                        $assinatura['especialistaNomeCompleto'] = $parecer->nomeCompletoEspecialista;
                        $assinatura['nomeConselho'] = $parecer->nomeConselho;
                        $assinatura['numeroInscricao'] = $parecer->numeroInscricao;
                        $assinatura['siglaEstadoFederacao'] = $parecer->siglaEstadoFederacao;
                        $assinatura['descricaoEspecialidade'] = $parecer->descricaoEspecialidade;


                        $htmlPareceres .= '<div class="text-muted well well-sm shadow-none">
                                   ' . $parecer->conteudoParecer . ' - ' . date('d/m/Y H:i', strtotime($parecer->dataCriacao)) . ' - ' . $parecer->nomeEspecialista . '
                                    </div>';
                    }
                } else {
                    $htmlPareceres = '<div class="text-muted well well-sm shadow-none">Não possui</div>';
                }


                $html .= '

            <div class="row border">
							<div class="col-sm-12">
								<div style="font-weight: bold; font-size:14px" class="row">

									<div class="col-sm-3">
										Nº Atendimento:' . $nrAtendimento . '/' . $ano . '
									</div>

									<div class="col-sm-3">
										Especialista: ' . $especialista . '
									</div>
									<div class="col-sm-3">
										Local: ' . $local . '
									</div>

									<div class="col-sm-3">
										Data:' . $periodoAtendimento . '
									</div>

								</div>
							</div>


                            <div class="col-sm-12">
                                    <div style="font-weight: bold; font-size:14px" class="row">

                                        <div class="col-sm-6">
                                            Tipo Atendimento:' . $tipoAtendimento . '
                                        </div>

                                        <div class="col-sm-6">
                                            Status: Atendido
                                        </div>
                                    </div>
                            </div>


							<div class="row">


								<div class="col-12">
									<div class="col-sm-12">
										<div style="font-weight: bold; font-size:14px">HDA</div>
										<div class="text-muted well well-sm shadow-none">
											' . $hda . '
										</div>
									</div>

									<div class="col-sm-12">
										<div style="font-weight: bold; font-size:14px">DIAGNÓSTICO</div>

										<div class="text-muted well well-sm shadow-none">
											' . $listaDiagnosticos . '
										</div>
									</div>


								</div>

							</div>


                                <div style="margin-left:1px" class="col-sm-12">
                                        <div style="font-weight: bold; font-size:14px;border-top: 2px dotted #eee;">CONDUTAS</div>

                                        ' . $htmlCondutas . '

                                </div>


                                <div style="margin-left:1px" class="col-sm-12">
                                        <div style="font-weight: bold; font-size:14px;border-top:2px dotted #eee;">EVOLUÇÕES</div>

                                        ' . $htmlEvolucoes . '

                                </div>

                                <div style="margin-left:1px" class="col-sm-12">
                                        <div style="font-weight: bold; font-size:14px;border-top: 2px dotted #eee;">PARECERES</div>

                                        ' . $htmlPareceres . '

                                </div>


				</div>



            ';
            }


            $dataCriacao = $data['dataCriacao'] = session()->cidade . '-' . session()->uf . ', ' . date('d', strtotime(date('Y-m-d'))) . ' de ' . nomeMesPorExtenso(date('m', strtotime(date('Y-m-d')))) . ' de ' . date('Y', strtotime(date('Y-m-d'))) . '.';

            $html .= '
            <div style="margin-top:30px;" class="row">
						<div class="col-md-12">
							<div class="text-right">' . $dataCriacao . '</div>
						</div>
			</div>';


            $assinaturasOrdenadas = ordenaAssinaturas($assinaturas);
            foreach ($assinaturasOrdenadas as $assinatura) {

                $tamanhoLinha = strlen($assinatura['especialistaNomeCompleto'] . ' - ' . $assinatura['siglaCargo']);
                $nome = $assinatura['especialistaNomeCompleto'] . ' - ' . $assinatura['siglaCargo'];
                $especialidade = $assinatura['descricaoEspecialidade'] . ' | ' . $assinatura['nomeConselho'] . ' nº ' . $assinatura['numeroInscricao'] . '/' . $assinatura['siglaEstadoFederacao'];
                $html .= '<div style="margin-top:30px" class="row">';
                $html .= '<hr style="margin-top:0px; margin-bottom:0px; width:' . ($tamanhoLinha * 10) . 'px !important" class="d-flex justify-content-center"></hr>';
                $html .= '<div style="margin-top:0px; font-weight:bold" class="col-md-12 d-flex justify-content-center">' . $nome . '</div>';
                $html .= '<div style="margin-top:0px; font-size:10px" class="col-md-12 d-flex justify-content-center">' . $especialidade . '</div>';
                $html .= '</div>';
            }


            $html .= '
            <div style="margin-top:50px;" class="row">
						<div class="col-md-12">
                        <div class="text-right">Emitido por ' . session()->nomeExibicao . ' (CPF:' . substr(session()->cpf, 0, -6) . '*****'  . ')</div>
                        <div class="text-right">Em ' . date('d/m/Y H:i') . '</div>
                        <div class="text-right">Sistema SANDRA | ' . base_url() . '</div>
						</div>
			</div>';

            $data['success'] = true;
            $data['html'] = $html;
        }

        return $this->response->setJSON($data);
    }


    public function dadosBoletimAtendimento()
    {
        $response = array();

        //VERIFICA  PERMISSÃO


        $acessoLiberado = 0;

        if (!empty(session()->minhasEspecialidades)) {
            $acessoLiberado = 1;
        }

        if (!empty(session()->minhasEspecialidades)) {
            $acessoLiberado = 1;
        }


        $perfisLiberados = array(2, 10, 16);
        if (!empty(session()->meusPerfis)) {


            foreach (session()->meusPerfis as $meuPerfil) {

                if (in_array($meuPerfil->codPerfil, $perfisLiberados)) {
                    $acessoLiberado = 1;
                }
            }
        }


        if ($acessoLiberado == 0) {
            $data['success'] = false;
            $data['messages'] = 'Apenas profissionais de saúde tem acesso a este recurso';

            return $this->response->setJSON($data);
        }



        $codAtendimento = $this->request->getPost('codAtendimento');

        $assinaturas = array();


        if ($this->validation->check($codAtendimento, 'required|numeric')) {



            $html = '';

            $assinatura = array();

            $dataAtendimento = $this->AtendimentosModel->dadosAtendimentoCompleto($codAtendimento);

            $assinatura['codCargo'] = $dataAtendimento->codCargo;
            $assinatura['siglaCargo'] = $dataAtendimento->siglaCargo;
            $assinatura['especialista'] = $dataAtendimento->nomeEspecialista;
            $assinatura['especialistaNomeCompleto'] = $dataAtendimento->nomeCompletoEspecialista;
            $assinatura['nomeConselho'] = $dataAtendimento->nomeConselho;
            $assinatura['siglaEstadoFederacao'] = $dataAtendimento->siglaEstadoFederacao;
            $assinatura['numeroInscricao'] = $dataAtendimento->numeroInscricao;;
            $assinatura['descricaoEspecialidade'] = $dataAtendimento->descricaoEspecialidade;


            array_push($assinaturas, $assinatura);

            $nrAtendimento = str_pad($dataAtendimento->codAtendimento, 8, '0', STR_PAD_LEFT);
            $ano = date('Y', strtotime($dataAtendimento->dataCriacao));


            $especialista =  $dataAtendimento->nomeEspecialista;
            $local =  $dataAtendimento->localAtendimento;
            $tipoAtendimento =  $dataAtendimento->descricaoTipoAtendimento;
            $hda =  $dataAtendimento->hda;
            $statusAtendimento =  $dataAtendimento->descricaoStatusAtendimento;
            $idade =  $dataAtendimento->idade;
            $nomeExibicaoPaciente =  $dataAtendimento->nomeExibicaoPaciente;
            $codPlanoPaciente = $dataAtendimento->codPlanoPaciente;
            $codProntuarioPaciente = $dataAtendimento->codProntuarioPaciente;
            $siglaCargoPaciente = $dataAtendimento->siglaCargoPaciente;
            $cpfPaciente = $dataAtendimento->cpfPaciente;
            $nomeMaePaciente = $dataAtendimento->nomeMaePaciente;
            $tipoBeneficiario = $dataAtendimento->nomeTipoBeneficiario . ' - ' . $dataAtendimento->siglaTipoBeneficiario;
            $sexoPaciente = $dataAtendimento->sexoPaciente;
            $dataNascimentoPaciente = $dataAtendimento->dataNascimentoPaciente;
            $dataValidade = $dataAtendimento->dataValidade;


            if ($dataAtendimento->dataEncerramento == NULL) {
                $periodoAtendimento = date('d/m/Y H:i', strtotime($dataAtendimento->dataCriacao));
            } else {

                if (date('d/m/Y', strtotime($dataAtendimento->dataCriacao)) < date('d/m/Y', strtotime($dataAtendimento->dataEncerramento))) {
                    $periodoAtendimento = date('d/m/Y H:i', strtotime($dataAtendimento->dataCriacao)) . ' a ' . $dataAtendimento = date('d/m/Y H:i', strtotime($dataAtendimento->dataEncerramento));
                } else {
                    $periodoAtendimento = date('d/m/Y H:i', strtotime($dataAtendimento->dataCriacao));
                }
            }



            //DIAGNOSTICOS
            $diagnosticos = $this->AtendimentosModel->dadosDiagnosticosAtendimento($codAtendimento);

            $listaDiagnosticos = "";
            foreach ($diagnosticos as $diagnostico) {
                $listaDiagnosticos .= $diagnostico->cid . ",";
            }
            $listaDiagnosticos = rtrim($listaDiagnosticos, ",");




            //CONDUTAS
            $condutas = $this->AtendimentosModel->dadosCondutasAtendimento($codAtendimento);

            if (!empty($condutas)) {





                $htmlCondutas = "";

                foreach ($condutas as $conduta) {

                    //assinatura
                    $assinatura['codCargo'] = $conduta->codCargo;
                    $assinatura['siglaCargo'] = $conduta->siglaCargo;
                    $assinatura['especialista'] = $conduta->nomeEspecialista;
                    $assinatura['especialistaNomeCompleto'] = $conduta->nomeCompletoEspecialista;
                    $assinatura['nomeConselho'] = $conduta->nomeConselho;
                    $assinatura['numeroInscricao'] = $conduta->numeroInscricao;
                    $assinatura['siglaEstadoFederacao'] = $conduta->siglaEstadoFederacao;
                    $assinatura['descricaoEspecialidade'] = $conduta->descricaoEspecialidade;

                    $htmlCondutas .= '<div class="text-muted well well-sm shadow-none">
                   ' . $conduta->conteudoConduta . ' - ' . date('d/m/Y H:i', strtotime($conduta->dataCriacao)) . ' - ' . $conduta->nomeEspecialista . '
                    </div>';
                }
            } else {
                $htmlCondutas = '<div class="text-muted well well-sm shadow-none">Não possui</div>';
            }


            //EVOLUÇÕES
            $evolucoes = $this->AtendimentosModel->dadosEvolucoesAtendimento($codAtendimento);

            if (!empty($evolucoes)) {


                $htmlEvolucoes = "";
                foreach ($evolucoes as $evolucao) {

                    //assinatura
                    $assinatura['codCargo'] = $evolucao->codCargo;
                    $assinatura['siglaCargo'] = $evolucao->siglaCargo;
                    $assinatura['especialista'] = $evolucao->nomeEspecialista;
                    $assinatura['especialistaNomeCompleto'] = $evolucao->nomeCompletoEspecialista;
                    $assinatura['nomeConselho'] = $evolucao->nomeConselho;
                    $assinatura['numeroInscricao'] = $evolucao->numeroInscricao;
                    $assinatura['siglaEstadoFederacao'] = $evolucao->siglaEstadoFederacao;
                    $assinatura['descricaoEspecialidade'] = $evolucao->descricaoEspecialidade;


                    $htmlEvolucoes .= '<div class="text-muted well well-sm shadow-none">
                                   ' . $evolucao->conteudoEvolucao . ' - ' . date('d/m/Y H:i', strtotime($evolucao->dataCriacao)) . ' - ' . $evolucao->nomeEspecialista . '
                                    </div>';
                }
            } else {
                $htmlEvolucoes = '<div class="text-muted well well-sm shadow-none">Não possui</div>';
            }





            //PARECERES
            $pareceres = $this->AtendimentosModel->dadosPareceresAtendimento($codAtendimento);

            if (!empty($pareceres)) {


                $htmlPareceres = "";
                foreach ($pareceres as $parecer) {

                    //assinatura
                    $assinatura['codCargo'] = $parecer->codCargo;
                    $assinatura['siglaCargo'] = $parecer->siglaCargo;
                    $assinatura['especialista'] = $parecer->nomeEspecialista;
                    $assinatura['especialistaNomeCompleto'] = $parecer->nomeCompletoEspecialista;
                    $assinatura['nomeConselho'] = $parecer->nomeConselho;
                    $assinatura['numeroInscricao'] = $parecer->numeroInscricao;
                    $assinatura['siglaEstadoFederacao'] = $parecer->siglaEstadoFederacao;
                    $assinatura['descricaoEspecialidade'] = $parecer->descricaoEspecialidade;


                    $htmlPareceres .= '<div class="text-muted well well-sm shadow-none">
                                   ' . $parecer->conteudoParecer . ' - ' . date('d/m/Y H:i', strtotime($parecer->dataCriacao)) . ' - ' . $parecer->nomeEspecialista . '
                                    </div>';
                }
            } else {
                $htmlPareceres = '<div class="text-muted well well-sm shadow-none">Não possui</div>';
            }


            $html .= '  

            <div class="row">
                <div class="col-12">
                    <h4>
                        <i class="fas fa-user"></i><span>' . $nomeExibicaoPaciente . ', ' . $idade . ' anos</span>
                    </h4>
                </div>
            </div>

            <div class="row invoice-info">
                <div class="col-sm-3 invoice-col">
                    <div>Nº Plano: ' . $codPlanoPaciente . '</div>
                    <div>Nº Prontuário: ' . $codProntuarioPaciente . '</div>
                    <div>Posto/Grad: ' . $siglaCargoPaciente . '</div>

                </div>
                <!-- /.col -->
                <div class="col-sm-5 invoice-col">
                    <div>CPF: ' . $cpfPaciente . '</div>
                    <div>Nome Mãe: ' . $nomeMaePaciente . '</div>
                    <div>Situação: ' . $tipoBeneficiario . '</div>

                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    <div>Sexo:' . $sexoPaciente . '</div>
                    <div>Data Nascimento: ' . date('d/m/Y', strtotime($dataNascimentoPaciente)) . '</div>
                    <div>Validade: ' . date('d/m/Y', strtotime($dataValidade)) . '</div>
                </div>

            </div>
            <br>
            <div class="row border">
							<div class="col-sm-12">
								<div style="font-weight: bold; font-size:14px" class="row">

									<div class="col-sm-3">
										Nº Atendimento:' . $nrAtendimento . '/' . $ano . '
									</div>

									<div class="col-sm-3">
										Especialista: ' . $especialista . '
									</div>
									<div class="col-sm-3">
										Local: ' . $local . '
									</div>

									<div class="col-sm-3">
										Data/Hora:' . $periodoAtendimento . '
									</div>

								</div>
							</div>


                            <div class="col-sm-12">
                                    <div style="font-weight: bold; font-size:14px" class="row">

                                        <div class="col-sm-6">
                                            Tipo Atendimento:' . $tipoAtendimento . '
                                        </div>

                                        <div class="col-sm-6">
                                            Status: ' . $statusAtendimento . '
                                        </div>
                                    </div>
                            </div>


							<div class="row">


								<div class="col-12">
									<div class="col-sm-12">
										<div style="font-weight: bold; font-size:14px">HDA</div>
										<div class="text-muted well well-sm shadow-none">
											' . $hda . '
										</div>
									</div>

									<div class="col-sm-12">
										<div style="font-weight: bold; font-size:14px">DIAGNÓSTICO</div>

										<div class="text-muted well well-sm shadow-none">
											' . $listaDiagnosticos . '
										</div>
									</div>


								</div>

							</div>


                                <div style="margin-left:1px" class="col-sm-12">
                                        <div style="font-weight: bold;border-top: 2px dotted #eee; font-size:14px">CONDUTAS</div>

                                        ' . $htmlCondutas . '

                                </div>


                                <div style="margin-left:1px;border-top: 2px dotted #eee;" class="col-sm-12 ">
                                        <div style="font-weight: bold; font-size:14px">EVOLUÇÕES</div>

                                        ' . $htmlEvolucoes . '

                                </div>

                                <div style="margin-left:1px;border-top: 2px dotted #eee;" class="col-sm-12">
                                        <div style="font-weight: bold; font-size:14px">PARECERES</div>

                                        ' . $htmlPareceres . '

                                </div>


				</div>



            ';



            $dataCriacao = $data['dataCriacao'] = session()->cidade . '-' . session()->uf . ', ' . date('d', strtotime(date('Y-m-d'))) . ' de ' . nomeMesPorExtenso(date('m', strtotime(date('Y-m-d')))) . ' de ' . date('Y', strtotime(date('Y-m-d'))) . '.';

            $html .= '
            <div style="margin-top:30px;" class="row">
						<div class="col-md-12">
							<div class="text-right">' . $dataCriacao . '</div>
						</div>
			</div>';


            $assinaturasOrdenadas = ordenaAssinaturas($assinaturas);
            foreach ($assinaturasOrdenadas as $assinatura) {

                $tamanhoLinha = strlen($assinatura['especialistaNomeCompleto'] . ' - ' . $assinatura['siglaCargo']);
                $nome = $assinatura['especialistaNomeCompleto'] . ' - ' . $assinatura['siglaCargo'];
                $especialidade = $assinatura['descricaoEspecialidade'] . ' | ' . $assinatura['nomeConselho'] . ' nº ' . $assinatura['numeroInscricao'] . '/' . $assinatura['siglaEstadoFederacao'];
                $html .= '<div style="margin-top:30px" class="row">';
                $html .= '<hr style="margin-top:0px; margin-bottom:0px; width:' . ($tamanhoLinha * 10) . 'px !important" class="d-flex justify-content-center"></hr>';
                $html .= '<div style="margin-top:0px; font-weight:bold" class="col-md-12 d-flex justify-content-center">' . $nome . '</div>';
                $html .= '<div style="margin-top:0px; font-size:10px" class="col-md-12 d-flex justify-content-center">' . $especialidade . '</div>';
                $html .= '</div>';
            }


            $html .= '
            <div style="margin-top:50px;" class="row">
						<div class="col-md-12">
                        <div class="text-right">Emitido por ' . session()->nomeExibicao . ' (CPF:' . substr(session()->cpf, 0, -6) . '*****'  . ')</div>
                        <div class="text-right">Em ' . date('d/m/Y H:i') . '</div>
                        <div class="text-right">Sistema SANDRA | ' . base_url() . '</div>
						</div>
			</div>';

            $data['success'] = true;
            $data['html'] = $html;
        }

        return $this->response->setJSON($data);
    }

    public function imprimirProntuarioCompleto()
    {
        $response = array();

        $codPaciente = $this->request->getPost('codPaciente');

        if ($this->validation->check($codPaciente, 'required|numeric')) {

            $data = $this->AtendimentosModel->pegaPorCodigo($codPaciente);
        }
    }

    public function getOne()
    {
        $response = array();

        $id = $this->request->getPost('codAtendimento');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->AtendimentosModel->pegaPorCodigo($id);

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function addAtendimentoAmbulatorioManual()
    {


        $response = array();

        /*
        $existe = $this->AtendimentosModel->verificaExistenciaAtendimentoEmAberto($this->request->getPost('codPaciente'), session()->codPessoa, 2);

        if ($existe !== NULL) {
            $response['success'] = 'info';
            $response['codPaciente'] = $this->request->getPost('codPaciente');
            $response['messages'] = 'Já existe atendimento/caso em aberto. Feche-o para iniciar um novo ou continue a evolução naquele atendimento.';
            $response['botao'] = '<button class="btn btn-warning " onclick="editarAtendimento(' . $existe->codAtendimento . ')" ><i class="fas fa-edit"></i>Ir para o atendimento em aberto</button>';
            return $this->response->setJSON($response);
        }

        */

        $fields['codOrganizacao'] = session()->codOrganizacao;
        $fields['codPaciente'] = $this->request->getPost('codPaciente');
        $fields['codLocalAtendimento'] = $this->request->getPost('codLocalAtendimento');
        $fields['codEspecialista'] = session()->codPessoa;
        $fields['codEspecialidade'] = $this->request->getPost('codEspecialidade');
        $fields['codStatus'] = 1;
        $fields['dataCriacao'] = date('Y-m-d H:i');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['dataInicio'] = date('Y-m-d H:i');;
        $fields['dataEncerramento'] = null;
        $fields['codTipoAtendimento'] = $this->request->getPost('codTipoAtendimento');
        $fields['codAutor'] = session()->codPessoa;



        $this->validation->setRules([
            'codOrganizacao' => ['label' => 'CodOrganizacao', 'rules' => 'required|numeric|max_length[11]'],
            'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'required|numeric|max_length[11]'],
            'codLocalAtendimento' => ['label' => 'codLocalAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'codEspecialista' => ['label' => 'CodEspecialista', 'rules' => 'required|numeric|max_length[11]'],
            'codEspecialidade' => ['label' => 'CodEspecialidade', 'rules' => 'required|numeric|max_length[11]'],
            'codStatus' => ['label' => 'Status', 'rules' => 'required|numeric|max_length[11]'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
            'dataInicio' => ['label' => 'DataInicio', 'rules' => 'permit_empty'],
            'dataEncerramento' => ['label' => 'DataEncerramento', 'rules' => 'permit_empty'],
            'codTipoAtendimento' => ['label' => 'CodTipoAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($codAtendimento = $this->AtendimentosModel->insert($fields)) {

                $response['success'] = true;
                $response['codAtendimento'] = $codAtendimento;
                $response['csrf_hash'] = csrf_hash();
                $response['messages'] = 'Atendimento Iniciado';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na inserção!';
            }
        }

        sleep(1);



        if (session()->nomeLocalAtendimento == NULL or session()->codLocalAtendimento == NULL) {
            session()->codLocalAtendimento = $this->request->getPost('codLocalAtendimento');
            session()->nomeLocalAtendimento = lookupNomeLocalAtendimento($this->request->getPost('codLocalAtendimento'));
        }

        if (session()->codEspecialidadeAtendimento == NULL or session()->nomeEspecialidadesAtendimento == NULL) {
            session()->codEspecialidadeAtendimento = $this->request->getPost('codEspecialidade');
            session()->nomeEspecialidadesAtendimento = lookupNomeEspecialidade($this->request->getPost('codEspecialidade'));
        }


        return $this->response->setJSON($response);
    }


    public function addTratamentoCirurgicoManual()
    {

        $response = array();



        $fields['codOrganizacao'] = session()->codOrganizacao;
        $fields['codPaciente'] = $this->request->getPost('codPaciente');
        $fields['codLocalAtendimento'] = $this->request->getPost('codLocalAtendimento');
        $fields['codEspecialista'] = session()->codPessoa;
        $fields['codEspecialidade'] = $this->request->getPost('codEspecialidade');
        $fields['codStatus'] = 1;
        $fields['dataCriacao'] = date('Y-m-d H:i');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['dataInicio'] = date('Y-m-d H:i');;
        $fields['dataEncerramento'] = null;
        $fields['codTipoAtendimento'] = 5;
        $fields['codAutor'] = session()->codPessoa;



        $this->validation->setRules([
            'codOrganizacao' => ['label' => 'CodOrganizacao', 'rules' => 'required|numeric|max_length[11]'],
            'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'required|numeric|max_length[11]'],
            'codLocalAtendimento' => ['label' => 'codLocalAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'codEspecialista' => ['label' => 'CodEspecialista', 'rules' => 'required|numeric|max_length[11]'],
            'codEspecialidade' => ['label' => 'CodEspecialidade', 'rules' => 'required|numeric|max_length[11]'],
            'codStatus' => ['label' => 'Status', 'rules' => 'required|numeric|max_length[11]'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
            'dataInicio' => ['label' => 'DataInicio', 'rules' => 'permit_empty'],
            'dataEncerramento' => ['label' => 'DataEncerramento', 'rules' => 'permit_empty'],
            'codTipoAtendimento' => ['label' => 'CodTipoAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($codAtendimento = $this->AtendimentosModel->insert($fields)) {

                $response['success'] = true;
                $response['codAtendimento'] = $codAtendimento;
                $response['csrf_hash'] = csrf_hash();
                $response['messages'] = 'Atendimento Iniciado';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na inserção!';
            }
        }

        sleep(1);



        if (session()->nomeLocalAtendimento == NULL or session()->codLocalAtendimento == NULL) {
            session()->codLocalAtendimento = $this->request->getPost('codLocalAtendimento');
            session()->nomeLocalAtendimento = lookupNomeLocalAtendimento($this->request->getPost('codLocalAtendimento'));
        }

        if (session()->codEspecialidadeAtendimento == NULL or session()->nomeEspecialidadesAtendimento == NULL) {
            session()->codEspecialidadeAtendimento = $this->request->getPost('codEspecialidade');
            session()->nomeEspecialidadesAtendimento = lookupNomeEspecialidade($this->request->getPost('codEspecialidade'));
        }


        return $this->response->setJSON($response);
    }

    public function addTratamentoOncologicoManual()
    {

        $response = array();


        //verifica se já existe um atendimento aberto

        $existe = $this->AtendimentosModel->verificaExistenciaTratOncologico($this->request->getPost('codPaciente'));

        if ($existe !== NULL) {
            $response['success'] = 'info';
            $response['codPaciente'] = $this->request->getPost('codPaciente');
            $response['messages'] = 'Já existe uma tratamento em curso.';
            $response['botao'] = '<button class="btn btn-warning " onclick="editarAtendimento(' . $existe->codAtendimento . ')" ><i class="fas fa-edit"></i>Ir para o caso aberto</button>';
            return $this->response->setJSON($response);
        }


        $fields['codOrganizacao'] = session()->codOrganizacao;
        $fields['codPaciente'] = $this->request->getPost('codPaciente');
        $fields['codLocalAtendimento'] = $this->request->getPost('codLocalAtendimento');
        $fields['codEspecialista'] = session()->codPessoa;
        $fields['codEspecialidade'] = $this->request->getPost('codEspecialidade');
        $fields['codStatus'] = 1;
        $fields['dataCriacao'] = date('Y-m-d H:i');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['dataInicio'] = date('Y-m-d H:i');;
        $fields['dataEncerramento'] = null;
        $fields['codTipoAtendimento'] = $this->request->getPost('codTipoAtendimento');
        $fields['codAutor'] = session()->codPessoa;



        $this->validation->setRules([
            'codOrganizacao' => ['label' => 'CodOrganizacao', 'rules' => 'required|numeric|max_length[11]'],
            'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'required|numeric|max_length[11]'],
            'codLocalAtendimento' => ['label' => 'codLocalAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'codEspecialista' => ['label' => 'CodEspecialista', 'rules' => 'required|numeric|max_length[11]'],
            'codEspecialidade' => ['label' => 'CodEspecialidade', 'rules' => 'required|numeric|max_length[11]'],
            'codStatus' => ['label' => 'Status', 'rules' => 'required|numeric|max_length[11]'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
            'dataInicio' => ['label' => 'DataInicio', 'rules' => 'permit_empty'],
            'dataEncerramento' => ['label' => 'DataEncerramento', 'rules' => 'permit_empty'],
            'codTipoAtendimento' => ['label' => 'CodTipoAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($codAtendimento = $this->AtendimentosModel->insert($fields)) {

                $response['success'] = true;
                $response['codAtendimento'] = $codAtendimento;
                $response['csrf_hash'] = csrf_hash();
                $response['messages'] = 'Atendimento Iniciado';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na inserção!';
            }
        }

        sleep(1);



        if (session()->nomeLocalAtendimento == NULL or session()->codLocalAtendimento == NULL) {
            session()->codLocalAtendimento = $this->request->getPost('codLocalAtendimento');
            session()->nomeLocalAtendimento = lookupNomeLocalAtendimento($this->request->getPost('codLocalAtendimento'));
        }

        if (session()->codEspecialidadeAtendimento == NULL or session()->nomeEspecialidadesAtendimento == NULL) {
            session()->codEspecialidadeAtendimento = $this->request->getPost('codEspecialidade');
            session()->nomeEspecialidadesAtendimento = lookupNomeEspecialidade($this->request->getPost('codEspecialidade'));
        }


        return $this->response->setJSON($response);
    }

    public function addAtendimentoUrgenciaEmergenciaManual()
    {

        $response = array();




        //verifica se já existe um atendimento aberto
        /*
        $existe = $this->AtendimentosModel->verificaExistenciaUrgenciaEmergencia($this->request->getPost('codPaciente'));

        if ($existe !== NULL) {
            $response['success'] = 'info';
            $response['codPaciente'] = $this->request->getPost('codPaciente');
            $response['messages'] = 'Já existe uma atendimento em aberto!';
            return $this->response->setJSON($response);
        }
        */

        $fields['codOrganizacao'] = session()->codOrganizacao;
        $fields['codPaciente'] = $this->request->getPost('codPaciente');
        $fields['codLocalAtendimento'] = $this->request->getPost('codLocalAtendimento');
        $fields['codEspecialista'] = 0;
        $fields['codEspecialidade'] = $this->request->getPost('codEspecialidade');
        $fields['codStatus'] = 0;
        $fields['dataCriacao'] = date('Y-m-d H:i');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['dataInicio'] = date('Y-m-d H:i');;
        $fields['dataEncerramento'] = null;
        $fields['codTipoAtendimento'] = 1;
        $fields['codAutor'] = session()->codPessoa;


        if (session()->nomeLocalAtendimento == NULL or session()->codLocalAtendimento == NULL) {
            session()->codLocalAtendimento = $this->request->getPost('codLocalAtendimento');
            session()->nomeLocalAtendimento = lookupNomeLocalAtendimento($this->request->getPost('codLocalAtendimento'));
        }

        if (session()->codEspecialidadeAtendimento == NULL or session()->nomeEspecialidadesAtendimento == NULL) {
            session()->codEspecialidadeAtendimento = $this->request->getPost('codEspecialidade');
            session()->nomeEspecialidadesAtendimento = lookupNomeEspecialidade($this->request->getPost('codEspecialidade'));
        }

        $this->validation->setRules([
            'codOrganizacao' => ['label' => 'CodOrganizacao', 'rules' => 'required|numeric|max_length[11]'],
            'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'required|numeric|max_length[11]'],
            'codLocalAtendimento' => ['label' => 'codLocalAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'codEspecialista' => ['label' => 'CodEspecialista', 'rules' => 'required|numeric|max_length[11]'],
            'codEspecialidade' => ['label' => 'CodEspecialidade', 'rules' => 'required|numeric|max_length[11]'],
            'codStatus' => ['label' => 'Status', 'rules' => 'required|numeric|max_length[11]'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
            'dataInicio' => ['label' => 'DataInicio', 'rules' => 'permit_empty'],
            'dataEncerramento' => ['label' => 'DataEncerramento', 'rules' => 'permit_empty'],
            'codTipoAtendimento' => ['label' => 'CodTipoAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($codAtendimento = $this->AtendimentosModel->insert($fields)) {

                //GRAVA QUEIXA ATUAL


                //INSERT
                $anamnese['codAtendimento'] = $codAtendimento;
                $anamnese['codPaciente'] =  $fields['codPaciente'];
                $anamnese['codEspecialidade'] = 29;
                $anamnese['codEspecialista'] =  0;
                $anamnese['queixaPrincipal'] = $this->request->getPost('queixaPrincipal');
                $anamnese['codStatus'] = 1;
                $anamnese['dataCriacao'] = date('Y-m-d H:i');
                $anamnese['dataAtualizacao'] = date('Y-m-d H:i');


                if ($codAtendimento !== NULL and $codAtendimento !== "" and $codAtendimento !== " ") {

                    if ($this->AtendimentoAnamneseModel->insert($anamnese)) {
                    }
                }

                $response['success'] = true;
                $response['csrf_hash'] = csrf_hash();
                $response['messages'] = 'Atendimento registrado';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na inserção!';
            }
        }
        sleep(3);

        return $this->response->setJSON($response);
    }



    public function edit()
    {

        $response = array();

        $fields['codAtendimento'] = $this->request->getPost('codAtendimento');
        $fields['codOrganizacao'] = $this->request->getPost('codOrganizacao');
        $fields['codPaciente'] = $this->request->getPost('codPaciente');
        $fields['codLocalAtendimento'] = $this->request->getPost('codLocalAtendimento');
        $fields['codEspecialista'] = $this->request->getPost('codEspecialista');
        $fields['codEspecialidade'] = $this->request->getPost('codEspecialidade');
        $fields['codStatus'] = $this->request->getPost('codStatus');
        $fields['dataCriacao'] = $this->request->getPost('dataCriacao');
        $fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');
        $fields['dataInicio'] = $this->request->getPost('dataInicio');
        $fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');
        $fields['codTipoAtendimento'] = $this->request->getPost('codTipoAtendimento');
        $fields['codAutor'] = $this->request->getPost('codAutor');


        $this->validation->setRules([
            'codAtendimento' => ['label' => 'codAtendimento', 'rules' => 'required|numeric'],
            'codOrganizacao' => ['label' => 'CodOrganizacao', 'rules' => 'required|numeric|max_length[11]'],
            'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'required|numeric|max_length[11]'],
            'codLocalAtendimento' => ['label' => 'codLocalAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'codEspecialista' => ['label' => 'CodEspecialista', 'rules' => 'required|numeric|max_length[11]'],
            'codEspecialidade' => ['label' => 'CodEspecialidade', 'rules' => 'required|numeric|max_length[11]'],
            'codStatus' => ['label' => 'Status', 'rules' => 'required|numeric|max_length[11]'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
            'dataInicio' => ['label' => 'DataInicio', 'rules' => 'permit_empty'],
            'dataEncerramento' => ['label' => 'DataEncerramento', 'rules' => 'permit_empty'],
            'codTipoAtendimento' => ['label' => 'CodTipoAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->AtendimentosModel->update($fields['codAtendimento'], $fields)) {

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

        $id = $this->request->getPost('codAtendimento');

        if (!$this->validation->check($id, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($this->AtendimentosModel->where('codAtendimento', $id)->delete()) {

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
