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
				  <h3 style="font-size:30px;font-weight: bold;" class="card-title">Faturamento de Materiais</h3>
			  	</div>
				<div class="col-md-4">
				  <button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addfaturamentoMateriais()" title="Adicionar">Adicionar</button>
				</div>
			  </div>			  
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="data_tablefaturamentoMateriais" class="table table-striped table-hover table-sm">
                <thead>
                <tr>
					<th>Código</th>
					<th>CodAtendimento</th>
					<th>CodTipoMaterial</th>
					<th>Quantidade</th>
					<th>Valor</th>
					<th>CodLocalAtendimento</th>
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
	<div id="faturamentoMateriaisAddModal" class="modal fade" role="dialog"
		aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Faturamento de Materiais</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
				<div class="modal-body">
					<form id="faturamentoMateriaisAddForm" class="pl-3 pr-3">
						<input type="hidden" id="<?php echo csrf_token() ?>faturamentoMateriaisAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
					
                        <div class="row">
 							<input type="hidden" id="codFaturamentoMaterial" name="codFaturamentoMaterial" class="form-control" placeholder="Código" maxlength="11" required>
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
									<label for="codMaterial"> CodTipoMaterial: <span class="text-danger">*</span> </label>
									<input type="number" id="codMaterial" name="codMaterial" class="form-control" placeholder="CodTipoMaterial" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="quantidade"> Quantidade: <span class="text-danger">*</span> </label>
									<input type="number" id="quantidade" name="quantidade" class="form-control" placeholder="Quantidade" maxlength="11" number="true" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="valor"> Valor: <span class="text-danger">*</span> </label>
									<input type="text" id="valor" name="valor" class="form-control" placeholder="Valor" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codLocalAtendimento"> CodLocalAtendimento: <span class="text-danger">*</span> </label>
									<input type="number" id="codLocalAtendimento" name="codLocalAtendimento" class="form-control" placeholder="CodLocalAtendimento" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="dataCriacao"> DataCriacao: <span class="text-danger">*</span> </label>
									<input type="text" id="dataCriacao" name="dataCriacao" class="form-control" placeholder="DataCriacao" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="dataAtualizacao"> DataAtualizacao: <span class="text-danger">*</span> </label>
									<input type="text" id="dataAtualizacao" name="dataAtualizacao" class="form-control" placeholder="DataAtualizacao" required>
								</div>
							</div>
						</div>
																				
						<div class="form-group text-center">
							<div class="btn-group">
								<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="faturamentoMateriaisAddForm-btn">Adicionar</button>
								<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
							</div>
						</div>
					</form>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->	

	<!-- Add modal content -->				
	<div id="faturamentoMateriaisEditModal" class="modal fade" role="dialog"
		aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Faturamento de Materiais</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
				<div class="modal-body">
					<form id="faturamentoMateriaisEditForm" class="pl-3 pr-3">
						<input type="hidden" id="<?php echo csrf_token() ?>faturamentoMateriaisEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                        <div class="row">
 							<input type="hidden" id="codFaturamentoMaterial" name="codFaturamentoMaterial" class="form-control" placeholder="Código" maxlength="11" required>
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
									<label for="codMaterial"> CodTipoMaterial: <span class="text-danger">*</span> </label>
									<input type="number" id="codMaterial" name="codMaterial" class="form-control" placeholder="CodTipoMaterial" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="quantidade"> Quantidade: <span class="text-danger">*</span> </label>
									<input type="number" id="quantidade" name="quantidade" class="form-control" placeholder="Quantidade" maxlength="11" number="true" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="valor"> Valor: <span class="text-danger">*</span> </label>
									<input type="text" id="valor" name="valor" class="form-control" placeholder="Valor" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codLocalAtendimento"> CodLocalAtendimento: <span class="text-danger">*</span> </label>
									<input type="number" id="codLocalAtendimento" name="codLocalAtendimento" class="form-control" placeholder="CodLocalAtendimento" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="dataCriacao"> DataCriacao: <span class="text-danger">*</span> </label>
									<input type="text" id="dataCriacao" name="dataCriacao" class="form-control" placeholder="DataCriacao" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="dataAtualizacao"> DataAtualizacao: <span class="text-danger">*</span> </label>
									<input type="text" id="dataAtualizacao" name="dataAtualizacao" class="form-control" placeholder="DataAtualizacao" required>
								</div>
							</div>
						</div>
											
						<div class="form-group text-center">
							<div class="btn-group">
								<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="faturamentoMateriaisEditForm-btn">Salvar</button>
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
	$('#data_tablefaturamentoMateriais').DataTable({
		"paging": true,
		"deferRender": true,
		"lengthChange": false,
		"searching": true,
		"ordering": true,
		"info": true,
		"autoWidth": false,
		"responsive": true,
		"ajax": {
			"url": '<?php echo base_url('faturamentoMateriais/getAll') ?>',			
			"type": "POST",
			"dataType": "json",
			async: "true",
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
		}	  
	});
});
function addfaturamentoMateriais() {
	// reset the form 
	$("#faturamentoMateriaisAddForm")[0].reset();
	$(".form-control").removeClass('is-invalid').removeClass('is-valid');		
	$('#faturamentoMateriaisAddModal').modal('show');
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
			
			var form = $('#faturamentoMateriaisAddForm');
			// remove the text-danger
			$(".text-danger").remove();

			$.ajax({
				url: '<?php echo base_url('faturamentoMateriais/add') ?>',						
				type: 'post',
				data: form.serialize(), // /converting the form data into array and sending it to server
				dataType: 'json',
				beforeSend: function() {
					//$('#faturamentoMateriaisAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
				},					
				success: function(response) {

					if (response.success === true) {
							$('#faturamentoMateriaisAddModal').modal('hide');

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
							$('#data_tablefaturamentoMateriais').DataTable().ajax.reload(null, false).draw(false);
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
					$('#faturamentoMateriaisAddForm-btn').html('Adicionar');
				}
			});

			return false;
		}
	});
	$('#faturamentoMateriaisAddForm').validate();
}

function editfaturamentoMateriais(codFaturamentoMaterial) {
	$.ajax({
		url: '<?php echo base_url('faturamentoMateriais/getOne') ?>',
		type: 'post',
		data: {
			codFaturamentoMaterial: codFaturamentoMaterial,
			csrf_sandra: $("#csrf_sandraPrincipal").val(),
		},
		dataType: 'json',
		success: function(response) {
			// reset the form 
			$("#faturamentoMateriaisEditForm")[0].reset();
			$(".form-control").removeClass('is-invalid').removeClass('is-valid');				
			$('#faturamentoMateriaisEditModal').modal('show');	

			$("#faturamentoMateriaisEditForm #codFaturamentoMaterial").val(response.codFaturamentoMaterial);
			$("#faturamentoMateriaisEditForm #codAtendimento").val(response.codAtendimento);
			$("#faturamentoMateriaisEditForm #codMaterial").val(response.codMaterial);
			$("#faturamentoMateriaisEditForm #quantidade").val(response.quantidade);
			$("#faturamentoMateriaisEditForm #valor").val(response.valor);
			$("#faturamentoMateriaisEditForm #codLocalAtendimento").val(response.codLocalAtendimento);
			$("#faturamentoMateriaisEditForm #dataCriacao").val(response.dataCriacao);
			$("#faturamentoMateriaisEditForm #dataAtualizacao").val(response.dataAtualizacao);

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
					var form = $('#faturamentoMateriaisEditForm');
					$(".text-danger").remove();
					$.ajax({
						url: '<?php echo base_url('faturamentoMateriais/edit') ?>' ,						
						type: 'post',
						data: form.serialize(), 
						dataType: 'json',
						beforeSend: function() {
							//$('#faturamentoMateriaisEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
						},								
						success: function(response) {

							if (response.success === true) {
								
									$('#faturamentoMateriaisEditModal').modal('hide');

								
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
									$('#data_tablefaturamentoMateriais').DataTable().ajax.reload(null, false).draw(false);
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
							$('#faturamentoMateriaisEditForm-btn').html('Salvar');
						}
					});

					return false;
				}
			});
			$('#faturamentoMateriaisEditForm').validate();

		}
	});
}	

function removefaturamentoMateriais(codFaturamentoMaterial) {	
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
			url: '<?php echo base_url('faturamentoMateriais/remove') ?>',
			type: 'post',
			data: {
				codFaturamentoMaterial: codFaturamentoMaterial,
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
						$('#data_tablefaturamentoMateriais').DataTable().ajax.reload(null, false).draw(false);								
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
