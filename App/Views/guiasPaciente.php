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
<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-md-6 mt-2">
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Guias Paciente</h3>
						</div>
						<div class="col-md-3">
							<button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addguiasPaciente()" title="Adicionar">Adicionar</button>
						</div>
						<div class="col-md-3">
							<button type="button" class="btn btn-block btn-success" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="gerarQRCode()" title="Adicionar">Gerar QRCode</button>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">

					<div class="col-12 col-sm-12">
						<div class="card card-primary card-tabs">
							<div class="card-header p-0 pt-1">
								<ul class="nav nav-tabs" id="guias-tab" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" id="guias-novas-tab" data-toggle="pill" href="#guias-novas" role="tab" aria-controls="guias-novas" aria-selected="true">Novas Solicitações</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="guias-prontas-tab" data-toggle="pill" href="#guias-prontas" role="tab" aria-controls="guias-prontas" aria-selected="false">Prontas</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="guias-canceladas-tab" data-toggle="pill" href="#guias-canceladas" role="tab" aria-controls="guias-canceladas" aria-selected="false">Canceladas</a>
									</li>
								</ul>
							</div>
							<div class="card-body">
								<div class="tab-content" id="guias-tabContent">
									<div class="tab-pane fade show active" id="guias-novas" role="tabpanel" aria-labelledby="guias-novas-tab">

										<table id="data_tableguiasPaciente" class="table table-striped table-hover table-sm ">
											<thead>
												<tr>
													<th>Código</th>
													<th>NumeroGuia</th>
													<th>CodPlano</th>
													<th>Beneficiário</th>
													<th></th>
												</tr>
											</thead>
										</table>
									</div>

									<div class="tab-pane fade" id="guias-prontas" role="tabpanel" aria-labelledby="guias-prontas-tab">

										Em fase de integração com o SIRI.

									</div>
									<div class="tab-pane fade" id="guias-canceladas" role="tabpanel" aria-labelledby="guias-canceladas-tab">

										<table id="data_tableguiasPacienteCanceladas" class="table table-striped table-hover table-sm ">
											<thead>
												<tr>
													<th>Código</th>
													<th>NumeroGuia</th>
													<th>CodPlano</th>
													<th>Beneficiário</th>
													<th></th>
												</tr>
											</thead>
										</table>
									</div>
								</div>
							</div>
							<!-- /.card -->
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
<!-- Add modal content -->
<div id="guiasPacienteAddModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Guias Paciente</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="guiasPacienteAddForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>guiasPacienteAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codGuia" name="codGuia" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">

						<div class="col-md-4">
							<div class="form-group">
								<label for="numeroGuia"> Número Guia: </label>
								<input type="text" id="numeroGuia" name="numeroGuia" class="form-control" placeholder="NumeroGuia" maxlength="14">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="valorTotal"> ValorTotal: </label>
								<input type="number" id="valorTotal" name="valorTotal" class="form-control" placeholder="ValorTotal" maxlength="11" number="true">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="nomeBeneficiario"> NomeBeneficiario: </label>
								<input type="text" id="nomeBeneficiario" name="nomeBeneficiario" class="form-control" placeholder="NomeBeneficiario" maxlength="100">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataCriacao"> DataCriacao: </label>
								<input type="date" id="dataCriacao" name="dataCriacao" class="form-control" placeholder="DataCriacao">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataAtualizacao"> DataAtualizacao: </label>
								<input type="date" id="dataAtualizacao" name="dataAtualizacao" class="form-control" placeholder="DataAtualizacao">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codPlano"> CodPlano: </label>
								<input type="text" id="codPlano" name="codPlano" class="form-control" placeholder="CodPlano" maxlength="14">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for=""> Informações Complementares: </label>
								<textarea cols="40" rows="5" id="informacoesComplementares" name="informacoesComplementares" class="form-control" placeholder="Justificativa"></textarea>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="guiasPacienteAddForm-btn">Adicionar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div id="gerarQRCodeModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">QRCODE</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div style="margin-top:10px" class="d-flex justify-content-left" id="qrcodeDevolucao"></div>
				</div>
				<div style="margin-top:20px; margin-bottom:20px" class="row">
					Utilize este QRCODE nos documentos. Ele aponta para "<?php echo base_url()."/GuiasPaciente/devolucao/"?>"
				</div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>


<!-- Add modal content -->
<div id="guiasPacienteEditModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Guias Paciente</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="guiasPacienteEditForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>guiasPacienteEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codGuia" name="codGuia" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="numeroGuia"> Número Guia: </label>
								<input type="text" id="numeroGuia" name="numeroGuia" class="form-control" placeholder="NumeroGuia" maxlength="14">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="nomeBeneficiario"> NomeBeneficiario: </label>
								<input type="text" id="nomeBeneficiario" name="nomeBeneficiario" class="form-control" placeholder="NomeBeneficiario" maxlength="100">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataCriacao"> DataCriacao: </label>
								<input type="date" id="dataCriacao" name="dataCriacao" class="form-control" placeholder="DataCriacao">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codPlano"> CodPlano: </label>
								<input type="text" id="codPlano" name="codPlano" class="form-control" placeholder="CodPlano" maxlength="14">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="codStatusEdit"> Status: <span class="text-danger">*</span> </label>
								<select id="codStatusEdit" name="codStatus" class="custom-select" required>
									<option value="0"></option>
								</select>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label for=""> Informações Complementares: </label>
								<textarea cols="40" rows="5" id="informacoesComplementares" name="informacoesComplementares" class="form-control" placeholder="Justificativa"></textarea>
							</div>
						</div>
					</div>
					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="guiasPacienteEditForm-btn">Salvar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>

			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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

	$(function() {
		$('#data_tableguiasPaciente').DataTable({
			"bDestroy": true,
			"paging": true,
			"deferRender": true,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('guiasPaciente/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});
		$('#data_tableguiasPacienteCanceladas').DataTable({
			"bDestroy": true,
			"paging": true,
			"deferRender": true,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('guiasPaciente/canceladas') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});
	});

	function addguiasPaciente() {
		// reset the form 
		$("#guiasPacienteAddForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#guiasPacienteAddModal').modal('show');
		// submit the add from 
		$.validator.setDefaults({
			highlight: function(element) {
				$(element).addClass('is-invalid').removeClass('is-valid');
			},
			unhighlight: function(element) {
				$(element).removeClass('is-invalid').addClass('is-valid');
			},
			errorElement: 'div ',
			errorClass: 'invalid-feedback',
			errorPlacement: function(error, element) {
				if (element.parent('.input-group').length) {
					error.insertAfter(element.parent());
				} else if ($(element).is('.select')) {
					element.next().after(error);
				} else if (element.hasClass('select2')) {
					//error.insertAfter(element);
					error.insertAfter(element.next());
				} else if (element.hasClass('selectpicker')) {
					error.insertAfter(element.next());
				} else {
					error.insertAfter(element);
				}
			},

			submitHandler: function(form) {

				var form = $('#guiasPacienteAddForm');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('guiasPaciente/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#guiasPacienteAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function(response) {

						if (response.success === true) {
							$('#guiasPacienteAddModal').modal('hide');

							var Toast = Swal.mixin({
								toast: true,
								position: 'bottom-end',
								showConfirmButton: false,
								timer: 2000
							});
							Toast.fire({
								icon: 'success',
								title: response.canceladas
							}).then(function() {
								$('#data_tableguiasPaciente').DataTable().ajax.reload(null, false).draw(false);
								$('#data_tableguiasPacienteCanceladas').DataTable().ajax.reload(null, false).draw(false);
							})

						} else {

							if (response.canceladas instanceof Object) {
								$.each(response.canceladas, function(index, value) {
									var id = $("#" + index);

									id.closest('.form-control')
										.removeClass('is-invalid')
										.removeClass('is-valid')
										.addClass(value.length > 0 ? 'is-invalid' : 'is-valid');

									id.after(value);

								});
							} else {

								var Toast = Swal.mixin({
									toast: true,
									position: 'bottom-end',
									showConfirmButton: false,
									timer: 2000
								});
								Toast.fire({
									icon: 'error',
									title: response.canceladas
								})

							}
						}
						$('#guiasPacienteAddForm-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#guiasPacienteAddForm').validate();
	}




	function gerarQRCode() {

		$('#gerarQRCodeModal').modal('show');

		var URLDevolucao = '<?php echo base_url() . "/GuiasPaciente/devolucao/" ?>';

		document.getElementById("qrcodeDevolucao").innerHTML = "";

		qrcode = new QRCode("qrcodeDevolucao", {
			text: URLDevolucao,
			width: 160,
			height: 160,
			colorDark: "#000000",
			colorLight: "#ffffff",
			correctLevel: QRCode.CorrectLevel.H
		});
	}

	function editguiasPaciente(codGuia) {
		$.ajax({
			url: '<?php echo base_url('guiasPaciente/getOne') ?>',
			type: 'post',
			data: {
				codGuia: codGuia,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#guiasPacienteEditForm")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#guiasPacienteEditModal').modal('show');

				$("#guiasPacienteEditForm #codGuia").val(response.codGuia);
				$("#guiasPacienteEditForm #codPaciente").val(response.codPaciente);
				$("#guiasPacienteEditForm #valorTotal").val(response.valorTotal);
				$("#guiasPacienteEditForm #nomeBeneficiario").val(response.nomeBeneficiario);
				$("#guiasPacienteEditForm #dataCriacao").val(response.dataCriacao);
				$("#guiasPacienteEditForm #dataAtualizacao").val(response.dataAtualizacao);
				$("#guiasPacienteEditForm #codPlano").val(response.codPlano);
				$("#guiasPacienteEditForm #numeroGuia").val(response.numeroGuia);
				$("#guiasPacienteEditForm #informacoesComplementares").val(response.informacoesComplementares);


				//document.getElementById('botoesEditar').innerHTML=
				//'<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="guiasPacienteEditForm-btn">Salvar</button>'+
				//'<button class="btn btn-dark" data-toggle="tooltip" data-placement="top" title="Cancelar" onclick="cancelarGuiasPacienteAgora('+response.codGuia+')">Cancelar</button>'+
				//'<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>';


				$.ajax({
					url: '<?php echo base_url('guiasPaciente/listaStatusGuia') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(codStatusEdit) {

						$("#codStatusEdit").select2({
							data: codStatusEdit,
							dropdownParent: $('#guiasPacienteEditModal .modal-content'),
						})

						$('#codStatusEdit').val(response.codStatus); // Select the option with a value of '1'
						$('#codStatusEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

					}
				})


				// submit the edit from 
				$.validator.setDefaults({
					highlight: function(element) {
						$(element).addClass('is-invalid').removeClass('is-valid');
					},
					unhighlight: function(element) {
						$(element).removeClass('is-invalid').addClass('is-valid');
					},
					errorElement: 'div ',
					errorClass: 'invalid-feedback',
					errorPlacement: function(error, element) {
						if (element.parent('.input-group').length) {
							error.insertAfter(element.parent());
						} else if ($(element).is('.select')) {
							element.next().after(error);
						} else if (element.hasClass('select2')) {
							//error.insertAfter(element);
							error.insertAfter(element.next());
						} else if (element.hasClass('selectpicker')) {
							error.insertAfter(element.next());
						} else {
							error.insertAfter(element);
						}
					},

					submitHandler: function(form) {
						var form = $('#guiasPacienteEditForm');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('guiasPaciente/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#guiasPacienteEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
							},
							success: function(response) {

								if (response.success === true) {

									$('#guiasPacienteEditModal').modal('hide');


									var Toast = Swal.mixin({
										toast: true,
										position: 'bottom-end',
										showConfirmButton: false,
										timer: 2000
									});
									Toast.fire({
										icon: 'success',
										title: response.canceladas
									}).then(function() {
										$('#data_tableguiasPaciente').DataTable().ajax.reload(null, false).draw(false);
										$('#data_tableguiasPacienteCanceladas').DataTable().ajax.reload(null, false).draw(false);
									})

								} else {

									if (response.canceladas instanceof Object) {
										$.each(response.canceladas, function(index, value) {
											var id = $("#" + index);

											id.closest('.form-control')
												.removeClass('is-invalid')
												.removeClass('is-valid')
												.addClass(value.length > 0 ? 'is-invalid' : 'is-valid');

											id.after(value);

										});
									} else {
										var Toast = Swal.mixin({
											toast: true,
											position: 'bottom-end',
											showConfirmButton: false,
											timer: 2000
										});
										Toast.fire({
											icon: 'error',
											title: response.canceladas
										})

									}
								}
								$('#guiasPacienteEditForm-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#guiasPacienteEditForm').validate();

			}
		});
	}

	function removeguiasPaciente(codGuia) {
		Swal.fire({
			title: 'Você tem certeza que deseja remover?',
			text: "Você não poderá reverter após a confirmação",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {

			if (result.value) {
				$.ajax({
					url: '<?php echo base_url('guiasPaciente/remove') ?>',
					type: 'post',
					data: {
						codGuia: codGuia,
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
								title: response.canceladas
							}).then(function() {
								$('#data_tableguiasPaciente').DataTable().ajax.reload(null, false).draw(false);
								$('#data_tableguiasPacienteCanceladas').DataTable().ajax.reload(null, false).draw(false);
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
								title: response.canceladas
							})


						}
					}
				});
			}
		})
	}

	function cancelarGuiasPaciente(codGuia) {
		Swal.fire({
			title: 'Você tem certeza que deseja cancelar a guia?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {

			if (result.value) {
				$.ajax({
					url: '<?php echo base_url('guiasPaciente/cancelar') ?>',
					type: 'post',
					data: {
						codGuia: codGuia,
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
								title: response.canceladas
							}).then(function() {
								$('#data_tableguiasPaciente').DataTable().ajax.reload(null, false).draw(false);
								$('#data_tableguiasPacienteCanceladas').DataTable().ajax.reload(null, false).draw(false);
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
								title: response.canceladas
							})


						}
					}
				});
			}
		})
	}
</script>