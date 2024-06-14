<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class FaturamentoModel extends Model
{

	protected $table = 'fat_faturamento';
	protected $primaryKey = 'codFatura';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['motivoReabertura','reabertoPor','dataReabertura','ccusto', 'codStatusFatura', 'codAuditor', 'codAtendimento', 'codPaciente', 'dataCriacao', 'dataAtualizacao', 'codAutor', 'dataInicio', 'dataEncerramento','observacoes'];
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
		$query = $this->db->query('select * from fat_faturamento');
		return $query->getResult();
	}


	public function datalhesMedicamentos($codFatura)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select iff.descricaoItem, al.descricaoLocalAtendimento,d.descricaoDepartamento, m.* from 
		fat_faturamentomedicamentos m
		left join sis_itensfarmacia iff on iff.codItem = m.codMedicamento
		left join amb_atendimentoslocais al on al.codLocalAtendimento=m.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=al.codDepartamento
	
		where m.codFatura="' . $codFatura . '" and quantidade > 0');// and codStatus>=8
		return $query->getResult();
	}

	
	public function datalhesMedicamentosConsolidados($codFatura)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select iff.descricaoItem, sum(m.quantidade) quantidade from 
		fat_faturamentomedicamentos m
		left join sis_itensfarmacia iff on iff.codItem = m.codMedicamento
		left join amb_atendimentoslocais al on al.codLocalAtendimento=m.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=al.codDepartamento	
		where m.codFatura="' . $codFatura . '" and quantidade > 0
		group by iff.descricaoItem
		order by iff.descricaoItem asc');// and codStatus>=8
		
		return $query->getResult();
	}

	public function atualizaItens($codFatura)
	{



		//MEDICAMENTOS		
		$this->db->query('
		update fat_faturamentomedicamentos 
		set codStatus=9, dataAtualizacao="' . date('Y-m-d H:i') . '", codAuditor=' . session()->codPessoa . '
		where codFatura="' . $codFatura . '" and codStatus not in(-1,0,6,7)');


		//TAXAR E SERVIÇOS		
		$this->db->query('
		update fat_faturamentotaxasservicos 
		set codStatus=9, dataAtualizacao="' . date('Y-m-d H:i') . '", codAuditor=' . session()->codPessoa . '
		where codFatura="' . $codFatura . '" and codStatus not in(-1,0,6)');


		//PROCEDIMENTOS	
		$this->db->query('
		update fat_faturamentoprocedimentos 
		set codStatus=9, dataAtualizacao="' . date('Y-m-d H:i') . '", codAuditor=' . session()->codPessoa . '
		where codFatura="' . $codFatura . '" and codStatus not in(-1,0,6)');


		//MATERIAIS	
		$this->db->query('
		update fat_faturamentomateriais 
		set codStatus=9, dataAtualizacao="' . date('Y-m-d H:i') . '", codAuditor=' . session()->codPessoa . '
		where codFatura="' . $codFatura . '" and codStatus not in(-1,0,6)');

		//KITS	
		$this->db->query('
		update fat_faturamentokits 
		set codStatus=9, dataAtualizacao="' . date('Y-m-d H:i') . '", codAuditor=' . session()->codPessoa . '
		where codFatura="' . $codFatura . '" and codStatus not in(-1,0,6,7)');



		return true;
	}
	public function datalhesMateriais($codFatura)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select iff.descricaoItem, al.descricaoLocalAtendimento,d.descricaoDepartamento, m.* from 
		fat_faturamentomateriais m
		left join sis_itensfarmacia iff on iff.codItem = m.codMaterial
		left join amb_atendimentoslocais al on al.codLocalAtendimento=m.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=al.codDepartamento	
		where m.codFatura="' . $codFatura . '" and quantidade > 0');// and codStatus>=8
		return $query->getResult();
	}
	public function datalhesMateriaisConsolidados($codFatura)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select iff.descricaoItem, sum(m.quantidade) as quantidade from 
		fat_faturamentomateriais m
		left join sis_itensfarmacia iff on iff.codItem = m.codMaterial
		left join amb_atendimentoslocais al on al.codLocalAtendimento=m.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=al.codDepartamento	
		where m.codFatura="' . $codFatura . '" and quantidade > 0
		group by iff.descricaoItem
		order by iff.descricaoItem asc');// and codStatus>=8
		return $query->getResult();
	}


	public function datalhesKits($codFatura)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select kk.descricaoKit, al.descricaoLocalAtendimento,d.descricaoDepartamento, k.* 
		from fat_faturamentokits k
		left join sis_kits kk on kk.codKit = k.codKit
		left join amb_atendimentoslocais al on al.codLocalAtendimento=k.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=al.codDepartamento
	
		where k.codFatura="' . $codFatura . '" and quantidade > 0');// and codStatus>=8
		return $query->getResult();
	}

	public function datalhesProcedimentos($codFatura)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select ap.descricao,ap.referencia, al.descricaoLocalAtendimento,d.descricaoDepartamento, p.* 
		from fat_faturamentoprocedimentos p
		left join amb_procedimentos ap on ap.codProcedimento = p.codProcedimento
		left join amb_atendimentoslocais al on al.codLocalAtendimento=p.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=al.codDepartamento
	
		where p.codFatura="' . $codFatura . '" and quantidade > 0');// and codStatus>=8
		return $query->getResult();
	}
	public function datalhesTaxasServicos($codFatura)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select ats.descricao,ats.referencia, al.descricaoLocalAtendimento,d.descricaoDepartamento, ts.* 
		from fat_faturamentotaxasservicos ts
		left join amb_taxasservicos ats on ats.codTaxaServico = ts.codTaxaServico
		left join amb_atendimentoslocais al on al.codLocalAtendimento=ts.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=al.codDepartamento	
		where ts.codFatura="' . $codFatura . '" and quantidade > 0');// and codStatus>=8
		return $query->getResult();
	}



	public function faturasAtendimento($codAtendimento)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select sf.*,f.*, a.codAtendimento, pa.nomeExibicao as nomePaciente, pe.nomeExibicao as autor, f.dataCriacao
		from fat_faturamento f
		left join amb_atendimentos a on a.codAtendimento=f.codAtendimento
		left join sis_pessoas pe on pe.codPessoa=f.codAutor
		left join sis_pacientes pa on pa.codPaciente=f.codPaciente
		left join fat_statusfatura sf on sf.codStatusFatura=f.codStatusFatura
		where f.codAtendimento=' . $codAtendimento);
		return $query->getResult();
	}


	public function verificaSeFaturaAberta($codAtendimento)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * 
		from fat_faturamento f
		where f.codAtendimento=' . $codAtendimento . ' and codStatusFatura=0 order by dataCriacao desc limit 1');
		return $query->getRow();
	}


	public function verificaStatusConta($codAtendimento)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * FROM amb_atendimentos where codAtendimento = "' . $codAtendimento . '"');
		return $query->getRow();
	}

	public function verificaExistenciaMateriaisNaoFaturadas($codAtendimento)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select count(*) totalNaoLancados FROM amb_atendimentosprescricoesmateriais apm
		left join amb_atendimentosprescricoes ap on ap.codAtendimentoPrescricao = apm.codAtendimentoPrescricao
		left join fat_faturamentomateriais fm on apm.codPrescricaoMaterial = fm.codPrescricaoMaterial
		where fm.codFaturamentoMaterial is null and ap.codAtendimento = "' . $codAtendimento . '" and ap.dataInicio >="2022-09-03"');
		return $query->getRow();
	}
	public function verificaExistenciaProcedimentosNaoFaturadas($codAtendimento)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select count(*) totalNaoLancados FROM amb_atendimentosprescricoesprocedimentos apm
		left join amb_atendimentosprescricoes ap on ap.codAtendimentoPrescricao = apm.codAtendimentoPrescricao
		left join fat_faturamentoprocedimentos fm on apm.codPrescricaoProcedimento = fm.codPrescricaoProcedimento
		where fm.codFaturamentoProcedimento is null and ap.codAtendimento = "' . $codAtendimento . '" and ap.dataInicio >="2022-09-03"');
		return $query->getRow();
	}
	public function verificaExistenciaMedicamentosNaoFaturadas($codAtendimento)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select count(*) totalNaoLancados FROM amb_atendimentosprescricoesmedicamentos apm 
		left join amb_atendimentosprescricoes ap on ap.codAtendimentoPrescricao = apm.codAtendimentoPrescricao 
		left join fat_faturamentomedicamentos fm on apm.codPrescricaoMedicamento = fm.codPrescricaoMedicamento
		where fm.codFaturamentoMedicamento is null and ap.codAtendimento = "' . $codAtendimento . '" and ap.dataInicio >="2022-09-03"');
		return $query->getRow();
	}
	public function verificaExistenciaKitsNaoFaturadas($codAtendimento)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select count(*) totalNaoLancados FROM amb_atendimentosprescricoeskits apm 
		left join amb_atendimentosprescricoes ap on ap.codAtendimentoPrescricao = apm.codAtendimentoPrescricao 
		left join fat_faturamentokits fm on apm.codPrescricaoKit = fm.codPrescricaoKit 
		where fm.codFaturamentoKit is null and ap.codAtendimento = "' . $codAtendimento . '" and ap.dataInicio >="2022-09-03"');
		return $query->getRow();
	}



	public function dadosFatura($codFatura)
	{

		$query = $this->db->query('select a.dataCriacao as dataAdmissao,pee.nomeExibicao as autorReabertura, f.dataCriacao, f.codFatura, sf.*,al.descricaoLocalAtendimento,tp.siglaTipoBeneficiario,d.descricaoDepartamento, 
		a.dataInicio as dataInicioAtendimento,pa.dataNascimento, TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade, f.codAtendimento,pa.informacoesComplementares,pa.validade,o.siglaOm,pa.dataNascimento,fo.siglaForca,pa.sexo,c.descricaoCargo as cargoPaciente,pa.codPlano,pa.cpf, f.*, a.codAtendimento,pa.codProntuario, pa.nomeExibicao as nomePaciente, pe.nomeExibicao as autor, f.dataCriacao
		from fat_faturamento f
		left join amb_atendimentos a on a.codAtendimento=f.codAtendimento
		left join amb_atendimentoslocais al on al.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=al.codDepartamento
		left join sis_pessoas pe on pe.codPessoa=f.codAutor
		left join sis_pessoas pee on pee.codPessoa=f.reabertoPor
		left join sis_pacientes pa on pa.codPaciente=f.codPaciente
		left join sis_tipobeneficiario tp on pa.codTipoBeneficiario = tp.codTipoBeneficiario
		left join sis_cargos c on c.codCargo=pa.codCargo
		left join sis_forca fo on fo.codForca=pa.codForca
		left join sis_om o on o.codOm=pa.codOm
		left join fat_statusfatura sf on sf.codStatusFatura=f.codStatusFatura
		where f.codFatura=' . $codFatura);
		return $query->getRow();
	}

	public function periodo($codFatura)
	{

		$query = $this->db->query('select min(dataPrescricao) dataMinima, max(dataPrescricao) as dataMaxima 
		from (    
		SELECT * FROM fat_faturamentomedicamentos where codFatura=' . $codFatura . '
		union all
		SELECT * FROM fat_faturamentokits where codFatura=' . $codFatura . '
		union all
		SELECT * FROM fat_faturamentoprocedimentos where codFatura=' . $codFatura . '
		union all
		SELECT * FROM fat_faturamentomateriais where codFatura=' . $codFatura . '
			)x');
		return $query->getRow();
	}


	public function totaisFatura($codFatura)
	{

		$totais = array();

		//MEDICAMENTOS
		$medicamentos = $this->db->query('
		select sum(quantidade*valor) as subtotal FROM fat_faturamentomedicamentos where codFatura=' . $codFatura.' and quantidade > 0');// and codStatus>=8
		if ($medicamentos->getRow()->subtotal !== NULL) {
			$totalMedicamentos = $medicamentos->getRow()->subtotal;
		} else {
			$totalMedicamentos = '0.00';
		}

		//MATERIAIS
		$materiais = $this->db->query('
		select sum(quantidade*valor) as subtotal FROM fat_faturamentomateriais where codFatura=' . $codFatura.' and quantidade > 0');// and codStatus>=8

		if ($materiais->getRow()->subtotal !== NULL) {
			$totalMateriais = $materiais->getRow()->subtotal;
		} else {
			$totalMateriais = '0.00';
		}

		//KITS
		$kits = $this->db->query('
		select sum(quantidade*valor) as subtotal FROM fat_faturamentokits where codFatura=' . $codFatura.' and quantidade > 0');// and codStatus>=8

		if ($kits->getRow()->subtotal !== NULL) {
			$totalKits = $kits->getRow()->subtotal;
		} else {
			$totalKits = '0.00';
		}



		//PROCEDIMENTOS
		$procedimentos = $this->db->query('
		select sum(quantidade*valor) as subtotal FROM fat_faturamentoprocedimentos where codFatura=' . $codFatura.' and quantidade > 0');// and codStatus>=8
		if ($procedimentos->getRow()->subtotal !== NULL) {
			$totalProcedimentos = $procedimentos->getRow()->subtotal;
		} else {
			$totalProcedimentos = '0.00';
		}


		//TAXAS E SERVIÇOS
		$taxasServicos = $this->db->query('
		select sum(quantidade*valor) as subtotal FROM fat_faturamentotaxasservicos where codFatura=' . $codFatura.' and quantidade > 0');// and codStatus>=8
		if ($taxasServicos->getRow()->subtotal !== NULL) {
			$totalTaxasServicos = $taxasServicos->getRow()->subtotal;
		} else {
			$totalTaxasServicos = '0.00';
		}


		$totais = array(
			'totalMedicamentos' => $totalMedicamentos,
			'totalMateriais' => $totalMateriais,
			'totalKits' => $totalKits,
			'totalProcedimentos' => $totalProcedimentos,
			'totalTaxasServicos' => $totalTaxasServicos,
		);

		return $totais;
	}


	public function pegaPorCodigo($codFatura)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codFatura = "' . $codFatura . '"');
		return $query->getRow();
	}



	public function verificaUltimaFatura($codAtendimento = NULL)
	{
		$query = $this->db->query('select *
		from fat_faturamento where codAtendimento="' . $codAtendimento .'"');
		return $query->getRow();

	}

	
	public function verificaSePrescricao($codAtendimento = NULL)
	{
		$query = $this->db->query('select *
		from amb_atendimentosprescricoes where codAtendimento="' . $codAtendimento .'" and dataInicio >= "2022-09-03"');
		return $query->getRow();

	}

	public function contasAbertas($codDepartamento = NULL)
	{
		$filtro = '';

		if ($codDepartamento == NULL) {
			$filtro .= '';
		} else {
			if ($codDepartamento == 0) {
				$filtro .= '';
			} else {
				$filtro .= ' and la.codDepartamento="' . $codDepartamento.'"';
			}
		}


		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select a.codPaciente, a.codAtendimento,pa.nomeCompleto, TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade,
		a.dataCriacao,d.descricaoDepartamento,la.codLocalAtendimento,la.descricaoLocalAtendimento,s.descricaoStatusAtendimento
		from  amb_atendimentos a 
		left join amb_atendimentosstatus s on s.codStatusAtendimento=a.codStatus
		left join sis_pessoas pe on pe.codPessoa=a.codEspecialista
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente		
		left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento	
		where a.codOrganizacao=' . $codOrganizacao . $filtro . ' and a.codStatusConta=1 and a.codTipoAtendimento in (1,4,5,6,7)
		order by a.dataCriacao desc');
		return $query->getResult();
	}

	public function buscaAvancada($paciente = NULL)
	{
		$filtro = ' and  pa.nomeCompleto="traz nada"';

		if ($paciente !== NULL and $paciente !== '' and $paciente !== ' ') {
			$filtro = ' and ( pa.nomeCompleto like "%'.$paciente.'%" or pa.cpf like "%'.$paciente.'%" or pa.codPlano like "%'.$paciente.'%" )';
		}


		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select a.codPaciente, a.codAtendimento,pa.nomeCompleto, TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade,
		a.dataCriacao,d.descricaoDepartamento,la.codLocalAtendimento,la.descricaoLocalAtendimento,s.descricaoStatusAtendimento
		from  amb_atendimentos a 
		left join amb_atendimentosstatus s on s.codStatusAtendimento=a.codStatus
		left join sis_pessoas pe on pe.codPessoa=a.codEspecialista
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente		
		left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento	
		where a.codOrganizacao=' . $codOrganizacao . $filtro . ' and a.codStatusConta=1 and a.codTipoAtendimento in (1,4,5,6,7)
		order by a.dataCriacao desc');
		return $query->getResult();
	}


	
	public function contasFechadas($codDepartamento = NULL)
	{
		$filtro = '';

		if ($codDepartamento == NULL) {
			$filtro .= '';
		} else {
			if ($codDepartamento == 0) {
				$filtro .= '';
			} else {
				$filtro .= ' and la.codDepartamento=' . $codDepartamento;
			}
		}


		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select a.codPaciente, a.codAtendimento,pa.nomeCompleto, TIMESTAMPDIFF(YEAR, pa.dataNascimento,CURDATE()) as idade,
		a.dataCriacao,d.descricaoDepartamento,la.codLocalAtendimento,la.descricaoLocalAtendimento,s.descricaoStatusAtendimento
		from  amb_atendimentos a 
		left join amb_atendimentosstatus s on s.codStatusAtendimento=a.codStatus
		left join sis_pessoas pe on pe.codPessoa=a.codEspecialista
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente		
		left join amb_atendimentoslocais la on la.codLocalAtendimento=a.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento			
		where a.codOrganizacao=' . $codOrganizacao . $filtro . ' and (a.dataEncerramento is null or DATE_FORMAT(a.dataEncerramento, "%Y-%m-%d")  > ADDDATE(NOW(), INTERVAL -30 DAY)) and a.codStatusConta=2 and a.codTipoAtendimento in (1,4,5,6)
		order by a.dataCriacao desc');
		return $query->getResult();
	}


	public function medicamentosPendentesFaturamento($codDepartamento = NULL)
	{
		$filtro = '';


		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select *
		from amb_atendimentosprescricoes ap
		left join amb_atendimentosprescricoesmedicamentos apm on apm.codAtendimentoPrescricao=ap.codAtendimentoPrescricao
		left join amb_atendimentosprescricoesmedicamentos apm on apm.codAtendimentoPrescricao=ap.codAtendimentoPrescricao
		left join amb_atendimentoslocais la on la.codLocalAtendimento=ap.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento			
		where ap.codOrganizacao=' . $codOrganizacao . $filtro);
		return $query->getResult();
	}
}
