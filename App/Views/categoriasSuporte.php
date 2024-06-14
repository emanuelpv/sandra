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
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Categorias de Suporte</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary" onclick="addcategoriasSuporte()" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<table id="data_tablecategoriasSuporte" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>Código</th>
								<th>Descrição da Categoria</th>
								<th>Equipe Responsável</th>

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
<div id="add-modalcategoriasSuporte" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Categorias de Suporte</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="add-formcategoriasSuporte" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>add-modalcategoriasSuporte" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codCategoriaSuporte" name="codCategoriaSuporte" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="descricaoCategoriaSuporte"> Descrição da Categoria: <span class="text-danger">*</span> </label>
								<input type="text" id="descricaoCategoriaSuporte" name="descricaoCategoriaSuporte" class="form-control" placeholder="Descrição da Categoria" maxlength="50" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codEquipeResponsavel"> Equipe Responsável: <span class="text-danger">*</span> </label>
								<select id="codEquipeResponsavelAdd" name="codEquipeResponsavel" class="custom-select" required>
									<option value=""></option>
								</select>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label for="sla"> SLA:</label>
								<input type="number" id="sla" name="sla" class="form-control" placeholder="Ex: 30 Minutos" maxlength="3">
							</div>
						</div>
						<span class="form-group">
							<label for="medidaSLA"> Und de Medida do SLA:</label>
							<select id="medidaSLA" name="medidaSLA" class="custom-select">
								<option value=""></option>
								<option value="minutes">MINUTO(S)</option>
								<option value="hours">HORA(S)</option>
								<option value="days">DIA(S)</option>
								<option value="weeks">SEMANA(S)</option>
								<option value="months">MES(ES)</option>
								<option value="years">ANO(S)</option>
							</select>
						</span>
					</div>

					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label for="slo"> SLO:</label>
								<input type="number" id="slo" name="slo" class="form-control" placeholder="Ex: 30 Minutos" maxlength="3">
							</div>
						</div>
						<span class="form-group">
							<label for="medidaSLO"> Und de Medida do SLO:</label>
							<select id="medidaSLO" name="medidaSLO" class="custom-select">
								<option value=""></option>
								<option value="minutes">MINUTO(S)</option>
								<option value="hours">HORA(S)</option>
								<option value="days">DIA(S)</option>
								<option value="weeks">SEMANA(S)</option>
								<option value="months">MES(ES)</option>
								<option value="years">ANO(S)</option>
							</select>
						</span>
					</div>


					<div class="row">
						<div class="form-group">
							<label for="sli"> SLI:</label>
							<select id="sli" name="sli" class="custom-select">
								<option value="">Escolha um indicador de alarme</option>
								<option value="0">Sem Indicador</option>
								<option value="10">10%</option>
								<option value="20">20%</option>
								<option value="30">30%</option>
								<option value="40">40%</option>
								<option value="50">50%</option>
								<option value="60">60%</option>
								<option value="70">70%</option>
								<option value="80">80%</option>
								<option value="90">90%</option>
								<option value="100">100%</option>
							</select>
						</div>

					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-xs btn-primary" id="add-formcategoriasSuporte-btn">Adicionar</button>
							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Add modal content -->
<div id="edit-modalcategoriasSuporte" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Categorias de Suporte</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="edit-formcategoriasSuporte" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>edit-modalcategoriasSuporte" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codCategoriaSuporte" name="codCategoriaSuporte" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="descricaoCategoriaSuporte"> Descrição da Categoria: <span class="text-danger">*</span> </label>
								<input type="text" id="descricaoCategoriaSuporte" name="descricaoCategoriaSuporte" class="form-control" placeholder="Descrição da Categoria" maxlength="50" required>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="codEquipeResponsavel"> Equipe Responsável: <span class="text-danger">*</span> </label>
								<select id="codEquipeResponsavelEdit" name="codEquipeResponsavel" class="custom-select" required>
								</select>
							</div>
						</div>

					</div>

					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label for="sla"> SLA:</label>
								<input type="number" id="slaEdit" name="sla" class="form-control" placeholder="Ex: 30 Minutos" maxlength="3">
							</div>
						</div>
						<span class="form-group">
							<label for="medidaSLA"> Und de Medida do SLA:</label>
							<select id="medidaSLAEdit" name="medidaSLA" class="custom-select">
								<option value=""></option>
								<option value="minutes">MINUTO(S)</option>
								<option value="hours">HORA(S)</option>
								<option value="days">DIA(S)</option>
								<option value="weeks">SEMANA(S)</option>
								<option value="months">MES(ES)</option>
								<option value="years">ANO(S)</option>
							</select>
						</span>
					</div>

					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label for="slo"> SLO:</label>
								<input type="number" id="sloEdit" name="slo" class="form-control" placeholder="Ex: 30 Minutos" maxlength="3">
							</div>
						</div>
						<span class="form-group">
							<label for="medidaSLO"> Und de Medida do SLO:</label>
							<select id="medidaSLOEdit" name="medidaSLO" class="custom-select">
								<option value=""></option>
								<option value="minutes">MINUTO(S)</option>
								<option value="hours">HORA(S)</option>
								<option value="days">DIA(S)</option>
								<option value="weeks">SEMANA(S)</option>
								<option value="months">MES(ES)</option>
								<option value="years">ANO(S)</option>
							</select>
						</span>
					</div>

					<div class="row">
						<div class="form-group">
							<label for="sli"> SLI:</label>
							<select id="sliEdit" name="sli" class="custom-select">
								<option value="">Escolha um indicador de alarme</option>
								<option value="0">Sem Indicador</option>
								<option value="10">10%</option>
								<option value="20">20%</option>
								<option value="30">30%</option>
								<option value="40">40%</option>
								<option value="50">50%</option>
								<option value="60">60%</option>
								<option value="70">70%</option>
								<option value="80">80%</option>
								<option value="90">90%</option>
								<option value="100">100%</option>
							</select>
						</div>

					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-xs btn-primary" id="edit-formcategoriasSuporte-btn">Salvar</button>
							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>

			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /.content -->
<?php
echo view('tema/rodape');
?>
<script>
	$(function() {
		$('#data_tablecategoriasSuporte').DataTable({
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('categoriasSuporte/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});
	});

	function addcategoriasSuporte() {
		// reset the form 
		$("#add-formcategoriasSuporte")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#add-modalcategoriasSuporte').modal('show');
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

				var form = $('#add-formcategoriasSuporte');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('categoriasSuporte/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#add-formcategoriasSuporte-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
								$('#data_tablecategoriasSuporte').DataTable().ajax.reload(null, false).draw(false);
								$('#add-modalcategoriasSuporte').modal('hide');
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
						$('#add-formcategoriasSuporte-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#add-formcategoriasSuporte').validate();

		$.ajax({
			url: '<?php echo base_url('EquipesSuporte/listaDropDown') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(equipeSuporteAdd) {
				$("#codEquipeResponsavelAdd").select2({
					data: equipeSuporteAdd,
				})

			}
		})



	}

	function editcategoriasSuporte(codCategoriaSuporte) {
		$.ajax({
			url: '<?php echo base_url('categoriasSuporte/getOne') ?>',
			type: 'post',
			data: {
				codCategoriaSuporte: codCategoriaSuporte,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#edit-formcategoriasSuporte")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#edit-modalcategoriasSuporte').modal('show');

				$("#edit-formcategoriasSuporte #codCategoriaSuporte").val(response.codCategoriaSuporte);
				$("#edit-formcategoriasSuporte #descricaoCategoriaSuporte").val(response.descricaoCategoriaSuporte);


				$("#edit-formcategoriasSuporte #slaEdit").val(response.sla);
				$("#edit-formcategoriasSuporte #medidaSLAEdit").val(response.medidaSLA);
				$("#edit-formcategoriasSuporte #sloEdit").val(response.slo);
				$("#edit-formcategoriasSuporte #medidaSLOEdit").val(response.medidaSLO);
				$("#edit-formcategoriasSuporte #sliEdit").val(response.sli);

				$.ajax({
					url: '<?php echo base_url('EquipesSuporte/listaDropDown') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(equipeSuporteEdit) {
						$("#codEquipeResponsavelEdit").select2({
							data: equipeSuporteEdit,
						})
						$('#codEquipeResponsavelEdit').val(response.codEquipeResponsavel); // Select the option with a value of '1'
						$('#codEquipeResponsavelEdit').trigger('change');
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
						var form = $('#edit-formcategoriasSuporte');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('categoriasSuporte/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#edit-formcategoriasSuporte-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
										$('#data_tablecategoriasSuporte').DataTable().ajax.reload(null, false).draw(false);
										$('#edit-modalcategoriasSuporte').modal('hide');
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
								$('#edit-formcategoriasSuporte-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#edit-formcategoriasSuporte').validate();

			}
		});
	}

	function removecategoriasSuporte(codCategoriaSuporte) {
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
					url: '<?php echo base_url('categoriasSuporte/remove') ?>',
					type: 'post',
					data: {
						codCategoriaSuporte: codCategoriaSuporte,
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
								$('#data_tablecategoriasSuporte').DataTable().ajax.reload(null, false).draw(false);
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