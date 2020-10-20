<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

JTable::addIncludePath(JPATH_BASE . DS . 'components' . DS . 'com_mantenimiento' . DS . 'tables');
 
/**
 * Modelo tipo obra
 */
class MantenimientoModelLineaBase extends JModelAdmin
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
    public function getTable( $type = 'LineaBase', $prefix = 'mantenimientoTable', $config = array() ) 
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
        $form = $this->loadForm( 'com_mantenimiento.lineabase', 'lineabase', array( 'control' => 'jform', 'load_data' => $loadData ) );
        
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
        $data = JFactory::getApplication()->getUserState( 'com_mantenimiento.edit.lineabase.data', array() );
        
        if( empty( $data ) ){
            $data = $this->getItem();
        }
        
        return $data;
    }
    
    
    public function registrarLineaBase()
    {
        $tbLB = $this->getTable();
        return $tbLB->registroLineaBase( JRequest::getVar( 'jform' ) );
    }
    
    public function mantenimientoLinBas()
    {
        $res = array();
        $fuente = JRequest::getVar( 'fuente' ) ;
        $liBase = JRequest::getVar( 'liBase' ) ;
        $valor = JRequest::getVar( 'valor' ) ;
        $op = JRequest::getVar( 'op' ) ;
        
        switch ($op){
            case 1:
               $res = $this->_registrarFuente( $fuente );
                break;
            case 2:
                $res = $this->_registrarLineaBase( $fuente, $liBase, $valor );
                break;
            case 3:
                $res = $this->_registrarValorLB( $liBase, $valor );
                break;
        }
        return $res;
    }
    
    
    private function _registrarFuente( $fuente )
    {
        $result = array();
        $tbFuente = $this->getTable( 'Fuente', 'MantenimientoTable' );
        
        $data["intCodigo_fuente"]       = 0;
        $data["intCodigo_ug"]           = 0;
        $data["strObservacion_fuente"]  = $fuente;
        $data["published"]              = 1;
        
        $idFuente = $tbFuente->registrarFuente( $data );
        if ( $idFuente ){
            $result = $tbFuente->getFuente( $idFuente );
        }
        
        return $result;
    }
    
    private function _registrarLineaBase( $fuente, $liBase, $valor )
    {
        $result = array();
        $tbLB = $this->getTable( 'LineaBase', 'MantenimientoTable' );
        
        $data["intCodigo_lbind"]            = 0;
        $data["intCodigo_fuente"]           = (int)$fuente;
        $data["intCodigo_per"]              = 1;
        $data["strDescripcion_lbind"]       = $liBase;
        $data["dcmValor_lbind"]             = (int)$valor;
        $data["dteFechaRegistro_lbind"]     = date("Y-m-d H:i:s");
        $data["dteFechaModificacion_lbind"] = date("Y-m-d H:i:s");
        $data["intTpoLB_lbind"]             = 0;        //  tipo de linea base 0;real, 1:supuesto
        
        $idLinBas = $tbLB->registroLineaBase( $data );
        if ( !empty($idLinBas) ){
            $result = $tbLB->getLineaBase( $idLinBas );
        }
        
        return $result;
    }
    
    private function _registrarValorLB( $idLB, $valor )
    {
        $result = array();
        $tbLB = $this->getTable( 'LineaBase', 'MantenimientoTable' );
        $dtaLB = $tbLB->updValorLB( (int)$idLB, (int)$valor );
        
        if ( !empty($dtaLB) ){
            $result = $dtaLB;
        }
        
        return $result;
    }
    
}