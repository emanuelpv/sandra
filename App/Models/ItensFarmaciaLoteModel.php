<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;
use CodeIgniter\Model;

class ItensFarmaciaLoteModel extends Model {
    
	protected $table = 'sis_itensfarmacialote';
	protected $primaryKey = 'codLote';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codLocalizacao','codDeposito','valorAquisicao','requisicao','empenho','nf','validadeIndeterminada','codOrganizacao','codItem','codAutor','nrLote', 'codBarra', 'quantidade', 'dataValidade', 'dataCriacao', 'dataAtualizacao', 'dataInventario', 'observacao'];
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
        $query = $this->db->query('select * from sis_itensfarmacialote');
        return $query->getResult();
    }

	public function pegaPorCodigo($codLote)
    {
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select iff.*,ifl.*,ifl.observacao as observacaoLote,p.nomeExibicao 
		from sis_itensfarmacialote ifl 
		left join sis_itensfarmacia iff on iff.codItem=ifl.codItem
		left join sis_pessoas p on p.codPessoa = ifl.codAutor
		where ifl.codOrganizacao = '.$codOrganizacao.' and ifl.codLote = "'.$codLote.'"');
        return $query->getRow();
    }

	
	public function pegaPorCodItem($codItem)
    {
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select iff.*,ifl.*,ifl.observacao as observacaoLote,p.nomeExibicao from sis_itensfarmacialote ifl 
		left join sis_itensfarmacia iff on iff.codItem=ifl.codItem
		left join sis_pessoas p on p.codPessoa = ifl.codAutor
		where ifl.codOrganizacao = '.$codOrganizacao.' and iff.codItem = "'.$codItem.'"');
        return $query->getResult();
    }





}