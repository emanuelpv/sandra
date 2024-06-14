<?php
//É NECESSÁRIO EM TODAS AS VIEWS

$codOrganizacao = session()->codOrganizacao;


?>

<div style="visibility:hidden" id="setEstilo"></div>
<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-md-8 mt-2">
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">ANIVERSARIANTES</h3>
						</div>
						<div class="col-md-12">

						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">

					<button onclick="imprimirCartoes()" class="btn btn-primary">Imprimir Selecionados</button>

					<form id="imprimirCartoesForm" class="pl-3 pr-3">
						<input type="hidden" id="<?php echo csrf_token() ?>imprimirCartoesForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<table id="data_tableAniversariantes" class="table table-striped table-hover table-sm">
							<thead>
								<tr>
									<th><input id="selecionarTodas" type="checkbox"><span style="margin-left:10px">Dia</span></th>
									<th>Pessoa</th>
									<th>Setor</th>
									<th>Email</th>
									<th>Celular</th>
									<th style="text-align:center">Ações</th>
								</tr>
							</thead>
						</table>
					</form>

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



<div id="imprimirCartoesModal" class="modal fade" role="dialog" aria-hidden="true">

	<div class="modal-dialog modal-xl">
		<div class="modal-content">

			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Cartões</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<div style="margin-left:10px" id="areaImpressaoCartoes">
					<div id="dadosCartoes" class="row">



					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary" onclick="imprimirAgora()">Imprimir</button>
				<button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
			</div>
		</div>



	</div>
</div>


<!-- /.content -->
<?php
echo view('tema/rodape');
?>
<script>
	$(function() {

		$('#data_tableAniversariantes').DataTable({
			"pageLength": 50,
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			"info": true,
			"autoWidth": false,
			"responsive": true,
			"ajax": {
				"url": '<?php echo base_url($controller . '/listaAniversariantes') ?>',
				"type": "post",
				"dataType": "json",
				async: "true",
				data: {
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},
			}
		});






	});

	$(function() {

		$('#selecionarTodas').click(function(event) {
			if (this.checked) {
				// Iterate each checkbox
				$('.imprimirCheckbox').each(function() {
					this.checked = true;
				});
			} else {
				$('.imprimirCheckbox').each(function() {
					this.checked = false;
				});
			}
		});

	})

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


	function imprimirCartoes() {

		$('#imprimirCartoesModal').modal('show');



		var form = $('#imprimirCartoesForm');


		$.ajax({
			url: '<?php echo base_url('pessoas/imprimirAniversariantes') ?>',
			type: 'post',
			data: form.serialize(),
			dataType: 'json',
			success: function(impressaoAniversariantes) {

				if (impressaoAniversariantes.success === true) {

					document.getElementById("dadosCartoes").innerHTML = impressaoAniversariantes.html;



				}
			}


		})

	}

	function imprimirAgora() {


		document.getElementById("setEstilo").innerHTML = '<style>@media screen {' +
			'#printSection {' +
			'display: none;' +

			'}' +
			'}' +

			'@media print {' +
			'@page {' +
			'size: A4 landscape;' +
			'margin-top: 15px;' +
			'margin-bottom: 15px;' +
			'margin-left: 15px;' +
			'margin-right: 15px;' +
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
			'width: 297mm;' +
			'height: 210mm;' +

			'}' +
			'}</style>';


		printElement(document.getElementById("areaImpressaoCartoes"));
		window.print();
	}
</script>