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
<link rel="stylesheet" href="<?php echo base_url() ?>/assets/adminlte/plugins/summernote/summernote-bs4.min.css">

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


<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-md-8 mt-2">
							<h3 style="font-size:30px;font-weight: bold;" class="card-title">Organizações</h3>
						</div>
						<div class="col-md-4">
							<button type="button" class="btn btn-block btn-primary" onclick="add()" title="Adicionar"> <i class="fa fa-plus"></i> Adicionar</button>
						</div>
					</div>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
					<table id="data_table" class="table table-striped table-hover table-sm">
						<thead>
							<tr>
								<th>Código</th>
								<th>Descrição</th>
								<th>Sigla</th>
								<th>Endereço</th>
								<th>CEP</th>
								<th>Telefone</th>
								<th>Cnpj</th>

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
<div id="add-modal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar <?php echo $title ?></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="add-form" class="pl-3 pr-3">
					<div class="row">
						<input type="hidden" id="<?php echo csrf_token() ?>add-modal" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

						<input type="hidden" id="codOrganizacao" name="codOrganizacao" class="form-control" placeholder="Código" maxlength="11" required>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="descricao"> Descrição: <span class="text-danger">*</span> </label>
								<input type="text" id="descricao" name="descricao" class="form-control" placeholder="Descrição" maxlength="100" required>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="siglaOrganizacao"> Sigla: <span class="text-danger">*</span> </label>
								<input type="text" id="siglaOrganizacaoAdd" name="siglaOrganizacao" class="form-control" placeholder="Sigla" maxlength="100" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="endereço"> Endereço: </label>
								<input type="text" id="endereço" name="endereço" class="form-control" placeholder="Endereço" maxlength="100">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="cidade"> Cidade: </label>
								<input type="text" id="cidade" name="cidade" class="form-control" placeholder="cidade" maxlength="100">
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">

								<label for="codEstadoFederacaoAdd"> UF: <span class="text-danger">*</span> </label>
								<select id="codEstadoFederacaoAdd" name="codEstadoFederacao" class="form-control select2" tabindex="-1" aria-hidden="true">
									<option value=""></option>
								</select>
							</div>
						</div>
					</div>




					<div class="row">

						<div class="col-md-4">
							<div class="form-group">
								<label for="endereço"> CEP: </label>
								<input type="text" id="cep" name="cep" class="form-control" placeholder="CEP" maxlength="100">
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="telefone"> Telefone: </label>
								<input type="text" id="telefone" name="telefone" class="form-control" placeholder="Telefone" maxlength="16">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="cnpj"> Cnpj: </label>
								<input type="text" id="cnpj" name="cnpj" class="form-control" placeholder="Cnpj" maxlength="15">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="checkboxmatriz"> Matriz: </label>
								<i type="button" class="fas fa-info-circle swalmatriz"></i>

								<div class="icheck-primary d-inline">
									<style>
										input[type=checkbox] {
											transform: scale(1.8);
										}
									</style>
									<input style="margin-left:5px;" name='matriz' type="checkbox" id="checkboxmatriz">


								</div>
							</div>
						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-xs btn-primary" id="add-form-btn">Adicionar</button>
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
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar <?php echo $title ?></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="col-12 col-sm-12">
					<div class="card card-primary">
						<div class="card-header p-0 border-bottom-0">
							<ul class="nav nav-tabs" id="Organizacoes" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" id="dadosOrganizacao-tab" data-toggle="pill" href="#dadosOrganizacao" role="tab" aria-controls="dadosOrganizacao" aria-selected="true">Geral</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="portalOrganizacao-tab" data-toggle="pill" href="#portalOrganizacao" role="tab" aria-controls="portalOrganizacao" aria-selected="false">Portal</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="imagensOrganizacao-tab" data-toggle="pill" href="#imagensOrganizacao" role="tab" aria-controls="imagensOrganizacao" aria-selected="false">Imagens</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="parametros-tab" data-toggle="pill" href="#parametros" role="tab" aria-controls="parametros" aria-selected="false">Parâmetros diversos</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="atributosSistema-tab" data-toggle="pill" href="#atributosSistema" role="tab" aria-controls="atributosSistema" aria-selected="false">Campos de Formulários</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="seguranca-tab" data-toggle="pill" href="#seguranca" role="tab" aria-controls="seguranca" aria-selected="false">Segurança</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="cabecalho-rodape-tab" data-toggle="pill" href="#cabecalho-rodape" role="tab" aria-controls="cabecalho-rodape" aria-selected="false">Cabeçalho e Rodapé</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="redesSociais-tab" data-toggle="pill" href="#redesSociais" role="tab" aria-controls="redesSociais" aria-selected="false">Redes Sociais</a>
								</li>
							</ul>
						</div>
						<div class="card-body">
							<div class="tab-content" id="OrganizacoesContent">
								<div class="tab-pane fade show active" id="dadosOrganizacao" role="tabpanel" aria-labelledby="dadosOrganizacao-tab">


									<form id="edit-form" class="pl-3 pr-3">
										<div class="row">
											<input type="hidden" id="<?php echo csrf_token() ?>edit-modal" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
											<input type="hidden" id="codOrganizacao" name="codOrganizacao" class="form-control" placeholder="Código" maxlength="11" required>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="descricao"> Descrição: <span class="text-danger">*</span> </label>
													<input type="text" id="descricao" name="descricao" class="form-control" placeholder="Descrição" maxlength="100" required>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="siglaOrganizacao"> Sigla: <span class="text-danger">*</span> </label>
													<input type="text" id="siglaOrganizacao" name="siglaOrganizacao" class="form-control" placeholder="Sigla" maxlength="100" required>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="endereço"> Endereço: </label>
													<input type="text" id="endereço" name="endereço" class="form-control" placeholder="Endereço" maxlength="100">
												</div>
											</div>
										</div>






										<div class="row">

											<div class="col-md-4">
												<div class="form-group">
													<label for="endereço"> CEP: </label>
													<input type="text" id="cep" name="cep" class="form-control" placeholder="CEP" maxlength="100">
												</div>
											</div>

											<div class="col-md-4">
												<div class="form-group">
													<label for="cidade"> Cidade: </label>
													<input type="text" id="cidade" name="cidade" class="form-control" placeholder="Cidade" maxlength="100">
												</div>
											</div>

											<div class="col-md-4">
												<div class="form-group">

													<label for="codEstadoFederacaoEdit"> UF: <span class="text-danger">*</span> </label>
													<select id="codEstadoFederacaoEdit" name="codEstadoFederacao" class="form-control select2" tabindex="-1" aria-hidden="true">
														<option value=""></option>
													</select>
												</div>
											</div>


										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="telefone"> Telefone: </label>
													<input type="text" id="telefone" name="telefone" class="form-control" placeholder="Telefone" maxlength="16">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="cnpj"> Cnpj: </label>
													<input type="text" id="cnpj" name="cnpj" class="form-control" placeholder="Cnpj" maxlength="15">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="site"> Site: </label>
													<input type="text" id="site" name="site" class="form-control" placeholder="https://URL do site" maxlength="200">
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<label for="checkboxmatriz">Matriz: </label>
													<i type="button" class="fas fa-info-circle swalmatriz"></i>

													<div class="icheck-primary d-inline">
														<style>
															input[type=checkbox] {
																transform: scale(1.8);
															}
														</style>
														<input style="margin-left:5px;" name='matriz' type="checkbox" id="checkboxmatrizEdit">


													</div>
												</div>
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
								<div class="tab-pane fade" id="portalOrganizacao" role="tabpanel" aria-labelledby="portalOrganizacao-tab">


									<div class="col-12 col-sm-12">
										<div class="card card-primary card-tabs">
											<div class="card-header p-0 pt-1">
												<ul class="nav nav-tabs" id="portal-tab" role="tablist">
													<li class="nav-item">
														<a class="nav-link active" id="portal-paletaCores-tab" data-toggle="pill" href="#portal-paletaCores" role="tab" aria-controls="portal-paletaCores" aria-selected="true">Paleta de Cores</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" id="portal-slideshow-tab" data-toggle="pill" href="#portal-slideshow" role="tab" aria-controls="portal-slideshow" aria-selected="true">SlideShow</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" id="portal-hero-tab" data-toggle="pill" href="#portal-hero" role="tab" aria-controls="portal-hero" aria-selected="true">Hero</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" id="portal-sobre-tab" data-toggle="pill" href="#portal-sobre" role="tab" aria-controls="portal-sobre" aria-selected="false">Sobre</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" id="portal-servicos-tab" data-toggle="pill" href="#portal-servicos" role="tab" aria-controls="portal-servicos" aria-selected="false">Serviços</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" id="portal-unidades-tab" data-toggle="pill" href="#portal-unidades" role="tab" aria-controls="portal-unidades" aria-selected="false">Unidades</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" id="portal-equipe-tab" data-toggle="pill" href="#portal-equipe" role="tab" aria-controls="portal-equipe" aria-selected="false">Equipe</a>
													</li>

													<li class="nav-item">
														<a class="nav-link" id="portal-convenios-tab" data-toggle="pill" href="#portal-convenios" role="tab" aria-controls="portal-convenios" aria-selected="false">Convênios</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" id="portal-contateNos-tab" data-toggle="pill" href="#portal-contateNos" role="tab" aria-controls="portal-contateNos" aria-selected="false">Contate-Nos</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" id="portal-redesSociais-tab" data-toggle="pill" href="#portal-redesSociais" role="tab" aria-controls="portal-redesSociais" aria-selected="false">Redes Sociais</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" id="portal-telaLogin-tab" data-toggle="pill" href="#portal-telaLogin" role="tab" aria-controls="portal-telaLogin" aria-selected="false">Tela de Login</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" id="portal-noticias-tab" data-toggle="pill" href="#portal-noticias" role="tab" aria-controls="portal-noticias" aria-selected="false">Notícias</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" id="portal-blog-tab" data-toggle="pill" href="#portal-blog" role="tab" aria-controls="portal-blog" aria-selected="false">Blog</a>
													</li>
												</ul>
											</div>
											<div class="card-body">
												<div class="tab-content" id="portal-tabContent">
													<div class="tab-pane fade show active" id="portal-paletaCores" role="tabpanel" aria-labelledby="portal-paletaCores-tab">

														<form id="paletaCores-form" class="pl-3 pr-3">
															<div class="row">
																<input type="hidden" id="<?php echo csrf_token() ?>paletaCores-form" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
															</div>


															<div class="row">
																<div class="col-md-6">
																	<div class="card card-secondary">

																		<div class="card-header">
																			<h3 class="card-title">TEMA</h3>

																		</div>
																		<div class="card-body">

																			<div class="row">
																				<div class="col-md-6">
																					<div class="form-group">
																						<label for="corFundoPrincipal"> Cor de Fundo Principal:</label>
																						<input type="color" id="corFundoPrincipal" name="corFundoPrincipal" class="form-control" placeholder="" maxlength="12" required>
																					</div>
																				</div>

																				<div class="col-md-6">
																					<div class="form-group">
																						<label for="corTextoPrincipal"> Cor de Texto Principal:</label>
																						<input type="color" id="corTextoPrincipal" name="corTextoPrincipal" class="form-control" placeholder="" maxlength="12" required>
																					</div>
																				</div>
																			</div>

																		</div>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="card card-secondary">

																		<div class="card-header">
																			<h3 class="card-title">MENUS</h3>

																		</div>
																		<div class="card-body">

																			<div class="row">
																				<div class="col-md-6">
																					<div class="form-group">
																						<label for="corMenus"> Cor Menu:</label>
																						<input type="color" id="corMenus" name="corMenus" class="form-control" placeholder="" maxlength="12" required>
																					</div>
																				</div>

																				<div class="col-md-6">
																					<div class="form-group">
																						<label for="corTextoMenus"> Cor de Texto Menu:</label>
																						<input type="color" id="corTextoMenus" name="corTextoMenus" class="form-control" placeholder="" maxlength="12" required>
																					</div>
																				</div>
																			</div>
																			<div class="row">
																				<div class="col-md-6">
																					<div class="form-group">
																						<label for="corBackgroundMenus"> Cor Fundo Menu:</label>
																						<input type="color" id="corBackgroundMenus" name="corBackgroundMenus" class="form-control" placeholder="" maxlength="12" required>
																					</div>
																				</div>
																			</div>

																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="col-md-6">
																	<div class="card card-secondary">

																		<div class="card-header">
																			<h3 class="card-title">GRADE TABELAS</h3>

																		</div>
																		<div class="card-body">

																			<div class="row">
																				<div class="col-md-6">
																					<div class="form-group">
																						<label for="corLinhaTabela"> Cor de Linhas:</label>
																						<input type="color" id="corLinhaTabela" name="corLinhaTabela" class="form-control" placeholder="" maxlength="12" required>
																					</div>
																				</div>

																				<div class="col-md-6">
																					<div class="form-group">
																						<label for="corTextoTabela"> Cor de Texto:</label>
																						<input type="color" id="corTextoTabela" name="corTextoTabela" class="form-control" placeholder="" maxlength="12" required>
																					</div>
																				</div>
																			</div>

																		</div>
																	</div>
																</div>
															</div>



															<div class="form-group text-center">
																<div class="btn-group">
																	<button type="button" onclick="salvaPaletaCores()" class="btn btn-xs btn-primary">Salvar</button>
																	<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
																</div>
															</div>
														</form>

													</div>

													<div class="tab-pane fade" id="portal-slideshow" role="tabpanel" aria-labelledby="portal-slideshow-tab">


														<div class="col-md-4">
															<button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addslideshow()" title="Adicionar">Adicionar</button>
														</div>


														<div class="card-body">
															<table id="data_tableslideshow" class="table table-striped table-hover table-sm">
																<thead>
																	<tr>
																		<th>Ordem</th>
																		<th>Descrição</th>
																		<th>Imagem</th>
																		<th>Url</th>
																		<th>Data Expiração</th>
																		<th>Status</th>
																		<th></th>
																	</tr>
																</thead>
															</table>
														</div>

													</div>
													<div class="tab-pane fade" id="portal-hero" role="tabpanel" aria-labelledby="portal-hero-tab">

														<form id="hero-form" class="pl-3 pr-3">
															<div class="row">
																<input type="hidden" id="<?php echo csrf_token() ?>hero-form" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
															</div>

															<div class="row">
																<div class="col-md-12">
																	<div class="form-group">
																		<label for="ativoHero">Ativo: </label>
																		<i type="button" class="fas fa-info-circle swalmatriz"></i>

																		<div class="icheck-primary d-inline">
																			<style>
																				input[type=checkbox] {
																					transform: scale(1.8);
																				}
																			</style>
																			<input style="margin-left:5px;" id="ativoHero" name="ativoHero" type="checkbox">


																		</div>


																	</div>
																</div>
															</div>

															<div class="row">
																<div class="col-md-6">

																</div>
																<div id="heroImagem" class="col-md-6">

																</div>
															</div>

															<div class="form-group text-center">
																<div class="btn-group">
																	<button type="button" onclick="salvaHero()" class="btn btn-xs btn-primary">Salvar</button>
																	<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
																</div>
															</div>
														</form>
													</div>
													<div class="tab-pane fade" id="portal-sobre" role="tabpanel" aria-labelledby="portal-sobre-tab">
														Quem Somos
													</div>
													<div class="tab-pane fade" id="portal-servicos" role="tabpanel" aria-labelledby="portal-servicos-tab">
														servicos
													</div>
													<div class="tab-pane fade" id="portal-unidades" role="tabpanel" aria-labelledby="portal-unidades-tab">
														unidades
													</div>
													<div class="tab-pane fade" id="portal-equipe" role="tabpanel" aria-labelledby="portal-equipe-tab">
														equipe
													</div>
													<div class="tab-pane fade" id="portal-convenios" role="tabpanel" aria-labelledby="portal-convenios-tab">
														convenios
													</div>
													<div class="tab-pane fade" id="portal-contateNos" role="tabpanel" aria-labelledby="portal-contateNos-tab">
														contateNos
													</div>
													<div class="tab-pane fade" id="portal-redesSociais" role="tabpanel" aria-labelledby="portal-redesSociais-tab">
														redesSociais
													</div>
													<div class="tab-pane fade" id="portal-telaLogin" role="tabpanel" aria-labelledby="portal-telaLogin-tab">
														telaLogin
													</div>
													<div class="tab-pane fade" id="portal-noticias" role="tabpanel" aria-labelledby="portal-noticias-tab">
														noticias
													</div>
													<div class="tab-pane fade" id="portal-blog" role="tabpanel" aria-labelledby="portal-blog-tab">
														blog
													</div>
												</div>
											</div>
											<!-- /.card -->
										</div>
									</div>

								</div>


								<div class="tab-pane fade" id="imagensOrganizacao" role="tabpanel" aria-labelledby="imagensOrganizacao-tab">

									<div class="row ">
										<div class="col-md-4">
											<div class="card card-primary shadow">
												<div class="card-header">
													<h3 class="card-title">LOGO</h3>

													<div class="card-tools">
														<button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
														</button>
													</div>
													<!-- /.card-tools -->
												</div>
												<!-- /.card-header -->
												<div style="height:300px;" class="card-body">

													<div style="margin-left: 15px" class="row">

														<div>
															<img id="logoOrganizacao" style="margin-bottom: 30px;display: block;  margin-left: auto;  margin-right: auto;  width: 50%;" width="150px" height="auto">
														</div>
													</div>
												</div>
												<button style="margin-left:5px;margin-right:5px;margin-bottom:5px" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modal-logo">
													TROCAR
												</button>
												<div class="modal fade" id="modal-logo">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header bg-primary">
																<h4 class="modal-title">Enviar Arquivo</h4>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">&times;</span>
																</button>
															</div>
															<div class="modal-body  bg-light text-dark">
																<form id="formLogo" method="post" accept-charset="utf-8" enctype="multipart/form-data">
																	<div class="form-group">
																		<input type="hidden" id="<?php echo csrf_token() ?>formLogo" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

																		<label for="formGroupExampleInput">Selecione o arquivo desejado</label>
																		<input type="file" name="userFileLogo" class="form-control" id="userFileLogo" style="height:45px;">
																	</div>
																	<div class="form-group">
																		<button type="button" onclick="envia_logo()" class="btn btn-xs btn-primary">Enviar</button>
																	</div>
																</form>
															</div>
														</div>
														<!-- /.modal-content -->
													</div>
													<!-- /.modal-dialog -->
												</div>
												<!-- /.modal -->
												<!-- /.card-body -->
											</div>
											<!-- /.card -->
										</div>


										<div class="col-md-4">
											<div class="card card-primary shadow">
												<div class="card-header">
													<h3 class="card-title">PLANO DE FUNDO</h3>

													<div class="card-tools">
														<button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
														</button>
													</div>
													<!-- /.card-tools -->
												</div>
												<!-- /.card-header -->
												<div style="height:300px;" class="card-body">

													<div style="margin-left: 15px" class="row">

														<div>
															<img id="fundoOrganizacao" style="margin-bottom: 30px;display: block;  margin-left: auto;  margin-right: auto;  width: 50%;" width="150px" height="auto">
														</div>
													</div>
												</div>
												<button style="margin-left:5px;margin-right:5px;margin-bottom:5px" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modal-fundo">
													TROCAR
												</button>
												<div class="modal fade" id="modal-fundo">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header bg-primary">
																<h4 class="modal-title">Enviar Arquivo</h4>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">&times;</span>
																</button>
															</div>
															<div class="modal-body  bg-light text-dark">
																<form id="formFundo" method="post" accept-charset="utf-8" enctype="multipart/form-data">
																	<input type="hidden" id="<?php echo csrf_token() ?>formFundo" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

																	<div class="form-group">
																		<label for="formGroupExampleInput">Selecione o arquivo desejado</label>
																		<input type="file" name="userFileFundo" class="form-control" id="userFileFundo" style="height:45px;">
																	</div>
																	<div class="form-group">
																		<button type="button" onclick="envia_fundo()" class="btn btn-xs btn-primary">Enviar</button>
																	</div>
																</form>
															</div>
														</div>
														<!-- /.modal-content -->
													</div>
													<!-- /.modal-dialog -->
												</div>
												<!-- /.modal -->
												<!-- /.card-body -->
											</div>
											<!-- /.card -->
										</div>

										<div class="col-md-4">
											<div class="card card-primary shadow">
												<div class="card-header">
													<h3 class="card-title">FOTO DO LOCAL</h3>

													<div class="card-tools">
														<button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
														</button>
													</div>
													<!-- /.card-tools -->
												</div>
												<!-- /.card-header -->
												<div style="height:300px;" class="card-body">

													<div style="margin-left: 15px" class="row">

														<div>
															<img id="fotoOrganizacao" style="margin-bottom: 30px;display: block;  margin-left: auto;  margin-right: auto;  width: 50%;" width="150px" height="auto">
														</div>
													</div>
												</div>
												<button style="margin-left:5px;margin-right:5px;margin-bottom:5px" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modal-foto">
													TROCAR
												</button>
												<div class="modal fade" id="modal-foto">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header bg-primary">
																<h4 class="modal-title">Enviar Arquivo</h4>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">&times;</span>
																</button>
															</div>
															<div class="modal-body  bg-light text-dark">
																<form id="formFoto" method="post" accept-charset="utf-8" enctype="multipart/form-data">
																	<input type="hidden" id="<?php echo csrf_token() ?>formFoto" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

																	<div class="form-group">
																		<label for="formGroupExampleInput">Selecione o arquivo desejado</label>
																		<input type="file" name="userFileFoto" class="form-control" id="userFileFoto" style="height:45px;">
																	</div>
																	<div class="form-group">
																		<button type="button" onclick="envia_foto()" class="btn btn-xs btn-primary">Enviar</button>
																	</div>
																</form>
															</div>
														</div>
														<!-- /.modal-content -->
													</div>
													<!-- /.modal-dialog -->
												</div>
												<!-- /.modal -->
												<!-- /.card-body -->
											</div>
											<!-- /.card -->
										</div>

									</div>

								</div>
								<div class="tab-pane fade" id="parametros" role="tabpanel" aria-labelledby="parametros-tab">

									<form id="diversos-form" method="post">



										<div class="row">
											<input type="hidden" id="<?php echo csrf_token() ?>diversos-form" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

											<input type="hidden" id="codOrganizacao" name="codOrganizacao" class="form-control" placeholder="Código" maxlength="11" required>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="checkboxPrimary1"> Permitir que os usuários registrem-se: <span class="text-danger">*</span> </label>
													<i type="button" class="fas fa-info-circle swalAutoCadastro"></i>

													<div class="icheck-primary d-inline">
														<style>
															input[type=checkbox] {
																transform: scale(1.8);
															}
														</style>
														<input style="margin-left:5px;" name='permiteAutocadastro' id='permiteAutocadastro' type="checkbox" id="checkboxPrimary1">


													</div>
												</div>
											</div>

											<div class="col-md-4">
												<div class="form-group">
													<label for="formularioRegistro"> Formulário de Registro: <span class="text-danger">*</span> </label>
													<select id="formularioRegistro" name="formularioRegistro" class="form-control" class="custom-select">
														<option value="1">Simplificado</option>
														<option value="2">Completo</option>
													</select>
												</div>
											</div>


											<div class="col-md-4">
												<div class="form-group">
													<label for="timezone"> Timezone: <span class="text-danger">*</span> </label>
													<i type="button" class="fas fa-info-circle swaltimezone"></i>

													<?php echo listboxTimezone($this); ?>
												</div>
											</div>

										</div>

										<div class="row">

											<div class="col-md-4">
												<div class="form-group">
													<label for="chaveSalgada"> Chave Salgada: <span class="text-danger">*</span> </label>
													<i type="button" class="fas fa-info-circle swalSenhaSlgada"></i>
													<input style="width:252px" name='chaveSalgada' id='chaveSalgada' class="form-control" type="text" placeholder="" value="<?php echo  $organizacao->chaveSalgada ?>">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="tempoInatividade"> Tempo Inatividade: <span class="text-danger">*</span> </label>
													<i type="button" class="fas fa-info-circle swalTempoInatividade"></i>
													<input style="width:252px" name='tempoInatividade' id='tempoInatividade' class="form-control" type="text" placeholder="" value="<?php echo  $organizacao->tempoInatividade ?>">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="forcarExpiracao"> Foçar expiração em: <span class="text-danger">*</span> </label>
													<i type="button" class="fas fa-info-circle swalforcarExpiracao"></i>
													<input style="width:252px" name='forcarExpiracao' id='forcarExpiracao' class="form-control" type="text" placeholder="" value="<?php echo  $organizacao->forcarExpiracao ?>">
												</div>
											</div>


										</div>
										<div style="margin-bottom:20px" class="row">

											<div class="col-md-4">
												<div class="form-group">
													<label for="forcarExpiracao"> Perfil Padrão de novas contas </label>

													<?php echo listboxPerfis($this) ?>
												</div>
											</div>


											<div class="col-md-4">
												<div class="form-group">
													<label for="forcarExpiracao"> Nome de Exibição do usuário no Sistema</label>
													<select class="form-control" id="nomeExibicaoSistema" name="nomeExibicaoSistema">
														<option value="1">Livre digitação</option>
														<option value="2">Nome Completo</option>
														<option value="3">Nome Principal</option>
														<option value="4">Nome Principal + Cargo</option>
														<option value="5">Cargo + Nome Principal</option>
													</select>

												</div>
											</div>


										</div>


										<input type="button" onclick="salvaDiversos()" value="SALVAR" class="btn btn-xs btn-primary">

									</form>
								</div>
								<div class="tab-pane fade" id="atributosSistema" role="tabpanel" aria-labelledby="atributosSistema-tab">


									<div class="card-header">
										<div class="row">
											<div class="col-md-8 mt-2">
												<h3 style="font-size:30px;font-weight: bold;" class="card-title">Atributos Sistema Organização</h3>
											</div>
											<div class="col-md-4">
											</div>
										</div>
									</div>
									<!-- /.card-header -->
									<div class="card-body">
										<table id="data_tableatributosSistemaOrganizacao" class="table table-striped table-hover table-sm">
											<thead>
												<tr>
													<th>Código</th>
													<th>Atributo Sistema</th>
													<th>Descrição</th>
													<th>Formulários</th>
													<th>Cadastro Rápido</th>
													<th>LDAP</th>
													<th>Obrigatório</th>

													<th></th>
												</tr>
											</thead>
										</table>
									</div>
									<!-- /.card-body -->


									<div id="edit-modalatributosSistemaOrganizacao" class="modal fade" role="dialog" aria-hidden="true">
										<div class="modal-dialog modal-xl">
											<div class="modal-content">
												<div class="modal-header bg-primary text-center p-3">
													<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Atributos Sistema Organização</h4>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">×</span>
													</button>
												</div>
												<div class="modal-body">
													<form id="edit-formatributosSistemaOrganizacao" class="pl-3 pr-3">
														<div class="row">
															<input type="hidden" id="<?php echo csrf_token() ?>edit-modalatributosSistemaOrganizacao" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
															<input type="hidden" id="codAtributosSistemaOrganizacao" name="codAtributosSistemaOrganizacao" class="form-control" placeholder="Código" maxlength="11" required>
															<input type="hidden" id="codOrganizacao" name="codOrganizacao" class="form-control" placeholder="Código" maxlength="11" required>

														</div>



														<div class="row">

															<div class="col-md-4">
																<div class="form-group">
																	<label for="nomeAtributoSistema"> Atributo Sistema: </label>
																	<input readonly type="text" id="nomeAtributoSistema" name="nomeAtributoSistema" class="form-control" placeholder="Atributo Sistema" maxlength="150" required>
																</div>
															</div>
															<div class="col-md-4">
																<div class="form-group">
																	<label for="descricaoAtributoSistema"> Descrição: <span class="text-danger">*</span> </label>
																	<input type="text" id="descricaoAtributoSistema" name="descricaoAtributoSistema" class="form-control" placeholder="Descrição" maxlength="150" required>
																</div>
															</div>
														</div>

														<div class="row">
															<div class="col-md-4">
																<div class="form-group">
																	<label for="checkboxvisivelFormulario"> Mostrar no formulário do usuário?: </label>
																	<i type="button" class="fas fa-info-circle swalvisivelFormulario"></i>

																	<div class="icheck-primary d-inline">
																		<style>
																			input[type=checkbox] {
																				transform: scale(1.8);
																			}
																		</style>
																		<input style="margin-left:5px;" name='visivelFormulario' id='visivelFormulario' type="checkbox" id="checkboxvisivelFormulario">


																	</div>
																</div>
															</div>
														</div>



														<div class="row">
															<div class="col-md-4">
																<div class="form-group">
																	<label for="checkboxvisivelLDAP"> Mostrar para integração LDAP?: </label>
																	<i type="button" class="fas fa-info-circle swalvisivelLDAP"></i>

																	<div class="icheck-primary d-inline">
																		<style>
																			input[type=checkbox] {
																				transform: scale(1.8);
																			}
																		</style>
																		<input style="margin-left:5px;" name='visivelLDAP' id='visivelLDAP' type="checkbox" id="checkboxvisivelLDAP">


																	</div>
																</div>
															</div>
														</div>

														<div class="row">
															<div class="col-md-6">
																<div class="form-group">
																	<label for="checkboxcadastroRapido"> Mostrar campo no formulário de cadastro Rápido?: </label>
																	<i type="button" class="fas fa-info-circle swalcadastroRapido"></i>

																	<div class="icheck-primary d-inline">
																		<style>
																			input[type=checkbox] {
																				transform: scale(1.8);
																			}
																		</style>
																		<input style="margin-left:5px;" name='cadastroRapido' id='cadastroRapido' type="checkbox" id="checkboxcadastroRapido">


																	</div>
																</div>
															</div>
														</div>


														<div class="row">
															<div class="col-md-4">
																<div class="form-group">
																	<label for="checkboxobrigatorio"> É campo obrigatório: </label>
																	<i type="button" class="fas fa-info-circle swalobrigatorio"></i>

																	<div class="icheck-primary d-inline">
																		<style>
																			input[type=checkbox] {
																				transform: scale(1.8);
																			}
																		</style>
																		<input style="margin-left:5px;" name='obrigatorio' id='obrigatorio' type="checkbox" id="checkboxobrigatorio">


																	</div>
																</div>
															</div>
														</div>

														<div class="form-group text-center">
															<div class="btn-group">
																<button type="submit" class="btn btn-xs btn-primary" id="edit-formatributosSistemaOrganizacao-btn">Salvar</button>
																<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
															</div>
														</div>
													</form>

												</div>
											</div><!-- /.modal-content -->
										</div><!-- /.modal-dialog -->
									</div>

								</div>


								<div class="tab-pane fade" id="seguranca" role="tabpanel" aria-labelledby="seguranca-tab">


									<div class="row">
										<div class="col-md-12">

											<div class="card card-primary">
												<div class="card-header">
													<h3 class="card-title">GERRENCIAR ADMINSTRADOR MASTER</h3>

													<div class="card-tools">
														<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
														</button>
													</div>
													<!-- /.card-tools -->
												</div>
												<!-- /.card-header -->
												<div class="card-body">


													<form id="seguranca-form" method="post">

														<div class="row">
															<input type="hidden" id="<?php echo csrf_token() ?>seguranca-form" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

															<input type="hidden" id="codOrganizacao" name="codOrganizacao" class="form-control" placeholder="Código" maxlength="11" required>

															<div class="col-md-4">
																<div class="form-group">
																	<label for="loginAdmin"> Login Admin: <span class="text-danger">*</span> </label>
																	<i type="button" class="fas fa-info-circle swalLoginAdmin"></i>
																	<input readonly style="width:252px" name='loginAdmin' id='loginAdmin' class="form-control" type="text" placeholder="" value="<?php echo  $organizacao->loginAdmin ?>">
																</div>
															</div>

															<div class="col-md-4">
																<div class="form-group">
																	<label for="senhaAdmin"> Senha Admin: <span class="text-danger">*</span> </label>
																	<i type="button" class="fas fa-info-circle swalSenhaAdmin"></i>
																	<input style="width:252px" name='senhaAdmin' id='senhaAdmin' class="form-control" type="password" placeholder="" value="<?php echo  $organizacao->senhaAdmin ?>">
																</div>
															</div>
															<div class="col-md-4">
																<div class="form-group">
																	<label for="confirmacao"> Confirmação: <span class="text-danger">*</span> </label>
																	<i type="button" class="fas fa-info-circle swalSenhaAdmin"></i>
																	<input style="width:252px" name='confirmacao' id='confirmacao' class="form-control" type="password" placeholder="" value="<?php echo  $organizacao->senhaAdmin ?>">
																</div>
															</div>
														</div>
														<input type="button" onclick="salvaSenhaAdmin()" value="SALVAR" class="btn btn-xs btn-primary">

													</form>
												</div>
												<!-- /.card-body -->
											</div>
											<!-- /.card -->
										</div>
									</div>

									<div class="row">
										<div class="col-md-12">

											<div class="card card-primary">
												<div class="card-header">
													<h3 class="card-title">POLÍTICA DE SENHA</h3>

													<div class="card-tools">
														<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
														</button>
													</div>
													<!-- /.card-tools -->
												</div>
												<!-- /.card-header -->
												<div class="card-body">

													<form id="politicaSenha-form" method="post">
														<input type="hidden" id="<?php echo csrf_token() ?>politicaSenha-form" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

														<input id="codOrganizacao" name="codOrganizacao" type="hidden">
														<div class="row">
															<div class="col-md-4">
																<div class="form-group">
																	<label for="politicaSenha">Ativar política de senha? </label>
																	<div class="icheck-primary d-inline">
																		<style>
																			input[type=checkbox] {
																				transform: scale(1.8);
																			}
																		</style>
																		<input style="margin-left:5px;" name='politicaSenha' id='politicaSenha' type="checkbox">


																	</div>
																</div>
															</div>
														</div>
														<div class="row">


															<div class="col-md-4">
																<div class="form-group">
																	<label for="UltimasSenhas"> Senha diferentas das Ultimas: </span> </label>
																	<input style="width:50px" name='diferenteUltimasSenhas' id='diferenteUltimasSenhas' type="number">
																</div>
															</div>


															<div class="col-md-4">
																<div class="form-group">
																	<label for="MinimodeCaracteres"> Mínimo de Caracteres: </span> </label>
																	<input style="width:50px" name='minimoCaracteres' id='minimoChar' type="number">
																</div>
															</div>



															<div class="col-md-4">
																<div class="form-group">
																	<label for="checkboxSenhaNaoSimples">Não permitir senha trivial: </label>
																	<div class="icheck-primary d-inline">
																		<style>
																			input[type=checkbox] {
																				transform: scale(1.8);
																			}
																		</style>
																		<input style="margin-left:5px;" name='senhaNaoSimples' id='NaoSimples' type="checkbox">


																	</div>
																</div>
															</div>


														</div>

														<div class="row">



															<div class="col-md-4">
																<div class="form-group">
																	<label for="checkboxNumeros">Deve conter Números: </label>
																	<div class="icheck-primary d-inline">
																		<style>
																			input[type=checkbox] {
																				transform: scale(1.8);
																			}
																		</style>
																		<input style="margin-left:5px;" name='numeros' id='idnumeros' type="checkbox">


																	</div>
																</div>
															</div>


															<div class="col-md-4">
																<div class="form-group">
																	<label for="checkboxLetras">Deve conter Letras: </label>
																	<div class="icheck-primary d-inline">
																		<style>
																			input[type=checkbox] {
																				transform: scale(1.8);
																			}
																		</style>
																		<input style="margin-left:5px;" name='letras' id='letras' type="checkbox">


																	</div>
																</div>
															</div>
															<div class="col-md-4">
																<div class="form-group">
																	<label for="checkboxMaiusculo">Deve conter Letras Maiúscula: </label>
																	<div class="icheck-primary d-inline">
																		<style>
																			input[type=checkbox] {
																				transform: scale(1.8);
																			}
																		</style>
																		<input style="margin-left:5px;" name='maiusculo' id='maiusculo' type="checkbox">


																	</div>
																</div>
															</div>

														</div>
														<div class="row">



															<div class="col-md-6">
																<div class="form-group">
																	<label for="checkboxCaracteresEspeciais">Deve conter caracteres especiais (!@#$%^&*.\£()}{~?><>,|=_+¬-): </label>
																	<div class="icheck-primary d-inline">
																		<style>
																			input[type=checkbox] {
																				transform: scale(1.8);
																			}
																		</style>
																		<input style="margin-left:5px;" name='caracteresEspeciais' id='caracteresEspeciais' type="checkbox">


																	</div>
																</div>
															</div>




														</div>
														<input type="button" onclick="salvaPoliticaSenha()" value="SALVAR" class="btn btn-xs btn-primary">

													</form>
												</div>
												<!-- /.card-body -->
											</div>
											<!-- /.card -->
										</div>
									</div>



									<div class="row">
										<div class="col-md-12">

											<div class="card card-primary">
												<div class="card-header">
													<h3 class="card-title">NOVOS USUÁRIOS</h3>

													<div class="card-tools">
														<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
														</button>
													</div>
													<!-- /.card-tools -->
												</div>
												<!-- /.card-header -->
												<div class="card-body">

													<form id="senhaNovosUsuarios-form" method="post">
														<input type="hidden" id="<?php echo csrf_token() ?>senhaNovosUsuarios-form" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

														<input id="codOrganizacao" name="codOrganizacao" type="hidden">

														<div class="row">
															<div class="col-md-6">
																<div class="form-group">
																	<label for="senhaPadrao">Novos usuários devem utilizar senha padrão definida aqui? </label>
																	<div class="icheck-primary d-inline">
																		<style>
																			input[type=radio] {
																				transform: scale(1.8);
																			}
																		</style>
																		<input style="margin-left:5px;" name='senhaNovousuario' id='ativarSenhaPadrao' type="radio" value="1">


																	</div>
																</div>
															</div>
														</div>
														<div id="mostrarAtivarSenhaPadrao" class="row">


															<div class="col-md-12">
																<div class="form-group">
																	<label for="senhaPadrao"> Senha Padrão: </span> </label>
																	<input style="width:250px" name='senhaPadrao' id='senhaPadrao' type="text">
																</div>
															</div>

														</div>


														<div class="row">
															<div class="col-md-12">
																<div class="form-group">
																	<label for="checkboxNumeros">Usuário define a senha após E-mail de confirmação</label>
																	<div class="icheck-primary d-inline">
																		<style>
																			input[type=radio] {
																				transform: scale(1.8);
																			}
																		</style>
																		<input style="margin-left:5px;" name='senhaNovousuario' id='confirmacaoCadastroPorEmail' type="radio" value="2">


																	</div>
																</div>
															</div>
														</div>

														<div class="row">
															<div class="col-md-4">
																<div class="form-group">
																	<label for="checkboxSenhaAleatoria">Senha Aleatória: </label>
																	<div class="icheck-primary d-inline">
																		<style>
																			input[type=radio] {
																				transform: scale(1.8);
																			}
																		</style>
																		<input style="margin-left:5px;" name='senhaNovousuario' id='senhaAleatória' type="radio" value="3">


																	</div>
																</div>
															</div>
														</div>

														<input type="button" onclick="salvaSenhaNovosUsuarios()" value="SALVAR" class="btn btn-xs btn-primary">

													</form>
												</div>
												<!-- /.card-body -->
											</div>
											<!-- /.card -->
										</div>
									</div>



								</div>


								<div class="tab-pane fade" id="cabecalho-rodape" role="tabpanel" aria-labelledby="cabecalho-rodape-tab">


									<div class="card card-primary card-tabs">
										<div class="card-header p-0 pt-1">
											<ul class="nav nav-tabs" id="itensCabecalho-tab" role="tablist">
												<li class="nav-item">
													<a class="nav-link active" id="itensCabecalho-oficios-tab" data-toggle="pill" href="#itensCabecalho-oficios" role="tab" aria-controls="itensCabecalho-oficios" aria-selected="true">Ofícios</a>
												</li>
												<li class="nav-item">
													<a class="nav-link" id="itensCabecalho-prescricoes-tab" data-toggle="pill" href="#itensCabecalho-prescricoes" role="tab" aria-controls="itensCabecalho-prescricoes" aria-selected="false">Prescrições</a>
												</li>
											</ul>
										</div>
										<div class="card-body">
											<div class="tab-content" id="itensCabecalho-tabContent">
												<div class="tab-pane fade show active" id="itensCabecalho-oficios" role="tabpanel" aria-labelledby="itensCabecalho-oficios-tab">


													<div class="row">
														<div class="col-md-12">

															<div class="card card-primary">
																<div class="card-header">
																	<h3 class="card-title">CABEÇALHO OFÍCIOS</h3>

																	<div class="card-tools">
																		<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
																		</button>
																	</div>
																	<!-- /.card-tools -->
																</div>
																<!-- /.card-header -->
																<div class="card-body">


																	<form id="cabecalho-form" method="post">
																		<input type="hidden" id="<?php echo csrf_token() ?>cabecalho-form" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

																		<div class="row">
																			<input type="hidden" id="codOrganizacaoSalvaCabacalho" name="codOrganizacao" class="form-control" placeholder="Código" maxlength="11" required>
																			<div class="col-md-12">
																				<div class="form-group">
																					<textarea id="cabecalho" name="cabecalho" class="form-control" placeholder="Cabeçalho"></textarea>
																				</div>
																			</div>

																		</div>
																		<input type="button" id="cabecalho-form-btn" value="SALVAR" onclick="salvaCabecalho()" class="btn btn-xs btn-primary">

																	</form>
																</div>
																<!-- /.card-body -->
															</div>
															<!-- /.card -->
														</div>
													</div>

													<div class="row">
														<div class="col-md-12">

															<div class="card card-primary">
																<div class="card-header">
																	<h3 class="card-title">RODAPE OFÍCIOS</h3>

																	<div class="card-tools">
																		<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
																		</button>
																	</div>
																	<!-- /.card-tools -->
																</div>
																<!-- /.card-header -->
																<div class="card-body">

																	<form id="rodape-form" method="post">
																		<div class="row">
																			<input type="hidden" id="<?php echo csrf_token() ?>rodape-form" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

																			<input type="hidden" id="codOrganizacaoSalvaCabacalho" name="codOrganizacao" class="form-control" placeholder="Código" maxlength="11" required>

																			<div class="col-md-12">
																				<div class="form-group">
																					<textarea id="rodape" name="rodape" class="form-control" placeholder="Rodape"></textarea>
																				</div>
																			</div>

																		</div>

																		<input type="button" id="rodape-form-btn" value="SALVAR" onclick="salvaRodape()" class="btn btn-xs btn-primary">

																	</form>
																</div>
																<!-- /.card-body -->
															</div>
															<!-- /.card -->
														</div>
													</div>
												</div>
												<div class="tab-pane fade" id="itensCabecalho-prescricoes" role="tabpanel" aria-labelledby="itensCabecalho-prescricoes-tab">



													<div class="row">
														<div class="col-md-12">

															<div class="card card-primary">
																<div class="card-header">
																	<h3 class="card-title">CABEÇALHO PRESCRIÇÃO</h3>

																	<div class="card-tools">
																		<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
																		</button>
																	</div>
																	<!-- /.card-tools -->
																</div>
																<!-- /.card-header -->
																<div class="card-body">


																	<form id="cabecalhoPrescricao-form" method="post">
																		<input type="hidden" id="<?php echo csrf_token() ?>cabecalhoPrescricao-form" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

																		<div class="row">
																			<input type="hidden" id="codOrganizacaoSalvaCabacalhoPrescricao" name="codOrganizacao" class="form-control" placeholder="Código" maxlength="11" required>
																			<div class="col-md-12">
																				<div class="form-group">
																					<textarea id="cabecalhoPrescricao" name="cabecalho" class="form-control" placeholder="Cabeçalho"></textarea>
																				</div>
																			</div>

																		</div>
																		<input type="button" id="cabecalhoPrescricao-form-btn" value="SALVAR" onclick="salvaCabecalhoPrescricao()" class="btn btn-xs btn-primary">

																	</form>
																</div>
																<!-- /.card-body -->
															</div>
															<!-- /.card -->
														</div>
													</div>

													<div class="row">
														<div class="col-md-12">

															<div class="card card-primary">
																<div class="card-header">
																	<h3 class="card-title">RODAPÉ PRESCRIÇÃO</h3>

																	<div class="card-tools">
																		<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
																		</button>
																	</div>
																	<!-- /.card-tools -->
																</div>
																<!-- /.card-header -->
																<div class="card-body">

																	<form id="rodapePrescricao-form" method="post">
																		<div class="row">
																			<input type="hidden" id="<?php echo csrf_token() ?>rodapePrescricao-form" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

																			<input type="hidden" id="codOrganizacaoSalvaCabacalhoPrescricao" name="codOrganizacao" class="form-control" placeholder="Código" maxlength="11" required>

																			<div class="col-md-12">
																				<div class="form-group">
																					<textarea id="rodapePrescricao" name="rodape" class="form-control" placeholder="Rodape"></textarea>
																				</div>
																			</div>

																		</div>

																		<input type="button" id="rodapePrescricao-form-btn" value="SALVAR" onclick="salvaRodapePrescricao()" class="btn btn-xs btn-primary">

																	</form>
																</div>
																<!-- /.card-body -->
															</div>
															<!-- /.card -->
														</div>
													</div>

												</div>
											</div>
										</div>
										<!-- /.card -->
									</div>








								</div>


								<div class="tab-pane fade" id="redesSociais" role="tabpanel" aria-labelledby="redesSociais-tab">
									<div class="card-body">


										<form id="redesSociais-form" method="post">
											<input type="hidden" id="<?php echo csrf_token() ?>redesSociais-form" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

											<input type="hidden" id="codOrganizacaoRedesSociais" name="codOrganizacao" class="form-control" placeholder="Código" maxlength="11" required>

											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label for="linkedin_url"> Linkedin:</label>
														<input id="linkedin_url" name="linkedin_url" class="form-control" placeholder="https://URL do linkedin">
													</div>
												</div>

												<div class="col-md-6">
													<div class="form-group">
														<label for="facebook_url"> Facebook:</label>
														<input id="facebook_url" name="facebook_url" class="form-control" placeholder="https://URL do Facebook">
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label for="instagram_url"> Instagram:</label>
														<input id="instagram_url" name="instagram_url" class="form-control" placeholder="https://URL do Instagram">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label for="twitter_url"> Twitter:</label>
														<input id="twitter_url" name="twitter_url" class="form-control" placeholder="https://URL do Twitter">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label for="youtube_url"> Youtube:</label>
														<input id="youtube_url" name="youtube_url" class="form-control" placeholder="https://URL do Twitter">
													</div>
												</div>
											</div>


											<input type="button" id="redesSociais-form-btn" value="SALVAR" onclick="salvaRedesSociais()" class="btn btn-xs btn-primary">

										</form>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->



<div id="slideshowAddModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar slideshow</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="slideshowAddForm" method="post" accept-charset="utf-8" enctype="multipart/form-data">
					<input type="hidden" id="<?php echo csrf_token() ?>slideshowAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codSlideShow" name="codSlideShow" class="form-control" placeholder="CodSlideShow" maxlength="11" required>
					</div>


					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="descricao"> Descrição: <span class="text-danger">*</span> </label>
								<input type="text" id="descricao" name="descricao" class="form-control" placeholder="Descrição" maxlength="100" required>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="url"> Url: </label>
								<input type="text" id="url" name="url" class="form-control" placeholder="Url" maxlength="100">
							</div>
						</div>

					</div>
					<div class="row">

						<div class="col-md-4">
							<label for="fileSlideShow">Selecione o arquivo desejado</label>
							<input type="file" id="fileSlideShow" name="fileSlideShow" class="form-control" style="height:45px;">
						</div>

					</div><span style="color:red; margin-bottom:10px">Resolução (440x530)</span>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataExpiracao"> Data Expiração: <span class="text-danger">*</span> </label>
								<input type="date" id="dataExpiracao" name="dataExpiracao" class="form-control" placeholder="Data Expiração" maxlength="11">
							</div>
						</div>

					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codStatusSlideShow"> Status: </label>
								<div class="icheck-primary d-inline">
									<style>
										input[type=checkbox] {
											transform: scale(1.8);
										}
									</style>
									<input style="margin-left:5px;" name='codStatus' type="checkbox" id="codStatusSlideShow">

								</div>
							</div>

						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="slideshowAddForm-btn">Adicionar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Add modal content -->
<div id="slideshowEditModal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar slideshow</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="slideshowEditForm" class="pl-3 pr-3">
					<input type="hidden" id="<?php echo csrf_token() ?>slideshowEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

					<div class="row">
						<input type="hidden" id="codSlideShow" name="codSlideShow" class="form-control" placeholder="CodSlideShow" maxlength="11" required>
						<input type="hidden" id="imagem" name="imagem" class="form-control">
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="descricao"> Descrição: <span class="text-danger">*</span> </label>
								<input type="text" id="descricao" name="descricao" class="form-control" placeholder="Descrição" maxlength="100" required>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="url"> Url: </label>
								<input type="text" id="url" name="url" class="form-control" placeholder="Url" maxlength="100">
							</div>
						</div>

					</div>
					<div class="row">

						<div class="col-md-4">
							<label for="fileSlideShowEdit">Selecione o arquivo desejado</label>
							<input type="file" id="fileSlideShowEdit" name="fileSlideShow" class="form-control" style="height:45px;">
						</div>

					</div><span style="color:red; margin-bottom:10px">Resolução (440x530)</span>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="dataExpiracao"> Data Expiração: <span class="text-danger">*</span> </label>
								<input type="date" id="dataExpiracao" name="dataExpiracao" class="form-control" placeholder="Data Expiração" maxlength="11">
							</div>
						</div>

						<div class="col-md-1">
							<div class="form-group">
								<label for="ordemSlideShow"> Ordem: <span class="text-danger">*</span> </label>
								<input type="number" id="ordemSlideShow" name="ordem" class="form-control" maxlength="11">
							</div>
						</div>

					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="codStatusSlideShowEdit"> Status: </label>
								<div class="icheck-primary d-inline">
									<style>
										input[type=checkbox] {
											transform: scale(1.8);
										}
									</style>
									<input style="margin-left:5px;" name='codStatus' type="checkbox" id="codStatusSlideShowEdit">

								</div>
							</div>

						</div>
					</div>

					<div class="form-group text-center">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="slideshowEditForm-btn">Salvar</button>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</form>

			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>


</div><!-- /.modal -->

<?php
echo view('tema/rodape');
?>

<script src="<?php echo base_url() ?>/assets/adminlte/plugins/summernote/summernote-bs4.min.js"></script>


<script>
	var codOrganizacaoTmp;


	$(function() {
		$('#data_table').DataTable({
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
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

		var table = $('#data_table').DataTable();

		$('#data_table tbody').on('dblclick', 'tr', function() {
			edit(<?php echo session()->codOrganizacao ?>);
		});




	});


	function envia_logo() {

		var formData = new FormData();
		formData.append('file', $('#userFileLogo')[0].files[0]);		
		formData.append('csrf_sandra', $("#csrf_sandraPrincipal").val());
		$.ajax({
			url: '<?php echo base_url($controller . '/envia_logo') ?>',
			type: 'post',
			data: formData,
			
			processData: false, // tell jQuery not to process the data
			contentType: false, // tell jQuery not to set contentType
			dataType: 'json',
			success: function(response) {

				if (response.success === true) {

					//MUDA URL FOTO
					document.getElementById("logoOrganizacao").src = "<?php echo base_url() . '/imagens/organizacoes/' ?>" + response.logo + "?" + new Date().getTime();
					$('#modal-logo').modal('hide');
					Swal.fire({
						position: 'bottom-end',
						icon: 'success',
						title: response.messages,
						showConfirmButton: false,
						timer: 3000
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
		});

	}

	function envia_fundo() {

		var formData = new FormData();
		formData.append('file', $('#userFileFundo')[0].files[0]);
		formData.append('csrf_sandra', $("#csrf_sandraPrincipal").val());
		$.ajax({
			url: '<?php echo base_url($controller . '/envia_fundo') ?>',
			type: 'post',
			data: formData,
			processData: false, // tell jQuery not to process the data
			contentType: false, // tell jQuery not to set contentType
			dataType: 'json',
			success: function(response) {

				if (response.success === true) {

					//MUDA URL FOTO
					document.getElementById("fundoOrganizacao").src = "<?php echo base_url() . '/imagens/fundo/' ?>" + response.fundo + "?" + new Date().getTime();
					$('#modal-fundo').modal('hide');
					Swal.fire({
						position: 'bottom-end',
						icon: 'success',
						title: response.messages,
						showConfirmButton: false,
						timer: 3000
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
		});

	}

	function envia_foto() {

		var formData = new FormData();
		formData.append('file', $('#userFileFoto')[0].files[0]);		
		formData.append('csrf_sandra', $("#csrf_sandraPrincipal").val());
		$.ajax({
			url: '<?php echo base_url($controller . '/envia_foto') ?>',
			type: 'post',
			data: formData,
			processData: false, // tell jQuery not to process the data
			contentType: false, // tell jQuery not to set contentType
			dataType: 'json',
			success: function(response) {

				if (response.success === true) {

					//MUDA URL FOTO
					document.getElementById("fotoOrganizacao").src = "<?php echo base_url() . '/imagens/organizacoes/' ?>" + response.foto + "?" + new Date().getTime();
					$('#modal-foto').modal('hide');
					Swal.fire({
						position: 'bottom-end',
						icon: 'success',
						title: response.messages,
						showConfirmButton: false,
						timer: 3000
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
		});

	}

	function salvaCabecalhoPrescricao() {
		var cabecalhoform = $('#cabecalhoPrescricao-form');


		$.ajax({
			url: '<?php echo base_url($controller . '/salvarCabacalhoPrescricao') ?>',
			type: 'post',
			data: cabecalhoform.serialize(), // /converting the form data into array and sending it to server
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
						$('#data_table').DataTable().ajax.reload(null, false).draw(false);
						//$('#add-modal').modal('hide');
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
			}
		});

		return false;
	}

	function salvaCabecalho() {
		var cabecalhoform = $('#cabecalho-form');


		$.ajax({
			url: '<?php echo base_url($controller . '/salvarCabacalho') ?>',
			type: 'post',
			data: cabecalhoform.serialize(), // /converting the form data into array and sending it to server
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
						$('#data_table').DataTable().ajax.reload(null, false).draw(false);
						//$('#add-modal').modal('hide');
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
			}
		});

		return false;
	}


	function salvaRedesSociais() {
		var redesSociaisform = $('#redesSociais-form');


		$.ajax({
			url: '<?php echo base_url($controller . '/salvarRedesSociais') ?>',
			type: 'post',
			data: redesSociaisform.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',

			beforeSend: function() {
				//$('#redesSociais-form-btn').html('<i class="fa fa-spinner fa-spin"></i>');
			},
			success: function(response) {

				if (response.success === true) {

					Swal.fire({
						position: 'bottom-end',
						icon: 'success',
						title: response.messages,
						showConfirmButton: false,
						timer: 1500
					})

				}
				if (response.success === false) {

					Swal.fire({
						position: 'bottom-end',
						icon: 'error',
						title: response.messages,
						showConfirmButton: false,
						timer: 1500
					})

				}
				$('#redesSociais-form-btn').html('Adicionar');
			}
		});

		return false;
	}


	function salvaSenhaAdmin() {
		var segurancaForm = $('#seguranca-form');

		$.ajax({
			url: '<?php echo base_url('/organizacoes/salvarSeguranca/') ?>',
			type: 'post',
			data: segurancaForm.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',

			beforeSend: function() {
				//$('#add-form-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
						$('#data_table').DataTable().ajax.reload(null, false).draw(false);
						//$('#add-modal').modal('hide');
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
				$('#add-form-btn').html('Adicionar');
			}
		});

		return false;
	}





	function salvaSenhaNovosUsuarios() {
		var senhaNovosUsuariosForm = $('#senhaNovosUsuarios-form');

		$.ajax({
			url: '<?php echo base_url('/organizacoes/salvaSenhaNovosUsuarios/') ?>',
			type: 'post',
			data: senhaNovosUsuariosForm.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',

			beforeSend: function() {
				//$('#add-form-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
						$('#data_table').DataTable().ajax.reload(null, false).draw(false);
						//$('#add-modal').modal('hide');
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
				$('#add-form-btn').html('Adicionar');
			}
		});

		return false;
	}


	function salvaDiversos() {
		var diversosForm = $('#diversos-form');

		$.ajax({
			url: '<?php echo base_url('/organizacoes/salvarDiversos/') ?>',
			type: 'post',
			data: diversosForm.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',

			beforeSend: function() {
				//$('#add-form-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
						$('#data_table').DataTable().ajax.reload(null, false).draw(false);
						//$('#add-modal').modal('hide');
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
				$('#diversos-form-btn').html('Adicionar');
			}
		});

		return false;
	}

	function salvaPoliticaSenha() {
		var segurancaSenhaForm = $('#politicaSenha-form');

		$.ajax({
			url: '<?php echo base_url('/organizacoes/SalvaPoliticaSenhas/') ?>',
			type: 'post',
			data: segurancaSenhaForm.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',

			beforeSend: function() {
				//$('#add-form-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
						$('#data_table').DataTable().ajax.reload(null, false).draw(false);
						//$('#add-modal').modal('hide');
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
				$('#add-form-btn').html('Adicionar');
			}
		});

		return false;
	}

	function salvaRodape() {
		var cabecalhoform = $('#rodape-form');


		$.ajax({
			url: '<?php echo base_url($controller . '/salvarRodape') ?>',
			type: 'post',
			data: cabecalhoform.serialize(), // /converting the form data into array and sending it to server
			dataType: 'json',

			beforeSend: function() {
				//$('#rodape-form-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
						$('#data_table').DataTable().ajax.reload(null, false).draw(false);
						//$('#add-modal').modal('hide');
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
				$('#rodape-form-btn').html('Salvar');
			}
		});

		return false;
	}

	function salvaRodapePrescricao() {
		var cabecalhoform = $('#rodapePrescricao-form');


		$.ajax({
			url: '<?php echo base_url($controller . '/salvarRodapePrescricao') ?>',
			type: 'post',
			data: cabecalhoform.serialize(), // /converting the form data into array and sending it to server
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
						$('#data_table').DataTable().ajax.reload(null, false).draw(false);
						//$('#add-modal').modal('hide');
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
			}
		});

		return false;
	}

	function add() {
		// reset the form 
		$("#add-form")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#add-modal').modal('show');
		// submit the add from 



		$.ajax({
			url: '<?php echo base_url('especialidades/listaDropDownEstadosFederacao') ?>',
			type: 'post',
			dataType: 'json',
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			success: function(estadoFederacaoAdd) {

				$("#codEstadoFederacaoAdd").select2({
					data: estadoFederacaoAdd,
				})

				$('#codEstadoFederacaoAdd').val(null); // Select the option with a value of '1'
				$('#codEstadoFederacaoAdd').trigger('change');
				$(document).on('select2:open', () => {
					document.querySelector('.select2-search__field').focus();
				});

			}
		})



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



				var form = $('#add-form');
				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url($controller . '/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#add-form-btn').html('<i class="fa fa-spinner fa-spin"></i>');
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
								$('#data_table').DataTable().ajax.reload(null, false).draw(false);
								$('#add-modal').modal('hide');
								edit(response.codOrganizacao);
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
						$('#add-form-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#add-form').validate();
	}


	function salvaPaletaCores() {


		var form = $('#paletaCores-form');

		$.ajax({
			url: '<?php echo base_url('portalOrganizacao/salvaPaletaCores') ?>',
			type: 'post',
			data: form.serialize(),
			dataType: 'json',
			success: function(response) {

				if (response.success === true) {

					Swal.fire({

						icon: 'success',
						title: response.messages,
						showConfirmButton: false,
						timer: 3000
					})

				} else {
					Swal.fire({

						icon: 'error',
						title: response.messages,
						showConfirmButton: false,
						timer: 3000
					})
				}

			}
		})


	}

	function salvaHero() {


		var form = $('#hero-form');

		$.ajax({
			url: '<?php echo base_url('portalOrganizacao/salvaHero') ?>',
			type: 'post',
			data: form.serialize(),
			dataType: 'json',
			success: function(response) {

				if (response.success === true) {

					Swal.fire({

						icon: 'success',
						title: response.messages,
						showConfirmButton: false,
						timer: 3000
					})

				} else {
					Swal.fire({

						icon: 'error',
						title: response.messages,
						showConfirmButton: false,
						timer: 3000
					})
				}

			}
		})


	}

	function edit(codOrganizacao) {
		codOrganizacaoTmp = codOrganizacao;


		$.ajax({
			url: '<?php echo base_url($controller . '/getOne') ?>',
			type: 'post',
			data: {
				codOrganizacao: codOrganizacao,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#cabecalho").summernote('destroy');
				$("#rodape").summernote('destroy');
				$("#edit-form")[0].reset();
				$("#diversos-form")[0].reset();
				$("#seguranca-form")[0].reset();
				$("#senhaNovosUsuarios-form")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#edit-modal').modal('show');

				$("#edit-form #codOrganizacao").val(response.codOrganizacao);
				$("#seguranca-form #codOrganizacao").val(response.codOrganizacao);
				$("#edit-form #descricao").val(response.descricao);
				$("#edit-form #endereço").val(response.endereço);
				$("#edit-form #telefone").val(response.telefone);
				$("#edit-form #cnpj").val(response.cnpj);
				$("#edit-form #site").val(response.site);
				$("#edit-form #cep").val(response.cep);
				$("#edit-form #cidade").val(response.cidade);
				$("#edit-form #siglaOrganizacao").val(response.siglaOrganizacao);
				$("#edit-form #siglaOrganizacao").val(response.siglaOrganizacao);


				$.ajax({
					url: '<?php echo base_url('portalOrganizacao/getOne') ?>',
					type: 'post',
					data: {
						codOrganizacao: codOrganizacao,
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					dataType: 'json',
					success: function(response) {
						$("#paletaCores-form #codPortal").val(response.codPortal);
						$("#paletaCores-form #corFundoPrincipal").val(response.corFundoPrincipal);
						$("#paletaCores-form #corTextoPrincipal").val(response.corTextoPrincipal);
						$("#paletaCores-form #corTextoMenus").val(response.corTextoMenus);
						$("#paletaCores-form #corMenus").val(response.corMenus);
						$("#paletaCores-form #corBackgroundMenus").val(response.corBackgroundMenus);
						
						$("#paletaCores-form #corLinhaTabela").val(response.corLinhaTabela);
						$("#paletaCores-form #corTextoTabela").val(response.corTextoTabela);

						document.getElementById("heroImagem").innerHTML = '<img style="width:200px" src="<?php echo base_url() . "/imagens/" ?>' + response.heroImagem + '">';

						if (response.ativoHero == '1') {
							document.getElementById("ativoHero").checked = true;
						}


					}
				})





				//CABECALHO E RODAPÉ OFICIOS
				$("#rodape-form #rodape").val(response.rodape);
				$("#cabecalho-form #cabecalho").val(response.cabecalho);
				$("#cabecalho-form #codOrganizacaoSalvaCabacalho").val(response.codOrganizacao);
				$("#rodape-form #codOrganizacaoSalvaCabacalho").val(response.codOrganizacao);


				//CABECALHO E RODAPÉ PRESCRIÇOES
				$("#rodapePrescricao-form #rodapePrescricao").val(response.rodapePrescricao);
				$("#cabecalhoPrescricao-form #cabecalhoPrescricao").val(response.cabecalhoPrescricao);
				$("#cabecalhoPrescricao-form #codOrganizacaoSalvaCabacalhoPrescricao").val(response.codOrganizacao);
				$("#rodapePrescricao-form #codOrganizacaoSalvaCabacalhoPrescricao").val(response.codOrganizacao);

				//REDES SOCIAIS
				$("#redesSociais-form #codOrganizacaoRedesSociais").val(response.codOrganizacao);
				$("#redesSociais-form #linkedin_url").val(response.linkedin_url);
				$("#redesSociais-form #facebook_url").val(response.facebook_url);
				$("#redesSociais-form #instagram_url").val(response.instagram_url);
				$("#redesSociais-form #twitter_url").val(response.twitter_url);
				$("#redesSociais-form #youtube_url").val(response.youtube_url);




				$.ajax({
					url: '<?php echo base_url('especialidades/listaDropDownEstadosFederacao') ?>',
					type: 'post',
					dataType: 'json',
					data: {
						csrf_sandra: $("#csrf_sandraPrincipal").val(),
					},
					success: function(estadoFederacaoEdit) {

						$("#codEstadoFederacaoEdit").select2({
							data: estadoFederacaoEdit,
						})

						$('#codEstadoFederacaoEdit').val(response.codEstadoFederacao); // Select the option with a value of '1'
						$('#codEstadoFederacaoEdit').trigger('change');
						$(document).on('select2:open', () => {
							document.querySelector('.select2-search__field').focus();
						});

					}
				})




				$(function() {

					//ADD text editor
					$('#cabecalho').summernote({
						height: 150,
						maximumImageFileSize: 1024 * 1024, // 1Mb
						fontSizes: ['8', '9', '10', '11', '12', '14', '18', '20', '36', '72'],
						lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
						toolbar: [
							['style', ['style']],
							['fontname', ['fontname']],
							['font', ['color', 'strikethrough', 'superscript', 'subscript', 'bold', 'underline', 'clear']],
							['fontsize', ['fontsize']],
							['height', ['height']],
							['para', ['ul', 'ol', 'paragraph']],
							['table', ['table']],
							['insert', ['link', 'picture', 'video', 'hr']],
							['view', ['fullscreen', 'codeview', 'help']],
							['redo'],
							['undo'],
						],
						callbacks: {
							onImageUploadError: function(msg) {
								var Toast = Swal.mixin({
									toast: true,
									position: 'top-end',
									showConfirmButton: false,
									timer: 5000
								});
								Toast.fire({
									icon: 'error',
									title: 'Tamanho máximo de imagens é 1Mb'
								})
							}
						}
					})


					//ADD text editor
					$('#cabecalhoPrescricao').summernote({
						height: 150,
						maximumImageFileSize: 1024 * 1024, // 1Mb
						fontSizes: ['8', '9', '10', '11', '12', '14', '18', '20', '36', '72'],
						lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
						toolbar: [
							['style', ['style']],
							['fontname', ['fontname']],
							['font', ['color', 'strikethrough', 'superscript', 'subscript', 'bold', 'underline', 'clear']],
							['fontsize', ['fontsize']],
							['height', ['height']],
							['para', ['ul', 'ol', 'paragraph']],
							['table', ['table']],
							['insert', ['link', 'picture', 'video', 'hr']],
							['view', ['fullscreen', 'codeview', 'help']],
							['redo'],
							['undo'],
						],
						callbacks: {
							onImageUploadError: function(msg) {
								var Toast = Swal.mixin({
									toast: true,
									position: 'top-end',
									showConfirmButton: false,
									timer: 5000
								});
								Toast.fire({
									icon: 'error',
									title: 'Tamanho máximo de imagens é 1Mb'
								})
							}
						}
					})




					//ADD text editor
					$('#rodape').summernote({
						height: 150,
						maximumImageFileSize: 1024 * 1024, // 1Mb
						fontSizes: ['8', '9', '10', '11', '12', '14', '18', '20', '36', '72'],
						lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
						toolbar: [
							['style', ['style']],
							['fontname', ['fontname']],
							['font', ['color', 'strikethrough', 'superscript', 'subscript', 'bold', 'underline', 'clear']],
							['fontsize', ['fontsize']],
							['height', ['height']],
							['para', ['ul', 'ol', 'paragraph']],
							['table', ['table']],
							['insert', ['link', 'picture', 'video', 'hr']],
							['view', ['fullscreen', 'codeview', 'help']],
							['redo'],
							['undo'],
						],
						callbacks: {
							onImageUploadError: function(msg) {
								var Toast = Swal.mixin({
									toast: true,
									position: 'top-end',
									showConfirmButton: false,
									timer: 5000
								});
								Toast.fire({
									icon: 'error',
									title: 'Tamanho máximo de imagens é 1Mb'
								})
							}
						}
					})

					//ADD text editor
					$('#rodapePrescricao').summernote({
						height: 150,
						maximumImageFileSize: 1024 * 1024, // 1Mb
						fontSizes: ['8', '9', '10', '11', '12', '14', '18', '20', '36', '72'],
						lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
						toolbar: [
							['style', ['style']],
							['fontname', ['fontname']],
							['font', ['color', 'strikethrough', 'superscript', 'subscript', 'bold', 'underline', 'clear']],
							['fontsize', ['fontsize']],
							['height', ['height']],
							['para', ['ul', 'ol', 'paragraph']],
							['table', ['table']],
							['insert', ['link', 'picture', 'video', 'hr']],
							['view', ['fullscreen', 'codeview', 'help']],
							['redo'],
							['undo'],
						],
						callbacks: {
							onImageUploadError: function(msg) {
								var Toast = Swal.mixin({
									toast: true,
									position: 'top-end',
									showConfirmButton: false,
									timer: 5000
								});
								Toast.fire({
									icon: 'error',
									title: 'Tamanho máximo de imagens é 1Mb'
								})
							}
						}
					})


				})





				$('.modal-title').text(response.descricao);

				//ABA DIVERSOS

				if (response.permiteAutocadastro == 1) {
					document.getElementById("permiteAutocadastro").checked = true;
				}


				if (response.politicaSenha == 1) {
					document.getElementById("politicaSenha").checked = true;
				}

				if (response.senhaNaoSimples == 1) {
					document.getElementById("NaoSimples").checked = true;
				}

				if (response.numeros == 1) {
					document.getElementById("idnumeros").checked = true;
				}

				if (response.letras == 1) {
					document.getElementById("letras").checked = true;
				}

				if (response.maiusculo == 1) {
					document.getElementById("maiusculo").checked = true;
				}

				if (response.caracteresEspeciais == 1) {
					document.getElementById("caracteresEspeciais").checked = true;
				}
				document.getElementById("minimoChar").value = response.minimoCaracteres;
				document.getElementById("diferenteUltimasSenhas").value = response.diferenteUltimasSenhas;

				$("#politicaSenha-form #codOrganizacao").val(response.codOrganizacao);

				$("#senhaNovosUsuarios-form #codOrganizacao").val(response.codOrganizacao);


				$("#senhaNovosUsuarios-form #senhaPadrao").val(response.senhaPadrao);

				if (response.ativarSenhaPadrao == 0) {
					document.getElementById("mostrarAtivarSenhaPadrao").style.display = "none";
					document.getElementById("senhaPadrao").value = "";
				} else {
					document.getElementById("mostrarAtivarSenhaPadrao").style.display = "block";

				}

				$('#confirmacaoCadastroPorEmail').change(function() {
					if ($(this).is(':checked')) {
						document.getElementById("mostrarAtivarSenhaPadrao").style.display = "none";
						document.getElementById("senhaPadrao").value = "";

					}
				});

				$('#senhaAleatória').change(function() {
					if ($(this).is(':checked')) {
						document.getElementById("mostrarAtivarSenhaPadrao").style.display = "none";
						document.getElementById("senhaPadrao").value = "";

					}
				});

				$('#ativarSenhaPadrao').change(function() {
					if ($(this).is(':checked')) {
						document.getElementById("mostrarAtivarSenhaPadrao").style.display = "block";

					}
				});

				$("#senhaNovosUsuarios-form #senhaPadrao").val(response.senhaPadrao);

				if (response.ativarSenhaPadrao == 1) {
					document.getElementById("ativarSenhaPadrao").checked = true;
				}

				if (response.confirmacaoCadastroPorEmail == 1) {
					document.getElementById("confirmacaoCadastroPorEmail").checked = true;
				}

				if (response.senhaAleatória == 1) {
					document.getElementById("senhaAleatória").checked = true;
				}

				$("#diversos-form #codTimezone").val(response.codTimezone).select2();
				$("#diversos-form #codOrganizacao").val(response.codOrganizacao);
				$("#diversos-form #codPerfil").val(response.codPerfilPadrao).select2();
				$("#diversos-form #nomeExibicaoSistema").val(response.nomeExibicaoSistema);
				$("#diversos-form #chaveSalgada").val(response.chaveSalgada);
				$("#diversos-form #tempoInatividade").val(response.tempoInatividade);
				$("#diversos-form #forcarExpiracao").val(response.forcarExpiracao);
				$("#diversos-form #formularioRegistro").val(response.formularioRegistro);

				if (response.matriz == '1') {
					document.getElementById("checkboxmatrizEdit").checked = true;
				}

				//ABA SEGURANÇA

				$("#seguranca-form #loginAdmin").val(response.loginAdmin);
				$("#seguranca-form #senhaAdmin").val(response.senhaAdmin);


				//MUDA URL LOGO
				document.getElementById("logoOrganizacao").src = "<?php echo base_url() . '/imagens/organizacoes/' ?>" + response.logo;

				//MUDA URL FUNDO
				document.getElementById("fundoOrganizacao").src = "<?php echo base_url() . '/imagens/fundo/' ?>" + response.fundo;

				//MUDA URL FOTO
				document.getElementById("fotoOrganizacao").src = "<?php echo base_url() . '/imagens/organizacoes/' ?>" + response.foto;




				//ABA ATRIBUTOS


				$('#data_tableatributosSistemaOrganizacao').DataTable({

					"bDestroy": true,
					"paging": true,
					"lengthChange": false,
					"searching": true,
					"ordering": true,
					"info": true,
					"autoWidth": false,
					"responsive": false,
					"scrollX": true,
					"ajax": {
						"url": '<?php echo base_url('atributosSistemaOrganizacao/getAll') ?>',
						"type": "POST",
						"dataType": "json",
						async: "true",
						data: {
							codOrganizacao: codOrganizacaoTmp,
							csrf_sandra: $("#csrf_sandraPrincipal").val(),
						},
					}
				});



				//SLIDESHOW

				$(function() {
					$('#data_tableslideshow').DataTable({
						"paging": true,
						"bDestroy": true,
						"deferRender": true,
						"lengthChange": false,
						"searching": true,
						"ordering": true,
						"info": true,
						"autoWidth": false,
						"responsive": true,
						"ajax": {
							"url": '<?php echo base_url('slideshow/getAll') ?>',
							"type": "POST",
							"dataType": "json",
							async: "true",
							data: {
								csrf_sandra: $("#csrf_sandraPrincipal").val(),
							},
						}
					});
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


						var form = $('#edit-form');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url($controller . '/edit') ?>',
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
										$('#data_table').DataTable().ajax.reload(null, false).draw(false);
										//$('#edit-modal').modal('hide');
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


	function addslideshow() {
		// reset the form 
		$("#slideshowAddForm")[0].reset();
		$(".form-control").removeClass('is-invalid').removeClass('is-valid');
		$('#slideshowAddModal').modal('show');
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

				var form = $('#slideshowAddForm');

				// remove the text-danger
				$(".text-danger").remove();

				$.ajax({
					url: '<?php echo base_url('slideshow/add') ?>',
					type: 'post',
					data: form.serialize(), // /converting the form data into array and sending it to server
					dataType: 'json',
					beforeSend: function() {
						//$('#slideshowAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function(response) {

						if (response.success === true) {

							if ($('#fileSlideShow')[0].files[0] !== undefined) {

								var formData = new FormData();
								formData.append('file', $('#fileSlideShow')[0].files[0]);
								formData.append('codSlideShow', response.codSlideShow);
								formData.append('csrf_sandra', $("#csrf_sandraPrincipal").val());
								$.ajax({
									url: '<?php echo base_url('Slideshow/envia_slideShow') ?>',
									type: 'post',
									data: formData,
									processData: false, // tell jQuery not to process the data
									contentType: false, // tell jQuery not to set contentType
									dataType: 'json',
									success: function(response) {


										if (response.success === true) {
											$('#slideshowAddModal').modal('hide');

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
												$('#data_tableslideshow').DataTable().ajax.reload(null, false).draw(false);
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
								})
							} else {
								if (response.success === true) {
									$('#slideshowEditModal').modal('hide');

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
										$('#data_tableslideshow').DataTable().ajax.reload(null, false).draw(false);
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
						$('#slideshowAddForm-btn').html('Adicionar');
					}
				});

				return false;
			}
		});
		$('#slideshowAddForm').validate();
	}

	function editslideshow(codSlideShow) {
		$.ajax({
			url: '<?php echo base_url('slideshow/getOne') ?>',
			type: 'post',
			data: {
				codSlideShow: codSlideShow,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
			dataType: 'json',
			success: function(response) {
				// reset the form 
				$("#slideshowEditForm")[0].reset();
				$(".form-control").removeClass('is-invalid').removeClass('is-valid');
				$('#slideshowEditModal').modal('show');

				$("#slideshowEditForm #codSlideShow").val(response.codSlideShow);
				$("#slideshowEditForm #descricao").val(response.descricao);
				$("#slideshowEditForm #url").val(response.url);
				$("#slideshowEditForm #imagem").val(response.imagem);
				$("#slideshowEditForm #ordemSlideShow").val(response.ordem);
				$("#slideshowEditForm #dataExpiracao").val(response.dataExpiracao);
				if (response.codStatus == '1') {
					document.getElementById("codStatusSlideShowEdit").checked = true;
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
						var form = $('#slideshowEditForm');
						$(".text-danger").remove();
						$.ajax({
							url: '<?php echo base_url('slideshow/edit') ?>',
							type: 'post',
							data: form.serialize(),
							dataType: 'json',
							beforeSend: function() {
								//$('#slideshowEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
							},
							success: function(response) {


								if (response.success === true) {

									if ($('#fileSlideShowEdit')[0].files[0] !== undefined) {

										var formData = new FormData();
										formData.append('file', $('#fileSlideShowEdit')[0].files[0]);
										formData.append('codSlideShow', response.codSlideShow);
										formData.append('imagem', response.imagem);
										formData.append('csrf_sandra', $("#csrf_sandraPrincipal").val());
										$.ajax({
											url: '<?php echo base_url('Slideshow/envia_slideShow') ?>',
											type: 'post',
											data: formData,
											processData: false, // tell jQuery not to process the data
											contentType: false, // tell jQuery not to set contentType
											dataType: 'json',
											success: function(response) {

												if (response.success === true) {
													$('#slideshowEditModal').modal('hide');

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
														$('#data_tableslideshow').DataTable().ajax.reload(null, false).draw(false);
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
										})
									} else {
										if (response.success === true) {
											$('#slideshowEditModal').modal('hide');

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
												$('#data_tableslideshow').DataTable().ajax.reload(null, false).draw(false);
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
								$('#slideshowEditForm-btn').html('Salvar');
							}
						});

						return false;
					}
				});
				$('#slideshowEditForm').validate();

			}
		});
	}

	function removeslideshow(codSlideShow) {
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
					url: '<?php echo base_url('slideshow/remove') ?>',
					type: 'post',
					data: {
						codSlideShow: codSlideShow,
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
								$('#data_tableslideshow').DataTable().ajax.reload(null, false).draw(false);
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


	function remove(codOrganizacao) {

		Swal.fire({
			title: 'Função desativada. Contate o administrador do sistema?',
			icon: 'warning',
			showCancelButton: false,
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ok',
		})

		throw new Error('Função desativada. Contate o administrador do sistema?');


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
						codOrganizacao: codOrganizacao,
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
								$('#data_table').DataTable().ajax.reload(null, false).draw(false);
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
				});
			}
		})
	}
</script>



<script>
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


	function editatributosSistemaOrganizacao(codAtributosSistemaOrganizacao) {
		$.ajax({
			url: '<?php echo base_url('atributosSistemaOrganizacao/getOne') ?>',
			type: 'post',
			data: {
				codAtributosSistemaOrganizacao: codAtributosSistemaOrganizacao,
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
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
						codAtributosSistemaOrganizacao: codAtributosSistemaOrganizacao,
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