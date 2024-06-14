<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Models;

use CodeIgniter\Model;

class PrescricaoMedicamentosModel extends Model
{

    protected $table = 'amb_atendimentosprescricoesmedicamentos';
    protected $primaryKey = 'codPrescricaoMedicamento';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['migrado', 'codPrescricaoKit', 'codAtendimentoPrescricao', 'codAutorLiberacao', 'codAutorExecucao', 'totalExecutado', 'totalLiberado', 'codMedicamento', 'qtde', 'und', 'via', 'freq', 'per', 'dias', 'horaIni', 'agora', 'risco', 'obs', 'apraza', 'total', 'stat', 'codAutor', 'dataCriacao', 'dataAtualizacao'];
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
        $query = $this->db->query('select * from amb_atendimentosprescricoesmedicamentos');
        return $query->getResult();
    }
    public function listaDropDownUnidades()
    {

        $codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select codUnidade as id, descricaoUnidade as text from sis_unidades');
        return $query->getResult();
    }

    public function listaDropDownVias()
    {

        $codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select codVia as id, descricaoVia as text from sis_vias');
        return $query->getResult();
    }
    public function listaDropDownPeriodo()
    {

        $codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select codPeriodo as id, descricaoPeriodo as text from sis_periodoprescricao');
        return $query->getResult();
    }
    public function listaDropDownAgora()
    {

        $codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select codAplicarAgora as id, descricaoAplicarAgora as text from sis_statusaplicaragora');
        return $query->getResult();
    }
    public function listaDropDownRiscoPrescricao()
    {

        $codOrganizacao = session()->codOrganizacao;
        $query = $this->db->query('select codRiscoPrescricao as id, descricaoRiscoPrescricao as text from sis_riscoprescricao');
        return $query->getResult();
    }

    public function pegaPorCodigo($codPrescricaoMedicamento)
    {
        $query = $this->db->query('select * from ' . $this->table . ' where codPrescricaoMedicamento = "' . $codPrescricaoMedicamento . '"');
        return $query->getRow();
    }

    public function buscaDetalheItem($codPrescricaoMedicamento)
    {
        $query = $this->db->query('select iff.descricaoItem, u.descricaoUnidade, v.descricaoVia, pp.descricaoPeriodo,apm.per,apm.qtde, apm.und, apm.via, apm.freq, apm.dias from 
        amb_atendimentosprescricoesmedicamentos apm
        left join sis_itensfarmacia iff on iff.codItem=apm.codMedicamento
		left join sis_periodoprescricao pp on pp.codPeriodo=apm.per
		left join sis_unidades u on u.codUnidade=apm.und
		left join sis_vias v on v.codVia=apm.via
        where apm.codPrescricaoMedicamento = "' . $codPrescricaoMedicamento . '"');
        return $query->getRow();
    }

    public function pegaPorcodAtendimentoPrescricao($codAtendimentoPrescricao)
    {
        $query = $this->db->query('select * from amb_atendimentosprescricoesmedicamentos where codAtendimentoPrescricao = "' . $codAtendimentoPrescricao . '"');
        return $query->getResult();
    }
    public function pegaPorcodAtendimentoPrescricaoClone($codAtendimentoPrescricao)
    {
        $query = $this->db->query('select apm.* from 
        amb_atendimentosprescricoesmedicamentos apm
        left join amb_atendimentossuspensaomedicamentos asm on asm.codPrescricaoMedicamento=apm.codPrescricaoMedicamento
        where apm.codAtendimentoPrescricao = "' . $codAtendimentoPrescricao . '" and asm.codSuspensaoMedicamento is null');
        return $query->getResult();
    }



    public function verificaExistenciaMedicamento($codAtendimentoPrescricao, $codMedicamento)
    {
        $query = $this->db->query('select * from amb_atendimentosprescricoesmedicamentos where codAtendimentoPrescricao = "' . $codAtendimentoPrescricao . '" and codMedicamento="' . $codMedicamento . '"');
        return $query->getRow();
    }

    public function pegaAlergiasPaciente($codPaciente)
    {
        $query = $this->db->query('select * from 
        sis_pacientesalergias
		where codPaciente = "' . $codPaciente . '"');
        return $query->getResult();
    }

    public function checkinMedicamento($codPrescricaoMedicamento = null, $codMedicamento = null, $observacoes = null, $dataHoraChecagem)
    {
        if ($observacoes == NULL) {
            $queryInsert = $this->db->query('insert into enf_checagemmedicamentos (codPrescricaoMedicamento, codMedicamento,codAutor, dataCriacao, observacao, codStatus) values ( ' . $codPrescricaoMedicamento . ', ' . $codMedicamento . ', ' . session()->codPessoa . ', "' . $dataHoraChecagem . '", NULL, 1)');
        } else {
            $queryInsert = $this->db->query('insert into enf_checagemmedicamentos (codPrescricaoMedicamento, codMedicamento,codAutor, dataCriacao, observacao, codStatus) values ( ' . $codPrescricaoMedicamento . ', ' . $codMedicamento . ', ' . session()->codPessoa . ', "' . $dataHoraChecagem . '", "' . $observacoes . '", 1)');
        }

        $queryUpdate = $this->db->query('update amb_atendimentosprescricoesmedicamentos set totalExecutado = IFNULL(totalExecutado, 0)  + 1 where codPrescricaoMedicamento=' . $codPrescricaoMedicamento . ' and codMedicamento=' . $codMedicamento);

        if ($queryInsert and $queryUpdate) {
            return true;
        } else {
            return false;
        }
    }

    public function checagemMedicamento($codPrescricaoMedicamento = null, $codMedicamento = null)
    {
        $query = $this->db->query('select c.*,p.nomeExibicao from 
        enf_checagemmedicamentos c
        left join sis_pessoas p on c.codAutor=p.codPessoa
		where c.codPrescricaoMedicamento = "' . $codPrescricaoMedicamento . '" and c.codMedicamento = "' . $codMedicamento . '"
        order by c.dataCriacao asc');
        return $query->getResult();
    }


    public function verificaGuiaAntimicrobiano($codAtendimento, $codItem, $dataInicioPrescricao)
    {
        $query = $this->db->query('select cam.*,pe.nomeExibicao,pex.nomeExibicao as suspensoPor
        from amb_controleantimicrobiano cam
		left join sis_pessoas pe on pe.codPessoa=cam.codAutor
		left join sis_pessoas pex on pex.codPessoa=cam.suspensoPor
		where cam.codAtendimento="' . $codAtendimento . '" and cam.codItem = "' . $codItem . '"  and cam.dataInicio <="' . $dataInicioPrescricao . '" and cam.dataEncerramento >="' . $dataInicioPrescricao . '"
        order by cam.codControleAntimicrobiano desc');
        return $query->getRow();
    }

    public function pegaPorCodigoAtendimentoPrescricao($codAtendimentoPrescricao)
    {
        $query = $this->db->query('select pc.codPrescricaoComplementar,asm.dataCriacao as dataSuspensao,asm.motivo, asm.codSuspensaoMedicamento,ppe.nomeExibicao as autorSuspensao,pex.nomeExibicao as autorComplemento,apm.dataCriacao as dataCriacaoComplemento,ap.dataInicio as dataInicioPrescricao, apm.dataCriacao as dataCriacaoPrescricao,a.codAtendimento,iff.antibiotico, ap.codStatus as codStatusPrescricao, apm.dataCriacao as dataCriacaoPrescricaoMedicamento, apm.*, pa.codPaciente,iff.*, u.*, v.*, pp.*,saa.*,rp.*,pe.nomeExibicao, sp.*
        from amb_atendimentosprescricoesmedicamentos apm
        left join amb_atendimentossuspensaomedicamentos asm on asm.codPrescricaoMedicamento=apm.codPrescricaoMedicamento
        left join amb_prescricaocomplementar pc on pc.codPrescricaoMedicamento=apm.codPrescricaoMedicamento
        left join sis_pessoas ppe on ppe.codPessoa=asm.codAutor
		left join sis_pessoas pex on pex.codPessoa=apm.codAutor
		left join amb_atendimentosprescricoes ap  on ap.codAtendimentoPrescricao=apm.codAtendimentoPrescricao
		left join amb_atendimentos a on a.codAtendimento=ap.codAtendimento
		left join sis_itensfarmacia iff on iff.codItem=apm.codMedicamento
		left join sis_unidades u on u.codUnidade=apm.und
		left join sis_vias v on v.codVia=apm.via
		left join sis_periodoprescricao pp on pp.codPeriodo=apm.per
		left join sis_statusaplicaragora saa on saa.codAplicarAgora=apm.agora
		left join sis_riscoprescricao rp on rp.codRiscoPrescricao=apm.risco
		left join sis_pessoas pe on pe.codPessoa=apm.codAutor
		left join sis_pacientes pa on pa.codPaciente=a.codPaciente        
		left join sis_statusprescricao sp on sp.codStatusPrescricao=apm.stat
		where apm.codAtendimentoPrescricao = "' . $codAtendimentoPrescricao . '" order by apm.codPrescricaoMedicamento desc');
        return $query->getResult();
    }

    public function pegaPorCodigoAtendimentoPrescricaoDietas($codAtendimentoPrescricao)
    {
        $query = $this->db->query('select ap.dataInicio as dataInicioPrescricao,ppe.nomeExibicao as autorSuspensao,asm.dataCriacao as dataSuspensao,asm.motivo, asm.codSuspensaoMedicamento,pex.nomeExibicao as autorComplemento,apm.dataCriacao as dataCriacaoComplemento,pc.codPrescricaoComplementar,apm.*, iff.*, u.*, v.*, pp.*,saa.*,rp.*,p.nomeExibicao, sp.*
        from amb_atendimentosprescricoesmedicamentos apm
		left join amb_atendimentosprescricoes ap  on ap.codAtendimentoPrescricao=apm.codAtendimentoPrescricao
        left join amb_prescricaocomplementar pc on pc.codPrescricaoMedicamento=apm.codPrescricaoMedicamento
        left join amb_atendimentossuspensaomedicamentos asm on asm.codPrescricaoMedicamento=apm.codPrescricaoMedicamento
		left join sis_itensfarmacia  iff on iff.codItem=apm.codMedicamento
		left join sis_unidades u on u.codUnidade=apm.und
		left join sis_vias v on v.codVia=apm.via
		left join sis_periodoprescricao pp on pp.codPeriodo=apm.per
		left join sis_statusaplicaragora saa on saa.codAplicarAgora=apm.agora
		left join sis_riscoprescricao rp on rp.codRiscoPrescricao=apm.risco
		left join sis_pessoas p on p.codPessoa=apm.codAutor
		left join sis_pessoas pex on pex.codPessoa=apm.codAutor
        left join sis_pessoas ppe on ppe.codPessoa=asm.codAutor
		left join sis_statusprescricao sp on sp.codStatusPrescricao=apm.stat
		where iff.codCategoria=3 and apm.codAtendimentoPrescricao = "' . $codAtendimentoPrescricao . '" order by apm.codPrescricaoMedicamento desc');
        return $query->getResult();
    }
}
