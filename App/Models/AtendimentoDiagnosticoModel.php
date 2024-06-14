<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;
use CodeIgniter\Model;

class AtendimentoDiagnosticoModel extends Model {
    
	protected $table = 'amb_atendimentosdiagnostico';
	protected $primaryKey = 'codAtendimentoDiagnostico';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['migrado','codCid','codAtendimento', 'codAutor', 'codTipoDiagnostico', 'dataCriacao'];
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
        $query = $this->db->query('select * from amb_atendimentosdiagnostico');
        return $query->getResult();
    }

	public function getAllPorAtendimento($codAtendimento)
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select ad.*, p.nomeExibicao,c.cid,a.codStatus 
		from amb_atendimentosdiagnostico ad
		left join amb_atendimentos a on a.codAtendimento=ad.codAtendimento
		left join sis_pessoas p on p.codPessoa = ad.codAutor 
		left join amb_cid10 c on c.codCid = ad.codCid 
		where ad.codAtendimento='.$codAtendimento.' order by ad.codTipoDiagnostico asc');
        return $query->getResult();
    }

	public function historicoDiagnostico($codAtendimento)
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select a.codAtendimento,ta.descricaoTipoAtendimento,ad.*, p.nomeExibicao,c.cid from 
		amb_atendimentosdiagnostico ad
		left join amb_atendimentos a on a.codAtendimento = ad.codAtendimento 
		left join amb_atendimentostipos ta on ta.codTipoAtendimento = a.codTipoAtendimento
		left join sis_pessoas p on p.codPessoa = ad.codAutor 
		left join amb_cid10 c on c.codCid = ad.codCid  
		where a.codPaciente in (
        select distinct codPaciente 
		from amb_atendimentos where codAtendimento ='.$codAtendimento.')
		order by ad.dataCriacao desc');
        return $query->getResult();
    }

	public function pegaPorCodigo($codAtendimentoDiagnostico)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codAtendimentoDiagnostico = "'.$codAtendimentoDiagnostico.'"');
        return $query->getRow();
    }
	public function lookupCid10($cid)
    {
        $query = $this->db->query('select * from amb_cid10 where cid like "%'.$cid.'%"  limit 1');
        return $query->getRow();
    }

	public function updateDiagnosticoPrincipal($codAtendimento)
    {
        $query = $this->db->query('update amb_atendimentosdiagnostico set codTipoDiagnostico = 2 where codAtendimento = "'.$codAtendimento.'"');
        return true;
    }


}