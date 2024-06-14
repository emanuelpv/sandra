<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class FaturamentoTaxasServicosModel extends Model
{

    protected $table = 'fat_faturamentotaxasservicos';
    protected $primaryKey = 'codFaturamentoTaxasServico';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['codAuditor','codFatura', 'dataInicio', 'dataEncerramento', 'referencia', 'codAtendimento', 'codTaxaServico', 'quantidade', 'valor', 'codStatus', 'codAutor', 'codLocalAtendimento', 'dataCriacao', 'dataAtualizacao'];
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
        $query = $this->db->query('select * from fat_faturamentotaxasservicos');
        return $query->getResult();
    }
    public function taxasServicosFaturados($codFatura)
    {

        $codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select f.codStatusFatura,sts.*, fts.*,(fts.quantidade * fts.valor ) as subTotal,ts.descricao descricaoTaxaServico,ts.*,d.descricaoDepartamento,la.descricaoLocalAtendimento,fts.codAutor as autorTaxaServico,pe.nomeExibicao as nomeAuditor
        from fat_faturamentotaxasservicos as fts     
        left join fat_faturamento f on f.codFatura=fts.codFatura 
        left join amb_taxasservicos ts on ts.codTaxaServico = fts.codTaxaServico
		left join sis_pessoas pe on pe.codPessoa=fts.codAuditor
		left join sis_statustaxasservicos sts on sts.codStatusTaxaServico=fts.codStatus
		left join amb_atendimentoslocais la on la.codLocalAtendimento=fts.codLocalAtendimento
		left join sis_departamentos d on d.codDepartamento=la.codDepartamento
        where fts.codFatura = ' . $codFatura . ' order by fts.codFaturamentoTaxasServico desc
        ');
        return $query->getResult();
    }

    public function pegaPorCodigo($codFaturamentoTaxasServico)
    {
        $query = $this->db->query('select * from ' . $this->table . ' where codFaturamentoTaxasServico = "' . $codFaturamentoTaxasServico . '"');
        return $query->getRow();
    }


    public function verificaExistencia($codAtendimento, $dataInicio, $dataEncerramento, $codLocalAtendimento)
    {
        $query = $this->db->query('select * from ' . $this->table . ' where codAtendimento = ' . $codAtendimento . ' and dataInicio = "' . $dataInicio . '" and dataEncerramento = "' . $dataEncerramento . '" and codLocalAtendimento=' . $codLocalAtendimento . ' order by dataEncerramento	desc limit 1');
        return $query->getRow();
    }


    public function ultimoLancamento($codAtendimento)
    {
        $query = $this->db->query('select * from ' . $this->table . ' where codAtendimento = "' . $codAtendimento . '" order by dataEncerramento	desc limit 1');
        return $query->getRow();
    }


    public function removeFatura($codFatura)
    {
        $query = $this->db->query('delete from ' . $this->table . ' where codFatura = ' . $codFatura);
        return true;
    }
}
