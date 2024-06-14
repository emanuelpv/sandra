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

<style>
	.modal {
		overflow: auto !important;
	}
</style>
<div style="visibility:hidden" id="setEstilo"></div>

<section class="content">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-md-8 mt-2">
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Dispensação</h3>
						</div>



					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codDepartamentoFiltro"> Centro de Custo: <span class="text-danger">*</span> </label>
								<select id="codDepartamentoFiltro" name="codDepartamento" class="custom-select" required>
									<option value=""></option>
									<option value="">TODOS</option>
								</select>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="dataInicioDispensacaoAdd"> Data Início: </label>
								<input type="date" id="dataInicioDispensacaoAdd" name="dataInicio" class="form-control" dateiso="true" value="<?php echo date('Y-m-d'); ?>" required="">
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="dataEncerramentoDispensacaoAdd"> Data Encerramento: </label>
								<input type="date" id="dataEncerramentoDispensacaoAdd" name="dataEncerramento" class="form-control" dateiso="true" value="<?php echo date('Y-m-d', strtotime('+1 days', strtotime(date('Y-m-d')))); ?>" required="">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codCategoriaFiltro"> Categoria: <span class="text-danger">*</span> </label>
								<select id="codCategoriaFiltro" name="codCategoria" class="custom-select" required>
									<option value=""></option>
									<option value="">TODOS</option>
									<option value="1">Medicamentos</option>
									<option value="2">Materiais</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<button type="button" class="btn btn-primary" onclick="filtrarDispensacao()" title="Procurar"> <i class="fas fa-filter"></i>FILTRAR</button>
						</div>
						<div class="col-md-3">
							<button type="button" class="btn btn-block btn-warning" onclick="verCensoInternados()">Censo Internados</button>
						</div>
						<div class="col-md-3">
							<button type="button" class="btn btn-block btn-info" onclick="verEsquemasAntimicrobianos()">Esquemas de Antimicrobianos</button>
						</div>
						<div class="col-md-3">
							<button type="button" class="btn btn-block btn-success" onclick="verDevolucoesMedicamentos()">Devolução de Medicamentos</button>
						</div>

					</div>

					<div style="margin-top:20px" class="col-12 col-sm-12">
						<div class="card card-primary card-tabs">
							<div class="card-header p-0 pt-1">
								<ul class="nav nav-tabs" id="dispensacao-tab" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" id="dispensacao-pendentes-tab" data-toggle="pill" href="#dispensacao-pendentes" role="tab" aria-controls="dispensacao-pendentes" aria-selected="true">Pendentes <span style="margin-left: 10px;" id="qtdPendente" class="right badge badge-warning"></span></a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="emProcessamento-tab" data-toggle="pill" href="#emProcessamento" role="tab" aria-controls="emProcessamento" aria-selected="false">Em Processamento <span style="margin-left: 10px;" id="qtdEmProcessamento" class="right badge badge-warning"></span></a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="dispensados-tab" data-toggle="pill" href="#dispensados" role="tab" aria-controls="dispensados" aria-selected="false">Dispensados <span style="margin-left: 10px;" id="qtdDispensada" class="right badge badge-warning"></span></a>
									</li>
								</ul>
							</div>
							<div class="card-body">

								<div class="tab-content" id="dispensacao-tabContent">
									<div class="tab-pane fade show active" id="dispensacao-pendentes" role="tabpanel" aria-labelledby="dispensacao-pendentes-tab">



										<table id="data_tablePendentesDispensacao" class="table table-striped table-hover table-sm">
											<thead>
												<tr>
													<th>Nº Prescrição</th>
													<th>Paciente</th>
													<th>Local Internação</th>
													<th>Periodo</th>
													<th>Autor</th>
													<th>Status</th>
													<th></th>
												</tr>
											</thead>
										</table>

									</div>
									<div class="tab-pane fade" id="emProcessamento" role="tabpanel" aria-labelledby="emProcessamento-tab">
										<table id="data_tableEmProcessamento" class="table table-striped table-hover table-sm">
											<thead>
												<tr>
													<th>Nº Prescrição</th>
													<th>Paciente</th>
													<th>Local Internação</th>
													<th>Periodo</th>
													<th>Autor</th>
													<th>Status</th>
													<th></th>
												</tr>
											</thead>
										</table>

									</div>
									<div class="tab-pane fade" id="dispensados" role="tabpanel" aria-labelledby="dispensados-tab">
										<table id="data_tableDispensados" class="table table-striped table-hover table-sm">
											<thead>
												<tr>
													<th>Nº Prescrição</th>
													<th>Paciente</th>
													<th>Local Internação</th>
													<th>Periodo</th>
													<th>Autor</th>
													<th>Status</th>
													<th></th>
												</tr>
											</thead>
										</table>

									</div>
								</div>
							</div>
							<!-- /.card -->
						</div>
					</div>
				</div>
				<!-- /.card-body -->
			</div>
			<!-- /.card -->
		</div>
		<!-- /.col -->
	</div>
	<!-- /.row -->
</section>


<div id="esquemasAntimicrobianosModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Esquemas Antimicrobianos</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<center>
					<div style="font-size:20px;font-weight: bold;">ESQUEMAS ANTIMICROBIANOS</div>
				</center>

				<table id="data_tableEsquemasAntimicrobianos" class="table table-striped table-hover table-sm">
					<thead>
						<tr>
							<th>UNIDADE</th>
							<th>LOCAL</th>
							<th>PACIENTE</th>
							<th>IDADE</th>
							<th>ANTIMICROBIANO</th>
							<th>Nº DIAS</th>
							<th>TIMELINE</th>
							<th>ÚLTIMO DIA</th>
							<th></th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>

<div id="sensoInternadosModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white">CENSO INTERNADOS</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-2 text-right">
						<div class="form-group">
							<button style="margin-left:10px" type="button" class="btn btn-block btn-outline-primary btn-lg" onclick="imprimirCensoPacientes()" title="Imprimir Conduta">
								<div><i class="fas fa-print fa-1x" aria-hidden="true"></i></div>
								Imprimir
							</button>
						</div>
					</div>
				</div>
				<div id="areaImpressaoCensoPacientes">
					<div style="margin-left:5px" id="sensoInternadosFarmacia"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="dispensacaoPrescricoesEditModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="dispensacaoPrescricoesEditModalHeader"></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">


				<div id="btnImprimirPrescricao" class="row">

				</div>


				<div class="col-12 col-sm-12">
					<div class="card card-primary card-tabs">
						<div class="card-header p-0 pt-1">
							<ul class="nav nav-tabs" id="prescricoesEdit-tab" role="tablist">


								<li class="nav-item">
									<a class="nav-link active" id="prescricoesEdit-medicamentos-tab" data-toggle="pill" href="#prescricoesEdit-medicamentos" role="tab" aria-controls="prescricoesEdit-medicamentos" aria-selected="false"><span id="labelAbaMedicamentos">Medicamentos</span></a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="prescricoesEdit-material-tab" data-toggle="pill" href="#prescricoesEdit-material" role="tab" aria-controls="prescricoesEdit-material" aria-selected="false"><span id="labelAbaMateriais">Material</span></a>
								</li>
							</ul>
						</div>
						<div class="card-body">
							<div class="tab-content" id="prescricoesEdit-tabContent">


								<div class="tab-pane fade show active" id="prescricoesEdit-medicamentos" role="tabpanel" aria-labelledby="prescricoesEdit-medicamentos-tab">

									<div class="row">
										<span style="margin-left:10px">
											<button class="btn btn-primary verMedicamentos" onclick="liberarMedicamentos()">Liberar Medicamentos</button>
										</span>
										<span style="margin-left:10px">
											<button style="display:none" class="btn btn-success editarMedicamentos" onclick="savarLiberacaoMedicamentos()">Salvar</button>
										</span>
										<span style="margin-left:10px">
											<button style="display:none" class="btn btn-danger editarMedicamentos" onclick="cancelarLiberacaoMedicamentos()">Cancelar</button>
										</span>
									</div>

									<div style="margin-top:10px" class="row">


										<div style="width:100%" class="col-md-12">

											<form id="dispensacaoMedicamentosForm" method="post">

												<input type="hidden" id="<?php echo csrf_token() ?>dispensacaoMedicamentosForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

												<table id="data_tableprescricaoMedicamentos" class="table table-striped table-hover table-sm">
													<thead>
														<tr>
															<th>Código</th>
															<th>Item</th>
															<th>Solicitado</th>
															<th>Liberado</th>
															<th>Und</th>
															<th>Via</th>
															<th>Classificação</th>
															<th>Total</th>
															<th>Status</th>
															<th>Especialista</th>
															<th></th>
														</tr>
													</thead>
												</table>

											</form>
										</div>
									</div>
									<div class="row">
										<span style="margin-left:10px">
											<button class="btn btn-primary verMedicamentos" onclick="liberarMedicamentos()">Liberar Medicamentos</button>
										</span>
										<span style="margin-left:10px">
											<button style="display:none" class="btn btn-success editarMedicamentos" onclick="savarLiberacaoMedicamentos()">Salvar</button>
										</span>
										<span style="margin-left:10px">
											<button style="display:none" class="btn btn-danger editarMedicamentos" onclick="cancelarLiberacaoMedicamentos()">Cancelar</button>
										</span>
									</div>

								</div>


								<div class="tab-pane fade" id="prescricoesEdit-material" role="tabpanel" aria-labelledby="prescricoesEdit-material-tab">

									<div class="row">
										<span style="margin-left:10px">
											<button class="btn btn-primary verMateriais" onclick="liberarMateriais()">Liberar Materiais</button>
										</span>
										<span style="margin-left:10px">
											<button style="display:none" class="btn btn-success editarMateriais" onclick="savarLiberacaoMateriais()">Salvar</button>
										</span>
										<span style="margin-left:10px">
											<button style="display:none" class="btn btn-danger editarMateriais" onclick="cancelarLiberacaoMateriais()">Cancelar</button>
										</span>
									</div>

									<div class="row">
										<div class="col-md-12">
											<form id="dispensacaoMateriaisForm" method="post">

												<input type="hidden" id="<?php echo csrf_token() ?>dispensacaoMateriaisForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

												<table id="data_tableprescricoesMaterial" class="table table-striped table-hover table-sm">
													<thead>
														<tr>
															<th>Código</th>
															<th>Material</th>
															<th>Solicitado</th>
															<th>Liberado</th>
															<th>Especialista</th>
															<th>Ult Atualização</th>
															<th>Status</th>
															<th></th>
														</tr>
													</thead>
												</table>
											</form>
										</div>
									</div>


									<div class="row">
										<span style="margin-left:10px">
											<button class="btn btn-primary verMateriais" onclick="liberarMateriais()">Liberar Materiais</button>
										</span>
										<span style="margin-left:10px">
											<button style="display:none" class="btn btn-success editarMateriais" onclick="savarLiberacaoMateriais()">Salvar</button>
										</span>
										<span style="margin-left:10px">
											<button style="display:none" class="btn btn-danger editarMateriais" onclick="cancelarLiberacaoMateriais()">Cancelar</button>
										</span>
									</div>

								</div>


							</div>
						</div>
						<!-- /.card -->
					</div>
				</div>

				<div id="btnImprimirPrescricaoRodape" class="row"></div>
			</div>
		</div>
	</div>
</div>



<div id="atendimentosPrescricaoImpressaoModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white"></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div style="margin-left:10px;margin-right:10px;margin-bottom:50px" id="areaImpressaoPrescricao" class="modal-body">


				<div class="row">
					<div class="col-md-12">
						<?php echo session()->cabecalhoOficios; ?>
					</div>
				</div>


				<div style="margin-top:20px" class="row">
					<div class="col-md-12">
						<div id="conteudoImpressaoPrescricao"></div>
					</div>
				</div>



				<div style="margin-top:20px" class="row">
					<div class="col-md-12">
						<?php echo session()->rodapeOficios; ?>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>


<div id="atendimentosGuiaAntimicrobianaImpressaoModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white"></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div style="margin-left:50px;margin-right:50px" id="areaImpressaoGuiaAntimicrobiana" class="modal-body">


				<div style="margin-bottom:10px;" class="row">
					<div class="col-md-12">
						<?php echo session()->cabecalhoPrescricao; ?>
					</div>

				</div>
				<div style="margin-bottom:0px;font-size:22px;font-weight: bold;" class="row d-flex justify-content-center">
					<center>
						<div class="col-md-12">
							FICHA DE CONTROLE DE PRESCRIÇÃO DE ANTIMICROBIANOS<br>
							COMISSÃO DE CONTROLE DE INFECÇÃO HOSPITALAR - CCIH
						</div>
					</center>

				</div>


				<div style="margin-top:30px" class="row">
					<div class="col-md-12">
						<div id="conteudoImpressaoGuiaAntimicrobiana"></div>
					</div>
				</div>


				<div class="row">
					<div class="col-md-12">
						<?php echo session()->rodapePrescricao; ?>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<!-- Add modal content -->

<?php
echo view('tema/rodape');
?>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/barcode/JsBarcode.all.min.js"></script>

<script>
	codItemTmp = "";
	codBarraTmp = "";
	var qtdDispensada = 0;
	var qtdEmProcessamento = 0;
	var qtdDispensada = 0;


	$(document).on('show.bs.modal', '.modal', function() {
		var zIndex = 1040 + (10 * $('.modal:visible').length);
		$(this).css('z-index', zIndex);
		setTimeout(function() {
			$('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
		}, 0);
	});


	avisoPesquisa('Farmácia', 2);


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


	$(function() {



		$.ajax({
			url: '<?php echo base_url('departamentos/listaDropDownUnidadesInternacaoCirurgia') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(departamentoItem) {

				$("#codDepartamentoFiltro").select2({
					data: departamentoItem,
				})

				$("#codDepartamentoFiltro").val(null); // Select the option with a value of '1'
				$("#codDepartamentoFiltro").trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});



			}
		})





	});






	function imprimirCensoPacientes() {


		printElement(document.getElementById("areaImpressaoCensoPacientes"));

		document.getElementById("setEstilo").innerHTML = '<style>@media screen {' +
			'#printSection {' +
			'display: none;' +

			'}' +
			'}' +

			'@media print {' +
			'@page {' +
			'size: A4;' +
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
			'width: 210mm;' +
			'height: 297mm;' +

			'}' +
			'}</style>';

		window.print();
	}



	function verEsquemasAntimicrobianos() {

		$('#esquemasAntimicrobianosModal').modal('show');

		titulo = "ESQUEMA ANTIMICROBIANO";

		$('#data_tableEsquemasAntimicrobianos').DataTable({
			"bDestroy": true,
			"pageLength": 200,
			"paging": true,
			"deferRender": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('controleAntimicrobiano/esquemasAntimicrobianos') ?>',
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
					pageSize: 'LEGAL',
					orientation: 'landscape',
					title: titulo,
					//messageTop: labelEspecialidade,
					customize: function(win) {
						$(win.document.body)
							.css('font-size', '8pt')
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

						columns: [0, 1, 2, 3, 4, 5, 6, 7],
					},

				}, {
					extend: 'pdfHtml5',
					orientation: 'landscape',
					pageSize: 'LEGAL',
					title: titulo,
					//messageTop: labelEspecialidade,
					exportOptions: {
						columns: [0, 1, 2, 3, 4, 5, 6, 7],
					}
				},
				{
					extend: 'csvHtml5',
					orientation: 'landscape',
					pageSize: 'LEGAL',
					exportOptions: {
						columns: [0, 1, 2, 3, 4, 5, 6, 7],
					}
				},
				{
					extend: 'excelHtml5',
					exportOptions: {
						columns: [0, 1, 2, 3, 4, 5, 6, 7],
					}
				},

			],
		});

	}

	function verCensoInternados() {



		$.ajax({
			url: '<?php echo base_url('Atendimentos/sensoInternadosFarmacia') ?>',
			type: 'post',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(prescricaosensoInternados) {

				Swal.close();

				$('#sensoInternadosModal').modal('show');
				document.getElementById("sensoInternadosFarmacia").innerHTML = prescricaosensoInternados.html;

			}

		}).always(
			Swal.fire({
				title: 'Estamos buscando os dados dos pacientes internados',
				html: 'Aguarde....',
				timerProgressBar: true,
				didOpen: () => {
					Swal.showLoading()


				}

			}))



	}

	function liberarMedicamentos() {

		$(".editarMedicamentos").show();
		$(".verMedicamentos").hide();

	}

	function cancelarLiberacaoMedicamentos() {

		$(".editarMedicamentos").hide();
		$(".verMedicamentos").show();

	}

	function liberarMateriais() {

		$(".editarMateriais").show();
		$(".verMateriais").hide();

	}

	function cancelarLiberacaoMateriais() {

		$(".editarMateriais").hide();
		$(".verMateriais").show();

	}




	function filtrarDispensacao() {



		var codDepartamento = document.getElementById("codDepartamentoFiltro").value;
		var dataInicio = document.getElementById("dataInicioDispensacaoAdd").value;
		var dataEncerramento = document.getElementById("dataEncerramentoDispensacaoAdd").value;
		var codCategoria = document.getElementById("codCategoriaFiltro").value;


		$.ajax({
			url: '<?php echo base_url('itensFarmacia/filtrarDispensacao') ?>',
			type: 'post',
			data: {
				codDepartamento: codDepartamento,
				codCategoria: codCategoria,
				dataInicio: dataInicio,
				dataEncerramento: dataEncerramento,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(filtrarDispensacao) {


			}

		})


		$('#data_tablePendentesDispensacao').DataTable({
			"bDestroy": true,
			"pageLength": 200,
			"paging": true,
			"deferRender": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('itensFarmacia/pendentesDispensacao') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			},
			"drawCallback": function(settings, json) {
				var api = this.api();
				var qtdPendente = api.rows().count();
				document.getElementById("qtdPendente").innerHTML = qtdPendente
			}
		});

		$('#data_tableEmProcessamento').DataTable({
			"bDestroy": true,
			"pageLength": 200,
			"paging": true,
			"deferRender": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('itensFarmacia/emProcessamentoDispensacao') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			},
			"drawCallback": function(settings, json) {
				var api = this.api();
				qtdEmProcessamento = api.rows().count();

				document.getElementById("qtdEmProcessamento").innerHTML = qtdEmProcessamento
			}
		});

		$('#data_tableDispensados').DataTable({
			"bDestroy": true,
			"pageLength": 200,
			"paging": true,
			"deferRender": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('itensFarmacia/dispensados') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			},
			"drawCallback": function(settings, json) {
				var api = this.api();
				qtdDispensada = api.rows().count();
				document.getElementById("qtdDispensada").innerHTML = qtdDispensada
			}
		});


	}




	function savarLiberacaoMedicamentos() {



		var form = $('#dispensacaoMedicamentosForm');

		$.ajax({
			url: '<?php echo base_url('itensFarmacia/savarLiberacaoMedicamentos') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(savarLiberacaoiaMedicamentos) {
				if (savarLiberacaoiaMedicamentos.success === true) {


					$(".editarMedicamentos").hide();
					$(".verMedicamentos").show();


					$('#data_tableprescricaoMedicamentos').DataTable().ajax.reload(null, false).draw(false);


					var Toast = Swal.mixin({
						toast: true,
						position: 'top-end',
						showConfirmButton: false,
						timer: 2000
					});
					Toast.fire({
						icon: 'success',
						title: savarLiberacaoiaMedicamentos.messages
					})
				}
			}
		})

	}



	function savarLiberacaoMateriais() {



		var form = $('#dispensacaoMateriaisForm');

		$.ajax({
			url: '<?php echo base_url('itensFarmacia/savarLiberacaoMateriais') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(savarLiberacaoiaMateriais) {
				if (savarLiberacaoiaMateriais.success === true) {


					$(".editarMateriais").hide();
					$(".verMateriais").show();


					$('#data_tableprescricoesMaterial').DataTable().ajax.reload(null, false).draw(false);


					var Toast = Swal.mixin({
						toast: true,
						position: 'top-end',
						showConfirmButton: false,
						timer: 2000
					});
					Toast.fire({
						icon: 'success',
						title: savarLiberacaoiaMateriais.messages
					})
				}
			}
		})

	}




	function assinarDispensacao(codAtendimentoPrescricao) {




		$.ajax({
			url: '<?php echo base_url('AtendimentosPrescricoes/assinarDispensacao') ?>',
			type: 'post',
			data: {
				codAtendimentoPrescricao: codAtendimentoPrescricao,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),


			},
			dataType: 'json',
			success: function(resultAssinarDispensacao) {
				if (resultAssinarDispensacao.success === true) {


					$('#data_tablePendentesDispensacao').DataTable().ajax.reload(null, false).draw(false);
					$('#data_tableEmProcessamento').DataTable().ajax.reload(null, false).draw(false);
					$('#data_tableDispensados').DataTable().ajax.reload(null, false).draw(false);

					var Toast = Swal.mixin({
						toast: true,
						position: 'bottom-end',
						showConfirmButton: false,
						timer: 5000
					});
					Toast.fire({
						icon: 'success',
						title: resultAssinarDispensacao.messages,
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
						title: resultAssinarDispensacao.messages,
					})
				}

			}
		})
	}





	function processarDispensacao(codAtendimentoPrescricao) {




		$.ajax({
			url: '<?php echo base_url('AtendimentosPrescricoes/processarDispensacao') ?>',
			type: 'post',
			data: {
				codAtendimentoPrescricao: codAtendimentoPrescricao,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(resultProcessarDispensacao) {
				if (resultProcessarDispensacao.success === true) {


					$('#data_tablePendentesDispensacao').DataTable().ajax.reload(null, false).draw(false);
					$('#data_tableEmProcessamento').DataTable().ajax.reload(null, false).draw(false);
					$('#data_tableDispensados').DataTable().ajax.reload(null, false).draw(false);

					var Toast = Swal.mixin({
						toast: true,
						position: 'bottom-end',
						showConfirmButton: false,
						timer: 5000
					});
					Toast.fire({
						icon: 'success',
						title: resultProcessarDispensacao.messages,
					})
				}


				$('#dispensacaoPrescricoesEditModal').modal('show');



				document.getElementById("btnImprimirPrescricao").innerHTML =
					'<div class="col-md-4">' +
					'<button style="margin-bottom:10px" class="btn btn-success " onclick="imprimirPrescricao(' + codAtendimentoPrescricao + ')" id="imprimirPrescricao">' +
					'<div><i class="fas fa-chart-line" aria-hidden="true"></i>Imprimir pedido</div>' +
					'</button>' +
					'</div>';

				document.getElementById("btnImprimirPrescricaoRodape").innerHTML =
					'<div class="col-md-4">' +
					'<button style="margin-bottom:10px" class="btn btn-success " onclick="imprimirPrescricao(' + codAtendimentoPrescricao + ')" id="imprimirPrescricao">' +
					'<div><i class="fas fa-chart-line" aria-hidden="true"></i>Imprimir pedido</div>' +
					'</button>' +
					'</div>';

				document.getElementById("dispensacaoPrescricoesEditModalHeader").innerHTML = resultProcessarDispensacao.dadosPaciente
				document.getElementById("labelAbaMedicamentos").innerHTML = resultProcessarDispensacao.qtdeMedicamentos
				document.getElementById("labelAbaMateriais").innerHTML = resultProcessarDispensacao.qtdeMateriais


				//MEDICAMENTOS

				$('#data_tableprescricaoMedicamentos').DataTable({
					"bDestroy": true,
					"paging": true,
					"deferRender": true,
					"pageLength": 200,
					"lengthChange": false,
					"searching": true,
					"ordering": false,
					"info": true,
					"autoWidth": false,
					"responsive": false,
					"ajax": {
						"url": '<?php echo base_url('itensFarmacia/prescricaoMedicamentos') ?>',
						"type": "POST",
						"dataType": "json",
						async: "true",
						data: {
							codAtendimentoPrescricao: codAtendimentoPrescricao,
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},
					}
				});




				//MATERIAIS

				$('#data_tableprescricoesMaterial').DataTable({
					"bDestroy": true,
					"paging": true,
					"deferRender": true,
					"lengthChange": false,
					"searching": true,
					"ordering": false,
					"info": true,
					"autoWidth": false,
					"responsive": true,
					"ajax": {
						"url": '<?php echo base_url('itensFarmacia/prescricaoMateriais') ?>',
						"type": "POST",
						"dataType": "json",
						async: "true",
						data: {
							codAtendimentoPrescricao: codAtendimentoPrescricao,
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},
					}
				});




			}
		})
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



	function imprimirGuiaAntimicrobiana(codControleAntimicrobiano) {

		document.getElementById("conteudoImpressaoGuiaAntimicrobiana").innerHTML = '';


		$.ajax({
			url: '<?php echo base_url('controleAntimicrobiano/imprimirGuia') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				codControleAntimicrobiano: codControleAntimicrobiano,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(impressaoGuia) {

				if (impressaoGuia.success === true) {


					document.getElementById("conteudoImpressaoGuiaAntimicrobiana").innerHTML = impressaoGuia.html;

					document.getElementById("setEstilo").innerHTML = '<style>@media screen {' +
						'#printSection {' +
						'display: none;' +

						'}' +
						'}' +

						'@media print {' +
						'@page {' +
						'size: A4;' +
						'margin-top: 30px;' +
						'margin-bottom: 40px;' +
						'margin-left: 15px;' +
						'margin-right: 15px;' +
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
						'width: 297mm;' +
						'height: 210mm;' +

						'}' +
						'}</style>';


					printElement(document.getElementById("areaImpressaoGuiaAntimicrobiana"));
					window.print();

				} else {

					Swal.fire({
						icon: 'warning',
						title: impressaoGuia.messages,
						showConfirmButton: true,
						confirmButtonText: 'Ciente',
					})


				}

			}
		})
	}



	function imprimirPrescricao(codAtendimentoPrescricao) {



		$.ajax({
			url: '<?php echo base_url('itensFarmacia/imprimirPrescricao') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				codAtendimentoPrescricao: codAtendimentoPrescricao,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(impressaoPrescricao) {

				document.getElementById("conteudoImpressaoPrescricao").innerHTML = impressaoPrescricao.html;

				document.getElementById("setEstilo").innerHTML = '<style>@media screen {' +
					'#printSection {' +
					'display: none;' +

					'}' +
					'}' +

					'@media print {' +
					'@page {' +
					'size: A4 landscape;' +
					'margin-top: 30px;' +
					'margin-bottom: 40px;' +
					'margin-left: 15px;' +
					'margin-right: 15px;' +
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
					'width: 297mm;' +
					'height: 210mm;' +

					'}' +
					'}</style>';


				printElement(document.getElementById("areaImpressaoPrescricao"));
				window.print();



			}
		})
	}
</script>