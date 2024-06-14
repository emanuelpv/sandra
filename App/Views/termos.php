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
<link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/summernote/summernote-bs4.min.css">

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-md-8 mt-2">
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Termos de Aceite</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary" onclick="addtermos()" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<table id="data_tabletermos" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>Código</th>
								<th>Autor</th>
								<th>Assunto</th>
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
<!-- Add modal content -->
<div id="add-modaltermos" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Termos de Aceite</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="add-formtermos" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>add-formtermos" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
						<input type="hidden" id="codTermo" name="codTermo" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">

						<div class="col-md-12">
							<div class="form-group">
								<label for="assunto"> Assunto: <span class="text-danger">*</span> </label>
								<input type="text" id="assunto" name="assunto" class="form-control" placeholder="Assunto" maxlength="100" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="termo"> Termo: <span class="text-danger">*</span> </label>
								<textarea cols="40" rows="5" id="termoAdd" name="termo" class="form-control" placeholder="Termo" required></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="checkboxstatus"> Status: </label>
								<i type="button" class="fas fa-info-circle swalstatus"></i>

								<div class="icheck-primary d-inline">
									<style>
										input[type=checkbox] {
											transform: scale(1.8);
										}
									</style>
									<input style="margin-left:5px;" name='codStatus' type="checkbox" id="checkboxstatus">


								</div>
							</div>
						</div>
					</div>



					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-xs btn-primary" id="add-formtermos-btn">Adicionar</button>
							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Add modal content -->
<div id="edit-modaltermos" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Termos de Aceite</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="edit-formtermos" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>edit-formtermos" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codTermo" name="codTermo" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="assunto"> Assunto: <span class="text-danger">*</span> </label>
								<input type="text" id="assunto" name="assunto" class="form-control" placeholder="Assunto" maxlength="100" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="termo"> Termo: <span class="text-danger">*</span> </label>
								<textarea cols="40" rows="5" id="termoEdit" name="termo" class="form-control" placeholder="Termo" required></textarea>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="checkboxstatus">Status: </label>
								<i type="button" class="fas fa-info-circle swalstatus"></i>

								<div class="icheck-primary d-inline">
									<style>
										input[type=checkbox] {
											transform: scale(1.8);
										}
									</style>
									<input style="margin-left:5px;" name='codStatusEdit' type="checkbox" id="checkboxstatusEdit">


								</div>

							</div>


						</div>

					</div>
					<div class="row">
						<label>Última Alteração: </label>
						<spam class="col-md-4">

							<spam>
								<input disabled type="readonly" id="autor" class="form-control">
							</spam>
						</spam>
					</div>
					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-xs btn-primary" id="edit-formtermos-btn">Salvar</button>
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

<script src="<?php echo base_url() ?>/assets/adminlte/plugins/summernote/summernote-bs4.min.js"></script>


<script>
	$(function() {
		$('#data_tabletermos').DataTable({
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('termos/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});
	});

	function addtermos() {
		// reset the form 
		$("#add-formtermos")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#add-modaltermos').modal('show');
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

				var form = $('#add-formtermos');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('termos/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#add-formtermos-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
								$('#data_tabletermos').DataTable().ajax.reload(null, false).draw(false);
								$('#add-modaltermos').modal('hide');
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
						$('#add-formtermos-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#add-formtermos').validate();

		//ADD text editor
		$('#termoAdd').summernote({
			height: 150,
			maximumImageFileSize: 1024 * 1024, // 1Mb
			fontSizes: ['8', '9', '10', '11', '12', '14', '18', '20', '36', '72'],
			lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
			toolbar: [
				['style', ['style']],
				['fontname', ['fontname']],
				['font', ['color', 'strikethrough', 'superscript', 'subscript', 'bold', 'underline', 'clear']],
				['fontsize', ['fontsize']],
				['height', ['height']],
				['para', ['ul', 'ol', 'paragraph']],
				['table', ['table']],
				['insert', ['link', 'hr']],
				['view', ['fullscreen', 'codeview', 'help']],
				['redo'],
				['undo'],
			],

		})

		$('#termoAdd').summernote('reset');


	}

	function edittermos(codTermo) {
		$.ajax({
			url: '<?php echo base_url('termos/getOne') ?>',
			type: 'post',
			data: {
				codTermo: codTermo,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),

			},
			dataType: 'json',
			success: function(response) {
				// reset the form 

				$("#termoEdit").summernote('destroy');
				$("#edit-formtermos")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#edit-modaltermos').modal('show');

				$("#edit-formtermos #codTermo").val(response.codTermo);
				$("#edit-formtermos #codPessoa").val(response.codPessoa);
				$("#edit-formtermos #assunto").val(response.assunto);
				$("#edit-formtermos #termoEdit").val(response.termo);
				$("#edit-formtermos #autor").val(response.nomeExibicao);
				if (response.codStatus == '1') {
					document.getElementById("checkboxstatusEdit").checked = true;
				}



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
						var form = $('#edit-formtermos');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('termos/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#edit-formtermos-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
										$('#data_tabletermos').DataTable().ajax.reload(null, false).draw(false);
										$('#edit-modaltermos').modal('hide');
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
								$('#edit-formtermos-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#edit-formtermos').validate();
				//ADD text editor
				$('#termoEdit').summernote({
					height: 150,
					maximumImageFileSize: 1024 * 1024, // 1Mb
					fontSizes: ['8', '9', '10', '11', '12', '14', '18', '20', '36', '72'],
					lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
					toolbar: [
						['style', ['style']],
						['fontname', ['fontname']],
						['font', ['color', 'strikethrough', 'superscript', 'subscript', 'bold', 'underline', 'clear']],
						['fontsize', ['fontsize']],
						['height', ['height']],
						['para', ['ul', 'ol', 'paragraph']],
						['table', ['table']],
						['insert', ['link', 'hr']],
						['view', ['fullscreen', 'codeview', 'help']],
						['redo'],
						['undo'],
					],

				})


			}
		});
	}

	function removetermos(codTermo) {
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
					url: '<?php echo base_url('termos/remove') ?>',
					type: 'post',
					data: {
						codTermo: codTermo,
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
								$('#data_tabletermos').DataTable().ajax.reload(null, false).draw(false);
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