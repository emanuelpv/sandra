<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class ModelosMedicamentosModel extends Model
{

	protected $table = 'amb_modelosmedicamentos';
	protected $primaryKey = 'codModelo';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['titulo', 'codAutor', 'dataCriacao', 'dataAtualizacao'];
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
		$query = $this->db->query('select * from amb_modelosmedicamentos');
		return $query->getResult();
	}


	public function pegaMeusModelos()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select m.codModelo,m.titulo,p.nomeExibicao,m.dataCriacao
		from amb_modelosmedicamentos m
		left join sis_pessoas p on p.codPessoa=m.codAutor
		where m.codAutor=' . session()->codPessoa);
		return $query->getResult();
	}


	public function pegaOutrosModelos()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select m.codModelo,m.titulo,p.nomeExibicao,m.dataCriacao
		from amb_modelosmedicamentos m
		left join sis_pessoas p on p.codPessoa=m.codAutor
		where m.codAutor<>' . session()->codPessoa);
		return $query->getResult();
	}

	public function pegaPorCodigo($codModelo)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codModelo = "' . $codModelo . '"');
		return $query->getRow();
	}

	public function pegaPorCodigoItem($codItemModelo)
	{
		$query = $this->db->query('select * from amb_modelosmedicamentositens where codItemModelo = "' . $codItemModelo . '"');
		return $query->getRow();
	}



	public function pegaPorCodigoModeloPrescricao($codModelo)
    {
        $query = $this->db->query('select apm.*, iff.*, u.*, v.*, pp.*,saa.*,rp.*,p.nomeExibicao, sp.*
        from amb_modelosmedicamentositens apm
		left join amb_modelosmedicamentos  mm on mm.codModelo=apm.codModelo
		left join sis_itensfarmacia  iff on iff.codItem=apm.codMedicamento
		left join sis_unidades u on u.codUnidade=apm.und
		left join sis_vias v on v.codVia=apm.via
		left join sis_periodoprescricao pp on pp.codPeriodo=apm.per
		left join sis_statusaplicaragora saa on saa.codAplicarAgora=apm.agora
		left join sis_riscoprescricao rp on rp.codRiscoPrescricao=apm.risco
		left join sis_pessoas p on p.codPessoa=apm.codAutor
		left join sis_statusprescricao sp on sp.codStatusPrescricao=apm.stat
		where apm.codModelo = "' . $codModelo . '" order by apm.codModelo desc');
        return $query->getResult();
    }


	public function removeItem($codItemModelo)
	{
		$query = $this->db->query('delete from amb_modelosmedicamentositens where codItemModelo = "' . $codItemModelo . '"');
		return true;
	}



}
