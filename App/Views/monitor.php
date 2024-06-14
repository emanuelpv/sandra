<?php

$perfisAutorizados = array(2, 10, 16, 19);
$perfilAutorizado = 0;

if ($_GET['autorizacao'] == '364f5b5504700506f2222e16cd2d7a0004') {
} else {

	if (!empty(session()->meusPerfis)) {

		foreach (session()->meusPerfis as $meuPerfil) {
			if (in_array($meuPerfil->codPerfil, $perfisAutorizados)) {
				$perfilAutorizado = 1;
			}
		}

		if ($perfilAutorizado == 1) {
		} else {
			print "NÃO AUTORIZADO!";
			exit();
		}
	} else {
		print "NÃO AUTORIZADO!";
		exit();
	}
}

?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="refresh" content="300">

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
	<!-- Google Font: Source Sans Pro -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

	<link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/toastr/toastr.min.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/select2/css/select2.min.css">

	<link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

	<!-- DATATABLES -->
	<link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">


	<script>
		var csrfName = '<?php echo csrf_token() ?>';
		var csrfHash = '<?php echo csrf_hash() ?>';
	</script>
	<input type="hidden" id="csrf_sandraPrincipal" name="csrf_sandraPrincipal" value="<?php echo csrf_hash() ?>">

</head>

<body class="bg-dark text-white">
	<section class="content">
		<div class="row">
			<div class="col-12">
				<div class="card">
				</div>
				<div style="padding-left:0; padding-right:0;padding-top:0;padding-bottom:0" class="card-body">

					<div class="col-md-12">
						<div class="card bg-dark text-white" style="transition: all 0.15s ease 0s; height: inherit; width: inherit;">

							<div class="col-md-12">
								<div style="padding-left:0; padding-right:0;padding-top:0;padding-bottom:0" class="card-body">
									<style>
										.carousel .carousel-item {
											transition-duration: 3s;
										}
									</style>
									<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" data-interval="40000" data-pause="hover">

										<div class="carousel-inner">

											<div class="carousel-item active">
												<style>
													th.dt-center,
													td.dt-center {
														text-align: center;
													}
												</style>

												<div style="padding-left:0; padding-right:0;padding-top:0;padding-bottom:0" class="card-body">
													<div style="margin-bottom:30px;height:40px; font-size:30px" class="align-middle font-weight-bold text-center">
														ATENDIMENTOS DA SEMANA POR TÉCNICO
													</div>

													<table id="data_tablesolicitacoesAtendidasPorTecnico" class="table  table-dark table-striped table-hover table-sm">
														<thead>
															<tr>
																<th style="text-align: center !important">Técnico</th>
																<th style="text-align: center !important">Total</th>
																<th style="text-align: center !important">Meta individual (15 chamados)</th>
																<th style="text-align: center !important">Horas Trabalhadas < SLA</th>
																<th style="text-align: center !important">Status</th>
																<th style="text-align: center !important">Avaliações do usuário</th>
															</tr>
														</thead>
													</table>
												</div>


											</div>

											
											<div class="carousel-item">
												<div class="row">
													<div class="col-md-6">
														<div class="card card-primary">
															<div class="card-header">
																<h3 class="card-title">Resolubilidade mensal</h3>

																<div class="card-tools">
																	<button type="button" class="btn btn-tool" data-card-widget="collapse">
																		<i class="fas fa-minus"></i>
																	</button>
																	<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
																	</button>
																</div>
															</div>
															<div class="card-body">
																<div class="row">
																	<div class="col-md-6 text-center">
																		<div class=" text-center" id="resolubilidadeOntem" canvas width="90" height="90"></canvas>
																			<input type="text" class="knobOntem" data-width="180" data-height="180" data-readonly="true" readonly="readonly" style="width: 49px; height: 30px; position: absolute; vertical-align: middle; margin-top: 30px; margin-left: -69px; border: 0px; background: none; font: bold 18px Arial; text-align: center; color: rgb(60, 141, 188); padding: 0px; appearance: none;">
																		</div>

																		<div style="color:black; font-size:20px;font-weight: bold;">Mês Passado</div>
																	</div>

																	<div class="col-md-6 text-center">
																		<div class=" text-center" id="resolubilidadeHoje" canvas width="90" height="90"></canvas>
																			<input type="text" class="knobHoje" data-width="180" data-height="180" data-readonly="true" readonly="readonly" style="width: 49px; height: 30px; position: absolute; vertical-align: middle; margin-top: 30px; margin-left: -69px; border: 0px; background: none; font: bold 18px Arial; text-align: center; color: rgb(60, 141, 188); padding: 0px; appearance: none;">
																		</div>

																		<div style="color:black; font-size:20px;font-weight: bold;">Mês Atual</div>
																	</div>
																</div>


															</div>
															<!-- /.card-body -->
														</div>
													</div>
													<div class="col-md-6">
														<!-- DONUT CHART -->
														<div class="card card-primary">
															<div class="card-header">
																<h3 class="card-title">Maiores Solicitantes (90 Dias)</h3>

																<div class="card-tools">
																	<button type="button" class="btn btn-tool" data-card-widget="collapse">
																		<i class="fas fa-minus"></i>
																	</button>
																	<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
																	</button>
																</div>
															</div>
															<div class="card-body">
																<canvas id="donutChart" style="min-height: 230px; height: 230px; max-height: 230px; max-width: 100%;"></canvas>
															</div>
															<!-- /.card-body -->
														</div>
													</div>
												</div>
												<div class="row">


													<div class="col-md-6">
														<!-- BAR CHART -->
														<div class="card card-primary">
															<div class="card-header">
																<h3 class="card-title">Evolução semanal (Solicitações em Abertos vs Fechados)</h3>

																<div class="card-tools">
																	<button type="button" class="btn btn-tool" data-card-widget="collapse">
																		<i class="fas fa-minus"></i>
																	</button>
																	<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
																	</button>
																</div>
															</div>
															<div class="card-body">
																<div class="chart">
																	<div class="chartjs-size-monitor">
																		<div class="chartjs-size-monitor-expand">
																			<div class=""></div>
																		</div>
																		<div class="chartjs-size-monitor-shrink">
																			<div class=""></div>
																		</div>
																	</div>
																	<canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 423px;" width="380" height="224" class="chartjs-render-monitor"></canvas>
																</div>
															</div>
														</div>
													</div>




													<!-- STACKED BAR CHART -->
													<div class="col-md-6">

														<div class="card card-primary">
															<div class="card-header">
																<h3 class="card-title">Nº de Pendências por técnico</h3>

																<div class="card-tools">
																	<button type="button" class="btn btn-tool" data-card-widget="collapse">
																		<i class="fas fa-minus"></i>
																	</button>
																	<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
																	</button>
																</div>
															</div>
															<div class="card-body">
																<div class="chart">
																	<div class="chartjs-size-monitor">
																		<div class="chartjs-size-monitor-expand">
																			<div class=""></div>
																		</div>
																		<div class="chartjs-size-monitor-shrink">
																			<div class=""></div>
																		</div>
																	</div>
																	<canvas id="barChartTecnicos" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 423px;" width="380" height="224" class="chartjs-render-monitor"></canvas>
																</div>
															</div>
														</div>


													</div>

												</div>

											</div>
											
											<div class="carousel-item ">

												<div style=" padding-left:0; padding-right:0;padding-top:0;padding-bottom:0" class="card-body">
													<div style="margin-bottom:30px;height:40px; font-size:30px" class="align-middle font-weight-bold text-center">
														INFRAESTRUTURA
													</div>

													<table id="data_tablesolicitacoesInfraestrutura" class="table  table-dark table-striped table-hover table-sm">
														<thead>
															<tr>
																<th>Código</th>
																<th>Descrição </th>
																<th>Categoria Suporte</th>
																<th>Solicitante</th>
																<th>Departamento</th>
																<th>Técnico</th>
																<th>Data</th>
																<th>Status</th>
															</tr>
														</thead>
													</table>
												</div>
											</div>
											<div class="carousel-item">


												<div style="padding-left:0; padding-right:0;padding-top:0;padding-bottom:0" class="card-body">
													<div style="margin-bottom:30px;height:40px; font-size:30px" class="align-middle text-center font-weight-bold ">
														SISTEMAS
													</div>

													<table id="data_tablesolicitacoesSistemas" class="table  table-dark table-striped table-hover table-sm">
														<thead>
															<tr>
																<th>Código</th>
																<th>Descrição </th>
																<th>Categoria Suporte</th>
																<th>Solicitante</th>
																<th>Departamento</th>
																<th>Técnico</th>
																<th>Data</th>
																<th>Status</th>
															</tr>
														</thead>
													</table>
												</div>


											</div>
											<div class="carousel-item">


												<div style="padding-left:0; padding-right:0;padding-top:0;padding-bottom:0" class="card-body">
													<div style="margin-bottom:30px;height:40px; font-size:30px" class="align-middle font-weight-bold text-center">
														TELEFONIA
													</div>

													<table id="data_tablesolicitacoesTelefonia" class="table  table-dark table-striped table-hover table-sm">
														<thead>
															<tr>
																<th>Código</th>
																<th>Descrição </th>
																<th>Categoria Suporte</th>
																<th>Solicitante</th>
																<th>Departamento</th>
																<th>Técnico</th>
																<th>Data</th>
																<th>Status</th>
															</tr>
														</thead>
													</table>
												</div>
											</div>
											
										</div>

									</div>

								</div>

							</div>

						</div>
					</div>
				</div>

			</div>
			<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
				<span class="carousel-control-custom-icon" aria-hidden="true">
					<i class="fas fa-chevron-left"></i>
				</span>
				<span class="sr-only">Anterior</span>
			</a>
			<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
				<span class="carousel-control-custom-icon" aria-hidden="true">
					<i class="fas fa-chevron-right"></i>
				</span>
				<span class="sr-only">Próximo</span>
			</a>
		</div>

	</section>

	</div>

</body>


<!-- jQuery -->
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/jquery-ui/jquery-ui.min.js"></script>
<script>
	$.widget.bridge('uibutton', $.ui.button)
</script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- jquery-validation -->


<script src="<?php echo base_url() ?>/assets/adminlte/plugins/jquery-knob/jquery.knob.min.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/chart.js/Chart-3.6.2.min.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/chartjs-plugin-datalabels-2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>


<!-- DATATABLES -->
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>



<script>
	$(function() {

		$('#data_tablesolicitacoesInfraestrutura').DataTable({
			"bDestroy": true,
			"paging": false,
			"lengthChange": false,
			"ordering": false,
			"info": false,
			"searching": false,
			"autoWidth": false,
			"responsive": false,
			"order": [
				[0, "desc"]
			],
			"ajax": {
				"url": '<?php echo base_url('monitor/solicitacoesInfraEmAberto') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});


		$('#data_tablesolicitacoesSistemas').DataTable({
			"bDestroy": true,
			"paging": false,
			"lengthChange": false,
			"ordering": false,
			"searching": false,
			"info": false,
			"autoWidth": false,
			"responsive": false,
			"order": [
				[0, "desc"]
			],
			"ajax": {
				"url": '<?php echo base_url('monitor/solicitacoesSistemasEmAberto') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});


		$('#data_tablesolicitacoesTelefonia').DataTable({
			"bDestroy": true,
			"paging": false,
			"lengthChange": false,
			"ordering": false,
			"info": false,
			"searching": false,
			"autoWidth": false,
			"responsive": false,
			"order": [
				[0, "desc"]
			],
			"ajax": {
				"url": '<?php echo base_url('monitor/solicitacoesTelefoniaEmAberto') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});



		$('#data_tablesolicitacoesAtendidasPorTecnico').DataTable({
			"bDestroy": true,
			"paging": false,
			"lengthChange": false,
			"ordering": true,
			"info": false,
			"searching": false,
			"autoWidth": false,
			"responsive": false,
			"order": [
				[1, "desc"]
			],
			"columnDefs": [{
					"className": "dt-center",
					"targets": 1
				},
				{
					"className": "dt-center",
					"targets": 3
				},
				{
					"className": "dt-center",
					"targets": 4
				},
			],

			"ajax": {
				"url": '<?php echo base_url('monitor/solicitacoesAtendidasPorTecnico') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});


	});


	function knobOntem(cor) {


		$('.knobOntem').knob({
			'min': 0,
			'max': 100,
			'readOnly': true,
			'width': 200,
			'height': 200,
			'fgColor': cor,
			'dynamicDraw': true,
			'thickness': 0.3,
			'tickColorizeValues': true,
			'skin': 'tron',
			draw: function() {

				// "tron" case
				if (this.$.data('skin') == 'tron') {

					var a = this.angle(this.cv) // Angle
						,
						sa = this.startAngle // Previous start angle
						,
						sat = this.startAngle // Start angle
						,
						ea // Previous end angle
						,
						eat = sat + a // End angle
						,
						r = true

					this.g.lineWidth = this.lineWidth

					this.o.cursor &&
						(sat = eat - 0.3) &&
						(eat = eat + 0.3)

					if (this.o.displayPrevious) {
						ea = this.startAngle + this.angle(this.value)
						this.o.cursor &&
							(sa = ea - 0.3) &&
							(ea = ea + 0.3)
						this.g.beginPath()
						this.g.strokeStyle = this.previousColor
						this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false)
						this.g.stroke()
					}

					this.g.beginPath()
					this.g.strokeStyle = r ? this.o.fgColor : this.fgColor
					this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false)
					this.g.stroke()

					this.g.lineWidth = 2
					this.g.beginPath()
					this.g.strokeStyle = this.o.fgColor
					this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false)
					this.g.stroke()

					return false
				}
			}
		})
	}


	function knobHoje(cor) {
		$('.knobHoje').knob({
			'min': 0,
			'max': 100,
			'readOnly': true,
			'width': 200,
			'height': 200,
			'fgColor': cor,
			'dynamicDraw': true,
			'thickness': 0.3,
			'tickColorizeValues': true,
			'skin': 'tron',
			draw: function() {

				// "tron" case
				if (this.$.data('skin') == 'tron') {

					var a = this.angle(this.cv) // Angle
						,
						sa = this.startAngle // Previous start angle
						,
						sat = this.startAngle // Start angle
						,
						ea // Previous end angle
						,
						eat = sat + a // End angle
						,
						r = true

					this.g.lineWidth = this.lineWidth

					this.o.cursor &&
						(sat = eat - 0.3) &&
						(eat = eat + 0.3)

					if (this.o.displayPrevious) {
						ea = this.startAngle + this.angle(this.value)
						this.o.cursor &&
							(sa = ea - 0.3) &&
							(ea = ea + 0.3)
						this.g.beginPath()
						this.g.strokeStyle = this.previousColor
						this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false)
						this.g.stroke()
					}

					this.g.beginPath()
					this.g.strokeStyle = r ? this.o.fgColor : this.fgColor
					this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false)
					this.g.stroke()

					this.g.lineWidth = 2
					this.g.beginPath()
					this.g.strokeStyle = this.o.fgColor
					this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false)
					this.g.stroke()

					return false
				}
			}
		})



	}


	function atualizaTela() {
		$.fn.dataTable.ext.errMode = 'none';
		$('#data_tablesolicitacoesInfraestrutura').DataTable().ajax.reload(null, false).draw(false);
		$('#data_tablesolicitacoesSistemas').DataTable().ajax.reload(null, false).draw(false);
		$('#data_tablesolicitacoesTelefonia').DataTable().ajax.reload(null, false).draw(false);
		$('#data_tablesolicitacoesAtendidasPorTecnico').DataTable().ajax.reload(null, false).draw(false);



	}

	function maioresSolicitantes() {


		$.ajax({
			url: '<?php echo base_url('monitor/maioresSolicitantes') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(maioresSolicitantes) {


				var departamentos
				departamentos = JSON.parse(maioresSolicitantes.departamentos);
				totaisDepartamento = JSON.parse(maioresSolicitantes.totais);





				var data = [{
					data: totaisDepartamento,
					backgroundColor: [
						"red",
						"#5f255f",
						"green ",
						"#d21243",
						"#d21243",
						"#B27200"
					],
					borderColor: "#fff"
				}];

				var Donutoptions = {
					maintainAspectRatio: false,
					responsive: true,
					legend: {
						position: 'left'
					},
					tooltips: {
						enabled: true
					},
					plugins: {
						datalabels: {
							formatter: (value, ctx) => {
								const datapoints = ctx.chart.data.datasets[0].data
								const total = datapoints.reduce((total, datapoint) => total + datapoint, 0)
								const percentage = value / total * 100
								return percentage.toFixed(0) + "%";
							},
							color: '#fff',
						},

					},

				};

				var ctx = document.getElementById("donutChart").getContext('2d');
				var myChart = new Chart(ctx, {
					type: 'pie',
					data: {
						labels: departamentos,
						datasets: data
					},
					options: Donutoptions,
					plugins: [ChartDataLabels],
				});


			}

		})


	}

	function resolubilidadeOntem() {

		$.ajax({
			url: '<?php echo base_url('monitor/resolubilidadeOntem') ?>',
			type: 'post',

			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(resolubilidadeOntem) {
				var percentual;
				if (resolubilidadeOntem.totalSolicitacoes != 0) {
					percentual = Math.round(resolubilidadeOntem.statusEncerrado / resolubilidadeOntem.totalSolicitacoes * 100);
				} else {
					percentual = 0;
				}

				$("input.knobOntem").val(percentual);
				$("input.knobOntem").trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});


				if (percentual <= 50) {
					knobOntem('red');
				}
				if (percentual > 50 && percentual < 70) {
					knobOntem('#b9e672');
				}
				if (percentual > 70) {
					knobOntem('green');
				}




			}



		})
	}

	function resolubilidadeHoje() {
		$.ajax({
			url: '<?php echo base_url('monitor/resolubilidadeHoje') ?>',
			type: 'post',

			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(resolubilidadeHoje) {
				var percentual;
				if (resolubilidadeHoje.totalSolicitacoes != 0) {
					percentual = Math.round(resolubilidadeHoje.statusEncerrado / resolubilidadeHoje.totalSolicitacoes * 100);
				} else {
					percentual = 0;
				}

				percentual = 70;
				$("input.knobHoje").val(percentual);
				$("input.knobHoje").trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});
				if (percentual <= 50) {
					knobHoje('red');
				}
				if (percentual > 50 && percentual < 70) {
					knobHoje('#b9e672');
				}
				if (percentual >= 70) {
					knobHoje('green');
				}


			}



		})

	}


	function solicitacoesAbertasPorTecnico() {
		$.ajax({
			url: '<?php echo base_url('monitor/solicitacoesAbertasPorTecnico') ?>',
			type: 'post',

			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(solicitacoesPorTecnicos) {


				tecnicos = JSON.parse(solicitacoesPorTecnicos.tecnicos);

				totais = JSON.parse(solicitacoesPorTecnicos.totais);


				var barChartDataTecnicos = {
					labels: tecnicos,
					datasets: [{
						label: 'Nº de Chamados Abertos por Técnicos',
						backgroundColor: 'red',
						borderColor: 'rgba(60,141,188,0.8)',
						pointRadius: false,
						pointColor: 'blue',
						pointStrokeColor: 'rgba(60,141,188,1)',
						pointHighlightFill: 'blue',
						pointHighlightStroke: 'rgba(60,141,188,1)',
						data: totais
					}, ]
				}


				var barChartCanvasTecnicos = $('#barChartTecnicos').get(0).getContext('2d')
				var barChartDataTecnicos = $.extend(true, {}, barChartDataTecnicos)
				var temp0 = barChartDataTecnicos.datasets[0]
				barChartDataTecnicos.datasets[0] = temp0

				var barChartOptionsTecnicos = {
					responsive: true,
					maintainAspectRatio: false,
					datasetFill: false,
					scales: {
						yAxes: [{
							scaleLabel: {
								display: true,
								labelString: 'Chamados'
							}
						}]
					},
				}

				new Chart(barChartCanvasTecnicos, {
					type: 'bar',
					data: barChartDataTecnicos,
					options: barChartOptionsTecnicos,
					plugins: [ChartDataLabels],
				})




			}
		})
	}

	function solicitacoesSemana() {
		$.ajax({
			url: '<?php echo base_url('monitor/solicitacoesSemana') ?>',
			type: 'post',

			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(solicitacoesSemana) {



				diaSemana = JSON.parse(solicitacoesSemana.diaSemana);

				solicitacoesAbertas = JSON.parse(solicitacoesSemana.solicitacoesAbertas);

				solicitacoesFechadas = JSON.parse(solicitacoesSemana.solicitacoesFechadas);


				var barChartData = {
					labels: diaSemana,
					datasets: [{
							label: 'Abertos',
							backgroundColor: 'red',
							borderColor: 'red',
							pointRadius: false,
							pointColor: 'rgba(210, 214, 222, 1)',
							pointStrokeColor: '#c1c7d1',
							pointHighlightFill: '#fff',
							pointHighlightStroke: 'rgba(220,220,220,1)',
							data: solicitacoesAbertas,
						},
						{
							label: 'Fechados',
							backgroundColor: 'rgba(60,141,188,0.9)',
							borderColor: 'rgba(60,141,188,0.8)',
							pointRadius: false,
							pointColor: 'red',
							pointStrokeColor: 'rgba(60,141,188,1)',
							pointHighlightFill: '#fff',
							pointHighlightStroke: 'rgba(60,141,188,1)',
							data: solicitacoesFechadas,

						},
					]
				}




				var stackedBarChartCanvas = $('#barChart').get(0).getContext('2d')
				var stackedBarChartData = $.extend(true, {}, barChartData)

				var stackedBarChartOptions = {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					}
				}

				new Chart(stackedBarChartCanvas, {
					type: 'bar',
					data: stackedBarChartData,
					options: stackedBarChartOptions,
					plugins: [ChartDataLabels],
				})



			}
		})
	}

	solicitacoesSemana();
	solicitacoesAbertasPorTecnico();
	maioresSolicitantes();
	resolubilidadeOntem();
	resolubilidadeHoje();


	setInterval(function() {
		atualizaTela();
		solicitacoesSemana();
		solicitacoesAbertasPorTecnico();
		resolubilidadeHoje();
		maioresSolicitantes();
		resolubilidadeOntem();
	}, 5000);
</script>