<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;
use CodeIgniter\Model;

class PrescricoesOutrasModel extends Model {
    
	protected $table = 'amb_atendimentosprescricoesoutras';
	protected $primaryKey = 'codPrescricaoOutra';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['migracao','codAtendimentoPrescricao', 'codTipoOutraPrescricao', 'descricao', 'codStatus', 'apraza', 'codAutor', 'dataCriacao', 'dataAtualizacao'];
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
        $query = $this->db->query('select * from amb_atendimentosprescricoesoutras');
        return $query->getResult();
    }

	public function pegaPorCodigo($codPrescricaoOutra)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codPrescricaoOutra = "'.$codPrescricaoOutra.'"');
        return $query->getRow();
    }

	public function pegaPorCodigoAtendimentoPrescricao($codAtendimentoPrescricao)
    {
        $query = $this->db->query('select apk.*,iff.nomeTipoOutraPrescricao,p.nomeExibicao,sk.* 
		from amb_atendimentosprescricoesoutras apk
		left join sis_tiposoutrasprescricoes iff on iff.codTipoOutraPrescricao =  apk.codTipoOutraPrescricao
		left join sis_pessoas p on p.codPessoa =  apk.codAutor
		left join sis_statusoutras sk on sk.codStatusOutras =  apk.codStatus
		where codAtendimentoPrescricao = "'.$codAtendimentoPrescricao.'"');
        return $query->getResult();
    }	
	
	public function pegaClonarOutras($codAtendimentoPrescricao)
    {
        $query = $this->db->query('select *
		from amb_atendimentosprescricoesoutras 
		where codAtendimentoPrescricao = '.$codAtendimentoPrescricao.' order by codPrescricaoOutra desc');
        return $query->getResult();
    }

	public function listaDropDownOutras()
    {
        $query = $this->db->query('select codTipoOutraPrescricao as id, nomeTipoOutraPrescricao as text from sis_tiposoutrasprescricoes');
        return $query->getResult();
    }

}