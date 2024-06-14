<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class ServicoLDAPModel extends Model
{

	protected $table = 'sis_servicoldap';
	protected $primaryKey = 'codServidorLDAP';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['sambaSID', 'servidorArquivo', 'codTipoMicrosoft', 'atributoChave', 'dnNovosUsuarios', 'master', 'status', 'tipoHash', 'forcarSSL', 'dataCriacao', 'dataAtualizacao', 'codOrganizacao', 'descricaoServidorLDAP', 'codTipoLDAP', 'ipServidorLDAP', 'portaLDAP', 'loginLDAP', 'senhaLDAP', 'dn', 'encoding', 'fqdn', 'LDAPOptProtocolVersion', 'LDAPOptReferrals', 'LDAPOptTimeLimit'];
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
		$query = $this->db->query('select * from sis_servicoldap sl 
		left join sis_tipoldap tp on sl.codTipoLDAP=tp.codTipoLDAP
		where sl.codOrganizacao = ' . $codOrganizacao);
		return $query->getResult();
	}

	public function pegaLogs($tipo = null)
	{

		$codOrganizacao = session()->codOrganizacao;
		if ($tipo !== NULL) {
			$query = $this->db->query('select ll.*,tp.nomeTipoLDAP,sl.descricaoServidorLDAP,sl.ipServidorLDAP,p.nomeExibicao from sis_logsldap ll 
		left join sis_servicoldap sl on  ll.codServidorLDAP = sl.codServidorLDAP  
		left join sis_tipoldap tp on sl.codTipoLDAP=tp.codTipoLDAP
		left join sis_organizacoes o on o.codOrganizacao=ll.codOrganizacao
		left join sis_pessoas p on p.codPessoa = ll.codPessoa
		where ll.tipoLogLDAP = ' . $tipo . ' and sl.codOrganizacao = ' . $codOrganizacao . ' order by ll.dataCriacao desc');
		} else {

			$query = $this->db->query('select ll.*,tp.nomeTipoLDAP,sl.descricaoServidorLDAP,sl.ipServidorLDAP,p.nomeExibicao from sis_logsldap ll 
		left join sis_servicoldap sl on  ll.codServidorLDAP = sl.codServidorLDAP  
		left join sis_tipoldap tp on sl.codTipoLDAP=tp.codTipoLDAP
		left join sis_organizacoes o on o.codOrganizacao=ll.codOrganizacao
		left join sis_pessoas p on p.codPessoa = ll.codPessoa
		where sl.codOrganizacao = ' . $codOrganizacao . ' order by ll.dataCriacao desc limit 1000');
		}

		return $query->getResult();
	}


	public function pegaTudoAtivo($codOrganizacao = null)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from sis_servicoldap sl 
		left join sis_tipoldap tp on sl.codTipoLDAP=tp.codTipoLDAP
		where sl.status=1 and sl.codOrganizacao =' . $codOrganizacao . ' order by master desc limit 1000');
		return $query->getResult();
	}


	public function pegaTudoAtivoActiveDirectory($codOrganizacao = null)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from sis_servicoldap sl 
		left join sis_tipoldap tp on sl.codTipoLDAP=tp.codTipoLDAP
		where sl.codTipoLDAP =1 and sl.status=1 and sl.codOrganizacao =' . $codOrganizacao . ' order by master desc limit 1000');
		return $query->getResult();
	}

	public function pegaServidoresLDAPMicrosoftAtivo($codOrganizacao = null)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from sis_servicoldap sl 
		left join sis_tipoldap tp on sl.codTipoLDAP=tp.codTipoLDAP
		where sl.status=1 and sl.codTipoLDAP = 1 and sl.codOrganizacao =' . $codOrganizacao . ' order by master desc limit 1000');
		return $query->getResult();
	}

	public function pegaPorCodigo($codServidorLDAP)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from sis_servicoldap sl 
		left join sis_tipoldap tp on sl.codTipoLDAP=tp.codTipoLDAP
		where codOrganizacao = ' . $codOrganizacao . ' and codServidorLDAP = "' . $codServidorLDAP . '"');
		return $query->getRow();
	}

	public function objectclassAdicionais($codServidorLDAP)
	{
		
		$query = $this->db->query('select * from sis_servicoldapobjectclassadicionais where codServidorLDAP = "' . $codServidorLDAP . '"');
		return $query->getResult();
	}

	public function pegaMaster()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from sis_servicoldap sl join sis_tipoldap tl on tl.codTipoLDAP=sl.codTipoLDAP Where sl.codOrganizacao = ' . $codOrganizacao . ' and sl.master=1');
		return $query->getRow();
	}

	public function redefineMaster()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('update sis_servicoldap set master=0 where codOrganizacao =' . $codOrganizacao);
	}



	public function conectaldap($conta = null, $senha = null, $codServidorLDAP = null)
	{
		//SERVIDOR MASTER DE AUTENTICAÇÃO
		$codOrganizacao = session()->codOrganizacao;


		if ($codServidorLDAP !== NULL) {
			$servidorLDAP = $this->db->query('select * from sis_servicoldap sl join sis_tipoldap tl on tl.codTipoLDAP=sl.codTipoLDAP Where sl.codServidorLDAP = ' . $codServidorLDAP)->getRow();
		} else {
			$servidorLDAP = $this->db->query('select * from sis_servicoldap sl join sis_tipoldap tl on tl.codTipoLDAP=sl.codTipoLDAP Where sl.codOrganizacao = ' . $codOrganizacao . ' and sl.master=1')->getRow();
		}


		if ($servidorLDAP->forcarSSL == 1) {
			$protocolo = 'ldaps://';
		} else {
			$protocolo = 'ldap://';
		}

		session()->remove('CONECTADO_LDAP');
		session()->remove('BASE_DN');
		$configruacaoGlobal = $this->db->query('select * from sis_configuracaoglobal')->getRow();

		$organizacao = $this->db->query('select * from sis_organizacoes Where codOrganizacao = ' . $codOrganizacao)->getResult();

		/*
		//CRIPTOGRAFIA DE SENHA
		$criptografado = $this->encriptar($chave,$tipo_cifra, 'paralelepipado');
		print $this->descriptar($chave,$tipo_cifra, 'dHZPcW84ZktwaytPOFBrTjBadk1QUT09OjqP+UO2YtpH7g==');

		$servidorLDAP = $servidorLDAP;
		$chave = $organizacao[0]->chaveSalgada;
		$tipo_cifra = 'des';

		*/

		$servidor = $servidorLDAP->ipServidorLDAP;
		$descricaoServidorLDAP = $servidorLDAP->descricaoServidorLDAP;
		$porta = $servidorLDAP->portaLDAP;
		$administrador = $servidorLDAP->loginLDAP;
		$dominiofqdn = $servidorLDAP->fqdn;
		$dominiodn = $servidorLDAP->dn;
		$tipoldap = $servidorLDAP->codTipoLDAP;
		$senhaadministrador =  $servidorLDAP->senhaLDAP;
		$baseDN =  $servidorLDAP->dn;



		if ($servidorLDAP->LDAP_OPT_PROTOCOL_VERSION !== NULL) {
			$LDAP_OPT_PROTOCOL_VERSION = $servidorLDAP->LDAP_OPT_PROTOCOL_VERSION;
		} else {
			$LDAP_OPT_PROTOCOL_VERSION = 3;
		}



		if ($servidorLDAP->LDAP_OPT_REFERRALS !== NULL) {
			$LDAP_OPT_REFERRALS = $servidorLDAP->LDAP_OPT_REFERRALS;
		} else {
			$LDAP_OPT_REFERRALS = 0;
		}


		if ($servidorLDAP->LDAP_OPT_TIMELIMIT !== NULL) {
			$LDAP_OPT_TIMELIMIT = $servidorLDAP->LDAP_OPT_TIMELIMIT;
		} else {
			$LDAP_OPT_TIMELIMIT = 0;
		}



		$mensagem = 'Conta inexistente ou senha incorreta!';
		if ($conta == null) {
			$conta = $administrador;
			$mensagem = 'Não foi possível acessar com a conta do Administrador do Domínio. Tente contate a STI!';
		}
		if ($senha == null) {
			$senha = $senhaadministrador;
		}

		$resultado = array();

		if ($tipoldap == 1) {
			//É ACTIVE DIRECTORY OU SAMBA4


			if (!$conectadoLDAP = @ldap_connect($protocolo . $servidor, $porta)) {
				$resultado = array(0, 'Falha ao conectar no servidor <b>' . $descricaoServidorLDAP . '(' . $servidor . ')</b>. Verifique se o servidor está disponível e se a porta ' . $porta . ' está correta');
				return $resultado;
			}

			//VEFIFICA LOGIN

			ldap_set_option($conectadoLDAP, LDAP_OPT_NETWORK_TIMEOUT, 2);
			ldap_set_option($conectadoLDAP, LDAP_OPT_PROTOCOL_VERSION, $LDAP_OPT_PROTOCOL_VERSION);
			ldap_set_option($conectadoLDAP, LDAP_OPT_REFERRALS, $LDAP_OPT_REFERRALS);
			ldap_set_option($conectadoLDAP, LDAP_OPT_TIMELIMIT, $LDAP_OPT_TIMELIMIT);
			if ($bind = @ldap_bind($conectadoLDAP, $conta . '@' . $dominiofqdn, $senha)) {

				$dados = array(
					'statusConexao' => $conectadoLDAP,
					'servidor' => $servidorLDAP->ipServidorLDAP,
					'descricaoServidorLDAP' => $servidorLDAP->descricaoServidorLDAP,
					'porta' => $servidorLDAP->portaLDAP,
					'administrador' => $servidorLDAP->loginLDAP,
					'dominiofqdn' => $servidorLDAP->fqdn,
					'dominiodn' => $servidorLDAP->dn,
					'tipoldap' => $servidorLDAP->codTipoLDAP,
					'senhaadministrador' =>  $servidorLDAP->senhaLDAP,
					'baseDN' =>  $servidorLDAP->dn,
					'nomeTipoLDAP' =>  $servidorLDAP->nomeTipoLDAP,
					'codTipoLDAP' =>  $servidorLDAP->codTipoLDAP,
					'codServidorLDAP' =>  $servidorLDAP->codServidorLDAP,
					'status' => 1,
					'mensagem' => 'Autenticação do usuário realizada com sucesso no servidor <b>' . $descricaoServidorLDAP . '(' . $servidor . ')</b>',

				);
				session()->set('CONECTADO_LDAP', $dados);
				$resultado = $dados;
				return $resultado;
			} else {
				$resultado = array(
					'status' => 0,
					'mensagem' => 'Autenticação do usuário realizada com erro no servidor <b>' . $descricaoServidorLDAP . '(' . $servidor . ')</b>',
					'tipoldap' => $servidorLDAP->codTipoLDAP,
				);
				return $resultado;
			}
		}



		if ($tipoldap == 2) {
			//É OPENLDAP



			if (count(explode(",", $conta)) > 1) {
				$conta = $conta;
			} else {
				$conta = 'cn=' . $conta . ',' . $baseDN;
			}

			if (!$conectadoLDAP = @ldap_connect($protocolo . $servidor, $porta)) {

				$resultado = array(0, 'Falha ao conectar no servidor <b>' . $descricaoServidorLDAP . '(' . $servidor . ')</b>. Verifique se o servidor está disponível e se a porta ' . $porta . ' está correta');
				return $resultado;
			}

			//VEFIFICA LOGIN
			//VERIFICA USUÁRIO

			ldap_set_option($conectadoLDAP, LDAP_OPT_NETWORK_TIMEOUT, 2);
			ldap_set_option($conectadoLDAP, LDAP_OPT_PROTOCOL_VERSION, $LDAP_OPT_PROTOCOL_VERSION);
			ldap_set_option($conectadoLDAP, LDAP_OPT_REFERRALS, $LDAP_OPT_REFERRALS);
			ldap_set_option($conectadoLDAP, LDAP_OPT_TIMELIMIT, $LDAP_OPT_TIMELIMIT);



			if ($bind = @ldap_bind($conectadoLDAP, $conta, $senha)) {
				$dados = array(
					'statusConexao' => $conectadoLDAP,
					'servidor' => $servidorLDAP->ipServidorLDAP,
					'descricaoServidorLDAP' => $servidorLDAP->descricaoServidorLDAP,
					'porta' => $servidorLDAP->portaLDAP,
					'administrador' => $servidorLDAP->loginLDAP,
					'dominiofqdn' => $servidorLDAP->fqdn,
					'dominiodn' => $servidorLDAP->dn,
					'tipoldap' => $servidorLDAP->codTipoLDAP,
					'senhaadministrador' =>  $servidorLDAP->senhaLDAP,
					'baseDN' =>  $servidorLDAP->dn,
					'nomeTipoLDAP' =>  $servidorLDAP->nomeTipoLDAP,
					'codTipoLDAP' =>  $servidorLDAP->codTipoLDAP,
					'codServidorLDAP' =>  $servidorLDAP->codServidorLDAP,
					'status' => 1,
					'mensagem' => 'Autenticação do usuário realizada com sucesso no servidor <b>' . $descricaoServidorLDAP . '(' . $servidor . ')</b>',

				);
				session()->set('CONECTADO_LDAP', $dados);
				$resultado = $dados;






				return $resultado;
			} else {
				$resultado = array(
					'status' => 0,
					'mensagem' => 'Autenticação do usuário realizada com erro no servidor <b>' . $descricaoServidorLDAP . '(' . $servidor . ')</b>',
					'tipoldap' => $servidorLDAP->codTipoLDAP,
				);
				return $resultado;
			}
		}
	}

	public  function pegaUnidadesOrganizacionais($tipoldap, $nome = null, $orderby = 'sn')
	{
		if ($nome !== NULL) {
			if ($tipoldap == 1) {
				$filter = "(&(objectClass=organizationalUnit)(name=" . $nome . "))";
				$orderby = 'sn';
			} else {
				$filter = "(&(objectClass=organizationalUnit)(name=" . $nome . "))";
				$orderby = 'sn';
			}
		} else {
			if ($tipoldap == 1) {
				$filter = "(&(objectClass=organizationalUnit))";
				$orderby = 'sn';
			} else {
				$filter = "(&(objectClass=organizationalUnit))";
				$orderby = 'sn';
			}
		}




		if (!($search = @ldap_search(session()->CONECTADO_LDAP['statusConexao'], session()->CONECTADO_LDAP['baseDN'], $filter))) {
			//die("Erro ao filtrar objetos");
		}
		$info = @ldap_get_entries(session()->CONECTADO_LDAP['statusConexao'], $search);

		return $info;
	}


	public  function pegaGrupos($tipoldap, $nome = null, $orderby = 'sn')
	{
		if ($nome !== NULL) {
			if ($tipoldap == 1) {
				$filter = "(&(objectClass=Group)(|(name=" . $nome . ")(description=" . $nome . ")))";
				$orderby = 'sn';
			} else {
				$filter = "(&(objectClass=Group)(|(name=" . $nome . ")(description=" . $nome . ")))";
				$orderby = 'sn';
			}
		} else {
			if ($tipoldap == 1) {
				$filter = "(&(objectClass=Group))";
				$orderby = 'sn';
			} else {
				$filter = "(&(objectClass=Group))";
				$orderby = 'sn';
			}
		}




		if (!($search = @ldap_search(session()->CONECTADO_LDAP['statusConexao'], session()->CONECTADO_LDAP['baseDN'], $filter))) {
			//die("Erro ao filtrar objetos");
		}
		$info = @ldap_get_entries(session()->CONECTADO_LDAP['statusConexao'], $search);

		return $info;
	}


	public  function pegaPessoas($tipoldap, $orderby = 'sn', $conta = null)
	{




		if ($conta !== NULL) {
			if ($tipoldap == 1) {
				//$filter = '(samaccountname=' . $conta . ')';
				$filter = '(|(cn=' . $conta . ')(samaccountname=' . $conta . ')(userprincipalname=' . $conta . '))';
				$orderby = 'sn';
			} else {
				$filter = '(|(uid=' . $conta . ') (cn=' . $conta . '))';
				$orderby = 'sn';
			}
		} else {
			if ($tipoldap == 1) {
				//É ACTIVE DIRECTORY OU SAMBA4
				$filter = "(&(objectClass=user)(objectCategory=person) (!(isCriticalSystemObject=TRUE)))";
			} else {
				//É OPENLDAP
				$filter = "(|(objectClass=person)(objectClass=user))";
			}
		}


		if (!($search = @ldap_search(session()->CONECTADO_LDAP['statusConexao'], session()->CONECTADO_LDAP['baseDN'], $filter))) {
			//die("Erro ao filtrar objetos");
		}
		$info = @ldap_get_entries(session()->CONECTADO_LDAP['statusConexao'], $search);

		return $info;
	}



	public  function pegaDepartamentos($tipoldap, $orderby = 'sn', $departamentos = null)
	{




		if ($departamentos !== NULL) {
			if ($tipoldap == 1) {
				//$filter = '(samaccountname=' . $departamentos . ')';
				$filter = "(&(objectClass=organizationalUnit)(!(name=Domain Controllers))(!(name=sitracker))(!(name=sitracker)))";
				$orderby = 'name';
			} else {
				$filter = "(&(objectClass=organizationalUnit)(!(name=Domain Controllers))(!(name=sitracker))(!(name=sitracker)))";
				$orderby = 'name';
			}
		} else {
			if ($tipoldap == 1) {
				//É ACTIVE DIRECTORY OU SAMBA4
				$filter = "(&(objectClass=organizationalUnit)(!(name=Domain Controllers))(!(name=sitracker))(!(name=sitracker)))";
			} else {
				//É OPENLDAP
				$filter = "(&(objectClass=organizationalUnit)(!(name=Domain Controllers))(!(name=sitracker))(!(name=sitracker)))";
			}
		}

		if (!($search = @ldap_search(session()->CONECTADO_LDAP['statusConexao'], session()->CONECTADO_LDAP['baseDN'], $filter))) {
			//die("Erro ao filtrar objetos");
		}
		$info = @ldap_get_entries(session()->CONECTADO_LDAP['statusConexao'], $search);

		return $info;
	}


	public function testeLDAP($parametros)
	{



		//SERVIDOR MASTER DE AUTENTICAÇÃO
		$codOrganizacao = session()->codOrganizacao;

		session()->remove('CONECTADO_LDAP');
		session()->remove('BASE_DN');
		$configruacaoGlobal = $this->db->query('select * from sis_configuracaoglobal')->getRow();

		$organizacao = $this->db->query('select * from sis_organizacoes Where codOrganizacao = ' . $codOrganizacao)->getResult();




		/*
		//CRIPTOGRAFIA DE SENHA
		$criptografado = $this->encriptar($chave,$tipo_cifra, 'paralelepipado');
		print $this->descriptar($chave,$tipo_cifra, 'dHZPcW84ZktwaytPOFBrTjBadk1QUT09OjqP+UO2YtpH7g==');

		$servidorLDAP = $servidorLDAP;
		$chave = $organizacao[0]->chaveSalgada;
		$tipo_cifra = 'des';

		*/

		$servidor = $parametros['ipServidorLDAP'];
		$descricaoServidorLDAP = $parametros['descricaoServidorLDAP'];
		$porta = $parametros['portaLDAP'];
		$conta = $parametros['loginLDAP'];
		$dominiofqdn = $parametros['fqdn'];
		$dominiodn = $parametros['dn'];
		$tipoldap = $parametros['codTipoLDAP'];
		$senha =  $parametros['senhaLDAP'];
		$baseDN =  $parametros['dn'];
		$encoding = $parametros['encoding'];
		$LDAPOptProtocolVersion = $parametros['LDAPOptProtocolVersion'];
		$LDAPOptTimeLimit = $parametros['LDAPOptTimeLimit'];
		$codServidoLDAPOptTimeLimitrLDAP = $parametros['LDAPOptTimeLimit'];
		$forcarSSL = $parametros['forcarSSL'];

		if ($forcarSSL == 'on') {
			$protocolo = 'ldaps://';
		} else {
			$protocolo = 'ldap://';
		}


		if ($parametros['LDAPOptProtocolVersion'] !== NULL) {
			$LDAP_OPT_PROTOCOL_VERSION = $parametros['LDAPOptProtocolVersion'];
		} else {
			$LDAP_OPT_PROTOCOL_VERSION = 3;
		}



		if ($parametros['lDAPOptReferrals'] !== NULL) {
			$LDAP_OPT_REFERRALS = $parametros['lDAPOptReferrals'];
		} else {
			$LDAP_OPT_REFERRALS = 0;
		}


		if ($parametros['LDAPOptTimeLimit'] !== NULL) {
			$LDAP_OPT_TIMELIMIT = $parametros['LDAPOptTimeLimit'];
		} else {
			$LDAP_OPT_TIMELIMIT = 0;
		}



		$resultado = array();

		if ($tipoldap == 1) {
			//É ACTIVE DIRECTORY OU SAMBA4


			if (!$conectadoLDAP = ldap_connect($protocolo . $servidor, $porta)) {
				return false;
			}

			//VEFIFICA LOGIN

			ldap_set_option($conectadoLDAP, LDAP_OPT_NETWORK_TIMEOUT, 2);
			ldap_set_option($conectadoLDAP, LDAP_OPT_PROTOCOL_VERSION, $LDAP_OPT_PROTOCOL_VERSION);
			ldap_set_option($conectadoLDAP, LDAP_OPT_REFERRALS, $LDAP_OPT_REFERRALS);
			ldap_set_option($conectadoLDAP, LDAP_OPT_TIMELIMIT, $LDAP_OPT_TIMELIMIT);
			if ($bind = @ldap_bind($conectadoLDAP, $conta . '@' . $dominiofqdn, $senha)) {
				return true;
			} else {
				return false;
			}
		}



		if ($tipoldap == 2) {
			//É OPENLDAP


			if (count(explode(",", $conta)) > 1) {
				$conta = $conta;
			} else {
				$conta = 'cn=' . $conta . ',' . $baseDN;
			}

			if (!$conectadoLDAP = ldap_connect($protocolo . $servidor, $porta)) {
				return false;
			}

			//VEFIFICA LOGIN
			//VERIFICA USUÁRIO

			ldap_set_option($conectadoLDAP, LDAP_OPT_NETWORK_TIMEOUT, 2);
			ldap_set_option($conectadoLDAP, LDAP_OPT_PROTOCOL_VERSION, $LDAP_OPT_PROTOCOL_VERSION);
			ldap_set_option($conectadoLDAP, LDAP_OPT_REFERRALS, $LDAP_OPT_REFERRALS);
			ldap_set_option($conectadoLDAP, LDAP_OPT_TIMELIMIT, $LDAP_OPT_TIMELIMIT);
			if ($bind = @ldap_bind($conectadoLDAP, $conta, $senha)) {
				return true;
			} else {
				return false;
			}
		}
	}
}
