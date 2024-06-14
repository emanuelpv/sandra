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


<!-- /.content -->
<?php
echo view('tema/rodape');
?>
<script>
	$(function() {



		var Toast = Swal.mixin({
			toast: true,
			position: 'top-end',
			showConfirmButton: false,
			timer: 5000
		});

		$('.swalvisivelFormulario').click(function() {
			Toast.fire({
				icon: 'info',
				title: 'Mostra esta campo no fomulário do usuário'
			})
		});

		$('.swalvisivelLDAP').click(function() {
			Toast.fire({
				icon: 'info',
				title: 'Mostra esta campo na integração deste sistema com o LDAP da sua Organização'
			})
		});

		$('.swalobrigatorio').click(function() {
			Toast.fire({
				icon: 'info',
				title: 'Mostra esta campo é obrigatório nos formulários'
			})
		});





		$('#data_tableatributosSistemaOrganizacao').DataTable({
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('atributosSistemaOrganizacao/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true"
			}
		});
	});



	function editatributosSistemaOrganizacao(codAtributosSistemaOrganizacao) {
		$.ajax({
			url: '<?php echo base_url('atributosSistemaOrganizacao/getOne') ?>',
			type: 'post',
			data: {
				codAtributosSistemaOrganizacao: codAtributosSistemaOrganizacao
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#edit-formatributosSistemaOrganizacao")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#edit-modalatributosSistemaOrganizacao').modal('show');

				$("#edit-formatributosSistemaOrganizacao #codAtributosSistemaOrganizacao").val(response.codAtributosSistemaOrganizacao);
				$("#edit-formatributosSistemaOrganizacao #codOrganizacao").val(response.codOrganizacao);
				$("#edit-formatributosSistemaOrganizacao #nomeAtributoSistema").val(response.nomeAtributoSistema);
				$("#edit-formatributosSistemaOrganizacao #descricaoAtributoSistema").val(response.descricaoAtributoSistema);

				if (response.visivelFormulario == 1) {
					document.getElementById("visivelFormulario").checked = true;
				}
				if (response.obrigatorio == 1) {
					document.getElementById("obrigatorio").checked = true;
				}
				if (response.visivelLDAP == 1) {
					document.getElementById("visivelLDAP").checked = true;
				}

				if (response.cadastroRapido == 1) {
					document.getElementById("cadastroRapido").checked = true;
				}

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
						var form = $('#edit-formatributosSistemaOrganizacao');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('atributosSistemaOrganizacao/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#edit-formatributosSistemaOrganizacao-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
										$('#data_tableatributosSistemaOrganizacao').DataTable().ajax.reload(null, false).draw(false);
										$('#edit-modalatributosSistemaOrganizacao').modal('hide');
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
								$('#edit-formatributosSistemaOrganizacao-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#edit-formatributosSistemaOrganizacao').validate();

			}
		});
	}

	function removeatributosSistemaOrganizacao(codAtributosSistemaOrganizacao) {
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
					url: '<?php echo base_url('atributosSistemaOrganizacao/remove') ?>',
					type: 'post',
					data: {
						codAtributosSistemaOrganizacao: codAtributosSistemaOrganizacao
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
								$('#data_tableatributosSistemaOrganizacao').DataTable().ajax.reload(null, false).draw(false);
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