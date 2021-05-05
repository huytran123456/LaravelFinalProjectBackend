<?php

namespace App\Http\Controllers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    //
    public function getQrCode()
    {
        return QrCode::size(300)->generate('Mooogg');
    }
}
