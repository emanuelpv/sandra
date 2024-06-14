<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class FaturamentoKitsModel extends Model
{

	protected $table = 'fat_faturamentokits';
	protected $primaryKey = 'codFaturamentoKit';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codAuditor', 'codFatura', 'codAtendimento', 'codPrescricaoKit', 'autorPrescricao', 'dataPrescricao', 'codKit', 'quantidade', 'valor', 'codLocalAtendimento', 'dataCriacao', 'dataAtualizacao', 'codStatus', 'codAutor', 'observacoes'];
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
		$query = $this->db->query('select * from fat_faturamentokits');
		return $query->getResult();
	}


	public function pegaPorCodigo($codFaturamentoKit)
	{
		$query = $this->db->query('select fk.*,la.codLocalAtendimento,la.descricaoLocalAtendimento,d.codDepartamento from 
		fat_faturamentokits fk
		left join amb_atendimentoslocais la on la.codLocalAtendimento=fk.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento
 		where fk.codFaturamentoKit = "' . $codFaturamentoKit . '"');
		return $query->getRow();
	}


	public function pegaKit($codKit)
	{
		$query = $this->db->query('select * from sis_kits 
		where codKit = "' . $codKit . '"');
		return $query->getRow();
	}

	public function kitsFatura($codFatura)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select f.codStatusFatura, pe.nomeExibicao,fk.*,sm.*,
		(fk.quantidade * fk.valor ) as subTotal,k.descricaoKit as descricaoKit,
		k.codKit,d.descricaoDepartamento,la.descricaoLocalAtendimento
		from fat_faturamentokits fk
        left join fat_faturamento f on f.codFatura=fk.codFatura 
		left join sis_pessoas pe on pe.codPessoa=fk.codAuditor
		left join sis_kits k on k.codKit = fk.codKit
		left join sis_statuskit sm on sm.codStatusKit=fk.codStatus
		left join amb_atendimentoslocais la on la.codLocalAtendimento=fk.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento
        where fk.codFatura ="' . $codFatura . '" order by fk.codFaturamentoKit desc');
		return $query->getResult();
	}

	public function verificaExistenciaKitFaturado($codAtendimento, $codPrescricaoKit)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codAtendimento = ' . $codAtendimento . ' and codPrescricaoKit=' . $codPrescricaoKit);
		return $query->getRow();
	}


	public function kits($codAtendimento = NULL, $ccusto = NULL, $dataInicio = NULL, $dataEncerramento = NULL)
	{
		$filtro = '';
		$ccusto = str_replace('"', "", $ccusto);
		if ($ccusto !== "0" and $ccusto !== NULL and $ccusto !== "" and $ccusto !== " ") {

			$filtro .= ' and d.codDepartamento in(' . $ccusto . ')';
		}

		if ($dataInicio !== NULL and $dataInicio !== "" and $dataInicio !== " ") {

			$filtro .= ' and ap.dataInicio >="' . $dataInicio . '"';
		}


		if ($dataEncerramento !== NULL and $dataEncerramento !== "" and $dataEncerramento !== " ") {

			$filtro .= ' and ap.dataEncerramento <="' . $dataEncerramento . '"';
		}




		$query = $this->db->query('select apk.*,ap.*,k.*,apk.codStatus as codStatusKit,apk.codAutor as codAutorPrescricao, apk.dataCriacao as dataCriacaoPrescricaoKit,apk.dataAtualizacao as dataAtualizacaoPrescricaoKit 
		FROM amb_atendimentosprescricoeskits apk
		join amb_atendimentosprescricoes ap on ap.codAtendimentoPrescricao=apk.codAtendimentoPrescricao
		join amb_atendimentoslocais al on al.codLocalAtendimento=ap.codLocalAtendimento
		join sis_departamentos d on d.codDepartamento=al.codDepartamento
		join sis_kits k on k.codKit=apk.codKit
		where ap.codAtendimento = ' . $codAtendimento . $filtro . ' and ap.dataInicio >= "2022-09-03" order by ap.codAtendimento desc');
		return $query->getResult();
	}



	public function removeFatura($codFatura)
	{
		$query = $this->db->query('delete from ' . $this->table . ' where codFatura = ' . $codFatura);
		return true;
	}
}
