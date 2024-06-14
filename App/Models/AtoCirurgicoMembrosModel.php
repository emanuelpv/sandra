<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class AtoCirurgicoMembrosModel extends Model
{

	protected $table = 'cir_atocirurgicomembros';
	protected $primaryKey = 'codAtoCirurgicoMembro';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codEstadoFederacao', 'codFuncaoMembro', 'conselhoMembro', 'codAtoCirurgico', 'nomeMembro', 'inscricaoMembro', 'dataCriacao', 'dataAtualizacao', 'codAutor'];
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
		$query = $this->db->query('select * from cir_atocirurgicomembros');
		return $query->getResult();
	}


	public function getAllMembrosAtoCirurgico($codAtoCirurgico = NULL)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select mc.*, f.descricaoFuncao,siglaEstadoFederacao 
		from cir_atocirurgicomembros  mc
		left join cir_atocirurgicofuncoes f on mc.codFuncaoMembro=f.codFuncao
		left join sis_estadosfederacao ef on ef.codEstadoFederacao=mc.codEstadoFederacao
		where mc.codAtoCirurgico="' . $codAtoCirurgico . '" order by ordenacao asc');
		return $query->getResult();
	}
	public function listaDropDownFuncoes()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select distinct codFuncao as id, descricaoFuncao as text from cir_atocirurgicofuncoes order by ordenacao asc');
		return $query->getResult();
	}
	public function pegaMembros()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('
		select distinct nomeMembro from cir_atocirurgicomembros
		union
		select distinct p.nomeCompleto as nomeMembro FROM sis_especialidadesmembros em
		join sis_pessoas p on p.codPessoa=em.codPessoa
		where p.ativo=1		
		');
		return $query->getResult();
	}


	public function pegaConselhos()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('
		select distinct nomeConselho from sis_conselhos');
		return $query->getResult();
	}

	public function pegaDadosMembro($nomeMembroAtoCirurgico = NULL)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('
		select * from (
		select distinct nomeMembro,conselhoMembro, inscricaoMembro as inscricaoMembro,codEstadoFederacao, dataCriacao from cir_atocirurgicomembros 
		where nomeMembro="' . $nomeMembroAtoCirurgico . '"
		union all
		select distinct p.nomeCompleto as nomeMembro, c.nomeConselho as conselhoMembro, em.numeroInscricao as inscricaoMembro, ef.codEstadoFederacao, p.dataCriacao FROM sis_especialidadesmembros em
		join sis_pessoas p on p.codPessoa=em.codPessoa 
		join sis_estadosfederacao ef on ef.codEstadoFederacao=em.codEstadoFederacao
		join sis_especialidades e on e.codEspecialidade=em.codEspecialidade 
		join sis_conselhos c on c.codConselho=e.codConselho 
		where p.ativo=1 and p.nomeCompleto="' . $nomeMembroAtoCirurgico . '") x order by dataCriacao desc limit 1');
		return $query->getRow();
	}
	public function pegaPorCodigo($codAtoCirurgicoMembro)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codAtoCirurgicoMembro = "' . $codAtoCirurgicoMembro . '"');
		return $query->getRow();
	}
}
