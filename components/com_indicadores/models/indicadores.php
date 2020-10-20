<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

//  Agrego Clase de Retorna informacion Especifica de un Indicador
jimport( 'ecorae.objetivos.objetivo.indicadores.indicador' );

//  Agrego Clase de Retorna informacion de Indicadores asociados a un Objetivo
jimport( 'ecorae.objetivos.objetivo.indicadores.indicadores' );

jimport( 'ecorae.database.table.dpa' );
jimport( 'ecorae.database.table.unidadmedida' );
jimport( 'ecorae.database.table.enfoque' );
jimport( 'ecorae.database.entidad.entidad' );


JTable::addIncludePath( JPATH_BASE . DS . 'components' . DS . 'com_proyectos' . DS . 'tables' );

/**
 * Modelo Fase
 */
class IndicadoresModelIndicadores extends JModelAdmin
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
     * Retorna informacion de un Indicador de Tipo Plantilla
     * 
     * @param type $idPlantilla     Identificador de Plantilla
     * 
     * @return type
     * 
     */
    public function getDtaPlantilla( $idPlantilla )
    {
        $objIndicador = new Indicadores();
        return $objIndicador->getDtaPlantillaIndicador( $idPlantilla );
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
        if( (int)$idTpoUM ){
            $db = JFactory::getDBO();
            //  Instacio la tabla Unidad de Medida
            $tbUT = new jTableUnidadMedida( $db );
            $rst = $tbUT->getDtaUnidadMedida( $idTpoUM );

            if( count( $rst ) > 0 ){
                $rst[] = (object) array( 'id' => "", 'nombre' => JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_UNDMEDIDA_TITLE' ) );
            }else{
                $rst[] = (object) array( 'id' => "", 'nombre' => JText::_( 'COM_INDICADORES_SIN_REGISTROS' ) );
            }
        }else{
            $rst[] = (object) array( 'id' => "", 'nombre' => JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_UNDMEDIDA_TITLE' ) );
        }

        return $rst;
    }
    
    /**
     * 
     * Retorna Informacion de funcionarios asociados a un determinada Unidad de Gestion
     * 
     * @param type $idUndGestion    Identificador Unidad de Gestion
     * @return type
     */
    public function getFuncionariosResponsables( $idUndGestion )
    {
        $objIndicador = new Indicadores();
        return $objIndicador->getResponsablesVariable( $idUndGestion );
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
            //  Programa
            case '1'; 
                $rst = $tbEntidad->getLstProgramas();
            break;
        
            //  Proyecto
            case '2'; 
                $rst = $tbEntidad->getLstProyectos();
            break;
            
            //  Contrato
            case '3'; 
                $rst = $tbEntidad->getLstContratos();
            break;
            
            //  PEI
            case '12'; 
                $rst = $tbEntidad->getLstObjetivosPEI();
            break;
            
            //  POA - UG
            case '13'; 
                $rst = $tbEntidad->getLstObjetivosPOA();
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
     * Retorno lista de dimensiones de un determinado enfoque
     * 
     * @param type $idEnfoque   Identificador de enfoque a filtrar
     * @return type
     */
    public function getDimensiones( $idEnfoque )
    {
        $db = JFactory::getDBO();
        
        //  Instacio la tabla Unidad de Medida
        $tbEnfoque = new jTableEnfoque( $db );
        $rst = $tbEnfoque->getLstDimension( $idEnfoque );
        
        if( count( $rst ) > 0 ){
            $rst[] = (object)array( 'id' => "0", 'nombre' => JText::_( 'COM_INDICADORES_INDICADOR_DIMENSION_TITLE' ) );
        }else{
            $rst = (object)array( 'id' => "0", 'nombre' => JText::_( 'COM_INDICADORES_SIN_REGISTROS' ) );
        }

        return $rst;
    }
    
    /**
     * 
     * Retorna Informacion de funcionarios asociados a un determinada Unidad de Gestion
     * 
     * @param type $idUndGestion    Identificador Unidad de Gestion
     * @return type
     */
    public function getResponsablesVariable( $idUndGestion )
    {
        $objIndicador = new Indicadores();
        return $objIndicador->getResponsablesVariable( $idUndGestion );
    }

    public function getLstIndicadoresPorEntidad( $idEntidad )
    {
        $objIndicador = new Indicadores();
        return $objIndicador->dtaIndicadoresEntidad( $idEntidad );
    }
    
    public function cierreIndicador( $idIndEntidad, $idIndicador )
    {
        //  sumatoria de valores ejecutados de un determinado indicador
        $objIndicador = new Indicadores();
                
        return $objIndicador->cierreIndicador( $idIndEntidad, $idIndicador );
    }
    
    public function __destruct()
    {
        return;
    }
}