<?php

defined( '_JEXEC' ) or die;

require_once JPATH_BASE . DS . 'modules' . DS . 'mod_mapa' . DS . 'models' . DS . 'mapaDPA.php';
require_once JPATH_BASE . DS . 'modules' . DS . 'mod_mapa' . DS . 'models' . DS . 'programas.php';
require_once JPATH_BASE . DS . 'modules' . DS . 'mod_mapa' . DS . 'models' . DS . 'wms.php';
JHtml::_( 'behavior.formvalidation' );

class modMapaHelper
{

    /**
     * @abstract Recupera las unidades terririasles 
     * @return type
     */
    static function getList()
    {
        $model = new mapaDPAModel();
        return $model->getTiposUniTerritoriales();
    }

    /**
     * @abstract Recupera las listas de programas de una unidad territorial
     * @param int $id identificador de la unidad territorial
     * @param int $tut identificador de el tipo de la unidad territorial
     * @return JSON
     */
    static function getProgramas( $id, $tut )
    {
        $model = new programasModel();
        return $model->getProgramasToJSON2( $id, $tut );
    }

    /**
     * @abstract Reculpera la lista de los tipos de unidadaes territoriales.
     * @param int $tipoUnidadTerritorial identificador de el tipo de unidad territorial.
     * @return array
     */
    static function getRegiones( $tipoUnidadTerritorial )
    {
        $model = new mapaDPAModel();
        return array_values( $model->getUnidadesTerritoriales2( $tipoUnidadTerritorial ) );
    }

    /**
     * @abstract Recuperas la provincias que le pertenecen a una región 
     * @param type $idRegion identificador de la provincia que se desea caragar
     * @return Array
     */
    static function getProvincias( $idRegion )
    {
        $model = new mapaDPAModel();
        return array_values( $model->getProvinciasPorZona2( $idRegion ) );
    }

    /**
     * @abstract recupera la lista de las wms. para el arbol wms
     * @return type JSON
     */
    static function getWMS()
    {
        $model = new WMSModel();
        return $model->getWMS();
    }

}
