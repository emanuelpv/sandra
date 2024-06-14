<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\ProcedimentosModel;

class Procedimentos extends BaseController
{
	
    protected $ProcedimentosModel;
    protected $pessoasModel;
    protected $OrganizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

	public function __construct()
	{
		
		helper('seguranca_helper');
        verificaSeguranca($this, session(),base_url());
	    $this->ProcedimentosModel = new ProcedimentosModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
       	$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
        $this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
		

		
	}
	
	public function index()
	{
		
		$permissao = verificaPermissao('Procedimentos', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "Procedimentos"', session()->codPessoa);
			exit();
		}
		

	    $data = [
                'controller'    	=> 'procedimentos',
                'title'     		=> 'Procedimetos'				
			];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('procedimentos', $data);
			
	}

	public function getAll()
	{
 		$response = array();		
		
	    $data['data'] = array();
 
		$result = $this->ProcedimentosModel->pegaTudo();
		
		foreach ($result as $key => $value) {
							
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editprocedimentos('. $value->codProcedimento .')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeprocedimentos('. $value->codProcedimento .')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';
			
			$data['data'][$key] = array(
				$value->codProcedimento,
				$value->referencia,
				$value->descricao,
				$value->usm,
				$value->valor,

				$ops,
			);
		} 

		return $this->response->setJSON($data);		
	}
	
	public function getOne()
	{
 		$response = array();
		
		$id = $this->request->getPost('codProcedimento');
		
		if ($this->validation->check($id, 'required|numeric')) {
			
			$data = $this->ProcedimentosModel->pegaPorCodigo($id);
			
			return $this->response->setJSON($data);	
				
		} else {
			
			throw new \CodeIgniter\Exceptions\PageNotFoundException();

		}	
		
	}	
	
	public function add()
	{

        $response = array();

        $fields['codProcedimento'] = $this->request->getPost('codProcedimento');
        $fields['referencia'] = $this->request->getPost('referencia');
        $fields['descricao'] = $this->request->getPost('descricao');
        $fields['usm'] = $this->request->getPost('usm');
        $fields['valor'] = $this->request->getPost('valor');


        $this->validation->setRules([
            'descricao' => ['label' => 'Descrição', 'rules' => 'permit_empty|max_length[250]'],
            'usm' => ['label' => 'Usm', 'rules' => 'permit_empty|max_length[5]'],
            'valor' => ['label' => 'Valor', 'rules' => 'permit_empty|numeric'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
			
        } else {

            if ($this->ProcedimentosModel->insert($fields)) {
												
                $response['success'] = true;
                $response['messages'] = 'Informação inserida com sucesso';	
				
            } else {
				
                $response['success'] = false;
                $response['messages'] = 'Erro na inserção!';
				
            }
        }
		
        return $this->response->setJSON($response);
	}


	
	public function listaDropDown()
	{

		$result = $this->ProcedimentosModel->listaDropDown();

		if ($result !== NULL) {

			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function edit()
	{

        $response = array();
		
        $fields['codProcedimento'] = $this->request->getPost('codProcedimento');
        $fields['referencia'] = $this->request->getPost('referencia');
        $fields['descricao'] = $this->request->getPost('descricao');
        $fields['usm'] = $this->request->getPost('usm');
        $fields['valor'] = $this->request->getPost('valor');


        $this->validation->setRules([
            'codProcedimento' => ['label' => 'codProcedimento', 'rules' => 'required|numeric'],
            'descricao' => ['label' => 'Descrição', 'rules' => 'permit_empty|max_length[250]'],
            'usm' => ['label' => 'Usm', 'rules' => 'permit_empty|max_length[5]'],
            'valor' => ['label' => 'Valor', 'rules' => 'permit_empty|numeric'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
			
        } else {

            if ($this->ProcedimentosModel->update($fields['codProcedimento'], $fields)) {
				
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
		
		$id = $this->request->getPost('codProcedimento');
		
		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
			
		} else {	
		
			if ($this->ProcedimentosModel->where('codProcedimento', $id)->delete()) {
								
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