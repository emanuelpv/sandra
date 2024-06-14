<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;
use CodeIgniter\Model;

class ParteRequisitoriaModel extends Model {
    
	protected $table = 'ges_parterequisitorias';
	protected $primaryKey = 'codRequisicao';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['numeroRequisicao','ano','descricao', 'codTipoRequisicao', 'codClasseRequisicao', 'dataAtualizacao','dataRequisicao', 'valorTotal', 'codDepartamento','matSau', 'carDisp'];
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
        $query = $this->db->query('select * 
		from ges_parterequisitorias r
		left join sis_departamentos d on d.codDepartamento=r.codDepartamento
		left join ges_classerequisicao c on c.codClasseRequisicao=r.codClasseRequisicao
		left join ges_tipoparterequisitoria t on t.codTipoRequisicao=r.codTipoRequisicao
		left join ges_statusparterequisitoria s on s.codStatusRequisicao=r.codStatusRequisicao
		order by codRequisicao desc ');
        return $query->getResult();
    }
	public function listaDropDownTipoparterequisitoria()
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select codTipoRequisicao as id,descricaoTipoRequisicao as text from ges_tipoparterequisitoria where descricaoTipoRequisicao is not null');
        return $query->getResult();
    }
	public function listaDropDownClasseparterequisitoria()
    {
		
		$codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select codClasseRequisicao as id,descricaoClasseRequisicao as text from ges_classerequisicao where descricaoClasseRequisicao is not null
		');
        return $query->getResult();
    }


	public function pegaPorCodigo($codRequisicao)
    {
        $query = $this->db->query('select r.*,DATE_FORMAT(r.dataRequisicao, "%Y-%m-%d") as dataRequisicaoV from ges_parterequisitorias r where r.codRequisicao = "'.$codRequisicao.'"');
        return $query->getRow();
    }

	public function ultimoLancamentoAnoCorrente($codDepartamento)
    {
        $query = $this->db->query('select * from  ges_parterequisitorias where codDepartamento = "'.$codDepartamento.'" and ano=YEAR(NOW()) order by codRequisicao desc limit 1');
        return $query->getRow();
    }




}