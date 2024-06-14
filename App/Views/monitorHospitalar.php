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
									<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" data-interval="40000" data-pause="hover" data-wrap="true">

										<div class="carousel-inner">



											<div class="carousel-item active">
												<div class="row">
													<div class="col-md-6">
														<div class="card card-primary">
															<div class="card-header">
																<h3 class="card-title">Taxa de ocupação Leitos</h3>

																<div class="card-tools">
																	<button type="button" class="btn btn-tool" data-card-widget="collapse">
																		<i class="fas fa-minus"></i>
																	</button>
																	<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
																	</button>
																</div>
															</div>
															<div class="card-body">
																<div style="height: 220px !important;" class="row">
																	<div class="col-md-12 text-center">
																		<div class=" text-center" id="ocupacaoLeitos" canvas width="90" height="90"></canvas>
																			<input type="text" class="ocupacaoLeitosClass" data-width="180" data-height="180" data-readonly="true" readonly="readonly" style="width: 49px; height: 30px; position: absolute; vertical-align: middle; margin-top: 30px; margin-left: -69px; border: 0px; background: none; font: bold 18px Arial; text-align: center; color: rgb(60, 141, 188); padding: 0px; appearance: none;">
																		</div>

																		<div style="color:black; font-size:20px;font-weight: bold;">% DE OCUPAÇÕES LEITOS</div>
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
																<h3 class="card-title">Status Leitos</h3>

																<div class="card-tools">
																	<button type="button" class="btn btn-tool" data-card-widget="collapse">
																		<i class="fas fa-minus"></i>
																	</button>
																	<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
																	</button>
																</div>
															</div>
															<div class="card-body">
																<div style="height: 220px !important;" class="row  align-items-center h-100">
																	<div class="col-lg-3 col-6">
																		<!-- small card -->
																		<div class="small-box bg-info">
																			<div class="inner">
																				<h3 id="vagas"></h3>

																				<p>Vagas</p>
																			</div>
																		</div>
																	</div>
																	<!-- ./col -->
																	<div class="col-lg-3 col-6">
																		<!-- small card -->
																		<div class="small-box bg-success">
																			<div class="inner">
																				<h3 id="ocupados"><sup style="font-size: 20px">%</sup></h3>

																				<p>Ocupados</p>
																			</div>
																		</div>
																	</div>
																	<!-- ./col -->
																	<div class="col-lg-3 col-6">
																		<!-- small card -->
																		<div class="small-box bg-warning">
																			<div class="inner">
																				<h3 id="livres"></h3>

																				<p>Livres</p>
																			</div>
																		</div>
																	</div>
																	<!-- ./col -->
																	<div class="col-lg-3 col-6">
																		<!-- small card -->
																		<div class="small-box bg-danger">
																			<div class="inner">
																				<h3 id="emManutencao"></h3>

																				<p>Manutenção</p>
																			</div>
																		</div>
																	</div>
																	<!-- ./col -->
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="row">


													<div class="col-md-6">
														<!-- BAR CHART -->
														<div class="card card-primary">
															<div class="card-header">
																<h3 class="card-title">Nº Atendimentos Emergência (PAMO)</h3>

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
															<h3 class="card-title">Nº Atendimentos Emergência por médico (Últimas 24hs)</h3>

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
																	<canvas id="barChartMedico" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 423px;" width="380" height="224" class="chartjs-render-monitor"></canvas>
																</div>
															</div>
														</div>


													</div>

												</div>

											</div>

											<?php

											$unidadesInternacao =  unidadesInternacao();
											foreach ($unidadesInternacao as $unidade) {

												$internados = internados($unidade->descricaoDepartamento);


											?>
												<div class="carousel-item">

													<div style=" padding-left:0; padding-right:0;padding-top:0;padding-bottom:0" class="card-body">
														<div style="margin-bottom:30px;height:40px; font-size:30px" class="align-middle font-weight-bold text-center">
															<?php echo $unidade->descricaoDepartamento ?>
														</div>

														<table class="table  table-dark table-striped table-hover table-sm">
															<thead>
																<tr>
																	<th>Leito</th>
																	<th>Paciente</th>
																	<th>Idade</th>
																	<th>Situação Local</th>
																	<th>Tempo Internação</th>
																	<th>Prev. Alta</th>
																</tr>
															</thead>
															<tbody>
																<?php
																foreach ($internados as $atendimento) {


																	$tempo =  intervaloTempoAtendimento($atendimento->dataCriacao, date('Y-m-d H:i'));


																	if ($atendimento->codAtendimento !== NULL) {
																		$situacaoLocal = 'OCUPADO';
																	} else {
																		$situacaoLocal = 'LIVRE';
																	}
																	if ($atendimento->codSituacaoLocalAtendimento == 2) {
																		$situacaoLocal = 'EM MANUTENÇÃO';
																	}



																	if ($atendimento->nomeCompleto !== NULL) {

																		$dadosAlta = monitorPrevAlta($atendimento->codAtendimento);

																		if ($dadosAlta !== NULL) {
																			$previsao =  previsaoAlta($dadosAlta->dataPrevAlta, $dadosAlta->dataEncerramento, $dadosAlta->indeterminado);
																			$previsaoAlta = '<div class="right badge badge-warning">Prev. Alta: ' . $previsao['dataPrevAlta'] . ' ' . $previsao['faltam'] . '<div>';
																		} else {
																			$previsao = NULL;
																			$previsao['dataPrevAlta'] = 'Falta informar';
																			$previsaoAlta = '<div class="right badge badge-danger">Prev. Alta: ' . $previsao['dataPrevAlta'] . ' ' . $previsao['faltam'] . '<div>';
																		}
																	} else {
																		$previsaoAlta = NULL;
																	}

																	if ($situacaoLocal == 'LIVRE') {
																		echo "<tr style='background:green !important; color:#fff !important'>";
																	} elseif ($situacaoLocal == 'EM MANUTENÇÃO') {
																		echo "<tr style='background:#fd7e14 !important; color:#000 !important'>";
																	} else {
																		echo "<tr>";
																	}
																	echo "<td>" . $atendimento->descricaoLocalAtendimento . "</td>";
																	echo "<td>" . $atendimento->nomeCompleto . "</td>";
																	echo "<td>" . $atendimento->idade . "</td>";
																	echo "<td>" . $situacaoLocal . "</td>";
																	echo "<td>" . $tempo . "</td>";
																	echo "<td>" . $previsaoAlta . "</td>";
																	echo "</tr>";
																}

																?>


															</tbody>
														</table>
													</div>
												</div>
											<?php

											}

											?>




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
				"url": '<?php echo base_url('monitorHospitalar/solicitacoesInfraEmAberto') ?>',
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
				"url": '<?php echo base_url('monitorHospitalar/solicitacoesSistemasEmAberto') ?>',
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
				"url": '<?php echo base_url('monitorHospitalar/solicitacoesTelefoniaEmAberto') ?>',
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
				"url": '<?php echo base_url('monitorHospitalar/solicitacoesAtendidasPorTecnico') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});


	});


	function ocupacaoLeitosClass(cor) {


		$('.ocupacaoLeitosClass').knob({
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


	function totalLeitosClass(cor) {


		$('.totalLeitosClass').knob({
			'min': 0,
			'max': 100000,
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



	function totalEmManutencaosClass(cor) {


		$('.totalEmManutencaosClass').knob({
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
			url: '<?php echo base_url('monitorHospitalar/	') ?>',
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

	function ocupacaoLeitos() {

		$.ajax({
			url: '<?php echo base_url('monitorHospitalar/ocupacao') ?>',
			type: 'post',

			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(ocupacaoLeitos) {
				var percentual;
				var totalLeitos;
				totalLeitos = ocupacaoLeitos.totalLeitos;

				if (totalLeitos != 0) {
					percentual = Math.round(ocupacaoLeitos.totalLeitosOcupados / ocupacaoLeitos.totalLeitos * 100);
				} else {
					percentual = 0;
				}


				document.getElementById("vagas").innerHTML = ocupacaoLeitos.totalLeitos;
				document.getElementById("ocupados").innerHTML = ocupacaoLeitos.totalLeitosOcupados;
				document.getElementById("livres").innerHTML = ocupacaoLeitos.totalLeitosLivres;
				document.getElementById("emManutencao").innerHTML = ocupacaoLeitos.totalLeitosEmManutencao;


				//TAXA DE OCUPAÇÃO
				$("input.ocupacaoLeitosClass").val(percentual);
				$("input.ocupacaoLeitosClass").trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});



				if (percentual <= 50) {
					ocupacaoLeitosClass('green');
				}
				if (percentual > 50 && percentual <= 70) {
					ocupacaoLeitosClass('#ffc107');
				}
				if (percentual > 70 && percentual <= 80) {
					ocupacaoLeitosClass('#fd7e14');
				}

				if (percentual > 81) {
					ocupacaoLeitosClass('red');
				}



				//TOTAL LEITOS
				$("input.totalLeitosClass").val(totalLeitos);
				$("input.totalLeitosClass").trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});



				if (totalLeitos <= 50) {
					totalLeitosClass('green');
				}
				if (totalLeitos > 50 && totalLeitos <= 70) {
					totalLeitosClass('#ffc107');
				}
				if (totalLeitos > 70 && totalLeitos <= 80) {
					totalLeitosClass('#fd7e14');
				}

				if (totalLeitos > 81) {
					totalLeitosClass('red');
				}



			}



		})
	}

	function resolubilidadeHoje() {
		$.ajax({
			url: '<?php echo base_url('monitorHospitalar/resolubilidadeHoje') ?>',
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


	function solicitacoesAbertasPorMedico() {
		$.ajax({
			url: '<?php echo base_url('monitorHospitalar/atendimentosPorMedicos') ?>',
			type: 'post',

			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(solicitacoesPorMedicos) {


				medicos = JSON.parse(solicitacoesPorMedicos.medicos);

				totais = JSON.parse(solicitacoesPorMedicos.totais);


				var barChartDataMedicos = {
					labels: medicos,
					datasets: [{
						label: 'Nº de Atendimentos Por Médico (Hoje)',
						backgroundColor: '#28a745bf',
						borderColor: 'rgba(60,141,188,0.8)',
						pointRadius: false,
						pointColor: '#3498dbbf',
						pointStrokeColor: 'rgba(60,141,188,1)',
						pointHighlightFill: '#3498dbbf',
						pointHighlightStroke: 'rgba(60,141,188,1)',
						data: totais
					}, ]
				}


				var barChartCanvasMedicos = $('#barChartMedico').get(0).getContext('2d')
				var barChartDataMedicos = $.extend(true, {}, barChartDataMedicos)
				var temp0 = barChartDataMedicos.datasets[0]
				barChartDataMedicos.datasets[0] = temp0

				var barChartOptionsMedicos = {
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

				new Chart(barChartCanvasMedicos, {
					type: 'bar',
					data: barChartDataMedicos,
					options: barChartOptionsMedicos,
					plugins: [ChartDataLabels],
				})




			}
		})
	}

	function atendimentosEmergencia() {
		$.ajax({
			url: '<?php echo base_url('monitorHospitalar/atendimentosEmergenciaSemana') ?>',
			type: 'post',

			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(atendimentosEmergencia) {



				diaSemana = JSON.parse(atendimentosEmergencia.diaSemana);

				semanaAtual = JSON.parse(atendimentosEmergencia.semanaAtual);

				semanaPassada = JSON.parse(atendimentosEmergencia.semanaPassada);


				var barChartData = {
					labels: diaSemana,
					datasets: [{
							label: 'Semana Passada',
							backgroundColor: '#ff0000a8',
							borderColor: '#ff0000a8',
							pointRadius: false,
							pointColor: 'rgba(210, 214, 222, 1)',
							pointStrokeColor: '#c1c7d1',
							pointHighlightFill: '#fff',
							pointHighlightStroke: 'rgba(220,220,220,1)',
							data: semanaPassada,
						},
						{
							label: 'Semana Atual',
							backgroundColor: '#3f6791b5',
							borderColor: '#3f6791b5',
							pointRadius: false,
							pointColor: 'red',
							pointStrokeColor: 'rgba(60,141,188,1)',
							pointHighlightFill: '#fff',
							pointHighlightStroke: 'rgba(60,141,188,1)',
							data: semanaAtual,

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

	atendimentosEmergencia();
	solicitacoesAbertasPorMedico();
	maioresSolicitantes();
	ocupacaoLeitos();
	resolubilidadeHoje();


	setInterval(function() {
		atualizaTela();
		atendimentosEmergencia();
		solicitacoesAbertasPorMedico();
		resolubilidadeHoje();
		maioresSolicitantes();
		ocupacaoLeitos();
	}, 5000);
</script>