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
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">slideshow</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addslideshow()" title="Adicionar">Adicionar</button>
						</div>
					</div>
				</div>

				<div class="card-body">
					<table id="data_tableslideshow" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>Ordem</th>
								<th>Descrição</th>
								<th>Imagem</th>
								<th>Url</th>
								<th>Data Expiração</th>
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


<div id="slideshowAddModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar slideshow</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="slideshowAddForm" method="post" accept-charset="utf-8" enctype="multipart/form-data">
					<input type="hidden" id="<?php echo csrf_token() ?>slideshowAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codSlideShow" name="codSlideShow" class="form-control" placeholder="CodSlideShow" maxlength="11" required>
					</div>


					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="descricao"> Descrição: <span class="text-danger">*</span> </label>
								<input type="text" id="descricao" name="descricao" class="form-control" placeholder="Descrição" maxlength="100" required>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="url"> Url: </label>
								<input type="text" id="url" name="url" class="form-control" placeholder="Url" maxlength="100">
							</div>
						</div>

					</div>
					<div class="row">

						<div class="col-md-4">
							<label for="fileSlideShow">Selecione o arquivo desejado</label>
							<input type="file" id="fileSlideShow" name="fileSlideShow" class="form-control" style="height:45px;">
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataExpiracao"> Data Expiração: <span class="text-danger">*</span> </label>
								<input type="date" id="dataExpiracao" name="dataExpiracao" class="form-control" placeholder="Data Expiração" maxlength="11">
							</div>
						</div>

					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codStatusSlideShow"> Status: </label>
								<div class="icheck-primary d-inline">
									<style>
										input[type=checkbox] {
											transform: scale(1.8);
										}
									</style>
									<input style="margin-left:5px;" name='codStatus' type="checkbox" id="codStatusSlideShow">

								</div>
							</div>

						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="slideshowAddForm-btn">Adicionar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Add modal content -->
<div id="slideshowEditModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar slideshow</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="slideshowEditForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>slideshowEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codSlideShow" name="codSlideShow" class="form-control" placeholder="CodSlideShow" maxlength="11" required>
						<input type="hidden" id="imagem" name="imagem" class="form-control">
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="descricao"> Descrição: <span class="text-danger">*</span> </label>
								<input type="text" id="descricao" name="descricao" class="form-control" placeholder="Descrição" maxlength="100" required>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="url"> Url: </label>
								<input type="text" id="url" name="url" class="form-control" placeholder="Url" maxlength="100">
							</div>
						</div>

					</div>
					<div class="row">

						<div class="col-md-4">
							<label for="fileSlideShowEdit">Selecione o arquivo desejado</label>
							<input type="file" id="fileSlideShowEdit" name="fileSlideShowEdit" class="form-control" style="height:45px;">
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataExpiracao"> Data Expiração: <span class="text-danger">*</span> </label>
								<input type="date" id="dataExpiracao" name="dataExpiracao" class="form-control" placeholder="Data Expiração" maxlength="11">
							</div>
						</div>

						<div class="col-md-1">
							<div class="form-group">
								<label for="ordemSlideShow"> Ordem: <span class="text-danger">*</span> </label>
								<input type="number" id="ordemSlideShow" name="ordem" class="form-control" maxlength="11">
							</div>
						</div>

					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codStatusSlideShowEdit"> Status: </label>
								<div class="icheck-primary d-inline">
									<style>
										input[type=checkbox] {
											transform: scale(1.8);
										}
									</style>
									<input style="margin-left:5px;" name='codStatus' type="checkbox" id="codStatusSlideShowEdit">

								</div>
							</div>

						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="slideshowEditForm-btn">Salvar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>

			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>


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
		$('#data_tableslideshow').DataTable({
			"paging": true,
			"deferRender": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('slideshow/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});
	});

	
	
	function addslideshow() {
		// reset the form 
		$("#slideshowAddForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#slideshowAddModal').modal('show');
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

				var form = $('#slideshowAddForm');

				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('slideshow/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#slideshowAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function(response) {

						if (response.success === true) {

							if ($('#fileSlideShowEdit')[0].files[0] !== undefined) {

								var formData = new FormData();
								formData.append('file', $('#fileSlideShow')[0].files[0]);
								formData.append('codSlideShow', response.codSlideShow);
								formData.append('csrf_sandra', $("#csrf_sandraPrincipal").val());
								$.ajax({
									url: '<?php echo base_url('Slideshow/envia_slideShow') ?>',
									type: 'post',
									data: formData,
									processData: false, // tell jQuery not to process the data
									contentType: false, // tell jQuery not to set contentType
									dataType: 'json',
									success: function(response) {


										if (response.success === true) {
											$('#slideshowAddModal').modal('hide');

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
												$('#data_tableslideshow').DataTable().ajax.reload(null, false).draw(false);
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
								})
							} else {
								if (response.success === true) {
									$('#slideshowEditModal').modal('hide');

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
										$('#data_tableslideshow').DataTable().ajax.reload(null, false).draw(false);
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
						$('#slideshowAddForm-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#slideshowAddForm').validate();
	}

	function editslideshow(codSlideShow) {
		$.ajax({
			url: '<?php echo base_url('slideshow/getOne') ?>',
			type: 'post',
			data: {
				codSlideShow: codSlideShow,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#slideshowEditForm")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#slideshowEditModal').modal('show');

				$("#slideshowEditForm #codSlideShow").val(response.codSlideShow);
				$("#slideshowEditForm #descricao").val(response.descricao);
				$("#slideshowEditForm #url").val(response.url);
				$("#slideshowEditForm #imagem").val(response.imagem);
				$("#slideshowEditForm #ordemSlideShow").val(response.ordem);
				$("#slideshowEditForm #dataExpiracao").val(response.dataExpiracao);
				if (response.codStatus == '1') {
					document.getElementById("codStatusSlideShowEdit").checked = true;
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
						var form = $('#slideshowEditForm');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('slideshow/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#slideshowEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
							},
							success: function(response) {


								if (response.success === true) {

									if ($('#fileSlideShowEdit')[0].files[0] !== undefined) {

										var formData = new FormData();
										formData.append('file', $('#fileSlideShowEdit')[0].files[0]);
										formData.append('codSlideShow', response.codSlideShow);
										formData.append('imagem', response.imagem);
										formData.append('csrf_sandra', $("#csrf_sandraPrincipal").val());
										$.ajax({
											url: '<?php echo base_url('Slideshow/envia_slideShow') ?>',
											type: 'post',
											data: formData,
											processData: false, // tell jQuery not to process the data
											contentType: false, // tell jQuery not to set contentType
											dataType: 'json',
											success: function(response) {

												if (response.success === true) {
													$('#slideshowEditModal').modal('hide');

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
														$('#data_tableslideshow').DataTable().ajax.reload(null, false).draw(false);
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
										})
									} else {
										if (response.success === true) {
											$('#slideshowEditModal').modal('hide');

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
												$('#data_tableslideshow').DataTable().ajax.reload(null, false).draw(false);
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
								$('#slideshowEditForm-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#slideshowEditForm').validate();

			}
		});
	}

	function removeslideshow(codSlideShow) {
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
					url: '<?php echo base_url('slideshow/remove') ?>',
					type: 'post',
					data: {
						codSlideShow: codSlideShow,
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
								$('#data_tableslideshow').DataTable().ajax.reload(null, false).draw(false);
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