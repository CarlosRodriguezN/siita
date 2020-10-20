<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

JTable::addIncludePath( JPATH_BASE . DS . 'components' . DS . 'com_proyectos' . DS . 'tables' );

/**
 * Modelo Fase
 */
class ProyectosModelPlanificacion extends JModelAdmin
{
    /**
    * Returns a reference to the a Table object, always creating it.
    *
    * @param	type	The table type to instantiate
    * @param	string	A prefix for the table class name. Optional.
    * @param	array	Configuration array for model. Optional.
    * @return	JTable	A database object
    * @since	1.6
    * 
    */
    public function getTable( $type = 'Planificacion', $prefix = 'ProyectosTable', $config = array() ) 
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
        $form = $this->loadForm( 'com_proyectos.planificacion', 'planificacion', array( 'control' => 'jform', 'load_data' => $loadData ) );
        $idProyecto = JRequest::getVar( 'idProyecto' );
        
        if( $idProyecto > 0 ){
            //  Instancio la tabla Proyecto
            $tbProyecto = $this->getTable( 'Proyecto', 'ProyectosTable' );
            
            //  Obtengo informacion general del proyecto
            $dataProyecto = $tbProyecto->getDataProyecto( $idProyecto );

            if( $dataProyecto ){
                //  Seteo Informacion general del Proyecto
                $form->setFieldAttribute( 'strPrograma', 'default', $dataProyecto[0]->nomPrograma );
                $form->setFieldAttribute( 'strNombreProyecto', 'default', $dataProyecto[0]->nomProyecto );
                $form->setFieldAttribute( 'fchInicio', 'default', $dataProyecto[0]->fchInicio );
                $form->setFieldAttribute( 'fchFin', 'default', $dataProyecto[0]->fchFin );
                $form->setFieldAttribute( 'duracionProyecto', 'default', $dataProyecto[0]->duracion.' '.$dataProyecto[0]->undMedida );
                $form->setFieldAttribute( 'responsable', 'default', $dataProyecto[0]->responsable );   
            }
        }
        
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
        $data = JFactory::getApplication()->getUserState( 'com_proyectos.edit.planificaciones.data', array() );
        
        if( empty( $data ) ){
            $data = $this->getItem();
        }

        return $data;
    }
    
    /**
     * 
     * Retorno una lista de indicadores pertenecientes a un determinado proyecto
     * 
     * @return type
     */
    public function getLstDataIndicadores()
    {
        //  Instancio la tabla Indicadores
        $tbIndicadores = $this->getTable( 'Indicador', 'ProyectosTable' );
        return $tbIndicadores->getDataIndicadores( JRequest::getVar( 'idProyecto' ) );
    }

    
    /**
     * 
     * Gestiono el retorno de las variables de un determinado indicador
     * 
     * @param type $idIndicador
     */
    public function getVariablesInd( $idProyecto, $idIndicador )
    {
        $tbVariable = $this->getTable( 'Variable', 'ProyectosTable' );
        
        return $tbVariable->getLstVariablesIndicador( $idProyecto, $idIndicador );
    }
    
    /**
     *
     * Registro la planificacion asignada a una determinada variable
     * 
     * @return boolean 
     */
    public function registroPlanificacion()
    {
        $tbPlanificacion = $this->getTable( 'Planificacion', 'ProyectosTable' );
        
        //  Tranformo a objetoPHP la lista de variables planificadas en formato JSon
        $infoVP = json_decode( JRequest::getVar( 'dataPlanificacion' ) );

        try{
            //  Recorro la lista de planificaciones realizadas a las variables
            foreach( $infoVP as $lstPlanificacion ){
                //  Registro la planificacion asignada a una determinada variable
                $tbPlanificacion->setPlanificacion( $lstPlanificacion->idIndicador, $lstPlanificacion->idVariable, $lstPlanificacion->planificacion );                    
            }
            
            return true;
        }  catch ( Exception $e ){
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
            
            return false;
        }
    }
    
    
    
    /**
     *
     * Registro la planificacion asignada a una determinada variable
     * 
     * @return boolean 
     */
    public function registroSeguimiento()
    {
        $tbSeguimiento = $this->getTable( 'Seguimiento', 'ProyectosTable' );
        
        //  Tranformo a objetoPHP la lista de variables planificadas en formato JSon
        $infoVP = json_decode( JRequest::getVar( 'dataPlanificacion' ) );

        try{
            //  Recorro la lista de planificaciones realizadas a las variables
            foreach( $infoVP as $lstPlanificacion ){
                //  Registro el seguimiento a una determinada variable
                $tbSeguimiento->setSeguimiento( $lstPlanificacion->idIndicador, $lstPlanificacion->idVariable, $lstPlanificacion->seguimiento );
            }
            
            return true;
        }  catch ( Exception $e ){
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
            
            return false;
        }
    }
    
    
    
    
    
    
    
    /**
     * 
     * Retorno una lista de variables asociadas a un indicador en un determinado 
     * proyecto
     * 
     * @param type $idProyecto  Identificador del proyecto
     * 
     * @return type
     * 
     */
    public function lstIndicadorVariables( $idProyecto )
    {
        $tbPlanificacion = $this->getTable();
        return $tbPlanificacion->lstVariables( $idProyecto );
    }
    
    /**
     *
     * Retorna una lista de Variables planificadas en un determinado proyecto
     * 
     * @param type $idProyecto  Identificador del proyecto
     * 
     * @return type 
     */
    public function lstVariablesPlanificadas( $idProyecto )
    {
        $lstVarPlanificadas = array();
        
        $tbPlanificacion = $this->getTable( 'Planificacion', 'ProyectosTable' );
        $tbSeguimiento =  $this->getTable( 'Seguimiento', 'ProyectosTable' );
        
        //  Lista de variables relacionadas con un indicador en un determinado proyecto
        $lstVariables = $tbPlanificacion->lstVariables( $idProyecto );
        
        if( $lstVariables ){
            foreach( $lstVariables as $variable ){
                $dataVariable["idVariable"] = $variable->idVariable;
                $dataVariable["idIndicador"] = $variable->idIndicador;

                $dataVariable["nombre"] = $variable->nombre;
                $dataVariable["tipoVariable"] = $variable->tipoVariable;

                //  Obtengo informacion de planificacion de una determinada variable
                $dataVariable["planificacion"] = $tbPlanificacion->lstPlanificacionVariables( $idProyecto, $variable->idIndicador, $variable->idVariable );

                //  Obtengo informacion de seguimiento de una determinada variable
                $dataVariable["seguimiento"] = $tbSeguimiento->lstSeguimientoVariables( $idProyecto, $variable->idIndicador, $variable->idVariable );

                $lstVarPlanificadas[] = $dataVariable;
            }
        }
        
        return $lstVarPlanificadas;
    }
}