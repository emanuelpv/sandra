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
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Perfis Módulos</h3>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">

					<form id="perfisModulos-form"  method="post">
					<button type="button" class="btn btn-xs btn-primary" id="perfisModulos-form-btn" onclick="salvarPerfilModulos()">SALVAR</button>
					<input type="input" id="codPerfil" name="codPerfil" class="form-control" placeholder="CodPerfil" maxlength="11">

						<table id="data_tableperfisModulos" class="table table-striped table-hover table-sm">
							<thead>
								<tr>
									<th>CodPerfil</th>
									<th>CodModulo</th>
									<th style="text-align:center">
										Listar
										<div style="margin-left:5px" class="icheck-primary d-inline">
											<style>
												input[type=checkbox] {
													transform: scale(1.8);
												}
											</style>
											<input class="listar" id="listar-all" type="checkbox">
										</div>

									</th>
									<th style="text-align:center">
										Adicionar
										<div style="margin-left:5px" class="icheck-primary d-inline">
											<style>
												input[type=checkbox] {
													transform: scale(1.8);
												}
											</style>
											<input class="adicionar" id="adicionar-all" type="checkbox">
										</div>

									</th>
									<th style="text-align:center">
										Editar
										<div style="margin-left:5px" class="icheck-primary d-inline">
											<style>
												input[type=checkbox] {
													transform: scale(1.8);
												}
											</style>
											<input class="editar" id="editar-all" type="checkbox" >
										</div>
									</th>
									<th style="text-align:center">
										Deletar
										<div style="margin-left:5px" class="icheck-primary d-inline">
											<style>
												input[type=checkbox] {
													transform: scale(1.8);
												}
											</style>
											<input class="deletar" id="deletar-all" type="checkbox">
										</div>

									</th>
								</tr>
							</thead>
						</table>
					</form>
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
<div id="add-modalperfisModulos" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Perfis Módulos</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="add-formperfisModulos" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="codPerfil" name="codPerfil" class="form-control" placeholder="CodPerfil" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codModulo"> CodModulo: <span class="text-danger">*</span> </label>
								<input type="number" id="codModulo" name="codModulo" class="form-control" placeholder="CodModulo" maxlength="11" number="true" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="listar"> Listar: <span class="text-danger">*</span> </label>
								<input type="number" id="listar" name="listar" class="form-control" placeholder="Listar" maxlength="11" number="true" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="adicionar"> Adicionar: <span class="text-danger">*</span> </label>
								<input type="number" id="adicionar" name="adicionar" class="form-control" placeholder="Adicionar" maxlength="11" number="true" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="editar"> Editar: <span class="text-danger">*</span> </label>
								<input type="number" id="editar" name="editar" class="form-control" placeholder="Editar" maxlength="11" number="true" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="deletar"> Deletar: <span class="text-danger">*</span> </label>
								<input type="number" id="deletar" name="deletar" class="form-control" placeholder="Deletar" maxlength="11" number="true" required>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-xs btn-primary" id="add-formperfisModulos-btn">Adicionar</button>
							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Add modal content -->
<div id="edit-modalperfisModulos" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Perfis Módulos</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="edit-formperfisModulos" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="codPerfil" name="codPerfil" class="form-control" placeholder="CodPerfil" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codModulo"> CodModulo: <span class="text-danger">*</span> </label>
								<input type="number" id="codModulo" name="codModulo" class="form-control" placeholder="CodModulo" maxlength="11" number="true" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="listar"> Listar: <span class="text-danger">*</span> </label>
								<input type="number" id="listar" name="listar" class="form-control" placeholder="Listar" maxlength="11" number="true" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="adicionar"> Adicionar: <span class="text-danger">*</span> </label>
								<input type="number" id="adicionar" name="adicionar" class="form-control" placeholder="Adicionar" maxlength="11" number="true" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="editar"> Editar: <span class="text-danger">*</span> </label>
								<input type="number" id="editar" name="editar" class="form-control" placeholder="Editar" maxlength="11" number="true" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="deletar"> Deletar: <span class="text-danger">*</span> </label>
								<input type="number" id="deletar" name="deletar" class="form-control" placeholder="Deletar" maxlength="11" number="true" required>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-xs btn-primary" id="edit-formperfisModulos-btn">Salvar</button>
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






	$('#listar-all').click(function(event) {
		if (this.checked) {
			// Iterate each checkbox
			$('.listar').each(function() {
				this.checked = true;
			});
		} else {
			$('.listar').each(function() {
				this.checked = false;
			});
		}
	});

	$('#adicionar-all').click(function(event) {
		if (this.checked) {
			// Iterate each checkbox
			$('.adicionar').each(function() {
				this.checked = true;
			});
		} else {
			$('.adicionar').each(function() {
				this.checked = false;
			});
		}
	});

	$('#editar-all').click(function(event) {
		if (this.checked) {
			// Iterate each checkbox
			$('.editar').each(function() {
				this.checked = true;
			});
		} else {
			$('.editar').each(function() {
				this.checked = false;
			});
		}
	});

	$('#deletar-all').click(function(event) {
		if (this.checked) {
			// Iterate each checkbox
			$('.deletar').each(function() {
				this.checked = true;
			});
		} else {
			$('.deletar').each(function() {
				this.checked = false;
			});
		}
	});


	function salvarPerfilModulos() {
	alert(1);
	}

	$(function() {


		$('#data_tableperfisModulos').DataTable({
			"paging": false,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('perfisModulos/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true"
			}
		});
	});

	function addperfisModulos() {
		// reset the form 
		$("#add-formperfisModulos")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#add-modalperfisModulos').modal('show');
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

				var form = $('#add-formperfisModulos');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('perfisModulos/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#add-formperfisModulos-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
								$('#data_tableperfisModulos').DataTable().ajax.reload(null, false).draw(false);
								$('#add-modalperfisModulos').modal('hide');
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
						$('#add-formperfisModulos-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#add-formperfisModulos').validate();
	}

	function editperfisModulos(codPerfil) {
		$.ajax({
			url: '<?php echo base_url('perfisModulos/getOne') ?>',
			type: 'post',
			data: {
				codPerfil: codPerfil
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#edit-formperfisModulos")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#edit-modalperfisModulos').modal('show');

				$("#edit-formperfisModulos #codPerfil").val(response.codPerfil);
				$("#edit-formperfisModulos #codModulo").val(response.codModulo);
				$("#edit-formperfisModulos #listar").val(response.listar);
				$("#edit-formperfisModulos #adicionar").val(response.adicionar);
				$("#edit-formperfisModulos #editar").val(response.editar);
				$("#edit-formperfisModulos #deletar").val(response.deletar);

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
						var form = $('#edit-formperfisModulos');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('perfisModulos/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#edit-formperfisModulos-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
										$('#data_tableperfisModulos').DataTable().ajax.reload(null, false).draw(false);
										$('#edit-modalperfisModulos').modal('hide');
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
								$('#edit-formperfisModulos-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#edit-formperfisModulos').validate();

			}
		});
	}

	function removeperfisModulos(codPerfil) {
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
					url: '<?php echo base_url('perfisModulos/remove') ?>',
					type: 'post',
					data: {
						codPerfil: codPerfil
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
								$('#data_tableperfisModulos').DataTable().ajax.reload(null, false).draw(false);
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