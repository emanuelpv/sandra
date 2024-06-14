<?php

use app\Models\ModelosNotificacaoModel as ModelosNotificacaoModel;
use app\Models\PerfisModel as PerfisModel;
use app\Models\CargosModel as CargosModel;
use app\Models\FuncoesModel as FuncoesModel;
use app\Models\PessoasModel as PessoasModel;
use app\Models\PacientesModel as PacientesModel;
use app\Models\ModulosModel as ModulosModel;
use app\Models\TipoLDAPModel as TipoLDAPModel;
use app\Models\RelatoriosModel as RelatoriosModel;
use app\Models\OrganizacoesModel as OrganizacoesModel;
use app\Models\EscalasModel as EscalasModel;
use app\Models\DepartamentosModel as DepartamentosModel;
use app\Models\ExamesListaModel as ExamesListaModel;
use app\Models\EspecialidadesModel as EspecialidadesModel;
use app\Models\StatusProjetosModel as StatusProjetosModel;
use app\Models\TiposProjetosModel as TiposProjetosModel;
use app\Models\QuestionariosModel as QuestionariosModel;
use app\Models\AtendimentoslocaisModel as AtendimentoslocaisModel;
use app\Models\AtendimentoDiagnosticoModel as AtendimentoDiagnosticoModel;
use app\Models\RotinasModel as RotinasModel;
use app\Models\ProtocolosRedeModel as ProtocolosRedeModel;
use app\Models\AgendamentosModel as AgendamentosModel;
use app\Models\AtendimentosModel as AtendimentosModel;
use app\Models\AtributosSistemaModel as AtributosSistemaModel;
use app\Models\AtributosTipoLDAPModel as AtributosTipoLDAPModel;
use app\Models\MunicipiosFederacaoModel as MunicipiosFederacaoModel;
use app\Models\PerfilPessoasMembroModel as PerfilPessoasMembroModel;
use app\Models\AtributosSistemaOrganizacaoModel as AtributosSistemaOrganizacaoModel;
use app\Models\ModulosNotificacaoModel as ModulosNotificacaoModel;
use app\Models\SlideshowModel as SlideshowModel;

function meusPacientesHoje()
{
    $AgendamentosModel = new AgendamentosModel;
    $totalPacientesHoje = $AgendamentosModel->meusPacientesHoje();
    return $totalPacientesHoje;
}

function atendimentosUrgenciaEmergencia()
{
    $AtendimentosModel = new AtendimentosModel;
    $totalPacientesHoje = $AtendimentosModel->totalPacientesUrgenciaEmergencia();
    return $totalPacientesHoje->total;
}


function slideShow()
{
    $SlideshowModel = new SlideshowModel;
    $slideShow = $SlideshowModel->slideShow();
    return $slideShow;
}


function atendimentosInternados()
{
    $AtendimentosModel = new AtendimentosModel;
    $totalPacientesHoje = $AtendimentosModel->totalPacientesInternados();
    return $totalPacientesHoje->total;
}

function unidadesInternacao()
{
    $AtendimentosModel = new AtendimentosModel;
    $unidadesInternacao = $AtendimentosModel->unidadesInternacao();
    return $unidadesInternacao;
}

function internados($unidadeInternacao)
{
    $AtendimentosModel = new AtendimentosModel;
    $internados = $AtendimentosModel->internados($unidadeInternacao);
    return $internados;
}


function monitorPrevAlta($codAtendimento)
{
    $AtendimentosModel = new AtendimentosModel;
    $previsaoAlta = $AtendimentosModel->monitorPrevAlta($codAtendimento);
    return $previsaoAlta;
}



function atendimentosInternadosOCS()
{
    $AtendimentosModel = new AtendimentosModel;
    $totalPacientesHoje = $AtendimentosModel->totalPacientesInternadosOCS();
    return $totalPacientesHoje->total;
}

function brl2decimal($brl, $casasDecimais = 2)
{
    if ($brl == NULL) {
        return null;
    }
    // Se já estiver no formato USD, retorna como float e formatado
    if (preg_match('/^\d+\.{1}\d+$/', $brl))
        return (float) number_format($brl, $casasDecimais, '.', '');
    // Tira tudo que não for número, ponto ou vírgula
    $brl = preg_replace('/[^\d\.\,]+/', '', $brl);
    // Tira o ponto
    $decimal = str_replace('.', '', $brl);
    // Troca a vírgula por ponto
    $decimal = str_replace(',', '.', $decimal);
    return (float) number_format($decimal, $casasDecimais, '.', '');
}


function dadosOcupacaoLeitos()
{
    $AtendimentosModel = new AtendimentosModel;
    $dadosOcupacaoLeitos = $AtendimentosModel->dadosOcupacaoLeitos();
    return $dadosOcupacaoLeitos;
}


function getNomeExibicaoPessoa($CI, $codPessoa)
{
    $CI->PessoasModel = new PessoasModel;
    $pessoas = $CI->PessoasModel->pegaPessoaPorCodPessoa($codPessoa);
    return $pessoas->nomeExibicao;
}


function parentestolookup($valor)
{
    if ($valor == 'OUTRO(A)') {
        return 6;
    }
    if ($valor == 'MÃE') {
        return 2;
    }
    if ($valor == 'PAI') {
        return 1;
    }
    if ($valor == 'ESPOSA') {
        return 3;
    }
    if ($valor == 'FILHO(A)') {
        return 4;
    }
    if ($valor == 'OUTROS') {
        return 6;
    }
    if ($valor == 'IRMÃ(0)') {
        return 5;
    }
    return 6;
}



function previsaoAlta($data = NULL, $dataEncerramento = NULL, $indeterminado = NULL)
{

    $previsao = array();


    if ($indeterminado == 1) {
        $dataPrevAlta = 'Indeterminada';
        //FALTAM
        $faltam =  '';

        $previsao['dataPrevAlta'] = $dataPrevAlta;
        $previsao['faltam'] = $faltam;

        return $previsao;
    }


    if ($data !== NULL) {


        if (date('Y-m-d', strtotime($data)) == date('Y-m-d')) {

            $dataPrevAlta = 'Hoje, ' . date('d/m/Y', strtotime($data));;

            //FALTAM
            $faltam =  'Falta 0 dia  ';
        }


        if (date('Y-m-d', strtotime($data)) > date('Y-m-d')) {
            $dataPrevAlta = date('d/m/Y', strtotime($data));

            //FALTAM
            $faltam =  'Falta(m)  ' . intervaloTempoAtendimento($data, DATE('Y-m-d'));
        }

        if (date('Y-m-d', strtotime($data)) < date('Y-m-d')) {
            $dataPrevAlta = date('d/m/Y', strtotime($data));

            //FALTAM
            $faltam =  'Passou ' . intervaloTempoAtendimento($data, DATE('Y-m-d'));
        }

        if (date('Y-m-d', strtotime($data)) == date('Y-m-d', strtotime("+1 day", strtotime(date('Y-m-d'))))) {

            $dataPrevAlta = '<img style="width:20px;" src="' . base_url() . '/imagens/atencao.gif">Amanhã, ' . date('d/m/Y', strtotime($data));;

            //FALTAM
            $faltam =  'Falta 1 dia  ';
        }
    } else {

        $dataPrevAlta = 'Não Informado';
        //FALTAM
        $faltam =  '';
    }

    $previsao['dataPrevAlta'] = $dataPrevAlta;
    $previsao['faltam'] = $faltam;
    if ($dataEncerramento !== NULL) {
        $previsao['faltam'] = '';
    }
    return $previsao;
}


function intervaloTempoAtendimento($dataCriacao = null, $dataEncerramento = null)
{
    $resultadoArray = 0;
    if ($dataEncerramento == null) {
        $resultadoArray = 1;
        $dataEncerramento = date('Y-m-d H:i');
    } else {
        $dataEncerramento = $dataEncerramento;
    }



    $dataCriacao = new DateTime($dataCriacao);
    $dataEncerramento = new DateTime($dataEncerramento);
    $since_start = $dataCriacao->diff($dataEncerramento); // date now



    //ANOS
    if ($since_start->y == 0) {
        $unidadeAno = "";
    }
    if ($since_start->y == 1) {
        $unidadeAno = $since_start->y . " ano";
    }
    if ($since_start->y > 1) {
        $unidadeAno = $since_start->y . " anos";
    }


    //MES
    if ($since_start->m == 0) {
        $unidadeMes = "";
    }
    if ($since_start->m == 1) {
        $unidadeMes = $since_start->m . " mês";
    }
    if ($since_start->m > 1) {
        $unidadeMes = $since_start->m . " meses";
    }


    //SEMANAS
    if ($since_start->ww == 0) {
        $unidadeSemana = "";
    }
    if ($since_start->ww == 1) {
        $unidadeSemana = $since_start->ww . " semana";
    }
    if ($since_start->ww > 1) {
        $unidadeSemana = $since_start->ww . " semanas";
    }


    //DIAS
    if ($since_start->d == 0) {
        $unidadeDia = "";
    }
    if ($since_start->d == 1) {
        $unidadeDia = $since_start->d . " dia";
    }
    if ($since_start->d > 1) {
        $unidadeDia = $since_start->d . " dias";
    }


    //HORAS
    if ($since_start->h == 0) {
        $unidadeHora = "";
    }
    if ($since_start->h == 1) {
        $unidadeHora = $since_start->h . " h";
    }
    if ($since_start->h > 1) {
        $unidadeHora = $since_start->h . " h";
    }


    //MINUTOS
    if ($since_start->i == 0) {
        $unidadeMinuto = "";
    }
    if ($since_start->i == 1) {
        $unidadeMinuto = $since_start->i . " min";
    }
    if ($since_start->i > 1) {
        $unidadeMinuto = $since_start->i . " min";
    }

    if ($resultadoArray == 0) {
        return  $unidadeAno . " " . $unidadeMes . " " . $unidadeSemana . " " . $unidadeDia . " " . $unidadeHora . " " . $unidadeMinuto;
    } else {
        return $tempo = array(
            'unidadeAno' => $unidadeAno,
            'unidadeMes' => $unidadeMes,
            'unidadeDia' => $unidadeDia,
            'unidadeHora' => $unidadeHora,
            'unidadeMinuto' => $unidadeMinuto,
        );
    }
}



function intervaloTempoFatura($dataCriacao = null, $dataEncerramento = null)
{
    $resultadoArray = 0;
    if ($dataEncerramento == null) {
        $resultadoArray = 1;
        $dataEncerramento = date('Y-m-d H:i');
    } else {
        $dataEncerramento = $dataEncerramento;
    }



    $dataCriacao = new DateTime($dataCriacao);
    $dataEncerramento = new DateTime($dataEncerramento);
    $since_start = $dataCriacao->diff($dataEncerramento); // date now



    //ANOS
    if ($since_start->y == 0) {
        $unidadeAno = "";
    }
    if ($since_start->y == 1) {
        $unidadeAno = $since_start->y . " ano";
    }
    if ($since_start->y > 1) {
        $unidadeAno = $since_start->y . " anos";
    }


    //MES
    if ($since_start->m == 0) {
        $unidadeMes = "";
    }
    if ($since_start->m == 1) {
        $unidadeMes = $since_start->m . " mês";
    }
    if ($since_start->m > 1) {
        $unidadeMes = $since_start->m . " meses";
    }


    //DIAS
    if ($since_start->d == 0) {
        $unidadeDia = "";
    }
    if ($since_start->d == 1) {
        $unidadeDia = $since_start->d . " dia";
    }
    if ($since_start->d > 1) {
        $unidadeDia = $since_start->d . " dias";
    }


    if ($resultadoArray == 0) {
        return  $unidadeAno . " " . $unidadeMes . " " . $unidadeDia;
    } else {
        return $tempo = array(
            'unidadeAno' => $unidadeAno,
            'unidadeMes' => $unidadeMes,
            'unidadeDia' => $unidadeDia,
        );
    }
}


function intervaloTempoHoraMinutos($dataCriacao = null, $dataEncerramento = null)
{
    $dataCriacao = new DateTime($dataCriacao);
    $dataEncerramento = new DateTime($dataEncerramento);
    $since_start = $dataCriacao->diff($dataEncerramento); // date now

    $unidadeHora = "hora";
    $unidadeMinuto = "minuto";
    if ($since_start->h > 1) {
        $unidadeHora = "horas";
    }
    if ($since_start->i > 1) {
        $unidadeMinuto = "minutos";
    }

    if ($since_start->h == 0) {
        return  $since_start->i . " " . $unidadeMinuto;
    } else {
        return  $since_start->h . " " . $unidadeHora . " " . $since_start->i . " " . $unidadeMinuto;
    }
}

function intervaloTempo($dataCriacao = null, $dataEncerramento = null, $diff = "minutes")
{
    $dataCriacao = new DateTime($dataCriacao);
    $dataEncerramento = new DateTime($dataEncerramento);
    $since_start = $dataCriacao->diff($dataEncerramento); // date now


    switch ($diff) {
        case 'seconds':
            return $since_start->s;
            break;
        case 'minutes':
            return $since_start->i;
            break;
        case 'hours':
            return $since_start->h;
            break;
        case 'days':
            return $since_start->d;
            break;
        case 'weeks':
            return $since_start->ww;
            break;
        case 'months':
            return $since_start->m;
            break;
        case 'years':
            return $since_start->y;
            break;
        default:
            # code...
            break;
    }
}


function tipoContatoLookup($valor)
{
    if ($valor == 'OUTRO(A)') {
        return 6;
    }
    if ($valor == 'OUTRO') {
        return 6;
    }
    if ($valor == 'AMIGO(A)') {
        return 5;
    }
    if ($valor == 'FAMILIAR') {
        return 3;
    }
    if ($valor == 'AMIGO') {
        return 5;
    }
    if ($valor == 'CHEFE') {
        return 2;
    }
    if ($valor == 'MÉDICO FAM') {
        return 4;
    }
    if ($valor == 'RESIDENCIAL') {
        return 8;
    }
    if ($valor == 'FUNCIONAL') {
        return 7;
    }
    if ($valor == 'PESSOAL') {
        return 1;
    }
    return 6;
}

function codPacienteLookupPorProntuario($pron_id, $pron_nr)
{
    $PacientesModel = new PacientesModel;
    $prontuario = $PacientesModel->pegaPacientePorCodProntuario($pron_id, $pron_nr);
    return  $prontuario->codPaciente;
}

function lookupCodPessoaPorCODPLANOIntegracaoApolo($CODPLANOr)
{
    if ($CODPLANOr == NULL or $CODPLANOr == "") {
        return 0;
    }
    $RotinasModel = new RotinasModel;
    $prontuario = $RotinasModel->lookupCodPessoaPorCODPLANOIntegracaoApolo($CODPLANOr);
    return  $prontuario->codPessoa;
}


function lookupEspecialista($CODPLANOr)
{

    if ($CODPLANOr == NULL or $CODPLANOr == "") {
        return 0;
    }
    $RotinasModel = new RotinasModel;
    $pessoa = $RotinasModel->lookupCodPessoa($CODPLANOr);
    return  $pessoa->codPessoa;
}


function lookupCid10($cid)
{

    if ($cid == NULL or $cid == "" or $cid == " ") {
        return null;
    }

    $AtendimentoDiagnosticoModel = new AtendimentoDiagnosticoModel;
    $cid = $AtendimentoDiagnosticoModel->lookupCid10($cid);

    if ($cid !== NULL) {
        return  $cid->codCid;
    } else {
        return  null;
    }
}

function lookupcodEstadoFederacao($codEstadoFederacao)
{
    $PacientesModel = new PacientesModel;
    $estadoFederacao = $PacientesModel->lookupEstadoFederacao($codEstadoFederacao);
    return  $estadoFederacao->codEstadoFederacao;
}

function conselhoLookup($conselho)
{
    $PacientesModel = new PacientesModel;
    $codConselho = $PacientesModel->pegaCodConselho($conselho);
    return  $codConselho;
}


function forcaLookup($valor)
{
    if ($valor == 'MB') {
        return 1;
    }
    if ($valor == 'EB') {
        return 2;
    }
    if ($valor == 'FAB') {
        return 3;
    }
    return 2;
}

function lookupCaegoriaItemFarmacia($valor)
{
    if ($valor == 1) {
        return 7;
    }

    if ($valor == 10) {
        return 2;
    }

    if ($valor == 35) {
        return 5;
    }

    if ($valor == 36) {
        return 6;
    }

    if ($valor == 43) {
        return 4;
    }

    if ($valor == 7) {
        return 3;
    }

    if ($valor == 26) {
        return 8;
    }

    if ($valor == 9) {
        return 1;
    }
    if ($valor == 0) {
        return 1;
    }
}



function dataValidade($texto)
{
    $data = explode("@", preg_replace('/[^0-9@]+/', '', str_replace("/", "@", $texto)));

    if (count($data) == 3) {
        return $data[2] . "-" .  $data[1] . "-" .  $data[0];
    }

    if (count($data) == 2) {
        return  $data[1] . "-" . $data[0] . "-" . '01';
    }
    return null;
}


function tipoBeneficiarioLookup($situacao)
{
    $PacientesModel = new PacientesModel;
    $codTipoBeneficiario = $PacientesModel->pegaTipoBeneficiario($situacao);
    return  $codTipoBeneficiario;
}


function tipoBeneficiarioLookupSIGH($valor)
{

    if ($valor == 1) {
        return 1;
    }
    if ($valor == 2) {
        return 2;
    }
    if ($valor == 3) {
        return 1;
    }
    if ($valor == 4) {
        return 12;
    }
    if ($valor == 5) {
        return 1;
    }
    if ($valor == 6) {
        return 1;
    }
    if ($valor == 7) {
        return 5;
    }
    if ($valor == 8) {
        return 1;
    }
    if ($valor == 9) {
        return 9;
    }
    if ($valor == 10) {
        return 8;
    }
    if ($valor == 11) {
        return 8;
    }
    if ($valor == 12) {
        return 3;
    }
    if ($valor == 13) {
        return 8;
    }
    if ($valor == 14) {
        return 10;
    }

    if ($valor >= 16 and $valor <= 18) {
        return 21;
    }

    return 22;
}


function lookupCodEspecialidade($especialidade)
{
    $EspecialidadesModel = new EspecialidadesModel;
    $especialidade = $EspecialidadesModel->pegaespecialidadePorNome($especialidade);
    return  $especialidade->codEspecialidade;
}

function lookupCodEspecialidadeAtendimento($localAtendimento)
{
    $RotinasModel = new RotinasModel;
    $especialidade = $RotinasModel->pegaespecialidadePorLocal($localAtendimento);
    return  $especialidade->codEspecialidade;
}

function lookupCodLocalAtendimento($localAtendimento)
{
    $RotinasModel = new RotinasModel;
    $especialidade = $RotinasModel->lookupCodLocalAtendimento($localAtendimento);
    return  $especialidade->codLocalAtendimento;
}

function lookupCodTipoAtendimento($localAtendimento)
{
    $RotinasModel = new RotinasModel;
    $especialidade = $RotinasModel->pegaTipoAtendimentoPorLocal($localAtendimento);
    return  $especialidade->codTipoAtendimento;
}

function lookupTipoEvolucao($tipoEvolucao)
{

    if ($tipoEvolucao == 'EV MÉDICA') {
        return 1;
    }
    if ($tipoEvolucao == 'EV ENFERMAGEM') {
        return 2;
    }
    if ($tipoEvolucao == 'EV TEC ENFERMAGEM') {
        return 3;
    }
    if ($tipoEvolucao == 'EV NUTRICIONAL') {
        return 4;
    }
    if ($tipoEvolucao == 'EV FISIOTERAPIA') {
        return 5;
    }
    if ($tipoEvolucao == 'EV FONOAUDIOLOGIA') {
        return 6;
    }
    if ($tipoEvolucao == 'EV PSICOLOGIA') {
        return 7;
    }
    if ($tipoEvolucao == 'EV ODONTOLÓGICA') {
        return 8;
    }
    if ($tipoEvolucao == 'EV ASST SOC') {
        return 9;
    }
    if ($tipoEvolucao == 'DESCR CIRURGICA') {
        return 10;
    }
    return 1;
}



function lookupNomeLocalAtendimento($codLocalAtendimento)
{
    $AtendimentoslocaisModel = new AtendimentoslocaisModel;
    $localAtendimento = $AtendimentoslocaisModel->pegaPorCodigo($codLocalAtendimento);
    return  $localAtendimento->descricaoLocalAtendimento;
}



function lookupNomeEspecialidade($codEspecialidade)
{
    $EspecialidadesModel = new EspecialidadesModel;
    $especialidades = $EspecialidadesModel->lookupCodNomeEspecialidade($codEspecialidade);
    return  $especialidades->descricaoEspecialidade;
}


function lookupCodNomeEspecialidadesJson($especialidades)
{

    $especialidades = removeBraketsJson($especialidades);
    $EspecialidadesModel = new EspecialidadesModel;
    $especialidades = $EspecialidadesModel->lookupCodNomeEspecialidadesJson($especialidades);
    return  $especialidades;
}

function lookupCodNomeExamesJson($exames)
{

    $exames = removeBraketsJson($exames);
    $ExamesListaModel = new ExamesListaModel;
    $exames = $ExamesListaModel->lookupCodNomeExamesJson($exames);
    return  $exames;
}


function lookupCodNomeDepartamentosJson($codDepartamento)
{


    $departamentos = removeBraketsJson($codDepartamento);
    $DepartamentosModel = new DepartamentosModel;
    $departamentos = $DepartamentosModel->lookupCodNomeDepartamentosJson($departamentos);
    return  $departamentos;
}

function lookupMigracaoDepartamentos($nomeDepartamento)
{


    $DepartamentosModel = new DepartamentosModel;
    $codDepartamentos = $DepartamentosModel->lookupMigracaoDepartamentos($nomeDepartamento);
    return  $codDepartamentos;
}


function nomeDepartamentoFull($codDepartamento)
{


    $DepartamentosModel = new DepartamentosModel;
    $codDepartamentos = $DepartamentosModel->pegaDepartamento($codDepartamento);
    return  $codDepartamentos->descricaoDepartamento;
}


function ativoLookup($valor)
{
    if ($valor == 'DESBLOQUEADO') {
        return 1;
    }
    if ($valor == 'LIBERADO') {
        return 1;
    }
    if ($valor == 'BLOQUEADO') {
        return 0;
    }
    if ($valor == 'NOVO') {
        return 1;
    }
    if ($valor == 'ARQUIVADO') {
        return 0;
    }
    return 1;
}

function ativoLookupSIGH($valor)
{
    if ($valor == 0) {
        return 0;
    }
    return 1;
}

function tipoSanguineoLookup($tp_ts, $tp_rh)
{


    if ($tp_rh !== 'POS' and $tp_rh !== 'NEG') {
        return null;
    }
    //TP POSITIVOS
    if ($tp_ts !== 'A' and $tp_rh !== 'POS') {
        return 1;
    }
    if ($tp_ts !== 'B' and $tp_rh !== 'POS') {
        return 2;
    }
    if ($tp_ts !== 'AB' and $tp_rh !== 'POS') {
        return 3;
    }
    if ($tp_ts !== 'O' and $tp_rh !== 'POS') {
        return 4;
    }

    //TP NEGATIVOS
    if ($tp_ts !== 'A' and $tp_rh !== 'NEG') {
        return 5;
    }
    if ($tp_ts !== 'B' and $tp_rh !== 'NEG') {
        return 6;
    }
    if ($tp_ts !== 'AB' and $tp_rh !== 'NEG') {
        return 7;
    }
    if ($tp_ts !== 'O' and $tp_rh !== 'NEG') {
        return 8;
    }



    return null;
}
function statusLookup($valor)
{
    if ($valor == 'NOVO') {
        return 1;
    }
    if ($valor == 'LIBERADO') {
        return 2;
    }
    if ($valor == 'BLOQUEADO') {
        return 3;
    }
    if ($valor == 'DESBLOQUEADO') {
        return 4;
    }
    if ($valor == 'ARQUIVADO') {
        return 5;
    }
    return 2;
}

function racaLookup($valor)
{
    if ($valor == '-NÃO INFORMADO') {
        return 0;
    }
    if ($valor == 'Preta') {
        return 5;
    }
    if ($valor == 'Parda') {
        return 1;
    }
    if ($valor == 'Branca') {
        return 2;
    }
    if ($valor == 'Amarela') {
        return 3;
    }
    if ($valor == 'Indígena') {
        return 4;
    }
    return 0;
}


function postoGraduacaoDescricao($valor)
{
    if ($valor == 'GEN EX') {
        return $valor;
    }
    if ($valor == 'GEN DIV') {
        return $valor;
    }
    if ($valor == 'GEN BDA') {
        return $valor;
    }
    if ($valor == 'CEL') {
        return $valor;
    }
    if ($valor == 'TEN CEL') {
        return $valor;
    }
    if ($valor == 'MAJ') {
        return $valor;
    }
    if ($valor == 'CAP') {
        return $valor;
    }
    if ($valor == '1 TEN') {
        return '1º TEN';
    }
    if ($valor == '2 TEN') {
        return '2º TEN';
    }
    if ($valor == 'ASP OF') {
        return $valor;
    }
    if ($valor == 'SUB TEN') {
        return $valor;
    }
    if ($valor == '1 SGT') {
        return '1º SGT';
    }
    if ($valor == '2 SGT') {
        return '2º SGT';
    }
    if ($valor == '3 SGT') {
        return '2º SGT';
    }
    if ($valor == 'CB') {
        return $valor;
    }
    if ($valor == 'CB CET') {
        return 'CB';
    }
    if ($valor == 'CB EV') {
        return 'CB';
    }
    if ($valor == 'SD EP') {
        return 'SD NB';
    }
    if ($valor == 'SD EV') {
        return 'SD EV';
    }
    if ($valor == 'SC') {
        return $valor;
    }
    if ($valor == 'DEP SC') {
        return $valor;
    }
    if ($valor == 'CIVIL') {
        return $valor;
    }
    if ($valor == 'Militar MB') {
        return $valor;
    }
    if ($valor == 'Militar FAB') {
        return $valor;
    }
    return 'CIVIL';
}


function postoGraduacaoLookup($valor)
{
    if ($valor == 'GEN EX') {
        return 2;
    }
    if ($valor == 'GEN DIV') {
        return 3;
    }
    if ($valor == 'GEN BDA') {
        return 4;
    }
    if ($valor == 'CEL') {
        return 5;
    }
    if ($valor == 'TEN CEL') {
        return 6;
    }
    if ($valor == 'MAJ') {
        return 7;
    }
    if ($valor == 'CAP') {
        return 8;
    }
    if ($valor == '1 TEN') {
        return 9;
    }
    if ($valor == '2 TEN') {
        return 10;
    }
    if ($valor == 'ASP OF') {
        return 11;
    }
    if ($valor == 'SUB TEN') {
        return 12;
    }
    if ($valor == '1 SGT') {
        return 13;
    }
    if ($valor == '2 SGT') {
        return 14;
    }
    if ($valor == '3 SGT') {
        return 15;
    }
    if ($valor == 'CB') {
        return 16;
    }
    if ($valor == 'CB CET') {
        return 16;
    }
    if ($valor == 'CB EV') {
        return 16;
    }
    if ($valor == 'SD EP') {
        return 17;
    }
    if ($valor == 'SD EV') {
        return 18;
    }
    if ($valor == 'SC') {
        return 19;
    }
    if ($valor == 'DEP SC') {
        return 20;
    }
    if ($valor == 'CIVIL') {
        return 21;
    }
    if ($valor == 'Militar MB') {
        return 22;
    }
    if ($valor == 'Militar FAB') {
        return 23;
    }
    return 21;
}

function postoGraduacaoLookupSIGH($valor)
{
    if ($valor == 1) {
        return 1;
    }
    if ($valor == 2) {
        return 2;
    }
    if ($valor == 3) {
        return 3;
    }
    if ($valor == 4) {
        return 4;
    }
    if ($valor == 5) {
        return 5;
    }
    if ($valor == 6) {
        return 6;
    }
    if ($valor == 7) {
        return 7;
    }
    if ($valor == 8) {
        return 8;
    }
    if ($valor == 9) {
        return 9;
    }
    if ($valor == 10) {
        return 10;
    }
    if ($valor == 11) {
        return 11;
    }
    if ($valor == 12) {
        return 11;
    }
    if ($valor == 13) {
        return 11;
    }
    if ($valor == 14) {
        return 11;
    }
    if ($valor == 15) {
        return 11;
    }
    if ($valor == 16) {
        return 11;
    }
    if ($valor == 17) {
        return 11;
    }
    if ($valor == 18) {
        return 12;
    }
    if ($valor == 19) {
        return 13;
    }
    if ($valor == 20) {
        return 14;
    }
    if ($valor == 21) {
        return 15;
    }
    if ($valor == 22) {
        return 16;
    }
    if ($valor == 23) {
        return 16;
    }
    if ($valor == 27) {
        return 17;
    }
    if ($valor == 28) {
        return 18;
    }
    if ($valor == 'SC') {
        return 19;
    }
    if ($valor == 29) {
        return 18;
    }
    if ($valor == 30) {
        return 18;
    }
    if ($valor == 31) {
        return 18;
    }
    if ($valor == 32) {
        return 18;
    }
    if ($valor == 33) {
        return 18;
    }
    if ($valor == 34) {
        return 21;
    }
    if ($valor == 36) {
        return 21;
    }
    if ($valor == 37) {
        return 21;
    }

    if ($valor == 38) {
        return 21;
    }

    if ($valor == 39) {
        return 21;
    }
    if ($valor == 97) {
        return 18;
    }
    if ($valor == 98) {
        return 18;
    }
    if ($valor >= 99 and $valor <= 113) {
        return 22;
    }

    return 96;
}



function listboxDestinatariosNotificacoes($CI, $tipo = null, $hiddenOrDisabled = null)
{
    $CI->PessoasModel = new PessoasModel;
    $pessoas = $CI->PessoasModel->pegatudo();


    $botao = '
    <div class="form-group">

    <select ' . $hiddenOrDisabled . ' id="destinoNotificacao' . $tipo . '"  name="destinoNotificacao[]"  class="select2" multiple="multiple" data-placeholder="Selecione os destinatários" data-dropdown-css-class="select2-primary" style="width: 100%;">
    
            <option value="0">Autor da ação</option>';
    foreach ($pessoas as $row) {

        $botao .= '
                <option value="' . $row->codPessoa . '" >' . $row->nomeExibicao . '</option>';
    }
    $botao .= "</select>
    </div>";
    return $botao;
}


function listboxTipoNotificacoes($CI, $tipo = null, $hiddenOrDisabled = null)
{
    $CI->ModulosNotificacaoModel = new ModulosNotificacaoModel;
    $modulosNotificacao = $CI->ModulosNotificacaoModel->pegaTiposNotificacoes();


    $botao = '
    
    <select style="width:100%"' . $hiddenOrDisabled . ' id="codTipoNotificacao' . $tipo . '"  name="codTipoNotificacao" class="custom-select" >
    
            <option value=""></option>';
    foreach ($modulosNotificacao as $row) {

        $botao .= '
                <option value="' . $row->codTipoNotificacao . '" >' . $row->descricaoTipoNotificacao . '</option>';
    }
    $botao .= "</select>";
    return $botao;
}

function listboxModelosNotificacoes($CI, $tipo = null, $hiddenOrDisabled = null)
{
    $CI->ModelosNotificacaoModel = new ModelosNotificacaoModel;
    $ModelosNotificacao = $CI->ModelosNotificacaoModel->pegaTudo();


    $botao = '
    
    <select style="width:100%" id="codModeloNotificacao' . $tipo . '"  name="codModeloNotificacao"   >
    
            <option value="0"></option>';
    foreach ($ModelosNotificacao as $row) {

        $botao .= '
                <option value="' . $row->codModeloNotificacao . '" >' . $row->nomeModeloNotificacao . '</option>';
    }
    $botao .= "</select>";
    return $botao;
}





function formularioRapido($CI, $localExibicao = null)
{

    $CI->AtributosSistemaOrganizacaoModel = new AtributosSistemaOrganizacaoModel();
    $atrubutosOrganizacaoSistama = $CI->AtributosSistemaOrganizacaoModel->pegaTudo();
    $atributos = array();


    $controleLinha = 0;
    $contador = 0;

    $totalAtributos = count($atrubutosOrganizacaoSistama);

    foreach ($atrubutosOrganizacaoSistama as $row) {
        $contador++;

        if ($row->cadastroRapido == 1) {
            if ($controleLinha == 0) {
?>
                <div class="row">
                <?php
            }

            //É TEXTO
            array_push($atributos, $row->nomeAtributoSistema);


            if ($row->tipo == 'password') {
                senhaForm($row->nomeAtributoSistema, $row->descricaoAtributoSistema, $row->tipo, $row->tamanho, $row->obrigatorio, $row->icone, $localExibicao);
            } elseif ($row->tipo == 'image') {
                fotoPerfil($CI, $row->nomeAtributoSistema, $row->descricaoAtributoSistema, $row->tipo, $row->tamanho, $row->obrigatorio, $row->icone, $row->fotoPerfil, $localExibicao);
            } elseif ($row->tipo == 'select' or $row->tipo == 'checkbox' or $row->tipo == 'textarea') {
                //É ORGANIZACAO
                if ($row->nomeAtributoSistema == 'codOrganizacao') {
                    listboxOrganizacoesForm($CI, $row->nomeAtributoSistema, $row->descricaoAtributoSistema, $row->tipo, $row->tamanho, $row->obrigatorio, $row->icone, $localExibicao);
                }
                //É ORGANIZACAO
                if ($row->nomeAtributoSistema == 'codMunicipioFederacao') {
                    listboxMunicipiosFederacaoForm($CI, $row->nomeAtributoSistema, $row->descricaoAtributoSistema, $row->tipo, $row->tamanho, $row->obrigatorio, $row->icone, $localExibicao);
                }
                //É DEPARTAMENTO
                if ($row->nomeAtributoSistema == 'codDepartamento') {
                    listboxDepartamentosForm($CI, $row->nomeAtributoSistema, $row->descricaoAtributoSistema, $row->tipo, $row->tamanho, $row->obrigatorio, $row->icone, $localExibicao);
                }

                //É ESPECIALIDADE
                if ($row->nomeAtributoSistema == 'codEspecialidade') {
                    listboxEspecialidadesForm($CI, $row->nomeAtributoSistema, $row->descricaoAtributoSistema, $row->tipo, $row->tamanho, $row->obrigatorio, $row->icone, $localExibicao);
                }


                //É FUNÇÃO
                if ($row->nomeAtributoSistema == 'codFuncao') {
                    listboxFuncoesForm($CI, $row->nomeAtributoSistema, $row->descricaoAtributoSistema, $row->tipo, $row->tamanho, $row->obrigatorio, $row->icone, $localExibicao);
                }


                //É CARGO
                if ($row->nomeAtributoSistema == 'codCargo') {
                    listboxCargosForm($CI, $row->nomeAtributoSistema, $row->descricaoAtributoSistema, $row->tipo, $row->tamanho, $row->obrigatorio, $row->icone, $localExibicao);
                }

                //É STATUS/ATIVO/INATIVO
                if ($row->nomeAtributoSistema == 'ativo') {
                    listboxAtivoForm($CI, $row->nomeAtributoSistema, $row->descricaoAtributoSistema, $row->tipo, $row->tamanho, $row->obrigatorio, $row->icone, $localExibicao);
                }


                //SUBORDINADO Á
                if ($row->nomeAtributoSistema == 'pai') {
                    listboxPessoaSubordinacaoForm($CI, $row->nomeAtributoSistema, $row->descricaoAtributoSistema, $row->tipo, $row->tamanho, $row->obrigatorio, $row->icone, $localExibicao);
                }

                //PERFIL PADRÃO
                if ($row->nomeAtributoSistema == 'codPerfilPadrao') {
                    listboxPerfilPadraoForm($CI, $row->nomeAtributoSistema, $row->descricaoAtributoSistema, $row->tipo, $row->tamanho, $row->obrigatorio, $row->icone, $localExibicao);
                }
                //É ACEITE TERMO DE USO
                if ($row->nomeAtributoSistema == 'aceiteTermos') {
                    listboxAceiteTermos($CI, $row->nomeAtributoSistema, $row->descricaoAtributoSistema, $row->tipo, $row->tamanho, $row->obrigatorio, $row->icone, $localExibicao);
                }
                //INFORMACOES COMPLEMENTARES
                if ($row->nomeAtributoSistema == 'informacoesComplementares') {
                    informacoesComplementares($CI, $row->nomeAtributoSistema, $row->descricaoAtributoSistema, $row->tipo, $row->tamanho, $row->obrigatorio, $row->icone, $localExibicao);
                }
            } else {
                echo constroAtributoFormulario($row->nomeAtributoSistema, $row->descricaoAtributoSistema, $row->tipo, $row->tamanho, $row->obrigatorio, $row->icone, $localExibicao);
            }
            $controleLinha++;

            if ($controleLinha == 3) {
                ?>
                </div>
            <?php
                $controleLinha = 0;
            }
            if ($controleLinha < 3 and $totalAtributos == $contador) {
            ?>
                </div>
            <?php

            }
        }
    }
}



function removeBraketsJson($string)
{
    $com = array(']', '[');
    $sem = array('', '');
    return strtoupper(str_replace($com, $sem, $string));
}

function removeCaracteresIndesejados($string)
{
    $com = array(']', '[', "'", '.', ' ', '-', ')', '(', 'º', '°', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ü', 'ú', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'O', 'Ù', 'Ü', 'Ú');
    $sem = array('', '', "", '', '', '', '', '', '', '', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', '0', 'U', 'U', 'U');
    return strtoupper(str_replace($com, $sem, $string));
}

function removeCaracteresIndesejadosEmail($string)
{
    $com = array(']', '[', "'", ' ', ')', '(', 'º', '°', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ü', 'ú', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'O', 'Ù', 'Ü', 'Ú');
    $sem = array('', '', "", '', '', '', '', '', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', '0', 'U', 'U', 'U');
    return strtolower(str_replace($com, $sem, $string));
}


function ordenaAssinaturas($assinaturas)
{

    array_multisort(array_map(function ($element) {
        return $element['codCargo'];
    }, $assinaturas), SORT_ASC, $assinaturas);


    $assinaturas = array_map("unserialize", array_unique(array_map("serialize", $assinaturas)));


    return $assinaturas;
}


function removeAcentos($string)
{
    $com = array('à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ü', 'ú', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'O', 'Ù', 'Ü', 'Ú');
    $sem = array('a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', '0', 'U', 'U', 'U');
    return strtoupper(str_replace($com, $sem, $string));
}


function formularioPessoaPadrao($CI, $localExibicao = null)
{

    $CI->AtributosSistemaOrganizacaoModel = new AtributosSistemaOrganizacaoModel();
    $atrubutosOrganizacaoSistama = $CI->AtributosSistemaOrganizacaoModel->pegaTudo();
    $atributos = array();


    $controleLinha = 0;
    $contador = 0;

    $totalAtributos = count($atrubutosOrganizacaoSistama);

    foreach ($atrubutosOrganizacaoSistama as $row) {
        $contador++;

        if ($row->visivelFormulario == 1) {
            if ($controleLinha == 0) {
            ?>
                <div class="row">
                <?php
            }

            //É TEXTO
            array_push($atributos, $row->nomeAtributoSistema);


            if ($row->tipo == 'password') {
                senhaForm($row->nomeAtributoSistema, $row->descricaoAtributoSistema, $row->tipo, $row->tamanho, $row->obrigatorio, $row->icone, $localExibicao);
            } elseif ($row->tipo == 'image') {
                fotoPerfil($CI, $row->nomeAtributoSistema, $row->descricaoAtributoSistema, $row->tipo, $row->tamanho, $row->obrigatorio, $row->icone, $row->fotoPerfil, $localExibicao);
            } elseif ($row->tipo == 'select' or $row->tipo == 'checkbox' or $row->tipo == 'textarea') {
                //É ORGANIZACAO
                if ($row->nomeAtributoSistema == 'codOrganizacao') {
                    listboxOrganizacoesForm($CI, $row->nomeAtributoSistema, $row->descricaoAtributoSistema, $row->tipo, $row->tamanho, $row->obrigatorio, $row->icone, $localExibicao);
                }
                //É ORGANIZACAO
                if ($row->nomeAtributoSistema == 'codMunicipioFederacao') {
                    listboxMunicipiosFederacaoForm($CI, $row->nomeAtributoSistema, $row->descricaoAtributoSistema, $row->tipo, $row->tamanho, $row->obrigatorio, $row->icone, $localExibicao);
                }
                //É DEPARTAMENTO
                if ($row->nomeAtributoSistema == 'codDepartamento') {
                    listboxDepartamentosForm($CI, $row->nomeAtributoSistema, $row->descricaoAtributoSistema, $row->tipo, $row->tamanho, $row->obrigatorio, $row->icone, $localExibicao);
                }

                //É ESPECIALIDADE
                if ($row->nomeAtributoSistema == 'codEspecialidade') {
                    listboxEspecialidadesForm($CI, $row->nomeAtributoSistema, $row->descricaoAtributoSistema, $row->tipo, $row->tamanho, $row->obrigatorio, $row->icone, $localExibicao);
                }


                //É FUNÇÃO
                if ($row->nomeAtributoSistema == 'codFuncao') {
                    listboxFuncoesForm($CI, $row->nomeAtributoSistema, $row->descricaoAtributoSistema, $row->tipo, $row->tamanho, $row->obrigatorio, $row->icone, $localExibicao);
                }

                //É CARGO
                if ($row->nomeAtributoSistema == 'codCargo') {
                    listboxCargosForm($CI, $row->nomeAtributoSistema, $row->descricaoAtributoSistema, $row->tipo, $row->tamanho, $row->obrigatorio, $row->icone, $localExibicao);
                }

                //É STATUS/ATIVO/INATIVO
                if ($row->nomeAtributoSistema == 'ativo') {
                    listboxAtivoForm($CI, $row->nomeAtributoSistema, $row->descricaoAtributoSistema, $row->tipo, $row->tamanho, $row->obrigatorio, $row->icone, $localExibicao);
                }


                //SUBORDINADO Á
                if ($row->nomeAtributoSistema == 'pai') {
                    listboxPessoaSubordinacaoForm($CI, $row->nomeAtributoSistema, $row->descricaoAtributoSistema, $row->tipo, $row->tamanho, $row->obrigatorio, $row->icone, $localExibicao);
                }

                //PERFIL PADRÃO
                if ($row->nomeAtributoSistema == 'codPerfilPadrao') {
                    listboxPerfilPadraoForm($CI, $row->nomeAtributoSistema, $row->descricaoAtributoSistema, $row->tipo, $row->tamanho, $row->obrigatorio, $row->icone, $localExibicao);
                }
                //É ACEITE TERMO DE USO
                if ($row->nomeAtributoSistema == 'aceiteTermos') {
                    listboxAceiteTermos($CI, $row->nomeAtributoSistema, $row->descricaoAtributoSistema, $row->tipo, $row->tamanho, $row->obrigatorio, $row->icone, $localExibicao);
                }
                //INFORMACOES COMPLEMENTARES
                if ($row->nomeAtributoSistema == 'informacoesComplementares') {
                    informacoesComplementares($CI, $row->nomeAtributoSistema, $row->descricaoAtributoSistema, $row->tipo, $row->tamanho, $row->obrigatorio, $row->icone, $localExibicao);
                }
            } else {
                echo constroAtributoFormulario($row->nomeAtributoSistema, $row->descricaoAtributoSistema, $row->tipo, $row->tamanho, $row->obrigatorio, $row->icone, $localExibicao);
            }
            $controleLinha++;

            if ($controleLinha == 3) {
                ?>
                </div>
            <?php
                $controleLinha = 0;
            }
            if ($controleLinha < 3 and $totalAtributos == $contador) {
            ?>
                </div>
    <?php

            }
        }
    }
}



function fotoPerfil($CI, $nomeAtributoSistema, $descricaoAtributoSistema, $tipo, $tamanho, $obrigatorio, $icone, $fotoPerfil, $localExibicao)

{
    $requerido = '';
    $requeridoLabel = '';
    if ($obrigatorio == 1) {
        $requerido = 'required';
        $requeridoLabel = '<span class="text-danger">*</span>';
    }
    if (session()->fotoPerfil !== NULL) {
        $imagem = session()->fotoPerfil;
    } else {
        $imagem = 'no_image.jpg';
    }
    ?>
    <div class="col-md-1">
        <img alt="" id="fotoPerfilFormulario<?php echo $localExibicao ?>" style="width:100px">
    </div>
    <div class="col-md-3">
        <label for="<?php echo $nomeAtributoSistema ?>"><?php echo $descricaoAtributoSistema ?>: <?php echo $requeridoLabel ?></label>
        <div class="input-group mb-3">
            <input <?php echo $requerido ?> type="file" name="file" id="<?php echo $nomeAtributoSistema . $localExibicao ?>" style="height:45px;">

        </div>
    </div>
<?php
}

function informacoesComplementares($CI, $nomeAtributoSistema, $descricaoAtributoSistema, $tipo, $tamanho, $obrigatorio, $icone, $localExibicao)

{
    $requerido = '';
    $requeridoLabel = '';
    if ($obrigatorio == 1) {
        $requerido = 'required';
        $requeridoLabel = '<span class="text-danger">*</span>';
    }
?>
    <div class="row">
        <div class="col-md-12">
            <label for="<?php echo $nomeAtributoSistema ?>"><?php echo $descricaoAtributoSistema ?>: <?php echo $requeridoLabel ?></label>
            <textarea class="form-control" <?php echo $requerido ?> id="<?php echo $nomeAtributoSistema . $localExibicao ?>" name="<?php echo $nomeAtributoSistema ?>" rows="4" cols="50">
</textarea>


        </div>
    </div>
<?php
}


function senhaForm($nomeAtributoSistema, $descricaoAtributoSistema, $tipo, $tamanho, $obrigatorio, $icone, $localExibicao)
{
    $requerido = '';
    $requeridoLabel = '';
    if ($obrigatorio == 1) {
        $requerido = 'required';
        $requeridoLabel = '<span class="text-danger">*</span>';
    }
?>

    <div class="col-md-4">
        <label for="<?php echo $nomeAtributoSistema ?>"><?php echo $descricaoAtributoSistema ?>: <?php echo $requeridoLabel ?></label>
        <div class="input-group mb-3">
            <input <?php echo $requerido ?> type="<?php echo $tipo ?>" id="<?php echo $nomeAtributoSistema . $localExibicao ?>" name="<?php echo $nomeAtributoSistema ?>" class="form-control" placeholder="<?php echo $descricaoAtributoSistema ?>" maxlength="<?php echo $tamanho ?>">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="<?php echo $icone ?>"></span>
                </div>
            </div>
        </div>
    </div>
<?php
}


function constroAtributoFormulario($nomeAtributoSistema, $descricaoAtributoSistema, $tipo, $tamanho, $obrigatorio, $icone, $localExibicao = null)
{
    $requerido = '';
    $requeridoLabel = '';
    if ($obrigatorio == 1) {
        $requerido = 'required';
        $requeridoLabel = '<span class="text-danger">*</span>';
    }

    if ($tamanho !== NULL and $tamanho > 0) {
        $tamamnhoMaximo = 'maxlength="' . $tamanho . '"';
    } else {
        $tamamnhoMaximo = '';
    }

    if ($tipo == 'select' or $tipo == 'date' or $tipo == 'checkbox') {
        $tamamnhoMaximo = '';
    }

?>

    <div class="col-md-4">
        <label for="<?php echo $nomeAtributoSistema ?>"><?php echo $descricaoAtributoSistema ?>: <?php echo $requeridoLabel ?></label>
        <div class="input-group mb-3">
            <input <?php echo $requerido ?> type="<?php echo $tipo ?>" id="<?php echo $nomeAtributoSistema . $localExibicao ?>" name="<?php echo $nomeAtributoSistema ?>" class="form-control" placeholder="<?php echo $descricaoAtributoSistema ?>" <?php echo  $tamamnhoMaximo ?>>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="<?php echo $icone ?>"></span>
                </div>
            </div>
        </div>
    </div>
<?php
}



function listboxAceiteTermos($CI, $nomeAtributoSistema, $descricaoAtributoSistema, $tipo, $tamanho, $obrigatorio, $icone, $localExibicao)
{
    $requerido = '';
    $requeridoLabel = '';
    if ($obrigatorio == 1) {
        $requerido = 'required';
        $requeridoLabel = '<span class="text-danger">*</span>';
    }
?> <div class="col-md-4">
        <div class="form-group">
            <label for="checkbox<?php echo $nomeAtributoSistema ?>"> <?php echo $descricaoAtributoSistema ?>: <?php echo $requeridoLabel ?> </label>

            <div class="icheck-primary d-inline">
                <style>
                    input[type=checkbox] {
                        transform: scale(1.8);
                    }
                </style>
                <input <?php echo $requerido ?> style="margin-left:5px;" name="<?php echo $nomeAtributoSistema ?>" id="<?php echo $nomeAtributoSistema . $localExibicao ?>" type="checkbox">


            </div>
        </div>
    </div>

<?php
}

function diaSemanaCompleto($data)
{

    $dia = date('w', strtotime($data));

    if ($dia == 0) {
        return 'Domingo';
    }
    if ($dia == 1) {
        return 'Segunda-Feira';
    }
    if ($dia == 2) {
        return 'Terça-Feira';
    }
    if ($dia == 3) {
        return 'Quarta-Feira';
    }
    if ($dia == 4) {
        return 'Quinta-Feira';
    }
    if ($dia == 5) {
        return 'Sexta-Feira';
    }
    if ($dia == 6) {
        return 'Sábado';
    }
}

function diaSemanaAbreviado($data)
{

    $dia = date('w', strtotime($data));

    if ($dia == 0) {
        return 'Dom';
    }
    if ($dia == 1) {
        return 'Seg';
    }
    if ($dia == 2) {
        return 'Ter';
    }
    if ($dia == 3) {
        return 'Qua';
    }
    if ($dia == 4) {
        return 'Qui';
    }
    if ($dia == 5) {
        return 'Sex';
    }
    if ($dia == 6) {
        return 'Sab';
    }
}

function listboxAtivoForm($CI, $nomeAtributoSistema, $descricaoAtributoSistema, $tipo, $tamanho, $obrigatorio, $icone, $localExibicao)
{
    $requerido = '';
    $requeridoLabel = '';
    if ($obrigatorio == 1) {
        $requerido = 'required';
        $requeridoLabel = '<span class="text-danger">*</span>';
    }
?>
    <div class="col-md-4">
        <div class="form-group">
            <label for="checkbox<?php echo $nomeAtributoSistema ?>"> <?php echo $descricaoAtributoSistema ?>: <?php echo $requeridoLabel ?> </label>

            <div class="icheck-primary d-inline">
                <style>
                    input[type=checkbox] {
                        transform: scale(1.8);
                    }
                </style>
                <input <?php //echo $requerido --Deve ser sempre opcional 
                        ?> style="margin-left:5px;" name="<?php echo $nomeAtributoSistema ?>" id="<?php echo $nomeAtributoSistema . $localExibicao ?>" type="checkbox">
            </div>
        </div>
    </div>

<?php
}



function listboxEspecialidadesForm($CI, $nomeAtributoSistema, $descricaoAtributoSistema, $tipo, $tamanho, $obrigatorio, $icone, $localExibicao)
{

    $CI->EspecialidadesModel = new EspecialidadesModel;
    $EspecialidadesModel = $CI->EspecialidadesModel->pegaEspecialidades();

    $requerido = '';
    $requeridoLabel = '';
    if ($obrigatorio == 1) {
        $requerido = 'required';
        $requeridoLabel = '<span class="text-danger">*</span>';
    }
?>
    <div class="col-md-4">
        <label for="<?php echo $nomeAtributoSistema ?>"><?php echo $descricaoAtributoSistema ?>: <?php echo $requeridoLabel ?> </label>
        <div class="input-group mb-3">
            <select style="width:85%" <?php echo $requerido ?> id="<?php echo $nomeAtributoSistema . $localExibicao ?>" name="<?php echo $nomeAtributoSistema ?>" class="form-control" class="custom-select">
                <option value=""></option>
                <?php
                foreach ($EspecialidadesModel as $row) {

                ?>
                    <option value="<?php echo $row->codEspecialidade ?>"><?php echo $row->descricaoEspecialidade ?></option>
                <?php
                }
                ?>
            </select>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="<?php echo $icone ?>"></span>
                </div>
            </div>
        </div>
    </div>
<?php
}





function listboxPerfilPadraoForm($CI, $nomeAtributoSistema, $descricaoAtributoSistema, $tipo, $tamanho, $obrigatorio, $icone, $localExibicao)
{
    $CI->PerfilPessoasMembroModel = new PerfilPessoasMembroModel;

    $meusPerfisValidos = $CI->PerfilPessoasMembroModel->pegaMeusPerfisValidos(session()->codPessoa);


    $requerido = '';
    $requeridoLabel = '';
    if ($obrigatorio == 1) {
        $requerido = 'required';
        $requeridoLabel = '<span class="text-danger">*</span>';
    }
?>
    <div class="col-md-4">
        <label for="<?php echo $nomeAtributoSistema ?>"><?php echo $descricaoAtributoSistema ?>: <?php echo $requeridoLabel ?> </label>
        <div class="input-group mb-3">
            <select class="form-control" id="codPerfilPadrao" <?php echo $localExibicao ?> name="codPerfilPadrao">
                <?php
                foreach ($meusPerfisValidos as $perfil) {
                    echo '<option value="' . $perfil->codPerfil . '">' . $perfil->descricao . '</option>';
                }
                ?>
            </select>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="<?php echo $icone ?>"></span>
                </div>
            </div>
        </div>
    </div>
<?php
}

function listboxCargosForm($CI, $nomeAtributoSistema, $descricaoAtributoSistema, $tipo, $tamanho, $obrigatorio, $icone, $localExibicao = null)
{
    $CI->CargosModel = new CargosModel;
    $cargos = $CI->CargosModel->pegaCargos();

    $requerido = '';
    $requeridoLabel = '';
    if ($obrigatorio == 1) {
        $requerido = 'required';
        $requeridoLabel = '<span class="text-danger">*</span>';
    }
?>
    <div class="col-md-4">
        <label for="<?php echo $nomeAtributoSistema ?>"><?php echo $descricaoAtributoSistema ?>: <?php echo $requeridoLabel ?> </label>
        <div class="input-group mb-3">
            <select <?php echo $requerido ?> id="<?php echo $nomeAtributoSistema . $localExibicao ?>" name="<?php echo $nomeAtributoSistema ?>" class="form-control" class="custom-select">
                <option value=""></option>
                <?php
                foreach ($cargos as $row) {

                ?>
                    <option value="<?php echo $row->codCargo ?>"><?php echo $row->descricaoCargo ?></option>
                <?php
                }
                ?>
            </select>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="<?php echo $icone ?>"></span>
                </div>
            </div>
        </div>
    </div>
<?php
}



function listboxFuncoesForm($CI, $nomeAtributoSistema, $descricaoAtributoSistema, $tipo, $tamanho, $obrigatorio, $icone, $localExibicao)
{
    $CI->FuncoesModel = new FuncoesModel;
    $funcoes = $CI->FuncoesModel->pegaFuncoes();

    $requerido = '';
    $requeridoLabel = '';
    if ($obrigatorio == 1) {
        $requerido = 'required';
        $requeridoLabel = '<span class="text-danger">*</span>';
    }
?>
    <div class="col-md-4">
        <label for="<?php echo $nomeAtributoSistema ?>"><?php echo $descricaoAtributoSistema ?>: <?php echo $requeridoLabel ?> </label>
        <div class="input-group mb-3">
            <select style="width:85%" <?php echo $requerido ?> id="<?php echo $nomeAtributoSistema . $localExibicao ?>" name="<?php echo $nomeAtributoSistema ?>" class="form-control" class="select2">
                <option value=""></option>
                <?php
                foreach ($funcoes as $row) {

                ?>
                    <option value="<?php echo $row->codFuncao ?>"><?php echo $row->descricaoFuncao ?></option>
                <?php
                }
                ?>
            </select>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="<?php echo $icone ?>"></span>
                </div>
            </div>
        </div>
    </div>
<?php
}



function listboxPessoaSubordinacaoForm($CI, $nomeAtributoSistema, $descricaoAtributoSistema, $tipo, $tamanho, $obrigatorio, $icone, $localExibicao)
{
    $CI->PessoasModel = new PessoasModel;
    $pessoas = $CI->PessoasModel->pegaTudo();


    $requerido = '';
    $requeridoLabel = '';
    if ($obrigatorio == 1) {
        $requerido = 'required';
        $requeridoLabel = '<span class="text-danger">*</span>';
    }
?>
    <div class="col-md-4">
        <label for="<?php echo $nomeAtributoSistema ?>"><?php echo $descricaoAtributoSistema ?>: </label>
        <div class="input-group mb-3">
            <select style="width:100%" <?php echo $requerido ?> id="<?php echo $nomeAtributoSistema . $localExibicao ?>" name="<?php echo $nomeAtributoSistema ?>" class="select2">
                <option value=""></option>
                <?php
                foreach ($pessoas as $row) {

                ?>
                    <option value="<?php echo $row->codPessoa ?>"><?php echo $row->nomeExibicao ?></option>
                <?php
                }
                ?>
            </select>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="<?php echo $icone ?>"></span>
                </div>
            </div>
        </div>
    </div>
<?php
}

function listboxOrganizacoesForm($CI, $nomeAtributoSistema, $descricaoAtributoSistema, $tipo, $tamanho, $obrigatorio, $icone, $localExibicao)
{
    $CI->OrganizacoesModel = new OrganizacoesModel;
    $organicacoes = $CI->OrganizacoesModel->pegaTudo();

    $requerido = '';
    $requeridoLabel = '';
    if ($obrigatorio == 1) {
        $requerido = 'required';
        $requeridoLabel = '<span class="text-danger">*</span>';
    }
?>
    <div class="col-md-4">
        <label for="<?php echo $nomeAtributoSistema ?>"><?php echo $descricaoAtributoSistema ?>: <?php echo $requeridoLabel ?> </label>
        <div class="input-group mb-3">
            <select <?php echo $requerido ?> id="<?php echo $nomeAtributoSistema . $localExibicao ?>" name="<?php echo $nomeAtributoSistema ?>" class="form-control" class="custom-select">
                <option value=""></option>
                <?php
                foreach ($organicacoes as $row) {

                ?>
                    <option value="<?php echo $row->codOrganizacao ?>"><?php echo $row->descricao ?></option>
                <?php
                }
                ?>
            </select>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="<?php echo $icone ?>"></span>
                </div>
            </div>
        </div>
    </div>
<?php
}



function listboxMunicipiosFederacaoForm($CI, $nomeAtributoSistema, $descricaoAtributoSistema, $tipo, $tamanho, $obrigatorio, $icone, $localExibicao)
{
    $CI->MunicipiosFederacaoModel = new MunicipiosFederacaoModel;
    $MunicipiosFederacao = $CI->MunicipiosFederacaoModel->pegaMunicipiosFederacao();


    $requerido = '';
    $requeridoLabel = '';
    if ($obrigatorio == 1) {
        $requerido = 'required';
        $requeridoLabel = '<span class="text-danger">*</span>';
    }


?>
    <div class="col-md-4">
        <label for="<?php echo $nomeAtributoSistema ?>"><?php echo $descricaoAtributoSistema ?>: <?php echo $requeridoLabel ?></label>
        <div class="input-group mb-3">
            <select style="width:85%" <?php echo $requerido ?> id="<?php echo $nomeAtributoSistema . $localExibicao ?>" name="<?php echo $nomeAtributoSistema ?>">
                <option value=""></option>
                <?php
                foreach ($MunicipiosFederacao as $row) {

                ?>
                    <option value="<?php echo $row->codMunicipioFederacao ?>"><?php echo mb_strtoupper($row->municipio . '-' . $row->uf, 'utf8') ?></option>
                <?php
                }
                ?>
            </select>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="<?php echo $icone ?>"></span>
                </div>
            </div>
        </div>
    </div>
<?php
}



function listboxDepartamentosForm($CI, $nomeAtributoSistema, $descricaoAtributoSistema, $tipo, $tamanho, $obrigatorio, $icone, $localExibicao)
{

    $CI->DepartamentosModel = new DepartamentosModel;
    $departamento = $CI->DepartamentosModel->pegaDepartamentos();

    $requerido = '';
    $requeridoLabel = '';
    if ($obrigatorio == 1) {
        $requerido = 'required';
        $requeridoLabel = '<span class="text-danger">*</span>';
    }
?>
    <div class="col-md-4">
        <label for="<?php echo $nomeAtributoSistema ?>"><?php echo $descricaoAtributoSistema ?>: <?php echo $requeridoLabel ?> </label>
        <div class="input-group mb-3">
            <select style="width:86%" <?php echo $requerido ?> id="<?php echo $nomeAtributoSistema . $localExibicao ?>" name="<?php echo $nomeAtributoSistema ?>" class="form-control" class="custom-select">
                <option value=""></option>
                <?php
                foreach ($departamento as $row) {

                ?>
                    <option value="<?php echo $row->codDepartamento ?>"><?php echo $row->descricaoDepartamento ?></option>
                <?php
                }
                ?>
            </select>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="<?php echo $icone ?>"></span>
                </div>
            </div>
        </div>
    </div>
<?php
}



function listboxAtributosTipoLDAP($CI, $id = null, $hiddenOrDisabled = null)
{
    $CI->AtributosTipoLDAPModel = new AtributosTipoLDAPModel;
    $atributoTipoLDAP = $CI->AtributosTipoLDAPModel->pegaTudo();

    $botao = '
    
    <select ' . $hiddenOrDisabled . '  id="nomeAtributoLDAP" name="nomeAtributoLDAP" class="custom-select" >
    
            <option value=""></option>';
    foreach ($atributoTipoLDAP as $row) {

        $botao .= '
                <option value="' . $row->nomeAtributoLDAP . '" >' . $row->nomeAtributoLDAP . '</option>';
    }
    $botao .= "</select>";
    return $botao;
}

function listboxAtributosSistema($CI, $id = null, $hiddenOrDisabled = null)
{
    $CI->AtributosSistemaModel = new AtributosSistemaModel;
    $atributoSistema = $CI->AtributosSistemaModel->pegaTudo();

    $botao = '
    
    <select ' . $hiddenOrDisabled . '  id="nomeAtributoSistema" name="nomeAtributoSistema" class="custom-select" >
    
            <option value=""></option>';
    foreach ($atributoSistema as $row) {

        $botao .= '
                <option value="' . $row->nomeAtributoSistema . '" >' . $row->descricaoAtributoSistema . '</option>';
    }
    $botao .= "</select>";
    return $botao;
}


function listboxAtributosSistemaOrganizacao($CI, $visivelFomulario = NULL, $visivelLDAP = NULL, $obrigatorio = NULL)
{
    $CI->AtributosSistemaOrganizacaoModel = new AtributosSistemaOrganizacaoModel;
    $atributoSistemaOrganizacao = $CI->AtributosSistemaOrganizacaoModel->pegaAtributosOrganizacao($visivelFomulario, $visivelLDAP, $obrigatorio);

    $botao = '
    
    <select  id="nomeAtributoSistema" name="nomeAtributoSistema" class="custom-select" >
    
            <option value=""></option>';
    foreach ($atributoSistemaOrganizacao as $row) {

        $botao .= '
                <option value="' . $row->nomeAtributoSistema . '" >' . $row->descricaoAtributoSistema . '</option>';
    }
    $botao .= "</select>";
    return $botao;
}
function listboxModulospai($CI, $codModulo = null, $hiddenOrDisabled = null)
{
    $gerarID = rand(10, 100);
    $CI->ModulosModel = new ModulosModel;
    $modulo = $CI->ModulosModel->pegaTudo();

    $botao = '
    
    <select style="width:100%" ' . $hiddenOrDisabled . '  id="pai" name="pai">
    
            <option value="0">CATEGORIA</option>';
    foreach ($modulo as $row) {

        $botao .= '
                <option value="' . $row->codModulo . '" >' . $row->nome . '</option>';
    }
    $botao .= "</select>";
    return $botao;
}

function listboxRelatoriospai($CI, $id = null, $hiddenOrDisabled = null)
{

    $CI->RelatoriosModel = new RelatoriosModel;
    $relatorio = $CI->RelatoriosModel->pegaRelatoriosRaiz();;

    $botao = '
    
    <select ' . $hiddenOrDisabled . '  id="pai" name="pai" class="custom-select" >
    
            <option value="0">CATEGORIA</option>';
    foreach ($relatorio as $row) {

        $botao .= '
                <option value="' . $row->id . '" >' . $row->nome . '</option>';
    }
    $botao .= "</select>";
    return $botao;
}

function listboxOrganizacoes($CI, $codOrganizacao = null, $hiddenOrDisabled = null)
{

    $CI->OrganizacoesModel = new OrganizacoesModel;
    $organizacao = $CI->OrganizacoesModel->pegaOrganizacoes();

    $botao = '
    
    <select ' . $hiddenOrDisabled . '  id="codOrganizacao" name="codOrganizacao" class="custom-select" >
    
            <option value=""></option>';
    foreach ($organizacao as $row) {

        $botao .= '
                <option value="' . $row->codOrganizacao . '" >' . $row->descricao . '</option>';
    }
    $botao .= "</select>";
    return $botao;
}


function listboxProtocoloNotificacoes($CI, $tipo = null, $hiddenOrDisabled = null)
{

    $CI->ModelosNotificacaoModel = new ModelosNotificacaoModel;
    $protocolosNotificacao = $CI->ModelosNotificacaoModel->pegaProtocolos();

    $botao = '
    
    <select ' . $hiddenOrDisabled . '  id="codProtocoloNotificacao-' . $tipo . '" name="codProtocoloNotificacao" class="custom-select" >
    
            <option value=""></option>';
    foreach ($protocolosNotificacao as $row) {

        $botao .= '
                <option value="' . $row->codProtocoloNotificacao . '" >' . $row->nomeProtocoloNotificacao . '</option>';
    }
    $botao .= "</select>";
    return $botao;
}
function listboxPessoas($CI, $tipo = null, $hiddenOrDisabled = null)
{

    $CI->PessoasModel = new PessoasModel;
    $pessoa = $CI->PessoasModel->pega_pessoas();

    $botao = '
    
    <select style="width:350px"' . $hiddenOrDisabled . ' id="codPessoa' . $tipo . '"  name="codPessoa" class="select2" >
    
            <option value=""></option>';
    foreach ($pessoa as $row) {

        $botao .= '
                <option value="' . $row->codPessoa . '" >' . $row->nomeExibicao . '</option>';
    }
    $botao .= "</select>";
    return $botao;
}

function listboxMembrosPerfil($CI, $tipo = null, $hiddenOrDisabled = null)
{

    $CI->PessoasModel = new PessoasModel;
    $pessoa = $CI->PessoasModel->pega_pessoas();

    $botao = '
    
    <select style="width:350px"' . $hiddenOrDisabled . ' id="codPessoa' . $tipo . '"  name="codPessoa" class="select2" >
    
            <option value=""></option>';
    foreach ($pessoa as $row) {

        $botao .= '
                <option value="' . $row->codPessoa . '" >' . $row->nomeExibicao . '</option>';
    }
    $botao .= "</select>";
    return $botao;
}


function listboxSupervisor($CI, $tipo = null, $hiddenOrDisabled = null)
{

    $CI->PessoasModel = new PessoasModel;
    $pessoa = $CI->PessoasModel->pega_pessoas();

    $botao = '
    <select style="width:350px"' . $hiddenOrDisabled . ' id="codSupervisor' . $tipo . '"  name="codSupervisor" class="select2" >
    
            <option value=""></option>';
    foreach ($pessoa as $row) {

        $botao .= '
                <option value="' . $row->codPessoa . '" >' . $row->nomeExibicao . '</option>';
    }
    $botao .= "</select>";
    return $botao;
}

function listboxGestor($CI, $tipo = null, $hiddenOrDisabled = null)
{

    $CI->PessoasModel = new PessoasModel;
    $pessoa = $CI->PessoasModel->pega_pessoas();

    $botao = '
    
    <select style="width:350px"' . $hiddenOrDisabled . ' id="codGestor' . $tipo . '"  name="codGestor" class="select2" >
    
            <option value=""></option>';
    foreach ($pessoa as $row) {

        $botao .= '
                <option value="' . $row->codPessoa . '" >' . $row->nomeExibicao . '</option>';
    }
    $botao .= "</select>";
    return $botao;
}

function listboxStatusProjetos($CI, $codStatusProjeto = null, $hiddenOrDisabled = null)
{

    $CI->StatusProjetosModel = new StatusProjetosModel;
    $statusProjeto = $CI->StatusProjetosModel->pega_statusprojetos();

    $botao = '
    
    <select style="width:350px"' . $hiddenOrDisabled . ' id="codStatusProjeto"  name="codStatusProjeto" class="custom-select" >
    
            <option value=""></option>';
    foreach ($statusProjeto as $row) {

        $botao .= '
                <option value="' . $row->codStatusProjeto . '" >' . $row->descricaoStatusProjeto . '</option>';
    }
    $botao .= "</select>";
    return $botao;
}


function listboxTipoProjetos($CI, $codTipoProjetos = null, $hiddenOrDisabled = null)
{

    $CI->TiposProjetosModel = new TiposProjetosModel;
    $TiposProjeto = $CI->TiposProjetosModel->pega_TiposProjetos();

    $botao = '
    
    <select style="width:350px"' . $hiddenOrDisabled . ' id="codTipoProjeto"  name="codTipoProjeto" class="custom-select" >
    
            <option value=""></option>';
    foreach ($TiposProjeto as $row) {

        $botao .= '
                <option value="' . $row->codTipoProjeto . '" >' . $row->descricaoTipoProjeto . '</option>';
    }
    $botao .= "</select>";
    return $botao;
}


function dadosDemograficosPaciente()
{
}


function dadosDemograficosColaboradores($CI, $modulo = null)
{
    $nomeDepartamentoFull = NULL;

    if (session()->codDepartamento !== NULL and session()->codDepartamento !== "" and session()->codDepartamento !== " ") {
        $nomeDepartamentoFull = nomeDepartamentoFull(session()->codDepartamento);
    }
    $departamentosSelect = listboxDepartamentos($CI, 'Add', $hiddenOrDisabled = null, 1, $nomeDepartamentoFull);

    $campo = "";

    $campo .= '


    <div class="card">
    <div style="background:#6c757d3b;color:#000" class="card-header">
        <h3 style="font-size:25px" class="card-title">DADOS DEMOGRÁFICOS</h3>
    </div>
    <div class="card-body">

     
    <div class="row">
        <div class="col-md-4">
            <label for="objetivo"> Setor: <span class="text-danger">*</span> </label>
            <div class="form-group">
        ' . $departamentosSelect . '
            </div>
        </div>
    
    ';



    $moduloPadrao = '<option value="">Módulo avaliado</option>';

    if ($modulo !== NULL and $modulo !== "") {
        $moduloPadrao = '<option value="' . $modulo . '">' . $modulo . '</option>';
    }
    $campo .= '


        </div>			

        
        </div>			
    </div>
                   
                    ';





    return $campo;
}

function dadosDemograficosPacientes($CI, $modulo = null)
{

    $campo = "";

    $campo .= '


    <div class="card">
    <div style="background:#6c757d3b;color:#000" class="card-header">
        <h3 style="font-size:25px" class="card-title">DADOS DEMOGRÁFICOS</h3>
    </div>
    <div class="card-body">


    
    ';



    $campo .= '

    <div class="row">
                <div class="col-md-2">
                    <label for="objetivo"> Sexo: <span class="text-danger">*</span> </label>
                    <div class="form-group">
                        <select id="sexo" name="sexo" class="custom-select" required>
                        <option value="">Selecione</option>    
                        <option value="M">M</option>
                            <option value="F">F</option>
                        </select>	
                    </div>
                </div>

                <div class="col-md-2">
                    <label for="objetivo"> Idade: <span class="text-danger">*</span> </label>
                    <div class="form-group">
                        <select id="idade" name="idade" class="custom-select" required>
                            <option value="">Selecione</option>        
                            <option value="<20"><20</option>
                            <option value="20-29">20-29</option>
                            <option value="30-39">30-39</option>
                            <option value="40-49">40-49</option>
                            <option value="50-59">50-59</option>
                            <option value="60-69">60-69</option>
                            <option value="70-79">70-79</option>
                            <option value="80-89">80-89</option>
                            <option value=">90">>90</option>
                        </select>	
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="objetivo"> Educação: <span class="text-danger">*</span> </label>
                    <div class="form-group">
                        <select id="educacao" name="educacao" class="custom-select" required>
                        <option value="">Selecione</option>    
                        <option value="Ensino Fundamental (Completo)">Ensino Fundamental (Completo)</option>
                        <option value="Ensino Fundamental (Incompleto)">Ensino Fundamental (Incompleto)</option>
                        <option value="Ensino Médio (Completo)">Ensino Médio (Completo)</option>
                        <option value="Ensino Médio (Incompleto)">Ensino Médio (Incompleto)</option>
                        <option value="Ensino Superior (Completo)">Ensino Superior (Completo)</option>
                        <option value="Ensino Superior (Incompleto)">Ensino Superior (Incompleto)</option>
                        <option value="Pós-Graduação Nível Mestrado (Completo)">Pós-Graduação Nível Mestrado (Completo)</option>
                        <option value="Pós-Graduação Nível Mestrado (Incompleto)">Pós-Graduação Nível Mestrado (Incompleto)</option>
                        <option value="Pós-Graduação Nível Doutorado (Completo)">Pós-Graduação Nível Doutorado (Completo)</option>
                        <option value="Pós-Graduação Nível Doutorado (Incompleto)">Pós-Graduação Nível Doutorado (Incompleto)</option>
                        </select>	
                    </div>
                </div>


                        <div class="col-md-3">
                            <label for="objetivo">Experiência com PC/celular: <span class="text-danger">*</span> </label>
                            <div class="form-group">
                                <select id="experienciaTecnologia" name="experienciaTecnologia" class="custom-select" required>
                                    <option value="">Selecione</option>    
                                    <option value="Menos de 2 Anos">< Menos de 2 Anos</option>
                                    <option value="Entre 2 e 5 anos">Entre 2 e 5 anos</option>
                                    <option value="Entre 5 e 10 anos">Entre 5 e 10 anos</option>
                                    <option value="Entre 10 e 20 anos">Entre 10 e 20 anos</option>
                                    <option value="Entre 20 e 30 anos">Entre 20 e 30 anos</option>
                                    <option value="Mais de 30 Anos">< Mais de 30 Anos</option>
                                    </select>	
                            </div>
                        </div>
                </div>

                <div class="row">
                            <div class="col-md-4">
                                <label for="objetivo"> Experiência com este sistema: <span class="text-danger">*</span> </label>
                                <div class="form-group">
                                    <select id="experienciaProduto" name="experienciaProduto" class="custom-select" required>
                                    <option value="">Selecione</option>    
                                    <option value="Entre de 6 meses">< menos de 6 meses</option>
                                    <option value="Entre 6 meses e 1 ano">entre 6 meses e 1 ano</option>
                                    <option value="Entre 1 ano e 2 anos">Entre 1 ano e 2 ano</option>
                                    <option value="Entre 2 ano e 4 anos">Entre 2 ano e 4 anos</option>
                                    <option value="Mais de 5 anos">Mais de 5 anos</option>
                                    </select>	
                                </div>
                            </div>
                    </div>                         
                   
                
        </div>			
    </div>
                   
                    ';





    return $campo;
}
function listboxDepartamentos($CI, $tipo = null, $hiddenOrDisabled = null, $obrigatorio = null, $default = null)
{
    $CI->DepartamentosModel = new DepartamentosModel;
    $departamento = $CI->DepartamentosModel->pegaDepartamentos();

    $required = "";
    if ($obrigatorio !== NULL) {
        $required = "required";
    }
    if ($default == NULL) {
        $opcaoPadrao = '<option value="">Informe seu setor</option>';
    } else {
        $opcaoPadrao = '<option value="' . $default . '">' . $default . '</option>';
    }


    $botao = '
    <div class="row">
		<div class="col-md-12">
			<div class="form-group">
    <select style="width:100%"' . $hiddenOrDisabled . ' id="codDepartamento' . $tipo . '"  name="codDepartamento" class="custom-select" ' . $required . '>
    
            ' . $opcaoPadrao;
    foreach ($departamento as $row) {

        $botao .= '
                <option value="' . $row->descricaoDepartamento . '" >' . $row->descricaoDepartamento . '</option>';
    }
    $botao .= "</select>
             </div>
         </div>
    </div>
    
    ";
    return $botao;
}


function listaDropDownEscalas()
{


    $EscalasModel = new EscalasModel;

    $result = $EscalasModel->listaDropDownEscalas();

    if ($result !== NULL) {


        print json_encode($result);
    } else {

        throw new \CodeIgniter\Exceptions\PageNotFoundException();
    }
}
function seletorEscala($codEscala = NULL)
{

    $EscalasModel = new EscalasModel;

    $response = array();

    $escalas = $EscalasModel->todasEscalas();



    $html = '

    <div class="row">
        <div class="col-md-4">
            <form target="_self" method="get" action="#">
                <div class="row">
                <input type="hidden" id="autorizacao" name="autorizacao" value="364f5b5504700506f2222e16cd2d7a0004">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="codEscala"> Seleciona a Escala: <span class="text-danger">*</span> </label>
                            <select id="codEscala" name="codEscala" class="custom-select">
                                <option value=""></option>';


    foreach ($escalas as $escala) {
        $default = "";
        if ($codEscala == $escala->codEscala) {
            $default = 'selected="selected"';
        }
        $html .= '<option value="' . $escala->codEscala . '" ' . $default . '>' . $escala->descricao . '</option>';
    }

    $html .= '
            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                    <label for="codEscala"> &nbsp;</label>
                        <button type="submit" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Pesquisar" title="Pesquisar">Pesquisar</button>
                    </div>
                </div>
                
            </form>
        </div>

    </div>

    
    ';
    return $html;
}

function previsaoEscala($codEscala = NULL)
{

    $EscalasModel = new EscalasModel;

    $response = array();

    if ($codEscala == NULL) {
        exit();
    }
    $dadosEscala = $EscalasModel->dadosEscala($codEscala);
    $dataLimiteLiberacao = $dadosEscala->dataLimiteLiberacao;
    $html = '';


    $dataUltimaLiberacao = $dadosEscala->dataUltimaLiberacao;
    $dataLimiteLiberacao = $dadosEscala->dataLimiteLiberacao;
    $modificadoPor = $dadosEscala->modificadoPor;


    $previsaoEscala = $EscalasModel->mostrarPrevisaoEscala($codEscala, $dataLimiteLiberacao);

    if ($dataLimiteLiberacao !== NULL) {
        $html .= '<div style="margin-left:15px;margin-right:15px" class="row">
                    Escala liberada até o dia ' . date('d/m/Y', strtotime($dataLimiteLiberacao)) . '. Última atualização em ' . date('d/m/Y H:i', strtotime($dataUltimaLiberacao)) . ', Por ' . $modificadoPor . '.
                    </div>
                    ';
    }

    $html .= '<div class="row">';

    $btnTroca = array();
    foreach ($previsaoEscala as $previsao) {

        if ($previsao->codTipoEscala == 1) {
            $colorBtn = 'dark';
        } else {
            $colorBtn = 'danger';
        }
        $showBtnTroca = '';

        $colorDestaque = '';




        //TROCAS QUE ACONPANHAM A DATA

        $verificaTrocaAcompanhamData = $EscalasModel->verificaTrocaAcompanhamData($previsao->dataPrevisao, $previsao->codEscala);

        if (!empty($verificaTrocaAcompanhamData)) {
            //TEM TROCA
            $nomeExibicao = '
				<div style="font-size:12px"><s>' . $previsao->nomeExibicao . '</s></div>
				<div style="color:yellow"><img style="width:15px;" src="' . base_url() . '/imagens/atencao.gif"><b>' . $verificaTrocaAcompanhamData->nomeExibicaoEntra . '</b></div>
				';

            //VERIFICA SE MUDANÇA NA ESCALA O USUÁRIO QUE SUBSTITUI DEVE SEGUIR A PESSOA ORIGINAL DA TROCA

            if ($verificaTrocaAcompanhamData->tipoTroca == 2) {
                if ($verificaTrocaAcompanhamData->codPessoa !== $previsao->codPessoa) {

                    $nomeExibicao = '
						<div>' . $previsao->nomeExibicao . '</div>
						';
                }
            }
        } else {
            $nomeExibicao = $previsao->nomeExibicao;
        }

        $vermelha = $EscalasModel->verificaSeVermelha(strtotime($previsao->dataPrevisao));
        $ultimoServico = $EscalasModel->ultimoServico($codEscala, $previsao->codPessoa);

        if ($vermelha !== true) {
            $dataUltimoServico = date('d/m', strtotime($ultimoServico->dataUltimoEscalacaoPreta));
        } else {

            $dataUltimoServico = date('d/m', strtotime($ultimoServico->dataUltimoEscalacaoVermelha));
        }


        $html .= '<div class="col-md-2">';
        $html .= '
			<button  ' . $showBtnTroca . ' style="height: 120px;color: ' . $colorDestaque . ' !important; border-width: 3px;border-color: ' . $colorDestaque . ';margin-top:15px;font-size:12px" type="button" data-toggle="tooltip" data-placement="top" title="Último Serviço em: ' . $dataUltimoServico . '" class="btn btn-block bg-gradient-' . $colorBtn . ' btn-lg">
				<div class="text-center"><h5>' . diaSemanaAbreviado($previsao->dataPrevisao) . ' (' . date('d/m', strtotime($previsao->dataPrevisao)) . ')</h5></div>
				<div class="text-center"><h6>' . $nomeExibicao . '</h6></div>
			</button>
			
			';
        $html .= '</div>';
    }
    $html .= '</div>';



    //TROCAS
    $trocas = $EscalasModel->listaTrocasFuturas($codEscala);

    $tabelaTrocas = '<div class="row">';

    foreach ($trocas as $troca) {



        $tabelaTrocas .= '
            <div class="col-md-12"> Em ' . date('d/m', strtotime($troca->dataPrevisao)) . ', ' . $troca->nomeExibicaoEntra . ' assume o serviço de ' . $troca->nomeExibicaoSai . '. <b style="color:red"> |' . $troca->observacoes . '</b></div>';
    }
    $tabelaTrocas .= '</div>';



    if (!empty($trocas)) {

        $html .= '
    <div style="margin-left:15px;" class="row">
        
             <div style="margin-top:50px; font-size:30px" class="col-md-12">TROCAS PREVISTAS</div>
            
        ' . $tabelaTrocas . '    
    </div>';
    }



    //AFASTAMENTOS DO SERVIÇO
    $afastamentos = $EscalasModel->membrosAfastadosFuturo($codEscala);

    $tabelaAfastamentos = '<div class="row">';
    foreach ($afastamentos as $afastamento) {

        if ($afastamento->afastamentoIndeterminado == 1) {
            $inicio = date('d/m', strtotime($afastamento->dataInicioAfastamento));
            $termino = 'Indeterminado';
        } else {

            $inicio = date('d/m', strtotime($afastamento->dataInicioAfastamento));
            $termino = date('d/m', strtotime($afastamento->dataEncerramentoAfastamento));
        }

        if ($termino !== NULL) {

            if ($afastamento->afastamentoIndeterminado == 1) {
                $pronto = 'Nao definido';
            } else {

                $pronto = date('d/m', strtotime($afastamento->dataEncerramentoAfastamento) + 86400);
            }
        } else {
            $pronto = '';
        }


        $tabelaAfastamentos .= '
        <div class="col-md-12"> ' . $afastamento->membro . ' ' . $afastamento->statusServico . ' de ' . $inicio . ' até ' . $termino . ', <b style="color:red">Pronto:' . $pronto . '</b></div>
        ';
    }
    $tabelaAfastamentos .= '</div>';

    if (!empty($afastamentos)) {


        $html .= '<div style="margin-left:15px;" class="row">
    
     <div style="margin-top:50px; font-size:30px" class="col-md-12">AFASTAMENTOS DO SERVIÇO</div>
        
    ' . $tabelaAfastamentos . '    
    </div>
    
    ';
    }

    return $html;
}

function listboxUnidadesFaturamento($CI, $tipo = null, $hiddenOrDisabled = null)
{
    $CI->DepartamentosModel = new DepartamentosModel;
    $departamento = $CI->DepartamentosModel->listaDropDownUnidadesFaturamentoHelper();


    $botao = '    
    <span>Centro de Custo:</span><select ' . $hiddenOrDisabled . ' id="codDepartamentoFiltro' . $tipo . '"  name="codDepartamento" class="custom-select" >
    
            <option value=""></option>';
    foreach ($departamento as $row) {

        $botao .= '
                <option value="' . $row->id . '" >' . $row->text . '</option>';
    }
    $botao .= "</select>";
    return $botao;
}




function listboxUnidadesInternacao($CI, $tipo = null, $hiddenOrDisabled = null)
{
    $CI->DepartamentosModel = new DepartamentosModel;
    $departamento = $CI->DepartamentosModel->listaDropDownUnidadesInternacaoHelper();


    $botao = '
    
    <span>Centro de Custo:</span><select ' . $hiddenOrDisabled . ' id="codDepartamentoFiltro' . $tipo . '"  name="codDepartamento" class="custom-select" >
    
            <option value=""></option>';
    foreach ($departamento as $row) {

        $botao .= '
                <option value="' . $row->id . '" >' . $row->text . '</option>';
    }
    $botao .= "</select>";
    return $botao;
}


function listboxMunicipiosFederacao($CI, $tipo = null, $hiddenOrDisabled = null)
{
    $CI->MunicipiosFederacaoModel = new MunicipiosFederacaoModel;
    $MunicipiosFederacao = $CI->MunicipiosFederacaoModel->pegaMunicipiosFederacao();


    $botao = '
    
    <select required style="width:100%"' . $hiddenOrDisabled . ' id="codMunicipioFederacao' . $tipo . '"  name="codMunicipioFederacao" >
    
            <option value=""></option>';
    foreach ($MunicipiosFederacao as $row) {

        $botao .= '
                <option value="' . $row->codMunicipioFederacao . '" >' . $row->municipio . ' - ' . $row->uf . '</option>';
    }
    $botao .= "</select>";
    return $botao;
}

function listboxEspecialidades($CI, $tipo = null, $hiddenOrDisabled = null)
{

    $CI->EspecialidadesModel = new EspecialidadesModel;
    $EspecialidadesModel = $CI->EspecialidadesModel->pegaEspecialidades();

    $botao = '
    
    <select ' . $hiddenOrDisabled . '  id="codEspecialidade" name="codEspecialidade" class="custom-select" >
    
            <option value=""></option>';
    foreach ($EspecialidadesModel as $row) {

        $botao .= '
                <option value="' . $row->codEspecialidade . '" >' . $row->descricaoEspecialidade . '</option>';
    }
    $botao .= "</select>";
    return $botao;
}



function listboxFuncoes($CI, $tipo = null, $hiddenOrDisabled = null)
{

    $CI->FuncoesModel = new FuncoesModel;
    $funcoes = $CI->FuncoesModel->pegaFuncoes();

    $botao = '
    
    <select style="width:85% " ' . $hiddenOrDisabled . '  id="codFuncao' . $tipo . '" name="codFuncao" class="select2" >
    
            <option value=""></option>';
    foreach ($funcoes as $row) {

        $botao .= '
                <option value="' . $row->codFuncao . '" >' . $row->descricaoFuncao . '</option>';
    }
    $botao .= "</select>";
    return $botao;
}

function listboxDepartamentos_pai($CI, $codOrganizacao = null, $hiddenOrDisabled = null)
{

    $CI->DepartamentosModel = new DepartamentosModel;
    $Departamentos = $CI->DepartamentosModel->pegaDepartamentos();

    $botao = '
    
    <select style="width:300px" ' . $hiddenOrDisabled . '  id="paiDepartamento" name="paiDepartamento" class="custom-select" >
    
            <option value=""></option>';
    foreach ($Departamentos as $row) {

        $botao .= '
                <option value="' . $row->codDepartamento . '" >' . $row->descricaoDepartamento . '</option>';
    }
    $botao .= "</select>";
    return $botao;
}

function listboxDepartamentos_transferir($CI, $codOrganizacao = null, $hiddenOrDisabled = null)
{

    $CI->DepartamentosModel = new DepartamentosModel;
    $Departamentos = $CI->DepartamentosModel->pegaDepartamentos();

    $botao = '
    
    <select style="width:300px" ' . $hiddenOrDisabled . '  id="paiDepartamento" name="paiDepartamento" class="select2" >
    
            <option value=""></option>';
    foreach ($Departamentos as $row) {

        $botao .= '
                <option value="' . $row->codDepartamento . '" >' . $row->descricaoDepartamento . '</option>';
    }
    $botao .= "</select>";
    return $botao;
}




function listboxTipoLDAP($CI, $tipo = null, $hiddenOrDisabled = null)
{
    $CI->TipoLDAPModel = new TipoLDAPModel;
    $TipoLDA = $CI->TipoLDAPModel->pegaTudo();


    $botao = '
    
    <select style="width:350px"' . $hiddenOrDisabled . ' id="codTipoLDAP' . $tipo . '"  name="codTipoLDAP" class="custom-select" >
    
            <option value=""></option>';
    foreach ($TipoLDA as $row) {

        $botao .= '
                <option value="' . $row->codTipoLDAP . '" >' . $row->nomeTipoLDAP . '</option>';
    }
    $botao .= "</select>";
    return $botao;
}

function listboxProtocolosRede($CI, $tipo = null, $hiddenOrDisabled = null)
{
    $CI->ProtocolosRedeModel = new ProtocolosRedeModel;
    $ProtocolosRede = $CI->ProtocolosRedeModel->pegaTudo();


    $botao = '
    
    <select style="width:350px"' . $hiddenOrDisabled . ' id="codProtocoloRede' . $tipo . '"  name="codProtocoloRede" class="custom-select" >
    
            <option value=""></option>';
    foreach ($ProtocolosRede as $row) {

        $botao .= '
                <option value="' . $row->codProtocoloRede . '" >' . $row->nomeProtocoloRede . '</option>';
    }
    $botao .= "</select>";
    return $botao;
}

function nomeMesAbreviado($valor)
{

    if ($valor == '01') {
        return 'Jan';
    }
    if ($valor == '02') {
        return 'Fev';
    }
    if ($valor == '03') {
        return 'Mar';
    }
    if ($valor == '04') {
        return 'Abr';
    }
    if ($valor == '05') {
        return 'Mai';
    }
    if ($valor == '06') {
        return 'Jun';
    }
    if ($valor == '07') {
        return 'Jul';
    }
    if ($valor == '08') {
        return 'Ago';
    }
    if ($valor == '09') {
        return 'Set';
    }
    if ($valor == '10') {
        return 'Out';
    }
    if ($valor == '11') {
        return 'Nov';
    }
    if ($valor == '12') {
        return 'Dez';
    }
}



function nomeMesPorExtenso($valor)
{

    if ($valor == '01') {
        return 'Janeiro';
    }
    if ($valor == '02') {
        return 'Fevereiro';
    }
    if ($valor == '03') {
        return 'Março';
    }
    if ($valor == '04') {
        return 'Abril';
    }
    if ($valor == '05') {
        return 'Maio';
    }
    if ($valor == '06') {
        return 'Junho';
    }
    if ($valor == '07') {
        return 'Julho';
    }
    if ($valor == '08') {
        return 'Agosto';
    }
    if ($valor == '09') {
        return 'Setembro';
    }
    if ($valor == '10') {
        return 'Outubro';
    }
    if ($valor == '11') {
        return 'Novembro';
    }
    if ($valor == '12') {
        return 'Dezembro';
    }
}
function listboxAtivo($CI, $codOrganizacao = null, $hiddenOrDisabled = null)
{

    $CI->DepartamentosModel = new DepartamentosModel;
    $Departamentos = $CI->DepartamentosModel->pegaDepartamentos();

    $botao = '
    
    <select style="width:300px" ' . $hiddenOrDisabled . '  id="ativo" name="ativo" class="select2" >
    
            <option value=""></option>';
    foreach ($Departamentos as $row) {

        $botao .= '
                <option value="' . $row->ativo . '" >' . $row->descricaoDepartamento . '</option>';
    }
    $botao .= "</select>";
    return $botao;
}

function listboxPerfis($CI, $codOrganizacao = null, $hiddenOrDisabled = null)
{

    $CI->PerfisModel = new PerfisModel;
    $perfis = $CI->PerfisModel->pega_todasPerfis();

    $botao = '
    
    <select style="width:300px" ' . $hiddenOrDisabled . '  id="codPerfil" name="codPerfil" class="select2" >
    
            <option value="0">Nenhum</option>';
    foreach ($perfis as $row) {

        $botao .= '
                <option value="' . $row->codPerfil . '" >' . $row->descricao_perfil . '</option>';
    }
    $botao .= "</select>";
    return $botao;
}
function listboxTimezone($CI, $codTimezone = null, $hiddenOrDisabled = null)
{

    $CI->OrganizacoesModel = new OrganizacoesModel;
    $timezone = $CI->OrganizacoesModel->pegaTimezones();

    $botao = '
        
        <select style="width:300px; margin-left:5px" ' . $hiddenOrDisabled . '  id="codTimezone" name="codTimezone" class="select2" >
        
                <option value=""></option>';
    foreach ($timezone as $row) {
        if ($row->codTimezone == $codTimezone) {
            $botao .= '<option selected value="' . $row->codTimezone . '" >' . $row->nome . '</option>';
        } else {
            $botao .= '<option value="' . $row->codTimezone . '" >' . $row->nome . '</option>';
        }
    }
    $botao .= "</select>";
    return $botao;
}


function random_str(
    int $length = 64,
    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%&*'
): string {
    if ($length < 1) {
        throw new \RangeException("Length must be a positive integer");
    }
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces[] = $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}

function pesquisa($CI, $modulo = NULL)
{
    $CI->QuestionariosModel = new QuestionariosModel;
    $timezone = $CI->OrganizacoesModel->pegaTimezones();
}


function dadosTcle($termo = NULL, $nomeCompleto, $cpf)
{

    $html = "";

    $response = array();

    $response['success'] = true;
    $html .= '<div  class="row">Eu, ' . $nomeCompleto . ', CPF nº ' . $cpf . ',';
    $html .= '        
        ' . $termo . '
                </div>';

    $dataExtenso = session()->cidade . '-' . session()->uf . ', ' . date('d', strtotime(date('Y-m-d'))) . ' de ' . nomeMesPorExtenso(date('m', strtotime(date('Y-m-d')))) . ' de ' . date('Y', strtotime(date('Y-m-d'))) . '.';

    $html .=
        '
                    <div style="margin-top:30px; margin-bottom:30px;" class="row">
                      <div class="col-md-12 d-flex justify-content-end">
                    ' . $dataExtenso . '
                      </div>
                    </div>
            
                    <div style="margin-top:30px; margin-bottom:0px;" class="row">
                      <div class="col-md-12 d-flex justify-content-center">
                         ______________________________________________
                        </div>
                    </div>

                    <div style="margin-bottom:0px;font-weight: bold; font-size:15px" class="row">
                        <div class="col-md-12 d-flex justify-content-center">
                        ' . mb_strtoupper($nomeCompleto, 'utf-8') . '
                        </div>
                    </div>
                    <div style=" font-size:14px" class="row">
                      <div class="col-md-12 d-flex justify-content-center">
                         Participante
                        </div>
                    </div>

                <div style="margin-top:30px; margin-bottom:0px;" class="row">
                    <div class="col-md-12 d-flex justify-content-center">
                    ______________________________________________
                      </div>
                  </div>

                 <div style="margin-top:0px; margin-bottom:0px;font-weight: bold; font-size:15px" class="row">
                    <div class="col-md-12 d-flex justify-content-center">
                     EMANUEL PEIXOTO VICENTE
                    </div>
                </div>
                <div style=" font-size:14px" class="row">
                  <div class="col-md-12 d-flex justify-content-center">
                     Pesquisador
                    </div>
                </div>
                
             <div style="margin-top:30px; margin-bottom:0px;" class="row">
                <div class="col-md-12 d-flex justify-content-center">
                ______________________________________________
                  </div>
              </div>
            <div style="margin-top:30px; margin-bottom:0px;font-weight: bold; font-size:15px" class="row">
                <div class="col-md-12 d-flex justify-content-center">
             
                </div>
            </div>
            <div style=" font-size:14px" class="row">
              <div class="col-md-12 d-flex justify-content-center">
                 Testemunha
                </div>
            </div>        



        ';

    //return $this->response->setJSON($response);
    return $html;
}
