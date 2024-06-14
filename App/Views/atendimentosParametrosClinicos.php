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
				  <h3 style="font-size:30px;font-weight: bold;" class="card-title">Parâmetros Clínicos do Atendimento</h3>
			  	</div>
				<div class="col-md-4">
				  <button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addatendimentosParametrosClinicos()" title="Adicionar">Adicionar</button>
				</div>
			  </div>			  
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="data_tableatendimentosParametrosClinicos" class="table table-striped table-hover table-sm">
                <thead>
                <tr>
					<th>CodParametroClinico</th>
					<th>CodAtendimento</th>
					<th>DataCriacao</th>
					<th>DataAtualizacao</th>
					<th>CodAutor</th>
					<th>Peso</th>
					<th>Altura</th>
					<th>PerimetroCefalico</th>
					<th>ParimetroAbdominal</th>
					<th>PaSistolica</th>
					<th>PaDiastolica</th>
					<th>Fc</th>
					<th>Fr</th>
					<th>Temperatura</th>
					<th>Saturacao</th>

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
	<div id="atendimentosParametrosClinicosAddModal" class="modal fade" role="dialog"
		aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Parâmetros Clínicos do Atendimento</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
				<div class="modal-body">
					<form id="atendimentosParametrosClinicosAddForm" class="pl-3 pr-3">
						<input type="hidden" id="<?php echo csrf_token() ?>atendimentosParametrosClinicosAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
					
                        <div class="row">
 							<input type="hidden" id="codParametroClinico" name="codParametroClinico" class="form-control" placeholder="CodParametroClinico" maxlength="11" required>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="codAtendimento"> CodAtendimento: <span class="text-danger">*</span> </label>
									<input type="number" id="codAtendimento" name="codAtendimento" class="form-control" placeholder="CodAtendimento" maxlength="11" number="true" required>
								</div>
							</div>
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
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="codAutor"> CodAutor: <span class="text-danger">*</span> </label>
									<input type="number" id="codAutor" name="codAutor" class="form-control" placeholder="CodAutor" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="peso"> Peso: <span class="text-danger">*</span> </label>
									<input type="text" id="peso" name="peso" class="form-control" placeholder="Peso" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="altura"> Altura: <span class="text-danger">*</span> </label>
									<input type="text" id="altura" name="altura" class="form-control" placeholder="Altura" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="perimetroCefalico"> PerimetroCefalico: <span class="text-danger">*</span> </label>
									<input type="text" id="perimetroCefalico" name="perimetroCefalico" class="form-control" placeholder="PerimetroCefalico" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="parimetroAbdominal"> ParimetroAbdominal: <span class="text-danger">*</span> </label>
									<input type="text" id="parimetroAbdominal" name="parimetroAbdominal" class="form-control" placeholder="ParimetroAbdominal" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="paSistolica"> PaSistolica: <span class="text-danger">*</span> </label>
									<input type="text" id="paSistolica" name="paSistolica" class="form-control" placeholder="PaSistolica" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="paDiastolica"> PaDiastolica: <span class="text-danger">*</span> </label>
									<input type="text" id="paDiastolica" name="paDiastolica" class="form-control" placeholder="PaDiastolica" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="fc"> Fc: <span class="text-danger">*</span> </label>
									<input type="text" id="fc" name="fc" class="form-control" placeholder="Fc" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="fr"> Fr: <span class="text-danger">*</span> </label>
									<textarea cols="40" rows="5" id="fr" name="fr" class="form-control" placeholder="Fr" required></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="temperatura"> Temperatura: <span class="text-danger">*</span> </label>
									<input type="text" id="temperatura" name="temperatura" class="form-control" placeholder="Temperatura" maxlength="3" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="saturacao"> Saturacao: <span class="text-danger">*</span> </label>
									<input type="text" id="saturacao" name="saturacao" class="form-control" placeholder="Saturacao" maxlength="3" required>
								</div>
							</div>
						</div>
																				
						<div class="form-group text-center">
							<div class="btn-group">
								<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="atendimentosParametrosClinicosAddForm-btn">Adicionar</button>
								<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
							</div>
						</div>
					</form>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->	

	<!-- Add modal content -->				
	<div id="atendimentosParametrosClinicosEditModal" class="modal fade" role="dialog"
		aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Parâmetros Clínicos do Atendimento</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
				<div class="modal-body">
					<form id="atendimentosParametrosClinicosEditForm" class="pl-3 pr-3">
						<input type="hidden" id="<?php echo csrf_token() ?>atendimentosParametrosClinicosEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                        <div class="row">
 							<input type="hidden" id="codParametroClinico" name="codParametroClinico" class="form-control" placeholder="CodParametroClinico" maxlength="11" required>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="codAtendimento"> CodAtendimento: <span class="text-danger">*</span> </label>
									<input type="number" id="codAtendimento" name="codAtendimento" class="form-control" placeholder="CodAtendimento" maxlength="11" number="true" required>
								</div>
							</div>
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
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="codAutor"> CodAutor: <span class="text-danger">*</span> </label>
									<input type="number" id="codAutor" name="codAutor" class="form-control" placeholder="CodAutor" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="peso"> Peso: <span class="text-danger">*</span> </label>
									<input type="text" id="peso" name="peso" class="form-control" placeholder="Peso" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="altura"> Altura: <span class="text-danger">*</span> </label>
									<input type="text" id="altura" name="altura" class="form-control" placeholder="Altura" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="perimetroCefalico"> PerimetroCefalico: <span class="text-danger">*</span> </label>
									<input type="text" id="perimetroCefalico" name="perimetroCefalico" class="form-control" placeholder="PerimetroCefalico" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="parimetroAbdominal"> ParimetroAbdominal: <span class="text-danger">*</span> </label>
									<input type="text" id="parimetroAbdominal" name="parimetroAbdominal" class="form-control" placeholder="ParimetroAbdominal" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="paSistolica"> PaSistolica: <span class="text-danger">*</span> </label>
									<input type="text" id="paSistolica" name="paSistolica" class="form-control" placeholder="PaSistolica" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="paDiastolica"> PaDiastolica: <span class="text-danger">*</span> </label>
									<input type="text" id="paDiastolica" name="paDiastolica" class="form-control" placeholder="PaDiastolica" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="fc"> Fc: <span class="text-danger">*</span> </label>
									<input type="text" id="fc" name="fc" class="form-control" placeholder="Fc" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="fr"> Fr: <span class="text-danger">*</span> </label>
									<textarea cols="40" rows="5" id="fr" name="fr" class="form-control" placeholder="Fr" required></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="temperatura"> Temperatura: <span class="text-danger">*</span> </label>
									<input type="text" id="temperatura" name="temperatura" class="form-control" placeholder="Temperatura" maxlength="3" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="saturacao"> Saturacao: <span class="text-danger">*</span> </label>
									<input type="text" id="saturacao" name="saturacao" class="form-control" placeholder="Saturacao" maxlength="3" required>
								</div>
							</div>
						</div>
											
						<div class="form-group text-center">
							<div class="btn-group">
								<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="atendimentosParametrosClinicosEditForm-btn">Salvar</button>
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
	$('#data_tableatendimentosParametrosClinicos').DataTable({
		"paging": true,
		"deferRender": true,
		"lengthChange": false,
		"searching": true,
		"ordering": true,
		"info": true,
		"autoWidth": false,
		"responsive": true,
		"ajax": {
			"url": '<?php echo base_url('atendimentosParametrosClinicos/getAll') ?>',			
			"type": "POST",
			"dataType": "json",
			async: "true",
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
		}	  
	});
});
function addatendimentosParametrosClinicos() {
	// reset the form 
	$("#atendimentosParametrosClinicosAddForm")[0].reset();
	$(".form-control").removeClass('is-invalid').removeClass('is-valid');		
	$('#atendimentosParametrosClinicosAddModal').modal('show');
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
			
			var form = $('#atendimentosParametrosClinicosAddForm');
			// remove the text-danger
			$(".text-danger").remove();

			$.ajax({
				url: '<?php echo base_url('atendimentosParametrosClinicos/add') ?>',						
				type: 'post',
				data: form.serialize(), // /converting the form data into array and sending it to server
				dataType: 'json',
				beforeSend: function() {
					//$('#atendimentosParametrosClinicosAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
				},					
				success: function(response) {

					if (response.success === true) {
							$('#atendimentosParametrosClinicosAddModal').modal('hide');

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
							$('#data_tableatendimentosParametrosClinicos').DataTable().ajax.reload(null, false).draw(false);
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
					$('#atendimentosParametrosClinicosAddForm-btn').html('Adicionar');
				}
			});

			return false;
		}
	});
	$('#atendimentosParametrosClinicosAddForm').validate();
}

function editatendimentosParametrosClinicos(codParametroClinico) {
	$.ajax({
		url: '<?php echo base_url('atendimentosParametrosClinicos/getOne') ?>',
		type: 'post',
		data: {
			codParametroClinico: codParametroClinico,
			csrf_sandra: $("#csrf_sandraPrincipal").val(),
		},
		dataType: 'json',
		success: function(response) {
			// reset the form 
			$("#atendimentosParametrosClinicosEditForm")[0].reset();
			$(".form-control").removeClass('is-invalid').removeClass('is-valid');				
			$('#atendimentosParametrosClinicosEditModal').modal('show');	

			$("#atendimentosParametrosClinicosEditForm #codParametroClinico").val(response.codParametroClinico);
			$("#atendimentosParametrosClinicosEditForm #codAtendimento").val(response.codAtendimento);
			$("#atendimentosParametrosClinicosEditForm #dataCriacao").val(response.dataCriacao);
			$("#atendimentosParametrosClinicosEditForm #dataAtualizacao").val(response.dataAtualizacao);
			$("#atendimentosParametrosClinicosEditForm #codAutor").val(response.codAutor);
			$("#atendimentosParametrosClinicosEditForm #peso").val(response.peso);
			$("#atendimentosParametrosClinicosEditForm #altura").val(response.altura);
			$("#atendimentosParametrosClinicosEditForm #perimetroCefalico").val(response.perimetroCefalico);
			$("#atendimentosParametrosClinicosEditForm #parimetroAbdominal").val(response.parimetroAbdominal);
			$("#atendimentosParametrosClinicosEditForm #paSistolica").val(response.paSistolica);
			$("#atendimentosParametrosClinicosEditForm #paDiastolica").val(response.paDiastolica);
			$("#atendimentosParametrosClinicosEditForm #fc").val(response.fc);
			$("#atendimentosParametrosClinicosEditForm #fr").val(response.fr);
			$("#atendimentosParametrosClinicosEditForm #temperatura").val(response.temperatura);
			$("#atendimentosParametrosClinicosEditForm #saturacao").val(response.saturacao);

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
					var form = $('#atendimentosParametrosClinicosEditForm');
					$(".text-danger").remove();
					$.ajax({
						url: '<?php echo base_url('atendimentosParametrosClinicos/edit') ?>' ,						
						type: 'post',
						data: form.serialize(), 
						dataType: 'json',
						beforeSend: function() {
							//$('#atendimentosParametrosClinicosEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
						},								
						success: function(response) {

							if (response.success === true) {
								
									$('#atendimentosParametrosClinicosEditModal').modal('hide');

								
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
									$('#data_tableatendimentosParametrosClinicos').DataTable().ajax.reload(null, false).draw(false);
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
							$('#atendimentosParametrosClinicosEditForm-btn').html('Salvar');
						}
					});

					return false;
				}
			});
			$('#atendimentosParametrosClinicosEditForm').validate();

		}
	});
}	

function removeatendimentosParametrosClinicos(codParametroClinico) {	
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
			url: '<?php echo base_url('atendimentosParametrosClinicos/remove') ?>',
			type: 'post',
			data: {
				codParametroClinico: codParametroClinico,
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
						$('#data_tableatendimentosParametrosClinicos').DataTable().ajax.reload(null, false).draw(false);								
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
