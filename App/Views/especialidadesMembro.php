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
				  <h3 style="font-size:30px;font-weight: bold;" class="card-title">Especialidades membros</h3>
			  	</div>
				<div class="col-md-4">
				  <button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addespecialidadesMembro()" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
				</div>
			  </div>			  
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="data_tableespecialidadesMembro" class="table table-striped table-hover table-sm">
                <thead>
                <tr>
					<th>CodEspecialidadeMembro</th>
					<th>CodEspecialidade</th>
					<th>CodPessoa</th>
					<th>CodEstadoFederacao</th>
					<th>NumeroInscricao</th>
					<th>NumeroSire</th>
					<th>Observacoes</th>
					<th>DataCriacao</th>
					<th>DataAtualizacao</th>
					<th>Autor</th>

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
	<div id="add-modalespecialidadesMembro" class="modal fade" role="dialog"
		aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Especialidades membros</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
				<div class="modal-body">
					<form id="add-formespecialidadesMembro" class="pl-3 pr-3">								
                        <div class="row">
 							<input type="hidden" id="codEspecialidadeMembro" name="codEspecialidadeMembro" class="form-control" placeholder="CodEspecialidadeMembro" maxlength="11" required>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="codEspecialidade"> CodEspecialidade: <span class="text-danger">*</span> </label>
									<input type="number" id="codEspecialidade" name="codEspecialidade" class="form-control" placeholder="CodEspecialidade" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codPessoa"> CodPessoa: <span class="text-danger">*</span> </label>
									<input type="number" id="codPessoa" name="codPessoa" class="form-control" placeholder="CodPessoa" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codEstadoFederacao"> CodEstadoFederacao: <span class="text-danger">*</span> </label>
									<input type="number" id="codEstadoFederacao" name="codEstadoFederacao" class="form-control" placeholder="CodEstadoFederacao" maxlength="11" number="true" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="numeroInscricao"> NumeroInscricao: <span class="text-danger">*</span> </label>
									<input type="text" id="numeroInscricao" name="numeroInscricao" class="form-control" placeholder="NumeroInscricao" maxlength="20" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="numeroSire"> NumeroSire: <span class="text-danger">*</span> </label>
									<input type="text" id="numeroSire" name="numeroSire" class="form-control" placeholder="NumeroSire" maxlength="20" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="observacoes"> Observacoes: </label>
									<input type="text" id="observacoes" name="observacoes" class="form-control" placeholder="Observacoes" maxlength="20" >
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
							<div class="col-md-4">
								<div class="form-group">
									<label for="autor"> Autor: <span class="text-danger">*</span> </label>
									<input type="number" id="autor" name="autor" class="form-control" placeholder="Autor" maxlength="11" number="true" required>
								</div>
							</div>
						</div>
						<div class="row">
						</div>
																				
						<div class="form-group text-center">
							<div class="btn-group">
								<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="add-formespecialidadesMembro-btn">Adicionar</button>
								<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
							</div>
						</div>
					</form>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->	

	<!-- Add modal content -->				
	<div id="edit-modalespecialidadesMembro" class="modal fade" role="dialog"
		aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Especialidades membros</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
				<div class="modal-body">
					<form id="edit-formespecialidadesMembro" class="pl-3 pr-3">
                        <div class="row">
 							<input type="hidden" id="codEspecialidadeMembro" name="codEspecialidadeMembro" class="form-control" placeholder="CodEspecialidadeMembro" maxlength="11" required>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="codEspecialidade"> CodEspecialidade: <span class="text-danger">*</span> </label>
									<input type="number" id="codEspecialidade" name="codEspecialidade" class="form-control" placeholder="CodEspecialidade" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codPessoa"> CodPessoa: <span class="text-danger">*</span> </label>
									<input type="number" id="codPessoa" name="codPessoa" class="form-control" placeholder="CodPessoa" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codEstadoFederacao"> CodEstadoFederacao: <span class="text-danger">*</span> </label>
									<input type="number" id="codEstadoFederacao" name="codEstadoFederacao" class="form-control" placeholder="CodEstadoFederacao" maxlength="11" number="true" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="numeroInscricao"> NumeroInscricao: <span class="text-danger">*</span> </label>
									<input type="text" id="numeroInscricao" name="numeroInscricao" class="form-control" placeholder="NumeroInscricao" maxlength="20" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="numeroSire"> NumeroSire: <span class="text-danger">*</span> </label>
									<input type="text" id="numeroSire" name="numeroSire" class="form-control" placeholder="NumeroSire" maxlength="20" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="observacoes"> Observacoes: </label>
									<input type="text" id="observacoes" name="observacoes" class="form-control" placeholder="Observacoes" maxlength="20" >
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
							<div class="col-md-4">
								<div class="form-group">
									<label for="autor"> Autor: <span class="text-danger">*</span> </label>
									<input type="number" id="autor" name="autor" class="form-control" placeholder="Autor" maxlength="11" number="true" required>
								</div>
							</div>
						</div>
						<div class="row">
						</div>
											
						<div class="form-group text-center">
							<div class="btn-group">
								<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="edit-formespecialidadesMembro-btn">Salvar</button>
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
	$('#data_tableespecialidadesMembro').DataTable({
		"paging": true,
		"lengthChange": false,
		"searching": true,
		"ordering": true,
		"info": true,
		"autoWidth": false,
		"responsive": true,
		"ajax": {
			"url": '<?php echo base_url('especialidadesMembro/getAll') ?>',			
			"type": "POST",
			"dataType": "json",
			async: "true"
		}	  
	});
});
function addespecialidadesMembro() {
	// reset the form 
	$("#add-formespecialidadesMembro")[0].reset();
	$(".form-control").removeClass('is-invalid').removeClass('is-valid');		
	$('#add-modalespecialidadesMembro').modal('show');
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
			
			var form = $('#add-formespecialidadesMembro');
			// remove the text-danger
			$(".text-danger").remove();

			$.ajax({
				url: '<?php echo base_url('especialidadesMembro/add') ?>',						
				type: 'post',
				data: form.serialize(), // /converting the form data into array and sending it to server
				dataType: 'json',
				beforeSend: function() {
					//$('#add-formespecialidadesMembro-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
							$('#data_tableespecialidadesMembro').DataTable().ajax.reload(null, false).draw(false);
							$('#add-modalespecialidadesMembro').modal('hide');
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
					$('#add-formespecialidadesMembro-btn').html('Adicionar');
				}
			});

			return false;
		}
	});
	$('#add-formespecialidadesMembro').validate();
}

function editespecialidadesMembro(codEspecialidadeMembro) {
	$.ajax({
		url: '<?php echo base_url('especialidadesMembro/getOne') ?>',
		type: 'post',
		data: {
			codEspecialidadeMembro: codEspecialidadeMembro
		},
		dataType: 'json',
		success: function(response) {
			// reset the form 
			$("#edit-formespecialidadesMembro")[0].reset();
			$(".form-control").removeClass('is-invalid').removeClass('is-valid');				
			$('#edit-modalespecialidadesMembro').modal('show');	

			$("#edit-formespecialidadesMembro #codEspecialidadeMembro").val(response.codEspecialidadeMembro);
			$("#edit-formespecialidadesMembro #codEspecialidade").val(response.codEspecialidade);
			$("#edit-formespecialidadesMembro #codPessoa").val(response.codPessoa);
			$("#edit-formespecialidadesMembro #codEstadoFederacao").val(response.codEstadoFederacao);
			$("#edit-formespecialidadesMembro #numeroInscricao").val(response.numeroInscricao);
			$("#edit-formespecialidadesMembro #numeroSire").val(response.numeroSire);
			$("#edit-formespecialidadesMembro #observacoes").val(response.observacoes);
			$("#edit-formespecialidadesMembro #dataCriacao").val(response.dataCriacao);
			$("#edit-formespecialidadesMembro #dataAtualizacao").val(response.dataAtualizacao);
			$("#edit-formespecialidadesMembro #autor").val(response.autor);

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
					var form = $('#edit-formespecialidadesMembro');
					$(".text-danger").remove();
					$.ajax({
						url: '<?php echo base_url('especialidadesMembro/edit') ?>' ,						
						type: 'post',
						data: form.serialize(), 
						dataType: 'json',
						beforeSend: function() {
							//$('#edit-formespecialidadesMembro-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
									$('#data_tableespecialidadesMembro').DataTable().ajax.reload(null, false).draw(false);
									$('#edit-modalespecialidadesMembro').modal('hide');
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
							$('#edit-formespecialidadesMembro-btn').html('Salvar');
						}
					});

					return false;
				}
			});
			$('#edit-formespecialidadesMembro').validate();

		}
	});
}	

function removeespecialidadesMembro(codEspecialidadeMembro) {	
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
			url: '<?php echo base_url('especialidadesMembro/remove') ?>',
			type: 'post',
			data: {
				codEspecialidadeMembro: codEspecialidadeMembro
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
						$('#data_tableespecialidadesMembro').DataTable().ajax.reload(null, false).draw(false);								
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
