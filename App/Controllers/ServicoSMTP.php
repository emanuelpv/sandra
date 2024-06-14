<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\ServicoSMTPModel;

class ServicoSMTP extends BaseController
{

    protected $servicoSMTPModel;
    protected $pessoasModel;
    protected $organizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

    public function __construct()
    {

        helper('seguranca_helper');
        verificaSeguranca($this, session(), base_url());
        $this->ServicoSMTPModel = new ServicoSMTPModel();
        $this->OrganizacoesModel = new OrganizacoesModel();
        $this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
        $this->validation =  \Config\Services::validation();
        $this->codOrganizacao = session()->codOrganizacao;
        $this->organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);

        $permissao = verificaPermissao('ServicoSMTP', 'listar');
        if ($permissao == 0) {
            echo mensagemAcessoNegado(session()->organizacoes);
            $this->LogsModel->inserirLog('Acesso indevido ao Módulo ServicoSMTP', session()->codPessoa);
            exit();
        }
    }

    public function index()
    {

        $data = [
            'controller'        => 'servicoSMTP',
            'title'             => 'Serviço SMTP'
        ];
        echo view('tema/cabecalho');
        echo view('tema/menu_vertical');
        echo view('tema/menu_horizontal');
        return view('servicoSMTP', $data);
    }

    public function getAll()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->ServicoSMTPModel->pega_servicosmtp();

        foreach ($result as $key => $value) {

            $ops = '<div class="btn-group">';
            $ops .= '	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Editar" onclick="editSMTP(' . $value->codServidorSMTP . ')"><i class="fa fa-edit"></i></button>';
            $ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="removeSMTP(' . $value->codServidorSMTP . ')"><i class="fa fa-trash"></i></button>';
            $ops .= '</div>';

            $statusSMTP = "";
            if ($value->statusSMTP == 0) {
                $statusSMTP = "Desativado";
            }

            if ($value->statusSMTP == 1) {
                $statusSMTP = "Ativado";
            }

            $protocoloSMTP = "";
            if ($value->protocoloSMTP == 1) {
                $protocoloSMTP = "SSL";
            }
            if ($value->protocoloSMTP == 2) {
                $protocoloSMTP = "TLS";
            }
            if ($value->protocoloSMTP == 3) {
                $protocoloSMTP = "STARTTLS";
            }



            $data['data'][$key] = array(
                $value->codServidorSMTP,
                $value->descricaoServidorSMTP,
                $value->ipServidorSMTP,
                $value->portaSMTP,
                $value->loginSMTP,
                '*****************',
                $value->emailRetorno,
                $protocoloSMTP,
                $statusSMTP,

                $ops,
            );
        }

        return $this->response->setJSON($data);
    }
    public function testeSMTP()
    {
        $response = array();

        $ipServidorSMTP = $this->request->getPost('ipServidorSMTP');
        $portaSMTP = $this->request->getPost('portaSMTP');
        $loginSMTP = $this->request->getPost('loginSMTP');
        $senhaSMTP = $this->request->getPost('senhaSMTP');
        $emailRetorno = $this->request->getPost('emailRetorno');
        $protocoloSMTP = $this->request->getPost('protocoloSMTP');
        $statusSMTP = $this->request->getPost('statusSMTP');
        $destinatario = $this->request->getPost('destinatario');

        if ($statusSMTP == 2) {
            $response['success'] = false;
            $response['messages'] = 'Somente é possível testar conexões ativas';

            return $this->response->setJSON($response);
        }

        if ($statusSMTP == 1) {

            $resultado = emailTeste($destinatario, $ipServidorSMTP, $portaSMTP, $loginSMTP, $senhaSMTP, $emailRetorno, $protocoloSMTP, $statusSMTP);

            if ($resultado == true) {

                $response['success'] = true;
                $response['csrf_hash'] = csrf_hash();
                $response['messages'] = 'O e-mail de teste foi enviado com sucesso para "' . $destinatario . '"';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Falha no envio. Verifique novamente as configurações';
            }


            return $this->response->setJSON($response);
        }
    }


    public function integracaoSMTP()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->ServicoSMTPModel->pega_servicosmtp();

        foreach ($result as $key => $value) {

            $ops = '<div class="btn-group">';
            $ops .= '	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Editar" onclick="editSMTP(' . $value->codServidorSMTP . ')"><i class="fa fa-edit"></i></button>';
            $ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="removeSMTP(' . $value->codServidorSMTP . ')"><i class="fa fa-trash"></i></button>';
            $ops .= '</div>';

            $statusSMTP = "";
            if ($value->statusSMTP == 0) {
                $statusSMTP = "Desativado";
            }

            if ($value->statusSMTP == 1) {
                $statusSMTP = "Ativado";
            }

            $protocoloSMTP = "";
            if ($value->protocoloSMTP == 1) {
                $protocoloSMTP = "SSL";
            }
            if ($value->protocoloSMTP == 2) {
                $protocoloSMTP = "TLS";
            }
            if ($value->protocoloSMTP == 3) {
                $protocoloSMTP = "STARTTLS";
            }



            $data['data'][$key] = array(
                $value->codServidorSMTP,
                $value->descricaoServidorSMTP,
                $value->ipServidorSMTP,
                $value->portaSMTP,

                $ops,
            );
        }

        return $this->response->setJSON($data);
    }


    public function getOne()
    {
        $response = array();

        $id = $this->request->getPost('codServidorSMTP');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->ServicoSMTPModel->pegaServicoSMTPModelPorcodServidorSMTP($id);

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function add()
    {

        $response = array();
        $codOrganizacao = session()->codOrganizacao;
        $fields['codServidorSMTP'] = $this->request->getPost('codServidorSMTP');
        $fields['descricaoServidorSMTP'] = $this->request->getPost('descricaoServidorSMTP');
        $fields['ipServidorSMTP'] = $this->request->getPost('ipServidorSMTP');
        $fields['portaSMTP'] = $this->request->getPost('portaSMTP');
        $fields['loginSMTP'] = $this->request->getPost('loginSMTP');
        $fields['senhaSMTP'] = $this->request->getPost('senhaSMTP');
        $fields['emailRetorno'] = $this->request->getPost('emailRetorno');
        $fields['protocoloSMTP'] = $this->request->getPost('protocoloSMTP');
        $fields['statusSMTP'] = $this->request->getPost('statusSMTP');
        $fields['codOrganizacao'] = $codOrganizacao;
        $fields['dataCriacao'] = date('Y-m-d H:i');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');


        $this->validation->setRules([
            'descricaoServidorSMTP' => ['label' => 'Nome Servidor', 'rules' => 'required|max_length[60]'],
            'ipServidorSMTP' => ['label' => 'IP Servidor', 'rules' => 'required|max_length[100]'],
            'portaSMTP' => ['label' => 'Porta SMTP', 'rules' => 'required|max_length[10]'],
            'loginSMTP' => ['label' => 'Login', 'rules' => 'required|max_length[100]'],
            'senhaSMTP' => ['label' => 'Senha', 'rules' => 'required|max_length[64]'],
            'emailRetorno' => ['label' => 'Email Retorno', 'rules' => 'required|max_length[100]'],
            'protocoloSMTP' => ['label' => 'Protocolo', 'rules' => 'required|max_length[10]'],
            'statusSMTP' => ['label' => 'Status SMTP', 'rules' => 'required|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->ServicoSMTPModel->insert($fields)) {

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

        $fields['codServidorSMTP'] = $this->request->getPost('codServidorSMTP');
        $fields['descricaoServidorSMTP'] = $this->request->getPost('descricaoServidorSMTP');
        $fields['ipServidorSMTP'] = $this->request->getPost('ipServidorSMTP');
        $fields['portaSMTP'] = $this->request->getPost('portaSMTP');
        $fields['loginSMTP'] = $this->request->getPost('loginSMTP');
        $fields['senhaSMTP'] = $this->request->getPost('senhaSMTP');
        $fields['emailRetorno'] = $this->request->getPost('emailRetorno');
        $fields['protocoloSMTP'] = $this->request->getPost('protocoloSMTP');
        $fields['statusSMTP'] = $this->request->getPost('statusSMTP');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');


        $this->validation->setRules([
            'codServidorSMTP' => ['label' => 'codServidorSMTP', 'rules' => 'required|numeric|max_length[11]'],
            'descricaoServidorSMTP' => ['label' => 'Nome Servidor', 'rules' => 'required|max_length[60]'],
            'ipServidorSMTP' => ['label' => 'IP Servidor', 'rules' => 'required|max_length[100]'],
            'portaSMTP' => ['label' => 'Porta SMTP', 'rules' => 'required|max_length[10]'],
            'loginSMTP' => ['label' => 'Login', 'rules' => 'required|max_length[100]'],
            'senhaSMTP' => ['label' => 'Senha', 'rules' => 'required|max_length[64]'],
            'emailRetorno' => ['label' => 'Email Retorno', 'rules' => 'required|max_length[100]'],
            'protocoloSMTP' => ['label' => 'Protocolo', 'rules' => 'required|max_length[10]'],
            'statusSMTP' => ['label' => 'Status SMTP', 'rules' => 'required|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->ServicoSMTPModel->update($fields['codServidorSMTP'], $fields)) {

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

        $id = $this->request->getPost('codServidorSMTP');

        if (!$this->validation->check($id, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($this->ServicoSMTPModel->where('codServidorSMTP', $id)->delete()) {

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
