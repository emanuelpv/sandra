<?php
//É NECESSÁRIO EM TODAS AS VIEWS

$codOrganizacao = session()->codOrganizacao;
$codPessoa = session()->codPessoa;



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






          <div id="modalTestarSMTP" class="modal fade" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl">
              <div class="modal-content">
                <div class="modal-header bg-primary text-center p-3">
                  <h4 class="modal-title text-white" id="info-header-modalLabel">Teste do Serviço SMTP</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form id="formTestarSMTP" class="pl-3 pr-3">

                    <div class="row">

                      <div class="col-md-5">
                        <div class="form-group">
                          <label for="conector"> Destinatário: <span class="text-danger">*</span> </label>
                          <input type="text" id="destinatarioTeste" name="destinatario" autocomplete="off" class="form-control" placeholder="E-mail do destinatário" maxlength="100" required>
                        </div>
                      </div>
                      <input type="hidden" id="<?php echo csrf_token() ?>formTestarSMTP" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                      <input type="hidden" id="ipServidorSMTPTeste" name="ipServidorSMTP" class="form-control" placeholder="IP Servidor" maxlength="100" required>
                      <input type="hidden" id="portaSMTPTeste" name="portaSMTP" class="form-control" placeholder="Porta SMTP" maxlength="10" required>
                      <input type="hidden" id="loginSMTPTeste" name="loginSMTP" class="form-control" placeholder="Login" maxlength="100" required>
                      <input type="hidden" id="senhaSMTPTeste" name="senhaSMTP" class="form-control" placeholder="Login" maxlength="100" required>
                      <input type="hidden" id="emailRetornoTeste" name="emailRetorno" class="form-control" placeholder="Email Retorno" maxlength="100" required>
                      <input type="hidden" id="protocoloSMTPTeste" name="protocoloSMTP" class="form-control" placeholder="Email Retorno" maxlength="100" required>
                      <input type="hidden" id="statusSMTPTeste" name="statusSMTP" class="form-control" placeholder="Email Retorno" maxlength="100" required>

                    </div>


                    <div class="form-group text-center">
                      <div class="btn-group">
                        <button type="button" onclick="testarSMTP()" class="btn btn-xs btn-success"> Enviar teste</button>
                        <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div>



          <div style="margin-top:10px;margin-left:10px" class="row">


            <div class="col-md-6">
              <div class="card card-primary collapsed-card">

                <div class="card-header">
                  <a data-card-widget="collapse">
                    <h3 class="card-title">Integração SMTP</h3>

                    <div class="card-tools">
                      <button type="button" class="btn btn-tool"><i class="fas fa-angle-down"></i>
                      </button>

                    </div>
                  </a>
                  <!-- /.card-tools -->
                </div>
                <div class="card-body" style="display: none;">


                  <div class="row">
                    <div class="col-md-8 mt-2">
                      <h3 style="font-size:30px;font-weight: bold;" class="card-title">Serviço SMTP</h3>

                    </div>
                    <div class="col-md-4">
                      <button type="button" class="btn btn-block btn-primary btn-xs" onclick="add_smtp()" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <table id="data_tableSMTP" class="table table-bordered table-striped table-hover">
                        <thead>
                          <tr>
                            <th>Código</th>
                            <th>Nome Servidor</th>
                            <th>Servidor</th>
                            <th>Porta SMTP</th>
                            <th></th>
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </div>


                </div>
              </div>
            </div>



            <div class="col-md-6">
              <div class="card card-primary collapsed-card">

                <div class="card-header">
                  <a data-card-widget="collapse">
                    <h3 class="card-title">Integração LDAP</h3>

                    <div class="card-tools">
                      <button type="button" class="btn btn-tool"><i class="fas fa-angle-down"></i>
                      </button>
                    </div>
                  </a>
                  <!-- /.card-tools -->
                </div>

                <div class="card-body" style="display: none;">

                  <div class="row">
                    <div class="col-md-8 mt-2">
                      <h3 style="font-size:30px;font-weight: bold;" class="card-title">Serviço ldap</h3>

                    </div>
                    <div class="col-md-4">
                      <button type="button" class="btn btn-block btn-primary btn-xs" onclick="addServicoLDAPModel()" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
                    </div>
                  </div>
                  <table id="data_tableServicoLDAPModel" class="table table-bordered table-striped table-hover">
                    <thead>
                      <tr>
                        <th>Código</th>
                        <th>Nome Servidor</th>
                        <th>Tipo</th>
                        <th>Servidor</th>

                        <th></th>
                      </tr>
                    </thead>
                  </table>


                </div>
              </div>
            </div>




          </div>











          <div style="margin-top:10px;margin-left:10px" class="row">


            <div class="col-md-6">
              <div class="card card-primary collapsed-card">
                <div class="card-header">
                  <h3 class="card-title">Integração SMS</h3>
                  <a data-card-widget="collapse">
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool"><i class="fas fa-angle-down"></i>
                      </button>
                    </div>
                  </a>
                  <!-- /.card-tools -->
                </div>
                <div class="card-body" style="display: none;">

                  <section class="content">
                    <div class="row">
                      <div class="col-12">
                        <div class="card">
                          <div class="card-header">

                            <div class="row">
                              <div class="col-md-8 mt-2">
                                <h3 style="font-size:30px;font-weight: bold;" class="card-title">Serviços de SMS</h3>
                              </div>
                              <div class="col-md-4">
                                <button type="button" class="btn btn-block btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addservicosSMS()" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
                              </div>
                            </div>
                          </div>
                          <!-- /.card-header -->
                          <div class="card-body">
                            <table id="data_tableservicosSMS" class="table table-striped table-hover table-sm">
                              <thead>
                                <tr>
                                  <th>Códico</th>
                                  <th>Provedor</th>
                                  <th>StatusSMS</th>

                                  <th></th>
                                </tr>
                              </thead>
                            </table>
                          </div>
                          <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /.row -->
                  </section>
                </div>
              </div>
            </div>




            <div class="col-md-6">
              <div class="card card-primary collapsed-card">
                <div class="card-header">
                  <h3 class="card-title">Integração SPED</h3>
                  <a data-card-widget="collapse">
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool"><i class="fas fa-angle-down"></i>
                      </button>
                    </div>
                  </a>
                  <!-- /.card-tools -->
                </div>
                <div class="card-body" style="display: none;">


                  <form id="formSPED" class="pl-3 pr-3">
                    <div class="row">
                      <input type="hidden" id="<?php echo csrf_token() ?>formSPED" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                      <input type="hidden" id="codOrganizacao" name="codOrganizacao" class="form-control" placeholder="Código" maxlength="11" required>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="servidorSpedDB"> Servidor: <span class="text-danger">*</span> </label>
                          <input type="text" id="servidorSpedDB" name="servidorSpedDB" class="form-control" placeholder="Servidor SPED" maxlength="60" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="SpedDB"> Nome Banco: <span class="text-danger">*</span> </label>
                          <input type="text" id="SpedDB" name="SpedDB" class="form-control" placeholder="Banco de dados" maxlength="100" required>
                        </div>
                      </div>
                    </div>
                    <div class="row">

                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="administradorSpedDB"> Login: <span class="text-danger">*</span> </label>
                          <input type="text" id="administradorSpedDB" name="administradorSpedDB" class="form-control" placeholder="Administrador" maxlength="10" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="senhaadministradorSpedDB"> Senha: <span class="text-danger">*</span> </label>
                          <input type="text" id="senhaadministradorSpedDB" name="senhaadministradorSpedDB" class="form-control" placeholder="Senha" maxlength="100" required>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="checkboxSPED">Ativo: </label>
                          <div class="icheck-primary d-inline">
                            <style>
                              input[type=checkbox] {
                                transform: scale(1.8);
                              }
                            </style>
                            <input style="margin-left:5px;" name="checkboxSPED" type="checkbox" id="checkboxSPEDEdit">


                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group text-center">
                      <div class="btn-group">
                        <button type="button" class="btn btn-xs btn-primary" onclick="salvarSPED()" id="SPED-btn">Salvar</button>
                      </div>
                    </div>
                  </form>

                </div>
              </div>
            </div>


          </div>

          <div style="margin-top:10px;margin-left:10px" class="row">


            <div class="col-md-6">
              <div class="card card-primary collapsed-card">
                <div class="card-header">
                  <h3 class="card-title">Integração TELEGRAM</h3>
                  <a data-card-widget="collapse">
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool"><i class="fas fa-angle-down"></i>
                      </button>
                    </div>
                  </a>
                  <!-- /.card-tools -->
                </div>
                <div class="card-body" style="display: none;">




                </div>
              </div>
            </div>


            <div class="col-md-6">
              <div class="card card-primary collapsed-card">
                <div class="card-header">
                  <a data-card-widget="collapse">
                    <h3 class="card-title">Protocolos</h3>

                    <div class="card-tools">
                      <button type="button" class="btn btn-tool"><i class="fas fa-angle-down"></i>
                      </button>
                    </div>
                  </a>
                  <!-- /.card-tools -->
                </div>
                <div class="card-body" style="display: none;">
                  <!-- Main content -->
                  <section class="content">
                    <div class="row">
                      <div class="col-12">
                        <div class="card">
                          <div class="card-header">
                            <div class="row">
                              <div class="col-md-8 mt-2">
                                <h3 style="font-size:30px;font-weight: bold;" class="card-title">Protocolos de Rede</h3>
                              </div>
                              <div class="col-md-4">
                                <button type="button" class="btn btn-block btn-primary  btn-xs" onclick="addProtocolosRedeModel()" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
                              </div>
                            </div>
                          </div>
                          <!-- /.card-header -->
                          <div class="card-body">
                            <table id="data_tableProtocolosRedeModel" class="table table-bordered table-striped table-hover">
                              <thead>
                                <tr>
                                  <th>Código</th>
                                  <th>Nome</th>
                                  <th>Porta Padrão</th>

                                  <th></th>
                                </tr>
                              </thead>
                            </table>
                          </div>
                          <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /.row -->
                  </section>
                  <!-- Add modal content -->
                  <div id="add-modalProtocolosRedeModel" class="modal fade" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                      <div class="modal-content">
                        <div class="modal-header bg-primary text-center p-3">
                          <h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Protocolos de Rede</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <form id="add-formProtocolosRedeModel" class="pl-3 pr-3">
                            <div class="row">
                              <input type="hidden" id="<?php echo csrf_token() ?>add-formProtocolosRedeModel" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                              <input type="hidden" id="codProtocoloRede" name="codProtocoloRede" class="form-control" placeholder="Código" maxlength="11" required>
                            </div>
                            <div class="row">
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="nomeProtocoloRede"> Nome: <span class="text-danger">*</span> </label>
                                  <input type="text" id="nomeProtocoloRede" name="nomeProtocoloRede" class="form-control" placeholder="Nome" maxlength="40" required>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="conector"> Conector: <span class="text-danger">*</span> </label>
                                  <input type="text" id="conector" name="conector" class="form-control" placeholder="Conector" maxlength="40" required>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="portaPadrao"> Porta Padrão: <span class="text-danger">*</span> </label>
                                  <input type="number" id="portaPadrao" name="portaPadrao" class="form-control" placeholder="Porta Padrão" maxlength="11" number="true" required>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                            </div>

                            <div class="form-group text-center">
                              <div class="btn-group">
                                <button type="submit" class="btn btn-xs btn-primary" id="add-formProtocolosRedeModel-btn">Adicionar</button>
                                <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                  </div><!-- /.modal -->





                  <!-- Add modal content -->
                  <div id="edit-modalProtocolosRedeModel" class="modal fade" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                      <div class="modal-content">
                        <div class="modal-header bg-primary text-center p-3">
                          <h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Protocolos de Rede</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <form id="edit-form" class="pl-3 pr-3">
                            <div class="row">
                              <input type="hidden" id="<?php echo csrf_token() ?>edit-form" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                              <input type="hidden" id="codProtocoloRede" name="codProtocoloRede" class="form-control" placeholder="Código" maxlength="11" required>
                            </div>
                            <div class="row">
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="nomeProtocoloRede"> Nome: <span class="text-danger">*</span> </label>
                                  <input type="text" id="nomeProtocoloRede" name="nomeProtocoloRede" class="form-control" placeholder="Nome" maxlength="40" required>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="conector"> Conector: <span class="text-danger">*</span> </label>
                                  <input type="text" id="conector" name="conector" class="form-control" placeholder="Conector" maxlength="40" required>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="portaPadrao"> Porta Padrão: <span class="text-danger">*</span> </label>
                                  <input type="number" i d="portaPadrao" name="portaPadrao" class="form-control" placeholder="Porta Padrão" maxlength="11" number="true" required>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                            </div>

                            <div class="form-group text-center">
                              <div class="btn-group">
                                <button type="submit" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="edit-form-btn">Salvar</button>
                                <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
                              </div>
                            </div>
                          </form>

                        </div>
                      </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                  </div><!-- /.modal -->
                  <!-- /.content -->


                </div>
              </div>
            </div>

          </div>



          <div id="add-modalservicosSMS" class="modal fade" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl">
              <div class="modal-content">
                <div class="modal-header bg-primary text-center p-3">
                  <h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Serviços de SMS</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form id="add-formservicosSMS" class="pl-3 pr-3">
                    <div class="row">
                      <input type="hidden" id="<?php echo csrf_token() ?>add-formservicosSMS" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                      <input type="hidden" id="codServicoSMS" name="codServicoSMS" class="form-control" placeholder="Códico" maxlength="11" required>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="codProvedor"> CodProvedor: <span class="text-danger">*</span> </label>
                          <select id="codProvedor" name="codProvedor" class="custom-select" required>
                            <option value=""></option>
                            <option value="1">ZENVIA</option>
                            <option value="2">MEX</option>
                            <option value="3">TWILIO</option>
                          </select>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="creditos"> Creditos: </label>
                          <input readonly type="text" id="creditos" name="creditos" class="form-control" placeholder="Créditos" maxlength="100">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="conta"> Conta: </label>
                          <input type="text" id="conta" name="conta" class="form-control" placeholder="Conta" maxlength="100">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="token"> Token: </label>
                          <input type="text" id="token" name="token" class="form-control" placeholder="Token" maxlength="100">
                        </div>
                      </div>

                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="statusSMS"> StatusSMS: <span class="text-danger">*</span> </label>
                          <select id="statusSMS" name="statusSMS" class="custom-select" required>
                            <option value=""></option>
                            <option value="1">Ativado</option>
                            <option value="0">Desativado</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="form-group text-center">
                      <div class="btn-group">
                        <button type="submit" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="add-formservicosSMS-btn">Adicionar</button>
                        <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div>
          <div id="edit-modalservicosSMS" class="modal fade" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl">
              <div class="modal-content">
                <div class="modal-header bg-primary text-center p-3">
                  <h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Serviços de SMS</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form id="edit-formservicosSMS" class="pl-3 pr-3">
                    <div class="row">
                      <input type="hidden" id="<?php echo csrf_token() ?>edit-formservicosSMS" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                      <input type="hidden" id="codServicoSMS" name="codServicoSMS" class="form-control" placeholder="Códico" maxlength="11" required>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="codProvedor"> CodProvedor: <span class="text-danger">*</span> </label>
                          <select id="codProvedor" name="codProvedor" class="custom-select" required>
                            <option value=""></option>
                            <option value="1">ZENVIA</option>
                            <option value="2">MEX</option>
                            <option value="3">TWILIO</option>
                          </select>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="creditos"> Creditos: </label>
                          <input readonly type="text" id="creditos" name="creditos" class="form-control" placeholder="Créditos" maxlength="100">
                        </div>
                      </div>


                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="conta"> Conta: </label>
                          <input type="text" id="conta" name="conta" class="form-control" placeholder="Conta" maxlength="100">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="token"> Token: </label>
                          <input type="text" id="token" name="token" class="form-control" placeholder="Token" maxlength="100">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="statusSMS"> StatusSMS: <span class="text-danger">*</span> </label>
                          <select id="statusSMS" name="statusSMS" class="custom-select" required>
                            <option value=""></option>
                            <option value="1">Ativado</option>
                            <option value="0">Desativado</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="form-group text-center">
                      <div class="btn-group">
                        <button type="submit" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="edit-formservicosSMS-btn">Salvar</button>
                        <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
                      </div>
                    </div>
                  </form>

                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div>


          <div id="add-modalSMTP" class="modal fade" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl">
              <div class="modal-content">
                <div class="modal-header bg-primary text-center p-3">
                  <h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Serviço SMTP</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form id="add-formSMTP" class="pl-3 pr-3">
                    <div class="row">
                      <input type="hidden" id="<?php echo csrf_token() ?>add-formSMTP" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                      <input type="hidden" id="codServidorSMTP" name="codServidorSMTP" class="form-control" placeholder="Código" maxlength="11" required>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="descricaoServidorSMTP"> Nome Servidor: <span class="text-danger">*</span> </label>
                          <input type="text" id="descricaoServidorSMTP" name="descricaoServidorSMTP" class="form-control" placeholder="Nome Servidor" maxlength="60" required>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="ipServidorSMTP"> Servidor: <span class="text-danger">*</span> </label>
                          <input type="text" id="ipServidorSMTP" name="ipServidorSMTP" class="form-control" placeholder="Servidor" maxlength="100" required>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="portaSMTP"> Porta SMTP: <span class="text-danger">*</span> </label>
                          <input type="text" id="portaSMTP" name="portaSMTP" class="form-control" placeholder="Porta SMTP" maxlength="10" required>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="loginSMTP"> Login: <span class="text-danger">*</span> </label>
                          <input type="text" id="loginSMTP" name="loginSMTP" class="form-control" placeholder="Login" maxlength="100" required>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="senhaSMTP"> Senha: <span class="text-danger">*</span> </label>
                          <input type="text" id="senhaSMTP" name="senhaSMTP" class="form-control" placeholder="Senha" maxlength="64" required>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="emailRetorno"> Email Retorno: <span class="text-danger">*</span> </label>
                          <input type="text" id="emailRetorno" name="emailRetorno" class="form-control" placeholder="Email Retorno" maxlength="100" required>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="protocoloSMTP"> Protocolo: <span class="text-danger">*</span> </label>
                          <select id="protocoloSMTP" name="protocoloSMTP" class="custom-select" required>
                            <option value="0">PADRÃO</option>
                            <option value="1">SSL</option>
                            <option value="2">TLS</option>
                            <option value="3">STARTTLS</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="statusSMTP"> Status SMTP: <span class="text-danger">*</span> </label>
                          <select id="statusSMTP" name="statusSMTP" class="custom-select" required>
                            <option value=""></option>
                            <option value="1">Ativado</option>
                            <option value="2">Desativado</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="form-group text-center">
                      <div class="btn-group">
                        <button type="submit" class="btn btn-xs btn-primary" id="add-formSMTP-btn">Adicionar</button>
                        <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div>

          <!-- edit modal content -->
          <div id="edit-modalSMTP" class="modal fade" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl">
              <div class="modal-content">
                <div class="modal-header bg-primary text-center p-3">
                  <h4 class="modal-title text-white" id="info-header-modalLabel">Atualização de Serviço SMTP</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
                <div class="modal-body">

                  <form id="edit-formSMTP" class="pl-3 pr-3">
                    <div class="row">
                      <input type="hidden" id="<?php echo csrf_token() ?>edit-formSMTP" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                      <input type="hidden" id="codServidorSMTP" name="codServidorSMTP" class="form-control" placeholder="Código" maxlength="11" required>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="descricaoServidorSMTP"> Nome Servidor: <span class="text-danger">*</span> </label>
                          <input type="text" id="descricaoServidorSMTP" name="descricaoServidorSMTP" class="form-control" placeholder="Nome Servidor" maxlength="60" required>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="ipServidorSMTP"> Servidor: <span class="text-danger">*</span> </label>
                          <input type="text" id="ipServidorSMTP" name="ipServidorSMTP" class="form-control" placeholder="Servidor" maxlength="100" required>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="portaSMTP"> Porta SMTP: <span class="text-danger">*</span> </label>
                          <input type="text" id="portaSMTP" name="portaSMTP" class="form-control" placeholder="Porta SMTP" maxlength="10" required>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="loginSMTP"> Login: <span class="text-danger">*</span> </label>
                          <input type="text" id="loginSMTP" name="loginSMTP" class="form-control" placeholder="Login" maxlength="100" required>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="senhaSMTP"> Senha: <span class="text-danger">*</span> </label>
                          <input type="password" id="senhaSMTP" name="senhaSMTP" class="form-control" placeholder="Senha" maxlength="64" required>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="emailRetorno"> Email Retorno: <span class="text-danger">*</span> </label>
                          <input type="text" id="emailRetorno" name="emailRetorno" class="form-control" placeholder="Email Retorno" maxlength="100" required>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="protocoloSMTP"> Protocolo: <span class="text-danger">*</span> </label>
                          <select id="protocoloSMTP" name="protocoloSMTP" class="custom-select" required>
                            <option value="0">PADRÃO</option>
                            <option value="1">SSL</option>
                            <option value="2">TLS</option>
                            <option value="3">STARTTLS</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="statusSMTP"> Status SMTP: <span class="text-danger">*</span> </label>
                          <select id="statusSMTP" name="statusSMTP" class="custom-select" required>
                            <option value=""></option>
                            <option value="1">Ativado</option>
                            <option value="2">Desativado</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="form-group text-center">
                      <div class="btn-group">
                        <button type="submit" class="btn btn-xs btn-primary" id="edit-formSMTP-btn">Salvar</button>
                        <button type="button" onclick="showTestarSMTP()" class="btn btn-xs btn-success"> Testar</button>
                        <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
                      </div>
                    </div>
                  </form>

                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->




          <div id="add-modalServicoLDAPModel" class="modal fade" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl">
              <div class="modal-content">
                <div class="modal-header bg-primary text-center p-3">
                  <h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Serviço LDAP</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form id="add-formServicoLDAPModel" class="pl-3 pr-3">
                    <div class="row">
                      <input type="hidden" id="<?php echo csrf_token() ?>add-formServicoLDAPModel" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                      <input type="hidden" id="codServidorLDAP" name="codServidorLDAP" class="form-control" placeholder="Código" maxlength="11" required>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="descricaoServidorLDAP"> Nome Servidor: <span class="text-danger">*</span> </label>
                          <input type="text" id="descricaoServidorLDAP" name="descricaoServidorLDAP" class="form-control" placeholder="Nome Servidor" maxlength="100" required>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="codTipoLDAP"> Tipo: </label>*
                          <?php echo listboxTipoLDAP($this) ?>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="ipServidorLDAP"> Servidor: </label>*
                          <input type="text" id="ipServidorLDAP" name="ipServidorLDAP" class="form-control" placeholder="Servidor" maxlength="50">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="portaLDAP"> Porta LDAP: <span class="text-danger">*</span> </label>
                          <input type="number" id="portaLDAP" name="portaLDAP" class="form-control" placeholder="Porta LDAP" maxlength="100" number="true" required>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="loginLDAP"> Login LDAP: <span class="text-danger">*</span> </label>
                          <input type="text" id="loginLDAP" name="loginLDAP" class="form-control" placeholder="Login LDAP" maxlength="100" required>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="senhaLDAP"> Senha LDAP: <span class="text-danger">*</span> </label>
                          <input type="password" id="senhaLDAP" name="senhaLDAP" class="form-control" placeholder="Senha LDAP" required>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="nome"> Tipo Hash de Senhas: <span class="text-danger">*</span> </label>
                          <select id="tipoHash" name="tipoHash" class="custom-select" required>
                            <option value="MD5">MD5</option>
                            <option value="SMD5">SMD5</option>
                            <option value="SHA">SHA</option>
                            <option value="SSHA">SSHA</option>
                            <option value="CRYPT ">CRYPT</option>
                            <option value="SASL">SASL</option>
                          </select>
                        </div>
                      </div>

                      <div class="col-md-8">
                        <div class="form-group">
                          <label for="dn"> Dn: <span class="text-danger">*</span> </label>
                          <input type="text" id="dn" name="dn" class="form-control" placeholder="DC=XXXX,DC=YYYY,DC=ZZZZ" maxlength="100" required>
                        </div>
                      </div>

                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="encoding"> Encoding: <span class="text-danger">*</span> </label>
                          <input type="text" id="encoding" name="encoding" class="form-control" placeholder="Padrão é utf8" maxlength="20" required>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="fqdn"> Fqdn: <span class="text-danger">*</span> </label>
                          <input type="text" id="fqdn" name="fqdn" class="form-control" placeholder="dominio.com.br" maxlength="100" required>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="lDAPOptProtocolVersion"> LDAPOptProtocolVersion: <span class="text-danger">*</span> </label>
                          <input type="number" id="lDAPOptProtocolVersion" name="lDAPOptProtocolVersion" class="form-control" placeholder="Padrão é 3" maxlength="11" number="true" required>
                        </div>
                      </div>


                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="lDAPOptReferrals"> LDAPOptReferrals: <span class="text-danger">*</span> </label>
                          <input type="number" id="lDAPOptReferrals" name="lDAPOptReferrals" class="form-control" placeholder="Padrão é 0" maxlength="11" number="true" required>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="lDAPOptTimeLimit"> LDAPOptTimeLimit: <span class="text-danger">*</span> </label>
                          <input type="number" id="lDAPOptTimeLimit" name="lDAPOptTimeLimit" class="form-control" placeholder="Padrão é 0" maxlength="11" number="true" required>
                        </div>
                      </div>
                      <div style="margin-top:30px" class="col-md-4">
                        <div class="form-group">
                          <div class="icheck-primary d-inline">
                            <input type="checkbox" id="forcarSSLAdd" name="forcarSSL">
                            <label for="forcarSSLAdd"> Forçar SSL
                            </label>
                          </div>
                        </div>

                      </div>
                    </div>

                    <div class="form-group text-center">
                      <div class="btn-group">
                        <button type="submit" class="btn btn-xs btn-primary" id="add-formServicoLDAPModel-btn">Adicionar</button>
                        <a type="text" class="btn btn-xs btn-success" id="testar-formServicoLDAPModel-btn" onclick="testarServicoLDAPModelAdd()">Testar</a>
                        <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>

                      </div>
                    </div>
                  </form>

                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->

          <!-- Add modal content -->
          <div id="edit-modalServicoLDAP" class="modal fade" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl">
              <div class="modal-content">
                <div class="modal-header bg-primary text-center p-3">
                  <h4 class="modal-title text-white" id="info-header-modalLabel">Atualização de Serviço LDAP</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
                <div class="modal-body">

                  <div class="col-12 col-sm-12">
                    <div class="card card-primary card-tabs">
                      <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="tab-global" role="tablist">
                          <li class="nav-item">
                            <a class="nav-link active" id="tab-principal" data-toggle="pill" href="#tab-principal-href" role="tab" aria-controls="tab-principal-href" aria-selected="true">Configuracao GLobal</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="tab-mapeamento-atributos" data-toggle="pill" href="#tab-mapeamento-atributos-href" role="tab" aria-controls="tab-mapeamento-atributos-href" aria-selected="false">Seleção de atributos</a>
                          </li>

                          <li class="nav-item">
                            <a class="nav-link" id="tab-propriedades  " data-toggle="pill" href="#tab-propriedades-href" role="tab" aria-controls="tab-propriedades-href" aria-selected="false">Propriedades</a>
                          </li>

                          <li class="nav-item">
                            <a class="nav-link" id="tab-objectClass" data-toggle="pill" href="#tab-objectClass-href" role="tab" aria-controls="tab-objectClass-href" aria-selected="false">ObjectClass</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="tab-testes" data-toggle="pill" href="#tab-testes-href" role="tab" aria-controls="tab-testes-href" aria-selected="false">Testes</a>
                          </li>
                        </ul>
                      </div>
                      <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                          <div class="tab-pane fade show active" id="tab-principal-href" role="tabpanel" aria-labelledby="tab-principal">


                            <form id="edit-formServicoLDAP" class="pl-3 pr-3">
                              <div class="row">
                                <input type="hidden" id="<?php echo csrf_token() ?>edit-formServicoLDAPModel" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                                <input type="hidden" id="codServidorLDAP" name="codServidorLDAP" class="form-control" placeholder="Código" maxlength="11" required>
                              </div>
                              <div class="row">
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="descricaoServidorLDAP"> Nome Servidor: <span class="text-danger">*</span> </label>
                                    <input type="text" id="descricaoServidorLDAP" name="descricaoServidorLDAP" class="form-control" placeholder="Nome Servidor" maxlength="100" required>
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="codTipoLDAP"> Tipo: </label>*
                                    <?php echo listboxTipoLDAP($this) ?>
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="ipServidorLDAP"> Servidor: </label>*
                                    <input type="text" id="ipServidorLDAP" name="ipServidorLDAP" class="form-control" placeholder="Servidor" maxlength="50">
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="portaLDAP"> Porta LDAP: <span class="text-danger">*</span> </label>
                                    <input type="number" id="portaLDAP" name="portaLDAP" class="form-control" placeholder="Porta LDAP" maxlength="100" number="true" required>
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="loginLDAP"> Login LDAP: <span class="text-danger">*</span> </label>
                                    <input type="text" id="loginLDAP" name="loginLDAP" class="form-control" placeholder="Login LDAP" maxlength="100" required>
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="senhaLDAP"> Senha LDAP: <span class="text-danger">*</span> </label>
                                    <input type="password" id="senhaLDAP" name="senhaLDAP" class="form-control" placeholder="Senha LDAP" required>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="nome"> Tipo Hash de Senhas: <span class="text-danger">*</span> </label>
                                    <select id="tipoHash" name="tipoHash" class="custom-select" required>
                                      <option value="MD5">MD5</option>
                                      <option value="SMD5">SMD5</option>
                                      <option value="SHA">SHA</option>
                                      <option value="SSHA">SSHA</option>
                                      <option value="CRYPT ">CRYPT</option>
                                      <option value="SASL">SASL</option>
                                    </select>
                                  </div>
                                </div>

                                <div class="col-md-8">
                                  <div class="form-group">
                                    <label for="dn"> Dn: <span class="text-danger">*</span> </label>
                                    <input type="text" id="dn" name="dn" class="form-control" placeholder="DC=XXXX,DC=YYYY,DC=ZZZZ" maxlength="100" required>
                                  </div>
                                </div>

                              </div>
                              <div class="row">
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="encoding"> Encoding: <span class="text-danger">*</span> </label>
                                    <input type="text" id="encoding" name="encoding" class="form-control" placeholder="Padrão é utf8" maxlength="20" required>
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="fqdn"> Fqdn: <span class="text-danger">*</span> </label>
                                    <input type="text" id="fqdn" name="fqdn" class="form-control" placeholder="dominio.com.br" maxlength="100" required>
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="lDAPOptProtocolVersion"> LDAPOptProtocolVersion: <span class="text-danger">*</span> </label>
                                    <input type="number" id="lDAPOptProtocolVersion" name="lDAPOptProtocolVersion" class="form-control" placeholder="Padrão é 3" maxlength="11" number="true" required>
                                  </div>
                                </div>


                              </div>
                              <div class="row">
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="lDAPOptReferrals"> LDAPOptReferrals: <span class="text-danger">*</span> </label>
                                    <input type="number" id="lDAPOptReferrals" name="lDAPOptReferrals" class="form-control" placeholder="Padrão é 0" maxlength="11" number="true" required>
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="lDAPOptTimeLimit"> LDAPOptTimeLimit: <span class="text-danger">*</span> </label>
                                    <input type="number" id="lDAPOptTimeLimit" name="lDAPOptTimeLimit" class="form-control" placeholder="Padrão é 0" maxlength="11" number="true" required>
                                  </div>
                                </div>

                              </div>
                              <div class="row">
                                <div class="icheck-primary d-inline">
                                  <input type="checkbox" id="forcarSSL" name="forcarSSL">
                                  <label for="forcarSSL"> Forçar SSL
                                  </label>
                                </div>

                              </div>

                              <div class="row">
                                <div class="icheck-primary d-inline">
                                  <input type="checkbox" id="master" name="master">
                                  <label for="master"> Master
                                  </label>
                                </div>

                              </div>
                              <div class="row">
                                <div class="icheck-primary d-inline">
                                  <input type="checkbox" id="status" name="status">
                                  <label for="status"> Ativo
                                  </label>
                                </div>
                              </div>


                              <div class="form-group text-center">
                                <div class="btn-group">
                                  <button type="submit" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="edit-form-btn">Salvar</button>
                                  <a type="text" class="btn btn-xs btn-success" id="testar-formServicoLDAPModel-btn" onclick="testarServicoLDAPModelEdit()">Testar</a>
                                  <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
                                </div>
                              </div>
                            </form>
                          </div>
                          <div class="tab-pane fade" id="tab-mapeamento-atributos-href" role="tabpanel" aria-labelledby="tab-mapeamento-atributos">

                            <!-- Main content -->
                            <section class="content">
                              <div class="row">
                                <div class="col-12">
                                  <div class="card">
                                    <div class="card-header">
                                      <div class="row">
                                        <div class="col-md-8 mt-2">
                                          <h3 style="font-size:30px;font-weight: bold;" class="card-title">Mapeamento de Atributos LDAP</h3>
                                        </div>
                                        <div class="col-md-4">
                                          <button type="button" class="btn btn-block btn-primary  btn-xs" onclick="addMapeamentoAtributosLDAPModel(<?php echo session()->codServidorLDAP ?>)" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
                                        </div>
                                      </div>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">

                                      <table id="data_tablemapeamentoAtributosLDAP" class="table table-bordered table-striped table-hover">
                                        <thead>
                                          <tr>
                                            <th>Código</th>
                                            <th>Servidor LDAP</th>
                                            <th>Atributo Sistema</th>
                                            <th>Atributo LDAP</th>

                                            <th></th>
                                          </tr>
                                        </thead>
                                      </table>
                                    </div>
                                    <!-- /.card-body -->
                                  </div>
                                  <!-- /.card -->
                                </div>
                                <!-- /.col -->
                              </div>
                              <!-- /.row -->
                            </section>
                            <!-- Add modal content -->
                            <div id="add-modalMapeamentoAtributosLDAPModel" class="modal fade" role="dialog" aria-hidden="true">
                              <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                  <div class="modal-header bg-primary text-center p-3">
                                    <h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Mapeamento de Atributos LDAP</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">×</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                    <form id="add-formMapeamentoAtributosLDAPModel" class="pl-3 pr-3">
                                      <div class="row">
                                        <input type="hidden" id="<?php echo csrf_token() ?>add-formMapeamentoAtributosLDAPModel" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                                        <input type="hidden" id="codMapAttrLDAP" name="codMapAttrLDAP" class="form-control" placeholder="Código" maxlength="11" required>
                                      </div>
                                      <div class="row">

                                        <input type="hidden" id="codServidorLDAP" name="codServidorLDAP" class="form-control" placeholder="Servidor LDAP" maxlength="11" number="true" required>


                                        <div class="col-md-4">
                                          <div class="form-group">
                                            <label for="nomeAtributoSistema"> Atributo Sistema: <span class="text-danger">*</span> </label>
                                            <?php echo listboxAtributosSistemaOrganizacao($this, $visivelFomulario = NULL, $visivelLDAP = 1, $obrigatorio = NULL) ?>

                                          </div>
                                        </div>
                                        <div class="col-md-4">
                                          <div class="form-group">
                                            <label for="nomeAtributoLDAP"> Atributo LDAP: <span class="text-danger">*</span> </label>
                                            <select class="form-control" id="nomeAtributoLDAPAdd" name="nomeAtributoLDAP">
                                            </select>

                                          </div>
                                        </div>
                                      </div>
                                      <div class="row">
                                      </div>

                                      <div class="form-group text-center">
                                        <div class="btn-group">
                                          <button type="submit" class="btn btn-xs btn-primary" id="add-formMapeamentoAtributosLDAPModel-btn">Adicionar</button>
                                          <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
                                        </div>
                                      </div>
                                    </form>
                                  </div>
                                </div><!-- /.modal-content -->
                              </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->

                            <!-- Add modal content -->
                            <div id="edit-modalMapeamentoAtributosLDAPModel" class="modal fade" role="dialog" aria-hidden="true">
                              <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                  <div class="modal-header bg-primary text-center p-3">
                                    <h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Mapeamento de Atributos LDAP</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">×</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                    <form id="edit-formMapeamentoAtributosLDAP" class="pl-3 pr-3">
                                      <div class="row">
                                        <input type="hidden" id="<?php echo csrf_token() ?>edit-formMapeamentoAtributosLDAPModel" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                                        <input type="hidden" id="codMapAttrLDAP" name="codMapAttrLDAP" class="form-control" placeholder="Código" maxlength="11" required>
                                      </div>
                                      <div class="row">

                                        <input type="hidden" id="codServidorLDAP" name="codServidorLDAP" class="form-control" placeholder="Servidor LDAP" maxlength="11" number="true" required>

                                        <div class="col-md-4">
                                          <div class="form-group">
                                            <label for="nomeAtributoSistema"> Atributo Sistema: <span class="text-danger">*</span> </label>
                                            <?php echo listboxAtributosSistemaOrganizacao($this, $visivelFomulario = NULL, $visivelLDAP = 1, $obrigatorio = NULL) ?>
                                          </div>
                                        </div>
                                        <div class="col-md-4">
                                          <div class="form-group">
                                            <label for="nomeAtributoLDAP"> Atributo LDAP: <span class="text-danger">*</span> </label>
                                            <select class="form-control" id="nomeAtributoLDAPEdit" name="nomeAtributoLDAP">
                                            </select>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="row">
                                      </div>

                                      <div class="form-group text-center">
                                        <div class="btn-group">
                                          <button type="submit" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="edit-form-btn">Salvar</button>
                                          <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
                                        </div>
                                      </div>
                                    </form>

                                  </div>
                                </div><!-- /.modal-content -->
                              </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                            <!-- /.content -->
                          </div>



                          <div class="tab-pane fade" id="tab-propriedades-href" role="tabpanel" aria-labelledby="tab-propriedades">


                            <form id="edit-propriedades-form" class="pl-3 pr-3">
                              <input type="hidden" id="<?php echo csrf_token() ?>edit-propriedades-form" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                              <input type="hidden" id="codServidorLDAP" name="codServidorLDAP" class="form-control" placeholder="Servidor LDAP" maxlength="11" number="true" required>

                              <div class="row">
                                <div class="col-md-6">
                                  <div id="showTipoMicrosoft" class="row">
                                    <div class="col-md-8">
                                      <div class="form-group">
                                        <label for="codTipoMicrosoft">Tipo Active Directory: <span class="text-danger">*</span> </label>
                                        <select class="form-control" id="codTipoMicrosoft" name="codTipoMicrosoft" type="custom-select">
                                          <option value="NULL">NÃO SE APLICA</option>
                                          <option value="1">Microsoft Windows NT 4.0</option>
                                          <option value="2">Microsoft Windows 2000</option>
                                          <option value="3">Microsoft Windows 2003</option>
                                          <option value="4">Microsoft Windows 2008</option>
                                          <option value="5">Microsoft Windows 2012</option>
                                          <option value="6">Microsoft Windows 2016</option>
                                          <option value="7">Microsoft Windows 2018</option>
                                          <option value="8">Microsoft Windows 2019</option>
                                          <option value="9">Microsoft Windows 2021</option>
                                          <option value="10">Samba 4 (Linux)</option>
                                        </select>
                                      </div>
                                    </div>
                                  </div>

                                  <div class="row">
                                    <div class="col-md-8">
                                      <div class="form-group">
                                        <label for="dnNovosUsuarios">DN de Novos Usuários: <span class="text-danger">*</span> </label>
                                        <input type="text" id="dnNovosUsuarios" name="dnNovosUsuarios" class="form-control" placeholder="OU=XXXX,DC=XXXX,DC=YYYY,DC=ZZZZ" maxlength="100" required>
                                      </div>
                                    </div>
                                  </div>

                                  <div class="row">
                                    <div class="col-md-8">
                                      <div class="form-group">
                                        <label for="atributoChave">Atributo chave: <span class="text-danger">*</span> </label>
                                        <select class="form-control" id="atributoChave" name="atributoChave" type="select2">
                                        </select>

                                      </div>
                                    </div>

                                  </div>

                                </div>
                                <div class="col-md-6">


                                  <div class="row">
                                    <div class="col-md-12">
                                      <div class="form-group">
                                        <label for="sambaSID">Samba SID:</label>
                                        <input type="text" id="sambaSID" name="sambaSID" class="form-control" placeholder="sambaSID" maxlength="100" >
                                      </div>
                                    </div>
                                    <div class="col-md-12">
                                      <div class="form-group">
                                        <label for="servidorArquivo">IP Servidor de Arquivo: </label>
                                        <input type="text" id="servidorArquivo" name="servidorArquivo" class="form-control" placeholder="IP Servidor de Arquivo" maxlength="100" >
                                      </div>
                                    </div>
                                  </div>



                                </div>
                              </div>


                              <div class="form-group text-center">
                                <div class="btn-group">
                                  <button type="submit" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="edit-form-btn">Salvar</button>
                                  <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
                                </div>
                              </div>
                            </form>
                          </div>
                          <div class="tab-pane fade" id="tab-objectClass-href" role="tabpanel" aria-labelledby="tab-objectClass">
                            implementar tabela no futuro, por enquanto vai ficar na tabela direto (sis_servicoldapobjectclassadicionais)
                          </div>
                          <div class="tab-pane fade" id="tab-testes-href" role="tabpanel" aria-labelledby="tab-testes">
                            Implementar
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /.card -->
                  </div>
                </div>





              </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->







      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.col -->
  </div>
  <!-- /.row -->
</section>


<?php
echo view('tema/rodape');
?>


<script>
  var codServidorLDAPtmp = 0;
</script>




<script>
  $(function() {
    $('#data_tableSMTP').DataTable({
      "paging": false,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": false,
      "autoWidth": false,
      "responsive": false,
      "ajax": {
        "url": '<?php echo base_url('integracoes/integracaoSMTP') ?>',
        "type": "POST",
        "dataType": "json",
        async: "true",
        data: {
          csrf_sandra: $("#csrf_sandraPrincipal").val(),
        },
      }
    });

    var codOrganizacao = <?php echo "$codOrganizacao" ?>;

    $.ajax({
      url: '<?php echo base_url('integracoes/getOneorganizacoes') ?>',
      type: 'post',
      data: {
        codOrganizacao: codOrganizacao,
        csrf_sandra: $("#csrf_sandraPrincipal").val(),
      },
      dataType: 'json',
      success: function(organizacao) {
        // reset the form 
        $("#formSPED")[0].reset();

        $("#formSPED #codOrganizacao").val(organizacao.codOrganizacao);
        $("#formSPED #servidorSpedDB").val(organizacao.servidorSpedDB);
        $("#formSPED #SpedDB").val(organizacao.SpedDB);
        $("#formSPED #administradorSpedDB").val(organizacao.administradorSpedDB);
        $("#formSPED #senhaadministradorSpedDB").val(organizacao.senhaadministradorSpedDB);

        if (organizacao.servidorSPEDStatus == '1') {
          document.getElementById("checkboxSPEDEdit").checked = true;
        }

      }
    })




  });



  function salvarSPED() {



    var form = $('#formSPED');


    $.ajax({
      url: '<?php echo base_url('integracoes/salvarSPED') ?>',
      type: 'post',
      data: form.serialize(), // /converting the form data into array and sending it to server
      dataType: 'json',
      beforeSend: function() {},
      success: function(responseSalvar) {

        if (responseSalvar.success === true) {

          Swal.fire({
            position: 'bottom-end',
            icon: 'success',
            title: responseSalvar.messages,
            showConfirmButton: false,
            timer: 1500
          })

        } else {

          Swal.fire({
            position: 'bottom-end',
            icon: 'error',
            title: responseSalvar.messages,
            showConfirmButton: false,
            timer: 1500
          })
        }
      }
    });

    return false;

  }


  function showTestarSMTP() {


    $("#formTestarSMTP")[0].reset();
    $('#modalTestarSMTP').modal('show');
    $("#modalTestarSMTP").css("z-index", "1500");

    $("#formTestarSMTP #ipServidorSMTPTeste").val($("#edit-formSMTP #ipServidorSMTP").val());
    $("#formTestarSMTP #portaSMTPTeste").val($("#edit-formSMTP #portaSMTP").val());
    $("#formTestarSMTP #loginSMTPTeste").val($("#edit-formSMTP #loginSMTP").val());
    $("#formTestarSMTP #senhaSMTPTeste").val($("#edit-formSMTP #senhaSMTP").val());
    $("#formTestarSMTP #emailRetornoTeste").val($("#edit-formSMTP #emailRetorno").val());
    $("#formTestarSMTP #protocoloSMTPTeste").val($("#edit-formSMTP #protocoloSMTP").val());
    $("#formTestarSMTP #statusSMTPTeste").val($("#edit-formSMTP #statusSMTP").val());


  }

  function testarSMTP() {
    // reset the form 
    $('#modalTestarSMTP').modal('hide');

    var form = $('#formTestarSMTP');
    // remove the text-danger

    $.ajax({
      url: '<?php echo base_url('integracoes/testeSMTP') ?>',
      type: 'post',
      data: form.serialize(), // /converting the form data into array and sending it to server
      dataType: 'json',
      success: function(testeSMTP) {

        if (testeSMTP.success === true) {
          Swal.fire({
            position: 'bottom-end',
            icon: 'success',
            title: testeSMTP.messages,
            showConfirmButton: false,
            timer: 4000
          })
        }

        if (testeSMTP.success === false) {
          Swal.fire({
            position: 'bottom-end',
            icon: 'error',
            title: testeSMTP.messages,
            html: testeSMTP.html,
            showConfirmButton: false,
            timer: 4000
          })
        }

      }



    }).always(
      Swal.fire({
        title: 'Estamos processando sua requisição',
        html: 'Aguarde....',
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading()


        }

      }))
  }

  function add_smtp() {
    // reset the form 
    $("#add-formSMTP")[0].reset();
    $(".form-control").removeClass('is-invalid').removeClass('is-valid');
    $('#add-modalSMTP').modal('show');
    // submit the add from 
    $.validator.setDefaults({
      highlight: function(element) {
        $(element).addClass('is-invalid').removeClass('is-valid');
      },
      unhighlight: function(element) {
        $(element).removeClass('is-invalid').addClass('is-valid');
      },
      errorElement: 'div ',
      errorClass: 'invalid-feedback',
      errorPlacement: function(error, element) {
        if (element.parent('.input-group').length) {
          error.insertAfter(element.parent());
        } else if ($(element).is('.select')) {
          element.next().after(error);
        } else if (element.hasClass('select2')) {
          //error.insertAfter(element);
          error.insertAfter(element.next());
        } else if (element.hasClass('selectpicker')) {
          error.insertAfter(element.next());
        } else {
          error.insertAfter(element);
        }
      },

      submitHandler: function(form) {

        var form = $('#add-formSMTP');
        // remove the text-danger
        $(".text-danger").remove();

        $.ajax({
          url: '<?php echo base_url('integracoes/addSMTP') ?>',
          type: 'post',
          data: form.serialize(), // /converting the form data into array and sending it to server
          dataType: 'json',
          beforeSend: function() {
            //$('#add-formSMTP-btn').html('<i class="fa fa-spinner fa-spin"></i>');
          },
          success: function(response) {

            if (response.success === true) {

              Swal.fire({
                position: 'bottom-end',
                icon: 'success',
                title: response.messages,
                showConfirmButton: false,
                timer: 1500
              }).then(function() {
                $('#data_tableSMTP').DataTable().ajax.reload(null, false).draw(false);
                $('#add-modalSMTP').modal('hide');
              })

            } else {

              if (response.messages instanceof Object) {
                $.each(response.messages, function(index, value) {
                  var id = $("#" + index);

                  id.closest('.form-control')
                    .removeClass('is-invalid')
                    .removeClass('is-valid')
                    .addClass(value.length > 0 ? 'is-invalid' : 'is-valid');

                  id.after(value);

                });
              } else {
                Swal.fire({
                  position: 'bottom-end',
                  icon: 'error',
                  title: response.messages,
                  showConfirmButton: false,
                  timer: 1500
                })

              }
            }
            $('#add-formSMTP-btn').html('Adicionar');
          }
        });

        return false;
      }
    });
    $('#add-formSMTP').validate();
  }

  function editSMTP(codServidorSMTP) {
    $.ajax({
      url: '<?php echo base_url('integracoes/getOneSMTP') ?>',
      type: 'post',
      data: {
        codServidorSMTP: codServidorSMTP,
        csrf_sandra: $("#csrf_sandraPrincipal").val(),
      },
      dataType: 'json',
      success: function(response) {
        // reset the form 
        $("#edit-formSMTP")[0].reset();
        $(".form-control").removeClass('is-invalid').removeClass('is-valid');
        $('#edit-modalSMTP').modal('show');

        $("#edit-formSMTP #codServidorSMTP").val(response.codServidorSMTP);
        $("#edit-formSMTP #descricaoServidorSMTP").val(response.descricaoServidorSMTP);
        $("#edit-formSMTP #ipServidorSMTP").val(response.ipServidorSMTP);
        $("#edit-formSMTP #portaSMTP").val(response.portaSMTP);
        $("#edit-formSMTP #loginSMTP").val(response.loginSMTP);
        $("#edit-formSMTP #senhaSMTP").val(response.senhaSMTP);
        $("#edit-formSMTP #emailRetorno").val(response.emailRetorno);
        $("#edit-formSMTP #protocoloSMTP").val(response.protocoloSMTP);
        $("#edit-formSMTP #statusSMTP").val(response.statusSMTP);

        // submit the edit from 
        $.validator.setDefaults({
          highlight: function(element) {
            $(element).addClass('is-invalid').removeClass('is-valid');
          },
          unhighlight: function(element) {
            $(element).removeClass('is-invalid').addClass('is-valid');
          },
          errorElement: 'div ',
          errorClass: 'invalid-feedback',
          errorPlacement: function(error, element) {
            if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
            } else if ($(element).is('.select')) {
              element.next().after(error);
            } else if (element.hasClass('select2')) {
              //error.insertAfter(element);
              error.insertAfter(element.next());
            } else if (element.hasClass('selectpicker')) {
              error.insertAfter(element.next());
            } else {
              error.insertAfter(element);
            }
          },

          submitHandler: function(form) {
            var form = $('#edit-formSMTP');
            $(".text-danger").remove();
            $.ajax({
              url: '<?php echo base_url('integracoes/editSMTP') ?>',
              type: 'post',
              data: form.serialize(),
              dataType: 'json',
              beforeSend: function() {
                //$('#edit-formSMTP-btn').html('<i class="fa fa-spinner fa-spin"></i>');
              },
              success: function(response) {

                if (response.success === true) {

                  Swal.fire({
                    position: 'bottom-end',
                    icon: 'success',
                    title: response.messages,
                    showConfirmButton: false,
                    timer: 1500
                  }).then(function() {
                    $('#data_tableSMTP').DataTable().ajax.reload(null, false).draw(false);
                  })

                } else {

                  if (response.messages instanceof Object) {
                    $.each(response.messages, function(index, value) {
                      var id = $("#" + index);

                      id.closest('.form-control')
                        .removeClass('is-invalid')
                        .removeClass('is-valid')
                        .addClass(value.length > 0 ? 'is-invalid' : 'is-valid');

                      id.after(value);

                    });
                  } else {
                    Swal.fire({
                      position: 'bottom-end',
                      icon: 'error',
                      title: response.messages,
                      showConfirmButton: false,
                      timer: 1500
                    })

                  }
                }
                $('#edit-formSMTP-btn').html('Salvar');
              }
            });

            return false;
          }
        });
        $('#edit-formSMTP').validate();

      }
    });
  }

  function removeSMTP(codServidorSMTP) {
    Swal.fire({
      title: 'Você tem certeza que deseja remover?',
      text: "Você não poderá reverter após a confirmação",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {

      if (result.value) {
        $.ajax({
          url: '<?php echo base_url('integracoes/removeSMTP') ?>',
          type: 'post',
          data: {
            codServidorSMTP: codServidorSMTP,
            csrf_sandra: $("#csrf_sandraPrincipal").val(),
          },
          dataType: 'json',
          success: function(response) {

            if (response.success === true) {
              Swal.fire({
                position: 'bottom-end',
                icon: 'success',
                title: response.messages,
                showConfirmButton: false,
                timer: 1500
              }).then(function() {
                $('#data_tableSMTP').DataTable().ajax.reload(null, false).draw(false);
              })
            } else {
              Swal.fire({
                position: 'bottom-end',
                icon: 'error',
                title: response.messages,
                showConfirmButton: false,
                timer: 1500
              })


            }
          }
        });
      }
    })
  }
</script>



<script>
  $(function() {
    $('#data_tableServicoLDAPModel').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": false,
      "autoWidth": false,
      "responsive": false,
      "ajax": {
        "url": '<?php echo base_url('integracoes/servidoresIntegracaoLDAP') ?>',
        "type": "POST",
        "dataType": "json",
        async: "true",
        data: {
          csrf_sandra: $("#csrf_sandraPrincipal").val(),
        },
      }
    });
  });


  function testarServicoLDAPModelEdit() {

    ipServidorLDAP = $("#edit-formServicoLDAP #ipServidorLDAP").val();
    codTipoLDAP = $("#edit-formServicoLDAP #codTipoLDAP").val();
    portaLDAP = $("#edit-formServicoLDAP #portaLDAP").val();
    loginLDAP = $("#edit-formServicoLDAP #loginLDAP").val();
    senhaLDAP = $("#edit-formServicoLDAP #senhaLDAP").val();
    dn = $("#edit-formServicoLDAP #dn").val();
    encoding = $("#edit-formServicoLDAP #encoding").val();
    fqdn = $("#edit-formServicoLDAP #fqdn").val();
    lDAPOptProtocolVersion = $("#edit-formServicoLDAP #lDAPOptProtocolVersion").val();
    lDAPOptReferrals = $("#edit-formServicoLDAP #lDAPOptReferrals").val();
    lDAPOptTimeLimit = $("#edit-formServicoLDAP #lDAPOptTimeLimit").val();
    descricaoServidorLDAP = $("#edit-formServicoLDAP #descricaoServidorLDAP").val();

    if (ipServidorLDAP == '' || codTipoLDAP == '' || portaLDAP == '' || loginLDAP == '' || senhaLDAP == '' || dn == '' || encoding == '' || fqdn == '' || lDAPOptProtocolVersion == '' || lDAPOptReferrals == '' || lDAPOptTimeLimit == '' || descricaoServidorLDAP == '') {
      Swal.fire({
        position: 'bottom-end',
        icon: 'error',
        title: 'É necessário preencher todos os campos obrigatórios',
        showConfirmButton: false,
        timer: 2000
      })

    } else {
      $.ajax({
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: '<?php echo base_url('integracoes/testeConexaoLDAP/') ?>',
        data: {
          ipServidorLDAP: ipServidorLDAP,
          codTipoLDAP: codTipoLDAP,
          portaLDAP: portaLDAP,
          loginLDAP: loginLDAP,
          senhaLDAP: senhaLDAP,
          dn: dn,
          encoding: encoding,
          fqdn: fqdn,
          lDAPOptProtocolVersion: lDAPOptProtocolVersion,
          lDAPOptReferrals: lDAPOptReferrals,
          lDAPOptTimeLimit: lDAPOptTimeLimit,
          descricaoServidorLDAP: descricaoServidorLDAP,
          csrf_sandra: $("#csrf_sandraPrincipal").val(),
        },
        success: function(response) {

          if (response.resultadoTeste == true) {
            Swal.fire({
              position: 'bottom-end',
              icon: 'success',
              title: response.messages,
              showConfirmButton: false,
            })
          } else {
            Swal.fire({
              position: 'bottom-end',
              icon: 'error',
              title: response.messages,
              showConfirmButton: false,
            })
          }

        }
      });

    }

  }


  function testarServicoLDAPModelAdd() {

    ipServidorLDAP = document.getElementById("ipServidorLDAP").value;
    codTipoLDAP = document.getElementById("codTipoLDAP").value;
    portaLDAP = document.getElementById("portaLDAP").value;
    loginLDAP = document.getElementById("loginLDAP").value;
    senhaLDAP = document.getElementById("senhaLDAP").value;
    dn = document.getElementById("dn").value;
    encoding = document.getElementById("encoding").value;
    fqdn = document.getElementById("fqdn").value;
    lDAPOptProtocolVersion = document.getElementById("lDAPOptProtocolVersion").value;
    lDAPOptReferrals = document.getElementById("lDAPOptReferrals").value;
    lDAPOptTimeLimit = document.getElementById("lDAPOptTimeLimit").value;
    descricaoServidorLDAP = document.getElementById("descricaoServidorLDAP").value;

    if (ipServidorLDAP == '' || codTipoLDAP == '' || portaLDAP == '' || loginLDAP == '' || senhaLDAP == '' || dn == '' || encoding == '' || fqdn == '' || lDAPOptProtocolVersion == '' || lDAPOptReferrals == '' || lDAPOptTimeLimit == '' || descricaoServidorLDAP == '') {
      Swal.fire({
        position: 'bottom-end',
        icon: 'error',
        title: 'É necessário preencher todos os campos obrigatórios',
        showConfirmButton: false,
        timer: 2000
      })

    } else {
      $.ajax({
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: '<?php echo base_url('Integracoes/testeConexaoLDAP/') ?>',
        data: {
          ipServidorLDAP: ipServidorLDAP,
          codTipoLDAP: codTipoLDAP,
          portaLDAP: portaLDAP,
          loginLDAP: loginLDAP,
          senhaLDAP: senhaLDAP,
          dn: dn,
          encoding: encoding,
          fqdn: fqdn,
          lDAPOptProtocolVersion: lDAPOptProtocolVersion,
          lDAPOptReferrals: lDAPOptReferrals,
          lDAPOptTimeLimit: lDAPOptTimeLimit,
          descricaoServidorLDAP: descricaoServidorLDAP,
          csrf_sandra: $("#csrf_sandraPrincipal").val(),
        },
        success: function(response) {

          if (response.resultadoTeste == true) {
            Swal.fire({
              position: 'bottom-end',
              icon: 'success',
              title: response.messages,
              showConfirmButton: false,
            })
          } else {
            Swal.fire({
              position: 'bottom-end',
              icon: 'error',
              title: response.messages,
              showConfirmButton: false,
            })
          }

        }
      });

    }

  }

  function addServicoLDAPModel() {
    // reset the form 
    $("#add-formServicoLDAPModel")[0].reset();
    $(".form-control").removeClass('is-invalid').removeClass('is-valid');
    $('#add-modalServicoLDAPModel').modal('show');




    // submit the add from 
    $.validator.setDefaults({
      highlight: function(element) {
        $(element).addClass('is-invalid').removeClass('is-valid');
      },
      unhighlight: function(element) {
        $(element).removeClass('is-invalid').addClass('is-valid');
      },
      errorElement: 'div ',
      errorClass: 'invalid-feedback',
      errorPlacement: function(error, element) {
        if (element.parent('.input-group').length) {
          error.insertAfter(element.parent());
        } else if ($(element).is('.select')) {
          element.next().after(error);
        } else if (element.hasClass('select2')) {
          //error.insertAfter(element);
          error.insertAfter(element.next());
        } else if (element.hasClass('selectpicker')) {
          error.insertAfter(element.next());
        } else {
          error.insertAfter(element);
        }
      },


      submitHandler: function(form) {

        var form = $('#add-formServicoLDAPModel');
        // remove the text-danger
        $(".text-danger").remove();

        $.ajax({
          url: '<?php echo base_url('integracoes/addLDAP') ?>',
          type: 'post',
          data: form.serialize(), // /converting the form data into array and sending it to server
          dataType: 'json',
          beforeSend: function() {
            //$('#add-formServicoLDAPModel-btn').html('<i class="fa fa-spinner fa-spin"></i>');
          },
          success: function(response) {

            if (response.success === true) {

              Swal.fire({
                position: 'bottom-end',
                icon: 'success',
                title: response.messages,
                showConfirmButton: false,
                timer: 1500
              }).then(function() {
                $('#data_tableServicoLDAPModel').DataTable().ajax.reload(null, false).draw(false);
                $('#add-modalServicoLDAPModel').modal('hide');
              })

            } else {

              if (response.messages instanceof Object) {
                $.each(response.messages, function(index, value) {
                  var id = $("#" + index);

                  id.closest('.form-control')
                    .removeClass('is-invalid')
                    .removeClass('is-valid')
                    .addClass(value.length > 0 ? 'is-invalid' : 'is-valid');

                  id.after(value);

                });
              } else {
                Swal.fire({
                  position: 'bottom-end',
                  icon: 'error',
                  title: response.messages,
                  showConfirmButton: false,
                  timer: 1500
                })

              }
            }
            $('#add-formServicoLDAPModel-btn').html('Adicionar');
          }
        });

        return false;
      }
    });
    $('#add-formServicoLDAPModel').validate();
  }

  function editservicoLDAP(codServidorLDAP) {

    var codServidorLDAP = codServidorLDAP;


    $.ajax({
      url: '<?php echo base_url('integracoes/getOneLDAP') ?>',
      cache: false,
      type: 'post',
      data: {
        codServidorLDAP: codServidorLDAP,
        csrf_sandra: $("#csrf_sandraPrincipal").val(),
      },
      dataType: 'json',
      success: function(response) {
        // reset the form 
        $("#edit-formServicoLDAP")[0].reset();
        $(".form-control").removeClass('is-invalid').removeClass('is-valid');
        $('#edit-modalServicoLDAP').modal('show');

        codServidorLDAPtmp = response.codServidorLDAP;


        // $("#nomeAtributoLDAPAdd").select2("destroy");


        $.ajax({
          url: '<?php echo base_url('integracoes/atributosLDAP') ?>',
          type: 'post',
          data: {
            codTipoLDAP: response.codTipoLDAP,
            csrf_sandra: $("#csrf_sandraPrincipal").val(),
          },
          dataType: 'json',
          success: function(atributos) {
            $("#nomeAtributoLDAPAdd").select2({
              data: atributos,
            })

          }
        })


        $('#data_tablemapeamentoAtributosLDAP').DataTable({
          "scrollX": false,
          "paging": true,
          "bDestroy": true,
          "lengthChange": true,
          "searching": false,
          "ordering": true,
          "info": false,
          "autoWidth": false,
          "responsive": true,
          "ajax": {
            "url": '<?php echo base_url('integracoes/pegaServidorLDAP/') ?>/' + codServidorLDAPtmp,
            "type": "POST",
            "dataType": "json",
            async: "true",
            data: {
              csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
          }
        });



        $("#edit-formServicoLDAP #codServidorLDAP").val(response.codServidorLDAP);
        $("#edit-formServicoLDAP #descricaoServidorLDAP").val(response.descricaoServidorLDAP);
        $("#edit-formServicoLDAP #codTipoLDAP").val(response.codTipoLDAP);
        $("#edit-formServicoLDAP #ipServidorLDAP").val(response.ipServidorLDAP);
        $("#edit-formServicoLDAP #portaLDAP").val(response.portaLDAP);
        $("#edit-formServicoLDAP #loginLDAP").val(response.loginLDAP);
        $("#edit-formServicoLDAP #senhaLDAP").val(response.senhaLDAP);
        $("#edit-formServicoLDAP #dn").val(response.dn);
        $("#edit-formServicoLDAP #encoding").val(response.encoding);
        $("#edit-formServicoLDAP #fqdn").val(response.fqdn);
        $("#edit-formServicoLDAP #lDAPOptProtocolVersion").val(response.LDAPOptProtocolVersion);
        $("#edit-formServicoLDAP #lDAPOptReferrals").val(response.LDAPOptReferrals);
        $("#edit-formServicoLDAP #lDAPOptTimeLimit").val(response.LDAPOptTimeLimit);
        $("#edit-formServicoLDAP #tipoHash").val(response.tipoHash);
        if (response.forcarSSL == 1) {
          $("#edit-formServicoLDAP #forcarSSL").prop("checked", true);
        } else {}

        if (response.master == 1) {
          $("#edit-formServicoLDAP #master").prop("checked", true);
        } else {}
        if (response.status == 1) {
          $("#edit-formServicoLDAP #status").prop("checked", true);
        } else {}
        // submit the edit from 
        $.validator.setDefaults({
          highlight: function(element) {
            $(element).addClass('is-invalid').removeClass('is-valid');
          },
          unhighlight: function(element) {
            $(element).removeClass('is-invalid').addClass('is-valid');
          },
          errorElement: 'div ',
          errorClass: 'invalid-feedback',
          errorPlacement: function(error, element) {
            if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
            } else if ($(element).is('.select')) {
              element.next().after(error);
            } else if (element.hasClass('select2')) {
              //error.insertAfter(element);
              error.insertAfter(element.next());
            } else if (element.hasClass('selectpicker')) {
              error.insertAfter(element.next());
            } else {
              error.insertAfter(element);
            }
          },

          submitHandler: function(form) {
            var form = $('#edit-formServicoLDAP');
            $(".text-danger").remove();
            $.ajax({
              url: '<?php echo base_url('integracoes/editLDAP') ?>',
              type: 'post',
              data: form.serialize(),
              dataType: 'json',
              beforeSend: function() {
                //$('#edit-formServicoLDAP-btn').html('<i class="fa fa-spinner fa-spin"></i>');
              },
              success: function(response) {

                if (response.success === true) {

                  Swal.fire({
                    position: 'bottom-end',
                    icon: 'success',
                    title: response.messages,
                    showConfirmButton: false,
                    timer: 1500
                  }).then(function() {
                    $('#data_tableServicoLDAPModel').DataTable().ajax.reload(null, false).draw(false);
                    //$('#edit-modalServicoLDAP').modal('hide');
                  })

                } else {

                  if (response.messages instanceof Object) {
                    $.each(response.messages, function(index, value) {
                      var id = $("#" + index);

                      id.closest('.form-control')
                        .removeClass('is-invalid')
                        .removeClass('is-valid')
                        .addClass(value.length > 0 ? 'is-invalid' : 'is-valid');

                      id.after(value);

                    });
                  } else {
                    Swal.fire({
                      position: 'bottom-end',
                      icon: 'error',
                      title: response.messages,
                      showConfirmButton: false,
                      timer: 1500
                    })

                  }
                }
                $('#edit-formServicoLDAP-btn').html('Salvar');
              }
            });

            return false;
          }
        });
        $('#edit-formServicoLDAP').validate();





        $("#edit-propriedades-form")[0].reset();
        $("#edit-propriedades-form #codServidorLDAP").val(codServidorLDAP);
        $("#edit-propriedades-form #codTipoMicrosoft").val(response.codTipoMicrosoft);
        $("#edit-propriedades-form #dnNovosUsuarios").val(response.dnNovosUsuarios);
        $("#edit-propriedades-form #sambaSID").val(response.sambaSID);
        $("#edit-propriedades-form #servidorArquivo").val(response.servidorArquivo);

        if (response.codTipoLDAP == 1) {
          document.getElementById("showTipoMicrosoft").style.display = 'block';


        } else {
          //document.getElementById("showTipoMicrosoft").style.display = 'none';
          document.getElementById("showTipoMicrosoft").style.display = 'block';

        }





        $("#atributoChave").select2({
          data: [{
              "id": '',
              "text": ''
            },
            {
              "id": 'cn',
              "text": 'cn'
            },
            {
              "id": 'uid',
              "text": 'uid'
            },
            {
              "id": 'samaccountname',
              "text": 'samaccountname'
            },
            {
              "id": 'samaccountname',
              "text": 'samaccountname = não usar no ZENTYAL. AD?'
            }
          ],
        }).val(response.atributoChave).trigger("change");



        $('#edit-propriedades-form').submit(function(event) {

          event.preventDefault();
          var form = $('#edit-propriedades-form');
          $.ajax({
            url: '<?php echo base_url('integracoes/salvaPropriedadesServidorLDAP') ?>',
            type: 'post',
            data: form.serialize(),
            dataType: 'json',
            beforeSend: function() {
              //$('#edit-propriedades-form-btn').html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function(response) {

              if (response.success === true) {

                Swal.fire({
                  position: 'bottom-end',
                  icon: 'success',
                  title: response.messages,
                  showConfirmButton: false,
                  timer: 2000
                })

              } else {

                if (response.messages instanceof Object) {
                  $.each(response.messages, function(index, value) {
                    var id = $("#" + index);

                    id.closest('.form-control')
                      .removeClass('is-invalid')
                      .removeClass('is-valid')
                      .addClass(value.length > 0 ? 'is-invalid' : 'is-valid');

                    id.after(value);

                  });
                } else {
                  Swal.fire({
                    position: 'bottom-end',
                    icon: 'error',
                    title: response.messages,
                    showConfirmButton: false,
                    timer: 3000
                  })

                }
              }
              $('#edit-propriedades-form-btn').html('Salvar');
            }
          });
        })




      }
    });

  }




  function removeservicoLDAP(codServidorLDAP) {
    Swal.fire({
      title: 'Você tem certeza que deseja remover?',
      text: "Você não poderá reverter após a confirmação",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {

      if (result.value) {
        $.ajax({
          url: '<?php echo base_url('integracoes/removeLDAP') ?>',
          type: 'post',
          data: {
            codServidorLDAP: codServidorLDAP,
            csrf_sandra: $("#csrf_sandraPrincipal").val(),
          },
          dataType: 'json',
          success: function(response) {

            if (response.success === true) {
              Swal.fire({
                position: 'bottom-end',
                icon: 'success',
                title: response.messages,
                showConfirmButton: false,
                timer: 1500
              }).then(function() {
                $('#data_tableServicoLDAPModel').DataTable().ajax.reload(null, false).draw(false);
              })
            } else {
              Swal.fire({
                position: 'bottom-end',
                icon: 'error',
                title: response.messages,
                showConfirmButton: false,
                timer: 1500
              })


            }
          }
        });
      }
    })
  }
</script>


<script>
  $(function() {
    $('#data_tableProtocolosRedeModel').DataTable({
      "scrollX": false,
      "paging": true,
      "lengthChange": false,
      "pageLength": 5,
      "searching": false,
      "ordering": true,
      "info": false,
      "autoWidth": false,
      "responsive": true,
      "ajax": {
        "url": '<?php echo base_url('integracoes/integracaoProtocolos') ?>',
        "type": "POST",
        "dataType": "json",
        async: "true",
        data: {
          csrf_sandra: $("#csrf_sandraPrincipal").val(),
        },
      }
    });
  });

  function addProtocolosRedeModel() {
    // reset the form 
    $("#add-formProtocolosRedeModel")[0].reset();
    $(".form-control").removeClass('is-invalid').removeClass('is-valid');
    $('#add-modalProtocolosRedeModel').modal('show');
    // submit the add from 
    $.validator.setDefaults({
      highlight: function(element) {
        $(element).addClass('is-invalid').removeClass('is-valid');
      },
      unhighlight: function(element) {
        $(element).removeClass('is-invalid').addClass('is-valid');
      },
      errorElement: 'div ',
      errorClass: 'invalid-feedback',
      errorPlacement: function(error, element) {
        if (element.parent('.input-group').length) {
          error.insertAfter(element.parent());
        } else if ($(element).is('.select')) {
          element.next().after(error);
        } else if (element.hasClass('select2')) {
          //error.insertAfter(element);
          error.insertAfter(element.next());
        } else if (element.hasClass('selectpicker')) {
          error.insertAfter(element.next());
        } else {
          error.insertAfter(element);
        }
      },

      submitHandler: function(form) {

        var form = $('#add-formProtocolosRedeModel');
        // remove the text-danger
        $(".text-danger").remove();

        $.ajax({
          url: '<?php echo base_url('integracoes/addProtocoloRede') ?>',
          type: 'post',
          data: form.serialize(), // /converting the form data into array and sending it to server
          dataType: 'json',
          beforeSend: function() {
            //$('#add-formProtocolosRedeModel-btn').html('<i class="fa fa-spinner fa-spin"></i>');
          },
          success: function(response) {

            if (response.success === true) {

              Swal.fire({
                position: 'bottom-end',
                icon: 'success',
                title: response.messages,
                showConfirmButton: false,
                timer: 1500
              }).then(function() {
                $('#data_tableProtocolosRedeModel').DataTable().ajax.reload(null, false).draw(false);
                $('#add-modalProtocolosRedeModel').modal('hide');
              })

            } else {

              if (response.messages instanceof Object) {
                $.each(response.messages, function(index, value) {
                  var id = $("#" + index);

                  id.closest('.form-control')
                    .removeClass('is-invalid')
                    .removeClass('is-valid')
                    .addClass(value.length > 0 ? 'is-invalid' : 'is-valid');

                  id.after(value);

                });
              } else {
                Swal.fire({
                  position: 'bottom-end',
                  icon: 'error',
                  title: response.messages,
                  showConfirmButton: false,
                  timer: 1500
                })

              }
            }
            $('#add-formProtocolosRedeModel-btn').html('Adicionar');
          }
        });

        return false;
      }
    });
    $('#add-formProtocolosRedeModel').validate();
  }

  function editprotocolosRede(codProtocoloRede) {
    $.ajax({
      url: '<?php echo base_url('integracoes/getOneProtocoloRede') ?>',
      type: 'post',
      data: {
        codProtocoloRede: codProtocoloRede,
        csrf_sandra: $("#csrf_sandraPrincipal").val(),
      },
      dataType: 'json',
      success: function(response) {
        // reset the form 
        $("#edit-form")[0].reset();
        $(".form-control").removeClass('is-invalid').removeClass('is-valid');
        $('#edit-modalProtocolosRedeModel').modal('show');

        $("#edit-form #codProtocoloRede").val(response.codProtocoloRede);
        $("#edit-form #nomeProtocoloRede").val(response.nomeProtocoloRede);
        $("#edit-form #conector").val(response.conector);
        $("#edit-form #portaPadrao").val(response.portaPadrao);

        // submit the edit from 
        $.validator.setDefaults({
          highlight: function(element) {
            $(element).addClass('is-invalid').removeClass('is-valid');
          },
          unhighlight: function(element) {
            $(element).removeClass('is-invalid').addClass('is-valid');
          },
          errorElement: 'div ',
          errorClass: 'invalid-feedback',
          errorPlacement: function(error, element) {
            if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
            } else if ($(element).is('.select')) {
              element.next().after(error);
            } else if (element.hasClass('select2')) {
              //error.insertAfter(element);
              error.insertAfter(element.next());
            } else if (element.hasClass('selectpicker')) {
              error.insertAfter(element.next());
            } else {
              error.insertAfter(element);
            }
          },

          submitHandler: function(form) {
            var form = $('#edit-form');
            $(".text-danger").remove();
            $.ajax({
              url: '<?php echo base_url('integracoes/editProtocoloRede') ?>',
              type: 'post',
              data: form.serialize(),
              dataType: 'json',
              beforeSend: function() {
                //$('#edit-form-btn').html('<i class="fa fa-spinner fa-spin"></i>');
              },
              success: function(response) {

                if (response.success === true) {

                  Swal.fire({
                    position: 'bottom-end',
                    icon: 'success',
                    title: response.messages,
                    showConfirmButton: false,
                    timer: 1500
                  }).then(function() {
                    $('#data_tableProtocolosRedeModel').DataTable().ajax.reload(null, false).draw(false);
                    $('#edit-modalProtocolosRedeModel').modal('hide');
                  })

                } else {

                  if (response.messages instanceof Object) {
                    $.each(response.messages, function(index, value) {
                      var id = $("#" + index);

                      id.closest('.form-control')
                        .removeClass('is-invalid')
                        .removeClass('is-valid')
                        .addClass(value.length > 0 ? 'is-invalid' : 'is-valid');

                      id.after(value);

                    });
                  } else {
                    Swal.fire({
                      position: 'bottom-end',
                      icon: 'error',
                      title: response.messages,
                      showConfirmButton: false,
                      timer: 1500
                    })

                  }
                }
                $('#edit-form-btn').html('Salvar');
              }
            });

            return false;
          }
        });
        $('#edit-form').validate();

      }
    });
  }

  function removeprotocolosRede(codProtocoloRede) {
    Swal.fire({
      title: 'Você tem certeza que deseja remover?',
      text: "Você não poderá reverter após a confirmação",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {

      if (result.value) {
        $.ajax({
          url: '<?php echo base_url('integracoes/removeProtocolosRede') ?>',
          type: 'post',
          data: {
            codProtocoloRede: codProtocoloRede,
            csrf_sandra: $("#csrf_sandraPrincipal").val(),
          },
          dataType: 'json',
          success: function(response) {

            if (response.success === true) {
              Swal.fire({
                position: 'bottom-end',
                icon: 'success',
                title: response.messages,
                showConfirmButton: false,
                timer: 1500
              }).then(function() {
                $('#data_tableProtocolosRedeModel').DataTable().ajax.reload(null, false).draw(false);
              })
            } else {
              Swal.fire({
                position: 'bottom-end',
                icon: 'error',
                title: response.messages,
                showConfirmButton: false,
                timer: 1500
              })


            }
          }
        });
      }
    })
  }
</script>


<script>
  function addMapeamentoAtributosLDAPModel() {
    // reset the form 
    $("#add-formMapeamentoAtributosLDAPModel")[0].reset();

    $("#add-formMapeamentoAtributosLDAPModel #codServidorLDAP").val(codServidorLDAPtmp);
    $("#add-formMapeamentoAtributosLDAPModel #nomeAtributoLDAPAdd").val();

    $(".form-control").removeClass('is-invalid').removeClass('is-valid');
    $('#add-modalMapeamentoAtributosLDAPModel').modal('show');





    // submit the add from 
    $.validator.setDefaults({
      highlight: function(element) {
        $(element).addClass('is-invalid').removeClass('is-valid');
      },
      unhighlight: function(element) {
        $(element).removeClass('is-invalid').addClass('is-valid');
      },
      errorElement: 'div ',
      errorClass: 'invalid-feedback',
      errorPlacement: function(error, element) {
        if (element.parent('.input-group').length) {
          error.insertAfter(element.parent());
        } else if ($(element).is('.select')) {
          element.next().after(error);
        } else if (element.hasClass('select2')) {
          //error.insertAfter(element);
          error.insertAfter(element.next());
        } else if (element.hasClass('selectpicker')) {
          error.insertAfter(element.next());
        } else {
          error.insertAfter(element);
        }
      },

      submitHandler: function(form) {

        var form = $('#add-formMapeamentoAtributosLDAPModel');
        // remove the text-danger
        $(".text-danger").remove();

        $.ajax({
          url: '<?php echo base_url('integracoes/addMapeamentoAtributosLDAP') ?>',
          type: 'post',
          data: form.serialize(), // /converting the form data into array and sending it to server
          dataType: 'json',
          beforeSend: function() {
            //$('#add-formMapeamentoAtributosLDAPModel-btn').html('<i class="fa fa-spinner fa-spin"></i>');
          },
          success: function(response) {

            if (response.success === true) {

              Swal.fire({
                position: 'bottom-end',
                icon: 'success',
                title: response.messages,
                showConfirmButton: false,
                timer: 1500
              }).then(function() {
                $('#data_tablemapeamentoAtributosLDAP').DataTable().ajax.reload(null, false).draw(false);
                $('#add-modalMapeamentoAtributosLDAPModel').modal('hide');
              })

            } else {

              if (response.messages instanceof Object) {
                $.each(response.messages, function(index, value) {
                  var id = $("#" + index);

                  id.closest('.form-control')
                    .removeClass('is-invalid')
                    .removeClass('is-valid')
                    .addClass(value.length > 0 ? 'is-invalid' : 'is-valid');

                  id.after(value);

                });
              } else {
                Swal.fire({
                  position: 'bottom-end',
                  icon: 'error',
                  title: response.messages,
                  showConfirmButton: false,
                  timer: 1500
                })

              }
            }
            $('#add-formMapeamentoAtributosLDAPModel-btn').html('Adicionar');
          }
        });

        return false;
      }
    });
    $('#add-formMapeamentoAtributosLDAPModel').validate();
  }

  function editmapeamentoAtributosLDAP(codMapAttrLDAP) {
    $.ajax({
      url: '<?php echo base_url('integracoes/getOneMapeamentoAtributosLDAP') ?>',
      type: 'post',
      data: {
        codMapAttrLDAP: codMapAttrLDAP,
        csrf_sandra: $("#csrf_sandraPrincipal").val(),
      },
      dataType: 'json',
      success: function(response) {
        // reset the form 
        $("#edit-formMapeamentoAtributosLDAP")[0].reset();
        $(".form-control").removeClass('is-invalid').removeClass('is-valid');
        $('#edit-modalMapeamentoAtributosLDAPModel').modal('show');

        $("#edit-formMapeamentoAtributosLDAP #codMapAttrLDAP").val(response.codMapAttrLDAP);
        $("#edit-formMapeamentoAtributosLDAP #codServidorLDAP").val(response.codServidorLDAP);
        $("#edit-formMapeamentoAtributosLDAP #nomeAtributoSistema").val(response.nomeAtributoSistema);
        //$("#edit-formMapeamentoAtributosLDAP #nomeAtributoLDAP").val(response.nomeAtributoLDAP);

        $.ajax({
          url: '<?php echo base_url('integracoes/atributosLDAP') ?>',
          type: 'post',
          data: {
            codTipoLDAP: response.codTipoLDAP,
            csrf_sandra: $("#csrf_sandraPrincipal").val(),
          },
          dataType: 'json',
          success: function(atributos) {

            $("#nomeAtributoLDAPEdit").select2({
              data: atributos,
            }).val(response.nomeAtributoLDAP).trigger("change");

          }
        })

        // submit the edit from 
        $.validator.setDefaults({
          highlight: function(element) {
            $(element).addClass('is-invalid').removeClass('is-valid');
          },
          unhighlight: function(element) {
            $(element).removeClass('is-invalid').addClass('is-valid');
          },
          errorElement: 'div ',
          errorClass: 'invalid-feedback',
          errorPlacement: function(error, element) {
            if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
            } else if ($(element).is('.select')) {
              element.next().after(error);
            } else if (element.hasClass('select2')) {
              //error.insertAfter(element);
              error.insertAfter(element.next());
            } else if (element.hasClass('selectpicker')) {
              error.insertAfter(element.next());
            } else {
              error.insertAfter(element);
            }
          },

          submitHandler: function(form) {
            var form = $('#edit-formMapeamentoAtributosLDAP');
            $(".text-danger").remove();
            $.ajax({
              url: '<?php echo base_url('integracoes/editMapeamentoAtributosLDAP') ?>',
              type: 'post',
              data: form.serialize(),
              dataType: 'json',
              beforeSend: function() {
                //$('#edit-form-btn').html('<i class="fa fa-spinner fa-spin"></i>');
              },
              success: function(response) {

                if (response.success === true) {

                  Swal.fire({
                    position: 'bottom-end',
                    icon: 'success',
                    title: response.messages,
                    showConfirmButton: false,
                    timer: 1500
                  }).then(function() {
                    $('#data_tablemapeamentoAtributosLDAP').DataTable().ajax.reload(null, false).draw(false);
                    $('#edit-modalMapeamentoAtributosLDAPModel').modal('hide');
                  })

                } else {

                  if (response.messages instanceof Object) {
                    $.each(response.messages, function(index, value) {
                      var id = $("#" + index);

                      id.closest('.form-control')
                        .removeClass('is-invalid')
                        .removeClass('is-valid')
                        .addClass(value.length > 0 ? 'is-invalid' : 'is-valid');

                      id.after(value);

                    });
                  } else {
                    Swal.fire({
                      position: 'bottom-end',
                      icon: 'error',
                      title: response.messages,
                      showConfirmButton: false,
                      timer: 1500
                    })

                  }
                }
                $('#edit-form-btn').html('Salvar');
              }
            });

            return false;
          }
        });
        $('#edit-formMapeamentoAtributosLDAP').validate();

      }
    });
  }

  function removemapeamentoAtributosLDAP(codMapAttrLDAP) {
    Swal.fire({
      title: 'Você tem certeza que deseja remover?',
      text: "Você não poderá reverter após a confirmação",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {

      if (result.value) {
        $.ajax({
          url: '<?php echo base_url('integracoes/removeMapeamentoAtributosLDAP') ?>',
          type: 'post',
          data: {
            codMapAttrLDAP: codMapAttrLDAP,
            csrf_sandra: $("#csrf_sandraPrincipal").val(),
          },
          dataType: 'json',
          success: function(response) {

            if (response.success === true) {
              Swal.fire({
                position: 'bottom-end',
                icon: 'success',
                title: response.messages,
                showConfirmButton: false,
                timer: 1500
              }).then(function() {
                $('#data_tablemapeamentoAtributosLDAP').DataTable().ajax.reload(null, false).draw(false);
              })
            } else {
              Swal.fire({
                position: 'bottom-end',
                icon: 'error',
                title: response.messages,
                showConfirmButton: false,
                timer: 1500
              })


            }
          }
        });
      }
    })
  }
</script>

<script>
  $(function() {
    $('#data_tableservicosSMS').DataTable({
      "scrollX": true,
      "paging": true,
      "lengthChange": true,
      "searching": false,
      "ordering": true,
      "info": false,
      "autoWidth": true,
      "responsive": true,
      "ajax": {
        "url": '<?php echo base_url('integracoes/getAllSMS') ?>',
        "type": "POST",
        "dataType": "json",
        async: "true",
        data: {
          csrf_sandra: $("#csrf_sandraPrincipal").val(),
        },
      }
    });
  });

  function addservicosSMS() {
    // reset the form 
    $("#add-formservicosSMS")[0].reset();
    $(".form-control").removeClass('is-invalid').removeClass('is-valid');
    $('#add-modalservicosSMS').modal('show');
    // submit the add from 
    $.validator.setDefaults({
      highlight: function(element) {
        $(element).addClass('is-invalid').removeClass('is-valid');
      },
      unhighlight: function(element) {
        $(element).removeClass('is-invalid').addClass('is-valid');
      },
      errorElement: 'div ',
      errorClass: 'invalid-feedback',
      errorPlacement: function(error, element) {
        if (element.parent('.input-group').length) {
          error.insertAfter(element.parent());
        } else if ($(element).is('.select')) {
          element.next().after(error);
        } else if (element.hasClass('select2')) {
          //error.insertAfter(element);
          error.insertAfter(element.next());
        } else if (element.hasClass('selectpicker')) {
          error.insertAfter(element.next());
        } else {
          error.insertAfter(element);
        }
      },

      submitHandler: function(form) {

        var form = $('#add-formservicosSMS');
        // remove the text-danger
        $(".text-danger").remove();

        $.ajax({
          url: '<?php echo base_url('integracoes/addSMS') ?>',
          type: 'post',
          data: form.serialize(), // /converting the form data into array and sending it to server
          dataType: 'json',
          beforeSend: function() {
            //$('#add-formservicosSMS-btn').html('<i class="fa fa-spinner fa-spin"></i>');
          },
          success: function(response) {

            if (response.success === true) {

              Swal.fire({
                position: 'bottom-end',
                icon: 'success',
                title: response.messages,
                showConfirmButton: false,
                timer: 1500
              }).then(function() {
                $('#data_tableservicosSMS').DataTable().ajax.reload(null, false).draw(false);
                $('#add-modalservicosSMS').modal('hide');
              })

            } else {

              if (response.messages instanceof Object) {
                $.each(response.messages, function(index, value) {
                  var id = $("#" + index);

                  id.closest('.form-control')
                    .removeClass('is-invalid')
                    .removeClass('is-valid')
                    .addClass(value.length > 0 ? 'is-invalid' : 'is-valid');

                  id.after(value);

                });
              } else {
                Swal.fire({
                  position: 'bottom-end',
                  icon: 'error',
                  title: response.messages,
                  showConfirmButton: false,
                  timer: 1500
                })

              }
            }
            $('#add-formservicosSMS-btn').html('Adicionar');
          }
        });

        return false;
      }
    });
    $('#add-formservicosSMS').validate();
  }

  function editservicosSMS(codServicoSMS) {
    $.ajax({
      url: '<?php echo base_url('integracoes/getOneSMS') ?>',
      type: 'post',
      data: {
        codServicoSMS: codServicoSMS,
        csrf_sandra: $("#csrf_sandraPrincipal").val(),
      },
      dataType: 'json',
      success: function(response) {
        // reset the form 
        $("#edit-formservicosSMS")[0].reset();
        $(".form-control").removeClass('is-invalid').removeClass('is-valid');
        $('#edit-modalservicosSMS').modal('show');

        $("#edit-formservicosSMS #codServicoSMS").val(response.codServicoSMS);
        $("#edit-formservicosSMS #codOrganizacao").val(response.codOrganizacao);
        $("#edit-formservicosSMS #codProvedor").val(response.codProvedor);
        $("#edit-formservicosSMS #token").val(response.token);
        $("#edit-formservicosSMS #conta").val(response.conta);
        $("#edit-formservicosSMS #creditos").val(response.creditos);
        $("#edit-formservicosSMS #statusSMS").val(response.statusSMS);

        // submit the edit from 
        $.validator.setDefaults({
          highlight: function(element) {
            $(element).addClass('is-invalid').removeClass('is-valid');
          },
          unhighlight: function(element) {
            $(element).removeClass('is-invalid').addClass('is-valid');
          },
          errorElement: 'div ',
          errorClass: 'invalid-feedback',
          errorPlacement: function(error, element) {
            if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
            } else if ($(element).is('.select')) {
              element.next().after(error);
            } else if (element.hasClass('select2')) {
              //error.insertAfter(element);
              error.insertAfter(element.next());
            } else if (element.hasClass('selectpicker')) {
              error.insertAfter(element.next());
            } else {
              error.insertAfter(element);
            }
          },

          submitHandler: function(form) {
            var form = $('#edit-formservicosSMS');
            $(".text-danger").remove();
            $.ajax({
              url: '<?php echo base_url('integracoes/editSMS') ?>',
              type: 'post',
              data: form.serialize(),
              dataType: 'json',
              beforeSend: function() {
                //$('#edit-formservicosSMS-btn').html('<i class="fa fa-spinner fa-spin"></i>');
              },
              success: function(response) {

                if (response.success === true) {

                  Swal.fire({
                    position: 'bottom-end',
                    icon: 'success',
                    title: response.messages,
                    showConfirmButton: false,
                    timer: 1500
                  }).then(function() {
                    $('#data_tableservicosSMS').DataTable().ajax.reload(null, false).draw(false);
                    $('#edit-modalservicosSMS').modal('hide');
                  })

                } else {

                  if (response.messages instanceof Object) {
                    $.each(response.messages, function(index, value) {
                      var id = $("#" + index);

                      id.closest('.form-control')
                        .removeClass('is-invalid')
                        .removeClass('is-valid')
                        .addClass(value.length > 0 ? 'is-invalid' : 'is-valid');

                      id.after(value);

                    });
                  } else {
                    Swal.fire({
                      position: 'bottom-end',
                      icon: 'error',
                      title: response.messages,
                      showConfirmButton: false,
                      timer: 1500
                    })

                  }
                }
                $('#edit-formservicosSMS-btn').html('Salvar');
              }
            });

            return false;
          }
        });
        $('#edit-formservicosSMS').validate();

      }
    });
  }

  function removeservicosSMS(codServicoSMS) {
    Swal.fire({
      title: 'Você tem certeza que deseja remover?',
      text: "Você não poderá reverter após a confirmação",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {

      if (result.value) {
        $.ajax({
          url: '<?php echo base_url('integracoes/removeSMS') ?>',
          type: 'post',
          data: {
            codServicoSMS: codServicoSMS,
            csrf_sandra: $("#csrf_sandraPrincipal").val(),
          },
          dataType: 'json',
          success: function(response) {

            if (response.success === true) {
              Swal.fire({
                position: 'bottom-end',
                icon: 'success',
                title: response.messages,
                showConfirmButton: false,
                timer: 1500
              }).then(function() {
                $('#data_tableservicosSMS').DataTable().ajax.reload(null, false).draw(false);
              })
            } else {
              Swal.fire({
                position: 'bottom-end',
                icon: 'error',
                title: response.messages,
                showConfirmButton: false,
                timer: 1500
              })


            }
          }
        });
      }
    })
  }
</script>