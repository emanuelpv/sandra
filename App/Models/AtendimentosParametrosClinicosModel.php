<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;
use CodeIgniter\Model;

class AtendimentosParametrosClinicosModel extends Model {
    
	protected $table = 'amb_atendimentosparametrosclinicos';
	protected $primaryKey = 'codParametroClinico';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['cirroseHepatica','sindromeDown','obesidadeMorbida','hemoglobinopatiaGrave','imuninosuprimido','doencaRenalCronica','doencaCerebroVascular','protesesValvaresDispCardImplatados','cardiopatiaCongenitaAdulto','arritimiasCardiacas','aortaVasosFistulas','miocarPericar','valvopatia','sc','ch','cphp','ic','hae12','hae3','har','pneumopatiaCronicaGrave','diabetesMellitus','codAtendimento', 'dataCriacao', 'dataAtualizacao', 'codAutor', 'peso', 'altura', 'perimetroCefalico', 'parimetroAbdominal', 'paSistolica', 'paDiastolica', 'fc', 'fr', 'temperatura', 'saturacao','glicemiaPosPrandial','glicemiaJejum'];
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
        $query = $this->db->query('select * from amb_atendimentosparametrosclinicos');
        return $query->getResult();
    }

	public function pegaPorCodigo($codParametroClinico)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codParametroClinico = "'.$codParametroClinico.'"');
        return $query->getRow();
    }
	public function verificaExistencia($codAtendimento)    {
        $query = $this->db->query('select * from amb_atendimentosparametrosclinicos where codAtendimento = '.$codAtendimento);
        return $query->getRow();
    }



}