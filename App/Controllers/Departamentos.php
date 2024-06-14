<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrganizacoesModel;
use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\ServicoLDAPModel;

use App\Models\DepartamentosModel;

class Departamentos extends BaseController
{

	protected $DepartamentosModel;
	protected $validation;

	public function __construct()
	{

		helper('seguranca_helper');
		verificaSeguranca($this, session(), base_url());
		$this->DepartamentosModel = new DepartamentosModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->PessoasModel = new PessoasModel();
		$this->ServicoLDAPModel = new ServicoLDAPModel;
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
	}

	public function index()
	{
		$permissao = verificaPermissao('Departamentos', 'listar');
		if ($permissao == 0) {
			echo mensagemAcessoNegado(session()->organizacoes);
			$this->LogsModel->inserirLog('Acesso indevido ao Módulo Departamentos', session()->codPessoa);
			exit();
		}

		$data = [
			'controller'    	=> 'departamentos',
			'title'     		=> 'Departamentos'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('departamentos', $data);
	}


	public function classificacaoDiarias()
	{

		$data = [
			'controller'    	=> 'departamentos',
			'title'     		=> 'Departamentos'
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('classificacaoDiarias', $data);
	}

	public function listaDropDownDepartamentosAtendimento()
	{

		$result = $this->DepartamentosModel->listaDropDownDepartamentosAtendimento();

		if ($result !== NULL) {

			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function listaDropDownUnidadesInternacao()
	{

		$result = $this->DepartamentosModel->listaDropDownUnidadesInternacao();

		if ($result !== NULL) {

			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}


	public function listaDropDownUnidadesInternacaoCirurgia()
	{

		$result = $this->DepartamentosModel->listaDropDownUnidadesInternacaoCirurgia();

		if ($result !== NULL) {

			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function listaDropDownUnidadesFaturamento()
	{

		$result = $this->DepartamentosModel->listaDropDownUnidadesFaturamento();

		if ($result !== NULL) {

			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function listaDropDown()
	{

		$result = $this->DepartamentosModel->listaDropDown();

		if ($result !== NULL) {

			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}
	public function listaDropDownTiposLocalAtendimentos()
	{

		$result = $this->DepartamentosModel->listaDropDownTiposLocalAtendimentos();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function listaDropDownStatusLocalAtendimentos()
	{

		$result = $this->DepartamentosModel->listaDropDownStatusLocalAtendimentos();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function listaDropDownSituacaoLocalAtendimentos()
	{

		$result = $this->DepartamentosModel->listaDropDownSituacaoLocalAtendimentos();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}



	public function listaTiposDiarias()
	{

		$result = $this->DepartamentosModel->listaTiposDiarias();

		if ($result !== NULL) {


			return $this->response->setJSON($result);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function getAll()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->DepartamentosModel->pegaDepartamentos();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-xs btn-primary" onclick="edit(' . $value->codDepartamento . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="remove(' . $value->codDepartamento . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';


			if ($value->ativo == 1) {
				$status = 'Sim';
			} else {
				$status = 'Não';
			}


			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codDepartamento,

				$value->descricaoDepartamento,
				$value->abreviacaoDepartamento,
				$value->descricaoTipoDepartamento,
				$value->paiDepartamento,
				$value->telefone,
				$value->email,
				$status,
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}

	public function getAllClassificacaoDiarias()
	{
		$response = array();

		$data['data'] = array();

		$result = $this->DepartamentosModel->getAllClassificacaoDiarias();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '	<button type="button" class="btn btn-xs btn-primary" onclick="edit(' . $value->codDepartamento . ')"><i class="fa fa-edit"></i></button>';
			$ops .= '	<button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Remover" onclick="remove(' . $value->codDepartamento . ')"><i class="fa fa-trash"></i></button>';
			$ops .= '</div>';


			if ($value->ativo == 1) {
				$status = 'Sim';
			} else {
				$status = 'Não';
			}


			$data['csrf_token'] =  csrf_token();
			$data['csrf_hash'] =  csrf_hash();
			$data['data'][$key] = array(
				$value->codDepartamento,

				$value->descricaoDepartamento,
				$value->abreviacaoDepartamento,
				$value->descricaoTipoDepartamento,
				$value->acomodacaoPaciente . ' (R$ ' . $value->valorPaciente . ')',
				$value->acomodacaoAcompanhante . ' (R$ ' . $value->valorAcompanhante . ')',
				$ops,
			);
		}

		return $this->response->setJSON($data);
	}
	public function addMembro($codDepartamento = null, $codPessoa = null)
	{
		$response = array();

		$data['data'] = array();


		$codDepartamento = $this->request->getPost('codDepartamento');

		$codPessoa = $this->request->getPost('codPessoa');

		$pessoa = $this->PessoasModel->pegaPessoaPorCodPessoa($codPessoa);

		if (!$this->validation->check($codPessoa, 'required|numeric') or !$this->validation->check($codDepartamento, 'required|numeric')) {

			$response['success'] = false;
			$response['messages'] = 'Falha ao adicionar membro!';
		} else {
			$this->PessoasModel->updateDepartamentoPorCodPessoa($codPessoa, $codDepartamento);

			$response['success'] = true;
			$response['csrf_hash'] = csrf_hash();
			$response['codPessoa'] = $codPessoa;
			$response['messages'] = $pessoa->nomeExibicao . ' foi adicionada com sucesso';
		}



		return $this->response->setJSON($response);
	}




	public function transferirMembrosDepartamento($codDepartamento = null, $codPessoa = null)
	{
		$response = array();

		$data['data'] = array();


		if ($this->request->getPost('codDepartamento') !== NULL) {
			$codDepartamento = $this->request->getPost('codDepartamento');
		}
		if ($this->request->getPost('codPessoa') !== NULL) {
			$codPessoa = $this->request->getPost('codPessoa');
		}
		if ($this->request->getPost('nomeExibicao') !== NULL) {
			$nomeExibicao = $this->request->getPost('nomeExibicao');
		}
		$departamento = $this->DepartamentosModel->pegaDepartamento($codDepartamento);


		if ($this->PessoasModel->updateDepartamentoPorCodPessoa($codPessoa, $codDepartamento)) {





			$response['success'] = true;
			$response['csrf_hash'] = csrf_hash();
			$response['codPessoa'] = $codPessoa;
			$response['messages'] = 'Conta ' . $nomeExibicao . ' transferida com sucesso para ' . $departamento->descricaoDepartamento;
		} else {
			$response['success'] = false;
			$response['messages'] = 'Falha na transferência!';
		}




		return $this->response->setJSON($response);
	}

	public function membrosDepartamento($codDepartamento = null)
	{
		$response = array();

		$data['data'] = array();

		if ($this->request->getPost('codDepartamento') !== NULL) {
			$codDepartamento = $this->request->getPost('codDepartamento');
		}

		$membros = $this->DepartamentosModel->pegaPessoasNoDepartamento($codDepartamento);


		foreach ($membros as $key => $value) {

			$dados = json_encode(array($value->codPessoa, $value->nomeExibicao));
			$ops = '<div class="btn-group">';

			$ops .= '	<button type="button" class="btn btn-xs btn-primary" onclick="transferirPessoa(\'' . $value->codPessoa . '\', \'' . $value->nomeExibicao . '\');"><i class="fa fa-edit"></i></button>';
			$ops .= '</div>';


			if ($value->ativo == 1) {
				$status = 'Sim';
				$ops .= '	<button type="button" class="btn btn-xs btn-danger" onclick="desativarPessoa(' . $value->codPessoa . ')"><i class="fa fa-power-off"></i></button>';
			} else {
				$status = 'Não';
				$ops .= '	<button type="button" class="btn btn-xs btn-success" onclick="reativarPessoa(' . $value->codPessoa . ')"><i class="fa fa-power-off"></i></button>';
			}


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


	public function importarDepartamentos($codServidorLDAP = null)
	{

		//REGRA DE SICRONIZAÇÃO DO LDAP IMPORTAÇÃO AQUI

		//Espera mínima
		sleep(3);



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

			$dadosLdapPessoa = $this->ServicoLDAPModel->pegaPessoas($loginLDAP['tipoldap'], $orderby = 'sn', NULL);




			//				array_push($arrayPessoas,array('pessoa'=>$contaAtual, 'departamento'=>explode(",", $dadosLdapPessoa[0]['distinguishedname'][0])));	



			if ($servidorLDAP->dnNovosUsuarios !== NULL) {
				$dnNovosUsuarios = explode(",", $servidorLDAP->dnNovosUsuarios);
				$repositorioNovosUsuarios = str_replace("ou=", "", str_replace("OU=", "", $dnNovosUsuarios[0]));
			} else {
				$repositorioNovosUsuarios = NULL;
			}



			if ($loginLDAP['tipoldap'] == 1) {
				$dadosLdapDepartamentos = $this->ServicoLDAPModel->pegaDepartamentos($loginLDAP['tipoldap'], $orderby = 'sn', NULL);


				$departamentosAdicionados = 0;
				$departamentosAtualizados = 0;

				foreach ($dadosLdapDepartamentos as $departamentosLDAP) {
					//não considera o repositorio de usuarios padrão
					if ($repositorioNovosUsuarios !== $departamentosLDAP['name'][0] and $repositorioNovosUsuarios !== $departamentosLDAP['description'][0]) {


						$departamentosExistente = $this->DepartamentosModel->pegaDepartamentoPorNome($departamentosLDAP['ou'][0]);

						if ($departamentosExistente !== NULL and $departamentosExistente !== "" and $departamentosExistente !== " ") {

							$fieldsEdit['codDepartamento'] = $departamentosExistente->codDepartamento;
							$fieldsEdit['descricaoDepartamento'] = $departamentosLDAP['name'][0];
							$fieldsEdit['abreviacaoDepartamento'] = $departamentosLDAP['description'][0];
							$fieldsEdit['ativo'] = 1;
							$fieldsEdit['autorAlteracao'] = session()->codPessoa;
							$this->DepartamentosModel->update($departamentosExistente->codDepartamento, $fieldsEdit);
							$departamentosAtualizados++;
							//print_r($fields);
							//echo 'atualizar departamento ' . $departamentosLDAP['ou'][0] . '<hr>';


						} else {


							$fieldsAdd['codOrganizacao'] = session()->codOrganizacao;
							$fieldsAdd['paiDepartamento'] = 0;
							$fieldsAdd['descricaoDepartamento'] = $departamentosLDAP['name'][0];
							$fieldsAdd['abreviacaoDepartamento'] = $departamentosLDAP['description'][0];
							$fieldsAdd['ativo'] = 1;
							$fieldsAdd['autorAlteracao'] = session()->codPessoa;



							if ($departamentosLDAP['name'][0] !== NULL) {
								$codDepartamento = $this->DepartamentosModel->insert($fieldsAdd);
								$departamentosAdicionados++;
							}
							//echo 'adicionar departamento ' . $departamentosLDAP['ou'][0] . '<hr>';
						}
						foreach ($dadosLdapPessoa as $pessoaAtual) {

							if (is_array($pessoaAtual[$atributoChave])) {
								$contaAtual = $pessoaAtual[$atributoChave][0];
								$arrayPessoa = explode(",", $pessoaAtual['distinguishedname'][0]);

								$secaoPessoaLDAP = str_replace("ou=", "", str_replace("OU=", "", $arrayPessoa[1]));
							} else {
								$contaAtual = $pessoaAtual[$atributoChave];
							}
							$contaAtual = mb_strtolower($contaAtual);

							if ($secaoPessoaLDAP == $departamentosLDAP['ou'][0]) {

								$dadosDepartamento = $this->DepartamentosModel->pegaDepartamentoPorNome($departamentosLDAP['ou'][0]);
								$codDepartamento = $dadosDepartamento->codDepartamento;
								$this->PessoasModel->updateDepartamento($contaAtual, $codDepartamento);
							}
						}
					}
				}
			}
		}

		$resposta = "
<table>
<tr>
<td>Departamentos Adicionados</td>
<td>" . $departamentosAdicionados . "</td>
</tr>


<tr>
<td>Departamentos Atualizados</td>
<td>" . $departamentosAtualizados . "</td>
</tr>

</table>
";

		$response['success'] = true;
		$response['csrf_hash'] = csrf_hash();
		$response['messages'] = $resposta;
		return $this->response->setJSON($response);
	}



	public function exportarDepartamentos($codServidorLDAP = null)
	{

		//REGRA DE SICRONIZAÇÃO DO LDAP IMPORTAÇÃO AQUI

		//Espera mínima
		sleep(3);



		if ($codServidorLDAP !== NULL) {
			$codServidorLDAP = $codServidorLDAP;
		}

		if ($this->request->getPost('codServidorLDAP') !== NULL) {
			$codServidorLDAP = $this->request->getPost('codServidorLDAP');
		}


		$servidorLDAP = $this->ServicoLDAPModel->pegaPorCodigo($codServidorLDAP);


		$loginLDAP = $this->ServicoLDAPModel->conectaldap($servidorLDAP->loginLDAP, $servidorLDAP->senhaLDAP, $servidorLDAP->codServidorLDAP);
		$atributoChave = $servidorLDAP->atributoChave;

		$departamentosExistente = $this->DepartamentosModel->pegaDepartamentos();
		$dadosLdapDepartamentos = $this->ServicoLDAPModel->pegaDepartamentos($loginLDAP['tipoldap'], $orderby = 'sn', NULL);

		//print_r($dadosLdapDepartamentos);





		$departamentosAdicionados = 0;
		$departamentosAtualizados = 0;
		foreach ($departamentosExistente as $departamento) {

			if ($departamento->abreviacaoDepartamento !== NULL) {


				$dadosLdapDepartamentos = $this->ServicoLDAPModel->pegaUnidadesOrganizacionais($loginLDAP['tipoldap'], $departamento->descricaoDepartamento);


				if ($dadosLdapDepartamentos[0]["name"] !== NULL) {


					//ADICIONA ATRIBUTO CASO NÃO EXISTA
					if ($dadosLdapDepartamentos[0]["description"] == NULL) {
						$dadosUpdOU["description"] = $departamento->abreviacaoDepartamento;
						$sync = ldap_mod_add($loginLDAP['statusConexao'], $dadosLdapDepartamentos[0]['distinguishedname'][0], $dadosUpdOU);
					}



					//EXISTE TEM QUE ATUALIZAR ABREVIQÇÃO = ABREVIQÇÃO
					if ($dadosLdapDepartamentos[0]["description"] !== $departamento->abreviacaoDepartamento) {

						if ($departamento->abreviacaoDepartamento == NULL) {
							$departamento->abreviacaoDepartamento = " ";
						}
						$dadosUpdOU["description"] = $departamento->abreviacaoDepartamento;
						$sync = ldap_modify($loginLDAP['statusConexao'], $dadosLdapDepartamentos[0]['distinguishedname'][0], $dadosUpdOU);
					}

					//REMOMEOU O DEPARTAMENTO
					if ($dadosLdapDepartamentos[0]["name"] !== $departamento->descricaoDepartamento) {
						ldap_rename($loginLDAP['statusConexao'], $dadosLdapDepartamentos[0]['distinguishedname'][0], 'OU=' . $departamento->descricaoDepartamento, NULL, TRUE);
					}
				} else {

					//NÃO EXISTE TEM QUE ADICIONAR
					$dadosOU["objectClass"][0] = "top";
					$dadosOU["objectClass"][1] = "organizationalUnit";
					$dadosOU["ou"] = $departamento->descricaoDepartamento;
					$dadosOU["name"] = $departamento->descricaoDepartamento;
					if ($departamento->abreviacaoDepartamento == NULL) {
						$departamento->abreviacaoDepartamento = " ";
					}
					$dadosOU["description"] = $departamento->abreviacaoDepartamento;
					$sync = ldap_add($loginLDAP['statusConexao'], "OU=" . $departamento->descricaoDepartamento . "," . $loginLDAP['baseDN'], $dadosOU);
					$departamentosAdicionados++;
				}




				/*
			if in array
			
					//Adiciona Departamento

					
					
					*/
			}
		}





		if ($loginLDAP['status'] == 1) {
			$resposta = "
<table>
<tr>
<td>Departamentos Adicionados</td>
<td>" . $departamentosAdicionados . "</td>
</tr>


<tr>
<td>Departamentos Atualizados</td>
<td>" . $departamentosAtualizados . "</td>
</tr>

</table>
";

			$response['success'] = true;
			$response['csrf_hash'] = csrf_hash();
			$response['messages'] = $resposta;
			return $this->response->setJSON($response);
		}
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


	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('codDepartamento');

		if ($this->validation->check($id, 'required|numeric')) {


			$data = $this->DepartamentosModel->pegaDepartamento($id);

			return $this->response->setJSON($data);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}
	public function defineChefe()
	{

		$response = array();

		$fields['codDepartamento'] = $this->request->getPost('codDepartamento');
		$fields['ultimaAlteracao'] = date('Y-m-d H:i');
		$fields['codChefe'] = $this->request->getPost('codChefe');


		if ($this->validation->check($fields['codDepartamento'], 'required|numeric')) {


			if ($fields['codChefe'] !== NULL and $fields['codChefe'] !== "" and $fields['codChefe'] !== " ") {
				$data = $this->DepartamentosModel->pegaDepartamento($fields['codDepartamento']);

				if ($fields['codChefe'] !== $data->codChefe) {
					$this->DepartamentosModel->update($fields['codDepartamento'], $fields);
					$response['success'] = true;
					$response['messages'] = 'Chefe do departamento definido';
				}
			}

			return $this->response->setJSON($response);
		} else {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}



	public function add()
	{

		$response = array();



		$fields['codOrganizacao'] = session()->codOrganizacao;
		$fields['codDepartamento'] = $this->request->getPost('codDepartamento');
		$fields['paiDepartamento'] = $this->request->getPost('paiDepartamento');
		$fields['codTipoDepartamento'] = $this->request->getPost('codTipoDepartamento');
		$fields['descricaoDepartamento'] = $this->request->getPost('descricaoDepartamento');
		$fields['abreviacaoDepartamento'] = $this->request->getPost('abreviacaoDepartamento');
		$fields['telefone'] = $this->request->getPost('telefone');
		$fields['email'] = $this->request->getPost('email');
		$fields['ativo'] = 1;


		$this->validation->setRules([
			'paiDepartamento' => ['label' => 'Subordinação', 'rules' => 'max_length[11]'],
			'descricaoDepartamento' => ['label' => 'Departamento', 'rules' => 'required|max_length[100]'],
			'abreviacaoDepartamento' => ['label' => 'Abreviação', 'rules' => 'permit_empty|max_length[30]'],
			'telefone' => ['label' => 'Telefone', 'rules' => 'permit_empty|max_length[20]'],
			'email' => ['label' => 'Email', 'rules' => 'permit_empty|max_length[50]'],
			'ativo' => ['label' => 'Ativo', 'rules' => 'permit_empty|max_length[1]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->DepartamentosModel->insert($fields)) {


				$servidoresLDAP = $this->ServicoLDAPModel->pegaTudoAtivoActiveDirectory();

				if (count($servidoresLDAP) > 0) {

					foreach ($servidoresLDAP as $servidorLDAP) {
						$loginLDAP = $this->ServicoLDAPModel->conectaldap($servidorLDAP->loginLDAP, $servidorLDAP->senhaLDAP, $servidorLDAP->codServidorLDAP);

						$dadosOU["objectClass"][0] = "top";
						$dadosOU["objectClass"][1] = "organizationalUnit";
						$dadosOU["ou"] = $this->request->getPost('descricaoDepartamento');
						$dadosOU["name"] = $this->request->getPost('descricaoDepartamento');
						if ($this->request->getPost('abreviacaoDepartamento') == NULL) {
							$abreviacaoDepartamento = " ";
						} else {
							$abreviacaoDepartamento = $this->request->getPost('abreviacaoDepartamento');
						}
						$dadosOU["description"] = $abreviacaoDepartamento;
						$sync = @ldap_add($loginLDAP['statusConexao'], "OU=" . $this->request->getPost('descricaoDepartamento') . "," . $loginLDAP['baseDN'], $dadosOU);
					}
				}

				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['messages'] = $this->request->getPost('descricaoDepartamento');
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

		$departamentoAlterado = $this->DepartamentosModel->pegaDepartamento($this->request->getPost('codDepartamento'));
		$descricaoDepartamentoAnterior = $departamentoAlterado->descricaoDepartamento;
		$abreviacaoDepartamentoAnterior = $departamentoAlterado->abreviacaoDepartamento;
		$fields['codDepartamento'] = $this->request->getPost('codDepartamento');
		$fields['paiDepartamento'] = $this->request->getPost('paiDepartamento');
		$fields['codTipoDepartamento'] = $this->request->getPost('codTipoDepartamento');
		$fields['descricaoDepartamento'] = $this->request->getPost('descricaoDepartamento');
		$fields['abreviacaoDepartamento'] = $this->request->getPost('abreviacaoDepartamento');
		$fields['telefone'] = $this->request->getPost('telefone');
		$fields['email'] = $this->request->getPost('email');
		$fields['ativo'] = $this->request->getPost('ativo');


		$this->validation->setRules([
			'codDepartamento' => ['label' => 'codDepartamento', 'rules' => 'required|numeric|max_length[11]'],
			'paiDepartamento' => ['label' => 'Subordinação', 'rules' => 'max_length[11]'],
			'descricaoDepartamento' => ['label' => 'Departamento', 'rules' => 'required|max_length[100]'],
			'abreviacaoDepartamento' => ['label' => 'Abreviação', 'rules' => 'permit_empty|max_length[30]'],
			'telefone' => ['label' => 'Telefone', 'rules' => 'permit_empty|max_length[20]'],
			'email' => ['label' => 'Email', 'rules' => 'permit_empty|max_length[50]'],
			'ativo' => ['label' => 'Ativo', 'rules' => 'permit_empty|max_length[1]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->DepartamentosModel->update($fields['codDepartamento'], $fields)) {


				//ATUALIZA LDAP

				$servidoresLDAP = $this->ServicoLDAPModel->pegaTudoAtivoActiveDirectory();

				if (count($servidoresLDAP) > 0) {

					foreach ($servidoresLDAP as $servidorLDAP) {
						$loginLDAP = $this->ServicoLDAPModel->conectaldap($servidorLDAP->loginLDAP, $servidorLDAP->senhaLDAP, $servidorLDAP->codServidorLDAP);
						$dadosLdapDepartamentos = $this->ServicoLDAPModel->pegaUnidadesOrganizacionais($loginLDAP['tipoldap'], $descricaoDepartamentoAnterior);

						if ($fields['abreviacaoDepartamento'] == NULL) {
							$fields['abreviacaoDepartamento'] == " ";
						}
						//ADICIONA ATRIBUTO CASO NÃO EXISTA
						if ($dadosLdapDepartamentos[0]["description"] == NULL) {
							$dadosUpdOU["description"] = $fields['abreviacaoDepartamento'];
							$sync = @ldap_mod_add($loginLDAP['statusConexao'], $dadosLdapDepartamentos[0]['distinguishedname'][0], $dadosUpdOU);
						}



						//NÃO EXISTE TEM QUE ATUALIZAR DESCRIÇÃO = ABREVIQÇÃO
						if ($dadosLdapDepartamentos[0]["description"] !== $abreviacaoDepartamentoAnterior) {
							$dadosUpdOU["description"] = $fields['abreviacaoDepartamento'];
							$sync = @ldap_modify($loginLDAP['statusConexao'], $dadosLdapDepartamentos[0]['distinguishedname'][0], $dadosUpdOU);
						}

						//REMOMEOU O DEPARTAMENTO
						if ($dadosLdapDepartamentos[0]["name"] !== $descricaoDepartamentoAnterior) {
							@ldap_rename($loginLDAP['statusConexao'], $dadosLdapDepartamentos[0]['distinguishedname'][0], 'OU=' . $fields['descricaoDepartamento'], NULL, TRUE);
						}
					}
				}



				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['messages'] = 'Atualizado com sucesso';
			} else {

				$response['success'] = false;
				$response['csrf_hash'] = csrf_hash();
				$response['messages'] = 'Erro na atualização!';
			}
		}

		return $this->response->setJSON($response);
	}
	public function editClassificacaoDiarias()
	{

		$response = array();
		$fields['codDepartamento'] = $this->request->getPost('codDepartamento');
		$fields['codTaxaServico'] = $this->request->getPost('codTaxaServico');
		$fields['codTaxaServicoAcompanhante'] = $this->request->getPost('codTaxaServicoAcompanhante');


		$this->validation->setRules([
			'codDepartamento' => ['label' => 'codDepartamento', 'rules' => 'required|numeric|max_length[11]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->listErrors();
		} else {

			if ($this->DepartamentosModel->update($fields['codDepartamento'], $fields)) {



				$response['success'] = true;
				$response['csrf_hash'] = csrf_hash();
				$response['messages'] = 'Atualizado com sucesso';
			} else {

				$response['success'] = false;
				$response['csrf_hash'] = csrf_hash();
				$response['messages'] = 'Erro na atualização!';
			}
		}

		return $this->response->setJSON($response);
	}

	public function remove()
	{
		$response = array();

		$id = $this->request->getPost('codDepartamento');
		$departamento = $this->DepartamentosModel->pegaDepartamento($id);

		$pessoas = $this->DepartamentosModel->pegaPessoasNoDepartamento($id);

		if ($pessoas !== NULL) {
			$response['pessoas'] = true;
		} else {

			if (!$this->validation->check($id, 'required|numeric')) {

				throw new \CodeIgniter\Exceptions\PageNotFoundException();
			} else {

				if ($this->DepartamentosModel->where('codDepartamento', $id)->delete()) {




					$servidoresLDAP = $this->ServicoLDAPModel->pegaTudoAtivoActiveDirectory();

					if (count($servidoresLDAP) > 0) {

						foreach ($servidoresLDAP as $servidorLDAP) {
							$loginLDAP = $this->ServicoLDAPModel->conectaldap($servidorLDAP->loginLDAP, $servidorLDAP->senhaLDAP, $servidorLDAP->codServidorLDAP);

							$dadosLdapDepartamentos = $this->ServicoLDAPModel->pegaUnidadesOrganizacionais($loginLDAP['tipoldap'], $departamento->descricaoDepartamento);


							@ldap_delete($loginLDAP['statusConexao'], $dadosLdapDepartamentos[0]['distinguishedname'][0]);
						}
					}



					$response['success'] = true;
					$response['csrf_hash'] = csrf_hash();
					$response['messages'] = 'Deletado com sucesso';
				} else {

					$response['success'] = false;
					$response['messages'] = 'Erro na deleção!';
				}
			}
		}

		return $this->response->setJSON($response);
	}
}
