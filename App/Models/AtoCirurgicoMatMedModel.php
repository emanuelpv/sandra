<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class AtoCirurgicoMatMedModel extends Model
{

	protected $table = 'cir_atocirurgicomatmed';
	protected $primaryKey = 'codAtoCirurgicoMatMed';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['dataAtualizacao','codUnidade','codAtoCirurgico', 'codMatMed', 'qtde', 'observacao', 'dataCriacao', 'codAutor'];
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
		$query = $this->db->query('select * from cir_atocirurgicomatmed');
		return $query->getResult();
	}
	public function getAllMatMedAtoCirurgico($codAtoCirurgico = NULL, $tipoItem = NULL)
	{

		$filtro = '';
		if ($tipoItem == 'Mat') {
			$filtro = ' and c.codCategoria in(6,5,8,9)';
		}
		if ($tipoItem == 'Med') {

			$filtro = ' and c.codCategoria in(1,2,3)';
		}
		if ($tipoItem == 'OPME') {

			$filtro = ' and c.codCategoria in(4)';
		}

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select m.*, iff.descricaoItem, u.descricaoUnidade, c.descricaoCategoria 
		from cir_atocirurgicomatmed m
		left join sis_itensfarmacia iff on iff.codItem=m.codMatMed
		left join sis_itensfarmaciacategoria c on c.codCategoria=iff.codCategoria		
		left join sis_unidades u on u.codUnidade=m.codUnidade
		where m.codAtoCirurgico="' . $codAtoCirurgico . '"'.$filtro);
		return $query->getResult();
	}

	public function pegaPorCodigo($codAtoCirurgicoMatMed)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codAtoCirurgicoMatMed = "' . $codAtoCirurgicoMatMed . '"');
		return $query->getRow();
	}
}
