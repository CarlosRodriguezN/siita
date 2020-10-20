<?php

//  Adjunto Tablas asociadas 
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'contrato.php';

class Contrato
{
    public function __construct()
    {}
    
    /**
     * Obtengo una lista de contratos de una determinada unidad de gestión responsable
     * 
     * @param int      $idEntidadUG    Identificador de la unidad de gestión
     * @return type
     */
    public function getLstContratosUG( $idEntidadUG )
    {
        $db = JFactory::getDBO();
        $tblCtr = new JTableContrato( $db );
        $result = $tblCtr->getLstContratosUG( $idEntidadUG );
        return $result;
    }
    
    /**
     * Obtengo una lista de contratos de un determinado funcionario responsable
     * 
     * @param int      $idFnc    Identificador del funcionario
     * @return type
     */
    public function getLstContratosFnc( $idFnc )
    {
        $db = JFactory::getDBO();
        $tblCtr = new JTableContrato( $db );
        $result = $tblCtr->getLstContratosFnc( $idFnc );
        return $result;
    }
    
    /**
     * 
     * Retorna una lista de contratos asociados a un deteminado programa
     * 
     * @param type $idPrograma  Identificador del programa
     * @return type
     * 
     */
    public function getLstContratosPrg( $idPrograma )
    {
        $db = JFactory::getDBO();
        $tblCtr = new JTableContrato( $db );
        $result = $tblCtr->getLstContratosPrg( $idPrograma );
        return $result;
    }
    
    /**
     * 
     * Retorna una lista de contratos asociados a un deteminado programa
     * 
     * @param type $idProyecto  Identificador del programa
     * @return type
     * 
     */
    public function getLstContratosPry( $idProyecto )
    {
        $db = JFactory::getDBO();
        $tblCtr = new JTableContrato( $db );

        $result = $tblCtr->getLstContratosPry( $idProyecto );
        return $result;
    }
}