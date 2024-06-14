<?php

if (session()->perfilSessao == 2) {
} else {
	if ($_GET['autorizacao'] !== '364f5b5504700506f2222e16cd2d7a0004') {

		print "NÃO AUTORIZADO!";
		exit();
	}
}


$tituloPainel = "TOTEN";

$codDepartamento = $_GET['codDepartamento'];
session()->codDepartamentoAtendimento = $codDepartamento;

?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!--	<meta http-equiv="refresh" content="300"> -->

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
	<link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/teclado-virtual/virtual-key.css">



	<script>
    var csrfName = '<?php echo csrf_token() ?>';
    var csrfHash = '<?php echo csrf_hash() ?>';
  </script>
    <input type="hidden" id="csrf_sandraPrincipal" name="csrf_sandraPrincipal" value="<?php echo csrf_hash() ?>">
</head>

<body>

	<div style="visibility:hidden" id="setEstilo"></div>
	<section style="min-height:85vh" class="content">
		<div class="row">
			<div class="col-12">
				<div class="card">
				</div>
				<div style="padding-left:0; padding-right:0;padding-top:0;padding-bottom:0" class="card-body">
					<div class="row justify-content-center">
						<input style="font-size:50px;width:300px" type="text" maxlength="11" id="cpf" class="teclado_text" required>

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





	<div id="gerarProtocolosModal" class="modal fade" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header bg-primary text-center p-3">
					<h4 class="modal-title text-white" id="info-header-modalLabel">Iniciar Protocolo</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="gerarProtocolosForm" class="pl-3 pr-3">
						<div class="row">
							<input type="hidden" id="codDepartamentoAtendimentoAdd" name="codDepartamento" value="<?php echo $codDepartamento ?>">
							<div class="col-md-12">
								<div class="form-group">

									<div id="dadosPessoa"></div>

								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<button type="button" onclick="gerarProtocolo()" class="btn btn-block btn-success btn-lg " data-toggle="tooltip" data-placement="top" title="NOVO SERVIÇO">NOVO SERVIÇO</button>
							</div>
							<div class="col-md-4">
								<button type="button" onclick="gerarProtocoloResultado()" class="btn btn-block btn-primary btn-lg " data-toggle="tooltip" data-placement="top" title="PEGAR RESULTADO">PEGAR RESULTADO</button>
							</div>
							<div class="col-md-4">
								<button type="button" onclick="cancelarGerarProtocolosModal()" class="btn btn-danger btn-lg" data-toggle="tooltip" title="CANCELAR">CANCELAR</button>
							</div>
						</div>
					</form>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div>



	<div style="position: absolute; height: 200mm" id="comprovanteEtiqueta80Modal" class="modal fade" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div style="width: 80mm" class="modal-content">
				<div class="modal-header bg-primary text-center p-3">
					<h4 class="modal-title text-white" id="info-header-modalLabel">Comprovante</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">

					<div style="margin-left:10px" id="areaImpressaoComprovanteEtiqueta80">
						<div style="width: 80mm important;height: 75mm important!;" class="row">
							<div class="col-sm-12">

								<div>
									<center><img alt="" style="text-align:center;width:60px;height:60px;" src="<?php echo base_url().'/imagens/organizacoes/'.session()->logo; ?>"></center>
								</div>
								<div style="text-align:center;font-weight: bold">
								<?php echo session()->descricaoOrganizacao; ?>



								</div>

								<div style="font-family: 'Arial';margin-top:20px;height: 80mm;">
									<div style="text-align:center;font-weight: bold;font-size:30px">SENHA: <span id="senhaCompletoComprovanteEtiqueta80"></span></div>
									<div style="text-align:left;;font-weight: bold;font-size:12px">Protocolo Nr: <span id="protocoloComprovanteEtiqueta80"></span></div>
									<div style="text-align:left;;font-weight: bold;font-size:12px">DATA: <span id="dataComprovanteEtiqueta80"></span></div>

									<!-- <div style="margin-top:20px" class="d-flex justify-content-center" id="qrcodeComprovanteEtiqueta80"></div> !-->

								</div>

							</div>

						</div>

						<!-- 

						<div style="margin-top:10px; border-top-style: dotted;" class="row">

						</div>
						<div style="margin-top:10px;font-family: 'Arial';margin-top:20px;text-align:left;font-weight: bold;font-size:12px">
							<div class="row">
								<b>Prezado usuário, leia atentamente as orientações a seguir:</b>
							</div>
							<div class="row">
								* Este é seu comprovante de solicitação de serviço.
							</div>

							<div class="row">
								* Guarde-o para que seja possível restrear sua solicitação.
							</div>


						</div>

						!-->
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" id="botaoImprimirComprovanteEtiqueta80">Imprimir</button>
					<button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
				</div>

			</div>
		</div>
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


<!-- qrcode -->
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/qrcode/qrcode.js"></script>


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
			url: '<?php echo base_url('toten/procuraPessoa') ?>',
			type: 'post',
			data: {
				cpf: $('#cpf').val(),
			},
			dataType: 'json',
			success: function(dadosPessoa) {

				if (dadosPessoa.success === true) {
					$('#gerarProtocolosModal').modal('show');
					Swal.close();
					document.getElementById("dadosPessoa").innerHTML = dadosPessoa.html;


				}
			}
		}).always(
			Swal.fire({
				title: 'Estamos buscando seus dados no banco de dados',
				html: 'Aguarde....',
				timerProgressBar: true,
				didOpen: () => {
					Swal.showLoading()


				}

			}))
	}


	function gerarProtocolo() {
		var form = $('#gerarProtocolosForm');

		$.ajax({
			url: '<?php echo base_url('atendimentoSenhas/gerarProtocolo') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(dadosPessoa) {

				if (dadosPessoa.success === true) {
					$('#data_tableatendimentoSenhas').DataTable().ajax.reload(null, false).draw(false);
					$('#gerarProtocolosModal').modal('hide');

					comprovanteEtiqueta80(dadosPessoa.senha, dadosPessoa.protocolo, dadosPessoa.data)

					var Toast = Swal.mixin({
						toast: true,
						position: 'bottom-end',
						showConfirmButton: false,
						timer: 2000
					});
					Toast.fire({
						icon: 'success',
						title: dadosPessoa.messages
					})

				}
			}
		})
	}

	function cancelarGerarProtocolosModal() {
		$('#gerarProtocolosModal').modal('hide');
		document.getElementById("cpf").value = '';
		tamanho = 0;

	}

	function gerarProtocoloResultado() {
		var form = $('#gerarProtocolosForm');

		$.ajax({
			url: '<?php echo base_url('atendimentoSenhas/gerarProtocoloResultado') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(dadosPessoa) {

				if (dadosPessoa.success === true) {
					$('#data_tableatendimentoSenhas').DataTable().ajax.reload(null, false).draw(false);
					$('#gerarProtocolosModal').modal('hide');

					comprovanteEtiqueta80(dadosPessoa.senha, dadosPessoa.protocolo, dadosPessoa.data)

					var Toast = Swal.mixin({
						toast: true,
						position: 'bottom-end',
						showConfirmButton: false,
						timer: 2000
					});
					Toast.fire({
						icon: 'success',
						title: dadosPessoa.messages
					})

				}
			}
		})
	}



	function comprovanteEtiqueta80(senha, protocolo, data) {

		//$('#comprovanteEtiqueta80Modal').modal('show');

		document.getElementById("botaoImprimirComprovanteEtiqueta80").onclick = function() {
			printElement(document.getElementById("areaImpressaoComprovanteEtiqueta80"));

			window.print();
		}


		document.getElementById("senhaCompletoComprovanteEtiqueta80").innerHTML = senha;
		document.getElementById("protocoloComprovanteEtiqueta80").innerHTML = protocolo;
		document.getElementById("dataComprovanteEtiqueta80").innerHTML = data;
		/*
			var URLComprovante = '<?php echo base_url() . "/atendimentos/?codagendamento=" ?>' + senha;
		
					document.getElementById("qrcodeComprovanteEtiqueta80").innerHTML = "";
					qrcode = new QRCode("qrcodeComprovanteEtiqueta80", {
						text: URLComprovante,
						width: 160,
						height: 160,
						colorDark: "#000000",
						colorLight: "#ffffff",
						correctLevel: QRCode.CorrectLevel.H
					});
			*/

		document.getElementById("setEstilo").innerHTML = '<style>@media screen {' +
			'#printSection {' +
			'display: none;' +

			'}' +
			'}' +

			'@media print {' +
			'@page {' +
			'size: 60mm 70mm;' +
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
			'width: 60mm;' +
			'height: 70mm;' +

			'}' +
			'}</style>';



		document.getElementById("cpf").value = '';
		tamanho = 0;

		document.getElementById('botaoImprimirComprovanteEtiqueta80').click();
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