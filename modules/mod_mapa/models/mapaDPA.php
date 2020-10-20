<?php

//define('DS', '/');
//$dirc = explode('/', $_SERVER['PHP_SELF']);
//$dirc = $dirc[1];
define( 'DS', DIRECTORY_SEPARATOR );
define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);
require_once JPATH_BASE . DS . 'includes' . DS . 'ecoraeFramework.php';
//print_r($_SERVER);
defined('_JEXEC') or die();
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'dpa.php';
require_once JPATH_BASE . DS . 'modules' . DS . 'mod_mapa' . DS . 'tables' . DS . 'unidadTerritorial.php';

class mapaDPAModel extends JModel
{
/**
 * @abstract retorna la s
 * @return type
 */
    function getTiposUniTerritoriales() {
        $db = JFactory::getDbo();
        $dpa = new JTableDPAActiva($db);

        return $dpa->getTiposUniTerritoriales();
    }

    /**
     * 
     * 
     * 
     * @param type $tipoUnidadTerritorial
     */
    function getUnidadesTerritoriales( $tipoUnidadTerritorial )
    {
        $db = JFactory::getDbo();
        $dpa = new JTableDPAActiva( $db );

        $filtro = $dpa->getUnidadesTerritoriales( $tipoUnidadTerritorial );

        $document = JFactory::getDocument();
        $document->setMimeEncoding('application/json');
        echo json_encode($filtro);
    }

    function getUnidadesTerritoriales2( $tipoUnidadTerritorial )
    {
        $db = JFactory::getDbo();
        $dpa = new JTableDPAActiva($db);
        return $dpa->getUnidadesTerritoriales($tipoUnidadTerritorial);
    }

    /**
     * 
     * Gestiona el retorno de una lista de Provincias por Zona utilizada para la llamada ajax
     * 
     * @param type $idZona  Identificador de Zona
     * 
     */
    function getProvinciasPorZona( $idZona )
    {
        $db = JFactory::getDbo();
        $dpa = new JTableDPAActiva($db);

        $provincias = $dpa->getProvinciasPorZona($idZona);

        $document = JFactory::getDocument();
        $document->setMimeEncoding('application/json');
        echo json_encode($provincias);
    }

    function getProvinciasPorZona2($idZona)
    {
        $db = JFactory::getDbo();
        $dpa = new JTableDPAActiva($db);

        return $provincias = $dpa->getProvinciasPorZona($idZona);
    }

    function getCantonesPorProvincia( $idZona, $idProvincia )
    {
        $db = JFactory::getDbo();
        $dpa = new JTableDPAActiva($db);

        $cantones = $dpa->getCantonesPorProvincia( $idZona, $idProvincia );

        $document = JFactory::getDocument();
        $document->setMimeEncoding('application/json');
        echo json_encode($cantones);
    }

    function getParroquiasPorCanton( $idZona, $idCanton )
    {
        $db = JFactory::getDbo();
        $dpa = new JTableDPAActiva($db);

        $hijos = $dpa->getParroquiasPorCanton( $idZona, $idCanton );

        $document = JFactory::getDocument();
        $document->setMimeEncoding('application/json');

        echo json_encode($hijos);
    }

    function getListaProyectos($id, $tut, $idPrograma)
    {
        //creamos un objeto de la tanla APAACTIVA
        $db = JFactory::getDbo();
        $dpa = new JTableDPAActiva($db);
        
        //  Llamamos a la funcion de la tabla
        //      $id = unidad territorial
        //      $tut = tipo unidad territorial
        //      $programa = identificador de el programa del cual queremos el proyecto
        $proyectos = $dpa->getListaProyectos($id, $tut, $idPrograma);
        
        $retval = array();
        if($proyectos){
            $retval = $proyectos;
        }
        
        return $retval;
    }

}

if (($_GET["tut"]) && ($_GET["task"])) {
    $tut = $_GET["tut"]; //;
    $task = $_GET["task"]; //;

    $model = new mapaDPAModel();

    $idZona = JRequest::getVar( "idZona" );
    $idProvincia = JRequest::getVar( "idProvincia" );
    $idCanton = JRequest::getVar( "idCanton" );
    
    switch( $task ){
        case 'getFiltros':
            $model->getUnidadesTerritoriales( $tut );
        break;
        
        case 'getProvinciasPorZona':
            $model->getProvinciasPorZona( $idZona );
        break;
        
        case 'getCantonesPorProvincia':
            $model->getCantonesPorProvincia( $idZona, $idProvincia );
        break;
        
        case 'getParroquiasPorCanton':
            $model->getParroquiasPorCanton( $idZona, $idCanton );
        break;

        case 'getListaProyectos':
            $id_ut = $_GET["id"];
            $tut = $_GET["tut"];
            $model->getListaProyectos( $id_ut, $tut );
        break;
    }
}
