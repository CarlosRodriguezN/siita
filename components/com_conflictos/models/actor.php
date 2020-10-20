<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla modelform library
jimport( 'joomla.application.component.modeladmin' );
JTable::addIncludePath( JPATH_BASE . DS . 'components' . DS . 'com_conflictos' . DS . 'tables' );

/**
 * Modelo tipo obra
 */
class ConflictosModelActor extends JModelAdmin
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
    public function getTable( $type = 'Actor', $prefix = 'ConflictosTable', $config = array( ) )
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
        $form = $this->loadForm( 'com_conflictos.actor', 'actor', array( 'control' => 'jform', 'load_data' => $loadData ) );
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
        $data = JFactory::getApplication()->getUserState( 'com_conflictos.edit.actor.data', array( ) );

        if( empty( $data ) ) {
            $data = $this->getItem();
        }

        return $data;
    }

    public function getActor()
    {
        $idActor = JRequest::getVar( "intId_act" );
        $oActor = false;
        if( $idActor != 0 ) {
            $tbActor = $this->getTable( 'Actor', 'ConflictosTable' );
            $oActor = $tbActor->getActor( $idActor );
            if( $oActor ) {
                // Asocio la lista de los actores
                $oActor->lstIncidencias = $this->_getIncidenciaActor( $idActor );
                //  Asocio la lista de los estados
                $oActor->lstLegitimidad = $this->_getLegitimidadActor( $idActor );
                //  Asocio la lista de los estados
                $oActor->lstFunciones = $this->_getFuncionesActor( $idActor );
//                //  Asocio la lista de las unidades territoriales del tema
//                $fuente->unidadTerrirorial = $this->_getUnidadTerritorial( $idActor );
            }
        }
        return $oActor;
    }

    /**
     * Retorna la lista detalles del los actores en un tema.
     * @param type $idTema      Identificador del tema
     * @return array
     */
    private function _getIncidenciaActor( $idTema )
    {
        $lstIncidencias = array( );
        $tbActorIncidencia = $this->getTable( 'ActorIncidencia', 'ConflictosTable' );
        $lstIncidencias = $tbActorIncidencia->getIncidenciaActor( $idTema );
        if( $lstIncidencias ) {
            foreach( $lstIncidencias AS $key => $incidencia ) {
                $incidencia->regActIncidencia = $key;
            }
        }
        return $lstIncidencias;
    }

    /**
     * Lista de estadoss de un tema 
     * @param type $idActor    Identificado de la fuente
     * @return array            Lista de legitimidades de la empresa
     */
    private function _getLegitimidadActor( $idActor )
    {
        $lstLegitimidad = array( );
        $tblegimidadActor = $this->getTable( 'ActorLegitimidad', 'ConflictosTable' );
        $lstLegitimidad = $tblegimidadActor->getLegitimidadActor( $idActor );
        if( $lstLegitimidad ) {
            foreach( $lstLegitimidad AS $key => $actor ) {
                $actor->regLegitimidad = $key;
            }
        }
        return $lstLegitimidad;
    }

    /**
     * Lista de unidades territoriales del conflicto
     * @return type
     */
    private function _getFuncionesActor( $idActor )
    {
        $lstFunciones = array( );
        if( $idActor != 0 ) {
            $tbFunActor = $this->getTable( 'ActorFuncion', 'ConflictosTable' );
            $lstFunciones = $tbFunActor->getFuncionesActor( $idActor );
            if( !empty($lstFunciones) ) {
                foreach( $lstFunciones AS $key => $funcion ) {
                    $funcion->regActFuncion = $key;
                }
            }
        }
        return $lstFunciones;
    }

    /**
     *  Gestiona el registro del tema 
     * @param object $actor     JSON con la información del ACTOR
     * @return int              Identificador del actor
     */
    public function saveActor( $actor )
    {
        $idActor = 0;
        $tbActor = $this->getTable( 'Actor', 'ConflictosTable' );
        if( $actor ) {
            $idActor = $tbActor->saveActor( $actor );
            if( $idActor != 0 ) {
                //Gestiono el registro de actores de un tema
                $this->_saveActorIncidencia( $actor->lstIncidencias, $idActor );
//                //Gestiono el registro de estados de un tema
                $this->_saveActorLegitimidad( $actor->lstLegitimidad, $idActor );
//                //Gestiona el registro de funciones de un actor
                $this->_saveFuncionesActor( $actor->lstFunciones, $idActor );
            }
        }
        return $idActor;
    }

    /**
     *  Gestiona el registro de la relacion incidenac con la fuente
     * @param type $lstInciden  Lista de incidencias de la fuente tema
     * @param type $idActor     Identificador del actor
     */
    private function _saveActorIncidencia( $lstInciden, $idActor )
    {
        if( $lstInciden ) {
            foreach( $lstInciden AS $incidencia ) {
                $tbActorIncidencia = $this->getTable( 'ActorIncidencia', 'ConflictosTable' );
                $idActorIncidencia = $tbActorIncidencia->saveActorIncidencia( $incidencia, $idActor );
            }
        }
    }

    /**
     * Gestiona el registro d elas fuentes de un tema 
     * @param array $lstLgidAct        Lista de legitimidades
     * @param int   $idActor           Identificador de actor
     */
    private function _saveActorLegitimidad( $lstLgidAct, $idActor )
    {
        if( count( (array)$lstLgidAct ) > 0 ) {
            $tbActorLegitimidad = $this->getTable( 'ActorLegitimidad', 'ConflictosTable' );
            foreach( $lstLgidAct AS $obj ) {
                if ( !($obj->published == 0 && $obj->idActLegi == 0) ) {
                    $idLA = $tbActorLegitimidad->saveActorLegitimidad( $obj, $idActor );
                }
            }
        }
    }

    /**
     * 
     *  Registro la unidad territorial
     * @param int   $idActor                 Identificador del tema.
     * @param array $lstFunciones  Lista de unidades Territoriales
     * @return int  $idUnidadTerritorial    Identificador de la unidad territorial
     */
    private function _saveFuncionesActor( $lstFunciones, $idActor )
    {
        if( $lstFunciones ) {
            foreach( $lstFunciones AS $funcion ) {
                $tbFuncionActor = $this->getTable( 'ActorFuncion', 'ConflictosTable' );
                $idFuncion = $tbFuncionActor->saveActorFuncion( $funcion, $idActor );
            }
        }
        return $idFuncion;
    }

    /*     * **** FUNCIONES UTILIZADAS EN EL AJAX *** */

    /**
     *  Retorna la lista de fuentes segun un tipo 
     * @param   int   $idTipoActor     Identificador del tipo de fuente
     * @return  array                   Lsta de fuentes segun el tipo
     */
    public function getActorsByTipo( $idTipoActor )
    {
        $tbActor = $this->getTable( 'Actor', 'ConflictosTable' );
        $lstActorsByTipo = $tbActor->getActorsByTipo( $idTipoActor );
        return $lstActorsByTipo;
    }
    
        /**
     *  Verifica si el registro se puede eliminar
     * @param type $reg
     * @return type
     */
    public function validoEliminar( $reg ){
        $tbActor = $this->getTable( 'Actor', 'ConflictosTable' );
        // op = 1 para verificar si har relaciones existentes (published = 1) con la fuente
        return $tbActor->validoEliminarActor($reg, 1); 
    }

    public function deleteActor( $id ){
        
        $tbActor = $this->getTable( 'Actor', 'ConflictosTable' );
        // op = 2 para verificar si el registro se pued eeliminar de forma fisica
        if ( $tbActor->validoEliminarActor($id, 2) ) {
            $result = $tbActor->eliminadoFisico( $id );
        } else {
            $result = $tbActor->eliminadoLogico( $id );
        }
        return $result;
    }
    

    ############################################################################
    #####################        Incidencias             #######################
    ############################################################################
    
    /**
     *  Retorna el ID de un nuevo registro de una Incidencia
     * @param type $data
     * @return type
     */
    public function saveIncidencia( $data )
    {
        $tbInc = $this->getTable( 'Incidencia', 'ConflictosTable' );
        $dataIncidencia = json_decode( $data );
        
        $dtaInc["intId_inc"]      = $dataIncidencia->intId_inc;
        $dtaInc["strNombre_inc"]  = $dataIncidencia->strNombre_inc;
        $dtaInc["published"]      = $dataIncidencia->published;
        
        $idInc = $tbInc->saveIncidencia( $dtaInc );
        
        if ( $idInc ) {
            $list = $tbInc->getListIncs();
        } else {
            $list = array();
        }
        
        return $list;
    }
    
    /**
     * 
     * @param type $idReg
     * @return type
     */
    public function deleteIncidencia( $idReg )
    {
        $tbInc = $this->getTable( 'Incidencia', 'ConflictosTable' );
        $result = false;
        
        if ( $tbInc->avalibleDeleteInc($idReg) ) {
            $del = false;
            
            if ( $tbInc->avalibleDeletePhysical($idReg) ) {
                $del= $tbInc->eliminadoFisicoInc($idReg);
            } else {
                $del = $tbInc->eliminadoLogicoInc($idReg);
            }
            
            if ( $del ) {
                $result = $tbInc->getListIncs();
            }
        }
        
        return $result;
    }
    
    ############################################################################
    #####################        Legitimidad             #######################
    ############################################################################
    
    /**
     *  Retorna el ID de un nuevo registro de una Incidencia
     * @param type $data
     * @return type
     */
    public function saveLegitimidad( $data )
    {
        $tbLeg = $this->getTable( 'Legitimidad', 'ConflictosTable' );
        $dataLegitimidad = json_decode( $data );
        
        $dtaLeg["intId_leg"]            = $dataLegitimidad->intId_leg;
        $dtaLeg["strDescripcion_leg"]   = $dataLegitimidad->strDescripcion_leg;
        $dtaLeg["published"]            = $dataLegitimidad->published;
        
        $idInc = $tbLeg->saveLegitimidad( $dtaLeg );
        
        if ( $idInc ) {
            $list = $tbLeg->getListLegs();
        } else {
            $list = array();
        }
        
        return $list;
    }
    
    /**
     * 
     * @param type $idReg
     * @return type
     */
    public function deleteLegitimidad( $idReg )
    {
        $tbLeg = $this->getTable( 'Legitimidad', 'ConflictosTable' );
        $result = false;
        
        if ( $tbLeg->avalibleDeleteLeg($idReg) ) {
            $del = false;
            
            if ( $tbLeg->avalibleDeletePhysical($idReg) ) {
                $del= $tbLeg->eliminadoFisicoLeg($idReg);
            } else {
                $del = $tbLeg->eliminadoLogicoLeg($idReg);
            }
            
            if ( $del ) {
                $result = $tbLeg->getListLegs();
            }
        }
        
        return $result;
    }
    
    ############################################################################
    #####################           Funcion              #######################
    ############################################################################
    
    /**
     *  Retorna el ID de un nuevo registro de una Funcion
     * @param type $data
     * @return type
     */
    public function saveFuncion( $data )
    {
        $tbFnc = $this->getTable( 'Funcion', 'ConflictosTable' );
        $dataLegitimidad = json_decode( $data );
        
        $dtaLeg["intId_fcn"]        = $dataLegitimidad->id;
        $dtaLeg["strNombre_fcn"]    = $dataLegitimidad->nombre;
        $dtaLeg["published"]        = $dataLegitimidad->published;
        
        $idInc = $tbFnc->saveFuncion( $dtaLeg );
        
        if ( $idInc ) {
            $list = $tbFnc->getListFunciones();
        } else {
            $list = array();
        }
        
        return $list;
    }
    
    /**
     * 
     * @param type $idReg
     * @return type
     */
    public function deleteFuncion( $idReg )
    {
        $tbFnc = $this->getTable( 'Funcion', 'ConflictosTable' );
        $result = false;
        
        if ( $tbFnc->avalibleDeleteFnc($idReg) ) {
            $del = false;
            
            if ( $tbFnc->avalibleDeletePhysical($idReg) ) {
                $del= $tbFnc->eliminadoFisicoFnc($idReg);
            } else {
                $del = $tbFnc->eliminadoLogicoFnc($idReg);
            }
            
            if ( $del ) {
                $result = $tbFnc->getListFunciones();
            }
        }
        
        return $result;
    }
    
}