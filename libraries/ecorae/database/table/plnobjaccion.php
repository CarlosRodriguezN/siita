<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
//jimport( 'joomla.database.table' );
jimport('joomla.database.tablenested');

/**
 * 
 * Clase que gestiona informacion de la tabla de datos generales de PEI ( #__pei_plan_institucion )
 * 
 */
class jTablePlnObjAccion     extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__pln_plnobj_accion', 'intId_plnobj_accion', $db );
    }

    /**
     * 
     * @param type $dtaObj
     * @return type
     */
    public function registroPlnObjAccion( $dtaPlnObjAcc )
    {
        if( !$this->save( $dtaPlnObjAcc ) ) {
            echo $this->getError();
            //  echo 'error al guardar un objetivo del PEI';
            exit;
        }

        return $this->intId_plnobj_accion;
    }


}