<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

JTable::addIncludePath(JPATH_BASE . DS . 'components' . DS . 'com_mantenimiento' . DS . 'tables');
 
/**
 * Modelo tipo obra
 */
class MantenimientoModelCargoFnc extends JModelAdmin
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
    public function getTable( $type = 'CargoFnc', $prefix = 'MantenimientoTable', $config = array() ) 
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
        $form = $this->loadForm( 'com_mantenimiento.cargofnc', 'cargofnc', array( 'control' => 'jform', 'load_data' => $loadData ) );
        
        //  Seteo el parent ID del grupo 
        $idGrupoCarg = $form->getField( 'intIdGrupo_cargo' )->value;
        if ( (int)$idGrupoCarg != 0 ){
            $form->setValue( 'nivel_cargo', null, $this->getParentIdGrp($idGrupoCarg) );
        } else {
            $form->setValue( 'nivel_cargo', null, 11 ); //  11 ID de grupo padre para administracion de unidad de gestion
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
        $data = JFactory::getApplication()->getUserState( 'com_mantenimiento.edit.cargofnc.data', array() );
        
        if( empty( $data ) ){
            $data = $this->getItem();
        }
        
        return $data;
    }
    
    /**
     * 
     */
    public function getLstCargos()
    {
        $tbCargo =  $this->getTable('CargoFnc', 'MantenimientoTable');
        $lstCargos = $tbCargo->getCargosFnc();
        if ( !empty($lstCargos) ){
            foreach ( $lstCargos AS $key=>$crg ){
                $crg->idReg = $key;
            }
        }
        return $lstCargos;
    }
    
    /**
     *  Guarda toda la data relacionada con un cargo
     * @return type
     */
    public function guardarCargos()
    {
        $dtaCargos = json_decode( JRequest::getvar( 'lstCargos' ) );
        
        if ( !empty($dtaCargos) ){
            $tbCargo =  $this->getTable('CargoFnc', 'MantenimientoTable');
            $lstCargosSave = array();
            foreach ( $dtaCargos As $cargo ){
                if ( $cargo->published == 1 ){
                    $data["inpCodigo_cargo"]        = $cargo->idCargo;
                    $data["strNombre_cargo"]        = $cargo->nombreCargo;
                    $data["published"]              = $cargo->published;

                    $idCargo = $tbCargo->registrarCargo( $data );
                    if ( $idCargo ) {
                        $cargo->idCargo = $idCargo;
                        $lstCargosSave[] = $cargo;
                    }
                } else if ( $cargo->idCargo != 0 ) {
                    $tbCargo->eliminarCargo( $cargo->idCargo );
                }
            }
        }
        
        return $lstCargosSave;
    }
    
    public function validarDelCargo()
    {
        $tbCargo =  $this->getTable('CargoFnc', 'MantenimientoTable');
        $id = json_decode( JRequest::getvar( 'idCrg' ) );
        return $tbCargo->validateDelete( $id );
    }
    
    /**
     *  Gestiona la eliminacion de un cargo y su grupo relacionado
     * @return type
     */
    function eliminarCargo()
    {
        $tbCargo =  $this->getTable('CargoFnc', 'MantenimientoTable');
        $idCargo = JRequest::getvar( 'idCrg' );
        $idGrupo = JRequest::getvar( 'idGrp' );
        
        // verificar si esxiten relaciones con ese cargo 
        if ( $tbCargo->getFncByCargo( $idCargo ) ) {
            $res = $tbCargo->eliminacionLogica( $idCargo );
        } else {
            $res = $tbCargo->eliminarCargo( $idCargo );
        }
            
        //  Cambia de grupo y cargo a los funconarios existentes con el cargo eliminado
        $idGrpSinCrg = (int) $tbCargo->getSinGrupo();
        if ( $idGrpSinCrg ){
            $tbCargo->changeGrupoFuncionarios($idGrupo, $idGrpSinCrg);
        }
        $tbCargo->changeCargoFuncionarios($idCargo, 0);
        
        //  eliminar el grupo de joomla para el cargo
        $tbCargo->eliminarGroup($idGrupo);
        
        return $res;
    }

    public function getParentIdGrp( $idGroCargo )
    {
        $tbCargo =  $this->getTable('CargoFnc', 'MantenimientoTable');
        return $tbCargo->getGrupoPadre( (int)$idGroCargo );
    }
    
    
}