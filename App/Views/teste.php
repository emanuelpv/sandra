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
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/html5-qrcode/html5-qrcode.min.js"></script> <!--https://scanapp.org/html5-qrcode-docs/docs/intro-->
<link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/fullcalendar/main.css">


<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-md-8 mt-2">
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Teste</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addteste()" title="Adicionar">Adicionar</button>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">

					<div class="row">
						<div class="col-md-3">
							<div class="sticky-top mb-3">
								<div class="card">
									<div class="card-header">
										<h4 class="card-title">Draggable Events</h4>
									</div>
									<div class="card-body">
										<!-- the events -->
										<div id="external-events">
											<div class="external-event bg-success">Lunch</div>
											<div class="external-event bg-warning">Go home</div>
											<div class="external-event bg-info">Do homework</div>
											<div class="external-event bg-primary">Work on UI design</div>
											<div class="external-event bg-danger">Sleep tight</div>
											<div class="checkbox">
												<label for="drop-remove">
													<input type="checkbox" id="drop-remove">
													remove after drop
												</label>
											</div>
										</div>
									</div>
									<!-- /.card-body -->
								</div>
								<!-- /.card -->
								<div class="card">
									<div class="card-header">
										<h3 class="card-title">Create Event</h3>
									</div>
									<div class="card-body">
										<div class="btn-group" style="width: 100%; margin-bottom: 10px;">
											<ul class="fc-color-picker" id="color-chooser">
												<li><a class="text-primary" href="#"><i class="fas fa-square"></i></a></li>
												<li><a class="text-warning" href="#"><i class="fas fa-square"></i></a></li>
												<li><a class="text-success" href="#"><i class="fas fa-square"></i></a></li>
												<li><a class="text-danger" href="#"><i class="fas fa-square"></i></a></li>
												<li><a class="text-muted" href="#"><i class="fas fa-square"></i></a></li>
											</ul>
										</div>
										<!-- /btn-group -->
										<div class="input-group">
											<input id="new-event" type="text" class="form-control" placeholder="Event Title">

											<div class="input-group-append">
												<button id="add-new-event" type="button" class="btn btn-primary">Adicionar</button>
											</div>
											<!-- /btn-group -->
										</div>
										<!-- /input-group -->
									</div>
								</div>
							</div>
						</div>
						<!-- /.col -->
						<div class="col-md-9">
							<div class="card card-primary">
								<div class="card-body p-0">
									<!-- THE CALENDAR -->
									<div id="calendar"></div>
								</div>
								<!-- /.card-body -->
							</div>
							<!-- /.card -->
						</div>
						<!-- /.col -->
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
<div id="testeAddModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Teste</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<div id="reader" width="600px"></div>



			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Add modal content -->
<div id="testeEditModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Teste</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="testeEditForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>testeEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codPessoa" name="codPessoa" class="form-control" placeholder="CodPessoa" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="nome"> Nome: <span class="text-danger">*</span> </label>
								<input type="text" id="nome" name="nome" class="form-control" placeholder="Nome" maxlength="10" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="telefone"> Telefone: <span class="text-danger">*</span> </label>
								<input type="number" id="telefone" name="telefone" class="form-control" placeholder="Telefone" maxlength="11" number="true" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataNascimento"> DataNascimento: <span class="text-danger">*</span> </label>
								<input type="number" id="dataNascimento" name="dataNascimento" class="form-control" placeholder="DataNascimento" maxlength="11" number="true" required>
							</div>
						</div>
					</div>
					<div class="row">
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="testeEditForm-btn">Salvar</button>
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
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/moment/moment.min.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/fullcalendar/main.js"></script>

<script>
	$(document).on('show.bs.modal', '.modal', function() {
		var zIndex = 1040 + (10 * $('.modal:visible').length);
		$(this).css('z-index', zIndex);
		setTimeout(function() {
			$('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
		}, 0);
	});

	$(function() {
		$('#data_tableteste').DataTable({
			"paging": true,
			"deferRender": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('teste/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});
	});


	function addteste() {
		$('#testeAddModal').modal('show');

		function onScanSuccess(decodedText, decodedResult) {
			// handle the scanned code as you like, for example:
			url = '<?php echo base_url() ?>' + decodedText;
			window.location.href = url;

		}

		function onScanFailure(error) {
			// handle scan failure, usually better to ignore and keep scanning.
			// for example:
			console.warn(`Code scan error = ${error}`);
		}

		let html5QrcodeScanner = new Html5QrcodeScanner(
			"reader", {
				fps: 10,
				qrbox: {
					width: 250,
					height: 250
				}
			},
			/* verbose= */
			false);
		html5QrcodeScanner.render(onScanSuccess, onScanFailure);



	}



	function editteste(codPessoa) {
		$.ajax({
			url: '<?php echo base_url('teste/getOne') ?>',
			type: 'post',
			data: {
				codPessoa: codPessoa,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#testeEditForm")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#testeEditModal').modal('show');

				$("#testeEditForm #codPessoa").val(response.codPessoa);
				$("#testeEditForm #nome").val(response.nome);
				$("#testeEditForm #telefone").val(response.telefone);
				$("#testeEditForm #dataNascimento").val(response.dataNascimento);

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
						var form = $('#testeEditForm');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('teste/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#testeEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
							},
							success: function(response) {

								if (response.success === true) {

									$('#testeEditModal').modal('hide');


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
										$('#data_tableteste').DataTable().ajax.reload(null, false).draw(false);
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
								$('#testeEditForm-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#testeEditForm').validate();

			}
		});
	}

	function removeteste(codPessoa) {
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
					url: '<?php echo base_url('teste/remove') ?>',
					type: 'post',
					data: {
						codPessoa: codPessoa,
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
								$('#data_tableteste').DataTable().ajax.reload(null, false).draw(false);
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

<script>
	$('#edit-modalagendamentosConfig').on('shown.bs.modal', function() {
		$("#calendar").fullCalendar('render');
	});

	$(function() {

		/* initialize the external events
		 -----------------------------------------------------------------*/
		function ini_events(ele) {
			ele.each(function() {

				// create an Event Object (https://fullcalendar.io/docs/event-object)
				// it doesn't need to have a start or end
				var eventObject = {
					title: $.trim($(this).text()) // use the element's text as the event title
				}

				// store the Event Object in the DOM element so we can get to it later
				$(this).data('eventObject', eventObject)

				// make the event draggable using jQuery UI
				$(this).draggable({
					zIndex: 1070,
					revert: true, // will cause the event to go back to its
					revertDuration: 0 //  original position after the drag
				})

			})
		}

		ini_events($('#external-events div.external-event'))

		/* initialize the calendar
		 -----------------------------------------------------------------*/
		//Date for the calendar events (dummy data)
		var date = new Date()
		var d = date.getDate(),
			m = date.getMonth(),
			y = date.getFullYear()

		var Calendar = FullCalendar.Calendar;
		var Draggable = FullCalendar.Draggable;

		var containerEl = document.getElementById('external-events');
		var checkbox = document.getElementById('drop-remove');
		var calendarEl = document.getElementById('calendar');

		// initialize the external events
		// -----------------------------------------------------------------

		new Draggable(containerEl, {
			itemSelector: '.external-event',
			eventData: function(eventEl) {
				return {
					title: eventEl.innerText,
					backgroundColor: window.getComputedStyle(eventEl, null).getPropertyValue('background-color'),
					borderColor: window.getComputedStyle(eventEl, null).getPropertyValue('background-color'),
					textColor: window.getComputedStyle(eventEl, null).getPropertyValue('color'),
				};
			}
		});

		var calendar = new Calendar(calendarEl, {
			headerToolbar: {
				left: 'prev,next today',
				center: 'title',
				right: 'dayGridMonth,timeGridWeek,timeGridDay'
			},
			themeSystem: 'bootstrap',
			//Random default events
			events: [{
					title: 'All Day Event',
					start: new Date(y, m, 1),
					backgroundColor: '#f56954', //red
					borderColor: '#f56954', //red
					allDay: true
				},
				{
					title: 'Long Event',
					start: new Date(y, m, d - 5),
					end: new Date(y, m, d - 2),
					backgroundColor: '#f39c12', //yellow
					borderColor: '#f39c12' //yellow
				},
				{
					title: 'Meeting',
					start: new Date(y, m, d, 10, 30),
					allDay: false,
					backgroundColor: '#0073b7', //Blue
					borderColor: '#0073b7' //Blue
				},
				{
					title: 'Lunch',
					start: new Date(y, m, d, 12, 0),
					end: new Date(y, m, d, 14, 0),
					allDay: false,
					backgroundColor: '#00c0ef', //Info (aqua)
					borderColor: '#00c0ef' //Info (aqua)
				},
				{
					title: 'Jaqueline ',
					start: '2023-11-08 14:10',
					end: '2023-11-08 14:20',
					allDay: false,
					backgroundColor: '#00c0ef', //Info (aqua)
					borderColor: '#00c0ef' //Info (aqua)
				},
				{
					title: 'Emanuel Peixoto',
					start: '2023-11-08 14:20',
					end: '2023-11-08 14:30',
					allDay: false,
					backgroundColor: '#00c0ef', //Info (aqua)
					borderColor: '#00c0ef' //Info (aqua)
				},
				{
					title: 'Birthday Party',
					start: new Date(y, m, d + 1, 19, 0),
					end: new Date(y, m, d + 1, 22, 30),
					allDay: false,
					backgroundColor: '#00a65a', //Success (green)
					borderColor: '#00a65a' //Success (green)
				},
				{
					title: 'Click for Google',
					start: new Date(y, m, 28),
					end: new Date(y, m, 29),
					url: 'https://www.google.com/',
					backgroundColor: '#3c8dbc', //Primary (light-blue)
					borderColor: '#3c8dbc' //Primary (light-blue)
				}
			],
			editable: true,
			droppable: true, // this allows things to be dropped onto the calendar !!!
			drop: function(info) {
				// is the "remove after drop" checkbox checked?
				if (checkbox.checked) {
					// if so, remove the element from the "Draggable Events" list
					info.draggedEl.parentNode.removeChild(info.draggedEl);
				}
			}
		});

		calendar.render();
		// $('#calendar').fullCalendar()

		/* ADDING EVENTS */
		var currColor = '#3c8dbc' //Red by default
		// Color chooser button
		$('#color-chooser > li > a').click(function(e) {
			e.preventDefault()
			// Save color
			currColor = $(this).css('color')
			// Add color effect to button
			$('#add-new-event').css({
				'background-color': currColor,
				'border-color': currColor
			})
		})
		$('#add-new-event').click(function(e) {
			e.preventDefault()
			// Get value and make sure it is not null
			var val = $('#new-event').val()
			if (val.length == 0) {
				return
			}

			// Create events
			var event = $('<div />')
			event.css({
				'background-color': currColor,
				'border-color': currColor,
				'color': '#fff'
			}).addClass('external-event')
			event.text(val)
			$('#external-events').prepend(event)

			// Add draggable funtionality
			ini_events(event)

			// Remove event from text input
			$('#new-event').val('')
		})
	})
</script>