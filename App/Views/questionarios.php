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

<!-- Bootstrap4 Duallistbox -->
<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-md-8 mt-2">
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Questionarios</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addquestionarios()" title="Adicionar">Adicionar</button>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<table id="data_tablequestionarios" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>Código</th>
								<th>Tipo Questionário</th>
								<th>Título</th>
								<th>Informações</th>

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
<div id="questionariosAddModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Questionarios</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="questionariosAddForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>questionariosAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codQuestionario" name="codQuestionario" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codTipoQuestionario"> Tipo Quetionário: <span class="text-danger">*</span>
								</label>
								<select id="codTipoQuestionario" name="codTipoQuestionario" class="custom-select" required>
									<option></option>
									<option value="1">Heurística de Nielsen</option>
									<option value="2">SUS</option>
									<option value="3">Satisfação</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataInicio"> Data Início: <span class="text-danger">*</span> </label>
								<input type="date" id="dataInicio" name="dataInicio" class="form-control" dateISO="true" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataEncerramento"> Data Encerramento: </label>
								<input type="date" id="dataEncerramento" name="dataEncerramento" class="form-control" dateISO="true" required>
							</div>
						</div>
					</div>

					<div class="row">

						<div class="col-md-6">
							<div class="form-group">
								<label for="titulo"> Título: </label>
								<input type="text" id="titulo" name="titulo" class="form-control" placeholder="Título" maxlength="100">
							</div>
						</div>
					</div>
					<div class="row">

						<div class="col-md-12">
							<div class="form-group">
								<label for="objetivo"> Objetivo: <span class="text-danger">*</span> </label>
								<textarea cols="40" rows="5" id="objetivo" name="objetivo" class="form-control" placeholder="Objetivo" required></textarea>
							</div>
						</div>

					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="instrucoes"> Instrucões: </label>
								<textarea cols="40" rows="5" id="instrucoes" name="instrucoes" class="form-control" placeholder="Instrucoes"></textarea>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="termoAceite"> TermoAceite: <span class="text-danger">*</span> </label>
								<textarea cols="40" rows="5" id="termoAceite" name="termoAceite" class="form-control" placeholder="TermoAceite" required></textarea>
							</div>
						</div>
					</div>
					<div class="row">

						<div class="col-md-2">
							<div class="form-group">
								<label for="aplicadoUsuarios"> Aplicado à Usuários: <span class="text-danger">*</span>
								</label>
								<select id="aplicadoUsuarios" name="aplicadoUsuarios" class="custom-select" required>
									<option></option>
									<option value="1">Sim</option>
									<option value="0">Não</option>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="aplicadoFuncionarios"> Aplicado à Funcionários: <span class="text-danger">*</span> </label>
								<select id="aplicadoFuncionarios" name="aplicadoFuncionarios" class="custom-select" required>
									<option></option>
									<option value="1">Sim</option>
									<option value="0">Não</option>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="ativo"> Ativo: <span class="text-danger">*</span> </label>
								<select id="ativo" name="ativo" class="custom-select" required>
									<option></option>
									<option value="1">Sim</option>
									<option value="0">Não</option>
								</select>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="questionariosAddForm-btn">Adicionar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Add modal content -->
<div id="questionariosEditModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Questionários</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">



				<div class="col-12 col-sm-12">
					<div class="card card-primary card-tabs">
						<div class="card-header p-0 pt-1">
							<ul class="nav nav-tabs" id="editquestionario-tab" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" id="editquestionario-home-tab" data-toggle="pill" href="#editquestionario-home" role="tab" aria-controls="editquestionario-home" aria-selected="true">Geral</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="editquestionario-profile-tab" data-toggle="pill" href="#editquestionario-profile" role="tab" aria-controls="editquestionario-profile" aria-selected="false">Perguntas</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="editquestionario-messages-tab" data-toggle="pill" href="#editquestionario-messages" role="tab" aria-controls="editquestionario-messages" aria-selected="false">Respostas</a>
								</li>
							</ul>
						</div>
						<div class="card-body">
							<div class="tab-content" id="editquestionario-tabContent">
								<div class="tab-pane fade show active" id="editquestionario-home" role="tabpanel" aria-labelledby="editquestionario-home-tab">


									<form id="questionariosEditForm" class="pl-3 pr-3">
										<input type="hidden" id="<?php echo csrf_token() ?>questionariosEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">


										<div class="row">
											<input type="hidden" id="codQuestionario" name="codQuestionario" class="form-control" placeholder="Código" maxlength="11" required>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="codTipoQuestionario"> Tipo Quetionário: <span class="text-danger">*</span>
													</label>
													<select disabled id="codTipoQuestionario" name="codTipoQuestionario" class="custom-select" required>
														<option></option>
														<option value="1">Heurística de Nielsen</option>
														<option value="2">SUS</option>
														<option value="3">Satisfação</option>
													</select>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="dataInicio"> Data Início: <span class="text-danger">*</span> </label>
													<input type="date" id="dataInicio" name="dataInicio" class="form-control" dateISO="true" required>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="dataEncerramento"> Data Encerramento: </label>
													<input type="date" id="dataEncerramento" name="dataEncerramento" class="form-control" dateISO="true" required>
												</div>
											</div>
										</div>
										<div class="row">

											<div class="col-md-6">
												<div class="form-group">
													<label for="titulo"> Título: </label>
													<input type="text" id="titulo" name="titulo" class="form-control" placeholder="Título" maxlength="100">
												</div>
											</div>
										</div>
										<div class="row">

											<div class="col-md-12">
												<div class="form-group">
													<label for="objetivo"> Objetivo: <span class="text-danger">*</span>
													</label>
													<textarea cols="40" rows="5" id="objetivo" name="objetivo" class="form-control" placeholder="Objetivo" required></textarea>
												</div>
											</div>

										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<label for="instrucoes"> Instrucões: </label>
													<textarea cols="40" rows="5" id="instrucoes" name="instrucoes" class="form-control" placeholder="Instrucoes"></textarea>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<label for="termoAceite"> TermoAceite: <span class="text-danger">*</span> </label>
													<textarea cols="40" rows="5" id="termoAceite" name="termoAceite" class="form-control" placeholder="TermoAceite" required></textarea>
												</div>
											</div>
										</div>
										<div class="row">

											<div class="col-md-2">
												<div class="form-group">
													<label for="aplicadoUsuarios"> Aplicado à Usuários: <span class="text-danger">*</span>
													</label>
													<select id="aplicadoUsuarios" name="aplicadoUsuarios" class="custom-select" required>
														<option></option>
														<option value="1">Sim</option>
														<option value="0">Não</option>
													</select>
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<label for="aplicadoFuncionarios"> Aplicado à Funcionários: <span class="text-danger">*</span> </label>
													<select id="aplicadoFuncionarios" name="aplicadoFuncionarios" class="custom-select" required>
														<option></option>
														<option value="1">Sim</option>
														<option value="0">Não</option>
													</select>
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<label for="ativo"> Ativo: <span class="text-danger">*</span>
													</label>
													<select id="ativo" name="ativo" class="custom-select" required>
														<option></option>
														<option value="1">Sim</option>
														<option value="0">Não</option>
													</select>
												</div>
											</div>
										</div>

										<div class="form-group text-center">
											<div class="btn-group">
												<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="questionariosEditForm-btn">Salvar</button>
												<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
											</div>
										</div>
									</form>

								</div>
								<div class="tab-pane fade" id="editquestionario-profile" role="tabpanel" aria-labelledby="editquestionario-profile-tab">
									<div class="col-md-2">
										<div class="row">
											<button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addPerguntas()" title="Adicionar">Adicionar</button>

										</div>
									</div>

									<table id="data_tableperguntasQuestionario" class="table table-striped table-hover table-sm">
										<thead>
											<tr>
												<th>Nº</th>
												<th>Pergunta</th>
												<th>Categoria</th>
												<th></th>
											</tr>
										</thead>
									</table>


								</div>
								<div class="tab-pane fade" id="editquestionario-messages" role="tabpanel" aria-labelledby="editquestionario-messages-tab">

									<table id="data_tableRespostasQuestionario" class="table table-striped table-hover table-sm">
										<thead>
											<tr>
												<th>Nº</th>
												<th>Entrevistado</th>
												<th>Tipo</th>
												<th>Setor</th>
												<th>Tipo Usuário</th>
												<th>Módulo</th>
												<th>P1</th>
												<th>P2</th>
												<th>P3</th>
												<th>P4</th>
												<th>P5</th>
												<th>P6</th>
												<th>P7</th>
												<th>P8</th>
												<th>P9</th>
												<th>P10</th>
												<th>Pontos</th>
												<th>Escala</th>
											</tr>
										</thead>
									</table>

								</div>
								<div class="tab-pane fade" id="editquestionario-settings" role="tabpanel" aria-labelledby="editquestionario-settings-tab">

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
		$('#data_tablequestionarios').DataTable({
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
				"url": '<?php echo base_url('questionarios/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});
	});

	function addPerguntas() {
		// reset the form respostaQuestionarioForm
		$("#selecaoPerguntasAddForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#selecaoPerguntasAddModal').modal('show');

		$("#selecaoPerguntasAddForm #codPerguntaAdd").html('').select2({
			data: [{
				id: '',
				text: ''
			}]
		});

		$.ajax({
			url: '<?php echo base_url('PerguntasQuestionario/listaDropDownPerguntas') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				codQuestionario: $("#questionariosEditForm #codQuestionario").val(),
				codTipoQuestionario: $("#questionariosEditForm #codTipoQuestionario").val(),
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(adicionarPergunta) {




				$("#selecaoPerguntasAddForm #codPerguntaAdd").select2({
					data: adicionarPergunta,
				})

				$("#selecaoPerguntasAddForm #codPerguntaAdd").bootstrapDualListbox('refresh', true);

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
				if (element.parent('. input-group').length) {
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

				var form = $('#selecaoPerguntasAddForm');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('perguntasQuestionario/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#questionariosAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function(response) {

						if (response.success === true) {
							$('#selecaoPerguntasAddModal').modal('hide');

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
								$('#data_tableperguntasQuestionario').DataTable().ajax.reload(null, false).draw(false);
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
					}
				});

				return false;
			}
		});
		$('#selecaoPerguntasAddForm').validate();






	}


	function addquestionarios() {
		// reset the form 
		$("#questionariosAddForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#questionariosAddModal').modal('show');
		// submit the add from 



		$('#questionariosAddForm #objetivo').summernote({
			height: 150,
			maximumImageFileSize: 1024 * 1024, // 1Mb
			fontSizes: ['8', '9', '10', '11', '12', '14', '18', '20', '36', '72'],
			lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
			toolbar: [
				['font', ['bold']],
				['fontsize', ['fontsize']],
				['para', ['ul', 'ol', 'paragraph']],
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



		$('#questionariosAddForm #instrucoes').summernote({
			height: 150,
			maximumImageFileSize: 1024 * 1024, // 1Mb
			fontSizes: ['8', '9', '10', '11', '12', '14', '18', '20', '36', '72'],
			lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
			toolbar: [
				['font', ['bold']],
				['fontsize', ['fontsize']],
				['para', ['ul', 'ol', 'paragraph']],
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


		$('#questionariosAddForm #termoAceite').summernote({
			height: 150,
			maximumImageFileSize: 1024 * 1024, // 1Mb
			fontSizes: ['8', '9', '10', '11', '12', '14', '18', '20', '36', '72'],
			lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
			toolbar: [
				['font', ['bold']],
				['fontsize', ['fontsize']],
				['para', ['ul', 'ol', 'paragraph']],
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

				var form = $('#questionariosAddForm');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('questionarios/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#questionariosAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function(response) {

						if (response.success === true) {
							$('#questionariosAddModal').modal('hide');

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
								$('#data_tablequestionarios').DataTable().ajax.reload(null, false).draw(false);
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
						$('#questionariosAddForm-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#questionariosAddForm').validate();
	}


	function addquestionarios_OLD() {
		// reset the form 
		$("#questionariosAddForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#questionariosAddModal').modal('show');
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
				if (element.parent('. input-group').length) {
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

				var form = $('#questionariosAddForm');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('questionarios/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#questionariosAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function(response) {

						if (response.success === true) {
							$('#questionariosAddModal').modal('hide');

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
								$('#data_tablequestionarios').DataTable().ajax.reload(null, false).draw(false);
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
						$('#questionariosAddForm-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#questionariosAddForm').validate();
	}

	function editquestionarios(codQuestionario) {
		$.ajax({
			url: '<?php echo base_url('questionarios/getOne') ?>',
			type: 'post',
			data: {
				codQuestionario: codQuestionario,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#questionariosEditForm")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#questionariosEditModal').modal('show');

				$("#questionariosEditForm #codQuestionario").val(response.codQuestionario);
				$("#questionariosEditForm #codTipoQuestionario").val(response.codTipoQuestionario);
				$("#questionariosEditForm #dataInicio").val(response.dataInicio);
				$("#questionariosEditForm #dataEncerramento").val(response.dataEncerramento);
				$("#questionariosEditForm #ativo").val(response.ativo);
				$("#questionariosEditForm #objetivo").val(response.objetivo);
				$("#questionariosEditForm #titulo").val(response.titulo);
				$("#questionariosEditForm #instrucoes").val(response.instrucoes);
				$("#questionariosEditForm #termoAceite").val(response.termoAceite);
				$("#questionariosEditForm #aplicadoUsuarios").val(response.aplicadoUsuarios);
				$("#questionariosEditForm #aplicadoFuncionarios").val(response.aplicadoFuncionarios);


				$("#selecaoPerguntasAddForm #codQuestionario").val(codQuestionario);


				$(function() {
					$('#data_tableperguntasQuestionario').DataTable({
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
							"url": '<?php echo base_url('perguntasQuestionario/getAll') ?>',
							"type": "POST",
							"dataType": "json",
							async: "true",
							data: {
								codQuestionario: codQuestionario,
								csrf_sandra: $("#csrf_sandraPrincipal").val(),
							},
						}
					});
				});

				$(function() {
					$('#data_tableRespostasQuestionario').DataTable({
						"sScrollX": "100%",
						"sScrollXInner": "110%",
						"bScrollCollapse": true,
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
							"url": '<?php echo base_url('questionarios/pegaDadosDemograficos') ?>',
							"type": "POST",
							"dataType": "json",
							async: "true",
							data: {
								codQuestionario: codQuestionario,
								csrf_sandra: $("#csrf_sandraPrincipal").val(),
							},
						},
						c
					});
				});




				$('#questionariosEditForm #objetivo').summernote({
					height: 150,
					maximumImageFileSize: 1024 * 1024, // 1Mb
					fontSizes: ['8', '9', '10', '11', '12', '14', '18', '20', '36', '72'],
					lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
					toolbar: [
						['font', ['bold']],
						['fontsize', ['fontsize']],
						['para', ['ul', 'ol', 'paragraph']],
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



				$('#questionariosEditForm #instrucoes').summernote({
					height: 150,
					maximumImageFileSize: 1024 * 1024, // 1Mb
					fontSizes: ['8', '9', '10', '11', '12', '14', '18', '20', '36', '72'],
					lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
					toolbar: [
						['font', ['bold']],
						['fontsize', ['fontsize']],
						['para', ['ul', 'ol', 'paragraph']],
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


				$('#questionariosEditForm #termoAceite').summernote({
					height: 150,
					maximumImageFileSize: 1024 * 1024, // 1Mb
					fontSizes: ['8', '9', '10', '11', '12', '14', '18', '20', '36', '72'],
					lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
					toolbar: [
						['font', ['bold']],
						['fontsize', ['fontsize']],
						['para', ['ul', 'ol', 'paragraph']],
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
						var form = $('#questionariosEditForm');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('questionarios/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#questionariosEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
							},
							success: function(response) {

								if (response.success === true) {

									$('#questionariosEditModal').modal('hide');


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
										$('#data_tablequestionarios').DataTable().ajax.reload(null, false).draw(false);
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
								$('#questionariosEditForm-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#questionariosEditForm').validate();

			}
		});
	}

	function removequestionarios(codQuestionario) {
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
					url: '<?php echo base_url('questionarios/remove') ?>',
					type: 'post',
					data: {
						codQuestionario: codQuestionario,
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
								$('#data_tablequestionarios').DataTable().ajax.reload(null, false).draw(false);
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