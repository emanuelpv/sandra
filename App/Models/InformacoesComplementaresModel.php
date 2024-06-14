<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;
use CodeIgniter\Model;

class InformacoesComplementaresModel extends Model {
    
	protected $table = 'ges_inforcomplementares';
	protected $primaryKey = 'codInforComplementar';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codRequisicao', 'codCategoria', 'conteudo', 'dataCriacao', 'dataAtualizacao', 'codAutor'];
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
        $query = $this->db->query('select * from ges_inforcomplementares');
        return $query->getResult();
    }
	public function informacoesComplementaresRequisicao($codRequisicao)
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select * from ges_inforcomplementares ic
		left join ges_categoriainforcomplementar c on c.codCategoria = ic.codCategoria 
		left join sis_pessoas p on p.codPessoa = ic.codAutor 
		where ic.codRequisicao='.$codRequisicao);
        return $query->getResult();
    }
	public function pegaPorCodigo($codInforComplementar)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codInforComplementar = "'.$codInforComplementar.'"');
        return $query->getRow();
    }

	public function listaDropDownCategoriaInformacoesComplementares()
    {
        $query = $this->db->query('select codCategoria as id, descricaoCategoria as text from ges_categoriainforcomplementar where descricaoCategoria is not null');
        return $query->getResult();
    }


}