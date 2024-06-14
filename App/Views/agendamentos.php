<?php
//É NECESSÁRIO EM TODAS AS VIEWS

use function App\Controllers\pegaIP;

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

if (!empty(session()->filtroEspecialidade)) {

	if (session()->filtroEspecialidade["codEspecialista"] !== NULL) {
		$codEspecialista = session()->filtroEspecialidade["codEspecialista"];
	} else {
		$codEspecialista = null;
	}
	$codEspecialidade = session()->filtroEspecialidade["codEspecialidade"];
} else {
	$codEspecialidade = NULL;
	$codEspecialista = NULL;
}

if (session()->filtroEspecialidade["dataInicio"] !== NULL) {
	$dataInicio = session()->filtroEspecialidade["dataInicio"];
} else {
	$dataInicio = date('Y-m-d H:i');
}

if (session()->filtroEspecialidade["dataEncerramento"] !== NULL) {
	$dataEncerramento = session()->filtroEspecialidade["dataEncerramento"];
} else {
	$dataEncerramento = date('Y-m-d H:i');;
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
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Agendamentos</h3>
						</div>

						<div class="col-md-12">

							<div class="row">
								<div class="col-md-12 text-right">
									<button class="btn btn-warning" onclick="profissionaisSaude()">
										<div><img style="width:60px" src="<?php echo base_url('/imagens/medico.png') ?>"></div>Conheça os Especialistas
									</button>
								</div>
							</div>
							<form id="filtroForm" class="pl-3 pr-3">
								<input type="hidden" id="<?php echo csrf_token() ?>filtroForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

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
												<option></option>
											</select>
										</div>
									</div>
								</div>
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

								<div>
									<button type="button" class="btn btn-primary" onclick="filtrar()" title="Procurar"> <i class="fas fa-filter"></i>Procurar</button>
								</div>



							</form>

						</div>

					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">


					<div class="row">
						<div class="col-12 col-sm-12">
							<div class="card card-primary card-tabs">
								<div class="card-header p-0 pt-1">

									<h3 class="card-title">
										<ul class="nav nav-tabs" id="agendamentos-tab" role="tablist">
											<li class="nav-item">
												<a class="nav-link active" id="agendamentosMarcados" data-toggle="pill" href="#agendamentos-profile" role="tab" aria-controls="agendamentos-profile" aria-selected="true">Agendados</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" id="agendamentosLivres" data-toggle="pill" href="#agendamentos-home" role="tab" aria-controls="agendamentos-home" aria-selected="false">Vagas livres</a>
											</li>

										</ul>
									</h3>

									<div class="card-tools">
										<span id="nomeLocalAtendimento" class="col-md-12"></span>
									</div>

								</div>


								<div class="card-body">
									<div class="tab-content" id="agendamentos-tabContent">
										<div class="tab-pane fade" id="agendamentos-home" role="tabpanel" aria-labelledby="agendamentosLivres">

											<div id="slotsLivres"> </div>
										</div>
										<div class="tab-pane fade  show active" id="agendamentos-profile" role="tabpanel" aria-labelledby="agendamentosMarcados">

											<div class="card-body">

												<div class="row">

													<div class="col-md-3">
														<button type="button" onclick="showListaPacientes()" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="add-formatalhos-btn">Lista Impressão</button>
													</div>


													<div id="botaoSala" class="col-md-6">


													</div>

												</div>
												<table id="data_tableagendamentos" class="table table-striped table-hover table-sm">
													<thead>
														<tr>
															<th>Paciente</th>
															<th>Especialidade</th>
															<th>Período</th>
															<th>Contato</th>
															<th>Confirmado</th>
															<th>Chegou</th>
															<th>Hora Chegada</th>
															<th>Tempo Espera</th>

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
				</div>
				<!-- /.card-body -->
			</div>
			<!-- /.card -->
		</div>
		<!-- /.col -->
	</div>
	<!-- /.row -->
</section>


<div id="setEstilo"></div>
<div id="showPacientesModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Seleção do Paciente</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">




				<form autocomplete="off" id="escolhaPacienteForm" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>escolhaPacienteForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codAgendamentoMarcacao" name="codAgendamento" class="form-control" placeholder="codAgendamento" maxlength="11" required>
					</div>
					<label for="xx"> Pesquisar paciente: <span class="text-danger">*</span> </label>

					<div class="row">
						<form class="pl-3 pr-3">

							<div class="col-md-4">
								<div class="form-group">
									<input autocomplete="off" type="text" id="nomeCpf" name="nomeCpf" class="form-control" placeholder="Nome, CPF ou PREC" maxlength="50" required>

								</div>

							</div>
							<div class="col-md-4">
								<button type="button" onclick="procurarPaciente()" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Pesquisar" id="add-formatalhos-btn">Pesquisar</button>

							</div>
						</form>



					</div>
					<div style="margin-left:20px" class="row">
						<div class="col-md-12">
							<div class="form-group">
								<div id="pacientesEncontrados"></div>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="button" onclick="marcarPaciente()" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Agendar Agora">Agendar Agora</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>


<div id="showPacientesReservaModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Seleção do Paciente Reserva</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">




				<form autocomplete="off" id="escolhaPacienteReservaForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>escolhaPacienteReservaForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<label for="xx"> Pesquisar paciente: <span class="text-danger">*</span> </label>

					<div class="row">
						<form class="pl-3 pr-3">

							<div class="col-md-4">
								<div class="form-group">
									<input autocomplete="off" type="text" name="nomeCpf" class="form-control" placeholder="Nome, CPF ou PREC" maxlength="50" required>

								</div>

							</div>
							<div class="col-md-4">
								<button type="button" onclick="procurarPacienteReserva()" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Pesquisar" id="add-formatalhos-btn">Pesquisar</button>

							</div>
						</form>



					</div>
					<div style="margin-left:20px" class="row">
						<div class="col-md-12">
							<div class="form-group">
								<div id="pacientesReservaEncontrados"></div>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="button" onclick="selecionarPaciente()" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Agendar Agora" id="add-formatalhos-btn">Selecionar</button>
							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
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
				<h4 class="modal-title text-white" id="info-header-modalLabel">Definição da Sala de atendimento</h4>
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
									<label for="codSalaAtendimentoAdd"> Departamento: <span class="text-danger">*</span> </label>
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



<div id="tipoImpressoraModal" class="modal fade col-md-6" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">COMPROVANTE DE MARCAÇÃO</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div style="margin-bottom:10px;font-size:16px">
					Selecione o layout da impressão do comprovante da consulta
				</div>

				<div id="botoesImprimirComprovante" class="row">
				</div>
			</div>


		</div>
	</div><!-- /.modal-content -->
</div>







<div id="imprimirListaModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Lista de Pacientes</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">


				<div id="teste"></div>

				<table id="data_listaPacientesImprimir" class="table table-striped table-hover table-sm">
					<thead>
						<tr>
							<th>Paciente</th>
							<th>Especialidade</th>
							<th>Período</th>
							<th>Contato</th>
							<th>Nº Plano</th>
							<th>Nº Prontuário</th>
						</tr>
					</thead>
				</table>

			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>



<div style="position: fixed;height: 800px" id="comprovanteA4Modal" class="modal fade" role="dialog" aria-hidden="true">

	<div class="modal-dialog modal-xl">
		<div class="modal-content">

			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Comprovante</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<div style="margin-left:10px" id="areaImpressaoComprovanteA4">
					<div class="row">
						<div style="width:50% !important" class="col-sm-6 border">

							<div>
								<center><img alt="" style="text-align:center;width:60px;height:60px;" src="<?php echo base_url() . "/imagens/organizacoes/" . session()->logo ?>"></center>
							</div>
							<div style="text-align:center;font-weight: bold">
								<?php echo session()->descricaoOrganizacao; ?>



							</div>

							<div style="font-family: 'Arial';margin-top:20px;height: 80mm;">
								<div style="text-align:left;font-weight: bold;font-size:12px">USUÁRIO: <span id="nomeCompletoComprovanteA4"></span></div>
								<div style="text-align:left;font-weight: bold;font-size:12px">ESPECIALISTA: <span id="nomeEspecialistaComprovanteA4"></span></div>
								<div style="text-align:left;font-weight: bold;font-size:12px">LOCAL DE ATENDIMENTO: <span id="nomeLocalComprovanteA4"></span></div>
								<div style="text-align:left;font-size:12px">DIA: <span id="dataInicioComprovanteA4"></span></div>
								<div style="text-align:left;font-size:12px">LOCAL <span id="localComprovanteA4"></span></div>
								<div style="text-align:left;font-size:12px">Protocolo Nr: <span id="protocoloComprovanteA4"></span></div>
								<div style="text-align:left;font-size:12px"><b>Prontuário Nº: </b>:<span id="codProntuarioComprovanteA4"></span></div>
								<div style="margin-top:10px" class="d-flex justify-content-left" id="qrcodeComprovanteA4"></div>

							</div>

						</div>
						<div style="width:50% !important" class="col-sm-6 border">

							<div style="margin-left:10px;margin-top:10px;font-family: 'Arial';margin-top:20px;text-align:left;font-weight: bold;font-size:12px">
								<div class="row">
									<b>Prezado usuário, leia atentamente as orientações a seguir:</b>
								</div>
								<div class="row">
									* Este é seu comprovante de marcação de consulta.
								</div>

								<div class="row">
									* Compareça no dia da consulta 30 minutos antes.
								</div>

								<div class="row">
									* Esta consulta só pode ser desmarcada até 24 horas antes. Para desmarcar utilize nossa plataforma online através do endereço <?php echo base_url() ?>, contate-nos através do telefone <?php echo session()->telefoneOrganizacao ?>
								</div>

								<div class="row">
									* Evite faltas, compareça à consulta.
								</div>
								<div class="row">
									* Evite bloqueio de marcações de consultas por motivo de faltas.
								</div>

								<div class="row">
									* Evite atrasos.
								</div>

							</div>
						</div>

					</div>
					<div class="row">
						<?php

						echo "CPF autor: " . substr(session()->cpf,0,-6).'*****'  . " | IP:"  . session()->ip;
						?>
					</div>
					<div class="row">
						<?php

						echo session()->cidade . '-' . session()->uf . ', ' . date('d', strtotime(date('Y-m-d'))) . ' de ' . nomeMesPorExtenso(date('m', strtotime(date('Y-m-d')))) . ' de ' . date('Y', strtotime(date('Y-m-d'))) . '.';

						?>
					</div>


				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary" id="botaoImprimirComprovanteA4">Imprimir</button>
				<button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
			</div>
		</div>



	</div>
</div>




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
								<center><img alt="" style="text-align:center;width:60px;height:60px;" src="<?php echo base_url() . "/imagens/organizacoes/" . session()->logo ?>"></center>
							</div>
							<div style="text-align:center;font-weight: bold">
								<?php echo session()->descricaoOrganizacao; ?>



							</div>

							<div style="font-family: 'Arial';margin-top:20px;height: 80mm;">
								<div style="text-align:left;font-weight: bold;font-size:12px">USUÁRIO: <span id="nomeCompletoComprovanteEtiqueta80"></span></div>
								<div style="text-align:left;font-weight: bold;font-size:12px">ESPECIALISTA: <span id="nomeEspecialistaComprovanteEtiqueta80"></span></div>
								<div style="text-align:left;;font-weight: bold;font-size:12px">DIA: <span id="dataInicioComprovanteEtiqueta80"></span></div>
								<div style="text-align:left;;font-weight: bold;font-size:12px">LOCAL <span id="localComprovanteEtiqueta80"></span></div>
								<div style="text-align:left;;font-weight: bold;font-size:12px">Protocolo Nr: <span id="protocoloComprovanteEtiqueta80"></span></div>
								<div style="text-align:left;;font-weight: bold;font-size:12px"><b>Prontuário Nº: </b>:<span id="codProntuarioComprovanteEtiqueta80"></span></div>
								<div style="margin-top:10px" class="d-flex justify-content-center" id="qrcodeComprovanteEtiqueta80"></div>

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
							* Este é seu comprovante de marcação de consulta.
						</div>

						<div class="row">
							* Compareça no dia da consulta 30 minutos antes.
						</div>

						<div class="row">
							* Esta consulta só pode ser desmarcada até 24 horas antes. Para desmarcar utilize nossa plataforma online através do endereço <?php echo base_url() ?>, contate-nos através do telefone <?php echo session()->telefoneOrganizacao ?>
						</div>

						<div class="row">
							* Evite faltas, compareça à consulta.
						</div>
						<div class="row">
							* Evite bloqueio de marcações de consultas por motivo de faltas.
						</div>

						<div class="row">
							* Evite atrasos.
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



<div style="position: absolute;height: 300mm" id="comprovanteEtiqueta58Modal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div style="width: 58mm" class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Comprovante</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<div style="margin-left:10px" id="areaImpressaoComprovanteEtiqueta58">
					<div style="width: 58mm important;height: 75mm important!;" class="row">
						<div class="col-sm-12">

							<div>
								<center><img alt="" style="text-align:center;width:60px;height:60px;" src="<?php echo base_url() . "/imagens/organizacoes/" . session()->logo ?>"></center>
							</div>
							<div style="text-align:center;font-weight: bold">
								<?php echo session()->descricaoOrganizacao; ?>



							</div>

							<div style="font-family: 'Arial';margin-top:20px;height: 90mm;">
								<div style="text-align:left;font-weight: bold;font-size:12px">USUÁRIO: <span id="nomeCompletoComprovanteEtiqueta58"></span></div>
								<div style="text-align:left;font-weight: bold;font-size:12px">ESPECIALISTA: <span id="nomeEspecialistaComprovanteEtiqueta58"></span></div>
								<div style="text-align:left;;font-weight: bold;font-size:12px">DIA: <span id="dataInicioComprovanteEtiqueta58"></span></div>
								<div style="text-align:left;;font-weight: bold;font-size:12px">LOCAL <span id="localComprovanteEtiqueta58"></span></div>
								<div style="text-align:left;;font-weight: bold;font-size:12px">Protocolo Nr: <span id="protocoloComprovanteEtiqueta58"></span></div>
								<div style="text-align:left;;font-weight: bold;font-size:12px"><b>Prontuário Nº: </b>:<span id="codProntuarioComprovanteEtiqueta58"></span></div>
								<div style="margin-top:10px" class="d-flex justify-content-center" id="qrcodeComprovanteEtiqueta58"></div>

							</div>

						</div>

					</div>



					<div style="margin-top:10px; border-top-style: dotted;" class="row">

					</div>
					<div style="margin-left:10px;margin-top:10px;font-family: 'Arial';margin-top:20px;text-align:left;font-weight: bold;font-size:12px">
						<div class="row">
							<b>Prezado usuário, leia atentamente as orientações a seguir:</b>
						</div>
						<div class="row">
							* Este é seu comprovante de marcação de consulta.
						</div>

						<div class="row">
							* Compareça no dia da consulta 30 minutos antes.
						</div>

						<div class="row">
							* Esta consulta só pode ser desmarcada até 24 horas antes. Para desmarcar utilize nossa plataforma online através do endereço <?php echo base_url() ?>, contate-nos através do telefone <?php echo session()->telefoneOrganizacao ?>
						</div>

						<div class="row">
							* Evite faltas, compareça à consulta.
						</div>
						<div class="row">
							* Evite bloqueio de marcações de consultas por motivo de faltas.
						</div>

						<div class="row">
							* Evite atrasos.
						</div>

					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary" id="botaoImprimirComprovanteEtiqueta58">Imprimir</button>
				<button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
			</div>

		</div>
	</div>
</div>


<div id="profissionaisSaudeModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Profissionais de Saúde</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<style>
					.borda {
						background: -webkit-linear-gradient(left top, #fffe01 0%, #28a745 100%);
						border-radius: 1000px;
						padding: 6px;
						width: 300px;
						height: 300px;

					}

					.callout {
						height: 250px !important;

					}

					.callout.callout-info {
						border-left-color: green
					}
				</style>

				<div id="listaProfissionaisSaude" class="row"></div>

			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>





<div id="agendamentosReservasAddModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">ENTRAR NA LISTA DE RESERVAS</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<button class="btn btn-success" onclick="procurarPacienteListaReserva()">Selecionar Paciente</button>
						<span style="font-size:16px;font-weight: bold;" id="nomePacienteSelecionado"></span>
					</div>
				</div>

				<form id="agendamentosReservasAddForm" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>agendamentosReservasAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
						<input type="hidden" id="codPacienteReserva" name="codPaciente" class="form-control" maxlength="11" required>
						<input type="hidden" id="codEspecialidadeReserva" name="codEspecialidade" class="form-control" maxlength="11" required>

						<input type="hidden" id="codAgendamentoReserva" name="codAgendamentoReserva" class="form-control" placeholder="CodAgendamentoReserva" maxlength="11" required>
					</div>


					<div class="row">


						<div class="col-md-4">
							<div class="form-group">
								<label for="codEspecialista"> Especialista: <span class="text-danger">*</span> </label>
								<select id="codEspecialistaReserva" name="codEspecialista" class="custom-select" required>
									<option value="0">Qualquer especialista</option>
								</select>
							</div>
						</div>


					</div>




					<div class="row">

						<div class="col-md-8">
							<div class="card card-secondary">

								<div class="card-header">
									<h3 class="card-title">PREFERÊNCIA DE DIA</h3>
									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
										</button>
									</div>
								</div>
								<div class="card-body">

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

										<div style="display: none;" class="col-md-1">
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
										<div style="display: none;" class="col-md-1">
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
									<h3 class="card-title">PREFERÊNCIA DE TURNO</h3>

									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
										</button>
									</div>
								</div>
								<div class="card-body">
									<div class="col-md-12">
										<div class="form-group">
											<label for="preferenciaHora"> Preferência Hora: <span class="text-danger">*</span> </label>

											<select id="preferenciaHora" name="preferenciaHora" class="custom-select">
												<option value="0">QUALQUER HORA</option>
												<option value="1">MANHA</option>
												<option value="2">TARDE</option>
											</select>
										</div>
									</div>

								</div>
							</div>
						</div>


					</div>


					<div class="form-group text-center">
						<div class="btn-group">
							<button type="button" onclick="salvarReserva()" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="agendamentosReservasAddForm-btn">Salvar Reserva</button>
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
	function salvarReserva() {



		var form = $('#agendamentosReservasAddForm');

		$.ajax({
			url: '<?php echo base_url('AgendamentosReservas/add') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(procuraPaciente) {
				if (procuraPaciente.success === true) {
					$('#agendamentosReservasAddModal').modal('hide');
					var Toast = Swal.mixin({
						toast: true,
						position: 'bottom-end',
						showConfirmButton: false,
						timer: 5000
					});
					Toast.fire({
						icon: 'success',
						title: procuraPaciente.messages
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
						title: procuraPaciente.messages
					})
				}
			}

		})
	}



	function procurarPacienteListaReserva() {
		$('#showPacientesReservaModal').modal('show');
	}

	function entrarListaReserva(especialidade, especialista) {

		$("#agendamentosReservasAddForm")[0].reset();

		$('#agendamentosReservasAddModal').modal('show');

		document.getElementById('codEspecialidadeReserva').value = especialidade;

		$.ajax({
			url: '<?php echo base_url('especialidades/listaDropDownEspecialistasDisponivelMarcacao') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				codEspecialidade: especialidade,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),

			},

			success: function(EspecialistasReserva) {

				$("#codEspecialistaReserva").select2({
					data: EspecialistasReserva,
				})

				$('#codEspecialistaReserva').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});


			}
		})





	}


	$(document).on('show.bs.modal', '.modal', function() {
		var zIndex = 1040 + (10 * $('.modal:visible').length);
		$(this).css('z-index', zIndex);
		setTimeout(function() {
			$('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
		}, 0);
	});



	$(function() {

		$.ajax({
			url: '<?php echo base_url('agendamentos/verificaSala') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(verificacaoSala) {

				if (verificacaoSala.success === true) {
					document.getElementById("botaoSala").innerHTML = verificacaoSala.botao;
					document.getElementById("nomeLocalAtendimento").innerHTML = verificacaoSala.nomeLocalAtendimento;


				}
			}
		});




		$.ajax({
			url: '<?php echo base_url('especialidades/listaDropDownEspecialidades') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(especialidadesAdm) {

				$("#codEspecialidadeAdd").select2({
					data: especialidadesAdm,
				})

				$('#codEspecialidadeAdd').val(codEspecialidade); // Select the option with a value of '1'
				$('#codEspecialidadeAdd').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});



			}
		})


		$("#codEspecialidadeAdd").on("change", function() {



			$('#codEspecialistaAdd').html('').select2({
				data: [{
					id: null,
					text: ''
				}]
			});

			if ($(this).val() !== '') {
				codEspecialidade = $(this).val();
			} else {
				codEspecialidade = 0;
			}

			$.ajax({
				url: '<?php echo base_url('especialidades/listaDropDownEspecialistasDisponivelMarcacao') ?>',
				type: 'post',
				dataType: 'json',
				data: {
					codEspecialidade: codEspecialidade,
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},

				success: function(EspecialistasAdd) {

					$("#codEspecialistaAdd").select2({
						data: EspecialistasAdd,
					})

					$('#codEspecialistaAdd').val(codEspecialista); // Select the option with a value of '1'
					$('#codEspecialistaAdd').trigger('change');
					$(document).on('select2:open', () => {
						document.querySelector('.select2-search__field').focus();
					});


				}
			})


		});




	})



	var codEspecialidade = "<?php echo $codEspecialidade ?>";
	var codEspecialista = "<?php echo $codEspecialista ?>";
	var dataInicio = "<?php echo $dataInicio ?>";
	var dataEncerramento = "<?php echo $dataEncerramento ?>";



	function marcarPaciente() {

		var form = $('#escolhaPacienteForm');
		var codPacienteMarcacao = document.querySelector('input[name="codPacienteMarcacao"]:checked').value;
		var codAgendamento = document.getElementById('codAgendamentoMarcacao').value;


		$.ajax({
			url: '<?php echo base_url('agendamentos/marcarPaciente') ?>',
			type: 'post',
			data: {
				codPacienteMarcacao: codPacienteMarcacao,
				codAgendamento: codAgendamento,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(marcacaoPaciente) {

				$('#showPacientesModal').modal('hide');

				if (marcacaoPaciente.success === true) {

					document.getElementById("slotsLivres").innerHTML = '';
					comprovante(marcacaoPaciente.codAgendamento);

					var Toast = Swal.mixin({
						toast: true,
						position: 'bottom-end',
						showConfirmButton: false,
						timer: 3000
					});
					Toast.fire({
						icon: 'success',
						title: marcacaoPaciente.messages
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
						title: marcacaoPaciente.messages
					}).then(function() {
						$('#data_tableagendamentos').DataTable().ajax.reload(null, false).draw(false);
						document.getElementById("slotsLivres").innerHTML = '';


					})
				}
			}
		}).always(
			Swal.fire({
				title: 'Estamos processando sua requisição',
				html: 'Aguarde....',
				timerProgressBar: true,
				didOpen: () => {
					Swal.showLoading()


				}

			}))
	}

	function selecionarPaciente() {

		var form = $('#escolhaPacienteReservaForm');
		var codPacienteMarcacao = document.querySelector('input[name="codPacienteMarcacao"]:checked').value;
		document.getElementById('codPacienteReserva').value = codPacienteMarcacao;
		$('#showPacientesReservaModal').modal('hide');



		//PEGA NOME PACIENTE

		$.ajax({
			url: '<?php echo base_url('agendamentos/nomePaciente') ?>',
			type: 'post',
			data: {
				codPaciente: codPacienteMarcacao,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(paciente) {
				if (paciente.success === true) {

					document.getElementById("nomePacienteSelecionado").innerHTML = paciente.nomePaciente;

				}
			}
		})


	}




	function chamarPainel(codAgendamento) {

		$.ajax({
			url: '<?php echo base_url('agendamentos/chamarPacienteAgora') ?>',
			type: 'post',
			data: {
				codAgendamento: codAgendamento,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(chamaPaciente) {
				if (chamaPaciente.success === true) {
					var Toast = Swal.mixin({
						toast: true,
						position: 'top-end',
						showConfirmButton: false,
						timer: 5000
					});
					Toast.fire({
						icon: 'success',
						title: chamaPaciente.messages
					})

				} else {
					var Toast = Swal.mixin({
						toast: true,
						position: 'top-end',
						showConfirmButton: false,
						timer: 5000
					});
					Toast.fire({
						icon: 'error',
						title: chamaPaciente.messages
					})
				}

			}
		})

	}



	function gravarSala() {




		$('#salaAtendimentoModel').modal('hide');



		$.ajax({
			url: '<?php echo base_url('agendamentos/gravarSala') ?>',
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
						url: '<?php echo base_url('agendamentos/verificaSala') ?>',
						type: 'post',
						dataType: 'json',
						data: {
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},
						success: function(verificacaoSala) {

							if (verificacaoSala.success === true) {
								document.getElementById("botaoSala").innerHTML = verificacaoSala.botao;
								document.getElementById("nomeLocalAtendimento").innerHTML = verificacaoSala.nomeLocalAtendimento;


							}
						}
					})

				}
			}
		})

	}

	function showDefinirSala() {




		$('#salaAtendimentoModel').modal('show');





		$.ajax({
			url: '<?php echo base_url('departamentos/listaDropDown') ?>',
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
				url: '<?php echo base_url('atendimentosLocais/listaDropDownAtivos') ?>',
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


	function profissionaisSaude() {
		$('#profissionaisSaudeModal').modal('show');

		$.ajax({
			url: '<?php echo base_url('login/profissionaisSaude') ?>',
			type: 'post',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(profissionaisSaude) {


				document.getElementById("listaProfissionaisSaude").innerHTML = profissionaisSaude.html;



			}
		})



	}


	function comprovante(codAgendamento) {
		$('#tipoImpressoraModal').modal('show');



		document.getElementById("botoesImprimirComprovante").innerHTML =

			'<div class="col-md-3"><button style="margin-left:0px" type="button" onclick="comprovanteA4(' + codAgendamento + ')" class="btn btn-primary" data-toggle="tooltip" data-placement="top" id="add-formatalhos-btn">Impressora A4</button></div>' +
			'<div class="col-md-3"><button style="margin-left:5px" type="button" onclick="comprovanteEtiqueta80(' + codAgendamento + ')" class="btn btn-primary" data-toggle="tooltip" data-placement="top" id="add-formatalhos-btn">Etiqueta 80mm</button></div>' +
			'<div class="col-md-3"><button style="margin-left:5px" type="button" onclick="comprovanteEtiqueta58(' + codAgendamento + ')" class="btn btn-primary" data-toggle="tooltip" data-placement="top" id="add-formatalhos-btn">Etiqueta 58mm</button></div>';

	}


	function comprovanteEtiqueta58(codAgendamento) {

		$('#comprovanteEtiqueta58Modal').modal('show');
		$('#tipoImpressoraModal').modal('hide');

		document.getElementById("botaoImprimirComprovanteEtiqueta58").onclick = function() {
			printElement(document.getElementById("areaImpressaoComprovanteEtiqueta58"));

			window.print();
		}

		$.ajax({
			url: '<?php echo base_url('agendamentos/comprovante') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				codAgendamento: codAgendamento,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(agendamentoComprovante) {
				document.getElementById("nomeCompletoComprovanteEtiqueta58").innerHTML = agendamentoComprovante.nomePaciente;
				document.getElementById("nomeEspecialistaComprovanteEtiqueta58").innerHTML = agendamentoComprovante.nomeEspecialista;
				document.getElementById("protocoloComprovanteEtiqueta58").innerHTML = agendamentoComprovante.protocolo;
				document.getElementById("codProntuarioComprovanteEtiqueta58").innerHTML = agendamentoComprovante.codProntuario;



				document.getElementById("localComprovanteEtiqueta58").innerHTML = agendamentoComprovante.local;
				document.getElementById("dataInicioComprovanteEtiqueta58").innerHTML = agendamentoComprovante.dataInicio;
				var URLComprovante = '<?php echo base_url() . "/atendimentos/?codagendamento=" ?>' + agendamentoComprovante.codAgendamento + '&chechsum=' + agendamentoComprovante.valorChecksum;

				document.getElementById("qrcodeComprovanteEtiqueta58").innerHTML = "";
				qrcode = new QRCode("qrcodeComprovanteEtiqueta58", {
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
					'size: 58mm 150mm;' +
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
					'width: 58mm;' +
					'height: 75mm;' +

					'}' +
					'}</style>';


			}

		})


	}


	function comprovanteA4(codAgendamento) {

		$('#comprovanteA4Modal').modal('show');
		$('#tipoImpressoraModal').modal('hide');

		document.getElementById("botaoImprimirComprovanteA4").onclick = function() {
			printElement(document.getElementById("areaImpressaoComprovanteA4"));

			window.print();
		}

		$.ajax({
			url: '<?php echo base_url('agendamentos/comprovante') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				codAgendamento: codAgendamento,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(agendamentoComprovante) {
				document.getElementById("nomeCompletoComprovanteA4").innerHTML = agendamentoComprovante.nomePaciente;
				document.getElementById("nomeEspecialistaComprovanteA4").innerHTML = agendamentoComprovante.nomeEspecialista;
				document.getElementById("nomeLocalComprovanteA4").innerHTML = agendamentoComprovante.descricaoDepartamento;
				document.getElementById("protocoloComprovanteA4").innerHTML = agendamentoComprovante.protocolo;
				document.getElementById("codProntuarioComprovanteA4").innerHTML = agendamentoComprovante.codProntuario;



				document.getElementById("localComprovanteA4").innerHTML = agendamentoComprovante.local;
				document.getElementById("dataInicioComprovanteA4").innerHTML = agendamentoComprovante.dataInicio;
				var URLComprovante = '<?php echo base_url() . "/atendimentos/?codagendamento=" ?>' + agendamentoComprovante.codAgendamento + '&chechsum=' + agendamentoComprovante.valorChecksum;

				document.getElementById("qrcodeComprovanteA4").innerHTML = "";

				qrcode = new QRCode("qrcodeComprovanteA4", {
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


			}

		})


	}



	function comprovanteEtiqueta80(codAgendamento) {

		$('#comprovanteEtiqueta80Modal').modal('show');
		$('#tipoImpressoraModal').modal('hide');

		document.getElementById("botaoImprimirComprovanteEtiqueta80").onclick = function() {
			printElement(document.getElementById("areaImpressaoComprovanteEtiqueta80"));

			window.print();
		}

		$.ajax({
			url: '<?php echo base_url('agendamentos/comprovante') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				codAgendamento: codAgendamento,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(agendamentoComprovante) {
				document.getElementById("nomeCompletoComprovanteEtiqueta80").innerHTML = agendamentoComprovante.nomePaciente;
				document.getElementById("nomeEspecialistaComprovanteEtiqueta80").innerHTML = agendamentoComprovante.nomeEspecialista;
				document.getElementById("protocoloComprovanteEtiqueta80").innerHTML = agendamentoComprovante.protocolo;
				document.getElementById("codProntuarioComprovanteEtiqueta80").innerHTML = agendamentoComprovante.codProntuario;



				document.getElementById("localComprovanteEtiqueta80").innerHTML = agendamentoComprovante.local;
				document.getElementById("dataInicioComprovanteEtiqueta80").innerHTML = agendamentoComprovante.dataInicio;
				var URLComprovante = '<?php echo base_url() . "/atendimentos/?codagendamento=" ?>' + agendamentoComprovante.codAgendamento + '&chechsum=' + agendamentoComprovante.valorChecksum;

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



	function escolhaPaciente(codAgendamento) {
		// reset the form 
		$("#escolhaPacienteForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#showPacientesModal').modal('show');

		$("#escolhaPacienteForm #codAgendamentoMarcacao").val(codAgendamento);


		//UPDATE PARA RESERVAR POR 1 MINUTO O SLOT E EVITAR CONFLITOS
		$.ajax({
			url: '<?php echo base_url('agendamentos/reservaUmMinuto') ?>',
			type: 'post',
			data: {
				codAgendamento: codAgendamento,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(reservaUmMinuto) {

			}
		})


	}

	function procurarPacienteReserva() {




		var form = $('#escolhaPacienteReservaForm');

		$.ajax({
			url: '<?php echo base_url('agendamentos/procurarPaciente') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(procuraPaciente) {
				if (procuraPaciente.success === true) {
					document.getElementById('pacientesReservaEncontrados').innerHTML = procuraPaciente.html;

				}
			}

		})


	}

	function procurarPaciente() {




		var form = $('#escolhaPacienteForm');

		$.ajax({
			url: '<?php echo base_url('agendamentos/procurarPaciente') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(procuraPaciente) {
				if (procuraPaciente.success === true) {
					document.getElementById('pacientesEncontrados').innerHTML = procuraPaciente.html;

				}
			}

		})


	}

	function filtrar() {




		var form = $('#filtroForm');
		$.ajax({
			url: '<?php echo base_url('agendamentos/filtrarVagas') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(filtrar) {

				if (filtrar.success === true) {

					$('#data_tableagendamentos').DataTable().ajax.reload(null, false).draw(false);


					$.ajax({
						url: '<?php echo base_url('agendamentos/agendamentosPorEspecialidade') ?>',
						type: 'post',
						dataType: 'json',
						data: {
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},
						success: function(responseAgendamentos) {
							swal.close();

							if (responseAgendamentos.success === true) {

								document.getElementById('slotsLivres').innerHTML = responseAgendamentos.slotsLivres;

							}
						}
					}).always(
						Swal.fire({
							title: 'Estamos buscando possíveis vagas no sistema',
							html: 'Aguarde....',
							timerProgressBar: true,
							didOpen: () => {
								Swal.showLoading()


							}

						}))

				}
			}
		})
	}





	$('#data_tableagendamentos').DataTable({

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
			"url": '<?php echo base_url('agendamentos/marcados') ?>',
			"type": "POST",
			"dataType": "json",
			async: "true",
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
		}
	})




	function dataAtualFormatada(datInicio) {
		var data = new Date(datInicio),
			somaUmDia = data.setDate(data.getDate() + 1),
			dia = data.getDate().toString(),
			diaF = (dia.length == 1) ? '0' + dia : dia,
			mes = (data.getMonth() + 1).toString(), //+1 pois no getMonth Janeiro começa com zero.
			mesF = (mes.length == 1) ? '0' + mes : mes,
			anoF = data.getFullYear();
		return diaF + "/" + mesF + "/" + anoF;
	}

	function showListaPacientes() {
		// reset the form 
		$('#imprimirListaModal').modal('show');

		var labelEspecialidade = document.getElementById('codEspecialidadeAdd')[document.getElementById('codEspecialidadeAdd').selectedIndex].innerHTML;

		datInicio = document.getElementById('dataInicioAdd').value;
		datEncerramento = document.getElementById('dataEncerramentoAdd').value;
		periodo = "";


		if (datInicio == '') {
			periodo = "";
		} else {

			if (datEncerramento == '') {
				periodo = " de " + dataAtualFormatada(datInicio);
			} else {
				if (datEncerramento == datInicio) {
					periodo = " de " + dataAtualFormatada(datInicio);
				} else {
					inicio = dataAtualFormatada(datInicio);
					fim = dataAtualFormatada(datEncerramento);
					periodo = " de " + inicio + " a " + fim;
				}
			}


		}




		document.getElementById("setEstilo").innerHTML = '@page {' +
			'size: A4;' +
			'margin: 0;' +
			'}' +

			'@media print {' +
			'.page {' +
			'margin: 0;' +
			'border: initial;' +
			'border - radius: initial;' +
			'width: initial;' +
			'min - height: initial;' +
			'box - shadow: initial;' +
			'background: initial;' +
			'page -' +
			'break -after: always;' +
			'}' +
			'}';



		var titulo = "Lista de Pacientes " + periodo;


		$('#data_listaPacientesImprimir').DataTable({
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
				"url": '<?php echo base_url('agendamentos/marcadosListaImprimir') ?>',
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
					title: titulo,
					messageTop: labelEspecialidade,
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
						columns: [0, 1, 2, 3, 4, 5]
					},

				},
				{
					extend: 'csvHtml5',
					exportOptions: {
						columns: [0, 1, 2, 3, 4, 5]
					}
				},
				{
					extend: 'excelHtml5',
					exportOptions: {
						columns: [0, 1, 2, 3, 4, 5]
					}
				},

			],
		})

	}

	function addagendamentos() {
		// reset the form 
		$("#agendamentosAddForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#agendamentosAddModal').modal('show');
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

				var form = $('#agendamentosAddForm');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('agendamentos/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#agendamentosAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function(response) {

						if (response.success === true) {
							$('#agendamentosAddModal').modal('hide');

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
								$('#data_tableagendamentos').DataTable().ajax.reload(null, false).draw(false);
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
						$('#agendamentosAddForm-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#agendamentosAddForm').validate();
	}

	function editagendamentos(codAgendamento) {
		$.ajax({
			url: '<?php echo base_url('agendamentos/getOne') ?>',
			type: 'post',
			data: {
				codAgendamento: codAgendamento,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#agendamentosEditForm")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#agendamentosEditModal').modal('show');

				$("#agendamentosEditForm #codAgendamento").val(response.codAgendamento);
				$("#agendamentosEditForm #codConfig").val(response.codConfig);
				$("#agendamentosEditForm #codOrganizacao").val(response.codOrganizacao);
				$("#agendamentosEditForm #codPaciente").val(response.codPaciente);
				$("#agendamentosEditForm #codLocal").val(response.codLocal);
				$("#agendamentosEditForm #codEspecialista").val(response.codEspecialista);
				$("#agendamentosEditForm #codEspecialidade").val(response.codEspecialidade);
				$("#agendamentosEditForm #codStatus").val(response.codStatus);
				$("#agendamentosEditForm #dataCriacao").val(response.dataCriacao);
				$("#agendamentosEditForm #dataAtualizacao").val(response.dataAtualizacao);
				$("#agendamentosEditForm #dataInicio").val(response.dataInicio);
				$("#agendamentosEditForm #dataEncerramento").val(response.dataEncerramento);
				$("#agendamentosEditForm #codTipoAgendamento").val(response.codTipoAgendamento);
				$("#agendamentosEditForm #codAutor").val(response.codAutor);
				$("#agendamentosEditForm #protocolo").val(response.protocolo);
				$("#agendamentosEditForm #ordemAtendimento").val(response.ordemAtendimento);

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
						var form = $('#agendamentosEditForm');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('agendamentos/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#agendamentosEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
							},
							success: function(response) {

								if (response.success === true) {

									$('#agendamentosEditModal').modal('hide');


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
										$('#data_tableagendamentos').DataTable().ajax.reload(null, false).draw(false);
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
								$('#agendamentosEditForm-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#agendamentosEditForm').validate();

			}
		});
	}

	function removeagendamentos(codAgendamento) {
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
					url: '<?php echo base_url('agendamentos/remove') ?>',
					type: 'post',
					data: {
						codAgendamento: codAgendamento,
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
								$('#data_tableagendamentos').DataTable().ajax.reload(null, false).draw(false);
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

	function desmarcar(codAgendamento) {
		Swal.fire({
			title: 'Você tem certeza que deseja desmarcar este agendamento?',
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
					url: '<?php echo base_url('agendamentos/desmarcar') ?>',
					type: 'post',
					data: {
						codAgendamento: codAgendamento,
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
								$('#data_tableagendamentos').DataTable().ajax.reload(null, false).draw(false);
								filtrar();
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
				}).always(
					Swal.fire({
						title: 'Estamos realizando a desmarcação da consulta',
						html: 'Aguarde....',
						timerProgressBar: true,
						didOpen: () => {
							Swal.showLoading()


						}

					}))
			}
		})
	}

	function chegou(codAgendamento) {
		Swal.fire({
			title: 'Deseja confirmar que o paciente chegou?',
			text: "O médico será notificado da presença do paciente na sala de espera",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {

			if (result.value) {
				$.ajax({
					url: '<?php echo base_url('agendamentos/chegou') ?>',
					type: 'post',
					data: {
						codAgendamento: codAgendamento,
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
								$('#data_tableagendamentos').DataTable().ajax.reload(null, false).draw(false);
								filtrar();
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
				}).always(
					Swal.fire({
						title: 'Estamos registrando a presença do paciente na consulta',
						html: 'Aguarde....',
						timerProgressBar: true,
						didOpen: () => {
							Swal.showLoading()


						}

					}))
			}
		})
	}


	function confirmou(codAgendamento) {
		Swal.fire({
			title: 'O paciente confirmou que virá para consulta?',
			text: "A agenda do médico será atualizada após essa confirmação",

			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {

			if (result.value) {
				$.ajax({
					url: '<?php echo base_url('agendamentos/confirmou') ?>',
					type: 'post',
					data: {
						codAgendamento: codAgendamento,
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
								$('#data_tableagendamentos').DataTable().ajax.reload(null, false).draw(false);
								filtrar();
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
				})
			}
		})
	}
</script>