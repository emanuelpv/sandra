<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">

	<title>Devolução de Guias Paciente</title>
	<meta name="description" content="Devolução de Guias Paciente" />

	<meta property="og:title" content="Devolução de Guias Paciente" />
	<meta property="og:url" content="<?php echo base_url() ?>" />
	<meta property="og:description" content="Sistema de Gestão Hospitalar Sandra 2.0" />
	<meta property="og:image" content="<?php echo base_url() . '/imagens/organizacoes/' . session()->logo; ?>" />

	<meta name="keywords" content=" <?php echo $siglaOrganizacao ?>, SANDRA, SANDRA 2.0, SGH, Gestão Hospitalar, Consultas, Exames, Encaminhamentos, Marcação">

	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

	<!-- Fontawesome Icons -->
	<link href="<?php echo base_url() ?>/assets/landing/css/all.min.css" rel="stylesheet">

	<!-- Icons -->

	<link rel="icon" href="<?php echo base_url() ?>/imagens/favicon.ico" />
	<link href="<?php echo base_url() . '/imagens/organizacoes/' . session()->logo; ?>" rel="icon">
	<link href="<?php echo base_url() ?>/imagens/apple-touch-icon.png" rel="apple-touch-icon">

	<link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/fontawesome-free/css/all.min.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/dist/css/adminlte.min.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">

	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<script>
		var csrfName = '<?php echo csrf_token() ?>';
		var csrfHash = '<?php echo csrf_hash() ?>';
	</script>
	<input type="hidden" id="csrf_sandraPrincipal" name="csrf_sandraPrincipal" value="<?php echo csrf_hash() ?>">
</head>

<?php

$codOrganizacao = session()->codOrganizacao;
$descricaoOrganizacao = session()->descricao;
$siglaOrganizacao = session()->siglaOrganizacao;

?>

<body class="hold-transition register-page">
	<div class="register-box">
		<div class="register-logo">
			<img style="width:110px" src="<?php echo base_url() . "/imagens/organizacoes/" . session()->logo ?>">
		</div>

		<div class="card">
			<div class="card-body register-card-body">
				<p style="font-weight: bold;" class="login-box-msg">DEVOLUÇÃO DE GUIA DO PACIENTE</p>

				<form id="guiasPacienteDevolucaoForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>guiasPacienteDevolucaoForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="input-group mb-3">
						<input type="number" autocomplete="off" class="form-control" id="numeroGuia" name="numeroGuia" placeholder="Número da Guia" required>
						<div class="input-group-append">
							<div class="input-group-text">
							</div>
						</div>
					</div>
					<div class="input-group mb-3">
						<input type="number" autocomplete="off" class="form-control" id="codPlano" name="codPlano" placeholder="Nº Nº Plano" required>
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-user"></span>
							</div>
						</div>
					</div>
					<div class="input-group mb-3">
						<input type="text" autocomplete="off" class="form-control" id="nomeBeneficiario" name="nomeBeneficiario" placeholder="Nome do beneficiário" required>
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-user"></span>
							</div>
						</div>
					</div>
					<div class="row">

					</div>
				</form>

				<div class="social-auth-links text-center">
					<button style="font-size:30px;font-weight: bold;" onclick="enviarSolicitacao()" type="button" class="btn btn-block btn-primary">Solicitar Devolução</button>

				</div>

			</div>
			<!-- /.form-box -->
		</div><!-- /.card -->
	</div>
	<!-- /.register-box -->

	<!-- jQuery -->
	<script src="<?php echo base_url() ?>/assets/adminlte/plugins/jquery/jquery.min.js"></script>
	<script src="<?php echo base_url() ?>/assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="<?php echo base_url() ?>/assets/adminlte/dist/js/adminlte.min.js"></script>
	<script src="<?php echo base_url() ?>/assets/adminlte/plugins/sweetalert2/sweetalert2.min.js"></script>


	<script>
		function enviarSolicitacao() {


			$.ajax({
				url: '<?php echo base_url('guiasPaciente/solicitacaoDevolucao') ?>',
				type: 'post',
				data: {
					numeroGuia: $("#guiasPacienteDevolucaoForm #numeroGuia").val(),
					codPlano: $("#guiasPacienteDevolucaoForm #codPlano").val(),
					nomeBeneficiario: $("#guiasPacienteDevolucaoForm #nomeBeneficiario").val(),
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
				dataType: 'json',
				success: function(response) {

					if (response.success === true) {
						Swal.fire({
							icon: 'success',
							title: response.messages,
							showConfirmButton: true,
							confirmButtonText: 'Ok',
						})

						$("#guiasPacienteDevolucaoForm #numeroGuia").val(null);
						$("#guiasPacienteDevolucaoForm #codPlano").val(null);
						$("#guiasPacienteDevolucaoForm #nomeBeneficiario").val(null);

					} else {
						Swal.fire({
							icon: 'error',
							title: response.messages,
							showConfirmButton: true,
							confirmButtonText: 'Ok',
						})

					}

				}
			})
		}
	</script>

</body>

</html>