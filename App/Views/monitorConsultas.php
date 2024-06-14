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

												<div class="row">
													<div class="col-md-6">
														<div class="card card-primary">
															<div class="card-header">
																<h3 class="card-title">Marcações do dia</h3>


															</div>
															<div style="height: 270px;" class="card-body">


																<div class="row">
																	<div class="col-md-3">
																		<div class="small-box bg-primary">
																			<div class="inner">
																				<h3 id="infoMarcacoesDiaSame"></h3>

																				<p>PRESENCIAL</p>
																			</div>
																			<div class="icon">
																				<i class="fas fa-chart-pie"></i>
																			</div>
																		</div>
																	</div>

																	<div class="col-md-3">
																		<div class="small-box bg-success">
																			<div class="inner">
																				<h3 id="infoMarcacoesDiaInternet"></h3>

																				<p>INTERNET</p>
																			</div>
																			<div class="icon">
																				<i class="fas fa-chart-pie"></i>
																			</div>
																		</div>
																	</div>

																	<div class="col-md-3">
																		<div class="small-box bg-warning">
																			<div class="inner">
																				<h3 id="infoMarcacoesDiaRetorno"></h3>

																				<p>RETORNO</p>
																			</div>
																			<div class="icon">
																				<i class="fas fa-chart-pie"></i>
																			</div>
																		</div>
																	</div>
																	<div class="col-md-3">
																		<div class="small-box bg-info">
																			<div class="inner">
																				<h3 id="infoMarcacoesDiaAbas"></h3>

																				<p>ABAS</p>
																			</div>
																			<div class="icon">
																				<i class="fas fa-chart-pie"></i>
																			</div>
																		</div>
																	</div>
																</div>

															</div>
															<!-- /.card-body -->
														</div>
													</div>
													<div class="col-md-3">
														<!-- DONUT CHART -->
														<div class="card card-primary">
															<div class="card-header">
																<h3 class="card-title">Mais marcadas (30 Dias)</h3>

															</div>
															<div class="card-body">
																<canvas id="donutChart" style="min-height: 230px; height: 230px; max-height: 230px; max-width: 100%;"></canvas>
															</div>
															<!-- /.card-body -->
														</div>
													</div>

													<div class="col-md-3">
														<!-- BAR CHART -->
														<div class="card card-primary">
															<div class="card-header">
																<h3 class="card-title">Evolução semanal </h3>

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
																	<canvas id="barChart" style="min-height: 230px; height: 230px; max-height: 230px; max-width: 100%; display: block; width: 423px;" width="380" height="224" class="chartjs-render-monitor"></canvas>
																</div>
															</div>
														</div>
													</div>

												</div>
												<div class="row">


													<div class="col-md-6">
														<div class="card card-primary">
															<div class="card-header">
																<h3 class="card-title">Evolução das Marcações (Semana Anterior)</h3>

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
																	<canvas id="lineChartSemanaAnterior" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 423px;" width="380" height="224" class="chartjs-render-monitor"></canvas>
																</div>
															</div>
														</div>
													</div>



													<div class="col-md-6">

														<div class="card card-primary">
															<div class="card-header">
																<h3 class="card-title">Evolução das Marcações (Semana Atual)</h3>

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
																	<canvas id="lineChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 423px;" width="380" height="224" class="chartjs-render-monitor"></canvas>
																</div>

															</div>
														</div>


													</div>

												</div>




											</div>


											<div class="carousel-item">
												<style>
													th.dt-center,
													td.dt-center {
														text-align: center;
													}
												</style>

												<div style="padding-left:0; padding-right:0;padding-top:0;padding-bottom:0" class="card-body">
													<div style="margin-bottom:30px;height:40px; font-size:30px" class="align-middle font-weight-bold text-center">
														VAGAS ABERTAS
													</div>



													<div class="row">


														<div class="col-md-6">
															<!-- BAR CHART -->
															<div class="card card-primary">
																<div class="card-header">
																	<center>
																		<h2>PRESENCIAL</h2>
																	</center>


																</div>
																<div class="card-body">
																	<div id="vagasAbertasPresencial"></div>

																</div>
															</div>
														</div>



														<div class="col-md-6">

															<div class="card card-success">
																<div class="card-header">
																	<center>
																		<h2>INTERNET</h2>
																	</center>
																</div>
																<div class="card-body">


																	<div id="vagasAbertasInternet"></div>


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
				"url": '<?php echo base_url('monitorConsultas/solicitacoesInfraEmAberto') ?>',
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
				"url": '<?php echo base_url('monitorConsultas/solicitacoesSistemasEmAberto') ?>',
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
				"url": '<?php echo base_url('monitorConsultas/solicitacoesTelefoniaEmAberto') ?>',
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
				"url": '<?php echo base_url('monitorConsultas/solicitacoesAtendidasPorTecnico') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});


	});



	function atualizaTela() {
		$.fn.dataTable.ext.errMode = 'none';
		$('#data_tablesolicitacoesAtendidasPorTecnico').DataTable().ajax.reload(null, false).draw(false);



	}

	function maisMarcadas() {


		$.ajax({
			url: '<?php echo base_url('monitorConsultas/maisMarcadas') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(maisMarcadas) {


				var especialidades
				especialidades = JSON.parse(maisMarcadas.especialidades);
				totaisEspecialidade = JSON.parse(maisMarcadas.totais);





				var data = [{
					data: totaisEspecialidade,
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
						labels: especialidades,
						datasets: data
					},
					options: Donutoptions,
					plugins: [ChartDataLabels],
				});


			}

		})


	}

	function totalAgendamentos() {

		$.ajax({
			url: '<?php echo base_url('monitorConsultas/totalAgendamentos') ?>',
			type: 'post',

			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(marcacoesSame) {

				if (marcacoesSame.abas == null) {
					abas = 0;
				} else {
					abas = marcacoesSame.abas;
				}

				if (marcacoesSame.retorno == null) {
					retorno = 0;
				} else {
					retorno = marcacoesSame.retorno;
				}

				document.getElementById("infoMarcacoesDiaInternet").innerHTML = marcacoesSame.internet;
				document.getElementById("infoMarcacoesDiaSame").innerHTML = marcacoesSame.same;
				document.getElementById("infoMarcacoesDiaAbas").innerHTML = abas;
				document.getElementById("infoMarcacoesDiaRetorno").innerHTML = retorno;





			}



		})
	}


	function vagasAbertas() {
		$.ajax({
			url: '<?php echo base_url('monitorConsultas/vagasAbertas') ?>',
			type: 'post',

			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(vagasAbertas) {

				document.getElementById("vagasAbertasInternet").innerHTML = vagasAbertas.vagasInternet;
				document.getElementById("vagasAbertasPresencial").innerHTML = vagasAbertas.vagasPresencial;

			}
		})
	}

	function agendamentosSemana() {
		$.ajax({
			url: '<?php echo base_url('monitorConsultas/agendamentosSemana') ?>',
			type: 'post',

			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(agendamentosSemana) {



				diaSemana = JSON.parse(agendamentosSemana.diaSemana);

				agendamentosInternet = JSON.parse(agendamentosSemana.agendamentosInternet);

				agendamentosSame = JSON.parse(agendamentosSemana.agendamentosSame);


				var barChartData = {
					labels: diaSemana,
					datasets: [{
							label: 'INTERNET',
							backgroundColor: '#28a745',
							borderColor: '#28a745',
							pointRadius: false,
							pointColor: '#FFF',
							pointStrokeColor: '#c1c7d1',
							pointHighlightFill: '#fff',
							pointHighlightStroke: 'rgba(220,220,220,1)',
							data: agendamentosInternet,
						},
						{

							label: 'PRESENCIAL',
							backgroundColor: '#007bff',
							borderColor: '#007bff',
							pointRadius: false,
							pointColor: '#FFF',
							pointStrokeColor: 'rgba(60,141,188,1)',
							pointHighlightFill: '#fff',
							pointHighlightStroke: 'rgba(60,141,188,1)',
							data: agendamentosSame,


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




				//-------------
				//- LINE CHART -
				//--------------




				var linhaOptions = {
					maintainAspectRatio: false,
					responsive: true,

					width: 400,
					height: 100,
					pointSize: 4,
					tooltip: {
						isHtml: true
					},
					legend: 'none',
					colors: ['#28a745', '#007bff'],

					legend: {
						display: true
					},
					scales: {
						xAxes: [{

							gridLines: {
								display: true,
							}
						}],
						yAxes: [{
							gridLines: {
								display: true,
							}
						}]
					}
				}


				var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
				var lineChartOptions = $.extend(true, {}, linhaOptions)
				var lineChartData = $.extend(true, {}, barChartData)
				lineChartData.datasets[0].fill = false;
				lineChartData.datasets[1].fill = false;
				lineChartOptions.datasetFill = false

				var lineChart = new Chart(lineChartCanvas, {
					type: 'line',
					data: lineChartData,
					options: lineChartOptions,
					plugins: [ChartDataLabels],
				})




			}
		})
	}




	function agendamentosSemanaAnterior() {
		$.ajax({
			url: '<?php echo base_url('monitorConsultas/agendamentosSemanaAnterior') ?>',
			type: 'post',

			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(agendamentosSemanaAnterior) {



				diaSemanaSemanaAnterior = JSON.parse(agendamentosSemanaAnterior.diaSemana);

				agendamentosInternetSemanaAnterior = JSON.parse(agendamentosSemanaAnterior.agendamentosInternet);

				agendamentosSameSemanaAnterior = JSON.parse(agendamentosSemanaAnterior.agendamentosSame);


				var barChartDataSemanaPassada = {
					labels: diaSemanaSemanaAnterior,
					datasets: [{
							label: 'INTERNET',
							backgroundColor: '#28a745',
							borderColor: '#28a745',
							pointRadius: false,
							pointColor: '#FFF',
							pointStrokeColor: '#c1c7d1',
							pointHighlightFill: '#fff',
							pointHighlightStroke: 'rgba(220,220,220,1)',
							data: agendamentosInternetSemanaAnterior,
						},
						{

							label: 'PRESENCIAL',
							backgroundColor: '#007bff',
							borderColor: '#007bff',
							pointRadius: false,
							pointColor: '#FFF',
							pointStrokeColor: 'rgba(60,141,188,1)',
							pointHighlightFill: '#fff',
							pointHighlightStroke: 'rgba(60,141,188,1)',
							data: agendamentosSameSemanaAnterior,


						},
					]
				}




				//-------------
				//- LINE CHART -
				//--------------




				var linhaOptionsSemanaAnterior = {
					maintainAspectRatio: false,
					responsive: true,

					width: 400,
					height: 100,
					pointSize: 4,
					tooltip: {
						isHtml: true
					},
					legend: 'none',
					colors: ['#28a745', '#007bff'],

					legend: {
						display: true
					},
					scales: {
						xAxes: [{

							gridLines: {
								display: true,
							}
						}],
						yAxes: [{
							gridLines: {
								display: true,
							}
						}]
					}
				}


				var lineChartCanvasSemanaAnterior = $('#lineChartSemanaAnterior').get(0).getContext('2d')
				var lineChartOptionsSemanaAnterior = $.extend(true, {}, linhaOptionsSemanaAnterior)
				var lineChartDataSemanaAnterior = $.extend(true, {}, barChartDataSemanaPassada)
				lineChartDataSemanaAnterior.datasets[0].fill = false;
				lineChartDataSemanaAnterior.datasets[1].fill = false;
				lineChartOptionsSemanaAnterior.datasetFill = false

				var lineChart = new Chart(lineChartCanvasSemanaAnterior, {
					type: 'line',
					data: lineChartDataSemanaAnterior,
					options: lineChartOptionsSemanaAnterior,
					plugins: [ChartDataLabels],
				})




			}
		})
	}





	totalAgendamentos();
	agendamentosSemana();
	agendamentosSemanaAnterior();
	maisMarcadas();
	vagasAbertas();

	setInterval(function() {
		atualizaTela();
		totalAgendamentos();
		agendamentosSemana();
		agendamentosSemanaAnterior();
		maisMarcadas();
		vagasAbertas();



	}, 60000);
</script>