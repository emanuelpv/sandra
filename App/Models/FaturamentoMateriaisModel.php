<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class FaturamentoMateriaisModel extends Model
{

	protected $table = 'fat_faturamentomateriais';
	protected $primaryKey = 'codFaturamentoMaterial';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codFatura', 'codAtendimento', 'codPrescricaoMaterial', 'codMaterial', 'autorPrescricao', 'dataPrescricao', 'quantidade', 'valor', 'codStatus', 'codAutor', 'codAuditor', 'codLocalAtendimento', 'dataCriacao', 'dataAtualizacao', 'observacoes'];
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
		$query = $this->db->query('select * from fat_faturamentomateriais');
		return $query->getResult();
	}

	public function materiaisFatura($codFatura)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select f.codStatusFatura,pe.nomeExibicao,fm.*,sm.*,
		(fm.quantidade * fm.valor ) as subTotal,iff.descricaoItem as descricaoMaterial,
		iff.nee,d.descricaoDepartamento,la.descricaoLocalAtendimento
		from fat_faturamentomateriais fm
        left join fat_faturamento f on f.codFatura=fm.codFatura 
		left join sis_pessoas pe on pe.codPessoa=fm.codAuditor
		left join sis_itensfarmacia  iff on iff.codItem = fm.codMaterial and iff.codCategoria =6 and iff.descricaoItem is not null
		left join sis_statusmaterial sm on sm.codStatusMaterial=fm.codStatus
		left join amb_atendimentoslocais la on la.codLocalAtendimento=fm.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento
        where fm.codFatura ="' . $codFatura . '" order by fm.codFaturamentoMaterial desc
		
		');
		return $query->getResult();
	}

	public function pegaPorCodigo($codFaturamentoMaterial)
	{
		$query = $this->db->query('select fm.*,la.codLocalAtendimento,la.descricaoLocalAtendimento,d.codDepartamento from 
		fat_faturamentomateriais fm
		left join amb_atendimentoslocais la on la.codLocalAtendimento=fm.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento
 		where fm.codFaturamentoMaterial = "' . $codFaturamentoMaterial . '"');
		return $query->getRow();
	}




	public function pegaMaterial($codMaterial)
	{
		$query = $this->db->query('select * from sis_itensfarmacia 
		where codItem = "' . $codMaterial . '"');
		return $query->getRow();
	}

	public function ultimoLancamento($codAtendimento)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codAtendimento = "' . $codAtendimento . '" order by codFaturamentoMaterial desc limit 1');
		return $query->getRow();
	}

	public function verificaExistenciaMaterialFaturado($codAtendimento, $codPrescricaoMaterial)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codAtendimento = ' . $codAtendimento . ' and codPrescricaoMaterial=' . $codPrescricaoMaterial);
		return $query->getRow();
	}


	public function materiais($codAtendimento = NULL, $ccusto = NULL, $dataInicio = NULL, $dataEncerramento = NULL)
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






		$query = $this->db->query('select apm.*,ap.*,apm.codStatus as codStatusMaterial, iff.*,apm.codAutor as codAutorPrescricao, apm.dataCriacao as dataCriacaoPrescricaoMaterial,apm.dataAtualizacao as dataAtualizacaoPrescricaoMaterial 
		FROM amb_atendimentosprescricoesmateriais apm
		join amb_atendimentosprescricoes ap on ap.codAtendimentoPrescricao=apm.codAtendimentoPrescricao
		join amb_atendimentoslocais al on al.codLocalAtendimento=ap.codLocalAtendimento
		join sis_departamentos d on d.codDepartamento=al.codDepartamento
		join sis_itensfarmacia iff on iff.codItem=apm.codMaterial
		where ap.codAtendimento = ' . $codAtendimento . $filtro . ' and ap.dataInicio >= "2022-09-03" order by ap.codAtendimento desc');
		return $query->getResult(); 
	}



	public function removeFatura($codFatura)
	{
		$query = $this->db->query('delete from ' . $this->table . ' where codFatura = ' . $codFatura);
		return true;
	}
}
