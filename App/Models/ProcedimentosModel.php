<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;
use CodeIgniter\Model;

class ProcedimentosModel extends Model {
    
	protected $table = 'amb_procedimentos';
	protected $primaryKey = 'codProcedimento';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['referencia','descricao', 'usm', 'valor'];
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
        $query = $this->db->query('select * from amb_procedimentos where usm is not null');
        return $query->getResult();
    }
	
	public function listaDropDown()
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select codProcedimento as id, concat(referencia," - ",descricao, " - RS ", valor) as text from amb_procedimentos where descricao is not null and usm is not null and valor is not null');
        return $query->getResult();
    }

	public function pegaPorCodigo($codProcedimento)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codProcedimento = "'.$codProcedimento.'"');
        return $query->getRow();
    }



}