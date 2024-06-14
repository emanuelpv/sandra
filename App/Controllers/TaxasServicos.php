<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\TaxasServicosModel;

class TaxasServicos extends BaseController
{
	
    protected $TaxasServicosModel;
    protected $pessoasModel;
    protected $OrganizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

	public function __construct()
	{
		
		helper('seguranca_helper');
        verificaSeguranca($this, session(),base_url());
	    $this->TaxasServicosModel = new TaxasServicosModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
       	$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
        $this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
		

		
	}
	
	public function index()
	{
		
		$permissao = verificaPermissao('TaxasServicos', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "TaxasServicos"', session()->codPessoa);
			exit();
		}
		

	    $data = [
                'controller'    	=> 'taxasServicos',
                'title'     		=> 'Taxas e Serviços'				
			];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('taxasServicos', $data);
			
	}

	public function getAll()
	{
 		$response = array();		
		
	    $data['data'] = array();
 
		$result = $this->TaxasServicosModel->pegaTudo();
		
		foreach ($result as $key => $value) {
							
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="edittaxasServicos('. $value->codTaxaServico .')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removetaxasServicos('. $value->codTaxaServico .')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';
			
			$data['data'][$key] = array(
				$value->codTaxaServico,
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
		
		$id = $this->request->getPost('codTaxaServico');
		
		if ($this->validation->check($id, 'required|numeric')) {
			
			$data = $this->TaxasServicosModel->pegaPorCodigo($id);
			
			return $this->response->setJSON($data);	
				
		} else {
			
			throw new \CodeIgniter\Exceptions\PageNotFoundException();

		}	
		
	}	
	
	public function add()
	{

        $response = array();

        $fields['codTaxaServico'] = $this->request->getPost('codTaxaServico');
        $fields['descricao'] = $this->request->getPost('descricao');
        $fields['usm'] = $this->request->getPost('usm');
        $fields['valor'] = $this->request->getPost('valor');


        $this->validation->setRules([
            'referencia' => ['label' => 'Referência', 'rules' => 'required|max_length[10]'],
            'descricao' => ['label' => 'Descrição', 'rules' => 'required|max_length[235]'],
            'usm' => ['label' => 'Usm', 'rules' => 'required|numeric|max_length[5]'],
            'valor' => ['label' => 'Valor', 'rules' => 'permit_empty'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
			
        } else {

            if ($this->TaxasServicosModel->insert($fields)) {
												
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
		
        $fields['codTaxaServico'] = $this->request->getPost('codTaxaServico');
        $fields['descricao'] = $this->request->getPost('descricao');
        $fields['usm'] = $this->request->getPost('usm');
        $fields['valor'] = $this->request->getPost('valor');


        $this->validation->setRules([
            'codTaxaServico' => ['label' => 'codTaxaServico', 'rules' => 'required|numeric|max_length[11]'],
            'referencia' => ['label' => 'Referência', 'rules' => 'required|max_length[10]'],
            'descricao' => ['label' => 'Descrição', 'rules' => 'required|max_length[235]'],
            'usm' => ['label' => 'Usm', 'rules' => 'required|numeric|max_length[5]'],
            'valor' => ['label' => 'Valor', 'rules' => 'permit_empty'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
			
        } else {

            if ($this->TaxasServicosModel->update($fields['codTaxaServico'], $fields)) {
				
                $response['success'] = true;
                $response['messages'] = 'Atualizado com sucesso';	
				
            } else {
				
                $response['success'] = false;
                $response['messages'] = 'Erro na atualização!';	
				
            }
        }
		
        return $this->response->setJSON($response);
		
	}
	


	public function listaDropDown()
	{

		$result = $this->TaxasServicosModel->listaDropDown();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function remove()
	{
		$response = array();
		
		$id = $this->request->getPost('codTaxaServico');
		
		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
			
		} else {	
		
			if ($this->TaxasServicosModel->where('codTaxaServico', $id)->delete()) {
								
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