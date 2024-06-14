<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-md-8 mt-2">
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Teste</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addteste()" title="Adicionar">Adicionar</button>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">



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
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/moment/moment.min.js"></script>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/fullcalendar/main.js"></script>

<script>
	$(document).on('show.bs.modal', '.modal', function() {
		var zIndex = 1040 + (10 * $('.modal:visible').length);
		$(this).css('z-index', zIndex);
		setTimeout(function() {
			$('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
		}, 0);
	});
	
</script>
