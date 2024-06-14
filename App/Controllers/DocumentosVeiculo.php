<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\DocumentosVeiculoModel;

class DocumentosVeiculo extends BaseController
{
	
    protected $DocumentosVeiculoModel;
    protected $pessoasModel;
    protected $OrganizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

	public function __construct()
	{
		
		helper('seguranca_helper');
        verificaSeguranca($this, session(),base_url());
	    $this->DocumentosVeiculoModel = new DocumentosVeiculoModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
       	$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
        $this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
		

		
	}
	
	public function index()
	{
		
		$permissao = verificaPermissao('DocumentosVeiculo', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "DocumentosVeiculo"', session()->codPessoa);
			exit();
		}
		

	    $data = [
                'controller'    	=> 'documentosVeiculo',
                'title'     		=> 'Documentos do Veículo'				
			];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('documentosVeiculo', $data);
			
	}


	
	public function getOne()
	{
 		$response = array();
		
		$id = $this->request->getPost('codDocumento');
		
		if ($this->validation->check($id, 'required|numeric')) {
			
			$data = $this->DocumentosVeiculoModel->pegaPorCodigo($id);
			
			return $this->response->setJSON($data);	
				
		} else {
			
			throw new \CodeIgniter\Exceptions\PageNotFoundException();

		}	
		
	}	
	
	public function add()
	{

        $response = array();

        $fields['codDocumento'] = $this->request->getPost('codDocumento');
        $fields['codVeiculo'] = $this->request->getPost('codVeiculo');
        $fields['dataCriacao'] = $this->request->getPost('dataCriacao');
        $fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');
        $fields['documento'] = $this->request->getPost('documento');
        $fields['codAutor'] = $this->request->getPost('codAutor');


        $this->validation->setRules([
            'codVeiculo' => ['label' => 'CodVeiculo', 'rules' => 'required|numeric|max_length[11]'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
            'documento' => ['label' => 'Documento', 'rules' => 'required|max_length[100]'],
            'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
			
        } else {

            if ($this->DocumentosVeiculoModel->insert($fields)) {
												
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
		
        $fields['codDocumento'] = $this->request->getPost('codDocumento');
        $fields['codVeiculo'] = $this->request->getPost('codVeiculo');
        $fields['dataCriacao'] = $this->request->getPost('dataCriacao');
        $fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');
        $fields['documento'] = $this->request->getPost('documento');
        $fields['codAutor'] = $this->request->getPost('codAutor');


        $this->validation->setRules([
            'codDocumento' => ['label' => 'codDocumento', 'rules' => 'required|numeric|max_length[11]'],
			'codVeiculo' => ['label' => 'CodVeiculo', 'rules' => 'required|numeric|max_length[11]'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
            'documento' => ['label' => 'Documento', 'rules' => 'required|max_length[100]'],
            'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
			
        } else {

            if ($this->DocumentosVeiculoModel->update($fields['codDocumento'], $fields)) {
				
                $response['success'] = true;
                $response['messages'] = 'Atualizado com sucesso';	
				
            } else {
				
                $response['success'] = false;
                $response['messages'] = 'Erro na atualização!';	
				
            }
        }
		
        return $this->response->setJSON($response);
		
	}
	

		
}	