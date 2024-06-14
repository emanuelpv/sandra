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

<style>
	.modal {
		overflow: auto !important;
	}

	#minhaFoto {
		width: 160px;
		height: 125px;
		border: 1px solid black;
	}

	#fotoPerfilCadastro {
		width: 160px;
		height: 125px;
		border: 1px solid black;
	}

	.select2-container {
		z-index: 100000;
	}


	.swal2-container {
		z-index: 9999999;
	}
</style>

<section class="content">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-md-8 mt-2">
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Kits</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addkits()" title="Adicionar">Adicionar</button>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<table id="data_tablekits" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>CodKit</th>
								<th>DescricaoKit</th>
								<th>Descricao Alternativa</th>
								<th>Valor</th>
								<th>Tipo</th>
								<th>Disponível</th>
								<th>Status</th>

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


<div id="kitsItensAddModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Itens de KIts</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="kitsItensAddForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>kitsItensAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codKitAddItem" name="codKit" class="form-control" placeholder="codKit" maxlength="11" required>
					</div>
					<div class="row">


						<div class="col-md-4">
							<div class="form-group">
								<label for="codItemAdd"> Tipo: <span class="text-danger">*</span> </label>
								<select id="codItemAdd" name="codItem" class="custom-select" required>
									<option value=""></option>
								</select>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="qtde"> Qtde: <span class="text-danger">*</span> </label>
								<input autocomplete="off" type="number" id="qtde" name="qtde" class="form-control" placeholder="Qtde" maxlength="11" number="true" required>
							</div>
						</div>

					</div>
					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="kitsItensAddForm-btn">Adicionar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>


<div id="kitsItensEditModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Itens de KIts</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="kitsItensEditForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>kitsItensEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codKitItemEdit" name="codKitItem" class="form-control" placeholder="codKit" maxlength="11" required>
					</div>
					<div class="row">


						<div class="col-md-4">
							<div class="form-group">
								<label for="codItemEdit"> Tipo: <span class="text-danger">*</span> </label>
								<select disabled id="codItemEdit" name="codItem" class="custom-select" required>
									<option value=""></option>
								</select>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="qtdeEdit"> Qtde: <span class="text-danger">*</span> </label>
								<input autocomplete="off" type="number" id="qtdeEdit" name="qtde" class="form-control" placeholder="Qtde" maxlength="11" number="true" required>
							</div>
						</div>

					</div>
					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="kitsItensEditForm-btn">Adicionar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>


<div id="kitsAddModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Kits</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="kitsAddForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>kitsAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codKitAdd" name="codKit" class="form-control" placeholder="CodKit" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="descricaoKit"> DescricaoKit: <span class="text-danger">*</span> </label>
								<input type="text" id="descricaoKit" name="descricaoKit" class="form-control" placeholder="DescricaoKit" maxlength="100" required>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="descricaoAlternativa"> DescricaoAlternativa: </label>
								<textarea cols="40" rows="5" id="descricaoAlternativa" name="descricaoAlternativa" class="form-control" placeholder="DescricaoAlternativa"></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codTipo"> Tipo: <span class="text-danger">*</span> </label>
								<select id="codTipoAdd" name="codTipo" class="custom-select" required>
									<option value=""></option>
								</select>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="kitsAddForm-btn">Adicionar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Add modal content -->
<div id="kitsEditModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Kits</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="kitsEditForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>kitsEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codKitEdit" name="codKit" class="form-control" placeholder="CodKit" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="descricaoKit"> DescricaoKit: <span class="text-danger">*</span> </label>
								<input type="text" id="descricaoKitEdit" name="descricaoKit" class="form-control" placeholder="DescricaoKit" maxlength="100" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="descricaoAlternativaEdit"> DescricaoAlternativa: </label>
								<textarea cols="40" rows="5" id="descricaoAlternativaEdit" name="descricaoAlternativa" class="form-control" placeholder="DescricaoAlternativa"></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codTipoEdit"> Tipo: <span class="text-danger">*</span> </label>
								<select id="codTipoEdit" name="codTipo" class="custom-select" required>
									<option value=""></option>
								</select>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="valorunEdit"> Valorun: </label>
								<input disabled type="text" id="valorun" name="valorun" class="form-control" placeholder="Valorun">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="disponivel"> Disponivel: </label>
								<input disabled type="number" id="disponivelEdit" name="disponivel" class="form-control" placeholder="Disponivel" maxlength="11" number="true">
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="kitsEditForm-btn">Salvar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>



				<div class="row">

					<div class="col-md-12">
						<div class="card card-secondary">

							<div class="card-header">
								<h3 class="card-title">ITENS DO KIT</h3>

								<div class="card-tools">
									<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
									</button>
								</div>
							</div>
							<div class="card-body">

								<div class="col-md-4">
									<button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addkitsItens()" title="Adicionar">Adicionar</button>
								</div>


								<div class="card-body">
									<table id="data_tablekitsItens" class="table table-striped table-hover table-sm">
										<thead>
											<tr>
												<th>CodKitItem</th>
												<th>CodKit</th>
												<th>Qtde</th>
												<th>Valor</th>
												<th>Data Inclusão</th>
												<th>Data Atualizacao</th>
												<th>Autor</th>

												<th></th>
											</tr>
										</thead>
									</table>
								</div>
							</div>
						</div>
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
	$(document).on('show.bs.modal', '.modal', function() {
		var zIndex = 1040 + (10 * $('.modal:visible').length);
		$(this).css('z-index', zIndex);
		setTimeout(function() {
			$('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
		}, 0);
	});

	$(function() {

		
		avisoPesquisa('Farmácia',2);

		$('#data_tablekits').DataTable({
			"paging": true,
			"deferRender": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('kits/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});
	});

	function addkits() {
		// reset the form 
		$("#kitsAddForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#kitsAddModal').modal('show');



		$.ajax({
			url: '<?php echo base_url('kits/listaDropDownTipos') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(tipoLocalAtendimentoEdit) {

				$("#codTipoAdd").select2({
					data: tipoLocalAtendimentoEdit,
				})

				$('#codTipoAdd').val(null); // Select the option with a value of '1'
				$('#codTipoAdd').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});



			}
		});




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

				var form = $('#kitsAddForm');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('kits/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#kitsAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function(response) {

						if (response.success === true) {
							$('#kitsAddModal').modal('hide');

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
								$('#data_tablekits').DataTable().ajax.reload(null, false).draw(false);
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
						$('#kitsAddForm-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#kitsAddForm').validate();
	}

	function editkits(codKit) {

		document.getElementById('codKitAddItem').value = codKit;


		$.ajax({
			url: '<?php echo base_url('kits/getOne') ?>',
			type: 'post',
			data: {
				codKit: codKit,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#kitsEditForm")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#kitsEditModal').modal('show');

				$("#kitsEditForm #codKitEdit").val(response.codKit);
				$("#kitsEditForm #descricaoKitEdit").val(response.descricaoKit);
				$("#kitsEditForm #disponivelEdit").val(response.disponivel);
				$("#kitsEditForm #descricaoAlternativaEdit").val(response.descricaoAlternativa);
				$("#kitsEditForm #valorunEdit").val(response.valorun);




				$('#data_tablekitsItens').DataTable({
					"bDestroy": true,
					"paging": true,
					"deferRender": true,
					"lengthChange": false,
					"searching": true,
					"ordering": true,
					"info": true,
					"autoWidth": false,
					"responsive": true,
					"ajax": {
						"url": '<?php echo base_url('kitsItens/itensKit') ?>',
						"type": "POST",
						"dataType": "json",
						async: "true",
						data: {
							codKit: response.codKit,
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},
					}
				});


				$.ajax({
					url: '<?php echo base_url('kits/listaDropDownTipos') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(tipoLocalAtendimentoEdit) {

						$("#codTipoEdit").select2({
							data: tipoLocalAtendimentoEdit,
						})

						$('#codTipoEdit').val(response.codTipo); // Select the option with a value of '1'
						$('#codTipoEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});



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
						var form = $('#kitsEditForm');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('kits/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#kitsEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
							},
							success: function(response) {

								if (response.success === true) {

									$('#kitsEditModal').modal('hide');


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
										$('#data_tablekits').DataTable().ajax.reload(null, false).draw(false);
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
								$('#kitsEditForm-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#kitsEditForm').validate();

			}
		});
	}



	function desativarKit(codKit) {
		Swal.fire({
			title: 'Não é possível remover este Kit.',
			text: "Deseja desativar?",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Quero desativar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {

			if (result.value) {
				$.ajax({
					url: '<?php echo base_url('kits/desativarKit') ?>',
					type: 'post',
					data: {
						codKit: codKit,
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
								$('#data_tablekits').DataTable().ajax.reload(null, false).draw(false);
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



	function addkitsItens() {
		// reset the form 
		$("#kitsItensAddForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#kitsItensAddModal').modal('show');


		$.ajax({
			url: '<?php echo base_url('kits/listaDropDownItensFarmacia') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(itens) {

				$("#codItemAdd").select2({
					data: itens,
				})

				$('#codItemAdd').val(null); // Select the option with a value of '1'
				$('#codItemAdd').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});



			}
		});



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

				var form = $('#kitsItensAddForm');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('kitsItens/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#kitsItensAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function(response) {

						if (response.success === true) {
							$('#kitsItensAddModal').modal('hide');
							$('#data_tablekits').DataTable().ajax.reload(null, false).draw(false);

							$('#data_tablekitsItens').DataTable().ajax.reload(null, false).draw(false);

							var Toast = Swal.mixin({
								toast: true,
								position: 'bottom-end',
								showConfirmButton: false,
								timer: 2000
							});
							Toast.fire({
								icon: 'success',
								title: response.messages
							}).then(function() {})

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
						$('#kitsItensAddForm-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#kitsItensAddForm').validate();
	}



	function editkitsItens(codKitItem) {
		$.ajax({
			url: '<?php echo base_url('kitsItens/getOne') ?>',
			type: 'post',
			data: {
				codKitItem: codKitItem,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#kitsItensEditForm")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#kitsItensEditModal').modal('show');

				$("#kitsItensEditForm #qtdeEdit").val(response.qtde);
				$("#kitsItensEditForm #codKitItemEdit").val(response.codKitItem);


				$.ajax({
					url: '<?php echo base_url('kits/listaDropDownItensFarmacia') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(itensEdit) {

						$("#codItemEdit").select2({
							data: itensEdit,
						})

						$('#codItemEdit').val(response.codItem); // Select the option with a value of '1'
						$('#codItemEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});



					}
				});


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
						var form = $('#kitsItensEditForm');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('kitsItens/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#kitsItensEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
							},
							success: function(response) {

								if (response.success === true) {

									$('#kitsItensEditModal').modal('hide');


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
										$('#data_tablekits').DataTable().ajax.reload(null, false).draw(false);

										$('#data_tablekitsItens').DataTable().ajax.reload(null, false).draw(false);
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
								$('#kitsItensEditForm-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#kitsItensEditForm').validate();

			}
		});
	}

	function removekitsItens(codKitItem) {
		Swal.fire({
			title: 'Você tem certeza que deseja remover este item do kit?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {

			if (result.value) {
				$.ajax({
					url: '<?php echo base_url('kitsItens/remove') ?>',
					type: 'post',
					data: {
						codKitItem: codKitItem,
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
								$('#data_tablekits').DataTable().ajax.reload(null, false).draw(false);

								$('#data_tablekitsItens').DataTable().ajax.reload(null, false).draw(false);
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