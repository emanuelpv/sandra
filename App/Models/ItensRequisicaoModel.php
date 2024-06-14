<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class ItensRequisicaoModel extends Model
{

    protected $table = 'ges_itensrequisicao';
    protected $primaryKey = 'codRequisicaoItem';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['codCat','prioridade','metodoCalculo', 'codRequisicao', 'tipoMaterial', 'nrRef', 'descricao', 'unidade', 'qtde_sol', 'valorUnit', 'valorTotal', 'cod_siasg', 'tipo_ref_preco', 'obs', 'dataCriacao', 'dataAtualizacao', 'codAutorUltAlteracao', 'codAutor'];
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = true;


    public function pegaTudo()
    {

        $codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select * from ges_itensrequisicao');
        return $query->getResult();
    }

    public function pegaItensRequisicao($codRequisicao)
    {

        $codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select ir.*,cu.descricaoUnidade, tm.descricaoTipoMaterial,r.codTipoRequisicao from
        ges_itensrequisicao ir
        left join ges_requisicao r on ir.codRequisicao=r.codRequisicao
        left join sis_unidades cu on cu.codUnidade=ir.unidade
        left join ges_tipomaterial tm on tm.codTipoMaterial=ir.tipoMaterial
        where ir.codRequisicao="' . $codRequisicao.'" order by ir.nrRef asc, ir.codRequisicaoItem asc');
        return $query->getResult();
    }


    public function pegaPorCodigo($codRequisicaoItem)
    {
        $query = $this->db->query('select * from ges_itensrequisicao where codRequisicaoItem = "' . $codRequisicaoItem . '"');
        return $query->getRow();
    }


    public function listaDropDownUnidades()
    {

        $codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select codUnidade as id,descricaoUnidade as text from sis_unidades where descricaoUnidade is not null');
        return $query->getResult();
    }

    public function listaDropDownTipoMaterial()
    {

        $codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select codTipoMaterial as id,descricaoTipoMaterial as text from ges_tipomaterial where descricaoTipoMaterial is not null');
        return $query->getResult();
    }


    public function listaDropDownTipoOrcamento()
    {

        $codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select codTipoOrcamento as id,descricaoTipoOrcamento as text from ges_tipoorcamento  where descricaoTipoOrcamento is not null');
        return $query->getResult();
    }



    public function removeOrcamentos($codRequisicaoItem)
    {
        if ($codRequisicaoItem !== NULL and $codRequisicaoItem !== "" and $codRequisicaoItem !== " ") {

            $this->db->query('delete from ges_orcamentos where codRequisicaoItem = "' . $codRequisicaoItem . '"');

        }
        return true;
    }





}