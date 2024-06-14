<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\OrcamentosModel;
use App\Models\ItensRequisicaoModel;

class Orcamentos extends BaseController
{

    protected $OrcamentosModel;
    protected $pessoasModel;
    protected $OrganizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

    public function __construct()
    {

        helper('seguranca_helper');
        verificaSeguranca($this, session(), base_url());
        $this->OrcamentosModel = new OrcamentosModel();
        $this->OrganizacoesModel = new OrganizacoesModel();
        $this->ItensRequisicaoModel = new ItensRequisicaoModel();
        $this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
        $this->validation = \Config\Services::validation();
        $this->codOrganizacao = session()->codOrganizacao;
        $this->Organizacao = $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
    }



    public function orcamentosItem()
    {
        $response = array();

        $data['data'] = array();



        $codRequisicaoItem = $this->request->getPost('codRequisicaoItem');

        $result = $this->OrcamentosModel->orcamentosItem($codRequisicaoItem);
        $x = 0;

        foreach ($result as $key => $value) {

            $anexo = '<span class="right badge badge-danger">NÃO</span>';
            if ($value->documento !== NULL and $value->documento !== "" and $value->documento !== " ") {
                $anexo = '
                <a style="font-size:20px" href="#" onclick="verOrcamento(' . $value->codOrcamento . ')"><span class="right badge badge-success">SIM</span></a>';
            }

            $x++;
            $ops = '<div class="btn-group">';
            $ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editorcamentos(' . $value->codOrcamento . ')"><i class="fa fa-edit"></i></button>';
            $ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeorcamentos(' . $value->codOrcamento . ')"><i class="fa fa-trash"></i></button>';
            $ops .= '</div>';

            $informacoes = '

                <div class="row">
                ' . $value->descricaoTipoOrcamento . '
                </div>
                <div style="font-size:14px;" class="row">
                Data: ' . date("d/m/Y", strtotime($value->dataOrcamento)) . '
                </div>
                <div style="font-size:14px;" class="row">
                Anexo: ' . $anexo . '
                </div>

                ';

            $data['data'][$key] = array(
                $x,
                $value->razaoSocial,
                $value->valorUnitario,
                $informacoes,

                $ops,
            );
        }

        return $this->response->setJSON($data);
    }

    public function getOne()
    {
        $response = array();

        $id = $this->request->getPost('codOrcamento');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->OrcamentosModel->pegaPorCodigo($id);

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }


    public function verOrcamento()
    {
        $response = array();

        $codOrcamento = $this->request->getPost('codOrcamento');

        if ($this->validation->check($codOrcamento, 'required|numeric')) {

            $data = $this->OrcamentosModel->pegaPorCodigo($codOrcamento);


            $response['success'] = true;
            $response['documento'] = '

			<object data="' . base_url() . '/arquivos/orcamentos/' . $data->documento . '" type="application/pdf" style="width:90vw;height:80vh;">
				<p>Alternative text - include a link <a href="' . base_url() . '/arquivos/orcamentos/' . $data->documento . '">to the PDF!</a></p>
	   		 </object>


			';
            return $this->response->setJSON($response);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function add()
    {

        $response = array();



        $fields['codOrcamento'] = $this->request->getPost('codOrcamento');
        $fields['codFornecedor'] = $this->request->getPost('codFornecedor');
        $fields['valorUnitario'] = brl2decimal($this->request->getPost('valorUnitario'));
        $fields['codTipoOrcamento'] = $this->request->getPost('codTipoOrcamento');
        $fields['codRequisicaoItem'] = $this->request->getPost('codRequisicaoItem');
        $fields['dataOrcamento'] = $this->request->getPost('dataOrcamento');
        $fields['dataCriacao'] = date('Y-m-d H:i');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');


        $this->validation->setRules([
            'codFornecedor' => ['label' => 'codFornecedor', 'rules' => 'required|numeric|max_length[20]'],
            'valorUnitario' => ['label' => 'ValorUnitario', 'rules' => 'required'],
            'codTipoOrcamento' => ['label' => 'Tipo Orcamento', 'rules' => 'required|max_length[11]'],
            'dataOrcamento' => ['label' => 'data Orcamento', 'rules' => 'required|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($codOrcamento = $this->OrcamentosModel->insert($fields)) {



                //RECALCULAR VALOR UNITÁRIO E SUBTOTAL
                $valor = $this->OrcamentosModel->calcularValor($this->request->getPost('codRequisicaoItem'), $this->request->getPost('metodoCalculo'), $fields['valorUnitario']);

                //RECALCULAR VALOR UNITÁRIO E SUBTOTAL
                $this->OrcamentosModel->calcularTotalGeralPorCodItem($this->request->getPost('codRequisicaoItem'));


                $response['valor'] = brl2decimal($valor);
                $response['success'] = true;
                $response['codOrcamento'] = $codOrcamento;
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

        $fields['codOrcamento'] = $this->request->getPost('codOrcamento');
        $fields['codFornecedor'] = $this->request->getPost('codFornecedor');
        $fields['valorUnitario'] = brl2decimal($this->request->getPost('valorUnitario'));
        $fields['codTipoOrcamento'] = $this->request->getPost('codTipoOrcamento');
        $fields['dataOrcamento'] = $this->request->getPost('dataOrcamento');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');



        $this->validation->setRules([
            'codOrcamento' => ['label' => 'codOrcamento', 'rules' => 'required|numeric|max_length[20]'],
            'codFornecedor' => ['label' => 'codFornecedor', 'rules' => 'required|numeric|max_length[20]'],
            'valorUnitario' => ['label' => 'ValorUnitario', 'rules' => 'required'],
            'codTipoOrcamento' => ['label' => 'Tipo Orcamento', 'rules' => 'required|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->OrcamentosModel->update($fields['codOrcamento'], $fields)) {


                //RECALCULAR VALOR UNITÁRIO E SUBTOTAL
                $valor = $this->OrcamentosModel->calcularValor($this->request->getPost('codRequisicaoItem'), $this->request->getPost('metodoCalculo'), $fields['valorUnitario']);

                //RECALCULAR VALOR UNITÁRIO E SUBTOTAL
                $this->OrcamentosModel->calcularTotalGeralPorCodItem($this->request->getPost('codRequisicaoItem'));


                $response['success'] = true;
                $response['valor'] = brl2decimal($valor);
                $response['messages'] = 'Atualizado com sucesso';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Erro na atualização!';
            }
        }

        return $this->response->setJSON($response);
    }


    function enviarArquivo()
    {


        $response = array();


        $codOrcamento = $this->request->getPost('codOrcamento');
        $arquivo = $this->request->getFile('file');
        $nomeArquivo = $codOrcamento . geraNumero(3) . '.' . $arquivo->getClientExtension();



        $fields['codOrcamento'] = $codOrcamento;
        $fields['dataCriacao'] = date('Y-m-d H:i');
        $fields['dataAtualizacao'] = date('Y-m-d H:i');
        $fields['documento'] = $nomeArquivo;
        $fields['codPessoa'] = session()->codPessoa;



        //VERIFICA TAMANHO
        /*
		$getClientMimeType = $arquivo->getClientMimeType();
		$response['success'] = false;
		$response['messages'] = $getClientMimeType;
		return $this->response->setJSON($response);
*/
        if ($arquivo->getSize() > 10000000) {
            $response['success'] = false;
            $response['messages'] = "Desculpe, o arquivo é muito grande. Máximo de 10Mb.";
            return $this->response->setJSON($response);
        }


        //VERIFICA TIPO
        $getClientMimeType = $arquivo->getClientMimeType();
        if (
            $getClientMimeType !== 'application/pdf'
            and $getClientMimeType !== 'image/png'
            and $getClientMimeType !== 'image/jpg'
            and $getClientMimeType !== 'image/jpeg'
            and $getClientMimeType !== 'image/gif'
        ) {
            $response['success'] = false;
            $response['messages'] = "Desculpe, somente arquivos com as extensões à seguir são permitidos: PDF, JPG, JPEG, PNG & GIF";
            return $this->response->setJSON($response);
        }

        if (!$this->validation->check($fields['codOrcamento'], 'required|numeric')) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
            return $this->response->setJSON($response);
        } else {

            if ($this->OrcamentosModel->update($fields['codOrcamento'], $fields)) {
                $arquivo->move(WRITEPATH . '../arquivos/orcamentos/',  $nomeArquivo, true);
            }
        }



        $response['success'] = true;

        return $this->response->setJSON($response);
    }


    public function remove()
    {
        $response = array();

        $id = $this->request->getPost('codOrcamento');

        if (!$this->validation->check($id, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            $data = $this->OrcamentosModel->pegaDadosItem($id);

            if ($this->OrcamentosModel->where('codOrcamento', $id)->delete()) {

                //RECALCULAR VALOR UNITÁRIO E SUBTOTAL

                if ($data !== NULL and $data !== "" and $data !== " ") {
                    $valor = $this->OrcamentosModel->calcularValor($data->codRequisicaoItem, $data->metodoCalculo, $data->valorUnit); // -1 DELETAR

                    //RECALCULAR VALOR UNITÁRIO E SUBTOTAL
                    $this->OrcamentosModel->calcularTotalGeralPorCodItem($data->codRequisicaoItem);
                }

                $response['valor'] = brl2decimal($valor);
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
