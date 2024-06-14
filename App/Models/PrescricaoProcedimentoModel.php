<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class PrescricaoProcedimentoModel extends Model
{

	protected $table = 'amb_atendimentosprescricoesprocedimentos';
	protected $primaryKey = 'codPrescricaoProcedimento';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codAtendimentoPrescricao', 'codProcedimento', 'qtde', 'codStatus', 'observacao', 'codAutor', 'dataCriacao', 'dataAtualizacao'];
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
		$query = $this->db->query('select * from amb_atendimentosprescricoesprocedimentos');
		return $query->getResult();
	}
	public function getAllPorPrescricao($codAtendimentoPrescricao)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select ap.*, pe.nomeExibicao,p.referencia,p.descricao as descricaoProcedimento,sp.*
		from amb_atendimentosprescricoesprocedimentos ap
		left join amb_procedimentos p on p.codProcedimento=ap.codProcedimento
		left join sis_statusprocedimentos sp on sp.codStatusProcedimento=ap.codStatus
		left join sis_pessoas pe on pe.codPessoa = ap.codAutor
		where ap.codAtendimentoPrescricao=' . $codAtendimentoPrescricao);
		return $query->getResult();
	}

	public function pegaClonarProcedimentos($codAtendimentoPrescricao)
	{
		$query = $this->db->query('select *
		from amb_atendimentosprescricoesprocedimentos 
		where codAtendimentoPrescricao = "' . $codAtendimentoPrescricao . '" order by codPrescricaoProcedimento desc');
		return $query->getResult();
	}


	public function pegaPorCodigo($codPrescricaoProcedimento)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codPrescricaoProcedimento = "' . $codPrescricaoProcedimento . '"');
		return $query->getRow();
	}

	public function listaDropDownProcedimentos()
	{
		$query = $this->db->query('select codProcedimento as id, concat(referencia," - ",descricao, " - RS ", valor) as text from amb_procedimentos where descricao is not null and usm is not null and valor is not null');
		return $query->getResult();
	}
	public function listaDropDownTabelaRef()
	{
		$query = $this->db->query('select codTabelaRef as id, descricao as text from sis_tabelarefconvenio where descricao is not null');
		return $query->getResult();
	}

	public function pegaConvenio($codPaciente = NULL)
	{
		$query = $this->db->query('select * from sis_conveniopaciente cp
		join sis_convenios c on c.codConvenio=cp.codConvenio
		join sis_tabelarefconvenio trc on trc.codTabelaRef=c.codTabelaRef
		where cp.codPaciente="' . $codPaciente . '"');
		return $query->getResult();
	}
}
