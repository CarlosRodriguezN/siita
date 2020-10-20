    <?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla Atribtuo ( #__ctr_atributo )
 * 
 */
class contratosTableCoordenada extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__ctr_coordenadas_grafico', 'intId_cg', $db);
    }

    function getCoordenadasGrafico($idGrafico) {
        try {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select(" 
                            intId_cg            AS  idCoordenada,
                            intIdGrafico_crtg	AS  idGrafico,
                            fltLatitud_cord     AS  lat,
                            fltLongitud_cord	AS  lng,
                            published           AS  published
                            ");
            $query->from("#__ctr_coordenadas_grafico AS cor");
            $query->where("intIdGrafico_crtg=" . $idGrafico);
            $query->where("published = 1");
            $db->setQuery($query);
            $db->query();

            $retval = ( $db->getNumRows() > 0 ) ? $db->loadObjectList() : false;
            return $retval;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.cargo.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

    /**
     * Almacena o edita las coordendas de un gráfico. 
     * @param int $idGrafico  Identificador del gráfico
     * @param object $Coordendada Objeto coordenada.
     * @return int
     */
    function saveCoordenadaGrafico($idGrafico, $Coordendada) {
        try {
            $data["intId_cg"] = $Coordendada->idCoordenada;
            $data["fltLatitud_cord"] = $Coordendada->lat;
            $data["fltLongitud_cord"] = $Coordendada->lng;
            $data["intIdGrafico_crtg"] = $idGrafico;
            $data["published"] = $Coordendada->published;
            if ($this->bind($data)) {
                if ($this->save($data)) {
                    $retval = $this->intId_cg;
                }
            }
            return $retval;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_contratos.table.coordenadaGrafico.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }

}
