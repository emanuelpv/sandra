<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;
use App\Models\CategoriasSuporteModel;
use App\Models\AgendamentosModel;

use App\Models\StatusSuporteModel;
use App\Models\PreferenciasModel;

use App\Models\AcaoSuporteModel;
use App\Models\PainelChamadasModel;
use App\Models\ExamesListaModel;
use App\Models\SolicitacoesSuporteModel;

class PainelChamadasExames extends BaseController
{

    protected $solicitacoesSuporteModel;
    protected $ExamesListaModel;
    protected $pessoasModel;
    protected $organizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

    public function __construct()
    {

        $this->PainelChamadasModel = new PainelChamadasModel();
        $this->AgendamentosModel = new AgendamentosModel();
        $this->PessoasModel = new PessoasModel();
        $this->SolicitacoesSuporteModel = new SolicitacoesSuporteModel();
        $this->OrganizacoesModel = new OrganizacoesModel();
        $this->StatusSuporteModel = new StatusSuporteModel();
        $this->PreferenciasModel = new PreferenciasModel();
        $this->ExamesListaModel = new ExamesListaModel();
        $this->AcaoSuporte = new AcaoSuporteModel();
        $this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
        $this->validation =  \Config\Services::validation();

        $configuracao = config('App');
        session()->set('codOrganizacao', $configuracao->codOrganizacao);
        $codOrganizacao = $configuracao->codOrganizacao;
        $dadosOrganizacao = $this->OrganizacoesModel->pegaDadosBasicosOrganizacao($codOrganizacao);

        session()->set('descricaoOrganizacao', $dadosOrganizacao->descricao);
        session()->set('logo', $dadosOrganizacao->logo);
    }

    public function index()
    {

        return view('painelChamadasExames');
    }





    public function painelChamadas()
    {
        $response = array();



        $data['data'] = array();


        $especialidades = $this->request->getPost('especialidades');
        $result = $this->PainelChamadasModel->marcados($especialidades);

        foreach ($result as $key => $value) {



            if ($value->horaChegada !== NULL) {
                $horaChegada = date('H:i', strtotime($value->horaChegada));
                $tempoAtendimento = intervaloTempoHoraMinutos($value->horaChegada, date('Y-m-d H:i'));
            } else {
                $horaChegada = "";
                $tempoAtendimento  = "";
            }


            if ($value->chegou == 1) {
                $statusChegou = '<i class="fa fa-thumbs-up text-center" style="font-size:30px;color:#098f09"></i>';
            } else {
                $statusChegou = "-";
            }

            if ($value->confirmou == 1) {
                $statusConfirmou = '<i class="fa fa-thumbs-up text-center" style="font-size:30px;color:#098f09"></i>';
            } else {
                $statusConfirmou = "-";
            }

            if ($value->dataInicio !== $value->dataEncerramento) {
                $periodo = date('d/m', strtotime($value->dataInicio)) . " das " . date('H:i', strtotime($value->dataInicio)) . " às " . date('H:i', strtotime($value->dataEncerramento));
            } else {
                $periodo = date('d/m', strtotime($value->dataInicio)) . " às " . date('H:i', strtotime($value->dataInicio));
            }
            $fotoPerfil = '<img  alt="" style="width:30px" id="fotoPerfilBarraSuperior" src="' . base_url() . '/arquivos/imagens/pacientes/' . $value->fotoPerfil . '" class="img-circle elevation-2">';

            if ($value->nomeEspecialista !== NULL) {
                $especialidade = $value->descricaoEspecialidade . " (" . $value->nomeEspecialista . ")";
            } else {
                $especialidade = $value->descricaoEspecialidade;
            }


            $data['data'][$key] = array(
                $fotoPerfil . " " . $value->nomePaciente . " (" . $value->idade . ")",
                $especialidade,
                $periodo,
                $horaChegada,
                $tempoAtendimento,
            );
        }

        return $this->response->setJSON($data);
    }


    public function listaDropDowncodExameLista()
    {

        $result = $this->ExamesListaModel->listaDropDowncodExameLista();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function verificaChamadas($especialidades = null)
    {
        if ($especialidades  == NULL) {
            $especialidades =  $this->request->getPost('especialidades');
        }
        $arrayEspecialidades = json_decode($especialidades);
        $especialidades = removeBraketsJson($especialidades);

        $data['data'] = array();

        $chamados = $this->PainelChamadasModel->pacientesChamados($especialidades);


        if (!empty($chamados) and in_array($chamados->codEspecialidade, $arrayEspecialidades)) {


            if ($chamados->qtdChamadas >= 1) {

                $data['qtdChamadas'] = $chamados->qtdChamadas - 1;

                if ($chamados->codChamada !== NULL and $chamados->codChamada !== "" and $chamados->codChamada !== " ") {

                    $this->PainelChamadasModel->update($chamados->codChamada, $data);
                }
                
                $tratamento = "";
                $data['success'] = true;
                $data['sala'] = $chamados->localAtendimento;
                $data['pacienteModal'] = '
                <div style="font-weight: bold;">' . $chamados->descricaoEspecialidade . '<div>
                <div style="font-size:80px;font-weight: bold;margin-top:20px">' . $chamados->nomeCompleto . '<div>
                <div style="margin-top:0px">' . $chamados->localAtendimento . '<div>';

                $data['textoLeitura'] = $tratamento . " " . $chamados->nomeCompleto . " " . $data['sala'];
            }

            if ($chamados->ultimaChamada > 10) {
                $this->PainelChamadasModel->where('codChamada', $chamados->codChamada)->delete();
            }


            return $this->response->setJSON($data);
        } else {
            $data['success'] = false;
            return $this->response->setJSON($data);
        }
    }


    public function pacientesUltimasChamadas($especialidades = NULL)
    {
        if ($especialidades  == NULL) {
            $especialidades =  $this->request->getPost('especialidades');
        }
        $arrayEspecialidades = json_decode($especialidades);
        $especialidades = removeBraketsJson($especialidades);

        $data['data'] = array();

        $result = $this->PainelChamadasModel->pacientesUltimasChamadas($especialidades);

        foreach ($result as $key => $value) {



            $data['data'][$key] = array(
                $value->nomePaciente . " (" . $value->idade . ")",
                '<center>' . $value->Nrchamadas . '</center>',
                $value->especialista . $value->localAtendimento,

            );
        }

        return $this->response->setJSON($data);
    }
}
