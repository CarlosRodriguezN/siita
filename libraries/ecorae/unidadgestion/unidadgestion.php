<?php

//  Importa la tabla necesaria para hacer la gestion
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'unidadgestion.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'funcionario.php';

/**
 * Getiona el objetivo
 */
class UnidadGestion {

    public function __construct() {
        return;
    }

    /**
     *  Retorna la lista de objetivos 
     * @param type $idPei
     * @return type
     */
    public function getUnidadGestionByID($idUndGestion) {
        $result = FALSE;
        $db = JFactory::getDBO();
        $tblPei = new JTableUnidadGestion($db);
        $result = $tblPei->getUnidadGestion($idUndGestion);
        return $result;
    }

    /**
     *  Recupera una UNIDADE DE GESTION dado el IDENTIDAD. 
     * @param type $idEntidad
     * @return type
     */
    public function getUnidadGestionByEntidad($idEntidad) {
        $result = FALSE;
        $db = JFactory::getDBO();
        $tblPei = new JTableUnidadGestion($db);
        $result = $tblPei->getUnidadGestionByEntidad($idEntidad);
        return $result;
    }
    
    /**
     *  Retorna la lista de unidades hijas de un aunidad de gestion padre 
     * @param type $idOwner
     * @return type
     */
    public function getUGHijos($idOwner) 
    {
        $db = JFactory::getDBO();
        $tblUG = new JTableUnidadGestion($db);
        $result = $tblUG->getHijosUnidadGestion($idOwner);
        return $result;
    }
    
    
    /**
     *  Retorna la lista fe runcionarios de una unidad de gestion
     * @param type $idUG
     * @return type
     */
    public function getFuncionariosResponsables( $idUG )
    {
        $db = JFactory::getDBO();
        $tbIndicador = new JTableUnidadFuncionario( $db );
        $rst = $tbIndicador->getLstRespoUniGestion( $idUG );
        
        if( count( $rst ) > 0 ){
            $rst[] = (object)array( 'id' => 0, 'nombre' => 'SELECCIONE UN FUNCIONARIO' );
        }else{
            $rst[] = (object)array(  'id' => 0, 'nombre' => 'SIN REGISTROS DISPONIBLES' );
        }
        return $rst;
    }

}
