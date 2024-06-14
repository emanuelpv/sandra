<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class FaturamentoProcedimentosModel extends Model
{

	protected $table = 'fat_faturamentoprocedimentos';
	protected $primaryKey = 'codFaturamentoProcedimento';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codAuditor', 'codFatura', 'codAtendimento', 'codPrescricaoProcedimento', 'autorPrescricao', 'dataPrescricao', 'codProcedimento', 'quantidade', 'valor', 'codLocalAtendimento', 'dataCriacao', 'dataAtualizacao', 'codStatus', 'codAutor', 'observacoes'];
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
		$query = $this->db->query('select * from fat_faturamentoprocedimentos');
		return $query->getResult();
	}

	public function procedimentosFatura($codFatura = NULL)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select f.codStatusFatura,pe.nomeExibicao,fp.*,sp.*,(fp.quantidade * fp.valor ) as subTotal,p.descricao as descricaoProcedimento,p.referencia,d.descricaoDepartamento,la.descricaoLocalAtendimento from 
		fat_faturamentoprocedimentos fp
        left join fat_faturamento f on f.codFatura=fp.codFatura 
		left join sis_pessoas pe on pe.codPessoa=fp.codAuditor
		left join amb_procedimentos p on p.codProcedimento=fp.codProcedimento
		left join sis_statusprocedimentos sp on sp.codStatusProcedimento=fp.codStatus
		left join amb_atendimentoslocais la on la.codLocalAtendimento=fp.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento
        where fp.codFatura = "' . $codFatura . '" order by fp.codFaturamentoProcedimento desc');
		return $query->getResult();
	}

	public function pegaProcedimento($codProcedimento)
	{
		$query = $this->db->query('select * from amb_procedimentos where codProcedimento = "' . $codProcedimento . '"');
		return $query->getRow();
	}


	public function pegaPorCodigo($codFaturamentoProcedimento)
	{
		$query = $this->db->query('select fp.*,la.codLocalAtendimento,la.descricaoLocalAtendimento,d.codDepartamento from 
		fat_faturamentoprocedimentos fp
		left join amb_atendimentoslocais la on la.codLocalAtendimento=fp.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento
 		where fp.codFaturamentoProcedimento = "' . $codFaturamentoProcedimento . '"');
		return $query->getRow();
	}

	public function verificaExistenciaProcedimentoFaturado($codAtendimento, $codPrescricaoProcedimento)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codAtendimento = ' . $codAtendimento . ' and codPrescricaoProcedimento=' . $codPrescricaoProcedimento);
		return $query->getRow();
	}


	public function procedimentos($codAtendimento = NULL, $ccusto = NULL, $dataInicio = NULL, $dataEncerramento = NULL)
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


		

		$query = $this->db->query('select fpp.*,ap.*,p.*,fpp.codStatus as codStatusProcedimento,fpp.codAutor as codAutorPrescricao, fpp.dataCriacao as dataCriacaoPrescricaoProcedimento,fpp.dataAtualizacao as dataAtualizacaoPrescricaoProcedimento 
		FROM amb_atendimentosprescricoesprocedimentos fpp
		join amb_atendimentosprescricoes ap on ap.codAtendimentoPrescricao=fpp.codAtendimentoPrescricao
		join amb_atendimentoslocais al on al.codLocalAtendimento=ap.codLocalAtendimento
		join sis_departamentos d on d.codDepartamento=al.codDepartamento
		join amb_procedimentos p on p.codProcedimento=fpp.codProcedimento
		where ap.codAtendimento = ' . $codAtendimento . $filtro . ' and ap.dataInicio >= "2022-09-03" order by ap.codAtendimento desc');
		return $query->getResult();  
	}


	public function removeFatura($codFatura)
	{
		$query = $this->db->query('delete from ' . $this->table . ' where codFatura = ' . $codFatura);
		return true;
	}
}
