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

<style>
	.modal {
		overflow: auto !important;
	}
</style>
<div style="visibility:hidden" id="setEstilo"></div>
<section class="content">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-md-8 mt-2">
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Itens Hospitalares</h3>
						</div>
						<div class="col-md-4 text-right">
							<button class="btn btn-primary" onclick="additensFarmacia()">
								<div><i class="fas fa-syringe zoom fa-3x"></i></div>Adicionar Item
							</button>
						</div>


					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<div class="col-md-4">
						<div class="form-group">
							<label for="codCategoriaFiltro"> Categoria: <span class="text-danger">*</span> </label>
							<select id="codCategoriaFiltro" name="codCategoria" class="custom-select" required>
								<option value=""></option>
							</select>
						</div>
					</div>
					<table id="data_tableitensFarmacia" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>Código</th>
								<th>NEE</th>
								<th>Descrição</th>
								<th>Valor</th>
								<th>Saldo</th>
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
<div id="itensFarmaciaAddModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Item Hospitalar</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="itensFarmaciaAddForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>itensFarmaciaAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">

						<div class="col-md-8">

							<div class="row">
								<input type="hidden" id="codItemAdd" name="codItem" class="form-control" placeholder="Código" maxlength="11" required>
							</div>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="descricaoItem"> Descrição: <span class="text-danger">*</span> </label>
										<input type="text" id="descricaoItemAdd" name="descricaoItem" class="form-control" placeholder="Descrição" maxlength="250" required>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="codCategoria"> Categoria: <span class="text-danger">*</span> </label>
										<select id="codCategoriaAdd" name="codCategoria" class="custom-select" required>
											<option value=""></option>
										</select>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="antibiotico"> Antibiótico: </label>
										<select id="antibiotico" name="antibiotico" class="custom-select">
											<option value="0">Não</option>
											<option value="1">Sim</option>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label for="nee"> NEE: <span class="text-danger">*</span> </label>
										<input type="text" id="neeAdd" name="nee" class="form-control" placeholder="NEE" maxlength="20" required>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="sire"> Sire:</span> </label>
										<input type="text" id="sireAdd" name="sire" class="form-control" placeholder="Sire" maxlength="10">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="ean"> EAN: </label>
										<input type="text" id="eanAdd" name="ean" class="form-control" placeholder="EAN" maxlength="15">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="nme"> NME: </span> </label>
										<input type="number" step=".01" id="nmeAdd" name="nme" class="form-control" placeholder="NME">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="nme"> PP: </span> </label>
										<input type="number" id="ppAdd" name="pp" class="form-control" placeholder="NME">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label for="valor"> Valor: <span class="text-danger">*</span> </label>
										<input type="number" step=".01" id="valorAdd" name="valor" class="form-control" placeholder="Valor" required>
									</div>
								</div>

							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="observacao"> Observação:</span> </label>
										<textarea cols="40" rows="5" id="observacaoAdd" name="observacao" class="form-control" placeholder="Observação"></textarea>
									</div>
								</div>

							</div>
							<div class="form-group text-center">
								<div class="btn-group">
									<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar">Adicionar</button>
									<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<img style="width:350px" src="<?php echo base_url('/imagens/imagemIndisponivel.png') ?>">
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div id="escolhaMeioAddLoteModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Item por Lote</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">

						<div class="callout callout-info">
							<h5>CÓDIGO DE BARRAS <i class="fas fa-barcode zoom fa-3x"></i></h5>

							<span>Para iniciar a inclusão de um Item, faça a leitura do códico de barras agora</span>
							<span class="spinner-grow" role="status">
								<span class="sr-only">Loading...</span>
							</span>
						</div>




					</div>
					<div class="col-md-4 text-right">
						<button class="btn btn-primary" onclick="additensFarmaciaLote()">
							<div>
								<i style="width:250px" class="fas fa-tablets zoom fa-4x"></i>
							</div>Adicionar Item
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="itensFarmaciaEditModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Itens Hospitalares</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-9">
						<form id="itensFarmaciaEditForm" class="pl-3 pr-3">


							<div class="row">
								<input type="hidden" id="<?php echo csrf_token() ?>itensFarmaciaEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

								<input type="hidden" id="codItem" name="codItem" class="form-control" placeholder="Código" maxlength="11" required>
							</div>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="descricaoItem"> Descrição: <span class="text-danger">*</span> </label>
										<input type="text" id="descricaoItem" name="descricaoItem" class="form-control" placeholder="Descrição" maxlength="250" required>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="codCategoria"> Categoria: <span class="text-danger">*</span> </label>
										<select id="codCategoria" name="codCategoria" class="custom-select" required>
											<option value=""></option>
										</select>
									</div>
								</div>
								<div class="col-md-1">
									<div class="form-group">
										<label for="checkboxmatriz">Ativo: </label>

										<div class="icheck-primary d-inline">
											<style>
												input[type=checkbox] {
													transform: scale(1.8);
												}
											</style>
											<input style="margin-left:5px;" name="ativo" type="checkbox" id="checkboxAtivoEdit">


										</div>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="antibiotico"> Antibiótico: </label>
										<select id="antibiotico" name="antibiotico" class="custom-select">
											<option value="0">Não</option>
											<option value="1">Sim</option>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label for="nee"> NEE: <span class="text-danger">*</span> </label>
										<input type="text" id="nee" name="nee" class="form-control" placeholder="NEE" maxlength="20" required>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="sire"> Sire:</span> </label>
										<input type="text" id="sire" name="sire" class="form-control" placeholder="Sire" maxlength="10">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="ean"> EAN: </label>
										<input type="text" id="ean" name="ean" class="form-control" placeholder="EAN" maxlength="15">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="nme"> NME: </span> </label>
										<input type="number" step=".01" id="nme" name="nme" class="form-control" placeholder="NME">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="nme"> PP: </span> </label>
										<input type="number" id="pp" name="pp" class="form-control" placeholder="NME">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label for="valor"> Valor: <span class="text-danger">*</span> </label>
										<input type="number" step=".01" id="valor" name="valor" class="form-control" placeholder="Valor" required>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="saldo"> Saldo: <span class="text-danger">*</span> </label>
										<input type="number" step=".01" id="saldo" name="saldo" class="form-control" placeholder="Saldo" required>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="observacao"> Observação:</span> </label>
										<textarea cols="40" rows="5" id="observacao" name="observacao" class="form-control" placeholder="Observação"></textarea>
									</div>
								</div>
							</div>


							<div class="row">

								<div class="col-lg-12">
									<div class="card">
										<div class="card-header bg-primary ">
											<h5 class="card-title m-0">LOTES</h5>
										</div>
										<div class="card-body">

											<div class="col-md-4">
												<button type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="escolhaMeioAddLote()" title="Adicionar">Adicionar</button>
											</div>

											<table id="data_tableitensFarmaciaLote" class="table table-striped table-hover table-sm">
												<thead>
													<tr>
														<th>ID</th>
														<th>Nº do Lote</th>
														<th>Qtde</th>
														<th>Validade</th>
														<th>Inventário</th>
														<th>Observação</th>
														<th>Código de Barras</th>
														<th>Autor</th>
														<th></th>
													</tr>
												</thead>
											</table>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group text-center">
								<div class="btn-group">
									<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar">Salvar</button>
									<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
								</div>
							</div>
						</form>
					</div>

					<div class="col-md-3">
						<div class="row">
							<img style="width:250px" src="<?php echo base_url('/imagens/imagemIndisponivel.png') ?>">
						</div>
						<div style="margin-top:10px" class="row">

							<div class="col-md-4 text-right">
								<div id="botaoFicha"></div>
							</div>


						</div>
						<div style="margin-top:10px" class="row">

							<!-- 
								IMPLEMENTAÇÃO DE LEITOR DE CODEBAR
							https://stackoverflow.com/questions/21633537/javascript-how-to-read-a-hand-held-barcode-scanner-best
						-->
							<div class="col-md-4 text-right">
								<a href="#" onclick="imprimirCodBarra()">
									<svg id="barcode"></svg> </a>
							</div>


						</div>
					</div>
				</div>

			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>



<div style="position: fixed;height: 800px" id="fichaA4Modal" class="modal fade" role="dialog" aria-hidden="true">

	<div class="modal-dialog modal-xl">
		<div class="modal-content">

			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Comprovante</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<div style="margin-left:10px" id="areaImpressaoFichaA4">
					<div class="row">
						<div style="width:50% !important" class="col-sm-6 border">

							<div>
								<center><img alt="" style="text-align:center;width:100px;height:100px;" src="<?php echo base_url() . "/imagens/organizacoes/" . session()->logo ?>"></center>
							</div>
							<div style="font-size:20px;text-align:center;font-weight: bold">
								<div>
									<?php echo session()->descricaoOrganizacao; ?>
								</div>
								<div>
									FICHA DE MATERIAL
								</div>

							</div>

							<div style="font-family: 'Arial';margin-top:20px;height: 80mm;">
								<div style="text-align:left;font-weight: bold;font-size:16px">Código: <span id="codItemFicha"></span></div>
								<div style="text-align:left;font-weight: bold;font-size:16px">ITEM: <span id="descricaoItemFicha"></span></div>
								<div style="text-align:left;font-size:16px">CATEGORIA: <span id="categoriaItemFicha"></span></div>
								<div style="text-align:left;font-size:16px">EAN: <span id="neeFicha"></span></div>
								<div style="text-align:left;font-size:16px">EAN: <span id="eanFicha"></span></div>
								<div style="text-align:left;font-size:16px">SIRE: <span id="sireFicha"></span></div>
								<div style="text-align:left;font-size:16px">NME: <span id="nmeFicha"></span></div>
								<div style="text-align:left;font-size:16px">PP: <span id="ppFicha"></span></div>
								<div style="text-align:left;font-size:16px">OBSERVAÇÃO: <span id="observacaoFicha"></span></div>


							</div>


						</div>
						<div style="width:50% !important" class="col-sm-6 border">

							<div style="margin-top:10px" class="d-flex justify-content-center align-items-center h-100" id="qrcodeimprimirFichaA4"></div>


						</div>

					</div>






				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary" id="botaoImprimirFichaA4">Imprimir</button>
				<button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
			</div>
		</div>



	</div>
</div>

<div id="itensFarmaciaLoteEditModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Item de lote</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="itensFarmaciaLoteEditForm" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>itensFarmaciaLoteEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codLote" name="codLote" class="form-control" placeholder="CodLote" maxlength="11" required>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codDepositoEdit"> Depósito:</label>
								<select id="codDepositoEdit" name="codDeposito" class="custom-select">
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codLocalizacaoEdit"> Localização: </label>
								<select id="codLocalizacaoEdit" name="codLocalizacao" class="custom-select">
									<option value=""></option>
								</select>
							</div>
						</div>
					</div>


					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="nrLote"> Nº do Lote: <span class="text-danger">*</span> </label>
								<input type="text" id="nrLote" name="nrLote" class="form-control" placeholder="Nº do Lote" maxlength="50" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codBarra"> Código de Barras: </label>
								<input type="text" id="codBarra" name="codBarra" class="form-control" placeholder="Código de Barras" maxlength="64">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="quantidade"> Quantidade: <span class="text-danger">*</span> </label>
								<input type="text" id="quantidade" name="quantidade" class="form-control" placeholder="Quantidade" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataValidade"> Data Validade: </label>

								<div class="row">
									<input type="date" id="dataValidade" name="dataValidade" class="form-control" dateISO="true">
								</div>
								<div class="row">

									<span class="icheck-primary d-inline">
										<style>
											input[type=checkbox] {
												transform: scale(1.8);
											}
										</style>
										<input style="margin-left:5px;" name="validadeIndeterminada" type="checkbox" id="checkboxValidadeIndeterminada">
									</span>
									<label style="margin-left:10px" for="checkboxValidadeIndeterminada">Validade Indeterminada</label>

								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataInventario"> Data Inventário: </label>
								<input type="date" id="dataInventario" name="dataInventario" class="form-control" placeholder="DataInventario">
							</div>
						</div>
					</div>
					<div class="row">

						<div class="col-md-12">
							<div class="form-group">
								<label for="observacao"> Observação: </label>
								<textarea cols="40" rows="5" id="observacao" name="observacao" class="form-control" placeholder="Observação"></textarea>
							</div>
						</div>
					</div>

					<div class="row">

						<div class="col-md-12">
							<div class="card card-secondary">

								<div class="card-header">
									<h3 class="card-title">DADOS DA AQUISIÇÃO</h3>

									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
										</button>
									</div>
								</div>
								<div class="card-body">

									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label for="empenho"> Nº Requisição: </label>
												<input type="text" id="requisicao" name="requisicao" class="form-control" placeholder="Nº Requisição:">
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="empenho"> Nº Empenho: </label>
												<input type="text" id="empenho" name="empenho" class="form-control" placeholder="Nº Empenho">
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="empenho"> Nº NF: </label>
												<input type="text" id="nf" name="nf" class="form-control" placeholder="Nº NF">
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="empenho"> Valor: </label>
												<input type="number" step=".01" id="valorAquisicao" name="valorAquisicao" class="form-control">
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="itensFarmaciaLoteEditForm-btn">Salvar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>

			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>

<div id="itensFarmaciaLoteAddModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Item de Lote</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="itensFarmaciaLoteAddForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>itensFarmaciaLoteAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">


					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codDepositoAdd"> Depósito:</label>
								<select id="codDepositoAdd" name="codDeposito" class="custom-select">
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codLocalizacaoAdd"> Localização: </label>
								<select id="codLocalizacaoAdd" name="codLocalizacao" class="custom-select">
									<option value=""></option>
								</select>
							</div>
						</div>
					</div>



					<div class="row">
						<input type="hidden" id="codLoteAdd" name="codLote" class="form-control" placeholder="CodLote" maxlength="11" required>
						<input type="hidden" id="codItemAddLote" name="codItem" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="nrLote"> Nº do Lote: <span class="text-danger">*</span> </label>
								<input type="text" id="nrLoteAdd" name="nrLote" class="form-control" placeholder="Nº do Lote" maxlength="50" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="codBarraAdd"> Código de Barras: </label>
								<input type="text" id="codBarraAdd" name="codBarra" class="form-control" placeholder="Código de Barras" maxlength="64">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="quantidade"> Quantidade: <span class="text-danger">*</span> </label>
								<input type="number" step=".01" id="quantidadeAdd" name="quantidade" class="form-control" placeholder="Quantidade" required>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataValidade"> Data Validade: </label>

								<div class="row">
									<input type="date" id="dataValidadeAdd" name="dataValidade" class="form-control" dateISO="true">
								</div>
								<div class="row">

									<span class="icheck-primary d-inline">
										<style>
											input[type=checkbox] {
												transform: scale(1.8);
											}
										</style>
										<input style="margin-left:5px;" name="validadeIndeterminada" type="checkbox" id="checkboxValidadeIndeterminadaAdd">
									</span>
									<label style="margin-left:10px" for="checkboxValidadeIndeterminadaAdd">Validade Indeterminada</label>

								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataInventario"> Data Inventário: </label>
								<input type="date" id="dataInventarioAdd" name="dataInventario" class="form-control" placeholder="DataInventario">
							</div>
						</div>
					</div>

					<div class="row">

						<div class="col-md-12">
							<div class="form-group">
								<label for="observacao"> Observação: </label>
								<textarea cols="40" rows="5" id="observacaoAdd" name="observacao" class="form-control" placeholder="Observação"></textarea>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="card card-secondary">

								<div class="card-header">
									<h3 class="card-title">DADOS DA AQUISIÇÃO</h3>

									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
										</button>
									</div>
								</div>
								<div class="card-body">

									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label for="empenho"> Nº Requisição: </label>
												<input type="text" id="requisicaoAdd" name="requisicao" class="form-control" placeholder="Nº Requisição:">
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="empenho"> Nº Empenho: </label>
												<input type="text" id="empenhoAdd" name="empenho" class="form-control" placeholder="Nº Empenho">
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="empenho"> Nº NF: </label>
												<input type="text" id="nfAdd" name="nf" class="form-control" placeholder="Nº NF">
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="empenho"> Valor: </label>
												<input type="number" step=".01" id="valorAquisicaoAdd" name="valorAquisicao" class="form-control">
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>



					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="itensFarmaciaLoteAddForm-btn">Adicionar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>



<?php
echo view('tema/rodape');
?>
<script src="<?php echo base_url() ?>/assets/adminlte/plugins/barcode/JsBarcode.all.min.js"></script>

<script>
	codItemTmp = "";
	codBarraTmp = "";


	$(document).on('show.bs.modal', '.modal', function() {
		var zIndex = 1040 + (10 * $('.modal:visible').length);
		$(this).css('z-index', zIndex);
		setTimeout(function() {
			$('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
		}, 0);
	});

	$(function() {


		avisoPesquisa('Farmácia', 2);

		$.ajax({
			url: '<?php echo base_url('itensFarmacia/listaDropDown') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(categoriaItem) {

				$("#codCategoriaFiltro").select2({
					data: categoriaItem,
				})

				$("#codCategoriaFiltro").val(null); // Select the option with a value of '1'
				$("#codCategoriaFiltro").trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});



			}
		})





		$("#codCategoriaFiltro").on("change", function() {

			var codCategoria = document.getElementById("codCategoriaFiltro").value;

			$('#data_tableitensFarmacia').DataTable({
				"bDestroy": true,
				"paging": true,
				"pageLength": 50,
				"deferRender": true,
				"lengthChange": false,
				"searching": true,
				"ordering": true,
				"info": true,
				"autoWidth": false,
				"responsive": true,
				"ajax": {
					"url": '<?php echo base_url('itensFarmacia/getAll') ?>',
					"type": "POST",
					"dataType": "json",
					async: "true",
					data: {
						codCategoria: codCategoria,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					}
				}
			});


		});



	});

	function escolhaMeioAddLote() {
		codBarraTmp = '';
		$('#escolhaMeioAddLoteModal').modal('show');


		let code = "";
		let reading = false;


		document.addEventListener('keypress', e => {
			//usually scanners throw an 'Enter' key at the end of read
			if (e.keyCode === 13) {
				if (code.length > 10) {

					codBarraTmp = code;


					if ($('#escolhaMeioAddLoteModal').is(':visible')) {
						additensFarmaciaLote();
					}



					/// code ready to use                
					code = "";
				}
			} else {
				code += e.key; //while this is not an 'enter' it stores the every key            
			}

			//run a timeout of 200ms at the first read and clear everything
			if (!reading) {
				reading = true;
				setTimeout(() => {
					code = "";
					reading = false;
				}, 200); //200 works fine for me but you can adjust it
			}
		});

	}

	function additensFarmaciaLote() {

		document.removeEventListener('keypress', e => {});


		// reset the form 
		$("#itensFarmaciaLoteAddForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#itensFarmaciaLoteAddModal').modal('show');
		$('#escolhaMeioAddLoteModal').modal('hide');

		if (codBarraTmp !== '') {

			$("#itensFarmaciaLoteAddForm #codBarraAdd").focus();
			$("#itensFarmaciaLoteAddForm #codBarraAdd").val(codBarraTmp);
		}


		document.getElementById("codItemAddLote").value = codItemTmp;


		$(document).ready(function() {
			//set initial state.
			$('#checkboxValidadeIndeterminadaAdd').change(function() {
				if (this.checked) {
					document.getElementById('dataValidadeAdd').type = 'hidden';
				} else {
					document.getElementById('dataValidadeAdd').type = 'date';

				}
			});
		});



		$.ajax({
			url: '<?php echo base_url('depositos/listaDropDown') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(depositoAdd) {

				$("#codDepositoAdd").select2({
					data: depositoAdd,
				})

				$('#codDepositoAdd').val(null); // Select the option with a value of '1'
				$('#codDepositoAdd').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

				$('#codDepositoAdd').select2({
					dropdownParent: $('#itensFarmaciaLoteAddModal')
				});

			}
		})


		$("#codDepositoAdd").on("change", function() {

			codDeposito = document.getElementById("codDepositoAdd").value;


			$('#codLocalizacaoAdd').html('').select2({
				data: [{
					id: null,
					text: ''
				}]
			});


			$.ajax({
				url: '<?php echo base_url('depositosLocalizacao/listaDropDownPorDeposito') ?>',
				type: 'post',
				dataType: 'json',
				data: {
					codDeposito: codDeposito,
					csrf_sandra: $("#csrf_sandraPrincipal").val(),
				},

				success: function(localizacaoAdd) {

					$("#codLocalizacaoAdd").select2({
						data: localizacaoAdd,
					})

					$('#codLocalizacaoAdd').val(null); // Select the option with a value of '1'
					$('#codLocalizacaoAdd').trigger('change');
					$(document).on('select2:open', () => {
						document.querySelector('.select2-search__field').focus();
					});


					$('#codLocalizacaoAdd').select2({
						dropdownParent: $('#itensFarmaciaLoteAddModal')
					});

				}
			})


		});



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

				var form = $('#itensFarmaciaLoteAddForm');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('itensFarmaciaLote/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					success: function(response) {

						if (response.success === true) {
							$('#itensFarmaciaLoteAddModal').modal('hide');
							$('#itensFarmaciaEditModal').modal('show');
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
								$('#data_tableitensFarmaciaLote').DataTable().ajax.reload(null, false).draw(false);
								$('#data_tableitensFarmacia').DataTable().ajax.reload(null, false).draw(false);

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
					}
				});

				return false;
			}
		});
		$('#itensFarmaciaLoteAddForm').validate();
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




	function additensFarmacia() {
		// reset the form 
		$("#itensFarmaciaAddForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#itensFarmaciaAddModal').modal('show');





		$.ajax({
			url: '<?php echo base_url('itensFarmacia/listaDropDown') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(categoriaItem) {

				$("#itensFarmaciaAddForm #codCategoriaAdd").select2({
					data: categoriaItem,
				})

				$("#itensFarmaciaAddForm #codCategoriaAdd").val(null); // Select the option with a value of '1'
				$("#itensFarmaciaAddForm #codCategoriaAdd").trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});



			}
		})



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

				var form = $('#itensFarmaciaAddForm');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('itensFarmacia/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					success: function(response) {

						if (response.success === true) {
							$('#itensFarmaciaAddModal').modal('hide');

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
								$('#data_tableitensFarmacia').DataTable().ajax.reload(null, false).draw(false);
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
					}
				});

				return false;
			}
		});
		$('#itensFarmaciaAddForm').validate();
	}



	function edititensFarmaciaLote(codLote) {


		$.ajax({
			url: '<?php echo base_url('itensFarmaciaLote/getOne') ?>',
			type: 'post',
			data: {
				codLote: codLote,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#itensFarmaciaLoteEditForm")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#itensFarmaciaLoteEditModal').modal('show');

				$("#itensFarmaciaLoteEditForm #codLote").val(response.codLote);
				$("#itensFarmaciaLoteEditForm #nrLote").val(response.nrLote);
				$("#itensFarmaciaLoteEditForm #codBarra").val(response.codBarra);
				$("#itensFarmaciaLoteEditForm #quantidade").val(response.quantidade);
				$("#itensFarmaciaLoteEditForm #dataValidade").val(response.dataValidade);
				$("#itensFarmaciaLoteEditForm #dataCriacao").val(response.dataCriacao);
				$("#itensFarmaciaLoteEditForm #dataAtualizacao").val(response.dataAtualizacao);
				$("#itensFarmaciaLoteEditForm #dataInventario").val(response.dataInventario);
				$("#itensFarmaciaLoteEditForm #observacao").val(response.observacao);
				$("#itensFarmaciaLoteEditForm #requisicao").val(response.requisicao);
				$("#itensFarmaciaLoteEditForm #empenho").val(response.empenho);
				$("#itensFarmaciaLoteEditForm #nf").val(response.nf);
				$("#itensFarmaciaLoteEditForm #valorAquisicao").val(response.valorAquisicao);
				if (response.validadeIndeterminada == 1) {
					document.getElementById("checkboxValidadeIndeterminada").checked = true;
					document.getElementById('dataValidade').type = 'hidden';
				} else {
					document.getElementById('dataValidade').type = 'date';
				}

				$(document).ready(function() {
					//set initial state.
					$('#checkboxValidadeIndeterminada').change(function() {
						if (this.checked) {
							document.getElementById('dataValidade').type = 'hidden';
						} else {
							document.getElementById('dataValidade').type = 'date';

						}
					});
				});



				$.ajax({
					url: '<?php echo base_url('depositos/listaDropDown') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(depositoEdit) {

						$("#codDepositoEdit").select2({
							data: depositoEdit,
						})

						$('#codDepositoEdit').val(response.codDeposito); // Select the option with a value of '1'
						$('#codDepositoEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

						$('#codDepositoEdit').select2({
							dropdownParent: $('#itensFarmaciaLoteEditModal')
						});

					}
				})


				$("#codDepositoEdit").on("change", function() {

					codDeposito = document.getElementById("codDepositoEdit").value;


					$('#codLocalizacaoEdit').html('').select2({
						data: [{
							id: null,
							text: ''
						}]
					});


					$.ajax({
						url: '<?php echo base_url('depositosLocalizacao/listaDropDownPorDeposito') ?>',
						type: 'post',
						dataType: 'json',
						data: {
							codDeposito: codDeposito,
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},

						success: function(localizacaoAdd) {

							$("#codLocalizacaoEdit").select2({
								data: localizacaoAdd,
							})

							$('#codLocalizacaoEdit').val(response.codLocalizacao); // Select the option with a value of '1'
							$('#codLocalizacaoEdit').trigger('change');
							$(document).on('select2:open', () => {
								document.querySelector('.select2-search__field').focus();
							});


							$('#codLocalizacaoEdit').select2({
								dropdownParent: $('#itensFarmaciaLoteEditModal')
							});

						}
					})


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
						var form = $('#itensFarmaciaLoteEditForm');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('itensFarmaciaLote/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',

							success: function(response) {

								if (response.success === true) {

									$("#itensFarmaciaEditForm #saldo").val(response.saldo);
									$('#itensFarmaciaLoteEditModal').modal('hide');
									$('#data_tableitensFarmaciaLote').DataTable().ajax.reload(null, false).draw(false);
									$('#data_tableitensFarmacia').DataTable().ajax.reload(null, false).draw(false);


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
								$('#itensFarmaciaLoteEditForm-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#itensFarmaciaLoteEditForm').validate();

			}
		});
	}

	function removeitensFarmaciaLote(codLote) {
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
					url: '<?php echo base_url('itensFarmaciaLote/remove') ?>',
					type: 'post',
					data: {
						codLote: codLote,
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
								$('#data_tableitensFarmaciaLote').DataTable().ajax.reload(null, false).draw(false);
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


	function imprimirFicha(codItem) {


		$('#fichaA4Modal').modal('show');

		document.getElementById("botaoImprimirFichaA4").onclick = function() {
			printElement(document.getElementById("areaImpressaoFichaA4"));

			window.print();
		}



		$.ajax({
			url: '<?php echo base_url('itensFarmacia/getOne') ?>',
			type: 'post',
			data: {
				codItem: codItem,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(responseFicha) {
				// reset the form 


				document.getElementById("codItemFicha").innerHTML = responseFicha.codItem;
				document.getElementById("neeFicha").innerHTML = responseFicha.nee;
				document.getElementById("descricaoItemFicha").innerHTML = responseFicha.descricaoItem;
				document.getElementById("categoriaItemFicha").innerHTML = responseFicha.descricaoCategoria;
				document.getElementById("observacaoFicha").innerHTML = responseFicha.observacao;
				document.getElementById("sireFicha").innerHTML = responseFicha.sire;
				document.getElementById("eanFicha").innerHTML = responseFicha.ean;
				document.getElementById("nmeFicha").innerHTML = responseFicha.nme;
				document.getElementById("ppFicha").innerHTML = responseFicha.pp;




				var URLComprovante = '<?php echo base_url() . "/itensFarmacia/ficha/?codItem=" ?>' + responseFicha.codItem + '&chechsum=' + responseFicha.checksum;

				document.getElementById("qrcodeimprimirFichaA4").innerHTML = "";
				qrcode = new QRCode("qrcodeimprimirFichaA4", {
					text: URLComprovante,
					width: 250,
					height: 250,
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
		});





	}


	function edititensFarmacia(codItem) {
		codItemTmp = codItem;

		$.ajax({
			url: '<?php echo base_url('itensFarmacia/getOne') ?>',
			type: 'post',
			data: {
				codItem: codItem,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#itensFarmaciaEditForm")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#itensFarmaciaEditModal').modal('show');

				$("#itensFarmaciaEditForm #codItem").val(response.codItem);
				$("#itensFarmaciaEditForm #nee").val(response.nee);
				$("#itensFarmaciaEditForm #descricaoItem").val(response.descricaoItem);
				$("#itensFarmaciaEditForm #valor").val(response.valor);
				$("#itensFarmaciaEditForm #saldo").val(response.saldo);
				$("#itensFarmaciaEditForm #observacao").val(response.observacao);
				$("#itensFarmaciaEditForm #sire").val(response.sire);
				$("#itensFarmaciaEditForm #ean").val(response.ean);
				$("#itensFarmaciaEditForm #nme").val(response.nme);
				$("#itensFarmaciaEditForm #dataValidade").val(response.dataValidade);
				$("#itensFarmaciaEditForm #antibiotico").val(response.antibiotico);


				if (response.ativo == '1') {
					document.getElementById("checkboxAtivoEdit").checked = true;
				}

				document.getElementById("botaoFicha").innerHTML =
					'<a href="#" onclick="imprimirFicha(' + codItem + ')">' +
					'<div style="margin-top:10px" class="d-flex justify-content-left" id="qrcodeFichaA4"></div>' +
					'</a>';



				$.ajax({
					url: '<?php echo base_url('itensFarmacia/listaDropDown') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(categoriaItem) {

						$("#itensFarmaciaEditForm #codCategoria").select2({
							data: categoriaItem,
						})

						$("#itensFarmaciaEditForm #codCategoria").val(response.codCategoria); // Select the option with a value of '1'
						$("#itensFarmaciaEditForm #codCategoria").trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});



					}
				})


				$('#data_tableitensFarmaciaLote').DataTable({
					"bDestroy": true,
					"paging": true,
					"deferRender": true,
					"lengthChange": false,
					"searching": true,
					"ordering": true,
					"info": true,
					"autoWidth": false,
					"responsive": true,
					"ajax": {
						"url": '<?php echo base_url('itensFarmaciaLote/getAllPorItem') ?>',
						"type": "POST",
						"dataType": "json",
						async: "true",
						data: {
							codItem: codItemTmp,
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						}
					}
				});



				var URLComprovante = '<?php echo base_url() . "/itensFarmacia/ficha/?codItem=" ?>' + response.codItem + '&chechsum=' + response.checksum;

				document.getElementById("qrcodeFichaA4").innerHTML = "";
				qrcode = new QRCode("qrcodeFichaA4", {
					text: URLComprovante,
					width: 250,
					height: 250,
					colorDark: "#000000",
					colorLight: "#ffffff",
					correctLevel: QRCode.CorrectLevel.H
				});


				JsBarcode("#barcode", 1, {
					lineColor: "#000",
					width: 4,
					height: 40,
					displayValue: false
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
						var form = $('#itensFarmaciaEditForm');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('itensFarmacia/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#itensFarmaciaEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
							},
							success: function(response) {

								if (response.success === true) {

									$('#itensFarmaciaEditModal').modal('hide');


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
										$('#data_tableitensFarmacia').DataTable().ajax.reload(null, false).draw(false);
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
								$('#itensFarmaciaEditForm-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#itensFarmaciaEditForm').validate();

			}
		});
	}

	function removeitensFarmacia(codItem) {

		Swal.fire({
			position: 'bottom-end',
			icon: 'warning',
			title: 'Funcionalidade desativada',
			html: 'Não é possível remover itens da farmácia. Desativa-o caso necessário',
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
					url: '<?php echo base_url('itensFarmacia/remove') ?>',
					type: 'post',
					data: {
						codItem: codItem,
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
								$('#data_tableitensFarmacia').DataTable().ajax.reload(null, false).draw(false);
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
		*/
	}
</script>