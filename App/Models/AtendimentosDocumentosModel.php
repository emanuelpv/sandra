<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;
use CodeIgniter\Model;

class AtendimentosDocumentosModel extends Model {
    
	protected $table = 'amb_atendimentosdocumentos';
	protected $primaryKey = 'codAtendimentoDocumento';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['assinadoPor','codAtendimento','codTipoDocumento', 'codStatus', 'conteudoDocumento', 'impresso', 'codAutor', 'dataCriacao','dataAtualizacao','dataInicio','dataEncerramento'];
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
        $query = $this->db->query('select * from amb_atendimentosdocumentos');
        return $query->getResult();
    }


	public function pegaPorCodigo($codAtendimentoDocumento)
	{
		$query = $this->db->query('select ac.*, pa.endereco,pe.nomeCompleto as nomeEspecialista,c.siglaCargo,pa.cpf, pa.nomeCompleto as nomePaciente,em.numeroInscricao,ef.siglaEstadoFederacao,co.nomeConselho
		from amb_atendimentosdocumentos ac
		left join amb_atendimentos a on a.codAtendimento=ac.codAtendimento
		left join sis_pessoas pe on pe.codPessoa=ac.codAutor
		left join sis_cargos c on c.codCargo=pe.codCargo
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente
		left join sis_especialidadesmembros em on em.codPessoa=pe.codPessoa
		left join sis_estadosfederacao ef on ef.codEstadoFederacao=em.codEstadoFederacao
		left join sis_especialidades e on e.codEspecialidade=em.codEspecialidade
		left join sis_conselhos co on co.codConselho=e.codConselho
		where ac.codAtendimentoDocumento = "' . $codAtendimentoDocumento . '" limit 1');
		return $query->getRow();
	}

	public function dadosAtendimento($codAtendimento)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select c.siglaCid,pa.nomeCompleto,pa.cpf, TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade, a.dataCriacao
		from  amb_atendimentos a 
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente
		left join amb_atendimentosdiagnostico d on d.codAtendimento=a.codAtendimento
		left join amb_cid10 c on d.codCid=c.codCid	
		where a.codOrganizacao=' . $codOrganizacao . ' and  a.codAtendimento="' . $codAtendimento . '"  order by d.codTipoDiagnostico asc limit 1');
		return $query->getRow();
	}


	public function pegaPorCodAtendimento($codAtendimento)
    {
        $query = $this->db->query('select ap.*,p.nomeExibicao,pp.nomeExibicao as nomeExibicaoAssinador ,aps.*
		from amb_atendimentosdocumentos ap
		left join sis_pessoas p on p.codPessoa=ap.codAutor
		left join sis_pessoas pp on pp.codPessoa=ap.assinadoPor 
		left join amb_atendimentosdocumentosstatus aps on aps.codStatus=ap.codStatus 
		where ap.codAtendimento = '.$codAtendimento.' order by ap.dataCriacao desc');
        return $query->getResult();
    }



}