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
				  <h3 style="font-size:30px;font-weight: bold;" class="card-title">dadosDemograficos</h3>
			  	</div>
				<div class="col-md-4">
				  <button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="adddadosDemograficos()" title="Adicionar">Adicionar</button>
				</div>
			  </div>			  
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="data_tabledadosDemograficos" class="table table-striped table-hover table-sm">
                <thead>
                <tr>
					<th>CodDadosDemograficos</th>
					<th>CodQuestionario</th>
					<th>CodResposta</th>
					<th>NomeCompleto</th>
					<th>NomeExibicao</th>
					<th>Idade</th>
					<th>Sexo</th>
					<th>TempoUso</th>
					<th>Concordou</th>
					<th>TempoExperienciaAgilidade</th>
					<th>NivelEducacao</th>
					<th>PosicaoOrganizacao</th>
					<th>TempoExperienciaProjetos</th>
					<th>TipoOrganizacao</th>
					<th>TamanhoOrganizacao</th>
					<th>EscopoOrganizacao</th>
					<th>CodPaciente</th>
					<th>CodPessoa</th>
					<th>Setor</th>
					<th>Modulo</th>
					<th>NrTentativa</th>

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
	<div id="dadosDemograficosAddModal" class="modal fade" role="dialog"
		aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar dadosDemograficos</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
				<div class="modal-body">
					<form id="dadosDemograficosAddForm" class="pl-3 pr-3">
						<input type="hidden" id="<?php echo csrf_token() ?>dadosDemograficosAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
					
                        <div class="row">
 							<input type="hidden" id="codDadosDemograficos" name="codDadosDemograficos" class="form-control" placeholder="CodDadosDemograficos" maxlength="11" required>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="codQuestionario"> CodQuestionario: <span class="text-danger">*</span> </label>
									<input type="number" id="codQuestionario" name="codQuestionario" class="form-control" placeholder="CodQuestionario" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codResposta"> CodResposta: <span class="text-danger">*</span> </label>
									<input type="number" id="codResposta" name="codResposta" class="form-control" placeholder="CodResposta" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="nomeCompleto"> NomeCompleto: <span class="text-danger">*</span> </label>
									<input type="text" id="nomeCompleto" name="nomeCompleto" class="form-control" placeholder="NomeCompleto" maxlength="100" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="nomeExibicao"> NomeExibicao: <span class="text-danger">*</span> </label>
									<input type="text" id="nomeExibicao" name="nomeExibicao" class="form-control" placeholder="NomeExibicao" maxlength="100" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="idade"> Idade: <span class="text-danger">*</span> </label>
									<input type="number" id="idade" name="idade" class="form-control" placeholder="Idade" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="sexo"> Sexo: <span class="text-danger">*</span> </label>
									<input type="text" id="sexo" name="sexo" class="form-control" placeholder="Sexo" maxlength="15" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="tempoUso"> TempoUso: <span class="text-danger">*</span> </label>
									<input type="text" id="tempoUso" name="tempoUso" class="form-control" placeholder="TempoUso" maxlength="50" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="concordou"> Concordou: <span class="text-danger">*</span> </label>
									<input type="text" id="concordou" name="concordou" class="form-control" placeholder="Concordou" maxlength="3" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="tempoExperienciaAgilidade"> TempoExperienciaAgilidade: <span class="text-danger">*</span> </label>
									<input type="text" id="tempoExperienciaAgilidade" name="tempoExperienciaAgilidade" class="form-control" placeholder="TempoExperienciaAgilidade" maxlength="100" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="nivelEducacao"> NivelEducacao: <span class="text-danger">*</span> </label>
									<input type="text" id="nivelEducacao" name="nivelEducacao" class="form-control" placeholder="NivelEducacao" maxlength="100" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="posicaoOrganizacao"> PosicaoOrganizacao: <span class="text-danger">*</span> </label>
									<input type="text" id="posicaoOrganizacao" name="posicaoOrganizacao" class="form-control" placeholder="PosicaoOrganizacao" maxlength="100" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="tempoExperienciaProjetos"> TempoExperienciaProjetos: <span class="text-danger">*</span> </label>
									<input type="text" id="tempoExperienciaProjetos" name="tempoExperienciaProjetos" class="form-control" placeholder="TempoExperienciaProjetos" maxlength="100" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="tipoOrganizacao"> TipoOrganizacao: <span class="text-danger">*</span> </label>
									<input type="text" id="tipoOrganizacao" name="tipoOrganizacao" class="form-control" placeholder="TipoOrganizacao" maxlength="100" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="tamanhoOrganizacao"> TamanhoOrganizacao: <span class="text-danger">*</span> </label>
									<input type="text" id="tamanhoOrganizacao" name="tamanhoOrganizacao" class="form-control" placeholder="TamanhoOrganizacao" maxlength="100" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="escopoOrganizacao"> EscopoOrganizacao: <span class="text-danger">*</span> </label>
									<input type="text" id="escopoOrganizacao" name="escopoOrganizacao" class="form-control" placeholder="EscopoOrganizacao" maxlength="100" required>
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
									<label for="codPessoa"> CodPessoa: </label>
									<input type="number" id="codPessoa" name="codPessoa" class="form-control" placeholder="CodPessoa" maxlength="11" number="true" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="setor"> Setor: </label>
									<input type="text" id="setor" name="setor" class="form-control" placeholder="Setor" maxlength="100" >
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="modulo"> Modulo: </label>
									<input type="text" id="modulo" name="modulo" class="form-control" placeholder="Modulo" maxlength="100" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="nrTentativa"> NrTentativa: </label>
									<input type="number" id="nrTentativa" name="nrTentativa" class="form-control" placeholder="NrTentativa" maxlength="11" number="true" >
								</div>
							</div>
						</div>
																				
						<div class="form-group text-center">
							<div class="btn-group">
								<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="dadosDemograficosAddForm-btn">Adicionar</button>
								<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
							</div>
						</div>
					</form>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->	

	<!-- Add modal content -->				
	<div id="dadosDemograficosEditModal" class="modal fade" role="dialog"
		aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar dadosDemograficos</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
				<div class="modal-body">
					<form id="dadosDemograficosEditForm" class="pl-3 pr-3">
						<input type="hidden" id="<?php echo csrf_token() ?>dadosDemograficosEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                        <div class="row">
 							<input type="hidden" id="codDadosDemograficos" name="codDadosDemograficos" class="form-control" placeholder="CodDadosDemograficos" maxlength="11" required>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="codQuestionario"> CodQuestionario: <span class="text-danger">*</span> </label>
									<input type="number" id="codQuestionario" name="codQuestionario" class="form-control" placeholder="CodQuestionario" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codResposta"> CodResposta: <span class="text-danger">*</span> </label>
									<input type="number" id="codResposta" name="codResposta" class="form-control" placeholder="CodResposta" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="nomeCompleto"> NomeCompleto: <span class="text-danger">*</span> </label>
									<input type="text" id="nomeCompleto" name="nomeCompleto" class="form-control" placeholder="NomeCompleto" maxlength="100" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="nomeExibicao"> NomeExibicao: <span class="text-danger">*</span> </label>
									<input type="text" id="nomeExibicao" name="nomeExibicao" class="form-control" placeholder="NomeExibicao" maxlength="100" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="idade"> Idade: <span class="text-danger">*</span> </label>
									<input type="number" id="idade" name="idade" class="form-control" placeholder="Idade" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="sexo"> Sexo: <span class="text-danger">*</span> </label>
									<input type="text" id="sexo" name="sexo" class="form-control" placeholder="Sexo" maxlength="15" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="tempoUso"> TempoUso: <span class="text-danger">*</span> </label>
									<input type="text" id="tempoUso" name="tempoUso" class="form-control" placeholder="TempoUso" maxlength="50" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="concordou"> Concordou: <span class="text-danger">*</span> </label>
									<input type="text" id="concordou" name="concordou" class="form-control" placeholder="Concordou" maxlength="3" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="tempoExperienciaAgilidade"> TempoExperienciaAgilidade: <span class="text-danger">*</span> </label>
									<input type="text" id="tempoExperienciaAgilidade" name="tempoExperienciaAgilidade" class="form-control" placeholder="TempoExperienciaAgilidade" maxlength="100" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="nivelEducacao"> NivelEducacao: <span class="text-danger">*</span> </label>
									<input type="text" id="nivelEducacao" name="nivelEducacao" class="form-control" placeholder="NivelEducacao" maxlength="100" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="posicaoOrganizacao"> PosicaoOrganizacao: <span class="text-danger">*</span> </label>
									<input type="text" id="posicaoOrganizacao" name="posicaoOrganizacao" class="form-control" placeholder="PosicaoOrganizacao" maxlength="100" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="tempoExperienciaProjetos"> TempoExperienciaProjetos: <span class="text-danger">*</span> </label>
									<input type="text" id="tempoExperienciaProjetos" name="tempoExperienciaProjetos" class="form-control" placeholder="TempoExperienciaProjetos" maxlength="100" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="tipoOrganizacao"> TipoOrganizacao: <span class="text-danger">*</span> </label>
									<input type="text" id="tipoOrganizacao" name="tipoOrganizacao" class="form-control" placeholder="TipoOrganizacao" maxlength="100" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="tamanhoOrganizacao"> TamanhoOrganizacao: <span class="text-danger">*</span> </label>
									<input type="text" id="tamanhoOrganizacao" name="tamanhoOrganizacao" class="form-control" placeholder="TamanhoOrganizacao" maxlength="100" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="escopoOrganizacao"> EscopoOrganizacao: <span class="text-danger">*</span> </label>
									<input type="text" id="escopoOrganizacao" name="escopoOrganizacao" class="form-control" placeholder="EscopoOrganizacao" maxlength="100" required>
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
									<label for="codPessoa"> CodPessoa: </label>
									<input type="number" id="codPessoa" name="codPessoa" class="form-control" placeholder="CodPessoa" maxlength="11" number="true" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="setor"> Setor: </label>
									<input type="text" id="setor" name="setor" class="form-control" placeholder="Setor" maxlength="100" >
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="modulo"> Modulo: </label>
									<input type="text" id="modulo" name="modulo" class="form-control" placeholder="Modulo" maxlength="100" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="nrTentativa"> NrTentativa: </label>
									<input type="number" id="nrTentativa" name="nrTentativa" class="form-control" placeholder="NrTentativa" maxlength="11" number="true" >
								</div>
							</div>
						</div>
											
						<div class="form-group text-center">
							<div class="btn-group">
								<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="dadosDemograficosEditForm-btn">Salvar</button>
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
	$('#data_tabledadosDemograficos').DataTable({
		"paging": true,
		"deferRender": true,
		"lengthChange": false,
		"searching": true,
		"ordering": true,
		"info": true,
		"autoWidth": false,
		"responsive": true,
		"ajax": {
			"url": '<?php echo base_url('dadosDemograficos/getAll') ?>',			
			"type": "POST",
			"dataType": "json",
			async: "true",
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
		}	  
	});
});
function adddadosDemograficos() {
	// reset the form 
	$("#dadosDemograficosAddForm")[0].reset();
	$(".form-control").removeClass('is-invalid').removeClass('is-valid');		
	$('#dadosDemograficosAddModal').modal('show');
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
			
			var form = $('#dadosDemograficosAddForm');
			// remove the text-danger
			$(".text-danger").remove();

			$.ajax({
				url: '<?php echo base_url('dadosDemograficos/add') ?>',						
				type: 'post',
				data: form.serialize(), // /converting the form data into array and sending it to server
				dataType: 'json',
				beforeSend: function() {
					//$('#dadosDemograficosAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
				},					
				success: function(response) {

					if (response.success === true) {
							$('#dadosDemograficosAddModal').modal('hide');

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
							$('#data_tabledadosDemograficos').DataTable().ajax.reload(null, false).draw(false);
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
					$('#dadosDemograficosAddForm-btn').html('Adicionar');
				}
			});

			return false;
		}
	});
	$('#dadosDemograficosAddForm').validate();
}

function editdadosDemograficos(codDadosDemograficos) {
	$.ajax({
		url: '<?php echo base_url('dadosDemograficos/getOne') ?>',
		type: 'post',
		data: {
			codDadosDemograficos: codDadosDemograficos,
			csrf_sandra: $("#csrf_sandraPrincipal").val(),
		},
		dataType: 'json',
		success: function(response) {
			// reset the form 
			$("#dadosDemograficosEditForm")[0].reset();
			$(".form-control").removeClass('is-invalid').removeClass('is-valid');				
			$('#dadosDemograficosEditModal').modal('show');	

			$("#dadosDemograficosEditForm #codDadosDemograficos").val(response.codDadosDemograficos);
			$("#dadosDemograficosEditForm #codQuestionario").val(response.codQuestionario);
			$("#dadosDemograficosEditForm #codResposta").val(response.codResposta);
			$("#dadosDemograficosEditForm #nomeCompleto").val(response.nomeCompleto);
			$("#dadosDemograficosEditForm #nomeExibicao").val(response.nomeExibicao);
			$("#dadosDemograficosEditForm #idade").val(response.idade);
			$("#dadosDemograficosEditForm #sexo").val(response.sexo);
			$("#dadosDemograficosEditForm #tempoUso").val(response.tempoUso);
			$("#dadosDemograficosEditForm #concordou").val(response.concordou);
			$("#dadosDemograficosEditForm #tempoExperienciaAgilidade").val(response.tempoExperienciaAgilidade);
			$("#dadosDemograficosEditForm #nivelEducacao").val(response.nivelEducacao);
			$("#dadosDemograficosEditForm #posicaoOrganizacao").val(response.posicaoOrganizacao);
			$("#dadosDemograficosEditForm #tempoExperienciaProjetos").val(response.tempoExperienciaProjetos);
			$("#dadosDemograficosEditForm #tipoOrganizacao").val(response.tipoOrganizacao);
			$("#dadosDemograficosEditForm #tamanhoOrganizacao").val(response.tamanhoOrganizacao);
			$("#dadosDemograficosEditForm #escopoOrganizacao").val(response.escopoOrganizacao);
			$("#dadosDemograficosEditForm #codPaciente").val(response.codPaciente);
			$("#dadosDemograficosEditForm #codPessoa").val(response.codPessoa);
			$("#dadosDemograficosEditForm #setor").val(response.setor);
			$("#dadosDemograficosEditForm #modulo").val(response.modulo);
			$("#dadosDemograficosEditForm #nrTentativa").val(response.nrTentativa);

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
					var form = $('#dadosDemograficosEditForm');
					$(".text-danger").remove();
					$.ajax({
						url: '<?php echo base_url('dadosDemograficos/edit') ?>' ,						
						type: 'post',
						data: form.serialize(), 
						dataType: 'json',
						beforeSend: function() {
							//$('#dadosDemograficosEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
						},								
						success: function(response) {

							if (response.success === true) {
								
									$('#dadosDemograficosEditModal').modal('hide');

								
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
									$('#data_tabledadosDemograficos').DataTable().ajax.reload(null, false).draw(false);
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
							$('#dadosDemograficosEditForm-btn').html('Salvar');
						}
					});

					return false;
				}
			});
			$('#dadosDemograficosEditForm').validate();

		}
	});
}	

function removedadosDemograficos(codDadosDemograficos) {	
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
			url: '<?php echo base_url('dadosDemograficos/remove') ?>',
			type: 'post',
			data: {
				codDadosDemograficos: codDadosDemograficos,
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
						$('#data_tabledadosDemograficos').DataTable().ajax.reload(null, false).draw(false);								
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
