<?php
//É NECESSÁRIO EM TODAS AS VIEWS

$codOrganizacao = session()->codOrganizacao;


if (session()->mensagem_sucesso !== NULL) {
    alerta(session()->mensagem_sucesso, 'success');
}
if (session()->mensagem_erro !== NULL) {
    alerta(session()->mensagem_erro, 'error');
}
if (session()->mensagem_informacao !== NULL) {
    alerta(session()->mensagem_informacao, 'info');
}
if (session()->mensagem_alerta !== NULL) {
    alerta(session()->mensagem_alerta, 'warning');
}
if ($_GET["PAASSEX"] !== NULL) {
    $PAASSEX = 1;
}

$euMesmo = session()->codPessoa;
$meuDepartamento = session()->codDepartamento;



if (session()->filtroRequisicao !== NULL) {
    $codTipoRequisicao = session()->filtroRequisicao['codTipoRequisicao'];
    $codClasseRequisicao = session()->filtroRequisicao['codClasseRequisicao'];
    $codStatusRequisicao = session()->filtroRequisicao['codStatusRequisicao'];
    $periodoRequisicoes = session()->filtroRequisicao['periodoRequisicoes'];
    $palarvaChave = session()->filtroRequisicao['palarvaChave'];
    $codResponsavel = session()->filtroRequisicao['codResponsavel'];
    $codFornecedor = session()->filtroRequisicao['codFornecedor'];
}


print_r(session()->filtroRequisicao);


/*
$dados = array();
$file = fopen(WRITEPATH . '../arquivos/orcamentos/teste.csv', 'r');
while (($line = fgetcsv($file)) !== FALSE) {
    //$line is an array of the csv elements
    array_push($dados, $line);
}
fclose($file);

print count($dados);

for ($x = 0; $x <= count($dados); $x++) {

    //sequencial,id,nome,tipo,unidade
    if ($dados[$x][0] >= 1) {
        print $dados[$x][1] . '' . $dados[$x][2] . '' . $dados[$x][3] . '' . $dados[$x][4];
    }

    print '<hr>';
}

*/

?>

<style>
    .modal {
        overflow: auto !important;
    }

    #minhaFoto {
        width: 160px;
        height: 125px;
        border: 1px solid black;
    }

    #fotoPerfilCadastro {
        width: 160px;
        height: 125px;
        border: 1px solid black;
    }

    .select2-container {
        z-index: 100000;
    }


    .swal2-container {
        z-index: 9999999;
    }
</style>


<div style="visibility:hidden" id="setEstilo"></div>
<link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/summernote/summernote-bs4.min.css">

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6 mt-2">
                            <h3 style="font-size:30px;font-weight: bold;" class="card-title">Requisições</h3>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="itensPAASSEx" onclick="itensPAASSEx()" title="Adicionar">itensPAASSEx</button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addrequisicao()" title="Adicionar">Adicionar</button>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">

                    <div class="col-12 col-sm-12">
                        <div class="card card-primary card-tabs">
                            <div class="card-header p-0 pt-1">
                                <ul class="nav nav-tabs" id="requisicoestab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="requisicoeshome-tab" data-toggle="pill" href="#requisicoeshome" role="tab" aria-controls="requisicoeshome" aria-selected="true">Caixa de Entrada</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="requisicoesmessages-tab" data-toggle="pill" href="#requisicoesmessages" role="tab" aria-controls="requisicoesmessages" aria-selected="false">Em Elaboração</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="requisicoesprofile-tab" data-toggle="pill" href="#requisicoesprofile" role="tab" aria-controls="requisicoesprofile" aria-selected="false">Caixa de Saída</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="requisicoestabContent">
                                    <div class="tab-pane fade show active" id="requisicoeshome" role="tabpanel" aria-labelledby="requisicoeshome-tab">
                                        Caixa de Entrada
                                    </div>
                                    <div class="tab-pane fade" id="requisicoesmessages" role="tabpanel" aria-labelledby="requisicoesmessages-tab">
                                        Em Elaboração
                                    </div>
                                    <div class="tab-pane fade" id="requisicoesprofile" role="tabpanel" aria-labelledby="requisicoesprofile-tab">
                                        Caixa de Saída
                                    </div>
                                </div>
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>

                    <div id="accordion">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h4 class="card-title w-100">
                                    <a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapseOne" aria-expanded="false">
                                        FILTRAR <i style="margin-left:5px" class="right fas fa-angle-down"></i>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="collapse" data-parent="#accordion">
                                <div class="card-body col-md-12">


                                    <form id="formFiltro" class="pl-3 pr-3">
                                        <div class="row">
                                            <input type="hidden" id="<?php echo csrf_token() ?>formFiltro" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                                            <input type="hidden" id="codPessoaPreferenciaFiltro" name="codPessoa" class="form-control" placeholder="Código" value="<?php echo session()->codPessoa ?>" maxlength="11" required>

                                        </div>

                                        <style>
                                            .select2-container--default .select2-selection--multiple .select2-selection__choice {
                                                background-color: #007bff;
                                            }
                                        </style>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Tipo</label>
                                                    <select id="codTipoFiltro" name="arrayTipo[]" class="select2" multiple="multiple" data-placeholder="Selecione uma opção" style="width: 100%;">

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Classe</label>
                                                    <select id="codClasseFiltro" name="arrayCodClasse[]" class="select2" multiple="multiple" data-placeholder="Selecione uma opção" style="width: 100%;">

                                                    </select>
                                                </div>
                                            </div>



                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Período</label>
                                                    <select id="periodoRequisicoes" name="periodoRequisicoes" class="select2" required>
                                                        <option value=0>Todos</option>
                                                        <option value=7>7 dias</option>
                                                        <option value=30>30 dias</option>
                                                        <option value=60>60 dias</option>
                                                        <option value=90>90 dias</option>
                                                        <option value=120>120 dias</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">


                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <select id="codStatusRequisicaoFiltro" name="arrayStatus[]" class="select2" multiple="multiple" data-placeholder="Selecione uma opção" style="width: 100%;">

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Técnico Responsável</label>
                                                    <select id="codResponsavelFiltro" name="codResponsavel" class="select2" data-placeholder="Selecione uma opção" style="width: 100%;">

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Departamento</label>
                                                    <select id="codDepartamentoFiltro" name="arrayCodDepartamento[]" class="select2" multiple="multiple" data-placeholder="Selecione uma opção" style="width: 100%;">

                                                        <option value="0">TODOS</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Palavra Chave</label>
                                                <div class="form-group">
                                                    <input type="text" id="palavraChave" name="palarvaChave" class="form-control" placeholder='Separada por ponto e vírgula (;) exemplo: computador;estabilizador; mouse'>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Fornecedor</label>
                                                    <select id="codFornecedorFiltro" name="codFornecedor" class="select2" data-placeholder="Selecione uma opção" style="width: 100%;">

                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-group text-center">
                                            <button onclick="buscarRequisicoes()" type="button" class="btn btn-xs btn-success">BUSCAR</button>
                                            <button onclick="limparRequisicoes()" type="button" class="btn btn-xs btn-primary">REDEFINIR FILTRO</button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <table id="data_tablerequisicao" class="table table-striped table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Requisição</th>
                                <th>Descricao</th>
                                <th>Departamento</th>
                                <th>Informações</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- Add modal content -->
<div id="requisicaoAddModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-center p-3">
                <h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Requisições</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="requisicaoAddForm" class="pl-3 pr-3">
                    <input type="hidden" id="<?php echo csrf_token() ?>requisicaoAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                    <div class="row">
                        <input type="hidden" id="codRequisicao" name="codRequisicao" class="form-control" placeholder="CodRequisicao" maxlength="11" required>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="descricao"> Descrição: <span class="text-danger">*</span> </label>
                                <textarea cols="40" rows="5" id="descricao" name="descricao" class="form-control" placeholder="Descricao" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="codClasseRequisicao"> Classe: <span class="text-danger">*</span> </label>
                                <select id="codClasseRequisicao" name="codClasseRequisicao" class="custom-select" required>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="codDepartamento"> Departamento: <span class="text-danger">*</span> </label>
                            <select id="codDepartamento" name="codDepartamento" class="custom-select" required>
                                <option value=""></option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="codTipoRequisicao"> Tipo Requisição: <span class="text-danger">*</span>
                                </label>
                                <select id="codTipoRequisicao" name="codTipoRequisicao" class="custom-select" required>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dataRequisicao"> Data Requisição: <span class="text-danger">*</span>
                                </label>
                                <input type="date" id="dataRequisicao" name="dataRequisicao" value="<?php echo date('Y-m-d') ?>" class="form-control" dateISO="true" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="matSau"> Tem material de Saúde? <span class="text-danger">*</span> </label>
                                <select id="matSau" name="matSau" class="custom-select" required>
                                    <option value=""></option>
                                    <option value="1">SIM</option>
                                    <option value="0">NÂO</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!--<div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="carDisp"> CarDisp: <span class="text-danger">*</span> </label>
                                <select id="carDisp" name="carDisp" class="custom-select" required>
                                    <option value=""></option>
                                    <option value="1">SIM</option>
                                    <option value="0">NÂO</option>
                                </select>
                            </div>
                        </div>
                    </div> -->

                    <div class="form-group text-center">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="requisicaoAddForm-btn">Adicionar</button>
                            <button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


<!-- Add modal content -->
<div id="requisicaoEditModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-center p-3">
                <h4 id="requisicaoEditModalTitulo" class="modal-title text-white"></h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="col-12 col-sm-12">
                    <div class="card card-primary card-tabs">
                        <div class="card-header p-0 pt-1">
                            <ul class="nav nav-tabs" id="requisicao-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="requisicao-Geral-tab" data-toggle="pill" href="#requisicao-Geral" role="tab" aria-controls="requisicao-Geral" aria-selected="true">Geral</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="requisicao-Itens-tab" data-toggle="pill" href="#requisicao-Itens" role="tab" aria-controls="requisicao-Itens" aria-selected="false">Itens</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="requisicao-InformacoesComplementares-tab" data-toggle="pill" href="#requisicao-InformacoesComplementares" role="tab" aria-controls="requisicao-InformacoesComplementares" aria-selected="false">Informações Complementares</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" id="requisicao-Documentos-tab" data-toggle="pill" href="#requisicao-Documentos" role="tab" aria-controls="requisicao-Documentos" aria-selected="false">Documentos</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="requisicao-Anexos-tab" data-toggle="pill" href="#requisicao-Anexos" role="tab" aria-controls="requisicao-Anexos" aria-selected="false">Anexos</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="requisicao-Despachos-tab" data-toggle="pill" href="#requisicao-Despachos" role="tab" aria-controls="requisicao-Despachos" aria-selected="false">Histórico de Ações/Despachos</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="requisicao-tabContent">
                                <div class="tab-pane fade show active" id="requisicao-Geral" role="tabpanel" aria-labelledby="requisicao-Geral-tab">
                                    <form id="requisicaoEditForm" class="pl-3 pr-3">
                                        <input type="hidden" id="<?php echo csrf_token() ?>requisicaoEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                                        <div class="row">
                                            <input type="hidden" id="codRequisicao" name="codRequisicao" class="form-control" placeholder="CodRequisicao" maxlength="11" required>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="descricao"> Descrição: <span class="text-danger">*</span> </label>
                                                    <textarea cols="40" rows="5" id="descricao" name="descricao" class="form-control" placeholder="Descricao" required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="codClasseRequisicao"> Classe: <span class="text-danger">*</span> </label>
                                                    <select id="codClasseRequisicao" name="codClasseRequisicao" class="custom-select" required>
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="codDepartamento"> Departamento: <span class="text-danger">*</span> </label>
                                                <select disabled="disabled" id="codDepartamento" name="codDepartamento" class="custom-select" required>
                                                    <option value=""></option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="codTipoRequisicao"> Tipo Requisição: <span class="text-danger">*</span> </label>
                                                    <select id="codTipoRequisicao" name="codTipoRequisicao" class="custom-select" required>
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="dataRequisicao"> Data Requisição: <span class="text-danger">*</span> </label>
                                                    <input type="date" id="dataRequisicao" name="dataRequisicao" class="form-control" dateISO="true" required>
                                                </div>
                                            </div>
                                            <!--<div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="valorTotal"> Valor Total: </label>
                                                    <input type="number" id="valorTotal" name="valorTotal"
                                                        class="form-control" placeholder="0.00">
                                                </div>
                                            </div>-->
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="matSau"> Tem material de Saúde? <span class="text-danger">*</span>
                                                    </label>
                                                    <select id="matSau" name="matSau" class="custom-select" required>
                                                        <option value=""></option>
                                                        <option value="1">SIM</option>
                                                        <option value="0">NÂO</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!--<div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="carDisp"> CarDisp: <span class="text-danger">*</span>
                                                    </label>
                                                    <select id="carDisp" name="carDisp" class="custom-select" required>
                                                        <option value="1">SIM</option>
                                                        <option value="0">NÂO</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>-->

                                        <div class="form-group text-center">
                                            <div class="btn-group">
                                                <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="requisicaoEditForm-btn">Salvar</button>
                                                <button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                                <div class="tab-pane fade" id="requisicao-Itens" role="tabpanel" aria-labelledby="requisicao-Itens-tab">


                                    <div style="margin-bottom:10px" class="row">
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="additensRequisicao()" title="Adicionar">Adicionar</button>
                                        </div>

                                        <div class="col-md-4">
                                            <a type="button" class="btn btn-block btn-secondary" data-toggle="tooltip" data-placement="top" title="Adicionar" title="Consultar CATMAT/CATSERV" href="https://catalogo.compras.gov.br/cnbs-web/busca" target="_blabk">Consultar CATMAT/CATSERV</a>
                                        </div>


                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="importarLista()" title="Adicionar">Importar Lista</button>
                                        </div>

                                        <div class="col-md-3">
                                            <button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Modelos" onclick="itensModelo()" title="Adicionar">Modelos de Materiais/Serviço</button>
                                        </div>
                                    </div>

                                    <input type="file" name="" id="inputFile" multiple="multiple" style="display: none;">

                                    <table id="data_tableitensRequisicao" class="table table-striped table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th>Código</th>
                                                <th>NrRef</th>
                                                <th>Descrição Item</th>
                                                <th>Unidade</th>
                                                <th>Qtde Sol</th>
                                                <th>Valor</th>
                                                <th>Sub Total</th>
                                                <th>Tipo Mat</th>
                                                <th>Observação</th>

                                                <th></th>
                                            </tr>
                                        </thead>
                                    </table>
                                    <!--  Itens - select * from apolo.ges_itens -->

                                </div>
                                <div class="tab-pane fade" id="requisicao-InformacoesComplementares" role="tabpanel" aria-labelledby="requisicao-InformacoesComplementares-tab">


                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addinformacoesComplementares()" title="Adicionar">Adicionar</button>
                                    </div>

                                    <table id="data_tableinformacoesComplementares" class="table table-striped table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th>Código</th>
                                                <th>Classificação</th>
                                                <th>Conteúdo</th>
                                                <th>Data Criação</th>
                                                <th>Autor</th>

                                                <th></th>
                                            </tr>
                                        </thead>
                                    </table>


                                    <!--  Informações Complementares - select * apolo.from ges_reqinfocompl -->
                                </div>
                                <div class="tab-pane fade" id="requisicao-Documentos" role="tabpanel" aria-labelledby="requisicao-Documentos-tab">
                                    <!-- Anexos - select * from apolo.ges_ranexos -->
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="tipoDocumento()" title="Adicionar">Adicionar</button>
                                    </div>
                                    <table id="data_tabledocumentos" class="table table-striped table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th>Número</th>
                                                <th>Assunto</th>
                                                <th>Destinatário</th>
                                                <th>Remetente</th>
                                                <th>Data Criação</th>
                                                <th>Data Atualização</th>
                                                <th>Autor</th>
                                                <th>Tipo Documento</th>
                                                <th>Status</th>

                                                <th></th>
                                            </tr>
                                        </thead>
                                    </table>

                                </div>
                                <div class="tab-pane fade" id="requisicao-Anexos" role="tabpanel" aria-labelledby="requisicao-Anexos-tab">
                                    <!-- Anexos - select * from apolo.ges_ranexos -->
                                </div>
                                <div class="tab-pane fade" id="requisicao-Despachos" role="tabpanel" aria-labelledby="requisicao-Despachos-tab">


                                    <div class="row d-flex justify-content-end">
                                        <div class="col-md-3 ">
                                            <button type="button" class="btn btn-block btn-secondary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="adicionarAcao()" title="Adicionar">Adicionar Ação/Despacho
                                            </button>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <!-- The time line -->
                                            <div id='htmlAcoes' class="timeline">


                                                <div>
                                                    <i class="fas fa-clock bg-gray"></i>
                                                </div>

                                            </div>
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                </div>
                                <!-- Despachos - select * from apolo.ges_reqdespachos -->
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>

        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->


<!-- Add modal content -->
<div id="itensRequisicaoAddModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-center p-3">
                <h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Item</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="itensRequisicaoAddForm" class="pl-3 pr-3">
                    <input type="hidden" id="<?php echo csrf_token() ?>itensRequisicaoAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                    <div class="row">
                        <input type="hidden" id="codRequisicaoItem" name="codRequisicaoItem" class="form-control" placeholder="Código" maxlength="11" required>
                        <input type="hidden" id="codRequisicao" name="codRequisicao" class="form-control" maxlength="11" required>
                    </div>
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="descricao"> Descrição detalhada do Item: <span class="text-danger">*</span> </label>
                                <textarea cols="40" rows="5" id="descricao" name="descricao" class="form-control" placeholder="Descrição detalhada do item como nome, dimensões, cor e demais características obrigatórias do material ou serviço" required></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- /.container-fluid -->


                    <div class="row">

                        <div class="col-md-6">
                            <div class="card card-secondary">
                                <div class="card-header ui-sortable-handle" style="cursor: move;">
                                    <h3 class="card-title">
                                        <i class="fas fa-circle-info mr-1"></i>
                                        Detalhes do Item
                                    </h3>
                                    <div class="card-tools">

                                    </div>
                                </div><!-- /.card-header -->
                                <div class="card-body">


                                    <div id="camposEspeciaisAdd" class="row">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="nrRef"> NrRef (Opcional): </label>
                                                <input type="number" id="nrRef" name="nrRef" class="form-control" placeholder="NrRef" maxlength="11" number="true">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="unidade"> Unidade: <span class="text-danger">*</span>
                                                </label>
                                                <select id="unidade" name="unidade" class="custom-select" required>
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>


                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="tipoMaterial"> Tipo: <span class="text-danger">*</span>
                                                </label>
                                                <select id="tipoMaterial" name="tipoMaterial" class="custom-select" required>
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="qtdeSol"> Qtde Sol: <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" id="qtdeSol" name="qtdeSol" class="form-control" placeholder="Qtde Sol" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="valorUnit"> Valor: <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" id="valorUnit" name="valorUnit" class="form-control" placeholder="0.00" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="metodoCalculo"> Método de Cálculo: <span class="text-danger">*</span>
                                                </label>
                                                <select id="metodoCalculo" name="metodoCalculo" class="custom-select" required>
                                                    <option value="1">Média de preços</option>
                                                    <option value="0">Valor Fixo</option>
                                                    <option value="2">Menor preço</option>
                                                    <option value="3">Maior preço</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="codSiasg"> SIASG/UASG (Opcional): </label>
                                                <input type="text" id="codSiasg" name="codSiasg" class="form-control" placeholder="SIASG/UASG" maxlength="20">
                                            </div>
                                        </div>


                                    </div>
                                </div><!-- /.card-body -->
                            </div>
                        </div>

                    </div>



                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="obs"> Observação/Justificativa: </label>
                                <textarea cols="40" rows="5" id="obs" name="obs" class="form-control" placeholder="Informe por exempo: como, onde e quando este item será utilizado" required></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-center">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="itensRequisicaoAddForm-btn">Adicionar</button>
                            <button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->









<div id="orcamentosAddModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-center p-3">
                <h4 class="modal-title text-white" id="info-header-modalLabel">Detalhe do Orçamento</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="orcamentosAddForm" class="pl-3 pr-3">
                    <input type="hidden" id="<?php echo csrf_token() ?>orcamentosAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">


                    <div class="row">
                        <input type="hidden" id="codOrcamento" name="codOrcamento" class="form-control" placeholder="Código" maxlength="11" required>
                        <input type="hidden" id="codRequisicaoItemOrcamentoAdd" name="codRequisicaoItem" class="form-control" placeholder="Código" maxlength="11" required>
                        <input type="hidden" id="metodoCalculoAddOrcamento" name="metodoCalculo" class="form-control" placeholder="Código" maxlength="11" required>
                    </div>


                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="codFornecedor"> Fornecedor: <span class="text-danger">*</span> </label>
                                <select id="codFornecedor" name="codFornecedor" class="custom-select" required>
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="valorUnitario"> Valor Unitário: <span class="text-danger">*</span> </label>
                                <input type="text" id="valorUnitario" name="valorUnitario" class="form-control" placeholder="0.00" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="codTipoOrcamento"> Tipo Orcamento: <span class="text-danger">*</span>
                                </label>
                                <select id="codTipoOrcamento" name="codTipoOrcamento" class="custom-select" required>
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="Data Orçamento"> Data Orçamento: <span class="text-danger">*</span> </label>
                                <input type="date" id="dataOrcamento" name="dataOrcamento" value="<?php echo date('Y-m-d') ?>" class="form-control" dateISO="true" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            Inserir um Orçamento.
                        </div>
                        <div style="margin-top:20px" class="col-md-6">
                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="arquivoOnAdd" name="file" onchange="aviso1(this)">
                                    <label class="custom-file-label" for="arquivo1">Selecione um arquivo</label>
                                </div>
                            </div>
                            <div style="color:red" id="aviso1">
                            </div>

                        </div>
                    </div>

                    <div class="form-group text-center">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="orcamentosAddForm-btn">Adicionar</button>
                            <button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Add modal content -->
<div id="orcamentosEditModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-center p-3">
                <h4 class="modal-title text-white" id="info-header-modalLabel">Detalhe do Orçamento</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="orcamentosEditForm" class="pl-3 pr-3">
                    <input type="hidden" id="<?php echo csrf_token() ?>orcamentosEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                    <div class="row">
                        <input type="hidden" id="codOrcamento" name="codOrcamento" class="form-control" placeholder="Código" maxlength="11" required>
                        <input type="hidden" id="codRequisicaoItemOrcamentoEdit" name="codRequisicaoItem" class="form-control" placeholder="Código" maxlength="11" required>
                        <input type="hidden" id="metodoCalculoEditOrcamento" name="metodoCalculo" class="form-control" placeholder="Código" maxlength="11" required>
                        <input type="hidden" id="codFornecedor" name="codFornecedor" class="form-control" placeholder="Código" maxlength="11" required>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="razaoSocial"> Fornecedor <span class="text-danger">*</span> </label>
                                <input readonly type="text" id="razaoSocial" name="razaoSocial" class="form-control" placeholder="razaoSocial" maxlength="50" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="valorUnitario"> Valor Unitário: <span class="text-danger">*</span> </label>
                                <input type="text" id="valorUnitario" name="valorUnitario" class="form-control" placeholder="0.00" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="codTipoOrcamento"> Tipo Orcamento: <span class="text-danger">*</span>
                                </label>
                                <select id="codTipoOrcamento" name="codTipoOrcamento" class="custom-select" required>
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="Data Orçamento"> Data Orçamento: <span class="text-danger">*</span> </label>
                                <input type="date" id="dataOrcamento" name="dataOrcamento" class="form-control" dateISO="true" required>
                            </div>
                        </div>
                    </div>


                    <div class="card card-primary">
                        <div class="card-header">
                            Arquivos
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    Insira aqui o Orcamento.
                                </div>
                                <div style="margin-top:20px" class="col-md-3">
                                    <div class="form-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="arquivoOnEdit" name="file" onchange="aviso2(this)">
                                            <label class="custom-file-label" for="arquivo1">Selecione um arquivo</label>
                                        </div>
                                    </div>
                                </div>


                            </div>

                            <div style="color:red" id="aviso2">
                            </div>

                            <div style="margin-top:20px" class="row">
                                <div class="col-md-12">

                                    <div id="documento">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="form-group text-center">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="orcamentosEditForm-btn">Salvar</button>
                            <button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<div id="fornecedoresAddModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-center p-3">
                <h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Fornecedores</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="fornecedoresAddForm" class="pl-3 pr-3">
                    <input type="hidden" id="<?php echo csrf_token() ?>fornecedoresAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                    <div class="row">
                        <input type="hidden" id="codFornecedor" name="codFornecedor" class="form-control" placeholder="CodFornecedor" maxlength="11" required>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inscricao"> CPF/CNPJ: <span class="text-danger">*</span> </label>
                                <input type="text" id="inscricao" name="inscricao" class="form-control" placeholder="CPF/CNPJ" maxlength="20" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="codTipo"> Tipo: <span class="text-danger">*</span> </label>
                                <select id="codTipo" name="codTipo" class="custom-select" required>
                                    <option></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nomeFantasia"> NomeFantasia: <span class="text-danger">*</span></label>
                                <input type="text" id="nomeFantasia" name="nomeFantasia" class="form-control" placeholder="NomeFantasia" required maxlength="255">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="razaoSocial"> RazaoSocial: <span class="text-danger">*</span></label>
                                <input type="text" id="razaoSocial" name="razaoSocial" class="form-control" placeholder="RazaoSocial" required maxlength="255">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="endereco"> Endereço: </label>
                                <textarea cols="40" rows="5" id="endereco" name="endereco" class="form-control" placeholder="Endereço"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cidade"> Cidade: <span class="text-danger">*</span></label>
                                <input type="text" id="cidade" name="cidade" class="form-control" placeholder="Cidade" required maxlength="30">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="codEstadoFederacao"> UF: <span class="text-danger">*</span> </label>
                                <select id="codEstadoFederacao" name="codEstadoFederacao" class="custom-select" required>
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cep"> CEP: </label>
                                <input type="text" id="cep" name="cep" class="form-control" placeholder="CEP" maxlength="9">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="contatos"> Contatos: </label>
                                <textarea cols="40" rows="5" id="contatos" name="contatos" class="form-control" placeholder="Contatos"></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="email"> Email: </label>
                                <input type="text" id="email" name="email" class="form-control" placeholder="Email" maxlength="255">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="website"> Website: </label>
                                <input type="text" id="website" name="website" class="form-control" placeholder="Website" maxlength="255">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="simples"> Simples: </label>
                                <input type="text" id="simples" name="simples" class="form-control" placeholder="Simples" maxlength="3">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="observacoes"> Observações: </label>
                                <textarea cols="40" rows="5" id="observacoes" name="observacoes" class="form-control" placeholder="Observações"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-center">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="fornecedoresAddForm-btn">Adicionar</button>
                            <button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="informacoesComplementaresAddModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-center p-3">
                <h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Informações Complementares</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="informacoesComplementaresAddForm" class="pl-3 pr-3">
                    <input type="hidden" id="<?php echo csrf_token() ?>informacoesComplementaresAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
                    <input type="hidden" id="codRequisicaoInforComplementar" name="codRequisicao" class="form-control" placeholder="CodRequisicao" maxlength="11" number="true" required>
                    <input type="hidden" id="codInforComplementar" name="codInforComplementar" class="form-control" placeholder="CodInforComplementar" maxlength="11" required>

                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="codCategoriaInforComplementar"> Classificação: <span class="text-danger">*</span> </label>
                                <select id="codCategoriaInforComplementar" name="codCategoria" class="custom-select" required>
                                    <option value=""></option>
                                </select>
                            </div>

                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="conteudo"> Conteúdo: <span class="text-danger">*</span> </label>
                                <textarea cols="40" rows="5" id="conteudo" name="conteudo" class="form-control" placeholder="Informe aqui todas as informações complementares que você deseja adicionar ao processo." required></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-center">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="informacoesComplementaresAddForm-btn">Adicionar</button>
                            <button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Add modal content -->
<div id="informacoesComplementaresEditModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-center p-3">
                <h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Informações Complementares</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="informacoesComplementaresEditForm" class="pl-3 pr-3">
                    <input type="hidden" id="<?php echo csrf_token() ?>informacoesComplementaresAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
                    <input type="hidden" id="codRequisicaoInforComplementar" name="codRequisicao" class="form-control" placeholder="CodRequisicao" maxlength="11" number="true" required>
                    <input type="hidden" id="codInforComplementar" name="codInforComplementar" class="form-control" placeholder="CodInforComplementar" maxlength="11" required>

                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="codCategoriaInforComplementar"> Classificação: <span class="text-danger">*</span> </label>
                                <select id="codCategoriaInforComplementar" name="codCategoria" class="custom-select" required>
                                    <option value=""></option>
                                </select>
                            </div>

                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="conteudo"> Conteúdo: <span class="text-danger">*</span> </label>
                                <textarea cols="40" rows="5" id="conteudo" name="conteudo" class="form-control" placeholder="Informe aqui todas as informações complementares que você deseja adicionar ao processo." required>
                                </textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-center">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="informacoesComplementaresEditForm-btn">Salvar</button>
                            <button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<div id="itensRequisicaoEditModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-center p-3">
                <h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Item</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="itensRequisicaoEditForm" class="pl-3 pr-3">
                    <input type="hidden" id="<?php echo csrf_token() ?>itensRequisicaoEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                    <div class="row">
                        <input type="hidden" id="codRequisicaoItem" name="codRequisicaoItem" class="form-control" placeholder="Código" maxlength="11" required>
                    </div>


                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="descricao"> Descrição detalhada do Item: <span class="text-danger">*</span> </label>
                                <textarea cols="40" rows="5" id="descricao" name="descricao" class="form-control" placeholder="Descrição detalhada do item como nome, dimensões, cor e demais características obrigatórias do material ou serviço" required></textarea>
                            </div>
                        </div>
                    </div>


                    <div class="row">

                        <div class="col-md-6">
                            <div class="card card-secondary">
                                <div class="card-header ui-sortable-handle" style="cursor: move;">
                                    <h3 class="card-title">
                                        <i class="fas fa-circle-info mr-1"></i>
                                        Detalhes do Item
                                    </h3>
                                    <div class="card-tools">

                                    </div>
                                </div>
                                <div class="card-body">


                                    <div id="camposEspeciaisEdit" class="row">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="nrRef"> NrRef (Opcional): </label>
                                                <input type="number" id="nrRef" name="nrRef" class="form-control" placeholder="NrRef" maxlength="11" number="true">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="unidade"> Unidade: <span class="text-danger">*</span>
                                                </label>
                                                <select id="unidade" name="unidade" class="custom-select" required>
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>


                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="tipoMaterial"> Tipo: <span class="text-danger">*</span>
                                                </label>
                                                <select id="tipoMaterial" name="tipoMaterial" class="custom-select" required>
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="qtdeSol"> Qtde Sol: <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" id="qtdeSol" name="qtdeSol" class="form-control" placeholder="Qtde Sol" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="valorUnit"> Valor: <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" id="valorUnit" name="valorUnit" class="form-control" placeholder="0.00" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="metodoCalculo"> Método de Cálculo: <span class="text-danger">*</span>
                                                </label>
                                                <select id="metodoCalculo" name="metodoCalculo" class="custom-select" required>
                                                    <option value="0">Valor Fixo</option>
                                                    <option value="1">Média de preços</option>
                                                    <option value="2">Menor preço</option>
                                                    <option value="3">Maior preço</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="codSiasg"> SIASG/UASG (Opcional): </label>
                                                <input type="text" id="codSiasg" name="codSiasg" class="form-control" placeholder="SIASG/UASG" maxlength="20">
                                            </div>
                                        </div>


                                    </div>
                                    <div class="row">

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="obs"> Observação/Justificativa: </label>
                                                <textarea cols="40" rows="5" id="obs" name="obs" class="form-control" placeholder="Informe por exempo: como, onde e quando este item será utilizado" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <div class="col-md-6">
                            <div class="card card-secondary">
                                <div class="card-header ui-sortable-handle" style="cursor: move;">
                                    <h3 class="card-title">
                                        <i class="fas fa-circle-info mr-1"></i>
                                        Composição do Preço do produto/serviço (Origem)
                                    </h3>
                                    <div class="card-tools">

                                    </div>
                                </div>
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addorcamentos()" title="Adicionar">Adicionar</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <table id="data_tableorcamentos" class="table table-striped table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Nr</th>
                                                    <th>Fornecedor</th>
                                                    <th>Valor Unitário</th>
                                                    <th>Tipo Orcamento</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="itensRequisicaoEditForm-btn">Salvar</button>
                            <button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




<div id="historicoAcaoAddModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-center p-3">
                <h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Ação</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="historicoAcaoAddForm" class="pl-3 pr-3">
                    <input type="hidden" id="<?php echo csrf_token() ?>historicoAcaoAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
                    <input type="hidden" id="codRequisicao" name="codRequisicao" class="form-control" placeholder="codRequisicao" maxlength="11" number="true" required>

                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="codTipoAcao">Tipo Ação: <span class="text-danger">*</span> </label>
                                <select id="codTipoAcao" name="codTipoAcao" class="custom-select" required>
                                    <option value=""></option>
                                </select>
                            </div>

                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="descricaoAcao"> Descricao da Ação: <span class="text-danger">*</span> </label>
                                <textarea cols="40" rows="5" id="descricaoAcao" name="descricaoAcao" class="form-control" placeholder="Informe aqui todas as informações relevantes para o acompanhamento do processo." required></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-center">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="historicoAcaoAddForm-btn">Adicionar</button>
                            <button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->






<div id="impressaoRequisicaoModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-center p-3">
                <button onclick="imprimirRequisicaoAgora()">
                    Imprimir</button>
                <h4 class="modal-title text-white"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>

            </div>
            <div style="margin-left:2cm !important;margin-right:2cm;margin-bottom:50px" id="areaImpressaoRequisicao" class="modal-body">

                <table>
                    <tr>
                        <td width="33.3%">
                            <img src="<?php echo base_url() . "/imagens/visto.png" ?>">
                        </td>
                        <td width="33.3%">
                            <?php echo session()->cabecalhoOficios; ?>
                        </td>
                        <td width="33.3%">
                        </td>
                    </tr>
                </table>



                <div style="margin-top:20px" class="row">
                    <div class="col-md-12">
                        <div id="conteudoImpressaoRequisicao"></div>
                    </div>
                </div>



                <div style="margin-top:20px" class="row">
                    <div class="col-md-12">
                        <?php echo session()->rodapeOficios; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<div id="verOrcamentoModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-center p-3">
                <h4 class="modal-title text-white" id="info-header-modalLabel">Orçamento</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div id="verOrcamento">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="tipoDocumentoModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-center p-3">
                <h4 class="modal-title text-white" id="info-header-modalLabel">Tipo do Documento</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3">
                        <button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addParteRequisitoria()" title="Adicionar">Parte Requisitória</button>
                    </div>
                    <div class="col-md-3">
                    </div>
                    <div class="col-md-3">
                    </div>
                    <div class="col-md-3">
                    </div>
                </div>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="addParteRequisitoriaModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-center p-3">
                <h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar documentos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addParteRequisitoriaForm" class="pl-3 pr-3">
                    <input type="hidden" id="<?php echo csrf_token() ?>addParteRequisitoriaForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
                    <input type="hidden" id="codRequisicao" name="codRequisicao" class="form-control" placeholder="CodRequisicao" maxlength="11" required>

                    <div class="row">
                        <input type="hidden" id="codDocumento" name="codDocumento" class="form-control" placeholder="CodDocumento" maxlength="11" required>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="assunto"> Assunto: <span class="text-danger">*</span> </label>
                                <input type="text" id="assunto" name="assunto" value="Aquisição de Material" class="form-control" placeholder="Assunto" maxlength="200" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="codRemetente"> Remetente: <span class="text-danger">*</span> </label>
                                <select id="codRemetente" name="codRemetente" class="custom-select" required>
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="codDestinatario"> Destinatário: <span class="text-danger">*</span> </label>
                                <select id="codDestinatario" name="codDestinatario" class="custom-select" required>
                                    <option></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="conteudo"> Conteúdo: <span class="text-danger">*</span> </label>
                                <textarea cols="40" rows="5" id="conteudoParteRequisitoria" name="conteudo" class="form-control" placeholder="Conteúdo" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="documentosAddForm-btn">Adicionar</button>
                            <button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div id="importarItensModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-center p-3">
                <h4 class="modal-title text-white" id="info-header-modalLabel">Importar Itens</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="importarItensForm" class="pl-3 pr-3" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="<?php echo csrf_token() ?>importarItensForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                    <div class="row">
                        <div class="col-md-12">
                            Inserir arquivo de Importação.
                        </div>
                        <div style="margin-top:20px" class="col-md-6">
                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="file" id="arquivoImportacao" name="file">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="form-group text-center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="importarAgora()">Importar</button>
                            <button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="itensModeloModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-center p-3">
                <h4 class="modal-title text-white" id="info-header-modalLabel">Itens Modelo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                <table id="data_tableitensModelo" class="table table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>CodCat</th>
                            <th>Descrição</th>
                            <th>Informações Complementares</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<div id="itensPAASSExModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-center p-3">
                <h4 class="modal-title text-white" id="info-header-modalLabel">Itens do PAASSEx</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                <table id="data_tableIstensPAASSEx" class="table table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Descricao Detalhada</th>
                            <th>Departamento</th>
                            <th>Tipo Req</th>
                            <th>Classe</th>
                            <th>Valor Req</th>
                            <th>Status</th>
                            <th>Autor</th>
                            <th>Item</th>
                            <th>Item Detalhado</th>
                            <th>ValorUnd</th>
                            <th>Qtde</th>
                            <th>SubTotal</th>
                            <th>TipoMaterial</th>
                            <th>Prioridade</th>
                            <th>unidade</th>
                            <th>Justificativa</th>
                            <th>CatMat</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php
echo view('tema/rodape');
?>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/summernote/summernote-bs4.min.js"></script>

<script>
    PAASSEX = '<?php echo $PAASSEX ?>'

    $(document).on('show.bs.modal', '.modal', function() {
        var zIndex = 1040 + (10 * $('.modal:visible').length);
        $(this).css('z-index', zIndex);
        setTimeout(function() {
            $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
    });


    var codDepartamento = <?php echo session()->codDepartamento ?>;
    var euMesmo = <?php echo session()->codPessoa ?>;

    function tipoDocumento() {
        // reset the form 


        $('#tipoDocumentoModal').modal('show');
    }

    $.ajax({
        url: '<?php echo base_url('requisicao/listaDropDownDepartamentos') ?>',
        type: 'post',
        dataType: 'json',
        data: {
            csrf_sandra: $("#csrf_sandraPrincipal").val(),
        },
        success: function(departamentoFiltro) {

            $("#codDepartamentoFiltro").select2({
                data: departamentoFiltro,
            })

            $('#codDepartamentoFiltro').val(codDepartamento); // Select the option with a value of '1'
            $('#codDepartamentoFiltro').trigger('change');
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });

        }
    })

    $.ajax({
        url: '<?php echo base_url('requisicao/listaDropDownStatusRequisicao') ?>',
        type: 'post',
        dataType: 'json',
        data: {
            csrf_sandra: $("#csrf_sandraPrincipal").val(),
        },
        success: function(status) {

            $("#codStatusRequisicaoFiltro").select2({
                data: status,
            })

            $('#codStatusRequisicaoFiltro').val(null); // Select the option with a value of '1'
            $('#codStatusRequisicaoFiltro').trigger('change');
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });

        }
    })


    $.ajax({
        url: '<?php echo base_url('requisicao/listaDropDownTipoRequisicao') ?>',
        type: 'post',
        dataType: 'json',
        data: {
            csrf_sandra: $("#csrf_sandraPrincipal").val(),
        },
        success: function(tipoRequisicao) {

            $("#codTipoFiltro").select2({
                data: tipoRequisicao,
            })

            $('#codTipoFiltro').val(null); // Select the option with a value of '1'
            $('#codTipoFiltro').trigger('change');
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });

        }
    })


    $.ajax({
        url: '<?php echo base_url('requisicao/listaDropDownClasseRequisicao') ?>',
        type: 'post',
        dataType: 'json',
        data: {
            csrf_sandra: $("#csrf_sandraPrincipal").val(),
        },
        success: function(codClasseFiltro) {

            $("#codClasseFiltro").select2({
                data: codClasseFiltro,
            })

            $('#codClasseFiltro').val(null); // Select the option with a value of '1'
            $('#codClasseFiltro').trigger('change');
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });

        }
    })

    $('#codFornecedorFiltro').html('').select2({
        data: [{
            id: '',
            text: ''
        }]
    });


    $.ajax({
        url: '<?php echo base_url('fornecedores/listaDropDownFornecedores') ?>',
        type: 'post',
        dataType: 'json',
        data: {
            csrf_sandra: $("#csrf_sandraPrincipal").val(),
        },
        success: function(Fornecedores) {

            $("#codFornecedorFiltro").select2({
                data: Fornecedores,
                allowClear: true,
                placeholder: 'Digite CNPJ ou Razão Social',
                minimumInputLength: 4,
                quietMillis: 1000,
                dropdownParent: $('#orcamentosAddModal .modal-content'),

            })


            $('#codFornecedorFiltro').val();
            $('#codFornecedorFiltro').trigger('change');
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });
        }
    })

    $.ajax({
        url: '<?php echo base_url('requisicao/listaDropDownAutor') ?>',
        type: 'post',
        dataType: 'json',
        data: {
            csrf_sandra: $("#csrf_sandraPrincipal").val(),
        },
        success: function(responsavelFiltro) {

            $("#codResponsavelFiltro").select2({
                data: responsavelFiltro,
            })

            $('#codResponsavelFiltro').val(null); // Select the option with a value of '1'
            $('#codResponsavelFiltro').trigger('change');
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });

        }
    })


    $.ajax({
        url: '<?php echo base_url('SolicitacoesSuporte/listaDropDownSolicitante') ?>',
        type: 'post',
        dataType: 'json',
        data: {
            csrf_sandra: $("#csrf_sandraPrincipal").val(),
        },
        success: function(pessoasFiltro) {

            $("#codSolicitanteFiltro").select2({
                data: pessoasFiltro,
            })

            $('#codSolicitanteFiltro').val(euMesmo); // Select the option with a value of '1'
            $('#codSolicitanteFiltro').trigger('change');
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });

        }
    })


    function itensPAASSEx() {

        $('#itensPAASSExModal').modal('show');


        $('#data_tableIstensPAASSEx').DataTable({
            "bDestroy": true,
            "paging": true,
            "deferRender": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "ajax": {
                "url": '<?php echo base_url('requisicao/itensPAASSEx') ?>',
                "type": "POST",
                "dataType": "json",
                async: "true",
                data: {
                    csrf_sandra: $("#csrf_sandraPrincipal").val(),
                },
            },
            dom: 'Bfrtip',
            buttons: [{


                    extend: 'print',
                    text: "Imprimir",
                    title: 'Itens PAASSEx 2023',

                    customize: function(win) {
                        $(win.document.body)
                            .css('font-size', '12pt')
                            .prepend(
                                '<div>' +
                                '</div>' +
                                '<img alt="" style="width:60px" src="<?php echo base_url() . "/imagens/organizacoes/" . session()->logo ?>" style="position:absolute; top:0; left:0;" />'
                            );

                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    },
                    exportOptions: {
                        //columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                    },

                },
                {
                    extend: 'csvHtml5',
                    exportOptions: {
                        //columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                    }
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        //columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                    }
                },

            ],
        });

    }


    function limparRequisicoes() {

        $.ajax({
            url: '<?php echo base_url('requisicao/limpaFiltro') ?>',
            type: 'post',
            data: {
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            dataType: 'json',
            success: function(limpaFiltro) {

                if (limpaFiltro.success === true) {
                    //$('#data_tablerequisicao').DataTable().ajax.reload(null, false).draw(false);

                }
                if (limpaFiltro.success === false) {

                }
            }
        })

        $.ajax({
            url: '<?php echo base_url('requisicao/listaDropDownDepartamentos') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            success: function(departamentoFiltro) {

                $("#codDepartamentoFiltro").select2({
                    data: departamentoFiltro,
                })

                $('#codDepartamentoFiltro').val(codDepartamento); // Select the option with a value of '1'
                $('#codDepartamentoFiltro').trigger('change');
                $(document).on('select2:open', () => {
                    document.querySelector('.select2-search__field').focus();
                });

            }
        })

        $.ajax({
            url: '<?php echo base_url('requisicao/listaDropDownStatusRequisicao') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            success: function(status) {

                $("#codStatusRequisicaoFiltro").select2({
                    data: status,
                })

                $('#codStatusRequisicaoFiltro').val(null); // Select the option with a value of '1'
                $('#codStatusRequisicaoFiltro').trigger('change');
                $(document).on('select2:open', () => {
                    document.querySelector('.select2-search__field').focus();
                });

            }
        })


        $.ajax({
            url: '<?php echo base_url('requisicao/listaDropDownTipoRequisicao') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            success: function(tipoRequisicao) {

                $("#codTipoFiltro").select2({
                    data: tipoRequisicao,
                })

                $('#codTipoFiltro').val(null); // Select the option with a value of '1'
                $('#codTipoFiltro').trigger('change');
                $(document).on('select2:open', () => {
                    document.querySelector('.select2-search__field').focus();
                });

            }
        })


        $.ajax({
            url: '<?php echo base_url('requisicao/listaDropDownClasseRequisicao') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            success: function(codClasseFiltro) {

                $("#codClasseFiltro").select2({
                    data: codClasseFiltro,
                })

                $('#codClasseFiltro').val(null); // Select the option with a value of '1'
                $('#codClasseFiltro').trigger('change');
                $(document).on('select2:open', () => {
                    document.querySelector('.select2-search__field').focus();
                });

            }
        })

        $.ajax({
            url: '<?php echo base_url('requisicao/listaDropDownAutor') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            success: function(responsavelFiltro) {

                $("#codResponsavelFiltro").select2({
                    data: responsavelFiltro,
                })

                $('#codResponsavelFiltro').val(null); // Select the option with a value of '1'
                $('#codResponsavelFiltro').trigger('change');
                $(document).on('select2:open', () => {
                    document.querySelector('.select2-search__field').focus();
                });

            }
        })

        $('#codFornecedorFiltro').html('').select2({
            data: [{
                id: '',
                text: ''
            }]
        });


        $.ajax({
            url: '<?php echo base_url('fornecedores/listaDropDownFornecedores') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            success: function(Fornecedores) {

                $("#codFornecedorFiltro").select2({
                    data: Fornecedores,
                    allowClear: true,
                    placeholder: 'Digite CNPJ ou Razão Social',
                    minimumInputLength: 4,
                    quietMillis: 1000,
                    dropdownParent: $('#orcamentosAddModal .modal-content'),

                })


                $('#codFornecedorFiltro').val(null);
                $('#codFornecedorFiltro').trigger('change');
                $(document).on('select2:open', () => {
                    document.querySelector('.select2-search__field').focus();
                });
            }
        })


        $("#periodoRequisicoes").val("0").trigger("change");

    }


    function buscarRequisicoes() {


        $("#collapseOne").collapse("hide");

        var form = $('#formFiltro');
        $.ajax({
            url: '<?php echo base_url('requisicao/salvaFiltro') ?>',
            type: 'post',
            data: form.serialize(), // /converting the form data into array and sending it to server
            dataType: 'json',
            success: function(responsePreferenciaFiltro) {

                if (responsePreferenciaFiltro.success === true) {
                    swal.close();
                    $('#data_tablerequisicao').DataTable().ajax.reload(null, false).draw(false);
                }
                if (responsePreferenciaFiltro.success === false) {
                    swal.close();
                    Swal.fire({
                        position: 'bottom-end',
                        icon: 'error',
                        title: responsePreferenciaFiltro.messages,
                        showConfirmButton: false,
                        timer: 4000
                    })
                }




            }
        }).always(
            Swal.fire({
                title: 'Estamos processando sua requisição',
                html: 'Aguarde....',
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading()


                }

            }))
    };

    function importarAgora() {


        if (document.getElementById("arquivoImportacao").files.length !== 0) {

            var formData = new FormData();
            formData.append('file', $('#arquivoImportacao')[0].files[0]);
            formData.append('codRequisicao', $('#requisicaoEditForm #codRequisicao').val());
            formData.append('csrf_sandra', $("#csrf_sandraPrincipal").val());
            $.ajax({
                url: 'requisicao/importarItens',
                type: 'post',
                dataType: 'json',
                data: formData,
                processData: false, // tell jQuery not to process the data
                contentType: false, // tell jQuery not to set contentType
                success: function(importar) {
                    if (importar.success === true) {

                        $('#importarItensModal').modal('hide');
                        $('#data_tableitensRequisicao').DataTable().ajax.reload(null, false).draw(false);
                        Swal.fire({
                            icon: 'success',
                            title: importar.messages,
                            showConfirmButton: true,
                            confirmButtonText: 'Ciente',
                        })


                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: importar.messages,
                            showConfirmButton: true,
                            confirmButtonText: 'Ciente',
                        })
                    }



                },
            });

        } else {

            alert('Você deve anexar um arquivo!');
        }



    }

    function itensModelo() {

        $('#itensModeloModal').modal('show');
        $('#data_tableitensModelo').DataTable({
            "bDestroy": true,
            "paging": true,
            "deferRender": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "ajax": {
                "url": '<?php echo base_url('itensModelo/modelos') ?>',
                "type": "POST",
                "dataType": "json",
                async: "true",
                data: {
                    csrf_sandra: $("#csrf_sandraPrincipal").val(),
                },
            }
        });

    }

    function importarLista() {

        $("#importarItensForm")[0].reset();
        $('#importarItensModal').modal('show');

    }

    function addParteRequisitoria() {
        // reset the form 

        conteudo = '';

        $("#addParteRequisitoriaForm")[0].reset();
        $(".form-control").removeClass('is-invalid').removeClass('is-valid');
        $('#addParteRequisitoriaModal').modal('show');

        $("#addParteRequisitoriaForm #codRequisicao").val($("#requisicaoEditForm #codRequisicao").val());


        $('#addParteRequisitoriaForm #codRemetente').on('change', function() {


            document.getElementById('assinadorDocumento').innerHTML = $("#addParteRequisitoriaForm #codRemetente option:selected").text();
        });


        $.ajax({
            url: '<?php echo base_url('requisicao/geraRequisicao') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                codRequisicao: $("#requisicaoEditForm #codRequisicao").val(),
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            success: function(impressaoRequisicao) {

                if (impressaoRequisicao.success === true) {


                    $("#addParteRequisitoriaForm #conteudoParteRequisitoria").val(impressaoRequisicao.html);

                    //$("#addParteRequisitoriaForm #conteudoParteRequisitoria").summernote('destroy');
                    //$("#addParteRequisitoriaForm #conteudoParteRequisitoria").val(null);
                    $(function() {
                        //ADD text editor
                        $("#addParteRequisitoriaForm #conteudoParteRequisitoria").summernote({
                            height: 150,
                            maximumImageFileSize: 1024 * 1024, // 1Mb
                            fontSizes: ['8', '9', '10', '11', '12', '14', '18', '20', '36', '72'],
                            lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
                            toolbar: [
                                ['style', ['style']],
                                ['fontname', ['fontname']],
                                ['font', ['color', 'strikethrough', 'superscript', 'subscript', 'bold', 'underline', 'clear']],
                                ['fontsize', ['fontsize']],
                                ['height', ['height']],
                                ['para', ['ul', 'ol', 'paragraph']],
                                ['table', ['table']],
                                ['insert', ['link', 'picture', 'video', 'hr']],
                                ['view', ['fullscreen', 'codeview', 'help']],
                                ['redo'],
                                ['undo'],
                            ],
                            callbacks: {
                                onImageUploadError: function(msg) {
                                    var Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 5000
                                    });
                                    Toast.fire({
                                        icon: 'error',
                                        title: 'Tamanho máximo de imagens é 1Mb'
                                    })
                                }
                            }
                        })
                    })

                }

            }
        })

        $.ajax({
            url: '<?php echo base_url('especialidades/listaDropDownPessoas') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            success: function(codRemetente) {

                $("#addParteRequisitoriaForm #codRemetente").select2({
                    data: codRemetente,
                })

                $('#addParteRequisitoriaForm #codRemetente').val('<?php echo session()->codPessoa ?>'); // Select the option with a value of '1'
                $('#addParteRequisitoriaForm #codRemetente').trigger('change');
                $(document).on('select2:open', () => {
                    document.querySelector('.select2-search__field').focus();
                });

            }
        })


        $.ajax({
            url: '<?php echo base_url('especialidades/listaDropDownPessoas') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            success: function(codDestinatario) {

                $("#addParteRequisitoriaForm #codDestinatario").select2({
                    data: codDestinatario,
                })

                $('#addParteRequisitoriaForm #codDestinatario').val(null); // Select the option with a value of '1'
                $('#addParteRequisitoriaForm #codDestinatario').trigger('change');
                $(document).on('select2:open', () => {
                    document.querySelector('.select2-search__field').focus();
                });

            }
        })



    }

    function editParteRequisitoria() {
        // reset the form 


        $('#editParteRequisitoriaModal').modal('show');
    }



    function aviso1(myFile) {
        var file = myFile.files[0];
        var filename = file.name;
        document.getElementById('aviso1').innerHTML = filename;

    }

    function aviso2(myFile) {
        var file = myFile.files[0];
        var filename = file.name;
        document.getElementById('aviso2').innerHTML = filename;

    }


    camposEspeciais = '<div class="col-md-6">' +
        '<div class="form-group">' +
        '<label for="codCat"> CATMAT/CATSERV: </label>' +
        '<input type="number" id="codCat" name="codCat" class="form-control" placeholder="CatMat/CatServ" maxlength="10" required>' +
        ' </div>' +
        ' </div>' +
        ' <div class="col-md-6">' +
        '<div class="form-group">' +
        '<label for="prioridade"> Prioridade: <span class="text-danger">*</span>' +
        '  </label>' +
        ' <select id="prioridade" name="prioridade" class="custom-select" required>' +
        '  <option value="">Selecione a prioridade</option>' +
        '    <option value="1">1 - Importante e urgente</option>' +
        '    <option value="2">2 - Importante, mas não urgente</option>' +
        '    <option value="3">3 - Urgente, mas não importante</option>' +
        '    <option value="4">4 - Não urgente, não importante</option>' +
        ' </select>' +
        ' </div>' +
        ' </div>';



    $(function() {

        codDepartamentoTmp = '<?php echo session()->codDepartamento ?>';


        avisoPesquisa('Aquisição', 2);

        $('#data_tablerequisicao').DataTable({
            "paging": true,
            "deferRender": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            //order: [[5, 'desc']],
            "ajax": {
                "url": '<?php echo base_url('requisicao/getAll') ?>',
                "type": "POST",
                "dataType": "json",
                async: "true",
                data: {
                    PAASSEX: PAASSEX,
                    csrf_sandra: $("#csrf_sandraPrincipal").val(),
                },
            }
        });
    });



    function printElement(elem) {
        var domClone = elem.cloneNode(true);

        var $printSection = document.getElementById("printSection");

        if (!$printSection) {
            var $printSection = document.createElement("div");
            $printSection.id = "printSection";
            document.body.appendChild($printSection);
        }

        $printSection.innerHTML = "";

        $printSection.appendChild(domClone);
    }

    function usarModelo(codItemModelo) {


        $.ajax({
            url: '<?php echo base_url('requisicao/usarModelo') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                codItemModelo: codItemModelo,
                codRequisicao: $("#requisicaoEditForm #codRequisicao").val(),
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            success: function(itemModelo) {



                $('#itensModeloModal').modal('hide');

                if (itemModelo.success === true) {
                    $('#data_tableitensRequisicao').DataTable().ajax.reload(null, false).draw(false);
                    Swal.fire({
                        icon: 'success',
                        title: itemModelo.messages,
                        showConfirmButton: true,
                        confirmButtonText: 'Ciente',
                    })
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: itemModelo.messages,
                        showConfirmButton: true,
                        confirmButtonText: 'Ciente',
                    })
                }
            }
        })
    }


    function imprimirRequisicao(codRequisicao) {

        document.getElementById("conteudoImpressaoRequisicao").innerHTML = '';


        $.ajax({
            url: '<?php echo base_url('requisicao/imprimirRequisicao') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                codRequisicao: codRequisicao,
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            success: function(impressaoRequisicao) {

                if (impressaoRequisicao.success === true) {



                    document.getElementById("conteudoImpressaoRequisicao").innerHTML = impressaoRequisicao.html;


                    $('#impressaoRequisicaoModal').modal('show');




                } else {

                    Swal.fire({
                        icon: 'warning',
                        title: impressaoRequisicao.messages,
                        showConfirmButton: true,
                        confirmButtonText: 'Ciente',
                    })


                }

            }
        })
    }


    function imprimirRequisicaoAgora() {

        document.getElementById("setEstilo").innerHTML = '<style>@media screen {' +
            '#printSection {' +
            'display: none;' +

            '}' +
            '}' +

            '@media print {' +
            '@page {' +
            'size: A4;' +
            'margin-top: 15px;' +
            'margin-bottom: 15px;' +
            'margin-left: 15px;' +
            'margin-right: 15px;' +
            '}' +

            'body>*:not(#printSection) {' +
            'display: none;' +
            '}' +

            '#printSection,' +
            '#printSection * {' +
            'visibility: visible;' +

            '}' +
            '#printSection {' +
            'position: absolute;' +
            'left: 0;' +
            'top: 0;' +
            'width: 297mm;' +
            'height: 210mm;' +

            '}' +
            '}</style>';



        printElement(document.getElementById("areaImpressaoRequisicao"));
        window.print();
    }



    function addrequisicao() {
        // reset the form 
        $("#requisicaoAddForm")[0].reset();
        $(".form-control").removeClass('is-invalid').removeClass('is-valid');
        $('#requisicaoAddModal').modal('show');




        $.ajax({
            url: '<?php echo base_url('Departamentos/listaDropDown') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            success: function(listaDepartamentos) {

                $("#requisicaoAddForm #codDepartamento").select2({
                    data: listaDepartamentos,
                })

                $("#requisicaoAddForm #codDepartamento").val(codDepartamentoTmp); // Select the option with a value of '1'
                $("#requisicaoAddForm #codDepartamento").trigger('change');
                $(document).on('select2:open', () => {
                    document.querySelector('.select2-search__field').focus();
                });

            }
        })



        tipoRequisicao = null;
        classseRequisicao = null;
        if (PAASSEX == 1) {
            tipoRequisicao = 70;
            classseRequisicao = 1;
        }

        $.ajax({
            url: '<?php echo base_url('requisicao/listaDropDownTipoRequisicao') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            success: function(listaTipoRequisicao) {

                $("#requisicaoAddForm #codTipoRequisicao").select2({
                    data: listaTipoRequisicao,
                })

                $("#requisicaoAddForm #codTipoRequisicao").val(tipoRequisicao); // Select the option with a value of '1'
                $("#requisicaoAddForm #codTipoRequisicao").trigger('change');
                $(document).on('select2:open', () => {
                    document.querySelector('.select2-search__field').focus();
                });

            }
        })

        $.ajax({
            url: '<?php echo base_url('requisicao/listaDropDownClasserequisicao') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            success: function(listaTipoServico) {

                $("#requisicaoAddForm #codClasseRequisicao").select2({
                    data: listaTipoServico,
                })

                $("#requisicaoAddForm #codClasseRequisicao").val(classseRequisicao); // Select the option with a value of '1'
                $("#requisicaoAddForm #codClasseRequisicao").trigger('change');
                $(document).on('select2:open', () => {
                    document.querySelector('.select2-search__field').focus();
                });

            }
        })


        $("#requisicaoAddForm #descricao").summernote('destroy');
        $("#requisicaoAddForm #descricao").val(null);
        $(function() {
            //ADD text editor
            $("#requisicaoAddForm #descricao").summernote({
                height: 150,
                placeholder: 'Descreva aqui o motiva da requisição....',
                maximumImageFileSize: 1024 * 1024, // 1Mb
                fontSizes: ['8', '9', '10', '11', '12', '14', '18', '20', '36', '72'],
                lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
                toolbar: [
                    ['style', ['style']],
                    ['fontname', ['fontname']],
                    ['font', ['color', 'strikethrough', 'superscript', 'subscript', 'bold', 'underline', 'clear']],
                    ['fontsize', ['fontsize']],
                    ['height', ['height']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video', 'hr']],
                    ['view', ['fullscreen', 'codeview', 'help']],
                    ['redo'],
                    ['undo'],
                ],
                callbacks: {
                    onImageUploadError: function(msg) {
                        var Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000
                        });
                        Toast.fire({
                            icon: 'error',
                            title: 'Tamanho máximo de imagens é 1Mb'
                        })
                    }
                }
            })
        })



        $.validator.setDefaults({
            highlight: function(element) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid').addClass('is-valid');
            },
            errorElement: 'div ',
            errorClass: 'invalid-feedback',
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if ($(element).is('.select')) {
                    element.next().after(error);
                } else if (element.hasClass('select2')) {
                    //error.insertAfter(element);
                    error.insertAfter(element.next());
                } else if (element.hasClass('selectpicker')) {
                    error.insertAfter(element.next());
                } else {
                    error.insertAfter(element);
                }
            },

            submitHandler: function(form) {

                var form = $('#requisicaoAddForm');
                // remove the text-danger
                $(".text-danger").remove();

                $.ajax({
                    url: '<?php echo base_url('requisicao/add') ?>',
                    type: 'post',
                    data: form.serialize(), // /converting the form data into array and sending it to server
                    dataType: 'json',
                    beforeSend: function() {
                        //$('#requisicaoAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
                    },
                    success: function(response) {

                        $('#requisicaoAddModal').modal('hide');
                        if (response.success === true) {

                            var Toast = Swal.mixin({
                                toast: true,
                                position: 'bottom-end',
                                showConfirmButton: false,
                                timer: 2000
                            });
                            Toast.fire({
                                icon: 'success',
                                title: response.messages
                            }).then(function() {
                                editrequisicao(response.codRequisicao);
                                $('#data_tablerequisicao').DataTable().ajax.reload(null, false).draw(false);
                            })

                        } else {

                            if (response.messages instanceof Object) {
                                $.each(response.messages, function(index, value) {
                                    var id = $("#" + index);

                                    id.closest('.form-control')
                                        .removeClass('is-invalid')
                                        .removeClass('is-valid')
                                        .addClass(value.length > 0 ? 'is-invalid' : 'is-valid');

                                    id.after(value);

                                });
                            } else {

                                Swal.fire({
                                    position: 'bottom-end',
                                    icon: 'error',
                                    title: response.messages,
                                    showConfirmButton: true,
                                    confirmButtonText: 'Ok',
                                });

                            }
                        }
                        $('#requisicaoAddForm-btn').html('Adicionar');
                    }
                });

                return false;
            }
        });
        $('#requisicaoAddForm').validate();
    }

    function editrequisicao(codRequisicao) {

        $.ajax({
            url: '<?php echo base_url('requisicao/getOne') ?>',
            type: 'post',
            data: {
                codRequisicao: codRequisicao,
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            dataType: 'json',
            success: function(response) {
                // reset the form 
                $("#requisicaoEditForm")[0].reset();
                $(".form-control").removeClass('is-invalid').removeClass('is-valid');
                $('#requisicaoEditModal').modal('show');

                document.getElementById("requisicaoEditModalTitulo").innerHTML = response.tituloRequisicao


                $("#requisicaoEditForm #codRequisicao").val(response.codRequisicao);
                $("#requisicaoEditForm #descricao").val(response.descricao);
                $("#requisicaoEditForm #dataRequisicao").val(response.dataRequisicao);
                $("#requisicaoEditForm #matSau").val(response.matSau);
                //$("#requisicaoEditForm #carDisp").val(response.carDisp);
                //$("#requisicaoEditForm #valorTotal").val(response.valorTotal);


                $("#itensRequisicaoAddForm #codRequisicao").val(codRequisicao);

                $("#informacoesComplementaresAddForm #codRequisicaoInforComplementar").val(codRequisicao);
                $("#informacoesComplementaresEditForm #codRequisicaoInforComplementar").val(codRequisicao);

                document.getElementById('htmlAcoes').innerHTML = '';

                document.getElementById('camposEspeciaisAdd').innerHTML = "";
                document.getElementById('camposEspeciaisEdit').innerHTML = "";


                if (response.codTipoRequisicao == 10 || response.codTipoRequisicao == 30 || response.codTipoRequisicao == 70 || response.codTipoRequisicao == 80 || response.codTipoRequisicao == 90) {

                    document.getElementById('camposEspeciaisAdd').innerHTML = camposEspeciais;
                    document.getElementById('camposEspeciaisEdit').innerHTML = camposEspeciais;

                }

                $.ajax({
                    url: '<?php echo base_url('requisicao/pegaHistoricoAcoes') ?>',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        codRequisicao: codRequisicao,
                        csrf_sandra: $("#csrf_sandraPrincipal").val(),
                    },
                    success: function(historicoRequisicoes) {
                        document.getElementById('htmlAcoes').innerHTML = historicoRequisicoes.html;
                    }
                });


                $(function() {
                    $('#data_tableitensRequisicao').DataTable({
                        "bDestroy": true,
                        "paging": true,
                        "deferRender": true,
                        "lengthChange": false,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "responsive": true,
                        "ajax": {
                            "url": '<?php echo base_url('itensRequisicao/itensRequisicao') ?>',
                            "type": "POST",
                            "dataType": "json",
                            async: "true",
                            data: {
                                codRequisicao: codRequisicao,
                                csrf_sandra: $("#csrf_sandraPrincipal").val(),
                            },
                        }
                    });
                });



                $(function() {
                    $('#data_tableinformacoesComplementares').DataTable({
                        "bDestroy": true,
                        "paging": true,
                        "deferRender": true,
                        "lengthChange": false,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "responsive": true,
                        "ajax": {
                            "url": '<?php echo base_url('informacoesComplementares/informacoesComplementaresRequisicao') ?>',
                            "type": "POST",
                            "dataType": "json",
                            async: "true",
                            data: {
                                codRequisicao: codRequisicao,
                                csrf_sandra: $("#csrf_sandraPrincipal").val(),
                            },
                        }
                    });
                });




                $('#data_tabledocumentos').DataTable({
                    "bDestroy": true,
                    "paging": true,
                    "deferRender": true,
                    "lengthChange": false,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    "ajax": {
                        "url": '<?php echo base_url('documentos/porRequisicaoCompra') ?>',
                        "type": "POST",
                        "dataType": "json",
                        async: "true",
                        data: {
                            csrf_sandra: $("#csrf_sandraPrincipal").val(),
                        },
                    }
                });



                $.ajax({
                    url: '<?php echo base_url('Departamentos/listaDropDown') ?>',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        csrf_sandra: $("#csrf_sandraPrincipal").val(),
                    },
                    success: function(listaDepartamentos) {

                        $("#requisicaoEditForm #codDepartamento").select2({
                            data: listaDepartamentos,
                        })

                        $("#requisicaoEditForm #codDepartamento").val(response.codDepartamento); // Select the option with a value of '1'
                        $("#requisicaoEditForm #codDepartamento").trigger('change');
                        $(document).on('select2:open', () => {
                            document.querySelector('.select2-search__field').focus();
                        });

                    }
                });


                $.ajax({
                    url: '<?php echo base_url('requisicao/listaDropDownTipoRequisicao') ?>',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        csrf_sandra: $("#csrf_sandraPrincipal").val(),
                    },
                    success: function(listaTipoRequisicao) {

                        $("#requisicaoEditForm #codTipoRequisicao").select2({
                            data: listaTipoRequisicao,
                        })

                        $("#requisicaoEditForm #codTipoRequisicao").val(response.codTipoRequisicao); // Select the option with a value of '1'
                        $("#requisicaoEditForm #codTipoRequisicao").trigger('change');
                        $(document).on('select2:open', () => {
                            document.querySelector('.select2-search__field').focus();
                        });

                    }
                })

                $.ajax({
                    url: '<?php echo base_url('requisicao/listaDropDownClasserequisicao') ?>',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        csrf_sandra: $("#csrf_sandraPrincipal").val(),
                    },
                    success: function(listaTipoServico) {

                        $("#requisicaoEditForm #codClasseRequisicao").select2({
                            data: listaTipoServico,
                        })

                        $("#requisicaoEditForm #codClasseRequisicao").val(response.codClasseRequisicao); // Select the option with a value of '1'
                        $("#requisicaoEditForm #codClasseRequisicao").trigger('change');
                        $(document).on('select2:open', () => {
                            document.querySelector('.select2-search__field').focus();
                        });

                    }
                });




                $("#requisicaoEditForm #descricao").summernote('destroy');
                $(function() {
                    //ADD text editor
                    $("#requisicaoEditForm #descricao").summernote({
                        height: 150,
                        maximumImageFileSize: 1024 * 1024, // 1Mb
                        fontSizes: ['8', '9', '10', '11', '12', '14', '18', '20', '36', '72'],
                        lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
                        toolbar: [
                            ['style', ['style']],
                            ['fontname', ['fontname']],
                            ['font', ['color', 'strikethrough', 'superscript', 'subscript', 'bold', 'underline', 'clear']],
                            ['fontsize', ['fontsize']],
                            ['height', ['height']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['table', ['table']],
                            ['insert', ['link', 'picture', 'video', 'hr']],
                            ['view', ['fullscreen', 'codeview', 'help']],
                            ['redo'],
                            ['undo'],
                        ],
                        callbacks: {
                            onImageUploadError: function(msg) {
                                var Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 5000
                                });
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Tamanho máximo de imagens é 1Mb'
                                })
                            }
                        }
                    })
                })



                $.validator.setDefaults({



                    highlight: function(element) {
                        $(element).addClass('is-invalid').removeClass('is-valid');
                    },
                    unhighlight: function(element) {
                        $(element).removeClass('is-invalid').addClass('is-valid');
                    },
                    errorElement: 'div ',
                    errorClass: 'invalid-feedback',
                    errorPlacement: function(error, element) {
                        if (element.parent('.input-group').length) {
                            error.insertAfter(element.parent());
                        } else if ($(element).is('.select')) {
                            element.next().after(error);
                        } else if (element.hasClass('select2')) {
                            //error.insertAfter(element);
                            error.insertAfter(element.next());
                        } else if (element.hasClass('selectpicker')) {
                            error.insertAfter(element.next());
                        } else {
                            error.insertAfter(element);
                        }
                    },

                    submitHandler: function(form) {
                        var form = $('#requisicaoEditForm');
                        $(".text-danger").remove();
                        $.ajax({
                            url: '<?php echo base_url('requisicao/edit') ?>',
                            type: 'post',
                            data: form.serialize(),
                            dataType: 'json',
                            beforeSend: function() {
                                //$('#requisicaoEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
                            },
                            success: function(response) {

                                if (response.success === true) {

                                    $('#requisicaoEditModal').modal('hide');


                                    var Toast = Swal.mixin({
                                        toast: true,
                                        position: 'bottom-end',
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    Toast.fire({
                                        icon: 'success',
                                        title: response.messages
                                    }).then(function() {
                                        $('#data_tablerequisicao').DataTable().ajax.reload(null, false).draw(false);
                                    })

                                } else {

                                    if (response.messages instanceof Object) {
                                        $.each(response.messages, function(index, value) {
                                            var id = $("#" + index);

                                            id.closest('.form-control')
                                                .removeClass('is-invalid')
                                                .removeClass('is-valid')
                                                .addClass(value.length > 0 ? 'is-invalid' : 'is-valid');

                                            id.after(value);

                                        });
                                    } else {
                                        var Toast = Swal.mixin({
                                            toast: true,
                                            position: 'bottom-end',
                                            showConfirmButton: false,
                                            timer: 2000
                                        });
                                        Toast.fire({
                                            icon: 'error',
                                            title: response.messages
                                        })

                                    }
                                }
                                $('#requisicaoEditForm-btn').html('Salvar');
                            }
                        });

                        return false;
                    }
                });
                $('#requisicaoEditForm').validate();

            }
        });
    }

    function removerequisicao(codRequisicao) {
        Swal.fire({
            title: 'Você tem certeza que deseja remover?',
            text: "Você não poderá reverter após a confirmação",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {

            if (result.value) {
                $.ajax({
                    url: '<?php echo base_url('requisicao/remove') ?>',
                    type: 'post',
                    data: {
                        codRequisicao: codRequisicao,
                        csrf_sandra: $("#csrf_sandraPrincipal").val(),
                    },
                    dataType: 'json',
                    success: function(response) {

                        if (response.success === true) {
                            var Toast = Swal.mixin({
                                toast: true,
                                position: 'bottom-end',
                                showConfirmButton: false,
                                timer: 2000
                            });
                            Toast.fire({
                                icon: 'success',
                                title: response.messages
                            }).then(function() {
                                $('#data_tablerequisicao').DataTable().ajax.reload(null, false).draw(false);
                            })
                        } else {
                            var Toast = Swal.mixin({
                                toast: true,
                                position: 'bottom-end',
                                showConfirmButton: false,
                                timer: 2000
                            });
                            Toast.fire({
                                icon: 'error',
                                title: response.messages
                            })


                        }
                    }
                });
            }
        })
    }


    function additensRequisicao() {
        // reset the form 
        $("#itensRequisicaoAddForm")[0].reset();
        $(".form-control").removeClass('is-invalid').removeClass('is-valid');
        $('#itensRequisicaoAddModal').modal('show');
        $.ajax({
            url: '<?php echo base_url('itensRequisicao/listaDropDownUnidades') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            success: function(unidadeAdd) {

                $("#itensRequisicaoAddForm #unidade").select2({
                    data: unidadeAdd,
                })

                $('#itensRequisicaoAddForm #unidade').val(1);
                $('#itensRequisicaoAddForm #unidade').trigger('change');
                $(document).on('select2:open', () => {
                    document.querySelector('.select2-search__field').focus();
                });
            }
        })




        $.ajax({
            url: '<?php echo base_url('itensRequisicao/listaDropDownTipoMaterial') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            success: function(tipoMaterialAdd) {

                $("#itensRequisicaoAddForm #tipoMaterial").select2({
                    data: tipoMaterialAdd,
                })

                $('#itensRequisicaoAddForm #tipoMaterial').val(null);
                $('#itensRequisicaoAddForm #tipoMaterial').trigger('change');
                $(document).on('select2:open', () => {
                    document.querySelector('.select2-search__field').focus();
                });
            }
        })




        $("#itensRequisicaoAddForm #descricao").summernote('destroy');
        $("#itensRequisicaoAddForm #descricao").val(null);
        $(function() {
            //ADD text editor
            $("#itensRequisicaoAddForm #descricao").summernote({
                height: 150,
                maximumImageFileSize: 1024 * 1024, // 1Mb
                fontSizes: ['8', '9', '10', '11', '12', '14', '18', '20', '36', '72'],
                lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
                toolbar: [
                    ['style', ['style']],
                    ['fontname', ['fontname']],
                    ['font', ['color', 'strikethrough', 'superscript', 'subscript', 'bold', 'underline', 'clear']],
                    ['fontsize', ['fontsize']],
                    ['height', ['height']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video', 'hr']],
                    ['view', ['fullscreen', 'codeview', 'help']],
                    ['redo'],
                    ['undo'],
                ],
                callbacks: {
                    onImageUploadError: function(msg) {
                        var Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000
                        });
                        Toast.fire({
                            icon: 'error',
                            title: 'Tamanho máximo de imagens é 1Mb'
                        })
                    }
                }
            })
        })



        // submit the add from 
        $.validator.setDefaults({
            highlight: function(element) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid').addClass('is-valid');
            },
            errorElement: 'div ',
            errorClass: 'invalid-feedback',
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if ($(element).is('.select')) {
                    element.next().after(error);
                } else if (element.hasClass('select2')) {
                    //error.insertAfter(element);
                    error.insertAfter(element.next());
                } else if (element.hasClass('selectpicker')) {
                    error.insertAfter(element.next());
                } else {
                    error.insertAfter(element);
                }
            },

            submitHandler: function(form) {

                var form = $('#itensRequisicaoAddForm');
                // remove the text-danger
                $(".text-danger").remove();

                $.ajax({
                    url: '<?php echo base_url('itensRequisicao/add') ?>',
                    type: 'post',
                    data: form.serialize(), // /converting the form data into array and sending it to server
                    dataType: 'json',
                    beforeSend: function() {
                        //$('#itensRequisicaoAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
                    },
                    success: function(response) {
                        $('#itensRequisicaoAddModal').modal('hide');
                        $('#data_tablerequisicao').DataTable().ajax.reload(null, false).draw(false);

                        if (response.success === true) {

                            var Toast = Swal.mixin({
                                toast: true,
                                position: 'bottom-end',
                                showConfirmButton: false,
                                timer: 2000
                            });
                            Toast.fire({
                                icon: 'success',
                                title: response.messages
                            }).then(function() {
                                edititensRequisicao(response.codRequisicaoItem);
                                $('#data_tableitensRequisicao').DataTable().ajax.reload(null, false).draw(false);
                            })

                        } else {

                            if (response.messages instanceof Object) {
                                $.each(response.messages, function(index, value) {
                                    var id = $("#" + index);

                                    id.closest('.form-control')
                                        .removeClass('is-invalid')
                                        .removeClass('is-valid')
                                        .addClass(value.length > 0 ? 'is-invalid' : 'is-valid');

                                    id.after(value);

                                });
                            } else {

                                var Toast = Swal.mixin({
                                    toast: true,
                                    position: 'bottom-end',
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                                Toast.fire({
                                    icon: 'error',
                                    title: response.messages
                                })

                            }
                        }
                        $('#itensRequisicaoAddForm-btn').html('Adicionar');
                    }
                });

                return false;
            }
        });
        $('#itensRequisicaoAddForm').validate();
    }

    function edititensRequisicao(codRequisicaoItem) {
        $.ajax({
            url: '<?php echo base_url('itensRequisicao/getOne') ?>',
            type: 'post',
            data: {
                codRequisicaoItem: codRequisicaoItem,
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            dataType: 'json',
            success: function(response) {
                // reset the form 
                $("#itensRequisicaoEditForm")[0].reset();
                $(".form-control").removeClass('is-invalid').removeClass('is-valid');
                $('#itensRequisicaoEditModal').modal('show');

                $("#itensRequisicaoEditForm #descricao").summernote('destroy');

                $("#itensRequisicaoEditForm #codRequisicaoItem").val(response.codRequisicaoItem);

                $("#orcamentosAddForm #codRequisicaoItemOrcamentoAdd").val(response.codRequisicaoItem);

                $("#itensRequisicaoEditForm #nrRef").val(response.nrRef);
                $("#itensRequisicaoEditForm #unidade").val(response.unidade);
                $("#itensRequisicaoEditForm #qtdeSol").val(response.qtde_sol);
                $("#itensRequisicaoEditForm #valorUnit").val(response.valorUnit);
                $("#itensRequisicaoEditForm #metodoCalculo").val(response.metodoCalculo);
                $("#itensRequisicaoEditForm #codSiasg").val(response.cod_siasg);
                $("#itensRequisicaoEditForm #tipoMaterial").val(response.tipoMaterial);
                $("#itensRequisicaoEditForm #obs").val(response.obs);
                $("#itensRequisicaoEditForm #codCat").val(response.codCat);
                $("#itensRequisicaoEditForm #prioridade").val(response.prioridade);
                $("#itensRequisicaoEditForm #descricao").val(response.descricao);



                $("#itensRequisicaoEditForm #metodoCalculo").change(function() {

                    $.ajax({
                        url: '<?php echo base_url('itensRequisicao/mudaMetodoCalculo') ?>',
                        type: 'post',
                        data: {
                            codRequisicaoItem: codRequisicaoItem,
                            metodoCalculo: $("#itensRequisicaoEditForm #metodoCalculo").val(),
                            valorUnit: response.valorUnit,
                            csrf_sandra: $("#csrf_sandraPrincipal").val(),
                        },
                        dataType: 'json',
                        success: function(response) {

                            if (response.valor !== true) {
                                $("#itensRequisicaoEditForm #valorUnit").val(response.valor);
                                $('#data_tableitensRequisicao').DataTable().ajax.reload(null, false).draw(false);
                                $('#data_tablerequisicao').DataTable().ajax.reload(null, false).draw(false);

                            }
                        }
                    })


                })



                $(function() {
                    $('#data_tableorcamentos').DataTable({
                        "bDestroy": true,
                        "paging": true,
                        "deferRender": true,
                        "lengthChange": false,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "responsive": true,
                        "ajax": {
                            "url": '<?php echo base_url('orcamentos/orcamentosItem') ?>',
                            "type": "POST",
                            "dataType": "json",
                            async: "true",
                            data: {
                                codRequisicaoItem: codRequisicaoItem,
                                csrf_sandra: $("#csrf_sandraPrincipal").val(),
                            },
                        }
                    });
                });



                $.ajax({
                    url: '<?php echo base_url('itensRequisicao/listaDropDownUnidades') ?>',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        csrf_sandra: $("#csrf_sandraPrincipal").val(),
                    },
                    success: function(unidadeAdd) {

                        $("#itensRequisicaoEditForm #unidade").select2({
                            data: unidadeAdd,
                        })

                        $('#itensRequisicaoEditForm #unidade').val(response.unidade);
                        $('#itensRequisicaoEditForm #unidade').trigger('change');
                        $(document).on('select2:open', () => {
                            document.querySelector('.select2-search__field').focus();
                        });
                    }
                })




                $.ajax({
                    url: '<?php echo base_url('itensRequisicao/listaDropDownTipoMaterial') ?>',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        csrf_sandra: $("#csrf_sandraPrincipal").val(),
                    },
                    success: function(tipoMaterialEdit) {

                        $("#itensRequisicaoEditForm #tipoMaterial").select2({
                            data: tipoMaterialEdit,
                        })

                        $('#itensRequisicaoEditForm #tipoMaterial').val(response.tipoMaterial);
                        $('#itensRequisicaoEditForm #tipoMaterial').trigger('change');
                        $(document).on('select2:open', () => {
                            document.querySelector('.select2-search__field').focus();
                        });
                    }
                })
                $(function() {
                    //ADD text editor


                    $("#itensRequisicaoEditForm #descricao").summernote({
                        height: 150,
                        maximumImageFileSize: 1024 * 1024, // 1Mb
                        fontSizes: ['8', '9', '10', '11', '12', '14', '18', '20', '36', '72'],
                        lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
                        toolbar: [
                            ['style', ['style']],
                            ['fontname', ['fontname']],
                            ['font', ['color', 'strikethrough', 'superscript', 'subscript', 'bold', 'underline', 'clear']],
                            ['fontsize', ['fontsize']],
                            ['height', ['height']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['table', ['table']],
                            ['insert', ['link', 'picture', 'video', 'hr']],
                            ['view', ['fullscreen', 'codeview', 'help']],
                            ['redo'],
                            ['undo'],
                        ],
                    })
                })




                // submit the edit from 
                $.validator.setDefaults({
                    highlight: function(element) {
                        $(element).addClass('is-invalid').removeClass('is-valid');
                    },
                    unhighlight: function(element) {
                        $(element).removeClass('is-invalid').addClass('is-valid');
                    },
                    errorElement: 'div ',
                    errorClass: 'invalid-feedback',
                    errorPlacement: function(error, element) {
                        if (element.parent('.input-group').length) {
                            error.insertAfter(element.parent());
                        } else if ($(element).is('.select')) {
                            element.next().after(error);
                        } else if (element.hasClass('select2')) {
                            //error.insertAfter(element);
                            error.insertAfter(element.next());
                        } else if (element.hasClass('selectpicker')) {
                            error.insertAfter(element.next());
                        } else {
                            error.insertAfter(element);
                        }
                    },

                    submitHandler: function(form) {
                        var form = $('#itensRequisicaoEditForm');
                        $(".text-danger").remove();
                        $.ajax({
                            url: '<?php echo base_url('itensRequisicao/edit') ?>',
                            type: 'post',
                            data: form.serialize(),
                            dataType: 'json',
                            beforeSend: function() {
                                //$('#itensRequisicaoEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
                            },
                            success: function(response) {

                                $('#itensRequisicaoEditModal').modal('hide');
                                $('#data_tablerequisicao').DataTable().ajax.reload(null, false).draw(false);

                                if (response.success === true) {



                                    var Toast = Swal.mixin({
                                        toast: true,
                                        position: 'bottom-end',
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    Toast.fire({
                                        icon: 'success',
                                        title: response.messages
                                    }).then(function() {
                                        $('#data_tableitensRequisicao').DataTable().ajax.reload(null, false).draw(false);
                                    })

                                } else {

                                    if (response.messages instanceof Object) {
                                        $.each(response.messages, function(index, value) {
                                            var id = $("#" + index);

                                            id.closest('.form-control')
                                                .removeClass('is-invalid')
                                                .removeClass('is-valid')
                                                .addClass(value.length > 0 ? 'is-invalid' : 'is-valid');

                                            id.after(value);

                                        });
                                    } else {
                                        var Toast = Swal.mixin({
                                            toast: true,
                                            position: 'bottom-end',
                                            showConfirmButton: false,
                                            timer: 2000
                                        });
                                        Toast.fire({
                                            icon: 'error',
                                            title: response.messages
                                        })

                                    }
                                }
                                $('#itensRequisicaoEditForm-btn').html('Salvar');
                            }
                        });

                        return false;
                    }
                });
                $('#itensRequisicaoEditForm').validate();

            }
        });
    }

    function removeitensRequisicao(codRequisicaoItem) {
        Swal.fire({
            title: 'Você tem certeza que deseja remover?',
            text: "Você não poderá reverter após a confirmação",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {

            if (result.value) {
                $.ajax({
                    url: '<?php echo base_url('itensRequisicao/remove') ?>',
                    type: 'post',
                    data: {
                        codRequisicaoItem: codRequisicaoItem,
                        csrf_sandra: $("#csrf_sandraPrincipal").val(),
                    },
                    dataType: 'json',
                    success: function(response) {

                        if (response.success === true) {

                            var Toast = Swal.mixin({
                                toast: true,
                                position: 'bottom-end',
                                showConfirmButton: false,
                                timer: 2000
                            });
                            Toast.fire({
                                icon: 'success',
                                title: response.messages
                            }).then(function() {
                                $('#data_tablerequisicao').DataTable().ajax.reload(null, false).draw(false);

                                $('#data_tableitensRequisicao').DataTable().ajax.reload(null, false).draw(false);
                            })
                        } else {
                            var Toast = Swal.mixin({
                                toast: true,
                                position: 'bottom-end',
                                showConfirmButton: false,
                                timer: 2000
                            });
                            Toast.fire({
                                icon: 'error',
                                title: response.messages
                            })


                        }
                    }
                });
            }
        })
    }


    function addorcamentos() {
        // reset the form 
        $("#orcamentosAddForm")[0].reset();
        $(".form-control").removeClass('is-invalid').removeClass('is-valid');
        $('#orcamentosAddModal').modal('show');

        $("#orcamentosAddForm #metodoCalculoAddOrcamento").val($("#itensRequisicaoEditForm #metodoCalculo").val());


        document.getElementById('aviso1').innerHTML = "";
        document.getElementById('aviso2').innerHTML = "";

        $.ajax({
            url: '<?php echo base_url('itensRequisicao/listaDropDownTipoOrcamento') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            success: function(TipoOrcamento) {

                $("#orcamentosAddForm #codTipoOrcamento").select2({
                    data: TipoOrcamento,
                })

                $('#orcamentosAddForm #codTipoOrcamento').val(null);
                $('#orcamentosAddForm #codTipoOrcamento').trigger('change');
                $(document).on('select2:open', () => {
                    document.querySelector('.select2-search__field').focus();
                });
            }
        })


        $('#codFornecedor').html('').select2({
            data: [{
                id: '',
                text: ''
            }]
        });


        $.ajax({
            url: '<?php echo base_url('fornecedores/listaDropDownFornecedores') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            success: function(Fornecedores) {

                $("#orcamentosAddForm #codFornecedor").select2({
                    data: Fornecedores,
                    allowClear: true,
                    placeholder: 'Digite CNPJ ou Razão Social',
                    minimumInputLength: 4,
                    quietMillis: 1000,
                    dropdownParent: $('#orcamentosAddModal .modal-content'),
                    language: {
                        noResults: function() {
                            return `<button style="width: 100%" type="button"
                                        class="btn btn-xs btn-primary" 
                                        onClick='addfornecedores()'>+ Adicionar Fornecedor</button>
                                        `;
                        }
                    },

                    escapeMarkup: function(markup) {
                        return markup;
                    }
                })


                $('#orcamentosAddForm #codFornecedor').val();
                $('#orcamentosAddForm #codFornecedor').trigger('change');
                $(document).on('select2:open', () => {
                    document.querySelector('.select2-search__field').focus();
                });
            }
        })




        // submit the add from 
        $.validator.setDefaults({
            highlight: function(element) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid').addClass('is-valid');
            },
            errorElement: 'div ',
            errorClass: 'invalid-feedback',
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if ($(element).is('.select')) {
                    element.next().after(error);
                } else if (element.hasClass('select2')) {
                    //error.insertAfter(element);
                    error.insertAfter(element.next());
                } else if (element.hasClass('selectpicker')) {
                    error.insertAfter(element.next());
                } else {
                    error.insertAfter(element);
                }
            },

            submitHandler: function(form) {

                var form = $('#orcamentosAddForm');
                // remove the text-danger
                $(".text-danger").remove();

                $.ajax({
                    url: '<?php echo base_url('orcamentos/add') ?>',
                    type: 'post',
                    data: form.serialize(), // /converting the form data into array and sending it to server
                    dataType: 'json',
                    beforeSend: function() {
                        //$('#orcamentosAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
                    },
                    success: function(response) {

                        if (response.success === true) {
                            $('#orcamentosAddModal').modal('hide');

                            if (response.valor !== true) {
                                $("#itensRequisicaoEditForm #valorUnit").val(response.valor);
                                $('#data_tableitensRequisicao').DataTable().ajax.reload(null, false).draw(false);
                                $('#data_tablerequisicao').DataTable().ajax.reload(null, false).draw(false);

                            }

                            if (document.getElementById("arquivoOnAdd").files.length !== 0) {

                                var formData = new FormData();
                                formData.append('file', $('#arquivoOnAdd')[0].files[0]);
                                formData.append('codOrcamento', response.codOrcamento);
                                formData.append('csrf_sandra', $("#csrf_sandraPrincipal").val());
                                $.ajax({
                                    url: 'orcamentos/enviarArquivo',
                                    type: 'post',
                                    dataType: 'json',
                                    data: formData,
                                    processData: false, // tell jQuery not to process the data
                                    contentType: false, // tell jQuery not to set contentType
                                    success: function(enviaArquivo) {
                                        if (enviaArquivo.success == true) {

                                        } else {
                                            var Toast = Swal.mixin({
                                                toast: true,
                                                position: 'bottom-end',
                                                showConfirmButton: false,
                                                timer: 5000
                                            });
                                            Toast.fire({
                                                icon: 'error',
                                                title: enviaArquivo.messages,
                                            })
                                        }
                                    },
                                });

                            }


                            var Toast = Swal.mixin({
                                toast: true,
                                position: 'bottom-end',
                                showConfirmButton: false,
                                timer: 2000
                            });
                            Toast.fire({
                                icon: 'success',
                                title: response.messages
                            }).then(function() {
                                $('#data_tableorcamentos').DataTable().ajax.reload(null, false).draw(false);
                            })

                        } else {

                            if (response.messages instanceof Object) {
                                $.each(response.messages, function(index, value) {
                                    var id = $("#" + index);

                                    id.closest('.form-control')
                                        .removeClass('is-invalid')
                                        .removeClass('is-valid')
                                        .addClass(value.length > 0 ? 'is-invalid' : 'is-valid');

                                    id.after(value);

                                });
                            } else {

                                var Toast = Swal.mixin({
                                    toast: true,
                                    position: 'bottom-end',
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                                Toast.fire({
                                    icon: 'error',
                                    title: response.messages
                                })

                            }
                        }
                        $('#orcamentosAddForm-btn').html('Adicionar');
                    }
                });

                return false;
            }
        });
        $('#orcamentosAddForm').validate();
    }

    function editorcamentos(codOrcamento) {
        $.ajax({
            url: '<?php echo base_url('orcamentos/getOne') ?>',
            type: 'post',
            data: {
                codOrcamento: codOrcamento,
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            dataType: 'json',
            success: function(response) {
                // reset the form 
                $("#orcamentosEditForm")[0].reset();
                $(".form-control").removeClass('is-invalid').removeClass('is-valid');
                $('#orcamentosEditModal').modal('show');

                $("#orcamentosEditForm #codOrcamento").val(response.codOrcamento);
                $("#orcamentosEditForm #razaoSocial").val(response.razaoSocial);
                $("#orcamentosEditForm #valorUnitario").val(response.valorUnitario);
                $("#orcamentosEditForm #codTipoOrcamento").val(response.codTipoOrcamento);
                $("#orcamentosEditForm #dataOrcamento").val(response.dataOrcamento);
                $("#orcamentosEditForm #codFornecedor").val(response.codFornecedor);
                $("#orcamentosEditForm #codRequisicaoItemOrcamentoEdit").val(response.codRequisicaoItem);

                $("#orcamentosEditForm #metodoCalculoEditOrcamento").val($("#itensRequisicaoEditForm #metodoCalculo").val());

                if (response.documento !== null) {
                    document.getElementById('documento').innerHTML = 'Anexo: <a style="font-size:20px" href="#" onclick="verOrcamento(' + response.codOrcamento + ')">' + response.documento + '</a>';

                }
                document.getElementById('aviso1').innerHTML = "";
                document.getElementById('aviso2').innerHTML = "";


                $.ajax({
                    url: '<?php echo base_url('itensRequisicao/listaDropDownTipoOrcamento') ?>',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        csrf_sandra: $("#csrf_sandraPrincipal").val(),
                    },
                    success: function(TipoOrcamento) {

                        $("#orcamentosEditForm #codTipoOrcamento").select2({
                            data: TipoOrcamento,
                        })

                        $('#orcamentosEditForm #codTipoOrcamento').val(response.codTipoOrcamento);
                        $('#orcamentosEditForm #codTipoOrcamento').trigger('change');
                        $(document).on('select2:open', () => {
                            document.querySelector('.select2-search__field').focus();
                        });
                    }
                })



                // submit the edit from 
                $.validator.setDefaults({
                    highlight: function(element) {
                        $(element).addClass('is-invalid').removeClass('is-valid');
                    },
                    unhighlight: function(element) {
                        $(element).removeClass('is-invalid').addClass('is-valid');
                    },
                    errorElement: 'div ',
                    errorClass: 'invalid-feedback',
                    errorPlacement: function(error, element) {
                        if (element.parent('.input-group').length) {
                            error.insertAfter(element.parent());
                        } else if ($(element).is('.select')) {
                            element.next().after(error);
                        } else if (element.hasClass('select2')) {
                            //error.insertAfter(element);
                            error.insertAfter(element.next());
                        } else if (element.hasClass('selectpicker')) {
                            error.insertAfter(element.next());
                        } else {
                            error.insertAfter(element);
                        }
                    },

                    submitHandler: function(form) {
                        var form = $('#orcamentosEditForm');
                        $(".text-danger").remove();
                        $.ajax({
                            url: '<?php echo base_url('orcamentos/edit') ?>',
                            type: 'post',
                            data: form.serialize(),
                            dataType: 'json',
                            beforeSend: function() {
                                //$('#orcamentosEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
                            },
                            success: function(response) {

                                if (response.success === true) {

                                    $('#orcamentosEditModal').modal('hide');


                                    if (response.success === true) {

                                        if (response.valor !== true) {
                                            $("#itensRequisicaoEditForm #valorUnit").val(response.valor);
                                            $('#data_tableitensRequisicao').DataTable().ajax.reload(null, false).draw(false);
                                            $('#data_tablerequisicao').DataTable().ajax.reload(null, false).draw(false);

                                        }

                                        if (document.getElementById("arquivoOnEdit").files.length !== 0) {

                                            var formData = new FormData();
                                            formData.append('file', $('#arquivoOnEdit')[0].files[0]);
                                            formData.append('codOrcamento', codOrcamento);
                                            formData.append('csrf_sandra', $("#csrf_sandraPrincipal").val());
                                            $.ajax({
                                                url: 'orcamentos/enviarArquivo',
                                                type: 'post',
                                                dataType: 'json',
                                                data: formData,
                                                processData: false, // tell jQuery not to process the data
                                                contentType: false, // tell jQuery not to set contentType
                                                success: function(enviaArquivo) {
                                                    if (enviaArquivo.success == true) {}


                                                },
                                            });

                                        }




                                        var Toast = Swal.mixin({
                                            toast: true,
                                            position: 'bottom-end',
                                            showConfirmButton: false,
                                            timer: 2000
                                        });
                                        Toast.fire({
                                            icon: 'success',
                                            title: response.messages
                                        }).then(function() {
                                            $('#data_tableorcamentos').DataTable().ajax.reload(null, false).draw(false);
                                        })
                                    }
                                } else {

                                    if (response.messages instanceof Object) {
                                        $.each(response.messages, function(index, value) {
                                            var id = $("#" + index);

                                            id.closest('.form-control')
                                                .removeClass('is-invalid')
                                                .removeClass('is-valid')
                                                .addClass(value.length > 0 ? 'is-invalid' : 'is-valid');

                                            id.after(value);

                                        });
                                    } else {
                                        var Toast = Swal.mixin({
                                            toast: true,
                                            position: 'bottom-end',
                                            showConfirmButton: false,
                                            timer: 2000
                                        });
                                        Toast.fire({
                                            icon: 'error',
                                            title: response.messages
                                        })

                                    }
                                }
                                $('#orcamentosEditForm-btn').html('Salvar');
                            }
                        });

                        return false;
                    }
                });
                $('#orcamentosEditForm').validate();

            }
        });
    }


    function verOrcamento(codOrcamento) {
        $.ajax({
            url: '<?php echo base_url('orcamentos/verOrcamento') ?>',
            type: 'post',
            data: {
                codOrcamento: codOrcamento,
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            dataType: 'json',
            success: function(response) {
                if (response.success === true) {

                    $('#verOrcamentoModal').modal('show');

                    document.getElementById('verOrcamento').innerHTML = response.documento;
                }
            }
        })
    }



    function removeorcamentos(codOrcamento) {
        Swal.fire({
            title: 'Você tem certeza que deseja remover?',
            text: "Você não poderá reverter após a confirmação",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {

            if (result.value) {
                $.ajax({
                    url: '<?php echo base_url('orcamentos/remove') ?>',
                    type: 'post',
                    data: {
                        codOrcamento: codOrcamento,
                        csrf_sandra: $("#csrf_sandraPrincipal").val(),
                    },
                    dataType: 'json',
                    success: function(response) {

                        if (response.success === true) {

                            if (response.valor !== true) {
                                $("#itensRequisicaoEditForm #valorUnit").val(response.valor);
                                $('#data_tableitensRequisicao').DataTable().ajax.reload(null, false).draw(false);
                                $('#data_tablerequisicao').DataTable().ajax.reload(null, false).draw(false);

                            }

                            var Toast = Swal.mixin({
                                toast: true,
                                position: 'bottom-end',
                                showConfirmButton: false,
                                timer: 2000
                            });
                            Toast.fire({
                                icon: 'success',
                                title: response.messages
                            }).then(function() {
                                $('#data_tableorcamentos').DataTable().ajax.reload(null, false).draw(false);
                            })
                        } else {
                            var Toast = Swal.mixin({
                                toast: true,
                                position: 'bottom-end',
                                showConfirmButton: false,
                                timer: 2000
                            });
                            Toast.fire({
                                icon: 'error',
                                title: response.messages
                            })


                        }
                    }
                });
            }
        })
    }



    function addfornecedores() {
        // reset the form 
        $("#fornecedoresAddForm")[0].reset();
        $(".form-control").removeClass('is-invalid').removeClass('is-valid');
        $('#fornecedoresAddModal').modal('show');


        $("#orcamentosAddForm #codFornecedor").select2('close');



        $.ajax({
            url: '<?php echo base_url('fornecedores/listaDropDownTipoFornecedor') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            success: function(tipoFornecedor) {

                $("#fornecedoresAddForm #codTipo").select2({
                    data: tipoFornecedor,
                })

                $("#fornecedoresAddForm #codTipo").val(null); // Select the option with a value of '1'
                $("#fornecedoresAddForm #codTipo").trigger('change');
                $(document).on('select2:open', () => {
                    document.querySelector('.select2-search__field').focus();
                });

            }
        })




        $.ajax({
            url: '<?php echo base_url('fornecedores/listaDropDownEstadosFederacao') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            success: function(estadosFederacao) {

                $("#fornecedoresAddForm #codEstadoFederacao").select2({
                    data: estadosFederacao,
                })

                $("#fornecedoresAddForm #codEstadoFederacao").val(null); // Select the option with a value of '1'
                $("#fornecedoresAddForm #codEstadoFederacao").trigger('change');
                $(document).on('select2:open', () => {
                    document.querySelector('.select2-search__field').focus();
                });

            }
        })




        // submit the add from 
        $.validator.setDefaults({
            highlight: function(element) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid').addClass('is-valid');
            },
            errorElement: 'div ',
            errorClass: 'invalid-feedback',
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if ($(element).is('.select')) {
                    element.next().after(error);
                } else if (element.hasClass('select2')) {
                    //error.insertAfter(element);
                    error.insertAfter(element.next());
                } else if (element.hasClass('selectpicker')) {
                    error.insertAfter(element.next());
                } else {
                    error.insertAfter(element);
                }
            },

            submitHandler: function(form) {

                var form = $('#fornecedoresAddForm');
                // remove the text-danger
                $(".text-danger").remove();


                $.ajax({
                    url: '<?php echo base_url('fornecedores/add') ?>',
                    type: 'post',
                    data: form.serialize(), // /converting the form data into array and sending it to server
                    dataType: 'json',
                    beforeSend: function() {
                        //$('#fornecedoresAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
                    },
                    success: function(response) {

                        if (response.success === true) {
                            $('#fornecedoresAddModal').modal('hide');


                            $('#codFornecedor').html('').select2({
                                data: [{
                                    id: '',
                                    text: ''
                                }]
                            });


                            $.ajax({
                                url: '<?php echo base_url('fornecedores/listaDropDownFornecedores') ?>',
                                type: 'post',
                                dataType: 'json',
                                data: {
                                    csrf_sandra: $("#csrf_sandraPrincipal").val(),
                                },
                                success: function(Fornecedores) {

                                    $("#orcamentosAddForm #codFornecedor").select2({
                                        data: Fornecedores,
                                        allowClear: true,
                                        placeholder: 'Procurar Pessoa',
                                        minimumInputLength: 4,
                                        quietMillis: 1000,
                                        dropdownParent: $('#fornecedoresAddModal .modal-content'),
                                        language: {
                                            noResults: function() {
                                                return `<button style="width: 100%" type="button"
                                        class="btn btn-xs btn-primary" 
                                        onClick='addfornecedores()'>+ Adicionar Fornecedor</button>
                                        `;
                                            }
                                        },

                                        escapeMarkup: function(markup) {
                                            return markup;
                                        }
                                    })


                                    $('#orcamentosAddForm #codFornecedor').val(response.codFornecedor);
                                    $('#orcamentosAddForm #codFornecedor').trigger('change');
                                    $(document).on('select2:open', () => {
                                        document.querySelector('.select2-search__field').focus();
                                    });
                                }
                            })




                            var Toast = Swal.mixin({
                                toast: true,
                                position: 'bottom-end',
                                showConfirmButton: false,
                                timer: 2000
                            });
                            Toast.fire({
                                icon: 'success',
                                title: response.messages
                            }).then(function() {
                                $('#data_tablefornecedores').DataTable().ajax.reload(null, false).draw(false);
                            })

                        } else {

                            if (response.messages instanceof Object) {
                                $.each(response.messages, function(index, value) {
                                    var id = $("#" + index);

                                    id.closest('.form-control')
                                        .removeClass('is-invalid')
                                        .removeClass('is-valid')
                                        .addClass(value.length > 0 ? 'is-invalid' : 'is-valid');

                                    id.after(value);

                                });
                            } else {

                                var Toast = Swal.mixin({
                                    toast: true,
                                    position: 'bottom-end',
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                                Toast.fire({
                                    icon: 'error',
                                    title: response.messages
                                })

                            }
                        }
                        $('#fornecedoresAddForm-btn').html('Adicionar');
                    }
                });

                return false;
            }
        });
        $('#fornecedoresAddForm').validate();
    }


    function adicionarAcao() {
        // reset the form 
        $("#historicoAcaoAddForm")[0].reset();
        $(".form-control").removeClass('is-invalid').removeClass('is-valid');
        $('#historicoAcaoAddModal').modal('show');


        $("#historicoAcaoAddForm #codRequisicao").val($("#requisicaoEditForm #codRequisicao").val());

        $.ajax({
            url: '<?php echo base_url('requisicao/listaDropDownTipoAcao') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            success: function(listaHistoricoAcaoAddForm) {

                $("#historicoAcaoAddForm #codTipoAcao").select2({
                    data: listaHistoricoAcaoAddForm,
                })

                $("#historicoAcaoAddForm #codTipoAcao").val(null); // Select the option with a value of '1'
                $("#historicoAcaoAddForm #codTipoAcao").trigger('change');
                $(document).on('select2:open', () => {
                    document.querySelector('.select2-search__field').focus();
                });

            }
        })




        // submit the add from 
        $.validator.setDefaults({
            highlight: function(element) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid').addClass('is-valid');
            },
            errorElement: 'div ',
            errorClass: 'invalid-feedback',
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if ($(element).is('.select')) {
                    element.next().after(error);
                } else if (element.hasClass('select2')) {
                    //error.insertAfter(element);
                    error.insertAfter(element.next());
                } else if (element.hasClass('selectpicker')) {
                    error.insertAfter(element.next());
                } else {
                    error.insertAfter(element);
                }
            },

            submitHandler: function(form) {

                var form = $('#historicoAcaoAddForm');
                // remove the text-danger
                $(".text-danger").remove();

                $.ajax({
                    url: '<?php echo base_url('historicoAcoes/add') ?>',
                    type: 'post',
                    data: form.serialize(), // /converting the form data into array and sending it to server
                    dataType: 'json',
                    beforeSend: function() {
                        //$('#historicoAcaoAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
                    },
                    success: function(response) {

                        if (response.success === true) {
                            $('#historicoAcaoAddModal').modal('hide');

                            document.getElementById('htmlAcoes').innerHTML = '';

                            $.ajax({
                                url: '<?php echo base_url('requisicao/pegaHistoricoAcoes') ?>',
                                type: 'post',
                                dataType: 'json',
                                data: {
                                    codRequisicao: $("#requisicaoEditForm #codRequisicao").val(),
                                    csrf_sandra: $("#csrf_sandraPrincipal").val(),
                                },
                                success: function(historicoRequisicoes) {
                                    document.getElementById('htmlAcoes').innerHTML = historicoRequisicoes.html;
                                }
                            });



                            var Toast = Swal.mixin({
                                toast: true,
                                position: 'bottom-end',
                                showConfirmButton: false,
                                timer: 2000
                            });
                            Toast.fire({
                                icon: 'success',
                                title: response.messages
                            }).then(function() {

                            })

                        } else {

                            if (response.messages instanceof Object) {
                                $.each(response.messages, function(index, value) {
                                    var id = $("#" + index);

                                    id.closest('.form-control')
                                        .removeClass('is-invalid')
                                        .removeClass('is-valid')
                                        .addClass(value.length > 0 ? 'is-invalid' : 'is-valid');

                                    id.after(value);

                                });
                            } else {

                                var Toast = Swal.mixin({
                                    toast: true,
                                    position: 'bottom-end',
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                                Toast.fire({
                                    icon: 'error',
                                    title: response.messages
                                })

                            }
                        }
                        $('#historicoAcaoAddForm-btn').html('Adicionar');
                    }
                });

                return false;
            }
        });
        $('#historicoAcaoAddForm').validate();
    }

    function addinformacoesComplementares() {
        // reset the form 
        $("#informacoesComplementaresAddForm")[0].reset();
        $(".form-control").removeClass('is-invalid').removeClass('is-valid');
        $('#informacoesComplementaresAddModal').modal('show');


        $.ajax({
            url: '<?php echo base_url('InformacoesComplementares/listaDropDownCategoriaInformacoesComplementares') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            success: function(listaInformacoesComplementaresAddForm) {

                $("#informacoesComplementaresAddForm #codCategoriaInforComplementar").select2({
                    data: listaInformacoesComplementaresAddForm,
                })

                $("#informacoesComplementaresAddForm #codCategoriaInforComplementar").val(null); // Select the option with a value of '1'
                $("#informacoesComplementaresAddForm #codCategoriaInforComplementar").trigger('change');
                $(document).on('select2:open', () => {
                    document.querySelector('.select2-search__field').focus();
                });

            }
        })



        $("#informacoesComplementaresAddForm #conteudo").summernote('destroy');
        $("#informacoesComplementaresAddForm #conteudo").val(null);
        $(function() {

            //ADD text editor
            $("#informacoesComplementaresAddForm #conteudo").summernote({
                height: 150,
                maximumImageFileSize: 1024 * 1024, // 1Mb
                fontSizes: ['8', '9', '10', '11', '12', '14', '18', '20', '36', '72'],
                lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
                toolbar: [
                    ['style', ['style']],
                    ['fontname', ['fontname']],
                    ['font', ['color', 'strikethrough', 'superscript', 'subscript', 'bold', 'underline', 'clear']],
                    ['fontsize', ['fontsize']],
                    ['height', ['height']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video', 'hr']],
                    ['view', ['fullscreen', 'codeview', 'help']],
                    ['redo'],
                    ['undo'],
                ],
                callbacks: {
                    onImageUploadError: function(msg) {
                        var Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000
                        });
                        Toast.fire({
                            icon: 'error',
                            title: 'Tamanho máximo de imagens é 1Mb'
                        })
                    }
                }
            })
        })




        // submit the add from 
        $.validator.setDefaults({
            highlight: function(element) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid').addClass('is-valid');
            },
            errorElement: 'div ',
            errorClass: 'invalid-feedback',
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if ($(element).is('.select')) {
                    element.next().after(error);
                } else if (element.hasClass('select2')) {
                    //error.insertAfter(element);
                    error.insertAfter(element.next());
                } else if (element.hasClass('selectpicker')) {
                    error.insertAfter(element.next());
                } else {
                    error.insertAfter(element);
                }
            },

            submitHandler: function(form) {

                var form = $('#informacoesComplementaresAddForm');
                // remove the text-danger
                $(".text-danger").remove();

                $.ajax({
                    url: '<?php echo base_url('informacoesComplementares/add') ?>',
                    type: 'post',
                    data: form.serialize(), // /converting the form data into array and sending it to server
                    dataType: 'json',
                    beforeSend: function() {
                        //$('#informacoesComplementaresAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
                    },
                    success: function(response) {

                        if (response.success === true) {
                            $('#informacoesComplementaresAddModal').modal('hide');

                            var Toast = Swal.mixin({
                                toast: true,
                                position: 'bottom-end',
                                showConfirmButton: false,
                                timer: 2000
                            });
                            Toast.fire({
                                icon: 'success',
                                title: response.messages
                            }).then(function() {
                                $('#data_tableinformacoesComplementares').DataTable().ajax.reload(null, false).draw(false);
                            })

                        } else {

                            if (response.messages instanceof Object) {
                                $.each(response.messages, function(index, value) {
                                    var id = $("#" + index);

                                    id.closest('.form-control')
                                        .removeClass('is-invalid')
                                        .removeClass('is-valid')
                                        .addClass(value.length > 0 ? 'is-invalid' : 'is-valid');

                                    id.after(value);

                                });
                            } else {

                                var Toast = Swal.mixin({
                                    toast: true,
                                    position: 'bottom-end',
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                                Toast.fire({
                                    icon: 'error',
                                    title: response.messages
                                })

                            }
                        }
                        $('#informacoesComplementaresAddForm-btn').html('Adicionar');
                    }
                });

                return false;
            }
        });
        $('#informacoesComplementaresAddForm').validate();
    }

    function editinformacoesComplementares(codInforComplementar) {
        $.ajax({
            url: '<?php echo base_url('informacoesComplementares/getOne') ?>',
            type: 'post',
            data: {
                codInforComplementar: codInforComplementar,
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            dataType: 'json',
            success: function(response) {
                // reset the form 
                $("#informacoesComplementaresEditForm")[0].reset();
                $(".form-control").removeClass('is-invalid').removeClass('is-valid');
                $('#informacoesComplementaresEditModal').modal('show');


                $("#informacoesComplementaresEditForm #codInforComplementar").val(response.codInforComplementar);
                $("#informacoesComplementaresEditForm #codRequisicao").val(response.codRequisicao);
                $("#informacoesComplementaresEditForm #codCategoria").val(response.codCategoria);
                $("#informacoesComplementaresEditForm #conteudo").val(response.conteudo);





                $.ajax({
                    url: '<?php echo base_url('InformacoesComplementares/listaDropDownCategoriaInformacoesComplementares') ?>',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        csrf_sandra: $("#csrf_sandraPrincipal").val(),
                    },
                    success: function(listaInformacoesComplementaresAddForm) {

                        $("#informacoesComplementaresEditForm #codCategoriaInforComplementar").select2({
                            data: listaInformacoesComplementaresAddForm,
                        })

                        $("#informacoesComplementaresEditForm #codCategoriaInforComplementar").val(response.codCategoria); // Select the option with a value of '1'
                        $("#informacoesComplementaresEditForm #codCategoriaInforComplementar").trigger('change');
                        $(document).on('select2:open', () => {
                            document.querySelector('.select2-search__field').focus();
                        });

                    }
                })





                $("#informacoesComplementaresEditForm #conteudo").summernote('destroy');
                $(function() {

                    //ADD text editor
                    $("#informacoesComplementaresEditForm #conteudo").summernote({
                        height: 150,
                        maximumImageFileSize: 1024 * 1024, // 1Mb
                        fontSizes: ['8', '9', '10', '11', '12', '14', '18', '20', '36', '72'],
                        lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
                        toolbar: [
                            ['style', ['style']],
                            ['fontname', ['fontname']],
                            ['font', ['color', 'strikethrough', 'superscript', 'subscript', 'bold', 'underline', 'clear']],
                            ['fontsize', ['fontsize']],
                            ['height', ['height']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['table', ['table']],
                            ['insert', ['link', 'picture', 'video', 'hr']],
                            ['view', ['fullscreen', 'codeview', 'help']],
                            ['redo'],
                            ['undo'],
                        ],
                        callbacks: {
                            onImageUploadError: function(msg) {
                                var Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 5000
                                });
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Tamanho máximo de imagens é 1Mb'
                                })
                            }
                        }
                    })
                })




                // submit the edit from 
                $.validator.setDefaults({
                    highlight: function(element) {
                        $(element).addClass('is-invalid').removeClass('is-valid');
                    },
                    unhighlight: function(element) {
                        $(element).removeClass('is-invalid').addClass('is-valid');
                    },
                    errorElement: 'div ',
                    errorClass: 'invalid-feedback',
                    errorPlacement: function(error, element) {
                        if (element.parent('.input-group').length) {
                            error.insertAfter(element.parent());
                        } else if ($(element).is('.select')) {
                            element.next().after(error);
                        } else if (element.hasClass('select2')) {
                            //error.insertAfter(element);
                            error.insertAfter(element.next());
                        } else if (element.hasClass('selectpicker')) {
                            error.insertAfter(element.next());
                        } else {
                            error.insertAfter(element);
                        }
                    },

                    submitHandler: function(form) {
                        var form = $('#informacoesComplementaresEditForm');
                        $(".text-danger").remove();
                        $.ajax({
                            url: '<?php echo base_url('informacoesComplementares/edit') ?>',
                            type: 'post',
                            data: form.serialize(),
                            dataType: 'json',
                            beforeSend: function() {
                                //$('#informacoesComplementaresEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
                            },
                            success: function(response) {

                                if (response.success === true) {

                                    $('#informacoesComplementaresEditModal').modal('hide');


                                    var Toast = Swal.mixin({
                                        toast: true,
                                        position: 'bottom-end',
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    Toast.fire({
                                        icon: 'success',
                                        title: response.messages
                                    }).then(function() {
                                        $('#data_tableinformacoesComplementares').DataTable().ajax.reload(null, false).draw(false);
                                    })

                                } else {

                                    if (response.messages instanceof Object) {
                                        $.each(response.messages, function(index, value) {
                                            var id = $("#" + index);

                                            id.closest('.form-control')
                                                .removeClass('is-invalid')
                                                .removeClass('is-valid')
                                                .addClass(value.length > 0 ? 'is-invalid' : 'is-valid');

                                            id.after(value);

                                        });
                                    } else {
                                        var Toast = Swal.mixin({
                                            toast: true,
                                            position: 'bottom-end',
                                            showConfirmButton: false,
                                            timer: 2000
                                        });
                                        Toast.fire({
                                            icon: 'error',
                                            title: response.messages
                                        })

                                    }
                                }
                                $('#informacoesComplementaresEditForm-btn').html('Salvar');
                            }
                        });

                        return false;
                    }
                });
                $('#informacoesComplementaresEditForm').validate();

            }
        });
    }

    function removeinformacoesComplementares(codInforComplementar) {
        Swal.fire({
            title: 'Você tem certeza que deseja remover?',
            text: "Você não poderá reverter após a confirmação",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {

            if (result.value) {
                $.ajax({
                    url: '<?php echo base_url('informacoesComplementares/remove') ?>',
                    type: 'post',
                    data: {
                        codInforComplementar: codInforComplementar,
                        csrf_sandra: $("#csrf_sandraPrincipal").val(),
                    },
                    dataType: 'json',
                    success: function(response) {

                        if (response.success === true) {
                            var Toast = Swal.mixin({
                                toast: true,
                                position: 'bottom-end',
                                showConfirmButton: false,
                                timer: 2000
                            });
                            Toast.fire({
                                icon: 'success',
                                title: response.messages
                            }).then(function() {
                                $('#data_tableinformacoesComplementares').DataTable().ajax.reload(null, false).draw(false);
                            })
                        } else {
                            var Toast = Swal.mixin({
                                toast: true,
                                position: 'bottom-end',
                                showConfirmButton: false,
                                timer: 2000
                            });
                            Toast.fire({
                                icon: 'error',
                                title: response.messages
                            })


                        }
                    }
                });
            }
        })
    }


    function clonarequisicao(codRequisicao) {
        Swal.fire({
            title: 'Você tem certeza que deseja clonar esta requisição?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, clonar',
            cancelButtonText: 'Não'
        }).then((result) => {

            if (result.value) {
                $.ajax({
                    url: '<?php echo base_url('requisicao/clonarRequisicao') ?>',
                    type: 'post',
                    data: {
                        codRequisicao: codRequisicao,
                        csrf_sandra: $("#csrf_sandraPrincipal").val(),
                    },
                    dataType: 'json',
                    success: function(response) {

                        if (response.success === true) {
                            var Toast = Swal.mixin({
                                toast: true,
                                position: 'bottom-end',
                                showConfirmButton: false,
                                timer: 2000
                            });
                            Toast.fire({
                                icon: 'success',
                                title: response.messages
                            }).then(function() {
                                $('#data_tablerequisicao').DataTable().ajax.reload(null, false).draw(false);
                            })
                        } else {
                            var Toast = Swal.mixin({
                                toast: true,
                                position: 'bottom-end',
                                showConfirmButton: false,
                                timer: 2000
                            });
                            Toast.fire({
                                icon: 'error',
                                title: response.messages
                            })


                        }
                    }
                });
            }
        })
    }
</script>