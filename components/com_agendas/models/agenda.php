<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

JTable::addIncludePath(JPATH_BASE . DS . 'components' . DS . 'com_agendas' . DS . 'tables');
 
/**
 * Modelo tipo obra
 */
class AgendasModelAgenda extends JModelAdmin
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
    public function getTable( $type = 'Agenda', $prefix = 'AgendasTable', $config = array() ) 
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
        $form = $this->loadForm( 'com_agendas.agenda', 'agenda', array( 'control' => 'jform', 'load_data' => $loadData ) );
        
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
        $data = JFactory::getApplication()->getUserState( 'com_agendas.edit.agenda.data', array() );
        
        if( empty( $data ) ){
            $data = $this->getItem();
        }
        
        return $data;
    }
 
    /**
     *  Retorna la lista de detalles de una determinada agenda
     * @param type $idAgenda        Id de la agenda a obtener los detalles
     */
    public function getDetallesAgd( $idAgenda )
    {
        $tbDetalleAgd = $this->getTable( 'DetalleAgd', 'AgendasTable' );
        $lstDetales = $tbDetalleAgd->getLstDetallesAgd( $idAgenda );
        if ( !empty($lstDetales) ){
            foreach ($lstDetales as $key=>$detalle){
                $detalle->registroDtll = $key;
            }
        }
        return $lstDetales;
    }
    
    /**
     *  Retorna la estructura de una determinada agenda
     * @param type $idAgenda        Id de la agenda a obtener la estructura
     */
    public function getEstructuraAgd( $idAgenda )
    {
        $tbEstructuraAgd = $this->getTable( 'EstructuraAgd', 'AgendasTable' );
        $lstEstructura = $tbEstructuraAgd->getEstructuraAgd( $idAgenda );
        if ( !empty($lstEstructura) ){
            foreach ($lstEstructura as $key=>$estructura){
                $estructura->registroEtr = $key;
                $estructura->avalibleDel = $tbEstructuraAgd->avalibleDel( $estructura->idEstructura );
                
            }
        }
        return $lstEstructura;
    }
    
    /**
     *  Retorna la lista de los Itmes de acuerdo a la estructura de la agenda 
     * @param type $estructuraAgd       Estrustura de la agenda
     * @return type
     */
    public function getItemsAgd( $estructuraAgd )
    {
        $indice = 0;
        $lastEtr = $estructuraAgd[count($estructuraAgd)-1];
        $lstItems = $this->_getItems( $estructuraAgd[$indice], 0, 0 );
        if ( $estructuraAgd[$indice]->idEstructura != $lastEtr->idEstructura && !empty($lstItems)){
            $indice++;
            $this->_addItemHijos($lstItems, $estructuraAgd, $indice ); 
        }
        return $lstItems;
    }
    
    /**
     *  Retorna la lista de items de una dterminada estructra y su owner
     * @param type $estructura          Id de la estructura
     * @param type $regOwner            Id de registro del item padre   
     * @param type $owner               Id del owner    
     * @return type
     */
    private function _getItems( $estructura, $regOwner, $owner = 0 )
    {
        $tbItemsAgd = $this->getTable( 'ItemsAgd', 'AgendasTable' );
        $lstItemsEtr = $tbItemsAgd->getItemsByEtr( $estructura->idEstructura, $owner );
       if ( !empty($lstItemsEtr) ){
            foreach ($lstItemsEtr as $key=>$item){
                $item->registroItem = $key;
                $item->registroEtr = $estructura->registroEtr;
                $item->registroOwner = $regOwner;
                $item->itemsHijos = array();
            }
        }
        return $lstItemsEtr;
    }
    
    /**
     *  Funcion recursiva que obtiene la lista de items hijos de una determinado lista de items owner
     * @param type $lstItems                Lista de items owner
     * @param type $estructuraAgd           Estructura de la agenda
     * @param type $index                   Indice que indica el nivel de la estructura
     */
    private function _addItemHijos($lstItems, $estructuraAgd, $index )
    {
        $estructura = $estructuraAgd[$index];
        foreach ($lstItems AS $item){
            $item->itemsHijos = $this->_getItems( $estructura, $item->registroItem, $item->idItem );
            if ($estructuraAgd[$index]->idEstructura != $estructuraAgd[count($estructuraAgd)-1]->idEstructura && !empty($item->itemsHijos)){
                $index++;
                $this->_addItemHijos($item->itemsHijos, $estructuraAgd, $index );
            } 
        }
    }
    
    /**
     *  Guasrda toda la data relacionada con un a agenda
     * @return type
     */
    public function guardarAgenda()
    {
        $idAgenda = $this->_registrarAgenda( JRequest::getvar( 'dtaFrm' ) );
        if (!empty($idAgenda) && is_numeric($idAgenda)){
            $lstDetalles = $this->_registrarDetalles( JRequest::getvar( 'lstDetalles' ), $idAgenda );
            $lstEstructura = $this->_registrarEstructura( JRequest::getvar( 'lstEstructura' ), $idAgenda );
            
            if (!empty($lstEstructura)){
                $lstItems = $this->_registrarItems( JRequest::getvar( 'lstItems' ), 0, $lstEstructura, $idAgenda );
            }
        }
        
        return $idAgenda;
    }
 
    /**
     *  Registra los datos generales de un agenda
     * @param type $dtaFrm      Data del formulario
     * @return type
     */
    private function _registrarAgenda( $dtaFrm )
    {
        $dtaAgenda = json_decode($dtaFrm);
        $tbAgd =  $this->getTable('Agenda', 'AgendasTable');

        $data["intIdAgenda_ag"]     = $dtaAgenda->idAgenda;
        $data["strDescripcion_ag"]  = $dtaAgenda->descripcionAgd;
        $data["dteFechaInicio_ag"]  = $dtaAgenda->fechaInicio;
        $data["dteFechaFin_ag"]  = $dtaAgenda->fechaFin;
        $data["published"]          = $dtaAgenda->published;

        $idAgd = $tbAgd->registrarAgenda($data);
        
        return $idAgd;
    }
    
    /**
     *  Registra los detalles especificos de una determinada agenda
     * @param type $lstDetalles     Lista de detalles
     * @param type $idAgenda        Id de la agenda
     * @return type
     */
    private function _registrarDetalles( $lstDetalles, $idAgenda )
    {
        $lstDtall = json_decode($lstDetalles);
        $resul = array();
        
        if ($lstDtall) {
            foreach ( $lstDtall AS $detalle ){
                $idDetalle = $this->saveDetalle( $detalle, $idAgenda );
                $resul[] = $idDetalle;
            }
        }
        
        return $resul;
    }
    
    /**
     *  Retorna el ID de un determinado detalle de una agenda
     * @param type $dtaDtall        Data general de un detalle
     * @param type $idAgd           Id de la agenda
     * @return type
     */
    public function saveDetalle( $dtaDtall, $idAgd )
    {
        $tbDetalle = $this->getTable('DetalleAgd', 'AgendasTable');
        $idDtall = null;
        
        if ( $dtaDtall->published ) {
            $data["intIdDetalle_dt"]    = $dtaDtall->idDetalle;
            $data["intIdAgenda_ag"]     = $idAgd;
            $data["strCampo_dt"]        = $dtaDtall->strCampoDtll;
            $data["strValorCampo_dt"]   = $dtaDtall->strValorDtll;
            $data["published"]          = $dtaDtall->published;
            
            $idDtall = $tbDetalle->registrarDetalle( $data );
        } else if ( $dtaDtall->idDetalle != 0) {
            $idDtall = ($tbDetalle->deleteLogicalDetalle( $dtaDtall->idDetalle ) ) ? array() : "Error al eliminar detalle";
        }
        return $idDtall;
    }
    
    /**
     *  Registra la estructura de una determinada agenda
     * @param type $lstEstructura       Lista de la estructucra
     * @param type $idAgenda            Id de la agenda
     * @return type
     */
    private function _registrarEstructura( $lstEstructura, $idAgenda )
    {
        $lstEstructura = json_decode($lstEstructura);
        $resul = array();
        
        foreach ( $lstEstructura AS $estructura ){
            if (!is_numeric($estructura->idPadreEtr)){
                $estructura->idPadreEtr = $this->getIdOwnerEtr( $estructura->idPadreEtr, $lstEstructura );
            }
            $idEstructura = $this->saveEstructura( $estructura, $idAgenda );
            if (is_numeric($idEstructura) && !empty($idEstructura)){
                $estructura->idEstructura = (int)$idEstructura;
                $resul[] = $estructura;
            }
        }
        
        
        return $resul;
    }
    
    /**
     *  Retorna el ID de un elemendo de la estructura de una agenda
     * @param type $dtaEstr         Data general de un elemento
     * @param type $idAgd           Id de la agenda
     * @return type
     */
    public function saveEstructura( $dtaEstr, $idAgd )
    {
        $tbEstr = $this->getTable('EstructuraAgd', 'AgendasTable');
        $idEstr = null;
        
        if ( $dtaEstr->published == 1 ) {
            $data["intIdEstructura_es"]         = $dtaEstr->idEstructura;
            $data["intIdAgenda_ag"]             = $idAgd;
            $data["intIdEstuctura_padre_es"]    = $dtaEstr->idPadreEtr;
            $data["strDescripcion_es"]          = $dtaEstr->descripcionEtr;
            $data["intNivel"]                   = $dtaEstr->nivelEtr;
            $data["published"]                  = $dtaEstr->published;
            
            $idEstr = $tbEstr->registrarEstructura( $data );
        } else if ( $dtaEstr->idEstructura != 0) {
            $idEstr = ($tbEstr->deleteLogicalEstructura( $dtaEstr->idEstructura ) ) ? array() : "Error al eliminar estructura";
        }
        return $idEstr;
    }
    
    /**
     *  Recupera el ID de un registro owner para un nuevo registro
     * @param type $idReg           Id del registro del Owner
     * @param type $lstEstr         Lista de elementos de la estructura
     * @return type
     */
    public function getIdOwnerEtr( $idReg, $lstEstr )
    {
        $id = 0;
        $owner = explode("-", $idReg);
        foreach ( $lstEstr As $estr ){
            if ( (int)$estr->registroEtr == (int)$owner[1] ) {
                $id = $estr->idEstructura;
            }
        }
        return $id;
    }
    
    /**
     *  Registra los items de acuerdo a una determinada estructura de una agenda
     * @param type $lstItems            Lista de Items
     * @param type $idOwner             Id del Ouner al que pertnece l item
     * @param type $lstEstr             Lista de la estructucra
     * @param type $idAgenda            Id de la agenda
     * @return type
     */
    private function _registrarItems( $lstItems, $idOwner ,$lstEstr, $idAgenda )
    {
        $dtaLstItems = ($idOwner == 0) ? json_decode($lstItems) : $lstItems;
        $resul = array();
        
        foreach ( $dtaLstItems AS $item ){
            if ($item->idEstructura == 0){
                $item->idEstructura = $this->getIdEstructura( $item->registroEtr, $lstEstr );
            }
            
            $idItem = $this->saveItem( $item, $idAgenda, $idOwner );
            if ( is_numeric($idItem) && !empty($idItem) ){
                $item->idItem = (int)$idItem;
                if (count($item->itemsHijos) > 0){
                    $item->itemsHijos = $this->_registrarItems( $item->itemsHijos, (int)$idItem, $lstEstr, $idAgenda );
                }
                $resul[] = $item;
            }
        }
        
        return $resul;
    }
    
    /**
     *  Retorna el ID de un item de una agenda
     * @param type $item            Data general del item
     * @param type $idAgd           Id de la agenda
     * @param type $idOwner         Id del item Padre
     * @return type
     */
    public function saveItem( $item, $idAgd, $idOwner )
    {
        $tbItem = $this->getTable('ItemsAgd', 'AgendasTable');
        $idItem = null;
        
        if ( $item->published == 1 ) {
            $data["intIdItem_it"]       = $item->idItem;
            $data["intIdAgenda_ag"]     = $idAgd;
            $data["intIdEstructura_es"] = $item->idEstructura;
            $data["intIdItem_padre_it"] = ($item->idOwner == 0) ? $idOwner : $item->idOwner;
            $data["strDescripcion_it"]  = $item->descripcionItem;
            $data["strNivel_it"]        = $item->nivelItem;
            $data["published"]          = $item->published;
            
            $idItem = $tbItem->registrarItem( $data );
        } else if ( $item->idItem != 0) {
            $idItem = ($tbItem->deleteLogicalItem( $item->idItem ) ) ? array() : "Error al eliminar item";
        }
        return $idItem;
    }
    
    
    /**
     *  Recupera el ID de estructura de un retermiado Id de registro
     * @param type $idReg           Id del registro 
     * @param type $lstEstr         Lista de elementos de la estructura
     * @return type
     */
    public function getIdEstructura( $idReg, $lstEstr )
    {
        $id = 0;
        foreach ( $lstEstr As $estr ){
            if ( (int)$estr->registroEtr == $idReg ) {
                $id = $estr->idEstructura;
            }
        }
        return $id;
    }
    
    public function avalibleUpdEst ( $idAgenda )
    {
        $tbDetalleAgd = $this->getTable( 'Agenda', 'AgendasTable' );
        return $tbDetalleAgd->avalibleUpdEstAgd( $idAgenda );
    }
    
    /**
     *  Retorna True si se puede eliminar la agenda, caso contratio retorna False
     * @param type $idAgenda
     * @return type
     */
    public function availableDelAgd ( $idAgenda )
    {
        $tbDetalleAgd = $this->getTable( 'Agenda', 'AgendasTable' );
        return $tbDetalleAgd->canDeleteAgd( $idAgenda );
    }
 
    /**
     * 
     * @param type $id
     * @return type
     */
    public function eliminarAgenda( $id ){
        $result = false;
        if ( $this->availableDelAgd ( $id ) ) {
            $tbDetalleAgd = $this->getTable( 'Agenda', 'AgendasTable' );
            $result = $tbDetalleAgd->deleteAgenda( $id );
            $tbDetalleAgd->deleteEstructuraAgd( $id );
            $tbDetalleAgd->deleteDetalleAgd( $id );
            $tbDetalleAgd->deleteItemsAgd( $id );
        }
        return $result;
    }
    
}