<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class AtributosSistemaModel extends Model {
    
	protected $table = 'sis_atributossistema';
	protected $primaryKey = 'codAtributosSistema';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['descricaoAtributoSistema', 'nomeAtributoSistema'];
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
        $query = $this->db->query('select * from sis_atributossistema');
        return $query->getResult();
    }

	public function pegaAtributosPadraoOrganizacao()
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select codAtributosSistema,nomeAtributoSistema, descricaoAtributoSistema,visivelFormulario,obrigatorio,visivelLDAP,cadastroRapido,ordenacao, tipo,tamanho,icone from sis_atributossistema');
        return $query->getResult();
    }

	public function pegaPorCodigo($codAtributosSistema)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codAtributosSistema = "'.$codAtributosSistema.'"');
        return $query->getRow();
    }



}