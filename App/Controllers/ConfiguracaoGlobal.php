<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\IncomingRequest;
use App\Models\OrganizacoesModel;
use App\Models\ConfiguracaoGlobalMOdel;
use App\Models\LogsModel;
use CodeIgniter\HTTP\Files\UploadedFile;

class ConfiguracaoGlobal extends BaseController
{

    protected $validation;

    public function __construct()
    {


        helper('seguranca_helper');
        verificaSeguranca($this, session(), base_url());
        $this->OrganizacoesModel = new OrganizacoesModel();
        $this->ConfiguracaoGlobalMOdel = new ConfiguracaoGlobalMOdel();
        $this->LogsModel = new LogsModel();
        $this->validation =  \Config\Services::validation();
        $permissao = verificaPermissao('ConfiguracaoGlobal', 'listar');
        if ($permissao == 0) {
            echo mensagemAcessoNegado(session()->organizacoes);
            $this->LogsModel->inserirLog('Acesso indevido ao Módulo ConfiguracaoGlobal', session()->codPessoa);
            exit();
        }
    }

    public function index()
    {



        $dados['organizacao'] = $this->OrganizacoesModel->pegaOrganizacao(session()->codOrganizacao);
        $dados['configuracaoGlobal'] = $this->ConfiguracaoGlobalMOdel->pegaConfiguracaoGlobal();

        helper('form');
        echo view('tema/cabecalho');
        echo view('tema/menu_vertical');
        echo view('tema/menu_horizontal');
        return view('configuracaoGlobal', $dados);
    }

    public function salvar()
    {

        $codOrganizacao = session()->codOrganizacao;

        $dadosOrganizacao['codTimezone'] = $this->request->getPost('codTimezone');
        $dadosOrganizacao['chaveSalgada'] = $this->request->getPost('chaveSalgada');
        $dadosOrganizacao['tempoInatividade'] = $this->request->getPost('tempoInatividade');
        $dadosOrganizacao['forcarExpiracao'] = $this->request->getPost('forcarExpiracao');
        $dadosOrganizacao['loginAdmin'] = $this->request->getPost('loginAdmin');


        $organizacao = $this->OrganizacoesModel->pegaOrganizacao($codOrganizacao);

        $timezone = $this->OrganizacoesModel->pegaTimezone($this->request->getPost('codTimezone'));

        session()->codTimezone = $this->request->getPost('codTimezone');
        session()->timezone = $timezone->nome;



        //NÃO ATUALIZA A SENHA SE FOR A MESMA
        if ($this->request->getPost('senhaAdmin') !== $organizacao->senhaAdmin) {

            $dadosOrganizacao['senhaAdmin'] = hash("sha256", $this->request->getPost('senhaAdmin'));
        }

        $db      = \Config\Database::connect();
        $builderOrganizacao = $db->table('sis_organizacoes');
        $builderOrganizacao->where('codOrganizacao', session()->codOrganizacao);



        if ($this->request->getPost('permiteAutocadastro') == 'on') {
            $permiteAutocadastro = 1;
        } else {
            $permiteAutocadastro = 0;
        }
        $dadosConfiguracaoGlobal['permiteAutocadastro'] = $permiteAutocadastro;
        $builderconfiguracaoGlobal = $db->table('sis_configuracaoGlobal');
        $builderconfiguracaoGlobal->where('codConfiguracaoGlobal', 1);

        //RECONFIGURA VARIÁVEISDE TEMPO DE SEÇÃO
        session()->tempoInatividade =  $this->request->getPost('tempoInatividade');
        session()->forcarExpiracao =  $this->request->getPost('forcarExpiracao');

        if ($builderOrganizacao->update($dadosOrganizacao) and  $builderconfiguracaoGlobal->update($dadosConfiguracaoGlobal)) {

            return redirect()->to(base_url('configuracaoGlobal'))->with('mensagem_sucesso',    'Atualização realizada com sucesso');
        } else {
            return redirect()->to(base_url('configuracaoGlobal'))->with('mensagem_erro',    'Atualização realizada com falha');
        }
    }
}
