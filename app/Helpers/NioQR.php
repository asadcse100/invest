<?php

namespace App\Helpers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class NioQR
{
    /**
     * @param string $qrcode
     * @param int $size
     * 
     * @return object|boolean
     */
    public static function generate($qrcode = null, $size = null)
    {
        $def  = 125;
        $size = (!empty($size)) ? (int) $size : $def;

        if ($qrcode) {
            $qrsize = ($size) ? $size : $def;
            return QrCode::size($qrsize)->generate($qrcode);
        }

        return '';
    }
}