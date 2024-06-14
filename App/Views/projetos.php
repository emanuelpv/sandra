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
<link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/summernote/summernote-bs4.min.css">

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-md-8 mt-2">
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Projetos</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary" onclick="add()" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<table id="data_table" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>Código</th>
								<th>Descrição</th>
								<th>Departamento</th>
								<th>Gestor</th>
								<th>Supervisor</th>
								<th>Status</th>
								<th>Tipo Projeto</th>
								<th>Data Início Projeto</th>
								<th>Data Encerramento Projeto</th>

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

<div id="edit-modalprojetosFase" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Fases Projeto</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="edit-formprojetosFase" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>edit-formprojetosFase" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codProjetoFaseEdit" name="codProjetoFase" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">

						<div class="col-md-4">
							<div class="form-group">
								<label for="descricaoFase"> Descrição da Fase: <span class="text-danger">*</span> </label>
								<input type="text" id="descricaoFaseEdit" name="descricaoFase" class="form-control" placeholder="DescricaoFase" maxlength="100" required>
							</div>
						</div>
					</div>
					<div class="row">

						<div class="col-md-4">
							<div class="form-group">
								<label for="dataInicial"> Data Inicial: </label>
								<input type="date" id="dataInicialEdit" name="dataInicial" class="form-control" placeholder="DataInicial">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataEncerramento"> Data Encerramento: </label>
								<input type="date" id="dataEncerramentoEdit" name="dataEncerramento" class="form-control" placeholder="DataEncerramento">
							</div>
						</div>
					</div>


					<div class="form-group text-center">
						<div class="btn-group">
							<button onclick="editarFase()" type="button" class="btn btn-xs btn-success">Salvar</button>
							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>

			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>

<div id="add-modalprojetosFase" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Fases Projeto</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="add-formprojetosFase" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>add-formprojetosFase" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codProjetoFaseAdd" name="codProjeto" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">

						<div class="col-md-4">
							<div class="form-group">
								<label for="descricaoFase"> Descrição da Fase: <span class="text-danger">*</span> </label>
								<input type="text" id="descricaoFase" name="descricaoFase" class="form-control" placeholder="DescricaoFase" maxlength="100" number="true" required>
							</div>
						</div>
					</div>
					<div class="row">

						<div class="col-md-4">
							<div class="form-group">
								<label for="dataInicial"> Data Inicial: </label>
								<input type="date" id="dataInicial" name="dataInicial" class="form-control" placeholder="DataInicial">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataEncerramento"> Data Encerramento: </label>
								<input type="date" id="dataEncerramento" name="dataEncerramento" class="form-control" placeholder="DataEncerramento">
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button onclick="adicionarFase()" type="button" class="btn btn-xs btn-success">Adicionar</button>
							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>


<div id="add-modalprojetosEscopo" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Escopo do Projeto</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="add-formprojetosEscopo" class="pl-3 pr-3">
					<div class="row">
						<div class="col-md-2">
							<input type="hidden" id="<?php echo csrf_token() ?>addformprojetosEscopo" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

							<input type="hidden" id="codProjetoAddEscopo" name="codProjeto" class="form-control" placeholder="CodProjeto" maxlength="11" number="true" required>
							<input type="hidden" id="codTipoEscopo" name="codTipoEscopo" class="form-control" placeholder="codTipoEscopo" maxlength="11" number="true" required>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="descricaoEscopo"> Descrição do Escopo: <span class="text-danger">*</span> </label>
								<input type="text" id="descricaoEscopo" name="descricaoEscopo" class="form-control" placeholder="Descrição do Escopo" maxlength="150" required>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button onclick="adicionarEscopo()" type="button" class="btn btn-xs btn-success">Adicionar</button>
							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>


<!-- Add modal content -->
<div id="add-modal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Projeto</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="add-form" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>add-form" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codProjeto" name="codProjeto" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="descricaoProjeto"> Descrição: <span class="text-danger">*</span> </label>
								<input type="text" id="descricaoProjeto" name="descricaoProjeto" class="form-control" placeholder="Descrição" maxlength="300" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codDepartamento"> Departamento: <span class="text-danger">*</span> </label>
								<select id="codDepartamentoAdd" name="codDepartamento" class="select2" data-placeholder="Selecione um departamento" style="width: 100%;">
									<option></option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codStatusProjeto"> Status: <span class="text-danger">*</span> </label>
								<select id="codStatusProjetoAdd" name="codStatusProjeto" class="select2" data-placeholder="Status Inicial do Projeto" style="width: 100%;">
									<option></option>
								</select>

							</div>
						</div>


						<div class="col-md-4">
							<div class="form-group">
								<label for="codTipoProjeto"> Tipo Projeto: <span class="text-danger">*</span> </label>
								<select id="codTipoProjetoAdd" name="codTipoProjeto" class="select2" data-placeholder="Tipo de Projeto" style="width: 100%;">
									<option></option>
								</select>

							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="dataInicioProjeto"> Data Início Projeto: </label>
								<input type="date" id="dataInicioProjeto" name="dataInicioProjeto" class="form-control" dateISO="true">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="dataEncerramentoProjeto"> Data Encerramento Projeto: </label>
								<input type="date" id="dataEncerramentoProjeto" name="dataEncerramentoProjeto" class="form-control" dateISO="true">
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-xs btn-primary" id="add-form-btn">Adicionar</button>
							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<div id="add-modalprojetosMembros" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Membros do Projeto</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="add-formprojetosMembros" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>add-formprojetosMembros" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codProjetoAdicionarMembro" name="codProjeto" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codMembro"> CodMembro: <span class="text-danger">*</span> </label>
								<select id="codMembro" name="codMembro" class="custom-select" required>
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codTipoMembro"> CodTipoMembro: <span class="text-danger">*</span> </label>
								<select id="codTipoMembro" name="codTipoMembro" class="custom-select" required>
									<option value=""></option>
								</select>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button onclick="adicionarMembro()" type="button" class="btn btn-xs btn-success">Adicionar</button>
							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>



<div id="edit-modal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="modalLabelProjeto"></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">


				<div class="col-12 col-sm-12">
					<div class="card card-primary">
						<div class="card-header p-0 border-bottom-0">
							<ul class="nav nav-tabs" id="Perfis" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" id="geral-tab" data-toggle="pill" href="#geral" role="tab" aria-controls="geral" aria-selected="true">Geral</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="objetivo-tab" data-toggle="pill" href="#objetivo" role="tab" aria-controls="objetivo" aria-selected="false">Objetivo</a>
								</li>

								<li class="nav-item">
									<a class="nav-link" id="justificativa-tab" data-toggle="pill" href="#justificativa" role="tab" aria-controls="justificativa" aria-selected="false">Justificativa</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="beneficios-tab" data-toggle="pill" href="#beneficios" role="tab" aria-controls="beneficios" aria-selected="false">Benefícios</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="escopo-tab" data-toggle="pill" href="#escopo" role="tab" aria-controls="escopo" aria-selected="false">Escopo</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="membros-tab" data-toggle="pill" href="#membros" role="tab" aria-controls="membros" aria-selected="false">Membros</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="fases-tab" data-toggle="pill" href="#fases" role="tab" aria-controls="fases" aria-selected="false">Fases</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="documentos-tab" data-toggle="pill" href="#documentos" role="tab" aria-controls="documentos" aria-selected="false">Documentos</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="riscos-tab" data-toggle="pill" href="#riscos" role="tab" aria-controls="riscos" aria-selected="false">Riscos</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="tarefas-tab" data-toggle="pill" href="#tarefas" role="tab" aria-controls="tarefas" aria-selected="false">Tarefas</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="propriedades-tab" data-toggle="pill" href="#propriedades" role="tab" aria-controls="propriedades" aria-selected="false">Propriedades</a>
								</li>
							</ul>
						</div>
						<div class="card-body">
							<div class="tab-content" id="PerfisContent">
								<div class="tab-pane fade show active" id="geral" role="tabpanel" aria-labelledby="geral-tab">
									<div class="row">
										<div class="col-md-6">
											<div style="background: #8cc3ff82" class="card card-primary">

												<!-- /.card-header -->
												<div class="card-body">
													<div class="row">
														<div class="col-md-12">
															<span style="font-weight: bold;">Projeto: </span> <span style="margin-left:5px" id=descricaoProjetoInfo></span>
														</div>
													</div>

													<div class="row">
														<div class="col-md-12">
															<span style="font-weight: bold;">Período: De</span> <span style="margin-left:5px" id=dataInicioInfo></span> <span style="font-weight: bold;">Até </span><span style="margin-left:5px" id=dataTerminoInfo></span>
														</div>
													</div>

													<div class="row">
														<div class="col-md-12">
															<span style="font-weight: bold;">Gestor: </span> <span style="margin-left:5px" id=gestorInfo></span>
														</div>
													</div>


													<div class="row">
														<div class="col-md-12">
															<span style="font-weight: bold;">Status: </span> <span style="margin-left:5px" id=descricaoStatusProjetoInfo></span>
														</div>
													</div>
												</div>
												<!-- /.card-body -->
											</div>
											<!-- /.card -->
										</div>
									</div>
								</div>

								<div class="tab-pane fade" id="objetivo" role="tabpanel" aria-labelledby="objetivo-tab">


									<div class="col-md-12">
										<div class="card card-primary" style="transition: all 0.15s ease 0s; height: inherit; width: inherit;">
											<div class="card-header">
												<h3 class="card-title">Objetivo</h3>

												<div class="card-tools">
													<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
													</button>
												</div>
												<!-- /.card-tools -->
											</div>
											<!-- /.card-header -->
											<div class="card-body">
												<form id="objetivo-form" class="pl-3 pr-3">
													<div class="row">
														<input type="hidden" id="<?php echo csrf_token() ?>objetivo-form" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

														<input type="hidden" id="codProjetoobjetivo" name="codProjeto" class="form-control" placeholder="Código" maxlength="11" required>
													</div>
													<div class="row">
														<div class="col-md-12">
															<div class="form-group">
																<textarea cols="40" rows="50" id="objetivoEdit" name="objetivo" class="form-control" placeholder="descreva o objetivo com verbos no infinitivo" required></textarea>
															</div>
														</div>

													</div>
													<div class="form-group text-center">
														<div class="btn-group">
															<button onclick="salvarObjetivo()" type="button" class="btn btn-xs btn-success">SALVAR</button>
														</div>
													</div>
												</form>
											</div>
											<!-- /.card-body -->
										</div>
										<!-- /.card -->
									</div>
								</div>


								<div class="tab-pane fade" id="justificativa" role="tabpanel" aria-labelledby="justificativa-tab">



									<div class="col-md-12">
										<div class="card card-primary" style="transition: all 0.15s ease 0s; height: inherit; width: inherit;">
											<div class="card-header">
												<h3 class="card-title">Justificativa</h3>

												<div class="card-tools">
													<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
													</button>
												</div>
												<!-- /.card-tools -->
											</div>
											<!-- /.card-header -->
											<div class="card-body">
												<form id="justificativa-form" class="pl-3 pr-3">
													<div class="row">
														<input type="hidden" id="<?php echo csrf_token() ?>justificativa-form" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

														<input type="hidden" id="codProjetoJustificativa" name="codProjeto" class="form-control" placeholder="Código" maxlength="11" required>
													</div>
													<div class="row">
														<div class="col-md-12">
															<div class="form-group">
																<textarea cols="40" rows="50" id="justificativaEdit" name="justificativa" class="form-control" placeholder="Listar problemas que precisam ser resolvidos através deste projeto" required></textarea>
															</div>
														</div>

													</div>
													<div class="form-group text-center">
														<div class="btn-group">
															<button onclick="salvarJustificativa()" type="button" class="btn btn-xs btn-success">SALVAR</button>
														</div>
													</div>
												</form>
											</div>
											<!-- /.card-body -->
										</div>
										<!-- /.card -->
									</div>


								</div>



								<div class="tab-pane fade" id="beneficios" role="tabpanel" aria-labelledby="beneficios-tab">


									<div class="col-md-12">
										<div class="card card-primary" style="transition: all 0.15s ease 0s; height: inherit; width: inherit;">
											<div class="card-header">
												<h3 class="card-title">Benefícios</h3>

												<div class="card-tools">
													<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
													</button>
												</div>
												<!-- /.card-tools -->
											</div>
											<!-- /.card-header -->
											<div class="card-body">
												<form id="beneficios-form" class="pl-3 pr-3">
													<div class="row">
														<input type="hidden" id="<?php echo csrf_token() ?>beneficios-form" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

														<input type="hidden" id="codProjetobeneficios" name="codProjeto" class="form-control" placeholder="Código" maxlength="11" required>
													</div>
													<div class="row">
														<div class="col-md-12">
															<div class="form-group">
																<textarea cols="40" rows="50" id="beneficiosEdit" name="beneficios" class="form-control" placeholder="descreva quais serão os ganhos" required></textarea>
															</div>
														</div>

													</div>
													<div class="form-group text-center">
														<div class="btn-group">
															<button onclick="salvarBeneficios()" type="button" class="btn btn-xs btn-success">SALVAR</button>
														</div>
													</div>
												</form>
											</div>
											<!-- /.card-body -->
										</div>
										<!-- /.card -->
									</div>

								</div>

								<div class="tab-pane fade" id="escopo" role="tabpanel" aria-labelledby="escopo-tab">

									<div class="row">

										<div class="col-md-6">
											<div class="card card-primary">
												<div class="card-header">
													<h3 class="card-title">PERTENCE AO ESCOPO</h3>
												</div>
												<div class="card-body">
													<button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" onclick="showAdicionarEscopo()" title="Adicionar" id="add-formprojetosEscopo-btn">Adicionar</button>
													<table id="data_tableEscopo" class="table table-striped table-hover table-sm">
														<thead>
															<tr>
																<th>Código</th>
																<th>Escopo</th>
																<th></th>
															</tr>
														</thead>
													</table>
												</div>
												<!-- /.card-body -->
											</div>
											<!-- /.card -->
										</div>

										<div class="col-md-6">
											<div class="card card-danger">
												<div class="card-header">
													<h3 class="card-title">NÃO PERTENCE AO ESCOPO</h3>
												</div>
												<div class="card-body">
													<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" onclick="showAdicionarNaoEscopo()" title="Adicionar" id="add-formprojetosEscopo-btn">Adicionar</button>
													<table id="data_tableNaoEscopo" class="table table-striped table-hover table-sm">
														<thead>
															<tr>
																<th>Código</th>
																<th>Não Escopo</th>
																<th></th>
															</tr>
														</thead>
													</table>
												</div>
												<!-- /.card-body -->
											</div>
											<!-- /.card -->
										</div>

									</div>
								</div>


								<div class="tab-pane fade" id="membros" role="tabpanel" aria-labelledby="membros-tab">
									<button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" onclick="showAdicionarMembro()" title="Adicionar">Adicionar</button>

									<table id="data_tableprojetosMembros" class="table table-striped table-hover table-sm">
										<thead>
											<tr>
												<th>Código</th>
												<th>Membro</th>
												<th>Tipo Membro</th>
												<th></th>
											</tr>
										</thead>
									</table>
								</div>

								<div class="tab-pane fade" id="fases" role="tabpanel" aria-labelledby="fases-tab">

									<button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" onclick="showAdicionarFase()" title="Adicionar" id="add-formprojetosEscopo-btn">Adicionar</button>

									<table id="data_tableprojetosFase" class="table table-striped table-hover table-sm">
										<thead>
											<tr>
												<th>Código</th>
												<th>Fase</th>
												<th>DataInicial</th>
												<th>DataEncerramento</th>

												<th></th>
											</tr>
										</thead>
									</table>
								</div>

								<div class="tab-pane fade" id="tarefas" role="tabpanel" aria-labelledby="tarefas-tab">
									tarefas
								</div>

								<div class="tab-pane fade" id="documentos" role="tabpanel" aria-labelledby="documentos-tab">

									documentos
								</div>

								<div class="tab-pane fade" id="riscos" role="tabpanel" aria-labelledby="riscos-tab">
									riscos
								</div>

								<div class="tab-pane fade" id="propriedades" role="tabpanel" aria-labelledby="propriedades-tab">
									<form id="edit-form" class="pl-3 pr-3">
										<div class="row">
											<input type="hidden" id="<?php echo csrf_token() ?>propriedades" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

											<input type="hidden" id="codProjeto" name="codProjeto" class="form-control" placeholder="Código" maxlength="11" required>
										</div>

										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="descricaoProjeto"> Descrição: <span class="text-danger">*</span> </label>
													<input type="text" id="descricaoProjeto" name="descricaoProjeto" class="form-control" placeholder="Descrição" maxlength="300" required>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="codDepartamento"> Departamento: <span class="text-danger">*</span> </label>
													<select id="codDepartamentoEdit" name="codDepartamento" class="select2" data-placeholder="Selecione um departamento" style="width: 100%;">
														<option></option>
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="codStatusProjeto"> Status: <span class="text-danger">*</span> </label>
													<select id="codStatusProjetoEdit" name="codStatusProjeto" class="select2" data-placeholder="Status Inicial do Projeto" style="width: 100%;">
														<option></option>
													</select>

												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="codTipoProjeto"> Tipo Projeto: <span class="text-danger">*</span> </label>
													<select id="codTipoProjetoEdit" name="codTipoProjeto" class="select2" data-placeholder="Tipo de Projeto" style="width: 100%;">
														<option></option>
													</select>

												</div>
											</div>
										</div>
										<div class="row">


											<div class="col-md-3">
												<div class="form-group">
													<label for="dataInicioProjeto"> Data Início Projeto: </label>
													<input type="date" id="dataInicioProjetoEdit" name="dataInicioProjeto" class="form-control" dateISO="true">
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label for="dataEncerramentoProjeto"> Data Encerramento Projeto: </label>
													<input type="date" id="dataEncerramentoProjetoEdit" name="dataEncerramentoProjeto" class="form-control" dateISO="true">
												</div>
											</div>
										</div>

										<div class="form-group text-center">
											<div class="btn-group">
												<button type="submit" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="edit-form-btn">Salvar</button>
												<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
											</div>
										</div>
									</form>
								</div>



							</div>
						</div>
					</div>
				</div>

			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>



<?php
echo view('tema/rodape');
?>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/summernote/summernote-bs4.min.js"></script>

<script>
	$(function() {
		$('#data_table').DataTable({

			"bDestroy": true,
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url($controller . '/getAll') ?>',
				"type": "get",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});


	});



	function salvarBeneficios() {

		var form = $('#beneficios-form');
		$.ajax({
			url: '<?php echo base_url('projetos/salvaBeneficios') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(responseBeneficios) {

				if (responseBeneficios.success === true) {

					Swal.fire({
						position: 'bottom-end',
						icon: 'success',
						title: responseBeneficios.messages,
						showConfirmButton: false,
						timer: 2500
					})

				}
				if (responseBeneficios.success === false) {
					Swal.fire({
						position: 'bottom-end',
						icon: 'error',
						title: responseBeneficios.messages,
						showConfirmButton: false,
						timer: 4000
					})
				}
				$('#data_tablesolicitacoesSuporte').DataTable().ajax.reload(null, false).draw(false);




			}
		});
	};

	function adicionarEscopo() {

		var form = $('#add-formprojetosEscopo');
		$.ajax({
			url: '<?php echo base_url('projetos/addEscopo') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(responseAddEscopo) {

				if (responseAddEscopo.success === true) {

					Swal.fire({
						position: 'bottom-end',
						icon: 'success',
						title: responseAddEscopo.messages,
						showConfirmButton: false,
						timer: 2500
					})

				}
				if (responseAddEscopo.success === false) {
					Swal.fire({
						position: 'bottom-end',
						icon: 'error',
						title: responseAddEscopo.messages,
						showConfirmButton: false,
						timer: 4000
					})
				}
				if (responseAddEscopo.codTipoEscopo === 1) {
					$('#data_tableEscopo').DataTable().ajax.reload(null, false).draw(false);
					$('#data_tableNaoEscopo').DataTable().ajax.reload(null, false).draw(false);
					$('#add-modalprojetosEscopo').modal('hide');
				} else {
					$('#data_tableEscopo').DataTable().ajax.reload(null, false).draw(false);
					$('#data_tableNaoEscopo').DataTable().ajax.reload(null, false).draw(false);
					$('#add-modalprojetosEscopo').modal('hide');

				}


			}
		});
	};


	function editarFase() {

		var form = $('#edit-formprojetosFase');
		$.ajax({
			url: '<?php echo base_url('projetos/editFase') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(responseAddFase) {

				if (responseAddFase.success === true) {

					Swal.fire({
						position: 'bottom-end',
						icon: 'success',
						title: responseAddFase.messages,
						showConfirmButton: false,
						timer: 2500
					})

				}
				if (responseAddFase.success === false) {
					Swal.fire({
						position: 'bottom-end',
						icon: 'error',
						title: responseAddFase.messages,
						showConfirmButton: false,
						timer: 4000
					})
				}
				$('#data_tableprojetosFase').DataTable().ajax.reload(null, false).draw(false);
				$('#edit-modalprojetosFase').modal('hide');



			}
		});
	};

	function adicionarFase() {

		var form = $('#add-formprojetosFase');
		$.ajax({
			url: '<?php echo base_url('projetos/addFase') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(responseAddFase) {

				if (responseAddFase.success === true) {

					Swal.fire({
						position: 'bottom-end',
						icon: 'success',
						title: responseAddFase.messages,
						showConfirmButton: false,
						timer: 2500
					})

				}
				if (responseAddFase.success === false) {
					Swal.fire({
						position: 'bottom-end',
						icon: 'error',
						title: responseAddFase.messages,
						showConfirmButton: false,
						timer: 4000
					})
				}
				$('#data_tableprojetosFase').DataTable().ajax.reload(null, false).draw(false);
				$('#add-modalprojetosFase').modal('hide');



			}
		});
	};


	function salvarObjetivo() {

		var form = $('#objetivo-form');
		$.ajax({
			url: '<?php echo base_url('projetos/salvaObjetivo') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(responseObjetivo) {

				if (responseObjetivo.success === true) {

					Swal.fire({
						position: 'bottom-end',
						icon: 'success',
						title: responseObjetivo.messages,
						showConfirmButton: false,
						timer: 2500
					})

				}
				if (responseObjetivo.success === false) {
					Swal.fire({
						position: 'bottom-end',
						icon: 'error',
						title: responseObjetivo.messages,
						showConfirmButton: false,
						timer: 4000
					})
				}
				$('#data_tablesolicitacoesSuporte').DataTable().ajax.reload(null, false).draw(false);




			}
		});
	};


	function adicionarMembro() {

		var form = $('#add-formprojetosMembros');
		$.ajax({
			url: '<?php echo base_url('projetos/adicionarMembro') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(responseJustificativa) {

				if (responseJustificativa.success === true) {

					Swal.fire({
						position: 'bottom-end',
						icon: 'success',
						title: responseJustificativa.messages,
						showConfirmButton: false,
						timer: 2500
					})

				}
				if (responseJustificativa.success === false) {
					Swal.fire({
						position: 'bottom-end',
						icon: 'error',
						title: responseJustificativa.messages,
						showConfirmButton: false,
						timer: 4000
					})
				}
				$('#add-modalprojetosMembros').modal('hide');

				$('#data_tableprojetosMembros').DataTable().ajax.reload(null, false).draw(false);




			}
		});
	};


	function salvarJustificativa() {

		var form = $('#justificativa-form');
		$.ajax({
			url: '<?php echo base_url('projetos/salvaJustificativa') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(responseJustificativa) {

				if (responseJustificativa.success === true) {

					Swal.fire({
						position: 'bottom-end',
						icon: 'success',
						title: responseJustificativa.messages,
						showConfirmButton: false,
						timer: 2500
					})

				}
				if (responseJustificativa.success === false) {
					Swal.fire({
						position: 'bottom-end',
						icon: 'error',
						title: responseJustificativa.messages,
						showConfirmButton: false,
						timer: 4000
					})
				}
				$('#data_tablesolicitacoesSuporte').DataTable().ajax.reload(null, false).draw(false);




			}
		});
	};


	function showAdicionarMembro() {
		// reset the form 
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#add-modalprojetosMembros').modal('show');
		$("#add-modalprojetosMembros").css("z-index", "1500");



		$.ajax({
			url: '<?php echo base_url('Pessoas/listaDropDownPessoas') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(membros) {

				$("#codMembro").select2({
					data: membros,
					dropdownParent: $('#add-modalprojetosMembros')
				})

				$('#codMembro').val(null); // Select the option with a value of '1'
				$('#codMembro').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})


		$.ajax({
			url: '<?php echo base_url('projetos/listaDropDownTipoMembros') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(tipoMembros) {

				$("#codTipoMembro").select2({
					data: tipoMembros,
					dropdownParent: $('#add-modalprojetosMembros')
				})

				$('#codTipoMembro').val(null); // Select the option with a value of '1'
				$('#codTipoMembro').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})



	}

	function showAdicionarEscopo() {
		// reset the form 
		$("#add-formprojetosEscopo")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#add-modalprojetosEscopo').modal('show');
		$("#add-modalprojetosEscopo").css("z-index", "1500");
		$("#add-formprojetosEscopo #codTipoEscopo").val(1);


	}


	function editprojetosFase(codProjetoFase) {
		// reset the form 

		$.ajax({
			url: '<?php echo base_url('projetos/getOneFase') ?>',
			type: 'post',
			data: {
				codProjetoFase: codProjetoFase,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(responseProjetoFase) {
				// reset the form 
				$("#edit-formprojetosFase")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#edit-modalprojetosFase').modal('show');
				$("#edit-modalprojetosFase").css("z-index", "1500");


				$("#edit-formprojetosFase #codProjetoFaseEdit").val(responseProjetoFase.codProjetoFase);
				$("#edit-formprojetosFase #descricaoFaseEdit").val(responseProjetoFase.descricaoFase);
				$("#edit-formprojetosFase #dataInicialEdit").val(responseProjetoFase.dataInicial);
				$("#edit-formprojetosFase #dataEncerramentoEdit").val(responseProjetoFase.dataEncerramento);
			}
		})





	}

	function showAdicionarFase() {
		// reset the form 
		$("#add-formprojetosFase")[0].reset();
		$("#add-formprojetosFase #codProjetoFaseAdd").val(codProjetoTmp);

		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#add-modalprojetosFase').modal('show');
		$("#add-modalprojetosFase").css("z-index", "1500");


	}

	function showAdicionarNaoEscopo() {
		// reset the form 
		$("#add-formprojetosEscopo")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#add-modalprojetosEscopo').modal('show');
		$("#add-modalprojetosEscopo").css("z-index", "1500");
		$("#add-formprojetosEscopo #codTipoEscopo").val(0);




	}

	function add() {
		// reset the form 
		$("#add-form")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#add-modal').modal('show');




		$.ajax({
			url: '<?php echo base_url('departamentos/listaDropDown') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(departamentos) {
				$("#codDepartamentoAdd").select2({
					data: departamentos,
				})

				$('#codDepartamentoAdd').val(null); // Select the option with a value of '1'
				$('#codDepartamentoAdd').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})

		$.ajax({
			url: '<?php echo base_url('statusProjetos/listaDropDown') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(statusProjeto) {

				$("#codStatusProjetoAdd").select2({
					data: statusProjeto,
				})

				$('#codStatusProjetoAdd').val(null); // Select the option with a value of '1'
				$('#codStatusProjetoAdd').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})


		$.ajax({
			url: '<?php echo base_url('TiposProjetos/listaDropDown') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(TipoProjeto) {

				$("#codTipoProjetoAdd").select2({
					data: TipoProjeto,
				})

				$('#codTipoProjetoAdd').val(null); // Select the option with a value of '1'
				$('#codTipoProjetoAdd').trigger('change');
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

				var form = $('#add-form');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url($controller . '/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#add-form-btn').html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function(response) {

						if (response.success === true) {

							Swal.fire({
								position: 'bottom-end',
								icon: 'success',
								title: response.messages,
								showConfirmButton: false,
								timer: 1500
							}).then(function() {
								$('#data_table').DataTable().ajax.reload(null, false).draw(false);
								$('#add-modal').modal('hide');
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
						$('#add-form-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#add-form').validate();
	}

	var codProjetoTmp = null;

	function edit(codProjeto) {

		codProjetoTmp = codProjeto;
		$.ajax({
			url: '<?php echo base_url($controller . '/getOne') ?>',
			type: 'post',
			data: {
				codProjeto: codProjeto,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#edit-form")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#edit-modal').modal('show');

				$("#edit-form #codProjeto").val(response.codProjeto);
				$("#edit-form #descricaoProjeto").val(response.descricaoProjeto);
				$("#edit-form #dataInicioProjetoEdit").val(response.dataInicioProjeto);
				$("#edit-form #dataEncerramentoProjetoEdit").val(response.dataEncerramentoProjeto);


				document.getElementById("modalLabelProjeto").innerHTML = response.descricaoProjeto;

				document.getElementById("descricaoProjetoInfo").innerHTML = response.descricaoProjeto;
				document.getElementById("dataInicioInfo").innerHTML = dataAtualFormatada(response.dataInicioProjeto);
				document.getElementById("dataTerminoInfo").innerHTML = dataAtualFormatada(response.dataEncerramentoProjeto);
				if (response.nomeGestor == null || response.nomeGestor == 'undefined') {
					document.getElementById("gestorInfo").innerHTML = '<small class="badge badge-danger"><i class="far fa-user"></i> Não definido</small>';

				} else {
					document.getElementById("gestorInfo").innerHTML = response.nomeGestor;

				}
				document.getElementById("descricaoStatusProjetoInfo").innerHTML = response.descricaoStatusProjeto;


				$("#add-formprojetosEscopo #codProjetoAddEscopo").val(response.codProjeto);

				$("#add-formprojetosFase #codProjetoFaseAdd").val(response.codProjeto);

				$("#add-formprojetosMembros #codProjetoAdicionarMembro").val(codProjetoTmp);



				function dataAtualFormatada() {
					var data = new Date(),
						dia = data.getDate().toString().padStart(2, '0'),
						mes = (data.getMonth() + 1).toString().padStart(2, '0'), //+1 pois no getMonth Janeiro começa com zero.
						ano = data.getFullYear();
					return dia + "/" + mes + "/" + ano;
				}

				$.ajax({
					url: '<?php echo base_url('departamentos/listaDropDown') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(departamentos) {
						$("#codDepartamentoEdit").select2({
							data: departamentos,
						})

						$('#codDepartamentoEdit').val(response.codDepartamento); // Select the option with a value of '1'
						$('#codDepartamentoEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

					}
				})



				$.ajax({
					url: '<?php echo base_url('statusProjetos/listaDropDown') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(statusProjeto) {

						$("#codStatusProjetoEdit").select2({
							data: statusProjeto,
						})

						$('#codStatusProjetoEdit').val(response.codStatusProjeto); // Select the option with a value of '1'
						$('#codStatusProjetoEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

					}
				})


				$.ajax({
					url: '<?php echo base_url('TiposProjetos/listaDropDown') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(TipoProjeto) {

						$("#codTipoProjetoEdit").select2({
							data: TipoProjeto,
						})

						$('#codTipoProjetoEdit').val(response.codTipoProjeto); // Select the option with a value of '1'
						$('#codTipoProjetoEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

					}
				})





				$("#justificativaEdit").summernote('destroy');
				$("#justificativa-form #justificativaEdit").val(response.justificativa);
				$("#codProjetoJustificativa").val(response.codProjeto);


				$("#justificativaEdit").summernote({
					height: 400,
					maximumImageFileSize: 1024 * 1024, // 1Mb
					fontSizes: ['8', '9', '10', '11', '12', '14', '18', '20', '36', '72'],
					lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
					toolbar: [
						['style', ['style']],
						['fontname', ['fontname']],
						['font', ['color', 'strikethrough', 'superscript', 'subscript', 'bold', 'underline', 'clear']],
						['fontsize', ['fontsize']],
						['height', ['height']],
						['para', ['ul', 'ol', 'paragraph']],
						['table', ['table']],
						['insert', ['link', 'hr']],
						['view', ['fullscreen', 'codeview', 'help']],
						['redo'],
						['undo'],
					],

				})



				$("#objetivoEdit").summernote('destroy');
				$("#objetivo-form #objetivoEdit").val(response.objetivo);
				$("#codProjetoobjetivo").val(response.codProjeto);

				$("#objetivoEdit").summernote({
					height: 400,
					maximumImageFileSize: 1024 * 1024, // 1Mb
					fontSizes: ['8', '9', '10', '11', '12', '14', '18', '20', '36', '72'],
					lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
					toolbar: [
						['style', ['style']],
						['fontname', ['fontname']],
						['font', ['color', 'strikethrough', 'superscript', 'subscript', 'bold', 'underline', 'clear']],
						['fontsize', ['fontsize']],
						['height', ['height']],
						['para', ['ul', 'ol', 'paragraph']],
						['table', ['table']],
						['insert', ['link', 'hr']],
						['view', ['fullscreen', 'codeview', 'help']],
						['redo'],
						['undo'],
					],

				})



				$("#beneficiosEdit").summernote('destroy');
				$("#beneficios-form #beneficiosEdit").val(response.beneficios);
				$("#codProjetobeneficios").val(response.codProjeto);

				$("#beneficiosEdit").summernote({
					height: 400,
					maximumImageFileSize: 1024 * 1024, // 1Mb
					fontSizes: ['8', '9', '10', '11', '12', '14', '18', '20', '36', '72'],
					lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
					toolbar: [
						['style', ['style']],
						['fontname', ['fontname']],
						['font', ['color', 'strikethrough', 'superscript', 'subscript', 'bold', 'underline', 'clear']],
						['fontsize', ['fontsize']],
						['height', ['height']],
						['para', ['ul', 'ol', 'paragraph']],
						['table', ['table']],
						['insert', ['link', 'hr']],
						['view', ['fullscreen', 'codeview', 'help']],
						['redo'],
						['undo'],
					],

				})



				//TABELA ESCOPO

				$(function() {
					$('#data_tableEscopo').DataTable({
						"bDestroy": true,
						"paging": true,
						"lengthChange": false,
						"searching": true,
						"ordering": true,
						"info": true,
						"autoWidth": false,
						"responsive": true,
						"ajax": {
							"url": '<?php echo base_url($controller . '/listaEscopo') ?>',
							"type": "POST",
							"dataType": "json",
							async: "true",
							data: {
								codProjeto: codProjeto,
								csrf_sandra: $("#csrf_sandraPrincipal").val(),
							},
						}
					});


				});



				//TABELA NAO ESCOPO

				$(function() {
					$('#data_tableNaoEscopo').DataTable({
						"bDestroy": true,
						"paging": true,
						"lengthChange": false,
						"searching": true,
						"ordering": true,
						"info": true,
						"autoWidth": false,
						"responsive": true,
						"scrollY": "200px",
						"scrollCollapse": true,
						"ajax": {
							"url": '<?php echo base_url($controller . '/listaNaoEscopo') ?>',
							"type": "POST",
							"dataType": "json",
							async: "true",
							data: {
								codProjeto: codProjeto,
								csrf_sandra: $("#csrf_sandraPrincipal").val(),
							},
						}
					});


				});


				$('#data_tableprojetosMembros').DataTable({
					"bDestroy": true,
					"paging": true,
					"lengthChange": false,
					"searching": true,
					"ordering": true,
					"info": true,
					"autoWidth": false,
					"responsive": true,
					"scrollY": "200px",
					"scrollCollapse": true,
					"ajax": {
						"url": '<?php echo base_url($controller . '/membrosProjeto') ?>',
						"type": "POST",
						"dataType": "json",
						async: "true",
						data: {
							codProjeto: codProjeto,
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},
					}
				});

				$('#data_tableprojetosFase').DataTable({
					"bDestroy": true,
					"paging": true,
					"lengthChange": false,
					"searching": true,
					"ordering": true,
					"info": true,
					"autoWidth": false,
					"responsive": true,
					"scrollY": "200px",
					"scrollCollapse": true,
					"ajax": {
						"url": '<?php echo base_url($controller . '/fasesProjeto') ?>',
						"type": "POST",
						"dataType": "json",
						async: "true",
						data: {
							codProjeto: codProjeto,
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},
					}
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
						var form = $('#edit-form');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url($controller . '/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#edit-form-btn').html('<i class="fa fa-spinner fa-spin"></i>');

							},
							success: function(response) {

								if (response.success === true) {

									Swal.fire({
										position: 'bottom-end',
										icon: 'success',
										title: response.messages,
										showConfirmButton: false,
										timer: 1500
									}).then(function() {
										$('#data_table').DataTable().ajax.reload(null, false).draw(false);
										//$('#edit-modal').modal('hide');
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
								$('#edit-form-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#edit-form').validate();

			}
		});
	}


	function removeprojetosEscopo(codProjetoEscopo) {
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
					url: '<?php echo base_url('projetosEscopo/remove') ?>',
					type: 'post',
					data: {
						codProjetoEscopo: codProjetoEscopo,
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


								$('#data_tableEscopo').DataTable().ajax.reload(null, false).draw(false);
								$('#data_tableNaoEscopo').DataTable().ajax.reload(null, false).draw(false);
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


	function remove(codProjeto) {
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
					url: '<?php echo base_url($controller . '/remove') ?>',
					type: 'post',
					data: {
						codProjeto: codProjeto,
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
								$('#data_table').DataTable().ajax.reload(null, false).draw(false);
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

	function removeprojetosMembros(codProjetoMembro) {
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
					url: '<?php echo base_url('projetos/removeMembro') ?>',
					type: 'post',
					data: {
						codProjetoMembro: codProjetoMembro,
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
								$('#data_tableprojetosMembros').DataTable().ajax.reload(null, false).draw(false);
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

	function removeprojetosFase(codProjetoFase) {
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
					url: '<?php echo base_url('projetosFase/remove') ?>',
					type: 'post',
					data: {
						codProjetoFase: codProjetoFase,
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
								$('#data_tableprojetosFase').DataTable().ajax.reload(null, false).draw(false);
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