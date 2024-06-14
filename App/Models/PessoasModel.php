<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class PessoasModel extends Model
{

	protected $table = 'sis_pessoas';
	protected $primaryKey = 'codPessoa';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codPlano', 'codClasse', 'codPessoa', 'codPerfilPadrao', 'codOrganizacao', 'ordenacao', 'pai', 'conta', 'codFuncao', 'codCargo', 'dn', 'nomeExibicao', 'nomeCompleto', 'nomePrincipal', 'identidade', 'cpf', 'emailFuncional', 'emailPessoal', 'codEspecialidade', 'telefoneTrabalho', 'celular', 'endereco', 'aceiteTermos', 'hashTcms', 'senha', 'dataSenha', 'historicoSenhas', 'ativo', 'ipRequisitante', 'notificado', 'dataCriacao', 'dataAtualizacao', 'dataInicioEmpresa', 'dataNascimento', 'codDepartamento', 'nrEndereco', 'codMunicipioFederacao', 'reservadoSimNao', 'reservadoTexto100', 'reservadoNumero', 'cep', 'fotoPerfil', 'informacoesComplementares', 'senhaResincLDAP', 'codTipoMicrosoft'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;


	public function pegaTudo()
	{

		$codOrganizacao = session()->codOrganizacao;
		if ($codOrganizacao == NULL or $codOrganizacao == '') {
			$configuracao = config('App');
			session()->set('codOrganizacao', $configuracao->codOrganizacao);
			$codOrganizacao = $configuracao->codOrganizacao;
		}
		$query = $this->db->query('select * from sis_pessoas p left join sis_departamentos d on d.codDepartamento=p.codDepartamento  where p.codClasse=1 and p.codOrganizacao = ' . $codOrganizacao . ' order by p.codCargo asc');
		return $query->getResult();
	}

	public function contas()
	{
		$codOrganizacao = session()->codOrganizacao;
		if ($codOrganizacao !== NULL) {
			$query = $this->db->query('select conta from sis_pessoas p left join sis_departamentos d on d.codDepartamento=p.codDepartamento  where p.codClasse=1 and  p.codOrganizacao = ' . $codOrganizacao . ' order by p.codCargo asc');
		} else {
			$query = $this->db->query('select conta from sis_pessoas p left join sis_departamentos d on d.codDepartamento=p.codDepartamento  where p.codClasse=1 order by p.codCargo asc');
		}
		return $query->getResult();
	}


	public function pegaDepartamentoPessoa($conta)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from sis_pessoas p left join sis_departamentos d on d.codDepartamento=p.codDepartamento  where p.codClasse=1  and p.conta ="' . $conta . '" and p.codOrganizacao = ' . $codOrganizacao . ' order by p.codCargo asc');
		return $query->getRow();
	}

	public function pegaPorCodPlano($codPlano)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from sis_pessoas p left join sis_departamentos d on d.codDepartamento=p.codDepartamento  where p.codClasse=1  and p.codPlano ="' . $codPlano . '" and p.codOrganizacao = ' . $codOrganizacao . ' order by p.codCargo asc');
		return $query->getRow();
	}


	public function pegaPorCodPlanoNome($codPlano)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from sis_pessoas p left join sis_departamentos d on d.codDepartamento=p.codDepartamento  where p.codClasse=1  and p.codPlano ="' . $codPlano . '" and p.codOrganizacao = ' . $codOrganizacao . ' order by p.codCargo asc');
		return $query->getRow();
	}


	public function pegaPessoaPorLogin($login)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from ' . $this->table . ' where codClasse=1  and ativo=1 and codOrganizacao = ' . $codOrganizacao . ' and conta = "' . $login . '"  order by codCargo asc');
		return $query->getRow();
	}


	public function aniversariantesHoje()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from sis_pessoas p 
		left join sis_departamentos d on d.codDepartamento = p.codDepartamento
		where p.codClasse=1  and p.ativo=1 and p.codOrganizacao = ' . $codOrganizacao . ' and DATE_FORMAT(p.dataNascimento, "%m-%d")=DATE_FORMAT(NOW(), "%m-%d")  order by p.codCargo asc');
		return $query->getResult();
	}
	
	public function aniversariantesNaDataReferencia($data = NULL)
	{
		$query = $this->db->query('select * from sis_pessoas p 
		left join sis_departamentos d on d.codDepartamento = p.codDepartamento
		where p.codClasse=1  and p.ativo=1  and DATE_FORMAT(p.dataNascimento, "%m-%d")="'.$data.'"  order by p.codCargo asc');
		return $query->getResult();
	}

	public function aniversariantes4Dias()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('
		select p.codPessoa,DATE_FORMAT(p.dataNascimento, "%m-%d") as dia,DAYOFWEEK(p.dataNascimento) as diaDaSemana,p.dataNascimento,p.nomeExibicao, p.emailPessoal,p.celular,d.descricaoDepartamento,d.abreviacaoDepartamento from sis_pessoas p 
		left join sis_departamentos d on d.codDepartamento = p.codDepartamento
		where p.codClasse=1  and p.ativo=1 and p.codOrganizacao = ' . $codOrganizacao . ' and DATE_FORMAT(p.dataNascimento, "%m-%d")>=DATE_FORMAT(NOW(), "%m-%d")  and DATE_FORMAT(p.dataNascimento, "%m-%d")<=DATE_FORMAT( DATE_ADD(NOW(), INTERVAL 11 DAY), "%m-%d") order by dia asc,p.codCargo asc');
		return $query->getResult();
	}

	public function pegaPessoaPorcpf($cpf)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select codPessoa,nomeExibicao,nomeCompleto,identidade,cpf,emailFuncional, emailPessoal, telefoneTrabalho,celular,cep,dataNascimento from ' . $this->table . ' where codClasse=1  and codOrganizacao = ' . $codOrganizacao . ' and trim(replace(replace(cpf,".",""),"-","")) = "' . $cpf . '" order by codCargo asc');
		return $query->getRow();
	}


	public function pegaPessoaPorcpfOuNomeCompleto($cpf, $nomeCompleto, $nomeCompletoSemAcento)
	{
		$codOrganizacao = session()->codOrganizacao;

		if ($cpf == NULL) {
			$query = $this->db->query('select codPessoa,nomeCompleto,identidade,cpf,emailFuncional, emailPessoal, telefoneTrabalho,celular,cep,dataNascimento from ' . $this->table . ' where codClasse=1  and codOrganizacao = ' . $codOrganizacao . ' and (UPPER(TRIM(nomeCompleto)) = "' . $$nomeCompletoSemAcento . '" or UPPER(TRIM(nomeCompleto)) = "' . $nomeCompleto . '" ) order by codCargo asc');
		} else {
			$query = $this->db->query('select codPessoa,nomeCompleto,identidade,cpf,emailFuncional, emailPessoal, telefoneTrabalho,celular,cep,dataNascimento from ' . $this->table . ' where codClasse=1  and codOrganizacao = ' . $codOrganizacao . ' and (UPPER(TRIM(nomeCompleto)) = "' . $nomeCompletoSemAcento . '" or UPPER(TRIM(nomeCompleto)) = "' . $nomeCompleto . '"  or replace(replace(cpf,".",""),"-","") = "' . $cpf . '" ) order by codCargo asc');
		}
		return $query->getRow();
	}

	public function pegaMembroGrupoPorLogin($login, $codGrupo)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select p.*,gp.codPessoa as existe,gp.codGrupo from sis_pessoas p left join sis_grupospessoas gp on gp.codPessoa=p.codPessoa and gp.codGrupo=' . $codGrupo . ' where p.codClasse=1  and p.codOrganizacao = ' . $codOrganizacao . ' and p.conta = "' . $login . '"  order by p.codCargo asc');
		return $query->getRow();
	}

	public function pegaPessoaPorCodPessoa($codPessoa)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select p.*, o.descricao,o.siglaOrganizacao from sis_pessoas p		
		join sis_organizacoes o on o.codOrganizacao=p.codOrganizacao
		where p.codClasse=1 and p.codOrganizacao = ' . $codOrganizacao . ' and p.codPessoa = "' . $codPessoa . '"  order by p.codCargo asc');
		return $query->getRow();
	}

	public function pegaPessoaDepartamento($codPessoa)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from sis_pessoas p
		join sis_departamentos d on p.codDepartamento=d.codDepartamento where p.codClasse=1 and p.codOrganizacao = ' . $codOrganizacao . ' and p.codPessoa = "' . $codPessoa . '"  order by p.codCargo asc');
		return $query->getRow();
	}

	public function organizacaoPessoa($codPessoa)
	{
		$query = $this->db->query('select * from sis_organizacoes o join sis_pessoas p on p.codOrganizacao=o.codOrganizacao where p.codClasse=1 and p.codPessoa = ' . $codPessoa . ' order by p.codCargo asc');
		return $query->getRow();
	}


	public function pega_pessoas()
	{
		$filtro = '';

		if (session()->filtroPessoa !== null and session()->filtroPessoa !== '') {
			$filtro = ' and p.codPessoa = ' . session()->filtroPessoa . ' ';
		}
		
		$filtroDesativados = ' and p.ativo in (0,1) ';

		if (session()->filtroDesativados == 0) {
			$filtroDesativados = ' and p.ativo in (0,1) ';
		}
		if (session()->filtroDesativados == 1) {
			$filtroDesativados = ' and p.ativo in (0) ';
		}
		

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select mi.descricaoMotivoInativacao,p.*,d.descricaoDepartamento,f.descricaoFuncao,f.siglaFuncao,g.siglaCargo,e.descricaoEspecialidade
		from sis_pessoas p 
		left join sis_departamentos d on p.codDepartamento=d.codDepartamento and p.codOrganizacao=d.codOrganizacao
		left join sis_funcoes f on f.codFuncao=p.codFuncao and f.codOrganizacao=p.codOrganizacao 
		left join sis_cargos g on g.codCargo=p.codCargo and g.codOrganizacao=p.codOrganizacao 
		left join sis_especialidades e on e.codEspecialidade=p.codEspecialidade and e.codOrganizacao=p.codOrganizacao 
		left join sis_motivosinativacao mi on mi.codMotivoInativacao=p.codMotivoInativo
		where p.codClasse=1 and p.codOrganizacao = ' . $codOrganizacao . $filtro . $filtroDesativados . '
		order by p.ativo desc,p.codCargo asc, f.ordenacaoFuncao asc, p.dataInicioEmpresa asc');
		return $query->getResult();
	}


	public function updateSenhaAdmin($senha)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('update sis_organizacoes set senhaAdmin="' . $senha . '" where codOrganizacao=' . $codOrganizacao);
		return 1;
	}


	public function updateFotoPerfil($codPessoa, $fotoPerfil)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('update sis_pessoas set fotoPerfil="' . $fotoPerfil . '" where codPessoa=' . $codPessoa . ' and codOrganizacao=' . $codOrganizacao);
		return 1;
	}

	public function updateDepartamento($conta, $codDepartamento)
	{
		$codOrganizacao = session()->codOrganizacao;
		if ($conta !== NULL) {
			$query = $this->db->query('update sis_pessoas set codDepartamento = ' . $codDepartamento . ' where conta="' . $conta . '" and codOrganizacao=' . $codOrganizacao);
		}
		return 1;
	}

	public function updateDepartamentoPorCodPessoa($codPessoa, $codDepartamento)
	{
		$codOrganizacao = session()->codOrganizacao;
		if ($codPessoa !== NULL) {
			$query = $this->db->query('update sis_pessoas set codDepartamento = ' . $codDepartamento . ' where codPessoa="' . $codPessoa . '" and codOrganizacao=' . $codOrganizacao);
		}
		return 1;
	}



	public function updatePessoaFromLDAP($conta, $dados)
	{

		$db      = \Config\Database::connect();
		$builder = $db->table("sis_pessoas");
		$builder->where('codOrganizacao', session()->codOrganizacao);
		$builder->where('conta', $conta);
		$builder->update($dados);
		return 1;
	}



	public function desativarPessoa($codPessoa, $dados)
	{

		$db      = \Config\Database::connect();
		$builder = $db->table("sis_pessoas");
		$builder->where('codOrganizacao', session()->codOrganizacao);
		$builder->where('codPessoa', $codPessoa);
		$builder->update($dados);
		return 1;
	}


	public function reativarPessoa($codPessoa, $dados)
	{

		$db      = \Config\Database::connect();
		$builder = $db->table("sis_pessoas");
		$builder->where('codOrganizacao', session()->codOrganizacao);
		$builder->where('codPessoa', $codPessoa);
		$builder->update($dados);
		return 1;
	}


	public function atualizaCodPlano($cpf, $codPlano)
	{
		$codOrganizacao = session()->codOrganizacao;
		if ($cpf !== NULL and $codPlano !== NULL and $codOrganizacao !== NULL) {
			$query = $this->db->query('update sis_pessoas set codPlano = "' . $codPlano . '" where cpf="' . $cpf . '" and codOrganizacao=' . $codOrganizacao);
		}
		return 1;
	}


	public function insertFromLDAP($data)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table("sis_pessoas");
		$builder->insert($data);
	}

	public function insereLookupPessoas($data)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table("tabelalookupapolo");
		$builder->insert($data);
	}



	public function updateLookupPessoas($codLookup, $dados)
	{

		$db      = \Config\Database::connect();
		$builder = $db->table("tabelalookupapolo");
		$builder->where('codLookup', $codLookup);
		$builder->update($dados);
		return 1;
	}


	public function agendasApolo()
	{
		$apolo = \Config\Database::connect('apolo', false);
		//$query = $apolo->query('SELECT * FROM amb_eagendas where DATE_FORMAT(gdh, "%Y-%m-%d") >= DATE_FORMAT(NOW(), "%Y-%m-%d")');
		$query = $apolo->query('select * FROM amb_eagendas where DATE_FORMAT(gdh, "%Y-%m-%d") >= "2022-07-01"');

		return $query->getResult();
	}

	public function pessoasApolo()
	{
		$apolo = \Config\Database::connect('apolo', false);
		$query = $apolo->query('select distinct x.prec_cp,p.* from amb_prontuarios_n p join ( SELECT DISTINCT prec_cp FROM amb_eagendas UNION ALL SELECT DISTINCT prec_cp from pes_especialidade )x on p.cod_ben=x.prec_cp');
		return $query->getResult();
	}

	public function usuariosAgendaApolo()
	{
		//RELACIONA AGENDAS COM A TABELA DE CONTAS DOS MÉDICOS
		$apolo = \Config\Database::connect('apolo', false);
		$query = $apolo->query('SELECT distinct a.prec_cp,a.nome_esp,u.login,u.nome FROM amb_eagendas a join sis_usuarios u on a.prec_cp=u.login');
		return $query->getResult();
	}

	public function usuariosEvolucoes()
	{
		//RELACIONA AGENDAS COM A TABELA DE CONTAS DOS MÉDICOS
		$apolo = \Config\Database::connect('apolo', false);
		$query = $apolo->query('SELECT distinct a.resp as prec_cp,u.login as loginUsuario,u.nome as nomeUsuario
		FROM amb_atendimentos a join sis_usuarios u on a.resp=u.login
		WHERE a.resp not in
		(select prec from sandra.tabelalookupapolo)');
		return $query->getResult();
	}

		public function prescricoesEvolucoesPacientes()
	{
		//RELACIONA AGENDAS COM A TABELA DE CONTAS DOS MÉDICOS
		$apolo = \Config\Database::connect('apolo', false);
		$query = $apolo->query('SELECT distinct a.resp as prec_cp,u.login as loginUsuario,u.nome as nomeUsuario,p.* 
		FROM amb_atdevolucao a join sis_usuarios u on a.resp=u.login
		left join amb_prontuarios_n p on p.cod_ben=a.resp');
		return $query->getResult();
	}
	public function pessoasLookup()
	{

		$query = $this->db->query('select * from tabelalookupapolo');
		return $query->getResult();
	}

	public function pessoasLookupOrfans()
	{

		$query = $this->db->query('select * from tabelalookupapolo where codPessoa=0');
		return $query->getResult();
	}

	public function pega_codDepartamento($codsolicitante)
	{

		$query = $this->db->query('select *
		from sis_pessoas p where p.codPessoa=' . $codsolicitante);
		return $query->getRow();
	}


	public function contasErradas()
	{

		$query = $this->db->query('select *
		from sis_pessoas p where p.conta like "% %"');
		return $query->getResult();
	}


	public function listaDropDownResponsaveis()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select p.codPessoa as id, p.nomeExibicao as text from sis_pessoas p where codOrganizacao = ' . $codOrganizacao . ' and  p.codClasse=1 and p.ativo=1 and p.nomeExibicao is not null and codDepartamento in (select distinct codDepartamentoResponsavel from sis_equipesuporte) order by p.codCargo asc');
		return $query->getResult();
	}

	public function pegarVCARD()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select o.siglaOrganizacao,p.nomeexibicao,p.nomeexibicao,p.datanascimento,UPPER(d.abreviacaoDepartamento) as secao,UPPER(d.abreviacaoDepartamento) as quadro,p.emailpessoal,p.emailfuncional,p.celular,p.telefonetrabalho,p.endereco 
		FROM sis_pessoas p 
		left join sis_departamentos d on p.codDepartamento=d.codDepartamento 
		join sis_organizacoes o on o.codOrganizacao=p.codOrganizacao
		where  codClasse=1 and celular is not null');
		return $query->getResult();
	}


	public function listaDropDownSolicitante()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('
		select p.codPessoa as id, p.nomeExibicao as text from sis_pessoas p  where codOrganizacao = ' . $codOrganizacao . ' and  p.codClasse=1 and p.ativo=1 and p.nomeExibicao is not null order by p.codCargo asc');
		return $query->getResult();
	}

	public function listaTodasPessoasAteDesativados()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('
		select p.codPessoa as id, p.nomeExibicao as text from sis_pessoas p  where codOrganizacao = ' . $codOrganizacao . ' and  p.codClasse=1 and p.nomeExibicao is not null order by p.codCargo asc,p.ativo desc');
		return $query->getResult();
	}

	public function listaDropDownPessoas()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('
		select p.codPessoa as id, concat(p.nomeExibicao," - ",p.nomeCompleto) as text from sis_pessoas p where codOrganizacao = ' . $codOrganizacao . ' and  p.codClasse=1 and p.ativo=1 and p.nomeExibicao is not null  and p.nomeCompleto is not null  order by p.codCargo asc');
		return $query->getResult();
	}

	public function listaDropDownMotivosInativos()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('
		select codMotivoInativacao as id, descricaoMotivoInativacao as text from sis_motivosinativacao');
		return $query->getResult();
	}
}
