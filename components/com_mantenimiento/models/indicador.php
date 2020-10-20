<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla modelform library
jimport( 'joomla.application.component.modeladmin' );

JTable::addIncludePath( JPATH_BASE . DS . 'components' . DS . 'com_mantenimiento' . DS . 'tables' );

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'objetivos' . DS . 'objetivo' . DS . 'indicadores' . DS . 'indicadores.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'indicadorPlantilla.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'variableIndPlantilla.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'indicador' . DS . 'IndVarPlantilla.php';

/**
 * Modelo Fase
 */
class MantenimientoModelIndicador extends JModelAdmin
{

    /**
     * 
     * Returns a reference to the a Table object, always creating it.
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     * @since	1.6
     */
    public function getTable( $type = 'Indicador', $prefix = 'MantenimientoTable', $config = array () )
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
    public function getForm( $data = array (), $loadData = true )
    {
        // Get the form.
        $form = $this->loadForm( 'com_mantenimiento.indicador', 'indicador', array ( 'control' => 'jform', 'load_data' => $loadData ) );

        //  Lista de variables asociada al indicador
        $lstVIP = $this->_getLstVariablesIndPlantilla( $form->getField( 'intId_pi' )->value );
        $form->setFieldAttribute( 'lstVIP', 'default', $lstVIP );

        if ( empty( $form ) ){
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
        $data = JFactory::getApplication()->getUserState( 'com_mantenimiento.edit.indicador.data', array () );

        if ( empty( $data ) ){
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * 
     * Retorno las variables asociadas a un indicador de tipo plantilla
     * 
     * @param int $idIndPtlla  Identificador Indicador Plantilla
     * 
     * @return type
     * 
     */
    private function _getLstVariablesIndPlantilla( $idIndPtlla )
    {
        $db = JFactory::getDBO();

        //  Instacio la tabla Linea Base
        $tbIndicador = new jTableIndicadorVariable( $db );

        return json_encode( $tbIndicador->getVariablesPlla( $idIndPtlla ) );
    }

    /**
     * 
     * Retorna Informacion de Unidad de Medida de un Indicador 
     * que pertenece a un determinado Tipo de Unidad de Medida
     * 
     * @param type $idTpoUM     Identificador del Tipo de Unidad de Medida
     * 
     * @return type
     */
    public function getUnidadMedida( $idTpoUM )
    {
        $db = JFactory::getDBO();

        //  Instacio la tabla Unidad de Medida
        $tbUT = new jTableUnidadMedida( $db );
        $rst = $tbUT->getDtaUnidadMedida( $idTpoUM );

        if ( count( $rst ) > 0 ){
            $rst[] = (object)array ( 'id' => "", 'nombre' => JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_UNDMEDIDA_TITLE' ) );
        } else{
            $rst = (object)array ( 'id' => "", 'nombre' => JText::_( 'COM_INDICADORES_SIN_REGISTROS' ) );
        }

        return $rst;
    }

    
    /**
     * 
     * Gestiona la informacion de indicadores
     * 
     * @param type $dtaGralIndicador    Datos Generales un indicador
     * @param type $dtaVariables        Datos de las variables asociadas al indicador
     * 
     */
    public function gestionIndicadorPlantilla( $dtaGralIndicador, $dtaVariables )
    {
        //   Gestion de informacion del indicador
        $idIndPlantilla = $this->_gestionIndicador( $dtaGralIndicador );
        
        if( count( $dtaVariables ) ){
            $this->_gestionIndVariable( $idIndPlantilla, $dtaVariables );
        }

        return $idIndPlantilla;
    }
    
    
    private function _gestionIndicador( $dtaGralIndicador )
    {
        $dtaIndicador["intId_pi"]           = $dtaGralIndicador->intId_pi;
        $dtaIndicador["inpCodigo_claseind"] = $dtaGralIndicador->inpCodigo_claseind;
        $dtaIndicador["intCodigo_unimed"]   = $dtaGralIndicador->intCodigo_unimed;
        $dtaIndicador["inpCodigo_unianl"]   = $dtaGralIndicador->inpCodigo_unianl;
        $dtaIndicador["strNombre_pi"]       = $dtaGralIndicador->strNombre_pi;
        $dtaIndicador["strDescripcion_pi"]  = $dtaGralIndicador->strDescripcion_pi;
        $dtaIndicador["strFormula_pi"]      = $dtaGralIndicador->formulaDescripcion;
        $dtaIndicador["published"]          = $dtaGralIndicador->published;
        
        if( $dtaGralIndicador->intId_pi == 0 ){
            $dtaIndicador["dteFechaRegistro_pi"] = date( 'Y-m-d' );
        }else{
            $dtaIndicador["dteFechaModificacion_pi"] = date( 'Y-m-d' );
        }
        
        $db = JFactory::getDBO();

        //  Instacio la tabla Indicador
        $tbIndPtlla = new jTableIndicadorPlantilla( $db );
        return $tbIndPtlla->registroDtaIndicadorPtlla( $dtaIndicador );
    }
    
    
    private function _gestionIndVariable( $idIndPlantilla, $dtaVariables )
    {
        foreach( $dtaVariables as $variable ){
            
            if( $variable->published == 1 ){
                $dtaVar["intIdVariable_pv"] = $variable->idVariable;
                $dtaVar["strNombre_pv"]     = $variable->nombre;
                $dtaVar["strAlias_pv"]      = $variable->alias;
                $dtaVar["strDescripcion_pv"]= $variable->descripcion;
                $dtaVar["intCodigo_unimed"] = $variable->idUndMedida;
                $dtaVar["inpCodigo_unianl"] = $variable->idUndAnalisis;

                if( $variable->idVariable == 0 ){
                    $dtaVar["dteFechaRegistro_pv"]      = date( 'Y-m-d' );
                }else{
                    $dtaVar["dteFechaModificacion_var"] = date( 'Y-m-d' );
                }

                $idVariable = $this->_regVariable( $dtaVar );

                if( $variable->idVariable == 0 ){
                    $this->_regIndPtllaVariable( $idIndPlantilla, $idVariable );
                }
            }  else{
                $this->_deleteVariable( $variable->idVariable );
            }
            
        }
        
        return $idVariable;
    }
    
    private function _regVariable( $dtaVar )
    {
        $db = JFactory::getDBO();

        //  Instacio la tabla Indicador
        $tbIndPtlla = new jTableVariableIndPlantilla( $db );
        return $tbIndPtlla->registroDtaIndVariablePtlla( $dtaVar );
    }
    
    
    private function _deleteVariable( $idVariable )
    {
        $db = JFactory::getDBO();

        //  Instacio la tabla Indicador
        $tbIndPtlla = new jTableVariableIndPlantilla( $db );
        return $tbIndPtlla->deleteVariablePtlla( $idVariable );
    }
    
    
    private function _regIndPtllaVariable( $idIndPlantilla, $idVariable )
    {
        $db = JFactory::getDBO();

        $dtaIndVarPtlla["intId_pi"] = $idIndPlantilla;
        $dtaIndVarPtlla["intIdVariable_pv"] = $idVariable;
        
        //  Instacio la tabla Indicador
        $tbIndPtlla = new jTableIndVarPlantilla( $db );
        return $tbIndPtlla->registroRelacionIndVarPtlla( $dtaIndVarPtlla );
    }
    
    public function __destruct()
    {
        return;
    }

}