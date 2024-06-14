<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;
use CodeIgniter\Model;

class PrescricoesMaterialModel extends Model {
    
	protected $table = 'amb_atendimentosprescricoesmateriais';
	protected $primaryKey = 'codPrescricaoMaterial';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['migrado','codAutorLiberacao','codAutorExecucao','totalExecutado','totalEntregue','totalLiberado','codPrescricaoKit','codAtendimentoPrescricao', 'codMaterial', 'qtde', 'codStatus', 'observacao', 'codAutor', 'dataCriacao', 'dataAtualizacao'];
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
        $query = $this->db->query('select * from amb_atendimentosprescricoesmateriais');
        return $query->getResult();
    }

	public function pegaPorCodigo($codPrescricaoMaterial)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codPrescricaoMaterial = "'.$codPrescricaoMaterial.'"');
        return $query->getRow();
    }

	public function pegaPorCodigoAtendimentoPrescricao($codAtendimentoPrescricao)
    {
        $query = $this->db->query('select apk.*,iff.descricaoItem,p.nomeExibicao,sk.* 
		from amb_atendimentosprescricoesmateriais apk
		left join sis_itensfarmacia iff on iff.codItem =  apk.codMaterial
		left join sis_pessoas p on p.codPessoa =  apk.codAutor
		left join sis_statusmaterial sk on sk.codStatusMaterial =  apk.codStatus
		where codAtendimentoPrescricao = "'.$codAtendimentoPrescricao.'" order by apk.codPrescricaoMaterial desc');
        return $query->getResult();
    }

	public function pegaClonarMateriais($codAtendimentoPrescricao)
    {
        $query = $this->db->query('select *
		from amb_atendimentosprescricoesmateriais 
		where codAtendimentoPrescricao = "'.$codAtendimentoPrescricao.'" order by codPrescricaoMaterial desc');
        return $query->getResult();
    }




	public function listaDropDownMaterials()
    {
        $query = $this->db->query('select codItem as id, descricaoItem as text from sis_itensfarmacia where codCategoria=6 and descricaoItem is not null order by descricaoItem asc');
        return $query->getResult();
    }

}