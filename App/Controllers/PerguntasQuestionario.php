<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\PerguntasQuestionarioModel;

class PerguntasQuestionario extends BaseController
{

	protected $PerguntasQuestionarioModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->PerguntasQuestionarioModel = new PerguntasQuestionarioModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation = \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao = $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);



	}

	public function index()
	{

		$permissao = verificaPermissao('PerguntasQuestionario', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "PerguntasQuestionario"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller' => 'perguntasQuestionario',
			'title' => 'Perguntas do Questionário'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('perguntasQuestionario', $data);

	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();


		$codQuestionario = $this->request->getPost('codQuestionario');
		$result = $this->PerguntasQuestionarioModel->pegaPorQuestionario($codQuestionario);
		$x=0;
		foreach ($result as $key => $value) {
			$x++;
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editperguntasQuestionario(' . $value->codPerguntaQuestionario . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeperguntasQuestionario(' . $value->codPerguntaQuestionario . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			$data['data'][$key] = array(
				$x,
				$value->descricaoPergunta,
				$value->descricao,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codPerguntaQuestionario');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->PerguntasQuestionarioModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);

		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();

		}

	}


	public function listaDropDownPerguntas()
	{

		$codQuestionario = $this->request->getPost('codQuestionario');

		$codTipoQuestionario = $this->request->getPost('codTipoQuestionario');

		$result = $this->PerguntasQuestionarioModel->listaDropDownPerguntas($codQuestionario, $codTipoQuestionario);

		if ($result !== NULL) {

			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();



		if ($this->request->getPost('listaPerguntas') !== NULL) {

			foreach ($this->request->getPost('listaPerguntas') as $item) {
				$fields['codPerguntaQuestionario'] = $this->request->getPost('codPerguntaQuestionario');
				$fields['codPergunta'] = $item;
				$fields['codQuestionario'] = $this->request->getPost('codQuestionario');
				$this->PerguntasQuestionarioModel->insert($fields);
			}

		}


		$response['success'] = true;
		$response['messages'] = 'Informação inserida com sucesso';
		return $this->response->setJSON($response);
	}

	public function edit()
	{

		$response = array();

		$fields['codPerguntaQuestionario'] = $this->request->getPost('codPerguntaQuestionario');
		$fields['codPergunta'] = $this->request->getPost('codPergunta');
		$fields['codQuestionario'] = $this->request->getPost('codQuestionario');


		$this->validation->setRules([
			'codPerguntaQuestionario' => ['label' => 'codPerguntaQuestionario', 'rules' => 'required|numeric|max_length[11]'],
			'codPergunta' => ['label' => 'CodPergunta', 'rules' => 'required|numeric|max_length[11]'],
			'codQuestionario' => ['label' => 'CodQuestionario', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();

		} else {

			if ($this->PerguntasQuestionarioModel->update($fields['codPerguntaQuestionario'], $fields)) {

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

		$id = $this->request->getPost('codPerguntaQuestionario');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();

		} else {

			if ($this->PerguntasQuestionarioModel->where('codPerguntaQuestionario', $id)->delete()) {

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