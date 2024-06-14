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
				  <h3 style="font-size:30px;font-weight: bold;" class="card-title">Atendimentos Condutas</h3>
			  	</div>
				<div class="col-md-4">
				  <button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addatendimentosCondutas()" title="Adicionar">Adicionar</button>
				</div>
			  </div>			  
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="data_tableatendimentosCondutas" class="table table-striped table-hover table-sm">
                <thead>
                <tr>
					<th>CodAtendimentoConduta</th>
					<th>CodAtendimento</th>
					<th>DataInicio</th>
					<th>DataEncerramento</th>
					<th>CodStatus</th>
					<th>ConteudoConduta</th>
					<th>Impresso</th>
					<th>CodAutor</th>
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
	<div id="atendimentosCondutasAddModal" class="modal fade" role="dialog"
		aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Atendimentos Condutas</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
				<div class="modal-body">
					<form id="atendimentosCondutasAddForm" class="pl-3 pr-3">
						<input type="hidden" id="<?php echo csrf_token() ?>atendimentosCondutasAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
					
                        <div class="row">
 							<input type="hidden" id="codAtendimentoConduta" name="codAtendimentoConduta" class="form-control" placeholder="CodAtendimentoConduta" maxlength="11" required>
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
									<label for="dataInicio"> DataInicio: </label>
									<input type="date" id="dataInicio" name="dataInicio" class="form-control" dateISO="true" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="dataEncerramento"> DataEncerramento: </label>
									<input type="date" id="dataEncerramento" name="dataEncerramento" class="form-control" dateISO="true" >
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="codStatus"> CodStatus: <span class="text-danger">*</span> </label>
									<select id="codStatus" name="codStatus" class="custom-select" required>
									<option value="0"></option>	
									<option value="1">select1</option>
										<option value="2">select2</option>
										<option value="3">select3</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="conteudoConduta"> ConteudoConduta: </label>
									<textarea cols="40" rows="5" id="conteudoConduta" name="conteudoConduta" class="form-control" placeholder="ConteudoConduta" ></textarea>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="impresso"> Impresso: <span class="text-danger">*</span> </label>
									<input type="number" id="impresso" name="impresso" class="form-control" placeholder="Impresso" maxlength="11" number="true" required>
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
						</div>
																				
						<div class="form-group text-center">
							<div class="btn-group">
								<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="atendimentosCondutasAddForm-btn">Adicionar</button>
								<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
							</div>
						</div>
					</form>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->	

	<!-- Add modal content -->				
	<div id="atendimentosCondutasEditModal" class="modal fade" role="dialog"
		aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Atendimentos Condutas</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
				<div class="modal-body">
					<form id="atendimentosCondutasEditForm" class="pl-3 pr-3">
						<input type="hidden" id="<?php echo csrf_token() ?>atendimentosCondutasEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                        <div class="row">
 							<input type="hidden" id="codAtendimentoConduta" name="codAtendimentoConduta" class="form-control" placeholder="CodAtendimentoConduta" maxlength="11" required>
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
									<label for="dataInicio"> DataInicio: </label>
									<input type="date" id="dataInicio" name="dataInicio" class="form-control" dateISO="true" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="dataEncerramento"> DataEncerramento: </label>
									<input type="date" id="dataEncerramento" name="dataEncerramento" class="form-control" dateISO="true" >
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="codStatus"> CodStatus: <span class="text-danger">*</span> </label>
									<select id="codStatus" name="codStatus" class="custom-select" required>
									<option value="0"></option>	
									<option value="1">select1</option>
										<option value="2">select2</option>
										<option value="3">select3</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="conteudoConduta"> ConteudoConduta: </label>
									<textarea cols="40" rows="5" id="conteudoConduta" name="conteudoConduta" class="form-control" placeholder="ConteudoConduta" ></textarea>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="impresso"> Impresso: <span class="text-danger">*</span> </label>
									<input type="number" id="impresso" name="impresso" class="form-control" placeholder="Impresso" maxlength="11" number="true" required>
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
						</div>
											
						<div class="form-group text-center">
							<div class="btn-group">
								<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="atendimentosCondutasEditForm-btn">Salvar</button>
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
	$('#data_tableatendimentosCondutas').DataTable({
		"paging": true,
		"deferRender": true,
		"lengthChange": false,
		"searching": true,
		"ordering": true,
		"info": true,
		"autoWidth": false,
		"responsive": true,
		"ajax": {
			"url": '<?php echo base_url('atendimentosCondutas/getAll') ?>',			
			"type": "POST",
			"dataType": "json",
			async: "true",
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
		}	  
	});
});
function addatendimentosCondutas() {
	// reset the form 
	$("#atendimentosCondutasAddForm")[0].reset();
	$(".form-control").removeClass('is-invalid').removeClass('is-valid');		
	$('#atendimentosCondutasAddModal').modal('show');
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
			
			var form = $('#atendimentosCondutasAddForm');
			// remove the text-danger
			$(".text-danger").remove();

			$.ajax({
				url: '<?php echo base_url('atendimentosCondutas/add') ?>',						
				type: 'post',
				data: form.serialize(), // /converting the form data into array and sending it to server
				dataType: 'json',
				beforeSend: function() {
					//$('#atendimentosCondutasAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
				},					
				success: function(response) {

					if (response.success === true) {
							$('#atendimentosCondutasAddModal').modal('hide');

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
							$('#data_tableatendimentosCondutas').DataTable().ajax.reload(null, false).draw(false);
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
					$('#atendimentosCondutasAddForm-btn').html('Adicionar');
				}
			});

			return false;
		}
	});
	$('#atendimentosCondutasAddForm').validate();
}

function editatendimentosCondutas(codAtendimentoConduta) {
	$.ajax({
		url: '<?php echo base_url('atendimentosCondutas/getOne') ?>',
		type: 'post',
		data: {
			codAtendimentoConduta: codAtendimentoConduta,
			csrf_sandra: $("#csrf_sandraPrincipal").val(),
		},
		dataType: 'json',
		success: function(response) {
			// reset the form 
			$("#atendimentosCondutasEditForm")[0].reset();
			$(".form-control").removeClass('is-invalid').removeClass('is-valid');				
			$('#atendimentosCondutasEditModal').modal('show');	

			$("#atendimentosCondutasEditForm #codAtendimentoConduta").val(response.codAtendimentoConduta);
			$("#atendimentosCondutasEditForm #codAtendimento").val(response.codAtendimento);
			$("#atendimentosCondutasEditForm #dataInicio").val(response.dataInicio);
			$("#atendimentosCondutasEditForm #dataEncerramento").val(response.dataEncerramento);
			$("#atendimentosCondutasEditForm #codStatus").val(response.codStatus);
			$("#atendimentosCondutasEditForm #conteudoConduta").val(response.conteudoConduta);
			$("#atendimentosCondutasEditForm #impresso").val(response.impresso);
			$("#atendimentosCondutasEditForm #codAutor").val(response.codAutor);
			$("#atendimentosCondutasEditForm #dataCriacao").val(response.dataCriacao);
			$("#atendimentosCondutasEditForm #dataAtualizacao").val(response.dataAtualizacao);

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
					var form = $('#atendimentosCondutasEditForm');
					$(".text-danger").remove();
					$.ajax({
						url: '<?php echo base_url('atendimentosCondutas/edit') ?>' ,						
						type: 'post',
						data: form.serialize(), 
						dataType: 'json',
						beforeSend: function() {
						//	$('#atendimentosCondutasEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
						},								
						success: function(response) {

							if (response.success === true) {
								
									$('#atendimentosCondutasEditModal').modal('hide');

								
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
									$('#data_tableatendimentosCondutas').DataTable().ajax.reload(null, false).draw(false);
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
							$('#atendimentosCondutasEditForm-btn').html('Salvar');
						}
					});

					return false;
				}
			});
			$('#atendimentosCondutasEditForm').validate();

		}
	});
}	

function removeatendimentosCondutas(codAtendimentoConduta) {	
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
			url: '<?php echo base_url('atendimentosCondutas/remove') ?>',
			type: 'post',
			data: {
				codAtendimentoConduta: codAtendimentoConduta,
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
						$('#data_tableatendimentosCondutas').DataTable().ajax.reload(null, false).draw(false);								
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
