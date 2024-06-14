<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;
use CodeIgniter\Model;

class PrescricoesCuidadosModel extends Model {
    
	protected $table = 'amb_atendimentosprescricoescuidados';
	protected $primaryKey = 'codPrescricaoCuidado';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['codAtendimentoPrescricao', 'codTipoCuidadoPrescricao', 'descricao', 'codStatus', 'apraza', 'codAutor', 'dataCriacao', 'dataAtualizacao'];
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
        $query = $this->db->query('select * from amb_atendimentosprescricoescuidados');
        return $query->getResult();
    }

	public function pegaPorCodigo($codPrescricaoCuidado)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codPrescricaoCuidado = "'.$codPrescricaoCuidado.'"');
        return $query->getRow();
    }

	public function pegaPorCodigoAtendimentoPrescricao($codAtendimentoPrescricao)
    {
        $query = $this->db->query('select apk.*,iff.nomeTipoCuidadoPrescricao,p.nomeExibicao,sk.*
		from amb_atendimentosprescricoescuidados apk
		left join sis_tiposcuidadosprescricoes iff on iff.codTipoCuidadoPrescricao =  apk.codTipoCuidadoPrescricao
		left join sis_pessoas p on p.codPessoa =  apk.codAutor
		left join sis_statuscuidados sk on sk.codStatusCuidado =  apk.codStatus
		where codAtendimentoPrescricao = "'.$codAtendimentoPrescricao.'"');
        return $query->getResult();
    }
	public function pegaClonarMateriais($codAtendimentoPrescricao)
    {
        $query = $this->db->query('select *
		from amb_atendimentosprescricoescuidados 
		where codAtendimentoPrescricao = "'.$codAtendimentoPrescricao.'" order by codPrescricaoCuidado desc');
        return $query->getResult();
    }
	public function listaDropDownCuidados()
    {
        $query = $this->db->query('select codTipoCuidadoPrescricao as id, nomeTipoCuidadoPrescricao as text from sis_tiposcuidadosprescricoes where nomeTipoCuidadoPrescricao is not null');
        return $query->getResult();
    }

}