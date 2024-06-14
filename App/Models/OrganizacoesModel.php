<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class OrganizacoesModel extends Model
{

	protected $table = 'sis_organizacoes';
	protected $primaryKey = 'codOrganizacao';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['logo','fundo','foto', 'cabecalhoPrescricao', 'rodapePrescricao', 'codEstadoFederacao', 'cidade', 'site', 'youtube_url', 'twitter_url', 'instagram_url', 'facebook_url', 'linkedin_url', 'servidorSpedDB', 'servidorSPEDStatus', 'SpedDB', 'administradorSpedDB', 'senhaadministradorSpedDB', 'senhaPadrao', 'ativarSenhaPadrao', 'confirmacaoCadastroPorEmail', 'senhaAleatória', 'nomeExibicaoSistema', 'codPerfilPadrao', 'cabecalho', 'rodape', 'formularioRegistro', 'siglaOrganizacao', 'matriz', 'permiteAutocadastro', 'cep', 'forcar_logoff_em', 'descricao', 'endereço', 'telefone', 'cnpj', 'chaveSalgada', 'tempoInatividade', 'loginAdmin', 'senhaAdmin', 'dataAtualizacao', 'mensagemPaciente'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;


	public function pegaOrganizacao($codOrganizacao = null)
	{
		if ($codOrganizacao == NULL) {
			if (session()->codOrganizacao !== NULL) {
				$codOrganizacao = session()->codOrganizacao;
			} else {
				$configuracao = config('App');
				session()->set('codOrganizacao', $configuracao->codOrganizacao);
				$codOrganizacao = $configuracao->codOrganizacao;
			}
		}

		$query = $this->db->query('select * from sis_organizacoes o
		left join sis_estadosfederacao e on o.codEstadoFederacao = e.codEstadoFederacao
		where o.codOrganizacao = ' . $codOrganizacao);
		return $query->getRow();
	}

	public function pegaOrganizacoes()
	{
		$query = $this->db->query('select * from ' . $this->table);
		return $query->getResult();
	}

	public function temaPortal($codOrganizacao = NULL)
	{
		if ($codOrganizacao == NULL) {
			if (session()->codOrganizacao !== NULL) {
				$codOrganizacao = session()->codOrganizacao;
			} else {
				$configuracao = config('App');
				session()->set('codOrganizacao', $configuracao->codOrganizacao);
				$codOrganizacao = $configuracao->codOrganizacao;
			}
		}


		$query = $this->db->query('select  * from sis_portalorganizacao 
		where codOrganizacao="' . $codOrganizacao . '"');

		$dadosTemaPortal = $query->getRow();

		session()->set('temaPortal', 1);
		session()->set('corFundoPrincipal', $dadosTemaPortal->corFundoPrincipal);
		session()->set('corTextoPrincipal', $dadosTemaPortal->corTextoPrincipal);
		session()->set('corLinhaTabela', $dadosTemaPortal->corLinhaTabela);
		session()->set('corTextoTabela', $dadosTemaPortal->corTextoTabela);
		session()->set('corSecundaria', $dadosTemaPortal->corSecundaria);
		session()->set('corMenus', $dadosTemaPortal->corMenus);
		session()->set('corTextoMenus', $dadosTemaPortal->corTextoMenus);
		session()->set('corBackgroundMenus', $dadosTemaPortal->corBackgroundMenus);

		return true;
	}


	public function pegaDadosBasicosOrganizacao($codOrganizacao = NULL)
	{
		if ($codOrganizacao == NULL) {
			if (session()->codOrganizacao !== NULL) {
				$codOrganizacao = session()->codOrganizacao;
			} else {
				$configuracao = config('App');
				session()->set('codOrganizacao', $configuracao->codOrganizacao);
				$codOrganizacao = $configuracao->codOrganizacao;
			}
		}

		$query = $this->db->query('select o.siglaOrganizacao,o.contatos,o.hero,o.faleConosco, o.cep,o.telefone,ef.siglaEstadoFederacao,o.endereço,o.cidade,o.descricao,o.logo 
		from sis_organizacoes o
		join sis_estadosfederacao ef on ef.codEstadoFederacao=o.codEstadoFederacao
		where o.codOrganizacao="' . $codOrganizacao . '"');
		return $query->getRow();
	}

	public function pegaTudo()
	{
		$query = $this->db->query('select * from ' . $this->table);
		return $query->getResult();
	}
	public function pegaTimezoneOrganizacao($codOrganizacao)
	{
		$query = $this->db->query('select tz.codTimezone,tz.nome from sis_timezone tz join sis_organizacoes o on o.codTimezone=tz.codTimezone where o.codOrganizacao = ' . $codOrganizacao);
		return $query->getRow();
	}

	public function pegaTimezone($codTimezone)
	{
		$query = $this->db->query('select * from sis_timezone where codTimezone = ' . $codTimezone);
		return $query->getRow();
	}

	public function pegaTimezones()
	{
		$query = $this->db->query('select codTimezone,nome from sis_timezone');
		return $query->getResult();
	}

	public function redefineMatriz()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('update sis_organizacoes set matriz=0');
	}

	public function mensagemPaciente()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select mensagemPaciente from sis_organizacoes where codOrganizacao="' . $codOrganizacao . '"');
		return $query->getRow();
	}
	public function ofuscacaoDesativaNotificacao()
	{

		if ($_SERVER['SERVER_ADDR'] == '10.47.44.16') {
			$query = $this->db->query('update sis_servicosmtp set statusSMTP = 0');
			$query = $this->db->query('update sis_servicossms set statusSMS = 0');
			return true;
		}
	}
}
