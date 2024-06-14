<?php
//É NECESSÁRIO EM TODAS AS VIEWS

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Sandra </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="shortcut icon" href="<?php echo base_url('/imagens/favicon.ico') ?>" type="image/x-icon" />


  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/dist/css/adminlte.min.css">

  <link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/fontawesome-free/css/all.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro 
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  -->
  <link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/toastr/toastr.min.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/select2/css/select2.min.css">

  <link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

  <!-- DATATABLES -->
  <link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">



  <link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">

  <link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/autocomplete/autocomplete.css">


  <link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/dropzone/min/dropzone.min.css">


  <script>
    var csrfName = '<?php echo csrf_token() ?>';
    var csrfHash = '<?php echo csrf_hash() ?>';
  </script>
  <input type="hidden" id="csrf_sandraPrincipal" name="csrf_sandraPrincipal" value="<?php echo csrf_hash() ?>">
</head>

<!-- <body class="hold-transition sidebar-mini text-sm"> -->
<style>
  .modal-content {
    position: relative;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
    width: 100%;
    pointer-events: auto;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid rgba(0, 0, 0, .2);
    border-radius: 0.3rem;
    box-shadow: 0 0.25rem 0.5rem rgb(0 0 0 / 50%);
    outline: 0;
    font-size: 16px;
  }



  .modal {
    padding-left: 10px !important;
    padding-right: 10px !important;
  }

  .modal-dialog {
    max-width: 95% !important;
    height: 100%;
    padding-left: 10px !important;
    padding-right: 10px !important;
  }

  .form-control {
    display: block;
    width: 100%;
    height: calc(1.6rem + 2px);
    padding: 0.375rem 0.75rem;
    font-size: 12px;
    font-weight: 400;
    line-height: 1;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    box-shadow: inset 0 0 0 transparent;
    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
  }

  .custom-select {
    font-size: 12px;
    height: calc(2rem + 2px);


  }

  .nav-tabs .nav-item.show .nav-link,
  .nav-tabs .nav-link.active {
    color: #000;
    background-color: <?php echo session()->corFundoPrincipal ?>;
    font-weight: bold;

  }


  .bg-primary,
  .btn-info,
  .swal2-styled.swal2-confirm {
    background-color: <?php echo session()->corFundoPrincipal ?> !important;
    border-color: <?php echo session()->corFundoPrincipal ?> !important;
  }

  .bg-primary:hover,
  .btn-info:hover,
  .swal2-styled.swal2-confirm:hover {
    opacity: .8;
  }


  .btn-outline-primary, .btn-outline-secondary {
    background-color: <?php echo session()->corFundoPrincipal ?> !important;
    border-color: <?php echo session()->corFundoPrincipal ?> !important;
    color: <?php echo session()->corTextoPrincipal ?> !important;
  }

  .btn-outline-primary:hover,.btn-outline-secondary:hover {
    opacity: .8;
  }



  .element {
    color: <?php echo session()->corTextoPrincipal ?> !important;
  }

  .btn-primary {
    color: <?php echo session()->corMenus ?> !important;
    background-color: <?php echo session()->corFundoPrincipal ?> !important;
    border-color: <?php echo session()->corFundoPrincipal ?> !important;
  }

  .btn-primary:hover {
    opacity: .8;
    background-color: <?php echo session()->corFundoPrincipal ?> !important;
    border-color: <?php echo session()->corFundoPrincipal ?> !important;
  }



  .nav-tabs {
    background-color: <?php echo session()->corFundoPrincipal ?> !important;
    border-color: <?php echo session()->corFundoPrincipal ?> !important;
    color: <?php echo session()->corTextoPrincipal ?> !important;
  }

  .page-link {
    position: relative;
    display: block;
    padding: 0.5rem 0.75rem;
    margin-left: -1px;
    line-height: 1.25;
    color: <?php echo session()->corFundoPrincipal ?>;
    background-color: <?php echo session()->corTextoPrincipal ?>;
    border: 1px solid <?php echo session()->corFundoPrincipal ?>;
  }

  .page-item.active .page-link {
    background-color: <?php echo session()->corFundoPrincipal ?> !important;
    border-color: <?php echo session()->corFundoPrincipal ?> !important;
    color: <?php echo session()->corTextoPrincipal ?> !important;
  }

  .card-primary:not(.card-outline)>.card-header {
    background-color: <?php echo session()->corFundoPrincipal ?> !important;
    border-color: <?php echo session()->corFundoPrincipal ?> !important;
    color: <?php echo session()->corTextoPrincipal ?> !important;
  }

  .control-sidebar {
    background-color: <?php echo session()->corFundoPrincipal ?> !important;
    border-color: <?php echo session()->corFundoPrincipal ?> !important;
    color: <?php echo session()->corTextoMenus ?> !important;
  }

  .menu-perfil {
    background-color: <?php echo session()->corFundoPrincipal ?> !important;
    border-color: <?php echo session()->corFundoPrincipal ?> !important;
    color: <?php echo session()->corTextoPrincipal ?> !important;
  }

  .menu-perfil:hover {
    opacity: .8;
    background-color: <?php echo session()->corFundoPrincipal ?> !important;
    border-color: <?php echo session()->corFundoPrincipal ?> !important;
  }


  .main-sidebar {

    /*--------------------------------------------------------------
# paleta com background invertido
--------------------------------------------------------------*/

    background-color: <?php echo session()->corBackgroundMenus ?> !important;
    border-color: <?php echo session()->corTextoMenus ?> !important;
    color: <?php echo session()->corTextoMenus ?> !important;
  }


  .text-success {
    color: <?php echo session()->corTextoPrincipal ?> !important;
  }

  .menu-personalizado {
    background-color: <?php echo session()->corFundoPrincipal ?> !important;
    color: <?php echo session()->corTextoPrincipal ?>;
  }

  .menu-personalizado:hover {
    opacity: .8;
    color: <?php echo session()->corTextoPrincipal ?> !important;
  }

  .nav-treeview>.nav-item>.nav-link {
    color: <?php echo session()->corTextoMenus ?> !important;

  }

  .nav-treeview>.nav-item>.nav-link:hover {

     font-weight: bold;
    

  }

  .nav-tabs .nav-item.show .nav-link,
  .nav-tabs .nav-link.active {

    background-color: <?php echo session()->corFundoPrincipal ?> !important;
    border-color: <?php echo session()->corTextoPrincipal ?>;
    background-clip: padding-box;
    border-radius: 0.25rem;
  }

  .nav-link.active {

    color: <?php echo session()->corTextoPrincipal ?> !important;
  }

  .table-striped tbody tr:nth-of-type(odd) {
    background-color: <?php echo session()->corLinhaTabela ?> !important;
    border-color: <?php echo session()->corLinhaTabela ?>;
    color: <?php echo session()->corTextoTabela ?> !important;

  }
</style>

<body id="sidebarClosed" class="hold-transition sidebar-mini">

