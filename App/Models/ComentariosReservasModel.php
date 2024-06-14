<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;
use CodeIgniter\Model;

class ComentariosReservasModel extends Model {
    
	protected $table = 'amb_agendamentosreservascomentarios';
	protected $primaryKey = 'codContato';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codAgendamentoReserva', 'comentario', 'dataCriacao', 'codPessoa', 'codPaciente'];
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
        $query = $this->db->query('select * from amb_agendamentosreservascomentarios');
        return $query->getResult();
    }

	public function pegaPorCodigo($codContato)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codContato = "'.$codContato.'"');
        return $query->getRow();
    }



}