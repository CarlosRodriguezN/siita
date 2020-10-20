<?php

//  Adjunto Tablas asociadas 
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'unidadmedida.php';

class UnidadMedida
{
    public function __construct()
    {}
    
    /**
     * 
     * Gestiono el registro de una variable
     * 
     * @return type
     */
    public function getDtaUnidadMedida( $idTpoUM )
    {
        $db = JFactory::getDBO();
        
        //  Instacio la tabla Unidad de Medida
        $tbUM = new jTableUnidadMedida( $db );
        $rst = $tbUM->getDtaUnidadMedida( $idTpoUM );

        if( count( $rst ) > 0 ){
            $rst[] = (object)array( 'id' => 0, 'nombre' => JText::_( 'COM_PEI_FIELD_ATRIBUTO_INDENTIDAD_TITLE' ) );
        }else{
            $rst = (object)array( '0' => JText::_( 'COM_PEI_SIN_REGISTROS' ) );
        }
        
        return $rst;
    }
}