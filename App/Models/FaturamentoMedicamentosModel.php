<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class FaturamentoMedicamentosModel extends Model
{

	protected $table = 'fat_faturamentomedicamentos';
	protected $primaryKey = 'codFaturamentoMedicamento';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codAuditor', 'codFatura', 'codAtendimento', 'codPrescricaoMedicamento', 'autorPrescricao', 'dataPrescricao', 'codMedicamento', 'quantidade', 'valor', 'codLocalAtendimento', 'dataCriacao', 'dataAtualizacao', 'codStatus', 'codAutor', 'observacoes'];
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
		$query = $this->db->query('select * from fat_faturamentomedicamentos');
		return $query->getResult();
	}

	public function pegaPorCodigo($codFaturamentoMedicamento)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codFaturamentoMedicamento = "' . $codFaturamentoMedicamento . '"');
		return $query->getRow();
	}

	public function verificaExistenciaMedicamentoFaturado($codAtendimento, $codPrescricaoMedicamento)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codAtendimento = ' . $codAtendimento . ' and codPrescricaoMedicamento=' . $codPrescricaoMedicamento);
		return $query->getRow();
	}


	public function medicamentos($codAtendimento = NULL, $ccusto = NULL, $dataInicio = NULL, $dataEncerramento = NULL)
	{


		$filtro = '';
		$ccusto = str_replace('"', "", $ccusto);
		if ($ccusto !== "0" and $ccusto !== NULL and $ccusto !== "" and $ccusto !== " ") {

			$filtro .= ' and d.codDepartamento in(' . $ccusto . ')';
		}


		
		if ($dataInicio !== NULL and $dataInicio !== "" and $dataInicio !== " ") {

			$filtro .= ' and apm.dataCriacao >="' . $dataInicio . '"';
		}
		
		
		if ($dataEncerramento !== NULL and $dataEncerramento !== "" and $dataEncerramento !== " ") {

			$filtro .= ' and apm.dataCriacao <="' . $dataEncerramento . '"';
		}


		$query = $this->db->query('select apm.*,ap.*,iff.*,apm.stat as codStatusMedicamento,apm.codAutor as codAutorPrescricao, apm.dataCriacao as dataCriacaoPrescricaoMedicamento,apm.dataAtualizacao as dataAtualizacaoPrescricaoMedicamento 
		FROM amb_atendimentosprescricoesmedicamentos apm
		join amb_atendimentosprescricoes ap on ap.codAtendimentoPrescricao=apm.codAtendimentoPrescricao
		join amb_atendimentoslocais al on al.codLocalAtendimento=ap.codLocalAtendimento
		join sis_departamentos d on d.codDepartamento=al.codDepartamento
		join sis_itensfarmacia iff on iff.codItem=apm.codMedicamento
		where ap.codAtendimento = ' . $codAtendimento . $filtro . ' and ap.dataInicio >= "2022-09-03" order by ap.codAtendimento desc');
		return $query->getResult(); 
	}

	public function medicamentosFaturados($codFatura = NULL)
	{


		$query = $this->db->query('select f.codStatusFatura,sp.*,(fm.quantidade * fm.valor ) as subTotal, iff.nee, iff.descricaoItem,d.descricaoDepartamento,la.descricaoLocalAtendimento,fm.*,iff.*,fm.autorPrescricao as autorPrescricao,pe.nomeExibicao as nomeAuditor

		FROM fat_faturamentomedicamentos fm
		left join fat_faturamento f on f.codFatura=fm.codFatura
		left join sis_pessoas pe on pe.codPessoa=fm.codAuditor
		left join sis_itensfarmacia iff on iff.codItem=fm.codMedicamento
		left join amb_atendimentoslocais la on la.codLocalAtendimento=fm.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento
		left join sis_statusprescricao sp on sp.codStatusPrescricao=fm.codStatus
		where fm.codFatura = ' . $codFatura . ' order by fm.codFaturamentoMedicamento desc');
		return $query->getResult();
	}


	public function removeFatura($codFatura)
	{
		$query = $this->db->query('delete from ' . $this->table . ' where codFatura = ' . $codFatura);
		return true;
	}



	public function listaStatusFaturamentoMedicamentos()
	{
		$query = $this->db->query('select codStatusPrescricao as id, descricaoStatusPrescricao as text from sis_statusprescricao where descricaoStatusPrescricao is not null');
		return $query->getResult();
	}
}
