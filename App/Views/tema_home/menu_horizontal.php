  
  
  <!-- ======= Header ======= -->
    <header id="header" class="fixed-top header_hero">
        <div class="container-fluid d-flex">

            <div class=" mr-auto">
                <a href="#"><img style="width:100px !important ;height:auto !important" src="<?php echo base_url() . '/imagens/organizacoes/' . session()->logo; ?>" alt="" class="img-fluid"></a>
            </div>
            
            <nav class="d-lg-none .d-xl-block">
                <a style="width:100px; margin-right: 40px;" href="#login" class="btn btn-block btn-info" title="Login"> <i class="fa fa-arrow-right"></i>Acesso</a>
            </nav>

            <nav  class="nav-menu d-none d-lg-block">
                <ul>
                    <li class="border-left"><a href="<?php echo base_url()?>#header">Home</a></li>
                    <li class="border-left"><a href="<?php echo base_url()?>#sobre">Conheça</a></li>
                    <li class="border-left"><a href="<?php echo base_url()?>#services">Serviços</a></li>
                    <li class="border-left"><a href="#header" onclick="profissionaisSaude()">Profissionais de Saúde</a></li>
                    <li class="border-left"><a href="<?php echo base_url('politicaPrivacidade') ?>" target="_blank">Política de Privacidade</a></li>
                    <li class="border-left"><a href="#header" onclick="termosDeUso()">Termos de Uso</a></li>
                    <li class="border-left"><a href="<?php echo base_url()?>#contact">Contate-nos</a></li>
                    <li class="border-left"><a href="<?php echo base_url()?>#social">Redes sociais</a></li>
                </ul>
            </nav>
            <!-- .nav-menu -->
            
            <!-- .nav-menu -->
            <div class="d-none d-lg-block">
                <a style="width:100px" href="#login" class="btn btn-block btn-info" title="Login"> <i class="fa fa-arrow-right"></i>Acesso</a>
            </div>

        </div>

    </header>