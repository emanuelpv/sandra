<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;
use App\Models\ServicoLDAPModel;
use App\Models\DepartamentosModel;


use App\Models\GruposModel;

class Grupos extends BaseController
{

	protected $gruposModel;
	protected $pessoasModel;
	protected $organizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->gruposModel = new GruposModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->DepartamentosModel = new DepartamentosModel();
		$this->ServicoLDAPModel = new ServicoLDAPModel();
		$this->PessoasModel = new PessoasModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);
		$permissao = verificaPermissao('Grupos', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo Grupos', session()->codPessoa);
			exit();
		}
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'grupos',
			'title'     		=> 'Grupos'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('grupos', $data);
	}


	public function membrosGrupo($codGrupo = null)
	{
		$response = array();

		$data['data'] = array();

		if ($this->request->getPost('codGrupo') !== NULL) {
			$codGrupo = $this->request->getPost('codGrupo');
		}

		$membros = $this->gruposModel->pegaPessoasNoGrupo($codGrupo);


		foreach ($membros as $key => $value) {

			$dados = json_encode(array($value->codPessoa, $value->nomeExibicao));
			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Remover Membro" onclick="removerMembro(\'' . $value->codPessoa . '\', \'' . $codGrupo . '\')"><i class="fa fa-power-off"></i></button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codPessoa,
				$value->nomeExibicao,
				$value->telefone,
				$value->email,
				$status,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}



	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->gruposModel->pegaTudo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Editar" onclick="editgrupos(' . $value->codGrupo . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="removegrupos(' . $value->codGrupo . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';

			if ($value->ativo == 1) {
				$status = 'Sim';
			} else {
				$status = 'Não';
			}



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codGrupo,
				$value->descricaoGrupo,
				$value->abreviacaoGrupo,
				$value->descricaoDepartamento,
				$value->telefone,
				$value->email,
				$status,

				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codGrupo');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->gruposModel->pegaPorCodigo($id);

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
		$fields['codGrupo'] = $this->request->getPost('codGrupo');
		$fields['descricaoGrupo'] = $this->request->getPost('descricaoGrupo');
		$fields['abreviacaoGrupo'] = $this->request->getPost('abreviacaoGrupo');
		$fields['codDepartamento'] = $this->request->getPost('codDepartamento');
		$fields['telefone'] = $this->request->getPost('telefone');
		$fields['email'] = $this->request->getPost('email');
		$fields['ativo'] = 1;
		$fields['autorAlteracao'] = session()->codPessoa;
		$fields['ultimaAlteracao'] = date('Y-m-d H:i');


		$this->validation->setRules([
			'descricaoGrupo' => ['label' => 'DescricaoGrupo', 'rules' => 'required|max_length[100]'],
			'codDepartamento' => ['label' => 'codDepartamento', 'rules' => 'required'],
			'abreviacaoGrupo' => ['label' => 'AbreviacaoGrupo', 'rules' => 'required'],
			'telefone' => ['label' => 'Telefone', 'rules' => 'permit_empty|max_length[20]'],
			'email' => ['label' => 'Email', 'rules' => 'permit_empty|max_length[50]'],
			'ativo' => ['label' => 'Ativo', 'rules' => 'permit_empty|max_length[1]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($codGrupo = $this->gruposModel->insert($fields)) {

				$response['success'] = true;
				$response['codGrupo'] = $codGrupo;
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

		$fields['codGrupo'] = $this->request->getPost('codGrupo');
		$fields['descricaoGrupo'] = $this->request->getPost('descricaoGrupo');
		$fields['abreviacaoGrupo'] = $this->request->getPost('abreviacaoGrupo');
		$fields['codDepartamento'] = $this->request->getPost('codDepartamento');
		$fields['telefone'] = $this->request->getPost('telefone');
		$fields['email'] = $this->request->getPost('email');
		$fields['ativo'] = $this->request->getPost('ativo');
		$fields['autorAlteracao'] = session()->codPessoa;
		$fields['ultimaAlteracao'] = date('Y-m-d H:i');


		$this->validation->setRules([
			'codGrupo' => ['label' => 'codGrupo', 'rules' => 'required|numeric'],
			'descricaoGrupo' => ['label' => 'DescricaoGrupo', 'rules' => 'required|max_length[100]'],
			'abreviacaoGrupo' => ['label' => 'AbreviacaoGrupo', 'rules' => 'required|max_length[100]'],
			'codDepartamento' => ['label' => 'codDepartamento', 'rules' => 'permit_empty'],
			'telefone' => ['label' => 'Telefone', 'rules' => 'permit_empty|max_length[20]'],
			'email' => ['label' => 'Email', 'rules' => 'permit_empty|max_length[50]'],
			'ativo' => ['label' => 'Ativo', 'rules' => 'permit_empty|max_length[1]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->gruposModel->update($fields['codGrupo'], $fields)) {

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




	public function addMembro($codGrupo = null, $codPessoa = null)
	{
		$response = array();

		$data['data'] = array();


		if ($this->request->getPost('codGrupo') !== NULL) {
			$codGrupo = $this->request->getPost('codGrupo');
		}
		if ($this->request->getPost('codPessoa') !== NULL) {
			$codPessoa = $this->request->getPost('codPessoa');
		}

		$pessoa = $this->PessoasModel->pegaPessoaPorCodPessoa($codPessoa);
		$verificaPessoasNoGrupo = $this->gruposModel->verificaPessoasNoGrupo($codPessoa, $codGrupo);
		if ($verificaPessoasNoGrupo !== NULL) {
			$response['JaPertence'] = true;
			$response['codPessoa'] = $codPessoa;
			$response['messages'] = $pessoa->nomeExibicao . ' Já pertence a este Grupo';
			return $this->response->setJSON($response);
		}



		if ($this->gruposModel->AddMembro($codPessoa, $codGrupo)) {

			$sincroniaLDAP = adicionarPessoaGrupoLDAP($this, $codPessoa, $codGrupo);

			$response['success'] = true;
			$response['csrf_hash'] = csrf_hash();
			$response['JaPertence'] = false;
			$response['codPessoa'] = $codPessoa;
			$response['messages'] = $pessoa->nomeExibicao . ' foi adicionada com sucesso';
		} else {
			$response['success'] = false;
			$response['messages'] = 'Falha ao adicionar membro!';
		}




		return $this->response->setJSON($response);
	}





	public function importarGrupos($codServidorLDAP = null)
	{

		//REGRA DE SICRONIZAÇÃO DO LDAP IMPORTAÇÃO AQUI

		//Espera mínima



		if ($codServidorLDAP !== NULL) {
			$codServidorLDAP = $codServidorLDAP;
		}

		if ($this->request->getPost('codServidorLDAP') !== NULL) {
			$codServidorLDAP = $this->request->getPost('codServidorLDAP');
		}


		$servidorLDAP = $this->ServicoLDAPModel->pegaPorCodigo($codServidorLDAP);


		$loginLDAP = $this->ServicoLDAPModel->conectaldap($servidorLDAP->loginLDAP, $servidorLDAP->senhaLDAP, $servidorLDAP->codServidorLDAP);
		$atributoChave = $servidorLDAP->atributoChave;

		if ($loginLDAP['status'] == 1) {

			//PESSOAS
			$dadosLdapPessoa = $this->ServicoLDAPModel->pegaPessoas($loginLDAP['tipoldap'], $orderby = 'sn', NULL);

			//GRUPOS
			$dadosLdapGrupos = $this->ServicoLDAPModel->pegaGrupos($loginLDAP['tipoldap'], NULL, $orderby = 'sn');

			$gruposExistentes = $this->gruposModel->pegaTudo();

			$listaSimplesGrupos = 	array();

			$departamentosAtualizados = 0;
			$departamentosAdicionados = 0;


			foreach ($gruposExistentes as $row) {
				if ($row->abreviacaoGrupo !== NULL) {
					array_push($listaSimplesGrupos, $row->abreviacaoGrupo);
				}
			}
			foreach ($dadosLdapGrupos as $grupoLDAP) {

				if ($grupoLDAP['cn'][0] !== NULL) {



					// VERIFICA SE O GRUPO ESTÁ DENTRO DE ALGUM DEPARTAMENTO PARA FAZER A ASSOCIAÇÃO
					$departamentoDN = explode(",", $grupoLDAP['distinguishedname'][0]);
					$departamentoDN[1];
					$departamento = str_replace("ou=", "", str_replace("OU=", "", $departamentoDN[1]));

					$codDepartamento = $this->DepartamentosModel->pegaDepartamentoPorNome($departamento)->codDepartamento;


					if (in_array($grupoLDAP['cn'][0], $listaSimplesGrupos)) {



						if ($grupoLDAP['description'][0] !== NULL and $grupoLDAP['description'][0] !== " "  and $grupoLDAP['description'][0] !== "") {
							$fieldsAdd['descricaoGrupo'] = $grupoLDAP['description'][0];
						} else {
							$fieldsAdd['descricaoGrupo'] = $grupoLDAP['name'][0];
						}
						$fieldsEdit['abreviacaoGrupo'] = $grupoLDAP['name'][0];
						$fieldsEdit['ativo'] = 1;
						$fieldsEdit['codDepartamento'] = $codDepartamento;
						$fieldsEdit['ultimaAlteracao'] = date('Y-m-d H:i');
						$fieldsEdit['autorAlteracao'] = session()->codPessoa;

						$codGrupo = $this->gruposModel->pegaGrupoPorNome($grupoLDAP['cn'][0])->codGrupo;
						if ($codGrupo !== NULL and $codGrupo !== "") {

							if ($this->gruposModel->update($codGrupo, $fieldsEdit)) {
								$departamentosAtualizados++;
							}
						}
					} else {
						if ($grupoLDAP['description'][0] !== NULL and $grupoLDAP['description'][0] !== " "  and $grupoLDAP['description'][0] !== "") {
							$fieldsAdd['descricaoGrupo'] = $grupoLDAP['description'][0];
						} else {
							$fieldsAdd['descricaoGrupo'] = $grupoLDAP['name'][0];
						}
						$fieldsAdd['abreviacaoGrupo'] = $grupoLDAP['name'][0];
						$fieldsAdd['ativo'] = 1;
						$fieldsAdd['codDepartamento'] = $codDepartamento;
						$fieldsAdd['codOrganizacao'] = session()->codOrganizacao;
						$fieldsAdd['paiGrupo'] = 0;
						$fieldsAdd['ultimaAlteracao'] = date('Y-m-d H:i');
						$fieldsAdd['autorAlteracao'] = session()->codPessoa;
						if ($codGrupo = $this->gruposModel->insert($fieldsAdd)) {
							$departamentosAdicionados++;
						}
					}

					//ADICIONAR MEMBROS AOS GRUPOS SE EXISTIR



					$contasMembros = array();
					$membroDN = null;
					if ($grupoLDAP['member'] !== null and is_array($grupoLDAP['member'])) {
						foreach ($grupoLDAP['member'] as $membro) {

							//print_r($membro); exit();
							$membroDN = explode(",", $membro);

							if (!is_numeric($membroDN[0])) {
								array_push($contasMembros, str_replace("cn=", "", str_replace("CN=", "", $membroDN[0])));
							}
						}
					}




					foreach ($contasMembros as $membroGrupo) {
						$membrosGrupo = $this->PessoasModel->pegaMembroGrupoPorLogin($membroGrupo, $codGrupo);

						if ($grupoLDAP['name'][0] == 'MANUTENCAO') {
						}

						if ($membrosGrupo->existe !== NULL) {
							//JÁ EXISTE NO GRUPO. NÃO FAZ NADA

						} else {
							//ADICIONA NO GRUPO
							$this->gruposModel->AddMembro($membrosGrupo->codPessoa, $codGrupo);
						}
					}
				}
			}
		}

		$resposta = "
<table>
<tr>
<td>Grupos Adicionados</td>
<td>" . $departamentosAdicionados . "</td>
</tr>


<tr>
<td>Grupos Atualizados</td>
<td>" . $departamentosAtualizados . "</td>
</tr>

</table>
";

		$response['success'] = true;
		$response['csrf_hash'] = csrf_hash();
		$response['messages'] = $resposta;
		return $this->response->setJSON($response);
	}




	public function pegaServidoresLDAPMicrosoft()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->ServicoLDAPModel->pegaServidoresLDAPMicrosoftAtivo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '<button type="button" class="btn btn-xs btn-primary" onclick="importarAgora(' . $value->codServidorLDAP . ',\'' . $value->descricaoServidorLDAP . '\',\'' . $value->ipServidorLDAP . '\')" title="Importar Departamentos"> <i class="fas fa-file-import"></i> Importar</button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->descricaoServidorLDAP,
				$value->ipServidorLDAP,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}



	public function pegaServidoresLDAPMIcrosoftParaExportacao()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->ServicoLDAPModel->pegaServidoresLDAPMicrosoftAtivo();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '<button type="button" class="btn btn-xs btn-primary" onclick="exportarAgora(' . $value->codServidorLDAP . ',\'' . $value->descricaoServidorLDAP . '\',\'' . $value->ipServidorLDAP . '\')" title="Exportar Departamentos"> <i class="fas fa-file-import"></i> Exportar</button>';
			$ops .= '</div>';



			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->descricaoServidorLDAP,
				$value->ipServidorLDAP,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}



	public function removerMembro($codPessoa = null, $codGrupo = null)
	{
		$response = array();

		if ($this->request->getPost('codGrupo') !== NULL) {
			$codGrupo = $this->request->getPost('codGrupo');
		}
		if ($this->request->getPost('codPessoa') !== NULL) {
			$codPessoa = $this->request->getPost('codPessoa');
		}

		if (!$this->validation->check($codPessoa, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->gruposModel->RemoverMembro($codPessoa, $codGrupo)) {

				removerPessoaGrupoLDAP($this, $codPessoa, $codGrupo);
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

	public function remove()
	{
		$response = array();

		$id = $this->request->getPost('codGrupo');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->gruposModel->where('codGrupo', $id)->delete()) {

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
