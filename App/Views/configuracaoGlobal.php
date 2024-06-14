<?php
//É NECESSÁRIO EM TODAS AS VIEWS

$codOrganizacao = session()->codOrganizacao;

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
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">


          <div class="row">


            <div class="col-md-4">
              <div class="card card-primary shadow">
                <div class="card-header">
                  <h3 class="card-title">LOGO</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
                    </button>
                  </div>
                  <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div style="height:300px;" class="card-body">

                  <div style="margin-left: 15px" class="row">

                    <div>
                      <?php
                      if (isset(session()->logo)) {
                        $url_logo = base_url() . "/imagens/organizacoes/" . session()->logo;
                      } else {
                        $url_logo = null;
                      }
                      ?>
                      <img style="margin-bottom: 30px;display: block;  margin-left: auto;  margin-right: auto;  width: 50%;" width="150px" height="auto" src="<?php echo $url_logo ?>">
                    </div>
                  </div>
                </div>
                <button style="margin-left:5px;margin-right:5px;margin-bottom:5px" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modal-logo">
                  TROCAR
                </button>
                <div class="modal fade" id="modal-logo">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header bg-primary">
                        <h4 class="modal-title">Enviar Arquivo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body  bg-light text-dark">
                        <form action="<?php echo base_url() . "/configuracaoGlobal/envia_logo/" ?> name=" ajax_form" id="ajax_form" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                          <div class="form-group">
                            <label for="formGroupExampleInput">Selecione o arquivo desejado</label>
                            <input type="file" name="file" class="form-control" id="file" style="height:45px;">
                          </div>
                          <div class="form-group">
                            <button type="submit" id="send_form" class="btn btn-xs btn-primary">Enviar</button>
                          </div>
                        </form>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>








            <div class="col-md-4">
              <div class="card card-primary shadow">
                <div class="card-header">
                  <h3 class="card-title">PLANO DE FUNDO</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
                    </button>
                  </div>
                  <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div style="height:300px;" class="card-body">

                  <div style="margin-left: 15px" class="row">

                    <div>
                      <?php
                      if (isset(session()->fundo)) {
                        $url_fundo = base_url() . "/imagens/fundo/" . session()->fundo;
                      } else {
                        $url_fundo = null;
                      }
                      ?>
                      <img style="margin-bottom: 30px;display: block;  margin-left: auto;  margin-right: auto;  width: 50%;" width="150px" height="auto" src="<?php echo $url_fundo ?>">
                    </div>
                  </div>
                </div>
                <button style="margin-left:5px;margin-right:5px;margin-bottom:5px" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modal-fundo">
                  TROCAR
                </button>
                <div class="modal fade" id="modal-fundo">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header bg-primary">
                        <h4 class="modal-title">Enviar Arquivo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body  bg-light text-dark">
                        <form action="<?php echo base_url() . "/configuracaoGlobal/envia_fundo/" ?> name=" ajax_form" id="ajax_form" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                          <div class="form-group">
                            <label for="formGroupExampleInput">Selecione o arquivo desejado</label>
                            <input type="file" name="file" class="form-control" id="file" style="height:45px;">
                          </div>
                          <div class="form-group">
                            <button type="submit" id="send_form" class="btn btn-xs btn-primary">Enviar</button>
                          </div>
                        </form>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>










            <div class="col-md-4">
              <div class="card card-primary shadow">
                <div class="card-header">
                  <h3 class="card-title">DIVERSOS</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
                    </button>
                  </div>
                  <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div style="height:300px;" class="card-body">

                  <div style="margin-left: 15px" class="row">

                    <div>

                      <div>
                        <label>Timezone:</label>
                        <span> <?php echo session()->timezone ?></span>
                      </div>

                      <div>
                        <label>Permite Autocadastro:</label>
                        <?php
                        if ($configuracaoGlobal->permiteAutocadastro == 1) {
                        ?>
                          <span>
                            Sim
                          </span>
                        <?php
                        } else {
                        ?>
                          <span>
                            Não
                          </span>
                        <?php
                        }
                        ?>


                      </div>

                      <div>
                        <label>Chave Salgada:</label>
                        <span> <?php echo $organizacao->chaveSalgada ?></span>
                      </div>

                      <div>
                        <label>Tempo de Sessão:</label>
                        <span> <?php echo $organizacao->tempoInatividade ?> Min</span>
                      </div>

                      <div>
                        <label>Forçar expiração Sessão:</label>
                        <span> <?php echo $organizacao->forcarExpiracao ?> Min</span>
                      </div>

                      <div>
                        <label>Login Admin:</label>
                        <span> <?php echo $organizacao->loginAdmin ?></span>
                      </div>

                      <div>
                        <label>Senha Admin:</label>
                        <span> *************</span>
                        <?php
                        // HASH DA SENHA PADRÃO É 8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918
                        if (hash("sha256", 'admin') == $organizacao->senhaAdmin) {
                        ?>
                          <div>
                            <img style="width:30px" src="<?php echo base_url() . '/imagens/atencao.gif' ?>">
                            'Senha padrão. É altamente recomendável a troca da senha'
                          </div>
                          <?php



                        } else {
                          if ($organizacao->senhaAdmin == NULL) {
                          ?>
                            <div>
                              <img style="width:30px" src="<?php echo base_url() . '/imagens/atencao.gif' ?>">
                              'Sem senha definida. É altamente recomendável a troca da senha'
                            </div>
                        <?php
                          }
                        }
                        ?>
                      </div>


                    </div>
                  </div>
                </div>
                <button style="margin-left:5px;margin-right:5px;margin-bottom:5px" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modal-outros">
                  TROCAR
                </button>
                <div class="modal fade" id="modal-outros">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header bg-primary">
                        <h4 class="modal-title">Configurações diversas</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body  bg-light text-dark">


                        <form action="<?php echo base_url() . "/ConfiguracaoGlobal/salvar" ?>" method="post">

                          <div class="form-group ">
                            <div style="font-weight:bold"> Timezone </div>
                            <div>
                              <?php echo listboxTimezone($this, session()->codTimezone); ?>
                              <i type="button" class="fas fa-info-circle swaltimezone"></i>
                            </div>

                            <div style="margin-top:10px" class="form-group row">
                              <div class="icheck-primary d-inline">
                                <?php
                                if ($configuracaoGlobal->permiteAutocadastro == 1) {
                                ?>
                                  <input type="checkbox" id="checkboxPrimary1" name='permiteAutocadastro' id='permiteAutocadastro' checked>
                                <?php
                                } else {
                                ?>
                                  <style>
                                    input[type=checkbox] {
                                      transform: scale(1.8);
                                    }
                                  </style>
                                  <input style="margin-left:5px;" name='permiteAutocadastro' id='permiteAutocadastro' type="checkbox" id="checkboxPrimary1">

                                <?php
                                }
                                ?>

                                <label for="checkboxPrimary1">
                                </label>
                              </div>
                              <label style="margin-left:5px;" for="checkboxPrimary3">Permite Autocadastro</label>
                              <i type="button" class="fas fa-info-circle swalAutoCadastro"></i>
                            </div>

                            <div class="form-group">
                              <label for="checkboxPrimary3">
                                Chave Salgada
                              </label>
                              <i type="button" class="fas fa-info-circle swalSenhaSlgada"></i>

                              <input style="width:252px" name='chaveSalgada' id='chaveSalgada' class="form-control" type="text" placeholder="" value="<?php echo  $organizacao->chaveSalgada ?>">

                            </div>

                            <div class="form-group">
                              <label for="checkboxPrimary3">
                                Tempo Inatividade
                              </label>
                              <i type="button" class="fas fa-info-circle swalTempoInatividade"></i>

                              <input style="width:252px" name='tempoInatividade' id='tempoInatividade' class="form-control" type="text" placeholder="" value="<?php echo  $organizacao->tempoInatividade ?>">
                            </div>
                            
                            <div class="form-group">
                              <label for="checkboxPrimary3">
                              Forçar Expiração em
                              </label>
                              <i type="button" class="fas fa-info-circle swalforcarExpiracao"></i>

                              <input style="width:252px" name='forcarExpiracao' id='forcarExpiracao' class="form-control" type="text" placeholder="" value="<?php echo  $organizacao->forcarExpiracao ?>">
                            </div>


                            <div class="form-group">
                              <label for="checkboxPrimary3">
                                Login Admin
                              </label>
                              <i type="button" class="fas fa-info-circle swalLoginAdmin"></i>

                              <input style="width:252px" name='loginAdmin' id='loginAdmin' class="form-control" type="text" placeholder="" value="<?php echo  $organizacao->loginAdmin ?>">
                            </div>



                            <div class="form-group">
                              <label for="checkboxPrimary3">
                                Senha Admin
                              </label>
                              <i type="button" class="fas fa-info-circle swalSenhaAdmin"></i>
                              <input style="width:252px" name='senhaAdmin' id='senhaAdmin' class="form-control" type="password" placeholder="" value="<?php echo  $organizacao->senhaAdmin ?>">
                            </div>
                            <input type="submit" value="SALVAR" class="btn btn-xs btn-primary">

                        </form>

                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>







          </div>


        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
</section>

<!-- /.content -->
<?php
echo view('tema/rodape');
?>