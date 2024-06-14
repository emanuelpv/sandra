<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;
use App\Models\EspecialidadesModel;

use App\Models\TermosModel;

class Medicos extends BaseController
{

	protected $termosModel;
	protected $pessoasModel;
	protected $organizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		$this->termosModel = new TermosModel();
		$this->EspecialidadesModel = new EspecialidadesModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);



	}

	public function index()
	{

		$response = array();

		$data['data'] = $this->EspecialidadesModel->especialistas();
		return view('medicos', $data);
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->termosModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Editar" onclick="edittermos(' . $value->codTermo . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="removetermos(' . $value->codTermo . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			if ($value->codStatus == 1) {
				$status = "Sim";
			} else {
				$status = "Não";
			}
			$data['data'][$key] = array(
				$value->codTermo,
				$value->nomeExibicao,
				$value->assunto,
				$status,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codTermo');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->termosModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{
		$codPessoa = session()->codPessoa;
		$codOrganizacao = session()->codOrganizacao;


		$response = array();

		$fields['codTermo'] = $this->request->getPost('codTermo');
		$fields['codPessoa'] = $codPessoa;
		$fields['codOrganizacao'] = $codOrganizacao;
		$fields['dataAlteracao'] = date('Y-m-d H:i');
		$fields['dataCriacao'] = date('Y-m-d H:i');
		$fields['assunto'] = $this->request->getPost('assunto');
		$fields['termo'] = $this->request->getPost('termo');
		if ($this->request->getPost('codStatus') == 'on') {
			$fields['codStatus'] = '1';
		} else {
			$fields['codStatus'] = '0';
		}


		$this->validation->setRules([
			'codPessoa' => ['label' => 'Autor', 'rules' => 'required|numeric|max_length[11]'],
			'assunto' => ['label' => 'Assunto', 'rules' => 'required|max_length[100]'],
			'termo' => ['label' => 'Termo', 'rules' => 'required'],
			'codStatus' => ['label' => 'Status', 'rules' => 'required|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->termosModel->insert($fields)) {

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

		$codPessoa = session()->codPessoa;
		$response = array();

		$fields['codTermo'] = $this->request->getPost('codTermo');
		$fields['codPessoa'] = $codPessoa;
		$fields['dataAlteracao'] = date('Y-m-d H:i');
		$fields['assunto'] = $this->request->getPost('assunto');
		$fields['termo'] = $this->request->getPost('termo');
		if ($this->request->getPost('codStatusEdit') == 'on') {
			$fields['codStatus'] = '1';
		} else {
			$fields['codStatus'] = '0';
		}


		$this->validation->setRules([
			'codPessoa' => ['label' => 'Autor', 'rules' => 'required|numeric|max_length[11]'],
			'assunto' => ['label' => 'Assunto', 'rules' => 'required|max_length[100]'],
			'termo' => ['label' => 'Termo', 'rules' => 'required'],
			'codStatus' => ['label' => 'Status', 'rules' => 'required|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->termosModel->update($fields['codTermo'], $fields)) {

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

		$id = $this->request->getPost('codTermo');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->termosModel->where('codTermo', $id)->delete()) {

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
