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
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">itensFarmaciaLote</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="additensFarmaciaLote()" title="Adicionar">Adicionar</button>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<table id="data_tableitensFarmaciaLote" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>CodLote</th>
								<th>Nº do Lote</th>
								<th>Código de Barras</th>
								<th>Quantidade</th>
								<th>Data Validade</th>
								<th>Criação</th>
								<th>Atualização</th>
								<th>DataInventario</th>
								<th>Observacao</th>

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
<div id="itensFarmaciaLoteAddModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar itensFarmaciaLote</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="itensFarmaciaLoteAddForm" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="codLote" name="codLote" class="form-control" placeholder="CodLote" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="nrLote"> Nº do Lote: <span class="text-danger">*</span> </label>
								<input type="text" id="nrLote" name="nrLote" class="form-control" placeholder="Nº do Lote" maxlength="50" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codBarra"> Código de Barras: </label>
								<input type="text" id="codBarra" name="codBarra" class="form-control" placeholder="Código de Barras" maxlength="64">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="quantidade"> Quantidade: <span class="text-danger">*</span> </label>
								<input type="text" id="quantidade" name="quantidade" class="form-control" placeholder="Quantidade" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataValidade"> Data Validade: </label>
								<input type="date" id="dataValidade" name="dataValidade" class="form-control" dateISO="true">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataCriacao"> Criação: <span class="text-danger">*</span> </label>
								<input type="text" id="dataCriacao" name="dataCriacao" class="form-control" placeholder="Criação" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataAtualizacao"> Atualização: <span class="text-danger">*</span> </label>
								<input type="text" id="dataAtualizacao" name="dataAtualizacao" class="form-control" placeholder="Atualização" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataInventario"> DataInventario: </label>
								<input type="text" id="dataInventario" name="dataInventario" class="form-control" placeholder="DataInventario">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="observacao"> Observacao: </label>
								<textarea cols="40" rows="5" id="observacao" name="observacao" class="form-control" placeholder="Observacao"></textarea>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="itensFarmaciaLoteAddForm-btn">Adicionar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Add modal content -->
<div id="itensFarmaciaLoteEditModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar itensFarmaciaLote</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="itensFarmaciaLoteEditForm" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="codLote" name="codLote" class="form-control" placeholder="CodLote" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="nrLote"> Nº do Lote: <span class="text-danger">*</span> </label>
								<input type="text" id="nrLote" name="nrLote" class="form-control" placeholder="Nº do Lote" maxlength="50" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codBarra"> Código de Barras: </label>
								<input type="text" id="codBarra" name="codBarra" class="form-control" placeholder="Código de Barras" maxlength="64">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="quantidade"> Quantidade: <span class="text-danger">*</span> </label>
								<input type="text" id="quantidade" name="quantidade" class="form-control" placeholder="Quantidade" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataValidade"> Data Validade: </label>
								<input type="date" id="dataValidade" name="dataValidade" class="form-control" dateISO="true">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataCriacao"> Criação: <span class="text-danger">*</span> </label>
								<input type="text" id="dataCriacao" name="dataCriacao" class="form-control" placeholder="Criação" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataAtualizacao"> Atualização: <span class="text-danger">*</span> </label>
								<input type="text" id="dataAtualizacao" name="dataAtualizacao" class="form-control" placeholder="Atualização" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataInventario"> DataInventario: </label>
								<input type="text" id="dataInventario" name="dataInventario" class="form-control" placeholder="DataInventario">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="observacao"> Observacao: </label>
								<textarea cols="40" rows="5" id="observacao" name="observacao" class="form-control" placeholder="Observacao"></textarea>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="itensFarmaciaLoteEditForm-btn">Salvar</button>
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
		$('#data_tableitensFarmaciaLote').DataTable({
			"paging": true,
			"deferRender": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('itensFarmaciaLote/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true"
			}
		});
	});

	function additensFarmaciaLote() {
		// reset the form 
		$("#itensFarmaciaLoteAddForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#itensFarmaciaLoteAddModal').modal('show');
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

				var form = $('#itensFarmaciaLoteAddForm');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('itensFarmaciaLote/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#itensFarmaciaLoteAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function(response) {

						if (response.success === true) {
							$('#itensFarmaciaLoteAddModal').modal('hide');

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
								$('#data_tableitensFarmaciaLote').DataTable().ajax.reload(null, false).draw(false);
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
						$('#itensFarmaciaLoteAddForm-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#itensFarmaciaLoteAddForm').validate();
	}

	function edititensFarmaciaLote(codLote) {
		$.ajax({
			url: '<?php echo base_url('itensFarmaciaLote/getOne') ?>',
			type: 'post',
			data: {
				codLote: codLote
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#itensFarmaciaLoteEditForm")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#itensFarmaciaLoteEditModal').modal('show');

				$("#itensFarmaciaLoteEditForm #codLote").val(response.codLote);
				$("#itensFarmaciaLoteEditForm #nrLote").val(response.nrLote);
				$("#itensFarmaciaLoteEditForm #codBarra").val(response.codBarra);
				$("#itensFarmaciaLoteEditForm #quantidade").val(response.quantidade);
				$("#itensFarmaciaLoteEditForm #dataValidade").val(response.dataValidade);
				$("#itensFarmaciaLoteEditForm #dataCriacao").val(response.dataCriacao);
				$("#itensFarmaciaLoteEditForm #dataAtualizacao").val(response.dataAtualizacao);
				$("#itensFarmaciaLoteEditForm #dataInventario").val(response.dataInventario);
				$("#itensFarmaciaLoteEditForm #observacao").val(response.observacao);

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
						var form = $('#itensFarmaciaLoteEditForm');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('itensFarmaciaLote/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							success: function(response) {

								if (response.success === true) {

									$('#itensFarmaciaLoteEditModal').modal('hide');

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
										$('#data_tableitensFarmaciaLote').DataTable().ajax.reload(null, false).draw(false);
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
								$('#itensFarmaciaLoteEditForm-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#itensFarmaciaLoteEditForm').validate();

			}
		});
	}

	function removeitensFarmaciaLote(codLote) {
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
					url: '<?php echo base_url('itensFarmaciaLote/remove') ?>',
					type: 'post',
					data: {
						codLote: codLote
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
								$('#data_tableitensFarmaciaLote').DataTable().ajax.reload(null, false).draw(false);
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