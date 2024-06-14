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
				  <h3 style="font-size:30px;font-weight: bold;" class="card-title">Prescrição de Medicamentos</h3>
			  	</div>
				<div class="col-md-4">
				  <button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addprescricaoMedicamentos()" title="Adicionar">Adicionar</button>
				</div>
			  </div>			  
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="data_tableprescricaoMedicamentos" class="table table-striped table-hover table-sm">
                <thead>
                <tr>
					<th>Código</th>
					<th>CodAtendimentoPrescricao</th>
					<th>CodMedicamento</th>
					<th>Qtde</th>
					<th>Und</th>
					<th>Via</th>
					<th>Freq</th>
					<th>Per</th>
					<th>Dias</th>
					<th>HoraIni</th>
					<th>Agora</th>
					<th>Risco</th>
					<th>Obs</th>
					<th>Apraza</th>
					<th>Total</th>
					<th>Stat</th>
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
	<div id="prescricaoMedicamentosAddModal" class="modal fade" role="dialog"
		aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Prescrição de Medicamentos</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
				<div class="modal-body">
					<form id="prescricaoMedicamentosAddForm" class="pl-3 pr-3">
						<input type="hidden" id="<?php echo csrf_token() ?>prescricaoMedicamentosAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
					
                        <div class="row">
 							<input type="hidden" id="codPrescricaoMedicamento" name="codPrescricaoMedicamento" class="form-control" placeholder="Código" maxlength="11" required>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="codAtendimentoPrescricao"> CodAtendimentoPrescricao: <span class="text-danger">*</span> </label>
									<input type="number" id="codAtendimentoPrescricao" name="codAtendimentoPrescricao" class="form-control" placeholder="CodAtendimentoPrescricao" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codMedicamento"> CodMedicamento: <span class="text-danger">*</span> </label>
									<input type="number" id="codMedicamento" name="codMedicamento" class="form-control" placeholder="CodMedicamento" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="qtde"> Qtde: <span class="text-danger">*</span> </label>
									<input type="text" id="qtde" name="qtde" class="form-control" placeholder="Qtde" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="und"> Und: <span class="text-danger">*</span> </label>
									<select id="und" name="und" class="custom-select" required>
									<option value="0"></option>	
									<option value="1">select1</option>
										<option value="2">select2</option>
										<option value="3">select3</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="via"> Via: <span class="text-danger">*</span> </label>
									<select id="via" name="via" class="custom-select" required>
									<option value="0"></option>	
									<option value="1">select1</option>
										<option value="2">select2</option>
										<option value="3">select3</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="freq"> Freq: <span class="text-danger">*</span> </label>
									<input type="number" id="freq" name="freq" class="form-control" placeholder="Freq" maxlength="2" number="true" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="per"> Per: <span class="text-danger">*</span> </label>
									<select id="per" name="per" class="custom-select" required>
									<option value="0"></option>	
									<option value="1">select1</option>
										<option value="2">select2</option>
										<option value="3">select3</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="dias"> Dias: <span class="text-danger">*</span> </label>
									<input type="number" id="dias" name="dias" class="form-control" placeholder="Dias" maxlength="3" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="horaIni"> HoraIni: <span class="text-danger">*</span> </label>
									<input type="text" id="horaIni" name="horaIni" class="form-control" placeholder="HoraIni" maxlength="10" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="agora"> Agora: <span class="text-danger">*</span> </label>
									<select id="agora" name="agora" class="custom-select" required>
									<option value="0"></option>	
									<option value="1">select1</option>
										<option value="2">select2</option>
										<option value="3">select3</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="risco"> Risco: <span class="text-danger">*</span> </label>
									<select id="risco" name="risco" class="custom-select" required>
									<option value="0"></option>	
									<option value="1">select1</option>
										<option value="2">select2</option>
										<option value="3">select3</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="obs"> Obs: <span class="text-danger">*</span> </label>
									<textarea cols="40" rows="5" id="obs" name="obs" class="form-control" placeholder="Obs" required></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="apraza"> Apraza: <span class="text-danger">*</span> </label>
									<textarea cols="40" rows="5" id="apraza" name="apraza" class="form-control" placeholder="Apraza" required></textarea>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="total"> Total: <span class="text-danger">*</span> </label>
									<input type="text" id="total" name="total" class="form-control" placeholder="Total" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="stat"> Stat: <span class="text-danger">*</span> </label>
									<select id="stat" name="stat" class="custom-select" required>
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
								<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="prescricaoMedicamentosAddForm-btn">Adicionar</button>
								<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
							</div>
						</div>
					</form>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->	

	<!-- Add modal content -->				
	<div id="prescricaoMedicamentosEditModal" class="modal fade" role="dialog"
		aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Prescrição de Medicamentos</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
				<div class="modal-body">
					<form id="prescricaoMedicamentosEditForm" class="pl-3 pr-3">
						<input type="hidden" id="<?php echo csrf_token() ?>prescricaoMedicamentosEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                        <div class="row">
 							<input type="hidden" id="codPrescricaoMedicamento" name="codPrescricaoMedicamento" class="form-control" placeholder="Código" maxlength="11" required>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="codAtendimentoPrescricao"> CodAtendimentoPrescricao: <span class="text-danger">*</span> </label>
									<input type="number" id="codAtendimentoPrescricao" name="codAtendimentoPrescricao" class="form-control" placeholder="CodAtendimentoPrescricao" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codMedicamento"> CodMedicamento: <span class="text-danger">*</span> </label>
									<input type="number" id="codMedicamento" name="codMedicamento" class="form-control" placeholder="CodMedicamento" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="qtde"> Qtde: <span class="text-danger">*</span> </label>
									<input type="text" id="qtde" name="qtde" class="form-control" placeholder="Qtde" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="und"> Und: <span class="text-danger">*</span> </label>
									<select id="und" name="und" class="custom-select" required>
									<option value="0"></option>	
									<option value="1">select1</option>
										<option value="2">select2</option>
										<option value="3">select3</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="via"> Via: <span class="text-danger">*</span> </label>
									<select id="via" name="via" class="custom-select" required>
									<option value="0"></option>	
									<option value="1">select1</option>
										<option value="2">select2</option>
										<option value="3">select3</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="freq"> Freq: <span class="text-danger">*</span> </label>
									<input type="number" id="freq" name="freq" class="form-control" placeholder="Freq" maxlength="2" number="true" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="per"> Per: <span class="text-danger">*</span> </label>
									<select id="per" name="per" class="custom-select" required>
									<option value="0"></option>	
									<option value="1">select1</option>
										<option value="2">select2</option>
										<option value="3">select3</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="dias"> Dias: <span class="text-danger">*</span> </label>
									<input type="number" id="dias" name="dias" class="form-control" placeholder="Dias" maxlength="3" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="horaIni"> HoraIni: <span class="text-danger">*</span> </label>
									<input type="text" id="horaIni" name="horaIni" class="form-control" placeholder="HoraIni" maxlength="10" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="agora"> Agora: <span class="text-danger">*</span> </label>
									<select id="agora" name="agora" class="custom-select" required>
									<option value="0"></option>	
									<option value="1">select1</option>
										<option value="2">select2</option>
										<option value="3">select3</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="risco"> Risco: <span class="text-danger">*</span> </label>
									<select id="risco" name="risco" class="custom-select" required>
									<option value="0"></option>	
									<option value="1">select1</option>
										<option value="2">select2</option>
										<option value="3">select3</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="obs"> Obs: <span class="text-danger">*</span> </label>
									<textarea cols="40" rows="5" id="obs" name="obs" class="form-control" placeholder="Obs" required></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="apraza"> Apraza: <span class="text-danger">*</span> </label>
									<textarea cols="40" rows="5" id="apraza" name="apraza" class="form-control" placeholder="Apraza" required></textarea>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="total"> Total: <span class="text-danger">*</span> </label>
									<input type="text" id="total" name="total" class="form-control" placeholder="Total" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="stat"> Stat: <span class="text-danger">*</span> </label>
									<select id="stat" name="stat" class="custom-select" required>
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
								<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="prescricaoMedicamentosEditForm-btn">Salvar</button>
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
	$('#data_tableprescricaoMedicamentos').DataTable({
		"paging": true,
		"deferRender": true,
		"lengthChange": false,
		"searching": true,
		"ordering": true,
		"info": true,
		"autoWidth": false,
		"responsive": true,
		"ajax": {
			"url": '<?php echo base_url('prescricaoMedicamentos/getAll') ?>',			
			"type": "POST",
			"dataType": "json",
			async: "true",
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
		}	  
	});
});
function addprescricaoMedicamentos() {
	// reset the form 
	$("#prescricaoMedicamentosAddForm")[0].reset();
	$(".form-control").removeClass('is-invalid').removeClass('is-valid');		
	$('#prescricaoMedicamentosAddModal').modal('show');
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
			
			var form = $('#prescricaoMedicamentosAddForm');
			// remove the text-danger
			$(".text-danger").remove();

			$.ajax({
				url: '<?php echo base_url('prescricaoMedicamentos/add') ?>',						
				type: 'post',
				data: form.serialize(), // /converting the form data into array and sending it to server
				dataType: 'json',
				beforeSend: function() {
					//$('#prescricaoMedicamentosAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
				},					
				success: function(response) {

					if (response.success === true) {
							$('#prescricaoMedicamentosAddModal').modal('hide');

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
							$('#data_tableprescricaoMedicamentos').DataTable().ajax.reload(null, false).draw(false);
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
					$('#prescricaoMedicamentosAddForm-btn').html('Adicionar');
				}
			});

			return false;
		}
	});
	$('#prescricaoMedicamentosAddForm').validate();
}

function editprescricaoMedicamentos(codPrescricaoMedicamento) {
	$.ajax({
		url: '<?php echo base_url('prescricaoMedicamentos/getOne') ?>',
		type: 'post',
		data: {
			codPrescricaoMedicamento: codPrescricaoMedicamento,
			csrf_sandra: $("#csrf_sandraPrincipal").val(),
		},
		dataType: 'json',
		success: function(response) {
			// reset the form 
			$("#prescricaoMedicamentosEditForm")[0].reset();
			$(".form-control").removeClass('is-invalid').removeClass('is-valid');				
			$('#prescricaoMedicamentosEditModal').modal('show');	

			$("#prescricaoMedicamentosEditForm #codPrescricaoMedicamento").val(response.codPrescricaoMedicamento);
			$("#prescricaoMedicamentosEditForm #codAtendimentoPrescricao").val(response.codAtendimentoPrescricao);
			$("#prescricaoMedicamentosEditForm #codMedicamento").val(response.codMedicamento);
			$("#prescricaoMedicamentosEditForm #qtde").val(response.qtde);
			$("#prescricaoMedicamentosEditForm #und").val(response.und);
			$("#prescricaoMedicamentosEditForm #via").val(response.via);
			$("#prescricaoMedicamentosEditForm #freq").val(response.freq);
			$("#prescricaoMedicamentosEditForm #per").val(response.per);
			$("#prescricaoMedicamentosEditForm #dias").val(response.dias);
			$("#prescricaoMedicamentosEditForm #horaIni").val(response.horaIni);
			$("#prescricaoMedicamentosEditForm #agora").val(response.agora);
			$("#prescricaoMedicamentosEditForm #risco").val(response.risco);
			$("#prescricaoMedicamentosEditForm #obs").val(response.obs);
			$("#prescricaoMedicamentosEditForm #apraza").val(response.apraza);
			$("#prescricaoMedicamentosEditForm #total").val(response.total);
			$("#prescricaoMedicamentosEditForm #stat").val(response.stat);
			$("#prescricaoMedicamentosEditForm #codAutor").val(response.codAutor);
			$("#prescricaoMedicamentosEditForm #dataCriacao").val(response.dataCriacao);
			$("#prescricaoMedicamentosEditForm #dataAtualizacao").val(response.dataAtualizacao);

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
					var form = $('#prescricaoMedicamentosEditForm');
					$(".text-danger").remove();
					$.ajax({
						url: '<?php echo base_url('prescricaoMedicamentos/edit') ?>' ,						
						type: 'post',
						data: form.serialize(), 
						dataType: 'json',
						beforeSend: function() {
							//$('#prescricaoMedicamentosEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
						},								
						success: function(response) {

							if (response.success === true) {
								
									$('#prescricaoMedicamentosEditModal').modal('hide');

								
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
									$('#data_tableprescricaoMedicamentos').DataTable().ajax.reload(null, false).draw(false);
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
							$('#prescricaoMedicamentosEditForm-btn').html('Salvar');
						}
					});

					return false;
				}
			});
			$('#prescricaoMedicamentosEditForm').validate();

		}
	});
}	

function removeprescricaoMedicamentos(codPrescricaoMedicamento) {	
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
			url: '<?php echo base_url('prescricaoMedicamentos/remove') ?>',
			type: 'post',
			data: {
				codPrescricaoMedicamento: codPrescricaoMedicamento,
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
						$('#data_tableprescricaoMedicamentos').DataTable().ajax.reload(null, false).draw(false);								
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
