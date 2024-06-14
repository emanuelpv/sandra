<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class ModelosCondutasModel extends Model
{

	protected $table = 'amb_modeloscondutas';
	protected $primaryKey = 'codModelo';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['titulo', 'conteudo', 'dataCriacao', 'codAutor'];
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
		$query = $this->db->query('select * from amb_modeloscondutas');
		return $query->getResult();
	}


	public function pegaMeusModelos()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select m.*,p.nomeExibicao
		from amb_modeloscondutas m
		left join sis_pessoas p on p.codPessoa=m.codAutor
		where m.codAutor=' . session()->codPessoa);
		return $query->getResult();
	}


	public function pegaOutrosModelos()
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select m.*,p.nomeExibicao
		from amb_modeloscondutas m
		left join sis_pessoas p on p.codPessoa=m.codAutor
		where m.codAutor<>' . session()->codPessoa);
		return $query->getResult();
	}

	public function pegaPorCodigo($codModelo)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codModelo = "' . $codModelo . '"');
		return $query->getRow();
	}
}
