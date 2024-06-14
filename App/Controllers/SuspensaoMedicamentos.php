<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\SuspensaoMedicamentosModel;

class SuspensaoMedicamentos extends BaseController
{
	
    protected $SuspensaoMedicamentosModel;
    protected $pessoasModel;
    protected $OrganizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

	public function __construct()
	{
		
		helper('seguranca_helper');
        verificaSeguranca($this, session(),base_url());
	    $this->SuspensaoMedicamentosModel = new SuspensaoMedicamentosModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
       	$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
        $this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
		

		
	}
	
	public function index()
	{
		
		$permissao = verificaPermissao('SuspensaoMedicamentos', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "SuspensaoMedicamentos"', session()->codPessoa);
			exit();
		}
		

	    $data = [
                'controller'    	=> 'suspensaoMedicamentos',
                'title'     		=> 'Suspensao de Medicamentos'				
			];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('suspensaoMedicamentos', $data);
			
	}

	public function getAll()
	{
 		$response = array();		
		
	    $data['data'] = array();
 
		$result = $this->SuspensaoMedicamentosModel->pegaTudo();
		
		foreach ($result as $key => $value) {
							
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editsuspensaoMedicamentos('. $value->codSuspensaoMedicamento .')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removesuspensaoMedicamentos('. $value->codSuspensaoMedicamento .')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';
			
			$data['data'][$key] = array(
				$value->codSuspensaoMedicamento,
				$value->codPrescricaoMedicamento,
				$value->codMedicamento,
				$value->codAtendimento,
				$value->codAutor,
				$value->motivo,
				$value->qtdDevolucao,
				$value->dataCriacao,

				$ops,
			);
		} 

		return $this->response->setJSON($data);		
	}
	
	public function getOne()
	{
 		$response = array();
		
		$id = $this->request->getPost('codSuspensaoMedicamento');
		
		if ($this->validation->check($id, 'required|numeric')) {
			
			$data = $this->SuspensaoMedicamentosModel->pegaPorCodigo($id);
			
			return $this->response->setJSON($data);	
				
		} else {
			
			throw new \CodeIgniter\Exceptions\PageNotFoundException();

		}	
		
	}	
	
	public function add()
	{

        $response = array();

        $fields['codSuspensaoMedicamento'] = $this->request->getPost('codSuspensaoMedicamento');
        $fields['codPrescricaoMedicamento'] = $this->request->getPost('codPrescricaoMedicamento');
        $fields['codMedicamento'] = $this->request->getPost('codMedicamento');
        $fields['codAtendimento'] = $this->request->getPost('codAtendimento');
        $fields['codAutor'] = $this->request->getPost('codAutor');
        $fields['motivo'] = $this->request->getPost('motivo');
        $fields['qtdDevolucao'] = $this->request->getPost('qtdDevolucao');
        $fields['dataCriacao'] = $this->request->getPost('dataCriacao');
        $fields['codPaciente'] = $this->request->getPost('codPaciente');


        $this->validation->setRules([
            'codPrescricaoMedicamento' => ['label' => 'CodPrescricaoMedicamento', 'rules' => 'permit_empty|numeric|max_length[11]'],
            'codMedicamento' => ['label' => 'CodMedicamento', 'rules' => 'required|numeric|max_length[11]'],
            'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
            'motivo' => ['label' => 'Motivo', 'rules' => 'required'],
            'qtdDevolucao' => ['label' => 'QtdDevolucao', 'rules' => 'required|numeric|max_length[11]'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
			
        } else {

            if ($this->SuspensaoMedicamentosModel->insert($fields)) {
												
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
		
        $fields['codSuspensaoMedicamento'] = $this->request->getPost('codSuspensaoMedicamento');
        $fields['codPrescricaoMedicamento'] = $this->request->getPost('codPrescricaoMedicamento');
        $fields['codMedicamento'] = $this->request->getPost('codMedicamento');
        $fields['codAtendimento'] = $this->request->getPost('codAtendimento');
        $fields['codAutor'] = $this->request->getPost('codAutor');
        $fields['motivo'] = $this->request->getPost('motivo');
        $fields['qtdDevolucao'] = $this->request->getPost('qtdDevolucao');
        $fields['dataCriacao'] = $this->request->getPost('dataCriacao');
        $fields['codPaciente'] = $this->request->getPost('codPaciente');


        $this->validation->setRules([
            'codSuspensaoMedicamento' => ['label' => 'codSuspensaoMedicamento', 'rules' => 'permit_empty|numeric|max_length[11]'],
            'codPrescricaoMedicamento' => ['label' => 'CodPrescricaoMedicamento', 'rules' => 'permit_empty|numeric|max_length[11]'],
            'codMedicamento' => ['label' => 'CodMedicamento', 'rules' => 'required|numeric|max_length[11]'],
            'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
            'motivo' => ['label' => 'Motivo', 'rules' => 'required'],
            'qtdDevolucao' => ['label' => 'QtdDevolucao', 'rules' => 'required|numeric|max_length[11]'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
			
        } else {

            if ($this->SuspensaoMedicamentosModel->update($fields['codSuspensaoMedicamento'], $fields)) {
				
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
		
		$id = $this->request->getPost('codSuspensaoMedicamento');
		
		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
			
		} else {	
		
			if ($this->SuspensaoMedicamentosModel->where('codSuspensaoMedicamento', $id)->delete()) {
								
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