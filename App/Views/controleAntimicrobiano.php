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
				  <h3 style="font-size:30px;font-weight: bold;" class="card-title">Controle Antimicrobiano</h3>
			  	</div>
				<div class="col-md-4">
				  <button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addcontroleAntimicrobiano()" title="Adicionar">Adicionar</button>
				</div>
			  </div>			  
            </div>
            <!-- /.card-header -->
            <div class="card-body">
			<div class="col-md-4">
				  <button type="button" class="btn btn-block btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" onclick="addcontroleAntimicrobiano()" title="Adicionar">Adicionar</button>
				</div>
				
              <table id="data_tablecontroleAntimicrobiano" class="table table-striped table-hover table-sm">
                <thead>
                <tr>
					<th>CodControleAntimicrobiano</th>
					<th>CodItem</th>
					<th>CodAtendimento</th>
					<th>CodPaciente</th>
					<th>CodAutor</th>
					<th>DataCriacao</th>
					<th>DataAtualizacao</th>
					<th>DataInicio</th>
					<th>DataEncerramento</th>
					<th>PrimeiraEscolha</th>
					<th>IndicacaoAntibiotico</th>
					<th>TipoInfeccao</th>
					<th>Respiratoria</th>
					<th>Urinaria</th>
					<th>peleTecido</th>
					<th>Cirurgia</th>
					<th>CorrenteSanguinea</th>
					<th>Outros</th>
					<th>resultadoCultura</th>
					<th>FaltaMedicamentoFarmacia</th>
					<th>AlergiaAntimicrobiano</th>
					<th>InsuficienciaRenal</th>
					<th>InsuficienciaHepatica</th>
					<th>OutroEsquemaAlternativo</th>
					<th>JustificativaEsquema</th>
					<th>ResultadoCultura</th>
					<th>SolicitouCultura</th>

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
	<div id="controleAntimicrobianoAddModal" class="modal fade" role="dialog"
		aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Adicionar Controle Antimicrobiano</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
				<div class="modal-body">
					<form id="controleAntimicrobianoAddForm" class="pl-3 pr-3">
						<input type="hidden" id="<?php echo csrf_token() ?>controleAntimicrobianoAddForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
					
                        <div class="row">
 							<input type="hidden" id="codControleAntimicrobiano" name="codControleAntimicrobiano" class="form-control" placeholder="CodControleAntimicrobiano" maxlength="11" required>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="codItem"> CodItem: <span class="text-danger">*</span> </label>
									<input type="number" id="codItem" name="codItem" class="form-control" placeholder="CodItem" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codAtendimento"> CodAtendimento: <span class="text-danger">*</span> </label>
									<input type="number" id="codAtendimento" name="codAtendimento" class="form-control" placeholder="CodAtendimento" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codPaciente"> CodPaciente: <span class="text-danger">*</span> </label>
									<input type="number" id="codPaciente" name="codPaciente" class="form-control" placeholder="CodPaciente" maxlength="11" number="true" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="codAutor"> CodAutor: <span class="text-danger">*</span> </label>
									<input type="number" id="codAutor" name="codAutor" class="form-control" placeholder="CodAutor" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="dataCriacao"> DataCriacao: <span class="text-danger">*</span> </label>
									<input type="date" id="dataCriacao" name="dataCriacao" class="form-control" dateISO="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="dataAtualizacao"> DataAtualizacao: <span class="text-danger">*</span> </label>
									<input type="date" id="dataAtualizacao" name="dataAtualizacao" class="form-control" dateISO="true" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="dataInicio"> DataInicio: </label>
									<input type="date" id="dataInicio" name="dataInicio" class="form-control" dateISO="true" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="dataEncerramento"> DataEncerramento: </label>
									<input type="date" id="dataEncerramento" name="dataEncerramento" class="form-control" dateISO="true" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="primeiraEscolha"> PrimeiraEscolha: <span class="text-danger">*</span> </label>
									<select id="primeiraEscolha" name="primeiraEscolha" class="custom-select" required>
									<option></option>	
									<option value="1">select1</option>
									<option value="2">select2</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="indicacaoAntibiotico"> IndicacaoAntibiotico: </label>
									<input type="number" id="indicacaoAntibiotico" name="indicacaoAntibiotico" class="form-control" placeholder="IndicacaoAntibiotico" maxlength="11" number="true" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="tipoInfeccao"> TipoInfeccao: </label>
									<input type="number" id="tipoInfeccao" name="tipoInfeccao" class="form-control" placeholder="TipoInfeccao" maxlength="11" number="true" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="respiratoria"> Respiratoria: </label>
									<input type="number" id="respiratoria" name="respiratoria" class="form-control" placeholder="Respiratoria" maxlength="11" number="true" >
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="urinaria"> Urinaria: </label>
									<input type="number" id="urinaria" name="urinaria" class="form-control" placeholder="Urinaria" maxlength="11" number="true" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="peleTecido"> peleTecido: </label>
									<input type="number" id="peleTecido" name="peleTecido" class="form-control" placeholder="peleTecido" maxlength="11" number="true" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="cirurgia"> Cirurgia: <span class="text-danger">*</span> </label>
									<input type="number" id="cirurgia" name="cirurgia" class="form-control" placeholder="Cirurgia" maxlength="11" number="true" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="correnteSanguinea"> CorrenteSanguinea: <span class="text-danger">*</span> </label>
									<input type="number" id="correnteSanguinea" name="correnteSanguinea" class="form-control" placeholder="CorrenteSanguinea" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="outros"> Outros: <span class="text-danger">*</span> </label>
									<input type="text" id="outros" name="outros" class="form-control" placeholder="Outros" maxlength="50" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="resultadoCultura"> resultadoCultura: <span class="text-danger">*</span> </label>
									<input type="number" id="resultadoCultura" name="resultadoCultura" class="form-control" placeholder="resultadoCultura" maxlength="11" number="true" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="faltaMedicamentoFarmacia"> FaltaMedicamentoFarmacia: <span class="text-danger">*</span> </label>
									<input type="number" id="faltaMedicamentoFarmacia" name="faltaMedicamentoFarmacia" class="form-control" placeholder="FaltaMedicamentoFarmacia" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="alergiaAntimicrobiano"> AlergiaAntimicrobiano: <span class="text-danger">*</span> </label>
									<input type="number" id="alergiaAntimicrobiano" name="alergiaAntimicrobiano" class="form-control" placeholder="AlergiaAntimicrobiano" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="insuficienciaRenal"> InsuficienciaRenal: <span class="text-danger">*</span> </label>
									<input type="number" id="insuficienciaRenal" name="insuficienciaRenal" class="form-control" placeholder="InsuficienciaRenal" maxlength="11" number="true" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="insuficienciaHepatica"> InsuficienciaHepatica: <span class="text-danger">*</span> </label>
									<input type="number" id="insuficienciaHepatica" name="insuficienciaHepatica" class="form-control" placeholder="InsuficienciaHepatica" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="outroEsquemaAlternativo"> OutroEsquemaAlternativo: <span class="text-danger">*</span> </label>
									<input type="text" id="outroEsquemaAlternativo" name="outroEsquemaAlternativo" class="form-control" placeholder="OutroEsquemaAlternativo" maxlength="50" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="justificativaEsquema"> JustificativaEsquema: <span class="text-danger">*</span> </label>
									<textarea cols="40" rows="5" id="justificativaEsquema" name="justificativaEsquema" class="form-control" placeholder="JustificativaEsquema" required></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="resultadoCultura"> ResultadoCultura: <span class="text-danger">*</span> </label>
									<textarea cols="40" rows="5" id="resultadoCultura" name="resultadoCultura" class="form-control" placeholder="ResultadoCultura" required></textarea>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="solicitouCultura"> SolicitouCultura: <span class="text-danger">*</span> </label>
									<input type="number" id="solicitouCultura" name="solicitouCultura" class="form-control" placeholder="SolicitouCultura" maxlength="11" number="true" required>
								</div>
							</div>
						</div>
																				
						<div class="form-group text-center">
							<div class="btn-group">
								<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Adicionar" id="controleAntimicrobianoAddForm-btn">Adicionar</button>
								<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Fechar" data-dismiss="modal">Fechar</button>
							</div>
						</div>
					</form>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->	

	<!-- Add modal content -->				
	<div id="controleAntimicrobianoEditModal" class="modal fade" role="dialog"
		aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
			<div class="modal-header bg-primary text-center p-3">
				<h4 class="modal-title text-white" id="info-header-modalLabel">Atualizar Controle Antimicrobiano</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
				<div class="modal-body">
					<form id="controleAntimicrobianoEditForm" class="pl-3 pr-3">
						<input type="hidden" id="<?php echo csrf_token() ?>controleAntimicrobianoEditForm" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">

                        <div class="row">
 							<input type="hidden" id="codControleAntimicrobiano" name="codControleAntimicrobiano" class="form-control" placeholder="CodControleAntimicrobiano" maxlength="11" required>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="codItem"> CodItem: <span class="text-danger">*</span> </label>
									<input type="number" id="codItem" name="codItem" class="form-control" placeholder="CodItem" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codAtendimento"> CodAtendimento: <span class="text-danger">*</span> </label>
									<input type="number" id="codAtendimento" name="codAtendimento" class="form-control" placeholder="CodAtendimento" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="codPaciente"> CodPaciente: <span class="text-danger">*</span> </label>
									<input type="number" id="codPaciente" name="codPaciente" class="form-control" placeholder="CodPaciente" maxlength="11" number="true" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="codAutor"> CodAutor: <span class="text-danger">*</span> </label>
									<input type="number" id="codAutor" name="codAutor" class="form-control" placeholder="CodAutor" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="dataCriacao"> DataCriacao: <span class="text-danger">*</span> </label>
									<input type="date" id="dataCriacao" name="dataCriacao" class="form-control" dateISO="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="dataAtualizacao"> DataAtualizacao: <span class="text-danger">*</span> </label>
									<input type="date" id="dataAtualizacao" name="dataAtualizacao" class="form-control" dateISO="true" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="dataInicio"> DataInicio: </label>
									<input type="date" id="dataInicio" name="dataInicio" class="form-control" dateISO="true" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="dataEncerramento"> DataEncerramento: </label>
									<input type="date" id="dataEncerramento" name="dataEncerramento" class="form-control" dateISO="true" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="primeiraEscolha"> PrimeiraEscolha: <span class="text-danger">*</span> </label>
									<select id="primeiraEscolha" name="primeiraEscolha" class="custom-select" required>
									<option></option>	
									<option value="1">select1</option>
									<option value="2">select2</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="indicacaoAntibiotico"> IndicacaoAntibiotico: </label>
									<input type="number" id="indicacaoAntibiotico" name="indicacaoAntibiotico" class="form-control" placeholder="IndicacaoAntibiotico" maxlength="11" number="true" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="tipoInfeccao"> TipoInfeccao: </label>
									<input type="number" id="tipoInfeccao" name="tipoInfeccao" class="form-control" placeholder="TipoInfeccao" maxlength="11" number="true" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="respiratoria"> Respiratoria: </label>
									<input type="number" id="respiratoria" name="respiratoria" class="form-control" placeholder="Respiratoria" maxlength="11" number="true" >
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="urinaria"> Urinaria: </label>
									<input type="number" id="urinaria" name="urinaria" class="form-control" placeholder="Urinaria" maxlength="11" number="true" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="peleTecido"> peleTecido: </label>
									<input type="number" id="peleTecido" name="peleTecido" class="form-control" placeholder="peleTecido" maxlength="11" number="true" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="cirurgia"> Cirurgia: <span class="text-danger">*</span> </label>
									<input type="number" id="cirurgia" name="cirurgia" class="form-control" placeholder="Cirurgia" maxlength="11" number="true" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="correnteSanguinea"> CorrenteSanguinea: <span class="text-danger">*</span> </label>
									<input type="number" id="correnteSanguinea" name="correnteSanguinea" class="form-control" placeholder="CorrenteSanguinea" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="outros"> Outros: <span class="text-danger">*</span> </label>
									<input type="text" id="outros" name="outros" class="form-control" placeholder="Outros" maxlength="50" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="resultadoCultura"> resultadoCultura: <span class="text-danger">*</span> </label>
									<input type="number" id="resultadoCultura" name="resultadoCultura" class="form-control" placeholder="resultadoCultura" maxlength="11" number="true" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="faltaMedicamentoFarmacia"> FaltaMedicamentoFarmacia: <span class="text-danger">*</span> </label>
									<input type="number" id="faltaMedicamentoFarmacia" name="faltaMedicamentoFarmacia" class="form-control" placeholder="FaltaMedicamentoFarmacia" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="alergiaAntimicrobiano"> AlergiaAntimicrobiano: <span class="text-danger">*</span> </label>
									<input type="number" id="alergiaAntimicrobiano" name="alergiaAntimicrobiano" class="form-control" placeholder="AlergiaAntimicrobiano" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="insuficienciaRenal"> InsuficienciaRenal: <span class="text-danger">*</span> </label>
									<input type="number" id="insuficienciaRenal" name="insuficienciaRenal" class="form-control" placeholder="InsuficienciaRenal" maxlength="11" number="true" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="insuficienciaHepatica"> InsuficienciaHepatica: <span class="text-danger">*</span> </label>
									<input type="number" id="insuficienciaHepatica" name="insuficienciaHepatica" class="form-control" placeholder="InsuficienciaHepatica" maxlength="11" number="true" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="outroEsquemaAlternativo"> OutroEsquemaAlternativo: <span class="text-danger">*</span> </label>
									<input type="text" id="outroEsquemaAlternativo" name="outroEsquemaAlternativo" class="form-control" placeholder="OutroEsquemaAlternativo" maxlength="50" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="justificativaEsquema"> JustificativaEsquema: <span class="text-danger">*</span> </label>
									<textarea cols="40" rows="5" id="justificativaEsquema" name="justificativaEsquema" class="form-control" placeholder="JustificativaEsquema" required></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="resultadoCultura"> ResultadoCultura: <span class="text-danger">*</span> </label>
									<textarea cols="40" rows="5" id="resultadoCultura" name="resultadoCultura" class="form-control" placeholder="ResultadoCultura" required></textarea>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="solicitouCultura"> SolicitouCultura: <span class="text-danger">*</span> </label>
									<input type="number" id="solicitouCultura" name="solicitouCultura" class="form-control" placeholder="SolicitouCultura" maxlength="11" number="true" required>
								</div>
							</div>
						</div>
											
						<div class="form-group text-center">
							<div class="btn-group">
								<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Salvar" id="controleAntimicrobianoEditForm-btn">Salvar</button>
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
	
$(function () {
	$('#data_tablecontroleAntimicrobiano').DataTable({
		"paging": true,
		"deferRender": true,
		"lengthChange": false,
		"searching": true,
		"ordering": true,
		"info": true,
		"autoWidth": false,
		"responsive": true,
		"ajax": {
			"url": '<?php echo base_url('controleAntimicrobiano/getAll') ?>',			
			"type": "POST",
			"dataType": "json",
			async: "true",
			data: {
				csrf_sandra: $("#csrf_sandraPrincipal").val(),
			},
		}	  
	});
});
function addcontroleAntimicrobiano() {
	// reset the form 
	$("#controleAntimicrobianoAddForm")[0].reset();
	$(".form-control").removeClass('is-invalid').removeClass('is-valid');		
	$('#controleAntimicrobianoAddModal').modal('show');
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
			
			var form = $('#controleAntimicrobianoAddForm');
			// remove the text-danger
			$(".text-danger").remove();

			$.ajax({
				url: '<?php echo base_url('controleAntimicrobiano/add') ?>',						
				type: 'post',
				data: form.serialize(), // /converting the form data into array and sending it to server
				dataType: 'json',
				beforeSend: function() {
					//$('#controleAntimicrobianoAddForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
				},					
				success: function(response) {

					if (response.success === true) {
							$('#controleAntimicrobianoAddModal').modal('hide');

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
							$('#data_tablecontroleAntimicrobiano').DataTable().ajax.reload(null, false).draw(false);
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
					$('#controleAntimicrobianoAddForm-btn').html('Adicionar');
				}
			});

			return false;
		}
	});
	$('#controleAntimicrobianoAddForm').validate();
}

function editcontroleAntimicrobiano(codControleAntimicrobiano) {
	$.ajax({
		url: '<?php echo base_url('controleAntimicrobiano/getOne') ?>',
		type: 'post',
		data: {
			codControleAntimicrobiano: codControleAntimicrobiano,
			csrf_sandra: $("#csrf_sandraPrincipal").val(),
		},
		dataType: 'json',
		success: function(response) {
			// reset the form 
			$("#controleAntimicrobianoEditForm")[0].reset();
			$(".form-control").removeClass('is-invalid').removeClass('is-valid');				
			$('#controleAntimicrobianoEditModal').modal('show');	

			$("#controleAntimicrobianoEditForm #codControleAntimicrobiano").val(response.codControleAntimicrobiano);
			$("#controleAntimicrobianoEditForm #codItem").val(response.codItem);
			$("#controleAntimicrobianoEditForm #codAtendimento").val(response.codAtendimento);
			$("#controleAntimicrobianoEditForm #codPaciente").val(response.codPaciente);
			$("#controleAntimicrobianoEditForm #codAutor").val(response.codAutor);
			$("#controleAntimicrobianoEditForm #dataCriacao").val(response.dataCriacao);
			$("#controleAntimicrobianoEditForm #dataAtualizacao").val(response.dataAtualizacao);
			$("#controleAntimicrobianoEditForm #dataInicio").val(response.dataInicio);
			$("#controleAntimicrobianoEditForm #dataEncerramento").val(response.dataEncerramento);
			$("#controleAntimicrobianoEditForm #primeiraEscolha").val(response.primeiraEscolha);
			$("#controleAntimicrobianoEditForm #indicacaoAntibiotico").val(response.indicacaoAntibiotico);
			$("#controleAntimicrobianoEditForm #tipoInfeccao").val(response.tipoInfeccao);
			$("#controleAntimicrobianoEditForm #respiratoria").val(response.respiratoria);
			$("#controleAntimicrobianoEditForm #urinaria").val(response.urinaria);
			$("#controleAntimicrobianoEditForm #peleTecido").val(response.peleTecido);
			$("#controleAntimicrobianoEditForm #cirurgia").val(response.cirurgia);
			$("#controleAntimicrobianoEditForm #correnteSanguinea").val(response.correnteSanguinea);
			$("#controleAntimicrobianoEditForm #outros").val(response.outros);
			$("#controleAntimicrobianoEditForm #resultadoCultura").val(response.resultadoCultura);
			$("#controleAntimicrobianoEditForm #faltaMedicamentoFarmacia").val(response.faltaMedicamentoFarmacia);
			$("#controleAntimicrobianoEditForm #alergiaAntimicrobiano").val(response.alergiaAntimicrobiano);
			$("#controleAntimicrobianoEditForm #insuficienciaRenal").val(response.insuficienciaRenal);
			$("#controleAntimicrobianoEditForm #insuficienciaHepatica").val(response.insuficienciaHepatica);
			$("#controleAntimicrobianoEditForm #outroEsquemaAlternativo").val(response.outroEsquemaAlternativo);
			$("#controleAntimicrobianoEditForm #justificativaEsquema").val(response.justificativaEsquema);
			$("#controleAntimicrobianoEditForm #resultadoCultura").val(response.resultadoCultura);
			$("#controleAntimicrobianoEditForm #solicitouCultura").val(response.solicitouCultura);

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
					var form = $('#controleAntimicrobianoEditForm');
					$(".text-danger").remove();
					$.ajax({
						url: '<?php echo base_url('controleAntimicrobiano/edit') ?>' ,						
						type: 'post',
						data: form.serialize(), 
						dataType: 'json',
						beforeSend: function() {
							//$('#controleAntimicrobianoEditForm-btn').html('<i class="fa fa-spinner fa-spin"></i>');
						},								
						success: function(response) {

							if (response.success === true) {
								
									$('#controleAntimicrobianoEditModal').modal('hide');

								
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
									$('#data_tablecontroleAntimicrobiano').DataTable().ajax.reload(null, false).draw(false);
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
							$('#controleAntimicrobianoEditForm-btn').html('Salvar');
						}
					});

					return false;
				}
			});
			$('#controleAntimicrobianoEditForm').validate();

		}
	});
}	

function removecontroleAntimicrobiano(codControleAntimicrobiano) {	
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
			url: '<?php echo base_url('controleAntimicrobiano/remove') ?>',
			type: 'post',
			data: {
				codControleAntimicrobiano: codControleAntimicrobiano,
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
						$('#data_tablecontroleAntimicrobiano').DataTable().ajax.reload(null, false).draw(false);								
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
