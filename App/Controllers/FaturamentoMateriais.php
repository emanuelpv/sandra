<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\FaturamentoMateriaisModel;

class FaturamentoMateriais extends BaseController
{

	protected $FaturamentoMateriaisModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->FaturamentoMateriaisModel = new FaturamentoMateriaisModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('FaturamentoMateriais', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "FaturamentoMateriais"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'faturamentoMateriais',
			'title'     		=> 'Faturamento de Materiais'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('faturamentoMateriais', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->FaturamentoMateriaisModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editfaturamentoMateriais(' . $value->codFaturamentoMaterial . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removefaturamentoMateriais(' . $value->codFaturamentoMaterial . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$value->codFaturamentoMaterial,
				$value->codAtendimento,
				$value->codMaterial,
				$value->quantidade,
				$value->valor,
				$value->codLocalAtendimento,
				$value->dataCriacao,
				$value->dataAtualizacao,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}
	public function materiaisFatura()
	{
		$response = array();

		$data['data'] = array();

		$codFatura = $this->request->getPost('codFatura');

		$result = $this->FaturamentoMateriaisModel->materiaisFatura($codFatura);

	
		$x = 0;
		foreach ($result as $key => $value) {
			$x++;
			$ops = '<div class="btn-group">';
			if ($value->codStatusFatura == 0) {
				$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editfaturamentoMateriais(' . $value->codFaturamentoMaterial . ')"><i class="fa fa-edit"></i></button>';
				$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removefaturamentoMateriais(' . $value->codFaturamentoMaterial . ')"><i class="fa fa-trash"></i></button>';
			}
			$ops .= '</div>';

			$descricaoStatus = '<span class="right badge badge-' . $value->corStatusMaterial . '">' . $value->descricaoStatusMaterial . '</span>';


			$checkbox = '<input style="display: none;margin-right:10px;" class="glosarMateriais" name="glosarMateriais' . $value->codFaturamentoMaterial . '" type="checkbox" >';

			$data['data'][$key] = array(
				$checkbox . $x,
				$value->descricaoDepartamento . ' (' . $value->descricaoLocalAtendimento . ')',
				$value->nee,
				$value->descricaoMaterial . '<div style="font-size:10px;color:red">' . $value->observacoes . '</div>',
				'<input style="width:50px;display:none" class="editarMateriais" id="editarMateriaisQtde' . $value->codFaturamentoMaterial . '" name="editarQtde' . $value->codFaturamentoMaterial . '" value="' . $value->quantidade . '">' . $value->quantidade,
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


	public function glosarMateriaisEmLoteAgora()
    {

        $response = array();



        foreach ($this->request->getPost() as $chave => $atributo) {
            $fields = array();

            if (strpos($chave,  'glosarMateriais') !== false) {

                $codFaturamentoMaterial = str_replace('glosarMateriais', '', $chave);
                $fields['codFaturamentoMaterial'] = $codFaturamentoMaterial;
                $fields['quantidade'] = 0;
                $fields['dataAtualizacao'] = date('Y-m-d H:i');
                $fields['codStatus'] = -1; //GLOSADO
                $fields['codAutor'] = session()->codPessoa;
                $fields['observacoes'] = $this->request->getPost('motivoEmLote');

                $this->validation->setRules([
                    'codFaturamentoMaterial' => ['label' => 'codFaturamentoMaterial', 'rules' => 'required|numeric|max_length[11]'],
                    'observacoes' => ['label' => 'Observacoes', 'rules' => 'permit_empty'],

                ]);



                if ($this->validation->run($fields) == FALSE) {
                } else {

                    if ($this->FaturamentoMateriaisModel->update($fields['codFaturamentoMaterial'], $fields)) {
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

		$id = $this->request->getPost('codFaturamentoMaterial');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->FaturamentoMateriaisModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['codFaturamentoMaterial'] = $this->request->getPost('codFaturamentoMaterial');
		$fields['codAtendimento'] = $this->request->getPost('codAtendimento');
		$fields['codPrescricaoMaterial'] = $this->request->getPost('codPrescricaoMaterial');
		$fields['codMaterial'] = $this->request->getPost('codMaterial');
		$fields['quantidade'] = $this->request->getPost('quantidade');
		$fields['valor'] = $this->request->getPost('valor');
		$fields['codLocalAtendimento'] = $this->request->getPost('codLocalAtendimento');
		$fields['dataCriacao'] = $this->request->getPost('dataCriacao');
		$fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');


		$this->validation->setRules([
			'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'codMaterial' => ['label' => 'CodTipoMaterial', 'rules' => 'required|numeric|max_length[11]'],
			'quantidade' => ['label' => 'Quantidade', 'rules' => 'required|numeric|max_length[11]'],
			'valor' => ['label' => 'Valor', 'rules' => 'required'],
			'codLocalAtendimento' => ['label' => 'CodLocalAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->FaturamentoMateriaisModel->insert($fields)) {

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

		$item  = $this->FaturamentoMateriaisModel->pegaMaterial($this->request->getPost('codMaterial'));


        if ($this->request->getPost('dataPrescricao') !== NULL) {
            $dataPrescricao = $this->request->getPost('dataPrescricao');
        } else {
            $dataPrescricao = date('Y-m-d H:i');
        }


		$fields['codFaturamentoMaterial'] = $this->request->getPost('codFaturamentoMaterial');
		$fields['codAtendimento'] = $this->request->getPost('codAtendimento');
		$fields['codFatura'] = $this->request->getPost('codFatura');
		$fields['codPrescricaoMaterial'] = 0;
		$fields['dataPrescricao'] = $dataPrescricao;
		$fields['codMaterial'] = $this->request->getPost('codMaterial');
		$fields['quantidade'] = $this->request->getPost('quantidade');
		$fields['valor'] = $item->valor;
		$fields['observacoes'] = $this->request->getPost('observacoes');
		$fields['codStatus'] = 1;
		$fields['codAutor'] = session()->codPessoa;
		$fields['codLocalAtendimento'] = $this->request->getPost('codLocalAtendimento');
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');



		$this->validation->setRules([
			'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'codMaterial' => ['label' => 'CodTipoMaterial', 'rules' => 'required|numeric|max_length[11]'],
			'quantidade' => ['label' => 'Quantidade', 'rules' => 'required|numeric|max_length[11]'],
			'valor' => ['label' => 'Valor', 'rules' => 'required'],
			'codLocalAtendimento' => ['label' => 'CodLocalAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->FaturamentoMateriaisModel->insert($fields)) {

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

		$item  = $this->FaturamentoMateriaisModel->pegaMaterial($this->request->getPost('codMaterial'));

		if ($item !== NULL) {
			$valor =  $item->valor;
		} else {
			$valor = 0;
		}

		$fields['codFaturamentoMaterial'] = $this->request->getPost('codFaturamentoMaterial');
		$fields['codMaterial'] = $this->request->getPost('codMaterial');
		$fields['quantidade'] = $this->request->getPost('quantidade');
		$fields['valor'] = $valor;
		$fields['observacoes'] = $this->request->getPost('observacoes');
		$fields['codAutor'] = session()->codPessoa;
		$fields['codLocalAtendimento'] = $this->request->getPost('codLocalAtendimento');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');



		$this->validation->setRules([
			'codFaturamentoMaterial' => ['label' => 'codFaturamentoMaterial', 'rules' => 'required|numeric|max_length[11]'],
			'codMaterial' => ['label' => 'CodTipoMaterial', 'rules' => 'required|numeric|max_length[11]'],
			'quantidade' => ['label' => 'Quantidade', 'rules' => 'required|numeric|max_length[11]'],
			'valor' => ['label' => 'Valor', 'rules' => 'required'],
			'codLocalAtendimento' => ['label' => 'CodLocalAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->FaturamentoMateriaisModel->update($fields['codFaturamentoMaterial'], $fields)) {

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

		$fields['codFaturamentoMaterial'] = $this->request->getPost('codFaturamentoMaterial');
		$fields['codAtendimento'] = $this->request->getPost('codAtendimento');
		$fields['codPrescricaoMaterial'] = $this->request->getPost('codPrescricaoMaterial');
		$fields['codMaterial'] = $this->request->getPost('codMaterial');
		$fields['quantidade'] = $this->request->getPost('quantidade');
		$fields['valor'] = $this->request->getPost('valor');
		$fields['codLocalAtendimento'] = $this->request->getPost('codLocalAtendimento');
		$fields['dataCriacao'] = $this->request->getPost('dataCriacao');
		$fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');


		$this->validation->setRules([
			'codFaturamentoMaterial' => ['label' => 'codFaturamentoMaterial', 'rules' => 'required|numeric|max_length[11]'],
			'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'codMaterial' => ['label' => 'CodTipoMaterial', 'rules' => 'required|numeric|max_length[11]'],
			'quantidade' => ['label' => 'Quantidade', 'rules' => 'required|numeric|max_length[11]'],
			'valor' => ['label' => 'Valor', 'rules' => 'required'],
			'codLocalAtendimento' => ['label' => 'CodLocalAtendimento', 'rules' => 'required|numeric|max_length[11]'],
			'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
			'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->FaturamentoMateriaisModel->update($fields['codFaturamentoMaterial'], $fields)) {

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

		$id = $this->request->getPost('codFaturamentoMaterial');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->FaturamentoMateriaisModel->where('codFaturamentoMaterial', $id)->delete()) {

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
