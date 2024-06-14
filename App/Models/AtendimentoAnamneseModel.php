<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class AtendimentoAnamneseModel extends Model
{

	protected $table = 'amb_atendimentosanamnese';
	protected $primaryKey = 'codAtendimentoAnamnese';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['exameFisico','migrado','codAtendimento', 'codPaciente', 'codEspecialidade', 'codEspecialista', 'queixaPrincipal', 'hda', 'hmp', 'historiaMedicamentos', 'historiaAlergias', 'chv', 'parecer', 'outrasInformacoes', 'codStatus', 'dataCriacao', 'dataAtualizacao'];
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
		$query = $this->db->query('select * from amb_atendimentosanamnese');
		return $query->getResult();
	}

	public function pegaPorCodigo($codAtendimentoAnamnese)
	{
		$query = $this->db->query('select * from amb_atendimentosanamnese where codAtendimentoAnamnese = "' . $codAtendimentoAnamnese . '"');
		return $query->getRow();
	}

	public function verificaExistencia($codAtendimento)
	{
		$query = $this->db->query('select a.*, aa.codAtendimentoAnamnese FROM amb_atendimentos a 
		left join amb_atendimentosanamnese aa on a.codAtendimento=aa.codAtendimento
		where  a.codAtendimento =' . $codAtendimento);
		return $query->getRow();
	}

	public function pegaPorCodAtendimento($codAtendimento)
	{
		$query = $this->db->query('select ta.descricaoTipoAtendimento,a.codAutor as autorAtendimento,aa.*, a.codLocalAtendimento,a.codClasseRisco,a.codTipoAtendimento,a.codPaciente,
		a.codStatus as codStatusAtendimento,ass.descricaoStatusAtendimento,pc.*, DATE_ADD(aa.dataCriacao, INTERVAL 1 DAY)  < NOW() as passou24Horas
		from amb_atendimentosanamnese aa 
		right join amb_atendimentos a on a.codAtendimento=aa.codAtendimento
		left join amb_atendimentosstatus ass on ass.codStatusAtendimento=a.codStatus 
		left join amb_atendimentostipos ta on ta.codTipoAtendimento=a.codTipoAtendimento 
		left join amb_atendimentosparametrosclinicos pc on pc.codAtendimento=a.codAtendimento 
		where  a.codAtendimento =' . $codAtendimento);
		return $query->getRow();
	}
}
