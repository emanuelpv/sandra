<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class SuspensaoMedicamentosModel extends Model
{

	protected $table = 'amb_atendimentossuspensaomedicamentos';
	protected $primaryKey = 'codSuspensaoMedicamento';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codPaciente', 'codPrescricaoMedicamento', 'codMedicamento', 'codAtendimento', 'codAutor', 'motivo', 'qtdDevolucao', 'dataCriacao'];
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
		$query = $this->db->query('select * from amb_atendimentossuspensaomedicamentos');
		return $query->getResult();
	}
	public function verificaGuasParaSuspender($codPrescricaoMedicamento, $codMedicamento)
	{

		$atendimentos = $this->db->query('select ap.* from 
		amb_atendimentosprescricoes ap 
		join amb_atendimentosprescricoesmedicamentos apm on apm.codAtendimentoPrescricao=ap.codAtendimentoPrescricao
		where apm.codPrescricaoMedicamento=' . $codPrescricaoMedicamento);
		$dataInicioPrescricao = $atendimentos->getRow()->dataInicio;
		$codAtendimento = $atendimentos->getRow()->codAtendimento;

		$query = $this->db->query('select *
        from amb_controleantimicrobiano cam
		where cam.codAtendimento="' . $codAtendimento . '" and cam.codItem = "' . $codMedicamento . '"  and cam.dataInicio <="' . $dataInicioPrescricao . '" and cam.dataEncerramento >="' . $dataInicioPrescricao . '" and cam.codStatus>=1');
		return $query->getRow();
	}
	public function pegaPorCodigo($codSuspensaoMedicamento)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codSuspensaoMedicamento = "' . $codSuspensaoMedicamento . '"');
		return $query->getRow();
	}
}
