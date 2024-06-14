<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;
use App\Models\AgendamentosExamesListaModel;
use App\Models\AgendamentosExamesModel;
use App\Models\AgendamentosExamesConfigModel;

class AgendamentosExamesConfig extends BaseController
{

    protected $AgendamentosExamesConfigModel;
    protected $AgendamentosExamesListaModel;
    protected $pessoasModel;
    protected $AgendamentosExamesModel;
    protected $OrganizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

    public function __construct()
    {

        helper('seguranca_helper');
        verificaSeguranca($this, session(), base_url());
        $this->AgendamentosExamesConfigModel = new AgendamentosExamesConfigModel();
        $this->AgendamentosExamesModel = new AgendamentosExamesModel();
        $this->AgendamentosExamesListaModel = new AgendamentosExamesListaModel();
        $this->OrganizacoesModel = new OrganizacoesModel();
        $this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
        $this->validation =  \Config\Services::validation();
        $this->codOrganizacao = session()->codOrganizacao;
        $this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
    }

    public function listaDropDownStatusExame()
    {

        $result = $this->AgendamentosExamesListaModel->listaDropDownStatusExame();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }




    public function listaVagasAvulsas()
    {


        $response = array();

        $filtro['codExameLista'] = $this->request->getPost('codEspecialidade');
        $filtro['codEspecialista'] = $this->request->getPost('codEspecialista');
        $filtro['dataInicio'] = $this->request->getPost('dataInicio');
        $filtro['dataEncerramento'] = $this->request->getPost('dataEncerramento');

        session()->set('filtroExameLista', $filtro);

        $response = array();

        $agendamentosExames = $this->AgendamentosExamesModel->cancelamentosAgendamentosExamesPorEspecialidade();


        if ($agendamentosExames == 'noEspecialidade') {

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
        foreach ($agendamentosExames as $especialista) {
            if (!in_array($especialista->codEspecialista, $especialistas) and $especialista->codEspecialista !== NULL) {
                array_push($especialistas, $especialista->codEspecialista);
            }
        }

        $slotsLivres = '';
        $slotsLivres .= '<div class="row">';
        foreach ($especialistas as $especialista) {


            $dadosEspecialista = $this->AgendamentosExamesModel->pegaPessoa($especialista);


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
            foreach ($agendamentosExames as  $key => $exame) {
                if ($especialista == $exame->codEspecialista) {
                    $hora = date('H:i', strtotime($exame->dataInicio));
                    $diaMes = date('d/m', strtotime($exame->dataInicio));
                    $diaSenma = diaSemanaAbreviado($exame->dataInicio);

                    $botao = 'btn-outline-primary';

                    $nomeStatus = $exame->nomeStatus;

                    if ($exame->codStatus == 0) {
                        $botao = 'btn-success';
                    }
                    if ($exame->codStatus == 1) {
                        $botao = 'btn-info';
                        $nomeStatus = 'MARCADO';
                    }

                    if ($exame->codStatus == 4) {
                        $botao = 'btn-dark';
                    }





                    $slotsLivres .= '
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <button type="button" onclick="cancelarExame(' . $exame->codExame . ')" class="btn btn-block ' . $botao . ' btn-lg">
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






    public function listaDropDownTipoExame()
    {

        $result = $this->AgendamentosExamesListaModel->listaDropDownTipoExame();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }
    public function listaDropDownEspecialistasDisponivelMarcacao()
    {

        $codExameLista = $this->request->getPost('codExameLista');
        $result = $this->AgendamentosExamesListaModel->listaDropDownEspecialistasDisponivelMarcacao($codExameLista);

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function listaDropDownAgendamentosExamesListaDisponivelMarcacao()
    {

        $result = $this->AgendamentosExamesListaModel->listaDropDownAgendamentosExamesListaDisponivelMarcacaoExame();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function index()
    {
        $permissao = verificaPermissao('agendamentosExamesConfig', 'listar');
        if ($permissao == 0) {
            echo mensagemAcessoNegado(session()->organizacoes);
            $this->LogsModel->inserirLog('Acesso indevido ao Módulo "AgendamentosExamesConfig"', session()->codPessoa);
            exit();
        }

        $data = [
            'controller'        => 'agendamentosExamesConfig',
            'title'             => 'Configuração de Exames'
        ];
        echo view('tema/cabecalho');
        echo view('tema/menu_vertical');
        echo view('tema/menu_horizontal');
        return view('agendamentosExamesConfig', $data);
    }

    public function getAll()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->AgendamentosExamesConfigModel->pegaTudo();

        if ($this->request->getPost('programadas') == 1) {
            $result = $this->AgendamentosExamesConfigModel->pegaAgendasProgramadas();
        }

        if ($this->request->getPost('liberadasHoje') == 1) {
            $result = $this->AgendamentosExamesConfigModel->pegaAgendasLiberadasHoje();
        }

        if ($this->request->getPost('buscaAvancada') == 1) {
            $codEspecialidade = $this->request->getPost('codEspecialidade');
            $codEspecialista = $this->request->getPost('codEspecialista');
            $dataInicio = $this->request->getPost('dataInicio');
            $dataEncerramento = $this->request->getPost('dataEncerramento');
            $result = $this->AgendamentosExamesConfigModel->buscaAvancada($codEspecialidade, $codEspecialista, $dataInicio, $dataEncerramento);
        }

        foreach ($result as $key => $value) {


            //ESTATISTICAS DE VAGAS
            $vagasCriadas = $this->AgendamentosExamesConfigModel->vagasCriadas($value->codConfig)->total;
            $vagasAbertas = $this->AgendamentosExamesConfigModel->vagasAbertas($value->codConfig)->total;

            $criadoPor =
                '<div>' . $value->nomeAutor . '</div>
            <div> Em ' . date('d/m/Y H:i', strtotime($value->dataCriacao)) . '</div>';


            $ops = '<div class="btn-group">';
            $ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editagendamentosExamesConfig(' . $value->codConfig . ')"><i class="fa fa-edit"></i></button>';
            $ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeagendamentosExamesConfig(' . $value->codConfig . ')"><i class="fa fa-trash"></i></button>';
            $ops .= '</div>';

            if ($value->codStatusExame == 1) {
                $btnLiberar = '<div><button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="mudaStatusAgendamentosExamesConfig(' . $value->codConfig . ',0)"><i class="fa fa-lock"></i>Bloquear</button></div>';
            } else {
                $btnLiberar = '<div><button type="button" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="mudaStatusAgendamentosExamesConfig(' . $value->codConfig . ',1)"><i class="fa fa-check"></i>Liberar</button></div>';
            }

            if ($value->ordemAtendimento == 1) {
                $ordemAtendimento = 'Hora marcada';
            } else {
                $ordemAtendimento = 'Ordem de chegada';
            }
            $periodo = "De " . date('d/m/Y', strtotime($value->dataInicio)) . " até " . date('d/m/Y', strtotime($value->dataEncerramento)) . " Das " . date('H:i', strtotime($value->horaInicio)) . " às " . date('H:i', strtotime($value->horaEncerramento)) . " (" . $ordemAtendimento . ")";
            $data['data'][$key] = array(
                $value->codConfig,
                $value->descricaoExameLista . '<div>' . $value->nomeExibicao . '</div>',
                $periodo,
                $value->tempoAtendimento . " Min",
                $value->intervaloAtendimento . " Min",
                $value->nomeStatus . $btnLiberar,
                $value->nomeTipo,
                $vagasCriadas,
                $vagasAbertas,
                $criadoPor,

                $ops,
            );
        }

        return $this->response->setJSON($data);
    }

    public function mudaStatusAgendamentosExamesConfig()
    {

        $response = array();

        $fields['codConfig'] = $this->request->getPost('codConfig');
        $fields['codStatusExame'] = $this->request->getPost('codStatusExame');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['autor'] = session()->codPessoa;



        $this->validation->setRules([
            'codConfig' => ['label' => 'codConfig', 'rules' => 'required|numeric|max_length[11]'],
            'codStatusExame' => ['label' => 'codStatusExame', 'rules' => 'required|max_length[11]'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

        ]);
        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->AgendamentosExamesConfigModel->update($fields['codConfig'], $fields)) {

                $response['success'] = true;
                $response['csrf_hash'] = csrf_hash();
                $response['messages'] = 'Status atualizado com sucesso';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na atualização!';
            }
        }

        return $this->response->setJSON($response);
    }

    public function getOne()
    {
        $response = array();

        $id = $this->request->getPost('codConfig');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->AgendamentosExamesConfigModel->pegaPorCodigo($id);

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function add()
    {

        sleep(2);
        $response = array();

        $fields['codOrganizacao'] = session()->codOrganizacao;
        $fields['codExameLista'] = $this->request->getPost('codExameLista');
        $fields['codEspecialista'] = $this->request->getPost('codEspecialista');
        $fields['codLocal'] = $this->request->getPost('codLocal');
        $fields['dataInicio'] = $this->request->getPost('dataInicio');
        $fields['horaInicio'] = $this->request->getPost('horaInicio');
        $fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');
        $fields['horaEncerramento'] = $this->request->getPost('horaEncerramento');
        $fields['tempoAtendimento'] = $this->request->getPost('tempoAtendimento');
        $fields['intervaloAtendimento'] = $this->request->getPost('intervaloAtendimento');
        $fields['codStatusExame'] = $this->request->getPost('codStatusExame');
        $fields['codTipoExame'] = $this->request->getPost('codTipoExame');
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


        if ($fields['intervaloAtendimento'] < 0) {
            $response['success'] = false;
            $response['messages'] = 'Intervalo de atendimento não pode ser negativo';
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
            'codLocal' => ['label' => 'Local de Atendimento', 'rules' => 'required|max_length[11]'],
            'codExameLista' => ['label' => 'ExameLista', 'rules' => 'required|max_length[11]'],
            'codEspecialista' => ['label' => 'Especialista', 'rules' => 'required|max_length[11]'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'permit_empty'],
            'dataInicio' => ['label' => 'DataInicio', 'rules' => 'permit_empty|valid_date'],
            'horaInicio' => ['label' => 'HoraInicio', 'rules' => 'required'],
            'dataEncerramento' => ['label' => 'DataEncerramento', 'rules' => 'permit_empty|valid_date'],
            'horaEncerramento' => ['label' => 'Hora encerramento', 'rules' => 'required'],
            'tempoAtendimento' => ['label' => 'TempoAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'ordemAtendimento' => ['label' => 'ordemAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'intervaloAtendimento' => ['label' => 'IntervaloAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'codStatusExame' => ['label' => 'CodStatusExame', 'rules' => 'required|max_length[11]'],
            'codTipoExame' => ['label' => 'CodTipoExame', 'rules' => 'required|max_length[11]'],
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


            if ($codConfig = $this->AgendamentosExamesConfigModel->insert($fields)) {

                //CRIA SLOTS


                $dataInicial = strtotime($fields['dataInicio']);
                $dataFinal = strtotime($fields['dataEncerramento']);

                $dataHoraInicio =  $fields['dataInicio'] . ' ' . $fields['horaInicio'];


                for ($s = $dataInicial; $s <= $dataFinal; $s = $s + 86400) {

                    $dataHoraInicio = date('Y-m-d', $s) . ' ' . $fields['horaInicio'];
                    $dataHoraEncerramento = date('Y-m-d', $s) . ' ' . $fields['horaEncerramento'];
                    $strtotimeInicial = strtotime($dataHoraInicio);
                    $strtotimeFinal = strtotime($dataHoraEncerramento);

                    for ($x = $strtotimeInicial; $x <= $strtotimeFinal; $x = $x + $fields['tempoAtendimento'] * 60) {

                        $inicio_eslote = date('Y-m-d H:i', $x);
                        $fim_eslote = date('Y-m-d H:i', strtotime(date('d-m-Y H:i', $x) . ' + ' . $fields['tempoAtendimento'] . ' minutes'));
                        if (in_array(date('N', strtotime($inicio_eslote)), $diasSemanaDisponivel)) {

                            $exame = array();
                            $exame['codConfig'] = $codConfig;
                            $exame['codOrganizacao'] = session()->codOrganizacao;
                            $exame['codPaciente'] = 0;
                            $exame['codEspecialista'] = $this->request->getPost('codEspecialista');
                            $exame['codExameLista'] = $this->request->getPost('codExameLista');
                            $exame['codStatus'] = 0;
                            $exame['ordemAtendimento'] =  $this->request->getPost('ordemAtendimento');
                            $exame['dataInicio'] = $inicio_eslote;
                            $exame['dataEncerramento'] = $fim_eslote;
                            $exame['codAutor'] = session()->codPessoa;
                            $exame['dataCriacao'] = date('Y-m-d H:i');
                            $exame['dataAtualizacao'] = date('Y-m-d H:i');


                            //VERIFICAR EXISTENCIA DO SLOT
                            //NÃO DEIXA DUPLICAR HORÁRIOS

                            $verificaExistenciaAgendamento = $this->AgendamentosExamesModel->verificaExistenciaAgendamento($this->request->getPost('codExameLista'), $this->request->getPost('codEspecialista'), $inicio_eslote);


                            if ($verificaExistenciaAgendamento == NULL) {
                                $this->AgendamentosExamesModel->insert($exame);
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
        $fields['codLocal'] = $this->request->getPost('codLocal');
        $fields['codStatusExame'] = $this->request->getPost('codStatusExame');
        $fields['codTipoExame'] = $this->request->getPost('codTipoExame');
        $fields['ordemAtendimento'] =  $this->request->getPost('ordemAtendimento');

        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['autor'] = session()->codPessoa;


        $this->validation->setRules([
            'codConfig' => ['label' => 'codConfig', 'rules' => 'required|numeric|max_length[11]'],
            'codLocal' => ['label' => 'Local de Atendimento', 'rules' => 'required|max_length[11]'],
            'codStatusExame' => ['label' => 'CodStatusExame', 'rules' => 'required|max_length[11]'],
            'codTipoExame' => ['label' => 'CodTipoExame', 'rules' => 'required|max_length[11]'],
            'ordemAtendimento' => ['label' => 'ordemAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

        ]);
        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->AgendamentosExamesConfigModel->update($fields['codConfig'], $fields)) {

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

            if ($this->AgendamentosExamesConfigModel->where('codConfig', $codConfig)->delete()) {
                $this->AgendamentosExamesModel->removeSlotsNaoAgendados($codConfig);

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
