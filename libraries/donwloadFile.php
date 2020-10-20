<?php

$data = $_GET['src'];

if ( $data ) {
    $source = json_decode(base64_decode($data));
    $op = $source->tpo;
    switch ($op) {
        case 'ATC': //  Archivos de tema de conflictos
            $path = $_SERVER['DOCUMENT_ROOT'] . "/media/ecorae/conflictos/" . $source->idTema . "/archivos/" . $source->name; 
            break;
        case 'AAT': //  Archivos de actor de un tema de conflictos
            $path = $_SERVER['DOCUMENT_ROOT'] . "/media/ecorae/conflictos/" . $source->idTema . "/actorTema/" . $source->idActor . "/" . $source->name; 
            break;
        case '': //  Archivos de tema de conflictos
            
            break;
        default:
            $path = '';
            break;
    }
    
    if(file_exists($path)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($path));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($path));
        ob_clean();
        flush();
        readfile($path);
    }
    exit;
    
}


