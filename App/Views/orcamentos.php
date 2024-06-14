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
				  <h3 style="font-size:30px;font-weight: bold;" class="card-title">Orçamentos</h3>
			  	</div>
				<div class="col-md-4">
				  <button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addorcamentos()" title="Adicionar">Adicionar</button>
				</div>
			  </div>			  
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="data_tableorcamentos" class="table table-striped table-hover table-sm">
                <thead>
                <tr>
					<th>Código</th>
					<th>codFornecedor</th>
					<th>ValorUnitario</th>
					<th>Tipo Orcamento</th>

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
	<div id="orcamentosAddModal" class="modal fade" role="dialog"
		aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Orçamentos</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
				<div class="modal-body">
					<form id="orcamentosAddForm" class="pl-3 pr-3">
						<input type="hidden" id="<?php echo csrf_token() ?>orcamentosAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
					
                        <div class="row">
 							<input type="hidden" id="codOrcamento" name="codOrcamento" class="form-control" placeholder="Código" maxlength="11" required>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="codFornecedor"> codFornecedor: <span class="text-danger">*</span> </label>
									<input type="number" id="codFornecedor" name="codFornecedor" class="form-control" placeholder="codFornecedor" maxlength="20" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="valorUnitario"> ValorUnitario: <span class="text-danger">*</span> </label>
									<input type="text" id="valorUnitario" name="valorUnitario" class="form-control" placeholder="ValorUnitario" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codTipoOrcamento"> Tipo Orcamento: <span class="text-danger">*</span> </label>
									<select id="codTipoOrcamento" name="codTipoOrcamento" class="custom-select" required>
									<option value="0"></option>	
									<option value="1">select1</option>
										<option value="2">select2</option>
										<option value="3">select3</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
						</div>
																				
						<div class="form-group text-center">
							<div class="btn-group">
								<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="orcamentosAddForm-btn">Adicionar</button>
								<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
							</div>
						</div>
					</form>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->	

	<!-- Add modal content -->				
	<div id="orcamentosEditModal" class="modal fade" role="dialog"
		aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Orçamentos</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
				<div class="modal-body">
					<form id="orcamentosEditForm" class="pl-3 pr-3">
						<input type="hidden" id="<?php echo csrf_token() ?>orcamentosEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                        <div class="row">
 							<input type="hidden" id="codOrcamento" name="codOrcamento" class="form-control" placeholder="Código" maxlength="11" required>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="codFornecedor"> codFornecedor: <span class="text-danger">*</span> </label>
									<input type="number" id="codFornecedor" name="codFornecedor" class="form-control" placeholder="codFornecedor" maxlength="20" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="valorUnitario"> ValorUnitario: <span class="text-danger">*</span> </label>
									<input type="text" id="valorUnitario" name="valorUnitario" class="form-control" placeholder="ValorUnitario" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codTipoOrcamento"> Tipo Orcamento: <span class="text-danger">*</span> </label>
									<select id="codTipoOrcamento" name="codTipoOrcamento" class="custom-select" required>
									<option value="0"></option>	
									<option value="1">select1</option>
										<option value="2">select2</option>
										<option value="3">select3</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
						</div>
											
						<div class="form-group text-center">
							<div class="btn-group">
								<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="orcamentosEditForm-btn">Salvar</button>
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
	

</script>
