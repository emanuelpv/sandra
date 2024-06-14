<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;
use CodeIgniter\Model;

class DepositosLocalizacaoModel extends Model {
    
	protected $table = 'sis_depositoslocalizacao';
	protected $primaryKey = 'codLocalizacao';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codAutor','dataAtualizacao','dataCriacao','codStatus','codDeposito','descricaoLocalizacao', 'codStatus'];
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
        $query = $this->db->query('select * from sis_depositoslocalizacao');
        return $query->getResult();
    }

	
	public function pegaTudoDeposito($codDeposito = null)
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select de.*,d.codDeposito, d.descricaoDeposito, d.codAutor as codAutorDeposito, d.codDepartamento, d.codStatus as codStatusDeposito,p.nomeExibicao 
		from sis_depositoslocalizacao de
		left join sis_depositos d on d.codDeposito=de.codDeposito
		left join sis_pessoas p on p.codPessoa=de.codAutor
		where d.codOrganizacao='.$codOrganizacao.' and de.codDeposito = '.$codDeposito.' order by de.descricaoLocalizacao asc');
        return $query->getResult();
    }
	public function pegaPorCodigo($codLocalizacao)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codLocalizacao = "'.$codLocalizacao.'"');
        return $query->getRow();
    }

	public function listaDropDown()
    {
        $query = $this->db->query('select codLocalizacao as id, descricaoLocalizacao as text from sis_depositoslocalizacao');
        return $query->getResult();
    }
	public function listaDropDownPorDeposito($codDeposito = null)
    {
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select codLocalizacao as id, descricaoLocalizacao as text from sis_depositoslocalizacao
		where codDeposito="'.$codDeposito.'"');
        return $query->getResult();
    }


}