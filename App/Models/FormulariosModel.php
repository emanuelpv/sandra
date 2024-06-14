<?php

namespace App\Models;
use CodeIgniter\Model;

class FormulariosModel extends Model {
    
	protected $atributoInternoMetodo = 'xxxxxx';
 
	

	function listboxModulospai($cod_modulo = null, $hiddenOrDisabled = null) {
		
		$modulo = $this->db->query('select * from sis_modulos where pai = 0')->getResult();

		

		$botao = '
		
		<select ' . $hiddenOrDisabled . ' align="left" name="cod_modulo" id="cod_modulo" class="form-control">
		
				<option value=""></option>';
		foreach ($modulo as $row) {
	
			if ($row->cod_modulo == $cod_modulo) {
				$selected = 'selected';
			} else {
				$selected = '';
			}
			$botao .= '
					<option value="' . $row->cod_modulo . '" ' . $selected . '>' . $row->nome . '</option>';
		}
		$botao .= "</select>";
		return $botao;
	}

}





