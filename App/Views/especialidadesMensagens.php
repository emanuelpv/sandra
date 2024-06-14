<?php


?>


<style>
	.modal {
		overflow: auto !important;
	}
</style>
<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-md-8 mt-2">
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Mensagens Personalizadas</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary" onclick="add()" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">


					<div class="col-12 col-sm-12">
						<div class="card card-primary card-tabs">
							<div class="card-header p-0 pt-1">
								<ul class="nav nav-tabs" id="especialidades-tab" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" id="especialidades-consultas-tab" data-toggle="pill" href="#especialidades-consultas" role="tab" aria-controls="especialidades-consultas" aria-selected="true">Agendamento Consultas</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="especialidades-encaminhamentos-tab" data-toggle="pill" href="#especialidades-encaminhamentos" role="tab" aria-controls="especialidades-encaminhamentos" aria-selected="false">Indicação Clínica</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="especialidades-agendamentosExames-tab" data-toggle="pill" href="#especialidades-agendamentosExames" role="tab" aria-controls="especialidades-agendamentosExames" aria-selected="false">Agendamento de Exames</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="especialidades-paciente-tab" data-toggle="pill" href="#especialidades-paciente" role="tab" aria-controls="especialidades-paciente" aria-selected="false">Paciente</a>
									</li>
								</ul>
							</div>
							<div class="card-body">
								<div class="tab-content" id="especialidades-tabContent">
									<div class="tab-pane fade show active" id="especialidades-consultas" role="tabpanel" aria-labelledby="especialidades-consultas-tab">
										<table id="data_table" class="table table-striped table-hover table-sm">
											<thead>
												<tr>
													<th>Codigo</th>
													<th>Descrição especialidade</th>
													<th>Mensagem Sucesso</th>
													<th>Mensagem Falha</th>
													<th></th>
												</tr>
											</thead>
										</table>

									</div>
									<div class="tab-pane fade" id="especialidades-encaminhamentos" role="tabpanel" aria-labelledby="especialidades-encaminhamentos-tab">
										<table id="data_tableexigirIndicacao" class="table table-striped table-hover table-sm">
											<thead>
												<tr>
													<th>Codigo</th>
													<th>Descrição especialidade</th>
													<th>Mensagem</th>
													<th></th>
												</tr>
											</thead>
										</table>
									</div>
									<div class="tab-pane fade" id="especialidades-agendamentosExames" role="tabpanel" aria-labelledby="especialidades-agendamentosExames-tab">

										Agendamento de Exames
									</div>
									<div class="tab-pane fade" id="especialidades-paciente" role="tabpanel" aria-labelledby="especialidades-paciente-tab">


										<form autocomplete="off" id="mensagemPaciente-form" class="pl-3 pr-3">
											<div class="row">
												<input type="hidden" id="<?php echo csrf_token() ?>mensagemPaciente-form" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
											</div>
											<div class="row">

												<div class="col-md-12">
													<div class="form-group">
														<label for="mensagemPaciente"> Mensagem: <span class="text-danger">*</span> </label>
														<input autocomplete="off" type="text" id="mensagemPaciente" name="mensagem" value="<?php echo $mensagemPaciente?>" class="form-control" placeholder="Mensagem de aviso quando não tiver vagas para marcação no paciente">
													</div>
												</div>

											</div>
											<div class="form-group text-left">
												<div class="btn-group">
													<button type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" onclick="salvarmensagemPaciente()" id="mensagemPaciente-form-btn">Salvar</button>
												</div>
											</div>
										</form>


									</div>
								</div>
							</div>
							<!-- /.card -->
						</div>
					</div>

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

<div id="mensagem-modal" class="modal fade" role="dialog" aria-hidden="true">


	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 id="nomeEspecialidadeInfo" class="modal-title text-white" id="info-header-modalLabel"></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">


				<div class="col-12 col-sm-12">
					<div class="card card-primary card-tabs">
						<div class="card-header p-0 pt-1">
							<ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" id="principal-tab" data-toggle="pill" href="#principal" role="tab" aria-controls="principal" aria-selected="true">Principal</a>
								</li>
							</ul>
						</div>
						<div class="card-body">
							<div class="tab-content" id="custom-tabs-one-tabContent">
								<div class="tab-pane fade show active" id="principal" role="tabpanel" aria-labelledby="principal-tab">



									<form autocomplete="off" id="mensagem-form" class="pl-3 pr-3">
										<div class="row">
											<input type="hidden" id="<?php echo csrf_token() ?>mensagem-form" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

											<input type="hidden" id="codEspecialidadeEdit" name="codEspecialidade" class="form-control" placeholder="Codigo" maxlength="11" required>
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<input disabled type="text" id="descricaoEspecialidadeEdit" name="descricaoEspecialidade" class="form-control" placeholder="Descrição especialidade" maxlength="60" required>
												</div>
											</div>
										</div>


										<div class="row">


											<div class="col-md-12">
												<div class="form-group">
													<label for="descricaoEspecialidade">Mensagem de falhas: <span class="text-danger">*</span> </label>
													<input autocomplete="off" type="text" id="mensagemFalhaMarcacao" name="mensagemFalhaMarcacao" class="form-control" placeholder="Mensagem nas falhas em pesquias por vagas">
												</div>
											</div>
										</div>



										<div class="row">

											<div class="col-md-12">
												<div class="form-group">
													<label for="descricaoEspecialidade"> Mensagem de sucesso: <span class="text-danger">*</span> </label>
													<input autocomplete="off" type="text" id="mensagemSucessoMarcacao" name="mensagemSucessoMarcacao" class="form-control" placeholder="Mensagem de sucesso em pesquias por vagas">
												</div>
											</div>


										</div>



										<div class="form-group text-center">
											<div class="btn-group">
												<button type="submit" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="mensagem-form-btn">Salvar</button>
												<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
						<!-- /.card -->
					</div>
				</div>



			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>


<div id="mensagemEncaminhamento-modal" class="modal fade" role="dialog" aria-hidden="true">


	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 id="nomeEspecialidadeInfoEncaminhamento" class="modal-title text-white" id="info-header-modalLabel"></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">


				<div class="col-12 col-sm-12">
					<div class="card card-primary card-tabs">
						<div class="card-header p-0 pt-1">
							<ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" id="principal-tab" data-toggle="pill" href="#principal" role="tab" aria-controls="principal" aria-selected="true">Principal</a>
								</li>
							</ul>
						</div>
						<div class="card-body">
							<div class="tab-content" id="custom-tabs-one-tabContent">
								<div class="tab-pane fade show active" id="principal" role="tabpanel" aria-labelledby="principal-tab">



									<form autocomplete="off" id="mensagemEncaminhamento-form" class="pl-3 pr-3">
										<div class="row">
											<input type="hidden" id="<?php echo csrf_token() ?>mensagemEncaminhamento-form" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

											<input type="hidden" id="codEspecialidadeEdit" name="codEspecialidade" class="form-control" placeholder="Codigo" maxlength="11" required>
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<input disabled type="text" id="descricaoEspecialidadeEdit" name="descricaoEspecialidade" class="form-control" placeholder="Descrição especialidade" maxlength="60" required>
												</div>
											</div>
										</div>


										<div class="row">

											<div class="col-md-12">
												<div class="form-group">
													<label for="mensagemExigirIndicacao"> Mensagem: <span class="text-danger">*</span> </label>
													<input autocomplete="off" type="text" id="mensagemExigirIndicacao" name="mensagemExigirIndicacao" class="form-control" placeholder="Mensagem de aviso sobre a exigencia de indicação clínica">
												</div>
											</div>


										</div>



										<div class="form-group text-center">
											<div class="btn-group">
												<button type="submit" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="mensagem-form-btn">Salvar</button>
												<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
						<!-- /.card -->
					</div>
				</div>



			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>


<!-- /.content -->
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

		$('#data_table').DataTable({
			"bDestroy": true,
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url($controller . '/getAllMensagensConsultas') ?>',
				"type": "get",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});

		$('#data_tableexigirIndicacao').DataTable({
			"bDestroy": true,
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url($controller . '/getAllMensagensEncaminhamentos') ?>',
				"type": "get",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});

	});

	function add() {
		// reset the form 
		$("#add-form")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#add-modal').modal('show');
		// submit the add from 



		$.ajax({
			url: '<?php echo base_url('especialidades/listaDropDownConselhos') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(conselhoAdd) {

				$("#codConselhoAdd").select2({
					data: conselhoAdd,
				})

				$('#codConselhoAdd').val(null); // Select the option with a value of '1'
				$('#codConselhoAdd').trigger('change');
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

				var form = $('#add-form');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url($controller . '/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#add-form-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
								$('#data_table').DataTable().ajax.reload(null, false).draw(false);
								$('#add-modal').modal('hide');
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
						$('#add-form-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#add-form').validate();
	}



	function salvarmensagemPaciente() {

		var form = $('#mensagemPaciente-form');
		$.ajax({
			url: '<?php echo base_url('especialidades/salvarmensagemPaciente') ?>',
			type: 'post',
			data: form.serialize(),
			dataType: 'json',
			success: function(salvarmensagemPaciente) {

				if (salvarmensagemPaciente.success === true) {
					Swal.fire({
						icon: 'success',
						title: salvarmensagemPaciente.messages,
						showConfirmButton: false,
						timer: 1500
					})
				}else{
					Swal.fire({
						icon: 'error',
						title: salvarmensagemPaciente.messages,
						showConfirmButton: false,
						timer: 1500
					})
				}
			}
		})

	}


	function editarMembroAgora() {

		$('#editMembroModal').modal('hide');

		var form = $('#editMembroForm');
		$(".text-danger").remove();
		$.ajax({
			url: '<?php echo base_url('especialidades/editMembro') ?>',
			type: 'post',
			data: form.serialize(),
			dataType: 'json',
			success: function(salvarEditMembro) {

				if (salvarEditMembro.success === true) {

					Swal.fire({
						position: 'bottom-end',
						icon: 'success',
						title: salvarEditMembro.messages,
						showConfirmButton: false,
						timer: 1500
					}).then(function() {
						$('#data_tableMembros').DataTable().ajax.reload(null, false).draw(false);

					})

				}

				if (salvarEditMembro.success === false) {
					Swal.fire({
						position: 'bottom-end',
						icon: 'error',
						title: salvarEditMembro.messages,
						showConfirmButton: false,
						timer: 1500
					})
				}



			}
		})

	}

	function adicionarMembroAgora() {

		$('#addMembroModal').modal('hide');

		var form = $('#addMembroForm');
		$(".text-danger").remove();
		$.ajax({
			url: '<?php echo base_url('especialidades/addMembro') ?>',
			type: 'post',
			data: form.serialize(),
			dataType: 'json',
			success: function(responseAddMembro) {

				if (responseAddMembro.success === true) {

					Swal.fire({
						position: 'bottom-end',
						icon: 'success',
						title: responseAddMembro.messages,
						showConfirmButton: false,
						timer: 1500
					}).then(function() {
						$('#data_tableMembros').DataTable().ajax.reload(null, false).draw(false);

					})

				}

				if (responseAddMembro.success === false) {
					Swal.fire({
						position: 'bottom-end',
						icon: 'error',
						title: responseAddMembro.messages,
						showConfirmButton: false,
						timer: 1500
					})
				}



			}
		})

	}

	function AddMembroModal(codEspecialidade) {
		$("#addMembroForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#addMembroModal').modal('show');




		$.ajax({
			url: '<?php echo base_url('especialidades/listaDropDownPessoas') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(pessoasAdd) {

				$("#codPessoaAdd").select2({
					data: pessoasAdd,
				})

				$('#codPessoaAdd').val(null); // Select the option with a value of '1'
				$('#codPessoaAdd').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})

		$.ajax({
			url: '<?php echo base_url('especialidades/listaDropDownEstadosFederacao') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(estadoFederacaoAdd) {

				$("#codEstadoFederacaoAdd").select2({
					data: estadoFederacaoAdd,
				})

				$('#codEstadoFederacaoAdd').val(null); // Select the option with a value of '1'
				$('#codEstadoFederacaoAdd').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})


	}


	function editarMensagem(codEspecialidade) {
		$.ajax({
			url: '<?php echo base_url($controller . '/getOne') ?>',
			type: 'post',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
				codEspecialidade: codEspecialidade
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#mensagem-form")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#mensagem-modal').modal('show');
				$("#mensagem-form #descricaoEspecialidadeEdit").val(response.descricaoEspecialidade);
				$("#mensagem-form #codEspecialidadeEdit").val(response.codEspecialidade);
				$("#mensagem-form #mensagemFalhaMarcacao").val(response.mensagemFalhaMarcacao);
				$("#mensagem-form #mensagemSucessoMarcacao").val(response.mensagemSucessoMarcacao);


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
						var form = $('#mensagem-form');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url($controller . '/editMensagens') ?>',
							type: 'post',
							data: form.serialize(),
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
										$('#data_table').DataTable().ajax.reload(null, false).draw(false);
										$('#mensagem-modal').modal('hide');
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
								$('#mensagem-form-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#mensagem-form').validate();

			}
		});
	}


	function editarMensagemEncaminhamento(codEspecialidade) {
		$.ajax({
			url: '<?php echo base_url($controller . '/getOne') ?>',
			type: 'post',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
				codEspecialidade: codEspecialidade
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#mensagemEncaminhamento-form")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#mensagemEncaminhamento-modal').modal('show');
				$("#mensagemEncaminhamento-form #descricaoEspecialidadeEdit").val(response.descricaoEspecialidade);
				$("#mensagemEncaminhamento-form #codEspecialidadeEdit").val(response.codEspecialidade);
				$("#mensagemEncaminhamento-form #mensagemExigirIndicacao").val(response.mensagemExigirIndicacao);


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
						var form = $('#mensagemEncaminhamento-form');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url($controller . '/editMensagensEncaminhamento') ?>',
							type: 'post',
							data: form.serialize(),
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
										$('#data_tableexigirIndicacao').DataTable().ajax.reload(null, false).draw(false);
										$('#mensagemEncaminhamento-modal').modal('hide');
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
								$('#mensagem-form-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#mensagemEncaminhamento-form').validate();

			}
		});
	}

	function editespecialidadesMembro(codEspecialidadeMembro) {


		$.ajax({
			url: '<?php echo base_url('especialidades/pegaMembro') ?>',
			type: 'post',
			data: {
				codEspecialidadeMembro: codEspecialidadeMembro,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(responseEditMembro) {


				// reset the form 

				$("#editMembroForm")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#editMembroModal').modal('show');



				$("#editMembroForm #codEspecialidadeMembroEdit").val(responseEditMembro.codEspecialidadeMembro);
				$("#editMembroForm #codPessoaEdit").val(responseEditMembro.codPessoa);
				$("#editMembroForm #codEspecialidadeEditMembro").val(responseEditMembro.codEspecialidade);
				$("#editMembroForm #numeroInscricaoEdit").val(responseEditMembro.numeroInscricao);
				$("#editMembroForm #numeroSireEdit").val(responseEditMembro.numeroSire);
				$("#editMembroForm #observacoesEdit").val(responseEditMembro.observacoes);


				if (responseEditMembro.atende == '1') {
					document.getElementById("atendeEdit").checked = true;
				}


				document.getElementById('nomeExibicaoEspecialista').innerHTML =
					'<span style="font-size:20px;font-weight: bold;">' + responseEditMembro.nomeExibicao + '</span>';


				$.ajax({
					url: '<?php echo base_url('especialidades/listaDropDownEstadosFederacao') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(estadoFederacaoEdit) {

						$("#codEstadoFederacaoEdit").select2({
							data: estadoFederacaoEdit,
						})

						$('#codEstadoFederacaoEdit').val(responseEditMembro.codEstadoFederacao); // Select the option with a value of '1'
						$('#codEstadoFederacaoEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

					}
				})








			}
		});
	}


	function removeespecialidadesMembro(codEspecialidadeMembro) {

		Swal.fire({
			title: 'Você tem certeza que deseja remover o membro desta especialidade?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {

			if (result.value) {
				$.ajax({
					url: '<?php echo base_url('especialidades/removeMembro') ?>',
					type: 'post',
					data: {
						codEspecialidadeMembro: codEspecialidadeMembro,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),

					},
					dataType: 'json',
					success: function(responseRemoveMembro) {

						if (responseRemoveMembro.success === true) {
							Swal.fire({
								position: 'bottom-end',
								icon: 'success',
								title: responseRemoveMembro.messages,
								showConfirmButton: false,
								timer: 1500
							}).then(function() {
								$('#data_tableMembros').DataTable().ajax.reload(null, false).draw(false);
							})
						} else {
							Swal.fire({
								position: 'bottom-end',
								icon: 'error',
								title: responseRemoveMembro.messages,
								showConfirmButton: false,
								timer: 1500
							})


						}
					}
				});
			}
		})
	}



	function remove(codEspecialidade) {

		Swal.fire({
			position: 'bottom-end',
			icon: 'warning',
			title: "Funcionalidade removida. Contate o administrador do sistema!",
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
					url: '<?php echo base_url($controller . '/remove') ?>',
					type: 'post',
					data: {
						codEspecialidade: codEspecialidade
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
								$('#data_table').DataTable().ajax.reload(null, false).draw(false);
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
		*/

	}
</script>