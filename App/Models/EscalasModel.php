<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class EscalasModel extends Model
{

	protected $table = 'svc_escalas';
	protected $primaryKey = 'codEscala';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['dataLimiteLiberacao', 'descricao', 'dataCriacao', 'dataAtualizacao', 'codAutor', 'setorGestor', 'modificadoPor'];
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
		$query = $this->db->query('select p.nomeExibicao as criadoPor, pp.nomeExibicao as modificadoPor, e.*,d.abreviacaoDepartamento
		from svc_escalas e
		left join sis_departamentos d on d.codDepartamento=e.setorGestor
		left join sis_pessoas p on p.codPessoa=e.codAutor
		left join sis_pessoas pp on pp.codPessoa=e.modificadoPor');
		return $query->getResult();
	}


	public function todasEscalas()
	{

		$query = $this->db->query('select e.*,pp.nomeExibicao as modificadoPor
		from svc_escalas e		
		left join sis_pessoas pp on pp.codPessoa=e.modificadoPor');
		return $query->getResult();
	}


	public function membrosAtivos($codEscala = NULL)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select DATEDIFF(CURDATE(), me.dataUltimoEscalacaoPreta) as diasPreta,DATEDIFF(CURDATE(), me.dataUltimoEscalacaoVermelha) as diasVermelha, ppp.nomeExibicao as membro, ss.descricao as statusServico, p.nomeExibicao as criadoPor, pp.nomeExibicao as modificadoPor, me.*,d.abreviacaoDepartamento
		from  svc_membrosescala me
		left join svc_escalas e on e.codEscala=me.codEscala
		left join sis_departamentos d on d.codDepartamento=e.setorGestor
		left join sis_pessoas p on p.codPessoa=me.codAutor
		left join sis_pessoas pp on pp.codPessoa=me.modificadoPor
		left join sis_pessoas ppp on ppp.codPessoa=me.codPessoa
		left join svc_statusservico ss on ss.codStatus=me.codStatus
		where p.ativo=1 and me.codStatus in(1) and me.codEscala="' . $codEscala . '"');
		return $query->getResult();
	}

	public function dadosEscala($codEscala = NULL)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select e.*, pp.nomeExibicao as modificadoPor
		from svc_escalas e		
		left join sis_pessoas pp on pp.codPessoa=e.modificadoPor
		where e.codEscala="' . $codEscala . '"');
		return $query->getRow();
	}


	public function ultimoServico($codEscala = NULL, $codPessoa = NULL)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select *
		from svc_membrosescala 
		where codEscala="' . $codEscala . '" and codPessoa="' . $codPessoa . '"');
		return $query->getRow();
	}

	public function listaDropDownEscalas()
	{
		$query = $this->db->query('select codEscala as id,descricao as text
		from svc_escalas where descricao is not null');
		return $query->getResult();
	}


	public function membrosAtivosEscalaPreta($codEscala = NULL)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select me.codPessoa,DATEDIFF(CURDATE(), me.dataUltimoEscalacaoPreta) as diasPreta,DATEDIFF(CURDATE(), me.dataUltimoEscalacaoVermelha) as diasVermelha, ppp.nomeExibicao as membro, ss.descricao as statusServico, p.nomeExibicao as criadoPor, pp.nomeExibicao as modificadoPor, me.*,d.abreviacaoDepartamento
		from  svc_membrosescala me
		left join svc_escalas e on e.codEscala=me.codEscala
		left join sis_departamentos d on d.codDepartamento=e.setorGestor
		left join sis_pessoas p on p.codPessoa=me.codAutor
		left join sis_pessoas pp on pp.codPessoa=me.modificadoPor
		left join sis_pessoas ppp on ppp.codPessoa=me.codPessoa
		left join svc_statusservico ss on ss.codStatus=me.codStatus
		where p.ativo=1 and me.codStatus in(1) and me.codEscala="' . $codEscala . '"
		order by diasPreta desc');
		return $query->getResult();
	}


	public function getPrevisaoEscala($codEscala = NULL, $dataPrevisao = NULL)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from svc_previsaoescala 
		where codEscala="' . $codEscala . '" and dataPrevisao="' . $dataPrevisao . '"');
		return $query->getRow();
	}

	public function mostrarPrevisaoEscala($codEscala = NULL, $dataLimiteLiberacao = NULL)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select pe.*, p.nomeExibicao 
		from svc_previsaoescala pe
		left join sis_pessoas p on p.codPessoa=pe.codPessoa 
		where pe.dataPrevisao>=CURDATE() and pe.dataPrevisao<="' . $dataLimiteLiberacao . '" and pe.codEscala="' . $codEscala . '" order by pe.dataPrevisao asc');
		return $query->getResult();
	}

	public function dadosPrevisaoEscala($codPrevisaoEscala = NULL)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select pe.*,  DATE_FORMAT(pe.dataPrevisao, "%d/%m/%Y") as previsao, p.nomeExibicao 
		from svc_previsaoescala pe
		left join sis_pessoas p on p.codPessoa=pe.codPessoa 
		where pe.codPrevisaoEscala="' . $codPrevisaoEscala . '"');
		return $query->getRow();
	}


	public function limpaPrevisaoEscala($codEscala = NULL)
	{

		$deleta = $this->db->query('delete from svc_previsaoescala 
		where codEscala="' . $codEscala . '"');
		return true;
	}

	public function limpaTabelaPrevisaoEscala()
	{

		//$this->db->query('truncate table svc_previsaoescala');
		return true;

	}

	public function addPrevisaoEscala($dataPrevisao = NULL, $codTipoEscala = NULL, $codEscala = NULL, $codPessoa = NULL)
	{

		$query = $this->db->query('insert into svc_previsaoescala (codPrevisaoEscala, dataPrevisao, codTipoEscala, codEscala, codPessoa) VALUES (NULL, "' . $dataPrevisao . '", "' . $codTipoEscala . '", "' . $codEscala . '", "' . $codPessoa . '");');
		return true;
	}


	public function updatePrevisaoEscala($codPrevisaoEscala = NULL, $dataPrevisao = NULL, $codTipoEscala = NULL, $codEscala = NULL, $codPessoa = NULL)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('update svc_previsaoescala 
		set dataPrevisao="' . $dataPrevisao . '", 
		codTipoEscala="' . $codTipoEscala . '", 
		codEscala= "' . $codEscala . '", 
		codPessoa="' . $codPessoa . '"
		where codPrevisaoEscala="' . $codPrevisaoEscala . '"');
		return true;
	}

	public function membrosAtivosEscalaVermelha($codEscala = NULL)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select me.codPessoa,DATEDIFF(CURDATE(), me.dataUltimoEscalacaoPreta) as diasPreta,DATEDIFF(CURDATE(), me.dataUltimoEscalacaoVermelha) as diasVermelha, ppp.nomeExibicao as membro, ss.descricao as statusServico, p.nomeExibicao as criadoPor, pp.nomeExibicao as modificadoPor, me.*,d.abreviacaoDepartamento
		from  svc_membrosescala me
		left join svc_escalas e on e.codEscala=me.codEscala
		left join sis_departamentos d on d.codDepartamento=e.setorGestor
		left join sis_pessoas p on p.codPessoa=me.codAutor
		left join sis_pessoas pp on pp.codPessoa=me.modificadoPor
		left join sis_pessoas ppp on ppp.codPessoa=me.codPessoa
		left join svc_statusservico ss on ss.codStatus=me.codStatus
		where p.ativo=1 and me.codStatus in(1) and me.codEscala="' . $codEscala . '"
		order by diasVermelha desc');
		return $query->getResult();
	}

	public function ultimaEscala($codPessoa = NULL, $codEscala)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * 
		from svc_previsaoescala where codPessoa="' . $codPessoa . '" and codEscala="' . $codEscala . '"
		order by dataPrevisao desc limit 1');
		return $query->getRow();
	}
	public function verificaSeVermelha($data = NULL)
	{

		//VERIFICA TABELA DE DATAS VERMELHAS (FÉRIADOS E DIAS ESPECIAIS)

		$query = $this->db->query('select *
		from svc_datasvermelhas
		where dataVermelha="' . date('Y-m-d', $data) . '" and ativo=1');
		if (!empty($query->getRow())) {
			return true;
		}


		//verifica se final de semana
		$dayOfWeek = date('w', $data);
		if ($dayOfWeek == 0 || $dayOfWeek == 6) {
			return true;
		} else {
			return false;
		}
	}


	public function vermelhaMaisProxima($data = NULL)
	{

		$dayOfWeek = date('w', strtotime($data));
		if ($dayOfWeek == 0 || $dayOfWeek == 6) {
			return $data;
		} else {

			$dataRef = strtotime($data);
			for ($r = 0; $r <= 10; $r++) {

				$dataRef = $dataRef + 86400;

				$dayOfWeek = date('w', $dataRef);
				if ($dayOfWeek == 0 || $dayOfWeek == 6) {
					return date('Y-m-d', $dataRef);
				}
			}
		}



		//verifica se final de semana

	}
	public function verificaSePreta($data = NULL)
	{

		//verifica se final de semana
		$dayOfWeek = date('w', $data);
		if ($dayOfWeek !== 0 and $dayOfWeek !== 6) {
			return true;
		} else {
			return false;
		}
	}


	public function membrosAfastados($codEscala = NULL)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select  a.*,ppp.nomeExibicao as membro, ss.descricao as statusServico, p.nomeExibicao as criadoPor, pp.nomeExibicao as modificadoPor,d.abreviacaoDepartamento
		from  svc_membrosescala me
		left join  svc_afastamentos a on a.codMembroEscala=me.codMembroEscala
		left join svc_escalas e on e.codEscala=me.codEscala
		left join sis_departamentos d on d.codDepartamento=e.setorGestor
		left join sis_pessoas p on p.codPessoa=a.codAutor
		left join sis_pessoas pp on pp.codPessoa=a.modificadoPor
		left join sis_pessoas ppp on ppp.codPessoa=me.codPessoa
		left join svc_statusservico ss on ss.codStatus=a.codStatus
		where p.ativo=1 and me.codEscala="' . $codEscala . '"');
		return $query->getResult();
	}

	public function membrosAfastadosFuturo($codEscala = NULL)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select  a.*,ppp.nomeExibicao as membro, ss.descricao as statusServico, p.nomeExibicao as criadoPor, pp.nomeExibicao as modificadoPor,d.abreviacaoDepartamento
		from  svc_membrosescala me
		left join  svc_afastamentos a on a.codMembroEscala=me.codMembroEscala
		left join svc_escalas e on e.codEscala=me.codEscala
		left join sis_departamentos d on d.codDepartamento=e.setorGestor
		left join sis_pessoas p on p.codPessoa=a.codAutor
		left join sis_pessoas pp on pp.codPessoa=a.modificadoPor
		left join sis_pessoas ppp on ppp.codPessoa=me.codPessoa
		left join svc_statusservico ss on ss.codStatus=a.codStatus
		where p.ativo=1 and me.codEscala="' . $codEscala . '"
		and (a.dataEncerramentoAfastamento >= CURDATE() or (a.afastamentoIndeterminado =1 and a.ativo=1))
		');
		return $query->getResult();
	}
	public function verificaTroca($codPessoa = NULL, $codTipoEscala = NULL)
	{
		$query = $this->db->query('select p.nomeExibicao as nomeExibicaoSai, pp.nomeExibicao as nomeExibicaoEntra, te.*
		from  svc_trocasescalas te
		left join svc_escalas e on e.codEscala=te.codEscala
		left join sis_pessoas p on p.codPessoa=te.codPessoa
		left join sis_pessoas pp on pp.codPessoa=te.trocadoPor
		where te.dataPrevisao>=CURDATE() and te.codTipoEscala="' . $codTipoEscala . '" and te.codPessoa="' . $codPessoa . '" order by te.dataPrevisao asc');
		return $query->getRow();
	}
	public function verificaTrocaAcompanhamData($dataPrevisao = NULL, $codEscala = NULL)
	{
		$query = $this->db->query('select p.nomeExibicao as nomeExibicaoSai, pp.nomeExibicao as nomeExibicaoEntra, te.*
		from  svc_trocasescalas te
		left join svc_escalas e on e.codEscala=te.codEscala
		left join sis_pessoas p on p.codPessoa=te.codPessoa
		left join sis_pessoas pp on pp.codPessoa=te.trocadoPor
		where te.dataPrevisao = "' . $dataPrevisao . '" and te.codEscala="' . $codEscala . '" order by te.dataPrevisao asc');
		return $query->getRow();
	}
	public function listaTrocas($codEscala = NULL)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select p.nomeExibicao as nomeExibicaoSai, pp.nomeExibicao as nomeExibicaoEntra,te.*
		from  svc_trocasescalas te
		left join svc_previsaoescala e on e.codPrevisaoEscala=te.codPrevisaoEscala        
		left join sis_pessoas p on p.codPessoa=te.codPessoa
		left join sis_pessoas pp on pp.codPessoa=te.trocadoPor
        where te.codEscala="' . $codEscala . '" order by te.dataPrevisao desc'); //te.dataPrevisao>=CURDATE()
		return $query->getResult();
	}


	public function listaTrocasFuturas($codEscala = NULL)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select p.nomeExibicao as nomeExibicaoSai, pp.nomeExibicao as nomeExibicaoEntra,te.*
		from  svc_trocasescalas te
		left join svc_previsaoescala e on e.codPrevisaoEscala=te.codPrevisaoEscala        
		left join sis_pessoas p on p.codPessoa=te.codPessoa
		left join sis_pessoas pp on pp.codPessoa=te.trocadoPor
        where te.codEscala="' . $codEscala . '" and te.dataPrevisao >= CURDATE()order by te.dataPrevisao desc'); //te.dataPrevisao>=CURDATE()
		return $query->getResult();
	}


	public function dadosTroca($codTrocaEscala = NULL)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select DATE_FORMAT(te.dataPrevisao, "%d/%m/%Y") as dataPrevisaoEscala,p.nomeExibicao as nomeExibicaoSai, pp.nomeExibicao as nomeExibicaoEntra,te.*
		from  svc_trocasescalas te
		left join svc_previsaoescala e on e.codPrevisaoEscala=te.codPrevisaoEscala        
		left join sis_pessoas p on p.codPessoa=te.codPessoa
		left join sis_pessoas pp on pp.codPessoa=te.trocadoPor
        where te.codTrocaEscala="' . $codTrocaEscala . '" order by te.dataPrevisao desc'); //te.dataPrevisao>=CURDATE()
		return $query->getRow();
	}


	public function listaDatasVermelhas($codEscala = NULL)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from svc_datasvermelhas order by dataVermelha');
		return $query->getResult();
	}

	public function listaDropDownMotivosAfastamentos()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select codStatus as id, descricao as text 
		from svc_statusservico where codStatus<>1 and descricao is not null');
		return $query->getResult();
	}

	public function verificaMembroCadastrado($codEscala = NULL, $codPessoa = NULL)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from svc_membrosescala where codEscala="' . $codEscala . '" and codPessoa="' . $codPessoa . '"');
		return $query->getRow();
	}


	public function addmembro($codEscala = NULL, $codPessoa = NULL)
	{

		$query = $this->db->query('insert into svc_membrosescala (codMembroEscala, codEscala, codPessoa, dataCriacao, codAutor) VALUES (NULL, "' . $codEscala . '", "' . $codPessoa . '", current_timestamp(), "' . session()->codPessoa . '");
		');
		return true;
	}


	public function removerAfastamento($codAfastamento = NULL)
	{
		$query = $this->db->query('delete from svc_afastamentos
		where codAfastamento="' . $codAfastamento . '"
		');
		return true;
	}


	public function insertDataVermelha($dataVermelha = NULL, $descricao = NULL, $recorrente = NULL)
	{

		$query = $this->db->query('insert into svc_datasvermelhas (codDataVermelha, dataVermelha, recorrente, descricao, ativo) VALUES (NULL, "' . $dataVermelha . '","' . $recorrente . '", "' . $descricao . '", 1);');
		return true;
	}

	
	public function desativarDataVermelha($codDataVermelha = NULL)
	{
		$query = $this->db->query('update svc_datasvermelhas
		set ativo=0 where codDataVermelha="' . $codDataVermelha . '"
		');
		return true;
	}

	public function ativarDataVermelha($codDataVermelha = NULL)
	{
		$query = $this->db->query('update svc_datasvermelhas
		set ativo=1 where codDataVermelha="' . $codDataVermelha . '"
		');
		return true;
	}

	public function removerDataVermelha($codDataVermelha = NULL)
	{
		$query = $this->db->query('delete from svc_datasvermelhas
		where codDataVermelha="' . $codDataVermelha . '"
		');
		return true;
	}

	public function removerDefinitivo($codMembroEscala = NULL)
	{
		$query = $this->db->query('delete from svc_membrosescala
		where codMembroEscala="' . $codMembroEscala . '"
		');
		return true;
	}



	public function removerTroca($codTrocaEscala = NULL)
	{
		$query = $this->db->query('delete from svc_trocasescalas
		where codTrocaEscala="' . $codTrocaEscala . '"
		');
		return true;
	}

	public function atualizarPrevisaoEscala($codEscala = NULL, $dataLimiteLiberacao = NULL, $folgaLiberacao = NULL)
	{
		$query = $this->db->query('update svc_escalas
		set dataLimiteLiberacao="' . $dataLimiteLiberacao . '",
		folgaLiberacao="' . $folgaLiberacao . '",
		dataUltimaLiberacao="' . date('Y-m-d H:i') . '",
		dataAtualizacao="' . date('Y-m-d H:i') . '"
		where codEscala="' . $codEscala . '"
		');
		return true;
	}




	public function prontoServico(
		$codMembroEscala = NULL
	) {

		$query = $this->db->query('update svc_membrosescala
		set  dataAtualizacao="' . date('Y-m-d H:i') . '",		
		modificadoPor="' . session()->codPessoa . '",
		codStatus=1
		where codMembroEscala="' . $codMembroEscala . '"
		');
		return true;
	}

	public function atualizadataUltimoEscalacaoPreta(
		$codMembroEscala = NULL,
		$dataUltimoEscalacaoPreta = NULL
	) {

		$query = $this->db->query('update svc_membrosescala
		set  dataUltimoEscalacaoPreta="' . $dataUltimoEscalacaoPreta . '",	
		dataAtualizacao="' . date('Y-m-d H:i') . '",		
		modificadoPor="' . session()->codPessoa . '"
		where codMembroEscala="' . $codMembroEscala . '"
		');
		return true;
	}


	public function updateTrocaEscala($codTrocaEscala = NULL, $tipoTroca = NULL, $trocadoPor = NULL, $observacoes = NULL)
	{

		$query = $this->db->query('update svc_trocasescalas 
		set tipoTroca="' . $tipoTroca . '",
		trocadoPor="' . $trocadoPor . '",
		observacoes="' . $observacoes . '"
		where codTrocaEscala="' . $codTrocaEscala . '"');
		return true;
	}

	public function addTrocarEscala($codEscala = NULL, $codPrevisaoEscala = NULL, $codPessoaOriginal = NULL, $dataPrevisao = NULL, $codTipoEscala = NULL, $codPessoaTroca = NULL, $tipoTroca = NULL, $observacoes = NULL, $dataCriacao = NULL, $codAutor = NULL)
	{
		/*
		$verifica2 = $this->db->query('select * from svc_trocasescalas where codPessoa<>"' . $codPessoaOriginal . '" and trocadoPor="' . $codPessoaTroca . '" and codTipoEscala="' . $codTipoEscala . '"
		and dataPrevisao>=CURDATE()');

		if (!empty($verifica2->getRow())) {
			return 4;
		}


		$verifica = $this->db->query('select * from svc_trocasescalas where codPessoa="' . $codPessoaOriginal . '" and codTipoEscala="' . $codTipoEscala . '"
		and dataPrevisao>=CURDATE()');

		if (!empty($verifica->getRow())) {
			return 3;
		}

	*/

		$query = $this->db->query('insert into svc_trocasescalas (codTrocaEscala, codPrevisaoEscala, codEscala, dataPrevisao, codTipoEscala, codPessoa,trocadoPor, tipoTroca, observacoes, codAutor, dataCriacao) VALUES (NULL, "' . $codPrevisaoEscala . '", "' . $codEscala . '", "' . $dataPrevisao . '", "' . $codTipoEscala . '", "' . $codPessoaOriginal . '", "' . $codPessoaTroca . '", "' . $tipoTroca . '", "' . $observacoes . '","' . $codAutor . '", current_timestamp());');
		return true;
	}



	public function atualizadataUltimoEscalacaoVermelha(
		$codMembroEscala = NULL,
		$dataUltimoEscalacaoVermelha = NULL
	) {

		$query = $this->db->query('update svc_membrosescala
		set  dataUltimoEscalacaoVermelha="' . $dataUltimoEscalacaoVermelha . '",	
		dataAtualizacao="' . date('Y-m-d H:i') . '",		
		modificadoPor="' . session()->codPessoa . '"
		where codMembroEscala="' . $codMembroEscala . '"
		');
		return true;
	}
	public function afastarMembroAdd(
		$codMembroEscala = NULL,
		$codStatus = NULL,
		$afastamentoIndeterminado = NULL,
		$dataInicioAfastamento = NULL,
		$dataEncerramentoAfastamento = NULL,
		$observacoes = NULL
	) {

		if ($afastamentoIndeterminado == 1) {
			$dataInicioAfastamento =  date('Y-m-d');
			$dataEncerramentoAfastamento = null;
		}

		$query = $this->db->query('
		insert into svc_afastamentos (codAfastamento, codMembroEscala, dataInicioAfastamento, dataEncerramentoAfastamento, afastamentoIndeterminado, observacoes, codAutor, dataAtualizacao, modificadoPor, codStatus) VALUES (NULL, "' . $codMembroEscala . '", "' . $dataInicioAfastamento . '", "' . $dataEncerramentoAfastamento . '","' . $afastamentoIndeterminado . '", "' . $observacoes . '", "' . session()->codPessoa . '", current_timestamp(), "' . session()->codPessoa . '", "' . $codStatus . '");
		');
		return true;
	}



	public function afastarMembroEdit(
		$codAfastamento = NULL,
		$codStatus = NULL,
		$afastamentoIndeterminado = NULL,
		$dataInicioAfastamento = NULL,
		$dataEncerramentoAfastamento = NULL,
		$observacoes = NULL
	) {

		$complemento = '';
		if ($afastamentoIndeterminado == 1) {
			$complemento = '
			,dataEncerramentoAfastamento=null,
			dataInicioAfastamento=CURDATE()			
		';
		} else {
			$complemento = '
			,dataInicioAfastamento="' . $dataInicioAfastamento . '"
			,dataEncerramentoAfastamento="' . $dataEncerramentoAfastamento . '"		
		';
		}

		$query = $this->db->query('update svc_afastamentos
		set  codStatus="' . $codStatus . '",
		observacoes="' . $observacoes . '",
		dataAtualizacao="' . date('Y-m-d H:i') . '",		
		modificadoPor="' . session()->codPessoa . '",
		afastamentoIndeterminado="' . $afastamentoIndeterminado . '"
		' . $complemento . '
		where codAfastamento="' . $codAfastamento . '"
		');
		return true;
	}

	public function afastarMembroAgora(
		$codMembroEscala = NULL,
		$codStatus = NULL,
		$afastamentoIndeterminado = NULL,
		$dataInicioAfastamento = NULL,
		$dataEncerramentoAfastamento = NULL,
		$observacoes = NULL
	) {

		$complemento = '';
		if ($afastamentoIndeterminado == 1) {
			$complemento = '
			,dataEncerramentoAfastamento=null,
			dataInicioAfastamento=CURDATE()			
		';
		} else {
			$complemento = '
			,dataInicioAfastamento="' . $dataInicioAfastamento . '"
			,dataEncerramentoAfastamento="' . $dataEncerramentoAfastamento . '"		
		';
		}

		$query = $this->db->query('update svc_afastamentos
		set  codStatus="' . $codStatus . '",
		observacoes="' . $observacoes . '",
		dataAtualizacao="' . date('Y-m-d H:i') . '",		
		modificadoPor="' . session()->codPessoa . '",
		afastamentoIndeterminado="' . $afastamentoIndeterminado . '"
		' . $complemento . '
		where codMembroEscala="' . $codMembroEscala . '"
		');
		return true;
	}
	public function concorrrentesEscala($codCargo = NULL)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select p.*,me.codMembroEscala,me.codEscala
		from sis_pessoas p  
		left join sis_cargos c on c.codCargo=p.codCargo
		left join svc_membrosescala me on p.codPessoa=me.codPessoa
		left join svc_escalas e on e.codEscala=me.codEscala
		where p.codCargo="' . $codCargo . '"
		and p.ativo=1 order by c.ordenacaoCargo, p.nomeExibicao asc');
		return $query->getResult();
	}
	public function pegaMembroEscala($codMembroEscala)
	{
		$query = $this->db->query('select * from svc_membrosescala where codMembroEscala = "' . $codMembroEscala . '"');
		return $query->getRow();
	}
	public function getAfastamento($codAfastamento)
	{
		$query = $this->db->query('select a.*,p.nomeExibicao 
		from svc_afastamentos a		
		left join  svc_membrosescala me on a.codMembroEscala=me.codMembroEscala
		left join sis_pessoas p on p.codPessoa=me.codPessoa
		where a.codAfastamento = "' . $codAfastamento . '"');
		return $query->getRow();
	}

	public function listaDropDownMembrosEscalas($codEscala = NULL)
	{
		$query = $this->db->query('select me.codPessoa as id,p.nomeExibicao as text
		from svc_membrosescala me
		left join sis_pessoas p on p.codPessoa=me.codPessoa
		left join sis_cargos c on c.codCargo=p.codCargo
		where me.codEscala = "' . $codEscala . '" and p.nomeExibicao is not null and p.ativo=1 
		order by c.ordenacaoCargo');
		return $query->getResult();
	}
	public function verificaMembroAfastado($codPessoa = NULL, $codEscala = NULL, $data = NULL)
	{
		$query = $this->db->query('select a.*
		from svc_afastamentos a	
		join svc_membrosescala me on a.codMembroEscala=me.codMembroEscala
		where me.codEscala = "' . $codEscala . '" and me.codPessoa="' . $codPessoa . '"
		and a.dataInicioAfastamento <= "' . $data . '" and DATE_ADD(a.dataEncerramentoAfastamento, INTERVAL 1 DAY) >= "' . $data . '"
		and a.dataEncerramentoAfastamento >= CURDATE()
		');
		return $query->getRow();
	}

	public function listaImpedidos($codEscala = NULL, $data = NULL)
	{
		$query = $this->db->query('select a.*,me.codPessoa
		from svc_afastamentos a	
		join svc_membrosescala me on a.codMembroEscala=me.codMembroEscala
		where me.codEscala = "' . $codEscala . '" 
		and (a.dataInicioAfastamento <= "' . $data . '" and DATE_ADD(a.dataEncerramentoAfastamento, INTERVAL 1 DAY) >= "' . $data . '" 
		and a.dataEncerramentoAfastamento >= CURDATE())
		or (a.afastamentoIndeterminado =1 and a.ativo=1)
		');
		return $query->getResult();
	}

	public function folgas($codEscala = NULL, $data = NULL)
	{
		$query = $this->db->query('select 
		codPessoa,nomeExibicao,max(dataUltimoEscalacaoPreta) as dataUltimoEscalacaoPreta,max(dataUltimoEscalacaoVermelha) as dataUltimoEscalacaoVermelha,
		DATEDIFF(CURDATE(), dataUltimoEscalacaoPreta) as folgasPreta,DATEDIFF(CURDATE(), dataUltimoEscalacaoVermelha) as folgasVermelha 
		from 
		(
		select me.codPessoa, p.nomeExibicao, me.dataUltimoEscalacaoPreta as dataUltimoEscalacaoPreta,me.dataUltimoEscalacaoVermelha  as dataUltimoEscalacaoVermelha
				from svc_membrosescala me
				left join sis_pessoas p on p.codPessoa=me.codPessoa
				where me.codEscala=' . $codEscala . '
				
		Union 
		select me.codPessoa, p.nomeExibicao, max(DATE_FORMAT(pe.dataPrevisao, "%Y-%m-%d")) as dataUltimoEscalacaoPreta, null as dataUltimoEscalacaoVermelha
				from svc_membrosescala me 
			   left join sis_pessoas p on p.codPessoa=me.codPessoa
			   left join svc_previsaoescala pe on pe.codPessoa=me.codPessoa
				where me.codEscala=' . $codEscala . ' and pe.codTipoEscala=1
				group by me.codPessoa
		UNION
		select me.codPessoa, p.nomeExibicao, null as dataUltimoEscalacaoPreta, max(DATE_FORMAT(pe.dataPrevisao, "%Y-%m-%d")) as dataUltimoEscalacaoVermelha
				from svc_membrosescala me 
			   left join sis_pessoas p on p.codPessoa=me.codPessoa
			   left join svc_previsaoescala pe on pe.codPessoa=me.codPessoa
				where me.codEscala=' . $codEscala . ' and pe.codTipoEscala=2
				group by me.codPessoa
		   )x
		   group by codPessoa
		');
		return $query->getResult();
	}

	public function folgasPretas($codEscala = NULL, $data = NULL)
	{
		$query = $this->db->query('select codPessoa,dataUltimoEscalacaoPreta,DATEDIFF("' . $data . '", dataUltimoEscalacaoPreta) as folgasPreta from (select 
		codPessoa,nomeExibicao,max(dataUltimoEscalacaoPreta) as dataUltimoEscalacaoPreta
		from 
		(
		select me.codPessoa, p.nomeExibicao, me.dataUltimoEscalacaoPreta as dataUltimoEscalacaoPreta
				from svc_membrosescala me
				left join sis_pessoas p on p.codPessoa=me.codPessoa
				where me.codEscala="' . $codEscala . '"
				
		Union 
		select me.codPessoa, p.nomeExibicao, max(DATE_FORMAT(pe.dataPrevisao, "%Y-%m-%d")) as dataUltimoEscalacaoPreta
				from svc_membrosescala me 
			   left join sis_pessoas p on p.codPessoa=me.codPessoa
			   left join svc_previsaoescala pe on pe.codPessoa=me.codPessoa
				where me.codEscala="' . $codEscala . '" and pe.codTipoEscala=1 and pe.dataPrevisao<="' . $data . '"
				group by me.codPessoa
		   )x
		   group by codPessoa,nomeExibicao)y order by folgasPreta desc
		');
		return $query->getResult();
	}
	public function folgasVermelhas($codEscala = NULL, $data = NULL)
	{
		$query = $this->db->query('select codPessoa,dataUltimoEscalacaoVermelha,DATEDIFF("' . $data . '", dataUltimoEscalacaoVermelha) as folgasVermelha from (select 
		codPessoa,nomeExibicao,max(dataUltimoEscalacaoVermelha) as dataUltimoEscalacaoVermelha
		from 
		(
		select me.codPessoa, p.nomeExibicao, me.dataUltimoEscalacaoVermelha as dataUltimoEscalacaoVermelha
				from svc_membrosescala me
				left join sis_pessoas p on p.codPessoa=me.codPessoa
				where me.codEscala="' . $codEscala . '"
				
		Union 
		select me.codPessoa, p.nomeExibicao, max(DATE_FORMAT(pe.dataPrevisao, "%Y-%m-%d")) as dataUltimoEscalacaoVermelha
				from svc_membrosescala me 
			   left join sis_pessoas p on p.codPessoa=me.codPessoa
			   left join svc_previsaoescala pe on pe.codPessoa=me.codPessoa
				where me.codEscala="' . $codEscala . '" and pe.codTipoEscala=2 and pe.dataPrevisao<="' . $data . '"
				group by me.codPessoa
		   )x
		   group by codPessoa)y order by folgasVermelha desc
		');
		return $query->getResult();
	}
	public function maiorFolgaPreta($escala = NULL, $codTipoEscala)
	{

		//$codTipoEscala=1 - PRETA
		//$codTipoEscala=2 - VERMELHA

		$ultimoServico = $this->db->query('select min(me.dataUltimoEscalacaoPreta),max(pe.dataPrevisao) as dataPrevisao
		from svc_membrosescala me
		join svc_previsaoescala pe  on me.codPessoa=pe.codPessoa 
		where me.escala=1 and pe.dataPrevisao <"2024-03-09"');
		$dataUltimoEscalacaoPreta = $ultimoServico->getRow()->dataUltimoEscalacaoPreta;
	}


	public function maiorFolgaVermelha($codPessoa = NULL)
	{
		$ultimoServico = $this->db->query('select dataUltimoEscalacaoVermelha
		from svc_membrosescala where codPessoa="' . $codPessoa . '"');
		$dataUltimoEscalacaoVermelha = $ultimoServico->getRow()->dataUltimoEscalacaoVermelha;


		$previsaoVermelha = $this->db->query('select max(dataPrevisao) as dataPrevisao
		from svc_previsaoescala where codTipoEscala=1 and codPessoa="' . $codPessoa . '"');
		$previsaoVermelha = $previsaoVermelha->getRow()->dataPrevisao;

		if ($previsaoVermelha >= $dataUltimoEscalacaoVermelha) {
			return $previsaoVermelha;
		} else {
			return $dataUltimoEscalacaoVermelha;
		}
	}


	public function afastamentosMembro($codMembroEscala)
	{
		$query = $this->db->query('select a.*,p.nomeExibicao,  ss.descricao as statusServico
		from svc_afastamentos a		
		left join  svc_membrosescala me on a.codMembroEscala=me.codMembroEscala
		left join sis_pessoas p on p.codPessoa=me.codPessoa
		left join svc_statusservico ss on ss.codStatus=a.codStatus
		where (a.dataEncerramentoAfastamento >= CURDATE() or a.afastamentoIndeterminado=1)and a.codMembroEscala = "' . $codMembroEscala . '"');
		return $query->getResult();
	}
	public function pegaPorCodigo($codEscala)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codEscala = "' . $codEscala . '"');
		return $query->getRow();
	}
}
