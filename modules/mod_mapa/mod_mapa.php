<?php

defined( '_JEXEC' ) or die;

require_once dirname( __FILE__ ) . DS . 'googlemaps.php';
require_once dirname( __FILE__ ) . DS . 'helper.php';

$id = 3; // id de la unidad Territorial REGION ORIENTE
$tut = 2; // tipo unidad territorial REGION

$ltsProgramas = modMapaHelper::getProgramas( $id, $tut );
$document = JFactory::getDocument();
$moduleclass_sfx = htmlspecialchars( $params->get( 'moduleclass_sfx' ) );
$list = modMapaHelper::getList();

$ltsWMSLayers = modMapaHelper::getWMS();
$tipoUnidadTerritorial = 2;

$listRegiones = modMapaHelper::getRegiones( $tipoUnidadTerritorial );
$idRegion = 1773;

$listProvincias = modMapaHelper::getProvincias( $idRegion );

require JModuleHelper::getLayoutPath( 'mod_mapa', $params->get( 'layout', 'default' ) );

/**
 * 
 * Funcion que me crea la lista de programas para ser manejado en el arbol
 * @param type $ltsProgramas
 * @return string
 */
function toJsonProgramas( $ltsProgramas )
{
    $retval = 'var ltsProgramas=' . $ltsProgramas . ';';
    return $retval;
}

/**
 * Funcion que me maneja la lista de WMS
 * @param type $ltsWMSLayers
 * @return string
 */
function toJsonWms( $ltsWMSLayers )
{
    $retval = 'var ltsWMSLayers=' . $ltsWMSLayers . ';';
    return $retval;
}