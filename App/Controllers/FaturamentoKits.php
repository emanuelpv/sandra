<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\FaturamentoKitsModel;

class FaturamentoKits extends BaseController
{

    protected $FaturamentoKitsModel;
    protected $pessoasModel;
    protected $OrganizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

    public function __construct()
    {

        helper('seguranca_helper');
        verificaSeguranca($this, session(), base_url());
        $this->FaturamentoKitsModel = new FaturamentoKitsModel();
        $this->OrganizacoesModel = new OrganizacoesModel();
        $this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
        $this->validation =  \Config\Services::validation();
        $this->codOrganizacao = session()->codOrganizacao;
        $this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
    }

    public function index()
    {

        $permissao = verificaPermissao('FaturamentoKits', 'listar');
        if ($permissao == 0) {
            echo mensagemAcessoNegado(session()->organizacoes);
            $this->LogsModel->inserirLog('Acesso indevido ao Módulo "FaturamentoKits"', session()->codPessoa);
            exit();
        }


        $data = [
            'controller'        => 'faturamentoKits',
            'title'             => 'Faturamento de Kits'
        ];
        echo view('tema/cabecalho');
        echo view('tema/menu_vertical');
        echo view('tema/menu_horizontal');
        return view('faturamentoKits', $data);
    }

    public function getAll()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->FaturamentoKitsModel->pegaTudo();

        foreach ($result as $key => $value) {

            $ops = '<div class="btn-group">';
            $ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editfaturamentoKits(' . $value->codFaturamentoKit . ')"><i class="fa fa-edit"></i></button>';
            $ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removefaturamentoKits(' . $value->codFaturamentoKit . ')"><i class="fa fa-trash"></i></button>';
            $ops .= '</div>';

            $data['data'][$key] = array(
                $value->codFaturamentoKit,
                $value->codAtendimento,
                $value->codPrescricaoKit,
                $value->autorPrescricao,
                $value->dataPrescricao,
                $value->codKit,
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


    public function kitsFatura()
    {
        $response = array();

        $data['data'] = array();

        $codFatura = $this->request->getPost('codFatura');

        $result = $this->FaturamentoKitsModel->kitsFatura($codFatura);
        $x = 0;
        foreach ($result as $key => $value) {
            $x++;
            $ops = '<div class="btn-group">';
            if ($value->codStatusFatura == 0) {
                $ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editfaturamentoKits(' . $value->codFaturamentoKit . ')"><i class="fa fa-edit"></i></button>';
                $ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removefaturamentoKits(' . $value->codFaturamentoKit . ')"><i class="fa fa-trash"></i></button>';
            }
            $ops .= '</div>';

            $descricaoStatus = '<span class="right badge badge-' . $value->corStatusKit . '">' . $value->descricaoStatusKit . '</span>';


            $checkbox = '<input style="display: none;margin-right:10px;" class="glosarKits" name="glosarKits' . $value->codFaturamentoKit . '" type="checkbox" >';

            $data['data'][$key] = array(
                $checkbox . $x,
                $value->descricaoDepartamento . ' (' . $value->descricaoLocalAtendimento . ')',
                $value->nee,
                $value->descricaoKit . '<div style="font-size:10px;color:red">' . $value->observacoes . '</div>',
                '<input style="width:50px;display:none" class="editarKits" id="editarKitsQtde' . $value->codFaturamentoKit . '" name="editarQtde' . $value->codFaturamentoKit . '" value="' . $value->quantidade . '">' . $value->quantidade . '',
                round($value->valor, 2),
                '<span class="ver" >R$ ' . round($value->subTotal, 2) . '</span>',
                date('d/m/Y', strtotime($value->dataPrescricao)),
                $value->nomeExibicao,
                $descricaoStatus,

                $ops,
            );
        }

        return $this->response->setJSON($data);
    }



    public function glosarKitsEmLoteAgora()
    {

        $response = array();




        foreach ($this->request->getPost() as $chave => $atributo) {
            $fields = array();

            if (strpos($chave,  'glosarKits') !== false) {

                $codFaturamentoKit = str_replace('glosarKits', '', $chave);
                $fields['codFaturamentoKit'] = $codFaturamentoKit;
                $fields['quantidade'] = 0;
                $fields['dataAtualizacao'] = date('Y-m-d H:i');
                $fields['codStatus'] = -1; //GLOSADO
                $fields['codAutor'] = session()->codPessoa;
                $fields['observacoes'] = $this->request->getPost('motivoEmLote');

                $this->validation->setRules([
                    'codFaturamentoKit' => ['label' => 'codFaturamentoKit', 'rules' => 'required|numeric|max_length[11]'],
                    'observacoes' => ['label' => 'Observacoes', 'rules' => 'permit_empty'],

                ]);


              

                if ($this->validation->run($fields) == FALSE) {


                    $response['success'] = false;
                    $response['messages'] = 'Falhou';

                    return $this->response->setJSON($response);
                } else {

                    if ($this->FaturamentoKitsModel->update($fields['codFaturamentoKit'], $fields)) {
                    }
                }
            }
        }




        $response['success'] = true;
        $response['messages'] = 'Glosa em lote realizada com sucesso';

        return $this->response->setJSON($response);
    }




    public function getOne()
    {
        $response = array();

        $id = $this->request->getPost('codFaturamentoKit');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->FaturamentoKitsModel->pegaPorCodigo($id);

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function add()
    {

        $response = array();

        $fields['codFaturamentoKit'] = $this->request->getPost('codFaturamentoKit');
        $fields['codAtendimento'] = $this->request->getPost('codAtendimento');
        $fields['codPrescricaoKit'] = $this->request->getPost('codPrescricaoKit');
        $fields['autorPrescricao'] = $this->request->getPost('autorPrescricao');
        $fields['dataPrescricao'] = $this->request->getPost('dataPrescricao');
        $fields['codKit'] = $this->request->getPost('codKit');
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
            'codPrescricaoKit' => ['label' => 'CodPrescricaoKit', 'rules' => 'required|numeric|max_length[11]'],
            'autorPrescricao' => ['label' => 'AutorPrescricao', 'rules' => 'required|numeric|max_length[11]'],
            'dataPrescricao' => ['label' => 'DataPrescricao', 'rules' => 'permit_empty'],
            'codKit' => ['label' => 'CodKit', 'rules' => 'required|numeric|max_length[11]'],
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

            if ($this->FaturamentoKitsModel->insert($fields)) {

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

        $item  = $this->FaturamentoKitsModel->pegaKit($this->request->getPost('codKit'));

        if ($item !== NULL) {
            $valor =  $item->valorun;
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
        $fields['codPrescricaoKit'] = 0;
        $fields['autorPrescricao'] = 0;
        $fields['dataPrescricao'] = $dataPrescricao;
        $fields['codKit'] = $this->request->getPost('codKit');
        $fields['quantidade'] = $this->request->getPost('quantidade');
        $fields['valor'] = $valor;
        $fields['codLocalAtendimento'] = $this->request->getPost('codLocalAtendimento');
        $fields['observacoes'] = $this->request->getPost('observacoes');
        $fields['codStatus'] = 1;
        $fields['codAutor'] = session()->codPessoa;
        $fields['dataCriacao'] = date('Y-m-d H:i');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');


        $this->validation->setRules([
            'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'codPrescricaoKit' => ['label' => 'CodPrescricaoKit', 'rules' => 'required|numeric|max_length[11]'],
            'autorPrescricao' => ['label' => 'AutorPrescricao', 'rules' => 'required|numeric|max_length[11]'],
            'dataPrescricao' => ['label' => 'DataPrescricao', 'rules' => 'permit_empty'],
            'codKit' => ['label' => 'CodKit', 'rules' => 'required|numeric|max_length[11]'],
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

            if ($this->FaturamentoKitsModel->insert($fields)) {

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

        $item  = $this->FaturamentoKitsModel->pegaKit($this->request->getPost('codKit'));

        if ($item !== NULL) {
            $valor =  $item->valorun;
        } else {
            $valor = 0;
        }

        $fields['codFaturamentoKit'] = $this->request->getPost('codFaturamentoKit');
        $fields['codKit'] = $this->request->getPost('codKit');
        $fields['quantidade'] = $this->request->getPost('quantidade');
        $fields['valor'] = $valor;
        $fields['observacoes'] = $this->request->getPost('observacoes');
        $fields['codAutor'] = session()->codPessoa;
        $fields['codLocalAtendimento'] = $this->request->getPost('codLocalAtendimento');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');



        $this->validation->setRules([
            'codFaturamentoKit' => ['label' => 'codFaturamentoKit', 'rules' => 'required|numeric|max_length[11]'],
            'codKit' => ['label' => 'CodTipoKit', 'rules' => 'required|numeric|max_length[11]'],
            'quantidade' => ['label' => 'Quantidade', 'rules' => 'required|numeric|max_length[11]'],
            'valor' => ['label' => 'Valor', 'rules' => 'required'],
            'codLocalAtendimento' => ['label' => 'CodLocalAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->FaturamentoKitsModel->update($fields['codFaturamentoKit'], $fields)) {

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

        $fields['codFaturamentoKit'] = $this->request->getPost('codFaturamentoKit');
        $fields['codAtendimento'] = $this->request->getPost('codAtendimento');
        $fields['codPrescricaoKit'] = $this->request->getPost('codPrescricaoKit');
        $fields['autorPrescricao'] = $this->request->getPost('autorPrescricao');
        $fields['dataPrescricao'] = $this->request->getPost('dataPrescricao');
        $fields['codKit'] = $this->request->getPost('codKit');
        $fields['quantidade'] = $this->request->getPost('quantidade');
        $fields['valor'] = $this->request->getPost('valor');
        $fields['codLocalAtendimento'] = $this->request->getPost('codLocalAtendimento');
        $fields['dataCriacao'] = $this->request->getPost('dataCriacao');
        $fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');
        $fields['codStatus'] = $this->request->getPost('codStatus');
        $fields['codAutor'] = $this->request->getPost('codAutor');
        $fields['observacoes'] = $this->request->getPost('observacoes');


        $this->validation->setRules([
            'codFaturamentoKit' => ['label' => 'codFaturamentoKit', 'rules' => 'required|numeric|max_length[11]'],
            'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'codPrescricaoKit' => ['label' => 'CodPrescricaoKit', 'rules' => 'required|numeric|max_length[11]'],
            'autorPrescricao' => ['label' => 'AutorPrescricao', 'rules' => 'required|numeric|max_length[11]'],
            'dataPrescricao' => ['label' => 'DataPrescricao', 'rules' => 'permit_empty'],
            'codKit' => ['label' => 'CodKit', 'rules' => 'required|numeric|max_length[11]'],
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

            if ($this->FaturamentoKitsModel->update($fields['codFaturamentoKit'], $fields)) {

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

        $id = $this->request->getPost('codFaturamentoKit');

        if (!$this->validation->check($id, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($this->FaturamentoKitsModel->where('codFaturamentoKit', $id)->delete()) {

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
