<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

//  Adjunto libreria de gestion de entidades
//  jimport( 'ecorae.entidad.entidad' );
//  jimport( 'ecorae.objetivos.objetivo.indicadores.indicadores' );

JTable::addIncludePath( JPATH_BASE . DS . 'components' . DS . 'com_indicadores' . DS . 'tables' );

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'dpa.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'unidadmedida.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'enfoque.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'funcionario.php';

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'entidad' . DS . 'entidad.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'objetivos' . DS . 'objetivo'. DS . 'indicadores' . DS . 'indicadores.php';

/**
 * Modelo Fase
 */
class IndicadoresModelIndicador extends JModelAdmin
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
     * Retorna Lista de Cantones asociados a una determinada Provincia
     * 
     * @param type $idProvincia     Identificador de Provincia
     * 
     * @return type
     * 
     */
    public function getCantones( $idProvincia )
    {
        $db = JFactory::getDBO();
        
        //  Instacio la tabla Unidad de Medida
        $tbUT = new JTableDPAActiva( $db );
        $rst = $tbUT->lstCantones( $idProvincia );
        
        if( count( $rst ) > 0 ){
            $rst[] = (object)array( 'id' => 0, 'nombre' => JText::_( 'COM_INDICADORES_INDICADOR_CANTON_TITLE' ) );
        }else{
            $rst = (object)array( 'id' => 0, 'nombre' => JText::_( 'COM_INDICADORES_SIN_REGISTROS' ) );
        }

        return $rst;
    }
    
    /**
     * 
     * Retorna una lista de Parroquias pertenecientes a un canton
     * 
     * @param type $idCanton    Identificador del Canton
     * 
     * @return type
     */
    public function getParroquias( $idCanton )
    {
        $db = JFactory::getDBO();
        
        //  Instacio la tabla Unidad de Medida
        $tbUT = new JTableDPAActiva( $db );
        $rst = $tbUT->lstParroquias( $idCanton );
        
        if( count( $rst ) > 0 ){
            $rst[] = (object)array( 'id' => 0, 'nombre' => JText::_( 'COM_INDICADORES_INDICADOR_PARROQUIA_TITLE' ) );
        }else{
            $rst = (object)array( 'id' => 0, 'nombre' => JText::_( 'COM_INDICADORES_SIN_REGISTROS' ) );
        }

        return $rst;
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
        
        if( count( $rst ) > 0 ){
            $rst[] = (object)array( 'id' => "", 'nombre' => JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_UNDMEDIDA_TITLE' ) );
        }else{
            $rst = (object)array( 'id' => "", 'nombre' => JText::_( 'COM_INDICADORES_SIN_REGISTROS' ) );
        }

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
     * Retorna Informacion de Lineas Base asociada a una determinada Fuente
     * 
     * @param type $idFuente
     * @return type
     */
    public function getLineasBase( $idFuente )
    {
        $db = JFactory::getDBO();
        
        //  Instacio la tabla Unidad de Medida
        $tbLineaBase = new jTableLineaBase( $db );
        return $tbLineaBase->getLineasBase( $idFuente );
    }
    
    /**
     * 
     * Retorna la lista de enfoques de igualdad.
     * 
     * @param int $idTipo          Identificador del Tipo de Enfoque
     * 
     * @return int Lista de objetos.
     * 
     */
    public function getTiposEnfoqueIgualdad( $idTipo )
    {
        $db = JFactory::getDBO();

        //  Instacio la tabla Unidad de Medida
        $tbIndicador = new jTableIndicador( $db );
        return $tbIndicador->getLstTiposEnfoqueIgualdad( $idTipo );
    }

    public function __destruct()
    {
        return;
    }
}