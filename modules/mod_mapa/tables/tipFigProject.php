<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
require_once JPATH_BASE . DS . 'modules' . DS . 'mod_mapa' . DS . 'models' . DS . 'coordenadas.php';

class JTableTipFigProject extends JTable
{

    public function __construct( &$db )
    {
        parent::__construct( '#__pfr_grafico', 'intIdGrafico_pfr', $db );

        $this->access = (int) JFactory::getConfig()->get( 'access' );
    }

    /**
     * 
     * @name getCoordenadas
     * @param int $idPRY Identificador de el gráfico de un proyecto.
     * @return array
     * 
     */
    function getFigsProy( $idPRY )
    {
        try {
            if($idPRY){
                $db = JFactory::getDbo();
                $query = $db->getQuery( true );
                
                // Se arma el select
                $query->select( '   tc.intIdGrafico_pfr AS idFig,
                                    tc.intId_tg' );
                $query->from( '#__pfr_grafico AS tc ' ); 
                $query->where( "tc.intCodigo_pry='{$idPRY}'" );
                
                $db->setQuery( $query );
                
                $figs = ($db->loadObjectList()) ? $db->loadObjectList()
                                                : false;
            }
            
            $figJSON = array();

            if( $figs ) {
                $figJSON = $this->_figProyObjectConvert( $figs );
            }
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.coordenadas.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        return (array_values( $figJSON ));
    }

    /**
     * 
     * @name _figProyObjectConvert
     * @param type $figuras
     * @return type
     */
    function _figProyObjectConvert( $figuras )
    {
        $figsJSON = array( );
        try {
            if( $figuras ) {
                foreach( $figuras as $figura ) {
                    $figJSON = (object) null;
                    $figJSON->idTipFig = $figura->intId_tg;
                    $figJSON->figpoints = self::getPointsFig( $figura->idFig );
                    $figsJSON[$figura->idFig] = $figJSON;
                }
            }
        } catch(Exception $e) {
            echo $e->getMessage();
        }
        return $figsJSON;
    }

    /**
     * 
     * @name getPointsGrafic
     * @param string $idPryGraf
     */
    function getPointsFig( $idPryGraf )
    {//retorna los puntos de un gráfico.
        try {
            //llamamos al modelo de coordenadas
            $model = new CoordenadasModel();
            // llamamos la funcion getCoordenadas del modelo le pasamos el 
            // id de un de los grafico de el proyecto
            return $model->getCoordenadas( $idPryGraf );
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.coordenadas.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

}

