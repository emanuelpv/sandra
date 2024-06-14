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
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Funções Atribuidas</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary  btn-xs" onclick="addFuncoesAtribuidasModel()" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<table id="data_tablefuncoesAtribuidas" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>Código</th>
								<th>Pessoa</th>
								<th>Função</th>
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
<div id="add-modalFuncoesAtribuidasModel" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Funções Atribuidas</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="add-formFuncoesAtribuidasModel" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>add-modalFuncoesAtribuidasModel" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codPessoaFuncao" name="codPessoaFuncao" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codPessoa"> Pessoa: <span class="text-danger">*</span> </label>
								<select id="codPessoa" name="codPessoa" class="custom-select" required>
									<option value="0"></option>
									<option value="1">select1</option>
									<option value="2">select2</option>
									<option value="3">select3</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codFuncao"> Função: <span class="text-danger">*</span> </label>
								<select id="codFuncao" name="codFuncao" class="custom-select" required>
									<option value="0"></option>
									<option value="1">select1</option>
									<option value="2">select2</option>
									<option value="3">select3</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataInicio"> Data Início: <span class="text-danger">*</span> </label>
								<input type="date" id="dataInicio" name="dataInicio" class="form-control" dateISO="true" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataEncerramento"> Data Encerramento:</span> </label>
								<input type="date" id="dataEncerramento" name="dataEncerramento" class="form-control" dateISO="true">
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-xs btn-primary" id="add-formFuncoesAtribuidasModel-btn">Adicionar</button>
							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Add modal content -->
<div id="edit-modalFuncoesAtribuidasModel" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Funções Atribuidas</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="edit-form" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>edit-modalFuncoesAtribuidasModel" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codPessoaFuncao" name="codPessoaFuncao" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codPessoa"> Pessoa: <span class="text-danger">*</span> </label>
								<select id="codPessoa" name="codPessoa" class="custom-select" required>
									<option value='NULL'></option>
									<option value="1">select1</option>
									<option value="2">select2</option>
									<option value="3">select3</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codFuncao"> Função: <span class="text-danger">*</span> </label>
								<select id="codFuncao" name="codFuncao" class="custom-select" required>
									<option value="NULL"></option>
									<option value="1">select1</option>
									<option value="2">select2</option>
									<option value="3">select3</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataInicio"> Data Início: <span class="text-danger">*</span> </label>
								<input type="date" id="dataInicio" name="dataInicio" class="form-control" dateISO="true" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataEncerramento"> Data Encerramento: </span> </label>
								<input type="date" id="dataEncerramento" name="dataEncerramento" class="form-control" dateISO="true">
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

<?php
echo view('tema/rodape');
?>
<script>
	$(function() {
		$('#data_tablefuncoesAtribuidas').DataTable({
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('funcoesAtribuidas/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});
	});

	function addFuncoesAtribuidasModel() {
		// reset the form 
		$("#add-formFuncoesAtribuidasModel")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#add-modalFuncoesAtribuidasModel').modal('show');
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

				var form = $('#add-formFuncoesAtribuidasModel');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('funcoesAtribuidas/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#add-formFuncoesAtribuidasModel-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
								$('#data_tablefuncoesAtribuidas').DataTable().ajax.reload(null, false).draw(false);
								$('#add-modalFuncoesAtribuidasModel').modal('hide');
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
						$('#add-formFuncoesAtribuidasModel-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#add-formFuncoesAtribuidasModel').validate();
	}

	function editfuncoesAtribuidas(codPessoaFuncao) {
		$.ajax({
			url: '<?php echo base_url('funcoesAtribuidas/getOne') ?>',
			type: 'post',
			data: {
				codPessoaFuncao: codPessoaFuncao,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),

			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#edit-form")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#edit-modalFuncoesAtribuidasModel').modal('show');

				$("#edit-form #codPessoaFuncao").val(response.codPessoaFuncao);
				$("#edit-form #codPessoa").val(response.codPessoa);
				$("#edit-form #codFuncao").val(response.codFuncao);
				$("#edit-form #dataInicio").val(response.dataInicio);
				$("#edit-form #dataEncerramento").val(response.dataEncerramento);

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
							url: '<?php echo base_url('funcoesAtribuidas/edit') ?>',
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
										$('#data_tablefuncoesAtribuidas').DataTable().ajax.reload(null, false).draw(false);
										$('#edit-modalFuncoesAtribuidasModel').modal('hide');
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

	function removefuncoesAtribuidas(codPessoaFuncao) {
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
					url: '<?php echo base_url('funcoesAtribuidas/remove') ?>',
					type: 'post',
					data: {
						codPessoaFuncao: codPessoaFuncao,
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
								$('#data_tablefuncoesAtribuidas').DataTable().ajax.reload(null, false).draw(false);
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