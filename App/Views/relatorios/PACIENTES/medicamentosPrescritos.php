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

<script>
	var csrfName = '<?php echo csrf_token() ?>';
	var csrfHash = '<?php echo csrf_hash() ?>';
</script>
<input type="hidden" id="csrf_sandraPrincipal" name="csrf_sandraPrincipal" value="<?php echo csrf_hash() ?>">

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-md-8 mt-2">
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Relatório</h3>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">


					<meta charset="UTF-8">
					<title>Relatório</title>

					<link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/pivot/2.23.0/pivot.min.css">
					<script src="<?php echo base_url() ?>/assets/adminlte/plugins/pivot/jquery-3.4.1.min.js"></script>
					<script src="<?php echo base_url() ?>/assets/adminlte/plugins/pivot/jquery-ui-1.12.1.min.js"></script>

					<script src="<?php echo base_url() ?>/assets/adminlte/plugins/pivot/2.23.0/pivot.min.js"></script>
					<script src="<?php echo base_url() ?>/assets/adminlte/plugins/pivot/plotly-basic-latest.min.js"></script>

					<script src="<?php echo base_url() ?>/assets/adminlte/plugins/pivot/plotly_renderers-2.22.0.min.js"></script>

					<div style="margin: 1em;" id="output"></div>


					<script>
						var salesPivotData =


							<?php


							use App\Models\AtendimentosModel;

							$this->AtendimentosModel = new AtendimentosModel();

							$medicamentosPrescritos = $this->AtendimentosModel->todosMedicamentosPrescritosUrgenciaEmergencia();

							print json_encode($medicamentosPrescritos);

							?>


						$("#output").pivotUI(
							salesPivotData, {
								rows: ["Medicamento"],
								cols: ["Mes","tipoAgenda"],
								renderers: $.extend(
									$.pivotUtilities.renderers,
									$.pivotUtilities.plotly_renderers,

								)
							});
					</script>
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
