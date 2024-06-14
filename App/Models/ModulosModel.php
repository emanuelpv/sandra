<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class ModulosModel extends Model
{

	protected $table = 'sis_modulos';
	protected $primaryKey = 'codModulo';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['nome', 'link', 'pai', 'ordem', 'destaque', 'icone', 'dataCriacao', 'DataAtualizacao'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;




	public function pegaTudo()
	{
		$query = $this->db->query('select m.*, mm.nome as descricaoPai
		from sis_modulos m 
		left join sis_modulos mm on m.pai=mm.codModulo');
        return $query->getResult();
	}


	public function get_items()
	{
		$query = $this->db->query('select * from ' . $this->table . ' order by pai,ordem');
		return $query->getResultArray();
	}




	function generateTree($items = array(), $parent_id = 0)
	{
		
		$temFilho='';
		$tree = '';

		for ($i = 0, $ni = count($items); $i < $ni; $i++) {
			if ($items[$i]['pai'] == $parent_id) {
				if(temFilho($items, $items[$i]['pai'])){
					$tree = '<ul class="nav nav-treeview" style="display: block;">';
					$temFilho = '';
				} else{
					$tree = '<ul class="nav nav-treeview">';
					$temFilho = 'margin-left:20px';
				}
				$tree .= '<li style="'.$temFilho.'" class="nav-item">
				<a href="'.$items[$i]['link'].'" class="nav-link">
                <i class=" '.$items[$i]['icone'].'"></i>
              <p>
                '.$items[$i]['nome'].'
              </p>
            </a>
			';
				
				$tree .= $this->generateTree($items, $items[$i]['codModulo']);
				$tree .= '</li></ul>';
			}
		}
		$tree .= '</li>';
		return $tree;
	}


	function temFilho($items = array(), $codpai){
		for ($i = 0, $ni = count($items); $i < $ni; $i++) {
			if ($items[$i]['pai'] == $codpai) {
				return true;
			}else{
				return false;
			}

		}
	}
	public function pegaModulosRaiz()
	{
		$query = $this->db->query('select * from ' . $this->table . ' where pai = 0 order by ordem asc');
		return $query->getResult();
	}

	public function pegaModulosFilho()
	{
		$query = $this->db->query('select * from ' . $this->table . ' where pai != 0 order by  ordem asc,codModulo asc');
		return $query->getResult();
	}
}
