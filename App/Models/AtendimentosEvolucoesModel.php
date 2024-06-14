<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;
use CodeIgniter\Model;

class AtendimentosEvolucoesModel extends Model {
    
	protected $table = 'amb_atendimentosevolucoes';
	protected $primaryKey = 'codAtendimentoEvolucao';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['assinadoPor','codLocalAtendimento','migrado','codTipoEvolucao','codAtendimento', 'dataInicio', 'dataEncerramento', 'codStatus', 'conteudoEvolucao', 'impresso', 'codAutor', 'dataCriacao', 'dataAtualizacao'];
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
        $query = $this->db->query('select * from amb_atendimentosevolucoes');
        return $query->getResult();
    }

	public function pegaPorCodigo($codAtendimentoConduta)
	{
		$query = $this->db->query('select al.descricaoLocalAtendimento, d.abreviacaoDepartamento,te.descricaoTipoEvolucao, ac.*, pe.nomeCompleto as nomeEspecialista,c.siglaCargo,pa.cpf,tp.siglaTipoBeneficiario,pa.codPlano, pa.nomeCompleto as nomePaciente,em.numeroInscricao,ef.siglaEstadoFederacao,co.nomeConselho
		from amb_atendimentosevolucoes ac
		left join sis_tiposevolucoes te on te.codTipoEvolucao=ac.codTipoEvolucao
		left join amb_atendimentos a on a.codAtendimento=ac.codAtendimento
		left join sis_pessoas pe on pe.codPessoa=ac.codAutor
		left join sis_cargos c on c.codCargo=pe.codCargo
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente
		left join sis_tipobeneficiario tp on tp.codTipoBeneficiario=pa.codTipoBeneficiario	
		left join sis_especialidadesmembros em on em.codPessoa=pe.codPessoa
		left join sis_estadosfederacao ef on ef.codEstadoFederacao=em.codEstadoFederacao
		left join sis_especialidades e on e.codEspecialidade=em.codEspecialidade
		left join sis_conselhos co on co.codConselho=e.codConselho
		left join amb_atendimentoslocais al on al.codLocalAtendimento=ac.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=al.codDepartamento
		where ac.codAtendimentoEvolucao = "' . $codAtendimentoConduta . '" limit 1');
		return $query->getRow();
	}


	public function pegaEvolucao($codAtendimentoEvolucao)
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select * from amb_atendimentosevolucoes where codAtendimentoEvolucao="'.$codAtendimentoEvolucao.'"');
        return $query->getRow();
    }


	public function pegaPorCodAtendimento($codAtendimento)
	{
		$query = $this->db->query('select te.descricaoTipoEvolucao, e.*,p.nomeExibicao,pp.nomeExibicao as nomeExibicaoAssinador,ae.* 
		from amb_atendimentosevolucoes e
		left join sis_tiposevolucoes as te on te.codTipoEvolucao=e.codTipoEvolucao 
		left join amb_atendimentosevolucoesstatus as ae on ae.codStatus=e.codStatus 
		left join sis_pessoas p on p.codPessoa=e.codAutor 
		left join sis_pessoas pp on pp.codPessoa=e.assinadoPor 
		where e.codAtendimento = ' . $codAtendimento . ' 
		order by e.dataCriacao desc');
		return $query->getResult();
	}

	public function listaDropDownTiposEvolucao()
	{
		$query = $this->db->query('select codTipoEvolucao as id, descricaoTipoEvolucao as text 
		from sis_tiposevolucoes
		where descricaoTipoEvolucao <> "DESCR CIRURGICA" and  descricaoTipoEvolucao is not null');
		return $query->getResult();
	}



}