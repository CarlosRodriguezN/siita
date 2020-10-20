<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');
 
/**
 * Modelo tipo obra
 */
class mantenimientoModelDpa extends JModelAdmin
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
    public function getTable( $type = 'Dpa', $prefix = 'mantenimientoTable', $config = array() ) 
    {
        return JTable::getInstance($type, $prefix, $config);
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
        $form = $this->loadForm( 'com_mantenimiento.dpa', 'dpa', array( 'control' => 'jform', 'load_data' => $loadData ) );
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
        $data = JFactory::getApplication()->getUserState( 'com_mantenimiento.edit.dpas.data', array() );
        
        if( empty( $data ) ){
            $data = $this->getItem();
        }
        
        return $data;
    }
    
    /**
     * Crea las tablas de logs por componente a se auditado
     * 
     * @return type
     */
    public function crearTbsAuditoria()
    {
        $tbMT = $this->getTable( 'MakeTrigger', 'mantenimientoTable' );
        $lstComAuditoria = array("tb_cp", "tb_ctr", "tb_ind", "tb_pfr", "tb_pln", "tb_pnd", "tb_gen");
        return $tbMT->crearTables($lstComAuditoria);
    }
    
    /**
     *  Crea los triggers para los logs de audirotia por cada tabla
     * 
     * @return type
     */
    public function generarTrigger()
    {
        $tbMT = $this->getTable( 'MakeTrigger', 'mantenimientoTable' );
        $lstComAuditoria = array("tb_cp", "tb_ctr", "tb_ind", "tb_pfr", "tb_pln", "tb_pnd", "tb_gen");
        return $tbMT->armarTrigger($lstComAuditoria);
    }
    
}