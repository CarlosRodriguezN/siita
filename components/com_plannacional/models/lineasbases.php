<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
/**
 * HelloWorldList Model
 */
class plannacionalModelLineasbases extends JModelList
{
    public function __construct( $config = array() ) 
    {
        if( empty( $config['filter_fields'] ) ){
            //  Creamos un arreglo por con los campos por los cuales vamos a realizar los filtros, 
            //  en este caso seria nombre, descripcion, estado( publicado / despublicado )
            $config['filter_fields'] = array(   'intCodigo_lb', 
                                                'intCodigo_in',
                                                'intCodigo_per',
                                                'intCodigo_uniMed',
                                                'strDescripcion_lb',
                                                'dcmValor_lb',
                                                'dteFechaIni_lb',
                                                'dteFechaFin_lb',
                                                'strFuente_lb'
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
            $item->url = JRoute::_('index.php?option=com_plannacional&amp;task=lineabase.edit&amp;intCodigo_lb='. $item->intCodigo_lb );
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
        
        $query->select( '   liba.intCodigo_lb,
                            liba.intCodigo_in,
                            inna.strDescripcion_in,
                            liba.intCodigo_per,
                            peri.strDescripcion_per,
                            liba.intCodigo_uniMed,
                            unime.strDescripcion_uniMed,
                            liba.strDescripcion_lb,
                            liba.dcmValor_lb,
                            liba.dteFechaIni_lb,
                            liba.dteFechaFin_lb,
                            liba.strFuente_lb,
                            liba.published,
                            u.name' );
        $query->from( '#__pnd_linea_base as liba' );

        //  Verificamos si algun registro esta siendo editado por algun usuario
        $query->leftJoin( '#__pnd_indicador_nacional as inna on inna.intCodigo_in = liba.intCodigo_in' );
        $query->leftJoin( '#__gen_periodicidad as peri on peri.intCodigo_per = liba.intCodigo_per' );
        $query->leftJoin( '#__gen_unidad_medida as unime on unime.intCodigo_uniMed = liba.intCodigo_uniMed' );
        $query->leftJoin( '#__users as u on u.id = liba.checked_out' );
        
        //
        //  Filtro estado del contenido ( publicado / despublicado )
        //
        //  Setea el valor de la variable de session "filter.search" 
        //  obtenida desde el metodo populateState
        //
        $published = $this->getState( 'filter.published' );
        
        if( $published == '' ){
            $query->where( '( liba.published = 1 OR liba.published = 0 )' );
        } else if( $published != '*' ){
            $published = (int)$published;
            $query->where( "liba.published = '". $published ."'" );
        }
        
        //
        //  Filtro por palabra o frase
        //
        //  Setea el valor de la variable de session "filter.search" 
        //  obtenida desde el metodo populateState
        $search = $this->getState( 'filter.search' );
        $db = $this->getDbo();
        
        if( !empty ( $search ) ){
            //  Prepara la cadena existente en la variable $search para que pueda ser ejecutada 
            //  en una sentencia Sql, con esto se previene de un ataque XSS
            $search = '%'. $db->getEscaped( $search, true ) .'%';

            $field_searches = " liba.strDescripcion_in LIKE '". $search ."' OR"
                            . " liba.strDescripcion_per LIKE '". $search ."' OR"
                            . " liba.strDescripcion_uniMed LIKE '". $search ."' OR"
                            . " liba.strDescripcion_lb LIKE '". $search ."' OR"
                            . " liba.dcmValor_lb LIKE '". $search ."' OR"
                            . " liba.dteFechaIni_lb LIKE '". $search ."' OR"
                            . " liba.dteFechaFin_lb LIKE '". $search ."' OR"
                            . " liba.strFuente_lb LIKE '". $search ."'";
            
            //  Adjuntamos via metodo where las opciones de busqueda registradas en al variable "$field_searches"
            $query->where( $field_searches );
        }
        
        //
        //  Filtro para ordenar por columna seleccionada
        //
        $orderCol = $this->getState( 'list.ordering' );
        $orderDirn = $this->getState( 'list.direction' );
        
        if( $orderCol != '' ){
            $query->order( $db->getEscaped( $orderCol. ' ' .$orderDirn ) );
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
        $search = $this->getUserStateFromRequest( $this->context.'.filter.search', 'filter_search' );
        $this->setState( 'filter.search', $search );
        
        $published = $this->getUserStateFromRequest( $this->context.'.filter.published', 'filter_published' );
        $this->setState( 'filter.published', $published );
        
        parent::populateState( $ordering, $direction );
    }
}