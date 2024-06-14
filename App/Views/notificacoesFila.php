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
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Notificações na FIla</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addnotificacoesFila()" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<table id="data_tablenotificacoesFila" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>Código</th>
								<th>Conteudo</th>
								<th>Remetente</th>
								<th>Destinatário</th>
								<th>CodOrganizacao</th>
								<th>CodProtocoloNotificacao</th>

								<th></th>
							</tr>
						</thead>
					</table>
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
<div id="add-modalnotificacoesFila" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Notificações na FIla</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="add-formnotificacoesFila" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="codNotificacao" name="codNotificacao" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="conteudo"> Conteudo: <span class="text-danger">*</span> </label>
								<textarea cols="40" rows="5" id="conteudo" name="conteudo" class="form-control" placeholder="conteudo" required></textarea>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="remetente"> Remetente: <span class="text-danger">*</span> </label>
								<input type="text" id="remetente" name="remetente" class="form-control" placeholder="Remetente" maxlength="100" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="destinatario"> Destinatário: <span class="text-danger">*</span> </label>
								<input type="text" id="destinatario" name="destinatario" class="form-control" placeholder="Destinatário" maxlength="100" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codProtocoloNotificacao"> CodProtocoloNotificacao: <span class="text-danger">*</span> </label>
								<input type="number" id="codProtocoloNotificacao" name="codProtocoloNotificacao" class="form-control" placeholder="CodProtocoloNotificacao" maxlength="11" number="true" required>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="add-formnotificacoesFila-btn">Adicionar</button>
							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Add modal content -->
<div id="edit-modalnotificacoesFila" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Notificações na FIla</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="edit-formnotificacoesFila" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="codNotificacao" name="codNotificacao" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="conteudo"> Conteudo: <span class="text-danger">*</span> </label>
								<textarea cols="40" rows="5" id="conteudo" name="conteudo" class="form-control" placeholder="conteudo" required></textarea>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="remetente"> Remetente: <span class="text-danger">*</span> </label>
								<input type="text" id="remetente" name="remetente" class="form-control" placeholder="Remetente" maxlength="100" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="destinatario"> Destinatário: <span class="text-danger">*</span> </label>
								<input type="text" id="destinatario" name="destinatario" class="form-control" placeholder="Destinatário" maxlength="100" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codProtocoloNotificacao"> CodProtocoloNotificacao: <span class="text-danger">*</span> </label>
								<input type="number" id="codProtocoloNotificacao" name="codProtocoloNotificacao" class="form-control" placeholder="CodProtocoloNotificacao" maxlength="11" number="true" required>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="edit-formnotificacoesFila-btn">Salvar</button>
							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
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
	$(function() {
		$('#data_tablenotificacoesFila').DataTable({
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('notificacoesFila/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true"
			}
		});
	});

	function addnotificacoesFila() {
		// reset the form 
		$("#add-formnotificacoesFila")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#add-modalnotificacoesFila').modal('show');
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

				var form = $('#add-formnotificacoesFila');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('notificacoesFila/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#add-formnotificacoesFila-btn').html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function(response) {

						if (response.success === true) {

							Swal.fire({
								position: 'bottom-end',
								icon: 'success',
								title: response.messages,
								showConfirmButton: false,
								timer: 1500
							}).then(function() {
								$('#data_tablenotificacoesFila').DataTable().ajax.reload(null, false).draw(false);
								$('#add-modalnotificacoesFila').modal('hide');
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
								Swal.fire({
									position: 'bottom-end',
									icon: 'error',
									title: response.messages,
									showConfirmButton: false,
									timer: 1500
								})

							}
						}
						$('#add-formnotificacoesFila-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#add-formnotificacoesFila').validate();
	}

	function editnotificacoesFila(codNotificacao) {
		$.ajax({
			url: '<?php echo base_url('notificacoesFila/getOne') ?>',
			type: 'post',
			data: {
				codNotificacao: codNotificacao
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#edit-formnotificacoesFila")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#edit-modalnotificacoesFila').modal('show');

				$("#edit-formnotificacoesFila #codNotificacao").val(response.codNotificacao);
				$("#edit-formnotificacoesFila #conteudo").val(response.conteudo);
				$("#edit-formnotificacoesFila #remetente").val(response.remetente);
				$("#edit-formnotificacoesFila #destinatario").val(response.destinatario);
				$("#edit-formnotificacoesFila #codOrganizacao").val(response.codOrganizacao);
				$("#edit-formnotificacoesFila #codProtocoloNotificacao").val(response.codProtocoloNotificacao);

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
						var form = $('#edit-formnotificacoesFila');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('notificacoesFila/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#edit-formnotificacoesFila-btn').html('<i class="fa fa-spinner fa-spin"></i>');
							},
							success: function(response) {

								if (response.success === true) {

									Swal.fire({
										position: 'bottom-end',
										icon: 'success',
										title: response.messages,
										showConfirmButton: false,
										timer: 1500
									}).then(function() {
										$('#data_tablenotificacoesFila').DataTable().ajax.reload(null, false).draw(false);
										$('#edit-modalnotificacoesFila').modal('hide');
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
										Swal.fire({
											position: 'bottom-end',
											icon: 'error',
											title: response.messages,
											showConfirmButton: false,
											timer: 1500
										})

									}
								}
								$('#edit-formnotificacoesFila-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#edit-formnotificacoesFila').validate();

			}
		});
	}

	function removenotificacoesFila(codNotificacao) {
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
					url: '<?php echo base_url('notificacoesFila/remove') ?>',
					type: 'post',
					data: {
						codNotificacao: codNotificacao
					},
					dataType: 'json',
					success: function(response) {

						if (response.success === true) {
							Swal.fire({
								position: 'bottom-end',
								icon: 'success',
								title: response.messages,
								showConfirmButton: false,
								timer: 1500
							}).then(function() {
								$('#data_tablenotificacoesFila').DataTable().ajax.reload(null, false).draw(false);
							})
						} else {
							Swal.fire({
								position: 'bottom-end',
								icon: 'error',
								title: response.messages,
								showConfirmButton: false,
								timer: 1500
							})


						}
					}
				});
			}
		})
	}
</script>