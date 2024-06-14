<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\OrganizacoesModel;
use App\Models\AtributosTipoLDAPModel;
use App\Models\ServicoLDAPModel;
use App\Models\ServicoSMTPModel;
use App\Models\ProtocolosRedeModel;
use App\Models\ServicosSMSModel;
use App\Models\LogsModel;
use App\Models\MapeamentoAtributosLDAPModel;



class Integracoes extends BaseController
{
    protected $usuariosModel;
    protected $validation;

    public function __construct()
    {
        helper('seguranca_helper');
        verificaSeguranca($this, session(), base_url());
        $this->OrganizacoesModel = new OrganizacoesModel();
        $this->AtributosTipoLDAPModel = new AtributosTipoLDAPModel();
        $this->ServicoLDAPModel = new ServicoLDAPModel();
        $this->validation =  \Config\Services::validation();
        $this->ServicoSMTPModel = new ServicoSMTPModel();
        $this->ProtocolosRedeModel = new ProtocolosRedeModel();
        $this->ServicosSMSModel = new ServicosSMSModel();
        $this->MapeamentoAtributosLDAPModel = new MapeamentoAtributosLDAPModel();
        $this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);


        $permissao = verificaPermissao('Integracoes', 'listar');
        if ($permissao == 0) {
            echo mensagemAcessoNegado(session()->organizacoes);
            $this->LogsModel->inserirLog('Acesso indevido ao Módulo Integracoes', session()->codPessoa);
            exit();
        }
    }

    public function editLDAP()
    {

        $response = array();

        $fields['codServidorLDAP'] = $this->request->getPost('codServidorLDAP');
        $fields['descricaoServidorLDAP'] = $this->request->getPost('descricaoServidorLDAP');
        $fields['codTipoLDAP'] = $this->request->getPost('codTipoLDAP');
        $fields['ipServidorLDAP'] = $this->request->getPost('ipServidorLDAP');
        $fields['portaLDAP'] = $this->request->getPost('portaLDAP');
        $fields['loginLDAP'] = $this->request->getPost('loginLDAP');
        $fields['senhaLDAP'] = $this->request->getPost('senhaLDAP');
        $fields['dn'] = $this->request->getPost('dn');
        $fields['encoding'] = $this->request->getPost('encoding');
        $fields['fqdn'] = $this->request->getPost('fqdn');
        $fields['LDAPOptProtocolVersion'] = $this->request->getPost('lDAPOptProtocolVersion');
        $fields['LDAPOptReferrals'] = $this->request->getPost('lDAPOptReferrals');
        $fields['LDAPOptTimeLimit'] = $this->request->getPost('lDAPOptTimeLimit');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['tipoHash'] = $this->request->getPost('tipoHash');
        if ($this->request->getPost('forcarSSL') == 'on') {
            $fields['forcarSSL'] = 1;
        } else {
            $fields['forcarSSL'] = 0;
        }
        if ($this->request->getPost('status') == 'on') {
            $fields['status'] = 1;
        } else {
            $fields['status'] = 0;
        }

        if ($this->request->getPost('master') == 'on') {
            $fields['master'] = 1;
            $this->ServicoLDAPModel->redefineMaster();
        } else {
            $fields['master'] = 0;
        }
        $this->validation->setRules([
            'codServidorLDAP' => ['label' => 'codServidorLDAP', 'rules' => 'required|numeric|max_length[11]'],
            'descricaoServidorLDAP' => ['label' => 'Nome Servidor', 'rules' => 'required|max_length[100]'],
            'codTipoLDAP' => ['label' => 'Tipo', 'rules' => 'permit_empty|max_length[11]'],
            'ipServidorLDAP' => ['label' => 'IP Servidor', 'rules' => 'permit_empty|max_length[50]'],
            'portaLDAP' => ['label' => 'Porta LDAP', 'rules' => 'required|numeric|max_length[100]'],
            'loginLDAP' => ['label' => 'Login LDAP', 'rules' => 'required|max_length[100]'],
            'senhaLDAP' => ['label' => 'Senha LDAP', 'rules' => 'required'],
            'dn' => ['label' => 'Dn', 'rules' => 'required|max_length[100]'],
            'encoding' => ['label' => 'Encoding', 'rules' => 'required|max_length[20]'],
            'fqdn' => ['label' => 'Fqdn', 'rules' => 'required|max_length[100]'],
            'LDAPOptProtocolVersion' => ['label' => 'LDAPOptProtocolVersion', 'rules' => 'required|numeric|max_length[11]'],
            'LDAPOptReferrals' => ['label' => 'LDAPOptReferrals', 'rules' => 'required|numeric|max_length[11]'],
            'LDAPOptTimeLimit' => ['label' => 'LDAPOptTimeLimit', 'rules' => 'required|numeric|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->ServicoLDAPModel->update($fields['codServidorLDAP'], $fields)) {

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


    public function getOneProtocoloRede()
    {
        $response = array();

        $id = $this->request->getPost('codProtocoloRede');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->ProtocolosRedeModel->pegaPorCodigo($id);

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function addMapeamentoAtributosLDAP()
    {

        $response = array();

        $fields['codMapAttrLDAP'] = $this->request->getPost('codMapAttrLDAP');
        $fields['codServidorLDAP'] = $this->request->getPost('codServidorLDAP');
        $fields['nomeAtributoSistema'] = $this->request->getPost('nomeAtributoSistema');
        $fields['nomeAtributoLDAP'] = $this->request->getPost('nomeAtributoLDAP');
        $fields['codOrganizacao'] = session()->codOrganizacao;


        $this->validation->setRules([
            'codServidorLDAP' => ['label' => 'Servidor LDAP', 'rules' => 'required|numeric|max_length[11]'],
            'nomeAtributoSistema' => ['label' => 'Atributo Sistema', 'rules' => 'required|max_length[60]'],
            'nomeAtributoLDAP' => ['label' => 'Atributo LDAP', 'rules' => 'required|max_length[60]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->MapeamentoAtributosLDAPModel->insert($fields)) {

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

    public function removeSMS()
    {
        $response = array();

        $id = $this->request->getPost('codServicoSMS');

        if (!$this->validation->check($id, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($this->ServicosSMSModel->where('codServicoSMS', $id)->delete()) {

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

    public function editSMS()
    {

        $response = array();

        $fields['codServicoSMS'] = $this->request->getPost('codServicoSMS');
        $fields['codOrganizacao'] = session()->codOrganizacao;
        $fields['codProvedor'] = $this->request->getPost('codProvedor');
        $fields['token'] = $this->request->getPost('token');
        $fields['conta'] = $this->request->getPost('conta');
        $fields['statusSMS'] = $this->request->getPost('statusSMS');


        $this->validation->setRules([
            'codServicoSMS' => ['label' => 'codServicoSMS', 'rules' => 'required|numeric|max_length[11]'],
            'codOrganizacao' => ['label' => 'CodOrganizacao', 'rules' => 'required|numeric|max_length[11]'],
            'codProvedor' => ['label' => 'CodProvedor', 'rules' => 'required|max_length[11]'],
            'token' => ['label' => 'Token', 'rules' => 'permit_empty|max_length[100]'],
            'conta' => ['label' => 'Conta', 'rules' => 'permit_empty|max_length[100]'],
            'statusSMS' => ['label' => 'StatusSMS', 'rules' => 'required|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->ServicosSMSModel->update($fields['codServicoSMS'], $fields)) {

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

    public function getOneSMS()
    {
        $response = array();

        $id = $this->request->getPost('codServicoSMS');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->ServicosSMSModel->pegaPorCodigo($id);

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function addSMS()
    {

        $response = array();

        $fields['codServicoSMS'] = $this->request->getPost('codServicoSMS');
        $fields['codOrganizacao'] = session()->codOrganizacao;
        $fields['codProvedor'] = $this->request->getPost('codProvedor');
        $fields['token'] = $this->request->getPost('token');
        $fields['conta'] = $this->request->getPost('conta');
        $fields['statusSMS'] = $this->request->getPost('statusSMS');


        $this->validation->setRules([
            'codOrganizacao' => ['label' => 'CodOrganizacao', 'rules' => 'required|numeric|max_length[11]'],
            'codProvedor' => ['label' => 'CodProvedor', 'rules' => 'required|max_length[11]'],
            'token' => ['label' => 'Token', 'rules' => 'permit_empty|max_length[100]'],
            'conta' => ['label' => 'Conta', 'rules' => 'permit_empty|max_length[100]'],
            'statusSMS' => ['label' => 'StatusSMS', 'rules' => 'required|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->ServicosSMSModel->insert($fields)) {

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

    public function removeMapeamentoAtributosLDAP()
    {
        $response = array();

        $id = $this->request->getPost('codMapAttrLDAP');

        if (!$this->validation->check($id, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($this->MapeamentoAtributosLDAPModel->where('codMapAttrLDAP', $id)->delete()) {

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


    public function editMapeamentoAtributosLDAP()
    {

        $response = array();

        $fields['codMapAttrLDAP'] = $this->request->getPost('codMapAttrLDAP');
        $fields['codServidorLDAP'] = $this->request->getPost('codServidorLDAP');
        $fields['nomeAtributoSistema'] = $this->request->getPost('nomeAtributoSistema');
        $fields['nomeAtributoLDAP'] = $this->request->getPost('nomeAtributoLDAP');


        $this->validation->setRules([
            'codMapAttrLDAP' => ['label' => 'codMapAttrLDAP', 'rules' => 'required|numeric|max_length[11]'],
            'codServidorLDAP' => ['label' => 'Servidor LDAP', 'rules' => 'required|numeric|max_length[11]'],
            'nomeAtributoSistema' => ['label' => 'Atributo Sistema', 'rules' => 'required|max_length[60]'],
            'nomeAtributoLDAP' => ['label' => 'Atributo LDAP', 'rules' => 'required|max_length[60]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->MapeamentoAtributosLDAPModel->update($fields['codMapAttrLDAP'], $fields)) {

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

    public function getOneMapeamentoAtributosLDAP()
    {
        $response = array();

        $id = $this->request->getPost('codMapAttrLDAP');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->MapeamentoAtributosLDAPModel->pegaPorCodigo($id);

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }



    public function removeProtocolosRede()
    {
        $response = array();

        $id = $this->request->getPost('codProtocoloRede');

        if (!$this->validation->check($id, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($this->ProtocolosRedeModel->where('codProtocoloRede', $id)->delete()) {

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


    public function editProtocoloRede()
    {

        $response = array();

        $fields['codProtocoloRede'] = $this->request->getPost('codProtocoloRede');
        $fields['nomeProtocoloRede'] = $this->request->getPost('nomeProtocoloRede');
        $fields['conector'] = $this->request->getPost('conector');
        $fields['portaPadrao'] = $this->request->getPost('portaPadrao');


        $this->validation->setRules([
            'codProtocoloRede' => ['label' => 'codProtocoloRede', 'rules' => 'required|numeric|max_length[11]'],
            'nomeProtocoloRede' => ['label' => 'Nome', 'rules' => 'required|max_length[40]'],
            'conector' => ['label' => 'Conector', 'rules' => 'required|max_length[40]'],
            'portaPadrao' => ['label' => 'Porta Padrão', 'rules' => 'required|numeric|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->ProtocolosRedeModel->update($fields['codProtocoloRede'], $fields)) {

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


    public function addProtocoloRede()
    {

        $response = array();

        $fields['codProtocoloRede'] = $this->request->getPost('codProtocoloRede');
        $fields['nomeProtocoloRede'] = $this->request->getPost('nomeProtocoloRede');
        $fields['conector'] = $this->request->getPost('conector');
        $fields['portaPadrao'] = $this->request->getPost('portaPadrao');


        $this->validation->setRules([
            'nomeProtocoloRede' => ['label' => 'Nome', 'rules' => 'required|max_length[40]'],
            'conector' => ['label' => 'Conector', 'rules' => 'required|max_length[40]'],
            'portaPadrao' => ['label' => 'Porta Padrão', 'rules' => 'required|numeric|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->ProtocolosRedeModel->insert($fields)) {

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


    public function removeLDAP()
    {
        $response = array();

        $id = $this->request->getPost('codServidorLDAP');

        if (!$this->validation->check($id, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($this->ServicoLDAPModel->where('codServidorLDAP', $id)->delete()) {

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

    public function pegaServidorLDAP($codServidorLDAP)
    {
        $response = array();

        $data['data'] = array();

        $result = $this->MapeamentoAtributosLDAPModel->pegaTudoPorServidor($codServidorLDAP);

        foreach ($result as $key => $value) {

            $ops = '<div class="btn-group">';
            $ops .= '	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Editar" onclick="editmapeamentoAtributosLDAP(' . $value->codMapAttrLDAP . ')"><i class="fa fa-edit"></i></button>';
            $ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="removemapeamentoAtributosLDAP(' . $value->codMapAttrLDAP . ')"><i class="fa fa-trash"></i></button>';
            $ops .= '</div>';

            $data['data'][$key] = array(
                $value->codMapAttrLDAP,
                $value->descricaoServidorLDAP,
                $value->nomeAtributoSistema,
                $value->nomeAtributoLDAP,

                $ops,
            );
        }

        return $this->response->setJSON($data);
    }


    public function getOneLDAP()
    {
        $response = array();

        $id = $this->request->getPost('codServidorLDAP');

        session()->set('codServidorLDAP', $id);

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->ServicoLDAPModel->pegaPorCodigo($id);

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function addLDAP()
    {
        $codOrganizacao = session()->codOrganizacao;

        $response = array();

        $fields['codServidorLDAP'] = $this->request->getPost('codServidorLDAP');
        $fields['descricaoServidorLDAP'] = $this->request->getPost('descricaoServidorLDAP');
        $fields['codTipoLDAP'] = $this->request->getPost('codTipoLDAP');
        $fields['ipServidorLDAP'] = $this->request->getPost('ipServidorLDAP');
        $fields['portaLDAP'] = $this->request->getPost('portaLDAP');
        $fields['loginLDAP'] = $this->request->getPost('loginLDAP');
        $fields['senhaLDAP'] = $this->request->getPost('senhaLDAP');
        $fields['dn'] = $this->request->getPost('dn');
        $fields['encoding'] = $this->request->getPost('encoding');
        $fields['fqdn'] = $this->request->getPost('fqdn');
        $fields['LDAPOptProtocolVersion'] = $this->request->getPost('lDAPOptProtocolVersion');
        $fields['LDAPOptReferrals'] = $this->request->getPost('lDAPOptReferrals');
        $fields['LDAPOptTimeLimit'] = $this->request->getPost('lDAPOptTimeLimit');
        $fields['dataCriacao'] = date('Y-m-d H:i');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['codOrganizacao'] = $codOrganizacao;
        $fields['tipoHash'] = $this->request->getPost('tipoHash');
        if ($this->request->getPost('forcarSSL') == 'on') {
            $fields['forcarSSL'] = 1;
        } else {
            $fields['forcarSSL'] = 0;
        }

        if (empty($this->ServicoLDAPModel->pegaMaster()) == true) {
            $fields['master'] = 1;
        } else {
            if ($this->request->getPost('master') == 'on') {
                $fields['master'] = 1;
                $this->ServicoLDAPModel->redefineMaster();
            } else {
                $fields['master'] = 0;
            }
        }


        $this->validation->setRules([
            'descricaoServidorLDAP' => ['label' => 'Nome Servidor', 'rules' => 'required|max_length[100]'],
            'codTipoLDAP' => ['label' => 'Tipo', 'rules' => 'permit_empty|max_length[11]'],
            'ipServidorLDAP' => ['label' => 'IP Servidor', 'rules' => 'permit_empty|max_length[50]'],
            'portaLDAP' => ['label' => 'Porta LDAP', 'rules' => 'required|numeric|max_length[100]'],
            'loginLDAP' => ['label' => 'Login LDAP', 'rules' => 'required|max_length[100]'],
            'senhaLDAP' => ['label' => 'Senha LDAP', 'rules' => 'required'],
            'dn' => ['label' => 'Dn', 'rules' => 'required|max_length[100]'],
            'encoding' => ['label' => 'Encoding', 'rules' => 'required|max_length[20]'],
            'fqdn' => ['label' => 'Fqdn', 'rules' => 'required|max_length[100]'],
            'LDAPOptProtocolVersion' => ['label' => 'LDAPOptProtocolVersion', 'rules' => 'required|numeric|max_length[11]'],
            'LDAPOptReferrals' => ['label' => 'LDAPOptReferrals', 'rules' => 'required|numeric|max_length[11]'],
            'LDAPOptTimeLimit' => ['label' => 'LDAPOptTimeLimit', 'rules' => 'required|numeric|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->ServicoLDAPModel->insert($fields)) {

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

    public function testeConexaoLDAP()
    {
        $response = array();
        $ipServidorLDAP = $_POST['ipServidorLDAP'];
        $codTipoLDAP = $_POST['codTipoLDAP'];
        $portaLDAP = $_POST['portaLDAP'];
        $loginLDAP = $_POST['loginLDAP'];
        $senhaLDAP = $_POST['senhaLDAP'];
        $dn = $_POST['dn'];
        $encoding = $_POST['encoding'];
        $fqdn = $_POST['fqdn'];
        $LDAPOptProtocolVersion = $_POST['LDAPOptProtocolVersion'];
        $LDAPOptTimeLimit = $_POST['LDAPOptTimeLimit'];
        $lDAPOptReferrals = $_POST['lDAPOptReferrals'];
        $descricaoServidorLDAP = $_POST['descricaoServidorLDAP'];
        $forcarSSL = $_POST['forcarSSL'];

        if ($resultadoTeste = $this->ServicoLDAPModel->testeLDAP($_POST) == true) {
            $response['messages'] = 'Conexão realizada com sucesso para o servidor ' . $descricaoServidorLDAP . ' (' . $ipServidorLDAP . ':' . $portaLDAP . ')';
        } else {
            $response['messages'] = 'Falha na conexão com o servidor ' . $descricaoServidorLDAP . ' (' . $ipServidorLDAP . ':' . $portaLDAP . '). Verifique os parâmetros fornecidos!';
        }

        $response['resultadoTeste'] =  $resultadoTeste;
        $response['ipServidorLDAP'] =  $ipServidorLDAP;
        $response['descricaoServidorLDAP'] =  $descricaoServidorLDAP;

        return $this->response->setJSON($response);
    }



    public function getOneorganizacoes()
    {
        $response = array();

        $id = $this->request->getPost('codOrganizacao');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->OrganizacoesModel->where('codOrganizacao', $id)->first();

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
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


    public function addSMTP()
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

    public function removeSMTP()
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

    public function editSMTP()
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


    public function getOneSMTP()
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



    public function salvarSPED()
    {

        $response = array();


        $fields['codOrganizacao'] = $this->request->getPost('codOrganizacao');
        $fields['servidorSpedDB'] = $this->request->getPost('servidorSpedDB');
        $fields['SpedDB'] = $this->request->getPost('SpedDB');
        $fields['administradorSpedDB'] = $this->request->getPost('administradorSpedDB');
        $fields['senhaadministradorSpedDB'] = $this->request->getPost('senhaadministradorSpedDB');
        $fields['checkboxSPED'] = $this->request->getPost('checkboxSPED');


        if ($this->request->getPost('checkboxSPED') == 'on') {
            $fields['servidorSPEDStatus'] = '1';
        } else {
            $fields['servidorSPEDStatus'] = '0';
        }

        $fields['dataAtualizacao'] = date('Y-m-d H:i');

        $this->validation->setRules([
            'codOrganizacao' => ['label' => 'codOrganizacao', 'rules' => 'required|numeric|max_length[11]'],
            'servidorSpedDB' => ['label' => 'servidorSpedDB', 'rules' => 'required|max_length[100]'],
            'SpedDB' => ['label' => 'SpedDB', 'rules' => 'permit_empty|max_length[100]'],
            'administradorSpedDB' => ['label' => 'administradorSpedDB', 'rules' => 'permit_empty|max_length[50]'],
            'senhaadministradorSpedDB' => ['label' => 'senhaadministradorSpedDB', 'rules' => 'permit_empty|max_length[50]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->OrganizacoesModel->update($fields['codOrganizacao'], $fields)) {

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

    public function getAllSMS()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->ServicosSMSModel->pegaTudo();
        $x = 0;

        // SERVIÇOS:
        // 1 - ZENVIA
        // 2 - MEX
        // 3 - TWILIO



        foreach ($result as $key => $value) {
            $x++;
            $ops = '<div class="btn-group">';
            $ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editservicosSMS(' . $value->codServicoSMS . ')"><i class="fa fa-edit"></i></button>';
            $ops .= '	<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeservicosSMS(' . $value->codServicoSMS . ')"><i class="fa fa-trash"></i></button>';
            $ops .= '</div>';

            if ($value->codProvedor == 1) {
                $provedor = 'ZENVIA';
            }
            if ($value->codProvedor == 2) {
                $provedor = 'MEX';
            }
            if ($value->codProvedor == 3) {
                $provedor = 'TWILIO';
            }


            if ($value->statusSMS == 1) {
                $statusSMS = 'Ativado';
            }
            if ($value->statusSMS == 0) {
                $statusSMS = 'Desativado';
            }


            $data['data'][$key] = array(
                $x,
                $provedor,
                $statusSMS,

                $ops,
            );
        }

        return $this->response->setJSON($data);
    }


    public function integracaoProtocolos()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->ProtocolosRedeModel->pegaTudo();

        foreach ($result as $key => $value) {

            $ops = '<div class="btn-group">';
            $ops .= '	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Editar" onclick="editprotocolosRede(' . $value->codProtocoloRede . ')"><i class="fa fa-edit"></i></button>';
            $ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="removeprotocolosRede(' . $value->codProtocoloRede . ')"><i class="fa fa-trash"></i></button>';
            $ops .= '</div>';

            $data['data'][$key] = array(
                $value->codProtocoloRede,
                $value->nomeProtocoloRede,
                $value->portaPadrao,

                $ops,
            );
        }

        return $this->response->setJSON($data);
    }

    public function servidoresIntegracaoLDAP()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->ServicoLDAPModel->pegaTudo();

        foreach ($result as $key => $value) {

            $ops = '<div class="btn-group">';
            $ops .= '	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Editar" onclick="editservicoLDAP(' . $value->codServidorLDAP . ')"><i class="fa fa-edit"></i></button>';
            $ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="removeservicoLDAP(' . $value->codServidorLDAP . ')"><i class="fa fa-trash"></i></button>';
            $ops .= '</div>';

            if ($value->forcarSSL == 1) {
                $statuForcarSSL = "Sim";
            } else {
                $statuForcarSSL = "Não";
            }

            if ($value->status == 1) {
                $statusAtivo = "Sim";
            } else {
                $statusAtivo = "Não";
            }
            if ($value->master == 1) {
                $master = "Sim";
            } else {
                $master = "Não";
            }
            $data['data'][$key] = array(
                $value->codServidorLDAP,
                $value->descricaoServidorLDAP,
                $value->nomeTipoLDAP,
                $value->ipServidorLDAP,
                $ops,
            );
        }

        return $this->response->setJSON($data);
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

    public function atributosLDAP()
    {

        $this->AtributosTipoLDAPModel = new AtributosTipoLDAPModel;

        $codTipoLDAP = $this->request->getPost('codTipoLDAP');
        if ($this->validation->check($codTipoLDAP, 'required|numeric')) {
            $atributoTipoLDAP = $this->AtributosTipoLDAPModel->pegaPorTipoLDAPSelect($codTipoLDAP);


            return $this->response->setJSON($atributoTipoLDAP);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function salvaPropriedadesServidorLDAP()
    {
        $fields['codServidorLDAP'] = $this->request->getPost('codServidorLDAP');
        $fields['dnNovosUsuarios'] = $this->request->getPost('dnNovosUsuarios');
        $fields['sambaSID'] = $this->request->getPost('sambaSID');
        $fields['servidorArquivo'] = $this->request->getPost('servidorArquivo');
        $fields['atributoChave'] = $this->request->getPost('atributoChave');


        if ($this->request->getPost('codTipoMicrosoft') == NULL or $this->request->getPost('codTipoMicrosoft') == 0) {
            $fields['codTipoMicrosoft'] = NULL;
        } else {
            $fields['codTipoMicrosoft'] = $this->request->getPost('codTipoMicrosoft');
        }

        $this->validation->setRules([
            'codServidorLDAP' => ['label' => 'codServidorLDAP', 'rules' => 'required|numeric'],
            'dnNovosUsuarios' => ['label' => 'Base DN (Distinguished Name) para a criação de novos usuários', 'rules' => 'required|max_length[250]'],
            'atributoChave' => ['label' => 'AtributoChave', 'rules' => 'required|max_length[250]'],
            'codTipoMicrosoft' => ['label' => 'Versão do Servidor', 'rules' => 'permit_empty|numeric'],
        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->ServicoLDAPModel->update($fields['codServidorLDAP'], $fields)) {

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
    public function listBoxDNNovosUsuarios($codServidorLDAP = null)
    {

        if ($codServidorLDAP !== NULL) {
            $codServidorLDAP = $codServidorLDAP;
        } else {
            $codServidorLDAP = $this->request->getPost('codServidorLDAP');
        }
        $servidor = $this->ServicoLDAPModel->pegaPorCodigo($codServidorLDAP);

        if ($this->validation->check($codServidorLDAP, 'required|numeric')) {

            $loginLDAP = $this->ServicoLDAPModel->conectaldap(null, null, $codServidorLDAP);

            $dados = array();
            //array_push($dados, array('id' => $servidor->dn, 'text' => 'RAIZ'));
            array_push($dados, array('id' => $servidor->dnNovosUsuarios, 'text' => $servidor->dnNovosUsuarios . ' (ATUAL)'));
            array_push($dados, array('id' => $servidor->dn, 'text' => $servidor->dn . ' (RAIZ)'));
            if ($servidor->codTipoLDAP == 1) {
                array_push($dados, array('id' => 'CN=Users,' . $servidor->dn, 'text' => 'CN=Users,' . $servidor->dn . ' (USERS)'));
            }

            if ($loginLDAP['status'] == 1) {

                $unidadesOrganizacionais = $this->ServicoLDAPModel->pegaUnidadesOrganizacionais($loginLDAP['tipoldap'], $orderby = 'sn');



                for ($i = 0; $i < $unidadesOrganizacionais["count"]; $i++) {
                    //array_push($dados, array('id' => $unidadesOrganizacionais[$i]["dn"], 'text' => $unidadesOrganizacionais[$i]['ou'][0]));
                    array_push($dados, array('id' => $unidadesOrganizacionais[$i]["dn"], 'text' => $unidadesOrganizacionais[$i]["dn"] . " (" . $unidadesOrganizacionais[$i]['ou'][0] . ")"));
                }
            }
            return $this->response->setJSON($dados);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function index()
    {

        $data['organizacao'] = $this->OrganizacoesModel->select('codOrganizacao,descricao')->findAll();
        helper('form');
        echo view('tema/cabecalho');
        echo view('tema/menu_vertical');
        echo view('tema/menu_horizontal');
        return view('integracoes', $data);
    }
}
