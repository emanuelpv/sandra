<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\FornecedoresModel;
use App\Models\EspecialidadesModel;

class Fornecedores extends BaseController
{

    protected $FornecedoresModel;
    protected $pessoasModel;
    protected $OrganizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

    public function __construct()
    {

        helper('seguranca_helper');
        verificaSeguranca($this, session(), base_url());
        $this->FornecedoresModel = new FornecedoresModel();
        $this->OrganizacoesModel = new OrganizacoesModel();
        $this->EspecialidadesModel = new EspecialidadesModel();
        $this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
        $this->validation = \Config\Services::validation();
        $this->codOrganizacao = session()->codOrganizacao;
        $this->Organizacao = $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);



    }

    public function index()
    {

        $permissao = verificaPermissao('Fornecedores', 'listar');
        if ($permissao == 0) {
            echo mensagemAcessoNegado(session()->organizacoes);
            $this->LogsModel->inserirLog('Acesso indevido ao Módulo "Fornecedores"', session()->codPessoa);
            exit();
        }


        $data = [
            'controller' => 'fornecedores',
            'title' => 'Fornecedores'
        ];
        echo view('tema/cabecalho');
        echo view('tema/menu_vertical');
        echo view('tema/menu_horizontal');
        return view('fornecedores', $data);

    }

    public function listaDropDownEstadosFederacao()
    {

        $result = $this->EspecialidadesModel->listaDropDownEstadosFederacao();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function listaDropDownTipoFornecedor()
    {

        $result = $this->FornecedoresModel->listaDropDownTipoFornecedor();

        if ($result !== NULL) {

            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function listaDropDownFornecedores()
    {

        $result = $this->FornecedoresModel->listaDropDownFornecedores();

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

        $result = $this->FornecedoresModel->pegaTudo();

        foreach ($result as $key => $value) {

            $ops = '<div class="btn-group">';
            $ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editfornecedores(' . $value->codFornecedor . ')"><i class="fa fa-edit"></i></button>';
            $ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removefornecedores(' . $value->codFornecedor . ')"><i class="fa fa-trash"></i></button>';
            $ops .= '</div>';

            $data['data'][$key] = array(
                $value->codFornecedor,
                $value->inscricao,
                $value->codTipo,
                $value->codNatureza,
                $value->nomeFantasia,
                $value->razaoSocial,
                $value->endereco,
                $value->cidade,
                $value->codEstadoFederacao,
                $value->cep,
                $value->contatos,
                $value->email,
                $value->website,
                $value->simples,
                $value->mnt,
                $value->observacoes,

                $ops,
            );
        }

        return $this->response->setJSON($data);
    }

    public function getOne()
    {
        $response = array();

        $id = $this->request->getPost('codFornecedor');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->FornecedoresModel->pegaPorCodigo($id);

            return $this->response->setJSON($data);

        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();

        }

    }

    public function add()
    {

        $response = array();

        $fields['codFornecedor'] = $this->request->getPost('codFornecedor');
        $fields['inscricao'] = $this->request->getPost('inscricao');
        $fields['codTipo'] = $this->request->getPost('codTipo');
        $fields['nomeFantasia'] = mb_strtoupper($this->request->getPost('nomeFantasia'), 'utf-8');
        $fields['razaoSocial'] = mb_strtoupper($this->request->getPost('razaoSocial'), 'utf-8');
        $fields['endereco'] = $this->request->getPost('endereco');
        $fields['cidade'] = mb_strtoupper($this->request->getPost('cidade'), 'utf-8');
        $fields['codEstadoFederacao'] = $this->request->getPost('codEstadoFederacao');
        $fields['cep'] = $this->request->getPost('cep');
        $fields['contatos'] = $this->request->getPost('contatos');
        $fields['email'] = $this->request->getPost('email');
        $fields['website'] = $this->request->getPost('website');
        $fields['simples'] = $this->request->getPost('simples');
        $fields['mnt'] = $this->request->getPost('mnt');
        $fields['observacoes'] = $this->request->getPost('observacoes');
        $fields['dataCriacao'] = date('Y-m-d H:i');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['codAutor'] = session()->codPessoa;


        $this->validation->setRules([
            'inscricao' => ['label' => 'inscricao', 'rules' => 'required|max_length[20]'],
            'codTipo' => ['label' => 'CodTipo', 'rules' => 'required|max_length[11]'],
            'nomeFantasia' => ['label' => 'NomeFantasia', 'rules' => 'permit_empty|max_length[255]'],
            'razaoSocial' => ['label' => 'RazaoSocial', 'rules' => 'permit_empty|max_length[255]'],
            'endereco' => ['label' => 'Endereço', 'rules' => 'permit_empty'],
            'cidade' => ['label' => 'Cidade', 'rules' => 'permit_empty|max_length[30]'],
            'codEstadoFederacao' => ['label' => 'UF', 'rules' => 'permit_empty|numeric|max_length[11]'],
            'cep' => ['label' => 'CEP', 'rules' => 'permit_empty|max_length[9]'],
            'contatos' => ['label' => 'Contatos', 'rules' => 'permit_empty'],
            'email' => ['label' => 'Email', 'rules' => 'permit_empty|max_length[255]'],
            'website' => ['label' => 'Website', 'rules' => 'permit_empty|max_length[255]'],
            'simples' => ['label' => 'Simples', 'rules' => 'permit_empty|max_length[3]'],
            'mnt' => ['label' => 'Mnt', 'rules' => 'permit_empty|max_length[3]'],
            'observacoes' => ['label' => 'Observações', 'rules' => 'permit_empty'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();

        } else {

            if ($codFornecedor = $this->FornecedoresModel->insert($fields)) {

                $response['success'] = true;
                $response['codFornecedor'] = $codFornecedor;
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

        $fields['codFornecedor'] = $this->request->getPost('codFornecedor');
        $fields['inscricao'] = $this->request->getPost('inscricao');
        $fields['codTipo'] = $this->request->getPost('codTipo');
        $fields['nomeFantasia'] = mb_strtoupper($this->request->getPost('nomeFantasia'), 'utf-8');
        $fields['razaoSocial'] = mb_strtoupper($this->request->getPost('razaoSocial'), 'utf-8');
        $fields['endereco'] = $this->request->getPost('endereco');
        $fields['cidade'] = mb_strtoupper($this->request->getPost('cidade'), 'utf-8');
        $fields['codEstadoFederacao'] = $this->request->getPost('codEstadoFederacao');
        $fields['cep'] = $this->request->getPost('cep');
        $fields['contatos'] = $this->request->getPost('contatos');
        $fields['email'] = $this->request->getPost('email');
        $fields['website'] = $this->request->getPost('website');
        $fields['simples'] = $this->request->getPost('simples');
        $fields['mnt'] = $this->request->getPost('mnt');
        $fields['observacoes'] = $this->request->getPost('observacoes');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');


        $this->validation->setRules([
            'codFornecedor' => ['label' => 'codFornecedor', 'rules' => 'required|numeric'],
            'inscricao' => ['label' => 'inscricao', 'rules' => 'required|max_length[20]'],
            'codTipo' => ['label' => 'CodTipo', 'rules' => 'required|max_length[11]'],
            'nomeFantasia' => ['label' => 'NomeFantasia', 'rules' => 'permit_empty|max_length[255]'],
            'razaoSocial' => ['label' => 'RazaoSocial', 'rules' => 'permit_empty|max_length[255]'],
            'endereco' => ['label' => 'Endereço', 'rules' => 'permit_empty'],
            'cidade' => ['label' => 'Cidade', 'rules' => 'permit_empty|max_length[30]'],
            'codEstadoFederacao' => ['label' => 'UF', 'rules' => 'permit_empty|numeric|max_length[11]'],
            'cep' => ['label' => 'CEP', 'rules' => 'permit_empty|max_length[9]'],
            'contatos' => ['label' => 'Contatos', 'rules' => 'permit_empty'],
            'email' => ['label' => 'Email', 'rules' => 'permit_empty|max_length[255]'],
            'website' => ['label' => 'Website', 'rules' => 'permit_empty|max_length[255]'],
            'simples' => ['label' => 'Simples', 'rules' => 'permit_empty|max_length[3]'],
            'mnt' => ['label' => 'Mnt', 'rules' => 'permit_empty|max_length[3]'],
            'observacoes' => ['label' => 'Observações', 'rules' => 'permit_empty'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();

        } else {

            if ($this->FornecedoresModel->update($fields['codFornecedor'], $fields)) {

                $response['success'] = true;
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

        $id = $this->request->getPost('codFornecedor');

        if (!$this->validation->check($id, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();

        } else {

            if ($this->FornecedoresModel->where('codFornecedor', $id)->delete()) {

                $response['success'] = true;
                $response['messages'] = 'Deletado com sucesso';

            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na deleção!';

            }
        }

        return $this->response->setJSON($response);
    }

}