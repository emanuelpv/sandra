<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\SlideshowModel;
use CodeIgniter\Files\File;

class Slideshow extends BaseController
{

	protected $SlideshowModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper', 'form');
		//verificaSeguranca($this, session(), base_url());
		$this->SlideshowModel = new SlideshowModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
	}

	public function index()
	{

		$permissao = verificaPermissao('Slideshow', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo "Slideshow"', session()->codPessoa);
			exit();
		}


		$data = [
			'controller'    	=> 'slideshow',
			'title'     		=> 'slideshow'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('slideshow', $data);
	}

	public function envia_slideShow()
	{


		$response = array();

		$validationRule = [
			'file' => [
				'label' => 'Imagem',
				'rules' => [
					'uploaded[file]',
					'is_image[file]',
					'mime_in[file,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
					'max_size[file,50000]',
				],
				'errors' => [
					'is_image' => 'Não é uma imagem',
					'max_size' => 'Arquivo muito grane',
					'mime_in' => 'Extensão inválida',
					'uploaded' => 'Extensão inválida',
				],
			],
		];
		if (!$this->validate($validationRule)) {

			$response['success'] = false;
			$response['messages'] = $this->validator->getError();
			return $this->response->setJSON($response);
		}



		if ($this->request->getFile('file') !== NULL) {

			$img = $this->request->getFile('file');

			if ($this->request->getPost('imagem') == NULL or $this->request->getPost('imagem') == 'undefined') {
				$nomeArquivo = str_replace(" ", "", removeCaracteresIndesejadosEmail($img->getName()));
			} else {
				$nomeArquivo = $this->request->getPost('imagem');
			}
			$img->move(WRITEPATH . '../imagens/slideshow/',  $nomeArquivo, true);

			$fields = array(
				'imagem' => $nomeArquivo,
				'dataAtualizacao' => date('Y-m-d H:i'),
			);
			if ($this->request->getPost('codSlideShow') !== NULL and $this->request->getPost('codSlideShow') !== "" and $this->request->getPost('codSlideShow') !== " ") {
				$this->SlideshowModel->update($this->request->getPost('codSlideShow'), $fields);
			}

			$response['success'] = true;
			$response['foto'] = $nomeArquivo;
			$response['messages'] =  'Foto enviada com sucesso';
			return $this->response->setJSON($response);
		}
	}


	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->SlideshowModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info"  data-toggle="tooltip" data-placement="top" title="Editar"  onclick="editslideshow(' . $value->codSlideShow . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover"  onclick="removeslideshow(' . $value->codSlideShow . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			if (
				$value->codStatus == 1
			) {
				$status = 'Sim';
			} else {
				$status = 'Não';
			}

			if ($value->dataExpiracao !== NULL) {

				$expiracao = date('d/m/Y', strtotime($value->dataExpiracao));
			} else {
				$expiracao = 'Indeterminado';
			}
			$data['data'][$key] = array(
				$value->ordem,
				$value->descricao,
				'<img width="110px" src="' . base_url() . '/imagens/slideshow/' . $value->imagem . '?' . time() . '">',
				$value->url,
				$expiracao,
				$status,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codSlideShow');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->SlideshowModel->pegaPorCodigo($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{

		$response = array();

		$codOrganizacao = session()->codOrganizacao;


		$fields['codOrganizacao'] = $codOrganizacao;
		$fields['codSlideShow'] = $this->request->getPost('codSlideShow');
		$fields['descricao'] = $this->request->getPost('descricao');
		$fields['url'] = $this->request->getPost('url');


		$data = $this->SlideshowModel->totalSlides();

		if ($data->total > 0) {
			$fields['ordem'] = $data->total + 1;
		} else {
			$fields['ordem'] = 1;
		}

		if ($this->request->getPost('codStatus') == 'on') {
			$fields['codStatus'] = 1;
		} else {
			$fields['codStatus'] = 0;
		}
		$fields['dataExpiracao'] = NULL;

		if ($this->request->getPost('dataExpiracao') > '2000-01-01') {
			$fields['dataExpiracao'] = $this->request->getPost('dataExpiracao');
		}



		$this->validation->setRules([
			'ordem' => ['label' => 'ordem', 'rules' => 'numeric|max_length[11]'],
			'codStatus' => ['label' => 'Status', 'rules' => 'numeric|max_length[11]'],
			'descricao' => ['label' => 'Descrição', 'rules' => 'required|max_length[100]|bloquearReservado'],
			'url' => ['label' => 'Url', 'rules' => 'permit_empty|max_length[100]|bloquearReservado'],
			'dataExpiracao' => ['label' => 'Data Expiração', 'rules' => 'permit_empty'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($codSlideShow  = $this->SlideshowModel->insert($fields)) {

				$response['success'] = true;
				$response['codSlideShow'] = $codSlideShow;
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

		$fields['codSlideShow'] = $this->request->getPost('codSlideShow');
		$fields['descricao'] = $this->request->getPost('descricao');
		$fields['url'] = $this->request->getPost('url');
		$fields['ordem'] = $this->request->getPost('ordem');

		if ($this->request->getPost('codStatus') == 'on') {
			$fields['codStatus'] = 1;
		} else {
			$fields['codStatus'] = 0;
		}

		$fields['dataExpiracao'] = NULL;

		if ($this->request->getPost('dataExpiracao') > '2000-01-01') {
			$fields['dataExpiracao'] = $this->request->getPost('dataExpiracao');
		}

		$this->validation->setRules([
			'ordem' => ['label' => 'ordem', 'rules' => 'numeric|max_length[11]'],
			'codStatus' => ['label' => 'Status', 'rules' => 'numeric|max_length[11]'],
			'descricao' => ['label' => 'Descrição', 'rules' => 'required|max_length[100]|bloquearReservado'],
			'url' => ['label' => 'Url', 'rules' => 'permit_empty|max_length[100]|bloquearReservado'],
			'dataExpiracao' => ['label' => 'Data Expiração', 'rules' => 'permit_empty'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->SlideshowModel->update($fields['codSlideShow'], $fields)) {

				$response['success'] = true;
				$response['codSlideShow'] = $fields['codSlideShow'];
				$response['imagem'] = $this->request->getPost('imagem');
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

		$id = $this->request->getPost('codSlideShow');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->SlideshowModel->where('codSlideShow', $id)->delete()) {

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
