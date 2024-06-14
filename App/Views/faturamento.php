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


<style>
	.modal {
		overflow: auto !important;
	}

	.select2-container {
		z-index: 300000;
	}


	.swal2-container {
		z-index: 9999999;
	}

	.table.dataTable {
		font-family: Verdana, Geneva, Tahoma, sans-serif;
		font-size: 12px;
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
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Faturamento</h3>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<?php
								echo listboxUnidadesFaturamento($this);
								?>
							</div>
						</div>
					</div>

					<div class="col-12 col-sm-12">
						<div class="card card-primary card-tabs">
							<div class="card-header p-0 pt-1">
								<ul class="nav nav-tabs" id="faturamento-tab" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" id="faturamento-emAberto-tab" data-toggle="pill" href="#faturamento-emAberto" role="tab" aria-controls="faturamento-emAberto" aria-selected="true">Contas Abertas</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="faturamento-fechado-tab" data-toggle="pill" href="#faturamento-fechado" role="tab" aria-controls="faturamento-fechado" aria-selected="false">Contas Fechadas (30 Dias)</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="faturamento-buscaAvancada-tab" data-toggle="pill" href="#faturamento-buscaAvancada" role="tab" aria-controls="faturamento-buscaAvancada" aria-selected="false">Busca Avançada</a>
									</li>
								</ul>
							</div>
							<div class="card-body">
								<div class="tab-content" id="faturamento-tabContent">
									<div class="tab-pane fade show active" id="faturamento-emAberto" role="tabpanel" aria-labelledby="faturamento-emAberto-tab">



										<div style="font-size:20px;font-weight:bold" class="text-center">ATENDIMENTOS</div>

										<div style="margin-left:10px">
											<button class="btn btn-primary" onclick="novoAddAtendimentoManual()">Adicionar Atendimento Manual</button>
										</div>

										<table id="data_tableContasAbertas" class="table table-striped table-hover table-sm">
											<thead>
												<tr>
													<th>Nº Atd</th>
													<th>Paciente</th>
													<th>Data</th>
													<th>Local</th>
													<th>Status</th>
													<th>Prescrição</th>
													<th>Última Fatura</th>
													<th></th>
												</tr>
											</thead>
										</table>

									</div>
									<div class="tab-pane fade" id="faturamento-fechado" role="tabpanel" aria-labelledby="faturamento-fechado-tab">
										<table id="data_tableContasFechadas" class="table table-striped table-hover table-sm">
											<thead>
												<tr>
													<th>Nº Atd</th>
													<th>Paciente</th>
													<th>Local Internação</th>
													<th>Status Paciente</th>
													<th>Tempo</th>
													<th>Última Fatura</th>
													<th></th>
												</tr>
											</thead>
										</table>

									</div>

									<div class="tab-pane fade" id="faturamento-buscaAvancada" role="tabpanel" aria-labelledby="faturamento-buscaAvancada-tab">


										<div style="margin-top:30px;margin-bottom:30px" class="row">
											<div class="col-md-8 offset-md-2">
												<div class="input-group input-group-lg">
													<input autocomplete="off" id="buscaAvancadaPacienteAdd" type="search" class="form-control form-control-lg" placeholder="Informe o Nome, CPF, Nº PLANO ou Nº Prontuário">
													<div class="input-group-append">
														<button onclick="buscaAvancadaPaciente()" class="btn btn-lg btn-default">
															<i class="fa fa-search"></i>
														</button>
													</div>
												</div>
											</div>
										</div>

										<table id="data_tableBuscaAvancada" class="table table-striped table-hover table-sm">
											<thead>
												<tr>
													<th>Nº Atd</th>
													<th>Paciente</th>
													<th>Data</th>
													<th>Local</th>
													<th>Status</th>
													<th>Prescrição</th>
													<th>Última Fatura</th>
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
<!-- Add modal content -->






<div id="buscarPacientesModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div id="print-me">
				<div class="modal-header bg-primary text-center p-3">
					<h4 class="modal-title text-white" id="info-header-modalLabel">Buscar Paciente</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">



					<div class="row">
						<div class="col-sm-8 mt-2">
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">PACIENTES</h3>
						</div>

					</div>

					<div style="margin-top:30px;margin-bottom:30px" class="row">
						<div class="col-md-8 offset-md-2">
							<div class="input-group input-group-lg">
								<input autocomplete="off" id="pacienteFiltro" type="search" class="form-control form-control-lg" placeholder="Informe o Nome, CPF, Nº PLANO ou Nº Prontuário">
								<div class="input-group-append">
									<button onclick="localizarPaciente()" class="btn btn-lg btn-default">
										<i class="fa fa-search"></i>
									</button>
								</div>
							</div>
						</div>
					</div>



					<table id="data_tablepaciente" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>Código</th>
								<th>Nome exibição</th>
								<th>cpf</th>
								<th>Nº Plano</th>
								<th>Nº Prontuário</th>
								<th>Status</th>

								<th style="text-align:center">Ações</th>
							</tr>
						</thead>
					</table>


				</div>
			</div>
		</div>
	</div>
</div>


<div id="verFaturasModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Faturas</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<input type="hidden" id="codAtendimentoFaturamento" name="codAtendimentoFaturamento">


				<div class="row">
					<div class="col-md-4">
						<button type="button" class="btn btn-block btn-primary" onclick="addFatura()" title="Gerar Fatura"> <i class="fa fa-plus"></i>Adicionar Fatura</button>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<table id="data_tablefaturamento" class="table table-striped table-hover table-sm">
							<thead>
								<tr>
									<th>Nº Fatura</th>
									<th>Nº Atendimento</th>
									<th>Paciente</th>
									<th>Autor</th>
									<th>Periodo</th>
									<th>Status</th>
									<th></th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div id="faturamentoEditModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">EDITAR FATURA</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">


				<input type="hidden" id="codFatura" name="codFatura">

				<div class="row">
					<div class="col-md-12">
						<div id="dadosFatura">
						</div>
					</div>
				</div>


				<div class="row">
					<div class="col-md-12">
						<div class="card card-secondary">

							<div class="card-header">
								<h3 class="card-title">PERÍODO DAS DESPESAS</h3>

								<div class="card-tools">
									<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
									</button>
								</div>
							</div>
							<div class="card-body">

								<form id="periodoObservacoesForm" method="post">
									<input type="hidden" id="<?php echo csrf_token() ?>periodoObservacoesForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
									<input type="hidden" id="codFaturaSetaPeriodoGlobalAdd" name="codFatura">

									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label for="dataGlobalInicioFaturaAdd"> Data Início: </label>
												<input type="date" id="dataGlobalInicioFaturaAdd" name="dataInicio" class="form-control" dateiso="true" required="">
											</div>
										</div>

										<div class="col-md-3">
											<div class="form-group">
												<label for="dataGlobalEncerramentoFaturaAdd"> Data Encerramento: </label>
												<input type="date" id="dataGlobalEncerramentoFaturaAdd" name="dataEncerramento" class="form-control" dateiso="true" required="">
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label for="observacoesFaturaAdd"> Observações: </label>
												<textarea cols="40" rows="5" id="observacoesFaturaAdd" name="observacoes" class="form-control" placeholder="Observações da Fatura" required></textarea>
											</div>
										</div>

									</div>


									<div class="form-group text-left">
										<div class="btn-group">
											<button type="button" class="btn btn-primary" onclick="savarPeriodoObservacoes()">Gravar</button>
										</div>
									</div>

								</form>

							</div>
						</div>
					</div>
				</div>


				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<style>
								.select2-container--default .select2-selection--multiple .select2-selection__choice {
									background-color: blue;
									border: 1px solid #aaa;
								}
							</style>
							<div>Centro de Custo</div>
							<select required id="codDepartamentoAtendimentoAdd" name="codDepartamento[]" class="custom-select" multiple="multiple" data-placeholder="Selecione um ou mais" style="width: 100%;">
								<option value="0">TODOS</option>
							</select>
						</div>
					</div>
				</div>

				<div style="margin-bottom:10px" class="row">
					<div class="col-md-3">
						<button type="button" class="btn btn-block btn-success btn-importarDespesas" onclick="importarDespesas()" title="Importar Novas Despesas">Importar Novas Despesas</button>
					</div>
					<div class="col-md-3">
						<button type="button" class="btn btn-block btn-danger btn-assinareFecharFatura" onclick="assinareFecharFatura()" title="Importar Novas Despesas">Assinar e Fechar Fatura</button>
					</div>
					<div class="col-md-3">
						<button type="button" class="btn btn-block btn-primary" onclick="imprimirFatura()" title="Importar Novas Despesas">Imprimir Fatura</button>
					</div>
					<div class="col-md-3">
						<button type="button" class="btn btn-block btn-info" onclick="imprimirConsolidado()" title="Importar Novas Despesas">Imprimir Consolidado</button>
					</div>
				</div>

				<div class="row">
					<div class="col-12 col-sm-12">
						<div class="card card-primary card-tabs">

							<div class="card-header p-0 pt-1">
								<ul class="nav nav-tabs" id="faturamento-tab" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" id="faturamento-home-tab" data-toggle="pill" href="#faturamento-home" role="tab" aria-controls="faturamento-home" aria-selected="true">Medicamentos</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="faturamento-taxas-tab" data-toggle="pill" href="#faturamento-taxas" role="tab" aria-controls="faturamento-taxas" aria-selected="false">Taxas e Serviços</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="faturamento-procedimentos-tab" data-toggle="pill" href="#faturamento-procedimentos" role="tab" aria-controls="faturamento-procedimentos" aria-selected="false">Procedimentos</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="faturamento-materiais-tab" data-toggle="pill" href="#faturamento-materiais" role="tab" aria-controls="faturamento-materiais" aria-selected="false">Materiais</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="faturamento-kits-tab" data-toggle="pill" href="#faturamento-kits" role="tab" aria-controls="faturamento-kits" aria-selected="false">Kits</a>
									</li>
								</ul>
							</div>
							<div class="card-body">
								<div class="tab-content" id="faturamento-tabContent">
									<div class="tab-pane fade show active" id="faturamento-home" role="tabpanel" aria-labelledby="faturamento-home-tab">

										<div class="row">
											<span style="margin-left:10px">
												<button class="btn btn-primary ver" onclick="auditar()">Auditar</button>
											</span>
											<span style="margin-left:10px">
												<button style="display:none" class="btn btn-success editar" onclick="savarAuditoriaMedicamentos()">Salvar</button>
											</span>
											<span style="margin-left:10px">
												<button style="display:none" class="btn btn-danger editar" onclick="cancelarAuditoriaMedicamentos()">Cancelar</button>
											</span>
											<span style="margin-left:10px">
												<button class="btn btn-primary ver" onclick="novoMedicamento()">Novo Medicamento</button>
											</span>

											<span style="margin-left:10px">
												<button style="display:none" class="btn btn-primary glosarMedicamento" onclick="glosarMedicamentosEmLoteAgora()">Glosar Selecionados Agora</button>
											</span>
											<span style="margin-left:10px">
												<button style="display:none" class="btn btn-danger glosarMedicamento" onclick="cancelarGlosarMedicamentosEmLoteAgora()">Cancelar</button>
											</span>

											<span style="margin-left:10px">
												<button class="btn btn-dark ver" onclick="glosarEmLote()">Glosar em Lote</button>
											</span>
										</div>

										<form id="faturamentoForm" method="post">

											<input type="hidden" id="<?php echo csrf_token() ?>faturamentoForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">


											<input type="hidden" id="motivoEmLoteAdd" name="motivoEmLote">

											<table id="data_tablefaturamentoMedicamentos" class="table table-striped table-hover table-sm">
												<thead>
													<tr>
														<th>Codigo</th>
														<th>CCusto</th>
														<th>NEE</th>
														<th>Descrição</th>
														<th>Qtde</th>
														<th>Valor</th>
														<th>Subtotal</th>
														<th>Data</th>
														<th>Status</th>
														<th>Anotação</th>

														<th></th>
													</tr>
												</thead>
											</table>
										</form>

									</div>
									<div class="tab-pane fade" id="faturamento-taxas" role="tabpanel" aria-labelledby="faturamento-taxas-tab">



										<div class="row">
											<span style="margin-left:10px">
												<button class="btn btn-primary verTaxas" onclick="auditarTaxas()">Auditar Taxas</button>
											</span>
											<span style="margin-left:10px">
												<button style="display:none" class="btn btn-success editarTaxas" onclick="savarAuditoriaTaxas()">Salvar Taxas</button>
											</span>
											<span style="margin-left:10px">
												<button style="display:none" class="btn btn-danger editarTaxas" onclick="cancelarAuditoriaTaxas()">Cancelar</button>
											</span>
											<span style="margin-left:10px">
												<button class="btn btn-primary verTaxas" onclick="novaTaxaServico()">Nova Taxa Serviço</button>
											</span>
										</div>

										<form id="taxasServicosForm" method="post">

											<input type="hidden" id="<?php echo csrf_token() ?>taxasServicosForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">




											<table id="data_tablefaturamentoTaxasServicos" class="table table-striped table-hover table-sm">
												<thead>
													<tr>
														<th>Código</th>
														<th>CCusto</th>
														<th>DGP</th>
														<th>Taxa/Serviço</th>
														<th>Quantidade</th>
														<th>Valor</th>
														<th>SubTotal</th>
														<th>Período</th>
														<th>Auditor</th>
														<th>Status</th>

														<th></th>
													</tr>
												</thead>
											</table>


										</form>
									</div>
									<div class="tab-pane fade" id="faturamento-procedimentos" role="tabpanel" aria-labelledby="faturamento-procedimentos-tab">


										<div class="row">
											<span style="margin-left:10px">
												<button class="btn btn-primary verProcedimentos" onclick="auditarProcedimentos()">Auditar</button>
											</span>
											<span style="margin-left:10px">
												<button style="display:none" class="btn btn-success editarProcedimentos" onclick="savarAuditoriaProcedimentos()">Salvar</button>
											</span>
											<span style="margin-left:10px">
												<button style="display:none" class="btn btn-danger editarProcedimentos" onclick="cancelarAuditoriaProcedimentos()">Cancelar</button>
											</span>
											<span style="margin-left:10px">
												<button class="btn btn-primary verProcedimentos" onclick="novoProcedimento()">Novo Procedimento</button>
											</span>
										</div>



										<form id="procedimentosForm" method="post">

											<input type="hidden" id="<?php echo csrf_token() ?>procedimentosForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">



											<table id="data_tablefaturamentoProcedimentos" class="table table-striped table-hover table-sm">
												<thead>
													<tr>
														<th>Código</th>
														<th>CCusto</th>
														<th>DGP</th>
														<th>Procedimento</th>
														<th>Quantidade</th>
														<th>Valor</th>
														<th>SubTotal</th>
														<th>Data</th>
														<th>Auditor</th>
														<th>Status</th>

														<th></th>
													</tr>
												</thead>
											</table>
										</form>
									</div>
									<div class="tab-pane fade" id="faturamento-materiais" role="tabpanel" aria-labelledby="faturamento-materiais-tab">



										<div class="row">
											<span style="margin-left:10px">
												<button class="btn btn-primary verMateriais" onclick="auditarMateriais()">Auditar</button>
											</span>
											<span style="margin-left:10px">
												<button style="display:none" class="btn btn-success editarMateriais" onclick="savarAuditoriaMateriais()">Salvar</button>
											</span>
											<span style="margin-left:10px">
												<button style="display:none" class="btn btn-danger editarMateriais" onclick="cancelarAuditoriaMateriais()">Cancelar</button>
											</span>
											<span style="margin-left:10px">
												<button class="btn btn-primary verMateriais" onclick="novoMaterial()">Novo Material</button>
											</span>


											<span style="margin-left:10px">
												<button style="display:none" class="btn btn-primary glosarMateriais" onclick="glosarMateriaisEmLoteAgora()">Glosar Selecionados Agora</button>
											</span>

											<span style="margin-left:10px">
												<button style="display:none" class="btn btn-danger glosarMateriais" onclick="cancelarGlosarMateriaisEmLoteAgora()">Cancelar</button>
											</span>

											<span style="margin-left:10px">
												<button class="btn btn-dark verMateriais" onclick="glosarMateriaisEmLote()">Glosar em Lote</button>
											</span>
										</div>



										<form id="materiaisForm" method="post">

											<input type="hidden" id="<?php echo csrf_token() ?>materiaisForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">



											<input type="hidden" id="motivoGlosarMaterialEmLoteAdd" name="motivoEmLote">

											<table id="data_tablefaturamentoMateriais" class="table table-striped table-hover table-sm">
												<thead>
													<tr>
														<th>Código</th>
														<th>CCusto</th>
														<th>DGP</th>
														<th>Procedimento</th>
														<th>Quantidade</th>
														<th>Valor</th>
														<th>SubTotal</th>
														<th>Data</th>
														<th>Auditor</th>
														<th>Status</th>

														<th></th>
													</tr>
												</thead>
											</table>
										</form>

									</div>
									<div class="tab-pane fade" id="faturamento-kits" role="tabpanel" aria-labelledby="faturamento-kits-tab">



										<div class="row">
											<span style="margin-left:10px">
												<button class="btn btn-primary verKits" onclick="auditarKits()">Auditar</button>
											</span>
											<span style="margin-left:10px">
												<button style="display:none" class="btn btn-success editarKits" onclick="savarAuditoriaKits()">Salvar</button>
											</span>
											<span style="margin-left:10px">
												<button style="display:none" class="btn btn-danger editarKits" onclick="cancelarAuditoriaKits()">Cancelar</button>
											</span>
											<span style="margin-left:10px">
												<button class="btn btn-primary verKits" onclick="novoKit()">Novo Kit</button>
											</span>

											<span style="margin-left:10px">
												<button style="display:none" class="btn btn-primary glosarKits" onclick="glosarKitsEmLoteAgora()">Glosar Selecionados Agora</button>
											</span>

											<span style="margin-left:10px">
												<button style="display:none" class="btn btn-danger glosarKits" onclick="cancelarGlosarKitsEmLoteAgora()">Cancelar</button>
											</span>

											<span style="margin-left:10px">
												<button class="btn btn-dark verKits" onclick="glosarKitsEmLote()">Glosar em Lote</button>
											</span>



										</div>



										<form id="kitsForm" method="post">

											<input type="hidden" id="<?php echo csrf_token() ?>kitsForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

											<input type="hidden" id="motivoGlosarKitEmLoteAdd" name="motivoEmLote">


											<table id="data_tablefaturamentoKits" class="table table-striped table-hover table-sm">
												<thead>
													<tr>
														<th>Código</th>
														<th>CCusto</th>
														<th>DGP</th>
														<th>Procedimento</th>
														<th>Quantidade</th>
														<th>Valor</th>
														<th>SubTotal</th>
														<th>Data</th>
														<th>Auditor</th>
														<th>Status</th>
														<th></th>
													</tr>
												</thead>
											</table>
										</form>


									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div id="imprimirFaturaModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">IMPRIMIR FATURA</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-2 text-right">
						<div class="form-group">
							<button style="margin-left:10px" type="button" class="btn btn-block btn-outline-primary btn-lg" onclick="imprimirFaturaAgora()" title="Imprimir Fatura">
								<div><i class="fas fa-print fa-1x" aria-hidden="true"></i></div>
								Imprimir
							</button>
						</div>
					</div>
				</div>

				<div style="margin-left:20px;margin-right:20px" id="areaImpressaoFatura" class="modal-body">


					<div class="row">
						<div class="col-md-12">
							<?php echo session()->cabecalhoOficios; ?>
						</div>
					</div>



					<div style="margin-top:30px" class="row">
						<div class="col-md-12">
							<div id="dadosFaturaImprimir"></div>
						</div>
					</div>





					<div style="margin-top:30px" class="row">
						<div class="col-md-12">
							<?php echo session()->rodapeOficios; ?>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>

<div id="imprimirConsolidadoModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">IMPRIMIR CONSOLIDADO</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-2 text-right">
						<div class="form-group">
							<button style="margin-left:10px" type="button" class="btn btn-block btn-outline-primary btn-lg" onclick=" imprimirFaturaConsolidadoAgora()" title="Imprimir Fatura">
								<div><i class="fas fa-print fa-1x" aria-hidden="true"></i></div>
								Imprimir
							</button>
						</div>
					</div>
				</div>

				<div style="margin-left:20px;margin-right:20px" id="areaImpressaoConsolidado" class="modal-body">



					<div style="margin-top:30px" class="row">
						<div class="col-md-12">
							<div id="dadosConsolidadoImprimir"></div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>

<div id="faturamentoMedicamentosAddModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Medicamento</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="faturamentoMedicamentosAddForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>faturamentoMedicamentosAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codFaturamentoMedicamento" name="codFaturamentoMedicamento" class="form-control" placeholder="Codigo" maxlength="11" required>
						<input type="hidden" id="codFaturaAddItem" name="codFatura" class="form-control" placeholder="codFatura" maxlength="11" required>
						<input type="hidden" id="codAtendimentoAdd" name="codAtendimento" class="form-control" placeholder="codFatura" maxlength="11" required>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="codDepartamentoMedicamentoAdd"> Departamento: <span class="text-danger">*</span> </label>
								<select required id="codDepartamentoMedicamentoAdd" name="codDepartamento" class="custom-select" required>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="codLocalMedicamentoAdd"> Local: <span class="text-danger">*</span> </label>
								<select required id="codLocalMedicamentoAdd" name="codLocalAtendimento" class="custom-select" required>
								</select>
							</div>
						</div>
					</div>


					<div class="row">

						<div class="col-md-4">
							<div class="form-group">
								<label for="codMedicamentoPrescritoAdd"> Item: <span class="text-danger">*</span> </label>
								<select id="codMedicamentoPrescritoAdd" name="codMedicamento" class="custom-select" required>
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="quantidade"> Quantidade: <span class="text-danger">*</span> </label>
								<input type="number" id="quantidade" name="quantidade" class="form-control" placeholder="Quantidade" maxlength="11" number="true" required>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="quantidade"> Data Despesa: <span class="text-danger">*</span> </label>
								<input type="date" id="dataDespesaMedicamentoAdd" name="dataPrescricao" class="form-control" dateiso="true" required="">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="observacoes"> Observações: </label>
								<textarea cols="40" rows="5" id="observacoes" name="observacoes" class="form-control" placeholder="Observacoes"></textarea>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="faturamentoMedicamentosAddForm-btn">Adicionar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>


<div id="faturamentoMedicamentosEditModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Faturamento de Medicamentos</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="faturamentoMedicamentosEditForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>faturamentoMedicamentosEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codFaturamentoMedicamento" name="codFaturamentoMedicamento" class="form-control" placeholder="Codigo" maxlength="11" required>
					</div>

					<div class="row">

						<div class="col-md-4">
							<div class="form-group">
								<label for="quantidade"> Quantidade: <span class="text-danger">*</span> </label>
								<input type="number" id="quantidade" name="quantidade" class="form-control" placeholder="Quantidade" maxlength="11" number="true" required>
							</div>
						</div>


						<div class="col-md-4">
							<div class="form-group">
								<label for="codStatusMedicamentoAdd"> Status: <span class="text-danger">*</span> </label>
								<select required id="codStatusMedicamentoAdd" name="codStatus" class="custom-select" required>
								</select>
							</div>
						</div>


					</div>
					<div class="row">

						<div class="col-md-6">
							<div class="form-group">
								<label for="observacoes"> Observacoes: </label>
								<textarea cols="40" rows="5" id="observacoes" name="observacoes" class="form-control" placeholder="Observacoes"></textarea>
							</div>
						</div>
					</div>
					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar">Salvar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>

			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>


<div id="faturamentoTaxasServicosAddModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Taxa e Serviço</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="faturamentoTaxasServicosAddForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>faturamentoTaxasServicosAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codFaturamentoTaxasServico" name="codFaturamentoTaxasServico" class="form-control" placeholder="Código" maxlength="11" required>
						<input type="hidden" id="codFaturaAddTaxasServico" name="codFatura" class="form-control" placeholder="Código" maxlength="11" required>
						<input type="hidden" id="codAtendimentoAddTaxasServico" name="codAtendimento" class="form-control" placeholder="codFatura" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="codDepartamentoTaxaServicoAdd"> Departamento: <span class="text-danger">*</span> </label>
								<select required id="codDepartamentoTaxaServicoAdd" name="codDepartamento" class="custom-select" required>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="codLocalTaxaServicoAdd"> Local: <span class="text-danger">*</span> </label>
								<select required id="codLocalTaxaServicoAdd" name="codLocalAtendimento" class="custom-select" required>
								</select>
							</div>
						</div>
					</div>
					<div class="row">



						<div class="col-md-4">
							<div class="form-group">
								<label for="codTaxaServicoAdd"> Item: <span class="text-danger">*</span> </label>
								<select id="codTaxaServicoAdd" name="codTaxaServico" class="custom-select" required>
									<option value=""></option>
								</select>
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
						<div class="col-md-3">
							<div class="form-group">
								<label for="dataInicioTaxaServicoAdd"> Data Início: </label>
								<input type="date" id="dataInicioTaxaServicoAdd" name="dataInicio" class="form-control" dateiso="true" required="">
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label for="dataEncerramentoTaxaServicoAdd"> Data Encerramento: </label>
								<input type="date" id="dataEncerramentoTaxaServicoAdd" name="dataEncerramento" class="form-control" dateiso="true" required="">
							</div>
						</div>

					</div>

					<div class="row">

						<div class="col-md-6">
							<div class="form-group">
								<label for="observacoes"> Observacoes: </label>
								<textarea cols="40" rows="5" id="observacoes" name="observacoes" class="form-control" placeholder="Observacoes"></textarea>
							</div>
						</div>
					</div>





					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="faturamentoTaxasServicosAddForm-btn">Adicionar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>

<div id="gerarFaturasModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Gerar Fatura</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">



				<div class="row">
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-12">
								<div class="card card-secondary">

									<div class="card-header">
										<h3 class="card-title">PERÍODO DAS DESPESAS</h3>

										<div class="card-tools">
											<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
											</button>
										</div>
									</div>
									<div class="card-body">

										<div id="periodoDataInicioDataFimDiv" class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="dataInicioFaturaAdd"> Data Início: </label>
													<input type="date" id="dataInicioFaturaAdd" name="dataInicio" class="form-control" dateiso="true" required="">
												</div>
											</div>

											<div class="col-md-4">
												<div class="form-group">
													<label for="dataEncerramentoFaturaAdd"> Data Encerramento: </label>
													<input type="date" id="dataEncerramentoFaturaAdd" name="dataEncerramento" class="form-control" dateiso="true" required="">
												</div>
											</div>

										</div>

										<div class="row" data-toggle="tooltip" data-placement="top" title="Busca todas as depessas não processadas aindas, independente do período!">

											<div class="icheck-primary d-inline center">
												<style>
													input[type=checkbox] {
														transform: scale(1.8);
														margin-left: 10px
													}
												</style>
												<input id="todoPeriodoAdd" name="taxasServicos" type="checkbox" onchange="valueChanged()">
											</div><span style="margin-left:10px"> TODO PERÍODO</span>
										</div>
									</div>
								</div>
							</div>

						</div>
					</div>


					<div class="col-md-6">
						<div class="card card-secondary">

							<div class="card-header">
								<h3 class="card-title">TIPO DE DESPESAS</h3>

								<div class="card-tools">
									<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
									</button>
								</div>
							</div>
							<div class="card-body">

								<div class="row">
									<span>TAXAS DE USOS E SERVIÇOS</span>
									<div class="icheck-primary d-inline center">
										<style>
											input[type=checkbox] {
												transform: scale(1.8);
												margin-left: 10px
											}
										</style>
										<input id="taxasServicosAdd" name="taxasServicos" type="checkbox" checked>
									</div> <span id="statusTaxasServicoes"></span><span id="quantidadeTaxasServicoes"></span>
								</div>


								<div class="row">
									<span>MATERIAIS MÉDICOS</span>
									<div class="icheck-primary d-inline center">
										<style>
											input[type=checkbox] {
												transform: scale(1.8);
												margin-left: 10px
											}
										</style>
										<input id="materiaisAdd" name="materiais" type="checkbox" checked>
									</div> <span id="statusMateriais"></span><span id="quantidadeMateriais"></span>
								</div>

								<div class="row">
									<span>PROCEDIMENTOS</span>
									<div class="icheck-primary d-inline center">
										<style>
											input[type=checkbox] {
												transform: scale(1.8);
												margin-left: 10px
											}
										</style>
										<input id="procedimentosAdd" name="procedimentos" type="checkbox" checked>
									</div> <span id="statusProcedimentos"></span><span id="quantidadeProcedimentos"></span>
								</div>

								<div class="row">
									<span>MEDICAMENTOS</span>
									<div class="icheck-primary d-inline center">
										<style>
											input[type=checkbox] {
												transform: scale(1.8);
												margin-left: 10px
											}
										</style>
										<input id="medicamentosAdd" name="medicamentos" type="checkbox" checked>
									</div> <span id="statusMedicamentos"></span><span id="quantidadeMedicamentos"></span>
								</div>


								<div class="row">
									<span>KITS</span>
									<div class="icheck-primary d-inline center">
										<style>
											input[type=checkbox] {
												transform: scale(1.8);
												margin-left: 10px
											}
										</style>
										<input id="kitsAdd" name="kits" type="checkbox" checked>
									</div> <span id="statusKits"></span><span id="quantidadeKits"></span>
								</div>


							</div>
						</div>
					</div>


				</div>


				<div class="row">
					<div class="col-md-4">
						<button type="button" class="btn btn-block btn-primary" onclick="buscarDespesas()" title="Gerar Fatura"></i>BUSCAR DESPESAS</button>
					</div>

					<div class="col-md-4">
						<button type="button" class="btn btn-block btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">FECHAR</button>

					</div>

				</div>



			</div>
		</div>
	</div>
</div>


<div id="faturamentoProcedimentosAddModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Procedimento</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="faturamentoProcedimentosAddForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>faturamentoProcedimentosAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codFaturamentoProcedimento" name="codFaturamentoProcedimento" class="form-control" placeholder="Codigo" maxlength="11" required>
						<input type="hidden" id="codFaturaProcedimentoAddItem" name="codFatura" class="form-control" placeholder="codFatura" maxlength="11" required>
						<input type="hidden" id="codAtendimentoProcedimentoAdd" name="codAtendimento" class="form-control" placeholder="codFatura" maxlength="11" required>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="codDepartamentoProcedimentoAdd"> Departamento: <span class="text-danger">*</span> </label>
								<select required id="codDepartamentoProcedimentoAdd" name="codDepartamento" class="custom-select" required>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="codLocalProcedimentoAdd"> Local: <span class="text-danger">*</span> </label>
								<select required id="codLocalProcedimentoAdd" name="codLocalAtendimento" class="custom-select" required>
								</select>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="codProcedimentoAdd"> Procedimento: <span class="text-danger">*</span> </label>
								<select id="codProcedimentoAdd" name="codProcedimento" class="custom-select" required>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="quantidade"> Quantidade: <span class="text-danger">*</span> </label>
								<input type="number" id="quantidade" name="quantidade" class="form-control" placeholder="Quantidade" maxlength="11" number="true" required>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="dataDespesaProcedimentoAdd"> Data Despesa: <span class="text-danger">*</span> </label>
								<input type="date" id="dataDespesaProcedimentoAdd" name="dataPrescricao" class="form-control" dateiso="true" required="">
							</div>
						</div>

					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="observacoes"> Observações: </label>
								<textarea cols="40" rows="5" id="observacoes" name="observacoes" class="form-control" placeholder="Observacoes"></textarea>
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
</div>




<div id="faturamentoProcedimentosEditModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Editar Procedimento</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="faturamentoProcedimentosEditForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>faturamentoProcedimentosEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codFaturamentoProcedimentoEdit" name="codFaturamentoProcedimento" class="form-control" placeholder="Codigo" maxlength="11" required>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="codDepartamentoProcedimentoEdit"> Departamento: <span class="text-danger">*</span> </label>
								<select required id="codDepartamentoProcedimentoEdit" name="codDepartamento" class="custom-select" required>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="codLocalProcedimentoEdit"> Local: <span class="text-danger">*</span> </label>
								<select required id="codLocalProcedimentoEdit" name="codLocalAtendimento" class="custom-select" required>
								</select>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="codProcedimentoEdit"> Procedimento: <span class="text-danger">*</span> </label>
								<select id="codProcedimentoEdit" name="codProcedimento" class="custom-select" required>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="quantidadeProcedimentoEdit"> Quantidade: <span class="text-danger">*</span> </label>
								<input type="number" id="quantidadeProcedimentoEdit" name="quantidade" class="form-control" placeholder="Quantidade" maxlength="11" number="true" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="observacoesProcedimentoEdit"> Observações: </label>
								<textarea cols="40" rows="5" id="observacoesProcedimentoEdit" name="observacoes" class="form-control" placeholder="Observacoes"></textarea>
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
</div>



<div id="faturamentoMateriaisAddModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Material</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="faturamentoMateriaisAddForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>faturamentoMateriaisAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codFaturamentoMaterial" name="codFaturamentoMaterial" class="form-control" placeholder="Codigo" maxlength="11" required>
						<input type="hidden" id="codFaturaMaterialAddItem" name="codFatura" class="form-control" placeholder="codFatura" maxlength="11" required>
						<input type="hidden" id="codAtendimentoMaterialAdd" name="codAtendimento" class="form-control" placeholder="codFatura" maxlength="11" required>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="codDepartamentoMaterialAdd"> Departamento: <span class="text-danger">*</span> </label>
								<select required id="codDepartamentoMaterialAdd" name="codDepartamento" class="custom-select" required>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="codLocalMaterialAdd"> Local: <span class="text-danger">*</span> </label>
								<select required id="codLocalMaterialAdd" name="codLocalAtendimento" class="custom-select" required>
								</select>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="codMaterialAdd"> Material: <span class="text-danger">*</span> </label>
								<select id="codMaterialAdd" name="codMaterial" class="custom-select" required>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="quantidade"> Quantidade: <span class="text-danger">*</span> </label>
								<input type="number" id="quantidadeMaterial" name="quantidade" class="form-control" placeholder="Quantidade" maxlength="11" number="true" required>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="quantidade"> Data Despesa: <span class="text-danger">*</span> </label>
								<input type="date" id="dataDespesaMaterialAdd" name="dataPrescricao" class="form-control" dateiso="true" required="">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="observacoes"> Observações: </label>
								<textarea cols="40" rows="5" id="observacoesMaterial" name="observacoes" class="form-control" placeholder="Observacoes"></textarea>
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
</div>


<div id="faturamentoKitsAddModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Kit</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="faturamentoKitsAddForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>faturamentoKitsAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codFaturamentoKit" name="codFaturamentoKit" class="form-control" placeholder="Codigo" maxlength="11" required>
						<input type="hidden" id="codFaturaKitAddItem" name="codFatura" class="form-control" placeholder="codFatura" maxlength="11" required>
						<input type="hidden" id="codAtendimentoKitAdd" name="codAtendimento" class="form-control" placeholder="codFatura" maxlength="11" required>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="codDepartamentoKitAdd"> Departamento: <span class="text-danger">*</span> </label>
								<select required id="codDepartamentoKitAdd" name="codDepartamento" class="custom-select" required>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="codLocalKitAdd"> Local: <span class="text-danger">*</span> </label>
								<select required id="codLocalKitAdd" name="codLocalAtendimento" class="custom-select" required>
								</select>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="codKitAdd"> Kit: <span class="text-danger">*</span> </label>
								<select id="codKitAdd" name="codKit" class="custom-select" required>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="quantidadeKit"> Quantidade: <span class="text-danger">*</span> </label>
								<input type="number" id="quantidadeKit" name="quantidade" class="form-control" placeholder="Quantidade" maxlength="11" number="true" required>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="dataDespesaKitAdd"> Data Despesa: <span class="text-danger">*</span> </label>
								<input type="date" id="dataDespesaKitAdd" name="dataPrescricao" class="form-control" dateiso="true" required="">
							</div>
						</div>


					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="observacoesKit"> Observações: </label>
								<textarea cols="40" rows="5" id="observacoesKit" name="observacoes" class="form-control" placeholder="Observacoes"></textarea>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="faturamentoKitsAddForm-btn">Adicionar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>


<div id="faturamentoKitsEditModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Editar Kit</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="faturamentoKitsEditForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>faturamentoKitsEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codFaturamentoKitEdit" name="codFaturamentoKit" class="form-control" placeholder="Codigo" maxlength="11" required>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="codDepartamentoKitEdit"> Departamento: <span class="text-danger">*</span> </label>
								<select required id="codDepartamentoKitEdit" name="codDepartamento" class="custom-select" required>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="codLocalKitEdit"> Local: <span class="text-danger">*</span> </label>
								<select required id="codLocalKitEdit" name="codLocalAtendimento" class="custom-select" required>
								</select>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="codKitEdit"> Kit: <span class="text-danger">*</span> </label>
								<select id="codKitEdit" name="codKit" class="custom-select" required>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="quantidadeKitEdit"> Quantidade: <span class="text-danger">*</span> </label>
								<input type="number" id="quantidadeKitEdit" name="quantidade" class="form-control" placeholder="Quantidade" maxlength="11" number="true" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="observacoesKitEdit"> Observações: </label>
								<textarea cols="40" rows="5" id="observacoesKitEdit" name="observacoes" class="form-control" placeholder="Observacoes"></textarea>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="faturamentoKitsEditForm-btn">Adicionar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>


<div id="faturamentoMateriaisEditModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Editar Material</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="faturamentoMateriaisEditForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>faturamentoMateriaisEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codFaturamentoMaterialEdit" name="codFaturamentoMaterial" class="form-control" placeholder="Codigo" maxlength="11" required>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="codDepartamentoMaterialEdit"> Departamento: <span class="text-danger">*</span> </label>
								<select required id="codDepartamentoMaterialEdit" name="codDepartamento" class="custom-select" required>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="codLocalMaterialEdit"> Local: <span class="text-danger">*</span> </label>
								<select required id="codLocalMaterialEdit" name="codLocalAtendimento" class="custom-select" required>
								</select>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="codMaterialEdit"> Material: <span class="text-danger">*</span> </label>
								<select id="codMaterialEdit" name="codMaterial" class="custom-select" required>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="quantidadeMaterialEdit"> Quantidade: <span class="text-danger">*</span> </label>
								<input type="number" id="quantidadeMaterialEdit" name="quantidade" class="form-control" placeholder="Quantidade" maxlength="11" number="true" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="observacoesMaterialEdit"> Observações: </label>
								<textarea cols="40" rows="5" id="observacoesMaterialEdit" name="observacoes" class="form-control" placeholder="Observacoes"></textarea>
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
</div>




<div id="atendimentoManualAddModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atendimento manual</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="atendimentoManualAddForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>atendimentoManualAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">



					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="codDepartamentoAtendimentoManualAdd"> Centro de Custo: <span class="text-danger">*</span> </label>
								<select required id="codDepartamentoAtendimentoManualAdd" name="codDepartamento" class="custom-select" required>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="codLocalAtendimentoManualAdd"> Local: <span class="text-danger">*</span> </label>
								<select required id="codLocalAtendimentoManualAdd" name="codLocalAtendimento" class="custom-select" required>
								</select>
							</div>
						</div>
					</div>
					<div style="background:#ececec" class="card">
						<div class="col-md-12">
							<b>PERÍOCO DA DESPESA</b>
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="dataInicioAtendimentoAdd"> Data Início: </label>
										<input type="date" id="dataInicioAtendimentoAdd" name="dataInicio" class="form-control" dateiso="true" required="">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="dataEncerramentoAtendimentoAdd"> Data Encerramento: </label>
										<input type="date" id="dataEncerramentoAtendimentoAdd" name="dataEncerramento" class="form-control" dateiso="true" required="">
									</div>
								</div>

							</div>
						</div>
					</div>
					<div class="row">

						<div class="col-md-4">
							<div class="form-group">
								<label for="codPacienteAtendimentoManualAdd"> Paciente: <span class="text-danger">*</span> </label>
								<div id="labelPacienteAtendimentoManual"></div>
								<div>
									<a class="btn btn-primary" onclick="selecionarPaciente()">Selecionar Paciente</a>
								</div>
								<input type="hidden" id="codPacienteAtendimentoManualAdd" name="codPaciente" required>

							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="observacoes"> Observações: </label>
								<textarea cols="40" rows="5" id="observacoes" name="observacoes" class="form-control" placeholder="Observacoes"></textarea>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="faturamentoMedicamentosAddForm-btn">Adicionar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>

<?php
echo view('tema/rodape');
?>
<script>
	codFaturaTmp = "";
	codAtendimentoTmp = "";
	codLocalAtendimentoTmp = null;
	codDepartamentoTmp = null;
	procedimentosTmp = null;
	materiaisTmp = null;
	kitsTmp = null;
	dataDespesaMedicamentoTmp = null;
	dataDespesaTaxaServicoTmp = null;
	dataDespesaProcedimentoTmp = null;
	dataDespesaMaterialTmp = null;
	dataDespesaKitTmp = null;

	$(document).on('show.bs.modal', '.modal', function() {
		var zIndex = 1040 + (10 * $('.modal:visible').length);
		$(this).css('z-index', zIndex);
		setTimeout(function() {
			$('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
		}, 0);
	});


	var codDepartamento = null;




	$(function() {

		avisoPesquisa('Faturamento', 2);

		$("#codDepartamentoFiltro").on("change", function() {

			var codDepartamento = document.getElementById("codDepartamentoFiltro").value;



			$('#data_tableContasAbertas').DataTable({
				"bDestroy": true,
				"pageLength": 50,
				"paging": true,
				"deferRender": true,
				"lengthChange": false,
				"searching": true,
				"ordering": true,
				"info": true,
				"autoWidth": false,
				"responsive": true,
				"order": [
					[0, "asc"]
				],
				"ajax": {
					"url": '<?php echo base_url('faturamento/contasAbertas') ?>',
					"type": "POST",
					"dataType": "json",
					async: "true",
					data: {
						codDepartamento: codDepartamento,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
				}
			});

			$('#data_tableContasFechadas').DataTable({
				"bDestroy": true,
				"pageLength": 50,
				"paging": true,
				"deferRender": true,
				"lengthChange": false,
				"searching": true,
				"ordering": true,
				"info": true,
				"autoWidth": false,
				"responsive": true,
				"ajax": {
					"url": '<?php echo base_url('faturamento/contasFechadas') ?>',
					"type": "POST",
					"dataType": "json",
					async: "true",
					data: {
						codDepartamento: codDepartamento,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
				}
			});






		});



	});



	function buscaAvancadaPaciente() {

		var paciente = document.getElementById("buscaAvancadaPacienteAdd").value;

		if (paciente !== null && paciente !== '' && paciente !== ' ') {

			$('#data_tableBuscaAvancada').DataTable({
				"bDestroy": true,
				"paging": true,
				"deferRender": true,
				"processing": true,
				"language": {
					"processing": '<?php echo '<img style="width:150px; height:150px;"  src="' .   base_url('/imagens/loading2.gif') . '" >' ?>',
				},
				"lengthChange": false,
				"searching": true,
				"ordering": false,
				"info": true,
				"autoWidth": false,
				"responsive": true,
				"ajax": {
					"url": '<?php echo base_url('faturamento/buscaAvancada') ?>',
					"type": "post",
					"dataType": "json",
					async: "true",
					data: {
						paciente: paciente,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
				}
			});
		}

	}






	function valueChanged() {
		if ($('#todoPeriodoAdd').is(":checked")) {
			$("#periodoDataInicioDataFimDiv").hide();

		} else
			$("#periodoDataInicioDataFimDiv").show();

	}


	function addFatura() {




		codAtendimento = document.getElementById("codAtendimentoFaturamento").value;

		swal.close();


		$.ajax({
			url: '<?php echo base_url('faturamento/vefificaExistenciaDespesas') ?>',
			type: 'post',
			data: {
				codAtendimento: codAtendimento,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(vefificaExistenciaDespesas) {

				swal.close();

				if (vefificaExistenciaDespesas.success === true) {

					Swal.fire({
						title: 'Existem despesas aguardando auditoria e faturamento?',
						html: 'Você tem certeza que deseja gerar uma nova fatura?',
						icon: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Confirmar',
						cancelButtonText: 'Cancelar',
						didOpen: () => {
							Swal.hideLoading()
						}
					}).then((result) => {

						if (result.value) {


							$.ajax({
								url: '<?php echo base_url('faturamento/add') ?>',
								type: 'post',
								data: {
									codAtendimento: codAtendimento,
									csrf_sandra: $("#csrf_sandraPrincipal").val(),
								},
								dataType: 'json',
								success: function(addFatura) {

									if (addFatura.success === true) {

										editfaturamento(addFatura.codFatura);
										$('#data_tablefaturamento').DataTable().ajax.reload(null, false).draw(false);
										$('#data_tableContasAbertas').DataTable().ajax.reload(null, false).draw(false);



										var Toast = Swal.mixin({
											toast: true,
											position: 'top-end',
											showConfirmButton: false,
											timer: 3000
										});
										Toast.fire({
											icon: 'success',
											title: addFatura.messages
										})


									} else {

										var Toast = Swal.mixin({
											toast: true,
											position: 'top-end',
											showConfirmButton: false,
											timer: 7000
										});
										Toast.fire({
											icon: 'error',
											title: addFatura.messages
										})

									}



								}
							}).always(
								Swal.fire({
									title: 'Estamos buscando dados de despesas do paciente',
									html: 'Aguarde....',
									timerProgressBar: true,
									didOpen: () => {
										Swal.showLoading()


									}

								}))



						}
					})


				}





				if (vefificaExistenciaDespesas.contaAberta === false) {
					var Toast = Swal.mixin({
						toast: true,
						position: 'top-end',
						showConfirmButton: true,
						confirmButtonText: 'Ok',
					});
					Toast.fire({
						icon: 'error',
						title: vefificaExistenciaDespesas.messages
					})
				}





				if (vefificaExistenciaDespesas.success === false) {

					swal.close();
					Swal.fire({
						title: 'Não encontramos novas despesas. Caso exista fatura em aberto, realize a autidoria e faturamento antes de abrir uma nova.',
						html: 'Deseja gerar uma fatura, mesmo assim, para lançamentos de itens manualmente?',
						icon: 'info',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Confirmar',
						cancelButtonText: 'Cancelar'
					}).then((result) => {




						if (result.value) {


							$.ajax({
								url: '<?php echo base_url('faturamento/add') ?>',
								type: 'post',
								data: {
									codAtendimento: codAtendimento,
									csrf_sandra: $("#csrf_sandraPrincipal").val(),
								},
								dataType: 'json',
								success: function(addFatura) {

									if (addFatura.success === true) {

										editfaturamento(addFatura.codFatura);
										$('#data_tablefaturamento').DataTable().ajax.reload(null, false).draw(false);



										var Toast = Swal.mixin({
											toast: true,
											position: 'top-end',
											showConfirmButton: false,
											timer: 3000
										});
										Toast.fire({
											icon: 'success',
											title: addFatura.messages
										})


									} else {

										var Toast = Swal.mixin({
											toast: true,
											position: 'top-end',
											showConfirmButton: false,
											timer: 7000
										});
										Toast.fire({
											icon: 'error',
											title: addFatura.messages
										})

									}



								}
							}).always(
								Swal.fire({
									title: 'Estamos buscando dados de despesas do paciente',
									html: 'Aguarde....',
									timerProgressBar: true,
									didOpen: () => {
										Swal.showLoading()
									}

								}))



						}





					})
				}
			}
		})



	}

	function importarDespesas() {
		codAtendimento = document.getElementById("codAtendimentoFaturamento").value;
		codFatura = document.getElementById("codFatura").value;
		document.getElementById("dataInicioFaturaAdd").value = document.getElementById("dataGlobalInicioFaturaAdd").value;
		document.getElementById("dataEncerramentoFaturaAdd").value = document.getElementById("dataGlobalEncerramentoFaturaAdd").value;

		ccusto = $("#codDepartamentoAtendimentoAdd").val();

		if (ccusto.length === 0) {
			Swal.fire({
				icon: 'warning',
				title: "Defina primeiro o centro de custo onde o sistema irá buscar as despesas",
				showConfirmButton: false,
				timer: 4000
			})
			throw new Error("Defina primeiro o centro de custo onde o sistema irá buscar as despesas");

		} else {
			//GRAVA CENTROS DE CUSTO

			$.ajax({
				url: '<?php echo base_url('faturamento/gravaCentroCusto') ?>',
				type: 'post',
				data: {
					codFatura: codFatura,
					ccusto: ccusto,
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
				success: function(gravaccusto) {
					if (gravaccusto.success === true) {}
				}
			})


		}


		$('#gerarFaturasModal').modal('show');

		document.getElementById("quantidadeTaxasServicoes").innerHTML = '';
		document.getElementById("quantidadeMateriais").innerHTML = '';
		document.getElementById("quantidadeProcedimentos").innerHTML = '';
		document.getElementById("quantidadeMedicamentos").innerHTML = '';
		document.getElementById("quantidadeKits").innerHTML = '';


		/*
				const dateInput = document.getElementById('dataEncerramentoAdd');

				// ✅ Using the visitor's timezone
				dateInput.value = formatDate();

				console.log(formatDate());

				function padTo2Digits(num) {
					return num.toString().padStart(2, '0');
				}

				function formatDate(date = new Date()) {

					date.setDate(date.getDate() - 1);

					return [
						date.getFullYear(),
						padTo2Digits(date.getMonth() + 1),
						padTo2Digits(date.getDate()),
					].join('-');
				}

		*/

	}

	function encerrarConta(codAtendimento) {

		Swal.fire({
			title: 'Você tem certeza que deseja encerrar esta conta?',
			icon: 'info',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {

			if (result.value) {

				$.ajax({
					url: '<?php echo base_url('faturamento/encerrarConta') ?>',
					type: 'post',
					data: {
						codAtendimento: codAtendimento,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					dataType: 'json',
					success: function(encerrarConta) {

						if (encerrarConta.success === true) {

							$('#data_tableContasAbertas').DataTable().ajax.reload(null, false).draw(false);
							$('#data_tableContasFechadas').DataTable().ajax.reload(null, false).draw(false);


							var Toast = Swal.mixin({
								toast: true,
								position: 'bottom-end',
								showConfirmButton: false,
								timer: 4000
							});
							Toast.fire({
								icon: 'success',
								title: encerrarConta.messages
							})



						}
					}
				})


			}
		})
	}






	function selecionarPacienteAgora(codPaciente) {





		$.ajax({
			url: '<?php echo base_url('faturamento/pacienteSelecionado') ?>',
			type: 'post',
			data: {
				codPaciente: codPaciente,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(pacienteSelecionado) {

				if (pacienteSelecionado.success === true) {

					$('#buscarPacientesModal').modal('hide');
					document.getElementById("codPacienteAtendimentoManualAdd").value = pacienteSelecionado.codPaciente;
					document.getElementById("labelPacienteAtendimentoManual").innerHTML = pacienteSelecionado.nomeCompletoPrec;



				}

			}

		})

	}



	function localizarPaciente() {

		var paciente = document.getElementById("pacienteFiltro").value;

		if (paciente !== null && paciente !== '' && paciente !== ' ') {

			$('#data_tablepaciente').DataTable({
				"bDestroy": true,
				"paging": true,
				"deferRender": true,
				"processing": true,
				"language": {
					"processing": '<?php echo '<img style="width:150px; height:150px;"  src="' .   base_url('/imagens/loading2.gif') . '" >' ?>',
				},
				"lengthChange": false,
				"searching": true,
				"ordering": false,
				"info": true,
				"autoWidth": false,
				"responsive": true,
				"ajax": {
					"url": '<?php echo base_url('faturamento/pegaPaciente') ?>',
					"type": "post",
					"dataType": "json",
					async: "true",
					data: {
						paciente: paciente,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
				}
			});
		}

	}




	function reabrirConta(codAtendimento) {

		Swal.fire({
			title: 'Você tem certeza que deseja reabrir esta conta?',
			icon: 'info',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {

			if (result.value) {

				$.ajax({
					url: '<?php echo base_url('faturamento/reabrirConta') ?>',
					type: 'post',
					data: {
						codAtendimento: codAtendimento,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					dataType: 'json',
					success: function(reabrirConta) {

						if (reabrirConta.success === true) {

							$('#data_tableContasAbertas').DataTable().ajax.reload(null, false).draw(false);
							$('#data_tableContasFechadas').DataTable().ajax.reload(null, false).draw(false);


							var Toast = Swal.mixin({
								toast: true,
								position: 'bottom-end',
								showConfirmButton: false,
								timer: 4000
							});
							Toast.fire({
								icon: 'success',
								title: reabrirConta.messages
							})



						}
					}
				})


			}
		})
	}



	function GerarFaturaAgora() {

		Swal.fire({
			title: 'Você tem certeza que deseja Gerar esta fatura?',
			icon: 'info',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {

			if (result.value) {



				$.ajax({
					url: '<?php echo base_url('faturamento/gerarFaturaAgora') ?>',
					type: 'post',
					data: {
						codAtendimento: codAtendimento,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					dataType: 'json',
					success: function(gerarFaturaAgora) {

						$('#gerarFaturasModal').modal('hide');
						swal.close();
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
								$('#data_tablefaturamento').DataTable().ajax.reload(null, false).draw(false);
							})

						}
					}
				}).always(
					Swal.fire({
						title: 'Estamos gerando a fatura das despesas encontradas',
						html: 'Aguarde....',
						timerProgressBar: true,
						didOpen: () => {
							Swal.showLoading()


						}

					}))

			}
		})



	}

	function buscarDespesas() {


		codAtendimento = document.getElementById("codAtendimentoFaturamento").value;
		dataInicio = document.getElementById("dataInicioFaturaAdd").value;
		dataEncerramento = document.getElementById("dataEncerramentoFaturaAdd").value;
		codFatura = document.getElementById("codFatura").value;
		ccusto = '"' + $("#codDepartamentoAtendimentoAdd").val() + '"';


		if (dataInicio == '' && dataEncerramento == '') {
			dataEncerramento = null;
			dataInicio = null;
		}


		if (document.getElementById("todoPeriodoAdd").checked === true) {
			dataEncerramento = null;
			dataInicio = null;
		}


		var quantidadeTaxasServico = 0;
		var quantidadeMateriaisMedicos = 0;
		var quantidadeProcedimentos = 0;
		var quantidadeMedicamentos = 0;
		var quantidadeKits = 0;




		//BUSCA TAXAS DE USOS E SERVIÇOS

		$.ajax({
			url: '<?php echo base_url('faturamento/buscaTaxasServicos') ?>',
			type: 'post',
			data: {
				dataEncerramento: dataEncerramento,
				dataInicio: dataInicio,
				codAtendimento: codAtendimento,
				codFatura: codFatura,
				ccusto: ccusto,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			beforeSend: function() {
				$("#statusTaxasServicoes").show();
				$("#quantidadeTaxasServicoes").hide();
				//$('#statusTaxasServicoes').html('<i style="margin-left:10px;color:green" class="fa fa-spinner fa-spin"></i> <span style="color:green">Buscando...</span>');
			},
			success: function(taxasServicos) {
				if (taxasServicos.success === true) {
					$("#statusTaxasServicoes").hide();
					$("#quantidadeTaxasServicoes").show();
					document.getElementById("quantidadeTaxasServicoes").innerHTML = '<span style="margin-left:10px;color:green">' + taxasServicos.messages + '</span>';
					quantidadeTaxasServico = taxasServicos.quantidade;




					//RELOAD TAXAS E SERVIÇOS


					$('#data_tablefaturamentoTaxasServicos').DataTable().ajax.reload(null, false).draw(false);

				}

			}
		});




		//BUSCA MATERIAIS MÉDICOS
		$.ajax({
			url: '<?php echo base_url('faturamento/buscaMateriaisMedicos') ?>',
			type: 'post',
			data: {
				dataEncerramento: dataEncerramento,
				dataInicio: dataInicio,
				codAtendimento: codAtendimento,
				codFatura: codFatura,
				ccusto: ccusto,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			beforeSend: function() {
				$("#statusMateriais").show();
				$("#quantidadeMateriais").hide();
				//$('#statusMateriais').html('<i style="margin-left:10px;color:green" class="fa fa-spinner fa-spin"></i> <span style="color:green">Buscando...</span>');
			},
			success: function(materiaisMedicos) {
				if (materiaisMedicos.success === true) {
					$("#statusMateriais").hide();
					$("#quantidadeMateriais").show();
					document.getElementById("quantidadeMateriais").innerHTML = '<span style="margin-left:10px;color:green">' + materiaisMedicos.messages + '</span>';

					quantidadeMateriaisMedicos = materiaisMedicos.quantidade;




					//RELOAD MATERIAIS


					$('#data_tablefaturamentoMateriais').DataTable().ajax.reload(null, false).draw(false);




				}

			}
		});



		//BUSCA PROCEDIMENTOS MÉDICOS
		$.ajax({
			url: '<?php echo base_url('faturamento/buscaProcedimentos') ?>',
			type: 'post',
			data: {
				dataEncerramento: dataEncerramento,
				dataInicio: dataInicio,
				codAtendimento: codAtendimento,
				codFatura: codFatura,
				ccusto: ccusto,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			beforeSend: function() {
				$("#statusProcedimentos").show();
				$("#quantidadeProcedimentos").hide();
				//$('#statusProcedimentos').html('<i style="margin-left:10px;color:green" class="fa fa-spinner fa-spin"></i> <span style="color:green">Buscando...</span>');
			},
			success: function(procedimentos) {
				if (procedimentos.success === true) {
					$("#statusProcedimentos").hide();
					$("#quantidadeProcedimentos").show();
					document.getElementById("quantidadeProcedimentos").innerHTML = '<span style="margin-left:10px;color:green">' + procedimentos.messages + '</span>';
					quantidadeMateriaisMedicos = procedimentos.quantidade;


					//CARREGA PROCEDIMENTOS ENCONTRADOS
					$('#data_tablefaturamentoProcedimentos').DataTable().ajax.reload(null, false).draw(false);;
				}

			}
		});





		//BUSCA MEDICAMENTOS
		$.ajax({
			url: '<?php echo base_url('faturamento/buscaMedicamentos') ?>',
			type: 'post',
			data: {
				dataEncerramento: dataEncerramento,
				dataInicio: dataInicio,
				codAtendimento: codAtendimento,
				codFatura: codFatura,
				ccusto: ccusto,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			beforeSend: function() {
				$("#statusMedicamentos").show();
				$("#quantidadeMedicamentos").hide();
				//$('#statusMedicamentos').html('<i style="margin-left:10px;color:green" class="fa fa-spinner fa-spin"></i> <span style="color:green">Buscando...</span>');
			},
			success: function(medicamentos) {
				if (medicamentos.success === true) {

					$("#statusMedicamentos").hide();
					$("#quantidadeMedicamentos").show();
					document.getElementById("quantidadeMedicamentos").innerHTML = '<span style="margin-left:10px;color:green">' + medicamentos.messages + '</span>';
					quantidadeMateriaisMedicos = medicamentos.quantidade;





					//RELOAD MEDICAMENTOS


					$('#data_tablefaturamentoMedicamentos').DataTable().ajax.reload(null, false).draw(false);



				}

			}
		});




		//BUSCA KITS MÉDICOS
		$.ajax({
			url: '<?php echo base_url('faturamento/buscaKits') ?>',
			type: 'post',
			data: {
				dataEncerramento: dataEncerramento,
				dataInicio: dataInicio,
				codAtendimento: codAtendimento,
				codFatura: codFatura,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			beforeSend: function() {
				$("#statusKits").show();
				$("#quantidadeKits").hide();
				//$('#statusKits').html('<i style="margin-left:10px;color:green" class="fa fa-spinner fa-spin"></i> <span style="color:green">Buscando...</span>');
			},
			success: function(kits) {
				if (kits.success === true) {
					$("#statusKits").hide();
					$("#quantidadeKits").show();
					document.getElementById("quantidadeKits").innerHTML = '<span style="margin-left:10px;color:green">' + kits.messages + '</span>';
					quantidadeMateriaisMedicos = kits.quantidade;


					//RELOAD KITS


					$('#data_tablefaturamentoKits').DataTable().ajax.reload(null, false).draw(false);



				}

			}
		});


		var quantidadeTaxasServico = 0;
		var quantidadeMateriaisMedicos = 0;
		var quantidadeProcedimentos = 0;
		var quantidadeMedicamentos = 0;
		var quantidadeDiarias = 0;
		var quantidadeKits = 0;






	}

	function verFaturas(codAtendimento) {


		codDepartamentoTmp = null;
		procedimentosTmp = null;

		$('#verFaturasModal').modal('show');


		document.getElementById("codAtendimentoFaturamento").value = codAtendimento;


		$('#data_tablefaturamento').DataTable({
			"bDestroy": true,
			"pageLength": 50,
			"paging": true,
			"deferRender": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('faturamento/faturasAtendimento') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					codAtendimento: codAtendimento,
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			},
		});



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


	function imprimirFaturaAgora() {


		printElement(document.getElementById("areaImpressaoFatura"));


		window.print();
	}



	function imprimirFaturaConsolidadoAgora() {


		printElement(document.getElementById("areaImpressaoConsolidado"));


		window.print();
	}



	function selecionarPaciente() {

		$('#buscarPacientesModal').modal('show');

	}


	function novoAddAtendimentoManual() {


		if ($("#codDepartamentoFiltro").val() == '') {

			Swal.fire({
				icon: 'error',
				title: 'Selecione o Centro de Custo Primeiro!',
				showConfirmButton: true,
				confirmButtonText: 'Ok',
			})

			throw new Error('Selecione o Centro de Custo Primeiro!');
		} else {
			codDepartamentoTmp = $("#codDepartamentoFiltro").val();
		}

		Swal.fire({
			title: 'Você tem certeza que deseja gerar um atendimento Manual?',
			html: '<div>1. Este recurso só deve ser utilizado para gerar faturas para atendientos de ficha manual.</div><div>2. Não utilize este recurso caso o atendimento do paciente já tenha sido gerado pelo sistema. Evite duplicitade de cobrança do paciente!</div>',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {

			if (result.value) {


				// reset the form 
				$("#atendimentoManualAddForm")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#atendimentoManualAddModal').modal('show');


				document.getElementById("codPacienteAtendimentoManualAdd").value = null;
				document.getElementById("labelPacienteAtendimentoManual").innerHTML = " ";



				$.ajax({
					url: '<?php echo base_url('departamentos/listaDropDownUnidadesFaturamento') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(codDepartamentoAtendimentoManualAdd) {

						$("#codDepartamentoAtendimentoManualAdd").select2({
							data: codDepartamentoAtendimentoManualAdd,
						})

						$('#codDepartamentoAtendimentoManualAdd').val(codDepartamentoTmp);
						$('#codDepartamentoAtendimentoManualAdd').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});



					}
				})


				$("#codDepartamentoAtendimentoManualAdd").on("change", function() {


					$('#codLocalAtendimentoManualAdd').html('').select2({
						data: [{
							id: null,
							text: ''
						}]
					});

					if ($(this).val() !== '') {
						codDepartamento = $(this).val();
						codDepartamentoTmp = $(this).val();
					} else {
						codDepartamento = 0;
					}

					$.ajax({
						url: '<?php echo base_url('atendimentosLocais/listaDropDownLeitosLocaisProcedimentosAtivos') ?>',
						type: 'post',
						dataType: 'json',
						data: {
							codDepartamento: codDepartamento,
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},

						success: function(codLocalAtendimentoManualAdd) {

							$("#codLocalAtendimentoManualAdd").select2({
								data: codLocalAtendimentoManualAdd,
							})


							$('#codLocalAtendimentoManualAdd').val(codLocalAtendimentoTmp);
							$('#codLocalAtendimentoManualAdd').trigger('change');
							$(document).on('select2:open', () => {
								document.querySelector('.select2-search__field').focus();
							});



						}
					})


				});


				$("#codLocalAtendimentoManualAdd").on("change", function() {
					if ($(this).val() !== '') {
						codLocalAtendimentoTmp = $(this).val();
					} else {
						codLocalAtendimentoTmp = null;


					}

				})







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


						if ($("#codPacienteAtendimentoManualAdd").val() == '') {

							Swal.fire({
								icon: 'error',
								title: 'Selecione o Paciente!',
								showConfirmButton: true,
								confirmButtonText: 'Ok',
							})

							event.preventDefault();
						}

						var form = $('#atendimentoManualAddForm');
						// remove the text-danger
						$(".text-danger").remove();

						$.ajax({
							url: '<?php echo base_url('faturamento/addAtendimentoManual') ?>',
							type: 'post',
							data: form.serialize(), // /converting the form data into array and sending it to server
							dataType: 'json',
							success: function(response) {

								if (response.success === true) {

									Swal.fire({
										position: 'bottom-end',
										icon: 'success',
										title: response.messages,
										showConfirmButton: false,
										timer: 3000
									}).then(function() {
										$('#data_tableContasAbertas').DataTable().ajax.reload(null, false).draw(false);
										$('#atendimentoManualAddModal').modal('hide');
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
											timer: 3000
										})

									}
								}
							}
						});

						return false;
					}
				});
				$('#atendimentoManualAddForm').validate();



















			}

		})

	}


	function assinareFecharFatura() {


		Swal.fire({
			title: 'Você tem certeza que deseja assinar e fechar esta fatura?',
			html: 'Auditoria será dada como concluída e não será mais possível realizar alterações!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {

			if (result.value) {

				$.ajax({
					url: '<?php echo base_url('faturamento/fecharFatura') ?>',
					type: 'post',
					data: {
						codFatura: codFaturaTmp,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					dataType: 'json',
					success: function(responseFecharFatura) {


						if (responseFecharFatura.success === true) {


							if ($('#verFaturasModal').is(':visible') == true) {
								$('#data_tablefaturamento').DataTable().ajax.reload(null, false).draw(false);

							}


							if ($('#faturamentoEditModal').is(':visible') == true) {
								$('#data_tablefaturamentoMedicamentos').DataTable().ajax.reload(null, false).draw(false);
								$('#data_tablefaturamentoTaxasServicos').DataTable().ajax.reload(null, false).draw(false);
								$('#data_tablefaturamentoProcedimentos').DataTable().ajax.reload(null, false).draw(false);
								$('#data_tablefaturamentoMateriais').DataTable().ajax.reload(null, false).draw(false);
								$('#data_tablefaturamentoKits').DataTable().ajax.reload(null, false).draw(false);

							}

							$(".ver").hide();
							$(".editar").hide();

							$(".verTaxas").hide();
							$(".editarTaxas").hide();

							$(".verProcedimentos").hide();
							$(".editarProcedimentos").hide();

							$(".verMateriais").hide();
							$(".editarMateriais").hide();

							$(".verKits").hide();
							$(".editarKits").hide();

							$(".btn-importarDespesas").hide();
							$(".btn-assinareFecharFatura").hide();


							var Toast = Swal.mixin({
								toast: true,
								position: 'top-end',
								showConfirmButton: false,
								timer: 7000
							});
							Toast.fire({
								icon: 'success',
								title: responseFecharFatura.messages
							})


						}

					}

				})


			}



		})

	}

	function imprimirFatura() {

		$.ajax({
			url: '<?php echo base_url('faturamento/dadosImpressaoFatura') ?>',
			type: 'post',
			data: {
				codFatura: codFaturaTmp,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(responseDadosImpressaoFatura) {
				// reset the form 
				$('#faturamentoEditModal').modal('show');

				if (responseDadosImpressaoFatura.success === true) {

					$('#imprimirFaturaModal').modal('show');

					document.getElementById("dadosFaturaImprimir").innerHTML = responseDadosImpressaoFatura.html;



				} else {
					var Toast = Swal.mixin({
						toast: true,
						position: 'top-end',
						showConfirmButton: false,
						timer: 7000
					});
					Toast.fire({
						icon: 'error',
						title: responseDadosImpressaoFatura.messages
					})
				}
			}
		});



		document.getElementById("setEstilo").innerHTML = '<style>@media screen {' +
			'#printSection {' +
			'display: none;' +

			'}' +
			'}' +

			'@media print {' +
			'@page {' +
			'size: A4 landscape;' +
			'margin-top: 15px;' +
			'margin-bottom: 15px;' +
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


	}

	function imprimirConsolidado() {

		$.ajax({
			url: '<?php echo base_url('faturamento/dadosImpressaoConsolidado') ?>',
			type: 'post',
			data: {
				codFatura: codFaturaTmp,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(responseDadosImpressaoConsolidado) {
				// reset the form 

				if (responseDadosImpressaoConsolidado.success === true) {

					$('#imprimirConsolidadoModal').modal('show');

					document.getElementById("dadosConsolidadoImprimir").innerHTML = responseDadosImpressaoConsolidado.html;



				} else {
					var Toast = Swal.mixin({
						toast: true,
						position: 'top-end',
						showConfirmButton: false,
						timer: 7000
					});
					Toast.fire({
						icon: 'error',
						title: responseDadosImpressaoConsolidado.messages
					})
				}
			}
		});



		document.getElementById("setEstilo").innerHTML = '<style>@media screen {' +
			'#printSection {' +
			'display: none;' +

			'}' +
			'}' +

			'@media print {' +
			'@page {' +
			'size: A4;' +
			'margin-top: 15px;' +
			'margin-bottom: 15px;' +
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


	}

	function editfaturamento(codFatura) {

		codAtendimento = document.getElementById("codAtendimentoFaturamento").value;

		document.getElementById("codFatura").value = codFatura;

		document.getElementById("codFaturaSetaPeriodoGlobalAdd").value = codFatura;




		$.ajax({
			url: '<?php echo base_url('faturamento/dadosFatura') ?>',
			type: 'post',
			data: {
				codFatura: codFatura,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(responseDadosFatura) {


				// reset the form 

				if (responseDadosFatura.success === true) {


					$('#faturamentoEditModal').modal('show');


					document.getElementById('dataGlobalInicioFaturaAdd').value = responseDadosFatura.dataInicio;
					document.getElementById('dataGlobalEncerramentoFaturaAdd').value = responseDadosFatura.dataEncerramento;
					document.getElementById('observacoesFaturaAdd').value = responseDadosFatura.observacoes;


					var ccustos = responseDadosFatura.ccusto;
					$.ajax({
						url: '<?php echo base_url('departamentos/listaDropDownUnidadesFaturamento') ?>',
						type: 'post',
						dataType: 'json',
						data: {
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},
						success: function(codDepartamentoAtendimentoAdd) {

							$("#codDepartamentoAtendimentoAdd").select2({
								data: codDepartamentoAtendimentoAdd,
							})

							$('#codDepartamentoAtendimentoAdd').val(JSON.parse(ccustos)).change(); // Select the option with a value of '1'
							$('#codDepartamentoAtendimentoAdd').trigger('change');
							$(document).on('select2:open', () => {
								document.querySelector('.select2-search__field').focus();
							});



						}
					})


					if (responseDadosFatura.codStatusFatura == 1) {

						$(".ver").hide();
						$(".editar").hide();

						$(".verTaxas").hide();
						$(".editarTaxas").hide();

						$(".verProcedimentos").hide();
						$(".editarProcedimentos").hide();

						$(".verMateriais").hide();
						$(".editarMateriais").hide();

						$(".verKits").hide();
						$(".editarKits").hide();

						$(".btn-importarDespesas").hide();
						$(".btn-assinareFecharFatura").hide();
					} else {

						$(".ver").show();

						$(".verTaxas").show();

						$(".verProcedimentos").show();

						$(".verMateriais").show();

						$(".verKits").show();

						$(".btn-importarDespesas").show();
						$(".btn-assinareFecharFatura").show();

					}


					document.getElementById('dadosFatura').innerHTML = responseDadosFatura.html;

					codAtendimentoTmp = responseDadosFatura.codAtendimento;
					codFaturaTmp = responseDadosFatura.codFatura;


				}
			}
		});



		//MEDICAMENTOS
		//carregaMedicamentos();

		$('#data_tablefaturamentoMedicamentos').DataTable({
			"pageLength": 50,
			"bDestroy": true,
			"paging": true,
			"deferRender": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('faturamentoMedicamentos/medicamentosFaturados') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					codFatura: document.getElementById("codFatura").value,
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			},
		})




		//TAXAS E SERVIÇOS
		//$('#data_tablefaturamentoTaxasServicos').DataTable().ajax.reload(null, false).draw(false);

		$('#data_tablefaturamentoTaxasServicos').DataTable({
			"bDestroy": true,
			"pageLength": 50,
			"paging": true,
			"deferRender": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('faturamentoTaxasServicos/taxasServicosFaturados') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					codFatura: document.getElementById("codFatura").value,
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});




		//PROCEDIMENTOS
		//$('#data_tablefaturamentoProcedimentos').DataTable().ajax.reload(null, false).draw(false);;

		$('#data_tablefaturamentoProcedimentos').DataTable({
			"bDestroy": true,
			"pageLength": 50,
			"paging": true,
			"deferRender": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('faturamentoProcedimentos/procedimentosFatura') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					codFatura: document.getElementById("codFatura").value,
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});


		//MATERIAIS

		$('#data_tablefaturamentoMateriais').DataTable({
			"bDestroy": true,
			"pageLength": 50,
			"paging": true,
			"deferRender": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('faturamentoMateriais/materiaisFatura') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					codFatura: document.getElementById("codFatura").value,
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});


		//KITS

		$('#data_tablefaturamentoKits').DataTable({
			"bDestroy": true,
			"pageLength": 50,
			"paging": true,
			"deferRender": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('faturamentoKits/kitsFatura') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					codFatura: document.getElementById("codFatura").value,
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});



	}





	function auditar() {

		$(".editar").show();
		$(".ver").hide();

	}

	function glosarEmLote() {

		$(".glosarMedicamento").show();
		$(".ver").hide();

	}

	function glosarMateriaisEmLote() {

		$(".glosarMateriais").show();
		$(".verMateriais").hide();

	}

	function glosarKitsEmLote() {

		$(".glosarKits").show();
		$(".verKits").hide();

	}


	function cancelarAuditoriaMedicamentos() {

		$(".editar").hide();
		$(".ver").show();

	}


	function cancelarAuditoriaTaxas() {

		$(".editarTaxas").hide();
		$(".verTaxas").show();

	}

	function cancelarAuditoriaProcedimentos() {

		$(".editarProcedimentos").hide();
		$(".verProcedimentos").show();

	}

	function cancelarAuditoriaMateriais() {

		$(".editarMateriais").hide();
		$(".verMateriais").show();

	}

	function cancelarAuditoriaKits() {

		$(".editarKits").hide();
		$(".verKits").show();

	}

	function auditarTaxas() {

		$(".editarTaxas").show();
		$(".verTaxas").hide();

	}

	function auditarProcedimentos() {

		$(".editarProcedimentos").show();
		$(".verProcedimentos").hide();

	}


	function auditarMateriais() {

		$(".editarMateriais").show();
		$(".verMateriais").hide();

	}


	function auditarKits() {

		$(".editarKits").show();
		$(".verKits").hide();

	}


	function novoMedicamento() {


	}


	function fecharAuditoria() {

		$(".editar").hide();
		$(".ver").show();


		$(".editarTaxas").hide();
		$(".verTaxas").show();
	}

	function savarAuditoriaMedicamentos() {



		var form = $('#faturamentoForm');

		$.ajax({
			url: '<?php echo base_url('faturamento/savarAuditoriaMedicamentos') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(savarAuditoriaMedicamentos) {
				if (savarAuditoriaMedicamentos.success === true) {


					$(".editar").hide();
					$(".ver").show();

					$('#data_tablefaturamentoMedicamentos').DataTable().ajax.reload(null, false).draw(false);


					var Toast = Swal.mixin({
						toast: true,
						position: 'top-end',
						showConfirmButton: false,
						timer: 2000
					});
					Toast.fire({
						icon: 'success',
						title: savarAuditoriaMedicamentos.messages
					})
				}
			}
		})

	}


	function cancelarGlosarMedicamentosEmLoteAgora() {

		$(".glosarMedicamento").hide();
		$(".ver").show();
	}


	function cancelarGlosarMateriaisEmLoteAgora() {

		$(".glosarMateriais").hide();
		$(".verMateriais").show();
	}

	function cancelarGlosarKitsEmLoteAgora() {

		$(".glosarKits").hide();
		$(".verKits").show();
	}

	function glosarMedicamentosEmLoteAgora() {



		Swal.fire({
			title: 'Informe o motivo para glosar estes medicamentos em lote.',
			input: 'text',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar',
			inputValidator: (value) => {
				if (!value) {
					return 'É necessário informar o Motivo'
				} else {

					document.getElementById('motivoEmLoteAdd').value = value;


					var form = $('#faturamentoForm');

					$.ajax({
						url: '<?php echo base_url('faturamentoMedicamentos/glosarMedicamentosEmLoteAgora') ?>',
						type: 'post',
						data: form.serialize(), // /converting the form data into array and sending it to server
						dataType: 'json',
						success: function(glosarMedicamentosEmLoteAgora) {
							if (glosarMedicamentosEmLoteAgora.success === true) {


								$(".glosarMedicamento").hide();
								$(".ver").show();

								$('#data_tablefaturamentoMedicamentos').DataTable().ajax.reload(null, false).draw(false);


								var Toast = Swal.mixin({
									toast: true,
									position: 'top-end',
									showConfirmButton: false,
									timer: 2000
								});
								Toast.fire({
									icon: 'success',
									title: glosarMedicamentosEmLoteAgora.messages
								})
							}
						}
					})



				}


			}

		})



	}


	function glosarMateriaisEmLoteAgora() {



		Swal.fire({
			title: 'Informe o motivo para glosar estes materiais em lote.',
			input: 'text',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar',
			inputValidator: (value) => {
				if (!value) {
					return 'É necessário informar o Motivo'
				} else {

					document.getElementById('motivoGlosarMaterialEmLoteAdd').value = value;


					var form = $('#materiaisForm');

					$.ajax({
						url: '<?php echo base_url('faturamentoMateriais/glosarMateriaisEmLoteAgora') ?>',
						type: 'post',
						data: form.serialize(), // /converting the form data into array and sending it to server
						dataType: 'json',
						success: function(glosarMateriaisEmLoteAgora) {
							if (glosarMateriaisEmLoteAgora.success === true) {


								$(".glosarMateriais").hide();
								$(".verMateriais").show();

								$('#data_tablefaturamentoMateriais').DataTable().ajax.reload(null, false).draw(false);


								var Toast = Swal.mixin({
									toast: true,
									position: 'top-end',
									showConfirmButton: false,
									timer: 2000
								});
								Toast.fire({
									icon: 'success',
									title: glosarMateriaisEmLoteAgora.messages
								})
							}
						}
					})



				}


			}

		})



	}


	function glosarKitsEmLoteAgora() {



		Swal.fire({
			title: 'Informe o motivo para glosar estes Kits em lote.',
			input: 'text',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar',
			inputValidator: (value) => {
				if (!value) {
					return 'É necessário informar o Motivo'
				} else {

					document.getElementById('motivoGlosarKitEmLoteAdd').value = value;


					var form = $('#kitsForm');

					$.ajax({
						url: '<?php echo base_url('faturamentoKits/glosarKitsEmLoteAgora') ?>',
						type: 'post',
						data: form.serialize(), // /converting the form data into array and sending it to server
						dataType: 'json',
						success: function(glosarKitsEmLoteAgora) {
							if (glosarKitsEmLoteAgora.success === true) {


								$(".glosarKits").hide();
								$(".verKits").show();

								$('#data_tablefaturamentoKits').DataTable().ajax.reload(null, false).draw(false);


								var Toast = Swal.mixin({
									toast: true,
									position: 'top-end',
									showConfirmButton: false,
									timer: 2000
								});
								Toast.fire({
									icon: 'success',
									title: glosarKitsEmLoteAgora.messages
								})
							} else {
								var Toast = Swal.mixin({
									toast: true,
									position: 'top-end',
									showConfirmButton: false,
									timer: 2000
								});
								Toast.fire({
									icon: 'error',
									title: glosarKitsEmLoteAgora.messages
								})
							}
						}
					})



				}


			}

		})



	}


	function savarAuditoriaKits() {



		var form = $('#kitsForm');

		$.ajax({
			url: '<?php echo base_url('faturamento/savarAuditoriaKits') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(savarAuditoriaKits) {
				if (savarAuditoriaKits.success === true) {


					$(".editarKits").hide();
					$(".verKits").show();

					$('#data_tablefaturamentoKits').DataTable().ajax.reload(null, false).draw(false);

					var Toast = Swal.mixin({
						toast: true,
						position: 'top-end',
						showConfirmButton: false,
						timer: 2000
					});
					Toast.fire({
						icon: 'success',
						title: savarAuditoriaKits.messages
					})
				}
			}
		})

	}

	function savarAuditoriaProcedimentos() {



		var form = $('#procedimentosForm');

		$.ajax({
			url: '<?php echo base_url('faturamento/savarAuditoriaProcedimentos') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(savarAuditoriaProcedimentos) {
				if (savarAuditoriaProcedimentos.success === true) {


					$(".editarProcedimentos").hide();
					$(".verProcedimentos").show();

					$('#data_tablefaturamentoProcedimentos').DataTable().ajax.reload(null, false).draw(false);


					var Toast = Swal.mixin({
						toast: true,
						position: 'top-end',
						showConfirmButton: false,
						timer: 2000
					});
					Toast.fire({
						icon: 'success',
						title: savarAuditoriaProcedimentos.messages
					})
				}
			}
		})

	}





	function savarPeriodoObservacoes() {


		var form = $('#periodoObservacoesForm');

		$.ajax({
			url: '<?php echo base_url('faturamento/savarPeriodoObservacoesForm') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(savarperiodoObservacoesForm) {
				if (savarperiodoObservacoesForm.success === true) {

					var Toast = Swal.mixin({
						toast: true,
						position: 'top-end',
						showConfirmButton: false,
						timer: 2000
					});
					Toast.fire({
						icon: 'success',
						title: savarperiodoObservacoesForm.messages
					})
				}
			}
		})

	}



	function savarAuditoriaMateriais() {



		var form = $('#materiaisForm');

		$.ajax({
			url: '<?php echo base_url('faturamento/savarAuditoriaMateriais') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(savarAuditoriaMateriais) {
				if (savarAuditoriaMateriais.success === true) {


					$(".editarMateriais").hide();
					$(".verMateriais").show();

					$('#data_tablefaturamentoMateriais').DataTable().ajax.reload(null, false).draw(false);


					var Toast = Swal.mixin({
						toast: true,
						position: 'top-end',
						showConfirmButton: false,
						timer: 2000
					});
					Toast.fire({
						icon: 'success',
						title: savarAuditoriaMateriais.messages
					})
				}
			}
		})

	}

	function savarAuditoriaTaxas() {



		var form = $('#taxasServicosForm');

		$.ajax({
			url: '<?php echo base_url('faturamento/savarAuditoriaTaxasServicos') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(savarAuditoriaTaxas) {
				if (savarAuditoriaTaxas.success === true) {

					$(".editarTaxas").hide();
					$(".verTaxas").show();
					$('#data_tablefaturamentoTaxasServicos').DataTable().ajax.reload(null, false).draw(false);

					var Toast = Swal.mixin({
						toast: true,
						position: 'top-end',
						showConfirmButton: false,
						timer: 2000
					});
					Toast.fire({
						icon: 'success',
						title: savarAuditoriaTaxas.messages
					})





				}

			}


		})

	}


	function novoMedicamento() {
		// reset the form 
		$("#faturamentoMedicamentosAddForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#faturamentoMedicamentosAddModal').modal('show');

		$("#codFaturaAddItem").val(codFaturaTmp);
		$("#codAtendimentoAdd").val(codAtendimentoTmp);



		if (dataDespesaMedicamentoTmp == null) {
			$("#dataDespesaMedicamentoAdd").val($("#dataGlobalInicioFaturaAdd").val());

		} else {

			document.getElementById("dataDespesaMedicamentoAdd").value = dataDespesaMedicamentoTmp;
		}


		$.ajax({
			url: '<?php echo base_url('itensFarmacia/listaDropDownMedicamentos') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(listaMedicamentosAdd) {

				$("#codMedicamentoPrescritoAdd").select2({
					data: listaMedicamentosAdd,
				})

				$('#codMedicamentoPrescritoAdd').val(null); // Select the option with a value of '1'
				$('#codMedicamentoPrescritoAdd').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-container--open .select2-search__field').focus();
				});

			}
		})



		$.ajax({
			url: '<?php echo base_url('departamentos/listaDropDownUnidadesFaturamento') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(codDepartamentoMedicamentoAdd) {

				$("#codDepartamentoMedicamentoAdd").select2({
					data: codDepartamentoMedicamentoAdd,
				})

				$('#codDepartamentoMedicamentoAdd').val(codDepartamentoTmp);
				$('#codDepartamentoMedicamentoAdd').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-container--open .select2-search__field').focus();
				});



			}
		})

		$("#codDepartamentoMedicamentoAdd").on("change", function() {


			$('#codLocalMedicamentoAdd').html('').select2({
				data: [{
					id: null,
					text: ''
				}]
			});

			if ($(this).val() !== '') {
				codDepartamento = $(this).val();
				codDepartamentoTmp = $(this).val();
			} else {
				codDepartamento = 0;
			}

			$.ajax({
				url: '<?php echo base_url('atendimentosLocais/listaDropDownLeitosLocaisProcedimentosAtivos') ?>',
				type: 'post',
				dataType: 'json',
				data: {
					codDepartamento: codDepartamento,
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},

				success: function(codLocalMedicamentoAdd) {

					$("#codLocalMedicamentoAdd").select2({
						data: codLocalMedicamentoAdd,
					})


					$('#codLocalMedicamentoAdd').val(codLocalAtendimentoTmp);
					$('#codLocalMedicamentoAdd').trigger('change');
					$(document).on('select2:open', () => {
						document.querySelector('.select2-search__field').focus();
					});



				}
			})


		});


		$("#codLocalMedicamentoAdd").on("change", function() {
			if ($(this).val() !== '') {
				codLocalAtendimentoTmp = $(this).val();
			} else {
				codLocalAtendimentoTmp = null;
			}

		});


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


				dataDespesaMedicamentoTmp = document.getElementById("dataDespesaMedicamentoAdd").value;


				var form = $('#faturamentoMedicamentosAddForm');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('faturamentoMedicamentos/addIndividual') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#faturamentoMedicamentosAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function(response) {

						if (response.success === true) {
							$('#faturamentoMedicamentosAddModal').modal('hide');

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


								$('#data_tablefaturamentoMedicamentos').DataTable().ajax.reload(null, false).draw(false);
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
						$('#faturamentoMedicamentosAddForm-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#faturamentoMedicamentosAddForm').validate();
	}



	function removefaturamentoTaxasServicos(codFaturamentoTaxasServico) {
		Swal.fire({
			title: 'Você tem certeza que deseja remover esta Taxa/Serviço?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {

			if (result.value) {
				$.ajax({
					url: '<?php echo base_url('faturamentoTaxasServicos/remove') ?>',
					type: 'post',
					data: {
						codFaturamentoTaxasServico: codFaturamentoTaxasServico,
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

								$('#data_tablefaturamentoTaxasServicos').DataTable().ajax.reload(null, false).draw(false);
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



	function novaTaxaServico() {
		// reset the form 
		$("#faturamentoTaxasServicosAddForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#faturamentoTaxasServicosAddModal').modal('show');

		$("#codFaturaAddTaxasServico").val(document.getElementById("codFatura").value);



		$("#codFaturaAddTaxasServico").val(codFaturaTmp);
		$("#codAtendimentoAddTaxasServico").val(codAtendimentoTmp);


		$("#dataInicioTaxaServicoAdd").val($("#dataGlobalInicioFaturaAdd").val());
		$("#dataEncerramentoTaxaServicoAdd").val($("#dataGlobalEncerramentoFaturaAdd").val());


		$.ajax({
			url: '<?php echo base_url('taxasServicos/listaDropDown') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(codTaxaServicoAdd) {

				$("#codTaxaServicoAdd").select2({
					data: codTaxaServicoAdd,
				})

				$('#codTaxaServicoAdd').val(null); // Select the option with a value of '1'
				$('#codTaxaServicoAdd').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-container--open .select2-search__field').focus();
				});

			}
		})


		$.ajax({
			url: '<?php echo base_url('departamentos/listaDropDownUnidadesFaturamento') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(codDepartamentoTaxaServicoAdd) {

				$("#codDepartamentoTaxaServicoAdd").select2({
					data: codDepartamentoTaxaServicoAdd,
				})

				$('#codDepartamentoTaxaServicoAdd').val(codDepartamentoTmp);
				$('#codDepartamentoTaxaServicoAdd').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-container--open .select2-search__field').focus();
				});



			}
		})

		$("#codDepartamentoTaxaServicoAdd").on("change", function() {


			$('#codLocalTaxaServicoAdd').html('').select2({
				data: [{
					id: null,
					text: ''
				}]
			});

			if ($(this).val() !== '') {
				codDepartamento = $(this).val();
				codDepartamentoTmp = $(this).val();
			} else {
				codDepartamento = 0;
			}

			$.ajax({
				url: '<?php echo base_url('atendimentosLocais/listaDropDownLeitosLocaisProcedimentosAtivos') ?>',
				type: 'post',
				dataType: 'json',
				data: {
					codDepartamento: codDepartamento,
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},

				success: function(codLocalTaxaServicoAdd) {

					$("#codLocalTaxaServicoAdd").select2({
						data: codLocalTaxaServicoAdd,
					})


					$('#codLocalTaxaServicoAdd').val(codLocalAtendimentoTmp);
					$('#codLocalTaxaServicoAdd').trigger('change');
					$(document).on('select2:open', () => {
						document.querySelector('.select2-search__field').focus();
					});



				}
			})


		});


		$("#codLocalTaxaServicoAdd").on("change", function() {
			if ($(this).val() !== '') {
				codLocalAtendimentoTmp = $(this).val();
			} else {
				codLocalAtendimentoTmp = null;
			}

		});





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

				var form = $('#faturamentoTaxasServicosAddForm');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('faturamentoTaxasServicos/addIndividual') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#faturamentoTaxasServicosAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function(response) {

						if (response.success === true) {
							$('#faturamentoTaxasServicosAddModal').modal('hide');

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
								$('#data_tablefaturamentoTaxasServicos').DataTable().ajax.reload(null, false).draw(false);
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
						$('#faturamentoTaxasServicosAddForm-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#faturamentoTaxasServicosAddForm').validate();
	}




	function removefaturamento(codFatura) {
		Swal.fire({
			title: 'Você tem certeza que deseja remover esta Fatura?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {

			if (result.value) {
				$.ajax({
					url: '<?php echo base_url('faturamento/remove') ?>',
					type: 'post',
					data: {
						codFatura: codFatura,
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
								$('#data_tablefaturamento').DataTable().ajax.reload(null, false).draw(false);
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


	function reabrirFaturamento(codFatura) {



		Swal.fire({
			title: 'Você tem certeza que deseja remover esta Fatura?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar',
			input: 'textarea',
			inputLabel: 'Informe o motivo(Obrigatório)',
			inputValidator: (value) => {
				if (!value) {
					return 'É necessário informar o motivo'
				} else {



					$.ajax({
						url: '<?php echo base_url('faturamento/reabrirFaturamento') ?>',
						type: 'post',
						data: {
							codFatura: codFatura,
							motivo: value,
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
									$('#data_tablefaturamento').DataTable().ajax.reload(null, false).draw(false);
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

			}
		})
	}


	function removefaturamentoProcedimentos(codFaturamentoProcedimento) {
		Swal.fire({
			title: 'Você tem certeza que deseja remover este Procedimento?',
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
								$('#data_tablefaturamentoProcedimentos').DataTable().ajax.reload(null, false).draw(false);;
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

	function editfaturamentoMedicamentos(codFaturamentoMedicamento) {
		$.ajax({
			url: '<?php echo base_url('faturamentoMedicamentos/getOne') ?>',
			type: 'post',
			data: {
				codFaturamentoMedicamento: codFaturamentoMedicamento,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {




				// reset the form 
				$("#faturamentoMedicamentosEditForm")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#faturamentoMedicamentosEditModal').modal('show');

				$("#faturamentoMedicamentosEditForm #codFaturamentoMedicamento").val(response.codFaturamentoMedicamento);
				$("#faturamentoMedicamentosEditForm #quantidade").val(response.quantidade);
				$("#faturamentoMedicamentosEditForm #observacoes").val(response.observacoes);







				$.ajax({
					url: '<?php echo base_url('faturamentoMedicamentos/listaStatusFaturamentoMedicamentos') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(listaStatusMedicamento) {

						$("#codStatusMedicamentoAdd").select2({
							data: listaStatusMedicamento,
						})

						$('#codStatusMedicamentoAdd').val(response.codStatus); // Select the option with a value of '1'
						$('#codStatusMedicamentoAdd').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

					}
				})


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
						var form = $('#faturamentoMedicamentosEditForm');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('faturamentoMedicamentos/editMedicamento') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							success: function(response) {

								if (response.success === true) {

									$('#faturamentoMedicamentosEditModal').modal('hide');


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
										$('#data_tablefaturamentoMedicamentos').DataTable().ajax.reload(null, false).draw(false);
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
							}
						});

						return false;
					}
				});
				$('#faturamentoMedicamentosEditForm').validate();

			}
		});
	}

	function removefaturamentoMedicamentos(codFaturamentoMedicamento) {


		Swal.fire({
			title: 'Informe o motivo para glosar este medicamento.',
			input: 'text',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar',
			inputValidator: (value) => {
				if (!value) {
					return 'É necessário informar o Motivo'
				} else {

					$.ajax({
						url: '<?php echo base_url('faturamentoMedicamentos/glosar') ?>',
						type: 'post',
						data: {
							observacoes: value,
							codFaturamentoMedicamento: codFaturamentoMedicamento,
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

									$('#data_tablefaturamentoMedicamentos').DataTable().ajax.reload(null, false).draw(false);
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

			}
		})


	}

	function novoProcedimento() {

		// reset the form 
		$("#faturamentoProcedimentosAddForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#faturamentoProcedimentosAddModal').modal('show');


		if (dataDespesaProcedimentoTmp == null) {
			$("#dataDespesaProcedimentoAdd").val($("#dataGlobalInicioFaturaAdd").val());

		} else {

			document.getElementById("dataDespesaProcedimentoAdd").value = dataDespesaProcedimentoTmp;
		}




		$("#codFaturaProcedimentoAddItem").val(codFaturaTmp);
		$("#codAtendimentoProcedimentoAdd").val(codAtendimentoTmp);



		$('#codProcedimentoAdd').html('').select2({
			data: [{
				id: '',
				text: ''
			}]
		});


		if (procedimentosTmp !== null) {


			$("#codProcedimentoAdd").select2({
				data: procedimentosTmp,
				minimumInputLength: 3,
				quietMillis: 1000,
				dropdownParent: $('#faturamentoProcedimentosAddModal .modal-content'),
			})

		} else {


			$.ajax({
				url: '<?php echo base_url('procedimentos/listaDropDown') ?>',
				type: 'post',
				dataType: 'json',
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
				success: function(procedimentoAdd) {

					procedimentosTmp = procedimentoAdd;

					$("#codProcedimentoAdd").select2({
						data: procedimentosTmp,
						minimumInputLength: 3,
						quietMillis: 1000,
						dropdownParent: $('#faturamentoProcedimentosAddModal .modal-content'),
					})

					$('#codProcedimentoAdd').val(null);
					$('#codProcedimentoAdd').trigger('change');
					$(document).on('select2:open', () => {
						document.querySelector('.select2-search__field').focus();
					});



				}
			})

		}
		$.ajax({
			url: '<?php echo base_url('departamentos/listaDropDownUnidadesFaturamento') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(codDepartamentoProcedimentoAdd) {

				$("#codDepartamentoProcedimentoAdd").select2({
					data: codDepartamentoProcedimentoAdd,
				})

				$('#codDepartamentoProcedimentoAdd').val(codDepartamentoTmp);
				$('#codDepartamentoProcedimentoAdd').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-container--open .select2-search__field').focus();
				});



			}
		})

		$("#codDepartamentoProcedimentoAdd").on("change", function() {


			$('#codLocalProcedimentoAdd').html('').select2({
				data: [{
					id: null,
					text: ''
				}]
			});

			if ($(this).val() !== '') {
				codDepartamento = $(this).val();
				codDepartamentoTmp = $(this).val();
			} else {
				codDepartamento = 0;
			}

			$.ajax({
				url: '<?php echo base_url('atendimentosLocais/listaDropDownLeitosLocaisProcedimentosAtivos') ?>',
				type: 'post',
				dataType: 'json',
				data: {
					codDepartamento: codDepartamento,
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},

				success: function(codLocalProcedimentoAdd) {

					$("#codLocalProcedimentoAdd").select2({
						data: codLocalProcedimentoAdd,
					})


					$('#codLocalProcedimentoAdd').val(codLocalAtendimentoTmp);
					$('#codLocalProcedimentoAdd').trigger('change');
					$(document).on('select2:open', () => {
						document.querySelector('.select2-search__field').focus();
					});



				}
			})


		});


		$("#codLocalProcedimentoAdd").on("change", function() {
			if ($(this).val() !== '') {
				codLocalAtendimentoTmp = $(this).val();
			} else {
				codLocalAtendimentoTmp = null;
			}

		});


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

				dataDespesaProcedimentoTmp = document.getElementById("dataDespesaProcedimentoAdd").value;

				var form = $('#faturamentoProcedimentosAddForm');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('faturamentoProcedimentos/addIndividual') ?>',
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

				$("#faturamentoProcedimentosEditForm #codFaturamentoProcedimentoEdit").val(response.codFaturamentoProcedimento);
				$("#faturamentoProcedimentosEditForm #quantidadeProcedimentoEdit").val(response.quantidade);
				$("#faturamentoProcedimentosEditForm #observacoesProcedimentoEdit").val(response.observacoes);


				$('#codProcedimentoEdit').html('').select2({
					data: [{
						id: '',
						text: ''
					}]
				});


				if (procedimentosTmp !== null) {


					$("#codProcedimentoEdit").select2({
						data: procedimentosTmp,
						minimumInputLength: 3,
						quietMillis: 1000,
						dropdownParent: $('#faturamentoProcedimentosEditModal .modal-content'),
					})

					if (response.codProcedimento !== null) {
						$('#codProcedimentoEdit').val(response.codProcedimento);
						$('#codProcedimentoEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});
					}

				} else {


					$.ajax({
						url: '<?php echo base_url('procedimentos/listaDropDown') ?>',
						type: 'post',
						dataType: 'json',
						data: {
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},
						success: function(procedimentoEdit) {

							procedimentosTmp = procedimentoEdit;

							$("#codProcedimentoEdit").select2({
								data: procedimentosTmp,
								minimumInputLength: 3,
								quietMillis: 1000,
								dropdownParent: $('#faturamentoProcedimentosEditModal .modal-content'),
							})

							$('#codProcedimentoEdit').val(response.codProcedimento);
							$('#codProcedimentoEdit').trigger('change');
							$(document).on('select2:open', () => {
								document.querySelector('.select2-search__field').focus();
							});



						}
					})

				}
				$.ajax({
					url: '<?php echo base_url('departamentos/listaDropDownUnidadesFaturamento') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(codDepartamentoProcedimentoEdit) {

						$("#codDepartamentoProcedimentoEdit").select2({
							data: codDepartamentoProcedimentoEdit,
						})

						$('#codDepartamentoProcedimentoEdit').val(response.codDepartamento);
						$('#codDepartamentoProcedimentoEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});



					}
				})

				$("#codDepartamentoProcedimentoEdit").on("change", function() {


					$('#codLocalProcedimentoEdit').html('').select2({
						data: [{
							id: null,
							text: ''
						}]
					});

					if ($(this).val() !== '') {
						codDepartamento = $(this).val();
						codDepartamentoTmp = $(this).val();
					} else {
						codDepartamento = 0;
					}

					$.ajax({
						url: '<?php echo base_url('atendimentosLocais/listaDropDownLeitosLocaisProcedimentosAtivos') ?>',
						type: 'post',
						dataType: 'json',
						data: {
							codDepartamento: codDepartamento,
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},
						success: function(codLocalProcedimentoEdit) {

							$("#codLocalProcedimentoEdit").select2({
								data: codLocalProcedimentoEdit,
							})


							$('#codLocalProcedimentoEdit').val(response.codLocalAtendimento);
							$('#codLocalProcedimentoEdit').trigger('change');
							$(document).on('select2:open', () => {
								document.querySelector('.select2-search__field').focus();
							});



						}
					})


				});


				$("#codLocalProcedimentoEdit").on("change", function() {
					if ($(this).val() !== '') {
						codLocalAtendimentoTmp = $(this).val();
					} else {
						codLocalAtendimentoTmp = null;
					}

				});


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
						// remove the text-danger
						$(".text-danger").remove();

						$.ajax({
							url: '<?php echo base_url('faturamentoProcedimentos/editIndividual') ?>',
							type: 'post',
							data: form.serialize(), // /converting the form data into array and sending it to server
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
								$('#faturamentoProcedimentosEditForm-btn').html('Adicionar');
							}
						});

						return false;
					}
				});
				$('#faturamentoProcedimentosEditForm').validate();


			}
		})
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

				$("#faturamentoMateriaisEditForm #codFaturamentoMaterialEdit").val(response.codFaturamentoMaterial);
				$("#faturamentoMateriaisEditForm #quantidadeMaterialEdit").val(response.quantidade);
				$("#faturamentoMateriaisEditForm #observacoesMaterialEdit").val(response.observacoes);



				$("#codFaturamentoMaterialEdit").val(codFaturamentoMaterial);



				$('#codMaterialEdit').html('').select2({
					data: [{
						id: '',
						text: ''
					}]
				});


				if (materiaisTmp !== null) {
					$("#codMaterialEdit").select2({
						data: materiaisTmp,
					})


					if (response.codMaterial !== null) {

						$('#codMaterialEdit').val(response.codMaterial);
						$('#codMaterialEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});
					}

				} else {


					$.ajax({
						url: '<?php echo base_url('itensFarmacia/listaDropDownMateriais') ?>',
						type: 'post',
						dataType: 'json',
						data: {
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},
						success: function(materiaisEdit) {

							materiaisTmp = materiaisEdit;

							$("#codMaterialEdit").select2({
								data: materiaisTmp,
							})

							$('#codMaterialEdit').val(response.codMaterial);
							$('#codMaterialEdit').trigger('change');
							$(document).on('select2:open', () => {
								document.querySelector('.select2-search__field').focus();
							});



						}
					})

				}
				$.ajax({
					url: '<?php echo base_url('departamentos/listaDropDownUnidadesFaturamento') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(codDepartamentoMaterialEdit) {

						$("#codDepartamentoMaterialEdit").select2({
							data: codDepartamentoMaterialEdit,
						})

						$('#codDepartamentoMaterialEdit').val(response.codDepartamento);
						$('#codDepartamentoMaterialEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});



					}
				})

				$("#codDepartamentoMaterialEdit").on("change", function() {


					$('#codLocalMaterialEdit').html('').select2({
						data: [{
							id: null,
							text: ''
						}]
					});

					if ($(this).val() !== '') {
						codDepartamento = $(this).val();
						codDepartamentoTmp = $(this).val();
					} else {
						codDepartamento = 0;
					}

					$.ajax({
						url: '<?php echo base_url('atendimentosLocais/listaDropDownLeitosLocaisProcedimentosAtivos') ?>',
						type: 'post',
						dataType: 'json',
						data: {
							codDepartamento: codDepartamento,
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},

						success: function(codLocalMaterialEdit) {

							$("#codLocalMaterialEdit").select2({
								data: codLocalMaterialEdit,
							})


							$('#codLocalMaterialEdit').val(response.codLocalAtendimento);
							$('#codLocalMaterialEdit').trigger('change');
							$(document).on('select2:open', () => {
								document.querySelector('.select2-search__field').focus();
							});



						}
					})


				});


				$("#codLocalMaterialEdit").on("change", function() {
					if ($(this).val() !== '') {
						codLocalAtendimentoTmp = $(this).val();
					} else {
						codLocalAtendimentoTmp = null;
					}

				});


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
						// remove the text-danger
						$(".text-danger").remove();

						$.ajax({
							url: '<?php echo base_url('faturamentoMateriais/editIndividual') ?>',
							type: 'post',
							data: form.serialize(), // /converting the form data into array and sending it to server
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
								$('#faturamentoMateriaisEditForm-btn').html('Adicionar');
							}
						});

						return false;
					}
				});
				$('#faturamentoMateriaisEditForm').validate();


			}
		})
	}



	function editfaturamentoKits(codFaturamentoKit) {



		$.ajax({
			url: '<?php echo base_url('faturamentoKits/getOne') ?>',
			type: 'post',
			data: {
				codFaturamentoKit: codFaturamentoKit,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#faturamentoKitsEditForm")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#faturamentoKitsEditModal').modal('show');

				$("#faturamentoKitsEditForm #codFaturamentoKitEdit").val(response.codFaturamentoKit);
				$("#faturamentoKitsEditForm #quantidadeKitEdit").val(response.quantidade);
				$("#faturamentoKitsEditForm #observacoesKitEdit").val(response.observacoes);



				$('#codKitEdit').html('').select2({
					data: [{
						id: '',
						text: ''
					}]
				});


				if (kitsTmp !== null) {
					$("#codKitEdit").select2({
						data: kitsTmp,
					})


					if (response.codKit !== null) {

						$('#codKitEdit').val(response.codKit);
						$('#codKitEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});
					}

				} else {


					$.ajax({
						url: '<?php echo base_url('kits/listaDropDownKits') ?>',
						type: 'post',
						dataType: 'json',
						data: {
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},
						success: function(kitsEdit) {

							kitsTmp = kitsEdit;

							$("#codKitEdit").select2({
								data: kitsTmp,
							})

							$('#codKitEdit').val(response.codKit);
							$('#codKitEdit').trigger('change');
							$(document).on('select2:open', () => {
								document.querySelector('.select2-search__field').focus();
							});



						}
					})

				}
				$.ajax({
					url: '<?php echo base_url('departamentos/listaDropDownUnidadesFaturamento') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(codDepartamentoKitEdit) {

						$("#codDepartamentoKitEdit").select2({
							data: codDepartamentoKitEdit,
						})

						$('#codDepartamentoKitEdit').val(response.codDepartamento);
						$('#codDepartamentoKitEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});



					}
				})

				$("#codDepartamentoKitEdit").on("change", function() {


					$('#codLocalKitEdit').html('').select2({
						data: [{
							id: null,
							text: ''
						}]
					});

					if ($(this).val() !== '') {
						codDepartamento = $(this).val();
						codDepartamentoTmp = $(this).val();
					} else {
						codDepartamento = 0;
					}

					$.ajax({
						url: '<?php echo base_url('atendimentosLocais/listaDropDownLeitosLocaisProcedimentosAtivos') ?>',
						type: 'post',
						dataType: 'json',
						data: {
							codDepartamento: codDepartamento,
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},

						success: function(codLocalKitEdit) {

							$("#codLocalKitEdit").select2({
								data: codLocalKitEdit,
							})


							$('#codLocalKitEdit').val(response.codLocalAtendimento);
							$('#codLocalKitEdit').trigger('change');
							$(document).on('select2:open', () => {
								document.querySelector('.select2-search__field').focus();
							});



						}
					})


				});


				$("#codLocalKitEdit").on("change", function() {
					if ($(this).val() !== '') {
						codLocalAtendimentoTmp = $(this).val();
					} else {
						codLocalAtendimentoTmp = null;
					}

				});


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

						var form = $('#faturamentoKitsEditForm');
						// remove the text-danger
						$(".text-danger").remove();

						$.ajax({
							url: '<?php echo base_url('faturamentoKits/editIndividual') ?>',
							type: 'post',
							data: form.serialize(), // /converting the form data into array and sending it to server
							dataType: 'json',
							beforeSend: function() {
								//$('#faturamentoKitsEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
							},
							success: function(response) {

								if (response.success === true) {
									$('#faturamentoKitsEditModal').modal('hide');

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
										$('#data_tablefaturamentoKits').DataTable().ajax.reload(null, false).draw(false);
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
								$('#faturamentoKitsEditForm-btn').html('Adicionar');
							}
						});

						return false;
					}
				});
				$('#faturamentoKitsEditForm').validate();


			}
		})
	}



	function novoMaterial() {

		// reset the form 
		$("#faturamentoMateriaisAddForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#faturamentoMateriaisAddModal').modal('show');



		$("#codFaturaMaterialAddItem").val(codFaturaTmp);
		$("#codAtendimentoMaterialAdd").val(codAtendimentoTmp);

		if (dataDespesaMaterialTmp == null) {
			$("#dataDespesaMaterialAdd").val($("#dataGlobalInicioFaturaAdd").val());

		} else {


			document.getElementById("dataDespesaMaterialAdd").value = dataDespesaMaterialTmp;
		}





		$('#codMaterialAdd').html('').select2({
			data: [{
				id: '',
				text: ''
			}]
		});


		if (materiaisTmp !== null) {


			$("#codMaterialAdd").select2({
				data: materiaisTmp,
			})

		} else {


			$.ajax({
				url: '<?php echo base_url('itensFarmacia/listaDropDownMateriais') ?>',
				type: 'post',
				dataType: 'json',
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
				success: function(materialAdd) {

					materiaisTmp = materialAdd;

					$("#codMaterialAdd").select2({
						data: materiaisTmp,
					})

					$('#codMaterialAdd').val(null);
					$('#codMaterialAdd').trigger('change');
					$(document).on('select2:open', () => {
						document.querySelector('.select2-search__field').focus();
					});



				}
			})

		}
		$.ajax({
			url: '<?php echo base_url('departamentos/listaDropDownUnidadesFaturamento') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(codDepartamentoMaterialAdd) {

				$("#codDepartamentoMaterialAdd").select2({
					data: codDepartamentoMaterialAdd,
				})

				$('#codDepartamentoMaterialAdd').val(codDepartamentoTmp);
				$('#codDepartamentoMaterialAdd').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-container--open .select2-search__field').focus();
				});



			}
		})

		$("#codDepartamentoMaterialAdd").on("change", function() {


			$('#codLocalMaterialAdd').html('').select2({
				data: [{
					id: null,
					text: ''
				}]
			});

			if ($(this).val() !== '') {
				codDepartamento = $(this).val();
				codDepartamentoTmp = $(this).val();
			} else {
				codDepartamento = 0;
			}

			$.ajax({
				url: '<?php echo base_url('atendimentosLocais/listaDropDownLeitosLocaisProcedimentosAtivos') ?>',
				type: 'post',
				dataType: 'json',
				data: {
					codDepartamento: codDepartamento,
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},

				success: function(codLocalMaterialAdd) {

					$("#codLocalMaterialAdd").select2({
						data: codLocalMaterialAdd,
					})


					$('#codLocalMaterialAdd').val(codLocalAtendimentoTmp);
					$('#codLocalMaterialAdd').trigger('change');
					$(document).on('select2:open', () => {
						document.querySelector('.select2-search__field').focus();
					});



				}
			})


		});


		$("#codLocalMaterialAdd").on("change", function() {
			if ($(this).val() !== '') {
				codLocalAtendimentoTmp = $(this).val();
			} else {
				codLocalAtendimentoTmp = null;
			}

		});


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

				dataDespesaMaterialTmp = document.getElementById("dataDespesaMaterialAdd").value;


				var form = $('#faturamentoMateriaisAddForm');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('faturamentoMateriais/addIndividual') ?>',
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

	function novoKit() {

		// reset the form 
		$("#faturamentoKitsAddForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#faturamentoKitsAddModal').modal('show');



		$("#codFaturaKitAddItem").val(codFaturaTmp);
		$("#codAtendimentoKitAdd").val(codAtendimentoTmp);


		if (dataDespesaKitTmp == null) {
			$("#dataDespesaKitAdd").val($("#dataGlobalInicioFaturaAdd").val());

		} else {


			document.getElementById("dataDespesaKitAdd").value = dataDespesaKitTmp;
		}




		$('#codKitAdd').html('').select2({
			data: [{
				id: '',
				text: ''
			}]
		});


		if (kitsTmp !== null) {


			$("#codKitAdd").select2({
				data: kitsTmp,
			})

		} else {


			$.ajax({
				url: '<?php echo base_url('kits/listaDropDownKits') ?>',
				type: 'post',
				dataType: 'json',
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
				success: function(kitsAdd) {

					kitsTmp = kitsAdd;

					$("#codKitAdd").select2({
						data: kitsTmp,
					})

					$('#codKitAdd').val(null);
					$('#codKitAdd').trigger('change');
					$(document).on('select2:open', () => {
						document.querySelector('.select2-search__field').focus();
					});



				}
			})

		}
		$.ajax({
			url: '<?php echo base_url('departamentos/listaDropDownUnidadesFaturamento') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(codDepartamentoKitAdd) {

				$("#codDepartamentoKitAdd").select2({
					data: codDepartamentoKitAdd,
				})

				$('#codDepartamentoKitAdd').val(codDepartamentoTmp);
				$('#codDepartamentoKitAdd').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-container--open .select2-search__field').focus();
				});



			}
		})

		$("#codDepartamentoKitAdd").on("change", function() {


			$('#codLocalKitAdd').html('').select2({
				data: [{
					id: null,
					text: ''
				}]
			});

			if ($(this).val() !== '') {
				codDepartamento = $(this).val();
				codDepartamentoTmp = $(this).val();
			} else {
				codDepartamento = 0;
			}

			$.ajax({
				url: '<?php echo base_url('atendimentosLocais/listaDropDownLeitosLocaisProcedimentosAtivos') ?>',
				type: 'post',
				dataType: 'json',
				data: {
					codDepartamento: codDepartamento,
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},

				success: function(codLocalKitAdd) {

					$("#codLocalKitAdd").select2({
						data: codLocalKitAdd,
					})


					$('#codLocalKitAdd').val(codLocalAtendimentoTmp);
					$('#codLocalKitAdd').trigger('change');
					$(document).on('select2:open', () => {
						document.querySelector('.select2-search__field').focus();
					});



				}
			})


		});


		$("#codLocalKitAdd").on("change", function() {
			if ($(this).val() !== '') {
				codLocalAtendimentoTmp = $(this).val();
			} else {
				codLocalAtendimentoTmp = null;
			}

		});


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

				dataDespesaKitTmp = document.getElementById("dataDespesaKitAdd").value;


				var form = $('#faturamentoKitsAddForm');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('faturamentoKits/addIndividual') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#faturamentoKitsAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function(response) {

						if (response.success === true) {
							$('#faturamentoKitsAddModal').modal('hide');

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
								$('#data_tablefaturamentoKits').DataTable().ajax.reload(null, false).draw(false);
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
						$('#faturamentoKitsAddForm-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#faturamentoKitsAddForm').validate();
	}



	function removefaturamentoMateriais(codFaturamentoMaterial) {
		Swal.fire({
			title: 'Você tem certeza que deseja remover este material?',
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


	function removefaturamentoKits(codFaturamentoKit) {
		Swal.fire({
			title: 'Você tem certeza que deseja remover este Kit?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {

			if (result.value) {
				$.ajax({
					url: '<?php echo base_url('faturamentoKits/remove') ?>',
					type: 'post',
					data: {
						codFaturamentoKit: codFaturamentoKit,
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
								$('#data_tablefaturamentoKits').DataTable().ajax.reload(null, false).draw(false);
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