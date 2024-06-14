<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class PreferenciasModel extends Model {
    
	protected $table = 'sis_preferenciaspessoas';
	protected $primaryKey = 'codPreferencia';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codResponsavel','dataAtualizacao','codOrganizacao','codPessoa', 'categoriasSolicitacoes', 'statusSolicitacoes', 'periodoSolicitacoes', 'autorPreferencia','codSolicitante','codDepartamento'];
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
		$codPessoa = session()->codPessoa;
        $query = $this->db->query('select * from sis_preferenciaspessoas');
        return $query->getResult();
    }

	public function pegaPorCodigo($codPreferencia)
    {
		
        $query = $this->db->query('select * from ' . $this->table. ' where codPreferencia = '.$codPreferencia);
        return $query->getRow();
    }

	public function pegaPorCodigoPessoa($codPessoa)
    {
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select * from ' . $this->table. ' where codOrganizacao = '.$codOrganizacao.' and codPessoa = '.$codPessoa);
        return $query->getRow();
    }


}