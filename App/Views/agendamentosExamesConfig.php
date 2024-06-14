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
						<div class="col-md-4 mt-2">
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Configuração de Agendamento de Exames</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addagendamentosExamesConfig()" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
						</div>

						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-danger" data-toggle="tooltip" data-placement="top" title="Remover Vagas" onclick="cancelarAgendamentosExamesConfig()"> <i class="fa fa-plus"></i>Cancelar vagas</button>
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
								<div class="tab-content" id="aba-tabContent">
									<div class="tab-pane fade show active" id="aba-agandasPendentes" role="tabpanel" aria-labelledby="aba-agandasPendentes-tab">
										<table id="data_tableagendamentosExamesConfig" class="table table-striped table-hover table-sm">
											<thead>
												<tr>
													<th>Código</th>
													<th>ExameLista</th>
													<th>Período</th>
													<th>Tempo</th>
													<th>Intervalo</th>
													<th>Status Exame</th>
													<th>TipoExame</th>
													<th>Vagas Criadas</th>
													<th>Vagas Abertas</th>
													<th>Criada por</th>
													<th></th>
												</tr>
											</thead>
										</table>
									</div>
									<div class="tab-pane fade" id="aba-liberadasHoje" role="tabpanel" aria-labelledby="aba-liberadasHoje-tab">
										<table id="data_tableagendamentosExamesConfigLiberadasHoje" class="table table-striped table-hover table-sm">
											<thead>
												<tr>
													<th>Código</th>
													<th>ExameLista</th>
													<th>Período</th>
													<th>Tempo</th>
													<th>Intervalo</th>
													<th>Status Exame</th>
													<th>TipoExame</th>
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

											<table id="data_tableagendamentosExamesConfigBuscaAvancada" class="table table-striped table-hover table-sm">
												<thead>
													<tr>
														<th>Código</th>
														<th>ExameLista</th>
														<th>Período</th>
														<th>Tempo</th>
														<th>Intervalo</th>
														<th>Status Exame</th>
														<th>TipoExame</th>
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
<div id="add-modalagendamentosExamesConfig" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Configuração de AgendamentosExames</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="add-formagendamentosExamesConfig" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>add-formagendamentosExamesConfig" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
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
												<label for="codExameListaAdd"> ExameLista: <span class="text-danger">*</span> </label>
												<select id="codExameListaAdd" name="codExameLista" class="custom-select" required>
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
												<label for="codTipoExameAdd"> Tipo Exame: <span class="text-danger">*</span> </label>
												<select id="codTipoExameAdd" name="codTipoExame" class="custom-select" required>
													<option value="0"></option>
												</select>
											</div>
										</div>


										<div class="col-md-6">
											<div class="form-group">
												<label for="codStatusExameAdd"> Status Exame: <span class="text-danger">*</span> </label>
												<select id="codStatusExameAdd" name="codStatusExame" class="custom-select" required>

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
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="add-formagendamentosExamesConfig-btn">Adicionar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>



<div id="cancelar-modalagendamentosExamesConfig" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Cancelar Vagas</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="cancelar-formagendamentosExamesConfig" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>cancelar-formagendamentosExamesConfig" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
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
<div id="edit-modalagendamentosExamesConfig" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Configuração de AgendamentosExames</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<form id="edit-formagendamentosExamesConfig" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>edit-formagendamentosExamesConfig" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

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
												<label for="codExameListaEdit"> ExameLista: <span class="text-danger">*</span> </label>
												<select disabled id="codExameListaEdit" name="codExameLista" class="custom-select" required>
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
												<label for="codTipoExameEdit"> Tipo Exame: <span class="text-danger">*</span> </label>
												<select id="codTipoExameEdit" name="codTipoExame" class="custom-select" required>
													<option value="0"></option>
												</select>
											</div>
										</div>


										<div class="col-md-6">
											<div class="form-group">
												<label for="codStatusExameEdit"> Status Exame: <span class="text-danger">*</span> </label>
												<select id="codStatusExameEdit" name="codStatusExame" class="custom-select" required>

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
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="add-formagendamentosExamesConfig-btn">Salvar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>

			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /.content -->
<?php
echo view('tema/rodape');
?>
<script>
	avisoPesquisa('Agendamento', 2);

	$(function() {
		$('#data_tableagendamentosExamesConfig').DataTable({
			"paging": true,
			"pageLength": 100,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('agendamentosExamesConfig/getAll') ?>',
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

		$('#data_tableagendamentosExamesConfigLiberadasHoje').DataTable({
			"paging": true,
			"lengthChange": false,
			"pageLength": 100,
			"searching": true,
			"ordering": false,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('agendamentosExamesConfig/getAll') ?>',
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
			url: '<?php echo base_url('agendamentosExamesConfig/listaDropDownAgendamentosExamesListaDisponivelMarcacao') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(agendamentosExamesConfigAdd) {

				$("#codEspecialidadeBuscaAvancada").select2({
					data: agendamentosExamesConfigAdd,
				})

				$('#codEspecialidadeBuscaAvancada').val(null); // Select the option with a value of '1'
				$('#codEspecialidadeBuscaAvancada').trigger('change');


			}
		})


		$("#codEspecialidadeBuscaAvancada").on("change", function() {


			if ($(this).val() !== '') {
				codExameLista = $(this).val();
			} else {
				codExameLista = 0;
			}

			$.ajax({
				url: '<?php echo base_url('agendamentosExamesConfig/listaDropDownEspecialistasDisponivelMarcacao') ?>',
				type: 'post',
				dataType: 'json',
				data: {
					codExameLista: codExameLista,
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
				success: function(EspecialistasAdd) {

					$("#codEspecialistaBuscaAvancada").empty();
					$('#codEspecialistaBuscaAvancada').trigger('change');


					$("#codEspecialistaBuscaAvancada").select2({
						data: EspecialistasAdd,
					})



				}
			})

		});



	});



	function mudaStatusAgendamentosExamesConfig(codConfig, codStatusExame) {
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
					url: '<?php echo base_url('agendamentosExamesConfig/mudaStatusAgendamentosExamesConfig') ?>',
					type: 'post',
					data: {
						codConfig: codConfig,
						codStatusExame: codStatusExame,
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

								$('#data_tableagendamentosExamesConfig').DataTable().ajax.reload(null, false).draw(false);
								$('#data_tableagendamentosExamesConfigLiberadasHoje').DataTable().ajax.reload(null, false).draw(false);
								if ($.fn.DataTable.isDataTable('#data_tableagendamentosExamesConfigBuscaAvancada')) {
									$('#data_tableagendamentosExamesConfigBuscaAvancada').DataTable().ajax.reload(null, false).draw(false);
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



	function buscarAgora() {

		$('#data_tableagendamentosExamesConfigBuscaAvancada').DataTable({
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
				"url": '<?php echo base_url('agendamentosExamesConfig/getAll') ?>',
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

	function addagendamentosExamesConfig() {
		// reset the form 
		$("#add-formagendamentosExamesConfig")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#add-modalagendamentosExamesConfig').modal('show');


		$.ajax({
			url: '<?php echo base_url('agendamentosExamesConfig/listaDropDownTipoExame') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(tipoExameAdd) {

				$("#codTipoExameAdd").select2({
					data: tipoExameAdd,
				})

				$('#codTipoExameAdd').val(null); // Select the option with a value of '1'
				$('#codTipoExameAdd').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})


		$.ajax({
			url: '<?php echo base_url('agendamentosExamesConfig/listaDropDownStatusExame') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(statusExameAdd) {

				$("#codStatusExameAdd").select2({
					data: statusExameAdd,
				})

				$('#codStatusExameAdd').val(null); // Select the option with a value of '1'
				$('#codStatusExameAdd').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})

		$.ajax({
			url: '<?php echo base_url('agendamentosExamesConfig/listaDropDownAgendamentosExamesListaDisponivelMarcacao') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(agendamentosExamesConfigAdd) {

				$("#codExameListaAdd").select2({
					data: agendamentosExamesConfigAdd,
				})

				$('#codExameListaAdd').val(null); // Select the option with a value of '1'
				$('#codExameListaAdd').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})


		$("#codExameListaAdd").on("change", function() {


			if ($(this).val() !== '') {
				codExameLista = $(this).val();
			} else {
				codExameLista = 0;
			}

			$.ajax({
				url: '<?php echo base_url('agendamentosExamesConfig/listaDropDownEspecialistasDisponivelMarcacao') ?>',
				type: 'post',
				dataType: 'json',
				data: {
					codExameLista: codExameLista,
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

				var form = $('#add-formagendamentosExamesConfig');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('agendamentosExamesConfig/add') ?>',
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

							$('#add-modalagendamentosExamesConfig').modal('hide');
							$('#data_tableagendamentosExamesConfig').DataTable().ajax.reload(null, false).draw(false);
							$('#data_tableagendamentosExamesConfigLiberadasHoje').DataTable().ajax.reload(null, false).draw(false);


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
									position: 'bottom-end',
									icon: 'error',
									title: response.messages,
									showConfirmButton: false,
									timer: 1500
								})

							}
						}
						$('#add-formagendamentosExamesConfig-btn').html('Adicionar');
					}
				})

				return false;
			}
		});
		$('#add-formagendamentosExamesConfig').validate();
	}




	function cancelarExame(codExame) {



		Swal.fire({
			title: 'Tem certeza que deseja cancelar este Exame?',
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
					url: '<?php echo base_url('agendamentosExames/cancelarExame') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						codExame: codExame,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(cancelarExameResultado) {


						var Toast = Swal.mixin({
							toast: true,
							position: 'bottom-end',
							showConfirmButton: false,
							timer: 2000
						});
						Toast.fire({
							icon: 'success',
							title: cancelarExameResultado.messages
						})

						var form = $('#cancelar-formagendamentosExamesConfig');

						$.ajax({
							url: '<?php echo base_url('agendamentosExamesConfig/listaVagasAvulsas') ?>',
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



	function cancelarAgendamentosExamesConfig() {
		// reset the form 

		$("#cancelar-formagendamentosExamesConfig")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#cancelar-modalagendamentosExamesConfig').modal('show');



		$.ajax({
			url: '<?php echo base_url('agendamentosExamesConfig/listaDropDownAgendamentosExamesListaDisponivelMarcacao') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(agendamentosExamesConfigCancelar) {

				$("#codEspecialidadeCancelar").select2({
					data: agendamentosExamesConfigCancelar,
				})

				$('#codEspecialidadeCancelar').val(null); // Select the option with a value of '1'
				$('#codEspecialidadeCancelar').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})





		$.ajax({
			url: '<?php echo base_url('agendamentosExamesConfig/listaDropDownEspecialistasDisponivelMarcacao') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(especialistaCancelar) {

				$("#codEspecialistaCancelar").select2({
					data: especialistaCancelar,
				})

				$('#codEspecialistaCancelar').val(null); // Select the option with a value of '1'
				$('#codEspecialistaCancelar').trigger('change');
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




				var form = $('#cancelar-formagendamentosExamesConfig');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('agendamentosExamesConfig/listaVagasAvulsas') ?>',
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
		$('#cancelar-formagendamentosExamesConfig').validate();
	}



	function editagendamentosExamesConfig(codConfig) {
		$.ajax({
			url: '<?php echo base_url('agendamentosExamesConfig/getOne') ?>',
			type: 'post',
			data: {
				codConfig: codConfig,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#edit-formagendamentosExamesConfig")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#edit-modalagendamentosExamesConfig').modal('show');

				$("#edit-formagendamentosExamesConfig #codConfigEdit").val(response.codConfig);
				$("#edit-formagendamentosExamesConfig #codEspecialistaEdit").val(response.codEspecialista);
				$("#edit-formagendamentosExamesConfig #dataCriacaoEdit").val(response.dataCriacao);
				$("#edit-formagendamentosExamesConfig #dataInicioEdit").val(response.dataInicio);
				$("#edit-formagendamentosExamesConfig #horaInicioEdit").val(response.horaInicio);
				$("#edit-formagendamentosExamesConfig #dataEncerramentoEdit").val(response.dataEncerramento);
				$("#edit-formagendamentosExamesConfig #horaEncerramentoEdit").val(response.horaEncerramento);
				$("#edit-formagendamentosExamesConfig #tempoAtendimentoEdit").val(response.tempoAtendimento);
				$("#edit-formagendamentosExamesConfig #intervaloAtendimentoEdit").val(response.intervaloAtendimento);
				$("#edit-formagendamentosExamesConfig #dataAtualizacaoEdit").val(response.dataAtualizacao);

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
					url: '<?php echo base_url('agendamentosExamesConfig/listaDropDownStatusExame') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(statusExameEdit) {

						$("#codStatusExameEdit").select2({
							data: statusExameEdit,
						})

						$('#codStatusExameEdit').val(response.codStatusExame); // Select the option with a value of '1'
						$('#codStatusExameEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

					}
				})

				$.ajax({
					url: '<?php echo base_url('agendamentosExamesConfig/listaDropDownTipoExame') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(tipoExameEdit) {

						$("#codTipoExameEdit").select2({
							data: tipoExameEdit,
						})

						$('#codTipoExameEdit').val(response.codTipoExame); // Select the option with a value of '1'
						$('#codTipoExameEdit').trigger('change');
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
						})

						$('#codLocalEdit').val(response.codLocal); // Select the option with a value of '1'
						$('#codLocalEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

					}
				})



				$.ajax({
					url: '<?php echo base_url('agendamentosExamesConfig/listaDropDownAgendamentosExamesListaDisponivelMarcacao') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(agendamentosExamesConfigEdit) {

						$("#codExameListaEdit").select2({
							data: agendamentosExamesConfigEdit,
						})

						$('#codExameListaEdit').val(response.codExameLista); // Select the option with a value of '1'
						$('#codExameListaEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

					}
				})


				$("#codExameListaEdit").on("change", function() {


					if ($(this).val() !== '') {
						codExameLista = $(this).val();
					} else {
						codExameLista = 0;
					}

					$.ajax({
						url: '<?php echo base_url('agendamentosExamesConfig/listaDropDownEspecialistasDisponivelMarcacao') ?>',
						type: 'post',
						dataType: 'json',
						data: {
							codExameLista: codExameLista,
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
						var form = $('#edit-formagendamentosExamesConfig');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('agendamentosExamesConfig/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#edit-formagendamentosExamesConfig-btn').html('<i class="fa fa-spinner fa-spin"></i>');
							},
							success: function(response) {

								if (response.success === true) {

									$('#edit-modalagendamentosExamesConfig').modal('hide');


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

										$('#data_tableagendamentosExamesConfig').DataTable().ajax.reload(null, false).draw(false);
										$('#data_tableagendamentosExamesConfigLiberadasHoje').DataTable().ajax.reload(null, false).draw(false);
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
								$('#edit-formagendamentosExamesConfig-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#edit-formagendamentosExamesConfig').validate();

			}
		});
	}

	function removeagendamentosExamesConfig(codConfig) {
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
					url: '<?php echo base_url('agendamentosExamesConfig/remove') ?>',
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
								$('#data_tableagendamentosExamesConfig').DataTable().ajax.reload(null, false).draw(false);
								$('#data_tableagendamentosExamesConfigLiberadasHoje').DataTable().ajax.reload(null, false).draw(false);
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
				});
			}
		})
	}
</script>