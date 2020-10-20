<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import the Joomla modellist library
jimport( 'joomla.application.component.modellist' );

require_once JPATH_BASE . DS . 'administrator' . DS .'components' . DS . 'com_unidadgestion' . DS . 'helpers' . DS . 'unidadgestion.php';

/**
 * Unidad de Gestion List Model
 */
class UnidadGestionModelUnidadesGestion extends JModelList
{

    public function __construct( $config = array() )
    {
        if( empty( $config[ 'filter_fields' ] ) ){
            //  Creamos un arreglo por con los campos por los cuales vamos a realizar los filtros, 
            //  en este caso seria nombre, descripcion, estado( publicado / despublicado )
            $config[ 'filter_fields' ] = array( 'intCodigo_ug',
                'intIdentidad_ent',
                'tb_intCodigo_ug',
                'intCodigo_ins',
                'strNombre_ug',
                'strAlias_ug',
                'published'
            );
        }

        parent::__construct( $config );
    }

    public function getItems()
    {
        //  Joomla ejecuta la funcion getItems de la clase JModelList
        //  la cual por defecto busca la funcion "getListQuery" y 
        //  retorna la informacion dentro de un objeto
        $items = parent::getItems();

        //  Recorro dicho objeto y a la propiedad "url" le asigno la url 
        //  de edicion de cada elemento de este conjunto de resultados
        //  Lo hacemos de esta manera para no hacerlo en la vista, 
        //  con esto mantenemos a nuestra vista ordenada
        foreach( $items as &$item ){
            $item->url = JRoute::_( 'index.php?option=com_unidadgestion&amp;task=unidadgestion.edit&amp;intCodigo_ug=' . $item->intCodigo_ug );
        }

        return $items;
    }

    /**
     *
     * Retorno informacion de la tabla "fases".
     * 
     * @return Object 
     */
    public function getListQuery()
    {
        $lstGruposUsr = JFactory::getUser()->groups; 
        $canDo = UnidadGestionHelper::getActions();
        
        //  Llama a la funcion getListQuery del padre en este caso del objeto JModelList, 
        //  el cual retorna un objeto que nos permite hacer una consulta a la BBDD        
        $query = parent::getListQuery();
        $query->select( '   ug.intCodigo_ug,
                            ug.intIdentidad_ent,
                            ug.tb_intCodigo_ug,
                            ugp.strNombre_ug,
                            ug.intCodigo_ins,
                            ug.strNombre_ug,
                            ug.strAlias_ug,
                            ug.published,
                            u.name' );
        $query->from( '#__gen_unidad_gestion as ug' );

        //  Verificamos si algun registro esta siendo editado por algun usuario
        $query->leftJoin( '#__gen_unidad_gestion as ugp on ugp.intCodigo_ug = ug.intCodigo_ins' );
        $query->leftJoin( '#__users as u on u.id = ug.checked_out' );
        //  Filtramos por la institucion de ecorae con id igual a 1
        $query->where( 'ug.intCodigo_ins = 1' );
        $query->where( 'ug.intCodigo_ug > 0' );
        
        if ( !$canDo->get('core.create') && !$canDo->get('core.edit') && !$canDo->get('core.delete')  ) {
            $query->where( 'ug.intIdGrupo_ug IN ( '. implode( ", ", $lstGruposUsr ) .' )' );
        }
        
        //
        //  Filtro estado del contenido ( publicado / despublicado )
        //
        //  Setea el valor de la variable de session "filter.search" 
        //  obtenida desde el metodo populateState
        //
        $published = $this->getState( 'filter.published' );

        if( $published == '' ){
            $query->where( '( ug.published = 1 OR ug.published = 0 )' );
        }else if( $published != '*' ){
            $published = (int)$published;
            $query->where( "ug.published = '" . $published . "'" );
        }

        //
        //  Filtro por palabra o frase
        //
        //  Setea el valor de la variable de session "filter.search" 
        //  obtenida desde el metodo populateState
        $search = $this->getState( 'filter.search' );
        $db = $this->getDbo();

        if( !empty( $search ) ){
            //  Prepara la cadena existente en la variable $search para que pueda ser ejecutada 
            //  en una sentencia Sql, con esto se previene de un ataque XSS
            $search = '%' . $db->getEscaped( $search, true ) . '%';

            $field_searches = " ug.strNombre_ug LIKE '" . $search . "' OR"
                    . " ug.strAlias_ug LIKE '" . $search . "'";

            //  Adjuntamos via metodo where las opciones de busqueda registradas en al variable "$field_searches"
            $query->where( $field_searches );
        }

        //
        //  Filtro para ordenar por columna seleccionada
        //
        $orderCol = $this->getState( 'list.ordering' );
        $orderDirn = $this->getState( 'list.direction' );

        if( $orderCol != '' ){
            $query->order( $db->getEscaped( $orderCol . ' ' . $orderDirn ) );
        }

        return $query;
    }

    /**
     *
     * Se encarga de gestionar ( capturar / actualizar ) las variables para los diferentes filtros 
     * 
     * @param type $ordering    define el orden para la presentacion de la informacion ( Ascendente / Descendente )
     * @param type $direction
     * 
     */
    protected function populateState( $ordering = null, $direction = null )
    {
        //
        //  Crea la variable de session "context.filter.search", y setea el valor ingresado por el usuario 
        //  en la caja de texto llamada "filter_search", en caso de existir esta variable simplemente actualiza 
        //  el contenido
        //
        
        $search = $this->getUserStateFromRequest( $this->context . '.filter.search', 'filter_search' );
        $this->setState( 'filter.search', $search );

        $published = $this->getUserStateFromRequest( $this->context . '.filter.published', 'filter_published' );
        $this->setState( 'filter.published', $published );

        parent::populateState( $ordering, $direction );
    }

}