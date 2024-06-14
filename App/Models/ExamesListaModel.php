<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class ExamesListaModel extends Model
{

	protected $table = 'age_agendamentosexameslista';
	protected $primaryKey = 'codExameLista';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codTipoAgenda','cadastroReserva','dataAtualizacao', 'autor',	'dataCriacao', 'numeroConselho', 'codTipo', 'codConselho', 'codOrganizacao', 'descricaoExameLista'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;


	public function listaDropDownExamesLista()
	{
		$query = $this->db->query('select codExameLista as id,descricaoExameLista as text from age_agendamentosexameslista order by descricaoExameLista');
		return $query->getResult();
	}
	public function listaDropDownStatusAgendamento()
	{
		$query = $this->db->query('select codStatus as id,nomeStatus as text from amb_examesstatusconfig order by codStatus');
		return $query->getResult();
	}
	public function listaDropDownStatusExame()
	{
		$query = $this->db->query('select codStatus as id,nomeStatus as text from amb_examesstatusconfig order by codStatus');
		return $query->getResult();
	}


	public function listaDropDownExamesListaDisponivelMarcacao()
	{
		$query = $this->db->query('select distinct e.codExameLista as id,e.descricaoExameLista as text 
		from age_agendamentosexameslista  e
		join age_agendamentosexameslistamembros em on e.codExameLista=em.codExameLista
		where em.atende=1
		order by descricaoExameLista');
		return $query->getResult();

	}	
	public function listaDropDowncodExameLista()
	{
		$query = $this->db->query('select distinct e.codExameLista as id,e.descricaoExameLista as text 
		from age_agendamentosexameslista  e
		join age_agendamentosexameslistamembros em on e.codExameLista=em.codExameLista
		where em.atende=1
		order by descricaoExameLista');
		return $query->getResult();

	}	
	
	public function lookupCodNomeExamesJson($codExameLista)
	{
		$query = $this->db->query('select * from age_agendamentosexameslista where codExameLista in (' . $codExameLista . ')');
		return $query->getResult();
	}


	public function listaDropDownExamesListaDisponivelMarcacaoExame()
	{
		$query = $this->db->query('select distinct e.codExameLista as id,e.descricaoExameLista as text 
		from age_agendamentosexameslista  e
		join age_agendamentosexameslistamembros em on e.codExameLista=em.codExameLista
		where em.atende=1 and e.codTipoAgenda=2
		order by descricaoExameLista');
		return $query->getResult();
	}

	public function listaDropDownTipoAgendamento()
	{
		$query = $this->db->query('select codTipo as id,nomeTipo as text from amb_examestipo order by codTipo');
		return $query->getResult();
	}


	public function listaDropDownTipoExame()
	{
		$query = $this->db->query('select codTipo as id,nomeTipo as text from amb_examestipo order by codTipo');
		return $query->getResult();
	}
	public function listaDropDownEspecialistas($codExameLista)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select p.codPessoa as id,p.nomeExibicao as text 
		from sis_pessoas p
		join age_agendamentosexameslistamembros em on p.codPessoa= em.codPessoa	
		join age_agendamentosexameslista e on e.codExameLista=em.codExameLista
		where e.codExameLista = ' . $codExameLista . ' and e.codOrganizacao=' . $codOrganizacao . '
		and p.ativo =1
		order by p.codCargo, p.dataNascimento');
		return $query->getResult();
	}

	public function listaDropDownEspecialistasDisponivelMarcacao($codExameLista = null)
	{
		$filtro="";
		if($codExameLista !== NULL and $codExameLista !== ""){
			$filtro=" and e.codExameLista = ".$codExameLista;
		}
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select distinct p.codPessoa as id,p.nomeExibicao as text 
		from sis_pessoas p
		join age_agendamentosexameslistamembros em on p.codPessoa= em.codPessoa	
		join age_agendamentosexameslista e on e.codExameLista=em.codExameLista
		where  e.codOrganizacao=' . $codOrganizacao .  $filtro . '
		and p.ativo =1 and em.atende=1
		order by p.codCargo, p.dataNascimento');
		return $query->getResult();
	}

	public function especialistas()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select distinct em.atende,p.codPessoa,p.nomeExibicao,c.nomeConselho,c.nomeArea, em.numeroInscricao, e.descricaoExameLista,p.fotoPerfil,ef.siglaEstadoFederacao
		from sis_pessoas p
		left join age_agendamentosexameslistamembros em on p.codPessoa= em.codPessoa	
		left join sis_estadosfederacao ef on ef.codEstadoFederacao= em.codEstadoFederacao	
		left join age_agendamentosexameslista e on e.codExameLista=em.codExameLista
		left join sis_conselhos c on c.codConselho=e.codConselho
		where e.codOrganizacao=' . $codOrganizacao . '
		and p.ativo =1
		order by p.codCargo, p.dataNascimento');
		return $query->getResult();
	}

	public function especialistasDisponiveis()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select distinct p.codPessoa,p.nomeExibicao,c.nomeConselho, em.numeroInscricao, e.descricaoExameLista,p.fotoPerfil,ef.siglaEstadoFederacao
		from sis_pessoas p
		left join age_agendamentosexameslistamembros em on p.codPessoa= em.codPessoa	
		left join sis_estadosfederacao ef on ef.codEstadoFederacao= em.codEstadoFederacao	
		left join age_agendamentosexameslista e on e.codExameLista=em.codExameLista
		left join sis_conselhos c on c.codConselho=e.codConselho
		where e.codOrganizacao=' . $codOrganizacao . '
		and p.ativo =1 and em.atende=1
		order by p.codCargo, p.dataNascimento');
		return $query->getResult();
	}


	public function listaDropDownEstadosFederacao()
	{
		$query = $this->db->query('select codEstadoFederacao as id,siglaEstadoFederacao as text from sis_estadosfederacao');
		return $query->getResult();
	}

	public function pegaExameListaPorCodExameLista($codExameLista)
	{
		$query = $this->db->query('select *
		from age_agendamentosexameslista e where e.codExameLista=' . $codExameLista);
		return $query->getResult();
	}


	public function pegaMembros($codExameLista)
	{
		$query = $this->db->query('select mi.descricaoMotivoInativacao,em.*, e.descricaoExameLista,p.nomeExibicao,p.ativo,ef.siglaEstadoFederacao
		from age_agendamentosexameslista e 
		left join age_agendamentosexameslistamembros em on e.codExameLista=em.codExameLista 
		left join sis_pessoas p on em.codPessoa=p.codPessoa 
		left join sis_estadosfederacao ef on ef.codEstadoFederacao=em.codEstadoFederacao 
		left join sis_motivosinativacao mi on mi.codMotivoInativacao=p.codMotivoInativo
		where e.codExameLista=' . $codExameLista . ' order by p.ativo desc,p.codCargo asc, p.datanascimento asc');
		return $query->getResult();
	}

	public function pegaMembroPorExameListaECodPessoa($codExameLista, $codPessoa)
	{
		$query = $this->db->query('select * from age_agendamentosexameslistamembros where codExameLista=' . $codExameLista . ' and codPessoa=' . $codPessoa);
		return $query->getRow();
	}


	public function pegaConselho($codConselho)
	{

		$query = $this->db->query('select * from sis_conselhos where codConselho=' . $codConselho);

		if ($query  == NULL) {
			return array();
		} else {
			return $query->getRow();
		}
	}


	public function listaDropDownConselhos()
	{
		$query = $this->db->query('select codConselho as id,nomeConselho as text from sis_conselhos');
		return $query->getResult();
	}


	public function pegaExamesLista()
	{
		$query = $this->db->query('select * from ' . $this->table . ' order by  descricaoExameLista asc');
		return $query->getResult();
	}


	public function pegaexameListaPorNome($descricaoExameLista)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where descricaoExameLista="' . $descricaoExameLista . '"');
		return $query->getRow();
	}
	public function lookupCodNomeExameLista($codExameLista)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codExameLista="' . $codExameLista . '"');
		return $query->getRow();
	}
	public function lookupCodNomeExamesListaJson($examesLista)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codExameLista in (' . $examesLista . ')');
		return $query->getResult();
	}
	public function importarExamesLista($dados)
	{

		$db      = \Config\Database::connect();
		$builder = $db->table("age_agendamentosexameslista");
		$builder->insert($dados);
		return 1;
	}
	public function insertMembro($dados)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table("age_agendamentosexameslistamembros");
		$builder->insert($dados);
	}


	public function examesListaApolo()
	{
		$apolo = \Config\Database::connect('apolo', false);
		$query = $apolo->query('select *
		from tp_examesLista');
		return $query->getResult();
	}
	public function membrosExamesListaApolo()
	{
		$apolo = \Config\Database::connect('apolo', false);
		$query = $apolo->query('select *
		from pes_exameLista');
		return $query->getResult();
	}
}
