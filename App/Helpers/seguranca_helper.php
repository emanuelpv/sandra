<?php

namespace App\Controllers;

use CodeIgniter\Services;
use App\Controllers\BaseController;
use App\Models\PessoasModel;
use App\Models\GruposModel;
use App\Models\ServicoLDAPModel;
use App\Models\OrganizacoesModel;
use App\Models\AtributosSistemaModel;
use App\Models\MapeamentoAtributosLDAPModel;
use App\Models\LogsModel;


function pegaCsrf()
{
    return  csrf_hash();
}


function verificaSeguranca($CI, $session, $aplicacao_url)
{



    //SET TIMEZONE
    $timezone = $session->timezone;
    date_default_timezone_set($timezone);


    // print_r($_GET['autorizacao']);
    //EXIT();

    /*
    helper('cookie');

    if (get_cookie('autorizacao') !== NULL and session()->autorizacao !== NULL) {
        if (md5(session()->autorizacao . session()->cpf) == get_cookie('autorizacao')) {
        } else {
            //header("Location:" . base_url());
          //  exit();
        }
    }
    */


    if ($session->estaLogado == NULL) {

?>
        <script>
            window.location.href = "<?php echo $aplicacao_url . '/login/logout' ?>";
        </script>
    <?php
        exit();
    }
}




function raizPermissoes($permissao)
{
    $url = explode("/", current_url());
    $controler = $url[count($url) - 1];



    if (verificaPermissao($controler, $permissao) !== 1) {
        session()->setFlashdata('mensagem_erro', 'Você não tem acesso a este recurso');
    ?>
        <script>
            window.location.href = "javascript:history.back()";
        </script>
<?php

    }
}

function verificaPermissao($modulo, $permissao)
{


    $liberados = array('principal');
    if (in_array($modulo, $liberados)) {
        return 1;
    }



    foreach (session()->meusModulos as $meuModulo) {

        if (trim(mb_strtolower($meuModulo->link, 'utf-8')) == trim(mb_strtolower($modulo, 'utf-8'))) {

            if ($permissao == 'listar') {
                return  $meuModulo->listar;
            }
            if ($permissao == 'adicionar') {
                return $meuModulo->adicionar;
            }
            if ($permissao == 'editar') {
                return $meuModulo->editar;
            }
            if ($permissao == 'deletar') {
                return $meuModulo->deletar;
            }
        }
    }
    return 0;
}


function mensagemAcessoSomenteIntranet($organizacao =  null)
{


    if (session()->logo !== NULL) {
        $cabechalho = "
        <div>
        <img style='margin-left:5px;width:70px' src='" . base_url() . "/imagens/organizacoes/" . session()->logo . "'>
        
        </div>
        <div>
        " . session()->descricaoOrganizacao . "
        </div>
";
    }
    if (session()->nomeExibicao !== NULL) {

        $request = \Config\Services::request();
        $ip = $request->getIPAddress();

        $dadosAcesso = "
        
        <div style='font-weight: bold;'>DADOS DO ACESSO</div>
        <div>Autor:" . session()->nomeExibicao . "</div>
        <div>Data/Hora:" . date('d/m/Y H:i') . "</div>
        <div>IP:" . $ip . "</div>
        
        ";
    }

    $corpo =
        '<html>
    
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
            <title></title>
    
            <style>
    
                .container {
                    #background: #d534347a;
                    color: #1f2d3db8;
                    
                   
                }
    
                .texto {
                    padding-left: 0px;
                    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
                    font-size: 30px;

    
    
                }
            </style>
        </head>
    
        <body>
    
            <div class="container">
                <div class="row">
                    <div style="text-align:center" >
                       
                    </div>
                     <div>
                        <div>
                        
                        <div style="text-align:center;font-weight: bold;">' . $cabechalho . '</div>

                        <table>
                        <tr>
                        <td>
                        <span style="text-align:center;font-weight: bold;"><img style="margin-left:5px;width:70px" src="' . base_url('imagens/atencao.gif') . '"></span>
                        </td>
                        <td>
                          <div style="color:red" >Acesso a este recurso apenas pela Intranet!</div>
                        </td>
                        <tr>
                        </table>
                      
                        <div>
                        
                    ' . $dadosAcesso . '
                        </div>
                        </div>
                         <div style="padding-top: 10px;padding-right: 10px; padding-left: 10px;padding-bottom: 10px;font-size: 10px; width: 100%;height:auto;color:#fff;background:gray;text-align:center;vertical-align:middle">
                         
                         </div>
                    </div>
                </div>
            </div>
        </body>
    
        </html>';
    return  $corpo;
}

function mensagemAcessoNegado($organizacao =  null)
{


    if (session()->logo !== NULL) {
        $cabechalho = "
        <div>
        <img style='margin-left:5px;width:70px' src='" . base_url() . "/imagens/organizacoes/" . session()->logo . "'>
        
        </div>
        <div>
        " . session()->descricaoOrganizacao . "
        </div>
";
    }
    if (session()->nomeExibicao !== NULL) {

        $request = \Config\Services::request();
        $ip = $request->getIPAddress();

        $dadosAcesso = "
        
        <div style='font-weight: bold;'>DADOS DO ACESSO</div>
        <div>Autor:" . session()->nomeExibicao . "</div>
        <div>Data/Hora:" . date('d/m/Y H:i') . "</div>
        <div>IP:" . $ip . "</div>
        
        ";
    }

    $corpo =
        '<html>
    
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
            <title></title>
    
            <style>
    
                .container {
                    #background: #d534347a;
                    color: #1f2d3db8;
                    
                   
                }
    
                .texto {
                    padding-left: 0px;
                    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
                    font-size: 30px;

    
    
                }
            </style>
        </head>
    
        <body>
    
            <div class="container">
                <div class="row">
                    <div style="text-align:center" >
                       
                    </div>
                     <div>
                        <div>
                        
                        <div style="text-align:center;font-weight: bold;">' . $cabechalho . '</div>

                        <table>
                        <tr>
                        <td>
                        <span style="text-align:center;font-weight: bold;"><img style="margin-left:5px;width:70px" src="' . base_url('imagens/atencao.gif') . '"></span>
                        </td>
                        <td>
                          <div style="color:red" >Você não tem acesso a este recurso!</div>
                        <div  style="color:red">Contate o Administrador do Sistema</div>
                        </td>
                        <tr>
                        </table>
                      
                        <div>
                        
                    ' . $dadosAcesso . '
                        </div>
                        </div>
                         <div style="padding-top: 10px;padding-right: 10px; padding-left: 10px;padding-bottom: 10px;font-size: 10px; width: 100%;height:auto;color:#fff;background:gray;text-align:center;vertical-align:middle">
                         
                         </div>
                    </div>
                </div>
            </div>
        </body>
    
        </html>';
    return  $corpo;
}


function encriptar($chave, $tipo_cifra, $texto)
{
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($tipo_cifra));
    $encrypted = openssl_encrypt($texto, $tipo_cifra, $chave, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

function descriptar($chave, $tipo_cifra, $texto)
{
    list($encrypted_data, $iv) = explode('::', base64_decode($texto), 2);
    // print  $chave;exit(); 
    return openssl_decrypt($encrypted_data, $tipo_cifra, $chave, 0, $iv);
}


function desativarPessoaHelper($CI, $codPessoa = null, $codMotivoInativo = NULL)
{
    $OrganizacoesModel = new OrganizacoesModel();
    $PessoasModel = new PessoasModel();
    $ServicoLDAPModel = new ServicoLDAPModel();
    $LogsModel = new LogsModel();

    $response = array();


    if ($codPessoa !== NULL) {
        $codPessoa = $codPessoa;
        if (!is_numeric($codPessoa)) {
            exit();
        }
        $pessoa = $PessoasModel->pegaPessoaPorCodPessoa($codPessoa);
    }



    if ($CI->request->getPost('codPessoa') !== NULL) {
        $codPessoa = $CI->request->getPost('codPessoa');
        $pessoa = $PessoasModel->pegaPessoaPorCodPessoa($codPessoa);
    }


    $servidoresLDAP = $ServicoLDAPModel->pegaTudoAtivo();



    foreach ($servidoresLDAP as $servidorLDAP) {

        //DEFINE CHAVE DN
        if ($servidorLDAP->atributoChave == NULL) {
            //$chaveDN = 'cn';
            $response['success'] = false;
            $response['messages'] = 'Falta definir o Atributo Chave para novos usuários do servidor LDAP ' . $servidorLDAP->descricaoServidorLDAP . "(" . $servidorLDAP->ipServidorLDAP . ")";
            return $CI->response->setJSON($response);
        } else {
            $chaveDN = $servidorLDAP->atributoChave;
        }




        $loginLDAP = $ServicoLDAPModel->conectaldap($servidorLDAP->loginLDAP, $servidorLDAP->senhaLDAP, $servidorLDAP->codServidorLDAP);

        if ($loginLDAP['status'] == 1) {
            $dadosLdapPessoa = $ServicoLDAPModel->pegaPessoas($loginLDAP['tipoldap'], $orderby = 'sn', $pessoa->conta);


            if ($loginLDAP['tipoldap'] == 1) {
                // print $dadosLdapPessoa[0]["distinguishedname"][0]; exit();
                $desativarAD["useraccountcontrol"][0] = 546;
                @ldap_modify($loginLDAP['statusConexao'], $dadosLdapPessoa[0]["distinguishedname"][0], $desativarAD);
                //não tirar o @
            }

            if ($loginLDAP['tipoldap'] == 2) {
                $desativarOpenLDAP["userPassword"] = 'desativado' . $dadosLdapPessoa[0]['userpassword'][0];

                @ldap_modify($loginLDAP['statusConexao'], $dadosLdapPessoa[0]["dn"], $desativarOpenLDAP);
                //não tirar o @
            }
        }
    }


    //DESATIVAR SPED

    $organizacao = $OrganizacoesModel->pegaOrganizacao(session()->codOrganizacao);

    if ($organizacao->servidorSPEDStatus == 1) {
        //VAI LA NO SPED E FAZ ALGUMA COISA
    }

    $fields["ativo"] = 0;
    $fields["codMotivoInativo"] = $codMotivoInativo;

    if ($PessoasModel->desativarPessoa($codPessoa, $fields)) {

        $response['success'] = true;
        $response['csrf_hash'] = csrf_hash();
        $response['messages'] = 'Pessoa desativada com sucesso';
    } else {

        $response['success'] = false;
        $response['messages'] = 'Erro na atualização!';
    }

    return $response;
}


function userAccountControlAtivo($codTipo = NULL)
{

    if ($codTipo == NULL) {
        return 512;
    }

    //Microsoft Windows NT 4.0
    if ($codTipo == 1) {
        return 512;
    }


    //Microsoft Windows 2000
    if ($codTipo == 2) {
        return 512;
    }

    //Microsoft Windows 2003
    if ($codTipo == 3) {

        return 512;
    }

    //Microsoft Windows 2008
    if ($codTipo == 4) {
        return 66080; //senha nao requerida
        //return 66048;
    }

    //Microsoft Windows 2012
    if ($codTipo == 5) {
        return 66080; //senha nao requerida
    }

    //Microsoft Windows 2016
    if ($codTipo == 6) {
        return 66080; //senha nao requerida
    }

    //Microsoft Windows 2018
    if ($codTipo == 7) {
        return 66080; //senha nao requerida
    }

    //Microsoft Windows 2019
    if ($codTipo == 8) {
        return 66080; //senha nao requerida
    }

    //Microsoft Windows 2021
    if ($codTipo == 9) {
        return 66080; //senha nao requerida
    }
}

function reativarPessoaHelper($CI, $codPessoa = null)
{

    $PessoasModel = new PessoasModel();
    $ServicoLDAPModel = new ServicoLDAPModel();
    $LogsModel = new LogsModel();

    $response = array();


    if ($codPessoa !== NULL) {
        $codPessoa = $codPessoa;
        if (!is_numeric($codPessoa)) {
            exit();
        }
        $pessoa = $PessoasModel->pegaPessoaPorCodPessoa($codPessoa);
    }



    if ($CI->request->getPost('codPessoa') !== NULL) {
        $codPessoa = $CI->request->getPost('codPessoa');
        $pessoa = $PessoasModel->pegaPessoaPorCodPessoa($codPessoa);
    }



    $servidoresLDAP = $ServicoLDAPModel->pegaTudoAtivo();



    foreach ($servidoresLDAP as $servidorLDAP) {

        //DEFINE CHAVE DN
        if ($servidorLDAP->atributoChave == NULL) {
            //$chaveDN = 'cn';

            $response['success'] = false;
            $response['messages'] = 'Falta definir o Atributo Chave para novos usuários do servidor LDAP ' . $servidorLDAP->descricaoServidorLDAP . "(" . $servidorLDAP->ipServidorLDAP . ")";
            return $CI->response->setJSON($response);
        } else {
            $chaveDN = $servidorLDAP->atributoChave;
        }

        $loginLDAP = $ServicoLDAPModel->conectaldap($servidorLDAP->loginLDAP, $servidorLDAP->senhaLDAP, $servidorLDAP->codServidorLDAP);
        if ($loginLDAP['status'] == 1) {
            $dadosLdapPessoa = $ServicoLDAPModel->pegaPessoas($loginLDAP['tipoldap'], $orderby = 'sn', $pessoa->conta);


            if ($loginLDAP['tipoldap'] == 1) {
                $desativarAD["useraccountcontrol"][0] = userAccountControlAtivo($servidorLDAP->codTipoMicrosoft);
                //print $dadosLdapPessoa[0]["distinguishedname"][0];
                @ldap_modify($loginLDAP['statusConexao'], $dadosLdapPessoa[0]["distinguishedname"][0], $desativarAD);
                //não tirar o @
            }

            if ($loginLDAP['tipoldap'] == 2) {

                $desativarOpenLDAP["userPassword"] = str_replace("desativado", "", $dadosLdapPessoa[0]['userpassword'][0]);

                @ldap_modify($loginLDAP['statusConexao'], $dadosLdapPessoa[0]["dn"], $desativarOpenLDAP);
                //não tirar o @
            }
        }
    }

    $fields["ativo"] = 1;
    $fields["codMotivoInativo"] = null;


    if ($PessoasModel->reativarPessoa($codPessoa, $fields)) {

        $response['success'] = true;
        $response['csrf_hash'] = csrf_hash();
        $response['messages'] = 'Pessoa reativada com sucesso';
    } else {

        $response['success'] = false;
        $response['messages'] = 'Erro na atualização!';
    }


    return $response;
}


function conectaSpedbd($servidorSpedDB, $portaSpedDB, $SpedDB, $administradorSpedDB, $senhaadministradorSpedDB)
{



    if ($conectadoSpeddb = pg_connect("host=" . $servidorSpedDB . " port=" . $portaSpedDB . " dbname=" . $SpedDB . " user=" . $administradorSpedDB . " password=" . $senhaadministradorSpedDB . " connect_timeout=3")) {
        pg_set_client_encoding($conectadoSpeddb, 'utf8');
        return true;
    } else {
        return false;
    }
}

function pessoaSPED($conta = NULL, $nomeGuerra = null, $nomeCompleto = null, $codCargo = NULL, $emailPessoal)
{

    if ($nomeGuerra !== NULL and $codCargo !== NULL and $conta !== NULL) {

        $OrganizacoesModel = new OrganizacoesModel();

        $organizacao = $OrganizacoesModel->where('codOrganizacao', session()->codOrganizacao)->first();

        if ($organizacao->servidorSpedDB !== NULL and $organizacao->servidorSPEDStatus == 1) {
            $conectadoSPEDDB = @conectaSpedbd($organizacao->servidorSpedDB, $organizacao->portaSpedDB, $organizacao->SpedDB, $organizacao->administradorSpedDB, $organizacao->senhaadministradorSpedDB);
        }


        if ($conectadoSPEDDB) {


            if ($codCargo == 17 or $codCargo == 18 or $codCargo == 22 or $codCargo == 23) {
                $codCargo = 17;
            }

            if ($codCargo == 19 or $codCargo == 20 or $codCargo == 21) {
                $codCargo = 21;
            }



            $result = pg_query("select max(id_pessoa) as maxid from pessoa");
            $row = pg_fetch_row($result);
            $maxidpessoa = $row[0];
            $newidpessoa = $maxidpessoa + 1;

            //Pega Id de Email livre
            $resultemail = pg_query("select max(id_email) as maxid from email");
            $row = pg_fetch_row($resultemail);
            $maxidemail = $row[0];
            $newidemail = $maxidemail + 1;
            /*#####      VERIFICA SE O MILITAR JÁ EXISTE  NO POSTGRESS    #####*/

            $result = pg_query("select MAX(id_pessoa) as id_pessoa from pessoa where id_pessoa in(select MAX(p.id_pessoa) as id_pessoa from pessoa p left join usuario_pessoa up on p.id_pessoa=up.id_pessoa where nm_completo= '" . mb_strtoupper($nomeCompleto, "utf-8") . "')");
            $contaexiste = pg_fetch_row($result);
            if ($contaexiste[0] > 0) {
                $atualizapessoa = "update pessoa set cd_patente = " . $codCargo . ", nm_guerra = '" . mb_strtoupper($nomeGuerra, "utf-8") . "', nm_login = '" . $conta . "' where id_pessoa in(select MAX(p.id_pessoa) as id_pessoa from pessoa p left join usuario_pessoa up on p.id_pessoa=up.id_pessoa where nm_completo= '" . mb_strtoupper($nomeCompleto, "utf-8") . "')";
                pg_query($atualizapessoa);
            } else {
                //Popula postgress com Dados da Conta do LDAP
                $inserepessoa = "insert into pessoa values (" . $newidpessoa . ", '" . $conta . "', '" . mb_strtoupper($nomeCompleto, "utf-8") . "', " . $codCargo . ", 0, 0, 10, '" . date("Y-m-d H:i:s") . "', 'n', 'n', 'n', 'n', 'n', 'n', 's', 's', 's', 's', '" . mb_strtoupper($nomeGuerra, "utf-8") . "', 'n', NULL, 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', 'n', NULL, NULL, 'n', 'n', 0);";
                $inseremail = "insert into email values (" . $newidemail . ", " . $newidpessoa . ",'" . $emailPessoal . "');";
                pg_query($inserepessoa);
                pg_query($inseremail);
            }
        }

        return true;
    }
}



function removerPessoaGrupoLDAP($CI, $codPessoa, $codGrupo)
{
    $PessoasModel = new PessoasModel();
    $GruposModel = new GruposModel();
    $ServicoLDAPModel = new ServicoLDAPModel();
    $MapeamentoAtributosLDAPModel = new MapeamentoAtributosLDAPModel();
    $pessoa = $PessoasModel->pegaPessoaPorCodPessoa($codPessoa);
    $grupo = $GruposModel->pegaPorCodigo($codGrupo);


    $servidoresLDAP = $ServicoLDAPModel->pegaTudoAtivo();


    foreach ($servidoresLDAP as $servidorLDAP) {


        //DEFINE tipo servidor Active Directory

        if ($servidorLDAP->codTipoLDAP == 1 and $servidorLDAP->codTipoMicrosoft == NULL) {
            //$chaveDN = 'cn';

            $response['success'] = false;
            $response['messages'] = 'Não é possível prosseguir. Falta definir o tipo do servidor Active Directory ' . $servidorLDAP->descricaoServidorLDAP . "(" . $servidorLDAP->ipServidorLDAP . ")";
            return $response;
        }

        //DEFINE CHAVE DN
        if ($servidorLDAP->atributoChave == NULL) {
            //$chaveDN = 'cn';

            $response['success'] = false;
            $response['messages'] = 'Não é possível prosseguir. Falta definir o Atributo Chave para novos usuários do servidor LDAP ' . $servidorLDAP->descricaoServidorLDAP . "(" . $servidorLDAP->ipServidorLDAP . ").";
            return $response;
        } else {
            $chaveDN = $servidorLDAP->atributoChave;
        }

        $atributosMapeados = $MapeamentoAtributosLDAPModel->pegaAtributosMapeados($servidorLDAP->codServidorLDAP);
        //print_r($atributosMapeados);
        $loginLDAP = array();
        $loginLDAP = $ServicoLDAPModel->conectaldap($servidorLDAP->loginLDAP, $servidorLDAP->senhaLDAP, $servidorLDAP->codServidorLDAP);
        //print_r($loginLDAP);


        if ($loginLDAP['status'] == 1) {

            $dadosLdapGrupo = $ServicoLDAPModel->pegaGrupos($loginLDAP['tipoldap'], $grupo->abreviacaoGrupo, $orderby = 'sn');

            $dadosLdapPessoa = $ServicoLDAPModel->pegaPessoas($loginLDAP['tipoldap'], $orderby = 'sn', $pessoa->conta);



            if ($loginLDAP['tipoldap'] == 1) {

                $distinguishednameMember['member'] = $dadosLdapPessoa[0]["distinguishedname"][0];
                $distinguishednameGroup = $dadosLdapGrupo[0]['distinguishedname'][0];

                //print_r($distinguishednameGroup); exit();

                if (@ldap_mod_del($loginLDAP['statusConexao'], $distinguishednameGroup, $distinguishednameMember)) {
                    return true;
                } else {
                    return false;
                }
            }

            if ($loginLDAP['tipoldap'] == 2) {

                // $userDnProxy = $dadosLdapPessoa[0]["dn"];
            }
        }
    }
}


function adicionarPessoaGrupoLDAP($CI, $codPessoa, $codGrupo)
{
    $PessoasModel = new PessoasModel();
    $GruposModel = new GruposModel();
    $ServicoLDAPModel = new ServicoLDAPModel();
    $MapeamentoAtributosLDAPModel = new MapeamentoAtributosLDAPModel();
    $pessoa = $PessoasModel->pegaPessoaPorCodPessoa($codPessoa);
    $grupo = $GruposModel->pegaPorCodigo($codGrupo);


    $servidoresLDAP = $ServicoLDAPModel->pegaTudoAtivo();


    foreach ($servidoresLDAP as $servidorLDAP) {


        //DEFINE tipo servidor Active Directory

        if ($servidorLDAP->codTipoLDAP == 1 and $servidorLDAP->codTipoMicrosoft == NULL) {
            //$chaveDN = 'cn';

            $response['success'] = false;
            $response['messages'] = 'Não é possível prosseguir. Falta definir o tipo do servidor Active Directory ' . $servidorLDAP->descricaoServidorLDAP . "(" . $servidorLDAP->ipServidorLDAP . ")";
            return $response;
        }

        //DEFINE CHAVE DN
        if ($servidorLDAP->atributoChave == NULL) {
            //$chaveDN = 'cn';

            $response['success'] = false;
            $response['messages'] = 'Não é possível prosseguir. Falta definir o Atributo Chave para novos usuários do servidor LDAP ' . $servidorLDAP->descricaoServidorLDAP . "(" . $servidorLDAP->ipServidorLDAP . ").";
            return $response;
        } else {
            $chaveDN = $servidorLDAP->atributoChave;
        }

        $atributosMapeados = $MapeamentoAtributosLDAPModel->pegaAtributosMapeados($servidorLDAP->codServidorLDAP);
        //print_r($atributosMapeados);
        $loginLDAP = array();
        $loginLDAP = $ServicoLDAPModel->conectaldap($servidorLDAP->loginLDAP, $servidorLDAP->senhaLDAP, $servidorLDAP->codServidorLDAP);
        //print_r($loginLDAP);


        if ($loginLDAP['status'] == 1) {

            $dadosLdapGrupo = $ServicoLDAPModel->pegaGrupos($loginLDAP['tipoldap'], $grupo->abreviacaoGrupo, $orderby = 'sn');


            $dadosLdapPessoa = $ServicoLDAPModel->pegaPessoas($loginLDAP['tipoldap'], $orderby = 'sn', $pessoa->conta);



            if ($loginLDAP['tipoldap'] == 1) {

                $distinguishednameMember['member'] = $dadosLdapPessoa[0]["distinguishedname"][0];
                $distinguishednameGroup = $dadosLdapGrupo[0]['distinguishedname'][0];





                if (@ldap_mod_add($loginLDAP['statusConexao'], $distinguishednameGroup, $distinguishednameMember)) {
                    return true;
                } else {
                    return false;
                }
            }

            if ($loginLDAP['tipoldap'] == 2) {

                // $userDnProxy = $dadosLdapPessoa[0]["dn"];
            }
        }
    }
}

function removerPessoaLDAP($CI, $codPessoa)
{

    $PessoasModel = new PessoasModel();
    $OrganizacoesModel = new OrganizacoesModel();
    $ServicoLDAPModel = new ServicoLDAPModel();
    $AtributosSistemaModel = new AtributosSistemaModel();
    $MapeamentoAtributosLDAPModel = new MapeamentoAtributosLDAPModel();
    $LogsModel = new LogsModel();
    $pessoas = $PessoasModel->pegaPessoaPorCodPessoa($codPessoa);

    $servidoresLDAP = $ServicoLDAPModel->pegaTudoAtivo();


    foreach ($servidoresLDAP as $servidorLDAP) {


        //DEFINE tipo servidor Active Directory

        if ($servidorLDAP->codTipoLDAP == 1 and $servidorLDAP->codTipoMicrosoft == NULL) {
            //$chaveDN = 'cn';

            $response['success'] = false;
            $response['messages'] = 'Não é possível prosseguir. Falta definir o tipo do servidor Active Directory ' . $servidorLDAP->descricaoServidorLDAP . "(" . $servidorLDAP->ipServidorLDAP . ")";
            return $response;
        }

        //DEFINE CHAVE DN
        if ($servidorLDAP->atributoChave == NULL) {
            //$chaveDN = 'cn';

            $response['success'] = false;
            $response['messages'] = 'Não é possível prosseguir. Falta definir o Atributo Chave para novos usuários do servidor LDAP ' . $servidorLDAP->descricaoServidorLDAP . "(" . $servidorLDAP->ipServidorLDAP . ").";
            return $response;
        } else {
            $chaveDN = $servidorLDAP->atributoChave;
        }

        $atributosMapeados = $MapeamentoAtributosLDAPModel->pegaAtributosMapeados($servidorLDAP->codServidorLDAP);
        //print_r($atributosMapeados);
        $loginLDAP = array();
        $loginLDAP = $ServicoLDAPModel->conectaldap($servidorLDAP->loginLDAP, $servidorLDAP->senhaLDAP, $servidorLDAP->codServidorLDAP);
        //print_r($loginLDAP);



        if ($loginLDAP['status'] == 1) {
            $dadosLdapPessoa = $ServicoLDAPModel->pegaPessoas($loginLDAP['tipoldap'], $orderby = 'sn', $pessoas->conta);




            if ($dadosLdapPessoa['count'] > 0) {

                if ($loginLDAP['tipoldap'] == 1) {

                    $distinguishedname = $dadosLdapPessoa[0]["distinguishedname"][0];
                    @removeobjetos($loginLDAP['statusConexao'], $distinguishedname);
                }

                if ($loginLDAP['tipoldap'] == 2) {

                    $userDnProxy = $dadosLdapPessoa[0]["dn"];
                    if ($dadosLdapPessoa[0]["cn"][0] == $pessoas->conta) {
                        @removeobjetos($loginLDAP['statusConexao'], $userDnProxy);
                    } elseif ($dadosLdapPessoa[0]["uid"][0] == $pessoas->conta) {
                        @removeobjetos($loginLDAP['statusConexao'], $userDnProxy);
                    }
                }
            }
        }
    }
}



function removeobjetos($ds, $dn, $recursive = false)
{
    if ($recursive == false) {
        return (ldap_delete($ds, $dn));
    } else {
        //Lista Todos os objetos nos níveis inferiores
        $sr = ldap_list($ds, $dn, "ObjectClass=*", array(""));
        $info = ldap_get_entries($ds, $sr);
        for ($i = 0; $i < $info['count']; $i++) {
            //remove recursivamente os objetos nos níveis inferiores
            $result = removeobjetos($ds, $info[$i]['dn'], $recursive);
            //print $dn;
            if (!$result) {
                //Retorno em caso de falha
                return ($result);
                //print $dn;
            }
        }
        //print $dn;
        return (ldap_delete($ds, $dn));
    }
}

function exportarPessoaHelper($CI, $codPessoa = NULL, $codServidorLDAP = NULL, $relatorioFinal = 1)
{

    $PessoasModel = new PessoasModel();
    $OrganizacoesModel = new OrganizacoesModel();
    $ServicoLDAPModel = new ServicoLDAPModel();
    $AtributosSistemaModel = new AtributosSistemaModel();
    $MapeamentoAtributosLDAPModel = new MapeamentoAtributosLDAPModel();
    $LogsModel = new LogsModel();

    if (session()->codPessoa !== NULL) {
        $LogsModel->inserirLog('Início da Exportação dos usuários', session()->codPessoa);
    }


    if ($codPessoa !== NULL) {
        $codPessoa = $codPessoa;
        if (!is_numeric($codPessoa)) {
            $response['success'] = false;
            $response['messages'] = 'Deve ser informado um número';
            return $response;
        }
        $pessoas = array($PessoasModel->pegaPessoaPorCodPessoa($codPessoa));
        $Organizacoes = $PessoasModel->organizacaoPessoa($codPessoa);
    }

    //não mudar, pois os formulários de add enviam codPessoa igua  a NULL
    //por isso, quando $codPessoa vier NULL, getPost('codPessoa') vem do ajax
    if ($codPessoa == NULL) {
        if ($CI->request->getPost('codPessoa') !== NULL) {
            $codPessoa = $CI->request->getPost('codPessoa');
            $pessoas = array($PessoasModel->pegaPessoaPorCodPessoa($codPessoa));
            $Organizacoes = $PessoasModel->organizacaoPessoa($codPessoa);
        }
    }


    ###############     INTEGRAÇÃO SPED    #################

    if ($Organizacoes->servidorSPEDStatus == 1) {
        @pessoaSPED($pessoas[0]->conta, $pessoas[0]->nomePrincipal, $pessoas[0]->nomeCompleto, $pessoas[0]->codCargo, $pessoas[0]->emailPessoal);
    }

    if ($codPessoa == -1 or $CI->request->getPost('codPessoa') == -1) {
        $pessoas = $PessoasModel->pegaTudo();
        $Organizacoes = $OrganizacoesModel->pegaOrganizacao(session()->codOrganizacao);
    }




    if ($codServidorLDAP !== NULL) {
        $codServidorLDAP = $codServidorLDAP;
    }

    if ($CI->request->getPost('codServidorLDAP') !== NULL) {
        $codServidorLDAP = $CI->request->getPost('codServidorLDAP');
    }



    if ($codServidorLDAP !== NULL) {
        $servidoresLDAP = array($ServicoLDAPModel->pegaPorCodigo($codServidorLDAP));
    } else {
        $servidoresLDAP = $ServicoLDAPModel->pegaTudoAtivo();
    }




    if ($servidoresLDAP == NULL) {


        $response['success'] = true;
        $response['csrf_hash'] = csrf_hash();
        $response['messages'] = 'Nenhum servidor Active Directory configurado';
        return $response;
    }



    $statusTrocaSenha = "";
    $statusGeral = "<i class='fas fa-check text-success'></i>Sucesso";
    $relatorio = array();
    $hoveFalha = 0;
    $hovesucesso = 0;
    $$qtdPessoasInserida = 0;
    $sambaNTPassword = 0;
    $sambaLMPassword = 0;
    foreach ($servidoresLDAP as $servidorLDAP) {

        $qtdPessoasAtualizadas = 0;

        //DEFINE tipo servidor Active Directory

        if ($servidorLDAP->codTipoLDAP == 1 and $servidorLDAP->codTipoMicrosoft == NULL) {
            //$chaveDN = 'cn';

            $response['success'] = false;
            $response['messages'] = 'Não é possível prosseguir. Falta definir o tipo do servidor Active Directory ' . $servidorLDAP->descricaoServidorLDAP . "(" . $servidorLDAP->ipServidorLDAP . ")";
            return $response;
        }

        //DEFINE CHAVE DN
        if ($servidorLDAP->atributoChave == NULL) {
            //$chaveDN = 'cn';

            $response['success'] = false;
            $response['messages'] = 'Não é possível prosseguir. Falta definir o Atributo Chave para novos usuários do servidor LDAP ' . $servidorLDAP->descricaoServidorLDAP . "(" . $servidorLDAP->ipServidorLDAP . ").";
            return $response;
        } else {
            $chaveDN = $servidorLDAP->atributoChave;
        }

        $atributosMapeados = $MapeamentoAtributosLDAPModel->pegaAtributosMapeados($servidorLDAP->codServidorLDAP);
        //print_r($atributosMapeados);
        $loginLDAP = array();
        $loginLDAP = $ServicoLDAPModel->conectaldap($servidorLDAP->loginLDAP, $servidorLDAP->senhaLDAP, $servidorLDAP->codServidorLDAP);
        //print_r($loginLDAP);

        $objectclassAdicionais =  $ServicoLDAPModel->objectclassAdicionais($servidorLDAP->codServidorLDAP);


        if ($loginLDAP['status'] == 1) {
            $statusGeral = "<i class='fas fa-check text-success'></i>Sucesso";

            //Contas que não entram na exportação
            $contasProtegidas = array("admin", "administrator", "administrador", "sisadmin", "root", "", null, "monitor glpi", "rootrepl rep", "rootrepl", "ldaper", "admin_sped", "admins");                //adiciona conta de acesso ao LDAP

            foreach ($pessoas as $pessoa) {


                array_push($contasProtegidas, $servidorLDAP->loginLDAP);

                if (!in_array($pessoa->conta, $contasProtegidas)) {


                    $dadosAtualizacaoTipo1 = array();
                    $dadosInsercaoTipo1 = array();
                    $dadosAtualizacaoTipo2 = array();
                    $dadosInsercaoTipo2 = array();
                    $dadosLdapPessoa = $ServicoLDAPModel->pegaPessoas($loginLDAP['tipoldap'], $orderby = 'sn', $pessoa->conta);






                    if ($dadosLdapPessoa['count'] > 0) {
                        $qtdPessoasAtualizadas++;
                        //USUÁRIO EXISTE - UPDATE
                        if ($loginLDAP['tipoldap'] == 1) {
                            $atributosAtualizadosTipo1 = 0;
                            $atributosNovosTipo1 = 0;

                            foreach ($atributosMapeados as $atributosTipo1) {


                                $arrayPessoa = json_decode(json_encode($pessoa), true);

                                if ($atributosTipo1->nomeAtributoSistema !== 'conta') {

                                    if ($dadosLdapPessoa[0][$atributosTipo1->nomeAtributoLDAP][0] !== NULL) {
                                        if ($arrayPessoa[$atributosTipo1->nomeAtributoSistema] !== NULL and $arrayPessoa[$atributosTipo1->nomeAtributoSistema] !== ""  and $atributosTipo1->nomeAtributoLDAP !== $chaveDN) {
                                            $dadosAtualizacaoTipo1[$atributosTipo1->nomeAtributoLDAP] = $arrayPessoa[$atributosTipo1->nomeAtributoSistema];
                                            $atributosAtualizadosTipo1++;
                                        }
                                    } else {
                                        if ($arrayPessoa[$atributosTipo1->nomeAtributoSistema] !== NULL and $arrayPessoa[$atributosTipo1->nomeAtributoSistema] !== "" and $atributosTipo1->nomeAtributoLDAP !== $chaveDN) {
                                            $dadosInsercaoTipo1[$atributosTipo1->nomeAtributoLDAP] = $arrayPessoa[$atributosTipo1->nomeAtributoSistema];
                                            $atributosNovosTipo1++;
                                        }
                                    }
                                }
                            }
                            if ($atributosNovosTipo1 > 0) {

                                //unset($dadosInsercaoTipo1[$chaveDN]); //REMOVE A CHAVE DO OBJETO PARA NÃO VAVER CONFLITO
                                if (@ldap_modify($loginLDAP['statusConexao'], $dadosLdapPessoa[0]["distinguishedname"][0], $dadosInsercaoTipo1)) {
                                    $hovesucesso++;
                                    $LogsModel->inserirLogLDAP($loginLDAP['statusConexao'], $dadosInsercaoTipo1, $servidorLDAP->codServidorLDAP, 1, $dadosLdapPessoa[0]["distinguishedname"][0], 'Inserido: ');
                                } else {
                                    $LogsModel->inserirLogLDAP($loginLDAP['statusConexao'], $dadosInsercaoTipo1, $servidorLDAP->codServidorLDAP, 0, $dadosLdapPessoa[0]["distinguishedname"][0], 'Erro ao inserir novo atributo: ');
                                }
                            }
                            if ($atributosAtualizadosTipo1 > 0) {


                                if (@ldap_modify($loginLDAP['statusConexao'], $dadosLdapPessoa[0]["distinguishedname"][0], $dadosAtualizacaoTipo1)) {
                                    $hovesucesso++;
                                    $LogsModel->inserirLogLDAP($loginLDAP['statusConexao'], $dadosInsercaoTipo1, $servidorLDAP->codServidorLDAP, 1, $dadosLdapPessoa[0]["distinguishedname"][0], 'Atualizado: ');


                                    //OPERAÇÃO SEPARADA PARA ATUALIZAR A SENHA SEM ATRAPALHAR A ATUALIZAÇÃO DOS OUTROS ATRIBUTOS
                                    //ACTIVE DIRECTORY EXIGE SSL
                                    if ($pessoa->senhaResincLDAP !== NULL) {
                                        $senhaResincLDAP  = descriptar($Organizacoes->chaveSalgada, 'des', $pessoa->senhaResincLDAP);
                                        //$senhaResincLDAP = 'hospital@00';
                                        $dadosSenhaAD["unicodePwd"] = iconv("UTF-8", "UTF-16LE", '"' . $senhaResincLDAP . '"');
                                        //Tentativa de sincronizar a senha
                                        if (@ldap_modify($loginLDAP['statusConexao'], $dadosLdapPessoa[0]["distinguishedname"][0], $dadosSenhaAD)) {
                                        } else {
                                            $LogsModel->inserirLogLDAP($loginLDAP['statusConexao'], array(), $servidorLDAP->codServidorLDAP, 0, $dadosLdapPessoa[0]["distinguishedname"][0], 'Erro ao atualizar a senha: ');
                                        }
                                    }
                                } else {
                                    $LogsModel->inserirLogLDAP($loginLDAP['statusConexao'], $dadosAtualizacaoTipo1, $servidorLDAP->codServidorLDAP, 0, $dadosLdapPessoa[0]["distinguishedname"][0], 'Erro ao atualizar atributo: ');
                                }
                            }

                            //MODIFICAR A PESSOA DE DEPARTAMENTO

                            $dadosDepartamento = $PessoasModel->pegaDepartamentoPessoa($pessoa->conta);
                            $dadosLdapDepartamento = $ServicoLDAPModel->pegaUnidadesOrganizacionais($loginLDAP['tipoldap'], $dadosDepartamento->descricaoDepartamento);
                            $cn = $dadosLdapPessoa[0]["cn"][0];
                            @ldap_rename($loginLDAP['statusConexao'], $dadosLdapPessoa[0]["distinguishedname"][0], 'cn=' . $cn, $dadosLdapDepartamento[0]["distinguishedname"][0], TRUE);
                        }

                        if ($loginLDAP['tipoldap'] == 2) {
                            $atributosAtualizadosTipo2 = 0;
                            $atributosNovosTipo2 = 0;
                            $sambaNTPassword = 0;
                            $sambaLMPassword = 0;
                            foreach ($atributosMapeados as $atributos) {


                                $arrayPessoa = json_decode(json_encode($pessoa), true);


                                if ($atributosTipo1->nomeAtributoSistema !== 'conta') {

                                    if ($dadosLdapPessoa[0][$atributos->nomeAtributoLDAP][0] !== NULL) {
                                        if ($arrayPessoa[$atributos->nomeAtributoSistema] !== NULL and $arrayPessoa[$atributos->nomeAtributoSistema] !== "" and $atributos->nomeAtributoLDAP !== $chaveDN) {
                                            $dadosAtualizacaoTipo2[$atributos->nomeAtributoLDAP] = $arrayPessoa[$atributos->nomeAtributoSistema];
                                            $atributosAtualizadosTipo2++;
                                        }
                                    } else {

                                        if ($arrayPessoa[$atributos->nomeAtributoSistema] !== NULL and $arrayPessoa[$atributos->nomeAtributoSistema] !== "" and $atributos->nomeAtributoLDAP !== $chaveDN) {
                                            $dadosInsercaoTipo2[$atributos->nomeAtributoLDAP] = $arrayPessoa[$atributos->nomeAtributoSistema];
                                            $atributosNovosTipo2++;
                                        }
                                    }



                                    if ($servidorLDAP->codTipoMicrosoft == 10) {

                                        if ($dadosLdapPessoa[0]['sambantpassword'][0] !== NULL) {
                                            $sambaNTPassword = 1;
                                        }
                                        if ($dadosLdapPessoa[0]['sambalmpassword'][0] !== NULL) {
                                            $sambaLMPassword = 1;
                                        }
                                    }
                                }
                            }
                            if ($atributosNovosTipo2 > 0) {
                                //unset($dadosInsercaoTipo2[$chaveDN]);
                                if (@ldap_modify($loginLDAP['statusConexao'], $dadosLdapPessoa[0]["dn"], $dadosInsercaoTipo2)) {
                                    $hovesucesso++;
                                    $LogsModel->inserirLogLDAP($loginLDAP['statusConexao'], $dadosInsercaoTipo2, $servidorLDAP->codServidorLDAP, 1, $dadosLdapPessoa[0]["dn"], 'Novo atributo: ');
                                } else {
                                    $LogsModel->inserirLogLDAP($loginLDAP['statusConexao'], $dadosInsercaoTipo2, $servidorLDAP->codServidorLDAP, 0, $dadosLdapPessoa[0]["dn"], 'Erro ao inserir novo atributo: ');
                                }
                            }
                            if ($atributosAtualizadosTipo2 > 0) {



                                if (@ldap_modify($loginLDAP['statusConexao'], $dadosLdapPessoa[0]["dn"], $dadosAtualizacaoTipo2)) {
                                    $hovesucesso++;
                                    $LogsModel->inserirLogLDAP($loginLDAP['statusConexao'], $dadosInsercaoTipo2, $servidorLDAP->codServidorLDAP, 1, $dadosLdapPessoa[0]["dn"], 'Atualização atributo: ');


                                    //se o atributo userpassword existir atualiza senha

                                    if ($pessoa->senhaResincLDAP !== NULL) {

                                        $senhaResincLDAP  = descriptar($Organizacoes->chaveSalgada, 'des', $pessoa->senhaResincLDAP);
                                        $dadosSenhaOpenLDAP["userPassword"] = "{MD5}" . base64_encode(pack("H*", md5($senhaResincLDAP)));


                                        if ($servidorLDAP->codTipoMicrosoft == 10) {
                                            if ($sambaNTPassword == 1) {

                                                $dadosSenhaOpenLDAP["sambaNTPassword"] = ntpasswd($senhaResincLDAP);
                                            }

                                            if ($sambaLMPassword == 1) {

                                                $dadosSenhaOpenLDAP["sambaLMPassword"] = ntpasswd($senhaResincLDAP);
                                            }
                                        }

                                        if (@ldap_modify($loginLDAP['statusConexao'], $dadosLdapPessoa[0]["dn"], $dadosSenhaOpenLDAP)) {
                                        } else {
                                            $LogsModel->inserirLogLDAP($loginLDAP['statusConexao'], array(), $servidorLDAP->codServidorLDAP, 0, $dadosLdapPessoa[0]["dn"], 'Erro ao atualizar a senha: ');
                                        }
                                    }
                                } else {
                                    $LogsModel->inserirLogLDAP($loginLDAP['statusConexao'], $dadosAtualizacaoTipo2, $servidorLDAP->codServidorLDAP, 0, $dadosLdapPessoa[0]["dn"], 'Erro ao atualizar atributo: ');
                                }
                            }
                        }
                    }

                    if ($dadosLdapPessoa['count'] == 0) {
                        //USUÁRIO NÃO EXISTE - INSERT
                        //print 'inserir';



                        if ($loginLDAP['tipoldap'] == 1) {
                            //ACTIVE DIRECORY

                            //$dn = 'cn=' . $pessoa->conta . ',' . $servidorLDAP->dn;

                            if ($servidorLDAP->dnNovosUsuarios == NULL) {
                                $dn = $chaveDN . "=" . $pessoa->conta . "," . $servidorLDAP->dn;
                            } else {
                                $dn = $chaveDN . "=" . $pessoa->conta . "," . $servidorLDAP->dnNovosUsuarios;
                            }


                            $dados["cn"][0] = $pessoa->conta; //.'@'.$dominiofqdn;
                            $dados["instancetype"][0] = 4;
                            $dados["samaccountname"][0] = $pessoa->conta;;
                            $dados["objectclass"][0] = "top";
                            $dados["objectclass"][1] = "person";
                            $dados["objectclass"][2] = "organizationalPerson";
                            $dados["objectclass"][3] = "user";
                            $dados["displayname"][0] = $pessoa->conta;
                            $dados["givenname"][0] = $pessoa->conta;
                            $dados["userprincipalname"][0] = $pessoa->conta;

                            //$dados["sn"][0] = $pessoa->conta;;
                            // $dados["mail"][0] = $pessoa->conta;


                            //VERIFICA SE A CONTA É DESATIVADA/ATIVADA


                            //desativa conta que já se encontra desativada
                            if ($pessoa->ativo == 0) {
                                $dados["UserAccountControl"] = 546;
                            } else {

                                $dados["UserAccountControl"] = userAccountControlAtivo($servidorLDAP->codTipoMicrosoft);
                            }


                            //VERIFICA SENHA PADRÃO DA CONTA CRIADA

                            if ($Organizacoes->ativarSenhaPadrao == 1) {
                                $userdata["unicodePwd"] = iconv("UTF-8", "UTF-16LE", '"' . $Organizacoes->senhaPadrao . '"');
                            }


                            if ($pessoa->senhaResincLDAP !== NULL) {
                                $chave = $pessoa->chaveSalgada;
                                $tipo_cifra = 'des';
                                $senhaResincLDAP = descriptar($chave, $tipo_cifra, $pessoa->senhaResincLDAP);
                                $userdata["unicodePwd"] = iconv("UTF-8", "UTF-16LE", '"' . $senhaResincLDAP . '"');
                            }
                        }



                        if ($loginLDAP['tipoldap'] == 2) {


                            //OPEN LDAP


                            if ($servidorLDAP->dnNovosUsuarios == NULL) {
                                $dn = $chaveDN . '=' . $pessoa->conta . ',' . $servidorLDAP->dn;
                            } else {
                                $dn = $chaveDN . '=' . $pessoa->conta . ',' . $servidorLDAP->dnNovosUsuarios;
                            }
                            $dados['cn'] = $pessoa->conta;
                            $dados["objectClass"][0] = "top";
                            $dados["objectClass"][1] = "person";
                            $dados["objectClass"][2] = "inetOrgPerson";

                            if ($servidorLDAP->codTipoMicrosoft == 10) {


                                $r = 3;
                                foreach ($objectclassAdicionais as $objectClass) {

                                    $dados["objectClass"][$r] = $objectClass->descricaoObjectClass;
                                    $r++;
                                }


                                //PROXIMO ID
                                $contasExistentes =  $ServicoLDAPModel->pegaPessoas($loginLDAP['tipoldap'], $orderby = 'sn', null);
                                $uidNumber =  (int)$contasExistentes['count'] + (int)5000;

                                //$dados["st"] = 'PB';
                                //$dados["gecos"] = "xxxx";
                                $dados["gidNumber"] = 1000;
                                $dados["uidNumber"] = $uidNumber;
                                $dados["sambaSID"] = $servidorLDAP->sambaSID . $pessoa->cpf;
                                $dados["homeDirectory"] = "/home/" . $pessoa->conta;
                                $dados["krbPrincipalName"] = $pessoa->conta . "@" . $servidorLDAP->fqdn;
                                $dados["krbTicketFlags"] = "128";
                                //$dados["l"] = "JP";
                                $dados["loginShell"] = "/bin/bashuid: " . $pessoa->conta;
                                $dados["sambaAcctFlags"] = "[U]";
                                $dados["sambaHomePath"] = $servidorLDAP->servidorArquivo . $pessoa->conta;
                                $dados["sambaKickoffTime"] = "2147483647";
                                $dados["sambaLogoffTime"] = "2147483647";
                                $dados["businessCategory"] = "21";
                                $dados["sambaLogonTime"] = "0";
                                $dados["sambaNTPassword"] = ntpasswd($Organizacoes->senhaPadrao);
                                $dados["sambaLMPassword"] = ntpasswd($Organizacoes->senhaPadrao);
                                $dados["sambaPwdCanChange"] = "0";
                                $dados["shadowWarning"] = "7";
                                //$dados["title"] = "CAP";
                                //$dados["postalCode"] = "343242432423";
                                //$dados["ou"] = "ALMOX";
                                //$dados["sambaProfilePath"] = "\\'. $servidorLDAP->servidorArquivo.'\'.$pessoa->conta.'\profile";
                                //$dados["street"] = "rua teste";
                            }

                            //VERIFICA SENHA PADRÃO DA CONTA CRIADA
                            if ($Organizacoes->ativarSenhaPadrao == 1) {
                                $dados["userpassword"] = "{MD5}" . base64_encode(pack("H*", md5($Organizacoes->senhaPadrao)));
                            }

                            //VERIFICA SE SENHA JÁ HAVIA SIDO DEFINIDA, SE SIM, REPLACE ATRIBUT
                            if ($pessoa->senhaResincLDAP !== NULL) {
                                $chave = $pessoa->chaveSalgada;
                                $tipo_cifra = 'des';
                                $senhaResincLDAP = descriptar($chave, $tipo_cifra, $pessoa->senhaResincLDAP);
                                $dados["userpassword"] = "{MD5}" . base64_encode(pack("H*", md5($senhaResincLDAP)));
                            }
                        }
                        //print_r($atributosMapeados); exit();
                        //MAPEAMENTO DE ATRIBUTOS
                        foreach ($atributosMapeados as $atributos) {

                            foreach ($pessoa as $key => $row) {
                                if ($key == $atributos->nomeAtributoSistema and $row !== NULL  and $row !== "") {
                                    if (in_array($atributos->nomeAtributoSistema, array('identidade', 'cnpj', 'cpf', 'telefoneTrabalho', 'celular'))) {
                                        $caracteresRemover = array("(", ")", "-", "_", ":", ".", "/", " ");
                                        $dados[$atributos->nomeAtributoLDAP] = str_replace($caracteresRemover, "", $row);
                                    } else {

                                        $dados[$atributos->nomeAtributoLDAP] = $row;
                                    }
                                }
                            }
                        }
                        //unset($dados('distinguishedname'));
                        //$dados[$atributos->nomeAtributoLDAP] = 111;
                        //print_r($dados);


                        if (@ldap_add($loginLDAP['statusConexao'], $dn, $dados)) {
                            $qtdPessoasInseridas++;
                            $hovesucesso++;

                            $LogsModel->inserirLogLDAP($loginLDAP['statusConexao'], $dadosInsercaoTipo1, $servidorLDAP->codServidorLDAP, 1, $dadosLdapPessoa[0]["distinguishedname"][0], 'Novo Usuário: ');
                        } else {

                            $LogsModel->inserirLogLDAP($loginLDAP['statusConexao'], $dadosInsercaoTipo1, $servidorLDAP->codServidorLDAP, 0, $dn, 'Falha ao inserir novo Usuário: ');
                        }
                    }
                }
            }
        } else {
            //FALHA NA CONEXAO
            $hoveFalha++;
            $statusGeral = '<i class="fas fa-times text-danger"> Falha conexão</i>';
        }

        if ($hovesucesso  == 0) {
            $statusGeral = '<i class="fas fa-times text-danger"> Falha na sincronização. Verifique o log</i>';
        }

        $dadosRelatorio = array(
            $servidorLDAP->descricaoServidorLDAP,
            $qtdPessoasInseridas,
            $qtdPessoasAtualizadas,
            $statusGeral,
        );
        array_push($relatorio, $dadosRelatorio);
    }


    if ($codPessoa == -1 or $CI->request->getPost('codPessoa') == -1) {

        if ($hovesucesso >= 1) {
            $response['success'] = true;
            $response['csrf_hash'] = csrf_hash();
            $response['messages'] = '<div style="margin-bottom:10px;font-weight:bold;font-size:20px">Sincronização realiziada com sucesso</div>';
        }


        if ($hoveFalha == 0 and $hovesucesso >= 1) {
            $response['success'] = true;
            $response['csrf_hash'] = csrf_hash();
            $response['messages'] = '<div style="margin-bottom:10px;font-weight:bold;font-size:20px">Sincronização realiziada com sucesso</div>';
        }

        if ($hovesucesso  == 0) {
            $response['success'] = false;
            $response['messages'] = '<div style="margin-bottom:10px;font-weight:bold;font-size:20px">Falha na Sincronização</div>';
        }


        if ($hoveFalha >= 1 and $hovesucesso >= 1) {
            $response['success'] = 'parcial';
            $response['messages'] = '<div style="margin-bottom:10px;font-weight:bold;font-size:20px">Sincronização parcialmente completa</div>';
        }


        $response['messages'] .= '<div class="justify-content-center row">
        <table class="table table-striped table-sm table-hover" style="font-size:14px" border=1>
        <tr style="font-size:16px; font-weight: bold;" >
        <td>Servidor</td>
        <td>Inseridas</td>
        <td>Atualizadas</td>
        <td>Status Geral</td>
        </tr>';
        foreach ($relatorio as $row) {
            $response['messages'] .= '
            
                    <tr>
                        <td>' .  $row[0] . '</td>
                        <td>' . $row[1] . '</td>
                        <td>' . $row[2] . '</td>
                        <td>' . $row[3] . '</td>
                    </tr>
               
             ';
        }
        $response['messages'] .= ' </table>
        <button type="button" class="btn btn-xs btn-primary" onclick="mostraLogs()" title="Logs"> <i class="fas fa-exclamation-triangle"></i> Logs</button>
        </div>';
    } else {

        if ($hovesucesso >= 1) {
            $response['success'] = true;
            $response['csrf_hash'] = csrf_hash();
            $response['messages'] = '<div style="margin-bottom:10px;font-weight:bold;font-size:20px">Sincronização realiziada com sucesso</div>';
        }


        if ($hoveFalha == 0 and $hovesucesso >= 1) {
            $response['success'] = true;
            $response['csrf_hash'] = csrf_hash();
            $response['messages'] = '<div style="margin-bottom:10px;font-weight:bold;font-size:20px">Sincronização realiziada com sucesso</div>';
        }

        if ($hovesucesso  == 0) {
            $response['success'] = false;
            $response['messages'] = '<div style="margin-bottom:10px;font-weight:bold;font-size:20px">Falha na Sincronização</div>';
        }


        if ($hoveFalha >= 1 and $hovesucesso >= 1) {
            $response['success'] = 'parcial';
            $response['messages'] = '<div style="margin-bottom:10px;font-weight:bold;font-size:20px">Sincronização parcialmente completa</div>';
        }

        $response['messages'] .= '<div class="justify-content-center row">
        <table class="table table-striped table-sm table-hover" style="font-size:14px" border=1>
        <tr style="font-size:16px; font-weight: bold;" >
        <td>Servidor</td>
        <td>Status Geral</td>
        </tr>';
        foreach ($relatorio as $row) {
            $response['messages'] .= '
            
                    <tr>
                        <td>' .  $row[0] . '</td>
                        <td>' . $row[3] . '</td>
                    </tr>
               
             ';
        }
        $response['messages'] .= ' </table>
        <button type="button" class="btn btn-xs btn-primary" onclick="mostraLogs()" title="Logs"> <i class="fas fa-exclamation-triangle"></i> Logs</button>
        </div>';
    }

    $LogsModel->inserirLog('Encerramento da Exportação dos usuários', session()->codPessoa);

    return $response;
}


function ntpasswd($Input)
{
    // Convert the password from UTF8 to UTF16 (little endian)
    $Input = iconv('UTF-8', 'UTF-16LE', $Input);

    $MD4Hash = hash('md4', $Input);

    // Make it uppercase, not necessary, but it's common to do so with NTLM hashes
    $NTLMHash = strtoupper($MD4Hash);

    // Return the result
    return ($NTLMHash);
}

function checknt($passwd, $hash)
{
    return (ntpasswd($passwd) === strtoupper($hash));
}


function pegaIP()
{
    //pega IP
    $request = \Config\Services::request();
    $ip = $request->getIPAddress();
    return $ip;
}

function geraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false)
{
    $lmin = 'abcdefghijklmnopqrstuvwxyz';
    $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $num = '1234567890';
    $simb = '!@#$%';
    $retorno = '';
    $caracteres = '';

    $caracteres .= $lmin;
    if ($maiusculas)
        $caracteres .= $lmai;
    if ($numeros)
        $caracteres .= $num;
    if ($simbolos)
        $caracteres .= $simb;

    $len = strlen($caracteres);
    for ($n = 1; $n <= $tamanho; $n++) {
        $rand = mt_rand(1, $len);
        $retorno .= $caracteres[$rand - 1];
    }
    return $retorno;
}

function geraNumero($tamanho = 8)
{
    $caracteres = '1234567890';
    $retorno = '';

    $len = strlen($caracteres);
    for ($n = 1; $n <= $tamanho; $n++) {
        $rand = mt_rand(1, $len);
        $retorno .= $caracteres[$rand - 1];
    }
    return $retorno;
}



?>