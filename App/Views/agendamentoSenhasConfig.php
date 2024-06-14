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
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Configuração de Agendamentos</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addagendamentoSenhasConfig()" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-danger" data-toggle="tooltip" data-placement="top" title="Remover Vagas" onclick="cancelarAgendamentoSenhasConfig()"> <i class="fa fa-plus"></i>Cancelar vagas</button>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<table id="data_tableagendamentoSenhasConfig" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>Código</th>
								<th>Departamento</th>
								<th>Período</th>
								<th>Tempo</th>
								<th>Qtde Atendentes</th>
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
				<!-- /.card-body -->
			</div>
			<!-- /.card -->
		</div>
		<!-- /.col -->
	</div>
	<!-- /.row -->
</section>
<!-- Add modal content -->
<div id="add-modalagendamentoSenhasConfig" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Configuração de Agendamentos</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="add-formagendamentoSenhasConfig" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>add-formagendamentoSenhasConfig" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
						<input type="hidden" id="codConfig" name="codConfig" class="form-control" placeholder="Código" maxlength="11" required>
					</div>



					<div class="row">

						<div class="col-md-12">
							<div class="card card-secondary">

								<div class="card-header">
									<h3 class="card-title">DEPARTAMENTO</h3>

									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
										</button>
									</div>
								</div>
								<div class="card-body">

									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label for="codDepartamentoAdd"> Departamento: <span class="text-danger">*</span> </label>
												<select id="codDepartamentoAdd" name="codDepartamento" class="custom-select" required>
													<option value=""></option>
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
										<div class="col-md-5">
											<div class="form-group">
												<label for="qtdeAtendentesAdd"> Qtde Atendentes: <span class="text-danger">*</span> </label>
												<input type="number" id="qtdeAtendentesAdd" value=0 name="qtdeAtendentes" class="form-control" placeholder="Em minutos" maxlength="11" number="true" required>
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
												<label for="codTipoAgendamentoAdd"> Tipo Agenda: <span class="text-danger">*</span> </label>
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
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="add-formagendamentoSenhasConfig-btn">Adicionar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>


<div id="cancelar-modalagendamentoSenhasConfig" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Cancelar Vagas</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="cancelar-formagendamentoSenhasConfig" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>cancelar-formagendamentoSenhasConfig" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
						<input type="hidden" id="codConfigCancelar" name="codConfig" class="form-control" placeholder="Código" maxlength="11" required>
					</div>



					<div class="row">

						<div class="col-md-12">
							<div class="card card-secondary">

								<div class="card-header">
									<h3 class="card-title">DEPARTAMENTO</h3>

									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
										</button>
									</div>
								</div>
								<div class="card-body">

									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label for="codDepartamentoCancelar"> Departamento: <span class="text-danger">*</span> </label>
												<select id="codDepartamentoCancelar" name="codDepartamento" class="custom-select" required>
													<option value=""></option>
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
<div id="edit-modalagendamentoSenhasConfig" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Configuração de Agendamentos</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<form id="edit-formagendamentoSenhasConfig" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>edit-formagendamentoSenhasConfig" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codConfigEdit" name="codConfig" class="form-control" placeholder="Código" maxlength="11" required>
					</div>



					<div class="row">

						<div class="col-md-12">
							<div class="card card-secondary">

								<div class="card-header">
									<h3 class="card-title">DEPARTAMENTO</h3>

									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
										</button>
									</div>
								</div>
								<div class="card-body">

									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label for="codDepartamentoEdit"> Departamento: <span class="text-danger">*</span> </label>
												<select disabled id="codDepartamentoEdit" name="codDepartamento" class="custom-select" required>
													<option value=""></option>
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
										<div class="col-md-5">
											<div class="form-group">
												<label for="qtdeAtendentesEdit"> Qtde Atendentes: <span class="text-danger">*</span> </label>
												<input disabled type="number" id="qtdeAtendentesEdit" value=0 name="qtdeAtendentes" class="form-control" placeholder="Em minutos" maxlength="11" number="true" required>
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
												<label for="codTipoAgendamentoEdit"> Tipo Agenda: <span class="text-danger">*</span> </label>
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
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="add-formagendamentoSenhasConfig-btn">Salvar</button>
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
	$(function() {
		
		avisoPesquisa('Agendamento',2);
		
		$('#data_tableagendamentoSenhasConfig').DataTable({
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('agendamentoSenhasConfig/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});


	});

	function addagendamentoSenhasConfig() {
		// reset the form 
		$("#add-formagendamentoSenhasConfig")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#add-modalagendamentoSenhasConfig').modal('show');


		$.ajax({
			url: '<?php echo base_url('agendamentoSenhasConfig/listaDropDownStatusAgendamento') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(statusAgendamentoAdd) {

				$("#codStatusAgendamentoAdd").select2({
					data: statusAgendamentoAdd,
				})

				$('#codStatusAgendamentoAdd').val(0); // Select the option with a value of '1'
				$('#codStatusAgendamentoAdd').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})

		$.ajax({
			url: '<?php echo base_url('agendamentoSenhasConfig/listaDropDownTipoSenha') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(tipoAgendamentoAdd) {

				$("#codTipoAgendamentoAdd").select2({
					data: tipoAgendamentoAdd,
				})

				$('#codTipoAgendamentoAdd').val(1); // Select the option with a value of '1'
				$('#codTipoAgendamentoAdd').trigger('change');
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
			success: function(agendamentoSenhasConfigAdd) {

				$("#codDepartamentoAdd").select2({
					data: agendamentoSenhasConfigAdd,
				})

				$('#codDepartamentoAdd').val(null); // Select the option with a value of '1'
				$('#codDepartamentoAdd').trigger('change');
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

				var form = $('#add-formagendamentoSenhasConfig');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('agendamentoSenhasConfig/add') ?>',
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

							$('#add-modalagendamentoSenhasConfig').modal('hide');
							$('#data_tableagendamentoSenhasConfig').DataTable().ajax.reload(null, false).draw(false);


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
						$('#add-formagendamentoSenhasConfig-btn').html('Adicionar');
					}
				})

				return false;
			}
		});
		$('#add-formagendamentoSenhasConfig').validate();
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
					url: '<?php echo base_url('agendamentoSenhasConfig/cancelarAgendamento') ?>',
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

						var form = $('#cancelar-formagendamentoSenhasConfig');

						$.ajax({
							url: '<?php echo base_url('agendamentoSenhasConfig/listaVagasAvulsas') ?>',
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

	function cancelarAgendamentoSenhasConfig() {
		// reset the form 

		$("#cancelar-formagendamentoSenhasConfig")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#cancelar-modalagendamentoSenhasConfig').modal('show');


		$.ajax({
			url: '<?php echo base_url('departamentos/listaDropDownDepartamentosAtendimento') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(agendamentoSenhasConfigAdd) {

				$("#codDepartamentoCancelar").select2({
					data: agendamentoSenhasConfigAdd,
				})

				$('#codDepartamentoCancelar').val(null); // Select the option with a value of '1'
				$('#codDepartamentoCancelar').trigger('change');
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




				var form = $('#cancelar-formagendamentoSenhasConfig');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('agendamentoSenhasConfig/listaVagasAvulsas') ?>',
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
		$('#cancelar-formagendamentoSenhasConfig').validate();
	}

	function editagendamentoSenhasConfig(codConfig) {
		$.ajax({
			url: '<?php echo base_url('agendamentoSenhasConfig/getOne') ?>',
			type: 'post',
			data: {
				codConfig: codConfig,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#edit-formagendamentoSenhasConfig")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#edit-modalagendamentoSenhasConfig').modal('show');

				$("#edit-formagendamentoSenhasConfig #codConfigEdit").val(response.codConfig);
				$("#edit-formagendamentoSenhasConfig #dataCriacaoEdit").val(response.dataCriacao);
				$("#edit-formagendamentoSenhasConfig #dataInicioEdit").val(response.dataInicio);
				$("#edit-formagendamentoSenhasConfig #horaInicioEdit").val(response.horaInicio);
				$("#edit-formagendamentoSenhasConfig #dataEncerramentoEdit").val(response.dataEncerramento);
				$("#edit-formagendamentoSenhasConfig #horaEncerramentoEdit").val(response.horaEncerramento);
				$("#edit-formagendamentoSenhasConfig #tempoAtendimentoEdit").val(response.tempoAtendimento);
				$("#edit-formagendamentoSenhasConfig #qtdeAtendentesEdit").val(response.qtdeAtendentes);
				$("#edit-formagendamentoSenhasConfig #dataAtualizacaoEdit").val(response.dataAtualizacao);

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
					url: '<?php echo base_url('agendamentoSenhasConfig/listaDropDownStatusAgendamento') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(statusAgendamentoEdit) {

						$("#codStatusAgendamentoEdit").select2({
							data: statusAgendamentoEdit,
						})

						$('#codStatusAgendamentoEdit').val(response.codStatusAgendamento); // Select the option with a value of '1'
						$('#codStatusAgendamentoEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

					}
				})

				$.ajax({
					url: '<?php echo base_url('agendamentoSenhasConfig/listaDropDownTipoSenha') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(tipoAgendamentoEdit) {

						$("#codTipoAgendamentoEdit").select2({
							data: tipoAgendamentoEdit,
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
					success: function(agendamentoSenhasConfigEdit) {

						$("#codDepartamentoEdit").select2({
							data: agendamentoSenhasConfigEdit,
						})

						$('#codDepartamentoEdit').val(response.codDepartamento); // Select the option with a value of '1'
						$('#codDepartamentoEdit').trigger('change');
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
						var form = $('#edit-formagendamentoSenhasConfig');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('agendamentoSenhasConfig/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#edit-formagendamentoSenhasConfig-btn').html('<i class="fa fa-spinner fa-spin"></i>');
							},
							success: function(response) {

								if (response.success === true) {

									$('#edit-modalagendamentoSenhasConfig').modal('hide');


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

										$('#data_tableagendamentoSenhasConfig').DataTable().ajax.reload(null, false).draw(false);
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
								$('#edit-formagendamentoSenhasConfig-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#edit-formagendamentoSenhasConfig').validate();

			}
		});
	}

	function removeagendamentoSenhasConfig(codConfig) {
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
					url: '<?php echo base_url('agendamentoSenhasConfig/remove') ?>',
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
								$('#data_tableagendamentoSenhasConfig').DataTable().ajax.reload(null, false).draw(false);
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