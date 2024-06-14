<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class AtributosSistemaOrganizacaoModel extends Model {
    
	protected $table = 'sis_atributossistemaorganizacao';
	protected $primaryKey = 'codAtributosSistemaOrganizacao';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codAtributosSistema', 'codOrganizacao', 'descricaoAtributoSistema', 'visivelFormulario', 'obrigatorio','visivelLDAP','cadastroRapido','ordenacao', 'tipo','tamanho','icone'];
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
        $query = $this->db->query('select a.nomeAtributoSistema, aso.* from sis_atributossistemaorganizacao aso join sis_atributossistema a on a.codAtributosSistema= aso.codAtributosSistema where aso.codOrganizacao='.$codOrganizacao. ' order by aso.ordenacao asc,aso.codAtributosSistema asc');
        return $query->getResult();
    }

	

	public function pegaTudoPorOrganizacao($codOrganizacao)
    {
				
        $query = $this->db->query('select a.nomeAtributoSistema, aso.* from sis_atributossistemaorganizacao aso join sis_atributossistema a on a.codAtributosSistema= aso.codAtributosSistema where aso.codOrganizacao='.$codOrganizacao. ' order by aso.ordenacao asc,aso.codAtributosSistema asc');
        return $query->getResult();
    }

	public function pegaFormularioRapido($codOrganizacao)
    {
				
        $query = $this->db->query('select a.nomeAtributoSistema, aso.* from sis_atributossistemaorganizacao aso join sis_atributossistema a on a.codAtributosSistema= aso.codAtributosSistema where aso.cadastroRapido=1 and aso.codOrganizacao='.$codOrganizacao. ' order by aso.ordenacao asc,aso.codAtributosSistema asc');
        return $query->getResult();
    }

	public function pegaAtributosOrganizacao($visivelFomulario = NULL ,$visivelLDAP = NULL,$obrigatorio = NULL)
    {
		$condicao ='';
		if($visivelFomulario == 1){
			$condicao .= ' and aso.visivelFormulario='.$visivelFomulario;
		}
		if($visivelLDAP == 1){
			$condicao .= ' and aso.visivelLDAP='.$visivelLDAP;
		}
		
		if($obrigatorio == 1){
			$condicao .= ' and aso.visivelFormulario='.$obrigatorio;
		}
		$condicao .=' order by aso.ordenacao asc,aso.codAtributosSistema asc';

		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select a.nomeAtributoSistema, aso.* 
		from sis_atributossistemaorganizacao aso join sis_atributossistema a on a.codAtributosSistema= aso.codAtributosSistema 
		where aso.codOrganizacao='.$codOrganizacao.$condicao);
        return $query->getResult();
    }

	public function pegaPorCodigo($codAtributosSistemaOrganizacao)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codAtributosSistemaOrganizacao = "'.$codAtributosSistemaOrganizacao.'"');
        return $query->getRow();
    }



}