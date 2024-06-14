<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class AtendimentoslocaisModel extends Model
{

	protected $table = 'amb_atendimentoslocais';
	protected $primaryKey = 'codLocalAtendimento';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codAtendimento','codDepartamento', 'codSituacaoLocalAtendimento', 'codStatusLocalAtendimento', 'observacoes', 'codOrganizacao', 'descricaoLocalAtendimento', 'codTipoLocalAtendimento', 'codStatusLocalAtendimento', 'codPessoa', 'dataAtualizacao'];
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
		$query = $this->db->query('select * from amb_atendimentoslocais');
		return $query->getResult();
	}

	public function pegaPorCodigo($codLocalAtendimento)
	{
		$query = $this->db->query('select * from amb_atendimentoslocais where codLocalAtendimento = "' . $codLocalAtendimento . '"');
		return $query->getRow();
	}

	public function listaDropDown($codDepartamento = null)
	{
		$query = $this->db->query('select codLocalAtendimento as id, descricaoLocalAtendimento as text from amb_atendimentoslocais where codDepartamento = "' . $codDepartamento . '" order by descricaoLocalAtendimento');
		return $query->getResult();
	}

	public function listaDropDownAtivos($codDepartamento = null)
	{

		if ($codDepartamento == 13) {
			$query = $this->db->query('select codLocalAtendimento as id, descricaoLocalAtendimento as text 
		from amb_atendimentoslocais 
		where codStatusLocalAtendimento = 1  and codTipoLocalAtendimento in(1,3)
		and codDepartamento = "' . $codDepartamento . '" 
		order by descricaoLocalAtendimento');
		} else {
			$query = $this->db->query('select codLocalAtendimento as id, descricaoLocalAtendimento as text 
		from amb_atendimentoslocais 
		where codStatusLocalAtendimento = 1  and codTipoLocalAtendimento in(1,3)
		and codDepartamento = "' . $codDepartamento . '" 
		order by descricaoLocalAtendimento');
		}


		return $query->getResult();
	}

	public function listaDropDownSalasGuichesAtivos($codDepartamento = null)
	{

		if ($codDepartamento == 13) {
			$query = $this->db->query('select codLocalAtendimento as id, descricaoLocalAtendimento as text 
		from amb_atendimentoslocais 
		where codStatusLocalAtendimento = 1  and codTipoLocalAtendimento in(3,4)
		and codDepartamento = "' . $codDepartamento . '" 
		order by descricaoLocalAtendimento');
		} else {
			$query = $this->db->query('select codLocalAtendimento as id, descricaoLocalAtendimento as text 
		from amb_atendimentoslocais 
		where codStatusLocalAtendimento = 1  and codTipoLocalAtendimento in(3,4)
		and codDepartamento = "' . $codDepartamento . '" 
		order by descricaoLocalAtendimento');
		}


		return $query->getResult();
	}



	
	public function listaDropDownLeitosLocaisProcedimentosAtivos($codDepartamento = null)
	{

		if ($codDepartamento == 13) {
			$query = $this->db->query('select codLocalAtendimento as id, descricaoLocalAtendimento as text 
		from amb_atendimentoslocais 
		where codStatusLocalAtendimento = 1  and codTipoLocalAtendimento in(2,3)
		and codDepartamento = "' . $codDepartamento . '" 
		order by descricaoLocalAtendimento');
		} else {
			$query = $this->db->query('select codLocalAtendimento as id, descricaoLocalAtendimento as text 
		from amb_atendimentoslocais 
		where codStatusLocalAtendimento = 1  and codTipoLocalAtendimento in(2,3)
		and codDepartamento = "' . $codDepartamento . '" 
		order by descricaoLocalAtendimento');
		}


		return $query->getResult();
	}


	public function pegaPorDepartamento($codDepartamento)
	{
		$query = $this->db->query('select * 
		from amb_atendimentoslocais dd
		left join amb_atendimentoslocaistipos td on td.codTipoLocalAtendimento = dd.codTipoLocalAtendimento
		left join amb_atendimentoslocaisstatus sd on sd.codStatusLocalAtendimento = dd.codStatusLocalAtendimento
		where dd.codDepartamento = ' . $codDepartamento);
		return $query->getResult();
	}

	public function pegaPorCodLocalAtendimento($codLocalAtendimento)
	{
		$query = $this->db->query('select * 
		from amb_atendimentoslocais dd
		left join sis_departamentos d on d.codDepartamento = dd.codDepartamento
		left join amb_atendimentoslocaistipos td on td.codTipoLocalAtendimento = dd.codTipoLocalAtendimento
		left join amb_atendimentoslocaisstatus sd on sd.codStatusLocalAtendimento = dd.codStatusLocalAtendimento
		where dd.codLocalAtendimento = ' . $codLocalAtendimento);
		return $query->getRow();
	}


	public function liberaLeito($codAtendimento)
	{

		$query = $this->db->query('update amb_atendimentoslocais set codAtendimento = NULL  where codAtendimento="'.$codAtendimento.'"');
		return true;
	}
	
	public function defineLocalAtendimento($codLocalAtendimento,$codAtendimento)
	{

		$query = $this->db->query('update amb_atendimentoslocais set codAtendimento = "'.$codAtendimento.'"  where codLocalAtendimento="'.$codLocalAtendimento.'"');
		return true;
	}
}
