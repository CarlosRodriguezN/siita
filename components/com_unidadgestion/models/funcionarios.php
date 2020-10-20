<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla modelform library
jimport( 'joomla.application.component.modeladmin' );
jimport( 'ecorae.objetivos.objetivo.objetivos' );
jimport( 'ecorae.unidadgestion.funcionario' );

JTable::addIncludePath( JPATH_BASE . DS . 'components' . DS . 'com_unidadgestion' . DS . 'tables' );

/**
 * Modelo Plan Estratégico Institucional
 */

class UnidadGestionModelFuncionarios extends JModelAdmin
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
    public function getTable( $type = 'Funcionario', $prefix = 'UnidadGestionTable', $config = array( ) )
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
    public function getForm( $data = array( ), $loadData = true )
    {
        // Get the form.
        $form = $this->loadForm( 'com_unidadgestion.funcionarios', 'funcionarios', array( 'control' => 'jform', 'load_data' => $loadData ) );

        if( empty( $form ) ) {
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
        $data = JFactory::getApplication()->getUserState( 'com_unidadgestion.edit.funcionarios.data', array( ) );

        if( empty( $data ) ) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     *  Retorna la lista de objetivos de una funcionario de acuaredo al plan vigente del sistema
     * @param type $idFnc
     * @param type $anioVigente
     * @return type
     */
    public function getObjetivosPln( $idFnc, $anioVigente )
    {
        $oFnc = new Funcionario();
        $entidadFnc = $oFnc->getEntidadFnc( $idFnc );
        $plnVigenteFnc = $this->getPlnFnc( $entidadFnc, $anioVigente );
        $oObjetivos = new Objetivos();
        $lstObjetivos = $oObjetivos->getViewLstObjetivos( $plnVigenteFnc->id, 2, $entidadFnc );
        
        return $lstObjetivos;
        
    }
    
    /**
     *  Obtiene la data del plan vigente del fucncionario de acuerdo al plan vigente del sistema
     * @param type $entidad
     * @param type $anio
     * @return type
     */
    public function getPlnFnc( $entidad, $anio )
    {
        $tbPlan = $this->getTable( 'Plan', 'UnidadGestionTable' );
        $plan = $tbPlan->getPlan( $entidad, $anio );
        return $plan;
    }

    /**
     *  Retorna la informacion de un fucnionario
     * @param type $idFnc
     */
    public function getFuncionario( $idFnc )
    {
        $tbFnc = $this->getTable( 'Funcionario', 'UnidadGestionTable' );
        $data = $tbFnc->getDataFuncionario( $idFnc );
        return $data;
    }
    
}