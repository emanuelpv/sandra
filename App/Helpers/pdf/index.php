<?php

// Include the main TCPDF library (search for installation path).
require_once('tcpdf.php');


$assunto =$this->session->userdata('DADOS_DOCUMENTO')['assunto']; 
$cod_documento = $this->session->userdata('DADOS_DOCUMENTO')['cod_documento']; 
$cod_cliente =$this->session->userdata('DADOS_DOCUMENTO')['cod_cliente']; 
$cod_demanda =$this->session->userdata('DADOS_DOCUMENTO')['cod_demanda']; 
$cod_pessoa =$this->session->userdata('DADOS_DOCUMENTO')['cod_pessoa']; 
$cod_tipo =$this->session->userdata('DADOS_DOCUMENTO')['cod_tipo']; 
$corpodotexto =$this->session->userdata('DADOS_DOCUMENTO')['conteudo']; 
$data_criacao = $this->session->userdata('DADOS_DOCUMENTO')['data_criacao']; 
$data_atualizacao = $this->session->userdata('DADOS_DOCUMENTO')['data_atualizacao']; 
$data_assinatura =$this->session->userdata('DADOS_DOCUMENTO')['data_assinatura']; 
$ds_cabecalho1 =$this->session->userdata('DADOS_DOCUMENTO')['ds_cabecalho1']; 
$ds_cabecalho2 =$this->session->userdata('DADOS_DOCUMENTO')['ds_cabecalho2']; 
$ds_cabecalho3 =$this->session->userdata('DADOS_DOCUMENTO')['ds_cabecalho3'];
$ds_cabecalho4 =$this->session->userdata('DADOS_DOCUMENTO')['ds_cabecalho4'];
$ds_cabecalho5 =$this->session->userdata('DADOS_DOCUMENTO')['ds_cabecalho5'];
$ds_cabecalho6 =$this->session->userdata('DADOS_DOCUMENTO')['ds_cabecalho6'];
$cidade_om =$this->session->userdata('DADOS_DOCUMENTO')['cidade_om'];
$uf_om =$this->session->userdata('DADOS_DOCUMENTO')['uf_om'];
$cep_om =$this->session->userdata('DADOS_DOCUMENTO')['cep_om'];
$telefone_om =$this->session->userdata('DADOS_DOCUMENTO')['telefone_om'];
$msg_parametro = $this->session->userdata('DADOS_DOCUMENTO')['msg_parametro'];
$equipe =  $this->session->userdata('DADOS_DOCUMENTO')['equipe'];
$nup =  $this->session->userdata('DADOS_DOCUMENTO')['nup'];
$seq_documento=  $this->session->userdata('DADOS_DOCUMENTO')['seq_documento'];
$cod_destinatario_interno = @$this->session->userdata('DADOS_DOCUMENTO')['cod_destinatario_interno'];
$cod_destinatario_externo = @$this->session->userdata('DADOS_DOCUMENTO')['cod_destinatario_externo'];
$cod_remetente = @$this->session->userdata('DADOS_DOCUMENTO')['cod_remetente'];
$urgencia = @$this->session->userdata('DADOS_DOCUMENTO')['urgencia'];
$tipo_urgencia = @$this->session->userdata('DADOS_DOCUMENTO')['tipo_urgencia'];
$campoDo = @$this->session->userdata('DADOS_DOCUMENTO')['campoDo'];
$campoAo = @$this->session->userdata('DADOS_DOCUMENTO')['campoAo'];




$descricaouegencia = "";
if($tipo_urgencia == 1){
    $descricaouegencia = '<div style="color:red; font-weight: bold;">URGENTE</div>';
}
if($tipo_urgencia == 2){
    $descricaouegencia = '<div style="color:red; font-weight: bold;">URGENTÍSSIMO</div>';
}

if($campoDo == 1){
    $descricaocampoDo = "<b>Do</></b>";
}

if($campoDo == 2){
    $descricaocampoDo = "<b>Da</></b>";
}



if($campoAo == 1){
    $descricaocampoAo = "<b>À </></b>";
}
if($campoAo == 2){
    $descricaocampoAo = "<b>Ao Sr</></b>";
}
if($campoAo == 3){
    $descricaocampoAo = "<b>À Sra ;</>";
}



if($data_assinatura == null){
	
	$datadocumento = $data_criacao;
	
}else{
	
	$datadocumento = $data_assinatura;
}

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);;
$pdf->SetTitle(@$assunto);

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
// set default header data

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// -------------------------------------------------------------------

$pdf->SetFont('helvetica', '', 10);
// add a page
$pdf->AddPage();

// set JPEG quality
$pdf->setJPEGQuality(75);

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// Image example with resizing

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -


// Stretching, position and alignment example


//     ******************          SITES INTEREENTES DE CONFIGURAÇÃO                 ********************

/*
https://blog.webnersolutions.com/tcpdf-generate-pdf-from-html/
https://tcpdf.org/examples/example_039/	



*/




//IMAGEM
$pdf->SetXY(110, 5);
$pdf->Image('images/brazao.png', '', '', 25, 25, '', '', 'T', false, 300, 'C', false, false, 0, false, false, false);

$conteudo = "";

$estilo = '
    <style>
    div {
	font-size:12pt;
	width: 175mm;
	margin-top:12mm;
	margin-left:20mm
	text-align: justify;
	vertical-align: top;
	font-family: "Times New Roman", Times, serif;
}
        
       </style>';
$conteudo .= $estilo;


$pdf->SetXY(12, 28);


//CABEÇALHO
$cabecalho = '

<span style="font-size: 12pt;
    font-weight: bold;
    text-align: center;">	
<br>
<b>'.$ds_cabecalho1.'</b><br>
<b>'.$ds_cabecalho2.'</b><br>
<b>'.$ds_cabecalho3.'</b><br>
<b>'.$ds_cabecalho4.'</b><br>	
<b>'.$ds_cabecalho5.'</b><br>	
<b>'.$ds_cabecalho6.'</b><br>	
<br>
</span>';
$conteudo .= $cabecalho;


$numeracao = '<span>'.$descricaouegencia.'</span>'
        . '<div style="margin-top:50px"><left><span style="text-align:left"><b>Processo Nº </b>'.$nup.'</span><left><br>'
        . '<left><span style="text-align:left"><b>DIEx Nº </b> '.$seq_documento.'</span><left>';

$conteudo .= $numeracao;

        

$data = '<br><br><div style="margin-botton:30px;text-align:right">'.$cidade_om.'-'.$uf_om.', '.date('j',strtotime($datadocumento)).' de '.strtolower(meses(date('n',strtotime($datadocumento)))).' de '.date('Y',strtotime($datadocumento)).'</div>';
$conteudo .= $data;



$do_para_assunto = "<br><br>";
$do_para_assunto .= '<left><span style="text-align:left">'.$descricaocampoDo.' '.get_nome_remetente($cod_remetente). '</span><left><br>';
$do_para_assunto .= '<left><span style="text-align:left">'.$descricaocampoAo.' '.get_nome_destinatario_interno($cod_destinatario_interno).'</span><left><br>';
$do_para_assunto .= '<left><span style="text-align:left"><b>Assunto</b> '.$assunto.'</span><left><br>';
$conteudo .= $do_para_assunto;







$conteudo .= '<div>'.$corpodotexto;
$conteudo .= '<div></div>';
$conteudo .= '<div></div>';
$conteudo .= '<div></div>';
foreach($equipe as $membro){
	

$conteudo .= '<br><span style="text-align:center" >______________________________________________</span><br>';
$conteudo .= '<span style="text-align:center"  >'.$membro['nomecompleto'].' - '. $membro['sigla'].'</span><br>';
$conteudo .= '<span style="text-align:center"  >'.$membro['nome'].'</span><br>';
$conteudo .= '</div>';
	
}	
$pdf->writeHTML($conteudo);
 
 // get the current page break margin.
$bMargin = $pdf->getBreakMargin();
// get current auto-page-break mode.
$auto_page_break = $pdf->getAutoPageBreak();
// enable auto page break.
$pdf->SetAutoPageBreak($auto_page_break, $bMargin);

$pdf->Output('doc.pdf', 'I');