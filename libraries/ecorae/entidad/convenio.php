<?php

//  Adjunto Tablas asociadas 
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'convenio.php';

class Convenio
{
    public function __construct()
    {}
    
    /**
     * Obtengo una lista de convenios de una determinada unidad de gestión responsable
     * 
     * @param int      $idEntidadUG    Identificador de la unidad de gestión
     * @return type
     */
    public function getLstConveniosUG( $idEntidadUG )
    {
        $db = JFactory::getDBO();
        $tblCvn = new JTableConvenio( $db );
        $result = $tblCvn->getLstConveniosUG( $idEntidadUG );
        return $result;
    }
    
    /**
     * Obtengo una lista de convenios de un determinado funcionario responsable
     * 
     * @param int      $idFuncionario    Identificador del funcionario
     * @return type
     */
    public function getLstConveniosFnc( $idFuncionario )
    {
        $db = JFactory::getDBO();
        $tblCvn = new JTableConvenio( $db );
        $result = $tblCvn->getLstConveniosFnc( $idFuncionario );
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
    public function getLstConveniosPrg( $idPrograma )
    {
        $db = JFactory::getDBO();
        $tblCtr = new JTableContrato( $db );
        $result = $tblCtr->getLstConveniosPrg( $idPrograma );
        return $result;
    }
    
    /**
     * 
     * Retorna una lista de Convenios asociados a un deteminado programa
     * 
     * @param type $idProyecto  Identificador del proyecto
     * @return type
     * 
     */
    public function getLstConveniosPry( $idProyecto )
    {
        $db = JFactory::getDBO();
        $tblCtr = new JTableContrato( $db );
        $result = $tblCtr->getLstConveniosPry( $idProyecto );
        return $result;
    }
}