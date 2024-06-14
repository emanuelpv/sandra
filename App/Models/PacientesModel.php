<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class PacientesModel extends Model
{

	protected $table = 'sis_pacientes';
	protected $primaryKey = 'codPaciente';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codMotivoInativo', 'observacoes', 'codOrganizacao', 'codPaciente', 'numeroContato', 'codTipoContato', 'sistema', 'dataImportacao', 'nomeContato', 'validade', 'codOm', 'codTipoBeneficiario', 'apolo', 'autor', 'autorAtualizacao', 'prontAnt', 'isindet', 'omVinc', 'nomePai', 'nomeMae', 'validade', 'situacao', 'codTipoSanguineo', 'codStatusCadastroPaciente', 'codRaca', 'sexo', 'codForca', 'codPlano', 'pronNR', 'codProntuario', 'pronID', 'codClasse', 'codPaciente', 'codPerfilPadrao', 'codOrganizacao', 'ordenacao', 'pai', 'conta', 'codFuncao', 'codCargo', 'dn', 'nomeExibicao', 'nomeCompleto', 'nomePrincipal', 'identidade', 'cpf', 'emailFuncional', 'emailPessoal', 'codEspecialidade', 'telefoneTrabalho', 'celular', 'endereco', 'aceiteTermos', 'hashTcms', 'senha', 'dataSenha', 'historicoSenhas', 'ativo', 'ipRequisitante', 'notificado', 'dataCriacao', 'dataAtualizacao', 'dataInicioEmpresa', 'dataNascimento', 'codDepartamento', 'nrEndereco', 'codMunicipioFederacao', 'reservadoSimNao', 'reservadoTexto100', 'reservadoNumero', 'cep', 'fotoPerfil', 'informacoesComplementares', 'senhaResincLDAP', 'codTipoMicrosoft'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;

	public function verificaExistencia($pron_id, $pron_nr)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from sis_pacientes 
		where codOrganizacao = ' . $codOrganizacao  . ' and pronID="' . $pron_id . '" and pronNR="' . $pron_nr . '" limit 1');
		return $query->getRow();
	}


	public function verificaExistenciaSIGH($codPaciente)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from sis_pacientes 
		where codOrganizacao = ' . $codOrganizacao  . ' and codPaciente=' . $codPaciente);
		return $query->getRow();
	}




	public function verificaExistenciaPorIDNR($pronID, $pronNR)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from sis_pacientes 
		where codOrganizacao = ' . $codOrganizacao  . ' and pronID="' . $pronID . '" and pronNR="' . $pronNR . '" limit 1');
		return $query->getRow();
	}

	public function pegaTudo()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from sis_pacientes p where p.codClasse=2 and p.codOrganizacao = ' . $codOrganizacao . ' order by p.codCargo asc');
		return $query->getResult();
	}
	public function medicoMaiorAgendaHj()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select codEspecialista, codEspecialidade, DATE_FORMAT(dataInicio, "%Y/%m/%d"),count(*) as total from amb_agendamentos 
		where DATE_FORMAT(dataInicio, "%Y/%m/%d")=CURDATE() and codStatus=1
		group by codEspecialista,codEspecialidade
		order by total desc limit 1');
		return $query->getRow();
	}
	public function pegaHistoricoPaciente($codAtendimento)
	{

		$filtro = "";

		$codEspecialidade = session()->filtroEspecialidadeFiltroHistoricoPaciente;
		$codTipoAtendimento = session()->filtroTipoAtendimentoFiltroHistoricoPaciente;


		if ($codEspecialidade !== NULL and $codEspecialidade !== "") {
			$filtro .= " and a.codEspecialidade=" . $codEspecialidade;
		}

		if ($codTipoAtendimento !== NULL and $codTipoAtendimento !== "") {
			$filtro .= " and a.codTipoAtendimento=" . $codTipoAtendimento;
		}




		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select a.*,pe.nomeExibicao,lo.descricaoLocalAtendimento,e.descricaoEspecialidade, ta.descricaoTipoAtendimento,ass.descricaoStatusAtendimento,aa.hda, aa.queixaPrincipal,aa.hmp,aa.historiaMedicamentos,aa.historiaAlergias ,aa.outrasInformacoes,d.descricaoDepartamento
		from amb_atendimentos a		
		left join amb_atendimentostipos ta on ta.codTipoAtendimento = a.codTipoAtendimento
		left join amb_atendimentosanamnese aa on aa.codAtendimento=a.codAtendimento 
		left join amb_atendimentosstatus ass on ass.codStatusAtendimento=a.codStatus 
		left join amb_atendimentoslocais lo on lo.codLocalAtendimento=a.codLocalAtendimento 
		left join sis_departamentos d on d.codDepartamento=lo.codDepartamento
		left join amb_atendimentosparametrosclinicos pc on pc.codAtendimento=a.codAtendimento
		left join sis_especialidades e on e.codEspecialidade=a.codEspecialidade 
		left join sis_pessoas pe on pe.codPessoa=a.codAutor 
		where a.codPaciente in (select distinct codPaciente from amb_atendimentos where codAtendimento="' . $codAtendimento . '" and codOrganizacao = ' . $codOrganizacao . ')' . $filtro . ' 
		order by dataCriacao desc');
		return $query->getResult();
	}
	public function pegaMedicamentosUsoContinuo($codPaciente)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select distinct mc.descricaoMedicamento
		from sis_pacientesmedicamentoscontinuo mc
		where mc.codPaciente="' . $codPaciente . '" order by mc.descricaoMedicamento asc');
		return $query->getResult();
	}
	public function pegaAlergias($codPaciente)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select distinct mc.descricaoAlergenico
		from sis_pacientesalergias mc
		where mc.codPaciente="' . $codPaciente . '" order by mc.descricaoAlergenico asc');
		return $query->getResult();
	}
	public function pegaHistoricoCondutas($codAtendimento)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select c.*,p.nomeExibicao
		from amb_atendimentoscondutas c 
		left join sis_pessoas p on c.codAutor=p.codPessoa
		where c.codAtendimento="' . $codAtendimento . '" order by c.dataCriacao desc');
		return $query->getResult();
	}
	public function pegaHistoricoEvolucoes($codAtendimento)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select e.*,p.nomeExibicao
		from amb_atendimentosevolucoes e 
		left join sis_pessoas p on e.codAutor=p.codPessoa
		where e.codAtendimento="' . $codAtendimento . '" order by e.dataCriacao desc');
		return $query->getResult();
	}
	public function pegaHistoricoPrescricoes($codAtendimento)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select ap.*,p.nomeExibicao
		from amb_atendimentosprescricoes ap 
		left join sis_pessoas p on ap.codAutor=p.codPessoa
		where ap.codAtendimento="' . $codAtendimento . '" order by ap.dataCriacao desc');
		return $query->getResult();
	}
	public function pegaHistoricoPrescricoesMedicamentos($codAtendimentoPrescricao)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select ap.*,i.descricaoItem,u.descricaoUnidade,v.descricaoVia
		from amb_atendimentosprescricoesmedicamentos ap 
		left join sis_itensfarmacia i on ap.codMedicamento=i.codItem
		left join sis_unidades u on ap.und=u.codUnidade
		left join sis_vias v on v.codVia=ap.via
		where ap.codAtendimentoPrescricao="' . $codAtendimentoPrescricao . '" order by ap.dataCriacao desc');
		return $query->getResult();
	}
	public function pegaPacientePorCPFCadben($paciente)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select p.*,o.descricao,o.siglaOrganizacao 
		from sis_pacientes p 	
		join sis_organizacoes o on o.codOrganizacao=p.codOrganizacao
		where p.codClasse=2 and p.codOrganizacao = ' . $codOrganizacao . ' 
		and (p.cpf = "' . $paciente . '" or p.codPlano="' . $paciente . '")');
		return $query->getRow();
	}


	public function faltaRecuperarCodPlano()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from sis_pacientes p where p.recuperado is null');
		return $query->getResult();
	}

	public function contas()
	{
		$codOrganizacao = session()->codOrganizacao;
		if ($codOrganizacao !== NULL) {
			$query = $this->db->query('select conta from sis_pacientes p where p.codClasse=2 and p.codOrganizacao = ' . $codOrganizacao . ' order by p.codCargo asc');
		} else {
			$query = $this->db->query('select conta from sis_pacientes p where p.codClasse=2 order by p.codCargo asc');
		}
		return $query->getResult();
	}


	public function pegaDepartamentoPaciente($conta)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from sis_pacientes p where p.codClasse=2  and p.conta ="' . $conta . '" and p.codOrganizacao = ' . $codOrganizacao . ' order by p.codCargo asc');
		return $query->getRow();
	}

	public function pegaPacientePorLogin($login)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from ' . $this->table . ' where codClasse=2  and codOrganizacao = ' . $codOrganizacao . ' and (conta = "' . $login . '" or replace(replace(cpf,".",""),"-","") = "' . $login . '" or cpf = "' . $login . '" or codPlano = "' . $login . '" or emailPessoal="' . $login . '") order by codCargo asc');
		return $query->getRow();
	}

	public function pegaNomeCpfOuPREC($nomeCpfPREC)
	{
		$filtroNome = str_replace(" ", "%", $nomeCpfPREC);
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from sis_pacientes where codClasse=2  and codOrganizacao = ' . $codOrganizacao . ' and (nomeCompleto like "%' . $filtroNome . '%" or replace(replace(cpf,".",""),"-","") = "' . $nomeCpfPREC . '" or codPlano = "' . $nomeCpfPREC . '") order by codCargo asc');
		return $query->getResult();
	}


	public function trocaSenha($codPaciente = null, $senha = null, $confirmacao = null)
	{


		$response = array();


		if ($codPaciente == null) {
			$codPaciente = $this->request->getPost('codPaciente');
		} else {
			$codPaciente = $codPaciente;
		}

		if ($senha == null) {
			$senha = $this->request->getPost('senha');
		} else {
			$senha = $senha;
		}


		if ($confirmacao == null) {
			$confirmacao = $this->request->getPost('confirmacao');
		} else {
			$confirmacao = $confirmacao;
		}


		$paciente = $this->organizacaoPaciente($codPaciente);


		$fields['codPaciente'] = $codPaciente;
		$fields['senha1'] = $senha;
		$fields['senha2'] = $confirmacao;


		$chave = $paciente->chaveSalgada;
		$tipo_cifra = 'des';

		//CRIPTOGRAFIA DE SENHA

		$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($tipo_cifra));
		$encrypted = openssl_encrypt($fields['senha1'], $tipo_cifra, $chave, 0, $iv);
		$senhaResincLDAP = base64_encode($encrypted . '::' . $iv);



		$statusTrocaSenha = "";

		//TROCA SENHA 
		$senha = hash("sha256", $senha . $paciente->chaveSalgada);
		$fields['senha'] = $senha;
		$fields['dataSenha'] = date('Y-m-d H:i');
		$fields['dataAtualizacao'] = date('Y-m-d H:i');



		$query = $this->db->query('
		update sis_pacientes 
		set senha="' . $senha . '",
		dataSenha="' . $fields['dataSenha'] . '",
		dataAtualizacao="' . $fields['dataAtualizacao'] . '"
		where codPaciente=' . $codPaciente);

		if ($query == true) {
			return 1;
		} else {
			return 0;
		}
	}



	public function pegaPacientePorCpf($cpf = null)
	{

		$codOrganizacao = session()->codOrganizacao;
		if ($codOrganizacao == NULL or $codOrganizacao == '') {
			$configuracao = config('App');
			session()->set('codOrganizacao', $configuracao->codOrganizacao);
			$codOrganizacao = $configuracao->codOrganizacao;
		}
		$query = $this->db->query('select TIMESTAMPDIFF (YEAR, dataNascimento,CURDATE()) as idade,nomeExibicao, codPaciente,nomeCompleto,identidade,cpf,emailFuncional, emailPessoal, telefoneTrabalho,celular,cep,codProntuario,codPlano, nomeMae, nomePai,dataNascimento,fotoPerfil from sis_pacientes where codClasse=2  and codOrganizacao = ' . $codOrganizacao . ' and replace(replace(cpf,".",""),"-","") = "' . $cpf . '" order by codCargo asc');
		return $query->getRow();
	}


	public function pegaPacientePorCodPlano($codPlano = null)
	{

		$codOrganizacao = session()->codOrganizacao;
		if ($codOrganizacao == NULL) {

			$configuracao = config('App');
			session()->set('codOrganizacao', $configuracao->codOrganizacao);
			$codOrganizacao = $configuracao->codOrganizacao;
		}
		$query = $this->db->query('select TIMESTAMPDIFF (YEAR, dataNascimento,CURDATE()) as idade,nomeExibicao, codPaciente,nomeCompleto,identidade,cpf,emailFuncional, emailPessoal, telefoneTrabalho,celular,cep,codProntuario,codPlano, nomeMae, nomePai,dataNascimento,fotoPerfil from sis_pacientes where codClasse=2  and codOrganizacao = ' . $codOrganizacao . ' and codPlano = "' . $codPlano . '" order by codCargo asc');
		return $query->getRow();
	}


	public function pegaCodConselho($conselho)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select codConselho from sis_conselhos where nomeConselho = "' . $conselho . '"');
		return $query->getRow()->codConselho;
	}


	public function emissaoCartao($codPaciente)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select pa.*, g.*,tb.*,DATE_FORMAT(pa.validade, "%d/%m/%Y") as validadeProntuario
		from sis_pacientes pa 
		left join sis_cargos g on g.codCargo=pa.codCargo and g.codOrganizacao=pa.codOrganizacao 
		left join sis_tipobeneficiario tb on tb.codTipobeneficiario=pa.codTipobeneficiario 
		where pa.codClasse=2 and pa.codOrganizacao = ' . $codOrganizacao . ' and pa.codPaciente = "' . $codPaciente . '"  order by pa.codCargo asc');
		return $query->getRow();
	}

	public function pegaPacientePorCodPaciente($codPaciente)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select 
		pa.*,TIMESTAMPDIFF (YEAR, pa.dataNascimento,CURDATE()) as idade, c.descricaoCargo,c.siglaCargo,tb.nomeTipoBeneficiario, DATE_FORMAT(pa.dataNascimento,"%d/%m/%Y") as dataNascimentoPerfil, DATE_FORMAT(pa.validade,"%d/%m/%Y") as validadePerfil,DATE_FORMAT(pa.dataNascimento,"%d/%m/%Y") as dataNascimentoPerfil,
		pe.nomeExibicao autorUltimaAtualizacao, 
		DATE_FORMAT(pa.dataAtualizacao,"%d/%m/%Y %H:%i") AS dataUltimaAtualizacao, pa.ativo,  tb.siglaTipoBeneficiario, pa.codPlano
		from sis_pacientes pa 
		left join sis_pessoas pe on pa.autorAtualizacao=pe.codPessoa
		left join sis_tipobeneficiario tb on tb.codTipoBeneficiario=pa.codTipoBeneficiario
		left join sis_cargos c on c.codCargo=pa.codCargo and c.codOrganizacao=pa.codOrganizacao  
		where pa.codClasse=2 and pa.codOrganizacao = ' . $codOrganizacao . ' and pa.codPaciente = "' . $codPaciente . '"  order by pa.codCargo asc');
		return $query->getRow();
	}


	public function pegaPorCodPaciente($codPaciente = null)
	{
		$query = $this->db->query('select * 
		from sis_pacientesalergias pa 
		left join sis_tiposalergenicos ta on ta.codTipoAlergenico = pa.codTipoAlergenico 
		where pa.codPaciente = ' . $codPaciente);
		return $query->getResult();
	}


	public function pegaPacientePorCodProntuario($pron_id, $pron_nr)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from sis_pacientes where pronID = "' . $pron_id . '" and pronNR="' . $pron_nr . '"');
		return $query->getRow();
	}

	public function lookupCodPessoaPorCODPLANOIntegracaoApolo($CODPLANO)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select pe.* from sis_pacientes pa join sis_pessoas pe on replace(replace(pe.cpf,".",""),"-","")=replace(replace(pa.cpf,".",""),"-","") or pe.nomeCompleto = pa.nomeCompleto
				where pa.codPlano = "' . $CODPLANO . '"');
		return $query->getRow();
	}
	public function lookupEstadoFederacao($codEstadoFederacao)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from sis_estadosfederacao where siglaEstadoFederacao = "' . $codEstadoFederacao . '"');
		return $query->getRow();
	}
	public function organizacaoPaciente($codPaciente)
	{
		$query = $this->db->query('select * from sis_organizacoes o join sis_pacientes p on p.codOrganizacao=o.codOrganizacao where p.codClasse=2 and p.codPaciente = ' . $codPaciente . ' order by p.codCargo asc');
		return $query->getRow();
	}


	public function getContato($codContato)
	{
		$query = $this->db->query('select * from sis_outroscontatos where codOutroContato = "' . $codContato . '"');
		return $query->getRow();
	}

	public function pega_pacientes()
	{


		$codOrganizacao = session()->codOrganizacao;
		if ($codOrganizacao == NULL or $codOrganizacao == '') {
			$configuracao = config('App');
			session()->set('codOrganizacao', $configuracao->codOrganizacao);
			$codOrganizacao = $configuracao->codOrganizacao;
		}
		$query = $this->db->query('select p.codPaciente,p.codPlano,p.codProntuario,p.nomeCompleto,p.cpf,p.nomeExibicao,p.ativo,g.siglaCargo
		from sis_pacientes p 
		left join sis_cargos g on g.codCargo=p.codCargo and g.codOrganizacao=p.codOrganizacao 
		where p.codClasse=2 and p.codOrganizacao = ' . $codOrganizacao . '
		order by p.codCargo asc, p.dataInicioEmpresa asc');
		return $query->getResult();
	}



	public function pegaPaciente($paciente)
	{


		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select p.codPaciente,p.codPlano,scp.nomeStatusCadastroPaciente,scp.corStatusCadastroPaciente,p.codProntuario,p.cpf,p.nomeExibicao,p.ativo,g.siglaCargo
		from sis_pacientes p 
		left join sis_cargos g on g.codCargo=p.codCargo and g.codOrganizacao=p.codOrganizacao 
		left join sis_statuscadastropaciente scp on scp.codStatusCadastroPaciente = p.codStatusCadastroPaciente
		where p.codClasse=2 and (p.nomeCompleto like "%' . $paciente . '%" or p.cpf like "%' . $paciente . '%" or p.codPlano like "%' . $paciente . '%" or p.codProntuario like "%' . $paciente . '%") and p.codOrganizacao = ' . $codOrganizacao . '
		order by p.codCargo asc, p.dataInicioEmpresa asc');
		return $query->getResult();
	}

	public function updateFotoPerfil($codPaciente, $fotoPerfil)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('update sis_pacientes set fotoPerfil="' . $fotoPerfil . '" where recuperado is null and codPaciente=' . $codPaciente . ' and codOrganizacao=' . $codOrganizacao);
		return 1;
	}


	public function updateCodPlano($pronID, $pronNR, $codPlano)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('update sis_pacientes set recuperado =1,codPlano="' . $codPlano . '" where pronID="' . $pronID . '" and pronNR="' . $pronNR . '"');
		return 1;
	}


	public function desativarPaciente($codPaciente, $dados)
	{

		if ($codPaciente !== NULL and $codPaciente !== "" and $codPaciente !== " ") {

			$this->db->table("sis_pacientes")->update($codPaciente, $dados);

			return 1;
		} else {
			return 0;
		}
	}


	public function reativarPaciente($codPaciente, $dados)
	{

		if ($codPaciente !== NULL and $codPaciente !== "" and $codPaciente !== " ") {

			$this->db->table("sis_pacientes")->update($codPaciente, $dados);

			return 1;
		} else {
			return 0;
		}
	}



	public function pegaOutrosContatosPorCodPaciente($codPaciente)
	{
		$query = $this->db->query('select * from sis_outroscontatos where codPaciente=' . $codPaciente);
		return $query->getResult();
	}


	public function tipoContatoLookup($codTipoContato = NULL)
	{
		if ($codTipoContato == NULL) {
			return NULL;
		}
		$query = $this->db->query('select nomeTipoContato from sis_tiposcontatos where codTipoContato = ' . $codTipoContato);
		return $query->getRow()->nomeTipoContato;
	}

	public function parentescoLookup($codParentesco = NULL)
	{
		if ($codParentesco == NULL) {
			return NULL;
		}
		$query = $this->db->query('select nomeParentesco from sis_parentesco where codParentesco = ' . $codParentesco);
		return $query->getRow()->nomeParentesco;
	}



	public function inserirOutroContatoTmp($dados)
	{

		$outrosContatosTmp = session()->outrosContatosTmp;
		array_push($outrosContatosTmp, $dados);
		session()->remove('outrosContatosTmp');
		session()->set('outrosContatosTmp', array());

		session()->push('outrosContatosTmp', $outrosContatosTmp);

		return 1;
	}


	public function inserirOutroContato($dados)
	{

		$db      = \Config\Database::connect();
		$builder = $db->table("sis_outroscontatos");
		$builder->insert($dados);
		return 1;
	}

	public function updateOutroContato($codOutroContato, $dados)
	{

		if ($codOutroContato !== NULL and $codOutroContato !== "" and $codOutroContato !== " ") {



			$query = $this->db->query('
			update sis_outroscontatos 
			set codTipoContato="' . $dados['codTipoContato'] . '",
			nomeContato="' . $dados['nomeContato'] . '",
			numeroContato="' . $dados['numeroContato'] . '",
			codParentesco="' . $dados['codParentesco'] . '",
			observacoes="' . $dados['observacoes'] . '"
			where codOutroContato="' . $codOutroContato . '"');

			if ($query == true) {
				return true;
			} else {
				return false;
			}
		}
	}





	public function prontuariosAnteriores($dados)
	{

		$this->db->table("sis_prontuariosanteriores")->insert($dados);
		return 1;
	}

	public function apagaProntuariosAnteriores()
	{

		$query = $this->db->query('truncate table sis_prontuariosanteriores');
		return 1;
	}



	public function prontuarios()
	{
		$apolo = \Config\Database::connect('apolo', false);
		$query = $apolo->query('select *
		from amb_prontuarios_n order by data_lc asc');
		return $query->getResult();
	}


	public function prontuariosSIGH()
	{
		$sigh = \Config\Database::connect('sigh', true);
		$query = $sigh->query('select * from PRONTUARIO order by CD_PACIENTE asc');
		return $query->getResult();
	}

	public function telefonesSIGH($codPaciente)
	{
		$sigh = \Config\Database::connect('sigh', true);
		$query = $sigh->query('select * from PRONTUARIO_FONE where CD_PACIENTE=' . $codPaciente);
		return $query->getResult();
	}


	public function buscaIdentidadeSIGH($CODPLANO = NULL, $SEQFAM = NULL)
	{
		$sigh = \Config\Database::connect('sigh', true);
		$query = $sigh->query("select * from BENEFICIARIOS_PLANO where CODPLANO='" . $CODPLANO . "' and SEQFAM='" . $SEQFAM . "'");
		return $query->getRow();
	}

	public function buscaCodPlanoSIGH($CODPLANO = NULL, $SEQ = NULL)
	{
		$sigh = \Config\Database::connect('sigh', true);
		$query = $sigh->query('select * from PRONTUARIO where CODPLANO="' . $CODPLANO . '" and SEQ="' . $SEQ . '"');
		return $query->getRow();
	}

	public function buscaCPFSIGH($CODPLANO = NULL, $SEQFAM = NULL)
	{
		$sigh = \Config\Database::connect('sigh', true);
		$query = $sigh->query("select * from BENEFICIARIOS_PLANO where CODPLANO='" . $CODPLANO . "' and SEQFAM='" . $SEQFAM . "'");
		return $query->getRow();
	}

	public function addProntuarioApolo($dados)
	{


		$apolo = \Config\Database::connect('apolo', false);
		$query = $apolo->table("amb_prontuarios_n")->insert($dados);

		return 1;
	}


	public function prontuariosRecuperados($pron_id, $pron_nr)
	{
		$apolo = \Config\Database::connect('apolo', false);
		$query = $apolo->query('select *
		from amb_prontuarios_n 
		where pron_id = ' . $pron_id . ' and pron_nr=' . $pron_nr);
		return $query->getRow();
	}



	public function contatosApolo()
	{
		$apolo = \Config\Database::connect('apolo', false);
		$query = $apolo->query('select *
		from amb_contatos');
		return $query->getResult();
	}

	public function emailsApolo()
	{
		$apolo = \Config\Database::connect('apolo', false);
		$query = $apolo->query('select *
		from amb_contatos_www where tipo="EMAIL" and valor like "%@%"');
		return $query->getResult();
	}

	public function outrosContatosPesApolo()
	{
		$apolo = \Config\Database::connect('apolo', false);
		$query = $apolo->query('select *
		from amb_contatos_pes order by pron_nr desc,pron_id desc');
		return $query->getResult();
	}



	public function contasErradas()
	{

		$query = $this->db->query('select *
		from sis_pacientes p where p.conta like "% %"');
		return $query->getResult();
	}


	public function listaDropDownResponsaveis()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select p.codPessoas as id, p.nomeExibicao as text from sis_pessoas p where codOrganizacao = ' . $codOrganizacao . ' and  p.codClasse=2 and p.ativo=1 and p.nomeExibicao is not null order by p.codCargo asc');
		return $query->getResult();
	}

	public function listaDropDownMotivosInativacaoPaciente()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('
		select codMotivoInativacao as id, descricaoMotivoInativacao as text from sis_motivosinativacaopacientes');
		return $query->getResult();
	}

	public function listaDropDownForca()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select codForca as id, nomeForca as text from sis_forca');
		return $query->getResult();
	}
	public function listaDropDownTipoSanguineo()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select codTipoSanguineo as id, nomeTipoSanguineo as text from sis_tiposanguineo order by codTipoSanguineo asc');
		return $query->getResult();
	}
	public function pegaTipoBeneficiario($siglaTipoBeneficiario)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select codTipoBeneficiario from sis_tipobeneficiario where siglaTipoBeneficiario="' . $siglaTipoBeneficiario . '"');
		if ($query !== NULL) {
			return $query->getRow()->codTipoBeneficiario;
		} else {
			return NULL;
		}
	}
	public function listaDropDownTipoBeneficiario()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select codTipoBeneficiario as id, nomeTipoBeneficiario as text from sis_tipobeneficiario');
		return $query->getResult();
	}
	public function listaDropDownOm()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select codOm as id, concat(siglaOM," (",nomeOm,")") as text from sis_om order by siglaOM asc ');
		return $query->getResult();
	}
	public function listaDropDownStatusCadastroPaciente()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select codStatusCadastroPaciente as id, nomeStatusCadastroPaciente as text from sis_statuscadastropaciente');
		return $query->getResult();
	}
	public function listaDropDownRaca()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select codRaca as id, nomeRaca as text from sis_racas');
		return $query->getResult();
	}

	public function listaDropDownParentesco()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select distinct codParentesco as id, nomeParentesco as text from sis_parentesco order by codParentesco asc');
		return $query->getResult();
	}

	public function listaDropDownTiposContatos()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select codTipoContato as id, nomeTipoContato as text from sis_tiposcontatos');
		return $query->getResult();
	}
	public function pegarVCARD()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select o.siglaOrganizacao,p.nomeexibicao,p.nomeexibicao,p.datanascimento,p.emailPessoal,p.emailfuncional,p.celular,p.telefonetrabalho,p.endereco 
		FROM sis_pacientes p 
		join sis_organizacoes o on o.codOrganizacao=p.codOrganizacao
		where p.celular is not null');
		return $query->getResult();
	}


	public function listaDropDownSolicitante()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('
		select p.codPessoas as id, p.nomeExibicao as text from sis_pessoas p  where codOrganizacao = ' . $codOrganizacao . ' and  p.codClasse=2 and p.ativo=1 and p.nomeExibicao is not null order by p.codCargo asc');
		return $query->getResult();
	}

	public function listaDropDownPacientesFiltrados($term)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('
		select p.codPaciente as id, p.nomeExibicao as text 
		from sis_pacientes p where p.nomeCompleto like "%' . $term . '%" and codOrganizacao = ' . $codOrganizacao . ' and  p.codClasse=2 and p.ativo=1 and p.nomeExibicao is not null order by p.codCargo asc');
		return $query->getResult();
	}
	public function listaDropDownPacientes()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('
		select p.codPaciente as id, p.nomeExibicao as text from sis_pacientes p where codOrganizacao = ' . $codOrganizacao . ' and  p.codClasse=2 and p.ativo=1 and p.nomeExibicao is not null order by p.codCargo asc');
		return $query->getResult();
	}


	public function listaDropDownBuscaPacientes()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('
		select p.codPaciente as id, concat(p.nomeExibicao," - CPF:", p.cpf," - Nº PLANO:", p.codPlano) as text from sis_pacientes p where codOrganizacao = ' . $codOrganizacao . ' and  p.codClasse=2 and concat(p.nomeExibicao," - CPF:", p.cpf," - Nº PLANO:", p.codPlano) is not null order by p.codCargo asc');
		return $query->getResult();
	}


	public function removerOutroContato($codOutroContato)
	{
		$query = $this->db->query('
		delete from sis_outroscontatos where codOutroContato = ' . $codOutroContato);
		return true;
	}


	public function updateEmail($pronID, $pronNR, $email)
	{
		$query = $this->db->query('
		update sis_pacientes 
		set emailPessoal="' . $email . '"
		where pronID="' . $pronID . '" and pronNR="' . $pronNR . '"');
	}

	public function updateCelular($codPaciente, $celular)
	{
		$query = $this->db->query('
		update sis_pacientes set celular="' . $celular . '" where codPaciente=' . $codPaciente);
	}
}
