<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class MapeamentoAtributosLDAPModel extends Model {
    
	protected $table = 'sis_mapeamentoatributosldap';
	protected $primaryKey = 'codMapAttrLDAP';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codOrganizacao','codServidorLDAP', 'nomeAtributoSistema', 'nomeAtributoLDAP'];
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
        $query = $this->db->query('select * from sis_mapeamentoatributosldap where codOrganizacao='.$codOrganizacao);
        return $query->getResult();
    }

	public function pegaTudoPorServidor($codServidorLDAP)
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select * from sis_mapeamentoatributosldap m 
		left join sis_servicoldap s on m.codServidorLDAP = s.codServidorLDAP and s.codOrganizacao='.$codOrganizacao.' where m.codServidorLDAP='.$codServidorLDAP);
        return $query->getResult();
    }

	public function pegaAtributosMapeados($codServidorLDAP)
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select * from sis_mapeamentoatributosldap m
		where m.codServidorLDAP='.$codServidorLDAP);
        return $query->getResult();
    }

	
	public function pegaPorCodigo($codMapAttrLDAP)
    {
        $query = $this->db->query('select m.*,s.codTipoLDAP from sis_mapeamentoatributosldap m join sis_servicoldap s on m.codServidorLDAP=s.codServidorLDAP where m.codMapAttrLDAP = "'.$codMapAttrLDAP.'"');
        return $query->getRow();
    }



}