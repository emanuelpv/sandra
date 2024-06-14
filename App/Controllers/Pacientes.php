<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\AtributosSistemaModel;
use App\Models\LogsModel;
use App\Models\PacientesModel;
use App\Models\PessoasModel;
use App\Models\ServicoLDAPModel;
use App\Models\CargosModel;
use App\Models\AtendimentosModel;
use App\Models\OrganizacoesModel;
use App\Models\RequisicaoModel;
use App\Models\AtributosSistemaOrganizacaoModel;
use App\Models\PacientesAlergiaModel;
use App\Models\EspecialidadesModel;
use App\Models\MapeamentoAtributosLDAPModel;
use App\Models\PerfilPacientesMembroModel as PerfilPacientesMembroModel;


class Pacientes extends BaseController
{

    protected $PacientesModel;
    public $request;
    protected $OrganizacoesModel;
    protected $Organizacao;
    protected $AtrubutosOrganizacaoSistama;
    protected $validation;


    public function __construct()
    {

        helper('seguranca_helper');
        verificaSeguranca($this, session(), base_url());
        $this->LogsModel = new LogsModel();



        $this->PacientesAlergiaModel = new PacientesAlergiaModel();
        $this->PacientesModel = new PacientesModel();
        $this->PessoasModel = new PessoasModel();
        $this->OrganizacoesModel = new OrganizacoesModel();
        $this->RequisicaoModel = new RequisicaoModel();
        $this->ServicoLDAPModel = new ServicoLDAPModel();
        $this->AtendimentosModel = new AtendimentosModel();
        $this->EspecialidadesModel = new EspecialidadesModel();
        $this->CargosModel = new CargosModel();
        $this->AtributosSistemaModel = new AtributosSistemaModel();
        $this->MapeamentoAtributosLDAPModel = new MapeamentoAtributosLDAPModel();


        $AtrubutosSistama = $this->AtributosSistemaModel->pegaTudo();

        //pega atributos disponiveis e passa para a view
        $this->AtributosSistemaOrganizacaoModel = new AtributosSistemaOrganizacaoModel();

        $AtrubutosOrganizacaoSistama = $this->AtributosSistemaOrganizacaoModel->pegaAtributosOrganizacao($visivelFomulario = 1);


        $this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPaciente);
        $this->validation =  \Config\Services::validation();

        $this->codOrganizacao = session()->codOrganizacao;
    }

    public function index()
    {

        $permissao = verificaPermissao('Pacientes', 'listar');
        if ($permissao == 0 and empty(session()->minhasEspecialidades)) {
            echo mensagemAcessoNegado(session()->organizacoes);
            $this->LogsModel->inserirLog('Acesso indevido ao Módulo Pacientes', session()->codPaciente);
            exit();
        }
        $especialistas = $this->EspecialidadesModel->especialistas();


        $data = [
            'controller'        => 'pacientes',
            'title'             => 'Pacientes',
            'especialistas' => $especialistas,
        ];
        echo view('tema/cabecalho');
        echo view('tema/menu_vertical');
        echo view('tema/menu_horizontal');
        return view('pacientes', $data);
    }

    public function listaDropDownTipoBeneficiario()
    {

        $result = $this->PacientesModel->listaDropDownTipoBeneficiario();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function medicamentosPrescritos()
    {



        $data = [
            'controller'        => 'Medicamentos Prescritos',
            'title'             => 'Medicamentos Prescritos'
        ];
        echo view('tema/cabecalho');
        echo view('tema/menu_vertical');
        echo view('tema/menu_horizontal');
        return view('relatorios/PACIENTES/medicamentosPrescritos', $data);
    }


    public function listaDropDownOm()
    {

        $result = $this->PacientesModel->listaDropDownOm();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }
    public function listaDropDownTipoSanguineo()
    {

        $result = $this->PacientesModel->listaDropDownTipoSanguineo();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }
    public function listaDropDownRaca()
    {

        $result = $this->PacientesModel->listaDropDownRaca();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function listaDropDownPacientes()
    {

        $result = $this->PacientesModel->listaDropDownPacientes();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function listaDropDownBuscaPacientes()
    {

        $result = $this->PacientesModel->listaDropDownBuscaPacientes();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function listaDropDownSolicitante()
    {

        $result = $this->PacientesModel->listaDropDownSolicitante();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }




    public function gerarVCARD()
    {

        $pacientes = $this->PacientesModel->pegarVCARD();

        $response = array();
        $contatos = "";

        foreach ($pacientes as $paciente) {
            $celular = removeCaracteresIndesejados($paciente->celular);
            $telefoneresidencial = removeCaracteresIndesejados($paciente->telefoneresidencial);
            $paciente->endereco = str_ireplace(",", "\,", $paciente->endereco);
            $contatos .= "BEGIN:VCARD" . "<br>";
            $contatos .= "VERSION:3.0" . "<br>";
            $contatos .= "FN:$paciente->nomeexibicao<br>";
            $contatos .= "N:;$paciente->nomeexibicao - 	$paciente->siglaOrganizacao - $paciente->secao ;;;" . "<br>";
            $contatos .= "item1.EMAIL;TYPE=INTERNET:$paciente->emailPessoal" . "<br>";
            $contatos .= "item1.X-ABLabel:Particular" . "<br>";
            $contatos .= "EMAIL;TYPE=INTERNET;TYPE=WORK:$paciente->emailfuncional" . "<br>";
            $contatos .= "TEL;TYPE=CELL:$celular" . "<br>";
            $contatos .= "TEL;TYPE=HOME:$telefoneresidencial" . "<br>";
            $contatos .= "ADR;TYPE=HOME:;$paciente->endereco;;;;;" . "<br>";
            $contatos .= "item2.ORG:;$paciente->endereco" . "<br>";
            $contatos .= "item2.X-ABLabel:" . "<br>";
            $contatos .= "item3.TITLE:$paciente->siglaOrganizacao" . "<br>";
            $contatos .= "item3.X-ABLabel:" . "<br>";
            $contatos .= "BDAY;VALUE=text:$paciente->datanascimento" . "<br>";
            $contatos .= "ROLE:$paciente->quadro" . "<br>";
            $contatos .= "NOTE:$paciente->secao\nLocation: $paciente->secao" . "<br>";
            $contatos .= "END:VCARD" . "<br>";
        }


        if ($pacientes !== NULL) {

            $response['success'] = true;
            $response['csrf_hash'] = csrf_hash();
            $response['contatos'] = $contatos;
        } else {
            $response['success'] = false;
        }

        return $this->response->setJSON($response);
    }


    public function listaDropDownResponsaveis()
    {

        $result = $this->PacientesModel->listaDropDownResponsaveis();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function listaDropDownCargos()
    {

        $result = $this->CargosModel->listaDropDownCargos();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function listaDropDownParentesco()
    {

        $result = $this->PacientesModel->listaDropDownParentesco();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }
    public function listaDropDownTiposContatos()
    {

        $result = $this->PacientesModel->listaDropDownTiposContatos();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }
    public function listaDropDownStatusCadastroPaciente()
    {

        $result = $this->PacientesModel->listaDropDownStatusCadastroPaciente();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }
    public function listaDropDownForca()
    {

        $result = $this->PacientesModel->listaDropDownForca();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function contas()
    {
        $response = array();
        $lista = array();
        $contas = $this->PacientesModel->contas();

        foreach ($contas as $row) {
            array_push($lista, $row->conta);
        }

        if (!empty($lista)) {
            $response['success'] = true;
            $response['csrf_hash'] = csrf_hash();
            $response['jsonContas'] = json_encode($lista);
        } else {
            $response['success'] = true;
            $response['csrf_hash'] = csrf_hash();
            $response['jsonContas'] = json_encode("");
        }

        return $this->response->setJSON($response);
    }


    public function exportarTodasPacientes($codServidor = NULL)
    {
        $pacientes = $this->PacientesModel->pegaTudo();
        if ($codServidor !== NULL) {
            /*
            foreach ($pacientes as $paciente) {
                $response =   exportarPacienteHelper($this,$paciente->codPaciente, $codServidor);
                //print_r($response); exit();
                return $this->response->setJSON($response);
            }
           */
            $response =   exportarPacienteHelper($this, -1, $codServidor);
            return $this->response->setJSON($response);
        } else {
            /*
            foreach ($pacientes as $paciente) {
                $response  = exportarPacienteHelper($this, $paciente->codPaciente);
                //print_r($response); exit();
                return $this->response->setJSON($response);
            }
            */
            $response =   exportarPacienteHelper($this, -1);
            return $this->response->setJSON($response);
        }
    }




    public function desativarPaciente($codPaciente = null)
    {
        sleep(2);
        $response = array();

        $fields["ativo"] = 0;

        if ($this->request->getPost('codPaciente') !== NULL) {
            $codPaciente = $this->request->getPost('codPaciente');
        }

        if ($codPaciente !== NULL) {
            $codPaciente = $codPaciente;
        }


        if ($this->request->getPost('codMotivoInativo') == NULL or $this->request->getPost('codMotivoInativo') == "") {


            $response['success'] = false;
            $response['messages'] = 'É necessário definir um motivo para desativação';
            return $this->response->setJSON($response);
        } else {
            $fields["codMotivoInativo"] = $this->request->getPost('codMotivoInativo');
        }

        if (!$this->validation->check($codPaciente, 'required|numeric')) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->PacientesModel->update($codPaciente, $fields)) {

                $response['success'] = true;
                $response['csrf_hash'] = csrf_hash();
                $response['messages'] = 'Paciente desativado com sucesso';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na atualização!';
            }
        }


        return $this->response->setJSON($response);
    }


    public function reativarPaciente($codPaciente = null)
    {
        $response = array();

        $fields["ativo"] = 1;
        $fields["codMotivoInativo"] = null;

        if ($this->request->getPost('codPaciente') !== NULL) {
            $codPaciente = $this->request->getPost('codPaciente');
        }

        if ($codPaciente !== NULL) {
            $codPaciente = $codPaciente;
        }


        if ($this->PacientesModel->update($codPaciente, $fields)) {

            $response['success'] = true;
            $response['csrf_hash'] = csrf_hash();
            $response['messages'] = 'Paciente reativada com sucesso';
        } else {

            $response['success'] = false;
            $response['messages'] = 'Erro na atualização!';
        }
        return $this->response->setJSON($response);
    }


    public function limpaOutrosContatosTmp()
    {
        session()->outrosContatosTmp = array();

        $response['success'] = true;
        $response['messages'] = 'Rotina executada com sucesso';
        return $this->response->setJSON($response);
    }

    public function removeContatoTmp()
    {
        $outrosContatosTmp = session()->outrosContatosTmp;
        unset($outrosContatosTmp[$this->request->getPost('codContato')]);
        session()->remove('outrosContatosTmp');
        session()->set('outrosContatosTmp', array());

        session()->push('outrosContatosTmp', $outrosContatosTmp);

        $response['success'] = true;
        $response['csrf_hash'] = csrf_hash();

        return $this->response->setJSON($response);
    }

    public function removeContato()
    {

        $codOutroContato = $this->request->getPost('codOutroContato');

        $this->PacientesModel->removerOutroContato($codOutroContato);
        $response['success'] = true;
        $response['csrf_hash'] = csrf_hash();

        return $this->response->setJSON($response);
    }

    public function incluirContatoEdit($codPaciente = null)
    {
        $response = array();
        $dados = array();



        $dados['codOrganizacao'] = session()->codOrganizacao;
        $dados['codPaciente'] = $this->request->getPost('codPaciente');
        $dados['codTipoContato'] = $this->request->getPost('codTipoContato');
        $dados['nomeContato'] = $this->request->getPost('nomeContato');
        $dados['numeroContato'] = $this->request->getPost('numeroContato');
        $dados['codParentesco'] = $this->request->getPost('codParentesco');
        $dados['observacoes'] = $this->request->getPost('observacoes');



        $this->validation->setRules([
            'codOrganizacao' => ['label' => 'CodOrganizacao', 'rules' => 'permit_empty'],
            'codTipoContato' => ['label' => 'Tipo Contato', 'rules' => 'required|numeric|max_length[11]'],
            'codParentesco' => ['label' => 'Parentesco', 'rules' => 'required|numeric|max_length[11]'],
            'nomeContato' => ['label' => 'Nome Contato', 'rules' => 'required|bloquearReservado|max_length[20]'],
            'numeroContato' => ['label' => 'Número Contato', 'rules' => 'required|bloquearReservado|max_length[50]'],
            'observacoes' => ['label' => 'Observações', 'rules' => 'bloquearReservado|max_length[60]'],
        ]);

        if ($this->validation->run($dados) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {
            if ($this->PacientesModel->inserirOutroContato($dados)) {

                $response['success'] = true;
                $response['csrf_hash'] = csrf_hash();
                $response['messages'] = 'Contato incluido com sucesso';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na incluir!';
            }
        }




        return $this->response->setJSON($response);
    }




    public function alterarContato($codPaciente = null)
    {
        $response = array();
        $dados = array();



        $dados['codOutroContato'] = $this->request->getPost('codOutroContato');
        $dados['codTipoContato'] = $this->request->getPost('codTipoContato');
        $dados['nomeContato'] = $this->request->getPost('nomeContato');
        $dados['numeroContato'] = $this->request->getPost('numeroContato');
        $dados['codParentesco'] = $this->request->getPost('codParentesco');
        $dados['observacoes'] = $this->request->getPost('observacoes');



        $this->validation->setRules([
            'codOutroContato' => ['label' => 'codOutroContato', 'rules' => 'required|numeric'],
            'codTipoContato' => ['label' => 'Tipo Contato', 'rules' => 'required|numeric|max_length[11]'],
            'codParentesco' => ['label' => 'Parentesco', 'rules' => 'required|numeric|max_length[11]'],
            'nomeContato' => ['label' => 'Nome Contato', 'rules' => 'required|bloquearReservado|max_length[20]'],
            'numeroContato' => ['label' => 'Número Contato', 'rules' => 'required|bloquearReservado|max_length[50]'],
            'observacoes' => ['label' => 'Observações', 'rules' => 'bloquearReservado|max_length[60]'],
        ]);

        if ($this->validation->run($dados) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {
            if ($this->PacientesModel->updateOutroContato($dados['codOutroContato'], $dados)) {

                $response['success'] = true;
                $response['csrf_hash'] = csrf_hash();
                $response['messages'] = 'Contato atualizado com sucesso';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na incluir!';
            }
        }




        return $this->response->setJSON($response);
    }



    public function incluirContatoTmp($codPaciente = null)
    {
        $response = array();
        $dados = array();

        $dados['codOrganizacao'] = session()->codOrganizacao;
        $dados['codTipoContato'] = $this->request->getPost('codTipoContato');
        $dados['nomeContato'] = $this->request->getPost('nomeContato');
        $dados['numeroContato'] = $this->request->getPost('numeroContato');
        $dados['codParentesco'] = $this->request->getPost('codParentesco');
        $dados['observacoes'] = $this->request->getPost('observacoes');



        $this->validation->setRules([
            'codTipoContato' => ['label' => 'Tipo Contato', 'rules' => 'required|numeric|max_length[11]'],
            'codParentesco' => ['label' => 'Parentesco', 'rules' => 'required|numeric|max_length[11]'],
            'nomeContato' => ['label' => 'Nome Contato', 'rules' => 'required|bloquearReservado|max_length[20]'],
            'numeroContato' => ['label' => 'Número Contato', 'rules' => 'required|bloquearReservado|max_length[50]'],
            'observacoes' => ['label' => 'Observações', 'rules' => 'bloquearReservado|max_length[60]'],


        ]);

        if ($this->validation->run($dados) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {
            if ($this->PacientesModel->inserirOutroContatoTmp($dados)) {

                $response['success'] = true;
                $response['csrf_hash'] = csrf_hash();
                $response['messages'] = 'Contato incluido com sucesso';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na incluir!';
            }
        }




        return $this->response->setJSON($response);
    }

    public function incluirContato($codPaciente = null)
    {
        $response = array();
        $dados = array();

        $dados['codPaciente'] = $this->request->getPost('codTipoContato');
        $dados['codOrganizacao'] = session()->codOrganizacao;
        $dados['codTipoContato'] = $this->request->getPost('codTipoContato');
        $dados['nomeContato'] = $this->request->getPost('nomeContato');
        $dados['numeroContato'] = $this->request->getPost('numeroContato');
        $dados['codParentesco'] = $this->request->getPost('codParentesco');
        $dados['observacoes'] = $this->request->getPost('observacoes');


        if ($this->PacientesModel->inserirOutroContato($dados)) {

            $response['success'] = true;
            $response['csrf_hash'] = csrf_hash();
            $response['messages'] = 'Contato incluido com sucesso';
        } else {

            $response['success'] = false;
            $response['messages'] = 'Erro na incluir!';
        }
        return $this->response->setJSON($response);
    }



    public function filtrar()
    {
        if ($this->request->getPost('codPaciente')  !== NULL) {
            $codPaciente = $this->request->getPost('codPaciente');
        }

        session()->set('filtroPaciente', $codPaciente);

        if ($this->request->getPost('desativados') == 'on') {
            session()->set('filtroDesativados', 1);
        } else {
            session()->set('filtroDesativados', 0);
        }


        $response = array();

        $response['success'] = true;
        $response['csrf_hash'] = csrf_hash();
        return $this->response->setJSON($response);
    }




    public function listaDropDownMotivosInativacaoPaciente()
    {

        $result = $this->PacientesModel->listaDropDownMotivosInativacaoPaciente();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function getAll()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->PacientesModel->pega_pacientes();

        foreach ($result as $key => $value) {

            $ops = '<div class="text-center"><div style="text-align:center" class="btn-group">';
            $ops .= '	<button style="margin-left:1px" type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Editar paciente" onclick="perfilPaciente(' . $value->codPaciente . ')"><i class="fa fa-edit"></i>ACESSAR</button>';
            $ops .= '	<button style="margin-left:1px" type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Cartão" onclick="cartao(' . $value->codPaciente . ')"><i class="fa fa-address-card"></i></button>';
            $ops .= '	<button style="margin-left:1px" type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Troca de senha" onclick="trocasenhaPaciente(' . $value->codPaciente . ')"><i class="fa fa-user-lock"></i></button>';
            $ops .= '	<button style="margin-left:1px" type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Remover paciente" onclick="remove(' . $value->codPaciente . ')"><i class="fa fa-trash"></i></button>';

            if ($value->ativo == 0) {
                $ops .= '	<button style="margin-left:1px" type="button" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Reativar paciente" onclick="reativarPaciente(' . $value->codPaciente . ')"><i style="font-size:15px" class="fas fa-user"></i></button>';
            }
            if ($value->ativo == 1) {
                $ops .= '	<button style="margin-left:1px" type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Desativar paciente" onclick="confirmacaoDesativacao(' . $value->codPaciente . ',\'' . $value->nomeExibicao . '\')"><i style="font-size:12px" class="fas fa-user-slash"></i></button>';
            }

            $ops .= '</div></div>';

            if ($value->ativo == 0) {
                $ativo = ' <span><i style="font-size:20px" class="fas fa-user-slash text-danger"></i></span>';
            }
            if ($value->ativo == 1) {
                $ativo = ' <span><i style="font-size:20px" class="fas fa-user text-success"></i></span>';
            }
            $data['data'][$key] = array(
                $value->codPaciente . $ativo,
                $value->nomeExibicao,
                $value->cpf,
                $value->codPlano,
                $value->codProntuario,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }


    public function pegaPaciente()
    {
        $response = array();

        $data['data'] = array();

        $paciente = $this->request->getPost('paciente');

        $result = $this->PacientesModel->pegaPaciente($paciente);

        foreach ($result as $key => $value) {

            $ops = '<div class="text-center"><div style="text-align:center" class="btn-group">';
            $ops .= '	<button style="margin-left:1px" type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Editar paciente" onclick="perfilPaciente(' . $value->codPaciente . ')"><i class="fa fa-edit"></i>ACESSAR</button>';
            $ops .= '	<button style="margin-left:1px" type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Cartão" onclick="cartao(' . $value->codPaciente . ')"><i class="fa fa-address-card"></i></button>';
            $ops .= '	<button style="margin-left:1px" type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Troca de senha" onclick="trocasenhaPaciente(' . $value->codPaciente . ')"><i class="fa fa-user-lock"></i></button>';
            $ops .= '	<button style="margin-left:1px" type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Remover paciente" onclick="remove(' . $value->codPaciente . ')"><i class="fa fa-trash"></i></button>';

            if ($value->ativo == 0) {
                $ops .= '	<button style="margin-left:1px" type="button" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Reativar paciente" onclick="reativarPaciente(' . $value->codPaciente . ')"><i style="font-size:15px" class="fas fa-user"></i></button>';
            }
            if ($value->ativo == 1) {
                $ops .= '	<button style="margin-left:1px" type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Desativar paciente" onclick="confirmacaoDesativacao(' . $value->codPaciente . ',\'' . $value->nomeExibicao . '\')"><i style="font-size:12px" class="fas fa-user-slash"></i></button>';
            }

            $ops .= '</div></div>';

            if ($value->ativo == 0) {
                $ativo = ' <span><i style="font-size:20px" class="fas fa-user-slash text-danger"></i></span>';
            }
            if ($value->ativo == 1) {
                $ativo = ' <span><i style="font-size:20px" class="fas fa-user text-success"></i></span>';
            }
            $status = '<span class="right badge badge-' . $value->corStatusCadastroPaciente . '">' . $value->nomeStatusCadastroPaciente . '</span>';

            $data['data'][$key] = array(
                $value->codPaciente . $ativo,
                $value->nomeExibicao,
                $value->cpf,
                $value->codPlano,
                $value->codProntuario,
                $status,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }





    public function prontuario()
    {
        $response = array();

        $codPaciente = $this->request->getPost('codPaciente');
        $codTipoAtendimento = $this->request->getPost('codTipoAtendimento');
        $codStatusAtendimento = $this->request->getPost('codStatusAtendimento');

        $data['data'] = array();

        $result = $this->AtendimentosModel->pegaAtendimentosPorCodPaciente($codPaciente,  $codTipoAtendimento, $codStatusAtendimento);
        $x = 0;
        foreach ($result as $key => $value) {
            $x++;
            $ops = '<div class="text-center"><div style="text-align:center" class="btn-group">';
            $ops .= '	<button style="margin-left:1px" type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Editar paciente" onclick="editarAtendimento(' . $value->codAtendimento . ')"><i class="fa fa-edit"></i></button>';


            if ($value->dataEncerramento == NULL) {
                $dataEncerramento = '<span class="right badge badge-danger">Em atendimento</span>';
            } else {
                $dataEncerramento = date('d/m/Y H:i', strtotime($value->dataEncerramento));
            }

            if ($value->descricaoDepartamento == NULL or $value->descricaoLocalAtendimento == NULL) {
                $local = "-";
            } else {
                $local = $value->descricaoDepartamento . " (" . $value->descricaoLocalAtendimento . ")";
            }

            $data['data'][$key] = array(
                $x,
                $value->descricaoEspecialidade,
                $value->nomeEspecialista,
                $local,
                $value->descricaoTipoAtendimento,
                date('d/m/Y H:i', strtotime($value->dataInicio)),
                $dataEncerramento,
                $ops,
            );
        }


        return $this->response->setJSON($data);
    }

    public function getOutrosContatos()
    {

        $response = array();

        $data['data'] = array();

        $codPaciente = $this->request->getPost('codPaciente');

        $result = $this->PacientesModel->pegaOutrosContatosPorCodPaciente($codPaciente);

        foreach ($result as $key => $value) {

            $ops = '<div class="btn-group">';
            $ops .= '	<button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Editar"  onclick="modificarContato(' . $value->codOutroContato . ')"><i class="fa fa-edit"></i></button>';
            $ops .= '	<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeContato(' . $value->codOutroContato . ')"><i class="fa fa-trash"></i></button>';
            $ops .= '</div>';

            $data['data'][$key] = array(
                $key + 1,
                $this->PacientesModel->tipoContatoLookup($value->codTipoContato),
                $value->nomeContato,
                $this->PacientesModel->parentescoLookup($value->codParentesco),
                $value->numeroContato,
                $value->observacoes,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }



    public function pacientesAlergia($codPaciente = NULL)
    {
        $response = array();
        $data['data'] = array();

        if ($codPaciente == NULL) {
            $codPaciente = $this->request->getPost('codPaciente');
        }

        $result = $this->PacientesModel->pegaPorCodPaciente($codPaciente);

        $x = 0;
        foreach ($result as $key => $value) {
            $x++;

            $ops = '<div class="btn-group">';
            $ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removepacientesAlergia(' . $value->codPacienteAlergia . ')"><i class="fa fa-trash"></i></button>';
            $ops .= '</div>';

            $data['data'][$key] = array(
                $x,
                $value->descricaoAlergenico . ' (' . $value->descricaoTipoAlergenico . ')',
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }


    public function getOutrosContatosTmp()
    {

        $response = array();

        $data['data'] = array();

        if (session()->outrosContatosTmp !== NULL) {
            foreach (session()->outrosContatosTmp as $key => $value) {

                $ops = '<div class="btn-group">';
                $ops .= '	<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeContatoTmp(' . $key . ')"><i class="fa fa-trash"></i></button>';
                $ops .= '</div>';

                $data['data'][$key] = array(
                    $key + 1,
                    $this->PacientesModel->tipoContatoLookup($value['codTipoContato']),
                    $value['nomeContato'],
                    $this->PacientesModel->parentescoLookup($value['codParentesco']),
                    $value['numeroContato'],
                    $value['observacoes'],
                    $ops,
                );
            }
        }
        return $this->response->setJSON($data);
    }

    public function pegaMeusPerfisValidos()
    {
        $this->PerfilPacientesMembroModel = new PerfilPacientesMembroModel;
        $id = $this->request->getPost('codPaciente');
        $meusPerfisValidos = $this->PerfilPacientesMembroModel->pegaMeusPerfisValidos($id);
        return $this->response->setJSON($meusPerfisValidos);
    }


    public function verificaPendenciaCadastro()
    {
        $response = array();

        $codPaciente = session()->codPaciente;
        $codOrganizacao = session()->codOrganizacao;

        $paciente = $this->PacientesModel->organizacaoPaciente($codPaciente);


        $atributos = $this->AtributosSistemaOrganizacaoModel->pegaTudoPorOrganizacao($codOrganizacao);

        $ArrayAtributos = array();
        foreach ($atributos as $atributo) {
            if ($atributo->visivelFormulario == 1 and $atributo->obrigatorio == 1) {
                array_push($ArrayAtributos, $atributo->nomeAtributoSistema);
            }
        }
        //print_r($ArrayAtributos);
        $nrPendencias = 0;
        foreach ($ArrayAtributos as $item) {
            if ($paciente->$item == NULL or $paciente->$item == '0000-00-00') {

                $nrPendencias++;
            } else {
                //print 'NÃO Tem Nulo';
            }
        }
        $data = array('pendencias' => $nrPendencias);

        if ($nrPendencias > 0 and $codPaciente !== 0) {
            $response['pendencias'] = true;
            $response['quantidade'] = $nrPendencias;
        } else {
            $response['pendencias'] = false;
            $response['quantidade'] = $nrPendencias;
        }

        return $this->response->setJSON($response);
    }



    public function verificaPendenciaSenha()
    {
        $response = array();

        $codPaciente = session()->codPaciente;
        $codOrganizacao = session()->codOrganizacao;

        $paciente = $this->PacientesModel->organizacaoPaciente($codPaciente);

        if ($paciente->senha == NULL and $codPaciente !== 0) {
            $response['pendencias'] = true;
        }

        return $this->response->setJSON($response);
    }

    public function emissaoCartao()
    {
        $response = array();


        $id = $this->request->getPost('codPaciente');
        $organizacao =  $this->OrganizacoesModel->pegaOrganizacao(session()->codOrganizacao);


        if ($this->validation->check($id, 'required|numeric')) {
            $data = array();
            $paciente = $this->PacientesModel->emissaoCartao($id);
            $data['nomeCompleto'] = $paciente->nomeCompleto;
            $data['codPaciente'] = $paciente->codPaciente;
            $data['valorChecksum'] = MD5($paciente->codPaciente . $organizacao->chaveSalgada);
            $data['fotoPerfil'] = $paciente->fotoPerfil;
            $data['cpf'] = $paciente->cpf;
            $data['nomeTipoBeneficiario'] = $paciente->nomeTipoBeneficiario;
            $data['descricaoCargo'] = $paciente->descricaoCargo;
            $data['codProntuario'] = $paciente->codProntuario;
            $data['codPlano'] = $paciente->codPlano;
            $data['validadeProntuario'] = $paciente->validadeProntuario;
            $data['responsavel'] = getNomeExibicaoPessoa($this, session()->codPessoa);
            $data['dataEmissao'] = date('d/m/Y H:i');

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function dadosEncaixe()
    {
        $response = array();


        if ($this->request->getPost('paciente') == NULL) {
            $response['success'] = false;
            $response['messages'] = 'Paciente Não encontrado';
            return $this->response->setJSON($response);
        }

        $paciente = removeCaracteresIndesejados($this->request->getPost('paciente'));


        $data = $this->PacientesModel->pegaPacientePorCPFCadben($paciente);

        if ($data !== NULL) {
            $response['success'] = true;
            $response['codPaciente'] =  $data->codPaciente;
            $response['nomeCompleto'] =  $data->nomeCompleto;
            $response['cpf'] =  $data->cpf;

            return $this->response->setJSON($response);
        } else {
            $response['success'] = false;
            $response['messages'] = 'Paciente não encontrado';
            return $this->response->setJSON($response);
        }
    }

    public function getOne()
    {
        $response = array();

        $permissao = verificaPermissao('Pacientes', 'editar');

        if ($permissao == 0  and empty(session()->minhasEspecialidades)) {
            $response['acessoNegado'] = true;
            $response['messages'] = 'Você não tem permissão para Editar';
            return $this->response->setJSON($response);
        }

        $id = $this->request->getPost('codPaciente');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->PacientesModel->pegaPacientePorCodPaciente($id);

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function pegaHistoricoPaciente()
    {

        $response = array();

        $codAtendimento = $this->request->getPost('codAtendimento');

        if ($this->validation->check($codAtendimento, 'required|numeric')) {

            $historicoPaciente = $this->PacientesModel->pegaHistoricoPaciente($codAtendimento);

            $html = "";
            $codPaciente = NULL;

            $datas = array();
            foreach ($historicoPaciente as $data) {

                if ($codPaciente == NULL) {
                    $codPaciente = $data->codPaciente;
                }

                if (!in_array(date('Y-m-d', strtotime($data->dataCriacao)), $datas)) {
                    array_push($datas, date('Y-m-d', strtotime($data->dataCriacao)));
                }
            }

            $medicamentosUsoContinuo = $this->PacientesModel->pegaMedicamentosUsoContinuo($codPaciente);
            $alergias = $this->PacientesModel->pegaAlergias($codPaciente);

            $listaMedicamentosUsoContinuo = '';
            $listaAlergias = '';

            foreach ($medicamentosUsoContinuo as $medicamento) {
                $listaMedicamentosUsoContinuo .=  $medicamento->descricaoMedicamento . ' | ';
            }

            foreach ($alergias as $alergia) {
                $listaAlergias .=  $alergia->descricaoAlergenico . ' | ';
            }




            $cronicos = '
            
            <div  style="margin-top:20px" class="row">
                <div class="col-md-6">

                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>Medicamentos de uso Contínuo</span></h3>
                                ' . $listaMedicamentosUsoContinuo . '
                        </div>
                        <div class="icon">
                            <i class="fas fa-pills zoom fa-1x"></i>
                        </div>
                    </div>



                </div>
                <div class="col-md-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            
                            <h3>Alergias</span></h3>
                        ' . $listaAlergias . '

                        </div>
                        <div class="icon">
                            <i class="fas fa-capsules zoom fa-1x"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            ';

            $html .= $cronicos;

            foreach ($datas as $acao) {

                $precricoesMedicamentosHtml = '';
                $html .= '

                
				<div style="margin-top:20px" class="time-label">
					<span class="bg-red">' . date('d', strtotime($acao)) . '' . nomeMesAbreviado(date('m', strtotime($acao))) . '' . date('Y', strtotime($acao)) . '</span>
				</div>';

                foreach ($historicoPaciente as $itensData) {


                    if (date('Y-m-d', strtotime($acao)) == date('Y-m-d', strtotime($itensData->dataCriacao))) {

                        //BUSCA CONDUTAS
                        $condutas = $this->PacientesModel->pegaHistoricoCondutas($itensData->codAtendimento);

                        $condutasHtml = '
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-ban"></i> Atenção</h5>
                            Nenhuma registro encontrado 
                        </div>
                        ';

                        $x = 0;
                        foreach ($condutas as $conduta) {
                            if ($x == 0) {
                                $condutasHtml = '';
                            }
                            $x++;
                            if ($x % 2 == 0) {
                                $codCorConduta = '#ffffff';
                            } else {
                                $codCorConduta = '#e5e5e58a';
                            }
                            $condutasHtml .= '
                        
                            <div style="background:' . $codCorConduta . '; margin-top:0px;margin-bottom:0px" class="card-comment">
                                <img class="img-circle img-sm" src="' . base_url() . '/imagens/fotousuario.png" alt="User Image">

                                <div class="comment-text">
                                    <span class="username">
                                ' . $conduta->nomeExibicao . '
                                        <span class="text-muted float-right">' . date('d/m/Y H:i', strtotime($conduta->dataCriacao)) . '</span>
                                    </span>
                                    ' . $conduta->conteudoConduta . '
                                </div>
                            </div>
                        ';
                        }


                        //BUSCA EVOLUÇÕES
                        $evolucoes = $this->PacientesModel->pegaHistoricoEvolucoes($itensData->codAtendimento);

                        $evolucoesHtml =  '
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-ban"></i> Atenção</h5>
                            Nenhuma registro encontrado 
                        </div>
                        ';


                        $x = 0;
                        foreach ($evolucoes as $evolucao) {
                            if ($x == 0) {
                                $evolucoesHtml = '';
                            }
                            $x++;
                            if ($x % 2 == 0) {
                                $codCorEvolucao = '#ffffff';
                            } else {
                                $codCorEvolucao = '#e5e5e58a';
                            }
                            $evolucoesHtml .= '
                        
                            <div style="background:' . $codCorEvolucao . '; margin-top:0px;margin-bottom:0px" class="card-comment">
                                <img class="img-circle img-sm" src="' . base_url() . '/imagens/fotousuario.png" alt="User Image">

                                <div class="comment-text">
                                    <span class="username">
                                ' . $evolucao->nomeExibicao . '
                                        <span class="text-muted float-right">' . date('d/m/Y H:i', strtotime($evolucao->dataCriacao)) . '</span>
                                    </span>
                                    ' . $evolucao->conteudoEvolucao . '
                                </div>
                            </div>
                        ';
                        }


                        //BUSCA PRESCRIÇÕES
                        $precricoes = $this->PacientesModel->pegaHistoricoPrescricoes($itensData->codAtendimento);

                        $precricoesHtml =  '
                                                <div class="alert alert-warning alert-dismissible">
                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                    <h5><i class="icon fas fa-ban"></i> Atenção</h5>
                                                    Nenhuma registro encontrado 
                                                </div>
                                                ';


                        $x = 0;
                        foreach ($precricoes as $prescricao) {
                            if ($x == 0) {
                                $precricoesHtml = '';
                                $precricoesMedicamentosHtml = '';
                            }
                            $x++;
                            if ($x % 2 == 0) {
                                $codCorPrescricao = '#ffffff';
                            } else {
                                $codCorPrescricao = '#e5e5e58a';
                            }

                            $precricoesMedicamentos = $this->PacientesModel->pegaHistoricoPrescricoesMedicamentos($prescricao->codAtendimentoPrescricao);

                            $precricoesMedicamentosHtml = '<div class="row">';

                            foreach ($precricoesMedicamentos as $medicamento) {
                                $entregue = NULL;
                                $liberado = NULL;
                                $executado = NULL;
                                if ($medicamento->totalEntregue !== NULL) {
                                    $entregue = '<span style="font-weight: bold;"> | Entregue: ' . $medicamento->totalEntregue . '</span> ';
                                }
                                if ($medicamento->totalLiberado !== NULL) {
                                    $liberado = '<span style="font-weight: bold;"> | Liberado: ' . $medicamento->totalLiberado . '</span> ';
                                }
                                if ($medicamento->totalExecutado !== NULL) {
                                    $executado = '<span style="font-weight: bold;"> | Executado: ' . $medicamento->totalExecutado . '</span> ';
                                }
                                $precricoesMedicamentosHtml .= '
                                <div style="margin-top:0px !important;margin-bottom:0px !important;" class="col-sm-3 border p-3 mb-2 bg-gradient-warning">
                                 <div style="font-weight: bold;"> ' . $medicamento->descricaoItem . '</div>
                                    <div>                                    
                                        <span>' . $medicamento->descricaoUnidade . '</span>|
                                        <span>' . $medicamento->descricaoVia . '</span>|
                                        <span style="font-weight: bold;">Qtde</span><span>' . $medicamento->qtde . '</span>|
                                        <span style="font-weight: bold;"> Solicitado: ' . $medicamento->total . '</span>
                                        ' . $liberado . '
                                        ' . $entregue . '
                                        ' . $executado . '
                                    </div>
                                  <div>
                                     <span style="font-weight: bold;">obs</span><span style="font-size:10px;color:red">' . $medicamento->obs . '</span>
                                  </div>
                                </div>
                                ';
                            }


                            $precricoesMedicamentosHtml .= '</div>';

                            $precricoesHtml .= '
                                                
                                                    <div style="background:' . $codCorPrescricao . '; margin-top:0px;margin-bottom:0px" class="card-comment">
                                                        <img class="img-circle img-sm" src="' . base_url() . '/imagens/fotousuario.png" alt="User Image">
                        
                                                        <div class="comment-text">
                                                            <span class="username">
                                                        ' . $prescricao->nomeExibicao . '
                                                                <span class="text-muted float-right">' . date('d/m/Y H:i', strtotime($prescricao->dataCriacao)) . '
                                                                <button type="button" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="Clonar prescrição para atendimento atual" onclick="clonarAtendimentoPrescricao(' . $prescricao->codAtendimentoPrescricao . ')"><i class="fa fa-clone"></i></button>
                                                                </span>
                                                            </span>
                                                            ' . $prescricao->conteudoPrescricao . '
                                                            ' . $precricoesMedicamentosHtml . '
                                                        </div>
                                                    </div>
                                                ';
                        }


                        $html .= '
						

                        <div>
                            <i class="fas fa-info bg-blue"></i>
                            <div class="timeline-item">
                                <span style="color:#fff;font-size:14px" class="time"><i class="fas fa-clock"></i> ' . date('d/m/Y H:i', strtotime($itensData->dataCriacao)) . '</span>
                                <h3 class="timeline-header bg-secondary"><a> Atendimento ' . $itensData->codAtendimento . ' - ' .  mb_strtoupper($itensData->descricaoTipoAtendimento, "utf-8") . ' - ' . $itensData->descricaoEspecialidade . ' - ' . $itensData->descricaoDepartamento . ' - ' . $itensData->descricaoLocalAtendimento . '</a> </h3>
                        
                        
                                <div style="margin-top:5px" class="col-12 col-sm-12">
                                    <div class="card card-secondary card-tabs">
                                        <div class="card-header p-0 pt-1">
                                            <ul class="nav nav-tabs" id="historia-tab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="historia-principal-tab' . $itensData->codAtendimento . '" data-toggle="pill" href="#historia-principal' . $itensData->codAtendimento . '" role="tab" aria-controls="historia-principal' . $itensData->codAtendimento . '" aria-selected="true">Principal</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="historia-condutas-tab' . $itensData->codAtendimento . '" data-toggle="pill" href="#historia-condutas' . $itensData->codAtendimento . '" role="tab" aria-controls="historia-condutas' . $itensData->codAtendimento . '" aria-selected="false">Condutas</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="historia-evolucoes-tab' . $itensData->codAtendimento . '" data-toggle="pill" href="#historia-evolucoes' . $itensData->codAtendimento . '" role="tab" aria-controls="historia-evolucoes' . $itensData->codAtendimento . '" aria-selected="false">Evoluções</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="historia-prescricoes-tab' . $itensData->codAtendimento . '" data-toggle="pill" href="#historia-prescricoes' . $itensData->codAtendimento . '" role="tab" aria-controls="historia-prescricoes' . $itensData->codAtendimento . '" aria-selected="false">Prescrições</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <div class="tab-content" id="historia-tabContent">
                                                <div class="tab-pane fade show active" id="historia-principal' . $itensData->codAtendimento . '" role="tabpanel" aria-labelledby="historia-principal-tab' . $itensData->codAtendimento . '">
                        
                                                        <div style="color:green;font-size:14px;overflow-y: auto;overflow-x: hidden;height: 200px;" class="timeline-body">
                            
                                                        <div>
                                                         <b>Profissional:</b> ' . $itensData->nomeExibicao . '
                                                        </div>
                                                        <div>
                                                            <b>Status:</b> ' . $itensData->descricaoStatusAtendimento . '
                                                        </div>
                                                        <div>
                                                            <b>Queixa:</b> ' . $itensData->queixaPrincipal . '
                                                        </div>
                                                        <div>
                                                            <b>HDA: </b>' . $itensData->hda . '
                                                        </div>
                                                        <div>
                                                            <b>HMP: </b>' . $itensData->hmp . '
                                                        </div>
                                                        <div>
                                                            <b>História de Medicamentos: </b>' . $itensData->historiaMedicamentos . '
                                                        </div>
                                                        <div>
                                                            <b>História de Alergias: </b>' . $itensData->historiaAlergias . '
                                                        </div>
                                                        <div>
                                                            <b>Outras Info: </b>' . $itensData->outrasInformacoes . '
                                                        </div>
                                                    </div>
                                                </div>
                        
                                                <div style="color:green;font-size:14px;overflow-y: auto;overflow-x: hidden;height: 200px;" class="tab-pane fade" id="historia-condutas' . $itensData->codAtendimento . '" role="tabpanel" aria-labelledby="historia-condutas-tab' . $itensData->codAtendimento . '">
                                                    ' . $condutasHtml . '
                                                </div>
                                                <div style="color:green;font-size:14px;overflow-y: auto;overflow-x: hidden;height: 200px;" class="tab-pane fade" id="historia-evolucoes' . $itensData->codAtendimento . '" role="tabpanel" aria-labelledby="historia-evolucoes-tab' . $itensData->codAtendimento . '">
                                                    ' . $evolucoesHtml . '
                                                </div>
                                                <div style="color:green;font-size:14px;overflow-y: auto;overflow-x: hidden;height: 200px;" class="tab-pane fade" id="historia-prescricoes' . $itensData->codAtendimento . '" role="tabpanel" aria-labelledby="historia-prescricoes-tab' . $itensData->codAtendimento . '">
                                                    ' . $precricoesHtml . '
                                                    
                                                 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        
                            </div>
                    </div>						
						
						';
                    }
                }
            }

            $response['html'] .= $html;

            sleep(3);

            return $this->response->setJSON($response);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function getContato()
    {
        $response = array();

        $codContato = $this->request->getPost('codContato');

        if ($this->validation->check($codContato, 'required|numeric')) {

            $data = $this->PacientesModel->getContato($codContato);

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function pegaOrganizacaoPaciente()
    {
        $response = array();

        $codPaciente = $this->request->getPost('codPaciente');

        if ($this->validation->check($codPaciente, 'required|numeric')) {

            $data = $this->PacientesModel->organizacaoPaciente($codPaciente);

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function filtrarEspecialidadeFiltroHistoricoPaciente()
    {

        if ($this->request->getPost('codEspecialidadeFiltroHistoricoPaciente')  !== NULL and $this->request->getPost('codEspecialidadeFiltroHistoricoPaciente')  < 1000) {
            $codEspecialidadeFiltroHistoricoPaciente = $this->request->getPost('codEspecialidadeFiltroHistoricoPaciente');
        } else {
            $codEspecialidadeFiltroHistoricoPaciente = NULL;
        }

        session()->set('filtroEspecialidadeFiltroHistoricoPaciente', $codEspecialidadeFiltroHistoricoPaciente);

        $response = array();

        $response['success'] = true;
        $response['csrf_hash'] = csrf_hash();
        return $this->response->setJSON($response);
    }


    public function limpaFiltroHistoricoPaciente()
    {

        session()->set('filtroEspecialidadeFiltroHistoricoPaciente', null);

        session()->set('filtroTipoAtendimentoFiltroHistoricoPaciente', null);

        $response = array();

        $response['success'] = true;
        return $this->response->setJSON($response);
    }



    public function filtrarTipoAtendimentoFiltroHistoricoPaciente()
    {

        if ($this->request->getPost('codTipoAtendimentoFiltroHistoricoPaciente')  !== NULL and $this->request->getPost('codTipoAtendimentoFiltroHistoricoPaciente') < 1000) {
            $codTipoAtendimentoFiltroHistoricoPaciente = $this->request->getPost('codTipoAtendimentoFiltroHistoricoPaciente');
        } else {
            $codTipoAtendimentoFiltroHistoricoPaciente = NULL;
        }

        session()->set('filtroTipoAtendimentoFiltroHistoricoPaciente', $codTipoAtendimentoFiltroHistoricoPaciente);

        $response = array();

        $response['success'] = true;
        $response['csrf_hash'] = csrf_hash();
        return $this->response->setJSON($response);
    }


    public function verificaCPF()
    {

        $response = array();

        $cpf = removeCaracteresIndesejados($this->request->getPost('cpf'));
        $cpfPaciente = $this->PacientesModel->pegaPacientePorCpf($cpf);

        if ($cpfPaciente->cpf !== NULL) {

            $response['existe'] = true;
            $response['messages'] = '<div>CPF já cadastrado! Pertence a "'.$cpfPaciente->nomeCompleto.'".<div>';

            return $this->response->setJSON($response);
        }
    }
    public function add()
    {

        $response = array();

        //VERIFICA SE CPF JÁ ESTÁ CADASTRADO

        $cpf = removeCaracteresIndesejados($this->request->getPost('cpf'));
        $cpfPaciente = $this->PacientesModel->pegaPacientePorCpf($cpf);
        $codPlanoPaciente = $this->PacientesModel->pegaPacientePorCodPlano($this->request->getPost('codPlano'));
        $organizacao =  $this->OrganizacoesModel->pegaOrganizacao(session()->codOrganizacao);


        if ($cpfPaciente !== NULL) {


            $response['existe'] = true;
            $response['messages'] = '<div>CPF já cadastrado.<div>';

            return $this->response->setJSON($response);
        }


        if ($codPlanoPaciente !== NULL) {


            $response['existe'] = true;
            $response['messages'] = '<div>Código Beneficiário(Nº Plano) já cadastrado.<div>';

            return $this->response->setJSON($response);
        }



        $fields['conta'] = removeCaracteresIndesejados($this->request->getPost('cpf'));
        $fields['codOrganizacao'] =  session()->codOrganizacao;
        $fields['nomeExibicao'] = mb_strtoupper($this->request->getPost('nomeCompleto'), "utf-8");
        $fields['nomePrincipal'] = mb_strtoupper($this->request->getPost('nomeCompleto'), "utf-8");
        $fields['nomeCompleto'] = mb_strtoupper($this->request->getPost('nomeCompleto'), "utf-8");
        $fields['codClasse'] = 2;
        $fields['sexo'] = mb_strtoupper($this->request->getPost('sexo'), "utf-8");
        $fields['codPlano'] = $this->request->getPost('codPlano');
        $fields['cpf'] = removeCaracteresIndesejados($this->request->getPost('cpf'));
        $fields['identidade'] = $this->request->getPost('identidade');
        $fields['nomePai'] = $this->request->getPost('nomePai');
        $fields['nomeMae'] = $this->request->getPost('nomeMae');
        $fields['emailPessoal'] = $this->request->getPost('emailPessoal');
        $fields['celular'] = $this->request->getPost('celular');
        $fields['endereco'] = $this->request->getPost('endereco');
        $fields['cep'] = $this->request->getPost('cep');
        $fields['codStatusCadastroPaciente'] = $this->request->getPost('codStatusCadastroPaciente');
        $fields['dataNascimento'] = $this->request->getPost('dataNascimento');
        $fields['validade'] = $this->request->getPost('validade');
        $fields['codRaca'] = $this->request->getPost('codRaca');
        $fields['codTipoSanguineo'] = $this->request->getPost('codTipoSanguineo');
        $fields['informacoesComplementares'] = $this->request->getPost('informacoesComplementares');
        $fields['dataCriacao'] = date('Y-m-d H:i');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['ativo'] = 1;
        $request = \Config\Services::request();
        $ip = $request->getIPAddress();
        $fields['ipRequisitante'] = $ip;

        //Definir Senha
        $senha = hash("sha256", $this->request->getPost('codPlano') . $organizacao->chaveSalgada);
        $fields['senha'] = $senha;


        if (session()->codPessoa !== NULL) {
            $funcionario = 1;
            $fields['autor'] = session()->codPessoa;
            $pessoa = $this->PessoasModel->pegaPessoaDepartamento(session()->codPessoa);
            $nomeFuncionarioResponsavel = $pessoa->nomeExibicao;
            $nomeDepartamentoResponsavel = $pessoa->descricaoDepartamento;
        } else {
            $funcionario = 0;
            if (session()->codPaciente !== NULL) {
                $fields['autor'] = session()->codPaciente;
            } else {
                $fields['autor'] = 0;
            }
        }


        $this->validation->setRules([

            'codOrganizacao' => ['label' => 'Código da Organização', 'rules' => 'required|integer|max_length[10]'],
            'nomeCompleto' => ['label' => 'Nome Completo', 'rules' => 'required|max_length[100]|bloquearReservado'],
            'sexo' => ['label' => 'Sexo', 'rules' => 'required|max_length[1]'],
            'emailPessoal'        => 'permit_empty|max_length[254]|valid_email',
            'codPlano' => ['label' => 'Código do Beneficiário (codplano)', 'rules' => 'required|integer|max_length[11]'],
            'cpf' => ['label' => 'cpf', 'rules' => 'required|max_length[15]'],
            'identidade' => ['label' => 'Identidade', 'rules' => 'required|integer|max_length[15]'],
            'nomeMae' => ['label' => 'Nome da Mãe', 'rules' => 'required|max_length[60]'],
            'celular' => ['label' => 'Celular', 'rules' => 'required|max_length[16]'],
            'endereco' => ['label' => 'Endereço', 'rules' => 'required|max_length[200]|bloquearReservado'],
            'codStatusCadastroPaciente' => ['label' => 'Status Cadastro Paciente', 'rules' => 'required|integer|max_length[9]'],
            'dataNascimento' => ['label' => 'Data de Nascimento', 'rules' => 'required|valid_date'],
            'dataInicioEmpresa' => ['label' => 'Data inclusão', 'rules' => 'permit_empty'],
            'codRaca' => ['label' => 'Raça', 'rules' => 'required|integer'],
            'codTipoSanguineo' => ['label' => 'Tipo Sanguineo', 'rules' => 'required|integer'],
            'informacoesComplementares' => ['label' => 'Informações Complementares', 'rules' => 'permit_empty|max_length[500]|bloquearReservado'],
        ]);

        if ($this->validation->run($fields) == false) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($codPaciente = $this->PacientesModel->insert($fields)) {


                if ($codPaciente !== NULL and $codPaciente !== "" and $codPaciente !== " ") {
                }
                //GERA PRONTUÁRIO
                $ano = date('Y');
                $digito = geraNumero(1);
                $codProntuario = $ano . str_pad($codPaciente, 6, '0', STR_PAD_LEFT) . $digito;
                $updateProntuario['codProntuario'] = $codProntuario;
                $updateProntuario['pronID'] = substr($codProntuario, 0, 5);
                $updateProntuario['pronNR'] = substr($codProntuario, -5);;

                if ($this->PacientesModel->update($codPaciente, $updateProntuario)) {
                }



                //ADICIONAR OUTROS CONTATOS
                $dados = array();
                foreach (session()->outrosContatosTmp as $contato) {

                    $dados['codOrganizacao'] = $contato['codOrganizacao'];
                    $dados['codPaciente'] = $codPaciente;
                    $dados['codTipoContato'] = $contato['codTipoContato'];
                    $dados['nomeContato'] = $contato['nomeContato'];
                    $dados['numeroContato'] = $contato['numeroContato'];
                    $dados['codParentesco'] = $contato['codParentesco'];
                    $dados['observacoes'] = $contato['observacoes'];
                    $this->PacientesModel->inserirOutroContato($dados);
                }



                //NOTIFICAÇÃO

                if ($fields['emailPessoal'] !== NULL and $fields['emailPessoal'] == "" and $fields['emailPessoal'] = " ") {
                    $email = $fields['emailPessoal'];
                    $email = removeCaracteresIndesejadosEmail($email);
                } else {
                    $email = NULL;
                }

                if ($fields['emailPessoal'] !== NULL and $fields['emailPessoal'] == "" and $fields['emailPessoal'] = " " and $fields['nomeExibicao'] !== NULL) {
                    $conteudo = "
                                <div> Caro senhor(a), " . $fields['nomeExibicao'] . ",</div>";
                    $conteudo .= "<div>Seu cadastro foi realizado com sucesso em " . date("d/m/Y  H:i") . ".";

                    $conteudo .= "<div><span style='margin-top:15px;'>DADOS DE ACESSO:</div>";
                    $conteudo .= "<div style='font-size:12px; font-weight: bold;margin-top:15px'>SITE:<span>" . base_url() . "</span></div>";
                    $conteudo .= "<div style='font-size:12px; font-weight: bold;margin-top:15px'>Nº PRONTUÁRIO: <span>" . $codProntuario . "</span></div>";
                    $conteudo .= "<div style='font-size:12px; font-weight: bold;margin-top:15px'>LOGIN: <span>" . $fields['cpf'] . "</span></div>";
                    $conteudo .= "<div style='font-size:12px; font-weight: bold;margin-top:15px'>SENHA: <span>" . $senha . "</span></div>";
                    $conteudo .= "<div style='font-size: 12px;margin-top:16px'>Atenciosamente,</div>";
                    if ($funcionario == 1) {
                        $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeFuncionarioResponsavel . "</div>";
                        $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeDepartamentoResponsavel  . "</div>";
                    }
                    $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . session()->descricaoOrganizacao . "</div>";

                    $resultadoEmail = @email($email, 'CADASTRO NOVO', $conteudo);
                    if ($resultadoEmail == false) {

                        //ADICIONAR NOTIFICAÇÃO ANA FILA EM CASO DE FALHA
                        @addNotificacoesFila($conteudo, $email, $email, 1);
                    }


                    //ENVIAR SMS
                    $celular = removeCaracteresIndesejados($fields['celular']);
                    $conteudoSMS = "
                                    Caro senhor(a), " . $fields['nomeExibicao'] . ",";
                    $conteudoSMS .= " Prontuário nº " . $codProntuario . " foi criado. Acesse: " . base_url() . ",  Login: " . $fields['cpf'] . ", Senha: " . $senha;

                    $conteudoSMS .= "Atenciosamente, ";
                    $conteudoSMS .= session()->siglaOrganizacao;

                    if ($celular !== NULL  and $celular !== ""  and $celular !== " ") {
                        $resultadoSMS = @sms($celular, $conteudoSMS);
                        if ($resultadoSMS == false) {

                            //ADICIONAR NOTIFICAÇÃO ANA FILA EM CASO DE FALHA
                            @addNotificacoesFila($conteudoSMS, 'Sistema', $celular, 2);
                        }
                    }
                }



                $response['success'] = true;
                $response['csrf_hash'] = csrf_hash();
                $response['codPacienteCriado'] = $codPaciente;
                $response['messages'] = 'Paciente criado com sucesso. Prontuário Nº ' . $codProntuario;
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


        $fields['codPaciente'] = $this->request->getPost('codPaciente');
        $fields['codOrganizacao'] =  session()->codOrganizacao;
        $fields['nomeExibicao'] = mb_strtoupper($this->request->getPost('nomeCompleto'), "utf-8");
        $fields['nomePrincipal'] = mb_strtoupper($this->request->getPost('nomeCompleto'), "utf-8");
        $fields['nomeCompleto'] = mb_strtoupper($this->request->getPost('nomeCompleto'), "utf-8");
        $fields['codClasse'] = 2;
        $fields['sexo'] = mb_strtoupper($this->request->getPost('sexo'), "utf-8");
        $fields['codPlano'] = $this->request->getPost('codPlano');
        $fields['cpf'] = removeCaracteresIndesejados($this->request->getPost('cpf'));
        $fields['identidade'] = $this->request->getPost('identidade');
        $fields['nomePai'] = $this->request->getPost('nomePai');
        $fields['nomeMae'] = $this->request->getPost('nomeMae');
        $fields['codForca'] = $this->request->getPost('codForca');
        $fields['codCargo'] = $this->request->getPost('codCargo');
        $fields['codOm'] = $this->request->getPost('codOm');
        $fields['codTipoBeneficiario'] = $this->request->getPost('codTipoBeneficiario');
        $fields['emailPessoal'] = $this->request->getPost('emailPessoal');
        $fields['celular'] = $this->request->getPost('celular');
        $fields['endereco'] = $this->request->getPost('endereco');
        $fields['cep'] = $this->request->getPost('cep');
        $fields['codStatusCadastroPaciente'] = $this->request->getPost('codStatusCadastroPaciente');
        $fields['dataNascimento'] = $this->request->getPost('dataNascimento');
        $fields['dataInicioEmpresa'] = $this->request->getPost('dataInicioEmpresa');
        $fields['validade'] = $this->request->getPost('validade');
        $fields['codRaca'] = $this->request->getPost('codRaca');
        $fields['codTipoSanguineo'] = $this->request->getPost('codTipoSanguineo');
        $fields['informacoesComplementares'] = $this->request->getPost('informacoesComplementares');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $request = \Config\Services::request();
        $ip = $request->getIPAddress();
        $fields['ipRequisitante'] = $ip;

        if (session()->codPessoa !== NULL) {
            $funcionario = 1;
            $fields['autorAtualizacao'] = session()->codPessoa;
            $pessoa = $this->PessoasModel->pegaPessoaDepartamento($fields['autorAtualizacao']);
            $nomeFuncionarioResponsavel = $pessoa->nomeExibicao;
            $nomeDepartamentoResponsavel = $pessoa->descricaoDepartamento;
        } else {
            $funcionario = 0;
            if (session()->codPaciente !== NULL) {
                $fields['autorAtualizacao'] = session()->codPaciente;
            } else {
                $fields['autorAtualizacao'] = 0;
            }
        }

        $this->validation->setRules([

            'codPaciente' => ['label' => 'codPaciente', 'rules' => 'required|numeric|max_length[10]'],
            'codOrganizacao' => ['label' => 'Código da Organização', 'rules' => 'required|integer|max_length[10]'],
            'nomeCompleto' => ['label' => 'Nome Completo', 'rules' => 'required|max_length[100]'],
            'sexo' => ['label' => 'Sexo', 'rules' => 'required|max_length[1]'],
            'codPlano' => ['label' => 'Código do Beneficiário (codplano)', 'rules' => 'required|integer|max_length[11]'],
            'emailPessoal'        => 'permit_empty|max_length[254]|valid_email',
            'cpf' => ['label' => 'cpf', 'rules' => 'required|max_length[15]'],
            'identidade' => ['label' => 'Identidade', 'rules' => 'required|integer|max_length[15]'],
            'nomeMae' => ['label' => 'Nome da Mãe', 'rules' => 'required|max_length[60]'],
            'celular' => ['label' => 'Celular', 'rules' => 'required|max_length[16]'],
            'endereco' => ['label' => 'Endereço', 'rules' => 'required|max_length[200]'],
            'codStatusCadastroPaciente' => ['label' => 'Status Cadastro Paciente', 'rules' => 'required|integer|max_length[9]'],
            'dataNascimento' => ['label' => 'Data de Nascimento', 'rules' => 'required|valid_date'],
            'dataInicioEmpresa' => ['label' => 'Data inclusão', 'rules' => 'permit_empty'],
            'codRaca' => ['label' => 'Raça', 'rules' => 'required|integer'],
            'codTipoSanguineo' => ['label' => 'Tipo Sanguineo', 'rules' => 'required|integer'],
            'informacoesComplementares' => ['label' => 'Informações Complementares', 'rules' => 'permit_empty|max_length[500]'],
        ]);

        if ($this->validation->run($fields) == false) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->PacientesModel->update($fields['codPaciente'], $fields)) {




                $paciente = $this->PacientesModel->pegaPacientePorCodPaciente($fields['codPaciente']);



                if ($paciente->emailPessoal !== NULL and $paciente->emailPessoal !== "" and $paciente->emailPessoal !== " ") {
                    $email = $paciente->emailPessoal;
                    $email = removeCaracteresIndesejadosEmail($email);
                } else {
                    $email = NULL;
                }

                if ($email !== NULL and $paciente->nomeExibicao !== NULL) {
                    $conteudo = "
							<div> Caro senhor(a), " . $paciente->nomeExibicao . ",</div>";
                    $conteudo .= "<div>Seus dados foram atualizados com sucesso em " . date("d/m/Y  H:i") . ".";
                    $conteudo .= "<div style='font-size: 12px; margin-top:16px'>Atenciosamente,</div>";
                    if ($funcionario == 1) {
                        $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeFuncionarioResponsavel . "</div>";
                        $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeDepartamentoResponsavel  . "</div>";
                    }
                    $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . session()->descricaoOrganizacao . "</div>";

                    $resultadoEmail = @email($email, 'ATUALIZAÇÃO DE CADASTRO', $conteudo);
                    if ($resultadoEmail == false) {

                        //ADICIONAR NOTIFICAÇÃO ANA FILA EM CASO DE FALHA
                        @addNotificacoesFila($conteudo, $email, $email, 1);
                    }


                    //ENVIAR SMS
                    $celular = removeCaracteresIndesejados($paciente->celular);
                    $conteudoSMS = "
								Caro senhor(a), " . $paciente->nomeExibicao . ",";
                    $conteudoSMS .= " Seus dados foram alterados com sucesso em " . date("d/m/Y  H:i") . ".";

                    $conteudoSMS .= "Atenciosamente, ";
                    $conteudoSMS .= session()->siglaOrganizacao;

                    if ($celular !== NULL  and $celular !== ""  and $celular !== " ") {
                        $resultadoSMS = @sms($celular, $conteudoSMS);
                        if ($resultadoSMS == false) {

                            //ADICIONAR NOTIFICAÇÃO ANA FILA EM CASO DE FALHA
                            @addNotificacoesFila($conteudoSMS, 'Sistema', $celular, 2);
                        }
                    }
                }



                $response['success'] = true;
                $response['codStatusCadastroPaciente'] = $fields['codStatusCadastroPaciente'];
                $response['cpf'] = $fields['cpf'];
                $response['codPlano'] = $fields['codPlano'];
                $response['endereco'] = $fields['endereco'];
                $response['celular'] = $fields['celular'];
                $response['dataNascimento'] = $fields['dataNascimento'];
                $response['emailPessoal'] = $fields['emailPessoal'];
                $response['validade'] = $fields['validade'];
                $response['informacoesComplementares'] = $fields['informacoesComplementares'];
                $response['csrf_hash'] = csrf_hash();
                $response['messages'] = 'Paciente atualizado com sucesso';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na inserção!';
            }
        }


        return $this->response->setJSON($response);
    }


    public function importarcontatos()
    {


        ini_set('max_execution_time', '0'); //300 seconds = 5 minutes
        set_time_limit(0);


        //CONTATO PRINCIPAL
        $contatosApolo = $this->PacientesModel->contatosApolo();

        foreach ($contatosApolo as $contato) {
            $fields = array();

            $numero = "";
            $ddd = "";
            $numeroCompleto = "";
            if ($contato->numero !== null and $contato->numero !== "") {
                $numero = str_replace("-", "", preg_replace('/[^0-9]/', '', $contato->numero));
            } else {
                $numero = str_replace("-", "", preg_replace('/[^0-9]/', '', $contato->obs));
            }

            if ($contato->ddd !== null and $contato->ddd !== "") {
                $ddd = "(" . ltrim($contato->ddd, '0') . ") ";
            }
            $numeroCompleto = $ddd . $numero;

            if ($numeroCompleto !== null and $numeroCompleto !== "") {
                $fields['numeroContato'] = $numeroCompleto;
            }


            $fields['codTipoContato'] = tipoContatoLookup($contato->tipo);
            $fields['nomeContato'] = null;
            $fields['codPaciente'] = codPacienteLookupPorProntuario($contato->pron_id, $contato->pron_nr);
            $fields['codOrganizacao'] = session()->codOrganizacao;
            $fields['codParentesco'] = null;
            $fields['observacoes'] =  $contato->obs;

            if ($fields['codPaciente'] !== NULL) {
                $this->PacientesModel->inserirOutroContato($fields);
                $this->PacientesModel->updateCelular($fields['codPaciente'], $numeroCompleto);
            }
        }




        //MAIS CONTATOS
        $outrosContatosPesApolo = $this->PacientesModel->outrosContatosPesApolo();

        foreach ($outrosContatosPesApolo as $contato) {
            $fields = array();

            $numero = "";
            $observacoes = "";

            $result = explode(' ', $contato->observacoes);

            if ($contato->emerg = 'SIM') {
                $observacoes =  'Emergência';
            }


            $maisContatos['observacoes'] = $observacoes;
            $maisContatos['codTipoContato'] = tipoContatoLookup($contato->tipo);
            $maisContatos['nomeContato'] = $contato->nome;
            $maisContatos['codPaciente'] = codPacienteLookupPorProntuario($contato->pron_id, $contato->pron_nr);
            $maisContatos['codOrganizacao'] = session()->codOrganizacao;
            $maisContatos['codParentesco'] = parentestolookup($contato->parentesco);

            foreach ($result as $valor) {
                $numero = str_replace("-", "", preg_replace('/[^0-9]/', '', $valor));
                if ($numero !== NULL and  $numero !== "") {

                    $maisContatos['numeroContato'] = $numero;


                    if ($maisContatos['codPaciente'] !== NULL and strlen($numero) >= 8) {
                        $this->PacientesModel->inserirOutroContato($maisContatos);
                    }
                }
            }
        }





        //IMPORTAR EMAILS
        $emails = $this->PacientesModel->emailsApolo();

        foreach ($emails as $email) {


            if ($email->pron_id !== NULL and $email->valor !== NULL) {
                $this->PacientesModel->updateEmail($email->pron_id, $email->pron_nr, mb_strtolower($email->valor, "utf-8"));
            }
        }


        $response['success'] = true;
        $response['csrf_hash'] = csrf_hash();
        $response['messages'] = 'Importação de contatos realizada com sucesso';
        return $this->response->setJSON($response);
    }

    public function importarPacientes()
    {

        //REGRA DE SICRONIZAÇÃO DO LDAP IMPORTAÇÃO AQUI

        //Espera mínima

        $inicioProcesso = time();



        $response = array();

        $prontuarios = $this->PacientesModel->prontuarios();
        $pacientesNovos = 0;
        $pacientesAtualizados = 0;

        //APAGA PROTUARIOS ANTERIORES PARA RECARREGAR
        // $this->PacientesModel->apagaProntuariosAnteriores();

        foreach ($prontuarios as $prontuario) {



            $fields = array();
            $protuariosAnteriores = array();

            //VERIFICA EXISTENCIA

            if ($prontuario->nome !== NULL) {

                $fields['nomeExibicao'] = $prontuario->nome;
                $fields['nomePrincipal'] = $prontuario->nome;
                $fields['nomeCompleto'] = $prontuario->nome;
            }

            if ($prontuario->identidade !== NULL) {
                $fields['identidade'] = $prontuario->idt;
            }

            if ($prontuario->cpf !== NULL) {
                $fields['cpf'] = $prontuario->cpf;
            }
            $fields['dataAtualizacao'] = date('Y-m-d H:i:s');


            if ($prontuario->data_nasc !== NULL) {
                $fields['dataNascimento'] = $prontuario->data_nasc;
            }

            if ($prontuario->data_nasc !== NULL) {
                $fields['dataNascimento'] = $prontuario->data_nasc;
            }

            $fields['dataAtualizacao'] = date('Y-m-d H:i:s');
            $fields['codCargo'] = postoGraduacaoLookup($prontuario->p_grad);
            $fields['codPerfilPadrao'] = 9;
            $fields['aceiteTermos'] = 0;
            $fields['codForca'] = forcaLookup($prontuario->forca);
            $fields['codRaca'] = racaLookup($prontuario->raca);
            $fields['ativo'] = ativoLookup($prontuario->bloq);
            $fields['codStatusCadastroPaciente'] = statusLookup($prontuario->bloq);

            if ($prontuario->situacao !== NULL) {
                $fields['codTipoBeneficiario'] = tipoBeneficiarioLookup($prontuario->situacao);
            }


            if ($prontuario->tp_ts !== NULL) {
                $fields['codTipoSanguineo'] = 0;
                //$fields['codTipoSanguineo'] = tipoSanguineoLookup($prontuario->tp_ts, $prontuario->tp_rh);
            }

            if ($prontuario->summary !== NULL) {
                $fields['informacoesComplementares'] = $prontuario->obs . '. ' . $prontuario->summary;
            }

            if ($prontuario->pront !== NULL) {
                $fields['codProntuario'] = $prontuario->pront;
            }


            if ($prontuario->pron_id !== NULL and $prontuario->pron_id !== "" and $prontuario->pron_id !== 0) {
                $fields['pronID'] = $prontuario->pron_id;
            } else {
                $fields['pronID'] = "0";
            }

            if ($prontuario->pron_nr !== NULL and $prontuario->pron_nr !== "" and $prontuario->pron_nr !== 0) {
                $fields['pronNR'] = $prontuario->pron_nr;
            } else {
                $fields['pronNR'] = 0;
            }

            if ($prontuario->cod_ben !== NULL) {
                $fields['codPlano'] = $prontuario->cod_ben;
            }

            if ($prontuario->sexo !== NULL) {
                $fields['sexo'] = $prontuario->sexo;
            }


            if ($prontuario->validade !== NULL) {
                $fields['validade'] = $prontuario->validade;
            }

            if ($prontuario->mae !== NULL) {
                $fields['nomeMae'] = $prontuario->mae;
            }
            if ($prontuario->pai !== NULL) {
                $fields['nomePai'] = $prontuario->pai;
            }
            if ($prontuario->omvinc !== NULL) {
                $fields['omVinc'] = $prontuario->omvinc;
            }

            if ($prontuario->isindet !== NULL) {
                $fields['isindet'] = $prontuario->isindet;
            }
            if ($prontuario->pront_ant1 !== NULL) {
                $fields['prontAnt'] = $prontuario->pront_ant1;
            }

            if ($prontuario->file_up !== NULL) {
                $fields['fotoPerfil'] = $prontuario->file_up;
            }


            if (session()->codPessoa !== NULL) {
                $fields['autor'] = session()->codPessoa;
            } else {
                if (session()->codPaciente !== NULL) {
                    $fields['autor'] = session()->codPaciente;
                } else {
                    $fields['autor'] = 0;
                }
            }

            if ($prontuario->pron_id !== NULL and $prontuario->pron_nr !== NULL) {



                $existe = $this->PacientesModel->verificaExistencia($prontuario->pron_id, $prontuario->pron_nr);

                ini_set('max_execution_time', '0'); //300 seconds = 5 minutes
                set_time_limit(0);

                if ($existe == NULL) {
                    $pacientesNovos++;

                    $fields['conta'] = $prontuario->cod_ben;
                    $fields['dataCriacao'] = $prontuario->data_lc;
                    $fields['codOrganizacao'] =  session()->codOrganizacao;
                    $fields['apolo'] = 1;
                    $fields['codClasse'] = 2;
                    $codPaciente = $this->PacientesModel->insert($fields);


                    //prontuários anteriores
                    $protuariosAnteriores['codPaciente'] =  $existe->codPaciente;
                    $protuariosAnteriores['sistema'] = 'Apolo';
                    $protuariosAnteriores['codProntuario'] = $prontuario->pront;
                    $this->PacientesModel->prontuariosAnteriores($protuariosAnteriores);
                } else {
                    /*
                    if ($prontuarios->celular !== NULL) {
                        $pacientesAtualizados++;
                        $this->PacientesModel->update($existe->codPaciente, $fields);

                        //prontuários anteriores
                        $protuariosAnteriores['codPaciente'] =  $existe->codPaciente;
                        $protuariosAnteriores['sistema'] = 'Apolo';
                        $protuariosAnteriores['codProntuario'] = $prontuario->pront;
                        $this->PacientesModel->prontuariosAnteriores($protuariosAnteriores);
                    }
                    */
                }
            }
        }

        $fimProcesso = time();

        $tempo = $fimProcesso - $inicioProcesso;
        $tempo = $tempo / 60;
        $response['success'] = true;
        $response['csrf_hash'] = csrf_hash();
        $response['messages'] = '
        <div>Informações importadas com sucesso.</div>
        <div>Tempo de execução: ' . $tempo . ' minutos</div>
        <div>Pacientes Novos: ' . $pacientesNovos . '</div>
        <div>Pacientes Atualizados: ' . $pacientesAtualizados . '</div>';
        return $this->response->setJSON($response);
    }

    public function importarPacientesSIGH()
    {

        //REGRA DE SICRONIZAÇÃO DO LDAP IMPORTAÇÃO AQUI

        //Espera mínima


        ini_set('max_execution_time', '0'); //300 seconds = 5 minutes
        set_time_limit(0);


        $inicioProcesso = time();



        $response = array();

        $prontuarios = $this->PacientesModel->prontuariosSIGH();
        $pacientesNovos = 0;
        $pacientesAtualizados = 0;

        //APAGA PROTUARIOS ANTERIORES PARA RECARREGAR
        // $this->PacientesModel->apagaProntuariosAnteriores();

        foreach ($prontuarios as $prontuario) {



            $fields = array();
            $protuariosAnteriores = array();


            $fields['codPaciente'] = $prontuario->CD_PACIENTE;

            //VERIFICA EXISTENCIA

            if ($prontuario->PACIENTE !== NULL) {

                $fields['nomeExibicao'] = $prontuario->PACIENTE;
                $fields['nomePrincipal'] = $prontuario->PACIENTE;
                $fields['nomeCompleto'] = $prontuario->PACIENTE;
            }







            $identidade = removeCaracteresIndesejados($prontuario->IDENT);

            if (is_numeric($identidade) == 1 and $prontuario->IDENT !== NULL) {
                $fields['identidade'] = $identidade;
            } else {
                //BUSCA IDENTIDADE BENEFICIARIOS_PLANO
                $identidade = $this->PacientesModel->buscaIdentidadeSIGH($prontuario->codPlano, $prontuario->SEQFAM)->IDENTBEN;
                $identidade = removeCaracteresIndesejados(filter_var($identidade, FILTER_SANITIZE_NUMBER_INT));
                $fields['identidade'] = $identidade;
            }


            if ($prontuario->CPF !== NULL and $prontuario->CPF !== '' and $prontuario->CPF == ' ') {

                $cpf = removeCaracteresIndesejados($prontuario->CPF);

                if (is_numeric($cpf) == 1) {
                    $fields['cpf'] = $cpf;
                } else {
                    //BUSCA CPF BENEFICIARIOS_PLANO
                    $cpf = $this->PacientesModel->buscaCPFSIGH($prontuario->codPlano, $prontuario->SEQFAM)->CPFBEN;
                    $cpf = removeCaracteresIndesejados(filter_var($cpf, FILTER_SANITIZE_NUMBER_INT));
                    $fields['cpf'] = $cpf;
                }
            } else {
                //BUSCA CPF BENEFICIARIOS_PLANO
                $cpf = $this->PacientesModel->buscaCPFSIGH($prontuario->codPlano, $prontuario->SEQFAM)->CPFBEN;

                if ($cpf !== NULL and $cpf !== '' and $cpf !== ' ') {
                    $cpf = removeCaracteresIndesejados(filter_var($cpf, FILTER_SANITIZE_NUMBER_INT));
                    $fields['cpf'] = $cpf;
                } else {
                    $pegaCPFPorPrec = $this->PacientesModel->buscaCodPlanoSIGH($prontuario->codPlano, $prontuario->SEQ);
                    $fields['cpf'] =  $pegaCPFPorPrec->CPFBEN;
                }
            }



            $fields['dataAtualizacao'] = date('Y-m-d H:i:s');


            if ($prontuario->DT_NASC !== NULL) {
                $fields['dataNascimento'] = $prontuario->DT_NASC;
            } else {
                //BUSCA CPF CADBEN
                $DTNASC = $this->PacientesModel->buscaCPFSIGH($prontuario->codPlano, $prontuario->SEQFAM)->DTNASC;

                if ($DTNASC == NULL) {
                    $DTNASC = $this->PacientesModel->buscaCodPlanoSIGH($prontuario->codPlano, $prontuario->SEQ)->CPFBEN;
                }

                $fields['dataNascimento'] = $DTNASC;
            }


            $fields['dataAtualizacao'] = date('Y-m-d H:i:s');
            $fields['codPerfilPadrao'] = 9;
            $fields['aceiteTermos'] = 0;
            $fields['codRaca'] = 0;
            $fields['codTipoSanguineo'] = 0;

            if ($prontuario->OBS_PRONT !== NULL) {
                $fields['informacoesComplementares'] = $prontuario->OBS_PRONT;
            }

            if ($prontuario->NR_PRONT !== NULL and $prontuario->NR_PRONT !== '0' and $prontuario->NR_PRONT !== '') {
                $fields['codProntuario'] = $prontuario->NR_PRONT;
            } else {
                //GERA PRONTUÁRIO
                $ano = date('Y');
                $digito = geraNumero(1);
                $codProntuario = $ano . str_pad($fields['codPaciente'], 6, '0', STR_PAD_LEFT) . $digito;
                $fields['codProntuario'] = $codProntuario;
                $fields['pronID'] = substr($codProntuario, 0, 5);
                $fields['pronNR'] = substr($codProntuario, -5);;
            }

            if ($prontuario->SEXO !== NULL) {
                $fields['sexo'] = $prontuario->SEXO;
            }
            $fields['codCargo'] = postoGraduacaoLookupSIGH($prontuario->CD_PSTGRD);
            $fields['codForca'] = 2;
            $fields['ativo'] = ativoLookupSIGH($prontuario->SIT_PRONT);
            $fields['codStatusCadastroPaciente'] = ativoLookupSIGH($prontuario->SIT_PRONT);
            if ($prontuario->CD_SIT_BEN !== NULL) {
                $fields['codTipoBeneficiario'] = tipoBeneficiarioLookupSIGH($prontuario->CD_SIT_BEN);
            }
            if ($prontuario->codPlano !== NULL) {
                $fields['codPlano'] = $prontuario->codPlano . str_pad(str_pad(filter_var($prontuario->SEQ, FILTER_SANITIZE_NUMBER_INT), 2, '0', STR_PAD_LEFT), 2, '0', STR_PAD_LEFT);
            }
            if ($prontuario->DT_VALIDADE !== NULL) {
                $fields['validade'] = $prontuario->DT_VALIDADE;
            }
            if ($prontuario->MAE !== NULL) {
                $fields['nomeMae'] = $prontuario->MAE;
            }
            if ($prontuario->PAI !== NULL) {
                $fields['nomePai'] = $prontuario->PAI;
            }
            if ($prontuario->CODOM_VINC !== NULL) {
                $fields['codOm'] = $prontuario->CODOM_VINC;
            }
            if ($prontuario->CODOM_VINC !== NULL) {
                $fields['omVinc'] = $prontuario->CODOM_VINC;
            }
            $fields['isindet'] = NULL;




            if ($prontuario->NR_PRONT_ANT !== NULL) {
                $fields['prontAnt'] = $prontuario->NR_PRONT_ANT;
            }


            $fields['fotoPerfil'] = NULL;

            if (session()->codPessoa !== NULL) {
                $fields['autor'] = session()->codPessoa;
            } else {
                if (session()->codPaciente !== NULL) {
                    $fields['autor'] = session()->codPaciente;
                } else {
                    $fields['autor'] = 0;
                }
            }

            $existe = $this->PacientesModel->verificaExistenciaSIGH($fields['codPaciente']);


            if ($existe == NULL) {
                $pacientesNovos++;

                $fields['conta'] = $fields['codPlano'];
                $fields['dataCriacao'] = date('Y-m-d H:i');
                $fields['codOrganizacao'] =  session()->codOrganizacao;
                $fields['apolo'] = 1;
                $fields['codClasse'] = 2;
                $codPaciente = $this->PacientesModel->insert($fields);


                //prontuários anteriores
                $protuariosAnteriores['codPaciente'] = $existe->codPaciente;
                $protuariosAnteriores['sistema'] = 'SIGH';
                $protuariosAnteriores['codProntuario'] = $prontuario->NR_PRONT_ANT;
                if ($prontuario->NR_PRONT_ANT !== 0 and $prontuario->NR_PRONT_ANT !== NULL and $prontuario->NR_PRONT_ANT !== "") {

                    $this->PacientesModel->prontuariosAnteriores($protuariosAnteriores);
                }


                //INSERIR TELEFONES

                $telefones = $this->PacientesModel->telefonesSIGH($fields['codPaciente']);

                $telfone = array();

                foreach ($telefones as $tel) {

                    if ($tel->DDD == '0' or $tel->DDD == '00') {
                        $tel->DDD = NULL;
                    }
                    $telfone['codPaciente'] = $fields['codPaciente'];
                    $telfone['codOrganizacao'] = session()->codOrganizacao;
                    $telfone['codTipoContato'] =  1;
                    $telfone['nomeContato'] =  $tel->nomeContato;
                    $telfone['numeroContato'] =  $tel->DDD . $tel->FONE;
                    $telfone['codParentesco'] =  $tel->codParentesco;
                    $telfone['observacoes'] =  $tel->observacoes;

                    if ($tel->DDD == '0' and $tel->DDD == '00' and $tel->DDD == NULL and $tel->DDD == '') {
                    } else {
                        $this->PacientesModel->inserirOutroContato($telfone);
                    }
                }
            } else {

                $pacientesAtualizados++;
                //$this->PacientesModel->update($fields['codPaciente'], $fields);
            }
        }
        $fimProcesso = time();

        $tempo = $fimProcesso - $inicioProcesso;
        $tempo = $tempo / 60;
        $response['success'] = true;
        $response['csrf_hash'] = csrf_hash();
        $response['messages'] = '
        <div>Informações importadas com sucesso.</div>
        <div>Tempo de execução: ' . $tempo . ' minutos</div>
        <div>Pacientes Novos: ' . $pacientesNovos . '</div>
        <div>Pacientes Atualizados: ' . $pacientesAtualizados . '</div>';
        return $this->response->setJSON($response);
    }


    public function atualizaPrecPacientes()
    {

        //REGRA DE SICRONIZAÇÃO DO LDAP IMPORTAÇÃO AQUI

        //Espera mínima

        ini_set('max_execution_time', '0'); //300 seconds = 5 minutes
        set_time_limit(0);

        $response = array();



        $pacientes = $this->PacientesModel->faltaRecuperarCodPlano();

        foreach ($pacientes as $paciente) {


            $existe = $this->PacientesModel->prontuariosRecuperados($paciente->pronID, $paciente->pronNR);

            if ($existe !== NULL) {
                if ($existe->cod_ben !== NULL and $existe->cod_ben !== "" and $existe->pron_id !== NULL and $existe->pron_nr !== NULL) {

                    @$this->PacientesModel->updateCodPlano($existe->pron_id, $existe->pron_nr, $existe->cod_ben);
                }
            }
        }

        $response['success'] = true;
        $response['csrf_hash'] = csrf_hash();
        $response['messages'] = '<div>Informações importadas com sucesso.</div>';
        return $this->response->setJSON($response);
    }



    public function prontuarioAnteriores()
    {
        ini_set('max_execution_time', '0'); //300 seconds = 5 minutes
        set_time_limit(0);
        $response = array();
        $prontuarios = $this->PacientesModel->prontuarios();

        foreach ($prontuarios as $prontuario) {

            $fields = array();

            $existe = $this->PacientesModel->verificaExistencia($prontuario->pron_id, $prontuario->pron_nr);
            if ($existe !== NULL) {
                $fields['codPaciente'] =  $existe->codPaciente;
                $fields['sistema'] = 'Apolo';
                $fields['codProntuario'] = $prontuario->pront;
                $this->PacientesModel->prontuariosAnteriores($fields);
            }
        }
        $response['success'] = true;
        $response['csrf_hash'] = csrf_hash();
        $response['messages'] = 'Operação concluída com sucesso';
        return $this->response->setJSON($response);
    }


    public function teste()
    {
        return $this->response->setJSON(true);
    }

    function uploadFoto()
    {
        $response = array();

        if ($this->request->getPost('cpf') !== NULL and $this->request->getPost('cpf') !== "") {
            $nomeArquivo = trim(removeCaracteresIndesejados($this->request->getPost('cpf')));
            $data_url =  $this->request->getPost('imagem');

            list($type, $data) = explode(';', $data_url);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);

            file_put_contents('arquivos/imagens/pacientes/' . $nomeArquivo . ".jpeg", $data);
        }

        //NA CRIAÇÃO
        if ($this->request->getPost('codPacienteCriado') !== NULL) {
            $fields['fotoPerfil'] =  $nomeArquivo . ".jpeg";
            $this->PacientesModel->update($this->request->getPost('codPacienteCriado'), $fields);
        }

        //NA ATUALIZACAO
        if ($this->request->getPost('codPaciente') !== NULL and $this->request->getPost('codPaciente') !== "") {
            $fields['fotoPerfil'] =  $nomeArquivo . ".jpeg";
            $this->PacientesModel->update($this->request->getPost('codPaciente'), $fields);
        }


        $response['success'] = true;
        $response['fotoPerfil'] =  $fields['fotoPerfil'];
        $response['csrf_hash'] = csrf_hash();

        return $this->response->setJSON($response);
    }
    function enviaFoto()
    {

        //FAZ O UPLOAD E GRAVA NO BANCO

        $response = array();

        $avatar = $this->request->getFile('file');
        $nomeArquivo = $this->request->getPost('codPaciente') . '.' . $avatar->getClientExtension();
        $avatar->move(WRITEPATH . '../imagens/pacientes/',  $nomeArquivo, true);



        $fields['fotoPerfil'] =  $nomeArquivo;


        $db      = \Config\Database::connect();
        $builder = $db->table('sis_pacientes');

        if ($this->request->getPost('codPaciente') !== NULL and $this->request->getPost('codPaciente') !== "") {
            $builder->where('codPaciente', $this->request->getPost('codPaciente'));
            $builder->update($fields);
        }




        $response['success'] = true;
        $response['csrf_hash'] = csrf_hash();
        $response['messages'] = 'Sucesso!';
        $response['meuCodPaciente'] = session()->codPaciente;
        $response['nomeArquivo'] =  $nomeArquivo;



        if (session()->codPaciente == $this->request->getPost('codPaciente')) {
            session()->set('fotoPerfil', $nomeArquivo);
        }
        return $this->response->setJSON($response);
    }

    public function trocaSenha($codPaciente = null, $senha = null, $confirmacao = null)
    {


        $response = array();


        if ($codPaciente == null) {
            $codPaciente = $this->request->getPost('codPaciente');
        } else {
            $codPaciente = $codPaciente;
        }

        if ($senha == null) {
            $senha = $this->request->getPost('senha');
        } else {
            $senha = $senha;
        }


        if ($confirmacao == null) {
            $confirmacao = $this->request->getPost('confirmacao');
        } else {
            $confirmacao = $confirmacao;
        }

        $organizacao =  $this->OrganizacoesModel->pegaOrganizacao(session()->codOrganizacao);



        $fields['codPaciente'] = $codPaciente;
        $fields['senha1'] = $senha;
        $fields['senha2'] = $confirmacao;

        /*
        $chave = $paciente->chaveSalgada;
        $tipo_cifra = 'des';

        //CRIPTOGRAFIA DE SENHA
        $senhaResincLDAP = encriptar($chave, $tipo_cifra, $fields['senha1']); // print descriptar($chave, $tipo_cifra, 'dHZPcW84ZktwaytPOFBrTjBadk1QUT09OjqP+UO2YtpH7g==');

        $fields['senhaResincLDAP'] = $senhaResincLDAP;
        */



        $statusTrocaSenha = "";

        //TROCA SENHA 
        $senha = hash("sha256", $senha . $organizacao->chaveSalgada);
        $fields['senha'] = $senha;
        $fields['dataSenha'] = date('Y-m-d H:i');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');

        $this->validation->setRules([
            'senha1' => ['label' => 'Senha', 'rules' => 'permit_empty|matches[senha2]'],
            'senha2' => ['label' => 'Confirmação', 'rules' => 'required|max_length[40]'],
        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {
            $response['success'] = true;
            $response['csrf_hash'] = csrf_hash();




            if ($fields['codPaciente'] !== NULL and $fields['codPaciente'] !== "" and $fields['codPaciente'] !== " ") {
                if ($this->PacientesModel->update($fields['codPaciente'], $fields)) {
                    $response['messages'] = '<div style="font-weight:bold;font-size:18px">Senha atualizada com sucesso. Anote imediatamene a senha estabelecida</div><br>';
                } else {

                    $response['success'] = false;
                    $response['messages'] = 'Erro na atualização da senha!';
                }
            } else {
                $response['success'] = false;
                $response['messages'] = 'Erro na atualização da senha!';
            }
        }


        //ENVIAR NOTIFICAÇÃO
        $dadosPaciente = $this->PacientesModel->pegaPacientePorCodPaciente($fields['codPaciente']);

        if (session()->codPessoa !== NULL) {
            $funcionario = 1;
            $fields['codAutor'] = session()->codPessoa;
            $pessoa = $this->PessoasModel->pegaPessoaDepartamento(session()->codPessoa);
            $nomeFuncionarioResponsavel = $pessoa->nomeExibicao;
            $nomeDepartamentoResponsavel = $pessoa->descricaoDepartamento;
        } else {
            $funcionario = 0;
            if (session()->codPaciente !== NULL) {
                $fields['codAutor'] = session()->codPaciente;
            } else {
                $fields['codAutor'] = 0;
            }
        }

        if ($dadosPaciente->emailPessoal !== NULL and $dadosPaciente->emailPessoal !== "" and $dadosPaciente->emailPessoal !== " ") {
            $email = $dadosPaciente->emailPessoal;
            $email = removeCaracteresIndesejadosEmail($email);
        } else {
            $email = NULL;
        }

        if ($email !== NULL and $dadosPaciente->nomeExibicao !== NULL) {
            $conteudo = "
                        <div> Caro senhor(a), " . $dadosPaciente->nomeExibicao . ",</div>";
            $conteudo .= "<div>sua senha foi alterada com sucesso em " . date("d/m/Y  H:i") . ".</div>";


            $conteudo .= "<div style='font-size: 12px;margin-top:16px'>Atenciosamente,</div>";
            if ($funcionario == 1) {
                $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeFuncionarioResponsavel . "</div>";
                $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . $nomeDepartamentoResponsavel  . "</div>";
            }
            $conteudo .= "<div style='font-size: 12px; margin-top:0px'>" . session()->descricaoOrganizacao . "</div>";

            $resultadoEmail = @email($email, 'TROCA DE SENHA', $conteudo);
            if ($resultadoEmail == false) {

                //ADICIONAR NOTIFICAÇÃO ANA FILA EM CASO DE FALHA
                @addNotificacoesFila($conteudo, $email, $email, 1);
            }


            //ENVIAR SMS
            $celular = removeCaracteresIndesejados($dadosPaciente->celular);
            $conteudoSMS = "
                           Caro senhor(a), sua senha foi alterada com sucesso!";

            $conteudoSMS .= "Atenciosamente, ";
            $conteudoSMS .= session()->siglaOrganizacao;

            if ($celular !== NULL  and $celular !== ""  and $celular !== " ") {
                $resultadoSMS = @sms($celular, $conteudoSMS);
                if ($resultadoSMS == false) {

                    //ADICIONAR NOTIFICAÇÃO ANA FILA EM CASO DE FALHA
                    @addNotificacoesFila($conteudoSMS, 'Sistema', $celular, 2);
                }
            }
        }

        return $this->response->setJSON($response);
    }




    public function remove()
    {

        $permissao = verificaPermissao('Pacientes', 'deletar');

        if ($permissao == 0) {
            $response['success'] = false;
            $response['messages'] = 'Você não tem permissão para deletar';
            return $this->response->setJSON($response);
        }
        $response = array();

        $id = $this->request->getPost('codPaciente');

        if (!$this->validation->check($id, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($this->PacientesModel->where('codPaciente', $id)->delete()) {


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
