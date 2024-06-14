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
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Serviço SMTP</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary" onclick="addSMTP()" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<table id="data_table" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>Código</th>
								<th>Nome Servidor</th>
								<th>IP Servidor</th>
								<th>Porta SMTP</th>
								<th>Login</th>
								<th>Senha</th>
								<th>Email Retorno</th>
								<th>Protocolo</th>
								<th>Status SMTP</th>

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
<div id="add-modalSMTP" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar <?php echo $title ?></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="add-formSMTP" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>add-formSMTP" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codServidorSMTP" name="codServidorSMTP" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="descricaoServidorSMTP"> Nome Servidor: <span class="text-danger">*</span> </label>
								<input type="text" id="descricaoServidorSMTP" name="descricaoServidorSMTP" class="form-control" placeholder="Nome Servidor" maxlength="60" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="ipServidorSMTP"> IP Servidor: <span class="text-danger">*</span> </label>
								<input type="text" id="ipServidorSMTP" name="ipServidorSMTP" class="form-control" placeholder="IP Servidor" maxlength="100" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="portaSMTP"> Porta SMTP: <span class="text-danger">*</span> </label>
								<input type="text" id="portaSMTP" name="portaSMTP" class="form-control" placeholder="Porta SMTP" maxlength="10" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="loginSMTP"> Login: <span class="text-danger">*</span> </label>
								<input type="text" id="loginSMTP" name="loginSMTP" class="form-control" placeholder="Login" maxlength="100" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="senhaSMTP"> Senha: <span class="text-danger">*</span> </label>
								<input type="text" id="senhaSMTP" name="senhaSMTP" class="form-control" placeholder="Senha" maxlength="64" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="emailRetorno"> Email Retorno: <span class="text-danger">*</span> </label>
								<input type="text" id="emailRetorno" name="emailRetorno" class="form-control" placeholder="Email Retorno" maxlength="100" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="protocoloSMTP"> Protocolo: <span class="text-danger">*</span> </label>
								<select id="protocoloSMTP" name="protocoloSMTP" class="custom-select" required>
									<option value=""></option>
									<option value="1">SSL</option>
									<option value="2">TLS</option>
									<option value="3">STARTTLS</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="statusSMTP"> Status SMTP: <span class="text-danger">*</span> </label>
								<select id="statusSMTP" name="statusSMTP" class="custom-select" required>
									<option value=""></option>
									<option value="1">Ativado</option>
									<option value="2">Desativado</option>
								</select>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-xs btn-primary" id="add-formSMTP-btn">Adicionar</button>
							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Add modal content -->
<div id="edit-modalSMTP" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="text-center bg-primary p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualização</h4>
			</div>
			<div class="modal-body">
				<form id="edit-formSMTP" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>edit-formSMTP" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codServidorSMTP" name="codServidorSMTP" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="descricaoServidorSMTP"> Nome Servidor: <span class="text-danger">*</span> </label>
								<input type="text" id="descricaoServidorSMTP" name="descricaoServidorSMTP" class="form-control" placeholder="Nome Servidor" maxlength="60" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="ipServidorSMTP"> IP Servidor: <span class="text-danger">*</span> </label>
								<input type="text" id="ipServidorSMTP" name="ipServidorSMTP" class="form-control" placeholder="IP Servidor" maxlength="100" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="portaSMTP"> Porta SMTP: <span class="text-danger">*</span> </label>
								<input type="text" id="portaSMTP" name="portaSMTP" class="form-control" placeholder="Porta SMTP" maxlength="10" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="loginSMTP"> Login: <span class="text-danger">*</span> </label>
								<input type="text" id="loginSMTP" name="loginSMTP" class="form-control" placeholder="Login" maxlength="100" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="senhaSMTP"> Senha: <span class="text-danger">*</span> </label>
								<input type="text" id="senhaSMTP" name="senhaSMTP" class="form-control" placeholder="Senha" maxlength="64" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="emailRetorno"> Email Retorno: <span class="text-danger">*</span> </label>
								<input type="text" id="emailRetorno" name="emailRetorno" class="form-control" placeholder="Email Retorno" maxlength="100" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="protocoloSMTP"> Protocolo: <span class="text-danger">*</span> </label>
								<select id="protocoloSMTP" name="protocoloSMTP" class="custom-select" required>
									<option value=""></option>
									<option value="1">SSL</option>
									<option value="2">TLS</option>
									<option value="3">STARTTLS</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="statusSMTP"> Status SMTP: <span class="text-danger">*</span> </label>
								<select id="statusSMTP" name="statusSMTP" class="custom-select" required>
									<option value=""></option>
									<option value="1">Ativado</option>
									<option value="2">Desativado</option>
								</select>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-xs btn-primary" id="edit-formSMTP-btn">Salvar</button>
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
		$('#data_table').DataTable({
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url($controller . '/getAll') ?>',
				"type": "get",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});
	});

	function addSMTP() {
		// reset the form 
		$("#add-formSMTP")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#add-modalSMTP').modal('show');
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

				var form = $('#add-formSMTP');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url($controller . '/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#add-formSMTP-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
								$('#data_table').DataTable().ajax.reload(null, false).draw(false);
								$('#add-modalSMTP').modal('hide');
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
						$('#add-formSMTP-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#add-formSMTP').validate();
	}

	function editSMTP(codServidorSMTP) {
		$.ajax({
			url: '<?php echo base_url($controller . '/getOne') ?>',
			type: 'post',
			data: {
				codServidorSMTP: codServidorSMTP,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),

			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#edit-formSMTP")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#edit-modalSMTP').modal('show');

				$("#edit-formSMTP #codServidorSMTP").val(response.codServidorSMTP);
				$("#edit-formSMTP #descricaoServidorSMTP").val(response.descricaoServidorSMTP);
				$("#edit-formSMTP #ipServidorSMTP").val(response.ipServidorSMTP);
				$("#edit-formSMTP #portaSMTP").val(response.portaSMTP);
				$("#edit-formSMTP #loginSMTP").val(response.loginSMTP);
				$("#edit-formSMTP #senhaSMTP").val(response.senhaSMTP);
				$("#edit-formSMTP #emailRetorno").val(response.emailRetorno);
				$("#edit-formSMTP #protocoloSMTP").val(response.protocoloSMTP);
				$("#edit-formSMTP #statusSMTP").val(response.statusSMTP);

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
						var form = $('#edit-formSMTP');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url($controller . '/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#edit-formSMTP-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
										$('#data_table').DataTable().ajax.reload(null, false).draw(false);
										$('#edit-modalSMTP').modal('hide');
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
								$('#edit-formSMTP-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#edit-formSMTP').validate();

			}
		});
	}

	function removeSMTP(codServidorSMTP) {
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
					url: '<?php echo base_url($controller . '/remove') ?>',
					type: 'post',
					data: {
						codServidorSMTP: codServidorSMTP,
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
								$('#data_table').DataTable().ajax.reload(null, false).draw(false);
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