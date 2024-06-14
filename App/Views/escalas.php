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

<div style="visibility:hidden" id="setEstilo"></div>
<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-md-8 mt-2">
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Escalas de Serviço</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addescalas()" title="Adicionar">Adicionar</button>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<table id="data_tableescalas" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>Código</th>
								<th>Descrição</th>
								<th>Setor Gestor</th>
								<th>Criado Por</th>
								<th>Data Criação</th>
								<th>Modificado Por</th>
								<th>Data Atualização</th>

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
<div id="escalasAddModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Escalas de Serviço</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="escalasAddForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>escalasAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codEscala" name="codEscala" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="descricao"> Descrição: <span class="text-danger">*</span> </label>
								<input type="text" id="descricao" name="descricao" class="form-control" placeholder="Descrição" maxlength="200" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="setorGestor"> Setor Gestor: <span class="text-danger">*</span> </label>
								<select id="setorGestor" name="setorGestor" class="custom-select">
									<option value=""></option>
								</select>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="escalasAddForm-btn">Adicionar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>



<!-- Add modal content -->
<div id="escalasEditModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="escalasEditModalHeader"></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="col-12 col-sm-12">
					<div class="card card-primary card-tabs">
						<div class="card-header p-0 pt-1">
							<ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Geral</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="custom-tabs-one-membros-tab" data-toggle="pill" href="#custom-tabs-one-membros" role="tab" aria-controls="custom-tabs-one-membros" aria-selected="false">Membros</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="custom-tabs-one-afastamentos-tab" data-toggle="pill" href="#custom-tabs-one-afastamentos" role="tab" aria-controls="custom-tabs-one-afastamentos" aria-selected="false">Afastamentos</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="custom-tabs-one-settings-tab" data-toggle="pill" href="#custom-tabs-one-settings" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">Calendário (Previsão)</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="custom-tabs-one-trocas-tab" data-toggle="pill" href="#custom-tabs-one-trocas" role="tab" aria-controls="custom-tabs-one-trocas" aria-selected="false">Trocas autorizadas <span style="margin-left: 10px;" id="qtdTrocas" class="right badge badge-warning"></span></a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="custom-tabs-one-datasVermelhas-tab" data-toggle="pill" href="#custom-tabs-one-datasVermelhas" role="tab" aria-controls="custom-tabs-one-datasVermelhas" aria-selected="false">Configuração de Vermelhas(Fériados e Outros)</a>
								</li>
							</ul>
						</div>
						<div class="card-body">
							<div class="tab-content" id="custom-tabs-one-tabContent">
								<div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">


									<form id="escalasEditForm" class="pl-3 pr-3">
										<input type="hidden" id="<?php echo csrf_token() ?>escalasEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

										<div class="row">
											<input type="hidden" id="codEscala" name="codEscala" class="form-control" placeholder="Código" maxlength="11" required>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="descricao"> Descrição: <span class="text-danger">*</span> </label>
													<input type="text" id="descricao" name="descricao" class="form-control" placeholder="Descrição" maxlength="200" required>
												</div>
											</div>

											<div class="col-md-4">
												<div class="form-group">
													<label for="setorGestor"> Setor Gestor: <span class="text-danger">*</span> </label>
													<select id="setorGestor" name="setorGestor" class="custom-select">
														<option value=""></option>
													</select>
												</div>
											</div>
										</div>
										<div class="form-group text-center">
											<div class="btn-group">
												<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="escalasEditForm-btn">Salvar</button>
												<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
											</div>
										</div>
									</form>

								</div>
								<div class="tab-pane fade" id="custom-tabs-one-membros" role="tabpanel" aria-labelledby="custom-tabs-one-membros-tab">

									<div class="row">
										<button type="button" onclick="adicionarMembro()" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="adicionar Membro">Adicionar Membro</button>
									</div>
									<div style="margin-top:20px" class="row">
										<div class="col-md-12">
											<div class="card card-primary">
												<div class="card-header">
													<h3 class="card-title">Prontos para o Serviço</h3>

												</div>
												<div class="card-body">
													<table id="data_membrosAtivos" class="table table-striped table-hover table-sm">
														<thead>
															<tr>
																<th>Membro</th>
																<th>Afastamentos</th>
																<th>Preta</th>
																<th>Vermelha</th>
																<th></th>
															</tr>
														</thead>
													</table>
												</div>
											</div>
										</div>

									</div>
								</div>
								<div class="tab-pane fade" id="custom-tabs-one-afastamentos" role="tabpanel" aria-labelledby="custom-tabs-one-afastamentos-tab">

									<div style="margin-top:10px" class="row">
										<div class="col-md-12">
											<div class="card card-danger">
												<div class="card-header">
													<h3 class="card-title">Afastamentos</h3>

												</div>
												<div class="card-body">
													<table id="data_membrosAfastados" class="table table-striped table-hover table-sm">
														<thead>
															<tr>
																<th>Membro</th>
																<th>Inicio</th>
																<th>Término</th>
																<th>Motivo</th>
																<th></th>
															</tr>
														</thead>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="custom-tabs-one-settings" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">


									<div style="margin-top:10px" class="row">
										<div class="col-md-2">
											<i style="color:red" class="fas fa-square"></i> Vermelha
										</div>

										<div class="col-md-2">
											<i style="color:#000" class="fas fa-square"></i> Preta
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="dataLimiteLiberacao"> Data Limite Liberação: <span class="text-danger">*</span> </label>
												<input type="date" id="dataLimiteLiberacao" name="dataLimiteLiberacao" class="form-control" required>
											</div> <span> <button type="button" onclick="atualizarPrevisaoEscala()" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Atualizar Previsão">Atualizar Previsão</button>
											</span>
										</div>

										<div class="col-md-1">
											<div class="form-group">
												<label for="folgaLiberacao"> Folga: <span class="text-danger">*</span> </label>
												<select id="folgaLiberacao" name="folgaLiberacao" class="form-control" required>
													<option value="24">24h</option>
													<option value="48">48h</option>
													<option value="72">72h</option>
												</select>
											</div>
										</div>
									</div>

									<div style="margin-top:10px" class="row">

										<div id="mostrarPrevisaoEscala"></div>

									</div>



								</div>
								<div class="tab-pane fade" id="custom-tabs-one-trocas" role="tabpanel" aria-labelledby="custom-tabs-one-trocas-tab">

									<table id="data_tabletrocas" class="table table-striped table-hover table-sm">
										<thead>
											<tr>
												<th>Código</th>
												<th>Data</th>
												<th>Sai</th>
												<th>Entra</th>
												<th>Tipo Escala</th>
												<th>Observaçoes</th>
												<th></th>
											</tr>
										</thead>
									</table>
								</div>
								<div class="tab-pane fade" id="custom-tabs-one-datasVermelhas" role="tabpanel" aria-labelledby="custom-tabs-one-datasVermelhas-tab">

									<div class="row">
										<button type="button" onclick="adicionarDataVermelha()" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="adicionar Data Vermelha">Adicionar Data Vermelha</button>
									</div>

									<table id="data_tableDatasVermelhas" class="table table-striped table-hover table-sm">
										<thead>
											<tr>
												<th>Data</th>
												<th>Descrição</th>
												<th>Recorrente</th>
												<th>Ativo</th>
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
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div id="dataVermelhaAddModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Data Vermelha</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="dataVermelhaAddForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>dataVermelhaAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">



					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="descricao"> Descrição: <span class="text-danger">*</span> </label>
								<input type="text" id="descricao" name="descricao" class="form-control" placeholder="Descrição" maxlength="200" required>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label for="recorrente"> Recorrente?: <span class="text-danger">*</span> </label>
								<input type="date" id="dataVermelha" name="dataVermelha" class="form-control" required>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="recorrente"> Recorrente?: <span class="text-danger">*</span> </label>
								<select id="recorrente" name="recorrente" class="custom-select" required>
									<option value=""></option>
									<option value=0>Não</option>
									<option value=1>Sim</option>
								</select>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="escalasAddForm-btn">Adicionar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>


<div id="membrosAddModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Membros</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="row">
					<div class="col-sm-3">
						<label for="Cargo">Cargo <span class="text-danger">*</span> </label>
						<div class="input-group mb-3">
							<select style="width:80%" required="" id="codCargoAdd" name="codCargo" class="form-control">
								<option value=""></option>

							</select>
						</div>
					</div>
				</div>

				<div class="row">
					<div id="membrosConcorrentes"></div>
				</div>


			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>

<div id="afastarMembroAddModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Afastar Membro da escala</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<form id="afastarMembroAddForm">
					<input type="hidden" id="codMembroEscalaAddMembro">
					<div class="row">

						<div class="col-md-4">
							<div class="form-group">
								<label for="codStatusAfastamento"> Motivo: <span class="text-danger">*</span> </label>
								<select id="codStatusAfastamento" name="codStatus" class="custom-select">
									<option value=""></option>
								</select>
							</div>
						</div>
					</div>



					<div class="row">
						<div class="icheck-primary d-inline center">
							<style>
								input[type=checkbox] {
									transform: scale(1.8);
									margin-left: 10px
								}
							</style>
							<input id="afastamentoIndeterminado" name="afastamentoIndeterminado" type="checkbox">
						</div><span style="margin-left:10px"> Tempo Indeterminado</span>
					</div>

					<div id="periodoAfastamento" class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="dataInicioAfastamento"> Inicio Afastamento: <span class="text-danger">*</span> </label>
								<input type="date" id="dataInicioAfastamento" name="dataInicioAfastamento" class="form-control" required>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="dataEncerramentoAfastamento"> Encerramento Afastamento: <span class="text-danger">*</span> </label>
								<input type="date" id="dataEncerramentoAfastamento" name="dataEncerramentoAfastamento" class="form-control" required>
							</div>
						</div>

					</div>



					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="observacoes"> Observações (Opcional):</label>

								<textarea rows="4" cols="50" id="observacoes" name="observacoes" class="form-control"></textarea>
							</div>
						</div>
					</div>

					<div class="row text-center">
						<div class="col-md-12">
							<div class="btn-group">
								<button type="button" onclick="afastarMembroAdd()" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar">Afastar</button>
								<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>

<div id="afastarMembroEditModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="afastarMembroEditHeader"></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<form id="afastarMembroEditForm">
					<input type="hidden" id="codAfastamentoEdit">
					<div class="row">

						<div class="col-md-4">
							<div class="form-group">
								<label for="codStatusAfastamentoEdit"> Motivo: <span class="text-danger">*</span> </label>
								<select id="codStatusAfastamentoEdit" name="codStatus" class="custom-select">
									<option value=""></option>
								</select>
							</div>
						</div>
					</div>



					<div class="row">
						<div class="icheck-primary d-inline center">
							<style>
								input[type=checkbox] {
									transform: scale(1.8);
									margin-left: 10px
								}
							</style>
							<input id="afastamentoIndeterminadoEdit" name="afastamentoIndeterminado" type="checkbox">
						</div><span style="margin-left:10px"> Tempo Indeterminado</span>
					</div>

					<div id="periodoAfastamentoEdit" class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="dataInicioAfastamentoEdit"> Inicio Afastamento: <span class="text-danger">*</span> </label>
								<input type="date" id="dataInicioAfastamentoEdit" name="dataInicioAfastamento" class="form-control" required>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="dataEncerramentoAfastamentoEdit"> Encerramento Afastamento: <span class="text-danger">*</span> </label>
								<input type="date" id="dataEncerramentoAfastamentoEdit" name="dataEncerramentoAfastamento" class="form-control" required>
							</div>
						</div>

					</div>



					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="observacoesEdit"> Observações (Opcional):</label>

								<textarea rows="4" cols="50" id="observacoesEdit" name="observacoes" class="form-control"></textarea>
							</div>
						</div>
					</div>

					<div class="row text-center">
						<div class="col-md-12">
							<div class="btn-group">
								<button type="button" onclick="afastarMembroEditAgora()" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar">Afastar</button>
								<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>


<div id="trocarPessoaEscalaModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Troca Pessoa Escala</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<form id="trocarPessoaEscalaForm">
					<input type="hidden" id="codPrevisaoEscalaTrocaPessoa" name="codPrevisaoEscala">
					<input type="hidden" id="codEscalaTrocaPessoa" name="codEscala">
					<input type="hidden" id="codPessoaTrocaPessoa" name="codPessoa">
					<input type="hidden" id="dataPrevisaoTrocaPessoa" name="dataPrevisao">
					<input type="hidden" id="codTipoEscalaTrocaPessoa" name="codTipoEscala">

					<div style="font-size:20px;font-weight: bold;" class="row">
						Escalado Original: <p id="labelnomeExibicaoTrocaPessoa"></p>
					</div>
					<div style="font-size:20px;font-weight: bold;" class="row">
						Data Previsão: <p id="labeldataPrevisaoTrocaPessoa"></p>
					</div>
					<div style="font-size:20px;font-weight: bold;" class="row">
						Tipo Escala: <p id="labelcodTipoEscalaTrocaPessoa"></p>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codPessoaTroca"> Substituído Por: <span class="text-danger">*</span> </label>
								<select id="codPessoaTroca" name="codPessoa" class="custom-select">
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="tipoTroca"> Tipo da Troca: <span class="text-danger">*</span> </label>
								<select id="tipoTroca" name="tipoTroca" class="custom-select">
									<option value=""></option>
									<option value="1">Por Data Referência</option>
									<option value="2">Por Pessoa Referência</option>
								</select>
							</div>
						</div>
					</div>


					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="observacoesTroca"> Observações (Opcional):</label>

								<textarea rows="4" cols="50" id="observacoesTroca" name="observacoes" class="form-control"></textarea>
							</div>
						</div>
					</div>

					<div class="row text-center">
						<div class="col-md-12">
							<div class="btn-group">
								<button type="button" onclick="trocarEscalaAgora()" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Trocar">Trocar agora</button>
								<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>


<div id="trocarPessoaEscalaModalEdit" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Editar Troca Pessoa Escala</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<form id="trocarPessoaEscalaFormEdit">
					<input type="hidden" id="codTrocaEscalaEdit" name="codTrocaEscalaEdit">


					<div style="font-size:20px;font-weight: bold;" class="row">
						Escalado Original: <p id="labelnomeExibicaoTrocaPessoaEdit"></p>
					</div>
					<div style="font-size:20px;font-weight: bold;" class="row">
						Data Previsão: <p id="labeldataPrevisaoTrocaPessoaEdit"></p>
					</div>
					<div style="font-size:20px;font-weight: bold;" class="row">
						Tipo Escala: <p id="labelcodTipoEscalaTrocaPessoaEdit"></p>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codPessoaTrocaEdit"> Substituído Por: <span class="text-danger">*</span> </label>
								<select id="codPessoaTrocaEdit" name="codPessoa" class="custom-select" required>
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="tipoTrocaEdit"> Tipo da Troca: <span class="text-danger">*</span> </label>
								<select id="tipoTrocaEdit" name="tipoTroca" class="custom-select" required>
									<option value=""></option>
									<option value="1">Por Data Referência</option>
									<option value="2">Por Pessoa Referência</option>
								</select>
							</div>
						</div>
					</div>


					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="observacoesTrocaEdit"> Observações (Opcional):</label>

								<textarea rows="4" cols="50" id="observacoesTrocaEdit" name="observacoes" class="form-control"></textarea>
							</div>
						</div>
					</div>

					<div class="row text-center">
						<div class="col-md-12">
							<div class="btn-group">
								<button type="button" onclick="editarTrocaAgora()" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Trocar">Salvar</button>
								<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
								<button style="margin-left:200px" type="button" id="removerTrocaFromEditar" class="btn btn-dark" data-toggle="tooltip" data-placement="top" title="Trocar">Remover Troca</button>
							</div>
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
	$(document).on('show.bs.modal', '.modal', function() {
		var zIndex = 1040 + (10 * $('.modal:visible').length);
		$(this).css('z-index', zIndex);
		setTimeout(function() {
			$('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
		}, 0);
	});

	$(function() {
		$('#data_tableescalas').DataTable({
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
				"url": '<?php echo base_url('escalas/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});
	});


	$("#codCargoAdd").on("change", function() {
		if ($("#codCargoAdd").val() !== "") {

			$.ajax({
				url: '<?php echo base_url('escalas/membrosEscala') ?>',
				type: 'post',
				dataType: 'json',
				data: {
					codEscala: $('#escalasEditForm #codEscala').val(),
					codCargo: $("#codCargoAdd").val(),
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
				success: function(membros) {

					$('#membrosConcorrentes').html(membros.html);
				}
			})
		}
	});

	function selecionarMembro(codPessoa, codEscala) {


		$.ajax({
			url: '<?php echo base_url('escalas/selecionarMembro') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				codPessoa: codPessoa,
				codEscala: codEscala,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(selecionar) {

				if (selecionar.success === true) {
					var Toast = Swal.mixin({
						toast: true,
						position: 'bottom-end',
						showConfirmButton: false,
						timer: 2000
					});
					Toast.fire({
						icon: 'success',
						title: 'Membro adicionado'
					})

					$('#membro' + codPessoa).removeClass('btn-primary');
					$('#membro' + codPessoa).addClass('btn-success');

					$('#data_membrosAtivos').DataTable().ajax.reload(null, false).draw(false);

				} else {
					var Toast = Swal.mixin({
						toast: true,
						position: 'bottom-end',
						showConfirmButton: false,
						timer: 2000
					});
					Toast.fire({
						icon: 'error',
						title: selecionar.messages,
					})
				}


			}
		})




	}


	function afastarMembroAdd() {


		$.ajax({
			url: '<?php echo base_url('escalas/afastarMembroAdd') ?>',
			type: 'post',
			data: {


				codMembroEscala: $('#codMembroEscalaAddMembro').val(),
				codStatusAfastamento: $('#codStatusAfastamento').val(),
				afastamentoIndeterminado: $("#afastamentoIndeterminado").is(':checked'),
				dataInicioAfastamento: $('#dataInicioAfastamento').val(),
				dataEncerramentoAfastamento: $('#dataEncerramentoAfastamento').val(),
				observacoes: $('#observacoes').val(),
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {

				if (response.success === true) {
					$('#data_membrosAtivos').DataTable().ajax.reload(null, false).draw(false);
					$('#data_membrosAfastados').DataTable().ajax.reload(null, false).draw(false);

					$('#afastarMembroAddModal').modal('hide');

					atualizarPrevisaoEscala();
					var Toast = Swal.mixin({
						toast: true,
						position: 'bottom-end',
						showConfirmButton: false,
						timer: 2000
					});
					Toast.fire({
						icon: 'success',
						title: response.messages
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



	function atualizarPrevisaoEscala() {

		$.ajax({
			url: '<?php echo base_url('escalas/atualizarPrevisaoEscala') ?>',
			type: 'post',
			data: {
				codEscala: $('#escalasEditForm #codEscala').val(),
				dataLimiteLiberacao: $('#dataLimiteLiberacao').val(),
				folgaLiberacao: $('#folgaLiberacao').val(),
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {

				if (response.success === true) {

					mostrarPrevisaoEscala();

					var Toast = Swal.mixin({
						toast: true,
						position: 'bottom-end',
						showConfirmButton: false,
						timer: 11000
					});
					Toast.fire({
						icon: 'success',
						title: response.messages
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
						title: response.messages
					})

				}
			}
		})


	}



	function mostrarPrevisaoEscala() {


		$.ajax({
			url: '<?php echo base_url('escalas/mostrarPrevisaoEscala') ?>',
			type: 'post',
			data: {
				codEscala: $('#escalasEditModal #codEscala').val(),
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},

			dataType: 'json',
			success: function(response) {
				$('#mostrarPrevisaoEscala').html(response.html);
			}
		})

	}

	function afastarMembroEditAgora() {


		$.ajax({
			url: '<?php echo base_url('escalas/afastarMembroEdit') ?>',
			type: 'post',
			data: {


				codAfastamento: $('#codAfastamentoEdit').val(),
				codStatusAfastamento: $('#codStatusAfastamentoEdit').val(),
				afastamentoIndeterminado: $("#afastamentoIndeterminadoEdit").is(':checked'),
				dataInicioAfastamento: $('#dataInicioAfastamentoEdit').val(),
				dataEncerramentoAfastamento: $('#dataEncerramentoAfastamentoEdit').val(),
				observacoes: $('#observacoesEdit').val(),
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {

				if (response.success === true) {
					$('#data_membrosAtivos').DataTable().ajax.reload(null, false).draw(false);
					$('#data_membrosAfastados').DataTable().ajax.reload(null, false).draw(false);

					$('#afastarMembroEditModal').modal('hide');
					atualizarPrevisaoEscala();

					var Toast = Swal.mixin({
						toast: true,
						position: 'bottom-end',
						showConfirmButton: false,
						timer: 2000
					});
					Toast.fire({
						icon: 'success',
						title: response.messages
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

	function trocarNaoAutorizada() {
		Swal.fire({
			icon: 'info',
			html: 'Trocas só são permitidas para as escalas mais recentes (preta/vermelha) de cada usuário',
			showConfirmButton: true,
			confirmButtonText: 'Ok',

		})
	}

	function trocarEscalaAgora() {

		if ($('#trocarPessoaEscalaForm #codPessoaTroca').val() == '') {
			Swal.fire({
				icon: 'warning',
				title: 'Defina a pessoa que substituirá!',
				showConfirmButton: true,
				confirmButtonText: 'Ok',

			})
			throw new Error('Defina a pessoa que substituirá!');

		}

		if ($('#trocarPessoaEscalaForm #tipoTroca').val() == '') {
			Swal.fire({
				icon: 'warning',
				title: 'Defina o tipo de troca!',
				showConfirmButton: true,
				confirmButtonText: 'Ok',

			})
			throw new Error('Defina o tipo de troca!');

		}

		$.ajax({
			url: '<?php echo base_url('escalas/trocarEscalaAgora') ?>',
			type: 'post',
			data: {
				codPrevisaoEscala: $('#trocarPessoaEscalaForm #codPrevisaoEscalaTrocaPessoa').val(),
				codPessoaOriginal: $('#trocarPessoaEscalaForm #codPessoaTrocaPessoa').val(),
				dataPrevisao: $('#trocarPessoaEscalaForm #dataPrevisaoTrocaPessoa').val(),
				codTipoEscala: $('#trocarPessoaEscalaForm #codTipoEscalaTrocaPessoa').val(),
				codPessoaTroca: $('#trocarPessoaEscalaForm #codPessoaTroca').val(),
				codEscala: $('#trocarPessoaEscalaForm #codEscalaTrocaPessoa').val(),
				tipoTroca: $('#trocarPessoaEscalaForm #tipoTroca').val(),
				observacoes: $('#trocarPessoaEscalaForm #observacoesTroca').val(),
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {

				if (response.success === true) {


					$('#data_tabletrocas').DataTable().ajax.reload(null, false).draw(false);

					$('#trocarPessoaEscalaModal').modal('hide');

					mostrarPrevisaoEscala();
					var Toast = Swal.mixin({
						toast: true,
						position: 'bottom-end',
						showConfirmButton: false,
						timer: 2000
					});
					Toast.fire({
						icon: 'success',
						title: response.messages
					})

				}
				if (response.success === false) {

					Swal.fire({
						icon: 'error',
						title: response.messages,
						showConfirmButton: true,
						confirmButtonText: 'Ok',

					})

				}


			}
		})
	}

	function trocar(codPrevisaoEscala) {

		$.ajax({
			url: '<?php echo base_url('escalas/dadosPrevisaoEscala') ?>',
			type: 'post',
			data: {
				codPrevisaoEscala: codPrevisaoEscala,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {

				$("#trocarPessoaEscalaForm")[0].reset();
				$('#trocarPessoaEscalaModal').modal('show');
				$('#codPrevisaoEscalaTrocaPessoa').val(codPrevisaoEscala);
				$('#codPessoaTrocaPessoa').val(response.codPessoa);
				$('#codEscalaTrocaPessoa').val(response.codEscala);
				$('#dataPrevisaoTrocaPessoa').val(response.dataPrevisao);
				$('#codTipoEscalaTrocaPessoa').val(response.codTipoEscala);


				$('#labelnomeExibicaoTrocaPessoa').html(response.nomeExibicao);
				$('#labeldataPrevisaoTrocaPessoa').html(response.previsao);

				if (response.codTipoEscala == 1) {
					$('#labelcodTipoEscalaTrocaPessoa').html('<i style="color:#000" class="fas fa-square"></i> Preta');
				} else {
					$('#labelcodTipoEscalaTrocaPessoa').html('<i style="color:red" class="fas fa-square"></i> Vermelha');
				}

				$('#codPessoaTroca').html('').select2({
					data: [{
						id: '',
						text: ''
					}]
				});


				$.ajax({
					url: '<?php echo base_url('Escalas/listaDropDownMembrosEscalas') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						codEscala: $('#escalasEditModal #codEscala').val(),
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(membros) {

						$("#codPessoaTroca").select2({
							data: membros,
						})

						$('#codPessoaTroca').val(null); // Select the option with a value of '1'
						$('#codPessoaTroca').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

					}
				})


			}
		})
	}

	function editarTrocaAgora() {

		$.ajax({
			url: '<?php echo base_url('escalas/editarTrocaAgora') ?>',
			type: 'post',
			data: {
				codTrocaEscala: $('#codTrocaEscalaEdit').val(),
				codPessoaTroca: $('#codPessoaTrocaEdit').val(),
				tipoTroca: $('#tipoTrocaEdit').val(),
				observacoes: $('#observacoesTrocaEdit').val(),
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {


				if (response.success === true) {

					$('#trocarPessoaEscalaModalEdit').modal('hide');

					$('#data_tabletrocas').DataTable().ajax.reload(null, false).draw(false);
					atualizarPrevisaoEscala();

					var Toast = Swal.mixin({
						toast: true,
						position: 'bottom-end',
						showConfirmButton: false,
						timer: 2000
					});
					Toast.fire({
						icon: 'success',
						title: response.messages
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

	function editarTroca(codTrocaEscala) {

		$.ajax({
			url: '<?php echo base_url('escalas/dadosTroca') ?>',
			type: 'post',
			data: {
				codTrocaEscala: codTrocaEscala,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {

				$("#trocarPessoaEscalaFormEdit")[0].reset();
				$('#trocarPessoaEscalaModalEdit').modal('show');
				$('#codTrocaEscalaEdit').val(response.codTrocaEscala);
				$('#tipoTrocaEdit').val(response.tipoTroca);
				$('#observacoesTrocaEdit').val(response.observacoes);


				$('#labelnomeExibicaoTrocaPessoaEdit').html(response.nomeExibicaoSai);
				$('#labeldataPrevisaoTrocaPessoaEdit').html(response.dataPrevisaoEscala);

				$('#removerTrocaFromEditar').removeAttr('onclick');
				$('#removerTrocaFromEditar').attr('onClick', 'removerTroca(' + response.codTrocaEscala + ');');




				if (response.codTipoEscala == 1) {
					$('#labelcodTipoEscalaTrocaPessoaEdit').html('<i style="color:#000" class="fas fa-square"></i> Preta');
				} else {
					$('#labelcodTipoEscalaTrocaPessoaEdit').html('<i style="color:red" class="fas fa-square"></i> Vermelha');
				}

				$('#codPessoaTroca').html('').select2({
					data: [{
						id: '',
						text: ''
					}]
				});


				$.ajax({
					url: '<?php echo base_url('Escalas/listaDropDownMembrosEscalas') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						codEscala: $('#escalasEditModal #codEscala').val(),
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(membros) {

						$("#codPessoaTrocaEdit").select2({
							data: membros,
						})

						$('#codPessoaTrocaEdit').val(response.trocadoPor); // Select the option with a value of '1'
						$('#codPessoaTrocaEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

					}
				})


			}
		})
	}

	function afastarmembro(codMembroEscala) {

		$("#afastarMembroAddForm")[0].reset();
		$('#afastarMembroAddModal').modal('show');
		$('#codMembroEscalaAddMembro').val(codMembroEscala);
		$('#periodoAfastamento').show();

		$.ajax({
			url: '<?php echo base_url('escalas/listaDropDownMotivosAfastamentos') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(statusAfastamento) {

				$("#codStatusAfastamento").select2({
					data: statusAfastamento,
				})

				$('#codStatusAfastamento').val(null); // Select the option with a value of '1'
				$('#codStatusAfastamento').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})


		$("#afastamentoIndeterminado").change(function() {
			if ($(this).prop('checked')) {
				$('#periodoAfastamento').hide();
			} else {
				$('#periodoAfastamento').show();
			}
		});



	}



	function desativarDataVermelha(codDataVermelha) {
		Swal.fire({
			title: 'Você tem certeza que deseja desativar esta data Vermelha?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {

			if (result.value) {
				$.ajax({
					url: '<?php echo base_url('escalas/desativarDataVermelha') ?>',
					type: 'post',
					data: {
						codDataVermelha: codDataVermelha,
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
								$('#data_tableDatasVermelhas').DataTable().ajax.reload(null, false).draw(false);
								atualizarPrevisaoEscala();
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



	function ativarDataVermelha(codDataVermelha) {
		Swal.fire({
			title: 'Você tem certeza que deseja reativar esta data Vermelha?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {

			if (result.value) {
				$.ajax({
					url: '<?php echo base_url('escalas/ativarDataVermelha') ?>',
					type: 'post',
					data: {
						codDataVermelha: codDataVermelha,
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
								$('#data_tableDatasVermelhas').DataTable().ajax.reload(null, false).draw(false);
								atualizarPrevisaoEscala();
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



	function removerDataVermelha(codDataVermelha) {
		Swal.fire({
			title: 'Você tem certeza que deseja remover definitivamente esta data Vermelha?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {

			if (result.value) {
				$.ajax({
					url: '<?php echo base_url('escalas/removerDataVermelha') ?>',
					type: 'post',
					data: {
						codDataVermelha: codDataVermelha,
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
								$('#data_tableDatasVermelhas').DataTable().ajax.reload(null, false).draw(false);
								atualizarPrevisaoEscala();
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


	function editarAfastamento(codAfastamento) {




		$.ajax({
			url: '<?php echo base_url('escalas/getAfastamento') ?>',
			type: 'post',
			data: {
				codAfastamento: codAfastamento,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {

				$("#afastarMembroEditForm")[0].reset();
				$('#afastarMembroEditModal').modal('show');
				$('#codAfastamentoEdit').val(codAfastamento);
				$('#periodoAfastamentoEdit').show();

				$('#afastarMembroEditHeader').html('Afastamento de ' + response.nomeExibicao);






				if (response.afastamentoIndeterminado == 1) {
					$("#afastamentoIndeterminadoEdit").prop("checked", true);
					$('#periodoAfastamentoEdit').hide();
				} else {
					$('#periodoAfastamentoEdit').show();
				}

				$('#dataInicioAfastamentoEdit').val(response.dataInicioAfastamento);
				$('#dataEncerramentoAfastamentoEdit').val(response.dataEncerramentoAfastamento);
				$('#observacoesEdit').val(response.observacoes);

				$("#afastamentoIndeterminadoEdit").change(function() {
					if ($(this).prop('checked')) {
						$('#periodoAfastamentoEdit').hide();
					} else {
						$('#periodoAfastamentoEdit').show();
					}
				});


				$.ajax({
					url: '<?php echo base_url('escalas/listaDropDownMotivosAfastamentos') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(statusAfastamento) {

						$("#codStatusAfastamentoEdit").select2({
							data: statusAfastamento,
						})

						$('#codStatusAfastamentoEdit').val(response.codStatus); // Select the option with a value of '1'
						$('#codStatusAfastamentoEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

					}
				})

			}
		})




	}

	function definirUltimoServicoPreta(codMembroEscala) {

		Swal.fire({
			title: '<div style="background:#000;color:#fff">Escala Preta</div><div>Você tem certeza que deseja redefinir a Data do última escalação Preta?</div>',
			html: '<input type="date" id="dataUltimoEscalacaoPretaAdd" name="dataUltimoEscalacaoPreta" class="form-control" required>',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar',

		}).then((result) => {

			if (result.isConfirmed) {

				if ($('#dataUltimoEscalacaoPretaAdd').val() == undefined || $('#dataUltimoEscalacaoPretaAdd').val() == '' || $('#dataUltimoEscalacaoPretaAdd').val() == null) {

					Swal.fire({
						icon: 'error',
						html: 'É necessário definir a data',
						showConfirmButton: true,
						confirmButtonText: 'Ok',

					})
				} else {
					$.ajax({
						url: '<?php echo base_url('escalas/dataUltimoEscalacaoPreta') ?>',
						type: 'post',
						data: {
							codMembroEscala: codMembroEscala,
							dataUltimoEscalacaoPreta: $('#dataUltimoEscalacaoPretaAdd').val(),
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},
						dataType: 'json',
						success: function(response) {

							if (response.success === true) {


								$('#data_membrosAtivos').DataTable().ajax.reload(null, false).draw(false);
								$('#data_membrosAfastados').DataTable().ajax.reload(null, false).draw(false);
								var Toast = Swal.mixin({
									toast: true,
									position: 'bottom-end',
									showConfirmButton: false,
									timer: 2000
								});
								Toast.fire({
									icon: 'success',
									title: response.messages
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
			}
		})
	}

	definirUltimoServicoVermelha

	function definirUltimoServicoVermelha(codMembroEscala) {

		Swal.fire({
			title: '<div style="background:red;color:#fff">Escala Vermelha</div><div>Você tem certeza que deseja redefinir a Data do última escalação Vermelha?</div>',
			icon: 'info',
			html: '<input type="date" id="dataUltimoEscalacaoVermelhaAdd" name="dataUltimoEscalacaoVermelha" class="form-control" required>',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar',

		}).then((result) => {

			if (result.isConfirmed) {

				if ($('#dataUltimoEscalacaoVermelhaAdd').val() == undefined || $('#dataUltimoEscalacaoVermelhaAdd').val() == '' || $('#dataUltimoEscalacaoVermelhaAdd').val() == null) {

					Swal.fire({
						icon: 'error',
						html: 'É necessário definir a data',
						showConfirmButton: true,
						confirmButtonText: 'Ok',

					})
				} else {
					$.ajax({
						url: '<?php echo base_url('escalas/dataUltimoEscalacaoVermelha') ?>',
						type: 'post',
						data: {
							codMembroEscala: codMembroEscala,
							dataUltimoEscalacaoVermelha: $('#dataUltimoEscalacaoVermelhaAdd').val(),
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},
						dataType: 'json',
						success: function(response) {

							if (response.success === true) {


								$('#data_membrosAtivos').DataTable().ajax.reload(null, false).draw(false);
								$('#data_membrosAfastados').DataTable().ajax.reload(null, false).draw(false);
								var Toast = Swal.mixin({
									toast: true,
									position: 'bottom-end',
									showConfirmButton: false,
									timer: 2000
								});
								Toast.fire({
									icon: 'success',
									title: response.messages
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
			}
		})
	}

	function prontoServico(codMembroEscala) {

		Swal.fire({
			title: 'Você deseja retornar esta pessoa para escala?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {

			if (result.value) {


				$.ajax({
					url: '<?php echo base_url('escalas/prontoServico') ?>',
					type: 'post',
					data: {
						codMembroEscala: codMembroEscala,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					dataType: 'json',
					success: function(response) {

						if (response.success === true) {
							$('#data_membrosAtivos').DataTable().ajax.reload(null, false).draw(false);
							$('#data_membrosAfastados').DataTable().ajax.reload(null, false).draw(false);
							var Toast = Swal.mixin({
								toast: true,
								position: 'bottom-end',
								showConfirmButton: false,
								timer: 2000
							});
							Toast.fire({
								icon: 'success',
								title: response.messages
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

	function removerDefinitivo(codMembroEscala) {

		Swal.fire({
			title: 'Você deseja remover definitivamente esta pessoa para escala?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {

			if (result.value) {


				$.ajax({
					url: '<?php echo base_url('escalas/removerDefinitivo') ?>',
					type: 'post',
					data: {
						codMembroEscala: codMembroEscala,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					dataType: 'json',
					success: function(response) {

						if (response.success === true) {
							$('#data_membrosAtivos').DataTable().ajax.reload(null, false).draw(false);
							$('#data_membrosAfastados').DataTable().ajax.reload(null, false).draw(false);
							var Toast = Swal.mixin({
								toast: true,
								position: 'bottom-end',
								showConfirmButton: false,
								timer: 2000
							});
							Toast.fire({
								icon: 'success',
								title: response.messages
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

	function adicionarMembro() {


		$('#membrosConcorrentes').html('');
		$('#membrosAddModal').modal('show');

		$.ajax({
			url: '<?php echo base_url('pacientes/listaDropDownCargos') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(cargosAdd) {

				$("#codCargoAdd").select2({
					data: cargosAdd,
				})

				$('#codCargoAdd').val(null); // Select the option with a value of '1'
				$('#codCargoAdd').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})
	}

	function addescalas() {
		// reset the form 
		$("#escalasAddForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#escalasAddModal').modal('show');


		$.ajax({
			url: '<?php echo base_url('departamentos/listaDropDown') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(departamentoAdd) {

				$("#escalasAddForm #setorGestor").select2({
					data: departamentoAdd,
				})

				$('#escalasAddForm #setorGestor').val(null); // Select the option with a value of '1'
				$('#escalasAddForm #setorGestor').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});


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

				var form = $('#escalasAddForm');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('escalas/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#escalasAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function(response) {

						if (response.success === true) {
							$('#escalasAddModal').modal('hide');

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
								$('#data_tableescalas').DataTable().ajax.reload(null, false).draw(false);
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
						$('#escalasAddForm-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#escalasAddForm').validate();
	}


	function adicionarDataVermelha() {
		// reset the form 
		$("#dataVermelhaAddForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#dataVermelhaAddModal').modal('show');


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

				var form = $('#dataVermelhaAddForm');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('escalas/adicionarDataVermelha') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					success: function(response) {

						if (response.success === true) {
							$('#dataVermelhaAddModal').modal('hide');

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
								$('#data_tableDatasVermelhas').DataTable().ajax.reload(null, false).draw(false);
								atualizarPrevisaoEscala();
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
		$('#dataVermelhaAddForm').validate();
	}



	function editescalas(codEscala) {
		$.ajax({
			url: '<?php echo base_url('escalas/getOne') ?>',
			type: 'post',
			data: {
				codEscala: codEscala,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#escalasEditForm")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#escalasEditModal').modal('show');

				$("#escalasEditForm #codEscala").val(response.codEscala);
				$("#escalasEditForm #descricao").val(response.descricao);
				$("#escalasEditForm #dataCriacao").val(response.dataCriacao);
				$("#escalasEditForm #dataAtualizacao").val(response.dataAtualizacao);
				$("#escalasEditForm #codAutor").val(response.codAutor);
				$("#escalasEditForm #setorGestor").val(response.setorGestor);
				$("#escalasEditForm #modificadoPor").val(response.modificadoPor);
				$("#escalasEditModalHeader").html(response.descricao);
				$("#dataLimiteLiberacao").val(response.dataLimiteLiberacao);
				$("#folgaLiberacao").val(response.folgaLiberacao);


				$.ajax({
					url: '<?php echo base_url('departamentos/listaDropDown') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(departamentoAdd) {

						$("#escalasEditForm #setorGestor").select2({
							data: departamentoAdd,
						})

						$('#escalasEditForm #setorGestor').val(response.setorGestor); // Select the option with a value of '1'
						$('#escalasEditForm #setorGestor').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});


					}
				})



				$('#data_membrosAtivos').DataTable({
					"bDestroy": true,
					"paging": true,
					"deferRender": true,
					"lengthChange": false,
					"searching": true,
					"pageLength": 100,
					"ordering": true,
					"order": [
						[1, 'desc']
					],
					"info": true,
					"autoWidth": false,
					"responsive": true,
					"ajax": {
						"url": '<?php echo base_url('escalas/membrosAtivos') ?>',
						"type": "POST",
						"dataType": "json",
						async: "true",
						data: {
							codEscala: $('#escalasEditForm #codEscala').val(),
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},
					}
				});

				$('#data_membrosAfastados').DataTable({
					"bDestroy": true,
					"paging": true,
					"deferRender": true,
					"lengthChange": false,
					"pageLength": 100,
					"searching": true,
					"ordering": true,
					"order": [
						[3, 'asc']
					],
					"info": true,
					"autoWidth": false,
					"responsive": true,
					"ajax": {
						"url": '<?php echo base_url('escalas/membrosAfastados') ?>',
						"type": "POST",
						"dataType": "json",
						async: "true",
						data: {
							codEscala: $('#escalasEditForm #codEscala').val(),
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},
					}
				});

				$('#data_tableDatasVermelhas').DataTable({
					"bDestroy": true,
					"paging": true,
					"deferRender": true,
					"lengthChange": false,
					"pageLength": 100,
					"searching": true,
					"ordering": false,
					"info": true,
					"autoWidth": false,
					"responsive": true,
					"ajax": {
						"url": '<?php echo base_url('escalas/datasVermelhas') ?>',
						"type": "POST",
						"dataType": "json",
						async: "true",
						data: {
							codEscala: $('#escalasEditForm #codEscala').val(),
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},
					}
				});


				var qtdTrocas = 0;
				$('#data_tabletrocas').DataTable({
					"bDestroy": true,
					"paging": true,
					"deferRender": true,
					"lengthChange": false,
					"pageLength": 100,
					"searching": true,
					"ordering": false,
					"info": true,
					"autoWidth": false,
					"responsive": true,
					"ajax": {
						"url": '<?php echo base_url('escalas/listaTrocas') ?>',
						"type": "POST",
						"dataType": "json",
						async: "true",
						data: {
							codEscala: $('#escalasEditForm #codEscala').val(),
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},
					},
					"drawCallback": function(settings, json) {
						var api = this.api();
						qtdTrocas = api.rows().count();

						document.getElementById("qtdTrocas").innerHTML = qtdTrocas
					}
				});




				mostrarPrevisaoEscala();

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
						var form = $('#escalasEditForm');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('escalas/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#escalasEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
							},
							success: function(response) {

								if (response.success === true) {

									$('#escalasEditModal').modal('hide');


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
										$('#data_tableescalas').DataTable().ajax.reload(null, false).draw(false);
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
								$('#escalasEditForm-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#escalasEditForm').validate();

			}
		});
	}

	function removeescalas(codEscala) {
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
					url: '<?php echo base_url('escalas/remove') ?>',
					type: 'post',
					data: {
						codEscala: codEscala,
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
								$('#data_tableescalas').DataTable().ajax.reload(null, false).draw(false);
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

	function removerAfastamento(codAfastamento) {
		Swal.fire({
			title: 'Você tem certeza que deseja remover o afastamento?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {

			if (result.value) {
				$.ajax({
					url: '<?php echo base_url('escalas/removerAfastamento') ?>',
					type: 'post',
					data: {
						codAfastamento: codAfastamento,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					dataType: 'json',
					success: function(response) {

						if (response.success === true) {

							$('#data_membrosAtivos').DataTable().ajax.reload(null, false).draw(false);
							$('#data_membrosAfastados').DataTable().ajax.reload(null, false).draw(false);
							atualizarPrevisaoEscala();
							var Toast = Swal.mixin({
								toast: true,
								position: 'bottom-end',
								showConfirmButton: false,
								timer: 2000
							});
							Toast.fire({
								icon: 'success',
								title: response.messages
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

	function removerTroca(codTrocaEscala) {
		Swal.fire({
			title: 'Você tem certeza que deseja remover esta troca?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {

			if (result.value) {
				$.ajax({
					url: '<?php echo base_url('escalas/removerTroca') ?>',
					type: 'post',
					data: {
						codTrocaEscala: codTrocaEscala,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					dataType: 'json',
					success: function(response) {

						if (response.success === true) {

							if ($('#trocarPessoaEscalaModalEdit').is(':visible') == true) {
								$('#trocarPessoaEscalaModalEdit').modal('hide');
							}

							$('#data_tabletrocas').DataTable().ajax.reload(null, false).draw(false);
							atualizarPrevisaoEscala();



							var Toast = Swal.mixin({
								toast: true,
								position: 'bottom-end',
								showConfirmButton: false,
								timer: 2000
							});
							Toast.fire({
								icon: 'success',
								title: response.messages
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