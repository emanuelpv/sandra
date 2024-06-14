<?php

$perfisAutorizados = array(2, 10, 16, 19);
$perfilAutorizado = 0;
if ($_GET['unidade'] !== NULL) {

	$unidade = $_GET['unidade'];
} else {

	$unidade = 0;
}
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
	<title><?php echo session()->descricaoOrganizacao ?></title>
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

<body>
	<section class="content">

		<div style="margin-top:50px" class="row text-center">
			<div class="col-md-12">
				<img style="width:150px" src="<?php echo base_url() . "/imagens/organizacoes/" . session()->logo ?>">
				<div>
					<h6><b><?php echo session()->descricaoOrganizacao ?></b></h6>
				</div>
			</div>
		</div>
		<div class="row  text-center">
			<div style="margin-top:30px;font-size:30px; font-weight: bold;" class="col-md-12">
				PESQUISA DE SATISFAÇÃO
			</div>
		</div>

		<?php
		$nomeUnidade = nomeDepartamentoFull($unidade);
		if ($nomeUnidade == NULL) {
			$nomeUnidade = "";
		} else {
			$nomeUnidade = " no(a) " . $nomeUnidade;
		}
		?>
		<div class="row">
			<div style="margin-left:30px; margin-top:20px;font-size:30px; font-weight: bold;" class="col-md-12">
				Como foi o seu atendimento<?php echo $nomeUnidade ?>?
			</div>
		</div>

		<div style="margin-top:70px" class="row  justify-content-center">
			<div class="col-md-2">
				<button style="height:160px !important;" onclick="gravarSatisfacao(83,1,5)" type="button" class="btn btn-block btn-outline-success btn-lg">
					<div class="text-center"><img style="width:80px" src="<?php echo base_url() . "/imagens/muitoSatisfeito.png" ?>">
						<div>
							<div style="color:#000 !important;font-size:14px">Muito Satisfeito</div>
				</button>
			</div>
			<div class="col-md-2">
				<button style="height:160px !important;" onclick="gravarSatisfacao(83,1,4)" type="button" class="btn btn-block btn-outline-success btn-lg">
					<div class="text-center"><img style="width:80px" src="<?php echo base_url() . "/imagens/satisfeito.png" ?>">
						<div>
							<div style="color:#000 !important;font-size:14px">Satisfeito</div>
				</button>
			</div>

			<div class="col-md-2">
				<button style="height:160px !important;" onclick="gravarSatisfacao(83,1,3)" type="button" class="btn btn-block btn-outline-warning btn-lg">
					<div class="text-center"><img style="width:80px" src="<?php echo base_url() . "/imagens/neutro.png" ?>">
						<div>
							<div style="color:#000 !important;font-size:14px">Neutro</div>
				</button>
			</div>
			<div class="col-md-2">
				<button style="height:160px !important;" onclick="gravarSatisfacao(83,1,2)" type="button" class="btn btn-block btn-outline-danger btn-lg">
					<div class="text-center"><img style="width:80px" src="<?php echo base_url() . "/imagens/insatisfeito.png" ?>">
						<div>
							<div style="color:#000 !important;font-size:14px">Insatisfeito</div>
				</button>
			</div>
			<div class="col-md-2">
				<button style="height:160px !important;" onclick="gravarSatisfacao(83,1,1)" type="button" class="btn btn-block btn-outline-danger btn-lg">
					<div class="text-center"><img style="width:80px" src="<?php echo base_url() . "/imagens/muitoInsatisfeito.png" ?>">
						<div>
							<div style="color:#000 !important;font-size:14px">Muito Insatisfeito</div>
				</button>
			</div>


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

<script src="<?php echo base_url() ?>/assets/adminlte/plugins/sweetalert2/sweetalert2.min.js"></script>



<script>
	function gravarSatisfacao(codDepartamento, codPergunta, nota) {


		$.ajax({
			url: '<?php echo 'addPesquisaSatisfacao' ?>',
			type: 'post',
			data: {
				codDepartamento: codDepartamento,
				codPergunta: codPergunta,
				nota: nota,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {

				if (response.success === true) {
					Swal.fire({
						icon: 'success',
						title: response.messages,
						showConfirmButton: false,
						timer: 4000
					}).then(function() {
						location.reload();
					});

				} else {
					Swal.fire({
						icon: 'warning',
						title: response.messages,
						showConfirmButton: false,
						timer: 4000
					});
				}
			}
		})

	}
</script>
