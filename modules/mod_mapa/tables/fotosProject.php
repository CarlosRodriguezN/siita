<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

//require_once JPATH_BASE . DS . 'modules' . DS . 'mod_mapa' . DS . 'models' . DS . 'coordenadas.php';
class JTableFotosProject extends JTable
{

    public function __construct( &$db )
    {
        parent::__construct( '#__pfr_images_proyecto', 'intId_img', $db );

        $this->access = (int)JFactory::getConfig()->get( 'access' );
    }

    /**
     * 
     * @name getFotosProy
     * @param int $idPRY Identificador de un proyecto.
     * @return array
     * 
     */
    function getFotosProy( $idPRY )
    {
        try{
            $db = JFactory::getDbo();
            $query = $db->getQuery( true );
            //se arma el select
            $query->select( "img.intId_img,img.intCodigo_pry,img.strNombre_img" );
            $query->from( '#__pfr_images_proyecto AS img ' ); //tg_coordenadas ... es la relacion entre el pryecto y la coordenada
            $query->where( "img.intCodigo_pry='{$idPRY}'" );
            $db->setQuery( $query );
            $figs = $db->loadObjectList();
        } catch ( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'ecorae.database.tables.coordenadas.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
        return (array_values( $figs ));
    }

}
