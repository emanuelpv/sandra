<?php


use App\Models\ModulosModel;


$this->ModulosModel = new ModulosModel();
$Modulos_raiz = $this->ModulosModel->pegaModulosRaiz();
$Modulos_filho = $this->ModulosModel->pegaModulosFilho();
?>
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars bg-primary" style="font-size:48px"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?php echo base_url() ?>/principal/?autorizacao=<?php echo session()->autorizacao ?>" class="nav-link">Home</a>
      </li>

    </ul>
    <?php
    if (count(session()->meusPerfis) > 1) {
    ?>
      <ul class="navbar-nav ml-auto">
        <select class="form-control" id="perfilGeral" name="codPerfil">
          <?php
          foreach (session()->meusPerfis as $perfil) {
            if (session()->perfilSessao == $perfil->codPerfil) {
              $selected = 'selected';
            } else {
              $selected = "";
            }
            echo '<option ' . $selected . ' value="' . $perfil->codPerfil . '">' . $perfil->descricao . '</option>';
          }
          ?>
        </select>
      </ul>
    <?php
    }
    if (session()->codPaciente !== NULL) {
      $camminho = base_url() . "/arquivos/imagens/pacientes/";
    } else {
      $camminho = base_url() . "/arquivos/imagens/pessoas/";
    }
    ?>
    <ul class="navbar-nav ml-auto">

      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img style="width:60px" id="fotoPerfilBarraSuperior" src="<?php if (session()->fotoPerfil !== NULL) {
                                                                      echo $camminho . session()->fotoPerfil;
                                                                    } else {
                                                                      echo $camminho . 'no_image.jpg';
                                                                    } ?>?<?php echo strtotime(date('Y-m-d H:i:s')) ?>" class="img-circle elevation-2">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo session()->nomeExibicao ?></a>

        </div>
      </div>

      <li>


      </li>
      <li class="nav-item">
        <a style="font-size: 24px;" class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"><i class="fas fa-cogs "></i></a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <a style="margin-left:15px; font-size: 30px;" class="nav-item d-sm-inline-block" href="<?php echo base_url() ?>/principal/?autorizacao=<?php echo session()->autorizacao ?>" class="nav-link">Home</a>



  <?php
  if (session()->ambienteTeste == "1") {
    echo '<div style="background:red; color:#fff;font-size: 50px;font-weight: bold;" class="row justify-content-center"><img style="height:40px;width:40px" src="' . base_url() . '/imagens/atencao.gif">AMBIENTE DE TESTE</div>';
  }
  ?>

  <!-- Main Sidebar Container -->
  <aside style="background:#343a40;height: 1200px;" class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <?php
    if (isset(session()->logo)) {
      $url_logo = base_url() . "/imagens/organizacoes/" . session()->logo;
    } else {
      $url_logo = null;
    }
    ?>


    <div style="text-align:center">
      <!-- <img style="width:100px" src="<?php echo $url_logo ?>" alt="" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
      <img style="width:100px" src="<?php echo $url_logo ?>" alt="">

    </div>

    <!-- Sidebar -->
    <div class="sidebar">



      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">



          <?php

          $meusModulosPerfil = session()->meusModulos;
          $modulosPerfil = array();
          foreach ($meusModulosPerfil as $moduloPerfil) {
            if ($moduloPerfil->listar == 1) {
              array_push($modulosPerfil, $moduloPerfil->nome);
            }
          }


          foreach ($Modulos_raiz as $modulo) {
            if (in_array($modulo->nome, $modulosPerfil) == TRUE or session()->login == 'admin') {


          ?>

              <?php

              if (session()->perfilAdmin == 1) {
              ?>
                <li class="nav-item has-treeview">

                <?php
              } else {
                ?>
                <li class="nav-item has-treeview menu-is-opening menu-open">

                <?php
              }
                ?>
                <a href="#" class="nav-link menu-personalizado active">
                  <?php
                  echo '<i class="' . $modulo->icone . ' "></i>';
                  ?>
                  <p>
                    <?php echo $modulo->nome ?>
                    <i class="fas fa-angle-left right"></i>
                  </p>
                </a>


                <?php


                foreach ($Modulos_filho as $modulo_filho) {
                  if (in_array($modulo_filho->nome, $modulosPerfil) == TRUE or session()->login == 'admin') {
                    if ($modulo->codModulo == $modulo_filho->pai) {

                      if ($modulo_filho->link !== NULL) {
                        $usarlink = $modulo_filho->link;
                      }
                ?>

                      <ul style="font-size: 12px; width: 30px; width: 210px;overflow: hidden;font-size: 1em;" class="nav nav-treeview">
                        <li class="nav-item">
                          <a href="<?php echo base_url() . "/" . $usarlink ?>/?autorizacao=<?php echo session()->autorizacao ?>" class="nav-link">
                            <i class="<?php echo $modulo_filho->icone ?>"></i>
                            <p>
                              <?php echo $modulo_filho->nome ?>
                            </p>
                          </a>
                        </li>
                      </ul>
                <?php

                    }
                  }
                }

                ?>




                </li>
            <?php
            }
          }


            ?>


        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>


  <div class="modal fade" id="modal-senhaPaciente">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">ATUALIZAÇÃO DA SENHA</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body  bg-light text-dark">
          <form id="formTrocaSenhaPaciente" method="post">

            <div class="row">
              <input type="hidden" id="<?php echo csrf_token() ?>formTrocaSenhaPaciente" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

              <input type="hidden" id="codPacienteTrocaSenha" name="codPaciente">
            </div>



            <div class="row">

              <div class="col-md-6">
                <div class="form-group">
                  <label for="senhaPaciente"> Senha: </label>
                  <input type="password" id="senhaPaciente" name="senha" class="form-control" autocomplete="off" placeholder="Senha" maxlength="100">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="confirmacaoPaciente"> Confirmação: </label>
                  <input type="password" id="confirmacaoPaciente" name="confirmacao" class="form-control" autocomplete="off" placeholder="Confirmação" maxlength="40">
                </div>
              </div>


            </div>

            <?php

            if (session()->politicaSenha == 1) {
            ?>


              <div class="row">

                <?php
                if (session()->minimoCaracteres > 0) {
                ?>
                  <div class="col-sm-12">
                    <i id="verificaMinimoCaracteresPaciente" style="color:#FF0004;" class="fas fa-times"></i> <span> Contém <?php echo session()->minimoCaracteres ?> Caracteres</span>
                  </div>
                <?php
                }
                ?>

                <?php
                if (session()->senhaNaoSimples == 1) {
                ?>
                  <div class="col-sm-12">
                    <i id="verificaSenhaNaoSimplesPaciente" style="color:#FF0004;" class="fas fa-times"></i> <span> Senha trivial</span>
                  </div>
                <?php
                }
                ?>


                <?php
                if (session()->numeros == 1) {
                ?>
                  <div class="col-sm-12">
                    <i id="verificanumerosPaciente" style="color:#FF0004;" class="fas fa-times"></i> <span>Contém número</span>
                  </div>
                <?php
                }
                ?>

                <?php
                if (session()->letras == 1) {
                ?>
                  <div class="col-sm-12">
                    <i id="verificaletrasPaciente" style="color:#FF0004;" class="fas fa-times"></i> <span>Contém letra</span>
                  </div>
                <?php
                }
                ?>


                <?php
                if (session()->maiusculo == 1) {
                ?>
                  <div class="col-sm-12">
                    <i id="verificamaiusculoPaciente" style="color:#FF0004;" class="fas fa-times"></i> <span>Contém letra Maiúscula</span>
                  </div>
                <?php
                }
                ?>


                <?php
                if (session()->caracteresEspeciais == 1) {
                ?>
                  <div class="col-sm-12">
                    <i id="verificacaracteresEspeciaisPaciente" style="color:#FF0004;" class="fas fa-times"></i> <span>Contém caracteres especiais</span>
                  </div>
                <?php
                }
                ?>

                <div class="col-sm-12">
                  <i id="pwmatchPaciente" style="color:#FF0004;" class="fas fa-times"></i> <span>Confirmação da senha</span>
                </div>
              </div>
            <?php
            }
            ?>

            <div class="form-group text-center">
              <div class="btn-group">
                <button disabled="disabled" type="submit" class="btn btn-xs btn-primary" id="SalvarTrocaSenhaPaciente">TROCAR SENHA</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>



  <div class="modal fade" id="modal-senha">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">ATUALIZAÇÃO DA SENHA</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body  bg-light text-dark">
          <form id="formTrocaSenha" method="post">

            <div class="row">
              <input type="hidden" id="<?php echo csrf_token() ?>formTrocaSenha" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

              <input type="hidden" id="codPessoaTrocaSenha" name="codPessoa">
            </div>



            <div class="row">

              <div class="col-md-6">
                <div class="form-group">
                  <label for="senha"> Senha: </label>
                  <input type="password" id="senha" name="senha" class="form-control" autocomplete="off" placeholder="Senha" maxlength="100">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="confirmacao"> Confirmação: </label>
                  <input type="password" id="confirmacao" name="confirmacao" class="form-control" autocomplete="off" placeholder="Confirmação" maxlength="40">
                </div>
              </div>


            </div>

            <?php

            if (session()->politicaSenha == 1) {
            ?>


              <div class="row">

                <?php
                if (session()->minimoCaracteres > 0) {
                ?>
                  <div class="col-sm-12">
                    <i id="verificaMinimoCaracteres" style="color:#FF0004;" class="fas fa-times"></i> <span> Contém <?php echo session()->minimoCaracteres ?> Caracteres</span>
                  </div>
                <?php
                }
                ?>

                <?php
                if (session()->senhaNaoSimples == 1) {
                ?>
                  <div class="col-sm-12">
                    <i id="verificaSenhaNaoSimples" style="color:#FF0004;" class="fas fa-times"></i> <span> Senha trivial</span>
                  </div>
                <?php
                }
                ?>


                <?php
                if (session()->numeros == 1) {
                ?>
                  <div class="col-sm-12">
                    <i id="verificanumeros" style="color:#FF0004;" class="fas fa-times"></i> <span>Contém número</span>
                  </div>
                <?php
                }
                ?>

                <?php
                if (session()->letras == 1) {
                ?>
                  <div class="col-sm-12">
                    <i id="verificaletras" style="color:#FF0004;" class="fas fa-times"></i> <span>Contém letra</span>
                  </div>
                <?php
                }
                ?>


                <?php
                if (session()->maiusculo == 1) {
                ?>
                  <div class="col-sm-12">
                    <i id="verificamaiusculo" style="color:#FF0004;" class="fas fa-times"></i> <span>Contém letra Maiúscula</span>
                  </div>
                <?php
                }
                ?>


                <?php
                if (session()->caracteresEspeciais == 1) {
                ?>
                  <div class="col-sm-12">
                    <i id="verificacaracteresEspeciais" style="color:#FF0004;" class="fas fa-times"></i> <span>Contém caracteres especiais</span>
                  </div>
                <?php
                }
                ?>

                <div class="col-sm-12">
                  <i id="pwmatch" style="color:#FF0004;" class="fas fa-times"></i> <span>Confirmação da senha</span>
                </div>
              </div>
            <?php
            }
            ?>

            <div class="form-group text-center">
              <div class="btn-group">
                <button disabled="disabled" type="submit" class="btn btn-xs btn-primary" id="SalvarTrocaSenha">TROCAR SENHA</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>


  <div class="modal fade" id="modalTrocaSenhaPessoaLogada">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">ATUALIZAÇÃO DA SENHA</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body  bg-light text-dark">
          <form id="formTrocaSenhaPessoaLogada" method="post">

            <div class="row">
              <input type="hidden" id="<?php echo csrf_token() ?>formTrocaSenhaPessoaLogada" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
            </div>



            <div class="row">

              <div class="col-md-6">
                <div class="form-group">
                  <label for="senha"> Senha: </label>
                  <input type="password" id="senhaPessoaLogada" name="senha" class="form-control" autocomplete="off" placeholder="Senha" maxlength="100">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="confirmacao"> Confirmação: </label>
                  <input type="password" id="confirmacaoPessoaLogada" name="confirmacao" class="form-control" autocomplete="off" placeholder="Confirmação" maxlength="40">
                </div>
              </div>


            </div>

            <?php

            if (session()->politicaSenha == 1) {
            ?>


              <div class="row">

                <?php
                if (session()->minimoCaracteres > 0) {
                ?>
                  <div class="col-sm-12">
                    <i id="verificaMinimoCaracteresPessoaLogada" style="color:#FF0004;" class="fas fa-times"></i> <span> Contém <?php echo session()->minimoCaracteres ?> Caracteres</span>
                  </div>
                <?php
                }
                ?>

                <?php
                if (session()->senhaNaoSimples == 1) {
                ?>
                  <div class="col-sm-12">
                    <i id="verificaSenhaNaoSimplesPessoaLogada" style="color:#FF0004;" class="fas fa-times"></i> <span> Senha trivial</span>
                  </div>
                <?php
                }
                ?>


                <?php
                if (session()->numeros == 1) {
                ?>
                  <div class="col-sm-12">
                    <i id="verificanumerosPessoaLogada" style="color:#FF0004;" class="fas fa-times"></i> <span>Contém número</span>
                  </div>
                <?php
                }
                ?>

                <?php
                if (session()->letras == 1) {
                ?>
                  <div class="col-sm-12">
                    <i id="verificaletrasPessoaLogada" style="color:#FF0004;" class="fas fa-times"></i> <span>Contém letra</span>
                  </div>
                <?php
                }
                ?>


                <?php
                if (session()->maiusculo == 1) {
                ?>
                  <div class="col-sm-12">
                    <i id="verificamaiusculoPessoaLogada" style="color:#FF0004;" class="fas fa-times"></i> <span>Contém letra Maiúscula</span>
                  </div>
                <?php
                }
                ?>


                <?php
                if (session()->caracteresEspeciais == 1) {
                ?>
                  <div class="col-sm-12">
                    <i id="verificacaracteresEspeciaisPessoaLogada" style="color:#FF0004;" class="fas fa-times"></i> <span>Contém caracteres especiais</span>
                  </div>
                <?php
                }
                ?>

                <div class="col-sm-12">
                  <i id="pwmatchPessoaLogada" style="color:#FF0004;" class="fas fa-times"></i> <span>Confirmação da senha</span>
                </div>
              </div>
            <?php
            }
            ?>

            <div class="form-group text-center">
              <div class="btn-group">
                <button disabled="disabled" type="submit" class="btn btn-xs btn-primary" id="SalvarTrocaSenhaPessoaLogada">TROCAR SENHA</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>




  <div id="editPessoaLogada" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-primary text-center p-3">
          <h4 class="modal-title text-white" id="info-header-modalLabel"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">

          <div class="row">
            <div class="col-12 col-sm-12">


              <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                  <div class="tab-pane fade show active" id="ref-tab-Pessoa-home" role="tabpanel" aria-labelledby="tab-Pessoa-home">

                    <form id="edit-form-pessoaLogada" class="pl-3 pr-3" enctype="multipart/form-data">
                      <div class="row">
                        <input type="hidden" id="<?php echo csrf_token() ?>edit-form-pessoaLogada" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                        <input type="hidden" id="codPessoa" name="codPessoa" class="form-control" placeholder="Código" maxlength="11" required>
                      </div>
                      <?php
                      echo formularioPessoaPadrao($this);
                      ?>
                      <div class="form-group text-center">
                        <div class="btn-group">
                          <button type="submit" class="btn btn-xs btn-primary" id="edit-form-pessoaLogada-btn">Salvar</button>
                          <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div id="editPacienteLogado" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-primary text-center p-3">
          <h4 id="tituloEditModal" class="modal-title text-white" id="info-header-modalLabel"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">

          <form id="editPacienteFormSelf" class="pl-3 pr-3">
            <input type="hidden" id="<?php echo csrf_token() ?>editPacienteFormSelf" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

            <input type="hidden" id="codPacienteSelf" name="codPaciente" class="form-control" placeholder="Código" maxlength="11" required="">
            <input type="hidden" id="codOrganizacaoSelf" name="codOrganizacao" value=<?php echo session()->codOrganizacao ?> class="form-control" placeholder="CodOrganizacao" maxlength="11" value="1">

            <div class="row">

              <div class="col-sm-3">
                <label for="fotoPerfil">Foto Perfil: </label>
                <img id="fotoPerfilFormularioSelf" style="width:100px">
                <div id="fotoPerfilCadastroSelf"></div>


              </div>

              <div class="col-lg-3 col-3">
                <!-- small box -->
                <div class="small-box bg-info">
                  <div class="inner">
                    <h4> Nº
                      <span id="codProntuarioInfoSelf"></span>
                    </h4>

                    <p>PRONTUÁRIO</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-bag"></i>
                  </div>
                </div>

              </div>

              <div class="col-lg-3 col-3">
                <div id="codStatusCadastroPacienteColorSelf" class="small-box">
                  <div class="inner">
                    <h4><span id="codStatusCadastroPacienteInfoSelf"></span></h4>

                    <p>STATUS</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-bag"></i>
                  </div>
                </div>

              </div>

              <div class="col-lg-3 col-3">
                <!-- small box -->
                <div class="small-box bg-warning">
                  <div class="inner">
                    <h4><span id="idadeInfoSelf"></span></h4>

                    <p>IDADE</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-bag"></i>
                  </div>
                </div>

              </div>

            </div>

            <div class="card card-secondary">
              <div class="card-header">
                <h3 class="card-title">CONTATO</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">

                <div class="col-12 col-sm-12">
                  <div class="card card-primary card-tabs">
                    <div class="card-header p-0 pt-1">
                      <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active" id="edcustom-tabs-one-home-Self-tab" data-toggle="pill" href="#edcustom-tabs-one-home-Self" role="tab" aria-controls="edcustom-tabs-one-home-Self" aria-selected="true">Contato Principal</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="custom-tabs-one-profile-Self-tab" data-toggle="pill" href="#custom-tabs-one-profile-Self" role="tab" aria-controls="custom-tabs-one-profile-Self" aria-selected="false">Outros Contatos</a>
                        </li>
                      </ul>
                    </div>
                    <div class="card-body">
                      <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="tab-pane fade show active" id="edcustom-tabs-one-home-Self" role="tabpanel" aria-labelledby="edcustom-tabs-one-home-Self-tab">
                          <div class="row">
                            <div class="col-sm-4">
                              <label for="emailPessoal">Email Pessoal: <span class="text-danger">*</span></label>
                              <div class="input-group mb-3">
                                <input type="text" autocomplete="off" id="emailPessoalSelf" name="emailPessoal" class="form-control" placeholder="Email Pessoal" maxlength="40">
                                <div class="input-group-append">
                                  <div class="input-group-text">
                                    <span class="fas fa-at"></span>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-2">
                              <label for="celular">Celular: <span class="text-danger">*</span></label>
                              <div class="input-group mb-3">
                                <input required="" type="text" autocomplete="off" id="celularSelf" name="celular" class="form-control" placeholder="Celular" maxlength="16">
                                <div class="input-group-append">
                                  <div class="input-group-text">
                                    <span class="fas fa-mobile-alt"></span>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <label for="endereco">Endereço: <span class="text-danger">*</span></label>
                              <div class="input-group mb-3">
                                <input required="" type="text" autocomplete="off" id="enderecoSelf" name="endereco" class="form-control" placeholder="Endereço" maxlength="200">
                                <div class="input-group-append">
                                  <div class="input-group-text">
                                    <span class="fas fa-map-marker-alt"></span>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="row">

                            <div class="col-sm-3">
                              <label for="cep">CEP: <span class="text-danger">*</span></label>
                              <div class="input-group mb-3">
                                <input required="" type="text" autocomplete="off" id="cepSelf" name="cep" class="form-control" placeholder="CEP" maxlength="9">
                                <div class="input-group-append">
                                  <div class="input-group-text">
                                    <span class="fas fa-map-marker-alt"></span>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                        </div>
                        <div class="tab-pane fade" id="custom-tabs-one-profile-Self" role="tabpanel" aria-labelledby="custom-tabs-one-profile-Self-tab">
                          <div class="col-sm-4">
                            <button type="button" class="btn btn-block btn-primary  btn-xs" onclick="modalOutrosContatosPaciente()" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
                          </div>

                          <table id="data_outrosContatosSelf" class="table table-striped table-hover table-sm">
                            <thead>
                              <tr>
                                <th>Código</th>
                                <th>Tipo</th>
                                <th>Nome Contato</th>
                                <th>Parentesco</th>
                                <th>Nº Contato</th>
                                <th>Observações</th>
                                <th style="text-align:center">Ações</th>
                              </tr>
                            </thead>
                          </table>

                        </div>

                      </div>
                    </div>
                    <!-- /.card -->
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group text-center">
              <div class="btn-group">
                <button type="button" class="btn btn-block btn-primary  btn-xs" onclick="salvarPacienteSelf()" title="Salvar">Salvar</button>
                <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
              </div>
            </div>
          </form>

        </div>


        <div class="modal-footer">

          <div>Última Alteração:
            <span id="ultimaAuteracaoSelf"></span>
          </div>
        </div>



      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>






  <div style="width:400px" id="alteracaoOutrosContatosModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-primary text-center p-3">
          <h4 class="modal-title text-white" id="info-header-modalLabel">Alteração de contato</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="alteracaoContatoForm" class="pl-3 pr-3">
            <input type="hidden" id="<?php echo csrf_token() ?>alteracaoContatoForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

            <input type="hidden" id="codOutroContatoAlteracao" name="codOutroContato" class="form-control" placeholder="codOutroContato" maxlength="11" required="">

            <div class="row">
              <div class="col-sm-12">
                <label for="codTipoContatoAlteracao">Tipo Contato: <span class="text-danger">*</span> </label>
                <div class="input-group mb-3">
                  <select style="width:86%" required="" id="codTipoContatoAlteracao" name="codTipoContato" class="form-control" aria-hidden="true">
                    <option value=""></option>
                    <option value="1">PESSOAL</option>
                    <option value="2">TRABALHO</option>
                    <option value="3">FAMILIAR</option>
                    <option value="4">MÉDICO FAM</option>
                    <option value="5">AMIGO(A)</option>
                    <option value="6">OUTRO(A)</option>
                    <option value="7">FUNCIONAL</option>
                    <option value="8">RESIDENCIAL</option>

                  </select>
                </div>
              </div>

            </div>

            <div class="row">

              <div class="col-sm-12">
                <label for="codParentescoAlteracao">Parentesco:</label>
                <div class="input-group mb-3">
                  <select style="width:86%" id="codParentescoAlteracao" name="codParentesco" class="form-control" tabindex="42" aria-hidden="true">
                    <option value="0">NÃO SE APLICA</option>
                    <option value="1">PAI</option>
                    <option value="2">MÃE</option>
                    <option value="3">ESPOSO(A)</option>
                    <option value="4">FILHO(A)</option>
                    <option value="5">IRMÃO(Ã)</option>
                    <option value="6">OUTRO</option>

                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-12">
                <label for="nomeContatoAlteracao">Nome do Contato: <span class="text-danger">*</span></label>
                <div class="input-group mb-3">
                  <input tabindex="43" required="" type="text" autocomplete="off" id="nomeContatoAlteracao" name="nomeContato" class="form-control" placeholder="Nome do Contato" maxlength="30">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-user"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-12">
                <label for="numeroContatoAlteracao">Número do Contato: <span class="text-danger">*</span></label>
                <div class="input-group mb-3">
                  <input tabindex="44" required="" type="text" autocomplete="off" id="numeroContatoAlteracao" name="numeroContato" class="form-control" placeholder="Número do Contato" maxlength="20">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-mobile-alt"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">


              <div class="row">
                <div class="col-sm-12">
                  <label for="observacoesAlteracao">Observações: <span class="text-danger">*</span></label>
                  <div class="input-group mb-3">
                    <textarea tabindex="45" id="observacoesAlteracao" name="observacoes" rows="4" cols="40" maxlength="100"></textarea>

                  </div>
                </div>
              </div>

            </div>
            <button type="button" class="btn btn-block btn-primary  btn-xs" onclick="alteraContatoSelf()" title="Incluir">Salvar</button>
          </form>

        </div>

      </div>
    </div>
  </div>




  <div style="width:400px" id="editOutrosContatosModalPaciente" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-primary text-center p-3">
          <h4 class="modal-title text-white" id="info-header-modalLabel">Incluir contato</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="editIncluiContatoFormSelf" class="pl-3 pr-3">
            <input type="hidden" id="<?php echo csrf_token() ?>editIncluiContatoFormSelf" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

            <input type="hidden" id="codPacienteOutrosContatosSelf" name="codPaciente" class="form-control" placeholder="Código" maxlength="11" required="">

            <div class="row">
              <div class="col-sm-12">
                <label for="codTipoContato">Tipo Contato: <span class="text-danger">*</span> </label>
                <div class="input-group mb-3">
                  <select style="width:86%" required="" id="codTipoContatoSelf" name="codTipoContato" class="form-control select2-hidden-accessible" data-select2-id="forcaSelf" tabindex="-1" aria-hidden="true">
                    <option value=""></option>

                  </select>
                </div>
              </div>

            </div>


            <div class="row">

              <div class="col-sm-12">
                <label for="codParentesco">Parentesco:</label>
                <div class="input-group mb-3">
                  <select style="width:86%" id="codParentescoSelf" name="codParentesco" class="form-control select2-hidden-accessible" data-select2-id="codParentescoSelf" tabindex="-1" aria-hidden="true">
                    <option value="0"></option>

                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-12">
                <label for="nomeContato">Nome do Contato: <span class="text-danger">*</span></label>
                <div class="input-group mb-3">
                  <input required="" type="text" autocomplete="off" id="nomeContatoSelf" name="nomeContato" class="form-control" placeholder="Nome do Contato" maxlength="30">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-user"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-12">
                <label for="numeroContato">Número do Contato: <span class="text-danger">*</span></label>
                <div class="input-group mb-3">
                  <input required="" type="text" autocomplete="off" id="numeroContatoSelf" name="numeroContato" class="form-control" placeholder="Número do Contato" maxlength="20">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-mobile-alt"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">


              <div class="row">
                <div class="col-sm-12">
                  <label for="observacoesSelf">Observações: <span class="text-danger">*</span></label>
                  <div class="input-group mb-3">
                    <textarea id="observacoesSelf" name="observacoes" rows="4" cols="50"></textarea>

                  </div>
                </div>
              </div>

            </div>
            <button type="button" class="btn btn-block btn-primary  btn-xs" onclick="incluiContatoPaciente()" title="Incluir"> <i class="fa fa-plus"></i> Adicionar</button>
          </form>

        </div>

      </div>
    </div>
  </div>



  <div id="pesquisaQualidadeModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-primary text-center p-3">
          <h4 class="modal-title text-white" id="info-header-modalLabel">VOCÊ DESEJA PARTICIPAR DA PESQUISA DE QUALIDADE DO SISTEMA?</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="<?php echo csrf_token() ?>pesquisaQualidadeModal" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

          <div id='dadosQuestionario'>
          </div>

          <div style="margin-top:60px" class="row justify-content-end">

            <div class="col-md-2">
              <div class="form-group">
                <button type="button" class="btn btn-block btn-danger" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="sairPesquisa()" title="Adicionar">Sair
                </button>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">

                <div class="icheck-primary d-inline">
                  <style>
                    input[type=checkbox] {
                      transform: scale(1.8);
                    }
                  </style>
                  <input style="margin-left:5px;" id='naoPerguntarNovamentePesquisa' name='naoPerguntarNovamentePesquisa' type="checkbox" id="checkboxstatus">


                </div>
                <label style="margin-left:5px;"> Não perguntar novamente: </label>
              </div>
            </div>
          </div>



        </div>

      </div>
    </div>
  </div>

  <div id="termoPesquisaModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-primary text-center p-3">
          <h4 class="modal-title text-white" id="info-header-modalLabel">Termo de aceite</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">

            <div class="col-md-12">
              <div class="form-group">

                <div class="icheck-primary d-inline center">
                  <style>
                    input[type=checkbox] {
                      transform: scale(1.8);
                      margin-left: 10px
                    }
                  </style>
                  <input id="aceitoOsTermos" name="aceitoOsTermos" type="checkbox"> <span style="margin-left:10px">Aceito os termos</span>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2">
              <button type="button" class="btn btn-block btn-success" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="aceitar()" title="Adicionar">continuar
              </button>
            </div>
          </div>
        </div>
        <div style="margin-left:2cm;margin-right:2cm" id='dadosTermoPesquisa' class="row text-justify"></div>
      </div>

    </div>
  </div>

  <div id="perguntasModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-primary text-center p-3">
          <h4 class="modal-title text-white" id="info-header-modalLabel">Perguntas</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">

          </div>
          <div id='dadosPerguntasPesquisa' class="row"></div>
        </div>

      </div>
    </div>


  </div>