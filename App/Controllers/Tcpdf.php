<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Libraries\Tpdf;

class Tcpdf extends BaseController
{

    function __construct()
    {
    }


    function index()
    {
        imprimir();
    }
}
