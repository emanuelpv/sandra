<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\HistoricoAcoesModel;

class HistoricoAcoes extends BaseController
{
	
    protected $HistoricoAcoesModel;
    protected $pessoasModel;
    protected $OrganizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

	public function __construct()
	{
		
		helper('seguranca_helper');
        verificaSeguranca($this, session(),base_url());
	    $this->HistoricoAcoesModel = new HistoricoAcoesModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
       	$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
        $this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
		

		
	}
	
	public function index()
	{
		
		$permissao = verificaPermissao('HistoricoAcoes', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "HistoricoAcoes"', session()->codPessoa);
			exit();
		}
		

	    $data = [
                'controller'    	=> 'historicoAcoes',
                'title'     		=> 'Histórico de ações'				
			];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('historicoAcoes', $data);
			
	}

	public function getAll()
	{
 		$response = array();		
		
	    $data['data'] = array();
 
		$result = $this->HistoricoAcoesModel->pegaTudo();
		
		foreach ($result as $key => $value) {
							
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="edithistoricoAcoes('. $value->codHistoricoAcao .')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removehistoricoAcoes('. $value->codHistoricoAcao .')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';
			
			$data['data'][$key] = array(
				$value->codHistoricoAcao,
				$value->codTipoAcao,
				$value->descricaoAcao,
				$value->codAutor,
				$value->dataCriacao,
				$value->recurso,
				$value->codRequisicao,
				$ops,
			);
		} 

		return $this->response->setJSON($data);		
	}
	
	public function getOne()
	{
 		$response = array();
		
		$id = $this->request->getPost('codHistoricoAcao');
		
		if ($this->validation->check($id, 'required|numeric')) {
			
			$data = $this->HistoricoAcoesModel->pegaPorCodigo($id);
			
			return $this->response->setJSON($data);	
				
		} else {
			
			throw new \CodeIgniter\Exceptions\PageNotFoundException();

		}	
		
	}	
	
	public function add()
	{

        $response = array();

        $fields['codHistoricoAcao'] = $this->request->getPost('codHistoricoAcao');
        $fields['codTipoAcao'] = $this->request->getPost('codTipoAcao');
        $fields['descricaoAcao'] = $this->request->getPost('descricaoAcao');
        $fields['codAutor'] = session()->codPessoa;
        $fields['dataCriacao'] = date('Y-m-d H:i');
        $fields['recurso'] = $this->request->getPost('recurso');
        $fields['codRequisicao'] = $this->request->getPost('codRequisicao');


        $this->validation->setRules([
            'codTipoAcao' => ['label' => 'CodTipoAcao', 'rules' => 'required|max_length[11]'],
            'descricaoAcao' => ['label' => 'DescricaoAcao', 'rules' => 'required'],
            'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
            'codRequisicao' => ['label' => 'CodRequisicao', 'rules' => 'required|numeric|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
			
        } else {

            if ($this->HistoricoAcoesModel->insert($fields)) {
												
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
		
        $fields['codHistoricoAcao'] = $this->request->getPost('codHistoricoAcao');
        $fields['codTipoAcao'] = $this->request->getPost('codTipoAcao');
        $fields['descricaoAcao'] = $this->request->getPost('descricaoAcao');
        $fields['codAutor'] = $this->request->getPost('codAutor');
        $fields['dataCriacao'] = $this->request->getPost('dataCriacao');
        $fields['recurso'] = $this->request->getPost('recurso');
        $fields['codRequisicao'] = $this->request->getPost('codRequisicao');


        $this->validation->setRules([
            'codHistoricoAcao' => ['label' => 'codHistoricoAcao', 'rules' => 'required|numeric'],
            'codTipoAcao' => ['label' => 'CodTipoAcao', 'rules' => 'required|max_length[11]'],
            'descricaoAcao' => ['label' => 'DescricaoAcao', 'rules' => 'required'],
            'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
            'recurso' => ['label' => 'Recurso', 'rules' => 'required|max_length[100]'],
            'codRequisicao' => ['label' => 'CodRequisicao', 'rules' => 'required|numeric|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
			
        } else {

            if ($this->HistoricoAcoesModel->update($fields['codHistoricoAcao'], $fields)) {
				
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
		
		$id = $this->request->getPost('codHistoricoAcao');
		
		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
			
		} else {	
		
			if ($this->HistoricoAcoesModel->where('codHistoricoAcao', $id)->delete()) {
								
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