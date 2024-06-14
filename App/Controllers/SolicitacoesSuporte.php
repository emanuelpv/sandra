<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;
use App\Models\CategoriasSuporteModel;
use App\Models\TipoSolicitacaoModel;
use App\Models\StatusSuporteModel;
use App\Models\PreferenciasModel;
use App\Models\DepartamentosModel;
use App\Models\AcaoSuporteModel;
use App\Models\SolicitacoesSuporteModel;
use App\Models\NotificacoesFilaModel;
use App\Models\ClassificacaoUrgenciaModel;
use App\Models\ClassificacaoPrioridadeModel;
use App\Models\OrigemSolicitacaoModel;

class SolicitacoesSuporte extends BaseController
{

    protected $solicitacoesSuporteModel;
    protected $pessoasModel;
    protected $organizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

    public function __construct()
    {

        helper('seguranca_helper');
        verificaSeguranca($this, session(), base_url());
        $this->PessoasModel = new PessoasModel();
        $this->NotificacoesFilaModel = new NotificacoesFilaModel();
        $this->SolicitacoesSuporteModel = new SolicitacoesSuporteModel();
        $this->OrganizacoesModel = new OrganizacoesModel();
        $this->StatusSuporteModel = new StatusSuporteModel();
        $this->PreferenciasModel = new PreferenciasModel();
        $this->CategoriasSuporteModel = new CategoriasSuporteModel();
        $this->ClassificacaoUrgenciaModel = new ClassificacaoUrgenciaModel();
        $this->OrigemSolicitacaoModel  = new OrigemSolicitacaoModel();
        $this->TipoSolicitacaoModel = new TipoSolicitacaoModel();
        $this->ClassificacaoPrioridadeModel = new ClassificacaoPrioridadeModel();
        $this->DepartamentosModel = new DepartamentosModel();
        $this->AcaoSuporte = new AcaoSuporteModel();
        $this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
        $this->validation =  \Config\Services::validation();
        $this->codOrganizacao = session()->codOrganizacao;
        $this->organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
    }

    public function index()
    {

        $preferencia = $this->PreferenciasModel->pegaPorCodigoPessoa(session()->codPessoa);
        $data = [
            'controller'        => 'solicitacoesSuporte',
            'title'             => 'Solicitações de Suporte',
            'preferencia' => $preferencia
        ];
        echo view('tema/cabecalho');
        echo view('tema/menu_vertical');
        echo view('tema/menu_horizontal');
        return view('solicitacoesSuporte', $data);
    }

    public function listaPercentualConclusao()
    {

        $result = $this->StatusSuporteModel->listaPercentualConclusao();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function listaDropDownOrigemSolicitacao()
    {

        $result = $this->OrigemSolicitacaoModel->listaDropDown();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function listaDropDownClassificacaoPrioridade()
    {

        $result = $this->ClassificacaoPrioridadeModel->listaDropDown();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function listaDropDownStatusSuporte()
    {

        $result = $this->StatusSuporteModel->listaDropDown();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function listaDropDownClassificacaoUrgencia()
    {

        $result = $this->ClassificacaoUrgenciaModel->listaDropDown();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function salvaPreferenciaSolicitacoes()
    {

        $response = array();
        $codPessoa = session()->codPessoa;

        if (session()->nomeCompleto == 'Administrador') {

            $preferencia = $this->PreferenciasModel->pegaPorCodigoPessoa(0);
        } else {

            $preferencia = $this->PreferenciasModel->pegaPorCodigoPessoa($codPessoa);
        }
        $preferencia = $this->PreferenciasModel->pegaPorCodigoPessoa($codPessoa);

        if ($preferencia !== NULL) {
            $codPreferencia = $preferencia->codPreferencia;


            $fieldsUpdate['codPreferencia'] = $codPreferencia;
            $fieldsUpdate['codPessoa'] = $this->request->getPost('codPessoa');
            $fieldsUpdate['categoriasSolicitacoes'] = json_encode($this->request->getPost('arrayCategoria'));
            $fieldsUpdate['statusSolicitacoes'] = json_encode($this->request->getPost('arrayStatus'));
            $fieldsUpdate['codSolicitante'] = json_encode($this->request->getPost('arrayCodSolicitante'));
            $fieldsUpdate['codResponsavel'] = json_encode($this->request->getPost('codResponsavel'));
            $fieldsUpdate['codDepartamento'] = json_encode($this->request->getPost('arrayCodDepartamento'));
            $fieldsUpdate['periodoSolicitacoes'] = $this->request->getPost('periodoSolicitacoes');
            $fieldsUpdate['autorPreferencia'] = $codPessoa;
            $fieldsUpdate['codOrganizacao'] = session()->codOrganizacao;
            $fieldsUpdate['dataAtualizacao'] = date('Y-m-d H:i');;


            $this->validation->setRules([
                'codPreferencia' => ['label' => 'codPreferencia', 'rules' => 'required|numeric|max_length[11]'],
                'codPessoa' => ['label' => 'CodPessoa', 'rules' => 'required|numeric|max_length[11]'],
                'categoriasSolicitacoes' => ['label' => 'CategoriasSolicitacoes', 'rules' => 'permit_empty'],
                'statusSolicitacoes' => ['label' => 'StatusSolicitacoes', 'rules' => 'permit_empty'],
                'autorPreferencia' => ['label' => 'AutorPreferencia', 'rules' => 'permit_empty'],

            ]);

            if ($this->validation->run($fieldsUpdate) == FALSE) {

                $response['success'] = false;
                $response['messages'] = $this->validation->listErrors();
            } else {

                if ($this->PreferenciasModel->update($codPreferencia, $fieldsUpdate)) {

                    $response['success'] = true;
                    $response['csrf_hash'] = csrf_hash();
                    $response['messages'] = 'Atualizado com sucesso';
                } else {

                    $response['success'] = false;
                    $response['messages'] = 'Erro na atualização!';
                }
            }
        } else {


            $fields['codPessoa'] = $codPessoa;
            $fields['categoriasSolicitacoes'] = json_encode($this->request->getPost('arrayCategoria'));
            $fields['statusSolicitacoes'] = json_encode($this->request->getPost('arrayStatus'));
            $fields['periodoSolicitacoes'] = json_encode($this->request->getPost('periodoSolicitacoes'));
            $fields['codSolicitante'] = json_encode($this->request->getPost('arrayCodSolicitante'));
            $fields['codDepartamento'] = json_encode($this->request->getPost('arrayCodDepartamento'));
            $fields['autorPreferencia'] = $codPessoa;


            $fields['codOrganizacao'] = session()->codOrganizacao;
            $fields['dataAtualizacao'] = date('Y-m-d H:i');




            $this->validation->setRules([
                'codPessoa' => ['label' => 'CodPessoa', 'rules' => 'required|numeric|max_length[11]'],
                'categoriasSolicitacoes' => ['label' => 'CategoriasSolicitacoes', 'rules' => 'permit_empty'],
                'statusSolicitacoes' => ['label' => 'StatusSolicitacoes', 'rules' => 'permit_empty'],
                'periodoSolicitacoes' => ['label' => 'PeriodoSolicitacoes', 'rules' => 'permit_empty'],
                'autorPreferencia' => ['label' => 'AutorPreferencia', 'rules' => 'permit_empty'],

            ]);

            if ($this->validation->run($fields) == FALSE) {

                $response['success'] = false;
                $response['messages'] = $this->validation->listErrors();
            } else {

                if ($this->PreferenciasModel->insert($fields)) {

                    $response['success'] = true;
                    $response['csrf_hash'] = csrf_hash();
                    $response['messages'] = 'Informação inserida com sucesso';
                } else {

                    $response['success'] = false;
                    $response['messages'] = 'Erro na inserção!';
                }
            }
        }

        return $this->response->setJSON($response);
    }

    public function listaDropDownTipoSolicitacao()
    {

        $result = $this->TipoSolicitacaoModel->listaDropDown();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function listaDropDownCategoriasSuporte()
    {

        $categorias = $this->CategoriasSuporteModel->listaDropDown();

        if ($categorias !== NULL) {


            return $this->response->setJSON($categorias);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function listaDropDownDepartamentos()
    {

        $result = $this->DepartamentosModel->listaDropDown();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function listaDropDownResponsaveis()
    {

        $result = $this->PessoasModel->listaDropDownResponsaveis();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function listaDropDownSolicitante()
    {

        $result = $this->PessoasModel->listaDropDownSolicitante();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }





    public function getAll()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->SolicitacoesSuporteModel->pegaTudo();

        foreach ($result as $key => $value) {

            $ops = '<div class="btn-group">';
            $ops .= '	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Editar" onclick="editsolicitacoesSuporte(' . $value->codSolicitacao . ')"><i class="fa fa-edit"></i></button>';
            $ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="removesolicitacoesSuporte(' . $value->codSolicitacao . ')"><i class="fa fa-trash"></i></button>';
            $ops .= '</div>';



            //SLA vs SLI
            $sla = NULL;
            $slaEstourado = NULL;
            if ($value->sla > 0 and $value->sli > 0) {
                if ($value->dataEncerramento !== NULL and $value->codStatusSolicitacao == 5) {
                    $sla =  $this->SolicitacoesSuporteModel->intervaloTempo($value->dataCriacao, $value->dataEncerramento, $value->medidaSLA);
                } else {
                    $sla = $this->SolicitacoesSuporteModel->intervaloTempo($value->dataCriacao, null, $value->medidaSLA);
                }


                if ($value->medidaSLA == 'days') {
                    if ($sla[0]['tempo'] * 24 * 60 >= $value->sla * 24 * 60  * $value->sli / 100) {
                        $slaEstourado = '<span><img style="width:30px" src="' . base_url() . '/imagens/sla.gif"></span>';
                    } else {
                        $slaEstourado = '';
                    }
                } else {
                    if ($sla[0]['tempo']  >= $value->sla * $value->sli / 100) {
                        $slaEstourado = '<span><img style="width:30px" src="' . base_url() . '/imagens/sla.gif"></span>';
                    } else {
                        $slaEstourado = '';
                    }
                }

                if ($sla[0]['tempo'] >= 0) {
                    $valorSLA = $sla[0]['tempo'] . $sla[0]['titulo'] . $slaEstourado;
                    $tempoTranscorrido = $value->sla . $sla[0]['titulo'];
                } else {
                    $valorSLA = "";
                    $tempoTranscorrido = "";
                }
            }








            if ($value->codStatusSolicitacao == 5) {

                if ($value->notaAtendimento !== NULL) {
                    $notaAtendimento = '<div>';
                    for ($x = 1; $x <= 5; $x++) {

                        if ($x <= $value->notaAtendimento) {
                            $notaAtendimento .= '<span><img style="width:10px" src="' . base_url() . '/imagens/estrelaDourada.png"></span>';
                        } else {
                            $notaAtendimento .= '<span><img style="width:10px" src="' . base_url() . '/imagens/estrelaCinza.png"></span>';
                        }
                    }
                    $notaAtendimento .= '</div>';
                } else {
                    if (session()->codPessoa !== $value->codSolicitante) {
                        $notaAtendimento = '<a href="#" data-toggle="tooltip" data-placement="top" title="Somente o autor da solicitação poderá avaliá-la" onclick="apenasOAutor();"><div>';
                    } else {
                        $notaAtendimento = '<a href="#" data-toggle="tooltip" data-placement="top" title="Gistaria de avaliar a qualidade do atendimento?" onclick="avaliarAgora(' . $value->codSolicitacao . ',' . $value->codResponsavel . ',\'' . $value->ResponsavelTecnico . '\');"><div>';
                    }
                    $notaAtendimento .= '<span><img style="width:10px" data-toggle="tooltip" data-placement="top" title="Muito Ruim" src="' . base_url() . '/imagens/estrelaCinza.png"></span>';
                    $notaAtendimento .= '<span><img style="width:10px" data-toggle="tooltip" data-placement="top" title="Ruim" src="' . base_url() . '/imagens/estrelaCinza.png"></span>';
                    $notaAtendimento .= '<span><img style="width:10px" data-toggle="tooltip" data-placement="top" title="Bom" src="' . base_url() . '/imagens/estrelaCinza.png"></span>';
                    $notaAtendimento .= '<span><img style="width:10px" data-toggle="tooltip" data-placement="top" title="Muito Bom" src="' . base_url() . '/imagens/estrelaCinza.png"></span>';
                    $notaAtendimento .= '<span><img style="width:10px" data-toggle="tooltip" data-placement="top" title="Ótimo" src="' . base_url() . '/imagens/estrelaCinza.png"></span>';
                    $notaAtendimento .= '</div>';
                    $notaAtendimento .= '<small class="badge badge-secondary"><i class="far fa-clock"></i> Avalie</small>';
                    $notaAtendimento .= '</a>';
                }
            } else {
                $notaAtendimento = '';
            }
            $spin = "";
            if ($value->codStatusSolicitacao == 2) {
                $spin = '<span style="width: 1.5rem; height: 1.5rem;" class="spinner-grow text-light" role="status"></span>';
            }
            $data['data'][$key] = array(
                $value->codSolicitacao . $spin,
                $value->descricaoSolicitacao,
                $value->descricaoCategoriaSuporte,
                $value->nomeExibicao,
                $value->descricaoDepartamento,
                $value->ResponsavelTecnico . $notaAtendimento,
                date('d/m/Y H:i', strtotime($value->dataCriacao)),
                $tempoTranscorrido,
                $valorSLA,
                $value->descricaoStatusSuporte,
                $value->descricaoTipoSolicitacao,
                $value->percentualConclusao . "%",

                $ops,
            );
        }

        return $this->response->setJSON($data);
    }

    public function salvarAvaliacao()
    {
        $response = array();

        $fields['notaAtendimento'] = $this->request->getPost('valorlAvaliacao');
        $codSolicitacao = $this->request->getPost('codSolicitacao');


        if (!$this->validation->check($codSolicitacao, 'required|numeric')) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->SolicitacoesSuporteModel->update($codSolicitacao, $fields)) {
                $response['success'] = true;
                $response['csrf_hash'] = csrf_hash();
                $response['messages'] = 'Avaliação realizada com sucesso';
            }
        }




        return $this->response->setJSON($response);
    }



    public function getAcoes($codSolicitacao = null)
    {
        $response = array();

        $data['data'] = array();
        if ($this->request->getPost('codSolicitacao') !== NULL) {
            $codSolicitacao =  $this->request->getPost('codSolicitacao');
        }
        $result = $this->SolicitacoesSuporteModel->getAcoes($codSolicitacao);
        $ultimaMensagem = $this->SolicitacoesSuporteModel->ultimaMensagem($codSolicitacao);

        $html = "";
        $html .= '
        <div class="card direct-chat direct-chat-primary">
        	<div class="card-body">
                <div id="direct-chat-messages" class="direct-chat-messages">';
        if (!empty($result)) {

            foreach ($result as $acao) {

                if ($acao->fotoPerfil == NULL) {

                    $noFoto = "arquivos/imagens/pessoas/no_image.jpg?";
                    $fotoPerfil = '<img class="direct-chat-img" src="' . $noFoto . '">';
                } else {
                    $fotoPerfil = '<img class="direct-chat-img" src="' .  'arquivos/imagens/pessoas/' . $acao->fotoPerfil . '">';
                }

                if ($acao->codSolicitante == $acao->codPessoaMensageiro) {
                    $ladoChat = '';
                    $ladodata = 'right';
                    $ladoMsg = 'left';
                } else {
                    $ladoChat = 'right';
                    $ladodata = 'left';
                    $ladoMsg = 'right';
                }
                //É O AUTOR


                $html .= '
                <div id= "contentChat" class="direct-chat-msg ' . $ladoChat . '">
                    <div class="direct-chat-infos clearfix">
                        <span class="direct-chat-name float-' . $ladoMsg . '">' . $acao->mensageiro . '</span>
                        <span class="direct-chat-timestamp float-' . $ladodata . '">' . date('d/m/Y H:i', strtotime($acao->dataInicioAcao)) . '</span>
                    </div>
                    ' . $fotoPerfil . '
                    <div class="direct-chat-text">
                        ' . $acao->descricaoAcao . '
                    </div>
                </div>';
            }
        } else {
            $html .= '
            <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-exclamation-triangle"></i> ATENÇÃO!</h5>
           AINDA NÃO HOUVE AÇÃO REGISTRADA.
          </div>';
        }


        $html .= '  </div>
                 </div>
             </div>';


        $response['success'] = true;
        $response['csrf_hash'] = csrf_hash();

        if ($ultimaMensagem->ultimaMensagem == NULL) {
            $response['ultimaMensagem'] = "";
        } else {
            $response['ultimaMensagem'] = $ultimaMensagem->ultimaMensagem;
        }
        $response['html'] =  $html;

        return $this->response->setJSON($response);
    }


    public function getOne()
    {
        $response = array();

        $id = $this->request->getPost('codSolicitacao');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->SolicitacoesSuporteModel->pegaPorCodigo($id);


            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function ultimaAcao()
    {
        $response = array();

        $id = $this->request->getPost('codSolicitacao');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->SolicitacoesSuporteModel->ultimaAcao($id);

            if ($data !== NULL) {
                return $this->response->setJSON($data);
            } else {
                $data = array(
                    'descricaoAcao' => null,
                    'autorAcaoText' => null,
                    'dataHoraAcaoText' => null,
                );
                return $this->response->setJSON($data);
            }
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }



    public function add()
    {
        $codOrganizacao = session()->codOrganizacao;
        $response = array();

        $equipesTecnicas = session()->equipesTecnicas;

        if (count($equipesTecnicas) > 0) {
            $codPessoa = $this->request->getPost('codSolicitante');
            $codOrigemSolicitacao =  $this->request->getPost('codOrigemSolicitacao');
            $fields['codSolicitante'] = $codPessoa;
            $fields['codOrigemSolicitacao'] = $codOrigemSolicitacao;
        } else {

            $codPessoa = session()->codPessoa;
            $codOrigemSolicitacao =  1;
            $fields['codSolicitante'] = $codPessoa;
            $fields['codOrigemSolicitacao'] = $codOrigemSolicitacao;
        }

        $pessoa =  $this->PessoasModel->pegaPessoaPorCodPessoa($codPessoa);

        $email = $pessoa->emailPessoal;

        $fields['descricaoSolicitacao'] = str_replace("</p>", "", str_replace("<p>", "", $this->request->getPost('descricaoSolicitacao')));

        $fields['codSolicitacao'] = $this->request->getPost('codSolicitacao');
        $fields['codOrganizacao'] = $codOrganizacao;
        $fields['codCategoriaSuporte'] = $this->request->getPost('codCategoriaSuporte');

        $fields['dataCriacao'] = date('Y-m-d H:i');
        $fields['codDepartamentoSolicitante'] = $pessoa->codDepartamento;
        $fields['codStatusSolicitacao'] = 1;
        $fields['codTipoSolicitacao'] = $this->request->getPost('codTipoSolicitacao');
        $fields['codUrgencia'] = $this->request->getPost('codUrgencia');
        $fields['codPrioridade'] = $this->request->getPost('codPrioridade');
        $fields['percentualConclusao'] = 0;



        $this->validation->setRules([
            'codCategoriaSuporte' => ['label' => 'CodCategoriaSuporte', 'rules' => 'required|bloquearReservado|max_length[11]'],
            'descricaoSolicitacao' => [
                'label' => 'Descrição ',
                'rules' => 'required|min_length[20]|bloquearReservado',
                'errors' => [
                    'required' => 'Você deve informar a solicitação',
                    'min_length' => 'Forneça mais detalhe sobre a solicitação',
                ],
            ],
            'codSolicitante' => ['label' => 'Solicitante', 'rules' => 'required|bloquearReservado|numeric'],
            'codStatusSolicitacao' => ['label' => 'Status', 'rules' => 'required|bloquearReservado|max_length[11]'],
            'codTipoSolicitacao' => ['label' => 'CodTipoSolicitacao', 'rules' => 'required|bloquearReservado|max_length[11]'],
            'codUrgencia' => ['label' => 'Urgência', 'rules' => 'required|bloquearReservado|max_length[11]'],
            'codPrioridade' => ['label' => 'Prioridade', 'rules' => 'required|bloquearReservado|max_length[11]'],
            'percentualConclusao' => ['label' => 'Percentual conclusao', 'rules' => 'required|bloquearReservado'],
            'codOrigemSolicitacao' => ['label' => 'Origem Solicitação', 'rules' => 'required|bloquearReservado'],


        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($codSolicitacao = $this->SolicitacoesSuporteModel->insert($fields)) {


                $response['success'] = true;
                $response['csrf_hash'] = csrf_hash();
                $response['messages'] = 'Informação inserida com sucesso';

                //ENVIAR NOTIFICAÇÃO
                if ($email !== NULL and $pessoa->nomeExibicao !== NULL) {

                    $email = removeCaracteresIndesejadosEmail($email);
                    $conteudo = "
                   <div> Caro senhor(a), " . $pessoa->nomeExibicao . ",</div>";
                    $conteudo .= "<div>Sua demanda foi registrada com sucesso em " . date("d/m/Y  H:i") . ", Protocolo Nr " . $codSolicitacao . ".</div>";

                    $conteudo .= "<span style='margin-top:15px;'>DEMANDA:</span><span style='font-size:15px;font-style: italic;'>" . $fields['descricaoSolicitacao'] . "</span>";

                    $conteudo .= "<div style='margin-top:15px'>Atenciosamente,</div>";
                    $conteudo .= "<div>" . session()->siglaOrganizacao . "</div>";

                    $resultadoEmail = @email($email, 'DEMANDA REGISTRADA', $conteudo);
                    if ($resultadoEmail == false) {

                        //ADICIONAR NOTIFICAÇÃO ANA FILA EM CASO DE FALHA
                        @addNotificacoesFila($conteudo, $email, $email, 1);
                    }


                    //ENVIAR SMS
                    $celular = removeCaracteresIndesejados($pessoa->celular);
                    $conteudoSMS = "
                    Caro senhor(a), " . $pessoa->nomeExibicao . ",";
                    $conteudoSMS .= " sua solicitação foi registrada com sucesso em " . date("d/m/Y  H:i") . ", através do Protocolo Nr " . $codSolicitacao . ".";

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
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na inserção!';
            }
        }



        return $this->response->setJSON($response);
    }

    public function reabrirSolicitacao()
    {



        if ($this->request->getPost('descricaoAcao') == '<p><br></p>') {
            $response['success'] = false;
            $response['messages'] = 'É necessário descrever a ação';
            return $this->response->setJSON($response);
        }

        //FECHAR TAMBEM A SOLICITAÇÃO PRINCIPAL
        $fieldsSolicitacoes['codStatusSolicitacao'] = 6;
        $fieldsSolicitacoes['percentualConclusao'] = 0;
        $fieldsSolicitacoes['dataAtualicacao'] = date('Y-m-d H:i');
        $fieldsSolicitacoes['dataEncerramento'] = null;

        if (!$this->validation->check($this->request->getPost('codSolicitacao'), 'required|numeric')) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->SolicitacoesSuporteModel->update($this->request->getPost('codSolicitacao'), $fieldsSolicitacoes)) {

                $acao['percentualConclusao'] = 0;
                $acao['codSolicitacao'] = $this->request->getPost('codSolicitacao');
                $acao['codStatusSolicitacao'] = 6;
                $acao['codPessoa'] = session()->codPessoa;
                $acao['descricaoAcao'] = 'Solicitação reaberta';
                $acao['dataInicio'] = date('Y-m-d H:i');
                $acao['codTipoAcao'] = 3;
                $this->AcaoSuporte->insert($acao);

                $response['success'] = true;
                $response['csrf_hash'] = csrf_hash();
                $response['messages'] = 'Solicitação reaberta';
            }
        }


        return $this->response->setJSON($response);
    }


    public function salvaAcao()
    {



        if ($this->request->getPost('descricaoAcao') == '<p><br></p>') {
            $response['success'] = false;
            $response['messages'] = 'É necessário descrever a ação';
            return $this->response->setJSON($response);
        }


        $equipesTecnicas = session()->equipesTecnicas;
        $response = array();
        $data = $this->SolicitacoesSuporteModel->pegaPorCodigo($this->request->getPost('codSolicitacao'));





        if (count($equipesTecnicas) > 0) {
            $fields['percentualConclusao'] = $data->percentualConclusao;
        }
        $fields['codSolicitacao'] = $this->request->getPost('codSolicitacao');
        $fields['codPessoa'] = session()->codPessoa;
        $fields['descricaoAcao'] = $this->request->getPost('descricaoAcao');

        $fields['dataInicio'] = date('Y-m-d H:i');


        $this->validation->setRules([
            'codSolicitacao' => ['label' => 'codSolicitacao', 'rules' => 'required'],
            'codPessoa' => ['label' => 'Código da Pessoa', 'rules' => 'required'],
            'descricaoAcao' => [
                'label' => 'Descrição ',
                'rules' => 'required|min_length[1]',
                'errors' => [
                    'required' => $this->request->getPost('codSolicitacao'),
                    'min_length' => 'Forneça mais detalhe sobre a ação',
                ],
            ],

        ]);


        //ATUALIZA AÇÕES
        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->AcaoSuporte->insert($fields)) {

                $response['success'] = true;
                $response['csrf_hash'] = csrf_hash();
                $response['messages'] = 'Ação registrada com sucesso';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na atualização!';
            }
        }


        return $this->response->setJSON($response);
    }

    public function edit()
    {

        $response = array();

        $fields['codSolicitacao'] = $this->request->getPost('codSolicitacao');
        $data = $this->SolicitacoesSuporteModel->pegaPorCodigo($this->request->getPost('codSolicitacao'));

        $equipesTecnicas = session()->equipesTecnicas;

        //VERIFICAR SE STATUS MUDOU

        if (count($equipesTecnicas) > 0) {
            if ($data->codStatusSolicitacao !== $this->request->getPost('codStatusSolicitacao')) {

                $statusSuporte = $this->StatusSuporteModel->pegaPorCodigo($this->request->getPost('codStatusSolicitacao'));
                $statusMudou['codStatusSolicitacao'] = $this->request->getPost('codStatusSolicitacao');
                $statusMudou['codSolicitacao'] = $this->request->getPost('codSolicitacao');
                $statusMudou['codPessoa'] = session()->codPessoa;
                $statusMudou['descricaoAcao'] = 'Status da solicitação mudou para ' . mb_strtoupper($statusSuporte->descricaoStatusSuporte, "utf-8");
                $statusMudou['dataInicio'] = date('Y-m-d H:i');
                $statusMudou['codTipoAcao'] = 2;
                $this->AcaoSuporte->insert($statusMudou);
                $statusAtendimentoMudou['dataInicio'] = date('Y-m-d H:i');
            }
        }



        //VERIFICAR SE RESPONSAVEL MUDOU

        if (count($equipesTecnicas) > 0) {
            if ($data->codResponsavel !== $this->request->getPost('codResponsavel')) {

                $pessoa =  $this->PessoasModel->pegaPessoaPorCodPessoa($this->request->getPost('codResponsavel'));

                $PercentualConclusaoMudou['percentualConclusao'] = $this->request->getPost('percentualConclusao');
                $PercentualConclusaoMudou['codSolicitacao'] = $this->request->getPost('codSolicitacao');
                $PercentualConclusaoMudou['codPessoa'] = session()->codPessoa;
                if ($data->codResponsavel == NULL and $data->codResponsavel == '' and $data->codResponsavel == 0) {
                    $PercentualConclusaoMudou['descricaoAcao'] = 'Técnico definido foi ' . $pessoa->nomeExibicao;
                } else {
                    $PercentualConclusaoMudou['descricaoAcao'] = 'Técnico mudou para ' . $pessoa->nomeExibicao;
                }
                if ($data->dataInicio == NULL) {
                    $PercentualConclusaoMudou['dataInicio'] = date('Y-m-d H:i');
                }
                $PercentualConclusaoMudou['codTipoAcao'] = 5;
                $this->AcaoSuporte->insert($PercentualConclusaoMudou);
            }
        }





        if ($equipesTecnicas > 0) {

            if ($this->request->getPost('codStatusSolicitacao') !== NULL) {
                $fields['codStatusSolicitacao'] = $this->request->getPost('codStatusSolicitacao');
            }

            if ($this->request->getPost('codResponsavel') !== NULL) {
                $fields['codResponsavel'] = $this->request->getPost('codResponsavel');
            }
            if ($this->request->getPost('codUrgencia') !== NULL) {
                $fields['codUrgencia'] = $this->request->getPost('codUrgencia');
            }

            if ($this->request->getPost('codPrioridade') !== NULL) {
                $fields['codPrioridade'] = $this->request->getPost('codPrioridade');
            }
        }


        if ($this->request->getPost('codCategoriaSuporte') !== NULL) {
            $fields['codCategoriaSuporte'] = $this->request->getPost('codCategoriaSuporte');
        }

        if ($this->request->getPost('codTipoSolicitacao') !== NULL) {
            $fields['codTipoSolicitacao'] = $this->request->getPost('codTipoSolicitacao');
        }


        if ($fields['codStatusSolicitacao'] == 6) {
            $fields['percentualConclusao'] = 0;
            $fields['dataEncerramento'] = null;
        } else {

            if ($this->request->getPost('percentualConclusao') !== NULL) {
                $fields['percentualConclusao'] = $this->request->getPost('percentualConclusao');
            }
        }

        //INICIO DA DEMANDA
        if (!in_array($fields['codStatusSolicitacao'], array(1, 5, 6))) {
            if ($data->dataInicio == NULL) {
                $fields['dataInicio'] = date('Y-m-d H:i');
            }
        }

        //ENCERRAMENTO CONDICIONAL AUTOMÁTICO

        if (count($equipesTecnicas) > 0) {
            if ($this->request->getPost('codStatusSolicitacao') == 5) {
                //altera raiz
                $fieldsSolicitacoesEncerraTudo['codStatusSolicitacao'] = 5;
                $fieldsSolicitacoesEncerraTudo['percentualConclusao'] = 100;
                $fieldsSolicitacoesEncerraTudo['dataEncerramento'] = date('Y-m-d H:i');
                $fieldsSolicitacoesEncerraTudo['solucao'] =  '<p>Atendimento Encerrado</p>';
                
                
                if ($this->SolicitacoesSuporteModel->update($this->request->getPost('codSolicitacao'), $fieldsSolicitacoesEncerraTudo)) {
                }


            }
        }



        $this->validation->setRules([
            'codSolicitacao' => ['label' => 'codSolicitacao', 'rules' => 'required|numeric|max_length[11]'],
            'codCategoriaSuporte' => ['label' => 'CodCategoriaSuporte', 'rules' => 'required|numeric|max_length[11]'],
            'codStatusSolicitacao' => ['label' => 'Status', 'rules' => 'permit_empty|bloquearReservado'],
            'codTipoSolicitacao' => ['label' => 'CodTipoSolicitacao', 'rules' => 'required|bloquearReservado|max_length[11]'],
            'codUrgencia' => ['label' => 'Urgência', 'rules' => 'permit_empty|bloquearReservado'],
            'codPrioridade' => ['label' => 'Prioridade', 'rules' => 'permit_empty|bloquearReservado'],
            'percentualConclusao' => ['label' => 'Percentual conclusao', 'rules' => 'permit_empty|bloquearReservado'],
            'codResponsavel' => ['label' => 'Responsável', 'rules' => 'permit_empty|bloquearReservado'],
            'solucao' => ['label' => 'Solucao', 'rules' => 'permit_empty|bloquearReservado'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->SolicitacoesSuporteModel->update($fields['codSolicitacao'], $fields)) {

                $response['success'] = true;
                $response['csrf_hash'] = csrf_hash();
                $response['codStatusSolicitacao'] = $this->request->getPost('codStatusSolicitacao');
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

        $id = $this->request->getPost('codSolicitacao');

        if (!$this->validation->check($id, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($this->SolicitacoesSuporteModel->where('codSolicitacao', $id)->delete()) {

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
