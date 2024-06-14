<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;
use CodeIgniter\Model;

class ItensModeloModel extends Model {
    
	protected $table = 'ges_itensmodelo';
	protected $primaryKey = 'codItemModelo';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['unidade','codCat', 'descricao', 'tipoMaterial', 'dataCriacao', 'dataAtualizacao', 'codAutor'];
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
        $query = $this->db->query('select im.*, tm.descricaoTipoMaterial,p.nomeExibicao
		from ges_itensmodelo im
		join ges_tipomaterial tm on tm.codTipoMaterial=im.tipoMaterial
		join sis_pessoas p on p.codPessoa=im.codAutor');
        return $query->getResult();
    }

	public function pegaPorCodigo($codItemModelo)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codItemModelo = "'.$codItemModelo.'"');
        return $query->getRow();
    }



}