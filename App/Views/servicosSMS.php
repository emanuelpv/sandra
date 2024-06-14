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

    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <div class="row">
			  	<div class="col-md-8 mt-2">
				  <h3 style="font-size:30px;font-weight: bold;" class="card-title">Serviços de SMS</h3>
			  	</div>
				<div class="col-md-4">
				  <button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addservicosSMS()" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
				</div>
			  </div>			  
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="data_tableservicosSMS" class="table table-striped table-hover table-sm">
                <thead>
                <tr>
					<th>Códico</th>
					<th>CodOrganizacao</th>
					<th>CodProvedor</th>
					<th>StatusSMS</th>

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
	<div id="add-modalservicosSMS" class="modal fade" role="dialog"
		aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Serviços de SMS</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
				<div class="modal-body">
					<form id="add-formservicosSMS" class="pl-3 pr-3">								
                        <div class="row">
 							<input type="hidden" id="codServicoSMS" name="codServicoSMS" class="form-control" placeholder="Códico" maxlength="11" required>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="codOrganizacao"> CodOrganizacao: <span class="text-danger">*</span> </label>
									<input type="number" id="codOrganizacao" name="codOrganizacao" class="form-control" placeholder="CodOrganizacao" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codProvedor"> CodProvedor: <span class="text-danger">*</span> </label>
									<select id="codProvedor" name="codProvedor" class="custom-select" required>
									<option value="0"></option>	
									<option value="1">select1</option>
										<option value="2">select2</option>
										<option value="3">select3</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
                        <div class="form-group">
                          <label for="conta"> Conta: </label>
                          <input type="text" id="conta" name="conta" class="form-control" placeholder="Conta" maxlength="100">
                        </div>
                      </div>
							
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="token"> Token: </label>
									<input type="text" id="token" name="token" class="form-control" placeholder="Token" maxlength="100" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="statusSMS"> StatusSMS: <span class="text-danger">*</span> </label>
									<select id="statusSMS" name="statusSMS" class="custom-select" required>
									<option value="0"></option>	
									<option value="1">select1</option>
										<option value="2">select2</option>
										<option value="3">select3</option>
									</select>
								</div>
							</div>
						</div>
																				
						<div class="form-group text-center">
							<div class="btn-group">
								<button type="submit" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="add-formservicosSMS-btn">Adicionar</button>
								<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
							</div>
						</div>
					</form>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div>				
	<div id="edit-modalservicosSMS" class="modal fade" role="dialog"
		aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Serviços de SMS</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
				<div class="modal-body">
					<form id="edit-formservicosSMS" class="pl-3 pr-3">
                        <div class="row">
 							<input type="hidden" id="codServicoSMS" name="codServicoSMS" class="form-control" placeholder="Códico" maxlength="11" required>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="codOrganizacao"> CodOrganizacao: <span class="text-danger">*</span> </label>
									<input type="number" id="codOrganizacao" name="codOrganizacao" class="form-control" placeholder="CodOrganizacao" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codProvedor"> CodProvedor: <span class="text-danger">*</span> </label>
									<select id="codProvedor" name="codProvedor" class="custom-select" required>
									<option value="0"></option>	
									<option value="1">select1</option>
										<option value="2">select2</option>
										<option value="3">select3</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="token"> Token: </label>
									<input type="text" id="token" name="token" class="form-control" placeholder="Token" maxlength="100" >
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="statusSMS"> StatusSMS: <span class="text-danger">*</span> </label>
									<select id="statusSMS" name="statusSMS" class="custom-select" required>
									<option value="0"></option>	
									<option value="1">select1</option>
										<option value="2">select2</option>
										<option value="3">select3</option>
									</select>
								</div>
							</div>
						</div>
											
						<div class="form-group text-center">
							<div class="btn-group">
								<button type="submit" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="edit-formservicosSMS-btn">Salvar</button>
								<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
							</div>
						</div>
					</form>

				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div>
  </div>
  
  <?php 
      echo view('tema/rodape');	
    ?>
<script>
$(function () {
	$('#data_tableservicosSMS').DataTable({
		"paging": true,
		"lengthChange": false,
		"searching": true,
		"ordering": true,
		"info": true,
		"autoWidth": false,
		"responsive": true,
		"ajax": {
			"url": '<?php echo base_url('servicosSMS/getAll') ?>',			
			"type": "POST",
			"dataType": "json",
			async: "true"
		}	  
	});
});
function addservicosSMS() {
	// reset the form 
	$("#add-formservicosSMS")[0].reset();
	$(".form-control").removeClass('is-invalid').removeClass('is-valid');		
	$('#add-modalservicosSMS').modal('show');
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
			
			var form = $('#add-formservicosSMS');
			// remove the text-danger
			$(".text-danger").remove();

			$.ajax({
				url: '<?php echo base_url('servicosSMS/add') ?>',						
				type: 'post',
				data: form.serialize(), // /converting the form data into array and sending it to server
				dataType: 'json',
				beforeSend: function() {
					//$('#add-formservicosSMS-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
							$('#data_tableservicosSMS').DataTable().ajax.reload(null, false).draw(false);
							$('#add-modalservicosSMS').modal('hide');
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
					$('#add-formservicosSMS-btn').html('Adicionar');
				}
			});

			return false;
		}
	});
	$('#add-formservicosSMS').validate();
}

function editservicosSMS(codServicoSMS) {
	$.ajax({
		url: '<?php echo base_url('servicosSMS/getOne') ?>',
		type: 'post',
		data: {
			codServicoSMS: codServicoSMS
		},
		dataType: 'json',
		success: function(response) {
			// reset the form 
			$("#edit-formservicosSMS")[0].reset();
			$(".form-control").removeClass('is-invalid').removeClass('is-valid');				
			$('#edit-modalservicosSMS').modal('show');	

			$("#edit-formservicosSMS #codServicoSMS").val(response.codServicoSMS);
			$("#edit-formservicosSMS #codOrganizacao").val(response.codOrganizacao);
			$("#edit-formservicosSMS #codProvedor").val(response.codProvedor);
			$("#edit-formservicosSMS #token").val(response.token);
			$("#edit-formservicosSMS #conta").val(response.conta);
			$("#edit-formservicosSMS #statusSMS").val(response.statusSMS);

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
					var form = $('#edit-formservicosSMS');
					$(".text-danger").remove();
					$.ajax({
						url: '<?php echo base_url('servicosSMS/edit') ?>' ,						
						type: 'post',
						data: form.serialize(), 
						dataType: 'json',
						beforeSend: function() {
							//$('#edit-formservicosSMS-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
									$('#data_tableservicosSMS').DataTable().ajax.reload(null, false).draw(false);
									$('#edit-modalservicosSMS').modal('hide');
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
							$('#edit-formservicosSMS-btn').html('Salvar');
						}
					});

					return false;
				}
			});
			$('#edit-formservicosSMS').validate();

		}
	});
}	

function removeservicosSMS(codServicoSMS) {	
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
			url: '<?php echo base_url('servicosSMS/remove') ?>',
			type: 'post',
			data: {
				codServicoSMS: codServicoSMS
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
						$('#data_tableservicosSMS').DataTable().ajax.reload(null, false).draw(false);								
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
