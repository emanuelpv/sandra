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
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Ação Suporte</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary" onclick="addacaoSuporte()" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<table id="data_tableacaoSuporte" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>CodAcaoSuporte</th>
								<th>CodSolicitacao</th>
								<th>CodPessoa</th>
								<th>DescricaoAcao</th>
								<th>DataInício</th>
								<th>CodStatusSolicitacao</th>
								<th>PercentualConclusao</th>

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
<div id="add-modalacaoSuporte" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Ação Suporte</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="add-formacaoSuporte" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>add-modalacaoSuporte" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
						<input type="hidden" id="codAcaoSuporte" name="codAcaoSuporte" class="form-control" placeholder="CodAcaoSuporte" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codSolicitacao"> CodSolicitacao: <span class="text-danger">*</span> </label>
								<input type="number" id="codSolicitacao" name="codSolicitacao" class="form-control" placeholder="CodSolicitacao" maxlength="11" number="true" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codPessoa"> CodPessoa: <span class="text-danger">*</span> </label>
								<input type="number" id="codPessoa" name="codPessoa" class="form-control" placeholder="CodPessoa" maxlength="11" number="true" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="descricaoAcao"> DescricaoAcao: <span class="text-danger">*</span> </label>
								<textarea cols="40" rows="5" id="descricaoAcao" name="descricaoAcao" class="form-control" placeholder="DescricaoAcao" required></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataInício"> DataInício: <span class="text-danger">*</span> </label>
								<input type="text" id="dataInício" name="dataInício" class="form-control" placeholder="DataInício" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codStatusSolicitacao"> CodStatusSolicitacao: <span class="text-danger">*</span> </label>
								<input type="number" id="codStatusSolicitacao" name="codStatusSolicitacao" class="form-control" placeholder="CodStatusSolicitacao" maxlength="11" number="true" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="percentualConclusao"> PercentualConclusao: <span class="text-danger">*</span> </label>
								<input type="number" id="percentualConclusao" name="percentualConclusao" class="form-control" placeholder="PercentualConclusao" maxlength="11" number="true" required>
							</div>
						</div>
					</div>
					<div class="row">
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-xs btn-primary" id="add-formacaoSuporte-btn">Adicionar</button>
							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Add modal content -->
<div id="edit-modalacaoSuporte" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Ação Suporte</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="edit-formacaoSuporte" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>edit-modalacaoSuporte" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
						<input type="hidden" id="codAcaoSuporte" name="codAcaoSuporte" class="form-control" placeholder="CodAcaoSuporte" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codSolicitacao"> CodSolicitacao: <span class="text-danger">*</span> </label>
								<input type="number" id="codSolicitacao" name="codSolicitacao" class="form-control" placeholder="CodSolicitacao" maxlength="11" number="true" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codPessoa"> CodPessoa: <span class="text-danger">*</span> </label>
								<input type="number" id="codPessoa" name="codPessoa" class="form-control" placeholder="CodPessoa" maxlength="11" number="true" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="descricaoAcao"> DescricaoAcao: <span class="text-danger">*</span> </label>
								<textarea cols="40" rows="5" id="descricaoAcao" name="descricaoAcao" class="form-control" placeholder="DescricaoAcao" required></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataInício"> DataInício: <span class="text-danger">*</span> </label>
								<input type="text" id="dataInício" name="dataInício" class="form-control" placeholder="DataInício" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codStatusSolicitacao"> CodStatusSolicitacao: <span class="text-danger">*</span> </label>
								<input type="number" id="codStatusSolicitacao" name="codStatusSolicitacao" class="form-control" placeholder="CodStatusSolicitacao" maxlength="11" number="true" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="percentualConclusao"> PercentualConclusao: <span class="text-danger">*</span> </label>
								<input type="number" id="percentualConclusao" name="percentualConclusao" class="form-control" placeholder="PercentualConclusao" maxlength="11" number="true" required>
							</div>
						</div>
					</div>
					<div class="row">
					</div>

                    <div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-xs btn-primary" id="edit-formacaoSuporte-btn">Salvar</button>
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
		$('#data_tableacaoSuporte').DataTable({
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('acaoSuporte/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: csrfHash,
				},
			}
		});
	});

	function addacaoSuporte() {
		// reset the form 
		$("#add-formacaoSuporte")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#add-modalacaoSuporte').modal('show');

		$("input[id*='csrf_sandra']").val(csrfHash);

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

				var form = $('#add-formacaoSuporte');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('acaoSuporte/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#add-formacaoSuporte-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
								$('#data_tableacaoSuporte').DataTable().ajax.reload(null, false).draw(false);
								$('#add-modalacaoSuporte').modal('hide');
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
						$('#add-formacaoSuporte-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#add-formacaoSuporte').validate();
	}

	function editacaoSuporte(codAcaoSuporte) {
		$.ajax({
			url: '<?php echo base_url('acaoSuporte/getOne') ?>',
			type: 'post',
			data: {
				codAcaoSuporte: codAcaoSuporte,
				csrf_sandra: csrfHash,
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#edit-formacaoSuporte")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#edit-modalacaoSuporte').modal('show');

				$("#edit-formacaoSuporte #codAcaoSuporte").val(response.codAcaoSuporte);
				$("#edit-formacaoSuporte #codSolicitacao").val(response.codSolicitacao);
				$("#edit-formacaoSuporte #codPessoa").val(response.codPessoa);
				$("#edit-formacaoSuporte #descricaoAcao").val(response.descricaoAcao);
				$("#edit-formacaoSuporte #dataInício").val(response.dataInício);
				$("#edit-formacaoSuporte #codStatusSolicitacao").val(response.codStatusSolicitacao);
				$("#edit-formacaoSuporte #percentualConclusao").val(response.percentualConclusao);

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
						var form = $('#edit-formacaoSuporte');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('acaoSuporte/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#edit-formacaoSuporte-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
										$('#data_tableacaoSuporte').DataTable().ajax.reload(null, false).draw(false);
										$('#edit-modalacaoSuporte').modal('hide');
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
								$('#edit-formacaoSuporte-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#edit-formacaoSuporte').validate();

			}
		});
	}

	function removeacaoSuporte(codAcaoSuporte) {
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
					url: '<?php echo base_url('acaoSuporte/remove') ?>',
					type: 'post',
					data: {
						codAcaoSuporte: codAcaoSuporte
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
								$('#data_tableacaoSuporte').DataTable().ajax.reload(null, false).draw(false);
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