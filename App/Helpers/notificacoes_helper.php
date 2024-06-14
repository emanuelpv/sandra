<?php

use App\Models\ServicoSMTPModel as ServicoSMTPModel;
use App\Models\OrganizacoesModel;
use App\Models\NotificacoesFilaModel;
use App\Models\ServicosSMSModel;
use App\Models\LogsModel;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once('App/Libraries/PHPMailer_6.6.3/src/Exception.php');
require_once('App/Libraries/PHPMailer_6.6.3/src/PHPMailer.php');
require_once('App/Libraries/PHPMailer_6.6.3/src/SMTP.php');

function cabecalho($organizacao)
{
    $logoOrganizacao = $organizacao->logo;
    $descricaoOrganizacao = $organizacao->descricao;
    $siglaOrganizacao = $organizacao->siglaOrganizacao;
    $endereço = $organizacao->endereço;
    $telefone =  $organizacao->telefone;
    $cabecalho =  $organizacao->cabecalho;
    $rodape =  $organizacao->rodape;

    if ($logoOrganizacao !== NULL and $logoOrganizacao !== "") {

        $texto =  '<img width="150px" src="cid:logo_2u" />';
        return $texto;
    } else {
        return "";
    }
}

function rodape($organizacao)
{
    $logoOrganizacao = $organizacao->logo;
    $descricaoOrganizacao = $organizacao->descricao;
    $siglaOrganizacao = $organizacao->siglaOrganizacao;
    $endereço = $organizacao->endereço;
    $telefone =  $organizacao->telefone;
    $cnpj =  $organizacao->cnpj;
    $site =  $organizacao->site;
    $cabecalho =  $organizacao->cabecalho;
    $rodape =  $organizacao->rodape;

    $texto = '<div>';
    if ($descricaoOrganizacao !== NULL and $descricaoOrganizacao !== "") {
        $texto .= $descricaoOrganizacao . ' | ';
    }
    if ($endereço !== NULL and $endereço !== "") {
        $texto .= $endereço . ' | ';
    }
    if ($telefone !== NULL and $telefone !== "") {
        $texto .= 'Telefone: ' . $telefone . ' | ';
    }
    if ($cnpj !== NULL and $cnpj !== "") {
        $texto .= 'CNPJ: ' . $cnpj . ' | ';
    }
    if ($site !== NULL and $site !== "") {
        $texto .= '<span style="a:link {color: green;background-color: transparent;text-decoration: none;}">' . $site . '</span> | ';
    }
    $texto = trim($texto);
    $texto = mb_substr($texto, 0, -1);

    $texto .= '</div>';
    return $texto;
}

function assinatura($organizacao)
{
}

function redesSociais($organizacao)
{
    $linkedin_url = $organizacao->linkedin_url;
    $facebook_url = $organizacao->facebook_url;
    $instagram_url = $organizacao->instagram_url;
    $twitter_url =  $organizacao->twitter_url;
    $youtube_url =  $organizacao->youtube_url;
    $linkedin_ico = base_url() . '/imagens/linkedin.png';
    $facebook_ico = base_url() . '/imagens/facebook.png';
    $instagram_ico = base_url() . '/imagens/instagram.png';
    $twitter_ico = base_url() . '/imagens/twitter.png';
    $youtube_ico = base_url() . '/imagens/youtube.png';

    $texto = '<div>';
    if ($linkedin_url !== NULL and $linkedin_url !== "") {
        $texto .= '<a href="' . $linkedin_url . '"><i class="fa-brands fa-whatsapp"></i></a>';
    }
    if ($facebook_url !== NULL and $facebook_url !== "") {
        $texto .= '<a href="' . $facebook_url . '"><i class="fa-brands fa-whatsapp"></i></a>';
    }
    if ($instagram_url !== NULL and $instagram_url !== "") {
        $texto .= '<a href="' . $instagram_url . '"><i class="fa-brands fa-whatsapp"></i></a>';
    }
    if ($twitter_url !== NULL and $twitter_url !== "") {
        $texto .= '<a href="' . $twitter_url . '"><i class="fa-brands fa-whatsapp"></i></a>';
    }
    if ($youtube_url !== NULL and $youtube_url !== "") {
        $texto .= '<a href="' . $youtube_url . '"><i class="fa-brands fa-whatsapp"></i></a>';
    }
    $texto = trim($texto);
    $texto = mb_substr($texto, 0, -1);

    $texto .= '</div>';
    return $texto;
}
function corpoEmail($organizacao, $conteudo)
{
    $cabecalho = cabecalho($organizacao);
    $rodape = rodape($organizacao);
    $redesSociais = redesSociais($organizacao);
    $request = \Config\Services::request();
    $ip = $request->getIPAddress();

    if (session()->cpf !== NULL) {
        $cpf = session()->cpf;
    } else {
        $cpf = "";
    }



    $corpo =
        '<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

        <title></title>

        <style>

        .ii a[href] {
            color: #fff;
            text-shadow: 2px 2px 4px #000000;
        }
            .container {
                #background: #f4f6f9;
                color: #1f2d3db8;

            }

            .texto {
                padding-left: 15px;
                font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
                font-size: 30px;


            }
        </style>
    </head>

    <body>

        <div class="container">
            <div class="row">
                <div style="text-align:center" >
                   ' . $cabecalho . '
                </div>
                 <div>
                    <div style=" font-size: 20px; padding-top: 20px;padding-right: 15px; padding-left: 15px;padding-bottom: 15px;width: 100%;min-height:200px !important; background:#3156bf3b">
                    ' . $conteudo . '
                     <div style=" font-size: 10px" >CPF AUTOR:' . $cpf . ' | IP:' . $ip . '</div>
                     </div >
                     <div style="padding-top: 10px;padding-right: 10px; padding-left: 10px;padding-bottom: 10px;font-size: 10px; width: 100%;height:auto;color:#fff;background:#ff0000d1;text-align:center;vertical-align:middle">
                     ' . $rodape . '
                     </div>

                     <div style="padding-top: 10px;padding-right: 10px; padding-left: 10px;padding-bottom: 10px;font-size: 15px; width: 100%;height: 50px;color:#fff;background:#4285f4;text-align:center;vertical-align:middle">
                    Siga-nos nas nossas redes sociais!
                     </div>
                </div>
            </div>
        </div>
    </body>

    </html>';
    return  $corpo;
}

function addNotificacoesFila($conteudo = null, $remetente = null, $destinatario = null, $codProtocoloNotificacao = null)
{


    $NotificacoesFilaModel = new NotificacoesFilaModel();

    $notificacoesFila['conteudo'] = $conteudo;
    $notificacoesFila['remetente'] = $remetente;
    $notificacoesFila['destinatario'] = $destinatario;
    $notificacoesFila['codOrganizacao'] = session()->codOrganizacao;
    $notificacoesFila['autor'] = session()->codPessoa;
    $notificacoesFila['codProtocoloNotificacao'] = $codProtocoloNotificacao; //
    $notificacoesFila['dataAtualizacao'] = date('Y-m-d H:i');
    $notificacoesFila['autor'] = session()->codPessoa;




    if ($NotificacoesFilaModel->insert($notificacoesFila)) {
        return true;
    } else {
        return true;
    }
}


function email($destinatario = null, $assunto = null, $conteudo = null)
{


    $organizacoesModel = new OrganizacoesModel();
    $servicoSMTPModel = new ServicoSMTPModel();
    $dadosConexao = $servicoSMTPModel->pegaAtivo();

    $organizacao = $organizacoesModel->pegaOrganizacao(session()->codOrganizacao);


    if ($dadosConexao !== NULL) {

        if ($dadosConexao->protocoloSMTP == 1) {
            $protocoloSMTP = 'ssl';
        }
        if ($dadosConexao->protocoloSMTP == 2) {
            $protocoloSMTP = 'tls';
        }

        if ($dadosConexao->protocoloSMTP == 3) {
            $protocoloSMTP = 'startls';
        }

        require_once('App/Libraries/PHPMailer_5.2.4/class.phpmailer.php');
        if ($dadosConexao->emailRetorno == NULL) {
            $emailRemetente = $dadosConexao->loginSMTP;
        } else {
            $emailRemetente = $dadosConexao->emailRetorno;
        }

        $emailRemetente = trim($emailRemetente);
        $nome_cliente = $organizacao->siglaOrganizacao;
        $mail = new PHPMailer(true);


        $mail->isSMTP();
        $mail->Host = $dadosConexao->ipServidorSMTP;
        $mail->SetFrom($emailRemetente, utf8_decode($nome_cliente));
        $mail->AddReplyTo($emailRemetente, utf8_decode($nome_cliente));
        $mail->SMTPAuth = true;
        $mail->Timeout  = 4; // set the timeout (seconds)
        $mail->Username = $dadosConexao->loginSMTP;
        $mail->Password = $dadosConexao->senhaSMTP;
        $mail->SMTPSecure = $protocoloSMTP;
        $mail->Port = $dadosConexao->portaSMTP;
        $mail->isHTML(true);
        $mail->addAddress($destinatario);
        $mail->Subject = utf8_decode($assunto);
        $mail->AddEmbeddedImage('imagens/organizacoes/' . $organizacao->logo, 'logo_2u');
        $mailContent = corpoEmail($organizacao, $conteudo);


        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail->Body = $mailContent;

        // Send email
        try {
            if (!$mail->send()) {
                return false;
            } else {
                return true;
            }
        } catch (phpmailerException $e) {
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
}


function sms($destinatario = null, $mensagem)
{

    $ServicosSMS = new ServicosSMSModel();

    $provedores = $ServicosSMS->pegaAtivoComCreditos();

    foreach ($provedores as $provedor) {

        if ($provedor->codProvedor == 1 and $provedor->creditos > 0) {
            //IMPLEMENTAR ZENVIA

        }

        if ($provedor->codProvedor == 2 and $provedor->creditos > 0) {
            //IMPLEMENTAR MEX
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "https://mex10.com/api/shortcodev2.aspx");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3); //timeout in seconds
            $parametros = array(
                'token' => $provedor->token,
                't' => 'send',
                'n' => $destinatario,
                'm' => $mensagem,
            );

            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parametros));

            $response = curl_exec($ch);
            curl_close($ch);
            if ($response !== null) {
                if (json_decode($response)->data->success == 1) {

                    $fields['creditos'] = --$provedor->creditos;

                    if ($provedor->codServicoSMS !== NULL and $provedor->codServicoSMS !== "") {
                        if ($ServicosSMS->update($provedor->codServicoSMS, $fields)) {
                        }
                    }
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        if ($provedor->codProvedor == 2 and $provedor->creditos > 0) {
            //IMPLEMENTAR TWLIO

        }
    }
}

function emailTeste($destinatario = null, $ipServidorSMTP = null, $portaSMTP = null, $loginSMTP = null, $senhaSMTP = null, $emailRetorno = null, $protocoloSMTP = null, $statusSMTP = null)
{

    $LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);

    $organizacoesModel = new OrganizacoesModel();

    $organizacao = $organizacoesModel->pegaOrganizacao(session()->codOrganizacao);


    if ($destinatario !== NULL) {

        if ($protocoloSMTP == 0) {
            $tipoprotocoloSMTP = 'false';
        }
        if ($protocoloSMTP == 1) {
            $tipoprotocoloSMTP = 'ssl';
        }
        if ($protocoloSMTP == 2) {
            $tipoprotocoloSMTP = 'tls';
        }

        if ($protocoloSMTP == 3) {
            $tipoprotocoloSMTP = 'startls';
        }

        // require_once('App/Libraries/PHPMailer_5.2.4/class.phpmailer.php');

        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->SMTPAutoTLS = false;
        $mail->Host = $ipServidorSMTP;
        $mail->SetFrom($loginSMTP, utf8_decode('TESTE'));
        $mail->AddReplyTo($emailRetorno, utf8_decode('TESTE'));
        $mail->SingleTo = true;
        $mail->SMTPAuth = true;
        $mail->Timeout  = 4; // set the timeout (seconds)
        $mail->Username = $loginSMTP;
        $mail->Password = $senhaSMTP;
        $mail->SMTPSecure = $tipoprotocoloSMTP;
        $mail->Port = $portaSMTP;
        $mail->isHTML(true);
        $mail->addAddress($destinatario);
        $mail->Subject = utf8_decode('TESTE');
        $conteudo = 'Este é um E-mail de Teste';
        $mailContent = corpoEmail($organizacao, $conteudo);


        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail->Body = $mailContent;

        // Send email
        try {
            if (!$mail->send()) {
                $LogsModel->inserirLog('Falha ao enviar email para "' . $destinatario . '"', session()->codPessoa);
                return false;
            } else {
                return true;
            }
        } catch (phpmailerException $e) {
            $LogsModel->inserirLog($e->errorMessage() . ' - Falha ao enviar email para "' . $destinatario . '"', session()->codPessoa);
            return false;
        } catch (Exception $e) {
            $LogsModel->inserirLog($e->getMessage() . ' - Falha ao enviar email para "' . $destinatario . '"', session()->codPessoa);
            return false;
        }
    }
}
