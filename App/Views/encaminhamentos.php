<?php


?>

<style>
    .swal2-container {
        z-index: 9999999;
    }

    .swal-overlay {
        z-index: 100000000000 !important;
    }

    body {
        height: 100vh;
        overflow-y: hidden;
        padding-right: 15px;
        /* Avoid width reflow */
    }
</style>
<!-- Main content -->

<div id="setEstilo"></div>

<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">

                    <div class="row">
                        <div class="col-md-9 mt-2">
                            <h3 style="font-size:30px;font-weight: bold;" class="card-title">Agendamento Paciente</h3>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">

                    <div style="margin-bottom: 30px;margin-left: 10px" class="row">


                        <div class="col-md-3">
                            <div class="form-group">
                                <a onclick="filtrar()" class="btn btn-block btn-outline-secondary btn-lg ">
                                    <div>
                                        <i style="        color: #007bff;" class="fas fa-users zoom fa-3x"></i>
                                    </div>
                                    <div>Buscar vagas </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <a href="<?php echo base_url()?>/meusAgendamentos/?autorizacao=<?php echo session()->autorizacao ?>" class="btn btn-block btn-outline-secondary btn-lg ">
                                    <div>
                                        <i style="        color: #007bff;" class="fas fa-users zoom fa-3x"></i>
                                    </div>
                                    <div>Meus Agendamentos</div>
                                </a>
                            </div>
                        </div>
                       <!-- <div class="col-md-3">
                            <div class="form-group">
                                <a href="<?php echo base_url()?>/GuiasPaciente/devolucao/?autorizacao=<?php echo session()->autorizacao ?>" class="btn btn-block btn-outline-secondary btn-lg ">
                                    <div>
                                        <i style="        color: #007bff;" class="fas fa-users zoom fa-3x"></i>
                                    </div>
                                    <div>Devolução de Guia</div>
                                </a>
                            </div>
                        </div> -->
                        

                    </div>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>

<div id="showPacientesModal" class="modal fade col-md-12" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-center p-3">
                <h4 class="modal-title text-white" id="info-header-modalLabel">Confirmação do Agendamento</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                <div id="dadosConfirmacao"></div>

                <form autocomplete="off" id="escolhaPacienteForm" class="pl-3 pr-3">
                    <div class="row">
                        <input type="hidden" id="<?php echo csrf_token() ?>escolhaPacienteForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>" />

                        <input type="hidden" id="codPacienteMarcacao" value="<?php echo session()->codPaciente ?>" name="codPacienteMarcacao" class="form-control" placeholder="codPacienteMarcacao" maxlength="11" required />
                        <input type="hidden" id="codSenhaAtendimento" name="codSenhaAtendimento" class="form-control" placeholder="codSenhaAtendimento" maxlength="11" required />
                    </div>

                    <div class="form-group text-center">
                        <div class="btn-group">
                            <button type="button" onclick="marcarPaciente()" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Agendar Agora" id="add-formatalhos-btn">Agendar Agora</button>
                            <button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>




<div id="vagasEncaminhamentoModal" class="modal fade col-md-12">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-center p-3">
                <h4 class="modal-title text-white" id="info-header-modalLabel">VAGAS ENCAMINHAMENTO</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="slotVagasLivres" class="row"></div>
            </div>


        </div>
    </div><!-- /.modal-content -->
</div>

<div style="position: fixed;height: 800px" id="comprovanteA4ServicoModal" class="modal fade" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header bg-primary text-center p-3">
                <h4 class="modal-title text-white" id="info-header-modalLabel">Comprovante de Servico</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                <div style="margin-left:10px" id="areaImpressaoComprovanteServicoA4">
                    <div class="row">
                        <div style="width:50% !important" class="col-sm-6 border">

                            <div>
                                <center><img alt="" style="text-align:center;width:60px;height:60px;" src="<?php echo base_url() . "/imagens/organizacoes/" . session()->logo ?>"></center>
                            </div>
                            <div style="text-align:center;font-weight: bold">
                                <?php echo session()->descricaoOrganizacao; ?>



                            </div>

                            <div style="font-family: 'Arial';margin-top:20px;height: 80mm;">
                                <div style="text-align:left;font-weight: bold;font-size:12px">USUÁRIO: <span id="nomeCompletoComprovanteServicoA4"></span></div>
                                <div style="text-align:left;font-weight: bold;font-size:12px">Nº PLANO: <span id="CODPLANOComprovanteServicoA4"></span></div>
                                <div style="text-align:left;font-weight: bold;font-size:12px">Servico: <span id="nomeServicoComprovanteServicoA4"></span></div>
                                <div style="text-align:left;font-weight: bold;font-size:12px">LOCAL DE ATENDIMENTO: <span id="nomeLocalComprovanteServicoA4"></span></div>
                                <div style="text-align:left;font-size:12px">DIA: <span id="dataInicioComprovanteServicoA4"></span></div>
                                <div style="text-align:left;font-size:12px">Protocolo Nr: <span id="protocoloComprovanteServicoA4"></span></div>
                                <div style="text-align:left;font-size:12px"><b>Prontuário Nº: </b>:<span id="codProntuarioComprovanteServicoA4"></span></div>
                                <div style="margin-top:10px" class="d-flex justify-content-left" id="qrcodeComprovanteServicoA4"></div>

                            </div>


                            <div style="margin-top:30px" class="row">
                                <div><b>Marcado Por: </b>:<span id="autorMarcacaoComprovanteServicoA4"></span></div>
                            </div>



                            <div class="row">
                                <?php

                                echo "CPF autor: " . substr(session()->cpf, 0, -6) . '*****' . " | IP:"  . session()->ip
                                ?>
                            </div>
                        </div>
                        <div style="width:50% !important" class="col-sm-6 border">

                            <div style="margin-left:10px;margin-top:10px;font-family: 'Arial';margin-top:20px;text-align:left;font-weight: bold;font-size:12px">
                                <div class="row">
                                    <b>Prezado usuário, leia atentamente as orientações a seguir:</b>
                                </div>
                                <div class="row">
                                    * Este é seu comprovante de marcação do Servico.
                                </div>

                                <div class="row">
                                    * Compareça no dia do Servico 30 minutos antes.
                                </div>

                                <div class="row">
                                    * Esta Servico só pode ser desmarcada até 24 horas antes. Para desmarcar utilize nossa plataforma online através do endereço <?php echo base_url() ?>, contate-nos através do telefone <?php echo session()->telefoneOrganizacao ?>
                                </div>

                                <div class="row">
                                    * Evite faltas, compareça ao Servico.
                                </div>
                                <div class="row">
                                    * Evite bloqueio de marcações de Servico por motivo de faltas.
                                </div>

                                <div class="row">
                                    * Evite atrasos.
                                </div>

                            </div>

                        </div>

                    </div>






                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="botaoImprimirComprovanteServicoA4">Imprimir</button>
                <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
            </div>
        </div>



    </div>
</div>




<?php
echo view('tema/rodape');
?>
<script>
    $(document).on('show.bs.modal', '.modal', function() {
        var zIndex = 1040 + (10 * $('.modal:visible').length);
        $(this).css('z-index', zIndex);
        setTimeout(function() {
            $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
    });

    avisoPesquisa('Agendamento', 2);


    function filtrar() {

        $.ajax({
            url: '<?php echo base_url('encaminhamentos/agendamentosLivres') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            success: function(responseAgendamentos) {
                swal.close();



                if (responseAgendamentos.existeAgendamento === true) {

                    Swal.fire({
                        icon: 'info',
                        html: responseAgendamentos.messages,
                        showConfirmButton: true,
                        showCancelButton: true,
                        confirmButtonText: 'Ok',
                        cancelButtonColor: '#d33',
                        focusConfirm: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        cancelButtonText: "CANCELAR AGENDAMENTO",
                    }).then((result) => {
                        if (!result.value) {


                            Swal.fire({
                                title: 'Você tem certeza que deseja cancelar o agendamento?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Confirmar',
                                cancelButtonText: 'Sair sem cancelar'
                            }).then((result) => {

                                if (result.value) {


                                    $.ajax({
                                        url: '<?php echo base_url('atendimentoSenhas/removerAgendamento') ?>',
                                        type: 'post',
                                        dataType: 'json',
                                        data: {
                                            csrf_sandra: $("#csrf_sandraPrincipal").val(),
                                            codSenhaAtendimento: responseAgendamentos.codSenhaAtendimento,
                                        },
                                        success: function(agendamentoRemovido) {


                                            Swal.fire({
                                                icon: 'success',
                                                html: agendamentoRemovido.messages,
                                                showConfirmButton: true,
                                                confirmButtonText: 'Ok',
                                            })

                                        }
                                    })






                                }
                            })





                        }
                    })
                }

                if (responseAgendamentos.success === true) {

                    $('#vagasEncaminhamentoModal').modal('show');


                    document.getElementById('slotVagasLivres').innerHTML = responseAgendamentos.slotsLivres;

                }
            }
        }).always(
            Swal.fire({
                title: 'Estamos buscando possíveis vagas no sistema',
                html: 'Aguarde....',
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading()


                }

            }))


    }




    function escolhaPaciente(codSenhaAtendimento) {
        // reset the form
        $("#escolhaPacienteForm")[0].reset();
        $('#showPacientesModal').modal('show');

        $("#escolhaPacienteForm #codSenhaAtendimento").val(codSenhaAtendimento);

        //UPDATE PARA RESERVAR POR 1 MINUTO O SLOT E EVITAR CONFLITOS
        $.ajax({
            url: '<?php echo base_url('encaminhamentos/reservaUmMinuto') ?>',
            type: 'post',
            data: {
                codSenhaAtendimento: codSenhaAtendimento,
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            dataType: 'json',
            success: function(reservaUmMinuto) {

                document.getElementById('dadosConfirmacao').innerHTML = reservaUmMinuto.dadosConfirmacao;


            }
        })


    }



    function marcarPaciente() {

        var form = $('#escolhaPacienteForm');
        var codPacienteMarcacao = document.getElementById('codPacienteMarcacao').value;
        var codSenhaAtendimento = document.getElementById('codSenhaAtendimento').value;


        $.ajax({
            url: '<?php echo base_url('encaminhamentos/marcarPaciente') ?>',
            type: 'post',
            data: {
                codPacienteMarcacao: codPacienteMarcacao,
                codSenhaAtendimento: codSenhaAtendimento,
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            dataType: 'json',
            success: function(marcacaoPaciente) {

                $('#showPacientesModal').modal('hide');
                $('#vagasEncaminhamentoModal').modal('hide');
                if (marcacaoPaciente.success === true) {

                    document.getElementById("slotVagasLivres").innerHTML = '';
                    // swal.close();

                    //comprovanteA4(marcacaoPaciente.codSenhaAtendimento);
                    /* Swal.fire({
                        icon: 'success',
                        html: marcacaoPaciente.messages,
                        showConfirmButton: true,
                        confirmButtonText: 'Ok',
                    }).then(function() {


                    })
                    */


                    avisoPesquisa('Agendamento', 1);

                    swal.close();
                    comprovanteA4Servico(codSenhaAtendimento);


                } else {

                    Swal.fire({
                        position: 'bottom-end',
                        icon: 'error',
                        html: marcacaoPaciente.messages,
                        showConfirmButton: true,
                        confirmButtonText: 'Ok',
                    }).then((result) => {

                        if (result.value) {
                            filtrar();
                        }
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
    }

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


    function comprovanteA4Servico(codAgendamento) {

        $('#comprovanteA4ServicoModal').modal('show');

        document.getElementById("botaoImprimirComprovanteServicoA4").onclick = function() {
            printElement(document.getElementById("areaImpressaoComprovanteServicoA4"));

            window.print();
        }

        $.ajax({
            url: '<?php echo base_url('encaminhamentos/comprovante') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                codAgendamento: codAgendamento,
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            success: function(ServicoComprovante) {
                document.getElementById("nomeCompletoComprovanteServicoA4").innerHTML = ServicoComprovante.nomePaciente;
                document.getElementById("CODPLANOComprovanteServicoA4").innerHTML = ServicoComprovante.codPlano;
                document.getElementById("nomeServicoComprovanteServicoA4").innerHTML = ServicoComprovante.descricaoDepartamento;
                document.getElementById("nomeLocalComprovanteServicoA4").innerHTML = ServicoComprovante.descricaoDepartamento;
                document.getElementById("protocoloComprovanteServicoA4").innerHTML = ServicoComprovante.protocolo;
                document.getElementById("codProntuarioComprovanteServicoA4").innerHTML = ServicoComprovante.codProntuario;
                document.getElementById("autorMarcacaoComprovanteServicoA4").innerHTML = ServicoComprovante.autorMarcacao;



                document.getElementById("dataInicioComprovanteServicoA4").innerHTML = ServicoComprovante.dataInicio;
                var URLComprovante = '<?php echo base_url() . "/Servicos/?codServico=" ?>' + ServicoComprovante.codAgendamento + '&chechsum=' + ServicoComprovante.valorChecksum;

                document.getElementById("qrcodeComprovanteServicoA4").innerHTML = "";

                qrcode = new QRCode("qrcodeComprovanteServicoA4", {
                    text: URLComprovante,
                    width: 160,
                    height: 160,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });


                document.getElementById("setEstilo").innerHTML = '<style>@media screen {' +
                    '#printSection {' +
                    'display: none;' +

                    '}' +
                    '}' +

                    '@media print {' +
                    '@page {' +
                    'size: A4;' +
                    'margin: 5px;' +
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
                    'width: 210mm;' +
                    'height: 297mm;' +

                    '}' +
                    '}</style>';


            }

        })


    }
</script>