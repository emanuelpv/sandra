<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;
use CodeIgniter\Model;

class PacientesMedicamentosContinuoModel extends Model {
    
	protected $table = 'sis_pacientesmedicamentoscontinuo';
	protected $primaryKey = 'codPacienteMedicamento';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codPaciente', 'descricaoMedicamento', 'observacao', 'dataCriacao', 'codAutor'];
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
        $query = $this->db->query('select * from sis_pacientesmedicamentoscontinuo');
        return $query->getResult();
    }

	public function pegaTudoPaciente($codPaciente)
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select * from sis_pacientesmedicamentoscontinuo where codPaciente='.$codPaciente);
        return $query->getResult();
    }


	public function pegaPorCodigo($codPacienteMedicamento)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codPacienteMedicamento = "'.$codPacienteMedicamento.'"');
        return $query->getRow();
    }



}