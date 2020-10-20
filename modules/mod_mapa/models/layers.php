<?php

defined('_JEXEC') or die();
require_once JPATH_BASE . DS . 'modules' . DS . 'mod_mapa' . DS . 'tables' . DS . 'layers.php';

class layersModel extends JModel {

    function getLayers($idWMS) {
        $db = JFactory::getDbo();
        $layers = new JTableLayers($db);
        return $layers->getLayers($idWMS);
    }

}

if (($_GET["tut"]) && ($_GET["task"])) {
    $tut = $_GET["tut"]; //;
    $task = $_GET["task"]; //;

    $model = new mapaDPAModel();
    switch ($task) {
        case 'getFiltros':
            $model->getUnidadesTerritoriales($tut);
            break;
        case 'getProvinciasPorZona':
            $idZona = $_GET["id"];
            $model->getProvinciasPorZona($idZona);
            break;
        case 'getCantonesPorProvincia':
            $idProvincia = $_GET["id"];
            $model->getCantonesPorProvincia($idProvincia);
            break;
        case 'getParroquiasPorCanton':
            $idCanton = $_GET["id"];
            $model->getParroquiasPorCanton($idCanton);
            break;

        case 'getListaProyectos':
            $id_ut = $_GET["id"];
            $tut = $_GET["tut"];
            $model->getListaProyectos($id_ut, $tut);
            break;
    }
}