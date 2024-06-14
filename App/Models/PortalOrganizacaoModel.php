<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;
use CodeIgniter\Model;

class PortalOrganizacaoModel extends Model {
    
	protected $table = 'sis_portalorganizacao';
	protected $primaryKey = 'codPortal';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['ativoHero','dataAtualizacao','codAutor','codOrganizacao', 'corFundoPrincipal', 'corTextoPrincipal', 'corLinhaTabela','corTextoTabela', 'corSecundaria', 'corMenus', 'corTextoMenus', 'corBackgroundMenus'];
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
        $query = $this->db->query('select * from sis_portalorganizacao');
        return $query->getResult();
    }

	public function pegaPorCodigo($codOrganizacao)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codOrganizacao = "'.$codOrganizacao.'"');
        return $query->getRow();
    }



}