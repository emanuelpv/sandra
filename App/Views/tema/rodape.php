<?php
//É NECESSÁRIO EM TODAS AS VIEWS

/*
// REDIRECIONA PARA TELA DE LOGIN CASO NÃO ESTAJA LOGADO
if (session()->getTempdata('estaLogado') == NULL) {
    return redirect()->to(base_url() . '/login/logout');
}
*/
?>

<!-- /    FOI TRAZIDO DAS VIEW  -->
<!-- /.content -->
</div>


<!-- /.content-wrapper -->
<footer class="main-footer">
    <div class="float-right d-none d-sm-block">
        <b>Version</b> 2.0
    </div>
    <strong><span>Desenvolvedor Emanuel Peixoto Vicente | <a href="https://www.linkedin.com/in/emanuelpv/">https://www.linkedin.com/in/emanuelpv/</a></span></strong>
    <div>

        <body>
            <!-- <div>Registration closes in <span id="time">05:00</span> minutes!</div> -->
        </body>
    </div>
</footer>

<!-- Control Sidebar -->





<aside style="margin-top:100px" id="control_sidebar" name="control_sidebar" class="control-sidebar text-white bg-dark elevation-4">
    <!-- Control sidebar content goes here -->
    <style>
        .sidebar .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }
    </style>
    <div style="font-size:30px;text-align:right;margin-right:10px" class="text-primary">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
    </div>
    <script>
        function closeNav() {

            document.getElementById("control_sidebar").style.display = "none";



        }
    </script>
    <div class="p-3 control-sidebar-content ">

        <nav class="p-3">

            <ul class="nav nav-pills menu-perfil nav-sidebar flex-column" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

                <?php
                if (session()->login !== 'admin') {


                    if (session()->codPaciente !== NULL) {
                ?>
                        <li class="nav-item">
                            <?php
                            //$checksum = hash('md5', session()->codPaciente . session()->login);
                            ?>
                            <a href="#" onclick="editPacienteLogado(<?php echo session()->codPaciente ?>)" class="nav-link menu-perfil">
                                <i class="far fa-address-card nav-icon"></i>
                                <p>Perfil</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" onclick="trocasenhaPaciente(<?php echo session()->codPaciente ?>)" class="nav-link menu-perfil">
                                <i class="fas fa-user-lock nav-icon"></i>
                                <p>Troca Senha</p>
                            </a>
                        </li>

                    <?php

                    } else {
                    ?>
                        <li class="nav-item">
                            <?php
                            //$checksum = hash('md5', session()->codPessoa . session()->login);
                            ?>
                            <a href="#" onclick="editPessoaLogada(<?php echo session()->codPessoa ?>)" class="nav-link  menu-perfil">
                                <i class="far fa-address-card nav-icon"></i>
                                <p>Perfil</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo base_url('veiculos') ?>" class="nav-link  menu-perfil">
                                <i class="fas fa-car nav-icon"></i>
                                <p>Veículos</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="#" onclick="trocasenhaPessoaLogada()" class="nav-link menu-perfil">
                                <i class="fas fa-user-lock nav-icon"></i>
                                <p>Troca Senha</p>
                            </a>
                        </li>
                <?php

                    }
                }

                ?>


                <li class="nav-item">
                    <a href="<?php echo base_url() . '/login/logout/?autorizacao=' . session()->autorizacao ?>" class="nav-link  menu-perfil">
                        <i class="fas fa-sign-out-alt nav-icon"></i>
                        <p>Sair</p>
                    </a>
                </li>


            </ul>
        </nav>
    </div>
</aside>




<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/jquery-ui/jquery-ui.min.js"></script>
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- jquery-validation -->
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/jquery-validation/additional-methods.min.js"></script>
<!-- SweetAlert2 -->
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url() ?>/assets/adminlte/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes - NÃO USAR -->
<!-- <script src=" echo base_url() /assets/adminlte/dist/js/demo.js"></script> -->
<!-- page script -->

<script src="<?php echo base_url() ?>/assets/adminlte/plugins/select2/js/select2.full.min.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/toastr/toastr.min.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/qrcode/qrcode.js"></script>



<!-- DATATABLES -->
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/jszip/jszip.min.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/cryptoJS/rollups/sha256.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/inputmask/jquery.inputmask.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/autocomplete/autocomplete.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/dropzone/min/dropzone.min.js"></script>





<?php
// VERIFICA SE AMBIENTE DE TESTE

if (session()->ambienteTeste == "1") {
    ?>
    <script>
        $(".modal").on('shown.bs.modal', function() {
            $('.modal-backdrop').css('background', 'red');
        });
    </script>

<?php
}

?>



<script>
    $(function() {


        //VARIÁVEL GLOBAL UTILIZADA PELAS PESQUISAS
        moduloTmp = "";

        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })

        //Bootstrap Duallistbox
        $('.duallistbox').bootstrapDualListbox()


        $('#perfilGeral').on('change', function() {
            window.open("<?php echo base_url() . '/principal/mudaPerfil/' . session()->codPessoa . '/' ?>" + this.value, '_self');

        });


    });



    $(function() {





        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000
        });

        $('.swaltimezone').click(function() {
            Toast.fire({
                icon: 'info',
                title: 'Informa o timezone global da organização'
            })
        });

        $('.swalAutoCadastro').click(function() {
            Toast.fire({
                icon: 'info',
                title: ' Ativa funcionalidade de auto cadastramento dos usuários'
            })
        });
        $('.swalSenhaSlgada').click(function() {
            Toast.fire({
                icon: 'info',
                title: ' Define que o HASH das senhas dos usuários serão salgada com uma chave pré-definida'
            })
        });

        $('.swalTempoInatividade').click(function() {
            Toast.fire({
                icon: 'info',
                title: ' Define o tempo de expiração da sessão do usuário'
            })
        });

        $('.swalforcarExpiracao').click(function() {
            Toast.fire({
                icon: 'info',
                title: ' Força a expiração da sessão do usuário, independentemente de inatividade no sistema'
            })
        });
        $('.swalLoginAdmin').click(function() {
            Toast.fire({
                icon: 'info',
                title: ' Define o login do administrador global dentro da Organização'
            })
        });

        $('.swalSenhaAdmin').click(function() {
            Toast.fire({
                icon: 'info',
                title: ' Define senha do administrador global dentro da Organização'
            })
        });






        $('input[type="file"]').change(function(imagem) {
            var fileName = imagem.target.files[0].name;


            var tiposImagens = ['efbbbf6c', '47727570', '5b7b2273', '3c3f7068', 'd0cf11e0', '504b34', '504b34', '424d125c', '89504e47', '25504446', '89504e47', '47494638', 'ffd8ffe0', 'ffd8ffe1', 'ffd8ffe2', 'ffd8ffe3', 'ffd8ffe8'];
            var blacklist = ['php', 'alert', 'script', '=', '?', 'audio', 'object', 'applet', 'frame', 'iframe', 'body', 'head', 'form', 'input', 'type', 'textarea', 'select', 'code', 'css', 'xmp', 'listing', 'shell', 'alert', 'onload', 'xmp'];


            var blob = imagem.target.files[0]; // See step 1 above


            var fileReader = new FileReader();

            //VERIFICA TIPO E CONTEÚDO


            fileReader.onloadend = function(exxx) {
                var arr = (new Uint8Array(exxx.target.result)).subarray(0, 4);

                var header = "";
                for (var i = 0; i < arr.length; i++) {
                    header += arr[i].toString(16);
                }



                //alert(JSON.stringify(header));

                if (tiposImagens.includes(header) === false) {
                    $('.modal').modal('hide');
                    alert('Arquivo Inválido, não será carregada.');
                    // location.reload();
                    //event.preventDefault();
                    throw new Error('Arquivo Inválido, não será carregada.');

                }



            }
            fileReader.readAsArrayBuffer(blob);




            //VERIFICA NOME

            for (i = 0; i < blacklist.length; i++) {

                text = blob.name;
                result = text.match(blacklist[i]);

                if (result) {
                    $('.modal').modal('hide');
                    alert('Nome do arquivo inválido, não será carregado!');
                    throw new Error('Nome do arquivo inválido, não será carregado.');
                    // location.reload();
                    // event.preventDefault();
                }

            }



        });









    });
</script>







<script>
    function salvarPacienteSelf() {


        var form = $('#editPacienteFormSelf');
        $.ajax({
            url: '<?php echo base_url('verificalogin/atualizaPaciente') ?>',
            type: 'post',
            data: form.serialize(), // /converting the form data into array and sending it to server
            dataType: 'json',
            success: function(editPaciente) {

                if (editPaciente.success === true) {



                    $('#editPacienteModal').modal('hide');
                    var Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 5000
                    });
                    Toast.fire({
                        icon: 'success',
                        title: editPaciente.messages
                    }).then(function() {
                        $('#data_tablepaciente').DataTable().ajax.reload(null, false).draw(false);
                    })





                }

                if (editPaciente.success === false) {

                    var Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 5000
                    });
                    Toast.fire({
                        icon: 'error',
                        title: editPaciente.messages
                    })


                }
            }
        }).always(
            Swal.fire({
                title: 'Estamos atualizando as informações do paciente',
                html: 'Aguarde....',
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading()


                }

            }))

    }



    function alteraContatoSelf() {



        var form = $('#alteracaoContatoForm');
        $.ajax({
            url: '<?php echo base_url('pacientes/alterarContato') ?>',
            type: 'post',
            data: form.serialize(), // /converting the form data into array and sending it to server
            dataType: 'json',
            success: function(atualizarContatos) {


                if (atualizarContatos.success === true) {


                    $('#alteracaoOutrosContatosModal').modal('hide');

                    $('#data_outrosContatosSelf').DataTable().ajax.reload(null, false).draw(false);



                    var Toast = Swal.mixin({
                        toast: true,
                        position: 'bottom-end',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    Toast.fire({
                        icon: 'success',
                        title: atualizarContatos.messages,
                    })

                } else {
                    Swal.fire({
                        position: 'bottom-end',
                        icon: 'error',
                        html: atualizarContatos.messages,
                        showConfirmButton: false,
                        timer: 4000
                    })
                }
            }
        })
    }



    function modificarContatoSelf(codContato) {
        $("#alteracaoContatoForm")[0].reset();
        $(".form-control").removeClass('is-invalid').removeClass('is-valid');
        $('#alteracaoOutrosContatosModal').modal('show');



        $.ajax({
            url: '<?php echo base_url('pacientes/getContato') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                codContato: codContato,
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            success: function(modificaContato) {




                document.getElementById("codOutroContatoAlteracao").value = modificaContato.codOutroContato;
                document.getElementById("codTipoContatoAlteracao").value = modificaContato.codTipoContato;
                document.getElementById("codParentescoAlteracao").value = modificaContato.codParentesco;
                document.getElementById("nomeContatoAlteracao").value = modificaContato.nomeContato;
                document.getElementById("numeroContatoAlteracao").value = modificaContato.numeroContato;
                document.getElementById("observacoesAlteracao").value = modificaContato.observacoes;



                $.ajax({
                    url: '<?php echo base_url('pacientes/listaDropDownParentesco') ?>',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        csrf_sandra: $("#csrf_sandraPrincipal").val(),
                    },
                    success: function(parentescoEdit) {

                        $("#codParentescoEdit").select2({
                            data: parentescoEdit,
                        })

                        $('#codParentescoEdit').val(null); // Select the option with a value of '1'
                        $('#codParentescoEdit').trigger('change');
                        $(document).on('select2:open', () => {
                            document.querySelector('.select2-search__field').focus();
                        });

                    }
                })







            }
        })




    }




    function removeContatoSelf(codOutroContato) {
        Swal.fire({
            title: 'Você tem certeza que deseja remover este contato?',
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
                    url: '<?php echo base_url('verificalogin/removeContatoSelf') ?>',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        codOutroContato: codOutroContato,
                        csrf_sandra: $("#csrf_sandraPrincipal").val(),
                    },
                    success: function(incluirContatosAdd) {

                        if (incluirContatosAdd.success === true) {

                            $('#data_outrosContatosSelf').DataTable().ajax.reload(null, false).draw(false);
                        }
                    }
                })




            }
        })
    }


    function incluiContatoPaciente() {


        var form = $('#editIncluiContatoFormSelf');
        $.ajax({
            url: '<?php echo base_url('verificalogin/incluirContatoSelf') ?>',
            type: 'post',
            data: form.serialize(), // /converting the form data into array and sending it to server
            dataType: 'json',
            success: function(incluirContatosSelf) {


                $('#editOutrosContatosModalPaciente').modal('hide');

                if (incluirContatosSelf.success === true) {

                    $('#data_outrosContatosSelf').DataTable().ajax.reload(null, false).draw(false);

                } else {
                    Swal.fire({
                        position: 'bottom-end',
                        icon: 'error',
                        html: incluirContatosSelf.messages,
                        showConfirmButton: false,
                        timer: 4000
                    })
                }
            }
        })
    }



    


    function modalOutrosContatosPaciente() {
        $("#editIncluiContatoFormSelf")[0].reset();
        $('#editOutrosContatosModalPaciente').modal('show');




        $.ajax({
            url: '<?php echo base_url('verificalogin/listaDropDownTiposContatos') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            success: function(tipoContatoSelf) {

                $("#codTipoContatoSelf").select2({
                    data: tipoContatoSelf,
                })

                $('#codTipoContatoSelf').val(null); // Select the option with a value of '1'
                $('#codTipoContatoSelf').trigger('change');
                $(document).on('select2:open', () => {
                    document.querySelector('.select2-search__field').focus();
                });

            }
        })

        $.ajax({
            url: '<?php echo base_url('verificalogin/listaDropDownParentesco') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            success: function(parentescoSelf) {

                $("#codParentescoSelf").select2({
                    data: parentescoSelf,
                })

                $('#codParentescoSelf').val(null); // Select the option with a value of '1'
                $('#codParentescoSelf').trigger('change');
                $(document).on('select2:open', () => {
                    document.querySelector('.select2-search__field').focus();
                });

            }
        })

    }


    function emManutencao() {
        alert('Em Manutenção');
    }

    function editPacienteLogado(codPaciente) {

        $("#editPacienteFormSelf")[0].reset();
        $(".form-control").removeClass('is-invalid').removeClass('is-valid');
        $('#editPacienteLogado').modal('show');



        fotoPerfilTmp = 0;
        // reset the form 
        $.ajax({
            url: '<?php echo base_url('pacientes/limpaOutrosContatosTmp') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            success: function(limpaContatos) {}
        })





        $.ajax({
            url: '<?php echo base_url('verificalogin/getOne') ?>',
            type: 'post',
            data: {
                codPaciente: codPaciente,
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            dataType: 'json',
            success: function(responseSelf) {

                if (responseSelf.acessoNegado === true) {
                    Swal.fire({
                        position: 'bottom-end',
                        icon: 'warning',
                        html: responseSelf.messages,
                        showConfirmButton: false,
                        timer: 4000
                    })
                    exit();

                }
                if (responseSelf.codProntuario !== null) {
                    prontuarioTitulo = responseSelf.codProntuario
                } else {
                    prontuarioTitulo = "";
                }
                $('#tituloSelfModal').text(responseSelf.nomeCompleto + " (PRONTUÁRIO:" + prontuarioTitulo + ")");

                $("#editPacienteFormSelf #codPacienteSelf").val(codPaciente);
                $("#editPacienteFormSelf #codOrganizacaoSelf").val(responseSelf.codOrganizacao);
                $("#editPacienteFormSelf #sexoSelf").val(responseSelf.sexo);
                $("#editPacienteFormSelf #dataInicioEmpresaSelf").val(responseSelf.dataInicioEmpresa);
                $("#editPacienteFormSelf #dataNascimentoSelf").val(responseSelf.dataNascimento);
                $("#editPacienteFormSelf #validadeSelf").val(responseSelf.validade);
                $("#editPacienteFormSelf #nomeCompletoSelf").val(responseSelf.nomeCompleto);
                $("#editPacienteFormSelf #codPlanoSelf").val(responseSelf.codPlano);
                $("#editPacienteFormSelf #cpfSelf").val(responseSelf.cpf);
                $("#editPacienteFormSelf #identidadeSelf").val(responseSelf.identidade);
                $("#editPacienteFormSelf #nomePaiSelf").val(responseSelf.nomePai);
                $("#editPacienteFormSelf #nomeMaeSelf").val(responseSelf.nomeMae);
                $("#editPacienteFormSelf #emailPessoalSelf").val(responseSelf.emailPessoal);
                $("#editPacienteFormSelf #celularSelf").val(responseSelf.celular);
                $("#editPacienteFormSelf #enderecoSelf").val(responseSelf.endereco);
                $("#editPacienteFormSelf #cepSelf").val(responseSelf.cep);
                $("#editPacienteFormSelf #informacoesComplementaresSelf").val(responseSelf.informacoesComplementares);

                $("#editIncluiContatoFormSelf #codPacienteOutrosContatosSelf").val(codPaciente);

                base_url = "<?php echo base_url() ?>";
                document.getElementById('fotoPerfilCadastroSelf').innerHTML =
                    '<img alt="" width="160px" height="125px" src="' + base_url + '/arquivos/imagens/pacientes/' + responseSelf.fotoPerfil + '"/>';


                document.getElementById('fotoPerfilFormularioSelf').innerHTML =
                    '<img alt="" width="160px" height="125px" src="' + base_url + '/arquivos/imagens/pacientes/' + responseSelf.fotoPerfil + '"/>';





                if (responseSelf.codProntuario !== null) {
                    document.getElementById('codProntuarioInfoSelf').innerHTML =
                        '<span>' + responseSelf.codProntuario + '</span>';
                }

                nomeStatus = "";
                if (responseSelf.codStatusCadastroPaciente == 1) {
                    nomeStatus = "NOVO";
                    document.getElementById("codStatusCadastroPacienteColorSelf").style.backgroundColor = "#ff000091";
                    //document.getElementById("codStatusCadastroPacienteColor").style.color = "red";
                }
                if (responseSelf.codStatusCadastroPaciente == 2) {
                    nomeStatus = "LIBERADO";
                    document.getElementById("codStatusCadastroPacienteColorSelf").style.backgroundColor = "#28a74580";

                }
                if (responseSelf.codStatusCadastroPaciente == 3) {
                    nomeStatus = "BLOQUEADO";
                    document.getElementById("codStatusCadastroPacienteColorSelf").style.backgroundColor = "#ff000091";

                }
                if (responseSelf.codStatusCadastroPaciente == 4) {
                    nomeStatus = "DESBLOQUEADO";
                    document.getElementById("codStatusCadastroPacienteColorSelf").style.backgroundColor = "#28a74580";

                }
                if (responseSelf.codStatusCadastroPaciente == 5) {
                    nomeStatus = "ARQUIVADO";
                    document.getElementById("codStatusCadastroPacienteColorSelf").style.backgroundColor = "#ff000091";

                }

                document.getElementById('codStatusCadastroPacienteInfoSelf').innerHTML =
                    '<span>' + nomeStatus + '</span>';

                if (responseSelf.idade > 0) {

                    document.getElementById('idadeInfoSelf').innerHTML =
                        '<span>' + responseSelf.idade + ' Anos</span>';
                } else {

                    document.getElementById('idadeInfoSelf').innerHTML =
                        '<span>' + responseSelf.idade + ' Ano</span>';
                }
                //SELECTS
                autor = "";
                ultimaAlteracao = "";
                if (responseSelf.autorUltimaAtualizacao !== null) {
                    autor = responseSelf.autorUltimaAtualizacao;
                }
                if (responseSelf.dataUltimaAtualizacao !== null) {
                    ultimaAlteracao = responseSelf.dataUltimaAtualizacao;
                }
                document.getElementById('ultimaAuteracaoSelf').innerHTML =
                    '<span>' + autor + ' em ' + ultimaAlteracao + '.</span>'





            }
        })






        $('#data_outrosContatosSelf').DataTable({
            "bDestroy": true,
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": false,
            "info": false,
            "autoWidth": false,
            "responsive": true,
            "ajax": {
                "url": '<?php echo base_url('verificalogin/getOutrosContatos') ?>',
                "type": "POST",
                "dataType": "json",
                async: "false",
                data: {
                    codPaciente: codPaciente,
                    csrf_sandra: $("#csrf_sandraPrincipal").val(),
                }
            }
        });




    }






    function editPessoaLogada(codPessoa) {



        var statusFotoPerfil = document.getElementById("fotoPerfilFormulario");
        if (statusFotoPerfil) {
            fotoPerfil.onchange = evt => {
                const [file] = fotoPerfil.files
                if (file) {
                    fotoPerfilFormulario.src = URL.createObjectURL(file)
                }
            }
        }






        $.ajax({
            url: '<?php echo base_url('principal/pegaPessoa') ?>',
            type: 'post',
            data: {
                codPessoa: codPessoa,
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            dataType: 'json',
            success: function(response) {
                // reset the form 

                if (response.success === false) {


                    Swal.fire({
                        position: 'bottom-end',
                        icon: 'error',
                        title: response.messages,
                        showConfirmButton: false,
                        timer: 3000
                    })
                    exit();

                } else {

                    $("#edit-form-pessoaLogada")[0].reset();
                    $(".form-control").removeClass('is-invalid').removeClass('is-valid');
                    $('#editPessoaLogada').modal('show');


                    $('.modal-title').text(response.nomeExibicao);


                    var nomeExibicaoSistema = '<?php echo session()->nomeExibicaoSistema; ?>';

                    if (nomeExibicaoSistema == 1) {
                        $("#edit-form-pessoaLogada #nomeExibicao").val(response.nomeExibicao);

                    }


                    if (nomeExibicaoSistema == 2) {
                        document.getElementById('nomeExibicao').readOnly = true;
                        $("#edit-form-pessoaLogada #nomeExibicao").val(response.nomeCompleto);
                        $('#nomeCompleto').change(function() {
                            $("#edit-form-pessoaLogada #nomeExibicao").val($("#edit-form-pessoaLogada #nomeCompleto").val());

                        })
                    }


                    if (nomeExibicaoSistema == 3) {
                        document.getElementById('nomeExibicao').readOnly = true;
                        $("#edit-form-pessoaLogada #nomeExibicao").val(response.nomePrincipal);
                        $('#nomePrincipal').change(function() {
                            $("#edit-form-pessoaLogada #nomeExibicao").val($("#edit-form-pessoaLogada #nomePrincipal").val());

                        })
                    }


                    if (nomeExibicaoSistema == 4) {
                        $("#edit-form-pessoaLogada #nomeExibicao").val(response.nomeExibicao);
                        document.getElementById('nomeExibicao').readOnly = true;
                        var siglaCargo = "";
                        $('#codCargo').change(function() {

                            $.ajax({
                                url: '<?php echo base_url('cargos/getOne') ?>',
                                type: 'post',
                                data: {
                                    codCargo: this.value
                                },
                                dataType: 'json',
                                success: function(cargo) {
                                    $("#edit-form-pessoaLogada #nomeExibicao").val($("#edit-form-pessoaLogada #nomePrincipal").val() + ' - ' + cargo.siglaCargo);
                                }
                            })
                        })

                        $('#nomePrincipal').change(function() {
                            $.ajax({
                                url: '<?php echo base_url('cargos/getOne') ?>',
                                type: 'post',
                                data: {
                                    codCargo: $("#edit-form-pessoaLogada #codCargo").val(),
                                    csrf_sandra: $("#csrf_sandraPrincipal").val(),
                                },
                                dataType: 'json',
                                success: function(cargo) {
                                    $("#edit-form-pessoaLogada #nomeExibicao").val($("#edit-form-pessoaLogada #nomePrincipal").val() + ' - ' + cargo.siglaCargo);
                                }
                            })


                            $("#edit-form-pessoaLogada #nomeExibicao").val($("#edit-form-pessoaLogada #nomePrincipal").val() + ' - ' + cargo.siglaCargo);

                        })

                    }

                    if (nomeExibicaoSistema == 5) {
                        $("#edit-form-pessoaLogada #nomeExibicao").val(response.nomeExibicao);
                        document.getElementById('nomeExibicao').readOnly = true;
                        var siglaCargo = "";
                        $('#codCargo').change(function() {

                            $.ajax({
                                url: '<?php echo base_url('cargos/getOne') ?>',
                                type: 'post',
                                data: {
                                    codCargo: this.value,
                                    csrf_sandra: $("#csrf_sandraPrincipal").val(),
                                },
                                dataType: 'json',
                                success: function(cargo) {
                                    $("#edit-form-pessoaLogada #nomeExibicao").val(cargo.siglaCargo + ' ' + $("#edit-form-pessoaLogada #nomePrincipal").val());
                                }
                            })
                        })

                        $('#nomePrincipal').change(function() {
                            $.ajax({
                                url: '<?php echo base_url('cargos/getOne') ?>',
                                type: 'post',
                                data: {
                                    codCargo: $("#edit-form-pessoaLogada #codCargo").val(),
                                    csrf_sandra: $("#csrf_sandraPrincipal").val(),
                                },
                                dataType: 'json',
                                success: function(cargo) {
                                    $("#edit-form-pessoaLogada #nomeExibicao").val(cargo.siglaCargo + ' ' + $("#edit-form-pessoaLogada #nomePrincipal").val());
                                }
                            })


                            $("#edit-form-pessoaLogada #nomeExibicao").val(cargo.siglaCargo + ' ' + $("#edit-form-pessoaLogada #nomePrincipal").val());

                        })

                    }


                    $("#edit-form-pessoaLogada #codPessoa").val(response.codPessoa);
                    $("#edit-form-pessoaLogada #codOrganizacao").val(response.codOrganizacao);
                    $("#edit-form-pessoaLogada #codDepartamento").val(response.codDepartamento).select2();
                    $("#edit-form-pessoaLogada #codFuncao").val(response.codFuncao).select2();
                    $("#edit-form-pessoaLogada #codCargo").val(response.codCargo);

                    if (typeof response.conta !== 'undefined') {
                        $("#edit-form-pessoaLogada #conta").val(response.conta);
                        // $("#edit-form-pessoaLogada #conta").disabled = true;
                        document.getElementById("conta").disabled = true;

                    }
                    $("#edit-form-pessoaLogada #nomeCompleto").val(response.nomeCompleto);
                    $("#edit-form-pessoaLogada #nomePrincipal").val(response.nomePrincipal);
                    $("#edit-form-pessoaLogada #identidade").val(response.identidade);
                    $("#edit-form-pessoaLogada #cpf").val(response.cpf);
                    $("#edit-form-pessoaLogada #codPlano").val(response.codPlano);
                    $("#edit-form-pessoaLogada #emailFuncional").val(response.emailFuncional);
                    $("#edit-form-pessoaLogada #emailPessoal").val(response.emailPessoal);
                    $("#edit-form-pessoaLogada #codEspecialidade").val(response.codEspecialidade).select2();
                    $("#edit-form-pessoaLogada #telefoneTrabalho").val(response.telefoneTrabalho);
                    $("#edit-form-pessoaLogada #celular").val(response.celular);
                    $("#edit-form-pessoaLogada #endereco").val(response.endereco);
                    $("#edit-form-pessoaLogada #dataInicioEmpresa").val(response.dataInicioEmpresa);
                    $("#edit-form-pessoaLogada #dataNascimento").val(response.dataNascimento);
                    $("#edit-form-pessoaLogada #nrEndereco").val(response.nrEndereco);
                    $("#edit-form-pessoaLogada #codMunicipioFederacao").val(response.codMunicipioFederacao).select2();
                    $("#edit-form-pessoaLogada #cep").val(response.cep);
                    $("#edit-form-pessoaLogada #codPerfilPadrao").val(response.codPerfilPadrao);
                    $("#edit-form-pessoaLogada #informacoesComplementares").val(response.informacoesComplementares);
                    $("#edit-form-pessoaLogada #pai").val(response.pai);



                    var statusFotoPerfil = document.getElementById("fotoPerfilFormulario");
                    if (statusFotoPerfil) {
                        if (response.fotoPerfil == null) {
                            document.getElementById("fotoPerfilFormulario").src = "<?php echo "arquivos/imagens/pessoas/no_image.jpg?" ?>" + new Date().getTime();

                        } else {
                            document.getElementById("fotoPerfilFormulario").src = "<?php echo "arquivos/imagens/pessoas/" ?>" + response.fotoPerfil + "?" + new Date().getTime();

                        }
                    }






                    if (response.ativo == 1) {

                        $("#edit-form-pessoaLogada #ativo").attr('checked', true);
                    }
                    if (response.aceiteTermos == 1) {

                        $("#edit-form-pessoaLogada #aceiteTermos").attr('checked', true);
                    }

                    $("#edit-form-pessoaLogada #dataInicioEmpresa").val(response.dataInicioEmpresa);




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



                            var statusFotoPerfil = document.getElementById("fotoPerfilFormulario");
                            if (statusFotoPerfil) {

                                var formData = new FormData();
                                formData.append('file', $('#fotoPerfil')[0].files[0]);
                                formData.append('codPessoa', codPessoa);
                                formData.append('csrf_sandra', $("#csrf_sandraPrincipal").val());
                                $.ajax({
                                    url: 'principal/enviaFoto',
                                    type: 'POST',
                                    data: formData,
                                    processData: false, // tell jQuery not to process the data
                                    contentType: false, // tell jQuery not to set contentType
                                    success: function(data) {
                                        document.getElementById("fotoPerfilFormulario").src = "<?php echo  "arquivos/imagens/pessoas/" ?>" + data.nomeArquivo + "?" + new Date().getTime();
                                        document.getElementById("fotoPerfilBarraSuperior").src = "<?php echo  "arquivos/imagens/pessoas/" ?>" + data.nomeArquivo + "?" + new Date().getTime();


                                    },
                                    error: function(data) {},
                                });

                            }


                            var form = $('#edit-form-pessoaLogada');

                            $(".text-danger").remove();
                            $.ajax({
                                url: '<?php echo base_url('principal/editPessoa') ?>',
                                type: 'post',
                                data: form.serialize(),
                                dataType: 'json',
                                beforeSend: function() {


                                    Swal.fire({
                                        title: 'Estamos processando sua requisição',
                                        html: 'Aguarde....',
                                        timerProgressBar: true,
                                        didOpen: () => {
                                            Swal.showLoading()


                                        }

                                    })
                                },
                                success: function(response) {

                                    if (response.success === true) {
                                        Swal.fire({
                                            position: 'bottom-end',
                                            icon: 'success',
                                            title: response.messages,
                                            showConfirmButton: false,
                                            timer: 3000
                                        })

                                        //EXPORTA PARA LDAP
                                        $.ajax({
                                            url: '<?php echo base_url('principal/exportarPessoa') ?>',
                                            type: 'post',
                                            data: {
                                                codPessoa: codPessoa,
                                                csrf_sandra: $("#csrf_sandraPrincipal").val(),
                                            },
                                            dataType: 'json',


                                            success: function(responseLDAP) {

                                                if (responseLDAP.success === true) {
                                                    Swal.fire({
                                                        position: 'bottom-end',
                                                        icon: 'success',
                                                        title: responseLDAP.messages,
                                                        showConfirmButton: false,
                                                        timer: 3000
                                                    }).then(function() {
                                                        $('#data_tablepessoa').DataTable().ajax.reload(null, false).draw(false);
                                                        //$('#editPessoaLogada').modal('hide');


                                                        //VERIFICA SE NECESSITA TROCAR SENHA
                                                        $.ajax({
                                                            url: '<?php echo base_url('principal/verificaPendenciaSenha') ?>',
                                                            type: 'post',
                                                            dataType: 'json',
                                                            data: {
                                                                csrf_sandra: $("#csrf_sandraPrincipal").val(),
                                                            },
                                                            success: function(responseSenhaPendente) {

                                                                if (responseSenhaPendente.pendencias == true) {
                                                                    $('#editPessoaLogada').modal('hide');

                                                                    Swal.fire({

                                                                        position: 'center',
                                                                        icon: 'warning',
                                                                        title: 'REDEFINIÇÃO DE SENHA',
                                                                        text: 'É necessário cadastrar nova senha.',
                                                                        showConfirmButton: true,
                                                                        timer: 10000
                                                                    }).then(function() {
                                                                        trocasenha(<?php echo session()->codPessoa ?>);

                                                                    })



                                                                } else {

                                                                }

                                                            }
                                                        })


                                                    })

                                                } else {
                                                    Swal.fire({
                                                        position: 'bottom-end',
                                                        icon: 'warning',
                                                        title: 'Falha na sincronização LDAP',
                                                        showConfirmButton: false,
                                                        timer: 3000
                                                    })
                                                }
                                            }




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
                                                showConfirmButton: false,
                                                timer: 1500
                                            })

                                        }
                                    }
                                    $('#edit-form-pessoaLogada-btn').html('Salvar');
                                }
                            });

                            return false;
                        }
                    });
                    $('#edit-form-pessoaLogada').validate();

                }
            }
        });
    }
</script>

<script>
    function verificaConta() {
        $.ajax({
            url: '<?php echo base_url('Pessoas/contas') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            success: function(contas) {

                //var backlist = ["emanuel", "12345678", "87654321", "11111111", "selvabrasil", "brasil1234", "brasil", "selva"];

                var backlist = JSON.parse(contas.jsonContas);



                if (jQuery.inArray($("#contaAdd").val(), backlist) == -1) {
                    $("#contaAdd").removeClass("is-invalid");
                    $("#contaAdd").addClass("is-valid");
                    $("#contaAdd").css("color", "#00A41E");
                    document.getElementById('mensagemConta').style.display = "none";

                } else {


                    document.getElementById('mensagemConta').style.display = "block";
                    $("#contaAdd").removeClass("is-valid");
                    $("#contaAdd").addClass("is-invalid");
                    $("#contaAdd").css("color", "#FF0004");
                }

            }
        })

    }

    function verificaSenhaPaciente(codPaciente) {

        $.ajax({
            url: '<?php echo base_url('pacientes/pegaOrganizacaoPaciente') ?>',
            type: 'post',
            data: {
                codPaciente: codPaciente,
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            dataType: 'json',
            success: function(response) {

                var verificaSenhaNaoSimples = 0;
                var verificanumeros = 0;
                var verificaletras = 0;
                var verificamaiusculo = 0;
                var verificacaracteresEspeciais = 0;
                var verificapwmatch = 0;
                var verificaMinimoCaracteres = 0;


                //VERIFICA CUMPRIMENTO DA SENHA
                if (response.minimoCaracteres > 0) {

                    if ($("#senhaPaciente").val().length >= '<?php echo session()->minimoCaracteres ?>') {
                        $("#verificaMinimoCaracteresPaciente").removeClass("fas fa-times");
                        $("#verificaMinimoCaracteresPaciente").addClass("fas fa-check");
                        $("#verificaMinimoCaracteresPaciente").css("color", "#00A41E");
                        verificaMinimoCaracteres = 1;
                    } else {
                        $("#verificaMinimoCaracteresPaciente").removeClass("fas fa-check");
                        $("#verificaMinimoCaracteresPaciente").addClass("fas fa-times");
                        $("#verificaMinimoCaracteresPaciente").css("color", "#FF0004");
                        verificaSenhaNaoSimples = 0;
                    }
                } else {
                    verificaMinimoCaracteres = 1;
                }




                //SENHA TRIVIAL

                if (response.senhaNaoSimples == 1) {

                    var backlist = ["", "12345678", "87654321", "11111111", "selvabrasil", "brasil1234", "brasil", "selva"];

                    if (jQuery.inArray($("#senhaPaciente").val(), backlist) == -1) {
                        $("#verificaSenhaNaoSimplesPaciente").removeClass("fas fa-times");
                        $("#verificaSenhaNaoSimplesPaciente").addClass("fas fa-check");
                        $("#verificaSenhaNaoSimplesPaciente").css("color", "#00A41E");
                        verificaSenhaNaoSimples = 1;
                    } else {
                        $("#verificaSenhaNaoSimplesPaciente").removeClass("fas fa-check");
                        $("#verificaSenhaNaoSimplesPaciente").addClass("fas fa-times");
                        $("#verificaSenhaNaoSimplesPaciente").css("color", "#FF0004");
                        verificaSenhaNaoSimples = 0;
                    }
                } else {
                    verificaSenhaNaoSimples = 1;
                }

                //SENHA NÚMERO


                if (response.numeros == 1) {
                    var num = new RegExp("[0-9]+");

                    if (num.test($("#senhaPaciente").val())) {
                        $("#verificanumerosPaciente").removeClass("fas fa-times");
                        $("#verificanumerosPaciente").addClass("fas fa-check");
                        $("#verificanumerosPaciente").css("color", "#00A41E");
                        verificanumeros = 1;
                    } else {
                        $("#verificanumerosPaciente").removeClass("fas fa-check");
                        $("#verificanumerosPaciente").addClass("fas fa-times");
                        $("#verificanumerosPaciente").css("color", "#FF0004");
                        verificanumeros = 0;
                    }
                } else {
                    verificanumeros = 1;
                }

                //SENHA LETRAS


                if (response.letras == 1) {
                    var lcase = new RegExp("[A-Za-z]+");

                    if (lcase.test($("#senhaPaciente").val())) {
                        $("#verificaletrasPaciente").removeClass("fas fa-times");
                        $("#verificaletrasPaciente").addClass("fas fa-check");
                        $("#verificaletrasPaciente").css("color", "#00A41E");
                        verificaletras = 1;
                    } else {
                        $("#verificaletrasPaciente").removeClass("fas fa-check");
                        $("#verificaletrasPaciente").addClass("fas fa-times");
                        $("#verificaletrasPaciente").css("color", "#FF0004");
                        verificaletras = 0;
                    }
                } else {
                    verificaletras = 1;
                }

                //SENHA LETRAS MAIUSCULA

                if (response.maiusculo == 1) {
                    var ucase = new RegExp("[A-Z]+");

                    if (ucase.test($("#senhaPaciente").val())) {
                        $("#verificamaiusculoPaciente").removeClass("fas fa-times");
                        $("#verificamaiusculoPaciente").addClass("fas fa-check");
                        $("#verificamaiusculoPaciente").css("color", "#00A41E");
                        verificamaiusculo = 1;
                    } else {
                        $("#verificamaiusculoPaciente").removeClass("fas fa-check");
                        $("#verificamaiusculoPaciente").addClass("fas fa-times");
                        $("#verificamaiusculoPaciente").css("color", "#FF0004");
                        verificamaiusculo = 0;
                    }
                } else {
                    verificamaiusculo = 1;
                }


                //SENHA CARACTERES ESPECIAIS

                if (response.caracteresEspeciais == 1) {
                    var carespeciallist = /^(?=.*[!@#$%^&*.\£()}{~?><>,|=_+¬-])/;

                    if (carespeciallist.test($("#senhaPaciente").val())) {
                        $("#verificacaracteresEspeciaisPaciente").removeClass("fas fa-times");
                        $("#verificacaracteresEspeciaisPaciente").addClass("fas fa-check");
                        $("#verificacaracteresEspeciaisPaciente").css("color", "#00A41E");
                        verificacaracteresEspeciais = 1;
                    } else {
                        $("#verificacaracteresEspeciaisPaciente").removeClass("fas fa-check");
                        $("#verificacaracteresEspeciaisPaciente").addClass("fas fa-times");
                        $("#verificacaracteresEspeciaisPaciente").css("color", "#FF0004");
                        verificacaracteresEspeciais = 0;
                    }
                } else {
                    verificacaracteresEspeciais = 1;
                }


                //CONFIRMAÇÃO DA SENHA

                if ($("#senhaPaciente").val() == $("#confirmacaoPaciente").val()) {
                    $("#pwmatchPaciente").removeClass("fas fa-times");
                    $("#pwmatchPaciente").addClass("fas fa-check");
                    $("#pwmatchPaciente").css("color", "#00A41E");
                    verificapwmatch = 1;
                } else {
                    $("#pwmatchPaciente").removeClass("fas fa-check");
                    $("#pwmatchPaciente").addClass("fas fa-times");
                    $("#pwmatchPaciente").css("color", "#FF0004");
                    verificapwmatch = 0;
                }


                //ATIVAR BOTÃO SALVAR



                if (verificaMinimoCaracteres == 1 && verificaSenhaNaoSimples == 1 && verificanumeros == 1 && verificaletras == 1 && verificamaiusculo == 1 && verificacaracteresEspeciais == 1 && verificapwmatch == 1) {
                    $('#SalvarTrocaSenhaPaciente').prop('disabled', false);
                }
            }
        })

    }


    function verificaSenha(codPessoa) {

        $.ajax({
            url: '<?php echo base_url('pessoas/pegaOrganizacaoPessoa') ?>',
            type: 'post',
            data: {
                codPessoa: codPessoa,
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            dataType: 'json',
            success: function(response) {

                var verificaSenhaNaoSimples = 0;
                var verificanumeros = 0;
                var verificaletras = 0;
                var verificamaiusculo = 0;
                var verificacaracteresEspeciais = 0;
                var verificapwmatch = 0;
                var verificaMinimoCaracteres = 0;



                //VERIFICA CUMPRIMENTO DA SENHA
                if (response.minimoCaracteres > 0) {

                    if ($("#senha").val().length >= '<?php echo session()->minimoCaracteres ?>') {
                        $("#verificaMinimoCaracteres").removeClass("fas fa-times");
                        $("#verificaMinimoCaracteres").addClass("fas fa-check");
                        $("#verificaMinimoCaracteres").css("color", "#00A41E");
                        verificaMinimoCaracteres = 1;
                    } else {
                        $("#verificaMinimoCaracteres").removeClass("fas fa-check");
                        $("#verificaMinimoCaracteres").addClass("fas fa-times");
                        $("#verificaMinimoCaracteres").css("color", "#FF0004");
                        verificaSenhaNaoSimples = 0;
                    }
                } else {
                    verificaMinimoCaracteres = 1;
                }




                //SENHA TRIVIAL

                if (response.senhaNaoSimples == 1) {

                    var backlist = ["", "12345678", "87654321", "11111111", "selvabrasil", "brasil1234", "brasil", "selva"];

                    if (jQuery.inArray($("#senha").val(), backlist) == -1) {
                        $("#verificaSenhaNaoSimples").removeClass("fas fa-times");
                        $("#verificaSenhaNaoSimples").addClass("fas fa-check");
                        $("#verificaSenhaNaoSimples").css("color", "#00A41E");
                        verificaSenhaNaoSimples = 1;
                    } else {
                        $("#verificaSenhaNaoSimples").removeClass("fas fa-check");
                        $("#verificaSenhaNaoSimples").addClass("fas fa-times");
                        $("#verificaSenhaNaoSimples").css("color", "#FF0004");
                        verificaSenhaNaoSimples = 0;
                    }
                } else {
                    verificaSenhaNaoSimples = 1;
                }

                //SENHA NÚMERO


                if (response.numeros == 1) {
                    var num = new RegExp("[0-9]+");

                    if (num.test($("#senha").val())) {
                        $("#verificanumeros").removeClass("fas fa-times");
                        $("#verificanumeros").addClass("fas fa-check");
                        $("#verificanumeros").css("color", "#00A41E");
                        verificanumeros = 1;
                    } else {
                        $("#verificanumeros").removeClass("fas fa-check");
                        $("#verificanumeros").addClass("fas fa-times");
                        $("#verificanumeros").css("color", "#FF0004");
                        verificanumeros = 0;
                    }
                } else {
                    verificanumeros = 1;
                }

                //SENHA LETRAS


                if (response.letras == 1) {
                    var lcase = new RegExp("[A-Za-z]+");

                    if (lcase.test($("#senha").val())) {
                        $("#verificaletras").removeClass("fas fa-times");
                        $("#verificaletras").addClass("fas fa-check");
                        $("#verificaletras").css("color", "#00A41E");
                        verificaletras = 1;
                    } else {
                        $("#verificaletras").removeClass("fas fa-check");
                        $("#verificaletras").addClass("fas fa-times");
                        $("#verificaletras").css("color", "#FF0004");
                        verificaletras = 0;
                    }
                } else {
                    verificaletras = 1;
                }

                //SENHA LETRAS MAIUSCULA

                if (response.maiusculo == 1) {
                    var ucase = new RegExp("[A-Z]+");

                    if (ucase.test($("#senha").val())) {
                        $("#verificamaiusculo").removeClass("fas fa-times");
                        $("#verificamaiusculo").addClass("fas fa-check");
                        $("#verificamaiusculo").css("color", "#00A41E");
                        verificamaiusculo = 1;
                    } else {
                        $("#verificamaiusculo").removeClass("fas fa-check");
                        $("#verificamaiusculo").addClass("fas fa-times");
                        $("#verificamaiusculo").css("color", "#FF0004");
                        verificamaiusculo = 0;
                    }
                } else {
                    verificamaiusculo = 1;
                }


                //SENHA CARACTERES ESPECIAIS

                if (response.caracteresEspeciais == 1) {
                    var carespeciallist = /^(?=.*[!@#$%^&*.\£()}{~?><>,|=_+¬-])/;

                    if (carespeciallist.test($("#senha").val())) {
                        $("#verificacaracteresEspeciais").removeClass("fas fa-times");
                        $("#verificacaracteresEspeciais").addClass("fas fa-check");
                        $("#verificacaracteresEspeciais").css("color", "#00A41E");
                        verificacaracteresEspeciais = 1;
                    } else {
                        $("#verificacaracteresEspeciais").removeClass("fas fa-check");
                        $("#verificacaracteresEspeciais").addClass("fas fa-times");
                        $("#verificacaracteresEspeciais").css("color", "#FF0004");
                        verificacaracteresEspeciais = 0;
                    }
                } else {
                    verificacaracteresEspeciais = 1;
                }


                //CONFIRMAÇÃO DA SENHA

                if ($("#senha").val() == $("#confirmacao").val()) {
                    $("#pwmatch").removeClass("fas fa-times");
                    $("#pwmatch").addClass("fas fa-check");
                    $("#pwmatch").css("color", "#00A41E");
                    verificapwmatch = 1;
                } else {
                    $("#pwmatch").removeClass("fas fa-check");
                    $("#pwmatch").addClass("fas fa-times");
                    $("#pwmatch").css("color", "#FF0004");
                    verificapwmatch = 0;
                }


                //ATIVAR BOTÃO SALVAR



                if (verificaMinimoCaracteres == 1 && verificaSenhaNaoSimples == 1 && verificanumeros == 1 && verificaletras == 1 && verificamaiusculo == 1 && verificacaracteresEspeciais == 1 && verificapwmatch == 1) {
                    $('#SalvarTrocaSenha').prop('disabled', false);
                }
            }
        })

    }

    function verificaSenhaPessoaLogada() {


        $.ajax({
            url: '<?php echo base_url('pessoas/pegaOrganizacaoPessoa') ?>',
            type: 'post',
            data: {
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            dataType: 'json',
            success: function(response) {

                var verificaSenhaNaoSimples = 0;
                var verificanumeros = 0;
                var verificaletras = 0;
                var verificamaiusculo = 0;
                var verificacaracteresEspeciais = 0;
                var verificapwmatch = 0;
                var verificaMinimoCaracteres = 0;



                //VERIFICA CUMPRIMENTO DA SENHA
                if (response.minimoCaracteres > 0) {

                    if ($("#senhaPessoaLogada").val().length >= '<?php echo session()->minimoCaracteres ?>') {
                        $("#verificaMinimoCaracteresPessoaLogada").removeClass("fas fa-times");
                        $("#verificaMinimoCaracteresPessoaLogada").addClass("fas fa-check");
                        $("#verificaMinimoCaracteresPessoaLogada").css("color", "#00A41E");
                        verificaMinimoCaracteres = 1;
                    } else {
                        $("#verificaMinimoCaracteresPessoaLogada").removeClass("fas fa-check");
                        $("#verificaMinimoCaracteresPessoaLogada").addClass("fas fa-times");
                        $("#verificaMinimoCaracteresPessoaLogada").css("color", "#FF0004");
                        verificaSenhaNaoSimples = 0;
                    }
                } else {
                    verificaMinimoCaracteres = 1;
                }




                //SENHA TRIVIAL

                if (response.senhaNaoSimples == 1) {

                    var backlist = ["", "12345678", "87654321", "11111111", "selvabrasil", "brasil1234", "brasil", "selva"];

                    if (jQuery.inArray($("#senhaPessoaLogada").val(), backlist) == -1) {
                        $("#verificaSenhaNaoSimplesPessoaLogada").removeClass("fas fa-times");
                        $("#verificaSenhaNaoSimplesPessoaLogada").addClass("fas fa-check");
                        $("#verificaSenhaNaoSimplesPessoaLogada").css("color", "#00A41E");
                        verificaSenhaNaoSimples = 1;
                    } else {
                        $("#verificaSenhaNaoSimplesPessoaLogada").removeClass("fas fa-check");
                        $("#verificaSenhaNaoSimplesPessoaLogada").addClass("fas fa-times");
                        $("#verificaSenhaNaoSimplesPessoaLogada").css("color", "#FF0004");
                        verificaSenhaNaoSimples = 0;
                    }
                } else {
                    verificaSenhaNaoSimples = 1;
                }

                //SENHA NÚMERO


                if (response.numeros == 1) {
                    var num = new RegExp("[0-9]+");

                    if (num.test($("#senhaPessoaLogada").val())) {
                        $("#verificanumerosPessoaLogada").removeClass("fas fa-times");
                        $("#verificanumerosPessoaLogada").addClass("fas fa-check");
                        $("#verificanumerosPessoaLogada").css("color", "#00A41E");
                        verificanumeros = 1;
                    } else {
                        $("#verificanumerosPessoaLogada").removeClass("fas fa-check");
                        $("#verificanumerosPessoaLogada").addClass("fas fa-times");
                        $("#verificanumerosPessoaLogada").css("color", "#FF0004");
                        verificanumeros = 0;
                    }
                } else {
                    verificanumeros = 1;
                }

                //SENHA LETRAS


                if (response.letras == 1) {
                    var lcase = new RegExp("[A-Za-z]+");

                    if (lcase.test($("#senhaPessoaLogada").val())) {
                        $("#verificaletrasPessoaLogada").removeClass("fas fa-times");
                        $("#verificaletrasPessoaLogada").addClass("fas fa-check");
                        $("#verificaletrasPessoaLogada").css("color", "#00A41E");
                        verificaletras = 1;
                    } else {
                        $("#verificaletrasPessoaLogada").removeClass("fas fa-check");
                        $("#verificaletrasPessoaLogada").addClass("fas fa-times");
                        $("#verificaletrasPessoaLogada").css("color", "#FF0004");
                        verificaletras = 0;
                    }
                } else {
                    verificaletras = 1;
                }

                //SENHA LETRAS MAIUSCULA

                if (response.maiusculo == 1) {
                    var ucase = new RegExp("[A-Z]+");

                    if (ucase.test($("#senhaPessoaLogada").val())) {
                        $("#verificamaiusculoPessoaLogada").removeClass("fas fa-times");
                        $("#verificamaiusculoPessoaLogada").addClass("fas fa-check");
                        $("#verificamaiusculoPessoaLogada").css("color", "#00A41E");
                        verificamaiusculo = 1;
                    } else {
                        $("#verificamaiusculoPessoaLogada").removeClass("fas fa-check");
                        $("#verificamaiusculoPessoaLogada").addClass("fas fa-times");
                        $("#verificamaiusculoPessoaLogada").css("color", "#FF0004");
                        verificamaiusculo = 0;
                    }
                } else {
                    verificamaiusculo = 1;
                }


                //SENHA CARACTERES ESPECIAIS

                if (response.caracteresEspeciais == 1) {
                    var carespeciallist = /^(?=.*[!@#$%^&*.\£()}{~?><>,|=_+¬-])/;

                    if (carespeciallist.test($("#senhaPessoaLogada").val())) {
                        $("#verificacaracteresEspeciaisPessoaLogada").removeClass("fas fa-times");
                        $("#verificacaracteresEspeciaisPessoaLogada").addClass("fas fa-check");
                        $("#verificacaracteresEspeciaisPessoaLogada").css("color", "#00A41E");
                        verificacaracteresEspeciais = 1;
                    } else {
                        $("#verificacaracteresEspeciaisPessoaLogada").removeClass("fas fa-check");
                        $("#verificacaracteresEspeciaisPessoaLogada").addClass("fas fa-times");
                        $("#verificacaracteresEspeciaisPessoaLogada").css("color", "#FF0004");
                        verificacaracteresEspeciais = 0;
                    }
                } else {
                    verificacaracteresEspeciais = 1;
                }


                //CONFIRMAÇÃO DA SENHA

                if ($("#senhaPessoaLogada").val() == $("#confirmacaoPessoaLogada").val()) {
                    $("#pwmatchPessoaLogada").removeClass("fas fa-times");
                    $("#pwmatchPessoaLogada").addClass("fas fa-check");
                    $("#pwmatchPessoaLogada").css("color", "#00A41E");
                    verificapwmatch = 1;
                } else {
                    $("#pwmatchPessoaLogada").removeClass("fas fa-check");
                    $("#pwmatchPessoaLogada").addClass("fas fa-times");
                    $("#pwmatchPessoaLogada").css("color", "#FF0004");
                    verificapwmatch = 0;
                }


                //ATIVAR BOTÃO SALVAR



                if (verificaMinimoCaracteres == 1 && verificaSenhaNaoSimples == 1 && verificanumeros == 1 && verificaletras == 1 && verificamaiusculo == 1 && verificacaracteresEspeciais == 1 && verificapwmatch == 1) {
                    $('#SalvarTrocaSenhaPessoaLogada').prop('disabled', false);
                }
            }
        })

    }
</script>



<script>
    function chamaPesquisa() {

        $('#pesquisaQualidadeModal').modal('show');

    }

    function avisoPesquisa(modulo, tipo) {

        //tipo 1 = popup
        //tipo 2 = botao cabecalho Horizontal
        //tipo 3 = botao no modal
        //tipo 4 = botao no modal agendamento Exames
        //tipo 5 = botao no modal agendamento Consultas
        //tipo 4 = botao no modal Prontuários apartir da Emergência



        moduloTmp = modulo;

        $.ajax({
            url: '<?php echo base_url('questionarios/verificaExistencia') ?>',
            type: 'post',
            data: {
                modulo: modulo,
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            dataType: 'json',
            success: function(response) {

                if (response.success === true) {
                    document.getElementById("dadosQuestionario").innerHTML = response.html;





                    if (tipo == 1) {
                        $('#pesquisaQualidadeModal').modal('show');
                    }

                    if (tipo == 2) {
                        document.getElementById("botaoPesquisa").innerHTML = response.botao;
                    }

                    if (tipo == 3) {
                        document.getElementById("botaoPesquisaInternadosModal").innerHTML = response.botao;
                    }

                    if (tipo == 4) {
                        document.getElementById("botaoPesquisaAgendamentoAgendamentosExamesModal").innerHTML = response.botao;
                    }

                    if (tipo == 5) {
                        document.getElementById("botaoPesquisaAgendamentoConsultasModal").innerHTML = response.botao;
                    }

                    if (tipo == 6) {
                        document.getElementById("botaoPesquisaProntuarioEletronicoModal").innerHTML = response.botao;
                    }

                    if (tipo == 7) {
                        document.getElementById("botaoPesquisaProntuarioEletronicoPerfilMedicoModal").innerHTML = response.botao;
                    }

                }
            }
        })



    }





    function testarPesquisa(codQuestionario) {

        moduloTmp = 'Teste';
        $.ajax({
            url: '<?php echo base_url('questionarios/verificaExistencia') ?>',
            type: 'post',
            data: {
                modulo: 'Teste',
                codQuestionario: codQuestionario,
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            dataType: 'json',
            success: function(response) {

                if (response.success === true) {
                    document.getElementById("dadosQuestionario").innerHTML = response.html;

                    $('#pesquisaQualidadeModal').modal('show');


                }
            }
        })

    }

    function chamaModalQualidade(codQuestionario) {

        $('#pesquisaQualidadeModal').modal('show');
    }


    function iniciarpesquisa(codQuestionario) {


        $.ajax({
            url: '<?php echo base_url('questionarios/termoAceite') ?>',
            type: 'post',
            data: {
                codQuestionario: codQuestionario,
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            dataType: 'json',
            success: function(termo) {

                if (termo.success === true) {
                    document.getElementById("dadosTermoPesquisa").innerHTML = termo.html;

                    $('#codQuestionario').val(termo.codQuestionario);
                    $('#termoPesquisaModal').modal('show');
                    $('#pesquisaQualidadeModal').modal('hide');


                }
            }
        })


    }

    function sairPesquisa() {

        $('#pesquisaQualidadeModal').modal('hide');

        if (document.querySelector('#naoPerguntarNovamentePesquisa').checked === true) {

            $.ajax({
                url: '<?php echo base_url('questionarios/naoConcordou') ?>',
                type: 'post',
                dataType: 'json',
                data: {
                    modulo: moduloTmp,
                    codQuestionario: document.getElementById("codQuestionario").value,
                    csrf_sandra: $("#csrf_sandraPrincipal").val(),
                },
                success: function(gravarConcorde) {

                    if (gravarConcorde.success == true) {


                    }
                }
            })
        }

    }

    function aceitar() {

        if ($("#aceitoOsTermos").is(':checked')) {

            $('#termoPesquisaModal').modal('hide');
            $('#perguntasModal').modal('show');

            $.ajax({
                url: '<?php echo base_url('questionarios/perguntasQuestionario') ?>',
                type: 'post',
                data: {
                    modulo: moduloTmp,
                    codQuestionario: $('#codQuestionario').val(),
                    csrf_sandra: $("#csrf_sandraPrincipal").val(),
                },
                dataType: 'json',
                success: function(perguntas) {

                    if (perguntas.success === true) {
                        document.getElementById("dadosPerguntasPesquisa").innerHTML = perguntas.html;

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

                                var form = $('#respostaQuestionarioForm');
                                // remove the text-danger
                                $(".text-danger").remove();

                                $.ajax({
                                    url: '<?php echo base_url('questionarios/inserirResposta') ?>',
                                    type: 'post',
                                    data: form.serialize(), // /converting the form data into array and sending it to server
                                    dataType: 'json',
                                    beforeSend: function() {},
                                    success: function(response) {

                                        if (response.success === true) {
                                            //  $('#agendamentosAddModal').modal('hide');

                                            $('#perguntasModal').modal('hide');

                                            document.getElementById("botaoPesquisa").innerHTML = "";

                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Obrigado, resposta registrada!',
                                                html: 'Sua participação será utilizada para aperfeiçoar o sistema.',
                                                showConfirmButton: true,
                                                confirmButtonColor: 'green',
                                                confirmButtonText: 'Ok',

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
                                    }
                                }).always(
                                    Swal.fire({
                                        title: 'Estamos enviando sua participação',
                                        html: 'Aguarde....',
                                        timerProgressBar: true,
                                        didOpen: () => {
                                            Swal.showLoading()


                                        }

                                    }))

                                return false;
                            }
                        });
                        $('#respostaQuestionarioForm').validate();


                    }
                }
            })



        } else {
            Swal.fire({
                icon: 'info',
                title: 'Para continuar é necessário aceitar os termos',
                showConfirmButton: true,
                confirmButtonText: 'Ok',
            })


        }
    }



    function trocasenhaPessoaLogada() {


        $('#modalTrocaSenhaPessoaLogada').modal('show');
        $("#formTrocaSenhaPessoaLogada")[0].reset();
        $("#senhaPessoaLogada").attr("onkeyup", "verificaSenhaPessoaLogada()");
        $("#confirmacaoPessoaLogada").attr("onkeyup", "verificaSenhaPessoaLogada()");

        $("#verificaMinimoCaracteresPessoaLogada").removeClass("fas fa-check");
        $("#verificaMinimoCaracteresPessoaLogada").addClass("fas fa-times");
        $("#verificaMinimoCaracteresPessoaLogada").css("color", "#FF0004");


        $("#verificaSenhaNaoSimplesPessoaLogada").removeClass("fas fa-check");
        $("#verificaSenhaNaoSimplesPessoaLogada").addClass("fas fa-times");
        $("#verificaSenhaNaoSimplesPessoaLogada").css("color", "#FF0004");

        $("#verificanumerosPessoaLogada").removeClass("fas fa-check");
        $("#verificanumerosPessoaLogada").addClass("fas fa-times");
        $("#verificanumerosPessoaLogada").css("color", "#FF0004");


        $("#verificaletrasPessoaLogada").removeClass("fas fa-check");
        $("#verificaletrasPessoaLogada").addClass("fas fa-times");
        $("#verificaletrasPessoaLogada").css("color", "#FF0004");



        $("#verificamaiusculoPessoaLogada").removeClass("fas fa-check");
        $("#verificamaiusculoPessoaLogada").addClass("fas fa-times");
        $("#verificamaiusculoPessoaLogada").css("color", "#FF0004");


        $("#verificacaracteresEspeciaisPessoaLogada").removeClass("fas fa-check");
        $("#verificacaracteresEspeciaisPessoaLogada").addClass("fas fa-times");
        $("#verificacaracteresEspeciaisPessoaLogada").css("color", "#FF0004");


        $("#pwmatchPessoaLogada").removeClass("fas fa-check");
        $("#pwmatchPessoaLogada").addClass("fas fa-times");
        $("#pwmatchPessoaLogada").css("color", "#FF0004");

    }

    function trocasenha(codPessoa) {

        $.ajax({
            url: '<?php echo base_url('pessoas/verificaPendenciaCadastro') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            success: function(responseverificaPendenciaCadastro) {

                if (responseverificaPendenciaCadastro.pendencias == true) {

                    Swal.fire({

                        position: 'center',
                        icon: 'warning',
                        title: 'Somente é possível alterar a senha após atualizar seu cadastro',
                        text: 'Você possue ' + responseverificaPendenciaCadastro.quantidade + ' pendencia(s).',
                        showConfirmButton: true,
                        timer: 10000
                    }).then(function() {
                        editPessoaLogada(<?php echo session()->codPessoa ?>);

                    })

                } else {

                    $("#formTrocaSenha")[0].reset();
                    $('#modal-senha').modal('show');
                    $("#formTrocaSenha #codPessoaTrocaSenha").val(codPessoa);
                    $("#senha").attr("onkeyup", "verificaSenha(" + codPessoa + ")");
                    $("#confirmacao").attr("onkeyup", "verificaSenha(" + codPessoa + ")");

                    $("#verificaMinimoCaracteres").removeClass("fas fa-check");
                    $("#verificaMinimoCaracteres").addClass("fas fa-times");
                    $("#verificaMinimoCaracteres").css("color", "#FF0004");


                    $("#verificaSenhaNaoSimples").removeClass("fas fa-check");
                    $("#verificaSenhaNaoSimples").addClass("fas fa-times");
                    $("#verificaSenhaNaoSimples").css("color", "#FF0004");

                    $("#verificanumeros").removeClass("fas fa-check");
                    $("#verificanumeros").addClass("fas fa-times");
                    $("#verificanumeros").css("color", "#FF0004");


                    $("#verificaletras").removeClass("fas fa-check");
                    $("#verificaletras").addClass("fas fa-times");
                    $("#verificaletras").css("color", "#FF0004");



                    $("#verificamaiusculo").removeClass("fas fa-check");
                    $("#verificamaiusculo").addClass("fas fa-times");
                    $("#verificamaiusculo").css("color", "#FF0004");


                    $("#verificacaracteresEspeciais").removeClass("fas fa-check");
                    $("#verificacaracteresEspeciais").addClass("fas fa-times");
                    $("#verificacaracteresEspeciais").css("color", "#FF0004");


                    $("#pwmatch").removeClass("fas fa-check");
                    $("#pwmatch").addClass("fas fa-times");
                    $("#pwmatch").css("color", "#FF0004");

                }
            },

        });








    }




    function trocasenhaPaciente(codPaciente) {


        $("#formTrocaSenhaPaciente")[0].reset();
        $('#modal-senhaPaciente').modal('show');
        $("#formTrocaSenhaPaciente #codPacienteTrocaSenha").val(codPaciente);
        $("#senhaPaciente").attr("onkeyup", "verificaSenhaPaciente(" + codPaciente + ")");
        $("#confirmacaoPaciente").attr("onkeyup", "verificaSenhaPaciente(" + codPaciente + ")");

        $("#verificaMinimoCaracteresPaciente").removeClass("fas fa-check");
        $("#verificaMinimoCaracteresPaciente").addClass("fas fa-times");
        $("#verificaMinimoCaracteresPaciente").css("color", "#FF0004");


        $("#verificaSenhaNaoSimplesPaciente").removeClass("fas fa-check");
        $("#verificaSenhaNaoSimplesPaciente").addClass("fas fa-times");
        $("#verificaSenhaNaoSimplesPaciente").css("color", "#FF0004");

        $("#verificanumerosPaciente").removeClass("fas fa-check");
        $("#verificanumerosPaciente").addClass("fas fa-times");
        $("#verificanumerosPaciente").css("color", "#FF0004");


        $("#verificaletrasPaciente").removeClass("fas fa-check");
        $("#verificaletrasPaciente").addClass("fas fa-times");
        $("#verificaletrasPaciente").css("color", "#FF0004");



        $("#verificamaiusculoPaciente").removeClass("fas fa-check");
        $("#verificamaiusculoPaciente").addClass("fas fa-times");
        $("#verificamaiusculoPaciente").css("color", "#FF0004");


        $("#verificacaracteresEspeciaisPaciente").removeClass("fas fa-check");
        $("#verificacaracteresEspeciaisPaciente").addClass("fas fa-times");
        $("#verificacaracteresEspeciaisPaciente").css("color", "#FF0004");


        $("#pwmatchPaciente").removeClass("fas fa-check");
        $("#pwmatchPaciente").addClass("fas fa-times");
        $("#pwmatchPaciente").css("color", "#FF0004");







    }


    $(document).ready(function() {
        $("#formTrocaSenha").submit(function() {

            $.ajax({
                url: "pessoas/trocaSenha",
                data: $("#formTrocaSenha").serialize(),
                type: "POST",
                dataType: 'json',
                success: function(response) {
                    if (response.success === true) {
                        Swal.fire({
                            position: 'bottom-end',
                            icon: 'success',
                            html: response.messages,
                            showConfirmButton: true,
                            confirmButtonColor: 'green',
                            confirmButtonText: 'Ok',

                        })
                    }


                },
                error: function(e) {
                    Swal.fire({
                        position: 'bottom-end',
                        icon: 'error',
                        title: response.messages,
                        showConfirmButton: false,
                        timer: 1500
                    })

                }
            }).always(
                Swal.fire({
                    title: 'Estamos processando a troca da senha',
                    html: 'Aguarde....',
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()


                    }

                }));
            $('#modal-senha').modal('hide');

            $("#verificaMinimoCaracteres").removeClass("fas fa-check");
            $("#verificaMinimoCaracteres").addClass("fas fa-times");
            $("#verificaMinimoCaracteres").css("color", "#FF0004");


            $("#verificaSenhaNaoSimples").removeClass("fas fa-check");
            $("#verificaSenhaNaoSimples").addClass("fas fa-times");
            $("#verificaSenhaNaoSimples").css("color", "#FF0004");

            $("#verificanumeros").removeClass("fas fa-check");
            $("#verificanumeros").addClass("fas fa-times");
            $("#verificanumeros").css("color", "#FF0004");


            $("#verificaletras").removeClass("fas fa-check");
            $("#verificaletras").addClass("fas fa-times");
            $("#verificaletras").css("color", "#FF0004");



            $("#verificamaiusculo").removeClass("fas fa-check");
            $("#verificamaiusculo").addClass("fas fa-times");
            $("#verificamaiusculo").css("color", "#FF0004");


            $("#verificacaracteresEspeciais").removeClass("fas fa-check");
            $("#verificacaracteresEspeciais").addClass("fas fa-times");
            $("#verificacaracteresEspeciais").css("color", "#FF0004");


            $("#pwmatch").removeClass("fas fa-check");
            $("#pwmatch").addClass("fas fa-times");
            $("#pwmatch").css("color", "#FF0004");

            return false;
        });
    });

    $(document).ready(function() {
        $("#formTrocaSenhaPessoaLogada").submit(function() {

            $.ajax({
                url: "pessoas/trocaSenhaPessoaLogada",
                data: $("#formTrocaSenhaPessoaLogada").serialize(),
                type: "POST",
                dataType: 'json',
                success: function(response) {

                    if (response.success === false) {
                        Swal.fire({
                            position: 'bottom-end',
                            icon: 'error',
                            html: response.messages,
                            showConfirmButton: false,
                            timer: 3000,

                        })
                        exit();
                    }


                    if (response.success === true) {
                        Swal.fire({
                            position: 'bottom-end',
                            icon: 'success',
                            html: response.messages,
                            showConfirmButton: true,
                            confirmButtonColor: 'green',
                            confirmButtonText: 'Ok',

                        })
                    }


                },
                error: function(e) {
                    Swal.fire({
                        position: 'bottom-end',
                        icon: 'error',
                        title: response.messages,
                        showConfirmButton: false,
                        timer: 1500
                    })

                }

            }).always(
                Swal.fire({
                    title: 'Estamos processando a troca da senha',
                    html: 'Aguarde....',
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()


                    }

                }));

            $('#modalTrocaSenhaPessoaLogada').modal('hide');

            $("#verificaMinimoCaracteresPessoaLogada").removeClass("fas fa-check");
            $("#verificaMinimoCaracteresPessoaLogada").addClass("fas fa-times");
            $("#verificaMinimoCaracteresPessoaLogada").css("color", "#FF0004");


            $("#verificaSenhaNaoSimplesPessoaLogada").removeClass("fas fa-check");
            $("#verificaSenhaNaoSimplesPessoaLogada").addClass("fas fa-times");
            $("#verificaSenhaNaoSimplesPessoaLogada").css("color", "#FF0004");

            $("#verificanumerosPessoaLogada").removeClass("fas fa-check");
            $("#verificanumerosPessoaLogada").addClass("fas fa-times");
            $("#verificanumerosPessoaLogada").css("color", "#FF0004");


            $("#verificaletrasPessoaLogada").removeClass("fas fa-check");
            $("#verificaletrasPessoaLogada").addClass("fas fa-times");
            $("#verificaletrasPessoaLogada").css("color", "#FF0004");



            $("#verificamaiusculoPessoaLogada").removeClass("fas fa-check");
            $("#verificamaiusculoPessoaLogada").addClass("fas fa-times");
            $("#verificamaiusculoPessoaLogada").css("color", "#FF0004");


            $("#verificacaracteresEspeciaisPessoaLogada").removeClass("fas fa-check");
            $("#verificacaracteresEspeciaisPessoaLogada").addClass("fas fa-times");
            $("#verificacaracteresEspeciaisPessoaLogada").css("color", "#FF0004");


            $("#pwmatchPessoaLogada").removeClass("fas fa-check");
            $("#pwmatchPessoaLogada").addClass("fas fa-times");
            $("#pwmatchPessoaLogada").css("color", "#FF0004");

            return false;
        });
    });


    $(document).ready(function() {
        $("#formTrocaSenhaPaciente").submit(function() {

            $.ajax({
                url: "pacientes/trocaSenha",
                data: $("#formTrocaSenhaPaciente").serialize(),
                type: "POST",
                dataType: 'json',
                success: function(responseTrocaSenhaPaciente) {
                    if (responseTrocaSenhaPaciente.success === true) {
                        Swal.fire({
                            position: 'bottom-end',
                            icon: 'success',
                            html: responseTrocaSenhaPaciente.messages,
                            showConfirmButton: true,
                            confirmButtonColor: 'green',
                            confirmButtonText: 'Ok',

                        })
                    }


                },
                error: function(e) {
                    Swal.fire({
                        position: 'bottom-end',
                        icon: 'error',
                        title: responseTrocaSenhaPaciente.messages,
                        showConfirmButton: false,
                        timer: 1500
                    })

                }
            }).always(
                Swal.fire({
                    title: 'Estamos processando a troca da senha',
                    html: 'Aguarde....',
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()


                    }

                }));
            $('#modal-senhaPaciente').modal('hide');


            $("#verificaMinimoCaracteresPaciente").removeClass("fas fa-check");
            $("#verificaMinimoCaracteresPaciente").addClass("fas fa-times");
            $("#verificaMinimoCaracteresPaciente").css("color", "#FF0004");


            $("#verificaSenhaNaoSimplesPaciente").removeClass("fas fa-check");
            $("#verificaSenhaNaoSimplesPaciente").addClass("fas fa-times");
            $("#verificaSenhaNaoSimplesPaciente").css("color", "#FF0004");

            $("#verificanumerosPaciente").removeClass("fas fa-check");
            $("#verificanumerosPaciente").addClass("fas fa-times");
            $("#verificanumerosPaciente").css("color", "#FF0004");


            $("#verificaletrasPaciente").removeClass("fas fa-check");
            $("#verificaletrasPaciente").addClass("fas fa-times");
            $("#verificaletrasPaciente").css("color", "#FF0004");



            $("#verificamaiusculoPaciente").removeClass("fas fa-check");
            $("#verificamaiusculoPaciente").addClass("fas fa-times");
            $("#verificamaiusculoPaciente").css("color", "#FF0004");


            $("#verificacaracteresEspeciaisPaciente").removeClass("fas fa-check");
            $("#verificacaracteresEspeciaisPaciente").addClass("fas fa-times");
            $("#verificacaracteresEspeciaisPaciente").css("color", "#FF0004");


            $("#pwmatchPaciente").removeClass("fas fa-check");
            $("#pwmatchPaciente").addClass("fas fa-times");
            $("#pwmatchPaciente").css("color", "#FF0004");

            return false;
        });
    });
</script>


<script>
    /*
    setInterval(function() {
        if ($("input[id*='csrf_sandra']").length > 0) {


            if ($("input[id*='csrf_sandra']").val() !== csrfHash) {
                csrfHash = '<?php echo csrf_hash() ?>';
                //alert($("input[id*='csrf_sandra']").val());
            }
        }
        //alert( $("input[id*='csrf_sandra']").val());
        // 
    }, 1000);


*/

    $("input[id*='cpf']").inputmask({
        mask: ['999.999.999-99'],
        keepStatic: true
    });


    $("input[id*='celular']").inputmask({
        mask: ["(99) 99999-9999"],
        keepStatic: true
    });

    $("input[id*='numeroContato']").inputmask({
        mask: ["(99) 99999-9999"],
        keepStatic: true
    });


    $("input[id*='cep']").inputmask({
        mask: ["99999‑999"],
        keepStatic: true
    });

    $("input[id*='altura']").inputmask({
        mask: ['9,99'],
        keepStatic: true
    });
</script>
<script>
    function verificaPermissoes(controller, tipoPermissao) {
        //VERIFICA SE PODE LISTAR
        var status;
        $.ajax({
            url: '<?php echo base_url('Seguranca/verificaSeguranca') ?>',
            type: 'post',
            data: {
                tipoPermissao: tipoPermissao,
                modulo: controller,
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            dataType: 'json',
            success: function(permissaoListar) {


                if (permissaoListar.permissao == 1) {
                    status = true;
                }
                if (permissaoListar.permissao == 0) {
                    status = false;

                }
            }

        })
        return status;
    }


    function boqueiaRetorna() {
        Swal.fire({

            position: 'center',
            icon: 'warning',
            title: 'Você não tem acesso a este recurso',
            showConfirmButton: true,
            timer: 10000
        }).then(function() {
            window.location.href = "javascript:history.back()";
        })
    }


    $(document).ajaxError(function myErrorHandler(event, xhr, ajaxOptions, thrownError) {
        if (xhr.status == 500) {
            Swal.fire({
                position: 'center',
                icon: 'warning',
                title: 'Erro 500  - Falha Interna da aplicação. O sistema será recarregado!',
                showConfirmButton: true,
            }).then(function() {
                location.reload();
            })

            //event.preventDefault(); //SUBJUDCE - PARECE QUE INTERROMPE OS OUTROS AJAX 99% de chance de ser, 
        }
        //Internal Server Error
    });
</script>

</body>

</html>