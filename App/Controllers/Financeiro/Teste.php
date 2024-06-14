<?php

namespace App\Controllers\Financeiro;

use App\Controllers\BaseController;
use App\Models\OrganizacoesModel;

class teste extends BaseController
{
    public function __construct()
    {

        $this->OrganizacoesModel = new OrganizacoesModel();
        $this->validation =  \Config\Services::validation();
    }

    public function index()
    {
        $tudo = $this->OrganizacoesModel->pegaTudo();
        print_r($tudo);
    }
     public function trasMarmita()
    {
        $tudo = $this->OrganizacoesModel->pegaTudo();
        print_r($tudo);
    }
}
