<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class SlideshowModel extends Model
{

	protected $table = 'sis_slideshow';
	protected $primaryKey = 'codSlideShow';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codOrganizacao', 'codStatus', 'ordem', 'descricao', 'imagem', 'url', 'dataExpiracao'];
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
		$query = $this->db->query('select * from sis_slideshow where  codOrganizacao=' . $codOrganizacao);
		return $query->getResult();
	}


	public function pegaPorCodigo($codSlideShow)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from ' . $this->table . ' where codSlideShow = "' . $codSlideShow . '" and codOrganizacao=' . $codOrganizacao . ' order by ordem');
		return $query->getRow();
	}


	public function totalSlides()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select count(*) as total from ' . $this->table . ' where codOrganizacao=' . $codOrganizacao . ' ');
		return $query->getRow();
	}


	public function slideShow()
	{
		$response = array();

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from sis_slideshow where  codOrganizacao=' . $codOrganizacao . ' and codStatus=1 order by ordem asc');
		$result = $query->getResult();

		$html = '';

		foreach ($result as $key => $value) {

			if ($key === array_key_first($result)) {
				$status = 'active';
			} else {
				$status = '';
			}

			$html .= '
			<div class="carousel-item ' . $status . '">
             	<img class="d-block" src="' . base_url() . '/imagens/slideshow/' . $value->imagem . '">
            </div>
			';
		}

		return  $html;
	}
}
