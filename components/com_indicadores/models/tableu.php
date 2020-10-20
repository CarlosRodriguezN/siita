<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

JTable::addIncludePath( JPATH_BASE . DS . 'components' . DS . 'com_indicadores' . DS . 'tables' );

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'dpa.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'unidadmedida.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'enfoque.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'funcionario.php';

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'entidad' . DS . 'entidad.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'objetivos' . DS . 'objetivo'. DS . 'indicadores' . DS . 'indicadores.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'objetivos' . DS . 'objetivo'. DS . 'indicadores' . DS . 'IndicadorEntidad.php';

//  Agrego Clase de Retorna informacion de Indicadores asociados a un Objetivo
jimport( 'ecorae.common.TicketTableu' );

/**
 * Modelo Fase
 */
class IndicadoresModelTableu extends JModelAdmin
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
    public function getTable( $type = 'Indicador', $prefix = 'IndicadoresTable', $config = array() ) 
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
        $form = $this->loadForm( 'com_indicadores.indicador', 'indicador', array( 'control' => 'jform', 'load_data' => $loadData ) );

        if( empty( $form ) ){
            return false;
        }

        return $form;
    }
    
    /**
     * 
     * Retorna informacion de la Url de Tableu
     * 
     * @param int $idIndicador     Identificador de Indicador, que vamos a obtener la Url de Tableu
     * 
     * @return string - html
     * 
     */
    private function _getNombreTableu( $idIndicador )
    {
        $db = JFactory::getDBO();
        $tbIndicador = new jTableIndicadorEntidad( $db );
        
        return $tbIndicador->getNombreIndicadorTableu( $idIndicador );
    }
    
    public function getTicketTableu( $idIndicador )
    {
        $ticket = new TicketTableu( JText::_( 'COM_INDICADORES_TABLEU_SERVER' ),
                                    JText::_( 'COM_INDICADORES_TABLEU_USER' ),
                                    JText::_( 'COM_INDICADORES_TABLEU_SITE' ),
                                    $this->_getNombreTableu( $idIndicador ) );

        return $ticket->getUrl();
    }
}
