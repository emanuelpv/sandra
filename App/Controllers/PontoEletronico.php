<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\PacientesModel;
use App\Models\OrganizacoesModel;
use App\Models\CategoriasSuporteModel;
use App\Models\AgendamentosModel;

use App\Models\StatusSuporteModel;
use App\Models\PreferenciasModel;

use App\Models\AcaoSuporteModel;
use App\Models\PontoEletronicoModel;
use App\Models\SolicitacoesSuporteModel;
use App\Models\AtendimentoSenhasModel;

class PontoEletronico extends BaseController
{

    protected $solicitacoesSuporteModel;
    protected $PessoasModel;
    protected $organizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

    public function __construct()
    {

        $this->PontoEletronicoModel = new PontoEletronicoModel();
        $this->AtendimentoSenhasModel = new AtendimentoSenhasModel();
        $this->AgendamentosModel = new AgendamentosModel();
        $this->PessoasModel = new PessoasModel();
        $this->PacientesModel = new PacientesModel();
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
    }

    public function index()
    {

        return view('pontoEletronico');
    }



    public function procuraPessoa($cpf = NULL)
    {


        $response = array();
        $html = "";

        if ($this->request->getPost('cpf') !== NULL) {
            $cpf = removeCaracteresIndesejados($this->request->getPost('cpf'));
        } else {
            $cpf = removeCaracteresIndesejados($cpf);
        }


        $pessoa = $this->PessoasModel->pegaPessoaPorcpf($cpf);

        if ($pessoa !== NULL) {
            $nomePessoa = $pessoa->nomeExibicao;
            $response['success'] = true;

            $html .= '
            <div class="row">
                <input type="hidden" id="codPessoa" name="codPessoa" value="' . $pessoa->codPessoa . '" >
                <input type="hidden" id="cpf" name="cpf" value="' . $cpf . '" >
                <input type="hidden" id="nomePessoa" name="nomePessoa" value="' . $pessoa->nomeExibicao . '" >
               
                <div class="col-md-12">
                    <div class="card card-widget widget-user shadow">
                        <div style="background:#56ff0052;height:100px" class="widget-user-header">
                            <h3 style="font-size:30px !important;margin-bottom:10px;font-weight: bold;" >' . $nomePessoa . ' (' . $pessoa->cpf . ')' . '</h3>
                           
                        </div>
                        
                    </div>
                </div>
            </div>		
            ';
        } else {

            $response['success'] = false;
            $response['csrf_hash'] = csrf_hash();
        }


        $response['html'] = $html;

        return $this->response->setJSON($response);
    }


    function registrarPresenca()
    {
        $response = array();

        // print 'estou aqui'; exit();


        $codPessoa = $this->request->getPost('codPessoa');
        if ($codPessoa !== NULL and $codPessoa !== "") {
            $nomeArquivo = $codPessoa . date('YmdHi') . ".jpeg";
            $data_url =  $this->request->getPost('imagem');

            list($type, $data) = explode(';', $data_url);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);

            file_put_contents('arquivos/imagens/registroPresenca/' . $nomeArquivo, $data);
            
            $fields['codOrganizacao'] =  session()->codOrganizacao;
            $fields['codPessoa'] =  $codPessoa;
            $fields['dataCriacao'] =  date('Y-m-d H:i');
            $fields['imagem'] =  $nomeArquivo;
            if ($this->PontoEletronicoModel->insert($fields)) {
                $response['success'] = true;
                $response['hora'] = date('H:i');;
                $response['data_url'] =  $data_url;
                $response['csrf_hash'] = csrf_hash();
            } else {
                $response['success'] = false;
                $response['hora'] = date('H:i');;
                $response['data_url'] =  $data_url;
                $response['csrf_hash'] = csrf_hash();
            }
        }





        return $this->response->setJSON($response);
    }
}
