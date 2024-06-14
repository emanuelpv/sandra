<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class ProjetosModel extends Model {
    
	protected $table = 'sis_projetos';
	protected $primaryKey = 'codProjeto';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codOrganizacao','descricaoProjeto', 'codDepartamento', 'codSupervisor', 'codGestor', 'codStatusProjeto', 'justificativa','objetivo','beneficios', 'codTipoProjeto', 'dataInicioProjeto', 'dataEncerramentoProjeto'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;    
	

	public function pega_projetos_por_codProjeto($codProjeto)
    {
       
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select d.*,p.*, ges.nomeExibicao as nomeGestor,sp.descricaoStatusProjeto,tp.descricaoTipoProjeto
		from sis_projetos p
		left join sis_departamentos d on d.codDepartamento = p.codDepartamento and d.codOrganizacao=p.codOrganizacao
		left join sis_projetosmembros pm on pm.codProjeto = p.codProjeto and pm.codTipoMembro=2
		left join sis_pessoas ges on ges.codPessoa = pm.codMembro
		left join sis_statusprojetos sp on sp.codStatusProjeto=p.codStatusProjeto
		left join sis_tiposprojetos tp on tp.codTipoProjeto=p.codTipoProjeto
		where p.codOrganizacao = '.$codOrganizacao.' and p.codProjeto = "'.$codProjeto.'" order by ges.codCargo asc');
        return $query->getRow();
    }

	public function pega_projetos()
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select d.*,p.*,sup.nomeExibicao as nomeSupervisor, ges.nomeExibicao as nomeGestor,sp.descricaoStatusProjeto,tp.descricaoTipoProjeto
		from sis_projetos p
		left join sis_departamentos d on d.codDepartamento = p.codDepartamento and d.codOrganizacao=p.codOrganizacao
		left join sis_pessoas sup on sup.codPessoa = p.codSupervisor and sup.codOrganizacao=p.codOrganizacao
		left join sis_pessoas ges on ges.codPessoa = p.codGestor and ges.codOrganizacao=p.codOrganizacao
		left join sis_statusprojetos sp on sp.codStatusProjeto=p.codStatusProjeto
		left join sis_tiposprojetos tp on tp.codTipoProjeto=p.codTipoProjeto
		where p.codOrganizacao='.$codOrganizacao);
        return $query->getResult();
    }





}