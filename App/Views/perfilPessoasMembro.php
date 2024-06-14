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
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Membros do Perfil</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary" onclick="addperfilPessoasMembro()" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<table id="data_tableperfilPessoasMembro" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>Membro</th>
								<th>Data Início</th>
								<th>Data Encerramento</th>

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
<div id="add-modalperfilPessoasMembro" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Membros do Perfil</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="add-formperfilPessoasMembro" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="codPessoaMembro" name="codPessoaMembro" class="form-control" placeholder="Código" maxlength="11" required>
						<input type="number" id="codPerfil" name="codPerfil" class="form-control" placeholder="CodPerfil" maxlength="11" number="true" required>

					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codPessoa"> Membro: <span class="text-danger">*</span> </label>
								<select id="codPessoaAdd" name="codPessoa" class="custom-select" required>
								</select>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="dataInicio"> DataInicio: <span class="text-danger">*</span> </label>
								<input type="date" id="dataInicio" name="dataInicio" class="form-control" dateISO="true" value="<?php echo date('d-m-Y')?>" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataEncerramento"> DataEncerramento: </label>
								<input type="date" id="dataEncerramento" name="dataEncerramento" class="form-control" dateISO="true">
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-xs btn-primary" id="add-formperfilPessoasMembro-btn">Adicionar</button>
							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Add modal content -->
<div id="edit-modalperfilPessoasMembro" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Membros do Perfil</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="edit-formperfilPessoasMembro" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="codPessoaMembro" name="codPessoaMembro" class="form-control" placeholder="Código" maxlength="11" required>
						<input type="number" id="codPerfil" name="codPerfil" class="form-control" placeholder="CodPerfil" maxlength="11" number="true" required>

					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codPessoa"> Membro: <span class="text-danger">*</span> </label>
								<?php
								echo listboxMembrosPerfil($this);
								?>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataInicio"> DataInicio: <span class="text-danger">*</span> </label>
								<input type="date" id="dataInicio" name="dataInicio" class="form-control" dateISO="true" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataEncerramento"> DataEncerramento: </label>
								<input type="date" id="dataEncerramento" name="dataEncerramento" class="form-control" dateISO="true">
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-xs btn-primary" id="edit-formperfilPessoasMembro-btn">Salvar</button>
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
		$('#data_tableperfilPessoasMembro').DataTable({
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('perfilPessoasMembro/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true"
			}
		});
	});

	function addperfilPessoasMembro() {
		// reset the form 
		$("#add-formperfilPessoasMembro")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#add-modalperfilPessoasMembro').modal('show');
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

				var form = $('#add-formperfilPessoasMembro');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('perfilPessoasMembro/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#add-formperfilPessoasMembro-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
								$('#data_tableperfilPessoasMembro').DataTable().ajax.reload(null, false).draw(false);
								$('#add-modalperfilPessoasMembro').modal('hide');
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
						$('#add-formperfilPessoasMembro-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#add-formperfilPessoasMembro').validate();
	}

	function editperfilPessoasMembro(codPessoaMembro) {
		$.ajax({
			url: '<?php echo base_url('perfilPessoasMembro/getOne') ?>',
			type: 'post',
			data: {
				codPessoaMembro: codPessoaMembro
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#edit-formperfilPessoasMembro")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#edit-modalperfilPessoasMembro').modal('show');
				$("#edit-formperfilPessoasMembro #codPessoaMembro").val(response.codPessoaMembro);
				$("#edit-formperfilPessoasMembro #codPessoa").val(response.codPessoa).select2();
				$("#edit-formperfilPessoasMembro #codPerfil").val(response.codPerfil);
				$("#edit-formperfilPessoasMembro #dataInicio").val(response.dataInicio);
				$("#edit-formperfilPessoasMembro #dataEncerramento").val(response.dataEncerramento);

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
						var form = $('#edit-formperfilPessoasMembro');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('perfilPessoasMembro/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#edit-formperfilPessoasMembro-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
										$('#data_tableperfilPessoasMembro').DataTable().ajax.reload(null, false).draw(false);
										$('#edit-modalperfilPessoasMembro').modal('hide');
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
								$('#edit-formperfilPessoasMembro-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#edit-formperfilPessoasMembro').validate();

			}
		});
	}

	function removeperfilPessoasMembro(codPessoaMembro) {
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
					url: '<?php echo base_url('perfilPessoasMembro/remove') ?>',
					type: 'post',
					data: {
						codPessoaMembro: codPessoaMembro
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
								$('#data_tableperfilPessoasMembro').DataTable().ajax.reload(null, false).draw(false);
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