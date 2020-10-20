<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import the Joomla modellist library
jimport('joomla.application.component.modellist');

/**
 * HelloWorldList Model
 */
class mantenimientoModelInstituciones extends JModelList
{
    public function __construct( $config = array() ) 
    {
        if( empty( $config['filter_fields'] ) ){
            //  Creamos un arreglo por con los campos por los cuales vamos a realizar los filtros, 
            //  en este caso seria nombre, descripcion, estado( publicado / despublicado )
            $config['filter_fields'] = array(   'intCodigo_ins', 
                                                'inpCodigo_depins',
                                                'inpCodigo_sec',
                                                'intCodigoFuncion',
                                                'intCodigoNorma',
                                                'intCodigoTipo_ins',
                                                'strNombre_ins',
                                                'strRuc_ins',
                                                'strAlias_ins',
                                                'strFuncionMandato_ins',
                                                'strNumNorma_ins',
                                                'strRegistroOficial_ins',
                                                'dteFechaRegOficial',
                                                'strMision',
                                                'strVision',
                                                'strObservacion_ins',
                                                'strDescripcion_ag');
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
            $item->url = JRoute::_('index.php?option=com_mantenimiento&amp;task=institucion.edit&amp;id='. $item->intCodigo_ins );
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
        
        $query->select( '   ins.intCodigo_ins,
                            ins.inpCodigo_depins,
                            ins.inpCodigo_sec,
                            ins.intCodigoFuncion,
                            ins.intCodigoNorma,
                            ins.intCodigoTipo_ins,
                            ins.strNombre_ins,
                            ins.strRuc_ins,
                            ins.strAlias_ins,
                            ins.strFuncionMandato_ins,
                            ins.strNumNorma_ins,
                            ins.strRegistroOficial_ins,
                            ins.dteFechaRegOficial,
                            ins.strMision,
                            ins.strVision,
                            ins.strObservacion_ins,
                            ins.published,
                            dep.strDescripcion_depins,
                            sec.strDescripcion_sec,
                            func.strDescripcion_funcion,
                            norm.strDescripcion_norma,
                            ti.strDescripcionTipo_ins,
                            u.name' );
        $query->from( '#__gen_institucion ins' );

        //  Verificamos si algun registro esta siendo editado por algun usuario
        $query->leftJoin( '#__pei_dependencia  as dep on dep.inpCodigo_depins = ins.inpCodigo_depins' );
        $query->leftJoin( '#__gen_sector as sec on sec.inpCodigo_sec = ins.inpCodigo_sec' );
        $query->leftJoin( '#__pei_funcion as func on func.intCodigofuncion = ins.intCodigofuncion' );
        $query->leftJoin( '#__pei_tipo_norma as norm on norm.intCodigoNorma = ins.intCodigoNorma' );
        $query->leftJoin( '#__gen_tipo_institucion as ti on ti.intCodigoTipo_ins = ins.intCodigoTipo_ins' );
       
        
        $query->leftJoin( '#__users as u on u.id = ins.checked_out' );
        
        //
        //  Filtro estado del contenido ( publicado / despublicado )
        //
        //  Setea el valor de la variable de session "filter.search" 
        //  obtenida desde el metodo populateState
        //
        $published = $this->getState( 'filter.published' );
        
        if( $published == '' ){
            $query->where( '( ins.published = 1 OR ins.published = 0 )' );
        } else if( $published != '*' ){
            $published = (int)$published;
            $query->where( "ins.published = '". $published ."'" );
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

//            $field_searches = " ins.strNombre_ins LIKE '". $search ."' OR"
//                            . " ins.strRuc_ins LIKE '". $search ."' OR"
//                            . " ins.strAlias_ins LIKE '". $search ."' OR"
//                            . " ins.strFuncionMandato_ins LIKE '". $search ."' OR"
//                            . " ins.strNumNorma_ins LIKE '". $search ."' OR"
//                            . " ins.dteFechaRegOficial LIKE '". $search ."' OR"
//                            . " ins.strMision LIKE '". $search ."' OR"
//                            . " ins.strVision LIKE '". $search ."' OR"
//                            . " ins.strObservacion_ins LIKE '". $search ."'";
            
            //  Adjuntamos via metodo where las opciones de busqueda registradas en al variable "$field_searches"
//            $query->where( $field_searches );
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