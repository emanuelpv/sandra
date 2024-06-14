<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class AtendimentosCondutasModel extends Model
{

	protected $table = 'amb_atendimentoscondutas';
	protected $primaryKey = 'codAtendimentoConduta';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['assinadoPor','migrado','codAtendimento', 'dataInicio', 'dataEncerramento', 'codStatus', 'conteudoConduta', 'impresso', 'codAutor', 'dataCriacao', 'dataAtualizacao'];
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
		$query = $this->db->query('select * from amb_atendimentoscondutas');
		return $query->getResult();
	}

	public function pegaPorCodigo($codAtendimentoConduta)
	{
		$query = $this->db->query('select ac.*, pe.nomeCompleto as nomeEspecialista,c.siglaCargo,pa.cpf, pa.nomeCompleto as nomePaciente,em.numeroInscricao,ef.siglaEstadoFederacao,co.nomeConselho
		from amb_atendimentoscondutas ac
		left join amb_atendimentos a on a.codAtendimento=ac.codAtendimento
		left join sis_pessoas pe on pe.codPessoa=ac.codAutor
		left join sis_cargos c on c.codCargo=pe.codCargo
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente
		left join sis_especialidadesmembros em on em.codPessoa=pe.codPessoa
		left join sis_estadosfederacao ef on ef.codEstadoFederacao=em.codEstadoFederacao
		left join sis_especialidades e on e.codEspecialidade=em.codEspecialidade
		left join sis_conselhos co on co.codConselho=e.codConselho
		where ac.codAtendimentoConduta = "' . $codAtendimentoConduta . '" limit 1');
		return $query->getRow();
	}


	public function pegaPorCodAtendimento($codAtendimento)
	{
		$query = $this->db->query('select ac.*,p.nomeExibicao,pp.nomeExibicao as nomeExibicaoAssinador,acs.* 
		from amb_atendimentoscondutas ac
		left join amb_atendimentoscondutasstatus as acs on acs.codStatus=ac.codStatus 
		left join sis_pessoas p on p.codPessoa=ac.codAutor 
		left join sis_pessoas pp on pp.codPessoa=ac.assinadoPor 
		where ac.codAtendimento = ' . $codAtendimento . ' 
		order by ac.dataCriacao desc');
		return $query->getResult();
	}
}
