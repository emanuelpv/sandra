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
						<div class="col-md-8 mt-2">
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Indicações Clínicas</h3>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<div class="row">
						<div class="col-sm-12">
							<table id="data_tableindicacoesClinicas" class="table table-striped table-hover table-sm">
								<thead>
									<tr>
										<th>Nº</th>
										<th>Protocolo</th>
										<th>Especialidade</th>
										<th>Indicado em</th>
										<th>Data validade</th>
										<th>Concedido por</th>
										<th></th>
									</tr>
								</thead>
							</table>
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
<div id="indicacoesClinicasAddModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Indicações Clínicas</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="indicacoesClinicasAddForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>indicacoesClinicasAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codIndicacaoClinica" name="codIndicacaoClinica" class="form-control" placeholder="nº" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codEspecialidade"> CodEspecialidade: <span class="text-danger">*</span> </label>
								<select id="codEspecialidade" name="codEspecialidade" class="custom-select" required>
									<option></option>
									<option value="1">select1</option>
									<option value="2">select2</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="protocolo"> Protocolo: <span class="text-danger">*</span> </label>
								<input type="text" id="protocolo" name="protocolo" class="form-control" placeholder="Protocolo" maxlength="20" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="justificativa"> Justificativa: </label>
								<textarea cols="40" rows="5" id="justificativa" name="justificativa" class="form-control" placeholder="Justificativa"></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataInicio"> DataInicio: <span class="text-danger">*</span> </label>
								<input type="text" id="dataInicio" name="dataInicio" class="form-control" placeholder="DataInicio" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataEncerramento"> DataEncerramento: <span class="text-danger">*</span> </label>
								<input type="text" id="dataEncerramento" name="dataEncerramento" class="form-control" placeholder="DataEncerramento" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codAutor"> CodAutor: <span class="text-danger">*</span> </label>
								<input type="number" id="codAutor" name="codAutor" class="form-control" placeholder="CodAutor" maxlength="11" number="true" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codPaciente"> CodPaciente: <span class="text-danger">*</span> </label>
								<input type="number" id="codPaciente" name="codPaciente" class="form-control" placeholder="CodPaciente" maxlength="11" number="true" required>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="indicacoesClinicasAddForm-btn">Adicionar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Add modal content -->
<div id="indicacoesClinicasEditModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Indicações Clínicas</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="indicacoesClinicasEditForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>indicacoesClinicasEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codIndicacaoClinica" name="codIndicacaoClinica" class="form-control" placeholder="nº" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codEspecialidade"> CodEspecialidade: <span class="text-danger">*</span> </label>
								<select id="codEspecialidade" name="codEspecialidade" class="custom-select" required>
									<option></option>
									<option value="1">select1</option>
									<option value="2">select2</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="protocolo"> Protocolo: <span class="text-danger">*</span> </label>
								<input type="text" id="protocolo" name="protocolo" class="form-control" placeholder="Protocolo" maxlength="20" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="justificativa"> Justificativa: </label>
								<textarea cols="40" rows="5" id="justificativa" name="justificativa" class="form-control" placeholder="Justificativa"></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataInicio"> DataInicio: <span class="text-danger">*</span> </label>
								<input type="text" id="dataInicio" name="dataInicio" class="form-control" placeholder="DataInicio" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataEncerramento"> DataEncerramento: <span class="text-danger">*</span> </label>
								<input type="text" id="dataEncerramento" name="dataEncerramento" class="form-control" placeholder="DataEncerramento" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codAutor"> CodAutor: <span class="text-danger">*</span> </label>
								<input type="number" id="codAutor" name="codAutor" class="form-control" placeholder="CodAutor" maxlength="11" number="true" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codPaciente"> CodPaciente: <span class="text-danger">*</span> </label>
								<input type="number" id="codPaciente" name="codPaciente" class="form-control" placeholder="CodPaciente" maxlength="11" number="true" required>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="indicacoesClinicasEditForm-btn">Salvar</button>
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
		$('#data_tableindicacoesClinicas').DataTable({
			"paging": true,
			"deferRender": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('indicacoesClinicas/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});
	});

	function addindicacoesClinicas() {
		// reset the form 
		$("#indicacoesClinicasAddForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#indicacoesClinicasAddModal').modal('show');
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

				var form = $('#indicacoesClinicasAddForm');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('indicacoesClinicas/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#indicacoesClinicasAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function(response) {

						if (response.success === true) {
							$('#indicacoesClinicasAddModal').modal('hide');

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
								$('#data_tableindicacoesClinicas').DataTable().ajax.reload(null, false).draw(false);
							})

						} else {

							if (response.messages instanceof Object) {
								$.each(response.messages, function(index, value) {
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
									title: response.messages
								})

							}
						}
						$('#indicacoesClinicasAddForm-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#indicacoesClinicasAddForm').validate();
	}

	function editindicacoesClinicas(codIndicacaoClinica) {
		$.ajax({
			url: '<?php echo base_url('indicacoesClinicas/getOne') ?>',
			type: 'post',
			data: {
				codIndicacaoClinica: codIndicacaoClinica,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#indicacoesClinicasEditForm")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#indicacoesClinicasEditModal').modal('show');

				$("#indicacoesClinicasEditForm #codIndicacaoClinica").val(response.codIndicacaoClinica);
				$("#indicacoesClinicasEditForm #codEspecialidade").val(response.codEspecialidade);
				$("#indicacoesClinicasEditForm #protocolo").val(response.protocolo);
				$("#indicacoesClinicasEditForm #justificativa").val(response.justificativa);
				$("#indicacoesClinicasEditForm #dataInicio").val(response.dataInicio);
				$("#indicacoesClinicasEditForm #dataEncerramento").val(response.dataEncerramento);
				$("#indicacoesClinicasEditForm #codAutor").val(response.codAutor);
				$("#indicacoesClinicasEditForm #codPaciente").val(response.codPaciente);

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
						var form = $('#indicacoesClinicasEditForm');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('indicacoesClinicas/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#indicacoesClinicasEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
							},
							success: function(response) {

								if (response.success === true) {

									$('#indicacoesClinicasEditModal').modal('hide');


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
										$('#data_tableindicacoesClinicas').DataTable().ajax.reload(null, false).draw(false);
									})

								} else {

									if (response.messages instanceof Object) {
										$.each(response.messages, function(index, value) {
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
											title: response.messages
										})

									}
								}
								$('#indicacoesClinicasEditForm-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#indicacoesClinicasEditForm').validate();

			}
		});
	}

	function removeindicacoesClinicas(codIndicacaoClinica) {
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
					url: '<?php echo base_url('indicacoesClinicas/remove') ?>',
					type: 'post',
					data: {
						codIndicacaoClinica: codIndicacaoClinica,
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
								$('#data_tableindicacoesClinicas').DataTable().ajax.reload(null, false).draw(false);
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
</script>