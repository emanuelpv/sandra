<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class ModelosMateriaisModel extends Model
{

	protected $table = 'amb_modelosmateriais';
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
		$query = $this->db->query('select * from amb_modelosmateriais');
		return $query->getResult();
	}


	public function pegaMeusModelos()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select m.codModelo,m.titulo,p.nomeExibicao,m.dataCriacao
		from amb_modelosmateriais m
		left join sis_pessoas p on p.codPessoa=m.codAutor
		where m.codAutor=' . session()->codPessoa);
		return $query->getResult();
	}


	public function pegaOutrosModelos()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select m.codModelo,m.titulo,p.nomeExibicao,m.dataCriacao
		from amb_modelosmateriais m
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
		$query = $this->db->query('select * from amb_modelosmateriaisitens where codItemModelo = "' . $codItemModelo . '"');
		return $query->getRow();
	}



	public function pegaPorCodigoModeloPrescricao($codModelo)
	{
		$query = $this->db->query('select apm.*, iff.descricaoItem,p.nomeExibicao, sm.*
        from amb_modelosmateriaisitens apm
		left join amb_modelosmateriais mm on mm.codModelo=apm.codModelo
		left join sis_itensfarmacia  iff on iff.codItem=apm.codMaterial and iff.codCategoria=6
		left join sis_pessoas p on p.codPessoa=apm.codAutor
		left join sis_statusmaterial sm on sm.codStatusMaterial=apm.codStatus
		where  iff.codCategoria=6 and apm.codModelo = "' . $codModelo . '" order by apm.codModelo desc');
		return $query->getResult();
	}

	public function pegaPorCodigoAtendimentoPrescricao($codAtendimentoPrescricao)
	{
		$query = $this->db->query('select*
        from amb_atendimentosprescricoesmateriais
		where  codAtendimentoPrescricao = "' . $codAtendimentoPrescricao . '"');
		return $query->getResult();
	}


	public function removeItem($codItemModelo)
	{
		$query = $this->db->query('delete from amb_modelosmateriaisitens where codItemModelo = "' . $codItemModelo . '"');
		return true;
	}


	public function verificaExistenciaMaterial($codAtendimentoPrescricao, $codMaterial)
	{
		$query = $this->db->query('select * from amb_atendimentosprescricoesmateriais where codAtendimentoPrescricao = "' . $codAtendimentoPrescricao . '" and codMaterial="' . $codMaterial . '"');
		return $query->getRow();
	}
}
