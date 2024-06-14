<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class PrevisaoAltaModel extends Model
{

	protected $table = 'amb_atendimentosprevalta';
	protected $primaryKey = 'codPrevAlta';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['indeterminado','codAtendimento', 'codAutor', 'condicaoAlta', 'dataPrevAlta'];
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
		$query = $this->db->query('select * from amb_atendimentosprevalta apa 
		join sis_pessoas pe on pe.codPessoa=apa.codAutor order by apa.codPrevAlta desc');
		return $query->getResult();
	}

	public function pegaPrevisaoAltaPorCodAtendimento($codAtendimento = NULL)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from amb_atendimentosprevalta apa 
		join sis_pessoas pe on pe.codPessoa=apa.codAutor 
		where apa.codAtendimento="' . $codAtendimento . '"
		order by apa.codPrevAlta desc');
		return $query->getResult();
	}

	public function pegaPorCodigo($codPrevAlta)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codPrevAlta = "' . $codPrevAlta . '"');
		return $query->getRow();
	}
}
