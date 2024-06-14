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
<link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/summernote/summernote-bs4.min.css">
<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-md-8 mt-2">
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Modelos de Notificação</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary" onclick="addmodelosNotificacao()" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<table id="data_tablemodelosNotificacao" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>Código</th>
								<th>Modelo</th>
								<th>Protocolo</th>
								<th>Assunto</th>
								<th>Responder Para</th>

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
<div id="add-modalmodelosNotificacao" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Modelos de Notificação</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="add-formmodelosNotificacao" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="codModeloNotificacao" name="codModeloNotificacao" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="nomeModeloNotificacao"> Modelo: <span class="text-danger">*</span> </label>
								<input type="text" id="nomeModeloNotificacao" name="nomeModeloNotificacao" class="form-control" placeholder="Modelo" maxlength="60" required>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="nomeModeloNotificacao"> Protocolo: <span class="text-danger">*</span> </label>

								<?php
								echo listboxProtocoloNotificacoes($this);
								?>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="responderPara"> Responder Para: <span class="text-danger"></span> </label>
								<input type="text" id="responderPara" name="responderPara" class="form-control" placeholder="Opcional" maxlength="60">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="assunto"> Assunto: <span class="text-danger">*</span> </label>
								<input type="text" id="assunto" name="assunto" class="form-control" placeholder="Assunto" maxlength="100" required>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="conteudoModeloNotificacao"> Conteudo: <span class="text-danger">*</span> </label>
								<textarea id="conteudoModeloNotificacaoAdd" name="conteudoModeloNotificacao" class="form-control" placeholder="Conteudo" required></textarea>
							</div>
						</div>

					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-xs btn-primary" id="add-formmodelosNotificacao-btn">Adicionar</button>
							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Add modal content -->
<div id="edit-modalmodelosNotificacao" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Modelos de Notificação</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="edit-formmodelosNotificacao" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="codModeloNotificacao-edit" name="codModeloNotificacao" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="nomeModeloNotificacao"> Modelo: <span class="text-danger">*</span> </label>
								<input type="text" id="nomeModeloNotificacao-edit" name="nomeModeloNotificacao" class="form-control" placeholder="Modelo" maxlength="60" required>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="nomeModeloNotificacao"> Protocolo: <span class="text-danger">*</span> </label>

								<?php
								echo listboxProtocoloNotificacoes($this, 'edit');
								?>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="responderPara"> Responder Para: <span class="text-danger"></span> </label>
								<input type="text" id="responderPara-edit" name="responderPara" class="form-control" placeholder="Opcional" maxlength="60">
							</div>
						</div>
					</div>
					<div class="row">

						<div class="col-md-4">
							<div class="form-group">
								<label for="assunto"> Assunto: <span class="text-danger">*</span> </label>
								<input type="text" id="assunto-edit" name="assunto" class="form-control" placeholder="Assunto" maxlength="100" required>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="conteudoModeloNotificacao-edit"> Conteudo: <span class="text-danger">*</span> </label>
								<textarea id="conteudoModeloNotificacao-edit" name="conteudoModeloNotificacao" class="form-control" placeholder="Conteudo" required></textarea>
							</div>
						</div>

					</div>


					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-xs btn-primary" id="edit-formmodelosNotificacao-btn">Salvar</button>
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
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/summernote/summernote-bs4.min.js"></script>

<script>
	$(function() {
		//Add text editor
		$('#conteudoModeloNotificacaoAdd').summernote({
			height: 150,
			maximumImageFileSize: 1024 * 1024, // 1Mb
			fontSizes: ['8', '9', '10', '11', '12', '14', '18', '20', '36', '72'],
			lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
			toolbar: [
				['style', ['style']],
				['fontname', ['fontname']],
				['font', ['color', 'strikethrough', 'superscript', 'subscript', 'bold', 'underline', 'clear']],
				['fontsize', ['fontsize']],
				['height', ['height']],
				['para', ['ul', 'ol', 'paragraph']],
				['table', ['table']],
				['insert', ['link', 'picture', 'video', 'hr']],
				['view', ['fullscreen', 'codeview', 'help']],
				['redo'],
				['undo'],
			],
			callbacks: {
				onImageUploadError: function(msg) {
					var Toast = Swal.mixin({
						toast: true,
						position: 'top-end',
						showConfirmButton: false,
						timer: 5000
					});
					Toast.fire({
						icon: 'error',
						title: 'Tamanho máximo de imagens é 1Mb'
					})
				}
			}
		})
	})


	$(function() {
		$('#data_tablemodelosNotificacao').DataTable({
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('modelosNotificacao/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true"
			}
		});
	});

	function addmodelosNotificacao() {
		// reset the form 
		$("#add-formmodelosNotificacao")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#add-modalmodelosNotificacao').modal('show');
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

				var form = $('#add-formmodelosNotificacao');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('modelosNotificacao/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#add-formmodelosNotificacao-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
								$('#data_tablemodelosNotificacao').DataTable().ajax.reload(null, false).draw(false);
								$('#add-modalmodelosNotificacao').modal('hide');
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
						$('#add-formmodelosNotificacao-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#add-formmodelosNotificacao').validate();
	}

	function editmodelosNotificacao(codModeloNotificacao) {
		$.ajax({
			url: '<?php echo base_url('modelosNotificacao/getOne') ?>',
			type: 'post',
			data: {
				codModeloNotificacao: codModeloNotificacao
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#conteudoModeloNotificacao-edit").summernote('destroy');
				$("#edit-formmodelosNotificacao")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#edit-modalmodelosNotificacao').modal('show');

				$("#edit-formmodelosNotificacao #codModeloNotificacao-edit").val(response.codModeloNotificacao);
				$("#edit-formmodelosNotificacao #nomeModeloNotificacao-edit").val(response.nomeModeloNotificacao);
				$("#edit-formmodelosNotificacao #assunto-edit").val(response.assunto);
				$("#edit-formmodelosNotificacao #responderPara-edit").val(response.responderPara);
				$("#edit-formmodelosNotificacao #codProtocoloNotificacao-edit").val(response.codProtocoloNotificacao);

				$("#edit-formmodelosNotificacao #conteudoModeloNotificacao-edit").val(response.conteudoModeloNotificacao);

				$('#conteudoModeloNotificacao-edit').summernote({
					height: 150,
					maximumImageFileSize: 1024 * 1024, // 1Mb
					fontSizes: ['8', '9', '10', '11', '12', '14', '18', '20', '36', '72'],
					lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
					toolbar: [
						['style', ['style']],
						['fontname', ['fontname']],
						['font', ['color', 'strikethrough', 'superscript', 'subscript', 'bold', 'underline', 'clear']],
						['fontsize', ['fontsize']],
						['height', ['height']],
						['para', ['ul', 'ol', 'paragraph']],
						['table', ['table']],
						['insert', ['link', 'picture', 'video', 'hr']],
						['view', ['fullscreen', 'codeview', 'help']],
						['redo'],
						['undo'],
					],
					callbacks: {
						onImageUploadError: function(msg) {
							var Toast = Swal.mixin({
								toast: true,
								position: 'top-end',
								showConfirmButton: false,
								timer: 5000
							});
							Toast.fire({
								icon: 'error',
								title: 'Tamanho máximo de imagens é 1Mb'
							})
						}
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
						var form = $('#edit-formmodelosNotificacao');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('modelosNotificacao/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#edit-formmodelosNotificacao-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
										$('#data_tablemodelosNotificacao').DataTable().ajax.reload(null, false).draw(false);
										$('#edit-modalmodelosNotificacao').modal('hide');
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
								$('#edit-formmodelosNotificacao-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#edit-formmodelosNotificacao').validate();

			}
		});
	}

	function removemodelosNotificacao(codModeloNotificacao) {
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
					url: '<?php echo base_url('modelosNotificacao/remove') ?>',
					type: 'post',
					data: {
						codModeloNotificacao: codModeloNotificacao
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
								$('#data_tablemodelosNotificacao').DataTable().ajax.reload(null, false).draw(false);
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