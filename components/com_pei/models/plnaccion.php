<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla modelform library
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'funcionario.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'indicador.php';

jimport( 'joomla.application.component.modeladmin' );
JTable::addIncludePath( JPATH_BASE . DS . 'components' . DS . 'com_pei' . DS . 'tables' );

/**
 * Modelo Plan Estratégico Institucional
 */
class PeiModelPlnAccion extends JModelAdmin
{

    private $testForm;

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     * @since	1.6
     */
    public function getTable( $type = 'PlnAccion', $prefix = 'PeiTable', $config = array() )
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
    public function getForm( $data = array(), $loadData = true )
    {

        // Get the form.
        $form = $this->loadForm( 'com_pei.plnaccion', 'plnaccion', array('control' => 'jform', 'load_data' => $loadData) );
        $this->testForm = $form;

        if( empty( $form ) ){
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
        $data = JFactory::getApplication()->getUserState( 'com_pei.plnaccion.plnaccion.data', array() );

        if( empty( $data ) ){
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * 
     * @param type $idUnidadGestion
     */
    public function getResponsables( $idUnidadGestion )
    {        
        $db = JFactory::getDBO();
        
        //  Instacio la tabla Funcionario
        $tbIndicador = new jTableIndicador( $db );
        $rst = $tbIndicador->getResponsablesVariable( $idUnidadGestion );
        
        if( count( $rst ) > 0 ){
            $rst[] = (object)array( 'id' => 0, 'nombre' => JText::_( 'COM_PEI_FIELD_ATRIBUTO_RESPONSABLE_TITLE' ) );
        }else{
            $rst[] = (object)array(  'id' => 0, 'nombre' => JText::_( 'COM_PEI_SIN_REGISTROS' ) );
        }
        
        return $rst;
        
        

    }
}