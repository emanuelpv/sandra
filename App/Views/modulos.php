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
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Modulos</h3>
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
								<th>Nome</th>
								<th>Link</th>
								<th>Pai</th>
								<th>Ordem</th>
								<th>Destaque</th>
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
						<input type="hidden" id="<?php echo csrf_token() ?>add-modal" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codModulo" name="codModulo" class="form-control" placeholder="Id" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="nome"> Nome: <span class="text-danger">*</span> </label>
								<input type="text" id="nome" name="nome" class="form-control" placeholder="Nome" maxlength="50" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="link"> Link: <span class="text-danger">*</span> </label>
								<input type="text" id="link" name="link" class="form-control" placeholder="Link" maxlength="70" required>
							</div>
						</div>
						<div class="col-md-4">

							<div class="form-group">

								<label for="pai"> Pai: </label>
								<?php
								echo listboxModulospai($this);
								?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="ordem"> Ordem: <span class="text-danger">*</span> </label>
								<input type="number" id="ordem" name="ordem" class="form-control" placeholder="Ordem" maxlength="11" number="true" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="destaque"> Destaque: <span class="text-danger">*</span> </label>
								<input type="number" id="destaque" name="destaque" class="form-control" placeholder="Destaque" maxlength="11" number="true" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="icone"> Icone: <span class="text-danger">*</span> </label>
								<input type="text" id="icone" name="icone" class="form-control" placeholder="Icone" maxlength="40" required>
							</div>
						</div>
					</div>
					<div class="row">
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


				<div class="col-12 col-sm-12">
					<div class="card card-primary card-tabs">
						<div class="card-header p-0 pt-1">
							<ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" id="modulo-tab" data-toggle="pill" href="#modulo" role="tab" aria-controls="modulo" aria-selected="true">Módulo</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="notificacao-tab" data-toggle="pill" href="#notificacao" role="tab" aria-controls="notificacao" aria-selected="false">Notificações</a>
								</li>
							</ul>
						</div>
						<div class="card-body">
							<div class="tab-content" id="custom-tabs-one-tabContent">
								<div class="tab-pane fade show active" id="modulo" role="tabpanel" aria-labelledby="modulo-tab">

									<form id="edit-form" class="pl-3 pr-3">
										<div class="row">
											<input type="hidden" id="<?php echo csrf_token() ?>edit-form" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

											<input type="hidden" id="codModulo" name="codModulo" class="form-control" placeholder="Id" maxlength="11" required>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="nome"> Nome: <span class="text-danger">*</span> </label>
													<input type="text" id="nome" name="nome" class="form-control" placeholder="Nome" maxlength="50" required>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="link"> Link: <span class="text-	danger">*</span> </label>
													<input type="text" id="link" name="link" class="form-control" placeholder="Link" maxlength="70" required>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="pai"> Pai: </label>
													<?php
													echo listboxModulospai($this);
													?>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="ordem"> Ordem: <span class="text-danger">*</span> </label>
													<input type="number" id="ordem" name="ordem" class="form-control" placeholder="Ordem" maxlength="11" number="true" required>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="destaque"> Destaque: <span class="text-danger">*</span> </label>
													<input type="number" id="destaque" name="destaque" class="form-control" placeholder="Destaque" maxlength="11" number="true" required>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="icone"> Icone: <span class="text-danger">*</span> </label>
													<input type="text" id="icone" name="icone" class="form-control" placeholder="Icone" maxlength="40" required>
												</div>
											</div>
										</div>
										<div class="row">
										</div>

										<div class="form-group text-center">
											<div class="btn-group">
												<button type="submit" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="edit-form-btn">Salvar</button>
												<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
											</div>
										</div>
									</form>
								</div>
								<div style="overflow-x: auto;" class="tab-pane fade" id="notificacao" role="tabpanel" aria-labelledby="notificacao-tab">

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
													<th>Protocolo</th>
													<th>Destino</th>

													<th></th>
												</tr>
											</thead>
										</table>
									</div>
									<!-- /.card-body -->

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

															<input type="hidden" id="codModuloAdd" name="codModulo" class="form-control" placeholder="Código" maxlength="11" required>
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
									<!-- /.content -->
								</div>
							</div>
						</div>
					</div>
					<!-- /.card -->
				</div>
			</div>


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

	function add() {
		// reset the form 
		$("#add-form")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#add-modal').modal('show');
		$("#add-form #pai").select2();
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
									var codModulo = $("#" + index);

									codModulo.closest('.form-control')
										.removeClass('is-invalid')
										.removeClass('is-valid')
										.addClass(value.length > 0 ? 'is-invalid' : 'is-valid');

									codModulo.after(value);

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

	function edit(codModulo) {
		codModuloTmp = codModulo;
		$.ajax({
			url: '<?php echo base_url($controller . '/getOne') ?>',
			type: 'post',
			data: {
				codModulo: codModulo,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),

			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#edit-form")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#edit-modal').modal('show');


				$('.modal-title').text(response.nome);


				$("#edit-form #codModulo").val(response.codModulo);
				$("#edit-form #nome").val(response.nome);
				$("#edit-form #link").val(response.link);
				$("#edit-form #pai").val(response.pai).select2();
				$("#edit-form #ordem").val(response.ordem);
				$("#edit-form #destaque").val(response.destaque);
				$("#edit-form #icone").val(response.icone);




				//NOTIFICAÇÕES DE MÓDULOS


				$('#data_tablemodulosNotificacao').DataTable({
					"bDestroy": true,
					"paging": true,
					"lengthChange": false,
					"searching": true,
					"ordering": true,
					"info": true,
					"autoWidth": false,
					"responsive": true,
					"ajax": {
						"url": '<?php echo base_url($controller . '/notificacoesModulo') ?>',
						"type": "POST",
						"dataType": "json",
						async: "true",
						data: {
							codModulo: 1,
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},

					}
				});



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
											var codModulo = $("#" + index);

											codModulo.closest('.form-control')
												.removeClass('is-invalid')
												.removeClass('is-valid')
												.addClass(value.length > 0 ? 'is-invalid' : 'is-valid');

											codModulo.after(value);

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

	function remove(codModulo) {
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
						codModulo: codModulo,
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


<script>
	function addmodulosNotificacao() {
		// reset the form 
		$("#add-formmodulosNotificacao")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#destinoNotificacaoAdd').val(-100).trigger('change');
$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

		$('#add-modalmodulosNotificacao').modal('show');

		$("#add-formmodulosNotificacao #codModuloAdd").val(codModuloTmp);
		$("#add-formmodulosNotificacao #codModeloNotificacaoAdd").select2();



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