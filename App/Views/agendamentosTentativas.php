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
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Tentativas de marcação</h3>
						</div>
					</div>
				</div>
				<!-- /.card-header -->

				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="prec"> CPF/PREC: <span class="text-danger">*</span> </label>
							<input type="text" id="prec" name="prec" class="form-control" placeholder="CPF Nº Plano" required>
						</div>
					</div>
				</div>

				<div class="row">

					<div class="col-md-4">
						<button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="procurarPaciente()" title="procurar">Procurar</button>
					</div>
				</div>

				<div class="card-body">
					<table id="data_tableagendamentosTentativas" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>Nome</th>
								<th>CPF</th>
								<th>Especialidade</th>
								<th>Tentativas</th>
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



	function procurarPaciente() {

		$('#data_tableagendamentosTentativas').DataTable({
			"bDestroy": true,
			"paging": true,
			"deferRender": true,
			"lengthChange": false,
			"lengthPath": false,
            "pageLength": 50,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url('agendamentos/tentativasPorPaciente') ?>',
				"type": "POST",
				"dataType": "json",
				async: "true",
				data: {
					prec:$('#prec').val(),
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});
	}
</script>