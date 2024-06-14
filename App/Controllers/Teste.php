<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\TesteModel;

class Teste extends BaseController
{
	
    protected $TesteModel;
    protected $pessoasModel;
    protected $OrganizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

	public function __construct()
	{
		
		helper('seguranca_helper');
        verificaSeguranca($this, session(),base_url());
	    $this->TesteModel = new TesteModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
       	$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
        $this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
		

		
	}
	
	public function index()
	{


		

	    $data = [
                'controller'    	=> 'teste',
                'title'     		=> 'Teste'				
			];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('teste', $data);
			
	}


	public function sped()
	{


		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "http://sped3.hospital.com.br/sigadexintegracao/api/organizacao-militar");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3); //timeout in seconds
		$parametros = array(
			'page' => 0,
			'size' => 5000,
		);

		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parametros));

		$response = curl_exec($ch);
		curl_close($ch);
		
		print_r($response);
			
	}
	public function getAll()
	{
 		$response = array();		
		
	    $data['data'] = array();
 
		$result = $this->TesteModel->pegaTudo();
		
		foreach ($result as $key => $value) {
							
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editteste('. $value->codPessoa .')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeteste('. $value->codPessoa .')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';
			
			$data['data'][$key] = array(
				$value->codPessoa,
				$value->nome,
				$value->telefone,
				$value->dataNascimento,

				$ops,
			);
		} 

		return $this->response->setJSON($data);		
	}
	
	public function getOne()
	{
 		$response = array();
		
		$id = $this->request->getPost('codPessoa');
		
		if ($this->validation->check($id, 'required|numeric')) {
			
			$data = $this->TesteModel->pegaPorCodigo($id);
			
			return $this->response->setJSON($data);	
				
		} else {
			
			throw new \CodeIgniter\Exceptions\PageNotFoundException();

		}	
		
	}	
	
	public function add()
	{

        $response = array();

        $fields['codPessoa'] = $this->request->getPost('codPessoa');
        $fields['nome'] = $this->request->getPost('nome');
        $fields['telefone'] = $this->request->getPost('telefone');
        $fields['dataNascimento'] = $this->request->getPost('dataNascimento');


        $this->validation->setRules([
            'codPessoa' => ['label' => 'CodPessoa', 'rules' => 'required|numeric|max_length[11]'],
            'nome' => ['label' => 'Nome', 'rules' => 'required|max_length[10]'],
            'telefone' => ['label' => 'Telefone', 'rules' => 'required|numeric|max_length[11]'],
            'dataNascimento' => ['label' => 'DataNascimento', 'rules' => 'required|numeric|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
			
        } else {

            if ($this->TesteModel->insert($fields)) {
												
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
		
        $fields['codPessoa'] = $this->request->getPost('codPessoa');
        $fields['nome'] = $this->request->getPost('nome');
        $fields['telefone'] = $this->request->getPost('telefone');
        $fields['dataNascimento'] = $this->request->getPost('dataNascimento');


        $this->validation->setRules([
            'codPessoa' => ['label' => 'CodPessoa', 'rules' => 'required|numeric|max_length[11]'],
            'nome' => ['label' => 'Nome', 'rules' => 'required|max_length[10]'],
            'telefone' => ['label' => 'Telefone', 'rules' => 'required|numeric|max_length[11]'],
            'dataNascimento' => ['label' => 'DataNascimento', 'rules' => 'required|numeric|max_length[11]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
			
        } else {

            if ($this->TesteModel->update($fields['codPessoa'], $fields)) {
				
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
		
		$id = $this->request->getPost('codPessoa');
		
		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
			
		} else {	
		
			if ($this->TesteModel->where('codPessoa', $id)->delete()) {
								
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