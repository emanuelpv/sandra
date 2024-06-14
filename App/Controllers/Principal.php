<?php


namespace App\Controllers;

use CodeIgniter\Services;
use App\Controllers\BaseController;
use App\Models\OrganizacoesModel;
use App\Models\QuestionariosModel;
use App\Models\AtalhosModel;
use App\Models\PerfilPessoasMembroModel;
use App\Models\ModulosModel;
use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\PacientesModel;
use App\Models\ServicoLDAPModel;


class Principal extends BaseController
{
    protected $usuariosModel;
    protected $validation;
    public $request;


    public function __construct()
    {


        helper('seguranca_helper');
        verificaSeguranca($this, session(), base_url());

        $this->PerfilPessoasMembroModel = new PerfilPessoasMembroModel();
        $this->atalhosModel = new AtalhosModel();
        $this->ModulosModel = new ModulosModel();
        $this->ServicoLDAPModel = new ServicoLDAPModel();
        $this->OrganizacoesModel = new OrganizacoesModel();
        $this->ServicoLDAPModel = new ServicoLDAPModel();
        $this->QuestionariosModel = new QuestionariosModel();
        $this->PessoasModel = new PessoasModel();
        $this->PacientesModel = new PacientesModel();
        $this->validation =  \Config\Services::validation();
        $this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);




    }



    function enviaFoto()
    {

        //FAZ O UPLOAD E GRAVA NO BANCO

        $response = array();

        $avatar = $this->request->getFile('file');

        if ($this->request->getFile('file') == NULL) {
            $response['success'] = false;
            return $this->response->setJSON($response);
        }

        $nomeArquivo = removeCaracteresIndesejados($this->request->getPost('codPessoa'))  . '.' . $avatar->getClientExtension();
        $avatar->move(WRITEPATH . '../arquivos/imagens/pessoas/',  $nomeArquivo, true);



        $fields['fotoPerfil'] =  $nomeArquivo;


        $db      = \Config\Database::connect();
        $builder = $db->table('sis_pessoas');


        $builder->where('codPessoa', $this->request->getPost('codPessoa'));
        $builder->update($fields);



        $response['success'] = true;
        $response['csrf_hash'] = csrf_hash();
        $response['messages'] = 'Sucesso!';
        $response['meuCodPessoa'] = session()->codPessoa;
        $response['nomeArquivo'] =  $nomeArquivo;



        if (session()->codPessoa == $this->request->getPost('codPessoa')) {
            session()->set('fotoPerfil', $nomeArquivo);
        }
        return $this->response->setJSON($response);
    }

    function mudaPerfil($codPessoa, $codPerfil)
    {

        if (session()->codPessoa !== $codPessoa or session()->codPaciente !== NULL) {
            echo mensagemAcessoNegado(session()->organizacoes);
            $this->LogsModel->inserirLog('Acesso indevido ao tentando mudar o perfil de acesso', session()->codPessoa);
            exit();
        }



        $meusPerfis = $this->PerfilPessoasMembroModel->pegaMeusPerfis($codPessoa);

        $codigosPerfis = array();
        foreach ($meusPerfis as $perfis) {
            array_push($codigosPerfis, $perfis->codPerfil);
        }
        if (in_array($codPerfil, $codigosPerfis) == TRUE) {
            $meusModulos = $this->PerfilPessoasMembroModel->pegaMinhasPermissoesModulos($codPessoa, $codPerfil);
            session()->perfilSessao = $codPerfil;
            session()->set('meusModulos', $meusModulos);
            return redirect()->to('principal/?autorizacao=' . session()->autorizacao);
        } else {
            echo mensagemAcessoNegado(session()->organizacoes);
            $this->LogsModel->inserirLog('Acesso indevido ao tentando mudar o perfil de acesso', session()->codPessoa);
            exit();
        }
    }

    public function index()
    {

        $data['organizacao'] = $this->OrganizacoesModel->select('codOrganizacao,descricao,chaveSalgada,fundo')->findAll();


        /*
		$chave = $data['organizacao'][0]->chaveSalgada;
		$tipo_cifra = 'des';

		//CRIPTOGRAFIA DE SENHA
		$criptografado = encriptar($chave, $tipo_cifra, 'paralelepipado');
		print  $criptografado;

		*/

        helper('form');
        echo view('tema/cabecalho');
        echo view('tema/menu_vertical');
        echo view('tema/menu_horizontal');
        return view('principal', $data);
    }




    public function pegaPessoa()
    {


        if ($this->request->getPost('codPessoa') == session()->codPessoa) {
            $id = $this->request->getPost('codPessoa');

            if ($this->validation->check($id, 'required|numeric')) {

                $data = $this->PessoasModel->where('codPessoa', $id)->first();

                return $this->response->setJSON($data);
            } else {

                throw new \CodeIgniter\Exceptions\PageNotFoundException();
            }
        } else {

            $data['success'] = false;
            $data['messages'] = 'VIOLAÇÃO DE ACESSO: Você não pode acessar o conteúdo de outra pessoa';
            return $this->response->setJSON($data);
        }
    }


    public function editPessoa()
    {
        sleep(2);

        $response = array();

        $fields['codOrganizacao'] = $this->request->getPost('codOrganizacao');
        $fields['codPessoa'] = $this->request->getPost('codPessoa');
        $fields['nomeCompleto'] = mb_strtoupper($this->request->getPost('nomeCompleto'), "utf-8");
        $fields['nomeExibicao'] = mb_strtoupper($this->request->getPost('nomeExibicao'), "utf-8");

        if (session()->codPessoa == $this->request->getPost('codPessoa')) {

            session()->nomeExibicao = $this->request->getPost('nomeExibicao');
        }

        $fields['nomePrincipal'] = mb_strtoupper($this->request->getPost('nomePrincipal'), "utf-8");
        $fields['codDepartamento'] = $this->request->getPost('codDepartamento');
        $fields['codFuncao'] = $this->request->getPost('codFuncao');
        $fields['codCargo'] = $this->request->getPost('codCargo');
        $fields['codEspecialidade'] = $this->request->getPost('codEspecialidade');
        $fields['identidade'] = $this->request->getPost('identidade');
        $fields['cpf'] = $this->request->getPost('cpf');
        $fields['codPlano'] = $this->request->getPost('codPlano');
        $fields['emailFuncional'] = mb_strtolower($this->request->getPost('emailFuncional'), "utf-8");
        $fields['emailPessoal'] = mb_strtolower($this->request->getPost('emailPessoal'), "utf-8");
        $fields['telefoneTrabalho'] = $this->request->getPost('telefoneTrabalho');
        $fields['celular'] = $this->request->getPost('celular');
        $fields['endereco'] = $this->request->getPost('endereco');
        if ($this->request->getPost('ativo') == 'on') {
            $fields['ativo'] = 1;
        } else {
            $fields['ativo'] = 0;
        }
        if ($this->request->getPost('aceiteTermos') == 'on') {
            $fields['aceiteTermos'] = 1;
        } else {
            $fields['aceiteTermos'] = 0;
        }
        $fields['dataInicioEmpresa'] = $this->request->getPost('dataInicioEmpresa');
        $fields['dataNascimento'] = $this->request->getPost('dataNascimento');
        $fields['nrEndereco'] = $this->request->getPost('nrEndereco');
        $fields['codMunicipioFederacao'] = $this->request->getPost('codMunicipioFederacao');
        $fields['reservadoSimNao'] = $this->request->getPost('reservadoSimNao');
        $fields['reservadoTexto100'] = $this->request->getPost('reservadoTexto100');
        $fields['reservadoNumero'] = $this->request->getPost('reservadoNumero');
        $fields['cep'] = $this->request->getPost('cep');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['codPerfilPadrao'] = $this->request->getPost('codPerfilPadrao');
        $fields['informacoesComplementares'] = $this->request->getPost('informacoesComplementares');
        $fields['pai'] = $this->request->getPost('pai');



        $this->validation->setRules([
            'codPessoa' => ['label' => 'codPessoa', 'rules' => 'required|numeric|max_length[11]'],
            'nomeExibicao' => ['label' => 'Nome exibição', 'rules' => 'required|max_length[40]'],
            'nomeCompleto' => ['label' => 'Nome completo', 'rules' => 'required|max_length[100]'],
            'identidade' => ['label' => 'Identidade', 'rules' => 'permit_empty|max_length[15]'],
            'cpf' => ['label' => 'cpf', 'rules' => 'permit_empty|max_length[15]'],
            'emailFuncional' => ['label' => 'Email funcional', 'rules' => 'permit_empty|max_length[40]'],
            'emailPessoal' => ['label' => 'Email pessoal', 'rules' => 'permit_empty|max_length[40]'],
            'codEspecialidade' => ['label' => 'Especialidade', 'rules' => 'permit_empty|max_length[11]'],
            'telefoneTrabalho' => ['label' => 'Telefone trabalho', 'rules' => 'permit_empty|max_length[16]'],
            'celular' => ['label' => 'Celular', 'rules' => 'permit_empty|max_length[16]'],
            'endereco' => ['label' => 'Endereço', 'rules' => 'permit_empty|max_length[200]'],
            'senha' => ['label' => 'Senha', 'rules' => 'permit_empty|max_length[200]'],
            'dataInicioEmpresa' => ['label' => 'Data início empresa', 'rules' => 'permit_empty|valid_date'],
            'datanascimento' => ['label' => 'Data de nascimento', 'rules' => 'permit_empty|valid_date'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->PessoasModel->update($fields['codPessoa'], $fields)) {

                $response['success'] = true;
                $response['csrf_hash'] = csrf_hash();
                $response['messages'] = 'Atualizado com sucesso';
            } else {

                $response['success'] = true;
                $response['csrf_hash'] = csrf_hash();
                $response['messages'] = 'Erro na atualização!';
            }
        }

        return $this->response->setJSON($response);
    }


    public function exportarPessoa($codPessoa = NULL, $codServidorLDAP = NULL)
    {
        if ($this->request->getPost('codPessoa') !== NULL) {
            $codPessoa = $this->request->getPost('codPessoa');
        }

        $response = exportarPessoaHelper($this, $codPessoa, $codServidorLDAP);

        return $this->response->setJSON($response);
    }



    public function verificaPendenciaSenha()
    {
        $response = array();

        if (session()->codPaciente !== NULL) {
            $response['pendencias'] = true;
            return $this->response->setJSON($response);
        }
        $codPessoa = session()->codPessoa;
        $codOrganizacao = session()->codOrganizacao;

        $pessoa = $this->PessoasModel->organizacaoPessoa($codPessoa);

        if ($pessoa->senha == NULL and $codPessoa !== 0) {
            $response['pendencias'] = true;
        }

        return $this->response->setJSON($response);
    }
}
