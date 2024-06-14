<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class QuestionariosModel extends Model
{

	protected $table = 'tes_questionarios';
	protected $primaryKey = 'codQuestionario';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['titulo', 'codTipoQuestionario', 'dataInicio', 'dataEncerramento', 'ativo', 'objetivo', 'instrucoes', 'termoAceite', 'aplicadoUsuarios', 'aplicadoFuncionarios'];
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
		$query = $this->db->query('select q.*,tq.descricao as descricaoTipoQuestionario 
		from tes_questionarios q
		left join tes_tipoquestionario tq on tq.codTipoQuestionario=q.codTipoQuestionario');
		return $query->getResult();
	}

	public function termoAceite($codQuestionario)
	{
		$query = $this->db->query('select *
			from tes_questionarios where codQuestionario=' . $codQuestionario);
		return $query->getRow();
	}

	public function perguntasQuestionario($codQuestionario)
	{
		$query = $this->db->query('select *
			from tes_perguntas p
			join tes_perguntasquestionario pq on pq.codPergunta=p.codPergunta where pq.codQuestionario=' . $codQuestionario);
		return $query->getResult();
	}

	public function dadosPessoa($codPessoa)
	{
		$query = $this->db->query('select p.nomeCompleto,p.nomeExibicao,TIMESTAMPDIFF(YEAR, p.dataNascimento,CURDATE()) as idade
			from sis_pessoas p where p.codPessoa="' . $codPessoa . '"');
		return $query->getRow();
	}
	public function dadosPaciente($codPaciente)
	{
		$query = $this->db->query('select p.nomeCompleto,p.nomeExibicao,  TIMESTAMPDIFF(YEAR, p.dataNascimento,CURDATE()) as idade
			from sis_pacientes p where p.codPaciente="' . $codPaciente . '"');
		return $query->getRow();
	}



	public function verificaExistenciaPesquisa($modulo = NULL, $codQuestionario = NULL)
	{

		$filtro = "";
		if ($codQuestionario !== NULL and $codQuestionario !== "" and $codQuestionario !== " ") {
			//PARA TESTE DEV
			$query = $this->db->query('select count(*) qtdPerguntas,q.instrucoes,q.termoAceite,q.codQuestionario ,q.objetivo,q.titulo, NULL as codDadosDemograficos 
			from tes_questionarios q 
			left join tes_perguntasquestionario pq on pq.codQuestionario=q.codQuestionario
			where q.codQuestionario="' . $codQuestionario . '"
			group by q.codQuestionario, q.objetivo');
			return $query->getResult();
		}

		if (session()->codPessoa !== NULL) {
			//PARA COLABORADORES

			$query = $this->db->query('select count(*) qtdPerguntas,q.instrucoes,q.termoAceite,q.codQuestionario ,q.objetivo,q.titulo,dd.codDadosDemograficos 
			from tes_questionarios q 
			left join tes_perguntasquestionario pq on pq.codQuestionario=q.codQuestionario 
			left join tes_perguntas p on p.codPergunta=pq.codPergunta 
			left join tes_dadosdemograficos dd on dd.codQuestionario=q.codQuestionario and dd.codPessoa="' . session()->codPessoa . '" and dd.modulo="' . $modulo . '"
			where ativo = 1 and aplicadoFuncionarios=1 and 
			DATE_FORMAT(q.dataInicio, "%Y-%m-%d") <= DATE_FORMAT(NOW(), "%Y-%m-%d") and 
			DATE_FORMAT(q.dataEncerramento, "%Y-%m-%d") >= DATE_FORMAT(NOW(), "%Y-%m-%d") 
			group by q.codQuestionario, q.objetivo');

			return $query->getResult();
		}

		if (session()->codPaciente !== NULL) {
			//PARA PACIENTES
			$query = $this->db->query('select count(*) qtdPerguntas,q.instrucoes,q.termoAceite,q.codQuestionario ,q.objetivo,q.titulo,dd.codDadosDemograficos
			from tes_questionarios q 
			left join tes_perguntasquestionario pq on pq.codQuestionario=q.codQuestionario 
			left join tes_perguntas p on p.codPergunta=pq.codPergunta
			left join tes_dadosdemograficos dd on dd.codQuestionario=q.codQuestionario and dd.codPaciente="' . session()->codPaciente . '" and dd.modulo="' . $modulo . '"
			where ativo = 1 and aplicadoUsuarios=1 and DATE_FORMAT(q.dataInicio, "%Y-%m-%d") <= DATE_FORMAT(NOW(), "%Y-%m-%d") and DATE_FORMAT(q.dataEncerramento, "%Y-%m-%d") >= DATE_FORMAT(NOW(), "%Y-%m-%d") group by q.codQuestionario , q.objetivo');
			return $query->getResult();
		}


		return $query->getResult();
	}

	public function verificaExistenciaRespostas($codQuestionario)
	{

		$filtro = "";

		if (session()->codPessoa !== NULL) {

			$filtro = ' and dq.codPessoa=' . session()->codPessoa;
		}

		if (session()->codPaciente !== NULL) {
			$filtro = ' and dq.codPaciente=' . session()->codPaciente;
		}

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from tes_questionarios q
		join tes_dadosdemograficos dq on q.codQuestionario=dq.codQuestionario 
		where q.codQuestionario=' . $codQuestionario . $filtro . ' limit 1');

		return $query->getResult();
	}




	public function pegaPerguntas($codTipoQuestionario)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select * from tes_perguntas where codTipoQuestionario=' . $codTipoQuestionario);
		return $query->getResult();
	}


	public function pegaDadosDemograficos($codQuestionario)
	{

		$codOrganizacao = session()->codOrganizacao;
		$query = $this->db->query('select codDadosDemograficos, nomeExibicao,tipoQuestionario,setor,modulo,tipoUsuario,P1,P2,P3,P4,P5,P6,P7,P8,P9,P10,Pontos,
		CASE
		WHEN Pontos > 80 THEN "Excelente" 
		WHEN Pontos BETWEEN 68 and 80.3 THEN "Bom"
		WHEN Pontos BETWEEN  51 and 68 THEN "Pobre"
		WHEN Pontos = 68 THEN "Ok"
		WHEN Pontos < 51  THEN "Miserável"
		END escala
		from (select x.codDadosDemograficos, x.nomeExibicao,x.tipoQuestionario,x.setor,x.modulo,x.tipoUsuario,x.P1,x.P2,x.P3,x.P4,x.P5,x.P6,x.P7,x.P8,x.P9,x.P10,(((P1+P3+P5+P7+P9)-5)+(25-(P2+P4+P6+P8+P10)))*2.5 as pontos
				from
				(select dd.codDadosDemograficos, dd.nomeExibicao,tq.descricao as tipoQuestionario,dd.setor,dd.modulo,dd.tipoUsuario,
						MAX(CASE WHEN p.ordem = 1 THEN r.resposta END) "P1",
						MAX(CASE WHEN p.ordem = 2 THEN r.resposta END) "P2",
						MAX(CASE WHEN p.ordem = 3 THEN r.resposta END) "P3",
						MAX(CASE WHEN p.ordem = 4 THEN r.resposta END) "P4",
						MAX(CASE WHEN p.ordem = 5 THEN r.resposta END) "P5",
						MAX(CASE WHEN p.ordem = 6 THEN r.resposta END) "P6",
						MAX(CASE WHEN p.ordem = 7 THEN r.resposta END) "P7",
						MAX(CASE WHEN p.ordem = 8 THEN r.resposta END) "P8",
						MAX(CASE WHEN p.ordem = 9 THEN r.resposta END) "P9",
						MAX(CASE WHEN p.ordem = 10 THEN r.resposta END) "P10"
								from tes_dadosdemograficos dd
								join tes_questionarios q on q.codQuestionario=dd.codQuestionario
								join tes_respostas r on r.codDadosDemograficos=dd.codDadosDemograficos
								join tes_perguntas p on p.codPergunta=r.codPergunta
								join tes_tipoquestionario tq on tq.codTipoQuestionario=q.codTipoQuestionario
								join tes_categoriaperguntas cp on cp.codCategoria=p.codCategoria
								where q.codQuestionario=' . $codQuestionario . '
								group by dd.codDadosDemograficos, dd.nomeExibicao,tq.descricao,dd.setor,dd.modulo,dd.tipoUsuario
								order by dd.dataResposta)x)y');
		return $query->getResult();
	}



	public function pegaPorCodigo($codQuestionario)
	{
		$query = $this->db->query('select * from ' . $this->table . ' where codQuestionario = "' . $codQuestionario . '"');
		return $query->getRow();
	}
}
