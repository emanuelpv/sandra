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
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Notificações dos Módulos</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary" onclick="addmodulosNotificacao()" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<table id="data_tablemodulosNotificacao" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>Código</th>
								<th>Tipo</th>
								<th>Modelo</th>
								<th>Destino</th>
								<th>Observacoes</th>

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
<div id="add-modalmodulosNotificacao" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Notificações dos Módulos</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="add-formmodulosNotificacao" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>add-formmodulosNotificacao" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codModuloNotificacao" name="codModuloNotificacao" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codTipoNotificacao"> Tipo Notificação: <span class="text-danger">*</span> </label>
								<?php
								echo listboxTipoNotificacoes($this, 'Add');
								?>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="codModeloNotificacao"> Modelo Notificação: <span class="text-danger">*</span> </label>
								<?php
								echo listboxModelosNotificacoes($this, 'Add');
								?>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="destinoNotificacao"> Destino Notificação: <span class="text-danger">*</span> </label>
								<?php
								echo listboxDestinatariosNotificacoes($this, 'Add');
								?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="observacoes"> Observacoes: </label>
								<textarea cols="40" rows="5" id="observacoes" name="observacoes" class="form-control" placeholder="Observacoes" maxlength="60"></textarea>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-xs btn-primary" id="add-formmodulosNotificacao-btn">Adicionar</button>
							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Add modal content -->
<div id="edit-modalmodulosNotificacao" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Notificações dos Módulos</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="edit-formmodulosNotificacao" class="pl-3 pr-3">

					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>edit-formmodulosNotificacao" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codModuloNotificacao" name="codModuloNotificacao" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codTipoNotificacao"> Tipo Notificação: <span class="text-danger">*</span> </label>
								<?php
								echo listboxTipoNotificacoes($this);
								?>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="codModeloNotificacao"> Modelo Notificação: <span class="text-danger">*</span> </label>
								<?php
								echo listboxModelosNotificacoes($this);
								?>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="destinoNotificacao"> Destino Notificação: <span class="text-danger">*</span> </label>
								<?php
								echo listboxDestinatariosNotificacoes($this);
								?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="observacoes"> Observacoes: </label>
								<textarea cols="40" rows="5" id="observacoes" name="observacoes" class="form-control" placeholder="Observacoes" maxlength="60"></textarea>
							</div>
						</div>
					</div>


					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-xs btn-primary" id="edit-formmodulosNotificacao-btn">Salvar</button>
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
		$('#data_tablemodulosNotificacao').DataTable({
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('modulosNotificacao/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});
	});

	function addmodulosNotificacao() {
		// reset the form 
		$("#add-formmodulosNotificacao")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#add-modalmodulosNotificacao').modal('show');
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

				var form = $('#add-formmodulosNotificacao');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('modulosNotificacao/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#add-formmodulosNotificacao-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
								$('#data_tablemodulosNotificacao').DataTable().ajax.reload(null, false).draw(false);
								$('#add-modalmodulosNotificacao').modal('hide');
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
						$('#add-formmodulosNotificacao-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#add-formmodulosNotificacao').validate();
	}

	function editmodulosNotificacao(codModuloNotificacao) {
		$.ajax({
			url: '<?php echo base_url('modulosNotificacao/getOne') ?>',
			type: 'post',
			data: {
				codModuloNotificacao: codModuloNotificacao,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),

			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#edit-formmodulosNotificacao")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#edit-modalmodulosNotificacao').modal('show');

				$("#edit-formmodulosNotificacao #codModuloNotificacao").val(response.codModuloNotificacao);
				$("#edit-formmodulosNotificacao #codTipoNotificacao").val(response.codTipoNotificacao);
				$("#edit-formmodulosNotificacao #codModeloNotificacao").val(response.codModeloNotificacao).select2();


				$("#edit-formmodulosNotificacao #destinoNotificacao").val(response.destinoNotificacao.split(",")).select2();
				$("#edit-formmodulosNotificacao #observacoes").val(response.observacoes);

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
						var form = $('#edit-formmodulosNotificacao');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('modulosNotificacao/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#edit-formmodulosNotificacao-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
										$('#data_tablemodulosNotificacao').DataTable().ajax.reload(null, false).draw(false);
										$('#edit-modalmodulosNotificacao').modal('hide');
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
								$('#edit-formmodulosNotificacao-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#edit-formmodulosNotificacao').validate();

			}
		});
	}

	function removemodulosNotificacao(codModuloNotificacao) {
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
					url: '<?php echo base_url('modulosNotificacao/remove') ?>',
					type: 'post',
					data: {
						codModuloNotificacao: codModuloNotificacao,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),

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
								$('#data_tablemodulosNotificacao').DataTable().ajax.reload(null, false).draw(false);
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