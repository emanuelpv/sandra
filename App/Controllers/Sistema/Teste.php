<?php
// Desenvolvido por Emanuel Peixoto Vicente

namespace App\Controllers\Sistema;

use App\Controllers\BaseController;
use App\Models\OrganizacoesModel;
use App\Models\LogsModel;

use App\Models\TesteModel;

class Teste extends BaseController
{

	protected $testeModel;
	protected $validation;

	public function __construct()
	{

		$this->testeModel = new TesteModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$cod_pessoa);
		$this->validation =  \Config\Services::validation();
	}

	public function index()
	{
		$tudo = $this->OrganizacoesModel->pegaTudo();
		print_r($tudo);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->testeModel->select('xxx, nome')->findAll();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Editar" onclick="edit(' . $value->xxx . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="remove(' . $value->xxx . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			

			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
$data['data'][$key] = array(
				$value->xxx,
				$value->nome,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('xxx');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->testeModel->where('xxx', $id)->first();

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$fields['xxx'] = $this->request->getPost('xxx');
		$fields['nome'] = $this->request->getPost('nome');


		$this->validation->setRules([
			'nome' => ['label' => 'Nome', 'rules' => 'required|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->testeModel->insert($fields)) {

				$response['success'] = true;
$response['csrf_hash'] = csrf_hash();
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

		$fields['xxx'] = $this->request->getPost('xxx');
		$fields['nome'] = $this->request->getPost('nome');


		$this->validation->setRules([
			'nome' => ['label' => 'Nome', 'rules' => 'required|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->testeModel->update($fields['xxx'], $fields)) {

				$response['success'] = true;
$response['csrf_hash'] = csrf_hash();
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

		$id = $this->request->getPost('xxx');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->testeModel->where('xxx', $id)->delete()) {

				$response['success'] = true;
$response['csrf_hash'] = csrf_hash();
				$response['messages'] = 'Deletado com sucesso';
			} else {

				$response['success'] = false;
				$response['messages'] = 'Erro na deleção!';
			}
		}

		return $this->response->setJSON($response);
	}
}
