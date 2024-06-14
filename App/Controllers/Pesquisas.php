<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\OrganizacoesModel;
use App\Models\PesquisasModel;

class Pesquisas extends BaseController
{

    protected $PesquisasModel;
    protected $OrganizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $LogsModel;
    protected $validation;

    public function __construct()
    {


        $this->PesquisasModel = new PesquisasModel();
        $this->OrganizacoesModel = new OrganizacoesModel();
        $this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
        $this->validation =  \Config\Services::validation();

        $configuracao = config('App');
        session()->set('codOrganizacao', $configuracao->codOrganizacao);
        $codOrganizacao = $configuracao->codOrganizacao;
        $dadosOrganizacao = $this->OrganizacoesModel->pegaDadosBasicosOrganizacao($codOrganizacao);

        session()->set('descricaoOrganizacao', $dadosOrganizacao->descricao);
        session()->set('logo', $dadosOrganizacao->logo);
    }

    public function satisfacao()
    {
        return view('pesquisaSatisfacao');
    }




    public function addPesquisaSatisfacao()
    {
        $nota =  $this->request->getPost('nota');
        $setor =  $this->request->getPost('setor');




        $fields['codDepartamento'] =  $this->request->getPost('codDepartamento');
        $fields['codPergunta'] = $this->request->getPost('codPergunta');
        $fields['dataCriacao'] = date('Y-m-d H:i');
        $fields['nota'] =  $this->request->getPost('nota');

        $this->validation->setRules([
            'dataCriacao' => ['label' => 'dataCriacao', 'rules' => 'required|max_length[40]'],
            'codDepartamento' => ['label' => 'codDepartamento', 'rules' => 'required|max_length[40]'],
            'codPergunta' => ['label' => 'codPergunta', 'rules' => 'required|max_length[40]'],
            'nota' => ['label' => 'nota', 'rules' => 'required|max_length[40]'],
        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($codPessoa = $this->PesquisasModel->insert($fields)) {


                $response['success'] = true;
                $response['messages'] = 'Sua nota foi registrada. Obrigado por participar!';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Falha ao registrar sua participação';
            }
        }



        return $this->response->setJSON($response);
    }
}
