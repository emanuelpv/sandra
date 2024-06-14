<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class EspecialidadesModel extends Model
{

	protected $table = 'sis_especialidades';
	protected $primaryKey = 'codEspecialidade';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['mensagemExigirIndicacao', 'exigirIndicacao', 'mensagemFalhaMarcacao', 'mensagemSucessoMarcacao', 'codTipoAgenda', 'cadastroReserva', 'dataAtualizacao', 'autor',	'dataCriacao', 'numeroConselho', 'codTipo', 'codConselho', 'codOrganizacao', 'descricaoEspecialidade'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;


	public function listaDropDownEspecialidades()
	{
		$query = $this->db->query('select codEspecialidade as id,descricaoEspecialidade as text 
		from sis_especialidades 
		where codTipo=1
		order by descricaoEspecialidade');
		return $query->getResult();
	}
	public function listaDropDownStatusAgendamento()
	{
		$query = $this->db->query('select codStatus as id,nomeStatus as text 
		from amb_agendamentosstatusconfig order by codStatus');
		return $query->getResult();
	}
	public function listaDropDownStatusExame()
	{
		$query = $this->db->query('select codStatus as id,nomeStatus as text from amb_agendamentosstatusconfig order by codStatus');
		return $query->getResult();
	}

	public function listaDropDownFaixasEtarias()
	{
		$query = $this->db->query('select codFaixaEtaria as id,descricaoFaixaEtaria as text from sis_faixasetarias order by idadeMinima asc');
		return $query->getResult();
	}

	public function listaDropDownEspecialidadesDisponivelMarcacao()
	{
		$query = $this->db->query('select distinct e.codEspecialidade as id,e.descricaoEspecialidade as text 
		from sis_especialidades  e
		join sis_especialidadesmembros em on e.codEspecialidade=em.codEspecialidade
		where em.atende=1 and e.codTipo=1 and ativoMarcacao=1
		order by descricaoEspecialidade');
		return $query->getResult();
	}
	public function listaDropDownEspecialidadesDisponivelIndicacao()
	{
		$query = $this->db->query('select distinct e.codEspecialidade as id,e.descricaoEspecialidade as text 
		from sis_especialidades  e
		join sis_especialidadesmembros em on e.codEspecialidade=em.codEspecialidade
		where em.atende=1 and e.codTipo=1 and e.exigirIndicacao=1
		order by descricaoEspecialidade');
		return $query->getResult();
	}

	public function listaDropDownEspecialidadesDisponivelMarcacaoExame()
	{
		$query = $this->db->query('select distinct e.codEspecialidade as id,e.descricaoEspecialidade as text 
		from sis_especialidades  e
		join sis_especialidadesmembros em on e.codEspecialidade=em.codEspecialidade
		where em.atende=1 and e.codTipo=1
		order by descricaoEspecialidade');
		return $query->getResult();
	}

	public function listaDropDownTipoAgendamento()
	{
		$query = $this->db->query('select codTipo as id,nomeTipo as text from 
		amb_agendamentostipo order by codTipo');
		return $query->getResult();
	}


	public function listaDropDownTipoExame()
	{
		$query = $this->db->query('select codTipo as id,nomeTipo as text from amb_agendamentostipo order by codTipo');
		return $query->getResult();
	}
	public function listaDropDownEspecialistas($codEspecialidade)
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select p.codPessoa as id,p.nomeExibicao as text 
		from sis_pessoas p
		join sis_especialidadesmembros em on p.codPessoa= em.codPessoa	
		join sis_especialidades e on e.codEspecialidade=em.codEspecialidade
		where e.codEspecialidade = ' . $codEspecialidade . ' and e.codOrganizacao=' . $codOrganizacao . '
		and p.ativo =1 and e.codTipo=1
		order by p.codCargo, p.dataNascimento');
		return $query->getResult();
	}

	public function listaDropDownEspecialistasDisponivelMarcacao($codEspecialidade = null)
	{
		$filtro = "";
		if ($codEspecialidade !== NULL and $codEspecialidade !== "") {
			$filtro = " and e.codEspecialidade = " . $codEspecialidade;
		}
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select distinct p.codPessoa as id,p.nomeExibicao as text 
		from sis_pessoas p
		join sis_especialidadesmembros em on p.codPessoa= em.codPessoa	
		join sis_especialidades e on e.codEspecialidade=em.codEspecialidade
		where  e.codOrganizacao=' . $codOrganizacao .  $filtro . '
		and p.ativo =1 and em.atende=1 and e.codTipo=1
		order by p.codCargo, p.dataNascimento');
		return $query->getResult();
	}

	public function especialistas()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select distinct em.atende,p.codPessoa,p.nomeExibicao,c.nomeConselho,c.nomeArea, em.numeroInscricao, e.descricaoEspecialidade,p.fotoPerfil,ef.siglaEstadoFederacao
		from sis_pessoas p
		left join sis_especialidadesmembros em on p.codPessoa= em.codPessoa	
		left join sis_estadosfederacao ef on ef.codEstadoFederacao= em.codEstadoFederacao	
		left join sis_especialidades e on e.codEspecialidade=em.codEspecialidade
		left join sis_conselhos c on c.codConselho=e.codConselho
		where e.codOrganizacao=' . $codOrganizacao . '
		and p.ativo =1 and e.codTipo=1
		order by p.codCargo, p.dataNascimento');
		return $query->getResult();
	}

	public function especialistasDisponiveis()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select distinct em.atende,p.codPessoa,p.informacoesComplementares,p.nomeExibicao,c.nomeConselho,c.nomeArea, em.numeroInscricao, e.descricaoEspecialidade,p.fotoPerfil,ef.siglaEstadoFederacao
		from sis_pessoas p
		left join sis_especialidadesmembros em on p.codPessoa= em.codPessoa	
		left join sis_estadosfederacao ef on ef.codEstadoFederacao= em.codEstadoFederacao	
		left join sis_especialidades e on e.codEspecialidade=em.codEspecialidade
		left join sis_conselhos c on c.codConselho=e.codConselho
		where e.codOrganizacao=' . $codOrganizacao . '
		and p.ativo =1 and em.atende=1 and e.codTipo=1
		order by p.codCargo, p.dataNascimento');
		return $query->getResult();
	}


	public function especialidadesDisponiveis()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select *
		from sis_especialidades e
		where e.codOrganizacao=' . $codOrganizacao . '
		and e.codTipo=1');
		return $query->getResult();
	}
	public function especialidadesExigemEncaminhamento()
	{
		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select *
		from sis_especialidades e
		where e.codOrganizacao=' . $codOrganizacao . '
		and e.codTipo=1 and e.exigirIndicacao=1');
		return $query->getResult();
	}

	public function listaDropDownEstadosFederacao()
	{
		$query = $this->db->query('select codEstadoFederacao as id,siglaEstadoFederacao as text from sis_estadosfederacao');
		return $query->getResult();
	}

	public function pegaEspecialidadePorCodEspecialidade($codEspecialidade = NULL, $codEspecialista = NULL)
	{
		$filtro = '';
		if ($codEspecialista !== NULL and $codEspecialista !== "null" and $codEspecialista !== "0") {
			$filtro = ' and em.codPessoa ="' . $codEspecialista . '"';
		}

		$query = $this->db->query('select e.*, em.codFaixaEtaria, fe.descricaoFaixaEtaria, fe.idadeMinima, fe.idadeMaxima
		from sis_especialidades e 
		left join sis_especialidadesmembros em on e.codEspecialidade=em.codEspecialidade 
		left join sis_faixasetarias fe on fe.codFaixaEtaria=em.codFaixaEtaria 
		where e.codEspecialidade="' . $codEspecialidade . '"' . $filtro);
		return $query->getRow();
	}


	public function pegaMembros($codEspecialidade)
	{
		$query = $this->db->query('select mi.descricaoMotivoInativacao,em.*, e.descricaoEspecialidade,p.nomeExibicao,p.ativo,ef.siglaEstadoFederacao
		from sis_especialidades e 
		left join sis_especialidadesmembros em on e.codEspecialidade=em.codEspecialidade 
		left join sis_pessoas p on em.codPessoa=p.codPessoa 
		left join sis_estadosfederacao ef on ef.codEstadoFederacao=em.codEstadoFederacao 
		left join sis_motivosinativacao mi on mi.codMotivoInativacao=p.codMotivoInativo
		where e.codEspecialidade=' . $codEspecialidade . ' order by p.ativo desc,p.codCargo asc, p.datanascimento asc');
		return $query->getResult();
	}

	public function pegaMembroPorEspecialidadeECodPessoa($codEspecialidade, $codPessoa)
	{
		$query = $this->db->query('select * from sis_especialidadesmembros where codEspecialidade=' . $codEspecialidade . ' and codPessoa=' . $codPessoa);
		return $query->getRow();
	}


	public function pegaConselho($codConselho)
	{

		$query = $this->db->query('select * from sis_conselhos where codConselho=' . $codConselho);

		if ($query  == NULL) {
			return array();
		} else {
			return $query->getRow();
		}
	}


	public function listaDropDownConselhos()
	{
		$query = $this->db->query('select codConselho as id,nomeConselho as text from sis_conselhos');
		return $query->getResult();
	}


	public function pegaEspecialidades()
	{
		$query = $this->db->query('select * from ' . $this->table . ' order by  descricaoEspecialidade asc');
		return $query->getResult();
	}


	public function pegaespecialidadePorNome($descricaoEspecialidade)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where descricaoEspecialidade="' . $descricaoEspecialidade . '"');
		return $query->getRow();
	}
	public function lookupCodNomeEspecialidade($codEspecialidade)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codEspecialidade="' . $codEspecialidade . '"');
		return $query->getRow();
	}
	public function lookupCodNomeEspecialidadesJson($especialidades)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codEspecialidade in (' . $especialidades . ')');
		return $query->getResult();
	}


	public function importarEspecialidades($dados)
	{

		$db      = \Config\Database::connect();
		$builder = $db->table("sis_especialidades");
		$builder->insert($dados);
		return 1;
	}
	public function insertMembro($dados)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table("sis_especialidadesmembros");
		$builder->insert($dados);
	}


	public function especialidadesApolo()
	{
		$apolo = \Config\Database::connect('apolo', false);
		$query = $apolo->query('select *
		from tp_especialidades');
		return $query->getResult();
	}
	public function membrosEspecialidadesApolo()
	{
		$apolo = \Config\Database::connect('apolo', false);
		$query = $apolo->query('select *
		from pes_especialidade');
		return $query->getResult();
	}
}
