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
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Requisições</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addparterequisitoria()" title="Adicionar">Adicionar</button>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<table id="data_tableparterequisitoria" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>Requisição</th>
								<th>Descricao</th>
								<th>Departamento</th>
								<th>Classe</th>
								<th>Tipo</th>
								<th>Data</th>
								<th>Valor Total</th>
								<th>MatSau</th>
								<th>CarDisp</th>
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
<div id="parterequisitoriaAddModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Requisições</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="parterequisitoriaAddForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>parterequisitoriaAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codRequisicao" name="codRequisicao" class="form-control" placeholder="CodRequisicao" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<label for="descricao"> Descrição: <span class="text-danger">*</span> </label>
								<textarea cols="40" rows="5" id="descricao" name="descricao" class="form-control" placeholder="Descricao" required></textarea>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="codClasseRequisicao"> Classe: <span class="text-danger">*</span> </label>
								<select id="codClasseRequisicao" name="codClasseRequisicao" class="custom-select" required>
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="codTipoRequisicao"> Tipo Requisição: <span class="text-danger">*</span> </label>
								<select id="codTipoRequisicao" name="codTipoRequisicao" class="custom-select" required>
									<option value=""></option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<label for="codDepartamento"> Departamento: <span class="text-danger">*</span> </label>
							<select id="codDepartamento" name="codDepartamento" class="custom-select" required>
								<option value=""></option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataRequisicao"> Data Requisição: <span class="text-danger">*</span> </label>
								<input type="date" id="dataRequisicao" name="dataRequisicao" class="form-control" dateISO="true" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="valorTotal"> Valor Total: </label>
								<input type="number" id="valorTotal" name="valorTotal" class="form-control" placeholder="ValorTotal" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="matSau"> MatSau: <span class="text-danger">*</span> </label>
								<select id="matSau" name="matSau" class="custom-select" required>
									<option value=""></option>
									<option value="1">SIM</option>
									<option value="0">NÂO</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="carDisp"> CarDisp: <span class="text-danger">*</span> </label>
								<select id="carDisp" name="carDisp" class="custom-select" required>
									<option value=""></option>
									<option value="1">SIM</option>
									<option value="0">NÂO</option>
								</select>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="parterequisitoriaAddForm-btn">Adicionar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Add modal content -->
<div id="parterequisitoriaEditModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Requisição</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="col-12 col-sm-12">
					<div class="card card-primary card-tabs">
						<div class="card-header p-0 pt-1">
							<ul class="nav nav-tabs" id="parterequisitoria-tab" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" id="parterequisitoria-Geral-tab" data-toggle="pill" href="#parterequisitoria-Geral" role="tab" aria-controls="parterequisitoria-Geral" aria-selected="true">Geral</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="parterequisitoria-Itens-tab" data-toggle="pill" href="#parterequisitoria-Itens" role="tab" aria-controls="parterequisitoria-Itens" aria-selected="false">Itens</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="parterequisitoria-InformacoesComplementares-tab" data-toggle="pill" href="#parterequisitoria-InformacoesComplementares" role="tab" aria-controls="parterequisitoria-InformacoesComplementares" aria-selected="false">InformacoesComplementares</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="parterequisitoria-Anexos-tab" data-toggle="pill" href="#parterequisitoria-Anexos" role="tab" aria-controls="parterequisitoria-Anexos" aria-selected="false">Anexos</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="parterequisitoria-Despachos-tab" data-toggle="pill" href="#parterequisitoria-Despachos" role="tab" aria-controls="parterequisitoria-Despachos" aria-selected="false">Despachos</a>
								</li>
							</ul>
						</div>
						<div class="card-body">
							<div class="tab-content" id="parterequisitoria-tabContent">
								<div class="tab-pane fade show active" id="parterequisitoria-Geral" role="tabpanel" aria-labelledby="parterequisitoria-Geral-tab">
									<form id="parterequisitoriaEditForm" class="pl-3 pr-3">
										<input type="hidden" id="<?php echo csrf_token() ?>parterequisitoriaEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

										<div class="row">
											<input type="hidden" id="codRequisicao" name="codRequisicao" class="form-control" placeholder="CodRequisicao" maxlength="11" required>
										</div>
										<div class="row">
											<div class="col-md-8">
												<div class="form-group">
													<label for="descricao"> Descrição: <span class="text-danger">*</span> </label>
													<textarea cols="40" rows="5" id="descricao" name="descricao" class="form-control" placeholder="Descricao" required></textarea>
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<label for="codClasseRequisicao"> Classe: <span class="text-danger">*</span> </label>
													<select id="codClasseRequisicao" name="codClasseRequisicao" class="custom-select" required>
														<option value=""></option>
													</select>
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<label for="codTipoRequisicao"> Tipo Requisição: <span class="text-danger">*</span> </label>
													<select id="codTipoRequisicao" name="codTipoRequisicao" class="custom-select" required>
														<option value=""></option>
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<label for="codDepartamento"> Departamento: <span class="text-danger">*</span> </label>
												<select disabled="disabled" id="codDepartamento" name="codDepartamento" class="custom-select" required>
													<option value=""></option>
												</select>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="dataRequisicao"> Data Requisição: <span class="text-danger">*</span> </label>
													<input type="date" id="dataRequisicao" name="dataRequisicao" class="form-control" dateISO="true" required>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="valorTotal"> Valor Total: </label>
													<input type="number" id="valorTotal" name="valorTotal" class="form-control" placeholder="ValorTotal" required>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="matSau"> MatSau: <span class="text-danger">*</span> </label>
													<select id="matSau" name="matSau" class="custom-select" required>
														<option value=""></option>
														<option value="1">SIM</option>
														<option value="0">NÂO</option>
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="carDisp"> CarDisp: <span class="text-danger">*</span> </label>
													<select id="carDisp" name="carDisp" class="custom-select" required>
														<option value="0">
															</opion>
														<option value="1">SIM</option>
														<option value="0">NÂO</option>
													</select>
												</div>
											</div>
										</div>

										<div class="form-group text-center">
											<div class="btn-group">
												<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="parterequisitoriaEditForm-btn">Salvar</button>
												<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
											</div>
										</div>
									</form>

								</div>
								<div class="tab-pane fade" id="parterequisitoria-Itens" role="tabpanel" aria-labelledby="parterequisitoria-Itens-tab">
									Itens - select * from apolo.ges_itens
								</div>
								<div class="tab-pane fade" id="parterequisitoria-InformacoesComplementares" role="tabpanel" aria-labelledby="parterequisitoria-InformacoesComplementares-tab">
									Informações Complementares - select * apolo.from ges_reqinfocompl
								</div>
								<div class="tab-pane fade" id="parterequisitoria-Anexos" role="tabpanel" aria-labelledby="parterequisitoria-Anexos-tab">
									Anexos - select * from apolo.ges_ranexos
								</div>
								<div class="tab-pane fade" id="parterequisitoria-Despachos" role="tabpanel" aria-labelledby="parterequisitoria-Despachos-tab">
									Despachos - select * from apolo.ges_reqdespachos
								</div>
							</div>
						</div>
						<!-- /.card -->
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

		codDepartamentoTmp = '<?php echo session()->codDepartamento ?>';


		$('#data_tableparterequisitoria').DataTable({
			"paging": true,
			"deferRender": true,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('parterequisitoria/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});
	});

	function addparterequisitoria() {
		// reset the form 
		$("#parterequisitoriaAddForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#parterequisitoriaAddModal').modal('show');





		$.ajax({
			url: '<?php echo base_url('Departamentos/listaDropDown') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(listaDepartamentos) {

				$("#parterequisitoriaAddForm #codDepartamento").select2({
					data: listaDepartamentos,
				})

				$("#parterequisitoriaAddForm #codDepartamento").val(codDepartamentoTmp); // Select the option with a value of '1'
				$("#parterequisitoriaAddForm #codDepartamento").trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})




		$.ajax({
			url: '<?php echo base_url('parterequisitoria/listaDropDownTipoparterequisitoria') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(listaTipoRequisicao) {

				$("#parterequisitoriaAddForm #codTipoRequisicao").select2({
					data: listaTipoRequisicao,
				})

				$("#parterequisitoriaAddForm #codTipoRequisicao").val(null); // Select the option with a value of '1'
				$("#parterequisitoriaAddForm #codTipoRequisicao").trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})

		$.ajax({
			url: '<?php echo base_url('parterequisitoria/listaDropDownClasseparterequisitoria') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(listaTipoServico) {

				$("#parterequisitoriaAddForm #codClasseRequisicao").select2({
					data: listaTipoServico,
				})

				$("#parterequisitoriaAddForm #codClasseRequisicao").val(null); // Select the option with a value of '1'
				$("#parterequisitoriaAddForm #codClasseRequisicao").trigger('change');
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

				var form = $('#parterequisitoriaAddForm');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('parterequisitoria/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#parterequisitoriaAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function(response) {

						if (response.success === true) {
							$('#parterequisitoriaAddModal').modal('hide');

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
								$('#data_tableparterequisitoria').DataTable().ajax.reload(null, false).draw(false);
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
						$('#parterequisitoriaAddForm-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#parterequisitoriaAddForm').validate();
	}

	function editparterequisitoria(codRequisicao) {
		$.ajax({
			url: '<?php echo base_url('parterequisitoria/getOne') ?>',
			type: 'post',
			data: {
				codRequisicao: codRequisicao,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#parterequisitoriaEditForm")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#parterequisitoriaEditModal').modal('show');

				$("#parterequisitoriaEditForm #codRequisicao").val(response.codRequisicao);
				$("#parterequisitoriaEditForm #descricao").val(response.descricao);
				$("#parterequisitoriaEditForm #dataRequisicao").val(response.dataRequisicaoV);
				$("#parterequisitoriaEditForm #valorTotal").val(response.valorTotal);
				$("#parterequisitoriaEditForm #matSau").val(response.matSau);
				$("#parterequisitoriaEditForm #carDisp").val(response.carDisp);





				$.ajax({
					url: '<?php echo base_url('Departamentos/listaDropDown') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(listaDepartamentos) {

						$("#parterequisitoriaEditForm #codDepartamento").select2({
							data: listaDepartamentos,
						})

						$("#parterequisitoriaEditForm #codDepartamento").val(response.codDepartamento); // Select the option with a value of '1'
						$("#parterequisitoriaEditForm #codDepartamento").trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

					}
				});


				$.ajax({
					url: '<?php echo base_url('parterequisitoria/listaDropDownTipoparterequisitoria') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(listaTipoRequisicao) {

						$("#parterequisitoriaEditForm #codTipoRequisicao").select2({
							data: listaTipoRequisicao,
						})

						$("#parterequisitoriaEditForm #codTipoRequisicao").val(response.codTipoRequisicao); // Select the option with a value of '1'
						$("#parterequisitoriaEditForm #codTipoRequisicao").trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

					}
				})

				$.ajax({
					url: '<?php echo base_url('parterequisitoria/listaDropDownClasseparterequisitoria') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(listaTipoServico) {

						$("#parterequisitoriaEditForm #codClasseRequisicao").select2({
							data: listaTipoServico,
						})

						$("#parterequisitoriaEditForm #codClasseRequisicao").val(response.codClasseRequisicao); // Select the option with a value of '1'
						$("#parterequisitoriaEditForm #codClasseRequisicao").trigger('change');
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
						var form = $('#parterequisitoriaEditForm');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('parterequisitoria/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#parterequisitoriaEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
							},
							success: function(response) {

								if (response.success === true) {

									$('#parterequisitoriaEditModal').modal('hide');


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
										$('#data_tableparterequisitoria').DataTable().ajax.reload(null, false).draw(false);
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
								$('#parterequisitoriaEditForm-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#parterequisitoriaEditForm').validate();

			}
		});
	}

	function removeparterequisitoria(codRequisicao) {
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
					url: '<?php echo base_url('parterequisitoria/remove') ?>',
					type: 'post',
					data: {
						codRequisicao: codRequisicao,
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
								$('#data_tableparterequisitoria').DataTable().ajax.reload(null, false).draw(false);
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