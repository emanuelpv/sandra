<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;
use CodeIgniter\Model;

class PrescricoesKitModel extends Model {
    
	protected $table = 'amb_atendimentosprescricoeskits';
	protected $primaryKey = 'codPrescricaoKit';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codAtendimentoPrescricao', 'codKit', 'qtde', 'codStatus', 'observacao', 'codAutor', 'dataCriacao', 'dataAtualizacao'];
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
        $query = $this->db->query('select * from amb_atendimentosprescricoeskits');
        return $query->getResult();
    }

	public function pegeItensKits($codKit)
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select ki.*, iff.codCategoria from sis_kitsitens ki
		left join sis_itensfarmacia iff on ki.codItem=iff.codItem
		where ki.codKit="'.$codKit.'"');
        return $query->getResult();
    }
	public function pegaPorCodigo($codPrescricaoKit)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codPrescricaoKit = "'.$codPrescricaoKit.'"');
        return $query->getRow();
    }

	public function pegaPorCodigoAtendimentoPrescricao($codAtendimentoPrescricao)
    {
        $query = $this->db->query('select * from amb_atendimentosprescricoeskits apk
		left join sis_kits k on k.codKit =  apk.codKit
		left join sis_pessoas p on p.codPessoa =  apk.codAutor
		left join sis_statuskit sk on sk.codStatusKit =  apk.codStatus
		where codAtendimentoPrescricao = "'.$codAtendimentoPrescricao.'"');
        return $query->getResult();
    }

		public function pegaClonarKits($codAtendimentoPrescricao)
    {
        $query = $this->db->query('select * from amb_atendimentosprescricoeskits 
		where codAtendimentoPrescricao = '.$codAtendimentoPrescricao);
        return $query->getResult();
    }


	public function listaDropDownKits()
    {
        $query = $this->db->query('select codKit as id, descricaoKit as text from sis_kits where descricaoKit is not null');
        return $query->getResult();
    }



	

	public function removeMedicamentosKit($codPrescricaoKit)
	{
		if ($this->db->query('delete from amb_atendimentosprescricoesmedicamentos where codPrescricaoKit = "' . $codPrescricaoKit . '"')) {
			return true;
		}
	}	

	public function removeMateriaisKit($codPrescricaoKit)
	{
		if ($this->db->query('delete from amb_atendimentosprescricoesmateriais where codPrescricaoKit = "' . $codPrescricaoKit . '"')) {
			return true;
		}
	}

}