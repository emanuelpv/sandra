<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\DocumentosModel;

class Documentos extends BaseController
{
	
    protected $DocumentosModel;
    protected $pessoasModel;
    protected $OrganizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

	public function __construct()
	{
		
		helper('seguranca_helper');
        verificaSeguranca($this, session(),base_url());
	    $this->DocumentosModel = new DocumentosModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
       	$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
        $this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
		

		
	}
	
	public function index()
	{
		
		$permissao = verificaPermissao('Documentos', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "Documentos"', session()->codPessoa);
			exit();
		}
		

	    $data = [
                'controller'    	=> 'documentos',
                'title'     		=> 'documentos'				
			];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('documentos', $data);
			
	}

	public function getAll()
	{
 		$response = array();		
		
	    $data['data'] = array();
 
		$result = $this->DocumentosModel->pegaTudo();
		
		foreach ($result as $key => $value) {
							
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editdocumentos('. $value->codDocumento .')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removedocumentos('. $value->codDocumento .')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';
			
			$data['data'][$key] = array(
				$value->codDocumento,
				$value->assunto,
				$value->conteudo,
				$value->codDestinatario,
				$value->codRemetente,
				$value->dataCriacao,
				$value->dataAtualizacao,
				$value->codAutor,
				$value->codTipoDocumento,
				$value->codStatus,

				$ops,
			);
		} 

		return $this->response->setJSON($data);		
	}
		public function porRequisicaoCompra()
	{
 		$response = array();		
		
	    $data['data'] = array();
 
		$codRequisicao = $this->request->getPost('codRequisicao');

		$result = $this->DocumentosModel->porRequisicaoCompra($codRequisicao);
		
		foreach ($result as $key => $value) {
							
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editdocumentos('. $value->codDocumento .')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removedocumentos('. $value->codDocumento .')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';
			
			$data['data'][$key] = array(
				$value->codDocumento,
				$value->assunto,
				$value->codDestinatario,
				$value->codRemetente,
				$value->dataCriacao,
				$value->dataAtualizacao,
				$value->codAutor,
				$value->codTipoDocumento,
				$value->codStatus,

				$ops,
			);
		} 

		return $this->response->setJSON($data);		
	}
	
	public function getOne()
	{
 		$response = array();
		
		$id = $this->request->getPost('codDocumento');
		
		if ($this->validation->check($id, 'required|numeric')) {
			
			$data = $this->DocumentosModel->pegaPorCodigo($id);
			
			return $this->response->setJSON($data);	
				
		} else {
			
			throw new \CodeIgniter\Exceptions\PageNotFoundException();

		}	
		
	}	
	
	public function add()
	{

        $response = array();

        $fields['codDocumento'] = $this->request->getPost('codDocumento');
        $fields['assunto'] = $this->request->getPost('assunto');
        $fields['conteudo'] = $this->request->getPost('conteudo');
        $fields['codDestinatario'] = $this->request->getPost('codDestinatario');
        $fields['codRemetente'] = $this->request->getPost('codRemetente');
        $fields['dataCriacao'] = $this->request->getPost('dataCriacao');
        $fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');
        $fields['codAutor'] = $this->request->getPost('codAutor');
        $fields['codTipoDocumento'] = $this->request->getPost('codTipoDocumento');
        $fields['codStatus'] = $this->request->getPost('codStatus');


        $this->validation->setRules([
            'assunto' => ['label' => 'Assunto', 'rules' => 'required|max_length[200]'],
            'conteudo' => ['label' => 'Conteúdo', 'rules' => 'required'],
            'codDestinatario' => ['label' => 'Destinatário', 'rules' => 'required|max_length[11]'],
            'codRemetente' => ['label' => 'Remetente', 'rules' => 'required|max_length[11]'],
            'dataCriacao' => ['label' => 'Data Criação', 'rules' => 'required'],
            'dataAtualizacao' => ['label' => 'Data Atualização', 'rules' => 'required'],
            'codAutor' => ['label' => 'codAutor', 'rules' => 'required|numeric|max_length[11]'],
            'codTipoDocumento' => ['label' => 'CodTipoDocumento', 'rules' => 'required|max_length[11]'],
            'codStatus' => ['label' => 'CodStatus', 'rules' => 'required|numeric|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
			
        } else {

            if ($this->DocumentosModel->insert($fields)) {
												
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
        $fields['assunto'] = $this->request->getPost('assunto');
        $fields['conteudo'] = $this->request->getPost('conteudo');
        $fields['codDestinatario'] = $this->request->getPost('codDestinatario');
        $fields['codRemetente'] = $this->request->getPost('codRemetente');
        $fields['dataCriacao'] = $this->request->getPost('dataCriacao');
        $fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');
        $fields['codAutor'] = $this->request->getPost('codAutor');
        $fields['codTipoDocumento'] = $this->request->getPost('codTipoDocumento');
        $fields['codStatus'] = $this->request->getPost('codStatus');


        $this->validation->setRules([
            'codDocumento' => ['label' => 'codDocumento', 'rules' => 'required|numeric|max_length[11]'],
			'assunto' => ['label' => 'Assunto', 'rules' => 'required|max_length[200]'],
            'conteudo' => ['label' => 'Conteúdo', 'rules' => 'required'],
            'codDestinatario' => ['label' => 'Destinatário', 'rules' => 'required|max_length[11]'],
            'codRemetente' => ['label' => 'Remetente', 'rules' => 'required|max_length[11]'],
            'dataCriacao' => ['label' => 'Data Criação', 'rules' => 'required'],
            'dataAtualizacao' => ['label' => 'Data Atualização', 'rules' => 'required'],
            'codAutor' => ['label' => 'codAutor', 'rules' => 'required|numeric|max_length[11]'],
            'codTipoDocumento' => ['label' => 'CodTipoDocumento', 'rules' => 'required|max_length[11]'],
            'codStatus' => ['label' => 'CodStatus', 'rules' => 'required|numeric|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
			
        } else {

            if ($this->DocumentosModel->update($fields['codDocumento'], $fields)) {
				
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
		
		$id = $this->request->getPost('codDocumento');
		
		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
			
		} else {	
		
			if ($this->DocumentosModel->where('codDocumento', $id)->delete()) {
								
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