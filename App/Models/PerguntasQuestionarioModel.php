<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;
use CodeIgniter\Model;

class PerguntasQuestionarioModel extends Model {
    
	protected $table = 'tes_perguntasquestionario';
	protected $primaryKey = 'codPerguntaQuestionario';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codPergunta', 'codQuestionario'];
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
        $query = $this->db->query('select * from tes_perguntasquestionario');
        return $query->getResult();
    }

	public function pegaPorCodigo($codPerguntaQuestionario)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codPerguntaQuestionario = "'.$codPerguntaQuestionario.'"');
        return $query->getRow();
    }


	public function pegaPorQuestionario($codQuestionario)
    {
        $query = $this->db->query('select * from 
		tes_perguntasquestionario  pq 
		join tes_perguntas p on p.codPergunta=pq.codpergunta
		join tes_categoriaperguntas cp on cp.codCategoria = p.codCategoria where pq.codQuestionario = "'.$codQuestionario.'"
		order by ordem asc');
        return $query->getResult();
    }
	
	public function listaDropDownPerguntas($codQuestionario,$codTipoQuestionario)
    {
        $query = $this->db->query('select p.codPergunta id, p.descricaoPergunta as text FROM tes_perguntas p left join tes_perguntasquestionario pq on pq.codPergunta=p.codPergunta where pq.codPergunta is null and p.codTipoQuestionario='.$codTipoQuestionario);
        return $query->getResult();
    }

}