<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla modelform library
jimport( 'joomla.application.component.modeladmin' );
JTable::addIncludePath( JPATH_BASE . DS . 'components' . DS . 'com_conflictos' . DS . 'tables' );

/**
 * Modelo tipo obra
 */
class ConflictosModelFuente extends JModelAdmin
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
    public function getTable( $type = 'Fuente', $prefix = 'ConflictosTable', $config = array( ) )
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
        $form = $this->loadForm( 'com_conflictos.fuente', 'fuente', array( 'control' => 'jform', 'load_data' => $loadData ) );
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
        $data = JFactory::getApplication()->getUserState( 'com_conflictos.edit.fuente.data', array( ) );

        if( empty( $data ) ) {
            $data = $this->getItem();
        }

        return $data;
    }

    public function getFuente()
    {
        $idFuente = JRequest::getVar( "intId_fte" );
        $fuente = false;
        if( $idFuente != 0 ) {
            $tbFuente = $this->getTable( 'Fuente', 'ConflictosTable' );
            $fuente = $tbFuente->getFuente( $idFuente );
            if( $fuente ) {
                // Asocio la lista de los actores
                $fuente->lstIncidencias = $this->_getIncidenciaFuente( $idFuente );
                //  Asocio la lista de los estados
                $fuente->lstLegitimidad = $this->_getLegitimidadFuente( $idFuente );
                //  Asocio la lista de las unidades territoriales del tema
                $fuente->unidadTerrirorial = $this->_getUnidadTerritorial( $idFuente );
            }
        }
        return $fuente;
    }

    /**
     * Retorna la lista detalles del los actores en un tema.
     * @param type $idTema      Identificador del tema
     * @return array
     */
    private function _getIncidenciaFuente( $idTema )
    {
        $lstIncidencias = array( );
        $tbFuntIncidecia = $this->getTable( 'FuenteIncidencia', 'ConflictosTable' );
        $lstIncidencias = $tbFuntIncidecia->getIncidenciaFuente( $idTema );
        if( $lstIncidencias ) {
            foreach( $lstIncidencias AS $key => $incidencia ) {
                $incidencia->regFuIncidencia = $key;
            }
        }
        return $lstIncidencias;
    }

    /**
     * Lista de estadoss de un tema 
     * @param type $idFuente    Identificado de la fuente
     * @return array            Lista de legitimidades de la empresa
     */
    private function _getLegitimidadFuente( $idFuente )
    {
        $lstLegitimidad = array( );
        $tblegimidadFuente = $this->getTable( 'FuenteLegitimidad', 'ConflictosTable' );
        $lstLegitimidad = $tblegimidadFuente->getLegitimidadFuente( $idFuente );
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
    private function _getUnidadTerritorial( $idFuente )
    {
        $unidadTerritorial = array( );
        if( $idFuente != 0 ) {
            $tbvwdpa = $this->getTable( 'vwdpa', 'ConflictosTable' );
            $unidadTerritorial = $tbvwdpa->getUndTerritorialesFuente( $idFuente );
        }
        return ($unidadTerritorial[0]) ? $unidadTerritorial[0] : false;
    }

    /**
     *  Gestiona el registro del tema 
     * @param type $fuente
     * @return type
     */
    public function saveFuente( $fuente )
    {
        $idFuente = 0;
        $tbFuente = $this->getTable( 'Fuente', 'ConflictosTable' );
        if( $fuente ) {
            $idFuente = (int) $tbFuente->saveFuente( $fuente );
            if( $idFuente != 0 ) {
                //Gestiono el registro de actores de un tema
                $this->_saveFuenteIncidencia( $fuente->lstIncidencias, $idFuente );
                //Gestiono el registro de estados de un tema
                $this->_saveFuenteLegitimidad( $fuente->lstLegitimidad, $idFuente );
                //Gestiona el registro de estados de un tema
                $this->_saveUnidadesTerritoriales( $fuente->unidadTerritorial, $idFuente );
            }
        }
        return $idFuente;
    }

    /**
     *  Gestiona el registro de la relacion incidenac con la fuente
     * @param type $lstInciden  Lista de incidencias de la fuente tema
     * @param type $idFuete     Identificador del tema
     */
    private function _saveFuenteIncidencia( $lstInciden, $idFuete )
    {
        if( $lstInciden ) {
            foreach( $lstInciden AS $incidencia ) {
                $tbFuenteIncidencia = $this->getTable( 'FuenteIncidencia', 'ConflictosTable' );
                $idFuenteIncidencia = $tbFuenteIncidencia->saveFuenteIncidencia( $incidencia, $idFuete );
            }
        }
    }

    /**
     * Gestiona el registro d elas fuentes de un tema 
     * @param array $lstFuentes         Lista de fuentes del tema
     * @param int   $idFuente           Identificador de tema
     */
    private function _saveFuenteLegitimidad( $lstFuentes, $idFuente )
    {
        if( $lstFuentes ) {
            foreach( $lstFuentes AS $fuente ) {
                $tbFuenteLegitimidad = $this->getTable( 'FuenteLegitimidad', 'ConflictosTable' );
                $idLegitimidadFuente = $tbFuenteLegitimidad->saveFuenteLegitimidad( $fuente, $idFuente );
            }
        }
    }

    /**
     *  Gestiona el registo de la lista de estados de un tema 
     * @param array $lstEstados  Lista de estados de un tema
     * @param int   $idTema      Identificador del tema
     */
    private function _saveEstadosTema( $lstEstados, $idTema )
    {
        if( $lstEstados ) {
            foreach( $lstEstados AS $estTema ) {
                $tbActorDetalle = $this->getTable( 'EstadoTema', 'ConflictosTable' );
                $idEstTema = $tbActorDetalle->saveEstadoTema( $estTema, $idTema );
            }
        }
    }

    /**
     * 
     *  Registro la unidad territorial
     * @param int   $idTema                 Identificador del tema.
     * @param array $unidadesTerritoriales  Lista de unidades Territoriales
     * @return int  $idUnidadTerritorial    Identificador de la unidad territorial
     */
    private function _saveUnidadesTerritoriales( $unidadesTerritoriales, $idTema )
    {
        if( count( $unidadesTerritoriales ) > 0 )
            $tbUndTer = $this->getTable( 'UnidadTerritorial', 'ConflictosTable' );
        $tbUndTer->deleteUnidadesTerritoriales( $idTema );
        if( $unidadesTerritoriales ) {
            foreach( $unidadesTerritoriales AS $unidadTerritorial ) {
                $tbUnidadTerritorial = $this->getTable( 'UnidadTerritorial', 'ConflictosTable' );
                $idUnidadTerritorial = $tbUnidadTerritorial->saveUnidadTerritorial( $idTema, $unidadTerritorial );
            }
        }
        return $idUnidadTerritorial;
    }

    /*     * **** FUNCIONES UTILIZADAS EN EL AJAX *** */

    /**
     *  Retorna la lista de fuentes segun un tipo 
     * @param   int   $idTipoFuente     Identificador del tipo de fuente
     * @return  array                   Lsta de fuentes segun el tipo
     */
    public function getFuentesByTipo( $idTipoFuente )
    {
        $tbFuente = $this->getTable( 'Fuente', 'ConflictosTable' );
        $lstFuentesByTipo = $tbFuente->getFuentesByTipo( $idTipoFuente );
        return $lstFuentesByTipo;
    }

    /**
     *  Verifica si el registro se puede eliminar
     * @param type $reg
     * @return type
     */
    public function validoEliminar( $reg ){
        $tbFuente = $this->getTable( 'Fuente', 'ConflictosTable' );
        // op = 1 para verificar si har relaciones existentes (published = 1) con la fuente
        return $tbFuente->validoEliminarFuente($reg, 1); 
    }

    public function deleteFuente( $id ){
        
        $tbFuente = $this->getTable( 'Fuente', 'ConflictosTable' );
        // op = 2 para verificar si el registro se pued eeliminar de forma fisica
        if ( $tbFuente->validoEliminarFuente($id, 2) ) {
            $result = $tbFuente->eliminadoFisico( $id );
        } else {
            $result = $tbFuente->eliminadoLogico( $id );
        }
        
        return $result;
    }
    
    ############################################################################
    ###################         TIPOS DE FUENTE           ######################
    ############################################################################
    
    /**
     *  Retorna el ID de un nuevo registro de un tipo de fuente
     * @param type $tpoFuente
     * @return type
     */
    public function saveTipoFuente( $tpoFuente )
    {
        $tbTpoFnt = $this->getTable( 'TipoFuente', 'ConflictosTable' );
        $dtaTpoFuente = json_decode( $tpoFuente );
        
        $data["intId_tf"]           = $dtaTpoFuente->intId_tf;
        $data["strDescripcion_tf"]  = $dtaTpoFuente->strDescripcion_tf;
        $data["published"]          = $dtaTpoFuente->published;
        
        $idTpoFnt = $tbTpoFnt->saveTipoFuente( $data );
        
        if ( $idTpoFnt ) {
            $list = $tbTpoFnt->getTiposFuente();
        } else {
            $list = array();
        }
        
        return $list;
    }
    
    
    public function deleteTipoFuente( $idReg )
    {
        $tbTpoFnt = $this->getTable( 'TipoFuente', 'ConflictosTable' );
        $result = false;
        
        if ( $tbTpoFnt->avalibleDeleteTF( $idReg, 1) ) {
            $del = false;
            if ( $tbTpoFnt->avalibleDeleteTF( $idReg, 2 ) ) {
                $del= $tbTpoFnt->deleteFisicoTF($idReg);
            } else {
                $del = $tbTpoFnt->deleteLogicoTF($idReg);
            }
            
            if ( $del ) {
                $result = $tbTpoFnt->getTiposFuente();
            }
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
        $dtaTpoFuente = json_decode( $data );
        
        $dtaInc["intId_inc"]      = $dtaTpoFuente->intId_inc;
        $dtaInc["strNombre_inc"]  = $dtaTpoFuente->strNombre_inc;
        $dtaInc["published"]      = $dtaTpoFuente->published;
        
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
        $result = array();
        
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
        
        if ( $tbLeg->avalibleDeleteLeg( $idReg, 1 ) ) {
            $del = false;
            
            if ( $tbLeg->avalibleDeleteLeg( $idReg, 2 ) ) {
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
    
}