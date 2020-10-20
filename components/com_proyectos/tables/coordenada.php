<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla Fase ( #__prf_etapa )
 * 
 */
class ProyectosTableCoordenada extends JTable
{
    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db)
    {
        parent::__construct('#__pfr_coordenadas_grafico', 'intIdCoordenada_pfr', $db);
    }

    /**
     * 
     * @param type $idGrafico
     * @param type $coordenada
     */
    public function saveCoordenadaGrafico( $idGrafico, $coordenada )
    {
        $idCoordenada = 0;
        $data = array();
        $data['intIdCoordenada_pfr'] = (int) $coordenada->idCoordenada;
        $data['intIdGrafico_pfr'] = (int) $idGrafico;
        $data['fltLatitud_cord'] = (double) $coordenada->latitud;
        $data['fltLongitud_cord'] = (double) $coordenada->longitud;
        $data['published'] = (int) $coordenada->published;
        if ($this->save($data)) {
            $idCoordenada = $this->intIdCoordenada_pfr;
           }
        return $idCoordenada;
    }

    /**
     * 
     * @param type $idGrafico
     * @return type
     */
    public function getCoordenadasGrafico( $idGrafico )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   intIdCoordenada_pfr AS  idCoordenada,
                                fltLongitud_cord    AS  longitud,
                                fltLatitud_cord     AS  latitud,
                                published           AS  published');
            $query->from( '#__pfr_coordenadas_grafico' );
            $query->where( 'intIdGrafico_pfr = ' . $idGrafico );
            $query->where( 'published = 1' );
            $db->setQuery( $query );
            $db->query();

            $lstDimensiones = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() 
                                                        : FALSE;

            return $lstDimensiones;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    public function delCoordenadaGrafico($idCoordenada)
    {
        try {
            if ((int) $idCoordenada != 0) {
                $db = & JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->delete('#__pfr_coordenadas_grafico');
                $query->where('intIdCoordenada_pfr=' . $idCoordenada);
                $db->setQuery($query);
                $db->query();
            }
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_canastaproy.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    public function delCoordenadasGrafico($idGrafico){
       try {
            if ((int) $idGrafico != 0) {
                $db = & JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->delete('#__pfr_coordenadas_grafico');
                $query->where('intIdGrafico_pfr=' . $idGrafico);
                $db->setQuery($query);
                $db->query();
            }
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_canastaproy.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        } 
    }

}