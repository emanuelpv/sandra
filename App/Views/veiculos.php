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
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Veículos</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar veículo" onclick="addveiculos()" title="Adicionar">Adicionar veículo</button>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<table id="data_tableveiculos" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>Num</th>
								<th>Placa</th>
								<th>Nome Exibição</th>
								<th>Marca</th>
								<th>Modelo</th>
								<th>Cor</th>
								<th>Status</th>
								<th>Data Autorização</th>
								<th>Data Validade</th>
								<th>Observações</th>

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

<div id="imprimirCartaoModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Cartão de Estacionamento</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="col-md-2">
					<button type="button" class="btn btn-block btn-success" data-toggle="tooltip" data-placement="top" title="Imprimir Cartão" id="botaoImprimirCartao" onclick="imprimirCartaoEstacionamentoAgora()" title="Adicionar"><i style="font-size:20px" class="fa fa-print"></i> Imprimir Cartão</button>
				</div>
				<div style="margin-left:20px; margin-right:20px" id="areaImpressaoCartao">
					<div id="imprimirCartao"></div>
				</div>
			</div>
		</div>
	</div>
</div>



<div id="veiculosAddModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Veículos</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="veiculosAddForm" enctype="multipart/form-data" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>veiculosAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codVeiculo" name="codVeiculo" class="form-control" placeholder="CodVeiculo" maxlength="11" required>
					</div>


					<div class="row">
						<div class="col-md-6">
							<div class="card card-primary">
								<div class="card-header">
									Informações do veículo
								</div>

								<div class="card-body">
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label for="placa"> Placa: <span class="text-danger">*</span> </label>
												<input type="text" id="placa" name="placa" class="form-control" placeholder="Placa" maxlength="7" required>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="cpf"> CPF Proprietário <span class="text-danger">*</span> </label>
												<input type="text" id="cpf" name="cpf" class="form-control" value="<?php echo session()->cpf ?>" placeholder="Cpf" required>
											</div>
										</div>

									</div>

									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="condutor1Add"> Condutor 1: <span class="text-danger">*</span> </label>
												<input type="text" id="condutor1Add" name="condutor1" class="form-control" placeholder="Nome do Condutor" value="<?php echo session()->nomeCompleto ?>" maxlength="100" required>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="condutor2Add"> Condutor 2: <span class="text-danger">*</span> </label>
												<input type="text" id="condutor2Add" name="condutor2" class="form-control" placeholder="Nome do segundo condutor" maxlength="100">
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="marcaAdd"> Marca: <span class="text-danger">*</span> </label>
												<input type="text" id="marcaAdd" name="marca" class="form-control" placeholder="Marca" maxlength="30" required>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="modeloAdd"> Modelo: <span class="text-danger">*</span> </label>
												<input type="text" id="modeloAdd" name="modelo" class="form-control" placeholder="Modelo" maxlength="50" required>
											</div>
										</div>
									</div>
									<div class="row">

										<div class="col-md-4">
											<div class="form-group">
												<label for="cor"> Cor: <span class="text-danger">*</span> </label>
												<input type="text" id="corAdd" name="cor" class="form-control" placeholder="Cor" maxlength="20" required>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label for="observacao"> Observacao: </label>
												<textarea cols="40" rows="10" id="observacao" name="observacao" class="form-control" placeholder="Observacao"></textarea>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="card card-primary">
								<div class="card-header">
									Arquivos
								</div>

								<div class="card-body">

									<div class="row">
										<div class="col-md-12">
											Favor inserir o CLRV do veículo.
										</div>
										<div style="margin-top:20px" class="col-md-6">
											<div class="form-group">
												<div class="custom-file">
													<input required type="file" class="custom-file-input" id="arquivoOnAdd" name="file" onchange="aviso1(this)">
													<label class="custom-file-label" for="arquivo1">Selecione um arquivo</label>
												</div>
											</div>
											<div style="color:red" id="aviso1">
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
					<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="veiculosAddForm-btn">Adicionar</button>
					<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
				</div>
			</div>
			</form>


		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div id="verDocumentoModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Documento</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div id="verDocumento">

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Add modal content -->
<div id="veiculosEditModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Veículos</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="veiculosEditForm" enctype="multipart/form-data" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>veiculosEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codVeiculo" name="codVeiculo" class="form-control" placeholder="CodVeiculo" maxlength="11" required>
					</div>


					<div class="row">
						<div class="col-md-6">
							<div class="card card-primary">
								<div class="card-header">
									Informações do veículo
								</div>

								<div class="card-body">
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label for="placa"> Placa: <span class="text-danger">*</span> </label>
												<input type="text" id="placa" name="placa" class="form-control" placeholder="Placa" maxlength="7" required>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="cpf"> CPF Proprietário <span class="text-danger">*</span> </label>
												<input type="text" id="cpf" name="cpf" class="form-control" placeholder="Cpf" required>
											</div>
										</div>

									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="condutor1Add"> Condutor 1: <span class="text-danger">*</span> </label>
												<input type="text" id="condutor1Edit" name="condutor1" class="form-control" placeholder="Nome do Condutor" maxlength="100" required>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="condutor2Add"> Condutor 2: <span class="text-danger">*</span> </label>
												<input type="text" id="condutor2Edit" name="condutor2" class="form-control" placeholder="Nome do segundo condutor" maxlength="100">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="marca"> Marca: <span class="text-danger">*</span> </label>
												<input type="text" id="marca" name="marca" class="form-control" placeholder="Marca" maxlength="30" required>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="modelo"> Modelo: <span class="text-danger">*</span> </label>
												<input type="text" id="modelo" name="modelo" class="form-control" placeholder="Modelo" maxlength="50" required>
											</div>
										</div>
									</div>
									<div class="row">

										<div class="col-md-4">
											<div class="form-group">
												<label for="cor"> Cor: <span class="text-danger">*</span> </label>
												<input type="text" id="cor" name="cor" class="form-control" placeholder="Cor" maxlength="20" required>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label for="observacao"> Observacao: </label>
												<textarea cols="40" rows="10" id="observacao" name="observacao" class="form-control" placeholder="Observacao"></textarea>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="card card-primary">
								<div class="card-header">
									Arquivos
								</div>

								<div class="card-body">

									<div class="row">
										<div class="col-md-12">
											Insira aqui o CLRV do veículo.
										</div>
										<div style="margin-top:20px" class="col-md-6">
											<div class="form-group">
												<div class="custom-file">
													<input type="file" class="custom-file-input" id="arquivoOnEdit" name="file" onchange="aviso2(this)">
													<label class="custom-file-label" for="arquivo1">Selecione um arquivo</label>
												</div>
											</div>
										</div>

										<div style="color:red" id="aviso2">
										</div>

									</div>

									<div style="margin-top:20px" class="row">
										<div class="col-md-12">
											<table id="data_tabledocumentosVeiculo" class="table table-striped table-hover table-sm">
												<thead>
													<tr>
														<th>Nun</th>
														<th>Data</th>
														<th>Documento</th>
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

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="veiculosEditForm-btn">Salvar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
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
	$(document).on('show.bs.modal', '.modal', function() {
		var zIndex = 1040 + (10 * $('.modal:visible').length);
		$(this).css('z-index', zIndex);
		setTimeout(function() {
			$('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
		}, 0);
	});




	var marcas = "";

	$.ajax({
		url: '<?php echo base_url('veiculos/pegaMarcas') ?>',
		type: 'post',
		data: {
			csrf_sandra: $("#csrf_sandraPrincipal").val(),
		},
		dataType: 'json',
		success: function(response) {
			marcas = response.marcas;

			autocomplete(document.getElementById("marcaAdd"), JSON.parse(marcas));

			autocomplete(document.getElementById("marca"), JSON.parse(marcas));
		}

	})



	var modelos = "";

	$.ajax({
		url: '<?php echo base_url('veiculos/pegaModelos') ?>',
		type: 'post',
		data: {
			csrf_sandra: $("#csrf_sandraPrincipal").val(),
		},
		dataType: 'json',
		success: function(response) {
			modelos = response.modelos;

			autocomplete(document.getElementById("modeloAdd"), JSON.parse(modelos));

			autocomplete(document.getElementById("modelo"), JSON.parse(modelos));
		}

	})


	var cores = "";

	$.ajax({
		url: '<?php echo base_url('veiculos/pegaCores') ?>',
		type: 'post',
		data: {
			csrf_sandra: $("#csrf_sandraPrincipal").val(),
		},
		dataType: 'json',
		success: function(response) {
			cores = response.cores;

			autocomplete(document.getElementById("corAdd"), JSON.parse(cores));

			autocomplete(document.getElementById("cor"), JSON.parse(cores));
		}

	})


	$(function() {
		$('#data_tableveiculos').DataTable({
			"paging": true,
			"deferRender": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			pageLength: 100,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('veiculos/getAll') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});
	});


	function aviso1(myFile) {
		var file = myFile.files[0];
		var filename = file.name;
		document.getElementById('aviso1').innerHTML = filename;

	}

	function aviso2(myFile) {
		var file = myFile.files[0];
		var filename = file.name;
		document.getElementById('aviso2').innerHTML = filename;

	}


	function addveiculos() {
		// reset the form 
		$("#veiculosAddForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#veiculosAddModal').modal('show');
		// submit the add from 

		document.getElementById('aviso1').innerHTML = "";

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

				var form = $('#veiculosAddForm');
				// remove the text-danger
				$(".text-danger").remove();



				$.ajax({
					url: '<?php echo base_url('veiculos/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#veiculosAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function(response) {

						if (response.success === true) {
							$('#veiculosAddModal').modal('hide');
							$('#data_tableveiculos').DataTable().ajax.reload(null, false).draw(false);



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



							if (document.getElementById("arquivoOnAdd").files.length !== 0) {


								var formData = new FormData();
								formData.append('file', $('#arquivoOnAdd')[0].files[0]);
								formData.append('codVeiculo', response.codVeiculo);
								formData.append('csrf_sandra', $("#csrf_sandraPrincipal").val());
								$.ajax({
									url: 'veiculos/enviarArquivo',
									type: 'post',
									dataType: 'json',
									data: formData,
									processData: false, // tell jQuery not to process the data
									contentType: false, // tell jQuery not to set contentType
									success: function(enviaArquivo) {
										if (enviaArquivo.success == true) {}


									},
								});

							}

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
						$('#veiculosAddForm-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#veiculosAddForm').validate();
	}


	function verDocumentoVeiculo(codDocumento) {
		$.ajax({
			url: '<?php echo base_url('veiculos/verDocumento') ?>',
			type: 'post',
			data: {
				codDocumento: codDocumento,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				if (response.success === true) {

					$('#verDocumentoModal').modal('show');

					document.getElementById('verDocumento').innerHTML = response.documento;
				}
			}
		})
	}




	function imprimirCartaoEstacionamentoAgora() {

		$('#imprimirCartaoModal').modal('hide');


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


		printElement(document.getElementById("areaImpressaoCartao"));

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


	function imprimirCartaoEstacionamento(codVeiculo) {
		$.ajax({
			url: '<?php echo base_url('veiculos/imprimirCartaoEstacionamento') ?>',
			type: 'post',
			data: {
				codVeiculo: codVeiculo,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				if (response.success === true) {
					$('#imprimirCartaoModal').modal('show');

					document.getElementById('imprimirCartao').innerHTML = response.html;

					document.getElementById("qrcodeCartao").innerHTML = "";

					qrcode = new QRCode("qrcodeCartao", {
						text: response.dadosQR,
						width: 160,
						height: 160,
						colorDark: "#000000",
						colorLight: "#ffffff",
						correctLevel: QRCode.CorrectLevel.H
					});


				}

				if (response.success === false) {

					Swal.fire({
						title: response.messages,
						icon: 'error',
						confirmButtonText: 'Ciente',
					})
				}
			}

		})
	}


	function aprovarveiculo(codVeiculo) {
		$.ajax({
			url: '<?php echo base_url('veiculos/aprovarveiculo') ?>',
			type: 'post',
			data: {
				codVeiculo: codVeiculo,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				if (response.success === true) {

					$('#data_tableveiculos').DataTable().ajax.reload(null, false).draw(false);

					var Toast = Swal.mixin({
						toast: true,
						position: 'bottom-end',
						showConfirmButton: false,
						timer: 2000
					});
					Toast.fire({
						icon: 'success',
						title: 'Veículo aprovado'
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
			})

		)
	}

	function rejeitarveiculo(codVeiculo) {
		$.ajax({
			url: '<?php echo base_url('veiculos/rejeitarveiculo') ?>',
			type: 'post',
			data: {
				codVeiculo: codVeiculo,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				if (response.success === true) {

					$('#data_tableveiculos').DataTable().ajax.reload(null, false).draw(false);

					var Toast = Swal.mixin({
						toast: true,
						position: 'bottom-end',
						showConfirmButton: false,
						timer: 2000
					});
					Toast.fire({
						icon: 'success',
						title: 'Veículo aprovado'
					})

				}
			}
		})
	}

	function editveiculos(codVeiculo) {
		$.ajax({
			url: '<?php echo base_url('veiculos/getOne') ?>',
			type: 'post',
			data: {
				codVeiculo: codVeiculo,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {


				// reset the form 
				$("#veiculosEditForm")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#veiculosEditModal').modal('show');

				$("#veiculosEditForm #codVeiculo").val(response.codVeiculo);
				$("#veiculosEditForm #placa").val(response.placa);
				$("#veiculosEditForm #cpf").val(response.cpf);
				$("#veiculosEditForm #condutor1Edit").val(response.condutor1);
				$("#veiculosEditForm #condutor2Edit").val(response.condutor2);
				$("#veiculosEditForm #codPessoa").val(response.codPessoa);
				$("#veiculosEditForm #codPaciente").val(response.codPaciente);
				$("#veiculosEditForm #codVisitante").val(response.codVisitante);
				$("#veiculosEditForm #marca").val(response.marca);
				$("#veiculosEditForm #modelo").val(response.modelo);
				$("#veiculosEditForm #cor").val(response.cor);
				$("#veiculosEditForm #codStatus").val(response.codStatus);
				$("#veiculosEditForm #dataCriacao").val(response.dataCriacao);
				$("#veiculosEditForm #dataAtualizacao").val(response.dataAtualizacao);
				$("#veiculosEditForm #dataAutorizacao").val(response.dataAutorizacao);
				$("#veiculosEditForm #dataValidade").val(response.dataValidade);
				$("#veiculosEditForm #codAutor").val(response.codAutor);
				$("#veiculosEditForm #observacao").val(response.observacao);


				document.getElementById('aviso2').innerHTML = "";


				document.getElementById("arquivoOnEdit").onchange = function() {


					if (document.getElementById("arquivoOnEdit").files.length !== 0) {

						var formData = new FormData();
						formData.append('file', $('#arquivoOnEdit')[0].files[0]);
						formData.append('codVeiculo', response.codVeiculo);
						formData.append('csrf_sandra', $("#csrf_sandraPrincipal").val());
						$.ajax({
							url: 'veiculos/enviarArquivo',
							type: 'post',
							dataType: 'json',
							data: formData,
							processData: false, // tell jQuery not to process the data
							contentType: false, // tell jQuery not to set contentType
							success: function(enviaArquivo) {
								if (enviaArquivo.success == true) {
									$('#data_tabledocumentosVeiculo').DataTable().ajax.reload(null, false).draw(false);

									var Toast = Swal.mixin({
										toast: true,
										position: 'bottom-end',
										showConfirmButton: false,
										timer: 3000
									});
									Toast.fire({
										icon: 'success',
										title: enviaArquivo.messages,
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
										title: enviaArquivo.messages,
									})
								}


							},
						});

					}
				}


				$('#data_tabledocumentosVeiculo').DataTable({
					bDestroy: true,
					"paging": true,
					"deferRender": true,
					"lengthChange": false,
					"searching": false,
					"ordering": true,
					"info": true,
					"autoWidth": false,
					"responsive": true,
					"ajax": {
						"url": '<?php echo base_url('veiculos/documentosVeiculo') ?>',
						"type": "POST",
						"dataType": "json",
						async: "true",
						data: {
							codVeiculo: codVeiculo,
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
						var form = $('#veiculosEditForm');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('veiculos/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#veiculosEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
							},
							success: function(response) {

								if (response.success === true) {

									$('#veiculosEditModal').modal('hide');


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
										$('#data_tableveiculos').DataTable().ajax.reload(null, false).draw(false);
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
								$('#veiculosEditForm-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#veiculosEditForm').validate();

			}
		});
	}

	function removeveiculos(codVeiculo) {
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
					url: '<?php echo base_url('veiculos/remove') ?>',
					type: 'post',
					data: {
						codVeiculo: codVeiculo,
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
								$('#data_tableveiculos').DataTable().ajax.reload(null, false).draw(false);
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


	function removedocumentosVeiculo(codDocumento) {
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
					url: '<?php echo base_url('veiculos/removeDocumentosVeiculo') ?>',
					type: 'post',
					data: {
						codDocumento: codDocumento,
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
								$('#data_tabledocumentosVeiculo').DataTable().ajax.reload(null, false).draw(false);
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