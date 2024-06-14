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
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Taxas e Serviços</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addtaxasServicos()" title="Adicionar">Adicionar</button>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<table id="data_tabletaxasServicos" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>Código</th>
								<th>Referência</th>
								<th>Descrição</th>
								<th>Usm</th>
								<th>Valor</th>

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
<div id="taxasServicosAddModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Taxas e Serviços</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="taxasServicosAddForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>taxasServicosAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codTaxaServico" name="codTaxaServico" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="referencia"> Referência: <span class="text-danger">*</span> </label>
								<input type="text" id="referencia" name="referencia" class="form-control" placeholder="Referência" maxlength="10" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="descricao"> Descrição: <span class="text-danger">*</span> </label>
								<input type="text" id="descricao" name="descricao" class="form-control" placeholder="Descrição" maxlength="235" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="usm"> Usm: <span class="text-danger">*</span> </label>
								<input type="number" id="usm" name="usm" class="form-control" placeholder="Usm" maxlength="5" number="true" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="valor"> Valor: </label>
								<input type="text" id="valor" name="valor" class="form-control" placeholder="Valor">
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="taxasServicosAddForm-btn">Adicionar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Add modal content -->
<div id="taxasServicosEditModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Taxas e Serviços</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="taxasServicosEditForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>taxasServicosEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codTaxaServico" name="codTaxaServico" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="referencia"> Referência: <span class="text-danger">*</span> </label>
								<input type="text" id="referencia" name="referencia" class="form-control" placeholder="Referência" maxlength="10" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="descricao"> Descrição: <span class="text-danger">*</span> </label>
								<input type="text" id="descricao" name="descricao" class="form-control" placeholder="Descrição" maxlength="235" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="usm"> Usm: <span class="text-danger">*</span> </label>
								<input type="number" id="usm" name="usm" class="form-control" placeholder="Usm" maxlength="5" number="true" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="valor"> Valor: </label>
								<input type="text" id="valor" name="valor" class="form-control" placeholder="Valor">
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="taxasServicosEditForm-btn">Salvar</button>
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
		$('#data_tabletaxasServicos').DataTable({
			"paging": true,
			"deferRender": true,
			"pageLength": 50,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('taxasServicos/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});
	});

	function addtaxasServicos() {
		// reset the form 
		$("#taxasServicosAddForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#taxasServicosAddModal').modal('show');
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

				var form = $('#taxasServicosAddForm');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('taxasServicos/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#taxasServicosAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function(response) {

						if (response.success === true) {
							$('#taxasServicosAddModal').modal('hide');

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
								$('#data_tabletaxasServicos').DataTable().ajax.reload(null, false).draw(false);
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
						$('#taxasServicosAddForm-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#taxasServicosAddForm').validate();
	}

	function edittaxasServicos(codTaxaServico) {
		$.ajax({
			url: '<?php echo base_url('taxasServicos/getOne') ?>',
			type: 'post',
			data: {
				codTaxaServico: codTaxaServico,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#taxasServicosEditForm")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#taxasServicosEditModal').modal('show');

				$("#taxasServicosEditForm #codTaxaServico").val(response.codTaxaServico);
				$("#taxasServicosEditForm #referencia").val(response.referencia);
				$("#taxasServicosEditForm #descricao").val(response.descricao);
				$("#taxasServicosEditForm #usm").val(response.usm);
				$("#taxasServicosEditForm #valor").val(response.valor);

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
						var form = $('#taxasServicosEditForm');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('taxasServicos/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#taxasServicosEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
							},
							success: function(response) {

								if (response.success === true) {

									$('#taxasServicosEditModal').modal('hide');


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
										$('#data_tabletaxasServicos').DataTable().ajax.reload(null, false).draw(false);
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
								$('#taxasServicosEditForm-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#taxasServicosEditForm').validate();

			}
		});
	}

	function removetaxasServicos(codTaxaServico) {


		Swal.fire({
			position: 'bottom-end',
			icon: 'warning',
			title: 'Funcionalidade desativada',
			html: 'Não é possível remover pacientes do sistema.',
			showConfirmButton: false,
			timer: 4000
		})



		/*
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
						url: '<?php echo base_url('taxasServicos/remove') ?>',
						type: 'post',
						data: {
							codTaxaServico: codTaxaServico,
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
									$('#data_tabletaxasServicos').DataTable().ajax.reload(null, false).draw(false);
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

			*/
	}
</script>