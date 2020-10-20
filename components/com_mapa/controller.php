<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

class mapaController extends JController {

    function getUnidadesTerritoriales($padre, $tipo) {
        $db = JFactory::getDBO();
        $document = JFactory::getDocument();
        $sql = "SELECT T1.intID_ut as id, T1.strNombre_ut as nombre
                FROM " . $db->nameQuote("#__ut_undTerritorial") . " as T1
                WHERE T1.intID_ut IN (
                                        SELECT t2.intID_ut
                                        FROM tb_ut_relacionDPA t2
                                        WHERE t2.intIDPadre_ut = '{$padre}'
                                        )
                AND intID_tut = '{$tipo}';";

        $db->setQuery($sql);
        $provincias = $db->loadObjectList();
        $document->setMimeEncoding('application/json');

        echo json_encode($provincias);
        exit();
    }

//    function getTiposUniTerritoriales(){
//    
//        $padre =   JRequest::getVar('id') ;
//        $tipo = JRequest::getVar('tut');
//        
//        return $this->getLstHijos($padre, $tipo);
//    }
    

    function getLstProyectos() {
        $db = JFactory::getDBO();
        $document = JFactory::getDocument();

        $sql = "SELECT T1.intcodigo_pry AS id, T1.strnombre_pry AS proyect
                FROM " . $db->nameQuote("#__pfr_proyecto_frm") . " as T1
                WHERE T1.intcodigo_pry IN(
                                      SELECT intcodigo_pry FROM dev_siita.ubicgeo_pry
                                      WHERE intID_ut = '" . JRequest::getVar('id') . "');";

        $db->setQuery($sql);
        $proyectos = $db->loadObjectList();
        $lstProyectos = $this->_proyectsObjectConvert($proyectos);
        $document->setMimeEncoding('application/json');
        echo json_encode($lstProyectos);
    }

    /*
     * funcion que conbierte al la lista de proyectos en formato
     * miff tree
     */

    function _proyectsObjectConvert($proyectos) {
        $proyectosJSON = array();
        try {
            if ($proyectos) {
                foreach ($proyectos as $proyecto) {
                    $proyectoJSON = (object) null;
                    $proyectoJSON->property = (object) null;
                    $proyectoJSON->property->name = $proyecto->proyecto;
                    $proyectoJSON->property->hasCheckbox = true;
                    $proyectoJSON->property->id = $proyecto->id;
                    $proyectoJSON->type = "proyecto";
                    // $proyectoJSON->data = (object) null;
                    // $proyectoJSON->data->responsable = $proyecto->responsable;
                    // $proyectoJSON->data->fInicioEst = $proyecto->fInicioEst;
                    // $proyectoJSON->data->fFinEst = $proyecto->fFinEst;
                    //  $proyectoJSON->data->totBeneficiarios = $proyecto->totBeneficiarios;
                    // $proyectoJSON->data->totMonto = $proyecto->totMonto;
                    //   $proyectoJSON->data->elemento = (object) null;
                    //   $proyectoJSON->data->elemento->id = $proyecto->id;
                    //  $proyectoJSON->data->elemento->vertex = self::getLstCoordProy($proyecto->id);

                    $proyectosJSON[$proyecto->id] = $proyectoJSON;
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return array_values($proyectosJSON);
    }

    /*
     * Recupera las coordenadas de un proyecto
     */

    function getLstCoordProy($idProyecto) {
        $db = JFactory::getDBO();
        $tabla = "#__pfr_coordenadas";
        $sql = "SELECT coor.fltLatitud_cord, coor.fltLongitud_cord 
                 FROM" . $db->nameQuote($tabla) . " as coor
                WHERE intCodigo_pry = '" . $idProyecto . "';";
        $db->setQuery($sql);
        $puntos = $db->loadObjectList();
        return ( $puntos );
    }

}

?>