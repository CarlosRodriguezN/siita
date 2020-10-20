<?php
defined('_JEXEC') or die;
jimport( 'joomla.filesystem.path' );
require_once dirname(__FILE__).'/helper.php';

//  Obtengo la DATA para crear el path de plan OPERATIVO
$path = JURI::getInstance();
$a = explode("/", $path);
$b = explode("&", $a[3]);
$c = explode("=", $b[0]);
$componente = $c[1];
$opcion = explode("=", $b[3]);


$oPlnVigente = new modPlanVigenteHelper();
$pei = $oPlnVigente->getPEI();
if ( is_object($pei) ) {
    $pppp = $oPlnVigente->getPlanVigenteByOwner( $pei->idPln, 3 );     //  3 Id del tipo de plan PPPP
    $papp = ( is_object($pppp) ) ? $oPlnVigente->getPlanVigenteByOwner( $pppp->idPln, 4 ) : array();    //  4 Id del tipo de plan PPPP
    
    if ( $componente == "com_unidadgestion" || $componente == "com_funcionarios" ){
        switch ( $componente ){
            case "com_unidadgestion":
                $dtaEntidad = $oPlnVigente->getDtaUnidadGestion( (int)$opcion[1] );
                $poa = $oPlnVigente->getPlanVigenteByOwner( $pppp->idPln, 2, (int)$dtaEntidad->idEntidadUG );
                break;
            case "com_funcionarios":
                $dtaEntidad = $oPlnVigente->getDtaFuncionario( (int)$opcion[1] );
                $poa = $oPlnVigente->getPlanVigenteByOwner( $pppp->idPln, 2, (int)$dtaEntidad->idEntidadFun );
                break;
        }
    } else{
        $poa = array();
    }
    
} else {
    $pppp = array();
    $papp = array();
}

require JModuleHelper::getLayoutPath('mod_plan_vigente', $params->get('layout', 'default'));
?>
