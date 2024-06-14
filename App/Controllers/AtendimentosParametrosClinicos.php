<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\AtendimentosParametrosClinicosModel;

class AtendimentosParametrosClinicos extends BaseController
{
	
    protected $AtendimentosParametrosClinicosModel;
    protected $pessoasModel;
    protected $OrganizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

	public function __construct()
	{
		
		helper('seguranca_helper');
        verificaSeguranca($this, session(),base_url());
	    $this->AtendimentosParametrosClinicosModel = new AtendimentosParametrosClinicosModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
       	$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
        $this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
		

		
	}
	
	public function index()
	{
		
		$permissao = verificaPermissao('AtendimentosParametrosClinicos', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "AtendimentosParametrosClinicos"', session()->codPessoa);
			exit();
		}
		

	    $data = [
                'controller'    	=> 'atendimentosParametrosClinicos',
                'title'     		=> 'Parâmetros Clínicos do Atendimento'				
			];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('atendimentosParametrosClinicos', $data);
			
	}

	public function getAll()
	{
 		$response = array();		
		
	    $data['data'] = array();
 
		$result = $this->AtendimentosParametrosClinicosModel->pegaTudo();
		
		foreach ($result as $key => $value) {
							
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editatendimentosParametrosClinicos('. $value->codParametroClinico .')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeatendimentosParametrosClinicos('. $value->codParametroClinico .')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';
			
			$data['data'][$key] = array(
				$value->codParametroClinico,
				$value->codAtendimento,
				$value->dataCriacao,
				$value->dataAtualizacao,
				$value->codAutor,
				$value->peso,
				$value->altura,
				$value->perimetroCefalico,
				$value->parimetroAbdominal,
				$value->paSistolica,
				$value->paDiastolica,
				$value->fc,
				$value->fr,
				$value->temperatura,
				$value->saturacao,

				$ops,
			);
		} 

		return $this->response->setJSON($data);		
	}
	
	public function getOne()
	{
 		$response = array();
		
		$id = $this->request->getPost('codParametroClinico');
		
		if ($this->validation->check($id, 'required|numeric')) {
			
			$data = $this->AtendimentosParametrosClinicosModel->pegaPorCodigo($id);
			
			return $this->response->setJSON($data);	
				
		} else {
			
			throw new \CodeIgniter\Exceptions\PageNotFoundException();

		}	
		
	}	
	
	public function add()
	{

        $response = array();

        $fields['codParametroClinico'] = $this->request->getPost('codParametroClinico');
        $fields['codAtendimento'] = $this->request->getPost('codAtendimento');
        $fields['dataCriacao'] = $this->request->getPost('dataCriacao');
        $fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');
        $fields['codAutor'] = $this->request->getPost('codAutor');
        $fields['peso'] = $this->request->getPost('peso');
        $fields['altura'] = $this->request->getPost('altura');
        $fields['perimetroCefalico'] = $this->request->getPost('perimetroCefalico');
        $fields['parimetroAbdominal'] = $this->request->getPost('parimetroAbdominal');
        $fields['paSistolica'] = $this->request->getPost('paSistolica');
        $fields['paDiastolica'] = $this->request->getPost('paDiastolica');
        $fields['fc'] = $this->request->getPost('fc');
        $fields['fr'] = $this->request->getPost('fr');
        $fields['temperatura'] = $this->request->getPost('temperatura');
        $fields['saturacao'] = $this->request->getPost('saturacao');


        $this->validation->setRules([
            'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
            'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
            'peso' => ['label' => 'Peso', 'rules' => 'required'],
            'altura' => ['label' => 'Altura', 'rules' => 'required'],
            'perimetroCefalico' => ['label' => 'PerimetroCefalico', 'rules' => 'required'],
            'parimetroAbdominal' => ['label' => 'ParimetroAbdominal', 'rules' => 'required'],
            'paSistolica' => ['label' => 'PaSistolica', 'rules' => 'required'],
            'paDiastolica' => ['label' => 'PaDiastolica', 'rules' => 'required'],
            'fc' => ['label' => 'Fc', 'rules' => 'required'],
            'fr' => ['label' => 'Fr', 'rules' => 'required'],
            'temperatura' => ['label' => 'Temperatura', 'rules' => 'required|max_length[3]'],
            'saturacao' => ['label' => 'Saturacao', 'rules' => 'required|max_length[3]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
			
        } else {

            if ($this->AtendimentosParametrosClinicosModel->insert($fields)) {
												
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
		
        $fields['codParametroClinico'] = $this->request->getPost('codParametroClinico');
        $fields['codAtendimento'] = $this->request->getPost('codAtendimento');
        $fields['dataCriacao'] = $this->request->getPost('dataCriacao');
        $fields['dataAtualizacao'] = $this->request->getPost('dataAtualizacao');
        $fields['codAutor'] = $this->request->getPost('codAutor');
        $fields['peso'] = $this->request->getPost('peso');
        $fields['altura'] = $this->request->getPost('altura');
        $fields['perimetroCefalico'] = $this->request->getPost('perimetroCefalico');
        $fields['parimetroAbdominal'] = $this->request->getPost('parimetroAbdominal');
        $fields['paSistolica'] = $this->request->getPost('paSistolica');
        $fields['paDiastolica'] = $this->request->getPost('paDiastolica');
        $fields['fc'] = $this->request->getPost('fc');
        $fields['fr'] = $this->request->getPost('fr');
        $fields['temperatura'] = $this->request->getPost('temperatura');
        $fields['saturacao'] = $this->request->getPost('saturacao');


        $this->validation->setRules([
            'codParametroClinico' => ['label' => 'codParametroClinico', 'rules' => 'required|numeric|max_length[11]'],
            'codAtendimento' => ['label' => 'CodAtendimento', 'rules' => 'required|numeric|max_length[11]'],
            'dataCriacao' => ['label' => 'DataCriacao', 'rules' => 'required'],
            'dataAtualizacao' => ['label' => 'DataAtualizacao', 'rules' => 'required'],
            'codAutor' => ['label' => 'CodAutor', 'rules' => 'required|numeric|max_length[11]'],
            'peso' => ['label' => 'Peso', 'rules' => 'required'],
            'altura' => ['label' => 'Altura', 'rules' => 'required'],
            'perimetroCefalico' => ['label' => 'PerimetroCefalico', 'rules' => 'required'],
            'parimetroAbdominal' => ['label' => 'ParimetroAbdominal', 'rules' => 'required'],
            'paSistolica' => ['label' => 'PaSistolica', 'rules' => 'required'],
            'paDiastolica' => ['label' => 'PaDiastolica', 'rules' => 'required'],
            'fc' => ['label' => 'Fc', 'rules' => 'required'],
            'fr' => ['label' => 'Fr', 'rules' => 'required'],
            'temperatura' => ['label' => 'Temperatura', 'rules' => 'required|max_length[3]'],
            'saturacao' => ['label' => 'Saturacao', 'rules' => 'required|max_length[3]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
			
        } else {

            if ($this->AtendimentosParametrosClinicosModel->update($fields['codParametroClinico'], $fields)) {
				
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
		
		$id = $this->request->getPost('codParametroClinico');
		
		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
			
		} else {	
		
			if ($this->AtendimentosParametrosClinicosModel->where('codParametroClinico', $id)->delete()) {
								
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