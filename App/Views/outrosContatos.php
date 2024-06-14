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
				  <h3 style="font-size:30px;font-weight: bold;" class="card-title">outrosContatos</h3>
			  	</div>
				<div class="col-md-4">
				  <button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addoutrosContatos()" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
				</div>
			  </div>			  
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="data_tableoutrosContatos" class="table table-striped table-hover table-sm">
                <thead>
                <tr>
					<th>CodOutroContato</th>
					<th>CodTipoContato</th>
					<th>NomeContato</th>
					<th>NumeroContato</th>
					<th>CodPaciente</th>
					<th>CodOrganizacao</th>

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
	<div id="add-modaloutrosContatos" class="modal fade" role="dialog"
		aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar outrosContatos</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
				<div class="modal-body">
					<form autocomplete="off" id="add-formoutrosContatos" class="pl-3 pr-3">								
                        <div class="row">
 							<input type="hidden" id="codOutroContato" name="codOutroContato" class="form-control" placeholder="CodOutroContato" maxlength="11" required>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="codTipoContato"> CodTipoContato: </label>
									<input type="number" id="codTipoContato" name="codTipoContato" class="form-control" placeholder="CodTipoContato" maxlength="11" number="true" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="nomeContato"> NomeContato: </label>
									<input autocomplete="off" type="text" id="nomeContato" name="nomeContato" class="form-control" placeholder="NomeContato" maxlength="20" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="numeroContato"> NumeroContato: </label>
									<input autocomplete="off" type="text" id="numeroContato" name="numeroContato" class="form-control" placeholder="NumeroContato" maxlength="15" >
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="codPaciente"> CodPaciente: </label>
									<input type="number" id="codPaciente" name="codPaciente" class="form-control" placeholder="CodPaciente" maxlength="11" number="true" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codOrganizacao"> CodOrganizacao: </label>
									<input type="number" id="codOrganizacao" name="codOrganizacao" class="form-control" placeholder="CodOrganizacao" maxlength="11" number="true" >
								</div>
							</div>
						</div>
																				
						<div class="form-group text-center">
							<div class="btn-group">
								<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="add-formoutrosContatos-btn">Adicionar</button>
								<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
							</div>
						</div>
					</form>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->	

	<!-- Add modal content -->				
	<div id="edit-modaloutrosContatos" class="modal fade" role="dialog"
		aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar outrosContatos</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
				<div class="modal-body">
					<form id="edit-formoutrosContatos" class="pl-3 pr-3">
                        <div class="row">
 							<input type="hidden" id="codOutroContato" name="codOutroContato" class="form-control" placeholder="CodOutroContato" maxlength="11" required>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="codTipoContato"> CodTipoContato: </label>
									<input type="number" id="codTipoContato" name="codTipoContato" class="form-control" placeholder="CodTipoContato" maxlength="11" number="true" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="nomeContato"> NomeContato: </label>
									<input autocomplete="off" type="text" id="nomeContato" name="nomeContato" class="form-control" placeholder="NomeContato" maxlength="20" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="numeroContato"> NumeroContato: </label>
									<input autocomplete="off" type="text" id="numeroContato" name="numeroContato" class="form-control" placeholder="NumeroContato" maxlength="15" >
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="codPaciente"> CodPaciente: </label>
									<input type="number" id="codPaciente" name="codPaciente" class="form-control" placeholder="CodPaciente" maxlength="11" number="true" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codOrganizacao"> CodOrganizacao: </label>
									<input type="number" id="codOrganizacao" name="codOrganizacao" class="form-control" placeholder="CodOrganizacao" maxlength="11" number="true" >
								</div>
							</div>
						</div>
											
						<div class="form-group text-center">
							<div class="btn-group">
								<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="edit-formoutrosContatos-btn">Salvar</button>
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
$(function () {
	$('#data_tableoutrosContatos').DataTable({
		"paging": true,
		"lengthChange": false,
		"searching": true,
		"ordering": true,
		"info": true,
		"autoWidth": false,
		"responsive": true,
		"ajax": {
			"url": '<?php echo base_url('outrosContatos/getAll') ?>',			
			"type": "POST",
			"dataType": "json",
			async: "true"
		}	  
	});
});
function addoutrosContatos() {
	// reset the form 
	$("#add-formoutrosContatos")[0].reset();
	$(".form-control").removeClass('is-invalid').removeClass('is-valid');		
	$('#add-modaloutrosContatos').modal('show');
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
			
			var form = $('#add-formoutrosContatos');
			// remove the text-danger
			$(".text-danger").remove();

			$.ajax({
				url: '<?php echo base_url('outrosContatos/add') ?>',						
				type: 'post',
				data: form.serialize(), // /converting the form data into array and sending it to server
				dataType: 'json',
				beforeSend: function() {
					//$('#add-formoutrosContatos-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
							$('#data_tableoutrosContatos').DataTable().ajax.reload(null, false).draw(false);
							$('#add-modaloutrosContatos').modal('hide');
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
					$('#add-formoutrosContatos-btn').html('Adicionar');
				}
			});

			return false;
		}
	});
	$('#add-formoutrosContatos').validate();
}

function editoutrosContatos(codOutroContato) {
	$.ajax({
		url: '<?php echo base_url('outrosContatos/getOne') ?>',
		type: 'post',
		data: {
			codOutroContato: codOutroContato
		},
		dataType: 'json',
		success: function(response) {
			// reset the form 
			$("#edit-formoutrosContatos")[0].reset();
			$(".form-control").removeClass('is-invalid').removeClass('is-valid');				
			$('#edit-modaloutrosContatos').modal('show');	

			$("#edit-formoutrosContatos #codOutroContato").val(response.codOutroContato);
			$("#edit-formoutrosContatos #codTipoContato").val(response.codTipoContato);
			$("#edit-formoutrosContatos #nomeContato").val(response.nomeContato);
			$("#edit-formoutrosContatos #numeroContato").val(response.numeroContato);
			$("#edit-formoutrosContatos #codPaciente").val(response.codPaciente);
			$("#edit-formoutrosContatos #codOrganizacao").val(response.codOrganizacao);

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
					var form = $('#edit-formoutrosContatos');
					$(".text-danger").remove();
					$.ajax({
						url: '<?php echo base_url('outrosContatos/edit') ?>' ,						
						type: 'post',
						data: form.serialize(), 
						dataType: 'json',
						beforeSend: function() {
							//$('#edit-formoutrosContatos-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
									$('#data_tableoutrosContatos').DataTable().ajax.reload(null, false).draw(false);
									$('#edit-modaloutrosContatos').modal('hide');
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
							$('#edit-formoutrosContatos-btn').html('Salvar');
						}
					});

					return false;
				}
			});
			$('#edit-formoutrosContatos').validate();

		}
	});
}	

function removeoutrosContatos(codOutroContato) {	
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
			url: '<?php echo base_url('outrosContatos/remove') ?>',
			type: 'post',
			data: {
				codOutroContato: codOutroContato
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
						$('#data_tableoutrosContatos').DataTable().ajax.reload(null, false).draw(false);								
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
