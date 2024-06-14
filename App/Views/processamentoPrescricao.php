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
	codPrescricaoMedicamento = '<?php echo $codPrescricaoMedicamento ?>';
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
											<a class="nav-link active" id="abaPrescricao-tab" data-toggle="pill" href="#abaPrescricao" role="tab" aria-controls="abaPrescricao" aria-selected="true">Prescricao</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" id="abaCuidadosEnfermagem-tab" data-toggle="pill" href="#abaCuidadosEnfermagem" role="tab" aria-controls="abaCuidadosEnfermagem" aria-selected="false">Cuidados de Enfermagem</a>
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
										<div class="tab-pane fade" id="abaCuidadosEnfermagem" role="tabpanel" aria-labelledby="abaCuidadosEnfermagem-tab">
											Cuidados de Enfermagem
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

					<input type="text" id="buscaPorCodPrescricaoMedicamento" name="codPrescricaoMedicamento">

					<div class="row">
						<div>
							<button type="button" class="btn btn-primary" onclick="buscaNrPrescricaoAgora()" title="Buscar prescrição">Buscar prescrição</button>
						</div>
					</div>
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
		"autoWidth": true,
		"responsive": true,
		"ajax": {
			"url": '<?php echo base_url('prescricaoMedicamentos/getAllPorPrescricao') ?>',
			"type": "POST",
			"dataType": "json",
			async: "true",
			data: {
				codAtendimentoPrescricao: codPrescricaoMedicamento,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
		}
	});


	function buscaNrPrescricaoAgora() {

		$('#NrPrescricaoModal').modal('hide');
		window.location.href = '<?php echo base_url() ?>/localizador/prescricao/' + $("#buscaPorCodPrescricaoMedicamento").val();

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