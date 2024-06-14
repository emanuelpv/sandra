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
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Tipos Projetos</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary" onclick="add()" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<table id="data_table" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>Código</th>
								<th>Descrição</th>
								<th>Ordem</th>
								<th>Dependência</th>
								<th>Prazo</th>
								<th>Ativar Notificação</th>
								<th>Nr dias Notificação</th>
								<th>Link</th>
								<th>Icone</th>

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
<div id="add-modal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar <?php echo $title ?></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="add-form" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>add-form" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codTipoProjeto" name="codTipoProjeto" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="descricaoTipoProjeto"> Descrição: <span class="text-danger">*</span> </label>
								<input type="text" id="descricaoTipoProjeto" name="descricaoTipoProjeto" class="form-control" placeholder="Descrição" maxlength="100" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="ordem"> Ordem: </label>
								<input type="number" id="ordem" name="ordem" class="form-control" placeholder="Ordem" maxlength="11" number="true">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codLocalAtendimento"> Dependência: </label>
								<select id="codLocalAtendimento" name="codLocalAtendimento" class="custom-select">
									<option value="0"></option>
									<option value="1">select1</option>
									<option value="2">select2</option>
									<option value="3">select3</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="prazo"> Prazo: </label>
								<input type="number" id="prazo" name="prazo" class="form-control" placeholder="Prazo" maxlength="11" number="true">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="ativarNotificacao"> Ativar Notificação: </label>
								<select id="ativarNotificacao" name="ativarNotificacao" class="custom-select">
									<option value="0"></option>
									<option value="1">select1</option>
									<option value="2">select2</option>
									<option value="3">select3</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="nrdiasNotificacao"> Nr dias Notificação: </label>
								<input type="number" id="nrdiasNotificacao" name="nrdiasNotificacao" class="form-control" placeholder="Nr dias Notificação" maxlength="11" number="true">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="link"> Link: </label>
								<input type="text" id="link" name="link" class="form-control" placeholder="Link" maxlength="150">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="icone"> Icone: </label>
								<input type="text" id="icone" name="icone" class="form-control" placeholder="Icone" maxlength="40">
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-xs btn-primary" id="add-form-btn">Adicionar</button>
							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Add modal content -->
<div id="edit-modal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar <?php echo $title ?></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="edit-form" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>edit-form" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codTipoProjeto" name="codTipoProjeto" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="descricaoTipoProjeto"> Descrição: <span class="text-danger">*</span> </label>
								<input type="text" id="descricaoTipoProjeto" name="descricaoTipoProjeto" class="form-control" placeholder="Descrição" maxlength="100" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="ordem"> Ordem: </label>
								<input type="number" id="ordem" name="ordem" class="form-control" placeholder="Ordem" maxlength="11" number="true">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codLocalAtendimento"> Dependência: </label>
								<select id="codLocalAtendimento" name="codLocalAtendimento" class="custom-select">
									<option value="0"></option>
									<option value="1">select1</option>
									<option value="2">select2</option>
									<option value="3">select3</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="prazo"> Prazo: </label>
								<input type="number" id="prazo" name="prazo" class="form-control" placeholder="Prazo" maxlength="11" number="true">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="ativarNotificacao"> Ativar Notificação: </label>
								<select id="ativarNotificacao" name="ativarNotificacao" class="custom-select">
									<option value="0"></option>
									<option value="1">select1</option>
									<option value="2">select2</option>
									<option value="3">select3</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="nrdiasNotificacao"> Nr dias Notificação: </label>
								<input type="number" id="nrdiasNotificacao" name="nrdiasNotificacao" class="form-control" placeholder="Nr dias Notificação" maxlength="11" number="true">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="link"> Link: </label>
								<input type="text" id="link" name="link" class="form-control" placeholder="Link" maxlength="150">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="icone"> Icone: </label>
								<input type="text" id="icone" name="icone" class="form-control" placeholder="Icone" maxlength="40">
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="edit-form-btn">Salvar</button>
							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>

			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /.content -->
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

	function add() {
		// reset the form 
		$("#add-form")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#add-modal').modal('show');
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

				var form = $('#add-form');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url($controller . '/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#add-form-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
								$('#add-modal').modal('hide');
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
						$('#add-form-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#add-form').validate();
	}

	function edit(codTipoProjeto) {
		$.ajax({
			url: '<?php echo base_url($controller . '/getOne') ?>',
			type: 'post',
			data: {
				codTipoProjeto: codTipoProjeto,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#edit-form")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#edit-modal').modal('show');

				$("#edit-form #codTipoProjeto").val(response.codTipoProjeto);
				$("#edit-form #descricaoTipoProjeto").val(response.descricaoTipoProjeto);
				$("#edit-form #ordem").val(response.ordem);
				$("#edit-form #codLocalAtendimento").val(response.codLocalAtendimento);
				$("#edit-form #prazo").val(response.prazo);
				$("#edit-form #ativarNotificacao").val(response.ativarNotificacao);
				$("#edit-form #nrdiasNotificacao").val(response.nrdiasNotificacao);
				$("#edit-form #link").val(response.link);
				$("#edit-form #icone").val(response.icone);

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
						var form = $('#edit-form');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url($controller . '/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#edit-form-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
										$('#edit-modal').modal('hide');
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
								$('#edit-form-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#edit-form').validate();

			}
		});
	}

	function remove(codTipoProjeto) {
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
						codTipoProjeto: codTipoProjeto,
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