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

if (!empty(session()->filtroEspecialidade)) {

	if (session()->filtroEspecialidade["codEspecialista"] !== NULL) {
		$codEspecialista = session()->filtroEspecialidade["codEspecialista"];
	} else {
		$codEspecialista = null;
	}
	$codEspecialidade = session()->filtroEspecialidade["codEspecialidade"];
	$dataInicio = session()->filtroEspecialidade["dataInicio"];
	$dataEncerramento = session()->filtroEspecialidade["dataEncerramento"];
} else {
	$codEspecialidade = NULL;
	$codEspecialista = NULL;
	$dataInicio = NULL;
	$dataEncerramento = NULL;
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
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">MEUS AGENDAMENTOS</h3>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<?php
					if (session()->codPaciente == NULL) {

					?>
						<div class="alert alert-danger alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h5><i class="icon fas fa-ban"></i> ATENÇÃO!</h5>
							Você deve logar no sistema com perfil de "BENEFICIÁRIO" para visualizar sua consultas agendas.
						</div>
					<?php
					} else {
					?>
						<table id="data_tableagendamentos" class="table table-striped table-hover table-sm">
							<thead>
								<tr>
									<th></th>
								</tr>
							</thead>
						</table>
					<?php
					}

					?>


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



<div id="comprovanteA4Modal" class="modal fade" role="dialog" aria-hidden="true">

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

							<div class="row">
								<div><b>Marcado Por: </b>:<span id="autorMarcacaoComprovanteA4"></span></div>
							</div>
							<div class="row">
								<?php
								echo "Impresso por: " . session()->nomeExibicao . ' | CPF: ' . substr(session()->cpf, 0, -6) . '*****'  . " | IP:"  . session()->ip;
								//echo "CPF autor: " . session()->cpf . " | IP:"  . session()->ip;
								?>
							</div>
							<div class="row">
								<?php
								echo session()->cidade . '-' . session()->uf . ', ' . date('d', strtotime(date('Y-m-d'))) . ' de ' . nomeMesPorExtenso(date('m', strtotime(date('Y-m-d')))) . ' de ' . date('Y', strtotime(date('Y-m-d'))) . '.';
								?>
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






				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary" id="botaoImprimirComprovanteA4">Imprimir</button>
				<button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
			</div>
		</div>



	</div>
</div>



<div style="position: fixed;height: 800px" id="comprovanteA4ExameModal" class="modal fade" role="dialog" aria-hidden="true">

	<div class="modal-dialog modal-xl">
		<div class="modal-content">

			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Comprovante de Exame</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<div style="margin-left:10px" id="areaImpressaoComprovanteExameA4">
					<div class="row">
						<div style="width:50% !important" class="col-sm-6 border">

							<div>
								<center><img alt="" style="text-align:center;width:60px;height:60px;" src="<?php echo base_url() . "/imagens/organizacoes/" . session()->logo ?>"></center>
							</div>
							<div style="text-align:center;font-weight: bold">
								<?php echo session()->descricaoOrganizacao; ?>



							</div>

							<div style="font-family: 'Arial';margin-top:20px;height: 80mm;">
								<div style="text-align:left;font-weight: bold;font-size:12px">USUÁRIO: <span id="nomeCompletoComprovanteExameA4"></span></div>
								<div style="text-align:left;font-weight: bold;font-size:12px">Nº PLANO: <span id="CODPLANOComprovanteExameA4"></span></div>
								<div style="text-align:left;font-weight: bold;font-size:12px">EXAME: <span id="nomeExameComprovanteExameA4"></span></div>
								<div style="text-align:left;font-weight: bold;font-size:12px">LOCAL DE ATENDIMENTO: <span id="nomeLocalComprovanteExameA4"></span></div>
								<div style="text-align:left;font-size:12px">DIA: <span id="dataInicioComprovanteExameA4"></span></div>
								<div style="text-align:left;font-size:12px">LOCAL <span id="localComprovanteExameA4"></span></div>
								<div style="text-align:left;font-size:12px">Protocolo Nr: <span id="protocoloComprovanteExameA4"></span></div>
								<div style="text-align:left;font-size:12px"><b>Prontuário Nº: </b>:<span id="codProntuarioComprovanteExameA4"></span></div>
								<div style="margin-top:10px" class="d-flex justify-content-left" id="qrcodeComprovanteExameA4"></div>

							</div>


							<div style="margin-top:30px" class="row">
								<div><b>Marcado Por: </b>:<span id="autorMarcacaoComprovanteExameA4"></span></div>
							</div>



							<div class="row">
								<?php

								echo "CPF autor: " . substr(session()->cpf, 0, -6) . '*****' . " | IP:"  . session()->ip
								?>
							</div>
						</div>
						<div style="width:50% !important" class="col-sm-6 border">

							<div style="margin-left:10px;margin-top:10px;font-family: 'Arial';margin-top:20px;text-align:left;font-weight: bold;font-size:12px">
								<div class="row">
									<b>Prezado usuário, leia atentamente as orientações a seguir:</b>
								</div>
								<div class="row">
									* Este é seu comprovante de marcação do exame.
								</div>

								<div class="row">
									* Compareça no dia do exame 30 minutos antes.
								</div>

								<div class="row">
									* Esta exame só pode ser desmarcada até 24 horas antes. Para desmarcar utilize nossa plataforma online através do endereço <?php echo base_url() ?>, contate-nos através do telefone <?php echo session()->telefoneOrganizacao ?>
								</div>

								<div class="row">
									* Evite faltas, compareça ao exame.
								</div>
								<div class="row">
									* Evite bloqueio de marcações de exame por motivo de faltas.
								</div>

								<div class="row">
									* Evite atrasos.
								</div>

							</div>

						</div>

					</div>






				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary" id="botaoImprimirComprovanteExameA4">Imprimir</button>
				<button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
			</div>
		</div>



	</div>
</div>


<div style="position: fixed;height: 800px" id="comprovanteA4ServicoModal" class="modal fade" role="dialog" aria-hidden="true">

	<div class="modal-dialog modal-xl">
		<div class="modal-content">

			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Comprovante de Servico</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<div style="margin-left:10px" id="areaImpressaoComprovanteServicoA4">
					<div class="row">
						<div style="width:50% !important" class="col-sm-6 border">

							<div>
								<center><img alt="" style="text-align:center;width:60px;height:60px;" src="<?php echo base_url() . "/imagens/organizacoes/" . session()->logo ?>"></center>
							</div>
							<div style="text-align:center;font-weight: bold">
								<?php echo session()->descricaoOrganizacao; ?>



							</div>

							<div style="font-family: 'Arial';margin-top:20px;height: 80mm;">
								<div style="text-align:left;font-weight: bold;font-size:12px">USUÁRIO: <span id="nomeCompletoComprovanteServicoA4"></span></div>
								<div style="text-align:left;font-weight: bold;font-size:12px">Nº PLANO: <span id="CODPLANOComprovanteServicoA4"></span></div>
								<div style="text-align:left;font-weight: bold;font-size:12px">LOCAL DE ATENDIMENTO: <span id="nomeLocalComprovanteServicoA4"></span></div>
								<div style="text-align:left;font-size:12px">DIA: <span id="dataInicioComprovanteServicoA4"></span></div>
								<div style="text-align:left;font-size:12px">Protocolo Nr: <span id="protocoloComprovanteServicoA4"></span></div>
								<div style="text-align:left;font-size:12px"><b>Prontuário Nº: </b>:<span id="codProntuarioComprovanteServicoA4"></span></div>
								<div style="margin-top:10px" class="d-flex justify-content-left" id="qrcodeComprovanteServicoA4"></div>

							</div>


							<div style="margin-top:30px" class="row">
								<div><b>Marcado Por: </b>:<span id="autorMarcacaoComprovanteServicoA4"></span></div>
							</div>



							<div class="row">
								<?php

								echo "CPF autor: " . substr(session()->cpf, 0, -6) . '*****' . " | IP:"  . session()->ip
								?>
							</div>
						</div>
						<div style="width:50% !important" class="col-sm-6 border">

							<div style="margin-left:10px;margin-top:10px;font-family: 'Arial';margin-top:20px;text-align:left;font-weight: bold;font-size:12px">
								<div class="row">
									<b>Prezado usuário, leia atentamente as orientações a seguir:</b>
								</div>
								<div class="row">
									* Este é seu comprovante de marcação do Servico.
								</div>

								<div class="row">
									* Compareça no dia do Servico 30 minutos antes.
								</div>

								<div class="row">
									* Esta Servico só pode ser desmarcada até 24 horas antes. Para desmarcar utilize nossa plataforma online através do endereço <?php echo base_url() ?>, contate-nos através do telefone <?php echo session()->telefoneOrganizacao ?>
								</div>

								<div class="row">
									* Evite faltas, compareça ao Servico.
								</div>
								<div class="row">
									* Evite bloqueio de marcações de Servico por motivo de faltas.
								</div>

								<div class="row">
									* Evite atrasos.
								</div>

							</div>

						</div>

					</div>






				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary" id="botaoImprimirComprovanteServicoA4">Imprimir</button>
				<button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
			</div>
		</div>



	</div>
</div>

<div id="showPacientesRemarcacaoModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Confirmação de Remarcação</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<div id="agendamentoAnterior"></div>

				<div id="dadosConfirmacaoRemarcacao"></div>

				<form autocomplete="off" id="escolhaPacienteRemarcacaoForm" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>escolhaPacienteRemarcacaoForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codAgendamentoAnteriorRemarcacao" name="codAgendamento" class="form-control" placeholder="codAgendamento" maxlength="11" required>
						<input type="hidden" id="codPacienteRemarcacao" value="<?php echo session()->codPaciente ?>" name="codPacienteRemarcacao" class="form-control" placeholder="codAgendamento" maxlength="11" required>
						<input type="hidden" id="codAgendamentoRemarcacao" name="codAgendamento" class="form-control" placeholder="codAgendamento" maxlength="11" required>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="button" onclick="reMarcarPaciente()" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Agendar Agora" id="add-formatalhos-btn">Remarcar agora</button>
							<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>


<div id="remarcacaoModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div id="print-me">
				<div class="modal-header bg-primary text-center p-3">
					<h4 class="modal-title text-white" id="info-header-modalLabel">Remarcação</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">

					<div style="margin-top:10px" class="row">
						<div class="col-sm-12">
							<div id="slotsLivresRemarcacao"> </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



<div id="tipoImpressoraModal" class="modal fade col-md-6" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Tipo de Impressora</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div style="margin-bottom:10px;font-size:20px">
					Selecione o modelo de impressão de papel
				</div>

				<div id="botoesImprimirComprovante" class="row">
				</div>
			</div>


		</div>
	</div><!-- /.modal-content -->
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




	var codEspecialidade = "<?php echo $codEspecialidade ?>";
	var codEspecialista = "<?php echo $codEspecialista ?>";
	var dataInicio = "<?php echo $dataInicio ?>";
	var dataEncerramento = "<?php echo $dataEncerramento ?>";
	var nomePacienteTmp = "";
	var codPacienteTmp = "";

	avisoPesquisa('Agendamento', 2);


	function comprovanteA4Consulta(codAgendamento) {

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
				document.getElementById("autorMarcacaoComprovanteA4").innerHTML = agendamentoComprovante.autorMarcacao;



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



	function comprovanteA4Exame(codExame) {

		$('#comprovanteA4ExameModal').modal('show');
		//$('#tipoImpressoraExameModal').modal('hide');

		document.getElementById("botaoImprimirComprovanteExameA4").onclick = function() {
			printElement(document.getElementById("areaImpressaoComprovanteExameA4"));

			window.print();
		}

		$.ajax({
			url: '<?php echo base_url('agendamentosExames/comprovante') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				codExame: codExame,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(exameComprovante) {
				document.getElementById("nomeCompletoComprovanteExameA4").innerHTML = exameComprovante.nomePaciente;
				document.getElementById("CODPLANOComprovanteExameA4").innerHTML = exameComprovante.codPlano;
				document.getElementById("nomeExameComprovanteExameA4").innerHTML = exameComprovante.nomeExame;
				document.getElementById("nomeLocalComprovanteExameA4").innerHTML = exameComprovante.descricaoDepartamento;
				document.getElementById("protocoloComprovanteExameA4").innerHTML = exameComprovante.protocolo;
				document.getElementById("codProntuarioComprovanteExameA4").innerHTML = exameComprovante.codProntuario;
				document.getElementById("autorMarcacaoComprovanteExameA4").innerHTML = exameComprovante.autorMarcacao;



				document.getElementById("localComprovanteExameA4").innerHTML = exameComprovante.local;
				document.getElementById("dataInicioComprovanteExameA4").innerHTML = exameComprovante.dataInicio;
				var URLComprovante = '<?php echo base_url() . "/agendamentosExames/?codExame=" ?>' + exameComprovante.codAgendamento + '&chechsum=' + exameComprovante.valorChecksum;

				document.getElementById("qrcodeComprovanteExameA4").innerHTML = "";

				qrcode = new QRCode("qrcodeComprovanteExameA4", {
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



	function comprovanteA4Servico(codAgendamento) {

		$('#comprovanteA4ServicoModal').modal('show');

		document.getElementById("botaoImprimirComprovanteServicoA4").onclick = function() {
			printElement(document.getElementById("areaImpressaoComprovanteServicoA4"));

			window.print();
		}

		$.ajax({
			url: '<?php echo base_url('encaminhamentos/comprovante') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				codAgendamento: codAgendamento,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(ServicoComprovante) {
				document.getElementById("nomeCompletoComprovanteServicoA4").innerHTML = ServicoComprovante.nomePaciente;
				document.getElementById("CODPLANOComprovanteServicoA4").innerHTML = ServicoComprovante.codPlano;
				document.getElementById("nomeLocalComprovanteServicoA4").innerHTML = ServicoComprovante.descricaoDepartamento;
				document.getElementById("protocoloComprovanteServicoA4").innerHTML = ServicoComprovante.protocolo;
				document.getElementById("codProntuarioComprovanteServicoA4").innerHTML = ServicoComprovante.codProntuario;
				document.getElementById("autorMarcacaoComprovanteServicoA4").innerHTML = ServicoComprovante.autorMarcacao;



				document.getElementById("dataInicioComprovanteServicoA4").innerHTML = ServicoComprovante.dataInicio;
				var URLComprovante = '<?php echo base_url() . "/Servicos/?codServico=" ?>' + ServicoComprovante.codAgendamento + '&chechsum=' + ServicoComprovante.valorChecksum;

				document.getElementById("qrcodeComprovanteServicoA4").innerHTML = "";

				qrcode = new QRCode("qrcodeComprovanteServicoA4", {
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


	function desmarcarExame(codExame) {
		Swal.fire({
			title: 'Tem certeza que deseja cancelar este Exame?',
			html: '<span class="right badge badge-info">A vaga retornará para lista de vagas abertas!</span>',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {

			if (result.value) {
				$.ajax({
					url: '<?php echo base_url('agendamentosExames/desmarcarExame') ?>',
					type: 'post',
					data: {
						codExame: codExame,
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

								//filtrar();
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
						title: 'Estamos realizando a desmarcação deste Exame',
						html: 'Aguarde....',
						timerProgressBar: true,
						didOpen: () => {
							Swal.showLoading()


						}

					}))
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


	function filtrar() {




		var form = $('#filtroForm');
		$.ajax({
			url: '<?php echo base_url('meusagendamentos/filtrarVagas') ?>',
			type: 'post',
			data: form.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',
			success: function(filtrar) {

				if (filtrar.success === true) {

					$('#data_tableagendamentos').DataTable().ajax.reload(null, false).draw(false);


					$.ajax({
						url: '<?php echo base_url('meusAgendamentos/agendamentosPorEspecialidade') ?>',
						type: 'post',
						dataType: 'json',
						data: {
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},
						success: function(responseAgendamentos) {

							if (responseAgendamentos.success === true) {

								document.getElementById('slotsLivresRemarcacao').innerHTML = responseAgendamentos.slotsLivres;



							}
						}
					})

				}
			}
		})
	}



	$(function() {






		$('#data_tableagendamentos').DataTable({

			"bDestroy": true,
			"paging": true,
			"pageLength": 15,
			"deferRender": true,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('meusAgendamentos/marcados') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		})
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



	function remarcar(codAgendamento) {



		$.ajax({
			url: '<?php echo base_url('Agendamentos/remarcacaoPorEspecialidade') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				codAgendamento: codAgendamento,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(responseAgendamentos) {



				if (responseAgendamentos.success === true) {

					$('#remarcacaoModal').modal('show');
					swal.close();
					document.getElementById('slotsLivresRemarcacao').innerHTML = responseAgendamentos.slotsLivres;
					document.getElementById('agendamentoAnterior').innerHTML = responseAgendamentos.agendamentoAnterior;
					document.getElementById('codAgendamentoAnteriorRemarcacao').value = codAgendamento;


					codPacienteTmp = responseAgendamentos.codPaciente;
					nomePacienteTmp = responseAgendamentos.nomePaciente;


				}
			}
		}).always(
			Swal.fire({
				title: 'Estamos buscando por vagas',
				html: 'Aguarde....',
				timerProgressBar: true,
				didOpen: () => {
					Swal.showLoading()


				}

			}))




	}



	function escolhaPacienteRemarcacao(codAgendamento) {
		// reset the form


		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#showPacientesRemarcacaoModal').modal('show');

		$("#escolhaPacienteRemarcacaoForm #codAgendamentoRemarcacao").val(codAgendamento);
		$("#escolhaPacienteRemarcacaoForm #codPacienteRemarcacao").val(codPacienteTmp);

		//UPDATE PARA RESERVAR POR 1 MINUTO O SLOT E EVITAR CONFLITOS
		$.ajax({
			url: '<?php echo base_url('agendamentos/reservaUmMinutoRemarcacao') ?>',
			type: 'post',
			data: {
				codAgendamento: codAgendamento,
				nomePaciente: nomePacienteTmp,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(reservaUmMinuto) {

				document.getElementById('dadosConfirmacaoRemarcacao').innerHTML = reservaUmMinuto.dadosConfirmacao;



			}
		})


	}

	function desmarcarConsulta(codAgendamento) {
		Swal.fire({
			title: 'Você tem certeza que deseja desmarcar este agendamento?',
			text: "Você não poderá reverter após a confirmação e a vaga retornará para o sistema",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {

			if (result.value) {
				$.ajax({
					url: '<?php echo base_url('meusAgendamentos/desmarcarConsulta') ?>',
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
								//filtrar();
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



	function desmarcarServico(codAgendamento) {
		Swal.fire({
			title: 'Você tem certeza que deseja desmarcar este agendamento de serviço?',
			text: "Você não poderá reverter após a confirmação e a vaga retornará para o sistema",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirmar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {

			if (result.value) {
				$.ajax({
					url: '<?php echo base_url('encaminhamentos/desmarcarServico') ?>',
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
								//filtrar();
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



	function comprovante(codAgendamento) {
		$('#tipoImpressoraModal').modal('show');



		document.getElementById("botoesImprimirComprovante").innerHTML =
			'' +
			'<div class="col-md-3"><button style="margin-left:0px" type="button" onclick="comprovanteA4Consulta(' + codAgendamento + ')" class="btn btn-primary" data-toggle="tooltip" data-placement="top" id="add-formatalhos-btn">Impressora A4</button></div>' +
			'';

	}



	function reMarcarPaciente() {


		var form = $('#escolhaPacienteRemarcacaoForm');
		var codPacienteMarcacao = document.getElementById('codPacienteRemarcacao').value;
		var codAgendamento = document.getElementById('codAgendamentoRemarcacao').value;
		var codAgendamentoAnteriorRemarcacao = document.getElementById('codAgendamentoAnteriorRemarcacao').value;


		$.ajax({
			url: '<?php echo base_url('agendamentos/reMarcarPaciente') ?>',
			type: 'post',
			data: {
				codPacienteMarcacao: codPacienteMarcacao,
				codAgendamentoAnteriorRemarcacao: codAgendamentoAnteriorRemarcacao,
				codAgendamento: codAgendamento,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(marcacaoPaciente) {

				$('#showPacientesModal').modal('hide');

				if (marcacaoPaciente.success === true) {



					if ($('#remarcacaoModal').is(':visible') == true) {

						$('#remarcacaoModal').modal('hide');
						$('#showPacientesRemarcacaoModal').modal('hide');
						$('#data_tableagendamentos').DataTable().ajax.reload(null, false).draw(false);

					}

					comprovanteA4Consulta(codAgendamento);

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

					var htmlMarcacao = "";
					if (marcacaoPaciente.html !== null) {
						htmlMarcacao = marcacaoPaciente.html;
					}


					Swal.fire({
						position: 'bottom-end',
						icon: 'error',
						title: marcacaoPaciente.messages,
						html: htmlMarcacao,
						showConfirmButton: true,
						confirmButtonText: 'Ok',
					})

					//document.getElementById("slotsLivres").innerHTML = '';


					var Toast = Swal.mixin({
						toast: true,
						position: 'bottom-end',
						showConfirmButton: false,
						timer: 5000
					});


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
					url: '<?php echo base_url('meusAgendamentos/confirmou') ?>',
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
						title: 'Estamos registrando que o paciente confirmou que virá para consulta',
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