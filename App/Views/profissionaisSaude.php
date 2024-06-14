<?php
//É NECESSÁRIO EM TODAS AS VIEWS

$codOrganizacao = session()->codOrganizacao;


?>

<?php
//É NECESSÁRIO EM TODAS AS VIEWS

?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
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


</head>


<style>
	body {
		height: 100vh;
		padding: 0;
	}
</style>

<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">



		<section class="content">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<div class="row">
								<div class="col-md-8 mt-2">
									<h3 style="font-size:30px;font-weight: bold;" class="card-title">Médicos/Dentistas</h3>
								</div>

							</div>
						</div>
						<!-- /.card-header -->
						<div class="card-body">
							<style>
								.borda {
									background: -webkit-linear-gradient(left top, #fffe01 0%, #28a745 100%);
									border-radius: 1000px;
									padding: 3px;
									width: 100%;
									height: 100%;

								}
							</style>

							<div class="row">


								<?php


								$profissionaisSaude = array();
								foreach ($data as $medico) {
									if (!in_array($medico->nomeExibicao, $profissionaisSaude)) {
										array_push(
											$profissionaisSaude,
											array(
												'nomeExibicao' => $medico->nomeExibicao,
												'fotoPerfil' => $medico->fotoPerfil,
												'nomeConselho' => $medico->nomeConselho,
												'numeroInscricao' => $medico->numeroInscricao,
											)
										);
									}
								}

								foreach ($profissionaisSaude as $medico) {

									$fotoPerfil = "no_image.jpg";
									if ($medico['fotoPerfil'] !== NULL and $medico['fotoPerfil'] !== "") {
										$fotoPerfil = base_url() . '/arquivos/imagens/pessoas/' . $medico['fotoPerfil'];
									} else {
										$fotoPerfil = base_url() . '/arquivos/imagens/pessoas/no_image.jpg';
									}

								?>
									<div class="col-md-4">

										<div class="callout callout-info">


											<div class="row">
												<div class="col-md-3">
													<img class="borda" style="width:100px" alt="" src="<?php echo $fotoPerfil ?>">
												</div>
												<div class="col-md-9">

													<div style="font-size:18px;font-weight: bold;"><?php echo $medico['nomeExibicao']; ?></div>

													<?php
													$especialidade = "";
													foreach ($data as $medico2) {
														if ($medico['nomeExibicao'] == $medico2->nomeExibicao) {

															$especialidade .= $medico2->descricaoEspecialidade." | ";
													?>
													<?php
														}
													}
													$especialidade = rtrim($especialidade, "| ");
													?>
													
													<div style="font-size:14px;"><?php echo $especialidade?></div>


													<div style="font-size:14px;"><?php echo $medico['nomeConselho'] . " " . $medico['numeroInscricao']; ?></div>


												</div>
											</div>

										</div>


									</div>
								<?php

								}
								?>
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
		<!-- Add modal content -->
		<div id="add-modaltermos" class="modal fade" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-xl">
				<div class="modal-content">
					<div class="modal-header bg-primary text-center p-3">
						<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Termos de Aceite</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						<form id="add-formtermos" class="pl-3 pr-3">
							<div class="row">
								<input type="hidden" id="codTermo" name="codTermo" class="form-control" placeholder="Código" maxlength="11" required>
							</div>
							<div class="row">

								<div class="col-md-12">
									<div class="form-group">
										<label for="assunto"> Assunto: <span class="text-danger">*</span> </label>
										<input type="text" id="assunto" name="assunto" class="form-control" placeholder="Assunto" maxlength="100" required>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="termo"> Termo: <span class="text-danger">*</span> </label>
										<textarea cols="40" rows="5" id="termoAdd" name="termo" class="form-control" placeholder="Termo" required></textarea>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="checkboxstatus"> Status: </label>
										<i type="button" class="fas fa-info-circle swalstatus"></i>

										<div class="icheck-primary d-inline">
											<style>
												input[type=checkbox] {
													transform: scale(1.8);
												}
											</style>
											<input style="margin-left:5px;" name='codStatus' type="checkbox" id="checkboxstatus">


										</div>
									</div>
								</div>
							</div>



							<div class="form-group text-center">
								<div class="btn-group">
									<button type="submit" class="btn btn-xs btn-primary" id="add-formtermos-btn">Adicionar</button>
									<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
								</div>
							</div>
						</form>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div>


		<div id="profissionaisSaudeModal" class="modal fade" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-xl">
				<div class="modal-content">
					<div class="modal-header bg-primary text-center p-3">
						<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Termos de Aceite</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
					
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div>

	</div>
	<!-- /.content-wrapper -->
	<footer class="main-footer">
		<div class="float-right d-none d-sm-block">
			<b>Version</b> 2.0
		</div>
		<strong><span>Desenvolvido pela Seção de Tecnologia da Informação do <?php echo session()->siglaOrganizacao?></span></strong> 
		<div>

			<body>
				<!-- <div>Registration closes in <span id="time">05:00</span> minutes!</div> -->
			</body>
		</div>
	</footer>

	<!-- Control Sidebar -->
	<aside class="control-sidebar control-sidebar-dark">
		<!-- Control sidebar content goes here -->
	</aside>
	<!-- /.control-sidebar -->
	</div>
	<!-- ./wrapper -->

	<!-- jQuery -->
	<script src="plugins/jquery/jquery.min.js"></script>
	<!-- jQuery UI 1.11.4 -->
	<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
	<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
	<script>
		$.widget.bridge('uibutton', $.ui.button)
	</script>
	<!-- Bootstrap 4 -->
	<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- ChartJS -->
	<script src="plugins/chart.js/Chart.min.js"></script>
	<!-- Sparkline -->
	<script src="plugins/sparklines/sparkline.js"></script>
	<!-- JQVMap -->
	<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
	<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
	<!-- jQuery Knob Chart -->
	<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
	<!-- daterangepicker -->
	<script src="plugins/moment/moment.min.js"></script>
	<script src="plugins/daterangepicker/daterangepicker.js"></script>
	<!-- Tempusdominus Bootstrap 4 -->
	<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
	<!-- Summernote -->
	<script src="plugins/summernote/summernote-bs4.min.js"></script>
	<!-- overlayScrollbars -->
	<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
	<!-- AdminLTE App -->
	<script src="dist/js/adminlte.js"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="dist/js/demo.js"></script>
	<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
	<script src="dist/js/pages/dashboard.js"></script>
</body>

</html>