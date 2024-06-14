<?php

if (session()->perfilSessao == 2) {
} else {
	if ($_GET['autorizacao'] !== '364f5b5504700506f2222e16cd2d7a0004') {

		print "NÃO AUTORIZADO!";
		exit();
	}
}


$tituloPainel = "PONTO ELETRÔNICO";

$codDepartamento = $_GET['codDepartamento'];
session()->codDepartamentoAtendimento = $codDepartamento;

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

	<link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/toastr/toastr.min.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/select2/css/select2.min.css">

	<link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

	<!-- DATATABLES -->
	<link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/teclado-virtual/virtual-key.css">


	<script>
		var csrfName = '<?php echo csrf_token() ?>';
		var csrfHash = '<?php echo csrf_hash() ?>';
	</script>
	<input type="hidden" id="csrf_sandraPrincipal" name="csrf_sandraPrincipal" value="<?php echo csrf_hash() ?>">
</head>

<style>
	#minhaFoto {
		width: 160px;
		height: 125px;
		border: 1px solid black;
	}
</style>

<body>

	<div style="visibility:hidden" id="setEstilo"></div>
	<section style="min-height:85vh" class="content">
		<div class="row">
			<div class="col-12">
				<div class="card">
				</div>
				<div style="padding-left:0; padding-right:0;padding-top:0;padding-bottom:0" class="card-body">

					<div class="row justify-content-center">
					<input type="hidden" id="<?php echo csrf_token() ?>add-form-pessoa" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input autocomplete="off" placeholder="Digite seu CPF " style="font-size:50px;width:320px" type="text" maxlength="11" id="cpf" class="teclado_text" required>

					</div>
					<div class="row justify-content-center">
						<div class="col-md-6 text-center">
							<div class="card" style="transition: all 0.15s ease 0s; height: inherit; width: inherit;">

								<div class="col-md-12">
									<div style="padding-left:0; padding-right:0;padding-top:0;padding-bottom:0" class="card-body">

										<div class="table-responsive">
											<table style="width:100%" class="table_teclado">
												<tr>
													<td style="width:100px"><button style="font-size:30px;font-weight: bold;width:100%" type="button" class="btn btn-block btn-primary">1</button></td>
													<td style="width:100px"><button style="font-size:30px;font-weight: bold;width:100%" type="button" class="btn btn-block btn-primary">2</button></td>
													<td style="width:100px"><button style="font-size:30px;font-weight: bold;width:100%" type="button" class="btn btn-block btn-primary">3</button></td>
												</tr>
												<tr>
													<td style="width:100px"><button style="font-size:30px;font-weight: bold;width:100%" type="button" class="btn btn-block btn-primary">4</button></td>
													<td style="width:100px"><button style="font-size:30px;font-weight: bold;width:100%" type="button" class="btn btn-block btn-primary">5</button></td>
													<td style="width:100px"><button style="font-size:30px;font-weight: bold;width:100%" type="button" class="btn btn-block btn-primary">6</button></td>
												</tr>
												<tr>
													<td style="width:100px"><button style="font-size:30px;font-weight: bold;width:100%" type="button" class="btn btn-block btn-primary">7</button></td>
													<td style="width:100px"><button style="font-size:30px;font-weight: bold;width:100%" type="button" class="btn btn-block btn-primary">8</button></td>
													<td style="width:100px"><button style="font-size:30px;font-weight: bold;width:100%" type="button" class="btn btn-block btn-primary">9</button></td>
												</tr>
												<tr>
													<td colspan="2"><button style="font-size:30px;font-weight: bold" type="button" class="btn btn-block btn-primary">0</button></td>
													<td style="width:100px"><button style="font-size:30px;font-weight: bold;width:95%" type="button" class="btn btn-block btn-primary btn_delete"><img src="<?php echo base_url("/imagens/borrar.png") ?>"></button></td>
												</tr>
											</table>
											<button style="font-size:30px;font-weight: bold;" onclick="verificaCadastro()" type="button" class="btn btn-block btn-success">AVANÇAR</button>
										</div>
									</div>

								</div>

							</div>
						</div>
					</div>
				</div>

			</div>

		</div>

	</section>





	<div id="registrarPresencaModal" class="modal fade" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">

				<div class="modal-body">
					<form id="registrarPresencaForm" class="pl-3 pr-3">
						<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>registrarPresencaModal" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

							<div class="col-md-12">
								<div class="form-group">

									<div id="dadosPessoa">


									</div>
									<div class="row">
										<div class="col-md-6">
											<div id="minhaFoto"></div>

										</div>
										<div class="col-md-6">
											<div style="margin-top:30px;font-size:80px;font-weight: bold;" id="time"></div>

										</div>
									</div>

								</div>
							</div>
						</div>
						<div class="row d-flex align-items-center justify-content-center">
							<div class="col-md-4">
								<center><button id="mostraBotaoRegistraPresenca" type="button" onclick="registrarPresenca()" class="btn btn-block btn-success btn-lg " data-toggle="tooltip" data-placement="top" title="NOVO SERVIÇO">REGISTRAR PRESENÇA</button>
							</div>
						</div>
					</form>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div>


	<footer style="margin-left :0px !important" class="main-footer">
		<div class="float-right d-none d-sm-block">
			<b>SANDRA Version</b> 2.0
		</div>
		<strong><span>Desenvolvedor Emanuel Peixoto Vicente | <a href="https://www.linkedin.com/in/emanuelpv/">https://www.linkedin.com/in/emanuelpv/</a></span></strong>
		<div>

			<body>
				<!-- <div>Registration closes in <span id="time">05:00</span> minutes!</div> -->
			</body>
		</div>
	</footer>

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

<script src="<?php echo base_url() ?>/assets/adminlte/plugins/sweetalert2/sweetalert2.min.js"></script>


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
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/select2/js/select2.full.min.js"></script>


<script src="<?php echo base_url() ?>/assets/adminlte/plugins/webcamjs/webcam.min.js"></script>

<script>
	Webcam.set({
		width: 320,
		height: 240,
		image_format: 'jpeg',
		jpeg_quality: 90
	});
	Webcam.attach('#minhaFoto');
</script>

<script>
	var tamanho = 0;
	$(document).ready(function() {
		$('.table_teclado tr td').click(function() {
			var number = $(this).text();

			if (number == '') {
				if (tamanho > 0 && tamanho <= 11) {
					--tamanho;
					$('#cpf').val($('#cpf').val().substr(0, $('#cpf').val().length - 1)).focus();

				}
			} else {
				if (tamanho >= 0 && tamanho < 11) {
					++tamanho;
					$('#cpf').val($('#cpf').val() + number).focus();

				}
			}

		});
	});


	function checkTime(i) {
		if (i < 10) {
			i = "0" + i;
		}
		return i;
	}

	function startTime() {
		var today = new Date();
		var h = today.getHours();
		var m = today.getMinutes();
		var s = today.getSeconds();
		// add a zero in front of numbers<10
		m = checkTime(m);
		s = checkTime(s);
		document.getElementById('time').innerHTML = h + ":" + m + ":" + s;
		t = setTimeout(function() {
			startTime()
		}, 500);
	}
	startTime();


	function registrarPresenca() {

		// take snapshot and get image data
		Webcam.snap(function(data_uri) {

			fotoPerfilTmp = data_uri;

			codPessoa = document.getElementById('codPessoa').value;
			cpf = document.getElementById('cpf').value;
			nomePessoa = document.getElementById('nomePessoa').value;

			$.ajax({
				url: '<?php echo base_url('pontoEletronico/registrarPresenca') ?>',
				type: 'post',
				dataType: 'json',
				data: {
					imagem: fotoPerfilTmp,
					codPessoa: codPessoa,
					nomePessoa: nomePessoa,
					cpf: cpf,
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},

				
				success: function(registro) {
					
					if (registro.success === true) {
						
						Swal.fire({
							icon: 'success',
							title: 'Registro realizado às ' + registro.hora,
							html: '<img alt="" width="160px" height="auto" src="' + registro.data_url + '"/>',
							showConfirmButton: false,
							timer: 4000
						}).then(function() {
							location.reload();
						})


					} else {

					}

				}
			})


		});
	}

	function verificaCadastro() {

		if ($('#cpf').val().length < 11 || $('#cpf').val() == '') {

			Swal.fire({
				position: 'bottom-end',
				icon: 'error',
				title: 'Informe um CPF válido',
				showConfirmButton: false,
				timer: 2000
			})
			//exit();

		}
		$.ajax({
			url: '<?php echo base_url('pontoEletronico/procuraPessoa') ?>',
			type: 'post',
			data: {
				cpf: $('#cpf').val(),
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(dadosPessoa) {

				if (dadosPessoa.success === true) {
					$('#registrarPresencaModal').modal('show');
					Swal.close();
					document.getElementById("dadosPessoa").innerHTML = dadosPessoa.html;
					$('#mostraBotaoRegistraPresenca').show();


				} else {
					Swal.fire({
						icon: 'warning',
						title: 'CPF não localizado. Tente novamente ou procure a informática!',
						showConfirmButton: false,
						timer: 4000
					});
				}
			}
		}).always(
			Swal.fire({
				title: 'Estamos confirmando sua identidade',
				html: 'Aguarde....',
				timerProgressBar: true,
				didOpen: () => {
					Swal.showLoading()


				}

			}))
	}
</script>