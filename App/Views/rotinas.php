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
    <div class="col-md-12">
      <div class="card card-primary">
        <div class="card-header">
          <h3 Style="font-size:20px;font-weight:bold" class="card-title">ROTINAS GENÉRICAS</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">



        </div>
      </div>
    </div>

  </div>




  <div class="row">
    <div class="col-md-12">
      <div class="card card-primary">
        <div class="card-header">
          <h3 Style="font-size:20px;font-weight:bold" class="card-title">ROTINAS MIGRAÇÃO APOLO</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">


          <div class="row">
            <div class="col-sm-2">
              <button type="button" class="btn btn-lg btn-primary" onclick="desativado_importarPessoasApolo()" title="Importar Pacientes"> <i class="fas fa-file-import"></i> Importar Pessoas</button>
            </div>
            <div class="col-sm-2">
              <button type="button" class="btn btn-lg btn-primary" onclick="desativado_importarPacientes()" title="Importar Pacientes"> <i class="fas fa-file-import"></i> Importar Prontuários</button>
            </div>
            <div class="col-sm-2">
              <button type="button" class="btn btn-lg btn-primary" onclick="desativado_importarContatos()" title="Importar Contatos"> <i class="fas fa-file-import"></i> Importar Contatos</button>
            </div>
            <div class="col-sm-2">
              <button type="button" class="btn btn-lg btn-primary" onclick="desativado_importarEspecialidades()" title="Importar Contatos"> <i class="fas fa-file-import"></i> Importar Especialidades</button>
            </div>
            <div class="col-sm-2">
              <button type="button" class="btn btn-lg btn-primary" onclick="diferencaEsquema()" title="Diferença Esquema"> <i class="fas fa-file-import"></i> Diferença Esquema</button>
            </div>
            <div class="col-sm-2">
              <button type="button" class="btn btn-lg btn-primary" onclick="desativado_lookupEspecialistasApolo()" title="Diferença Esquema"> <i class="fas fa-file-import"></i>Lookup Especialistas</button>
            </div>
          </div>

          <div style="margin-top:20px" class="row">

            <div class="col-sm-2">
              <button type="button" class="btn btn-lg btn-primary" onclick="desativado_importarAgendas()" title="Importar Agendas"> <i class="fas fa-file-import"></i>Importar Agendas</button>
            </div>
            <div class="col-sm-2">
              <button type="button" class="btn btn-lg btn-primary" onclick="desativado_importarItensHospitalares()" title="Importar Itens Hospitalares"> <i class="fas fa-capsules"></i></i>Importar Itens Hospitalares</button>
            </div>
            <div class="col-sm-2">
              <button type="button" class="btn btn-lg btn-primary" onclick="desativado_importarAtendimentosAmbulatorios()" title="Importar Atendimentos Ambulatórios"> <i class="fas fa-capsules"></i></i>Importar Atendimentos
                Ambulatórios</button>
            </div>
            <div class="col-sm-2">
              <button type="button" class="btn btn-lg btn-primary" onclick="redefineSenhaTodos()" title="Redefine Senha de todos os Pacientes (PREC)"> <i class="fas fa-capsules"></i></i>Redefine Senha de todos
                os Pacientes (PREC)</button>
            </div>

            <div class="col-sm-2">
              <button type="button" class="btn btn-lg btn-primary" onclick="desativado_atualizaPrecPacientes()" title="Importar Pacientes"> <i class="fas fa-file-import"></i> Atualiza Prec</button>
            </div>
            <div class="col-sm-2">
              <button type="button" class="btn btn-lg btn-primary" onclick="desativado_sincronizaKits()" title="Importar Kits">
                <i class="fas fa-file-import"></i>Sincroniza Kits</button>
            </div>
          </div>

          <div style="margin-top:20px" class="row">

            <div class="col-sm-2">
              <button type="button" class="btn btn-lg btn-primary" onclick="desativado_importarPrescricoesEPrescricoes()" title="Importar Kits"> <i class="fas fa-file-import"></i>Importar Prescrições e Evoluções</button>
            </div>
            <div class="col-sm-2">
              <button type="button" class="btn btn-lg btn-primary" onclick="importarFornecedores()" title="Importar Fornecedores"> <i class="fas fa-file-import"></i>Importar Fornecedores</button>
            </div>
            <div class="col-sm-2">
              <button type="button" class="btn btn-lg btn-primary" onclick="importarRequisicoes()" title="Importar Fornecedores"> <i class="fas fa-file-import"></i>Importar Requisições</button>
            </div>
          </div>



        </div>
      </div>
    </div>

  </div>


  <div class="row">
    <div class="col-md-12">
      <div class="card card-primary">
        <div class="card-header">
          <h3 Style="font-size:20px;font-weight:bold" class="card-title">ROTINAS MIGRAÇÃO SIGH</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">

          <div style="margin-top:20px" class="row">


            <div class="col-sm-2">
              <button type="button" class="btn btn-lg btn-primary" onclick="importarColaboradores()" title="Importar Colaboradores"> <i class="fas fa-file-import"></i>Importar Colaboradores</button>
            </div>

            <div class="col-sm-2">
              <button type="button" class="btn btn-lg btn-primary" onclick="ajustarLookups()" title="Ajustar Lookups"> <i class="fas fa-file-import"></i>Ajustar Lookups</button>
            </div>

            <div class="col-sm-2">
              <button type="button" class="btn btn-lg btn-primary" onclick="importarPacientesSIGH()" title="Importar Agendas"> <i class="fas fa-file-import"></i>Importar Pacientes SIGH</button>
            </div>

            <div class="col-sm-2">
              <button type="button" class="btn btn-lg btn-primary" onclick="importarAgendasSIGH()" title="Importar Agendas"> <i class="fas fa-file-import"></i>Importar Agendas SIGH</button>
            </div>

            <div class="col-sm-2">
              <button type="button" class="btn btn-lg btn-primary" onclick="importarAmbulatorioSIGH()" title="Importar Ambulatório"> <i class="fas fa-file-import"></i>Importar Ambulatório</button>
            </div>

            <div class="col-sm-2">
              <button type="button" class="btn btn-lg btn-primary" onclick="importarBoletinsSIGH()" title="Importar Boletim/Emergência"> <i class="fas fa-file-import"></i>Importar Boletim/Emergência</button>
            </div>

          </div>

        </div>
      </div>
    </div>

  </div>



</section>

<div id="lookupEspecialistasModal" class="modal fade" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-primary text-center p-3">
        <h4 class="modal-title text-white" id="info-header-modalLabel">Desativação de Pessoa</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">

        <form id="confirmacaoDesativacaoForm" class="pl-3 pr-3">
          <input type="hidden" id="<?php echo csrf_token() ?>confirmacaoDesativacaoForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

          <button type="button" onclick="desativado_salvarLink()" class="btn btn-xs btn-success" id="add-form-pessoa-btn">Salvar</button>

          <div id="lookupEspecialistasForm"></div>

          <button type="button" onclick="desativado_salvarLink()" class="btn btn-xs btn-success" id="add-form-pessoa-btn">Salvar</button>
        </form>



      </div>
    </div>
  </div>
</div>



<div id="diferencaEsquemaModal" class="modal fade" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-primary text-center p-3">
        <h4 class="modal-title text-white" id="info-header-modalLabel">Desativação de Pessoa</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="alteracoesNoEsquema"></div>
      </div>
    </div>
  </div>
</div>



<div id="federacoesModal" class="modal fade" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-primary text-center p-3">
        <h4 class="modal-title text-white" id="info-header-modalLabel">Federações</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <table id="data_tablefederacoes" class="table table-striped table-hover table-sm">
          <thead>
            <tr>
              <th>Código</th>
              <th>Decrição</th>
              <th>Servidor</th>
              <th>Banco</th>
              <th>Login</th>
              <th>Senha</th>
              <th></th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>





<div id="ajustarLookupsModal" class="modal fade" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-primary text-center p-3">
        <h4 class="modal-title text-white" id="info-header-modalLabel">Ajustar tabelas de Lookups</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="col-12 col-sm-12">
          <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
              <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Especialidades</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Pessoas</a>
                </li>
              </ul>
            </div>
            <div class="card-body">
              <div class="tab-content" id="custom-tabs-one-tabContent">
                <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">

                  <form id="especialidadeLookupForm" class="pl-3 pr-3">
                    <input type="hidden" id="<?php echo csrf_token() ?>especialidadeLookupForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                    <div class="row">
                      <div>
                        <button type="button" class="btn btn-primary" onclick="gravaEspecialidadesLoockup()" title="Salvar">Salvar</button>
                      </div>
                    </div>

                    <table id="data_tableespecialidades" class="table table-striped table-hover table-sm">
                      <thead>
                        <tr>
                          <th>ID SIGH</th>
                          <th>Especialidade SIGH</th>
                          <th>Especialidade SANDRA</th>
                        </tr>
                      </thead>
                    </table>

                    <div class="row">
                      <div>
                        <button type="button" class="btn btn-primary" onclick="gravaEspecialidadesLoockup()" title="Salvar">Salvar</button>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">

                  <form id="pessoasLookupForm" class="pl-3 pr-3">
                    <input type="hidden" id="<?php echo csrf_token() ?>pessoasLookupForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                    <div class="row">
                      <div>
                        <button type="button" class="btn btn-primary" onclick="gravaPessoasLoockup()" title="Salvar">Salvar</button>
                      </div>
                    </div>
                    <table id="data_tablepesoas" class="table table-striped table-hover table-sm">
                      <thead>
                        <tr>
                          <th>ID PESSOA</th>
                          <th>Pessoa SIGH</th>
                          <th>Pessoa SANDRA</th>
                        </tr>
                      </thead>
                    </table>

                    <div class="row">
                      <div>
                        <button type="button" class="btn btn-primary" onclick="gravaPessoasLoockup()" title="Salvar">Salvar</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<?php
echo view('tema/rodape');
?>


<script>
  function gravaEspecialidadesLoockup() {


    var form = $('#especialidadeLookupForm');

    $.ajax({
      url: '<?php echo base_url('rotinas/salvarEspecialidadeLookup') ?>',
      type: 'post',
      data: form.serialize(), // /converting the form data into array and sending it to server
      dataType: 'json',
      success: function(salvarEspecialidadeLookpup) {

        if (salvarEspecialidadeLookpup.success === true) {

          Swal.fire({
            position: 'bottom-end',
            icon: 'success',
            html: salvarEspecialidadeLookpup.messages,
            showConfirmButton: true,
            confirmButtonText: 'Ok',

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

  function gravaPessoasLoockup() {


    var form = $('#pessoasLookupForm');

    $.ajax({
      url: '<?php echo base_url('rotinas/salvarPessoaLookup') ?>',
      type: 'post',
      data: form.serialize(), // /converting the form data into array and sending it to server
      dataType: 'json',
      success: function(salvarPessoaLookup) {

        if (salvarPessoaLookup.success === true) {

          Swal.fire({
            position: 'bottom-end',
            icon: 'success',
            html: salvarPessoaLookup.messages,
            showConfirmButton: true,
            confirmButtonText: 'Ok',

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

  function salvarLink() {


    var form = $('#confirmacaoDesativacaoForm');

    $.ajax({
      url: '<?php echo base_url('rotinas/salvarReferenciaLookup') ?>',
      type: 'post',
      data: form.serialize(), // /converting the form data into array and sending it to server
      dataType: 'json',
      success: function(salvarLink) {

        if (salvarLink.success === true) {

          Swal.fire({
            position: 'bottom-end',
            icon: 'success',
            html: salvarLink.messages,
            showConfirmButton: true,
            confirmButtonText: 'Ok',

          })

        }

      }
    })

  }

  function importarPacientes() {


    Swal.fire({
      title: 'Deseja importar dados de pacientes do sistema anterior?',
      text: "Esta ação irá trazer dados de pacientes para o sistema atual locais",
      icon: 'info',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.value) {




        //EXECUTA JSON DE IMPORTAÇÃO
        $.ajax({
          url: '<?php echo base_url('pacientes/importarPacientes/') ?>',
          type: 'post',
          dataType: 'json',
          data: {
            csrf_sandra: $("#csrf_sandraPrincipal").val(),
          },
          success: function(response) {
            if (response.success === true) {

              Swal.fire({
                position: 'bottom-end',
                icon: 'success',
                html: response.messages,
                showConfirmButton: true,
                confirmButtonText: 'Ok',

              }).then(function() {

                // $('#data_tablepaciente').DataTable().ajax.reload(null, false).draw(false);
                //$('#editPacienteLogada').modal('hide');
              })


            }

            if (response.erro === true) {

              Swal.fire({
                position: 'bottom-end',
                icon: 'error',
                title: response.messages,
                showConfirmButton: false,
                timer: 5000
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


      } else {
        Swal.fire({
          title: 'Cancelado!',
          text: "Tenha certeza da operação a ser realizada",
          icon: 'info',
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Fechar',
          timer: 3000,
        })
      }
    })

  }

  function ajustarLookups() {


    $('#ajustarLookupsModal').modal('show');


    $('#data_tableespecialidades').DataTable({
      "bDestroy": true,
      "paging": false,
      "deferRender": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "ajax": {
        "url": '<?php echo base_url('rotinas/ajustarLookupsEspecialidades') ?>',
        "type": "POST",
        "dataType": "json",
        async: "true",
        data: {
          csrf_sandra: $("#csrf_sandraPrincipal").val(),
        },
      }
    });


    $('#data_tablepesoas').DataTable({
      "bDestroy": true,
      "paging": false,
      "deferRender": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "ajax": {
        "url": '<?php echo base_url('rotinas/ajustarLookupsPessoas') ?>',
        "type": "POST",
        "dataType": "json",
        async: "true",
        data: {
          csrf_sandra: $("#csrf_sandraPrincipal").val(),
        },
      }
    });
  }


  function importarPacientesSIGH() {


    Swal.fire({
      title: 'Deseja importar dados de pacientes do sistema anterior?',
      text: "Esta ação irá trazer dados de pacientes para o sistema atual locais",
      icon: 'info',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.value) {




        //EXECUTA JSON DE IMPORTAÇÃO
        $.ajax({
          url: '<?php echo base_url('pacientes/importarPacientesSIGH/') ?>',
          type: 'post',
          dataType: 'json',
          data: {
            csrf_sandra: $("#csrf_sandraPrincipal").val(),
          },
          success: function(response) {
            if (response.success === true) {

              Swal.fire({
                position: 'bottom-end',
                icon: 'success',
                html: response.messages,
                showConfirmButton: true,
                confirmButtonText: 'Ok',

              }).then(function() {

                // $('#data_tablepaciente').DataTable().ajax.reload(null, false).draw(false);
                //$('#editPacienteLogada').modal('hide');
              })


            }

            if (response.erro === true) {

              Swal.fire({
                position: 'bottom-end',
                icon: 'error',
                title: response.messages,
                showConfirmButton: false,
                timer: 5000
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


      } else {
        Swal.fire({
          title: 'Cancelado!',
          text: "Tenha certeza da operação a ser realizada",
          icon: 'info',
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Fechar',
          timer: 3000,
        })
      }
    })

  }

  function importarColaboradores() {


    Swal.fire({
      title: 'Deseja importar dados de pessoas colaboradoras do sistema anterior?',
      text: "Esta ação irá trazer dados de pessoas para o sistema atual locais",
      icon: 'info',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.value) {




        //EXECUTA JSON DE IMPORTAÇÃO
        $.ajax({
          url: '<?php echo base_url('rotinas/importarPessoasSIGH/') ?>',
          type: 'post',
          dataType: 'json',
          data: {
            csrf_sandra: $("#csrf_sandraPrincipal").val(),
          },
          success: function(response) {
            if (response.success === true) {

              Swal.fire({
                position: 'bottom-end',
                icon: 'success',
                html: response.messages,
                showConfirmButton: true,
                confirmButtonText: 'Ok',

              }).then(function() {

                // $('#data_tablepaciente').DataTable().ajax.reload(null, false).draw(false);
                //$('#editPacienteLogada').modal('hide');
              })


            }

            if (response.erro === true) {

              Swal.fire({
                position: 'bottom-end',
                icon: 'error',
                title: response.messages,
                showConfirmButton: false,
                timer: 5000
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


      } else {
        Swal.fire({
          title: 'Cancelado!',
          text: "Tenha certeza da operação a ser realizada",
          icon: 'info',
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Fechar',
          timer: 3000,
        })
      }
    })

  }

  function atualizaPrecPacientes() {

    /*
        Swal.fire({
          title: 'Deseja importar dados de pacientes do sistema anterior?',
          text: "Esta ação irá trazer dados de pacientes para o sistema atual locais",
          icon: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Confirmar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.value) {




            //EXECUTA JSON DE IMPORTAÇÃO
            $.ajax({
              url: '<?php echo base_url('pacientes/atualizaPrecPacientes/') ?>',
    type: 'post',
      dataType: 'json',
        data: {
      csrf_sandra: $("#csrf_sandraPrincipal").val(),
              },
    success: function(response) {
      if (response.success === true) {

        Swal.fire({
          position: 'bottom-end',
          icon: 'success',
          html: response.messages,
          showConfirmButton: true,
          confirmButtonText: 'Ok',

        }).then(function () {

          // $('#data_tablepaciente').DataTable().ajax.reload(null, false).draw(false);
          //$('#editPacienteLogada').modal('hide');
        })


      }

      if (response.erro === true) {

        Swal.fire({
          position: 'bottom-end',
          icon: 'error',
          title: response.messages,
          showConfirmButton: false,
          timer: 5000
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


          } else {
    Swal.fire({
      title: 'Cancelado!',
      text: "Tenha certeza da operação a ser realizada",
      icon: 'info',
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Fechar',
      timer: 3000,
    })
  }
        })
    * /
  }


  function importarContatos() {


    Swal.fire({
      title: 'Deseja importar dados de contatos dos pacientes do sistema anterior?',
      text: "Esta ação irá trazer dados de pacientes para o sistema atual locais",
      icon: 'info',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.value) {




        //EXECUTA JSON DE IMPORTAÇÃO
        $.ajax({
          url: '<?php echo base_url('pacientes/importarContatos/') ?>',
          type: 'post',
          dataType: 'json',
          data: {
            csrf_sandra: $("#csrf_sandraPrincipal").val(),
          },
          success: function (response) {
            if (response.success === true) {

              Swal.fire({
                position: 'bottom-end',
                icon: 'success',
                html: response.messages,
                showConfirmButton: true,
                confirmButtonText: 'Ok',

              }).then(function () {

                $('#data_tablepaciente').DataTable().ajax.reload(null, false).draw(false);
                //$('#editPacienteLogada').modal('hide');
              })


            }

            if (response.erro === true) {

              Swal.fire({
                position: 'bottom-end',
                icon: 'error',
                title: response.messages,
                showConfirmButton: false,
                timer: 5000
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


      } else {
        Swal.fire({
          title: 'Cancelado!',
          text: "Tenha certeza da operação a ser realizada",
          icon: 'info',
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Fechar',
          timer: 3000,
        })
      }
    })

  }



  function importarEspecialidades() {


    Swal.fire({
      title: 'Deseja importar dados de especialidades do sistema anterior?',
      icon: 'info',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.value) {




        //EXECUTA JSON DE IMPORTAÇÃO
        $.ajax({
          url: '<?php echo base_url('especialidades/importarEspecialidades/') ?>',
          type: 'post',
          dataType: 'json',
          data: {
            csrf_sandra: $("#csrf_sandraPrincipal").val(),
          },
          success: function (response) {
            if (response.success === true) {

              Swal.fire({
                position: 'bottom-end',
                icon: 'success',
                html: response.messages,
                showConfirmButton: true,
                confirmButtonText: 'Ok',

              })


            }

            if (response.erro === true) {

              Swal.fire({
                position: 'bottom-end',
                icon: 'error',
                title: response.messages,
                showConfirmButton: false,
                timer: 5000
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


      } else {
        Swal.fire({
          title: 'Cancelado!',
          text: "Tenha certeza da operação a ser realizada",
          icon: 'info',
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Fechar',
          timer: 3000,
        })
      }
    })

  }


  function importarPessoasApolo() {

    Swal.fire({
      position: 'bottom-end',
      icon: 'warning',
      title: 'Funcionalidade desativada porque já cumpriu sua finalidade',
      html: 'Novas pessoas devem ser adicionadas manualmente no sistema',
      showConfirmButton: false,
      timer: 4000
    })

    /*
        Swal.fire({
          title: 'Deseja importar as pessoas/funcionários do sistema anterior?',
          icon: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Confirmar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.value) {




            //EXECUTA JSON DE IMPORTAÇÃO
            $.ajax({
              url: '<?php echo base_url('pessoas/importarPessoasApolo/') ?>',
    type: 'post',
      dataType: 'json',
        success: function(response) {
          if (response.success === true) {

            Swal.fire({
              position: 'bottom-end',
              icon: 'success',
              html: response.messages,
              showConfirmButton: true,
              confirmButtonText: 'Ok',

            })


          }

          if (response.erro === true) {

            Swal.fire({
              position: 'bottom-end',
              icon: 'error',
              title: response.messages,
              showConfirmButton: false,
              timer: 5000
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


          } else {
    Swal.fire({
      title: 'Cancelado!',
      text: "Tenha certeza da operação a ser realizada",
      icon: 'info',
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Fechar',
      timer: 3000,
    })
  }
        })

        * /

  }


  function lookupEspecialistasApolo() {

    //EXECUTA JSON DE IMPORTAÇÃO
    $.ajax({
      url: '<?php echo base_url('rotinas/lookupEspecialistasApolo') ?>',
      type: 'post',
      dataType: 'json',
      data: {
        csrf_sandra: $("#csrf_sandraPrincipal").val(),
      },
      success: function (responselookupEspecialistasApolo) {

        if (responselookupEspecialistasApolo.success === true) {
          $('#lookupEspecialistasModal').modal('show');
          Swal.close();
          document.getElementById('lookupEspecialistasForm').innerHTML = responselookupEspecialistasApolo.form;


        }

        $.ajax({
          url: '<?php echo base_url('pessoas/listaTodasPessoasAteDesativados') ?>',
          type: 'post',
          dataType: 'json',
          data: {
            csrf_sandra: $("#csrf_sandraPrincipal").val(),
          },
          success: function (pessoas) {


            $("select[id*='pessoa']").select2({
              data: pessoas,
            })

          }
        })

      }
    }).always(
      Swal.fire({
        title: 'Estamos processando sua requisição',
        html: 'Aguarde....',
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading()


        }
      })

    )

  }


  function importarAtendimentosAmbulatorios() {

    Swal.fire({
      position: 'bottom-end',
      icon: 'warning',
      title: 'Funcionalidade desativada',
      html: 'Não é possível remover pacientes do sistema.',
      showConfirmButton: false,
      timer: 4000
    })


    //EXECUTA JSON DE IMPORTAÇÃO

    /*
    $.ajax({
      url: '<php echo base_url('rotinas/importarAtendimentosAmbulatorios') ?>',
      type: 'post',
      dataType: 'json',
      data: {
        csrf_sandra: $("#csrf_sandraPrincipal").val(),
      },
      success: function(importarAtendimentosAmbulatorios) {

        if (importarAtendimentosAmbulatorios.success === true) {


          Swal.fire({
            position: 'bottom-end',
            icon: 'success',
            html: importarAtendimentosAmbulatorios.messages,
            showConfirmButton: true,
            confirmButtonText: 'Ok',

          })


        }


      }
    }).always(
      Swal.fire({
        title: 'Estamos importando os atendimentos ambulatóriais',
        html: 'Aguarde....',
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading()


        }
      })

    )

    */

  }


  function importarItensHospitalares_old() {


    Swal.fire({
      position: 'bottom-end',
      icon: 'warning',
      title: 'Funcionalidade desativada',
      showConfirmButton: false,
      timer: 4000
    })


    //EXECUTA JSON DE IMPORTAÇÃO
    $.ajax({
      url: '<?php echo base_url('rotinas/importarItensHospitalares') ?>',
      type: 'post',
      dataType: 'json',
      data: {
        csrf_sandra: $("#csrf_sandraPrincipal").val(),
      },
      success: function(importarItensFarmacia) {

        if (importarItensFarmacia.success === true) {


          Swal.fire({
            position: 'bottom-end',
            icon: 'success',
            html: importarItensFarmacia.messages,
            showConfirmButton: true,
            confirmButtonText: 'Ok',

          })


        }


      }
    }).always(
      Swal.fire({
        title: 'Estamos importando os Itens de Farmácia',
        html: 'Aguarde....',
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading()


        }
      })

    )


  }


  function diferencaEsquema() {


    $('#federacoesModal').modal('show');

    $('#data_tablefederacoes').DataTable({
      "paging": true,
      "bDestroy":true,
      "deferRender": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "ajax": {
        "url": '<?php echo base_url('federacoes/verificarEsquema') ?>',
        "type": "POST",
        "dataType": "json",
        async: "true",
        data: {
          csrf_sandra: $("#csrf_sandraPrincipal").val(),
        },
      }
    });


  }

  function diferencaEsquemaAgora(codFederacao) {

    $('#federacoesModal').modal('hide');
    //EXECUTA JSON DE IMPORTAÇÃO
    $.ajax({
      url: '<?php echo base_url('rotinas/diferencaEsquema') ?>',
      type: 'post',
      dataType: 'json',
      data: {
        codFederacao: codFederacao,
        csrf_sandra: $("#csrf_sandraPrincipal").val(),
      },
      success: function(responseDiferencaEsquema) {

        if (responseDiferencaEsquema.success === true) {
          $('#diferencaEsquemaModal').modal('show');



          Swal.close();
          document.getElementById('alteracoesNoEsquema').innerHTML = responseDiferencaEsquema.alteracoes;


        }

        if (responseDiferencaEsquema.erro === true) {

          Swal.fire({
            position: 'bottom-end',
            icon: 'error',
            title: responseDiferencaEsquema.messages,
            showConfirmButton: false,
            timer: 5000
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
      })

    )

  }


  function importarPrescricoesEPrescricoes_old() {


    Swal.fire({
      position: 'bottom-end',
      icon: 'warning',
      title: 'Funcionalidade desativada',
      showConfirmButton: false,
      timer: 4000
    })

    //EXECUTA JSON DE IMPORTAÇÃO
    $.ajax({
      url: '<?php echo base_url('rotinas/importarPrescricoesEPrescricoes') ?>',
      type: 'post',
      dataType: 'json',
      data: {
        csrf_sandra: $("#csrf_sandraPrincipal").val(),
      },
      success: function(importarPrescricoesEPrescricoes) {

        if (importarPrescricoesEPrescricoes.success === true) {


          Swal.fire({
            position: 'bottom-end',
            icon: 'success',
            html: importarPrescricoesEPrescricoes.messages,
            showConfirmButton: true,
            confirmButtonText: 'Ok',

          })

        }


      }
    }).always(
      Swal.fire({
        title: 'Estamos Importando as prescrições e Evoluçõs',
        html: 'Aguarde....',
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading()


        }
      })

    )



  }

  function sincronizaKits_old() {

    //EXECUTA JSON DE IMPORTAÇÃO
    $.ajax({
      url: '<?php echo base_url('rotinas/sincronizaKits') ?>',
      type: 'post',
      dataType: 'json',
      data: {
        csrf_sandra: $("#csrf_sandraPrincipal").val(),
      },
      success: function(sincronizaKits) {

        if (sincronizaKits.success === true) {


          Swal.fire({
            position: 'bottom-end',
            icon: 'success',
            html: sincronizaKits.messages,
            showConfirmButton: true,
            confirmButtonText: 'Ok',

          })


        }


      }
    }).always(
      Swal.fire({
        title: 'Estamos Sincronizando os Kits',
        html: 'Aguarde....',
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading()


        }
      })

    )

  }

  function redefineSenhaTodos() {


    Swal.fire({
      title: 'Deseja Redefinir a senha de todos os usuários para o PREC?',
      text: "NÃO REALIZE ESTA AÇÃO SE NÃO TIVER CERTEZA. O SISTEMA REGISTRARÁ AUDITORIA",
      icon: 'info',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.value) {

        //EXECUTA JSON DE IMPORTAÇÃO
        $.ajax({
          url: '<?php echo base_url('rotinas/redefineSenhaTodos') ?>',
          type: 'post',
          dataType: 'json',
          data: {
            csrf_sandra: $("#csrf_sandraPrincipal").val(),
          },
          success: function(redefinirSenhasPacientes) {

            if (redefinirSenhasPacientes.success === true) {


              Swal.fire({
                position: 'bottom-end',
                icon: 'success',
                html: redefinirSenhasPacientes.messages,
                showConfirmButton: true,
                confirmButtonText: 'Ok',

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
          })

        )


      }
    })



  }

  function importarAgendas_old() {

    //EXECUTA JSON DE IMPORTAÇÃO
    $.ajax({
      url: '<?php echo base_url('rotinas/importarAgendas') ?>',
      type: 'post',
      dataType: 'json',
      data: {
        csrf_sandra: $("#csrf_sandraPrincipal").val(),
      },
      success: function(importarAgendas) {

        if (importarAgendas.success === true) {


          Swal.fire({
            position: 'bottom-end',
            icon: 'success',
            html: importarAgendas.messages,
            showConfirmButton: true,
            confirmButtonText: 'Ok',

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
      })

    )

  }

  function importarAgendasSIGH() {

    //EXECUTA JSON DE IMPORTAÇÃO
    $.ajax({
      url: '<?php echo base_url('rotinas/importarAgendasSIGH') ?>',
      type: 'post',
      dataType: 'json',
      data: {
        csrf_sandra: $("#csrf_sandraPrincipal").val(),
      },
      success: function(importarAgendas) {

        if (importarAgendas.success === true) {


          Swal.fire({
            position: 'bottom-end',
            icon: 'success',
            html: importarAgendas.messages,
            showConfirmButton: true,
            confirmButtonText: 'Ok',

          })


        } else {

          Swal.fire({
            position: 'bottom-end',
            icon: 'false',
            html: importarAgendas.messages,
            showConfirmButton: true,
            confirmButtonText: 'Ok',

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
      })

    )

  }

  function importarAmbulatorioSIGH() {

    //EXECUTA JSON DE IMPORTAÇÃO
    $.ajax({
      url: '<?php echo base_url('rotinas/importarAmbulatorioSIGH') ?>',
      type: 'post',
      dataType: 'json',
      data: {
        csrf_sandra: $("#csrf_sandraPrincipal").val(),
      },
      success: function(importarAmbulatorio) {

        if (importarAmbulatorio.success === true) {


          Swal.fire({
            position: 'bottom-end',
            icon: 'success',
            html: importarAmbulatorio.messages,
            showConfirmButton: true,
            confirmButtonText: 'Ok',

          })


        } else {

          Swal.fire({
            position: 'bottom-end',
            icon: 'false',
            html: importarAmbulatorio.messages,
            showConfirmButton: true,
            confirmButtonText: 'Ok',

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
      })

    )

  }
  function importarBoletinsSIGH() {

    //EXECUTA JSON DE IMPORTAÇÃO
    $.ajax({
      url: '<?php echo base_url('rotinas/importarBoletinsSIGH') ?>',
      type: 'post',
      dataType: 'json',
      data: {
        csrf_sandra: $("#csrf_sandraPrincipal").val(),
      },
      success: function(importarBoletins) {

        if (importarBoletins.success === true) {


          Swal.fire({
            position: 'bottom-end',
            icon: 'success',
            html: importarBoletins.messages,
            showConfirmButton: true,
            confirmButtonText: 'Ok',

          })


        } else {

          Swal.fire({
            position: 'bottom-end',
            icon: 'false',
            html: importarAmbulatorio.messages,
            showConfirmButton: true,
            confirmButtonText: 'Ok',

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
      })

    )

  }

  function importarRequisicoes_old() {

    //EXECUTA JSON DE IMPORTAÇÃO
    $.ajax({
      url: '<?php echo base_url('rotinas/importarRequisicoes') ?>',
      type: 'post',
      dataType: 'json',
      data: {
        csrf_sandra: $("#csrf_sandraPrincipal").val(),
      },
      success: function(importarFornecedores) {

        if (importarFornecedores.success === true) {


          Swal.fire({
            position: 'bottom-end',
            icon: 'success',
            html: importarFornecedores.messages,
            showConfirmButton: true,
            confirmButtonText: 'Ok',

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
      })

    )

  }

  function importarFornecedores_old() {

    //EXECUTA JSON DE IMPORTAÇÃO
    $.ajax({
      url: '<?php echo base_url('rotinas/importarFornecedores') ?>',
      type: 'post',
      dataType: 'json',
      data: {
        csrf_sandra: $("#csrf_sandraPrincipal").val(),
      },
      success: function(importarFornecedores) {

        if (importarFornecedores.success === true) {


          Swal.fire({
            position: 'bottom-end',
            icon: 'success',
            html: importarFornecedores.messages,
            showConfirmButton: true,
            confirmButtonText: 'Ok',

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
      })

    )

  }
</script>