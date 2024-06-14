<?php
//É NECESSÁRIO EM TODAS AS VIEWS

$codOrganizacao = session()->codOrganizacao;
$codPessoa = session()->codPessoa;

use App\Models\ServicoLDAPModel;
use App\Models\AtalhosModel;


$this->ServicoLDAPModel = new ServicoLDAPModel;
$this->atalhosModel = new AtalhosModel();



if (date('Y-m-d', strtotime(session()->dt_login)) < date('Y-m-d')) {
  session()->destroy();
}

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

//SEGURANÇA
/*
helper('cookie');
if (session()->autorizacao !== NULL) {

  set_cookie('autorizacao',  md5(session()->autorizacao . session()->codPessoa), '3600');
}
*/






?>

<style>
  .modal {
    overflow: auto !important;
  }

  #minhaFoto {
    width: 160px;
    height: 125px;
    border: 1px solid black;
  }

  #fotoPerfilCadastro {
    width: 160px;
    height: 125px;
    border: 1px solid black;
  }

  .select2-container {
    z-index: 100000;
  }


  .swal2-container {
    z-index: 9999999;
  }
</style>

<div style="visibility:hidden" id="setEstilo"></div>


<script>
  window.onload = function() {

    $.ajax({
      url: '<?php echo base_url('pessoas/verificaPendenciaCadastro') ?>',
      type: 'post',
      dataType: 'json',
      data: {
        csrf_sandra: $("#csrf_sandraPrincipal").val(),
      },
      success: function(responseverificaPendenciaCadastro) {

        if (responseverificaPendenciaCadastro.pendencias == true) {

          Swal.fire({

            position: 'center',
            icon: 'warning',
            title: 'Cadastro Desatualizado',
            text: 'Seu cadratro está desatualizado. Você possue ' + responseverificaPendenciaCadastro.quantidade + ' pendencia(s).',
            showConfirmButton: true,
            timer: 10000
          }).then(function() {
            editPessoaLogada(<?php echo session()->codPessoa ?>);

          })



        } else {

          //VERIFICA SE NECESSITA TROCAR SENHA
          $.ajax({
            url: '<?php echo base_url('pessoas/verificaPendenciaSenha') ?>',
            type: 'post',
            dataType: 'json',
            data: {
              csrf_sandra: $("#csrf_sandraPrincipal").val(),
            },
            success: function(response) {

              if (response.pendencias == true) {

                Swal.fire({

                  position: 'center',
                  icon: 'warning',
                  title: 'REDEFINIÇÃO DE SENHA',
                  text: 'É necessário cadastrar nova senha.',
                  showConfirmButton: true,
                  timer: 10000
                }).then(function() {
                  trocasenha(<?php echo session()->codPessoa ?>);

                })



              } else {

              }

            }
          })

        }

      }
    })


  }
</script>





<?php







//DASHBOARD PROFISSIONAIS DE SAÚDE


if (!empty(session()->minhasEspecialidades) and session()->codPessoa !== NULL) {

?>
  <i class="fas fa-whatsapp"></i>


  <div class="col-md-12">
    <div class="card">
      <div style="margin-left:10px;margin-top:10px;margin-right:10px" class="row">

        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-info">
            <div class="inner">
              <div style="font-size: 60px;font-weight: bold"><?php echo meusPacientesHoje() ?></div>

              <div style="font-size: 25px;font-weight: bold">Minha agenda</div>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="<?php echo base_url('Pacientes') ?>?meusPacientes=1" class="small-box-footer">Ver <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-warning">
            <div class="inner">
              <div style="font-size: 60px;font-weight: bold"><?php echo atendimentosUrgenciaEmergencia() ?></div>

              <div style="font-size: 25px;font-weight: bold">Emergência</div>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="<?php echo base_url('Pacientes') ?>?pacientesEmergencia=1" class="small-box-footer">Ver <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-success">
            <div class="inner">
              <div class="row">
                <div class="col-md-6 border-right border-bottom">
                  <div> <span style="font-size: 60px;font-weight: bold"><?php echo atendimentosInternados() ?></span> <span style="font-size: 10px;font-weight: bold" ><?php echo session()->siglaOrganizacao?></span></div>
            
                </div>
                <div class="col-md-6 border-bottom">
                  <div> <span style="font-size: 60px;font-weight: bold"><?php echo atendimentosInternadosOCS() ?> </span>  <span style="font-size: 10px;font-weight: bold">OCS</span></div>
                
                </div>
              </div>

              <div style="font-size: 25px;font-weight: bold">Internados</div>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="<?php echo base_url('Pacientes') ?>?pacientesInternados=1" class="small-box-footer">Ver <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-danger">
            <div class="inner">
              <?php
              $dadosOcupacaoLeitos = dadosOcupacaoLeitos();
              ?>
              <div style="font-size: 15px;font-weight: bold">Leitos: <?php echo $dadosOcupacaoLeitos['totalLeitos'] ?></div>
              <div style="font-size: 15px;font-weight: bold">Ocupados: <?php echo $dadosOcupacaoLeitos['totalLeitosOcupados'] ?></div>
              <div style="font-size: 15px;font-weight: bold">Vagos: <?php echo $dadosOcupacaoLeitos['totalLeitos'] - $dadosOcupacaoLeitos['totalLeitosOcupados'] ?></div>
              <div style="font-size: 15px;font-weight: bold">Em Manutenção: <?php echo $dadosOcupacaoLeitos['totalLeitosEmManutencao'] ?></div>
              <div style="font-size: 15px;font-weight: bold">Tx de Ocupação: <span style="font-size: 25px"><?php echo round($dadosOcupacaoLeitos['totalLeitosOcupados'] / $dadosOcupacaoLeitos['totalLeitos'] * 100, 2) ?>%</span></div>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" onclick="relatoriosOcupacao()" class="small-box-footer">Relatórios <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
    </div>
  </div>






  <div id="verSituacaoModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-primary text-center p-3">
          <h4 class="modal-title text-white" id="info-header-modalLabel">Situação dos Leitos</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">


          <div class="row">
            <div class="col-md-12">
              <table style="width: 100%" id="data_situacaoLeitos" class="table table-striped table-hover table-sm">
                <thead>
                  <tr>
                    <th>Unidade</th>
                    <th>Leito</th>
                    <th>Paciente</th>
                    <th>Idade</th>
                    <th>Situação Leito</th>
                    <th>Tempo Ocupação</th>
                  </tr>
                </thead>
              </table>

            </div>
          </div>



        </div>
      </div>
    </div>
  </div>





  <div id="sensoInternadosModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-primary text-center p-3">
          <h4 class="modal-title text-white">CENSO INTERNADOS</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-2 text-right">
              <div class="form-group">
                <button style="margin-left:10px" type="button" class="btn btn-block btn-outline-primary btn-lg" onclick="imprimirCensoPacientes()" title="Imprimir Conduta">
                  <div><i class="fas fa-print fa-1x" aria-hidden="true"></i></div>
                  Imprimir
                </button>
              </div>
            </div>
          </div>
          <div id="areaImpressaoCensoPacientes">
            <div style="margin-left:5px" id="sensoInternadosFarmacia"></div>
          </div>
        </div>
      </div>
    </div>
  </div>




  <div id="relatoriosOcupacaoModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-primary text-center p-3">
          <h4 id="nomeLocalAtendimentoAgendamentosExames" class="modal-title text-white">Relatórios</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">

          <div id="botoesPerfil">
            <div class="row">
              <div class="col-md-4"><button type="button" class="btn btn-lg btn-primary" data-toggle="tooltip" data-placement="top" onclick="verCensoInternados()"><i class="fa fa-print"></i> CENSO INTERNADOS</button></div>
              <div class="col-md-4"><button type="button" class="btn btn-lg btn-primary" data-toggle="tooltip" data-placement="top" onclick="verSituacaoModal()"><i class="fa fa-print"></i> SITUAÇÃO LEITOS</button></div>
              <div class="col-md-4"><button type="button" class="btn btn-lg btn-primary" data-toggle="tooltip" data-placement="top" onclick="verPlanoChamadas()"><i class="fa fa-print"></i> PLANO DE CHAMADA</button></div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
<?php
}


//ATALHOS
$atalhos = $this->atalhosModel->pegaTudoPorPerfil(session()->perfilSessao);

// print_r($atalhos);
$contador = 0;

?>
<div style="margin-bottom:30px;margin-left:10px" class="row">

  <?php
  if ($atalhos !== FALSE) {





    if (count($atalhos) > 1) {
      $colunas = 3;
    } else {
      $colunas = 3;
    }
    foreach ($atalhos as $atalho) {
      $autorizacao = session()->autorizacao;
      $icones .= '            
            <div class="col-md-' . $colunas . '">
                <div class="form-group">
                    <a href="' . base_url($atalho->link) . '/?autorizacao=' . $autorizacao . '" class="btn btn-block btn-outline-secondary btn-lg ">
                        <div> 
                          <i class="btn-primary ' . $atalho->icone . ' zoom fa-3x"></i>
                        </div>
                      <div>' . $atalho->nome . ' </div>
                    </a>
                </div>
            </div>
            ';
    }
  }

  print $icones;

  ?>
</div>
<!--

          <div id="ELEMENT_ID" class="card-body">

          </div>
          <style>
            div.watermark.leftwatermark{
              background-image: none !important;
            }
            
          </style>
          <script src="https://meet.jit.si/external_api.js"></script>
          <script>
            //DOCUMENTAÇÃO -> https://jitsi.github.io/handbook/docs/dev-guide/dev-guide-iframe
            //DOCUMENTAÇÃO -> https://doganbros.com/index.php/jitsi/iframe-example/
            //OURO -> https://community.jitsi.org/t/how-to-remove-jitsi-name-logo-from-iframe-api/90625/6
            var domain = "meet.jit.si";
            var options = {
              roomName: "JitsiMeetAPIExample",
              width: '700px',
              height: '400px',
              parentNode: document.querySelector('#ELEMENT_ID'),
              configOverwrite: {
                //prejoinPageEnabled: false, Loga o usuário direto
                startWithAudioMuted: false,
              },
              interfaceConfigOverwrite: {
                DEFAULT_LOGO_URL: 'https://sandra.hospital.com.br/imagens/logos/1.png',
                SHOW_JITSI_WATERMARK: true,
                HIDE_INVITE_MORE_HEADER: true,
                HIDE_DEEP_LINKING_LOGO: true,
                DISABLE_JOIN_LEAVE_NOTIFICATIONS: true,
                DISABLE_DOMINANT_SPEAKER_INDICATOR: true,
                DISABLE_FOCUS_INDICATOR: true,
                DEFAULT_BACKGROUND: '#474747',
                DEFAULT_WELCOME_PAGE_LOGO_URL: 'https://sandra.hospital.com.br/imagens/logos/1.png',
                SHOW_JITSI_WATERMARK: true,
                //TOOLBAR_BUTTONS: ['microphone', 'camera', 'hangup', 'mute-everyone']
                //TOOLBAR_BUTTONS: [],
              },
              userInfo: {
                id: '3232243',
                email: 'emanuel@peixoto.com.br',
                displayName: 'Cap Emanuel',
                avatarURL: 'http://www.rumoaesfcex.com.br/wp-content/uploads/2011/08/dsc000281.jpg',
              },


            }
            var api = new JitsiMeetExternalAPI(domain, options);
            //api.executeCommand('displayName', 'Cap Emanuel');
            api.executeCommand('avatarUrl', 'http://www.rumoaesfcex.com.br/wp-content/uploads/2011/08/dsc000281.jpg');
            document.getElementById("teste").style.backgroundImage = none; // specify the image path here
          </script>
          <button onclick="api.executeCommand('toggleAudio')">Microfone</button>
          <button onclick="api.executeCommand('toggleVideo')">Vídeo</button>
          <button onclick="api.executeCommand('toggleChat')">Chat</button>

          -->


<?php
echo view('tema/rodape');
?>


<link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/parabens/parabens.css">



<div id="parabensModal" class="modal fade" role="dialog" aria-hidden="true" style="z-index: 31000;">
  <div class="modal-dialog modal-xl">
    <div style="background:#000" class="modal-content">

      <button style="width:100px" type="button" class="text-left btn btn-primary" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">Fechar</span>
      </button>

    </div>

    <div style="background:#000" class="modal-body">
      <div style='margin-top:30px'>
        <IMG STYLE='position:relative;  TOP:130px; RIGHT:0px;' width='222px' SRC='<?php echo base_url() ?>/imagens/aniversariantes/fogos6.gif'>
        <IMG STYLE='position:relative;  TOP:80px; LEFT:100px;' width='100px' SRC='<?php echo base_url() ?>/imagens/aniversariantes/fogos5.gif'>
        <IMG STYLE='position:relative;  TOP:0px; LEFT:200px;' width='100px' SRC='<?php echo base_url() ?>/imagens/aniversariantes/fogos5.gif'>
        <IMG STYLE='position:relative;  TOP:100px; LEFT:20px;' width='400px' SRC='<?php echo base_url() ?>/imagens/aniversariantes/parabens.gif'>

        <h1 style="margin-top:10%"><?php echo session()->nomeExibicao ?></h1>
        <h2 style="margin-top:0px !important">Saúde e felicidades!</h2>

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<style>
  #toast-container>div {
    width: 800px;
  }
</style>
<?php




if (!empty(session()->aniversariantes)) {

  $existeAninversariante = 1;

  $html = '<div style="font-size:20px;font-weight: bold;">ANIVERSARIANTES</div>';
  foreach (session()->aniversariantes as $value) {
    $html .= '<div>' . $value->nomeExibicao . ' - ' . $value->descricaoDepartamento . '</div>';
  }
}

?>

<script>
  $(document).on('show.bs.modal', '.modal', function() {
    var zIndex = 1040 + (10 * $('.modal:visible').length);
    $(this).css('z-index', zIndex);
    setTimeout(function() {
      $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
    }, 0);
  });

  
  avisoPesquisa('Suporte',2);

  aniversariante = '<?php echo session()->aniversariante ?>'
  existeAninversariante = '<?php echo $existeAninversariante ?>'

  if (aniversariante == 1) {
    $('#parabensModal').modal('show');
  }


  if (existeAninversariante == 1) {
    toastr.info('', '<?php echo $html ?>', {
      hideMethod: 'fadeOut',
      showMethod: 'fadeIn',
      closeMethod: 'swing',
      showDuration: 4000,
      timeOut: 15000,
      extendedTimeOut: 15000,
      positionClass: "toast-bottom-right",
    })
  }



  function relatoriosOcupacao() {

    $('#relatoriosOcupacaoModal').modal('show');
  }




  function verCensoInternados() {

    $('#sensoInternadosModal').modal('show');


    $.ajax({
      url: '<?php echo base_url('Atendimentos/sensoInternadosServicoSocial') ?>',
      type: 'post',
      data: {
        csrf_sandra: $("#csrf_sandraPrincipal").val(),
      },
      success: function(prescricaosensoInternados) {

        Swal.close();

        document.getElementById("sensoInternadosFarmacia").innerHTML = prescricaosensoInternados.html;

      }

    }).always(
      Swal.fire({
        title: 'Estamos buscando os dados dos pacientes internados',
        html: 'Aguarde....',
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading()


        }

      }))



  }

  function verPlanoChamadas() {
    Swal.fire({
      icon: 'info',
      title: 'Este recurso está em desenvolvimento....',
      showConfirmButton: false,
      timer: 4000
    })
  }

  function verSituacaoModal() {

    $('#verSituacaoModal').modal('show');



    $('#data_situacaoLeitos').DataTable({

      "bDestroy": true,
      "paging": false,
      "pageLength": 200,
      "deferRender": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
      createdRow: function(row, data, dataIndex) {
        if (data[4].trim() == 'EM MANUTENÇÃO') {
          $(row).css({
            "background-color": "#f3a40dc9",
            "color": "#fff"
          });
          $(row).addClass('sub-needed');
        }
        if (data[4].trim() == 'LIVRE') {
          $(row).css({
            "background-color": "#28a745c2",
            "color": "#fff"
          });
          $(row).addClass('sub-needed');
        }
        if (data[4].trim() == 'OCUPADO') {
          $(row).css({
            "background-color": "#ff1800c9",
            "color": "#fff"
          });
          $(row).addClass('sub-needed');
        }

      },
      "ajax": {
        "url": '<?php echo base_url('atendimentos/situacaoTodosLeitos') ?>',
        "type": "POST",
        "dataType": "json",
        async: "true",
        data: {
          csrf_sandra: $("#csrf_sandraPrincipal").val(),
        },
      }
    })



  }



  function imprimirCensoPacientes() {


    printElement(document.getElementById("areaImpressaoCensoPacientes"));

    document.getElementById("setEstilo").innerHTML = '<style>@media screen {' +
      '#printSection {' +
      'display: none;' +

      '}' +
      '}' +

      '@media print {' +
      '@page {' +
      'size: A4;' +
      'margin: 5px;' +
      '}' +

      'body>*:not(#printSection) {' +
      'display: none;' +
      '}' +

      '#printSection,' +
      '#printSection * {' +
      'visibility: visible;' +

      '}' +
      '#printSection {' +
      'position: absolute;' +
      'left: 0;' +
      'top: 0;' +
      'width: 210mm;' +
      'height: 297mm;' +

      '}' +
      '}</style>';

    window.print();
  }


  function printElement(elem) {
    var domClone = elem.cloneNode(true);

    var $printSection = document.getElementById("printSection");

    if (!$printSection) {
      var $printSection = document.createElement("div");
      $printSection.id = "printSection";
      document.body.appendChild($printSection);
    }

    $printSection.innerHTML = "";

    $printSection.appendChild(domClone);
  }
</script>