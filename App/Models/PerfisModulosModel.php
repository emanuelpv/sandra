<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class PerfisModulosModel extends Model {
    
	protected $table = 'sis_perfismodulos';
	protected $primaryKey = 'codPerfil,codModulo';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codOrganizacao','codPerfil','codModulo','listar', 'adicionar', 'editar', 'deletar','dataAtualizacao'];
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
        $query = $this->db->query('select * from sis_perfismodulos pm
		right  join sis_perfis p on p.codPerfil=p.codPerfil
		right  join sis_modulos m on m.codModulo=pm.codModulo');
        return $query->getResult();
    }


	public function pegaModulosVisiveis($codPerfil)
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('
		select '.$codPerfil.' as codPerfil, m.codModulo, m.nome, a.codAtalho
		from sis_perfismodulos pm
		join sis_modulos m on pm.listar =1 and m.codModulo=pm.codModulo
		left join sis_atalhos a on m.codModulo=a.codModulo and pm.codPerfil = a.codPerfil 
		where pm.codPerfil = '.$codPerfil.' and pm.codOrganizacao='.$codOrganizacao.' and m.pai is not null and m.pai<>0
		order by m.nome asc,m.nome asc');
        return $query->getResult();
    }
	
	public function pegaTudoPerfil($codPerfil)
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('
		select '.$codPerfil.' as codPerfil, m.codModulo, m.nome,mm.nome as pai, pm.listar,pm.adicionar,pm.editar,pm.deletar 
		from sis_perfismodulos pm
		right  join sis_modulos m on m.codModulo=pm.codModulo and pm.codPerfil = '.$codPerfil.' and pm.codOrganizacao='.$codOrganizacao.'
       	left join sis_modulos mm on mm.codModulo =  m.pai
		order by m.nome asc,mm.nome asc');
        return $query->getResult();
    }
	
	public function pegaTudoPerfilSame($codPerfil)
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('
		select '.$codPerfil.' as codPerfil, m.codModulo, m.nome,mm.nome as pai, pm.listar,pm.adicionar,pm.editar,pm.deletar 
		from sis_perfismodulos pm
		right  join sis_modulos m on m.codModulo=pm.codModulo and pm.codPerfil = '.$codPerfil.' and pm.codOrganizacao='.$codOrganizacao.' and  m.nome like "%same%"
       	left join sis_modulos mm on mm.codModulo =  m.pai
		order by m.nome asc,mm.nome asc');
        return $query->getResult();
    }

	public function verificaSeExiste($codPerfil,$codModulo)
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select * 
		from sis_perfismodulos where codPerfil = '.$codPerfil.' and codModulo = '.$codModulo.' and codOrganizacao='.$codOrganizacao
		);
        return $query->getRow();
    }


	public function atualiza_listar($codPerfil,$codModulo)
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $this->db->query('update sis_perfismodulos set listar =1 where codPerfil = '.$codPerfil.' and codModulo = '.$codModulo.' and codOrganizacao='.$codOrganizacao
		);
        return true;
    }

	public function atualiza_adicionar($codPerfil,$codModulo)
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $this->db->query('update sis_perfismodulos set adicionar =1 where codPerfil = '.$codPerfil.' and codModulo = '.$codModulo.' and codOrganizacao='.$codOrganizacao
		);
        return true;
    }
	public function atualiza_editar($codPerfil,$codModulo)
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $this->db->query('update sis_perfismodulos set editar =1 where codPerfil = '.$codPerfil.' and codModulo = '.$codModulo.' and codOrganizacao='.$codOrganizacao
		);
        return true;
    }
	public function atualiza_deletar($codPerfil,$codModulo)
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $this->db->query('update sis_perfismodulos set deletar =1 where codPerfil = '.$codPerfil.' and codModulo = '.$codModulo.' and codOrganizacao='.$codOrganizacao
		);
        return true;
    }

	public function pegaPorCodigo($codPerfil)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codPerfil = "'.$codPerfil.'"');
        return $query->getRow();
    }

	public function deletePerfilModulo($codPerfil,$codOrganizacao)
    {
        $query = $this->db->query('delete from ' . $this->table. ' where codPerfil = '.$codPerfil.' and codOrganizacao ='.$codOrganizacao);
        return true;
    }



}