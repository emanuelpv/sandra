<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\AtributosSistemaModel;
use App\Models\ServicoLDAPModel;
use App\Models\AtributosTipoLDAPModel;

class ServicoLDAP extends BaseController
{

    protected $ServicoLDAPModel;
    protected $pessoasModel;
    protected $OrganizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

    public function __construct()
    {

        helper('seguranca_helper');
        verificaSeguranca($this, session(), base_url());
        $this->ServicoLDAPModel = new ServicoLDAPModel();
        $this->OrganizacoesModel = new OrganizacoesModel();
        $this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
        $this->validation =  \Config\Services::validation();
        $this->codOrganizacao = session()->codOrganizacao;
        $this->organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);

        $permissao = verificaPermissao('ServicoLDAP', 'listar');
        if ($permissao == 0) {
            echo mensagemAcessoNegado(session()->organizacoes);
            $this->LogsModel->inserirLog('Acesso indevido ao Módulo ServicoLDAP', session()->codPessoa);
            exit();
        }
    }

    public function index()
    {

        $data = [
            'controller'        => 'servicoLDAP',
            'title'             => 'Serviço LDAP'
        ];
        echo view('tema/cabecalho');
        echo view('tema/menu_vertical');
        echo view('tema/menu_horizontal');
        return view('servicoLDAP', $data);
    }


    public function pegaLogs()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->ServicoLDAPModel->pegaLogs();

        foreach ($result as $key => $value) {

            $ops = '<div class="btn-group">';
            $ops .= '</div>';

            if ($value->tipoLogLDAP == 0) {
                $status = "Falha";
            } else {
                $status = "Sucesso";
            }

            if ($value->codPessoa == 0) {
                $autor = "Admin";
            } else {
                $autor = $value->nomeExibicao;
            }


            $data['data'][$key] = array(
                $value->codLogLDAP,
                $value->descricaoServidorLDAP . ' (' . $value->ipServidorLDAP . ')',
                $value->nomeTipoLDAP,
                $autor,
                $status,
                date('d/m/Y H:i', strtotime($value->dataCriacao)),
                $value->ip,
                $value->ocorrencia,
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

    public function getAll()
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
                $value->portaLDAP,
                $value->loginLDAP,
                '**************',
                $value->tipoHash,
                $statuForcarSSL,
                $value->dn,
                $value->encoding,
                $value->fqdn,
                $value->LDAPOptProtocolVersion,
                $value->LDAPOptReferrals,
                $value->LDAPOptTimeLimit,
                $statusAtivo,
                $master,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }

    public function getOne()
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

    public function add()
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

    public function edit()
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

    public function remove()
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
}
