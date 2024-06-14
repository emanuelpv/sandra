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
use App\Models\PainelChamadasUrgenciaEmergenciaModel;
use App\Models\SolicitacoesSuporteModel;

class PainelChamadasUrgenciaEmergencia extends BaseController
{

    protected $solicitacoesSuporteModel;
    protected $pessoasModel;
    protected $organizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

    public function __construct()
    {

        $this->PainelChamadasUrgenciaEmergenciaModel = new PainelChamadasUrgenciaEmergenciaModel();
        $this->AgendamentosModel = new AgendamentosModel();
        $this->PessoasModel = new PessoasModel();
        $this->SolicitacoesSuporteModel = new SolicitacoesSuporteModel();
        $this->OrganizacoesModel = new OrganizacoesModel();
        $this->StatusSuporteModel = new StatusSuporteModel();
        $this->PreferenciasModel = new PreferenciasModel();
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

        return view('PainelChamadasUrgenciaEmergencia');
    }


    public function verificaChamadas($especialidades = null)
    {


        $data['data'] = array();

        $chamados = $this->PainelChamadasUrgenciaEmergenciaModel->pacientesChamados();


        if (!empty($chamados)) {


            if ($chamados->qtdChamadas >= 1) {

                //DECREMENTA A CHAMADA
                $data['qtdChamadas'] = $chamados->qtdChamadas - 1;


                if ($chamados->codChamada !== NULL and $chamados->codChamada !== "" and $chamados->codChamada !== " ") {

                    $this->PainelChamadasUrgenciaEmergenciaModel->update($chamados->codChamada, $data);
                }


                $tratamento = "";

                $data['success'] = true;
                $data['codClasseRisco'] = $chamados->codClasseRisco;
                $data['sala'] = $chamados->localAtendimento;
                $data['pacienteModal'] = '             
             
                <div style="font-size:80px;font-weight: bold;margin-top:20px">' . $chamados->nomeCompleto . '<div>
                <div style="margin-top:0px">' . $chamados->localAtendimento . '<div>';


                $data['textoLeitura'] = $tratamento . " " . $chamados->nomeCompleto . " " . $data['sala'];
            }

            //REMOVE AS CHAMADAS MAIORES QUE 5MINUTOS
            if ($chamados->ultimaChamada > 10) {
                $this->PainelChamadasUrgenciaEmergenciaModel->where('codChamada', $chamados->codChamada)->delete();
            }

            return $this->response->setJSON($data);
        } else {
            $data['success'] = false;
            return $this->response->setJSON($data);
        }
    }


    public function pacientesUrgenciaEmergencia()
    {
        $response = array();



        $data['data'] = array();


        $especialidades = $this->request->getPost('especialidades');
        $result = $this->PainelChamadasUrgenciaEmergenciaModel->pacientesUrgenciaEmergencia($especialidades);

        foreach ($result as $key => $value) {


            $tempoAtendimento = intervaloTempoAtendimento($value->dataCriacao, date('Y-m-d H:i'));

            $data['data'][$key] = array(
                $value->nomePaciente . " (" . $value->idade . ")",
                date('H:i', strtotime($value->dataCriacao)),
                $tempoAtendimento

            );
        }

        return $this->response->setJSON($data);
    }

    public function pacientesUrgenciaEmergenciaUltimasChamadas()
    {
        $response = array();



        $data['data'] = array();


        $especialidades = $this->request->getPost('especialidades');
        $result = $this->PainelChamadasUrgenciaEmergenciaModel->pacientesUrgenciaEmergenciaUltimasChamadas($especialidades);

        foreach ($result as $key => $value) {



            $data['data'][$key] = array(
                $value->nomePaciente . " (" . $value->idade . ")",
                '<center>' . $value->Nrchamadas . '</center>',
                $value->localAtendimento, // $value->especialista

            );
        }

        return $this->response->setJSON($data);
    }
}
