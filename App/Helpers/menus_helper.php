<?php

use App\Models\ModulosModel as Modulosmodel;
use App\Models\Organizacoesmodel as Organizacoesmodel;
use App\Models\RelatoriosModel  as RelatoriosModel ;


function temFilho($rows, $id)
{
    foreach ($rows as $row) {
        if ($row->pai == $id) {
            return true;
        }
    }
    return false;
}
function constroiMenus($rows, $parent = 0)
{


?>
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <?php

            function verificaMenu($rows, $parent = 0)
            {
                foreach ($rows as $row) {
                    $result = '<li class="nav nav-treeview">';
                    if ($row->pai == $parent) {
                        $result .= '<ul class="nav-item">';

            ?>

                        <a href="<?php echo base_url() . "/" . $row->link ?>" class="nav-link">
                            <i class="<?php echo $row->icone ?>"></i>
                            <p>
                                <?php echo $row->nome ?>
                            </p>
                        </a>
            <?php
                        if (temFilho($rows, $row->id)) {
                            $result .= constroiMenus($rows, $row->id);
                        }
                        $result .= "</ul>";
                    }
                    $result .= "</li>";
                }
                return $result;
            }
            ?>
        </ul>
    <?php
}

function menuPermissoes($CI = null)
{


    ?>
        <nav class="navbar navbar-expand navbar-primary navbar-dark">
            <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link">Notificações</a>
                        <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow" style="left: 0px; right: inherit;">
                            <li><a href="#" class="dropdown-item">Servidor SMTP (E-mail)</a></li>
                            <li class="dropdown-divider"></li>
                            <li><a href="#" class="dropdown-item">Testes</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link">Integração de usuários</a>
                        <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow" style="left: 0px; right: inherit;">
                            <li><a href="#" class="dropdown-item">Administrar Servidores LDAP</a></li>
                            <li class="dropdown-divider"></li>
                            <li><a href="#" class="dropdown-item">Autoregistro por E-mail</a></li>
                        </ul>
                    </li>
                </ul>

            </div>
        </nav>
    <?php

}

function menusRelatorios($CI = null)
{
    $CI->RelatoriosModel  = new RelatoriosModel ;
    $Relatorios_raiz = $CI->RelatoriosModel->pegaRelatoriosRaiz();
    $Relatorios_filho = $CI->RelatoriosModel->pegaRelatoriosFilho();

    ?>
        <nav class="navbar navbar-expand navbar-primary navbar-dark">
            <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                <ul class="navbar-nav">


                    <?php

                    foreach ($Relatorios_raiz as $RelatorioRaiz) {
                    ?>
                        <li class="nav-item dropdown">
                            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link"><?php echo $RelatorioRaiz->nome ?></a>
                            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow" style="left: 0px; right: inherit;">

                                <?php
                                $qtde_relatorios = 0;
                                foreach ($Relatorios_filho as $Relatorio_filho) {
                                    if ($Relatorio_filho->pai == $RelatorioRaiz->id) {
                                        $qtde_relatorios++;
                                ?>
                                        <li><div onclick="<?php echo $Relatorio_filho->link ?>()" class="dropdown-item"><?php echo $Relatorio_filho->nome ?></div></li>
                                    <?php
                                    }
                                }

                                if ($qtde_relatorios == 0) {
                                    ?>
                                    <li style="width:210px">nenhum relatório encontrado</li>
                                <?php
                                }
                                ?>

                            </ul>
                        </li>

                    <?php
                    }


                    ?>



                    <li class="nav-item">
                        <a href="#" aria-haspopup="true" aria-expanded="false" class="nav-link">Logs de acesso</a>
                        <ul aria-labelledby="dropdownSubMenu1" class=" border-0 shadow" style="left: 0px; right: inherit;">
                        </ul>
                    </li>
                </ul>

            </div>
        </nav>

    <?php
}

    ?>