<?php
// Desenvolvido por Sandra Sistemas

namespace App\Models;

use CodeIgniter\Model;

class SolicitacoesSuporteModel extends Model
{

	protected $table = 'sis_solicitacoessuporte';
	protected $primaryKey = 'codSolicitacao';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['dataEncerramento', 'dataInicio', 'codOrganizacao', 'dataCriacao', 'codOrigemSolicitacao', 'codResponsavel', 'codDepartamentoSolicitante', 'codCategoriaSuporte', 'descricaoSolicitacao', 'codSolicitante', 'codStatusSolicitacao', 'codTipoSolicitacao', 'codUrgencia', 'codPrioridade', 'percentualConclusao', 'solucao', 'notaAtendimento'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;

	public function solicitacoesInfraEmAberto()
	{




		if (session()->codOrganizacao == NULL) {
			$codOrganizacao = $this->db->query('select codOrganizacao from sis_organizacoes where matriz=1')->getRow()->codOrganizacao;
		} else {
			$codOrganizacao = session()->codOrganizacao;
		}


		$select = 'select ss.*, cs.*,p.nomeExibicao,d.*, sss.*, ts.*,cp.*, cu.*,resp.nomeExibicao as ResponsavelTecnico
		from sis_solicitacoessuporte ss 
		left join sis_categoriassuporte cs on cs.codCategoriaSuporte=ss.codCategoriaSuporte
		left join sis_pessoas p on p.codPessoa=ss.codSolicitante
		left join sis_pessoas resp on resp.codPessoa=ss.codResponsavel
		left join sis_departamentos d on d.codDepartamento=ss.codDepartamentoSolicitante
		left join sis_statussuporte sss on sss.codStatusSuporte=ss.codStatusSolicitacao
		left join sis_tiposolicitacao ts on ts.codTipoSolicitacao=ss.codTipoSolicitacao
		left join sis_classificacaoprioridade cp on cp.codPrioridade=ss.codPrioridade
		left join sis_classificacaourgencia cu on cu.codUrgencia=ss.codUrgencia
		where ss.codOrganizacao = ' . $codOrganizacao . ' and ss.codCategoriaSuporte in(1,5,6) and ss.codStatusSolicitacao<>5';
		$query = $this->db->query($select);
		return $query->getResult();
	}

	public function maioresSolicitantes()
	{
		if (session()->codOrganizacao == NULL) {
			$codOrganizacao = $this->db->query('select codOrganizacao from sis_organizacoes where matriz=1')->getRow()->codOrganizacao;
		} else {
			$codOrganizacao = session()->codOrganizacao;
		}


		$select = 'select count(codSolicitacao) as totalAtendimentos, descricaoDepartamento as departamento
		from sis_solicitacoessuporte ss 
		left join sis_departamentos d on d.codDepartamento=ss.codDepartamentoSolicitante
		where ss.dataCriacao >= DATE(NOW()) - INTERVAL 90 DAY
        group by abreviacaoDepartamento
        order by count(codSolicitacao) desc
        limit 4';
		$query = $this->db->query($select);
		return $query->getResult();
	}

	public function solicitacoesSemana()
	{
		if (session()->codOrganizacao == NULL) {
			$codOrganizacao = $this->db->query('select codOrganizacao from sis_organizacoes where matriz=1')->getRow()->codOrganizacao;
		} else {
			$codOrganizacao = session()->codOrganizacao;
		}


		$select = 'select ss.codSolicitacao,DATE_FORMAT(ss.dataCriacao, "%Y-%m-%d") as dataCriacao,DATE_FORMAT(ss.dataCriacao, "%w") as diaSemana, DATE_FORMAT(ss.dataEncerramento, "%Y-%m-%d") as dataEncerramento , ss.codStatusSolicitacao
		from sis_solicitacoessuporte ss 
		where ss.codOrganizacao = ' . $codOrganizacao . ' and YEARWEEK(ss.dataCriacao, 1) = YEARWEEK(CURDATE(), 1)';
		$query = $this->db->query($select);
		return $query->getResult();
	}

	public function solicitacoesAbertasPorTecnico()
	{
		if (session()->codOrganizacao == NULL) {
			$codOrganizacao = $this->db->query('select codOrganizacao from sis_organizacoes where matriz=1')->getRow()->codOrganizacao;
		} else {
			$codOrganizacao = session()->codOrganizacao;
		}


		$select = 'select count(ss.codSolicitacao) as total, resp.nomeExibicao, resp.fotoPerfil
		from sis_solicitacoessuporte ss 
		join sis_pessoas resp on resp.codPessoa=ss.codResponsavel
		where ss.codResponsavel not in (1894,1173) and ss.codOrganizacao = ' . $codOrganizacao . ' and ss.codStatusSolicitacao <> 5 
		group by resp.nomeExibicao,resp.fotoPerfil
		order by resp.codCargo';
		$query = $this->db->query($select);
		return $query->getResult();
	}
	public function solicitacoesSistemasEmAberto()
	{
		if (session()->codOrganizacao == NULL) {
			$codOrganizacao = $this->db->query('select codOrganizacao from sis_organizacoes where matriz=1')->getRow()->codOrganizacao;
		} else {
			$codOrganizacao = session()->codOrganizacao;
		}



		$select = 'select ss.*, cs.*,p.nomeExibicao,p.fotoPerfil,d.*, sss.*, ts.*,cp.*, cu.*,resp.nomeExibicao as ResponsavelTecnico
		from sis_solicitacoessuporte ss 
		left join sis_categoriassuporte cs on cs.codCategoriaSuporte=ss.codCategoriaSuporte
		left join sis_pessoas p on p.codPessoa=ss.codSolicitante
		left join sis_pessoas resp on resp.codPessoa=ss.codResponsavel
		left join sis_departamentos d on d.codDepartamento=ss.codDepartamentoSolicitante
		left join sis_statussuporte sss on sss.codStatusSuporte=ss.codStatusSolicitacao
		left join sis_tiposolicitacao ts on ts.codTipoSolicitacao=ss.codTipoSolicitacao
		left join sis_classificacaoprioridade cp on cp.codPrioridade=ss.codPrioridade
		left join sis_classificacaourgencia cu on cu.codUrgencia=ss.codUrgencia
		where ss.codOrganizacao = ' . $codOrganizacao . ' and ss.codCategoriaSuporte in(2,3) and ss.codStatusSolicitacao<>5';
		$query = $this->db->query($select);
		return $query->getResult();
	}

	public function solicitacoesTelefoniaEmAberto()
	{
		if (session()->codOrganizacao == NULL) {
			$codOrganizacao = $this->db->query('select codOrganizacao from sis_organizacoes where matriz=1')->getRow()->codOrganizacao;
		} else {
			$codOrganizacao = session()->codOrganizacao;
		}



		$select = 'select ss.*, cs.*,p.nomeExibicao,d.*, sss.*, ts.*,cp.*, cu.*,resp.nomeExibicao as ResponsavelTecnico
		from sis_solicitacoessuporte ss 
		left join sis_categoriassuporte cs on cs.codCategoriaSuporte=ss.codCategoriaSuporte
		left join sis_pessoas p on p.codPessoa=ss.codSolicitante
		left join sis_pessoas resp on resp.codPessoa=ss.codResponsavel
		left join sis_departamentos d on d.codDepartamento=ss.codDepartamentoSolicitante
		left join sis_statussuporte sss on sss.codStatusSuporte=ss.codStatusSolicitacao
		left join sis_tiposolicitacao ts on ts.codTipoSolicitacao=ss.codTipoSolicitacao
		left join sis_classificacaoprioridade cp on cp.codPrioridade=ss.codPrioridade
		left join sis_classificacaourgencia cu on cu.codUrgencia=ss.codUrgencia
		where ss.codOrganizacao = ' . $codOrganizacao . ' and ss.codCategoriaSuporte in(4) and ss.codStatusSolicitacao<>5';
		$query = $this->db->query($select);
		return $query->getResult();
	}

	public function solicitacoesAtendidasPorTecnico()
	{

		if (session()->codOrganizacao == NULL) {
			$codOrganizacao = $this->db->query('select codOrganizacao from sis_organizacoes where matriz=1')->getRow()->codOrganizacao;
		} else {
			$codOrganizacao = session()->codOrganizacao;
		}

		/*
		$select = 'select p.nomeExibicao as tecnicoReponsavel, p.fotoPerfil, count(codSolicitacao) as totalAtendimentos, sum(notaAtendimento) as pontosAvaliacoes
		from sis_solicitacoessuporte ss 
		join sis_pessoas p on p.codPessoa=ss.codResponsavel
		where ss.codOrganizacao = ' . $codOrganizacao . ' and ss.dataEncerramento >= DATE(NOW()) - INTERVAL 10 DAY and ss.codStatusSolicitacao=5
		group by p.nomeExibicao
		order by p.codCargo asc';

*/

		$select = '	
		
select p.nomeExibicao as tecnicoReponsavel, p.fotoPerfil, round(count(codSolicitacao)*40/100) as totalAtendimentos,  sum(notaAtendimento) as pontosAvaliacoes,horasTrabahadas.tempoEmAtendimento,
	sum(CASE 
    WHEN notaAtendimento <=2 
    THEN 1
    ELSE 0 
END) AS caveira
from sis_solicitacoessuporte ss 
join sis_pessoas p on p.codPessoa=ss.codResponsavel
left join (		
select p.codPessoa, sum(TIMESTAMPDIFF(HOUR,ss.dataInicio,ss.dataEncerramento)) as tempoEmAtendimento
from sis_solicitacoessuporte ss 
join sis_pessoas p on p.codPessoa=ss.codResponsavel
where WEEK(ss.dataEncerramento) >=WEEK(NOW()) and YEAR(ss.dataEncerramento) >=YEAR(NOW())  and WEEKDAY(ss.dataEncerramento) not in(6)
and ss.codStatusSolicitacao=5
and ss.dataEncerramento is not null and ss.dataInicio is not null and DATE_FORMAT(ss.dataInicio, "%Y-%m-%d")= DATE_FORMAT(ss.dataEncerramento, "%Y-%m-%d")
group by p.codPessoa
having sum(TIMESTAMPDIFF(HOUR,ss.dataInicio,ss.dataEncerramento)) >0
order by p.codCargo asc
) as horasTrabahadas on horasTrabahadas.codPessoa = p.codPessoa
where ss.codResponsavel not in (1894,1173) and WEEK(ss.dataEncerramento)+14 >=WEEK(NOW()) and YEAR(ss.dataEncerramento) >=YEAR(NOW())  and WEEKDAY(ss.dataEncerramento) not in(6)
and ss.codStatusSolicitacao=5
group by p.nomeExibicao,p.fotoPerfil
order by p.codCargo asc';





		$query = $this->db->query($select);
		return $query->getResult();
	}

	public function resolubilidadeOntem()
	{


		$select = 'select *
		from sis_solicitacoessuporte ss 
		where ss.dataCriacao> last_day(curdate() - interval 2 month) + interval 1 day and ss.dataCriacao< last_day(curdate() - interval 1 month)';
		$query = $this->db->query($select);
		return $query->getResult();
	}

	public function resolubilidadeHoje()
	{

		if (session()->codOrganizacao == NULL) {
			$codOrganizacao = $this->db->query('select codOrganizacao from sis_organizacoes where matriz=1')->getRow()->codOrganizacao;
		} else {
			$codOrganizacao = session()->codOrganizacao;
		}


		$select = 'select codSolicitacao, codStatusSolicitacao
		from sis_solicitacoessuporte ss 
		where ss.dataCriacao> last_day(curdate() - interval 1 month) + interval 1 day and ss.dataCriacao< last_day(curdate())';
		$query = $this->db->query($select);
		return $query->getResult();
	}


	function intervaloTempo($dataCriacao =  null, $dataEncerramento = null, $interval = null, $using_timestamps = false)
	{
		/*
    $interval can be:
    yyyy - Number of full years
    q    - Number of full quarters
    m    - Number of full months
    y    - Difference between day numbers
           (eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
    d    - Number of full days
    w    - Number of full weekdays
    ww   - Number of full weeks
    h    - Number of full hours
    n    - Number of full minutes
    s    - Number of full seconds (default)
    */

		if ($dataEncerramento == null) {
			$dataEncerramento = date('Y-m-d H:i');
		}
		if (!$using_timestamps) {
			$dataCriacao = strtotime($dataCriacao, 0);
			$dataEncerramento   = strtotime($dataEncerramento, 0);
		}

		$difference        = $dataEncerramento - $dataCriacao; // Difference in seconds
		$months_difference = 0;

		$valores = array();
		switch ($interval) {

			case "months": // Number of full months
				$months_difference = floor($difference / 2678400);

				while (mktime(date("H", $dataCriacao), date("i", $dataCriacao), date("s", $dataCriacao), date("n", $dataCriacao) + ($months_difference), date("j", $dataEncerramento), date("Y", $dataCriacao)) < $dataEncerramento) {
					$months_difference++;
				}

				$months_difference--;

				$datediff = $months_difference;

				if ($datediff <= 1) {
					array_push($valores, array('titulo' => ' Mês', 'tempo' => $datediff));
				} else {
					array_push($valores, array('titulo' => ' Meses', 'tempo' => $datediff));
				}
				break;

			case 'years': // Difference between day numbers
				$datediff = date("z", $dataEncerramento) - date("z", $dataCriacao);

				if ($datediff <= 1) {
					array_push($valores, array('titulo' => ' Ano', 'tempo' => $datediff));
				} else {
					array_push($valores, array('titulo' => ' Anos', 'tempo' => $datediff));
				}

				break;

			case "days": // Number of full days
				$datediff = floor($difference / 86400);

				if ($datediff <= 1) {
					array_push($valores, array('titulo' => ' Dia', 'tempo' => $datediff));
				} else {
					array_push($valores, array('titulo' => ' Dias', 'tempo' => $datediff));
				}

				break;

			case "weeks": // Number of full weeks
				$datediff = floor($difference / 604800);

				if ($datediff <= 1) {
					array_push($valores, array('titulo' => ' Semana', 'tempo' => $datediff));
				} else {
					array_push($valores, array('titulo' => ' Semanas', 'tempo' => $datediff));
				}


				break;

			case "hours": // Number of full hours
				$datediff = floor($difference / 3600);

				if ($datediff <= 1) {
					array_push($valores, array('titulo' => ' Hora', 'tempo' => $datediff));
				} else {
					array_push($valores, array('titulo' => ' Horas', 'tempo' => $datediff));
				}

				break;

			case "minutes": // Number of full minutes
				$datediff = floor($difference / 60);

				if ($datediff <= 1) {
					array_push($valores, array('titulo' => ' Minuto', 'tempo' => $datediff));
				} else {
					array_push($valores, array('titulo' => ' Minutos', 'tempo' => $datediff));
				}
				break;


			default: // Number of full seconds (default)
				$datediff = $difference;

				if ($datediff <= 1) {
					array_push($valores, array('titulo' => ' Segundo', 'tempo' => $datediff));
				} else {
					array_push($valores, array('titulo' => ' Segundos', 'tempo' => $datediff));
				}

				break;
		}

		return $valores;
	}


	public function pegaTudo()
	{
		if (session()->codOrganizacao == NULL) {
			$codOrganizacao = $this->db->query('select codOrganizacao from sis_organizacoes where matriz=1')->getRow()->codOrganizacao;
		} else {
			$codOrganizacao = session()->codOrganizacao;
		}
		$codPessoa = session()->codPessoa;
		$meuDepartamento = session()->codDepartamento;
		$preferenciasFiltro = $this->db->query('select * from sis_preferenciaspessoas where codPessoa = ' . $codPessoa)->getRow();


		$select = 'select ss.*, cs.*,p.nomeExibicao,d.*, sss.*, ts.*,cp.*, cu.*,resp.nomeExibicao as ResponsavelTecnico
		from sis_solicitacoessuporte ss 
		left join sis_categoriassuporte cs on cs.codCategoriaSuporte=ss.codCategoriaSuporte
		left join sis_pessoas p on p.codPessoa=ss.codSolicitante
		left join sis_pessoas resp on resp.codPessoa=ss.codResponsavel
		left join sis_departamentos d on d.codDepartamento=ss.codDepartamentoSolicitante
		left join sis_statussuporte sss on sss.codStatusSuporte=ss.codStatusSolicitacao
		left join sis_tiposolicitacao ts on ts.codTipoSolicitacao=ss.codTipoSolicitacao
		left join sis_classificacaoprioridade cp on cp.codPrioridade=ss.codPrioridade
		left join sis_classificacaourgencia cu on cu.codUrgencia=ss.codUrgencia
		where ss.codOrganizacao = ' . $codOrganizacao;
		if ($preferenciasFiltro !== NULL) {


			$categoriasSolicitacoes = str_replace("]", "", str_replace("[", "", $preferenciasFiltro->categoriasSolicitacoes));
			if ($categoriasSolicitacoes !== NULL and $categoriasSolicitacoes !== 'null'  and $categoriasSolicitacoes !== '""') {
				$select .= ' and ss.codCategoriaSuporte in (' . $categoriasSolicitacoes . ')';
			}

			$statusSolicitacoes = str_replace("]", "", str_replace("[", "", $preferenciasFiltro->statusSolicitacoes));
			if ($statusSolicitacoes !== NULL and $statusSolicitacoes !== 'null'  and $statusSolicitacoes !== '""') {
				$select .= ' and ss.codStatusSolicitacao in (' . $statusSolicitacoes . ')';
			}


			$departamentos = str_replace("]", "", str_replace("[", "", $preferenciasFiltro->codDepartamento));
			if ($departamentos !== NULL and $departamentos !== 'null'  and $departamentos !== '""') {
				$select .= ' and ss.codDepartamentoSolicitante in (' . $departamentos . ')';
			}

			$pessoas = str_replace("]", "", str_replace("[", "", $preferenciasFiltro->codSolicitante));
			if ($pessoas !== NULL and $pessoas !== 'null'  and $pessoas !== '""') {
				$select .= ' and ss.codSolicitante in (' . $pessoas . ')';
			}

			$tecnicoResponsavel = str_replace("]", "", str_replace("[", "", $preferenciasFiltro->codResponsavel));
			if ($tecnicoResponsavel !== NULL and $tecnicoResponsavel !== 'null'  and $tecnicoResponsavel !== '""') {
				$select .= ' and ss.codResponsavel in (' . $tecnicoResponsavel . ')';
			}

			$periodo = $preferenciasFiltro->periodoSolicitacoes;
			if ($periodo !== NULL and $periodo !== 'null' and $periodo !== '""') {
				if ($periodo == 0) {
					$select .= '';
				} else {
					$select .= ' and ss.dataCriacao >= DATE(NOW()) - INTERVAL ' . $periodo . ' DAY ';
				}
			} else {
				$select .= ' and ss.dataCriacao >= DATE(NOW()) - INTERVAL 4 DAY ';
			}
		} else {
			$select .= ' and (ss.codSolicitante = ' . $codPessoa . ' ';
			$select .= ' or ss.codDepartamentoSolicitante = ' . $meuDepartamento . ') ';
		}

		$query = $this->db->query($select);
		return $query->getResult();
	}

	/*
	public function pegaPorCodigo($codSolicitacao)
    {
        $query = $this->db->query('select * from ' . $this->table. ' where codSolicitacao = "'.$codSolicitacao.'"');
        return $query->getRow();
    }
*/


	public function pegaPorCodigo($codSolicitacao)
	{

		if (session()->codOrganizacao == NULL) {
			$codOrganizacao = $this->db->query('select codOrganizacao from sis_organizacoes where matriz=1')->getRow()->codOrganizacao;
		} else {
			$codOrganizacao = session()->codOrganizacao;
		}
		$query = $this->db->query('select ss.*, cs.*,p.nomeExibicao,d.*, sss.*, ts.*,cp.*, cu.*
		from sis_solicitacoessuporte ss 
		left join sis_categoriassuporte cs on cs.codCategoriaSuporte=ss.codCategoriaSuporte
		left join sis_pessoas p on p.codPessoa=ss.codSolicitante
		left join sis_departamentos d on d.codDepartamento=p.codDepartamento
		left join sis_statussuporte sss on sss.codStatusSuporte=ss.codStatusSolicitacao
		left join sis_tiposolicitacao ts on ts.codTipoSolicitacao=ss.codTipoSolicitacao
		left join sis_classificacaoprioridade cp on cp.codPrioridade=ss.codPrioridade
		left join sis_classificacaourgencia cu on cu.codUrgencia=ss.codUrgencia
		where codSolicitacao = "' . $codSolicitacao . '"
		order by ss.codSolicitacao desc');
		return $query->getRow();
	}

	public function equipesTecnicasPorPessoa($codPessoa)
	{
		if (session()->codOrganizacao == NULL) {
			$codOrganizacao = $this->db->query('select codOrganizacao from sis_organizacoes where matriz=1')->getRow()->codOrganizacao;
		} else {
			$codOrganizacao = session()->codOrganizacao;
		}

		$query = $this->db->query('select distinct abreviacaoDepartamento 
		FROM sis_equipesuporte es 
		join sis_departamentos d on d.codDepartamento=es.codDepartamentoResponsavel
		join sis_pessoas p on p.codDepartamento=es.codDepartamentoResponsavel 
		where es.codOrganizacao = ' . $codOrganizacao . ' and p.codPessoa = "' . $codPessoa . '"');
		return $query->getResult();
	}


	public function diasAcoes($codSolicitacao)
	{
		if (session()->codOrganizacao == NULL) {
			$codOrganizacao = $this->db->query('select codOrganizacao from sis_organizacoes where matriz=1')->getRow()->codOrganizacao;
		} else {
			$codOrganizacao = session()->codOrganizacao;
		}

		$query = $this->db->query('select distinct DATE_FORMAT(dataInicio, "%Y-%m-%d")  
		from sis_acaosuporte where codSolicitacao = ' . $codSolicitacao);
		return $query->getResult();
	}


	public function getAcoes($codSolicitacao)
	{
		if (session()->codOrganizacao == NULL) {
			$codOrganizacao = $this->db->query('select codOrganizacao from sis_organizacoes where matriz=1')->getRow()->codOrganizacao;
		} else {
			$codOrganizacao = session()->codOrganizacao;
		}
		$query = $this->db->query('select ass.codAcaoSuporte, ss.codSolicitante, ass.codPessoa as codPessoaMensageiro, p.nomeExibicao as mensageiro, p.fotoPerfil, ss.dataInicio, ass.dataInicio as dataInicioAcao, ass.descricaoAcao from sis_acaosuporte as ass 
		left join sis_solicitacoessuporte ss on ss.codSolicitacao=ass.codSolicitacao
		left join sis_pessoas p on p.codPessoa=ass.codPessoa 
		where ss.codOrganizacao = ' . $codOrganizacao . '
		and ass.codSolicitacao = ' . $codSolicitacao . ' order by ass.codAcaoSuporte asc');
		return $query->getResult();
	}

	public function ultimaMensagem($codSolicitacao)
	{
		if (session()->codOrganizacao == NULL) {
			$codOrganizacao = $this->db->query('select codOrganizacao from sis_organizacoes where matriz=1')->getRow()->codOrganizacao;
		} else {
			$codOrganizacao = session()->codOrganizacao;
		}
		$query = $this->db->query('select max(ass.codAcaoSuporte) as ultimaMensagem
		from sis_acaosuporte as ass 
		left join sis_solicitacoessuporte ss on ss.codSolicitacao=ass.codSolicitacao
		where ss.codOrganizacao = ' . $codOrganizacao . '
		and ass.codSolicitacao = ' . $codSolicitacao);
		return $query->getRow();
	}


	public function ultimaAcao($codSolicitacao)
	{
		if (session()->codOrganizacao == NULL) {
			$codOrganizacao = $this->db->query('select codOrganizacao from sis_organizacoes where matriz=1')->getRow()->codOrganizacao;
		} else {
			$codOrganizacao = session()->codOrganizacao;
		}

		$query = $this->db->query('select p.nomeExibicao, ass.dataInicio, ass.descricaoAcao 
		from sis_acaosuporte as ass left join sis_pessoas p on p.codPessoa=ass.codPessoa 
		where ass.codSolicitacao = ' . $codSolicitacao . ' order by ass.codAcaoSuporte desc limit 1');
		return $query->getRow();
	}
}
