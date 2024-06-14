<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;
use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;
use App\Models\ItensFarmaciaModel;
use App\Models\DepartamentosModel;
use App\Models\FaturamentoMedicamentosModel;

class FaturamentoMedicamentos extends BaseController
{

    protected $FaturamentoMedicamentosModel;
    protected $pessoasModel;
    protected $OrganizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

    public function __construct()
    {

        helper('seguranca_helper');
        verificaSeguranca($this, session(), base_url());
        $this->FaturamentoMedicamentosModel = new FaturamentoMedicamentosModel();
        $this->OrganizacoesModel = new OrganizacoesModel();
        $this->ItensFarmaciaModel = new ItensFarmaciaModel();
        $this->DepartamentosModel = new DepartamentosModel();
        $this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
        $this->validation =  \Config\Services::validation();
        $this->codOrganizacao = session()->codOrganizacao;
        $this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
    }

    public function index()
    {

        $permissao = verificaPermissao('FaturamentoMedicamentos', 'listar');
        if ($permissao == 0) {
            echo mensagemAcessoNegado(session()->organizacoes);
            $this->LogsModel->inserirLog('Acesso indevido ao Módulo "FaturamentoMedicamentos"', session()->codPessoa);
            exit();
        }


        $data = [
            'controller'        => 'faturamentoMedicamentos',
            'title'             => 'Faturamento de Medicamentos'
        ];
        echo view('tema/cabecalho');
        echo view('tema/menu_vertical');
        echo view('tema/menu_horizontal');
        return view('faturamentoMedicamentos', $data);
    }

    public function getAll()
    {
        $response = array();

        $data['data'] = array();

        $result = $this->FaturamentoMedicamentosModel->pegaTudo();

        foreach ($result as $key => $value) {

            $ops = '<div class="btn-group">';
            $ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editfaturamentoMedicamentos(' . $value->codFaturamentoMedicamento . ')"><i class="fa fa-edit"></i></button>';
            $ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removefaturamentoMedicamentos(' . $value->codFaturamentoMedicamento . ')"><i class="fa fa-trash"></i></button>';
            $ops .= '</div>';

            $data['data'][$key] = array(
                $value->codFaturamentoMedicamento,
                $value->codAtendimento,
                $value->codPrescricaoMedicamento,
                $value->autorPrescricao,
                $value->dataPrescricao,
                $value->codMedicamento,
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
    public function medicamentosFaturados()
    {
        $response = array();

        $data['data'] = array();


        $codFatura = $this->request->getPost('codFatura');

        $result = $this->FaturamentoMedicamentosModel->medicamentosFaturados($codFatura);

        $x = 0;
        foreach ($result as $key => $value) {
            $x++;
            $ops = '<div class="btn-group">';
            if ($value->codStatusFatura == 0) {
                $ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editfaturamentoMedicamentos(' . $value->codFaturamentoMedicamento . ')"><i class="fa fa-edit"></i></button>';
                $ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removefaturamentoMedicamentos(' . $value->codFaturamentoMedicamento . ')"><i class="fa fa-trash"></i></button>';
            }
            $ops .= '</div>';


            $descricaoStatus = '<span class="right badge badge-' . $value->corStatusPrescricao . '">' . $value->descricaoStatusPrescricao . '</span>';

            $statusFaturado = "";
            $statusFaturadoeAuditado = "";
            $statusLancadoSire = "";

            if ($value->codStatus == 8) {
                $statusFaturado = "selected";
            }
            if ($value->codStatus == 9) {
                $statusFaturadoeAuditado = "selected";
            }
            if ($value->codStatus == 10) {
                $statusLancadoSire = "selected";
            }

            $checkbox = '<input style="display: none;margin-right:10px;" class="glosarMedicamento" name="glosarMedicamento' . $value->codFaturamentoMedicamento . '" type="checkbox" >';


            $data['data'][$key] = array(
                $checkbox . $x,
                $value->descricaoDepartamento . ' (' . $value->descricaoLocalAtendimento . ')',
                $value->nee,
                $value->descricaoItem,
                '<input style="width:50px; display: none" class="editar" id="editarQtde' . $value->codFaturamentoMedicamento . '" name="editarQtde' . $value->codFaturamentoMedicamento . '" value="' . $value->quantidade . '">' . $value->quantidade,
                round($value->valor, 2),
                '<span class="ver" >R$ ' . round($value->subTotal, 2) . '</span>',
                date('d/m/Y', strtotime($value->dataPrescricao)),

                $descricaoStatus = '<span class="right badge badge-' . $value->corStatusPrescricao . '">' . $value->descricaoStatusPrescricao . '</span>',
                '<input style="width:150px; display: none" class="editar" id="editarObservacoes' . $value->codFaturamentoMedicamento . '" name="editarObservacoes' . $value->codFaturamentoMedicamento . '" value="' . $value->observacoes . '"><span class="ver">' . $value->observacoes . '</span>',
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }
    public function getOne()
    {
        $response = array();

        $id = $this->request->getPost('codFaturamentoMedicamento');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->FaturamentoMedicamentosModel->pegaPorCodigo($id);

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function add()
    {

        $response = array();

        $fields['codFaturamentoMedicamento'] = $this->request->getPost('codFaturamentoMedicamento');
        $fields['codAtendimento'] = $this->request->getPost('codAtendimento');
        $fields['codPrescricaoMedicamento'] = $this->request->getPost('codPrescricaoMedicamento');
        $fields['autorPrescricao'] = $this->request->getPost('autorPrescricao');
        $fields['dataPrescricao'] = $this->request->getPost('dataPrescricao');
        $fields['codMedicamento'] = $this->request->getPost('codMedicamento');
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
            'codPrescricaoMedicamento' => ['label' => 'CodPrescricaoMedicamento', 'rules' => 'required|numeric|max_length[11]'],
            'autorPrescricao' => ['label' => 'AutorPrescricao', 'rules' => 'required|numeric|max_length[11]'],
            'dataPrescricao' => ['label' => 'DataPrescricao', 'rules' => 'permit_empty'],
            'codMedicamento' => ['label' => 'CodMedicamento', 'rules' => 'required|numeric|max_length[11]'],
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

            if ($this->FaturamentoMedicamentosModel->insert($fields)) {

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


        $item  = $this->ItensFarmaciaModel->pegaPorCodigo($this->request->getPost('codMedicamento'));

        /*
        $local = $this->DepartamentosModel->salaDepartamento(session()->codDepartamento)->codLocalAtendimento;

        if ($local !== NULL and $local !== "" and $local !== 0) {
            $local = $local;
        } else {
            $local = 0;
        }
        */

        if ($this->request->getPost('dataPrescricao') !== NULL) {
            $dataPrescricao = $this->request->getPost('dataPrescricao');
        } else {
            $dataPrescricao = date('Y-m-d H:i');
        }

        $fields['codFatura'] = $this->request->getPost('codFatura');
        $fields['codAtendimento'] = $this->request->getPost('codAtendimento');
        $fields['codPrescricaoMedicamento'] = 0;
        $fields['autorPrescricao'] = 0;
        $fields['dataPrescricao'] = $dataPrescricao;
        $fields['codMedicamento'] = $this->request->getPost('codMedicamento');
        $fields['quantidade'] = $this->request->getPost('quantidade');
        $fields['valor'] = $item->valor;
        $fields['codLocalAtendimento'] =  $this->request->getPost('codLocalAtendimento');
        $fields['dataCriacao'] = date('Y-m-d H:i');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['codStatus'] = 1;
        $fields['codAutor'] = session()->codPessoa;
        $fields['observacoes'] = $this->request->getPost('observacoes');


        $this->validation->setRules([
            'codFatura' => ['label' => 'codFatura', 'rules' => 'required|numeric|max_length[11]'],
            'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'codPrescricaoMedicamento' => ['label' => 'CodPrescricaoMedicamento', 'rules' => 'required|numeric|max_length[11]'],
            'autorPrescricao' => ['label' => 'AutorPrescricao', 'rules' => 'required|numeric|max_length[11]'],
            'dataPrescricao' => ['label' => 'DataPrescricao', 'rules' => 'permit_empty'],
            'codMedicamento' => ['label' => 'CodMedicamento', 'rules' => 'required|numeric|max_length[11]'],
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

            if ($this->FaturamentoMedicamentosModel->insert($fields)) {

                $response['success'] = true;
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

        $fields['codFaturamentoMedicamento'] = $this->request->getPost('codFaturamentoMedicamento');
        $fields['codAtendimento'] = $this->request->getPost('codAtendimento');
        $fields['codPrescricaoMedicamento'] = $this->request->getPost('codPrescricaoMedicamento');
        $fields['autorPrescricao'] = $this->request->getPost('autorPrescricao');
        $fields['dataPrescricao'] = $this->request->getPost('dataPrescricao');
        $fields['codMedicamento'] = $this->request->getPost('codMedicamento');
        $fields['quantidade'] = $this->request->getPost('quantidade');
        $fields['valor'] = $this->request->getPost('valor');
        $fields['codLocalAtendimento'] = $this->request->getPost('codLocalAtendimento');
        $fields['dataCriacao'] = $this->request->getPost('dataCriacao');
        $fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');
        $fields['codStatus'] = $this->request->getPost('codStatus');
        $fields['codAutor'] = $this->request->getPost('codAutor');
        $fields['observacoes'] = $this->request->getPost('observacoes');


        $this->validation->setRules([
            'codFaturamentoMedicamento' => ['label' => 'codFaturamentoMedicamento', 'rules' => 'required|numeric|max_length[11]'],
            'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'codPrescricaoMedicamento' => ['label' => 'CodPrescricaoMedicamento', 'rules' => 'required|numeric|max_length[11]'],
            'autorPrescricao' => ['label' => 'AutorPrescricao', 'rules' => 'required|numeric|max_length[11]'],
            'dataPrescricao' => ['label' => 'DataPrescricao', 'rules' => 'permit_empty'],
            'codMedicamento' => ['label' => 'CodMedicamento', 'rules' => 'required|numeric|max_length[11]'],
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

            if ($this->FaturamentoMedicamentosModel->update($fields['codFaturamentoMedicamento'], $fields)) {

                $response['success'] = true;
                $response['messages'] = 'Atualizado com sucesso';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na atualização!';
            }
        }

        return $this->response->setJSON($response);
    }




    public function editMedicamento()
    {

        $response = array();

        $fields['codFaturamentoMedicamento'] = $this->request->getPost('codFaturamentoMedicamento');
        $fields['quantidade'] = $this->request->getPost('quantidade');
        $fields['observacoes'] = $this->request->getPost('observacoes');
        $fields['codStatus'] = $this->request->getPost('codStatus');


        $this->validation->setRules([
            'codFaturamentoMedicamento' => ['label' => 'codFaturamentoMedicamento', 'rules' => 'required|numeric|max_length[11]'],
            'quantidade' => ['label' => 'Quantidade', 'rules' => 'required|numeric|max_length[11]'],
            'observacoes' => ['label' => 'Observacoes', 'rules' => 'permit_empty'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->FaturamentoMedicamentosModel->update($fields['codFaturamentoMedicamento'], $fields)) {

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

        $id = $this->request->getPost('codFaturamentoMedicamento');

        if (!$this->validation->check($id, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($this->FaturamentoMedicamentosModel->where('codFaturamentoMedicamento', $id)->delete()) {

                $response['success'] = true;
                $response['messages'] = 'Deletado com sucesso';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na deleção!';
            }
        }

        return $this->response->setJSON($response);
    }



    public function glosar()
    {

        $response = array();

        $fields['codFaturamentoMedicamento'] = $this->request->getPost('codFaturamentoMedicamento');
        $fields['quantidade'] = 0;
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['codStatus'] = -1; //GLOSADO
        $fields['codAutor'] = session()->codPessoa;
        $fields['observacoes'] = $this->request->getPost('observacoes');

        $this->validation->setRules([
            'codFaturamentoMedicamento' => ['label' => 'codFaturamentoMedicamento', 'rules' => 'required|numeric|max_length[11]'],
            'observacoes' => ['label' => 'Observacoes', 'rules' => 'permit_empty'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->FaturamentoMedicamentosModel->update($fields['codFaturamentoMedicamento'], $fields)) {

                $response['success'] = true;
                $response['messages'] = 'Glosado com sucesso';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na atualização!';
            }
        }

        return $this->response->setJSON($response);
    }




    public function listaStatusFaturamentoMedicamentos()
    {

        $result = $this->FaturamentoMedicamentosModel->listaStatusFaturamentoMedicamentos();

        if ($result !== NULL) {

            return $this->response->setJSON($result);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function glosarMedicamentosEmLoteAgora()
    {

        $response = array();



        foreach ($this->request->getPost() as $chave => $atributo) {
            $fields = array();

            if (strpos($chave,  'glosarMedicamento') !== false) {

                $codFaturamentoMedicamento = str_replace('glosarMedicamento', '', $chave);
                $fields['codFaturamentoMedicamento'] = $codFaturamentoMedicamento;
                $fields['quantidade'] = 0;
                $fields['dataAtualizacao'] = date('Y-m-d H:i');
                $fields['codStatus'] = -1; //GLOSADO
                $fields['codAutor'] = session()->codPessoa;
                $fields['observacoes'] = $this->request->getPost('motivoEmLote');

                $this->validation->setRules([
                    'codFaturamentoMedicamento' => ['label' => 'codFaturamentoMedicamento', 'rules' => 'required|numeric|max_length[11]'],
                    'observacoes' => ['label' => 'Observacoes', 'rules' => 'permit_empty'],

                ]);



                if ($this->validation->run($fields) == FALSE) {
                } else {

                    if ($this->FaturamentoMedicamentosModel->update($fields['codFaturamentoMedicamento'], $fields)) {
                    }
                }
            }
        }




        $response['success'] = true;
        $response['messages'] = 'Glosa em lote realizada com sucesso';

        return $this->response->setJSON($response);
    }
}
