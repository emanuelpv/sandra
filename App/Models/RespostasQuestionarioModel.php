<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;
use CodeIgniter\Model;

class RespostasQuestionarioModel extends Model {
    
	protected $table = 'tes_respostas';
	protected $primaryKey = 'codResposta';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codQuestionario', 'codDadosDemograficos','codPergunta', 'resposta', 'severidade', 'dataResposta','codPessoa','codPaciente'];
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
        $query = $this->db->query('select * from tes_respostas');
        return $query->getResult();
    }

	public function pegaPorCodigo($codResposta)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codResposta = "'.$codResposta.'"');
        return $query->getRow();
    }



}