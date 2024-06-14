<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;
use App\Models\EspecialidadesModel;
use App\Models\AgendamentosModel;
use App\Models\AgendamentoSenhasConfigModel;
use App\Models\AtendimentoSenhasModel;

class AgendamentoSenhasConfig extends BaseController
{

    protected $AgendamentoSenhasConfigModel;
    protected $pessoasModel;
    protected $OrganizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

    public function __construct()
    {

        helper('seguranca_helper');
        verificaSeguranca($this, session(), base_url());
        $this->AtendimentoSenhasModel = new AtendimentoSenhasModel();
        $this->AgendamentoSenhasConfigModel = new AgendamentoSenhasConfigModel();
        $this->AgendamentosModel = new AgendamentosModel();
        $this->EspecialidadesModel = new EspecialidadesModel();
        $this->OrganizacoesModel = new OrganizacoesModel();
        $this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
        $this->validation =  \Config\Services::validation();
        $this->codOrganizacao = session()->codOrganizacao;
        $this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
    }

    public function listaDropDownStatusAgendamento()
    {

        $result = $this->EspecialidadesModel->listaDropDownStatusAgendamento();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function listaDropDownTipoSenha()
    {

        $result = $this->AgendamentoSenhasConfigModel->listaDropDownTipoSenha();

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


    public function listaDropDownEspecialidadesDisponivelMarcacao()
    {

        $result = $this->EspecialidadesModel->listaDropDownEspecialidadesDisponivelMarcacao();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function index()
    {
        $permissao = verificaPermissao('agendamentoSenhasConfig', 'listar');
        if ($permissao == 0) {
            echo mensagemAcessoNegado(session()->organizacoes);
            $this->LogsModel->inserirLog('Acesso indevido ao Módulo "AgendamentoSenhasConfig"', session()->codPessoa);
            exit();
        }

        $data = [
            'controller'        => 'agendamentoSenhasConfig',
            'title'             => 'Configuração de Agendamentos'
        ];
        echo view('tema/cabecalho');
        echo view('tema/menu_vertical');
        echo view('tema/menu_horizontal');
        return view('agendamentoSenhasConfig', $data);
    }

    public function getAll()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->AgendamentoSenhasConfigModel->pegaTudo();

        foreach ($result as $key => $value) {


            //ESTATISTICAS DE VAGAS
            $vagasCriadas = $this->AgendamentoSenhasConfigModel->vagasCriadas($value->codConfig)->total;
            $vagasAbertas = $this->AgendamentoSenhasConfigModel->vagasAbertas($value->codConfig)->total;
            //$vagasCriadas = '';
            // $vagasAbertas = '';

            $criadoPor =
                '<div>' . $value->autor . '</div>
            <div> Em ' . date('d/m/Y H:i', strtotime($value->dataCriacao)) . '</div>';

            $ops = '<div class="btn-group">';
            $ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editagendamentoSenhasConfig(' . $value->codConfig . ')"><i class="fa fa-edit"></i></button>';
            $ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeagendamentoSenhasConfig(' . $value->codConfig . ')"><i class="fa fa-trash"></i></button>';
            $ops .= '</div>';

            if ($value->ordemAtendimento == 1) {
                $ordemAtendimento = 'Hora marcada';
            } else {
                $ordemAtendimento = 'Ordem de chegada';
            }
            $periodo = "De " . date('d/m/Y', strtotime($value->dataInicio)) . " até " . date('d/m/Y', strtotime($value->dataEncerramento)) . " Das " . date('H:i', strtotime($value->horaInicio)) . " às " . date('H:i', strtotime($value->horaEncerramento)) . " (" . $ordemAtendimento . ")";
            $data['data'][$key] = array(
                $value->codConfig,
                $value->abreviacaoDepartamento,
                $periodo,
                $value->tempoAtendimento . " Min",
                $value->qtdeAtendentes,
                $value->nomeStatus,
                $value->nomeTipo,
                $vagasCriadas,
                $vagasAbertas,
                $criadoPor,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }

    public function getOne()
    {
        $response = array();

        $id = $this->request->getPost('codConfig');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->AgendamentoSenhasConfigModel->pegaPorCodigo($id);

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }



    public function cancelarAgendamento()
    {

        $codAgendamento = $this->request->getPost('codAgendamento');


        if ($codAgendamento !== NULL and $codAgendamento !== "" and $codAgendamento !== " ") {

            $fields['codStatus'] = 4;
            $fields['dataAtualizacao'] = date('Y-m-d H:i');
            $fields['codAutor'] = session()->codPessoa;

            $this->AgendamentosModel->update($codAgendamento, $fields);
        }

        $response['success'] = true;
        $response['messages'] = 'Cancelada com sucesso!';

        return $this->response->setJSON($response);
    }

    public function listaVagasAvulsas()
    {


        $response = array();

        $filtro['codEspecialidade'] = $this->request->getPost('codEspecialidade');
        $filtro['codEspecialista'] = $this->request->getPost('codEspecialista');
        $filtro['dataInicio'] = $this->request->getPost('dataInicio');
        $filtro['dataEncerramento'] = $this->request->getPost('dataEncerramento');

        session()->set('filtroEspecialidade', $filtro);

        $response = array();

        $agendamentos = $this->AgendamentosModel->cancelamentosAgendamentosPorEspecialidade();


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
                    $hora = date('H:i', strtotime($agendamento->dataInicio));
                    $diaMes = date('d/m', strtotime($agendamento->dataInicio));
                    $diaSenma = diaSemanaAbreviado($agendamento->dataInicio);

                    $botao = 'btn-outline-primary';

                    $nomeStatus = $agendamento->nomeStatus;

                    if ($agendamento->codStatus == 0) {
                        $botao = 'btn-success';
                    }
                    if ($agendamento->codStatus == 1) {
                        $botao = 'btn-info';
                        $nomeStatus = 'MARCADO';
                    }

                    if ($agendamento->codStatus == 4) {
                        $botao = 'btn-dark';
                    }





                    $slotsLivres .= '

                    <div class="col-md-3">
                        <div class="form-group">
                            <button type="button" onclick="cancelarAgendamento(' . $agendamento->codAgendamento . ')" class="btn btn-block ' . $botao . ' btn-lg">
                            <div>' . $nomeStatus . '</div>
                            <div>' . "(" . $diaSenma . ") " . $diaMes . ' - ' . $hora . ' </div>
                            </button>
                        </div>
                    </div>
                    ';
                }
            }
            $slotsLivres .= '
            </div>
            </div>
            </div>';
        }
        $slotsLivres .= '</div>';


        sleep(1);

        $response['success'] = true;
        $response['csrf_hash'] = csrf_hash();
        $response['slotsLivres'] = $slotsLivres;
        return $this->response->setJSON($response);
    }




    public function add()
    {

        sleep(2);
        $response = array();

        $fields['codOrganizacao'] = session()->codOrganizacao;
        $fields['codDepartamento'] = $this->request->getPost('codDepartamento');
        $fields['dataInicio'] = $this->request->getPost('dataInicio');
        $fields['horaInicio'] = $this->request->getPost('horaInicio');
        $fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');
        $fields['horaEncerramento'] = $this->request->getPost('horaEncerramento');
        $fields['tempoAtendimento'] = $this->request->getPost('tempoAtendimento');
        $fields['qtdeAtendentes'] = $this->request->getPost('qtdeAtendentes');
        $fields['codStatusAgendamento'] = $this->request->getPost('codStatusAgendamento');
        $fields['codTipoAgendamento'] = $this->request->getPost('codTipoAgendamento');
        $fields['ordemAtendimento'] = $this->request->getPost('ordemAtendimento');
        $fields['dataCriacao'] = date('Y-m-d H:i');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['autor'] = session()->codPessoa;



        if ($fields['dataInicio'] > $fields['dataEncerramento']) {
            $response['success'] = false;
            $response['messages'] = 'Data de inicio não pode ser maior que a data de encerramento!';
            return $this->response->setJSON($response);
        }



        if ($fields['tempoAtendimento'] < 1) {
            $response['success'] = false;
            $response['messages'] = 'Tempo de atendimento não pode ser menor que 1';
            return $this->response->setJSON($response);
        }



        if ($fields['qtdeAtendentesAdd'] < 0) {
            $response['success'] = false;
            $response['messages'] = 'Deve haver ao menos 1 atendente';
            return $this->response->setJSON($response);
        }




        if ($fields['horaEncerramento'] < $fields['horaInicio']) {
            $response['success'] = false;
            $response['messages'] = 'Hora de inicio não pode ser maior que a hora de encerramento!';
            return $this->response->setJSON($response);
        }


        //SLOTS NÃO PODE SER PARA MAIS DE 90 DIAS

        $date1 = date_create($fields['dataInicio']);
        $date2 = date_create($fields['dataEncerramento']);
        $diff = date_diff($date1, $date2);
        $dias =  $diff->format("%a");
        if ($dias > 90) {

            $response['success'] = false;
            $response['messages'] = 'Configuração de agenda não pode ultrapassar 90 Dias. Reveja a configuração ou contate o administrador do sistema';
            return $this->response->setJSON($response);
        }





        $diasSemanaDisponivel = array();

        if ($this->request->getPost('segunda') == 'on') {
            $fields['segunda'] = 1;
            array_push($diasSemanaDisponivel, 1);
        } else {
            $fields['segunda'] = 0;
        }
        if ($this->request->getPost('terca') == 'on') {
            $fields['terca'] = 1;
            array_push($diasSemanaDisponivel, 2);
        } else {
            $fields['terca'] = 0;
        }
        if ($this->request->getPost('quarta') == 'on') {
            $fields['quarta'] = 1;
            array_push($diasSemanaDisponivel, 3);
        } else {
            $fields['quarta'] = 0;
        }
        if ($this->request->getPost('quinta') == 'on') {
            $fields['quinta'] = 1;
            array_push($diasSemanaDisponivel, 4);
        } else {
            $fields['quinta'] = 0;
        }
        if ($this->request->getPost('sexta') == 'on') {
            $fields['sexta'] = 1;
            array_push($diasSemanaDisponivel, 5);
        } else {
            $fields['sexta'] = 0;
        }
        if ($this->request->getPost('sabado') == 'on') {
            $fields['sabado'] = 1;
            array_push($diasSemanaDisponivel, 6);
        } else {
            $fields['sabado'] = 0;
        }
        if ($this->request->getPost('domingo') == 'on') {
            $fields['domingo'] = 1;
            array_push($diasSemanaDisponivel, 7);
        } else {
            $fields['domingo'] = 0;
        }




        //caso não seja selecionado nenhum dia da semana, macar todos

        if (empty($diasSemanaDisponivel)) {
            /*array_push($diasSemanaDisponivel, 1);
            array_push($diasSemanaDisponivel, 2);
            array_push($diasSemanaDisponivel, 3);
            array_push($diasSemanaDisponivel, 4);
            array_push($diasSemanaDisponivel, 5);
             */
            $diasSemanaDisponivel = array(1, 2, 3, 4, 5, 6, 7);
        }



        $this->validation->setRules([
            'codDepartamento' => ['label' => 'Departamento', 'rules' => 'required|max_length[11]'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'permit_empty'],
            'dataInicio' => ['label' => 'DataInicio', 'rules' => 'permit_empty|valid_date'],
            'horaInicio' => ['label' => 'HoraInicio', 'rules' => 'required'],
            'dataEncerramento' => ['label' => 'DataEncerramento', 'rules' => 'permit_empty|valid_date'],
            'horaEncerramento' => ['label' => 'Hora encerramento', 'rules' => 'required'],
            'tempoAtendimento' => ['label' => 'TempoAtendimento', 'rules' => 'required|numeric|greater_than[0]'],
            'qtdeAtendentes' => ['label' => 'qtde de Atendentes', 'rules' => 'required|numeric|greater_than[0]'],
            'ordemAtendimento' => ['label' => 'ordemAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'codStatusAgendamento' => ['label' => 'CodStatusAgendamento', 'rules' => 'required|max_length[11]'],
            'codTipoAgendamento' => ['label' => 'CodTipoAgendamento', 'rules' => 'required|max_length[11]'],
            'segunda' => ['label' => 'Segunda', 'rules' => 'permit_empty|numeric|max_length[11]'],
            'terca' => ['label' => 'Terca', 'rules' => 'permit_empty|numeric|max_length[11]'],
            'quarta' => ['label' => 'Quarta', 'rules' => 'permit_empty|numeric|max_length[11]'],
            'quinta' => ['label' => 'Quinta', 'rules' => 'permit_empty|numeric|max_length[11]'],
            'sexta' => ['label' => 'Sexta', 'rules' => 'permit_empty|numeric|max_length[11]'],
            'sabado' => ['label' => 'Sabado', 'rules' => 'permit_empty|numeric|max_length[11]'],
            'domingo' => ['label' => 'Domingo', 'rules' => 'permit_empty|numeric|max_length[11]'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($codConfig = $this->AgendamentoSenhasConfigModel->insert($fields)) {

                //CRIA SLOTS



                $dataInicial = strtotime($fields['dataInicio']);
                $dataFinal = strtotime($fields['dataEncerramento']);

                $dataHoraInicio =  $fields['dataInicio'] . ' ' . $fields['horaInicio'];



                //CONTROLE DE SENHAS

                $ultimoNumeroNormal = $this->AtendimentoSenhasModel->ultimoNumeroNormal(1, $fields['codDepartamento'])->total;



                for ($s = $dataInicial; $s <= $dataFinal; $s = $s + 86400) {

                    $dataHoraInicio = date('Y-m-d', $s) . ' ' . $fields['horaInicio'];
                    $dataHoraEncerramento = date('Y-m-d', $s) . ' ' . $fields['horaEncerramento'];
                    $strtotimeInicial = strtotime($dataHoraInicio);
                    $strtotimeFinal = strtotime($dataHoraEncerramento);

                    $senha=0;
                    for ($x = $strtotimeInicial; $x < $strtotimeFinal; $x = $x + $fields['tempoAtendimento'] * 60) {


                        $inicio_eslote = date('Y-m-d H:i', $x);
                        $fim_eslote = date('Y-m-d H:i', strtotime(date('d-m-Y H:i', $x) . ' + ' . $fields['tempoAtendimento'] . ' minutes'));
                        if (in_array(date('N', strtotime($inicio_eslote)), $diasSemanaDisponivel)) {


                            for ($y=0; $y < $fields['qtdeAtendentes']; $y++){
                                $senha++;
                                $agendamento = array();
                                $agendamento['codConfig'] = $codConfig;
                                $agendamento['codOrganizacao'] = session()->codOrganizacao;
                                $agendamento['codPaciente'] = 0;
                                $agendamento['codDepartamento'] = $this->request->getPost('codDepartamento');
                                $agendamento['codStatus'] = 0;
                                $agendamento['ordemAtendimento'] =  $this->request->getPost('ordemAtendimento');
                                $agendamento['dataInicio'] = $inicio_eslote;
                                $agendamento['dataEncerramento'] = $fim_eslote;
                                $agendamento['codAutor'] = session()->codPessoa;
                                $agendamento['dataCriacao'] = date('Y-m-d H:i');
                                $agendamento['dataAtualizacao'] = date('Y-m-d H:i');


                                //$ultimoNumeroNormal++;
                                //$ultimoNumeroNormal = str_pad($ultimoNumeroNormal, 3, '0', STR_PAD_LEFT);
                                // $agendamento['senha'] = $senha = 'N' . $ultimoNumeroNormal;

                                $ultimoNumeroNormal = str_pad($senha, 3, '0', STR_PAD_LEFT);
                                $agendamento['senha'] = 'N' .$ultimoNumeroNormal;

                                $this->AtendimentoSenhasModel->insert($agendamento);
                            }



                            //VERIFICAR EXISTENCIA DO SLOT
                            //NÃO DEIXA DUPLICAR HORÁRIOS


                            //  $verificaExistenciaAgendamento = $this->AgendamentoSenhasModel->verificaExistenciaAgendamento($this->request->getPost('codEspecialidade'), $this->request->getPost('codEspecialista'), $inicio_eslote);

                            if ($verificaExistenciaAgendamento == NULL) {

                                // $this->AtendimentoSenhasModel->insert($agendamento);
                            }
                        }
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

        $fields['codConfig'] = $this->request->getPost('codConfig');
        $fields['codStatusAgendamento'] = $this->request->getPost('codStatusAgendamento');
        $fields['codTipoAgendamento'] = $this->request->getPost('codTipoAgendamento');
        $fields['ordemAtendimento'] =  $this->request->getPost('ordemAtendimento');

        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['autor'] = session()->codPessoa;


        $this->validation->setRules([
            'codConfig' => ['label' => 'codConfig', 'rules' => 'required|numeric|max_length[11]'],
            'codStatusAgendamento' => ['label' => 'CodStatusAgendamento', 'rules' => 'required|max_length[11]'],
            'codTipoAgendamento' => ['label' => 'CodTipoAgendamento', 'rules' => 'required|max_length[11]'],
            'ordemAtendimento' => ['label' => 'ordemAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

        ]);
        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->AgendamentoSenhasConfigModel->update($fields['codConfig'], $fields)) {

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

        $codConfig = $this->request->getPost('codConfig');

        if (!$this->validation->check($codConfig, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($codConfig !== NULL and  $codConfig !== "") {
                if ($this->AgendamentoSenhasConfigModel->where('codConfig', $codConfig)->delete()) {
                    $this->AgendamentoSenhasConfigModel->removeSlotsNaoAgendados($codConfig);

                    $response['success'] = true;
                    $response['csrf_hash'] = csrf_hash();
                    $response['messages'] = 'Deletado com sucesso';
                } else {

                    $response['success'] = false;
                    $response['messages'] = 'Erro na deleção!';
                }
            } else {
                $response['success'] = false;
                $response['messages'] = 'Erro na deleção!';
            }
        }

        return $this->response->setJSON($response);
    }
}
