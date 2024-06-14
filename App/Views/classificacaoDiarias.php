<?php


?>
<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-md-8 mt-2">
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Departamentos</h3>
						</div>
						<div class="col-md-12">

						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<table id="data_table" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>Código</th>
								<th>Departamento</th>
								<th>Abreviação</th>
								<th>Tipo Departamento</th>
								<th>Diária Paciente</th>
								<th>Diária Acompanhante</th>

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

<style>
	.teste {
		z-index: 1200;
	}
</style>




<div id="addMembro-modal" class="modal fade teste" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabelExportacao">Adicionar Membro no Departamento</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="addMembro-form" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>AddMembro" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
						<input type="hidden" id="codDepartamentoAddMembro" name="codDepartamento" class="form-control" placeholder="Código" maxlength="11" required>

					</div>
					<div class="row">
						<div class="col-md-4" id="selectAdicionarMembro">
							<div class="form-group">
								<label for="codPessoa"> Pessoa: <span class="text-danger">*</span> </label>
								<select id="codPessoaAdcionar" name="codPessoa" class="custom-select" required>
									<option value=""></option>
								</select>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button onclick="adicionarPessoaAgora()" type="button" class="btn btn-xs btn-primary">Adicionar Membro</button>

							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>




<div id="transferir-modal" class="modal fade teste" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabelExportacao">Transferir membro para outro Departamentos</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="transferir-form" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>Transf" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
						<input type="hidden" id="codPessoa" name="codPessoa" class="form-control" placeholder="Código" maxlength="11" required>

					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="pessoa"> Pessoa:</label>
								<input disabled type="text" id="nomeExibixao" name="nomeExibixao" class="form-control" placeholder="Pessoa" maxlength="100">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4" id="selectTtransferencia">
							<div class="form-group">
								<label for="codDepartamentoResponsavel"> Departamento: <span class="text-danger">*</span> </label>
								<select id="codDepartamentoTransferencia" name="codDepartamento" class="custom-select" required>
									<option value=""></option>
								</select>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button onclick="transferirPessoaAgora()" type="button" class="btn btn-xs btn-primary">Transferir agora</button>

							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>



<div id="add-modal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar <?php echo $title ?></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="add-form" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>AddDept" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
						<input type="hidden" id="codDepartamento" name="codDepartamento" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">

						<div class="col-md-4">
							<div class="form-group">
								<label for="descricaoDepartamento"> Departamento: <span class="text-danger">*</span> </label>
								<input type="text" id="descricaoDepartamento" name="descricaoDepartamento" class="form-control" placeholder="Departamento" maxlength="100" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="abreviacaoDepartamento"> Abreviação: </label>
								<input type="text" id="abreviacaoDepartamento" name="abreviacaoDepartamento" class="form-control" placeholder="Abreviação" maxlength="30">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="paiDepartamento"> Subordinação: <span class="text-danger">*</span> </label>
								<select id="paiDepartamentoAdd" name="paiDepartamento" class="custom-select">
									<option value=""></option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="telefone"> Telefone: </label>
								<input type="text" id="telefone" name="telefone" class="form-control" placeholder="Telefone" maxlength="20">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="email"> Email: </label>
								<input type="email" id="email" name="email" class="form-control" placeholder="Email" maxlength="50">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="ativo"> Ativo: </label>
								<select id="ativo" name="ativo" class="custom-select">
									<option value="1">Sim</option>
									<option value="0">Não</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="ativo"> Tipo Departamento: </label>
								<select id="codTipoDepartamento" name="codTipoDepartamento" class="custom-select">
									<option value="1">Administrativo</option>
									<option value="2">Internações</option>
									<option value="3">Atendimentos</option>
									<option value="4">Cirurgias</option>
								</select>
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


<div id="exportarDepartamentos-modal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabelExportacao">Exportação de Departamentos do LDAP</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<table id="data_tableExportarDepartamentos" class="table table-striped table-hover table-sm">
					<thead>
						<tr>
							<th>Descricao</th>
							<th>IP</th>
							<th></th>
						</tr>
					</thead>
				</table>

			</div>

		</div>

	</div>
</div>



<div id="importarDepartamentos-modal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabelImportacao">Importarção de Departamentos do LDAP</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<table id="data_tableImportarDepartamentos" class="table table-striped table-hover table-sm">
					<thead>
						<tr>
							<th>Descricao</th>
							<th>IP</th>
							<th></th>
						</tr>
					</thead>
				</table>

			</div>

		</div>

	</div>
</div>



<div id="atendimentoslocaisAddModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Dependências dos Departamentos</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="atendimentoslocaisAddForm" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>codLocalAtendimentoAdd" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
						<input type="hidden" id="codLocalAtendimentoAdd" name="codLocalAtendimento" class="form-control" placeholder="codLocalAtendimento" maxlength="11" required>
						<input type="hidden" id="codDepartamentoLocalAtendimentoAdd" name="codDepartamento" class="form-control" placeholder="codDepartamento" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="descricaoLocalAtendimentoAdd"> Descrição : <span class="text-danger">*</span> </label>
								<input type="text" id="descricaoLocalAtendimentoAdd" name="descricaoLocalAtendimento" class="form-control" placeholder="Descrição" maxlength="100" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codTipoLocalAtendimentoAdd"> Tipo: <span class="text-danger">*</span> </label>
								<select id="codTipoLocalAtendimentoAdd" name="codTipoLocalAtendimento" class="custom-select" required>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codStatusLocalAtendimentoAdd"> Status: <span class="text-danger">*</span> </label>
								<select id="codStatusLocalAtendimentoAdd" name="codStatusLocalAtendimento" class="custom-select" required>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codSituacaoLocalAtendimentoAdd"> Situação: <span class="text-danger">*</span> </label>
								<select id="codSituacaoLocalAtendimentoAdd" name="codSituacaoLocalAtendimento" class="custom-select" required>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="observacoesAtendimentoAdd"> Observações: <span class="text-danger">*</span> </label>

								<textarea id="observacoesAtendimentoAdd" name="observacoes" class="form-control"></textarea>
							</div>
						</div>
					</div>
					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="atendimentoslocaisAddForm-btn">Adicionar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>



<div id="atendimentoslocaisEditModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Dependências dos Departamentos</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="atendimentoslocaisEditForm" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>codLocalAtendimentoEdit" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codLocalAtendimentoEdit" name="codLocalAtendimento" class="form-control" placeholder="codDepartamento" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="descricaoLocalAtendimentoEdit"> Descrição : <span class="text-danger">*</span> </label>
								<input type="text" id="descricaoLocalAtendimentoEdit" name="descricaoLocalAtendimento" class="form-control" placeholder="Descrição" maxlength="100" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codTipoLocalAtendimentoEdit"> Tipo: <span class="text-danger">*</span> </label>
								<select id="codTipoLocalAtendimentoEdit" name=" codTipoLocalAtendimento" class="custom-select" required>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codStatusLocalAtendimentoEdit"> Status: <span class="text-danger">*</span> </label>
								<select id="codStatusLocalAtendimentoEdit" name="codStatusLocalAtendimento" class="custom-select" required>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codSituacaoLocalAtendimentoEdit"> Situação: <span class="text-danger">*</span> </label>
								<select id="codSituacaoLocalAtendimentoEdit" name="codSituacaoLocalAtendimento" class="custom-select" required>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="observacoesAtendimentoEdit"> Observações: <span class="text-danger">*</span> </label>

								<textarea id="observacoesAtendimentoEdit" name="observacoes" class="form-control"></textarea>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="atendimentoslocaisEditForm-btn">Salvar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>




<!-- Add modal content -->
<div id="edit-modal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar <?php echo $title ?></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">




				<div class="col-12 col-sm-12">
					<div class="card card-primary card-tabs">
						<div class="card-header p-0 pt-1">
							<ul class="nav nav-tabs" id="aba-tab" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" id="aba-propriedade-tab" data-toggle="pill" href="#aba-propriedade" role="tab" aria-controls="aba-propriedade" aria-selected="true">Propriedade</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="aba-locaisAtendimento-tab" data-toggle="pill" href="#aba-locaisAtendimento" role="tab" aria-controls="aba-locaisAtendimento" aria-selected="false">Locais de Atendimento/Dependência</a>
								</li>
							</ul>
						</div>
						<div class="card-body">
							<div class="tab-content" id="aba-tabContent">
								<div class="tab-pane fade show active" id="aba-propriedade" role="tabpanel" aria-labelledby="aba-propriedade-tab">

									<form id="edit-form" class="pl-3 pr-3">
										<div class="row">
											<input type="hidden" id="<?php echo csrf_token() ?>EditDept" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

											<input type="hidden" id="codDepartamento" name="codDepartamento" class="form-control" placeholder="Código" maxlength="11" required>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="descricaoDepartamento"> Departamento: <span class="text-danger">*</span> </label>
													<input disabled type="text" id="descricaoDepartamento" name="descricaoDepartamento" class="form-control" placeholder="Departamento" maxlength="100" required>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="abreviacaoDepartamento"> Abreviação: </label>
													<input disabled type="text" id="abreviacaoDepartamento" name="abreviacaoDepartamento" class="form-control" placeholder="Abreviação" maxlength="30">
												</div>
											</div>
										</div>
										<div class="row">

											<div class="col-md-4">
												<div class="form-group">
													<label for="ativo"> Diaria Paciente: </label>
													<select id="codTaxaServico" name="codTaxaServico" class="custom-select">
														<option value=""></option>
													</select>
												</div>
											</div>


											<div class="col-md-4">
												<div class="form-group">
													<label for="ativo">Diaria Acompanhante: </label>
													<select id="codTaxaServicoAcompanhante" name="codTaxaServicoAcompanhante" class="custom-select">
														<option value=""></option>
													</select>
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


								<div class="tab-pane fade" id="aba-locaisAtendimento" role="tabpanel" aria-labelledby="aba-locaisAtendimento-tab">

									<div class="row">
										<div class="col-md-4">
											<button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addatendimentoslocais()" title="Adicionar"> Adicionar</button>
										</div>
									</div>


									<table id="data_tableatendimentoslocais" class="table table-striped table-hover table-sm">
										<thead>
											<tr>
												<th>Código</th>
												<th>Descrição</th>
												<th>Tipo</th>
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
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /.content -->
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
		$('#data_table').DataTable({
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url($controller . '/getAllClassificacaoDiarias') ?>',
				"type": "post",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});

		$.ajax({
			url: '<?php echo base_url('departamentos/listaDropDown') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(departamentoAdd) {

				$("#paiDepartamentoAdd").select2({
					data: departamentoAdd,
				})

				$('#paiDepartamentoAdd').val(null); // Select the option with a value of '1'
				$('#paiDepartamentoAdd').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});


			}
		})



		$.ajax({
			url: '<?php echo base_url('departamentos/listaDropDown') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(departamentoAdd) {

				$("#paiDepartamentoEdit").select2({
					data: departamentoAdd,
				})

				$('#paiDepartamentoEdit').val(null); // Select the option with a value of '1'
				$('#paiDepartamentoEdit').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})



	});


	function importarAgora(codServidorLDAP, descricaoServidorLDAP, ipServidorLDAP) {
		Swal.fire({
			title: 'Você tem certeza que deseja importar todos os DEPARTAMENTOS existentes no servidor LDAP "' + descricaoServidorLDAP + '(' + ipServidorLDAP + ')" para o sistema local?',
			text: "Esta ação irá sincronizar os departamentos de mesmo nome como apenas 1, juntando seus objetos Localmente",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar',
		}).then((result) => {
			if (result.value) {

				//EXECUTA JSON DE IMPORTAÇÃO
				$('#importarDepartamentos-modal').modal('hide');

				$.ajax({
					url: '<?php echo base_url('departamentos/importarDepartamentos/') ?>',
					type: 'post',
					data: {
						codServidorLDAP: codServidorLDAP,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),

					},
					dataType: 'json',
					success: function(response) {
						if (response.success === true) {

							Swal.fire({
								position: 'bottom-end',
								icon: 'success',
								html: response.messages,
								showConfirmButton: false,
								timer: 5000
							}).then(function() {

								$('#data_table').DataTable().ajax.reload(null, false).draw(false);
							})


						}

						if (response.erro === true) {

							Swal.fire({
								position: 'bottom-end',
								icon: 'error',
								title: response.messages,
								showConfirmButton: false,
								timer: 5000
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


			} else {
				Swal.fire({
					title: 'Cancelado!',
					text: "Tenha certeza da operação a ser realizada",
					icon: 'info',
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Fechar',
					timer: 3000,
				})
			}
		})
	}


	function exportarAgora(codServidorLDAP, descricaoServidorLDAP, ipServidorLDAP) {
		Swal.fire({
			title: 'Você tem certeza que deseja exportar todos os DEPARTAMENTOS existentes"' + descricaoServidorLDAP + '(' + ipServidorLDAP + ')" para o servidor LDAP?',
			text: "Esta ação irá sincronizar os departamentos de mesmo nome em apenas 1, juntando seus objetos no servidor LDAP selecionado",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar',
		}).then((result) => {
			if (result.value) {




				//EXECUTA JSON DE IMPORTAÇÃO
				$('#exportarDepartamentos-modal').modal('hide');

				$.ajax({
					url: '<?php echo base_url('departamentos/exportarDepartamentos/') ?>',
					type: 'post',
					data: {
						codServidorLDAP: codServidorLDAP,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					dataType: 'json',
					success: function(response) {
						if (response.success === true) {

							Swal.fire({
								position: 'bottom-end',
								icon: 'success',
								html: response.messages,
								showConfirmButton: false,
								timer: 5000
							}).then(function() {

								$('#data_table').DataTable().ajax.reload(null, false).draw(false);
							})


						}

						if (response.erro === true) {

							Swal.fire({
								position: 'bottom-end',
								icon: 'error',
								title: response.messages,
								showConfirmButton: false,
								timer: 5000
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


			} else {
				Swal.fire({
					title: 'Cancelado!',
					text: "Tenha certeza da operação a ser realizada",
					icon: 'info',
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Fechar',
					timer: 3000,
				})
			}
		})
	}

	function ExportarDepartamentos() {

		Swal.fire({
			title: 'A exportação de Departamentos é uma via de mão única que irá transferir todos os departamentis do sistema local para seus servidores LDAP.',
			text: "Esta ação irá sincronizar os departamentos de mesmo nome como apenas 1, juntando seus objetos",
			icon: 'info',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {
			if (result.value) {

				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#exportarDepartamentos-modal').modal('show');

				$('#data_tableExportarDepartamentos').DataTable({
					"bDestroy": true,
					"paging": false,
					"lengthChange": false,
					"searching": false,
					"ordering": false,
					"info": true,
					"autoWidth": false,
					"responsive": true,
					"ajax": {
						"url": '<?php echo base_url('Departamentos/pegaServidoresLDAPMIcrosoftParaExportacao') ?>',
						"type": "POST",
						"dataType": "json",
						async: "true",
						data: {
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},
					}
				});

			} else {
				Swal.fire({
					title: 'Cancelado!',
					text: "Tenha certeza da operação a ser realizada",
					icon: 'info',
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Fechar',
					timer: 3000,
				})
			}
		})

	}


	function addatendimentoslocais() {
		// reset the form 
		$("#atendimentoslocaisAddForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#atendimentoslocaisAddModal').modal('show');

		$('#codTipoLocalAtendimentoAdd').val(null); // Select the option with a value of '1'
		$('#codTipoLocalAtendimentoAdd').trigger('change');
		$(document).on('select2:open', () => {
			document.querySelector('.select2-search__field').focus();
		});


		$('#codStatusLocalAtendimentoAdd').val(1); // Select the option with a value of '1'
		$('#codStatusLocalAtendimentoAdd').trigger('change');
		$(document).on('select2:open', () => {
			document.querySelector('.select2-search__field').focus();
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

				var form = $('#atendimentoslocaisAddForm');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('atendimentosLocais/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#atendimentoslocaisAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function(response) {

						if (response.success === true) {
							$('#atendimentoslocaisAddModal').modal('hide');

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
								$('#data_tableatendimentoslocais').DataTable().ajax.reload(null, false).draw(false);
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
						$('#atendimentoslocaisAddForm-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#atendimentoslocaisAddForm').validate();
	}


	function ImportarDepartamentos() {

		Swal.fire({
			title: 'A importação de departamentos é uma via de mão única, do servidor de LDAP para o este sistema.',
			text: "Esta ação irá sincronizar os departamentos de mesmo nome como apenas 1, juntando seus objetos",
			icon: 'info',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {
			if (result.value) {

				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#importarDepartamentos-modal').modal('show');

				$('#data_tableImportarDepartamentos').DataTable({
					"bDestroy": true,
					"paging": false,
					"lengthChange": false,
					"searching": false,
					"ordering": false,
					"info": true,
					"autoWidth": false,
					"responsive": true,
					"ajax": {
						"url": '<?php echo base_url('departamentos/pegaServidoresLDAPMicrosoft') ?>',
						"type": "POST",
						"dataType": "json",
						async: "true",
						data: {
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},
					}
				});


			} else {
				Swal.fire({
					title: 'Cancelado!',
					text: "Tenha certeza da operação a ser realizada",
					icon: 'info',
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Fechar',
					timer: 3000,
				})
			}
		})

	}



	function add() {
		// reset the form 
		$("#add-form")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#add-modal').modal('show');
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
							$("input[id*='csrf_sandra']").val(response.csrf_hash);
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

	function edit(codDepartamento) {


		$.ajax({
			url: '<?php echo base_url($controller . '/getOne') ?>',
			type: 'post',
			data: {
				codDepartamento: codDepartamento,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {

				// reset the form 
				$("#edit-form")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#edit-modal').modal('show');
				$("#edit-form #codDepartamento").val(response.codDepartamento);

				$("#edit-form #descricaoDepartamento").val(response.descricaoDepartamento);
				$("#edit-form #abreviacaoDepartamento").val(response.abreviacaoDepartamento);
				$("#addMembro-form #codDepartamentoAddMembro").val(response.codDepartamento);
				$("#atendimentoslocaisAddForm #codDepartamentoLocalAtendimentoAdd").val(response.codDepartamento);


				$('.modal-title').text(response.descricaoDepartamento);








				$.ajax({
					url: '<?php echo base_url('departamentos/listaTiposDiarias') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(listaTiposDiarias) {

						$("#edit-form #codTaxaServico").select2({
							data: listaTiposDiarias,
						})

						$("#edit-form #codTaxaServico").val(response.codTaxaServico); // Select the option with a value of '1'
						$("#edit-form #codTaxaServico").trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});



					}
				})


				$.ajax({
					url: '<?php echo base_url('departamentos/listaTiposDiarias') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(listaTiposDiarias) {

						$("#edit-form #codTaxaServicoAcompanhante").select2({
							data: listaTiposDiarias,
						})

						$("#edit-form #codTaxaServicoAcompanhante").val(response.codTaxaServicoAcompanhante); // Select the option with a value of '1'
						$("#edit-form #codTaxaServicoAcompanhante").trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});



					}
				})

				$('#data_tableatendimentoslocais').DataTable({
					"paging": true,
					"bDestroy": true,
					"lengthChange": false,
					"searching": true,
					"ordering": true,
					"info": true,
					"autoWidth": false,
					"responsive": true,
					"ajax": {
						"url": '<?php echo base_url('AtendimentosLocais/locaisAtendimento') ?>',
						"type": "post",
						"dataType": "json",
						data: {
							codDepartamento: codDepartamento,
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},
						async: "true"
					},
					success: function(responseLocaisAtendimento) {

					}
				});




				$.ajax({
					url: '<?php echo base_url('departamentos/listaDropDownTiposLocalAtendimentos') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},

					success: function(tipoLocalAtendimentoAdd) {

						$("#codTipoLocalAtendimentoAdd").select2({
							data: tipoLocalAtendimentoAdd,
						})

						$('#codTipoLocalAtendimentoAdd').val(null); // Select the option with a value of '1'
						$('#codTipoLocalAtendimentoAdd').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

					},
				})

				$.ajax({
					url: '<?php echo base_url('departamentos/listaDropDownStatusLocalAtendimentos') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(statusLocalAtendimentoAdd) {

						$("#codStatusLocalAtendimentoAdd").select2({
							data: statusLocalAtendimentoAdd,
						})

						$('#codStatusLocalAtendimentoAdd').val(1); // Select the option with a value of '1'
						$('#codStatusLocalAtendimentoAdd').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});


					},
				})

				$.ajax({
					url: '<?php echo base_url('departamentos/listaDropDownSituacaoLocalAtendimentos') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(situacaoLocalAtendimentoAdd) {

						$("#codSituacaoLocalAtendimentoAdd").select2({
							data: situacaoLocalAtendimentoAdd,
						})

						$('#codSituacaoLocalAtendimentoAdd').val(1); // Select the option with a value of '1'
						$('#codSituacaoLocalAtendimentoAdd').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});



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
							url: '<?php echo base_url($controller . '/editClassificacaoDiarias') ?>',
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
										$('#edit-modal').modal('hide');
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
						}).always(
							Swal.fire({
								title: 'Estamos salvando',
								html: 'Aguarde....',
								timerProgressBar: true,
								didOpen: () => {
									Swal.showLoading()


								}

							}))

						return false;
					}
				});
				$('#edit-form').validate();

			}
		})
	}



	function reativarPessoa(codPessoa) {
		Swal.fire({
			title: 'Você deseja reativar esta pessoa do sistema?',
			text: "Ao reativar está pessoa no sistema ela voltará a ter os mesmo acessos concedidos previamente.",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {


			if (result.value) {
				$.ajax({
					url: '<?php echo base_url('pessoas/reativarpessoa') ?>',
					type: 'post',
					data: {
						codPessoa: codPessoa,
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
								$('#data_tableMembros').DataTable().ajax.reload(null, false).draw(false);
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


	function adicionarPessoa() {
		$.ajax({
			url: '<?php echo base_url('pessoas/listaDropDownPessoas') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(PessoasAdd) {
				$("#codPessoaAdcionar").select2({
					data: PessoasAdd,
				})
				$("#codPessoaAdcionar").select2({
					dropdownParent: $("#selectAdicionarMembro")
				});
				$('#codPessoaAdcionar').val(null); // Select the option with a value of '1'
				$('#codPessoaAdcionar').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})

		$('#addMembro-modal').modal('show');
	}



	function transferirPessoa(codPessoa, nomeExibixao) {
		Swal.fire({
			title: 'Você deseja transferir esta pessoa para outro departamento?',
			text: "Ao transferie está pessoa de departamento ela não receberá privilégios de acesso altomaticamente. É necessário incluíla no grupo da seção de destino",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {

			$.ajax({
				url: '<?php echo base_url('departamentos/listaDropDown') ?>',
				type: 'post',
				dataType: 'json',
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
				success: function(departamentoTransferencia) {
					$("#transferir-form #codPessoa").val(codPessoa);
					$("#transferir-form #nomeExibixao").val(nomeExibixao);


					$("#codDepartamentoTransferencia").select2({
						data: departamentoTransferencia,
					})
					$("#codDepartamentoTransferencia").select2({
						dropdownParent: $("#selectTtransferencia")
					});


				}
			})

			$('#transferir-modal').modal('show');

		})
	}


	function desativarPessoa(codPessoa) {
		Swal.fire({
			title: 'Você tem certeza que deseja desativar esta pessoa do sistema?',
			text: "Você poderá reativar a qualquer momento",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {
			if (result.value) {
				$.ajax({
					url: '<?php echo base_url('/pessoas/desativarpessoa') ?>',
					type: 'post',
					data: {
						codPessoa: codPessoa,
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
								$('#data_tableMembros').DataTable().ajax.reload(null, false).draw(false);
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


	function adicionarPessoaAgora() {

		var form = $('#addMembro-form');

		$.ajax({
			url: '<?php echo base_url('departamentos/addMembro') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(response) {

				if (response.success === true) {

					$('#addMembro-modal').modal('hide');
					$('#data_tableMembros').DataTable().ajax.reload(null, false).draw(false);

					$.ajax({
							url: '<?php echo base_url('pessoas/exportarPessoa') ?>',
							type: 'post',
							data: {
								codPessoa: response.codPessoa,
								csrf_sandra: $("#csrf_sandraPrincipal").val(),

							},
							dataType: 'json',
						}),

						Swal.fire({
							position: 'bottom-end',
							icon: 'success',
							title: response.messages,
							showConfirmButton: false,
							timer: 3000
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



			}
		}).always(
			Swal.fire({
				title: 'Estamos adicionando novo membro',
				html: 'Aguarde....',
				timerProgressBar: true,
				didOpen: () => {
					Swal.showLoading()


				}

			}))
	};


	function transferirPessoaAgora() {

		var form = $('#transferir-form');

		$.ajax({
			url: '<?php echo base_url('departamentos/transferirMembrosDepartamento') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(response) {

				if (response.success === true) {

					$('#transferir-modal').modal('hide');

					$('#data_tableMembros').DataTable().ajax.reload(null, false).draw(false);



					$.ajax({
							url: '<?php echo base_url('pessoas/exportarPessoa') ?>',
							type: 'post',
							data: {
								codPessoa: response.codPessoa,
								csrf_sandra: $("#csrf_sandraPrincipal").val(),

							},
							dataType: 'json',
						}),





						Swal.fire({
							position: 'bottom-end',
							icon: 'success',
							title: response.messages,
							showConfirmButton: false,
							timer: 3000
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



			}
		});
	};


	function editatendimentoslocais(codLocalAtendimento) {
		$.ajax({
			url: '<?php echo base_url('atendimentosLocais/getOne') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				codLocalAtendimento: codLocalAtendimento,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),

			},
			success: function(responseLocalAtendimentoEdit) {
				// reset the form 
				$("#atendimentoslocaisEditForm")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#atendimentoslocaisEditModal').modal('show');

				$("#atendimentoslocaisEditForm #codDepartamentoEdit").val(responseLocalAtendimentoEdit.codDepartamento);
				$("#atendimentoslocaisEditForm #codLocalAtendimentoEdit").val(codLocalAtendimento);
				$("#atendimentoslocaisEditForm #descricaoLocalAtendimentoEdit").val(responseLocalAtendimentoEdit.descricaoLocalAtendimento);
				$("#atendimentoslocaisEditForm #codPessoaEdit").val(responseLocalAtendimentoEdit.codPessoa);
				$("#atendimentoslocaisEditForm #dataAtualizacaoEdit").val(responseLocalAtendimentoEdit.dataAtualizacao);
				$("#atendimentoslocaisEditForm #observacoesAtendimentoEdit").val(responseLocalAtendimentoEdit.observacoes);


				$.ajax({
					url: '<?php echo base_url('departamentos/listaDropDownTiposLocalAtendimentos') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(tipoLocalAtendimentoEdit) {

						$("#codTipoLocalAtendimentoEdit").select2({
							data: tipoLocalAtendimentoEdit,
						})

						$('#codTipoLocalAtendimentoEdit').val(responseLocalAtendimentoEdit.codTipoLocalAtendimento); // Select the option with a value of '1'
						$('#codTipoLocalAtendimentoEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});



					}
				});

				$.ajax({
					url: '<?php echo base_url('departamentos/listaDropDownSituacaoLocalAtendimentos') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(situacaoLocalAtendimentoEdit) {

						$("#codSituacaoLocalAtendimentoEdit").select2({
							data: situacaoLocalAtendimentoEdit,
						})

						$('#codSituacaoLocalAtendimentoEdit').val(responseLocalAtendimentoEdit.codSituacaoLocalAtendimento); // Select the option with a value of '1'
						$('#codSituacaoLocalAtendimentoEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});



					}
				});

				$.ajax({
					url: '<?php echo base_url('departamentos/listaDropDownStatusLocalAtendimentos') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(statusLocalAtendimentoEdit) {

						$("#codStatusLocalAtendimentoEdit").select2({
							data: statusLocalAtendimentoEdit,
						})

						$('#codStatusLocalAtendimentoEdit').val(responseLocalAtendimentoEdit.codStatusLocalAtendimento); // Select the option with a value of '1'
						$('#codStatusLocalAtendimentoEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});



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
						var form = $('#atendimentoslocaisEditForm');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('atendimentosLocais/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#atendimentoslocaisEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
							},
							success: function(response) {

								if (response.success === true) {

									$('#atendimentoslocaisEditModal').modal('hide');


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
										$('#data_tableatendimentoslocais').DataTable().ajax.reload(null, false).draw(false);
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
								$('#atendimentoslocaisEditForm-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#atendimentoslocaisEditForm').validate();

			}
		});
	}

	function removeatendimentoslocais(codLocalAtendimento) {

		Swal.fire({
			position: 'bottom-end',
			icon: 'warning',
			title: 'Não é possivel remover este local de atendimento',
			html: 'Caso necessário, desative-o',
			showConfirmButton: false,
			timer: 4000
		})


		/*
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
					url: '<?php echo base_url('atendimentosLocais/remove') ?>',
					type: 'post',
					data: {
						codLocalAtendimento: codLocalAtendimento
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
								$('#data_tableatendimentoslocais').DataTable().ajax.reload(null, false).draw(false);
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
		*/
	}


	function remove(codDepartamento) {
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
						codDepartamento: codDepartamento,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					dataType: 'json',
					success: function(response) {

						if (response.pessoas === true) {
							Swal.fire({
								position: 'bottom-end',
								icon: 'warning',
								title: "Há pessoas no departamento",
								text: "Não é possível deletar Departamento com pessoas dentro. Remova ou transfira as pessoas, primeiro.",
								showConfirmButton: true,
								timer: 10000
							}).then(function() {
								$('#data_table').DataTable().ajax.reload(null, false).draw(false);
							})
						}

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
						}

						if (response.success === false) {
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