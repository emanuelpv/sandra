<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class ExamesListaMembroModel extends Model {
    
	protected $table = 'age_agendamentosexameslistamembros';
	protected $primaryKey = 'codExameListaMembro';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['atende','codOrganizacao','codExameLista', 'codPessoa', 'codEstadoFederacao', 'numeroInscricao', 'numeroSire', 'observacoes', 'dataCriacao', 'dataAtualizacao', 'autor'];
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
        $query = $this->db->query('select * from age_agendamentosexameslistamembros');
        return $query->getResult();
    }

	
	public function pegaExameListaPorMembro($codExameLista,$codMembro)
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select * from age_agendamentosexameslistamembros
		where codExameLista='.$codExameLista.' and codPessoa='.$codMembro);
        return $query->getRow();
    }

	public function pegaPorCodigo($codExameListaMembro)
    {
        $query = $this->db->query('select em.*, e.descricaoExameLista,p.nomeExibicao,ef.siglaEstadoFederacao
		from age_agendamentosexameslista e 
		left join age_agendamentosexameslistamembros em on e.codExameLista=em.codExameLista 
		left join sis_pessoas p on em.codPessoa=p.codPessoa 
		left join sis_estadosfederacao ef on ef.codEstadoFederacao=em.codEstadoFederacao 
		where em.codExameListaMembro='.$codExameListaMembro.' order by p.codCargo asc, p.datanascimento asc');
        return $query->getRow();
    }

	public function pegaExamesListaPorCodMembro($codMembro)
    {
        $query = $this->db->query('select em.*, e.descricaoExameLista,p.nomeExibicao,ef.siglaEstadoFederacao
		from age_agendamentosexameslista e 
		left join amb_exameslistamembros em on e.codExameLista=em.codExameLista 
		left join sis_pessoas p on em.codPessoa=p.codPessoa 
		left join sis_estadosfederacao ef on ef.codEstadoFederacao=em.codEstadoFederacao 
		where em.codPessoa='.$codMembro.' order by p.codCargo asc, p.datanascimento asc');
        return $query->getResult();
    }

}