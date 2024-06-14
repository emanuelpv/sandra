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
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Serviço LDAP</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary" onclick="addServicoLDAPModel()" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<table id="data_tableServicoLDAPModel" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>Código</th>
								<th>Nome Servidor</th>
								<th>Tipo</th>
								<th>IP Servidor</th>
								<th>Porta LDAP</th>
								<th>Login LDAP</th>
								<th>Senha LDAP</th>
								<th>Método Hash</th>
								<th>Forçar SSL</th>
								<th>Dn</th>
								<th>Encoding</th>
								<th>Fqdn</th>
								<th>LDAPOptProtocolVersion</th>
								<th>LDAPOptReferrals</th>
								<th>LDAPOptTimeLimit</th>
								<th>Ativo</th>
								<th>Master</th>

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
<div id="add-modalServicoLDAPModel" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar <?php echo $title ?></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="add-formServicoLDAPModel" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="codServidorLDAP" name="codServidorLDAP" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="descricaoServidorLDAP"> Nome Servidor: <span class="text-danger">*</span> </label>
								<input type="text" id="descricaoServidorLDAP" name="descricaoServidorLDAP" class="form-control" placeholder="Nome Servidor" maxlength="100" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codTipoLDAP"> Tipo: </label>
								<?php echo listboxTipoLDAP($this) ?>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="ipServidorLDAP"> IP Servidor: </label>
								<input type="text" id="ipServidorLDAP" name="ipServidorLDAP" class="form-control" placeholder="IP Servidor" maxlength="50">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="portaLDAP"> Porta LDAP: <span class="text-danger">*</span> </label>
								<input type="number" id="portaLDAP" name="portaLDAP" class="form-control" placeholder="Porta LDAP" maxlength="100" number="true" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="loginLDAP"> Login LDAP: <span class="text-danger">*</span> </label>
								<input type="text" id="loginLDAP" name="loginLDAP" class="form-control" placeholder="Login LDAP" maxlength="100" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="senhaLDAP"> Senha LDAP: <span class="text-danger">*</span> </label>
								<input type="password" id="senhaLDAP" name="senhaLDAP" class="form-control" placeholder="Senha LDAP" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="nome"> Tipo Hash de Senhas: <span class="text-danger">*</span> </label>
								<select id="tipoHash" name="tipoHash" class="custom-select" required>
									<option value="MD5">MD5</option>
									<option value="SMD5">SMD5</option>
									<option value="SHA">SHA</option>
									<option value="SSHA">SSHA</option>
									<option value="CRYPT ">CRYPT</option>
									<option value="SASL">SASL</option>
								</select>
							</div>
						</div>

						<div class="col-md-8">
							<div class="form-group">
								<label for="dn"> Dn: <span class="text-danger">*</span> </label>
								<input type="text" id="dn" name="dn" class="form-control" placeholder="DC=XXXX,DC=YYYY,DC=ZZZZ" maxlength="100" required>
							</div>
						</div>

					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="encoding"> Encoding: <span class="text-danger">*</span> </label>
								<input type="text" id="encoding" name="encoding" class="form-control" placeholder="Padrão é utf8" maxlength="20" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="fqdn"> Fqdn: <span class="text-danger">*</span> </label>
								<input type="text" id="fqdn" name="fqdn" class="form-control" placeholder="dominio.com.br" maxlength="100" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="lDAPOptProtocolVersion"> LDAPOptProtocolVersion: <span class="text-danger">*</span> </label>
								<input type="number" id="lDAPOptProtocolVersion" name="lDAPOptProtocolVersion" class="form-control" placeholder="Padrão é 3" maxlength="11" number="true" required>
							</div>
						</div>


					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="lDAPOptReferrals"> LDAPOptReferrals: <span class="text-danger">*</span> </label>
								<input type="number" id="lDAPOptReferrals" name="lDAPOptReferrals" class="form-control" placeholder="Padrão é 0" maxlength="11" number="true" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="lDAPOptTimeLimit"> LDAPOptTimeLimit: <span class="text-danger">*</span> </label>
								<input type="number" id="lDAPOptTimeLimit" name="lDAPOptTimeLimit" class="form-control" placeholder="Padrão é 0" maxlength="11" number="true" required>
							</div>
						</div>
						<div style="margin-top:30px" class="col-md-4">
							<div class="form-group">
								<div class="icheck-primary d-inline">
									<input type="checkbox" id="forcarSSLAdd" name="forcarSSL">
									<label for="forcarSSLAdd"> Forçar SSL
									</label>
								</div>
							</div>

						</div>
						
						<div style="margin-top:30px" class="col-md-4">
							<div class="form-group">
								<div class="icheck-primary d-inline">
									<input type="checkbox" id="masterAdd" name="master">
									<label for="masterAdd"> Master
									</label>
								</div>
							</div>

						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-xs btn-primary" id="add-formServicoLDAPModel-btn">Adicionar</button>
							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Add modal content -->
<div id="edit-modal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="text-center bg-primary p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualização</h4>
			</div>
			<div class="modal-body">
				<form id="edit-form" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="codServidorLDAP" name="codServidorLDAP" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="descricaoServidorLDAP"> Nome Servidor: <span class="text-danger">*</span> </label>
								<input type="text" id="descricaoServidorLDAP" name="descricaoServidorLDAP" class="form-control" placeholder="Nome Servidor" maxlength="100" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codTipoLDAP"> Tipo: </label>
								<?php echo listboxTipoLDAP($this) ?>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="ipServidorLDAP"> IP Servidor: </label>
								<input type="text" id="ipServidorLDAP" name="ipServidorLDAP" class="form-control" placeholder="IP Servidor" maxlength="50">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="portaLDAP"> Porta LDAP: <span class="text-danger">*</span> </label>
								<input type="number" id="portaLDAP" name="portaLDAP" class="form-control" placeholder="Porta LDAP" maxlength="100" number="true" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="loginLDAP"> Login LDAP: <span class="text-danger">*</span> </label>
								<input type="text" id="loginLDAP" name="loginLDAP" class="form-control" placeholder="Login LDAP" maxlength="100" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="senhaLDAP"> Senha LDAP: <span class="text-danger">*</span> </label>
								<input type="password" id="senhaLDAP" name="senhaLDAP" class="form-control" placeholder="Senha LDAP" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="nome"> Tipo Hash de Senhas: <span class="text-danger">*</span> </label>
								<select id="tipoHash" name="tipoHash" class="custom-select" required>
									<option value="MD5">MD5</option>
									<option value="SMD5">SMD5</option>
									<option value="SHA">SHA</option>
									<option value="SSHA">SSHA</option>
									<option value="CRYPT ">CRYPT</option>
									<option value="SASL">SASL</option>
								</select>
							</div>
						</div>

						<div class="col-md-8">
							<div class="form-group">
								<label for="dn"> Dn: <span class="text-danger">*</span> </label>
								<input type="text" id="dn" name="dn" class="form-control" placeholder="DC=XXXX,DC=YYYY,DC=ZZZZ" maxlength="100" required>
							</div>
						</div>

					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="encoding"> Encoding: <span class="text-danger">*</span> </label>
								<input type="text" id="encoding" name="encoding" class="form-control" placeholder="Padrão é utf8" maxlength="20" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="fqdn"> Fqdn: <span class="text-danger">*</span> </label>
								<input type="text" id="fqdn" name="fqdn" class="form-control" placeholder="dominio.com.br" maxlength="100" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="lDAPOptProtocolVersion"> LDAPOptProtocolVersion: <span class="text-danger">*</span> </label>
								<input type="number" id="lDAPOptProtocolVersion" name="lDAPOptProtocolVersion" class="form-control" placeholder="Padrão é 3" maxlength="11" number="true" required>
							</div>
						</div>


					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="lDAPOptReferrals"> LDAPOptReferrals: <span class="text-danger">*</span> </label>
								<input type="number" id="lDAPOptReferrals" name="lDAPOptReferrals" class="form-control" placeholder="Padrão é 0" maxlength="11" number="true" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="lDAPOptTimeLimit"> LDAPOptTimeLimit: <span class="text-danger">*</span> </label>
								<input type="number" id="lDAPOptTimeLimit" name="lDAPOptTimeLimit" class="form-control" placeholder="Padrão é 0" maxlength="11" number="true" required>
							</div>
						</div>

					</div>
					<div class="row">
								<div class="icheck-primary d-inline">
									<input type="checkbox" id="forcarSSL" name="forcarSSL">
									<label for="forcarSSL"> Forçar SSL
									</label>
						</div>

					</div>
					<div class="row">
								<div class="icheck-primary d-inline">
									<input type="checkbox" id="master" name="master">
									<label for="master"> Master
									</label>
						</div>

					</div>
					<div class="row">
								<div class="icheck-primary d-inline">
									<input type="checkbox" id="status" name="status">
									<label for="status"> Ativo
									</label>
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
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php
echo view('tema/rodape');
?>
<script>
	$(function() {
		$('#data_tableServicoLDAPModel').DataTable({
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('servicoLDAP/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true"
			}
		});
	});

	function addServicoLDAPModel() {
		// reset the form 
		$("#add-formServicoLDAPModel")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#add-modalServicoLDAPModel').modal('show');
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

				var form = $('#add-formServicoLDAPModel');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('servicoLDAP/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#add-formServicoLDAPModel-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
								$('#data_tableServicoLDAPModel').DataTable().ajax.reload(null, false).draw(false);
								$('#add-modalServicoLDAPModel').modal('hide');
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
						$('#add-formServicoLDAPModel-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#add-formServicoLDAPModel').validate();
	}

	function editservicoLDAP(codServidorLDAP) {
		$.ajax({
			url: '<?php echo base_url('servicoLDAP/getOne') ?>',
			type: 'post',
			data: {
				codServidorLDAP: codServidorLDAP
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#edit-form")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#edit-modal').modal('show');

				$("#edit-form #codServidorLDAP").val(response.codServidorLDAP);
				$("#edit-form #descricaoServidorLDAP").val(response.descricaoServidorLDAP);
				$("#edit-form #codTipoLDAP").val(response.codTipoLDAP);
				$("#edit-form #ipServidorLDAP").val(response.ipServidorLDAP);
				$("#edit-form #portaLDAP").val(response.portaLDAP);
				$("#edit-form #loginLDAP").val(response.loginLDAP);
				$("#edit-form #senhaLDAP").val(response.senhaLDAP);
				$("#edit-form #dn").val(response.dn);
				$("#edit-form #encoding").val(response.encoding);
				$("#edit-form #fqdn").val(response.fqdn);
				$("#edit-form #lDAPOptProtocolVersion").val(response.LDAPOptProtocolVersion);
				$("#edit-form #lDAPOptReferrals").val(response.LDAPOptReferrals);
				$("#edit-form #lDAPOptTimeLimit").val(response.LDAPOptTimeLimit);
				$("#edit-form #tipoHash").val(response.tipoHash);
				if (response.forcarSSL == 1) {
					$("#edit-form #forcarSSL").prop("checked", true);
				} else {}
			if (response.master == 1) {
					$("#edit-form #master").prop("checked", true);
				} else {}
				if (response.status == 1) {
					$("#edit-form #status").prop("checked", true);
				} else {}
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
							url: '<?php echo base_url('servicoLDAP/edit') ?>',
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
										$('#data_tableServicoLDAPModel').DataTable().ajax.reload(null, false).draw(false);
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
						});

						return false;
					}
				});
				$('#edit-form').validate();

			}
		});
	}

	function removeservicoLDAP(codServidorLDAP) {
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
					url: '<?php echo base_url('servicoLDAP/remove') ?>',
					type: 'post',
					data: {
						codServidorLDAP: codServidorLDAP
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
								$('#data_tableServicoLDAPModel').DataTable().ajax.reload(null, false).draw(false);
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