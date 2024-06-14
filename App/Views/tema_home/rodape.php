
<footer id="footer">

<div class="container py-4">
    <div class="copyright">
        <strong><span>Desenvolvedor Emanuel Peixoto Vicente | <a href="https://www.linkedin.com/in/emanuelpv/">https://www.linkedin.com/in/emanuelpv/</a></span></strong>
    </div>
</div>
</footer>
<!-- End Footer -->

<a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

<!-- Vendor JS Files -->
<script src="<?php echo base_url() ?>/assets/landing/js/jquery.min.js"></script>
<script src="<?php echo base_url() ?>/assets/landing/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url() ?>/assets/landing/js/jquery.easing.min.js"></script>
<script src="<?php echo base_url() ?>/assets/landing/js/isotope.pkgd.min.js"></script>
<script src="<?php echo base_url() ?>/assets/landing/js/venobox.min.js"></script>
<script src="<?php echo base_url() ?>/assets/landing/js/owl.carousel.min.js"></script>
<script src="<?php echo base_url() ?>/assets/landing/js/aos.js"></script>

<!-- Template Main JS File -->
<script src="<?php echo base_url() ?>/assets/landing/js/main.js"></script>


<script src="<?php echo base_url() ?>/assets/landing/js/privacidade.js"></script>


<script src="<?php echo base_url() ?>/assets/adminlte/plugins/select2/js/select2.full.min.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/bs-stepper/js/bs-stepper.min.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/inputmask/jquery.inputmask.js"></script>


<script>
$('#beneficiarioForm').submit(function(event) {
    Swal.fire({
        title: 'Estamos verificando suas credenciais',
        html: 'Aguarde....',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading()
        }

    })
});


$('#colaboradorForm').submit(function(event) {
    Swal.fire({
        title: 'Estamos verificando suas credenciais',
        html: 'Aguarde....',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading()
        }

    })
});



$("input[id*='cpf']").inputmask({
    mask: ['999.999.999-99'],
    keepStatic: true
});

$("input[id*='celular']").inputmask({
    mask: ["(99) 99999-9999"],
    keepStatic: true
});


mensagem = '<?php echo session()->getFlashdata('mensagem')  ?>';

if (mensagem !== null && mensagem !== undefined && mensagem !== "") {
    var Toast = Swal.mixin({
        toast: true,
        position: 'center-end',
        showConfirmButton: false,
        timer: 5000
    });
    Toast.fire({
        icon: 'error',
        title: mensagem,
    })
}
</script>


<script>
function profissionaisSaude() {

    $('#profissionaisSaudeModal').modal('show');

    $.ajax({
        url: '<?php echo base_url('login/profissionaisSaude') ?>',
        type: 'post',
        data: {
            csrf_sandra: $("#csrf_sandraPrincipal").val(),
        },
        dataType: 'json',
        success: function(profissionaisSaude) {


            document.getElementById("listaProfissionaisSaude").innerHTML = profissionaisSaude.html;



        }
    })



}




function verTermo(codTermo) {

    $('#verTermoModal').modal('show');
    $.ajax({
        url: '<?php echo base_url('login/termo') ?>',
        type: 'post',
        data: {
            codTermo: codTermo,
            csrf_sandra: $("#csrf_sandraPrincipal").val(),
        },
        dataType: 'json',
        success: function(response) {



            document.getElementById('verTermoContent').innerHTML = response.termo;



        }
    })
}


function termosDeUso() {
    $('#termosDeUsoModal').modal('show');
}

function recuperaSenhaSaude() {

    Swal.fire({
        position: 'center-end',
        icon: 'info',
        title: "Procure o setor de Tecnologia da Informação",
        showConfirmButton: true,
        confirmButtonText: 'Ok',
    })
    event.preventDefault();
}

function recuperaSenhaAdministrativo() {

    Swal.fire({
        position: 'center-end',
        icon: 'info',
        title: "Procure o setor de Tecnologia da Informação",
        showConfirmButton: true,
        confirmButtonText: 'Ok',
    })
    event.preventDefault();
}


function primeiroAcesso() {


    document.getElementById("primeiroAcessoForm").reset();

    $('#primeiroAcessoModal').modal('show');
}

function recuperaSenhaPaciente() {
    /*
                Swal.fire({
                    icon: 'info',
                    title: 'Caro usuário, informamos que o primeiro acesso deverá ser realizado utilizando o seu CPF como login e número de beneficiário do Paciente(Nº PLANO), com 11 dígitos, ou seja, PREC de 9 digitos + CP de 2 digitos à direta, como a senha inicial.',
                    html: 'Recomendamos a troca da senha ao realizar o primeiro acesso!',
                    showConfirmButton: true,
                    confirmButtonText: 'Ok',
                })
                event.preventDefault();*/

    event.preventDefault();
    document.getElementById("informecpfForm").reset();

    $('#informecpfModal').modal('show');
    $("#informecpfForm #codOrganizacaoRecuperaSenha").val(document.getElementById("codOrganizacao").value);
    $("#informecpfForm #codPerfilRecuperaSenha").val(document.getElementById("perfilLogin").value);

}

function verificarConfirmacoes() {

    var form = $('#confirmacoesForm');
    $('#recuperaSenhaModal').modal('hide');
    $.ajax({
        url: '<?php echo base_url('Verificalogin/verificacaoConfirmacoes/') ?>',
        type: 'post',
        dataType: 'json',
        data: form.serialize(),

        success: function(validacaoRespostas) {
            if (validacaoRespostas.success === true) {
                Swal.fire({
                    position: 'center-end',
                    icon: 'success',
                    html: validacaoRespostas.messages,
                    showConfirmButton: true,
                    confirmButtonText: 'Ok',
                })
            } else {
                Swal.fire({
                    position: 'center-end',
                    icon: 'error',
                    html: validacaoRespostas.messages,
                    showConfirmButton: true,
                    confirmButtonText: 'Ok',
                })
            }
        }
    }).always(
        Swal.fire({
            title: 'Estamos verificando as informações',
            html: 'Aguarde....',
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading()


            }

        }))
}

function buscaPessoa() {

    if ($("#informecpfForm #cpfRecuperaSenha").val() == null || $("#informecpfForm #cpfRecuperaSenha").val() == undefined || $("#informecpfForm #cpfRecuperaSenha").val() == "") {
        Swal.fire({
            position: 'center-end',
            icon: 'error',
            title: 'Forneça um CPF válido',
            showConfirmButton: false,
            timer: 5000
        });
    } else {

        $.ajax({
            url: '<?php echo base_url('Verificalogin/pegaUsuario/') ?>',
            type: 'post',
            dataType: 'json',
            data: {
                cpf: $("#informecpfForm #cpfRecuperaSenha").val(),
                codOrganizacao: $("#informecpfForm #codOrganizacaoRecuperaSenha").val(),
                codPerfil: $("#informecpfForm #codPerfilRecuperaSenha").val(),
                csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },

            success: function(respostaBusca) {

                if (respostaBusca.success === true) {


                    $('#informecpfModal').modal('hide');
                    if (respostaBusca.emailPessoal === true) {

                        Swal.fire({
                            position: 'center-end',
                            icon: 'success',
                            html: respostaBusca.messages,
                            showConfirmButton: false,
                            timer: 5000
                        })

                    } else {
                        $('#recuperaSenhaModal').modal('show');
                        document.getElementById('formConfirmacao').innerHTML = respostaBusca.formConfirmacao;
                        $("#confirmacoesForm #cpfConfirmacoes").val(respostaBusca.cpf)
                        $("#confirmacoesForm #codOrganizacaoConfirmacoes").val(respostaBusca.codOrganizacao)

                    }

                } else {

                    $('#informecpfModal').modal('hide');
                    Swal.fire({
                        position: 'center-end',
                        icon: 'error',
                        html: respostaBusca.messages,
                        showConfirmButton: false,
                        timer: 4000
                    })
                }
            }
        });
    }






}
</script>

</body>

</html>