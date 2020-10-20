<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla modelform library
jimport( 'joomla.application.component.modeladmin' );
jimport( 'ecorae.acls.permisos' );
require_once JPATH_BASE . DS . 'libraries' . DS . 'joomla' . DS . 'database' . DS . 'table' . DS . 'usergroup.php';
JTable::addIncludePath( JPATH_BASE . DS . 'components' . DS . 'com_mantenimiento' . DS . 'tables' );

/**
 * Modelo Plan Estratégico Institucional
 */

class MantenimientoModelCargoUG extends JModelAdmin
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
    public function getTable( $type = 'UnidadGestion', $prefix = 'MantenimientoTable', $config = array( ) )
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
        $form = $this->loadForm( 'com_mantenimiento.cargoug', 'cargoug', array( 'control' => 'jform', 'load_data' => $loadData ) );

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
        $data = JFactory::getApplication()->getUserState( 'com_mantenimiento.cargoug.cargoug.data', array( ) );

        if( empty( $data ) ) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     *  Retorna la lista de componentes de un deerminado autor y los permisos para un grupo dado
     * 
     * @param type $autor
     * @param type $grupo
     * @return type
     */
    public function getComponentes( $autor, $grupo ) 
    {
        $objPermisos = new Permisos();
        $result = $objPermisos->getComponentesPrm( $autor, $grupo );
        return $result;
    }

    /**
     * 
     * @param type $idUG
     * @return type
     */
    public function getinfoUG( $idUG )
    {
        $tbExtensions =  $this->getTable('UnidadGestion', 'MantenimientoTable');
        return $tbExtensions->getDataUG( $idUG );
    }
    
    /**
     * 
     */
    public function asignarCargoUG()
    {
        $dtaCargoUG = json_decode( JRequest::getvar( 'cargoUG' ) );
        $result = new stdClass();
        if ( $dtaCargoUG ) {
            
            $data["intId_ugc"]              = $dtaCargoUG->intId_ugc;
            $data["intCodigo_ug"]           = $dtaCargoUG->intCodigo_ug;
            $data["inpCodigo_cargo"]        = $dtaCargoUG->inpCodigo_cargo;
            $data["strDescripcion_cargo"]   = $dtaCargoUG->strDescripcion_cargo;
            $data["published"]              = 1;
            
            $data["intIdGrupo_cargo"]       = ( $dtaCargoUG->intId_ugc == 0  )   ? $this->_registrarGrupoCrg( $dtaCargoUG->strDescripcion_cargo )
                                                                                : $dtaCargoUG->intIdGrupo_cargo;
            
            if ( $dtaCargoUG->upd && !$this->actualizarNameGrupo($dtaCargoUG->intIdGrupo_cargo, $dtaCargoUG->strDescripcion_cargo)){
                $data["intIdGrupo_cargo"] = false;
            }
            
            if ( $data["intIdGrupo_cargo"] === false ) {
                $result->error = "Cargo ya existe";
            } else {
                $id = $this->guardarCargo( $data, $dtaCargoUG->lstPermisos);
                $result->data = ( $id ) ? $this->getCargoUG($id) : $id;
            }
        }
        return $result;
    }
    
    public function actualizarNameGrupo( $idCargo, $newName )
    {
        $tbGrupo =  $this->getTable( "UserGroup", "MantenimientoTable" );
        $result = false;
        if ( !$tbGrupo->existeGrupo($newName) ) {
            $tbGrupo->updNameGroup( $idCargo, $newName );
            $result = true;
        }
        return $result;
    }


    public function guardarCargo ( $data, $lstPermisos )
    {
        $tbCaroUG = $this->getTable("CargoUG", "MantenimientoTable");
        $idCargoUG = $tbCaroUG->guardarCargoUG( $data );
        $arrayPermisos = (array)$lstPermisos;
        if( $idCargoUG ){
            $objPermisos = new Permisos();
            $objPermisos->addPermisosGrpCom( $data["intIdGrupo_cargo"], $arrayPermisos );
        } 
        
        return $idCargoUG;
    }

    /**
     *  Crea un grupo de joomla para el nuevo cargo
     * @param type $descGrupo
     * @return type
     */
    private function _registrarGrupoCrg( $descGrupo )
    {
        $tbGrupo =  $this->getTable( 'Usergroup', 'JTable' );
        $data["id"]         = 0;
        $data["parent_id"]  = 6;
        $data["title"]      = "cr-" . $descGrupo;
        $result = $tbGrupo->save($data);
        if ( $result ){
            $result = $tbGrupo->id;
        }
        return $result;
    }
    
    /**
     *  Retorna un el objeto cargo de una unidad de gestion
     * @param type $id      Id del cargo
     * @return type
     */
    public function getCargoUG( $id )
    {
        $tbCaroUG = $this->getTable("CargoUG", "MantenimientoTable");
        return $tbCaroUG->getCargoUG($id);
    }
    
    /**
     * Eimina el registro de un cargo de unidad de gestion
     */
    public function eliminarCargoUG()
    {
        $dtaCargoUG = json_decode( JRequest::getvar( 'cargoUG' ) );
        $tbCaroUG = $this->getTable("CargoUG", "MantenimientoTable");
        $result = $tbCaroUG->eliminarCargoUG( $dtaCargoUG->intId_ugc );
        if ( $result ){
            $tbCaroUG->updFuncionariosSC( $dtaCargoUG->intId_ugc );
            $this->upsUsersGroups( $dtaCargoUG->intIdGrupo_cargo );
        }
        return $result;
    }
    
    /**
     *  Actualiza los registros de grupos relacinados a un cargo
     * @param type $grupo
     * @return type
     */
    public function upsUsersGroups( $grupo )
    {
        $tbGrupo =  $this->getTable( "UserGroup", "MantenimientoTable" );
        $result = $tbGrupo->eliminarGrupo( $grupo );
        if( $result ){
            $tbGrupo->updUserSG( $grupo );
        }
        return $result;
    }
}