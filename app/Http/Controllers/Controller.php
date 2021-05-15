<?php

namespace App\Http\Controllers;

use App\Models\AuxiliaryModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    //Use for query
    protected $auxiliaryModel;

    function __construct()
    {
        $this->auxiliaryModel = new AuxiliaryModel();
    }
}
