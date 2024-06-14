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
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Fornecedores</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addfornecedores()" title="Adicionar">Adicionar</button>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<table id="data_tablefornecedores" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>CodFornecedor</th>
								<th>CPF/CNPJ</th>
								<th>CodTipo</th>
								<th>CodNatureza</th>
								<th>NomeFantasia</th>
								<th>RazaoSocial</th>
								<th>Endereço</th>
								<th>Cidade</th>
								<th>UF</th>
								<th>CEP</th>
								<th>Contatos</th>
								<th>Email</th>
								<th>Website</th>
								<th>Simples</th>
								<th>Mnt</th>
								<th>Observações</th>

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
<div id="fornecedoresAddModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Fornecedores</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="fornecedoresAddForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>fornecedoresAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codFornecedor" name="codFornecedor" class="form-control" placeholder="CodFornecedor" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="inscricao"> CPF/CNPJ: <span class="text-danger">*</span> </label>
								<input type="text" id="inscricao" name="inscricao" class="form-control" placeholder="CPF/CNPJ" maxlength="20" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codTipo"> Tipo: <span class="text-danger">*</span> </label>
								<select id="codTipo" name="codTipo" class="custom-select" required>
									<option></option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="nomeFantasia"> NomeFantasia: </label>
								<input type="text" id="nomeFantasia" name="nomeFantasia" class="form-control" placeholder="NomeFantasia" required maxlength="255">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="razaoSocial"> RazaoSocial: </label>
								<input type="text" id="razaoSocial" name="razaoSocial" class="form-control" placeholder="RazaoSocial" required maxlength="255">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="endereco"> Endereço: </label>
								<textarea cols="40" rows="5" id="endereco" name="endereco" class="form-control" placeholder="Endereço"></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="cidade"> Cidade: </label>
								<input type="text" id="cidade" name="cidade" class="form-control" placeholder="Cidade" maxlength="30">
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="codEstadoFederacao"> UF: <span class="text-danger">*</span> </label>
								<select id="codEstadoFederacao" name="codEstadoFederacao" class="custom-select" required>
									<option></option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="cep"> CEP: </label>
								<input type="text" id="cep" name="cep" class="form-control" placeholder="CEP" maxlength="9">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="contatos"> Contatos: </label>
								<textarea cols="40" rows="5" id="contatos" name="contatos" class="form-control" placeholder="Contatos"></textarea>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="email"> Email: </label>
								<input type="text" id="email" name="email" class="form-control" placeholder="Email" maxlength="255">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="website"> Website: </label>
								<input type="text" id="website" name="website" class="form-control" placeholder="Website" maxlength="255">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="simples"> Simples: </label>
								<input type="text" id="simples" name="simples" class="form-control" placeholder="Simples" maxlength="3">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="observacoes"> Observações: </label>
								<textarea cols="40" rows="5" id="observacoes" name="observacoes" class="form-control" placeholder="Observações"></textarea>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="fornecedoresAddForm-btn">Adicionar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Add modal content -->
<div id="fornecedoresEditModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Fornecedores</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="fornecedoresEditForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>fornecedoresEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codFornecedor" name="codFornecedor" class="form-control" placeholder="CodFornecedor" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="inscricao"> CPF/CNPJ: <span class="text-danger">*</span> </label>
								<input type="text" id="inscricao" name="inscricao" class="form-control" placeholder="CPF/CNPJ" maxlength="20" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codTipo"> Tipo: <span class="text-danger">*</span> </label>
								<select id="codTipo" name="codTipo" class="custom-select" required>
									<option></option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="nomeFantasia"> NomeFantasia: </label>
								<input type="text" id="nomeFantasia" name="nomeFantasia" class="form-control" placeholder="NomeFantasia" required maxlength="255">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="razaoSocial"> RazaoSocial: </label>
								<input type="text" id="razaoSocial" name="razaoSocial" class="form-control" placeholder="RazaoSocial" required maxlength="255">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="endereco"> Endereço: </label>
								<textarea cols="40" rows="5" id="endereco" name="endereco" class="form-control" placeholder="Endereço"></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="cidade"> Cidade: </label>
								<input type="text" id="cidade" name="cidade" class="form-control" placeholder="Cidade" maxlength="30">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codEstadoFederacao"> UF: <span class="text-danger">*</span> </label>
								<select id="codEstadoFederacao" name="codEstadoFederacao" class="custom-select" required>
									<option></option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="cep"> CEP: </label>
								<input type="text" id="cep" name="cep" class="form-control" placeholder="CEP" maxlength="9">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="contatos"> Contatos: </label>
								<textarea cols="40" rows="5" id="contatos" name="contatos" class="form-control" placeholder="Contatos"></textarea>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="email"> Email: </label>
								<input type="text" id="email" name="email" class="form-control" placeholder="Email" maxlength="255">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="website"> Website: </label>
								<input type="text" id="website" name="website" class="form-control" placeholder="Website" maxlength="255">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="simples"> Simples: </label>
								<input type="text" id="simples" name="simples" class="form-control" placeholder="Simples" maxlength="3">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="observacoes"> Observações: </label>
								<textarea cols="40" rows="5" id="observacoes" name="observacoes" class="form-control" placeholder="Observações"></textarea>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="fornecedoresEditForm-btn">Salvar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
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
	$(document).on('show.bs.modal', '.modal', function() {
		var zIndex = 1040 + (10 * $('.modal:visible').length);
		$(this).css('z-index', zIndex);
		setTimeout(function() {
			$('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
		}, 0);
	});

	$(function() {
		$('#data_tablefornecedores').DataTable({
			"paging": true,
			"deferRender": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('fornecedores/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});
	});

	function editfornecedores(codFornecedor) {
		$.ajax({
			url: '<?php echo base_url('fornecedores/getOne') ?>',
			type: 'post',
			data: {
				codFornecedor: codFornecedor,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#fornecedoresEditForm")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#fornecedoresEditModal').modal('show');

				$("#fornecedoresEditForm #codFornecedor").val(response.codFornecedor);
				$("#fornecedoresEditForm #inscricao").val(response.inscricao);
				$("#fornecedoresEditForm #nomeFantasia").val(response.nomeFantasia);
				$("#fornecedoresEditForm #razaoSocial").val(response.razaoSocial);
				$("#fornecedoresEditForm #endereco").val(response.endereco);
				$("#fornecedoresEditForm #cidade").val(response.cidade);
				$("#fornecedoresEditForm #cep").val(response.cep);
				$("#fornecedoresEditForm #contatos").val(response.contatos);
				$("#fornecedoresEditForm #email").val(response.email);
				$("#fornecedoresEditForm #website").val(response.website);
				$("#fornecedoresEditForm #simples").val(response.simples);
				$("#fornecedoresEditForm #mnt").val(response.mnt);
				$("#fornecedoresEditForm #observacoes").val(response.observacoes);



				$.ajax({
					url: '<?php echo base_url('fornecedores/listaDropDownTipoFornecedor') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(tipoFornecedor) {

						$("#fornecedoresEditForm #codTipo").select2({
							data: tipoFornecedor,
						})

						$("#fornecedoresEditForm #codTipo").val(response.codTipo); // Select the option with a value of '1'
						$("#fornecedoresEditForm #codTipo").trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

					}
				})



				$.ajax({
					url: '<?php echo base_url('fornecedores/listaDropDownEstadosFederacao') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(estadosFederacao) {

						$("#fornecedoresEditForm #codEstadoFederacao").select2({
							data: estadosFederacao,
						})

						$("#fornecedoresEditForm #codEstadoFederacao").val(response.codEstadoFederacao); // Select the option with a value of '1'
						$("#fornecedoresEditForm #codEstadoFederacao").trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

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
						var form = $('#fornecedoresEditForm');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('fornecedores/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#fornecedoresEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
							},
							success: function(response) {

								if (response.success === true) {

									$('#fornecedoresEditModal').modal('hide');


									var Toast = Swal.mixin({
										toast: true,
										position: 'bottom-end',
										showConfirmButton: false,
										timer: 2000
									});
									Toast.fire({
										icon: 'success',
										title: response.messages
									}).then(function() {
										$('#data_tablefornecedores').DataTable().ajax.reload(null, false).draw(false);
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
										var Toast = Swal.mixin({
											toast: true,
											position: 'bottom-end',
											showConfirmButton: false,
											timer: 2000
										});
										Toast.fire({
											icon: 'error',
											title: response.messages
										})

									}
								}
								$('#fornecedoresEditForm-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#fornecedoresEditForm').validate();

			}
		});
	}


	function addfornecedores() {
		// reset the form 
		$("#fornecedoresAddForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#fornecedoresAddModal').modal('show');


		$("#orcamentosAddForm #codFornecedor").select2('close');



		$.ajax({
			url: '<?php echo base_url('fornecedores/listaDropDownTipoFornecedor') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(tipoFornecedor) {

				$("#fornecedoresAddForm #codTipo").select2({
					data: tipoFornecedor,
				})

				$("#fornecedoresAddForm #codTipo").val(null); // Select the option with a value of '1'
				$("#fornecedoresAddForm #codTipo").trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})




		$.ajax({
			url: '<?php echo base_url('fornecedores/listaDropDownEstadosFederacao') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(estadosFederacao) {

				$("#fornecedoresAddForm #codEstadoFederacao").select2({
					data: estadosFederacao,
				})

				$("#fornecedoresAddForm #codEstadoFederacao").val(null); // Select the option with a value of '1'
				$("#fornecedoresAddForm #codEstadoFederacao").trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})




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

				var form = $('#fornecedoresAddForm');
				// remove the text-danger
				$(".text-danger").remove();


				$.ajax({
					url: '<?php echo base_url('fornecedores/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#fornecedoresAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function(response) {

						if (response.success === true) {
							$('#fornecedoresAddModal').modal('hide');


							$('#codFornecedor').html('').select2({
								data: [{
									id: '',
									text: ''
								}]
							});


							$.ajax({
								url: '<?php echo base_url('fornecedores/listaDropDownFornecedores') ?>',
								type: 'post',
								dataType: 'json',
								data: {
									csrf_sandra: $("#csrf_sandraPrincipal").val(),
								},
								success: function(Fornecedores) {

									$("#orcamentosAddForm #codFornecedor").select2({
										data: Fornecedores,
										allowClear: true,
										placeholder: 'Procurar Pessoa',
										minimumInputLength: 4,
										quietMillis: 1000,
										dropdownParent: $('#fornecedoresAddModal .modal-content'),
										language: {
											noResults: function() {
												return `<button style="width: 100%" type="button"
                                        class="btn btn-xs btn-primary" 
                                        onClick='addfornecedores()'>+ Adicionar Fornecedor</button>
                                        `;
											}
										},

										escapeMarkup: function(markup) {
											return markup;
										}
									})


									$('#orcamentosAddForm #codFornecedor').val(response.codFornecedor);
									$('#orcamentosAddForm #codFornecedor').trigger('change');
									$(document).on('select2:open', () => {
										document.querySelector('.select2-search__field').focus();
									});
								}
							})




							var Toast = Swal.mixin({
								toast: true,
								position: 'bottom-end',
								showConfirmButton: false,
								timer: 2000
							});
							Toast.fire({
								icon: 'success',
								title: response.messages
							}).then(function() {
								$('#data_tablefornecedores').DataTable().ajax.reload(null, false).draw(false);
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

								var Toast = Swal.mixin({
									toast: true,
									position: 'bottom-end',
									showConfirmButton: false,
									timer: 2000
								});
								Toast.fire({
									icon: 'error',
									title: response.messages
								})

							}
						}
						$('#fornecedoresAddForm-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#fornecedoresAddForm').validate();
	}


	function removefornecedores(codFornecedor) {
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
					url: '<?php echo base_url('fornecedores/remove') ?>',
					type: 'post',
					data: {
						codFornecedor: codFornecedor,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					dataType: 'json',
					success: function(response) {

						if (response.success === true) {
							var Toast = Swal.mixin({
								toast: true,
								position: 'bottom-end',
								showConfirmButton: false,
								timer: 2000
							});
							Toast.fire({
								icon: 'success',
								title: response.messages
							}).then(function() {
								$('#data_tablefornecedores').DataTable().ajax.reload(null, false).draw(false);
							})
						} else {
							var Toast = Swal.mixin({
								toast: true,
								position: 'bottom-end',
								showConfirmButton: false,
								timer: 2000
							});
							Toast.fire({
								icon: 'error',
								title: response.messages
							})


						}
					}
				});
			}
		})
	}
</script>