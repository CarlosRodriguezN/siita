<?php

define('DS', DIRECTORY_SEPARATOR);
define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);

require_once JPATH_BASE . DS . 'includes'. DS .'ecoraeFramework.php';

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

//  import joomlas filesystem functions, we will do all the filewriting with joomlas functions,
//  so if the ftp layer is on, joomla will write with that, not the apache user, which might
//  not have the correct permissions

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

require_once JPATH_BASE . DS . 'components' . DS . 'com_programa'. DS .'models'. DS .'programa.php';

//  Instancia del Modelo Programa en el componente com_programa
$modelo = new ProgramaModelPrograma();
//  $info = JRequest::getVar( 'jform' );

//  $modelo->saveFromJSON( $info["dataPrograma"] );

if( !empty( $_FILES ) ){
    $modelo->saveImage( $_FILES );
}else{
    echo 'no vino nada !!!!!!!!!!!';
}
exit;