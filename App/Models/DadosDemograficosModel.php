<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;
use CodeIgniter\Model;

class DadosDemograficosModel extends Model {
    
	protected $table = 'tes_dadosdemograficos';
	protected $primaryKey = 'codDadosDemograficos';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['experienciaProduto','experienciaTecnologia','experienciaProfissional','ocupacao','educacao','dataResposta','tipoUsuario','codQuestionario', 'nomeCompleto', 'nomeExibicao', 'idade', 'sexo', 'tempoUso', 'concordou', 'tempoExperienciaAgilidade', 'nivelEducacao', 'posicaoOrganizacao', 'tempoExperienciaProjetos', 'tipoOrganizacao', 'tamanhoOrganizacao', 'escopoOrganizacao', 'codPaciente', 'codPessoa', 'setor', 'modulo', 'nrTentativa'];
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
        $query = $this->db->query('select * from tes_dadosdemograficos');
        return $query->getResult();
    }

	public function pegaPorCodigo($codDadosDemograficos)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codDadosDemograficos = "'.$codDadosDemograficos.'"');
        return $query->getRow();
    }



}