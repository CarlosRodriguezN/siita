<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
/**
 * Etapas Model
 */
class ProyectosModelLineasBases extends JModelList
{
    public function __construct( $config = array() ) 
    {
        if( empty( $config['filter_fields'] ) ){
            //  Creamos un arreglo por con los campos por los cuales vamos a realizar los filtros, 
            //  en este caso seria nombre, descripcion, estado( publicado / despublicado )
            $config['filter_fields'] = array(   'strDescripcion_cb',
                                                'published' );
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
            $item->url = JRoute::_('index.php?option=com_proyectos&amp;task=lineabase.edit&amp;intCodigo_lbind='. $item->intCodigo_lbind );
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
        
        $query->select( '   t1.intCodigo_lbind AS idLineaBase,
                            t1.strDescripcion_lbind AS descripcion,
                            t1.dcmValor_lbind AS valor,
                            t4.strNombre_ins AS institucion,
                            t1.dteFechaInicio_lbind as fchInicio,
                            t1.dteFechaFin_lbind as fchFin,
                            t5.strdescripcion_per as periodicidad,
                            t1.published,
                            u.name' );
        $query->from( '#__ind_linea_base t1' );
        
        //  Relaciono Indicador con una fuente
        $query->join( 'LEFT', '#__ind_fuente t2 ON t1.intCodigo_fuente = t2.intCodigo_fuente' );
        
        //  Relaciono Indicador con una unidad de Gestion
        $query->join( 'LEFT', '#__gen_unidad_gestion t3 ON t2.intCodigo_ug = t3.intCodigo_ug' );
        
        //  Relaciono Indicador con Institucion
        $query->join( 'LEFT', '#__gen_institucion t4 ON t3.intCodigo_ins = t4.intCodigo_ins' );
        
        //  Relaciono Indicador con Periodicidad
        $query->join( 'LEFT', '#__gen_periodicidad t5 ON t5.intcodigo_per = t1.intcodigo_per' );
        
        //  Verificamos si algun registro esta siendo editado por algun usuario
        $query->join( 'LEFT', '#__users as u on u.id = t1.checked_out' );
        
        //  Verificamos si algun registro esta siendo editado por algun usuario
        $query->join( 'LEFT', '#__ind_indicador as t6 on t1.intCodigo_lbind = t6.intCodigo_lbind' );
        
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
            $query->where( "t1.published = '". $published ."'" );
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

            $field_searches = "t1.strDescripcion_lbind LIKE '". $search ."' OR "
                             ."t4.strNombre_ins LIKE '". $search ."'";
            
            //  Adjuntamos via metodo where las opciones de busqueda registradas en al variable "$field_searches"
            $query->where( $field_searches );
        }
        
        //  $query->where( 't6.intCodigo_ind = '. JRequest::getVar( 'idRegIndicador' ) );
        
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
        
        $idFuente = $this->getUserStateFromRequest( $this->context.'.idFuente', 'idFuente' );
        $this->setState( 'idFuente', $idFuente );
        
        parent::populateState( $ordering, $direction );
    }

    
    public function getLstInstituciones( $idIndicador = 0 )
    {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   intCodigo_ins AS idInstitucion, 
                                strNombre_ins AS nombre' );
            $query->from( '#__gen_institucion' );            
            $query->where( 'published = 1' );
            
            $db->setQuery( (string)$query );
            $db->query();
            
            $rst = ( $db->getNumRows() > 0 )? $db->loadObjectList()
                                            : false;

            return $rst;
        } 
        catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_proyectos.tables.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
}
