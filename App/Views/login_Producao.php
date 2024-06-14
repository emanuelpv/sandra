<?php
$codOrganizacao = session()->codOrganizacao;
$descricaoOrganizacao = session()->descricao;
$siglaOrganizacao = session()->siglaOrganizacao;


?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Sistema Hospitalar Sandra 2.0</title>
    <meta name="description" content="Sistema de Marcação de Consultas, AgendamentosExames e Encaminhamentos" />

    <meta property="og:title" content="Sistema de Gestão Hospitalar Sandra 2.0" />
    <meta property="og:url" content="<?php echo base_url() ?>" />
    <meta property="og:description" content="Consultas, AgendamentosExames e Encaminhamentos" />
    <meta property="og:image" content="<?php echo base_url() . '/imagens/organizacoes/' . session()->logo; ?>" />

    <meta name="keywords" content=" <?php echo $siglaOrganizacao ?>, SANDRA, SANDRA 2.0, SGH, Gestão Hospitalar, Consultas, AgendamentosExames, Encaminhamentos, Marcação">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

    <!-- Fontawesome Icons -->
    <link href="<?php echo base_url() ?>/assets/landing/css/all.min.css" rel="stylesheet">

    <!-- Icons -->

    <link rel="icon" href="<?php echo base_url() ?>/imagens/favicon.ico" />
    <link href="<?php echo base_url() . '/imagens/organizacoes/' . session()->logo; ?>" rel="icon">
    <link href="<?php echo base_url() ?>/imagens/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Vendor CSS Files -->
    <link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/dist/css/adminlte.min.css">

    <script src="<?php echo base_url() ?>/assets/adminlte/plugins/sweetalert2/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">

    <link href="<?php echo base_url() ?>/assets/landing/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>/assets/landing/css/icofont/icofont.min.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>/assets/landing/css/venobox.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>/assets/landing/css/owl.carousel.min.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>/assets/landing/css/aos.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="<?php echo base_url() ?>/assets/landing/css/style.css" rel="stylesheet">



    <link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/toastr/toastr.min.css">
    <script src="<?php echo base_url() ?>/assets/adminlte/plugins/toastr/toastr.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/bs-stepper/css/bs-stepper.min.css">

    <!-- ===== fontawesome ICONS ===== -->
    <link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/fontawesome-free/css/all.min.css">


    <script>
        var csrfName = '<?php echo csrf_token() ?>';
        var csrfHash = '<?php echo csrf_hash() ?>';
    </script>
    <input type="hidden" id="csrf_sandraPrincipal" name="csrf_sandraPrincipal" value="<?php echo csrf_hash() ?>">


</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top">
        <div class="container-fluid d-flex">

            <div class=" mr-auto">
                <a href="#"><img style="width:100px !important ;height:auto !important" src="<?php echo base_url() . '/imagens/organizacoes/' . session()->logo; ?>" alt="" class="img-fluid"></a>
            </div>


            <nav class="d-lg-none .d-xl-block">
                <a style="width:100px; margin-right: 40px;" href="#login" class="btn btn-block btn-info" title="Login"> <i class="fa fa-arrow-right"></i>Acesso</a>
            </nav>

            <nav class="nav-menu d-none d-lg-block">
                <ul>
                    <li class="active"><a href="#header">Home</a></li>
                    <li><a href="#sobre">Conheça</a></li>
                    <li><a href="#services">Serviços</a></li>
                    <li><a href="" onclick="profissionaisSaude()">Profissionais de Saúde</a></li>
                    <li><a href="<?php echo base_url('politicaPrivacidade') ?>" target="_blank">Política de Privacidade</a></li>
                    <li><a href="" onclick="termosDeUso()">Termos de Uso</a></li>
                    <li><a href="#contact">Contate-nos</a></li>
                    <li><a href="#social">Redes sociais</a></li>
                </ul>
            </nav>
            <!-- .nav-menu -->
            <div class="d-none d-lg-block">
                <a style="width:100px" href="#login" class="btn btn-block btn-info" title="Login"> <i class="fa fa-arrow-right"></i>Acesso</a>
            </div>
        </div>

    </header>
    <!-- End Header -->

    <section id="hero" class="d-flex align-items-center">

        <div class="container">
            <div class="row">
                <div class="col-lg-6 pt-5 pt-lg-0 order-2 order-lg-1">
                    <h1>SANDRA 2.0</h1>
                    <h3>SISTEMA DE GESTÃO HOSPITALAR</h3>
                    <div>
                        Este é o novo sistema de gerenciamento de processos hospitalares, marcação de consultas e solicitações de encaminhamentos de agendamentosExames.
                    </div>

                </div>


                <div class="col-lg-5 d-flex align-items-center justify-content-center sobre-img">
                    <!--    <video width="900px" height="auto" class="img-fluid" alt="" data-aos="zoom-in" controls>
                                 <source src="imagens/sti.mp4" type="video/mp4">
                                </video>
                    -->

                    <?php echo session()->hero ?>


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

                    <div class="col-lg-6 pt-5 pt-lg-0">
                        <h3 data-aos="fade-up">Conheça o sistema Sandra</h3>
                        <p></p>
                        <p data-aos="fade-up" data-aos-delay="90">
                            O sistema Sandra é uma plataforma Opensource desenvolvida para ser uma ferramenta de apoio aos processos Hospitalares e melhorar a experiência dos seus usuários.
                        <p data-aos="fade-up" data-aos-delay="90">
                            O sistema dispõe de controle de agendamentos, resultados de agendamentosExames e solicitação de encaminhamentos.

                        <p data-aos="fade-up" data-aos-delay="90">
                            Para utilizar os recursos oferecidos pela plataforma realize seu cadastramento.
                        </p>
                        <p data-aos="fade-up" data-aos-delay="10">
                            <a style="color:#fff" onclick="recuperaSenhaPaciente()" class="btn btn-info">
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

                <div class="row">
                    <div class="col-md-12 col-lg-3 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="400">
                        <div class="icon-box col-md-12">

                            <h4 class="title"><a>CONSULTAS</a></h4>
                            <p class="description">Marque suas consultas pela internet de forma ágil e sem complicações.</p>
                        </div>
                    </div>

                    <div class="col-md-12 col-lg-3 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="400">
                        <div class="icon-box col-md-12">

                            <h4 class="title"><a>EXAMES</a></h4>
                            <p class="description">Agende seus agendamentosExames e encaminhamentos para Organizações Civís de Saúde ou Profissional de saúde Autônomo</p>
                        </div>
                    </div>

                    <div class="col-md-12 col-lg-3 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="100">
                        <div class="icon-box col-md-12">
                            <h4 class="title"><a>RESULTADOS</a></h4>
                            <p class="description">Consulte o resultado de agendamentosExames </p>
                        </div>
                    </div>

                    <div class="col-md-12 col-lg-3 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="200">
                        <div class="icon-box col-md-12">
                            <h4 class="title"><a>IMPRESSÃO DE GUIAS</a></h4>
                            <p class="description">Imprima as guias de encaminhamentos para agendamentosExames e procedimentos externos.</p>
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
                        <div class="card card-primary card-outline card-outline-tabs">
                            <div class="card-header p-0 border-bottom-0">
                                <ul class="nav nav-tabs" id="abas" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="abaPacientes" data-toggle="pill" href="#abahome" role="tab" aria-controls="abahome" aria-selected="true">Beneficiário</a>
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

                                                    <input type="hidden" id="<?php echo csrf_token() ?>abasContent" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                                                    <input type="hidden" id="perfilLogin" name="perfilLogin" value="1">
                                                    <input type="hidden" id="codOrganizacao" name="codOrganizacao" value="<?php echo $codOrganizacao ?>">

                                                    <div class="input-group mb-3">
                                                        <input id="cpfPaciente" name="login" type="text" placeholder="Login é seu CPF" class="form-control">
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
                                                        <div class="col-6">
                                                            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                                                        </div>
                                                        <div class="col-6">
                                                            <button onclick="recuperaSenhaPaciente()" class="btn btn-danger btn-block">Esqueci a senha</button>
                                                        </div>
                                                        <!-- /.col -->
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="abaprofile" role="tabpanel" aria-labelledby="abaColaboradores">
                                        <div class="login-box col-12 col-sm-12">
                                            <div style="margin-bottom:20px;color:red; font-weight:bold">Área restrita a colaboradores apenas.
                                            </div>
                                            <div>
                                                <form id="colaboradorForm" method="post" action="verificalogin" class="pl-3 pr-3">

                                                    <input type="hidden" id="<?php echo csrf_token() ?>abaprofile" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                                                    <input type="hidden" name="perfilLogin" value="2">
                                                    <input type="hidden" name="codOrganizacao" value="1">

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
                                                        <div class="col-6">
                                                            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                                                        </div>
                                                        <div class="col-6">
                                                            <button onclick="recuperaSenhaSaude()" class="btn btn-danger btn-block">Esqueci a senha</button>
                                                        </div>
                                                        <!-- /.col -->
                                                    </div>
                                                </form>

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

                            <div class="address">
                                <img style="width:30px" src="<?php echo base_url() . "//imagens/localizacao.png" ?>">
                                <h4>Localização:</h4>
                                <p><?php echo session()->endereço . "," . session()->cidade . "-" . session()->uf . ", CEP: " . session()->cep ?></p>
                            </div>

                            <div class="email">
                                <img style="width:30px" src="<?php echo base_url() . "/imagens/notificacoes.png" ?>">
                                <h4>Email:</h4>
                                <p> <?php echo session()->faleConosco ?></p>
                            </div>
                            <div class="Telefones">
                                <img style="width:30px" src="<?php echo base_url() . "/imagens/notificacoes.png" ?>">
                                <h4>Telefones:</h4>
                                <p> <?php echo session()->contatos ?></p>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </section>
        <!-- End Contact Us Section -->

    </main>
    <!-- End #main -->

    <!-- ======= Footer ======= -->

    <footer id="footer">

        <section id="social" class="social">
            <div class="footer-top">
                <div class="container">
                    <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="400">

                        <div class="footer-links">
                            <p>Nossas Redes Sociais:</p>
                            <div class="mt-3 d-flex justify-content-center">
                                <a href="https://pt-br.facebook.com/" class="github" target="_blank"><img style="width:30px;margin-left:10px" src="<?php echo base_url() . "/imagens/facebook.png" ?>"></a>
                                <a href="https://www.instagram.com/_oficial/" class="instagram" target="_blank"><img style="width:30px;margin-left:10px" src="<?php echo base_url() . "/imagens/instagram.png" ?>"></a>
                                <a href="https://br.linkedin.com/company/" class="linkedin" target="_blank"><img style="width:30px;margin-left:10px" src="<?php echo base_url() . "/imagens/linkedin.png" ?>"></a>
                                <a href="https://twitter.com/oficial" class="medium" target="_blank"><img style="width:30px;margin-left:10px" src="<?php echo base_url() . "/imagens/twitter.png" ?>"></a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>




        <div id="termosDeUsoModal" class="modal fade" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-center p-3">
                        <h4 class="modal-title text-white" id="info-header-modalLabel">TERMOS DE USO</h4>
                        <div class="d-lg-none .d-xl-block">
                            <button style="margin-right: 30px;" type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">Fechar</span>
                            </button>
                        </div>

                        <div class="d-none d-lg-block">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>


                    </div>
                    <div class="modal-body">

                        <div class="row">


                            <?php

                            use App\Models\TermosModel;

                            $this->TermosModel = new TermosModel();
                            $data = $this->TermosModel->pegaTudo();
                            foreach ($data as $termo) {

                            ?>
                                <div style="margin-top:10px" class="col-md-4">
                                    <button type="button" class="btn btn-block btn-outline-info btn-sm" onclick="verTermo(<?php echo $termo->codTermo; ?>)"> <i class="fa fa-file-signature"></i><?php echo $termo->assunto; ?></button>
                                </div>
                            <?php

                            }
                            ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div id="verTermoModal" class="modal fade" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-center p-3">
                        <h4 class="modal-title text-white" id="info-header-modalLabel">TERMOS DE USO</h4>


                        <div class="d-lg-none .d-xl-block">
                            <button style="margin-right: 30px;" type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">Fechar</span>
                            </button>
                        </div>

                        <div class="d-none d-lg-block">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>


                    </div>
                    <div class="modal-body">

                        <div id="verTermoContent" class="row">

                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div id="informecpfModal" class="modal fade" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-center p-3">
                        <h4 class="modal-title text-white" id="info-header-modalLabel">RECUPERAÇÃO DE SENHA E PRIMEIRO ACESSO</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div>O sistema de recuperação de senha e primeiro acesso necessita que sejam fornecidas informações para validar seu cadastro.</div>
                        <div style="margin-bottom:10px">Após o processo, será enviado um e-mail contendo os dados de acesso ao sistema.</div>
                        <form id="informecpfForm" class="pl-3 pr-3">
                            <input type="hidden" id="<?php echo csrf_token() ?>informecpfModal" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                            <input type="hidden" id="codOrganizacaoRecuperaSenha" name="codOrganizacao" class="form-control" maxlength="11" required>
                            <input type="hidden" id="codPerfilRecuperaSenha" name="codPerfil" class="form-control" maxlength="11" required>
                            <input style="width:200px;margin-bottom:10px" type="text" id="cpfRecuperaSenha" name="cpf" class="form-control" placeholder="Informe seu cpf" maxlength="14" required>

                            <div style="margin-top:30px">
                                <button type="button" class="btn btn-primary" onclick="buscaPessoa()" title="Buscar"></i>Avançar</button>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>


        <div id="recuperaSenhaModal" class="modal fade" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-center p-3">
                        <h4 class="modal-title text-white" id="info-header-modalLabel">PROCESSO DE CONFIRMAÇÃO DE DADOS</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div style=" max-height: calc(100vh - 200px); overflow-y: auto;" class="modal-body">

                        <div class="row">
                            <div class="col-12 col-sm-12">


                                <div class="card-body">
                                    <form id="confirmacoesForm" class="pl-3 pr-3">
                                        <input type="hidden" id="<?php echo csrf_token() ?>recuperaSenhaModal" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                                        <input type="hidden" id="respostas" name="respostas" value=1 class="form-control" placeholder="respostas" maxlength="11" required>
                                        <input type="hidden" id="cpfConfirmacoes" name="cpf" class="form-control" maxlength="14" required>
                                        <input type="hidden" id="codOrganizacaoConfirmacoes" name="codOrganizacao" class="form-control" maxlength="12" required>

                                        <div id="formConfirmacao">
                                        </div>

                                        <button type="button" class="btn btn-primary" onclick="verificarConfirmacoes()" title="Filtrar">VERIFICAR</button>

                                        <form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <?php

        ?>




        <div id="profissionaisSaudeModal" class="modal fade" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-center p-3">
                        <h4 class="modal-title text-white" id="info-header-modalLabel">Profissionais de Saúde</h4>

                        <div class="d-lg-none .d-xl-block">
                            <button style="margin-right: 30px;" type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">Fechar</span>
                            </button>
                        </div>

                        <div class="d-none d-lg-block">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>






                    </div>
                    <div class="modal-body">
                        <style>
                            .borda {
                                background: -webkit-linear-gradient(left top, #fffe01 0%, #28a745 100%);
                                border-radius: 1000px;
                                padding: 6px;
                                width: 300px;
                                height: 300px;

                            }
                        </style>

                        <div id="listaProfissionaisSaude" class="row"></div>
                    </div>
                </div>
            </div>
        </div>


        <div class="container py-4">
            <div class="copyright">
                <strong><span>Desenvolvedor Emanuel Peixoto Vicente | https://www.linkedin.com/in/emanuelpv/</span></strong>
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
</html>