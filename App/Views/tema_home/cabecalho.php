<?php
$configuracao = config('App');
session()->set('codOrganizacao', $configuracao->codOrganizacao);
$codOrganizacao = $configuracao->codOrganizacao;


?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Sistema Hospitalar Sandra 2.0</title>
  <meta name="description" content="Sistema de Marcação de Consultas, Exames e Encaminhamentos" />

  <meta property="og:title" content="Sistema de Gestão Hospitalar Sandra 2.0" />
  <meta property="og:url" content="<?php echo base_url() ?>" />
  <meta property="og:description" content="Consultas, Exames e Encaminhamentos" />
  <meta property="og:image" content="<?php echo base_url() . '/imagens/organizacoes/' . session()->logo; ?>" />

  <meta name="keywords" content=" <?php echo $siglaOrganizacao ?>, SANDRA, SANDRA 2.0, SGH, Gestão Hospitalar, Consultas, Exames, Encaminhamentos, Marcação">

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

  <style>
    .mobile-nav {
      background: <?php echo session()->corFundoPrincipal ?> !important;
      color: <?php echo session()->corTextoPrincipal ?> !important;
    }

    .header_hero {
      background: <?php echo session()->corBackgroundMenus ?> !important;
      color: <?php echo session()->corTextoPrincipal ?> !important;

    }


    .nav-menu a {
      color: <?php echo session()->corFundoPrincipal ?> !important;
    }

    .nav-menu a:hover,
    .nav-menu .active>a,
    .nav-menu li:hover>a {
      color: <?php echo session()->corTextoPrincipal ?>;
      font-weight: bold;
    }

    .mobile-nav a {
      color: <?php echo session()->corTextoPrincipal ?> !important;
    }

    #hero {
      background: <?php echo session()->corFundoPrincipal ?>;
      border-bottom: 2px solid <?php echo session()->corFundoPrincipal ?>;
      color: <?php echo session()->corTextoPrincipal ?>;
    }

    #hero h1,
    h2,
    h3,
    h4,
    h5 {
      color: <?php echo session()->corTextoPrincipal ?>;
    }

    
    #contact {
      background: <?php echo session()->corFundoPrincipal ?>;
      border-bottom: 2px solid <?php echo session()->corFundoPrincipal ?>;
      color: <?php echo session()->corTextoPrincipal ?>;
    }

    .contact .info p,
    h4 {
      color: <?php echo session()->corFundoPrincipal ?> !important;
    }


    #contact p,
    h1,
    h2,
    h3,
    h5 {
      color: <?php echo session()->corTextoPrincipal ?>;
    }


    .services {
      background: <?php echo session()->corFundoPrincipal ?>;
      border-bottom: 2px solid <?php echo session()->corFundoPrincipal ?>;
      color: <?php echo session()->corTextoPrincipal ?>;
    }


    .services p,
    h1,
    h2,
    h3,
    h4,
    h5 {
      color: <?php echo session()->corTextoPrincipal ?>;
    }

    .services .title a {
      color: <?php echo session()->corFundoPrincipal ?>;
    }

    .tipoServico p,
    h1,
    h2,
    h3,
    h4,
    h5 {
      color: <?php echo session()->corFundoPrincipal ?>;
    }

    .back-to-top {
      background: <?php echo session()->corFundoPrincipal ?>;
      color: <?php echo session()->corTextoPrincipal ?>;
    }

    .nav-link {
      background: <?php echo session()->corFundoPrincipal ?>;
      color: <?php echo session()->corTextoPrincipal ?>;
    }

    .btn-primary {
      background: <?php echo session()->corFundoPrincipal ?>;
      color: <?php echo session()->corTextoPrincipal ?>;
    }

    .btn-primary {

      background: <?php echo session()->corFundoPrincipal ?>;
      color: <?php echo session()->corTextoPrincipal ?>;
      border-color: <?php echo session()->corFundoPrincipal ?> !important;
    }

    .btn-primary:hover {

      background: <?php echo session()->corFundoPrincipal ?>;
      color: <?php echo session()->corTextoPrincipal ?>;
      border-color: <?php echo session()->corFundoPrincipal ?> !important;
      opacity: .8;
    }

    .bg-primary,
    .btn-info,
    .swal2-styled.swal2-confirm {
      background-color: <?php echo session()->corFundoPrincipal ?> !important;
      border-color: <?php echo session()->corFundoPrincipal ?> !important;
    }

    .text-success {
      color: <?php echo session()->corFundoPrincipal ?> !important;
    }

    .bg-primary:hover,
    .btn-info:hover {
      opacity: .8;
    }

    .toast {
      -webkit-flex-basis: 350px;
      -ms-flex-preferred-size: 350px;
      flex-basis: 350px;
      max-width: 350px;
      font-size: .875rem;
      background-color: rgba(255, 255, 255, .85);
      background-clip: padding-box;
      border: 1px solid rgba(0, 0, 0, .1);
      box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, .1);
      opacity: 0;
      border-radius: 0.25rem;
    }

    .back-to-top:hover {
      color: <?php echo session()->corTextoPrincipal ?>;
      background: <?php echo session()->corFundoPrincipal ?>;
      transition: background 0.2s ease-in-out;
    }

    #footer {
      background: <?php echo session()->corFundoPrincipal ?>;
      border-bottom: 2px solid <?php echo session()->corFundoPrincipal ?>;
      color: <?php echo session()->corTextoPrincipal ?>;
    }

    #footer h1,
    h2,
    h3,
    h4,
    h5 {
      color: <?php echo session()->corTextoPrincipal ?>;
    }
  </style>


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


  <div id="primeiroAcessoModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-primary text-center p-3">
          <h4 class="modal-title text-white" id="info-header-modalLabel">MEU PRIMEIRO ACESSO</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div>Para utilizar os recursos do sistema será necessário fornecer as informações a seguir:</div>
          <div style="margin-bottom:10px">Após o processo, será enviado um e-mail contendo os dados de acesso ao sistema.</div>
          <form id="primeiroAcessoForm" class="pl-3 pr-3">
            <input type="hidden" id="<?php echo csrf_token() ?>primeiroAcessoForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

            <div class="login-box col-12 col-sm-6">
              <div class="input-group mb-3">
                <label>Email</label>
                <input name="login" type="text" class="form-control">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                  </div>
                </div>
              </div>
            </div>
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

                </form>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


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

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <style>
            .borda {
              background: -webkit-linear-gradient(left top, #fffe01 0%, #28a745 100%);
              border-radius: 1000px;
              padding: 6px;
              width: 200px;
              height: 200px;

            }

            .callout {
              height: 250px !important;

            }

            .callout.callout-info {
              border-left-color: green
            }

            #footer h1,
            h2,
            h3,
            h4,
            h5 {
              color: #343a40;
            }
          </style>

          <div id="listaProfissionaisSaude" class="row"></div>

        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>