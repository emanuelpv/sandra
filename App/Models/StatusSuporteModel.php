<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;
use CodeIgniter\Model;

class StatusSuporteModel extends Model {
    
	protected $table = 'sis_statussuporte';
	protected $primaryKey = 'codStatusSuporte';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['descricaoStatusSuporte','codCorStatusSuporte'];
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
        $query = $this->db->query('select * from sis_statussuporte');
        return $query->getResult();
    }

	public function pegaPorCodigo($codStatusSuporte)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codStatusSuporte = "'.$codStatusSuporte.'"');
        return $query->getRow();
    }

    public function pegaPorNome($nome)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where descricaoStatusSuporte = "'.$nome.'"');
        return $query->getRow();
    }


	public function listaDropDown()
    {
        $query = $this->db->query('select codStatusSuporte as id, descricaoStatusSuporte as text from ' . $this->table.' where descricaoStatusSuporte is not null');
        return $query->getResult();
    }
public function listaPercentualConclusao()
    {
        $query = $this->db->query('select cod_percentualConclusao as id, descricaoPercentualConclusao as text from sis_percentuaisconclusao where descricaoPercentualConclusao is not null');
        return $query->getResult();
    }


}