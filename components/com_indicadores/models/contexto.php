<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

//  Agrego Clase de Retorna informacion de Indicadores asociados a un Objetivo
jimport( 'ecorae.common.TicketTableu' );

JTable::addIncludePath( JPATH_BASE . DS . 'components' . DS . 'com_indicadores' . DS . 'tables' );

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'dpa.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'unidadmedida.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'enfoque.php';

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'entidad' . DS . 'entidad.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'objetivos' . DS . 'objetivo'. DS . 'indicadores' . DS . 'indicadores.php';

/**
 * 
 * Modelo Fase
 * 
 */
class IndicadoresModelContexto extends JModelAdmin
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
        $form = $this->loadForm( 'com_indicadores.contexto', 'contexto', array( 'control' => 'jform', 'load_data' => $loadData ) );

        if( empty( $form ) ){
            return false;
        }

        return $form;
    }

    /**
     * 
     * Retorna informacion de un indicador asociado a una determinada entidad
     * y con una determinada Unidad de Medida
     * 
     * @param type $idEntidad   Identificador de Indicador
     * @param type $idUM        Identificador de Unidad de Medida
     * 
     * @return type
     */
    public function getIndicadores( $idEntidad, $idUM )
    {
        $objIndicador = new Indicadores();   
        $rst = $objIndicador->getDtaIndicador( $idEntidad, $idUM );
        
        if( count( $rst ) > 0 ){
            $rst[] = (object)array( 'id' => 0, 'nombre' => JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_LSTINDICADOR_TITLE' ) );
        }else{
            $rst[] = (object)array(  'id' => 0, 'nombre' => JText::_( 'COM_INDICADORES_SIN_REGISTROS' ) );
        }
        
        return $rst;
    }

    /**
     * 
     * Retorna Responsables del indicador
     *  
     * @param type $idEntidad       Identificador de Entidad
     * @param type $idIndicador     Identificador de Indicador
     * 
     */
    public function getResponsablesIndicador( $idEntidad, $idIndicador )
    {
        $objIndicador = new Indicadores();
        $rst = $objIndicador->getDtaResponsablesIndicador( $idEntidad, $idIndicador );

        return $rst;
    }

    /**
     * 
     * Retorna informacion de un Indicador de Tipo Plantilla
     * 
     * @param type $idPlantilla     Identificador de Plantilla
     * 
     * @return type
     * 
     */
    public function getDtaPlantillaPorId( $idPlantilla )
    {
        $objIndicador = new Indicadores();
        return $objIndicador->getDtaPlantillaIndicador( $idPlantilla );
    }
    
    /**
     * 
     * Retorna informacion de un Indicador de Tipo Plantilla
     * 
     * @param type $idDimension     Identificador de la dimension
     * 
     * @return type
     * 
     */
    public function getDtaPlantillaPorDimension( $dtaDimension )
    {
        $objIndicador = new Indicadores();
        
        switch( $dtaDimension ){
            case 'eco': 
                $idDimension = 31;
            break;
        
            case 'fin': 
                $idDimension = 32;
            break;
        
            case 'bd': 
                $idDimension = 33;
            break;
        
            case 'bi': 
                $idDimension = 34;
            break;
        }

        return $objIndicador->getDtaPtllaPorDimension( $idDimension );
    }
    
    
    /**
     * 
     * Obtengo todos los indicadores asociados a una determinada entidad
     * 
     * @param type $idEntidad   Identificador de la entidad
     * @return type
     * 
     */
    public function getDtaIndicadoresEntidad( $idEntidad )
    {
        $objIndicador = new Indicadores();
        return $objIndicador->dtaIndicadoresEntidad( $idEntidad );
    }
    
    
    /**
     * 
     * Retorna informacion de Entidades ( programa, proyecto, contratos,... ) 
     * de acuerdo a un determinado tipo de entidad
     * 
     * @param type $idTpoEntidad    Tipo de Entidad
     */
    public function getLstEntidad( $idTpoEntidad )
    {
        $db = JFactory::getDBO();
        
        //  Instacio la tabla Entidad
        $tbEntidad = new jTableEntidad( $db );
        
        switch( $idTpoEntidad ){
            case '1'; 
                $rst = $tbEntidad->getLstProgramas();
            break;
        
            case '2'; 
                $rst = $tbEntidad->getLstProyectos();
            break;
            
            case '3'; 
                $rst = $tbEntidad->getLstContratos();
            break;
            
            case '12'; 
                $rst = $tbEntidad->getLstObjetivosPEI();
            break;
        }
        
        if( count( $rst ) > 0 ){
            $rst[] = (object)array( 'id' => 0, 'nombre' => JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_INDENTIDAD_TITLE' ) );
        }else{
            $rst[] = (object)array(  'id' => 0, 'nombre' => JText::_( 'COM_INDICADORES_SIN_REGISTROS' ) );
        }
        
        return $rst;
    }
    
    
    
    /**
     * 
     * Retorna url con ticket de confianza de Tableau
     * 
     * @param String $nombreDashBoard
     * @return type
     * 
     */
    public function getTicketTableuPorNombre( $nombreDashBoard )
    {
        $ticket = new TicketTableu( JText::_( 'COM_INDICADORES_TABLEU_SERVER' ),
                                    JText::_( 'COM_INDICADORES_TABLEU_USER' ),
                                    JText::_( 'COM_INDICADORES_TABLEU_SITE' ),
                                    $nombreDashBoard );

        $dtaURL = $ticket->getUrl();

        return $dtaURL;
    }
    
    
    public function __destruct()
    {
        return;
    }
    
    
}