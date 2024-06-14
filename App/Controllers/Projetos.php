<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\ProjetosModel;
use App\Models\ProjetosEscopoModel;
use App\Models\ProjetosMembrosModel;
use App\Models\ProjetosFaseModel;


class Projetos extends BaseController
{

    protected $ProjetosModel;
    protected $pessoasModel;
    protected $OrganizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

    public function __construct()
    {

        helper('seguranca_helper');
        verificaSeguranca($this, session(), base_url());
        $this->ProjetosModel = new ProjetosModel();
        $this->ProjetosEscopoModel = new ProjetosEscopoModel();
        $this->ProjetosMembrosModel = new ProjetosMembrosModel();
        $this->ProjetosFaseModel = new ProjetosFaseModel();
        $this->OrganizacoesModel = new OrganizacoesModel();
        $this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
        $this->validation =  \Config\Services::validation();
        $this->codOrganizacao = session()->codOrganizacao;

        $this->organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);


        $permissao = verificaPermissao('Projetos', 'listar');
        if ($permissao == 0) {
            echo mensagemAcessoNegado(session()->organizacoes);
            $this->LogsModel->inserirLog('Acesso indevido ao Módulo Projetos', session()->codPessoa);
            exit();
        }
    }

    public function index()
    {

        $data = [
            'controller'        => 'projetos',
            'title'             => 'Projetos'
        ];
        echo view('tema/cabecalho');
        echo view('tema/menu_vertical');
        echo view('tema/menu_horizontal');
        return view('projetos', $data);
    }


    public function listaEscopo()
    {
        $response = array();

        $data['data'] = array();
        $codProjeto = $this->request->getPost('codProjeto');

        $result = $this->ProjetosEscopoModel->listaEscopo($codProjeto);
        $x = 0;
        foreach ($result as $key => $value) {
            $x++;
            $ops = '<div class="btn-group">';
            $ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="removeprojetosEscopo(' . $value->codProjetoEscopo . ')"><i class="fa fa-trash"></i></button>';
            $ops .= '</div>';

            $data['data'][$key] = array(
                $x,
                $value->descricaoEscopo,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }

    public function listaNaoEscopo()
    {
        $response = array();

        $data['data'] = array();
        $codProjeto = $this->request->getPost('codProjeto');

        $result = $this->ProjetosEscopoModel->listaNaoEscopo($codProjeto);
        $x = 0;
        foreach ($result as $key => $value) {
            $x++;
            $ops = '<div class="btn-group">';
            $ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="removeprojetosEscopo(' . $value->codProjetoEscopo . ')"><i class="fa fa-trash"></i></button>';
            $ops .= '</div>';

            $data['data'][$key] = array(
                $x,
                $value->descricaoEscopo,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }

    public function getAll()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->ProjetosModel->pega_projetos();

        foreach ($result as $key => $value) {

            $ops = '<div class="btn-group">';
            $ops .= '	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Editar" onclick="edit(' . $value->codProjeto . ')"><i class="fa fa-edit"></i></button>';
            $ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="remove(' . $value->codProjeto . ')"><i class="fa fa-trash"></i></button>';
            $ops .= '</div>';

            $data['data'][$key] = array(
                $value->codProjeto,
                $value->descricaoProjeto,
                $value->descricaoDepartamento,
                $value->nomeGestor,
                $value->nomeSupervisor,
                $value->descricaoStatusProjeto,
                $value->descricaoTipoProjeto,
                date('d/m/Y', strtotime($value->dataInicioProjeto)),
                date('d/m/Y', strtotime($value->dataEncerramentoProjeto)),
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }

    public function getOne()
    {
        $response = array();

        $id = $this->request->getPost('codProjeto');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->ProjetosModel->pega_projetos_por_codProjeto($id);

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function add()
    {

        $response = array();
        $fields['codOrganizacao'] = session()->codOrganizacao;
        $fields['descricaoProjeto'] = $this->request->getPost('descricaoProjeto');
        $fields['codDepartamento'] = $this->request->getPost('codDepartamento');
        $fields['codStatusProjeto'] = $this->request->getPost('codStatusProjeto');
        $fields['codTipoProjeto'] = $this->request->getPost('codTipoProjeto');
        $fields['dataInicioProjeto'] = $this->request->getPost('dataInicioProjeto');
        $fields['dataEncerramentoProjeto'] = $this->request->getPost('dataEncerramentoProjeto');


        $this->validation->setRules([
            'descricaoProjeto' => ['label' => 'Descrição', 'rules' => 'required|max_length[300]'],
            'codDepartamento' => ['label' => 'Departamento', 'rules' => 'required|max_length[11]'],
            'codStatusProjeto' => ['label' => 'Status', 'rules' => 'required|max_length[11]'],
            'codTipoProjeto' => ['label' => 'Tipo Projeto', 'rules' => 'required|max_length[11]'],
            'dataInicioProjeto' => ['label' => 'Data Início Projeto', 'rules' => 'permit_empty|valid_date'],
            'dataEncerramentoProjeto' => ['label' => 'Data Encerramento Projeto', 'rules' => 'permit_empty|valid_date'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->ProjetosModel->insert($fields)) {

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

    public function salvaJustificativa()
    {

        $response = array();
        $fields['codProjeto'] = $this->request->getPost('codProjeto');
        $fields['justificativa'] = $this->request->getPost('justificativa');


        $this->validation->setRules([
            'codProjeto' => ['label' => 'codProjeto', 'rules' => 'required|numeric'],
            'justificativa' => ['label' => 'Justificativa', 'rules' => 'required|bloquearReservado'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->ProjetosModel->update($fields['codProjeto'], $fields)) {

                $response['success'] = true;
                $response['csrf_hash'] = csrf_hash();
                $response['messages'] = 'Justificativa salva com sucesso';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na inserção!';
            }
        }

        return $this->response->setJSON($response);
    }


    public function adicionarMembro()
    {

        $response = array();
        $fields['codProjeto'] = $this->request->getPost('codProjeto');
        $fields['codMembro'] = $this->request->getPost('codMembro');
        $fields['codTipoMembro'] = $this->request->getPost('codTipoMembro');


        $this->validation->setRules([
            'codMembro' => ['label' => 'codMembro', 'rules' => 'required'],
            'codTipoMembro' => ['label' => 'codTipoMembro', 'rules' => 'required'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->ProjetosMembrosModel->insert($fields)) {

                $response['success'] = true;
                $response['csrf_hash'] = csrf_hash();
                $response['messages'] = 'Membro adicionado com sucesso';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na inserção!';
            }
        }

        return $this->response->setJSON($response);
    }



    public function salvaObjetivo()
    {

        $response = array();
        $fields['codProjeto'] = $this->request->getPost('codProjeto');
        $fields['objetivo'] = $this->request->getPost('objetivo');



        $this->validation->setRules([
            'objetivo' => ['label' => 'objetivo', 'rules' => 'required'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->ProjetosModel->update($fields['codProjeto'], $fields)) {

                $response['success'] = true;
                $response['csrf_hash'] = csrf_hash();
                $response['messages'] = 'Objetivo salvo com sucesso';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na inserção!';
            }
        }

        return $this->response->setJSON($response);
    }


    public function salvaBeneficios()
    {

        $response = array();
        $fields['codProjeto'] = $this->request->getPost('codProjeto');
        $fields['beneficios'] = $this->request->getPost('beneficios');



        $this->validation->setRules([
            'beneficios' => ['label' => 'beneficios', 'rules' => 'required'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->ProjetosModel->update($fields['codProjeto'], $fields)) {

                $response['success'] = true;
                $response['csrf_hash'] = csrf_hash();
                $response['messages'] = 'Benefícios salvo com sucesso';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na inserção!';
            }
        }

        return $this->response->setJSON($response);
    }


    public function fasesProjeto()
    {
        $response = array();

        $data['data'] = array();

        $codProjeto = $this->request->getPost('codProjeto');

        $result = $this->ProjetosFaseModel->fasesProjeto($codProjeto);
        $x = 0;
        foreach ($result as $key => $value) {
            $x++;
            $ops = '<div class="btn-group">';
            $ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editprojetosFase(' . $value->codProjetoFase . ')"><i class="fa fa-edit"></i></button>';
            $ops .= '	<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeprojetosFase(' . $value->codProjetoFase . ')"><i class="fa fa-trash"></i></button>';
            $ops .= '</div>';



            $data['csrf_token'] =  csrf_token();
            $data['csrf_hash'] =  csrf_hash();
            $data['data'][$key] = array(
                $x,
                $value->descricaoFase,
                date('d/m/Y', strtotime($value->dataInicial)),
                date('d/m/Y', strtotime($value->dataEncerramento)),
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }


    public function membrosProjeto()
    {
        $response = array();

        $data['data'] = array();

        $codProjeto = $this->request->getPost('codProjeto');

        $result = $this->ProjetosMembrosModel->pegaTudoPorCodProjeto($codProjeto);

        $x = 0;
        foreach ($result as $key => $value) {
            $x++;
            $ops = '<div class="btn-group">';
            $ops .= '	<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeprojetosMembros(' . $value->codProjetoMembro . ')"><i class="fa fa-trash"></i></button>';
            $ops .= '</div>';



            $data['csrf_token'] =  csrf_token();
            $data['csrf_hash'] =  csrf_hash();
            $data['data'][$key] = array(
                $x,
                $value->nomeExibicao,
                $value->descricaoTipoMembro,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }


    public function getOneFase()
    {
        $response = array();

        $id = $this->request->getPost('codProjetoFase');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->ProjetosFaseModel->pegaPorCodigo($id);

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function editFase()
    {

        $response = array();

        $fields['codProjetoFase'] = $this->request->getPost('codProjetoFase');
        $fields['descricaoFase'] = $this->request->getPost('descricaoFase');
        $fields['dataInicial'] = $this->request->getPost('dataInicial');
        $fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');


        $this->validation->setRules([
            'descricaoFase' => ['label' => 'DescricaoFase', 'rules' => 'required|max_length[100]'],
            'dataInicial' => ['label' => 'DataInicial', 'rules' => 'permit_empty'],
            'dataEncerramento' => ['label' => 'DataEncerramento', 'rules' => 'permit_empty'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->ProjetosFaseModel->update($fields['codProjetoFase'], $fields)) {

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

    public function addEscopo()
    {

        $response = array();

        $fields['codProjetoEscopo'] = $this->request->getPost('codProjetoEscopo');
        $fields['codProjeto'] = $this->request->getPost('codProjeto');
        $fields['descricaoEscopo'] = $this->request->getPost('descricaoEscopo');
        $fields['codTipoEscopo'] = $this->request->getPost('codTipoEscopo');


        $this->validation->setRules([
            'codProjeto' => ['label' => 'CodProjeto', 'rules' => 'required|numeric|max_length[11]'],
            'descricaoEscopo' => ['label' => 'Descrição do Escopo', 'rules' => 'required|max_length[150]'],
            'codTipoEscopo' => ['label' => 'Tipo', 'rules' => 'required|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->projetosEscopoModel->insert($fields)) {

                $response['success'] = true;
                $response['csrf_hash'] = csrf_hash();
                $response['codTipoEscopo'] = $fields['codTipoEscopo'];
                $response['messages'] = 'Informação inserida com sucesso';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na inserção!';
            }
        }

        return $this->response->setJSON($response);
    }

    public function addFase()
    {

        $response = array();

        $fields['codProjeto'] = $this->request->getPost('codProjeto');
        $fields['descricaoFase'] = $this->request->getPost('descricaoFase');
        $fields['dataInicial'] = $this->request->getPost('dataInicial');
        $fields['dataEncerramento'] = $this->request->getPost('dataEncerramento');


        $this->validation->setRules([
            'codProjeto' => ['label' => 'CodProjeto', 'rules' => 'required|numeric|max_length[11]'],
            'descricaoFase' => ['label' => 'DescricaoFase', 'rules' => 'required|max_length[100]'],
            'dataInicial' => ['label' => 'DataInicial', 'rules' => 'permit_empty'],
            'dataEncerramento' => ['label' => 'DataEncerramento', 'rules' => 'permit_empty'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->ProjetosFaseModel->insert($fields)) {

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


    public function removeMembro()
    {
        $response = array();

        $id = $this->request->getPost('codProjetoMembro');

        if (!$this->validation->check($id, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($this->ProjetosMembrosModel->where('codProjetoMembro', $id)->delete()) {

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

    public function listaDropDownTipoMembros()
    {

        $result = $this->ProjetosMembrosModel->pegaTipoMembros();

        if ($result !== NULL) {


            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function edit()
    {

        $response = array();

        $fields['codProjeto'] = $this->request->getPost('codProjeto');
        $fields['descricaoProjeto'] = $this->request->getPost('descricaoProjeto');
        $fields['codDepartamento'] = $this->request->getPost('codDepartamento');
        $fields['codStatusProjeto'] = $this->request->getPost('codStatusProjeto');
        $fields['codTipoProjeto'] = $this->request->getPost('codTipoProjeto');
        $fields['dataInicioProjeto'] = $this->request->getPost('dataInicioProjeto');
        $fields['dataEncerramentoProjeto'] = $this->request->getPost('dataEncerramentoProjeto');


        $this->validation->setRules([
            'descricaoProjeto' => ['label' => 'Descrição', 'rules' => 'required|max_length[300]'],
            'codDepartamento' => ['label' => 'Departamento', 'rules' => 'required|max_length[11]'],
            'codStatusProjeto' => ['label' => 'Status', 'rules' => 'required|max_length[11]'],
            'codTipoProjeto' => ['label' => 'Tipo Projeto', 'rules' => 'required|max_length[11]'],
            'dataInicioProjeto' => ['label' => 'Data Início Projeto', 'rules' => 'permit_empty|valid_date'],
            'dataEncerramentoProjeto' => ['label' => 'Data Encerramento Projeto', 'rules' => 'permit_empty|valid_date'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->ProjetosModel->update($fields['codProjeto'], $fields)) {

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

        $id = $this->request->getPost('codProjeto');

        if (!$this->validation->check($id, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($this->ProjetosModel->where('codProjeto', $id)->delete()) {

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
