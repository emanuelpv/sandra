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
						<div class="col-md-8 mt-2">
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Grupos</h3>
						</div>
						<div class="col-md-12">

							<div class="form-group  text-right">
								<div class="btn-group">
									<button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar Grupo" onclick="addgrupos()" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
									<button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Importar Departamentos" onclick="ImportarGrupos()" title="Importar Grupos"> <i class="fas fa-file-import"></i> Importar</button>
									<button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Exportar Departamentos" onclick="ExportarGrupos()" title="Exportar Grupos"> <i class="fa fa-file-export"></i> Exportar</button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- /.card-header -->
				<div class="card-body">
					<table id="data_tablegrupos" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>Código</th>
								<th>DescricaoGrupo</th>
								<th>AbreviacaoGrupo</th>
								<th>Departamento</th>
								<th>Telefone</th>
								<th>Email</th>
								<th>Ativo</th>

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
<div id="add-modalgrupos" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Grupos</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="add-formgrupos" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>add-formgrupos" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codGrupo" name="codGrupo" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="descricaoGrupo"> DescricaoGrupo: <span class="text-danger">*</span> </label>
								<input type="text" id="descricaoGrupo" name="descricaoGrupo" class="form-control" placeholder="DescricaoGrupo" maxlength="100" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="abreviacaoGrupo"> AbreviacaoGrupo: <span class="text-danger">*</span></label>
								<input type="text" id="abreviacaoGrupo" name="abreviacaoGrupo" class="form-control" placeholder="AbreviacaoGrupo" maxlength="100" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="telefone"> Telefone: </label>
								<input type="text" id="telefone" name="telefone" class="form-control" placeholder="Telefone" maxlength="20">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4" id="selectAdd">
							<div class="form-group">
								<label for="codDepartamento"> Departamento: <span class="text-danger">*</span> </label>
								<select id="codDepartamentoAdd" name="codDepartamento" class="custom-select">
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="email"> Email: </label>
								<input type="text" id="email" name="email" class="form-control" placeholder="Email" maxlength="50">
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

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-xs btn-primary" id="add-formgrupos-btn">Adicionar</button>
							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<style>
	.teste {
		z-index: 1200;
	}
</style>





<div id="importarGrupos-modal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabelImportacao">Importarção de Grupos do LDAP</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<table id="data_tableImportarGrupos" class="table table-striped table-hover table-sm">
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
						<input type="hidden" id="<?php echo csrf_token() ?>addMembro-form" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codGrupoAddMembro" name="codGrupo" class="form-control" placeholder="Código" maxlength="11" required>

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
							<button onclick="adicionarPessoaAgora()" type="button" class="btn btn-xs btn-primary">Adicionar agora</button>

							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div id="exportarGrupos-modal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabelExportacao">Exportação de Grupos do LDAP</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<table id="data_tableExportarGrupos" class="table table-striped table-hover table-sm">
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


<!-- Add modal content -->
<div id="edit-modalgrupos" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Grupos</h4>
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
									<a class="nav-link" id="aba-membros-tab" data-toggle="pill" href="#aba-membros" role="tab" aria-controls="aba-membros" aria-selected="false">Membros</a>
								</li>
							</ul>
						</div>
						<div class="card-body">
							<div class="tab-content" id="aba-tabContent">
								<div class="tab-pane fade show active" id="aba-propriedade" role="tabpanel" aria-labelledby="aba-propriedade-tab">
									<form id="edit-formgrupos" class="pl-3 pr-3">
										<div class="row">
											<input type="hidden" id="<?php echo csrf_token() ?>edit-formgrupos" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

											<input type="hidden" id="codGrupo" name="codGrupo" class="form-control" placeholder="Código" maxlength="11" required>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="descricaoGrupo"> DescricaoGrupo: <span class="text-danger">*</span> </label>
													<input type="text" id="descricaoGrupo" name="descricaoGrupo" class="form-control" placeholder="DescricaoGrupo" maxlength="100" required>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="abreviacaoGrupo"> AbreviacaoGrupo: <span class="text-danger">*</span></label>
													<input type="text" id="abreviacaoGrupo" name="abreviacaoGrupo" class="form-control" placeholder="AbreviacaoGrupo" maxlength="100" required>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="telefone"> Telefone: </label>
													<input type="text" id="telefone" name="telefone" class="form-control" placeholder="Telefone" maxlength="20">
												</div>
											</div>
										</div>
										<div class="row">

											<div class="col-md-4" id="selectEdit">
												<div class="form-group">
													<label for="codDepartamento"> Departamento: <span class="text-danger">*</span> </label>
													<select id="codDepartamentoEdit" name="codDepartamento" class="custom-select">
														<option value=""></option>
													</select>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="email"> Email: </label>
													<input type="text" id="email" name="email" class="form-control" placeholder="Email" maxlength="50">
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

										<div class="form-group text-center">
											<div class="btn-group">
												<button type="submit" class="btn btn-xs btn-primary" id="edit-formgrupos-btn">Salvar</button>
												<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
											</div>
										</div>
									</form>
								</div>
								<div class="tab-pane fade" id="aba-membros" role="tabpanel" aria-labelledby="aba-membros-tab">

									<button onclick="adicionarPessoa()" type="button" class="btn btn-xs btn-primary">Adicionar Membro</button>


									<div class="card-body">
										<table id="data_tableMembros" class="table table-striped table-hover table-sm">
											<thead>
												<tr>
													<th>Código</th>
													<th>Nome Pessoa</th>
													<th>Telefone</th>
													<th>Email</th>
													<th>Ativo</th>

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
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php
echo view('tema/rodape');
?>
<script>
	$(function() {
		$('#data_tablegrupos').DataTable({
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('grupos/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});
	});

	function adicionarPessoa() {

		$.ajax({
			url: '<?php echo base_url('pessoas/listaDropDownPessoas') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(Pessoas) {
				$("#codPessoaAdcionar").select2({
					data: Pessoas,
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


	function ImportarGrupos() {

		Swal.fire({
			title: 'A importação de Grupos é uma via de mão única, do servidor de LDAP para o este sistema.',
			text: "Esta ação irá sincronizar os Grupos de mesmo nome como apenas 1, juntando seus objetos",
			icon: 'info',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {
			if (result.value) {

				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#importarGrupos-modal').modal('show');

				$('#data_tableImportarGrupos').DataTable({
					"bDestroy": true,
					"paging": false,
					"lengthChange": false,
					"searching": false,
					"ordering": false,
					"info": true,
					"autoWidth": false,
					"responsive": true,
					"ajax": {
						"url": '<?php echo base_url('Grupos/pegaServidoresLDAPMicrosoft') ?>',
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


	function ExportarGrupos() {

		Swal.fire({
			title: 'A exportação de Grupos é uma via de mão única que irá transferir todos os departamentis do sistema local para seus servidores LDAP.',
			text: "Esta ação irá sincronizar os Grupos de mesmo nome como apenas 1, juntando seus objetos",
			icon: 'info',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {
			if (result.value) {

				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#exportarGrupos-modal').modal('show');

				$('#data_tableExportarGrupos').DataTable({
					"bDestroy": true,
					"paging": false,
					"lengthChange": false,
					"searching": false,
					"ordering": false,
					"info": true,
					"autoWidth": false,
					"responsive": true,
					"ajax": {
						"url": '<?php echo base_url('Grupos/pegaServidoresLDAPMIcrosoftParaExportacao') ?>',
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



	function importarAgora(codServidorLDAP, descricaoServidorLDAP, ipServidorLDAP) {
		Swal.fire({
			title: 'Você tem certeza que deseja importar todos os GRUPOS existentes no servidor LDAP "' + descricaoServidorLDAP + '(' + ipServidorLDAP + ')" para o sistema local?',
			text: "Esta ação irá sincronizar os grupos de mesmo nome como apenas 1, juntando seus objetos Localmente",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar',
		}).then((result) => {
			if (result.value) {




				//EXECUTA JSON DE IMPORTAÇÃO
				$('#importarGrupos-modal').modal('hide');

				$.ajax({
					url: '<?php echo base_url('Grupos/importargrupos/') ?>',
					type: 'post',
					data: {
						codServidorLDAP: codServidorLDAP,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),

					},
					dataType: 'json',
					success: function(responseImportacao) {
						if (responseImportacao.success === true) {

							Swal.fire({
								position: 'bottom-end',
								icon: 'success',
								html: responseImportacao.messages,
								showConfirmButton: false,
								timer: 3000
							}).then(function() {

								$('#data_tablegrupos').DataTable().ajax.reload(null, false).draw(false);
							})


						}

						if (responseImportacao.erro === true) {

							Swal.fire({
								position: 'bottom-end',
								icon: 'error',
								title: responseImportacao.messages,
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

	function adicionarPessoaAgora() {

		var form = $('#addMembro-form');

		$('#addMembro-modal').modal('hide');
		$.ajax({
			url: '<?php echo base_url('grupos/addMembro') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(response) {


				if (response.JaPertence === true) {
					Swal.fire({
						position: 'bottom-end',
						icon: 'warning',
						title: response.messages,
						showConfirmButton: false,
						timer: 4000
					}).then(function() {

						$('#addMembro-modal').modal('hide');
					})
				}

				if (response.success === true) {
					$('#data_tableMembros').DataTable().ajax.reload(null, false).draw(false);

					Swal.fire({
						position: 'bottom-end',
						icon: 'success',
						title: response.messages,
						showConfirmButton: false,
						timer: 3000
					}).then(function() {})

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


	function addgrupos() {
		// reset the form 
		$("#add-formgrupos")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#add-modalgrupos').modal('show');
		// submit the add from 

		$.ajax({
			url: '<?php echo base_url('departamentos/listaDropDown') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(departamentoGrupoAdd) {

				$("#codDepartamentoAdd").select2({
					data: departamentoGrupoAdd,
				})
				$("#codDepartamentoAdd").select2({
					dropdownParent: $("#selectAdd")
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

				var form = $('#add-formgrupos');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('grupos/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#add-formgrupos-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
								$('#data_tablegrupos').DataTable().ajax.reload(null, false).draw(false);
								$('#add-modalgrupos').modal('hide');
								editgrupos(response.codGrupo);
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
						$('#add-formgrupos-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#add-formgrupos').validate();
	}

	function editgrupos(codGrupo) {
		$.ajax({
			url: '<?php echo base_url('grupos/getOne') ?>',
			type: 'post',
			data: {
				codGrupo: codGrupo,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),

			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#edit-formgrupos")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#edit-modalgrupos').modal('show');

				$("#edit-formgrupos #codGrupo").val(response.codGrupo);
				$("#edit-formgrupos #descricaoGrupo").val(response.descricaoGrupo);
				$("#edit-formgrupos #abreviacaoGrupo").val(response.abreviacaoGrupo);
				$("#edit-formgrupos #telefone").val(response.telefone);
				$("#edit-formgrupos #email").val(response.email);
				$("#edit-formgrupos #ativo").val(response.ativo);
				$("#addMembro-form #codGrupoAddMembro").val(response.codGrupo);


				$('.modal-title').text("Grupo " + response.descricaoGrupo);



				$.ajax({
					url: '<?php echo base_url('departamentos/listaDropDown') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(departamentoGrupoEdit) {
						$("#codDepartamentoEdit").select2({
							data: departamentoGrupoEdit,
						})
						$('#codDepartamentoEdit').val(response.codDepartamento); // Select the option with a value of '1'
						$('#codDepartamentoEdit').trigger('change');
$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

					}
				})



				$('#data_tableMembros').DataTable({
					"paging": true,
					"bDestroy": true,
					"lengthChange": false,
					"searching": true,
					"ordering": false,
					"info": true,
					"autoWidth": false,
					"responsive": true,
					"ajax": {
						"url": '<?php echo base_url($controller . '/membrosGrupo') ?>',
						"type": "POST",
						"dataType": "json",
						data: {
							codGrupo: response.codGrupo,
							csrf_sandra: $("#csrf_sandraPrincipal").val(),

						},
						async: "true"
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
						var form = $('#edit-formgrupos');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('grupos/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#edit-formgrupos-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
										$('#data_tablegrupos').DataTable().ajax.reload(null, false).draw(false);
										$('#edit-modalgrupos').modal('hide');
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
								$('#edit-formgrupos-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#edit-formgrupos').validate();

			}
		});
	}


	function removerMembro(codPessoa, codGrupo) {
		Swal.fire({
			title: 'Você tem certeza que deseja remover este membro do grupo?',
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
					url: '<?php echo base_url('grupos/removerMembro') ?>',
					type: 'post',
					data: {
						codGrupo: codGrupo,
						codPessoa: codPessoa,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),

					},
					dataType: 'json',
					success: function(removermembroResponse) {

						if (removermembroResponse.success === true) {
							Swal.fire({
								position: 'bottom-end',
								icon: 'success',
								title: removermembroResponse.messages,
								showConfirmButton: false,
								timer: 1500
							}).then(function() {
								$('#data_tableMembros').DataTable().ajax.reload(null, false).draw(false);
							})
						} else {
							Swal.fire({
								position: 'bottom-end',
								icon: 'error',
								title: removermembroResponse.messages,
								showConfirmButton: false,
								timer: 1500
							})


						}
					}
				});
			}
		})
	}




	function removegrupos(codGrupo) {
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
					url: '<?php echo base_url('grupos/remove') ?>',
					type: 'post',
					data: {
						codGrupo: codGrupo,
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
								$('#data_tablegrupos').DataTable().ajax.reload(null, false).draw(false);
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