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
	codAtendimentoPrescricao = '<?php echo $codAtendimentoPrescricao ?>';
</script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/html5-qrcode/html5-qrcode.min.js"></script> <!--https://scanapp.org/html5-qrcode-docs/docs/intro-->

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					Processamento da Enfermagem
				</div>
				<!-- /.card-header -->
				<div class="card-body">


					<div class="row">
						<div style="margin-bottom:10px" class="col-md-2">
							<button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="lerQRCode" onclick="lerQRCode()" title="lerQRCode">Ler QRCode</button>
						</div>
						<div style="margin-bottom:10px" class="col-md-2">
							<button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="lerNrPrescricao" onclick="lerNrPrescricao()" title="lerNrPrescricao">Ler Nº Prescrição</button>
						</div>
					</div>

					<?php echo $dadosAtendimento ?>


					<div class="row">
						<div class="col-12 col-sm-12">
							<div class="card card-primary card-tabs">
								<div class="card-header p-0 pt-1">
									<ul class="nav nav-tabs" id="aba-tab" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" id="abaPrescricao-tab" data-toggle="pill" href="#abaPrescricao" role="tab" aria-controls="abaPrescricao" aria-selected="true">Medicamentos<span style="margin-left: 10px;" id="qtdMedicamentos" class="right badge badge-warning"></span></a>
										</li>
										<li class="nav-item">
											<a class="nav-link" id="abaDieta-tab" data-toggle="pill" href="#abaDieta" role="tab" aria-controls="abaDieta" aria-selected="true">Dietas<span style="margin-left: 10px;" id="qtdDietas" class="right badge badge-warning"></span></a>
										</li>
										<li class="nav-item">
											<a class="nav-link" id="abaMateriais-tab" data-toggle="pill" href="#abaMateriais" role="tab" aria-controls="abaMateriais" aria-selected="true">Materiais<span style="margin-left: 10px;" id="qtdMateriais" class="right badge badge-warning"></span></a>
										</li>
										<li class="nav-item">
											<a class="nav-link" id="abaCuidadosEnfermagem-tab" data-toggle="pill" href="#abaCuidadosEnfermagem" role="tab" aria-controls="abaCuidadosEnfermagem" aria-selected="false">Cuidados de Enfermagem<span style="margin-left: 10px;" id="qtdCuidadosEnfermagem" class="right badge badge-warning"></span></a>
										</li>
										<li class="nav-item">
											<a class="nav-link" id="abaBalancoHidrico-tab" data-toggle="pill" href="#abaBalancoHidrico" role="tab" aria-controls="abaBalancoHidrico" aria-selected="false">Balanço Hidroco</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" id="abaOutros-tab" data-toggle="pill" href="#abaOutros" role="tab" aria-controls="abaOutros" aria-selected="false">Outros</a>
										</li>
									</ul>
								</div>
								<div class="card-body">
									<div class="tab-content" id="aba-tabContent">
										<div class="tab-pane fade show active" id="abaPrescricao" role="tabpanel" aria-labelledby="abaPrescricao-tab">

											<div style="margin-top:10px" class="row">
												<div class="col-md-12">
													<table id="data_tableprescricaoMedicamentos" class="table table-striped table-hover table-sm">
														<thead>
															<tr>
																<th>Código</th>
																<th>Item</th>
																<th>Qtde</th>
																<th>Und</th>
																<th>Via</th>
																<th>Classificação</th>
																<th>Total</th>
																<th>Status</th>
																<th>Autor</th>
																<th></th>
															</tr>
														</thead>
													</table>
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="abaDieta" role="tabpanel" aria-labelledby="abaDieta-tab">

											<div style="margin-top:10px" class="row">
												<div class="col-md-12">

													<table id="data_tableprescricaoDietas" class="table table-striped table-hover table-sm">
														<thead>
															<tr>
																<th>Código</th>
																<th>Item</th>
																<th>Qtde</th>
																<th>Und</th>
																<th>Via</th>
																<th>Classificação</th>
																<th>Total</th>
																<th>Status</th>
																<th>Autor</th>
																<th></th>
															</tr>
														</thead>
													</table>
												</div>
											</div>

										</div>
										<div class="tab-pane fade" id="abaMateriais" role="tabpanel" aria-labelledby="abaMateriais-tab">

											<div style="margin-top:10px" class="row">
												<div class="col-md-12">
													<table id="data_tableprescricoesMaterial" class="table table-striped table-hover table-sm">
														<thead>
															<tr>
																<th>Código</th>
																<th>Material</th>
																<th>Qtde</th>
																<th>Autor</th>
																<th>Ult Atualização</th>
																<th>Status</th>
																<th></th>
															</tr>
														</thead>
													</table>
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="abaCuidadosEnfermagem" role="tabpanel" aria-labelledby="abaCuidadosEnfermagem-tab">
											<div class="row">
												<div class="col-sm-12">
													<table id="data_tableprescricoesCuidados" class="table table-striped table-hover table-sm">
														<thead>
															<tr>
																<th>Código</th>
																<th>Cuidado</th>
																<th>Observações</th>
																<th>Apraza</th>
																<th>Autor</th>
																<th>Ult Atualização</th>
																<th>Status</th>
																<th></th>
															</tr>
														</thead>
													</table>
												</div>
											</div>

										</div>
										<div class="tab-pane fade" id="abaBalancoHidrico" role="tabpanel" aria-labelledby="abaBalancoHidrico-tab">
											Balanço Hidrico
										</div>
										<div class="tab-pane fade" id="abaOutros" role="tabpanel" aria-labelledby="abaOutros-tab">
											Outros
										</div>
									</div>
								</div>
								<!-- /.card -->
							</div>
						</div>

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

<div id="QRCodeModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Aponte a camera para o código</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<div id="reader" width="600px"></div>



			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>
<div id="NrPrescricaoModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Buscar a prescrição pelo Nº</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<form id="buacaNrPrescricaoForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>buacaNrPrescricaoForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<input autocomplete="off" type="text" id="buscaPorcodAtendimentoPrescricao" name="codAtendimentoPrescricao">

					<span>
						<button type="button" class="btn btn-primary btn-sm" onclick="buscaNrPrescricaoAgora()" title="Buscar prescrição">Buscar prescrição</button>
					</span>
				</form>

			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>
<?php
echo view('tema/rodape');
?>
<script>
	$(document).on('show.bs.modal', '.modal', function() {
		var zIndex = 1040 + (10 * $('.modal:visible').length);
		$(this).css('z-index', zIndex);
		setTimeout(function() {
			$('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
		}, 0);
	});


	var qtdMedicamentos = 0;
	var qtdMateriais = 0;
	var qtdDietas = 0;
	var qtdCuidadosEnfermagem = 0;

	var element = document.getElementById("sidebarClosed");
	element.classList.add("sidebar-closed");
	element.classList.add("sidebar-collapse");


	$('#data_tableprescricaoMedicamentos').DataTable({
		"bDestroy": true,
		"paging": true,
		"deferRender": true,
		"pageLength": 50,
		"lengthChange": false,
		"searching": true,
		"ordering": false,
		"info": true,
		"autoWidth": false,
		"responsive": true,
		"ajax": {
			"url": '<?php echo base_url('prescricaoMedicamentos/getAllPorPrescricao') ?>',
			"type": "POST",
			"dataType": "json",
			async: "true",
			data: {
				codAtendimentoPrescricao: codAtendimentoPrescricao,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			}
		},
		"drawCallback": function(settings, json) {
			var api = this.api();
			var qtdMedicamentos = api.rows().count();
			if (qtdMedicamentos > 0) {
				document.getElementById("qtdMedicamentos").innerHTML = qtdMedicamentos
			}
		}
	});

	$('#data_tableprescricoesMaterial').DataTable({
		"bDestroy": true,
		"paging": true,
		"deferRender": true,
		"pageLength": 50,
		"lengthChange": false,
		"searching": true,
		"ordering": false,
		"info": true,
		"autoWidth": false,
		"responsive": true,
		"ajax": {
			"url": '<?php echo base_url('prescricoesMaterial/getAllPorPrescricao') ?>',
			"type": "POST",
			"dataType": "json",
			async: "true",
			data: {
				codAtendimentoPrescricao: codAtendimentoPrescricao,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			}
		},
		"drawCallback": function(settings, json) {
			var api = this.api();
			var qtdMateriais = api.rows().count();
			if (qtdMateriais > 0) {
				document.getElementById("qtdMateriais").innerHTML = qtdMateriais
			}
		}
	});

	$('#data_tableprescricoesCuidados').DataTable({
		"bDestroy": true,
		"paging": true,
		"deferRender": true,
		"pageLength": 50,
		"lengthChange": false,
		"searching": true,
		"ordering": false,
		"info": true,
		"autoWidth": false,
		"responsive": true,
		"ajax": {
			"url": '<?php echo base_url('prescricoesCuidados/getAllPorPrescricao') ?>',
			"type": "POST",
			"dataType": "json",
			async: "true",
			data: {
				codAtendimentoPrescricao: codAtendimentoPrescricao,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			}
		},
		"drawCallback": function(settings, json) {
			var api = this.api();
			var qtdCuidadosEnfermagem = api.rows().count();
			if (qtdCuidadosEnfermagem > 0) {
				document.getElementById("qtdCuidadosEnfermagem").innerHTML = qtdCuidadosEnfermagem
			}
		}
	});


	$('#data_tableprescricaoDietas').DataTable({
		"bDestroy": true,
		"paging": true,
		"deferRender": true,
		"pageLength": 50,
		"lengthChange": false,
		"searching": true,
		"ordering": false,
		"info": true,
		"autoWidth": false,
		"responsive": true,
		"ajax": {
			"url": '<?php echo base_url('prescricaoMedicamentos/getAllPorPrescricaoDietas') ?>',
			"type": "POST",
			"dataType": "json",
			async: "true",
			data: {
				codAtendimentoPrescricao: codAtendimentoPrescricao,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			}
		},
		"drawCallback": function(settings, json) {
			var api = this.api();
			var qtdDietas = api.rows().count();
			if (qtdDietas > 0) {
				document.getElementById("qtdDietas").innerHTML = qtdDietas
			}
		}
	});

	function buscaNrPrescricaoAgora() {

		if ($("#buscaPorcodAtendimentoPrescricao").val() == null || $("#buscaPorcodAtendimentoPrescricao").val() == '') {

		} else {
			$('#NrPrescricaoModal').modal('hide');
			window.location.href = '<?php echo base_url() ?>/localizador/prescricao/' + $("#buscaPorcodAtendimentoPrescricao").val();

		}


	}

	function checkinMedicamento(codPrescricaoMedicamento, codMedicamento, ano, mes, dia, hora, minuto) {

		anoX = String(ano).padStart(4, '0');
		diaX = String(dia).padStart(2, '0');
		mesX = String(mes).padStart(2, '0');
		horaX = String(hora).padStart(2, '0');
		minutoX = String(minuto).padStart(2, '0');
		$.fn.modal.Constructor.prototype.enforceFocus = function() {};

		Swal.fire({
			title: 'Você tem certeza que deseja fazer o Check-in do medicamento?',
			icon: 'info',
			html: '<div class="row"><div class="col-sm-6"><label for="Hora Checagem">Hora Checagem: <span class="text-danger">*</span> </label><div class="input-group mb-3"></div><input style="" type="datetime-local" id="dataHoraChecagem" name="dataHoraChecagem" value="' + anoX + '-' + mesX + '-' + diaX + 'T' + horaX + ':' + minutoX + '" class="form-control" autofocus></div></div>',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar',
			input: 'textarea',
			inputLabel: 'Observações (Opcional)',

		}).then((result) => {

			if (result.isConfirmed) {

				$.ajax({
					url: '<?php echo base_url('PrescricaoMedicamentos/checkinMedicamento') ?>',
					type: 'post',
					data: {
						codPrescricaoMedicamento: codPrescricaoMedicamento,
						codMedicamento: codMedicamento,
						observacoes: result.value,
						dataHoraChecagem: document.getElementById("dataHoraChecagem").value,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					dataType: 'json',
					success: function(response) {

						if (response.success === true) {
							var Toast = Swal.mixin({
								toast: true,
								position: 'bottom-end',
								showConfirmButton: false,
								timer: 2000
							});
							Toast.fire({
								icon: 'success',
								title: response.messages
							}).then(function() {
								$('#data_tableprescricaoMedicamentos').DataTable().ajax.reload(null, false).draw(false);
							})
						} else {
							var Toast = Swal.mixin({
								toast: true,
								position: 'bottom-end',
								showConfirmButton: false,
								timer: 2000
							});
							Toast.fire({
								icon: 'error',
								title: response.messages
							})


						}
					}
				});
			}

		})
	}

	function lerNrPrescricao() {
		$('#NrPrescricaoModal').modal('show');

	}

	function lerQRCode() {
		$('#QRCodeModal').modal('show');

		function onScanSuccess(decodedText, decodedResult) {
			// handle the scanned code as you like, for example:
			url = decodedText;
			window.location.href = url;

		}

		function onScanFailure(error) {
			// handle scan failure, usually better to ignore and keep scanning.
			// for example:
			console.warn(`Code scan error = ${error}`);
		}

		let html5QrcodeScanner = new Html5QrcodeScanner(
			"reader", {
				fps: 10,
				qrbox: {
					width: 250,
					height: 250
				}
			},
			/* verbose= */
			false);
		html5QrcodeScanner.render(onScanSuccess, onScanFailure);



	}
</script>