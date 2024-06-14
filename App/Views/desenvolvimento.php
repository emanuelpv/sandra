<?php
//É NECESSÁRIO EM TODAS AS VIEWS

$codOrganizacao = session()->codOrganizacao;
$codPessoa = session()->codPessoa;



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
        <div class="card-body">



          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <a style="margin-left:10px" type="buttom" href="<?php echo base_url() . "/harviacode/?p=generator" ?>" class="btn btn-block btn-outline-primary btn-lg" title="Adicionar">
                  <div><i class="fa fa-code fa-3x" aria-hidden="true"></i></div>
                  <div>CRUD</div>
                </a>
              </div>
            </div>
          </div>



        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
</section>

<!-- /.content -->
<?php
echo view('tema/rodape');
?>