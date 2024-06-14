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
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Lista de Exames</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary" onclick="add()" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">


					<table id="data_table" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>Codigo</th>
								<th>Descrição exameLista</th>
								<th>Tipo</th>
								<th>Conselho</th>
								<th>Nº Conselho</th>
								<th>Permite Reserva</th>
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
<div id="add-modal" class="modal fade" role="dialog" aria-hidden="true">

	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Lista Exames</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">


				<form id="add-form" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>add-modal" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codExameListaAdd" name="codExameLista" class="form-control" placeholder="Codigo" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="descricaoExameListaAdd"> Descrição exameLista: <span class="text-danger">*</span> </label>
								<input type="text" id="descricaoExameLista" name="descricaoExameLista" class="form-control" placeholder="Descrição exameLista" maxlength="60" required>
							</div>
						</div>


						<div class="col-md-2">
							<div class="form-group">

								<label for="tipoExameListaAdd"> Tipo ExameLista: <span class="text-danger">*</span> </label>
								<select required id="codTipoAdd" name="codTipo" class="form-control select2" tabindex="-1" aria-hidden="true">
									<option value=""></option>
									<option value="1">Médica</option>
									<option value="2">Administrativo</option>
								</select>
							</div>
						</div>


						<div class="col-md-2">
							<div class="form-group">

								<label for="codTipoAgendaAdd"> Tipo Agenda: <span class="text-danger">*</span> </label>
								<select required id="codTipoAgendaAdd" name="codTipoAgenda" class="form-control select2" tabindex="-1" aria-hidden="true">
									<option value=""></option>
									<option value="1">Consulta</option>
									<option value="2">Exame</option>
									<option value="3">Procedimento</option>
								</select>
							</div>
						</div>

					</div>
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="tipoExameListaAdd">Conselho: <span class="text-danger">*</span> </label>
								<select style="width:100%" id="codConselhoAdd" name="codConselho">
								</select>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label for="numeroConselhoAdd">Nº Conselho: <span class="text-danger">*</span> </label>
								<input type="text" id="numeroConselhoAdd" name="numeroConselho" class="form-control" placeholder="Nº Conselho" maxlength="12" required>
							</div>
						</div>

					</div>


					<div class="row">
						<div class="col-md-4">
							<div class="form-group">

								<label for="cadastroReservaAdd"> Permitir cadastro reserva: <span class="text-danger">*</span> </label>
								<select required id="cadastroReservaAdd" name="cadastroReserva" class="form-control" tabindex="-1" aria-hidden="true">
									<option value=""></option>
									<option value="0">Não</option>
									<option value="1">Sim</option>
								</select>
							</div>
						</div>
					</div>


					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-xs btn-primary">Adicionar</button>
							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>



<div id="addMembroModal" class="modal fade" role="dialog" aria-hidden="true">

	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Membro</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">


				<form id="addMembroForm" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>addMembroModal" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codExameListaMembroAdd" name="codExameLista" class="form-control" placeholder="Codigo" maxlength="11" required>
					</div>
					<div class="row">


						<div class="col-md-4">
							<div class="form-group">

								<label for="codPessoaAdd"> Especialista: <span class="text-danger">*</span> </label>
								<select id="codPessoaAdd" name="codPessoa" class="form-control select2" tabindex="-1" aria-hidden="true">
									<option value=""></option>
								</select>
							</div>
						</div>




						<div class="col-md-4">
							<div class="form-group">
								<label for="numeroInscricaoAdd">Nº Inscrição Conselho: <span class="text-danger">*</span> </label>
								<input type="text" id="numeroInscricaoAdd" name="numeroInscricao" class="form-control" placeholder="Nº Inscrição no conselho" maxlength="20" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">

								<label for="codEstadoFederacaoAdd"> Federação da Inscrição: <span class="text-danger">*</span> </label>
								<select id="codEstadoFederacaoAdd" name="codEstadoFederacao" class="form-control select2" tabindex="-1" aria-hidden="true">
									<option value=""></option>
								</select>
							</div>
						</div>


					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="numeroSireAdd">Nº Inscrição Sire: <span class="text-danger">*</span> </label>
								<input type="text" id="numeroSireAdd" name="numeroSire" class="form-control" placeholder="Nº Inscrição no Sire" maxlength="20" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="observacoesAdd">Observações <span class="text-danger">*</span> </label>
								<textarea id="observacoesAdd" name="observacoes" class="form-control" rows="4" cols="50" maxlength="500"></textarea>
							</div>
						</div>




					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="checkbox"> Atende</label>

								<div class="icheck-primary d-inline">
									<style>
										input[type=checkbox] {
											transform: scale(1.8);
										}
									</style>
									<input style="margin-left:5px;" id="atendeAdd" name="atende" type="checkbox">
								</div>
							</div>
						</div>
					</div>


					<div class="form-group text-center">
						<div class="btn-group">
							<button type="button" class="btn btn-xs btn-primary" onclick="adicionarMembroAgora()" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>



<div id="editMembroModal" class="modal fade" role="dialog" aria-hidden="true">

	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Alterar Membro</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">


				<form id="editMembroForm" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>editMembroModal" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codExameListaEditMembro" name="codExameLista" class="form-control" placeholder="Codigo" maxlength="11" required>
						<input type="hidden" id="codPessoaEdit" name="codPessoa" class="form-control" placeholder="Codigo" maxlength="11" required>
						<input type="hidden" id="codExameListaMembroEdit" name="codExameListaMembro" class="form-control" placeholder="Codigo" maxlength="11" required>
					</div>
					<div class="row">


						<div class="col-md-4">
							<div class="form-group">

								<label for="codPessoaEdit"> Especialista: <span class="text-danger">*</span> </label>
								<div id="nomeExibicaoEspecialista">
								</div>
							</div>
						</div>




						<div class="col-md-4">
							<div class="form-group">
								<label for="numeroInscricaoEdit">Nº Inscrição Conselho: <span class="text-danger">*</span> </label>
								<input type="text" id="numeroInscricaoEdit" name="numeroInscricao" class="form-control" placeholder="Nº Inscrição no conselho" maxlength="20" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">

								<label for="codEstadoFederacaoEdit"> Federação da Inscrição: <span class="text-danger">*</span> </label>
								<select id="codEstadoFederacaoEdit" name="codEstadoFederacao" class="form-control select2" tabindex="-1" aria-hidden="true">
									<option value=""></option>
								</select>
							</div>
						</div>


					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="numeroSireEdit">Nº Inscrição Sire: <span class="text-danger">*</span> </label>
								<input type="text" id="numeroSireEdit" name="numeroSire" class="form-control" placeholder="Nº Inscrição no Sire" maxlength="20">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="observacoesEdit">Observações <span class="text-danger">*</span> </label>
								<textarea id="observacoesEdit" name="observacoes" class="form-control" rows="4" cols="50" maxlength="500"></textarea>
							</div>
						</div>


					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="checkbox"> Atende no Ambulatório </label>

								<div class="icheck-primary d-inline">
									<style>
										input[type=checkbox] {
											transform: scale(1.8);
										}
									</style>
									<input style="margin-left:5px;" id="atendeEdit" name="atende" type="checkbox">
								</div>
							</div>
						</div>
					</div>



					<div class="form-group text-center">
						<div class="btn-group">
							<button type="button" class="btn btn-xs btn-primary" onclick="editarMembroAgora()" title="Adicionar">Salvar</button>
							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>



<!-- Add modal content -->
<div id="edit-modal" class="modal fade" role="dialog" aria-hidden="true">


	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 id="nomeExameListaInfo" class="modal-title text-white" id="info-header-modalLabel"></h4>
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
								<li class="nav-item">
									<a class="nav-link" id="membros-tab" data-toggle="pill" href="#membros" role="tab" aria-controls="membros" aria-selected="false">Membros</a>
								</li>

							</ul>
						</div>
						<div class="card-body">
							<div class="tab-content" id="custom-tabs-one-tabContent">
								<div class="tab-pane fade show active" id="principal" role="tabpanel" aria-labelledby="principal-tab">



									<form id="edit-form" class="pl-3 pr-3">
										<div class="row">
											<input type="hidden" id="<?php echo csrf_token() ?>edit-form" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

											<input type="hidden" id="codExameListaEdit" name="codExameLista" class="form-control" placeholder="Codigo" maxlength="11" required>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="descricaoExameLista"> Descrição exameLista: <span class="text-danger">*</span> </label>
													<input type="text" id="descricaoExameListaEdit" name="descricaoExameLista" class="form-control" placeholder="Descrição exameLista" maxlength="60" required>
												</div>
											</div>


											<div class="col-md-3">
												<div class="form-group">

													<label for="tipoExameLista"> Tipo ExameLista: <span class="text-danger">*</span> </label>
													<select required id="codTipoEdit" name="codTipo" class="form-control" tabindex="-1" aria-hidden="true">

														<option value=""></option>
														<option value="1">Médica</option>
														<option value="2">Administrativo</option>
													</select>
												</div>
											</div>


											<div class="col-md-2">
												<div class="form-group">

													<label for="codTipoAgendaEdit"> Tipo ExameLista: <span class="text-danger">*</span> </label>
													<select required id="codTipoAgendaEdit" name="codTipoAgenda" class="form-control" tabindex="-1" aria-hidden="true">
														<option value=""></option>
														<option value="1">Consulta</option>
														<option value="2">Exame</option>
														<option value="3">Procedimento</option>
													</select>
												</div>
											</div>

										</div>

										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="numeroConselhoEdit">Nº Conselho: <span class="text-danger">*</span> </label>
													<input type="text" id="numeroConselhoEdit" name="numeroConselho" class="form-control" placeholder="Nº Conselho" maxlength="20" required>
												</div>
											</div>

											<div class="col-md-3">
												<div class="form-group">
													<label for="tipoExameLista">Conselho: <span class="text-danger">*</span> </label>
													<select id="codConselhoEdit" name="codConselho" class="form-control select2" tabindex="-1" aria-hidden="true">
													</select>
												</div>
											</div>

										</div>


										<div class="row">
											<div class="col-md-4">
												<div class="form-group">

													<label for="cadastroReservaEdit"> Permitir cadastro reserva: <span class="text-danger">*</span> </label>
													<select required id="cadastroReservaEdit" name="cadastroReserva" class="form-control" tabindex="-1" aria-hidden="true">
														<option value=""></option>
														<option value="0">Não</option>
														<option value="1">Sim</option>
													</select>
												</div>
											</div>
										</div>



										<div class="form-group text-center">
											<div class="btn-group">
												<button type="submit" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="edit-form-btn">Salvar</button>
												<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
											</div>
										</div>
									</form>
								</div>
								<div class="tab-pane fade" id="membros" role="tabpanel" aria-labelledby="membros-tab">

									<button style="width:200px" type="button" class="btn btn-block btn-primary  btn-xs" onclick="AddMembroModal()" title="Incluir"> <i class="fa fa-plus"></i> Adicionar</button>

									<table id="data_tableMembros" class="table table-striped table-hover table-sm">
										<thead>
											<tr>
												<th>Codigo</th>
												<th>Especialista</th>
												<th>Nº Inscrição conselho </th>
												<th>UF</th>
												<th>Nº Sire</th>
												<th>Observações</th>
												<th>Atende?</th>
												<th></th>
											</tr>
										</thead>
									</table>


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
				"url": '<?php echo base_url($controller . '/getAll') ?>',
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
			url: '<?php echo base_url('agendamentosExamesLista/listaDropDownConselhos') ?>',
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



	function editarMembroAgora() {

		$('#editMembroModal').modal('hide');

		var form = $('#editMembroForm');
		$(".text-danger").remove();
		$.ajax({
			url: '<?php echo base_url('agendamentosExamesLista/editMembro') ?>',
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
			url: '<?php echo base_url('agendamentosExamesLista/addMembro') ?>',
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

	function AddMembroModal(codExameLista) {
		$("#addMembroForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#addMembroModal').modal('show');




		$.ajax({
			url: '<?php echo base_url('agendamentosExamesLista/listaDropDownPessoas') ?>',
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
			url: '<?php echo base_url('agendamentosExamesLista/listaDropDownEstadosFederacao') ?>',
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


	function edit(codExameLista) {
		$.ajax({
			url: '<?php echo base_url($controller . '/getOne') ?>',
			type: 'post',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
				codExameLista: codExameLista
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#edit-form")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#edit-modal').modal('show');
				$("#addMembroForm #codExameListaMembroAdd").val(response.codExameLista);

				$("#edit-form #codExameListaEdit").val(response.codExameLista);
				$("#edit-form #descricaoExameListaEdit").val(response.descricaoExameLista);
				$("#edit-form #numeroConselhoEdit").val(response.numeroConselho);
				$("#edit-form #codTipoEdit").val(response.codTipo);
				$("#edit-form #codTipoAgendaEdit").val(response.codTipoAgenda);
				$("#edit-form #cadastroReservaEdit").val(response.cadastroReserva);



				$('#nomeExameListaInfo').text(response.descricaoExameLista);


				$('#data_tableMembros').DataTable({
					"bDestroy": true,
					"paging": true,
					"lengthChange": false,
					"searching": true,
					"ordering": true,
					"info": true,
					"autoWidth": false,
					"responsive": true,
					"ajax": {
						"url": '<?php echo base_url('agendamentosExamesLista/pegaMembros') ?>',
						"type": "POST",
						"dataType": "json",
						async: "true",
						data: {
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
							codExameLista: codExameLista
						}
					}
				});




				$.ajax({
					url: '<?php echo base_url('agendamentosExamesLista/listaDropDownConselhos') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(conselhoEdit) {

						$("#codConselhoEdit").select2({
							data: conselhoEdit,
						})

						$('#codConselhoEdit').val(response.codConselho); // Select the option with a value of '1'
						$('#codConselhoEdit').trigger('change');
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
						var form = $('#edit-form');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url($controller . '/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#edit-form-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
										$('#edit-modal').modal('hide');
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
								$('#edit-form-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#edit-form').validate();

			}
		});
	}


	function editagendamentosExamesListaMembro(codExameListaMembro) {


		$.ajax({
			url: '<?php echo base_url('agendamentosExamesLista/pegaMembro') ?>',
			type: 'post',
			data: {
				codExameListaMembro: codExameListaMembro,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(responseEditMembro) {


				// reset the form 

				$("#editMembroForm")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#editMembroModal').modal('show');



				$("#editMembroForm #codExameListaMembroEdit").val(responseEditMembro.codExameListaMembro);
				$("#editMembroForm #codPessoaEdit").val(responseEditMembro.codPessoa);
				$("#editMembroForm #codExameListaEditMembro").val(responseEditMembro.codExameLista);
				$("#editMembroForm #numeroInscricaoEdit").val(responseEditMembro.numeroInscricao);
				$("#editMembroForm #numeroSireEdit").val(responseEditMembro.numeroSire);
				$("#editMembroForm #observacoesEdit").val(responseEditMembro.observacoes);


				if (responseEditMembro.atende == '1') {
					document.getElementById("atendeEdit").checked = true;
				}


				document.getElementById('nomeExibicaoEspecialista').innerHTML =
					'<span style="font-size:20px;font-weight: bold;">' + responseEditMembro.nomeExibicao + '</span>';


				$.ajax({
					url: '<?php echo base_url('agendamentosExamesLista/listaDropDownEstadosFederacao') ?>',
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


	function removeagendamentosExamesListaMembro(codExameListaMembro) {

		Swal.fire({
			title: 'Você tem certeza que deseja remover o membro desta exameLista?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {

			if (result.value) {
				$.ajax({
					url: '<?php echo base_url('agendamentosExamesLista/removeMembro') ?>',
					type: 'post',
					data: {
						codExameListaMembro: codExameListaMembro,
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



	function remove(codExameLista) {

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
						codExameLista: codExameLista
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