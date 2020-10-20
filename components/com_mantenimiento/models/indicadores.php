<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );
// import the Joomla modellist library
jimport( 'joomla.application.component.modellist' );

/**
 * HelloWorldList Model
 */
class MantenimientoModelIndicadores extends JModelList
{

    public function __construct( $config = array () )
    {
        if ( empty( $config['filter_fields'] ) ){
            //  Creamos un arreglo por con los campos por los cuales vamos a realizar los filtros, 
            //  en este caso seria nombre, descripcion, estado( publicado / despublicado )
            $config['filter_fields'] = array ( 'strNombre_pi' );
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
        foreach ( $items as &$item ){
            $item->url = JRoute::_( 'index.php?option=com_mantenimiento&amp;task=indicador.edit&amp;intId_pi=' . $item->idPtllaInd );
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
        //  Llama a la funcion getListQuery del padre en este caso del objeto JModelList, 
        //  el cual retorna un objeto que nos permite hacer una consulta a la BBDD        
        $query = parent::getListQuery();

        $query->select( '   t1.intId_pi                 AS idPtllaInd,
                            t1.inpCodigo_claseind       AS idClaseInd,
                            t2.strDescripcion_claseInd  AS claseInd,
                            t1.intCodigo_unimed         AS idUndMedida,
                            
                            CONCAT( t3.strDescripcion_unimed, " ( ", strSimbolo_unimed , " ) " ) AS undMedida,

                            t1.inpCodigo_unianl         AS idUndAnalisis,
                            t4.strDescripcion_unianl    AS undAnalisis,
                            UPPER( t1.strNombre_pi )    AS nombreIndicador,
                            t1.strDescripcion_pi        AS descripcionIndicador,
                            t1.strFormula_pi            AS formula,
                            t1.dteFechaRegistro_pi      AS fchRegistro,
                            t1.dteFechaModificacion_pi  AS fchModificacion,
                            t1.published                AS published,
                            u.name' );
        $query->from( '#__ptlla_indicador t1' );
        $query->join( 'INNER', '#__ind_clase_indicador t2 ON t1.inpCodigo_claseind = t2.inpCodigo_claseInd' );
        $query->join( 'INNER', '#__gen_unidad_medida t3 ON t1.intCodigo_unimed = t3.intCodigo_unimed' );
        $query->join( 'INNER', '#__gen_unidad_analisis t4 ON t1.inpCodigo_unianl = t4.inpCodigo_unianl' );

        //  Verificamos si algun registro esta siendo editado por algun usuario
        $query->leftJoin( '#__users as u on u.id = t1.checked_out' );

        //
        //  Filtro estado del contenido ( publicado / despublicado )
        //
        //  Setea el valor de la variable de session "filter.search" 
        //  obtenida desde el metodo populateState
        //
        $published = $this->getState( 'filter.published' );

        if ( $published == '' ){
            $query->where( '( t1.published = 1 OR t1.published = 0 )' );
        } else if ( $published != '*' ){
            $published = (int)$published;
            $query->where( "t1.published = '" . $published . "'" );
        }

        //
        //  Filtro por palabra o frase
        //
        //  Setea el valor de la variable de session "filter.search" 
        //  obtenida desde el metodo populateState
        $search = $this->getState( 'filter.search' );
        $db = $this->getDbo();

        if ( !empty( $search ) ){
            //  Prepara la cadena existente en la variable $search para que pueda ser ejecutada 
            //  en una sentencia Sql, con esto se previene de un ataque XSS
            $search = '%' . $db->getEscaped( $search, true ) . '%';

            $field_searches = " t1.strNombre_pi LIKE '" . $search . "' OR ";
            $field_searches .= " t1.descripcionIndicador LIKE '" . $search . "' OR ";
            $field_searches .= " t3.strDescripcion_unimed LIKE '" . $search . "' OR ";
            $field_searches .= " t4.strDescripcion_unianl LIKE '" . $search . "'";

            //  Adjuntamos via metodo where las opciones de busqueda registradas en al variable "$field_searches"
            $query->where( $field_searches );
        }

        //
        //  Filtro para ordenar por columna seleccionada
        //
        $orderCol = $this->getState( 'list.ordering' );
        $orderDirn = $this->getState( 'list.direction' );

        if ( $orderCol != '' ){
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
