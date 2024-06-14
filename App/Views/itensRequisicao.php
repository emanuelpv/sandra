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
				  <h3 style="font-size:30px;font-weight: bold;" class="card-title">Itens Requisição</h3>
			  	</div>
				<div class="col-md-4">
				  <button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="additensRequisicao()" title="Adicionar">Adicionar</button>
				</div>
			  </div>			  
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="data_tableitensRequisicao" class="table table-striped table-hover table-sm">
                <thead>
                <tr>
					<th>Código</th>
					<th>NrRef</th>
					<th>Descrição Item</th>
					<th>Unidade</th>
					<th>Qtde Sol</th>
					<th>ValorUnit</th>
					<th>Cod Siasg</th>
					<th>Observação</th>

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
	<div id="itensRequisicaoAddModal" class="modal fade" role="dialog"
		aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Itens Requisição</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
				<div class="modal-body">
					<form id="itensRequisicaoAddForm" class="pl-3 pr-3">
						<input type="hidden" id="<?php echo csrf_token() ?>itensRequisicaoAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
					
                        <div class="row">
 							<input type="hidden" id="codRequisicaoItem" name="codRequisicaoItem" class="form-control" placeholder="Código" maxlength="11" required>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="nrRef"> NrRef: <span class="text-danger">*</span> </label>
									<input type="number" id="nrRef" name="nrRef" class="form-control" placeholder="NrRef" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="descricao"> Descrição Item: <span class="text-danger">*</span> </label>
									<textarea cols="40" rows="5" id="descricao" name="descricao" class="form-control" placeholder="Descrição detalhada do item como nome, dimensões, cor e demais características obrigatórias do material ou serviço" required></textarea>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="unidade"> Unidade: <span class="text-danger">*</span> </label>
									<select id="unidade" name="unidade" class="custom-select" required>
									<option value="0"></option>	
									<option value="1">select1</option>
										<option value="2">select2</option>
										<option value="3">select3</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="qtdeSol"> Qtde Sol: <span class="text-danger">*</span> </label>
									<input type="text" id="qtdeSol" name="qtdeSol" class="form-control" placeholder="Qtde Sol" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="valorUnit"> ValorUnit: <span class="text-danger">*</span> </label>
									<input type="text" id="valorUnit" name="valorUnit" class="form-control" placeholder="ValorUnit" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codSiasg"> Cod Siasg: <span class="text-danger">*</span> </label>
									<input type="text" id="codSiasg" name="codSiasg" class="form-control" placeholder="Cod Siasg" maxlength="20" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="tipoRefPreco"> Ref Preco: <span class="text-danger">*</span> </label>
									<select id="tipoRefPreco" name="tipoRefPreco" class="custom-select" required>
									<option value="0"></option>	
									<option value="1">select1</option>
										<option value="2">select2</option>
										<option value="3">select3</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="obs"> Observação: <span class="text-danger">*</span> </label>
									<textarea cols="40" rows="5" id="obs" name="obs" class="form-control" placeholder="Observação" required></textarea>
								</div>
							</div>
						</div>
																				
						<div class="form-group text-center">
							<div class="btn-group">
								<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="itensRequisicaoAddForm-btn">Adicionar</button>
								<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
							</div>
						</div>
					</form>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->	

	<!-- Add modal content -->				
	<div id="itensRequisicaoEditModal" class="modal fade" role="dialog"
		aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Itens Requisição</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
				<div class="modal-body">
					<form id="itensRequisicaoEditForm" class="pl-3 pr-3">
						<input type="hidden" id="<?php echo csrf_token() ?>itensRequisicaoEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                        <div class="row">
 							<input type="hidden" id="codRequisicaoItem" name="codRequisicaoItem" class="form-control" placeholder="Código" maxlength="11" required>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="nrRef"> NrRef: <span class="text-danger">*</span> </label>
									<input type="number" id="nrRef" name="nrRef" class="form-control" placeholder="NrRef" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="descricao"> Descrição Item: <span class="text-danger">*</span> </label>
									<textarea cols="40" rows="5" id="descricao" name="descricao" class="form-control" placeholder="Descrição detalhada do item como nome, dimensões, cor e demais características obrigatórias do material ou serviço" required></textarea>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="unidade"> Unidade: <span class="text-danger">*</span> </label>
									<select id="unidade" name="unidade" class="custom-select" required>
									<option value="0"></option>	
									<option value="1">select1</option>
										<option value="2">select2</option>
										<option value="3">select3</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="qtdeSol"> Qtde Sol: <span class="text-danger">*</span> </label>
									<input type="text" id="qtdeSol" name="qtdeSol" class="form-control" placeholder="Qtde Sol" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="valorUnit"> ValorUnit: <span class="text-danger">*</span> </label>
									<input type="text" id="valorUnit" name="valorUnit" class="form-control" placeholder="ValorUnit" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codSiasg"> Cod Siasg: <span class="text-danger">*</span> </label>
									<input type="text" id="codSiasg" name="codSiasg" class="form-control" placeholder="Cod Siasg" maxlength="20" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="tipoRefPreco"> Ref Preco: <span class="text-danger">*</span> </label>
									<select id="tipoRefPreco" name="tipoRefPreco" class="custom-select" required>
									<option value="0"></option>	
									<option value="1">select1</option>
										<option value="2">select2</option>
										<option value="3">select3</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="obs"> Observação: <span class="text-danger">*</span> </label>
									<textarea cols="40" rows="5" id="obs" name="obs" class="form-control" placeholder="Observação" required></textarea>
								</div>
							</div>
						</div>
											
						<div class="form-group text-center">
							<div class="btn-group">
								<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="itensRequisicaoEditForm-btn">Salvar</button>
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
	$('#data_tableitensRequisicao').DataTable({
		"paging": true,
		"deferRender": true,
		"lengthChange": false,
		"searching": true,
		"ordering": true,
		"info": true,
		"autoWidth": false,
		"responsive": true,
		"ajax": {
			"url": '<?php echo base_url('itensRequisicao/getAll') ?>',			
			"type": "POST",
			"dataType": "json",
			async: "true",
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
		}	  
	});
});
function additensRequisicao() {
	// reset the form 
	$("#itensRequisicaoAddForm")[0].reset();
	$(".form-control").removeClass('is-invalid').removeClass('is-valid');		
	$('#itensRequisicaoAddModal').modal('show');
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
			
			var form = $('#itensRequisicaoAddForm');
			// remove the text-danger
			$(".text-danger").remove();

			$.ajax({
				url: '<?php echo base_url('itensRequisicao/add') ?>',						
				type: 'post',
				data: form.serialize(), // /converting the form data into array and sending it to server
				dataType: 'json',
				beforeSend: function() {
					//$('#itensRequisicaoAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
				},					
				success: function(response) {

					if (response.success === true) {
							$('#itensRequisicaoAddModal').modal('hide');

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
							$('#data_tableitensRequisicao').DataTable().ajax.reload(null, false).draw(false);
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
					$('#itensRequisicaoAddForm-btn').html('Adicionar');
				}
			});

			return false;
		}
	});
	$('#itensRequisicaoAddForm').validate();
}

function edititensRequisicao(codRequisicaoItem) {
	$.ajax({
		url: '<?php echo base_url('itensRequisicao/getOne') ?>',
		type: 'post',
		data: {
			codRequisicaoItem: codRequisicaoItem,
			csrf_sandra: $("#csrf_sandraPrincipal").val(),
		},
		dataType: 'json',
		success: function(response) {
			// reset the form 
			$("#itensRequisicaoEditForm")[0].reset();
			$(".form-control").removeClass('is-invalid').removeClass('is-valid');				
			$('#itensRequisicaoEditModal').modal('show');	

			$("#itensRequisicaoEditForm #codRequisicaoItem").val(response.codRequisicaoItem);
			$("#itensRequisicaoEditForm #nrRef").val(response.codItem);
			$("#itensRequisicaoEditForm #descricao").val(response.descricao);
			$("#itensRequisicaoEditForm #unidade").val(response.unidade);
			$("#itensRequisicaoEditForm #qtdeSol").val(response.qtde_sol);
			$("#itensRequisicaoEditForm #valorUnit").val(response.valorUnit);
			$("#itensRequisicaoEditForm #codSiasg").val(response.cod_siasg);
			$("#itensRequisicaoEditForm #tipoRefPreco").val(response.tipo_ref_preco);
			$("#itensRequisicaoEditForm #obs").val(response.obs);

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
					var form = $('#itensRequisicaoEditForm');
					$(".text-danger").remove();
					$.ajax({
						url: '<?php echo base_url('itensRequisicao/edit') ?>' ,						
						type: 'post',
						data: form.serialize(), 
						dataType: 'json',
						beforeSend: function() {
							//$('#itensRequisicaoEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
						},								
						success: function(response) {

							if (response.success === true) {
								
									$('#itensRequisicaoEditModal').modal('hide');

								
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
									$('#data_tableitensRequisicao').DataTable().ajax.reload(null, false).draw(false);
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
							$('#itensRequisicaoEditForm-btn').html('Salvar');
						}
					});

					return false;
				}
			});
			$('#itensRequisicaoEditForm').validate();

		}
	});
}	

function removeitensRequisicao(codRequisicaoItem) {	
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
			url: '<?php echo base_url('itensRequisicao/remove') ?>',
			type: 'post',
			data: {
				codRequisicaoItem: codRequisicaoItem,
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
						$('#data_tableitensRequisicao').DataTable().ajax.reload(null, false).draw(false);								
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
