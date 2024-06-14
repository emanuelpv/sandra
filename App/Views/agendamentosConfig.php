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

<link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/fullcalendar/main.css">
<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-md-4 mt-2">
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Configuração de Agendamentos</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addagendamentosConfig()" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-danger" data-toggle="tooltip" data-placement="top" title="Remover Vagas" onclick="cancelarAgendamentosConfig()"> <i class="fa fa-plus"></i>Cancelar vagas</button>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">

					<div class="col-12 col-sm-12">
						<div class="card card-primary card-tabs">
							<div class="card-header p-0 pt-1">
								<ul class="nav nav-tabs" id="aba-tab" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" id="aba-agandasPendentes-tab" data-toggle="pill" href="#aba-agandasPendentes" role="tab" aria-controls="aba-agandasPendentes" aria-selected="true">Pendentes de liberação<span style="margin-left: 10px;" id="qtdPendente" class="right badge badge-warning"></span></a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="aba-liberadasHoje-tab" data-toggle="pill" href="#aba-liberadasHoje" role="tab" aria-controls="aba-liberadasHoje" aria-selected="false">Agendas liberadas Hoje<span style="margin-left: 10px;" id="qtdLiberadaHoje" class="right badge badge-warning"></span></a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="aba-buscaAvancada-tab" data-toggle="pill" href="#aba-buscaAvancada" role="tab" aria-controls="aba-buscaAvancada" aria-selected="false">Busca Avançada</a>
									</li>
								</ul>
							</div>
							<div class="card-body">

							<h3> HORA DO SERVIDOR</h3>
								<h1><?php echo date('H: i'); ?></h1>

								<div class="tab-content" id="aba-tabContent">
									<div class="tab-pane fade show active" id="aba-agandasPendentes" role="tabpanel" aria-labelledby="aba-agandasPendentes-tab">
										<table id="data_tableagendamentosConfig" class="table table-striped table-hover table-sm">
											<thead>
												<tr>
													<th>Código</th>
													<th>Especialidade</th>
													<th>Especialista</th>
													<th>Período</th>
													<th>Tempo</th>
													<th>Intervalo</th>
													<th>Status</th>
													<th>Tipo</th>
													<th>Vagas Criadas</th>
													<th>Vagas Abertas</th>
													<th>Criada por</th>
													<th></th>
												</tr>
											</thead>
										</table>
									</div>
									<div class="tab-pane fade" id="aba-liberadasHoje" role="tabpanel" aria-labelledby="aba-liberadasHoje-tab">
										<table id="data_tableagendamentosConfigLiberadasHoje" class="table table-striped table-hover table-sm">
											<thead>
												<tr>
													<th>Código</th>
													<th>Especialidade</th>
													<th>Especialista</th>
													<th>Período</th>
													<th>Tempo</th>
													<th>Intervalo</th>
													<th>Status</th>
													<th>Tipo</th>
													<th>Vagas Criadas</th>
													<th>Vagas Abertas</th>
													<th>Criada por</th>
													<th></th>
												</tr>
											</thead>
										</table>

									</div>
									<div class="tab-pane fade" id="aba-buscaAvancada" role="tabpanel" aria-labelledby="aba-buscaAvancada-tab">
										<div class="row">
											<div class="col-md-12">
												<form id="buscaAvancada" class="pl-3 pr-3">
													<div class="row">
														<input type="hidden" id="<?php echo csrf_token() ?>buscaAvancada" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
													</div>
													<div class="row">
														<div class="col-md-4">
															<div class="form-group">
																<label for="codEspecialidadeBuscaAvancada"> Especialidade: <span class="text-danger">*</span> </label>
																<select id="codEspecialidadeBuscaAvancada" name="codEspecialidade" class="custom-select">
																	<option value=""></option>
																</select>
															</div>
														</div>
														<div class="col-md-4">
															<div class="form-group">
																<label for="codEspecialistaBuscaAvancada"> Especialista: <span class="text-danger">*</span> </label>
																<select id="codEspecialistaBuscaAvancada" name="codEspecialista" class="custom-select">
																	<option value="0"></option>
																</select>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-3">
															<div class="form-group">
																<label for="dataInicioBuscaAvancada"> Data Início: </label>
																<input type="date" id="dataInicioBuscaAvancada" name="dataInicio" class="form-control" dateISO="true" required>
															</div>
														</div>

														<div class="col-md-3">
															<div class="form-group">
																<label for="dataEncerramentoBuscaAvancada"> Data Encerramento: </label>
																<input type="date" id="dataEncerramentoBuscaAvancada" name="dataEncerramento" class="form-control" dateISO="true" required>
															</div>
														</div>

													</div>
													<div style="margin-bottom:20px" class="row">
														<button type="button" onclick="buscarAgora()" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Buscar">Buscar</button>
													</div>
												</form>
											</div>
										</div>
										<div class="row">

											<table id="data_tableagendamentosConfigBuscaAvancada" class="table table-striped table-hover table-sm">
												<thead>
													<tr>
														<th>Código</th>
														<th>Especialidade</th>
														<th>Especialista</th>
														<th>Período</th>
														<th>Tempo</th>
														<th>Intervalo</th>
														<th>Status</th>
														<th>Tipo</th>
														<th>Vagas Criadas</th>
														<th>Vagas Abertas</th>
														<th>Criada por</th>
														<th></th>
													</tr>
												</thead>
											</table>
										</div>

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
<div id="add-modalagendamentosConfig" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Configuração de Agendamentos</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="add-formagendamentosConfig" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>add-formagendamentosConfig" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
						<input type="hidden" id="codConfig" name="codConfig" class="form-control" placeholder="Código" maxlength="11" required>
					</div>



					<div class="row">

						<div class="col-md-12">
							<div class="card card-secondary">

								<div class="card-header">
									<h3 class="card-title">ESPECIALIDADE</h3>

									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
										</button>
									</div>
								</div>
								<div class="card-body">

									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label for="codEspecialidadeAdd"> Especialidade: <span class="text-danger">*</span> </label>
												<select id="codEspecialidadeAdd" name="codEspecialidade" class="custom-select" required>
													<option value=""></option>
												</select>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="codEspecialista"> Especialista: <span class="text-danger">*</span> </label>
												<select id="codEspecialistaAdd" name="codEspecialista" class="custom-select" required>
													<option value="0"></option>
												</select>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="codLocalAdd"> Local de Atendimento: <span class="text-danger">*</span> </label>
												<select id="codLocalAdd" name="codLocal" class="custom-select" required>
												</select>
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>


					<div class="row">

						<div class="col-md-8">
							<div class="card card-secondary">

								<div class="card-header">
									<h3 class="card-title">PERÍODO</h3>
									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
										</button>
									</div>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label for="dataInicioAdd"> Data Início: </label>
												<input type="date" id="dataInicioAdd" name="dataInicio" class="form-control" dateISO="true" required>
											</div>
										</div>

										<div class="col-md-3">
											<div class="form-group">
												<label for="dataEncerramentoAdd"> Data Encerramento: </label>
												<input type="date" id="dataEncerramentoAdd" name="dataEncerramento" class="form-control" dateISO="true" required>
											</div>
										</div>

									</div>

									<div class="row">
										<div class="col-md-1">
											<div class="form-group">
												<div class="row">
													Segunda
												</div>
												<div class="row">
													<div class="icheck-primary d-inline center">
														<style>
															input[type=checkbox] {
																transform: scale(1.8);
																margin-left: 10px
															}
														</style>
														<input id="segundaAdd" name="segunda" type="checkbox">
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-1">
											<div class="form-group">
												<div class="row">
													Terça
												</div>
												<div class="row">
													<div class="icheck-primary d-inline center">
														<style>
															input[type=checkbox] {
																transform: scale(1.8);
																margin-left: 10px
															}
														</style>
														<input id="tercaAdd" name="terca" type="checkbox">
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-1">
											<div class="form-group">
												<div class="row">
													Quarta
												</div>
												<div class="row">
													<div class="icheck-primary d-inline center">
														<style>
															input[type=checkbox] {
																transform: scale(1.8);
																margin-left: 10px
															}
														</style>
														<input id="quartaAdd" name="quarta" type="checkbox">
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-1">
											<div class="form-group">
												<div class="row">
													Quinta
												</div>
												<div class="row">
													<div class="icheck-primary d-inline center">
														<style>
															input[type=checkbox] {
																transform: scale(1.8);
																margin-left: 10px
															}
														</style>
														<input id="quintaAdd" name="quinta" type="checkbox">
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-1">
											<div class="form-group">
												<div class="row">
													Sexta
												</div>
												<div class="row">
													<div class="icheck-primary d-inline center">
														<style>
															input[type=checkbox] {
																transform: scale(1.8);
																margin-left: 10px
															}
														</style>
														<input id="sextaAdd" name="sexta" type="checkbox">
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-1">
											<div class="form-group">
												<div class="row">
													Sábado
												</div>
												<div class="row">
													<div class="icheck-primary d-inline center">
														<style>
															input[type=checkbox] {
																transform: scale(1.8);
																margin-left: 10px
															}
														</style>
														<input id="sabadoAdd" name="sabado" type="checkbox">
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-1">
											<div class="form-group">
												<div class="row">
													Domingo
												</div>
												<div class="row">
													<div class="icheck-primary d-inline center">
														<style>
															input[type=checkbox] {
																transform: scale(1.8);
																margin-left: 10px
															}
														</style>
														<input id="domingoAdd" name="domingo" type="checkbox">
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>


						<div class="col-md-4">
							<div class="card card-secondary">

								<div class="card-header">
									<h3 class="card-title">HORÁRIO DE TRABALHO</h3>

									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
										</button>
									</div>
								</div>
								<div class="card-body">
									<div class="row">

										<div class="col-md-6">
											<div class="form-group">
												<label for="horaInicioAdd"> Hora Início: <span class="text-danger">*</span> </label>
												<input type="time" id="horaInicioAdd" name="horaInicio" class="form-control" placeholder="HoraInicio" required>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="horaEncerramentoAdd"> Hora Fim: <span class="text-danger">*</span> </label>
												<input type="time" id="horaEncerramentoAdd" name="horaEncerramento" class="form-control" placeholder="Hora encerramento" required>
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>


					</div>




					<div class="row">
						<div class="col-md-6">
							<div class="card card-secondary">

								<div class="card-header">
									<h3 class="card-title">TEMPO DE ATENDIMENTO</h3>

									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
										</button>
									</div>
								</div>
								<div class="card-body">

									<div class="row">


										<div class="col-md-3">
											<div class="form-group">
												<label for="tempoAtendimentoAdd"> Duração: <span class="text-danger">*</span> </label>
												<input type="number" id="tempoAtendimentoAdd" name="tempoAtendimento" class="form-control" placeholder="Em minutos" maxlength="11" number="true" required>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="intervaloAtendimentoAdd"> Intervalo: <span class="text-danger">*</span> </label>
												<input type="number" id="intervaloAtendimentoAdd" value=0 name="intervaloAtendimento" class="form-control" placeholder="Em minutos" maxlength="11" number="true" required>
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>


						<div class="col-md-6">
							<div class="card card-secondary">

								<div class="card-header">
									<h3 class="card-title">CLASSIFICAÇÃO DO ATENDIMENTO</h3>

									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
										</button>
									</div>
								</div>
								<div class="card-body">
									<div class="row">

										<div class="col-md-6">
											<div class="form-group">
												<label for="codTipoAgendamentoAdd"> Tipo Agendamento: <span class="text-danger">*</span> </label>
												<select id="codTipoAgendamentoAdd" name="codTipoAgendamento" class="custom-select" required>
													<option value="0"></option>
												</select>
											</div>
										</div>


										<div class="col-md-6">
											<div class="form-group">
												<label for="codStatusAgendamentoAdd"> Status Agendamento: <span class="text-danger">*</span> </label>
												<select id="codStatusAgendamentoAdd" name="codStatusAgendamento" class="custom-select" required>

												</select>
											</div>
										</div>
									</div>

									<div class="row">
										Como os pacientes devem considerar a ordem do atendimento no dia?
									</div>
									<div class="row">

										<div class="form-group clearfix">
											<div class="row">
												<div class="icheck-primary d-inline">
													<input type="radio" id="horaMarcada" name="ordemAtendimento" value=1>
													<label for="horaMarcada">Hora marcada
													</label>
												</div>
											</div>
											<div class="row">
												<div class="icheck-primary d-inline">
													<input type="radio" id="ordemChegada" name="ordemAtendimento" value=2>
													<label for="ordemChegada">Ordem de chegada
													</label>
												</div>
											</div>
										</div>

									</div>


								</div>
							</div>
						</div>


					</div>


					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="add-formagendamentosConfig-btn">Adicionar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>


<div id="cancelar-modalagendamentosConfig" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Cancelar Vagas</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="cancelar-formagendamentosConfig" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>cancelar-formagendamentosConfig" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
						<input type="hidden" id="codConfigCancelar" name="codConfig" class="form-control" placeholder="Código" maxlength="11" required>
					</div>



					<div class="row">

						<div class="col-md-12">
							<div class="card card-secondary">

								<div class="card-header">
									<h3 class="card-title">ESPECIALIDADE</h3>

									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
										</button>
									</div>
								</div>
								<div class="card-body">

									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label for="codEspecialidadeCancelar"> Especialidade: <span class="text-danger">*</span> </label>
												<select id="codEspecialidadeCancelar" name="codEspecialidade" class="custom-select" required>
													<option value=""></option>
												</select>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="codEspecialistaCancelar"> Especialista: <span class="text-danger">*</span> </label>
												<select id="codEspecialistaCancelar" name="codEspecialista" class="custom-select" required>
													<option value="0"></option>
												</select>
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>






					<div class="row">

						<div class="col-md-12">
							<div class="card card-secondary">

								<div class="card-header">
									<h3 class="card-title">PERÍODO</h3>
									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
										</button>
									</div>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label for="dataInicioCancelar"> Data Início: </label>
												<input type="date" id="dataInicioCancelar" name="dataInicio" class="form-control" dateISO="true" required>
											</div>
										</div>

										<div class="col-md-3">
											<div class="form-group">
												<label for="dataEncerramentoCancelar"> Data Encerramento: </label>
												<input type="date" id="dataEncerramentoCancelar" name="dataEncerramento" class="form-control" dateISO="true" required>
											</div>
										</div>

									</div>

								</div>
							</div>
						</div>
					</div>


					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar">Buscar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>

					<div class="row">

						<div style="margin-top:10px" class="row">
							<div class="col-sm-12">
								<div id="slotsLivres"> </div>
							</div>
						</div>
					</div>


				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>

<!-- Add modal content -->
<div id="edit-modalagendamentosConfig" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Configuração de Agendamentos</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">


				<div class="row">
					<div class="col-12 col-sm-12">
						<div class="card card-primary card-tabs">
							<div class="card-header p-0 pt-1">
								<ul class="nav nav-tabs" id="abas-tab" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" id="abas-parametros-tab" data-toggle="pill" href="#abas-parametros" role="tab" aria-controls="abas-parametros" aria-selected="true">Parametros</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="abas-calendario-tab" data-toggle="pill" href="#abas-calendario" role="tab" aria-controls="abas-calendario" aria-selected="false">Calendário</a>
									</li>
								</ul>
							</div>
							<div class="card-body">
								<div class="tab-content" id="abas-tabContent">
									<div class="tab-pane fade show active" id="abas-parametros" role="tabpanel" aria-labelledby="abas-parametros-tab">

										<form id="edit-formagendamentosConfig" class="pl-3 pr-3">
											<div class="row">
												<input type="hidden" id="<?php echo csrf_token() ?>edit-formagendamentosConfig" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

												<input type="hidden" id="codConfigEdit" name="codConfig" class="form-control" placeholder="Código" maxlength="11" required>
											</div>



											<div class="row">

												<div class="col-md-12">
													<div class="card card-secondary">

														<div class="card-header">
															<h3 class="card-title">ESPECIALIDADE</h3>

															<div class="card-tools">
																<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
																</button>
															</div>
														</div>
														<div class="card-body">

															<div class="row">
																<div class="col-md-4">
																	<div class="form-group">
																		<label for="codEspecialidadeEdit"> Especialidade: <span class="text-danger">*</span> </label>
																		<select disabled id="codEspecialidadeEdit" name="codEspecialidade" class="custom-select" required>
																			<option value=""></option>
																		</select>
																	</div>
																</div>
																<div class="col-md-4">
																	<div class="form-group">
																		<label for="codEspecialista"> Especialista: <span class="text-danger">*</span> </label>
																		<select disabled id="codEspecialistaEdit" name="codEspecialista" class="custom-select" required>
																			<option value="0"></option>
																		</select>
																	</div>
																</div>
																<div class="col-md-4">
																	<div class="form-group">
																		<label for="codLocalEdit"> Local de Atendimento: <span class="text-danger">*</span> </label>
																		<select id="codLocalEdit" name="codLocal" class="custom-select" required>
																		</select>
																	</div>
																</div>
															</div>

														</div>
													</div>
												</div>
											</div>


											<div class="row">

												<div class="col-md-8">
													<div class="card card-secondary">

														<div class="card-header">
															<h3 class="card-title">PERÍODO</h3>
															<div class="card-tools">
																<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
																</button>
															</div>
														</div>
														<div class="card-body">
															<div class="row">
																<div class="col-md-3">
																	<div class="form-group">
																		<label for="dataInicioEdit"> Data Início: </label>
																		<input disabled type="date" id="dataInicioEdit" name="dataInicio" class="form-control" dateISO="true" required>
																	</div>
																</div>

																<div class="col-md-3">
																	<div class="form-group">
																		<label for="dataEncerramentoEdit"> Data Encerramento: </label>
																		<input disabled type="date" id="dataEncerramentoEdit" name="dataEncerramento" class="form-control" dateISO="true" required>
																	</div>
																</div>

															</div>

															<div class="row">
																<div class="col-md-1">
																	<div class="form-group">
																		<div class="row">
																			Segunda
																		</div>
																		<div class="row">
																			<div class="icheck-primary d-inline center">
																				<style>
																					input[type=checkbox] {
																						transform: scale(1.8);
																						margin-left: 10px
																					}
																				</style>
																				<input disabled id="segundaEdit" name="segunda" type="checkbox">
																			</div>
																		</div>
																	</div>
																</div>
																<div class="col-md-1">
																	<div class="form-group">
																		<div class="row">
																			Terça
																		</div>
																		<div class="row">
																			<div class="icheck-primary d-inline center">
																				<style>
																					input[type=checkbox] {
																						transform: scale(1.8);
																						margin-left: 10px
																					}
																				</style>
																				<input disabled id="tercaEdit" name="terca" type="checkbox">
																			</div>
																		</div>
																	</div>
																</div>
																<div class="col-md-1">
																	<div class="form-group">
																		<div class="row">
																			Quarta
																		</div>
																		<div class="row">
																			<div class="icheck-primary d-inline center">
																				<style>
																					input[type=checkbox] {
																						transform: scale(1.8);
																						margin-left: 10px
																					}
																				</style>
																				<input disabled id="quartaEdit" name="quarta" type="checkbox">
																			</div>
																		</div>
																	</div>
																</div>
																<div class="col-md-1">
																	<div class="form-group">
																		<div class="row">
																			Quinta
																		</div>
																		<div class="row">
																			<div class="icheck-primary d-inline center">
																				<style>
																					input[type=checkbox] {
																						transform: scale(1.8);
																						margin-left: 10px
																					}
																				</style>
																				<input disabled id="quintaEdit" name="quinta" type="checkbox">
																			</div>
																		</div>
																	</div>
																</div>
																<div class="col-md-1">
																	<div class="form-group">
																		<div class="row">
																			Sexta
																		</div>
																		<div class="row">
																			<div class="icheck-primary d-inline center">
																				<style>
																					input[type=checkbox] {
																						transform: scale(1.8);
																						margin-left: 10px
																					}
																				</style>
																				<input disabled id="sextaEdit" name="sexta" type="checkbox">
																			</div>
																		</div>
																	</div>
																</div>
																<div class="col-md-1">
																	<div class="form-group">
																		<div class="row">
																			Sábado
																		</div>
																		<div class="row">
																			<div class="icheck-primary d-inline center">
																				<style>
																					input[type=checkbox] {
																						transform: scale(1.8);
																						margin-left: 10px
																					}
																				</style>
																				<input disabled id="sabadoEdit" name="sabado" type="checkbox">
																			</div>
																		</div>
																	</div>
																</div>
																<div class="col-md-1">
																	<div class="form-group">
																		<div class="row">
																			Domingo
																		</div>
																		<div class="row">
																			<div class="icheck-primary d-inline center">
																				<style>
																					input[type=checkbox] {
																						transform: scale(1.8);
																						margin-left: 10px
																					}
																				</style>
																				<input disabled id="domingoEdit" name="domingo" type="checkbox">
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>


												<div class="col-md-4">
													<div class="card card-secondary">

														<div class="card-header">
															<h3 class="card-title">HORÁRIO DE TRABALHO</h3>

															<div class="card-tools">
																<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
																</button>
															</div>
														</div>
														<div class="card-body">
															<div class="row">

																<div class="col-md-6">
																	<div class="form-group">
																		<label for="horaInicioEdit"> Hora Início: <span class="text-danger">*</span> </label>
																		<input disabled type="time" id="horaInicioEdit" name="horaInicio" class="form-control" placeholder="HoraInicio" required>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<label for="horaEncerramentoEdit"> Hora Fim: <span class="text-danger">*</span> </label>
																		<input disabled type="time" id="horaEncerramentoEdit" name="horaEncerramento" class="form-control" placeholder="Hora encerramento" required>
																	</div>
																</div>
															</div>

														</div>
													</div>
												</div>


											</div>




											<div class="row">
												<div class="col-md-6">
													<div class="card card-secondary">

														<div class="card-header">
															<h3 class="card-title">TEMPO DE ATENDIMENTO</h3>

															<div class="card-tools">
																<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
																</button>
															</div>
														</div>
														<div class="card-body">

															<div class="row">


																<div class="col-md-3">
																	<div class="form-group">
																		<label for="tempoAtendimentoEdit"> Duração: <span class="text-danger">*</span> </label>
																		<input disabled type="number" id="tempoAtendimentoEdit" name="tempoAtendimento" class="form-control" placeholder="Em minutos" maxlength="11" number="true" required>
																	</div>
																</div>
																<div class="col-md-3">
																	<div class="form-group">
																		<label for="intervaloAtendimentoEdit"> Intervalo: <span class="text-danger">*</span> </label>
																		<input disabled type="number" id="intervaloAtendimentoEdit" value=0 name="intervaloAtendimento" class="form-control" placeholder="Em minutos" maxlength="11" number="true" required>
																	</div>
																</div>
															</div>

														</div>
													</div>
												</div>


												<div class="col-md-6">
													<div class="card card-secondary">

														<div class="card-header">
															<h3 class="card-title">CLASSIFICAÇÃO DO ATENDIMENTO</h3>

															<div class="card-tools">
																<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
																</button>
															</div>
														</div>
														<div class="card-body">
															<div class="row">

																<div class="col-md-6">
																	<div class="form-group">
																		<label for="codTipoAgendamentoEdit"> Tipo Agendamento: <span class="text-danger">*</span> </label>
																		<select id="codTipoAgendamentoEdit" name="codTipoAgendamento" class="custom-select" required>
																			<option value="0"></option>
																		</select>
																	</div>
																</div>


																<div class="col-md-6">
																	<div class="form-group">
																		<label for="codStatusAgendamentoEdit"> Status Agendamento: <span class="text-danger">*</span> </label>
																		<select id="codStatusAgendamentoEdit" name="codStatusAgendamento" class="custom-select" required>

																		</select>
																	</div>
																</div>
															</div>

															<div class="row">
																Como os pacientes devem considerar a ordem do atendimento no dia?
															</div>
															<div class="row">

																<div class="form-group clearfix">
																	<div class="row">
																		<div class="icheck-primary d-inline">
																			<input type="radio" id="horaMarcadaEdit" name="ordemAtendimento" value=1>
																			<label for="horaMarcadaEdit">Hora marcada
																			</label>
																		</div>
																	</div>
																	<div class="row">
																		<div class="icheck-primary d-inline">
																			<input type="radio" id="ordemChegadaEdit" name="ordemAtendimento" value=2>
																			<label for="ordemChegadaEdit">Ordem de chegada
																			</label>
																		</div>
																	</div>
																</div>

															</div>


														</div>
													</div>
												</div>


											</div>


											<div class="form-group text-center">
												<div class="btn-group">
													<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="add-formagendamentosConfig-btn">Salvar</button>
													<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
												</div>
											</div>
										</form>
									</div>
									<div class="tab-pane fade" id="abas-calendario" role="tabpanel" aria-labelledby="abas-calendario-tab">

										<!-- Main content -->
										<section class="content">
											<div class="container-fluid">
												<div class="row">
													<div class="col-md-3">
														<div class="sticky-top mb-3">
															<div class="card">
																<div class="card-header">
																	<h4 class="card-title">Draggable Events</h4>
																</div>
																<div class="card-body">
																	<!-- the events -->
																	<div id="external-events">
																		<div class="external-event bg-success">Lunch</div>
																		<div class="external-event bg-warning">Go home</div>
																		<div class="external-event bg-info">Do homework</div>
																		<div class="external-event bg-primary">Work on UI design</div>
																		<div class="external-event bg-danger">Sleep tight</div>
																		<div class="checkbox">
																			<label for="drop-remove">
																				<input type="checkbox" id="drop-remove">
																				remove after drop
																			</label>
																		</div>
																	</div>
																</div>
																<!-- /.card-body -->
															</div>
															<!-- /.card -->
															<div class="card">
																<div class="card-header">
																	<h3 class="card-title">Create Event</h3>
																</div>
																<div class="card-body">
																	<div class="btn-group" style="width: 100%; margin-bottom: 10px;">
																		<ul class="fc-color-picker" id="color-chooser">
																			<li><a class="text-primary" href="#"><i class="fas fa-square"></i></a></li>
																			<li><a class="text-warning" href="#"><i class="fas fa-square"></i></a></li>
																			<li><a class="text-success" href="#"><i class="fas fa-square"></i></a></li>
																			<li><a class="text-danger" href="#"><i class="fas fa-square"></i></a></li>
																			<li><a class="text-muted" href="#"><i class="fas fa-square"></i></a></li>
																		</ul>
																	</div>
																	<!-- /btn-group -->
																	<div class="input-group">
																		<input id="new-event" type="text" class="form-control" placeholder="Event Title">

																		<div class="input-group-append">
																			<button id="add-new-event" type="button" class="btn btn-primary">Adicionar</button>
																		</div>
																		<!-- /btn-group -->
																	</div>
																	<!-- /input-group -->
																</div>
															</div>
														</div>
													</div>
													<!-- /.col -->
													<div class="col-md-9">
														<div class="card card-primary">
															<div class="card-body p-0">
																<!-- THE CALENDAR -->
																<div id="calendar"></div>
															</div>
															<!-- /.card-body -->
														</div>
														<!-- /.card -->
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div><!-- /.container-fluid -->
										</section>
									</div>
								</div>
							</div>
							<!-- /.card -->
						</div>
					</div>
				</div>




			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /.content -->
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/moment/moment.min.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/fullcalendar/main.js"></script>

<?php
echo view('tema/rodape');
?>




<script>
	$(function() {


		avisoPesquisa('Agendamento', 2);

		$('#data_tableagendamentosConfig').DataTable({
			"bDestroy": true,
			"pageLength": 100,
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('agendamentosConfig/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					programadas: 1,
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			},
			"drawCallback": function(settings, json) {
				var api = this.api();
				var qtdPendente = api.rows().count();
				document.getElementById("qtdPendente").innerHTML = qtdPendente;
			},
		});

		$('#data_tableagendamentosConfigLiberadasHoje').DataTable({
			"bDestroy": true,
			"paging": true,
			"pageLength": 100,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('agendamentosConfig/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					liberadasHoje: 1,
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			},
			"drawCallback": function(settings, json) {
				var api = this.api();
				var qtdLiberadaHoje = api.rows().count();
				document.getElementById("qtdLiberadaHoje").innerHTML = qtdLiberadaHoje
			}
		});





		$.ajax({
			url: '<?php echo base_url('agendamentosConfig/listaDropDownEspecialidadesDisponivelMarcacao') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(codEspecialidadeBuscaAvancada) {

				$("#codEspecialidadeBuscaAvancada").select2({
					data: codEspecialidadeBuscaAvancada,
				})

				$('#codEspecialidadeBuscaAvancada').val(null); // Select the option with a value of '1'
				$('#codEspecialidadeBuscaAvancada').trigger('change');


			}
		})


		$("#codEspecialidadeBuscaAvancada").on("change", function() {


			if ($(this).val() !== '') {
				codEspecialidade = $(this).val();
			} else {
				codEspecialidade = 0;
			}

			$.ajax({
				url: '<?php echo base_url('agendamentosConfig/listaDropDownEspecialistasDisponivelMarcacao') ?>',
				type: 'post',
				dataType: 'json',
				data: {
					codEspecialidade: codEspecialidade,
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
				success: function(codEspecialistaBuscaAvancada) {

					$("#codEspecialistaBuscaAvancada").empty();
					$('#codEspecialistaBuscaAvancada').trigger('change');


					$("#codEspecialistaBuscaAvancada").select2({
						data: codEspecialistaBuscaAvancada,
					})



				}
			})

		});



	});

	function buscarAgora() {

		$('#data_tableagendamentosConfigBuscaAvancada').DataTable({
			"bDestroy": true,
			"paging": true,
			"pageLength": 100,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('agendamentosConfig/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					buscaAvancada: 1,
					codEspecialidade: $('#codEspecialidadeBuscaAvancada').val(),
					codEspecialista: $('#codEspecialistaBuscaAvancada').val(),
					dataInicio: $('#dataInicioBuscaAvancada').val(),
					dataEncerramento: $('#dataEncerramentoBuscaAvancada').val(),
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});
	}

	function addagendamentosConfig() {
		// reset the form 
		$("#add-formagendamentosConfig")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#add-modalagendamentosConfig').modal('show');


		$.ajax({
			url: '<?php echo base_url('agendamentosConfig/listaDropDownStatusAgendamento') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(statusAgendamentoAdd) {

				$("#codStatusAgendamentoAdd").select2({
					data: statusAgendamentoAdd,
					dropdownParent: $('#add-modalagendamentosConfig .modal-content'),
				})

				$('#codStatusAgendamentoAdd').val(null); // Select the option with a value of '1'
				$('#codStatusAgendamentoAdd').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})

		$.ajax({
			url: '<?php echo base_url('agendamentosConfig/listaDropDownTipoAgendamento') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(tipoAgendamentoAdd) {

				$("#codTipoAgendamentoAdd").select2({
					data: tipoAgendamentoAdd,
					dropdownParent: $('#add-modalagendamentosConfig .modal-content'),
				})

				$('#codTipoAgendamentoAdd').val(null); // Select the option with a value of '1'
				$('#codTipoAgendamentoAdd').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})



		$.ajax({
			url: '<?php echo base_url('agendamentosConfig/listaDropDownEspecialidadesDisponivelMarcacao') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(agendamentosConfigAdd) {

				$("#codEspecialidadeAdd").select2({
					data: agendamentosConfigAdd,
					dropdownParent: $('#add-modalagendamentosConfig .modal-content'),
				})

				$('#codEspecialidadeAdd').val(null); // Select the option with a value of '1'
				$('#codEspecialidadeAdd').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})


		$("#codEspecialidadeAdd").on("change", function() {


			if ($(this).val() !== '') {
				codEspecialidade = $(this).val();
			} else {
				codEspecialidade = 0;
			}

			$.ajax({
				url: '<?php echo base_url('agendamentosConfig/listaDropDownEspecialistasDisponivelMarcacao') ?>',
				type: 'post',
				dataType: 'json',
				data: {
					codEspecialidade: codEspecialidade,
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
				success: function(EspecialistasAdd) {

					$("#codEspecialistaAdd").empty();
					$('#codEspecialistaAdd').trigger('change');
					$(document).on('select2:open', () => {
						document.querySelector('.select2-search__field').focus();
					});

					$("#codEspecialistaAdd").select2({
						data: EspecialistasAdd,
						dropdownParent: $('#add-modalagendamentosConfig .modal-content'),
					})



				}
			})

		});


		$.ajax({
			url: '<?php echo base_url('departamentos/listaDropDownDepartamentosAtendimento') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(LocalAdd) {

				$("#codLocalAdd").empty();
				$('#codLocalAdd').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

				$("#codLocalAdd").select2({
					data: LocalAdd,
					dropdownParent: $('#add-modalagendamentosConfig .modal-content'),
				})

				$('#codLocalAdd').val(null); // Select the option with a value of '1'
				$('#codLocalAdd').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})



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

				var form = $('#add-formagendamentosConfig');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('agendamentosConfig/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {


						Swal.fire({
							title: 'Estamos criando a agenda',
							html: 'Aguarde....',
							timerProgressBar: true,
							didOpen: () => {
								Swal.showLoading()


							}

						})


					},
					success: function(response) {

						if (response.success === true) {

							$('#add-modalagendamentosConfig').modal('hide');
							$('#data_tableagendamentosConfig').DataTable().ajax.reload(null, false).draw(false);
							$('#data_tableagendamentosConfigLiberadasHoje').DataTable().ajax.reload(null, false).draw(false);
							if ($.fn.DataTable.isDataTable('#data_tableagendamentosConfigBuscaAvancada')) {
								$('#data_tableagendamentosConfigBuscaAvancada').DataTable().ajax.reload(null, false).draw(false);
							}


							var Toast = Swal.mixin({
								toast: true,
								position: 'top-end',
								showConfirmButton: false,
								timer: 5000
							});
							Toast.fire({
								icon: 'success',
								title: response.messages
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
									icon: 'error',
									title: response.messages,
									showConfirmButton: true,
									confirmButtonText: 'Ok',
								})

							}
						}
						$('#add-formagendamentosConfig-btn').html('Adicionar');
					}
				})

				return false;
			}
		});
		$('#add-formagendamentosConfig').validate();
	}

	function cancelarAgendamento(codAgendamento) {



		Swal.fire({
			title: 'Tem certeza que deseja cancelar esta Vaga?',
			html: '<span class="right badge badge-danger">A vaga NÃO retornará para lista de vagas abertas!</span>',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {

			if (result.value) {


				$.ajax({
					url: '<?php echo base_url('agendamentosConfig/cancelarAgendamento') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						codAgendamento: codAgendamento,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(cancelarAgendamentoResultado) {


						var Toast = Swal.mixin({
							toast: true,
							position: 'bottom-end',
							showConfirmButton: false,
							timer: 2000
						});
						Toast.fire({
							icon: 'success',
							title: cancelarAgendamentoResultado.messages
						})

						var form = $('#cancelar-formagendamentosConfig');

						$.ajax({
							url: '<?php echo base_url('agendamentosConfig/listaVagasAvulsas') ?>',
							type: 'post',
							data: form.serialize(), // /converting the form data into array and sending it to server
							dataType: 'json',

							success: function(response2) {

								if (response2.success === true) {

									document.getElementById('slotsLivres').innerHTML = '';
									document.getElementById('slotsLivres').innerHTML = response2.slotsLivres;

								}
							}
						})



					}
				})


			}
		})




	}

	function cancelarAgendamentosConfig() {
		// reset the form 

		$("#cancelar-formagendamentosConfig")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#cancelar-modalagendamentosConfig').modal('show');


		$.ajax({
			url: '<?php echo base_url('agendamentosConfig/listaDropDownEspecialidadesDisponivelMarcacao') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(agendamentosConfigAdd) {

				$("#codEspecialidadeCancelar").select2({
					data: agendamentosConfigAdd,
					dropdownParent: $('#cancelar-modalagendamentosConfig .modal-content'),
				})

				$('#codEspecialidadeCancelar').val(null); // Select the option with a value of '1'
				$('#codEspecialidadeCancelar').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})


		$("#codEspecialidadeCancelar").on("change", function() {


			if ($(this).val() !== '') {
				codEspecialidade = $(this).val();
			} else {
				codEspecialidade = 0;
			}

			$.ajax({
				url: '<?php echo base_url('agendamentosConfig/listaDropDownEspecialistasDisponivelMarcacao') ?>',
				type: 'post',
				dataType: 'json',
				data: {
					codEspecialidade: codEspecialidade,
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
				success: function(EspecialistasAdd) {

					$("#codEspecialistaRemoverd").empty();
					$('#codEspecialistaCancelar').trigger('change');
					$(document).on('select2:open', () => {
						document.querySelector('.select2-search__field').focus();
					});

					$("#codEspecialistaCancelar").select2({
						data: EspecialistasAdd,
						dropdownParent: $('#cancelar-modalagendamentosConfig .modal-content'),
					})



				}
			})

		});


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




				var form = $('#cancelar-formagendamentosConfig');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('agendamentosConfig/listaVagasAvulsas') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',

					success: function(response) {

						if (response.success === true) {

							Swal.close();
							document.getElementById('slotsLivres').innerHTML = '';
							document.getElementById('slotsLivres').innerHTML = response.slotsLivres;



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
								Swal.close();
								Swal.fire({
									position: 'bottom-end',
									icon: 'error',
									title: response.messages,
									showConfirmButton: false,
									timer: 1500
								})

							}
						}
					}
				}).always(
					Swal.fire({
						title: 'Estamos buscando vagas para cancelar ',
						html: 'Aguarde....',
						timerProgressBar: true,
						didOpen: () => {
							Swal.showLoading()
						}

					}))

				return false;
			}
		});
		$('#cancelar-formagendamentosConfig').validate();
	}

	function editagendamentosConfig(codConfig) {
		$.ajax({
			url: '<?php echo base_url('agendamentosConfig/getOne') ?>',
			type: 'post',
			data: {
				codConfig: codConfig,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#edit-formagendamentosConfig")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#edit-modalagendamentosConfig').modal('show');

				$("#edit-formagendamentosConfig #codConfigEdit").val(response.codConfig);
				$("#edit-formagendamentosConfig #codEspecialistaEdit").val(response.codEspecialista);
				$("#edit-formagendamentosConfig #dataCriacaoEdit").val(response.dataCriacao);
				$("#edit-formagendamentosConfig #dataInicioEdit").val(response.dataInicio);
				$("#edit-formagendamentosConfig #horaInicioEdit").val(response.horaInicio);
				$("#edit-formagendamentosConfig #dataEncerramentoEdit").val(response.dataEncerramento);
				$("#edit-formagendamentosConfig #horaEncerramentoEdit").val(response.horaEncerramento);
				$("#edit-formagendamentosConfig #tempoAtendimentoEdit").val(response.tempoAtendimento);
				$("#edit-formagendamentosConfig #intervaloAtendimentoEdit").val(response.intervaloAtendimento);
				$("#edit-formagendamentosConfig #dataAtualizacaoEdit").val(response.dataAtualizacao);

				if (response.ordemAtendimento == 1) {
					document.getElementById("horaMarcadaEdit").checked = true;
				} else {

				}
				if (response.ordemAtendimento == 2) {
					document.getElementById("ordemChegadaEdit").checked = true;
				}



				if (response.segunda == 1) {
					document.getElementById("segundaEdit").checked = true;
				}


				if (response.terca == 1) {
					document.getElementById("tercaEdit").checked = true;
				}


				if (response.quarta == 1) {
					document.getElementById("quartaEdit").checked = true;
				}


				if (response.quinta == 1) {
					document.getElementById("quintaEdit").checked = true;
				}


				if (response.sexta == 1) {
					document.getElementById("sextaEdit").checked = true;
				}


				if (response.sabado == 1) {
					document.getElementById("sabadoEdit").checked = true;
				}


				if (response.domingo == 1) {
					document.getElementById("domingoEdit").checked = true;
				}


				$.ajax({
					url: '<?php echo base_url('agendamentosConfig/listaDropDownStatusAgendamento') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(statusAgendamentoEdit) {

						$("#codStatusAgendamentoEdit").select2({
							data: statusAgendamentoEdit,
							dropdownParent: $('#edit-modalagendamentosConfig .modal-content'),
						})

						$('#codStatusAgendamentoEdit').val(response.codStatusAgendamento); // Select the option with a value of '1'
						$('#codStatusAgendamentoEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

					}
				})

				$.ajax({
					url: '<?php echo base_url('agendamentosConfig/listaDropDownTipoAgendamento') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(tipoAgendamentoEdit) {

						$("#codTipoAgendamentoEdit").select2({
							data: tipoAgendamentoEdit,
							dropdownParent: $('#edit-modalagendamentosConfig .modal-content'),
						})

						$('#codTipoAgendamentoEdit').val(response.codTipoAgendamento); // Select the option with a value of '1'
						$('#codTipoAgendamentoEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

					}
				})


				$.ajax({
					url: '<?php echo base_url('departamentos/listaDropDownDepartamentosAtendimento') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(codLocalEdit) {

						$("#codLocalEdit").empty();
						$('#codLocalEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

						$("#codLocalEdit").select2({
							data: codLocalEdit,
							dropdownParent: $('#edit-modalagendamentosConfig .modal-content'),
						})

						$('#codLocalEdit').val(response.codLocal); // Select the option with a value of '1'
						$('#codLocalEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

					}
				})



				$.ajax({
					url: '<?php echo base_url('agendamentosConfig/listaDropDownEspecialidadesDisponivelMarcacao') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(agendamentosConfigEdit) {

						$("#codEspecialidadeEdit").select2({
							data: agendamentosConfigEdit,
							dropdownParent: $('#edit-modalagendamentosConfig .modal-content'),
						})

						$('#codEspecialidadeEdit').val(response.codEspecialidade); // Select the option with a value of '1'
						$('#codEspecialidadeEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

					}
				})


				$("#codEspecialidadeEdit").on("change", function() {


					if ($(this).val() !== '') {
						codEspecialidade = $(this).val();
					} else {
						codEspecialidade = 0;
					}

					$.ajax({
						url: '<?php echo base_url('agendamentosConfig/listaDropDownEspecialistasDisponivelMarcacao') ?>',
						type: 'post',
						dataType: 'json',
						data: {
							codEspecialidade: codEspecialidade,
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},
						success: function(EspecialistasEdit) {

							$("#codEspecialistaEdit").empty();
							$('#codEspecialistaEdit').trigger('change');
							$(document).on('select2:open', () => {
								document.querySelector('.select2-search__field').focus();
							});

							$("#codEspecialistaEdit").select2({
								data: EspecialistasEdit,
								dropdownParent: $('#edit-modalagendamentosConfig .modal-content'),
							})



						}
					})


				});




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
						var form = $('#edit-formagendamentosConfig');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('agendamentosConfig/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#edit-formagendamentosConfig-btn').html('<i class="fa fa-spinner fa-spin"></i>');
							},
							success: function(response) {

								if (response.success === true) {

									$('#edit-modalagendamentosConfig').modal('hide');


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

										$('#data_tableagendamentosConfig').DataTable().ajax.reload(null, false).draw(false);
										$('#data_tableagendamentosConfigLiberadasHoje').DataTable().ajax.reload(null, false).draw(false);
										if ($.fn.DataTable.isDataTable('#data_tableagendamentosConfigBuscaAvancada')) {
											$('#data_tableagendamentosConfigBuscaAvancada').DataTable().ajax.reload(null, false).draw(false);
										}
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
											timer: 1500
										})

									}
								}
								$('#edit-formagendamentosConfig-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#edit-formagendamentosConfig').validate();

			}
		});
	}

	function removeagendamentosConfig(codConfig) {
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
					url: '<?php echo base_url('agendamentosConfig/remove') ?>',
					type: 'post',
					data: {
						codConfig: codConfig,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					dataType: 'json',
					success: function(response) {

						if (response.success === true) {
							Swal.fire({
								position: 'bottom-end',
								icon: 'success',
								title: response.messages,
								showConfirmButton: false,
								timer: 1500
							}).then(function() {
								$('#data_tableagendamentosConfig').DataTable().ajax.reload(null, false).draw(false);
								$('#data_tableagendamentosConfigLiberadasHoje').DataTable().ajax.reload(null, false).draw(false);
								if ($.fn.DataTable.isDataTable('#data_tableagendamentosConfigBuscaAvancada')) {
									$('#data_tableagendamentosConfigBuscaAvancada').DataTable().ajax.reload(null, false).draw(false);
								}
							})
						} else {
							Swal.fire({
								position: 'bottom-end',
								icon: 'error',
								title: response.messages,
								showConfirmButton: false,
								timer: 1500
							})

						}
					}
				}).always(
					Swal.fire({
						title: 'Estamos removendo o agendamento',
						html: 'Aguarde....',
						timerProgressBar: true,
						didOpen: () => {
							Swal.showLoading()


						}

					}))
			}
		})
	}

	function mudaStatusAgendamentosConfig(codConfig, codStatusAgendamento) {
		Swal.fire({
			title: 'Você tem certeza que deseja mudar de status?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {

			if (result.value) {
				$.ajax({
					url: '<?php echo base_url('agendamentosConfig/mudaStatusAgendamentosConfig') ?>',
					type: 'post',
					data: {
						codConfig: codConfig,
						codStatusAgendamento: codStatusAgendamento,
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
								$('#data_tableagendamentosConfig').DataTable().ajax.reload(null, false).draw(false);
								$('#data_tableagendamentosConfigLiberadasHoje').DataTable().ajax.reload(null, false).draw(false);
								if ($.fn.DataTable.isDataTable('#data_tableagendamentosConfigBuscaAvancada')) {
									$('#data_tableagendamentosConfigBuscaAvancada').DataTable().ajax.reload(null, false).draw(false);
								}
							})
						} else {
							Swal.fire({
								position: 'bottom-end',
								icon: 'error',
								title: response.messages,
								showConfirmButton: false,
								timer: 1500
							})

						}
					}
				}).always(
					Swal.fire({
						title: 'Estamos removendo o agendamento',
						html: 'Aguarde....',
						timerProgressBar: true,
						didOpen: () => {
							Swal.showLoading()


						}

					}))
			}
		})
	}
</script>
<script>
	$(function() {

		/* initialize the external events
		 -----------------------------------------------------------------*/
		function ini_events(ele) {
			ele.each(function() {

				// create an Event Object (https://fullcalendar.io/docs/event-object)
				// it doesn't need to have a start or end
				var eventObject = {
					title: $.trim($(this).text()) // use the element's text as the event title
				}

				// store the Event Object in the DOM element so we can get to it later
				$(this).data('eventObject', eventObject)

				// make the event draggable using jQuery UI
				$(this).draggable({
					zIndex: 1070,
					revert: true, // will cause the event to go back to its
					revertDuration: 0 //  original position after the drag
				})

			})
		}

		ini_events($('#external-events div.external-event'))

		/* initialize the calendar
		 -----------------------------------------------------------------*/
		//Date for the calendar events (dummy data)
		var date = new Date()
		var d = date.getDate(),
			m = date.getMonth(),
			y = date.getFullYear()

		var Calendar = FullCalendar.Calendar;
		var Draggable = FullCalendar.Draggable;

		var containerEl = document.getElementById('external-events');
		var checkbox = document.getElementById('drop-remove');
		var calendarEl = document.getElementById('calendar');

		// initialize the external events
		// -----------------------------------------------------------------

		new Draggable(containerEl, {
			itemSelector: '.external-event',
			eventData: function(eventEl) {
				return {
					title: eventEl.innerText,
					backgroundColor: window.getComputedStyle(eventEl, null).getPropertyValue('background-color'),
					borderColor: window.getComputedStyle(eventEl, null).getPropertyValue('background-color'),
					textColor: window.getComputedStyle(eventEl, null).getPropertyValue('color'),
				};
			}
		});

		var calendar = new Calendar(calendarEl, {
			headerToolbar: {
				left: 'prev,next today',
				center: 'title',
				right: 'dayGridMonth,timeGridWeek,timeGridDay'
			},
			themeSystem: 'bootstrap',
			//Random default events
			events: [{
					title: 'All Day Event',
					start: new Date(y, m, 1),
					backgroundColor: '#f56954', //red
					borderColor: '#f56954', //red
					allDay: true
				},
				{
					title: 'Long Event',
					start: new Date(y, m, d - 5),
					end: new Date(y, m, d - 2),
					backgroundColor: '#f39c12', //yellow
					borderColor: '#f39c12' //yellow
				},
				{
					title: 'Meeting',
					start: new Date(y, m, d, 10, 30),
					allDay: false,
					backgroundColor: '#0073b7', //Blue
					borderColor: '#0073b7' //Blue
				},
				{
					title: 'Lunch',
					start: new Date(y, m, d, 12, 0),
					end: new Date(y, m, d, 14, 0),
					allDay: false,
					backgroundColor: '#00c0ef', //Info (aqua)
					borderColor: '#00c0ef' //Info (aqua)
				},
				{
					title: 'Birthday Party',
					start: new Date(y, m, d + 1, 19, 0),
					end: new Date(y, m, d + 1, 22, 30),
					allDay: false,
					backgroundColor: '#00a65a', //Success (green)
					borderColor: '#00a65a' //Success (green)
				},
				{
					title: 'Click for Google',
					start: new Date(y, m, 28),
					end: new Date(y, m, 29),
					url: 'https://www.google.com/',
					backgroundColor: '#3c8dbc', //Primary (light-blue)
					borderColor: '#3c8dbc' //Primary (light-blue)
				}
			],
			editable: true,
			droppable: true, // this allows things to be dropped onto the calendar !!!
			drop: function(info) {
				// is the "remove after drop" checkbox checked?
				if (checkbox.checked) {
					// if so, remove the element from the "Draggable Events" list
					info.draggedEl.parentNode.removeChild(info.draggedEl);
				}
			}
		});

		calendar.render();
		// $('#calendar').fullCalendar()

		/* ADDING EVENTS */
		var currColor = '#3c8dbc' //Red by default
		// Color chooser button
		$('#color-chooser > li > a').click(function(e) {
			e.preventDefault()
			// Save color
			currColor = $(this).css('color')
			// Add color effect to button
			$('#add-new-event').css({
				'background-color': currColor,
				'border-color': currColor
			})
		})
		$('#add-new-event').click(function(e) {
			e.preventDefault()
			// Get value and make sure it is not null
			var val = $('#new-event').val()
			if (val.length == 0) {
				return
			}

			// Create events
			var event = $('<div />')
			event.css({
				'background-color': currColor,
				'border-color': currColor,
				'color': '#fff'
			}).addClass('external-event')
			event.text(val)
			$('#external-events').prepend(event)

			// Add draggable funtionality
			ini_events(event)

			// Remove event from text input
			$('#new-event').val('')
		})
	})
</script>