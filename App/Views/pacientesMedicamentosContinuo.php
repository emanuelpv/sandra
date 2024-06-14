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
				  <h3 style="font-size:30px;font-weight: bold;" class="card-title">Medicamentos Contínuo</h3>
			  	</div>
				<div class="col-md-4">
				  <button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addpacientesMedicamentosContinuo()" title="Adicionar">Adicionar</button>
				</div>
			  </div>			  
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="data_tablepacientesMedicamentosContinuo" class="table table-striped table-hover table-sm">
                <thead>
                <tr>
					<th>Código</th>
					<th>CodPaciente</th>
					<th>DescricaoMedicamento</th>
					<th>Observacao</th>
					<th>DataCriacao</th>
					<th>CodAutor</th>

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
	<div id="pacientesMedicamentosContinuoAddModal" class="modal fade" role="dialog"
		aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Medicamentos Contínuo</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
				<div class="modal-body">
					<form id="pacientesMedicamentosContinuoAddForm" class="pl-3 pr-3">								
                        <div class="row">
 							<input type="hidden" id="codPacienteMedicamento" name="codPacienteMedicamento" class="form-control" placeholder="Código" maxlength="11" required>
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
									<label for="descricaoMedicamento"> DescricaoMedicamento: <span class="text-danger">*</span> </label>
									<input type="text" id="descricaoMedicamento" name="descricaoMedicamento" class="form-control" placeholder="DescricaoMedicamento" maxlength="60" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="observacao"> Observacao: <span class="text-danger">*</span> </label>
									<textarea cols="40" rows="5" id="observacao" name="observacao" class="form-control" placeholder="Observacao" required></textarea>
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
									<label for="codAutor"> CodAutor: <span class="text-danger">*</span> </label>
									<input type="number" id="codAutor" name="codAutor" class="form-control" placeholder="CodAutor" maxlength="11" number="true" required>
								</div>
							</div>
						</div>
																				
						<div class="form-group text-center">
							<div class="btn-group">
								<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="pacientesMedicamentosContinuoAddForm-btn">Adicionar</button>
								<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
							</div>
						</div>
					</form>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->	

	<!-- Add modal content -->				
	<div id="pacientesMedicamentosContinuoEditModal" class="modal fade" role="dialog"
		aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Medicamentos Contínuo</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
				<div class="modal-body">
					<form id="pacientesMedicamentosContinuoEditForm" class="pl-3 pr-3">
                        <div class="row">
 							<input type="hidden" id="codPacienteMedicamento" name="codPacienteMedicamento" class="form-control" placeholder="Código" maxlength="11" required>
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
									<label for="descricaoMedicamento"> DescricaoMedicamento: <span class="text-danger">*</span> </label>
									<input type="text" id="descricaoMedicamento" name="descricaoMedicamento" class="form-control" placeholder="DescricaoMedicamento" maxlength="60" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="observacao"> Observacao: <span class="text-danger">*</span> </label>
									<textarea cols="40" rows="5" id="observacao" name="observacao" class="form-control" placeholder="Observacao" required></textarea>
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
									<label for="codAutor"> CodAutor: <span class="text-danger">*</span> </label>
									<input type="number" id="codAutor" name="codAutor" class="form-control" placeholder="CodAutor" maxlength="11" number="true" required>
								</div>
							</div>
						</div>
											
						<div class="form-group text-center">
							<div class="btn-group">
								<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="pacientesMedicamentosContinuoEditForm-btn">Salvar</button>
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
	$('#data_tablepacientesMedicamentosContinuo').DataTable({
		"paging": true,
		"deferRender": true,
		"lengthChange": false,
		"searching": true,
		"ordering": true,
		"info": true,
		"autoWidth": false,
		"responsive": true,
		"ajax": {
			"url": '<?php echo base_url('pacientesMedicamentosContinuo/getAll') ?>',			
			"type": "POST",
			"dataType": "json",
			async: "true"
		}	  
	});
});
function addpacientesMedicamentosContinuo() {
	// reset the form 
	$("#pacientesMedicamentosContinuoAddForm")[0].reset();
	$(".form-control").removeClass('is-invalid').removeClass('is-valid');		
	$('#pacientesMedicamentosContinuoAddModal').modal('show');
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
			
			var form = $('#pacientesMedicamentosContinuoAddForm');
			// remove the text-danger
			$(".text-danger").remove();

			$.ajax({
				url: '<?php echo base_url('pacientesMedicamentosContinuo/add') ?>',						
				type: 'post',
				data: form.serialize(), // /converting the form data into array and sending it to server
				dataType: 'json',
				beforeSend: function() {
					//$('#pacientesMedicamentosContinuoAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
				},					
				success: function(response) {

					if (response.success === true) {
							$('#pacientesMedicamentosContinuoAddModal').modal('hide');

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
							$('#data_tablepacientesMedicamentosContinuo').DataTable().ajax.reload(null, false).draw(false);
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
					$('#pacientesMedicamentosContinuoAddForm-btn').html('Adicionar');
				}
			});

			return false;
		}
	});
	$('#pacientesMedicamentosContinuoAddForm').validate();
}

function editpacientesMedicamentosContinuo(codPacienteMedicamento) {
	$.ajax({
		url: '<?php echo base_url('pacientesMedicamentosContinuo/getOne') ?>',
		type: 'post',
		data: {
			codPacienteMedicamento: codPacienteMedicamento
		},
		dataType: 'json',
		success: function(response) {
			// reset the form 
			$("#pacientesMedicamentosContinuoEditForm")[0].reset();
			$(".form-control").removeClass('is-invalid').removeClass('is-valid');				
			$('#pacientesMedicamentosContinuoEditModal').modal('show');	

			$("#pacientesMedicamentosContinuoEditForm #codPacienteMedicamento").val(response.codPacienteMedicamento);
			$("#pacientesMedicamentosContinuoEditForm #codPaciente").val(response.codPaciente);
			$("#pacientesMedicamentosContinuoEditForm #descricaoMedicamento").val(response.descricaoMedicamento);
			$("#pacientesMedicamentosContinuoEditForm #observacao").val(response.observacao);
			$("#pacientesMedicamentosContinuoEditForm #dataCriacao").val(response.dataCriacao);
			$("#pacientesMedicamentosContinuoEditForm #codAutor").val(response.codAutor);

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
					var form = $('#pacientesMedicamentosContinuoEditForm');
					$(".text-danger").remove();
					$.ajax({
						url: '<?php echo base_url('pacientesMedicamentosContinuo/edit') ?>' ,						
						type: 'post',
						data: form.serialize(), 
						dataType: 'json',
						beforeSend: function() {
							//$('#pacientesMedicamentosContinuoEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
						},								
						success: function(response) {

							if (response.success === true) {
								
									$('#pacientesMedicamentosContinuoEditModal').modal('hide');

								
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
									$('#data_tablepacientesMedicamentosContinuo').DataTable().ajax.reload(null, false).draw(false);
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
							$('#pacientesMedicamentosContinuoEditForm-btn').html('Salvar');
						}
					});

					return false;
				}
			});
			$('#pacientesMedicamentosContinuoEditForm').validate();

		}
	});
}	

function removepacientesMedicamentosContinuo(codPacienteMedicamento) {	
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
			url: '<?php echo base_url('pacientesMedicamentosContinuo/remove') ?>',
			type: 'post',
			data: {
				codPacienteMedicamento: codPacienteMedicamento
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
						$('#data_tablepacientesMedicamentosContinuo').DataTable().ajax.reload(null, false).draw(false);								
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
