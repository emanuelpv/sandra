<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class LogsModel extends Model
{

	protected $table = 'sis_logs';
	protected $primaryKey = 'codLog';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codOrganizacao', 'ocorrrencia', 'codPessoa', 'dataCriacao'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;


	public function pega_logs()
	{
		$query = $this->db->query('select * from ' . $this->table);
		return $query->getRow();
	}



	public function inserirLog($ocorrencia, $codPessoa)
	{
		if ($codPessoa == NULL) {
			$codPessoa = 0;
		}

		
		//pega IP
		$request = \Config\Services::request();
		$ip = $request->getIPAddress();
		$codOrganizacao = session()->codOrganizacao;
		$sis_timezone = session()->sis_timezone;
		date_default_timezone_set($sis_timezone);




		$query = $this->db->query('insert into ' . $this->table . '(codOrganizacao,ocorrencia,codPessoa,dataCriacao,ip) values (' . $codOrganizacao . ',"' . $ocorrencia . '",' . $codPessoa . ',"' . date('Y-m-d H:i') . '","' . $ip . '")');
	}



	public function inserirLogPaciente($ocorrencia, $codPaciente)
	{
		if ($codPaciente == NULL) {
			$codPaciente = 0;
		}

		$codOrganizacao = session()->codOrganizacao;
		$sis_timezone = session()->sis_timezone;
		date_default_timezone_set($sis_timezone);


		//pega IP
		$request = \Config\Services::request();
		$ip = $request->getIPAddress();

		$query = $this->db->query('insert into sis_logspacientes(codOrganizacao, ocorrencia, codPaciente, dataCriacao, ip) values (' . $codOrganizacao . ',"' . $ocorrencia . '",' . $codPaciente . ',"' . date('Y-m-d H:i') . '","' . $ip . '")');
	}


	
	public function inserirPesquisaVagas($codPaciente, $codEspecialidade)
	{
		if ($codPaciente == NULL) {
			$codPaciente = 0;
		}

		$codOrganizacao = session()->codOrganizacao;
		$sis_timezone = session()->sis_timezone;
		date_default_timezone_set($sis_timezone);


		//pega IP
		$request = \Config\Services::request();
		$ip = $request->getIPAddress();

		$query = $this->db->query('insert into logs_pesquisavagas(codPaciente,codEspecialidade,dataPesquisa) values (' . $codPaciente . ',"' . $codEspecialidade . '","' . date('Y-m-d H:i') . '")');
	}


	public function inserirLogLDAP($conexao = null, $dados = null, $codServidorLDAP = NULL, $tipoLogLDAP = NULL, $dn = null, $ocorrencia = null)
	{
		//$tipoLogLDAP ERROR =0, SUCESSO =1

		$codPessoa = session()->codPessoa;
		if ($codPessoa == NULL) {
			$codPessoa = 0;
		}


		$codOrganizacao = session()->codOrganizacao;
		
		if ($codOrganizacao == NULL) {
			$configuracao = config('App');
			session()->set('codOrganizacao', $configuracao->codOrganizacao);
			$codOrganizacao = $configuracao->codOrganizacao;
		}
		$sis_timezone = session()->sis_timezone;
		if ($sis_timezone == NULL) {
			$sis_timezone = 'America/Sao_Paulo';
		}
		date_default_timezone_set($sis_timezone);
		$dataHora = date('Y-m-d H:i:s');
		$saidaConexao = @ldap_error($conexao);
		if ($saidaConexao !== 'Success') {
			$erro = " - " . $saidaConexao;
		} else {
			$erro = "";
		}
		@ldap_get_option($conexao, LDAP_OPT_DIAGNOSTIC_MESSAGE, $erroDetalheado);
		$atributosTentados = str_replace(array("{", "}", "'\'", "[", "]", '"'), "", json_encode($dados));
		$mensagemCompleta = $ocorrencia . $erro . ' - ' . $erroDetalheado . ' - ' . $dn . $atributosTentados;


		//pega IP
		$request = \Config\Services::request();
		$ip = $request->getIPAddress();


		$query = $this->db->query('insert into sis_logsldap(codOrganizacao,codServidorLDAP,codPessoa,tipoLogLDAP,dataCriacao,ip,ocorrencia) values (' . $codOrganizacao . ',' . $codServidorLDAP . ',' . $codPessoa . ',' . $tipoLogLDAP . ',"' . $dataHora . '","' . $ip . '","' . $mensagemCompleta . '")');
	}
}
