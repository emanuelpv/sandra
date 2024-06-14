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
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Itens Modelo</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="additensModelo()" title="Adicionar">Adicionar</button>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<table id="data_tableitensModelo" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>Código</th>
								<th>CodCat</th>
								<th>Descrição</th>
								<th>Tipo Material</th>
								<th>Data Criacção</th>
								<th>CodAutor</th>
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
<div id="itensModeloAddModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Itens Modelo</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="itensModeloAddForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>itensModeloAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codItemModelo" name="codItemModelo" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codCat">CatMat ou CatServ: </label>
								<input type="number" id="codCat" name="codCat" class="form-control" placeholder="CodCat" maxlength="11" number="true" required>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="tipoMaterial"> TipoMaterial: </label>
								<select id="tipoMaterial" name="tipoMaterial" class="custom-select" required>
									<option></option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="unidade"> unidade: </label>
								<select id="unidade" name="unidade" class="custom-select" required>
									<option></option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">

						<div class="col-md-12">
							<div class="form-group">
								<label for="descricao"> Descrição: </label>
								<textarea cols="40" rows="5" id="descricao" name="descricao" class="form-control" placeholder="Descrição" required></textarea>
							</div>
						</div>
					</div>
					<div class="row">
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="itensModeloAddForm-btn">Adicionar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>

<!-- Add modal content -->
<div id="itensModeloEditModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Itens Modelo</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="itensModeloEditForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>itensModeloEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codItemModelo" name="codItemModelo" class="form-control" placeholder="Código" maxlength="11" required>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codCat">CatMat ou CatServ: </label>
								<input type="number" id="codCat" name="codCat" class="form-control" placeholder="CodCat" maxlength="11" number="true" required>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="tipoMaterial"> TipoMaterial: </label>
								<select id="tipoMaterial" name="tipoMaterial" class="custom-select" required>
									<option></option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="unidae"> Unidade: </label>
								<select id="unidade" name="unidade" class="custom-select" required>
									<option></option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">

						<div class="col-md-12">
							<div class="form-group">
								<label for="descricao"> Descrição: </label>
								<textarea cols="40" rows="5" id="descricao" name="descricao" class="form-control" placeholder="Descrição" required></textarea>
							</div>
						</div>
					</div>


					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="itensModeloEditForm-btn">Salvar</button>
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
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/summernote/summernote-bs4.min.js"></script>

<script>
	$(document).on('show.bs.modal', '.modal', function() {
		var zIndex = 1040 + (10 * $('.modal:visible').length);
		$(this).css('z-index', zIndex);
		setTimeout(function() {
			$('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
		}, 0);
	});

	$(function() {
		$('#data_tableitensModelo').DataTable({
			"paging": true,
			"deferRender": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('itensModelo/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});
	});

	function additensModelo() {
		// reset the form 
		$("#itensModeloAddForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#itensModeloAddModal').modal('show');
		// submit the add from 


		$("#itensModeloAddForm #descricao").summernote({
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
				['insert', ['link', 'picture', 'video', 'hr']],
				['view', ['fullscreen', 'codeview', 'help']],
				['redo'],
				['undo'],
			],
			callbacks: {
				onImageUploadError: function(msg) {
					var Toast = Swal.mixin({
						toast: true,
						position: 'top-end',
						showConfirmButton: false,
						timer: 5000
					});
					Toast.fire({
						icon: 'error',
						title: 'Tamanho máximo de imagens é 1Mb'
					})
				}
			}
		})

		$.ajax({
			url: '<?php echo base_url('itensRequisicao/listaDropDownTipoMaterial') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(tipoMaterialAdd) {

				$("#itensModeloAddForm #tipoMaterial").select2({
					data: tipoMaterialAdd,
				})

				$('#itensModeloAddForm #tipoMaterial').val(null);
				$('#itensModeloAddForm #tipoMaterial').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});
			}
		})




		$.ajax({
			url: '<?php echo base_url('itensRequisicao/listaDropDownUnidades') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(unidadeAdd) {

				$("#itensModeloAddForm #unidade").select2({
					data: unidadeAdd,
				})

				$('#itensModeloAddForm #unidade').val(null);
				$('#itensModeloAddForm #unidade').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});
			}
		})



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

				var form = $('#itensModeloAddForm');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('itensModelo/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#itensModeloAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function(response) {

						if (response.success === true) {
							$('#itensModeloAddModal').modal('hide');

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
								$('#data_tableitensModelo').DataTable().ajax.reload(null, false).draw(false);
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
						$('#itensModeloAddForm-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#itensModeloAddForm').validate();
	}

	function edititensModelo(codItemModelo) {
		$.ajax({
			url: '<?php echo base_url('itensModelo/getOne') ?>',
			type: 'post',
			data: {
				codItemModelo: codItemModelo,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#itensModeloEditForm")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#itensModeloEditModal').modal('show');

				$("#itensModeloEditForm #codItemModelo").val(response.codItemModelo);
				$("#itensModeloEditForm #codCat").val(response.codCat);
				$("#itensModeloEditForm #descricao").val(response.descricao);
				$("#itensModeloEditForm #tipoMaterial").val(response.tipoMaterial);
				$("#itensModeloEditForm #unidade").val(response.unidade);
				$("#itensModeloEditForm #dataCriacao").val(response.dataCriacao);
				$("#itensModeloEditForm #dataAtualizacao").val(response.dataAtualizacao);
				$("#itensModeloEditForm #codAutor").val(response.codAutor);


				$("#itensModeloEditForm #descricao").summernote({
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
						['insert', ['link', 'picture', 'video', 'hr']],
						['view', ['fullscreen', 'codeview', 'help']],
						['redo'],
						['undo'],
					],
					callbacks: {
						onImageUploadError: function(msg) {
							var Toast = Swal.mixin({
								toast: true,
								position: 'top-end',
								showConfirmButton: false,
								timer: 5000
							});
							Toast.fire({
								icon: 'error',
								title: 'Tamanho máximo de imagens é 1Mb'
							})
						}
					}
				})

				$.ajax({
					url: '<?php echo base_url('itensRequisicao/listaDropDownTipoMaterial') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(tipoMaterialAdd) {

						$("#itensModeloEditForm #tipoMaterial").select2({
							data: tipoMaterialAdd,
						})

						$('#itensModeloEditForm #tipoMaterial').val(response.tipoMaterial);
						$('#itensModeloEditForm #tipoMaterial').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});
					}
				})


				$.ajax({
					url: '<?php echo base_url('itensRequisicao/listaDropDownUnidades') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(unidadeAdd) {

						$("#itensModeloEditForm #unidade").select2({
							data: unidadeAdd,
						})

						$('#itensModeloEditForm #unidade').val(response.unidade);
						$('#itensModeloEditForm #unidade').trigger('change');
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
						var form = $('#itensModeloEditForm');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('itensModelo/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#itensModeloEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
							},
							success: function(response) {

								if (response.success === true) {

									$('#itensModeloEditModal').modal('hide');


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
										$('#data_tableitensModelo').DataTable().ajax.reload(null, false).draw(false);
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
								$('#itensModeloEditForm-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#itensModeloEditForm').validate();

			}
		});
	}

	function removeitensModelo(codItemModelo) {
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
					url: '<?php echo base_url('itensModelo/remove') ?>',
					type: 'post',
					data: {
						codItemModelo: codItemModelo,
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
								$('#data_tableitensModelo').DataTable().ajax.reload(null, false).draw(false);
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