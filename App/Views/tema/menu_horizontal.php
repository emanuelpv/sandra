<?php 
//É NECESSÁRIO EM TODAS AS VIEWS

?>
<style>
  #content {
    
    width: 100%;
    position: relative;
  }

  #bg {
    
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background: url('<?php echo base_url('imagens/fundo/' . session()->fundo) ?>') center center;
    opacity: .2;
    width: 100%;
    height:80vh;
  }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section id="content" class="content-header">
      <div id='bg' class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active"></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
      
<div id="botaoPesquisa"></div>
    </section>