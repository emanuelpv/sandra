<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class ItensFarmaciaModel extends Model
{

	protected $table = 'sis_itensfarmacia';
	protected $primaryKey = 'codItem';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['antibiotico','ativo', 'pp', 'codOrganizacao', 'nee', 'descricaoItem', 'valor', 'saldo', 'observacao', 'dataCriacao', 'dataAtualizacao', 'codAutor', 'codBarra', 'sire', 'codCategoria', 'ean', 'nme', 'imagemItem'];
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
		$query = $this->db->query('select * from sis_itensfarmacia');
		return $query->getResult();
	}

	public function pegaPorCodCategoria($codCategoria)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from sis_itensfarmacia where codOrganizacao = ' . $codOrganizacao . ' and codCategoria = ' . $codCategoria);
		return $query->getResult();
	}


	public function pegaPorCodigo($codItem)
	{
		$query = $this->db->query('select * from sis_itensfarmacia iff
		left join sis_itensfarmaciacategoria ifc on ifc.codCategoria = iff.codCategoria where iff.codItem = ' . $codItem);
		return $query->getRow();
	}



	public function verificaPrescricaoDispensada($codAtendimento)
	{
		$query = $this->db->query('select * from amb_atendimentosprescricoes where codAtendimento = "' . $codAtendimento . '" and codStatus = 4 and dataInicio =CURDATE()');
		return $query->getRow();
	}


	public function pegaPorDescricao($nee)
	{
		$query = $this->db->query('select * from sis_itensfarmacia iff where iff.nee = "' . $nee . '"');
		return $query->getRow();
	}



	public function listaDropDown()
	{
		$query = $this->db->query('select codCategoria as id, descricaoCategoria as text from sis_itensfarmaciacategoria order by descricaoCategoria');
		return $query->getResult();
	}


	public function listaDropDownMedicamentos()
	{
		$query = $this->db->query('select codItem as id, descricaoItem as text from sis_itensfarmacia where codCategoria not in(6) and descricaoItem is not null order by descricaoItem');
		return $query->getResult();
	}
	public function listaDropDownTodosItens()
	{
		$query = $this->db->query('select codItem as id, descricaoItem as text from sis_itensfarmacia 
		where descricaoItem is not null order by descricaoItem');
		return $query->getResult();
	}
	public function listaDropDownDietas()
	{
		$query = $this->db->query('select codItem as id, descricaoItem as text from sis_itensfarmacia where codCategoria in(3) and descricaoItem is not null order by descricaoItem');
		return $query->getResult();
	}


	public function listaDropDownMateriais()
	{
		$query = $this->db->query('select codItem as id, descricaoItem as text from sis_itensfarmacia where codCategoria =6 and descricaoItem is not null order by descricaoItem');
		return $query->getResult();
	}
	public function listaDropDownMedicamentosAnvisa()
	{
		$query = $this->db->query('select distinct produto as id,concat(produto," - ",principioAtivo, " - ",apresentacao) as text from sis_medicamentosanvisa where restricaoHospitalar="não" and comercializacao="sim" order by produto asc');
		return $query->getResult();
	}



	public function pendentesDispensacao($codDepartamento = NULL)
	{
		$filtro = '';


		$codDepartamento = session()->filtroDispensacao["codDepartamento"];
		$codCategoria = session()->filtroDispensacao["codCategoria"];
		$dataInicio = session()->filtroDispensacao["dataInicio"];
		$dataEncerramento = session()->filtroDispensacao["dataEncerramento"];

		if ($codDepartamento !== 0 and $codDepartamento !== NULL and $codDepartamento !== '' and $codDepartamento !== ' ') {
			$filtro .= ' and la.codDepartamento=' . $codDepartamento;
		}

		if ($codCategoria !== 0 and $codCategoria !== NULL and $codCategoria !== '' and $codCategoria !== ' ') {

			if ($codCategoria == 1) {
				$filtro .= ' and  ap.codAtendimentoPrescricao  in(select codAtendimentoPrescricao from amb_atendimentosprescricoesmedicamentos)';
			}

			if ($codCategoria == 2) {
				$filtro .= ' and  ap.codAtendimentoPrescricao in(select codAtendimentoPrescricao from amb_atendimentosprescricoesmateriais)';
			}
		}

		if (($dataInicio == $dataEncerramento) or ($dataInicio !== '' and $dataEncerramento == '')) {
			if ($dataInicio !== NULL and $dataInicio !== "") {

				$filtro .= ' and ap.dataInicio = "' . $dataInicio . '"';
			} else {
				$filtro .= ' and ap.dataInicio = CURDATE()';
			}
		}

		if ($dataInicio <= $dataEncerramento and $dataEncerramento !== '') {

			$filtro .= ' and ap.dataInicio >= "' . $dataInicio . '" and ap.dataEncerramento <= "' . $dataEncerramento . '"';
		}


		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select ap.conteudoPrescricao,ap.dataInicio, ap.dataEncerramento,ap.codAtendimentoPrescricao, pe.nomeExibicao as nomeEspecialista,a.codPaciente, a.codAtendimento,pa.nomeCompleto, TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade,
		a.dataCriacao,d.descricaoDepartamento,la.codLocalAtendimento,la.descricaoLocalAtendimento,s.descricaoStatus,s.corStatusPrescricao
		from  amb_atendimentos a 
		left join amb_atendimentosprescricoes ap on ap.codAtendimento=a.codAtendimento		
		left join amb_atendimentosprescricoesstatus s on s.codStatus=ap.codStatus
		left join sis_pessoas pe on pe.codPessoa=ap.prescricaoAssinadaPor
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente		
		left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento			
		where a.codOrganizacao=' . $codOrganizacao . $filtro . '
		and  a.codStatus not in (2, 3, 8, 9, 11) 
		and a.codTipoAtendimento in (1,4,5) 
		and ap.codStatus in(2)
		order by a.dataCriacao desc');
		return $query->getResult();
	}

	public function emProcessamentoDispensacao($codDepartamento = NULL)
	{
		$filtro = '';


		$codDepartamento = session()->filtroDispensacao["codDepartamento"];
		$codCategoria = session()->filtroDispensacao["codCategoria"];
		$dataInicio = session()->filtroDispensacao["dataInicio"];
		$dataEncerramento = session()->filtroDispensacao["dataEncerramento"];

		if ($codDepartamento !== 0 and $codDepartamento !== NULL and $codDepartamento !== '' and $codDepartamento !== ' ') {
			$filtro .= ' and la.codDepartamento=' . $codDepartamento;
		}

		
		if ($codCategoria !== 0 and $codCategoria !== NULL and $codCategoria !== '' and $codCategoria !== ' ') {

			if ($codCategoria == 1) {
				$filtro .= ' and  ap.codAtendimentoPrescricao  in(select codAtendimentoPrescricao from amb_atendimentosprescricoesmedicamentos)';
			}

			if ($codCategoria == 2) {
				$filtro .= ' and  ap.codAtendimentoPrescricao in(select codAtendimentoPrescricao from amb_atendimentosprescricoesmateriais)';
			}
		}


		if (($dataInicio == $dataEncerramento) or ($dataInicio !== '' and $dataEncerramento == '')) {
			if ($dataInicio !== NULL and $dataInicio !== "") {

				$filtro .= ' and ap.dataInicio = "' . $dataInicio . '"';
			} else {
				$filtro .= ' and ap.dataInicio = CURDATE()';
			}
		}

		if ($dataInicio <= $dataEncerramento and $dataEncerramento !== '') {

			$filtro .= ' and ap.dataInicio >= "' . $dataInicio . '" and ap.dataEncerramento <= "' . $dataEncerramento . '"';
		}


		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select ap.conteudoPrescricao,ap.dataInicio, ap.dataEncerramento,ap.codAtendimentoPrescricao, pe.nomeExibicao as nomeEspecialista,a.codPaciente, a.codAtendimento,pa.nomeCompleto, TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade,
		a.dataCriacao,d.descricaoDepartamento,la.codLocalAtendimento,la.descricaoLocalAtendimento,s.descricaoStatus,s.corStatusPrescricao
		from  amb_atendimentos a 
		left join amb_atendimentosprescricoes ap on ap.codAtendimento=a.codAtendimento		
		left join amb_atendimentosprescricoesstatus s on s.codStatus=ap.codStatus
		left join sis_pessoas pe on pe.codPessoa=ap.prescricaoAssinadaPor
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente		
		left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento			
		where a.codOrganizacao=' . $codOrganizacao . $filtro . '
		and  a.codStatus not in (2, 3, 8, 9, 11) 
		and a.codTipoAtendimento in (1,4,5) 
		and ap.codStatus in(3)
		order by a.dataCriacao desc');
		return $query->getResult();
	}




	public function dispensados($codDepartamento = NULL)
	{
		$filtro = '';

		$codDepartamento = session()->filtroDispensacao["codDepartamento"];
		$codCategoria = session()->filtroDispensacao["codCategoria"];
		$dataInicio = session()->filtroDispensacao["dataInicio"];
		$dataEncerramento = session()->filtroDispensacao["dataEncerramento"];

		if ($codDepartamento !== 0 and $codDepartamento !== NULL and $codDepartamento !== '' and $codDepartamento !== ' ') {
			$filtro .= ' and la.codDepartamento=' . $codDepartamento;
		}
		if ($codCategoria !== 0 and $codCategoria !== NULL and $codCategoria !== '' and $codCategoria !== ' ') {

			if ($codCategoria == 1) {
				$filtro .= ' and  ap.codAtendimentoPrescricao  in(select codAtendimentoPrescricao from amb_atendimentosprescricoesmedicamentos)';
			}

			if ($codCategoria == 2) {
				$filtro .= ' and  ap.codAtendimentoPrescricao in(select codAtendimentoPrescricao from amb_atendimentosprescricoesmateriais)';
			}
		}

		if (($dataInicio == $dataEncerramento) or ($dataInicio !== '' and $dataEncerramento == '')) {
			if ($dataInicio !== NULL and $dataInicio !== "") {

				$filtro .= ' and ap.dataInicio = "' . $dataInicio . '"';
			} else {
				$filtro .= ' and ap.dataInicio = CURDATE()';
			}
		}

		if ($dataInicio <= $dataEncerramento and $dataEncerramento !== '') {

			$filtro .= ' and ap.dataInicio >= "' . $dataInicio . '" and ap.dataEncerramento <= "' . $dataEncerramento . '"';
		}


		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select ap.conteudoPrescricao,ap.dataInicio, ap.dataEncerramento,ap.codAtendimentoPrescricao, pe.nomeExibicao as nomeEspecialista,a.codPaciente, a.codAtendimento,pa.nomeCompleto, TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade,
		a.dataCriacao,d.descricaoDepartamento,la.codLocalAtendimento,la.descricaoLocalAtendimento,s.descricaoStatus,s.corStatusPrescricao
		from  amb_atendimentos a 
		left join amb_atendimentosprescricoes ap on ap.codAtendimento=a.codAtendimento		
		left join amb_atendimentosprescricoesstatus s on s.codStatus=ap.codStatus
		left join sis_pessoas pe on pe.codPessoa=ap.prescricaoAssinadaPor
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente		
		left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento			
		where a.codOrganizacao=' . $codOrganizacao . $filtro . '
		and  a.codStatus not in (2, 3, 8, 9, 11) 
		and a.codTipoAtendimento in (1,4) 
		and ap.codStatus in(4)
		order by a.dataCriacao desc');
		return $query->getResult();
	}



	public function atendimentoPorCodAtendimentoPrescricao($codAtendimentoPrescricao)
	{
		$query = $this->db->query('select ap.dispensacaoAssinadaPor,ap.codStatus,ca.siglaCargo, c.nomeConselho,em.numeroInscricao,uf.siglaEstadoFederacao as uf,a.codAtendimento,  pe.nomeCompleto as nomeCompletoEspecialista, ap.*,pe.nomeExibicao as especialista, pa.nomeCompleto as paciente, 
		pa.codProntuario,pa.codPlano, aps.descricaoStatus,ap.dataInicio, ap.dataEncerramento, ap.codAtendimentoPrescricao, TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade,d.abreviacaoDepartamento,la.descricaoLocalAtendimento,tp.siglaTipoBeneficiario
		from amb_atendimentosprescricoes ap
		left join amb_atendimentos a on a.codAtendimento = ap.codAtendimento
		left join sis_pacientes pa on pa.codPaciente = a.codPaciente
		left join sis_tipobeneficiario tp on pa.codTipoBeneficiario = tp.codTipoBeneficiario
		left join sis_pessoas pe on pe.codPessoa=ap.dispensacaoAssinadaPor 
        left join sis_cargos ca on ca.codCargo = pe.codCargo
        left join sis_especialidadesmembros em on em.codPessoa = pe.codPessoa
        left join sis_especialidades e on e.codEspecialidade = em.codEspecialidade
        left join sis_conselhos c on c.codConselho = e.codConselho
        left join sis_estadosfederacao uf on uf.codEstadoFederacao = em.codEstadoFederacao
		left join amb_atendimentosprescricoesstatus aps on aps.codStatus=ap.codStatus
		left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento                
        where ap.codAtendimentoPrescricao =' . $codAtendimentoPrescricao . ' order by ap.codAtendimentoPrescricao desc limit 1');
		return $query->getRow();
	}


	public function prescricoesPorCodAtendimentoPrescricao($codAtendimentoPrescricao)
	{
		$query = $this->db->query('select pc.codPrescricaoComplementar,asm.dataCriacao as dataSuspensao,asm.motivo, asm.codSuspensaoMedicamento,ppe.nomeExibicao as autorSuspensao,pex.nomeExibicao as autorComplemento,apm.dataCriacao as dataCriacaoComplemento,ap.*,pe.nomeExibicao as especialista, pe.nomeCompleto as nomeCompletoEspecialista, pa.nomeCompleto as paciente, pa.codProntuario,
		aps.descricaoStatus,iff.descricaoItem,apm.qtde,u.descricaoUnidade,v.descricaoVia,apm.freq,
		pp.descricaoPeriodo, apm.dias, apm.horaIni,saa.descricaoAplicarAgora,
		rp.descricaoRiscoPrescricao,apm.obs,apm.apraza,apm.total,apm.totalLiberado,apm.obs as observacaoMedicamento,
		iff.nee,ap.dieta
		from amb_atendimentosprescricoes ap
		left join amb_atendimentos a on a.codAtendimento = ap.codAtendimento
		left join sis_pacientes pa on pa.codPaciente = a.codPaciente
		left join sis_pessoas pe on pe.codPessoa=ap.codAutor
		left join amb_atendimentosprescricoesstatus aps on aps.codStatus=ap.codStatus 
		left join amb_atendimentosprescricoesmedicamentos apm on apm.codAtendimentoPrescricao=ap.codAtendimentoPrescricao 
		left join amb_atendimentossuspensaomedicamentos asm on asm.codPrescricaoMedicamento=apm.codPrescricaoMedicamento
        left join sis_pessoas ppe on ppe.codPessoa=asm.codAutor
		left join sis_pessoas pex on pex.codPessoa=apm.codAutor
		left join amb_prescricaocomplementar pc on pc.codPrescricaoMedicamento=apm.codPrescricaoMedicamento
		left join sis_itensfarmacia iff on iff.codItem=apm.codMedicamento 
		left join sis_unidades u on u.codUnidade=apm.und 
		left join sis_vias v on v.codVia=apm.via 
		left join sis_periodoprescricao pp on pp.codPeriodo=apm.per 
		left join sis_statusaplicaragora saa on saa.codAplicarAgora=apm.agora 
		left join sis_riscoprescricao rp on rp.codRiscoPrescricao=apm.risco 
		where ap.codAtendimentoPrescricao = ' . $codAtendimentoPrescricao . ' order by apm.codPrescricaoMedicamento desc');
		return $query->getResult();
	}

	public function materiaisPorCodAtendimentoPrescricao($codAtendimentoPrescricao)
	{
		$query = $this->db->query('select apm.*,iff.*,ap.*, apm.observacao as observacaoMaterial
		from amb_atendimentosprescricoes ap
		left join amb_atendimentosprescricoesmateriais apm on apm.codAtendimentoPrescricao=ap.codAtendimentoPrescricao
		left join sis_itensfarmacia iff on iff.codItem=apm.codMaterial
		where ap.codAtendimentoPrescricao = ' . $codAtendimentoPrescricao . ' order by ap.codAtendimentoPrescricao desc');
		return $query->getResult();
	}
}
