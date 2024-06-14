<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\FaturamentoProcedimentosModel;

class FaturamentoProcedimentos extends BaseController
{

    protected $FaturamentoProcedimentosModel;
    protected $pessoasModel;
    protected $OrganizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

    public function __construct()
    {

        helper('seguranca_helper');
        verificaSeguranca($this, session(), base_url());
        $this->FaturamentoProcedimentosModel = new FaturamentoProcedimentosModel();
        $this->OrganizacoesModel = new OrganizacoesModel();
        $this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
        $this->validation =  \Config\Services::validation();
        $this->codOrganizacao = session()->codOrganizacao;
        $this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
    }

    public function index()
    {

        $permissao = verificaPermissao('FaturamentoProcedimentos', 'listar');
        if ($permissao == 0) {
            echo mensagemAcessoNegado(session()->organizacoes);
            $this->LogsModel->inserirLog('Acesso indevido ao Módulo "FaturamentoProcedimentos"', session()->codPessoa);
            exit();
        }


        $data = [
            'controller'        => 'faturamentoProcedimentos',
            'title'             => 'Faturamento de Procedimentos'
        ];
        echo view('tema/cabecalho');
        echo view('tema/menu_vertical');
        echo view('tema/menu_horizontal');
        return view('faturamentoProcedimentos', $data);
    }

    public function getAll()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->FaturamentoProcedimentosModel->pegaTudo();

        foreach ($result as $key => $value) {

            $ops = '<div class="btn-group">';
            $ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editfaturamentoProcedimentos(' . $value->codFaturamentoProcedimento . ')"><i class="fa fa-edit"></i></button>';
            $ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removefaturamentoProcedimentos(' . $value->codFaturamentoProcedimento . ')"><i class="fa fa-trash"></i></button>';
            $ops .= '</div>';

            $data['data'][$key] = array(
                $value->codFaturamentoProcedimento,
                $value->codAtendimento,
                $value->codPrescricaoProcedimento,
                $value->autorPrescricao,
                $value->dataPrescricao,
                $value->codProcedimento,
                $value->quantidade,
                $value->valor,
                $value->codLocalAtendimento,
                $value->dataCriacao,
                $value->dataAtualizacao,
                $value->codStatus,
                $value->codAutor,
                $value->observacoes,

                $ops,
            );
        }

        return $this->response->setJSON($data);
    }
    public function procedimentosFatura()
    {
        $response = array();

        $data['data'] = array();
        $codFatura = $this->request->getPost('codFatura');

        $result = $this->FaturamentoProcedimentosModel->procedimentosFatura($codFatura);
        $x = 0;
        foreach ($result as $key => $value) {
            $x++;
            $ops = '<div class="btn-group">';
            if ($value->codStatusFatura == 0) {
                $ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editfaturamentoProcedimentos(' . $value->codFaturamentoProcedimento . ')"><i class="fa fa-edit"></i></button>';
                $ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removefaturamentoProcedimentos(' . $value->codFaturamentoProcedimento . ')"><i class="fa fa-trash"></i></button>';
            }
            $ops .= '</div>';


            $descricaoStatus = '<span class="right badge badge-' . $value->corStatusProcedimento . '">' . $value->descricaoStatusProcedimento . '</span>';



            $data['data'][$key] = array(
                $x,
                $value->descricaoDepartamento . ' (' . $value->descricaoLocalAtendimento . ')',
                $value->referencia,
                $value->descricaoProcedimento . '<div style="font-size:10px;color:red">' . $value->observacoes . '</div>',
                '<input style="width:50px;display:none" class="editarProcedimentos" id="editarProcedimentosQtde' . $value->codFaturamentoProcedimento . '" name="editarQtde' . $value->codFaturamentoProcedimento . '" value="' . $value->quantidade . '"><span class="verProcedimentos">' . $value->quantidade . '</span>',
                round($value->valor,2),
                '<span class="ver" >R$ ' . round($value->subTotal,2) . '</span>',
                date('d/m/Y', strtotime($value->dataPrescricao)),
                $value->nomeExibicao,
                $descricaoStatus,

                $ops,
            );
        }

        return $this->response->setJSON($data);
    }
    public function getOne()
    {
        $response = array();

        $id = $this->request->getPost('codFaturamentoProcedimento');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->FaturamentoProcedimentosModel->pegaPorCodigo($id);

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function add()
    {

        $response = array();

        $fields['codFaturamentoProcedimento'] = $this->request->getPost('codFaturamentoProcedimento');
        $fields['codAtendimento'] = $this->request->getPost('codAtendimento');
        $fields['codPrescricaoProcedimento'] = $this->request->getPost('codPrescricaoProcedimento');
        $fields['autorPrescricao'] = $this->request->getPost('autorPrescricao');
        $fields['dataPrescricao'] = $this->request->getPost('dataPrescricao');
        $fields['codProcedimento'] = $this->request->getPost('codProcedimento');
        $fields['quantidade'] = $this->request->getPost('quantidade');
        $fields['valor'] = $this->request->getPost('valor');
        $fields['codLocalAtendimento'] = $this->request->getPost('codLocalAtendimento');
        $fields['dataCriacao'] = $this->request->getPost('dataCriacao');
        $fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');
        $fields['codStatus'] = $this->request->getPost('codStatus');
        $fields['codAutor'] = $this->request->getPost('codAutor');
        $fields['observacoes'] = $this->request->getPost('observacoes');


        $this->validation->setRules([
            'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'codPrescricaoProcedimento' => ['label' => 'CodPrescricaoProcedimento', 'rules' => 'required|numeric|max_length[11]'],
            'autorPrescricao' => ['label' => 'AutorPrescricao', 'rules' => 'required|numeric|max_length[11]'],
            'dataPrescricao' => ['label' => 'DataPrescricao', 'rules' => 'permit_empty'],
            'codProcedimento' => ['label' => 'CodProcedimento', 'rules' => 'required|numeric|max_length[11]'],
            'quantidade' => ['label' => 'Quantidade', 'rules' => 'required|numeric|max_length[11]'],
            'valor' => ['label' => 'Valor', 'rules' => 'required'],
            'codLocalAtendimento' => ['label' => 'CodLocalAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
            'codStatus' => ['label' => 'CodStatus', 'rules' => 'required|numeric|max_length[11]'],
            'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
            'observacoes' => ['label' => 'Observacoes', 'rules' => 'permit_empty'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->FaturamentoProcedimentosModel->insert($fields)) {

                $response['success'] = true;
                $response['messages'] = 'Informação inserida com sucesso';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na inserção!';
            }
        }

        return $this->response->setJSON($response);
    }
    public function addIndividual()
    {

        $response = array();


        $item  = $this->FaturamentoProcedimentosModel->pegaProcedimento($this->request->getPost('codProcedimento'));
        if ($item !== NULL) {
            $valor =  $item->valor;;
        } else {
            $valor = 0;
        }

        if ($this->request->getPost('dataPrescricao') !== NULL) {
            $dataPrescricao = $this->request->getPost('dataPrescricao');
        } else {
            $dataPrescricao = date('Y-m-d H:i');
        }


        $fields['codAtendimento'] = $this->request->getPost('codAtendimento');
        $fields['codFatura'] = $this->request->getPost('codFatura');
        $fields['codPrescricaoProcedimento'] = 0;
        $fields['autorPrescricao'] = 0;
        $fields['dataPrescricao'] = $dataPrescricao;
        $fields['codProcedimento'] = $this->request->getPost('codProcedimento');
        $fields['quantidade'] = $this->request->getPost('quantidade');
        $fields['valor'] = $valor;
        $fields['codLocalAtendimento'] = $this->request->getPost('codLocalAtendimento');
        $fields['dataCriacao'] = date('Y-m-d H:i');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['codStatus'] = 1;
        $fields['codAutor'] = session()->codPessoa;
        $fields['observacoes'] = $this->request->getPost('observacoes');


        $this->validation->setRules([
            'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'codPrescricaoProcedimento' => ['label' => 'CodPrescricaoProcedimento', 'rules' => 'required|numeric|max_length[11]'],
            'autorPrescricao' => ['label' => 'AutorPrescricao', 'rules' => 'required|numeric|max_length[11]'],
            'dataPrescricao' => ['label' => 'DataPrescricao', 'rules' => 'permit_empty'],
            'codProcedimento' => ['label' => 'CodProcedimento', 'rules' => 'required|numeric|max_length[11]'],
            'quantidade' => ['label' => 'Quantidade', 'rules' => 'required|numeric|max_length[11]'],
            'valor' => ['label' => 'Valor', 'rules' => 'required'],
            'codLocalAtendimento' => ['label' => 'CodLocalAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
            'codStatus' => ['label' => 'CodStatus', 'rules' => 'required|numeric|max_length[11]'],
            'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
            'observacoes' => ['label' => 'Observacoes', 'rules' => 'permit_empty'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->FaturamentoProcedimentosModel->insert($fields)) {

                $response['success'] = true;
                $response['messages'] = 'Informação inserida com sucesso';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na inserção!';
            }
        }

        return $this->response->setJSON($response);
    }
    public function editIndividual()
    {

        $response = array();


        $item  = $this->FaturamentoProcedimentosModel->pegaProcedimento($this->request->getPost('codProcedimento'));
        if ($item !== NULL) {
            $valor =  $item->valor;;
        } else {
            $valor = 0;
        }


        $fields['codFaturamentoProcedimento'] = $this->request->getPost('codFaturamentoProcedimento');
        $fields['codProcedimento'] = $this->request->getPost('codProcedimento');
        $fields['quantidade'] = $this->request->getPost('quantidade');
        $fields['valor'] = $valor;
        $fields['codLocalAtendimento'] = $this->request->getPost('codLocalAtendimento');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['codAutor'] = session()->codPessoa;
        $fields['observacoes'] = $this->request->getPost('observacoes');


        $this->validation->setRules([
            'codFaturamentoProcedimento' => ['label' => 'codFaturamentoProcedimento', 'rules' => 'required|numeric|max_length[11]'],
            'codProcedimento' => ['label' => 'CodProcedimento', 'rules' => 'required|numeric|max_length[11]'],
            'quantidade' => ['label' => 'Quantidade', 'rules' => 'required|numeric|max_length[11]'],
            'valor' => ['label' => 'Valor', 'rules' => 'required'],
            'codLocalAtendimento' => ['label' => 'CodLocalAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
            'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
            'observacoes' => ['label' => 'Observacoes', 'rules' => 'permit_empty'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->FaturamentoProcedimentosModel->update($fields['codFaturamentoProcedimento'], $fields)) {

                $response['success'] = true;
                $response['messages'] = 'Informação atualizada com sucesso';
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

        $fields['codFaturamentoProcedimento'] = $this->request->getPost('codFaturamentoProcedimento');
        $fields['codAtendimento'] = $this->request->getPost('codAtendimento');
        $fields['codPrescricaoProcedimento'] = $this->request->getPost('codPrescricaoProcedimento');
        $fields['autorPrescricao'] = $this->request->getPost('autorPrescricao');
        $fields['dataPrescricao'] = $this->request->getPost('dataPrescricao');
        $fields['codProcedimento'] = $this->request->getPost('codProcedimento');
        $fields['quantidade'] = $this->request->getPost('quantidade');
        $fields['valor'] = $this->request->getPost('valor');
        $fields['codLocalAtendimento'] = $this->request->getPost('codLocalAtendimento');
        $fields['dataCriacao'] = $this->request->getPost('dataCriacao');
        $fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');
        $fields['codStatus'] = $this->request->getPost('codStatus');
        $fields['codAutor'] = $this->request->getPost('codAutor');
        $fields['observacoes'] = $this->request->getPost('observacoes');


        $this->validation->setRules([
            'codFaturamentoProcedimento' => ['label' => 'codFaturamentoProcedimento', 'rules' => 'required|numeric|max_length[11]'],
            'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'codPrescricaoProcedimento' => ['label' => 'CodPrescricaoProcedimento', 'rules' => 'required|numeric|max_length[11]'],
            'autorPrescricao' => ['label' => 'AutorPrescricao', 'rules' => 'required|numeric|max_length[11]'],
            'dataPrescricao' => ['label' => 'DataPrescricao', 'rules' => 'permit_empty'],
            'codProcedimento' => ['label' => 'CodProcedimento', 'rules' => 'required|numeric|max_length[11]'],
            'quantidade' => ['label' => 'Quantidade', 'rules' => 'required|numeric|max_length[11]'],
            'valor' => ['label' => 'Valor', 'rules' => 'required'],
            'codLocalAtendimento' => ['label' => 'CodLocalAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
            'codStatus' => ['label' => 'CodStatus', 'rules' => 'required|numeric|max_length[11]'],
            'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
            'observacoes' => ['label' => 'Observacoes', 'rules' => 'permit_empty'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->FaturamentoProcedimentosModel->update($fields['codFaturamentoProcedimento'], $fields)) {

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

        $id = $this->request->getPost('codFaturamentoProcedimento');

        if (!$this->validation->check($id, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($this->FaturamentoProcedimentosModel->where('codFaturamentoProcedimento', $id)->delete()) {

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
