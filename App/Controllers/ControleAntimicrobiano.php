<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\ControleAntimicrobianoModel;

class ControleAntimicrobiano extends BaseController
{

    protected $ControleAntimicrobianoModel;
    protected $pessoasModel;
    protected $OrganizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

    public function __construct()
    {

        helper('seguranca_helper');
        verificaSeguranca($this, session(), base_url());
        $this->ControleAntimicrobianoModel = new ControleAntimicrobianoModel();
        $this->OrganizacoesModel = new OrganizacoesModel();
        $this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
        $this->validation =  \Config\Services::validation();
        $this->codOrganizacao = session()->codOrganizacao;
        $this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
    }

    public function index()
    {

        $permissao = verificaPermissao('ControleAntimicrobiano', 'listar');
        if ($permissao == 0) {
            echo mensagemAcessoNegado(session()->organizacoes);
            $this->LogsModel->inserirLog('Acesso indevido ao Módulo "ControleAntimicrobiano"', session()->codPessoa);
            exit();
        }


        $data = [
            'controller'        => 'controleAntimicrobiano',
            'title'             => 'Controle Antimicrobiano'
        ];
        echo view('tema/cabecalho');
        echo view('tema/menu_vertical');
        echo view('tema/menu_horizontal');
        return view('controleAntimicrobiano', $data);
    }

    public function getAll()
    {
        $response = array();
        $codPaciente = $this->request->getPost('codPaciente');


        $data['data'] = array();

        $result = $this->ControleAntimicrobianoModel->pegaTudoPorPaciente($codPaciente);

        foreach ($result as $key => $value) {

            $ops = '<div class="btn-group">';
            $ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editcontroleAntimicrobiano(' . $value->codControleAntimicrobiano . ')"><i class="fa fa-edit"></i></button>';
            $ops .= '	<button type="button" class="btn btn-sm btn-secondary"  data-toggle="tooltip" data-placement="top" title="Imprimir"  onclick="imprimirGuiaAntimicrobiana(' . $value->codControleAntimicrobiano . ')"><i class="fa fa-print"></i></button>';
            $ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removecontroleAntimicrobiano(' . $value->codControleAntimicrobiano . ')"><i class="fa fa-trash"></i></button>';
            $ops .= '</div>';


            $diagnosticos = $this->ControleAntimicrobianoModel->pegaDiagnosticos($value->codAtendimento);

            if ($value->codStatus >= 1) {
                if ($value->dataEncerramento < date("Y-m-d", strtotime(date("Y-m-d")))) {
                    $status = '<span class="right badge badge-info" style="font-size:14px"> Vencida </span>';
                } else {
                    $status = '<span class="right badge badge-success" style="font-size:14px"> Ativa </span>';
                }
            } else {
                $status = '<span class="right badge badge-dark" style="font-size:14px"> Suspensa </span>';
            }


            $autorMotivo = '';
            if ($value->dataSuspensao !== NULL) {
                $autorMotivo = '<div style="color:red"> Suspenso em : ' . date("d/m/Y H:i", strtotime($value->dataSuspensao)) . ' por ' . $value->autorSuspensao . '.</div>';
                $autorMotivo .= '<div style="color:red"> Motivo:' . $value->motivoSuspensaoGuia . '</div>';
            }


            $data['data'][$key] = array(
                $value->codControleAntimicrobiano,
                $value->codAtendimento,
                '<div>' . $value->descricaoItem . $autorMotivo,
                date("d/m/Y", strtotime($value->dataInicio)),
                date("d/m/Y", strtotime($value->dataEncerramento)),
                $diagnosticos->cid,
                $status,
                $value->nomeExibicao,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }

    public function esquemasAntimicrobianos()
    {
        $response = array();

        $data['data'] = array();

        $esquemas = $this->ControleAntimicrobianoModel->esquemasAntimicrobianos();


        foreach ($esquemas as $key => $value) {

            $ops = '';

            $dias = '';
            if ($value->dias > 1) {
                $dias = $value->dias . ' dias';
            }
            if ($value->dias == 1) {
                $dias = $value->dias . ' dia';
            }

            $medicamento = '
            <div style="font-size:16px;">' . $value->qtde . ' ' . $value->descricaoUnidade . '(s) de ' . $value->descricaoItem .  ', ' . $value->freq . 'x/' . $value->descricaoPeriodo . ', via ' . $value->descricaoVia . ', Por ' . $dias . '</div>
            ';

            $hoje = strtotime(date('Y-m-d'));
            $dataEncerramento = strtotime($value->dataEncerramento);
            $dataInicio = strtotime($value->dataInicio);

            $diasAntimicrobiano = round(($hoje - $dataInicio) / 60 / 60 / 24);
            $totalDiasCalculado = round(($dataEncerramento - $dataInicio) / 60 / 60 / 24);
            $diasAntimicrobiano = $diasAntimicrobiano + 1 . '&deg; dia de ' . $totalDiasCalculado;



            $data['data'][$key] = array(
                $value->abreviacaoDepartamento,
                $value->descricaoLocalAtendimento,
                $value->nomeExibicao,
                $value->idade,
                $medicamento,
                $dias,
                $diasAntimicrobiano,
                date('d/m/Y', $dataEncerramento),
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }

    public function getOne()
    {
        $response = array();

        $data = array();

        $id = $this->request->getPost('codControleAntimicrobiano');

        if ($this->validation->check($id, 'numeric')) {

            $data = $this->ControleAntimicrobianoModel->pegaPorCodigo($id);

            $response['codControleAntimicrobiano'] = $data->codControleAntimicrobiano;
            $response['codItem'] = $data->codItem;
            $response['codAtendimento'] = $data->codAtendimento;
            $response['codPaciente'] = $data->codPaciente;
            $response['codAutor'] = $data->codAutor;
            $response['codStatus'] = $data->codStatus;
            $response['dataCriacao'] = $data->dataCriacao;
            $response['dataAtualizacao'] = $data->dataAtualizacao;
            $response['dataInicio'] = $data->dataInicio;
            $response['dataEncerramento'] = $data->dataEncerramento;
            $response['primeiraEscolha'] = $data->primeiraEscolha;
            $response['indicacaoAntibiotico'] = $data->indicacaoAntibiotico;
            $response['tipoInfeccao'] = $data->tipoInfeccao;
            $response['respiratoria'] = $data->respiratoria;
            $response['urinaria'] = $data->urinaria;
            $response['resultadoCultura'] = $data->resultadoCultura;
            $response['peleTecido'] = $data->peleTecido;
            $response['cirurgia'] = $data->cirurgia;
            $response['correnteSanguinea'] = $data->correnteSanguinea;
            $response['outroSitioInfecao'] = $data->outroSitioInfecao;
            $response['faltaMedicamentoFarmacia'] = $data->faltaMedicamentoFarmacia;
            $response['alergiaAntimicrobiano'] = $data->alergiaAntimicrobiano;
            $response['insuficienciaRenal'] = $data->insuficienciaRenal;
            $response['insuficienciaHepatica'] = $data->insuficienciaHepatica;
            $response['outroEsquemaAlternativo'] = $data->outroEsquemaAlternativo;
            $response['justificativaEsquema'] = $data->justificativaEsquema;
            $response['detalheResultadoCultura'] = $data->detalheResultadoCultura;
            $response['solicitouCultura'] = $data->solicitouCultura;
            $response['per'] = $data->per;
            $response['qtde'] = $data->qtde;
            $response['und'] = $data->und;
            $response['via'] = $data->via;
            $response['freq'] = $data->freq;
            $response['dias'] = $data->dias;
            $response['motivoSuspensaoGuia'] = $data->motivoSuspensaoGuia;


            $dias = '';
            if ($data->dias > 1) {
                $dias = $data->dias . ' dias';
            }
            if ($data->dias == 1) {
                $dias = $data->dias . ' dia';
            }
            $aplicacao = '<div style="font-size:20px; font-weight:bold">' . $data->qtde . ' ' . $data->descricaoUnidade . '(s) de ' . $data->descricaoItem . '</div>';
            $aplicacao .= '<div style="font-size:20px;font-weight:bold">Frequência: ' . $data->freq . 'x/' . $data->descricaoPeriodo . ' | ' . $data->descricaoVia . '  | Por ' . $dias . '</div>';
            $response['dados'] = $aplicacao;

            return $this->response->setJSON($response);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function add()
    {

        $response = array();

        /*
        $response['indicacaoAntibiotico'] = $this->request->getPost('indicacaoAntibiotico');
        $response['success'] = true;
        $response['messages'] = 'Informação inserida com sucesso';
        return $this->response->setJSON($response);
        */


        $fields['codControleAntimicrobiano'] = $this->request->getPost('codControleAntimicrobiano');
        $fields['codItem'] = $this->request->getPost('codItem');
        $fields['codAtendimento'] = $this->request->getPost('codAtendimento');
        $fields['codStatus'] = 1;
        $fields['per'] = $this->request->getPost('per');
        $fields['qtde'] = $this->request->getPost('qtde');
        $fields['und'] = $this->request->getPost('und');
        $fields['via'] = $this->request->getPost('via');
        $fields['freq'] = $this->request->getPost('freq');
        $fields['dias'] = $this->request->getPost('dias');
        $fields['codPaciente'] = $this->request->getPost('codPaciente');
        $fields['codAutor'] = session()->codPessoa;
        $fields['dataCriacao'] = date('Y-m-d H:i');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['dataInicio'] = $this->request->getPost('dataInicio');


        if ($this->request->getPost('dataPedidoCultura') == NULL) {
            $fields['dataPedidoCultura'] = NULL;
        } else {
            $fields['dataPedidoCultura'] = $this->request->getPost('dataPedidoCultura');
        }

        $fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');
        $fields['primeiraEscolha'] = $this->request->getPost('primeiraEscolha');
        $fields['indicacaoAntibiotico'] = $this->request->getPost('indicacaoAntibiotico');
        $fields['tipoInfeccao'] = $this->request->getPost('tipoInfeccao');
        $fields['solicitouCultura'] = $this->request->getPost('solicitouCultura');
        $fields['detalheResultadoCultura'] = $this->request->getPost('detalheResultadoCultura');
        $fields['outroSitioInfecao'] = $this->request->getPost('outroSitioInfecao');

        if ($this->request->getPost('respiratoria') == 'on') {
            $fields['respiratoria'] = 1;
        } else {
            $fields['respiratoria'] = 0;
        }

        if ($this->request->getPost('urinaria') == 'on') {
            $fields['urinaria'] = 1;
        } else {
            $fields['urinaria'] = 0;
        }
        if ($this->request->getPost('peleTecido') == 'on') {
            $fields['peleTecido'] = 1;
        } else {
            $fields['peleTecido'] = 0;
        }

        if ($this->request->getPost('cirurgia') == 'on') {
            $fields['cirurgia'] = 1;
        } else {
            $fields['cirurgia'] = 0;
        }
        if ($this->request->getPost('correnteSanguinea') == 'on') {
            $fields['correnteSanguinea'] = 1;
        } else {
            $fields['correnteSanguinea'] = 0;
        }

        if ($this->request->getPost('resultadoCultura') == 'on') {
            $fields['resultadoCultura'] = 1;
        } else {
            $fields['resultadoCultura'] = 0;
        }

        if ($this->request->getPost('faltaMedicamentoFarmacia') == 'on') {
            $fields['faltaMedicamentoFarmacia'] = 1;
        } else {
            $fields['faltaMedicamentoFarmacia'] = 0;
        }


        if ($this->request->getPost('alergiaAntimicrobiano') == 'on') {
            $fields['alergiaAntimicrobiano'] = 1;
        } else {
            $fields['alergiaAntimicrobiano'] = 0;
        }

        if ($this->request->getPost('insuficienciaRenal') == 'on') {
            $fields['insuficienciaRenal'] = 1;
        } else {
            $fields['insuficienciaRenal'] = 0;
        }


        if ($this->request->getPost('insuficienciaHepatica') == 'on') {
            $fields['insuficienciaHepatica'] = 1;
        } else {
            $fields['insuficienciaHepatica'] = 0;
        }



        $fields['outroEsquemaAlternativo'] = $this->request->getPost('outroEsquemaAlternativo');
        $fields['justificativaEsquema'] = $this->request->getPost('justificativaEsquema');


        $this->validation->setRules([
            'codItem' => ['label' => 'CodItem', 'rules' => 'required|numeric|max_length[11]'],
            'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'required|numeric|max_length[11]'],
            'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required|valid_date'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required|valid_date'],
            'dataInicio' => ['label' => 'DataInicio', 'rules' => 'permit_empty|valid_date'],
            'dataEncerramento' => ['label' => 'DataEncerramento', 'rules' => 'permit_empty|valid_date'],
            'primeiraEscolha' => ['label' => 'PrimeiraEscolha', 'rules' => 'max_length[11]'],
            'indicacaoAntibiotico' => ['label' => 'IndicacaoAntibiotico', 'rules' => 'permit_empty|numeric|max_length[11]'],
            'outroEsquemaAlternativo' => ['label' => 'OutroEsquemaAlternativo', 'rules' => 'bloquearReservado'],
            'outroSitioInfecao' => ['label' => 'outroSitioInfecao', 'rules' => 'bloquearReservado'],
            'detalheResultadoCultura' => ['label' => 'detalheResultadoCultura', 'rules' => 'bloquearReservado'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->ControleAntimicrobianoModel->insert($fields)) {

                $response['success'] = true;
                $response['messages'] = 'Informação inserida com sucesso';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na inserção!';
            }
        }

        return $this->response->setJSON($response);
    }
    public function imprimirGuia()
    {
        $codControleAntimicrobiano = $this->request->getPost('codControleAntimicrobiano');

        $response = array();

        if (!$this->validation->check($codControleAntimicrobiano, 'numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {


            $data = $this->ControleAntimicrobianoModel->pegaPorCodigo($codControleAntimicrobiano);

            /*
            $response['codControleAntimicrobiano '] = $data->codControleAntimicrobiano;
            $response['codItem'] = $data->codItem;
            $response['codAtendimento'] = $data->codAtendimento;
            $response['codPaciente'] = $data->codPaciente;
            $response['codAutor'] = $data->codAutor;
            $response['codStatus'] = $data->codStatus;
            $response['dataCriacao'] = $data->dataCriacao;
            $response['dataAtualizacao'] = $data->dataAtualizacao;
            $response['dataInicio'] = $data->dataInicio;
            $response['dataEncerramento'] = $data->dataEncerramento;
            $response['primeiraEscolha'] = $data->primeiraEscolha;
            $response['indicacaoAntibiotico'] = $data->indicacaoAntibiotico;
            $response['tipoInfeccao'] = $data->tipoInfeccao;
            $response['respiratoria'] = $data->respiratoria;
            $response['urinaria'] = $data->urinaria;
            $response['resultadoCultura'] = $data->resultadoCultura;
            $response['peleTecido'] = $data->peleTecido;
            $response['cirurgia'] = $data->cirurgia;
            $response['correnteSanguinea'] = $data->correnteSanguinea;
            $response['outroSitioInfecao'] = $data->outroSitioInfecao;
            $response['faltaMedicamentoFarmacia'] = $data->faltaMedicamentoFarmacia;
            $response['alergiaAntimicrobiano'] = $data->alergiaAntimicrobiano;
            $response['insuficienciaRenal'] = $data->insuficienciaRenal;
            $response['insuficienciaHepatica'] = $data->insuficienciaHepatica;
            $response['outroEsquemaAlternativo'] = $data->outroEsquemaAlternativo;
            $response['justificativaEsquema'] = $data->justificativaEsquema;
            $response['detalheResultadoCultura'] = $data->detalheResultadoCultura;
            $response['solicitouCultura'] = $data->solicitouCultura;
            $response['per'] = $data->per;
            $response['qtde'] = $data->qtde;
            $response['und'] = $data->und;
            $response['via'] = $data->via;
            $response['freq'] = $data->freq;
            $response['dias'] = $data->dias;
            $response['motivoSuspensaoGuia'] = $data->motivoSuspensaoGuia;
*/

            $html = '';
            $dias = '';


            $html .= '            
            <div class="row border">
                <div style="font-weight: bold;" class="col-md-12">
                    DADOS DO PACIENTE
                </div>
                <div class="col-md-12">
                    Nome: ' . $data->nomeExibicao . ' 
                     |  Nº Prontuário: ' . $data->codProntuario . '
                     |  Idade: ' . $data->idade . '
                     |  Setor: ' . $data->abreviacaoDepartamento . '
                     |  Leito: ' . $data->descricaoLocalAtendimento . '
                </div>
            </div>';


            //DIAGNÓSTICO
            $diagnosticos = $this->ControleAntimicrobianoModel->pegaDiagnosticos($data->codAtendimento);



            $html .= '            
            <div class="row border">
                <div style="font-weight: bold;" class="col-md-12">
                    DIAGNÓSTICO
                </div>
                <div class="col-md-12">
                    Diagnóstico Principal: ' . $diagnosticos->cid . '
                </div>                
                <div class="col-md-12">
                <span class="border-bottom"></span>
                </div>
            </div>';

            if ($data->indicacaoAntibiotico == 1) {
                $profilatico = 'x';
                $terapeutico = '';
            } else {
                $profilatico = '';
                $terapeutico = 'x';
            }

            $html .= '            
            <div class="row">
                <div style="font-weight: bold;" class="col-md-12">
                     Indicação de Antibiótico
                </div>
                    <span>
                        ( ' . $profilatico . ' ) Profilático
                    </span>                
                    <span style="margin-left:10px">
                        ( ' . $terapeutico . ' ) Terapeutico
                    </span>

            </div>';


            if ($data->tipoInfeccao == 1) {
                $comunitaria = 'x';
                $hospitalar = '';
            } else {
                $comunitaria = '';
                $hospitalar = 'x';
            }

            $html .= '            
            <div class="row">
                <div style="font-weight: bold;" class="col-md-12">
                     Indicação de Antibiótico
                </div>
                    <span>
                        ( ' . $comunitaria . ' ) Comunitária
                    </span>                
                    <span style="margin-left:10px">
                        ( ' . $hospitalar . ' ) Hospitalar
                    </span>

            </div>';


            if ($data->respiratoria == 1) {
                $respiratoria = 'x';
            } else {
                $respiratoria = '';
            }
            if ($data->urinaria == 1) {
                $urinaria = 'x';
            } else {
                $urinaria = '';
            }
            if ($data->peleTecido == 1) {
                $peleTecido = 'x';
            } else {
                $peleTecido = '';
            }

            if ($data->cirurgia == 1) {
                $cirurgia = 'x';
            } else {
                $cirurgia = '';
            }

            if ($data->correnteSanguinea == 1) {
                $correnteSanguinea = 'x';
            } else {
                $correnteSanguinea = '';
            }


            $html .= '            
            <div class="row">
                <div style="font-weight: bold;" class="col-md-12">
                    Sítio da Infecção
                </div>
                <div class="col-md-12">
                    <span>
                        ( ' . $respiratoria . ' ) Respiratória
                    </span>                
                    <span style="margin-left:10px">
                        ( ' . $urinaria . ' ) Urinária
                    </span>                
                    <span style="margin-left:10px">
                        ( ' . $peleTecido . ' ) Pele e Tecido
                    </span>                
                    <span style="margin-left:10px">
                        ( ' . $cirurgia . ' ) Cirurgia
                    </span>                
                    <span style="margin-left:10px">
                        ( ' . $correnteSanguinea . ' ) Corrente Sanguinea
                    </span> 
                </div>               
                <div class="col-md-12">
                    Outros: ' . $data->outroSitioInfecao . '
                </div>

            </div>';



            if ($data->dias > 1) {
                $dias = $data->dias . ' dias';
            }
            if ($data->dias == 1) {
                $dias = $data->dias . ' dia';
            }

            $html .= '
            <div class="row border">
                <div style="font-weight: bold;" class="col-md-12">
                    Dados do Antimicrobiano
                </div>
                <div class="col-md-12">

                <div style="font-size:20px; font-weight:bold">' . $data->qtde . ' ' . $data->descricaoUnidade . '(s) de ' . $data->descricaoItem . '</div>
                <div style="font-size:20px;font-weight:bold">Frequência: ' . $data->freq . 'x/' . $data->descricaoPeriodo . ' | ' . $data->descricaoVia . '  | Por ' . $dias . '</div>
                </div>
            </div>
            ';



            if ($data->primeiraEscolha == 1) {
                $primeiraEscolhaSim = 'x';
            } else {
                $primeiraEscolhaNao = 'x';
            }

            $html .= '            
            <div class="row">
                <div style="font-weight: bold;" class="col-md-12">
                     Esquema de 1º escolha?
                </div>
                    <span>
                        ( ' . $primeiraEscolhaSim . ' ) Sim
                    </span>                
                    <span style="margin-left:10px">
                        ( ' . $primeiraEscolhaNao . ' ) Não
                    </span>

            </div>';

            if ($data->resultadoCultura == 1) {
                $resultadoCultura = 'x';
            } else {
                $resultadoCultura = '';
            }
            if ($data->faltaMedicamentoFarmacia == 1) {
                $faltaMedicamentoFarmacia = 'x';
            } else {
                $faltaMedicamentoFarmacia = '';
            }

            if ($data->alergiaAntimicrobiano == 1) {
                $alergiaAntimicrobiano = 'x';
            } else {
                $alergiaAntimicrobiano = '';
            }
            if ($data->insuficienciaRenal == 1) {
                $insuficienciaRenal = 'x';
            } else {
                $insuficienciaRenal = '';
            }
            if ($data->insuficienciaHepatica == 1) {
                $insuficienciaHepatica = 'x';
            } else {
                $insuficienciaHepatica = '';
            }

            $html .= '            
            <div class="row">
                <div style="font-weight: bold;" class="col-md-12">
                    Se esquema alternativo assinale o motivo:
                </div>
                <div class="col-md-12">
                    <span>
                        ( ' . $resultadoCultura . ' ) Resultado de cultura
                    </span>                
                    <span style="margin-left:10px">
                        ( ' . $faltaMedicamentoFarmacia . ' ) Falta de Medicamento na Farmácia
                    </span>                
                    <span style="margin-left:10px">
                        ( ' . $alergiaAntimicrobiano . ' ) Alergia à antimicrobiano
                    </span>                
                    <span style="margin-left:10px">
                        ( ' . $insuficienciaRenal . ' ) Insuf. Renal
                    </span>                
                    <span style="margin-left:10px">
                        ( ' . $insuficienciaHepatica . ' ) Insuf. Hepática
                    </span> 
                </div>               
                <div  style="font-weight: bold;" class="col-md-12">
                    Outros Esquema alternativo: ' . $data->outroEsquemaAlternativo . '
                </div>                               
                <div  style="font-weight: bold;" class="col-md-12">
                    Justificativa do Esquema: ' . $data->justificativaEsquema . '
                </div>
            </div>';



            if ($data->solicitouCultura == 1) {
                $solicitouCulturaSim = 'x';
            } else {
                $solicitouCulturaNao = 'x';
            }

            $html .= '            
            <div class="row">
                <div style="font-weight: bold;" class="col-md-5">
                     Solicitou cultura?
                </div>
                    <span>
                        ( ' . $solicitouCulturaSim . ' ) Sim
                    </span>                
                    <span style="margin-left:10px">
                        ( ' . $solicitouCulturaNao . ' ) Não
                    </span>
                <div style="font-weight: bold;" class="col-md-5">
                   Data Solicitação: ' . date("d/m/Y", strtotime($data->dataPedidoCultura)) . '
               </div>

            </div>
            <div class="row">
                <div class="col-md-12">
                       <span style="font-weight: bold;"> Resultado</span>
                        <span>' . $data->detalheResultadoCultura . '</span> 
                </div>
                
            </div>';



            $assinatura =
                '
				<div style="margin-bottom:20px" class="row">
					<div class="col-md-12">
						<div style="font-size:16px;font-weight: bold;margin-top:30px" class="text-center">' . $data->nomeCompletoEspecialista . ' - <b>' . $data->siglaCargo . '</b></div>	
						<div style="font-size:16px;font-weight: bold;margin-top:0px" class="text-center">' . $data->nomeConselho . ' ' . $data->numeroInscricao . '/' . $data->uf . '</div>	
						
					</div>
				</div>';



            $html .= $assinatura;




            $response['success'] = true;
            $response['html'] = $html;
        }

        return $this->response->setJSON($response);
    }
    public function edit()
    {

        $response = array();
        $fields['codControleAntimicrobiano'] = $this->request->getPost('codControleAntimicrobiano');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['dataInicio'] = $this->request->getPost('dataInicio');
        $fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');
        $fields['primeiraEscolha'] = $this->request->getPost('primeiraEscolha');
        $fields['indicacaoAntibiotico'] = $this->request->getPost('indicacaoAntibiotico');
        $fields['tipoInfeccao'] = $this->request->getPost('tipoInfeccao');
        $fields['solicitouCultura'] = $this->request->getPost('solicitouCultura');
        $fields['detalheResultadoCultura'] = $this->request->getPost('detalheResultadoCultura');
        $fields['outroSitioInfecao'] = $this->request->getPost('outroSitioInfecao');


        if ($this->request->getPost('dataPedidoCultura') == NULL) {
            $fields['dataPedidoCultura'] = NULL;
        } else {
            $fields['dataPedidoCultura'] = $this->request->getPost('dataPedidoCultura');
        }


        if ($this->request->getPost('respiratoria') == 'on') {
            $fields['respiratoria'] = 1;
        } else {
            $fields['respiratoria'] = 0;
        }

        if ($this->request->getPost('urinaria') == 'on') {
            $fields['urinaria'] = 1;
        } else {
            $fields['urinaria'] = 0;
        }
        if ($this->request->getPost('peleTecido') == 'on') {
            $fields['peleTecido'] = 1;
        } else {
            $fields['peleTecido'] = 0;
        }

        if ($this->request->getPost('cirurgia') == 'on') {
            $fields['cirurgia'] = 1;
        } else {
            $fields['cirurgia'] = 0;
        }
        if ($this->request->getPost('correnteSanguinea') == 'on') {
            $fields['correnteSanguinea'] = 1;
        } else {
            $fields['correnteSanguinea'] = 0;
        }

        if ($this->request->getPost('resultadoCultura') == 'on') {
            $fields['resultadoCultura'] = 1;
        } else {
            $fields['resultadoCultura'] = 0;
        }

        if ($this->request->getPost('faltaMedicamentoFarmacia') == 'on') {
            $fields['faltaMedicamentoFarmacia'] = 1;
        } else {
            $fields['faltaMedicamentoFarmacia'] = 0;
        }


        if ($this->request->getPost('alergiaAntimicrobiano') == 'on') {
            $fields['alergiaAntimicrobiano'] = 1;
        } else {
            $fields['alergiaAntimicrobiano'] = 0;
        }

        if ($this->request->getPost('insuficienciaRenal') == 'on') {
            $fields['insuficienciaRenal'] = 1;
        } else {
            $fields['insuficienciaRenal'] = 0;
        }


        if ($this->request->getPost('insuficienciaHepatica') == 'on') {
            $fields['insuficienciaHepatica'] = 1;
        } else {
            $fields['insuficienciaHepatica'] = 0;
        }



        $fields['outroEsquemaAlternativo'] = $this->request->getPost('outroEsquemaAlternativo');
        $fields['justificativaEsquema'] = $this->request->getPost('justificativaEsquema');


        $this->validation->setRules([
            'codControleAntimicrobiano' => ['label' => 'codControleAntimicrobiano', 'rules' => 'required|numeric'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required|valid_date'],
            'dataInicio' => ['label' => 'DataInicio', 'rules' => 'permit_empty|valid_date'],
            'dataEncerramento' => ['label' => 'DataEncerramento', 'rules' => 'permit_empty|valid_date'],
            'primeiraEscolha' => ['label' => 'PrimeiraEscolha', 'rules' => 'max_length[11]'],
            'indicacaoAntibiotico' => ['label' => 'IndicacaoAntibiotico', 'rules' => 'permit_empty|numeric|max_length[11]'],
            'outroEsquemaAlternativo' => ['label' => 'OutroEsquemaAlternativo', 'rules' => 'bloquearReservado'],
            'outroSitioInfecao' => ['label' => 'outroSitioInfecao', 'rules' => 'bloquearReservado'],
            'detalheResultadoCultura' => ['label' => 'detalheResultadoCultura', 'rules' => 'bloquearReservado'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->ControleAntimicrobianoModel->update($fields['codControleAntimicrobiano'], $fields)) {

                $response['success'] = true;
                $response['messages'] = 'Atualização realizada com sucesso';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na atualização!';
            }
        }

        return $this->response->setJSON($response);
    }

    public function suspender()
    {
        $response = array();

        $fields['codControleAntimicrobiano'] = $this->request->getPost('codControleAntimicrobiano');
        $fields['motivoSuspensaoGuia'] = $this->request->getPost('motivoSuspensaoGuia');
        $fields['suspensoPor'] = session()->codPessoa;
        $fields['dataSuspensao'] = date('Y-m-d H:i');
        $fields['codStatus'] = 0;

        $this->validation->setRules([
            'motivoSuspensaoGuia' => ['label' => 'motivo da Suspensao Guia', 'rules' => 'bloquearReservado'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
            return $this->response->setJSON($response);
        }


        if (!$this->validation->check($fields['codControleAntimicrobiano'], 'numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($this->ControleAntimicrobianoModel->update($fields['codControleAntimicrobiano'], $fields)) {

                $response['success'] = true;
                $response['messages'] = 'Guia Suspensa com sucesso!';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na deleção!';
            }
        }

        return $this->response->setJSON($response);
    }
}
