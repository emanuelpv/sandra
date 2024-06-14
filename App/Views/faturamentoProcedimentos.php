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
				  <h3 style="font-size:30px;font-weight: bold;" class="card-title">Faturamento de Procedimentos</h3>
			  	</div>
				<div class="col-md-4">
				  <button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addfaturamentoProcedimentos()" title="Adicionar">Adicionar</button>
				</div>
			  </div>			  
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="data_tablefaturamentoProcedimentos" class="table table-striped table-hover table-sm">
                <thead>
                <tr>
					<th>Codigo</th>
					<th>CodAtendimento</th>
					<th>CodPrescricaoProcedimento</th>
					<th>AutorPrescricao</th>
					<th>DataPrescricao</th>
					<th>CodProcedimento</th>
					<th>Quantidade</th>
					<th>Valor</th>
					<th>CodLocalAtendimento</th>
					<th>DataCriacao</th>
					<th>DataAtualizacao</th>
					<th>CodStatus</th>
					<th>CodAutor</th>
					<th>Observacoes</th>

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
	<div id="faturamentoProcedimentosAddModal" class="modal fade" role="dialog"
		aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Faturamento de Procedimentos</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
				<div class="modal-body">
					<form id="faturamentoProcedimentosAddForm" class="pl-3 pr-3">
						<input type="hidden" id="<?php echo csrf_token() ?>faturamentoProcedimentosAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
					
                        <div class="row">
 							<input type="hidden" id="codFaturamentoProcedimento" name="codFaturamentoProcedimento" class="form-control" placeholder="Codigo" maxlength="11" required>
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
									<label for="codPrescricaoProcedimento"> CodPrescricaoProcedimento: <span class="text-danger">*</span> </label>
									<input type="number" id="codPrescricaoProcedimento" name="codPrescricaoProcedimento" class="form-control" placeholder="CodPrescricaoProcedimento" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="autorPrescricao"> AutorPrescricao: <span class="text-danger">*</span> </label>
									<input type="number" id="autorPrescricao" name="autorPrescricao" class="form-control" placeholder="AutorPrescricao" maxlength="11" number="true" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="dataPrescricao"> DataPrescricao: </label>
									<input type="text" id="dataPrescricao" name="dataPrescricao" class="form-control" placeholder="DataPrescricao" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codProcedimento"> CodProcedimento: <span class="text-danger">*</span> </label>
									<input type="number" id="codProcedimento" name="codProcedimento" class="form-control" placeholder="CodProcedimento" maxlength="11" number="true" required>
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
							<div class="col-md-4">
								<div class="form-group">
									<label for="codStatus"> CodStatus: <span class="text-danger">*</span> </label>
									<input type="number" id="codStatus" name="codStatus" class="form-control" placeholder="CodStatus" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codAutor"> CodAutor: <span class="text-danger">*</span> </label>
									<input type="number" id="codAutor" name="codAutor" class="form-control" placeholder="CodAutor" maxlength="11" number="true" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="observacoes"> Observacoes: </label>
									<textarea cols="40" rows="5" id="observacoes" name="observacoes" class="form-control" placeholder="Observacoes" ></textarea>
								</div>
							</div>
						</div>
																				
						<div class="form-group text-center">
							<div class="btn-group">
								<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="faturamentoProcedimentosAddForm-btn">Adicionar</button>
								<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
							</div>
						</div>
					</form>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->	

	<!-- Add modal content -->				
	<div id="faturamentoProcedimentosEditModal" class="modal fade" role="dialog"
		aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Faturamento de Procedimentos</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
				<div class="modal-body">
					<form id="faturamentoProcedimentosEditForm" class="pl-3 pr-3">
						<input type="hidden" id="<?php echo csrf_token() ?>faturamentoProcedimentosEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                        <div class="row">
 							<input type="hidden" id="codFaturamentoProcedimento" name="codFaturamentoProcedimento" class="form-control" placeholder="Codigo" maxlength="11" required>
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
									<label for="codPrescricaoProcedimento"> CodPrescricaoProcedimento: <span class="text-danger">*</span> </label>
									<input type="number" id="codPrescricaoProcedimento" name="codPrescricaoProcedimento" class="form-control" placeholder="CodPrescricaoProcedimento" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="autorPrescricao"> AutorPrescricao: <span class="text-danger">*</span> </label>
									<input type="number" id="autorPrescricao" name="autorPrescricao" class="form-control" placeholder="AutorPrescricao" maxlength="11" number="true" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="dataPrescricao"> DataPrescricao: </label>
									<input type="text" id="dataPrescricao" name="dataPrescricao" class="form-control" placeholder="DataPrescricao" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codProcedimento"> CodProcedimento: <span class="text-danger">*</span> </label>
									<input type="number" id="codProcedimento" name="codProcedimento" class="form-control" placeholder="CodProcedimento" maxlength="11" number="true" required>
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
							<div class="col-md-4">
								<div class="form-group">
									<label for="codStatus"> CodStatus: <span class="text-danger">*</span> </label>
									<input type="number" id="codStatus" name="codStatus" class="form-control" placeholder="CodStatus" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codAutor"> CodAutor: <span class="text-danger">*</span> </label>
									<input type="number" id="codAutor" name="codAutor" class="form-control" placeholder="CodAutor" maxlength="11" number="true" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="observacoes"> Observacoes: </label>
									<textarea cols="40" rows="5" id="observacoes" name="observacoes" class="form-control" placeholder="Observacoes" ></textarea>
								</div>
							</div>
						</div>
											
						<div class="form-group text-center">
							<div class="btn-group">
								<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="faturamentoProcedimentosEditForm-btn">Salvar</button>
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
	$('#data_tablefaturamentoProcedimentos').DataTable({
		"paging": true,
		"deferRender": true,
		"lengthChange": false,
		"searching": true,
		"ordering": true,
		"info": true,
		"autoWidth": false,
		"responsive": true,
		"ajax": {
			"url": '<?php echo base_url('faturamentoProcedimentos/getAll') ?>',			
			"type": "POST",
			"dataType": "json",
			async: "true",
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
		}	  
	});
});
function addfaturamentoProcedimentos() {
	// reset the form 
	$("#faturamentoProcedimentosAddForm")[0].reset();
	$(".form-control").removeClass('is-invalid').removeClass('is-valid');		
	$('#faturamentoProcedimentosAddModal').modal('show');
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
			
			var form = $('#faturamentoProcedimentosAddForm');
			// remove the text-danger
			$(".text-danger").remove();

			$.ajax({
				url: '<?php echo base_url('faturamentoProcedimentos/add') ?>',						
				type: 'post',
				data: form.serialize(), // /converting the form data into array and sending it to server
				dataType: 'json',
				beforeSend: function() {
					//$('#faturamentoProcedimentosAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
				},					
				success: function(response) {

					if (response.success === true) {
							$('#faturamentoProcedimentosAddModal').modal('hide');

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
							$('#data_tablefaturamentoProcedimentos').DataTable().ajax.reload(null, false).draw(false);
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
					$('#faturamentoProcedimentosAddForm-btn').html('Adicionar');
				}
			});

			return false;
		}
	});
	$('#faturamentoProcedimentosAddForm').validate();
}

function editfaturamentoProcedimentos(codFaturamentoProcedimento) {
	$.ajax({
		url: '<?php echo base_url('faturamentoProcedimentos/getOne') ?>',
		type: 'post',
		data: {
			codFaturamentoProcedimento: codFaturamentoProcedimento,
			csrf_sandra: $("#csrf_sandraPrincipal").val(),
		},
		dataType: 'json',
		success: function(response) {
			// reset the form 
			$("#faturamentoProcedimentosEditForm")[0].reset();
			$(".form-control").removeClass('is-invalid').removeClass('is-valid');				
			$('#faturamentoProcedimentosEditModal').modal('show');	

			$("#faturamentoProcedimentosEditForm #codFaturamentoProcedimento").val(response.codFaturamentoProcedimento);
			$("#faturamentoProcedimentosEditForm #codAtendimento").val(response.codAtendimento);
			$("#faturamentoProcedimentosEditForm #codPrescricaoProcedimento").val(response.codPrescricaoProcedimento);
			$("#faturamentoProcedimentosEditForm #autorPrescricao").val(response.autorPrescricao);
			$("#faturamentoProcedimentosEditForm #dataPrescricao").val(response.dataPrescricao);
			$("#faturamentoProcedimentosEditForm #codProcedimento").val(response.codProcedimento);
			$("#faturamentoProcedimentosEditForm #quantidade").val(response.quantidade);
			$("#faturamentoProcedimentosEditForm #valor").val(response.valor);
			$("#faturamentoProcedimentosEditForm #codLocalAtendimento").val(response.codLocalAtendimento);
			$("#faturamentoProcedimentosEditForm #dataCriacao").val(response.dataCriacao);
			$("#faturamentoProcedimentosEditForm #dataAtualizacao").val(response.dataAtualizacao);
			$("#faturamentoProcedimentosEditForm #codStatus").val(response.codStatus);
			$("#faturamentoProcedimentosEditForm #codAutor").val(response.codAutor);
			$("#faturamentoProcedimentosEditForm #observacoes").val(response.observacoes);

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
					var form = $('#faturamentoProcedimentosEditForm');
					$(".text-danger").remove();
					$.ajax({
						url: '<?php echo base_url('faturamentoProcedimentos/edit') ?>' ,						
						type: 'post',
						data: form.serialize(), 
						dataType: 'json',
						beforeSend: function() {
							//$('#faturamentoProcedimentosEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
						},								
						success: function(response) {

							if (response.success === true) {
								
									$('#faturamentoProcedimentosEditModal').modal('hide');

								
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
									$('#data_tablefaturamentoProcedimentos').DataTable().ajax.reload(null, false).draw(false);
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
							$('#faturamentoProcedimentosEditForm-btn').html('Salvar');
						}
					});

					return false;
				}
			});
			$('#faturamentoProcedimentosEditForm').validate();

		}
	});
}	

function removefaturamentoProcedimentos(codFaturamentoProcedimento) {	
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
			url: '<?php echo base_url('faturamentoProcedimentos/remove') ?>',
			type: 'post',
			data: {
				codFaturamentoProcedimento: codFaturamentoProcedimento,
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
						$('#data_tablefaturamentoProcedimentos').DataTable().ajax.reload(null, false).draw(false);								
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
