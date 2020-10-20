<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
/**
 * Etapas Model
 */
class ProyectosModelProyectos extends JModelList
{
    /**
    * Items total
    * @var integer
    */
    var $_total = null;

    /**
    * Pagination object
    * @var object
    */
    var $_pagination = null;
    
    public function __construct( $config = array() ) 
    {
        if( empty( $config['filter_fields'] ) ){
            //  Creamos un arreglo por con los campos por los cuales vamos a realizar los filtros, 
            //  en este caso seria nombre, descripcion, estado( publicado / despublicado )
            $config['filter_fields'] = array(   'strDescripcion_cb',
                                                'published' );
        }
        
        parent::__construct( $config );
        
        $mainframe = JFactory::getApplication();
 
        // Get pagination request variables
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
 
        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
 
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
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
            $item->url = JRoute::_('index.php?option=com_proyectos&amp;task=proyecto.edit&amp;intCodigo_pry='. $item->intCodigo_pry . '&amp;intIdEntidad_ent='. $item->intIdEntidad_ent );
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
        
        $query->select( '   t1.intCodigo_pry,
                            t1.intIdEntidad_ent,
                            t1.strCodigoInterno_pry,
                            t1.strNombre_pry,
                            t2.strNombre_prg,
                            t1.dteFechaInicio_stmdoPry,
                            t1.dteFechaFin_stmdoPry,
                            t1.dcmMonto_total_stmdoPry,
                            t1.published,
                            u.name' );
        
        $query->from( '#__pfr_proyecto_frm t1' );
        $query->join( 'INNER', '#__pfr_programa t2 ON t1.intCodigo_prg = t2.intCodigo_prg' );
        $query->join( 'INNER', '#__pry_ug_responsable t3 ON t1.intCodigo_pry = t3.intCodigo_pry' );
        
        //  Verificamos si algun registro esta siendo editado por algun usuario
        $query->leftJoin( '#__users as u on u.id = t1.checked_out' );
        
        //
        //  Filtro estado del contenido ( publicado / despublicado )
        //
        //  Setea el valor de la variable de session "filter.search" 
        //  obtenida desde el metodo populateState
        //
        $published = $this->getState( 'filter.published' );
        
        if( $published == '' ){
            $query->where( '( t1.published = 1 OR t1.published = 0 )' );
        } else if( $published != '*' ){
            $published = (int)$published;
            $query->where( "c.published = '". $published ."'" );
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

            $field_searches = " t1.strNombre_pry LIKE '". $search ."' OR 
                                t2.strNombre_prg LIKE '". $search ."'";
            
            //  Adjuntamos via metodo where las opciones de busqueda registradas en al variable "$field_searches"
            $query->where( $field_searches );
        }
        
        if( JFactory::getUser()->id != 147 ){
            $query->where( 't3.intCodigo_ug IN (    SELECT DISTINCT tb.intCodigo_ug
                                                    FROM #__gen_funcionario ta
                                                    JOIN #__gen_ug_funcionario tb ON ta.intCodigo_fnc = tb.intCodigo_fnc
                                                    WHERE ta.intIdUser_fnc = '. JFactory::getUser()->id .' )' );
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