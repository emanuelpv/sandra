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
<link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/pivot/2.23.0/pivot.min.css">
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/pivot/jquery-3.4.1.min.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/pivot/jquery-ui-1.12.1.min.js"></script>

<script src="<?php echo base_url() ?>/assets/adminlte/plugins/pivot/2.23.0/pivot.min.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/pivot/plotly-basic-latest.min.js"></script>

<script src="<?php echo base_url() ?>/assets/adminlte/plugins/pivot/plotly_renderers-2.22.0.min.js"></script>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-12">
      <div class="card">

        <?php
        menusRelatorios($this);
        ?>

      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
</section>





<div id="estatisticasSameModal" class="modal fade" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-primary text-center p-3">
        <h4 class="modal-title text-white" id="info-header-modalLabel">Faturas</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">


        <div style="margin: 1em;" id="output"></div>



      </div>
    </div>
  </div>
</div>




<div style="margin: 1em;" id="output"></div>


<?php
echo view('tema/rodape');
?>

<script>
  function estatisticas() {


    $('#estatisticasSameModal').modal('show');


    var salesPivotData = [{
        "Region": "Australia and Oceania",
        "Country": "Kiribati",
        "Item Type": "Cereal",
        "Sales Channel": "Offline",
        "Order Priority": "L",
        "Order Date": "7/24/2012",
        "Order ID": 905392587,
        "Ship Date": "8/16/2012",
        "Units Sold": 4641,
        "Unit Price": 205.7,
        "Unit Cost": 117.11,
        "Total Revenue": 954653.7,
        "Total Cost": 543507.51,
        "Total Profit": 411146.19
      },

      {
        "Region": "Asia",
        "Country": "Cambodia",
        "Item Type": "Snacks",
        "Sales Channel": "Online",
        "Order Priority": "C",
        "Order Date": "3/25/2012",
        "Order ID": 990708720,
        "Ship Date": "5/4/2012",
        "Units Sold": 1581,
        "Unit Price": 152.58,
        "Unit Cost": 97.44,
        "Total Revenue": 241228.98,
        "Total Cost": 154052.64,
        "Total Profit": 87176.34
      },
    ];

    $("#output").pivotUI(
      salesPivotData, {
        rows: ["Country"],
        cols: [],
        renderers: $.extend(
          $.pivotUtilities.renderers,
          $.pivotUtilities.plotly_renderers,

        )
      });

  }
</script>