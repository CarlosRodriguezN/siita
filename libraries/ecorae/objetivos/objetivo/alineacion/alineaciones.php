<?php

//  
jimport( 'ecorae.objetivos.objetivo.alineacion.alineacion' );
jimport( 'ecorae.objetivos.objetivo.alineacion.estructura' );
jimport( 'ecorae.objetivos.objetivo.alineacion.items' );

class Alineaciones
{

    /**
     * 
     * @param type $lstAlineaciones
     * @param type $idObjetivo
     */
    public function saveAlineaciones( $lstAlineaciones, $idObjetivo )
    {
        if(count( $lstAlineaciones ) > 0) {
            foreach ( $lstAlineaciones as $alineacion ){
                $oAlineacion = new Alineacion();
                $oAlineacion->saveAlineacion( $alineacion, $idObjetivo );
            }
        }
    }

    /**
     * Retorna la lista de las alineacioned de un objetivo
     * @param int $idObjetivo   Identificador del Objetivo
     * @return boolean
     */
    public function getLstAlineaciones( $idObjetivo )
    {
        $lstAlineaciones = array();
        if($idObjetivo) {
            $lstAlineaciones = $this->_getAlineacionesObjetivo( $idObjetivo );

            if($lstAlineaciones) {
                $this->_setNivelesAlineacion( $lstAlineaciones );
            }
        }

        return $lstAlineaciones;
    }

    /**
     * Recupera las alineaciones de un objetivo
     * @param type $idObjetivo
     * @return type
     */
    private function _getAlineacionesObjetivo( $idObjetivo )
    {
        $oAlineacion = new Alineacion();
        $lstAlineaciones = $oAlineacion->getAlineacionObjetivo( $idObjetivo );
        $this->_setNivelesAlineacion( $lstAlineaciones );
        return $lstAlineaciones;
    }

    /**
     * Pone los niveles a los alineaciones
     * @param type $lstAlineaciones
     */
    private function _setNivelesAlineacion( $lstAlineaciones )
    {
        foreach ( $lstAlineaciones AS $key => $alineacion ){
            $alineacion->idRegistro = $key;
            $lstNivelesbd = $this->_getNivelesAgenda( $alineacion->idAgenda );
            $lstItemsbd = $this->_getItemsAgenda( $alineacion );
            $alineacion->niveles = array();
            foreach ( $lstNivelesbd AS $nivel ){
                $oNivel = (object) null;
                $oNivel->id = $nivel->id;
                $oNivel->idPadre = $nivel->idPadre;
                $oNivel->nombre = $nivel->nombre;

                $this->_getHijoOfList( $lstItemsbd, $oNivel );
                $alineacion->niveles[] = $oNivel;
            }
        }
    }

    private function _getHijoOfList( $lstItemsbd, $oNivel )
    {
        if($lstItemsbd->idEstructura == $oNivel->id) {
            $oItem = (object) null;
            $oItem->descripcion = $lstItemsbd->nivel . ' ' . $lstItemsbd->descripcion;
            $oItem->idItem = $lstItemsbd->idItem;
            $oItem->nivel = $lstItemsbd->nivel;
            $oNivel->item = $oItem;
        } elseif($lstItemsbd->padre != null) {
            $this->_getHijoOfList( $lstItemsbd->padre, $oNivel );
        }
    }

    /**
     * 
     * @param type $idAgenda
     */
    private function _getNivelesAgenda( $idAgenda )
    {
        $oEstructura = new Estructura();
        $lstEstructura = $oEstructura->getEstructura( $idAgenda );
        return $lstEstructura;
    }

    /**
     * 
     * @param type $alineacion
     */
    private function _getItemsAgenda( $alineacion )
    {
        $item = $this->_getItem( $alineacion->idItem );

        $this->_getPadre( $item );
        return $item;
    }

    private function _getPadre( $item )
    {
        if($item->idPadre != 0) {
            $padre = $this->_getItem( $item->idPadre );
            if($padre->idPadre != 0) {
                $this->_getPadre( $padre );
            }
            $item->padre = $padre;
        }
    }

    /**
     * 
     * @param type $idItem
     * @return \Items
     */
    private function _getItem( $idItem )
    {
        $oItem = new Items();
        $dtaItem = $oItem->getItem( $idItem );
        return $dtaItem;
    }

}
