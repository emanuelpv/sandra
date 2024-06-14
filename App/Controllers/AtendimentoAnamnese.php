<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;
use App\Models\AtendimentosModel;
use App\Models\AtendimentoAnamneseModel;
use App\Models\AtendimentoDiagnosticoModel;
use App\Models\AtendimentosParametrosClinicosModel;

class AtendimentoAnamnese extends BaseController
{

    protected $AtendimentoAnamneseModel;
    protected $pessoasModel;
    protected $OrganizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

    public function __construct()
    {

        helper('seguranca_helper');
        verificaSeguranca($this, session(), base_url());
        $this->AtendimentosModel = new AtendimentosModel();
        $this->AtendimentoAnamneseModel = new AtendimentoAnamneseModel();
        $this->AtendimentoDiagnosticoModel = new AtendimentoDiagnosticoModel();
        $this->AtendimentosParametrosClinicosModel = new AtendimentosParametrosClinicosModel();
        $this->OrganizacoesModel = new OrganizacoesModel();
        $this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
        $this->validation =  \Config\Services::validation();
        $this->codOrganizacao = session()->codOrganizacao;
        $this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
    }

    public function index()
    {

        $permissao = verificaPermissao('AtendimentoAnamnese', 'listar');
        if ($permissao == 0) {
            echo mensagemAcessoNegado(session()->organizacoes);
            $this->LogsModel->inserirLog('Acesso indevido ao Módulo "AtendimentoAnamnese"', session()->codPessoa);
            exit();
        }


        $data = [
            'controller'        => 'atendimentoAnamnese',
            'title'             => 'Atendimento Anamnese'
        ];
        echo view('tema/cabecalho');
        echo view('tema/menu_vertical');
        echo view('tema/menu_horizontal');
        return view('atendimentoAnamnese', $data);
    }

    public function getAll()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->AtendimentoAnamneseModel->pegaTudo();

        foreach ($result as $key => $value) {

            $ops = '<div class="btn-group">';
            $ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editatendimentoAnamnese(' . $value->codAtendimentoAnamnese . ')"><i class="fa fa-edit"></i></button>';
            $ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeatendimentoAnamnese(' . $value->codAtendimentoAnamnese . ')"><i class="fa fa-trash"></i></button>';
            $ops .= '</div>';

            $data['data'][$key] = array(
                $value->codAtendimentoAnamnese,
                $value->codPaciente,
                $value->codEspecialidade,
                $value->codEspecialista,
                $value->queixaPrincipal,
                $value->hda,
                $value->hmp,
                $value->historiaMedicamentos,
                $value->historiaAlergias,
                $value->chv,
                $value->parecer,
                $value->outros,
                $value->codStatus,
                $value->dataCriacao,
                $value->dataAtualizacao,

                $ops,
            );
        }

        return $this->response->setJSON($data);
    }

    public function getOne()
    {
        $response = array();

        $id = $this->request->getPost('codAtendimentoAnamnese');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->AtendimentoAnamneseModel->pegaPorCodigo($id);

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function getOnePorCodAtendimento()
    {
        $response = array();
        $data = array();

        $codAtendimento = $this->request->getPost('codAtendimento');

        if ($this->validation->check($codAtendimento, 'required|numeric')) {

            $data = $this->AtendimentoAnamneseModel->pegaPorCodAtendimento($codAtendimento);

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function salvarAnamnese()
    {

        $response = array();
        $fields = array();
        $parametrosClinicos = array();

        $codAtendimento = $this->request->getPost('codAtendimento');




        //PARAMETROS CLINICOS

        $parametrosClinicosSetado = 0;
        if ($this->request->getPost('peso') !== NULL and $this->request->getPost('peso') !== "") {
            $parametrosClinicos['peso'] = $this->request->getPost('peso');
            $parametrosClinicosSetado = 1;
        } else {

            $parametrosClinicos['peso'] = NULL;
        }

        if ($this->request->getPost('altura') !== NULL and $this->request->getPost('altura') !== "") {
            $parametrosClinicos['altura'] = $this->request->getPost('altura');
            $parametrosClinicosSetado = 1;
        } else {

            $parametrosClinicos['altura'] = NULL;
        }

        if ($this->request->getPost('perimetroCefalico') !== NULL and $this->request->getPost('perimetroCefalico') !== "") {
            $parametrosClinicos['perimetroCefalico'] = $this->request->getPost('perimetroCefalico');
            $parametrosClinicosSetado = 1;
        } else {

            $parametrosClinicos['perimetroCefalico'] = NULL;
        }


        if ($this->request->getPost('parimetroAbdominal') !== NULL and $this->request->getPost('parimetroAbdominal') !== "") {
            $parametrosClinicos['parimetroAbdominal'] = $this->request->getPost('parimetroAbdominal');
            $parametrosClinicosSetado = 1;
        } else {

            $parametrosClinicos['parimetroAbdominal'] = NULL;
        }

        if ($this->request->getPost('paSistolica') !== NULL and $this->request->getPost('paSistolica') !== "") {
            $parametrosClinicos['paSistolica'] = $this->request->getPost('paSistolica');
            $parametrosClinicosSetado = 1;
        } else {

            $parametrosClinicos['paSistolica'] = NULL;
        }


        if ($this->request->getPost('paDiastolica') !== NULL and $this->request->getPost('paDiastolica') !== "") {
            $parametrosClinicos['paDiastolica'] = $this->request->getPost('paDiastolica');
            $parametrosClinicosSetado = 1;
        } else {

            $parametrosClinicos['paDiastolica'] = NULL;
        }


        if ($this->request->getPost('fc') !== NULL and $this->request->getPost('fc') !== "") {
            $parametrosClinicos['fc'] = $this->request->getPost('fc');
            $parametrosClinicosSetado = 1;
        } else {

            $parametrosClinicos['fc'] = NULL;
        }

        if ($this->request->getPost('fr') !== NULL and $this->request->getPost('fr') !== "") {
            $parametrosClinicos['fr'] = $this->request->getPost('fr');
            $parametrosClinicosSetado = 1;
        } else {

            $parametrosClinicos['fr'] = NULL;
        }


        if ($this->request->getPost('temperatura') !== NULL and $this->request->getPost('temperatura') !== "") {
            $parametrosClinicos['temperatura'] = $this->request->getPost('temperatura');
            $parametrosClinicosSetado = 1;
        } else {

            $parametrosClinicos['temperatura'] = NULL;
        }

        if ($this->request->getPost('saturacao') !== NULL and $this->request->getPost('saturacao') !== "") {
            $parametrosClinicos['saturacao'] = $this->request->getPost('saturacao');
            $parametrosClinicosSetado = 1;
        } else {

            $$parametrosClinicos['saturacao'] = NULL;
        }

        if ($this->request->getPost('glicemiaJejum') !== NULL and $this->request->getPost('glicemiaJejum') !== "") {
            $parametrosClinicos['glicemiaJejum'] = $this->request->getPost('glicemiaJejum');
            $parametrosClinicosSetado = 1;
        } else {

            $parametrosClinicos['glicemiaJejum'] = NULL;
        }

        if ($this->request->getPost('glicemiaPosPrandial') !== NULL and $this->request->getPost('glicemiaPosPrandial') !== "") {
            $parametrosClinicos['glicemiaPosPrandial'] = $this->request->getPost('glicemiaPosPrandial');
            $parametrosClinicosSetado = 1;
        } else {

            $parametrosClinicos['glicemiaPosPrandial'] = NULL;
        }





        //COMORBIDADES





        if ($this->request->getPost('diabetesMellitus') === true) {
            $parametrosClinicos['diabetesMellitus'] = 1;
        } else {
            $parametrosClinicos['diabetesMellitus'] = NULL;
        }

        if ($this->request->getPost('pneumopatiaCronicaGrave') == 'true') {
            $parametrosClinicos['pneumopatiaCronicaGrave'] = 1;
        } else {
            $parametrosClinicos['pneumopatiaCronicaGrave'] = NULL;
        }


        if ($this->request->getPost('har') == 'true') {
            $parametrosClinicos['har'] = 1;
        } else {
            $parametrosClinicos['har'] = NULL;
        }


        if ($this->request->getPost('hae3') == 'true') {
            $parametrosClinicos['hae3'] = 1;
        } else {
            $parametrosClinicos['hae3'] = NULL;
        }

        if ($this->request->getPost('hae12') == 'true') {
            $parametrosClinicos['hae12'] = 1;
        } else {
            $parametrosClinicos['hae12'] = NULL;
        }

        if ($this->request->getPost('ic') == 'true') {
            $parametrosClinicos['ic'] = 1;
        } else {
            $parametrosClinicos['ic'] = NULL;
        }

        if ($this->request->getPost('cphp') == 'true') {
            $parametrosClinicos['cphp'] = 1;
        } else {
            $parametrosClinicos['cphp'] = NULL;
        }


        if ($this->request->getPost('ch') == 'true') {
            $parametrosClinicos['ch'] = 1;
        } else {
            $parametrosClinicos['ch'] = NULL;
        }


        if ($this->request->getPost('sc') == 'true') {
            $parametrosClinicos['sc'] = 1;
        } else {
            $parametrosClinicos['sc'] = NULL;
        }


        if ($this->request->getPost('valvopatia') == 'true') {
            $parametrosClinicos['valvopatia'] = 1;
        } else {
            $parametrosClinicos['valvopatia'] = NULL;
        }

        if ($this->request->getPost('miocarPericar') == 'true') {
            $parametrosClinicos['miocarPericar'] = 1;
        } else {
            $parametrosClinicos['miocarPericar'] = NULL;
        }

        if ($this->request->getPost('aortaVasosFistulas') == 'true') {
            $parametrosClinicos['aortaVasosFistulas'] = 1;
        } else {
            $parametrosClinicos['aortaVasosFistulas'] = NULL;
        }

        if ($this->request->getPost('arritimiasCardiacas') == 'true') {
            $parametrosClinicos['arritimiasCardiacas'] = 1;
        } else {
            $parametrosClinicos['arritimiasCardiacas'] = NULL;
        }

        if ($this->request->getPost('cardiopatiaCongenitaAdulto') == 'true') {
            $parametrosClinicos['cardiopatiaCongenitaAdulto'] = 1;
        } else {
            $parametrosClinicos['cardiopatiaCongenitaAdulto'] = NULL;
        }
        if ($this->request->getPost('protesesValvaresDispCardImplatados') == 'true') {
            $parametrosClinicos['protesesValvaresDispCardImplatados'] = 1;
        } else {
            $parametrosClinicos['protesesValvaresDispCardImplatados'] = NULL;
        }

        if ($this->request->getPost('doencaCerebroVascular') == 'true') {
            $parametrosClinicos['doencaCerebroVascular'] = 1;
        } else {
            $parametrosClinicos['doencaCerebroVascular'] = NULL;
        }

        if ($this->request->getPost('doencaRenalCronica') == 'true') {
            $parametrosClinicos['doencaRenalCronica'] = 1;
        } else {
            $parametrosClinicos['doencaRenalCronica'] = NULL;
        }

        if ($this->request->getPost('imuninosuprimido') == 'true') {
            $parametrosClinicos['imuninosuprimido'] = 1;
        } else {
            $parametrosClinicos['imuninosuprimido'] = NULL;
        }

        if ($this->request->getPost('hemoglobinopatiaGrave') == 'true') {
            $parametrosClinicos['hemoglobinopatiaGrave'] = 1;
        } else {
            $parametrosClinicos['hemoglobinopatiaGrave'] = NULL;
        }
        if ($this->request->getPost('obesidadeMorbida') == 'true') {
            $parametrosClinicos['obesidadeMorbida'] = 1;
        } else {
            $parametrosClinicos['obesidadeMorbida'] = NULL;
        }

        if ($this->request->getPost('sindromeDown') == 'true') {
            $parametrosClinicos['sindromeDown'] = 1;
        } else {
            $parametrosClinicos['sindromeDown'] = NULL;
        }


        if ($this->request->getPost('cirroseHepatica') == 'true') {
            $parametrosClinicos['cirroseHepatica'] = 1;
        } else {
            $parametrosClinicos['cirroseHepatica'] = NULL;
        }


        $parametrosClinicos['codAtendimento'] = $codAtendimento;
        $parametrosClinicos['codAutor'] = session()->codPessoa;
        $parametrosClinicos['dataCriacao'] = date('Y-m-d H:i');
        $parametrosClinicos['dataAtualizacao'] = date('Y-m-d H:i');


        $this->validation->setRules([
            'peso' => ['label' => 'peso', 'rules' => 'permit_empty|numeric|max_length[11]'],

        ]);

        if ($this->validation->run($fiparametrosClinicoselds) == FALSE) {
        } else {
            if ($parametrosClinicosSetado == 1) {
                $existeParametroClinico =  $this->AtendimentosParametrosClinicosModel->verificaExistencia($codAtendimento);

                if ($existeParametroClinico !== NULL) {

                    //NÃO DEIXA ATUALIZAR SE CÓDIGO FOR NULO OU VAZIO
                    if ($existeParametroClinico->codParametroClinico !== NULL and $existeParametroClinico->codParametroClinico !== "" and $existeParametroClinico->codParametroClinico !== " ") {
                        if ($this->AtendimentosParametrosClinicosModel->update($existeParametroClinico->codParametroClinico, $parametrosClinicos)) {
                        }
                    }
                } else {


                    if ($this->AtendimentosParametrosClinicosModel->insert($parametrosClinicos)) {
                    }
                }
            }
        }










        //ANAMNESE

        $status = 1;
        if ($this->request->getPost('queixaPrincipal') !== NULL and $this->request->getPost('queixaPrincipal') !== "") {
            $queixaPrincipal = $this->request->getPost('queixaPrincipal');
        } else {

            $queixaPrincipal = NULL;
        }

        if ($this->request->getPost('historiaMedicamentos') !== NULL and $this->request->getPost('historiaMedicamentos') !== "") {
            $historiaMedicamentos = $this->request->getPost('historiaMedicamentos');
        } else {

            $historiaMedicamentos = NULL;
        }

        if ($this->request->getPost('historiaAlergias') !== NULL and $this->request->getPost('historiaAlergias') !== "") {
            $historiaAlergias = $this->request->getPost('historiaAlergias');
        } else {

            $historiaAlergias = NULL;
        }
        if ($this->request->getPost('outrasInformacoes') !== NULL and $this->request->getPost('outrasInformacoes') !== "") {
            $outrasInformacoes = $this->request->getPost('outrasInformacoes');
        } else {

            $outrasInformacoes = NULL;
        }

        if ($this->request->getPost('hda') !== NULL and $this->request->getPost('hda') !== "") {
            $hda = $this->request->getPost('hda');
        } else {

            $hda = NULL;
        }

        if ($this->request->getPost('hmp') !== NULL and $this->request->getPost('hmp') !== "") {
            $hmp = $this->request->getPost('hmp');
        } else {

            $hmp = NULL;
        }

        if ($this->request->getPost('exameFisico') !== NULL and $this->request->getPost('exameFisico') !== "") {
            $exameFisico = $this->request->getPost('exameFisico');
        } else {

            $exameFisico = NULL;
        }

        if ($this->request->getPost('chv') !== NULL and $this->request->getPost('chv') !== "") {
            $chv = $this->request->getPost('chv');
        } else {

            $chv = NULL;
        }


        if ($this->request->getPost('parecer') !== NULL and $this->request->getPost('parecer') !== "") {
            $parecer = $this->request->getPost('parecer');
        } else {

            $parecer = NULL;
        }

        $anamnese =  $this->AtendimentoAnamneseModel->verificaExistencia($codAtendimento);

        if ($anamnese->codAtendimentoAnamnese !== NULL and $anamnese->codAtendimentoAnamnese !== '' and $anamnese->codAtendimentoAnamnese !== ' ') {

            //UPDATE

            $fields['codPaciente'] = $anamnese->codPaciente;
            $fields['codEspecialidade'] = $anamnese->codEspecialidade;
            $fields['codEspecialista'] =  session()->codPessoa;
            $fields['queixaPrincipal'] = $queixaPrincipal;
            $fields['hda'] = $hda;
            $fields['hmp'] = $hmp;
            $fields['exameFisico'] = $exameFisico;
            $fields['historiaMedicamentos'] = $historiaMedicamentos;
            $fields['historiaAlergias'] = $historiaAlergias;
            $fields['outrasInformacoes'] = $outrasInformacoes;
            $fields['chv'] = $chv;
            $fields['parecer'] = $parecer;
            $fields['codStatus'] = $status;
            $fields['dataAtualizacao'] = date('Y-m-d H:i');

            $this->validation->setRules([
                'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'permit_empty|numeric|max_length[11]'],
                'codEspecialidade' => ['label' => 'CodEspecialidade', 'rules' => 'permit_empty|numeric|max_length[11]'],
                'codEspecialista' => ['label' => 'CodEspecialista', 'rules' => 'permit_empty|numeric|max_length[11]'],
                'queixaPrincipal' => ['label' => 'QueixaPrincipal', 'rules' => 'permit_empty'],
                'hda' => ['label' => 'Hda', 'rules' => 'permit_empty'],
                'hmp' => ['label' => 'Hmp', 'rules' => 'permit_empty'],
                'exameFisico' => ['label' => 'Exame Físico', 'rules' => 'permit_empty'],
                'historiaMedicamentos' => ['label' => 'HistoriaMedicamentos', 'rules' => 'permit_empty'],
                'historiaAlergias' => ['label' => 'HistoriaAlergias', 'rules' => 'permit_empty'],
                'outrasInformacoes' => ['label' => 'outras Informacoes', 'rules' => 'permit_empty'],
                'chv' => ['label' => 'Chv', 'rules' => 'permit_empty'],
                'parecer' => ['label' => 'Parecer', 'rules' => 'permit_empty'],
                'codStatus' => ['label' => 'CodStatus', 'rules' => 'permit_empty'],
                'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'permit_empty'],
                'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'permit_empty'],

            ]);

            if ($this->validation->run($fields) == FALSE) {

                $response['success'] = false;
                $response['messages'] = $this->validation->listErrors();
            } else {

                if ($this->AtendimentoAnamneseModel->update($anamnese->codAtendimentoAnamnese, $fields)) {

                    $atualizaAtendimento['codEspecialista'] =  session()->codPessoa;

                    if ($codAtendimento !== NULL and $codAtendimento !== "" and $codAtendimento !== " ") {
                        if ($this->AtendimentosModel->update($codAtendimento, $atualizaAtendimento)) {
                        }
                    }



                    $response['success'] = true;
                    $response['csrf_hash'] = csrf_hash();

                    if ($parametrosClinicosSetado == 1) {
                        $response['messages'] = 'Anamnese  e parâmetros clínicos Atualizados!';
                    } else {
                        $response['messages'] = 'Anamnese Atualizada!';
                    }
                } else {

                    $response['success'] = false;
                    $response['messages'] = 'Erro na atualização!';
                }
            }

            sleep(2);
            return $this->response->setJSON($response);
        } else {
            //INSERT
            $fields['codAtendimento'] = $this->request->getPost('codAtendimento');
            $fields['codPaciente'] = $anamnese->codPaciente;
            $fields['codEspecialidade'] = $anamnese->codEspecialidade;
            $fields['codEspecialista'] =  $anamnese->codEspecialista;
            $fields['queixaPrincipal'] = $queixaPrincipal;
            $fields['hda'] = $hda;
            $fields['hmp'] = $hmp;
            $fields['exameFisico'] = $exameFisico;
            $fields['historiaMedicamentos'] = $historiaMedicamentos;
            $fields['historiaAlergias'] = $historiaAlergias;
            $fields['outrasInformacoes'] = $outrasInformacoes;
            $fields['chv'] = $chv;
            $fields['parecer'] = $parecer;
            $fields['codStatus'] = $status;
            $fields['dataCriacao'] = date('Y-m-d H:i');
            $fields['dataAtualizacao'] = date('Y-m-d H:i');



            $this->validation->setRules([
                'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'permit_empty|numeric|max_length[11]'],
                'codEspecialidade' => ['label' => 'CodEspecialidade', 'rules' => 'permit_empty|numeric|max_length[11]'],
                'codEspecialista' => ['label' => 'CodEspecialista', 'rules' => 'permit_empty|numeric|max_length[11]'],
                'queixaPrincipal' => ['label' => 'QueixaPrincipal', 'rules' => 'permit_empty'],
                'hda' => ['label' => 'Hda', 'rules' => 'permit_empty'],
                'hmp' => ['label' => 'Hmp', 'rules' => 'permit_empty'],
                'exameFisico' => ['label' => 'Exame Físico', 'rules' => 'permit_empty'],
                'historiaMedicamentos' => ['label' => 'HistoriaMedicamentos', 'rules' => 'permit_empty'],
                'historiaAlergias' => ['label' => 'HistoriaAlergias', 'rules' => 'permit_empty'],
                'outrasInformacoes' => ['label' => 'outras Informacoes', 'rules' => 'permit_empty'],
                'chv' => ['label' => 'Chv', 'rules' => 'permit_empty'],
                'parecer' => ['label' => 'Parecer', 'rules' => 'permit_empty'],
                'codStatus' => ['label' => 'CodStatus', 'rules' => 'permit_empty'],
                'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'permit_empty'],
                'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'permit_empty'],

            ]);

            if ($this->validation->run($fields) == FALSE) {

                $response['success'] = false;
                $response['messages'] = $this->validation->listErrors();
            } else {

                if ($this->AtendimentoAnamneseModel->insert($fields)) {

                    $response['success'] = true;
                    $response['csrf_hash'] = csrf_hash();

                    if ($parametrosClinicosSetado == 1) {
                        $response['messages'] = 'Anamnese  e parâmetros clínicos Inseridos!';
                    } else {
                        $response['messages'] = 'Anamnese Inserida!';
                    }
                } else {

                    $response['success'] = false;
                    $response['messages'] = 'Erro na atualização!';
                }
            }

            sleep(2);
            return $this->response->setJSON($response);
        }
    }

    public function add()
    {

        $response = array();

        $fields['codAtendimentoAnamnese'] = $this->request->getPost('codAtendimentoAnamnese');
        $fields['codPaciente'] = $this->request->getPost('codPaciente');
        $fields['codEspecialidade'] = $this->request->getPost('codEspecialidade');
        $fields['codEspecialista'] = $this->request->getPost('codEspecialista');
        $fields['queixaPrincipal'] = $this->request->getPost('queixaPrincipal');
        $fields['hda'] = $this->request->getPost('hda');
        $fields['hmp'] = $this->request->getPost('hmp');
        $fields['exameFisico'] = $this->request->getPost('exameFisico');
        $fields['historiaMedicamentos'] = $this->request->getPost('historiaMedicamentos');
        $fields['historiaAlergias'] = $this->request->getPost('historiaAlergias');
        $fields['chv'] = $this->request->getPost('chv');
        $fields['parecer'] = $this->request->getPost('parecer');
        $fields['outros'] = $this->request->getPost('outros');
        $fields['codStatus'] = $this->request->getPost('codStatus');
        $fields['dataCriacao'] = $this->request->getPost('dataCriacao');
        $fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');


        $this->validation->setRules([
            'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'required|numeric|max_length[11]'],
            'codEspecialidade' => ['label' => 'CodEspecialidade', 'rules' => 'required|numeric|max_length[11]'],
            'codEspecialista' => ['label' => 'CodEspecialista', 'rules' => 'required|numeric|max_length[11]'],
            'queixaPrincipal' => ['label' => 'QueixaPrincipal', 'rules' => 'required'],
            'hda' => ['label' => 'Hda', 'rules' => 'required'],
            'hmp' => ['label' => 'Hmp', 'rules' => 'required|numeric|max_length[11]'],
            'exameFisico' => ['label' => 'exameFisico', 'rules' => 'required|numeric|max_length[11]'],
            'historiaMedicamentos' => ['label' => 'HistoriaMedicamentos', 'rules' => 'required'],
            'historiaAlergias' => ['label' => 'HistoriaAlergias', 'rules' => 'required'],
            'chv' => ['label' => 'Chv', 'rules' => 'required'],
            'parecer' => ['label' => 'Parecer', 'rules' => 'required'],
            'outros' => ['label' => 'Outros', 'rules' => 'required'],
            'codStatus' => ['label' => 'CodStatus', 'rules' => 'required|numeric|max_length[11]'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->AtendimentoAnamneseModel->insert($fields)) {

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

        $fields['codAtendimentoAnamnese'] = $this->request->getPost('codAtendimentoAnamnese');
        $fields['codPaciente'] = $this->request->getPost('codPaciente');
        $fields['codEspecialidade'] = $this->request->getPost('codEspecialidade');
        $fields['codEspecialista'] = $this->request->getPost('codEspecialista');
        $fields['queixaPrincipal'] = $this->request->getPost('queixaPrincipal');
        $fields['hda'] = $this->request->getPost('hda');
        $fields['hmp'] = $this->request->getPost('hmp');
        $fields['exameFisico'] = $this->request->getPost('exameFisico');
        $fields['historiaMedicamentos'] = $this->request->getPost('historiaMedicamentos');
        $fields['historiaAlergias'] = $this->request->getPost('historiaAlergias');
        $fields['chv'] = $this->request->getPost('chv');
        $fields['parecer'] = $this->request->getPost('parecer');
        $fields['outros'] = $this->request->getPost('outros');
        $fields['codStatus'] = $this->request->getPost('codStatus');
        $fields['dataCriacao'] = $this->request->getPost('dataCriacao');
        $fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');


        $this->validation->setRules([
            'codAtendimentoAnamnese' => ['label' => 'codAtendimentoAnamnese', 'rules' => 'required|numeric|max_length[11]'],
            'codPaciente' => ['label' => 'CodPaciente', 'rules' => 'required|numeric|max_length[11]'],
            'codEspecialidade' => ['label' => 'CodEspecialidade', 'rules' => 'required|numeric|max_length[11]'],
            'codEspecialista' => ['label' => 'CodEspecialista', 'rules' => 'required|numeric|max_length[11]'],
            'queixaPrincipal' => ['label' => 'QueixaPrincipal', 'rules' => 'required'],
            'hda' => ['label' => 'Hda', 'rules' => 'required'],
            'hmp' => ['label' => 'Hmp', 'rules' => 'required|numeric|max_length[11]'],
            'exameFisico' => ['label' => 'exameFisico', 'rules' => 'required|numeric|max_length[11]'],
            'historiaMedicamentos' => ['label' => 'HistoriaMedicamentos', 'rules' => 'required'],
            'historiaAlergias' => ['label' => 'HistoriaAlergias', 'rules' => 'required'],
            'chv' => ['label' => 'Chv', 'rules' => 'required'],
            'parecer' => ['label' => 'Parecer', 'rules' => 'required'],
            'outros' => ['label' => 'Outros', 'rules' => 'required'],
            'codStatus' => ['label' => 'CodStatus', 'rules' => 'required|numeric|max_length[11]'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            //NÃO DEIXA ATUALIZAR SE cÓDIGO FOR NULO OU VAZIO
            if ($fields['codAtendimentoAnamnese'] !== NULL and $fields['codAtendimentoAnamnese'] !== "" and $fields['codAtendimentoAnamnese'] !== " ") {

                if ($this->AtendimentoAnamneseModel->update($fields['codAtendimentoAnamnese'], $fields)) {

                    $response['success'] = true;
                    $response['csrf_hash'] = csrf_hash();
                    $response['messages'] = 'Atualizado com sucesso';
                } else {

                    $response['success'] = false;
                    $response['messages'] = 'Erro na atualização!';
                }
            } else {
                $response['success'] = false;
                $response['messages'] = 'Erro na operação!';
                return $this->response->setJSON($response);
            }
        }

        return $this->response->setJSON($response);
    }

    public function remove()
    {
        $response = array();

        $id = $this->request->getPost('codAtendimentoAnamnese');

        if (!$this->validation->check($id, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($this->AtendimentoAnamneseModel->where('codAtendimentoAnamnese', $id)->delete()) {

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
