<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;
use CodeIgniter\Model;

class TesteModel extends Model {
    
	protected $table = 'teste';
	protected $primaryKey = 'codPessoa';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['nome', 'telefone', 'dataNascimento'];
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
        $query = $this->db->query('select * from teste');
        return $query->getResult();
    }

	public function pegaPorCodigo($codPessoa)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codPessoa = "'.$codPessoa.'"');
        return $query->getRow();
    }



}