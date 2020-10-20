<?php

//  Adjunto Tablas asociadas 
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'programas.php';

class Programa
{
    public function __construct()
    {}
    
    /**
     * Obtengo una lista de programas de una determinada unidad de gestión responsable
     * 
     * @param int      $idEntidadUG    Identificador de la unidad de gestión
     * @return type
     */
    public function getLstProgramasUG( $idEntidadUG )
    {
        $db = JFactory::getDBO();
        $tblPrg = new JTableProgramas( $db );
        $result = $tblPrg->getLstProgramasUG( $idEntidadUG );
        return $result;
    }
    
    /**
     * Obtengo una lista de programas de un determinado Funcionario
     * 
     * @param int      $idFnc    Id del Funcionario
     * @return type
     */
    public function getLstProgramasFnc( $idFnc )
    {
        $db = JFactory::getDBO();
        $tblPrg = new JTableProgramas( $db );
        $result = $tblPrg->getLstProgramasFnc( $idFnc );
        return $result;
    }
    
    
}