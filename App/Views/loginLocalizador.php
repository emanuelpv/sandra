<?php

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
?>
<main id="main">



    <!-- ======= login Section ======= -->
    <section id="login" class="login">
        <div style="text-align:center" class="container col-sm-12 d-flex justify-content-center">

            <div class="section-title" data-aos="fade-up">

                <div>
                    <img style='margin-left:5px;width:110px' src="<?php echo base_url()."/imagens/organizacoes/" . session()->logo ?>">

                </div>

                <div class="col-sm-12">
                    <div class="card card-primary">

                        <div class="card-body">
                            <div class="login-box col-12 col-sm-12">
                                <div style="margin-bottom:20px;color:red; font-weight:bold">Área restrita a colaboradores apenas.
                                </div>
                                <div>
                                    <form id="colaboradorLocalizadorForm" method="post" action="<?php echo base_url() ?>/verificalogin" class="pl-3 pr-3">

                                        <input type="hidden" id="<?php echo csrf_token() ?>colaboradorForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                                        <input type="hidden" name="localizador" value="1">
                                        <input type="hidden" name="codAtendimentoPrescricao" value=<?php echo $codAtendimentoPrescricao ?>>
                                        <input type="hidden" name="perfilLogin" value="2">
                                        <input type="hidden" name="codOrganizacao" value="<?php echo $codOrganizacao ?>">

                                        <div class="input-group mb-3">
                                            <input id="login" name="login" type="text" placeholder="Login" class="form-control">
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span class="fas fa-user"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-group mb-3">
                                            <input id="senha" name="senha" type="password" placeholder="Senha" class="form-control">
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span class="fas fa-lock"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                                            </div>
                                            <!-- /.col -->
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>





            </div>


    </section>
    <!-- End Portfolio Section -->

</main>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/jquery/jquery.min.js"></script>

<script src="<?php echo base_url() ?>/assets/adminlte/plugins/sweetalert2/sweetalert2.min.js"></script>

<script>
    $('#colaboradorLocalizadorForm').submit(function(event) {
        Swal.fire({
            title: 'Estamos verificando suas credenciais',
            html: 'Aguarde....',
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading()
            }

        })
    });
</script>