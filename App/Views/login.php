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

<section id="hero" class="d-flex align-items-center">

    <div class="container">


        <div class="row">

            <div class="col-md-6 d-flex align-items-center justify-content-center sobre-img">


                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" data-interval="3000" data-pause="hover" data-wrap="true">
                    <ol class="carousel-indicators">
                        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                    </ol>
                    <div style="width:430px;height:450px" class="carousel-inner" id="slideShow">
                        <?php
                        echo slideShow();
                        ?>

                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                        <span class="carousel-control-custom-icon" aria-hidden="true">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                        <span class="sr-only">Anterior</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                        <span class="carousel-control-custom-icon" aria-hidden="true">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                        <span class="sr-only">Próximo</span>
                    </a>
                </div>


            </div>
            <div class="col-md-6 sobre-img">
                <h1>SANDRA 2.0</h1>
                <div style="font-size:28px">
                    SISTEMA HOSPITALAR OPENSOURCE
                </div>
                <div>
                    Este é o novo sistema de gerenciamento de processos hospitalares, marcação de consultas e solicitações de encaminhamentos de Exames.
                </div>

            </div>

        </div>
    </div>

</section>
<!-- End Hero -->

<main id="main">

    <!-- ======= sobre Section ======= -->
    <section id="sobre" class="sobre">
        <div class="container">

            <div class="row justify-content-between">


                <div class="col-lg-6 order-1 order-lg-2 hero-img">
                    <img style="width:200px" src="<?php echo base_url() ?>/imagens/eblogo.png" class="img-fluid animated" alt="" width="70%">
                </div>

                <div style="margin-top:20px" class="col-lg-6 pt-5 pt-lg-0">
                    <h3 data-aos="fade-up">Conheça o sistema Sandra</h3>
                    <p></p>
                    <p data-aos="fade-up" data-aos-delay="90">
                        O sistema Sandra é uma plataforma Opensource desenvolvida para ser uma ferramenta de apoio aos processos Hospitalares e melhorar a experiência dos seus usuários.
                    <p data-aos="fade-up" data-aos-delay="90">
                        O sistema dispõe de controle de agendamentos, resultados de Exames e solicitação de encaminhamentos.

                    <p data-aos="fade-up" data-aos-delay="90">
                        Para utilizar os recursos oferecidos pela plataforma realize seu cadastramento.
                    </p>
                    <p data-aos="fade-up" data-aos-delay="10">
                        <a style="color:#fff" onclick="primeiroAcesso()" class="btn btn-info">
                            Meu primeiro acesso
                        </a>

                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- End sobre Section -->

    <!-- ======= Services Section ======= -->
    <section id="services" class="services section-bg">
        <div class="container">

            <div class="section-title" data-aos="fade-up">
                <p>Serviços</p>
            </div>

            <div class="row tipoServico">
                <div class="col-md-12 col-lg-3 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="400">
                    <div class="icon-box col-md-12">

                        <h4 class="title"><a>CONSULTAS</a></h4>
                        <p class="description">Marque suas consultas pela internet de forma ágil e sem complicações.</p>
                    </div>
                </div>

                <div class="col-md-12 col-lg-3 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="400">
                    <div class="icon-box col-md-12">

                        <h4 class="title"><a>EXAMES</a></h4>
                        <p class="description">Agende seus Exames e encaminhamentos para Organizações Civís de Saúde ou Profissional de saúde Autônomo</p>
                    </div>
                </div>

                <div class="col-md-12 col-lg-3 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="100">
                    <div class="icon-box col-md-12">
                        <h4 class="title"><a>RESULTADOS</a></h4>
                        <p class="description">Consulte o resultado de Exames </p>
                    </div>
                </div>

                <div class="col-md-12 col-lg-3 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="200">
                    <div class="icon-box col-md-12">
                        <h4 class="title"><a>IMPRESSÃO DE GUIAS</a></h4>
                        <p class="description">Imprima as guias de encaminhamentos para Exames e procedimentos externos.</p>
                    </div>
                </div>

            </div>

        </div>
    </section>
    <!-- End Services Section -->

    <!-- ======= login Section ======= -->
    <section id="login" class="login">
        <div style="text-align:center" class="container col-sm-12 d-flex justify-content-center">

            <div class="section-title" data-aos="fade-up">

                <p>Acesso</p>





                <div class="col-sm-12">
                    <div class="card card-primary">
                        <div class="card-header p-0 border-bottom-0">
                            <ul class="nav nav-tabs" id="abas" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="abaPacientes" data-toggle="pill" href="#abahome" role="tab" aria-controls="abahome" aria-selected="true">Pacientes</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="abaColaboradores" data-toggle="pill" href="#abaprofile" role="tab" aria-controls="abaprofile" aria-selected="false">Colaboradores e Prestadores de Serviço</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="abasContent">
                                <div class="tab-pane fade show active" id="abahome" role="tabpanel" aria-labelledby="abaPacientes">
                                    <div class="login-box col-12 col-sm-12">

                                        <div>

                                            <form id="beneficiarioForm" method="post" action="verificalogin" class="pl-3 pr-3">

                                                <input type="hidden" id="<?php echo csrf_token() ?>beneficiarioForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                                                <input type="hidden" id="perfilLogin" name="perfilLogin" value="1">
                                                <input type="hidden" id="codOrganizacao" name="codOrganizacao" value="<?php echo $codOrganizacao ?>">

                                                <div style="color:gray;" class="text-left">Login (CPF ou E-mail)</div>

                                                <div class="input-group mb-3">
                                                    <input name="login" type="text" class="form-control">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <span class="fas fa-user"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="color:gray;" class="text-left">Senha</div>

                                                <div class="input-group mb-3">
                                                    <input id="senha" name="senha" type="password" class="form-control">
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
                                                    <div style="margin-top:5px" class="col-12">
                                                        <button onclick="recuperaSenhaPaciente()" class="btn btn-danger btn-block">Esqueci a senha</button>
                                                    </div>
                                                    <!-- /.col -->
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                    <div style="color:red">
                                        Login: paciente | senha: paciente
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="abaprofile" role="tabpanel" aria-labelledby="abaColaboradores">
                                    <div class="login-box col-12 col-sm-12">
                                        <div style="margin-bottom:20px;color:red; font-weight:bold">Área restrita a colaboradores apenas.
                                        </div>
                                        <div>
                                            <form id="colaboradorForm" method="post" action="verificalogin" class="pl-3 pr-3">

                                                <input type="hidden" id="<?php echo csrf_token() ?>colaboradorForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

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
                                                    <div style="margin-top:5px" class="col-12">
                                                        <button onclick="recuperaSenhaSaude()" class="btn btn-danger btn-block">Esqueci a senha</button>
                                                    </div>
                                                    <!-- /.col -->
                                                </div>
                                            </form>
                                            
                                    <div style="color:red">
                                        Login: admin | senha: admin
                                    </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>





            </div>


    </section>
    <!-- End Portfolio Section -->

    <!-- ======= Contact Us Section ======= -->
    <section id="contact" class="contact">
        <div class="container">

            <div class="section-title" data-aos="fade-up">
                <p>Contate-nos</p>
            </div>

            <div class="row justify-content-center">

                <div class="col-lg-5 d-flex align-items-stretch justify-content-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="info">

                        <div class="row">
                            <div class="col-md-1">
                                <img style="width:30px" src="<?php echo base_url() . "/imagens/localizacao.png" ?>">

                            </div>
                            <div class="col-md-11 address">
                                <h4>Localização:</h4>
                                <p><?php echo session()->endereço . "," . session()->cidade . "-" . session()->uf . ", CEP: " . session()->cep ?></p>
                            </div>
                        </div>



                        <div class="row">
                            <div class="col-md-1">
                                <img style="width:30px" src="<?php echo base_url() . "/imagens/notificacoes.png" ?>">

                            </div>
                            <div class="col-md-11 Telefones">
                                <h4>Telefones:</h4>
                                <p> <?php echo session()->contatos ?></p>
                            </div>
                        </div>




                        <div class="row">
                            <div class="col-md-1">
                                <img style="width:30px" src="<?php echo base_url() . "/imagens/notificacoes.png" ?>">

                            </div>
                            <div class="col-md-11 email">
                                <h4>Email:</h4>
                                <p> <?php echo session()->faleConosco ?></p>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-12">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d159276.88201175214!2d-34.95674934110621!3d-8.043487917847871!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x7ab196f94e5408b%3A0xe5800ef782bde3a6!2sRecife%2C%20PE!5e0!3m2!1spt-PT!2sbr!4v1718317790711!5m2!1spt-PT!2sbr" width="100%" height="auto" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </section>
    <!-- End Contact Us Section -->

    <section id="social" class="social">
        <div class="footer-top">
            <div class="container">
                <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="400">

                    <div class="footer-links">
                        <p>Redes Sociais:</p>
                        <div class="mt-3 d-flex justify-content-center">
                            <a href="https://github.com/emanuelpv" class="linkedin" target="_blank"><img style="width:30px;margin-left:10px" src="<?php echo base_url() . "/imagens/github.png" ?>"></a>
                            <a href="https://www.linkedin.com/in/emanuelpv/" class="linkedin" target="_blank"><img style="width:30px;margin-left:10px" src="<?php echo base_url() . "/imagens/linkedin.png" ?>"></a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</main>