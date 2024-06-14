<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\DocumentoRequisicaoModel;

class DocumentoRequisicao extends BaseController
{
	
    protected $DocumentoRequisicaoModel;
    protected $pessoasModel;
    protected $OrganizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

	public function __construct()
	{
		
		helper('seguranca_helper');
        verificaSeguranca($this, session(),base_url());
	    $this->DocumentoRequisicaoModel = new DocumentoRequisicaoModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
       	$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
        $this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
		

		
	}
	
	public function index()
	{
		
		$permissao = verificaPermissao('DocumentoRequisicao', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "DocumentoRequisicao"', session()->codPessoa);
			exit();
		}
		

	    $data = [
                'controller'    	=> 'documentoRequisicao',
                'title'     		=> 'Documento da Requisição'				
			];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('documentoRequisicao', $data);
			
	}

	public function getAll()
	{
 		$response = array();		
		
	    $data['data'] = array();
 
		$result = $this->DocumentoRequisicaoModel->pegaTudo();
		
		foreach ($result as $key => $value) {
							
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editdocumentoRequisicao('. $value->codDocumentoRequisicao .')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removedocumentoRequisicao('. $value->codDocumentoRequisicao .')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';
			
			$data['data'][$key] = array(
				$value->codDocumentoRequisicao,
				$value->codDocumento,
				$value->codRequisicao,

				$ops,
			);
		} 

		return $this->response->setJSON($data);		
	}
	
	public function getOne()
	{
 		$response = array();
		
		$id = $this->request->getPost('codDocumentoRequisicao');
		
		if ($this->validation->check($id, 'required|numeric')) {
			
			$data = $this->DocumentoRequisicaoModel->pegaPorCodigo($id);
			
			return $this->response->setJSON($data);	
				
		} else {
			
			throw new \CodeIgniter\Exceptions\PageNotFoundException();

		}	
		
	}	
	
	public function add()
	{

        $response = array();

        $fields['codDocumentoRequisicao'] = $this->request->getPost('codDocumentoRequisicao');
        $fields['codDocumento'] = $this->request->getPost('codDocumento');
        $fields['codRequisicao'] = $this->request->getPost('codRequisicao');


        $this->validation->setRules([
            'codDocumento' => ['label' => 'CodDocumento', 'rules' => 'required|numeric|max_length[11]'],
            'codRequisicao' => ['label' => 'CodRequisicao', 'rules' => 'required|numeric|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
			
        } else {

            if ($this->DocumentoRequisicaoModel->insert($fields)) {
												
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
		
        $fields['codDocumentoRequisicao'] = $this->request->getPost('codDocumentoRequisicao');
        $fields['codDocumento'] = $this->request->getPost('codDocumento');
        $fields['codRequisicao'] = $this->request->getPost('codRequisicao');


        $this->validation->setRules([
            'codDocumentoRequisicao' => ['label' => 'codDocumentoRequisicao', 'rules' => 'required|numeric|max_length[11]'],
			'codDocumento' => ['label' => 'CodDocumento', 'rules' => 'required|numeric|max_length[11]'],
            'codRequisicao' => ['label' => 'CodRequisicao', 'rules' => 'required|numeric|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
			
        } else {

            if ($this->DocumentoRequisicaoModel->update($fields['codDocumentoRequisicao'], $fields)) {
				
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
		
		$id = $this->request->getPost('codDocumentoRequisicao');
		
		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
			
		} else {	
		
			if ($this->DocumentoRequisicaoModel->where('codDocumentoRequisicao', $id)->delete()) {
								
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