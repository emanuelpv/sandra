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
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Reservas</h3>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<table id="data_tableagendamentosExamesReservas" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>Protocolo</th>
								<th>Paciente</th>
								<th>Especialidade</th>
								<th>Especialista</th>
								<th>Status</th>
								<th>Data</th>
								<th>Dia Preferência</th>
								<th>Hora Preferência</th>
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

<!-- Add modal content -->
<div id="agendamentosExamesReservasEditModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Reservas</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div style="font-size:16px" class="modal-body">
				<form id="agendamentosExamesReservasEditForm" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>agendamentosExamesReservasEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codExameReserva" name="codExameReserva" class="form-control" placeholder="CodExameReserva" maxlength="11" required>
						<input type="hidden" id="codPaciente" name="codPaciente" class="form-control" placeholder="CodPaciente" maxlength="11" number="true" required>
						<input type="hidden" id="codEspecialidade" name="codEspecialidade" class="form-control" placeholder="CodEspecialidade" maxlength="11" number="true" required>

						<!-- ESTÁ SENDO DEFINIDO  codEspecialista == 0 NO FORME PARA FORÇAR TRAZER TODOS OS ESPECIALISTAS DISPONÍVEIS!-->
						<input type="hidden" id="codEspecialista" name="codEspecialista" value="0" class="form-control" placeholder="CodEspecialista" maxlength="11" number="true" required>
						<!-- ESTÁ SENDO DEFINIDO  codEspecialista == 0 NO FORME PARA FORÇAR TRAZER TODOS OS ESPECIALISTAS DISPONÍVEIS!-->

					</div>
					<div class="row">
						<div class="col-md-4">
							<span id="btn-buscarVagas"></span>

						</div>
					</div>
					<div style="margin-top:20px" class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="codPaciente"> Nome Paciente: </label>
								<span id="nomePacienteInfo"></span><span style="margin-left:10px" id="idadeInfo" class="right badge badge-danger"></span>
							</div>
						</div>

					</div>

					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label for="codEspecialidade"> Especialidade: </label>
								<div id="nomeEspecialidadeInfo"></div>

							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="codEspecialista"> Especialista: </label>
								<div id="nomeEspecialistaInfo"></div>
							</div>
						</div>

						<div class="col-md-2">
							<div class="form-group">
								<label for="codStatus"> Status: </label>
								<span id="descricaoStatusReservaInfo"></span>

							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="dataSolicitacaoInfo"> Data Solicitação: </label>
								<div id="dataSolicitacaoInfo"></div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label for="DiaSemana"> Dias de Preferência :</label>
								<div id="diasPreferenciaInfo"></div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="preferenciaHora"> Horário de Preferência: </label>
								<div id="horaPreferenciaInfo"></div>

							</div>
						</div>
					</div>



				</form>

				<div class="row">
					<div id="slotsLivres"> </div>

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
		$('#data_tableagendamentosExamesReservas').DataTable({
			"paging": true,
			"deferRender": true,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('agendamentosExamesReservas/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});
	});

	function addagendamentosExamesReservas() {
		// reset the form 
		$("#agendamentosExamesReservasAddForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#agendamentosExamesReservasAddModal').modal('show');
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

				var form = $('#agendamentosExamesReservasAddForm');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('agendamentosExamesReservas/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#agendamentosExamesReservasAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function(response) {

						if (response.success === true) {
							$('#agendamentosExamesReservasAddModal').modal('hide');

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
								$('#data_tableagendamentosExamesReservas').DataTable().ajax.reload(null, false).draw(false);
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
						$('#agendamentosExamesReservasAddForm-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#agendamentosExamesReservasAddForm').validate();
	}



	function escolhaPaciente(codExame) {
		// reset the form 

		$(".form-control").removeClass('is-invalid').removeClass('is-valid');

		var codPaciente = $("#agendamentosExamesReservasEditForm #codPaciente").val();

		//UPDATE PARA RESERVAR POR 1 MINUTO O SLOT E EVITAR CONFLITOS
		$.ajax({
			url: '<?php echo base_url('agendamentosExames/reservaUmMinuto') ?>',
			type: 'post',
			data: {
				codExame: codExame,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(reservaUmMinuto) {



				Swal.fire({
					title: 'Você tem certeza que deseja marcar neste horário ?',
					icon: 'info',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Confirmar',
					cancelButtonText: 'Cancelar'
				}).then((result) => {

					if (result.value) {
						$.ajax({
							url: '<?php echo base_url('agendamentosExames/marcarPaciente') ?>',
							type: 'post',
							data: {
								codPacienteMarcacao: codPaciente,
								codExame: codExame,
								csrf_sandra: $("#csrf_sandraPrincipal").val(),
							},
							dataType: 'json',
							success: function(marcacaoPaciente) {

								$('#agendamentosExamesReservasEditModal').modal('hide');

								if (marcacaoPaciente.success === true) {


									$('#data_tableagendamentosExamesReservas').DataTable().ajax.reload(null, false).draw(false);

									//comprovante(marcacaoPaciente.codExame);


									var Toast = Swal.mixin({
										toast: true,
										position: 'bottom-end',
										showConfirmButton: false,
										timer: 3000
									});
									Toast.fire({
										icon: 'success',
										title: marcacaoPaciente.messages
									})
								} else {
									var Toast = Swal.mixin({
										toast: true,
										position: 'bottom-end',
										showConfirmButton: false,
										timer: 5000
									});
									Toast.fire({
										icon: 'error',
										title: marcacaoPaciente.messages
									}).then(function() {

									})
								}
							}
						}).always(
							Swal.fire({
								title: 'Estamos processando sua requisição',
								html: 'Aguarde....',
								timerProgressBar: true,
								didOpen: () => {
									Swal.showLoading()


								}

							}))







					}
				})













			}
		})


	}



	function buscarVagas() {




		var form = $('#agendamentosExamesReservasEditForm');
		$.ajax({
			url: '<?php echo base_url('agendamentosExames/filtrarVagas') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(filtrar) {

				if (filtrar.success === true) {



					$.ajax({
						url: '<?php echo base_url('agendamentosExames/agendamentosExamesPorExameLista') ?>',
						type: 'post',
						dataType: 'json',
						data: {							
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},
						success: function(responseAgendamentosExames) {
							swal.close();

							if (responseAgendamentosExames.success === true) {

								document.getElementById('slotsLivres').innerHTML = responseAgendamentosExames.slotsLivres;



							}
						}
					}).always(
						Swal.fire({
							title: 'Estamos buscando possíveis vagas no sistema',
							html: 'Aguarde....',
							timerProgressBar: true,
							didOpen: () => {
								Swal.showLoading()


							}

						}))

				}
			}
		})
	}



	function editagendamentosExamesReservas(codExameReserva) {
		$.ajax({
			url: '<?php echo base_url('agendamentosExamesReservas/getOne') ?>',
			type: 'post',
			data: {
				codExameReserva: codExameReserva,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#agendamentosExamesReservasEditForm")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#agendamentosExamesReservasEditModal').modal('show');

				document.getElementById('slotsLivres').innerHTML = '';


				$("#agendamentosExamesReservasEditForm #codExameReserva").val(response.codExameReserva);
				$("#agendamentosExamesReservasEditForm #codOrganizacao").val(response.codOrganizacao);
				$("#agendamentosExamesReservasEditForm #codPaciente").val(response.codPaciente);
				$("#agendamentosExamesReservasEditForm #codEspecialidade").val(response.codEspecialidade);
				$("#agendamentosExamesReservasEditForm #codEspecialista").val(response.codEspecialista);
				$("#agendamentosExamesReservasEditForm #codStatus").val(response.codStatus);
				$("#agendamentosExamesReservasEditForm #dataCriacao").val(response.dataCriacao);
				$("#agendamentosExamesReservasEditForm #dataAtualizacao").val(response.dataAtualizacao);
				$("#agendamentosExamesReservasEditForm #preferenciaDia").val(response.preferenciaDia);
				$("#agendamentosExamesReservasEditForm #preferenciaHora").val(response.preferenciaHora);
				$("#agendamentosExamesReservasEditForm #codAutor").val(response.codAutor);
				$("#agendamentosExamesReservasEditForm #protocolo").val(response.protocolo);

				if (response.codStatus !== '2') {

					document.getElementById('btn-buscarVagas').innerHTML = '<button type="button" onclick="buscarVagas()" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="agendamentosExamesReservasEditForm-btn">BUSCAR VAGAS</button>';

				} else {
					document.getElementById('btn-buscarVagas').innerHTML = '';
				}

				document.getElementById('nomePacienteInfo').innerHTML = response.nomeExibicao;
				if (response.nomeEspecialista === undefined || response.nomeEspecialista === null || response.nomeEspecialista === "") {

					document.getElementById('nomeEspecialistaInfo').innerHTML = "Qualquer um";

				} else {
					document.getElementById('nomeEspecialistaInfo').innerHTML = response.nomeEspecialista;

				}
				document.getElementById('nomeEspecialidadeInfo').innerHTML = response.descricaoEspecialidade;
				document.getElementById('dataSolicitacaoInfo').innerHTML = response.dataSolicitacao;
				document.getElementById('idadeInfo').innerHTML = ' ' + response.idade + ' anos';

				//DEFAULT STATUS
				document.getElementById('descricaoStatusReservaInfo').innerHTML = '<span style="margin-left:10px" id="statusInfo" class="right badge badge-danger">' + response.descricaoStatus + '</span>';

				if (response.codStatus == '1') {
					document.getElementById('descricaoStatusReservaInfo').innerHTML = '<span style="margin-left:10px" id="statusInfo" class="right badge badge-warning">' + response.descricaoStatus + '</span>';
				}
				if (response.codStatus == '2') {
					document.getElementById('descricaoStatusReservaInfo').innerHTML = '<span style="margin-left:10px" id="statusInfo" class="right badge badge-success">' + response.descricaoStatus + '</span>';
				}



				diasPreferenciaInfo = "";
				if (response.segunda == 1) {
					diasPreferenciaInfo += 'Seg | ';
				}


				if (response.terca == 1) {
					diasPreferenciaInfo += 'Ter | ';
				}

				if (response.quarta == 1) {
					diasPreferenciaInfo += 'Qua | ';
				}

				if (response.quinta == 1) {
					diasPreferenciaInfo += 'Qui | ';
				}

				if (response.sexta == 1) {
					diasPreferenciaInfo += 'Sex | ';
				}

				if (response.sabado == 1) {
					diasPreferenciaInfo += 'Sab | ';
				}



				document.getElementById('diasPreferenciaInfo').innerHTML = diasPreferenciaInfo;


				if (response.preferenciaHora == 0) {
					horaPreferenciaInfo = 'Qualquer Hora';
				}
				if (response.preferenciaHora == 1) {
					horaPreferenciaInfo = 'Manhã';
				}
				if (response.preferenciaHora == 2) {
					horaPreferenciaInfo = 'Tarde';
				}

				document.getElementById('horaPreferenciaInfo').innerHTML = horaPreferenciaInfo;



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
						var form = $('#agendamentosExamesReservasEditForm');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('agendamentosExamesReservas/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#agendamentosExamesReservasEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
							},
							success: function(response) {

								if (response.success === true) {

									$('#agendamentosExamesReservasEditModal').modal('hide');


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
										$('#data_tableagendamentosExamesReservas').DataTable().ajax.reload(null, false).draw(false);
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
								$('#agendamentosExamesReservasEditForm-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#agendamentosExamesReservasEditForm').validate();

			}
		});
	}

	function removeagendamentosExamesReservas(codExameReserva) {
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
					url: '<?php echo base_url('agendamentosExamesReservas/remove') ?>',
					type: 'post',
					data: {
						codExameReserva: codExameReserva,
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
								$('#data_tableagendamentosExamesReservas').DataTable().ajax.reload(null, false).draw(false);
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