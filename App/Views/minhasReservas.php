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
					<table id="data_tableagendamentosReservas" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>Protocolo</th>
								<th>Especialidade</th>
								<th>Status</th>
								<th>Especialista</th>
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



<div id="comprovanteA4Modal" class="modal fade" role="dialog" aria-hidden="true">

	<div class="modal-dialog modal-xl">
		<div class="modal-content">

			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Comprovante</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<div style="margin-left:10px" id="areaImpressaoComprovanteA4">
					<div class="row">
						<div style="width:50% !important" class="col-sm-6 border">

							<div>
								<center><img alt="" style="text-align:center;width:60px;height:60px;" src="<?php echo base_url() . "/imagens/organizacoes/" . session()->logo ?>"></center>
							</div>
							<div style="text-align:center;font-weight: bold">
								<?php echo session()->descricaoOrganizacao; ?>



							</div>

							<div style="font-family: 'Arial';margin-top:20px;height: 80mm;">
								<div style="text-align:left;font-weight: bold;font-size:12px">USUÁRIO: <span id="nomeCompletoComprovanteA4"></span></div>
								<div style="text-align:left;font-weight: bold;font-size:12px">Nº PLANO: <span id="CODPLANOComprovanteA4"></span></div>
								<div style="text-align:left;font-weight: bold;font-size:12px">ESPECIALISTA: <span id="nomeEspecialidadeComprovanteA4"></span></div>
								<div style="text-align:left;font-size:12px">DIA: <span id="dataInicioComprovanteA4"></span></div>
								<div style="text-align:left;font-size:12px">LOCAL <span id="localComprovanteA4"></span></div>
								<div style="text-align:left;font-size:12px">Protocolo Nr: <span id="protocoloComprovanteA4"></span></div>
								<div style="text-align:left;font-size:12px"><b>Prontuário Nº: </b>:<span id="codProntuarioComprovanteA4"></span></div>
								<div style="margin-top:10px" class="d-flex justify-content-left" id="qrcodeComprovanteA4"></div>

							</div>


							<div style="margin-top:30px" class="row">
								<div><b>Marcado Por: </b>:<span id="autorMarcacaoComprovanteA4"></span></div>
							</div>


							<div class="row">
								<?php
								echo "Impresso por: " . session()->nomeExibicao . ' | CPF: ' . substr(session()->cpf,0,-6).'*****'  . " | IP:"  . session()->ip
								?>
							</div>

							<div class="row">
								<?php

								echo session()->cidade . '-' . session()->uf . ', ' . date('d', strtotime(date('Y-m-d'))) . ' de ' . nomeMesPorExtenso(date('m', strtotime(date('Y-m-d')))) . ' de ' . date('Y', strtotime(date('Y-m-d'))) . '.';

								?>
							</div>
						</div>
						<div style="width:50% !important" class="col-sm-6 border">

							<div style="margin-left:10px;margin-top:10px;font-family: 'Arial';margin-top:20px;text-align:left;font-weight: bold;font-size:12px">
								<div class="row">
									<b>Prezado usuário, leia atentamente as orientações a seguir:</b>
								</div>
								<div class="row">
									* Este é seu comprovante de marcação de consulta.
								</div>

								<div class="row">
									* Compareça no dia da consulta 30 minutos antes.
								</div>

								<div class="row">
									* Esta consulta só pode ser desmarcada até 24 horas antes. Para desmarcar utilize nossa plataforma online através do endereço <?php echo base_url() ?>, contate-nos através do telefone <?php echo session()->telefoneOrganizacao ?>
								</div>

								<div class="row">
									* Evite faltas, compareça à consulta.
								</div>
								<div class="row">
									* Evite bloqueio de marcações de consultas por motivo de faltas.
								</div>

								<div class="row">
									* Evite atrasos.
								</div>

							</div>

						</div>

					</div>






				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary" id="botaoImprimirComprovanteA4">Imprimir</button>
				<button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
			</div>
		</div>



	</div>
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
		$('#data_tableagendamentosReservas').DataTable({
			"paging": true,
			"deferRender": true,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('minhasReservas/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
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
					url: '<?php echo base_url('minhasReservas/add') ?>',
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
								$('#data_tableagendamentosReservas').DataTable().ajax.reload(null, false).draw(false);
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


									$('#data_tableagendamentosReservas').DataTable().ajax.reload(null, false).draw(false);

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
			url: '<?php echo base_url('minhasReservas/getOne') ?>',
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

				if (response.codStatus !== '2') {

					document.getElementById('btn-buscarVagas').innerHTML = '<button type="button" onclick="buscarVagas()" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="agendamentosReservasEditForm-btn">BUSCAR VAGAS</button>';

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
						var form = $('#agendamentosReservasEditForm');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('minhasReservas/edit') ?>',
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
										$('#data_tableagendamentosReservas').DataTable().ajax.reload(null, false).draw(false);
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




	function comprovanteA4(codAgendamento) {

		$('#comprovanteA4Modal').modal('show');
		$('#tipoImpressoraModal').modal('hide');

		document.getElementById("botaoImprimirComprovanteA4").onclick = function() {
			printElement(document.getElementById("areaImpressaoComprovanteA4"));

			window.print();
		}

		$.ajax({
			url: '<?php echo base_url('agendamentos/comprovante') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				codAgendamento: codAgendamento,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(agendamentoComprovante) {
				document.getElementById("nomeCompletoComprovanteA4").innerHTML = agendamentoComprovante.nomePaciente;
				document.getElementById("CODPLANOComprovanteA4").innerHTML = agendamentoComprovante.codPlano;
				document.getElementById("nomeEspecialidadeComprovanteA4").innerHTML = agendamentoComprovante.nomeEspecialista;
				document.getElementById("protocoloComprovanteA4").innerHTML = agendamentoComprovante.protocolo;
				document.getElementById("autorMarcacaoComprovanteA4").innerHTML = agendamentoComprovante.autorMarcacao;
				document.getElementById("codProntuarioComprovanteA4").innerHTML = agendamentoComprovante.codProntuario;



				document.getElementById("localComprovanteA4").innerHTML = agendamentoComprovante.local;
				document.getElementById("dataInicioComprovanteA4").innerHTML = agendamentoComprovante.dataInicio;
				var URLComprovante = '<?php echo base_url() . "/atendimentos/?codagendamento=" ?>' + agendamentoComprovante.codAgendamento + '&chechsum=' + agendamentoComprovante.valorChecksum;

				document.getElementById("qrcodeComprovanteA4").innerHTML = "";

				qrcode = new QRCode("qrcodeComprovanteA4", {
					text: URLComprovante,
					width: 160,
					height: 160,
					colorDark: "#000000",
					colorLight: "#ffffff",
					correctLevel: QRCode.CorrectLevel.H
				});


				document.getElementById("setEstilo").innerHTML = '<style>@media screen {' +
					'#printSection {' +
					'display: none;' +

					'}' +
					'}' +

					'@media print {' +
					'@page {' +
					'size: A4;' +
					'margin: 5px;' +
					'}' +

					'body>*:not(#printSection) {' +
					'display: none;' +
					'}' +

					'#printSection,' +
					'#printSection * {' +
					'visibility: visible;' +

					'}' +
					'#printSection {' +
					'position: absolute;' +
					'left: 0;' +
					'top: 0;' +
					'width: 210mm;' +
					'height: 297mm;' +

					'}' +
					'}</style>';


			}

		})


	}



	function removeagendamentosReservas(codAgendamentoReserva) {
		Swal.fire({
			title: 'Você tem certeza que deseja sair do cadastro reserva',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {

			if (result.value) {
				$.ajax({
					url: '<?php echo base_url('minhasReservas/remove') ?>',
					type: 'post',
					data: {
						codAgendamentoReserva: codAgendamentoReserva,
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
								timer: 4000
							}).then(function() {
								$('#data_tableagendamentosReservas').DataTable().ajax.reload(null, false).draw(false);
							})

						} else {
							Swal.fire({
								position: 'bottom-end',
								icon: 'error',
								title: response.messages,
								showConfirmButton: false,
								timer: 4000
							})


						}
					}
				});
			}
		})
	}
</script>