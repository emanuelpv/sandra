<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;
use CodeIgniter\Model;

class TaxasServicosModel extends Model {
    
	protected $table = 'amb_taxasservicos';
	protected $primaryKey = 'codTaxaServico';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['referencia', 'descricao', 'usm', 'valor'];
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
        $query = $this->db->query('select * from amb_taxasservicos');
        return $query->getResult();
    }

	public function pegaPorCodigo($codTaxaServico)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codTaxaServico = "'.$codTaxaServico.'"');
        return $query->getRow();
    }


	public function listaDropDown()
    {
        $query = $this->db->query('select codTaxaServico as id, concat(descricao," - RS ",valor) as text from ' . $this->table);
        return $query->getResult();
    }


}