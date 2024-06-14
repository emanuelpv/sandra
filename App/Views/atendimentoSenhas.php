<?php
//É NECESSÁRIO EM TODAS AS VIEWS


if (session()->codOrganizacao == NULL) {
	$configuracao = config('App');
	session()->set('codOrganizacao', $configuracao->codOrganizacao);
	$codOrganizacao = $configuracao->codOrganizacao;
} else {

	$codOrganizacao = session()->codOrganizacao;
}


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

<style>
	.modal {
		overflow: auto !important;
	}

	#minhaFoto {
		width: 160px;
		height: 125px;
		border: 1px solid black;
	}

	#fotoPerfilCadastro {
		width: 160px;
		height: 125px;
		border: 1px solid black;
	}

	.select2-container {
		z-index: 100000;
	}


	.swal2-container {
		z-index: 9999999;
	}
</style>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">


					<div class="row">
						<div class="col-md-12 mt-2">
							<h3 id="tituloDepartamento" style="font-size:30px;font-weight: bold;" class="card-title">
							</h3>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<div class="row">
						<div class="col-md-12">
							<div class="description-block">
								<h5 class="description-header">
									<div style="font-size:14px;font-weight: bold ; color:green">
										<div style="font-size:14px;">
											<div class="form-group">
												<label for="checkboxresultados">
													<h4>FILA DE RESULTADOS: </h4>
												</label>
												<div class="icheck-primary d-inline">
													<style>
														input[type=checkbox] {
															transform: scale(1.8);
														}
													</style>
													<input style="margin-left:5px;" id="checkboxresultados" name="checkboxresultados" type="checkbox">


												</div>
											</div>
										</div>
									</div>
								</h5>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card card-secondary">
								<div style="margin-top:10px" class="bg-light text-dark" class="row">

									<div class="row">
										<div class="col-md-4">
											<button type="button" class="btn btn-block btn-outline-secondary btn-lg " data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addatendimentoSenhas()" title="Adicionar">Adicionar na
												Fila</button>
										</div>
										<div class="col-md-4">
											<button type="button" class="btn btn-block btn-outline-secondary btn-lg " data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="chamarProximo()" title="Adicionar">Chamar Próximo</button>
										</div>
										<div class="col-md-4">
											<div style="margin-bottom:10px;width:100% !important" id="botaoSala">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>



					<form style="margin-bottom:10px;margin-top:10px" id="filtroAgendadosForm" class="pl-3 pr-3">
						<input type="hidden" id="<?php echo csrf_token() ?>filtroAgendadosForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">


						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="dataInicioAgendadosAdd"> Data Início: </label>
									<input type="date" id="dataInicioAgendadosAdd" name="dataInicio" class="form-control" dateISO="true" required>
								</div>
							</div>
						</div>

						<div>
							<button type="button" class="btn btn-primary" onclick="filtrarAgendados()" title="Filtrar">
								<i class="fas fa-filter"></i>Filtrar</button>
						</div>

					</form>


					<table id="data_tableatendimentoSenhas" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>Senha</th>
								<th>Pessoa</th>
								<th>CPF</th>
								<th>Nº PLANO/Celular</th>
								<th>Prioridade</th>
								<th>Início Atendimento</th>
								<th>Status</th>
								<th>Qtd</th>
								<th>Atendente</th>
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
<div style="width:300px" id="atendimentoSenhasAddModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Iniciar Protocolo</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="atendimentoSenhasAddForm" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>atendimentoSenhasAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codSenhaAtendimento" name="codSenhaAtendimento" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="cpf"> CPF: <span class="text-danger">*</span> </label>
								<input autocomplete="off" type="text" id="cpf" name="cpf" class="form-control" placeholder="CPF" maxlength="14" required>
							</div>
						</div>
					</div>
					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="atendimentoSenhasAddForm-btn">Procurar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>


<div id="emAtendimentoModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Em Atendimento</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<form id="emAtendimentoForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>emAtendimentoForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<div class="col-md-12">
							<div class="form-group">

								<div id="emAtendimentoHtml"></div>

							</div>
						</div>
					</div>
					<div class="form-group text-center">
						<div class="btn-group">
							<button type="button" onclick="encerrarAtendimento()" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="atendimentoSenhasAddForm-btn">Encerrar Atendimento</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>


			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>

<div id="gerarProtocolosModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Iniciar Protocolo</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="gerarProtocolosForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>gerarProtocolosForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<div class="col-md-12">
							<div class="form-group">

								<div id="dadosPessoa"></div>

							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<button type="button" onclick="gerarProtocolo()" class="btn btn-block btn-outline-secondary btn-lg " data-toggle="tooltip" data-placement="top" title="NOVO SERVIÇO">NOVO SERVIÇO</button>
						</div>
						<div class="col-md-4">
							<button type="button" onclick="gerarProtocoloResultado()" class="btn btn-block btn-outline-secondary btn-lg " data-toggle="tooltip" data-placement="top" title="PEGAR RESULTADO">PEGAR RESULTADO</button>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-danger btn-lg" data-toggle="tooltip" data-placement="top" title="CANCELAR">CANCELAR</button>
						</div>
					</div>
			</div>
			</form>
		</div>
	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div>

<!-- Add modal content -->
<div id="atendimentoSenhasEditModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Senhas Atendimento</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="atendimentoSenhasEditForm" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>atendimentoSenhasEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codSenhaAtendimento" name="codSenhaAtendimento" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="protocolo"> Protocolo: <span class="text-danger">*</span> </label>
								<input type="text" id="protocolo" name="protocolo" class="form-control" placeholder="Protocolo" maxlength="16" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codPaciente"> Pessoa: <span class="text-danger">*</span> </label>
								<input type="number" id="codPaciente" name="codPaciente" class="form-control" placeholder="Pessoa" maxlength="11" number="true" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="cpf"> CPF: <span class="text-danger">*</span> </label>
								<input type="number" id="cpf" name="cpf" class="form-control" placeholder="CPF" maxlength="11" number="true" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="senha"> Senha: <span class="text-danger">*</span> </label>
								<input type="text" id="senha" name="senha" class="form-control" placeholder="Senha" maxlength="4" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codPrioridade"> CodPrioridade: <span class="text-danger">*</span> </label>
								<input type="number" id="codPrioridade" name="codPrioridade" class="form-control" placeholder="CodPrioridade" maxlength="11" number="true" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataInicio"> Data Início: <span class="text-danger">*</span> </label>
								<input type="number" id="dataInicio" name="dataInicio" class="form-control" placeholder="Data Início" maxlength="11" number="true" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codStatus"> CodStatus: <span class="text-danger">*</span> </label>
								<input type="number" id="codStatus" name="codStatus" class="form-control" placeholder="CodStatus" maxlength="11" number="true" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataEncerramento"> Data Encerramento: <span class="text-danger">*</span>
								</label>
								<input type="number" id="dataEncerramento" name="dataEncerramento" class="form-control" placeholder="Data Encerramento" maxlength="11" number="true" required>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="atendimentoSenhasEditForm-btn">Salvar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>

			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>



<div id="salaAtendimentoModel" class="modal fade col-md-8" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Definição do local de atendimento</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div style="margin-bottom:10px;font-size:20px">
					Para iniciar a chamada dos pacientes é necessário definir o local de atendimento


					<form id="salaAtendimentoForm" class="pl-3 pr-3">
						<input type="hidden" id="<?php echo csrf_token() ?>salaAtendimentoForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="codSalaAtendimentoAdd"> Departamento: <span class="text-danger">*</span>
									</label>
									<select id="codSalaAtendimentoAdd" name="codDepartamento" class="custom-select" required>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="codLocalAtendimento"> Local: <span class="text-danger">*</span> </label>
									<select id="codLocalAtendimentoAdd" name="codLocalAtendimento" class="custom-select" required>
									</select>
								</div>
							</div>
						</div>




						<div>
							<button type="button" class="btn btn-primary" onclick="gravarSala()" title="Gravar Sala"> <i class="fas fa-filter"></i>Gravar Sala</button>
						</div>



					</form>




				</div>

			</div>


		</div>
	</div><!-- /.modal-content -->
</div>


<div id="setEstilo"></div>

<div style="position: absolute; height: 300mm" id="comprovanteEtiqueta80Modal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div style="width: 80mm" class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Comprovante</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<div style="margin-left:10px" id="areaImpressaoComprovanteEtiqueta80">
					<div style="width: 80mm important;height: 75mm important!;" class="row">
						<div class="col-sm-12">

							<div>
								<center><img alt="" style="text-align:center;width:60px;height:60px;" src="<?php echo base_url() . "/imagens/organizacoes/" . session()->logo; ?>"></center>
							</div>
							<div style="text-align:center;font-weight: bold">
								<?php echo session()->descricaoOrganizacao; ?>



							</div>

							<div style="font-family: 'Arial';margin-top:20px;height: 80mm;">
								<div style="text-align:center;font-weight: bold;font-size:30px">SENHA: <span id="senhaCompletoComprovanteEtiqueta80"></span></div>
								<div style="text-align:left;;font-weight: bold;font-size:12px">Protocolo Nr: <span id="protocoloComprovanteEtiqueta80"></span></div>
								<div style="text-align:left;;font-weight: bold;font-size:12px">DATA: <span id="dataComprovanteEtiqueta80"></span></div>

								<div style="margin-top:20px" class="d-flex justify-content-center" id="qrcodeComprovanteEtiqueta80"></div>

							</div>

						</div>

					</div>



					<div style="margin-top:10px; border-top-style: dotted;" class="row">

					</div>
					<div style="margin-top:10px;font-family: 'Arial';margin-top:20px;text-align:left;font-weight: bold;font-size:12px">
						<div class="row">
							<b>Prezado usuário, leia atentamente as orientações a seguir:</b>
						</div>
						<div class="row">
							* Este é seu comprovante de solicitação de serviço.
						</div>

						<div class="row">
							* Guarde-o para que seja possível restrear sua solicitação.
						</div>


					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary" id="botaoImprimirComprovanteEtiqueta80">Imprimir</button>
				<button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
			</div>

		</div>
	</div>
</div>



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

	document.getElementById("tituloDepartamento").innerHTML = '<?php echo session()->nomeDepartamentoAtendimento ?>';

	codTipoFila = '<?php echo session()->codTipoFila ?>';

	if (codTipoFila == 2) {
		document.getElementById("checkboxresultados").checked = true;

	}

	$('#checkboxresultados').click(function() {
		if ($(this).is(':checked')) {

			$.ajax({
				url: '<?php echo base_url('atendimentoSenhas/setaTipoFila') ?>',
				type: 'post',
				dataType: 'json',
				data: {
					codTipoFila: "2",
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
				success: function(setaTipoFila) {
					if (setaTipoFila.success === true) {
						$('#data_tableatendimentoSenhas').DataTable().ajax.reload(null, false).draw(false);
					}
				}
			});
		} else {
			$.ajax({
				url: '<?php echo base_url('atendimentoSenhas/setaTipoFila') ?>',
				type: 'post',
				dataType: 'json',
				data: {
					codTipoFila: "1",
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
				success: function(setaTipoFila) {
					if (setaTipoFila.success === true) {
						$('#data_tableatendimentoSenhas').DataTable().ajax.reload(null, false).draw(false);
					}
				}
			});
		}
	});



	$(function() {


		avisoPesquisa('Agendamento', 2);

		$('#data_tableatendimentoSenhas').DataTable({
			"paging": true,
			"pageLength": 50,
			"deferRender": true,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			createdRow: function(row, data, dataIndex) {
				if (data[6].trim() == 'Em atendimento') {
					$(row).css({
						"background-color": "#28a7457a",
						"color": "#fff"
					});
					$(row).addClass('sub-needed');
				}
				if (data[7] > 3) {
					$(row).css({
						"background-color": "red",
						"color": "#fff"
					});
					$(row).addClass('sub-needed');
				}

			},
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('atendimentoSenhas/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			},
			dom: 'Bfrtip',
			buttons: [{


					extend: 'print',
					text: "Imprimir",
					title: 'Agendados PACIENTE',

					customize: function(win) {
						$(win.document.body)
							.css('font-size', '12pt')
							.prepend(
								'<div>' +
								'</div>' +
								'<img alt="" style="width:60px" src="<?php echo base_url() . "/imagens/organizacoes/" . session()->logo ?>" style="position:absolute; top:0; left:0;" />'
							);

						$(win.document.body).find('table')
							.addClass('compact')
							.css('font-size', 'inherit');
					},
					exportOptions: {
						columns: [0, 5, 1, 2, 3]
					},

				},
				{
					extend: 'csvHtml5',
					exportOptions: {
						columns: [0, 5, 1, 2, 3]
					}
				},
				{
					extend: 'excelHtml5',
					exportOptions: {
						columns: [0, 5, 1, 2, 3]
					}
				},

			],

		});


		$.ajax({
			url: '<?php echo base_url('atendimentoSenhas/verificaSala') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(verificacaoSalaInicial) {

				if (verificacaoSalaInicial.success === true) {
					document.getElementById("botaoSala").innerHTML = verificacaoSalaInicial.botao;
					//document.getElementById("nomeLocalAtendimento").innerHTML = verificacaoSalaInicial.nomeLocalAtendimento;


				}
			}
		});




	});



	function showDefinirSala() {


		$('#salaAtendimentoModel').modal('show');



		$.ajax({
			url: '<?php echo base_url('departamentos/listaDropDownDepartamentosAtendimento') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(codSalaAtendimentoAdd) {

				$("#codSalaAtendimentoAdd").select2({
					data: codSalaAtendimentoAdd,
				})

				$('#codSalaAtendimentoAdd').val('<?php echo session()->codDepartamento ?>'); // Select the option with a value of '1'
				$('#codSalaAtendimentoAdd').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});



			}
		})


		$("#codSalaAtendimentoAdd").on("change", function() {



			$('#codLocalAtendimentoAdd').html('').select2({
				data: [{
					id: null,
					text: ''
				}]
			});

			if ($(this).val() !== '') {
				codDepartamento = $(this).val();
			} else {
				codDepartamento = 0;
			}

			$.ajax({
				url: '<?php echo base_url('atendimentosLocais/listaDropDownSalasGuichesAtivos') ?>',
				type: 'post',
				dataType: 'json',
				data: {
					codDepartamento: codDepartamento,
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},

				success: function(codLocalAtendimentoAdd) {

					$("#codLocalAtendimentoAdd").select2({
						data: codLocalAtendimentoAdd,
					})



				}
			})


		});




	}



	function gravarSala() {


		$('#salaAtendimentoModel').modal('hide');



		$.ajax({
			url: '<?php echo base_url('atendimentoSenhas/gravarSala') ?>',
			type: 'post',
			data: {
				codDepartamento: $("#salaAtendimentoForm #codSalaAtendimentoAdd").val(),
				codLocalAtendimento: $("#salaAtendimentoForm #codLocalAtendimentoAdd").val(),
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(setaSala) {

				if (setaSala.success === true) {

					$.ajax({
						url: '<?php echo base_url('atendimentoSenhas/verificaSala') ?>',
						type: 'post',
						dataType: 'json',
						data: {
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},
						success: function(verificacaoSala) {

							if (verificacaoSala.success === true) {
								document.getElementById("botaoSala").innerHTML = verificacaoSala.botao;
								document.getElementById("tituloDepartamento").innerHTML = verificacaoSala.nomeDepartamentoAtendimento;

								//document.getElementById("nomeLocalAtendimento").innerHTML = verificacaoSala.nomeLocalAtendimento;


							}
						}
					})

				}
			}
		}).then(function() {
			$('#data_tableatendimentoSenhas').DataTable().ajax.reload(null, false).draw(false);
		})

	}



	function encerrarAtendimento() {
		var form = $('#emAtendimentoForm');

		$.ajax({
			url: '<?php echo base_url('atendimentoSenhas/encerrarAtendimento') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(encerrarAtendimento) {

				if (encerrarAtendimento.success === true) {
					$('#data_tableatendimentoSenhas').DataTable().ajax.reload(null, false).draw(false);
					$('#emAtendimentoModal').modal('hide');
					var Toast = Swal.mixin({
						toast: true,
						position: 'bottom-end',
						showConfirmButton: false,
						timer: 2000
					});
					Toast.fire({
						icon: 'success',
						title: encerrarAtendimento.messages
					})

				}
			}
		}).always(
			Swal.fire({
				title: 'Estamos finalizando o atendimento',
				html: 'Aguarde....',
				timerProgressBar: true,
				didOpen: () => {
					Swal.showLoading()


				}

			}))
	}



	function faltouAtendimento(codSenhaAtendimento) {


		Swal.fire({
			title: 'Você tem certeza que deseja indicar falta do paciente?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {

			if (result.value) {
				$.ajax({
					url: '<?php echo base_url('atendimentoSenhas/encerrarAtendimentoComFalta') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						codSenhaAtendimento: codSenhaAtendimento,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(encerrarAtendimento) {

						if (encerrarAtendimento.success === true) {
							$('#data_tableatendimentoSenhas').DataTable().ajax.reload(null, false).draw(false);
							$('#emAtendimentoModal').modal('hide');
							var Toast = Swal.mixin({
								toast: true,
								position: 'bottom-end',
								showConfirmButton: false,
								timer: 2000
							});
							Toast.fire({
								icon: 'success',
								title: encerrarAtendimento.messages
							})

						}
					}
				}).always(
					Swal.fire({
						title: 'Estamos finalizando o atendimento',
						html: 'Aguarde....',
						timerProgressBar: true,
						didOpen: () => {
							Swal.showLoading()


						}

					}))

			}
		})

	}


	function encerrarAtendimentoAgora(codSenhaAtendimento) {


		$.ajax({
			url: '<?php echo base_url('atendimentoSenhas/encerrarAtendimentoComFalta') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				codSenhaAtendimento: codSenhaAtendimento,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(encerrarAtendimento) {

				if (encerrarAtendimento.success === true) {
					$('#data_tableatendimentoSenhas').DataTable().ajax.reload(null, false).draw(false);
					$('#emAtendimentoModal').modal('hide');
					var Toast = Swal.mixin({
						toast: true,
						position: 'bottom-end',
						showConfirmButton: false,
						timer: 2000
					});
					Toast.fire({
						icon: 'success',
						title: encerrarAtendimento.messages
					})

				}
			}
		}).always(
			Swal.fire({
				title: 'Estamos finalizando o atendimento',
				html: 'Aguarde....',
				timerProgressBar: true,
				didOpen: () => {
					Swal.showLoading()


				}

			}))
	}

	function gerarProtocolo() {
		var form = $('#gerarProtocolosForm');

		$.ajax({
			url: '<?php echo base_url('atendimentoSenhas/gerarProtocolo') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(dadosPessoa) {

				if (dadosPessoa.success === true) {
					$('#data_tableatendimentoSenhas').DataTable().ajax.reload(null, false).draw(false);
					$('#gerarProtocolosModal').modal('hide');

					comprovanteEtiqueta80(dadosPessoa.senha, dadosPessoa.protocolo, dadosPessoa.data)

					var Toast = Swal.mixin({
						toast: true,
						position: 'bottom-end',
						showConfirmButton: false,
						timer: 2000
					});
					Toast.fire({
						icon: 'success',
						title: dadosPessoa.messages
					})

				}
			}
		})
	}

	function gerarProtocoloResultado() {
		var form = $('#gerarProtocolosForm');

		$.ajax({
			url: '<?php echo base_url('atendimentoSenhas/gerarProtocoloResultado') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(dadosPessoa) {

				if (dadosPessoa.success === true) {
					$('#data_tableatendimentoSenhas').DataTable().ajax.reload(null, false).draw(false);
					$('#gerarProtocolosModal').modal('hide');

					comprovanteEtiqueta80(dadosPessoa.senha, dadosPessoa.protocolo, dadosPessoa.data)

					var Toast = Swal.mixin({
						toast: true,
						position: 'bottom-end',
						showConfirmButton: false,
						timer: 2000
					});
					Toast.fire({
						icon: 'success',
						title: dadosPessoa.messages
					})

				}
			}
		})
	}



	function comprovanteEtiqueta80(senha, protocolo, data) {

		//$('#comprovanteEtiqueta80Modal').modal('show');

		document.getElementById("botaoImprimirComprovanteEtiqueta80").onclick = function() {
			printElement(document.getElementById("areaImpressaoComprovanteEtiqueta80"));

			window.print();
		}


		document.getElementById("senhaCompletoComprovanteEtiqueta80").innerHTML = senha;
		document.getElementById("protocoloComprovanteEtiqueta80").innerHTML = protocolo;
		document.getElementById("dataComprovanteEtiqueta80").innerHTML = data;

		var URLComprovante = '<?php echo base_url() . "/atendimentos/?codagendamento=" ?>' + senha;

		document.getElementById("qrcodeComprovanteEtiqueta80").innerHTML = "";
		qrcode = new QRCode("qrcodeComprovanteEtiqueta80", {
			text: URLComprovante,
			width: 160,
			height: 160,
			colorDark: "#000000",
			colorLight: "#ffffff",
			correctLevel: QRCode.CorrectLevel.H
		});


		document.getElementById("setEstilo").innerHTML = '<style>@media screen {' +
			'#printSection {' +
			'display: none;' +

			'}' +
			'}' +

			'@media print {' +
			'@page {' +
			'size: 80mm 150mm;' +
			'margin: 5px;' +
			'}' +

			'body>*:not(#printSection) {' +
			'display: none;' +
			'}' +

			'#printSection,' +
			'#printSection * {' +
			'visibility: visible;' +

			'}' +
			'#printSection {' +
			'position: absolute;' +
			'left: 0;' +
			'top: 0;' +
			'width: 80mm;' +
			'height: 75mm;' +

			'}' +
			'}</style>';


		document.getElementById('botaoImprimirComprovanteEtiqueta80').click();
	}


	function printElement(elem) {
		var domClone = elem.cloneNode(true);

		var $printSection = document.getElementById("printSection");

		if (!$printSection) {
			var $printSection = document.createElement("div");
			$printSection.id = "printSection";
			document.body.appendChild($printSection);
		}

		$printSection.innerHTML = "";

		$printSection.appendChild(domClone);
	}



	function chamarPainelAgora(codSenhaAtendimento) {

		$.ajax({
			url: '<?php echo base_url('atendimentoSenhas/chamarPainelAgora') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				codSenhaAtendimento: codSenhaAtendimento,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(chamarAgora) {

				if (chamarAgora.success === true) {
					$('#data_tableatendimentoSenhas').DataTable().ajax.reload(null, false).draw(false);

					var Toast = Swal.mixin({
						toast: true,
						position: 'bottom-end',
						showConfirmButton: false,
						timer: 5000
					});
					Toast.fire({
						icon: 'success',
						title: chamarAgora.messages,
					})

				} else {
					var Toast = Swal.mixin({
						toast: true,
						position: 'bottom-end',
						showConfirmButton: false,
						timer: 5000
					});
					Toast.fire({
						icon: 'error',
						title: chamarAgora.messages,
					})
				}

			}
		})
	}

	function chamarAtendimentoIniciado(codSenhaAtendimento) {

		$.ajax({
			url: '<?php echo base_url('atendimentoSenhas/verificaSala') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(verificacaoGeraSenha) {

				if (verificacaoGeraSenha.success === true) {

					if (verificacaoGeraSenha.nomeLocalAtendimento !== '') {
						$.ajax({
							url: '<?php echo base_url('atendimentoSenhas/chamarAtendimentoIniciado') ?>',
							type: 'post',
							dataType: 'json',
							data: {
								codSenhaAtendimento: codSenhaAtendimento,
								csrf_sandra: $("#csrf_sandraPrincipal").val(),
							},
							success: function(chamarAtendimentoIniciado) {



								if (chamarAtendimentoIniciado.success === true) {

									$('#data_tableatendimentoSenhas').DataTable().ajax.reload(null, false).draw(false);

									$('#emAtendimentoModal').modal('show');

									document.getElementById("emAtendimentoHtml").innerHTML = chamarAtendimentoIniciado.html;

								} else {

									var Toast = Swal.mixin({
										toast: true,
										position: 'top-end',
										showConfirmButton: false,
										timer: 5000
									});
									Toast.fire({
										icon: 'warning',
										title: chamarAtendimentoIniciado.messages,
									})


								}



							}
						})



					} else {
						var Toast = Swal.mixin({
							toast: true,
							position: 'top-end',
							showConfirmButton: false,
							timer: 2000
						});
						Toast.fire({
							icon: 'warning',
							title: 'Defina o local de atendimento primeiro',
						}).then(function() {
							showDefinirSala();
						})


					}

				}
			}
		});


	}

	function iniciarAtendimento(codSenhaAtendimento) {

		$.ajax({
			url: '<?php echo base_url('atendimentoSenhas/verificaSala') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(verificacaoGeraSenha) {

				if (verificacaoGeraSenha.success === true) {

					if (verificacaoGeraSenha.nomeLocalAtendimento !== '') {
						$.ajax({
							url: '<?php echo base_url('atendimentoSenhas/iniciarAtendimento') ?>',
							type: 'post',
							dataType: 'json',
							data: {
								codSenhaAtendimento: codSenhaAtendimento,
								csrf_sandra: $("#csrf_sandraPrincipal").val(),
							},
							success: function(chamarAtendimentoIniciado) {


								if (chamarAtendimentoIniciado.success === 'info') {

									Swal.fire({
										title: 'Atendimento já iniciado!',
										icon: 'info',
										confirmButtonText: 'Confirmar',
									})

									$('#data_tableatendimentoSenhas').DataTable().ajax.reload(null, false).draw(false);


									preventDefault();


								}

								if (chamarAtendimentoIniciado.success === true) {
									$('#data_tableatendimentoSenhas').DataTable().ajax.reload(null, false).draw(false);


									$('#emAtendimentoModal').modal('show');

									document.getElementById("emAtendimentoHtml").innerHTML = chamarAtendimentoIniciado.html;

								} else {

									var Toast = Swal.mixin({
										toast: true,
										position: 'top-end',
										showConfirmButton: false,
										timer: 5000
									});
									Toast.fire({
										icon: 'warning',
										title: chamarAtendimentoIniciado.messages,
									})


								}



							}
						})



					} else {
						var Toast = Swal.mixin({
							toast: true,
							position: 'top-end',
							showConfirmButton: false,
							timer: 2000
						});
						Toast.fire({
							icon: 'warning',
							title: 'Defina o local de atendimento primeiro',
						}).then(function() {
							showDefinirSala();
						})


					}

				}
			}
		});


	}


	function chamarProximo() {

		$.ajax({
			url: '<?php echo base_url('atendimentoSenhas/verificaSala') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(verificacaoGeraSenha) {

				if (verificacaoGeraSenha.success === true) {

					if (verificacaoGeraSenha.nomeLocalAtendimento !== '') {


						$.ajax({
							url: '<?php echo base_url('atendimentoSenhas/chamarProximo') ?>',
							type: 'post',
							dataType: 'json',
							data: {
								csrf_sandra: $("#csrf_sandraPrincipal").val(),
							},
							success: function(proximo) {
								if (proximo.success === true) {
									$('#data_tableatendimentoSenhas').DataTable().ajax.reload(null, false).draw(false);

									$('#emAtendimentoModal').modal('show');
									chamarPainelAgora(proximo.codSenhaAtendimento);

									document.getElementById("emAtendimentoHtml").innerHTML = proximo.html;


								}
							}
						})




					} else {
						var Toast = Swal.mixin({
							toast: true,
							position: 'top-end',
							showConfirmButton: false,
							timer: 2000
						});
						Toast.fire({
							icon: 'warning',
							title: 'Defina o local de atendimento primeiro',
						}).then(function() {
							showDefinirSala();
						})


					}

				}
			}
		});





	}

	function addatendimentoSenhas() {



		$.ajax({
			url: '<?php echo base_url('atendimentoSenhas/verificaSala') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(verificacaoGeraSenha) {

				if (verificacaoGeraSenha.success === true) {

					if (verificacaoGeraSenha.nomeLocalAtendimento !== '') {

						// reset the form 
						$("#atendimentoSenhasAddForm")[0].reset();
						$(".form-control").removeClass('is-invalid').removeClass('is-valid');
						$('#atendimentoSenhasAddModal').modal('show');
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

								var form = $('#atendimentoSenhasAddForm');
								// remove the text-danger
								$(".text-danger").remove();

								$.ajax({
									url: '<?php echo base_url('atendimentoSenhas/procuraPessoa') ?>',
									type: 'post',
									data: form.serialize(), // /converting the form data into array and sending it to server
									dataType: 'json',
									beforeSend: function() {
										//$('#atendimentoSenhasAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
									},
									success: function(dadosPessoa) {

										if (dadosPessoa.success === true) {
											$('#atendimentoSenhasAddModal').modal('hide');
											$('#gerarProtocolosModal').modal('show');

											document.getElementById("dadosPessoa").innerHTML = dadosPessoa.html;



										} else {

											if (dadosPessoa.messages instanceof Object) {
												$.each(dadosPessoa.messages, function(index, value) {
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
													title: dadosPessoa.messages
												})

											}
										}
										$('#atendimentoSenhasAddForm-btn').html('Adicionar');
									}
								});

								return false;
							}
						});
						$('#atendimentoSenhasAddForm').validate();

					} else {
						var Toast = Swal.mixin({
							toast: true,
							position: 'top-end',
							showConfirmButton: false,
							timer: 2000
						});
						Toast.fire({
							icon: 'warning',
							title: 'Defina o local de atendimento primeiro',
						}).then(function() {
							showDefinirSala();
						})


					}

				}
			}
		});




	}

	function editatendimentoSenhas(codSenhaAtendimento) {
		$.ajax({
			url: '<?php echo base_url('atendimentoSenhas/getOne') ?>',
			type: 'post',
			data: {
				codSenhaAtendimento: codSenhaAtendimento,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#atendimentoSenhasEditForm")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#atendimentoSenhasEditModal').modal('show');

				$("#atendimentoSenhasEditForm #codSenhaAtendimento").val(response.codSenhaAtendimento);
				$("#atendimentoSenhasEditForm #protocolo").val(response.protocolo);
				$("#atendimentoSenhasEditForm #codPaciente").val(response.codPaciente);
				$("#atendimentoSenhasEditForm #cpf").val(response.cpf);
				$("#atendimentoSenhasEditForm #senha").val(response.senha);
				$("#atendimentoSenhasEditForm #codPrioridade").val(response.codPrioridade);
				$("#atendimentoSenhasEditForm #dataInicio").val(response.dataInicio);
				$("#atendimentoSenhasEditForm #codStatus").val(response.codStatus);
				$("#atendimentoSenhasEditForm #dataEncerramento").val(response.dataEncerramento);

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
						var form = $('#atendimentoSenhasEditForm');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('atendimentoSenhas/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#atendimentoSenhasEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
							},
							success: function(response) {

								if (response.success === true) {

									$('#atendimentoSenhasEditModal').modal('hide');


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
										$('#data_tableatendimentoSenhas').DataTable().ajax.reload(null, false).draw(false);
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
								$('#atendimentoSenhasEditForm-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#atendimentoSenhasEditForm').validate();

			}
		});
	}

	function removeatendimentoSenhas(codSenhaAtendimento) {
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
					url: '<?php echo base_url('atendimentoSenhas/remove') ?>',
					type: 'post',
					data: {
						codSenhaAtendimento: codSenhaAtendimento,
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
								$('#data_tableatendimentoSenhas').DataTable().ajax.reload(null, false).draw(false);
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


	function filtrarAgendados() {




		var form = $('#filtroAgendadosForm');
		$.ajax({
			url: '<?php echo base_url('atendimentoSenhas/filtrarAgendados') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(filtrar) {

				if (filtrar.success === true) {
					Swal.close();
					$('#data_tableatendimentoSenhas').DataTable().ajax.reload(null, false).draw(false);



				}
			}
		}).always(
			Swal.fire({
				title: 'Estamos buscando pacienets marcados',
				html: 'Aguarde....',
				timerProgressBar: true,
				didOpen: () => {
					Swal.showLoading()


				}

			}))
	}
</script>