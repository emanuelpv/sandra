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
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Equipes de Suporte</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary" onclick="addequipesSuporte()" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<table id="data_tableequipesSuporte" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>Código</th>
								<th>Sigla</th>
								<th>Descrição Equipe</th>
								<th>Departamento</th>

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
<div id="add-modalequipesSuporte" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Equipes de Suporte</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="add-formequipesSuporte" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>add-modalequipesSuporte" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
						<input type="hidden" id="codEquipeSuporte" name="codEquipeSuporte" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="siglaEquipeSuporte"> Sigla: <span class="text-danger">*</span> </label>
								<input type="text" id="siglaEquipeSuporte" name="siglaEquipeSuporte" class="form-control" placeholder="Sigla" maxlength="50" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="descricaoEquipeSuporte"> Descrição Equipe: <span class="text-danger">*</span> </label>
								<input type="text" id="descricaoEquipeSuporte" name="descricaoEquipeSuporte" class="form-control" placeholder="Descrição Equipe" maxlength="200" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codDepartamentoResponsavel"> Departamento: <span class="text-danger">*</span> </label>
								<select id="codDepartamentoResponsavelAdd" name="codDepartamentoResponsavel" class="custom-select" required>
									<option value=""></option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-xs btn-primary" id="add-formequipesSuporte-btn">Adicionar</button>
							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Add modal content -->
<div id="edit-modalequipesSuporte" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Equipes de Suporte</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="edit-formequipesSuporte" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>edit-modalequipesSuporte" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
						<input type="hidden" id="codEquipeSuporte" name="codEquipeSuporte" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="siglaEquipeSuporte"> Sigla: <span class="text-danger">*</span> </label>
								<input type="text" id="siglaEquipeSuporte" name="siglaEquipeSuporte" class="form-control" placeholder="Sigla" maxlength="50" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="descricaoEquipeSuporte"> Descrição Equipe: <span class="text-danger">*</span> </label>
								<input type="text" id="descricaoEquipeSuporte" name="descricaoEquipeSuporte" class="form-control" placeholder="Descrição Equipe" maxlength="200" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codDepartamentoResponsavel"> Departamento: <span class="text-danger">*</span> </label>
								<select id="codDepartamentoResponsavelEdit" name="codDepartamentoResponsavel" class="custom-select" required>
									<option value=""></option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-xs btn-primary" id="edit-formequipesSuporte-btn">Salvar</button>
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
		$('#data_tableequipesSuporte').DataTable({
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('equipesSuporte/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});
	});

	function addequipesSuporte() {
		// reset the form 
		$("#add-formequipesSuporte")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#add-modalequipesSuporte').modal('show');
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

				var form = $('#add-formequipesSuporte');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('equipesSuporte/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#add-formequipesSuporte-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
								$('#data_tableequipesSuporte').DataTable().ajax.reload(null, false).draw(false);
								$('#add-modalequipesSuporte').modal('hide');
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
						$('#add-formequipesSuporte-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#add-formequipesSuporte').validate();



		$.ajax({
			url: '<?php echo base_url('departamentos/listaDropDown') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(departamentoAdd) {
				$("#codDepartamentoResponsavelAdd").select2({
					data: departamentoAdd,
				})

			}
		})



	}

	function editequipesSuporte(codEquipeSuporte) {
		$.ajax({
			url: '<?php echo base_url('equipesSuporte/getOne') ?>',
			type: 'post',
			data: {
				codEquipeSuporte: codEquipeSuporte,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#edit-formequipesSuporte")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#edit-modalequipesSuporte').modal('show');

				$("#edit-formequipesSuporte #codEquipeSuporte").val(response.codEquipeSuporte);
				$("#edit-formequipesSuporte #siglaEquipeSuporte").val(response.siglaEquipeSuporte);
				$("#edit-formequipesSuporte #descricaoEquipeSuporte").val(response.descricaoEquipeSuporte);
				$("#edit-formequipesSuporte #codDepartamentoResponsavel").val(response.codDepartamentoResponsavel);

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
						var form = $('#edit-formequipesSuporte');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('equipesSuporte/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#edit-formequipesSuporte-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
										$('#data_tableequipesSuporte').DataTable().ajax.reload(null, false).draw(false);
										$('#edit-modalequipesSuporte').modal('hide');
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
								$('#edit-formequipesSuporte-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#edit-formequipesSuporte').validate();


				$.ajax({
					url: '<?php echo base_url('departamentos/listaDropDown') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(departamentosList) {
						$("#codDepartamentoResponsavelEdit").select2({
							data: departamentosList,
						})
						$('#codDepartamentoResponsavelEdit').val(response.codDepartamentoResponsavel); // Select the option with a value of '1'
						$('#codDepartamentoResponsavelEdit').trigger('change');
$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

					}
				})




			}
		});
	}

	function removeequipesSuporte(codEquipeSuporte) {
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
					url: '<?php echo base_url('equipesSuporte/remove') ?>',
					type: 'post',
					data: {
						codEquipeSuporte: codEquipeSuporte,
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
								$('#data_tableequipesSuporte').DataTable().ajax.reload(null, false).draw(false);
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