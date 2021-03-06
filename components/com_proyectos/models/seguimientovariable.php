<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

JTable::addIncludePath( JPATH_BASE . DS . 'components' . DS . 'com_proyectos' . DS . 'tables' );

/**
 * Modelo Fase
 */
class ProyectosModelSeguimientoVariable extends JModelAdmin
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
    public function getTable( $type = 'Seguimiento', $prefix = 'ProyectosTable', $config = array() ) 
    {
        return JTable::getInstance( $type, $prefix, $config );
    }
    
    /**
    * 
    * Method to get the record form.
    *
    * @param	array	$data		Data for the form.
    * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
    * @return	mixed	A JForm object on success, false on failure
    * @since	1.6
    * 
    */
    public function getForm( $data = array(), $loadData = true ) 
    {
        // Get the form.
        $form = $this->loadForm( 'com_proyectos.seguimientovariable', 'seguimientovariable', array( 'control' => 'jform', 'load_data' => $loadData ) );
        
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
        $data = JFactory::getApplication()->getUserState( 'com_proyectos.edit.seguimientovariable.data', array() );
        
        if( empty( $data ) ){
            $data = $this->getItem();
        }

        return $data;
    }
    
    
    
    public function getLstPlanificacion( $idProyecto, $idIndicador, $idVariable )
    {
        $tbPlanificacion = $this->getTable();
        return $tbPlanificacion->lstPlanificacionVariables( $idProyecto, $idIndicador, $idVariable );
    }
}