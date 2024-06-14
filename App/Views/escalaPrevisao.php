<?php

$perfisAutorizados = array(2, 10, 16, 19);
$perfilAutorizado = 0;

if ($_GET['codEscala'] == NULL) {
	$codEscala = 0;
} else {
	$codEscala = $_GET['codEscala'];
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
<html lang="pt-BR">

<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">

	<title>Escala de Serviço</title>
	<meta name="description" content="Sistema de escala de serviços e plantões" />

	<meta property="og:title" content="Sistema de Gestão Hospitalar Sandra 2.0" />
	<meta property="og:url" content="<?php echo base_url() ?>" />
	<meta property="og:description" content="Sistema de escala de serviços e plantões" />
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

<?php
echo '<center><img style="width:150px" src="' . base_url() . '/imagens/organizacoes/' . session()->logo . '"></center>';



?>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/jquery/jquery.min.js"></script>



<?php
echo seletorEscala($codEscala);


echo '<div style="margin-top:50px; font-size:30px"><center> ESCALA DE SERVIÇOS E PLANTÕES</center></div>';

echo previsaoEscala($codEscala);

echo "<div style='margin-top:50px'></div>";




?>