<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class EspecialidadesMembroModel extends Model {
    
	protected $table = 'sis_especialidadesmembros';
	protected $primaryKey = 'codEspecialidadeMembro';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codFaixaEtaria','atende','codOrganizacao','codEspecialidade', 'codPessoa', 'codEstadoFederacao', 'numeroInscricao', 'numeroSire', 'observacoes', 'dataCriacao', 'dataAtualizacao', 'autor'];
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
        $query = $this->db->query('select * from sis_especialidadesmembros');
        return $query->getResult();
    }

	
	public function pegaEspecialidadePorMembro($codEspecialidade,$codMembro)
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select * from sis_especialidadesmembros
		where codEspecialidade='.$codEspecialidade.' and codPessoa='.$codMembro);
        return $query->getRow();
    }

	public function pegaPorCodigo($codEspecialidadeMembro)
    {
        $query = $this->db->query('select em.*, e.descricaoEspecialidade,p.nomeExibicao,ef.siglaEstadoFederacao
		from sis_especialidades e 
		left join sis_especialidadesmembros em on e.codEspecialidade=em.codEspecialidade 
		left join sis_pessoas p on em.codPessoa=p.codPessoa 
		left join sis_estadosfederacao ef on ef.codEstadoFederacao=em.codEstadoFederacao 
		where em.codEspecialidadeMembro='.$codEspecialidadeMembro.' order by p.codCargo asc, p.datanascimento asc');
        return $query->getRow();
    }

	public function pegaEspecialidadesPorCodMembro($codMembro)
    {
        $query = $this->db->query('select em.*, e.descricaoEspecialidade,p.nomeExibicao,ef.siglaEstadoFederacao
		from sis_especialidades e 
		left join sis_especialidadesmembros em on e.codEspecialidade=em.codEspecialidade 
		left join sis_pessoas p on em.codPessoa=p.codPessoa 
		left join sis_estadosfederacao ef on ef.codEstadoFederacao=em.codEstadoFederacao 
		where em.codPessoa='.$codMembro.' order by p.codCargo asc, p.datanascimento asc');
        return $query->getResult();
    }

}