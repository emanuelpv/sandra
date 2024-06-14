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
				  <h3 style="font-size:30px;font-weight: bold;" class="card-title">Mapeamento de Atributos LDAP</h3>
			  	</div>
				<div class="col-md-4">
				  <button type="button" class="btn btn-block btn-primary  btn-xs" onclick="addMapeamentoAtributosLDAPModel()" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
				</div>
			  </div>			  
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="data_tablemapeamentoAtributosLDAP" class="table table-striped table-hover table-sm">
                <thead>
                <tr>
					<th>Código</th>
					<th>Servidor LDAP</th>
					<th>Atributo Sistema</th>
					<th>Atributo LDAP</th>

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
	<div id="add-modalMapeamentoAtributosLDAPModel" class="modal fade" role="dialog"
		aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Mapeamento de Atributos LDAP</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
				<div class="modal-body">
					<form id="add-formMapeamentoAtributosLDAPModel" class="pl-3 pr-3">								
                        <div class="row">
 							<input type="hidden" id="codMapAttrLDAP" name="codMapAttrLDAP" class="form-control" placeholder="Código" maxlength="11" required>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="codServidorLDAP"> Servidor LDAP: <span class="text-danger">*</span> </label>
									<input type="number" id="codServidorLDAP" name="codServidorLDAP" class="form-control" placeholder="Servidor LDAP" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="nomeAtributoSistema"> Atributo Sistema: <span class="text-danger">*</span> </label>
									<select id="nomeAtributoSistema" name="nomeAtributoSistema" class="custom-select" required>
									<option value="0"></option>	
									<option value="1">select1</option>
										<option value="2">select2</option>
										<option value="3">select3</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="nomeAtributoLDAP"> Atributo LDAP: <span class="text-danger">*</span> </label>
									<select id="nomeAtributoLDAP" name="nomeAtributoLDAP" class="custom-select" required>
									<option value="0"></option>	
									<option value="1">select1</option>
										<option value="2">select2</option>
										<option value="3">select3</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
						</div>
																				
						<div class="form-group text-center">
							<div class="btn-group">
								<button type="submit" class="btn btn-xs btn-primary" id="add-formMapeamentoAtributosLDAPModel-btn">Adicionar</button>
								<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
							</div>
						</div>
					</form>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->	

	<!-- Add modal content -->				
	<div id="edit-modalMapeamentoAtributosLDAPModel" class="modal fade" role="dialog"
		aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Mapeamento de Atributos LDAP</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
				<div class="modal-body">
					<form id="edit-formMapeamentoAtributosLDAP" class="pl-3 pr-3">
                        <div class="row">
 							<input type="hidden" id="codMapAttrLDAP" name="codMapAttrLDAP" class="form-control" placeholder="Código" maxlength="11" required>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="codServidorLDAP"> Servidor LDAP: <span class="text-danger">*</span> </label>
									<input type="number" id="codServidorLDAP" name="codServidorLDAP" class="form-control" placeholder="Servidor LDAP" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="nomeAtributoSistema"> Atributo Sistema: <span class="text-danger">*</span> </label>
									<select id="nomeAtributoSistema" name="nomeAtributoSistema" class="custom-select" required>
									<option value="0"></option>	
									<option value="1">select1</option>
										<option value="2">select2</option>
										<option value="3">select3</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="nomeAtributoLDAP"> Atributo LDAP: <span class="text-danger">*</span> </label>
									<select id="nomeAtributoLDAP" name="nomeAtributoLDAP" class="custom-select" required>
									<option value="0"></option>	
									<option value="1">select1</option>
										<option value="2">select2</option>
										<option value="3">select3</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
						</div>
											
						<div class="form-group text-center">
							<div class="btn-group">
								<button type="submit" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="edit-form-btn">Salvar</button>
								<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
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
	$('#data_tablemapeamentoAtributosLDAP').DataTable({
		"paging": true,
		"lengthChange": false,
		"searching": true,
		"ordering": true,
		"info": true,
		"autoWidth": false,
		"responsive": true,
		"ajax": {
			"url": '<?php echo base_url('mapeamentoAtributosLDAP/getAll') ?>',			
			"type": "POST",
			"dataType": "json",
			async: "true"
		}	  
	});
});
function addMapeamentoAtributosLDAPModel(codServidorLDAP) {
	// reset the form 
	$("#add-formMapeamentoAtributosLDAPModel")[0].reset();
	$("#add-formMapeamentoAtributosLDAPModel #codServidorLDAP").val(codServidorLDAP);

	$(".form-control").removeClass('is-invalid').removeClass('is-valid');		
	$('#add-modalMapeamentoAtributosLDAPModel').modal('show');
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
			
			var form = $('#add-formMapeamentoAtributosLDAPModel');
			// remove the text-danger
			$(".text-danger").remove();

			$.ajax({
				url: '<?php echo base_url('mapeamentoAtributosLDAP/add') ?>',						
				type: 'post',
				data: form.serialize(), // /converting the form data into array and sending it to server
				dataType: 'json',
				beforeSend: function() {
					//$('#add-formMapeamentoAtributosLDAPModel-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
							$('#data_tablemapeamentoAtributosLDAP').DataTable().ajax.reload(null, false).draw(false);
							$('#add-modalMapeamentoAtributosLDAPModel').modal('hide');
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
					$('#add-formMapeamentoAtributosLDAPModel-btn').html('Adicionar');
				}
			});

			return false;
		}
	});
	$('#add-formMapeamentoAtributosLDAPModel').validate();
}

function editmapeamentoAtributosLDAP(codMapAttrLDAP) {
	$.ajax({
		url: '<?php echo base_url('mapeamentoAtributosLDAP/getOne') ?>',
		type: 'post',
		data: {
			codMapAttrLDAP: codMapAttrLDAP
		},
		dataType: 'json',
		success: function(response) {
			// reset the form 
			$("#edit-formMapeamentoAtributosLDAP")[0].reset();
			$(".form-control").removeClass('is-invalid').removeClass('is-valid');				
			$('#edit-modalMapeamentoAtributosLDAPModel').modal('show');	

			$("#edit-formMapeamentoAtributosLDAP #codMapAttrLDAP").val(response.codMapAttrLDAP);
			$("#edit-formMapeamentoAtributosLDAP #codServidorLDAP").val(response.codServidorLDAP);
			$("#edit-formMapeamentoAtributosLDAP #nomeAtributoSistema").val(response.nomeAtributoSistema);
			$("#edit-formMapeamentoAtributosLDAP #nomeAtributoLDAP").val(response.nomeAtributoLDAP);
	
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
					var form = $('#edit-formMapeamentoAtributosLDAP');
					$(".text-danger").remove();
					$.ajax({
						url: '<?php echo base_url('mapeamentoAtributosLDAP/edit') ?>' ,						
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
									$('#data_tablemapeamentoAtributosLDAP').DataTable().ajax.reload(null, false).draw(false);
									$('#edit-modalMapeamentoAtributosLDAPModel').modal('hide');
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
			$('#edit-formMapeamentoAtributosLDAP').validate();

		}
	});
}	

function removemapeamentoAtributosLDAP(codMapAttrLDAP) {	
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
			url: '<?php echo base_url('mapeamentoAtributosLDAP/remove') ?>',
			type: 'post',
			data: {
				codMapAttrLDAP: codMapAttrLDAP
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
						$('#data_tablemapeamentoAtributosLDAP').DataTable().ajax.reload(null, false).draw(false);								
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
