<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class KitsItensModel extends Model
{

	protected $table = 'sis_kitsitens';
	protected $primaryKey = 'codKitItem';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['valorun', 'codItem', 'codKit', 'qtde', 'dataCriacao', 'dataAtualizacao', 'codAutor'];
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
		$query = $this->db->query('select * from sis_kitsitens');
		return $query->getResult();
	}

	public function pegaPorCodigo($codKitItem)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codKitItem = "' . $codKitItem . '"');
		return $query->getRow();
	}


	public function itensKit($codKit)
	{
		$query = $this->db->query('select ki.*,p.nomeExibicao,iff.descricaoItem,iff.valor from  sis_kitsitens ki
		join sis_itensfarmacia iff on iff.codItem=ki.codItem
		left join sis_pessoas p on p.codPessoa=ki.codAutor
		where ki.codKit = "' . $codKit . '"');
		return $query->getResult();
	}

	public function itemKitLookup($nomeItem)
	{
		$query = $this->db->query('select *
		from  sis_itensfarmacia iff
		where iff.descricaoItem = "' . $nomeItem . '"');
		return $query->getRow();
	}


	public function verificaExistenciaItemKit($codKit, $codItem)
	{
		$query = $this->db->query('select *
		from  sis_kits k
		left join sis_kitsitens ki on ki.codKit=k.codKit
		left join sis_itensfarmacia iff on iff.codItem=ki.codItem
		where k.codKit = "' . $codKit . '" and iff.codItem="' . $codItem . '"');
		return $query->getRow();
	}


}
