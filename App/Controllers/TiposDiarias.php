<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\TiposDiariasModel;

class TiposDiarias extends BaseController
{
	
    protected $TiposDiariasModel;
    protected $pessoasModel;
    protected $OrganizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

	public function __construct()
	{
		
		helper('seguranca_helper');
        verificaSeguranca($this, session(),base_url());
	    $this->TiposDiariasModel = new TiposDiariasModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
       	$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
        $this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
		

		
	}
	
	public function index()
	{
		
		$permissao = verificaPermissao('TiposDiarias', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "TiposDiarias"', session()->codPessoa);
			exit();
		}
		

	    $data = [
                'controller'    	=> 'tiposDiarias',
                'title'     		=> 'Tipos de Diarias'				
			];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('tiposDiarias', $data);
			
	}

	public function getAll()
	{
 		$response = array();		
		
	    $data['data'] = array();
 
		$result = $this->TiposDiariasModel->pegaTudo();
		
		foreach ($result as $key => $value) {
							
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="edittiposDiarias('. $value->codTipoDiaria .')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removetiposDiarias('. $value->codTipoDiaria .')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';
			
			$data['data'][$key] = array(
				$value->codTipoDiaria,
				$value->codDgp,
				$value->descricao,
				'R$ '.$value->valor,

				$ops,
			);
		} 

		return $this->response->setJSON($data);		
	}
	
	public function getOne()
	{
 		$response = array();
		
		$id = $this->request->getPost('codTipoDiaria');
		
		if ($this->validation->check($id, 'required|numeric')) {
			
			$data = $this->TiposDiariasModel->pegaPorCodigo($id);
			
			return $this->response->setJSON($data);	
				
		} else {
			
			throw new \CodeIgniter\Exceptions\PageNotFoundException();

		}	
		
	}	
	
	public function add()
	{

        $response = array();

        $fields['codTipoDiaria'] = $this->request->getPost('codTipoDiaria');
        $fields['codDgp'] = $this->request->getPost('codDgp');
        $fields['descricao'] = $this->request->getPost('descricao');
        $fields['valor'] = $this->request->getPost('valor');


        $this->validation->setRules([
            'codDgp' => ['label' => 'Cdódigo DGP', 'rules' => 'required|max_length[8]'],
            'descricao' => ['label' => 'Descrição', 'rules' => 'required|max_length[50]'],
            'valor' => ['label' => 'Valor', 'rules' => 'required|numeric'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
			
        } else {

            if ($this->TiposDiariasModel->insert($fields)) {
												
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
		
        $fields['codTipoDiaria'] = $this->request->getPost('codTipoDiaria');
        $fields['codDgp'] = $this->request->getPost('codDgp');
        $fields['descricao'] = $this->request->getPost('descricao');
        $fields['valor'] = $this->request->getPost('valor');
		

        $this->validation->setRules([
            'codDgp' => ['label' => 'Cdódigo DGP', 'rules' => 'required|max_length[8]'],
            'descricao' => ['label' => 'Descrição', 'rules' => 'required|max_length[50]'],
            'valor' => ['label' => 'Valor', 'rules' => 'required|numeric'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
			
        } else {

            if ($this->TiposDiariasModel->update($fields['codTipoDiaria'], $fields)) {
				
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
		
		$id = $this->request->getPost('codTipoDiaria');
		
		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
			
		} else {	
		
			if ($this->TiposDiariasModel->where('codTipoDiaria', $id)->delete()) {
								
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