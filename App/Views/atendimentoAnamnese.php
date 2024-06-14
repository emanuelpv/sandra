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
				  <h3 style="font-size:30px;font-weight: bold;" class="card-title">Atendimento Anamnese</h3>
			  	</div>
				<div class="col-md-4">
				  <button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addatendimentoAnamnese()" title="Adicionar">Adicionar</button>
				</div>
			  </div>			  
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="data_tableatendimentoAnamnese" class="table table-striped table-hover table-sm">
                <thead>
                <tr>
					<th>Código</th>
					<th>CodPaciente</th>
					<th>CodEspecialidade</th>
					<th>CodEspecialista</th>
					<th>QueixaPrincipal</th>
					<th>Hda</th>
					<th>Hmp</th>
					<th>HistoriaMedicamentos</th>
					<th>HistoriaAlergias</th>
					<th>Chv</th>
					<th>Parecer</th>
					<th>Outros</th>
					<th>CodStatus</th>
					<th>DataCriacao</th>
					<th>DataAtualizacao</th>

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
	<div id="atendimentoAnamneseAddModal" class="modal fade" role="dialog"
		aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Atendimento Anamnese</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
				<div class="modal-body">
					<form id="atendimentoAnamneseAddForm" class="pl-3 pr-3">								
                        <div class="row">
 							<input type="hidden" id="codAtendimentoAnamnese" name="codAtendimentoAnamnese" class="form-control" placeholder="Código" maxlength="11" required>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="codPaciente"> CodPaciente: <span class="text-danger">*</span> </label>
									<input type="number" id="codPaciente" name="codPaciente" class="form-control" placeholder="CodPaciente" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codEspecialidade"> CodEspecialidade: <span class="text-danger">*</span> </label>
									<input type="number" id="codEspecialidade" name="codEspecialidade" class="form-control" placeholder="CodEspecialidade" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codEspecialista"> CodEspecialista: <span class="text-danger">*</span> </label>
									<input type="number" id="codEspecialista" name="codEspecialista" class="form-control" placeholder="CodEspecialista" maxlength="11" number="true" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="queixaPrincipal"> QueixaPrincipal: <span class="text-danger">*</span> </label>
									<textarea cols="40" rows="5" id="queixaPrincipal" name="queixaPrincipal" class="form-control" placeholder="QueixaPrincipal" required></textarea>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="hda"> Hda: <span class="text-danger">*</span> </label>
									<textarea cols="40" rows="5" id="hda" name="hda" class="form-control" placeholder="Hda" required></textarea>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="hmp"> Hmp: <span class="text-danger">*</span> </label>
									<input type="number" id="hmp" name="hmp" class="form-control" placeholder="Hmp" maxlength="11" number="true" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="historiaMedicamentos"> HistoriaMedicamentos: <span class="text-danger">*</span> </label>
									<textarea cols="40" rows="5" id="historiaMedicamentos" name="historiaMedicamentos" class="form-control" placeholder="HistoriaMedicamentos" required></textarea>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="historiaAlergias"> HistoriaAlergias: <span class="text-danger">*</span> </label>
									<textarea cols="40" rows="5" id="historiaAlergias" name="historiaAlergias" class="form-control" placeholder="HistoriaAlergias" required></textarea>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="chv"> Chv: <span class="text-danger">*</span> </label>
									<textarea cols="40" rows="5" id="chv" name="chv" class="form-control" placeholder="Chv" required></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="parecer"> Parecer: <span class="text-danger">*</span> </label>
									<textarea cols="40" rows="5" id="parecer" name="parecer" class="form-control" placeholder="Parecer" required></textarea>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="outros"> Outros: <span class="text-danger">*</span> </label>
									<textarea cols="40" rows="5" id="outros" name="outros" class="form-control" placeholder="Outros" required></textarea>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codStatus"> CodStatus: <span class="text-danger">*</span> </label>
									<input type="number" id="codStatus" name="codStatus" class="form-control" placeholder="CodStatus" maxlength="11" number="true" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="dataCriacao"> DataCriacao: <span class="text-danger">*</span> </label>
									<input type="text" id="dataCriacao" name="dataCriacao" class="form-control" placeholder="DataCriacao" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="dataAtualizacao"> DataAtualizacao: <span class="text-danger">*</span> </label>
									<input type="text" id="dataAtualizacao" name="dataAtualizacao" class="form-control" placeholder="DataAtualizacao" required>
								</div>
							</div>
						</div>
																				
						<div class="form-group text-center">
							<div class="btn-group">
								<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="atendimentoAnamneseAddForm-btn">Adicionar</button>
								<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
							</div>
						</div>
					</form>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->	

	<!-- Add modal content -->				
	<div id="atendimentoAnamneseEditModal" class="modal fade" role="dialog"
		aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Atendimento Anamnese</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
				<div class="modal-body">
					<form id="atendimentoAnamneseEditForm" class="pl-3 pr-3">
                        <div class="row">
 							<input type="hidden" id="codAtendimentoAnamnese" name="codAtendimentoAnamnese" class="form-control" placeholder="Código" maxlength="11" required>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="codPaciente"> CodPaciente: <span class="text-danger">*</span> </label>
									<input type="number" id="codPaciente" name="codPaciente" class="form-control" placeholder="CodPaciente" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codEspecialidade"> CodEspecialidade: <span class="text-danger">*</span> </label>
									<input type="number" id="codEspecialidade" name="codEspecialidade" class="form-control" placeholder="CodEspecialidade" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codEspecialista"> CodEspecialista: <span class="text-danger">*</span> </label>
									<input type="number" id="codEspecialista" name="codEspecialista" class="form-control" placeholder="CodEspecialista" maxlength="11" number="true" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="queixaPrincipal"> QueixaPrincipal: <span class="text-danger">*</span> </label>
									<textarea cols="40" rows="5" id="queixaPrincipal" name="queixaPrincipal" class="form-control" placeholder="QueixaPrincipal" required></textarea>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="hda"> Hda: <span class="text-danger">*</span> </label>
									<textarea cols="40" rows="5" id="hda" name="hda" class="form-control" placeholder="Hda" required></textarea>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="hmp"> Hmp: <span class="text-danger">*</span> </label>
									<input type="number" id="hmp" name="hmp" class="form-control" placeholder="Hmp" maxlength="11" number="true" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="historiaMedicamentos"> HistoriaMedicamentos: <span class="text-danger">*</span> </label>
									<textarea cols="40" rows="5" id="historiaMedicamentos" name="historiaMedicamentos" class="form-control" placeholder="HistoriaMedicamentos" required></textarea>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="historiaAlergias"> HistoriaAlergias: <span class="text-danger">*</span> </label>
									<textarea cols="40" rows="5" id="historiaAlergias" name="historiaAlergias" class="form-control" placeholder="HistoriaAlergias" required></textarea>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="chv"> Chv: <span class="text-danger">*</span> </label>
									<textarea cols="40" rows="5" id="chv" name="chv" class="form-control" placeholder="Chv" required></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="parecer"> Parecer: <span class="text-danger">*</span> </label>
									<textarea cols="40" rows="5" id="parecer" name="parecer" class="form-control" placeholder="Parecer" required></textarea>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="outros"> Outros: <span class="text-danger">*</span> </label>
									<textarea cols="40" rows="5" id="outros" name="outros" class="form-control" placeholder="Outros" required></textarea>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codStatus"> CodStatus: <span class="text-danger">*</span> </label>
									<input type="number" id="codStatus" name="codStatus" class="form-control" placeholder="CodStatus" maxlength="11" number="true" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="dataCriacao"> DataCriacao: <span class="text-danger">*</span> </label>
									<input type="text" id="dataCriacao" name="dataCriacao" class="form-control" placeholder="DataCriacao" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="dataAtualizacao"> DataAtualizacao: <span class="text-danger">*</span> </label>
									<input type="text" id="dataAtualizacao" name="dataAtualizacao" class="form-control" placeholder="DataAtualizacao" required>
								</div>
							</div>
						</div>
											
						<div class="form-group text-center">
							<div class="btn-group">
								<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="atendimentoAnamneseEditForm-btn">Salvar</button>
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
<script>

	$(document).on('show.bs.modal', '.modal', function() {
		var zIndex = 1040 + (10 * $('.modal:visible').length);
		$(this).css('z-index', zIndex);
		setTimeout(function() {
			$('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
		}, 0);
	});
	
$(function () {
	$('#data_tableatendimentoAnamnese').DataTable({
		"paging": true,
		"deferRender": true,
		"lengthChange": false,
		"searching": true,
		"ordering": true,
		"info": true,
		"autoWidth": false,
		"responsive": true,
		"ajax": {
			"url": '<?php echo base_url('atendimentoAnamnese/getAll') ?>',			
			"type": "POST",
			"dataType": "json",
			async: "true"
		}	  
	});
});
function addatendimentoAnamnese() {
	// reset the form 
	$("#atendimentoAnamneseAddForm")[0].reset();
	$(".form-control").removeClass('is-invalid').removeClass('is-valid');		
	$('#atendimentoAnamneseAddModal').modal('show');
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
			
			var form = $('#atendimentoAnamneseAddForm');
			// remove the text-danger
			$(".text-danger").remove();

			$.ajax({
				url: '<?php echo base_url('atendimentoAnamnese/add') ?>',						
				type: 'post',
				data: form.serialize(), // /converting the form data into array and sending it to server
				dataType: 'json',
				beforeSend: function() {
					//$('#atendimentoAnamneseAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
				},					
				success: function(response) {

					if (response.success === true) {
							$('#atendimentoAnamneseAddModal').modal('hide');

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
							$('#data_tableatendimentoAnamnese').DataTable().ajax.reload(null, false).draw(false);
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
					$('#atendimentoAnamneseAddForm-btn').html('Adicionar');
				}
			});

			return false;
		}
	});
	$('#atendimentoAnamneseAddForm').validate();
}

function editatendimentoAnamnese(codAtendimentoAnamnese) {
	$.ajax({
		url: '<?php echo base_url('atendimentoAnamnese/getOne') ?>',
		type: 'post',
		data: {
			codAtendimentoAnamnese: codAtendimentoAnamnese
		},
		dataType: 'json',
		success: function(response) {
			// reset the form 
			$("#atendimentoAnamneseEditForm")[0].reset();
			$(".form-control").removeClass('is-invalid').removeClass('is-valid');				
			$('#atendimentoAnamneseEditModal').modal('show');	

			$("#atendimentoAnamneseEditForm #codAtendimentoAnamnese").val(response.codAtendimentoAnamnese);
			$("#atendimentoAnamneseEditForm #codPaciente").val(response.codPaciente);
			$("#atendimentoAnamneseEditForm #codEspecialidade").val(response.codEspecialidade);
			$("#atendimentoAnamneseEditForm #codEspecialista").val(response.codEspecialista);
			$("#atendimentoAnamneseEditForm #queixaPrincipal").val(response.queixaPrincipal);
			$("#atendimentoAnamneseEditForm #hda").val(response.hda);
			$("#atendimentoAnamneseEditForm #hmp").val(response.hmp);
			$("#atendimentoAnamneseEditForm #historiaMedicamentos").val(response.historiaMedicamentos);
			$("#atendimentoAnamneseEditForm #historiaAlergias").val(response.historiaAlergias);
			$("#atendimentoAnamneseEditForm #chv").val(response.chv);
			$("#atendimentoAnamneseEditForm #parecer").val(response.parecer);
			$("#atendimentoAnamneseEditForm #outros").val(response.outros);
			$("#atendimentoAnamneseEditForm #codStatus").val(response.codStatus);
			$("#atendimentoAnamneseEditForm #dataCriacao").val(response.dataCriacao);
			$("#atendimentoAnamneseEditForm #dataAtualizacao").val(response.dataAtualizacao);

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
					var form = $('#atendimentoAnamneseEditForm');
					$(".text-danger").remove();
					$.ajax({
						url: '<?php echo base_url('atendimentoAnamnese/edit') ?>' ,						
						type: 'post',
						data: form.serialize(), 
						dataType: 'json',
						beforeSend: function() {
							//$('#atendimentoAnamneseEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
						},								
						success: function(response) {

							if (response.success === true) {
								
									$('#atendimentoAnamneseEditModal').modal('hide');

								
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
									$('#data_tableatendimentoAnamnese').DataTable().ajax.reload(null, false).draw(false);
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
							$('#atendimentoAnamneseEditForm-btn').html('Salvar');
						}
					});

					return false;
				}
			});
			$('#atendimentoAnamneseEditForm').validate();

		}
	});
}	

function removeatendimentoAnamnese(codAtendimentoAnamnese) {	
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
			url: '<?php echo base_url('atendimentoAnamnese/remove') ?>',
			type: 'post',
			data: {
				codAtendimentoAnamnese: codAtendimentoAnamnese
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
						$('#data_tableatendimentoAnamnese').DataTable().ajax.reload(null, false).draw(false);								
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
