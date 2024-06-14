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


<style>
	.modal {
		overflow: auto !important;
	}

	#minhaFoto {
		width: 160px;
		height: 125px;
		border: 1px solid black;
	}

	#fotoPerfilCadastro {
		width: 160px;
		height: 125px;
		border: 1px solid black;
	}

	.select2-container {
		z-index: 100000;
	}


	.swal2-container {
		z-index: 9999999;
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
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Reservas</h3>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">

					<div id="demandaHtml">
					</div>


					<div class="row">
						<div class="col-12 col-sm-12">
							<div class="card card-primary card-tabs">
								<div class="card-header p-0 pt-1">
									<ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Pendentes<span style="margin-left: 10px;" id="qtdEmPendente" class="right badge badge-warning"></span></a>
										</li>
										<li class="nav-item">
											<a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Resolvidos<span style="margin-left: 10px;" id="qtdEmResolvido" class="right badge badge-warning"></span></a>
										</li>
									</ul>
								</div>
								<div class="card-body">
									<div class="tab-content" id="custom-tabs-one-tabContent">
										<div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
											<table id="data_tableagendamentosReservasPendentes" class="table table-striped table-hover table-sm">
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
										<div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
											<table id="data_tableagendamentosReservasResolvidos" class="table table-striped table-hover table-sm">
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
									</div>
								</div>
								<!-- /.card -->
							</div>
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





<!-- Add modal content -->
<div id="agendamentosReservasEditModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Reservas</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div style="font-size:16px" class="modal-body">


				<div class="row">
					<div class="col-md-7">


						<form id="agendamentosReservasEditForm" class="pl-3 pr-3">
							<div class="row">
								<input type="hidden" id="<?php echo csrf_token() ?>agendamentosReservasEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

								<input type="hidden" id="codAgendamentoReserva" name="codAgendamentoReserva" class="form-control" placeholder="CodAgendamentoReserva" maxlength="11" required>
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
								<div class="col-md-12">
									<div class="form-group">
										<label for="contatosInfo"> Contados: </label>
										<span id="contatosInfo"></span><span style="margin-left:10px" id="idadeInfo" class="right badge badge-danger"></span>
									</div>
								</div>

							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="codEspecialidade"> Especialidade: </label>
										<div id="nomeEspecialidadeInfo"></div>

									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="codEspecialista"> Especialista: </label>
										<div id="nomeEspecialistaInfo"></div>
									</div>
								</div>
							</div>


							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="codStatus"> Status: </label>
										<span id="descricaoStatusReservaInfo"></span>

									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="dataSolicitacaoInfo"> Data Solicitação: </label>
										<div id="dataSolicitacaoInfo"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="DiaSemana"> Dias de Preferência :</label>
										<div id="diasPreferenciaInfo"></div>
									</div>
								</div>
								<div class="col-md-6">
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

					<div class="col-md-5">


						<div class="card card-secondary">

							<div class="card-header">
								<h3 class="card-title">COMENTÁRIOS</h3>

								<div class="card-tools">
									<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
									</button>
								</div>
							</div>
							<div class="card-body">

								<div class="row justify-content-end">

									<div class="col-md-6">
										<button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addcomentariosReservas()" title="Adicionar">Adicionar Comentário</button>
									</div>
									<div class="col-md-4">
										<button type="button" class="btn btn-block btn-danger" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="encerrarCaso()" title="Adicionar">Encerrar caso</button>
									</div>
								</div>

								<div style="margin-top:10px" class="row">
									<div class="col-md-12">
										<div id="dadosComentarios" class="timeline">

										</div>
									</div>

								</div>


							</div>
						</div>

					</div>
				</div>

			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /.content -->




<div id="comentariosReservasAddModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar comentarios das Reservas</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="comentariosReservasAddForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>comentariosReservasAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">


					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="comentario"> Comentário: <span class="text-danger">*</span> </label>
								<textarea cols="40" rows="5" id="comentarioAdd" name="comentario" class="form-control" placeholder="Comentario" required></textarea>
							</div>
						</div>
					</div>
					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="comentariosReservasAddForm-btn">Adicionar</button>
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
	codPacienteTmp = 0;
	codAgendamentoReservaTmp = 0;
	$(document).on('show.bs.modal', '.modal', function() {
		var zIndex = 1040 + (10 * $('.modal:visible').length);
		$(this).css('z-index', zIndex);
		setTimeout(function() {
			$('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
		}, 0);
	});

	$(function() {


		qtdEmPendente = 0;
		$('#data_tableagendamentosReservasPendentes').DataTable({
			"paging": true,
			"deferRender": true,
			"lengthChange": false,
			"pageLength": 100,
			"searching": true,
			"ordering": false,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('agendamentosReservas/getAllPendentes') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			},
			"drawCallback": function(settings, json) {
				var api = this.api();
				qtdEmPendente = api.rows().count();

				document.getElementById("qtdEmPendente").innerHTML = qtdEmPendente
			}
		});

		qtdEmResolvido = 0;
		$('#data_tableagendamentosReservasResolvidos').DataTable({
			"paging": true,
			"deferRender": true,
			"pageLength": 100,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('agendamentosReservas/getAllResolvidos') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			},
			"drawCallback": function(settings, json) {
				var api = this.api();
				qtdEmResolvido = api.rows().count();

				document.getElementById("qtdEmResolvido").innerHTML = qtdEmResolvido


				$.ajax({
					url: '<?php echo base_url('agendamentosReservas/demandaReprimidaReserva') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(reservasPendentes) {

						document.getElementById('demandaHtml').innerHTML = reservasPendentes.html;

					}
				})



			}
		});
	});



	function addagendamentosReservas() {
		// reset the form 
		$("#agendamentosReservasAddForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#agendamentosReservasAddModal').modal('show');
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

				var form = $('#agendamentosReservasAddForm');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('agendamentosReservas/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#agendamentosReservasAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function(response) {

						if (response.success === true) {
							$('#agendamentosReservasAddModal').modal('hide');

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
								$('#data_tableagendamentosReservasPendentes').DataTable().ajax.reload(null, false).draw(false);
								$('#data_tableagendamentosReservasResolvidos').DataTable().ajax.reload(null, false).draw(false);
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
						$('#agendamentosReservasAddForm-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#agendamentosReservasAddForm').validate();
	}



	function escolhaPaciente(codAgendamento) {
		// reset the form 

		$(".form-control").removeClass('is-invalid').removeClass('is-valid');

		var codPaciente = $("#agendamentosReservasEditForm #codPaciente").val();

		//UPDATE PARA RESERVAR POR 1 MINUTO O SLOT E EVITAR CONFLITOS
		$.ajax({
			url: '<?php echo base_url('agendamentos/reservaUmMinuto') ?>',
			type: 'post',
			data: {
				codAgendamento: codAgendamento,
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
							url: '<?php echo base_url('agendamentos/marcarPaciente') ?>',
							type: 'post',
							data: {
								codPacienteMarcacao: codPaciente,
								codAgendamento: codAgendamento,
								csrf_sandra: $("#csrf_sandraPrincipal").val(),
							},
							dataType: 'json',
							success: function(marcacaoPaciente) {

								$('#agendamentosReservasEditModal').modal('hide');

								if (marcacaoPaciente.success === true) {


									$('#data_tableagendamentosReservasPendentes').DataTable().ajax.reload(null, false).draw(false);
									$('#data_tableagendamentosReservasResolvidos').DataTable().ajax.reload(null, false).draw(false);

									//comprovante(marcacaoPaciente.codAgendamento);


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




		var form = $('#agendamentosReservasEditForm');
		$.ajax({
			url: '<?php echo base_url('agendamentos/filtrarVagas') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(filtrar) {

				if (filtrar.success === true) {



					$.ajax({
						url: '<?php echo base_url('agendamentos/agendamentosPorEspecialidade') ?>',
						type: 'get',
						dataType: 'json',
						data: {
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},
						success: function(responseAgendamentos) {
							swal.close();

							if (responseAgendamentos.success === true) {

								document.getElementById('slotsLivres').innerHTML = responseAgendamentos.slotsLivres;
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



	function editagendamentosReservas(codAgendamentoReserva) {

		$.ajax({
			url: '<?php echo base_url('agendamentosReservas/getOne') ?>',
			type: 'post',
			data: {
				codAgendamentoReserva: codAgendamentoReserva,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#agendamentosReservasEditForm")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#agendamentosReservasEditModal').modal('show');

				document.getElementById('slotsLivres').innerHTML = '';


				$("#agendamentosReservasEditForm #codAgendamentoReserva").val(response.codAgendamentoReserva);
				$("#agendamentosReservasEditForm #codOrganizacao").val(response.codOrganizacao);
				$("#agendamentosReservasEditForm #codPaciente").val(response.codPaciente);
				$("#agendamentosReservasEditForm #codEspecialidade").val(response.codEspecialidade);
				$("#agendamentosReservasEditForm #codEspecialista").val(response.codEspecialista);
				$("#agendamentosReservasEditForm #codStatus").val(response.codStatus);
				$("#agendamentosReservasEditForm #dataCriacao").val(response.dataCriacao);
				$("#agendamentosReservasEditForm #dataAtualizacao").val(response.dataAtualizacao);
				$("#agendamentosReservasEditForm #preferenciaDia").val(response.preferenciaDia);
				$("#agendamentosReservasEditForm #preferenciaHora").val(response.preferenciaHora);
				$("#agendamentosReservasEditForm #codAutor").val(response.codAutor);
				$("#agendamentosReservasEditForm #protocolo").val(response.protocolo);

				codPacienteTmp = response.codPaciente;
				codAgendamentoReservaTmp = response.codAgendamentoReserva;


				document.getElementById("dadosComentarios").innerHTML = response.html;




				if (response.codStatus !== '2') {

					document.getElementById('btn-buscarVagas').innerHTML = '<button type="button" onclick="buscarVagas()" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="agendamentosReservasEditForm-btn">BUSCAR VAGAS</button>';

				} else {
					document.getElementById('btn-buscarVagas').innerHTML = '';
				}

				document.getElementById('nomePacienteInfo').innerHTML = response.nomeExibicao;

				document.getElementById('contatosInfo').innerHTML = response.contatos;



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
						var form = $('#agendamentosReservasEditForm');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('agendamentosReservas/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#agendamentosReservasEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
							},
							success: function(response) {

								if (response.success === true) {

									$('#agendamentosReservasEditModal').modal('hide');


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
										$('#data_tableagendamentosReservasPendentes').DataTable().ajax.reload(null, false).draw(false);
										$('#data_tableagendamentosReservasResolvidos').DataTable().ajax.reload(null, false).draw(false);
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
								$('#agendamentosReservasEditForm-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#agendamentosReservasEditForm').validate();

			}
		});
	}

	function encerrarCaso() {

		Swal.fire({
			title: 'Você tem certeza que deseja encerrar tentativas de marcar o Paciente?',
			text: "Após esta ação o paciente terá que entrar no cadastro reserva novamente",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar',
			input: 'textarea',
			inputLabel: 'Informe o motivo(Obrigatório)',
			inputValidator: (value) => {
				if (!value) {
					return 'É necessário informar o motivo'
				} else {
					$.ajax({
						url: '<?php echo base_url('agendamentosReservas/encerrarCaso') ?>',
						type: 'post',
						data: {
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
							codPaciente: codPacienteTmp,
							codAgendamentoReserva: codAgendamentoReservaTmp,
							comentario: value,
						},
						dataType: 'json',
						success: function(response) {

							if (response.success === true) {

								document.getElementById('descricaoStatusReservaInfo').innerHTML = '<span style="margin-left:10px" id="statusInfo" class="right badge badge-success">Resolvido</span>';

								document.getElementById("dadosComentarios").innerHTML = response.html;


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

									$('#data_tableagendamentosReservasPendentes').DataTable().ajax.reload(null, false).draw(false);
									$('#data_tableagendamentosReservasResolvidos').DataTable().ajax.reload(null, false).draw(false);

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

			}
		})


	}

	function addcomentariosReservas() {
		// reset the form 
		$("#comentariosReservasAddForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#comentariosReservasAddModal').modal('show');
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

				var form = $('#comentariosReservasAddForm');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('agendamentosReservas/addComentario') ?>',
					type: 'post',
					data: {
						comentario: $('#comentariosReservasAddForm #comentarioAdd').val(),
						codPaciente: codPacienteTmp,
						codAgendamentoReserva: codAgendamentoReservaTmp,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),

					},
					dataType: 'json',
					success: function(response) {

						if (response.success === true) {



							document.getElementById("dadosComentarios").innerHTML = response.html;



							$('#comentariosReservasAddModal').modal('hide');

							var Toast = Swal.mixin({
								toast: true,
								position: 'bottom-end',
								showConfirmButton: false,
								timer: 2000
							});
							Toast.fire({
								icon: 'success',
								title: response.messages
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
						$('#comentariosReservasAddForm-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#comentariosReservasAddForm').validate();
	}


	function removeagendamentosReservas(codAgendamentoReserva) {
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
					url: '<?php echo base_url('agendamentosReservas/remove') ?>',
					type: 'post',
					data: {
						codAgendamentoReserva: codAgendamentoReserva,
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
								$('#data_tableagendamentosReservasPendentes').DataTable().ajax.reload(null, false).draw(false);
								$('#data_tableagendamentosReservasResolvidos').DataTable().ajax.reload(null, false).draw(false);
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