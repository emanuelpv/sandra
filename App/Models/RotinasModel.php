<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class RotinasModel extends Model
{




	public function tabelasLocal()
	{

		$database = $this->db->database;
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from information_schema.TABLES where TABLE_SCHEMA ="' . $database . '"');


		return $query->getResult();
	}
	public function importarDoencas()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from apolo.amb_hndoenca d
		join sandra.tabealookupespecialidadesfaltantes f on d.clinica=f.especialidade
		order by data_lc desc');
		return $query->getResult();
	}



	public function importarEvolucaoes()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from apolo.amb_patevolucao');
		return $query->getResult();
	}


	public function lookupCodEspecialidade($clinica, $prec)
	{


		$query = $this->db->query('
		SELECT p.codPessoa, p.nomeExibicao, e.descricaoEspecialidade, e.codEspecialidade
		FROM  sandra.tabelalookupapolo l
		join sandra.sis_pessoas p on p.codPessoa=l.codPessoa
		join sandra.sis_especialidadesmembros em on em.codPessoa=p.codPessoa
		join sandra.sis_especialidades e on e.codEspecialidade=em.codEspecialidade
		where em.codEspecialidade <>29 and l.prec="' . $prec . '"');

		if ($query->getRow()->codEspecialidade !== NULL and $query->getRow()->codEspecialidade !== "") {
			return $query->getRow()->codEspecialidade;
		} else {
			$query = $this->db->query('
		SELECT codEspecialidade from sandra.tabealookupespecialidadesfaltantes
		where especialidade ="' . $clinica . '"');
			if ($query->getRow()->codEspecialidade !== NULL and $query->getRow()->codEspecialidade !== "") {

				return $query->getRow()->codEspecialidade;
			} else {

				return 29;
			}
		}
	}
	public function pegaespecialidadePorLocal($localAtendimento)
	{

		$query = $this->db->query('select * from tabelalookupespecialidades where local_atd="' . $localAtendimento . '"');
		return $query->getRow();
	}

	public function pegaTipoAtendimentoPorLocal($localAtendimento)
	{

		$query = $this->db->query('select * from tabelalookupespecialidades where local_atd="' . $localAtendimento . '"');
		return $query->getRow();
	}


	public function lookupMedicamento($nomeMedicamento)
	{

		$query = $this->db->query('select * from sis_itensfarmacia where descricaoItem="' . $nomeMedicamento . '"');
		return $query->getRow();
	}


	public function lookupMaterial($nomeMaterial)
	{

		$query = $this->db->query('select * from sis_itensfarmacia where descricaoItem="' . $nomeMaterial . '"');

		if (!empty($query)) {
			return $query->getRow();
		} else {
			return array();
		}
	}


	public function lookupUnidadeItem($unidadeItem)
	{

		$query = $this->db->query('select * from sis_unidades where descricaoUnidade="' . $unidadeItem . '"');
		return $query->getRow();
	}

	public function lookupPegaTipoOrcamento($tipoOrcamento)
	{

		$query = $this->db->query('select * from ges_tipoorcamento where descricaoTipoOrcamento="' . $tipoOrcamento . '"');
		return $query->getRow();
	}

	public function lookupPegaCodFornecedor($razaoSocial)
	{

		$query = $this->db->query('select * from ges_fornecedores where razaoSocial="' . $razaoSocial . '"');
		return $query->getRow();
	}



	public function lookupViaItem($viaItem)
	{

		$query = $this->db->query('select * from sis_vias where descricaoVia="' . $viaItem . '"');
		return $query->getRow();
	}


	public function lookupPeriodoItem($descricaoPeriodo)
	{

		$query = $this->db->query('select * from sis_periodoprescricao where descricaoPeriodo="' . $descricaoPeriodo . '"');
		return $query->getRow();
	}


	public function lookupAgoraItem($descricaoAplicarAgora)
	{

		$query = $this->db->query('select * from sis_statusaplicaragora where descricaoAplicarAgora="' . $descricaoAplicarAgora . '"');
		return $query->getRow();
	}


	public function lookupRiscoItem($descricaoRiscoPrescricao)
	{

		$query = $this->db->query('select * from sis_riscoprescricao where descricaoRiscoPrescricao="' . $descricaoRiscoPrescricao . '"');
		return $query->getRow();
	}


	public function lookupStatusItem($descricaoStatusPrescricao)
	{

		$query = $this->db->query('select * from sis_statusprescricao where descricaoStatusPrescricao="' . $descricaoStatusPrescricao . '"');
		return $query->getRow();
	}
	public function lookupTipoOutrasPrescricoes($nomeTipoOutraPrescricao)
	{

		$query = $this->db->query('select * from sis_tiposoutrasprescricoes where nomeTipoOutraPrescricao="' . $nomeTipoOutraPrescricao . '"');
		return $query->getRow();
	}

	public function lookupStatusItemMaterial($descricaoStatusMaterial)
	{

		$query = $this->db->query('select * from sis_statusmaterial where descricaoStatusMaterial="' . $descricaoStatusMaterial . '"');
		return $query->getRow();
	}

	public function lookupStatusItemOutras($descricaoStatusOutras)
	{

		$query = $this->db->query('select * from sis_statusoutras where descricaoStatusOutras="' . $descricaoStatusOutras . '"');
		return $query->getRow();
	}

	public function lookupCodLocalAtendimento($localAtendimento)
	{

		$query = $this->db->query('select * from tabelalookupespecialidades where local_atd="' . $localAtendimento . '"');
		return $query->getRow();
	}



	public function tabelasRemota($servidor, $login, $senha, $banco)
	{


		$teleteamProducao = [
			'DSN'      => '',
			'hostname' => $servidor,
			'username' => $login,
			'password' => $senha,
			'database' => $banco,
			'DBDriver' => 'MySQLi',
			'DBPrefix' => '',
			'pConnect' => false,
			'DBDebug'  => (ENVIRONMENT !== 'production'),
			'charset'  => 'utf8',
			'DBCollat' => 'utf8_general_ci',
			'swapPre'  => '',
			'encrypt'  => false,
			'compress' => false,
			'strictOn' => false,
			'failover' => [],
			'port'     => 3306,
		];

		$teleteamRemota = \Config\Database::connect($teleteamProducao, true);

		$query = $teleteamRemota->query('select * from information_schema.TABLES where TABLE_SCHEMA ="' . $teleteamRemota->database . '"');

		return $query->getResult();
	}

	public function agendasSIGH()
	{
		$sigh = \Config\Database::connect('sigh', true);
		$query = $sigh->query('select * FROM AGENDA a
		join AGENDA_MEDICO am on am.CD_AG_MEDICO=a.CD_AG_MEDICO');
		return $query->getResult();
	}

	public function agendasConfigSIGH()
	{
		$sigh = \Config\Database::connect('sigh', true);
		$query = $sigh->query('select * FROM AGENDA_MEDICO');
		return $query->getResult();
	}

	public function pessoasSIGH()
	{
		$sigh = \Config\Database::connect('sigh', true);
		$query = $sigh->query('select * FROM USUARIO u
		join POSTO_GRAD p on u.PSTGRD=p.CD_PSTGRD
		order by u.CD_USUARIO');
		return $query->getResult();
	}

	public function especialidadesUsadas()
	{
		$sigh = \Config\Database::connect('sigh', true);
		$query = $sigh->query('select distinct x.CD_ESPECIALIDADE,e.ESPECIALIDADE 
		from (select CD_ESPECIALIDADE FROM AGENDA_MEDICO 
		union ALL select CD_ESPECIALIDADE FROM AGENDA 
		union ALL select CD_ESPECIALIDADE FROM AMBULATORIO )x 
		join ESPECIALIDADE e on e.CD_ESPECIALIDADE=x.CD_ESPECIALIDADE order by x.CD_ESPECIALIDADE asc');
		return $query->getResult();
	}

	public function pessoasUsadas()
	{
		$sigh = \Config\Database::connect('sigh', true);
		$query = $sigh->query('select distinct x.CD_USUARIO,e.NOME, e.CODPLANO,e.CPF
		from (select CD_USER_MED as CD_USUARIO FROM AGENDA_MEDICO 
		union ALL select CD_USER_MED as CD_USUARIO FROM AGENDA 
		union ALL select CD_USER_REG as CD_USUARIO FROM AMBULATORIO )x 
		join USUARIO e on e.CD_USUARIO=x.CD_USUARIO order by e.NOME asc');
		return $query->getResult();
	}
	public function especialidadesDesteUsuario($codPessoaSIGH)
	{
		$sigh = \Config\Database::connect('sigh', true);
		$query = $sigh->query('select distinct x.CD_ESPECIALIDADE,e.ESPECIALIDADE
		from (select DISTINCT CD_ESPECIALIDADE FROM AGENDA_MEDICO where CD_USER_MED="' . $codPessoaSIGH . '"
		union ALL select DISTINCT CD_ESPECIALIDADE FROM AGENDA where CD_USER_MED="' . $codPessoaSIGH . '"
		union ALL  select DISTINCT CD_ESPECIALIDADE FROM AMBULATORIO where CD_USER_REG="' . $codPessoaSIGH . '")x 
		join ESPECIALIDADE e on e.CD_ESPECIALIDADE=x.CD_ESPECIALIDADE 
		where e.ESPECIALIDADE not in ("ADMINISTRAÇÃO HOSPITALAR")
		order by e.ESPECIALIDADE asc');
		return $query->getResult();
	}
	public function pegaCIDAtendimento($CD_AMBULATORIO)
	{
		$sigh = \Config\Database::connect('sigh', true);
		$query = $sigh->query('select * from AMBULATORIO_CID WHERE CD_AMBULATORIO="' . $CD_AMBULATORIO . '"');
		return $query->getResult();
	}

	public function pegaCIDBoletom($CD_BOLETIM)
	{
		$sigh = \Config\Database::connect('sigh', true);
		$query = $sigh->query('select * from BOLETIM_CID WHERE CD_BOLETIM="' . $CD_BOLETIM . '"');
		return $query->getResult();
	}

	public function pegaCodCid($CD_CID = NULL)
	{
		$query = $this->db->query('select * FROM amb_cid10 where siglaCid="' . $CD_CID . '"');
		return $query->getRow();
	}

	public function lookupCodEspecialidadeSIGH($CD_ESPECIALIDADE = NULL)
	{
		$query = $this->db->query('select * FROM lookpup_especialidades l
		join sis_especialidades e on l.descricaoEspecialidade=e.descricaoEspecialidade
		where l.CD_ESPECIALIDADE="' . $CD_ESPECIALIDADE . '"  order by ESPECIALIDADE');
		return $query->getRow();
	}

	public function lookupCodEspecialitaSandra($CD_USUARIO = NULL)
	{
		$query = $this->db->query('select * FROM lookpup_pessoas		
		where CD_USUARIO="' . $CD_USUARIO . '"');
		return $query->getRow();
	}

	public function verificaSeJaImportado($codPaciente = NULL, $codEspeciaista = NULL, $DT_REG = NULL)
	{
		$query = $this->db->query('select * FROM amb_atendimentos where codPaciente="' . $codPaciente . '" and codEspecialista="' . $codEspeciaista . '" and dataCriacao="' . $DT_REG . '"');
		return $query->getRow();
	}

	public function pegaChave($codOrganizacao)
	{
		$query = $this->db->query('select chaveSalgada from sis_organizacoes where codOrganizacao = "' . $codOrganizacao . '"');
		return $query->getRow();
	}

	public function verificaExistenciaUsuario($NOME = NULL)
	{
		$query = $this->db->query('select * FROM sis_pessoas
		where nomeCompleto like "%' . $NOME . '%"');
		return $query->getRow();
	}
	public function verificaCodPessoa($codPessoa = NULL)
	{
		$query = $this->db->query('select * FROM sis_pessoas
		where codPessoa="' . $codPessoa . '"');
		return $query->getRow();
	}

	public function pegaPessoaSandraPorNome($nomeCompleto = NULL)
	{
		$query = $this->db->query('select * FROM sis_pessoas
		where nomeCompleto="' . $nomeCompleto . '"');
		return $query->getRow();
	}

	public function lookupCodPessoaSIGH($CD_USUARIO = NULL)
	{
		$query = $this->db->query('select * FROM lookpup_pessoas l
		where l.CD_USUARIO="' . $CD_USUARIO . '"  order by NOME');
		return $query->getRow();
	}
	public function dadosPessoa($codPessoa = NULL)
	{
		$query = $this->db->query('select * FROM sis_pessoas where codPessoa="' . $codPessoa . '"');
		return $query->getRow();
	}
	public function especialidadesSandra()
	{
		$query = $this->db->query('select * FROM sis_especialidades');
		return $query->getResult();
	}
	public function pessoasSandra()
	{
		$query = $this->db->query('select * FROM sis_pessoas order by nomeCompleto asc');
		return $query->getResult();
	}
	public function lookupListaEspecialidades()
	{
		$query = $this->db->query('select * FROM lookpup_especialidades order by ESPECIALIDADE ASC');
		return $query->getResult();
	}
	public function lookupListaPessoas()
	{
		$query = $this->db->query('select * FROM lookpup_pessoas order by NOME ASC');
		return $query->getResult();
	}


	public function lookupProfissionaisSIGH($CD_USER_MED = NULL)
	{
		$sigh = \Config\Database::connect('sigh', true);
		$query = $sigh->query('select * FROM hgujp.sis_pessoas p
		join sigh.USUARIO u on u.USUARIO=P.CONTA 
		where u.CD_USUARIO="' . $CD_USER_MED . '"');
		return $query->getRow();
	}

	public function listarAmbulatoriosSIGH()
	{
		$sigh = \Config\Database::connect('sigh', true);
		$query = $sigh->query('select * FROM AMBULATORIO order by CD_AMBULATORIO asc');
		return $query->getResult();
	}

	public function listarBoletinsSIGH()
	{
		$sigh = \Config\Database::connect('sigh', true);
		$query = $sigh->query('select * FROM BOLETIM b
		join (select DISTINCT CD_BOLETIM, CD_ESPECIALIDADE from BOLETIM_ESPEC) be 
		on b.CD_BOLETIM=be.CD_BOLETIM 
		ORDER BY b.CD_BOLETIM  DESC');
		return $query->getResult();
	}


	public function verificaExitenciaConfigSIGH($codEspecialidade = NULL, $DT_REG = NULL)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from amb_agendamentosconfig where codEspecialidade ="' . $codEspecialidade . '" and dataCriacao="' . $DT_REG . '"');
		return $query->getRow();
	}

	public function atributosLocal($tabela = null)
	{

		$database = $this->db->database;

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from information_schema.COLUMNS where TABLE_SCHEMA ="' . $database . '" and TABLE_NAME="' . $tabela . '"');
		return $query->getResult();
	}



	public function atributosRemotaPorTabela($tabela = null, $coluna = null, $servidor = null, $login = null, $senha = null, $banco = null)
	{

		$teleteamProducao = [
			'DSN'      => '',
			'hostname' => $servidor,
			'username' => $login,
			'password' => $senha,
			'database' => $banco,
			'DBDriver' => 'MySQLi',
			'DBPrefix' => '',
			'pConnect' => false,
			'DBDebug'  => (ENVIRONMENT !== 'production'),
			'charset'  => 'utf8',
			'DBCollat' => 'utf8_general_ci',
			'swapPre'  => '',
			'encrypt'  => false,
			'compress' => false,
			'strictOn' => false,
			'failover' => [],
			'port'     => 3306,
		];

		$teleteamRemota = \Config\Database::connect($teleteamProducao, true);

		$query = $teleteamRemota->query('select * from information_schema.COLUMNS where TABLE_SCHEMA ="' . $banco . '" and TABLE_NAME="' . $tabela . '" and COLUMN_NAME="' . $coluna . '"');
		return $query->getResult();
	}


	public function lookupCodPessoaPorCODPLANOIntegracaoApolo($CODPLANO)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('SELECT * FROM sis_pessoas p join tabelalookupapolo ta on p.codPessoa=ta.codPessoa
				where ta.prec ="' . $CODPLANO . '"');
		return $query->getRow();
	}


	public function atualizaPrec()
	{
		$query = $this->db->query('update sis_pessoas pp,sis_pacientes p set pp.codPlano = p.codPlano where p.nomeCompleto=pp.nomeCompleto and p.codPlano is not null and pp.codPlano is null');
		return true;
	}

	public function lookupCodPessoa($CODPLANO)
	{

		$query = $this->db->query('SELECT * FROM sis_pessoas
			where codPlano ="' . $CODPLANO . '"');

		if ($query !== NULL) {
			return $query->getRow();
		}


		$query = $this->db->query('SELECT * FROM sis_pacientes
			where codPlano ="' . $CODPLANO . '"');

		if ($query !== NULL) {
			return $query->getRow();
		}

		$query = $this->db->query('SELECT * FROM tabelalookupapolo
				where prec ="' . $CODPLANO . '"');

		return $query->getRow();
	}


	public function atributosLocalPorTabela($tabela = null, $coluna = null)
	{

		$database = $this->db->database;
		$query = $this->db->query('select * from information_schema.COLUMNS where TABLE_SCHEMA ="' . $database . '" and TABLE_NAME="' . $tabela . '" and COLUMN_NAME="' . $coluna . '"');
		return $query->getRow();
	}

	public function atributosRemota($tabela, $servidor, $login, $senha, $banco)
	{
		$teleteamProducao = [
			'DSN'      => '',
			'hostname' => $servidor,
			'username' => $login,
			'password' => $senha,
			'database' => $banco,
			'DBDriver' => 'MySQLi',
			'DBPrefix' => '',
			'pConnect' => false,
			'DBDebug'  => (ENVIRONMENT !== 'production'),
			'charset'  => 'utf8',
			'DBCollat' => 'utf8_general_ci',
			'swapPre'  => '',
			'encrypt'  => false,
			'compress' => false,
			'strictOn' => false,
			'failover' => [],
			'port'     => 3306,
		];

		$teleteamRemota = \Config\Database::connect($teleteamProducao, true);

		$query = $teleteamRemota->query('select * from information_schema.COLUMNS where TABLE_SCHEMA ="' . $banco . '" and TABLE_NAME="' . $tabela . '"');
		return $query->getResult();
	}
	public function importarItensHospitalares()
	{
		$apolo = \Config\Database::connect('apolo', true);
		$query = $apolo->query('select * FROM far_itens');
		return $query->getResult();
	}
	public function pegaAtendimentosApolo()
	{
		//PEGA DATA DE REINICIO
		$producaoSandra = $this->db->query('select min(dataInicio) as dataInicio FROM amb_atendimentos WHERE migrado=1');
		$dataInicio = $producaoSandra->getRow()->dataInicio;

		if ($dataInicio == NULL or $dataInicio == "") {
			$dataInicio = date('Y-m-d H:i');
		}

		$apolo = \Config\Database::connect('apolo', true);
		//$query = $apolo->query('select * FROM amb_atendimentos where pron_id=01402 and pron_nr=12575');
		$query = $apolo->query('select * FROM amb_atendimentos where data_lc <= "' . $dataInicio . '" order by data_lc desc');
		return $query->getResult();
	}

	public function pegaEvolucoesApolo($pron_id, $pron_nr, $id_atd)
	{
		$apolo = \Config\Database::connect('apolo', true);
		$query = $apolo->query('select * from amb_atdevolucao where pron_id="' . $pron_id . '" and pron_nr="' . $pron_nr . '" and id_atd="' . $id_atd . '"
		order by data_lc asc');
		return $query->getResult();
	}

	public function importarFornecedores()
	{
		$apolo = \Config\Database::connect('apolo', true);
		$query = $apolo->query('select * from adm_empresas');
		return $query->getResult();
	}

	public function importarRequisicoes()
	{
		$apolo = \Config\Database::connect('apolo', true);
		$query = $apolo->query('select * from ges_requisicoes');
		return $query->getResult();
	}

	public function importarRequisicoesInformacoesComplementares($sec_nome = null, $nr = null, $ano = null)
	{
		$apolo = \Config\Database::connect('apolo', true);
		$query = $apolo->query('select * from ges_reqinfocompl where sec_nome ="' . $sec_nome . '" and nr="' . $nr . '" and ano="' . $ano . '" order by id asc');
		return $query->getResult();
	}

	public function itensRequisicoes($sec_nome = null, $nr = null, $ano = null)
	{
		$apolo = \Config\Database::connect('apolo', true);
		$query = $apolo->query('select * from ges_itens where sec_nome ="' . $sec_nome . '" and nr="' . $nr . '" and ano="' . $ano . '" order by idItem asc');
		return $query->getResult();
	}


	public function historicoAcoes($sec_nome = null, $nr = null, $ano = null)
	{
		$apolo = \Config\Database::connect('apolo', true);
		$query = $apolo->query('select * from ges_reqdespachos where sec_nome ="' . $sec_nome . '" and nr="' . $nr . '" and ano="' . $ano . '" order by data_lc asc');
		return $query->getResult();
	}

	public function orcamentos($sec_nome = null, $nr = null, $ano = null, $idItem = null)
	{
		$apolo = \Config\Database::connect('apolo', true);
		$query = $apolo->query('select * from ges_mapas where sec_nome ="' . $sec_nome . '" and nr="' . $nr . '" and ano="' . $ano . '" and  idItem="' . $idItem . '" order by idItem asc');
		return $query->getResult();
	}


	public function pegaPrescricoesApolo($pron_id, $pron_nr, $id_atd)
	{
		$apolo = \Config\Database::connect('apolo', true);
		$query = $apolo->query('select * from amb_atd_prescr where pron_id="' . $pron_id . '" and pron_nr="' . $pron_nr . '" and id_atd="' . $id_atd . '"
		order by data_lc asc');
		return $query->getResult();
	}
	public function pegaItensPrescricoesApolo($pron_id, $pron_nr, $id_atd, $idprescr)
	{
		$apolo = \Config\Database::connect('apolo', true);
		$query = $apolo->query('select * from amb_prescmed where pron_id="' . $pron_id . '" and pron_nr="' . $pron_nr . '" and id_atd="' . $id_atd . '" and idprescr="' . $idprescr . '"
		order by data_lc asc');
		return $query->getResult();
	}
	public function pegaItensMateriaisApolo($pron_id, $pron_nr, $id_atd, $idprescr)
	{
		$apolo = \Config\Database::connect('apolo', true);
		$query = $apolo->query('select * from amb_prescmat where pron_id="' . $pron_id . '" and pron_nr="' . $pron_nr . '" and id_atd="' . $id_atd . '" and idprescr="' . $idprescr . '"
		order by data_lc asc');
		return $query->getResult();
	}

	public function pegaItensOutrasPrescricoesApolo($pron_id, $pron_nr, $id_atd, $idprescr)
	{
		$apolo = \Config\Database::connect('apolo', true);
		$query = $apolo->query('select * from amb_outrasprescr where pron_id="' . $pron_id . '" and pron_nr="' . $pron_nr . '" and id_atd="' . $id_atd . '" and idprescr="' . $idprescr . '"
		order by data_lc asc');
		return $query->getResult();
	}

	public function sincronizaKits()
	{
		$apolo = \Config\Database::connect('apolo', true);
		$query = $apolo->query('select * FROM cdm_kits');
		return $query->getResult();
	}

	public function itensKit($nome)
	{
		$apolo = \Config\Database::connect('apolo', true);
		$query = $apolo->query('select * FROM cdm_kits_itens where nome="' . $nome . '"');
		return $query->getResult();
	}

	public function importarAtendimentosAmbulatoriosPorDoencas()
	{
		$apolo = \Config\Database::connect('apolo', true);
		$query = $apolo->query('select * from amb_hndoenca');
		return $query->getResult();
	}



	public function importarAtendimentosAmbulatoriosPorAnamnese()
	{
		$apolo = \Config\Database::connect('apolo', true);
		$query = $apolo->query('select * from amb_anamnese');
		return $query->getResult();
	}
	public function pegaUsuarioFantasma()
	{
		$apolo = \Config\Database::connect('apolo', true);
		$query = $apolo->query('SELECT * FROM amb_anamnese WHERE pron_id =  "01902" and pron_nr = "02562"');
		return $query->getResult();
	}

	public function condutasApolo($pron_id, $pron_nr, $id)
	{
		$apolo = \Config\Database::connect('apolo', true);
		$query = $apolo->query('SELECT * FROM amb_patevolucao WHERE pron_id =  "' . $pron_id . '" and pron_nr = "' . $pron_nr . '" and id=' . $id);
		return $query->getResult();
	}

	public function addEspecialidadeLookup($CD_ESPECIALIDADE, $ESPECIALIDADE)
	{
		$this->db->query('insert into lookpup_especialidades(CD_ESPECIALIDADE,ESPECIALIDADE, codEspecialidade, descricaoEspecialidade) VALUES (' . $CD_ESPECIALIDADE . ', "' . $ESPECIALIDADE . '", NULL,NULL)');
		return true;
	}

	public function addPessoaLookup($CD_USUARIO, $NOME, $CODPLANO, $CPF)
	{
		$this->db->query('insert into lookpup_pessoas(CD_USUARIO, NOME, CODPLANO, CPF, codPessoa, nomeCompleto, codPlano, cpfSandra) VALUES ("' . $CD_USUARIO . '","' . $NOME . '","' . $CODPLANO . '","' . $CPF . '",NULL,NULL,NULL,NULL)');
		return true;
	}
	public function editEspecialidadeLookup($codEspecialidadeSIGH, $codEspecialidade, $descricaoEspecialidade)
	{
		$this->db->query('update lookpup_especialidades set codEspecialidade="' . $codEspecialidade . '", descricaoEspecialidade="' . $descricaoEspecialidade . '" where CD_ESPECIALIDADE="' . $codEspecialidadeSIGH . '"');
		return true;
	}

	public function editPessoaLookup($CD_USUARIO, $codPessoa = NULL, $nomeCompleto = NULL, $codPlano = NULL, $cpdSandra = NULL)
	{
		$this->db->query('update lookpup_pessoas set codPessoa="' . $codPessoa . '", nomeCompleto="' . $nomeCompleto . '", codPlano="' . $codPlano . '", cpfSandra="' . $cpdSandra . '" where CD_USUARIO="' . $CD_USUARIO . '"');
		return true;
	}
}
