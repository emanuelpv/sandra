<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class painelSenhasModel extends Model
{

	protected $table = 'amb_chamadassenhas';
	protected $primaryKey = 'codChamada';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codDepartamento', 'localAtendimento', 'dataChamada', 'nomeCompleto', 'qtdChamadas', 'fotoPerfil', 'senha'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;

	public function listaDropDownEspecialidades()
	{
		$query = $this->db->query('select codEspecialidade as id,descricaoEspecialidade as text from sis_departamentos order by descricaoEspecialidade');
		return $query->getResult();
	}
	public function listaDropDownDepartamentos()
	{
		$query = $this->db->query('select codDepartamento as id,descricaoDepartamento as text from sis_departamentos order by descricaoDepartamento');
		return $query->getResult();
	}

	public function pegaTudo()
	{
		$query = $this->db->query('select * from amb_chamadasfila');
		return $query->getRow();
	}

	public function senhaChamada($codDepartamento = null)
	{
		if ($codDepartamento !== null and $codDepartamento !== "") {
			$query = $this->db->query('select *
		from amb_chamadassenhas cs
		where codDepartamento in (' . $codDepartamento . ')
		order by qtdChamadas desc,codChamada asc  limit 1');
		} else {
			$query = $this->db->query('select *
			from amb_chamadassenhas cs
		    order by qtdChamadas desc,codChamada asc  limit 1');
		}

		return $query->getRow();
	}


	public function filaPrioridades($departamentos = null)
	{

		$departamentos = removeCaracteresIndesejados($departamentos);

		if ($departamentos !== null and $departamentos !== "") {
			$query = $this->db->query('select *
		from amb_atendimentosenha a 
		where codTipoFila = 1 and codStatus=0 and a.codPrioridade=1 and a.codDepartamento in (' . $departamentos . ') and DATE_FORMAT(a.dataProtocolo, "%Y-%m-%d") >= DATE_FORMAT(NOW(), "%Y-%m-%d") 
		order by a.codPrioridade desc, a.dataProtocolo asc, a.idade desc');
		} else {
			$query = $this->db->query('select *
			from amb_atendimentosenha a
			where codTipoFila = 1 and codStatus=0 and a.codPrioridade=1 and DATE_FORMAT(a.dataProtocolo, "%Y-%m-%d") >= DATE_FORMAT(NOW(), "%Y-%m-%d") 
			order by a.codPrioridade desc, a.dataProtocolo asc, a.idade desc');
		}

		return $query->getResult();
	}


	public function filaProximasChamadas($departamentos = null)
	{

		$departamentos = removeCaracteresIndesejados($departamentos);

		if ($departamentos !== null and $departamentos !== "") {
			$query = $this->db->query('select *
		from amb_atendimentosenha a 
		where a.codStatus=0 and a.qtdChamadas=0 and a.codDepartamento in (' . $departamentos . ') and DATE_FORMAT(a.dataInicio, "%Y-%m-%d") >= DATE_FORMAT(NOW(), "%Y-%m-%d") 
		order by a.dataInicio asc, a.idade desc');
		} else {
			$query = $this->db->query('select * from amb_atendimentosenha a 
			where codStatus=0 and qtdChamadas=0 and DATE_FORMAT(a.dataInicio, "%Y-%m-%d") >= DATE_FORMAT(NOW(), "%Y-%m-%d") 
			order by a.dataInicio asc, a.idade desc');
		}

		return $query->getResult();
	}

	public function filaUltimasChamadas($departamentos = null)
	{

		$departamentos = removeCaracteresIndesejados($departamentos);

		if ($departamentos !== null and $departamentos !== "") {
			$query = $this->db->query('select *
		from amb_atendimentosenha a 
		where a.qtdChamadas>0 and a.dataEncerramentoAtendimento is null and a.codDepartamento in (' . $departamentos . ') and DATE_FORMAT(a.dataInicio, "%Y-%m-%d") >= DATE_FORMAT(NOW(), "%Y-%m-%d") 
		order by a.dataInicio asc, a.idade desc');
		} else {
				$query = $this->db->query('select * from amb_atendimentosenha a 
				where a.qtdChamadas>0 and a.dataEncerramentoAtendimento is null and DATE_FORMAT(a.dataInicio, "%Y-%m-%d") >= DATE_FORMAT(NOW(), "%Y-%m-%d") 
				order by a.dataInicio asc, a.idade desc');
		}

		return $query->getResult();
	}

	public function filaNormal($departamentos = null)
	{

		$departamentos = removeCaracteresIndesejados($departamentos);

		if ($departamentos !== null and $departamentos !== "") {
			$query = $this->db->query('select *
		from amb_atendimentosenha a 
		where codTipoFila = 1 and codStatus=0 and a.codPrioridade=0 and a.codDepartamento in (' . $departamentos . ')  and DATE_FORMAT(a.dataProtocolo, "%Y-%m-%d") >= DATE_FORMAT(NOW(), "%Y-%m-%d")
		order by a.dataProtocolo asc, a.idade desc');
		} else {
			$query = $this->db->query('select *
			from amb_atendimentosenha a 
			where codTipoFila = 1 and codStatus=0 and a.codPrioridade=0 and DATE_FORMAT(a.dataProtocolo, "%Y-%m-%d") >= DATE_FORMAT(NOW(), "%Y-%m-%d") 
			order by a.dataProtocolo asc, a.idade desc');
		}

		return $query->getResult();
	}

	public function filaPrioridadesResultados($departamentos = null)
	{

		$departamentos = removeCaracteresIndesejados($departamentos);

		if ($departamentos !== null and $departamentos !== "") {
			$query = $this->db->query('select *
		from amb_atendimentosenha a 
		where codTipoFila = 2 and codStatus=0 and a.codPrioridade=1 and a.codDepartamento in (' . $departamentos . ') and DATE_FORMAT(a.dataProtocolo, "%Y-%m-%d") >= DATE_FORMAT(NOW(), "%Y-%m-%d") 
		order by a.codPrioridade desc, a.dataProtocolo asc, a.idade desc');
		} else {
			$query = $this->db->query('select *
			from amb_atendimentosenha a
			where codTipoFila = 2 and codStatus=0 and a.codPrioridade=1 and DATE_FORMAT(a.dataProtocolo, "%Y-%m-%d") >= DATE_FORMAT(NOW(), "%Y-%m-%d") 
			order by a.codPrioridade desc, a.dataProtocolo asc, a.idade desc');
		}

		return $query->getResult();
	}


	public function filaNormalResultados($departamentos = null)
	{

		$departamentos = removeCaracteresIndesejados($departamentos);

		if ($departamentos !== null and $departamentos !== "") {
			$query = $this->db->query('select *
		from amb_atendimentosenha a 
		where codTipoFila = 2 and codStatus=0 and a.codPrioridade=0 and a.codDepartamento in (' . $departamentos . ')  and DATE_FORMAT(a.dataProtocolo, "%Y-%m-%d") >= DATE_FORMAT(NOW(), "%Y-%m-%d")
		order by a.dataProtocolo asc, a.idade desc');
		} else {
			$query = $this->db->query('select *
			from amb_atendimentosenha a 
			where codTipoFila = 2 and codStatus=0 and a.codPrioridade=0 and DATE_FORMAT(a.dataProtocolo, "%Y-%m-%d") >= DATE_FORMAT(NOW(), "%Y-%m-%d") 
			order by a.dataProtocolo asc, a.idade desc');
		}

		return $query->getResult();
	}
}
