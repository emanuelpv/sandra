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
session()->set('filtroPessoa', NULL);
session()->set('filtroDesativados', 0);
?>
<!-- Main content -->

<div style="visibility:hidden" id="setEstilo"></div>
<section class="content">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-md-8 mt-2">
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Pessoas</h3>
						</div>
						<div class="col-md-12">

							<div class="form-group  text-right">
								<div class="btn-group">

									<button type="button" class="btn btn-xs btn-primary" onclick="add(<?php echo session()->codOrganizacao ?>)" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
									<button type="button" class="btn btn-xs btn-primary" onclick="ImportarPessoas()" title="Importar Pessoas"> <i class="fas fa-file-import"></i> Importar</button>
									<button type="button" class="btn btn-xs btn-primary" onclick="ExportarPessoas()" title="Exportar Pessoas"> <i class="fa fa-file-export"></i> Exportar</button>
									<button type="button" class="btn btn-xs btn-primary" onclick="ConfiguracoesPessoas()" title="Configurações Pessoas"> <i class="fa fa-file-export"></i> Configurações</button>
									<button type="button" class="btn btn-xs btn-primary" onclick="mostraLogs()" title="Logs"> <i class="fas fa-exclamation-triangle"></i> Logs</button>
									<button type="button" class="btn btn-xs btn-primary" onclick="saveVCARD()" title="vcard"> <i class="fas fa-address-book"></i>VCARD</button>

								</div>
							</div>
							<form id="filtroForm" class="pl-3 pr-3">
								<input type="hidden" id="<?php echo csrf_token() ?>filtroForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

								<div class="row">
									<div class="col-md-4">
										<div class="form-group text-left">
											<label for="codOrigemSolicitacao"> Pessoa: <span class="text-danger">*</span> </label>
											<select id="pessoaFiltro" name="codPessoa" class="custom-select" required>
												<option value=""></option>
											</select>

										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="checkboxativo"> Contas desativadas: </label>

											<div class="icheck-primary d-inline">
												<style>
													input[type=checkbox] {
														transform: scale(1.8);
													}
												</style>
												<input style="margin-left:5px;" name="desativados" id="desativados" type="checkbox">
											</div>
										</div>
									</div>
								</div>

								<div>
									<button type="button" class="btn btn-xs btn-primary" onclick="filtrar()" title="Filtrar"> <i class="fas fa-filter"></i>Filtrar</button>
								</div>

							</form>

						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<table id="data_tablepessoa" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>Código</th>
								<th>Conta</th>
								<th>Nome exibição</th>
								<th>Nome Completo</th>
								<th>Departamento</th>

								<th style="text-align:center">Ações</th>
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




<div style="width:500px" id="confirmacaoDesativacaoModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Desativação de Pessoa</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="confirmacaoDesativacaoForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>confirmacaoDesativacaoForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<input type="hidden" id="codPessoaConfirmacaoDesativacao" name="codPessoa" class="form-control" placeholder="Código" maxlength="11" required>
					<div style="font-size:14px">Você tem certeza que deseja desativar <b><span id="nomeExibicaoConfirmacaoDesativacao"></span></b>?</div>
					<div style="font-size:14px">
						Para confirmar a desativação desta pessoa é necessário classificar o motivo
					</div>

					<div class="row">
						<div class="col-sm-12">
							<div class="input-group mb-3">
								<select style="width:86%" required id="codMotivoInativo" name="codMotivoInativo" class="form-control" data-select2-id="codMotivoInativo" tabindex="-1" aria-hidden="true">
									<option value=""></option>

								</select>
							</div>
						</div>

					</div>
					<button type="button" onclick="desativarPessoaAgora()" class="btn btn-xs btn-success" id="add-form-pessoa-btn">Confirmar</button>
					<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Cancelar</button>
			</div>
		</div>
		</form>
	</div>
</div>




<div id="add-modal-Pessoa" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar <?php echo $title ?></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="add-form-pessoa" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>add-form-pessoa" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<input type="hidden" id="codPessoaAdd" name="codPessoa" class="form-control" placeholder="Código" maxlength="11" required>
					<input type="hidden" id="codOrganizacaoAdd" name="codOrganizacao" class="form-control" placeholder="CodOrganizacao" maxlength="11">
					<div class="row">
						<div class="col-sm-4">
						</div>
						<div id="mensagemConta" style="display: none;" class="col-sm-4">
							<i style="color:#FF0004;" class="fas fa-times"></i> <span style="font-size:20px; color:red"><b>Atenção:</b> esta conta já está em uso, escolha outro nome para Login</span>
						</div>
					</div>
					<?php
					echo formularioPessoaPadrao($this, 'Add');
					?>
					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-xs btn-success" id="add-form-pessoa-btn">Adicionar</button>
							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>



<div id="modalPessoasVCARD" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Lista de Pessoas VCARD</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div><span>Autor:</span><span id="listaVCARD"></span></div>

			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>


<div id="edit-modal-Pessoa" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 id="modal-titleEditarPessoa" class="modal-title text-white" id="info-header-modalLabel"></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="row">
					<div class="col-12 col-sm-12">
						<div class="card card-primary card-tabs">
							<div class="card-header p-0 pt-1">
								<ul class="nav nav-tabs" id="tab-Pessoa" role="tablist">

									<li class="nav-item">
										<a class="nav-link" id="dados-pessoais-tab" data-toggle="pill" href="#dados-pessoais" role="tab" aria-controls="dados-pessoais" aria-selected="true">DADOS PESSOAIS</a>
									</li>
								</ul>
							</div>
							<div class="card-body">
								<div class="tab-content" id="custom-tabs-one-tabContent">
									<div class="tab-pane fade show active" id="dados-pessoais" role="tabpanel" aria-labelledby="dados-pessoais-tab">
										<form id="edit-form-pessoa" class="pl-3 pr-3">
											<input type="hidden" id="<?php echo csrf_token() ?>edit-form-pessoa" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

											<input type="hidden" id="codPessoaFormAdmin" name="codPessoa" class="form-control" placeholder="Código" maxlength="11" required>
											<input type="hidden" id="codOrganizacaoFormAdmin" name="codOrganizacao" class="form-control" placeholder="Código" maxlength="11" required>

											<?php
											echo formularioPessoaPadrao($this, 'FormAdmin');
											?>

											<div class="form-group text-center">
												<div class="btn-group">
													<button type="submit" class="btn btn-xs btn-primary" id="edit-form-pessoa-btn">Salvar</button>
													<button type="button" class="btn btn-xs btn-success" id="btnTrocasenhaCodPessoa" id="btnTrocasenhaCodPessoa">Troca Senha</button>
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
			</div>
		</div>
	</div>
</div>



<div id="exportarPessoas-modal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabelExportacao">Exportação de pessoas do LDAP</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<table id="data_tableExportarPessoas" class="table table-striped table-hover table-sm">
					<thead>
						<tr>
							<th>Descricao</th>
							<th>IP</th>
							<th></th>
						</tr>
					</thead>
				</table>
				<div style="margin-top:10px">
					<button type="button" class="btn btn-xs btn-primary" onclick="exportarTudao()" title="Importar Pessoas"> <i class="fas fa-file-import"></i> PARA TODOS OS SERVIDORES</button>
				</div>
			</div>

		</div>

	</div>
</div>



<div id="importarPessoas-modal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabelImportacao">Importarção de pessoas do LDAP</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<table id="data_tableImportarPessoas" class="table table-striped table-hover table-sm">
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




<div id="logs-modal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabelImportacao">LOGS do serviço de Sincronização do LDAP</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<table style="font-size: 12px;" id="data_logs" class="table table-striped table-hover table-sm">
					<thead>
						<tr>
							<th>Código</th>
							<th>Servidor</th>
							<th>Tipo</th>
							<th>Autor</th>
							<th>Resultado</th>
							<th>Data/Hora</th>
							<th>IP</th>
							<th>Ocorrência</th>
						</tr>
					</thead>
				</table>

			</div>

		</div>

	</div>
</div>



<div id="tcleModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div id="print-me">
				<div class="modal-header bg-primary text-center p-3">
					<h4 class="modal-title text-white" id="info-header-modalLabel">TCLE</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-2 text-right">
							<div class="form-group">
								<button style="margin-left:10px" type="button" class="btn btn-block btn-outline-primary btn-lg" onclick="imprimirTcle()" title="Imprimir TCLE">
									<div><i class="fas fa-print fa-1x" aria-hidden="true"></i></div>
									Imprimir
								</button>
							</div>
						</div>
					</div>


					<div style="margin-left:60px;margin-right:60px" id="areaImpressaoTcle">

						<div style="margin-bottom:10px;margin-top:10px;" class="row">
							<div class="col-md-12">
								<img class="float-right" src="<?php echo base_url() . "/imagens/cesarSchool.png" ?>">
							</div>
						</div>


						<div style="margin-top:50px;margin-bottom:20px;font-weight: bold; font-size:20px" class="row">
							<div class="col-md-12 d-flex justify-content-center">
								TERMO DE CONSENTIMENTO LIVRE E ESCLARECIDO
							</div>
						</div>

						<div style="margin-top:20px;margin-bottom:20px; font-size:15px" class="row">
							<div class="col-md-12 d-flex text-justify">
								<div id="dadosTcle"></div>
							</div>
						</div>


						<div style="margin-top:40px;margin-bottom:20px; font-size:12px" class="row">
							<div class="col-md-12 d-flex justify-content-center">
								<center>CESAR School | Cais do Apolo, 77, Bairro do Recife - Recife/PE CEP: 50030-390 - Fone: +55 (81) 3419.6700</center>
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
<script>
	$(document).on('show.bs.modal', '.modal', function() {
		var zIndex = 1040 + (10 * $('.modal:visible').length);
		$(this).css('z-index', zIndex);
		setTimeout(function() {
			$('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
		}, 0);
	});



	var codPessoaTmp = "";

	

	$(function() {

		$('#data_tablepessoa').DataTable({
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
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




	$.ajax({
		url: '<?php echo base_url('pessoas/listaDropDownSolicitante') ?>',
		type: 'post',
		dataType: 'json',
		data: {
			csrf_sandra: $("#csrf_sandraPrincipal").val(),
		},
		success: function(pessoaFiltro) {

			$("#pessoaFiltro").select2({
				data: pessoaFiltro,
				allowClear: true,
				placeholder: 'Procurar Pessoa',
				language: {
					noResults: function() {
						return `<button style="width: 100%" type="button"
            class="btn btn-xs btn-primary" 
            onClick='add(<?php echo session()->codOrganizacao ?>)'>+ Adicionar Pessoa</button>
            `;
					}
				},

				escapeMarkup: function(markup) {
					return markup;
				}

			})
			$(document).on('select2:open', () => {
				document.querySelector('.select2-search__field').focus();
			});



		}
	})



	function task() {
		alert("Hello world! ");
	}



	function add(codOrganizacao) {
		// reset the form 
		$("#add-form-pessoa")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#add-modal-Pessoa').modal('show');

		$("#add-form-pessoa #codMunicipioFederacaoAdd").select2();
		$("#add-form-pessoa #codDepartamentoAdd").select2();
		$("#add-form-pessoa #codEspecialidadeAdd").select2();
		$("#add-form-pessoa #codOrganizacaoAdd").val(codOrganizacao);

		var nomeExibicaoSistema = <?php echo session()->nomeExibicaoSistema; ?>


		$("#contaAdd").attr("onkeyup", "verificaConta()");

		document.getElementById("ativoAdd").checked = true;




		if (nomeExibicaoSistema == 2) {
			document.getElementById('nomeExibicaoAdd').readOnly = true;
			$("#add-form-pessoa #nomeExibicaoAdd").val('Automático');
			$('#nomeCompletoAdd').change(function() {
				$("#add-form-pessoa #nomeExibicaoAdd").val($("#add-form-pessoa #nomeCompletoAdd").val());

			})
		}


		if (nomeExibicaoSistema == 3) {
			document.getElementById('nomeExibicaoAdd').readOnly = true;
			$("#add-form-pessoa #nomeExibicaoAdd").val('Automático');
			$('#nomePrincipalAdd').change(function() {
				$("#add-form-pessoa #nomeExibicaoAdd").val($("#add-form-pessoa #nomePrincipalAdd").val());

			})
		}


		if (nomeExibicaoSistema == 4) {
			document.getElementById('nomeExibicaoAdd').readOnly = true;
			$("#add-form-pessoa #nomeExibicaoAdd").val('Automático');
			var siglaCargo = "";
			$('#codCargoAdd').change(function() {

				$.ajax({
					url: '<?php echo base_url('cargos/getOne') ?>',
					type: 'post',
					data: {
						codCargo: this.value,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),

					},
					dataType: 'json',
					success: function(cargo) {
						$("#add-form-pessoa #nomeExibicaoAdd").val($("#add-form-pessoa #nomePrincipalAdd").val() + ' - ' + cargo.siglaCargo);
					}
				})
			})

			$('#nomePrincipalAdd').change(function() {
				$.ajax({
					url: '<?php echo base_url('cargos/getOne') ?>',
					type: 'post',
					data: {
						codCargo: $("#add-form-pessoa #codCargoAdd").val(),
						csrf_sandra: $("#csrf_sandraPrincipal").val(),

					},
					dataType: 'json',
					success: function(cargo) {
						$("#add-form-pessoa #nomeExibicaoAdd").val($("#add-form-pessoa #nomePrincipalAdd").val() + ' - ' + cargo.siglaCargo);
					}
				})


				$("#add-form-pessoa #nomeExibicaoAdd").val($("#add-form-pessoa #nomePrincipalAdd").val() + ' - ' + cargo.siglaCargo);

			})

		}

		if (nomeExibicaoSistema == 5) {
			document.getElementById('nomeExibicaoAdd').readOnly = true;
			$("#add-form-pessoa #nomeExibicaoAdd").val('Automático');
			var siglaCargo = "";
			$('#codCargoAdd').change(function() {

				$.ajax({
					url: '<?php echo base_url('cargos/getOne') ?>',
					type: 'post',
					data: {
						codCargo: this.value,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),

					},
					dataType: 'json',
					success: function(cargo) {
						$("#add-form-pessoa #nomeExibicaoAdd").val(cargo.siglaCargo + ' ' + $("#add-form-pessoa #nomePrincipalAdd").val());
					}
				})
			})

			$('#nomePrincipalAdd').change(function() {
				$.ajax({
					url: '<?php echo base_url('cargos/getOne') ?>',
					type: 'post',
					data: {
						codCargo: $("#add-form-pessoa #codCargoAdd").val(),
						csrf_sandra: $("#csrf_sandraPrincipal").val(),

					},
					dataType: 'json',
					success: function(cargo) {
						$("#add-form-pessoa #nomeExibicaoAdd").val(cargo.siglaCargo + ' ' + $("#add-form-pessoa #nomePrincipalAdd").val());
					}
				})


				$("#add-form-pessoa #nomeExibicaoAdd").val(cargo.siglaCargo + ' ' + $("#add-form-pessoa #nomePrincipalAdd").val());

			})

		}

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

				var form = $('#add-form-pessoa');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url($controller . '/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',

					success: function(responseAdd) {

						if (responseAdd.success === 'contaExistente') {

							Swal.fire({
								position: 'bottom-end',
								icon: 'error',
								title: responseAdd.mensagem,
								showConfirmButton: false,
								timer: 3000
							})
						}

						if (responseAdd.success === true) {

							Swal.fire({
								position: 'bottom-end',
								icon: 'success',
								title: responseAdd.messages,
								html: "Caso essa pessoa precise estar nos servidores LDAP, você deve sincronizar a conta!",
								showConfirmButton: true,
								confirmButtonText: 'Ok',
							}).then(function() {
								$('#data_tablepessoa').DataTable().ajax.reload(null, false).draw(false);
								$('#add-modal-Pessoa').modal('hide');
							})

						}
						if (responseAdd.messages instanceof Object) {
							$.each(responseAdd.messages, function(index, value) {
								var id = $("#" + index);

								id.closest('.form-control')
									.removeClass('is-invalid')
									.removeClass('is-valid')
									.addClass(value.length > 0 ? 'is-invalid' : 'is-valid');

								id.after(value);

							});
						}

						if (responseAdd.success === false) {

							Swal.fire({
								position: 'bottom-end',
								icon: 'error',
								title: responseAdd.messages,
								showConfirmButton: false,
								timer: 1500
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

				return false;
			}
		});
		$('#add-form-pessoa').validate();
	}





	function importarAgora(codServidorLDAP, descricaoServidorLDAP, ipServidorLDAP) {
		Swal.fire({
			title: 'Você tem certeza que deseja trazer todas as contas do servidor LDAP "' + descricaoServidorLDAP + '(' + ipServidorLDAP + ')" para o sistema local?',
			text: "Esta ação irá sobrescrever os dados de usuários locais, com exceção da senha",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar',
		}).then((result) => {
			if (result.value) {




				//EXECUTA JSON DE IMPORTAÇÃO
				$('#importarPessoas-modal').modal('hide');
				$.ajax({
					url: '<?php echo base_url('pessoas/importarPessoas/') ?>',
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

								$('#data_tablepessoa').DataTable().ajax.reload(null, false).draw(false);
								//$('#editPessoaLogada').modal('hide');
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




	function mostraLogs() {

		swal.close();
		$('#logs-modal').modal('show');

		$('#data_logs').DataTable({
			"order": [
				[0, "desc"]
			],
			"bDestroy": true,
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('pessoas/pegalogs') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});





	}


	function mostrartcle(codPessoa) {

		$('#tcleModal').modal('show');


		document.getElementById("dadosTcle").innerHTML = '';

		$.ajax({
			url: '<?php echo base_url('pessoas/dadosTcle') ?>',
			type: 'post',
			data: {
				codPessoa: codPessoa,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),

			},
			dataType: 'json',
			success: function(response) {
				document.getElementById("dadosTcle").innerHTML = response.dadosTcle;
			}
		})


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


	function imprimirTcle() {

		
		$('#tcleModal').modal('hide');
		printElement(document.getElementById("areaImpressaoTcle"));
		window.print();
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

	function ImportarPessoas() {

		Swal.fire({
			title: 'A importação de pessoas é uma via de mão única, do servidor de LDAP para o este sistema.',
			text: "Esta ação irá sobrescrever os dados de usuários locais, com exceção da senha",
			icon: 'info',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {
			if (result.value) {

				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#importarPessoas-modal').modal('show');

				$('#data_tableImportarPessoas').DataTable({
					"bDestroy": true,
					"paging": false,
					"lengthChange": false,
					"searching": false,
					"ordering": false,
					"info": true,
					"autoWidth": false,
					"responsive": true,
					"ajax": {
						"url": '<?php echo base_url('pessoas/pegaServidoresLDAP') ?>',
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


	function exportarAgora(codServidorLDAP, descricaoServidorLDAP, ipServidorLDAP) {
		Swal.fire({
			title: 'Você tem certeza que deseja enviar todas as contas do servidor local para o servidor LDAP "' + descricaoServidorLDAP + '(' + ipServidorLDAP + ')" ?',
			text: "Esta ação irá atualizar todos os usuários preexistentes, bem como, seus atributos, com exceção da senha",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar',
		}).then((result) => {
			if (result.value) {




				//EXECUTA JSON DE IMPORTAÇÃO
				$.ajax({
					url: '<?php echo base_url('pessoas/exportarTodasPessoas/') ?>',
					type: 'post',
					data: {
						codServidorLDAP: codServidorLDAP,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),

					},
					dataType: 'json',
					beforeSend: function() {


						Swal.fire({
							title: 'Estamos processando sua requisição',
							html: 'Aguarde....',
							timerProgressBar: true,
							didOpen: () => {
								Swal.showLoading()


							}

						})

					},
					success: function(response) {
						if (response.success === true) {

							Swal.fire({
								position: 'bottom-end',
								icon: 'success',
								html: response.messages,
								showConfirmButton: true,
								confirmButtonText: 'Ok',
							})


						}

						if (response.success === false) {

							Swal.fire({
								position: 'bottom-end',
								icon: 'error',
								html: response.messages,
								showConfirmButton: true,
								confirmButtonText: 'Ok',
							})


						}



						if (response.success === 'parcial') {

							Swal.fire({
								position: 'bottom-end',
								icon: 'warning',
								html: response.messages,
								showConfirmButton: true,
								confirmButtonText: 'Ok',
							})


						}

					}
				})


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



	function filtrar() {



		var form = $('#filtroForm');
		$.ajax({
			url: '<?php echo base_url('pessoas/filtrar') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(filtrar) {

				if (filtrar.success === true) {

					$('#data_tablepessoa').DataTable().ajax.reload(null, false).draw(false);

				}
			}
		})
	}


	function saveVCARD() {




		$.ajax({
			url: '<?php echo base_url('/pessoas/gerarVCARD') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(response) {

				if (response.success === true) {

					$('#modalPessoasVCARD').modal('show');

					document.getElementById("listaVCARD").innerHTML = response.contatos;




					/*
					
										var pom = document.createElement('a');
					pom.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(response.contatos));
					pom.setAttribute('download', "test.vcf");

					if (document.createEvent) {
						var event = document.createEvent('MouseEvents');
						event.initEvent('click', true, true);
						pom.dispatchEvent(event);
					} else {
						pom.click();
					}


					*/


				}

				if (response.success === false) {
					alert('nenhum contato');
				}


			}
		})





	}

	function exportarTudao() {
		Swal.fire({
			title: 'Você tem certeza que deseja enviar todas as contas do servidor local para o TODOS os servidores cadastrados no sistema?',
			text: "Esta ação irá atualizar todos os usuários preexistentes, bem como, seus atributos, com exceção da senha",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar',
		}).then((result) => {
			if (result.value) {




				//EXECUTA JSON DE IMPORTAÇÃO
				$.ajax({
					url: '<?php echo base_url('pessoas/exportarTodasPessoas/') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					beforeSend: function() {


						Swal.fire({
							title: 'Estamos processando sua requisição',
							html: 'Aguarde....',
							timerProgressBar: true,
							didOpen: () => {
								Swal.showLoading()


							}

						})

					},
					success: function(response) {
						if (response.success === true) {

							Swal.fire({
								position: 'bottom-end',
								icon: 'success',
								html: response.messages,
								showConfirmButton: true,
								confirmButtonText: 'Ok',
							})


						}

						if (response.success === false) {

							Swal.fire({
								position: 'bottom-end',
								icon: 'error',
								html: response.messages,
								showConfirmButton: true,
								confirmButtonText: 'Ok',
							})

						}
						if (response.success === 'parcial') {

							Swal.fire({
								position: 'bottom-end',
								icon: 'warning',
								html: response.messages,
								showConfirmButton: true,
								confirmButtonText: 'Ok',
							})

						}
					}
				})


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



	function ExportarPessoas() {

		Swal.fire({
			title: 'A exportação de pessoas é uma via de mão única que irá transferir todos os usuários do sistema local para seus servidores LDAP cadastrados.',
			text: "Esta ação irá sobrescrever os dados de usuários em seu(s) servidor(es) LDAP, você tem certeza desta ação?",
			icon: 'info',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {
			if (result.value) {

				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#exportarPessoas-modal').modal('show');

				$('#data_tableExportarPessoas').DataTable({
					"bDestroy": true,
					"paging": false,
					"lengthChange": false,
					"searching": false,
					"ordering": false,
					"info": true,
					"autoWidth": false,
					"responsive": true,
					"ajax": {
						"url": '<?php echo base_url('pessoas/pegaServidoresLDAPParaExportacao') ?>',
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



	function edit(codPessoa) {




		document.getElementById("btnTrocasenhaCodPessoa").onclick =
			function() {
				trocasenha(codPessoa)
			}




		$.ajax({
			url: '<?php echo base_url('pessoas/getOne') ?>',
			type: 'post',
			data: {
				codPessoa: codPessoa,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),

			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#edit-form-pessoa")[0].reset();
				$('#edit-modal-Pessoa').modal('show');
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				//$(".invalid-feedback").remove();

				var statusFotoPerfil = document.getElementById("fotoPerfilFormAdmin");
				if (statusFotoPerfil) {
					fotoPerfilFormAdmin.onchange = evt => {
						const [file] = fotoPerfilFormAdmin.files
						if (file) {
							fotoPerfilFormularioFormAdmin.src = URL.createObjectURL(file)
						}
					}
				}




				codPessoaTmp = codPessoa;
				$('#data_tablefuncoesAtribuidas').DataTable({
					"paging": true,
					"lengthChange": false,
					"bDestroy": true,
					"searching": true,
					"ordering": true,
					"info": true,
					"autoWidth": false,
					"responsive": true,
					"ajax": {
						"url": '<?php echo base_url('funcoesAtribuidas/pegaFuncoesPessoa/') ?>' + "/" + codPessoa,
						"type": "POST",
						"dataType": "json",
						async: "true",
						data: {
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},
					}
				});

				$('#modal-titleEditarPessoa').text(response.nomeExibicao);



				document.getElementById('contaFormAdmin').readOnly = true;

				var nomeExibicaoSistema = <?php echo session()->nomeExibicaoSistema; ?>

				if (nomeExibicaoSistema == 1) {
					$("#edit-form-pessoa #nomeExibicaoFormAdmin").val(response.nomeExibicao);

				}


				if (nomeExibicaoSistema == 2) {
					document.getElementById('nomeExibicaoFormAdmin').readOnly = true;
					$("#edit-form-pessoa #nomeExibicaoFormAdmin").val(response.nomeCompleto);
					$('#nomeCompletoFormAdmin').change(function() {
						$("#edit-form-pessoa #nomeExibicaoFormAdmin").val($("#edit-form-pessoa #nomeCompletoFormAdmin").val());

					})
				}


				if (nomeExibicaoSistema == 3) {
					document.getElementById('nomeExibicaoFormAdmin').readOnly = true;
					$("#edit-form-pessoa #nomeExibicaoFormAdmin").val(response.nomePrincipal);
					$('#nomePrincipalFormAdmin').change(function() {
						$("#edit-form-pessoa #nomeExibicaoFormAdmin").val($("#edit-form-pessoa #nomePrincipalFormAdmin").val());

					})
				}


				if (nomeExibicaoSistema == 4) {
					$("#edit-form-pessoa #nomeExibicaoFormAdmin").val(response.nomeExibicao);
					document.getElementById('nomeExibicaoFormAdmin').readOnly = true;
					var siglaCargo = "";
					$('#codCargoFormAdmin').change(function() {

						$.ajax({
							url: '<?php echo base_url('cargos/getOne') ?>',
							type: 'post',
							data: {
								codCargo: this.value,
								csrf_sandra: $("#csrf_sandraPrincipal").val(),

							},
							dataType: 'json',
							success: function(cargo) {
								$("#edit-form-pessoa #nomeExibicaoFormAdmin").val($("#edit-form-pessoa #nomePrincipalFormAdmin").val() + ' - ' + cargo.siglaCargo);
							}
						})
					})

					$('#nomePrincipalFormAdmin').change(function() {
						$.ajax({
							url: '<?php echo base_url('cargos/getOne') ?>',
							type: 'post',
							data: {
								codCargo: $("#edit-form-pessoa #codCargoFormAdmin").val(),
								csrf_sandra: $("#csrf_sandraPrincipal").val(),

							},
							dataType: 'json',
							success: function(cargo) {
								$("#edit-form-pessoa #nomeExibicaoFormAdmin").val($("#edit-form-pessoa #nomePrincipalFormAdmin").val() + ' - ' + cargo.siglaCargo);
							}
						})


						$("#edit-form-pessoa #nomeExibicaoFormAdmin").val($("#edit-form-pessoa #nomePrincipalFormAdmin").val() + ' - ' + cargo.siglaCargo);

					})

				}

				if (nomeExibicaoSistema == 5) {
					$("#edit-form-pessoa #nomeExibicaoFormAdmin").val(response.nomeExibicao);
					document.getElementById('nomeExibicaoFormAdmin').readOnly = true;
					var siglaCargo = "";
					$('#codCargoFormAdmin').change(function() {

						$.ajax({
							url: '<?php echo base_url('cargos/getOne') ?>',
							type: 'post',
							data: {
								codCargo: this.value,
								csrf_sandra: $("#csrf_sandraPrincipal").val(),

							},
							dataType: 'json',
							success: function(cargo) {
								$("#edit-form-pessoa #nomeExibicaoFormAdmin").val(cargo.siglaCargo + ' ' + $("#edit-form-pessoa #nomePrincipalFormAdmin").val());
							}
						})
					})

					$('#nomePrincipalFormAdmin').change(function() {
						$.ajax({
							url: '<?php echo base_url('cargos/getOne') ?>',
							type: 'post',
							data: {
								codCargo: $("#edit-form-pessoa #codCargoFormAdmin").val(),
								csrf_sandra: $("#csrf_sandraPrincipal").val(),

							},
							dataType: 'json',
							success: function(cargo) {
								$("#edit-form-pessoa #nomeExibicaoFormAdmin").val(cargo.siglaCargo + ' ' + $("#edit-form-pessoa #nomePrincipalFormAdmin").val());
							}
						})


						$("#edit-form-pessoa #nomeExibicaoFormAdmin").val(cargo.siglaCargo + ' ' + $("#edit-form-pessoa #nomePrincipalFormAdmin").val());

					})

				}

				$("#edit-form-pessoa #codPessoaFormAdmin").val(response.codPessoa);
				$("#edit-form-pessoa #codOrganizacaoFormAdmin").val(response.codOrganizacao);
				$("#edit-form-pessoa #codDepartamentoFormAdmin").val(response.codDepartamento).select2();
				$("#edit-form-pessoa #codFuncaoFormAdmin").val(response.codFuncao).select2();
				$("#edit-form-pessoa #codCargoFormAdmin").val(response.codCargo);
				$("#edit-form-pessoa #contaFormAdmin").val(response.conta);
				$("#edit-form-pessoa #nomeCompletoFormAdmin").val(response.nomeCompleto);
				$("#edit-form-pessoa #nomePrincipalFormAdmin").val(response.nomePrincipal);
				$("#edit-form-pessoa #identidadeFormAdmin").val(response.identidade);
				$("#edit-form-pessoa #cpfFormAdmin").val(response.cpf);
				$("#edit-form-pessoa #codPlanoFormAdmin").val(response.codPlano);
				$("#edit-form-pessoa #emailFuncionalFormAdmin").val(response.emailFuncional);
				$("#edit-form-pessoa #emailPessoalFormAdmin").val(response.emailPessoal);
				$("#edit-form-pessoa #codEspecialidadeFormAdmin").val(response.codEspecialidade).select2();
				$("#edit-form-pessoa #telefoneTrabalhoFormAdmin").val(response.telefoneTrabalho);
				$("#edit-form-pessoa #celularFormAdmin").val(response.celular);
				$("#edit-form-pessoa #enderecoFormAdmin").val(response.endereco);
				$("#edit-form-pessoa #dataInicioEmpresaFormAdmin").val(response.dataInicioEmpresa);
				$("#edit-form-pessoa #dataNascimentoFormAdmin").val(response.dataNascimento);
				$("#edit-form-pessoa #nrEnderecoFormAdmin").val(response.nrEndereco);
				$("#edit-form-pessoa #codMunicipioFederacaoFormAdmin").val(response.codMunicipioFederacao).select2();
				$("#edit-form-pessoa #cepFormAdmin").val(response.cep);
				$("#edit-form-pessoa #codPerfilPadraoFormAdmin").val(response.codPerfilPadrao);
				$("#edit-form-pessoa #informacoesComplementaresFormAdmin").val(response.informacoesComplementares);
				$("#edit-form-pessoa #paiFormAdmin").val(response.pai);


				var statusFotoPerfil = document.getElementById("fotoPerfilFormAdmin");
				if (statusFotoPerfil) {
					if (response.fotoPerfil == null) {
						document.getElementById("fotoPerfilFormularioFormAdmin").src = "<?php echo  "arquivos/imagens/pessoas/no_image.jpg?" ?>" + new Date().getTime();

					} else {
						document.getElementById("fotoPerfilFormularioFormAdmin").src = "<?php echo "arquivos/imagens/pessoas/" ?>" + response.fotoPerfil + "?" + new Date().getTime();

					}
				}




				if (response.ativo == 1) {

					$("#edit-form-pessoa #ativoFormAdmin").attr('checked', true);
				}
				if (response.aceiteTermos == 1) {

					$("#edit-form-pessoa #aceiteTermosFormAdmin").attr('checked', true);
				}

				$("#edit-form-pessoa #dataInicioEmpresaFormAdmin").val(response.dataInicioEmpresa);


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



						var statusFotoPerfil = document.getElementById("fotoPerfilFormAdmin");
						if ($('#fotoPerfilFormAdmin')[0].files[0] !== 'undefined') {


							var formData = new FormData();
							formData.append('file', $('#fotoPerfilFormAdmin')[0].files[0]);
							formData.append('codPessoa', codPessoaTmp);
							formData.append('csrf_sandra', $("#csrf_sandraPrincipal").val());
							$.ajax({
								url: 'pessoas/enviaFoto',
								type: 'post',
								dataType: 'json',
								data: formData,
								processData: false, // tell jQuery not to process the data
								contentType: false, // tell jQuery not to set contentType
								success: function(resultFoto) {
									if (resultFoto.success == true) {
										document.getElementById("fotoPerfilFormularioFormAdmin").src = "<?php echo "arquivos/imagens/pessoas/" ?>" + resultFoto.nomeArquivo + "?" + new Date().getTime();

										if (resultFoto.meuCodPessoa == codPessoaTmp) {
											document.getElementById("fotoPerfilBarraSuperior").src = "<?php echo  "arquivos/imagens/pessoas/" ?>" + resultFoto.nomeArquivo + "?" + new Date().getTime();

										}
									}


								},
							});


						}


						var form = $('#edit-form-pessoa');


						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('pessoas/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {

								Swal.fire({
									title: 'Estamos processando sua requisição',
									html: 'Aguarde....',
									timerProgressBar: true,
									timer: 3000,
									didOpen: () => {
										Swal.showLoading()
									}

								})

							},
							success: function(response) {


								Swal.fire({
									position: 'bottom-end',
									icon: 'success',
									html: response.messages,
									showConfirmButton: false,
									timer: 2000
								}).then(function() {
									$('#data_tablepessoa').DataTable().ajax.reload(null, false).draw(false);
									//$('#editPessoaLogada').modal('hide');
								})




								if (response.success === true) {

									//EXPORTA PARA LDAP
									$.ajax({
										url: '<?php echo base_url('pessoas/exportarPessoa') ?>',
										type: 'post',
										data: {
											codPessoa: codPessoaTmp,
											csrf_sandra: $("#csrf_sandraPrincipal").val(),
										},
										dataType: 'json',

										success: function(responseLDAP) {
											if (responseLDAP.LDAPDisable !== true) {
												if (responseLDAP.success === true) {
													Swal.fire({
														position: 'bottom-end',
														icon: 'success',
														html: responseLDAP.messages,
														showConfirmButton: true,
														confirmButtonText: 'Ok',
													}).then(function() {
														$('#data_tablepessoa').DataTable().ajax.reload(null, false).draw(false);
														//$('#editPessoaLogada').modal('hide');
													})

												}

												if (responseLDAP.success === 'parcial') {
													Swal.fire({
														position: 'bottom-end',
														icon: 'warning',
														html: responseLDAP.messages,
														showConfirmButton: true,
														confirmButtonText: 'Ok',
													})

												}
												if (responseLDAP.success === 'false') {
													Swal.fire({
														position: 'bottom-end',
														icon: 'error',
														html: responseLDAP.messages,
														showConfirmButton: true,
														confirmButtonText: 'Ok',
													})

												}
											}
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
											timer: 5000
										})

									}
								}
								$('#edit-form-pessoa-btn').html('Salvar');
							}
						})

						return false;
					}
				});
				$('#edit-form-pessoa').validate();

			}
		});
	}

	function remove(codPessoa) {


		Swal.fire({
			position: 'bottom-end',
			icon: 'warning',
			title: 'Funcionalidade desativada',
			html: 'Não é possível remover pessoas do sistema.',
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
					url: '<?php echo base_url($controller . '/remove') ?>',
					type: 'post',
					data: {
						codPessoa: codPessoa
					}
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
								$('#data_tablepessoa').DataTable().ajax.reload(null, false).draw(false);
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

		*/
	}


	function confirmacaoDesativacao(codPessoa, nomeExibicao) {
		$("#confirmacaoDesativacaoForm")[0].reset();
		$('#confirmacaoDesativacaoModal').modal('show');
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');

		$("#confirmacaoDesativacaoForm #codPessoaConfirmacaoDesativacao").val(codPessoa);


		document.getElementById('nomeExibicaoConfirmacaoDesativacao').innerHTML = '<span>"' + nomeExibicao + '"</span>';



		$.ajax({
			url: '<?php echo base_url('pessoas/listaDropDownMotivosInativos') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(motivosInativacao) {

				$("#codMotivoInativo").select2({
					data: motivosInativacao,
				})

				$('#codMotivoInativo').val(null); // Select the option with a value of '1'
				$('#codMotivoInativo').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})


	}

	function desativarPessoaAgora() {
		$('#confirmacaoDesativacaoModal').modal('hide');
		codPessoa = $("#confirmacaoDesativacaoForm #codPessoaConfirmacaoDesativacao").val();
		codMotivoInativo = $("#confirmacaoDesativacaoForm #codMotivoInativo").val();


		$.ajax({
			url: '<?php echo base_url('/pessoas/desativarpessoa') ?>',
			type: 'post',
			data: {
				codPessoa: codPessoa,
				codMotivoInativo: codMotivoInativo,
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
						timer: 3000
					}).then(function() {
						$('#data_tablepessoa').DataTable().ajax.reload(null, false).draw(false);
					})
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


	function resincronizarPessoa(codPessoa) {
		Swal.fire({
			title: 'Você tem certeza que deseja resincronizar esta pessoa em todos os servidores de LDAP?',
			text: "Esta ação replicará os objetos deste usuário para todos os servidores",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {
			if (result.value) {
				$.ajax({
					url: '<?php echo base_url('/pessoas/exportarPessoa') ?>',
					type: 'post',
					data: {
						codPessoa: codPessoa,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),

					},
					dataType: 'json',
					beforeSend: function() {


						Swal.fire({
							title: 'Estamos processando sua requisição',
							html: 'Aguarde....',
							timerProgressBar: true,
							didOpen: () => {
								Swal.showLoading()


							}

						})

					},

					success: function(response) {

						if (response.success === true) {
							Swal.fire({
								position: 'bottom-end',
								icon: 'success',
								html: response.messages,
								showConfirmButton: true,
								confirmButtonText: 'Ok',
								//timer: 1500
							}).then(function() {
								$('#data_tablepessoa').DataTable().ajax.reload(null, false).draw(false);
							})
						}


						if (response.success === false) {
							Swal.fire({
								position: 'bottom-end',
								icon: 'error',
								html: response.messages,
								showConfirmButton: true,
								confirmButtonText: 'Ok',
							})


						}

						if (response.success === 'parcial') {
							Swal.fire({
								position: 'bottom-end',
								icon: 'warning',
								html: response.messages,
								showConfirmButton: true,
								confirmButtonText: 'Ok',
							})


						}




					}
				});
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
								$('#data_tablepessoa').DataTable().ajax.reload(null, false).draw(false);
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
						title: 'Estamos processando sua requisição',
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
	function addFuncoesAtribuidasModel() {
		// reset the form 
		$("#add-formFuncoesAtribuidasModal")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#add-modalFuncoesAtribuidasModel').modal('show');


		$("#add-formFuncoesAtribuidasModal #codPessoa").val(codPessoaTmp);
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

				var form = $('#add-formFuncoesAtribuidasModal');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('funcoesAtribuidas/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#add-formFuncoesAtribuidasModal-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
								$('#data_tablefuncoesAtribuidas').DataTable().ajax.reload(null, false).draw(false);
								$('#add-modalFuncoesAtribuidasModel').modal('hide');
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
						$('#add-formFuncoesAtribuidasModal-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#add-formFuncoesAtribuidasModal').validate();
	}

	function editfuncoesAtribuidas(codPessoaFuncao) {
		$.ajax({
			url: '<?php echo base_url('funcoesAtribuidas/getOne') ?>',
			type: 'post',
			data: {
				codPessoaFuncao: codPessoaFuncao,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),

			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#edit-form-funcoes-atribuidas")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#edit-modalFuncoesAtribuidasModel').modal('show');
				$("#edit-form-funcoes-atribuidas #codPessoaFuncao").val(response.codPessoaFuncao);
				$("#edit-form-funcoes-atribuidas #codPessoa").val(response.codPessoa)
				$("#edit-form-funcoes-atribuidas #codFuncaoeditOutraFuncao").val(response.codFuncao).select2();
				$("#edit-form-funcoes-atribuidas #dataInicio").val(response.dataInicio);
				$("#edit-form-funcoes-atribuidas #dataEncerramento").val(response.dataEncerramento);

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
						var form = $('#edit-form-funcoes-atribuidas');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('funcoesAtribuidas/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#edit-form-funcoes-atribuidas-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
										$('#data_tablefuncoesAtribuidas').DataTable().ajax.reload(null, false).draw(false);
										$('#edit-modalFuncoesAtribuidasModel').modal('hide');
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
								$('#edit-form-funcoes-atribuidas-btn').html('Salvar');
							}
						});

						return false;
					}


				});
				$('#edit-form-funcoes-atribuidas').validate();

			}
		});
	}

	function removefuncoesAtribuidas(codPessoaFuncao) {
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
					url: '<?php echo base_url('funcoesAtribuidas/remove') ?>',
					type: 'post',
					data: {
						codPessoaFuncao: codPessoaFuncao,
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
								$('#data_tablefuncoesAtribuidas').DataTable().ajax.reload(null, false).draw(false);
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