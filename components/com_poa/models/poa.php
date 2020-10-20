<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla modelform library
jimport( 'joomla.application.component.modeladmin' );
jimport( 'ecorae.objetivos.objetivo.objetivos' );

JTable::addIncludePath( JPATH_BASE . DS . 'components' . DS . 'com_poa' . DS . 'tables' );

/**
 * Modelo Plan Estratégico Institucional
 */
class PoaModelPoa extends JModelAdmin
{

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     * @since	1.6
     */
    public function getTable( $type = 'Poa', $prefix = 'PoaTable', $config = array( ) )
    {
        return JTable::getInstance( $type, $prefix, $config );
    }

    /**
     * Method to get the record form.
     *
     * @param	array	$data		Data for the form.
     * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
     * @return	mixed	A JForm object on success, false on failure
     * @since	1.6
     */
    public function getForm( $data = array( ), $loadData = true )
    {
        // Get the form.
        $form = $this->loadForm( 'com_poa.poa', 'poa', array( 'control' => 'jform', 'load_data' => $loadData ) );

        if( empty( $form ) ) {
            return false;
        }

        return $form;
        }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return	mixed	The data for the form.
     * @since	1.6
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState( 'com_poa.edit.poa.data', array( ) );

        if( empty( $data ) ) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * 
     * Retorna el id del POA (almeacenamiento via ajax)
     * 
     * @return type
     */
    public function guardarPoa()
    {
        $data = JRequest::getvar( 'dtaFrm' );
        $idPoa = $this->_registrarPoa( $data );

        //  Creacion del directorio para sus archivos.

        $this->_makeDirPOA( $idPoa );

        $retVal["idPoa"] = $idPoa;

        $oObejtivos = new Objetivos();
        $lstObjetivos = JRequest::getvar( 'lstObjetivos' );
        $tipo = 2; //indica que es un objetivo de un POA
        $retVal["lstObjetivos"] = $oObejtivos->saveObjetivos( $lstObjetivos, $idPoa, $tipo );
      
        return $retVal;
    }

    /**
     *  Registra un POA 
     * @param object $dtaFrm    Datos generales del plan  
     * @return int              Identificador del POA
     */
    private function _registrarPoa( $dtaFrm )
    {
        $dtaPOA = json_decode( $dtaFrm );

        $tbPoa = $this->getTable( 'Poa', 'PoaTable' );
        $idPOA = $tbPoa->registroPoa( $dtaPOA );

        return $idPOA;
    }

    /**
     * 
     *  Crea el directorio del POA
     * 
     * @param int $idPoa    Identificador del Poa
     */
    private function _makeDirPOA( $idPoa )
    {
        $path = JPATH_BASE . DS . 'media' . DS . 'ecorae' . DS . 'docs' . DS . 'poas' . DS . $idPoa;
        if( !(file_exists( $path )) ) {
            mkdir( $path, 0777, true );
        }
    }

    /**
     * Recupera la indormacion del pei al que pertenece
     * @return type
     */
    public function getPei()
    {

        $idPei = JRequest::getVar( "idPadre" );
        $objePei = false;

        if( $idPei ) {
            $tbPoa = $this->getTable();
            $objePei = $tbPoa->getPei( $idPei );
        }
        return $objePei;
    }

    /**
     *  Retorna una lista de objetivos de un determinado plan 
     * 
     * @param type $idPei
     * @param type $idTpoEntidad    Identificador del Tipo de PEI
     *                                  1: PEI
     *                                  2: POA
     *                                  3: Programas
     * 
     * @return type
     */
    public function lstObjetivos( $idPei, $idTpoEntidad )
    {
        $lstObjetivos = false;
        $objetivos = new Objetivos();

        if( $objetivos ) {
            $lstObjetivos = $objetivos->getLstObjetivos( $idPei, $idTpoEntidad );
        }

        return $lstObjetivos;
    }

    /**
     * Funcin encargada de eliminar los archivos 
     */
    public function delArchivo()
    {
        $archivo = JRequest::getVar( "infArchivo" );
        switch( $archivo["tipo"] ) {
            case 1:
                $dirName = 'peis';
                break;
            case 2:
                $dirName = 'poas';
                break;
            case 3:
                $dirName = 'programas';
                break;
        }

        $path = JPATH_BASE . DS . "media" . DS . "ecorae" . DS . "docs" . DS . $dirName . DS . $archivo["idPadre"] . DS . 'objetivos' . DS . $archivo["idObjetivo"] . DS . 'actividades' . DS . $archivo["idActividad"] . DS . $archivo["nameArchivo"];
        $retvatl = unlink( $path );
        echo $retvatl;
        exit();
    }

}