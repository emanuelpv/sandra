<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class AtoCirurgicoProcedimentosModel extends Model
{

	protected $table = 'cir_atocirurgicoprocedimentos';
	protected $primaryKey = 'codAtoCirurgicoProcedimento';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codAcomodacao', 'filme', 'viaAcesso', 'codTecnica', 'dataInicio', 'dataEncerramento', 'classificacaoProcedimento', 'codTabelaRef', 'codAtoCirurgico', 'codProcedimento', 'qtde', 'observacao'];
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
		$query = $this->db->query('select * from cir_atocirurgicoprocedimentos');
		return $query->getResult();
	}


	public function listaDropDownProcedimentos($codTabelaRef = NULL)
	{
		if ($codTabelaRef == NULL) {
			$codTabelaRef = 0;
		}
		$query = $this->db->query('select codProcedimento as id, concat(referencia," - ",descricao, " - RS ", valor) as text 
		from amb_procedimentos 
		where codTabelaRef="' . $codTabelaRef . '" and descricao is not null and usm is not null and valor is not null');
		return $query->getResult();
	}
	public function listaDropDownTecnica()
	{
		$query = $this->db->query('select codTecnica as id, descricao as text
		from cir_atocicurgicotecnicas where descricao is not null');
		return $query->getResult();
	}
	public function listaDropDownAcomodacoes()
	{
		$query = $this->db->query('select codAcomodacao as id, descricao as text
		from cir_atocirurgicoacomodacoes where descricao is not null');
		return $query->getResult();
	}
	public function listaDropDownTabelaRef()
	{
		$query = $this->db->query('select trc.codTabelaRef as id, concat(c.descricao," - ",trc.descricao) as text 
		from sis_tabelarefconvenio trc 
		join sis_convenios c on trc.codTabelaRef=c.codTabelaRef where trc.descricao is not null');
		return $query->getResult();
	}

	public function pegaConvenio($codPaciente = NULL)
	{
		$query = $this->db->query('select cp.*,p.codPlano,c.codTabelaRef from sis_pacientes p
		left join sis_conveniopaciente cp on p.codPaciente=cp.codPaciente
		left join sis_convenios c on c.codConvenio=cp.codConvenio
		left join sis_tabelarefconvenio trc on trc.codTabelaRef=c.codTabelaRef
		where p.codPaciente="' . $codPaciente . '" and cp.ativo=1');
		return $query->getResult();
	}

	public function getAllProcedimentosAtoCirurgico($codAtoCirurgico = NULL)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select dc.*,pr.descricao,pr.referencia
		from cir_atocirurgicoprocedimentos dc
		left join amb_procedimentos pr on pr.codProcedimento=dc.codProcedimento
		where dc.codAtoCirurgico="' . $codAtoCirurgico . '"');
		return $query->getResult();
	}

	public function pegaPorCodigo($codAtoCirurgicoProcedimento)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codAtoCirurgicoProcedimento = "' . $codAtoCirurgicoProcedimento . '"');
		return $query->getRow();
	}

	public function setaSecundario($codAtoCirurgico)
	{
		if ($codAtoCirurgico !== NULL and $codAtoCirurgico !== 0) {
			$query = $this->db->query('update cir_atocirurgicoprocedimentos set classificacaoProcedimento=0 where codAtoCirurgico = "' . $codAtoCirurgico . '"');
		}
		return true;
	}
}
