<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Python extends Controller
{
    public static function callScript( $file, $parameters )
    {
        $fileLocation = __DIR__ . '/../../Python/'. $file;
        $fileLocation .= str_ends_with( $file, '.py' ) ? '' : '.py';
        $command = escapeshellcmd( '/usr/bin/python3 ' . $fileLocation . ' ' . escapeshellarg( json_encode( $parameters ) ) );
        $output = shell_exec( $command );
        return $output;
    }
}
