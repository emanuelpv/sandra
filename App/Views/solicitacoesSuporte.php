<?php
//É NECESSÁRIO EM TODAS AS VIEWS

$codOrganizacao = session()->codOrganizacao;
$codPessoa = session()->codPessoa;
$euMesmo = session()->codPessoa;
$meuDepartamento = session()->codDepartamento;
$equipesTecnicas = session()->equipesTecnicas;

//PREFERENCIAS DA PESSOA LOGADA
if ($preferencia->categoriasSolicitacoes !== NULL) {
	$categoriasSolicitacoes = $preferencia->categoriasSolicitacoes;
} else {
	$categoriasSolicitacoes = '""';
}


if ($preferencia->statusSolicitacoes !== NULL) {
	$statusSolicitacoes = $preferencia->statusSolicitacoes;
} else {
	$statusSolicitacoes = '""';
}

if ($preferencia->periodoSolicitacoes !== NULL) {
	$periodoSolicitacoes = $preferencia->periodoSolicitacoes;
} else {
	$periodoSolicitacoes = '""';
}

if ($preferencia->codDepartamento !== NULL) {
	$codDepartamento = $preferencia->codDepartamento;
} else {
	$codDepartamento = '""';
}

if ($preferencia->codSolicitante !== NULL) {
	$codSolicitante = $preferencia->codSolicitante;
} else {
	$codSolicitante = '""';
}

if ($preferencia->codResponsavel !== NULL) {
	$codResponsavel = $preferencia->codResponsavel;
} else {
	$codResponsavel = '""';
}


if (count($equipesTecnicas) > 0) {
	$pertenceEquipeTecnica = 1;
} else {
	$pertenceEquipeTecnica = 0;
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
<link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/summernote/summernote-bs4.min.css">


<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-md-8 mt-2">
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Solicitações de Suporte</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary" onclick="addsolicitacoesSuporte()" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
						</div>

					</div>



					<div id="accordion">
						<div class="card card-muted">
							<div class="card-header">
								<h4 class="card-title w-100">
									<a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapseOne" aria-expanded="false">
										Preferências de Filtro
									</a>
								</h4>
							</div>
							<div id="collapseOne" class="collapse" data-parent="#accordion">
								<div class="card-body col-md-12">


									<form id="formPreferenciaFiltro" class="pl-3 pr-3">
										<div class="row">
											<input type="hidden" id="<?php echo csrf_token() ?>formPreferenciaFiltro" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

											<input type="hidden" id="codPessoaPreferenciaFiltro" name="codPessoa" class="form-control" placeholder="Código" value="<?php echo session()->codPessoa ?>" maxlength="11" required>

										</div>
										<style>
											.select2-container--default .select2-selection--multiple .select2-selection__choice {
												background-color: #007bff;
											}
										</style>

										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label>Categoria</label>
													<select id="codCategoriaSuporteFiltro" name="arrayCategoria[]" class="select2" multiple="multiple" data-placeholder="Selecione uma opção" style="width: 100%;">
														<option></option>
													</select>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label>Status</label>
													<select id="codStatusSolicitacaoFiltro" name="arrayStatus[]" class="select2" multiple="multiple" data-placeholder="Selecione uma opção" style="width: 100%;">
														<option></option>
													</select>
												</div>
											</div>

											<div class="col-md-4">
												<div class="form-group">
													<label>Período</label>
													<select id="periodoSolicitacoes" name="periodoSolicitacoes" class="select2" required>
														<option value=0>Todos</option>
														<option value=7>7 dias</option>
														<option value=30>30 dias</option>
														<option value=60>60 dias</option>
														<option value=90>90 dias</option>
													</select>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label>Solicitante</label>
													<select id="codSolicitanteFiltro" name="arrayCodSolicitante[]" class="select2" multiple="multiple" data-placeholder="Selecione uma opção" style="width: 100%;">
														<option value=""></option>
													</select>
												</div>
											</div>

											<div class="col-md-4">
												<div class="form-group">
													<label>Técnico Responsável</label>
													<select id="codResponsavelFiltro" name="codResponsavel" class="select2" data-placeholder="Selecione uma opção" style="width: 100%;">
														<option value=""></option>
													</select>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label>Departamento</label>
													<select id="codDepartamentoFiltro" name="arrayCodDepartamento[]" class="select2" multiple="multiple" data-placeholder="Selecione uma opção" style="width: 100%;">
														<option value=""></option>
													</select>
												</div>
											</div>





										</div>
										<div class="form-group text-center">
											<button onclick="salvarPreferencia()" type="button" class="btn btn-xs btn-success">SALVAR PREFERÊNCIA</button>
											<button onclick="limparPreferencia()" type="button" class="btn btn-xs btn-primary">LIMPAR PREFERÊNCIA</button>
										</div>

									</form>
								</div>
							</div>
						</div>
					</div>

				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<table id="data_tablesolicitacoesSuporte" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th style="width:10px !important">Código</th>
								<th style="width:10px !important">Descrição </th>
								<th>Categoria Suporte</th>
								<th>Solicitante</th>
								<th>Departamento</th>
								<th>Técnico</th>
								<th>Data</th>
								<th>SLA</th>
								<th>Tempo</th>
								<th>Status</th>
								<th>Tipo</th>
								<th>Percentual conclusão</th>
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
<div id="add-modalsolicitacoesSuporte" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Solicitações de Suporte</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="add-formsolicitacoesSuporte" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>add-formsolicitacoesSuporte" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codSolicitacao" name="codSolicitacao" class="form-control" placeholder="Código" maxlength="11" required>

					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="descricaoSolicitacao"> Descrição : <span class="text-danger">*</span> </label>
								<textarea cols="40" rows="5" id="descricaoSolicitacao" name="descricaoSolicitacao" class="form-control" placeholder="Descrição " required></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codSolicitante"> Solicitante: <span class="text-danger">*</span> </label>
								<select id="codSolicitanteAdd" name="codSolicitante" class="custom-select" required>
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codOrigemSolicitacao"> Origem Solicitação: <span class="text-danger">*</span> </label>
								<select id="codOrigemSolicitacaoAdd" name="codOrigemSolicitacao" class="custom-select" required>
									<option value=""></option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="codCategoriaSuporte"> Categoria: <span class="text-danger">*</span> </label>
								<select id="codCategoriaSuporteAdd" name="codCategoriaSuporte" class="custom-select" required>
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="codTipoSolicitacao"> Tipo Solicitação: <span class="text-danger">*</span> </label>
								<select id="codTipoSolicitacaoAdd" name="codTipoSolicitacao" class="custom-select" required>
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="codUrgencia"> Urgência: <span class="text-danger">*</span> </label>
								<select id="codUrgenciaAdd" name="codUrgencia" class="custom-select" required>
								</select>
							</div>
						</div>

						<div class="col-md-2">
							<div class="form-group">
								<label for="codPrioridade"> Prioridade: <span class="text-danger">*</span> </label>
								<select id="codPrioridadeAdd" name="codPrioridade" class="custom-select" required>
								</select>
							</div>
						</div>

					</div>
					<div class="row">
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-xs btn-primary" id="add-formsolicitacoesSuporte-btn">Adicionar</button>
							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->





<!-- Add modal content -->
<div id="avaliacaoAtendimento-modal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Como você foi atendido?</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">


				<form id="avaliacaoAtendimento-form" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>avaliacaoAtendimento-modal" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codSolicitacaoAvaliacao" name="codSolicitacao" class="form-control" placeholder="Código" maxlength="11" required>
						<input type="hidden" id="codResponsavelAvaliacao" name="codResponsavel" class="form-control" placeholder="Código" maxlength="11" required>
						<input type="hidden" id="valorlAvaliacao" name="valorlAvaliacao" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">

						<div class="card-body">

							<span> Técnico Avaliado é: </span><span style="font-size:20px;font-weight:bold" id="tecnicoAvaliado"></span>


							<div class="row">
								<?php

								$notaAtendimento = '<div>';

								for ($x = 1; $x <= 5; $x++) {
									if ($x == 1) {
										$classificacao = "Muito Ruim";
									}
									if ($x == 2) {
										$classificacao = "Ruim";
									}
									if ($x == 3) {
										$classificacao = "Bom";
									}
									if ($x == 4) {
										$classificacao = "Muito Bom";
									}
									if ($x == 5) {
										$classificacao = "Ótimo";
									}
									$notaAtendimento .= '<span><img id="estrela' . $x . '" onclick="selecionarScore(' . $x . ')" style="width:30px" data-toggle="tooltip" data-placement="top" title="' . $classificacao . '" src="' . base_url() . '/imagens/estrelaCinza.png"></span>';
								}
								$notaAtendimento .= '</div>';
								echo $notaAtendimento;
								?>

							</div>


						</div>



					</div>


					<div class="form-group text-center">
						<div class="btn-group">
							<button onclick="salvarAvaliacao()" class="btn btn-xs btn-primary" id="btnSalvarAvaliacao">Salvar Avaliação</button>
						</div>
					</div>
				</form>




			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<!-- Add modal content -->
<div id="edit-modalsolicitacoesSuporte" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel"><span>Solicitação nº </span><span id=codSolicitacaoInfo></span></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>


			<div class="modal-body">

				<div class="row">

					<div class="col-lg-6">

						<div class="row">
							<div class="col-md-12">

								<div class="card card-primary">
									<div class="card-header">
										<h3 class="card-title">VIDEOCHAMADA</h3>
									</div>

									<!-- /.card-header -->
									<div class="card-body">



									</div>


								</div>

								<div class="card card-primary">
									<div class="card-header">
										<h3 class="card-title">HISTÓRICO DE AÇÕES</h3>
									</div>

									<!-- /.card-header -->
									<div class="card-body">

										<div id="mensagensAcoes">
											<div id="content">

											</div>
										</div>
										<!--/.direct-chat-messages-->

									</div>
									<div id="envidorAcao" class="card-footer">
										<form id="add-formAcaoSuporte" class="pl-3 pr-3">
											<div class="input-group">
												<input type="hidden" id="<?php echo csrf_token() ?>add-formAcaoSuporte" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

												<input type="hidden" id="codSolicitacaoAcao" name="codSolicitacao" class="form-control" placeholder="Código" maxlength="11" required>

												<input type="text" id="descricaoAcao" name="descricaoAcao" placeholder="Escreva sua mensagem..." autocomplete="off" class="form-control">
												<span class="input-group-append">
													<button onclick="salvarAcao()" type="button" class="btn btn-xs btn-primary">ENVIAR</button>
												</span>
											</div>
										</form>
									</div>


								</div>
							</div>


						</div>

					</div>

					<div class="col-lg-6">

						<div class="card card-primary">
							<div class="card-header">
								<h3 class="card-title">DADOS DA SOLICITAÇÃO</h3>
							</div>

							<!-- /.card-header -->
							<div class="card-body">

								<form id="edit-formsolicitacoesSuporte" class="pl-3 pr-3">
									<div class="row">
										<input type="hidden" id="<?php echo csrf_token() ?>edit-formsolicitacoesSuporte" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

										<input type="hidden" id="codSolicitacao" name="codSolicitacao" class="form-control" placeholder="Código" maxlength="11" required>
									</div>
									<div class="row">

										<div class="card-body">
											<blockquote>

												<div id="descricaoSolicitacaoReadOnly">
												</div>
												<div><small>Solcitante:<cite id="nomeSolicitante"></cite> (<cite id="departamentoInfo"></cite> )</small></div>
												<div><small>Data/Hora:<cite id="datahora"></cite></small></div>
											</blockquote>




											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<label for="codCategoriaSuporte"> Categoria: <span class="text-danger">*</span> </label>
														<select id="codCategoriaSuporteEdit" name="codCategoriaSuporte" class="custom-select" required>
															<option value=""></option>
														</select>
													</div>
												</div>
											</div>
											<div class="row">

												<div class="col-md-4">
													<div class="form-group">
														<label for="codTipoSolicitacao"> Tipo Solicitação: <span class="text-danger">*</span> </label>
														<select id="codTipoSolicitacaoEdit" name="codTipoSolicitacao" class="custom-select" required>
															<option value=""></option>
														</select>
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label for="codUrgencia"> Urgência: <span class="text-danger">*</span> </label>
														<select id="codUrgenciaEdit" name="codUrgencia" class="custom-select" required>
														</select>
													</div>
												</div>

												<div class="col-md-4">
													<div class="form-group">
														<label for="codPrioridade"> Prioridade: <span class="text-danger">*</span> </label>
														<select id="codPrioridadeEdit" name="codPrioridade" class="custom-select" required>
														</select>
													</div>
												</div>

											</div>


											<div class="row">

												<div class="col-md-12">
													<div class="form-group">
														<label for="codResponsavel"> Responsável Técnico: <span class="text-danger">*</span> </label>
														<select id="codResponsavelEdit" name="codResponsavel" class="custom-select" required>
															<option value=""></option>
														</select>
													</div>
												</div>
											</div>
											<div class="row">

												<div class="col-md-12">
													<div class="form-group">
														<label for="codStatusSolicitacao"> Status: <span class="text-danger">*</span> </label>
														<select id="codStatusSolicitacaoEdit" name="codStatusSolicitacao" class="custom-select" required>
															<option value=""></option>
														</select>
													</div>
												</div>
											</div>
											<div class="row">

												<div class="col-md-6">
													<div class="form-group">
														<label for="percentualConclusao"> % Conclusão: <span class="text-danger">*</span> </label>
														<select id="percentualConclusaoEdit" name="percentualConclusao" class="custom-select" required>
															<option value=""></option>
														</select>
													</div>
												</div>


											</div>


										</div>



									</div>

									<div class="row">


									</div>


									<div class="form-group text-center">
										<div class="btn-group">
											<button id="btnReabrir" onclick="reabrirSolicitacao()" type="button" class="btn btn-xs btn-danger">REABRIR</button>
											<button type="submit" class="btn btn-xs btn-primary" id="btnSalvarSolicitacao">SALVAR</button>
											<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">FECHAR</button>
										</div>
									</div>
								</form>

							</div>
						</div>


					</div>



				</div>


			</div>
		</div>
	</div>
</div>

<?php
echo view('tema/rodape');

?>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/summernote/summernote-bs4.min.js"></script>


<script>
	var pertenceEquipeTecnica = "<?php echo "$pertenceEquipeTecnica" ?>";
	var periodoSolicitacoes = "<?php echo "$preferencia->periodoSolicitacoes" ?>";
	var categoriasSolicitacoes = <?php echo "$categoriasSolicitacoes" ?>;
	var statusSolicitacoes = <?php echo "$statusSolicitacoes" ?>;
	var codSolicitante = <?php echo "$codSolicitante" ?>;
	var codResponsavel = <?php echo "$codResponsavel" ?>;
	var codDepartamento = <?php echo "$codDepartamento" ?>;
	var meuDepartamento = <?php echo "$meuDepartamento" ?>;
	var euMesmo = <?php echo "$euMesmo" ?>;



	function limparPreferencia() {

		$.ajax({
			url: '<?php echo base_url('SolicitacoesSuporte/listaDropDownCategoriasSuporte') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(categoriasFiltro) {
				$("#codCategoriaSuporteFiltro").select2({
					data: categoriasFiltro,
				})

				$('#codCategoriaSuporteFiltro').val(null); // Select the option with a value of '1'
				$('#codCategoriaSuporteFiltro').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})




		$.ajax({
			url: '<?php echo base_url('SolicitacoesSuporte/listaDropDownStatusSuporte') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(status) {

				$("#codStatusSolicitacaoFiltro").select2({
					data: status,
				})

				$('#codStatusSolicitacaoFiltro').val(null); // Select the option with a value of '1'
				$('#codStatusSolicitacaoFiltro').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})



		$.ajax({
			url: '<?php echo base_url('SolicitacoesSuporte/listaDropDownSolicitante') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(pessoasFiltro) {

				$("#codSolicitanteFiltro").select2({
					data: pessoasFiltro,
				})

				$('#codSolicitanteFiltro').val(euMesmo); // Select the option with a value of '1'
				$('#codSolicitanteFiltro').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})

		$.ajax({
			url: '<?php echo base_url('SolicitacoesSuporte/listaDropDownResponsaveis') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(responsavelFiltro) {

				$("#codResponsavelFiltro").select2({
					data: responsavelFiltro,
				})

				$('#codResponsavelFiltro').val(null); // Select the option with a value of '1'
				$('#codResponsavelFiltro').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})

		$.ajax({
			url: '<?php echo base_url('SolicitacoesSuporte/listaDropDownDepartamentos') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(departamentoFiltroClear) {

				$("#codDepartamentoFiltro").select2({
					data: departamentoFiltroClear,
				})

				$('#codDepartamentoFiltro').val(meuDepartamento); // Select the option with a value of '1'
				$('#codDepartamentoFiltro').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})



		$('#periodoSolicitacoes').val(0); // Select the option with a value of '1'



	}


	$(function() {

		avisoPesquisa('Suporte',2);



		$('#data_tablesolicitacoesSuporte').DataTable({
			"bDestroy": true,
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"order": [
				[0, "asc"]
			],
			createdRow: function(row, data, dataIndex) {
				if (data[9].trim() == 'Reaberto') {
					$(row).css({
						"background-color": "#f3a40dc9",
						"color": "#fff"
					});
					$(row).addClass('sub-needed');
				}
				if (data[9].trim() == 'Em atendimento') {
					$(row).css({
						"background-color": "#28a74580",
						"color": "#fff"
					});
					$(row).addClass('sub-needed');
				}

			},
			"ajax": {
				"url": '<?php echo base_url('solicitacoesSuporte/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});
	});


	$(document).ready(function() {

	});


	$.ajax({
		url: '<?php echo base_url('solicitacoesSuporte/listaDropDownCategoriasSuporte') ?>',
		type: 'post',
		dataType: 'json',
		data: {
			csrf_sandra: $("#csrf_sandraPrincipal").val(),
		},
		success: function(categoriasFiltro) {
			$("#codCategoriaSuporteFiltro").select2({
				data: categoriasFiltro,
			})

			$('#codCategoriaSuporteFiltro').val(categoriasSolicitacoes); // Select the option with a value of '1'
			$('#codCategoriaSuporteFiltro').trigger('change');
			$(document).on('select2:open', () => {
				document.querySelector('.select2-search__field').focus();
			});

		}
	})




	$.ajax({
		url: '<?php echo base_url('SolicitacoesSuporte/listaDropDownStatusSuporte') ?>',
		type: 'post',
		dataType: 'json',
		data: {
			csrf_sandra: $("#csrf_sandraPrincipal").val(),
		},
		success: function(statusFiltro) {

			$("#codStatusSolicitacaoFiltro").select2({
				data: statusFiltro,
			})

			$('#codStatusSolicitacaoFiltro').val(statusSolicitacoes); // Select the option with a value of '1'
			$('#codStatusSolicitacaoFiltro').trigger('change');
			$(document).on('select2:open', () => {
				document.querySelector('.select2-search__field').focus();
			});

		}
	})


	$.ajax({
		url: '<?php echo base_url('SolicitacoesSuporte/listaDropDownSolicitante') ?>',
		type: 'post',
		dataType: 'json',
		data: {
			csrf_sandra: $("#csrf_sandraPrincipal").val(),
		},
		success: function(pessoasFiltro) {

			$("#codSolicitanteFiltro").select2({
				data: pessoasFiltro,
			})

			$('#codSolicitanteFiltro').val(codSolicitante); // Select the option with a value of '1'
			$('#codSolicitanteFiltro').trigger('change');
			$(document).on('select2:open', () => {
				document.querySelector('.select2-search__field').focus();
			});

		}
	})



	$.ajax({
		url: '<?php echo base_url('SolicitacoesSuporte/listaDropDownResponsaveis') ?>',
		type: 'post',
		dataType: 'json',
		data: {
			csrf_sandra: $("#csrf_sandraPrincipal").val(),
		},
		success: function(responsavelFiltro) {

			$("#codResponsavelFiltro").select2({
				data: responsavelFiltro,
			})

			$('#codResponsavelFiltro').val(codResponsavel); // Select the option with a value of '1'
			$('#codResponsavelFiltro').trigger('change');
			$(document).on('select2:open', () => {
				document.querySelector('.select2-search__field').focus();
			});

		}
	})


	$.ajax({
		url: '<?php echo base_url('SolicitacoesSuporte/listaDropDownDepartamentos') ?>',
		type: 'post',
		dataType: 'json',
		data: {
			csrf_sandra: $("#csrf_sandraPrincipal").val(),
		},
		success: function(departamentoFiltro) {

			$("#codDepartamentoFiltro").select2({
				data: departamentoFiltro,
			})

			$('#codDepartamentoFiltro').val(codDepartamento); // Select the option with a value of '1'
			$('#codDepartamentoFiltro').trigger('change');
			$(document).on('select2:open', () => {
				document.querySelector('.select2-search__field').focus();
			});

		}
	})


	$('#periodoSolicitacoes').val(periodoSolicitacoes); // Select the option with a value of '1'


	function reabrirSolicitacao() {

		var form = $('#add-formAcaoSuporte');

		$.ajax({
			url: '<?php echo base_url('solicitacoesSuporte/reabrirSolicitacao') ?>',
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
						timer: 1500
					})

				}
				if (response.success === false) {
					Swal.fire({
						position: 'bottom-end',
						icon: 'error',
						title: response.messages,
						showConfirmButton: false,
						timer: 4000
					})
				}
				$('#data_tablesolicitacoesSuporte').DataTable().ajax.reload(null, false).draw(false);

				$('#edit-modalsolicitacoesSuporte').modal('hide');



			}
		});
	};



	function salvarAcao() {

		var form = $('#add-formAcaoSuporte');

		var codSolicitacaoAcao = document.getElementById('codSolicitacaoAcao').value;
		$.ajax({
			url: '<?php echo base_url('solicitacoesSuporte/salvaAcao') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(response) {

				if (response.success === true) {

					fnMensagensAcoes(codSolicitacaoAcao)


				}
				if (response.success === false) {
					Swal.fire({
						position: 'bottom-end',
						icon: 'error',
						title: response.messages,
						showConfirmButton: false,
						timer: 4000
					})
				}
				$('#data_tablesolicitacoesSuporte').DataTable().ajax.reload(null, false).draw(false);

				//$('#edit-modalsolicitacoesSuporte').modal('hide');

				document.getElementById('descricaoAcao').value = null;


			}
		});
	};


	function salvarPreferencia() {

		var form = $('#formPreferenciaFiltro');
		$.ajax({
			url: '<?php echo base_url('solicitacoesSuporte/salvaPreferenciaSolicitacoes') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(responsePreferenciaFiltro) {

				if (responsePreferenciaFiltro.success === true) {

					Swal.fire({
						position: 'bottom-end',
						icon: 'success',
						title: responsePreferenciaFiltro.messages,
						showConfirmButton: false,
						timer: 2500
					})

				}
				if (responsePreferenciaFiltro.success === false) {
					Swal.fire({
						position: 'bottom-end',
						icon: 'error',
						title: responsePreferenciaFiltro.messages,
						showConfirmButton: false,
						timer: 4000
					})
				}
				$('#data_tablesolicitacoesSuporte').DataTable().ajax.reload(null, false).draw(false);




			}
		});
	};

	function addsolicitacoesSuporte() {
		// reset the form 

		$("#add-formsolicitacoesSuporte")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#add-modalsolicitacoesSuporte').modal('show');


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

				var form = $('#add-formsolicitacoesSuporte');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('solicitacoesSuporte/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#add-formsolicitacoesSuporte-btn').html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function(response) {

						if (response.success === true) {

							Swal.fire({
								position: 'bottom-end',
								icon: 'success',
								title: response.messages,
								showConfirmButton: false,
								timer: 2500
							}).then(function() {
								$('#data_tablesolicitacoesSuporte').DataTable().ajax.reload(null, false).draw(false);
								$('#add-modalsolicitacoesSuporte').modal('hide');

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
									timer: 2500
								})


							}
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

				return false;
			}
		});
		$('#add-formsolicitacoesSuporte').validate();


		$.ajax({
			url: '<?php echo base_url('solicitacoesSuporte/listaDropDownCategoriasSuporte') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(categorias) {
				$("#codCategoriaSuporteAdd").select2({
					data: categorias,
				})

			}
		})





		$.ajax({
			url: '<?php echo base_url('solicitacoesSuporte/listaDropDownTipoSolicitacao') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(tipoSolicitacao) {
				$("#codTipoSolicitacaoAdd").select2({
					data: tipoSolicitacao,
				})

			}
		})



		$.ajax({
			url: '<?php echo base_url('solicitacoesSuporte/listaDropDownClassificacaoUrgencia') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(urgencia) {
				$("#codUrgenciaAdd").select2({
					data: urgencia,
				})

			}
		})
		$.ajax({
			url: '<?php echo base_url('solicitacoesSuporte/listaDropDownClassificacaoPrioridade') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(prioridade) {
				$("#codPrioridadeAdd").select2({
					data: prioridade,
				})

			}
		})


		$.ajax({
			url: '<?php echo base_url('solicitacoesSuporte/listaDropDownOrigemSolicitacao') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(origemSolicitacao) {

				if (pertenceEquipeTecnica < 1) {
					$("#codOrigemSolicitacaoAdd").select2({
						data: origemSolicitacao,
						disabled: 'readonly',
					})
				} else {
					$("#codOrigemSolicitacaoAdd").select2({
						data: origemSolicitacao,
					})
				}



				$('#codOrigemSolicitacaoAdd').val(1); // Select the option with a value of '1'
				$('#codOrigemSolicitacaoAdd').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})


		var codPessoa = "<?php echo "$codPessoa" ?>";

		$.ajax({
			url: '<?php echo base_url('solicitacoesSuporte/listaDropDownSolicitante') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(solicitante) {

				if (pertenceEquipeTecnica < 1) {
					$("#codSolicitanteAdd").select2({
						data: solicitante,
						disabled: 'readonly',
					})
				} else {
					$("#codSolicitanteAdd").select2({
						data: solicitante,
					})
				}


				$('#codSolicitanteAdd').val(codPessoa); // Select the option with a value of '1'
				$('#codSolicitanteAdd').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})


		//ADD text editor
		$('#descricaoSolicitacao').summernote({
			height: 150,
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

		$('#descricaoSolicitacao').summernote('reset');

	}

	function selecionarScore(score) {


		if (score === 1) {
			document.getElementById("estrela1").src = "<?php echo base_url() . '/imagens/caveira3.png' ?>";
			document.getElementById("estrela2").src = "<?php echo base_url() . '/imagens/estrelaCinza.png' ?>";
			document.getElementById("estrela3").src = "<?php echo base_url() . '/imagens/estrelaCinza.png' ?>";
			document.getElementById("estrela4").src = "<?php echo base_url() . '/imagens/estrelaCinza.png' ?>";
			document.getElementById("estrela5").src = "<?php echo base_url() . '/imagens/estrelaCinza.png' ?>";
		}
		if (score === 2) {
			document.getElementById("estrela1").src = "<?php echo base_url() . '/imagens/caveira3.png' ?>";
			document.getElementById("estrela2").src = "<?php echo base_url() . '/imagens/caveira3.png' ?>";
			document.getElementById("estrela3").src = "<?php echo base_url() . '/imagens/estrelaCinza.png' ?>";
			document.getElementById("estrela4").src = "<?php echo base_url() . '/imagens/estrelaCinza.png' ?>";
			document.getElementById("estrela5").src = "<?php echo base_url() . '/imagens/estrelaCinza.png' ?>";
		}
		if (score === 3) {
			document.getElementById("estrela1").src = "<?php echo base_url() . '/imagens/estrelaDourada.png' ?>";
			document.getElementById("estrela2").src = "<?php echo base_url() . '/imagens/estrelaDourada.png' ?>";
			document.getElementById("estrela3").src = "<?php echo base_url() . '/imagens/estrelaDourada.png' ?>";
			document.getElementById("estrela4").src = "<?php echo base_url() . '/imagens/estrelaCinza.png' ?>";
			document.getElementById("estrela5").src = "<?php echo base_url() . '/imagens/estrelaCinza.png' ?>";
		}
		if (score === 4) {
			document.getElementById("estrela1").src = "<?php echo base_url() . '/imagens/estrelaDourada.png' ?>";
			document.getElementById("estrela2").src = "<?php echo base_url() . '/imagens/estrelaDourada.png' ?>";
			document.getElementById("estrela3").src = "<?php echo base_url() . '/imagens/estrelaDourada.png' ?>";
			document.getElementById("estrela4").src = "<?php echo base_url() . '/imagens/estrelaDourada.png' ?>";
			document.getElementById("estrela5").src = "<?php echo base_url() . '/imagens/estrelaCinza.png' ?>";
		}
		if (score === 5) {
			document.getElementById("estrela1").src = "<?php echo base_url() . '/imagens/estrelaDourada.png' ?>";
			document.getElementById("estrela2").src = "<?php echo base_url() . '/imagens/estrelaDourada.png' ?>";
			document.getElementById("estrela3").src = "<?php echo base_url() . '/imagens/estrelaDourada.png' ?>";
			document.getElementById("estrela4").src = "<?php echo base_url() . '/imagens/estrelaDourada.png' ?>";
			document.getElementById("estrela5").src = "<?php echo base_url() . '/imagens/estrelaDourada.png' ?>";
		}

		$("#avaliacaoAtendimento-form #valorlAvaliacao").val(score);


	}

	function avaliarAgora(codSolicitacao, codResponsavel, nomeResponsavel) {
		$("#avaliacaoAtendimento-form")[0].reset();
		document.getElementById("estrela1").src = "<?php echo base_url() . '/imagens/estrelaCinza.png' ?>";
		document.getElementById("estrela2").src = "<?php echo base_url() . '/imagens/estrelaCinza.png' ?>";
		document.getElementById("estrela3").src = "<?php echo base_url() . '/imagens/estrelaCinza.png' ?>";
		document.getElementById("estrela4").src = "<?php echo base_url() . '/imagens/estrelaCinza.png' ?>";
		document.getElementById("estrela5").src = "<?php echo base_url() . '/imagens/estrelaCinza.png' ?>";


		$('#avaliacaoAtendimento-modal').modal('show');
		$("#avaliacaoAtendimento-form #codSolicitacaoAvaliacao").val(codSolicitacao);
		$("#avaliacaoAtendimento-form #codResponsavelAvaliacao").val(codResponsavel);


		document.getElementById("tecnicoAvaliado").innerHTML = nomeResponsavel;

	}


	function apenasOAutor() {
		Swal.fire({
			position: 'bottom-end',
			icon: 'warning',
			title: 'Apenas o autor da solicitação pode realizar a avaliação do atendimento',
			showConfirmButton: false,
			timer: 3500
		})
	}

	function salvarAvaliacao() {

		event.preventDefault();
		var form = $('#avaliacaoAtendimento-form');
		$.ajax({
			url: '<?php echo base_url('solicitacoesSuporte/salvarAvaliacao') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
				codSolicitacao: document.getElementById('codSolicitacaoAvaliacao').value,
				valorlAvaliacao: document.getElementById('valorlAvaliacao').value
			},
			success: function(response) {

				if (response.success === true) {
					Swal.fire({
						position: 'bottom-end',
						icon: 'success',
						title: response.messages,
						showConfirmButton: false,
						timer: 1500
					})
				}
				$('#avaliacaoAtendimento-modal').modal('hide');
				$('#data_tablesolicitacoesSuporte').DataTable().ajax.reload(null, false).draw(false);

			}
		})

	}






	function fnMensagensAcoesNovas(codSolicitacao) {


		if (typeof codSolicitacao == 'undefined') {
			window.location = '<?php echo base_url() ?>';
		}


		$.ajax({


			url: '<?php echo base_url('solicitacoesSuporte/getAcoes') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
				codSolicitacao: codSolicitacao
			},
			success: function(acoes) {

				if (acoes.success = true) {


					if (ultimaMensagemAtual < acoes.ultimaMensagem) {

						const mensagensAcoes = document.getElementById("mensagensAcoes");

						mensagensAcoes.innerHTML = '';
						mensagensAcoes.innerHTML = acoes.html;

						chatWindow = document.getElementById('direct-chat-messages');
						var xH = chatWindow.scrollHeight;
						chatWindow.scrollTo(0, xH);
					}


				}


			}
		})
	}



	function fnMensagensAcoes(codSolicitacao) {

		const mensagensAcoes = document.getElementById("mensagensAcoes");
		mensagensAcoes.innerHTML = '';

		$.ajax({


			url: '<?php echo base_url('solicitacoesSuporte/getAcoes') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
				codSolicitacao: codSolicitacao
			},
			success: function(acoes) {

				if (acoes.success = true) {
					ultimaMensagemAtual = acoes.ultimaMensagem;
					mensagensAcoes.innerHTML = acoes.html;

					chatWindow = document.getElementById('direct-chat-messages');
					var xH = chatWindow.scrollHeight;
					chatWindow.scrollTo(0, xH);
				}


			}
		})
	}

	var codSolicitacaoTmp = 0;


	function editsolicitacoesSuporte(codSolicitacao) {

		//DESATIVA/ IGNIRA ERROR NO JAVASCRIPT
		window.onerror = function() {
			return true;
		}

		codSolicitacaoTmp = codSolicitacao;
		fnMensagensAcoes(codSolicitacaoTmp);



		function verificaChat() {
			fnMensagensAcoesNovas(codSolicitacaoTmp);

		}

		setInterval(verificaChat, 1000);

		document.getElementById('descricaoAcao').value = "";


		$.ajax({
			url: '<?php echo base_url('solicitacoesSuporte/getOne') ?>',
			type: 'post',
			data: {
				codSolicitacao: codSolicitacao,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 

				$("#edit-formsolicitacoesSuporte")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#edit-modalsolicitacoesSuporte').modal('show');

				document.getElementById("descricaoSolicitacaoReadOnly").innerHTML = response.descricaoSolicitacao;
				document.getElementById("datahora").innerHTML = response.dataCriacao;
				document.getElementById("nomeSolicitante").innerHTML = response.nomeExibicao;
				document.getElementById("departamentoInfo").innerHTML = response.abreviacaoDepartamento;
				document.getElementById("codSolicitacaoInfo").innerHTML = response.codSolicitacao;

				$("#edit-formsolicitacoesSuporte #codSolicitacao").val(response.codSolicitacao);
				$("#add-formAcaoSuporte #codSolicitacaoAcao").val(response.codSolicitacao);
				$("#edit-formsolicitacoesSuporte #codCategoriaSuporte").val(response.codCategoriaSuporte);
				$("#edit-formsolicitacoesSuporte #codSolicitante").val(response.codSolicitante);
				$("#edit-formsolicitacoesSuporte #codStatusSolicitacao").val(response.codStatusSolicitacao);
				$("#edit-formsolicitacoesSuporte #codTipoSolicitacao").val(response.codTipoSolicitacao);
				$("#edit-formsolicitacoesSuporte #codUrgencia").val(response.codUrgencia);
				$("#edit-formsolicitacoesSuporte #codPrioridade").val(response.codPrioridade);


				var codPessoa = "<?php echo "$codPessoa" ?>";



				if (response.codStatusSolicitacao !== '5') {
					document.getElementById('btnReabrir').style.visibility = 'hidden';
					document.getElementById('btnSalvarSolicitacao').style.visibility = 'visible';
					document.getElementById('envidorAcao').style.visibility = 'visible';
				} else {
					document.getElementById('btnReabrir').style.visibility = 'visible';
					document.getElementById('btnSalvarSolicitacao').style.visibility = 'hidden';
					document.getElementById('envidorAcao').style.visibility = 'hidden';
				}

				$.ajax({
					url: '<?php echo base_url('solicitacoesSuporte/listaDropDownStatusSuporte') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(status) {
						if (pertenceEquipeTecnica < 1) {
							$("#codStatusSolicitacaoEdit").select2({
								data: status,
								disabled: 'readonly',
							})
						} else {
							$("#codStatusSolicitacaoEdit").select2({
								data: status,
							})
						}
						$('#codStatusSolicitacaoEdit').val(response.codStatusSolicitacao); // Select the option with a value of '1'
						$('#codStatusSolicitacaoEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});


					}
				})




				$.ajax({
					url: '<?php echo base_url('solicitacoesSuporte/listaPercentualConclusao') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(percentual) {
						if (pertenceEquipeTecnica < 1) {
							$("#percentualConclusaoEdit").select2({
								data: percentual,
								disabled: 'readonly',
							})
						} else {
							$("#percentualConclusaoEdit").select2({
								data: percentual,
							})
						}

						$('#percentualConclusaoEdit').val(response.percentualConclusao); // Select the option with a value of '1'
						$('#percentualConclusaoEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

					}
				})



				$.ajax({
					url: '<?php echo base_url('solicitacoesSuporte/listaPercentualConclusao') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(percentual) {
						if (pertenceEquipeTecnica < 1) {
							$("#percentualConclusaoEdit").select2({
								data: percentual,
								disabled: 'readonly',
							})


						} else {
							$("#percentualConclusaoEdit").select2({
								data: percentual,
							})
						}

						$('#percentualConclusaoEdit').val(response.percentualConclusao); // Select the option with a value of '1'
						$('#percentualConclusaoEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

					}
				})

				$.ajax({
					url: '<?php echo base_url('solicitacoesSuporte/listaDropDownResponsaveis') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(responsaveis) {
						if (pertenceEquipeTecnica < 1) {
							$("#codResponsavelEdit").select2({
								data: responsaveis,
								disabled: 'readonly',
							})
						} else {
							$("#codResponsavelEdit").select2({
								data: responsaveis,
							})
						}

						$('#codResponsavelEdit').val(response.codResponsavel); // Select the option with a value of '1'
						$('#codResponsavelEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

					}
				})



				$.ajax({
					url: '<?php echo base_url('solicitacoesSuporte/listaDropDownCategoriasSuporte') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(categorias) {
						$("#codCategoriaSuporteEdit").select2({
							data: categorias,
						})
						$('#codCategoriaSuporteEdit').val(response.codCategoriaSuporte); // Select the option with a value of '1'
						$('#codCategoriaSuporteEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

					}
				})




				$.ajax({
					url: '<?php echo base_url('solicitacoesSuporte/listaDropDownTipoSolicitacao') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(tipoSolicitacao) {
						$("#codTipoSolicitacaoEdit").select2({
							data: tipoSolicitacao,
						})
						$('#codTipoSolicitacaoEdit').val(response.codTipoSolicitacao); // Select the option with a value of '1'
						$('#codTipoSolicitacaoEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

					}
				})



				$.ajax({
					url: '<?php echo base_url('solicitacoesSuporte/listaDropDownClassificacaoUrgencia') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(urgencia) {

						if (pertenceEquipeTecnica < 1) {
							$("#codUrgenciaEdit").select2({
								data: urgencia,
								disabled: 'readonly',
							})
						} else {
							$("#codUrgenciaEdit").select2({
								data: urgencia,
							})
						}


						$('#codUrgenciaEdit').val(response.codUrgencia); // Select the option with a value of '1'
						$('#codUrgenciaEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

					}
				})



				$.ajax({
					url: '<?php echo base_url('solicitacoesSuporte/listaDropDownClassificacaoPrioridade') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(prioridade) {
						if (pertenceEquipeTecnica < 1) {

							$("#codPrioridadeEdit").select2({
								data: prioridade,
								disabled: 'readonly',
							})
						} else {
							$("#codPrioridadeEdit").select2({
								data: prioridade,
							})
						}


						$('#codPrioridadeEdit').val(response.codPrioridade); // Select the option with a value of '1'
						$('#codPrioridadeEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

					}
				})



				//STATUS ADD AÇÃO


				$.ajax({
					url: '<?php echo base_url('solicitacoesSuporte/listaDropDownStatusSuporte') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(statusAcao) {
						if (pertenceEquipeTecnica < 1) {
							document.getElementById('divCodStatusSolicitacaoAcao').style.visibility = 'hidden';

						} else {
							document.getElementById('divCodStatusSolicitacaoAcao').style.visibility = 'visible';

							$("#codStatusSolicitacaoAcao").select2({
								data: statusAcao,
							})
						}
						$('#codStatusSolicitacaoAcao').val(response.codStatusSolicitacao); // Select the option with a value of '1'
						$('#codStatusSolicitacaoAcao').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});


					}
				})



				$.ajax({
					url: '<?php echo base_url('solicitacoesSuporte/listaPercentualConclusaoStatusSuporte') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(percentual) {
						if (pertenceEquipeTecnica < 1) {
							$("#percentualConclusaAcao").select2({
								data: percentual,
								disabled: 'readonly',
							})
						} else {
							$("#percentualConclusaAcao").select2({
								data: percentual,
							})
						}

						$('#percentualConclusaAcao').val(response.percentualConclusao); // Select the option with a value of '1'
						$('#percentualConclusaAcao').trigger('change');
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
						var form = $('#edit-formsolicitacoesSuporte');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('solicitacoesSuporte/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {},
							success: function(response) {

								if (response.success === true) {


									if (response.codStatusSolicitacao !== '5') {
										document.getElementById('btnReabrir').style.visibility = 'hidden';
										document.getElementById('btnSalvarSolicitacao').style.visibility = 'visible';
										document.getElementById('envidorAcao').style.visibility = 'visible';
									} else {

										$.ajax({
											url: '<?php echo base_url('StatusSuporte/listaPercentualConclusao') ?>',
											type: 'post',
											dataType: 'json',
											data: {
												csrf_sandra: $("#csrf_sandraPrincipal").val(),
											},
											success: function(percentual) {
												if (pertenceEquipeTecnica < 1) {
													$("#percentualConclusaoEdit").select2({
														data: percentual,
														disabled: 'readonly',
													})



												} else {
													$("#percentualConclusaoEdit").select2({
														data: percentual,
													})
												}

												$('#percentualConclusaoEdit').val(100); // Select the option with a value of '1'
												$('#percentualConclusaoEdit').trigger('change');
												$(document).on('select2:open', () => {
													document.querySelector('.select2-search__field').focus();
												});

											}
										})
										document.getElementById('btnReabrir').style.visibility = 'visible';
										document.getElementById('btnSalvarSolicitacao').style.visibility = 'hidden';
										document.getElementById('envidorAcao').style.visibility = 'hidden';
									}

									//REFRESHA MENSAGENS
									fnMensagensAcoes(codSolicitacao);
									Swal.fire({
										position: 'bottom-end',
										icon: 'success',
										title: response.messages,
										showConfirmButton: false,
										timer: 1500
									}).then(function() {
										$('#data_tablesolicitacoesSuporte').DataTable().ajax.reload(null, false).draw(false);
										//$('#edit-modalsolicitacoesSuporte').modal('hide');

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
								$('#btnSalvarSolicitacao').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#edit-formsolicitacoesSuporte').validate();

			}
		});
	}

	function removesolicitacoesSuporte(codSolicitacao) {
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
					url: '<?php echo base_url('solicitacoesSuporte/remove') ?>',
					type: 'post',
					data: {
						codSolicitacao: codSolicitacao,
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
								$('#data_tablesolicitacoesSuporte').DataTable().ajax.reload(null, false).draw(false);
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